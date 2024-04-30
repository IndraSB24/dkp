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
                        <form method="POST" action="<?= base_url(); ?>/pemakaian_barang/tambah">
                            <div class=" form-row">
                                <div class="form-group col-md-12">
                                    <label for="no_faktur">No Faktur</label>
                                    <input type="text" class="form-control text-center" id="no_faktur" name="no_faktur" autocomplete="off" value="<?= $kode_faktur; ?>" readonly>
                                </div>
                            </div>
                            <div class=" form-row">
                                <div class=" form-group col-md-6">
                                    <label for="tanggal_faktur">Tanggal Faktur</label>
                                    <input type="date" class="form-control datepicker" id="tanggal_faktur" name="tanggal_faktur" autocomplete="off" placeholder="Pilih Tanggal" >
                                    <?= form_error('tanggal_faktur', '<small class="form-text text-danger">', '</small>'); ?>
                                </div>
                                <div class=" form-group col-md-6">
                                    <label for="karyawan">Nama karyawan</label>
                                    <select class="form-control select2" id="karyawan" name="karyawan">
                                        <option value="" selected>Pilih Nama Karyawan</option>
                                        <?php foreach ($list_karyawan as $karyawan) : ?>
                                            <option value="<?= $karyawan['id'] ?>"><?= $karyawan['nama'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?= form_error('karyawan', '<small class="form-text text-danger">', '</small>'); ?>
                                </div>
                            </div>
                            <div class=" form-row">
                                <div class=" form-group col-md-6">
                                    <label for="gudang">Dari Gudang</label>
                                    <select class="form-control select2" id="gudang" name="gudang">
                                        <option value="0" selected>Pilih Gudang</option>
                                        <?php foreach ($list_gudang as $gudang) : ?>
                                            <option value="<?= $gudang['id'] ?>"><?= $gudang['nama'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?= form_error('dari_gudang', '<small class="form-text text-danger">', '</small>'); ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="lampiran">Lampiran</label>
                                    <input type="text" class="form-control" id="lampiran" name="lampiran" autocomplete="off" placeholder="Masukkan Link Lampiran">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class=" form-group col-md-12">
                                    <label>Tambah Transaksi Pemakaian Barang</label>
                                    <table id="tbl_add_transaksi" style="font-family:nunito; font-color:black; font-size:15px" border="0" width="100%">
                                            <tr>
                                                <th style="text-align:left; color:black;" width="70%"> Produk </th>
                                                <th style="text-align:left; color:black;" width="30%"> Satuan Dasar </th>
                                            </tr>
                						    <tr>
                						        <td>
                                                    <select class="form-control select2" id="produk_transaksi" name="produk_transaksi" >
                                                        <option value="0" selected>Pilih Bahan</option>
                                                        <?php foreach ($list_produk as $row) : ?>
                                                            <option value="<?= $row['id'].'PengHubunG'.$row['nama'].'PengHubunG'.$row['satuan_dasar'] ?>" > <?= $row['nama'] ?> </option>
                                                        <?php endforeach; ?>
                                                    </select>
                						        </td>
                						        <td>
                						            <input class="myinput" id="satuan_dasar" style="text-align:center; color:black;" >
                						        </td>
                						    </tr>
                						    <tr>
                                                <th colspan="2" style="text-align:left; color:black;" width="100%"> Jumlah </th>
                                            </tr>
                                            <tr>
                						        <td colspan="2">
                						            <input class="myinput" type="number" id="jumlah_transaksi" name="jumlah_transaksi" style="width:100%;text-align:center">
                						        </td>
                						    </tr>
                                        
                			        </table>
                			        <br>
                			        <button type="button" class="btn btn-success" onclick="tambahRow()">Tambahkan</button>
            			        </div>
            			    </div>
            			    <div class="form-row">
                                <div class=" form-group col-md-12">
                                    <label for="resep">List Pemakaian Barang</label>
                                    <table class="table table-bordered table-light shadow-sm p-3 mb-5 bg-white rounded dt-responsive nowrap" id="tabel-tambah" width="100%">
                                        <thead>
                                            <tr>
                                                <th class="bg-primary" style="text-align:center; color:white;" > No. </th>
                                                <th class="bg-primary" style="text-align:center; color:white;" > Produk </th>
                                                <th class="bg-primary" style="text-align:center; color:white;" > Jumlah </th>
                                                <th class="bg-primary" style="text-align:center; color:white;" > Satuan </th>
                                                <th class="bg-primary" style="text-align:center; color:white;" > Aksi </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                						    <tr>
                						        <td colspan="6"></td>
                						    </tr>
                                        </tbody>
                			        </table>
            			        </div>
            			    </div>
        			        <br>
        			        <input type="hidden" id="itung" name="itung">
                            <a href="<?= base_url(); ?>pemakaian_barang/Pemakaian_barang" class="btn btn-danger">Kembali</a>
                            <button type="submit" class="btn btn-primary float-right">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<script>
    var baris = 0;

    function tambahRow() {
        var table   = document.getElementById("tabel-tambah");
        var produk  = document.getElementById('produk_transaksi').value;
            const isi   = produk.split("PengHubunG");
    		    let id  = isi[0],
    			nama    = isi[1];
        var jumlah  = document.getElementById('jumlah_transaksi').value;
        var satuan  = document.getElementById('satuan_dasar').value;
        
        baris       = parseInt(baris) + 1;
        
        var row     = table.insertRow();
        var first   = row.insertCell(0);
        var cell1   = row.insertCell(1);
        var cell2   = row.insertCell(2);
        var cell3   = row.insertCell(3);
        var last    = row.insertCell(4);
        first.innerHTML = "<center>"+baris+"</center>";
        cell1.innerHTML = "<input type='hidden' name='id_produk_"+baris+"' value='"+id+"'/>"+
                            "<input class='detailInput' type='text' name='produk_"+baris+"' style='width:100%; text-align:center' value='"+nama+"' readonly/>";
        cell2.innerHTML = "<input class='detailInput' type='text' name='jumlah_"+baris+"' style='width:100%; text-align:center' value='"+jumlah+"' readonly/>";
        cell3.innerHTML = "<input class='detailInput' type='text' name='satuan_dasar_"+baris+"' style='width:100%; text-align:center' value='"+satuan+"' readonly/>";
        last.innerHTML  = "<center><button type='button' name='btn_delete' id='btn_delete_"+baris+"' onclick='hapusRow("+baris+")' class='btn btn-sm btn-danger text-light'><i class='fas fa-trash-alt'></i></button></center>";
        
        document.getElementById('itung').value = baris;
        document.getElementById('jumlah_transaksi').value = "";
        document.getElementById('satuan_dasar').value = "";
        document.querySelector('#produk_transaksi').value = 0;
    }
    
    function hapusRow(baris) {
        var table = document.getElementById('tabel-produk');
        table.deleteRow(baris);
    }
    
    document.addEventListener('DOMContentLoaded', function() {
	    Array.prototype.forEach.call(document.getElementsByName('produk_transaksi'),
    		function (elem) {
    			elem.addEventListener('change', function() {
    				let text	= this.value;
    				const isi 	= text.split("PengHubunG");
    				let id		= isi[0],
    					satuan 	= isi[2];
    				
    				document.getElementById('satuan_dasar').value = satuan;
    	    });
        });
    });

</script>

<script>
    $(".select2").select2();
</script>