<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>@yield('title')</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <style>
            .img-fluid{
                width: 50px !important;
                height: 50px !important;
                border-radius: 50px !important;
            }
            .profile_image{
                width: 100px !important;
                height: 100px !important;
                border-radius: 50px !important;
            }
        </style>
    </head>
<body>
    @include('includes.header')
    <div class="container-fluid">
        @yield('content')
   </div>
</div>
<script>
    function printValidationErrorMsg(msg){
        $.each(msg, function(field_name, error){
            $(document).find('#'+field_name+'_error').text(error);
        });
    }
    function printErrorMsg(msg){
        $('#alert-danger').html('');
        $('#alert-danger').removeClass('d-none');
        $('#alert-danger').append(msg);
    }
    function printSuccessMsg(msg){
        $('#alert-success').html('');
        $('#alert-success').removeClass('d-none');
        $('#alert-success').append(msg);
    }
</script>
</body>

</html>
