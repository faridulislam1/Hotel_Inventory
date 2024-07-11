@extends('admin.master')
@section('content')
<div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                            <h3 class="text-center my-0">Room data List</h3>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-striped table-hover table-bordered text-center" id="datatablesSimple" width="100%" cellspacing="0" >
                                <thead>
                                <tr>


                                    <th>sl</th>
                                    <th>room_name</th>
                                    <th>available_capacity</th>
                                    <th>max_capacity</th>
                                    <th>refundable</th>
                                    <th>non_refundable</th>
                                    <th>refundable_break</th>
                                    <th style="width:20px;">refundable_nonbreak</th>
                                    <th>extra_bed</th>
                                    <th>room_size</th>
                                    <th>cancellation_policy</th>
                                    <th>room_available</th>
                                    <th>bed_type</th>
                                    <th>room_facilities</th>
                                    <th>Action</th>

                                </tr>
                                </thead>
                                <tbody>
                                @php $i=1 @endphp
                                @foreach($products as $product)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$product->room_num}}</td>
                                        <td>{{$product->available_capacity}}</td>
                                        <td>{{$product->max_capacity}}</td>
                                        <td>{{$product->refundable}}</td>
                                        <td>{{$product->non_refundable}}</td>
                                        <td>{{$product->refundable_break}}</td>
                                        <td>{{$product->refundable_nonbreak}}</td>
                                        <td>{{$product->extra_bed}}</td>
                                        <td>{{$product->room_size}}</td>
                                        <td>{{$product->cancellation_policy}}</td>
                                        <td>{{$product->room_available}}</td>
                                        <td>{{$product->bed_type}}</td>
                                        <td>{{$product->room_facilities}}</td>
                                        <td  class="btn-group">

                                            <a href="{{route('edit.room',['id'=>$product->id]) }}" class="btn btn-primary btn-sm mx-1">edit</a>

                                            <form action="{{route('delete.room')}}" method="post">
                                                @csrf
                                                <input type="hidden" value="{{$product->id}}" name="id">
                                                <button type="submit" class="btn btn-danger btn-sm mx-1" onclick="return confirm('Are you sure Delete this !!')">Delete</button>

                                            </form>
                                        </td>
 
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
