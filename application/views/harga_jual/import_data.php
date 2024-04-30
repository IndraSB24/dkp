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
                        <div class="row">
                            <div class="col-md-12">
                                <label>Template Import</label>
                            </div>
                            <div class="col-md-8">
                                <a href="<?= base_url('harga_jual/export_template') ?>" class="btn btn-lg btn-success float-left" style="width:100%">
                                    Download Template Import
                                </a>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-lg btn-info mb-4 font-weight-bold" style="width:100%" data-toggle="modal" data-target="#modal_info_import">
                                    Klik Untuk Panduan
                                </button>
                            </div>
                        </div>
                        
                        <form method="POST" action="<?= base_url('harga_jual/import_harga_jual') ?>" enctype="multipart/form-data">
                            <div class=" form-row">
                                <div class=" form-group col-md-12">
                                    <label>Upload File</label>
                                    <?php
                                        $data_penjualan = false;
                                        if($data_penjualan){
                                            echo '
                                                <input class="form-control" type="text" name="notif" value="Reset Data Untuk Menambah Data Baru" readonly />
                                                <br>
                                                <a href="'.base_url('penjualan/hapus/data_penjualan/1').'" class="btn btn-primary float-left">Reset</a>
                                            ';
                                        }else{
                                            echo '
                                                <div class="input-group">
                                                    <label class="btn btn-lg btn-primary" style="box-shadow: none; border-radius: 0;">
                                                        <i class="fas fa-file"></i> Pilih File Untuk Diimport
                                                        <input class="form-control" type="file" id="fileExcel" name="fileExcel" style="display: none">
                                                    </label>
                                                    <input class="form-control" type="text" id="fileLabel" style="border-radius: 0;" readonly />
                                                </div>
                                                <br>
                                                <button type="submit" class="btn btn-success float-left"><i class="fas fa-plus"> Simpan</i></button>
                                            ';
                                        }
                                    ?>

                                </div>
                            </div>
                            <br>
                            <a href="<?= base_url('harga_jual/harga_jual') ?>" class="btn btn-danger float-left">Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Info Import -->
<div class="modal fade mt-5" id="modal_info_import" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog mt-5">
        <div class="modal-content">
        <div class="modal-header bg-primary" style="color:white">
            <h5 class="modal-title" id="staticBackdropLabel">Cara Import Data Harga Jual</h5>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const fileExcel = document.getElementById('fileExcel');
    const fileLabel = document.getElementById('fileLabel');
    
    fileExcel.addEventListener('change', function() {
      const file = this.files[0];
      if (file) {
        fileLabel.value = file.name;
      } else {
        fileLabel.value = '';
      }
    });
</script>
