<head>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
</head>

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <!--FILTER-->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-2">
                                <label for="filter_dari_tgl">Dari Tanggal</label>
                                <input type="date" class="form-control" id="filter_dari_tgl">
                            </div>
                            <div class="col-lg-2">
                                <label for="filter_ke_tgl">Ke Tanggal</label>
                                <input type="date" class="form-control" id="filter_ke_tgl">
                            </div>
                            
                            <div class="col-lg-4">
                                <?php
                                    $filter_outlet_disabled = "";
                                    $filter_outlet_selected = "";
                                    active_role()=="admin_entitas" ? $filter_outlet_disabled="disabled" : $filter_outlet_disabled="";
                                ?>
                                <label for="filter_outlet">Nama Outlet</label>
                                <select class="form-control select2" id="filter_entitas" <?= $filter_outlet_disabled ?>>
                                    <option value="nothing">-- PILIH --</option>
                                    <?php
                                        foreach($list_gudang as $row){
                                            activeOutlet()==$row['id'] ? $filter_outlet_selected="selected" : $filter_outlet_selected="";
                                            echo '<option value="'.$row['id'].'" '.$filter_outlet_selected.'>'.$row['nama'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label for="filter_karyawan">Nama Penanggung Jawab</label>
                                <select class="form-group select2" id="filter_karyawan">
                                    <option value="nothing">-- PILIH --</option>
                                    <?php
                                        foreach($list_karyawan as $row){
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
                <div class="flash-data" data-flashdata="<?= $this->session->flashdata('pesan'); ?>"></div>
                <?php if(isAdmin() || active_role()=="admin_entitas"): ?>
                    <a class="btn btn-lg btn-primary mb-3 font-weight-bold" id="btn-add" href="<?= base_url('fat/show/tambah_data') ?>">
                        <i class="fas fa-plus"> Tambah Data</i>
                    </a>
                <?php endif; ?>
                <table class="table table-bordered table-light shadow-sm p-3 mb-5 bg-white rounded dt-responsive nowrap" id="tabel-test" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Tanggal</th>
                            <th class="text-center">Shift</th>
                            <th class="text-center">Entitas</th></th>
                            <th class="text-center">Penanggung Jawab</th>
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

<!-- Modal Tambah Faktur -->
<div class="modal fade " id="form_tambah" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="color:white">
                <h5 class="modal-title" id="staticBackdropLabel">Tambah Faktur Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url(); ?>penjualan/tambah/faktur" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="no_faktur">No Faktur</label>
                        <input type="text" class="form-control text-center" id="no_faktur" name="no_faktur" autocomplete="off" value="<?= $kode_faktur; ?>" readonly>
                    </div>
                    <div class=" form-group">
                        <label for="tanggal_faktur">Tanggal Faktur</label>
                        <input type="date" class="form-control" id="tanggal_faktur" name="tanggal_faktur" autocomplete="off" placeholder="Pilih Tanggal" >
                        <?= form_error('tanggal_faktur', '<small class="form-text text-danger">', '</small>'); ?>
                    </div>
                    <div class=" form-group">
                        <label for="gudang">Pada Outlet</label>
                        <select class="form-control select2" id="gudang" name="gudang">
                            <option value="0" selected>PILIH OUTLET</option>
                            <?php foreach ($list_gudang as $gudang) : ?>
                                <option value="<?= $gudang['id'] ?>"><?= $gudang['nama'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?= form_error('gudang', '<small class="form-text text-danger">', '</small>'); ?>
                    </div>
                    <div class=" form-group">
                        <label for="karyawan">Nama karyawan</label>
                        <select class="form-control select2" id="karyawan" name="karyawan">
                            <option value="0" selected>PILIH KARYAWAN</option>
                            <?php foreach ($list_karyawan as $karyawan) : ?>
                                <option value="<?= $karyawan['id'] ?>"><?= $karyawan['nama'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?= form_error('karyawan', '<small class="form-text text-danger">', '</small>'); ?>
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
                "url": "<?php echo site_url('fat/table_data')?>",
                "type": "POST",
                "data": function ( data ) {
                    data.filter_dari_tgl = $('#filter_dari_tgl').val();
                    data.filter_ke_tgl = $('#filter_ke_tgl').val();
                    data.filter_entitas = $('#filter_entitas').val();
                    data.filter_karyawan = $('#filter_karyawan').val();
                }
            },
            "columnDefs": [
                { 
                    "targets": [0, 1, 2, 3, 4, 5],
                    "className": 'text-center'
                },
                { 
                    "targets": [0, 5], 
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