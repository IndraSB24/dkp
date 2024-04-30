<style>
    .no-wrap{
        white-space: nowrap;
    }
</style>

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="row mt-5">
            <div class="col-lg-12 mt-2">
                <div class="flash-data" data-flashdata="<?= $this->session->flashdata('pesan'); ?>"></div>
                <button class="btn btn-lg btn-primary mb-3 font-weight-bold" data-toggle="modal" data-target="#form_tambah">Tambah Kelompok Produk</button>
                <br><br>
                <table class="table table-bordered table-light shadow-sm p-3 mb-5 bg-white rounded dt-responsive" id="tabel-produk" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Kode</th>
                            <th class="text-center">Nama</th>
                            <th class="text-center">Deskripsi</th>
                            <th class="text-center">Dibuat Oleh</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                            $itung = 0;
                            foreach ($kelompok_produk as $row) :
                                $itung = $itung + 1;
                                if($row['status'] == 1){
                                    $stat = '<div class="badge badge-sm badge-success">Aktif</div>';
                                }else if($row['status'] == 0){
                                    $stat = '<div class="badge badge-sm badge-danger">Nontaktif</div>';
                                }
                        ?>
                            <tr>
                                <td class="text-center"><?= $itung; ?></td>
                                <td class="text-center"><?= $row['kode']; ?></td>
                                <td class="text-center no-wrap"><?= $row['nama']; ?></td>
                                <td class="text-left"><?= $row['deskripsi']; ?></td>
                                <td class="text-left no-wrap"><?= $row['dibuat_oleh']; ?></td>
                                <td class="text-center"><?= $stat; ?></td>
                                <td class="text-center no-wrap">
                                    <!-- Button Edit -->
                                    <a class="btn btn-sm btn-success text-light" id="btn-edit" data-toggle="modal" data-target="#modal_edit_<?= $row['id']; ?>">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <!-- Button Hapus -->
                                    <a href="<?= base_url(); ?>kelompok_produk/hapus/<?= $row['id']; ?>" class="btn btn-sm btn-danger text-light tombol-hapus"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </section>
</div>

<!-- Modal Tambah Kelompok Produk -->
<div class="modal fade mt-5" id="form_tambah" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog mt-5">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="color:white">
                <h5 class="modal-title" id="staticBackdropLabel">Tambah Kelompok Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url(); ?>kelompok_produk/tambah" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="kode">Kode Kelompok produk</label>
                        <input type="text" class="form-control" id="kode" name="kode" autocomplete="off" placeholder="Masukkan Kode Kelompok Produk . . ." />
                    </div>
                    <div class="form-group">
                        <label for="nama_satuan">Nama Kelompok produk</label>
                        <input type="text" class="form-control" id="nama" name="nama" autocomplete="off" placeholder="Masukkan Nama Kelompok Produk . . ." />
                    </div>
                    <div class="form-group">
                        <label for="deskripsi_satuan">Deskripsi</label>
                        <input type="text" class="form-control" id="deskripsi" name="deskripsi" autocomplete="off" placeholder="Masukkan Deskripsi Kelompok Produk . . ." />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal Edit -->
<?php
    $itung = 0;
    foreach ($kelompok_produk as $row) :
        $itung = $itung + 1;
?>
<div class="modal fade mt-5" id="modal_edit_<?= $row['id']; ?>" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" role="dialog">
    <div class="modal-dialog mt-5">
        <div class="modal-content">
            <div class="modal-header bg-success" style="color:white">
                <h5 class="modal-title" id="staticBackdropLabel">Edit Kelompok Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_edit_<?= $row['id']; ?>" action="<?= base_url(); ?>kelompok_produk/update" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="kode_edit">Kode Kelompok produk</label>
                        <input type="text" class="form-control" id="kode_edit" name="kode_edit" autocomplete="off" placeholder="Masukkan Kode Kelompok Produk . . ." value="<?= $row['kode']; ?>"/>
                    </div>
                    <div class="form-group">
                        <label for="nama_edit">Nama Kelompok Produk</label>
                        <input type="text" class="form-control" id="nama_edit" name="nama_edit" autocomplete="off" placeholder="Masukkan Nama Kelompok Produk . . ." value="<?= $row['nama']; ?>" />
                    </div>
                    <div class="form-group">
                        <label for="deskripsi_edit">Deskripsi</label>
                        <input type="text" class="form-control" id="deskripsi_edit" name="deskripsi_edit" autocomplete="off" placeholder="Masukkan Deskripsi . . ." value="<?= $row['deskripsi']; ?>" />
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id_edit" id="id_edit" value="<?= $row['id']; ?>" />
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>