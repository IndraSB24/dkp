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
    <div class="flash-data" data-flashdata="<?= $this->session->flashdata('pesan'); ?>"></div>
    <section class="section">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body">
                            <div class=" form-row">
                                <div class="form-group col-md-12">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control text-center" id="username" name="username"  value="<?= $data_user[0]->username; ?>" readonly />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12 text-center">
                                    <img src="<?php echo base_url('uploads/foto_profil/'.$data_user[0]->foto_profil);?>" height="100px" width="100px">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12 text-center">
                                    <form class="form-horizontal" action="<?php echo base_url();?>user_profile/upload_foto/<?= $data_user[0]->id_username ?>"  method="POST" enctype="multipart/form-data">
                                    <label for="photo">Photo Profile</label>
                                    <input type="file" name="photo" class="form-control" required />
                                    <input type="hidden" name="old_foto" value="<?= $data_user[0]->foto_profil ?>" />
                                    <input type="submit" class="btn btn-primary" value="Upload Foto" />
                                    </form>
                                </div>
                            </div>
                            <div class=" form-row">
                                <div class="form-group col-md-12">
                                    <label for="nama">Pemilik Akun</label>
                                    <input type="text" class="form-control text-center" id="nama" name="nama"  value="<?= $data_user[0]->nama; ?>" readonly />
                                </div>
                            </div>
                            <div class=" form-row">
                                <div class=" form-group col-md-6">
                                    <label for="nohp">No. Hp</label>
                                    <input type="text" class="form-control text-center" id="nohp" name="nohp" value="<?= $data_user[0]->hp ?>"  readonly />
                                </div>
                                <div class=" form-group col-md-6">
                                    <label for="role">Hak Akses</label>
                                    <?php
                                    $hak_akses = $data_user[0]->nama_role .
                                        (
                                            $data_user[0]->entitas != "" ? " - " . $data_user[0]->entitas :
                                            ($data_user[0]->kota != "" ? " - " . $data_user[0]->kota : "")
                                        );
                                    ?>

                                    <input type="text" class="form-control text-center" id="role" name="role" value="<?= $hak_akses ?>" readonly />
                                </div>
                            </div>
                            <br>
                            <a href="<?= base_url(); ?>user_profile/user_profile" class="btn btn-danger float-left">Kembali</a>
                            <button type="submit" class="btn btn-primary float-right">Edit</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<script>
    var baris = 0;

</script>
