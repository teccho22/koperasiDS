<html>
    <head>
        <title>Surat Peringatan</title>
        <style>
        body {
            width: 230mm;
            height: 100%;
            margin: 0 auto;
            padding: 0;
            font-size: 12pt;
            background: rgb(204,204,204); 
        }
        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }
        .main-page {
            width: 210mm;
            min-height: 297mm;
            margin: 10mm auto;
            background: white;
            box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
        }
        .sub-page {
            padding: 1cm;
            height: 297mm;
            line-height: 1.5;
        }
        @page {
            size: A4;
            margin: 0;
        }
        @media print {
            html, body {
                width: 210mm;
                height: 297mm;        
            }
            .main-page {
                margin: 0;
                border: initial;
                border-radius: initial;
                width: initial;
                min-height: initial;
                box-shadow: initial;
                background: initial;
                page-break-after: always;
            }
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js" type="text/javascript"></script>
    </head>
    <body>
        <div class="main-page">
        <div class="sub-page">
            <h1>KSP. DAMAI SEJAHTERA</h1>
            <p>Jl. Raya Langsep 2c No 2</p>
            <h3 align="center" id='spName' style="text-decoration: underline;"></h3>
            <p>Dengan Hormat,</p>
            <p>Bersama surat ini kami memberi peringatan kepada saudara:</p>
            <table style="border-spacing: 10px;">
            <tr>
                <td>Nama</td>
                <td style="padding-left: 75px;">:</td>
                <td><span id="custName" style="text-transform: capitalize;"></span></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td style="padding-left: 75px;">:</td>
                <td><span id="custAddress"></span></td>
            </tr>
            <tr>
                <td>Pokok Hutang</td>
                <td style="padding-left: 75px;">:</td>
                <td>Rp<span id="loanAmount"></span></td>
            </tr>
            <tr>
                <td>Cicilan per Bulan</td>
                <td style="padding-left: 75px;">:</td>
                <td>Rp<span id="installmentAmount"></span></td>
            </tr>
            <tr>
                <td>Durasi hutang</td>
                <td style="padding-left: 75px;">:</td>
                <td><span id="tenor"></span></td>
            </tr>
            </table>
            <p>untuk segera membayar cicilan hutangnya ke KSP Damai Sejahtera. <span id="paid"></span>, sehingga sampai dengan saat ini menunggak <span id="unpaid"></span></p>
            <p class="sp3" hidden>Jika dalam 30 hari sejak SP3 diterbitkan masih belum ada pembayaran hutang dari saudara, maka dengan sangat amat terpaksa KSP akan <span style="text-decoration: underline;"><b>MENJUAL</b></span> jaminan yang saudara titipkan kepada kami yaitu <b id="collateralDesc" style="text-decoration: underline;"></b>. Uang hasil penjualan kendaran akan kami pakai untuk pelunasan hutang, dan sisanya akan kami kembalikan ke saudara.</p>
            <p>Demikian pemberitahuan kami. Terima kasih atas perhatian anda.</p>
            <br>
            <p>Malang, <span id="date"></span></p>
            <br>
            <br>
            <br>
            <br>
            <p style="padding-left: 50px;">Priska Dwi Lujeng</p>
            <p>Manager Koperasi Damai Sejahtera</p>
        </div>    
        </div>
    </body>
    <script>
        var customerDetail = <?=$_POST['customerDetail']?>;
        var installmentDetail = <?=$_POST['installmentDetail']?>;
        var installmentPaid = <?=$_POST['installmentPaid']?>;
        var installmentUnpaid = <?=$_POST['installmentUnpaid']?>;
        var spNumber = <?=$_POST['spNumber']?>;
        var installmentPaidDetail = <?=$_POST['installmentPaidDetail']?>;
        // console.log(installmentPaidDetail);

        $('#spName').html('Surat Peringatan ' + spNumber + ' (SP' + spNumber + ')');
        $('#custName').html(customerDetail['customer_name']);
        $('#custAddress').html(customerDetail['customer_address']);
        $('#loanAmount').html(parseFloat(customerDetail['loan_amount'], 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString());
        $('#installmentAmount').html(parseFloat(customerDetail['installment_amount'], 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString());
        $('#tenor').html(customerDetail['tenor']);

        if (spNumber == 3)
        {
            $('.sp3').show();
        }

        if (installmentPaid[0]['total_paid'] > 0)
        {
            $('#paid').html('Yang bersangkutan baru membayar ' + installmentPaid[0]['count_paid'] + 'x cicilan pada ' + installmentPaidDetail['month']);
        }
        else
        {
            $('#paid').html('Yang bersangkutan belum membayar cicilan');
        }

        $('#unpaid').html(installmentUnpaid[0]['count_unpaid'] + 'x cicilan untuk bulan ' + installmentDetail['month'] + ' sejumlah Rp' + parseFloat(installmentUnpaid[0]['total_unpaid'], 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString() + '.');

        $('#collateralDesc').html(customerDetail['collateral_description']);

        Date.prototype.toShortFormat = function() {

            let monthNames =["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September", "Oktober","November","Desember"];

            let day = this.getDate();

            let monthIndex = this.getMonth();

            let monthName = monthNames[monthIndex];

            let year = this.getFullYear();

            return `${day} ${monthName} ${year}`;  

        }
        today = new Date();

        $('#date').html(today.toShortFormat());
        window.print();
    </script>
</html>