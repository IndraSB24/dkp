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
                        <form method="POST" action="<?= base_url(); ?>penjualan/import_excel_diskon" enctype="multipart/form-data">
                            <div class=" form-row">
                                <div class="form-group col-md-12">
                                    <label for="no_faktur">No Faktur</label>
                                    <input type="text" class="form-control text-center" id="no_faktur" name="no_faktur"  value="<?= $data_faktur[0]->kode_faktur; ?>" readonly />
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12 mb-0">
                                    <label>Template Import</label>
                                </div>
                                <div class="col-md-8">
                                    <a href="<?= base_url('uploads/template_import/template_penjualan_rincian.xlsx') ?>" class="btn btn-lg btn-success float-left" 
                                        style="width:100%" download
                                    >
                                        Download Template Import
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a class="btn btn-lg btn-info mb-4 font-weight-bold" style="width:100%" data-toggle="modal" data-target="#modal_info_import">
                                        Klik Untuk Panduan
                                    </a>
                                </div>
                            </div>
                            <div class=" form-row">
                                <div class=" form-group col-md-12">
                                    <label for="tanggal_faktur">Pilih File</label>
                                    <?php
                                        if($data_diskon){
                                            echo '
                                                <input class="form-control" type="text" name="notif" value="Reset Data Untuk Menambah Data Baru" readonly />
                                                <br>
                                                <a href="'. base_url() .'penjualan/hapus/data_diskon/'. $data_faktur[0]->id .'" class="btn btn-primary float-left">Reset</a>
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
                            <th class="text-center">Order No</th>
                            <th class="text-center">Customer</th>
                            <th class="text-center">Payment Mode</th>
                            <th class="text-center">Subtotal</th></th>
                            <th class="text-center">Diskon</th>
                            <th class="text-center">Total Bayar</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php 
                            $itung = 0;
                            foreach ($data_diskon as $row) :
                                $itung = $itung + 1;
                        ?>
                            <tr>
                                <td class="text-center"><?= $itung; ?></td>
                                <td class="text-center"><?= $row->order_no; ?></td>
                                <td class="text-center"><?= $row->customer; ?></td>
                                <td class="text-center"><?= $row->payment_mode; ?></td>
                                <td class="text-left">Rp. <?= number_format($row->subtotal, 2, ',', '.') ?></td>
                                <td class="text-left">Rp. <?= number_format($row->diskon, 2, ',', '.') ?></td>
                                <td class="text-left">Rp. <?= number_format($row->subtotal - $row->diskon, 2, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </section>
</div>

<!-- Modal Info Import -->
<div class="modal fade mt-5" id="modal_info_import" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog mt-5">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="color:white">
                <h5 class="modal-title" id="staticBackdropLabel">Cara Import Data Penjualan Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <br>
                Disini Isi
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Mengerti</button>
            </div>
        </div>
    </div>
</div>


<script>
    var baris = 0;

</script>
