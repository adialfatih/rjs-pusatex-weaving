<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nota extends CI_Controller
{
  function __construct()
  {
      parent::__construct();
      $this->load->model('data_model');
      date_default_timezone_set("Asia/Jakarta");
      if($this->session->userdata('login_form') != "rindangjati_sess"){
        redirect(base_url('login'));
      }
  } 

  function index(){ 
        $data = array(
            'title' => 'Nota Penjualan - PPC Weaving',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id'),
            'sess_dep' => $this->session->userdata('departement'),
            //'dtnota' => $this->data_model->sort_record('id_nota','a_nota'),
	          'dtnota' => $this->db->query("SELECT * FROM a_nota ORDER BY id_nota DESC LIMIT 1000")
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('baru/nota_penjualan', $data);
        $this->load->view('part/main_js_dttable');
  } //end
   
  function konsumen(){ 
      $url = $this->uri->segment(3);
      $cek = $this->data_model->get_byid('dt_konsumen', ['sha1(id_konsumen)'=>$url]);
      if($cek->num_rows() == 1){
        $data = array(
            'title' => 'Nota Konsumen - PPC Weaving',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id'),
            'sess_dep' => $this->session->userdata('departement'),
            'customer' => $cek->row_array()
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('baru/nota_konsumen', $data);
        $this->load->view('part/main_js_dttable');
      } else {
          echo "Erorr Token";
      }
  } //end
   
  function piutang(){ 
      $url = $this->uri->segment(3);
      $cek = $this->data_model->get_byid('a_nota', ['sha1(id_nota)'=>$url]);
      if($cek->num_rows() == 1){
        $data = array(
            'title' => 'Nota Piutang Konsumen - PPC Weaving',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id'),
            'sess_dep' => $this->session->userdata('departement'),
            'nota' => $cek->row_array()
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('baru/nota_piutang_konsumen', $data);
        $this->load->view('part/main_js_dttable');
      } else {
          echo "Erorr Token";
      }
  } //end
  
   
  function pembayaran(){ 
      $url = $this->uri->segment(3);
      $cek = $this->data_model->get_byid('dt_konsumen', ['sha1(id_konsumen)'=>$url]);
      if($cek->num_rows() == 1){
        $idcus = $cek->row("id_konsumen");
        $allBayar = $this->data_model->get_byid('a_nota_bayar2', ['id_cus'=>$idcus]);
        $data = array(
            'title' => 'Nota Pembayaran Konsumen - PPC Weaving',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id'),
            'sess_dep' => $this->session->userdata('departement'),
            'customer' => $cek->row_array(),
            'allbayar' => $allBayar
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('baru/nota_bayar_konsumen', $data);
        $this->load->view('part/main_js_dttable3');
      } else {
          echo "Erorr Token";
      }
  } //end
   
  function piutangall(){ 
      $url = $this->uri->segment(3);
      $tipeuri = $this->uri->segment(4);
      $cek = $this->data_model->get_byid('dt_konsumen', ['sha1(id_konsumen)'=>$url]);
      if($cek->num_rows() == 1){
        $data = array(
            'title' => 'Nota Piutang Konsumen - PPC Weaving',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id'),
            'sess_dep' => $this->session->userdata('departement'),
            'tipeuri' => $tipeuri,
            'kons' => $cek->row_array()
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('baru/nota_piutang_konsumen_all', $data);
        $this->load->view('part/main_js_dttable');
      } else {
          echo "Erorr Token";
      }
  } //end

  function piutangshowall(){
      $url = $this->uri->segment(3);
      $cek = $this->data_model->get_byid('dt_konsumen', ['sha1(id_konsumen)'=>$url]);
      $hak_akses = $this->session->userdata('hak');
      if($hak_akses == "Manager"){  
        if($cek->num_rows() == 1){
          $data = array(
              'title' => 'Nota Piutang Konsumen - PPC Weaving',
              'sess_nama' => $this->session->userdata('nama'),
              'sess_id' => $this->session->userdata('id'),
              'sess_dep' => $this->session->userdata('departement'),
              'kons' => $cek->row_array(),
              'uriid' => $url
          );
          $this->load->view('part/main_head', $data);
          $this->load->view('part/left_sidebar2', $data);
          $this->load->view('baru/nota_piutang_konsumen_all', $data);
          $this->load->view('part/main_js_dttable');
        } else {
            echo "Erorr Token";
        }
      } else {
        $this->load->view('block');
      }
  }

  function piutangshowallsaved(){
      $url = $this->uri->segment(3);
      $cek = $this->data_model->get_byid('dt_konsumen', ['id_konsumen'=>$url]);
      $hak_akses = $this->session->userdata('hak');
      if($hak_akses == "Manager"){  
        if($cek->num_rows() == 1){
          $data = array(
              'title' => 'Nota Piutang Konsumen - PPC Weaving',
              'sess_nama' => $this->session->userdata('nama'),
              'sess_id' => $this->session->userdata('id'),
              'sess_dep' => $this->session->userdata('departement'),
              'kons' => $cek->row_array(),
              'uriid' => $url
          );
          $this->load->view('part/main_head', $data);
          $this->load->view('part/left_sidebar2', $data);
          $this->load->view('baru/nota_piutang_konsumen_all2', $data);
          $this->load->view('part/main_js_dttable');
        } else {
            echo "Erorr Token";
        }
      } else {
        $this->load->view('block');
      }
  }

  function baru(){ 
      $url = $this->uri->segment(3);
      $cek_url = $this->data_model->get_byid('surat_jalan', ['sha1(id_sj)'=>$url]);
      if($cek_url->num_rows() == 1){
        $data = array(
            'title' => 'Buat Nota Penjualan - PPC Weaving',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id'),
            'sess_dep' => $this->session->userdata('departement'),
            'dt_sj' => $cek_url->row_array(),
            'uris' => $url
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('baru/nota_penjualan_harga', $data);
        $this->load->view('part/main_js_dttable');
      } else {
        $data = array(
            'title' => 'Buat Nota Penjualan - PPC Weaving',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id'),
            'sess_dep' => $this->session->userdata('departement'),
            'dt_sj' => $this->data_model->get_byid('surat_jalan', ['tujuan_kirim'=>'cus','create_nota'=>'n'])
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('baru/nota_penjualan_baru', $data);
        $this->load->view('part/main_js_dttable');
      }
  } //end

  function prosessimpan(){
      $nosj = $this->input->post('nosj');
      $ppn = $this->input->post('ppn');
      $kdpkg = $this->input->post('kdpkg');
      $konstruksi = $this->input->post('konstruksi');
      $jmlroll = $this->input->post('jmlroll');
      $ttlpanjang = $this->input->post('ttlpanjang');
      $hargasatuan = $this->input->post('hargasatuan');
      $totalharga = $this->input->post('totalharga');
      $totalharga = $this->input->post('totalharga');
      $pembayaransb = $this->input->post('pembayaransb');
      $pembayaranecer = $this->input->post('pembayaranecer');
      $pembayarannotad = $this->input->post('pembayarannotad');
      $uris = $this->input->post('uris');
      $total_persen = intval($pembayaransb) + intval($pembayaranecer) + intval($pembayarannotad);
      if($total_persen != 100){
          $this->session->set_flashdata('gagal', 'Presentase harus 100%');
          redirect(base_url('nota/baru/'.$uris));
      } else {
          $con = count($kdpkg);
          // $_thisTotal = 0;
          // for($a=0; $a < $con; $a++){
          //     $_x = floatval($totalharga[$i]);
          //     $_thisTotal += $_x;
          // }
          // $ppn_thisTotal = (intval($ppn) / 100) * $_thisTotal;
          for ($i=0; $i < $con; $i++) { 
              $_thisTotalHarga = floatval($totalharga[$i]);
              $_thisTotalHarga2 = round($_thisTotalHarga,2);
              $_ppnIni = (intval($ppn) / 100) * $_thisTotalHarga;
              $_harga_plus_ppn = $_thisTotalHarga + $_ppnIni;
              $_harga_plus_ppn2 = round($_harga_plus_ppn,2);
              $dtlist = [
                'no_sj' => $nosj,
                'kd' => $kdpkg[$i],
                'konstruksi' => $konstruksi[$i],
                'jml_roll' => $jmlroll[$i],
                'total_panjang' => $ttlpanjang[$i],
                'harga_satuan' => $hargasatuan[$i],
                'total_harga' => $_harga_plus_ppn2,
                'tgl_nota' => date('Y-m-d'),
                'tmstmp' => date('Y-m-d H:i:s'),
                'pembuat_nota' => $this->session->userdata('id'),
                'total_harga_asli' => $_thisTotalHarga2,
                'pajak_ppn' => $ppn,
                'pres_sb' => $pembayaransb,
                'pres_ecer' => $pembayaranecer,
                'pres_notad' => $pembayarannotad
              ];
              $this->data_model->saved('a_nota', $dtlist);
          }
          $this->data_model->updatedata('no_sj',$nosj,'surat_jalan',['create_nota'=>'y']);
          $this->session->set_flashdata('announce', 'Berhasil membuat nota');
          redirect(base_url('nota'));
      }
  } //end

  function kredit_bayar(){
      $cus = $this->input->post('cus');
      $id = $this->data_model->filter($this->input->post('idnota'));
      $tgl = $this->data_model->filter($this->input->post('tglbayar'));
      $nominal = $this->data_model->clean($this->input->post('nominal'));
      $metode = $this->data_model->filter($this->input->post('metode'));
      $nobukti = $this->input->post('nobukti');
      //echo "$id<br>$tgl<br>$nominal<br>$metode<br>$nobukti";
      if($id!="" AND $tgl!="" AND $nominal!="" AND $metode!="" AND $nobukti!=""){
          $dtlist = [
            'id_nota' => $id,
            'id_cus' => $cus,
            'nominal_pemb' => $nominal,
            'tgl_pemb' => $tgl,
            'id_admin' => $this->session->userdata('id'),
            'metode_bayar' => $metode,
            'nomor_bukti' => $nobukti
          ];
          $this->data_model->saved('a_nota_bayar', $dtlist);
          $this->session->set_flashdata('announce', 'Berhasil menyimpan pembayaran');
          redirect(base_url('nota/konsumen/'.sha1($cus)));
      } else {
        $this->session->set_flashdata('gagal', 'Anda tidak mengisi data dengan benar');
        redirect(base_url('nota/konsumen/'.sha1($cus)));
      }
  } //end

  function newpembayaran(){
      $_kodeBayar = $this->data_model->acakKode(21);
      $jumlahTotalKurang = $this->data_model->filter($this->input->post('jumlahTotalKurang'));
      $totalid = $this->data_model->filter($this->input->post('totalid'));
      $ex = explode(',', $totalid);
      $exOwek = explode(',', $totalid);
      $id_end = end($ex);
      $allkurang = $this->data_model->filter($this->input->post('allkurang'));
      $ez = explode(',', $allkurang);
      $tglbayar = $this->data_model->filter($this->input->post('tglbayar'));
      $nominal = $this->data_model->clean($this->input->post('dibayarkonsumen'));
      $metodbayar = $this->data_model->clean($this->input->post('metodbayar'));
      $tjuanbayar = $this->data_model->clean($this->input->post('tjuanbayar'));
      $nomorbuk = $this->input->post('nomorbuk');
      $icus = $this->input->post('icus');
      $ket2 = 'tes';
      if($tjuanbayar == "ECER"){ $_keRek = "ECER"; } else { $_keRek = "SB"; }
      $dtlist_ex = [
        'id_nota' => $ex[0],
        'id_cus' => $icus,
        'nominal_pemb' => $nominal,
        'tgl_pemb' => $tglbayar,
        'id_admin' => $this->session->userdata('id'),
        'metode_bayar' => $metodbayar,
        'nomor_bukti' => $nomorbuk,
        'ket' => $ket2,
        'tujuan_rek' => $_keRek,
        'kode_bayar' => $_kodeBayar
      ];
      $this->data_model->saved('a_nota_bayar2', $dtlist_ex);
      $tes_owek = $this->data_model->get_byid('a_nota_bayar2', $dtlist_ex)->row("id_pemb2");

      if($nomorbuk!="" AND $metodbayar!="" AND $nominal!="" AND $tglbayar!="" AND $totalid!="" AND $jumlahTotalKurang!=""){
          // echo $jumlahTotalKurang."<br>";
          // echo $totalid."<br>";
          // echo $tglbayar."<br>";
          // echo $nominal."<br>";
          // echo $metodbayar."<br>";
          // echo $nomorbuk."<br>";
          // echo $allkurang."<br>";
          // echo count($ex)."<br>";
          // echo count($ez)."<br>";
          $kelebihan_bayar = floatval($nominal) - floatval($jumlahTotalKurang);
          if($kelebihan_bayar > 0){
              end($exOwek); // Memindahkan pointer internal array ke elemen terakhir
              $secondFromEnd = prev($exOwek);
              $dtlist = [
                'id_nota' => $secondFromEnd,
                'id_cus' => $icus,
                'nominal_pemb' => round($kelebihan_bayar),
                'tgl_pemb' => $tglbayar,
                'id_admin' => $this->session->userdata('id'),
                'metode_bayar' => $metodbayar,
                'nomor_bukti' => $nomorbuk,
                'tes_buktibayar' => $tes_owek,
                'ket' => $ket2,
                'tujuan_rek' => $_keRek,
                'kode_bayar' => $_kodeBayar
              ];
              $this->data_model->saved('a_nota_bayar', $dtlist);
          }

          for ($i=0; $i < count($ex); $i++) { 
            if($i==0){
              
            }
            if($ex[$i]!=""){
                $kekurangan = $ez[$i] - $nominal;
                //echo "Piutang (".$ez[$i].") dibayar (".$nominal.") sisa (".$sisa_uang.") kekurangan (".$kekurangan.")<br>";
                if($nominal > $ez[$i]){
                    $dtlist = [
                      'id_nota' => $ex[$i],
                      'id_cus' => $icus,
                      'nominal_pemb' => $ez[$i],
                      'tgl_pemb' => $tglbayar,
                      'id_admin' => $this->session->userdata('id'),
                      'metode_bayar' => $metodbayar,
                      'nomor_bukti' => $nomorbuk,
                      'tes_buktibayar' => $tes_owek,
                      'ket' => $ket2,
                      'tujuan_rek' => $_keRek,
                      'kode_bayar' => $_kodeBayar
                    ];
                    if($ez[$i]>0){ $this->data_model->saved('a_nota_bayar', $dtlist); }
                } else {
                    if($nominal <= $ez[$i]){
                        $sisa_uang = $nominal;
                        $dtlist = [
                          'id_nota' => $ex[$i],
                          'id_cus' => $icus,
                          'nominal_pemb' => $sisa_uang,
                          'tgl_pemb' => $tglbayar,
                          'id_admin' => $this->session->userdata('id'),
                          'metode_bayar' => $metodbayar,
                          'nomor_bukti' => $nomorbuk,
                          'tes_buktibayar' => $tes_owek,
                          'ket' => $ket2,
                          'tujuan_rek' => $_keRek,
                          'kode_bayar' => $_kodeBayar
                        ];
                        if($sisa_uang>0){ $this->data_model->saved('a_nota_bayar', $dtlist); }
                    } else {
                        
                    }
                }
            } //jika id nya tidak kosong
            
            $sisa_uang = $nominal - $ez[$i];
            $nominal = $sisa_uang;
            if($nominal < 0) {
                break;
            }
          } //end for

          
          $this->session->set_flashdata('announce', 'Berhasil');
          redirect(base_url('nota/konsumen/'.sha1($icus)));
      } else {
          $this->session->set_flashdata('gagal', 'Anda tidak mengisi data dengan benar');
          redirect(base_url('nota/konsumen/'.sha1($icus)));
      }
  } //end 

  function editharga(){
      $id = $this->input->post('idnotas');
      $hrg = $this->input->post('hargasatuan');
      $tglnt = $this->input->post('tglnt');
      $tghsb = $this->input->post('tghsb');
      $tghecer = $this->input->post('tghecer');
      $tghnotad = $this->input->post('tghnotad');
      $ppn = $this->input->post('ppn34');
      $total_persen = intval($tghsb) + intval($tghecer) + intval($tghnotad);
      if($total_persen == 100){
          $nota = $this->data_model->get_byid('a_nota', ['id_nota'=>$id])->row_array();
          $panjang = $nota['total_panjang'];
          $no_sj = $nota['no_sj'];
          if(floatval($ppn) > 0){
            $newharga_asli = floatval($panjang) * floatval($hrg);
            $newharga_asli2 = round($newharga_asli,2);
            //$_thisPPn = intval($ppn) / 100;
            $newharga_ppn = (intval($ppn) * $newharga_asli2) / 100;
            $newharga_ppn2 = round($newharga_ppn,2);
            $harga_plus_ppn = $newharga_ppn2 + $newharga_asli2;
            $harga_plus_ppn2 = round($harga_plus_ppn,2);
            $this->data_model->updatedata('id_nota',$id,'a_nota',['harga_satuan'=>$hrg,'total_harga'=>$harga_plus_ppn2,'tgl_nota'=>$tglnt,'total_harga_asli'=>$newharga_asli2]);
          } else {
            $newharga = floatval($panjang) * floatval($hrg);
            $this->data_model->updatedata('id_nota',$id,'a_nota',['harga_satuan'=>$hrg,'total_harga'=>round($newharga,2),'tgl_nota'=>$tglnt,'total_harga_asli'=>round($newharga,2)]);
          }
          $this->data_model->updatedata('id_nota',$id,'a_nota',['pajak_ppn'=>$ppn,'pres_sb'=>$tghsb,'pres_ecer'=>$tghecer,'pres_notad'=>$tghnotad]);
          $this->session->set_flashdata('success', 'Berhasil update nota');
          redirect(base_url('nota-penjualan'));
      } else {
          $this->session->set_flashdata('gagal', 'Anda tidak mengisi data dengan benar');
          redirect(base_url('nota-penjualan'));
      }
  }
  //end

  function delnotas(){
    $id = $this->input->post('sj');
    $this->data_model->delete('a_nota','no_sj',$id);
    $this->data_model->updatedata('no_sj',$id,'surat_jalan',['create_nota'=>'n']);
    redirect(base_url('nota-penjualan'));
  } //end

  function splitnotas(){
      $id = $this->input->post('idnota');
      $panjang = $this->input->post('panjang');
      $harga = $this->input->post('harga');
      $nota = $this->data_model->get_byid('a_nota', ['id_nota'=>$id])->row_array();
      $id_nota = $nota['id_nota'];
      $nosj = $nota['no_sj'];
      $kpkg = $nota['kd'];
      $kons = $nota['konstruksi'];
      $jmlr = $nota['jml_roll'];
      $tgln = $nota['tgl_nota'];
      $pmbt = $nota['pembuat_nota'];
      if(count($panjang) > 1){
          for ($i=0; $i <count($panjang) ; $i++) { 
              if($panjang[$i]!="" AND $harga[$i]!=""){
                  if($i==0){
                    $total_harga = floatval($harga[$i]) * floatval($panjang[$i]);
                    $dtlisr = [
                      'no_sj' => $nosj,
                      'kd' => $kpkg,
                      'konstruksi' => $kons,
                      'jml_roll' => $jmlr,
                      'total_panjang' => $panjang[$i],
                      'harga_satuan' => $harga[$i],
                      'total_harga' =>  $total_harga,
                      'tgl_nota' => $tgln,
                      'pembuat_nota' => $pmbt
                    ];
                    //$this->data_model->saved('a_nota', $dtlisr);
                    $this->data_model->updatedata('id_nota',$id_nota,'a_nota',['total_panjang'=>$panjang[$i], 'harga_satuan'=>round($harga[$i]), 'total_harga'=>round($total_harga)]);
                  } else {
                    $total_harga = floatval($harga[$i]) * floatval($panjang[$i]);
                    $dtlisr = [
                      'no_sj' => $nosj,
                      'kd' => $kpkg,
                      'konstruksi' => $kons,
                      'jml_roll' => '0',
                      'total_panjang' => $panjang[$i],
                      'harga_satuan' => $harga[$i],
                      'total_harga' =>  round($total_harga),
                      'tgl_nota' => $tgln,
                      'pembuat_nota' => $pmbt
                    ];
                    $this->data_model->saved('a_nota', $dtlisr);
                  }
              }
          }
      } else {
          echo "Anda harus split nota lebih dari 1";
      }

  } //

  function del_bayar(){
      $id_pemb = $this->input->post('id_pemb');
      $idcus = $this->input->post('idcus');
      $this->data_model->delete('a_nota_bayar2','kode_bayar',$id_pemb);
      $this->data_model->delete('a_nota_bayar','kode_bayar',$id_pemb);
      redirect(base_url('nota/pembayaran/'.sha1($idcus)));
  }

  


}