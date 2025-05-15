<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cetakrjs extends CI_Controller
{
  function __construct()
  {
      parent::__construct();
      $this->load->model('data_model');
      $this->load->library('pdf');
      date_default_timezone_set("Asia/Jakarta");
    //   if($this->session->userdata('login_form') != "grafamedia_admin"){
    //       redirect(base_url("login"));
    //   }
  }
  function index(){
    echo "";
  }
  
  function sj(){
    $token = $this->uri->segment(3);
    $wicode = $this->uri->segment(4);
    $ar = array(
      '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    );
    $ceksj = $this->data_model->get_byid('surat_jalan',['sha1(id_sj)'=>$token]);
    if($ceksj->num_rows() == 1){
        $ex = explode('-', $ceksj->row("tgl_kirim"));
        $printTgl = $ex[2]." ".$ar[$ex[1]]." ".$ex[0];
        $nosj = $ceksj->row('no_sj');
        $pkg = $this->data_model->get_byid('new_tb_packinglist', ['no_sj'=>$nosj]);
             $pdf = new FPDF('p','mm','A4');
              // membuat halaman baru
              $pdf->AddPage();
              // setting jenis font yang akan digunakan
              $pdf->SetTitle('Surat Jalan - '.$nosj.'');

              $pdf->SetFont('Arial','B',14);
              $pdf->Image('https://weaving.rdgjt.com/assets/logo_rjs2.jpg', 13, 10, 25, 0, 'JPG');
              $pdf->Cell(0, 6, 'PT RINDANG JATI SPINNING', 0, 0, 'C' );
              $pdf->SetFont('Arial','',10);
              $pdf->Ln(6);
              $pdf->Cell(0, 6, 'Jl Raya Jatirejo RT 004 RW 001 Kel Jatirejo Kec Ampel Gading Pemalang', 0, 0, 'C' );
              $pdf->Ln(5);
              $pdf->Cell(0, 6, 'TELP (0285) 5750072 FAX (0285) 5750073', 0, 0, 'C' );
              $pdf->Line(13, 33, 210-15, 33);
              $pdf->Line(13, 33.1, 210-15, 33.1);
              $pdf->Line(13, 33.2, 210-15, 33.2);
              $pdf->Line(13, 33.8, 210-15, 33.8);
              $pdf->Ln(18);
              $pdf->Cell(130, 5, '', 0, 0, 'L' );
              $pdf->Cell(17, 5, 'No', 0, 0, 'L' );
              $pdf->Cell(5, 5, ':', 0, 0, 'L' );
              $pdf->Cell(30, 5, ''.$nosj.'', 0, 0, 'L' );
              $pdf->Ln(5);
              $pdf->Cell(130, 5, '', 0, 0, 'L' );
              $pdf->Cell(17, 5, 'Tanggal', 0, 0, 'L' );
              $pdf->Cell(5, 5, ':', 0, 0, 'L' );
              $pdf->Cell(30, 5, ''.$printTgl.'', 0, 0, 'L' );
              $pdf->Ln(5);
              $pdf->Cell(2, 5, '', 0, 0, 'L' );
              $pdf->Cell(130, 5, 'Kepada Yth', 0, 0, 'L' );
              $pdf->Ln(5);
              $pdf->Cell(2, 5, '', 0, 0, 'L' );
              
              if($ceksj->row('tujuan_kirim') == 'Pusatex' OR $ceksj->row('tujuan_kirim') == 'pusatex'){
                  $nama_orang = 'Istiqomah';
                  $pdf->Cell(130, 5, 'GUDANG RINDANG JATI - PUSATEX', 0, 0, 'L' );
                  $pdf->Ln(5);
                  $pdf->Cell(2, 5, '', 0, 0, 'L' );
                  $pdf->Cell(130, 5, 'WATUSALAM', 0, 0, 'L' );
                  $_tipe2 = "Pengiriman";
              } else {
                  $nm_cus = $this->data_model->get_byid('dt_konsumen',['id_konsumen'=>$ceksj->row('id_customer')])->row("nama_konsumen");
                  $pdf->Cell(130, 5, ''.strtoupper($nm_cus).'', 0, 0, 'L' );
                  $nama_orang = 'Achmad Syafiq';
                  $pdf->Ln(5);
                  $pdf->Cell(2, 5, '', 0, 0, 'L' );
                  $pdf->Cell(130, 5, 'di Tempat', 0, 0, 'L' );
                  $_tipe2 = "Penjualan";
              }
              
              $pdf->Ln(10);
              $pdf->SetFont('Arial','B',11);
              $pdf->Cell(0, 6, 'SURAT JALAN', 0, 0, 'C' );
              $pdf->Ln(10);
              $pdf->SetFont('Arial','',10);
              $pdf->Cell(2, 5, '', 0, 0, 'L' );
              $pdf->Cell(0, 5, 'Berikut kami kirimkan barang-barang dibawah ini dengan No Mobil G :', 0, 0, 'L' );
              $pdf->Ln(5);
              $pdf->Cell(2, 5, '', 0, 0, 'L' );
              $pdf->Cell(0, 5, 'Driver :', 0, 0, 'L' );
              $pdf->SetFont('Arial','B',10);
              if($wicode==2){
              $pdf->Ln(7);
              $pdf->Cell(3, 8, '', 0, 0, 'L' );
              $pdf->Cell(35, 8, 'Qty (ROLL)', 1, 0, 'C' );
              // if($_tipe2 == "Pengiriman"){
              //   $pdf->Cell(90, 8, 'Panjang (Meter)', 1, 0, 'C' );
              // } else {
                $pdf->Cell(90, 8, 'Panjang', 1, 0, 'C' );
              //}
              $pdf->Cell(55, 8, 'Keterangan', 1, 0, 'C' );
                $jmlroll = 0; $pjg =0;
                $pdf->SetFont('Arial','',10);
                foreach($pkg->result() as $val) { 
                    $pdf->Ln(8);
                    $pdf->Cell(3, 8, '', 0, 0, 'L' );
                    $pdf->Cell(35, 8, ''.$val->jumlah_roll.'', 1, 0, 'C' );
                    if($val->ttl_panjang == floor($val->ttl_panjang)){
                        $ttlpjg = number_format($val->ttl_panjang,0,',','.'); 
                    } else {
                        $ttlpjg = number_format($val->ttl_panjang,2,',','.');
                    }
                    if($val->jns_fold == "Finish"){
                      $pdf->Cell(90, 8, ''.$ttlpjg.' Yard', 1, 0, 'C' );
                    } else {
                      $pdf->Cell(90, 8, ''.$ttlpjg.' Meter', 1, 0, 'C' );
                    }
                    
                    $pdf->Cell(55, 8, ''.$val->kode_konstruksi.'', 1, 0, 'C' );
                    $jmlroll += intval($val->jumlah_roll);
                    $pjg += floatval($val->ttl_panjang);
                }
                    $pjg2 = round($pjg,2);
                    if(fmod($pjg2, 1) !== 0.00){
                        $printTotal = number_format($pjg2,2,',','.');
                    } else {
                        $printTotal = number_format($pjg2,0,',','.');
                    }
                $pdf->SetFont('Arial','B',10);
                $pdf->Ln(8);
                $pdf->Cell(3, 8, '', 0, 0, 'L' );
                $pdf->Cell(35, 8, $jmlroll, 1, 0, 'C' );
                $pdf->Cell(90, 8, $printTotal, 1, 0, 'C' );
                $pdf->Cell(55, 8, '', 1, 0, 'C' );
              } else {
              $pdf->Ln(7);
              $pdf->Cell(3, 8, '', 0, 0, 'L' );
              $pdf->Cell(15, 8, 'No', 1, 0, 'C' );
              $pdf->Cell(30, 8, 'Qty (ROLL)', 1, 0, 'C' );
              // if($_tipe2 == "Pengiriman"){
              //   $pdf->Cell(70, 8, 'Panjang (Meter)', 1, 0, 'C' );
              // } else {
                $pdf->Cell(70, 8, 'Panjang', 1, 0, 'C' );
              //}
              //$pdf->Cell(70, 8, 'Panjang (Meter)', 1, 0, 'C' );
              $pdf->Cell(40, 8, 'Konstruksi', 1, 0, 'C' );
              $pdf->Cell(25, 8, 'Kode Pkg', 1, 0, 'C' );
              $pdf->SetFont('Arial','',10);
                $jmlroll = 0; $pjg =0;
                foreach($pkg->result() as $n => $val) { 
                    $no = $n+1;
                    $pdf->Ln(8);
                    $pdf->Cell(3, 8, '', 0, 0, 'L' );
                    $pdf->Cell(15, 8, $no, 1, 0, 'C' );
                    $pdf->Cell(30, 8, ''.$val->jumlah_roll.'', 1, 0, 'C' );
                    if($val->ttl_panjang == floor($val->ttl_panjang)){
                        $ttlpjg = number_format($val->ttl_panjang,0,',','.'); 
                    } else {
                        $ttlpjg = number_format($val->ttl_panjang,2,',','.');
                    }
                    if($val->jns_fold == "Finish"){
                      $pdf->Cell(70, 8, ''.$ttlpjg.' Yard', 1, 0, 'C' );
                    } else {
                      $pdf->Cell(70, 8, ''.$ttlpjg.' Meter', 1, 0, 'C' );
                    }
                    //$pdf->Cell(70, 8, ''.number_format($val->ttl_panjang,2,',','.').'', 1, 0, 'C' );
                    $pdf->Cell(40, 8, ''.$val->kode_konstruksi.'', 1, 0, 'C' );
                    $pdf->Cell(25, 8, ''.$val->kd.'', 1, 0, 'C' );
                    $jmlroll += intval($val->jumlah_roll);
                    $pjg += floatval($val->ttl_panjang);
                }
                    $pjg2 = round($pjg,2);
                    if(fmod($pjg2, 1) !== 0.00){
                        $printTotal = number_format($pjg2,2,',','.');
                    } else {
                        $printTotal = number_format($pjg2,0,',','.');
                    }
                $pdf->SetFont('Arial','B',10);
                $pdf->Ln(8);
                $pdf->Cell(3, 8, '', 0, 0, 'L' );
                $pdf->Cell(15, 8, 'TTL', 1, 0, 'C' );
                $pdf->Cell(30, 8, $jmlroll, 1, 0, 'C' );
                $pdf->Cell(70, 8, $printTotal, 1, 0, 'C' );
                $pdf->Cell(40, 8, '', 1, 0, 'C' );
                $pdf->Cell(25, 8, '', 1, 0, 'C' );
              }
              $pdf->SetFont('Arial','',10);
              $pdf->Ln(20);
              $pdf->Cell(3, 8, '', 0, 0, 'L' );
              $pdf->Cell(110, 8, 'Diterima Oleh', 0, 0, 'L' );
              $pdf->Cell(30, 8, 'Hormat Kami,', 0, 0, 'L' );
              $pdf->Ln(5);
              $pdf->Cell(3, 8, '', 0, 0, 'L' );
              $pdf->Cell(110, 8, '', 0, 0, 'L' );
              $pdf->Cell(30, 8, 'PT Rindang Jati Spinning,', 0, 0, 'L' );
              $pdf->Ln(25);
              $pdf->Cell(3, 8, '', 0, 0, 'L' );
              $pdf->Cell(110, 8, '', 0, 0, 'L' );
              $pdf->Cell(30, 8, $nama_orang, 0, 0, 'L' );
              $pdf->Output('I','cetak-surat-jalan.pdf'); 
    } else {
        echo "Token Erorr";
    }
  } //end

  

  function packinglist(){
    $token = $this->uri->segment(3);
    //$cekdt = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$token]);
    $cekdt = $this->db->query("SELECT kd,tanggal_dibuat,customer FROM new_tb_packinglist WHERE kd='$token'");
    if($cekdt->num_rows() == 1){
    $ex = explode('-', $cekdt->row("tanggal_dibuat"));
    $_proses = $cekdt->row("customer");
    $wicode = $this->uri->segment(4);
    $ar = array(
      '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    );
    $printTgl = $ex[2]." ".$ar[$ex[1]]." ".$ex[0];
    $d1_koderoll = array();
    $d2_tglpotong = array();
    $d3_mc = array();
    $d4_beam = array();
    $d5_panjang = array();
    $d6_kons = array();
    $roll = $this->db->query("SELECT kd,konstruksi,kode,ukuran FROM new_tb_isi WHERE kd='$token'");
    if($roll->num_rows() > 0 ){
      $dari = 1;
      foreach($roll->result() as $val){
          $_kd = $val->kode;
          $d1_koderoll[] = $_kd;
          $d5_panjang[] = $val->ukuran;
          $d6_kons[] = $val->konstruksi;
          $detil = $this->db->query("SELECT kode_roll,no_mesin,no_beam,tanggal FROM data_ig WHERE kode_roll='$_kd'")->row_array();
          $d3_mc[] = $detil['no_mesin'];
          $d4_beam[] = $detil['no_beam'];
          $lx = explode('-', $detil['tanggal']);
          $d2_tglpotong[] = $lx[2]."/".$lx[1]."/".$lx[0];
      }
    } 
    $var2 = "";
    $kode_packinglist = $token;
    $tgl_packinglist = $printTgl;
    $total_packinglist = array_sum($d5_panjang);
    for ($i=0; $i < count($d1_koderoll); $i++) { 
        $var2 .=  $d1_koderoll[$i].",".$d2_tglpotong[$i].",".$d3_mc[$i].",".$d4_beam[$i].",".$d5_panjang[$i].",".$d6_kons[$i]."-en-";
    }
    //$this->db->query("DELETE FROM tb_pkg_sementara WHERE kodepkg = '$kode_packinglist'");
    //$this->data_model->saved('tb_pkg_sementara', ['kodepkg'=>$kode_packinglist,'longtks'=>$var2,'tgl_pkg'=>$tgl_packinglist,'jml_pkg'=>$total_packinglist]);
    //echo "Klik <a href='https://rdgjt.com/cetak/index.php?id=".$token."'>disini</a> untuk mencetak packinglist";
    //redirect(base_url('cetakrjs2/packinglist/'.$kode_packinglist.''));
    //echo $var2."-".$kode_packinglist."-".$tgl_packinglist."-".$total_packinglist;
    $jumlah = $roll->num_rows();
             $pdf = new FPDF('p','mm','A4');
              // membuat halaman baru
              $pdf->AddPage();
              // setting jenis font yang akan digunakan
              $pdf->SetTitle('Packing List - '.$token.'');
      
              $pdf->SetFont('Arial','B',14);
              $pdf->Image('https://weaving.rdgjt.com/assets/logo_rjs2.jpg', 13, 10, 25, 0, 'JPG');
              $pdf->Cell(0, 6, 'PT RINDANG JATI SPINNING', 0, 0, 'C' );
              $pdf->SetFont('Arial','',10);
              $pdf->Ln(6);
              $pdf->Cell(0, 6, 'Jl Raya Jatirejo RT 004 RW 001 Kel Jatirejo Kec Ampel Gading Pemalang', 0, 0, 'C' );
              $pdf->Ln(5);
              $pdf->Cell(0, 6, 'TELP (0285) 5750072 FAX (0285) 5750073', 0, 0, 'C' );
              $pdf->Line(13, 33, 210-15, 33);
              $pdf->Line(13, 33.1, 210-15, 33.1);
              $pdf->Line(13, 33.2, 210-15, 33.2);
              $pdf->Line(13, 33.8, 210-15, 33.8);
              $pdf->Ln(18);
              $pdf->Cell(120, 5, '', 0, 0, 'L' );
              $pdf->Cell(30, 5, 'Kode Packing', 0, 0, 'L' );
              $pdf->Cell(5, 5, ':', 0, 0, 'L' );
              $pdf->Cell(30, 5, ''.$token.'', 0, 0, 'L' );
              $pdf->Ln(5);
              $pdf->Cell(120, 5, '', 0, 0, 'L' );
              $pdf->Cell(30, 5, 'Tanggal Packing', 0, 0, 'L' );
              $pdf->Cell(5, 5, ':', 0, 0, 'L' );
              $pdf->Cell(30, 5, ''.$printTgl.'', 0, 0, 'L' );
              $pdf->Ln(13);
              $pdf->Cell(3, 10, '', 0, 0, 'L' );
              $pdf->Cell(10, 10, 'No.', 1, 0, 'C' );
              $pdf->Cell(25, 10, 'Kode Roll', 1, 0, 'C' );
              $pdf->Cell(30, 10, 'Tanggal Potong', 1, 0, 'C' );
              $pdf->Cell(40, 5, 'Nomor', 1, 0, 'C' );
              $pdf->Cell(30, 10, 'Panjang (Mtr)', 1, 0, 'C' );
              $pdf->Cell(30, 10, 'Konstruksi', 1, 0, 'C' );
              $pdf->Cell(17, 10, 'Proses', 1, 0, 'C' );
              $pdf->Ln(5);
              $pdf->Cell(3, 5, '', 0, 0, 'L' );
              $pdf->Cell(10, 5, '', 0, 0, 'C' );
              $pdf->Cell(25, 5, '', 0, 0, 'C' );
              $pdf->Cell(30, 5, '', 0, 0, 'C' );
              $pdf->Cell(20, 5, 'M/C', 1, 0, 'C' );
              $pdf->Cell(20, 5, 'Beam', 1, 0, 'C' );
              $total_panjang = array_sum($d5_panjang);
              for ($i=0; $i <count($d1_koderoll) ; $i++) { 
                  $ii = $i+1;
                  $pdf->Ln(5);
                  $pdf->Cell(3, 5, '', 0, 0, 'L' );
                  $pdf->Cell(10, 5, ''.$ii.'', 1, 0, 'C' );
                  $pdf->Cell(25, 5, ''.$d1_koderoll[$i].'', 1, 0, 'C' );
                  $pdf->Cell(30, 5, ''.$d2_tglpotong[$i].'', 1, 0, 'C' );
                  $pdf->Cell(20, 5, ''.$d3_mc[$i].'', 1, 0, 'C' );
                  $pdf->Cell(20, 5, ''.$d4_beam[$i].'', 1, 0, 'C' );
                  $pdf->Cell(30, 5, ''.$d5_panjang[$i].'', 1, 0, 'C' );
                  $pdf->Cell(30, 5, ''.$d6_kons[$i].'', 1, 0, 'C' );
                  $pdf->Cell(17, 5, ''.$_proses.'', 1, 0, 'C' );
                  //$total_panjang += floatval($dtroll['ukuran_ori']);
              }
              
              $total_panjang2 = round($total_panjang);
              if(fmod($total_panjang2, 1) !== 0.00){
                $printTotal = number_format($total_panjang2,2,',','.');
              } else {
                $printTotal = number_format($total_panjang2,0,',','.');
              }
              $pdf->SetFont('Arial','B',10);
              $pdf->Ln(5);
              $pdf->Cell(3, 5, '', 0, 0, 'L' );
              $pdf->Cell(105, 5, 'Total Panjang', 1, 0, 'C' );
              $pdf->Cell(30, 5, ''.$printTotal.'', 1, 0, 'C' );
              $pdf->Cell(30, 5, '', 1, 0, 'C' );
              $pdf->Cell(17, 5, '', 1, 0, 'C' );
              $pdf->Ln(7);
              $pdf->SetFont('Arial','',10);
              $pdf->Cell(3, 5, '', 0, 0, 'L' );
              $pdf->Cell(45, 5, 'Diperiksa Oleh', 0, 0, 'L' );
              $pdf->Cell(40, 5, 'Driver', 0, 0, 'L' );
              $pdf->Cell(40, 5, 'Security', 0, 0, 'L' );
              $pdf->Cell(40, 5, 'Mengetahui', 0, 0, 'L' );
              $pdf->Output('I','cetak-packinglist-'.$token.'.pdf'); 
    } else {
        echo "Token erorr..";
    }
  } //end
  
  
}
?>