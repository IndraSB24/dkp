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
            margin-bottom:20px;
		}
		
		.detailInput{
			width:100%;
			height:auto;
			border:0px solid #000;
			border-radius:2px; 
			margin-left:0px;
		}
    </style>
</header>
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <form action="<?= base_url(); ?>/pembelian/update" method="POST" >
        <div class="row d-flex justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <br>
                        <div class="row">
                            <div class="col-lg-12">
                                <center><h2><?= $data_faktur[0]->no_faktur; ?></h2></center>
                                <input type="hidden" id="id_faktur" name="id_faktur" value="<?= $data_faktur[0]->id; ?>"/>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-6">
                                <?php
                                    if($data_faktur[0]->no_invoice == ""){
                                        $invoice = "Tidak Ada Invoice";
                                    }else{
                                        $invoice = $data_faktur[0]->no_invoice;
                                    }
                                ?>
                                <label><strong>No Invoice</strong></label>
                                <input type="text" class="myinput text-center" id="no_invoice" name="no_invoice" value="<?= $invoice ?>" readonly/>
                            </div>
                            <div class="col-lg-6">
                                <label><strong>Tanggal Faktur</strong></label>
                                <input type="text" class="myinput text-center" id="tgl_faktur" name="tgl_faktur" value="<?= $data_faktur[0]->tanggal_faktur ?>" readonly/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <label><strong>Supplier</strong></label>
                                <input type="text" class="myinput text-center" id="supplier" name="supplier" value="<?= $data_faktur[0]->nama_supplier ?>" readonly/>
                            </div>
                            <div class="col-lg-6">
                                <label><strong>Ke Gudang</strong></label>
                                <input type="text" class="myinput text-center" id="gudang" name="gudang" value="<?= $data_faktur[0]->nama_gudang ?>" readonly/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <label><strong>Jenis Pembayaran</strong></label>
                                <input type="text" class="myinput text-center" id="pembayaran" name="pembayaran" value="<?= $data_faktur[0]->jenis_bayar ?>" readonly/>
                            </div>
                            <div class="col-lg-6">
                                <label><strong>Karyawan</strong></label>
                                <input type="text" class="myinput text-center" id="karyawan" name="karyawan" value="<?= $data_faktur[0]->nama_karyawan ?>" readonly/>
                            </div>
                        </div>
                        <div class="row">
                            <?php if(isAdmin()){ ?>
                                <div class="col-md-4">
                                    <label for="tgl_bayar" class="font-weight-bold">Tanggal Bayar</label>
                                    <input type="date" class="form-control" id="tgl_bayar" name="tgl_bayar" autocomplete="off" value="<?= $data_faktur[0]->tanggal_bayar ?>">
                                    <input type="hidden" name="id_faktur" value="<?= $data_faktur[0]->id ?>" />
                                </div>
                                <div class="col-md-2">
                                    <label for="btn_update" style="color:white"> i </label>
                                    <input type="submit" class="form-control btn btn-lg btn-info mb-3 font-weight-bold" name="btn_update" value="Update" />
                                </div>
                            <?php }else{ ?>
                                <div class="col-lg-4">
                                    <label><strong>Tanggal Bayar</strong></label>
                                    <input type="text" class="myinput text-center" id="tgl_bayar" name="tgl_bayar" value="<?= $data_faktur[0]->tanggal_bayar ?>" readonly/>
                                </div>
                            <?php } ?>
                            <div class="col-lg-6">
                                <label><strong>Lampiran</strong></label></br>
                                <?php if($data_faktur[0]->lampiran == NULL){ ?>
                                    <span>
                                        <a class="btn btn-lg btn-primary mb-3 font-weight-bold" id="btn-edit" data-toggle="modal" data-target="#modal_no_lampiran">
                                            Tidak Ada Lampiran
                                        </a>
                                    </span>
                                <?php }else if($data_faktur[0]->lampiran != NULL){ ?>
                                    </span><a href="<?= $data_faktur[0]->lampiran?>" class="btn btn-lg btn-primary mb-3 font-weight-bold" >Cek Lampiran</a></span>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="row d-flex justify-content-center">
            <div class="col-lg-12">
                <div class="flash-data" data-flashdata="<?= $this->session->flashdata('pesan'); ?>"></div>

                <table class="table table-bordered table-light shadow-sm p-3 mb-5 bg-white rounded dt-responsive nowrap" id="tabel-produk" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Kode Transaksi</th>
                            <th class="text-center">Nama Produk</th>
                            <th class="text-center">Satuan Dasar</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-center">Harga Satuan</th>
                            <th class="text-center">Total Harga</th>
                            
                        </tr>
                    </thead>

                    <tbody>
                        <?php 
                            $itung = 0;
                            foreach ($detail_faktur as $row) :
                                $itung      = $itung + 1;
                                $jumlah_beli= $row['jumlah_beli'];
                                $harga_beli = $row['harga_beli'];
                                $harga_satuan= $row['harga_satuan'];
                        ?>
                            <tr>
                                <td class="text-center"><?= $itung; ?></td>
                                <td class="text-center"><?= $row['kode']; ?></td>
                                <td class="text-center"><?= $row['nama_produk']; ?></td>
                                <td class="text-center"><?= $row['satuan_dasar'] ?></td>
                                <td class="text-center"><?= number_format($row['jumlah_beli'], 0, ',', '.'); ?></td>
                                <td class="text-left">Rp. <?= number_format($harga_satuan, 2, ',', '.') ?>&nbsp;</td>
                                <td class="text-left">Rp. <?= number_format($harga_beli, 2, ',', '.') ?>&nbsp;</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
        </div>
        </form>
    </section>
</div>

//Notif Tidak ada lampiran
<div class="modal fade mt-5" id="modal_no_lampiran" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" role="dialog">
    <div class="modal-dialog mt-5" >
        <div class="modal-content">
            <div class="modal-header bg-danger" style="color:white">
                <center class="text-center"><i class="fa fa-exclamation"></i>Notifikasi</center>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <br>
            <center><h3 class="text-center"><i class="fa fa-exclamation"></i> Tidak Ada Lampiran</h3></center>
            <br>
        </div>
    </div>
</div>
