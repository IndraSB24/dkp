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
			background:transparent;
			border: 0px solid black;
			border-radius:2px;
			margin-left:0px;
		}
		
		.huruf-besar{
		    text-transform: uppercase;
		}
		
		.bg-editable{
		    padding:0;
		    background-color:rgb(120, 120, 120, 0.2);
		}
    </style>
</header>
<!-- Main Content -->
<div class="flash-data" data-flashdata="<?= $this->session->flashdata('pesan'); ?>"></div>
<div class="main-content">
    <section class="section">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <br>
                        <div class=" form-row">
                            <div class="form-group col-md-12">
                                <label for="no_faktur">No Faktur</label>
                                <input type="text" class="form-control text-center" id="no_faktur" name="no_faktur" value="<?= $data_faktur[0]->no_faktur; ?>" readonly>
                            </div>
                        </div>
                        <div class=" form-row">
                            <div class=" form-group col-md-6">
                                <label for="tanggal_faktur">Tanggal Faktur</label>
                                <input type="text" class="form-control text-center" id="tanggal_faktur" name="tanggal_faktur" value="<?= tgl_indo($data_faktur[0]->tgl_faktur) ?>" readonly />
                            </div>
                            <div class=" form-group col-md-6">
                                <label for="gudang">Gudang</label>
                                <input type="text" class="form-control text-center" id="gudang" name="gudang" value="<?= $data_faktur[0]->gudang; ?>" readonly />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <label class="font-weight-bold">Dari Divisi</label>
                                <div class="myinput text-center"><?= $data_faktur[0]->dari_divisi; ?></div>
                            </div>
                            <div class="col-lg-6">
                                <label class="font-weight-bold">Ke Divisi</label>
                                <div class="myinput text-center"><?= $data_faktur[0]->ke_divisi; ?></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
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
                        <div class="row">
                            <div class="col-lg-12">
                                <?php
                                    if($data_faktur[0]->catatan_mutasi == NULL){
                                        $catatan_mutasi = "tidak ada catatan";
                                    }else{
                                        $catatan_mutasi = $data_faktur[0]->catatan_mutasi;
                                    }
                                ?>
                                <label class="font-weight-bold">Catatan Mutasi</label>
                                <div class="myinput text-left huruf-besar"><?= nl2br($catatan_mutasi); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="row d-flex justify-content-center">
            <div class="col-lg-12">
                <table class="table table-bordered table-light shadow-sm p-3 mb-5 bg-white rounded dt-responsive nowrap" id="tabel-khusus" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Nama Produk</th>
                            <th class="text-center">Satuan Dasar</th>
                            <th class="text-center">Jumlah Mutasi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php 
                            $itung = 0;
                            foreach ($detail_mutasi as $row) :
                                $itung  = $itung + 1;
                        ?>
                            <tr>
                                <td class="text-center"><input type="hidden" name="itung" id="itung" value="<?= $itung; ?>" /><?= $itung; ?></td>
                                <td class="text-center">
                                    <input type="hidden" id="id_produk_<?=$itung;?>" name="id_produk_<?=$itung;?>" value="<?= $row->id_produk ?>">
                                    <?= $row->nama_produk; ?>
                                </td>
                                <td class="text-center"><?= $row->satuan_dasar ?></td>
                                <td class="text-center">
                                    <input type="hidden" id="jumlah_kirim_<?=$itung;?>" name="jumlah_kirim_<?=$itung;?>" value="<?= $row->jumlah ?>">
                                    <?= number_format($row->jumlah, 0, ',', '.'); ?>
                                </td>
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
