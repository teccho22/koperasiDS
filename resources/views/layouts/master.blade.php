<html>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="icon" href="{{ URL::asset('assets/images/Logo.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <head>
        <title>@yield('title')</title>
    </head>
    <body> 
        <div class="content">
            @yield('content')
        </div>
    </body>
</html>

<style>
    body {
        background-image: url("{{ URL::asset('assets/images/Backgroundbg.png') }}");
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-position: right;
        background-size: contain;
        font-family: 'Righteous', cursive !important;
        font-size: 36px;
        font-weight: 400;
        line-height: 45px;
        letter-spacing: 0em;
        text-align: left;
    }

    .content {
        height: 100%;
        width: 100%;
    }
</style>