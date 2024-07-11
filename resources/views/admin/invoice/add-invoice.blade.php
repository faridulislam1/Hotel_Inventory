@extends('admin.master')
@section('content')
<link rel="stylesheet" href="https://unpkg.com/cropperjs/dist/cropper.min.css">
<style>
      #image-container {
      max-width: 500px;
      margin: 20px auto;
    }
    .cropper-bg{
      width: 542px;
    height: 400px;
    }
</style>
<div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0 rounded-lg mt-5">
                    <div class="card-header bg-primary">
                        <h3 class="text-center font-weight-light my-2">Add Invoice Form</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('new.invoice')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row" style="margin-top:10px">
                                <div class="col-md-12">
                                    <form class="form-horizontal">
                                        <label class="col-xs-2  control-label">Trip ID</label>
                                        <div class="form-group">
                                            <div class="col-xs-7">
                                                <input type="number" name="trip_id" class="form-control" id="pwd" placeholder="">
                                            </div>
                                        </div>
                                        <!-- 2 -->
                                        <div class="form-group">
                                            <label for="pwd" class="col-xs-2    control-label">Name of the Hotel
                                            </label>
                                            <div class="col-xs-7">
                                                <input type="text" name="hotel_name" class="form-control" id="pwd" placeholder="">
                                            </div>
                                        </div>
                                        <!-- 3 -->
                                        <div class="form-group">
                                            <label for="pwd" class="col-xs-2    control-label">Hotel Address</label>
                                            <div class="col-xs-7">
                                                <input type="text" name="address" class="form-control" id="pwd" placeholder="">
                                            </div>
                                        </div>
                                        <!-- 4 -->
                                        <div class="form-group">
                                            <label class="col-xs-2    control-label">Hotel Number</label>
                                            <div class="col-xs-7">
                                                <input type="number" name="hotel_num" class="form-control" id="pwd" placeholder="">
                                            </div>
                                        </div>
                                        <!-- 5 -->
                                        <div class="form-group">
                                            <label for="pwd" class="col-xs-2    control-label">Room Type</label>
                                            <div class="col-xs-7">
                                                <input type="number" name="room_type" class="form-control" id="pwd" placeholder="">
                                            </div>
                                        </div>
                                        <!-- 6 -->
                                        <div class="form-group">
                                            <label for="pwd" class="col-xs-2    control-label">Number of Rooms</label>
                                            <div class="col-xs-7">
                                                <input type="number" name="room_num" class="form-control" id="pwd" placeholder="">
                                            </div>
                                        </div>
                                        <!-- 7 -->
                                        <div class="form-group">
                                            <label for="pwd" class="col-xs-2    control-label">Includes in Hotel</label>
                                            <div class="col-xs-7">
                                                <input type="text" name="includes_hotel" class="form-control" id="pwd" placeholder="">
                                            </div>
                                        </div>
                                        <!-- 8 -->
                                        <div class="form-group">
                                            <label for="pwd" class="col-xs-2    control-label">Hotel Star Rating</label>
                                            <div class="col-xs-7">
                                                <input type="number" name="rating" class="form-control" id="pwd" placeholder="">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="pwd" class="col-xs-2    control-label">Lead Guest</label>
                                            <div class="col-xs-7">
                                                <input type="text" name="lead_guest" class="form-control" id="pwd" placeholder="">
                                            </div>
                                        </div>
                                        <!-- 11 -->
                                        <div class="form-group">
                                            <label for="pwd" class="col-xs-2    control-label">Total Number of Guests</label>
                                            <div class="col-xs-7">
                                                <input type="number" name="guest_num" class="form-control" id="pwd" placeholder="">
                                            </div>
                                        </div>
                                        <!-- 12 -->
                                        <div class="form-group">
                                            <label for="pwd" class="col-xs-2    control-label">Check In Date</label>
                                            <div class="col-xs-7">
                                                <input type="date" name="checkin_date" class="form-control" id="pwd" placeholder="">
                                            </div>
                                        </div>
                                        <!-- 13 -->
                                        <div class="form-group">
                                            <label for="pwd" class="col-xs-2    control-label">Check Out Date</label>
                                            <div class="col-xs-7">
                                                <input type="date" name="checkout_date" class="form-control" id="pwd" placeholder="">
                                            </div>
                                        </div>
                                        <!-- 14 -->
                                        <div class="form-group">
                                            <label for="pwd" class="col-xs-2    control-label">Total Numbe of Nights</label>
                                            <div class="col-xs-7">
                                                <input type="number" name="night_num" class="form-control" id="pwd" placeholder="">
                                            </div>
                                        </div>
                                        <!-- 15 -->
                                        <div class="form-group">
                                            <label for="pwd" class="col-xs-2    control-label">Booking Refference  </label>
                                            <div class="col-xs-7">
                                                <input type="text" name="reference_num" class="form-control" id="pwd" placeholder="">
                                            </div>
                                        </div>
                                        <!-- 17 -->
                                        <div class="form-group">
                                            <label for="pwd" class="col-xs-2    control-label">Booking Guest Details</label>

                                        </div>
                                        <!-- 18 -->
                                        <div class="form-group">
                                            <label for="pwd" class="col-xs-2    control-label">Adults</label>
                                            <div class="col-xs-7">
                                                <input type="number" name="adults" class="form-control" id="pwd" placeholder="">
                                            </div>
                                        </div>
                                        <!-- 19 -->
                                        <div class="form-group">
                                            <label for="pwd" class="col-xs-2    control-label">Childs</label>
                                            <div class="col-xs-7">
                                                <input type="number" name="child" class="form-control" id="pwd" placeholder="">
                                            </div>
                                        </div>
                                        <!-- 20 -->
                                        <div class="form-group">
                                            <label for="pwd" class="col-xs-2    control-label">Age</label>
                                            <div class="col-xs-7">
                                                <input type="number" name="age" class="form-control" id="pwd" placeholder="">
                                            </div>
                                        </div>
                            <div class="mt-4 mb-0">
                                <div class="d-grid"><button class="btn btn-primary">Submit</button></div>
                            </div>
                        </form>
                    </div>
                </div>

    <script src="https://unpkg.com/cropperjs"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      var inputImage = document.getElementById('inputImage');
      var image = document.getElementById('image');
      var cropButton = document.getElementById('cropButton');
      var cropper;

      inputImage.addEventListener('change', function (e) {
        var files = e.target.files;
        var reader = new FileReader();

        reader.onload = function (e) {
          image.src = e.target.result;

          // Initialize Cropper.js
          cropper = new Cropper(image, {
            aspectRatio: 1, // Set the aspect ratio for the crop area (width:height)
            viewMode: 2,    // Set the crop box to cover the entire canvas
          });
        };

        reader.readAsDataURL(files[0]);
      });

      cropButton.addEventListener('click', function () {
        // Get the cropped data as a base64-encoded string
        var croppedDataUrl = cropper.getCroppedCanvas().toDataURL('image/jpeg');

        // You can now send `croppedDataUrl` to your server or perform other actions
        console.log(croppedDataUrl);
      });
    });
  </script>

@endsection
