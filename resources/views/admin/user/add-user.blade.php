@extends('admin.master')
@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card shadow-lg border-0 rounded-lg mt-5">
                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Add User Form</h3></div>
                    <div class="card-body">
                        <form action="{{ route('new.user') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-floating mb-3">
                                <input class="form-control" type="text" name="name" id="productName" placeholder="" />
                                <label for="inputEmail">User Name</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input class="form-control" type="email" name="email" id="productName" placeholder="" />
                                <label for="inputEmail">Email</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input class="form-control" type="password" name="password" id="productName" placeholder="" />
                                <label for="inputEmail">Password</label>
                            </div>


                            <div class="mt-4 mb-0">
                                <div class="d-grid">
                                    <button class="btn btn-primary btn-block" type="submit">Save User</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection