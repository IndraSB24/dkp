<style>
    .table-wrapper {
        overflow-x: scroll;
    }
    .table-wrapper table {
        min-width: 600px; /* Set an appropriate minimum width for the table to avoid overflow issues */
    }
    .fixed-column {
        position: sticky;
        left: 0;
        background-color: white;
    }
</style>

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= base_url().'stok_gudang/show/filtered' ?>" method="POST" id="form_filter">
                        <div class="row text-center mb-2">
                            <div class="col-lg-12">
                                <h4>
                                    <?php
                                        if($dari_tgl && !$ke_tgl){
                                            echo 'MENAMPILKAN STOK DARI TANGGAL '.tgl_indo($dari_tgl);
                                        }else if($ke_tgl && !$dari_tgl){
                                            echo 'MENAMPILKAN STOK SAMPAI TANGGAL '.tgl_indo($ke_tgl);
                                        }else if($dari_tgl && $ke_tgl){
                                            echo 'MENAMPILKAN STOK TANGGAL '.tgl_indo($dari_tgl).' SAMPAI '.tgl_indo($ke_tgl);
                                        }else{
                                            echo 'MENAMPILKAN STOK KESELURUHAN';
                                        }
                                    ?>
                                </h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="dari_tanggal">Dari Tanggal</label>
                                <input type="date" class="form-control" name="dari_tgl_faktur"/>
                            </div>
                            <div class="col-lg-6">
                                <label for="ke_tanggal">Sampai Tanggal</label>
                                <input type="date" class="form-control" name="ke_tgl_faktur">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12  mt-3">
                                <input type="submit" id="btn-filter" class="btn btn-primary float-right ml-3" value="Filter"></input>
                                <a href="<?= base_url().'stok_gudang/stok_gudang' ?>" type="button" class="btn btn-danger float-right ml-3">Reset</a>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-lg-12 mt-2">
                <div class="flash-data" data-flashdata="<?= $this->session->flashdata('pesan'); ?>"></div>
                <table class="table table-bordered table-light shadow-sm p-3 mb-5 bg-white rounded nowrap" id="tabel-stok" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">NO</th>
                            <th class="text-center">KATEGORI</th>
                            <th class="text-center">NAMA PRODUK</th>
                            <?php
                                $kolom_header = 0;
                                foreach($list_gudang as $row){
                                    $kolom_header = $kolom_header + 1;
                                    $id_gudang[$kolom_header] = $row['id'];
                                    echo '<th class="text-center">'.$row['nama'].'</th>';
                                }
                            ?>
                        </tr>
                    </thead>
                        
                    <tbody>
                        <?php
                            $baris  = 0;
                            foreach($list_produk as $row){
                                $baris = $baris + 1;
                        ?>
                                    <tr>
                                        <td class="text-center"><?=$baris?></td>
                                        <td class="text-center"><?=$row['kelompok_produk']?></td>
                                        <td class="text-left"><?=$row['nama']?></td>
                                        <?php
                                            $kolom  = 0;
                                            for ($i=0; $i<$kolom_header; $i++){ 
                                                $kolom = $kolom + 1;
                                                
                                                if($dari_tgl || $ke_tgl){
                                                    $ambil_stok_in = $this->Model_stok_gudang->get_transaksi_in_by_produk($id_gudang[$kolom], $row['id'], $dari_tgl, $ke_tgl);
                                                    if($ambil_stok_in){
                                                        $stok_terkini_masuk = $ambil_stok_in[0]->jumlah_masuk;
                                                    }else{
                                                        $stok_terkini_masuk = 0;
                                                    }
                                                    $ambil_stok_out = $this->Model_stok_gudang->get_transaksi_out_by_produk($id_gudang[$kolom], $row['id'], $dari_tgl, $ke_tgl);
                                                    if($ambil_stok_out){
                                                        $stok_terkini_keluar = $ambil_stok_out[0]->jumlah_keluar;
                                                    }else{
                                                        $stok_terkini_keluar = 0;
                                                    }
                                                    
                                                    $show_stok = $stok_terkini_masuk - $stok_terkini_keluar;
                                                }else{
                                                    $ambil_stok_gudang = $this->Model_gudang->get_produk_stok_gudang($id_gudang[$kolom], $row['id']);
                                                    if($ambil_stok_gudang){
                                                        if($ambil_stok_gudang[0]->id_produk == $row['id']){
                                                            $stok_produk = (float)$ambil_stok_gudang[0]->stok_in - (float)$ambil_stok_gudang[0]->stok_out;
                                                        }else{
                                                            $stok_produk = 0;
                                                        }
                                                    }else{
                                                        $stok_produk = 0;
                                                    }
                                                    $show_stok = $stok_produk;
                                                }
                                        ?>
                                            <td class="text-center"><?= thousand_separator_international($show_stok); ?></td>
                                        <?php } ?>
                                        
                                    </tr>
                        <?php } ?>
                    </tbody>
                </table>

            </div>
        </div>
    </section>
</div>

<script>
    // Tabel stok
    $('#tabel-g-stok').DataTable({
        scrollX: true,
        autoWidth: false,
        lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, 'All']
        ],
        columnDefs: [{
            searchable: false,
            orderable: false,
            targets: 0,
        }],
        dom: 'lBfrtip', // Include 'C' for column visibility control
        buttons: [
            'colvis', // Column visibility control
            'excel', // Excel button
            'csv',   // CSV button
            'pdf',   // PDF button
        ],
    });

</script>
