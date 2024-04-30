<div class="flash-data" data-flashdata="<?= $this->session->flashdata('pesan'); ?>"></div>
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= base_url().'/stok_divisi/show/stok' ?>" method="POST">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="id_gudang">Nama Gudang</label>
                                <select class="form-group select2" id="id_gudang" name="id_gudang" >
                                    <option value="nothing" selected>-- PILIH --</option>
                                    <?php
                                        foreach($list_gudang as $row){
                                            if($row->id == $id_gudang){
                                                $selected = "selected";
                                            }else{
                                                $selected = "";
                                            }
                                            echo '<option value="'.$row->id.'" '.$selected.' >'.$row->nama.'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <button type="submit" class="btn btn-primary float-right ml-3">Tampilkan Data</button>
                                <a href="<?= base_url().'/stok_divisi/stok_divisi' ?>" class="btn btn-danger float-right ml-3">Reset</a>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        
        <div class="row mt-5">
            <div class="col-md-12 mt-2">
                <table class="table table-bordered table-light shadow-sm p-3 mb-5 bg-white rounded dt-responsive nowrap" id="tabel-khusus" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">NO</th>
                            <th class="text-center">NAMA PRODUK</th>
                            <?php
                                $kolom_header = 0;
                                if($list_divisi){
                                    foreach($list_divisi as $row){
                                        $kolom_header = $kolom_header + 1;
                                        $id_divisi[$kolom_header] = $row->id;
                                        echo '<th class="text-center">'.$row->nama.'</th>';
                                    }
                                }else{
                                    echo '<th class="text-center">Divisi</th>';
                                }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if($data_stok){
                            $baris  = 0;
                            foreach($data_stok as $row){
                                $baris = $baris + 1;
                        ?>
                                <tr>
                                    <td class="text-center"><?= $baris ?></td>
                                    <td class="text-center"><?= $row->nama_produk ?></td>
                                    <?php
                                        $kolom  = 0;
                                        for ($i=0; $i<$kolom_header; $i++){
                                            $kolom = $kolom + 1;
                                            $ambil_stok_divisi = $this->Model_divisi->get_stok_produk_by_divisi($id_divisi[$kolom], $row->id_produk);
                                            if($ambil_stok_divisi){
                                                if($ambil_stok_divisi[0]->id_produk == $row->id_produk){
                                                    $stok_produk = (int)$ambil_stok_divisi[0]->stok_in - (int)$ambil_stok_divisi[0]->stok_out;
                                                }else{
                                                    $stok_produk = 0;
                                                }
                                            }else{
                                                $stok_produk = 0;
                                            }
                                    ?>
                                        <td class="text-center"><?= number_format($stok_produk, 0, ',', '.'); ?></td>
                                    <?php } ?>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

