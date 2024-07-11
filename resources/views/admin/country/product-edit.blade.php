@extends('admin.master')
@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 ">
                <div class="card shadow-lg border-0 rounded-lg mt-2">
                    <div class="card-header bg-primary"><h3 class="text-center font-weight-light my-2">Edit Room Form</h3></div>
                    <div class="card-body">
                        <form action="{{route('update.room')}}" method="post" enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="id" value="{{ $product->id }}">

                           
                            <div class="col-md-10"><br>
                                            <input class="form-control" type="text" value="{{$product ->room_num }}" name="room_num" placeholder="Enter hotel name">
                                        </div>

                                        <div class="col-md-10"><br>
                                            <input class="form-control" type="text"  value="{{$product ->available_capacity }}" name="available_capacity"
                                                placeholder="Enter hotel Embed code">
                                        </div>

                                        <div class="col-md-10"><br>
                                            <input class="form-control" id="pac-input"  value="{{$product ->max_capacity }}" type="text" name="max_capacity"
                                                placeholder="Enter a location">

                                        </div>

                                      
                                        <div class="col-md-10"><br>
                                            <input class="form-control" type="text"  value="{{$product ->refundable }}"name="refundable"
                                                placeholder="Enter hotel address ">
                                        </div>
                                        <div class="col-md-10"><br>
                                            <input class="form-control" type="text"  value="{{$product ->non_refundable }}"name="non_refundable"
                                                placeholder="Enter hotel Property highlights">

                                        </div>
                                       
                                        <div class="col-md-10"><br>
                                            <input class="form-control" type="text"  value="{{$product ->refundable_break }}" name="refundable_break"
                                                placeholder="Enter Hotels Terms and Condition">
                                        </div>

                                        <div class="col-md-10"><br>
                                            <input class="form-control" type="text"  value="{{$product ->refundable_nonbreak }}" name="refundable_nonbreak"
                                                placeholder="Enter longitude">
                                        </div>

                                        <div class="col-md-10"><br>
                                            <input class="form-control" type="text"  value="{{$product ->extra_bed }}" name="extra_bed"
                                                placeholder="Enter litetitude">
                                        </div>

                                        <div class="col-md-10"><br>
                                            <input class="form-control" type="text"  value="{{$product ->room_size }}" name="room_size"
                                                placeholder="Enter litetitude">
                                        </div>
                                        <div class="col-md-10"><br>
                                            <input class="form-control" type="text"  value="{{$product ->cancellation_policy }}" name="cancellation_policy"
                                                placeholder="Enter litetitude">
                                        </div>
                                        <div class="col-md-10"><br>
                                            <input class="form-control" type="text"  value="{{$product ->room_available }}" name="room_available"
                                                placeholder="Enter litetitude">
                                        </div>

                                        <div class="col-md-10"><br>
                                            <input class="form-control" type="text"  value="{{$product ->bed_type }}" name="bed_type"
                                                placeholder="Enter litetitude">
                                        </div>
                                        <div class="col-md-10"><br>
                                            <textarea class="form-control" name="room_facilities" placeholder="Enter litetitude">{{$product->room_facilities}}</textarea>
                                        </div>


                                    </div><br>

                            <div class="mt-4 mb-0">
                                <div class="d-grid"><button class="btn btn-primary">Submit</button></div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection