<!-- Main Content -->
<div class="main-content">
    <div class="flash-data" data-flashdata="<?= $this->session->flashdata('pesan'); ?>"></div>
    <section class="section">
        <div class="row mt-5">
            <div class="col-lg-12 mt-2">
                <button class="btn btn-lg btn-primary mb-3 font-weight-bold" data-toggle="modal" data-target="#form_tambah">Tambah Divisi</button>
                <table class="table table-bordered table-light shadow-sm p-3 mb-5 bg-white rounded dt-responsive nowrap" id="tabel-khusus" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Divisi</th>
                            <th class="text-center">Penanggung Jawab</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php 
                            $itung = 0;
                            if($divisi):
                                foreach ($divisi as $row) :
                                    $itung = $itung + 1;
                                    if($row->status == 1){
                                        $stat = '<div class="badge badge-sm badge-success">Aktif</div>';
                                    }else if($row->status == 0){
                                        $stat = '<div class="badge badge-sm badge-danger">Nontaktif</div>';
                                    }
                        ?>
                            <tr>
                                <td class="text-center"><?= $itung; ?></td>
                                <td class="text-capitalize"><?= $row->nama; ?></td>
                                <td class="text-center"><?= $row->nama_pjt; ?></td>
                                <td class="text-center"><?= $stat; ?></td>
                                <td class="text-center">
                                    <!-- Button Edit -->
                                    <a class="btn btn-sm btn-success text-light" id="btn-edit" data-toggle="modal" data-target="#modal_edit_<?= $row->id; ?>">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <!-- Button Hapus -->
                                    <a href="<?= base_url(); ?>data_divisi/hapus/<?= $row->id; ?>" class="btn btn-sm btn-danger text-light tombol-hapus"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                        <?php
                                endforeach;
                            endif;
                        ?>
                    </tbody>
                </table>

            </div>
        </div>
    </section>
</div>

<!-- Modal Tambah Divisi -->
<div class="modal fade mt-5" id="form_tambah" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog mt-5">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="color:white">
                <h5 class="modal-title" id="staticBackdropLabel">Tambah Data Divisi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url(); ?>data_divisi/tambah" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama">Nama Divisi</label>
                        <input type="text" class="form-control" id="nama" name="nama" autocomplete="off" placeholder="Masukkan Nama Divisi . . ." />
                    </div>
                    <div class=" form-group">
                        <label for="pjt">Penanggung Jawab</label>
                        <select class="form-control select2" id="pjt" name="pjt">
                            <option>-- PILIH KARYAWAN --</option>
                            <?php foreach ($karyawan as $row) : ?>
                                <option value="<?= $row->id ?>"><?= $row->nama ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <input type="hidden" id="id_gudang" name="id_gudang" value="<?= $id_gudang ?>" />
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
    if($divisi):
    foreach ($divisi as $row) :
        $itung = $itung + 1;
?>
<div class="modal fade mt-5" id="modal_edit_<?= $row->id; ?>" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" role="dialog">
    <div class="modal-dialog mt-5" >
        <div class="modal-content">
            <div class="modal-header bg-success" style="color:white">
                <h5 class="modal-title" id="staticBackdropLabel">Edit Data Divisi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_edit_<?= $row->id; ?>" action="<?= base_url(); ?>data_gudang/update" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_karyawan">Nama Karyawan</label>
                        <input type="text" class="form-control" id="nama" name="nama_edit" autocomplete="off" placeholder="Masukkan Nama Gudang . . ." value="<?= $row->nama; ?>" />
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id_edit" id="id_edit" value="<?= $row->id; ?>" />
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
    endforeach; 
    endif;
?>