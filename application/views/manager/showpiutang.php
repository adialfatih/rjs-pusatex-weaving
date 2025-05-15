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

.lds-roller,
.lds-roller div,
.lds-roller div:after {
  box-sizing: border-box;
}
.lds-roller {
  display: inline-block;
  position: relative;
  width: 80px;
  height: 80px;
}
.lds-roller div {
  animation: lds-roller 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
  transform-origin: 40px 40px;
}
.lds-roller div:after {
  content: " ";
  display: block;
  position: absolute;
  width: 7.2px;
  height: 7.2px;
  border-radius: 50%;
  background: currentColor;
  margin: -3.6px 0 0 -3.6px;
}
.lds-roller div:nth-child(1) {
  animation-delay: -0.036s;
}
.lds-roller div:nth-child(1):after {
  top: 62.62742px;
  left: 62.62742px;
}
.lds-roller div:nth-child(2) {
  animation-delay: -0.072s;
}
.lds-roller div:nth-child(2):after {
  top: 67.71281px;
  left: 56px;
}
.lds-roller div:nth-child(3) {
  animation-delay: -0.108s;
}
.lds-roller div:nth-child(3):after {
  top: 70.90963px;
  left: 48.28221px;
}
.lds-roller div:nth-child(4) {
  animation-delay: -0.144s;
}
.lds-roller div:nth-child(4):after {
  top: 72px;
  left: 40px;
}
.lds-roller div:nth-child(5) {
  animation-delay: -0.18s;
}
.lds-roller div:nth-child(5):after {
  top: 70.90963px;
  left: 31.71779px;
}
.lds-roller div:nth-child(6) {
  animation-delay: -0.216s;
}
.lds-roller div:nth-child(6):after {
  top: 67.71281px;
  left: 24px;
}
.lds-roller div:nth-child(7) {
  animation-delay: -0.252s;
}
.lds-roller div:nth-child(7):after {
  top: 62.62742px;
  left: 17.37258px;
}
.lds-roller div:nth-child(8) {
  animation-delay: -0.288s;
}
.lds-roller div:nth-child(8):after {
  top: 56px;
  left: 12.28719px;
}
@keyframes lds-roller {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
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
    $last_update = $this->db->query("SELECT updatess FROM tes_piutang3 LIMIT 1")->row("updatess");
    $xx = explode(' ', $last_update);
    ?>
    <div class="konten-mobile">
        <h1>Piutang Customer </h1>
        <small style="font-size:11px;color:#525252;">Last Update : <?=$xx[1];?></small>
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
        <div style="width:100%;display:flex;justify-content:center;align-items:center;flex-direction:column;gap:15px;padding-top:20px;" id="mainutama">
            <div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div><span>Loading data...</span>
        </div>
    </div>
    <div class="bunderan" id="gotohome">
        <img src="<?=base_url('assets/assetsimg/24.png');?>" alt="Home">
    </div>
    <?php
        //$kons = $this->data_model->get_record('dt_konsumen');
        //$kons = $this->db->query("SELECT * FROM dt_konsumen WHERE nama_konsumen NOT LIKE 'KM%' AND nama_konsumen NOT LIKE 'PS%' AND nama_konsumen NOT LIKE 'PB%' AND nama_konsumen NOT LIKE 'PH%'");
        $kons = $this->db->query("SELECT id_customer,nama_konsumen FROM `view_nota2` WHERE nama_konsumen NOT LIKE 'KM%' AND nama_konsumen NOT LIKE 'PS%' AND nama_konsumen NOT LIKE 'PH%' GROUP BY id_customer;");
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
                        showThisPiutang('satu', selection);
                    }
                }
            }
        });
        function showThisPiutang(val, nama){
            $('#mainutama').html('<div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div><span>Loading data...</span>');
            $.ajax({
                    url:"<?=base_url();?>alldashboard/sortutang2",
                    type: "POST",
                    data: {"val" : val, "nama" : nama},
                    cache: false,
                    success: function(dataResult){
                        setTimeout(() => {
                            $('#mainutama').html(dataResult);
                        }, 1100);
                        
                    }
            });
        }
        showThisPiutang('satu', 'null');
        document.getElementById('sort').addEventListener('click', function() {
            var sortInput = document.getElementById('sorttxt');
            var currentValue = parseInt(sortInput.value);
            var imageElement = document.getElementById('myImage');

            if (currentValue === 0) {
                sortInput.value = '1';
                imageElement.src = "<?=base_url('assets/26.png');?>";
                showThisPiutang('dua');
            } else if (currentValue === 1) {
                sortInput.value = '2';
                imageElement.src = "<?=base_url('assets/27.png');?>";
                showThisPiutang('satu');
            } else if (currentValue === 2){
                sortInput.value = '0';
                imageElement.src = "<?=base_url('assets/25.png');?>";
                showThisPiutang('nol');
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