<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-8 mt-1">
                <div class="card">
                    <div class="card-body">
                            <br>
                            <div >
                                <center><h2> <?= $detail_produk[0]->nama ?> </h2></center>
                            </div>
                            <br>
                            <div class="col-lg-12 mt-2">
                                <label for="resep">List Formula Produk</label>
                                <table class="table table-bordered table-light shadow-sm p-3 mb-5 bg-white rounded dt-responsive nowrap" id="tabel-khusus" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="bg-primary" style="text-align:center; color:white;" width="10%"> No </th>
                                            <th class="bg-primary" style="text-align:center; color:white;" width="65%"> Bahan </th>
                                            <th class="bg-primary" style="text-align:center; color:white;" width="15%"> Jumlah </th>
                                            <th class="bg-primary" style="text-align:center; color:white;" width="15%"> Satuan Dasar </th>
                                        </tr>
                                    </thead>
                                    <tbody>
            						    <?php 
                                            $itung = 0;
                                            foreach ($detail_formula as $row) :
                                                $itung = $itung + 1;
                                        ?>
                                            <tr>
                                                <td class="text-center"><?= $itung; ?></td>
                                                <td class="text-center"><?= $row['nama_produk']; ?></td>
                                                <td class="text-center"><?= $row['jumlah_bahan']; ?></td>
                                                <td class="text-center"><?= $row['satuan']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
            			        </table>
        			        </div>
        			        <br>
        			        <input type="hidden" id="itung" name="itung">
                            <a href="<?= base_url(); ?>formula_produk/formula_produk" class="btn btn-danger">Kembali</a>
                            <button type="submit" class="btn btn-primary float-right">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

