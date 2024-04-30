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
<div class="main-content">
    <section class="section">
        <form action="<?= base_url(); ?>/mutasi/tambah/penerimaan/<?= $data_faktur[0]->id; ?>" method="POST" >
        <div class="row d-flex justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <br>
                        <div class="row">
                            <div class="col-lg-12">
                                <center><h2><?= $data_faktur[0]->no_faktur; ?></h2></center>
                                <input type="hidden" id="id_faktur" name="id_faktur" value="<?= $data_faktur[0]->id; ?>"/>
                                <input type="hidden" id="no_faktur" name="no_faktur" value="<?= $data_faktur[0]->no_faktur; ?>"/>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-6">
                                <label class="font-weight-bold">Dari Gudang</label>
                                <div class="myinput text-center"><?= $data_faktur[0]->dari_gudang; ?></div>
                                <input type="hidden" id="dari_gudang" name="dari_gudang" value="<?= $data_faktur[0]->id_dari_gudang ?>"/>
                            </div>
                            <div class="col-lg-6">
                                <label class="font-weight-bold">Ke Gudang</label>
                                <div class="myinput text-center"><?= $data_faktur[0]->ke_gudang; ?></div>
                                <input type="hidden" id="ke_gudang" name="ke_gudang" value="<?= $data_faktur[0]->id_ke_gudang ?>"/>
                            </div>
                        </div>
                        
                        <?php if($data_faktur[0]->jenis_mutasi == "normal"): ?>
                        <div class="row">
                            <div class="col-lg-6">
                                <label class="font-weight-bold">Tanggal Diminta</label>
                                <div class="myinput text-center"><?= date('d-m-Y', strtotime($data_faktur[0]->tgl_faktur)); ?>&nbsp;PUKUL&nbsp;<?= date('H:i:s', strtotime($data_faktur[0]->tgl_faktur)); ?></div>
                            </div>
                            <div class="col-lg-6">
                                <label class="font-weight-bold">Diminta Oleh</label>
                                <div class="myinput text-center"><?= $data_faktur[0]->nama_karyawan; ?></div>
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
                        <?php endif; ?>
                        
                        <div class="row">
                            <div class="col-lg-6">
                                <label class="font-weight-bold">Tanggal Dimutasi</label>
                                <div class="myinput text-center"><?= date('d-m-Y', strtotime($data_faktur[0]->tgl_mutasi)); ?>&nbsp;PUKUL&nbsp;<?= date('H:i:s', strtotime($data_faktur[0]->tgl_mutasi)); ?></div>
                            </div>
                            <div class="col-lg-6">
                                <label class="font-weight-bold">Dimutasi Oleh</label>
                                <div class="myinput text-center"><?= $data_faktur[0]->nama_disetujui_oleh; ?></div>
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
                        
                        <?php if($data_faktur[0]->jenis_mutasi == "langsung"): ?>
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
                        <?php endif; ?>
                        
                        <?php if($data_faktur[0]->jenis_mutasi == "normal"): ?>
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="penerima" class="font-weight-bold">Diterima Oleh</label>
                                <select class="form-control select2" id="penerima" name="penerima">
                                    <option value="0" selected>PILIH NAMA KARYAWAN</option>
                                    <?php foreach ($list_karyawan as $karyawan) : ?>
                                        <option value="<?= $karyawan['nama'] ?>"><?= $karyawan['nama'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label><strong>Tambah Catatan Penerimaan Mutasi</strong></label>
                                <textarea rows="4" class="myinput text-left" id="catatan_penerimaan" name="catatan_penerimaan"></textarea>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="row d-flex justify-content-center">
            <div class="col-lg-12">
                <div class="flash-data" data-flashdata="<?= $this->session->flashdata('pesan'); ?>"></div>
                <table class="table table-bordered table-light shadow-sm p-3 mb-5 bg-white rounded dt-responsive nowrap" id="tabel-all-row" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Nama Produk</th>
                            <th class="text-center">Satuan Dasar</th>
                            <th class="text-center">Dimutasi</th>
                            <?php if($data_faktur[0]->jenis_mutasi == "normal"): ?>
                                <th class="text-center">Diterima (editable)</th>
                            <?php endif; ?>
                            <th class="text-center">Harga Jual</th>
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
                                <?php if($data_faktur[0]->jenis_mutasi == "normal"): ?>
                                    <td class="text-center bg-editable">
                                        <input class="detailInput text-center" type="number" id="jumlah_terima_<?=$itung;?>" name="jumlah_terima_<?=$itung;?>" value="<?= $row->jumlah ?>">
                                    </td>
                                <?php endif; ?>
                                <td class="text-center">
                                    Rp. <?= number_format($row->harga, 2, ',', '.'); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
            if(substr(hak_akses(),0,3)=="SPV"){
                $lokasi = substr(hak_akses(),4);
            }else if(substr(hak_akses(),0,3)=="ADM"){
                $lokasi = substr(hak_akses(),6);
            }
            
            if( (isAdmin() || $lokasi==$data_faktur[0]->ke_gudang) && $data_faktur[0]->jenis_mutasi=="normal" ): 
        ?>
            <div class="row d-flex justify-content-center pt-3">
                <div class="col-lg-12 text-right">
                    <button type="submit" class="btn btn-lg btn-primary mb-3 font-weight-bold">Setujui Penerimaan Mutasi</button>
                </div>
            </div>
        <?php endif; ?>
        </form>
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
