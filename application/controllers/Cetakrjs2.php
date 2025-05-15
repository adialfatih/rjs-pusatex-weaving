<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cetakrjs2 extends CI_Controller
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
  
  function packinglist(){
    $token = $this->uri->segment(3);
    //$cekdt = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$token]);
    $cekdt = $this->db->query("SELECT kode_konstruksi,kd,tanggal_dibuat,customer FROM new_tb_packinglist WHERE kd='$token'");
    if($cekdt->num_rows() == 1){
    $ex = explode('-', $cekdt->row("tanggal_dibuat"));
    $_proses = $cekdt->row("customer");
    $_kode_konstruksi = $cekdt->row("kode_konstruksi");
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
          //$d6_kons[] = $val->konstruksi;
          $jnsFold = $this->data_model->get_byid('data_fol',['kode_roll'=>$_kd])->row("jns_fold");
          if($jnsFold == "Grey"){ $d4_beam[] = "Meter"; } else { $d4_beam[]="Yard"; }
      }
    } 
    $var2 = "";
    $kode_packinglist = $token;
    $tgl_packinglist = $printTgl;
    $total_packinglist = array_sum($d5_panjang);
    for ($i=0; $i < count($d1_koderoll); $i++) { 
        $var2 .=  $d1_koderoll[$i].",".$d2_tglpotong[$i].",".$d3_mc[$i].",".$d4_beam[$i].",".$d5_panjang[$i].",".$d6_kons[$i]."-en-";
    }
    
    $jumlah = $roll->num_rows();
             $pdf = new FPDF('p','mm','A4');
              // membuat halaman baru
              $pdf->AddPage();
              // setting jenis font yang akan digunakan
              $pdf->SetTitle('Packing List - '.$token.'');
      
              $pdf->SetFont('Arial','B',14);
            //   $pdf->Image('https://weaving.rdgjt.com/assets/logo_rjs2.jpg', 13, 10, 25, 0, 'JPG');
               $pdf->Cell(0, 6, 'PACKING LIST ', 0, 0, 'L' );
               $pdf->SetFont('Arial','',10);
            //   $pdf->Ln(6);
            //   $pdf->Cell(0, 6, 'Jl Raya Jatirejo RT 004 RW 001 Kel Jatirejo Kec Ampel Gading Pemalang', 0, 0, 'C' );
            //   $pdf->Ln(5);
            //   $pdf->Cell(0, 6, 'TELP (0285) 5750072 FAX (0285) 5750073', 0, 0, 'C' );
            //   $pdf->Line(13, 33, 210-15, 33);
            //   $pdf->Line(13, 33.1, 210-15, 33.1);
            //   $pdf->Line(13, 33.2, 210-15, 33.2);
            //   $pdf->Line(13, 33.8, 210-15, 33.8);
              $pdf->Ln(5);
              $pdf->Cell(120, 5, '', 0, 0, 'L' );
              $pdf->Cell(30, 5, 'Kode Packing', 0, 0, 'L' );
              $pdf->Cell(5, 5, ':', 0, 0, 'L' );
              $pdf->Cell(30, 5, ''.$token.'', 0, 0, 'L' );
              $pdf->Ln(5);
              
              $pdf->Cell(120, 5, '', 0, 0, 'L' );
              $pdf->Cell(30, 5, 'Tanggal Packing', 0, 0, 'L' );
              $pdf->Cell(5, 5, ':', 0, 0, 'L' );
              $pdf->Cell(30, 5, ''.$printTgl.'', 0, 0, 'L' );
              $pdf->Ln(7);
              $pdf->Cell(120, 5, 'Konstruksi : '.$_kode_konstruksi.'', 0, 0, 'L' );
              $pdf->Ln(5);
              $pdf->SetFillColor(64, 64, 64); // Warna hitam untuk background
              $pdf->SetTextColor(255, 255, 255);
              
              
              $pdf->Cell(1, 10, '', 0, 0, 'L');
              $pdf->Cell(9, 10, 'No.', 1, 0, 'C', true);
              $pdf->Cell(20, 10, 'Kode Roll', 1, 0, 'C', true);
              $pdf->Cell(20, 10, 'Panjang', 1, 0, 'C', true);
              $pdf->Cell(13, 10, 'Satuan', 1, 0, 'C', true);
              $pdf->Cell(9, 10, 'No.', 1, 0, 'C', true);
              $pdf->Cell(20, 10, 'Kode Roll', 1, 0, 'C', true);
              $pdf->Cell(20, 10, 'Panjang', 1, 0, 'C', true);
              $pdf->Cell(13, 10, 'Satuan', 1, 0, 'C', true);
              $pdf->Cell(9, 10, 'No.', 1, 0, 'C', true);
              $pdf->Cell(20, 10, 'Kode Roll', 1, 0, 'C', true);
              $pdf->Cell(20, 10, 'Panjang', 1, 0, 'C', true);
              $pdf->Cell(13, 10, 'Satuan', 1, 0, 'C', true);
              $pdf->SetTextColor(0, 0, 0);
              $pdf->Ln(10);
              $n1=36;
              $n1i=35;
              $n2=71;
              $n2i=70;
              $pdf->SetFillColor(64, 64, 64); // Warna hitam untuk background
              $pdf->SetTextColor(255, 255, 255);
              $pdf->SetTextColor(0, 0, 0);
              $total_kolom1=0;
              $total_kolom2=0;
              $total_kolom3=0;
              for ($i=0; $i < 35; $i++) {
                $num = $i+1;
                $pdf->Cell(1, 5, '', 0, 0, 'L' );
                //------------ Kolom 1--------------//
                $pdf->SetTextColor(255, 255, 255);
                $pdf->Cell(9, 5, $num, 1, 0, 'C', true);
                $pdf->SetTextColor(0, 0, 0);
                if($d1_koderoll[$i]==""){
                    $pdf->Cell(20, 5, '', 1, 0, 'C' );
                    $pdf->Cell(20, 5, '', 1, 0, 'C' );
                    $pdf->Cell(13, 5, '', 1, 0, 'C' );
                } else {
                    $pdf->Cell(20, 5, $d1_koderoll[$i], 1, 0, 'C' );
                    $total_kolom1+=$d5_panjang[$i];
                    if($d5_panjang[$i] == floor($d5_panjang[$i])){
                      $pdf->Cell(20, 5, number_format($d5_panjang[$i],0,',','.'), 1, 0, 'C' );
                    } else {
                      $pdf->Cell(20, 5, number_format($d5_panjang[$i],2,',','.'), 1, 0, 'C' );
                    }
                    $pdf->Cell(13, 5, $d4_beam[$i], 1, 0, 'C' );
                }
                //------------ Kolom 2--------------//
                
                $pdf->SetTextColor(255, 255, 255);
                $pdf->Cell(9, 5, $n1, 1, 0, 'C', true);
                $pdf->SetTextColor(0, 0, 0);
                if($d1_koderoll[$n1i]==""){
                    $pdf->Cell(20, 5, '', 1, 0, 'C' );
                    $pdf->Cell(20, 5, '', 1, 0, 'C' );
                    $pdf->Cell(13, 5, '', 1, 0, 'C' );
                } else {
                    $pdf->Cell(20, 5, $d1_koderoll[$n1i], 1, 0, 'C' );
                    $total_kolom2+=$d5_panjang[$n1i];
                    if($d5_panjang[$n1i] == floor($d5_panjang[$n1i])){
                      $pdf->Cell(20, 5, number_format($d5_panjang[$n1i],0,',','.'), 1, 0, 'C' );
                    } else {
                      $pdf->Cell(20, 5, number_format($d5_panjang[$n1i],2,',','.'), 1, 0, 'C' );
                    }
                    $pdf->Cell(13, 5, $d4_beam[$n1i], 1, 0, 'C' );
                }
                //------------ Kolom 3--------------//
                
                $pdf->SetTextColor(255, 255, 255);
                $pdf->Cell(9, 5, $n2, 1, 0, 'C', true);
                $pdf->SetTextColor(0, 0, 0);
                if($d1_koderoll[$n2i]==""){
                   $pdf->Cell(20, 5, '', 1, 0, 'C' );
                   $pdf->Cell(20, 5, '', 1, 0, 'C' );
                   $pdf->Cell(13, 5, '', 1, 0, 'C' );
                } else {
                    $pdf->Cell(20, 5, $d1_koderoll[$n2i], 1, 0, 'C' );
                    $total_kolom3+=$d5_panjang[$n2i];
                    if($d5_panjang[$n2i] == floor($d5_panjang[$n2i])){
                      $pdf->Cell(20, 5, number_format($d5_panjang[$n2i],0,',','.'), 1, 0, 'C' );
                    } else {
                      $pdf->Cell(20, 5, number_format($d5_panjang[$n2i],2,',','.'), 1, 0, 'C' );
                    }
                    $pdf->Cell(13, 5, $d4_beam[$n2i], 1, 0, 'C' );
                }
                $pdf->Ln(5);
                $n1++; $n1i++;
                $n2++; $n2i++;
              }
              $total_panjang = $total_kolom1 + $total_kolom2 + $total_kolom3;
              $total_panjang = round($total_panjang,1);
              $total_kolom1 = round($total_kolom1,1);
              $total_kolom2 = round($total_kolom2,1);
              $total_kolom3 = round($total_kolom3,1);
              //------------ Kolom total sub--------------//
              $pdf->SetTextColor(255, 255, 255);
              $pdf->Cell(1, 5, '', 0, 0, 'L' );
              $pdf->Cell(29, 5, 'Sub Total', 1, 0, 'C', true);
              if($total_kolom1 == floor($total_kolom1)){
                $pdf->Cell(20, 5, number_format($total_kolom1,0,',','.'), 1, 0, 'C', true );
              } else {
                $pdf->Cell(20, 5, number_format($total_kolom1,2,',','.'), 1, 0, 'C', true );
              }
              $pdf->Cell(13, 5, $d4_beam[0], 1, 0, 'C', true);
              //---------------
              $pdf->Cell(29, 5, 'Sub Total', 1, 0, 'C', true);
              if($total_kolom2 == floor($total_kolom2)){
                $pdf->Cell(20, 5, number_format($total_kolom2,0,',','.'), 1, 0, 'C', true );
              } else {
                $pdf->Cell(20, 5, number_format($total_kolom2,2,',','.'), 1, 0, 'C', true );
              }
              $pdf->Cell(13, 5, $d4_beam[0], 1, 0, 'C', true);
              //---------------
              $pdf->Cell(29, 5, 'Sub Total', 1, 0, 'C', true);
              if($total_kolom3 == floor($total_kolom3)){
                $pdf->Cell(20, 5, number_format($total_kolom3,0,',','.'), 1, 0, 'C', true );
              } else {
                $pdf->Cell(20, 5, number_format($total_kolom3,2,',','.'), 1, 0, 'C', true );
              }
              $pdf->Cell(13, 5, $d4_beam[0], 1, 0, 'C', true);
              if(count($d1_koderoll)<105){
              $pdf->Ln(5);
              $pdf->Cell(1, 5, '', 0, 0, 'L' );
              $pdf->Cell(29, 5, 'Total', 1, 0, 'C', true);
              if($total_panjang == floor($total_panjang)){
                $pdf->Cell(62, 5, number_format($total_panjang,0,',','.'), 1, 0, 'C', true );
              } else {
                $pdf->Cell(62, 5, number_format($total_panjang,2,',','.'), 1, 0, 'C', true );
              }
              }
              //$pdf->Cell(62, 5, 'Total', 1, 0, 'C', true);
              $pdf->Ln(10);
              $pdf->SetFont('Arial','',10);
              $pdf->SetTextColor(0, 0, 0);
              $pdf->Cell(3, 5, '', 0, 0, 'L' );
              $pdf->Cell(45, 5, 'Diperiksa Oleh', 0, 0, 'L' );
              $pdf->Cell(40, 5, 'Driver', 0, 0, 'L' );
              $pdf->Cell(40, 5, 'Security', 0, 0, 'L' );
              $pdf->Cell(40, 5, 'Mengetahui', 0, 0, 'L' );
              
              //---galaman ke 2
              if(count($d1_koderoll)>105){
                $pdf->AddPage();
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
              $pdf->Ln(7);
              $pdf->Cell(120, 5, 'Konstruksi : '.$_kode_konstruksi.'', 0, 0, 'L' );
              $pdf->Ln(5);
              $pdf->SetFillColor(214, 214, 214); // Warna hitam untuk background
              $pdf->SetTextColor(28, 27, 27);
              
              $pdf->Cell(1, 10, '', 0, 0, 'L');
              $pdf->Cell(9, 10, 'No.', 1, 0, 'C', true);
              $pdf->Cell(20, 10, 'Kode Roll', 1, 0, 'C', true);
              $pdf->Cell(20, 10, 'Panjang', 1, 0, 'C', true);
              $pdf->Cell(13, 10, 'Satuan', 1, 0, 'C', true);
              $pdf->Cell(9, 10, 'No.', 1, 0, 'C', true);
              $pdf->Cell(20, 10, 'Kode Roll', 1, 0, 'C', true);
              $pdf->Cell(20, 10, 'Panjang', 1, 0, 'C', true);
              $pdf->Cell(13, 10, 'Satuan', 1, 0, 'C', true);
              $pdf->Cell(9, 10, 'No.', 1, 0, 'C', true);
              $pdf->Cell(20, 10, 'Kode Roll', 1, 0, 'C', true);
              $pdf->Cell(20, 10, 'Panjang', 1, 0, 'C', true);
              $pdf->Cell(13, 10, 'Satuan', 1, 0, 'C', true);
              $pdf->SetTextColor(0, 0, 0);
              $pdf->Ln(10);
              $n1=141;
              $n1i=140;
              $n2=176;
              $n2i=175;
              $pdf->SetFillColor(214, 214, 214); // Warna hitam untuk background
              $pdf->SetTextColor(28, 27, 27);
              $total_kolom1=0;
              $total_kolom2=0;
              $total_kolom3=0;
              for ($i=105; $i < 140; $i++) {
                $num = $i+1;
                $pdf->Cell(1, 5, '', 0, 0, 'L' );
                //------------ Kolom 1--------------//
                $pdf->Cell(9, 5, $num, 1, 0, 'C', true);
                if($d1_koderoll[$i]==""){
                    $pdf->Cell(20, 5, '', 1, 0, 'C' );
                    $pdf->Cell(20, 5, '', 1, 0, 'C' );
                    $pdf->Cell(13, 5, '', 1, 0, 'C' );
                } else {
                    $pdf->Cell(20, 5, $d1_koderoll[$i], 1, 0, 'C' );
                    $total_kolom1+=$d5_panjang[$i];
                    if($d5_panjang[$i] == floor($d5_panjang[$i])){
                      $pdf->Cell(20, 5, number_format($d5_panjang[$i],0,',','.'), 1, 0, 'C' );
                    } else {
                      $pdf->Cell(20, 5, number_format($d5_panjang[$i],2,',','.'), 1, 0, 'C' );
                    }
                    $pdf->Cell(13, 5, $d4_beam[$i], 1, 0, 'C' );
                }
                //------------ Kolom 2--------------//
                $pdf->Cell(9, 5, $n1, 1, 0, 'C', true);
                if($d1_koderoll[$n1i]==""){
                    $pdf->Cell(20, 5, '', 1, 0, 'C' );
                    $pdf->Cell(20, 5, '', 1, 0, 'C' );
                    $pdf->Cell(13, 5, '', 1, 0, 'C' );
                } else {
                    $pdf->Cell(20, 5, $d1_koderoll[$n1i], 1, 0, 'C' );
                    $total_kolom2+=$d5_panjang[$n1i];
                    if($d5_panjang[$n1i] == floor($d5_panjang[$n1i])){
                      $pdf->Cell(20, 5, number_format($d5_panjang[$n1i],0,',','.'), 1, 0, 'C' );
                    } else {
                      $pdf->Cell(20, 5, number_format($d5_panjang[$n1i],2,',','.'), 1, 0, 'C' );
                    }
                    $pdf->Cell(13, 5, $d4_beam[$n1i], 1, 0, 'C' );
                }
                //------------ Kolom 3--------------//
                $pdf->Cell(9, 5, $n2, 1, 0, 'C', true);
                if($d1_koderoll[$n2i]==""){
                   $pdf->Cell(20, 5, '', 1, 0, 'C' );
                   $pdf->Cell(20, 5, '', 1, 0, 'C' );
                   $pdf->Cell(13, 5, '', 1, 0, 'C' );
                } else {
                    $pdf->Cell(20, 5, $d1_koderoll[$n2i], 1, 0, 'C' );
                    $total_kolom3+=$d5_panjang[$n2i];
                    if($d5_panjang[$n2i] == floor($d5_panjang[$n2i])){
                      $pdf->Cell(20, 5, number_format($d5_panjang[$n2i],0,',','.'), 1, 0, 'C' );
                    } else {
                      $pdf->Cell(20, 5, number_format($d5_panjang[$n2i],2,',','.'), 1, 0, 'C' );
                    }
                    $pdf->Cell(13, 5, $d4_beam[$n2i], 1, 0, 'C' );
                }
                $pdf->Ln(5);
                $n1++; $n1i++;
                $n2++; $n2i++;
              }
              $total_panjang2 = $total_kolom1 + $total_kolom2 + $total_kolom3;
              $total_panjang2 = round($total_panjang2,1);
              $total_panjang3 = $total_panjang2 + $total_panjang;
              $total_kolom1 = round($total_kolom1,1);
              $total_kolom2 = round($total_kolom2,1);
              $total_kolom3 = round($total_kolom3,1);
              //------------ Kolom total sub--------------//
              $pdf->Cell(1, 5, '', 0, 0, 'L' );
              $pdf->Cell(29, 5, 'Sub Total', 1, 0, 'C', true);
              if($total_kolom1 == floor($total_kolom1)){
                $pdf->Cell(20, 5, number_format($total_kolom1,0,',','.'), 1, 0, 'C', true );
              } else {
                $pdf->Cell(20, 5, number_format($total_kolom1,2,',','.'), 1, 0, 'C', true );
              }
              $pdf->Cell(13, 5, $d4_beam[0], 1, 0, 'C', true);
              //---------------
              $pdf->Cell(29, 5, 'Sub Total', 1, 0, 'C', true);
              if($total_kolom2 == floor($total_kolom2)){
                $pdf->Cell(20, 5, number_format($total_kolom2,0,',','.'), 1, 0, 'C', true );
              } else {
                $pdf->Cell(20, 5, number_format($total_kolom2,2,',','.'), 1, 0, 'C', true );
              }
              $pdf->Cell(13, 5, $d4_beam[0], 1, 0, 'C', true);
              //---------------
              $pdf->Cell(29, 5, 'Sub Total', 1, 0, 'C', true);
              if($total_kolom3 == floor($total_kolom3)){
                $pdf->Cell(20, 5, number_format($total_kolom3,0,',','.'), 1, 0, 'C', true );
              } else {
                $pdf->Cell(20, 5, number_format($total_kolom3,2,',','.'), 1, 0, 'C', true );
              }
              $pdf->Cell(13, 5, $d4_beam[0], 1, 0, 'C', true);
              $pdf->Ln(5);
              $pdf->Cell(1, 5, '', 0, 0, 'L' );
              if(count($d1_koderoll)<211){
              $pdf->Cell(29, 5, 'Total', 1, 0, 'C', true);
              if($total_panjang3 == floor($total_panjang3)){
                $pdf->Cell(62, 5, number_format($total_panjang3,0,',','.'), 1, 0, 'C', true );
              } else {
                $pdf->Cell(62, 5, number_format($total_panjang3,2,',','.'), 1, 0, 'C', true );
              }
              //$pdf->Cell(62, 5, 'Total', 1, 0, 'C', true);
              }
              $pdf->Ln(10);
              $pdf->SetFont('Arial','',10);
              $pdf->Cell(3, 5, '', 0, 0, 'L' );
              $pdf->Cell(45, 5, 'Diperiksa Oleh', 0, 0, 'L' );
              $pdf->Cell(40, 5, 'Driver', 0, 0, 'L' );
              $pdf->Cell(40, 5, 'Security', 0, 0, 'L' );
              $pdf->Cell(40, 5, 'Mengetahui', 0, 0, 'L' );
              }
              if(count($d1_koderoll)>210){
                $pdf->AddPage();
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
              $pdf->Ln(7);
              $pdf->Cell(120, 5, 'Konstruksi : '.$_kode_konstruksi.'', 0, 0, 'L' );
              $pdf->Ln(5);
              $pdf->SetFillColor(214, 214, 214); // Warna hitam untuk background
              $pdf->SetTextColor(28, 27, 27);
              
              $pdf->Cell(1, 10, '', 0, 0, 'L');
              $pdf->Cell(9, 10, 'No.', 1, 0, 'C', true);
              $pdf->Cell(20, 10, 'Kode Roll', 1, 0, 'C', true);
              $pdf->Cell(20, 10, 'Panjang', 1, 0, 'C', true);
              $pdf->Cell(13, 10, 'Satuan', 1, 0, 'C', true);
              $pdf->Cell(9, 10, 'No.', 1, 0, 'C', true);
              $pdf->Cell(20, 10, 'Kode Roll', 1, 0, 'C', true);
              $pdf->Cell(20, 10, 'Panjang', 1, 0, 'C', true);
              $pdf->Cell(13, 10, 'Satuan', 1, 0, 'C', true);
              $pdf->Cell(9, 10, 'No.', 1, 0, 'C', true);
              $pdf->Cell(20, 10, 'Kode Roll', 1, 0, 'C', true);
              $pdf->Cell(20, 10, 'Panjang', 1, 0, 'C', true);
              $pdf->Cell(13, 10, 'Satuan', 1, 0, 'C', true);
              $pdf->SetTextColor(0, 0, 0);
              $pdf->Ln(10);
              $_n1=246;
              $_n1i=245;
              $_n2=281;
              $_n2i=280;
              $pdf->SetFillColor(214, 214, 214); // Warna hitam untuk background
              $pdf->SetTextColor(28, 27, 27);
              $_total_kolom1=0;
              $_total_kolom2=0;
              $_total_kolom3=0;
              for ($i=210; $i < 245; $i++) {
                $num = $i+1;
                $pdf->Cell(1, 5, '', 0, 0, 'L' );
                //------------ Kolom 1--------------//
                $pdf->Cell(9, 5, $num, 1, 0, 'C', true);
                if($d1_koderoll[$i]==""){
                    $pdf->Cell(20, 5, '', 1, 0, 'C' );
                    $pdf->Cell(20, 5, '', 1, 0, 'C' );
                    $pdf->Cell(13, 5, '', 1, 0, 'C' );
                } else {
                    $pdf->Cell(20, 5, $d1_koderoll[$i], 1, 0, 'C' );
                    $_total_kolom1+=$d5_panjang[$i];
                    if($d5_panjang[$i] == floor($d5_panjang[$i])){
                      $pdf->Cell(20, 5, number_format($d5_panjang[$i],0,',','.'), 1, 0, 'C' );
                    } else {
                      $pdf->Cell(20, 5, number_format($d5_panjang[$i],2,',','.'), 1, 0, 'C' );
                    }
                    $pdf->Cell(13, 5, $d4_beam[$i], 1, 0, 'C' );
                }
                //------------ Kolom 2--------------//
                $pdf->Cell(9, 5, $_n1, 1, 0, 'C', true);
                if($d1_koderoll[$_n1i]==""){
                    $pdf->Cell(20, 5, '', 1, 0, 'C' );
                    $pdf->Cell(20, 5, '', 1, 0, 'C' );
                    $pdf->Cell(13, 5, '', 1, 0, 'C' );
                } else {
                    $pdf->Cell(20, 5, $d1_koderoll[$_n1i].'', 1, 0, 'C' );
                    $_total_kolom2+=$d5_panjang[$_n1i];
                    if($d5_panjang[$_n1i] == floor($d5_panjang[$_n1i])){
                      $pdf->Cell(20, 5, number_format($d5_panjang[$_n1i],0,',','.'), 1, 0, 'C' );
                    } else {
                      $pdf->Cell(20, 5, number_format($d5_panjang[$_n1i],2,',','.'), 1, 0, 'C' );
                    }
                    $pdf->Cell(13, 5, $d4_beam[$_n1i], 1, 0, 'C' );
                }
                //------------ Kolom 3--------------//
                $pdf->Cell(9, 5, $_n2, 1, 0, 'C', true);
                if($d1_koderoll[$_n2i]==""){
                   $pdf->Cell(20, 5, '', 1, 0, 'C' );
                   $pdf->Cell(20, 5, '', 1, 0, 'C' );
                   $pdf->Cell(13, 5, '', 1, 0, 'C' );
                } else {
                    $pdf->Cell(20, 5, $d1_koderoll[$_n2i], 1, 0, 'C' );
                    $_total_kolom3+=$d5_panjang[$_n2i];
                    if($d5_panjang[$_n2i] == floor($d5_panjang[$_n2i])){
                      $pdf->Cell(20, 5, number_format($d5_panjang[$_n2i],0,',','.'), 1, 0, 'C' );
                    } else {
                      $pdf->Cell(20, 5, number_format($d5_panjang[$_n2i],2,',','.'), 1, 0, 'C' );
                    }
                    $pdf->Cell(13, 5, $d4_beam[$_n2i], 1, 0, 'C' );
                }
                $pdf->Ln(5);
                $_n1++; $_n1i++;
                $_n2++; $_n2i++;
              }
              $_total_panjang2 = $_total_kolom1 + $_total_kolom2 + $_total_kolom3;
              $_total_panjang2 = round($_total_panjang2,1);
              $_total_panjang3 = $_total_panjang2 + $total_panjang3;
              $_total_kolom1 = round($_total_kolom1,1);
              $_total_kolom2 = round($_total_kolom2,1);
              $_total_kolom3 = round($_total_kolom3,1);
              //------------ Kolom total sub--------------//
              $pdf->Cell(1, 5, '', 0, 0, 'L' );
              $pdf->Cell(29, 5, 'Sub Total', 1, 0, 'C', true);
              if($_total_kolom1 == floor($_total_kolom1)){
                $pdf->Cell(20, 5, number_format($_total_kolom1,0,',','.'), 1, 0, 'C', true );
              } else {
                $pdf->Cell(20, 5, number_format($_total_kolom1,2,',','.'), 1, 0, 'C', true );
              }
              $pdf->Cell(13, 5, $d4_beam[0], 1, 0, 'C', true);
              //---------------
              $pdf->Cell(29, 5, 'Sub Total', 1, 0, 'C', true);
              if($_total_kolom2 == floor($_total_kolom2)){
                $pdf->Cell(20, 5, number_format($_total_kolom2,0,',','.'), 1, 0, 'C', true );
              } else {
                $pdf->Cell(20, 5, number_format($_total_kolom2,2,',','.'), 1, 0, 'C', true );
              }
              $pdf->Cell(13, 5, $d4_beam[0], 1, 0, 'C', true);
              //---------------
              $pdf->Cell(29, 5, 'Sub Total', 1, 0, 'C', true);
              if($_total_kolom3 == floor($_total_kolom3)){
                $pdf->Cell(20, 5, number_format($_total_kolom3,0,',','.'), 1, 0, 'C', true );
              } else {
                $pdf->Cell(20, 5, number_format($_total_kolom3,2,',','.'), 1, 0, 'C', true );
              }
              $pdf->Cell(13, 5, $d4_beam[0], 1, 0, 'C', true);
              $pdf->Ln(5);
              $pdf->Cell(1, 5, '', 0, 0, 'L' );
              $pdf->Cell(29, 5, 'Total', 1, 0, 'C', true);
              if($_total_panjang3 == floor($_total_panjang3)){
                $pdf->Cell(62, 5, number_format($_total_panjang3,0,',','.'), 1, 0, 'C', true );
              } else {
                $pdf->Cell(62, 5, number_format($_total_panjang3,2,',','.'), 1, 0, 'C', true );
              }
              //$pdf->Cell(62, 5, 'Total', 1, 0, 'C', true);
              
              $pdf->Ln(10);
              $pdf->SetFont('Arial','',10);
              $pdf->Cell(3, 5, '', 0, 0, 'L' );
              $pdf->Cell(45, 5, 'Diperiksa Oleh', 0, 0, 'L' );
              $pdf->Cell(40, 5, 'Driver', 0, 0, 'L' );
              $pdf->Cell(40, 5, 'Security', 0, 0, 'L' );
              $pdf->Cell(40, 5, 'Mengetahui', 0, 0, 'L' );
              }
              $pdf->Output('I','cetak-packinglist-'.$token.'.pdf'); 
    } else {
        echo "Token erorr..";
    }
  } //end
  
  
}
?>