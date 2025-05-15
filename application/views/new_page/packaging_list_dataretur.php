<?php $bln = [ '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des', ]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Retur ?</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="Javascript:void(0);">Home</a>
										</li>
                                        <li class="breadcrumb-item">
											<a href="Javascript:void(0);">Data</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Retur
										</li>
									</ol>
								</nav>
							</div>
							
						</div>
					</div>
					<!-- Simple Datatable start -->
				<form action="<?=base_url('packing-list-retur-proses');?>" name="fr1" method="post">
                <input type="hidden" name="ket" value="<?=$ket?>">
                <input type="hidden" name="rkode" value="<?=$rkode?>">
                <input type="hidden" name="tiperetur" value="<?=$tiperetur?>">
				<!-- basic table  Start -->
                <div class="pd-20 card-box mb-30">
                    <?php
                    if($tiperetur =="rjs"){
                        if($ket == ""){ echo "Proses Retur Berhasil. Silahkan Cetak ulang packinglist dan surat jalan"; } else {
                        ?>
                        <div class="table-responsive">
                            Alasan Retur : <strong><?=$ket?></strong><hr>
                            <?php
                                $x = explode(',', $rkode);
                                for ($i=0; $i < count($x); $i++) { 
                                    $kode_roll = $x[$i];
                                    $datas = $this->data_model->get_byid('new_tb_isi', ['siap_jual'=>'n','kode'=>$kode_roll]);
                                    $pkg = $datas->row('kd');
                                    if($datas->num_rows() == 1){
                                        $datas2 = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$pkg])->row_array();
                                        $nosj = $datas2['no_sj'];
                                        $tujuan = $datas2['kepada'];
                                        //$nt = $this->data_model->get_byid('surat_jalan', ['no_sj'=>$nosj])->row("create_nota");
                                        //if($nt == 'y'){$nt = 'Sudah';} else {$nt = 'Belum';}
                                        echo "Kode Roll <strong>".$kode_roll."</strong> ditemukan di packing list <strong>".$pkg."</strong> tujuan kirim <strong>".$tujuan."</strong> Nomor SJ <strong>".$nosj."</strong>.<br>";
                                        $txtRetur = "Retur Kode Roll <strong>".$kode_roll."</strong> packing list <strong>".$pkg."</strong> tujuan kirim <strong>".$tujuan."</strong> Nomor SJ <strong>".$nosj."</strong>.<br>";
                                        ?>
                                        <input type="hidden" name="txt[]" value="<?=$txtRetur?>">
                                        <input type="hidden" name="kode_roll[]" value="<?=$kode_roll?>">
                                        <?php
                                    } else {
                                        echo "<font style='color:red;'>Kode Roll <strong>".$kode_roll."</strong> tidak ditemukan di packing list manapun.!!</font><br>";
                                    }
                                }
                            ?>
						</div>
                        <hr>
                        <button type="submit" class="btn btn-danger">Simpan & Lanjutkan Proses Retur</button>
                        <?php }
                    } else {
                    if($ket == ""){ echo "Proses Retur Berhasil. Silahkan Cetak ulang packinglist dan surat jalan"; } else {
                    ?>
						<div class="table-responsive">
                            Alasan Retur : <strong><?=$ket?></strong><hr>
                            <?php
                                $x = explode(',', $rkode);
                                for ($i=0; $i < count($x); $i++) { 
                                    $kode_roll = $x[$i];
                                    $datas = $this->data_model->get_byid('new_tb_isi', ['siap_jual'=>'y','kode'=>$kode_roll]);
                                    $pkg = $datas->row('kd');
                                    if($datas->num_rows() == 1){
                                        $datas2 = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$pkg])->row_array();
                                        $nosj = $datas2['no_sj'];
                                        $tujuan = $datas2['kepada'];
                                        $nt = $this->data_model->get_byid('surat_jalan', ['no_sj'=>$nosj])->row("create_nota");
                                        if($nt == 'y'){$nt = 'Sudah';} else {$nt = 'Belum';}
                                        echo "Kode Roll <strong>".$kode_roll."</strong> ditemukan di packing list <strong>".$pkg."</strong> tujuan kirim <strong>".$tujuan."</strong> Nomor SJ <strong>".$nosj."</strong>. Nota $nt dibuat<br>";
                                        $txtRetur = "Retur Kode Roll <strong>".$kode_roll."</strong> packing list <strong>".$pkg."</strong> tujuan kirim <strong>".$tujuan."</strong> Nomor SJ <strong>".$nosj."</strong>. Nota $nt dibuat<br>";
                                        ?>
                                        <input type="hidden" name="txt[]" value="<?=$txtRetur?>">
                                        <input type="hidden" name="kode_roll[]" value="<?=$kode_roll?>">
                                        <?php
                                    } else {
                                        echo "<font style='color:red;'>Kode Roll <strong>".$kode_roll."</strong> tidak ditemukan di packing list manapun.!!</font><br>";
                                    }
                                }
                            ?>
						</div>
                        <hr>
                        <button type="submit" class="btn btn-danger">Simpan & Lanjutkan Proses Retur</button>
                    <?php } } ?>
				</div>
                </form>
        </div>
    </div>
</div>