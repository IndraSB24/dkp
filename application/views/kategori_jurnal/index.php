<div class="flash-data" data-flashdata="<?= $this->session->flashdata('pesan'); ?>"></div>
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="row mt-5">
            <div class="col-lg-12 mt-2">
                <button class="btn btn-lg btn-primary mb-3 font-weight-bold" data-toggle="modal" data-target="#form_tambah">Tambah Kategori Jurnal</button>
                <table class="table table-bordered table-light shadow-sm p-3 mb-5 bg-white rounded dt-responsive nowrap" id="tabel-produk" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Nama Kategori Jurnal</th>
                            <th class="text-center">Deskripsi</th>
                            <th class="text-center">Dibuat Oleh</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php 
                            $itung = 0;
                            foreach ($data_kategori_jurnal as $row) :
                                $itung = $itung + 1;
                                if($row['status'] == 1){
                                    $stat = '<div class="badge badge-sm badge-success">Aktif</div>';
                                }else if($row['status'] == 0){
                                    $stat = '<div class="badge badge-sm badge-danger">Nontaktif</div>';
                                }
                        ?>
                            <tr>
                                <td class="text-center"><?= $itung; ?></td>
                                <td class="text-center"><?= $row['kategori_jurnal']; ?></td>
                                <td class="text-center"><?= $row['deskripsi']; ?></td>
                                <td class="text-center"><?= $row['dibuat_oleh']; ?></td>
                                <td class="text-center"><?= $stat; ?></td>
                                <td class="text-center">
                                    <!-- Button Edit -->
                                    <a href="#" class="btn btn-sm btn-success text-light" id="btn-edit"
                                        data-id="<?= $row['id'] ?>"
                        				data-nama_kategori="<?= $row['kategori_jurnal'] ?>"
                        				data-deskripsi_kategori="<?= $row['deskripsi'] ?>"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <!-- Button Hapus -->
                                    <a href="<?= base_url('kategori_jurnal/hapus/'.$row['id']); ?>" class="btn btn-sm btn-danger text-light tombol-hapus">
                                        <i class="fas fa-trash-alt"></i>
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

<!-- Modal Tambah -->
<div class="modal fade " id="form_tambah" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="color:white">
                <h5 class="modal-title" id="staticBackdropLabel">Tambah Data Kategori Jurnal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('kategori_jurnal/tambah'); ?>" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_satuan">Nama Kategori Jurnal</label>
                        <input type="text" class="form-control" id="nama" name="nama" autocomplete="off" placeholder="Masukkan Nama Kategori Jurnal . . ." />
                    </div>
                    <div class="form-group">
                        <label for="deskripsi_satuan">Deskripsi</label>
                        <input type="text" class="form-control" id="deskripsi" name="deskripsi" autocomplete="off" placeholder="Masukkan Deskripsi . . ." />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal Edit -->
<div class="modal fade mt-5" id="modal-edit" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" role="dialog">
    <div class="modal-dialog mt-5" >
        <div class="modal-content">
            <div class="modal-header btn-success" style="color:white">
                <h5 class="modal-title" id="staticBackdropLabel">Edit Data Kategori Jurnal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_edit" action="<?= base_url('kategori_jurnal/update'); ?>" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_satuan">Nama Kategori Jurnal</label>
                        <input type="text" class="form-control" id="nama_edit" name="nama_edit" autocomplete="off" placeholder="Masukkan Nama Kategori Jurnal . . ." />
                    </div>
                    <div class="form-group">
                        <label for="deskripsi_satuan">Deskripsi</label>
                        <input type="text" class="form-control" id="deskripsi_edit" name="deskripsi_edit" autocomplete="off" placeholder="Masukkan Deskripsi . . ." />
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id_edit" id="id_edit" />
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--SCRIPT-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).on('click', '#btn-edit', function(){
        // Set data to Form Edit
        $('#modal-edit #id_edit').val($(this).data('id'));
        $('#modal-edit #nama_edit').val($(this).data('nama_kategori'));
        $('#modal-edit #deskripsi_edit').val($(this).data('deskripsi_kategori'));
        
        // Call Modal Edit
        $('#modal-edit').modal('show');
    });
</script>