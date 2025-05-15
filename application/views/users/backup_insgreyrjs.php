<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="google" content="notranslate">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Inspect Grey</title>
    <link rel="stylesheet" href="<?=base_url('new_db/');?>style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/css/autoComplete.02.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>
        .autoComplete_wrapper input{
            width: 95%;
            transform: translateX(10px);
        }
        .boxpot {
            width:100%;
            border:1px solid #ccc;
            margin-bottom:10px;
            padding:5px;
            border-radius:5px;
            display:flex;
            justify-content:space-between;
        }
        .formpot {
            width:70%;
            display:flex;
            flex-direction:column;
        }
        .formpot label {font-weight:bold;}
        .formpot input {
            width:100%;
            padding:7px;
            border:1px solid #ccc;
            outline:none;
            margin-top:5px;
            border-radius:3px;
        }
        .loadpot {
            width:30%;
            display:flex;
            justify-content:center;
            align-items:center;
        }
        /* HTML: <div class="loadersfr"></div> */
        .loadersfr {
        width: 30px;
        padding: 8px;
        aspect-ratio: 1;
        border-radius: 50%;
        background: #25b09b;
        --_m: 
            conic-gradient(#0000 10%,#000),
            linear-gradient(#000 0 0) content-box;
        -webkit-mask: var(--_m);
                mask: var(--_m);
        -webkit-mask-composite: source-out;
                mask-composite: subtract;
        animation: l3 1s infinite linear;
        }
        @keyframes l3 {to{transform: rotate(1turn)}}
        .submitid {
            background:#3474eb;
            color:#fff;
            padding:10px 25px;
            border-radius:4px;
        }
        .fResult {
            width:100%;
            font-size:13px;
            border:2px solid #3470d9;
            padding:15px 10px;
            margin-bottom:10px;
            border-radius:5px;
            position: relative;
            display:none;
        }
        .tytle {
            font-size:16px;
            font-weight:bold;
            background:#fff;
            position: absolute;
            top:-10px;
            padding:0 8px;
        }
    </style>
</head>
<body>
    <?php
        $hariIni = new DateTime();
        $sf = $hariIni->format('l F Y, H:i');
        $ex = explode(' ', $sf);
        $nToday = $ex[0];
        //echo $nToday;
        $hariIndo = ["Sunday"=>"Minggu", "Monday"=>"Senin", "Tuesday"=>"Selasa", "Wednesday"=>"Rabu", "Thursday"=>"Kamis", "Friday"=>"Jumat", "Saturday"=>"Sabtu"];
        $newToday = $hariIndo[$nToday];
        //echo $newToday;
        $tgl = date('Y-m-d');
        $ex_tgl = explode('-', $tgl);
        //echo $tgl;
        $ar = array(
            '00' => 'NaN', '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        );
        $prinTgl = $ex_tgl[2]." ".$ar[$ex_tgl[1]]." ".$ex_tgl[0];
    ?>
    <h1>Produksi Inspect Grey</h1>
    <small class="sm"><?=$newToday;?>, <strong><?=$prinTgl;?></strong> Operator : <strong id="nmoptid">Nur Hikmah</strong></small>
    <div class="container">
        <div class="boxpot">
            <div class="formpot">
                <label for="idpotid">Kode Potongan</label>
                <input type="text" placeholder="Masukan Kode Potongan Kain" id="idpotid" onkeyup="tespo(this.value)" onkeypress="tespo(this.value)">
                
            </div>
            
            <div class="loadpot" id="loadpot">
                <!-- <div class="loadersfr"></div> -->
                <div class="submitid" onclick="cekpotongan()">Cari</div>
            </div>
        </div>
        <div id="notesid" style="background:#ccc;font-size:12px;padding:5px;margin:0px 0px 10px 0px;border-radius:2px;"><strong style="color:red;">Note : </strong>Jika tidak ada kode potongan mohon di isi dengan angka nol</div>
        <div id="testhasil"class="fResult">
            <span class="tytle">Informasi Potongan</span>
            <div>Tanggal Potong : <strong>23 Feb 2024</strong></div>
            <div>Ukuran Potong : <strong>400 Meter</strong></div>
            <div>Operator : <strong>Dani</strong></div>
        </div>
        <div class="form-label">
            <label for="autoComplete">Konstruksi</label>
            <div class="autoComplete_wrapper">
                <input id="autoComplete" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off">
            </div>
            <!-- <input type="text" class="ipt" name="kons" id="kons" autofocus> -->
            <input type="hidden" id="id_username" value="0">
            <input type="hidden" id="id_tgl" value="<?=$tgl;?>">
        </div>
        <div class="form-label">
            <label for="mc">Nomor Mesin</label>
            <input type="text" class="ipt" name="mc" id="mc" placeholder="Masukan nomor mesin">
        </div>
        <div class="form-label">
            <label for="beam">Nomor Beam</label>
            <input type="text" class="ipt" name="beam" id="beam" placeholder="Masukan nomor beam">
        </div>
        <div class="form-label">
            <label for="oka">OKA</label>
            <input type="text" class="ipt" name="oka" id="idoka" placeholder="Input oka">
        </div>
        <div class="form-label">
            <label for="ukuran">Ukuran</label>
        </div>
        <div class="form-ukuran">
            <input type="tel" id="ukuran" name="ukuran" placeholder="Masukan Ukuran">
            <small>Meter</small>
        </div>
        <div class="box-bs-bp">
            <div>
                <label for="bs"><strong>Ukuran BS</strong></label>
                <input type="tel" name="bs" id="bs" value="0">
            </div>
            <div>
                <label for="bs"><strong>Ukuran BP</strong></label>
                <input type="tel" name="bs" id="bp" value="0">
            </div>
        </div>
        <button class="btn-save" onclick="simpanData()">Simpan</button>
        <div class="fortable">
            <table id="fortable">
            </table>
        </div>
        <input type="hidden" id="fromData" value="0">
        <a href="" id="btnSelesai" class="btn-save2">Selesai</a>
    </div>
    <?php
        $kons = $this->data_model->get_record('tb_konstruksi');
        $ar_kons = array();
        foreach($kons->result() as $val){
            //echo $val->kode_konstruksi."<br>";
            if($val->kode_konstruksi!="SM05L"){
            $ar_kons[] = '"'.$val->kode_konstruksi.'"';
            }
            
        }
        
        $im_kons = implode(',', $ar_kons);
        
    ?>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        const autoCompleteJS = new autoComplete({
            placeHolder: "Ketik Konstruksi...",
            data: {
                src: [<?=$im_kons;?>],
                cache: true,
            },
            resultItem: {
                highlight: true
            },
            events: {
                input: {
                    selection: (event) => {
                        const selection = event.detail.selection.value;
                        autoCompleteJS.input.value = selection;
                    }
                }
            }
        });
        let personName = sessionStorage.getItem("userName");
        document.getElementById('nmoptid').innerHTML = ''+personName;
        document.getElementById('id_username').value = ''+personName;
        document.getElementById('btnSelesai').href = "<?=base_url();?>usersrjs/laporaninsgrey/"+personName+"";
        function loadData(){
            $.ajax({
                url:"<?=base_url();?>usersrjs/loaddata",
                type: "POST",
                data: {"username" : personName, "proses" : "insgrey"},
                cache: false,
                success: function(dataResult){
                    $('#fortable').html(dataResult);
                }
            });
        }
        function owek(iddata){
            var result = window.confirm("Apakah Anda yakin ingin menghapus?");
            if (result) {
                $.ajax({
                    url:"<?=base_url();?>users/delinsgrey",
                    type: "POST",
                    data: {"iddata" : iddata},
                    cache: false,
                    success: function(dataResult){
                        var dataResult = JSON.parse(dataResult);
                        if(dataResult.statusCode==200){
                            loadData();
                        } else {
                            peringatan('Erorr');
                        }
                    }
                });
            } else {
                console.log("Tindakan dibatalkan.");
            }
            
        }
        loadData();
        function simpanData(){
            var kons = document.getElementById('autoComplete').value;
            var idpotid = document.getElementById('idpotid').value;
            var mc = document.getElementById('mc').value;
            var beam = document.getElementById('beam').value;
            var okasd = document.getElementById('idoka').value;
            var ori = document.getElementById('ukuran').value;
            var bs = document.getElementById('bs').value;
            var bp = document.getElementById('bp').value;
            var tgl = document.getElementById('id_tgl').value;
            var username = document.getElementById('id_username').value;
            var fromData = document.getElementById('fromData').value;
            if(parseInt(idpotid) >= 0){
                
            if(kons!="" && mc!="" && beam!="" && okasd!="" && ori!="" && bs!="" && bp!="" && username!="null"){
                $.ajax({
                    url:"<?=base_url();?>usersrjs/prosesInsGrey",
                    type: "POST",
                    data: {"kons" : kons, "mc" : mc, "beam" : beam, "oka" : okasd, "ori" : ori, "bs":bs, "bp":bp, "tgl":tgl, "username":username, "fromData":fromData, "idpotid":idpotid},
                        cache: false,
                        success: function(dataResult){
                            var dataResult = JSON.parse(dataResult);
                            if(dataResult.statusCode==200){
                                suksestoast(''+dataResult.psn+'');
                                loadData();
                                document.getElementById('ukuran').value = '';
                                document.getElementById('bs').value = '0';
                                document.getElementById('bp').value = '0';
                            } else {
                                peringatan(''+dataResult.psn+'');
                            }
                        }
                });
            } else {
                peringatan('Anda perlu mengisi semua data');
                if(mc==""){
                    document.getElementById('mc').classList.add("warning");
                } else {
                    document.getElementById('mc').classList.remove("warning");
                }
                if(beam==""){
                    document.getElementById('beam').classList.add("warning");
                } else {
                    document.getElementById('beam').classList.remove("warning");
                }
                if(okasd==""){
                    document.getElementById('idoka').classList.add("warning");
                } else {
                    document.getElementById('idoka').classList.remove("warning");
                }
                if(ori==""){
                    document.getElementById('ukuran').classList.add("warning");
                } else {
                    document.getElementById('ukuran').classList.remove("warning");
                }
                if(bs==""){
                    document.getElementById('bs').classList.add("warning");
                } else {
                    document.getElementById('bs').classList.remove("warning");
                }
                if(bp==""){
                    document.getElementById('bp').classList.add("warning");
                } else {
                    document.getElementById('bp').classList.remove("warning");
                }
            }
            
            } else {
                peringatan('Isi Potongan dengan angka');
            }
        } // end proses simpan
            function peringatan(txt) {
                Toastify({
                    text: ""+txt+"",
                    duration: 4000,
                    close:true,
                    gravity:"top",
                    position: "right",
                    backgroundColor: "#cc214e",
                }).showToast();
            }
            function suksestoast(txt){
                Toastify({
                    text: ""+txt+"",
                    duration: 5000,
                    close:true,
                    gravity:"top",
                    position: "right",
                    backgroundColor: "#4fbe87",
                }).showToast();
            } 
            
            function cekpotongan(){
                $('#loadpot').html('<div class="loadersfr"></div>');
                $('#testhasil').html('');
                $('#testhasil').hide();
                $('#fromData').val('0');
                var id = $('#idpotid').val();
                if(id == ""){
                    peringatan('Anda tidak memasukan Kode Potongan');
                    $('#idpotid').focus();
                    $('#autoComplete').val('');
                    $('#mc').val('');
                    $('#beam').val('');
                    $('#idoka').val('');
                    setTimeout(function(){
                        $('#loadpot').html('<div class="submitid" onclick="cekpotongan()">Cari</div>');
                    }, 1000);
                    $('#testhasil').html('');
                    $('#testhasil').hide();
                    $('#fromData').val('0');
                } else {
                    $.ajax({
                        url: 'https://ajl.rindangjati.com/api/rindang',
                        type: 'GET',
                        dataType: 'json',
                        data: {"id" : id},
                        success: function(response) {
                            var nobeam = response.data[0].no_beam; 
                            var konstruksi = response.data[0].konstruksi;
                            var nomesin = response.data[0].no_mesin;
                            var oka = response.data[0].oka;
                            var beamfrom = response.data[0].beam_from;
                            var mtr = response.data[0].ukuran_meter;
                            var op = response.data[0].operator;
                            var wk = response.data[0].waktu_potong;
                            var formatwk = formatDate(wk);
                            if(beamfrom == "bsm"){
                                var lowerCaseString = nobeam.toLowerCase();
                                if (lowerCaseString.includes("bsm") ) {
                                    
                                } else {
                                    var nobeam = "bsm "+nobeam+"";
                                }
                            }
                            //console.log(response.data[0].no_beam);
                            $('#autoComplete').val(''+konstruksi);
                            $('#mc').val(''+nomesin);
                            $('#beam').val(''+nobeam);
                            $('#idoka').val(''+oka);
                            setTimeout(function(){
                                $('#loadpot').html('<div class="submitid" onclick="cekpotongan()">Cari</div>');
                            }, 1000);
                            $('#testhasil').html('<span class="tytle">Informasi Potongan</span><div>Tanggal Potong : <strong>'+formatwk+'</strong></div><div>Ukuran Potong : <strong>'+mtr+'</strong></div><div>Operator : <strong>'+op+'</strong></div>');
                            $('#testhasil').show();
                            $('#fromData').val('1');
                        },
                        error: function(xhr, status, error) {
                            peringatan('Kode Potongan tidak ditemukan');
                            $('#autoComplete').val('');
                            $('#mc').val('');
                            $('#beam').val('');
                            $('#idoka').val('');
                            setTimeout(function(){
                                $('#loadpot').html('<div class="submitid" onclick="cekpotongan()">Cari</div>');
                            }, 1000);
                            $('#testhasil').html('');
                            $('#testhasil').hide();
                            $('#fromData').val('0');
                        }
                    });
                }
            }
        function tespo(a){
            if (a == ""){
                $('#notesid').show();
            } else {
                var ins = parseInt(a);
                if(ins > -1){
                    $('#notesid').hide();
                } else {
                    $('#notesid').show();
                }
                
            }

        }
        function formatDate(inputDate) {
            // Parsing inputDate sebagai objek Date
            var date = new Date(inputDate);
            
            // Array untuk nama-nama bulan
            var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            
            // Mendapatkan tanggal, bulan, dan tahun dari objek Date
            var day = date.getDate();
            var monthIndex = date.getMonth();
            var year = date.getFullYear();
            var hours = date.getHours();
            var minutes = date.getMinutes();
            
            // Format tanggal sesuai dengan format yang diinginkan
            var formattedDate = day + "-" + months[monthIndex] + "-" + year + " " + ("0" + hours).slice(-2) + ":" + ("0" + minutes).slice(-2);
            
            return formattedDate;
        }

        // var string = "2024-02-26 09:10:02";
        // var formattedString = formatDate(string);
        // console.log(formattedString); // Output: "26-Feb-2024 09:10"

    </script>
</body>
</html>