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
        <div class="row d-flex justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body">
                        <form method="POST">
                            <div class=" form-row">
                                <div class="form-group col-md-6">
                                    <label for="no_faktur">No Faktur</label>
                                    <input type="text" class="form-control text-center" id="no_faktur" name="no_faktur"  value="<?= $data_faktur[0]->kode_faktur; ?>" readonly />
                                </div>
                                <div class=" form-group col-md-6">
                                    <label for="gudang">Pada Outlet</label>
                                    <input type="text" class="form-control text-center" id="gudang" name="gudang" value="<?= $data_faktur[0]->nama_outlet; ?>" readonly />
                                </div>
                            </div>
                            <div class=" form-row">
                                <div class=" form-group col-md-6">
                                    <label for="tanggal_faktur">Tanggal Faktur</label>
                                    <input type="text" class="form-control text-center" id="tanggal_faktur" name="tanggal_faktur" value="<?= $data_faktur[0]->tgl_faktur ?>"  readonly />
                                </div>
                                <div class=" form-group col-md-6">
                                    <label for="karyawan">Nama karyawan</label>
                                    <input type="text" class="form-control text-center" id="karyawan" name="karyawan" value="<?= $data_faktur[0]->nama_penanggung_jawab; ?>" readonly />
                                </div>
                            </div>
                            <div class=" form-row">
                                <div class=" form-group col-md-6">
                                    <label>Data Penjualan</label>
                                    <div>
                                        <a href="<?= base_url(); ?>penjualan/show/tambah_data_penjualan/<?= $data_faktur[0]->id ?>" class="btn btn-info">
                                            <i class="fas fa-plus"> Tambah Data Product Sales Stock</i>
                                        </a>
                                    </div>
                                </div>
                                <div class=" form-group col-md-6">
                                    <label>Data Diskon</label>
                                    <div>
                                        <a href="<?= base_url(); ?>penjualan/show/tambah_data_diskon/<?= $data_faktur[0]->id ?>" class="btn btn-info">
                                            <i class="fas fa-plus"> Tambah Data Product Sales Detail</i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <a href="<?= base_url(); ?>penjualan/penjualan" class="btn btn-danger">Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<script>
    var baris = 0;

</script>
