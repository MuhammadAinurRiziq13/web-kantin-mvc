<main class="d-flex flex-nowrap">
    <?php
    include "../app/views/template/sidebar.php";
    ?>
    <div class="dashboard overflow-hidden" style="width: 100%">
        <div class="nav-utama"></div>
        <div class="row bg-gray pt-5" style="height: 100%">
            <main class="col-md-9 col-lg-10 px-md-4 mx-4 mt-4" style="width: 100%">
                <div class="d-flex justify-content-between flex-wrap align-items-center pt-4 pb-2 me-5 mb-4 border-bot">
                    <?php
                    if ($_SESSION['level'] == 1) {
                        echo '<h1 class="h1 fw-bold">Welcome Pemilik</h1>';
                    } elseif ($_SESSION['level'] == 2) {
                        echo '<h1 class="h1 fw-bold">Welcome Admin</h1>';
                    }
                    ?>
                </div>
                <div class="row">
                    <div class="col-sm-6 mb-3" style="width: 330px">
                        <div class="card rounded-4">
                            <div class="card-body d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Jenis Barang</h5>
                                    <p class="card-text fw-bold my-4 fs-3"><?= $data['barang']; ?></p>
                                </div>
                                <a href="DataBarang" class="btn bg-dongker text-white" style="height: 35px; width: 40px"
                                ><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-grid-fill" viewBox="0 0 16 16">
                                        <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5zm8 0A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5zm-8 8A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5zm8 0A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 mb-3" style="width: 330px">
                        <div class="card rounded-4">
                            <div class="card-body d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Total Pembelian</h5>
                                    <p class="card-text fw-bold my-4 fs-3"><?= $data['pembelian']; ?></p>
                                </div>
                                <a href="History" class="btn bg-dongker text-white" style="height: 35px; width: 40px"
                                ><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cash-coin" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M11 15a4 4 0 1 0 0-8 4 4 0 0 0 0 8m5-4a5 5 0 1 1-10 0 5 5 0 0 1 10 0"/>
                                        <path d="M9.438 11.944c.047.596.518 1.06 1.363 1.116v.44h.375v-.443c.875-.061 1.386-.529 1.386-1.207 0-.618-.39-.936-1.09-1.1l-.296-.07v-1.2c.376.043.614.248.671.532h.658c-.047-.575-.54-1.024-1.329-1.073V8.5h-.375v.45c-.747.073-1.255.522-1.255 1.158 0 .562.378.92 1.007 1.066l.248.061v1.272c-.384-.058-.639-.27-.696-.563h-.668zm1.36-1.354c-.369-.085-.569-.26-.569-.522 0-.294.216-.514.572-.578v1.1h-.003zm.432.746c.449.104.655.272.655.569 0 .339-.257.571-.709.614v-1.195l.054.012z"/>
                                        <path d="M1 0a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h4.083c.058-.344.145-.678.258-1H3a2 2 0 0 0-2-2V3a2 2 0 0 0 2-2h10a2 2 0 0 0 2 2v3.528c.38.34.717.728 1 1.154V1a1 1 0 0 0-1-1z"/>
                                        <path d="M9.998 5.083 10 5a2 2 0 1 0-3.132 1.65 5.982 5.982 0 0 1 3.13-1.567z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 mb-3" style="width: 330px">
                        <div class="card rounded-4">
                            <div class="card-body d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Total Pemasukan</h5>
                                    <p class="card-text fw-bold my-4 fs-3">Rp. <?= number_format($data['pemasukan']); ?></p>
                                </div>
                                <a href="History" class="btn bg-dongker text-white" style="height: 35px; width: 40px"
                                ><i class="fa-solid fa-dollar-sign"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</main>
