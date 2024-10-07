<?php 

        $r = array(1,2,3,4,5);
        $e = array(2,3,4,5,6);
       
        // $eror = 0;
        // for ($i=0; $i < count($r); $i++) { 
        //     $eror+= abs($e[$i]-$r[$i])/$e[$i];
        // }

        // $mape = ($eror*100)/(count($e));
        // echo $mape;
            $sum=0;
            for ($i=0; $i <count($r) ; $i++) { 
                $sum=$sum+$r[$i];
            }
            echo $sum;




?>