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
		
		input{
		    text-transform: uppercase;
		}
    </style>
</header>

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="<?= base_url(); ?>/mutasi_divisi/tambah">
                            <div class=" form-row">
                                <div class="form-group col-md-12">
                                    <label for="no_faktur">No Faktur</label>
                                    <input type="text" class="form-control text-center" id="no_faktur" name="no_faktur" value="<?= $kode_faktur; ?>" readonly>
                                    <input type="hidden" id="kode_urut" name="kode_urut" value="<?= $urut; ?>">
                                </div>
                            </div>
                            <div class=" form-row">
                                <div class=" form-group col-md-6">
                                    <label for="tanggal_faktur">Tanggal Faktur</label>
                                    <input type="text" class="form-control text-center" id="tanggal_faktur" name="tanggal_faktur" value="<?= date_now(); ?>" readonly />
                                </div>
                                <div class=" form-group col-md-6">
                                    <label for="gudang">Gudang</label>
                                    <input type="text" class="form-control text-center" id="gudang" name="gudang" value="<?= $nama_gudang ?>" readonly />
                                </div>
                            </div>
                            <div class=" form-row">
                                <div class=" form-group col-md-6">
                                    <label for="dari_divisi">Dari Divisi</label>
                                    <select class="form-control select2" id="dari_divisi" name="dari_divisi">
                                        <option value="0">-- PILIH DIVISI --</option>
                                        <?php foreach ($list_divisi as $row) : ?>
                                            <option value="<?= $row->id ?>"><?= $row->nama ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class=" form-group col-md-6">
                                    <label for="ke_divisi">Ke Divisi</label>
                                    <select class="form-control select2" id="ke_divisi" name="ke_divisi">
                                        <option value="0">-- PILIH DIVISI --</option>
                                        <?php foreach ($list_divisi as $row) : ?>
                                            <option value="<?= $row->id ?>"><?= $row->nama ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class=" form-row">
                                <div class=" form-group col-md-6">
                                    <label for="karyawan">Nama karyawan</label>
                                    <select class="form-control select2" id="karyawan" name="karyawan">
                                        <option value="0" selected>PILIH NAMA KARYAWAN</option>
                                        <?php foreach ($list_karyawan as $karyawan) : ?>
                                            <option value="<?= $karyawan['id'] ?>"><?= $karyawan['nama'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?= form_error('karyawan', '<small class="form-text text-danger">', '</small>'); ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="lampiran">Lampiran</label>
                                    <input type="text" class="form-control" id="lampiran" name="lampiran" autocomplete="off" placeholder="Masukkan Link Lampiran">
                                </div>
                            </div>
                            <div class=" form-row">
                                <div class="form-group col-md-12">
                                    <label for="catatan">Catatan</label>
                                    <textarea class="form-control " id="catatan" name="catatan"></textarea>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class=" form-group col-md-12">
                                    <label>Tambah Produk</label>
                                    <table id="tbl_add_transaksi" style="font-family:nunito; font-color:black; font-size:15px" border="0" width="100%">
                                            <tr>
                                                <th style="text-align:left; color:black;" width="70%" > Produk </th>
                                                <th style="text-align:left; color:black;" width="30%" > Jumlah </th>
                                            </tr>
                						    <tr>
                						        <td>
                                                    <select class="form-control select2" id="produk_transaksi" name="produk_transaksi" >
                                                        <option value="0" selected>Pilih Produk</option>
                                                        <?php foreach ($list_produk as $row) : ?>
                                                            <option value="<?= $row['id'].'PengHubunG'.$row['nama'] ?>" > <?= $row['nama'] ?> </option>
                                                        <?php endforeach; ?>
                                                    </select>
                						        </td>
                						        <td>
                						            <input class="myinput text-center" type="number" id="jumlah_transaksi" name="jumlah_transaksi" >
                						        </td>
                						    </tr>
                			        </table>
                			        <br>
                			        <button type="button" class="btn btn-success" onclick="tambahRow()">Tambahkan</button>
            			        </div>
            			    </div>
            			    <div class="form-row">
                                <div class=" form-group col-md-12">
                                    <label for="resep">List Transaksi Mutasi</label>
                                    <table class="table table-bordered table-light shadow-sm p-3 mb-5 bg-white rounded dt-responsive nowrap" id="tabel-khusus" width="100%">
                                        <thead>
                                            <tr>
                                                <th class="bg-primary" style="text-align:center; color:white;" > No. </th>
                                                <th class="bg-primary" style="text-align:center; color:white;" > Produk </th>
                                                <th class="bg-primary" style="text-align:center; color:white;" > Jumlah </th>
                                                <th class="bg-primary" style="text-align:center; color:white;" > Aksi </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                			        </table>
            			        </div>
            			    </div>
        			        <br>
        			        <input type="hidden" id="itung" name="itung">
                            <a href="<?= base_url(); ?>mutasi_divisi/mutasi_divisi" class="btn btn-danger">Kembali</a>
                            <button type="submit" class="btn btn-primary float-right">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


//ambil harga jual
<?php
    foreach($list_produk as $lisprod){
        $cek_harga = $this->Model_harga_jual->get_by_idProduk_bulan($lisprod['id'] ,date("m"));
        if($cek_harga){
            $harga = $cek_harga[0]->harga_jual;
        }else{
            $harga = 0;
        }
        echo '<input type="hidden" id="harga_jual_'.$lisprod['id'].'" name="harga_jual_'.$lisprod['id'].'" value="'. $harga .'" />';
    }
?>

<script>
    var baris = 0;

    function tambahRow() {
        var table   = document.getElementById("tabel-khusus");
        var produk  = document.getElementById('produk_transaksi').value;
            const isi   = produk.split("PengHubunG");
    		    let id  = isi[0],
    			nama    = isi[1];
        var jumlah  = document.getElementById('jumlah_transaksi').value;
        var btn_name= "btn_delete";
        baris       = parseInt(baris) + 1;
        
        var row     = table.insertRow();
        var first   = row.insertCell(0);
        var cell1   = row.insertCell(1);
        var cell2   = row.insertCell(2);
        var last    = row.insertCell(3);
        first.innerHTML = "<center>"+baris+"</center>";
        cell1.innerHTML = "<input type='hidden' name='id_produk_"+baris+"' value='"+id+"'/>"+
                            "<input class='detailInput' type='text' name='produk_"+baris+"' style='width:100%; text-align:center' value='"+nama+"' readonly/>";
        cell2.innerHTML = "<input class='detailInput' type='text' name='jumlah_"+baris+"' style='width:100%; text-align:center' value='"+jumlah+"' readonly/>";
        last.innerHTML = "<center><button type='button' name='btn_delete' id='btn_delete_"+baris+"' onclick='hapusRow("+baris+")' class='btn btn-sm btn-danger text-light'><i class='fas fa-trash-alt'></i></button></center>";
        
        document.getElementById('itung').value = baris;
        document.getElementById('jumlah_transaksi').value = "";
        document.querySelector('#produk_transaksi').value = 0;
    }
    
    function hapusRow(baris) {
        var table = document.getElementById('tabel-khusus');
        table.deleteRow(baris);
    }
    
    document.addEventListener('DOMContentLoaded', function() {
	    Array.prototype.forEach.call(document.getElementsByName('produk_transaksi'),
    		function (elem) {
    			elem.addEventListener('change', function() {
    				let text	= this.value;
    				const isi 	= text.split("PengHubunG");
    				let id		= isi[0],
    					harga 	= isi[4];
    				
    				document.getElementById('harga_satuan').value = harga;
    	    });
        });
    });

</script>