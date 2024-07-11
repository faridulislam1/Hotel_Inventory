@extends('admin.master')
@section('content')
    <section class="my-5 ">
        <div class="container col-md-12">
            <div class="row">
                <div class="col-md-12 offset-1">
                    <div class="card col-md-12">
                        <div class="card-header bg-danger">
                            <i class="fas fa-table me-2"></i>
                            <h3 class="text-center my-0">DataName List</h3>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-striped table-hover table-bordered text-center" id="datatablesSimple" width="100%" cellspacing="0" >
                                <thead>
                                <tr>
                                    <th>sl</th>
                                    <th>country_id</th>
                                    <th>city_id</th>
                                    <th>hotel_id</th>
                                    <th>currency</th>
                                    <th>short_decription</th>
                                    <th>long_decription</th>
                                    <th>offer_fare</th>
                                    <th>public_fare</th>
                                    <th>policy</th>
                                    <th>term_condition</th>
                                    <th>cancellation_policy</th>
                                    <th>includes</th>
                                    <th>bed_type</th>
                                    <th>facalities</th>
                                    <th>room_features</th>
                                    <th>extra_features</th>
                                    <th>additional_services</th>
                                    <th>Action</th>

                                </tr>
                                </thead>
                                <tbody>
                                @php $i=1 @endphp
                                @foreach((array) $products as $product)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$product->country_id}}</td>
                                        <td>{{$product->city_id}}</td>
                                        <td>{{$product->hotel_id}}</td>
                                        <td>{{$product->currency}}</td>
                                        <td>{{$product->short_decription}}</td>
                                        <td>{{$product->long_decription}}</td>
                                        <td>{{$product->offer_fare}}</td>
                                        <td>{{$product->public_fare}}</td>
                                        <td>{{$product->policy}}</td>
                                        <td>{{$product->term_condition}}</td>
                                        <td>{{$product->cancellation_policy}}</td>
                                        <td>{{$product->includes}}</td>
                                        <td>{{$product->bed_type}}</td>
                                        <td>{{$product->facalities}}</td>
                                        <td>{{$product->room_features}}</td>
                                        <td>{{$product->extra_features}}</td>
                                        <td>{{$product->additional_services}}</td>
                                        

                                        <td  class="btn-group">
                                            <a href="{{route('edit.passenger',['id'=>$product->id]) }}" class="btn btn-primary btn-sm mx-1">edit</a>
                                            {{--                                            <a href="{{route('delete.product',['id'=>$product->id]) }}" class="btn btn-danger btn-sm">delete</a>--}}

                                            <form action="{{route('delete.passenger')}}" method="post">
                                                @csrf
                                                <input type="hidden" value="{{$product->id}}" name="id">
                                                <button type="submit" class="btn btn-danger btn-sm mx-1" onclick="return confirm('Are you sure Delete this !!')">Delete</button>

                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection