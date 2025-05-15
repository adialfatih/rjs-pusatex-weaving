<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fake extends CI_Controller
{
    function __construct()
    {
      parent::__construct();
      $this->load->model('data_model');
      date_default_timezone_set("Asia/Jakarta");
    //   if($this->session->userdata('login_form') != "rindangjati_sess"){
	// 	  redirect(base_url("login"));
	//   }
    }
   
  function index(){
        //$this->load->view('users/login');
        echo "..";
  } //end
  function fakepkglistall(){
        $this->load->view('fakeAll_show');
  }
  function fakepkglist(){
        $this->load->view('fakeAll');
  }
  function updatepkg(){
      $nmpkg = $this->input->post('nmpkg');
      $ketpkg = $this->input->post('ketpkg');
      $pkgkons = $this->input->post('pkgkons');
      $ygbuat = $this->input->post('ygbuat');
      $id_pkg = $this->input->post('id_pkg');
      if($nmpkg!="" AND $ketpkg!="" AND $pkgkons!="" AND $ygbuat!="" AND $id_pkg!=""){
          $this->data_model->updatedata('id_fakepkg',$id_pkg, 'fake_pkglist', [
              'name_pkglist' => $nmpkg,
              'keterangan' => $ketpkg,
              'yg_buat' => $ygbuat,
              'konstruksi' => $pkgkons
          ]);
          echo json_encode(array("statusCode"=>200, "psn"=>"success"));
      } else {
          echo json_encode(array("statusCode"=>404, "psn"=>"failed"));
      }
  } //end

  function updatepkg_isi(){
      $nmpkg = $this->input->post('nmpkg');
      $ketpkg = $this->input->post('ketpkg');
      $pkgkons = $this->input->post('pkgkons');
      $ygbuat = $this->input->post('ygbuat');
      $id_pkg = $this->input->post('id_pkg');
      $kode_roll = $this->input->post('selection');
      if($nmpkg!="" AND $ketpkg!="" AND $pkgkons!="" AND $ygbuat!="" AND $id_pkg!=""){
          $this->data_model->updatedata('id_fakepkg',$id_pkg, 'fake_pkglist', [
              'name_pkglist' => $nmpkg,
              'keterangan' => $ketpkg,
              'yg_buat' => $ygbuat,
              'konstruksi' => $pkgkons
          ]);
          $cek_kode = $this->data_model->get_byid('data_ig', ['kode_roll'=>$kode_roll]);
          if($cek_kode->num_rows() == 1){
              $kode_roll_konstruksi = $cek_kode->row("konstruksi");
              if($kode_roll_konstruksi == $pkgkons){
                $cek_kode_folding = $this->data_model->get_byid('data_fol', ['kode_roll'=>$kode_roll]);
                if($cek_kode_folding->num_rows() == 0){
                    $cek_isi_pkg = $this->data_model->get_byid('fake_isi', ['kode_roll'=>$kode_roll]);
                    if($cek_isi_pkg->num_rows() == 0){
                        $this->data_model->saved('fake_isi', [
                          'id_fakepkg' => $id_pkg,
                          'kode_roll' => $kode_roll,
                          'ukuran' => $cek_kode->row('ukuran_ori')
                        ]);
                        echo json_encode(array("statusCode"=>200, "psn"=>"success"));
                    } else {
                        echo json_encode(array("statusCode"=>500, "psn"=>"Kode Sudah di Packinglist"));
                    }
                    
                } else {
                    echo json_encode(array("statusCode"=>500, "psn"=>"Kain Telah di proses folding"));
                }
              } else {
                  $txt = "Konstruksi Salah (".$kode_roll_konstruksi.")";
                  echo json_encode(array("statusCode"=>500, "psn"=>$txt));
              }  
          } else if($cek_kode->num_rows() > 1){
                echo json_encode(array("statusCode"=>500, "psn"=>"Terdapat Doble Kode"));
          } else {
                echo json_encode(array("statusCode"=>500, "psn"=>"Kode Tidak Ditemukan"));
          }
          
      } else {
          echo json_encode(array("statusCode"=>404, "psn"=>"failed"));
      }
  } //end

  function loadisipkg(){
      $id = $this->input->post('id_pkg');
      //$data = $this->data_model->get_byid('fake_isi', ['id_fakepkg'=>$id]);
      $data = $this->db->query("SELECT * FROM fake_isi WHERE id_fakepkg='$id' ORDER BY id_fakeisi DESC");
      $ukuran_total = $this->db->query("SELECT SUM(ukuran) AS ukr FROM fake_isi WHERE id_fakepkg='$id' ORDER BY id_fakeisi DESC")->row("ukr");
      if($data->num_rows() > 0){
          echo '<tr>
                    <td><strong>No</strong></td>
                    <td><strong>Kode Roll</strong></td>
                    <td><strong>Ukuran</strong></td>
                    <td><strong>#</strong></td>
                </tr>';
          $no=1;
          foreach($data->result() as $val){
              echo "<tr>";
              echo "<td>".$no."</td>";
              echo "<td>".$val->kode_roll."</td>";
              echo "<td>".$val->ukuran."</td>";
              ?><td><a href="#" onclick="delpkg('<?=$val->kode_roll;?>')" style="text-decoration:none;color:red;">Del</a></td><?php
              //echo "<td></td>";
              echo "</tr>";
              $no++;
          } 
          echo "<tr>";
          echo "<td colspan='2'><strong>Total</strong></td>";
          echo "<td><strong>".$ukuran_total."</strong></td>";
          echo "<td></td>";
          echo "</tr>";
      } else {
          echo '<tr>
                    <td><strong>No</strong></td>
                    <td><strong>Kode Roll</strong></td>
                    <td><strong>Ukuran</strong></td>
                    <td><strong>#</strong></td>
                </tr>
                <tr>
                    <td colspan="4">Paket Kosong...</td>
                </tr>';
      }
      
  } //end

  function del_isi(){
     $kode = $this->input->post('kode');
     $this->db->query("DELETE FROM fake_isi WHERE kode_roll='$kode'");
     echo "success";
  }

  function deletPkg_isi(){
      $id = $this->input->post('id_pkg');
      $cek_isi = $this->data_model->get_byid('fake_isi', ['id_fakepkg'=>$id]);
      if($cek_isi->num_rows() > 0){
          echo json_encode(array("statusCode"=>404, "psn"=>"Kosongi dulu isi paket"));
      } else {
          $this->db->query("DELETE FROM fake_pkglist WHERE id_fakepkg='$id'");
          echo json_encode(array("statusCode"=>200, "psn"=>"success"));
      }
  } //
  
  function fakedata(){
      $kons = $this->db->query("SELECT * FROM v_fakeisi GROUP BY konstruksi");
      echo "<table border='1'>";
      echo "<tr>";
      echo "<th>No.</th>";
      echo "<th>Konstruksi</th>";
      echo "<th>Total Roll</th>";
      echo "<th>Total Panjang</th>";
      echo "</tr>";
      $no=1;
      foreach($kons->result() as $val):
          $k = $val->konstruksi;
          echo "<tr>";
          echo "<td>".$no."</td>";
          echo "<td>".$k."</td>";
          $jmlroll = $this->db->query("SELECT COUNT(konstruksi) AS jml FROM v_fakeisi WHERE konstruksi='$k'")->row("jml");
          $ukr = $this->db->query("SELECT SUM(ukuran) AS jml FROM v_fakeisi WHERE konstruksi='$k'")->row("jml");
          echo "<td>".$jmlroll."</td>";
          echo "<td>".number_format($ukr,0,',','.')."</td>";
          echo "</tr>";
          $no++;
      endforeach;
      echo "</table>";
      echo "<hr>";
      
      echo "<table border='1'>";
      echo "<tr>";
      echo "<th>No.</th>";
      echo "<th>Konstruksi</th>";
      echo "<th>Packinglist</th>";
      echo "<th>Keterangan</th>";
      echo "<th>Yg Buat</th>";
      echo "<th>Jml Roll</th>";
      echo "<th>total Panjang</th>";
      echo "</tr>";
      $n=1;
      foreach($kons->result() as $b):
          $k2 = $b->konstruksi;
          $cekdata = $this->db->query("SELECT * FROM fake_pkglist WHERE konstruksi='$k2'");
          foreach($cekdata->result() as $bal):
              $id = $bal->id_fakepkg;
              $jml_roll = $this->db->query("SELECT COUNT(id_fakepkg) AS jm FROM fake_isi WHERE id_fakepkg='$id'")->row("jm");
              $pjg_roll = $this->db->query("SELECT SUM(ukuran) AS jm FROM fake_isi WHERE id_fakepkg='$id'")->row("jm");
              echo "<tr>";
              echo "<td>".$n."</td>";
              echo "<td>".$k2."</td>";
              ?><td><a href="<?=base_url('fake/fakepkglist/'.$id);?>"><?=$bal->name_pkglist;?></a></td><?php
              //echo "<td><a href=''>".$bal->name_pkglist."</a></td>";
              echo "<td>".$bal->keterangan."</td>";
              echo "<td>".$bal->yg_buat."</td>";
              echo "<td>".$jml_roll."</td>";
              echo "<td>".number_format($pjg_roll,0,',','.')."</td>";
              echo "</tr>";
              $n++;
          endforeach;
      endforeach;
  }

  function pusatex(){
        $datas = $this->db->query("SELECT idterima,kode_roll FROM kiriman_pusatex WHERE tujuan_proses='notset'");
        $no=1;
        foreach($datas->result() as $v){
            $idterima = $v->idterima;
            $kd = $v->kode_roll;
            $dt = $this->db->query("SELECT kd,siap_jual,kode FROM new_tb_isi WHERE kode='$kd'")->row("kd");
            $fns = $this->db->query("SELECT kd,customer FROM new_tb_packinglist WHERE kd='$dt'")->row("customer");
            if($fns == "Finish"){ $proses = "Finish"; } else { $proses="Grey"; }
            
            echo $no.". Kode Roll ".$kd." - ".$dt." - ".$proses."<br>";
            $this->db->query("UPDATE kiriman_pusatex SET tujuan_proses='$proses' WHERE idterima='$idterima'");
            $no++;
        }
  } //end

  function lihatRollSb2(){
        $kons = strtoupper($this->input->post('kons'));
        $proses = $this->input->post('proses');
        if($proses == "antFinish"){
            echo "Error : 28";
        } else {
            $qry = $this->db->query("SELECT * FROM data_if_before WHERE konstruksi='$kons' AND status='ready'");
            if($qry->num_rows() > 0){
                echo "<div class='tables'>";
                echo "<table style='font-size:12px;'>";
                echo "<tr>";
                echo "<th>No</th>";
                echo "<th>Kode Roll</th>";
                echo "<th>Panjang</th>";
                echo "<th>Diterima</th>";
                echo "<th>Waktu</th>";
                echo "</tr>";
                $f=1;
                foreach($qry->result() as $val){
                    echo '<tr>';
                    echo "<td>".$f."</td>";
                    echo "<td>".$val->kode_roll."</td>";
                    echo "<td>".$val->ukuran."</td>";
                    echo "<td>".$val->users."</td>";
                    echo "<td>".$val->tgl_tms."</td>";
                    echo '</tr>';
                    $f++;
                }
            } else {
                echo "Tidak ada stok Antrian Finish";
            }
        }
  }
  
  function laporanInspect(){
      $grey = $this->db->query("SELECT konstruksi,tgl_potong,operator FROM data_if WHERE tgl_potong BETWEEN '2025-04-01' AND '2025-05-01' AND operator IN ('ana','efiana','nina','kusnita2','eva','eka') GROUP BY konstruksi");
      //$grey = $this->db->query("SELECT konstruksi,tanggal,operator,shift_op FROM data_ig WHERE tanggal='2025-05-01' AND shift_op='31' AND operator NOT IN ('ana','efiana','nina','kusnita2','eva','eka') GROUP BY konstruksi");
      //$grey = $this->db->query("SELECT konstruksi,tanggal,operator,shift_op FROM data_ig WHERE tanggal='2025-03-01' AND shift_op!='31' AND operator NOT IN ('ana','efiana','nina','kusnita2','eva','eka') GROUP BY konstruksi");
      $no=1;
      ?>
      <table border="1">
          <tr>
              <th>NO</th>
              <th>KONSTRUKSI</th>
              <th>ORI1</th>
              <th>ORI2</th>
              <th>ORI3</th>
              <th>ORIASLI</th>
              
              <th>BP</th>
              <th>BC</th>
              <th>BS</th>
          </tr>
      
      <?php
      foreach($grey->result() as $val){
          $ks = $val->konstruksi;
          $ukuranORI3 = $this->db->query("SELECT SUM(ukuran_ori) AS jml FROM data_ig WHERE konstruksi='$ks' AND tanggal='2025-04-01' AND shift_op!='31' AND operator IN ('ana','efiana','nina','kusnita2','eva','eka') AND ukuran_ori>49")->row("jml");
          $ukuranORI = $this->db->query("SELECT SUM(ukuran_ori) AS jml FROM data_ig WHERE konstruksi='$ks' AND tanggal BETWEEN '2025-04-02' AND '2025-04-31' AND operator IN ('ana','efiana','nina','kusnita2','eva','eka') AND ukuran_ori>49")->row("jml");
          $ukuranORI2 = $this->db->query("SELECT SUM(ukuran_ori) AS jml FROM data_ig WHERE konstruksi='$ks' AND tanggal='2025-05-01' AND shift_op='31' AND operator IN ('ana','efiana','nina','kusnita2','eva','eka') AND ukuran_ori>49")->row("jml");
          $oriAsli = $ukuranORI2 + $ukuranORI + $ukuranORI3;
          
          
          $ukuranBC1 = $this->db->query("SELECT SUM(ukuran_ori) AS jml FROM data_ig WHERE konstruksi='$ks' AND tanggal='2025-04-01' AND shift_op!='31' AND operator IN ('ana','efiana','nina','kusnita2','eva','eka') AND ukuran_ori>20 AND ukuran_ori<50")->row("jml");
          $ukuranBC2 = $this->db->query("SELECT SUM(ukuran_ori) AS jml FROM data_ig WHERE konstruksi='$ks' AND tanggal BETWEEN '2025-04-02' AND '2025-04-31' AND operator IN ('ana','efiana','nina','kusnita2','eva','eka') AND ukuran_ori>20 AND ukuran_ori<50")->row("jml");
          $ukuranBC3 = $this->db->query("SELECT SUM(ukuran_ori) AS jml FROM data_ig WHERE konstruksi='$ks' AND tanggal='2025-05-01' AND shift_op='31' AND operator IN ('ana','efiana','nina','kusnita2','eva','eka') AND ukuran_ori>20 AND ukuran_ori<50")->row("jml");
          $ukuranBC = $ukuranBC1 + $ukuranBC2 + $ukuranBC3;
          
          $ukuranBCE1 = $this->db->query("SELECT SUM(ukuran_ori) AS jml FROM data_ig WHERE konstruksi='$ks' AND tanggal='2025-04-01' AND shift_op!='31' AND operator IN ('ana','efiana','nina','kusnita2','eva','eka') AND ukuran_ori>3 AND ukuran_ori<21")->row("jml");
          $ukuranBCE2 = $this->db->query("SELECT SUM(ukuran_ori) AS jml FROM data_ig WHERE konstruksi='$ks' AND tanggal BETWEEN '2025-04-02' AND '2025-04-31' AND operator IN ('ana','efiana','nina','kusnita2','eva','eka') AND ukuran_ori>3 AND ukuran_ori<21")->row("jml");
          $ukuranBCE3 = $this->db->query("SELECT SUM(ukuran_ori) AS jml FROM data_ig WHERE konstruksi='$ks' AND tanggal='2025-05-01' AND shift_op='31' AND operator IN ('ana','efiana','nina','kusnita2','eva','eka') AND ukuran_ori>3 AND ukuran_ori<21")->row("jml");
          $ukuranBCE = $ukuranBCE1 + $ukuranBCE2 + $ukuranBCE3;
          
          $ukuranBS1 = $this->db->query("SELECT SUM(ukuran_bs) AS jml FROM data_ig WHERE konstruksi='$ks' AND tanggal='2025-04-01' AND shift_op!='31' AND operator IN ('ana','efiana','nina','kusnita2','eva','eka') AND ukuran_bs>0")->row("jml");
          $ukuranBS2 = $this->db->query("SELECT SUM(ukuran_bs) AS jml FROM data_ig WHERE konstruksi='$ks' AND tanggal BETWEEN '2025-04-02' AND '2025-04-31' AND operator IN ('ana','efiana','nina','kusnita2','eva','eka') AND ukuran_ori>0")->row("jml");
          $ukuranBS3 = $this->db->query("SELECT SUM(ukuran_bs) AS jml FROM data_ig WHERE konstruksi='$ks' AND tanggal='2025-05-01' AND shift_op='31' AND operator IN ('ana','efiana','nina','kusnita2','eva','eka') AND ukuran_ori>0")->row("jml");
          $bs = $ukuranBS1 + $ukuranBS2 + $ukuranBS3;
          
          ?>
          <tr>
              <td><?=$no;?></td>
              <td><?=$val->konstruksi;?></td>
              <td><?=$ukuranORI3;?></td>
              <td><?=$ukuranORI;?></td>
              <td><?=$ukuranORI2;?></td>
              <td><?=$oriAsli;?></td>
              <td><?=$ukuranBC;?></td>
              <td><?=$ukuranBCE;?></td>
              <td><?=$bs;?></td>
          </tr>
          <?php $no++;
      }
      echo "</table>";
  }
  function laporanInspect2(){
      $grey = $this->db->query("SELECT konstruksi,tgl_potong,operator FROM data_if WHERE tgl_potong BETWEEN '2025-04-01' AND '2025-04-30' GROUP BY konstruksi");
      //$grey = $this->db->query("SELECT konstruksi,tanggal,operator,shift_op FROM data_ig WHERE tanggal='2025-05-01' AND shift_op='31' AND operator NOT IN ('ana','efiana','nina','kusnita2','eva','eka') GROUP BY konstruksi");
      //$grey = $this->db->query("SELECT konstruksi,tanggal,operator,shift_op FROM data_ig WHERE tanggal='2025-03-01' AND shift_op!='31' AND operator NOT IN ('ana','efiana','nina','kusnita2','eva','eka') GROUP BY konstruksi");
      $no=1;
      ?>
      <table border="1">
          <tr>
              <th>NO</th>
              <th>KONSTRUKSI</th>
              <th>ORI1</th>
              <th>ORI2</th>
              <th>ORI3</th>
              <th>ORIASLI</th>
              
              <th>BP</th>
              <th>BC</th>
              <th>BS</th>
          </tr>
      
      <?php
      foreach($grey->result() as $val){
          $ks = $val->konstruksi;
          //$ukuranORI3 = $this->db->query("SELECT SUM(ukuran_ori) AS jml FROM data_if WHERE konstruksi='$ks' AND tgl_potong='2025-04-01' AND shift_op!='31' AND operator IN ('ana','efiana','nina','kusnita2','eva','eka') AND ukuran_ori>45")->row("jml");
          $ukuranORI = $this->db->query("SELECT SUM(ukuran_ori) AS jml FROM data_if WHERE konstruksi='$ks' AND tgl_potong BETWEEN '2025-04-01' AND '2025-04-30' AND ukuran_ori>45")->row("jml");
          //$ukuranORI2 = $this->db->query("SELECT SUM(ukuran_ori) AS jml FROM data_if WHERE konstruksi='$ks' AND tgl_potong='2025-05-01' AND shift_op='31' AND operator IN ('ana','efiana','nina','kusnita2','eva','eka') AND ukuran_ori>45")->row("jml");
          //$oriAsli = $ukuranORI2 + $ukuranORI + $ukuranORI3;
          
          
          //$ukuranBC1 = $this->db->query("SELECT SUM(ukuran_ori) AS jml FROM data_if WHERE konstruksi='$ks' AND tgl_potong='2025-04-01' AND shift_op!='31' AND operator IN ('ana','efiana','nina','kusnita2','eva','eka') AND ukuran_ori>18 AND ukuran_ori<45")->row("jml");
          $ukuranBC2 = $this->db->query("SELECT SUM(ukuran_ori) AS jml FROM data_if WHERE konstruksi='$ks' AND tgl_potong BETWEEN '2025-04-01' AND '2025-04-30' AND ukuran_ori>18 AND ukuran_ori<45")->row("jml");
          //$ukuranBC3 = $this->db->query("SELECT SUM(ukuran_ori) AS jml FROM data_if WHERE konstruksi='$ks' AND tgl_potong='2025-05-01' AND shift_op='31' AND operator IN ('ana','efiana','nina','kusnita2','eva','eka') AND ukuran_ori>18 AND ukuran_ori<45")->row("jml");
         //$ukuranBC = $ukuranBC1 + $ukuranBC2 + $ukuranBC3;
          
          //$ukuranBCE1 = $this->db->query("SELECT SUM(ukuran_ori) AS jml FROM data_if WHERE konstruksi='$ks' AND tgl_potong='2025-04-01' AND shift_op!='31' AND operator IN ('ana','efiana','nina','kusnita2','eva','eka') AND ukuran_ori>2 AND ukuran_ori<18")->row("jml");
          $ukuranBCE2 = $this->db->query("SELECT SUM(ukuran_ori) AS jml FROM data_if WHERE konstruksi='$ks' AND tgl_potong BETWEEN '2025-04-01' AND '2025-04-30' AND ukuran_ori>2 AND ukuran_ori<18")->row("jml");
          //$ukuranBCE3 = $this->db->query("SELECT SUM(ukuran_ori) AS jml FROM data_if WHERE konstruksi='$ks' AND tgl_potong='2025-05-01' AND shift_op='31' AND operator IN ('ana','efiana','nina','kusnita2','eva','eka') AND ukuran_ori>2 AND ukuran_ori<18")->row("jml");
          //$ukuranBCE = $ukuranBCE1 + $ukuranBCE2 + $ukuranBCE3;
          
          //$ukuranBS1 = $this->db->query("SELECT SUM(ukuran_bs) AS jml FROM data_if WHERE konstruksi='$ks' AND tgl_potong='2025-04-01' AND shift_op!='31' AND operator IN ('ana','efiana','nina','kusnita2','eva','eka') AND ukuran_bs>0")->row("jml");
          //$ukuranBS2 = $this->db->query("SELECT SUM(ukuran_bs) AS jml FROM data_if WHERE konstruksi='$ks' AND tgl_potong BETWEEN '2025-04-02' AND '2025-04-31' AND operator IN ('ana','efiana','nina','kusnita2','eva','eka') AND ukuran_ori>0")->row("jml");
          //$ukuranBS3 = $this->db->query("SELECT SUM(ukuran_bs) AS jml FROM data_if WHERE konstruksi='$ks' AND tgl_potong='2025-05-01' AND shift_op='31' AND operator IN ('ana','efiana','nina','kusnita2','eva','eka') AND ukuran_ori>0")->row("jml");
          $bs = 0;
          
          ?>
          <tr>
              <td><?=$no;?></td>
              <td><?=$val->konstruksi;?></td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td><?=$ukuranORI;?></td>
              <td><?=$ukuranBC2;?></td>
              <td><?=$ukuranBCE2;?></td>
              <td><?=$bs;?></td>
          </tr>
          <?php $no++;
      }
      echo "</table>";
  }
  function jege(){
      $dbq = $this->db->query("SELECT * FROM new_tb_isi WHERE kd='PKT274' AND kode LIKE 'JG%'");
      foreach($dbq->result() as $val){
          $kd = $val->kode;
          $pch = $this->data_model->get_byid('data_fol',['kode_roll'=>$kd])->row("joindfrom");
          
          $x = explode(',', $pch);
          $cek_ukuran1 = $this->data_model->get_byid('data_ig', ['kode_roll' => $x[0] ])->row("ukuran_ori");
          if($cek_ukuran1 > 0){
                $this->data_model->saved('new_tb_isi', [
                  'kd' => 'PKT213',
                  'konstruksi' => 'RJ15',
                  'siap_jual' => 'y',
                  'kode' => $x[0],
                  'ukuran' => $cek_ukuran1,
                  'status' => 'oke',
                  'satuan' =>'Meter',
                  'validasi' => 'valid'
                ]);
          } else {
              $this->data_model->saved('new_tb_isi', [
                  'kd' => 'PKT213',
                  'konstruksi' => 'RJ15',
                  'siap_jual' => 'y',
                  'kode' => 'XXA',
                  'ukuran' => 0,
                  'status' => 'oke',
                  'satuan' =>'Meter',
                  'validasi' => 'valid' ]);
          }
          $cek_ukuran2 = $this->data_model->get_byid('data_ig', ['kode_roll' => $x[1] ])->row("ukuran_ori");
          if($cek_ukuran2 > 0){
                $this->data_model->saved('new_tb_isi', [
                  'kd' => 'PKT213',
                  'konstruksi' => 'RJ15',
                  'siap_jual' => 'y',
                  'kode' => $x[1]=='' ? 'S':$x[1],
                  'ukuran' => $cek_ukuran1,
                  'status' => 'oke',
                  'satuan' =>'Meter',
                  'validasi' => 'valid'
                ]);
          } else {
              $this->data_model->saved('new_tb_isi', [
                  'kd' => 'PKT213',
                  'konstruksi' => 'RJ15',
                  'siap_jual' => 'y',
                  'kode' => 'XXB',
                  'ukuran' => 0,
                  'status' => 'oke',
                  'satuan' =>'Meter',
                  'validasi' => 'valid']);
          }
            echo $val->kode." - $x[0] - $x[1] <br>";
      }
  }

}











