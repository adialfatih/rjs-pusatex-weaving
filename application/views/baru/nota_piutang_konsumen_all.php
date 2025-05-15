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
                            <?php } else { 
                            $cekCusId = $this->db->query("SELECT * FROM ab_cuspriority WHERE idcus='$idcus'");
                            if($cekCusId->num_rows() == 1){
                                //echo $idcus;
                                $inisial  = $cekCusId->row("awalan");
                                $allID    = $this->db->query("SELECT id_konsumen,nama_konsumen FROM dt_konsumen WHERE nama_konsumen LIKE '$inisial%' OR id_konsumen='$idcus'");
                                $arrID = array();
                                foreach($allID->result() as $vl){
                                    $thisID = "'".$vl->id_konsumen."'";
                                    $arrID[]= $thisID;
                                }
                                $arrIM = implode(",", $arrID);
                                $qry   = "SELECT *  FROM `view_nota2` WHERE `id_customer` IN (".$arrIM.")";
                                $showNota = $this->db->query($qry);
                                $showBayar = $this->db->query("SELECT * FROM `a_nota_bayar2` WHERE id_cus IN (".$arrIM.")");
                            } else {
                                //echo $idcus;
                                $qry   = "SELECT *  FROM `view_nota2` WHERE `id_customer` = '$idcus' ";
                                $showNota = $this->db->query($qry);
                                $showBayar = $this->db->query("SELECT * FROM `a_nota_bayar2` WHERE id_cus = '$idcus'");
                            }
                            $allNota = [];
                            foreach($showNota->result() as $val){
                                $allNota[] = [
                                    "tipe" => "nota",
                                    "notayes" => 1,
                                    "idnota" => $val->id_nota,
                                    "nosj" => $val->no_sj,
                                    "idcustomer" => $val->id_customer,
                                    "nmcus" => $val->nama_konsumen,
                                    "kd" => $val->kd,
                                    "jnsfold" => $val->jns_fold,
                                    "konstruksi" => $val->konstruksi,
                                    "jmlroll" => $val->jml_roll,
                                    "totalpanjang" => $val->total_panjang,
                                    "hargasatuan" => $val->harga_satuan,
                                    "totalharga" => $val->total_harga,
                                    "tglnota" => $val->tgl_nota,
                                    "bayar" => 0,
                                    "saldo" => 0,
                                    "nomorbukti" => "null",
                                    "tujuan_rek"=> "null"
                                ];
                            }
                            foreach($showBayar->result() as $byr){
                                $allNota[] = [
                                    "tipe" => "bayar",
                                    "notayes" => 0,
                                    "idnota" => 0,
                                    "nosj" => 0,
                                    "idcustomer" => $byr->id_cus,
                                    "nmcus" => "null",
                                    "kd" => "null",
                                    "jnsfold" => "null",
                                    "konstruksi" => "null",
                                    "jmlroll" => "null",
                                    "totalpanjang" => 0,
                                    "hargasatuan" => "null",
                                    "totalharga" => 0,
                                    "tglnota" => $byr->tgl_pemb,
                                    "bayar" => $byr->nominal_pemb,
                                    "saldo" => 0,
                                    "nomorbukti" => $byr->nomor_bukti,
                                    "tujuan_rek"=> $byr->tujuan_rek
                                ];
                            }
                            // usort($allNota, function($a, $b) {
                            //     return strtotime($a['tglnota']) - strtotime($b['tglnota']);
                            // });
                            usort($allNota, function($a, $b) {
                                $tanggalA = strtotime($a['tglnota']);
                                $tanggalB = strtotime($b['tglnota']);

                                // 1. Urutkan berdasarkan tanggal (asc)
                                if ($tanggalA != $tanggalB) {
                                    return $tanggalA - $tanggalB;
                                }

                                // 2. Jika tanggal sama, tampilkan 'nota' dulu baru 'bayar'
                                // Nilai 'nota' dianggap lebih kecil dari 'bayar'
                                $prioritas = ['nota' => 0, 'bayar' => 1];
                                $tipeA = isset($a['tipe']) ? $a['tipe'] : 'bayar'; // default ke bayar jika tidak ada
                                $tipeB = isset($b['tipe']) ? $b['tipe'] : 'bayar';

                                return $prioritas[$tipeA] - $prioritas[$tipeB];
                            });
                            //$thisTotalPanjang = SUM($allNota['totalpanjang']);
                            $thisTotalPanjang = array_reduce($allNota, function($carry, $item) {
                                if (isset($item['tipe']) && $item['tipe'] === 'nota') {
                                    $carry += floatval($item['totalpanjang']);
                                }
                                return $carry;
                            }, 0);
                            $thisTotalHarga = array_reduce($allNota, function($carry, $item) {
                                if (isset($item['tipe']) && $item['tipe'] === 'nota') {
                                    $carry += floatval($item['totalharga']);
                                }
                                return $carry;
                            }, 0);
                            $thisTotalBayar = array_reduce($allNota, function($carry, $item) {
                                if (isset($item['tipe']) && $item['tipe'] === 'bayar') {
                                    $carry += floatval($item['bayar']);
                                }
                                return $carry;
                            }, 0);
                            $thisTotalnotaYes = array_reduce($allNota, function($carry, $item) {
                                if (isset($item['tipe']) && $item['tipe'] === 'nota') {
                                    $carry += floatval($item['notayes']);
                                }
                                return $carry;
                            }, 0);
                            ?>
                            <div class="table-responsive">
							<table class="table table-bordered stripe hover nowrap">
								<thead>
									<tr>
										<th class="table-plus datatable-nosort" style="text-align:center;">TUJUAN</th>
										<th style="text-align:center;">TANGGAL</th>
										<th style="text-align:center;">KONSTRUKSI</th>
										<th style="text-align:center;">NO SJ</th>
										<th style="text-align:center;">NO NOTA</th>
										<th style="text-align:center;">QTY</th>
										<th style="text-align:center;">PANJANG EP</th>
										<th style="text-align:center;">HARGA</th>
                                        <th style="text-align:center;">PPN</th>
                                        <th style="text-align:center;">JUMLAH EP+PPN</th>
                                        <th style="text-align:center;">BAYAR</th>
                                        <th style="text-align:center;">SALDO</th>
									</tr>
								</thead>
								<tbody>
                                    <?php 
                                    $allsaldo = 0; $allBayar = 0;
                                    foreach ($allNota as $nota):
                                    if($nota['totalpanjang'] == floor($nota['totalpanjang'])){
                                        $totalPanjang = number_format($nota['totalpanjang'],0,',','.');
                                    } else {
                                        $totalPanjang = number_format($nota['totalpanjang'],2,',','.');
                                    }
                                    if($nota['hargasatuan'] == "null"){ $hargasatuan =""; } else { 
                                    if($nota['hargasatuan'] == floor($nota['hargasatuan'])){
                                        $hargasatuan = "Rp ".number_format($nota['hargasatuan'],0,',','.');
                                    } else {
                                        $hargasatuan = "Rp ".number_format($nota['hargasatuan'],2,',','.');
                                    } }
                                    if($nota['totalharga'] == "null"){ $totalharga =""; } else {
                                    if($nota['totalharga'] == floor($nota['totalharga'])){
                                        $totalharga = "Rp ".number_format($nota['totalharga'],0,',','.');
                                    } else {
                                        $totalharga = "Rp ".number_format($nota['totalharga'],2,',','.');
                                    } }
                                    if($nota['bayar'] > 0){
                                        if($nota['bayar'] == floor($nota['bayar'])){
                                            $notabayar = "Rp ".number_format($nota['bayar'],0,',','.');
                                        } else {
                                            $notabayar = "Rp ".number_format($nota['bayar'],2,',','.');
                                        }
                                    } else {
                                        $notabayar = "";
                                    }
                                    if($nota['saldo'] > 0){
                                        if($nota['saldo'] == floor($nota['saldo'])){
                                            $saldonota = "Rp ".number_format($nota['saldo'],0,',','.');
                                        } else {
                                            $saldonota = "Rp ".number_format($nota['saldo'],2,',','.');
                                        }
                                    } else {
                                        $saldonota = "";
                                    }
                                    if($nota['tipe']=="nota"){
                                        $allsaldo = $allsaldo + $nota['totalharga'];
                                        $thisBayar = 0;
                                        $showAkhirSaldo = $allsaldo - $thisBayar;
                                        if($showAkhirSaldo == floor($showAkhirSaldo)){
                                            $formatSaldo = "Rp. ".number_format($showAkhirSaldo,0,",",".");
                                        } else {
                                            $formatSaldo = "Rp. ".number_format($showAkhirSaldo,2,",",".");
                                        }
                                        
                                    } else {
                                        $thisSaldo = $allsaldo;
                                        $thisBayar = $nota['bayar'];
                                        $showAkhirSaldo = $allsaldo - $thisBayar;
                                        if($showAkhirSaldo == floor($showAkhirSaldo)){
                                            $formatSaldo = "Rp. ".number_format($showAkhirSaldo,0,",",".");
                                        } else {
                                            $formatSaldo = "Rp. ".number_format($showAkhirSaldo,2,",",".");
                                        }
                                        $allsaldo = $showAkhirSaldo;
                                    }
                                    ?>
                                    <tr>
                                        <td><?=$nota['nmcus']=="null" ? "":$nota['nmcus'];?></td>
                                        <td><?=date('d/m/y',strtotime($nota['tglnota']));?></td>
                                        <td><?=$nota['konstruksi']=="null" ? $nota['nomorbukti'] : $nota['konstruksi'];?></td>
                                        <td><?=$nota['nosj']=="0" ? "":$nota['nosj'];?></td>
                                        <td><?=$nota['idnota']=="0" ? "":$nota['idnota'];?></td>
                                        <td><?=$totalPanjang==0 ? "":$totalPanjang;?></td>
                                        <td><?=$totalPanjang==0 ? "":$totalPanjang;?></td>
                                        <td><?=$hargasatuan;?></td>
                                        <td>-</td>
                                        <td><?=$totalharga;?></td>
                                        <td style="color:red;"><?=$notabayar;?></td>
                                        <td><?=$formatSaldo;?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td colspan="12"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" style="font-weight:bold;">Total</td>
                                        <td style="font-weight:bold;"><?=number_format($thisTotalPanjang,0,",",".");?></td>
                                        <td style="font-weight:bold;"><?=number_format($thisTotalPanjang,0,",",".");?></td>
                                        <td></td>
                                        <td></td>
                                        <td style="font-weight:bold;">Rp. <?=number_format($thisTotalHarga,0,",",".");?></td>
                                        <td style="color:red;font-weight:bold;">Rp. <?=number_format($thisTotalBayar,0,",",".");?></td>
                                        <td style="font-weight:bold;"><?=$formatSaldo;?></td>
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                            <?php } ?>
						</div>
					</div>
					<!-- Simple Datatable End -->
                    
					
        </div>
    </div>
</div>