<header class="page-header">
    <style>
        .mylabel {
			border:0px solid blue;
			display: table-cell;
			width: 100%;
		}
		
		td{
		    text-transform:uppercase
		}
    </style>
</header>

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="row mt-5">
            <div class="col-lg-12 mt-2">
                <div class="flash-data" data-flashdata="<?= $this->session->flashdata('pesan'); ?>"></div>
                <button class="btn btn-lg btn-primary mb-3 font-weight-bold" data-toggle="modal" data-target="#form_tambah">Tambah Produk</button>
                <table class="table table-bordered table-light shadow-sm p-3 mb-10 bg-white rounded dt-responsive nowrap" id="tabel-produk" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th class="text-center">Kode</th>
                            <th class="text-center">Kelompok Produk</th>
                            <th class="text-center">Nama</th>
                            <th class="text-center">Satuan Dasar</th>
                            <th class="text-center">Harga Satuan</th>
                            <th class="text-center">Stock in</th>
                            <th class="text-center">Stock out</th>
                            <th class="text-center">Stock Total</th>
                            <th class="text-center">Nilai Persediaan</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $itung = 0;
                            foreach ($produk as $row) :
                                $itung = $itung + 1;
                        ?>
                            <tr>
                                <td class="text-center"><?= $itung; ?></td>
                                <td class="text-center"><?= $row['kode']; ?></td>
                                <td class="text-center"><?= $row['kelompok_produk']; ?></td>
                                <td class="text-center"><?= $row['nama']; ?></td>
                                <td class="text-center"><?= $row['satuan_dasar']; ?></td>
                                <td class="text-left">Rp. <?= number_format($row['harga_satuan'], 2, ',', '.') ?>&nbsp;</td>
                                <td class="text-center"><?= number_format($row['stok_in'], 0, ',', '.'); ?></td>
                                <td class="text-center"><?= number_format($row['stok_out'], 0, ',', '.'); ?></td>
                                <td class="text-center"><?= number_format(($row['stok_in'] - $row['stok_out']), 0, ',', '.'); ?></td>
                                <td class="text-left">Rp. <?= number_format((($row['stok_in'] - $row['stok_out']) * $row['harga_satuan']), 2, ',', '.'); ?>&nbsp;</td>
                                <td class="text-center">
                                    <!-- Button Edit -->
                                    <a class="btn btn-sm btn-success text-light" id="btn-edit" data-toggle="modal" data-target="#modal_edit_<?= $row['id']; ?>">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <!-- Button Hapus -->
                                    <a href="<?= base_url(); ?>produk/hapus/<?= $row['id']; ?>/<?= $row['id_kelompok_produk']; ?>" class="btn btn-sm btn-danger text-light tombol-hapus"><i class="fas fa-trash-alt"></i></a>
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
<div class="modal fade" id="form_tambah" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="color:white">
                <h5 class="modal-title" id="staticBackdropLabel">Tambah Data Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url(); ?>produk/tambah" method="POST">
                <div class="modal-body">
                    <div class=" form-group">
                        <label for="kelompok">Kelompok Produk</label>
                        <input type="hidden" id="val_kelompok" name="val_kelompok" />
                        <input type="hidden" id="terpakai_kelompok" name="terpakai_kelompok" />
                        <select class="form-control select2" id="kelompok" name="kelompok">
                            <option disabled selected>Pilih Kelompok Produk</option>
                            <?php foreach ($kelompok_produk as $kel) : ?>
                                <option value="<?= $kel['id']."PengHubunG".$kel['kode']."PengHubunG".$kel['terpakai'] ?>"><?= $kel['nama'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?= form_error('kelompok', '<small class="form-text text-danger">', '</small>'); ?>
                    </div>
                    <div class="form-group">
                        <label for="kode">Kode Produk</label>
                        <input type="text" class="form-control" id="kode" name="kode" autocomplete="off" style="text-transform:uppercase" placeholder="Masukkan Kode Produk . . ." />
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama Produk</label>
                        <input type="text" class="form-control" id="nama" name="nama" autocomplete="off" style="text-transform:uppercase" placeholder="Masukkan Nama Produk . . ." />
                    </div>
                    <div class=" form-group">
                        <label for="satuan_dasar">Satuan Dasar</label>
                        <select class="form-control select2" id="satuan_dasar" name="satuan_dasar">
                            <option disabled selected>Pilih Satuan Dasar</option>
                            <?php foreach ($satuan_dasar as $sd) : ?>
                                <option value="<?= $sd['id'] ?>"><?= $sd['nama_satuan'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?= form_error('satuan_dasar', '<small class="form-text text-danger">', '</small>'); ?>
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
    foreach ($produk as $row) :
        $itung = $itung + 1;
?>
<div class="modal fade" id="modal_edit_<?= $row['id']; ?>" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" role="dialog">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header bg-success" style="color:white">
                <h5 class="modal-title" id="staticBackdropLabel">Edit Data Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_edit_<?= $row['id']; ?>" action="<?= base_url(); ?>produk/update" method="POST">
                <div class="modal-body">
                    <div class=" form-group">
                        <label for="kelompok_edit">Kelompok Produk</label>
                        <input type="hidden" id="kelompok_edit_awal" name="kelompok_edit_awal" value="<?= $row['id_kelompok_produk']; ?>" />
                        <select class="form-control select2" id="kelompok_edit" name="kelompok_edit">
                            <option disabled selected>Pilih Kelompok Produk</option>
                            <?php foreach ($kelompok_produk as $kel) : ?>
                                <option value="<?= $kel['id'] ?>"
                                    <?php 
                                        if($kel['id'] == $row['id_kelompok_produk']){
                                            echo " SELECTED";
                                        } 
                                    ?>
                                >
                                    <?= $kel['nama'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?= form_error('kelompok_edit', '<small class="form-text text-danger">', '</small>'); ?>
                    </div>
                    <div class="form-group">
                        <label for="kode_edit">Kode Produk</label>
                        <input type="text" class="form-control" id="kode_edit" name="kode_edit" autocomplete="off" placeholder="Masukkan Kode Produk . . ." value="<?= $row['kode']; ?>"/>
                    </div>
                    <div class="form-group">
                        <label for="nama_edit">Nama Produk</label>
                        <input type="text" class="form-control" id="nama_edit" name="nama_edit" autocomplete="off" style="text-transform:uppercase" placeholder="Masukkan Nama Produk . . ." value="<?= $row['nama']; ?>" />
                    </div>
                    <div class=" form-group">
                        <label for="satuan_dasar_edit">Satuan Dasar</label>
                        <select class="form-control select2" id="satuan_dasar_edit" name="satuan_dasar_edit">
                            <option disabled selected>Pilih Satuan Dasar</option>
                            <?php foreach ($satuan_dasar as $sd) : ?>
                                <option value="<?= $sd['id'] ?>"
                                    <?php 
                                        if($sd['id'] == $row['id_satuan_dasar']){
                                            echo " SELECTED";
                                        } 
                                    ?>
                                >
                                    <?= $sd['nama_satuan'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?= form_error('satuan_dasar_edit', '<small class="form-text text-danger">', '</small>'); ?>
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



<script>
	document.addEventListener('DOMContentLoaded', function() {
	
		Array.prototype.forEach.call(document.getElementsByName('kelompok'),
		function (elem) {
			elem.addEventListener('change', function() {
				let text	= this.value;
				const isi 	= text.split("PengHubunG");
				let id		= isi[0],
					kode 	= isi[1],
					terpakai= isi[2];
				var	urut    = parseInt(terpakai)+1;
				
				document.getElementById('kode').value = kode+"-"+padLeadingZeros(urut, 4);
				document.getElementById('val_kelompok').value = id;
				document.getElementById('terpakai_kelompok').value = urut;
			});
		});
	})
	
	function padLeadingZeros(num, size) {
        var s = num+"";
        while (s.length < size) s = "0" + s;
        return s;
    }

</script>
</script>