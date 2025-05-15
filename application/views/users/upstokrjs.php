<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="google" content="notranslate">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Packinglist Ke Pusatex</title>
    <link rel="stylesheet" href="<?=base_url('new_db/');?>style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/css/autoComplete.02.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>
        .autoComplete_wrapper input{
            width: 95%;
            transform: translateX(10px);
        }
        .middle {
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            position: absolute;
            }

            .bar {
            width: 10px;
            height: 70px;
            display: inline-block;
            transform-origin: bottom center;
            border-top-right-radius: 20px;
            border-top-left-radius: 20px;
            box-shadow: 5px 10px 20px inset rgba(52, 152, 219, 0.8);
            animation: loader 1.2s linear infinite;
            margin:0 2px;
            }

            .bar1 {
            animation-delay: 0.1s;
            }
            .bar2 {
            animation-delay: 0.2s;
            }
            .bar3 {
            animation-delay: 0.3s;
            }
            .bar4 {
            animation-delay: 0.4s;
            }
            .bar5 {
            animation-delay: 0.5s;
            }
            .bar6 {
            animation-delay: 0.6s;
            }
            .bar7 {
            animation-delay: 0.7s;
            }
            .bar8 {
            animation-delay: 0.8s;
            }

            @keyframes loader {
            0% {
                transform: scaleY(0.1);
                background: transparent;
            }
            50% {
                transform: scaleY(1);
                background: #3498db;
            }
            100% {
                transform: scaleY(0.1);
                background: transparent;
            }
            }
            .form-label input {
                width: 190px;
                padding: 10px;
                outline:none;
                border:1px solid #ccc;
                border-radius:3px;
            }
    </style>
</head>
<body>
    <?php
        $kd = $this->uri->segment(3);
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
    <h1>Edit Kode Roll</h1>
    <small class="sm"><?=$newToday;?>, <strong><?=$prinTgl;?></strong>, Username : <strong id="nmoptid">Nur Hikmah</strong></small>
    
    <div class="container">
        <div class="form-label">
            <small>Ketik dan pilih kode roll untuk mengupdate data</small>
        </div>
        <div class="form-label">
            <label for="autoComplete">Kode Roll</label>
            <div class="autoComplete_wrapper">
                <input id="autoComplete" type="search"  dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off">
            </div>
            <!-- <input type="text" class="ipt" name="kons" id="kons" autofocus> -->
            <input type="hidden" id="id_username" value="0">
            <input type="hidden" id="id_tgl" value="<?=$tgl;?>">
            <input type="hidden" id="id_kons" value="<?=$kons;?>">
            <input type="hidden" id="id_pkg" value="<?=$kd;?>">
            <button style="background:#01852f;outline:none;border:none;color:#fff;padding:10px 15px;margin-left:10px;border-radius:4px;" id="btnCari">Cari</button>
        </div>
        <div style="width:100%;min-height:500px;display:flex;flex-direction:column;" id="autoCari">
            
        </div>
        <!-- <div style="display:flex;">
        <a style="background:red;color:#FFFFFF;width:105px;display:flex;justify-content:center;align-items:center;margin-top:15px;font-size:12px;padding:5px 0;border-radius:7px;" id="delPaket">Hapus Paket</a>
        <a style="background:green;color:#FFFFFF;width:105px;display:flex;justify-content:center;align-items:center;margin-top:15px;font-size:12px;padding:5px 0;border-radius:7px;margin-left:5px;" href="<?=base_url('usersrjs/kirimpst');?>">Simpan</a>
        </div> -->
    </div>
    
    
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var kons = $('#id_kons').val();
        var pkg = $('#id_pkg').val();
        const autoCompleteJS = new autoComplete({
            placeHolder: "Ketik & Pilih Kode Roll...",
            data: {
                src: [""],
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
                        //suksestoast(''+selection);
                        $.ajax({
                            url:"<?=base_url();?>users2/inputstokrjs",
                            type: "POST",
                            data: {"selection" : selection, "kons" : kons, "pkg" : pkg},
                            cache: false,
                            success: function(dataResult){
                                var dataResult = JSON.parse(dataResult);
                                if(dataResult.statusCode==200){
                                    suksestoast('Add '+selection+'');
                                    autoCompleteJS.input.value = '';
                                    loadisi();
                                } else {
                                    peringatan(''+dataResult.psn+'');
                                }
                            }
                        });
                    }
                }
            }
        });
        function delpkg(kode){
            //peringatan('del'+kode);
            $.ajax({
                url:"<?=base_url();?>users2/delisi256",
                type: "POST",
                data: {"kode" : kode},
                cache: false,
                success: function(dataResult){
                    var dataResult = JSON.parse(dataResult);
                    if(dataResult.statusCode==200){
                        peringatan('Hapus kode '+kode+'');
                        loadisi();
                    } 
                }
            });
        }
        let personName = sessionStorage.getItem("userName");
        document.getElementById('nmoptid').innerHTML = ''+personName;
        document.getElementById('id_username').value = ''+personName;
        function peringatan(txt) {
                Toastify({
                    text: ""+txt+"",
                    duration: 4000,
                    close:true,
                    gravity:"top",
                    position: "center",
                    backgroundColor: "#cc214e",
                }).showToast();
            }
            function suksestoast(txt){
                Toastify({
                    text: ""+txt+"",
                    duration: 5000,
                    close:true,
                    gravity:"top",
                    position: "center",
                    backgroundColor: "#4fbe87",
                }).showToast();
            } 
            
            $( "#btnCari" ).on( "click", function() {
                var kode = $('#autoComplete').val();
                if(kode!=""){
                    $('#autoCari').html('<div class="middle"><div class="bar bar1"></div><div class="bar bar2"></div><div class="bar bar3"></div><div class="bar bar4"></div><div class="bar bar5"></div><div class="bar bar6"></div><div class="bar bar7"></div><div class="bar bar8"></div></div>');
                    $.ajax({
                        url:"<?=base_url();?>usersrjs/cekkodestokrjs",
                        type: "POST",
                        data: {"kode" : kode},
                        cache: false,
                        success: function(dataResult){
                            setTimeout(function () {
                                $('#autoCari').html(dataResult);
                            }, 1500);
                        }
                    });
                } else {
                    peringatan('Anda belum mengetik kode roll');
                }
            });
            function btnCarisave(){
                var mc = document.getElementById('nomesin').value;
                var beam = document.getElementById('nobeam').value;
                var oka = document.getElementById('oka').value;
                var ori = document.getElementById('ori').value;
                var bs = document.getElementById('oribs').value;
                var bp = document.getElementById('bp').value;
                var kode = document.getElementById('autoComplete').value;
                var konst = document.getElementById('konst').value;
                if(mc!="" && beam!="" && oka!="" && ori!="" && bs!="" && bp!="" && kode!=""){
                    $('#autoCari').html('<div class="middle"><div class="bar bar1"></div><div class="bar bar2"></div><div class="bar bar3"></div><div class="bar bar4"></div><div class="bar bar5"></div><div class="bar bar6"></div><div class="bar bar7"></div><div class="bar bar8"></div></div>');
                    $.ajax({
                        url:"<?=base_url();?>usersrjs/simpanupdate",
                        type: "POST",
                        data: {"kode" : kode, "mc" : mc, "beam" : beam, "oka" : oka, "ori" : ori, "bs" : bs, "bp" : bp, "konst" : konst},
                        cache: false,
                        success: function(dataResult){
                            setTimeout(function () {
                                $('#autoCari').html(dataResult);
                            }, 1500);
                        }
                    });
                } else {
                    peringatan('Anda harus mengisi semua data');
                }
            }
            function btnCaridel(kode){
                var confirmation = confirm("Yakin ingin menghapus kode Roll "+kode+" ?");
                if (confirmation) {
                    
                    $('#autoCari').html('<div class="middle"><div class="bar bar1"></div><div class="bar bar2"></div><div class="bar bar3"></div><div class="bar bar4"></div><div class="bar bar5"></div><div class="bar bar6"></div><div class="bar bar7"></div><div class="bar bar8"></div></div>');
                    $.ajax({
                        url:"<?=base_url();?>usersrjs/delkoderolls",
                        type: "POST",
                        data: {"kode" : kode},
                        cache: false,
                        success: function(dataResult){
                            setTimeout(function () {
                                $('#autoCari').html(dataResult);
                            }, 1500);
                        }
                    });
                } else {
                    suksestoast('Tidak jadi menghapus kode roll');
                }
            }
    </script>
</body>
</html>