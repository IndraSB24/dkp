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
		
		.text-uppercase{
		    text-transform: uppercase;
		}
		
		td{
		    text-transform: uppercase;
		}
    </style>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
    
</header>

<!-- Main Content -->
<div class="main-content">
    <div class="flash-data" data-flashdata="<?= $this->session->flashdata('pesan'); ?>"></div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="#" method="POST" id="form-filter">
                        <div class="row">
                            <div class="col-lg-6">
                                <?php
                                    $readonly = "";
                                    $lokasi = "";
                                    // foreach($list_gudang as $ld){
                                    //     if(substr(hak_akses(),0,3)=="SPV"){
                                    //         $lokasi = substr(hak_akses(),4);
                                    //     }else if(substr(hak_akses(),0,3)=="ADM"){
                                    //         $lokasi = substr(hak_akses(),6);
                                    //     }
                                    //     if($lokasi == $ld->nama){
                                    //         $readonly = "disabled";
                                    //     }
                                    // }
                                    if(activeOutlet()){
                                        $readonly = "disabled";
                                    }
                                ?>
                                <label for="id_gudang">Nama Gudang</label>
                                <select class="form-group select2" id="id_gudang" <?= $readonly ?>>
                                    <option value="nothing">-- PILIH --</option>
                                    <?php
                                        foreach($list_gudang as $row){
                                            // if(substr(hak_akses(),0,3)=="SPV"){
                                            //     $lokasi = substr(hak_akses(),4);
                                            // }else if(substr(hak_akses(),0,3)=="ADM"){
                                            //     $lokasi = substr(hak_akses(),6);
                                            // }
                                            // if($lokasi == $row->nama){
                                            //     $selected = "selected";
                                            // }else{
                                            //     $selected = "";
                                            // }
                                            
                                            $row->id == activeOutlet() ? $selected="selected" : $selected="";
                                            
                                            echo '<option value="'.$row->id.'" '.$selected.'>'.$row->nama.'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label for="id_produk">Nama Produk</label>
                                <select class="form-group select2" id="id_produk">
                                    <option value="nothing">-- PILIH --</option>
                                    <?php
                                        foreach($list_produk as $row){
                                            echo '<option value="'.$row['id'].'" class="text-uppercase">'.$row['nama'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="jenis_transaksi">Jenis Transaksi</label>
                                <select class="form-group select2" id="jenis_transaksi">
                                    <option value="nothing">-- PILIH --</option>
                                    <option value="masuk">MASUK</option>
                                    <option value="keluar">KELUAR</option>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label for="deskripsi_transaksi">Deskripsi Transaksi</label>
                                <select class="form-group select2" id="deskripsi_transaksi">
                                    <option value="nothing">-- PILIH --</option>
                                    <option value="pembelian">PEMBELIAN</option>
                                    <option value="mutasi">MUTASI</option>
                                    <option value="opname">OPNAME</option>
                                    <option value="pemakaian">PEMAKAIAN</option
                                    <option value="pembelian">PEMBELIAN</option>
                                    <option value="penjualan">PENJUALAN</option>
                                    <option value="produksi">PRODUKSI</option>
                                    <option value="rusak">RUSAK</option>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label for="dari_tanggal">Dari Tanggal</label>
                                <input type="date" class="form-control" id="tgl_faktur">
                            </div>
                            <div class="col-lg-2">
                                <label for="ke_tanggal">Sampai Tanggal</label>
                                <input type="date" class="form-control" id="ke_tgl_faktur">
                            </div>
                            
                        </div>
                        <div class="row">
                            <div class="col-lg-12  mt-3">
                                <button type="button" id="btn-filter" class="btn btn-primary float-right ml-3">Filter</button>
                                <button type="button" id="btn-reset" class="btn btn-danger float-right ml-3">Reset</button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-12">
                <!--<table id="tabel-show" class="table table-bordered table-light shadow-sm p-3 mb-5 bg-white rounded nowrap" cellspacing="0" width="100%">-->
                <table class="table table-bordered table-light shadow-sm p-3 mb-5 bg-white rounded nowrap" id="tabel-transaksi-gudang" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Pada Gudang</th>
                            <th class="text-center">Jenis Transaksi</th>
                            <th class="text-center">Dari/Ke Gudang/Supplier</th>
                            <th class="text-center">Kode Produk</th>
                            <th class="text-center">Nama Produk</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-center">Satuan</th>
                            <th class="text-center">Harga</th>
                            <th class="text-center">Deskripsi Transaksi</th>
                            <th class="text-center">Kode Faktur</th>
                            <th class="text-center">Tanggal Faktur</th>
                        </tr>
                        <!--<tr>-->
                        <!--    <th class="text-center">No</th>-->
                        <!--    <th class="text-center">Dari Gudang</th>-->
                        <!--    <th class="text-center">Ke Gudang</th>-->
                        <!--    <th class="text-center">Default Gudang</th>-->
                        <!--    <th class="text-center">Nama Produk</th>-->
                        <!--    <th class="text-center">Jenis Transaksi</th>-->
                        <!--    <th class="text-center">Deskripsi Transaksi</th>-->
                        <!--    <th class="text-center">Kode Faktur</th>-->
                        <!--    <th class="text-center">Jumlah</th>-->
                        <!--    <th class="text-center">Tanggal Faktur</th>-->
                        <!--</tr>-->
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
            </div>
        </div>
    </section>
</div>

<script>
    
    var table;
 
    $(document).ready(function() {
        //datatables
        table = $('#tabel-transaksi-gudang').DataTable({
            scrollX: true,
            autoWidth: false,
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "responsive": false,
            "order": [], //Initial no order.
            dom: 'Blrtip',
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, 'All']],
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
     
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('transaksi_gudang/ajax_list')?>",
                "type": "POST",
                "data": function ( data ) {
                    data.id_gudang = $('#id_gudang').val();
                    data.id_produk = $('#id_produk').val();
                    data.jenis_transaksi = $('#jenis_transaksi').val();
                    data.deskripsi_transaksi = $('#deskripsi_transaksi').val();
                    data.tgl_faktur = $('#tgl_faktur').val();
                    data.ke_tgl = $('#ke_tgl_faktur').val();
                }
            },
     
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