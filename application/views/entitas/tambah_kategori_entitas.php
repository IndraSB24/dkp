<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="<?= base_url(); ?>/kategori_entitas/tambah">
                            <div class="form-group">
                                <label for="nama">Nama Entitas</label>
                                <input type="text" class="form-control text-capitalize" id="nama" name="nama" autocomplete="off" placeholder="Masukan Nama Entitas..." value="<?= set_value('nama');  ?>">
                                <?= form_error('nama', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="form-group">
                                <label for="deskripsi">Deskripsi Entitas</label>
                                <input type="text" class="form-control text-capitalize" id="deskripsi" name="deskripsi" autocomplete="off" placeholder="Masukan Deskripsi Entitas..." value="<?= set_value('deskripsi');  ?>">
                                <?= form_error('deskripsi', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <a href="<?= base_url(); ?>kategori_entitas/data" class="btn btn-danger">Kembali</a>
                            <button type="submit" class="btn btn-primary float-right">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>