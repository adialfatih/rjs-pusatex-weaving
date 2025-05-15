<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Manager</title>
    <link rel="stylesheet" href="<?=base_url('new_db/');?>style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+Vithkuqi&family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/css/autoComplete.02.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>
        .autoComplete_wrapper input{
            width: 95%;
            transform: translateX(10px);
        }
        .dobel.fullrjs { width:100%; background:#035797; color: #fff;}
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
    // echo $namalogin."<br>";
    $ar = array(
        '00' => 'NaN', '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    );
    $ex = explode('-', $tanggalSebelumnya);
    $printHarIni = $namaHari[$hariIni].", ".$tanggalHariini." ".$ar[$bulanHariini]." ".$tahunHariini;
    $printHariKemarin = $namaHari[$hariSebelumnya].", ".$ex[2]." ".$ar[$ex[1]]." ".$ex[0];
    //cek penjualan
    $cekPenjualan = $this->db->query("SELECT SUM(total_panjang) AS ukr FROM a_nota WHERE tgl_nota='$dateNow'")->row("ukr");
    if($cekPenjualan > 0){
        if(fmod($cekPenjualan, 1) !== 0.00){
            $jum_penjualan = number_format($cekPenjualan,2,',','.');
        } else {
            $jum_penjualan = number_format($cekPenjualan,0,',','.');
        }
        $jual_grey = $this->db->query("SELECT a_nota.id_nota, a_nota.no_sj, a_nota.kd, a_nota.konstruksi, a_nota.jml_roll, a_nota.total_panjang, SUM(a_nota.total_panjang) as ukr, a_nota.tgl_nota, new_tb_packinglist.kd, new_tb_packinglist.tanggal_dibuat, new_tb_packinglist.ttl_panjang, new_tb_packinglist.jns_fold FROM a_nota,new_tb_packinglist WHERE a_nota.kd = new_tb_packinglist.kd AND new_tb_packinglist.jns_fold='Grey' AND a_nota.tgl_nota = '$dateNow' ")->row("ukr");
        if(fmod($jual_grey, 1) !== 0.00){
            $jum_penjualan_grey = number_format($jual_grey,2,',','.');
        } else {
            $jum_penjualan_grey = number_format($jual_grey,0,',','.');
        }
        $jual_finish = $this->db->query("SELECT a_nota.id_nota, a_nota.no_sj, a_nota.kd, a_nota.konstruksi, a_nota.jml_roll, a_nota.total_panjang, SUM(a_nota.total_panjang) as ukr, a_nota.tgl_nota, new_tb_packinglist.kd, new_tb_packinglist.tanggal_dibuat, new_tb_packinglist.ttl_panjang, new_tb_packinglist.jns_fold FROM a_nota,new_tb_packinglist WHERE a_nota.kd = new_tb_packinglist.kd AND new_tb_packinglist.jns_fold='Finish' AND a_nota.tgl_nota = '$dateNow' ")->row("ukr");
        if(fmod($jual_finish, 1) !== 0.00){
            $jum_penjualan_finish = number_format($jual_finish,2,',','.');
        } else {
            $jum_penjualan_finish = number_format($jual_finish,0,',','.');
        }
    } else {
        $jum_penjualan = 0;
        $jum_penjualan_grey = 0;
        $jum_penjualan_finish = 0;
    }
    // end penjualan
    // show stok 
    $stokGrey = $this->db->query("SELECT SUM(ukuran) AS ukr FROM data_fol WHERE jns_fold='Grey' AND (posisi='Pusatex' OR posisi LIKE '%PKT%')")->row("ukr");
    $stokFinish2 = $this->db->query("SELECT SUM(ukuran) AS ukr FROM data_fol WHERE jns_fold='Finish' AND (posisi='Pusatex' OR posisi LIKE '%PKT%')")->row("ukr");
    $stokFinish = round($stokFinish2,1);
    $allStok2 = floatval($stokGrey) + floatval($stokFinish);
    $allStok = round($allStok2,1);
    if($allStok > 0){
        if(fmod($stokGrey, 1) !== 0.00){
            $showStokGrey = number_format($stokGrey,2,',','.');
        } else {
            $showStokGrey = number_format($stokGrey,0,',','.');
        }
        if(fmod($stokFinish, 1) !== 0.00){
            $showStokFinish = number_format($stokFinish,2,',','.');
        } else {
            $showStokFinish = number_format($stokFinish,0,',','.');
        }
        if(fmod($allStok, 1) !== 0.00){
            $showAllStok = number_format($allStok,2,',','.');
        } else {
            $showAllStok = number_format($allStok,0,',','.');
        }
    } else {
        $showStokGrey = 0;
        $showStokFinish = 0;
        $showAllStok = 0;
    }
    //end stok
    //start produksi harian
    $prod = $this->db->query("SELECT SUM(ukuran_ori) AS jml FROM data_ig WHERE tanggal='$dateNow'")->row("jml");
    $prod1 = $this->db->query("SELECT SUM(ukuran_ori) AS jml FROM data_if WHERE tgl_potong='$dateNow'")->row("jml");
    if(floatval($prod1) > 0){
        $prod1 = floatval($prod1) / 0.9144;
        $prod1 = round($prod1,1);
    } else {
        $prod1 = 0;
    }
    if(floatval($prod) > 0){
        $prod = floatval($prod);
    } else {
        $prod = 0;
    }
    $_allinspect = floatval($prod) + floatval($prod1);
    
    if(floor($prod) == $prod){
        $prod_inspect = number_format($prod,0,',','.');
    } else {
        $prod_inspect = number_format($prod,2,',','.');
    }
    if(floor($prod1) == $prod1){
        $prod_inspectf = number_format($prod1,0,',','.');
    } else {
        $prod_inspectf = number_format($prod1,2,',','.');
    }
    if($_allinspect > 0){
    if(floor($_allinspect) == $_allinspect){
        $_allinspect2 = number_format($_allinspect,0,',','.');
    } else {
        $_allinspect2 = number_format($_allinspect,2,',','.');
    }} else {
        $_allinspect2 = 0;
    }
    $prod_folding = $this->db->query("SELECT SUM(ukuran) AS ukr FROM data_fol WHERE jns_fold='Grey' AND tgl='$dateNow'")->row("ukr");
    $prod_foldinf = $this->db->query("SELECT SUM(ukuran) AS ukr FROM data_fol WHERE jns_fold='Finish' AND tgl='$dateNow'")->row("ukr");
    if(floatval($prod_folding) > 0){
        $nilai_folgrey = floatval($prod_folding);
        if(floor($prod_folding) == $prod_folding){
            $prod_folding = number_format($prod_folding,0,',','.');
        } else {
            $prod_folding = number_format($prod_folding,2,',','.');
        }
        
    } else {
        $prod_folding = 0;
        $nilai_folgrey = 0;
    }
    if(floatval($prod_foldinf) > 0){
        $nilai_folfinish = floatval($prod_foldinf);
        if(floor($prod_foldinf) == $prod_foldinf){
            $prod_foldinf = number_format($prod_foldinf,0,',','.');
        } else {
            $prod_foldinf = number_format($prod_foldinf,2,',','.');
        }
    } else {
        $prod_foldinf = 0;
        $nilai_folfinish = 0;
    }
    $all_fol = $nilai_folgrey + $nilai_folfinish;
    if(floor($all_fol) == $all_fol){
        $all_fol = number_format($all_fol,0,',','.');
    } else {
        $all_fol = number_format($all_fol,2,',','.');
    }
    //end produksi harian
    //start produksi ajl
    $ajl = $this->db->query("SELECT SUM(hasil) as ukr FROM dt_produksi_mesin WHERE tanggal_produksi = '$tanggalSebelumnya'")->row("ukr");
    //$ajl_smt = $this->db->query("SELECT SUM(hasil) as ukr FROM dt_produksi_mesin WHERE tanggal_produksi = '$tanggalSebelumnya' AND lokasi='Samatex'")->row("ukr");
    $ajl_rjs = $this->db->query("SELECT SUM(hasil) as ukr FROM dt_produksi_mesin WHERE tanggal_produksi = '$tanggalSebelumnya' AND lokasi='RJS'")->row("ukr");
    if(fmod($ajl, 1) !== 0.00){
        $ajl1 = number_format($ajl,2,',','.');
    } else {
        $ajl1 = number_format($ajl,0,',','.');
    }
    // if(fmod($ajl_smt, 1) !== 0.00){
    //     $ajl2 = number_format($ajl_smt,2,',','.');
    // } else {
    //     $ajl2 = number_format($ajl_smt,0,',','.');
    // }
    if(fmod($ajl_rjs, 1) !== 0.00){
        $ajl3 = number_format($ajl_rjs,2,',','.');
    } else {
        $ajl3 = number_format($ajl_rjs,0,',','.');
    }
    ?>
    <div class="konten-mobile">
        <h1>Dashboard Rindang </h1>
        <?php if($namalogin=="Pak Hamid" OR $namalogin=="Husain" OR $namalogin=="Ahmad" OR $namalogin=="Oki" OR $namalogin=="Fahmi" OR $namalogin=="Adi Subuhadir" OR $namalogin=="Lilik "){ ?>
            <div class="card-awal blue">
                <div class="items" style="color:#2c2e2d">Total Piutang : </div>
                <div class="items-nilai" id="idpiutang">
                    Loading...
                    <!--<span style="color:#2c2e2d">Update : <=$printHarIni;?></span>-->
                </div>
            </div>
        <?php } if($namalogin=="Lilik "){} else { ?>
        <div class="card-awal blue">
            <div class="items">Penjualan : </div>
            <div class="items-nilai">
                <a href="<?=base_url('penjualan-all');?>" class="nodec"><?=$jum_penjualan;?></a>
                <span><?=$printHarIni;?></span>
            </div>
            <div class="items-dobel">
                <div class="dobel green" id="hrefToPenjualanGrey">
                    Grey : <?=$jum_penjualan_grey;?>
                </div>                
                <div class="dobel red" id="hrefToPenjualanFinish">
                    Finish : <?=$jum_penjualan_finish;?>
                </div>
            </div>
        </div>
        <?php  } //bulilik jangan liat ini
        $stokrjs = $this->db->query("SELECT SUM(ukuran_ori) as ukr FROM data_ig WHERE dep='RJS' AND loc_now REGEXP '^RJS[0-9]+$' ")->row("ukr");
        if(fmod($stokrjs, 1) !== 0.00){
            $stokrjs2 = number_format($stokrjs,2,',','.');
        } else {
            $stokrjs2 = number_format($stokrjs,0,',','.');
        }
        ?>
        <div class="card-awal blue">
            <div class="items">Stok : </div>
            <div class="items-nilai" id="hrefToStokAll">
                <?=$showAllStok;?>
            </div>
            <div class="items-dobel">
                <div class="dobel green" id="hrefToStokGrey">
                    Grey : <?=$showStokGrey;?>
                </div>
                <div class="dobel red" id="hrefToStokFinish">
                    Finish : <?=$showStokFinish;?>
                </div>
            </div>
            <?php if($namalogin=="Husain"){} else {?>
            <div class="items-dobel">
               <div class="dobel fullrjs" id="clickToInspectRjs982">
                   Stok Di Rindang : <?=$stokrjs2;?>
                </div>
                
            </div>
            <?php } ?>
        </div>
        
        
        <?php  if($namalogin=="Adi Subuhadir"){ ?>
        <div class="card-awal blue">
            <div class="items">Presentase BS : </div>
            <div class="items-nilai">
                <a href="<?=base_url('penjualan-all');?>" class="nodec">50%</a>
            </div>
            <div class="items-dobel">
                <div class="dobel green" id="hrefToPenjualanGrey">
                    Grey : 20%
                </div>                
                <div class="dobel red" id="hrefToPenjualanFinish">
                    Finish : 30%
                </div>
            </div>
        </div>
        <?php } 
        //echo $namalogin."tes";
        if($namalogin=="Lilik "){ } else { ?>
        <div class="card-awal blue">
            <div class="items">Produksi (AJL):</div>
            <div class="items-nilai" id="clickToAjlRjs">
                <?=$ajl3;?>
                <span><?=$printHariKemarin;?></span>
            </div>
            <!--<div class="items-dobel">-->
            <!--    <div class="dobel grey" id="clickToAjlSamatex">-->
            <!--        Samatex : <=$ajl2;?>-->
            <!--    </div>-->
            <!--    <div class="dobel grey" id="clickToAjlRjs">-->
            <!--        RJS : <=$ajl3;?>-->
            <!--    </div>-->
            <!--</div>-->
            <!-- <div class="items-dobel">
                <div class="dobel fullrjs" id="clickToInspectRjs982">
                    Samatex : <=$ajl2;?>
                </div>
                
            </div> -->
            
        </div>
        <div class="card-awal blue">
            <div class="items">Inspect : </div>
            <div class="items-nilai" id="clickToInspect">
                <?=$_allinspect2;?>
                <span><?=$printHarIni;?></span>
            </div>
            <div class="items-dobel">
                <div class="dobel green" id="clickToInspectGrey">
                    Grey : <?=$prod_inspect;?>
                </div>
                <div class="dobel red" id="clickToInspectFinish">
                    Finish : <?=$prod_inspectf;?>
                </div>
            </div>
            <!--<div class="items-dobel">-->
            <!--    <div class="dobel fullrjs" id="clickToInspectRjs">-->
            <!--        RJS Inspect Grey : <=$igrjs2;?>-->
            <!--    </div>-->
                
            <!--</div>-->
        </div>
        <div class="card-awal blue">
            <div class="items">Folding : </div>
            <div class="items-nilai" id="clickToFolding">
                <?=$all_fol;?>
                <span><?=$printHarIni;?></span>
            </div>
            <div class="items-dobel">
                <div class="dobel green" id="clickToFoldingGrey">
                    Grey : <?=$prod_folding;?>
                </div>
                <div class="dobel red" id="clickToFoldingFinish">
                    Finish : <?=$prod_foldinf;?>
                </div>
            </div>
        </div>
        <!-- <div class="card-awal blue">
            <div class="items">Stok di Pusatex : </div>
            <div class="items-nilai" id="agf_totalValue">
                Loading...
            </div>
            <div class="items-dobel">
                <div class="dobel green" id="agf_total2">
                   Loading...
                </div>
                <div class="dobel red" id="agf_rjs2">
                   Loading...
                </div>
            </div>
        </div> -->
        <div class="card-awal blue">
            <div class="items">Stok di Pusatex : </div>
            <div class="items-nilai" id="agf_totalValue">
                Loading...
            </div>
            <?php if($namalogin=="Husain"){} else {?>
            <div class="items-dobel">
                <div class="dobel green" id="agf_grey" style="font-size:14px;">
                   Loading...
                </div>
                <div class="dobel red" id="agf_finish" style="font-size:14px;">
                   Loading...
                </div>
            </div>
            <?php } ?>
        </div>
        <input type="hidden" id="agf_total">
        <div class="card-awal blue">
            <div class="items">Download Laporan Bulanan ( Pdf ) : </div>
            <div class="items-nilai" style="display:flex;align-items:center;justify-content:center;margin-top:20px;">
                <img src="<?=base_url('report.png');?>" alt="Gambar" style="width:30px;margin-right:10px;">
                <input type="month" style="padding:7px;border-radius:3px;outline:none;border:none;width:50%;" placeholder="Pilih bulan" id="iptbulan">
            </div>
            <div class="items-dobel">
                <div class="dobel green" style="display:flex;justify-content:center;font-size:11px;align-items:center;" id="lap_jual">
                    Penjualan
                </div>
                <div class="dobel grey" style="display:flex;justify-content:center;text-align:center;font-size:12px;align-items:center;" id="lap_prod_rjs">
                    Produksi
                </div>
                <!--<div class="dobel grey" style="display:flex;justify-content:center;font-size:11px;align-items:center;" id="lap_prod_rjs">-->
                <!--    Produksi RJS-->
                <!--</div>-->
                <div class="dobel grey" style="display:flex;justify-content:center;font-size:11px;align-items:center;" id="lap_inspect">
                    Inspect Grey
                </div>
            </div>
        </div>
        <?php } ?> 
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        $( "#hrefToPenjualanGrey" ).on( "click", function() { 
            window.location.href = "<?=base_url('penjualan-grey');?>";
        });
        $( "#hrefToPenjualanFinish" ).on( "click", function() { 
            window.location.href = "<?=base_url('penjualan-finish');?>";
        });
        $( "#hrefToStokGrey" ).on( "click", function() { 
            window.location.href = "<?=base_url('stok-grey');?>";
        });
        $( "#hrefToStokFinish" ).on( "click", function() { 
            window.location.href = "<?=base_url('stok-finish');?>";
        });
        $( "#hrefToStokAll" ).on( "click", function() { 
            window.location.href = "<?=base_url('stok-all');?>";
        });
        $( "#clickToInspect" ).on( "click", function() { 
            window.location.href = "<?=base_url('inspect-all');?>";
        });
        $( "#clickToInspectGrey" ).on( "click", function() { 
            window.location.href = "<?=base_url('inspect-grey');?>";
        });
        $( "#clickToInspectFinish" ).on( "click", function() { 
            window.location.href = "<?=base_url('inspect-finish');?>";
        });
        $( "#clickToInspectRjs" ).on( "click", function() { 
            window.location.href = "<?=base_url('inspect-rjs');?>";
        });
        $( "#clickToFolding" ).on( "click", function() { 
            window.location.href = "<?=base_url('folding');?>";
        });
        $( "#clickToFoldingGrey" ).on( "click", function() { 
            window.location.href = "<?=base_url('folding-grey');?>";
        });
        $( "#clickToFoldingFinish" ).on( "click", function() { 
            window.location.href = "<?=base_url('folding-finish');?>";
        });
        $( "#clickToAjlSamatex" ).on( "click", function() { 
            window.location.href = "<?=base_url('ajl-samatex');?>";
        });
        $( "#clickToAjlRjs" ).on( "click", function() { 
            window.location.href = "<?=base_url('ajl-rindang');?>";
        });
        $( "#idpiutang" ).on( "click", function() { 
            window.location.href = "<?=base_url('show-piutang');?>";
        });
        $( "#lap_jual" ).on( "click", function() { 
            var bulan = $('#iptbulan').val();
            console.log('jual '+ bulan);
            if(bulan == ""){
                peringatan('Silahkan pilih bulan');
            } else {
                window.location.href = "<?=base_url('cetakmng/penjualan/');?>"+bulan;
            }
        });
        $( "#lap_prod" ).on( "click", function() {
            var bulan = $('#iptbulan').val();
            console.log('produksi '+ bulan);
            if(bulan == ""){
                peringatan('Silahkan pilih bulan');
            } else {
                window.location.href = "<?=base_url('cetakmng/produksi/');?>"+bulan;
            }
            
        });
        $( "#lap_prod_rjs" ).on( "click", function() {
            var bulan = $('#iptbulan').val();
            console.log('produksi rjs'+ bulan);
            if(bulan == ""){
                peringatan('Silahkan pilih bulan');
            } else {
                window.location.href = "<?=base_url('cetakmng/produksirjs/');?>"+bulan;
            }
            
        });
        $( "#lap_inspect" ).on( "click", function() {
            sukes('Loading...');
            var bulan = $('#iptbulan').val();
            console.log('Inspect Grey : '+ bulan);
            if(bulan == ""){
                peringatan('Silahkan pilih bulan');
            } else {
                peringatan('Download Gagal. Server Error');
                //window.location.href = "<?=base_url('cetakmng/inspectgrey/');?>"+bulan;
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
        function sukes(txt) {
            Toastify({
                    text: ""+txt+"",
                    duration: 4000,
                    close:true,
                    gravity:"top",
                    position: "right",
                    backgroundColor: "#17cc10",
            }).showToast();
        }
        function cek_agf(){
            $.ajax({
                    url:"<?=base_url();?>alldashboard/show_agf",
                    type: "POST",
                    data: {"tes" : "tes"},
                        cache: false,
                        success: function(dataResult){
                            var dataResult = JSON.parse(dataResult);
                            if(dataResult.statusCode==200){
                                $('#agf_totalValue').html(''+dataResult.psn_total+'');
                                $('#agf_grey').html('Proses Grey : '+dataResult.psn_grey+'');
                                $('#agf_finish').html('Proses Finish :'+dataResult.psn_finish+'');
                                $('#agf_total').val(''+dataResult.psn_total+'');
                            } else {
                                $('#agf_totalValue').html('Erorr');
                                $('#agf_total').val('Erorr');
                                $('#agf_grey').html('e');
                                $('#agf_finish').html('e');
                            }
                        }
            });
        }
        function cek_piutang(){
            $.ajax({
                    url:"<?=base_url();?>alldashboard/showpiutang",
                    type: "POST",
                    data: {"tes" : "tes"},
                        cache: false,
                        success: function(dataResult){
                            var dataResult = JSON.parse(dataResult);
                            if(dataResult.statusCode==200){
                                $('#idpiutang').html('Rp. '+dataResult.psn+' ');
                                //$('#idpiutang').html('Rp. '+dataResult.psn+' <span style="color:#2c2e2d">Update : <=$printHarIni;?> '+dataResult.owek+'</span>');
                            } else {
                                $('#idpiutang').html('Erorr Calculated..');
                            }
                        }
            });
        }
        cek_agf();
        cek_piutang();
        $( "#agf_total" ).on( "click", function() { 
            window.location.href = "<?=base_url('stok-on-pusatex3');?>";
        });
        $( "#agf_samatex" ).on( "click", function() { 
            window.location.href = "<?=base_url('stok-on-pusatex2');?>";
        });
        $( "#agf_grey" ).on( "click", function() { 
            window.location.href = "<?=base_url('stok-on-pusatex');?>/grey";
        });
        $( "#agf_finish" ).on( "click", function() { 
            window.location.href = "<?=base_url('stok-on-pusatex');?>/finish";
        });
        $( "#clickToInspectRjs982" ).on( "click", function() { 
            window.location.href = "<?=base_url('stok-on-rjs');?>";
        });
    </script>
</body>
</html>