<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\visa_booking;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\activity_booking;
use App\Models\rail_booking;
use App\Models\hotel_booking;
use App\Models\booking_agent;
use App\Models\booking_tour;
use App\Models\passenger;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class  BookingController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
    
        // Retrieve the credentials from the request
        $credentials = $request->only('email', 'password');
    
        // Attempt to authenticate the user
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid email or password',
            ], 401);
        }
        // Get the authenticated user
        $user = Auth::user();
        $user->makeHidden(['created_at', 'updated_at']); // Hide timestamps if needed
    
        return response()->json([
            'user' => [
                'email' => $user->email, 
                'country' => $user->country,
                'division' => $user->division,
                'district' => $user->district, 
                'address' => $user->address,
                'contact_no' => $user->contact_no, 
                'company_name' => $user->company_name, 
                'company_persons_name' => $user->company_persons_name, 
                'currency' => $user->currency, 
                'balance' => $user->balance, 
                'credit_balance' => $user->credit_balance, 
                'status' => $user->status, 
                'token' => Auth::tokenById($user->id), 
            ],
            'message' => 'Successfully logged in',
        ]);
    }
    
        public function register(Request $request)
        {
            // Validate the request
            $request->validate([
                'emailID' => 'required|string|email|max:255|unique:users,emailID,' . $request->emailID,
                'address' => 'nullable|string|max:255',
                'division' => 'nullable|string|max:255',
                'country' => 'nullable|string|max:255',
                'company_name' => 'nullable|string|max:255',
                'company_persons_name' => 'nullable|string|max:255',
                'district' => 'nullable|string|max:255',
                'contact_no' => 'nullable|string|max:15', // Adjust max length as needed
                'currency' => 'nullable|string|max:3', // Currency typically short (e.g., BDT)
                'balance' => 'nullable|numeric', // Balance should be numeric
                'credit_balance' => 'nullable|numeric', // Credit balance should be numeric
                'status' => 'nullable|string|max:20', // Status should have a reasonable length
                'password' => 'required|string|min:8', // Password must be present and at least 8 characters long
            ], [
                'email.unique' => 'The email has already been taken.',
            ]);
        
            // Create the user
            $user = User::create([
                'email' => $request->email,
                'mobile' => $request->mobile,
                'address' => $request->address,
                'division' => $request->division,
                'country' => $request->country,
                'company_name' => $request->company_name,
                'company_persons_name' => $request->company_persons_name,
                'district' => $request->district,
                'contact_no' => $request->contact_no,
                'currency' => $request->currency,
                'balance' => $request->balance ?? 0.00, // Default balance to 0 if not provided
                'credit_balance' => $request->credit_balance ?? 0.00, // Default credit balance to 0 if not provided
                'status' => $request->status ?? 'active', // Default status to 'active' if not provided
                'password' => bcrypt($request->password),
                // 'role' => $request->role ?? 'user', // Uncomment if roles are required
            ]);
        
            return response()->json([
                'message' => 'User created successfully',
                // 'user' => $user // Uncomment if you want to return user details
            ]);
        }
        
        
   private static $book;
    public function store(Request $request)
    {
     try {
        $validatedData = $request->validate([        
            'user_id' => 'exists:users,id', 
            'contactNo' => 'nullable|string',
            'contactNo2' => 'nullable|string',
            'IP' => 'nullable|string',
            'email' => 'nullable|email',
            'cityCode' => 'nullable|string',
            'countryCode' => 'nullable|string',
            'addressLine1' => 'nullable|string',
            'addressLine2' => 'nullable|string',
            'pnr' => 'required|string|unique:bookings,pnr', 
            'bookingId' => 'required|string|unique:bookings,bookingId',
            'isDomestic' => 'nullable|integer',
            'origin' => 'nullable|string',
            'destination' => 'nullable|string',
            'airlineCode' => 'nullable|string',
            'airlineRemark' => 'nullable|string',
            'isLCC' => 'nullable|string',
            'nonRefundable' => 'nullable|integer',
            'invoiceAmount' => 'nullable|string',
            'invoiceNo' => 'nullable|string',
            'invoiceCreatedOn' => 'nullable|string',
            'fareRules' => 'nullable|string',
            'errorCode' => 'nullable|string',
            'errorMessage' => 'nullable|string',
            'isTicket' => 'nullable|integer',
            'hold' => 'nullable|string',
            'offer' => 'nullable|numeric',
            'public' => 'nullable|numeric',
            'baseFare' => 'nullable|numeric',
            'otherFare' => 'nullable|numeric',
            'offerFare' => 'nullable|numeric',
            'publicFare' => 'nullable|numeric',
            'agent_public_price' => 'nullable|numeric',
            'agent_offer_price' => 'nullable|numeric',
            'agent_commission' => 'nullable|numeric',
            'agent_tds' => 'nullable|numeric',
            'agent_invoice_price' => 'nullable|numeric',
            'agent_net_receivable' => 'nullable|numeric',
            'account_fare' => 'nullable|numeric',
            'my_net_receivable' => 'nullable|numeric',
            'jdate' => 'nullable|string',
            'cancelID' => 'nullable|string',
            'voidID' => 'nullable|string',
            'RefID' => 'nullable|integer',
            'ReissueID' => 'nullable|integer',
            'SsrID' => 'nullable|string',
            'TicketId' => 'nullable|string',
            'CreditNoteNo' => 'nullable|string',
            'ChangeRequestStatus' => 'nullable|integer',
            'addedBy' => 'nullable|integer',
            'agntCurrency' => 'nullable|string',
            'agency' => 'nullable|string',
            'acceptBy' => 'nullable|integer',
            'lead_pax' => 'nullable|string',
            'surname' => 'nullable|string',
            'ppr' => 'nullable|integer',
            'partial_pay' => 'nullable|integer',
            'due_amt' => 'nullable|integer',
            'request_for' => 'nullable|string',
            'copy' => 'nullable|string',
            'sourceID' => 'nullable|integer',
            'void_pass' => 'nullable|string',
            'cancel_pass' => 'nullable|string',
            'cancel_leg' => 'nullable|string',
            'refund_pass' => 'nullable|string',
            'refund_leg' => 'nullable|string',
            'reissue_pass' => 'nullable|string', 
            'ssr_leg' => 'nullable|string',
            'ssr_pass' => 'nullable|string',
            'sb_bag' => 'nullable|string',
            'remark_book' => 'nullable|string',
            'pcc_currency' => 'nullable|string',
            'flt_class' => 'nullable|string',
            'pass_copy' => 'nullable|string',
            'refund_point' => 'nullable|integer',
            'reissue_point' => 'nullable|integer',
            'agent_bal' => 'nullable|numeric',
            'agent_crit' => 'nullable|numeric',
            'subID' => 'nullable|integer'
            
        ]);
        $visaBooking = booking::create($validatedData);
        return response()->json([
            'message' => 'Booking created successfully',
        
        ], 201);
    } catch (ValidationException $e) {
        return response()->json([
            'message' => 'Validation failed.',
            'errors' => $e->errors(),  
        ], 422);  
    }
}

    public function show()
        {
            
            $bookings = Booking::all(); 
            return response()->json([
                'success' => true,
                'data' => $bookings,
                'message' => 'Booking data retrieved successfully'
            ], 200);
        }

    public function visa_store(Request $request)
    { 
        try {
            $validatedData = $request->validate([
                'ref_no' => 'required|string|max:225',
                'country' => 'required|string|max:225',
                'visa_type' => 'required|string|max:225',
                'entry_type' => 'nullable|string|max:225',
                'visa_duration' => 'nullable|string|max:225',
                'processing_duration' => 'nullable|string|max:225',
                'visa_charge' => 'nullable|string|max:225',
                'require_document' => 'nullable|string',
                'jstatus' => 'nullable|date',
                'acceptBy' => 'nullable|int',
                'visa_status' => 'nullable|string|max:30',
                'delivary_date' => 'nullable|date',
                'user_id' => 'required|exists:users,id',
                'remark' => 'nullable|string|max:225',
                'updatedOn' => 'nullable|date',
                'addedOn' => 'nullable|date',
            ]);
            $visaBooking = visa_booking::create($validatedData);
            return response()->json([
                'message' => 'Visa booking saved successfully.',
            ], 201);
         } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors(),  
            ], 422);  
        }
    }
    public function visa_show()
    {  
        $bookings = visa_booking::all(); 
        return response()->json([
            'success' => true,
            'data' => $bookings,
            'message' => 'Visa Booking data retrieved successfully'
        ], 200);
    }

    public function activity_store(Request $request)
    {
        
        $validatedData = $request->validate([
            'user_id' => 'exists:users,id',
            'actID' => 'integer',
            'bookingId' => 'integer',
            'invoice_number' => 'string',
            'country' => 'string|max:100',
            'currency' => 'string|max:100',
            'date_from' => 'string|max:15',
            'lead_pass_name' => 'required|string|max:100',
            'lead_pass_email' => 'nullable|email|max:100',
            'lead_pass_country' => 'required|string|max:50',
            'lead_pass_mobile' => 'nullable|string|max:100',
            // Validate adult fields (nullable)
            'adult0' => 'nullable|string|max:100',
            'adult1' => 'nullable|string|max:100',
            'adult2' => 'nullable|string|max:100',
            'adult3' => 'nullable|string|max:100',
            'adult4' => 'nullable|string|max:100',
            'adult5' => 'nullable|string|max:100',
            'adult6' => 'nullable|string|max:100',
            'adult7' => 'nullable|string|max:100',
            'adult8' => 'nullable|string|max:100',
            'adult9' => 'nullable|string|max:100',
            'adult10' => 'nullable|string|max:100',
            // Validate child fields (nullable)
            'child0' => 'nullable|string|max:100',
            'child1' => 'nullable|string|max:100',
            'child2' => 'nullable|string|max:100',
            'child3' => 'nullable|string|max:100',
            'child4' => 'nullable|string|max:100',
            'child5' => 'nullable|string|max:100',
            'child6' => 'nullable|string|max:100',
            'child7' => 'nullable|string|max:100',
            'child8' => 'nullable|string|max:100',
            // Validate infant fields (nullable)
            'infant0' => 'nullable|string|max:100',
            'infant1' => 'nullable|string|max:100',
            'infant2' => 'nullable|string|max:100',
            'infant3' => 'nullable|string|max:100',
            'infant4' => 'nullable|string|max:100',
            'infant5' => 'nullable|string|max:100',
            'infant6' => 'nullable|string|max:100',
            'infant7' => 'nullable|string|max:100',
            'infant8' => 'nullable|string|max:100',
            // Other details
            'isTour' => 'boolean',
            'offer' => 'numeric',
            'public' => 'numeric',
            'baseFare' => 'numeric',
            'offerFare' => 'numeric',
            'publicFare' => 'numeric',
            'agent_public_price' => 'nullable|numeric',
            'agent_offer_price' => 'nullable|numeric',
            'agent_commission' => 'nullable|numeric',
            'agent_tds' => 'nullable|numeric',
            'agent_invoice_amount' => 'nullable|numeric',
            'agent_net_receivable' => 'nullable|numeric',
            // Dates and status
            'cancel_date' => 'nullable|string|max:15',
            'cancel_status' => 'nullable|string|max:15',
            'updatedOn' => 'nullable|date',
            'addedOn' => 'nullable|date',
            'addedBy' => 'integer',
            'agent_id' => 'nullable|integer',
            'flight_info' => 'nullable|string|max:100',
            'flight_ticket' => 'nullable|string|max:100',
            'remarks' => 'nullable|string|max:180',
            'new_travel_date' => 'nullable|string|max:100',
            'new_pickup_location' => 'nullable|string|max:100',
            'admin_status' => 'nullable|integer',
            'refund_amount' => 'nullable|string|max:100',
            'vendor' => 'nullable|integer',
            'services' => 'nullable|string|max:80',
            'net_cost' => 'nullable|numeric',
        ]);
       
        $activityBooking = activity_booking::create($validatedData);
        return response()->json([
            'message' => 'Activity booking successfully created',
        ], 201); 
    }

    public function activity_show()
    {  
        $bookings = activity_booking::all(); 
        return response()->json([
            'success' => true,
            'data' => $bookings,
            'message' => 'Visa Booking data retrieved successfully'
        ], 200);
    }

    public function rail_store(Request $request)
    {

        try {
        $validatedData = $request->validate([
            'user_id' => 'exists:users,id',
           'pnr' => 'required|string|unique:rail_bookings,pnr', 
            'bookingId' => 'required|string|unique:rail_bookings,bookingId',
            'jstatus' => 'nullable|string|max:225',
            'origin' => 'nullable|string|max:225',
            'destination' => 'nullable|string|max:225',
            'dtime' => 'nullable|string|max:20',
            'atime' => 'nullable|string|max:20',
            'train_no' => 'nullable|integer',
            'train_name' => 'nullable|string|max:225',
            'jdate' => 'nullable|string|max:20',
            'class' => 'nullable|string|max:10',
            'isAC' => 'nullable|boolean',
            'invoiceNo' => 'nullable|string|max:11',
            'isTicket' => 'nullable|integer',
            'offer' => 'nullable|numeric',
            'public' => 'nullable|numeric',
            'baseFare' => 'nullable|numeric',
            'currency' => 'nullable|string|max:5',
            'offerFare' => 'nullable|numeric',
            'publicFare' => 'nullable|numeric',
            'agent_public_price' => 'nullable|numeric',
            'agent_offer_price' => 'nullable|numeric',
            'agent_commission' => 'nullable|numeric',
            'agent_tds' => 'nullable|numeric',
            'agent_invoice_price' => 'nullable|numeric',
            'agent_net_receivable' => 'nullable|numeric',
            'rail_accFare' => 'nullable|numeric',
            'book_status' => 'nullable|string|max:225',
            'curr_status' => 'nullable|string|max:225',
            'ttime' => 'nullable|string|max:10',
            'distance' => 'nullable|integer',
            'source' => 'nullable|string|max:225',
            'rail_status' => 'nullable|integer',
            'remark' => 'nullable|string|max:225',
            'updatedOn' => 'nullable|date',
            'addedOn' => 'nullable|date',
            'addedBy' => 'nullable|integer',
            'acceptBy' => 'nullable|integer',
            'sourceID' => 'nullable|integer',
            'availabl' => 'nullable|string|max:225',
            'agent_bal' => 'nullable|numeric',
            'agent_crit' => 'nullable|numeric',
            
        ]);
    

        $visaBooking = rail_booking::create($validatedData);
        return response()->json([
            'message' => 'Rail booking created successfully',
        
        ], 201);
    } catch (ValidationException $e) {
        return response()->json([
            'message' => 'Validation failed.',
            'errors' => $e->errors(),  
        ], 422);  
    }
    }

    public function rail_show()
    {  
        $bookings = rail_booking::all(); 
        return response()->json([
            'success' => true,
            'data' => $bookings,
            'message' => 'Rail booking data retrieved successfully'
        ], 200);
    } 

    public function hotel_store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'exists:users,id',
            'bookingId' => 'integer',
            'invoice_number' => 'string|max:255',
            'confirmation_no' => 'nullable|string|max:255',
            'booking_ref_no' => 'nullable|string|max:255',
            'HCN' => 'nullable|string|max:255',
            'HotelCity' => 'nullable|string|max:255',
            'hotel_name' => 'nullable|string|max:255',
            'checkIn' => 'nullable|string|max:20',
            'checkOut' => 'nullable|string|max:20',
            'jdate' => 'nullable|string|max:20',
            'offer' => 'nullable',
            'public' => 'nullable',
            'base' => 'nullable',
            'base_offerFare' => 'nullable',
            'publicFare' => 'nullable|integer',
            'offerFare' => 'nullable',
            'agent_pFare' => 'nullable',
            'agent_oFare' => 'nullable',
            'agent_commission' => 'nullable',
            'agent_tds' => 'nullable',
            'agent_invoice_amount' => 'nullable',
            'agent_net_receivable' => 'nullable',
            'hotel_account_fare' => 'nullable|integer',
            'currency' => 'nullable|string|max:10',
            'title' => 'nullable|string|max:50',
            'fname' => 'nullable|string|max:255',
            'lname' => 'nullable|string|max:255',
            'email' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:10',
            'LastCancellationDate' => 'nullable|date',
            'ChangeRequestId' => 'nullable|string|max:50',
            'ChangeRequestdate' => 'nullable|string|max:50',
            'updatedOn' => 'nullable|date',
            'addedOn' => 'nullable|date',
            'addedBy' => 'nullable|integer',
            'sourceID' => 'nullable|integer',
            'agent_bal' => 'nullable',
            'agent_crit' => 'nullable',
        ]);

        $booking = hotel_booking::create($validatedData);

        return response()->json([
            'message' => 'Hotel booking created successfully.',

        ], 201);
    }
    public function hotel_show()
    {  
        $bookings = hotel_booking::all();    
        return response()->json([
            'success' => true,
            'data' => $bookings,
            'message' => 'hotel booking data retrieved successfully'
        ], 200);
    }

    public function agent_store(Request $request)
    {  

        $validatedData = $request->validate([
            'user_id' => 'exists:users,id',
            'agent' => 'nullable|integer',
            'amount' => 'nullable|integer',
            'currency' => 'nullable|string|max:3',
            'pnr' => 'nullable|string|max:50',
            'bookingID' => 'nullable|string|max:50',
            'flight_no' => 'nullable|string|max:50',
            'tot_time' => 'nullable|string|max:50',
            'origin' => 'nullable|string|max:100',
            'date_time_orig' => 'nullable|string|max:100',
            'destination' => 'nullable|string|max:100',
            'date_time_dest' => 'nullable|string|max:100',
            'layover' => 'nullable|string|max:100',
            'layover_airport' => 'nullable|string|max:255',
            'date_time_layover' => 'nullable|string|max:100',
            'isLCC' => 'nullable|integer',
            'Refundable' => 'nullable|integer',
            'journey_type' => 'nullable|integer',
            'adult' => 'nullable|integer',
            'child' => 'nullable|integer',
            'infant' => 'nullable|integer',
            'ptitle' => 'nullable|string|max:10',
            'pfname' => 'nullable|string|max:255',
            'plname' => 'nullable|string|max:255',
            'gender' => 'nullable|integer',
            'dob' => 'nullable|string|max:10',
            'passportno' => 'nullable|string|max:50',
            'passportexpdate' => 'nullable|string|max:50',
            'contactNo' => 'nullable|string|max:20',
            'email' => 'nullable|string|email|max:255',
            'cityCode' => 'nullable|string|max:255',
            'countryCode' => 'nullable|string|max:255',
            'addressLine1' => 'nullable|string|max:255',
            'addressLine2' => 'nullable|string|max:255',
            'updatedOn' => 'nullable|date',
            'addedOn' => 'nullable|date',
            'addedBy' => 'nullable|integer',
        ]);
    
        booking_agent::create($validatedData);
    
        return response()->json([
            'message' => 'Agent booking created successfully.',

        ], 201);
    }
 
    public function agent_show()
    {  
        $bookings = booking_agent::all(); 
        return response()->json([
            'success' => true,
            'data' => $bookings,
            'message' => 'Agent booking data retrieved successfully'
        ], 200);
    }

    public function tour_store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'exists:users,id',
            'tourID' => 'nullable|integer',
            'bookingId' => 'nullable|integer',
            'invoice_number' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'star' => 'nullable|integer',
            'tot_room' => 'nullable|integer',
            'currency' => 'nullable|string|max:10',
            'date_from' => 'nullable|string|max:100',
            'date_to' => 'nullable|string|max:100',
            'lead_pass_name' => 'nullable|string|max:100',
            'lead_pass_email' => 'nullable|string|email|max:100',
            'lead_pass_country' => 'nullable|string|max:100',
            'lead_pass_mobile' => 'nullable|string|max:100',
            'default_act1' => 'nullable|string|max:100',
            'default_act2' => 'nullable|string|max:100',
            'default_act3' => 'nullable|string|max:100',
            'default_act4' => 'nullable|string|max:100',
            'default_act5' => 'nullable|string|max:100',
            'add_act1' => 'nullable|string|max:100',
            'add_act2' => 'nullable|string|max:100',
            'add_act3' => 'nullable|string|max:100',
            'add_act4' => 'nullable|string|max:100',
            'add_act5' => 'nullable|string|max:100',
            // 'adult1' => 'nullable|string|max:100',
            // 'adult2' => 'nullable|string|max:100',
            // 'adult3' => 'nullable|string|max:100',
            // 'adult4' => 'nullable|string|max:100',
            // 'adult5' => 'nullable|string|max:100',
            // 'adult6' => 'nullable|string|max:100',
            // 'adult7' => 'nullable|string|max:100',
            // 'adult8' => 'nullable|string|max:100',
            // 'adult9' => 'nullable|string|max:100',
            // 'adult10' => 'nullable|string|max:100',
            // 'adult11' => 'nullable|string|max:100',
            // 'adult12' => 'nullable|string|max:100',
            // 'adult13' => 'nullable|string|max:100',
            // 'adult14' => 'nullable|string|max:100',
            // 'adult15' => 'nullable|string|max:100',
            // 'adult16' => 'nullable|string|max:100',
            // 'adult17' => 'nullable|string|max:100',
            // 'adult18' => 'nullable|string|max:100',
            // 'child1' => 'nullable|string|max:100',
            // 'child2' => 'nullable|string|max:100',
            // 'child3' => 'nullable|string|max:100',
            // 'child4' => 'nullable|string|max:100',
            // 'child5' => 'nullable|string|max:100',
            // 'child6' => 'nullable|string|max:100',
            // 'child7' => 'nullable|string|max:100',
            // 'child8' => 'nullable|string|max:100',
            // 'child9' => 'nullable|string|max:100',
            // 'child10' => 'nullable|string|max:100',
            // 'child11' => 'nullable|string|max:100',
            // 'child12' => 'nullable|string|max:100',
            // 'child13' => 'nullable|string|max:100',
            // 'child14' => 'nullable|string|max:100',
            'isTour' => 'nullable|boolean',
            'offer' => 'nullable|numeric',
            'public' => 'nullable|numeric',
            'baseFare' => 'nullable|numeric',
            'offerFare' => 'nullable|numeric',
            'publicFare' => 'nullable|numeric',
            'agent_public_price' => 'nullable|numeric',
            'agent_offer_price' => 'nullable|numeric',
            'agent_commission' => 'nullable|numeric',
            'agent_tds' => 'nullable|numeric',
            'agent_invoice_price' => 'nullable|numeric',
            'agent_net_receivable' => 'nullable|numeric',
            'cancel_status' => 'nullable|string|max:100',
            'cancel_date' => 'nullable|date',
            'updatedOn' => 'nullable|date',
            'addedOn' => 'nullable|date',
            'addedBy' => 'nullable|integer',
        ]);
        $bookingTour = booking_tour::create($validatedData);
        return response()->json([
            'message' => 'Booking tour created successfully',
        ], 201); 
     }

    public function tour_show()
    {  
        $bookings = booking_tour::all(); 
        return response()->json([
            'success' => true,
            'data' => $bookings,
            'message' => 'Agent booking data retrieved'
        ], 200);
    }

    public function stores(Request $request)
    {
        // Define validation rules
        $rules = [
            'bookings_id' => 'required|exists:bookings,id', // Ensure the booking ID exists in the bookings table
            'passenger.*.*.title' => 'required|string',
            'passenger.*.*.first_name' => 'required|string',
            'passenger.*.*.last_name' => 'required|string',
            'passenger.*.*.gender' => 'required|string',
            'passenger.*.*.nationality' => 'required|string',
            'passenger.*.*.email' => 'required|email',
            'passenger.*.*.passport_num' => 'required|string',
            'passenger.*.*.Passport_Expiry_Date' => 'required|date',
            'passenger.*.*.date_of_birth' => 'required|date',
        ];
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        foreach (['adult', 'infant', 'children'] as $type) {
            if ($request->has("passenger.$type")) {
               
                foreach ($request->input("passenger.$type") as $passengerData) {
                    Passenger::create([
                        'bookings_id' => $request->bookings_id, 
                        'title' => $passengerData['title'],
                        'first_name' => $passengerData['first_name'],
                        'last_name' => $passengerData['last_name'],
                        'gender' => $passengerData['gender'],
                        'nationality' => $passengerData['nationality'],
                        'email' => $passengerData['email'],
                        'passport_num' => $passengerData['passport_num'],
                        'passport_expiry_date' => $passengerData['Passport_Expiry_Date'],
                        'date_of_birth' => $passengerData['date_of_birth'],
                        'type' => $type, 
                    ]);
                }
            }
        }
    
        return response()->json(['message' => 'Passengers stored successfully'], 201);
    }
    

    public function getPassengers()
    {
        $adults = Passenger::where('type', 'adult')->get();
        $infants = Passenger::where('type', 'infant')->get();
        $children = Passenger::where('type', 'children')->get();

        $passengerData = [
            'passenger' => [
                'adult' => $adults,
                'infant' => $infants,
                'children' => $children
            ]
        ];

        return response()->json($passengerData);
    }

    public function single_data($id)
    {
        try {
            $booking = Booking::with(['passengers' => function ($query) {
                $query->select(
                    'bookings_id',
                    'title',
                    'first_name',
                    'last_name',
                    'gender',
                    'nationality',
                    'email',
                    'passport_num',
                    'passport_expiry_date',
                    'date_of_birth',
                    'type'
                );
            }])->findOrFail($id);
    
            $response = [
                'user_id' => $booking->user_id,
                'contactNo' => $booking->contactNo,
                'contactNo2' => $booking->contactNo2,
                'remark_book' => $booking->remark_book,
                'pcc_currency' => $booking->pcc_currency,
                'flt_class' => $booking->flt_class,
                'bass_copy' => $booking->bass_copy,
                'refund_point' => $booking->refund_point,
                'reissue_point' => $booking->reissue_point,
                'passID' => $booking->passID,
                'agent_bal' => $booking->agent_bal,
                'agent_crit' => $booking->agent_crit,
                'subID' => $booking->subID,
                'passenger' => [
                    'adult' => $booking->passengers->where('type', 'adult')->values(),
                    'infant' => $booking->passengers->where('type', 'infant')->values(),
                    'children' => $booking->passengers->where('type', 'children')->values(),
                ],
            ];
    
            return response()->json($response, 200);
    
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'No booking found with the provided ID.'
            ], 404);
        }
    }


    public function shows()
{
    $bookings = Booking::with('passengers')->get();

    $response = $bookings->map(function ($booking) {
        return [
            'user_id' => $booking->user_id,
            'contactNo' => $booking->contactNo,
            'contactNo2' => $booking->contactNo2,
            'remark_book' => $booking->remark_book,
            'pcc_currency' => $booking->pcc_currency,
            'flt_class' => $booking->flt_class,
            'bass_copy' => $booking->bass_copy,
            'refund_point' => $booking->refund_point,
            'reissue_point' => $booking->reissue_point,
            'passID' => $booking->passID,
            'agent_bal' => $booking->agent_bal,
            'agent_crit' => $booking->agent_crit,
            'subID' => $booking->subID,
            'passenger' => [
                'adult' => $booking->passengers->where('type', 'adult')->values(),
                'infant' => $booking->passengers->where('type', 'infant')->values(),
                'children' => $booking->passengers->where('type', 'children')->values(),
            ],
        ];
    });

    return response()->json($response, 200);
}


public function sendOtp(Request $request)
{
    $request->validate(['emailID' => 'required|email|exists:users,emailID']);
    
    $otp = rand(100000, 999999);
    Session::put('otp', $otp); 
    Session::put('emailID', $request->emailID);

    // Send OTP via email
    Mail::raw("Your OTP is $otp", function($message) use ($request) {
        $message->to($request->emailID)
                ->subject('Password Reset OTP');
    });

    return response()->json(['message' => 'OTP sent to your email.'], 200);
}

// Method to verify OTP
public function verifyOtp(Request $request)
{
    $request->validate(['otp' => 'required|numeric']);

    if (Session::get('otp') == $request->otp) {
        // OTP is correct, redirect to password reset page or show reset form
        Session::forget('otp'); // Clear OTP from session after verification
        return response()->json(['message' => 'OTP verified. You may now reset your password.'], 200);
    } else {
        return response()->json(['message' => 'Invalid OTP. Please try again.'], 400);
    }
}


   public function fetchIPData($ip)
    {
        $apiKey = '05D4015FA6DB5903B6E18B7BCD4C62F6'; // Your API key
        $url = "https://api.ip2location.io/?key=$apiKey&ip=$ip";

        try {
           
            $response = Http::get($url);

            if ($response->successful()) { 
                $data = $response->json();
                return response()->json($data); 
            } else {
               
                return response()->json(['error' => 'Failed to fetch IP data'], $response->status());
            }
        } catch (\Exception $e) {
         
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

}



