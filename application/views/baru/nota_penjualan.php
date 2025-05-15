<?php $bln = [ '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des', ]; ?>
<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Nota Penjualan</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="javascript:void(0);">Home</a>
										</li>
                                        <li class="breadcrumb-item">
											<a href="javascript:void(0);">Penjualan</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Nota Penjualan
										</li>
									</ol>
								</nav>
							</div>
							<!-- <div class="col-md-6 col-sm-12 text-right">
								<div class="dropdown">
									<a class="btn btn-primary dropdown-toggle no-arrow" href="javascript:void(0);" role="button" data-toggle="modal" data-target="#bd-example-modal-lg">
										Tambah Penjualan
									</a>
									
								</div>
							</div> -->
						</div>
					</div>
					<!-- Simple Datatable start -->
					<?php if(!empty($this->session->flashdata('success'))){ ?>
                    <div class="alert alert-success" role="alert">
						<?=$this->session->flashdata('success');?>
					</div>
                    <?php } ?>
                    <?php if(!empty($this->session->flashdata('gagal'))){ ?>
					<div class="alert alert-danger" role="alert">
                    <?=$this->session->flashdata('gagal');?>
					</div><?php } ?>
					<div class="card-box mb-30">
						<div class="pd-20">
                            <a href="<?=base_url('nota/baru');?>">
							<button class="btn btn-primary"><i class="icon-copy bi bi-plus-circle"></i>&nbsp;&nbsp;Buat Nota Baru</button></a>
							<a href="#" onclick="window.open('<?=base_url('nota-update');?>', '_blank', 'width=600,height=400')">
							<button class="btn btn-success"><i class="icon-copy bi bi-arrow-clockwise"></i>&nbsp;&nbsp;Update Nota</button></a>
						</div>
						<div class="pb-20">
							<table class="data-table table stripe hover nowrap">
								<thead>
									<tr>
										<th class="table-plus datatable-nosort">No</th>
										<th>Customer</th>
										<th>No Nota</th>
										<!-- <th>Kode PL</th> -->
										<th>Jenis Kain</th>
										<th>Panjang</th>
										<th>Harga</th>
										<th>Total</th>
										<th>PPN</th>
										<th>Status</th>
										<th>Print</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$numbering = 1;
									foreach($dtnota->result() as $n => $val):
									$nosj = $val->no_sj;
									$tgl_nota = $val->tgl_nota;
									$rek_sb = $val->pres_sb;
									$rek_ecer = $val->pres_ecer;
									$rek_notad = $val->pres_notad;
									$_ppn = $val->pajak_ppn;
									$exsj = explode('/',$nosj);
									if($exsj[0] == "SLD"){} else {
									$idcus = $this->data_model->get_byid('surat_jalan', ['no_sj'=>$nosj])->row("id_customer");
									$idsj = $this->data_model->get_byid('surat_jalan', ['no_sj'=>$nosj])->row("id_sj");
									$nmcus = $this->data_model->get_byid('dt_konsumen', ['id_konsumen'=>$idcus])->row("nama_konsumen");
									?>
                                    <tr>
                                        <td><?=$numbering;?></td>
										<td><?=$nmcus;?></td>
                                        <td><?=$val->id_nota;?></td>
                                        <!-- <td><=$val->kd;?></td> -->
                                        <td><?=$val->konstruksi;?></td>
                                        <td>
                                            <?php
                                                if(fmod($val->total_panjang, 1) !== 0.00){
                                                    $ttl = number_format($val->total_panjang,2,',','.');
                                                } else {
                                                    $ttl = number_format($val->total_panjang,0,',','.');
                                                }
                                                echo $ttl;
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                                if(fmod($val->harga_satuan, 1) !== 0.00){
                                                    $hrg = number_format($val->harga_satuan,2,',','.');
                                                } else {
                                                    $hrg = number_format($val->harga_satuan,0,',','.');
                                                }
                                                echo "Rp. ".$hrg;
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                                if(fmod($val->total_harga_asli, 1) !== 0.00){
                                                    $thrg = number_format($val->total_harga_asli,2,',','.');
                                                } else {
                                                    $thrg = number_format($val->total_harga_asli,0,',','.');
                                                }
                                                echo "Rp. ".$thrg;
                                            ?>
                                        </td>
										<td><?=$val->pajak_ppn;?> %</td>
										<td><a href="<?=base_url('nota/konsumen/'.sha1($idcus));?>">
											<?php
                                            $cek_bayar = $this->data_model->get_byid('a_nota_bayar',['id_nota'=>$val->id_nota]);
                                            if($cek_bayar->num_rows() == 0){
                                                echo '<span style="background:#e6122f; padding:5px; border-radius:4px; font-size:10px;cursor:pointer;color:#ffffff;">Belum Dibayar</span>';
                                            } else {
                                                $cek_bayar2 = $this->db->query("SELECT SUM(nominal_pemb) as nilai FROM a_nota_bayar WHERE id_nota='$val->id_nota'")->row("nilai");
                                                //echo "Rp. ".number_format($cek_bayar2,0,',','.');
                                                $kurangan = $val->total_harga - $cek_bayar2;
                                                if($kurangan==0){
													echo '<span style="background:#279c17; padding:5px; border-radius:4px; font-size:10px;cursor:pointer;color:#ffffff;">Lunas</span>';
												} else {
													echo '<span style="background:#f59127; padding:5px; border-radius:4px; font-size:10px;cursor:pointer;color:#ffffff;">Belum Lunas</span>';
												}
                                            }
                                            
                                            ?></a>
										</td>
										<td>
											<a href="<?=base_url('cetak/nota/'.$idsj);?>" target="_blank" title="Cetak Nota">
												<i class="icon-copy bi bi-printer-fill" style="color:black;" aria-hidden="true"></i>
											</a>
											&nbsp;&nbsp;
											<a href="javascript:;" onclick="newCode('<?=$val->id_nota;?>','<?=$val->harga_satuan;?>','<?=$tgl_nota;?>','<?=$rek_sb;?>','<?=$rek_ecer;?>','<?=$rek_notad;?>','<?=$_ppn;?>')" target="_blank" title="Edit Nota" data-toggle="modal" data-target="#Medium-modal">
												<i class="icon-copy fa fa-edit" aria-hidden="true"></i>
											</a>
											&nbsp;&nbsp;
											<a href="javascript:;" target="_blank" onclick="newCode2('<?=$val->no_sj;?>')" title="Hapus Nota" data-toggle="modal" data-target="#Medium-modal2">
												<i class="icon-copy fa fa-trash-o" style="color:red;" aria-hidden="true"></i>
											</a>
											&nbsp;&nbsp;
											<a href="javascript:;" target="_blank" onclick="newCode3('<?=$val->id_nota;?>','<?=$val->total_panjang;?>','<?=$val->harga_satuan;?>')" title="Split Nota" data-toggle="modal" data-target="#Medium-modal3">
										<span class="icon-copy ti-split-v"></span></a>
										</td>
                                    </tr>
                                    <?php $numbering++; } endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
					<!-- Simple Datatable End -->
								<div class="modal fade" id="Medium-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered">
										<div class="modal-content">
											<form action="<?=base_url('nota/editharga');?>" method="post">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel">
													Edit Nota
												</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											</div>
											<div class="modal-body">
												<table class="table">
													<tr>
														<td>No Nota</td>
														<td><strong id="idnota">123</strong><input type="hidden" name="idnotas" id="idnota2"></td>
													</tr>
													<tr>
														<td>Harga Satuan</td>
														<td><input type="text" class="form-control" name="hargasatuan" id="iptharga" placeholder="Masukan harga satuan"></td>
													</tr>
													<tr>
														<td>Tanggal Nota</td>
														<td><input type="date" class="form-control" name="tglnt" id="tglnt" placeholder="Masukan tanggal"></td>
													</tr>
													<tr>
														<td style="color:red;">PPN %</td>
														<td><input type="number" class="form-control" name="ppn34" max="100" min="0" id="ppn344id" placeholder="Masukan Presentase"></td>
													</tr>
													<tr>
														<td>Tagihan Ke SB %</td>
														<td><input type="number" class="form-control" name="tghsb" max="100" min="0" id="sbid2" placeholder="Masukan Presentase"></td>
													</tr>
													<tr>
														<td>Tagihan Ke Ecer %</td>
														<td><input type="number" class="form-control" name="tghecer" max="100" min="0" id="ecerid2" placeholder="Masukan Presentase"></td>
													</tr>
													<tr>
														<td>Tagihan Nota D %</td>
														<td><input type="number" class="form-control" name="tghnotad" max="100" min="0" id="tghnotad2" placeholder="Masukan Presentase"></td>
													</tr>
												</table>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
												<button type="submit" class="btn btn-primary">Save changes</button>
											</div>
											</form>
										</div>
									</div>
								</div>
								<div
									class="modal fade"
									id="Medium-modal2"
									tabindex="-1"
									role="dialog"
									aria-labelledby="myLargeModalLabel"
									aria-hidden="true"
								>
									<div class="modal-dialog modal-dialog-centered">
										<div class="modal-content">
											<form action="<?=base_url('nota/delnotas');?>" method="post">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel">
													Hapus Nota
												</h4>
												<button
													type="button"
													class="close"
													data-dismiss="modal"
													aria-hidden="true"
												>
													×
												</button>
											</div>
											<div class="modal-body">
												<table class="table">
													<tr>
														<td>No Nota</td>
														<td><strong id="idnota34">123</strong><input type="hidden" name="sj" id="idnota2566"></td>
													</tr>
													<tr>
														<td colspan="2">Anda akan menghapus semua nota dari nomor surat jalan tersebut.?</td>
													</tr>
												</table>
											</div>
											<div class="modal-footer">
												<button
													type="button"
													class="btn btn-secondary"
													data-dismiss="modal"
												>
													Close
												</button>
												<button type="submit" class="btn btn-danger">
													Hapus
												</button>
											</div>
											</form>
										</div>
									</div>
								</div>
								<div
									class="modal fade"
									id="Medium-modal3"
									tabindex="-1"
									role="dialog"
									aria-labelledby="myLargeModalLabel"
									aria-hidden="true"
								>
									<div class="modal-dialog modal-dialog-centered">
										<div class="modal-content">
											<form action="<?=base_url('nota/splitnotas');?>" method="post">
											<div class="modal-header">
												<h4 class="modal-title" id="myLargeModalLabel">
													Split Nota
												</h4>
												<button
													type="button"
													class="close"
													data-dismiss="modal"
													aria-hidden="true"
												>
													×
												</button>
											</div>
											<div class="modal-body">
												<table class="table">
													<tr>
														<td>No Nota</td>
														<td><strong id="idnotasr34">123</strong><input type="hidden" name="idnota" id="idnotasr2566"></td>
													</tr>
													<tr>
														<td>
															<span>Panjang</span>
															<input type="text" class="form-control" placeholder="Masukan panjang" name="panjang[]" id="pjg_os">
														</td>
														<td>
															<span>Harga Satuan</span>
															<input type="text" class="form-control" placeholder="Input Harga Satuan" name="harga[]" id="hrg_os">
														</td>
													</tr>
													<tr>
														<td>
															<span>Panjang</span>
															<input type="text" class="form-control" placeholder="Masukan panjang" name="panjang[]">
														</td>
														<td>
															<span>Harga Satuan</span>
															<input type="text" class="form-control" placeholder="Input Harga Satuan" name="harga[]">
														</td>
													</tr>
													<tr>
														<td>
															<span>Panjang</span>
															<input type="text" class="form-control" placeholder="Masukan panjang" name="panjang[]">
														</td>
														<td>
															<span>Harga Satuan</span>
															<input type="text" class="form-control" placeholder="Input Harga Satuan" name="harga[]">
														</td>
													</tr>
													<tr>
														<td>
															<span>Panjang</span>
															<input type="text" class="form-control" placeholder="Masukan panjang" name="panjang[]">
														</td>
														<td>
															<span>Harga Satuan</span>
															<input type="text" class="form-control" placeholder="Input Harga Satuan" name="harga[]">
														</td>
													</tr>
												</table>
											</div>
											<div class="modal-footer">
												<button
													type="button"
													class="btn btn-secondary"
													data-dismiss="modal"
												>
													Close
												</button>
												<button type="submit" class="btn btn-success">
													Hapus
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
	function newCode(id,hrg,tgl,sb,ecer,notad,ppn){
		document.getElementById('idnota').innerHTML = ''+id;
		document.getElementById('iptharga').value = ''+hrg;
		document.getElementById('idnota2').value = ''+id;
		document.getElementById('tglnt').value = ''+tgl;
		document.getElementById('sbid2').value = ''+sb;
		document.getElementById('ecerid2').value = ''+ecer;
		document.getElementById('tghnotad2').value = ''+notad;
		document.getElementById('ppn344id').value = ''+ppn;
	}
	function newCode2(id){
		document.getElementById('idnota34').innerHTML = ''+id;
		document.getElementById('idnota2566').value = ''+id;
	}
	function newCode3(id,pjg,hrg){
		document.getElementById('idnotasr34').innerHTML = ''+id;
		document.getElementById('idnotasr2566').value = ''+id;
		document.getElementById('pjg_os').value = ''+pjg;
		document.getElementById('hrg_os').value = ''+hrg;
	}
</script>