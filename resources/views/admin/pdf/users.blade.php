{{--<!DOCTYPE html>--}}
{{--<html>--}}
{{--<head>--}}
{{--    <style>--}}
{{--        #customers {--}}
{{--            font-family: Arial, Helvetica, sans-serif;--}}
{{--            border-collapse: collapse;--}}
{{--            width: 100%;--}}
{{--        }--}}

{{--        #customers td, #customers th {--}}
{{--            border: 1px solid #ddd;--}}
{{--            padding: 8px;--}}
{{--        }--}}

{{--        #customers tr:nth-child(even){background-color: #f2f2f2;}--}}

{{--        #customers tr:hover {background-color: #ddd;}--}}

{{--        #customers th {--}}
{{--            padding-top: 12px;--}}
{{--            padding-bottom: 12px;--}}
{{--            text-align: left;--}}
{{--            background-color: #04AA6D;--}}
{{--            color: white;--}}
{{--        }--}}
{{--        .pdf{--}}
{{--            text-align: center;--}}
{{--        }--}}
{{--    </style>--}}
{{--</head>--}}
{{--<body>--}}

{{--<h1 class="pdf">Invoice table</h1>--}}

{{--<table id="customers">--}}
{{--    <thead>--}}
{{--    <tr>--}}
{{--        <th>sl</th>--}}
{{--        <th>Trip_id</th>--}}
{{--        <th>hotel_name</th>--}}
{{--        <th>address</th>--}}
{{--        <th>hotel_num</th>--}}
{{--        <th>room_type</th>--}}
{{--        <th>room_num</th>--}}
{{--        <th>includes_hotel</th>--}}
{{--        <th>rating</th>--}}
{{--        <th>lead_guest</th>--}}
{{--        <th>guest_num</th>--}}
{{--        <th>checkin_date</th>--}}
{{--        <th>checkout_date</th>--}}
{{--        <th>night_num</th>--}}
{{--        <th>reference_num</th>--}}
{{--        <th>adults</th>--}}
{{--        <th>child</th>--}}
{{--        <th>age</th>--}}
{{--        <th>Action</th>--}}
{{--    </tr>--}}
{{--    </thead>--}}
{{--    <tbody>--}}
{{--@if(isset($product))--}}
{{--    <table id="customers">--}}
{{--        <thead>--}}
{{--        <tr>--}}
{{--            <th>sl</th>--}}
{{--            <th>Trip_id</th>--}}
{{--            <th>hotel_name</th>--}}
{{--            <th>address</th>--}}
{{--            <th>hotel_num</th>--}}
{{--            <th>room_type</th>--}}
{{--            <th>room_num</th>--}}
{{--            <th>includes_hotel</th>--}}
{{--            <th>rating</th>--}}
{{--            <th>lead_guest</th>--}}
{{--            <th>guest_num</th>--}}
{{--            <th>checkin_date</th>--}}
{{--            <th>checkout_date</th>--}}
{{--            <th>night_num</th>--}}
{{--            <th>reference_num</th>--}}
{{--            <th>adults</th>--}}
{{--            <th>child</th>--}}
{{--            <th>age</th>--}}
{{--        </tr>--}}
{{--        </thead>--}}
{{--        <tbody>--}}
{{--        <tr>--}}

{{--            <td>{{$product->trip_id}}</td>--}}
{{--            <td>{{$product->hotel_name}}</td>--}}
{{--            <td>{{$product->address}}</td>--}}
{{--            <td>{{$product->hotel_num}}</td>--}}
{{--            <td>{{$product->room_type}}</td>--}}
{{--            <td>{{$product->room_num}}</td>--}}
{{--            <td>{{$product->includes_hotel}}</td>--}}
{{--            <td>{{$product->rating}}</td>--}}
{{--            <td>{{$product->lead_guest}}</td>--}}
{{--            <td>{{$product->guest_num}}</td>--}}
{{--            <td>{{$product->checkin_date}}</td>--}}
{{--            <td>{{$product->checkout_date}}</td>--}}
{{--            <td>{{$product->night_num}}</td>--}}
{{--            <td>{{$product->reference_num}}</td>--}}
{{--            <td>{{$product->adults}}</td>--}}
{{--            <td>{{$product->child}}</td>--}}
{{--            <td>{{$product->age}}</td>--}}
{{--        </tr>--}}
{{--        </tbody>--}}
{{--    </table>--}}
{{--@else--}}
{{--    <p>No product found</p>--}}
{{--@endif--}}



{{--</body>--}}
{{--</html>--}}



    <!DOCTYPE html>
<html>
<head>
    <style>
        #customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #customers td, #customers th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #customers tr:nth-child(even){
            background-color: #f2f2f2;
        }

        #customers tr:hover {
            background-color: #ddd;
        }

        #customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #04AA6D;
            color: white;
        }
        .pdf {
            text-align: center;
        }
    </style>
</head>
<body>

<h1 class="pdf">Invoice table</h1>

@if(isset($product))
    <table id="customers">
        <thead>
        <tr>
            <th>Field</th>
            <th>Value</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Trip ID</td>
            <td>{{$product->trip_id}}</td>
        </tr>
        <tr>
            <td>Hotel Name</td>
            <td>{{$product->hotel_name}}</td>
        </tr>
        <tr>
            <td>Address</td>
            <td>{{$product->address}}</td>
        </tr>
        <tr>
            <td>Hotel Number</td>
            <td>{{$product->hotel_num}}</td>
        </tr>
        <tr>
            <td>Room Type</td>
            <td>{{$product->room_type}}</td>
        </tr>
        <tr>
            <td>Room Number</td>
            <td>{{$product->room_num}}</td>
        </tr>
        <tr>
            <td>Includes Hotel</td>
            <td>{{$product->includes_hotel}}</td>
        </tr>
        <tr>
            <td>Rating</td>
            <td>{{$product->rating}}</td>
        </tr>
        <tr>
            <td>Lead Guest</td>
            <td>{{$product->lead_guest}}</td>
        </tr>
        <tr>
            <td>Guest Number</td>
            <td>{{$product->guest_num}}</td>
        </tr>
        <tr>
            <td>Check-in Date</td>
            <td>{{$product->checkin_date}}</td>
        </tr>
        <tr>
            <td>Check-out Date</td>
            <td>{{$product->checkout_date}}</td>
        </tr>
        <tr>
            <td>Night Number</td>
            <td>{{$product->night_num}}</td>
        </tr>
        <tr>
            <td>Reference Number</td>
            <td>{{$product->reference_num}}</td>
        </tr>
        <tr>
            <td>Adults</td>
            <td>{{$product->adults}}</td>
        </tr>
        <tr>
            <td>Child</td>
            <td>{{$product->child}}</td>
        </tr>
        <tr>
            <td>Age</td>
            <td>{{$product->age}}</td>
        </tr>
        </tbody>
    </table>
@else
    <p>No product found</p>
@endif

</body>
</html>




