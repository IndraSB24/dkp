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
                                <label><strong>Tanggal Faktur</strong></label>
                                <input type="text" class="myinput text-center" id="tgl_faktur" name="tgl_faktur" value="<?= $data_faktur[0]->tgl_faktur ?>" readonly/>
                            </div>
                            <div class="col-lg-6">
                                <label><strong>Nama Karyawan</strong></label>
                                <input type="text" class="myinput text-center" id="nama_karyawan" name="nama_karyawan" value="<?= $data_faktur[0]->nama_karyawan ?>" readonly/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <label><strong>Gudang</strong></label>
                                <input type="text" class="myinput text-center" id="gudang" name="gudang" value="<?= $data_faktur[0]->nama_gudang ?>" readonly/>
                            </div>
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
                            <th class="bg-primary" style="text-align:center; color:white;" > No. </th>
                            <th class="bg-primary" style="text-align:center; color:white;" > Kode </th>
                            <th class="bg-primary" style="text-align:center; color:white;" > Produk </th>
                            <th class="bg-primary" style="text-align:center; color:white;" > Jumlah </th>
                            <th class="bg-primary" style="text-align:center; color:white;" > Satuan </th>
                            <th class="bg-primary" style="text-align:center; color:white;" > Harga Satuan </th>
                            <th class="bg-primary" style="text-align:center; color:white;" > Harga Total </th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php 
                            $itung = 0;
                            foreach ($detail_faktur as $row) :
                                $itung  = $itung + 1;
                        ?>
                            <tr>
                                <td class="text-center"><?= $itung; ?></td>
                                <td class="text-center"><?= $row->kode; ?></td>
                                <td class="text-left"><?= $row->nama_produk; ?></td>
                                <td class="text-center"><?= number_format($row->jumlah, 0, ',', '.'); ?></td>
                                <td class="text-center"><?= $row->satuan ?></td>
                                <td class="text-left">Rp. <?= number_format($row->harga_satuan, 0, ',', '.'); ?>,-</td>
                                <td class="text-left">Rp. <?= number_format($row->harga_satuan * $row->jumlah, 0, ',', '.'); ?>,-</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
        </div>
    </section>
</div>


<!--Notif Tidak ada lampiran -->
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
            <center><h3 class="text-center">Tidak Ada Lampiran</h3></center>
            <br>
        </div>
    </div>
</div>
