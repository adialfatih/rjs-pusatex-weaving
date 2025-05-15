<?php $bln = [ '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des', ]; 
$idcus = $kons['id_konsumen'];
if($tipeuri == 0){
    $_thisTipe = "";
} elseif($tipeuri == 1){
    $_thisTipe = "SB";
} elseif($tipeuri == 2){
    $_thisTipe = "ECER";
} else {
    $_thisTipe = "404";
}
?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Kartu Piutang Konsumen</h4>
                                    <small>Menampilkan kartu piutang konsumen atas nama <strong><?=$kons['nama_konsumen'];?></strong></small>
								</div>
							</div>
                            <?php if($_thisTipe != "404"){ ?>
							<div class="col-md-6 col-sm-12 text-right">
								<!-- <div class="dropdown"> -->
									<a class="btn btn-success dropdown-toggle" href="<?=base_url('exportexcel/piutang/'.$idcus.'/'.$tipeuri);?>" role="button" target="_blank">
										Export & Download
									</a>
									<!-- <div class="dropdown-menu dropdown-menu-right">
										<a class="dropdown-item" href="<=base_url('exportexcel/piutang/'.$idcus);?>">Export Excel</a>
										<a class="dropdown-item" href="#">Export PDF</a>
									</div> -->
								<!-- </div> -->
							</div>
                            <?php } ?>
						</div>
					</div>
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
                            <table class="table">
                                <tr>
                                    <td style="width:200px;">Nama Konsumen</td>
                                    <th><?=$kons['nama_konsumen'];?></th>
                                </tr>
                                <tr>
                                    <td>No HP</td>
                                    <th><?=$kons['no_hp'];?></th>
                                </tr>
                                <tr>
                                    <td>Alamat</td>
                                    <th><?=$kons['alamat'];?></th>
                                </tr>
                                <?php if($_thisTipe == "SB"){
                                    echo '<tr><td>Piutang</td><th>SB</th></tr>';
                                } if($_thisTipe == "ECER"){
                                    echo '<tr><td>Piutang</td><th>ECER</th></tr>';
                                }
                                
                                ?>
                            </table>
                            <?php if($_thisTipe == "404"){ ?>
                            <font style="color:red;">Token Error.. You cannot open this page.!!</font>
                            <?php } else { ?>
							<table class="table table-bordered stripe hover nowrap">
								<thead>
									<tr>
										<th class="table-plus datatable-nosort" style="text-align:center;">Tanggal</th>
										<!-- <th style="text-align:center;">Keterangan</th> -->
										<th style="text-align:center;">No. Faktur</th>
										<th style="text-align:center;">No. Nota</th>
										<th style="text-align:center;">Jenis Barang</th>
										<th style="text-align:center;">Panjang</th>
										<th style="text-align:center;">Harga</th>
										<th style="text-align:center;">Total Harga</th>
										<th style="text-align:center;">Bayar</th>
                                        <th style="text-align:center;">Saldo</th>
									</tr>
								</thead>
								<tbody>
                                    <?php
                                    $tglar = array();
                                    $idnotasr = array();
                                    $jenis = array();
                                    $arid = array();
                                    $surjal = array();
                                    $konstruksi = array();
                                    $panjang = array();
                                    $panjang_sb = array();
                                    $panjang_ecer = array();
                                    $harga = array();
                                    $harga_total = array();
                                    $prc_sb = array();
                                    $prc_ecer = array();
                                    $idcus = $kons['id_konsumen'];
                                    $query = $this->db->query("SELECT a_nota.id_nota, a_nota.no_sj, a_nota.konstruksi, a_nota.total_panjang, a_nota.harga_satuan, a_nota.total_harga, a_nota.tgl_nota, a_nota.pres_sb, a_nota.pres_ecer, surat_jalan.id_sj, surat_jalan.no_sj, surat_jalan.id_customer FROM a_nota,surat_jalan WHERE a_nota.no_sj=surat_jalan.no_sj AND surat_jalan.id_customer='$idcus'");
                                    
                                    foreach($query->result() as $val):
                                        $tglar[]= $val->tgl_nota;
                                        $jenis[]='Pembelian';
                                        $arid[]=$val->id_nota;
                                        $surjal[]=$val->no_sj;
                                        $konstruksi[] = $val->konstruksi;
                                        $panjang[] = $val->total_panjang;
                                        if($val->pres_sb > 0){
                                            $_pjgSB = (floatval($val->total_panjang) * floatval($val->pres_sb)) / 100;
                                            $panjang_sb[] = round($_pjgSB,2);
                                        } else {
                                            $panjang_sb[] = 0;
                                        }
                                        if($val->pres_ecer > 0){
                                            $_pjgECER = (floatval($val->total_panjang) * floatval($val->pres_ecer)) / 100;
                                            $panjang_ecer[] = round($_pjgECER,2);
                                        } else {
                                            $panjang_ecer[] = 0;
                                        }
                                        $harga[] = $val->harga_satuan;
                                        $harga_total[] = $val->total_harga;
                                        $idnota = $val->id_nota;
                                        $idnotasr[] = $val->id_nota;
                                        $prc_sb[] = $val->pres_sb;
                                        $prc_ecer[] = $val->pres_ecer;
                                        //if($_thisTipe==""){
                                            $cekbyr = $this->data_model->get_byid('a_nota_bayar', ['id_nota'=>$idnota]);
                                        //} else {
                                            //$cekbyr = $this->data_model->get_byid('a_nota_bayar', ['id_nota'=>$idnota, 'tujuan_rek'=>$_thisTipe]);
                                        //}
                                        foreach($cekbyr->result() as $val2):
                                            $tglar[]= $val2->tgl_pemb;
                                            $jenis[]='Pembayaran';
                                            $arid[]=$val2->id_pemb;
                                            $surjal[]="null";
                                            $konstruksi[] = "null";
                                            $panjang[] = "null";
                                            $harga[] = "null";
                                            $harga_total[] = "null";
                                            $idnotasr[] = "null";
                                            $prc_sb[] = "null";
                                            $prc_ecer[] = "null";
                                            $panjang_ecer[] = "null";
                                            $panjang_sb[] = "null";
                                        endforeach;
                                    endforeach;
                                    if($idcus==100){
                                        $allIdKm = $this->db->query("SELECT * FROM dt_konsumen WHERE nama_konsumen LIKE 'KM%'");
                                        foreach ($allIdKm->result() as $key => $value) {
                                            $newid = $value->id_konsumen;
                                            $query = $this->db->query("SELECT a_nota.id_nota, a_nota.no_sj, a_nota.konstruksi, a_nota.total_panjang, a_nota.harga_satuan, a_nota.total_harga, a_nota.tgl_nota, a_nota.pres_sb, a_nota.pres_ecer, surat_jalan.id_sj, surat_jalan.no_sj, surat_jalan.id_customer FROM a_nota,surat_jalan WHERE a_nota.no_sj=surat_jalan.no_sj AND surat_jalan.id_customer='$newid'");
                                    
                                            foreach($query->result() as $val):
                                                $tglar[]= $val->tgl_nota;
                                                $jenis[]='Pembelian';
                                                $arid[]=$val->id_nota;
                                                $surjal[]=$val->no_sj;
                                                $konstruksi[] = $val->konstruksi;
                                                $panjang[] = $val->total_panjang;
                                                if($val->pres_sb > 0){
                                                    $_pjgSB = (floatval($val->total_panjang) * floatval($val->pres_sb)) / 100;
                                                    $panjang_sb[] = round($_pjgSB,2);
                                                } else {
                                                    $panjang_sb[] = 0;
                                                }
                                                if($val->pres_ecer > 0){
                                                    $_pjgECER = (floatval($val->total_panjang) * floatval($val->pres_ecer)) / 100;
                                                    $panjang_ecer[] = round($_pjgECER,2);
                                                } else {
                                                    $panjang_ecer[] = 0;
                                                }
                                                $harga[] = $val->harga_satuan;
                                                $harga_total[] = $val->total_harga;
                                                $idnota = $val->id_nota;
                                                $idnotasr[] = $val->id_nota;
                                                //$cekbyr = $this->data_model->get_byid('a_nota_bayar', ['id_nota'=>$idnota]);
                                                $prc_sb[] = $val->pres_sb;
                                                $prc_ecer[] = $val->pres_ecer;
                                                //if($_thisTipe==""){
                                                    $cekbyr = $this->data_model->get_byid('a_nota_bayar', ['id_nota'=>$idnota]);
                                                //} else {
                                                    //$cekbyr = $this->data_model->get_byid('a_nota_bayar', ['id_nota'=>$idnota, 'tujuan_rek'=>$_thisTipe]);
                                                //}
                                                foreach($cekbyr->result() as $val2):
                                                    $tglar[]= $val2->tgl_pemb;
                                                    $jenis[]='Pembayaran';
                                                    $arid[]=$val2->id_pemb;
                                                    $surjal[]="null";
                                                    $konstruksi[] = "null";
                                                    $panjang[] = "null";
                                                    $harga[] = "null";
                                                    $harga_total[] = "null";
                                                    $idnotasr[] = "null";
                                                    $prc_sb[] = "null";
                                                    $prc_ecer[] = "null";
                                                    $panjang_ecer[] = "null";
                                                    $panjang_sb[] = "null";
                                                endforeach;
                                            endforeach;
                                        }
                                    } //if KM ended
                                    if($idcus==29){
                                        $allIdKm = $this->db->query("SELECT * FROM dt_konsumen WHERE nama_konsumen LIKE 'PS%'");
                                        foreach ($allIdKm->result() as $key => $value) {
                                            $newid = $value->id_konsumen;
                                            $query = $this->db->query("SELECT a_nota.id_nota, a_nota.no_sj, a_nota.konstruksi, a_nota.total_panjang, a_nota.harga_satuan, a_nota.total_harga, a_nota.tgl_nota, a_nota.pres_sb, a_nota.pres_ecer, surat_jalan.id_sj, surat_jalan.no_sj, surat_jalan.id_customer FROM a_nota,surat_jalan WHERE a_nota.no_sj=surat_jalan.no_sj AND surat_jalan.id_customer='$newid'");
                                    
                                            foreach($query->result() as $val):
                                                $tglar[]= $val->tgl_nota;
                                                $jenis[]='Pembelian';
                                                $arid[]=$val->id_nota;
                                                $surjal[]=$val->no_sj;
                                                $konstruksi[] = $val->konstruksi;
                                                $panjang[] = $val->total_panjang;
                                                if($val->pres_sb > 0){
                                                    $_pjgSB = (floatval($val->total_panjang) * floatval($val->pres_sb)) / 100;
                                                    $panjang_sb[] = round($_pjgSB,2);
                                                } else {
                                                    $panjang_sb[] = 0;
                                                }
                                                if($val->pres_ecer > 0){
                                                    $_pjgECER = (floatval($val->total_panjang) * floatval($val->pres_ecer)) / 100;
                                                    $panjang_ecer[] = round($_pjgECER,2);
                                                } else {
                                                    $panjang_ecer[] = 0;
                                                }
                                                $harga[] = $val->harga_satuan;
                                                $harga_total[] = $val->total_harga;
                                                $idnota = $val->id_nota;
                                                $idnotasr[] = $val->id_nota;
                                                //$cekbyr = $this->data_model->get_byid('a_nota_bayar', ['id_nota'=>$idnota]);
                                                $prc_sb[] = $val->pres_sb;
                                                $prc_ecer[] = $val->pres_ecer;
                                                //if($_thisTipe==""){
                                                    $cekbyr = $this->data_model->get_byid('a_nota_bayar', ['id_nota'=>$idnota]);
                                                //} else {
                                                    //$cekbyr = $this->data_model->get_byid('a_nota_bayar', ['id_nota'=>$idnota, 'tujuan_rek'=>$_thisTipe]);
                                                //}
                                                foreach($cekbyr->result() as $val2):
                                                    $tglar[]= $val2->tgl_pemb;
                                                    $jenis[]='Pembayaran';
                                                    $arid[]=$val2->id_pemb;
                                                    $surjal[]="null";
                                                    $konstruksi[] = "null";
                                                    $panjang[] = "null";
                                                    $harga[] = "null";
                                                    $harga_total[] = "null";
                                                    $idnotasr[] = "null";
                                                    $prc_sb[] = "null";
                                                    $prc_ecer[] = "null";
                                                    $panjang_ecer[] = "null";
                                                    $panjang_sb[] = "null";
                                                endforeach;
                                            endforeach;
                                        }
                                    } //if ps ended
                                    if($idcus==101){
                                        $allIdKm = $this->db->query("SELECT * FROM dt_konsumen WHERE nama_konsumen LIKE 'PB%'");
                                        foreach ($allIdKm->result() as $key => $value) {
                                            $newid = $value->id_konsumen;
                                            $query = $this->db->query("SELECT a_nota.id_nota, a_nota.no_sj, a_nota.konstruksi, a_nota.total_panjang, a_nota.harga_satuan, a_nota.total_harga, a_nota.tgl_nota, a_nota.pres_sb, a_nota.pres_ecer, surat_jalan.id_sj, surat_jalan.no_sj, surat_jalan.id_customer FROM a_nota,surat_jalan WHERE a_nota.no_sj=surat_jalan.no_sj AND surat_jalan.id_customer='$newid'");
                                    
                                            foreach($query->result() as $val):
                                                $tglar[]= $val->tgl_nota;
                                                $jenis[]='Pembelian';
                                                $arid[]=$val->id_nota;
                                                $surjal[]=$val->no_sj;
                                                $konstruksi[] = $val->konstruksi;
                                                $panjang[] = $val->total_panjang;
                                                if($val->pres_sb > 0){
                                                    $_pjgSB = (floatval($val->total_panjang) * floatval($val->pres_sb)) / 100;
                                                    $panjang_sb[] = round($_pjgSB,2);
                                                } else {
                                                    $panjang_sb[] = 0;
                                                }
                                                if($val->pres_ecer > 0){
                                                    $_pjgECER = (floatval($val->total_panjang) * floatval($val->pres_ecer)) / 100;
                                                    $panjang_ecer[] = round($_pjgECER,2);
                                                } else {
                                                    $panjang_ecer[] = 0;
                                                }
                                                $harga[] = $val->harga_satuan;
                                                $harga_total[] = $val->total_harga;
                                                $idnota = $val->id_nota;
                                                $idnotasr[] = $val->id_nota;
                                                //$cekbyr = $this->data_model->get_byid('a_nota_bayar', ['id_nota'=>$idnota]);
                                                $prc_sb[] = $val->pres_sb;
                                                $prc_ecer[] = $val->pres_ecer;
                                                //if($_thisTipe==""){
                                                    $cekbyr = $this->data_model->get_byid('a_nota_bayar', ['id_nota'=>$idnota]);
                                                //} else {
                                                    //$cekbyr = $this->data_model->get_byid('a_nota_bayar', ['id_nota'=>$idnota, 'tujuan_rek'=>$_thisTipe]);
                                                //}
                                                foreach($cekbyr->result() as $val2):
                                                    $tglar[]= $val2->tgl_pemb;
                                                    $jenis[]='Pembayaran';
                                                    $arid[]=$val2->id_pemb;
                                                    $surjal[]="null";
                                                    $konstruksi[] = "null";
                                                    $panjang[] = "null";
                                                    $harga[] = "null";
                                                    $harga_total[] = "null";
                                                    $idnotasr[] = "null";
                                                    $prc_sb[] = "null";
                                                    $prc_ecer[] = "null";
                                                    $panjang_ecer[] = "null";
                                                    $panjang_sb[] = "null";
                                                endforeach;
                                            endforeach;
                                        }
                                    } //if pB ended
                                    if($idcus==235){
                                        $allIdKm = $this->db->query("SELECT * FROM dt_konsumen WHERE nama_konsumen LIKE 'PH%' OR nama_konsumen='PUTRI HANAN'");
                                        foreach ($allIdKm->result() as $key => $value) {
                                            $newid = $value->id_konsumen;
                                            $query = $this->db->query("SELECT a_nota.id_nota, a_nota.no_sj, a_nota.konstruksi, a_nota.total_panjang, a_nota.harga_satuan, a_nota.total_harga, a_nota.tgl_nota, a_nota.pres_sb, a_nota.pres_ecer, surat_jalan.id_sj, surat_jalan.no_sj, surat_jalan.id_customer FROM a_nota,surat_jalan WHERE a_nota.no_sj=surat_jalan.no_sj AND surat_jalan.id_customer='$newid'");
                                    
                                            foreach($query->result() as $val):
                                                $tglar[]= $val->tgl_nota;
                                                $jenis[]='Pembelian';
                                                $arid[]=$val->id_nota;
                                                $surjal[]=$val->no_sj;
                                                $konstruksi[] = $val->konstruksi;
                                                $panjang[] = $val->total_panjang;
                                                if($val->pres_sb > 0){
                                                    $_pjgSB = (floatval($val->total_panjang) * floatval($val->pres_sb)) / 100;
                                                    $panjang_sb[] = round($_pjgSB,2);
                                                } else {
                                                    $panjang_sb[] = 0;
                                                }
                                                if($val->pres_ecer > 0){
                                                    $_pjgECER = (floatval($val->total_panjang) * floatval($val->pres_ecer)) / 100;
                                                    $panjang_ecer[] = round($_pjgECER,2);
                                                } else {
                                                    $panjang_ecer[] = 0;
                                                }
                                                $harga[] = $val->harga_satuan;
                                                $harga_total[] = $val->total_harga;
                                                $idnota = $val->id_nota;
                                                $idnotasr[] = $val->id_nota;
                                                //$cekbyr = $this->data_model->get_byid('a_nota_bayar', ['id_nota'=>$idnota]);
                                                $prc_sb[] = $val->pres_sb;
                                                $prc_ecer[] = $val->pres_ecer;
                                                //if($_thisTipe==""){
                                                    $cekbyr = $this->data_model->get_byid('a_nota_bayar', ['id_nota'=>$idnota]);
                                                //} else {
                                                    //$cekbyr = $this->data_model->get_byid('a_nota_bayar', ['id_nota'=>$idnota, 'tujuan_rek'=>$_thisTipe]);
                                                //}
                                                foreach($cekbyr->result() as $val2):
                                                    $tglar[]= $val2->tgl_pemb;
                                                    $jenis[]='Pembayaran';
                                                    $arid[]=$val2->id_pemb;
                                                    $surjal[]="null";
                                                    $konstruksi[] = "null";
                                                    $panjang[] = "null";
                                                    $harga[] = "null";
                                                    $harga_total[] = "null";
                                                    $idnotasr[] = "null";
                                                    $prc_sb[] = "null";
                                                    $prc_ecer[] = "null";
                                                    $panjang_ecer[] = "null";
                                                    $panjang_sb[] = "null";
                                                endforeach;
                                            endforeach;
                                        }
                                    } //if pH ended
                                    asort($tglar);
                                    $sum_panjang = 0;
                                    $sum_harga =0;
                                    $sum_bayar = 0;
                                    $saldo=0;
                                    foreach($tglar as $index => $value){
                                        //echo $index." - ".$jenis[$index]." - ".$arid[$index]." - $value - $idnotasr[$index]<br>";
                                        if($jenis[$index]=="Pembelian"){
                                            $dtqr = $this->data_model->get_byid('a_nota', ['id_nota'=>$arid[$index]]);
                                            $dtqr2 = $this->db->query("SELECT * FROM a_nota WHERE id_nota='$arid[$index]' AND no_sj NOT LIKE '%SLD%'");
                                            $nobukti = "<a href='".base_url('nota/piutang/'.sha1($arid[$index]))."' style='color:#135bd6;text-decoration:none;'>Invoice No.".$dtqr->row("id_nota")."</a>";
                                            if($_thisTipe==""){
                                                $_total_harga = $dtqr->row("total_harga");
                                                $_total_harga2 = $dtqr2->row("total_harga");
                                                $sum_harga = $sum_harga + $_total_harga2;
                                                if(fmod($_total_harga, 1) !== 0.00){
                                                    $debet = "Rp. ".number_format($dtqr->row("total_harga"),2,',','.');
                                                } else {
                                                    $debet = "Rp. ".number_format($dtqr->row("total_harga"),0,',','.');
                                                }
                                            } else {
                                                if($_thisTipe=="SB"){
                                                    $_total_harga1 = $dtqr->row("total_harga");
                                                    $_persenSB = $dtqr->row("pres_sb");
                                                    $_total_harga2 = ($_total_harga1 * $_persenSB) / 100;
                                                    $_total_harga = round($_total_harga2,2);
                                                    //$_total_harga = $dtqr->row("total_harga");
                                                    if(fmod($_total_harga, 1) !== 0.00){
                                                        $debet = "Rp. ".number_format($dtqr->row("total_harga"),2,',','.');
                                                    } else {
                                                        $debet = "Rp. ".number_format($dtqr->row("total_harga"),0,',','.');
                                                    }
                                                    $_2total_harga2 = $dtqr2->row("total_harga");
                                                    $_2persenSB = $dtqr2->row("pres_sb");
                                                    $_22total_harga2 = ($_2total_harga2 * $_2persenSB) / 100;
                                                    $_222total_harga = round($_22total_harga2,2);
                                                    $sum_harga = $sum_harga + $_222total_harga;
                                                }
                                                if($_thisTipe=="ECER"){
                                                    $_total_harga1 = $dtqr->row("total_harga");
                                                    $_persenSB = $dtqr->row("pres_ecer");
                                                    $_total_harga2 = ($_total_harga1 * $_persenSB) / 100;
                                                    $_total_harga = round($_total_harga2,2);
                                                    //$_total_harga = $dtqr->row("total_harga");
                                                    if(fmod($_total_harga, 1) !== 0.00){
                                                        $debet = "Rp. ".number_format($dtqr->row("total_harga"),2,',','.');
                                                    } else {
                                                        $debet = "Rp. ".number_format($dtqr->row("total_harga"),0,',','.');
                                                    }
                                                    $_2total_harga2 = $dtqr2->row("total_harga");
                                                    $_2persenSB = $dtqr2->row("pres_ecer");
                                                    $_22total_harga2 = ($_2total_harga2 * $_2persenSB) / 100;
                                                    $_222total_harga = round($_22total_harga2,2);
                                                    $sum_harga = $sum_harga + $_222total_harga;
                                                }
                                            }
                                              
                                            //$sum_harga = $sum_harga + $dtqr2->row("total_harga"); //BE;LUM
                                            $kredit = "";
                                            $saldo = $saldo + $_total_harga;
                                            $jns = "Penjualan";
                                        } else {
                                            $dtqr = $this->data_model->get_byid('a_nota_bayar', ['id_pemb'=>$arid[$index]]);
                                            //$prc_sb[] = $val->pres_sb;
                                            //$prc_ecer[] = $val->pres_ecer;
                                            // if($_thisTipe==""){
                                            //     $dtqr = $this->data_model->get_byid('a_nota_bayar', ['id_pemb'=>$arid[$index]]);
                                            // } else {
                                            //     $dtqr = $this->data_model->get_byid('a_nota_bayar', ['id_pemb'=>$arid[$index], 'tujuan_rek'=>$_thisTipe]);
                                            //     //$cekbyr = $this->data_model->get_byid('a_nota_bayar', ['id_nota'=>$idnota, 'tujuan_rek'=>$_thisTipe]);
                                            // }
                                            $nobukti = $dtqr->row("nomor_bukti");
                                            $tujuan_rek = $dtqr->row("tujuan_rek");
                                            $debet = "";
                                            if($_thisTipe==""){
                                                $_nominal_pemb = $dtqr->row("nominal_pemb");
                                                if(fmod($dtqr->row("nominal_pemb"), 1) !== 0.00){
                                                    $kredit = "Rp. ".number_format($dtqr->row("nominal_pemb"),2,',','.');
                                                } else {
                                                    $kredit = "Rp. ".number_format($dtqr->row("nominal_pemb"),0,',','.');
                                                }
                                            } else {
                                                if($_thisTipe == "SB"){
                                                    if($tujuan_rek == "SB"){
                                                        $_nominal_pemb = $dtqr->row("nominal_pemb");
                                                        if(fmod($dtqr->row("nominal_pemb"), 1) !== 0.00){
                                                            $kredit = "Rp. ".number_format($dtqr->row("nominal_pemb"),2,',','.');
                                                        } else {
                                                            $kredit = "Rp. ".number_format($dtqr->row("nominal_pemb"),0,',','.');
                                                        }
                                                    } else {
                                                        $kredit = 0;
                                                        $_nominal_pemb = 0;
                                                    }
                                                }
                                                if($_thisTipe == "ECER"){
                                                    if($tujuan_rek == "ECER"){
                                                        $_nominal_pemb = $dtqr->row("nominal_pemb");
                                                        if(fmod($dtqr->row("nominal_pemb"), 1) !== 0.00){
                                                            $kredit = "Rp. ".number_format($dtqr->row("nominal_pemb"),2,',','.');
                                                        } else {
                                                            $kredit = "Rp. ".number_format($dtqr->row("nominal_pemb"),0,',','.');
                                                        }
                                                    } else {
                                                        $kredit = 0;
                                                        $_nominal_pemb = 0;
                                                    }
                                                }
                                            }
                                            $sum_bayar = $sum_bayar + $_nominal_pemb;
                                            $saldo = $saldo - $_nominal_pemb;
                                            $jns = "<a href='".base_url('nota/piutang/'.sha1($dtqr->row('id_nota')))."' style='color:#135bd6;text-decoration:none;'>Pembayaran Invoice No.".$dtqr->row("id_nota")."</a>";
                                        }
                                    ?>
                                    <tr>
                                        <td>
                                            <?php $ek=explode('-',$value); echo $ek[2]." ".$bln[$ek[1]]." ".$ek[0]; ?>
                                        </td>
                                        <!-- <td><=$jns;?></td> -->
                                        <td>
                                        <?php
                                            $rtx = explode('/', $surjal[$index]);
                                            $idcusbysj = $this->data_model->get_byid('surat_jalan',['no_sj'=>$surjal[$index]])->row("id_customer");
                                            $nm_cus = $this->data_model->get_byid('dt_konsumen',['id_konsumen'=>$idcusbysj])->row("nama_konsumen");
                                            if($rtx[0]=="SLD"){
                                                echo "<strong>Saldo Awal</strong>";
                                            } elseif($rtx[0]=="null") {
                                                echo "<font style='color:red;'>$nobukti</font>";
                                            } else {
                                                echo "<a href='#' data-toggle='tooltip' data-placement='top' title='$nm_cus'>J".$rtx[3]."".$rtx[0]."</a>";
                                            }
                                        ?>
                                        </td>
                                        <td><?=$idnotasr[$index]=='null' ? '':$idnotasr[$index];?></td>
                                        <td><?=$konstruksi[$index]=="null" ? '':$konstruksi[$index];?></td>
                                        <td>
                                        <?php if($panjang[$index]=="null" || $panjang[$index]==0){} else {
                                            if($_thisTipe==""){
                                                $sum_panjang = $sum_panjang + $panjang[$index];
                                                if(fmod($panjang[$index], 1) !== 0.00){
                                                    echo "".number_format($panjang[$index],2,',','.');
                                                } else {
                                                    echo "".number_format($panjang[$index],0,',','.');
                                                }
                                            } else {
                                                if($_thisTipe=="SB"){
                                                    $sum_panjang = $sum_panjang + $panjang_sb[$index];
                                                    if(fmod($panjang_sb[$index], 1) !== 0.00){
                                                        echo "".number_format($panjang_sb[$index],2,',','.');
                                                    } else {
                                                        echo "".number_format($panjang_sb[$index],0,',','.');
                                                    }
                                                }
                                                if($_thisTipe=="ECER"){
                                                    $sum_panjang = $sum_panjang + $panjang_ecer[$index];
                                                    if(fmod($panjang_ecer[$index], 1) !== 0.00){
                                                        echo "".number_format($panjang_ecer[$index],2,',','.');
                                                    } else {
                                                        echo "".number_format($panjang_ecer[$index],0,',','.');
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                        </td>
                                        <td>
                                        <?php 
                                            
                                            if($harga[$index]=="null" || $harga[$index]==0){} else {
                                            if(fmod($harga[$index], 1) !== 0.00){
                                                echo "Rp. ".number_format($harga[$index],2,',','.');
                                            } else {
                                                echo "Rp. ".number_format($harga[$index],0,',','.');
                                            }
                                        }
                                        ?>
                                        </td>
                                        <td>
                                            <?php 
                                            if($_thisTipe==""){
                                                if($rtx[0]=="SLD"){ echo ""; } else { echo $debet; } 
                                            } else {
                                                if($_thisTipe=="SB"){
                                                    $debet_1 = $harga[$index] * $panjang_sb[$index];
                                                    $debet_1 = round($debet_1,2);
                                                    if($debet_1 > 0){
                                                    if(fmod($debet_1, 1) !== 0.00){
                                                        echo "Rp. ".number_format($debet_1,2,',','.');
                                                    } else {
                                                        echo "Rp. ".number_format($debet_1,0,',','.');
                                                    }
                                                    } 
                                                }
                                                if($_thisTipe=="ECER"){
                                                    $debet_1 = $harga[$index] * $panjang_ecer[$index];
                                                    $debet_1 = round($debet_1,2);
                                                    if($debet_1 > 0){
                                                    if(fmod($debet_1, 1) !== 0.00){
                                                        echo "Rp. ".number_format($debet_1,2,',','.');
                                                    } else {
                                                        echo "Rp. ".number_format($debet_1,0,',','.');
                                                    }
                                                    }
                                                }
                                            }
                                            
                                            ?>
                                        </td>
                                        <td><?=$kredit;?></td>
                                        <td>
                                        <?php
                                            if(fmod($saldo, 1) !== 0.00){
                                                echo "Rp. ".number_format($saldo,2,',','.');
                                            } else {
                                                echo "Rp. ".number_format($saldo,0,',','.');
                                            }
                                        
                                        ?></td>
                                    </tr>
                                    <?php } ?>
                                    <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                                    <tr>
                                        <td colspan="4"><strong>TOTAL</strong></td>
                                        <td><?php
                                          if(fmod($sum_panjang, 1) !== 0.00){
                                            echo "".number_format($sum_panjang,2,',','.');
                                          } else {
                                            echo "".number_format($sum_panjang,0,',','.');
                                          } ?>
                                        </td>
                                        <td>&nbsp;</td>
                                        <td>
                                            <?php
                                              if(fmod($sum_harga, 1) !== 0.00){
                                                echo  "Rp. ".number_format($sum_harga,2,',','.');
                                              } else {
                                                echo "Rp. ".number_format($sum_harga,0,',','.');
                                              }
                                              ?>
                                        </td>
                                        <td>
                                            <?php
                                            if(fmod($sum_bayar, 1) !== 0.00){
                                                echo "Rp. ".number_format($sum_bayar,2,',','.');
                                              } else {
                                                echo "Rp. ".number_format($sum_bayar,0,',','.');
                                              }
                                              ?>
                                        </td>
                                        <td><?php
                                            if(fmod($saldo, 1) !== 0.00){
                                                echo "Rp. ".number_format($saldo,2,',','.');
                                            } else {
                                                echo "Rp. ".number_format($saldo,0,',','.');
                                            }
                                        
                                        ?></td>
                                    </tr>
								</tbody>
							</table>
                            <?php } ?>
						</div>
					</div>
					<!-- Simple Datatable End -->
                    
					
        </div>
    </div>
</div>