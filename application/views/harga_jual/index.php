<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-12">
                <div class="flash-data" data-flashdata="<?= $this->session->flashdata('pesan'); ?>"></div>
                <div class="card text-center">
                    <br>
                    <h3> LIST HARGA JUAL PRODUK TAHUN <?= date('Y'); ?> </h3>
                    <br>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <button class="btn btn-lg btn-primary mb-3 font-weight-bold" data-toggle="modal" data-target="#form_tambah">Tambah Harga Jual</button>
                <table class="table table-bordered table-light shadow-sm p-3 mb-5 bg-white rounded dt-responsive nowrap" id="tabel-produk" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Kode Produk</th>
                            <th class="text-center">Nama Produk</th>
                            <th class="text-center">Satuan</th>
                            <th class="text-center">Harga Jual</th>
                            <th class="text-center">Bulan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                       <?php 
                            $itung = 0;
                            foreach ($list_produk as $row) :
                                $itung = $itung + 1;
                        ?>
                            <tr>
                                <td class="text-center"><?= $itung; ?></td>
                                <td class="text-center"><?= $row['kode']; ?></td>
                                <td class="text-left"><?= $row['nama']; ?></td>
                                <td class="text-center"><?= $row['satuan_dasar']; ?></td>
                                <td class="text-center">
                                    <?php
                                        $ambil_harga_jual = $this->Model_harga_jual->get_by_idProduk_bulan($row['id'], date('m'));
                                        if($ambil_harga_jual){
                                            $harga_jual = $ambil_harga_jual[0]->harga_jual;
                                        }else{
                                            $harga_jual = 0;
                                        }
                                    ?>
                                    Rp. <?= number_format($harga_jual, 2, ',', '.'); ?>
                                </td>
                                <td class="text-center" style="text-transform:uppercase"><?= bulan_indo(date('m')); ?></td>
                                <td class="text-center">
                                    <?php if($ambil_harga_jual == FALSE){ ?>
                                        <!-- Button add -->
                                        <a class="btn btn-sm btn-danger text-light" id="btn-add" data-toggle="modal" data-target="#modal_add_<?= $row['id']; ?>">
                                            <i class="fas fa-plus"> Tambah</i>
                                        </a>
                                    <?php }else{ ?>
                                        <!-- Button Edit -->
                                        <a class="btn btn-sm btn-success text-light font-weight-bold" id="btn-edit" data-toggle="modal" data-target="#modal_edit_<?= $row['id']; ?>">
                                            <i class="fas fa-edit"> Edit</i>
                                        </a>
                                    <?php } ?>
                                    
                                    <a class="btn btn-sm btn-info text-light font-weight-bold" id="btn-detail" href="<?= base_url(); ?>harga_jual/show/detail/<?= $row['id']; ?>">
                                        <i class="fas fa-info"> Detail</i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </section>
</div>


<!-- Modal Tambah -->
<div class="modal fade mt-5" id="form_tambah" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog mt-5">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="color:white">
                <h5 class="modal-title" id="staticBackdropLabel">Tambah Harga Jual</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url(); ?>harga_jual/tambah" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="id_produk">Nama Produk</label>
                        <select class="form-control" id="id_produk" name="id_produk">
                            <option value="0" selected>PILIH PRODUK</option>
                            <?php
                                foreach($list_produk as $lp){
                                    echo '<option value="'.$lp['id'].'">'.$lp['nama'].'</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tanggal">Untuk Bulan</label>
                        <select class="form-control" style="text-transform:uppercase" id="tanggal" name="tanggal">
                            <option value="0" selected>PILIH BULAN</option>
                            <?php
                                $count = 0;
                                foreach(all_bulan() as $bulan => $nama_bulan){
                                    $count += 1;
                                    echo '<option value="'.date('Y').'-'.$count.'-01">'.$nama_bulan.'</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="harga_jual">Harga Jual</label>
                        <input type="number" class="form-control" id="harga_jual" name="harga_jual" autocomplete="off" placeholder="Masukkan Nominal Harga Jual . . ." />
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


<!-- Modal add -->
<?php
    $itung = 0;
    foreach ($list_produk as $lpd) :
        $itung = $itung + 1;
?>
<div class="modal fade mt-5" id="modal_add_<?= $lpd['id']; ?>" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog mt-5">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="color:white">
                <h5 class="modal-title" id="staticBackdropLabel">Tambah Harga Jual</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url(); ?>harga_jual/tambah" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <br>
                        <div class="form-group text-center">
                            <h3><?= $lpd['nama'] ?><h3>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tanggal">Untuk Bulan</label>
                        <select class="form-control" style="text-transform:uppercase" id="tanggal" name="tanggal">
                            <option value="0" selected>PILIH BULAN</option>
                            <?php
                                $count = 0;
                                foreach(all_bulan() as $bulan => $nama_bulan){
                                    $count += 1;
                                    echo '<option value="'.date('Y').'-'.$count.'-01">'.$nama_bulan.'</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="harga_jual">Harga Jual</label>
                        <input type="number" class="form-control" id="harga_jual" name="harga_jual" autocomplete="off" placeholder="Masukkan Nominal Harga Jual . . ." />
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id_produk_row" id="id_produk_row" value="<?= $lpd['id']; ?>" />
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>


<!-- Modal Edit -->
<?php
    $itung = 0;
    foreach ($list_produk as $lpd) :
        $itung = $itung + 1;
?>
<div class="modal fade mt-5" id="modal_edit_<?= $lpd['id']; ?>" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog mt-5">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="color:white">
                <h5 class="modal-title" id="staticBackdropLabel">Ubah Harga Jual</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url(); ?>harga_jual/update" method="POST">
                <div class="modal-body">
                    <br>
                    <div class="form-group text-center">
                        <h3><?= $lpd['nama'] ?><h3>
                    </div>
                    <div class="form-group">
                        <label for="harga_jual">Harga Jual</label>
                        <?php
                            $ambil_harga_jual = $this->Model_harga_jual->get_by_idProduk_bulan($lpd['id'], date('m'));
                            if($ambil_harga_jual){
                                $harga_jual = $ambil_harga_jual[0]->harga_jual;
                            }else{
                                $harga_jual = 0;
                            }
                        ?>
                        <input type="number" class="form-control" id="harga_jual" name="harga_jual" autocomplete="off" value="<?= $harga_jual ?>" placeholder="Masukkan Nominal Harga Jual . . ." />
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id_edit" id="id_edit" value="<?= $lpd['id']; ?>" />
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>