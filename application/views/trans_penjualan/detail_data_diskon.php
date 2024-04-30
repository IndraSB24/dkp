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
                            <div class=" form-row">
                                <div class="form-group col-md-12">
                                    <label for="no_faktur">No Faktur</label>
                                    <input type="text" class="form-control text-center" id="no_faktur" name="no_faktur"  value="<?= $data_faktur[0]->kode_faktur; ?>" readonly />
                                </div>
                            </div>
                            <br>
                            <a href="<?= base_url(); ?>penjualan/show/detail/<?= $data_faktur[0]->id ?>" class="btn btn-danger float-left">Kembali</a>
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


<script>
    var baris = 0;

</script>
