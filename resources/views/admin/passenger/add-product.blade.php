@extends('admin.master')
@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow-lg border-0 rounded-lg mt-2">
                    <div class="card-header bg-primary">
                        <h3 class="text-center font-weight-light my-2">Hotel Data Form</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('new.passenger')}}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div id="London" class="tabcontent" style="display:block;">
                             <div class="row">

                             <div class="form-group row">
                            <label for="" style="margin-left:3px;" class="col-sm-3 control-label"><b>Country Name</b> <span class="text-danger">*</span></label>
                            <!-- Button/link to open the modal -->

                              <div class="col-sm-9"><br>
                                <select class="form-control" name="country_id" id="categoryId">
                                    <option value="" disabled selected> -- Select country -- </option>
                                    @foreach($products as $product)
                                        <option value="{{$product->id}}">{{$product->country}}</option>
                                    @endforeach

                                </select>
                                  @error('country_id')
                                  <div class="alert alert-danger">{{ $message }}</div>
                                  @enderror
                            </div>
                        </div>



            <div class="form-group row">
                            <label for="" style="margin-left:3px;" class="col-sm-3 control-label"><b>City Name</b> <span class="text-danger">*</span></label>
                            <!-- Button/link to open the modal -->

                              <div class="col-sm-9"><br>
                                <select class="form-control" name="city_id" id="categoryId">
                                    <option value="" disabled selected> -- Select Citry -- </option>
                                    @foreach($products as $product)
                                        <option value="{{$product->id}}">{{$product->city}}</option>
                                    @endforeach
                                </select>
                                  @error('city_id')
                                  <div class="alert alert-danger">{{ $message }}</div>
                                  @enderror
                            </div>
                        </div>

<!--
            <div class="col-md-6">
              <input class="form-control" type="text" name="hotel_name" placeholder="Enter Hotel Name">

            </div> -->
               <div class="form-group row"><br>
                            <label for="" style="margin-left:3px;" class="col-sm-3 control-label"><b>Hotel Name</b> <span class="text-danger">*</span></label>
                            <!-- Button/link to open the modal -->

                                      <div class="col-sm-9">
              <select class="form-control" name="hotel_id" id="categoryIds">
                  <option value="" disabled selected> -- Select Hotel -- </option>
                  @foreach($products as $product)
                      <option value="{{$product->id}}">{{$product->hotel_name}}</option>
                  @endforeach
              </select>
                  @error('hotel_id')
                  <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
               <div id="hotelDetails">
                  <p id="embed"></p>
                  <p id="location"></p>
                  <p id="rating"></p>
                  <p id="Single_image"></p>
                  <p id="multiple_image"></p>
              </div>
          </div>

                        </div>

                      <div class="col-md-6"><br>
                    <input type="text" class="form-control" name="short_decription" placeholder="Write short description">
                          @error('short_decription')
                          <div class="alert alert-danger">{{ $message }}</div>
                          @enderror
                  </div>
                  <div class="col-md-6"><br>
                    <textarea class="form-control" rows="3" name="long_decription" placeholder="Write long description"></textarea>
                      @error('long_decription')
                      <div class="alert alert-danger">{{ $message }}</div>
                      @enderror
                  </div>
                  <div class="col-md-6">
                    <!-- hotel -->
                    <select class="form-control" name="currency" >
                        <option value="" disabled selected> -- Select currency -- </option>
                       <option>BDT (Bangladesh)</option>
                       <option>INR (India)</option>
                       <option>NPR (Nepal)</option>
                       <option>MYR (Malaysia)</option>
                       <option>THB (Thailand)</option>
                       <option>AED (Dubai)</option>
                       <option>USD/CAD (Cambodia)</option>
                     </select>
                      @error('currency')
                      <div class="alert alert-danger">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="col-md-6">
                    <input type="number" class="form-control" name="offer_fare" placeholder="Offer Fare">
                        @error('offer_fare')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                    <input type="number" class="form-control" name="public_fare" placeholder="Public Fare">
                        @error('public_fare')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                    <textarea class="form-control" rows="2" name="policy" placeholder="Policy"></textarea>
                        @error('policy')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                    <textarea class="form-control" rows="2" name="term_condition" placeholder="Terms & Conditions"></textarea>
                        @error('term_condition')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                    <textarea class="form-control" rows="2" name="cancellation_policy" placeholder="Cancellation Policy"></textarea>
                        @error('cancellation_policy')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="disabledTextInput"  style="display: block;"><b>Includes</b></label>
                        <div class="checkbox">
                            <label>
                              <input type="checkbox" name="includes[]" value="BreakFast Included">
                              BreakFast Included
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="includes[]" value="Free Cancellation">
                              Free Cancellation
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="includes[]" value="Instant Confirmation">
                              Instant Confirmation
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="includes[]" value=" Reserve">
                              Reserve
                            </label>
                          </div>

                    </div>

                    <div class="col-md-6">
                        <label><b>Bed Type</b></label>
                        <div class="checkbox">
                            <label>
                              <input type="checkbox" name="bed_type[]" value="Double Bed">
                              1. Double Bed
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="bed_type[]"value="Bed">
                              2. Bed
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="bed_type[]"value="Single Bed">
                              3. Single Bed
                            </label>
                          </div>


                    </div>
                    <!-- facilities -->
                    <div class="col-md-6">
                        <label><b>Facilities</b></label>
                        <div class="checkbox">
                            <label>
                              <input type="checkbox" name="facalities[]" value="Free parking">
                              Free parking
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="facalities[]" value="Airport Pickup">
                              Airport Pickup
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="facalities[]" value="Pets Allowed">
                              Pets Allowed
                            </label>
                          </div>


                    </div>
                    <!-- room features -->
                    <div class="col-md-6">
                        <label><b>Room features</b> </label>
                        <div class="checkbox">
                            <label>
                              <input type="checkbox" name="room_features[]" value="Family Room">
                              Family Room
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="room_features[]" value="Suit">
                              Suit
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="room_features[]" value="Room River View">
                              Room River View
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="room_features[]" value="Water Bed">
                              Water Bed
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="room_features[]" value="Games Room">
                              Games Room
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="room_features[]" value="Cinema Room">
                              Cinema Room
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="room_features[]" value="Round Room">
                              Round Room
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="room_features[]"value="Sea View Room">
                              Sea View Room
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="room_features[]" value="Parent Child Room">
                              Parent Child Room
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="room_features[]" value="Themed Room">
                              Themed Room
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="room_features[]"value="lake View">
                              lake View
                            </label>
                          </div>

                    <!-- Extra Features -->
                    <div class="col-md-6">
                        <label><b>Extra Features</b></label>
                        <div class="checkbox">
                            <label>
                              <input type="checkbox" name="extra_features[]" value="Bathtop">
                              Bathtop
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="extra_features[]" value="Washing Machine">
                              Washing Machine
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="extra_features[]" value="Kitchen">
                              Kitchen
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="extra_features[]" value="Smart Toilet">
                              Smart Toilet
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="extra_features[]" value="Refridgator">
                              Refridgator
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="extra_features[]"value="Balcony">
                              Balcony
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="extra_features[]" value="Chess">
                              Chess
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="extra_features[]"value="Computer">
                              Computer
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="extra_features[]" value="Air Conditioning">
                              Air Conditioning
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="extra_features[]" value="Extra Bed">
                              Extra Bed
                            </label>
                          </div>
                    </div>
                    <!-- Additional Services -->
                    <div class="col-md-6">
                        <label><b>Additional Services</b></label>
                        <div class="checkbox">
                            <label>
                              <input type="checkbox" name="additional_services[]" value="Parking">
                              Parking (Free/Paid)
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="additional_services[]"value="Cafe">
                              Cafe (Free/Paid)
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="additional_services[]" value="Audio">
                              Audio (Free/Paid)
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="additional_services[]" value="Tea Room ">
                              Tea Room (Free/Paid)
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="additional_services[]" value="Wifi">
                              Wifi (Free/Paid)
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="additional_services[]" value="FrontDesk">
                              24 Hour Front Desk (Free/Paid)
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="additional_services[]" value="Car Charging">
                              Car Charging (Free/Paid)
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="additional_services[]" value="Bar">
                              Bar (Free/Paid)
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="additional_services[]" value="Multifunctional Room">
                              Multifunctional Room (Free/Paid)
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox"name="additional_services[]"  value="Smoking">
                              Smoking (Free/Paid)
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="additional_services[]" value="Non Smoking">
                              Non Smoking (Free/Paid)
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="additional_services[]" value="Front Desk">
                              Front Desk (Free/Paid)
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="additional_services[]"value="Restruant">
                              Restruant (Free/Paid)
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="additional_services[]"value="Meeting Room">
                              Meeting Room (Free/Paid)
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="additional_services[]"value="Gym">
                              Fitness Room/Gym (Free/Paid)
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="additional_services[]"value="Luggage Room">
                              Luggage Room (Free/Paid)
                            </label>
                          </div>
                    </div>


          </div>

        </div>

    </div>


                            <div class="mt-4 mb-0">
                                <div class="d-grid"><button class="btn btn-primary">Submit</button></div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
      $('#categoryIds').change(function() {
        // var hotelId = $(this).val();
        // return console.log(hotelId);
        const value = document.getElementById('categoryIds').value;
        $.ajax({
          url: 'http://localhost/visa_tbp/visa1/public/manage-product',
          type: 'GET',
          success: function(response) {
            // const value
            // console.log(response)
            const newData = response.find((hotel)=>hotel.id==value);
                    $('#embed').text('Embed Code: ' + newData.embed_code);
                    $('#location').text('Location: ' + newData.location);
                    $('#rating').text('Rating: ' + newData.rating);
                    $('#Single_image').text('Image: ' + newData.Single_image);
                    $('#multiple_image').text('Image: ' + newData.multiple_image);
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script>

@endsection
