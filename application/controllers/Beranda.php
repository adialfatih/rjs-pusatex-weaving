<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Beranda extends CI_Controller
{
  function __construct()
  {
      parent::__construct();
      $this->load->model('data_model');
      if($this->session->userdata('login_form') != "rindangjati_sess"){
        redirect(base_url('login'));
      }
  }
   
  function index(){ 
      $data = array(
          'title' => 'Welcome - PPC Weaving',
          'sess_nama' => $this->session->userdata('nama'),
          'sess_hak' => $this->session->userdata('hak'),
          'sess_dep' => $this->session->userdata('departement')
      );
      $this->load->view('part/main_head', $data);
      $this->load->view('part/left_sidebar', $data);
      $this->load->view('beranda_view', $data);
      $this->load->view('part/main_js');
  } //end

  function managerdashboard(){
        $hak_akses = $this->session->userdata('hak');
        if($hak_akses == "Manager"){
            $data = array(
                'title' => 'Welcome Manager - PPC Weaving',
                'sess_nama' => $this->session->userdata('nama'),
                'sess_hak' => $this->session->userdata('hak'),
                'sess_dep' => $this->session->userdata('departement')
            );
            $this->load->view('part/main_head', $data);
            $this->load->view('part/left_sidebar2', $data);
            $this->load->view('beranda_view', $data);
            $this->load->view('part/main_js');
        } else {
            $this->load->view('block');
        }   
  }
  function rekappiutang(){
    $hak_akses = $this->session->userdata('hak');
    $dtcust = $this->db->query("SELECT * FROM `dt_konsumen` ORDER BY nama_konsumen");
    $arrsd = array();
    foreach($dtcust->result() as $val):
        $arrsd[] = '"'.$val->nama_konsumen.'"';
    endforeach;
    $dataauto = implode(',', $arrsd);
    if($hak_akses == "Manager"){
        $data = array(
            'title' => 'tesRekap Penjualan',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_hak' => $this->session->userdata('hak'),
            'qrdata' => $this->db->query("SELECT * FROM a_nota WHERE no_sj NOT LIKE '%SLD%' ORDER BY tgl_nota DESC LIMIT 500"),
            'qrdata_txt' => "SELECT * FROM a_nota WHERE no_sj NOT LIKE '%SLD%' ORDER BY tgl_nota DESC",
            'daterange' => 'true',
            'tanggalRange' => 'none',
            'autocomplet' => "true",
            'dataauto' => $dataauto,
            'sess_dep' => $this->session->userdata('departement')
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar2', $data);
        $this->load->view('manager/rekap_piutang', $data);
        $this->load->view('part/main_js_dttable');
    } else {
        $this->load->view('block');
    }   
  } //end
  function stoksmt(){
    $hak_akses = $this->session->userdata('hak');
    if($hak_akses == "Manager"){
        $data = array(
            'title' => 'Rekap Penjualan',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_hak' => $this->session->userdata('hak'),
            'daterange' => 'true',
            'tanggalRange' => 'none',
            'sess_dep' => $this->session->userdata('departement')
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar2', $data);
        $this->load->view('manager/stok_gudang_smt', $data);
        $this->load->view('part/main_js_dttable');
    } else {
        $this->load->view('block');
    }   
  } //end

  function rekappenjualan(){
    $rangetgl = $this->input->post('datesr');
    $cust = $this->input->post('cust');
    $ex = explode(' - ', $rangetgl);
    $tgl1 = explode('/', $ex[0]);
    $formatTgl1 = $tgl1[2]."-".$tgl1[0]."-".$tgl1[1];
    $tgl2 = explode('/', $ex[1]);
    $formatTgl2 = $tgl2[2]."-".$tgl2[0]."-".$tgl2[1];
    $hak_akses = $this->session->userdata('hak');
    if($cust==""){
        $txt = "SELECT * FROM a_nota WHERE no_sj NOT LIKE '%SLD%' AND tgl_nota BETWEEN '$formatTgl1' AND '$formatTgl2' ORDER BY tgl_nota ASC";
        $showcus = 'Semua Customer';
    } else {
        $txt = "SELECT * FROM view_nota2 WHERE no_sj NOT LIKE '%SLD%' AND nama_konsumen LIKE '$cust' AND tgl_nota BETWEEN '$formatTgl1' AND '$formatTgl2' ORDER BY tgl_nota ASC";
        $showcus = $cust;
    }
    //'qrdata' => $this->db->query("SELECT * FROM a_nota WHERE no_sj NOT LIKE '%SLD%' AND tgl_nota BETWEEN '$formatTgl1' AND '$formatTgl2' ORDER BY tgl_nota ASC"),
    $dtcust = $this->db->query("SELECT * FROM `dt_konsumen` ORDER BY nama_konsumen");
    $arrsd = array();
    foreach($dtcust->result() as $val):
        $arrsd[] = '"'.$val->nama_konsumen.'"';
    endforeach;
    $dataauto = implode(',', $arrsd);
    if($hak_akses == "Manager"){
        $data = array(
            'title' => 'Rekap Penjualan',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_hak' => $this->session->userdata('hak'),
            'qrdata' => $this->db->query($txt),
            'qrdata_txt' => $txt,
            'daterange' => 'true',
            'tanggalRange' => 'yes',
            'tanggal1' => $formatTgl1,
            'tanggal2' => $formatTgl2,
            'showcus' => $showcus,
            'autocomplet' => "true",
            'dataauto' => $dataauto,
            'sess_dep' => $this->session->userdata('departement')
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar2', $data);
        $this->load->view('manager/rekap_piutang', $data);
        $this->load->view('part/main_js_dttable');
    } else {
        $this->load->view('block');
    }   
  } //end

  function saldopiutang(){
    $hak_akses = $this->session->userdata('hak');
    if($hak_akses == "Manager"){
        $data = array(
            'title' => 'Saldo Piutang',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_hak' => $this->session->userdata('hak'),
            'sess_dep' => $this->session->userdata('departement')
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar2', $data);
        $this->load->view('manager/saldo_piutang2kolom', $data);
        $this->load->view('part/main_js_dttable3');
    } else {
        $this->load->view('block');
    }   
  } //end

  function dsbmng(){ 
    $data = array(
        'title' => 'Manager Dashboard - PPC Weaving',
        'sess_nama' => $this->session->userdata('nama'),
        'sess_dep' => $this->session->userdata('departement')
    );
    $this->load->view('part/main_head', $data);
    $this->load->view('part/left_sidebar', $data);
    $this->load->view('beranda_view2', $data);
    $this->load->view('part/main_js');
} //end

    function dsbopt(){ 
        $data = array(
            'title' => 'Operator Dashboard - PPC Weaving',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_dep' => $this->session->userdata('departement')
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('beranda_view2', $data);
        $this->load->view('part/main_js');
    } //end
    function pkglist(){ 
        $dep = $this->session->userdata('departement');
        $yglogin = $this->session->userdata('nama');
        $nquery_data = $this->db->query("SELECT * FROM new_tb_packinglist WHERE siap_jual='n' ORDER BY id_kdlist DESC LIMIT 1000");
        
        $data = array(
            'title' => 'Data Packaging List',
            'sess_nama' => $this->session->userdata('nama'),
            'loc' => $this->session->userdata('departement'),
            'notif' => 0,
            'dep' => $dep,
            'tipe' => 'rjs',
            'tipes' => 'rjs',
            'dt_list' => $nquery_data,
            'sess_dep' => $this->session->userdata('departement')
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('new_page/packaging_list_data', $data);
        $this->load->view('part/main_js_dttable');
    } //end
    function pkglist2(){ 
        $dep = $this->session->userdata('departement');
        $yglogin = $this->session->userdata('nama');
        $nquery_data = $this->db->query("SELECT * FROM new_tb_packinglist WHERE lokasi_now='Pusatex' AND siap_jual='y' ORDER BY id_kdlist DESC LIMIT 1000");
        
        $data = array(
            'title' => 'Data Packaging List',
            'sess_nama' => $this->session->userdata('nama'),
            'loc' => $this->session->userdata('departement'),
            'notif' => 0,
            'dep' => $dep,
            'tipe' => 'pkg',
            'tipes' => 'pst',
            'dt_list' => $nquery_data,
            'sess_dep' => $this->session->userdata('departement')
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('new_page/packaging_list_data', $data);
        $this->load->view('part/main_js_dttable');
    } //end
    function prosesretur(){
        $r = $this->input->post('rkode');
        $k = $this->input->post('ket');
        $txt = $this->input->post('txt');
        $kode_roll = $this->input->post('kode_roll');
        $tiperetur = $this->input->post('tiperetur');
        $cn = count($kode_roll);
        if($tiperetur=='rjs'){
            for ($i=0; $i < $cn; $i++) { 
                $kd = $kode_roll[$i];
                $kdpkg = $this->data_model->get_byid('new_tb_isi', ['siap_jual'=>'n','kode'=>$kd])->row("kd");
                $this->db->query("DELETE FROM new_tb_isi WHERE siap_jual='n' AND kode='$kd'");
                $total_roll = $this->data_model->get_byid('new_tb_isi',['kd'=>$kdpkg])->num_rows();
                $panjang_roll = $this->db->query("SELECT SUM(ukuran) as ukr FROM new_tb_isi WHERE kd='$kdpkg'")->row("ukr");
                $this->data_model->updatedata('kd',$kdpkg,'new_tb_packinglist',['jumlah_roll'=>$total_roll,'ttl_panjang'=>round($panjang_roll,1)]);
                $this->data_model->saved('notifikasi',['departement'=>'RJS','notif'=>$txt[$i], 'tm_stmp'=>date('Y-m-d H:i:s')]);
                $this->data_model->updatedata('kode_roll',$kd,'data_ig', ['loc_now'=>'RJS']);
                $this->db->query("DELETE FROM kiriman_pusatex WHERE kode_roll='$kd'");
            }
            redirect(base_url('packing-list-retur'));
        } else {
            for ($i=0; $i < $cn; $i++) { 
                $kd = $kode_roll[$i];
                $kdpkg = $this->data_model->get_byid('new_tb_isi', ['siap_jual'=>'y','kode'=>$kd])->row("kd");
                $sj = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$kdpkg])->row("no_sj");
                $this->db->query("DELETE FROM new_tb_isi WHERE siap_jual='y' AND kode='$kd'");
                $total_roll = $this->data_model->get_byid('new_tb_isi',['kd'=>$kdpkg])->num_rows();
                $panjang_roll = $this->db->query("SELECT SUM(ukuran) as ukr FROM new_tb_isi WHERE kd='$kdpkg'")->row("ukr");
                $this->data_model->updatedata('kd',$kdpkg,'new_tb_packinglist',['jumlah_roll'=>$total_roll,'ttl_panjang'=>round($panjang_roll,1)]);
                $nota = $this->data_model->get_byid('a_nota', ['kd'=>$kdpkg])->row_array();
                $harga_satuan = $nota['harga_satuan'];
                $pajak_ppn = $nota['pajak_ppn'];
                $total_harga = floatval($panjang_roll) * floatval($harga_satuan);
                $total_harga_asli = round($total_harga,2);
                if(floatval($pajak_ppn) > 0){
                    $tambah_pajak = (intval($pajak_ppn) / 100) * floatval($total_harga_asli);
                    $total_harga_pajak = $total_harga_asli + $tambah_pajak;
                } else {
                    $total_harga_pajak = $total_harga_asli;
                }
                $this->data_model->updatedata('kd',$kdpkg,'a_nota',['jml_roll'=>$total_roll,'total_panjang'=>round($panjang_roll,1),'total_harga'=>$total_harga_pajak, 'total_harga_asli'=>$total_harga_asli]);
                $this->data_model->saved('notifikasi',['departement'=>'RJS','notif'=>$txt[$i], 'tm_stmp'=>date('Y-m-d H:i:s')]);
                $this->data_model->updatedata('kode_roll',$kd,'data_fol', ['posisi'=>'Pusatex']);
            }
            redirect(base_url('packing-list-retur'));
        }
    }
    function pkglist3(){ 
        $dep = $this->session->userdata('departement');
        $yglogin = $this->session->userdata('nama');
        $r = $this->input->post('returKode');
        $k = $this->input->post('ket');
        $tiperetur = $this->input->post('tiperetur');
        $data = array(
            'title' => 'Data Packaging List Retur',
            'sess_nama' => $this->session->userdata('nama'),
            'loc' => $this->session->userdata('departement'),
            'notif' => 0,
            'dep' => $dep,
            'rkode' => $r,
            'ket' => $k,
            'tiperetur' => $tiperetur,
            'sess_dep' => $this->session->userdata('departement')
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('new_page/packaging_list_dataretur', $data);
        $this->load->view('part/main_js_dttable');
    } //end

    function pkglistbs(){ 
        $dep = $this->session->userdata('departement');
        $yglogin = $this->session->userdata('nama');
        $data = array(
            'title' => 'Data Packaging List BS',
            'sess_nama' => $this->session->userdata('nama'),
            'loc' => $this->session->userdata('departement'),
            'notif' => 0,
            'dep' => $dep,
            'sess_dep' => $this->session->userdata('departement')
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('new_page/packaging_list_databs', $data);
        $this->load->view('part/main_js_dttable');
    } //end

    function pkg_pengiriman(){
        $dep = $this->session->userdata('departement');
        $data = array(
            'title' => 'Data Pengiriman Packaging List',
            'sess_nama' => $this->session->userdata('nama'),
            'loc' => $this->session->userdata('departement'),
            'sess_dep' => $this->session->userdata('departement'),
            'dt_list' => $this->db->query("SELECT * FROM new_tb_trackpaket WHERE kirim_dari='$dep' OR kirim_ke='$dep' ORDER BY id_pengiriman DESC")
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('new_page/packaging_list_data_pengiriman', $data);
        $this->load->view('part/main_js_dttable');
    } //end

    function chTanggal(){
        $kd = $this->input->post('kd');
        $tgl = $this->input->post('tgl');
        $this->data_model->updatedata('kd',$kd,'new_tb_packinglist',['tanggal_dibuat'=>$tgl]);
        redirect(base_url('packing-list'));
    }
    
    function chPenerimaPaket(){
         $kd = $this->input->post('kd');
         $pnr = $this->input->post('pnr');
         $this->data_model->updatedata('kd',$kd,'new_tb_packinglist',['customer'=>$pnr]);
         redirect(base_url('packing-list'));
    }
    function chPenerimaPaket2(){
        $kd = $this->input->post('kd');
        $tujuans = $this->input->post('tujuans');
        $this->data_model->updatedata('kd',$kd,'new_tb_packinglist',['customer'=>$tujuans]);
        redirect(base_url('packing-list'));
   }
    function chPenerimaPaket3(){
       $kd = $this->input->post('kd');
       $tujuans = $this->input->post('tjs');
       $this->data_model->updatedata('kd',$kd,'new_tb_packinglist',['customer'=>$tujuans]);
       redirect(base_url('packing-list-folding'));
    }
    function ceksuratjalan(){
        $sj = $this->input->post('id');
        $cek = $this->data_model->get_byid('new_tb_packinglist', ['no_sj'=>$sj]);
        echo "<table class='table table-bordered'>";
        echo "<tr>";
        echo "<td><strong>Kode</strong></td>";
        echo "<td><strong>Roll</strong></td>";
        echo "<td><strong>Jumlah</strong></td>";
        echo "</tr>";
        foreach($cek->result() as $val){
            echo "<tr>";
            echo "<td><a href='https://sm.rindangjati.com/cetakstx/packinglist/".$val->kd."/29' target='_blank'>".$val->kd."</a></td>";
            echo "<td>".$val->jumlah_roll."</td>";
            echo "<td>".$val->ttl_panjang."</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    function updatePaketDiPusatex(){
        $dbs = $this->db->query("SELECT * FROM new_tb_packinglist WHERE siap_jual='n' ORDER BY id_kdlist DESC LIMIT 50");
        echo "oke<br>";
        foreach($dbs->result() as $val){
            $_kd = $val->kd;
            $_cus = $val->customer;
            //echo "$_kd - $_cus<br>";
            if($_cus == "Grey"){
                echo "----Grey---<br>";
                $ndata = $this->data_model->get_byid('new_tb_isi', ['kd'=>$_kd]);
                foreach($ndata->result() as $bal){
                    echo "--- $bal->kode ---<br>";
                    $this->data_model->updatedata('kode_roll',$bal->kode,'kiriman_pusatex',['tujuan_proses'=>'Grey']);
                }
                echo "----Grey---<br>";
            }
            if($_cus == "Finish"){
                echo "----Finish---<br>";
                $ndata = $this->data_model->get_byid('new_tb_isi', ['kd'=>$_kd]);
                foreach($ndata->result() as $bal){
                    echo "--- $bal->kode ---<br>";
                    $this->data_model->updatedata('kode_roll',$bal->kode,'kiriman_pusatex',['tujuan_proses'=>'Finish']);
                }
                echo "----Finish---<br>";
            }
        }
    }
    function tarikrjs(){
        $tarikRJS = $this->input->post('tarikRJS');
        $tujuanTarik = $this->input->post('tujuanTarik');
        $nulls = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$tarikRJS])->row("jns_fold");
        if($nulls=="null"){
        if($tarikRJS!="" && $tujuanTarik!=""){
            $kons = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$tarikRJS])->row("kode_konstruksi");
            $cekPkg = $this->db->query("SELECT kd FROM new_tb_packinglist WHERE kd LIKE 'PKT%' ORDER BY id_kdlist DESC LIMIT 1");
                if($cekPkg->num_rows() == 0){
                    $kdpkg = "PKT1";
                } else {
                    $kode_sebelum = $cekPkg->row("kd");
                    $ex = explode('T', $kode_sebelum);
                    $num = intval($ex[1]) + 1;
                    $kdpkg = "PKT".$num."";
                }
            $dtlist = [
                'kode_konstruksi' => $kons,
                'kd' => $kdpkg,
                'tanggal_dibuat' => date('Y-m-d'),
                'tms_tmp' => date('Y-m-d H:i:s'),
                'lokasi_now' => 'Pusatex',
                'siap_jual' => 'y',
                'jumlah_roll' => 0,
                'ttl_panjang' => 0,
                'kepada' => 'NULL',
                'no_sj' => 'NULL',
                'ygbuat' => $this->session->userdata('nama'),
                'jns_fold' => 'Grey',
                'customer' => $tujuanTarik
            ];
            $this->data_model->saved('new_tb_packinglist', $dtlist);
            $qry = $this->data_model->get_byid('new_tb_isi', ['kd'=>$tarikRJS])->result();
            foreach($qry as $val){
                $kode_roll  = $val->kode;
                $ukuran     = $val->ukuran;
                $this->data_model->saved('new_tb_isi',[
                    'kd'         => $kdpkg,
                    'konstruksi' => $kons,
                    'siap_jual'  => 'y',
                    'kode'       => $kode_roll,
                    'ukuran'     => $ukuran,
                    'status'     => 'oke',
                    'satuan'     => 'Meter',
                    'validasi'   => 'valid'
                ]);
                $this->data_model->saved('data_fol',[
                    'kode_roll'     => $kode_roll,
                    'konstruksi'    => $kons,
                    'ukuran'        => $ukuran,
                    'jns_fold'      => 'Grey',
                    'tgl'           => date('Y-m-d'),
                    'operator'      => $this->session->userdata('nama'),
                    'loc'           => 'Pusatex',
                    'posisi'        => $kdpkg,
                    'joinss'        => 'false',
                    'joindfrom'     => 'null',
                    'jam_input'     => date('Y-m-d H:i:s'),
                    'shift'         => 0
                ]); 
            }
            $cek = $this->data_model->get_byid('data_fol', ['posisi'=>$kdpkg])->num_rows();
            $cek2= $this->db->query("SELECT SUM(ukuran) AS jml FROM data_fol WHERE posisi='$kdpkg'")->row("jml");
            $this->data_model->updatedata('kd',$kdpkg,'new_tb_packinglist',['jumlah_roll'=>$cek,'ttl_panjang'=>round($cek2,1)]);
            $this->data_model->updatedata('kd',$tarikRJS,'new_tb_packinglist',['jns_fold'=>'tarik']);
            echo "Sukses tarik ".$tarikRJS." Ke -> ".$kdpkg ;
        } else {
            echo "Anda tidak mengisi data dengan benar.!!";
        }
        } else {
            echo "Paket sudah ditarik ke Penjualan";
        }
    }
    
}
?>