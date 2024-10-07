<?php


include 'pso.php';

class M_dashboard extends CI_Model{
  
  // OPEN CSV FILE, DAN AMBIL SEMUA DATA
  public function getData($filename){
		$data    = [];
		$file    = fopen(base_url()."assets/$filename","r");
		$count   = 0;

		while(! feof($file)){
			$data[$count] = fgetcsv($file);
			$count++;
		}
		fclose($file);
		return $data;
  }
  
  // MENJALANKAN PROSSES PSO
  public function doo_pso($data, $max, $min, $partikel, $iterasi, $c1, $c2, $w){
    $doo_pso = new pso($data, $max, $min, $partikel, $iterasi, $c1, $c2, $w);
    return $doo_pso->next;
  }

  // MELAKUKAN PREDIKSI MENGGUNAKAN FUZZY TIME SERIES CHEN
  public function do_forecast($data, $posisi){
    $jum_interval   = round(1+(3.3*log(count($data),10)));
    $interval       = [];
    
    // INTERVAL
    for ($i=0; $i < $jum_interval; $i++) { 
      for ($j=0; $j < 2; $j++) { 
       if ($i == 0 && $j==0) {
          $interval[$i][$j] = $posisi[$i];
        }
        elseif ($j==0) {
          $interval[$i][$j] = $posisi[$i];
        }
        else {
          $interval[$i][$j] = $posisi[$i+1];
        } 
      }
    }

    // MENENTUKAN MIDPOINT INTERVAL
    $mid = [];
    for ($i=0; $i < count($interval); $i++) { 
      $temp = 0;
      for ($j=0; $j < count($interval[0]); $j++) { 
        $temp+= $interval[$i][$j];
      }
      $mid[] = $temp/2;
    }

    // MELAKUKAN FUZZYFIKASI
    $fuzzy = [];
    for ($i=0; $i < count($data); $i++) { 
       if ($data[$i][1] >= $interval[0][0] && $data[$i][1] <= $interval[0][1]) {
         $fuzzy[] = "A1";
       }
       elseif ($data[$i][1] > $interval[1][0] && $data[$i][1] <= $interval[1][1]) {
        $fuzzy[] = "A2"; 
       }
       elseif ($data[$i][1] > $interval[2][0] && $data[$i][1] <= $interval[2][1]) {
        $fuzzy[] = "A3";
       }
       elseif ($data[$i][1] > $interval[3][0] && $data[$i][1] <= $interval[3][1]) {
        $fuzzy[] = "A4";
       }
       elseif ($data[$i][1] > $interval[4][0] && $data[$i][1] <= $interval[4][1]) {
        $fuzzy[] = "A5";
       }
       elseif ($data[$i][1] > $interval[5][0] && $data[$i][1] <= $interval[5][1]) {
        $fuzzy[] = "A6";
       }
       elseif ($data[$i][1] > $interval[6][0] && $data[$i][1] <= $interval[6][1]) {
        $fuzzy[] = "A7";
       }
       elseif ($data[$i][1] > $interval[7][0] && $data[$i][1] <= $interval[7][1]) {
        $fuzzy[] = "A8";
      }
    }

    
    // MENENTUKAN FUZZY RELATIONSHIP
    $flrg = [];
    for ($i=0; $i < count($interval); $i++) { 
    $temp1    = 0;
    $skip     = $i+1;
    $temp2    = "A".$skip;
    $group    = []; 
    
    for ($j=0; $j < count($fuzzy)-1; $j++) {
      if (count($flrg)==0 && $fuzzy[$j] == $temp2) {
        $flrg [$i][$temp1] = $fuzzy[$j+1];
        $temp1++;
        $group[] = $fuzzy[$j+1];
      }
      elseif (count($group)==0 && $fuzzy[$j] == $temp2) {
        $flrg [$i][$temp1] = $fuzzy[$j+1];
        $temp1++;
        $group[] = $fuzzy[$j+1];
      }
      elseif ($fuzzy[$j] == $temp2 && !in_array($fuzzy[$j+1], $group)) {
        $flrg [$i][$temp1] = $fuzzy[$j+1];
        $temp1++;
        $group[] = $fuzzy[$j+1];
      }
      elseif ($j == count($fuzzy)-2 && count($group)==0 ) {
        $flrg [$i][$temp1] = "A-";
      }         
    }
  }
    
    
    // DEFUZZIFIKASI
  $defuzzy  = [];
  for ($i=0; $i < count($flrg); $i++) {
    $temp = 0;
    for ($j=0; $j < count($flrg[$i]); $j++) { 
      if (count($flrg[$i])>1) {
        $temp += $mid[trim($flrg[$i][$j],"A")-1];  
      }
      
      //Jika dalam defuzzifikasi tidak memiliki anggota
      elseif (count($flrg[$i])==1) {
       if ($flrg[$i][$j] == "A-") {
          $temp += $mid[$i];
        }
        else {
          $temp += $mid[trim($flrg[$i][$j],"A")-1];   
        } 
      }
    }
    
    $temp = $temp/count($flrg[$i]);
    $defuzzy[] = $temp;
  }
  
  for ($i=0; $i < count($defuzzy); $i++) {
    $defuzzy[$i] = round($defuzzy[$i]);
  }
   
   // MELAKUKAN FORECAST
  $test       = [];
  $er         = 0;
  $forecast   = $fuzzy;

  for ($i=1; $i < count($forecast); $i++) { 
    if ($i == 1) {
      if ($forecast[$i-1] == "A1") {
        $test[]       = $forecast[$i];
        $forecast[$i] = $defuzzy[0];
        $er++;
      }
      elseif ($forecast[$i-1] == "A2") {
        $test[]       = $forecast[$i];
        $forecast[$i] = $defuzzy[1];
        $er++; 
      }
      elseif ($forecast[$i-1] == "A3") {
        $test[]       = $forecast[$i];
        $forecast[$i] = $defuzzy[2];
        $er++; 
      }
      elseif ($forecast[$i-1] == "A4") {
        $test[]       = $forecast[$i];
        $forecast[$i] = $defuzzy[3];
        $er++; 
      }
      elseif ($forecast[$i-1] == "A5") {
        $test[]       = $forecast[$i];
        $forecast[$i] = $defuzzy[4];
        $er++; 
      }
      elseif ($forecast[$i-1] == "A6") {
        $test[]       = $forecast[$i];
        $forecast[$i] = $defuzzy[5];
        $er++; 
      }
      elseif ($forecast[$i-1] == "A7") {
        $test[]       = $forecast[$i];
        $forecast[$i] = $defuzzy[6];
        $er++; 
      }
      elseif ($forecast[$i-1] == "A8") {
        $test[]       = $forecast[$i];
        $forecast[$i] = $defuzzy[7];
        $er++; 
      }
    }
    else {
      if ($test[$er-1] == "A1") {
        $test[]       = $forecast[$i];
        $forecast[$i] = $defuzzy[0];
        $er++;
      }
    elseif ($test[$er-1] == "A2") {
        $test[]       = $forecast[$i];
        $forecast[$i] = $defuzzy[1];
        $er++; 
      }
    elseif ($test[$er-1] == "A3") {
        $test[]       = $forecast[$i];
        $forecast[$i] = $defuzzy[2];
        $er++; 
      }
    elseif ($test[$er-1] == "A4") {
        $test[]       = $forecast[$i];
        $forecast[$i] = $defuzzy[3];
        $er++; 
      }
    elseif ($test[$er-1] == "A5") {
        $test[]       = $forecast[$i];
        $forecast[$i] = $defuzzy[4];
        $er++; 
      }
    elseif ($test[$er-1] == "A6") {
        $test[]       = $forecast[$i];
        $forecast[$i] = $defuzzy[5];
        $er++; 
      }
    elseif ($test[$er-1] == "A7") {
        $test[]       = $forecast[$i];
        $forecast[$i] = $defuzzy[6];
        $er++; 
      }
    elseif ($test[$er-1] == "A8") {
        $test[]       = $forecast[$i];
        $forecast[$i] = $defuzzy[7];
        $er++; 
      }
    }
  }
    return $forecast;
  }
}

/* End of file M_dashboard.php */
/* Location: ./application/models/M_dashboard.php */