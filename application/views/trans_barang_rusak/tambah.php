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
		
        .cursor-off {
            color: #555; 
            background-color: #eee; 
            pointer-events: none;
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
                        <form method="POST" action="<?= base_url(); ?>/barang_rusak/tambah">
                            <div class=" form-row">
                                <div class="form-group col-md-12">
                                    <label for="no_faktur">No Faktur</label>
                                    <input type="text" class="form-control text-center" id="no_faktur" name="no_faktur" autocomplete="off" value="<?= $kode_faktur; ?>" readonly>
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
                                    <select class="form-control select2" id="gudang" name="gudang" >
                                        <option value="0" selected>Pilih Gudang</option>
                                        <?php foreach ($list_gudang as $gudang) : ?>
                                            <option value="<?= $gudang['id'] ?>" <?= activeOutlet()==$gudang['id'] ? "selected" : "" ?>>
                                                <?= $gudang['nama'] ?>
                                            </option>
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
                                                    <select class="form-control select2 myinput" id="produk_transaksi" name="produk_transaksi" >
                                                        <option value="0" selected>Pilih Bahan</option>
                                                        <?php foreach ($list_produk as $row) : ?>
                                                            <option value="<?= $row['id'].'PengHubunG'.$row['nama'].'PengHubunG'.$row['satuan_dasar'] ?>" > <?= $row['nama'] ?> </option>
                                                        <?php endforeach; ?>
                                                    </select>
                						        </td>
                						        <td> <input class="myinput text-center" id="satuan_dasar" readonly /> </td>
                						    </tr>
                						    <tr>
                						        <th style="text-align:left; color:black;" width="70%"> Kategori </th>
                                                <th style="text-align:left; color:black;" width="30%"> Jumlah </th>
                                            </tr>
                                            <tr>
                                                <td> <input class="myinput text-center" style="color:black;" value="BUAH" readonly /> </td>
                						        <td> <input class="myinput text-center" type="number" id="jumlah_buah" name="jumlah_buah" value="0" /> </td>
                						    </tr>
                						    <tr>
                                                <td> <input class="myinput text-center" style="color:black;" value="KULIT" readonly /> </td>
                						        <td> <input class="myinput text-center" type="number" id="jumlah_kulit" name="jumlah_kulit" value="0" /> </td>
                						    </tr>
                						    <tr>
                                                <td> <input class="myinput text-center" style="color:black;" value="BONGGOL" readonly /> </td>
                						        <td> <input class="myinput text-center" type="number" id="jumlah_bonggol" name="jumlah_bonggol" value="0" /> </td>
                						    </tr>
                						    <tr>
                                                <td> <input class="myinput text-center" style="color:black;" value="LAIN - LAIN" readonly /> </td>
                						        <td> <input class="myinput text-center" type="number" id="jumlah_lain" name="jumlah_lain" value="0" /> </td>
                						    </tr>
                                        
                			        </table>
                			        <br>
                			        <button type="button" class="btn btn-success" onclick="tambahRow()">Tambahkan</button>
            			        </div>
            			    </div>
            			    <div class="form-row">
                                <div class=" form-group col-md-12">
                                    <label for="resep">List Formula Produk</label>
                                    <table class="table table-bordered table-light shadow-sm p-3 mb-5 bg-white rounded dt-responsive nowrap" id="tabel-khusus" width="100%">
                                        <thead>
                                            <tr>
                                                <th class="bg-primary text-center text-white"> No. </th>
                                                <th class="bg-primary text-center text-white"> Produk </th>
                                                <th class="bg-primary text-center text-white"> Buah </th>
                                                <th class="bg-primary text-center text-white"> Kulit </th>
                                                <th class="bg-primary text-center text-white"> Bonggol </th>
                                                <th class="bg-primary text-center text-white"> Lain-Lain </th>
                                                <th class="bg-primary text-center text-white"> Satuan </th>
                                                <th class="bg-primary text-center text-white"> Aksi </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                			        </table>
            			        </div>
            			    </div>
        			        <br>
        			        <input type="hidden" id="itung" name="itung">
                            <a href="<?= base_url(); ?>barang_rusak/barang_rusak" class="btn btn-danger">Kembali</a>
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
        var table   = document.getElementById("tabel-khusus");
        var produk  = document.getElementById('produk_transaksi').value;
            const isi   = produk.split("PengHubunG");
    		    let id  = isi[0],
    			nama    = isi[1],
    			satuan  = isi[2];
    	var buah    = document.getElementById('jumlah_buah').value;
    	var kulit   = document.getElementById('jumlah_kulit').value;
    	var bonggol = document.getElementById('jumlah_bonggol').value;
        var lain    = document.getElementById('jumlah_lain').value;
        //var satuan  = document.getElementById('satuan_dasar').value;
        
        baris       = parseInt(baris) + 1;
        
        var row     = table.insertRow();
        var first   = row.insertCell(0);
        var cell1   = row.insertCell(1);
        var cell2   = row.insertCell(2);
        var cell3   = row.insertCell(3);
        var cell4   = row.insertCell(4);
        var cell5   = row.insertCell(5);
        var cell6   = row.insertCell(6);
        var last    = row.insertCell(7);
        first.innerHTML = "<center>"+baris+"</center>";
        cell1.innerHTML = "<input type='hidden' name='id_produk_"+baris+"' value='"+id+"'/>"+
                            "<input class='detailInput text-center' type='text' name='produk_"+baris+"' value='"+nama+"' readonly/>";
        cell2.innerHTML = "<input class='detailInput text-center' type='text' name='buah_"+baris+"' value='"+buah+"' readonly/>";
        cell3.innerHTML = "<input class='detailInput text-center' type='text' name='kulit_"+baris+"' value='"+kulit+"' readonly/>";
        cell4.innerHTML = "<input class='detailInput text-center' type='text' name='bonggol_"+baris+"' value='"+bonggol+"' readonly/>";
        cell5.innerHTML = "<input class='detailInput text-center' type='text' name='lain_"+baris+"' value='"+lain+"' readonly/>";
        cell6.innerHTML = "<input class='detailInput text-center' type='text' name='satuan_dasar_"+baris+"' value='"+satuan+"' readonly/>";
        last.innerHTML  = "<center><button type='button' name='btn_delete' id='btn_delete_"+baris+"' onclick='hapusRow("+baris+")' class='btn btn-sm btn-danger text-light'><i class='fas fa-trash-alt'></i></button></center>";
        
        document.getElementById('itung').value = baris;
        document.getElementById('satuan_dasar').value = "";
        document.querySelector('#produk_transaksi').value = 0;
        document.getElementById('jumlah_buah').value = 0;
        document.getElementById('jumlah_kulit').value = 0;
        document.getElementById('jumlah_bonggol').value = 0;
        document.getElementById('jumlah_lain').value = 0;
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
    					satuan 	= isi[2];
    				
    				document.getElementById('satuan_dasar').value = text;
    	    });
        });
    });

</script>

<script>
    $(".select2").select2();
</script>