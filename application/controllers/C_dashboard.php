<?php

// use phpDocumentor\Reflection\Types\Object_;

defined('BASEPATH') or exit('No direct script access allowed');

class C_dashboard extends CI_Controller{

  public function __construct(){
    parent::__construct();
    $this->load->model('M_dashboard');
    $this->file='Covid_Sumsel.csv'; 
  }


  public function index(){
    $data               = $this->M_dashboard->getData($this->file);
    $data['data']       = $data;
    $this->load->view('layout/header');
		$this->load->view('layout/navbar');
		$this->load->view('dashboard/main',$data);
		$this->load->view('layout/footer');
    $this->load->view('dashboard/main_js');
  }

  
  public function forecast(){
    
    if (isset($_POST['submit'])) {
      $iterasi      = $this->input->post('iterasi');
      $partikel     = $this->input->post('partikel');
      $w            = $this->input->post('w');
      $c1           = $this->input->post('c1');
      $c2           = $this->input->post('c2');
      
    }
    
    // JIKA TIDAK MENEKAN TOMBOL FORECAST MAKA AKAN KEMBALI KE HALAMAN AWAL
    else {
      redirect('','refresh');
    }

      $data       = $this->M_dashboard->getData($this->file);
      $d          = $this->get_datacovid($data);
      $max        = max($d);
      $min        = min($d);
      $posisi     = $this->M_dashboard->doo_pso($data, $max, $min, $partikel, $iterasi, $c1, $c2, $w);
      
      
      $fitness = [];
      for ($i=0; $i < count($posisi); $i++){ 
        $fitness[] = $posisi[$i][9];
      }


      sort($fitness);
      $best_fitness      = min($fitness);
      $temp1             = 0;
      $posisi_terbaik    = 0;
      $pos_chen_pso      = false;
      
      
      // CEK APAKAH NILAI GBEST TERPILIH MEMENUHI SYARAT (POSISI PADA NILAI GBEST HARUS MENCAKUP SELURUH DATA AKTUAL YANG ADA ) 
      while ($pos_chen_pso == false) {
        for ($i=0; $i < count($posisi); $i++) { 
          if ($fitness[$temp1] == $posisi[$i][9]) {
            if ($posisi[$i][0] <= $min && $posisi[$i][8] >= $max) { 
              $posisi_terbaik   = $i;
              $pos_chen_pso     = true;
              $best_fitness     = $posisi[$i][9];
            }
            
          }
        }
        $temp1++;
      }
      
      
      // INTERVAL TERBAIK DARI OPTIMASI
      $interval_chen_pso = [];
      for ($i=0; $i < 9; $i++) { 
        $interval_chen_pso[] = $posisi[$posisi_terbaik][$i];
      }
      

      // INTERVAL CHEN
      $interval_chen = [];
      for ($i=0; $i < 9; $i++) { 
        $interval_chen[] = $posisi[0][$i];
      }
      
      
      
      $chen_pso                   = $this->M_dashboard->do_forecast($data, $interval_chen_pso);
      $chen                       = $this->M_dashboard->do_forecast($data, $interval_chen);
      $data['data']               = $data;
      $data['prediksi_chen_pso']  = $chen_pso;
      $data['prediksi_chen']      = $chen;
      

      // MENCARI NILAI ERROR
      $data['mae_chen_pso']= $best_fitness;
      $data['mae_chen']    = $posisi[0][9];
      // $data['ree']         = $best_fitness;
      // END MENCARI NILAI ERROR
      
      // DATA UNTUK VIEW
      $this->load->view('layout/header');
		  $this->load->view('layout/navbar');
		  $this->load->view('dashboard/hasil',$data);
		  $this->load->view('layout/footer');
      $this->load->view('dashboard/Hasil_js');
    }

    // SPLIT DATA MEMSISAHKAN DATA DAN TANGGAL
    public function get_datacovid($data){
      $d = [];
      for ($i=0; $i < count($data); $i++) { 
        $d[] = $data[$i][1];
      }
      return $d;
    }
  }

