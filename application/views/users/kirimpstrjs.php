<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="google" content="notranslate">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Packing Ke Pusatex</title>
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
    <h1>Kirim Pusatex</h1>
    <small class="sm"><?=$newToday;?>, <strong><?=$prinTgl;?></strong>, Username : <strong id="nmoptid">Nur Hikmah</strong></small>
    <div class="container">
        <div class="fortable has" style="font-size: 13px;">
            <table id="idtable">
                <tr><td>Loading...</td></tr>
            </table>
        </div>
        <div class="kotaknewpkg">
            <span>Buat Paket Baru</span>
            <div class="newpkg" >
                <div class="autoComplete_wrapper">
                    <input id="autoComplete" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off">
                    <input type="hidden" id="id_username" value="0">
                    <input type="hidden" id="usernnameID" value="0">
                    <input type="hidden" id="id_tgl" value="<?=$tgl;?>">
                </div>
                <button id="createPkg">Buat Paket</button>
            </div>
            
        </div>
        <div class="kotaknewpkg">
            <span id="diddatastok">Data Stok Realtime</span>
            <div class="fortable has2" style="min-width:100%;overflow-x:auto;">
                <table id="tableStok" style="min-width:100%;">
                    <tr>
                        <td colspan="2">Loading...</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="kotaknewpkg">
            <span>Tampilkan Stok Per Tanggal</span>
            <div class="newpkg" >
                <input type="date" style="width:250px;padding:7px;outline:none;border:1px solid #ccc;margin-top:5px;border-radius:3px;" id="idtgloke">
                <button id="createPkgshjow">Tampilkan</button>
            </div>
        </div>
        <div class="kotaknewpkg">
            <span>Join Packinglist</span>
            <div style="width:100%;display:flex;flex-direction:column;">
                <label for="joinpck" style="color:2e2e2e;">Masukan Kode Packinglist</label>
                <input type="text" placeholder="RJS123,RJS124,RJS125" id="joinpck" style="padding:7px;outline:none;border:1px solid #ccc;margin-top:5px;border-radius:3px;">
                <button style="margin-top:10px;outline:none;border:none;padding:7px;background:#4184f0;color:#fff;border-radius:3px;" onclick="joinpk()">Join Packinglist</button>
            </div>
            
        </div>
        <button class="btn-save" style="background:#575757;border:1px solid #FFF;" id="btn-upstok">Edit Roll</button>
        <button class="btn-save" style="background:red;border:1px solid #FFF;" id="btn-logout">Logout</button>
    </div>
    <?php
        $kons = $this->data_model->get_record('tb_konstruksi');
        $ar_kons = array();
        foreach($kons->result() as $val){
            $ar_kons[] = '"'.$val->kode_konstruksi.'"';
        }
        $im_kons = implode(',', $ar_kons);
        //jika yusuf keluar finish
        //jiki rizik keluar grey
    ?>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        let personName = sessionStorage.getItem("userName");
        document.getElementById('nmoptid').innerHTML = ''+personName;
        document.getElementById('id_username').value = ''+personName;
        document.getElementById('usernnameID').value = ''+personName;
        function loadData(){
            $.ajax({
                url:"<?=base_url();?>usersrjs/loadpaketpst",
                type: "POST",
                data: {"username" : personName},
                cache: false,
                success: function(dataResult){
                    $('#idtable').html(dataResult);
                }
            });
        }
        function joinpk(){
            var txt = document.getElementById('joinpck').value;
            if(txt!=""){
                $.ajax({
                    url:"<?=base_url();?>usersrjs/joinpkglist",
                    type: "POST",
                    data: {"username" : personName, "txt" : txt},
                    cache: false,
                    success: function(dataResult){
                        var dataResult = JSON.parse(dataResult);
                        if(dataResult.statusCode==200){
                            suksestoast('Join Ke Kode '+dataResult.psn);
                        } else {
                            peringatan(''+dataResult.psn);
                        }
                    }
                });
            } else {
                peringatan('isi kode packinglist');
            }
        }
        loadData();
        function loadDataStok(){
            $.ajax({
                url:"<?=base_url();?>usersrjs/loadDataStokGrey",
                type: "POST",
                data: {"username" : personName, "tgl" : "null"},
                cache: false,
                success: function(dataResult){
                    $('#diddatastok').html('Data Stok Realtime');
                    $('#tableStok').html(dataResult);
                }
            });
        }
        loadDataStok();
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
        $( "#btn-logout" ).on( "click", function() {
            window.location.href = "<?=base_url('usersrjs/');?>";
        });
        $( "#btn-upstok" ).on( "click", function() {
            window.location.href = "<?=base_url('usersrjs/upstok');?>";
        });
        $( "#createPkgshjow" ).on( "click", function() {
            var tgl = document.getElementById('idtgloke').value;
            var tglreal = document.getElementById('idtgloke').value;
            var tgl_obj = new Date(tgl);
            var tgl_bulan = tgl_obj.toLocaleString('default', { month: 'long' });
            var tgl_tahun = tgl_obj.getFullYear();
            tgl = tgl_bulan + " " + tgl_tahun;
            tgl = ""+tgl_obj.getDate()+ " " +tgl_bulan + " " + tgl_tahun;
            if(tglreal!=""){
                $.ajax({
                    url:"<?=base_url();?>usersrjs/loadDataStokGrey",
                    type: "POST",
                    data: {"username" : personName, "tgl" : tglreal},
                    cache: false,
                    success: function(dataResult){
                        $('#diddatastok').html('Data Stok Per '+tgl+'');
                        $('#tableStok').html(dataResult);
                    }
                });
            } else {
                peringatan('Anda belum memilih tanggal');
            }
        });
        $( "#createPkg" ).on( "click", function() {
            suksestoast('Loading...');
            var kodekons = document.getElementById('autoComplete').value;
            var username = document.getElementById('id_username').value;
            var namaopt = document.getElementById('usernnameID').value;
            var tgl = document.getElementById('id_tgl').value;
            if(kodekons!="" && username!="" && tgl!="" && namaopt!=""){
                $.ajax({
                    url:"<?=base_url();?>usersrjs/prosesCreatepkg2",
                    type: "POST",
                    data: {"kodekons" : kodekons, "tgl":tgl, "username":username, "opt":namaopt},
                        cache: false,
                        success: function(dataResult){
                            var dataResult = JSON.parse(dataResult);
                            if(dataResult.statusCode==200){
                                setTimeout(() => {
                                    window.location.href = "<?=base_url('usersrjs/createkirimpst/');?>"+dataResult.psn+"";
                                }, "500");
                            }
                        }
                });
            } else {
                peringatan('Anda perlu mengisi kode konstruksi');
            }                    
        });
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
            setTimeout(() => {
                loadDataStok();
            }, 60000);
    </script>
</body>
</html>