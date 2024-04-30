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
            <div class="col-lg-12 mt-5">
                <div class="flash-data" data-flashdata="<?= $this->session->flashdata('pesan'); ?>"></div>

                <table class="table table-bordered table-light shadow-sm p-3 mb-5 bg-white rounded dt-responsive nowrap" id="tabel-produk" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">No Faktur</th>
                            <th class="text-center">Tanggal Mutasi</th>
                            <th class="text-center">Tanggal Bayar</th>
                            <th class="text-center">Total Nilai Mutasi</th>
                            <th class="text-center">Dibayar</th>
                            <th class="text-center">Sisa</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php 
                            $itung = 0;
                            foreach ($detail_hutang as $row) :
                                $itung      = $itung + 1;
                                if($row->tgl_bayar == NULL){
                                    $tgl_bayar = "-";
                                }else{
                                    $tgl_bayar = date('d-m-Y', strtotime($row->tgl_bayar));
                                }
                        ?>
                            <tr>
                                <td class="text-center"><?= $itung; ?></td>
                                <td class="text-center"><?= $row->no_faktur; ?></td>
                                <td class="text-center"><?= date('d-m-Y', strtotime($row->tgl_mutasi)); ?></td>
                                <td class="text-center"><?= $tgl_bayar; ?></td>
                                <td class="text-center">Rp. <?= number_format($row->nilai_mutasi, 2, ',', '.'); ?></td>
                                <td class="text-center">Rp. <?= number_format($row->dibayar, 2, ',', '.'); ?></td>
                                <td class="text-center">Rp. <?= number_format($row->nilai_mutasi - $row->dibayar, 2, ',', '.'); ?></td>
                                <td class="text-center">
                                    <!-- Button Edit -->
                                    <a class="btn btn-sm btn-success text-light" id="btn-edit" data-toggle="modal" data-target="#modal_bayar_<?= $row->id; ?>">
                                        <i class="fas fa-edit">Bayar</i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
        </div>
    </section>
</div>


<!-- Modal Tambah Pembayaran Hutang -->
<?php
    $itung = 0;
    foreach ($detail_hutang as $row) :
        $itung = $itung + 1;
?>
<div class="modal fade " id="modal_bayar_<?= $row->id; ?>" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="color:white">
                <h5 class="modal-title" id="staticBackdropLabel">Tambah Data Pembayaran Piutang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url(); ?>daftar_piutang/bayar/piutang/mutasi" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="est_tgl_bayar">Tanggal Mutasi</label>
                        <input type="text" class="form-control" id="est_tgl_bayar" name="est_tgl_bayar" value="<?= date('d-m-Y', strtotime($row->tgl_mutasi)); ?>" readonly />
                    </div>
                    <div class="form-group">
                        <label for="sisa">Sisa Piutang</label>
                        <input type="text" class="form-control" id="sisa" name="sisa" value="Rp. <?= number_format($row->nilai_mutasi - $row->dibayar, 2, ',', '.'); ?>" readonly />
                    </div>
                    <div class="form-group">
                        <label for="nominal">Nominal Bayar</label>
                        <input type="number" class="form-control" id="nominal" name="nominal" autocomplete="off" placeholder="MASUKKAN NOMINAL BAYAR . . ." />
                    </div>
                    <div class="form-group">
                        <label for="tanggal_bayar">Pilih Tanggal Bayar</label>
                        <input type="date" class="form-control datepicker" id="tanggal_bayar" name="tanggal_bayar" autocomplete="off" placeholder="PILIH TANGGAL" >
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id_vendor" id="id_vendor" value="<?= $row->id_ke_gudang ?>" />
                    <input type="hidden" name="no_faktur" id="no_faktur" value="<?= $row->no_faktur ?>" />
                    <input type="hidden" name="id_faktur" id="id_faktur" value="<?= $row->id ?>" />
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Tambah Pembayaran</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

//Notif kebanyakan bayar
<div class="modal fade mt-5" id="modal_kelebihan_bayar" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" role="dialog">
    <div class="modal-dialog mt-5" >
        <div class="modal-content">
            <div class="modal-header bg-danger" style="color:white">
                <center class="text-center"><i class="fa fa-exclamation"></i>Notifikasi</center>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <br>
            <center><h3 class="text-center"><i class="fa fa-exclamation"></i>  NOMINAL YANG ANDA KETIK MELEBIHI SISA HUTANG</h3></center>
            <br>
        </div>
    </div>
</div>


