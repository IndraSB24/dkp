<head>
    <style>
        .custom-modal {
          max-width: 600px;
        }
        
        .price-container {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-12">
                <div class="flash-data" data-flashdata="<?= $this->session->flashdata('pesan'); ?>"></div>
                <div class="card text-center">
                    <br>
                    <h3> LIST HARGA JUAL PRODUK </h3>
                    <br>
                </div>
            </div>
        </div>
        
        <!--FILTER-->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-2">
                                <label for="filter_dari_tgl">Tahun</label>
                                <select class="form-group select2" id="filter_tahun">
                                    <option value="nothing">-- PILIH --</option>
                                    <?php
                                        foreach (generate_years(2020) as $year) {
                                            echo '<option value="' . $year . '">' . $year . '</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label for="filter_ke_tgl">Bulan</label>
                                <select class="form-group select2" id="filter_bulan">
                                    <option value="nothing">-- PILIH --</option>
                                    <?php
                                        foreach(all_bulan() as $row){
                                            echo '<option value="'.$row['id'].'">'.$row['nama'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="col-lg-4">
                                <label for="filter_dari_gudang">Dari Entitas</label>
                                <select class="form-group select2" id="filter_dari_entitas">
                                    <option value="nothing">-- PILIH --</option>
                                    <?php
                                        foreach($list_gudang as $row){
                                            echo '<option value="'.$row['id'].'">'.$row['nama'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label for="filter_ke_gudang">Ke Entitas</label>
                                <select class="form-group select2" id="filter_ke_entitas">
                                    <option value="nothing">-- PILIH --</option>
                                    <?php
                                        foreach($list_gudang as $row){
                                            echo '<option value="'.$row['id'].'">'.$row['nama'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12  mt-3">
                                <button type="button" id="btn-filter" class="btn btn-primary float-right ml-3">Filter</button>
                                <button type="button" id="btn-reset" class="btn btn-danger float-right ml-3">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--FILTER CLOSE-->
        
        <div class="row">
            <div class="col-lg-12">
                <!--Notif-->
                <div class="flash-data" data-flashdata="<?= $this->session->flashdata('pesan'); ?>"></div>
                <!--Button-->
                <button class="btn btn-lg btn-primary mb-4 mr-2 font-weight-bold" data-toggle="modal" data-target="#modal_tambah">Tambah Data Harga Jual</button>
                <a class="btn btn-lg btn-primary mb-4 mr-2 font-weight-bold" href="<?= base_url('harga_jual/show/import') ?>">
                    Import Data Harga Jual
                </a>
                <!--table-->
                <table class="table table-bordered table-light shadow-sm p-3 mb-5 bg-white rounded nowrap" id="tabel-test" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Kode Produk</th>
                            <th class="text-center">Nama Produk</th>
                            <th class="text-center">Satuan</th>
                            <th class="text-center">Dari Entitas</th>
                            <th class="text-center">Ke Entitas</th>
                            <th class="text-center">Harga Jual</th>
                            <th class="text-center">Bulan</th>
                            <th class="text-center">Tahun</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        
    </section>
</div>


<!-- Modal Tambah -->
<div class="modal fade mt-5" id="modal_tambah" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog mt-5">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="color:white">
                <h5 class="modal-title" id="staticBackdropLabel">Tambah Harga Jual</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url(); ?>harga_jual/tambah" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="id_produk">Nama Produk</label>
                        <select class="form-control select2" id="id_produk" name="id_produk">
                            <option value="0" selected>PILIH PRODUK</option>
                            <?php
                                foreach($list_produk as $lp){
                                    echo '<option value="'.$lp['id'].'">'.$lp['nama'].'</option>';
                                }
                            ?>
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bulan">Bulan</label>
                                <select class="form-control select2" style="text-transform:uppercase" id="bulan" name="bulan">
                                    <option value="nothing">-- PILIH --</option>
                                    <?php
                                        foreach(all_bulan() as $row){
                                            echo '<option value="'.$row['id'].'">'.$row['nama'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tahun">Tahun</label>
                                <select class="form-group select2" id="tahun" name="tahun">
                                    <option value="nothing">-- PILIH --</option>
                                    <?php
                                        foreach (generate_years(2020) as $year) {
                                            echo '<option value="' . $year . '">' . $year . '</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="dari_entitas">Dari Entitas</label>
                        <select class="form-group select2" id="dari_entitas" name="dari_entitas">
                            <option value="nothing">-- PILIH --</option>
                            <?php
                                foreach($list_gudang as $row){
                                    echo '<option value="'.$row['id'].'">'.$row['nama'].'</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ke_entitas">Ke Entitas</label>
                        <select class="form-group select2" id="ke_entitas" name="ke_entitas">
                            <option value="nothing">-- PILIH --</option>
                            <?php
                                foreach($list_gudang as $row){
                                    echo '<option value="'.$row['id'].'">'.$row['nama'].'</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="harga_jual">Harga Jual</label>
                        <input type="number" class="form-control" id="harga_jual" name="harga_jual" autocomplete="off" placeholder="Masukkan Nominal Harga Jual . . ." />
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
<div class="modal fade mt-5" id="modal_edit" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog mt-5">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="color:white">
                <h5 class="modal-title" id="staticBackdropLabel">Ubah Harga Jual</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url(); ?>harga_jual/update" method="POST">
                <div class="modal-body">
                    <br>
                    <div class="form-group text-center">
                        <h3><?= $lpd['nama'] ?><h3>
                    </div>
                    <div class="form-group">
                        <label for="harga_jual">Harga Jual</label>
                        <input type="number" class="form-control" id="harga_jual" name="harga_jual" autocomplete="off" value="0" placeholder="Masukkan Nominal Harga Jual . . ." />
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id_edit" id="id_edit" value="0" />
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    var table;
 
    $(document).ready(function() {
        //datatables
        table = $('#tabel-test').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": false,
            "scrollX"   : true,
            "order"     : [],
            dom: 'Blrtip',
                "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
            "ajax": {
                "url": "<?php echo site_url('harga_jual/table_data')?>",
                "type": "POST",
                "data": function ( data ) {
                    data.filter_dari_entitas = $('#filter_dari_entitas').val();
                    data.filter_ke_entitas = $('#filter_ke_entitas').val();
                    data.filter_tahun = $('#filter_tahun').val();
                    data.filter_bulan = $('#filter_bulan').val();
                }
            },
            "columnDefs": [
                { 
                    "targets": [0, 1, 3, 4, 5, 6, 7, 8, 9],
                    "className": 'text-center'
                },
                { 
                    "targets": [0, 9], 
                    "orderable": false 
                },
            ],
        });
        
        $('#btn-filter').click(function(){ 
            table.ajax.reload(); 
        });
        $('#btn-reset').click(function(){
            location.reload();
        });
     
    });
</script>