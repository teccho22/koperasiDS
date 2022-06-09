<html>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css">
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
        background-image: url("/assets/images/Backgroundbg.png");
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-position: right; 
        font-family: Righteous;
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