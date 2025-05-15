<?php $bln = [ '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des', '00' => 'Undefined' ]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="title">
							<h4>Data Prosesing Aplikasi</h4>
							<p>Menampilkan semua proses yang pernah anda kerjakan dalam aplikasi.</p>
					    </div>
                        <table class="table table-bordered table-hover">
							<tr>
								<td>No.</td>
								<td>Tanggal</td>
								<td>Waktu</td>
								<td>Nama Proses</td>
								<td>Kode</td>
								<td>#</td>
							</tr>
							<?php 
							//echo "".$sess_id;
							$cekproses = $this->db->query("SELECT * FROM log_input_roll WHERE penginput='$sess_id' ORDER BY id_loginp DESC");
							foreach($cekproses->result() as $n => $val):
							$ex = explode(' ', $val->tmstmp);
							$er = explode('-', $ex[0]);
							if($val->proses_name == "Inspect Grey"){
								$link = "".base_url()."resume/insgrey/".$val->kodeauto;
							}
							if($val->proses_name == "Inspect Finish"){
								$link = "".base_url()."resume/insfinish/".$val->kodeauto;
							}
							?>
							<tr>
								<td><?=$n+1;?></td>
								<td><?=$er[2]." ".$bln[$er[1]]." ".$er[0];?></td>
								<td><?=$ex[1];?></td>
								<td><?=$val->proses_name;?></td>
								<td><?=$val->kodeauto;?></td>
								<td>
									<?php if($val->simpan == "saved"){ ?>
									<a href="javascript:;"><span style="background:green;padding:3px 10px;border-radius:3px;color:#FFFFFF;font-size:0.8em;cursor:pointer;">Saved</span></a>
									<?php } else { ?>
									<a href="<?=$link;?>"><span style="background:orange;padding:3px 10px;border-radius:3px;color:#FFFFFF;font-size:0.8em;cursor:pointer;">View</span></a>
									<?php } ?>
									<a href=""><span style="background:red;padding:3px 10px;border-radius:3px;color:#FFFFFF;font-size:0.8em;cursor:pointer;">Reverse</span></a>
								</td>
							</tr>
							<?php
							endforeach;
							?>
						</table>
					</div>
					<!-- Simple Datatable start -->
					<!-- Bootstrap Select End -->
					
					
        </div>
    </div>
</div>
