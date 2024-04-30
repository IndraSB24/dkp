<head>
    <style>
        .huruf-besar{
            text-transform:uppercase;
        }
    </style>
</head>

<!-- Main Content -->
<div class="main-content">
    <div class="flash-data" data-flashdata="<?= $this->session->flashdata('pesan'); ?>"></div>
    <section class="section">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-12 mt-4">
                <div class="card">
                    <div class="text-center huruf-besar">
                        <br>
                        <h3> List KPI Barang Rusak <?= $nama_produk ?> </h3>
                        <br>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-lg-12 mt-2">
                <table class="table table-bordered table-light shadow-sm p-3 mb-5 bg-white rounded dt-responsive nowrap" id="tabel-khusus" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Bulan</th>
                            <?php
                                $kolom_header = 0;
                                if($list_tahun){
                                    foreach($list_tahun as $row){
                                        $kolom_header = $kolom_header + 1;
                                        $tahun[$kolom_header] = $row->tahun;
                                        echo '<th class="text-center">'.$row->tahun.'</th>';
                                    }
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
                                            if($kolom_header != 0){
                                                for ($i=0; $i<$kolom_header; $i++){ 
                                                    $kolom = $kolom + 1;
                                                    
                                                    $ambil_data = $this->Model_kpi_barang_rusak->get_by_idProduk_bulan_tahun($id_produk, $baris, $tahun[$kolom]);
                                                    if($ambil_data){
                                                        $nilai_maksimum = $ambil_data[0]->nilai_maksimum;
                                                    }else{
                                                        $nilai_maksimum = 0;
                                                    }
                                        ?>
                                            <td class="text-center">Rp. <?= number_format($nilai_maksimum, 2, ',', '.'); ?></td>   
                                        <?php   }
                                            }
                                        ?>
                                    </tr>
                        <?php } ?>
                    </tbody>
                </table>

            </div>
        </div>
    </section>
</div>

