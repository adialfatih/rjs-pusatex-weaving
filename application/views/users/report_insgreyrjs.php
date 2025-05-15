<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="google" content="notranslate">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Inspect Grey</title>
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
        $tgl = date('Y-m-d');
        $ex = explode('-', $tgl);
        $arbln = $this->data_model->printBln($ex[1]);
        $printTgl = $ex[2]." ".$arbln." ".$ex[0];
        $username =strtolower($this->uri->segment(3));
        $cek_username = $this->data_model->get_byid('a_operator', ['username'=>$username]);
        $currentTime = date('H:i');
        $tgl_kemarin = date('Y-m-d', strtotime($tgl . ' -1 day'));
        
        if($cek_username->num_rows() == 1){
            if (strtotime($currentTime) >= strtotime('00:00') && strtotime($currentTime) < strtotime('06:00')) { 
                $cek_ig = $this->data_model->get_byid('data_ig', ['tanggal'=>$tgl_kemarin,'operator'=>$username,'shift_op'=>'3']);
                $cek_ig2 = $this->data_model->get_byid('data_ig', ['tanggal'=>$tgl,'operator'=>$username,'shift_op'=>'31']);
            } elseif(strtotime($currentTime) >= strtotime('22:00') && strtotime($currentTime) < strtotime('23:59')) {
                $cek_ig = $this->data_model->get_byid('data_ig', ['tanggal'=>$tgl,'operator'=>$username,'shift_op'=>'3']);
            } else {
                $cek_ig = $this->data_model->get_byid('data_ig', ['tanggal'=>$tgl,'operator'=>$username]);
            }
            $konst = array();
    ?>
        <p style="font-size:12px;">Resume <br>
        Nama Operator : <strong><?=ucfirst($username);?></strong><br>
        Tanggal : <strong><?=$printTgl;?></strong><br>
        Inspect Grey :
        </p>
        <div class="fortable2">
            <table id="fortable">
                <tr>
                    <td><strong>No</strong></td>
                    <td><strong>Kode Roll</strong></td>
                    <td><strong>Konstruksi</strong></td>
                    <td><strong>MC</strong></td>
                    <td><strong>Ukuran</strong></td>
                </tr>
                <?php
                //if($cek_ig->num_rows() > 0){
                    $total_ukuran = 0;
                    $jumlah_data = 1;
                    foreach($cek_ig->result() as $n => $val):
                        $total_ukuran+= floatval($val->ukuran_ori);
                        echo "<tr>";
                        $num = $n+1;
                        echo "<td>".$jumlah_data."</td>";
                        echo "<td>".$val->kode_roll."</td>";
                        echo "<td>".$val->konstruksi."</td>";
                        echo "<td>".strtoupper($val->no_mesin)."</td>";
                        echo "<td>".$val->ukuran_ori."</td>";
                        echo "</tr>";
                        $jumlah_data++;
                        if (in_array($val->konstruksi, $konst)){ } else {
                            $konst[] = $val->konstruksi;
                        }
                    endforeach;
                    if (strtotime($currentTime) >= strtotime('00:00') && strtotime($currentTime) < strtotime('06:00')) { 
                        if($cek_ig2->num_rows() > 0){
                            foreach($cek_ig2->result() as $ns => $vals){
                                $total_ukuran+= floatval($vals->ukuran_ori);
                                echo "<tr>";
                                $num = $n+1;
                                echo "<td>".$jumlah_data."</td>";
                                echo "<td>".$vals->kode_roll."</td>";
                                echo "<td>".$vals->konstruksi."</td>";
                                echo "<td>".strtoupper($vals->no_mesin)."</td>";
                                echo "<td>".$vals->ukuran_ori."</td>";
                                echo "</tr>";
                                $jumlah_data++;
                                if (in_array($vals->konstruksi, $konst)){ } else {
                                    $konst[] = $vals->konstruksi;
                                }
                            }
                        }
                    }
                    echo "<tr>";
                    echo "<td>#</td><td colspan='3'><strong>Total</strong></td>";
                    echo "<td>".number_format($total_ukuran)."</td>";
                    echo "</tr>";
                ?>
                <tr>
                    <td colspan="5">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="5">Produksi : </td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Konstruksi</strong></td>
                    <td><strong>ORI</strong></td>
                    <td><strong>BS</strong></td>
                    <td><strong>BP</strong></td>
                </tr>
                <?php
                    //$cek_prod = $this->data_model->get_byid('data_produksi_opt', ['username_opt'=>$username,'tgl'=>$tgl,'proses'=>'insgrey']);
                    foreach($konst as $valse):
                         if (strtotime($currentTime) >= strtotime('00:00') && strtotime($currentTime) < strtotime('06:00')) { 
                            
                            $jml_ori1 = $this->db->query("SELECT SUM(ukuran_ori) AS uk FROM data_ig WHERE konstruksi='$valse' AND tanggal='$tgl_kemarin' AND operator='$username' AND shift_op='3'")->row("uk");
                            $jml_ori2 = $this->db->query("SELECT SUM(ukuran_ori) AS uk FROM data_ig WHERE konstruksi='$valse' AND tanggal='$tgl' AND operator='$username' AND shift_op='31'")->row("uk");
                            $jml_bs1 = $this->db->query("SELECT SUM(ukuran_bs) AS uk FROM data_ig WHERE konstruksi='$valse' AND tanggal='$tgl_kemarin' AND operator='$username' AND shift_op='3'")->row("uk");
                            $jml_bs2 = $this->db->query("SELECT SUM(ukuran_bs) AS uk FROM data_ig WHERE konstruksi='$valse' AND tanggal='$tgl' AND operator='$username' AND shift_op='31'")->row("uk");
                            $jml_bp1 = $this->db->query("SELECT SUM(ukuran_bp) AS uk FROM data_ig WHERE konstruksi='$valse' AND tanggal='$tgl_kemarin' AND operator='$username' AND shift_op='3'")->row("uk");
                            $jml_bp2 = $this->db->query("SELECT SUM(ukuran_bp) AS uk FROM data_ig WHERE konstruksi='$valse' AND tanggal='$tgl' AND operator='$username' AND shift_op='31'")->row("uk");
                            
                            $jml_ori = $jml_ori1 + $jml_ori2;
                            $jml_bs = $jml_bs1 + $jml_bs2;
                            $jml_bp = $jml_bp1 + $jml_bp2;
                        } elseif(strtotime($currentTime) >= strtotime('22:00') && strtotime($currentTime) < strtotime('23:59')) {
                            $jml_ori = $this->db->query("SELECT SUM(ukuran_ori) AS uk FROM data_ig WHERE konstruksi='$valse' AND tanggal='$tgl' AND operator='$username' AND shift_op='3'")->row("uk");
                            $jml_bs = $this->db->query("SELECT SUM(ukuran_bs) AS uk FROM data_ig WHERE konstruksi='$valse' AND tanggal='$tgl' AND operator='$username' AND shift_op='3'")->row("uk");
                            $jml_bp = $this->db->query("SELECT SUM(ukuran_bp) AS uk FROM data_ig WHERE konstruksi='$valse' AND tanggal='$tgl' AND operator='$username' AND shift_op='3'")->row("uk");
                        } else {
                            $jml_ori = $this->db->query("SELECT SUM(ukuran_ori) AS uk FROM data_ig WHERE konstruksi='$valse' AND tanggal='$tgl' AND operator='$username' ")->row("uk");
                            $jml_bs = $this->db->query("SELECT SUM(ukuran_bs) AS uk FROM data_ig WHERE konstruksi='$valse' AND tanggal='$tgl' AND operator='$username' ")->row("uk");
                            $jml_bp = $this->db->query("SELECT SUM(ukuran_bp) AS uk FROM data_ig WHERE konstruksi='$valse' AND tanggal='$tgl' AND operator='$username' ")->row("uk");
                        }
                        echo "<tr>";
                        echo "<td colspan='2'>".$valse."</td>";
                        echo "<td>".$jml_ori."</td>";
                        echo "<td>".$jml_bs."</td>";
                        echo "<td>".$jml_bp."</td>";
                        echo "</tr>";
                    endforeach;
                // } else {
                //     echo "<tr><td colspan='5'>Data Inspect Grey Kosong</td></tr>";
                // }
                ?>
                
            </table>
        </div>
        
        <div class="doble-btn no-print">
                <div class="db-btn" onclick="window.print()">Cetak</div>
                <div class="db-btn red" onclick="logout()">Keluar</div>
        </div>
        
    <?php 
        
        } else { echo "Username tidak ditemukan"; }
    
    
    ?>
    
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
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
            function logout(){
                sessionStorage.removeItem("userName");
                peringatan('Logout.. Please wait');
                setTimeout(() => {
                    window.location.href = "<?=base_url('usersrjs');?>";
                }, "3300");
            }
    </script>
</body>
</html>