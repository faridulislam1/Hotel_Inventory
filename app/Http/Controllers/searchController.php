<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\searchresult;
use App\Models\Room;

class searchController extends Controller
{
    public function storesearch(Request $request)
{
    $request->validate([
        'country' => 'nullable|string',
        'check_in' => 'nullable|date',
        'check_out' => 'nullable|date|after_or_equal:check_in',
        'guests' => 'nullable|array', 
        'guests.adults' => 'nullable|integer|min:0',
        'guests.children' => 'nullable|integer|min:0',
        'guests.infants' => 'nullable|integer|min:0',
    ]);

    searchresult::create([
        'country' => $request->country,
        'check_in' => $request->check_in,
        'check_out' => $request->check_out,
        'guests' => $request->guests,
    ]);

    return response()->json([
        'success' => true,
        'statusCode' => 200,
        'message' => 'searchresult created successfully']);
}

public function showsearch()
{
    $bookings = searchresult::all()->makeHidden(['created_at', 'updated_at', 'user_id']);
    $formattedBookings = $bookings->map(function ($booking) {
        return [
            'success' => true,
            'statusCode' => 200,
            'data' => $booking,

        ];
    });

    return response()->json($formattedBookings, 200);
}

public function searchresult(Request $request)
{
    // Validate incoming request data
    $validated = $request->validate([
        'country' => 'required|string',
        'check_in' => 'required|date',
        'check_out' => 'required|date|after_or_equal:check_in',
        'adults' => 'required|integer|min:0',
        'children' => 'required|integer|min:0',
        'infants' => 'required|integer|min:0',
    ]);

    // Get the search filters from validated data
    $country = $validated['country'];
    $check_in = $validated['check_in'];
    $check_out = $validated['check_out'];
    $adults = $validated['adults'];
    $children = $validated['children'];
    $infants = $validated['infants'];

    // Query the Room model for available rooms based on filters
    $rooms = searchresult::where('country', $country)
        ->where('check_in', '<=', $check_in)
        ->where('check_out', '>=', $check_out)
        ->where('adults', '>=', $adults) // Assuming max_adults is a field in your Room model
        ->where('children', '>=', $children) // Assuming max_children is a field in your Room model
        ->where('infants', '>=', $infants) // Assuming max_infants is a field in your Room model
        ->get();

    // Return the search results as JSON or return a view
    return response()->json([
        'rooms' => $rooms,
        'search_criteria' => $validated,
    ]);
}


}
