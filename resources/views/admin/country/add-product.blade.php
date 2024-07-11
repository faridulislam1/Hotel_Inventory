@extends('admin.master')
@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12"> 
            <div class="card-body">
    <form action="{{ route('new.itenary')}}" id="itnary" method="post" enctype="multipart/form-data">
         @csrf
        <div id="step2" class="card shadow-lg border-0 rounded-lg mt-5">
            <div class="card-header bg-primary">
                <h3 class="text-center font-weight-light my-2">Room Information</h3>
                {{session('message')}}
            </div>
         
            <div class="card-body">
                    <div id="London" class="tabcontent" style="display:block;">


                    <div class="row">
                        <div class="col-md-6">
                            <label for="country"><b>Country:</b></label>
                            <select class="form-control" name="country_id" id="countryid">
                                <option value="" selected>Select Country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->country }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="city"><b>City:</b></label>
                            <select class="form-control" name="city_id" id="cityid">
                                <option value="" selected>Select City</option>
                                            @foreach($cities as $city)
                                                <option value="{{$city->id}}">{{$city->city}}</option>
                                        @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="hotel"><b>Hotel:</b></label>
                            <select class="form-control" name="hotel_id" id="hotelid">
                                <option value="" selected>Select Hotel</option>
                                        @foreach($rooms as $room)
                                            <option value="{{$room->id}}">{{$room->hotel}}</option>
                                        @endforeach
                            </select>
                        </div>
                    </div>
                    </div>

                        

                        <div class="card-header">
                            <h3 class="text-center font-weight-light my-2">Room Details</h3>
                            {{session('message')}}
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <input class="form-control" type="text" name="room_num[]" placeholder="Enter room Name">
                                           @error('room_num')
                                           <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                </div>
                                <div class="col-md-6">
                                    <input class="form-control" type="number" name="available_capacity[]"
                                        placeholder="Enter Available Accommodation Capacity">
                                           @error('available_capacity')
                                           <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                </div>
                                <div class="col-md-6"><br>
                                    <input class="form-control" type="number" name="max_capacity[]"
                                        placeholder="Enter Maximum Accommodation Capacity">
                                </div>

                            </div><br>

                            <div class="col-md-6" id="additionalFields">
                                <label for="" class="col-sm-6 control-label"><b>Room Only Refundable</b> <span
                                        class="text-danger">*</span></label>
                                <!-- Button/link to open the modal -->
                                <div class="row">
                                    <div class="col-sm-6">
                                        <input class="form-control refund" type="number" name="refundable[]" placeholder="Enter value">
                                    </div>
                                    <div class="col-sm-6">
                                        <input class="form-control refund" type="number" readonly id="refundableInput"
                                            placeholder="currency">
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-6" id="additionalFields1">
                                <label for="" class="col-sm-6 control-label"><b>Room Only – Non-Refundable</b> <span
                                        class="text-danger">*</span></label>
                                <!-- Button/link to open the modal -->
                                <div class="row">
                                    <div class="col-sm-6">
                                        <input class="form-control" type="number" name="non_refundable[]" placeholder="Enter value">
                                    </div>
                                    <div class="col-sm-6">
                                        <input class="form-control refund " type="number" readonly id="refundableInputs1"
                                            placeholder="currency">
                                    </div>

                                </div>
                            </div>


                            <div class="col-md-6" id="additionalFields1">
                                <label for="" class="col-sm-6 control-label"><b>Good Breakfast -Refundable</b> <span
                                        class="text-danger">*</span></label>
                                <!-- Button/link to open the modal -->
                                <div class="row">
                                    <div class="col-sm-6">
                                        <input class="form-control" type="number" name="refundable_break[]"
                                            placeholder="Enter value">
                                    </div>
                                    <div class="col-sm-6">
                                        <input class="form-control refund" type="number" readonly id="refundableInput2"
                                            placeholder="currency">
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-6" id="additionalFields1">
                                <label for="" class="col-sm-6 control-label"><b>Good Breakfast – Non-Refundable</b> <span
                                        class="text-danger">*</span></label>
                                <!-- Button/link to open the modal -->
                                <div class="row">
                                    <div class="col-sm-6">
                                        <input class="form-control" type="number" name="refundable_nonbreak[]"
                                            placeholder="Enter value">
                                    </div>
                                    <div class="col-sm-6">
                                        <input class="form-control refund" type="number" readonly id="refundableInput3"
                                            placeholder="currency">
                                    </div>

                                </div>
                            </div><br>


                            <div class="col-md-6" id="additionalFields1">
                                    <label for="" class="col-sm-6 control-label"><b>Extra Bed Applicable:</b> <span class="text-danger">*</span></label>
                                    <!-- Radio buttons for selecting Yes or No -->
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input roomBed" type="radio" id="extraBedCheckbox" name="extra_bed" value="yes">
                                        <label class="form-check-label" for="extraBedCheckbox">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="noExtraBedCheckbox" name="extra_bed" value="no">
                                        <label class="form-check-label" for="noExtraBedCheckbox">No</label>
                                    </div><br>
                                    <!-- Input field for entering the value -->
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <input class="form-control" type="text" name="extra_bed[]" id="extraBedInput" placeholder="Enter value" disabled>
                                        </div>
                                        <div class="col-sm-6">
                                            <input class="form-control refund" type="text" readonly id="refundableInput4" placeholder="currency">
                                        </div>
                                    </div>
                                </div>


                                    <div class="row">
                                <div class="col-md-6">
                                    <label for="" class="col-sm-6 control-label"><b>Room Size</b> <span
                                            class="text-danger">*</span></label>
                                    <!-- Button/link to open the modal -->
                                    <input class="form-control" type="number" name="room_size[]" placeholder="Enter value">
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="col-sm-6 control-label"><b>Cancelation Policy: </b> <span
                                            class="text-danger">*</span></label>
                                    <!-- Button/link to open the modal -->
                                    <input class="form-control" type="text" name="cancellation_policy[]"
                                        placeholder="Enter value">

                                        @error('cancellation_policy')
                                           <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="col-sm-6 control-label"><b>Available Rooms:</b> <span
                                            class="text-danger">*</span></label>
                                    <!-- Button/link to open the modal -->
                                    <input class="form-control" type="number" name="room_available[]" placeholder="Enter value">
                                </div>
                            </div>

                            <div class="row" style="margin:10px;">
                                <div class="col-md-6">
                                    <label><b>Facilities</b></label>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="room_facilities[]" value="City View">
                                            City View
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="room_facilities[]" value="Air Conditioning">
                                            Air Conditioning
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="room_facilities[]" value="En-suite Bathroom">
                                            En-suite Bathroom
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="room_facilities[]" value="Flat-screen TV">
                                            Flat-screen TV
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="room_facilities[]" value="Soundproofing">
                                            Soundproofing
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="room_facilities[]" value="Minibar">
                                            Minibar
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="room_facilities[]" value="Free Wi-Fi">
                                            Free Wi-Fi
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="room_facilities[]" value="Private Suite">
                                            Private Suite
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="room_facilities[]" value="Private Kitchenette">
                                            Private Kitchenette
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="room_facilities[]" value="Pool View">
                                            Pool View
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="room_facilities[]" value="Landmark View">
                                            Landmark View
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="room_facilities[]" value="Pool View">
                                            Pool View
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="room_facilities[]" value="Rooftop Pool">
                                            Rooftop Pool
                                        </label>
                                    </div>

                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="room_facilities[]" value="Private Kitchen">
                                            Private Kitchen
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="room_facilities[]" value="Free Toiletries">
                                            Free Toiletries
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="room_facilities[]" value="Private Bathroom">
                                            Private Bathroom
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="room_facilities[]" value="Bath">
                                            Bath
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="room_facilities[]" value="Coffee Machine">
                                            Coffee Machine
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="room_facilities[]" value="Balcony">
                                            Balcony
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="room_facilities[]" value="Telephone">
                                            Telephone
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="room_facilities[]" value="Mountain View">
                                            Mountain View
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label><b>Bed type</b></label>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="bed_type[]" value="King Bed">
                                            King Bed
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="bed_type[]" value=" Queen Bed">
                                            Queen Bed
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="   []" value="Twin Bed">
                                            Twin Bed
                                        </label>
                                    </div>
                                </div>
                            </div>
       
                    <!-- Add a submit button -->
                    <!-- <div class="mt-4" style="position: absolute; bottom: -8px; right:13px; z-index: 99;"> -->
                        <!-- <a class="btn btn-primary prev-step" data-step="1">Previous</a> -->
                        <div class="mt-4 mb-0">
                                <div class="d-grid"><button class="btn btn-primary">Submit</button></div>
                            </div>
                        <!-- <span onclick="addRoom()" id="add-Room" style="color: #fff; cursor: pointer; background: #337ab7; padding: 9px 10px; border-radius: 3px;">+
                            Add Details</span> -->
                    </div>
                </div>
            </form>
        </div>
        </div>
    </div>
    </div>

        
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

</script>

<script>
        $(document).ready(function () {
            $('#extraBedCheckbox').change(function () {
                if ($(this).is(':checked')) {
                    // If "Yes" is checked, enable the input field
                    $('#extraBedInput').prop('disabled', false);

                } else {
                    // If "Yes" is unchecked, disable the input field and clear the value
                    $('#extraBedInput').prop('disabled', true);
                    $('#extraBedInput').val('');
                    $('#refundableInput4').val('');
                    //$('#refundableInput10').val('');
                }
            });

            $('#noExtraBedCheckbox').change(function () {
                if ($(this).is(':checked')) {

                    $('#extraBedInput').prop('disabled', true);
                    $('#extraBedInput').val('');
                    $('#refundableInput4').val('');
                   // $('#refundableInput10').val('');
                }
            });
        });
    </script>

<script>
    $(document).ready(function() {
        $('#search_itinerary').on('input', function() {
            var searchTerm = $(this).val();
            $.ajax({
                url: '{{ route("search.hotel") }}', // Update with your actual route
                type: 'GET',
                data: { search_itinerary: searchTerm },
                success: function(response) {
                    $('#search-results').html(response);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });

        $('#submitForm').on('click', function() {
            var formData = $('#itnary').serialize();
            $.ajax({
                url: '{{ route("new.itenary") }}', // Update with your actual route
                type: 'POST',
                data: formData,
                success: function(response) {
                    console.log(response);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });
    });
</script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        var myHeaders = new Headers();
        myHeaders.append("Accept", "application/json");
        myHeaders.append("Authorization", "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL2F1dGgvbG9naW4iLCJpYXQiOjE3MDM3NjMwMzQsImV4cCI6MTcwMzc2NjYzNCwibmJmIjoxNzAzNzYzMDM0LCJqdGkiOiJRd1JhSmRUMjdvNG9mTWhwIiwic3ViIjoiNSIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.ORH8Ah7glu8XUgff_iJGvuOjw2h5IjzsnpHDBJ9yVFM");

        var requestOptions = {
        method: 'GET',
        headers: myHeaders,
        redirect: 'follow'
        };

        fetch("http://127.0.0.1:8000/api/auth/manage-product", requestOptions)
        .then(response => response.text())
        .then(result => console.log(result))
        .catch(error => console.log('error', error));
            </script>

            <script>
            function toggleExtraBed(number) {
                    let radioButton1 = document.getElementById(`extraBedCheckbox_${number}`);
                    let inputFiled = document.getElementById(`extraBedInput_${number}`);
                    let bedApplication = document.getElementById(`refundableInputs_${i}`);
                    if (!radioButton1.checked) {
                        inputFiled.disabled = true;
                    } else {
                        inputFiled.disabled = false;
                        bedApplication.value = '';
                    }

                }
    </script>  


    <script>
        $(document).ready(function () {
            // Initially hide all steps except the first one
            $('.card').not(':first').hide();

            // Handle 'Next' button click
            $('.next-step').click(function (e) {
                console.log("Hello kakau");
                for (let i = 0; i < document.querySelectorAll('.refund').length; i++) {
                    document.querySelectorAll('.refund')[i].value = document.getElementById("currencySelect").value;
                }
                e.preventDefault();
                var currentStep = $(this).closest('.card');
                currentStep.hide();
                currentStep.next('.card').show();
            });

            $('.prev-step').click(function (e) {
                e.preventDefault();
                // alert("Hello world")
                var currentStep = $(this).closest('.card');
                currentStep.hide();
                currentStep.prev('.card').show();
            });
        });
    </script>
    <script>
        var i = 1;
        const addRoom = () => {
            i++;
            const add = document.getElementById("step2");
            const div = document.createElement("div");
            div.classList.add(`personal-class_${i}`);
            div.innerHTML = `<div class="card-body">
            <div id="step2" class="card shadow-lg border-0 rounded-lg mt-5">
              <div class="card-header bg-primary">
              <h3 class="text-center font-weight-light my-2">Room Details</h3>
              </div>

              <div class="card-body">

              <div class="row">
              <div class="col-md-6">
              <input class="form-control" type="text" name="room_num[]" placeholder="Enter room name">
              </div>
              <div class="col-md-6">
              <input class="form-control" type="number" name="available_capacity[]" placeholder="Enter Available Accommodation Capacity">
              </div>
              <div class="col-md-6"><br>
              <input class="form-control" type="number" name="max_capacity[]" placeholder="Enter Maximum Accommodation Capacity">
              </div>

              </div><br>

                  <div class="col-md-6" id="additionalFields">
            <label for="" class="col-sm-6 control-label"><b>Room Only Refundable</b> <span class="text-danger">*</span></label>
            <div class="row">
                <div class="col-sm-6">
                    <input class="form-control" type="number" name="refundable[]"  placeholder="Enter value">
                </div>
                <div class="col-sm-6">
                    <input class="form-control refund" type="number" readonly id="" placeholder="Selected Currency">
                </div>
            </div>
        </div>
              <div class="col-md-6" id="additionalFields1">
              <label for="" class="col-sm-6 control-label"><b>Room Only – Non-Refundable</b> <span class="text-danger">*</span></label>

              <div class="row">
              <div class="col-sm-6">
              <input class="form-control" type="number" name="non_refundable[]"  placeholder="Enter value">
              </div>
              <div class="col-sm-6">
              <input class="form-control refund" type="number" readonly  id="" placeholder="Enter value">
              </div>

              </div>
              </div>

              <div class="col-md-6" id="additionalFields1">
              <label for="" class="col-sm-6 control-label"><b>Good Breakfast -Refundable</b> <span class="text-danger">*</span></label>

              <div class="row">
              <div class="col-sm-6">
              <input class="form-control" type="number" name="refundable_break[]"  placeholder="Enter value">
              </div>
              <div class="col-sm-6">
              <input class="form-control refund" type="number" readonly  id="" placeholder="Enter value">
              </div>

              </div>
              </div>

              <div class="col-md-6" id="additionalFields1">
              <label for="" class="col-sm-6 control-label"><b>Good Breakfast – Non-Refundable</b> <span class="text-danger">*</span></label>

              <div class="row">
              <div class="col-sm-6">
              <input class="form-control" type="number" name="refundable_nonbreak[]"  placeholder="Enter value">
              </div>
              <div class="col-sm-6">
              <input class="form-control refund" type="number" readonly  id="" placeholder="Enter value">
              </div>

              </div>
              </div><br>

              <div class="col-md-6" id="additionalFields1">
            <label for="" class="col-sm-6 control-label"><b>Extra Bed Applicable:</b> <span class="text-danger">*</span></label>

            <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" id="extraBedCheckbox_${i}" name="extra_bed[]" value="yes"  onchange="toggleExtraBed(${i})">
                <label class="form-check-label" for="extraBedCheckbox_${i}">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                <input class="form-check-input roomBed" type="radio" id="noExtraBedCheckbox_${i}" name="extra_bed[]" value="no" onchange="toggleExtraBed(${i})">
                <label class="form-check-label" for="noExtraBedCheckbox_${i}">No</label>
                </div><br>
                <div class="row">
                <div class="col-sm-6">
                <input class="form-control" type="number" name="extra_bed[]" id="extraBedInput_${i}" placeholder="Enter value">
                </div>
                <div class="col-sm-6">
                <input class="form-control refund" type="number" readonly id="refundableInputs_${i}" placeholder="Enter value">
                </div>
                </div>
                </div>

        <div class="row">
                  <div class="col-md-6">
                  <label for="" class="col-sm-6 control-label"><b>Room Size</b> <span class="text-danger">*</span></label>

                  <input class="form-control" type="number" name="room_size[]" placeholder="Enter value">
                  </div>
                  <div class="col-md-6">
                  <label for="" class="col-sm-6 control-label"><b>Cancelation Policy: </b> <span class="text-danger">*</span></label>

                  <input class="form-control" type="number" name="cancellation_policy[]" placeholder="Enter value">
                  </div>
                  <div class="col-md-6">
                  <label for="" class="col-sm-6 control-label"><b>Available Rooms:</b> <span class="text-danger">*</span></label>

                  <input class="form-control" type="number" name="room_available[]" placeholder="Enter value">
                  </div>
                  </div>

                  <div class="row">
                  <div class="col-md-6">
                  <label><b>Facilities</b></label>
                  <div class="checkbox">
                  <label>
                  <input type="checkbox" name="room_facilities[]" value="City View">
                  City View
                  </label>
                  </div>
                  <div class="checkbox">
                  <label>
                  <input type="checkbox" name="room_facilities[]" value="Air Conditioning">
                  Air Conditioning
                  </label>
                  </div>
                  <div class="checkbox">
                  <label>
                  <input type="checkbox" name="room_facilities[]" value="En-suite Bathroom">
                  En-suite Bathroom
                  </label>
                  </div>
                  <div class="checkbox">
                  <label>
                  <input type="checkbox" name="room_facilities[]" value="Flat-screen TV">
                  Flat-screen TV
                  </label>
                  </div>
                  <div class="checkbox">
                  <label>
                  <input type="checkbox" name="room_facilities[]" value="Soundproofing">
                  Soundproofing
                  </label>
                  </div>
                  <div class="checkbox">
                  <label>
                  <input type="checkbox" name="room_facilities[]" value="Minibar">
                  Minibar
                  </label>
                  </div>
                  <div class="checkbox">
                  <label>
                  <input type="checkbox" name="room_facilities[]" value="Free Wi-Fi">
                  Free Wi-Fi
                  </label>
                  </div>
                  <div class="checkbox">
                  <label>
                  <input type="checkbox" name="room_facilities[]" value="Private Suite">
                  Private Suite
                  </label>
                  </div>
                  <div class="checkbox">
                  <label>
                  <input type="checkbox" name="room_facilities[]" value="Private Kitchenette">
                  Private Kitchenette
                  </label>
                  </div>
                  <div class="checkbox">
                  <label>
                  <input type="checkbox" name="room_facilities[]" value="Pool View">
                  Pool View
                  </label>
                  </div>
                  <div class="checkbox">
                  <label>
                  <input type="checkbox" name="room_facilities[]" value="Landmark View">
                  Landmark View
                  </label>
                  </div>
                  <div class="checkbox">
                  <label>
                  <input type="checkbox" name="room_facilities[]" value="Pool View">
                  Pool View
                  </label>
                  </div>
                  <div class="checkbox">
                  <label>
                  <input type="checkbox" name="room_facilities[]" value="Rooftop Pool">
                  Rooftop Pool
                  </label>
                  </div>

                  <div class="checkbox">
                  <label>
                  <input type="checkbox" name="room_facilities[]" value="Private Kitchen">
                  Private Kitchen
                  </label>
                  </div>
                  <div class="checkbox">
                  <label>
                  <input type="checkbox" name="room_facilities[]" value="Free Toiletries">
                  Free Toiletries
                  </label>
                  </div>
                  <div class="checkbox">
                  <label>
                  <input type="checkbox" name="room_facilities[]" value="Private Bathroom">
                  Private Bathroom
                  </label>
                  </div>
                  <div class="checkbox">
                  <label>
                  <input type="checkbox" name="room_facilities[]" value="Bath">
                  Bath
                  </label>
                  </div>
                  <div class="checkbox">
                  <label>
                  <input type="checkbox" name="room_facilities[]" value="Coffee Machine">
                  Coffee Machine
                  </label>
                  </div>
                  <div class="checkbox">
                  <label>
                  <input type="checkbox" name="room_facilities[]" value="Balcony">
                  Balcony
                  </label>
                  </div>
                  <div class="checkbox">
                  <label>
                  <input type="checkbox" name="room_facilities[]" value="Telephone">
                  Telephone
                  </label>
                  </div>
                  <div class="checkbox">
                  <label>
                  <input type="checkbox" name="room_facilities[]" value="Mountain View">
                  Mountain View
                  </label>
                  </div>
                  </div>


                  <div class="col-md-6">
                  <label><b>Bed type</b></label>
                  <div class="checkbox">
                  <label>
                  <input type="checkbox" name="bed_type[]" value="King Bed">
                  King Bed
                  </label>
                  </div>
                  <div class="checkbox">
                  <label>
                  <input type="checkbox" name="bed_type[]" value=" Queen Bed">
                  Queen Bed
                  </label>
                  </div>
                  <div class="checkbox">
                  <label>
                  <input type="checkbox" name="bed_type[]" value="Twin Bed">
                  Twin Bed
                  </label>
                  </div>
                  </div>
                  </div>

                  <div class="mt-4">
                  <button  onclick="remove(${i})" style="color: #fff;cursor: pointer;background: #337ab7;padding: 6px 15px;border: none;border-radius: 3px; margin-bottom:50px;"> Remove</button>
            </div>
            </div>
            </div>
            </div>`;

            add.appendChild(div);
            /* for (let i = 0; i < document.querySelectorAll('.refund').length; i++) {
                console.log(document.querySelectorAll('.refund'))
                document.querySelectorAll('.refund')[i].value = document.getElementById("currencySelect").value;
            } */
            if (i === 100) {
                document.getElementById("step2").style.visibility = "hidden";
                document.getElementById("add2").style.visibility = "hidden";
            }
        };
        const remove = (x) => {
            console.log(x, i);
            i--;
            console.log(x, i);
            const elementToRemove = document.querySelector(`.personal-class_${x}`);
            if (elementToRemove) {
                elementToRemove.remove();
            }

            if (i < 4) {
                document.getElementById("step2").style.visibility = "visible";
                document.getElementById("add2").style.visibility = "visible";
            }
        };
    </script>
 
@endsection

