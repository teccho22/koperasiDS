<html>
    <head>
        <title>Perjanjian Kredit</title>
        <style>
            body {
                font-family: "Calibri (body)";
                font-size: 15pt;
                line-height: 1.15;
                text-align: justify;
            }
            @page {
                size: A4;
                margin: 1.5cm 2.2cm 1.5cm 2.2cm !important;
            }
            @media print {
                html, body {
                    width: 210mm;
                    height: 297mm;
                }
                .main-page {

                    border: initial;
                    border-radius: initial;
                    width: initial;
                    min-height: initial;
                    box-shadow: initial;
                    background: initial;
                    page-break-after: always;
                }
            }
            table {border: none;}
            tr, td {
                font-size: 15pt;
                padding-bottom: 5px;
            }
            li {
                margin-bottom: 10px;
                text-indent: 0.63cm hanging each-line !important;
                padding-left: 0.63cm;
            }
            p {
                margin-bottom: 10px;
            }
        </style>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js" type="text/javascript"></script>
    </head>
    <body>
        <div class="main-page">
            <div class="sub-page">
                <div align="right">
                    <!-- dev -->
                    <!-- <img src="/assets/images/Logo.png" alt="KDS Logo" style="margin-right: 20px;"> -->
                    <!-- prod -->
                    <img src="/public/assets/images/Logo.png" alt="KDS Logo" style="margin-right: 20px;">
                </div>
                <h1 align="center" style="font-weight: bold; text-decoration: underline;">Perjanjian Kredit</h1>
                <p align="center">No: KDS/<span id="customerId"></span>/B3/<span id="loanYear"></span></p>
                <br>
                <p>Pada tanggal, <span class="loanDate"></span>, kami yang disebut dibawah ini,</p>
                <div class="numberingFormat">
                    <ol type="1">
                        <li>Priska Dwi Lujeng, Manager, dalam hal ini bertindak dan bertanggung jawab menurut jabatannya tersebut, mewakili Koperasi Damai Sejahtera, yang selanjutnya akan disebut sebagai KOPERASI, dan</li>
                        <li><span class="customerName"></span>, yang dalam hal ini bertindak untuk dirinya sendiri, yang selanjutnya disebut PEMINJAM,</li>
                    </ol>
                </div>
                <p>membuat perjanjian kredit dengan ketentuan-ketentuan sebagaimana dijabarkan pada pasal-pasal berikut ini.</p>
                <br>
                <p align="center" style="font-weight: bold; text-decoration: underline; margin-top:10px;">Pasal 1 : Biodata Peminjam</p>
                <table style="width: 100%;">
                    <tr>
                        <td>Nama</td>
                        <td>: <span class="customerName"></span></td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>: <span class="customerAddress"></span></td>
                    </tr>
                    <!-- <tr>
                        <td>Tempat & Tanggal Lahir</td>
                        <td>: <span class="customerName"></span></td>
                    </tr> -->
                    <tr>
                        <td>No Telp</td>
                        <td>: <span class="customerPhone"></span></td>
                    </tr>
                    <tr>
                        <td>No KTP</td>
                        <td>: <span class="customerIdNumber"></span></td>
                    </tr>
                    <tr>
                        <td>Pekerjaan</td>
                        <td>: <span class="customerProffesion"></span></td>
                    </tr>
                </table>
                <br>
                <p align="center" style="font-weight: bold; text-decoration: underline; margin-top:10px;">Pasal 2 : Detail Pinjaman</p>
                <table style="width: 100%;">
                    <tr>
                        <td>Pokok Pinjaman</td>
                        <td>: Rp <span class="loanAmount"></span>,-</td>
                    </tr>
                    <tr>
                        <td>Suku Bunga</td>
                        <td>: <span class="interestRate"> % per bulan</span></td>
                    </tr>
                    <tr>
                        <td>Nominal Angsuran</td>
                        <td>: Rp <span class="installmentAmount"></span>,-</td>
                    </tr>
                    <tr>
                        <td>Durasi Pinjaman</td>
                        <td>: <span class="tenor"></span> bulan</td>
                    </tr>
                    <tr>
                        <td>Biaya Adm & provisi</td>
                        <td>: 3% dari pokok hutang, yaitu sebesar Rp <span class="provisionFee"></span>,-</td>
                    </tr>
                    <tr>
                        <td>Nominal Pencairan</td>
                        <td>: Rp<span class="disbursementAmount"></span>,-</td>
                    </tr>
                    <tr>
                        <td>Tanggal Pencairan</td>
                        <td>: <span class="loanDate"></span></td>
                    </tr>
                    <tr>
                        <td>Jaminan</td>
                        <td>: <span class="collateral"></span></td>
                    </tr>
                </table>
                <br>
                <p style="font-weight: bold; text-decoration: underline; margin-top:10px;">Pasal 3 : Tata Cara Peminjaman dan Pembayaran Kembali</p>
                <ol>
                    <li>Atas permohonan PEMINJAM, KOPERASI setuju untuk meminjamkan sejumlah uang yang disebut dengan Pokok Pinjaman</li>
                    <li>Oleh karena itu, PEMINJAM setuju dikenai bunga atas pinjamannya tersebut. Suku Bunga pinjaman ini adalah tetap selama perjanjian kredit ini berlaku. </li>
                    <li>PEMINJAM harus melunasi pinjamannya dengan suatu Nominal Angsuran yang disebutkan di pasal 2, untuk jangka waktu tertentu sesuai Durasi Pinjaman yang tertera di pasal 2.</li>
                    <li>PEMINJAM setuju untuk membayar Uang Administrasi dan Provisi sejumlah 3% dari total Pokok Pinjamannya tersebut, dan harus dibayar di muka saat ditandatanganinya perjanjian kredit ini.</li>
                    <li>PEMINJAM setuju untuk membayar semua biaya-biaya yang timbul karena dibuatnya perjanjian kredit ini, termasuk di dalamnya biaya materai, biaya pengecekan keaslian dan pemindahtanganan Bukti Kepemilikan Kendaraan Bermotor (BPKB), bukti atas hak milik tanah, biaya asuransi, dan lain sebagainya.</li>
                    <li>PEMINJAM wajib untuk mulai mencicil persis 1 bulan, setelah PEMINJAM menerima pencairan dana dari KOPERASI. Misalnya, jika pencairan dana dilakukan pada tanggal 10 Mei 2018, maka PEMINJAM wajib melakukan pembayaran angsuran pertama pada 10 Juni 2018, angsuran kedua pada 10 Juli 2018, demikian seterusnya sampai lunas.</li>
                    <li>Jika PEMINJAM terlambat melakukan pembayaran, maka untuk 3 hari pertama tidak dikenakan denda. Untuk keterlambatan hari keempat dan seterusnya, PEMINJAM setuju dikenai denda keterlambatan sebesar 0,5% per hari dari Nominal Angsurannya bulan tersebut.</li>
                </ol>
                <p align="center" style="font-weight: bold; text-decoration: underline; margin-top:10px;">Pasal 4 : Jaminan</p>
                <ol>
                    <li>Untuk menjamin pembayaran kembali pinjaman dan kewajiban-kewajiban lainnya kepada KOPERASI, baik karena pokok pinjaman, bunga, denda, dan biaya-biaya lain yang timbul karena dibuatnya perjanjian kredit ini, maka PEMINJAM setuju untuk memberikan jaminan seperti yang tertera pada pasal 2 di atas.</li>
                    <li>KOPERASI menerima jaminan yang diberikan oleh PEMINJAM, dan akan menjaganya dengan baik sampai dengan waktu pelunasan pinjaman.</li>
                    <li>PEMINJAM tidak diperkenankan untuk menjual, menyewakan, menggadaikan, memindahtangankan, jaminan yang telah diberikan kepada KOPERASI dengan cara apapun, tanpa surat persetujuan tertulis dari KOPERASI, selama perjanjian kredit ini berlaku.</li>
                    <li>PEMINJAM tidak diperkenankan untuk mengganti jaminannya dengan jaminan yang lain, kecuali dengan surat persetujuan tertulis dari KOPERASI.</li>
                    <li>KOPERASI berkewajiban untuk mengembalikan jaminan yang diberikan oleh peminjam dalam keadaan seperti sediakala saat berakhirnya perjanjian kredit ini.</li>
                </ol>
                <br>
                <p align="center" style="font-weight: bold; text-decoration: underline; margin-top:10px;">Pasal 5 : Wanprestasi</p>
                <p>Apabila PEMINJAM untuk alasan apapun tidak dapat memenuhi kewajibannya untuk membayar angsurannya kepada KOPERASI sesuai dengan tanggal-tanggal yang sudah ditetapkan pada pasal-pasal sebelumnya, maka ini disebut wanprestasi PEMINJAM. Apabila terjadi wanprestasi, maka KOPERASI akan menjalankan protokol sebagai berikut:</p>
                <ol>
                    <li>Jika PEMINJAM sudah menunggak angsuran sebanyak dua kali, maka KOPERASI akan menerbitkan Surat Peringatan 1 (SP1).</li>
                    <li>Jika PEMINJAM sudah menunggak angsuran sebanyak tiga kali, maka KOPERASI akan menerbitkan Surat Peringatan 2 (SP2).</li>
                    <li>Jika PEMINJAM sudah menunggak angsuran sebanyak empat kali, maka KOPERASI akan menerbitkan Surat Peringatan 3 (SP3).</li>
                    <li>Jika dalam 30 hari sejak diterbitkannya SP3 PEMINJAM tidak juga melakukan pembayaran kepada KOPERASI, dan/atau tidak menunjukkan itikad baik, maka KOPERASI berhak untuk menjual jaminan yang diberikan oleh PEMINJAM kepada KOPERASI.</li>
                    <li>PEMINJAM setuju untuk membayar semua biaya-biaya lain yang nantinya akan timbul oleh karena wanprestasi PEMINJAM diantaranya biaya penagihan, penarikan barang jaminan, biaya pengacara, biaya pengurusan di pengadilan, dan lain sebagainya.</li>
                    <li>Uang yang didapat dari hasil penjualan jaminan akan dipakai KOPERASI untuk melunasi semua kewajiban-kewajiban PEMINJAM kepada KOPERASI. Bila setelah itu masih ada sisa dari uang hasil penjualan jaminan, maka sisa ini akan diberikan kepada PEMINJAM.</li>
                    <li>Setelah semua proses yang disebutkan diatas selesai, maka perjanjian kredit ini dianggap selesai.</li>
                </ol>
                <br>
                <p align="center" style="font-weight: bold; text-decoration: underline; margin-top:10px;">Pasal 6 : Penyelesaian Konflik</p>
                <p style="margin-bottom:5px;">Segala perjanjian tertulis tambahan dan bukti tertulis tambahan yang dibuat oleh KOPERASI dan PEMINJAM berkaitan dengan perjanjian kredit ini dianggap satu dan tidak terpisah. </p>
                <p style="margin-bottom:5px;">KOPERASI dan PEMINJAM berkomitmen untuk menyelesaikan setiap konflik yang mungkin timbul dari dibuatnya perjanjian kredit ini secara baik dan kekeluargaan.</p>
                <p style="margin-bottom:5px;">Namun apabila tidak tercapai titik temu karena perbedaan pendapat, maka KOPERASI dan PEMINJAM setuju untuk menyelesaikan masalah di Pengadilan Tinggi Negeri Malang, sesuai dengan hukum yang berlaku di Negara Kesatuan Republik Indonesia.</p>
                <p style="margin-bottom:5px;">PEMINJAM setuju untuk menanggung semua biaya-biaya, diantaranya biaya pengadilan, biaya pengacara, dan biaya lain-lain yang sekiranya timbul oleh karena konflik ini.</p>
                <br>
                <p align="center" style="font-weight: bold; text-decoration: underline; margin-top:10px;">Pasal 7 : Hak-Hak Koperasi</p>
                <p style="margin-bottom:5px;">KOPERASI mempunyai kuasa untuk menjalankan hak-haknya terhadap PEMINJAM berkenaan dengan perjanjian kredit ini, sesuai dengan kata-kata yang termuat dalam perjanjian kredit ini. Oleh karena itu tidak diperlukan lagi kuasa khusus tersendiri.</p>
                <p style="margin-bottom:5px;">Setiap kuasa yang telah diberikan PEMINJAM kepada KOPERASI dalam perjanjian kredit ini dan perjanjian pemberian jaminan merupakan bagian terpenting dari perjanjian ini, oleh karena itu hak-hak KOPERASI yang telah disebutkan pada perjanjian kredit ini bersifat mengikat PEMINJAM dan tidak dapat dicabut oleh apapun juga dengan alasan apapun. Oleh karenanya KOPERASI dan PEMINJAM dengan ini melepaskan ketentuan-ketentuan yang berlaku dalam pasal 1816 dan 1316 KUHP Perdata.</p>
                <p style="margin-bottom:5px;">Perjanjian kredit ini hanya akan selesai dengan diselesaikannya seluruh kewajiban-kewajiban PEMINJAM kepada KOPERASI, seperti telah diatur dengan detail pada pasal-pasal diatas.</p>
                <br>
                <p align="center" style="font-weight: bold; text-decoration: underline; margin-top:10px;">Pasal 8 : Penutup</p>
                <p style="margin-bottom:5px;">Dengan ditandatanganinya perjanjian kredit ini, KOPERASI dan PEMINJAM menyatakan telah membaca dan mencermati seluruh isi perjanjian ini dengan teliti dan seksama.</p>
                <p style="margin-bottom:5px;">Dengan ditandatanganinya perjanjian kredit ini, KOPERASI dan PEMINJAM menyatakan setuju akan seluruh isi perjanjian ini, dan akan melaksanakan semua kewajiban-kewajibannya masing-masing dengan sebaik-baiknya. KOPERASI dan PEMINJAM juga mengetahui akan hak-haknya, dan berhak menuntut haknya tersebut apabila diperlukan.</p>
                <p style="margin-bottom:5px;">Demikian perjanjian kredit ini dibuat dan ditandatangani oleh KOPERASI dan PEMINJAM, yang keduanya dalam keadaan sehat, merdeka, tidak dipengaruhi oleh pihak manapun, dan dapat dipertanggungjawabkan secara hukum.</p>
                <br>
                <br>
                <br>
                <p>Malang,____________________</p>
                <br>
                <br>
                <table style="width: 100%;">
                    <tr>
                        <td align="center">PEMINJAM</td>
                        <td>&nbsp</td>
                        <td>&nbsp</td>
                        <td>&nbsp</td>
                        <td>&nbsp</td>
                        <td>&nbsp</td>
                        <td align="center">KOPERASI</td>
                    </tr>
                    <tr>
                        <td>&nbsp</td>
                        <td>&nbsp</td>
                        <td>&nbsp</td>
                        <td>&nbsp</td>
                        <td>&nbsp</td>
                    </tr>
                    <tr>
                        <td>&nbsp</td>
                        <td>&nbsp</td>
                        <td>&nbsp</td>
                        <td>&nbsp</td>
                        <td>&nbsp</td>
                    </tr>
                    <tr>
                        <td>&nbsp</td>
                        <td>&nbsp</td>
                        <td>&nbsp</td>
                        <td>&nbsp</td>
                        <td>&nbsp</td>
                    </tr>
                    <tr>
                        <td>&nbsp</td>
                        <td>&nbsp</td>
                        <td>&nbsp</td>
                        <td>&nbsp</td>
                        <td>&nbsp</td>
                    </tr>
                    <tr>
                        <td align="center">________________</td>
                        <td>&nbsp</td>
                        <td>&nbsp</td>
                        <td>&nbsp</td>
                        <td>&nbsp</td>
                        <td>&nbsp</td>
                        <td align="center">
                            Priska Dwi Lujeng
                            <br>Manager
                            <br>Koperasi Damai Sejahtera
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
    <script>
        var customerDetail = <?=$_POST['customerDetail']?>;
        var loanDetail = <?=$_POST['loanDetail']?>;
        console.log(customerDetail['customer_id']);
        console.log(loanDetail);

        $('#customerId').html(customerDetail.customer_id);
        $('#loanYear').html(loanDetail.loan_year);
        $('.customerName').html(customerDetail.customer_name);
        $('.customerAddress').html(customerDetail.customer_address);
        $('.customerPhone').html(customerDetail.customer_phone);
        $('.customerIdNumber').html(customerDetail.customer_id_number);
        $('.customerProffesion').html(customerDetail.customer_proffesion);
        $('.loanAmount').html(parseFloat(loanDetail.loan_amount, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString());
        $('.interestRate').html(loanDetail.interest_rate);
        $('.installmentAmount').html(parseFloat(loanDetail.installment_amount, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString());
        $('.tenor').html(loanDetail.tenor);
        $('.provisionFee').html(parseFloat(loanDetail.provision_fee, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString());
        $('.disbursementAmount').html(parseFloat(loanDetail.disbursement_amount, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString());
        $('.loanDate').html(loanDetail.loan_date);
        $('.collateral').html(loanDetail.collateral_description);

        window.print();
    </script>

</html>
