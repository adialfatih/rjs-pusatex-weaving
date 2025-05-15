<?php $bln = [ '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des', ]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Nota Konsumen</h4>
                                    <small>Menampilkan semua nota dari konsumen atas nama <strong><?=$customer['nama_konsumen'];?></strong></small>
								</div>
							</div>
							
						</div>
					</div>
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
                            <table class="table">
                                <tr>
                                    <td style="width:200px;">Nama Konsumen</td>
                                    <th><?=$customer['nama_konsumen'];?></th>
                                </tr>
                                <tr>
                                    <td>Total Piutang</td>
                                    <th id="totalpiutang">0</th>
                                </tr>
                                <tr>
                                    <td>Kartu Piutang</td>
                                    <th>
                                        <a href="<?=base_url('nota/piutangall/'.sha1($customer['id_konsumen']).'/0');?>">
                                            <span style="background:#e6122f; padding:5px; border-radius:4px; font-size:10px;cursor:pointer;color:#ffffff;">Kartu Piutang Total</span>
                                        </a>
                                        <a href="<?=base_url('nota/piutangall/'.sha1($customer['id_konsumen']).'/1');?>">
                                            <span style="background:#4287f5; padding:5px; border-radius:4px; font-size:10px;cursor:pointer;color:#ffffff;">Kartu Piutang SB</span>
                                        </a>
                                        <a href="<?=base_url('nota/piutangall/'.sha1($customer['id_konsumen']).'/2');?>">
                                            <span style="background:#de8d04; padding:5px; border-radius:4px; font-size:10px;cursor:pointer;color:#ffffff;">Kartu Piutang Ecer</span>
                                        </a>
                                    </th>
                                </tr>
                            </table>
							<table class="data-table table stripe hover nowrap">
								<thead>
									<tr>
										<th class="table-plus datatable-nosort">No</th>
                                        <th>Nota</th>
										<th>Customer</th>
										<th>Tanggal</th>
										<th>Sub Total SB</th>
										<th>Sub Total Ecer</th>
                                        <!-- <th>Pembayaran SB</th> -->
										<th>Sisa Pembayaran SB</th>
                                        <!-- <th>Pembayaran Ecer</th> -->
										<th>Sisa Pembayaran Ecer</th>
                                        <th></th>
                                        <th>Action</th>
									</tr>
								</thead>
								<tbody>
							<?php
                            $ar_data1 = array();
                            $ar_data2 = array();

                            $ar_data3 = array();
                            $ar_data31 = array();
                            $ar_data32 = array();

                            $ar_data4 = array();
                            $ar_data41 = array();
                            $ar_data42 = array();

                            $ar_data5 = array();
                            $ar_data51 = array();
                            $ar_data52 = array();
                                $idcus = $customer['id_konsumen'];
                                $qr_nota = $this->db->query("SELECT a_nota.id_nota,a_nota.no_sj,a_nota.total_harga,a_nota.tgl_nota,a_nota.pres_sb,a_nota.pres_ecer,surat_jalan.no_sj,surat_jalan.id_customer FROM a_nota,surat_jalan WHERE a_nota.no_sj = surat_jalan.no_sj AND surat_jalan.id_customer='$idcus'");
                                if($idcus==100){
                                    $allIdKm = $this->db->query("SELECT * FROM dt_konsumen WHERE nama_konsumen LIKE 'KM%'");
                                    
                                    foreach ($allIdKm->result() as $key => $value) {
                                        $newid = $value->id_konsumen;
                                        $qr_nota1 = $this->db->query("SELECT a_nota.id_nota,a_nota.no_sj,a_nota.total_harga,a_nota.tgl_nota,a_nota.pres_sb,a_nota.pres_ecer,surat_jalan.no_sj,surat_jalan.id_customer FROM a_nota,surat_jalan WHERE a_nota.no_sj = surat_jalan.no_sj AND surat_jalan.id_customer='$newid'");
                                        
                                        foreach($qr_nota1->result() as $n => $val):
                                            $idnota = $val->id_nota;
                                            $totalid = $totalid."".$idnota.",";
                                            $ar_data1[]=$idnota;
                                            $ar_data2[]=$val->tgl_nota;
                                            $_xTotalHarga = $val->total_harga;
                                            $_xSB = (floatval($_xTotalHarga) * floatval($val->pres_sb)) / 100;
                                            $_xECER = (floatval($_xTotalHarga) * floatval($val->pres_ecer)) / 100;
                                            $ar_data3[] = $_xTotalHarga;
                                            $ar_data31[] = $_xSB;
                                            $ar_data32[] = $_xECER;
                                            $cek_bayar = $this->data_model->get_byid('a_nota_bayar',['id_nota'=>$idnota]);
                                            $cek_bayarSB = $this->data_model->get_byid('a_nota_bayar',['id_nota'=>$idnota,'tujuan_rek'=>'SB']);
                                            $cek_bayarEcer = $this->data_model->get_byid('a_nota_bayar',['id_nota'=>$idnota,'tujuan_rek'=>'ECER']);
                                            if($cek_bayar->num_rows() == 0){
                                                //echo "<td>0</td>";
                                                //echo "<td>Rp. ".$ttl."</td>";
                                                $kurangan = $val->total_harga;
                                                $ar_data4[] = "0";
                                                $ar_data5[] = $val->total_harga;
                                            } else {
                                                $cek_bayar2 = $this->db->query("SELECT SUM(nominal_pemb) as nilai FROM a_nota_bayar WHERE id_nota='$idnota'")->row("nilai");
                                                //echo "Rp. ".number_format($cek_bayar2,0,',','.');
                                                $kurangan = $val->total_harga - $cek_bayar2;
                                                //echo "<td>Rp. ".number_format($cek_bayar2,0,',','.')."</td>";
                                                //echo "<td>Rp. ".number_format($kurangan,0,',','.')."</td>";
                                                $ar_data4[] = $cek_bayar2;
                                                $ar_data5[] = $kurangan;
                                            }
                                            if($cek_bayarSB->num_rows() == 0){
                                                //echo "<td>0</td>";
                                                //echo "<td>Rp. ".$ttl."</td>";
                                                $kurangan1 = $val->total_harga;
                                                $ar_data41[] = "0";
                                                $ar_data51[] = $val->total_harga;
                                            } else {
                                                $cek_bayar2 = $this->db->query("SELECT SUM(nominal_pemb) as nilai FROM a_nota_bayar WHERE id_nota='$idnota' AND tujuan_rek='SB'")->row("nilai");
                                                //echo "Rp. ".number_format($cek_bayar2,0,',','.');
                                                $kurangan1 = $val->total_harga - $cek_bayar2;
                                                //echo "<td>Rp. ".number_format($cek_bayar2,0,',','.')."</td>";
                                                //echo "<td>Rp. ".number_format($kurangan,0,',','.')."</td>";
                                                $ar_data41[] = $cek_bayar2;
                                                $ar_data51[] = $kurangan1;
                                            }
                                            if($cek_bayarEcer->num_rows() == 0){
                                                //echo "<td>0</td>";
                                                //echo "<td>Rp. ".$ttl."</td>";
                                                $kurangan2 = $val->total_harga;
                                                $ar_data42[] = "0";
                                                $ar_data52[] = $val->total_harga;
                                            } else {
                                                $cek_bayar2 = $this->db->query("SELECT SUM(nominal_pemb) as nilai FROM a_nota_bayar WHERE id_nota='$idnota' AND tujuan_rek='ECER'")->row("nilai");
                                                //echo "Rp. ".number_format($cek_bayar2,0,',','.');
                                                $kurangan2 = $val->total_harga - $cek_bayar2;
                                                //echo "<td>Rp. ".number_format($cek_bayar2,0,',','.')."</td>";
                                                //echo "<td>Rp. ".number_format($kurangan,0,',','.')."</td>";
                                                $ar_data41[] = $cek_bayar2;
                                                $ar_data51[] = $kurangan2;
                                            }
                                        endforeach;
                                    }
                                } //end KM
                                if($idcus==29){
                                    $allIdKm = $this->db->query("SELECT * FROM dt_konsumen WHERE nama_konsumen LIKE 'PS%'");
                                    foreach ($allIdKm->result() as $key => $value) {
                                        $newid = $value->id_konsumen;
                                        $qr_nota1 = $this->db->query("SELECT a_nota.id_nota,a_nota.no_sj,a_nota.total_harga,a_nota.tgl_nota,a_nota.pres_sb,a_nota.pres_ecer,surat_jalan.no_sj,surat_jalan.id_customer FROM a_nota,surat_jalan WHERE a_nota.no_sj = surat_jalan.no_sj AND surat_jalan.id_customer='$newid'");
                                        
                                        foreach($qr_nota1->result() as $n => $val):
                                            $idnota = $val->id_nota;
                                            $totalid = $totalid."".$idnota.",";
                                            $ar_data1[]=$idnota;
                                            $ar_data2[]=$val->tgl_nota;
                                            $_xTotalHarga = $val->total_harga;
                                            $_xSB = (floatval($_xTotalHarga) * floatval($val->pres_sb)) / 100;
                                            $_xECER = (floatval($_xTotalHarga) * floatval($val->pres_ecer)) / 100;
                                            $ar_data3[] = $_xTotalHarga;
                                            $ar_data31[] = $_xSB;
                                            $ar_data32[] = $_xECER;
                                            $cek_bayar = $this->data_model->get_byid('a_nota_bayar',['id_nota'=>$idnota]);
                                            $cek_bayarSB = $this->data_model->get_byid('a_nota_bayar',['id_nota'=>$idnota,'tujuan_rek'=>'SB']);
                                            $cek_bayarEcer = $this->data_model->get_byid('a_nota_bayar',['id_nota'=>$idnota,'tujuan_rek'=>'ECER']);
                                            if($cek_bayar->num_rows() == 0){
                                                //echo "<td>0</td>";
                                                //echo "<td>Rp. ".$ttl."</td>";
                                                $kurangan = $val->total_harga;
                                                $ar_data4[] = "0";
                                                $ar_data5[] = $val->total_harga;
                                            } else {
                                                $cek_bayar2 = $this->db->query("SELECT SUM(nominal_pemb) as nilai FROM a_nota_bayar WHERE id_nota='$idnota'")->row("nilai");
                                                //echo "Rp. ".number_format($cek_bayar2,0,',','.');
                                                $kurangan = $val->total_harga - $cek_bayar2;
                                                //echo "<td>Rp. ".number_format($cek_bayar2,0,',','.')."</td>";
                                                //echo "<td>Rp. ".number_format($kurangan,0,',','.')."</td>";
                                                $ar_data4[] = $cek_bayar2;
                                                $ar_data5[] = $kurangan;
                                            }
                                            if($cek_bayarSB->num_rows() == 0){
                                                //echo "<td>0</td>";
                                                //echo "<td>Rp. ".$ttl."</td>";
                                                $kurangan1 = $val->total_harga;
                                                $ar_data41[] = "0";
                                                $ar_data51[] = $val->total_harga;
                                            } else {
                                                $cek_bayar2 = $this->db->query("SELECT SUM(nominal_pemb) as nilai FROM a_nota_bayar WHERE id_nota='$idnota' AND tujuan_rek='SB'")->row("nilai");
                                                //echo "Rp. ".number_format($cek_bayar2,0,',','.');
                                                $kurangan1 = $val->total_harga - $cek_bayar2;
                                                //echo "<td>Rp. ".number_format($cek_bayar2,0,',','.')."</td>";
                                                //echo "<td>Rp. ".number_format($kurangan,0,',','.')."</td>";
                                                $ar_data41[] = $cek_bayar2;
                                                $ar_data51[] = $kurangan1;
                                            }
                                            if($cek_bayarEcer->num_rows() == 0){
                                                //echo "<td>0</td>";
                                                //echo "<td>Rp. ".$ttl."</td>";
                                                $kurangan2 = $val->total_harga;
                                                $ar_data42[] = "0";
                                                $ar_data52[] = $val->total_harga;
                                            } else {
                                                $cek_bayar2 = $this->db->query("SELECT SUM(nominal_pemb) as nilai FROM a_nota_bayar WHERE id_nota='$idnota' AND tujuan_rek='ECER'")->row("nilai");
                                                //echo "Rp. ".number_format($cek_bayar2,0,',','.');
                                                $kurangan2 = $val->total_harga - $cek_bayar2;
                                                //echo "<td>Rp. ".number_format($cek_bayar2,0,',','.')."</td>";
                                                //echo "<td>Rp. ".number_format($kurangan,0,',','.')."</td>";
                                                $ar_data41[] = $cek_bayar2;
                                                $ar_data51[] = $kurangan2;
                                            }
                                        endforeach;
                                    }

                                } //end PS
                                if($idcus==101){
                                    $allIdKm = $this->db->query("SELECT * FROM dt_konsumen WHERE nama_konsumen LIKE 'PB%'");
                                    foreach ($allIdKm->result() as $key => $value) {
                                        $newid = $value->id_konsumen;
                                        $qr_nota1 = $this->db->query("SELECT a_nota.id_nota,a_nota.no_sj,a_nota.total_harga,a_nota.tgl_nota,a_nota.pres_sb,a_nota.pres_ecer,surat_jalan.no_sj,surat_jalan.id_customer FROM a_nota,surat_jalan WHERE a_nota.no_sj = surat_jalan.no_sj AND surat_jalan.id_customer='$newid'");
                                        
                                        foreach($qr_nota1->result() as $n => $val):
                                            $idnota = $val->id_nota;
                                            $totalid = $totalid."".$idnota.",";
                                            $ar_data1[]=$idnota;
                                            $ar_data2[]=$val->tgl_nota;
                                            $_xTotalHarga = $val->total_harga;
                                            $_xSB = (floatval($_xTotalHarga) * floatval($val->pres_sb)) / 100;
                                            $_xECER = (floatval($_xTotalHarga) * floatval($val->pres_ecer)) / 100;
                                            $ar_data3[] = $_xTotalHarga;
                                            $ar_data31[] = $_xSB;
                                            $ar_data32[] = $_xECER;
                                            $cek_bayar = $this->data_model->get_byid('a_nota_bayar',['id_nota'=>$idnota]);
                                            $cek_bayarSB = $this->data_model->get_byid('a_nota_bayar',['id_nota'=>$idnota,'tujuan_rek'=>'SB']);
                                            $cek_bayarEcer = $this->data_model->get_byid('a_nota_bayar',['id_nota'=>$idnota,'tujuan_rek'=>'ECER']);
                                            if($cek_bayar->num_rows() == 0){
                                                //echo "<td>0</td>";
                                                //echo "<td>Rp. ".$ttl."</td>";
                                                $kurangan = $val->total_harga;
                                                $ar_data4[] = "0";
                                                $ar_data5[] = $val->total_harga;
                                            } else {
                                                $cek_bayar2 = $this->db->query("SELECT SUM(nominal_pemb) as nilai FROM a_nota_bayar WHERE id_nota='$idnota'")->row("nilai");
                                                //echo "Rp. ".number_format($cek_bayar2,0,',','.');
                                                $kurangan = $val->total_harga - $cek_bayar2;
                                                //echo "<td>Rp. ".number_format($cek_bayar2,0,',','.')."</td>";
                                                //echo "<td>Rp. ".number_format($kurangan,0,',','.')."</td>";
                                                $ar_data4[] = $cek_bayar2;
                                                $ar_data5[] = $kurangan;
                                            }
                                            if($cek_bayarSB->num_rows() == 0){
                                                //echo "<td>0</td>";
                                                //echo "<td>Rp. ".$ttl."</td>";
                                                $kurangan1 = $val->total_harga;
                                                $ar_data41[] = "0";
                                                $ar_data51[] = $val->total_harga;
                                            } else {
                                                $cek_bayar2 = $this->db->query("SELECT SUM(nominal_pemb) as nilai FROM a_nota_bayar WHERE id_nota='$idnota' AND tujuan_rek='SB'")->row("nilai");
                                                //echo "Rp. ".number_format($cek_bayar2,0,',','.');
                                                $kurangan1 = $val->total_harga - $cek_bayar2;
                                                //echo "<td>Rp. ".number_format($cek_bayar2,0,',','.')."</td>";
                                                //echo "<td>Rp. ".number_format($kurangan,0,',','.')."</td>";
                                                $ar_data41[] = $cek_bayar2;
                                                $ar_data51[] = $kurangan1;
                                            }
                                            if($cek_bayarEcer->num_rows() == 0){
                                                //echo "<td>0</td>";
                                                //echo "<td>Rp. ".$ttl."</td>";
                                                $kurangan2 = $val->total_harga;
                                                $ar_data42[] = "0";
                                                $ar_data52[] = $val->total_harga;
                                            } else {
                                                $cek_bayar2 = $this->db->query("SELECT SUM(nominal_pemb) as nilai FROM a_nota_bayar WHERE id_nota='$idnota' AND tujuan_rek='ECER'")->row("nilai");
                                                //echo "Rp. ".number_format($cek_bayar2,0,',','.');
                                                $kurangan2 = $val->total_harga - $cek_bayar2;
                                                //echo "<td>Rp. ".number_format($cek_bayar2,0,',','.')."</td>";
                                                //echo "<td>Rp. ".number_format($kurangan,0,',','.')."</td>";
                                                $ar_data41[] = $cek_bayar2;
                                                $ar_data51[] = $kurangan2;
                                            }
                                        endforeach;
                                    }

                                } //end PB
                                if($idcus==235){
                                    $allIdKm = $this->db->query("SELECT * FROM dt_konsumen WHERE nama_konsumen LIKE 'PH%' OR nama_konsumen='PUTRI HANAN'");
                                    foreach ($allIdKm->result() as $key => $value) {
                                        $newid = $value->id_konsumen;
                                        $qr_nota1 = $this->db->query("SELECT a_nota.id_nota,a_nota.no_sj,a_nota.total_harga,a_nota.tgl_nota,a_nota.pres_sb,a_nota.pres_ecer,surat_jalan.no_sj,surat_jalan.id_customer FROM a_nota,surat_jalan WHERE a_nota.no_sj = surat_jalan.no_sj AND surat_jalan.id_customer='$newid'");
                                        
                                        foreach($qr_nota1->result() as $n => $val):
                                            $idnota = $val->id_nota;
                                            $totalid = $totalid."".$idnota.",";
                                            $ar_data1[]=$idnota;
                                            $ar_data2[]=$val->tgl_nota;
                                            $_xTotalHarga = $val->total_harga;
                                            $_xSB = (floatval($_xTotalHarga) * floatval($val->pres_sb)) / 100;
                                            $_xECER = (floatval($_xTotalHarga) * floatval($val->pres_ecer)) / 100;
                                            $ar_data3[] = $_xTotalHarga;
                                            $ar_data31[] = $_xSB;
                                            $ar_data32[] = $_xECER;
                                            $cek_bayar = $this->data_model->get_byid('a_nota_bayar',['id_nota'=>$idnota]);
                                            $cek_bayarSB = $this->data_model->get_byid('a_nota_bayar',['id_nota'=>$idnota,'tujuan_rek'=>'SB']);
                                            $cek_bayarEcer = $this->data_model->get_byid('a_nota_bayar',['id_nota'=>$idnota,'tujuan_rek'=>'ECER']);
                                            if($cek_bayar->num_rows() == 0){
                                                //echo "<td>0</td>";
                                                //echo "<td>Rp. ".$ttl."</td>";
                                                $kurangan = $val->total_harga;
                                                $ar_data4[] = "0";
                                                $ar_data5[] = $val->total_harga;
                                            } else {
                                                $cek_bayar2 = $this->db->query("SELECT SUM(nominal_pemb) as nilai FROM a_nota_bayar WHERE id_nota='$idnota'")->row("nilai");
                                                //echo "Rp. ".number_format($cek_bayar2,0,',','.');
                                                $kurangan = $val->total_harga - $cek_bayar2;
                                                //echo "<td>Rp. ".number_format($cek_bayar2,0,',','.')."</td>";
                                                //echo "<td>Rp. ".number_format($kurangan,0,',','.')."</td>";
                                                $ar_data4[] = $cek_bayar2;
                                                $ar_data5[] = $kurangan;
                                            }
                                            if($cek_bayarSB->num_rows() == 0){
                                                //echo "<td>0</td>";
                                                //echo "<td>Rp. ".$ttl."</td>";
                                                $kurangan1 = $val->total_harga;
                                                $ar_data41[] = "0";
                                                $ar_data51[] = $val->total_harga;
                                            } else {
                                                $cek_bayar2 = $this->db->query("SELECT SUM(nominal_pemb) as nilai FROM a_nota_bayar WHERE id_nota='$idnota' AND tujuan_rek='SB'")->row("nilai");
                                                //echo "Rp. ".number_format($cek_bayar2,0,',','.');
                                                $kurangan1 = $val->total_harga - $cek_bayar2;
                                                //echo "<td>Rp. ".number_format($cek_bayar2,0,',','.')."</td>";
                                                //echo "<td>Rp. ".number_format($kurangan,0,',','.')."</td>";
                                                $ar_data41[] = $cek_bayar2;
                                                $ar_data51[] = $kurangan1;
                                            }
                                            if($cek_bayarEcer->num_rows() == 0){
                                                //echo "<td>0</td>";
                                                //echo "<td>Rp. ".$ttl."</td>";
                                                $kurangan2 = $val->total_harga;
                                                $ar_data42[] = "0";
                                                $ar_data52[] = $val->total_harga;
                                            } else {
                                                $cek_bayar2 = $this->db->query("SELECT SUM(nominal_pemb) as nilai FROM a_nota_bayar WHERE id_nota='$idnota' AND tujuan_rek='ECER'")->row("nilai");
                                                //echo "Rp. ".number_format($cek_bayar2,0,',','.');
                                                $kurangan2 = $val->total_harga - $cek_bayar2;
                                                //echo "<td>Rp. ".number_format($cek_bayar2,0,',','.')."</td>";
                                                //echo "<td>Rp. ".number_format($kurangan,0,',','.')."</td>";
                                                $ar_data41[] = $cek_bayar2;
                                                $ar_data51[] = $kurangan2;
                                            }
                                        endforeach;
                                    }

                                } //end PH
                                
                               // asort($ar_data2);
                                foreach($qr_nota->result() as $n => $val){
                                    $idnota = $val->id_nota;
                                    $totalid = $totalid."".$idnota.",";
                                    $ar_data1[]=$idnota;
                                    $ar_data2[]=$val->tgl_nota;
                                    //$ar_data3[] = $val->total_harga;
                                    $_xTotalHarga = $val->total_harga;
                                            $_xSB = (floatval($_xTotalHarga) * floatval($val->pres_sb)) / 100;
                                            $_xECER = (floatval($_xTotalHarga) * floatval($val->pres_ecer)) / 100;
                                            $ar_data3[] = $_xTotalHarga;
                                            $ar_data31[] = $_xSB;
                                            $ar_data32[] = $_xECER;
                                    $cek_bayar = $this->data_model->get_byid('a_nota_bayar',['id_nota'=>$idnota]);
                                    $cek_bayarSB = $this->data_model->get_byid('a_nota_bayar',['id_nota'=>$idnota,'tujuan_rek'=>'SB']);
                                    $cek_bayarEcer = $this->data_model->get_byid('a_nota_bayar',['id_nota'=>$idnota,'tujuan_rek'=>'ECER']);
                                            if($cek_bayar->num_rows() == 0){
                                                //echo "<td>0</td>";
                                                //echo "<td>Rp. ".$ttl."</td>";
                                                $kurangan = $val->total_harga;
                                                $ar_data4[] = "0";
                                                $ar_data5[] = $val->total_harga;
                                            } else {
                                                $cek_bayar2 = $this->db->query("SELECT SUM(nominal_pemb) as nilai FROM a_nota_bayar WHERE id_nota='$idnota'")->row("nilai");
                                                //echo "Rp. ".number_format($cek_bayar2,0,',','.');
                                                $kurangan = $val->total_harga - $cek_bayar2;
                                                //echo "<td>Rp. ".number_format($cek_bayar2,0,',','.')."</td>";
                                                //echo "<td>Rp. ".number_format($kurangan,0,',','.')."</td>";
                                                $ar_data4[] = $cek_bayar2;
                                                $ar_data5[] = $kurangan;
                                            }
                                            if($cek_bayarSB->num_rows() == 0){
                                                //echo "<td>0</td>";
                                                //echo "<td>Rp. ".$ttl."</td>";
                                                $kurangan1 = $val->total_harga;
                                                $ar_data41[] = "0";
                                                $ar_data51[] = $val->total_harga;
                                            } else {
                                                $cek_bayar2 = $this->db->query("SELECT SUM(nominal_pemb) as nilai FROM a_nota_bayar WHERE id_nota='$idnota' AND tujuan_rek='SB'")->row("nilai");
                                                //echo "Rp. ".number_format($cek_bayar2,0,',','.');
                                                $kurangan1 = $val->total_harga - $cek_bayar2;
                                                //echo "<td>Rp. ".number_format($cek_bayar2,0,',','.')."</td>";
                                                //echo "<td>Rp. ".number_format($kurangan,0,',','.')."</td>";
                                                $ar_data41[] = $cek_bayar2;
                                                $ar_data51[] = $kurangan1;
                                            }
                                            if($cek_bayarEcer->num_rows() == 0){
                                                //echo "<td>0</td>";
                                                //echo "<td>Rp. ".$ttl."</td>";
                                                $kurangan2 = $val->total_harga;
                                                $ar_data42[] = "0";
                                                $ar_data52[] = $val->total_harga;
                                            } else {
                                                $cek_bayar2 = $this->db->query("SELECT SUM(nominal_pemb) as nilai FROM a_nota_bayar WHERE id_nota='$idnota' AND tujuan_rek='ECER'")->row("nilai");
                                                //echo "Rp. ".number_format($cek_bayar2,0,',','.');
                                                $kurangan2 = $val->total_harga - $cek_bayar2;
                                                //echo "<td>Rp. ".number_format($cek_bayar2,0,',','.')."</td>";
                                                //echo "<td>Rp. ".number_format($kurangan,0,',','.')."</td>";
                                                $ar_data41[] = $cek_bayar2;
                                                $ar_data51[] = $kurangan2;
                                            }
                                }
                                asort($ar_data2);
                                $total_kurang = 0; $totalid=""; $txtkurang ="";
                                $nomberku=1;
                                foreach($ar_data2 as $idx => $vals):
                                $idnota = $ar_data1[$idx];
                                $totalid = $totalid."".$idnota.",";
                            ?>
                                    <tr>
                                        <td><?=$nomberku;?></td>
                                        
                                        <td><?=$idnota;?></td>
                                        <td>
                                            <?php
                                            $idcusni = $this->data_model->get_byid('view_nota',['id_nota'=>$idnota])->row("id_customer");
                                            $nmcus = $this->data_model->get_byid('dt_konsumen',['id_konsumen'=>$idcusni])->row("nama_konsumen");
                                            echo $nmcus; 
                                            ?>
                                        </td>
                                        <td>
                                            <?php $ex=explode('-',$vals); echo $ex[2]." ".$bln[$ex[1]]." ".$ex[0]; 
                                            
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                                if(fmod($ar_data3[$idx], 1) !== 0.00){
                                                    $ttl = number_format($ar_data3[$idx],2,',','.');
                                                } else {
                                                    $ttl = number_format($ar_data3[$idx],0,',','.');
                                                }
                                                if(fmod($ar_data31[$idx], 1) !== 0.00){
                                                    $ttl31 = number_format($ar_data31[$idx],2,',','.');
                                                } else {
                                                    $ttl31 = number_format($ar_data31[$idx],0,',','.');
                                                }
                                                echo "Rp. ".$ttl31; 
                                                //$ar_data3[] = $val->total_harga; //ini tampilan rekening SB
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                                if(fmod($ar_data32[$idx], 1) !== 0.00){
                                                    $ttl32 = number_format($ar_data32[$idx],2,',','.');
                                                } else {
                                                    $ttl32 = number_format($ar_data32[$idx],0,',','.');
                                                }
                                                echo "Rp. ".$ttl32; 
                                                //$ar_data3[] = $val->total_harga; //ini tampilan rekening SB
                                            ?>
                                        </td>
                                            <?php
                                            $cek_bayar = $this->data_model->get_byid('a_nota_bayar',['id_nota'=>$idnota]);
                                            $cek_bayarSB = $this->data_model->get_byid('a_nota_bayar',['id_nota'=>$idnota,'tujuan_rek'=>'SB']);
                                            $cek_bayarEcer = $this->data_model->get_byid('a_nota_bayar',['id_nota'=>$idnota,'tujuan_rek'=>'ECER']);
                                            if($cek_bayar->num_rows() == 0){
                                                //echo "<td>".$ar_data4[$idx]."</td>";
                                                //echo "<td>Rp. ".$ttl."</td>";
                                                $kurangan = $ar_data3[$idx];
                                                //$ar_data4[] = "0";
                                                //$ar_data5[] = $ttl;
                                                //echo "<td>0</td>";
                                                //echo "<td>0</td>";
                                            } else {
                                                $cek_bayar2 = $this->db->query("SELECT SUM(nominal_pemb) as nilai FROM a_nota_bayar WHERE id_nota='$idnota' AND tujuan_rek='SB'")->row("nilai");
                                                //echo "Rp. ".number_format($cek_bayar2,0,',','.');
                                                $kurangan = $ar_data3[$idx] - $cek_bayar2;
                                                //echo "<td>Rp. ".number_format($cek_bayar2,0,',','.')."</td>";
                                                //echo "<td>Rp. ".number_format($kurangan,0,',','.')."</td>";
                                                //$ar_data4[] = $cek_bayar2;
                                                //$ar_data5[] = $kurangan;
                                                //echo "<td>0</td>";
                                                //echo "<td>0</td>";
                                            }
                                            if($cek_bayarSB->num_rows() == 0){
                                                //echo "<td>".$ar_data41[$idx]."</td>";
                                                echo "<td>Rp. ".$ttl31."</td>";
                                                $kurangan31 = $ar_data31[$idx];
                                                //$ar_data4[] = "0";
                                                //$ar_data5[] = $ttl;
                                                //echo "<td>0</td>";
                                                //echo "<td>0</td>";
                                            } else {
                                                $cek_bayar2 = $this->db->query("SELECT SUM(nominal_pemb) as nilai FROM a_nota_bayar WHERE id_nota='$idnota' AND tujuan_rek='SB'")->row("nilai");
                                                //echo "Rp. ".number_format($cek_bayar2,0,',','.');
                                                $kurangan31 = $ar_data31[$idx] - $cek_bayar2;
                                                //echo "<td>Rp. ".number_format($cek_bayar2,0,',','.')."</td>";
                                                echo "<td>Rp. ".number_format($kurangan31,0,',','.')."</td>";
                                            }
                                            if($cek_bayarEcer->num_rows() == 0){
                                                //echo "<td>".$ar_data42[$idx]."</td>";
                                                echo "<td>Rp. ".$ttl32."</td>";
                                                $kurangan32 = $ar_data32[$idx];
                                                //$ar_data4[] = "0";
                                                //$ar_data5[] = $ttl;
                                                //echo "<td>0</td>";
                                                //echo "<td>0</td>";
                                            } else {
                                                $cek_bayar2 = $this->db->query("SELECT SUM(nominal_pemb) as nilai FROM a_nota_bayar WHERE id_nota='$idnota' AND tujuan_rek='ECER'")->row("nilai");
                                                //echo "Rp. ".number_format($cek_bayar2,0,',','.');
                                                $kurangan32 = $ar_data32[$idx] - $cek_bayar2;
                                                //echo "<td>Rp. ".number_format($cek_bayar2,0,',','.')."</td>";
                                                echo "<td>Rp. ".number_format($kurangan32,0,',','.')."</td>";
                                            }
                                            $total_kurang+=$kurangan;
                                            $nilai_total_kurang = "Rp. ".number_format($total_kurang,0,',','.');
                                            $total_kurang2 = "".number_format($total_kurang,0,',',',');
                                            $txtkurang = $txtkurang."".$kurangan.",";
                                            ?>
                                            <input type="hidden" value="<?=$kurangan;?>" id="idkurangan-<?=$nomberku;?>">
                                        
                                        <td><?php if($kurangan<2){}else { ?>
                                            <input type="checkbox" id="ck-<?=$nomberku;?>" style="width:15px;height:15px;cursor:pointer;" onclick="klikNotaPilih('<?=$idnota;?>','<?=$kurangan;?>','<?=$nomberku;?>')" checked>
                                            <input type="hidden" id="cekpilih-<?=$nomberku;?>" value="1"><?php } ?>
                                        </td>
                                        <td>
                                            <?php if($kurangan<2){
                                                ?><span style="background:#279c17; padding:5px; border-radius:4px; font-size:10px;cursor:pointer;color:#ffffff;">Lunas</span><?php
                                            } else { ?>
                                            <a href="javascript:;" onclick="changeNota('<?=$idnota;?>')" data-toggle="modal" data-target="#Medium-modal">
                                            <span style="background:#156fed; padding:5px; border-radius:4px; font-size:10px;cursor:pointer;color:#ffffff;">Pembayaran</span></a>
                                            <?php } ?>
                                            <a href="<?=base_url('nota/piutang/'.sha1($idnota));?>">
                                                <span style="background:#e6122f; padding:5px; border-radius:4px; font-size:10px;cursor:pointer;color:#ffffff;">Kartu Piutang</span></a>
                                        </td>
                                    </tr>
                            <?php $nomberku++;
                                endforeach;
                                // for ($i=0; $i <count($ar_data1) ; $i++) { 
                                //     echo $ar_data1[$i]." > ";
                                //     echo $ar_data2[$i]." > ";
                                //     echo $ar_data3[$i]." > ";
                                //     echo $ar_data4[$i]." > ";
                                //     echo $ar_data5[$i]."<br>";
                                // }
                            ?>
								</tbody>
							</table>
                            <form action="<?=base_url('nota/newpembayaran');?>" method="post" name="fr1" id="frt123">
                            <input type="hidden" id="jumlahUangdipilih" name="jumlahTotalKurang" value="<?=$total_kurang;?>" required>
                            <input type="hidden" id="idallkurang" name="allkurang" value="<?=$txtkurang;?>" required>
                            <input type="hidden" id="idDipilih" name="totalid" value="<?=$totalid;?>" required>
                            <input type="hidden" value="<?=$idcus;?>" id="icusid" name="icus" required>
                            <p>&nbsp;</p>
                            <h4 id="textTotalID">Total Rp. sesuai yang di klik</h4>
                            <p>&nbsp;</p>
                            <span>Tanggal Transaksi</span>
                            <input type="date" class="form-control" value="<?=date('Y-m-d');?>" name="tglbayar" id="idtglbyr" style="max-width:300px;" required>
                            <br>
                            <span>Jumlah Pembayaran Konsumen</span>
                            <input type="text" id="jmlPembKonsum" name="dibayarkonsumen" class="form-control" style="max-width:300px;" required>
                            <br>
                            <span>Metode Pembayaran</span>
                            <select class="form-control" style="max-width:300px;" name="metodbayar" id="metodbayarid" required>
                                <option value="">Pilih Metode Bayar</option>
                                <option value="Cash">Cash</option>
                                <option value="Bank">Transfer Bank</option>
                            </select>
                            <br>
                            <span>Tujuan Pembayaran</span>
                            <select class="form-control" style="max-width:300px;" name="tjuanbayar" id="tjuanbayarid" required>
                                <option value="">Pilih Tujuan Bayar</option>
                                <option value="SB">SB</option>
                                <option value="ECER">ECER</option>
                            </select>
                            <br>
                            <span>Nomor Bukti</span>
                            <input type="text" class="form-control" name="nomorbuk" id="nomorbukid" placeholder="Masukan nomor bukti pembayaran" style="max-width:300px;" required>
                            <br>
                            <button type="submit" class="btn btn-success">Bayar</button>
                            </form>
						</div>
					</div>
					<!-- Simple Datatable End -->
                    <!-- Medium modal -->
								<div class="modal fade" id="Medium-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel">
													Pembayaran Konsumen
												</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											</div>
											<form name="fr2" action="<?=base_url('nota/kredit_bayar');?>" method="post">
                                            <input type="hidden" name="cus" value="<?=$idcus;?>">
											<div class="modal-body">
												<table class="table">
                                                    <tr>
                                                        <td>No Nota</td>
                                                        <td><input type="text" class="form-control" name="idnota" id="idmdlnota" readonly></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Tanggal Bayar</td>
                                                        <td><input type="date" class="form-control" name="tglbayar" required></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Nominal Bayar</td>
                                                        <td><input type="text" class="form-control" name="nominal" id="howek" required></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Metode Bayar</td>
                                                        <td>
                                                            <select name="metode" id="metode" class="form-control" required>
                                                                <option value="">Pilih</option>
                                                                <option value="Cash">Cash</option>
                                                                <option value="Bank">Transfer Bank</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Nomor Bukti</td>
                                                        <td><input type="text" name="nobukti" class="form-control" required></td>
                                                    </tr>
                                                </table>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-dismiss="modal">
													Tutup
												</button>
												<button type="submit" class="btn btn-success">
													Simpan
												</button>
											</div>
											</form>
										</div>
									</div>
								</div>
					
        </div>
    </div>
</div>
<script>
    function changeNota(id){
        document.getElementById('idmdlnota').value = ''+id;
    }
    document.getElementById('totalpiutang').innerHTML = '<?=$nilai_total_kurang;?>';
    document.getElementById('textTotalID').innerHTML = 'Total <?=$nilai_total_kurang;?>';
    document.getElementById('jmlPembKonsum').value = '<?=$total_kurang2;?>';
    
</script>