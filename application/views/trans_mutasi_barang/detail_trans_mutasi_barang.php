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
		
		textarea, input{
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
    <div class="flash-data" data-flashdata="<?= $this->session->flashdata('pesan'); ?>"></div>
    <section class="section">
        <form action="<?= base_url(); ?>/mutasi/tambah/mutasi/<?= $data_faktur[0]->id; ?>" method="POST" >
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
                                <label><strong>Dari Gudang</strong></label>
                                <input type="text" class="myinput text-center" id="dar_gud" name="dar_gud" value="<?= $data_faktur[0]->dari_gudang ?>" readonly/>
                                <input type="hidden" id="dari_gudang" name="dari_gudang" value="<?= $data_faktur[0]->id_dari_gudang ?>"/>
                            </div>
                            <div class="col-lg-6">
                                <label><strong>Ke Gudang</strong></label>
                                <input type="text" class="myinput text-center" id="ke_gud" name="ke_gud" value="<?= $data_faktur[0]->ke_gudang ?>" readonly/>
                                <input type="hidden" id="ke_gudang" name="ke_gudang" value="<?= $data_faktur[0]->id_ke_gudang ?>"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <label><strong>Tanggal Diminta</strong></label>
                                <input type="text" class="myinput text-center" id="tanggal_faktur" name="tanggal_faktur" value="<?= date('d-m-Y', strtotime($data_faktur[0]->tgl_faktur)); ?>" readonly/>
                            </div>
                            <div class="col-lg-6">
                                <label><strong>Diminta Oleh</strong></label>
                                <input type="text" class="myinput text-center" id="nama_karyawan" name="nama_karyawan" value="<?= $data_faktur[0]->nama_karyawan ?>" readonly/>
                            </div>
                        </div>
                        <div class="row">
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
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="pemutasi" class="font-weight-bold">Dimutasi Oleh</label>
                                <select class="form-control select2" id="pemutasi" name="pemutasi">
                                    <option value="0" selected>PILIH NAMA KARYAWAN</option>
                                    <?php foreach ($list_karyawan as $karyawan) : ?>
                                        <option value="<?= $karyawan['nama'] ?>"><?= $karyawan['nama'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label><strong>Tambah Catatan Mutasi</strong></label>
                                <textarea rows="4" class="myinput text-left" id="catatan" name="catatan"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="row d-flex justify-content-center">
            <div class="col-lg-12">
                <table class="table table-bordered table-light shadow-sm p-3 mb-5 bg-white rounded dt-responsive nowrap" id="tabel-all-row" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Nama Produk</th>
                            <th class="text-center">Satuan Dasar</th>
                            <th class="text-center">Jumlah (editable)</th>
                            <th class="text-center">Harga Mutasi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php 
                            $itung = 0;
                            foreach ($detail_permintaan as $row) :
                                $itung  = (int)$itung + 1;
                                if($data_faktur[0]->id_dari_gudang == '1' && $data_faktur[0]->id_ke_gudang == '2'){
                                    $harga_mutasi = 0;
                                }else{
                                    $data_harga = $this->Model_harga_jual->get_by_idProduk_bulan($row->id_produk, date("m"));
                                    if($data_harga){
                                        $harga_mutasi = $data_harga[0]->harga_jual;
                                    }else{
                                        $harga_mutasi = 0;
                                    }
                                }
                        ?>
                            <tr>
                                <td class="text-center"><input type="hidden" name="itung" id="itung" value="<?= $itung; ?>" /><?= $itung; ?></td>
                                <td class="text-center">
                                    <input type="hidden" id="id_produk_<?=$itung;?>" name="id_produk_<?=$itung;?>" value="<?= $row->id_produk ?>">
                                    <?= $row->nama_produk; ?>
                                </td>
                                <td class="text-center"><?= $row->satuan_dasar ?></td>
                                <td class="text-center bg-editable">
                                    <input class="detailInput text-center" type="number" id="jumlah_<?=$itung;?>" name="jumlah_<?=$itung;?>" value="<?= $row->jumlah ?>">
                                </td>
                                <td class="text-center">
                                    <input type="hidden" id="harga_<?=$itung;?>" name="harga_<?=$itung;?>" value="<?= $harga_mutasi ?>">
                                    Rp. <?= number_format($harga_mutasi, 2, ',', '.'); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <br>
        <?php
            if(substr(hak_akses(),0,3)=="SPV"){
                $lokasi = substr(hak_akses(),4);
            }else if(substr(hak_akses(),0,3)=="ADM"){
                $lokasi = substr(hak_akses(),6);
            }
            
            if( isAdmin() || $lokasi==$data_faktur[0]->dari_gudang ): 
        ?>
        <div class="row d-flex justify-content-center">
            <div class="col-lg-12 text-right">
                <button type="submit" class="btn btn-lg btn-primary mb-3 font-weight-bold">Setujui Permintaan Mutasi</button>
            </div>
        </div>
        <?php endif; ?>
        </form>
    </section>
</div>


<!-- Modal Edit Harga -->
<?php
    foreach($detail_permintaan as $dp) :
?>
<div class="modal fade mt-5" id="modal_edit_<?= $dp->id_produk; ?>" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog mt-5">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="color:white">
                <h5 class="modal-title" id="staticBackdropLabel">Edit Jumlah Mutasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <br>
                        <div class="form-group text-center">
                            <h3><?= $dp->nama_produk ?><h3>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="jumlah">Harga Jual</label>
                        <input type="number" class="form-control" id="jumlah" name="jumlah" autocomplete="off" placeholder="Masukkan Jumlah Mutasi . . ." value="<?= $dp->jumlah ?>" />
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id_produk_row" id="id_produk_row" value="<?= $dp->id_produk ?>" />
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

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
