<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="row mt-5">
            <div class="col-lg-12 mt-2">
                <div class="flash-data" data-flashdata="<?= $this->session->flashdata('pesan'); ?>"></div>
                <button class="btn btn-lg btn-primary mb-3 font-weight-bold" data-toggle="modal" data-target="#form_tambah">Tambah Satuan Pengukuran</button>
                <br><br>
                <table class="table table-bordered table-light shadow-sm p-3 mb-5 bg-white rounded dt-responsive nowrap" id="tabel-produk" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Kode</th>
                            <th class="text-center">Nama Satuan</th>
                            <th class="text-center">Deskripsi</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php
                            $itung = 0;
                            foreach ($satuan as $row) :
                                $itung = $itung + 1;
                                $id_satuan = $row['id'];
                                if($row['status'] == 1){
                                    $stat = '<div class="badge badge-sm badge-success">Aktif</div>';
                                }else if($row['status'] == 0){
                                    $stat = '<div class="badge badge-sm badge-danger">Nontaktif</div>';
                                }
                        ?>
                            <tr>
                                <td class="text-center"><?= $itung; ?></td>
                                <td class="text-center"><?= $row['kode']; ?></td>
                                <td class="text-center"><?= $row['nama_satuan']; ?></td>
                                <td class="text-left"><?= $row['deskripsi_satuan']; ?></td>
                                <td class="text-center"><?= $stat; ?></td>
                                <td class="text-center">
                                    <!-- Button Edit -->
                                    <a class="btn btn-sm btn-success text-light" id="btn-edit2" data-toggle="modal" data-target="#modal_edit_<?= $id_satuan; ?>">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <!-- Button Hapus -->
                                    <a href="<?= base_url(); ?>satuan_pengukuran/hapus/<?= $row['id']; ?>" class="btn btn-sm btn-danger text-light tombol-hapus"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    </tbody>
                </table>

            </div>
        </div>
    </section>
</div>

<!-- Modal Tambah Satuan -->
<div class="modal fade mt-5" id="form_tambah" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog mt-5" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="color:white">
                <h5 class="modal-title" id="staticBackdropLabel">Tambah Satuan Pengukuran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form ="form_tambah_form" action="<?= base_url(); ?>satuan_pengukuran/tambah" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="kode">Kode</label>
                        <input type="text" class="form-control" id="kode" name="kode" autocomplete="off" placeholder="Masukkan Kode Satuan . . ." />
                    </div>
                    <div class="form-group">
                        <label for="nama_satuan">Nama Satuan</label>
                        <input type="text" class="form-control" id="nama_satuan" name="nama_satuan" autocomplete="off" placeholder="Masukkan Nama Satuan . . ." />
                    </div>
                    <div class="form-group">
                        <label for="deskripsi_satuan">Deskripsi Satuan</label>
                        <input type="text" class="form-control" id="deskripsi_satuan" name="deskripsi_satuan" autocomplete="off" placeholder="Masukkan Deskripsi Satuan . . ." />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Satuan -->
<?php
    $itung = 0;
    foreach ($satuan as $row) :
        $itung = $itung + 1;
        $id_satuan = $row['id'];
?>
<div class="modal fade mt-5" id="modal_edit_<?= $id_satuan; ?>" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" role="dialog">
    <div class="modal-dialog mt-5" >
        <div class="modal-content">
            <div class="modal-header bg-success" style="color:white">
                <h5 class="modal-title" id="staticBackdropLabel">Edit Satuan Pengukuran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_edit_<?= $id_satuan; ?>" action="<?= base_url(); ?>satuan_pengukuran/update" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="kode_edit">Kode</label>
                        <input type="text" class="form-control" id="kode_edit" name="kode_edit" autocomplete="off" value="<?= $row['kode']; ?>" />
                    </div>
                    <div class="form-group">
                        <label for="nama_edit">Nama Satuan</label>
                        <input type="text" class="form-control" id="nama_edit" name="nama_edit" autocomplete="off" value="<?= $row['nama_satuan']; ?>" />
                    </div>
                    <div class="form-group">
                        <label for="deskripsi_edit">Deskripsi Satuan</label>
                        <input type="text" class="form-control" id="deskripsi_edit" name="deskripsi_edit" autocomplete="off" value="<?= $row['deskripsi_satuan']; ?>" />
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id_satuan_edit" id="id_satuan_edit" value="<?= $id_satuan; ?>" />
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<script>
    
    function isi_data() {
        //var kode = document.getElementById("data_kode").value;
        //var kode = document.forms("form_data").elements["data_kode"].value;
        document.getElementById("kode_edit").value = "kode";
        
       //$('#form_edit #kode_edit').val(kode);
       //$('#form_edit #kode_edit').attr('disabled', 'disabled');
       $('#form_edit #nama_satuan_edit').val("nama");
       $('#form_edit #nama_satuan_edit').attr('disabled', 'disabled');
       $('#form_edit #deskripsi_satuan_edit').val("deskripsi");
       $('#form_edit #deskripsi_satuan_edit').attr('disabled', 'disabled');
       
    }
    
    document.addEventListener('DOMContentLoaded', function() {

		$(document).on('click', '#btn-edit', function() {
			var object = 'satuan_pengukuran'
			//$('#modal_edit #form_edit').attr('action', 'satuan_pengu/update')
			$('#modal_edit').modal()

			//var id = $(this).attr("data-id")
			fetch(object + '/edit/6')
				.then(function(resp) {
					return resp.json()
				})
				.then(function(data) {
					$('#modal_edit #kode_edit').val("okok")
					$('#modal_edit #nama_edit').val(data[0].nama_satuan)
					$('#modal_edit #deskripsi_edit').val(data[0].deskripsi_satuan)
				})
		})
	})


</script>









