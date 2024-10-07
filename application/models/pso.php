<?php
    
    include 'chen.php';
    include 'chen_pso.php';
    
    class pso {    
      public function __construct($data, $max, $min, $partikel, $iterasi, $c1, $c2, $w){ 
        $this->data     = $data;
		    $this->max      = $max;
		    $this->min      = $min;
		    $this->partikel = $partikel;
		    $this->iterasi  = $iterasi;
        $this->c1       = $c1;
        $this->c2       = $c2;
        $this->w        = $w;
		    $this->doPso();
      }
      
      public function doPso(){
	      $data       = $this->data;
	      $max        = $this->max;
	      $min        = $this->min;
	      $partikel   = $this->partikel;
	      $iterasi    = $this->iterasi;
        $c1         = $this->c1;
        $c2         = $this->c2;
        $w          = $this->w;
        $div        = pow(10, 3);
        $r1         = mt_rand(0 * $div, 1 * $div) / $div;
        $r2         = mt_rand(0 * $div, 1 * $div) / $div;
        $pbest      = [];
        $velocity   = [];
        $posisi     = [];
        
        // MELAKUKAN ITERASI SEBANYAK INPUTAN
        for ($n_iter=1; $n_iter <= $iterasi; $n_iter++){
          if ($n_iter==1) {
            // MELAKUKAN PENCARIAN NILAI FITNESS SETIAP PARTIKEL
            for ($n_par=0; $n_par < $partikel; $n_par ++) { 
              $posisi[] = $this->Get_fitness($data, $max, $min);
            }
            // MENENTUKAN VELOCITY SETIAP DIMENSI PADA SETIAP PARTIKEL
            $gbest      = $this->min_gbest($posisi);
            $velocity   = $this->first_velocity($posisi); 
            for ($i=count($posisi); $i < (count($posisi)*2); $i++) { 
              for ($j=0; $j < count($velocity[0]); $j++) { 
                $velocity_awal  = $velocity[$i-$partikel][$j];
                $velocity[$i][$j] = ($w*$velocity_awal) + (($c1*$r1)*($posisi[$i-$partikel][$j] - $posisi[$i-$partikel][$j])) + (($c2*$r2)*($posisi[$gbest[1]][$j] -$posisi[$i-$partikel][$j]));
              }
            }

            for ($i=0; $i < $partikel; $i++) { 
              $pbest[] = $i;
            }
            

            // MENGHITUNG POSISI BARU
            $n_partikel = count($posisi);
            $n_kolom    = count($posisi[0]);
            for ($i=$n_partikel; $i < ($n_partikel*2); $i++) { 
              for ($j=0; $j < $n_kolom-1; $j++) {
                $posisi_awal = $posisi[$i-$partikel][$j]; 
                $posisi[$i][$j] = $posisi_awal + $velocity[$i][$j];
              }
            }
            
            // FITNESS POSISI BARU
            $new_pos = [];
            for ($i=$n_partikel; $i < ($n_partikel *2); $i++) { 
              $new_pos[] = $posisi[$i];
            }
            
            $p = 0;
            $op = count($posisi)-$partikel;
            for ($i=$op; $i < ($op*2); $i++) { 
              $posisi[$i][9] = $this->doChen_Pso($data, $new_pos[$p]);
              $p++;
            }
            // END FITNESS

            // MENCARI PBEST BARU
            for ($i=0; $i < $partikel ; $i++) { 
              if ($posisi[$pbest[$i]][9] >$posisi[$i+$partikel][9]) {
                $pbest[$i]=$i+$partikel;
              }
            }
          }
          
          else{
            
            // GBEST BARU DARI PBEST TERBARU
            $tt = 0;
            $minn = $posisi [$pbest[$tt]][9];
            $temp = 0;
            for ($j=0; $j < $partikel-1; $j++) { 
              if ($minn>$posisi [$pbest[$j+1]][9]) {
                $minn = $posisi [$pbest[$j+1]][9];
                $temp = $pbest[$j+1];
              }
            }
            
            $gbest = [];
            for ($i=0; $i < 2; $i++) { 
              $gbest[0] = $minn;
              $gbest[1] = $temp;
            }
            // END GBEST BARU DARI PBEST BARU


            // VELOCITY UPDATE
            $ert = 0;
            for ($k=count($posisi ); $k < (count($posisi )+$partikel); $k++) { 
              for ($l=0; $l < count($velocity[0]); $l++) { 
                $velocity[$k][$l] = ($w*$velocity[$pbest[$ert]][$l]) + (($c1*$r1)*($posisi [$pbest[$ert]][$l] - $posisi [$pbest[$ert]][$l])) + (($c2*$r2)*($posisi [$gbest[1]][$l] - $posisi [$pbest[$ert]][$l]));
              }
              $ert++;
            }
            

            // POSISI BARU
            $n_partikel = count($posisi);
            $n_kolom    = count($posisi[0]);
            $ert = 0;
            for ($k=$n_partikel; $k < ($n_partikel+$partikel); $k++) { 
              for ($l=0; $l < $n_kolom-1; $l++) { 
                $posisi [$k][$l] =$posisi [$pbest[$ert]][$l] + $velocity[$k][$l];
              }
              $ert++;
            }
            // END POSISI BARU
            

            // FITNESS POSISI BARU
            $new_pos = [];
            for ($j=$n_partikel; $j < ($n_partikel+$partikel); $j++) {
              $new_pos[] = $posisi [$j];
            }
            
            $p = 0;
            $op = count($posisi)-$partikel;
            for ($j=$op; $j < ($op+$partikel); $j++) { 
              $posisi[$j][9] = $this->doChen_Pso($data, $new_pos[$p]);
              $p++;  
            }
            //END FITNESS POSISI BARU

            // MENCARI PBEST BARU
            $ert = 0;
            for ($i=$op; $i < ($op+$partikel); $i++) { 
              if ($posisi[$pbest[$ert]][9] > $posisi[$i][9]) {
                $pbest[$ert] = $i;
              }
              $ert++;
            }
          }
        }
        $this->next = $posisi;
      }
      
      // MENENTUKAN NILAI GBEST
      public function min_gbest($posisi){
        $fitness  = $posisi[0][9];
        $temp     = 0;
        $G_best   = [];
        for ($i=1; $i < count($posisi); $i++) { 
          if ($fitness>$posisi[$i][9]) {
            $fitness = $posisi[$i][9];
            $temp = $i;
          }
        } 
        $G_best[] = $fitness;
        $G_best[] = $temp;
        return $G_best;
      }
      
      // MENENTUKAN NILAI VELOCITY AWAL = 0
      public function first_velocity($posisi){
      $velocity_awal = [];
      for ($i=0; $i < count($posisi); $i++) { 
        for ($j=0; $j < 9; $j++) { 
          $velocity_awal[$i][$j] = 0;
        }
      }
      return $velocity_awal;
      }
      
      // MENJANLAKAN PERHITUNGAN CHEN PADA CLASS CHEN UNTUK MENDAPATKAN NILAI FITNESS MASING-MASING PARTIKEL
      public function Get_fitness($data, $max, $min){
        $Get_fitness = new Chen($data, $max, $min);
        return $Get_fitness->fitness;		
	    }
       
      // MENJANLAKAN PERHITUNGAN CHEN PADA CLASS CHEN_PSO SETELAH MENDAPATKAN POSISI TERBAIK
      public function doChen_Pso($data,  $posisi){
        $doChen_Pso = new chen_pso($data, $posisi);
        return $doChen_Pso->N_MAE;		
      }
    }

?>