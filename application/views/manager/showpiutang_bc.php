<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Piutang Customer</title>
    <link rel="stylesheet" href="<?=base_url('new_db/');?>style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+Vithkuqi&family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/css/autoComplete.02.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>
        
        .dobel.fullrjs { width:100%; background:#035797; color: #fff;}
        .dobelm {
            width:50%;
            display: flex;
            flex-direction:column;
            padding:7px 10px;
            font-size:12px;
        }
        .box-search {
            width:100%;
            display:flex;
            align-items:center;
            justify-content:space-between;
            margin-top:20px;
        }
        .box-search form{
            width:80%;
            background:#fff;
            display:flex;
            align-items:center;
            justify-content:space-between;
            border-radius:5px;
            box-shadow:1px 2px 5px #ccc, -1px -2px 5px #ccc;
        }
        .autoComplete_wrapper{
            width: calc(100% - 35px);
        }
        .autoComplete_wrapper input{
            width: 100%;
            border:none;
        }
        .srcbtn {
            width:32px;
            height:32px;
            background:#fff;
            outline:none;
            border:none;
            display:flex;
            justify-content:center;
            align-items:center;
        }
        .srcbtn img {
            width:20px;
        }
        .sortbtn {
            width:55px
        }
        .sortbtn img {width:100%;}
        .bunderan {
            width: 50px;
            height: 50px;
            background:#fff;
            border-radius:50%;
            position: absolute;
            bottom:20px;
            right:20px;
            position: fixed;
            display:flex;
            justify-content:center;
            align-items:center;
            box-shadow:1px 2px 5px #f2f2f2, -1px -2px 5px #f2f2f2;
        }
        .bunderan img {
            width:50%;
        }
    </style>
</head>
<body>
    <?php
    $namalogin = $this->session->userdata('nama');
    $namaHari = array(
        'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
    );
    $tanggalHariini = date('d');
    $bulanHariini = date('m');
    $tahunHariini = date('Y');
    $dateNow = date('Y-m-d');
    $hariIni = date('w');
    $tanggalSebelumnya = date('Y-m-d', strtotime('-1 day'));
    $hariSebelumnya = date('w', strtotime('-1 day'));
    // echo $tanggalHariini."<br>";
    // echo $namaHari[$hariIni]."<br>";
    // echo $namaHari[$hariSebelumnya]."<br>";
    // echo $tanggalSebelumnya."<br>";
    $arbln = array(
        '00' => 'NaN', '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    );
    $ar = array(
        '00' => 'NaN', '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des'
    );
    $ex = explode('-', $tanggalSebelumnya);
    $printHarIni = $namaHari[$hariIni].", ".$tanggalHariini." ".$ar[$bulanHariini]." ".$tahunHariini;
    $printHariKemarin = $namaHari[$hariSebelumnya].", ".$ex[2]." ".$ar[$ex[1]]." ".$ex[0];
    //cek penjualan
    $dt2 = $this->db->query("SELECT * FROM tes_piutang2 WHERE saldop > 0 AND nmcus NOT LIKE 'KM%' AND nmcus NOT LIKE 'PS%' AND nmcus NOT LIKE 'PB%' AND nmcus NOT LIKE 'PH%' AND nmcus!='rahmawati' ORDER BY saldop DESC");
    if(empty($this->input->post('namacus'))){
        $dt2 = $this->db->query("SELECT * FROM tes_piutang2 WHERE saldop > 0 AND nmcus NOT LIKE 'KM%' AND nmcus NOT LIKE 'PS%' AND nmcus NOT LIKE 'PB%' AND nmcus NOT LIKE 'PH%' AND nmcus!='rahmawati' ORDER BY saldop DESC");
    } else {
        $cus = $this->input->post('namacus');
        $dt2 = $this->db->query("SELECT * FROM tes_piutang2 WHERE nmcus = '".$cus."' AND nmcus!='rahmawati' ORDER BY saldop DESC");
    }
    ?>
    <div class="konten-mobile">
        <h1>Piutang Customer </h1>
        <div class="box-search">
            <form action="" method="post">
                <div class="autoComplete_wrapper">
                    <input id="autoComplete" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off" name="namacus">
                </div>
                <button type="submit" class="srcbtn">
                    <img src="<?=base_url('assets/');?>assetsimg/19.png" alt="Search">
                </button>
            </form>
            <div class="sortbtn" id="sort">
                <img src="<?=base_url('assets/25.png');?>" id="myImage">
            </div>
            <input type="hidden" value="0" id="sorttxt">
        </div>
        <div class="mainutama" style="width:100%;" id="mainutama">
        <?php //if($namalogin=="Pak Hamid" OR $namalogin=="Adi Subuhadir"){ 
            if($dt2->num_rows() < 1){
?>
                <div class="card-awal blue">
                    <div class="items" style="color:#2c2e2d"><?=$cus;?> </div>
                    <div class="items-nilai" id="idpiutang" style="text-align:left;padding-left:70px;">
                        Tidak ditemukan
                    </div>
                    
                </div>
<?php
            } else {
            foreach($dt2->result() as $val):
                $tesData = "all";
                $idcus = $val->id_konsumen;
                if($idcus == 100){
                    $tesData = "km";
                    //km
                    $allIdKm = $this->db->query("SELECT * FROM dt_konsumen WHERE nama_konsumen LIKE 'KM%'");
                    $allidkmoke = "";
                    foreach ($allIdKm->result() as $key => $value) {
                        if($key!="0") { $allidkmoke .= ",";}
                        $allidkmoke  .= "".$value->id_konsumen."";
                    }
                    $sj = $this->db->query("SELECT * FROM surat_jalan WHERE id_customer IN (".$allidkmoke.") AND create_nota='y'  ORDER BY id_sj DESC LIMIT 1")->row("no_sj");
                    $tgl_kirim = $this->db->query("SELECT * FROM surat_jalan WHERE id_customer IN (".$allidkmoke.") AND create_nota='y' ORDER BY id_sj DESC LIMIT 1")->row("tgl_kirim");
                    $jmlnota = $this->db->query("SELECT SUM(total_harga) AS ttl FROM a_nota WHERE no_sj='$sj'")->row("ttl");
                    if(fmod($jmlnota, 1) !== 0.00){
                        $jmlnota2 = number_format($jmlnota,2,',','.');
                    } else {
                        $jmlnota2 = number_format($jmlnota,0,',','.');
                    }
                    $ex = explode('-',$tgl_kirim);
                    $printTglnota = $ex[2]."-".$ar[$ex[1]]."-".$ex[0];
                    //cek pembayaran terakhir
                    $tgl_byr = $this->db->query("SELECT * FROM a_nota_bayar2 WHERE id_cus='$idcus' ORDER BY tgl_pemb DESC LIMIT 1")->row("tgl_pemb");
                    $er = explode('-', $tgl_byr);
                    $printTglByr = $er[2]."-".$ar[$er[1]]."-".$er[0];
                    $nominal_byr = $this->db->query("SELECT SUM(nominal_pemb) AS ttls FROM a_nota_bayar2 WHERE id_cus='$idcus' AND tgl_pemb='$tgl_byr'")->row("ttls");
                    if(fmod($nominal_byr, 1) !== 0.00){
                        $nominal_byr2 = number_format($nominal_byr,2,',','.');
                    } else {
                        $nominal_byr2 = number_format($nominal_byr,0,',','.');
                    }
                } else if($idcus == 29){
                    $tesData = "ps";
                    $allIdKm = $this->db->query("SELECT * FROM dt_konsumen WHERE nama_konsumen LIKE 'PS%'");
                    $allidkmoke = "";
                    foreach ($allIdKm->result() as $key => $value) {
                        if($key!="0") { $allidkmoke .= ",";}
                        $allidkmoke  .= "".$value->id_konsumen."";
                    }
                    $sj = $this->db->query("SELECT * FROM surat_jalan WHERE id_customer IN (".$allidkmoke.") AND create_nota='y'  ORDER BY id_sj DESC LIMIT 1")->row("no_sj");
                    $tgl_kirim = $this->db->query("SELECT * FROM surat_jalan WHERE id_customer IN (".$allidkmoke.") AND create_nota='y' ORDER BY id_sj DESC LIMIT 1")->row("tgl_kirim");
                    $jmlnota = $this->db->query("SELECT SUM(total_harga) AS ttl FROM a_nota WHERE no_sj='$sj'")->row("ttl");
                    if(fmod($jmlnota, 1) !== 0.00){
                        $jmlnota2 = number_format($jmlnota,2,',','.');
                    } else {
                        $jmlnota2 = number_format($jmlnota,0,',','.');
                    }
                    $ex = explode('-',$tgl_kirim);
                    $printTglnota = $ex[2]."-".$ar[$ex[1]]."-".$ex[0];
                    //cek pembayaran terakhir
                    $tgl_byr = $this->db->query("SELECT * FROM a_nota_bayar2 WHERE id_cus='$idcus' ORDER BY tgl_pemb DESC LIMIT 1")->row("tgl_pemb");
                    $er = explode('-', $tgl_byr);
                    $printTglByr = $er[2]."-".$ar[$er[1]]."-".$er[0];
                    $nominal_byr = $this->db->query("SELECT SUM(nominal_pemb) AS ttls FROM a_nota_bayar2 WHERE id_cus='$idcus' AND tgl_pemb='$tgl_byr'")->row("ttls");
                    if(fmod($nominal_byr, 1) !== 0.00){
                        $nominal_byr2 = number_format($nominal_byr,2,',','.');
                    } else {
                        $nominal_byr2 = number_format($nominal_byr,0,',','.');
                    }
                } else if($idcus == 101){
                    $tesData = "pb";
                    $allIdKm = $this->db->query("SELECT * FROM dt_konsumen WHERE nama_konsumen LIKE 'PB%'");
                    $allidkmoke = "";
                    foreach ($allIdKm->result() as $key => $value) {
                        if($key!="0") { $allidkmoke .= ",";}
                        $allidkmoke  .= "".$value->id_konsumen."";
                    }
                    $sj = $this->db->query("SELECT * FROM surat_jalan WHERE id_customer IN (".$allidkmoke.") AND create_nota='y'  ORDER BY id_sj DESC LIMIT 1")->row("no_sj");
                    $tgl_kirim = $this->db->query("SELECT * FROM surat_jalan WHERE id_customer IN (".$allidkmoke.") AND create_nota='y' ORDER BY id_sj DESC LIMIT 1")->row("tgl_kirim");
                    $jmlnota = $this->db->query("SELECT SUM(total_harga) AS ttl FROM a_nota WHERE no_sj='$sj'")->row("ttl");
                    if(fmod($jmlnota, 1) !== 0.00){
                        $jmlnota2 = number_format($jmlnota,2,',','.');
                    } else {
                        $jmlnota2 = number_format($jmlnota,0,',','.');
                    }
                    $ex = explode('-',$tgl_kirim);
                    $printTglnota = $ex[2]."-".$ar[$ex[1]]."-".$ex[0];
                    //cek pembayaran terakhir
                    $tgl_byr = $this->db->query("SELECT * FROM a_nota_bayar2 WHERE id_cus='$idcus' ORDER BY tgl_pemb DESC LIMIT 1")->row("tgl_pemb");
                    $er = explode('-', $tgl_byr);
                    $printTglByr = $er[2]."-".$ar[$er[1]]."-".$er[0];
                    $nominal_byr = $this->db->query("SELECT SUM(nominal_pemb) AS ttls FROM a_nota_bayar2 WHERE id_cus='$idcus' AND tgl_pemb='$tgl_byr'")->row("ttls");
                    if(fmod($nominal_byr, 1) !== 0.00){
                        $nominal_byr2 = number_format($nominal_byr,2,',','.');
                    } else {
                        $nominal_byr2 = number_format($nominal_byr,0,',','.');
                    }
                } else if($idcus == 235){
                    $tesData = "ph";
                    $allIdKm = $this->db->query("SELECT * FROM dt_konsumen WHERE nama_konsumen LIKE 'PH%'");
                    $allidkmoke = "";
                    foreach ($allIdKm->result() as $key => $value) {
                        if($key!="0") { $allidkmoke .= ",";}
                        $allidkmoke  .= "".$value->id_konsumen."";
                    }
                    $allidkmoke .= ",235";
                    $sj = $this->db->query("SELECT * FROM surat_jalan WHERE id_customer IN (".$allidkmoke.") AND create_nota='y' ORDER BY id_sj DESC LIMIT 1")->row("no_sj");
                    $tgl_kirim = $this->db->query("SELECT * FROM surat_jalan WHERE id_customer IN (".$allidkmoke.") AND create_nota='y' ORDER BY id_sj DESC LIMIT 1")->row("tgl_kirim");
                    $jmlnota = $this->db->query("SELECT SUM(total_harga) AS ttl FROM a_nota WHERE no_sj='$sj'")->row("ttl");
                    if(fmod($jmlnota, 1) !== 0.00){
                        $jmlnota2 = number_format($jmlnota,2,',','.');
                    } else {
                        $jmlnota2 = number_format($jmlnota,0,',','.');
                    }
                    $ex = explode('-',$tgl_kirim);
                    $printTglnota = $ex[2]."-".$ar[$ex[1]]."-".$ex[0];
                    //cek pembayaran terakhir
                    $tgl_byr = $this->db->query("SELECT * FROM a_nota_bayar2 WHERE id_cus='$idcus' ORDER BY tgl_pemb DESC LIMIT 1")->row("tgl_pemb");
                    $er = explode('-', $tgl_byr);
                    $printTglByr = $er[2]."-".$ar[$er[1]]."-".$er[0];
                    $nominal_byr = $this->db->query("SELECT SUM(nominal_pemb) AS ttls FROM a_nota_bayar2 WHERE id_cus='$idcus' AND tgl_pemb='$tgl_byr'")->row("ttls");
                    if(fmod($nominal_byr, 1) !== 0.00){
                        $nominal_byr2 = number_format($nominal_byr,2,',','.');
                    } else {
                        $nominal_byr2 = number_format($nominal_byr,0,',','.');
                    }
                } else {
                    $tesData = "all";
                //cek nota terakhir
                    $sj = $this->db->query("SELECT * FROM surat_jalan WHERE id_customer='$idcus' AND create_nota='y'  ORDER BY id_sj DESC LIMIT 1")->row("no_sj");
                    $tgl_kirim = $this->db->query("SELECT * FROM surat_jalan WHERE id_customer='$idcus' AND create_nota='y'  ORDER BY id_sj DESC LIMIT 1")->row("tgl_kirim");
                    $jmlnota = $this->db->query("SELECT SUM(total_harga) AS ttl FROM a_nota WHERE no_sj='$sj'")->row("ttl");
                    if(fmod($jmlnota, 1) !== 0.00){
                        $jmlnota2 = number_format($jmlnota,2,',','.');
                    } else {
                        $jmlnota2 = number_format($jmlnota,0,',','.');
                    }
                    $ex = explode('-',$tgl_kirim);
                    $printTglnota = $ex[2]."-".$ar[$ex[1]]."-".$ex[0];
                    //cek pembayaran terakhir
                    $tgl_byr = $this->db->query("SELECT * FROM a_nota_bayar2 WHERE id_cus='$idcus' ORDER BY tgl_pemb DESC LIMIT 1")->row("tgl_pemb");
                    $er = explode('-', $tgl_byr);
                    $printTglByr = $er[2]."-".$ar[$er[1]]."-".$er[0];
                    $nominal_byr = $this->db->query("SELECT SUM(nominal_pemb) AS ttls FROM a_nota_bayar2 WHERE id_cus='$idcus' AND tgl_pemb='$tgl_byr'")->row("ttls");
                    if(fmod($nominal_byr, 1) !== 0.00){
                        $nominal_byr2 = number_format($nominal_byr,2,',','.');
                    } else {
                        $nominal_byr2 = number_format($nominal_byr,0,',','.');
                    }
                }
                $_vall = $val->saldop;
                if($_vall > 100){
        ?>
            <div class="card-awal blue">
                <div class="items" style="color:#2c2e2d"><?=$val->nmcus;?> : </div>
                <div class="items-nilai" id="idpiutang" style="text-align:left;padding-left:60px;font-size:26px;">
                    <?=$val->nominal_piutang;?>
                </div>
                <hr>
                <div class="items-dobel">
                    <div class="dobelm" >
                        <span>Nota Terakhir :</span>
                        <span>Rp. <?=$jmlnota2;?></span>
                        <span><?=$printTglnota;?></span>
                    </div>
                    <div class="dobelm" >
                        <span>Pembayaran Terakhir :</span>
                        <span>Rp. <?=$nominal_byr2;?></span>
                        <span><?=$printTglByr;?></span>
                    </div>
                </div>
            </div>
        <?php  } endforeach; }
        //} 
        ?>
        </div>
    </div>
    <div class="bunderan" id="gotohome">
        <img src="<?=base_url('assets/assetsimg/24.png');?>" alt="Home">
    </div>
    <?php
        $kons = $this->data_model->get_record('dt_konsumen');
        $kons = $this->db->query("SELECT * FROM dt_konsumen WHERE nama_konsumen NOT LIKE 'KM%' AND nama_konsumen NOT LIKE 'PS%' AND nama_konsumen NOT LIKE 'PB%' AND nama_konsumen NOT LIKE 'PH%'");
        $ar_kons = array();
        foreach($kons->result() as $val){
            //echo $val->kode_konstruksi."<br>";
            $ar_kons[] = '"'.$val->nama_konsumen.'"';
        }
        
        $im_kons = implode(',', $ar_kons);
        
    ?>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        const autoCompleteJS = new autoComplete({
            placeHolder: "Ketik Nama Konsumen...",
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
        document.getElementById('sort').addEventListener('click', function() {
            var sortInput = document.getElementById('sorttxt');
            var currentValue = parseInt(sortInput.value);
            var imageElement = document.getElementById('myImage');

            if (currentValue === 0) {
                sortInput.value = '1';
                imageElement.src = "<?=base_url('assets/26.png');?>";
                $.ajax({
                    url:"<?=base_url();?>alldashboard/sortutang",
                    type: "POST",
                    data: {"val" : "dua"},
                    cache: false,
                    success: function(dataResult){
                        $('#mainutama').html(dataResult);
                    }
                });
            } else if (currentValue === 1) {
                sortInput.value = '2';
                imageElement.src = "<?=base_url('assets/27.png');?>";
                $.ajax({
                    url:"<?=base_url();?>alldashboard/sortutang",
                    type: "POST",
                    data: {"val" : "satu"},
                    cache: false,
                    success: function(dataResult){
                        $('#mainutama').html(dataResult);
                    }
                });
            } else if (currentValue === 2){
                sortInput.value = '0';
                imageElement.src = "<?=base_url('assets/25.png');?>";$.ajax({
                    url:"<?=base_url();?>alldashboard/sortutang",
                    type: "POST",
                    data: {"val" : "nol"},
                    cache: false,
                    success: function(dataResult){
                        $('#mainutama').html(dataResult);
                    }
                });
            }
            
            // Untuk memeriksa hasil perubahan value pada konsol
            console.log("Nilai sorttxt sekarang: " + sortInput.value);
            console.log("Sumber gambar sekarang: " + imageElement.src);
        });
        $( "#gotohome" ).on( "click", function() { 
            window.location.href = "<?=base_url('dash-manager');?>";
        });
    </script>
</body>
</html>