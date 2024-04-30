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
    </style>
</header>

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="<?= base_url(); ?>/formula_produk/tambah">
                            <div class=" form-group">
                                <label for="produk">Nama Produk</label>
                                <select class="form-control select2" id="produk" name="produk">
                                    <option disabled selected>Pilih Nama Produk</option>
                                    <?php foreach ($list_produk as $row) : ?>
                                        <option value="<?= $row['id'] ?>"><?= $row['nama'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?= form_error('kategori', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class=" form-group">
                                <label for="resep">Pilih Formula Produk</label>
                                <table id="tbl_add_resep" style="font-family:nunito; font-color:black; font-size:15px" border="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align:left; color:black;" width="70%"> Bahan </th>
                                            <th style="text-align:left; color:black;" width="15%"> Jumlah </th>
                                            <th style="text-align:left; color:black;" width="15%"> Satuan Dasar </th>
                                        </tr>
                                    </thead>
                                    <tbody>
            						    <tr>
            						        <td>
            						            <input type="hidden" id="val_bahan" name="val_bahan" />
                                                <select class="form-control select2" id="bahan" name="bahan">
                                                    <option disabled selected>Pilih Bahan</option>
                                                    <?php foreach ($list_bahan as $row) : ?>
                                                        <option value="<?= $row['id'].'PengHubunG'.$row['satuan_dasar'].'PengHubunG'.$row['nama'] ?>" > <?= $row['nama'] ?> </option>
                                                    <?php endforeach; ?>
                                                </select>
            						        </td>
            						        <td>
            						            <input class="myinput" type="number" id="jumlah" name="jumlah" style="width:100%; text-align:center">
            						        </td>
            						        <td>
            						            <input class="myinput" type="text" id="satuan" name="satuan" style="width:100%; text-align:center" readonly>
            						        </td>
            						    </tr>
                                    </tbody>
            			        </table>
            			        <br>
            			        <button type="button" class="btn btn-success" onclick="tambahRow()">Tambahkan</button>
        			        </div>
                            <div class=" form-group">
                                <label for="resep">List Formula Produk</label>
                                <table class="table table-bordered table-light shadow-sm p-3 mb-5 bg-white rounded dt-responsive nowrap" id="tabel-produk" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="bg-primary" style="text-align:center; color:white;" > No. </th>
                                            <th class="bg-primary" style="text-align:center; color:white;" > Bahan </th>
                                            <th class="bg-primary" style="text-align:center; color:white;" > Jumlah </th>
                                            <th class="bg-primary" style="text-align:center; color:white;" > Satuan Dasar </th>
                                            <th class="bg-primary" style="text-align:center; color:white;" > Aksi </th>
                                        </tr>
                                    </thead>
                                    <tbody>
            						
                                    </tbody>
            			        </table>
        			        </div>
        			        <br>
        			        <input type="hidden" id="itung" name="itung">
                            <a href="<?= base_url(); ?>formula_produk/formula_produk" class="btn btn-danger">Kembali</a>
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
        var table   = document.getElementById("tabel-produk");
        var bahan   = document.getElementById('bahan').value;
            const isi   = bahan.split("PengHubunG");
    		    let id  = isi[0],
    			satuan  = isi[1],
    			nama    = isi[2];
        var jumlah  = document.getElementById('jumlah').value;
        var btn_name= "btn_delete";
        baris       = parseInt(baris) + 1;
        
        var row     = table.insertRow();
        var cell0   = row.insertCell(0);
        var cell1   = row.insertCell(1);
        var cell2   = row.insertCell(2);
        var cell3   = row.insertCell(3);
        var cell4   = row.insertCell(4);
        cell0.innerHTML = "<center>"+baris+"</center>";
        cell1.innerHTML = "<input type='hidden' name='id_bahan_"+baris+"' value='"+id+"'>"+
                            "<input class='detailInput' type='text' name='bahan_"+baris+"' style='width:100%; text-align:center' value='"+nama+"' readonly>";
        cell2.innerHTML = "<input class='detailInput' type='text' name='jumlah_"+baris+"' style='width:100%; text-align:center' value='"+jumlah+"' readonly>";
        cell3.innerHTML = "<input class='detailInput' type='text' name='satuan_"+baris+"' style='width:100%; text-align:center' value='"+satuan+"' readonly>";
        cell4.innerHTML = "<center><button type='button' name='btn_delete' id='btn_delete_"+baris+"' onclick='hapusRow1("+baris+")' class='btn btn-sm btn-danger text-light'><i class='fas fa-trash-alt'></i></button></center>";
        
        document.getElementById('itung').value = baris;
    }
    
    function hapusRow(baris) {
        var table = document.getElementById('tabel-produk');
        table.deleteRow(baris);
    }
    
    function hapusRow1(baris) {
        var table = document.getElementById('tabel-produk');
        table.deleteRow(baris);
        
        var itung = document.getElementById('itung').value;
        const btn = [];
        var ada = 1;
        for(var i=1; i<10; i++){
            if(document.getElementById('btn_delete_'+i) != undefined){
                document.getElementById('btn_delete_'+i).onclick='hapusRow1('+ada+')';
                ada = parseInt(ada);
                ada = ada + 1;
            }
        }
    }
    
    function removeRow(btn_delete) {  
        try {  
            var table = document.getElementById('tabel-produk');  
            var rowCount = table.rows.length;  
            for (var i = 0; i < rowCount; i++) {  
                var row = table.rows[i];
                var rowObj = row.cells[4];
                if (rowObj.name == btn_delete) {  
                    table.deleteRow(i);  
                    rowCount--;  
                }  
            }  
        }  
        catch (e) {  
            alert(e);  
        }  
    }
    
    document.addEventListener('DOMContentLoaded', function() {
	    Array.prototype.forEach.call(document.getElementsByName('bahan'),
    		function (elem) {
    			elem.addEventListener('change', function() {
    				let text	= this.value;
    				const isi 	= text.split("PengHubunG");
    				let id		= isi[0],
    					satuan 	= isi[1];
    				
    				document.getElementById('satuan').value = satuan;
    				document.getElementById('val_bahan').value = id;
    	    });
        });
    });

</script>

<script>
    $(".select2").select2();
</script>