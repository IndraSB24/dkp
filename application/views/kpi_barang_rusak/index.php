<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-12">
                <div class="flash-data" data-flashdata="<?= $this->session->flashdata('pesan'); ?>"></div>
                <div class="card text-center">
                    <br>
                    <h3> LIST KPI BARANG RUSAK TAHUN <?= date('Y'); ?> </h3>
                    <br>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <button class="btn btn-lg btn-primary mb-3 font-weight-bold" data-toggle="modal" data-target="#form_tambah">Tambah KPI</button>
                <table class="table table-bordered table-light shadow-sm p-3 mb-5 bg-white rounded dt-responsive nowrap" id="tabel-produk" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Kode Produk</th>
                            <th class="text-center">Nama Produk</th>
                            <th class="text-center">Nilai Maksimum</th>
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
                                <td class="text-center">
                                    <?php
                                        $ambil_data = $this->Model_kpi_barang_rusak->get_by_idProduk_bulan($row['id'], date('m'));
                                        if($ambil_data){
                                            $nilai_maksimum = $ambil_data[0]->nilai_maksimum;
                                        }else{
                                            $nilai_maksimum = 0;
                                        }
                                    ?>
                                    Rp. <?= number_format($nilai_maksimum, 2, ',', '.'); ?>
                                </td>
                                <td class="text-center" style="text-transform:uppercase"><?= bulan_indo(date('m')); ?></td>
                                <td class="text-center">
                                    <?php if($ambil_data == FALSE){ ?>
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
                                    
                                    <a class="btn btn-sm btn-info text-light font-weight-bold" id="btn-detail" href="<?= base_url(); ?>kpi_barang_rusak/show/detail/<?= $row['id']; ?>">
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
                <h5 class="modal-title" id="staticBackdropLabel">Tambah KPI Barang Rusak</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url(); ?>kpi_barang_rusak/tambah" method="POST">
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
                        <label for="nilai_maksimum">Nilai Maksimum</label>
                        <input type="number" class="form-control" id="nilai_maksimum" name="nilai_maksimum" autocomplete="off" placeholder="Masukkan Nilai Maksimum . . ." />
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
                <h5 class="modal-title" id="staticBackdropLabel">Tambah Nilai Maksimum</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url(); ?>kpi_barang_rusak/tambah" method="POST">
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
                        <label for="nilai_maksimum">Nilai Maksimum</label>
                        <input type="number" class="form-control" id="nilai_maksimum" name="nilai_maksimum" autocomplete="off" placeholder="Masukkan Nilai Maksimum . . ." />
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
            <form action="<?= base_url(); ?>kpi_barang_rusak/update" method="POST">
                <div class="modal-body">
                    <br>
                    <div class="form-group text-center">
                        <h3><?= $lpd['nama'] ?><h3>
                    </div>
                    <div class="form-group">
                        <label for="nilai_maksimum">Nilai Maksimum</label>
                        <?php
                            $ambil_data = $this->Model_kpi_barang_rusak->get_by_idProduk_bulan($lpd['id'], date('m'));
                            if($ambil_data){
                                $nilai_maksimum = $ambil_data[0]->nilai_maksimum;
                            }else{
                                $nilai_maksimum = 0;
                            }
                        ?>
                        <input type="number" class="form-control" id="nilai_maksimum" name="nilai_maksimum" autocomplete="off" value="<?= $nilai_maksimum ?>" placeholder="Masukkan Nilai Maksimum . . ." />
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