<html>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js" type="text/javascript"   ></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/fontawesome.min.css" />  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css" />  
    <script type="text/javascript" src="{{ URL::asset('js/custom.js') }}"></script>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/5.0.7/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    {{-- <script type="text/javascript" src="{{ URL::asset('js/moment.min.js') }}"></script> --}}
    {{-- <link href="{{ URL::asset('css/jquery.datetimepicker.css') }}" rel="stylesheet">
    <script src="{{ URL::asset('js/jquery.datetimepicker.js') }}"></script>   --}}
    {{-- <script type="text/javascript" src="{{ URL::asset('js/freescript.js') }}"></script> --}}
    <head>
        <title>@yield('title')</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body> 
        <div class="header">
            <div id="mySidenav" class="sidenav">
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                <a href="{{ route('customer') }}" style="color: #f1f1f1 !important; padding: 14px 16px; font-size: 20px;">Customer & loan</a>
                <div class="subnav">
                    <button class="subnavbtn">Transaction <i class="fa fa-caret-down"></i></button>
                    <div class="subnav-content">
                        <a href="{{ route('incoming') }}">Incoming</a>
                        <a href="{{ route('outgoing') }}">Outgoing</a>
                        <a href="{{ route('transaction') }}">Account Transaction</a>
                    </div>
                </div>
                <div class="subnav">
                    <button class="subnavbtn">Report <i class="fa fa-caret-down"></i></button>
                    <div class="subnav-content">
                        <a href="#disbursement">Disbursement</a>
                        <a href="#NPL">NPL</a>
                        <a href="#business">Business Growth</a>
                    </div>
                </div>
            </div>
            <span class="hamburger" onclick="openNav()">&#9776;</span>
            <h3 align="left" style="color: #0877DE;"><img src="{{ URL::asset('assets/images/Logo.png') }}" alt="KDS Logo"> Koperasi Damai Sejahtera</h3><br />
        </div>

        <div class="content">
            @yield('content')
        </div>
        <div class="footer">
            <p>Teccho</p>
        </div>
    </body>
    @yield('javascript')
</html>

<script type="text/javascript">
    function openNav() {
        document.getElementById('mySidenav').style.width = '300px';
        $('.hamburger').hide();
    }

    function closeNav() {
        document.getElementById('mySidenav').style.width = '0';
        $('.hamburger').show();
    }
</script>

<style>
    body {
        background-image: url("{{ URL::asset('assets/images/Backgroundbg.png') }}");
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-position: right top; 
        font-family: Righteous;
        font-size: 40px;
        font-weight: 400;
        line-height: 45px;
        letter-spacing: 0em;
        text-align: left;
        height: auto;
        flex: 1;
    }

    .content {
        min-height: 700px;
        width: 90%;
        margin: auto;
        margin-bottom: 0;
    }

    .header {
        /* position: fixed; */
        margin: 25px;
    }

    .footer {
        clear: both;
        position: relative;
        left: 0;
        bottom: 0;
        width: 100%;
        background-color: #e7e7e7;
        color: black;
        text-align: center;
        font-size: 15px;
        height: 35px;
    }

    .hamburger {
        font-size: 30px;
        cursor: pointer;
        top: 20px;
        right: 20px;
        /* position: fixed; */
        float: right;
    }

    #mySidenav {
        height: 100%;
        width: 0px;
        position: fixed;
        z-index: 1;
        top: 0;
        right: 0;
        background-color: #111;
        overflow-x: hidden;
        padding-top: 60px;
        transition: 0.5s;
        opacity: 0.98;
    }

    #mySidenav a {
        padding: 8px 8px 8px 32px;
        text-decoration: none;
        font-size: 20px;
        color: #818181;
        display: block;
        transition: 0.3s
    }

    #mySidenav a:hover,
    #mySidenav .offcanvas a:focus .subnav:hover .subnavbtn{
        color: #f1f1f1;
    }

    #mySidenav .closebtn {
        position: absolute;
        top: 0;
        right: 25px;
        font-size: 20px;
        margin-left: 50px;
    }

    .subnav {
        /* float: left; */
        overflow: hidden;
    }

    /* Subnav button */
    .subnav .subnavbtn {
        font-size: 20px;
        border: none;
        outline: none;
        color: white;
        padding: 14px 16px;
        background-color: inherit;
        font-family: inherit;
        margin: 0;
    }

    /* Style the subnav content - positioned absolute */
    .subnav-content {
        display: none;
        /* position: absolute; */
        left: 0;
        width: 100%;
        z-index: 1;
    }

    /* Style the subnav links */
    .subnav-content a {
        /* float: left; */
        color: white;
        text-decoration: none;
    }

    /* Add a grey background color on hover */
    .subnav-content a:hover {
        background-color: rgb(37, 37, 37);
        color: black;
    }

    /* When you move the mouse over the subnav container, open the subnav content */
    .subnav:hover .subnav-content {
        display: block;
    }

    .container {
        min-width: 95%;
    }

    .jconfirm {
        font-family: Roboto;
        font-size: 15px;
        line-height: normal !important;
    }

    .form-group.required label:not(.labelCheckbox)::after,
    label.required::after{
        content: "*";
        color: red;
    }

    .form-group.required span.control-label::after {
        content: " *";
        color: red;
    }
</style>