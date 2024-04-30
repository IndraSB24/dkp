<head>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
</head>

<!-- Main Content -->
<div class="flash-data" data-flashdata="<?= $this->session->flashdata('pesan'); ?>"></div>
<div class="main-content">
    <section class="section">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="card card-statistic-2">
                    <div class="card-icon shadow-primary bg-primary text-light font-weight-bold">
                        <i class="fas fa-list"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4 style="font-size: 12px;">Total Faktur Mutasi Divisi<span class="text-primary"></span></h4>
                        </div>
                        <div class="card-body">
                            <span id="show_total_faktur"><?= number_format($total_mutasi[0]->jumlah_faktur, 0, ',', '.') ?> Faktur</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card card-statistic-2">
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4 style="font-size: 12px;">Pada Gudang / Outlet <span class="text-primary"></span></h4>
                        </div>
                        <div class="card-body mb-3">
                            <select class="form-group select2" id="filter_gudang">
                                <option value="nothing">-- PILIH --</option>
                                <?php
                                    foreach($gudang as $row){
                                        echo '<option value="'.$row->id.'">'.$row->nama.'</option>';
                                    }
                                ?>
                            </select>
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
                                <label for="filter_dari_divisi">Dari Divisi</label>
                                <select class="form-group select2" id="filter_dari_divisi">
                                    <option value="nothing">-- PILIH --</option>
                                    <?php
                                        foreach($divisi as $row){
                                            echo '<option value="'.$row->id.'">'.$row->nama.'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label for="filter_ke_divisi">Ke Divisi</label>
                                <select class="form-group select2" id="filter_ke_divisi">
                                    <option value="nothing">-- PILIH --</option>
                                    <?php
                                        foreach($divisi as $row){
                                            echo '<option value="'.$row->id.'">'.$row->nama.'</option>';
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
                <a class="btn btn-lg btn-primary mb-3 font-weight-bold" data-toggle="modal" data-target="#modal_pilih_gudang">
                    Tambah Faktur Mutasi Divisi
                </a>
                <table class="table table-bordered table-light shadow-sm p-3 mb-5 bg-white rounded dt-responsive nowrap" id="tabel-show" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">No Faktur</th>
                            <th class="text-center">Tanggal Dimutasi</th>
                            <th class="text-center">Pada Gudang</th>
                            <th class="text-center">Dari Divisi</th>
                            <th class="text-center">Ke Divisi</th>
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

<!-- Modal Pilih Gudang -->
<div class="modal fade mt-5" id="modal_pilih_gudang" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog mt-5">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="color:white">
                <h5 class="modal-title" id="staticBackdropLabel">Pilih Gudang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url(); ?>mutasi_divisi/show/tambah" method="POST">
                <div class="modal-body">
                    <label for="gudang_add">Nama Gudang</label>
                    <select class="form-control select2" id="gudang_add" name="gudang_add">
                        <option selected>--PILIH GUDANG--</option>
                        <?php foreach ($gudang as $gd) : ?>
                            <option value="<?=$gd->id?>PengHubunG<?=$gd->nama?>"><?= $gd->nama ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="id_gudang" name="id_gudang" />
                    <input type="hidden" id="nama_gudang" name="nama_gudang" />
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
                "url": "<?php echo site_url('mutasi_divisi/ajax_list')?>",
                "type": "POST",
                "data": function ( data ) {
                    data.filter_dari_divisi = $('#filter_dari_divisi').val();
                    data.filter_ke_divisi = $('#filter_ke_divisi').val();
                    data.filter_dari_tgl = $('#filter_dari_tgl').val();
                    data.filter_ke_tgl = $('#filter_ke_tgl').val();
                }
            },
            
            "columnDefs": [{
				targets: [0, 1, 2, 3, 4, 5, 6, 7, 8],
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
    
    document.addEventListener('DOMContentLoaded', function() {
	    Array.prototype.forEach.call(document.getElementsByName('gudang_add'),
    		function (elem) {
    			elem.addEventListener('change', function() {
    				let text	= this.value;
    				const isi 	= text.split("PengHubunG");
    				let id		= isi[0],
    					nama 	= isi[1];
    				
    				document.getElementById('id_gudang').value = id;
    				document.getElementById('nama_gudang').value = nama;
    	    });
        });
    });
</script>

