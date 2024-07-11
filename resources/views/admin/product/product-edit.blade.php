@extends('admin.master')
@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 ">
                <div class="card shadow-lg border-0 rounded-lg mt-2">
                    <div class="card-header bg-primary"><h3 class="text-center font-weight-light my-2">Edit Data Form</h3></div>
                    <div class="card-body">
                        <form action="{{route('update.product')}}" method="post" enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="id" value="{{ $product->id }}">

                            <!-- <div class="form-floating mb-3">
                                <input class="form-control" name="country" value="{{$product ->country }}" id="productName" type="text"/>
                                <label for="productName">Country</label>
                            </div> -->
                            <div class="col-md-10"><br>
                                            <input class="form-control" type="text" value="{{$product ->hotel }}" name="hotel" placeholder="Enter hotel name">
                                        </div>

                                        <div class="col-md-10"><br>
                                            <input class="form-control" type="text"  value="{{$product ->hotel }}" name="embed_code"
                                                placeholder="Enter hotel Embed code">
                                        </div>

                                        <div class="col-md-10"><br>
                                            <input class="form-control" id="pac-input"  value="{{$product ->hotel }}" type="text" name="landmark"
                                                placeholder="Enter a location">

                                        </div>

                                        <!-- <div class="form-group col-md-12"><br>
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
                                        </div><br> -->

                                        <div class="col-md-10"><br>
                                            <input class="form-control" type="text"  value="{{$product ->address }}"name="address"
                                                placeholder="Enter hotel address ">
                                        </div>
                                        <div class="col-md-10"><br>
                                            <input class="form-control" type="text"  value="{{$product ->highlights }}"name="highlights"
                                                placeholder="Enter hotel Property highlights">

                                        </div>

                                        <div class="col-md-10"><br>
                                        <textarea class="form-control" name="long_decription" placeholder="Enter Hotel Description">{{$product->long_decription}}</textarea>

                                        </div>
                                       
                                        <div class="col-md-10"><br>
                                            <input class="form-control" type="text"  value="{{$product ->term_condition }}" name="term_condition"
                                                placeholder="Enter Hotels Terms and Condition">
                                        </div>

                                        <div class="col-md-10"><br>
                                            <input class="form-control" type="text"  value="{{$product ->longitude }}" name="longitude"
                                                placeholder="Enter longitude">
                                        </div>

                                        <div class="col-md-10"><br>
                                            <input class="form-control" type="text"  value="{{$product ->litetitude }}" name="litetitude"
                                                placeholder="Enter litetitude">
                                        </div>
                                        <div class="col-md-10"><br>
                                        <textarea class="form-control" name="facilities" placeholder="Enter facilities">{{$product->facilities}}</textarea>
                                            
                                        </div>
                                    </div><br>
                            <div class="col-md-6 mt-3">
                                <div class="form-floating mb-3 mb-md-0">
                                    <input type="file" name="Single_image" class="form-control">
                                    <img src="{{ asset ($product->Single_image)}}" class="img-fluid" style="width:80px;height:70px;" alt="">
                                </div>
                            </div>

                            <!-- <div class="col-md-6 mt-3">
                        <div class="form-floating mb-3 mb-md-0">
                            <input type="file" name="multiple_image[]" class="form-control" accept="image/*">

                            @if($product->getMultiImageUrl && is_array($product->getMultiImageUrl))
                                @foreach($product->getMultiImageUrl as $otherImage)
                                    <img src="{{ asset($otherImage->multiple_image) }}" alt="" height="100" width="130"/>
                                @endforeach
                            @endif
                        </div>
                    </div> -->


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