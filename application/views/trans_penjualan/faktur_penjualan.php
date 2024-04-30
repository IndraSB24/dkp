<head>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
</head>

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="row">
            <div class="col-lg-4">
                <div class="card card-statistic-2">
                    <div class="card-icon shadow-primary bg-primary text-light font-weight-bold">
                        <i class="fas fa-list"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4 style="font-size: 12px;">Total Faktur Penjualan <span class="text-primary"></span></h4>
                        </div>
                        <div class="card-body">
                            <span id="show_total_faktur"><?= number_format($total_faktur[0]->total_faktur, 0, ',', '.') ?> Faktur</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card card-statistic-2">
                    <div class="card-icon shadow-primary bg-primary text-light font-weight-bold">
                        <i class="fas fa-list"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4 style="font-size: 12px;">Total Penjualan <span class="text-primary"></span></h4>
                        </div>
                        <div class="card-body">
                            <span id="show_total_pembelian">Rp. <?= number_format($total_penjualan, 2, ',', '.') ?></span>
                        </div>
                    </div>
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
                                <label for="filter_dari_tgl">Dari Tanggal</label>
                                <input type="date" class="form-control" id="filter_dari_tgl">
                            </div>
                            <div class="col-lg-2">
                                <label for="filter_ke_tgl">Ke Tanggal</label>
                                <input type="date" class="form-control" id="filter_ke_tgl">
                            </div>
                            
                            <div class="col-lg-4">
                                <label for="filter_outlet">Nama Outlet</label>
                                <select class="form-control select2" id="filter_outlet" <?= activeOutlet()?"selected":"" ?>>
                                    <option value="nothing">-- PILIH --</option>
                                    <?php
                                        foreach($list_gudang as $row){
                                            $row['id'] == activeOutlet() ? $selected="selected" : $selected="";
                                            echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['nama'].'</option>';
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
                <?php if(isAdmin()): ?>
                    <button class="btn btn-lg btn-primary mb-3 font-weight-bold" data-toggle="modal" data-target="#form_tambah">Tambah Faktur Penjualan</button>
                <?php endif; ?>
                <table class="table table-bordered table-light shadow-sm p-3 mb-5 bg-white rounded dt-responsive nowrap" id="tabel-show" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">kode Faktur</th>
                            <th class="text-center">Tanggal</th>
                            <th class="text-center">Penanggung Jawab</th></th>
                            <th class="text-center">Nama Outlet</th>
                            <th class="text-center">Total Penjualan</th>
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
                        <input type="hidden" id="kode_urut" name="kode_urut" value="<?= $urut; ?>">
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

<script>
    var table;
 
    $(document).ready(function() {
        //datatables
        table = $('#tabel-show').DataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "responsive": true,
            "order": [], //Initial no order.
            dom: 'Blrtip',
                "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
     
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('penjualan/ajax_list')?>",
                "type": "POST",
                "data": function ( data ) {
                    data.filter_outlet = $('#filter_outlet').val();
                    data.filter_karyawan = $('#filter_karyawan').val();
                    data.filter_dari_tgl = $('#filter_dari_tgl').val();
                    data.filter_ke_tgl = $('#filter_ke_tgl').val();
                }
            },
            "columnDefs": [{
				targets: [0, 1, 2, 3, 4, 5, 6],
				className: 'text-center'
			}],
     
            //Set column definition initialisation properties.
            "columnDefs": [
                { 
                    "targets": [ 0 ], //first column / numbering column
                    "orderable": false, //set not orderable
                },
            ],
        });
        
        $('#btn-filter').click(function(){ //button filter event click
            table.ajax.reload();  //just reload table
        });
        $('#btn-reset').click(function(){ //button reset event click
            location.reload();
        });
     
    });
</script>