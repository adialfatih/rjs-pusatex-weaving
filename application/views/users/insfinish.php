<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="google" content="notranslate">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Inspect Finish</title>
    <link rel="stylesheet" href="<?=base_url('new_db/');?>style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/css/autoComplete.02.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>
        .autoComplete_wrapper input{
            width: 95%;
            transform: translateX(10px);
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
    <h1>Produksi Inspect Finish</h1>
    <small class="sm"><?=$newToday;?>, <strong><?=$prinTgl;?></strong> Operator : <strong id="nmoptid">Nur Hikmah</strong></small>
    <div class="container">
        <div class="form-label">
            <label for="autoComplete">Kode Roll</label>
            <div class="autoComplete_wrapper">
                <input id="autoComplete" onchange="teschange(this.value)" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off">
            </div>
            <!-- <input type="text" class="ipt" name="kons" id="kons" autofocus> -->
            <input type="hidden" id="id_username" value="0">
            <input type="hidden" id="id_tgl" value="<?=$tgl;?>">
        </div>
        <div class="inforsblm" id="resultKode">
            Klik untuk melihat.
        </div>
        <input type="hidden" id="ukrSebelum" value="0">
        <div class="form-label" style="margin-top:5px;">
            <label for="ukuran">Ukuran ORI</label>
        </div>
        <div class="form-ukuran">
            <input type="tel" id="ukuran" name="ukuran[]" placeholder="Ukuran ORI (Mtr)">
            <input type="tel" id="ukuranyy" name="ukuranyy[]" placeholder="" readonly>
            <small class="geser-yard">Yard</small>
            <span class="circle-green" id="addbtn">+</span>
        </div>
        <?php for ($i=2; $i <11 ; $i++) { ?>
            <div class="form-ukuran" id="divukuran<?=$i;?>" style="display:none;">
                <input type="tel" id="ukuran<?=$i;?>" name="ukuran[]" placeholder="Ukuran ORI (Mtr)">
                <input type="tel" id="ukuranyy<?=$i;?>" name="ukuranyy[]" placeholder="" readonly>
                <small class="geser-yard">Yard</small>
                <span class="circle-green" id="addbtn<?=$i;?>">+</span>
            </div>
        <?php } ?>
        

        <div class="box-bs-bp2">
            <div class="min30">
                <label for="bs"><strong>BS</strong></label>
                <input type="tel" name="bs" id="bs" value="0">
            </div>
            <div class="min30">
                <label for="bs"><strong>BP</strong></label>
                <input type="tel" name="bp" id="bp" value="0">
            </div>
            <div class="min30">
                <label for="crt"><strong>CRT</strong></label>
                <input type="tel" name="crt" id="crt" value="0">
            </div>
        </div>
        
        <button class="btn-save" onclick="simpanData()">Simpan</button>
        <div class="fortable">
            <table id="fortable" style="font-size:12px;">
            </table>
        </div>
        <a href="" id="btnSelesai" class="btn-save2">Selesai</a>
    </div>
    <?php
        $ig = $this->db->query("SELECT kode_roll,loc_now FROM data_ig WHERE loc_now='Samatex' AND kode_roll NOT IN (SELECT kode_roll FROM data_if)");
        $ar_ig = array();
        foreach($ig->result() as $val){
            $ar_ig[] = '"'.$val->kode_roll.'"';
        }
        $im_kons = implode(',', $ar_ig);
        
    ?>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        function teschange(kode){
            $.ajax({
                url:"<?=base_url();?>users/cariInsgrey",
                type: "POST",
                data: {"kode" : kode},
                cache: false,
                success: function(dataResult){
                    var dataResult = JSON.parse(dataResult);
                    if(dataResult.statusCode==200){
                        $('#resultKode').html(""+dataResult.psn+"");
                        $('#ukrSebelum').val(''+dataResult.ukrsblm);
                    } else {
                        peringatan(''+dataResult.psn+'');
                        $('#resultKode').html("<span style='color:red;'>Kode tidak ditemukan</span>");
                    }
                }
            });
        }
            function roundToTwo(num) {
                return +(Math.round(num + "e+2")  + "e-2");
            }
            $( "#ukuran" ).on( "keyup", function() {
                    var nilai = parseInt($('#ukuran').val());
                    var nYard = nilai / 0.9144;
                    var nYard2 = roundToTwo(nYard);
                    if(!isNaN(nYard)){
                        $('#ukuranyy').val(''+nYard2);  
                    } else {
                        $('#ukuranyy').val('');  
                    }      
            } );
            $( "#addbtn" ).on( "click", function() {
                    $( "#addbtn" ).hide();
                    $( "#divukuran2" ).show();
                    
            } );
            for (let io = 2; io < 11; io++) {
                $( "#addbtn"+io+"" ).on( "click", function() {
                    $( "#addbtn"+io+"" ).hide();
                    var newnum = io+1;
                    $( "#divukuran"+newnum+"" ).show();
                } );
                $( "#ukuran"+io+"" ).on( "keyup", function() {
                    var nilai = parseInt($('#ukuran'+io+'').val());
                    var nYard = nilai / 0.9144;
                    var nYard2 = roundToTwo(nYard);
                    if(!isNaN(nYard)){
                        $('#ukuranyy'+io+'').val(''+nYard2);  
                    } else {
                        $('#ukuranyy'+io+'').val('');  
                    }      
            } );
            }

        const autoCompleteJS = new autoComplete({
            placeHolder: "Ketik & Pilih Kode Roll...",
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
        document.getElementById('btnSelesai').href = "<?=base_url();?>users/laporaninsfinish/"+personName+"";
        function loadData(){
            $.ajax({
                url:"<?=base_url();?>users/loaddata",
                type: "POST",
                data: {"username" : personName, "proses" : "insfinish"},
                cache: false,
                success: function(dataResult){
                    $('#fortable').html(dataResult);
                }
            });
        }
        loadData();
        function kuyhas(iddata){
            var result = window.confirm("Apakah Anda yakin ingin menghapus?");
            if (result) {
                $.ajax({
                    url:"<?=base_url();?>users/delinsfinish",
                    type: "POST",
                    data: {"iddata" : iddata},
                    cache: false,
                    success: function(dataResult){
                        var dataResult = JSON.parse(dataResult);
                        if(dataResult.statusCode==200){
                            peringatan('Hapus kode '+dataResult.psn+'');
                            loadData();
                        } 
                    }
                });
            } else { console.log('Erorr'); }
        }
        function simpanData(){
            var koderoll = document.getElementById('autoComplete').value;
            var ori = document.querySelectorAll('input[name="ukuran[]"]');
            const ukuranArray = [];
            ori.forEach(ori => {
                ukuranArray.push(ori.value);
            });
            var bs = document.getElementById('bs').value;
            var bp = document.getElementById('bp').value;
            var crt = document.getElementById('crt').value;
            var tgl = document.getElementById('id_tgl').value;
            var ukrSebelum = document.getElementById('ukrSebelum').value;
            var username = document.getElementById('id_username').value;
            if(koderoll!="" && ori!="" && bs!="" && bp!="" && username!="null"){
                $.ajax({
                    url:"<?=base_url();?>users/prosesInsFinish",
                    type: "POST",
                    data: {"koderoll" : koderoll, "ori" : ukuranArray, "bs":bs, "bp":bp, "crt":crt, "tgl":tgl, "username":username, "ukrSebelum":ukrSebelum},
                        cache: false,
                        success: function(dataResult){
                            var dataResult = JSON.parse(dataResult);
                            if(dataResult.statusCode==200){
                                suksestoast(''+dataResult.psn+'');
                                loadData();
                                document.getElementById('autoComplete').value = '';
                                $('#resultKode').html('Klik untuk melihat');
                                document.getElementById('ukuran').value = '';
                                $('#addbtn').show();
                                for (let po = 2; po < 11; po++) {
                                    document.getElementById('ukuran'+po+'').value = '';
                                    $('#divukuran'+po+'').hide();
                                    $('#addbtn'+po+'').show();
                                }
                                
                                document.getElementById('bs').value = '0';
                                document.getElementById('bp').value = '0';
                                document.getElementById('crt').value = '0';
                            } else {
                                peringatan(''+dataResult.psn+'');
                            }
                        }
                });
            } else {
                peringatan('Anda perlu mengisi semua data');
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
                if(crt==""){
                    document.getElementById('crt').classList.add("warning");
                } else {
                    document.getElementById('crt').classList.remove("warning");
                }
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
    </script>
</body>
</html>