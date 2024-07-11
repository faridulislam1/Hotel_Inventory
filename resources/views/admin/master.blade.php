<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="{{asset('admin-assets')}}/css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
@include('admin.include.header')

<div id="layoutSidenav">
    @include('admin.include.left-sidebar')

    <div id="layoutSidenav_content">

        <main>
          @yield('content')
        </main>
        @include('admin.include.footer')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="{{asset('admin-assets')}}/js/scripts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="assets/demo/chart-area-demo.js"></script>
<script src="assets/demo/chart-bar-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
<script src="{{asset('admin-assets')}}/js/datatables-simple-demo.js"></script>

<script>

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
    $(function(){
        $(document).on('change', '#countryid', function () {
            var countryId = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{ route('product.get-subcategory-by-category') }}", // Adjust the route name
                data: {id: countryId},
                dataType: "JSON", // Corrected spelling of 'dataType'
                success: function(response){
                    var cityId = $('#cityid');
                    cityId.empty();
                    var option = '<option value=""  selected>-- Select City --</option>';
                    $.each(response, function(key, value){
                        option += '<option value="'+value.id+'"> '+value.city+'</option>';
                    });
                    cityId.append(option);
                }
            });
        });

        $(document).on('change', '#cityid', function () {
            var countryId = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{ route('product.hotel') }}", // Adjust the route name
                data: {id: countryId},
                dataType: "JSON", // Corrected spelling of 'dataType'
                success: function(response){
                    var cityId = $('#hotelid');
                    cityId.empty();
                    var option = '<option value=""  selected>-- Select hotel --</option>';
                    $.each(response, function(key, value){
                        option += '<option value="'+value.id+'"> '+value.hotel+'</option>';
                    });
                    cityId.append(option);
                }
            });
        });
    });
</script>

</body>
</html>
