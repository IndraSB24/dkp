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
                        <form method="POST" action="<?= base_url(); ?>/produksi/tambah">
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
                                    <input type="date" class="form-control datepicker" id="tanggal_faktur" name="tanggal_faktur" autocomplete="off" placeholder="Pilih Tanggal" >
                                    <?= form_error('tanggal_faktur', '<small class="form-text text-danger">', '</small>'); ?>
                                </div>
                                <div class=" form-group col-md-6">
                                    <label for="gudang">Gudang</label>
                                    <select class="form-control select2" id="gudang" name="gudang" >
                                        <option value="0" selected>-PILIH GUDANG-</option>
                                        <?php foreach ($list_gudang as $gudang) : 
                                            $gudang['id'] == activeOutlet() ? $selected="selected" : $selected="";
                                        ?>
                                            <option value="<?= $gudang['id'] ?>" <?= $selected ?>><?= $gudang['nama'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?= form_error('gudang', '<small class="form-text text-danger">', '</small>'); ?>
                                </div>
                            </div>
                            <div class=" form-row">
                                <div class=" form-group col-md-6">
                                    <label for="karyawan">Nama karyawan</label>
                                    <select class="form-control select2" id="karyawan" name="karyawan">
                                        <option value="0" selected>-PILIH KARYAWAN-</option>
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
                            <div class="form-row">
                                <div class=" form-group col-md-12">
                                    <label>Tambah Transaksi Pembelian</label>
                                    <table id="tbl_add_transaksi" style="font-family:nunito; font-color:black; font-size:15px" border="0" width="100%">
                                            <tr>
                                                <th colspan="2" style="text-align:left; color:black;" width="80%"> Produk </th>
                                            </tr>
                						    <tr>
                						        <td colspan="2">
                                                    <select class="form-control select2" id="produk_transaksi" name="produk_transaksi" >
                                                        <option value="0" selected>Pilih Produk</option>
                                                        <?php foreach ($list_produk as $row) : 
                                                            $row_id = $row['id'];
                                                        ?>
                                                            <option value="<?= $row['id'].'PengHubunG'.$row['nama'].'PengHubunG'.$row['harga_satuan'].'PengHubunG'.$row['satuan_dasar'].'PengHubunG'.$harga_satuan[$row_id] ?>" > <?= $row['nama'] ?> </option>
                                                        <?php endforeach; ?>
                                                    </select>
                						        </td>
                						    </tr>
                						    <tr>
                						        <th style="text-align:left; color:black;" width="50%"> Harga Satuan </th>
                                                <th style="text-align:left; color:black;" width="50%"> Jumlah </th>
                                            </tr>
                                            <tr>
                                                <td>
                						            <input class="myinput text-center" type="text" id="harga_satuan" name="harga_satuan" readonly>
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
                                    <label for="resep">List Transaksi Produksi</label>
                                    <table class="table table-bordered table-light shadow-sm p-3 mb-5 bg-white rounded dt-responsive nowrap" id="tabel-khusus" width="100%">
                                        <thead>
                                            <tr>
                                                <th class="bg-primary" style="text-align:center; color:white;" > No. </th>
                                                <th class="bg-primary" style="text-align:center; color:white;" > Produk </th>
                                                <th class="bg-primary" style="text-align:center; color:white;" > Jumlah </th>
                                                <th class="bg-primary" style="text-align:center; color:white;" > Satuan </th>
                                                <th class="bg-primary" style="text-align:center; color:white;" > Harga Satuan </th>
                                                <th class="bg-primary" style="text-align:center; color:white;" > Harga Total </th>
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
        			        <input type="hidden" id="gudang_transaksi" name="gudang_transaksi">
        			        <input type="hidden" id="produk_transaksi" name="produk_transaksi">
        			        <input type="hidden" id="jumlah_transaksi" name="jumlah_transaksi">
                            <a href="<?= base_url(); ?>produksi/produksi" class="btn btn-danger">Kembali</a>
                            <button type="submit" class="btn btn-primary float-right">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
    function cek_ketersediaan_stok($id_gudang, $id_produk, $jumlah)
    {
        if($id_gudang != 0){
            $ambil_detail_produk = $this->Model_produk_formula->get_detail_formula_by_produk_no_array($id_produk);
            foreach($ambil_detail_produk as $adp){
                $jumlah_bahan = $adp->jumlah_bahan * $jumlah;
                $ambil_stok = $this->Model_gudang->get_produk_stok_gudang($id_gudang, $adp->id_bahan);
                if($ambil_stok){
                    $cek_stok = ((int)$ambil_stok[0]->stok_in - (int)$ambil_stok[0]->stok_out) - (int)$jumlah_bahan;
                    if((int)$cek_stok < 0){
                        $status = $adp->nama_produk." Kurang untuk produksi";
                    }else{
                        $status = "ok";
                    }
                }else{
                    $status = $adp->nama_produk." Tidak ada stok di Gudang";
                }
                $count_stat += 1;
            }
        }
        return $status;
    }
?>

<script>
    var baris = 0;

    function tambahRow() {
        var table   = document.getElementById("tabel-khusus");
        var produk  = document.getElementById('produk_transaksi').value;
            const isi   = produk.split("PengHubunG");
    		    let id  = isi[0],
    			nama    = isi[1],
    			satuan  = isi[3],
    			harga   = isi[4];
        var jumlah      = document.getElementById('jumlah_transaksi').value;
        var id_gudang   = document.getElementById('gudang').value;
        var btn_name    = "btn_delete";
        baris           = parseInt(baris) + 1;
        
        var row     = table.insertRow();
        var first   = row.insertCell(0);
        var cell1   = row.insertCell(1);
        var cell2   = row.insertCell(2);
        var cell3   = row.insertCell(3);
        var cell4   = row.insertCell(4);
        var cell5   = row.insertCell(5);
        var last    = row.insertCell(6);
        
        first.innerHTML = "<center>"+baris+"</center>";
        cell1.innerHTML = "<input type='hidden' name='id_produk_"+baris+"' value='"+id+"'/>"+
                            "<input class='detailInput' type='text' name='produk_"+baris+"' style='width:100%; text-align:center' value='"+nama+"' readonly/>";
        cell2.innerHTML = "<input class='detailInput' type='text' name='jumlah_"+baris+"' style='width:100%; text-align:center' value='"+jumlah+"' readonly/>";
        cell3.innerHTML = "<input class='detailInput' type='text' name='satuan_turunan_"+baris+"' style='width:100%; text-align:center' value='"+satuan+"' readonly/>";
        cell4.innerHTML = "<input class='detailInput' type='text' name='harga_satuan_"+baris+"' style='width:100%; text-align:center' value='"+harga+"' readonly/>";
        cell5.innerHTML = "Rp. <label class='uang'>"+jumlah * harga+"</label>,- ";
        last.innerHTML  = "<center><button type='button' name='btn_delete' id='btn_delete_"+baris+"' onclick='hapusRow("+baris+")' class='btn btn-sm btn-danger text-light'><i class='fas fa-trash-alt'></i></button></center>";
        
        document.getElementById('itung').value = baris;
        document.getElementById('jumlah_transaksi').value = "";
        document.getElementById('harga_satuan').value = "";
        document.querySelector('#produk_transaksi').value = 0;
    }
    
    function addRow(){
        var table   = document.getElementById("tabel-khusus");
        var produk  = document.getElementById('produk_transaksi').value;
            const isi   = produk.split("PengHubunG");
    		    let id  = isi[0],
    			nama    = isi[1],
    			satuan  = isi[3],
    			harga   = isi[4];
        var jumlah      = document.getElementById('jumlah_transaksi').value;
        var id_gudang   = document.getElementById('gudang').value;
        var btn_name    = "btn_delete";
        baris           = parseInt(baris) + 1;
        
        jQuery.ajax({
            url: 'produksi/cek_ketersediaan_stok/',
            type: 'POST',
            data: {
                id_gudang   : id_gudang,
                id_produk   : id,
                jumlah      : jumlah
            },
                error:function(data)
                {
                    alert("failed");
                    console.log(data);
                },
                success: function(data) 
                {
                    var obj = jQuery.parseJSON(data);
                    alert( obj.x );
        
                    console.log(obj); // Inspect this in your console
                }
        });
        
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

<script>
    $(".select2").select2();
</script>