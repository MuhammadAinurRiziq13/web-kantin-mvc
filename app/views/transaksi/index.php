<main class="d-flex flex-nowrap">
    <?php
        include "../app/views/template/sidebar.php";
    ?>
    <div class="data-barang overflow-auto" style="width: 100%">
        <div class="nav-utama"></div>
        <div id="transaksi" class="wrap-barang bg-gray d-flex pt-5 pe-5" style="width: 100%">
          <div class="wrap-transaksi pt-4" style="width: 78%">
            <div class="header py-4">
              <h2 class="fw-bold ps-4 mb-3">Transaksi</h2>
              <div class="wrap-header d-flex justify-content-between align-items-center border-bot mx-4">
                <ul class="list-grup gap-3 ps-0 mb-0">
                    <li class="list-item"><a href="Transaksi" ><i class="fa-solid fa-border-all me-1"></i> All Items</a></li>
                    <li class="list-item">                  
                    <form action='<?= BASEURL; ?>/Transaksi' method='post'>
                        <input type='hidden' name='kategori' value=1>
                        <button type='submit'><i class="fa-solid fa-utensils me-1"></i> Food</button>
                    </form>
                    </li>
                    <li class="list-item">                  
                    <form action='<?= BASEURL; ?>/Transaksi' method='post'>
                        <input type='hidden' name='kategori' value=2>
                        <button type='submit'><i class="fa-solid fa-wine-glass me-1"></i> Drink</button>
                    </form>
                    </li>
                    <li class="list-item">                  
                    <form action='<?= BASEURL; ?>/Transaksi' method='post'>
                        <input type='hidden' name='kategori' value=3>
                        <button type='submit'><i class="fa-solid fa-burger me-1"></i> Snack</button>
                    </form>
                    </li>
                </ul>
                <div class="grup pe-0">
                  <form action="" method="post">
                    <input type="text" name="search" id="search" placeholder="Search..." class="px-3 py-1 rounded-3 search" style="width: 13rem" />
                    <button type="submit" class="me-3 rounded-3 px-3 py-1 cari-barang">
                      <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                  </form>
                </div>
              </div>
            </div>

            <div class="card-container px-4 pt-2 d-flex flex-wrap gap-3 pb-4" >
            <?php
                $no = 1;
                foreach ($data['barang'] as $item) {
                ?>
                <div class="kartu rounded-4 pt-2 bg-white d-flex align-items-center flex-column" style="width:18%">
                    <img src="../public/uploads/<?= $item['gambar']; ?>" class="rounded-4 kartu-image" style="width: 7rem; height: 4rem" />
                    <div class="kartu-body pt-3 d-flex align-items-center flex-column">
                        <h5 class="kartu-title h6 fw-bold"><?= $item['nama_barang']; ?></h5>
                        <p class="kartu-text mb-2 fs-6"><?= number_format($item['harga_jual']); ?></p>
                        <form method="POST" action="<?= BASEURL; ?>/Transaksi/tambahKeranjang" class="add-to-cart-form">
                            <input type="hidden" name="id_barang" value="<?= $item['id_barang']; ?>">
                            <div class="items-kuantiti d-flex align-items-end gap-1 justify-content-center">
                                <?php
                                    // Check if the stock is zero, and disable the input accordingly
                                    $disabled = $item['stok_barang'] == 0 ? 'disabled' : '';
                                ?>
                                <button type="button" class="min-items">-</button>
                                <?php if ($item['stok_barang'] > 0) : ?>
                                    <input type="text" min="1" max="<?= $item['stok_barang']; ?>" value="1" name="quantity_<?= $item['id_barang']; ?>" class="quantity-input px-2" style="width: 2rem; font-size: 0.8rem" />
                                <?php else : ?>
                                    <input type="text" value="0" class="quantity-input px-2" style="width: 2rem; font-size: 0.8rem" />
                                <?php endif; ?>
                                <button type="button" class="plus-items">+</button>
                            </div>
                            <?php if ($item['stok_barang'] > 0) : ?>
                                <button type="submit" name="add_to_cart" class="btn bg-dongker py-0 rounded-3 text-white add-transaksi mt-2" style="width:7rem">Add</button>
                            <?php else : ?>
                                <p class="p-0 pt-2 text-danger text-center">Stok Habis</p>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
                <?php
                }
            ?>
            </div>
          </div>
          
          <!-- Baris di bawah adalah tempat di mana orderMenu.php akan diletakkan -->
          <div class="order-menu bg-white p-3 ms-0" style="width: 22%; height: 88%">
            <div class="header-order-menu border-bot">
              <h5 class="fw-bold">Order Menu</h5>
              <p class="date-menu"><?= $data['date']; ?></p>
            </div>
            <div class="items pt-3" style="height: 72%">
              <div class="title d-flex align-items-center justify-content-between">
                <h5 class="fw-bold mb-1">Items</h5>
                <form method="POST" action="<?= BASEURL; ?>/Transaksi/hapusKeranjang">
                  <button type="submit" name="clear_cart" class="btn bg-dongker text-white remove-items p-0" style="height: 1.5rem; width: 3rem; font-size: .7rem">Clear</button>
                </form>
              </div>
              <div class="items-wrap mb-0" style="height: 20rem">
                <?php
                $totalQuantity = 0;
                // Loop untuk menampilkan item di keranjang
                if (isset($data['cartItems']) && !empty($data['cartItems'])) {
                    foreach ($data['cartItems'] as $cartItem) {
                        $itemQuantity = $cartItem["quantity"];

                        ?>
                        <div class="list-items-order d-flex border-bot py-2 justify-content-between">
                            <div class="d-flex py-2">
                                <img src="uploads/<?php echo $cartItem['gambar']; ?>" alt="order items" style="height: 50px;width:70px;" />
                                <div class="inner-items-order ms-2 d-flex flex-column justify-content-center">
                                    <p class="items-order-title fw-bold mb-1"><?php echo $cartItem['nama_barang']; ?></p>
                                    <p class="items-order-harga mb-1">Rp. <?php echo number_format($cartItem['harga_jual']); ?></p>
                                </div>
                            </div>
                            <div class="items-kuantiti d-flex flex-column justify-content-between align-items-end gap-1 me-1">
                                <form method="POST" action="<?= BASEURL; ?>/Transaksi/hapusKeranjang">
                                    <input type="hidden" name="id_barang" value="<?php echo $cartItem['id_barang']; ?>">
                                    <button type="submit" name="remove_from_cart" class="remove-items btn btn-danger p-0" style="height: 1rem; width: 1rem; font-size: .6rem">x</button>
                                </form>
                                <div class="items-kuantiti d-flex align-items-end gap-1 ms-4 me-0">
                                    <p class="m-0"><?php echo $itemQuantity . ' item'; ?></p>
                                </div>
                            </div>
                        </div>
                        <?php
                        $totalQuantity += $itemQuantity;
                    }
                }
                ?>
            </div>
            </div>
            <div class="bayar-container border-top py-2 px-1 mt-2">
                <div class="bayar-items d-flex justify-content-between bg-dongker py-2 px-2 rounded-4 align-items-center">
                    <div class="bayar-teks ms-2">
                        <p class="mb-1 jml-item"><?php echo $totalQuantity . " items"; ?></p>
                        <p class="text-white mb-0 jml-harga"><?php echo "Rp. " . number_format($data['totalPrice']); ?></p>
                    </div>
                    <button type="submit" class="rounded-5 px-3  bg-white" style="height: 2rem" data-bs-toggle="modal" data-bs-target="#bayarModal">Entry</button>
                </div>
            </div>
            </div>
        </div>
      </div>

      <div class="modal fade" id="bayarModal" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalToggleLabel">Proses Transaksi</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="" id="checkoutForm" data-url="<?= BASEURL; ?>">
                  <div class="modal-body d-flex flex-column align-items-center">
                      <p class="mb-2">Harga Total</p>
                      <h4 class="fw-bold"><?php echo "Rp. " . number_format($data['totalPrice']); ?></h4>
                  </div>
                  <div class="modal-body">
                      <p class="mb-1">Uang yang dibayar</p>
                      <input type="text" class="form-control" id="uangBayar" name="uangBayar" />
                  </div>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-danger" name="bayar" id="submitBtn" data-bs-dismiss="modal" aria-label="Close">Bayar</button>
                  </div>
                  </form>
              </div>
        </div>
      </div>

      <div class="modal fade" id="strukModal" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalToggleLabel2">Struk Transaksi</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body border-bottom ps-5">
                  <div class="table-responsive">
                      <table id="strukTable" class="table table-borderless">
                          <thead>
                              <tr>
                                  <th style="width: 34%" class="th-struk">Nama Barang</th>
                                  <th class="th-struk" style="width: 25%">Harga Barang</th>
                                  <th style="width: 16%" class="th-struk">Jumlah</th>
                                  <th class="th-struk" style="width: 18%">Total</th>
                              </tr>
                          </thead>
                          <tbody>
                              <!-- Data will be dynamically added here using JavaScript -->
                          </tbody>
                      </table>
                  </div>
                </div>

                <div class="modal-body d-flex justify-content-end me-3">
                    <table>
                        <tr>
                            <td>Total Harga:</td>
                            <td id="totalHarga">Rp. 0</td>
                        </tr>
                        <tr>
                            <td>Total Tunai:</td>
                            <td id="totalTunai">Rp. 0</td>
                        </tr>
                        <tr>
                            <td>Kembalian:</td>
                            <td id="kembalian">Rp. 0</td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <a href="<?= BASEURL; ?>/Transaksi" class='btn btn-danger'>Close</a>
                </div>
            </div>
        </div>
      </div>

    </main>