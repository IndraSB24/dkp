<head>
    <style>
        .huruf-besar{
            text-transform:uppercase;
        }
    </style>
</head>

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="text-center huruf-besar">
                        <br>
                        <h3> LIST HARGA JUAL PRODUK <?= $nama_produk ?> </h3>
                        <br>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="flash-data" data-flashdata="<?= $this->session->flashdata('pesan'); ?>"></div>
                <table class="table table-bordered table-light shadow-sm p-3 mb-5 bg-white rounded dt-responsive nowrap" id="tabel-khusus" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Bulan</th>
                            <?php
                                $kolom_header = 0;
                                foreach($list_tahun as $row){
                                    $kolom_header = $kolom_header + 1;
                                    $tahun[$kolom_header] = $row->tahun;
                                    echo '<th class="text-center">'.$row->tahun.'</th>';
                                }
                            ?>
                        </tr>
                    </thead>
                        
                    <tbody>
                        <?php
                            $baris  = 0;
                            foreach(all_bulan() as $row => $bulan){
                                $baris = $baris + 1;
                        ?>
                                    <tr>
                                        <td class="text-center"><?= $baris ?></td>
                                        <td class="text-center huruf-besar"><?= $bulan ?></td>
                                        <?php
                                            $kolom  = 0;
                                            for ($i=0; $i<$kolom_header; $i++){ 
                                                $kolom = $kolom + 1;
                                                
                                                $ambil_harga_jual = $this->Model_harga_jual->get_by_idProduk_bulan_tahun($id_produk, $baris, $tahun[$kolom]);
                                                if($ambil_harga_jual){
                                                    $harga_jual = $ambil_harga_jual[0]->harga_jual;
                                                }else{
                                                    $harga_jual = 0;
                                                }
                                        ?>
                                            <td class="text-center">Rp. <?= number_format($harga_jual, 2, ',', '.'); ?></td>   
                                        <?php } ?>
                                    </tr>
                        <?php } ?>
                    </tbody>
                </table>

            </div>
        </div>
    </section>
</div>

