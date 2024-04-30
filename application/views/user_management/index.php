<head>
    <style>
        .huruf-besar{
            text-transform:uppercase
        }
        
        td{
            text-transform:uppercase
        }
    </style>
</head>

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="row d-flex justify-content-center mt-5">
            <div class="col-lg-12">
                <div class="flash-data" data-flashdata="<?= $this->session->flashdata('pesan'); ?>"></div>
                <button class="btn btn-lg btn-primary mb-3 font-weight-bold" data-toggle="modal" data-target="#form_tambah">Tambah User</button>
                <table class="table table-bordered table-light shadow-sm p-3 mb-5 bg-white rounded dt-responsive nowrap" id="tabel-produk" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Nama</th>
                            <th class="text-center">Username</th>
                            <th class="text-center">No Hp</th>
                            <th class="text-center">Role</th>
                            <th class="text-center">Entitas</th>
                            <th class="text-center">Kota</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $itung = 0;
                            foreach ($data_user as $row) {
                                $itung = $itung + 1;
                                if($row->status == 1){
                                    $stat = '<div class="badge badge-sm badge-success">Aktif</div>';
                                }else if($row->status == 0){
                                    $stat = '<div class="badge badge-sm badge-danger">Nontaktif</div>';
                                }
                        ?>
                            <tr>
                                <td class="text-center"><?= $itung; ?></td>
                                <td class="text-center"><?= $row->nama; ?></td>
                                <td class="text-center"><?= $row->username; ?></td>
                                <td class="text-center"><?= $row->hp; ?></td>
                                <td class="text-center"><?= $row->nama_role; ?></td>
                                <td class="text-center"><?= $row->entitas; ?></td>
                                <td class="text-center"><?= $row->kota; ?></td>
                                <td class="text-center"><?= $stat; ?></td>
                                <td class="text-center">
                                    <!-- Button Edit -->
                                    <a class="btn btn-sm btn-success text-light" id="btn-edit" data-toggle="modal" data-target="#form_edit_<?= $row->id_username; ?>">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <!-- Button Reset Password -->
                                    <a class="btn btn-sm btn-info text-light" id="btn-respass" data-toggle="modal" data-target="#form_respass_<?= $row->id_username; ?>">
                                        <i class="fas fa-key"></i>
                                    </a>
                                    <!-- Button Hapus -->
                                    <a href="<?= base_url(); ?>user_management/hapus/<?= $row->id_username; ?>" class="btn btn-sm btn-danger text-light tombol-hapus"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

            </div>
        </div>
    </section>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="form_tambah" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="color:white">
                <h5 class="modal-title" id="staticBackdropLabel">Tambah Data User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url(); ?>user_management/tambah" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" autocomplete="off" style="" placeholder="Masukkan Nama Lengkap User . . ." />
                        <?= form_error('nama', '<small class="form-text text-danger pl-3">', '</small>') ?>
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" autocomplete="off" style="text-transform:uppercase" placeholder="Masukkan Username . . ." />
                        <?= form_error('username', '<small class="form-text text-danger pl-3">', '</small>') ?>
                    </div>
                    <div class="form-group">
                        <label for="nohp">No HP</label>
                        <input type="text" class="form-control" id="nohp" name="nohp" autocomplete="off" style="text-transform:uppercase" placeholder="Masukkan Nomor Hp . . ." />
                    </div>
                    <div class="form-group">
                        <label for="role_edit">Role</label>
                        <select class="form-control select2" id="role" name="role">
                            <option value="0" >PILIH ROLE USER</option>
                            <?php 
                                foreach ($list_user_role as $role){
                                    echo '<option value="'.$role['id'].'"> '.$role['deskripsi'].' </option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="stat_edit">Entitas</label>
                        <select class="form-control select2" id="gudang" name="gudang" >
                            <option>Pilih Entitas</option>
                            <?php 
                                foreach ($list_gudang as $gudang){
                                    echo '<option value="'.$gudang['id'].'"> '.$gudang['nama'].' </option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" autocomplete="off" style="text-transform:uppercase" placeholder="Masukkan Password . . ." />
                        <?= form_error('password', '<small class="form-text text-danger pl-3">', '</small>') ?>
                    </div>
                    <div class="form-group">
                        <label for="konfirmpass">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="konfirmpass" name="konfirmpass" autocomplete="off" style="text-transform:uppercase" placeholder="Masukkan Konfirmasi Password . . ." />
                        <?= form_error('konfirmpass', '<small class="form-text text-danger pl-3">', '</small>') ?>
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
    foreach ($data_user as $du) {
        $itung = $itung + 1;
?>
<div class="modal fade" id="form_edit_<?= $du->id_username ?>" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="color:white">
                <h5 class="modal-title" id="staticBackdropLabel">Edit Data User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url(); ?>user_management/update" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control text-center huruf-besar" value="<?= $du->username ?>" readonly />
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" class="form-control huruf-besar" id="nama_edit" name="nama_edit" autocomplete="off" placeholder="Masukkan Nama Lengkap User . . ." value="<?= $du->nama ?>" />
                    </div>
                    <div class="form-group">
                        <label for="nohp_edit">No HP</label>
                        <input type="text" class="form-control huruf-besar" id="nohp_edit" name="nohp_edit" autocomplete="off" placeholder="Masukkan Nomor Hp . . ." value="<?= $du->hp ?>" />
                    </div>
                    <div class="form-group">
                        <label for="role_edit">Role</label>
                        <select class="form-control select2" id="role_edit" name="role_edit">
                            <option value="0" >PILIH ROLE USER</option>
                            <?php 
                                foreach ($list_user_role as $role){
                                    echo '<option value="'.$role['id'].'"> '.$role['deskripsi'].' </option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="stat_edit">Entitas</label>
                        <select class="form-control select2" id="gudang_edit" name="gudang_edit" >
                            <option>Pilih Entitas</option>
                            <?php 
                                foreach ($list_gudang as $gudang){
                                    echo '<option value="'.$gudang['id'].'"> '.$gudang['nama'].' </option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="stat_edit">Status</label>
                        <select class="form-control select2" id="stat_edit" name="stat_edit">
                            <option value="1" <?= $du->status=="1" ? 'selected':'' ?>>AKTIF</option>
                            <option value="0" <?= $du->status=="0" ? 'selected':'' ?>>NONAKTIF</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id_edit" id="id_edit" value="<?= $du->id_username ?>" />
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php } ?>


<!-- Modal Reset Password -->
<?php
    $itung = 0;
    foreach ($data_user as $du) {
        $itung = $itung + 1;
?>
<div class="modal fade" id="form_respass_<?= $du->id_username ?>" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="color:white">
                <h5 class="modal-title" id="staticBackdropLabel">Reset Password User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url(); ?>user_management/update_password" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control text-center huruf-besar" value="<?= $du->username ?>" readonly />
                    </div>
                    <div class="form-group">
                        <label for="new_pass">Password Baru</label>
                        <input type="text" class="form-control" id="new_pass" name="new_pass" autocomplete="off" placeholder="Masukkan Password Baru . . ." />
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id_respass" id="id_respass" value="<?= $du->id_username ?>" />
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Reset</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php } ?>
