<head>
    <style>
        .huruf-besar{
            text-transform:uppercase;
        }
        
        .detailInput{
			width:100%;
			height:auto;
			background:transparent;
			border: 0px solid black;
			border-radius:0px;
			margin-left:0px;
		}
		
		.bg-editable{
		    padding:0;
		    background-color:rgb(120, 120, 120, 0.2);
		}
		
		::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
          color: red;
          opacity: 1; /* Firefox */
        }
        
        :-ms-input-placeholder { /* Internet Explorer 10-11 */
          color: red;
        }
        
        ::-ms-input-placeholder { /* Microsoft Edge */
          color: red;
        }
    </style>
</head>

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <?php 
            if($kode == "tambah"){
                echo '<form action="'.base_url().'stock_opname/tambah/bulanan" method="POST" >';
            }else if($kode == "tambah_harian"){
                echo '<form action="'.base_url().'stock_opname/tambah/harian" method="POST" >';
            }
        ?>
        <div class="row d-flex justify-content-center">
            <div class="col-lg-12 mt-4">
                <div class="card">
                    <div class="text-center huruf-besar">
                        <?php
                            if($id_gudang != 1 && $id_gudang != 2 && $id_gudang != 14){
                                if(date('m') != 01){
                                    $bulan = (int)date('m') - 1;
                                    $tahun = date('Y');
                                }else{
                                    $bulan = 12;
                                    $tahun = date('Y') - 1;
                                }
                            }else{
                                $bulan = date('m');
                                $tahun = date('Y');
                            }
                        ?>
                        <br>
                        <h3>
                            <?php 
                                if($kode == "tambah"){
                                    echo 'Stock Opname Bulan '.bulan_indo($bulan).' Tahun '.$tahun;
                                }else if($kode == "tambah_harian"){
                                    echo 'Stock Opname Harian Tanggal '.date('d-m-Y');
                                }
                            ?>
                        </h3>
                        <br>
                        <h4> <?= $nama_gudang ?> </h4>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="id_gudang" value="<?= $id_gudang ?>" readonly/>
        <input type="hidden" name="nama_gudang" value="<?= $nama_gudang ?>" readonly/>
        <div class="row mt-1">
            <div class="col-lg-12 mt-2">
                <div class="flash-data" data-flashdata="<?= $this->session->flashdata('pesan'); ?>"></div>
                <table class="table table-bordered table-light shadow-sm p-3 mb-5 bg-white rounded nowrap" id="tabel-tambah-opname" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Produk</th>
                            <th class="text-center">Satuan</th>
                            <!-- <th class="text-center">Stock Akhir</th> -->
                            <th class="text-center">Stock Real</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <?php
                        if($kode == "tambah_harian"){
                            $baris  = 0;
                            foreach($list_produk as $lp){
                                if($lp->status_opname_harian == 1){
                                    $baris += 1;
                                    $ambil_stok_gudang = $this->Model_gudang->get_produk_stok_gudang($id_gudang, $lp->id_produk);
                                    if($ambil_stok_gudang){
                                        if($ambil_stok_gudang[0]->id_produk == $lp->id_produk){
                                            $stok_produk = (int)$ambil_stok_gudang[0]->stok_in - (int)$ambil_stok_gudang[0]->stok_out;
                                        }else{
                                            $stok_produk = 0;
                                        }
                                    }else{
                                        $stok_produk = 0;
                                    }
                        ?>
                                    <tr>
                                        <td class="text-center"><?= $baris ?></td>
                                        <td class="text-center huruf-besar"><?= $lp->nama_produk; ?></td>
                                        <td class="text-center huruf-besar"><?= $lp->satuan_dasar; ?></td>
                                        <!--
                                        <td class="text-center">
                                            <?= number_format($stok_produk, 0, ',', '.'); ?>
                                        </td>
                                        -->
                                        <td class="text-center">
                                            <input type="number" class="detailInput text-center" id="stok_real_<?= $baris ?>" name="stok_real_<?= $baris ?>" placeholder="Ketik Stok Real" />
                                        </td>
                                        <input type="hidden" name="itung" value="<?= $baris ?>" />
                                        <input type="hidden" name="stok_now_harian_<?= $baris ?>" value="<?= $stok_produk ?>" />
                                        <input type="hidden" name="id_produk_harian_<?= $baris ?>" value="<?= $lp->id_produk ?>" />
                                        <input type="hidden" name="nama_produk_harian_<?= $baris ?>" value="<?= $lp->nama_produk ?>" />
                                    </tr>
                        <?php   }
                            }
                        }else{
                            $baris  = 0;
                            foreach($list_produk as $lp){
                                $baris = $baris + 1;
                        ?>
                                <tr>
                                        <td class="text-center"><?= $baris ?></td>
                                        <td class="text-center huruf-besar"><?= $lp->nama_produk; ?></td>
                                        <td class="text-center huruf-besar"><?= $lp->satuan_dasar; ?></td>
                                        <?php
                                            $ambil_stok_gudang = $this->Model_gudang->get_produk_stok_gudang($id_gudang, $lp->id_produk);
                                            if($ambil_stok_gudang){
                                                if($ambil_stok_gudang[0]->id_produk == $lp->id_produk){
                                                    $stok_produk = (int)$ambil_stok_gudang[0]->stok_in - (int)$ambil_stok_gudang[0]->stok_out;
                                                }else{
                                                    $stok_produk = 0;
                                                }
                                            }else{
                                                $stok_produk = 0;
                                            }
                                        ?>
                                        <!--
                                        <td class="text-center">
                                            <?= number_format($stok_produk, 0, ',', '.'); ?>
                                        </td>
                                        -->
                                        <td class="text-center">
                                            <input type="number" class="detailInput text-center" id="stok_real_<?= $baris ?>" name="stok_real_<?= $baris ?>" placeholder="Ketik Stok Real" />
                                        </td>
                                        <input type="hidden" name="itung" value="<?= $baris ?>" />
                                        <input type="hidden" name="stok_now_<?= $baris ?>" value="<?= $stok_produk ?>" />
                                        <input type="hidden" name="id_produk_<?= $baris ?>" value="<?= $lp->id_produk ?>" />
                                        <input type="hidden" name="nama_produk_<?= $baris ?>" value="<?= $lp->nama_produk ?>" />
                                    </tr>
                        <?php 
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <br>
        <div class="row d-flex justify-content-center">
            <div class="col-lg-12 text-right">
                <!--<input type="hidden" name="id_gudang" value="<?= $id_gudang ?>" readonly/>-->
                <!--<input type="hidden" name="nama_gudang" value="<?= $nama_gudang ?>" readonly/>-->
                <button type="submit" class="btn btn-lg btn-primary mb-3 font-weight-bold">Submit Data Opname</button>
            </div>
        </div>
        </form>
    </section>
</div>


<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.10/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#tabel-tambah-opname').DataTable({
            "paging": false, 
            "searching": true, 
            "ordering": true,
            scrollX: true,
            autoWidth: false,
        });
    });
</script>