

    <div class="container-fluid my-5">
      <div class="container text-black">
        <div class="row">
          <div class="col-lg-6">
            <div class="card">
              <div class="card-body ">
                <div class="card-header bg-primary text-center text-white"><h5>INPUT PARAMETER PSO</h5></div>
                <!-- <strong><p class="card-text text-center m-2">PILIH PARAMETER PSO</p></strong> -->
                <form class="needs-validation" novalidate action="<?= base_url('forecast') ?>" method="POST">

                
                <div class="mt-4">
                <strong><label for="validationCustom04">Jumlah Iterasi :</label></strong>
                <select name="iterasi" id="validationCustom04" required class="form-select form-select-sm" aria-label=".form-select-sm example">
                  <option selected disabled value="">Silahkan Pilih</option>
                  <option>50</option>
                  <option>100</option>
                  <option>150</option>
                  <option>200</option>
                  <option>250</option>
                  <option>500</option>
                  <option>600</option>
                  <option>700</option>
                  <option>800</option>
                  <option>900</option>
                  <option>1000</option>
                </select>
                </div>

                <div class="mt-2">
                <strong><label for="validationCustom04">Jumlah Partikel :</label></strong>
                <select name="partikel" id="validationCustom04" required class="form-select form-select-sm" aria-label=".form-select-sm example">
                  <option selected disabled value="">Silahkan Pilih</option>
                  <option>10</option>
                  <option>20</option>
                  <option>30</option>
                  <option>40</option>
                  <option>50</option>
                  <option>100</option>
                  <option>200</option>
                  <option>300</option>
                  <option>400</option>
                  <option>500</option>
                </select>
                </div>

                <div class="mt-2">
                <strong><label for="validationCustom04">Bobot Inersia :</label></strong>
                <select name="w" id="validationCustom04" required class="form-select form-select-sm" aria-label=".form-select-sm example">
                  <option selected disabled value="">Silahkan Pilih</option>
                  <option>0.01</option>
                  <option>0.02</option>
                  <option>0.03</option>
                  <option>0.04</option>
                  <option>0.05</option>
                  <option>0.06</option>
                  <option>0.07</option>
                  <option>0.08</option>
                  <option>0.09</option>
                  <option>0.1</option>
                  <option>0.2</option>
                  <option>0.3</option>
                  <option>0.4</option>
                  <option>0.5</option>
                  <option>0.6</option>
                  <option>0.7</option>
                  <option>0.8</option>
                  <option>0.9</option>
                </select>
                </div>

                <div class="mt-2">
                <strong><label for="validationCustom04">Nilai C1 :</label></strong>
                <select name="c1" id="validationCustom04" required class="form-select form-select-sm" aria-label=".form-select-sm example">
                  <option selected disabled value="">Silahkan Pilih</option>
                  <option>0.5</option>
                  <option>1</option>
                  <option>1.5</option>
                  <option>2</option>
                </select>
                </div>

                <div class="mt-2">
                <strong><label for="validationCustom04">Nilai C2 :</label></strong>
                <select name="c2" id="validationCustom04" required class="form-select form-select-sm" aria-label=".form-select-sm example">
                  <option selected disabled value="">Silahkan Pilih</option>
                  <option>0.5</option>
                  <option>1</option>
                  <option>1.5</option>
                  <option>2</option>
                </select>
                </div>
                
                <button type="submit" name="submit" class="btn btn-danger w-100 mt-4">Forecast</button>
                </form>
              </div>
            </div>
            <br>
          </div>
          
          <div class="col-lg-6">
            <div class="card">
              <div class="card-body">
                <div class="card-header bg-primary text-center text-white"><h5 class="card-title">DATA AKTUAL</h5></div>
                <br/>
                <div class="scroll" style=" height: 368px; overflow: scroll;">
                <table id="dataset" class="table table-bordered table-striped table-hover ">
                  <thead class="table-dark">
                    <tr class="text-center">
                      <th>Tanggal</th>
                      <th>Kasus Harian COVID-19</th>
                    </tr>
                  </thead>
                  <tbody class="text-center">
                    <?php
                    for ($i=0; $i < count($data); $i++) { 
                      echo '
                      <tr>
                      <td>'.$data[$i][0].'</td>
                      <td>'.$data[$i][1].'</td>
                      </tr>';
                    }
                    
                    ?>
                  </tbody>
                </table>
                </div>                 
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    
