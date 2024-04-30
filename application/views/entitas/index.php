<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="row mt-5">
            <div class="col-lg-12 mt-2">
                <div class="flash-data" data-flashdata="<?= $this->session->flashdata('pesan'); ?>"></div>
                <a href="<?= base_url(); ?>entitas/tambah_entitas" class="btn btn-lg btn-primary mb-3 font-weight-bold">
                    Tambah Entitas
                </a>
                <a href="<?= base_url(); ?>entitas/tambah_kategori_entitas" class="btn btn-lg btn-primary mb-3 font-weight-bold">
                    Tambah Kategori Entitas
                </a>
                <table class="table table-bordered table-light shadow-sm p-3 mb-5 bg-white rounded dt-responsive nowrap" id="tabel-entitas" width="100%">
                    <thead>
                        <tr>
                            <th class=" text-center">Entitas</th>
                            <th class="text-center">Provinsi</th>
                            <th class="text-center">Kota</th>
                            <th class="text-center">Alamat</th>
                            <th class="text-center">Kontak</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($entitas as $row) : ?>
                            <tr>
                                <td class="text-capitalize"><?= $row['nama_entitas']; ?></td>
                                <td class="text-center"><?= $row['provinsi']; ?></td>
                                <td class="text-center"><?= $row['kota']; ?></td>
                                <td class="text-center"><?= $row['alamat']; ?></td>
                                <td class="text-center">
                                    <!-- Button Tambah Stock -->
                                    <a href="javascript:;" data-id_entitas="<?= $row['id']; ?>" data-toggle="modal" data-target="#detailKontak">
                                        <button class=" btn btn-sm btn-light"><i class="fas fa-user"> Detail Kontak </i></button>
                                    </a>
                                </td>
                                <td class="text-center">
                                    <!-- Button Tambah Stock -->
                                    <a href="javascript:;" data-id_entitas="<?= $row['id']; ?>" data-toggle="modal" data-target="#tambahstock">
                                        <button class=" btn btn-sm btn-light"><i class="fas fa-plus"></i></button>
                                    </a>
                                    <!-- Button Update -->
                                    <a href="<?= base_url(); ?>produk/update_produk/<?= $row['id_produk']; ?>" class="btn btn-sm btn-success text-light"><i class="fas fa-edit"></i></a>
                                    <!-- Button Hapus -->
                                    <a href="<?= base_url(); ?>produk/hapus_produk/<?= $row['id_produk']; ?>" class="btn btn-sm btn-danger text-light tombol-hapus"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </section>
</div>

<!-- Modal -->
<div class="modal fade mt-5" id="tambahstock" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm mt-5">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Tambah Stock Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url(); ?>produk/tambah_stock" method="POST">
                <div class="modal-body">
                    <input type="hidden" id="id_produk" name="id_produk">
                    <div class="form-group">
                        <label for="stock">Stock Produk</label>
                        <input type="number" class="form-control" id="stock" name="stock" autocomplete="off" placeholder="Masukan stock Produk..." min="1" required max="99">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Tambah Stock</button>
                </div>
            </form>
        </div>
    </div>
</div>