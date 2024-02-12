<main class="d-flex flex-nowrap">
    <?php
        include "../app/views/template/sidebar.php";
    ?>
    <div class="data-barang overflow-auto" style="width: 100%">
        <div class="nav-utama"></div>
        <div class="wrap-barang bg-gray pt-5">
          <div class="header pt-4 pt-5 border-bot ms-4 me-5">
            <h2 class="fw-bold mb-3">Supplier</h2>
            <div class="wrap-header d-flex justify-content-end align-items-center mb-3">
              <button type="button" class="me-3 rounded-3 px-3 py-1 cari-barang" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fa-solid fa-user-plus"></i> Add Supplier</button>
              <form action="<?= BASEURL; ?>/Supplier" method="post">
                <input type="text" name="search" id="search" placeholder="Search..." class="px-3 py-1 rounded-3 search" style="width: 13rem" />
                <button type="submit" class="me-3 rounded-3 px-3 py-1 cari-barang">
                  <i class="fa-solid fa-magnifying-glass"></i>
                </button>
              </form>
            </div>
          </div>
          <div class="table-responsive mx-4 me-5">
          <?php Flasher::flash(); ?>
          <table class="mt-2 table table-secondary table-bordered">
            <thead>
              <tr class="table-light">
                <th>No</th>
                <th style="width: 30%">Nama Supplier</th>
                <th style="width: 20%">Telepon</th>
                <th style="width: 20%">Date</th>
                <th>Option</th>
              </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                 foreach ($data['suppliers'] as $supplierData) { ?>
                    <tr class='py-3' style='height: 3rem'>
                        <td><?= $no++; ?></td>
                        <td><?= $supplierData['nama_supplier']; ?></td>
                        <td><?= $supplierData['telepon']; ?></td>
                        <td><?= $supplierData['tanggal_input']; ?></td>
                        <td>
                            <a href="#" class='edit ' data-bs-toggle="modal" data-bs-target="#editModal<?= $supplierData['id_supplier']; ?>" data-id="<?= $supplierData['id_supplier']; ?>">Edit</a>
                            <a href="<?= BASEURL; ?>/supplier/deleteSupplier/<?= $supplierData['id_supplier'];?>" class='hapus' onclick='return confirmDelete(event);'>Delete</a>
                        </td>
                    </tr>
                <?php } ?>                
            </tbody>
          </table>
                 </div>
        </div>
    </div>

    <div class="modal fade" id="addModal">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5">Add Supplier</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= BASEURL; ?>/supplier/tambahSupplier" method='post' enctype="multipart/form-data">
                    <input type='hidden' name='id_supplier'>
                    <div class="mb-1">
                        <label for="nama-supplier" class="col-form-label">Nama Supplier</label>
                        <input type='text' class="form-control" id="nama-supplier" name='nama-supplier' required>
                    </div>
                    <div class="mb-1">
                        <label for="telepon" class="col-form-label">Nomor Telepon</label>
                        <input type='text' class="form-control" id="telepon" name='telepon' required>
                    </div>
                    <div class="mb-1 d-flex justify-content-end mt-3 gap-2">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                        <input type='submit' name='submit' class="btn btn-primary" value='Simpan'>
                    </div>
                </form>
            </div>
          </div>
        </div>
    </div>

    <?php foreach ($data['suppliers'] as $supplierData) { ?>
      <div class="modal fade" id="editModal<?= $supplierData['id_supplier']; ?>">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5">Add Supplier</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <form action="<?= BASEURL; ?>/supplier/updateSupplier" method='post' enctype="multipart/form-data">
                      <input type='hidden' name='id_supplier' value="<?= $supplierData['id_supplier']; ?>">
                      <div class="mb-1">
                          <label for="nama-supplier" class="col-form-label">Nama Supplier</label>
                          <input type='text' class="form-control" id="nama-supplier" name='nama_supplier' value="<?= $supplierData['nama_supplier']; ?>">
                      </div>
                      <div class="mb-1">
                          <label for="telepon" class="col-form-label">Nomor Telepon</label>
                          <input type='text' class="form-control" id="telepon" name='telepon' value="<?= $supplierData['telepon']; ?>">
                      </div>
                      <div class="mb-1 d-flex justify-content-end mt-3 gap-2">
                          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                          <input type='submit' name='submit' class="btn btn-primary" value='Update'>
                      </div>
                  </form>
              </div>
            </div>
          </div>
      </div>
    <?php } ?>  

    </main>