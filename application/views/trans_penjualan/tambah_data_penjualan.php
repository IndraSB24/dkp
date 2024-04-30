<header class="page-header">
    <style>
        .myinput{
			width:100%;
			height:auto;
			border:3px solid #000;
			border-radius:8px; 
			-moz-border-radius:8px;
			margin-left:0px;
			padding:8px 10px;
            line-height:100%;
		}
		
		.detailInput{
			width:100%;
			height:auto;
			border:0px solid #000;
			border-radius:2px; 
			margin-left:0px;
		}
		
		input{
		    text-transform: uppercase;
		}
    </style>
</header>

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="flash-data" data-flashdata="<?= $this->session->flashdata('pesan'); ?>"></div>
        <div class="row d-flex justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="<?= base_url(); ?>penjualan/import_excel" enctype="multipart/form-data">
                            <div class=" form-row">
                                <div class="form-group col-md-12">
                                    <label for="no_faktur">No Faktur</label>
                                    <input type="text" class="form-control text-center" id="no_faktur" name="no_faktur"  value="<?= $data_faktur[0]->kode_faktur; ?>" readonly />
                                </div>
                            </div>
                            <div class=" form-row">
                                <div class=" form-group col-md-12">
                                    <label for="tanggal_faktur">Pilih File</label>
                                    <?php
                                        if($data_penjualan){
                                            echo '
                                                <input class="form-control" type="text" name="notif" value="Reset Data Untuk Menambah Data Baru" readonly />
                                                <br>
                                                <a href="'. base_url() .'penjualan/hapus/data_penjualan/'. $data_faktur[0]->id .'" class="btn btn-primary float-left">Reset</a>
                                            ';
                                        }else{
                                            echo '
                                                <input class="form-control" type="file" id="fileExcel" name="fileExcel" />
                                                <br>
                                                <button type="submit" class="btn btn-success float-left"><i class="fas fa-plus"> Simpan</i></button>
                                            ';
                                        }
                                    ?>
                                </div>
                            </div>
                            <br>
                            <input type="hidden" id="id_faktur" name="id_faktur"  value="<?= $data_faktur[0]->id; ?>" />
                            <a href="<?= base_url(); ?>penjualan/show/tambah_data/<?= $data_faktur[0]->id ?>" class="btn btn-danger float-left">Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row d-flex justify-content-center">
            <div class="col-lg-10 mt-2">
                <table class="table table-bordered table-light shadow-sm p-3 mb-5 bg-white rounded dt-responsive nowrap" id="tabel-produk" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Produk</th>
                            <th class="text-center">Kelompok Produk</th>
                            <th class="text-center">Jumlah</th></th>
                            <th class="text-center">Harga/pcs</th>
                            <th class="text-center">Total Penjualan</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php 
                            $itung = 0;
                            foreach ($data_penjualan as $row) :
                                $itung = $itung + 1;
                        ?>
                            <tr>
                                <td class="text-center"><?= $itung; ?></td>
                                <td class="text-center"><?= $row->nama_produk; ?></td>
                                <td class="text-center"><?= $row->kelompok_produk; ?></td>
                                <td class="text-center"><?= number_format($row->jumlah, 0, ',', '.') ?></td>
                                <td class="text-left">Rp. <?= number_format($row->harga, 2, ',', '.') ?></td>
                                <td class="text-left">Rp. <?= number_format($row->jumlah * $row->harga, 2, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </section>
</div>


<script>
    var baris = 0;

</script>
