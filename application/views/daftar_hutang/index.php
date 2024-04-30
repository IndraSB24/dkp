<head>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
</head>
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        
        <!--RANGKUMAN-->
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="card card-statistic-2">
                    <div class="card-icon shadow-primary bg-primary text-light font-weight-bold">
                        <i class="fas fa-list"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4 style="font-size: 12px;">Total Supplier Terhutang <span class="text-primary"></span></h4>
                        </div>
                        <div class="card-body">
                            <span id="show_total_faktur">
                                    <?php
                                        $itung=0;
                                        foreach($list_data as $row){
                                           $itung += 1;
                                        }
                                        echo $itung." Supplier";
                                    ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="card card-statistic-2">
                    <div class="card-icon shadow-primary bg-primary text-light font-weight-bold">
                        <i class="fas fa-list"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4 style="font-size: 12px;">Total Faktur Terhutang <span class="text-primary"></span></h4>
                        </div>
                        <div class="card-body">
                            <span id="show_total_pembelian">
                                    <?php
                                        $itung=0;
                                        foreach($list_data as $row){
                                           $itung += $row->jumlah_faktur;
                                        }
                                        echo $itung." Faktur";
                                    ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="card card-statistic-2">
                    <div class="card-icon shadow-primary bg-primary text-light font-weight-bold">
                        <i class="fas fa-list"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4 style="font-size: 12px;">Total Hutang <span class="text-primary"></span></h4>
                        </div>
                        <div class="card-body">
                            <span id="show_total_hutang">Rp. <?= number_format($data_hutang[0]->total_hutang - $data_hutang[0]->dibayar, 2, ',', '.') ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--RANGKUMAN-->
        
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
                                <label for="filter_supplier">Nama Supplier</label>
                                <select class="form-group select2" id="filter_supplier">
                                    <option value="nothing">-- PILIH --</option>
                                    <?php
                                        foreach($supplier as $row){
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
        
        <div class="row d-flex justify-content-center">
            <div class="col-lg-12">
                <div class="flash-data" data-flashdata="<?= $this->session->flashdata('pesan'); ?>"></div>
                <table class="table table-bordered table-light shadow-sm p-3 mb-5 bg-white rounded dt-responsive nowrap" id="tabel-show" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Nama Supplier</th>
                            <th class="text-center">Jumlah Faktur Hutang</th>
                            <th class="text-center">Total Hutang</th>
                            <th class="text-center">Sudah Dibayar</th>
                            <th class="text-center">Sisa Hutang</th>
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
                "url": "<?php echo site_url('daftar_hutang/ajax_list')?>",
                "type": "POST",
                "data": function ( data ) {
                    data.filter_supplier = $('#filter_supplier').val();
                    data.filter_dari_tgl = $('#filter_dari_tgl').val();
                    data.filter_ke_tgl = $('#filter_ke_tgl').val();
                }
            },
            
            columnDefs: [{
				"targets": [0, 1, 2, 3, 4, 5],
				"className": 'text-center'
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

