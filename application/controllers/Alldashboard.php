<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alldashboard extends CI_Controller
{
  function __construct()
  {
      parent::__construct();
      $this->load->model('data_model');
      date_default_timezone_set("Asia/Jakarta");
  }
  function cekdatafol(){
        $qry = $this->db->query("SELECT * FROM data_fol WHERE jns_fold='Finish' AND (posisi='Pusatex' OR posisi LIKE '%PKT%')");
        ?>
        <table border="1" style="border-collapse:collapse;border:1px solid #000;">
            <tr>
                <th>No</th>
                <th>Kode Roll</th>
                <th>Konstruksi</th>
                <th>Ukuran</th>
                <th>Lokasi Sekarang</th>
            </tr>
            <?php
            $jumlah_data = $qry->num_rows();
            $jml_ori = 0;
            $jml_nonori = 0;
            foreach($qry->result() as $n => $val){
                $ukr = floatval($val->ukuran);
                $kons = strtoupper($val->konstruksi);
                $chto = $this->data_model->get_byid('tb_konstruksi',['kode_konstruksi'=>$kons])->row("chto");
                if($chto == "null" OR $chto == "NULL"){
                    $chto = $kons;
                }
                if($ukr > 99){
                    $jml_ori++;
                } else {
                    $jml_nonori++;
                }
                echo "<tr>";
                echo "<td>".($n+1)."</td>";
                echo "<td>".$val->kode_roll."</td>";
                echo "<td>".$chto."</td>";
                echo "<td>".$val->ukuran."</td>";
                echo "<td>".$val->posisi."</td>";
                echo "</tr>";
            }
            ?>
        </table>
        <?php
        $presen1 = ($jml_ori/$jumlah_data)*100;
        $presen1 = round($presen1, 2);
        $presen2 = ($jml_nonori/$jumlah_data)*100;
        $presen2 = round($presen2, 2);
        echo "Jumlah Ukuran >= 100 Meter adalah <strong>".$jml_ori."</strong> Roll (".$presen1." %)<br>Jumlah Ukuran < 100 Meter adalah <strong>".$jml_nonori."</strong> Roll (".$presen2." %)";
  }
  function index(){ 
      $this->load->view('manager/logindashboard');
  } //end
  function uppick(){
        $id = $this->input->post('idkons');
        $rjs = $this->input->post('pickrjs');
        $stx = $this->input->post('pickstx');
        for ($i=0; $i < count($id); $i++) { 
            $this->data_model->updatedata('id_konstruksi',$id[$i], 'tb_konstruksi', ['pick_rjs'=>$rjs[$i], 'pick_samatex'=>$stx[$i]]);
            echo $id[$i]."-".$rjs[$i]."-".$stx[$i]."<br>";
        }
  }

  function loaddataajl(){
        $pss = $this->input->post('posisi');
        $tgl = $this->input->post('tgl');
        //echo "owek $pss $tgl";
        echo "<table>";
        if($pss == "RJS"){
            $query = $this->data_model->get_byid('dt_produksi_mesin', ['tanggal_produksi'=>$tgl,'lokasi'=>"RJS"]);
            echo '<tr>
                <td><strong>No</strong></td>
                <td><strong>Konstruksi</strong></td>
                <td><strong>Mesin</strong></td>
                <td><strong>Hasil Produksi</strong></td>
                <td><strong>Pick</strong></td>
            </tr>';
            if($query->num_rows() > 0){
                $totalmc=0;
                $totalhasil=0;
                $sum_pick_mesin = 0;
                foreach($query->result() as $n => $val):
                    echo "<tr>";
                    $num = $n+1;
                    $ks = $val->kode_konstruksi;
                    $pick_rjs = $this->db->query("SELECT kode_konstruksi,pick_rjs FROM tb_konstruksi WHERE kode_konstruksi='$ks'")->row("pick_rjs");
                    echo "<td>".$num."</td>";
                    echo "<td>".$ks."</td>";
                    echo "<td>".$val->jumlah_mesin."</td>";
                    if(fmod($val->hasil, 1) !== 0.00){
                        echo "<td>". number_format($val->hasil,2,',','.')."</td>";
                    } else {
                        echo "<td>". number_format($val->hasil,0,',','.')."</td>";
                    }
                    
                    echo "<td>$pick_rjs</td>";
                    echo "</tr>";
                    $totalmc+=$val->jumlah_mesin;
                    $totalhasil+=$val->hasil;
                    $pick_mesin = $pick_rjs * $val->jumlah_mesin;
                    $sum_pick_mesin+=$pick_mesin;
                endforeach;
                echo "<tr>";
                echo "<td colspan='2'><strong>Total</strong></td>";
                echo "<td>".$totalmc."</td>";
                if(fmod($totalhasil, 1) !== 0.00){
                    echo "<td>". number_format($totalhasil,2,',','.')."</td>";
                } else {
                    echo "<td>". number_format($totalhasil,0,',','.')."</td>";
                }
                echo "<td></td>";
                echo "</tr>";

                $rtprod = $totalhasil / $totalmc;
                $rtpick = $sum_pick_mesin / $totalmc;
                
                echo "<tr>";
                echo "<td colspan='2'><strong>Rata-Rata</strong></td>";
                echo "<td></td>";
                if(fmod($rtprod, 1) !== 0.00){
                    echo "<td>". number_format($rtprod,2,',','.')."</td>";
                } else {
                    echo "<td>". number_format($rtprod,0,',','.')."</td>";
                }
                if(fmod($rtpick, 1) !== 0.00){
                    echo "<td>". number_format($rtpick,2,',','.')."</td>";
                } else {
                    echo "<td>". number_format($rtpick,0,',','.')."</td>";
                }
                //echo "<td>PICK</td>";
                echo "</tr>";
            } else {
                echo "<tr><td colspan='5'>Tidak ada data</td></tr>";
            }
        }
        if($pss == "Samatex"){
            $query = $this->data_model->get_byid('dt_produksi_mesin', ['tanggal_produksi'=>$tgl,'lokasi'=>"Samatex"]);
            echo '<tr>
                <td><strong>No</strong></td>
                <td><strong>Konstruksi</strong></td>
                <td><strong>Mesin</strong></td>
                <td><strong>Hasil Produksi</strong></td>
                <td><strong>Pick</strong></td>
            </tr>';
            if($query->num_rows() > 0){
                $totalmc=0;
                $totalhasil=0;
                $sum_pick_mesin = 0;
                foreach($query->result() as $n => $val):
                    echo "<tr>";
                    $num = $n+1;
                    $ks = $val->kode_konstruksi;
                    $pick_rjs = $this->db->query("SELECT kode_konstruksi,pick_samatex FROM tb_konstruksi WHERE kode_konstruksi='$ks'")->row("pick_samatex");
                    echo "<td>".$num."</td>";
                    echo "<td>".$val->kode_konstruksi."</td>";
                    echo "<td>".$val->jumlah_mesin."</td>";
                    if(fmod($val->hasil, 1) !== 0.00){
                        echo "<td>". number_format($val->hasil,2,',','.')."</td>";
                    } else {
                        echo "<td>". number_format($val->hasil,0,',','.')."</td>";
                    }
                    echo "<td>$pick_rjs</td>";
                    echo "</tr>";
                    $totalmc+=$val->jumlah_mesin;
                    $totalhasil+=$val->hasil;
                    $pick_mesin = $pick_rjs * $val->jumlah_mesin;
                    $sum_pick_mesin+=$pick_mesin;
                endforeach;
                echo "<tr>";
                echo "<td colspan='2'><strong>Total</strong></td>";
                echo "<td>".$totalmc."</td>";
                if(fmod($totalhasil, 1) !== 0.00){
                    echo "<td>". number_format($totalhasil,2,',','.')."</td>";
                } else {
                    echo "<td>". number_format($totalhasil,0,',','.')."</td>";
                }
                echo "<td></td>";
                echo "</tr>";

                $rtprod = $totalhasil / $totalmc;
                $rtpick = $sum_pick_mesin / $totalmc;
                
                echo "<tr>";
                echo "<td colspan='2'><strong>Rata-Rata</strong></td>";
                echo "<td></td>";
                if(fmod($rtprod, 1) !== 0.00){
                    echo "<td>". number_format($rtprod,2,',','.')."</td>";
                } else {
                    echo "<td>". number_format($rtprod,0,',','.')."</td>";
                }
                if(fmod($rtpick, 1) !== 0.00){
                    echo "<td>". number_format($rtpick,2,',','.')."</td>";
                } else {
                    echo "<td>". number_format($rtpick,0,',','.')."</td>";
                }
                //echo "<td>PICK</td>";
                echo "</tr>";
            } else {
                echo "<tr><td colspan='5'>Tidak ada data</td></tr>";
            }
        }
        echo "</table>";
  } //end

  function loaddata(){
        $jns = $this->input->post('jns');
        if($jns=="Grey"){
            $jenis = "Grey";
        } elseif($jns=="Finish"){
            $jenis = "Finish";
        } else {
            $jenis = "All";
        }
        $tgl = $this->input->post('tgl');
        $kons = $this->input->post('kons');
        $cekkons = $this->data_model->get_byid('dt_konsumen', ['nama_konsumen'=>$kons]);
        if($cekkons->num_rows() == 1){
            $idcus = $cekkons->row("id_konsumen");
        } else {
            $idcus = "all";
        }
        $ex = explode(' - ', $tgl);
        $ex1 = explode('/', $ex[0]);
        $tanggal1 = $ex1[2]."-".$ex1[0]."-".$ex1[1];
        $ex2 = explode('/', $ex[1]);
        $tanggal2 = $ex2[2]."-".$ex2[0]."-".$ex2[1];
        if($tanggal1 == $tanggal2){
            if($idcus == "all"){
                if($jenis == "All"){
                    $query = $this->db->query("SELECT * FROM view_nota2 WHERE tgl_nota='$tanggal1' ");
                    $query1 = "SELECT * FROM view_nota2 WHERE tgl_nota='$tanggal1' ";
                } else {
                    $query = $this->db->query("SELECT * FROM view_nota2 WHERE jns_fold='$jenis' AND tgl_nota='$tanggal1' ");
                    $query1 = "SELECT * FROM view_nota2 WHERE jns_fold='$jenis' AND tgl_nota='$tanggal1' ";
                }
            } else {
                if($jenis == "All"){
                    $query = $this->db->query("SELECT * FROM view_nota2 WHERE id_customer='$idcus' AND tgl_nota='$tanggal1' ");
                    $query1 = "SELECT * FROM view_nota2 WHERE id_customer='$idcus' AND tgl_nota='$tanggal1' ";
                } else {
                    $query = $this->db->query("SELECT * FROM view_nota2 WHERE id_customer='$idcus' AND jns_fold='$jenis' AND  tgl_nota='$tanggal1' ");
                    $query1 = "SELECT * FROM view_nota2 WHERE id_customer='$idcus' AND jns_fold='$jenis' AND  tgl_nota='$tanggal1' ";
                }
            }
        } else {
            if($idcus == "all"){
                if($jenis == "All"){
                    $query = $this->db->query("SELECT * FROM view_nota2 WHERE tgl_nota BETWEEN '$tanggal1' AND '$tanggal2' ");
                    $query1 = "SELECT * FROM view_nota2 WHERE tgl_nota BETWEEN '$tanggal1' AND '$tanggal2' ";
                } else {
                    $query = $this->db->query("SELECT * FROM view_nota2 WHERE jns_fold='$jenis' AND tgl_nota BETWEEN '$tanggal1' AND '$tanggal2' ");
                    $query1 = "SELECT * FROM view_nota2 WHERE jns_fold='$jenis' AND tgl_nota BETWEEN '$tanggal1' AND '$tanggal2' ";
                }
                
            } else {
                if($jenis == "All"){
                    $query = $this->db->query("SELECT * FROM view_nota2 WHERE id_customer='$idcus' AND tgl_nota BETWEEN '$tanggal1' AND '$tanggal2'  ");
                    $query1 = "SELECT * FROM view_nota2 WHERE id_customer='$idcus' AND tgl_nota BETWEEN '$tanggal1' AND '$tanggal2'  ";
                } else {
                    $query = $this->db->query("SELECT * FROM view_nota2 WHERE id_customer='$idcus' AND jns_fold='$jenis' AND tgl_nota BETWEEN '$tanggal1' AND '$tanggal2'  ");
                    $query1 = "SELECT * FROM view_nota2 WHERE id_customer='$idcus' AND jns_fold='$jenis' AND tgl_nota BETWEEN '$tanggal1' AND '$tanggal2'  ";
                }
            }
        }
        echo "<table>";
        echo "<tr>";
        echo "<td><strong>No.</strong></td>";
        echo "<td><strong>Konsumen</strong></td>";
        echo "<td><strong>Konstruksi</strong></td>";
        echo "<td><strong>Jumlah</strong></td>";
        echo "</tr>";
        $n=1;
        $total = 0;
        if($query->num_rows() > 0){
            $ar_konstruksi = array();
            foreach($query->result() as $val):
                echo "<tr>";
                echo "<td>".$n."</td>";
                echo "<td>".$val->nama_konsumen."</td>";
                if (in_array($val->konstruksi, $ar_konstruksi)) {} else {
                    $ar_konstruksi[]=$val->konstruksi;
                }
                echo "<td>".$val->konstruksi."</td>";
                if(fmod($val->total_panjang, 1) !== 0.00){
                    $ajl3 = number_format($val->total_panjang,2,',','.');
                } else {
                    $ajl3 = number_format($val->total_panjang,0,',','.');
                }
                $total+=$val->total_panjang;
                echo "<td>".$ajl3."</td>";
                echo "</tr>";
                $n++;
            endforeach;
            if(fmod($total, 1) !== 0.00){
                $ttl = number_format($total,2,',','.');
            } else {
                $ttl = number_format($total,0,',','.');
            }
            echo "<tr>";
            echo "<td colspan='3'><strong>Total</strong></td>";
            echo "<td><strong>".$ttl."</strong></td>";
            echo "</tr>";
            echo "<tr><td colspan='4'>&nbsp;</td></tr>";
            echo "<tr>";
            echo "<td colspan='4'><strong>Resume</strong></td>";
            foreach($ar_konstruksi as $bal){
                echo "<tr>";
                echo "<td></td><td></td>";
                echo "<td>$bal</td>";
                $ex_query = explode('* FROM view_nota2 WHERE', $query1);
                $new_query = "SELECT SUM(total_panjang) AS ukr FROM view_nota2 WHERE konstruksi='$bal' AND ".$ex_query[1]."";
                $ukr = $this->db->query($new_query)->row("ukr");
                if(fmod($ukr, 1) !== 0.00){
                    $ukr2 = number_format($ukr,2,',','.');
                } else {
                    $ukr2 = number_format($ukr,0,',','.');
                }
                echo "<td>$ukr2</td>";
                echo "</tr>";
                //echo "<tr><td colspan='4'>$new_query</td></tr>";
            }
            echo "<tr>";
        } else {
            echo "<tr>";
            echo "<td colspan='4'>Belum ada penjualan $jenis</td>";
            echo "</tr>";
        }
        //echo "<tr><td>".$query1."</td></tr>";
        echo "</table>";
        
  } //
  function proseslogin(){
        $username = $this->data_model->clean($this->input->post('username'));
        //if($username == "aidi" OR $username == "Hamid" OR $username == "hamid"){
            $password = $this->input->post('password');
            if($username!="" AND $password!=""){
                $ceklogin = $this->data_model->get_byid('user', ['username'=>$username,'password'=>sha1($password),'hak_akses'=>'Manager']);
                if($ceklogin->num_rows() == 1){
                    $dt = $ceklogin->row_array();
                    $data_session = array(
                        'nama'  => $dt['nama_user'],
                        'username'=> $dt['username'],
                        'password' => $dt['password'],
                        'hak'     => $dt['hak_akses'],
                        'departement' => $dt['departement'],
                        'mng_dash'=> 'manager_dash'
                    );
                    $this->session->set_userdata($data_session);
                    redirect(base_url('dash-manager'));
                } else {
                    echo "Username dan Password anda tidak cocok.";
                }
            } else {
                echo "Anda harus mengisi username dan password.";
            }
        //} else {
            //$this->load->view('under_maintainance');
        //}
  } //end

  function halamanutama2(){
        if($this->session->userdata('mng_dash') == "manager_dash"){
            $this->load->view('manager/showdashboard');
        } else {
            echo "<a href='".base_url('all-dashboard')."' style='text-decoration:none;'><div style='width:100%;height:100vh;display:flex;justify-content:center;align-items:center;font-size:3em;'>Akses diblokir</div></a>";
        }
  } //end
  function halamanutama(){
    if($this->session->userdata('mng_dash') == "manager_dash"){
        $userName = $this->session->userdata('username');
            if($userName=="isti" OR $userName=="anding"){
                $this->load->view('manager/showdashboard');
            } else {
            $this->load->view('manager/showdashboard'); }
        //$this->load->view('manager/showdashboard');
    } else {
        $this->load->view('under_maintainance');
    }
} //end

  function penjualangrey(){
        $this->load->view('manager/penjualanGrey');
  } //end

  function penjualanfinish(){
        $this->load->view('manager/penjualanFinish');
  } //end
  function penjualanall(){
        $this->load->view('manager/penjualanAll');
  } //end-
  function stokallpusatex(){
        $this->load->view('manager/stok_all_pusatex');
  } //end
  function stokallpusatex2(){
        $this->load->view('manager/stok_all_pusatex2');
  } //end
  function stokallpusatex3(){
    $this->load->view('manager/stok_all_pusatex3');
  } //end
  
  function stokall(){
        $this->load->view('manager/stok_all');
  } //end
  function stokgrey(){
        $this->load->view('manager/stok_grey');
  } //end
  function stokonrjs(){
        $this->load->view('manager/stok_onrjs');
        //echo "tes";
  } //end
  function allpick(){
        $this->load->view('manager/create_pick');
        //echo "tes";
  } //end
  function stokfinish(){
        $this->load->view('manager/stok_finish');
  } //end-
  function stokonpkg(){
    $this->load->view('manager/stok_onpkg');
  } //end-
  function inspect_all(){
        $this->load->view('manager/inspect_all');
  } //end-
  function inspect_grey(){
        $this->load->view('manager/inspect_grey');
  } //end-
  function inspect_finish(){
        $this->load->view('manager/inspect_finish');
  } //end-
  function inspect_rjs(){
        $this->load->view('manager/inspect_rjs');
  } //end-
  function folding(){
        $this->load->view('manager/hasil_folding');
  } //end-
  function folding_grey(){
        $this->load->view('manager/hasil_folding_grey');
  } //end-
  function folding_finish(){
        $this->load->view('manager/hasil_folding_finish');
  } //end-
  function ajl_rindang(){
        $this->load->view('manager/ajl_rindang');
  } //end-
  function ajl_samatex(){
        $this->load->view('manager/ajl_samatex');
  } //end-
  

  function loaddataFolding(){
    $jns = $this->input->post('jns');
    $tgl = $this->input->post('tgl');
    if($tgl==""){
        $tgl = date('Y-m-d');
    }
    //echo $jns."-".$tgl;
        if($jns == "Grey"){
            //$query = $this->data_model->get_byid('data_fol', ['jns_fold'=>'Grey','tgl'=>$tgl]);
            $query = $this->db->query("SELECT * FROM data_fol WHERE jns_fold='Grey' AND tgl='$tgl' GROUP BY konstruksi");
            foreach($query->result() as $val){
                $kons = $val->konstruksi;
                $hasil = $this->db->query("SELECT SUM(ukuran) AS ukr FROM data_fol WHERE konstruksi='$kons' AND jns_fold='Grey' AND tgl='$tgl' ")->row("ukr");
                $hasil = floatval($hasil);
                $hasil = round($hasil,1);
                if($hasil == floor($hasil)){
                    $hasil = number_format($hasil,0,',','.');
                } else {
                    $hasil = number_format($hasil,2,',','.');
                }
                    echo '<div class="card-kons">';
                    echo '<div>'.$kons.'</div>'; 
                    echo '<div>'.$hasil.'</div>';
                    echo '</div>';
            }
        } elseif($jns == "Finish"){
            $query = $this->db->query("SELECT * FROM data_fol WHERE jns_fold='Finish' AND tgl='$tgl' GROUP BY konstruksi");
            foreach($query->result() as $val){
                $kons = $val->konstruksi;
                $chto = $this->data_model->get_byid('tb_konstruksi',['kode_konstruksi'=>$kons])->row("chto");
                if($chto=="null" OR $chto=="NULL"){
                    $_thisKons22 = $val->konstruksi;
                } else {
                    $_thisKons22 = $chto;
                }
                $hasil = $this->db->query("SELECT SUM(ukuran) AS ukr FROM data_fol WHERE konstruksi='$kons' AND jns_fold='Finish' AND tgl='$tgl' ")->row("ukr");
                $hasil = round($hasil,1);
                if($hasil == floor($hasil)){
                    $hs = number_format($hasil,0,',','.');
                } else {
                    $hs = number_format($hasil,2,',','.');
                }
                    echo '<div class="card-kons">';
                    echo '<div>'.$_thisKons22.'</div>'; 
                    echo '<div>'.$hs.'</div>';
                    echo '</div>';
            }
        } elseif($jns == "RJS"){
            $query = $this->data_model->get_byid('data_produksi', ['tgl'=>$tgl, 'dep'=>'RJS']);
            foreach($query->result() as $val){
                $kons = $val->kode_konstruksi;
                $fg = $val->prod_fg;
                if(floatval($fg) > 0){
                    if(fmod($fg, 1) !== 0.00){
                        $fg2 = number_format($fg,2,',','.');
                    } else {
                        $fg2 = number_format($fg,0,',','.');
                    }
                    echo '<div class="card-kons">';
                    echo '<div>'.$kons.'</div>'; 
                    echo '<div>'.$fg2.'</div>';
                    echo '</div>';
                }
            }
        } else {
            $query = $this->db->query("SELECT * FROM data_fol WHERE jns_fold='Grey' AND tgl='$tgl' GROUP BY konstruksi");
            foreach($query->result() as $val){
                $kons = $val->konstruksi;
                $hasil = $this->db->query("SELECT SUM(ukuran) AS ukr FROM data_fol WHERE konstruksi='$kons' AND jns_fold='Grey' AND tgl='$tgl' ")->row("ukr");
                $hasil = floatval($hasil);
                $hasil = round($hasil,1);
                if($hasil == floor($hasil)){
                    $hasil = number_format($hasil,0,',','.');
                } else {
                    $hasil = number_format($hasil,2,',','.');
                }
                    echo '<div class="card-kons">';
                    echo '<div>'.$kons.'</div>'; 
                    echo '<div>'.$hasil.'</div>';
                    echo '</div>';
            }
            $query = $this->db->query("SELECT * FROM data_fol WHERE jns_fold='Finish' AND tgl='$tgl' GROUP BY konstruksi");
            foreach($query->result() as $val){
                $kons = $val->konstruksi;
                $chto = $this->data_model->get_byid('tb_konstruksi',['kode_konstruksi'=>$kons])->row("chto");
                if($chto=="null" OR $chto=="NULL"){
                    $_thisKons22 = $val->konstruksi;
                } else {
                    $_thisKons22 = $chto;
                }
                $hasil = $this->db->query("SELECT SUM(ukuran) AS ukr FROM data_fol WHERE konstruksi='$kons' AND jns_fold='Finish' AND tgl='$tgl' ")->row("ukr");
                if($hasil == floor($hasil)){
                    $hs = number_format($hasil,0,',','.');
                } else {
                    $hs = number_format($hasil,2,',','.');
                }
                    echo '<div class="card-kons">';
                    echo '<div>'.$_thisKons22.'</div>'; 
                    echo '<div>'.$hs.'</div>';
                    echo '</div>';
                    
            }
        }
  } //end
  function loaddataInspect2(){
      echo "Maaf, untuk sementara Sedang proses perbaikan.";
  }
  
  function loaddataInspect3(){
        $tgl = $this->input->post('tgl');
        $tgl_sebelumnya = date("Y-m-d", strtotime($tgl . " -1 day"));
        $tgl_sesudahnya = date("Y-m-d", strtotime($tgl . " +1 day"));
        $ex = explode('-', $tgl);
        $printTgl = $ex[2]." ".$this->data_model->printBln($ex[1])." ".$ex[0];
        
        $no=1;
        $query = $this->db->query("SELECT * FROM data_ig WHERE tanggal='$tgl' AND dep='RJS' AND shift_op!='31' ");
        $query2 = $this->db->query("SELECT * FROM data_ig WHERE tanggal='$tgl_sesudahnya' AND dep='RJS' AND shift_op='31' ");
        
        //cek data shift 1 di tanggal tersebut
        $cekshift1 = $this->db->query("SELECT konstruksi,tanggal,dep FROM data_ig WHERE tanggal='$tgl' AND dep='RJS' AND shift_op='1'");
        if($cekshift1->num_rows() > 0){
        echo "<div style='width:100%;display:flex;margin-bottom:10px;'>Shift 1</div>";
            $_arkons = array();
            foreach($cekshift1->result() as $sf1){
                $sf1_kons = strtoupper($sf1->konstruksi);
                if(in_array($sf1_kons, $_arkons)){
    
                } else {
                        $_arkons[]=$sf1_kons;
                }
            }
            foreach($_arkons as $kons1){
                echo '<div class="card-kons">';
                $cekshift_ukr = $this->db->query("SELECT SUM(ukuran_ori) AS ukr FROM data_ig WHERE konstruksi='$kons1' AND tanggal='$tgl' AND dep='RJS' AND shift_op='1'")->row("ukr");
                echo '<div>'.$kons1.'</div>'; 
                echo '<div>'.number_format($cekshift_ukr).'</div>';
                echo '</div>';
            }
        } else {
            echo "Belum ada data";
        }
        
        //cek data shift 2 di tanggal tersebut
        $cekshift2 = $this->db->query("SELECT konstruksi,tanggal,dep FROM data_ig WHERE tanggal='$tgl' AND dep='RJS' AND shift_op='2'");
        if($cekshift2->num_rows() > 0){
        echo "<div style='width:100%;display:flex;margin-bottom:10px;'>Shift 2</div>";
            $_arkons2 = array();
            foreach($cekshift2->result() as $sf2){
                $sf2_kons = strtoupper($sf2->konstruksi);
                if(in_array($sf2_kons, $_arkons2)){
    
                } else {
                        $_arkons2[]=$sf2_kons;
                }
            }
            foreach($_arkons2 as $kons2){
                echo '<div class="card-kons">';
                $cekshift_ukr = $this->db->query("SELECT SUM(ukuran_ori) AS ukr FROM data_ig WHERE konstruksi='$kons2' AND tanggal='$tgl' AND dep='RJS' AND shift_op='2'")->row("ukr");
                echo '<div>'.$kons2.'</div>'; 
                echo '<div>'.number_format($cekshift_ukr).'</div>';
                echo '</div>';
            }
        } else {
            echo "";
        }
        
        //cek data shift 3 di tanggal tersebut
        $cekshift3 = $this->db->query("SELECT konstruksi,tanggal,dep FROM data_ig WHERE tanggal = '$tgl' AND dep='RJS' AND shift_op='3'");
        if($cekshift3->num_rows() > 0){
        echo "<div style='width:100%;display:flex;margin-bottom:10px;'>Shift 3</div>";
            $_arkons3 = array();
            $_arkons31 = array();
            foreach($cekshift3->result() as $sf3){
                $sf3_kons = strtoupper($sf3->konstruksi);
                if(in_array($sf3_kons, $_arkons3)){
    
                } else {
                        $_arkons3[]=$sf3_kons;
                }
                $dt = "'".$sf3->konstruksi."'";
                if(in_array($dt, $_arkons31)){
    
                } else {
                        $_arkons31[]=$dt;
                }
            }
            foreach($_arkons3 as $kons3){
                echo '<div class="card-kons">';
                $cekshift_ukr = $this->db->query("SELECT SUM(ukuran_ori) AS ukr FROM data_ig WHERE konstruksi='$kons3' AND tanggal = '$tgl' AND dep='RJS' AND shift_op='3' ")->row("ukr");
                $cekshift_ukr2 = $this->db->query("SELECT SUM(ukuran_ori) AS ukr FROM data_ig WHERE konstruksi='$kons3' AND tanggal = '$tgl_sesudahnya' AND dep='RJS' AND shift_op='31' ")->row("ukr");
                $cekshift_ukr3 = $cekshift_ukr + $cekshift_ukr2;
                echo '<div>'.$kons3.'</div>'; 
                echo '<div>'.number_format($cekshift_ukr3).'</div>';
                echo '</div>';
            }
            
            $im_kons = implode(',', $_arkons31);
            $cekshift_again = $this->db->query("SELECT konstruksi,tanggal,dep FROM data_ig WHERE konstruksi NOT IN($im_kons) AND tanggal = '$tgl_sesudahnya' AND dep='RJS' AND shift_op='31'");
            $ar_kons_9 = array();
            foreach($cekshift_again->result() as $ckag){
                $ckag_kons = strtoupper($ckag->konstruksi);
                if(in_array($ckag_kons, $ar_kons_9)){
    
                } else {
                        $ar_kons_9[]=$ckag_kons;
                }
            }
            foreach($ar_kons_9 as $kons9){
                echo '<div class="card-kons">';
                $cekshift_ukr = $this->db->query("SELECT SUM(ukuran_ori) AS ukr FROM data_ig WHERE konstruksi='$kons9' AND tanggal = '$tgl_sesudahnya' AND dep='RJS' AND shift_op='31' ")->row("ukr");
                echo '<div>'.$kons9.'</div>'; 
                echo '<div>'.number_format($cekshift_ukr).'</div>';
                echo '</div>';
            }
            //echo $im_kons;
        } else {
            $cekshift31 = $this->db->query("SELECT konstruksi,tanggal,dep FROM data_ig WHERE tanggal = '$tgl_sesudahnya' AND dep='RJS' AND shift_op='31'");
            if($cekshift31->num_rows() > 0){
            echo "<div style='width:100%;display:flex;margin-bottom:10px;'>Shift 3</div>";
                $_arkons31 = array();
                foreach($cekshift31->result() as $sf31){
                    $sf31_kons = strtoupper($sf31->konstruksi);
                    if(in_array($sf31_kons, $_arkons31)){
        
                    } else {
                            $_arkons31[]=$sf31_kons;
                    }
                }
                foreach($_arkons31 as $kons31){
                    echo '<div class="card-kons">';
                    $cekshift_ukr = $this->db->query("SELECT SUM(ukuran_ori) AS ukr FROM data_ig WHERE konstruksi='$kons31' AND tanggal = '$tgl_sesudahnya' AND dep='RJS' AND shift_op='31' ")->row("ukr");
                    echo '<div>'.$kons31.'</div>'; 
                    echo '<div>'.number_format($cekshift_ukr).'</div>';
                    echo '</div>';
                }
                // $im_kons = implode(',', $_arkons3);
                // echo $im_kons;
            } else {
                echo "";
            }
        }
       
  } //end

  function loaddataInspect(){
    $jns = $this->input->post('jns');
    $tgl = $this->input->post('tgl');
        if($jns == "Grey"){
            $query = $this->db->query("SELECT id_data,konstruksi,tanggal FROM data_ig WHERE tanggal='$tgl' GROUP BY konstruksi");
            foreach($query->result() as $val){
                $kons = $val->konstruksi;
                $hasil = $this->db->query("SELECT SUM(ukuran_ori) AS ukr FROM data_ig WHERE konstruksi='$kons' AND tanggal = '$tgl'")->row("ukr");
                if(floatval($hasil) > 0){
                    if(fmod($hasil, 1) !== 0.00){
                        $hasil2 = number_format($hasil,2,',','.');
                    } else {
                        $hasil2 = number_format($hasil,0,',','.');
                    }
                    echo '<div class="card-kons">';
                    echo '<div>'.$kons.'</div>'; 
                    echo '<div>'.$hasil2.'</div>';
                    echo '</div>';
                }
            }
        } elseif($jns == "Finish"){
            //$query = $this->data_model->get_byid('data_produksi', ['tgl'=>$tgl, 'dep'=>'Samatex']);
            $query = $this->db->query("SELECT id_infs,tgl_potong,konstruksi FROM data_if WHERE tgl_potong='$tgl' GROUP BY konstruksi");
            foreach($query->result() as $val){
                $kons = $val->konstruksi;
                $hasil = $this->db->query("SELECT SUM(ukuran_ori) AS ukr FROM data_if WHERE konstruksi='$kons' AND tgl_potong = '$tgl'")->row("ukr");
                $ff = $hasil / 0.9144;
                if(floatval($ff) > 0){
                    if(fmod($ff, 1) !== 0.00){
                        $ff2 = number_format($ff,2,',','.');
                    } else {
                        $ff2 = number_format($ff,0,',','.');
                    }
                    $chto = $this->data_model->get_byid('tb_konstruksi',['kode_konstruksi'=>$kons])->row("chto");
                    if($chto=="null" OR $chto=="NULL"){
                        $_thisKons22 = $val->konstruksi;
                    } else {
                        $_thisKons22 = $chto;
                    }
                    echo '<div class="card-kons">';
                    echo '<div>'.$_thisKons22.'</div>'; 
                    echo '<div>'.$ff2.'</div>';
                    echo '</div>';
                }
            }
        } elseif($jns == "RJS"){
            $query = $this->data_model->get_byid('data_produksi', ['tgl'=>$tgl, 'dep'=>'RJS']);
            foreach($query->result() as $val){
                $kons = $val->kode_konstruksi;
                $fg = $val->prod_ig;
                if(floatval($fg) > 0){
                    if(fmod($fg, 1) !== 0.00){
                        $fg2 = number_format($fg,2,',','.');
                    } else {
                        $fg2 = number_format($fg,0,',','.');
                    }
                    echo '<div class="card-kons">';
                    echo '<div>'.$kons.'</div>'; 
                    echo '<div>'.$fg2.'</div>';
                    echo '</div>';
                }
            }
        } else {
            $query = $this->db->query("SELECT id_data,konstruksi,tanggal FROM data_ig WHERE tanggal='$tgl' GROUP BY konstruksi");
            foreach($query->result() as $val){
                $kons = $val->konstruksi;
                $hasil = $this->db->query("SELECT SUM(ukuran_ori) AS ukr FROM data_ig WHERE konstruksi='$kons' AND tanggal = '$tgl'")->row("ukr");
                if(floatval($hasil) > 0){
                    if(fmod($hasil, 1) !== 0.00){
                        $hasil2 = number_format($hasil,2,',','.');
                    } else {
                        $hasil2 = number_format($hasil,0,',','.');
                    }
                    echo '<div class="card-kons">';
                    echo '<div>'.$kons.'</div>'; 
                    echo '<div>'.$hasil2.'</div>';
                    echo '</div>';
                }
            }
            $query = $this->db->query("SELECT id_infs,tgl_potong,konstruksi FROM data_if WHERE tgl_potong='$tgl' GROUP BY konstruksi");
            foreach($query->result() as $val){
                $kons = $val->konstruksi;
                $hasil = $this->db->query("SELECT SUM(ukuran_ori) AS ukr FROM data_if WHERE konstruksi='$kons' AND tgl_potong = '$tgl'")->row("ukr");
                $ff = $hasil / 0.9144;
                if(floatval($ff) > 0){
                    if(fmod($ff, 1) !== 0.00){
                        $ff2 = number_format($ff,2,',','.');
                    } else {
                        $ff2 = number_format($ff,0,',','.');
                    }
                    $chto = $this->data_model->get_byid('tb_konstruksi',['kode_konstruksi'=>$kons])->row("chto");
                    if($chto=="null" OR $chto=="NULL"){
                        $_thisKons22 = $val->konstruksi;
                    } else {
                        $_thisKons22 = $chto;
                    }
                    echo '<div class="card-kons">';
                    echo '<div>'.$_thisKons22.'</div>'; 
                    echo '<div>'.$ff2.'</div>';
                    echo '</div>';
                }
            }
        }
  }
  
  function loaddatastokonrjs(){
        $alldata = $this->db->query("SELECT konstruksi,ukuran_ori,loc_now FROM `data_ig` WHERE ukuran_ori>0 AND loc_now REGEXP '^RJS[0-9]+$' GROUP BY konstruksi");
        $stok = 0;
        foreach($alldata->result() as $kons):
            $_ks = $kons->konstruksi;
            $_jm = $this->db->query("SELECT SUM(ukuran_ori) AS jml FROM data_ig WHERE konstruksi='$_ks' AND loc_now REGEXP '^RJS[0-9]+$'")->row("jml");
            if($_jm > 0){
                $showStok = number_format($_jm,0,',','.');
            } else {
                $showStok = 0;
            }
            ?>
            <div class="card-kons" onclick="openModal('<?=$_ks;?>')">
                <div><?=$_ks;?></div>
                <div><?=$showStok;?></div>
            </div>
            <?php
        endforeach;
        
  }
  function showStokRjsByKons(){
        $kons       = $this->input->post('kons');
        $totalStok  = $this->db->query("SELECT SUM(ukuran_ori) AS jml FROM data_ig WHERE konstruksi='$kons' AND ukuran_ori>3 AND loc_now REGEXP '^RJS[0-9]+$'")->row("jml");
        $oriStok    = $this->db->query("SELECT SUM(ukuran_ori) AS jml FROM data_ig WHERE konstruksi='$kons' AND ukuran_ori>33 AND loc_now REGEXP '^RJS[0-9]+$'")->row("jml");
        $bpStok    = $this->db->query("SELECT SUM(ukuran_ori) AS jml FROM data_ig WHERE konstruksi='$kons' AND ukuran_ori>20 AND ukuran_ori<34 AND loc_now REGEXP '^RJS[0-9]+$'")->row("jml");
        $bcStok    = $this->db->query("SELECT SUM(ukuran_ori) AS jml FROM data_ig WHERE konstruksi='$kons' AND ukuran_ori>3 AND ukuran_ori<20 AND loc_now REGEXP '^RJS[0-9]+$'")->row("jml");
        $avalStok    = $this->db->query("SELECT SUM(ukuran_ori) AS jml FROM data_ig WHERE konstruksi='$kons' AND ukuran_ori<4  AND loc_now REGEXP '^RJS[0-9]+$'")->row("jml");

        $totalStokRoll  = $this->db->query("SELECT konstruksi,loc_now FROM data_ig WHERE konstruksi='$kons' AND ukuran_ori>3 AND loc_now REGEXP '^RJS[0-9]+$'")->num_rows();
        $oriStokRoll    = $this->db->query("SELECT konstruksi,ukuran_ori,loc_now FROM data_ig WHERE konstruksi='$kons' AND ukuran_ori>33 AND loc_now REGEXP '^RJS[0-9]+$'")->num_rows();
        $bpStokRoll    = $this->db->query("SELECT konstruksi,ukuran_ori,loc_now FROM data_ig WHERE konstruksi='$kons' AND ukuran_ori>20 AND ukuran_ori<34 AND loc_now REGEXP '^RJS[0-9]+$'")->num_rows();
        $bcStokRoll   = $this->db->query("SELECT konstruksi,ukuran_ori,loc_now FROM data_ig WHERE konstruksi='$kons' AND ukuran_ori>3 AND ukuran_ori<20 AND loc_now REGEXP '^RJS[0-9]+$'")->num_rows();
        $avalStokRoll    = $this->db->query("SELECT konstruksi,ukuran_ori,loc_now FROM data_ig WHERE konstruksi='$kons' AND ukuran_ori<4  AND loc_now REGEXP '^RJS[0-9]+$'")->num_rows();

        $jgStok = 0;
        $jgStokRoll = 0;
        ?>
        <div class="tables" style="margin-top:20px;">
            <table border="1">
                <tr>
                    <th>TOTAL STOK</th>
                    <th>ORI</th>
                    <th>BP</th>
                    <th>BC</th>
                    <th>JG</th>
                </tr>
                <tr>
                    <td style="text-align:center;"><?=number_format($totalStok,0,',','.');?></td>
                    <td style="text-align:center;"><?=number_format($oriStok,0,',','.');?></td>
                    <td style="text-align:center;"><?=number_format($bpStok,0,',','.');?></td>
                    <td style="text-align:center;"><?=number_format($bcStok,0,',','.');?></td>
                    <td style="text-align:center;"><?=number_format($jgStok,0,',','.');?></td>
                </tr>
                <tr>
                    <td colspan="5"></td>
                </tr>
                <tr>
                    <th>TOTAL ROLL</th>
                    <th>ORI</th>
                    <th>BP</th>
                    <th>BC</th>
                    <th>JG</th>
                </tr>
                <tr>
                    <td style="text-align:center;"><?=number_format($totalStokRoll,0,',','.');?></td>
                    <td style="text-align:center;"><?=number_format($oriStokRoll,0,',','.');?></td>
                    <td style="text-align:center;"><?=number_format($bpStokRoll,0,',','.');?></td>
                    <td style="text-align:center;"><?=number_format($bcStokRoll,0,',','.');?></td>
                    <td style="text-align:center;"><?=number_format($jgStokRoll,0,',','.');?></td>
                </tr>
            </table>
        </div> 
        <div style="width:100%;text-align:center;margin-top:20px;">Stok Aval : <?=number_format($avalStok,0,',','.');?></div>
        <?php
  }

  function loaddatastok(){
        $jns = $this->input->post('jns');
        if($jns == "Grey"){
            $_kons = $this->db->query("SELECT DISTINCT konstruksi FROM data_fol WHERE jns_fold='Grey' AND (posisi='Pusatex' OR posisi LIKE '%PKT%')");
            foreach ($_kons->result() as $val) {
                $_thisKons = $val->konstruksi;
                $stok = $this->db->query("SELECT SUM(ukuran) AS jml FROM data_fol WHERE konstruksi='$_thisKons' AND jns_fold='Grey' AND posisi='Pusatex'")->row("jml");
                //$stok_onpkg = $this->db->query("SELECT SUM(ukuran) AS jml FROM data_fol WHERE konstruksi='$_thisKons' AND jns_fold='Grey' AND posisi LIKE '%PKT%'")->row("jml");
                $stok_onpkgall = $this->data_model->get_byid('new_tb_packinglist',['kode_konstruksi'=>$_thisKons,'siap_jual'=>'y','no_sj'=>'NULL']);
                $stok_onpkg = 0;
                foreach($stok_onpkgall->result() as $rts){
                    $nss = $rts->ttl_panjang;
                    $stok_onpkg = $stok_onpkg+$nss;
                }
                if(floatval($stok) > 0){
                    if(floor($stok)==$stok){
                        $_thisStok = number_format($stok, 0, ',', '.');
                    } else {
                        $_thisStok = number_format($stok, 2, ',', '.');
                    }
                } else {
                    $_thisStok = 0;
                }
                if(floatval($stok_onpkg) > 0){
                    if(floor($stok_onpkg)==$stok_onpkg){
                        $_thisstok_onpkg = number_format($stok_onpkg, 0, ',', '.');
                    } else {
                        $_thisstok_onpkg = number_format($stok_onpkg, 2, ',', '.');
                    }
                } else {
                    $_thisstok_onpkg = 0;
                }
                ?>
                <div class="card-kons">
                    <div onclick="openModal('<?=$_thisKons;?>','Grey','null')"><?=$_thisKons;?></div>
                    <div onclick="openModal('<?=$_thisKons;?>','Grey','null')"><?=$_thisStok;?></div>
                    <div style="background:#ccc;color:red;padding:3px 0;font-size:13px;" onclick="tesh('<?=$_thisKons;?>')"><?=$_thisstok_onpkg;?></div>
                </div>
                <?php
            }
            
        } elseif($jns == "Finish"){
            $_kons2 = $this->db->query("SELECT DISTINCT konstruksi FROM data_fol WHERE jns_fold='Finish' AND (posisi='Pusatex' OR posisi LIKE '%PKT%')");
            foreach ($_kons2->result() as $val2) {
                $chto = $this->data_model->get_byid('tb_konstruksi',['kode_konstruksi'=>$val2->konstruksi])->row("chto");
                if($chto=="null" OR $chto=="NULL"){
                    $_thisKons22 = $val2->konstruksi;
                } else {
                    $_thisKons22 = $chto;
                }
                $_thisKons = $val2->konstruksi;
                $stok = $this->db->query("SELECT SUM(ukuran) AS jml FROM data_fol WHERE konstruksi='$_thisKons' AND jns_fold='Finish' AND posisi='Pusatex'")->row("jml");
                $stok = round($stok,1);
                //$stok_onpkg = $this->db->query("SELECT SUM(ukuran) AS jml FROM data_fol WHERE konstruksi='$_thisKons' AND jns_fold='Finish' AND posisi LIKE '%PKT%'")->row("jml");
                $stok_onpkgall = $this->data_model->get_byid('new_tb_packinglist',['kode_konstruksi'=>$_thisKons22,'siap_jual'=>'y','no_sj'=>'NULL']);
                $stok_onpkg = 0;
                foreach($stok_onpkgall->result() as $rts){
                    $nss = $rts->ttl_panjang;
                    $stok_onpkg = $stok_onpkg+$nss;
                }
                if(floatval($stok) > 0){
                    if(floor($stok)==$stok){
                        $_thisStok = number_format($stok, 0, ',', '.');
                    } else {
                        $_thisStok = number_format($stok, 2, ',', '.');
                    }
                } else {
                    $_thisStok = 0;
                }
                if(floatval($stok_onpkg) > 0){
                    if(floor($stok_onpkg)==$stok_onpkg){
                        $_thisstok_onpkg = number_format($stok_onpkg, 0, ',', '.');
                    } else {
                        $_thisstok_onpkg = number_format($stok_onpkg, 2, ',', '.');
                    }
                } else {
                    $_thisstok_onpkg = 0;
                }
                ?>
                <div class="card-kons">
                    <div onclick="openModal('<?=$_thisKons;?>','Finish','<?=$_thisKons22;?>')"><?=$_thisKons22;?></div>
                    <div onclick="openModal('<?=$_thisKons;?>','Finish','<?=$_thisKons22;?>')"><?=$_thisStok;?></div>
                    <div style="background:#ccc;color:red;padding:3px 0;font-size:13px;" onclick="tesh('<?=$_thisKons22;?>')"><?=$_thisstok_onpkg;?></div>
                </div>
                <?php
                
            }
        } else {
            $_kons = $this->db->query("SELECT DISTINCT konstruksi FROM data_fol WHERE jns_fold='Grey' AND (posisi='Pusatex' OR posisi LIKE '%PKT%')");
            foreach ($_kons->result() as $val) {
                $_thisKons = $val->konstruksi;
                $stok = $this->db->query("SELECT SUM(ukuran) AS jml FROM data_fol WHERE konstruksi='$_thisKons' AND jns_fold='Grey' AND posisi='Pusatex'")->row("jml");
                //$stok_onpkg = $this->db->query("SELECT SUM(ukuran) AS jml FROM data_fol WHERE konstruksi='$_thisKons' AND jns_fold='Grey' AND posisi LIKE '%PKT%'")->row("jml");
                $stok_onpkgall = $this->data_model->get_byid('new_tb_packinglist',['kode_konstruksi'=>$_thisKons,'siap_jual'=>'y','no_sj'=>'NULL']);
                $stok_onpkg = 0;
                foreach($stok_onpkgall->result() as $rts){
                    $nss = $rts->ttl_panjang;
                    $stok_onpkg = $stok_onpkg+$nss;
                }
                if(floatval($stok) > 0){
                    if(floor($stok)==$stok){
                        $_thisStok = number_format($stok, 0, ',', '.');
                    } else {
                        $_thisStok = number_format($stok, 2, ',', '.');
                    }
                } else {
                    $_thisStok = 0;
                }
                if(floatval($stok_onpkg) > 0){
                    if(floor($stok_onpkg)==$stok_onpkg){
                        $_thisstok_onpkg = number_format($stok_onpkg, 0, ',', '.');
                    } else {
                        $_thisstok_onpkg = number_format($stok_onpkg, 2, ',', '.');
                    }
                } else {
                    $_thisstok_onpkg = 0;
                }
                
                ?>
                <div class="card-kons">
                    <div onclick="openModal('<?=$_thisKons;?>','Grey','null')"><?=$_thisKons;?></div>
                    <div onclick="openModal('<?=$_thisKons;?>','Grey','null')"><?=$_thisStok;?></div>
                    <div style="background:#ccc;color:red;padding:3px 0;font-size:13px;" onclick="tesh('<?=$_thisKons;?>')"><?=$_thisstok_onpkg;?></div>
                </div>
                <?php
                
            }
            $_kons2 = $this->db->query("SELECT DISTINCT konstruksi FROM data_fol WHERE jns_fold='Finish' AND (posisi='Pusatex' OR posisi LIKE '%PKT%')");
            foreach ($_kons2->result() as $val2) {
                $chto = $this->data_model->get_byid('tb_konstruksi',['kode_konstruksi'=>$val2->konstruksi])->row("chto");
                if($chto=="null" OR $chto=="NULL"){
                    $_thisKons22 = $val2->konstruksi;
                } else {
                    $_thisKons22 = $chto;
                }
                $_thisKons = $val2->konstruksi;
                $stok = $this->db->query("SELECT SUM(ukuran) AS jml FROM data_fol WHERE konstruksi='$_thisKons' AND jns_fold='Finish' AND posisi='Pusatex'")->row("jml");
                //$stok_onpkg = $this->db->query("SELECT SUM(ukuran) AS jml FROM data_fol WHERE konstruksi='$_thisKons' AND jns_fold='Finish' AND posisi LIKE '%PKT%'")->row("jml");
                $stok_onpkgall = $this->data_model->get_byid('new_tb_packinglist',['kode_konstruksi'=>$_thisKons22,'siap_jual'=>'y','no_sj'=>'NULL']);
                $stok_onpkg = 0;
                foreach($stok_onpkgall->result() as $rts){
                    $nss = $rts->ttl_panjang;
                    $stok_onpkg = $stok_onpkg+$nss;
                }
                if(floatval($stok) > 0){
                    if(floor($stok)==$stok){
                        $_thisStok = number_format($stok, 0, ',', '.');
                    } else {
                        $_thisStok = number_format($stok, 2, ',', '.');
                    }
                } else {
                    $_thisStok = 0;
                }
                if(floatval($stok_onpkg) > 0){
                    if(floor($stok_onpkg)==$stok_onpkg){
                        $_thisstok_onpkg = number_format($stok_onpkg, 0, ',', '.');
                    } else {
                        $_thisstok_onpkg = number_format($stok_onpkg, 2, ',', '.');
                    }
                } else {
                    $_thisstok_onpkg = 0;
                }
                ?>
                <div class="card-kons">
                    <div onclick="openModal('<?=$_thisKons;?>','Finish','<?=$_thisKons22;?>')"><?=$_thisKons22;?></div>
                    <div onclick="openModal('<?=$_thisKons;?>','Finish','<?=$_thisKons22;?>')"><?=$_thisStok;?></div>
                    <div style="background:#ccc;color:red;padding:3px 0;font-size:13px;" onclick="tesh('<?=$_thisKons22;?>')"><?=$_thisstok_onpkg;?></div>
                </div>
                <?php
                
            }
        }
        
  } //end

  function show_agf(){
        $pst_to_grey = $this->db->query("SELECT SUM(ukuran) AS jml FROM data_igtujuan WHERE ukuran>33 AND tujuan_proses='Grey'")->row("jml");
        $pst_to_finish = $this->db->query("SELECT SUM(ukuran) AS jml FROM data_igtujuan WHERE tujuan_proses='Finish'")->row("jml");
        $sudahDiFinish = $this->db->query("SELECT SUM(ukuran) AS jml FROM data_if_before WHERE status='ready'")->row("jml");
        $all_pusatex = $pst_to_grey + $pst_to_finish + $sudahDiFinish;
        $alltoFinish = $pst_to_finish + $sudahDiFinish;
        //$akanDiFolding = $this->db->query("SELECT SUM(ukuran) AS jml FROM kiriman_pusatex WHERE ukuran>20 AND stfol = 'no' AND diterima_pusatex!='null' AND tujuan_proses='Grey'")->row("jml");
        //$antrianInspect = floatval($sudahDiFinish) + floatval($akanDiFolding);
        //$antrianFinish = $this->db->query("SELECT SUM(ukuran) AS jml FROM kiriman_pusatex WHERE ukuran>20 AND stfol = 'no' AND diterima_pusatex!='null' AND tujuan_proses='Finish' AND kode_roll NOT IN (SELECT kode_roll FROM data_if_before) AND kode_roll NOT IN (SELECT kode_roll FROM data_if)")->row("jml");
        //$all_stok = $antrianFinish + $antrianInspect;
        if(fmod($all_pusatex, 1) !== 0.00){
            $all_stok = number_format($all_pusatex,2,',','.');
        } else {
            $all_stok = number_format($all_pusatex,0,',','.');
        }
        if(fmod($pst_to_grey, 1) !== 0.00){
            $all_stok2 = number_format($pst_to_grey,2,',','.');
        } else {
            $all_stok2 = number_format($pst_to_grey,0,',','.');
        }
        if(fmod($alltoFinish, 1) !== 0.00){
            $all_stok3 = number_format($alltoFinish,2,',','.');
        } else {
            $all_stok3 = number_format($alltoFinish,0,',','.');
        }
        echo json_encode(array("statusCode"=>200, "psn_total"=>$all_stok, "psn_grey"=>$all_stok2, "psn_finish"=>$all_stok3));
  } //edn 

  function loaddatastokonpusatex(){
        $name = $this->session->userdata('nama');
        $page = $this->input->post('tipe');
        if($page == "Finish"){
            $alldata = $this->db->query("SELECT DISTINCT konstruksi FROM kiriman_pusatex WHERE stfol='no'");
        } else {
            $alldata = $this->db->query("SELECT DISTINCT konstruksi FROM data_igtujuan WHERE tujuan_proses='Grey'");
        }
        if($alldata->num_rows() == 0){
            echo "Tidak Ada Kain $page di Pusatex";
        } else {
            //if($page=="RJS"){
            if($page == "Finish"){
                foreach($alldata->result() as $kons2){
                    $_kons = $kons2->konstruksi;
                    $jmlBelumFinish = $this->db->query("SELECT SUM(ukuran) AS jml FROM data_igtujuan WHERE konstruksi='$_kons' AND tujuan_proses='Finish'")->row("jml");
                    $jmlSudahFinish = $this->db->query("SELECT SUM(ukuran) AS jml FROM data_if_before WHERE konstruksi='$_kons' AND status='ready'")->row("jml");
                    $jmlTotalFinish = $jmlBelumFinish + $jmlSudahFinish;
                    if($jmlTotalFinish == floor($jmlTotalFinish)){
                        $jmlTotalFinish2 = number_format($jmlTotalFinish,0,',','.');
                    } else {
                        $jmlTotalFinish2 = number_format($jmlTotalFinish,2,',','.');
                    }
                    if($jmlBelumFinish == floor($jmlBelumFinish)){
                        $jmlBelumFinish2 = number_format($jmlBelumFinish,0,',','.');
                    } else {
                        $jmlBelumFinish2 = number_format($jmlBelumFinish,2,',','.');
                    }
                    if($jmlSudahFinish == floor($jmlSudahFinish)){
                        $jmlSudahFinish2 = number_format($jmlSudahFinish,0,',','.');
                    } else {
                        $jmlSudahFinish2 = number_format($jmlSudahFinish,2,',','.');
                    }
                    if($jmlTotalFinish > 0){
                    ?>
                    <div class="card-awal blue">
                        <div class="items" style="color:#FFFFFF;border-bottom:1px solid #ccc;text-align:center;"><?=$_kons;?></div>
                        <?php //if($name == "Adi Subuhadir"){?>
                        <div class="items-nilai" id="agf_total" onclick="openModal2('<?=$_kons;?>','num1')">
                        <?php //} else { ?>
                        <!-- <div class="items-nilai" id="agf_total"> -->
                        <?php //} ?>
                            <?=$jmlTotalFinish2;?>
                        </div>
                        <div style="width:100%;height:40px;margin-top:-15px;display:flex;justify-content:space-between;align-items:center;">
                            <div style="width:50%;display:flex;flex-direction:column;align-items:center;background:#06a235;color:#FFFFFF;padding:5px 0;">
                                <small style="font-size:12px;">Belum Di Finish</small>
                                <label onclick="openModal('<?=$_kons;?>','antFinish')"><?=$jmlBelumFinish2;?></label>
                            </div>
                            <div style="width:50%;display:flex;flex-direction:column;align-items:center;background:#fa1e0f;color:#FFFFFF;padding:5px 0;">
                                <small style="font-size:12px;">Sudah Di Finish</small>
                                <label onclick="openModal('<?=$_kons;?>','antInspect')"><?=$jmlSudahFinish2;?></label>
                            </div>
                        </div>
                    </div>
                    <?php }
                    }
            } else {
                foreach($alldata->result() as $kons2){
                    $_kons = $kons2->konstruksi;
                    $jmlGrey = $this->db->query("SELECT SUM(ukuran) AS jml FROM data_igtujuan WHERE ukuran>33 AND konstruksi='$_kons' AND tujuan_proses='Grey'")->row("jml");
                    if($jmlGrey == floor($jmlGrey)){
                        $jmlGrey2 = number_format($jmlGrey,0,',','.');
                    } else {
                        $jmlGrey2 = number_format($jmlGrey,2,',','.');
                    }
                    if($jmlGrey > 0){
                    ?>
                    <div class="card-awal blue">
                        <div class="items" style="color:#FFFFFF;border-bottom:1px solid #ccc;text-align:center;"><?=$_kons;?></div>
                        <?php //if($name == "Adi Subuhadir"){?>
                        <div class="items-nilai" id="agf_total" onclick="openModal2('<?=$_kons;?>','num2')">
                        <?php //} else { ?>
                        <!-- <div class="items-nilai" id="agf_total"> -->
                        <?php //} ?>
                            <?=$jmlGrey2;?>
                        </div>
                        
                    </div>
                    <?php } }
            }
            //} 
        }
  } //end

  function loadkirimanbarang(){
        $tgl = $this->input->post('jns');
        $page = $this->input->post('page');
        $kiriman = $this->db->query("SELECT * FROM surat_jalan WHERE tgl_kirim='$tgl' AND dep_asal='RJS' AND tujuan_kirim='Pusatex' ORDER BY no_sj DESC");
        
        
        if($kiriman->num_rows() > 0){
            echo "<tr>";
            echo "<td><strong>SJ</strong></td>";
            echo "<td><strong>TGL</strong></td>";
            echo "<td><strong>PKG</strong></td>";
            echo "<td><strong>ROLL</strong></td>";
            echo "<td><strong>TOTAL</strong></td>";
            echo "</tr>";
            foreach($kiriman->result() as $bal){
                $sj = $bal->no_sj;
                $tgl_kirim = date('d M Y', strtotime($bal->tgl_kirim));
                $pkg = $this->data_model->get_byid('new_tb_packinglist',['no_sj'=>$sj])->num_rows();
                $roll = $this->db->query("SELECT SUM(jumlah_roll) AS jml, SUM(ttl_panjang) AS pjg FROM new_tb_packinglist WHERE no_sj='$sj'");
                $_roll = $roll->row("jml");
                $_pjg = $roll->row("pjg");
                if(floor($_pjg)==$_pjg){
                    $_pjg2 = number_format($_pjg,0,',','.');
                } else {
                    $_pjg2 = number_format($_pjg,2,',','.');
                }
                //$konst = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$kd])->row("kode_konstruksi");
                echo "<tr>";
                echo "<td>".$sj."</td>";
                echo "<td>$tgl_kirim</td>";
                echo "<td>$pkg</td>";
                echo "<td>$_roll</td>";
                echo "<td>$_pjg2</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td>Belum ada kiriman Kain</td></tr>";
        }
  }
  function loadkirimanbarang23(){
        $tgl = $this->input->post('jns');
        $page = $this->input->post('page');
        if($page=="RJS"){
            $kiriman = $this->db->query("SELECT * FROM kiriman_pusatex WHERE kode_roll LIKE 'R%' AND tanggal='$tgl'");
        } elseif($page=="All"){
            $kiriman = $this->db->query("SELECT * FROM kiriman_pusatex WHERE tanggal='$tgl'");
        } else {
            $kiriman = $this->db->query("SELECT * FROM kiriman_pusatex WHERE kode_roll LIKE 'S%' AND tanggal='$tgl'");
        }
        if($kiriman->num_rows() > 0){
             $kons = array();
             foreach($kiriman->result() as $val){
                if (in_array($val->konstruksi, $kons)){ } else {
                  $kons[] = $val->konstruksi;
                }
             }
             echo "<tr><th>Konstruksi</th><th>Jumlah Diterima</th></tr>";
             foreach($kons as $kons){
                 if($page=="RJS"){
                 $jumlah = $this->db->query("SELECT SUM(ukuran) as ukr FROM kiriman_pusatex WHERE kode_roll LIKE 'R%' AND konstruksi='$kons' AND tanggal='$tgl'")->row("ukr");
                 } else {
                 $jumlah = $this->db->query("SELECT SUM(ukuran) as ukr FROM kiriman_pusatex WHERE kode_roll LIKE 'S%' AND konstruksi='$kons' AND tanggal='$tgl'")->row("ukr");
                 }
                 echo "<tr>";
                 echo "<td>".$kons."</td>";
                 echo "<td>".number_format($jumlah)."</td>";
                 echo "</tr>";
             }
        } else {
             echo "<tr><td>Belum ada kiriman ke Samatex</td></tr>";
        }
  } //end
  function showpiutang(){
        $lasup = $this->db->query("SELECT * FROM tes_piutang ORDER BY id_update DESC LIMIT 1")->row("last_time");
        $ex = explode(" ", $lasup);
        $er = explode(':', $ex[1]);
        $waktu = $er[0].":".$er[1];
        $cek = $this->db->query("SELECT SUM(saldop) as utang FROM tes_piutang2
        WHERE saldop > 0 AND nmcus NOT LIKE 'KM%' AND nmcus NOT LIKE 'PS%' AND nmcus NOT LIKE 'PB%' AND nmcus NOT LIKE 'PH%'")->row("utang");
        if(fmod($cek, 1) !== 0.00){
            $utang = number_format($cek,2,',','.');
        } else {
            $utang = number_format($cek,0,',','.');
        }
        echo json_encode(array("statusCode"=>200, "psn"=>$utang, "owek"=>$waktu));
  } //end
  function halamanpiutang2(){
    if($this->session->userdata('mng_dash') == "manager_dash"){
        $this->load->view('manager/showpiutang');
    } else {
        echo "<a href='".base_url('all-dashboard')."' style='text-decoration:none;'><div style='width:100%;height:100vh;display:flex;justify-content:center;align-items:center;font-size:3em;'>Akses diblokir</div></a>";
    }
  } //end
  function halamanpiutang(){
    if($this->session->userdata('mng_dash') == "manager_dash"){
        $this->load->view('manager/showpiutang');
    } else {
        echo "<a href='".base_url('all-dashboard')."' style='text-decoration:none;'><div style='width:100%;height:100vh;display:flex;justify-content:center;align-items:center;font-size:3em;'>Akses diblokir</div></a>";
    }
  } //end

  function sortutang(){
    echo '<div class="mainutama" style="width:100%;">';
      $ar = array(
        '00' => 'NaN', '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des'
    );
        $sort = $this->input->post('val');
        if($sort == "satu"){
            $dt2 = $this->db->query("SELECT * FROM tes_piutang2 WHERE saldop > 0 AND nmcus NOT LIKE 'KM%' AND nmcus NOT LIKE 'PS%' AND nmcus NOT LIKE 'PB%' AND nmcus NOT LIKE 'PH%' ORDER BY saldop DESC");
        } elseif($sort=="dua"){
            $dt2 = $this->db->query("SELECT * FROM tes_piutang2 WHERE saldop > 0 AND nmcus NOT LIKE 'KM%' AND nmcus NOT LIKE 'PS%' AND nmcus NOT LIKE 'PB%' AND nmcus NOT LIKE 'PH%' ORDER BY saldop ASC");
        } else {
            $dt2 = $this->db->query("SELECT * FROM tes_piutang2 WHERE saldop > 0 AND nmcus NOT LIKE 'KM%' AND nmcus NOT LIKE 'PS%' AND nmcus NOT LIKE 'PB%' AND nmcus NOT LIKE 'PH%'");
        }
        foreach($dt2->result() as $val):
            $idcus = $val->id_konsumen;
            //cek nota terakhir
            $sj = $this->db->query("SELECT * FROM surat_jalan WHERE id_customer='$idcus' AND create_nota='y' ORDER BY id_sj DESC LIMIT 1")->row("no_sj");
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
    <?php  endforeach;
    echo "</div>";
    echo '<div class="spaceputih">&nbsp;</div>';
  } //end sort utang
  function sortutang2(){
    echo '<div class="mainutama" style="width:100%;">';
    $sort = $this->input->post('val');
    $nama = $this->input->post('nama');
        $ar = array(
        '00' => 'NaN', '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des'
        );
        
        if($nama=="" OR $nama=="null"){
            if($sort == "satu"){
                $dt2 = $this->db->query("SELECT * FROM tes_piutang3 WHERE saldop!=0 ORDER BY saldop DESC");
            } elseif($sort=="dua"){
                $dt2 = $this->db->query("SELECT * FROM tes_piutang3 WHERE saldop!=0 ORDER BY saldop ASC");
            } else {
                $dt2 = $this->db->query("SELECT * FROM tes_piutang3 WHERE saldop!=0 ORDER BY nmcus");
            }
        } else {
            $dt2 = $this->db->query("SELECT * FROM tes_piutang3 WHERE nmcus='$nama'");
        }
        foreach($dt2->result() as $val):
            $idcus = $val->id_konsumen;
            $cekCusPriority = $this->data_model->get_byid('ab_cuspriority', ['idcus'=>$idcus]);
            if($cekCusPriority->num_rows()==0){
                $sj = $this->db->query("SELECT * FROM surat_jalan WHERE id_customer='$idcus' AND create_nota='y' ORDER BY id_sj DESC LIMIT 1")->row("no_sj");
                $tgl_kirim = $this->db->query("SELECT * FROM surat_jalan WHERE id_customer='$idcus' AND create_nota='y'  ORDER BY id_sj DESC LIMIT 1")->row("tgl_kirim");
            } else {
                $inisial = $cekCusPriority->row("awalan");
                $allid = array();
                $qrs = $this->db->query("SELECT id_konsumen,nama_konsumen FROM dt_konsumen WHERE nama_konsumen LIKE '$inisial%'");
                foreach($qrs->result() as $valw){
                    $iniId = "'".$valw->id_konsumen."'";
                    $allid[] = $iniId;
                }
                $imss = implode(',',$allid);
                $sj = $this->db->query("SELECT * FROM surat_jalan WHERE id_customer IN ($imss) AND create_nota='y' ORDER BY id_sj DESC LIMIT 1")->row("no_sj");
                $tgl_kirim = $this->db->query("SELECT * FROM surat_jalan WHERE id_customer IN ($imss) AND create_nota='y'  ORDER BY id_sj DESC LIMIT 1")->row("tgl_kirim");
            }
            //cek nota terakhir
            
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
    <?php  endforeach;
    echo "</div>";
    echo '<div class="spaceputih">&nbsp;</div>';
  } //end sort utang2
  function showDroll(){
        $jns = $this->input->post('jns');
        $kons = $this->input->post('kons');
        $chto = $this->input->post('chto');
        $userName = $this->session->userdata('username');
        //echo $userName;
        $qr = "SELECT * FROM data_fol WHERE konstruksi='$kons' AND jns_fold='$jns' AND (posisi='Pusatex' OR posisi LIKE '%PKT%')";
        if($jns=="Finish"){
            $qr2 = "SELECT * FROM data_fol WHERE kode_roll NOT LIKE '%JP%' AND konstruksi='$kons' AND jns_fold='$jns' AND ukuran>36 AND (posisi='Pusatex' OR posisi LIKE '%PKT%')";
            $qr2bp = "SELECT * FROM data_fol WHERE kode_roll NOT LIKE '%JP%' AND konstruksi='$kons' AND jns_fold='$jns' AND ukuran<37 AND (posisi='Pusatex' OR posisi LIKE '%PKT%')";
            $qr3 = "SELECT * FROM data_fol WHERE kode_roll LIKE '%JP%' AND konstruksi='$kons' AND jns_fold='$jns' AND (posisi='Pusatex' OR posisi LIKE '%PKT%')";
        } else {
            $qr2 = "SELECT * FROM data_fol WHERE kode_roll NOT LIKE '%JG%' AND konstruksi='$kons' AND jns_fold='$jns' AND ukuran>33 AND (posisi='Pusatex' OR posisi LIKE '%PKT%')";
            $qr2bp = "SELECT * FROM data_fol WHERE kode_roll NOT LIKE '%JG%' AND konstruksi='$kons' AND jns_fold='$jns' AND ukuran<34 AND (posisi='Pusatex' OR posisi LIKE '%PKT%')";
            $qr3 = "SELECT * FROM data_fol WHERE kode_roll LIKE '%JG%' AND konstruksi='$kons' AND jns_fold='$jns' AND (posisi='Pusatex' OR posisi LIKE '%PKT%')";
        }
        
        $cek = $this->db->query($qr);
        $cek2 = $this->db->query($qr2);
        $cek2bp = $this->db->query($qr2bp);
        $cek3 = $this->db->query($qr3);
        $jumlah_roll = $cek->num_rows();
        $jumlah_roll_ORI = $cek2->num_rows();
        $jumlah_roll_BP = $cek2bp->num_rows();
        $jumlah_roll_JP = $cek3->num_rows();
        $_qr = $this->db->query("SELECT SUM(ukuran) AS jml FROM data_fol WHERE konstruksi='$kons' AND jns_fold='$jns' AND (posisi='Pusatex' OR posisi LIKE '%PKT%')")->row("jml");
        if($jns=="Finish"){
            $_qr2 = $this->db->query("SELECT SUM(ukuran) AS jml FROM data_fol WHERE kode_roll NOT LIKE '%JP%' AND konstruksi='$kons' AND jns_fold='$jns' AND ukuran>36 AND (posisi='Pusatex' OR posisi LIKE '%PKT%')")->row("jml");
            $_qr2bp = $this->db->query("SELECT SUM(ukuran) AS jml FROM data_fol WHERE kode_roll NOT LIKE '%JP%' AND konstruksi='$kons' AND jns_fold='$jns' AND ukuran<37 AND (posisi='Pusatex' OR posisi LIKE '%PKT%')")->row("jml");
            $_qr3 = $this->db->query("SELECT SUM(ukuran) AS jml FROM data_fol WHERE kode_roll LIKE '%JP%' AND konstruksi='$kons' AND jns_fold='$jns' AND (posisi='Pusatex' OR posisi LIKE '%PKT%')")->row("jml");
        } else {
            $_qr2 = $this->db->query("SELECT SUM(ukuran) AS jml FROM data_fol WHERE kode_roll NOT LIKE '%JG%' AND konstruksi='$kons' AND jns_fold='$jns' AND ukuran>33 AND (posisi='Pusatex' OR posisi LIKE '%PKT%')")->row("jml");
            $_qr2bp = $this->db->query("SELECT SUM(ukuran) AS jml FROM data_fol WHERE kode_roll NOT LIKE '%JG%' AND konstruksi='$kons' AND jns_fold='$jns' AND ukuran<34 AND (posisi='Pusatex' OR posisi LIKE '%PKT%')")->row("jml");
            $_qr3 = $this->db->query("SELECT SUM(ukuran) AS jml FROM data_fol WHERE kode_roll LIKE '%JG%' AND konstruksi='$kons' AND jns_fold='$jns' AND (posisi='Pusatex' OR posisi LIKE '%PKT%')")->row("jml");
        }
        
        $_qr = round($_qr,2);
        $_qr2 = round($_qr2,2);
        $_qr2bp = round($_qr2bp,2);
        $_qr3 = round($_qr3,2);
        if($_qr == floor($_qr)){ $_qr = number_format($_qr,0,',','.'); } else { $_qr = number_format($_qr,2,',','.'); }
        if($_qr2 == floor($_qr2)){ $_qr2 = number_format($_qr2,0,',','.'); } else { $_qr2 = number_format($_qr2,2,',','.'); }
        if($_qr2bp == floor($_qr2bp)){ $_qr2bp = number_format($_qr2bp,0,',','.'); } else { $_qr2bp = number_format($_qr2bp,2,',','.'); }
        if($_qr3 == floor($_qr3)){ $_qr3 = number_format($_qr3,0,',','.'); } else { $_qr3 = number_format($_qr3,2,',','.'); }
        ?>
        
        <div class="tables" style="margin-top:20px;">
            <table border="1">
                <tr>
                    <th>Konstruksi</th>
                    <th>Total Panjang</th>
                    <th>Ori</th>
                    <th>BP</th>
                    <th>JP</th>
                </tr>
                <tr>
                    <td style="text-align:center;"><?=$jns=='Grey' ? $kons:$chto;?></td>
                    <td><?=$_qr;?> </td>
                    <td><?=$_qr2;?> </td>
                    <td><?=$_qr2bp;?> </td>
                    <td><?=$_qr3;?> </td>
                </tr>
                <tr>
                    <td style="text-align:center;"></td>
                    <td><?=$jumlah_roll;?> Roll</td>
                    <td><?php if($jumlah_roll_ORI > 0){ echo "".$jumlah_roll_ORI." Roll"; } ?></td>
                    <td><?php if($jumlah_roll_ORI > 0){ echo "".$jumlah_roll_BP." Roll"; } ?></td>
                    <td><?php if($jumlah_roll_JP > 0){ echo "".$jumlah_roll_JP." Roll"; } ?></td>
                </tr>
            </table>
        </div> 
        <?php  if($userName == "adi" OR $userName == "hamid" OR $userName == "syafiq" OR $userName == "anding2" OR $userName == "syafiq2"){ ?>
        <div class="tables" style="margin-top:20px;">
            <table border="1">
                <tr>
                    <th>Kode Roll</th>
                    <th>Ukuran</th>
                    <th>Tanggal Fol</th>
                    <th>Operator</th>
                </tr>
                <?php
                foreach($cek->result() as $g){
                    $posisi = $g->posisi;
                    if($posisi == "Pusatex"){
                        echo "<tr>";
                        //echo "<td>".$g->kode_roll."</td>";
                        ?><td><input type="checkbox" id="koderoll<?=$g->kode_roll;?>" data-kode="<?=$g->kode_roll;?>" data-ukuran="<?=$g->ukuran;?>" onclick="kodeRoll('<?=$g->kode_roll;?>','<?=$g->ukuran;?>'); updateSummary()"><label for="koderoll<?=$g->kode_roll;?>"><?=$g->kode_roll;?></label></td><?php
                        echo "<td>".$g->ukuran."</td>";
                        echo "<td>".date('d-m-Y',strtotime($g->tgl))."</td>";
                        echo "<td>".ucfirst($g->operator)."</td>";
                        echo "</tr>";
                    } else {
                        echo "<tr>";
                        //echo "<td>".$g->kode_roll."</td>";
                        ?><td style="color:red;"><input type="checkbox" id="koderoll<?=$g->kode_roll;?>" data-kode="<?=$g->kode_roll;?>" data-ukuran="<?=$g->ukuran;?>" onclick="kodeRoll('<?=$g->kode_roll;?>','<?=$g->ukuran;?>'); updateSummary()"><label for="koderoll<?=$g->kode_roll;?>"><?=$g->kode_roll;?></label></td><?php
                        echo "<td style='color:red;'>".$g->ukuran."</td>";
                        echo "<td style='color:red;'>".date('d-m-Y',strtotime($g->tgl))."</td>";
                        echo "<td style='color:red;'>".ucfirst($g->operator)."</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </table>
        </div>
        
        <?php
        }
        
  } //end

  function showPaket(){
        $kons = $this->input->post('kons');
        $cek = $this->data_model->get_byid('new_tb_packinglist',['kode_konstruksi'=>$kons,'siap_jual'=>'y','no_sj'=>'NULL']);
        if($cek->num_rows() > 0){
            
                ?>
                <div class="tables" style="margin-top:20px;">
                    <table border="1">
                        <tr>
                            <th>Kode Paket</th>
                            <th>Konstruksi</th>
                            <th>Roll</th>
                            <th>Panjang</th>
                            <th>Tujuan</th>
                        </tr>
                        <?php foreach($cek->result() as $val){ ?>
                        <tr>
                            <td><?=$val->kd;?></td>
                            <td><?=$val->kode_konstruksi;?></td>
                            <td><?=$val->jumlah_roll;?></td>
                            <td><?=$val->ttl_panjang;?></td>
                            <td><?=$val->customer;?></td>
                        </tr>
                        <?php } ?>
                    </table>
                </div>
                <div style="width:100%;margin-top:20px;background:#ccc;padding:10px;display:flex;justify-content:center;align-items:center;" onclick="closeModal()">Close</div>
                <?php
            
        } else {
            echo "Tidak ada packinglist Konstruksi ".$kons."";
            ?><div style="width:100%;margin-top:20px;background:#ccc;padding:10px;display:flex;justify-content:center;align-items:center;" onclick="closeModal()">Close</div><?php
        }
  }
  function lihatRollSb(){
        $kons = strtoupper($this->input->post('kons'));
        $proses = $this->input->post('proses');
        if($proses == "num1") { 
            $p = "Finish"; 
            // $cek_ori = $this->db->query("SELECT kode_roll,konstruksi,ukuran,stfol,diterima_pusatex FROM kiriman_pusatex WHERE ukuran>49 AND stfol = 'no' AND konstruksi='$kons' AND diterima_pusatex!='null'");
            // $cek_BP = $this->db->query("SELECT kode_roll,konstruksi,ukuran,stfol,diterima_pusatex FROM kiriman_pusatex WHERE ( ukuran>20 AND ukuran<50 ) AND stfol = 'no' AND konstruksi='$kons' AND diterima_pusatex!='null'");
            // $cek_BC = $this->db->query("SELECT kode_roll,konstruksi,ukuran,stfol,diterima_pusatex FROM kiriman_pusatex WHERE ( ukuran>3 AND ukuran<21 ) AND stfol = 'no' AND konstruksi='$kons' AND diterima_pusatex!='null'");
            //echo "num1";
            $cek_ori = $this->db->query("SELECT * FROM data_igtujuan WHERE konstruksi='$kons' AND ukuran>49 AND tujuan_proses='Finish'");
            $cek_BP = $this->db->query("SELECT * FROM data_igtujuan WHERE konstruksi='$kons' AND ( ukuran>20 AND ukuran<50 ) AND tujuan_proses='Finish'");
            $cek_BC = $this->db->query("SELECT * FROM data_igtujuan WHERE konstruksi='$kons' AND ( ukuran>3 AND ukuran<21 ) AND tujuan_proses='Finish'");
        } else { 
            $cek_ori = $this->db->query("SELECT * FROM data_igtujuan WHERE konstruksi='$kons' AND ukuran>33 AND tujuan_proses='Grey'");
            //$cek_BP = $this->db->query("SELECT * FROM data_igtujuan WHERE konstruksi='$kons' AND ( ukuran>20 AND ukuran<34 ) AND tujuan_proses='Grey'");
            //$cek_BC = $this->db->query("SELECT * FROM data_igtujuan WHERE konstruksi='$kons' AND ( ukuran>3 AND ukuran<21 ) AND tujuan_proses='Grey'");
            //echo "num2";
        }
        
        
        if($cek_ori->num_rows() > 0){
            echo "<div class='tables'>";
            echo "<table style='font-size:12px;'>";
            echo "<tr>";
            echo "<th>No</th>";
            echo "<th>Konstruksi</th>";
            echo "<th>Kode Roll</th>";
            echo "<th>Panjang</th>";
            echo "<th>Status</th>";
            echo "</tr>";
            echo "<tr><td colspan='5' style='text-align:left;font-weight:bold;'>DATA ORI</td></tr>";
            $no=1;
            $total_ori=0;
            foreach($cek_ori->result() as $val){
                $_kdrol = $val->kode_roll;
                $wasFold = $this->data_model->get_byid('data_fol',['kode_roll'=>$_kdrol])->num_rows();
                if($wasFold > 0){
                    $status = "Folding";
                } else {
                    $wasIF = $this->data_model->get_byid('data_if',['kode_roll'=>$_kdrol])->num_rows();
                    if($wasIF > 0){
                        $status = "InsFinish";
                    } else {
                        $status = "Pusatex";
                    }
                }
                echo "<tr>";
                echo "<td>".$no++."</td>";
                echo "<td>".$val->konstruksi."</td>";
                echo "<td>".$val->kode_roll."</td>";
                echo "<td>".$val->ukuran."</td>";
                if($status == "Pusatex"){
                    echo "<td>$status</td>";
                } else {
                    echo "<td style='color:red;'>$status</td>";
                }
                echo "</tr>";
                $total_ori += $val->ukuran;
            }
            $total_ori = number_format($total_ori,0,',','.');
            echo "<tr><td colspan='3' style='text-align:left;font-weight:bold;'>Total Panjang ORI</td><td>".$total_ori."</td><td></td></tr>";
            echo "</table></div>";
            
        } else {
            echo "Tidak ada data ORI.!!";
        }
        
        ?><div style="width:100%;margin-top:20px;background:#ccc;padding:10px;display:flex;justify-content:center;align-items:center;" onclick="closeModal()">Close</div><?php
  } //end
  function hitungPaket(){
        $kode_roll = $this->input->post('tampungan2');
        $x = explode(',', $kode_roll);
        $jml = count($x);
        if($kode_roll == "0"){
            echo "Silahkan pilih Kode Roll...";
        } else {
            $ukr1=0;
            foreach($x as $val){
                //echo $val."-";
                $ukr = $this->data_model->get_byid('data_fol', ['kode_roll'=>$val])->row("ukuran");
                $ukr1 += $ukr;
            }
            $_ukuran = round($ukr1,2);
            if($_ukuran == floor($_ukuran)){
                $_ukuranView = number_format($_ukuran,0,',','.');
            } else {
                $_ukuranView = number_format($_ukuran,2,',','.');
            }
            echo $jml." Roll Total Panjang : ".$_ukuranView."";
        }
        
  }
  function showbcstok(){
        $bcRJs = $this->db->query("SELECT SUM(ukuran) AS ukr FROM ab_non_ori WHERE posisi='RJS' ")->row("ukr");
        $bcPst = $this->db->query("SELECT SUM(ukuran) AS ukr FROM ab_non_ori WHERE posisi='Pusatex' ")->row("ukr");
        $bcALL = floatval($bcRJs) + floatval($bcPst);
        if(floor($bcRJs) == $bcRJs){
            $bcRJs2 = number_format($bcRJs,0,'.',',');
        } else {
            $bcRJs2 = number_format($bcRJs,2,'.',',');
        }
        if(floor($bcPst) == $bcPst){
            $bcPst2 = number_format($bcPst,0,'.',',');
        } else {
            $bcPst2 = number_format($bcPst,2,'.',',');
        }
        if(floor($bcALL) == $bcALL){
            $bcALL2 = number_format($bcALL,0,'.',',');
        } else {
            $bcALL2 = number_format($bcALL,2,'.',',');
        }
        echo json_encode(array("statusCode"=>200, "bcrjs"=>$bcRJs2, "bcpst"=>$bcPst2, "bcall"=>$bcALL2));
  }

}
?>