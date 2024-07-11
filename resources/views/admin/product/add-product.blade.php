@extends('admin.master')
@section('content')
    <link rel="stylesheet" href="https://unpkg.com/cropperjs/dist/cropper.min.css">
    <style>
        #image-container {
            max-width: 500px;
            margin: 20px auto;
        }

        .cropper-bg {
            width: 542px;
            height: 400px;
        }

        .next {
            text-align: center;
        }
    </style>  
    <!-- Your HTML form code (multi-step) -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">     
            <form action="{{route('new.hotel')}}" method="post" enctype="multipart/form-data">
                            @csrf
                    <!-- First step of the form -->
                    <div id="step1" class="card shadow-lg border-0 rounded-lg mt-5">
                        <!-- ... (your form fields for step 1) ... -->
                        <div class="card-header bg-primary">
                            <h3 class="text-center font-weight-light my-2">Hotel system</h3>
                            {{session('message')}}
                        </div>


                            

                        <div class="card-body">
                                <div id="London" class="tabcontent" style="display:block;">

                                    <div class="row">
                                        <div class="col-md-6"><br>
                                            <input class="form-control" type="text" name="country"
                                                placeholder="Enter country">
                                             @error('country')
                                          <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                         </div>
                                        
                                        <div class="col-md-6"><br>
                                            <input class="form-control" type="text" name="city" placeholder="Enter city">
                                            @error('city')
                                          <div class="text-danger">{{ $message }}</div>
                                            @enderror 
                                        </div>

                                        <div class="col-md-6"><br>
                                            <input class="form-control" type="text" name="hotel" placeholder="Enter hotel name">
                                            @error('hotel')
                                          <div class="text-danger">{{ $message }}</div>
                                            @enderror 
                                        </div>

                                        <div class="col-md-6"><br>
                                            <input class="form-control" type="text" name="embed_code"
                                                placeholder="Enter hotel Embed code">
                                                @error('embed_code')
                                          <div class="text-danger">{{ $message }}</div>
                                            @enderror 
                                        </div>

                                        <div class="col-md-6"><br>
                                            <input class="form-control" id="pac-input" type="text" name="landmark"
                                                placeholder="Enter a landmark">
                                                @error('landmark')
                                          <div class="text-danger">{{ $message }}</div>
                                            @enderror 

                                        </div>

                                        <div class="form-group col-md-12"><br>
                                            <label for="disabledTextInput" style="display: block;"><b>Hotel Star
                                                    Rating</b></label>
                                            <label class="radio-inline">
                                                <input type="radio" name="rating" id="inlineRadio1" value="option1"> 1
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="rating" id="inlineRadio2" value="option2"> 2
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="rating" id="inlineRadio3" value="option3"> 3
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="rating" id="inlineRadio3" value="option4"> 4
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="rating" id="inlineRadio3" value="option5"> 5
                                            </label>

                                            @error('rating')
                                          <div class="text-danger">{{ $message }}</div>
                                            @enderror 
                                        </div><br>

                                        <!-- thum img -->
                                        <div class="col-md-6"><br>

                                            <label><b>Thumb Image</b></label>
                                             <input type="file" name="Single_image">   
                                             @error('Single_image')
                                          <div class="text-danger">{{ $message }}</div>
                                            @enderror       
                                        </div>

                                        <br><br>
                                        <div class="col-md-6"><br>
                                            <label><b>Additional Image</b></label>
                                            <!-- Input field for image upload -->
                                            <input type="file" name="multiple_image[]" id="inputImage2" multiple>
                                            @error('multiple_image')
                                          <div class="text-danger">{{ $message }}</div>
                                            @enderror   

                                            
                                        </div>

                                        <div class="col-md-6"><br>
                                            <input class="form-control" type="text" name="address"
                                                placeholder="Enter hotel address ">
                                                @error('address')
                                          <div class="text-danger">{{ $message }}</div>
                                            @enderror 

                                        </div>
                                        <div class="col-md-6"><br>
                                            <input class="form-control" type="text" name="highlights"
                                                placeholder="Enter hotel Property highlights">
                                                @error('highlights')
                                          <div class="text-danger">{{ $message }}</div>
                                            @enderror 

                                        </div>

                                         <div class="col-md-6"><br>
                                            <textarea class="form-control" type="text" name="long_decription"
                                                placeholder="Enter Hotel Description"></textarea>
                                                @error('long_decription')
                                          <div class="text-danger">{{ $message }}</div>
                                            @enderror 

                                        </div>
                                        <div class="col-md-6" id="currencyField"><br>
                                            <!-- hotel -->
                                            <select class="form-control" id="currencySelect" name="currency">
                                                <option value="" selected> -- Select currency -- </option>
                                                <option>BDT (Bangladesh)</option>
                                                <option>INR (India)</option>
                                                <option>NPR (Nepal)</option>
                                                <option>MYR (Malaysia)</option>
                                                <option>THB (Thailand)</option>
                                                <option>AED (Dubai)</option>
                                                <option>USD/CAD (Cambodia)</option>
                                            </select>
                                            @error('currency')
                                          <div class="text-danger">{{ $message }}</div>
                                            @enderror 

                                        </div>

                                         <div class="row">
                                         <div class="col-md-6"><br>
                                    <textarea class="form-control" name="term_condition" placeholder="Enter Hotels Terms and Condition"></textarea>
                                    @error('term_condition')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                        <div class="col-md-6"><br>
                                            <input class="form-control" type="text" name="longitude" placeholder="Enter longitude">
                                            @error('longitude')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror 
                                        </div>
                                    </div>

                                        <div class="col-md-6"><br>
                                            <input class="form-control" type="text" name="litetitude"
                                                placeholder="Enter litetitude">
                                                @error('litetitude')
                                          <div class="text-danger">{{ $message }}</div>
                                            @enderror 
                                        </div>
                                    </div><br>

                                    <div class="col-md-6">
                                        <label><b>Facilities</b></label>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="facilities[]" value="Swimming Pool">
                                                Swimming Pool
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="facilities[]" value="Airport Shuttle">
                                                Airport Shuttle
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="facilities[]" value="Non-Smoking Rooms">
                                                Non-Smoking Rooms
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="facilities[]" value="Smoking Rooms">
                                                Smoking Rooms
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="facilities[]" value="Room Service">
                                                Room Service
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="facilities[]" value="Spa and Wellness Center">
                                                Spa and Wellness Center
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="facilities[]"
                                                    value="Facilities for Disabled Guests">
                                                Facilities for Disabled Guests
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="facilities[]" value="Bar">
                                                Bar
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="facilities[]" value="Gym">
                                                Gym
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="facilities[]" value="Restaurant">
                                                Restaurant
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="facilities[]" value="Free Parking">
                                                Free Parking
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="facilities[]" value="Free Wi-Fi">
                                                Free Wi-Fi
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="facilities[]" value="Family Rooms">
                                                Family Rooms
                                            </label>
                                        </div>

                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="facilities[]" value="Good Breakfast">
                                                Good Breakfast
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="facilities[]" value="24-Hour Front Desk">
                                                Pets Allowed
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="facilities[]" value="Laundry">
                                                24-Hour Front Desk
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="facilities[]" value="Parking On-Site">
                                                Parking On-Site
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="facilities[]" value="Kitchen">
                                                Kitchen
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="facilities[]" value="Terrace">
                                                Terrace
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="facilities[]" value="Air Conditioning">
                                                Air Conditioning
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="facilities[]" value="Garden">
                                                Garden
                                            </label>
                                        </div>

                                        @error('facilities')
                                          <div class="text-danger">{{ $message }}</div>
                                            @enderror 
                                    </div>

                                </div>
                                        
                            <div class="mt-4 mb-0">
                                <div class="d-grid"><button class="btn btn-primary">Submit</button></div>
                            </div>
                         </form>
                
                        </div>
                     </div>
                 </div>
@endsection


