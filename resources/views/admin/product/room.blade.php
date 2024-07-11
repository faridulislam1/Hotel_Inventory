@extends('admin.master')
@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg border-0 rounded-lg mt-5">
                    <div class="card-header bg-primary">
                        <h3 class="text-center font-weight-light my-2">Room Details</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('new.room') }}" method="post">
                        @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <input class="form-control" type="text" name="room_num" placeholder="Enter room number">
                                </div>
                                <div class="col-md-6">
                                    <input class="form-control" type="text" name="available_capacity" placeholder="Enter Available Accommodation Capacity">
                                </div>
                                <div class="col-md-6"><br>
                                    <input class="form-control" type="text" name="max_capacity" placeholder="Enter Maximum Accommodation Capacity">
                                </div>

                            </div><br>

                            <div class="col-md-6" id="additionalFields">
                                <label for="" class="col-sm-6 control-label"><b>Room Only Refundable</b> <span class="text-danger">*</span></label>
                                <!-- Button/link to open the modal -->
                                <input class="form-control" type="text" name="refundable" placeholder="Enter value">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input class="form-control" type="text" name="embed_code" placeholder="Enter value">

                                    </div>

                                </div>

                            </div>
                            <div class="col-md-6">
                                <label for="" class="col-sm-6 control-label"><b>Room Only – Non-Refundable</b> <span class="text-danger">*</span></label>
                                <!-- Button/link to open the modal -->
                                <input class="form-control" type="text" name="non_refundable" placeholder="Enter value">
                            </div>
                            <div class="col-md-6">
                                <label for="" class="col-sm-6 control-label"><b>Good Breakfast – Refundable</b> <span class="text-danger">*</span></label>
                                <!-- Button/link to open the modal -->
                                <input class="form-control" type="text" name="refundable_break" placeholder="Enter value">
                            </div>
                            <div class="col-md-6">
                                <label for="" class="col-sm-6 control-label"><b>Good Breakfast – Non-Refundable</b> <span class="text-danger">*</span></label>
                                <!-- Button/link to open the modal -->
                                <input class="form-control" type="text" name="refundable_nonbreak" placeholder="Enter value">
                            </div>
                            <div class="col-md-6">
                                <label for="" class="col-sm-6 control-label"><b>Extra Bed Applicable:</b> <span class="text-danger">*</span></label>
                                <!-- Button/link to open the modal -->
                                <input class="form-control" type="text" name="extra_bed" placeholder="Enter value">
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <label for="" class="col-sm-6 control-label"><b>Room Size</b> <span class="text-danger">*</span></label>
                                    <!-- Button/link to open the modal -->
                                    <input class="form-control" type="text" name="room_size" placeholder="Enter value">
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="col-sm-6 control-label"><b>Cancelation Policy: </b> <span class="text-danger">*</span></label>
                                    <!-- Button/link to open the modal -->
                                    <input class="form-control" type="text" name="cancellation_policy" placeholder="Enter value">
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="col-sm-6 control-label"><b>Available Rooms:</b> <span class="text-danger">*</span></label>
                                    <!-- Button/link to open the modal -->
                                    <input class="form-control" type="text" name="room_available" placeholder="Enter value">
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



                            <!-- Add a submit button -->
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        // Get the currency select element
        var currencySelect = document.getElementById('currencySelect');
        // Get the div containing additional fields
        var additionalFieldsDiv = document.getElementById('additionalFields');

        // Event listener for changes in the currency select dropdown
        currencySelect.addEventListener('change', function () {
            var selectedCurrency = currencySelect.value;

            // Show additional fields if a currency other than the default is selected
            if (selectedCurrency !== '') {
                additionalFieldsDiv.style.display = 'block';
                // You can perform actions related to the selected currency here
            } else {
                // Hide additional fields when the default option is selected
                additionalFieldsDiv.style.display = 'none';
            }
        });


    </script>

@endsection
