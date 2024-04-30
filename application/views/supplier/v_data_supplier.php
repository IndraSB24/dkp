<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="row mt-5">
            <div class="col-lg-12 mt-2">
                <div class="flash-data" data-flashdata="<?= $this->session->flashdata('pesan'); ?>"></div>
                <button class="btn btn-lg btn-primary mb-3 font-weight-bold" data-toggle="modal" data-target="#form_tambah">Tambah Supplier</button>
                <table class="table table-bordered table-light shadow-sm p-3 mb-5 bg-white rounded dt-responsive nowrap" id="tabel-produk" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class=" text-center">Nama</th>
                            <th class=" text-center">Email</th>
                            <th class="text-center">No. Hp</th>
                            <th class="text-center">No. Rekening</th>
                            <th class="text-center">Alamat</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $itung = 0;
                            foreach($suplier as $row):
                                $itung = $itung + 1;
                                if($row['status'] == 1){
                                    $stat = '<div class="badge badge-sm badge-success">Aktif</div>';
                                }else if($row['status'] == 0){
                                    $stat = '<div class="badge badge-sm badge-danger">Nontaktif</div>';
                                }
                        ?>
                            <tr>
                                <td class="text-center"><?= $itung; ?></td>
                                <td class="text-capitalize"><?= $row['nama']; ?></td>
                                <td class="text-center"><?= $row['email']; ?></td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-warning text-light" id="btn-edit" data-toggle="modal" data-target="#modal_detail_nope_<?= $row['id']; ?>">
                                        <i class="fas fa-eye"> Lihat</i>
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-warning text-light" id="btn-edit" data-toggle="modal" data-target="#modal_detail_norek_<?= $row['id']; ?>">
                                        <i class="fas fa-eye"> Lihat</i>
                                    </a>
                                </td>
                                <td class="text-center"><?= $row['alamat']; ?></td>
                                <td class="text-center"><?= $stat; ?></td>
                                <td class="text-center">
                                    <!-- Button Edit -->
                                    <a class="btn btn-sm btn-success text-light" id="btn-edit" data-toggle="modal" data-target="#modal_edit_<?= $row['id']; ?>">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <!-- Button Hapus -->
                                    <a href="<?= base_url(); ?>data_supplier/hapus/<?= $row['id']; ?>" class="btn btn-sm btn-danger text-light tombol-hapus"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<!-- Modal Tambah Supplier -->
<div class="modal fade mt-2" id="form_tambah" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog mt-5">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="color:white">
                <h5 class="modal-title" id="staticBackdropLabel">Tambah Data Supplier</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url(); ?>data_supplier/tambah/supplier" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_satuan">Nama Supplier</label>
                        <input type="text" class="form-control" id="nama" name="nama" autocomplete="off" placeholder="Masukkan Nama Supplier . . ." />
                    </div>
                    <div class="form-group">
                        <label for="deskripsi_satuan">Email</label>
                        <input type="text" class="form-control" id="email" name="email" autocomplete="off" placeholder="Masukkan Email . . ." />
                    </div>
                    <div class="form-group">
                        <label for="deskripsi_satuan">Alamat</label>
                        <input type="text" class="form-control" id="alamat" name="alamat" autocomplete="off" placeholder="Masukkan Alamat . . ." />
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

<!-- Modal Edit Supplier -->
<?php
    $itung = 0;
    foreach ($suplier as $row) :
        $itung = $itung + 1;
?>
<div class="modal fade mt-2" id="modal_edit_<?= $row['id']; ?>" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" role="dialog">
    <div class="modal-dialog mt-5" >
        <div class="modal-content">
            <div class="modal-header bg-success" style="color:white">
                <h5 class="modal-title" id="staticBackdropLabel">Edit Data Supplier</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_edit_<?= $row['id']; ?>" action="<?= base_url(); ?>data_supplier/update" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_satuan">Nama Karyawan</label>
                        <input type="text" class="form-control" id="nama" name="nama_edit" autocomplete="off" placeholder="Masukkan Nama Karyawan . . ." value="<?= $row['nama']; ?>" />
                    </div>
                    <div class="form-group">
                        <label for="deskripsi_satuan">Email</label>
                        <input type="text" class="form-control" id="email" name="email_edit" autocomplete="off" placeholder="Masukkan Email . . ." value="<?= $row['email']; ?>" />
                    </div>
                    <div class="form-group">
                        <label for="deskripsi_satuan">Alamat</label>
                        <input type="text" class="form-control" id="alamat" name="alamat_edit" autocomplete="off" placeholder="Masukkan Alamat . . ." value="<?= $row['alamat']; ?>" />
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

<!-- Modal Edit Nope -->
<?php
    $itung = 0;
    foreach ($suplier as $row) :
?>
<div class="modal fade mt-2" id="modal_detail_nope_<?= $row['id']; ?>" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" role="dialog">
    <div class="modal-dialog mt-5">
        <div class="modal-content">
            <div class="modal-header bg-success" style="color:white">
                <h5 class="modal-title" id="staticBackdropLabel">Edit Data Nomor Hp</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_detail_<?= $row['id']; ?>" action="<?= base_url(); ?>data_supplier/tambah/nope" method="POST">
                <div class="modal-body">
                    <?php
                        if($detail_nope[$row['id']]){
                            foreach($detail_nope[$row['id']] as $detail): 
                    ?>
                                <div class="form-group mb-1">
                                    <label for="nama_kontak_edit">Nama Kontak</label>
                                    <input type="text" class="form-control" id="nama_kontak_edit[<?= $detail->id ?>]" name="nama_kontak_edit[<?= $detail->id ?>]" value="<?= $detail->deskripsi ?>" />
                                </div>
                                <div class="form-group">
                                    <label for="nope_edit">Nomor Telepon</label>
                                    <input type="text" class="form-control" id="nope_edit[<?= $detail->id ?>]" name="nope_edit[<?= $detail->id ?>]" value="<?= $detail->detail ?>" />
                                </div>
                    <?php 
                            endforeach;
                    ?>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-success">Edit</button>
                            </div>
                    <?php
                        }else{
                            echo '<center><h3>Tidak Ada Data!!</h3></center>';
                        }
                    ?>
                    <div class="form-group text-center">
                        <h4>Tambah Data Kontak</h4>
                    </div>
                    <div class="form-group mb-1">
                        <label for="nama_kontak">Nama Kontak</label>
                        <input type="text" class="form-control" id="nama_kontak" name="nama_kontak" placeholder="Masukkan Nama Kontak" />
                    </div>
                    <div class="form-group">
                        <label for="nope">Nomor Telepon</label>
                        <input type="text" class="form-control" id="nope" name="nope" placeholder="Masukkan Nomor Telepon" />
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id_supplier" id="id_supplier" value="<?= $row['id']; ?>" />
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
        $itung = $itung + 1;
    endforeach; 
?>

<!-- Modal Edit Norek -->
<?php
    $itung = 0;
    foreach ($suplier as $row) :
?>
<div class="modal fade mt-2" id="modal_detail_norek_<?= $row['id']; ?>" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" role="dialog">
    <div class="modal-dialog mt-5">
        <div class="modal-content">
            <div class="modal-header bg-success" style="color:white">
                <h5 class="modal-title" id="staticBackdropLabel">Edit Data Nomor Rekening</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_detail_<?= $row['id']; ?>" action="<?= base_url(); ?>data_supplier/tambah/norek" method="POST">
                <div class="modal-body">
                    <?php
                        if($detail_norek[$row['id']]){
                            foreach($detail_norek[$row['id']] as $detail): 
                    ?>
                                <div class="form-group mb-1">
                                    <label for="bank_an_edit">BANK - Atas Nama</label>
                                    <input type="text" class="form-control" id="bank_an_edit[<?= $detail->id ?>]" name="bank_an_edit[<?= $detail->id ?>]" value="<?= $detail->deskripsi ?>" />
                                </div>
                                <div class="form-group">
                                    <label for="norek_edit">Nomor Rekening</label>
                                    <input type="text" class="form-control" id="norek_edit[<?= $detail->id ?>]" name="norek_edit[<?= $detail->id ?>]" value="<?= $detail->detail ?>" />
                                </div>
                    <?php
                            endforeach;
                    ?>
                            <div class="form-group text-right">
                                <a href="<?= base_url().'data_supplier/update/norek' ?>" class="btn btn-success">Edit</a>
                            </div>
                    <?php
                        }else{
                            echo '<center><h3>Tidak Ada Data!!</h3></center>';
                        }
                    ?>
                    <div class="form-group text-center">
                        <h4>Tambah Data Rekening</h4>
                    </div>
                    <div class="form-group mb-1">
                        <label for="bank_an">BANK - Atas Nama</label>
                        <input type="text" class="form-control" id="bank_an" name="bank_an" placeholder="Masukkan Bank - Atas Nama" />
                    </div>
                    <div class="form-group">
                        <label for="norek">Nomor Rekening</label>
                        <input type="text" class="form-control" id="norek" name="norek" placeholder="Masukkan Nomor Rekening" />
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id_supplier" id="id_supplier" value="<?= $row['id']; ?>" />
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
        $itung = $itung + 1;
    endforeach; 
?>