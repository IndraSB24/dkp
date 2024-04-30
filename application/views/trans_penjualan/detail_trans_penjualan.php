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
			text-align:center;
		}
		
		input{
		    text-transform: uppercase;
		}
    </style>
</header>

<?php
    //data sistem
    $data_cash = $this->Model_transaksi_penjualan->get_total_diskon($data_faktur[0]->id, "CASH");
    if($data_cash){
        $total_cash = $data_cash[0]->sub_total;
        $diskon_cash= $data_cash[0]->total_diskon;
    }else{
        $total_cash = 0;
        $diskon_cash= 0;
    }
                            
    $data_sfood = $this->Model_transaksi_penjualan->get_total_diskon($data_faktur[0]->id, "Shopee Food");
    if($data_sfood){
        $total_sfood = $data_sfood[0]->sub_total;
        $diskon_sfood= $data_sfood[0]->total_diskon;
    }else{
        $total_sfood = 0;
        $diskon_sfood= 0;
    }
                                    
    $data_gofood = $this->Model_transaksi_penjualan->get_total_diskon($data_faktur[0]->id, "Saldo Gojek");
    if($data_gofood){
        $total_gofood = $data_gofood[0]->sub_total;
        $diskon_gofood= $data_gofood[0]->total_diskon;
    }else{
        $total_gofood = 0;
        $diskon_gofood= 0;
    }
                                        
    $data_grfood = $this->Model_transaksi_penjualan->get_total_diskon($data_faktur[0]->id, "Saldo Grab");
    if($data_grfood){
        $total_grfood = $data_grfood[0]->sub_total;
        $diskon_grfood= $data_grfood[0]->total_diskon;
    }else{
        $total_grfood = 0;
        $diskon_grfood= 0;
    }
    
    $total_omset= $total_cash + $total_sfood + $total_gofood + $total_grfood;
    $diskon_all = $diskon_cash + $diskon_sfood + $diskon_gofood + $diskon_grfood;
    $omset      = $total_omset - $diskon_all;
    
    
    //data manual
    if($data_manual){
        $manual_omset           = 'Rp. '.number_format($data_manual[0]->omset, 2, ',', '.');
        $manual_cash            = 'Rp. '.number_format($data_manual[0]->cash, 2, ',', '.');
        $manual_diskon_cash     = 'Rp. '.number_format($data_manual[0]->diskon_cash, 2, ',', '.');
        $manual_sfood           = 'Rp. '.number_format($data_manual[0]->sfood, 2, ',', '.');
        $manual_diskon_sfood    = 'Rp. '.number_format($data_manual[0]->diskon_sfood, 2, ',', '.');
        $manual_gofood          = 'Rp. '.number_format($data_manual[0]->gofood, 2, ',', '.');
        $manual_diskon_gofood   = 'Rp. '.number_format($data_manual[0]->diskon_gofood, 2, ',', '.');
        $manual_grfood          = 'Rp. '.number_format($data_manual[0]->grfood, 2, ',', '.');
        $manual_diskon_grfood   = 'Rp. '.number_format($data_manual[0]->diskon_grfood, 2, ',', '.');
        $total_omset_manual     = $data_manual[0]->cash + $data_manual[0]->sfood + $data_manual[0]->gofood + $data_manual[0]->grfood;
        $total_diskon_manual    = $data_manual[0]->diskon_cash + $data_manual[0]->diskon_sfood + $data_manual[0]->diskon_gofood + $data_manual[0]->diskon_grfood;
        $total_all_manual       = $total_omset_manual - $total_diskon_manual;
        $selisih_total_omset    = 'Rp. '.number_format($total_omset_manual - $total_omset, 2, ',', '.');
        $selisih_total_all      = 'Rp. '.number_format($total_all_manual - $omset, 2, ',', '.');
        $selisih_omset          = 'Rp. '.number_format($data_manual[0]->omset - $omset, 2, ',', '.');
        $selisih_cash           = 'Rp. '.number_format($data_manual[0]->cash - $total_cash, 2, ',', '.');
        $selisih_diskon_cash    = 'Rp. '.number_format($data_manual[0]->diskon_cash - $diskon_cash, 2, ',', '.');
        $selisih_sfood          = 'Rp. '.number_format($data_manual[0]->sfood - $total_sfood, 2, ',', '.');
        $selisih_diskon_sfood   = 'Rp. '.number_format($data_manual[0]->diskon_sfood - $diskon_sfood, 2, ',', '.');
        $selisih_gofood         = 'Rp. '.number_format($data_manual[0]->gofood - $total_gofood, 2, ',', '.');
        $selisih_diskon_gofood  = 'Rp. '.number_format($data_manual[0]->diskon_gofood - $diskon_gofood, 2, ',', '.');
        $selisih_grfood         = 'Rp. '.number_format($data_manual[0]->grfood - $total_grfood, 2, ',', '.');
        $selisih_diskon_grfood  = 'Rp. '.number_format($data_manual[0]->diskon_grfood - $diskon_grfood, 2, ',', '.');
        $stat_input             = "readonly";
        
    }else{
        $manual_omset           = 0;
        $manual_cash            = 0;
        $manual_diskon_cash     = 0;
        $manual_sfood           = 0;
        $manual_diskon_sfood    = 0;
        $manual_gofood          = 0;
        $manual_diskon_gofood   = 0;
        $manual_grfood          = 0;
        $manual_diskon_grfood   = 0;
        $total_omset_manual     = 0;
        $total_all_manual       = 0;
        $selisih_total_omset    = "Belum Diketahui";
        $selisih_total_all      = "Belum Diketahui";
        $selisih_omset          = 0;
        $selisih_cash           = 0;
        $selisih_diskon_cash    = 0;
        $selisih_sfood          = 0;
        $selisih_diskon_sfood   = 0;
        $selisih_gofood         = 0;
        $selisih_diskon_gofood  = 0;
        $selisih_grfood         = 0;
        $selisih_diskon_grfood  = 0;
        $stat_input             = "";
    }
?>

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <form action="<?= base_url(); ?>penjualan/tambah/manual" method="POST">
            <input type="hidden" name="id_faktur" value="<?= $data_faktur[0]->id ?>" />
        <div class="row d-flex justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
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
                                    <a href="<?= base_url(); ?>penjualan/show/detail_data_penjualan/<?= $data_faktur[0]->id ?>" class="btn btn-info">
                                        <i class="fas fa-info"> Lihat Data Product Sales Stock</i>
                                    </a>
                                </div>
                            </div>
                            <div class=" form-group col-md-6">
                                <label>Data Diskon</label>
                                <div>
                                    <a href="<?= base_url(); ?>penjualan/show/detail_data_diskon/<?= $data_faktur[0]->id ?>" class="btn btn-info">
                                        <i class="fas fa-info"> Lihat Data Product Sales Detail</i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class=" form-group col-md-4">
                                    <label for="gudang">Total Omset Sistem</label>
                                    <input type="text" class="form-control text-center" id="gudang" name="gudang" value="Rp. <?= number_format($total_omset, 2, ',', '.') ?>" readonly />
                            </div>
                            <div class=" form-group col-md-4">
                                    <label for="gudang">Total Omset Manual</label>
                                    <input type="text" class="form-control text-center" id="gudang" name="gudang" value="Rp. <?= number_format($total_omset_manual, 2, ',', '.') ?>" readonly />
                            </div>
                            <div class=" form-group col-md-4">
                                    <label for="gudang">Selisih Omset</label>
                                    <input type="text" class="form-control text-center" id="gudang" name="gudang" value="<?= $selisih_total_omset ?>" readonly />
                            </div>
                        </div>
                        <div class="row">
                            <div class=" form-group col-md-4">
                                    <label for="gudang">Total All Sistem</label>
                                    <input type="text" class="form-control text-center" id="gudang" name="gudang" value="Rp. <?= number_format($omset, 2, ',', '.') ?>" readonly />
                            </div>
                            <div class=" form-group col-md-4">
                                    <label for="gudang">Total All Manual</label>
                                    <input type="text" class="form-control text-center" id="gudang" name="gudang" value="Rp. <?= number_format($total_all_manual, 2, ',', '.') ?>" readonly />
                            </div>
                            <div class=" form-group col-md-4">
                                    <label for="gudang">Selisih Total All</label>
                                    <input type="text" class="form-control text-center" id="gudang" name="gudang" value="<?= $selisih_total_all ?>" readonly />
                            </div>
                        </div>
                        <br>
                        <div class="form-row">
                            <div class=" form-group col-md-12">
                            <table class="table-bordered shadow-sm mb-5 bg-white" id="tabel-detail" width="100%">
                                <thead>
                                    <tr class="bg-primary text-white">
                                        <th class="text-center"> Kategori </th>
                                        <th class="text-center text-white p-2"> Sistem </th>
                                        <th class="text-center text-white p-2"> Manual </th>
                                        <th class="text-center text-white p-2"> Selisih </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th class="text-center text-white bg-primary p-2">Omset</th>
                                        <td class="text-center"> Rp. <?= number_format($omset, 2, ',', '.') ?> </td>
                                        <td class="text-center"><input class="detailInput" type="text" name="in_omset" value="<?= $manual_omset ?>" <?= $stat_input ?>/></td>
                                        <td class="text-center"> <?= $selisih_omset ?> </td>
                                    </tr>
                                    <tr>    
                                        <th class="text-center text-white bg-primary p-2">Diskon Cash</th>
                                        <td class="text-center"> Rp. <?= number_format($diskon_cash, 2, ',', '.') ?> </td>
                                        <td class="text-center"><input class="detailInput" type="text" name="in_diskon_cash" value="<?= $manual_diskon_cash ?>" <?= $stat_input ?>/></td>
                                        <td class="text-center"> <?= $selisih_diskon_cash ?> </td>
                                    </tr>
                                    <tr>
                                        <th class="text-center text-white bg-primary p-2">Cash</th>
                                        <td class="text-center"> Rp. <?= number_format($total_cash, 2, ',', '.') ?> </td>
                                        <td class="text-center"><input class="detailInput" type="text" name="in_cash" value="<?= $manual_cash ?>" <?= $stat_input ?>/></td>
                                        <td class="text-center"> <?= $selisih_cash ?> </td>
                                    </tr>
                                    <tr>
                                        <th class="text-center text-white bg-primary p-2">Diskon Gojek</th>
                                        <td class="text-center"> Rp. <?= number_format($diskon_gofood, 2, ',', '.') ?> </td>
                                        <td class="text-center"><input class="detailInput" type="text" name="in_diskon_gojek" value="<?= $manual_diskon_gofood ?>" <?= $stat_input ?>/></td>
                                        <td class="text-center"> <?= $selisih_diskon_gofood ?> </td>
                                    </tr>
                                    <tr>
                                        <th class="text-center text-white bg-primary p-2">Gojek</th>
                                        <td class="text-center"> Rp. <?= number_format($total_gofood, 2, ',', '.') ?> </td>
                                        <td class="text-center"><input class="detailInput" type="text" name="in_gojek" value="<?= $manual_gofood ?>" <?= $stat_input ?>/></td>
                                        <td class="text-center"> <?= $selisih_gofood ?> </td>
                                    </tr>
                                    <tr>
                                        <th class="text-center text-white bg-primary p-2">Diskon Grab</th>
                                        <td class="text-center"> Rp. <?= number_format($diskon_grfood, 2, ',', '.') ?> </td>
                                        <td class="text-center"><input class="detailInput" type="text" name="in_diskon_grab" value="<?= $manual_diskon_grfood ?>" <?= $stat_input ?>/></td>
                                        <td class="text-center"> <?= $selisih_diskon_grfood ?> </td>
                                    </tr>
                                    <tr>
                                        <th class="text-center text-white bg-primary p-2">Grab</th>
                                        <td class="text-center"> Rp. <?= number_format($total_grfood, 2, ',', '.') ?> </td>
                                        <td class="text-center"><input class="detailInput" type="text" name="in_grab" value="<?= $manual_grfood ?>" <?= $stat_input ?>/></td>
                                        <td class="text-center"> <?= $selisih_grfood ?> </td>
                                    </tr>
                                    <tr>
                                        <th class="text-center text-white bg-primary p-2">Diskon Shopee</th>
                                        <td class="text-center"> Rp. <?= number_format($diskon_sfood, 2, ',', '.') ?> </td>
                                        <td class="text-center"><input class="detailInput" type="text" name="in_diskon_shopee" value="<?= $manual_diskon_sfood ?>" <?= $stat_input ?>/></td>
                                        <td class="text-center"> <?= $selisih_diskon_sfood ?> </td>
                                    </tr>
                                    <tr>
                                        <th class="text-center text-white bg-primary p-2">Shopee</th>
                                        <td class="text-center"> Rp. <?= number_format($total_sfood, 2, ',', '.') ?> </td>
                                        <td class="text-center"><input class="detailInput" type="text" name="in_shopee" value="<?= $manual_diskon_sfood ?>" <?= $stat_input ?>/></td>
                                        <td class="text-center"> <?= $selisih_sfood ?> </td>
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                        </div>
                        <br>
                        <a href="<?= base_url(); ?>penjualan/penjualan" class="btn btn-danger">Kembali</a>
                        <?php
                            if(!$data_manual){
                        ?>
                            <button type="submit" class="btn btn-primary float-right">Submit Data Manual</button>
                        <?php }else{ ?>
                            <a href="<?= base_url(); ?>penjualan/hapus/data_manual/<?= $data_faktur[0]->id ?>" class="btn btn-warning float-right">Reset Data Manual</a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </section>
</div>


<script>
    var baris = 0;

</script>
