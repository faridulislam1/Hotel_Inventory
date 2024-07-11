@extends('admin.master')
@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 ">
                <div class="card shadow-lg border-0 rounded-lg mt-2">
                    <div class="card-header bg-primary"><h3 class="text-center font-weight-light my-2">Edit Data Form</h3></div>
                    <div class="card-body">
                        <form action="{{route('update.passenger')}}" method="post" enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="id" value="{{ $product->id }}">

                
                              <div class="form-group row">
                            <label for="" style="margin-left:3px;" class="col-sm-3 control-label"><b>Title Name</b> <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-control" name="title" id="categoryId">
                                    <option value="" disabled selected> -- Select Title -- </option>
                                    <option value="Title1" @if($product->title == 'Title1') selected @endif>Title1</option>
                                    <option value="Title2" @if($product->title == 'Title2') selected @endif>Title2</option>
                                </select>
                            </div>
                        </div>


                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3 mb-md-0">
                                        <input class="form-control" name="first_name" value="{{$product ->first_name }}" id="inputFirstName" type="text"/>
                                        <label for="inputFirstName">first_name</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input class="form-control" name="last_name" value="{{$product ->last_name }}" id="inputLastName" type="text"  />
                                        <label for="inputLastName">last_name</label>
                                    </div>
                                </div>
                          
                            
                              <div class="form-group row">
                            <label for="" style="margin-left:3px;" class="col-sm-3 control-label"><b>Gender Name</b> <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-control" name="gender" id="categoryId">
                                    <option value="" disabled selected> -- Select Gender -- </option>
                                    <option value="Male" @if($product->gender == 'Male') selected @endif>Male</option>
                                    <option value="Female" @if($product->gender == 'Female') selected @endif>Female</option>
                                </select>
                            </div>
                        </div>

                                                
                        <div class="form-group row">
                    <label for="" style="margin-left:3px;" class="col-sm-3 control-label"><b>Date of Birth</b> <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <select class="form-control" name="date_birth" id="categoryId">
                            <option value="" disabled selected> -- Select date_birth -- </option>
                            @for ($i = 1; $i <= 31; $i++)
                                <option value="{{ $i }}" @if($product->date_birth == $i) selected @endif>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                        
                                <div class="form-group row">
                        <label for="" style="margin-left:3px;" class="col-sm-3 control-label"><b>Nationality</b> <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <select class="form-control" name="nationality" id="categoryId">
                                <option value="" disabled selected> -- Select nationality -- </option>
                                <option value="Nationality1" @if($product->nationality == 'Nationality1') selected @endif>Nationality1</option>
                                <option value="Nationality2" @if($product->nationality == 'Nationality2') selected @endif>Nationality2</option>
                            </select>
                        </div>
                    </div>

                          
                            <div class="col-md-6">
                                    <div class="form-floating">
                                        <input class="form-control" name="address" value="{{$product ->address }}" id="inputLastName" type="text"  />
                                        <label for="inputLastName">address</label>
                                    </div>
                                </div>
                           
                            <div class="col-md-6">
                                    <div class="form-floating">
                                        <input class="form-control" name="address_two" value="{{$product ->address_two }}"  id="inputLastName" type="text"  />
                                        <label for="inputLastName">address_two</label>
                                    </div>
                                </div>
                            
                                <div class="form-group row">
                            <label for="" style="margin-left:3px;" class="col-sm-3 control-label"><b>City</b> <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-control" name="city" id="categoryId">
                                    <option value="" disabled selected> -- Select city -- </option>
                                    <option value="City1" @if($product->city == 'City1') selected @endif>City1</option>
                                    <option value="City2" @if($product->city == 'City2') selected @endif>City2</option>
                                </select>
                            </div>
                        </div>


                                    <div class="row mb-12">
                                <div class="form-floating mb-3 mb-md-0">
                                    <input name="code" id="" value="{{$product ->code }}" class="form-control"></textarea>
                                    <label for="inputPassword">code</label>
                                </div>
                            </div>
                            <div class="row mb-12">
                                <div class="form-floating mb-3 mb-md-0">
                                    <input name="email" id="" value="{{$product ->email }}"class="form-control"></textarea>
                                    <label for="inputPassword">email</label>
                                </div>
                            </div>
                            <div class="row mb-12">
                                <div class="form-floating mb-3 mb-md-0">
                                    <input name="contact_num" id="" value="{{$product ->contact_num }}"  class="form-control"></textarea>
                                    <label for="inputPassword">contact_num</label>
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