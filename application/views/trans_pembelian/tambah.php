<header class="page-header">
    <style>
        .myinput{
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
		
		.up-case{
		    text-transform: uppercase;
		}
		
		.no-break {
            white-space: nowrap;
        }
		
		.table-container {
            overflow-x: auto;
            width: 100%;
        }
		
		#tabel-add-pembelian td:first-child {
            text-align: center;
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
                        <form method="POST" action="<?= base_url(); ?>/pembelian/tambah">
                            <div class=" form-row">
                                <div class="form-group col-md-6">
                                    <label for="no_faktur">No Faktur</label>
                                    <input type="text" class="form-control text-center" id="no_faktur" name="no_faktur" autocomplete="off" value="<?= $kode_faktur; ?>" readonly>
                                    <input type="hidden" id="kode_urut" name="kode_urut" value="<?= $urut; ?>">
                                </div>
                                <div class=" form-group col-md-6">
                                    <label for="tanggal_faktur">Tanggal Faktur</label>
                                    <input type="date" class="form-control" id="tanggal_faktur" name="tanggal_faktur" autocomplete="off" placeholder="Pilih Tanggal" >
                                    <?= form_error('tanggal_faktur', '<small class="form-text text-danger">', '</small>'); ?>
                                </div>
                            </div>
                            <div class=" form-group">
                                    <label for="no_invoice">No Invoice</label>
                                    <input type="text" class="form-control" id="no_invoice" name="no_invoice" autocomplete="off" placeholder="" >
                                    <?= form_error('no_invoice', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class=" form-row">
                                <div class=" form-group col-md-6">
                                    <label for="supplier">Nama Supplier</label>
                                    <select class="form-control select2" id="supplier" name="supplier">
                                        <option disabled selected>Pilih Nama Supplier</option>
                                        <?php foreach ($list_supplier as $supplier) : ?>
                                            <option value="<?= $supplier['id'] ?>"><?= $supplier['nama'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?= form_error('supplier', '<small class="form-text text-danger">', '</small>'); ?>
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
                                    <label for="gudang">Ke Gudang</label>
                                    <?php
                                        $selectDisable = "";
                                        if(active_role()=="admin_entitas"){
                                            $selectDisable = "disabled";
                                        }
                                    ?>
                                    <select class="form-control select2" id="gudang" name="gudang" <?= $selectDisable ?> >
                                        <option>Pilih Tujuan Gudang</option>
                                        <?php 
                                            foreach ($list_gudang as $gudang){
                                                $optionSelected = "";
                                                if(active_role()=="admin_entitas" && activeOutlet()==$gudang['id']){
                                                    $optionSelected = "selected";
                                                }
                                                echo '<option value="'.$gudang['id'].'" '.$optionSelected.'> '.$gudang['nama'].' </option>';
                                            }
                                        ?>
                                    </select>
                                    <?= form_error('gudang', '<small class="form-text text-danger">', '</small>'); ?>
                                </div>
                                <div class=" form-group col-md-6">
                                    <label for="jenis_bayar">Jenis Pembayaran</label>
                                    <select class="form-control select2" id="jenis_bayar" name="jenis_bayar">
                                        <option value="" selected>Pilih Jenis Pembayaran</option>
                                        <option value="CASH">CASH</option>
                                        <option value="CREDIT">CREDIT</option>
                                        <option value="TRANSFER BCA">TRANSFER BCA</option>
                                        <option value="TRANSFER BNI">TRANSFER BNI</option>
                                        <option value="TRANSFER BRI">TRANSFER BRI</option>
                                        <option value="TRANSFER BSI">TRANSFER BSI</option>
                                        <option value="TRANSFER MANDIRI">TRANSFER MANDIRI</option>
                                    </select>
                                    <?= form_error('jenis_bayar', '<small class="form-text text-danger">', '</small>'); ?>
                                </div>
                            </div>
                            <div class=" form-row">
                                <div class=" form-group col-md-6">
                                    <label for="tanggal_bayar">Tanggal Bayar</label>
                                    <input type="date" class="form-control" id="tanggal_bayar" name="tanggal_bayar" autocomplete="off" >
                                    <?= form_error('tanggal_bayar', '<small class="form-text text-danger">', '</small>'); ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="lampiran">Lampiran</label>
                                    <input type="text" class="form-control" id="lampiran" name="lampiran" autocomplete="off" placeholder="Masukkan Link Lampiran">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class=" form-group col-md-12">
                                    <label>Tambah Transaksi Pembelian</label>
                                    <table style="font-family:nunito; font-color:black; font-size:15px" border="0" width="100%">
                                            <tr>
                                                <th style="text-align:left; color:black;" width="80%"> Produk </th>
                                                <th style="text-align:left; color:black;" width="20%"> Satuan Dasar </th>
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
                						            <input class="myinput" id="satuan_dasar" style="text-align:center; color:black;" readonly/>
                						        </td>
                						    </tr>
                				    </table>
                				    <table style="font-family:nunito; font-color:black; font-size:15px" border="0" width="100%">
                						    <tr>
                                                <th style="text-align:left; color:black;" width="50%"> Jumlah </th>
                                                <th style="text-align:left; color:black;" width="50%"> Total Harga Beli</th>
                                            </tr>
                                            <tr>
                                                <td>
                						            <input class="myinput thousand-separator" type="text" id="jumlah_transaksi" name="jumlah_transaksi" style="width:100%;text-align:center">
                						        </td>
                                                <td>
                                                    <input class="myinput thousand-separator" type="text" id="harga_beli" name="harga_beli" style="width:100%;text-align:center">
                                                </td>
                						    </tr>
                			        </table>
                			        <br>
                			        <button type="button" class="btn btn-success" onclick="tambahRow()">Tambahkan</button>
            			        </div>
            			    </div>
            			    <div class="form-row">
                                <div class=" form-group col-md-12">
                                    <label for="resep">List Produk Ditambahkan</label>
                                    <div class="table-container">
                                        <table class="table table-bordered table-light shadow-sm p-3 mb-5 bg-white rounded nowrap" id="tabel-add-pembelian" width="100%">
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
                                            <tbody id="mytbody">
                    						  
                                            </tbody>
                    			        </table>
                    			    </div>
            			        </div>
            			    </div>
        			        <br>
        			        <input type="hidden" id="itung" name="itung">
                            <a href="<?= base_url(); ?>pembelian/pembelian" class="btn btn-danger">Kembali</a>
                            <button type="submit" class="btn btn-primary float-right">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<script>
    // declaration
    const table = document.getElementById("tabel-add-pembelian");
    const produkInput = document.getElementById('produk_transaksi');
    const satuanDasarInput = document.getElementById('satuan_dasar');
    
    // add row
    let baris = 0;
    function tambahRow() {
        const [id, nama, satuan] = produkInput.value.split("PengHubunG");
        const jumlah = parseInt(document.getElementById('jumlah_transaksi').value.replace(/\D/g, ""));
        const harga_beli = parseInt(document.getElementById('harga_beli').value.replace(/\D/g, ""));
        const formattedHargaBeli = ThousandSeparator(harga_beli);
        const formattedJumlah = ThousandSeparator(jumlah);
        const formattedHargaSatuan = ThousandSeparator(formatToFixed((harga_beli/jumlah).toFixed(2)));
        
        baris += 1;
        const newRow = table.insertRow();
        newRow.innerHTML = `
            <td><center>${baris}</center></td>
            <td>
                <label class="text-dark no-break up-case">${nama}</label>
                <input class='detailInput no-break' type='hidden' name='produk_${baris}' style='width:100%; text-align:center' value='${nama}' readonly/></td>
                <input type='hidden' name='id_produk_${baris}' value='${id}'/>
            <td>
                <label class="text-dark no-break">${formattedJumlah}</label>
                <input type='hidden' name='jumlah_${baris}' style='width:100%; text-align:center' value='${jumlah}' readonly/>
            </td>
            <td><input class='detailInput' type='text' name='satuan_${baris}' style='width:100%; text-align:center' value='${satuan}' readonly/></td>
            <td>
                <label class='uang text-dark no-break'>Rp. ${formattedHargaSatuan}</label>
                <input type='hidden' name='harga_satuan_${baris}' style='width:100%; text-align:center' value='${(harga_beli / jumlah)}' readonly/>
            </td>
            <td>
                <label class='uang text-dark no-break'>Rp. ${formattedHargaBeli}</label>
                <input type='hidden' name='harga_beli_${baris}' value='${harga_beli}'></td>
            <td>
                <center>
                    <button type='button' name='btn_delete' onclick='hapusRow(this)' class='btn btn-sm btn-danger text-light'>
                        <i class='fas fa-trash-alt'></i>
                    </button>
                </center>
            </td>
        `;

        document.getElementById('itung').value = baris;
        document.getElementById('jumlah_transaksi').value = "";
        document.getElementById('harga_beli').value = "";
        $(produkInput).val("0").trigger('change.select2');
        satuanDasarInput.value = "";
    }
    
    // delete row
    function hapusRow(rowElement) {
        const row = $(rowElement).closest("tr");
    
        table.deleteRow(row[0].rowIndex);
    
        // Renumber the rows
        const rows = table.getElementsByTagName("tr");
        for (let i = 1; i < rows.length; i++) {
            rows[i].getElementsByTagName("td")[0].innerText = i;
        }
    
        baris = rows.length - 1;
    }
    
    // autoset satuan dasar on produk change
    document.addEventListener('DOMContentLoaded', function() {
        $(produkInput).select2();

        $(produkInput).on('select2:select', function(event) {
            const selectedOption = event.params.data;
            const [id, nama, satuanDasar] = selectedOption.id.split("PengHubunG");
            satuanDasarInput.value = satuanDasar;
        });
    });
    
    // auto thousand separator
    function addThousandSeparator(value) {
        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    function keepOnlyNumbers(input) {
        return input.replace(/[^0-9]/g, '');
    }
    const thousandSeparatorInputs = document.querySelectorAll('.thousand-separator');
    thousandSeparatorInputs.forEach(input => {
        input.addEventListener('input', function(event) {
            let inputValue = event.target.value;
            inputValue = keepOnlyNumbers(inputValue);

            const formattedValue = addThousandSeparator(inputValue);
            event.target.value = formattedValue;
        });
    });
    
    // thousand separator change
    function ThousandSeparator(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
    // change toFixed separator
    function formatToFixed(number) {
        const [integerPart, decimalPart] = number.toString().split('.');
        const formattedIntegerPart = ThousandSeparator(integerPart);
        const formattedDecimalPart = decimalPart ? ',' + decimalPart : '';
        return formattedIntegerPart + formattedDecimalPart;
    }
</script>
