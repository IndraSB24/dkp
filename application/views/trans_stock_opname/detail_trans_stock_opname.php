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
        <form action="#" method="POST">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-12 mt-4">
                <div class="card">
                    <div class="text-center huruf-besar">
                        <br>
                        <h3>  
                            <?php 
                                if($kode == "detail"){
                                    echo 'Stock Opname Bulan '.bulan_indo(date('m', strtotime($data_faktur[0]->tgl_opname))).' Tahun '.date('Y', strtotime($data_faktur[0]->tgl_opname));
                                }else if($kode == "detail_harian"){
                                    echo 'Stock Opname Harian Tanggal '.date('d-m-Y', strtotime($data_faktur[0]->tgl_opname));
                                }
                            ?>
                        </h3>
                        <br>
                        <h4> <?= $data_faktur[0]->nama_gudang ?> </h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-lg-12 mt-2">
                <div class="flash-data" data-flashdata="<?= $this->session->flashdata('pesan'); ?>"></div>
                <table class="table table-bordered table-light shadow-sm p-3 mb-5 bg-white rounded dt-responsive nowrap" id="tabel-produk" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Produk</th>
                            <th class="text-center">Satuan Dasar</th>
                            <th class="text-center">Stock Akhir</th>
                            <th class="text-center">Stock Real (editable)</th>
                            <th class="text-center">Selisih</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <?php
                            $baris  = 0;
                            foreach($list_detail as $row){
                                $baris = $baris + 1;
                        ?>
                                    <tr>
                                        <td class="text-center"><?= $baris ?></td>
                                        <td class="text-center huruf-besar"><?= $row->nama_produk; ?></td>
                                        <td class="text-center huruf-besar">-</td>
                                        <td class="text-center">
                                            <?= number_format($row->stok_sistem, 0, ',', '.'); ?>
                                        </td>
                                        <td class="text-center bg-editable">
                                            <input type="number" class="text-center detailInput" name="stok_real[<?= $baris ?>]" value="<?= $row->stok_nyata ?>" />
                                        </td>
                                        <td class="text-center">
                                            <?= number_format($row->stok_nyata - $row->stok_sistem, 0, ',', '.'); ?>
                                        </td>
                                    </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
            if(substr(hak_akses(),0,3)=="SPV"){
                $lokasi = substr(hak_akses(),4);
            }else if(substr(hak_akses(),0,3)=="ADM"){
                $lokasi = substr(hak_akses(),6);
            }
            
            if($lokasi==$data_faktur[0]->nama_gudang && !isAdmin()){
        ?>
            <div class="row d-flex justify-content-center pt-3">
                <div class="col-lg-12 text-right">
                    <button type="submit" class="btn btn-lg btn-primary mb-3 font-weight-bold">Konfirmasi Detail Opname</button>
                </div>
            </div>
        <?php }else{ ?>
            <div class="row d-flex justify-content-center pt-3">
                <div class="col-lg-12 text-right">
                    <button type="submit" class="btn btn-lg btn-primary mb-3 font-weight-bold">Update Detail Opname</button>
                </div>
            </div>
        <?php } ?>
        </form>
    </section>
</div>

<script>
    
   
</script>