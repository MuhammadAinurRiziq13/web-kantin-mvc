<main class="d-flex flex-nowrap">
    <?php
        include "../app/views/template/sidebar.php";
    ?>
    
    <div class="data-barang overflow-auto" style="width: 100%">
        <div class="nav-utama"></div>
        <div class="wrap-barang bg-gray pt-5">
          <div class="header pb-2 pt-4 ms-4 me-5 mt-3 d-flex justify-content-between align-items-center border-bot">
            <h2 class="fw-bold">History Penjualan</h2>
            <div class="grup pe-0 mt-3">
              <form action="" method="post" class="mb-2">
                <select id="supplier" name="supplier" class="px-3 py-1 rounded-3 pilih-bulan me-2" style="width: 10rem">
                <option value="0">Supplier</option>
                  <?php
                  foreach ($data['suppliers'] as $item){
                    echo '<option value="'.$item['id_supplier'].'">'.$item['nama_supplier'].'</option>';
                  }        
                  ?>
                </select>
                <input type="date" name="date" id="date" class="date px-3 py-1 rounded-3" style="width: 10rem">
                <button type="submit" name="filter1" class="mx-2 rounded-3 px-3 py-1 cari-barang"><i class="fa-solid fa-list"></i></button>
              </form>
              
              <form action="" method="post">
                <select id="bulan" name="bulan" class="px-3 py-1 rounded-3 pilih-bulan me-2" style="width: 10rem">
                  <option value="0">Bulan</option>
                  <option value="1">Januari</option>
                  <option value="2">Februari</option>
                  <option value="3">Maret</option>
                  <option value="4">April</option>
                  <option value="5">Mei</option>
                  <option value="6">Juni</option>
                  <option value="7">Juli</option>
                  <option value="8">Agustus</option>
                  <option value="9">September</option>
                  <option value="10">Oktober</option>
                  <option value="11">November</option>
                  <option value="12">Desember</option>
                </select>
                <select id="tahun" name="tahun" class="px-3 py-1 rounded-3 pilih-tahun" style="width: 10rem">
                  <option value="0">Tahun</option>
                  <option value="2022">2022</option>
                  <option value="2023">2023</option>
                </select>
                <button type="submit" name="filter2" class="mx-2 rounded-3 px-3 py-1 cari-barang"><i class="fa-solid fa-list"></i></button>
              </form>
            </div>
          </div>
          <div class="table-responsive mx-4 me-5">
            <table class="mt-2 table table-secondary table-bordered">
              <thead>
                <tr class="table-light"> 
                  <th>No</th>
                  <th>Date</th>
                  <th style="width: 27%">Nama Barang</th>
                  <th>Jumlah</th>
                  <th>Total Beli</th>
                  <th>Total Harga</th>
                  <?php
                  if (!isset($_POST['filter1']) || $data['history'] == $data['history2']) { ?>
                  <th>Option</th>
                  <?php } ?>
                </tr>
              </thead>
              <tbody>
              <?php
                if (isset($_POST['filter1']) && $data['history'] != $data['history2']) {
                  $no = 1;
                  
                  foreach($data['total'] as $item) {
                    $jumlahBeli = $item['total_beli'];
                    $jumlahJual = $item['total_jual'];
                    $jumlahKeuntungan = $item['total_keuntungan'];
                  }
              
                  foreach ($data['history'] as $item) {
                      ?>
                      <tr class="bg-secondary">
                          <td><?php echo $no++; ?></td>
                          <td><?php echo $item['tanggal_transaksi']; ?></td>
                          <td><?php echo $item['nama_barang']; ?></td>
                          <td><?php echo $item['total_qty']; ?></td>
                          <td>Rp. <?php echo number_format($item['harga_beli']); ?></td>
                          <td>Rp. <?php echo number_format($item['harga_jual']); }?></td>
                      </tr>
                  <tr>
                      <td class="table-success" colspan="4">Total Penjualan :</td>
                      <td class="table-success">Rp. <?php echo number_format($jumlahBeli); ?></td>
                      <td class="table-success">Rp. <?php echo number_format($jumlahJual); ?></td>
                  </tr>
                  <tr>
                    <td class="bg-success-subtle" colspan="4">Total Keuntungan :</td>
                    <td class="bg-success text-white"colspan="2" >Rp. <?php echo number_format($jumlahJual - $jumlahBeli); ?></td>
                  </tr>
                  <?php 
                    } else {
                      $no = 1;
                      foreach($data['total'] as $item) {
                        $jumlahBeli = $item['total_beli'];
                        $jumlahJual = $item['total_jual'];
                        $jumlahKeuntungan = $item['total_keuntungan'];
                      }
                      foreach ($data['history'] as $item) {
                          ?>
                          <tr class="bg-secondary">
                              <td><?php echo $no++; ?></td>
                              <td><?php echo $item['tanggal_transaksi']; ?></td>
                              <td><?php echo $item['nama_barang']; ?></td>
                              <td><?php echo $item['total_qty']; ?></td>
                              <td>Rp. <?php echo number_format($item['harga_beli']); ?></td>
                              <td>Rp. <?php echo number_format($item['harga_jual']); ?></td>
                              <td><a href="#" class="edit" data-id="<?= $item['id_transaksi']; ?>" data-url="<?= BASEURL; ?>" data-bs-toggle="modal" data-bs-target="#detailModal">Detail</a></td>
                          </tr>
                          <?php
                      }
                      ?>
                      <tr>
                          <td class="table-success" colspan="4">Total Penjualan :</td>
                          <td class="table-success">Rp. <?php echo number_format($jumlahBeli); ?></td>
                          <td class="table-success">Rp. <?php echo number_format($jumlahJual); ?></td>
                          <td></td>
                      </tr>
                      <tr>
                        <td class="bg-success-subtle" colspan="4">Total Keuntungan :</td>
                        <td class="bg-success text-white"colspan="2" >Rp. <?php echo number_format($jumlahKeuntungan); ?></td>
                        <td></td>
                      </tr>
                    <?php
                  }
                  ?>
              </tbody>
            </table>
          </div>
         </div>
       </div>
     </main>

    <div class="modal fade" id="detailModal" aria-hidden="true" aria-labelledby="exampleModalLabel" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h1 class="modal-title fs-5" id="exampleModalToggleLabel2">Detail Transaksi</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body border-bottom ps-5">
                <div class="table-responsive">
                    <table id="detailTable" class="table table-borderless">
                        <thead>
                            <tr>
                                <th style="width: 34%" class="th-struk">Nama Barang</th>
                                <th class="th-struk" style="width: 25%">Harga Barang</th>
                                <th style="width: 16%" class="th-struk">Jumlah</th>
                                <th class="th-struk" style="width: 18%">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be dynamically inserted here -->
                        </tbody>
                    </table>
                </div>
              </div>

              <div class="modal-body d-flex justify-content-end me-2">
                  <table>
                      <tr>
                          <td>Total Harga:</td>
                          <td id="totalHarga">Rp. 0</td>
                      </tr>
                  </table>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
              </div>
          </div>
      </div>
    </div>