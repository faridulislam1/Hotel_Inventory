@extends('admin.master')
@section('content')
    <div class="row mt-3">
        <div class="col-12 ">
            <div class="">
                <div class="card-body">
                    <h4 class="card-title">All Hotel information</h4>
                    <div class="table-responsive m-t-40">
                        <p class="text-center text-success">{{Session::get('message')}}</p>
                        <table class="hotel-details">
                            <tr>
                                <th><b>Country:</b></th>
                                <td><strong>{{$hotel->country}}</strong></td>
                            </tr>
                            <tr>
                                <th><b>Place:</b></th>
                                <td><strong>{{$hotel->city}}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="font-size: 15px;">{{$hotel->description}}</td>
                            </tr>
                        </table>

                        <table class="room-details">
                            
                            @foreach($hotel->rooms as $key => $room)
                                <tr>
                                    <td>
                                        <table class="day-details">
                                            <tr>
                                                <th>Day {{ $key + 1 }}:</th>
                                                <td><b>{{ $room->title }}</b></td>
                                            </tr>
                                            <tr>
                                                <td>{{ $room->description }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            @endforeach
                        </table>

        </td>
    </tr>
</table>



                    </div>
                </div>
            </div>

        </div>
    </div>

    
@endsection