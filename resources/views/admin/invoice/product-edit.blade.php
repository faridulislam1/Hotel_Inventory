@extends('admin.master')
@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 ">
                <div class="card shadow-lg border-0 rounded-lg mt-2">
                    <div class="card-header bg-primary"><h3 class="text-center font-weight-light my-2">Edit Data Form</h3></div>
                    <div class="card-body">
                        <form action="{{route('update.product')}}" method="post" enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="id" value="{{ $product->id }}">

                            <div class="form-floating mb-3">
                                <input class="form-control" name="country" value="{{$product ->country }}" id="productName" type="text"/>
                                <label for="productName">Country</label>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3 mb-md-0">
                                        <input class="form-control" name="hotel" value="{{$product ->hotel }}" id="inputFirstName" type="text"/>
                                        <label for="inputFirstName">NAME OF THE HOTEL</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input class="form-control" name="location" value="{{$product ->location }}" id="inputLastName" type="text"  />
                                        <label for="inputLastName">HOTEL LOCATION</label>
                                    </div>
                                </div>
                          <br><br>
                            <div class="col-md-6">
                                    <div class="form-floating">
                                        <input class="form-control" name="rating" value="{{$product ->rating }}" id="inputLastName" type="text"  />
                                        <label for="inputLastName">STAR RATING</label>
                                    </div>
                                </div>
                           
                            <div class="col-md-6">
                                    <div class="form-floating">
                                        <input class="form-control" name="latitude" value="{{$product ->latitude }}" id="inputLastName" type="text"  />
                                        <label for="inputLastName">HOTEL LATITUDE</label>
                                    </div>
                                </div>
                        
                            <div class="col-md-6">
                                    <div class="form-floating">
                                        <input class="form-control" name="longitude" value="{{$product ->longitude }}" id="inputLastName" type="text"  />
                                        <label for="inputLastName">HOTEL LONGITUDE</label>
                                    </div>
                                </div>
                          
                            <div class="col-md-6">
                                    <div class="form-floating">
                                        <input class="form-control" name="landmark" value="{{$product ->landmark }}" id="inputLastName" type="text"  />
                                        <label for="inputLastName">HOTEL LANDMARK</label>
                                    </div>
                                </div>
                           
                            <div class="col-md-6">
                                    <div class="form-floating">
                                        <input class="form-control" name="offer_price" value="{{$product ->offer_price }}" id="inputLastName" type="text"  />
                                        <label for="inputLastName">HOTEL OFFER-PRICE</label>
                                    </div>
                                </div>
                            
                            <div class="col-md-6">
                                    <div class="form-floating">
                                        <input class="form-control" name="public_price" value="{{$product ->public_price }}" id="inputLastName" type="text"  />
                                        <label for="inputLastName">HOTEL PUBLIC-PRICE</label>
                                    </div>
                                </div>
                                </div>
                           

                            <div class="row mb-12">
                                <div class="form-floating mb-3 mb-md-0">
                                    <input name="policy" value="{{$product ->policy }}" id="" class="form-control">
                                    <label for="inputPassword">HOTEL POLICY</label>
                                </div>
                            </div>
                            <div class="row mb-12">
                                <div class="form-floating mb-3 mb-md-0">
                                    <input name="amenities" value="{{$product ->amenities }}" id="" class="form-control">
                                    <label for="inputPassword">HOTEL AMENITIES</label>
                                </div>
                            </div>
                            <div class="row mb-12">
                                <div class="form-floating mb-3 mb-md-0">
                                    <input name="description" value="{{$product ->description }}" id="" class="form-control">
                                    <label for="inputPassword">HOTEL DESCRIPTION</label>
                                </div>
                            </div>
                            <div class="row mb-12">
                                <div class="form-floating mb-3 mb-md-0">
                                    <input name="term_condition" value="{{$product ->term_condition }}" id="" class="form-control">
                                    <label for="inputPassword">TERMS & CONDITONS</label>
                                </div>
                            </div>
                            <div class="row mb-12">
                                <div class="form-floating mb-3 mb-md-0">
                                    <input name="cancellation" value="{{$product ->cancellation }}" id="" class="form-control">
                                    <label for="inputPassword">CANCELLATION POLICY</label>
                                </div>
                            </div>

                            <div class="col-md-6 mt-3">
                                <div class="form-floating mb-3 mb-md-0">
                                    <input type="file" name="image" class="form-control">
                                    <img src="{{ asset ($product->image)}}" class="img-fluid" style="width:80px;height:50px;border-radius:50%;" alt="">
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

@endsection