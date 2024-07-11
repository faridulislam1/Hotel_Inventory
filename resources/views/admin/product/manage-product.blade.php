@extends('admin.master')
@section('content')
<div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                            <h3 class="text-center my-0">Hotel Info List</h3>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-striped table-hover table-bordered text-center" id="datatablesSimple" width="100%" cellspacing="0" >
                                <thead>
                                <tr>
                                    <th>sl</th>
                                    <th>Hotel Name</th>
                                    <th style="width:5px;">embed_code</th>
                                    <th>landmark</th>
                                    <th>rating</th>
                                    <th>Single_image</th>
                                    <!-- <th>multiple_image</th> -->
                                    <th>address</th>
                                    <th>highlights</th>
                                    <th>long_decription</th>
                                    <th>currency</th>
                                    <th>term_condition</th>
                                    <th>longitude</th>
                                    <th>litetitude</th>
                                    <th>facilities</th>
                                    <th>Action</th>

                                </tr>
                                </thead>
                                <tbody>
                                @php $i=1 @endphp
                                @foreach($products as $product)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$product->hotel}}</td>
                                        <td>{{$product->embed_code}}</td>
                                        <td>{{$product->landmark}}</td>
                                        <td>{{$product->rating}}</td>
                                        <td>
                                            <img src="{{ asset ($product->Single_image)}}" class="img-fluid" style="width:80px;height:50px;" alt="">
                                        </td>
                                        <!-- <td>
                                        @foreach(explode(',', $product->multiple_image) as $image)
                                            <img src="{{ asset(trim($image)) }}" class="img-fluid" style="width:80px;height:50px;margin-right: 5px;" alt="">
                                        @endforeach
                                    </td> -->


                                        <!-- <td>{{$product->multiple_image}}</td> -->
                                        <td>{{$product->address}}</td>
                                        <td>{{$product->highlights}}</td>
                                        <td>{{$product->long_decription}}</td>
                                        <td>{{$product->currency}}</td>
                                        <td>{{$product->term_condition}}</td>
                                        <td>{{$product->longitude}}</td>
                                        <td>{{$product->litetitude}}</td>
                                        <td>{{$product->facilities}}</td>
                            
                                        <!-- <td>
                                            <img src="{{ asset ($product->image)}}" class="img-fluid" style="width:80px;height:50px;border-radius:50%;" alt="">
                                        </td> -->
                                        
    
                                        <td  class="btn-group">

                                        <!-- <a href="{{route('product.detail', ['id' => $product->id])}}" class="btn btn-info btn-sm">
                                          View
                                        </a> -->
                                            <a href="{{route('edit.product',['id'=>$product->id]) }}" class="btn btn-primary btn-sm mx-1">Edit</a>

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