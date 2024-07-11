@extends('admin.master')
@section('content')
    <div class="col-md-12">
        <div class="form-group">
            <div class="row"><br>
                <div class="col-12"><br>
                        <div class="card-header bg-danger">
                            <i class="fas fa-table me-2"></i>
                            <h3 class="text-center my-0">Invoice List</h3>
                        </div>

                        <div class="card-body table-responsive">
                            <table class="table table-striped table-hover table-bordered"  id="datatablesSimple">
                                <thead>
                                <tr>
                                    <th>sl</th>
                                    <th>Trip_id</th>
                                    <th>hotel_name</th>
                                    <th>address</th>
                                    <th>hotel_num</th>
                                    <th>room_type</th>
                                    <th>room_num</th>
                                    <th>includes_hotel</th>
                                    <th>rating</th>
                                    <th>lead_guest</th>
                                    <th>guest_num</th>
                                    <th>checkin_date</th>
                                    <th>checkout_date</th>
                                    <th>night_num</th>
                                    <th>reference_num</th>
                                    <th>adults</th>
                                    <th>child</th>
                                    <th>age</th>
                                    <th>Action</th>

                                </tr>
                                </thead>
                                <tbody>
                                @php $i=1 @endphp
                                @foreach($products as $product)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$product->trip_id}}</td>
                                        <td>{{$product->hotel_name}}</td>
                                        <td>{{$product->address}}</td>
                                        <td>{{$product->hotel_num}}</td>
                                        <td>{{$product->room_type}}</td>
                                        <td>{{$product->room_num}}</td>
                                        <td>{{$product->includes_hotel}}</td>
                                        <td>{{$product->rating}}</td>
                                        <td>{{$product->lead_guest}}</td>
                                        <td>{{$product->guest_num}}</td>
                                        <td>{{$product->checkin_date}}</td>
                                        <td>{{$product->checkout_date}}</td>
                                        <td>{{$product->night_num}}</td>
                                        <td>{{$product->reference_num}}</td>
                                        <td>{{$product->adults}}</td>
                                        <td>{{$product->child}}</td>
                                        <td>{{$product->age}}</td>


                                        <td  class="btn-group">
                                            <div>
                                                <a class="btn btn-success" href="{{ route('export_user_pdf', ['id' => $product->id]) }}">Export Pdf</a>
                                            </div>


                                            <a href="{{route('edit.product',['id'=>$product->id]) }}" class="btn btn-primary btn-sm mx-1">edit</a>
                                            {{--                                            <a href="{{route('delete.product',['id'=>$product->id]) }}" class="btn btn-danger btn-sm">delete</a>--}}

                                            <form action="{{route('delete.product')}}" method="post">
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
