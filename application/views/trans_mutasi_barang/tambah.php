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
		
		.table-container {
            overflow-x: auto;
            width: 100%;
        }
		
		#tabel-add-mutasi td:first-child {
            text-align: center;
        }
        
        .no-break {
            white-space: nowrap;
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
                        <?php
                            if($kode_tambah == "permintaan"){
                                echo '<form method="POST" action="'.base_url().'/mutasi/tambah/permintaan">';
                            }else if($kode_tambah == "mutasi"){
                                echo '<form method="POST" action="'.base_url().'/mutasi/tambah/mutasi_langsung">';
                            }
                        ?>
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
                                    <?php 
                                        if(isAdmin()){
                                            echo '<input type="date" class="form-control" id="tanggal_faktur" name="tanggal_faktur" autocomplete="off" placeholder="Pilih Tanggal" value="'.date_now().'" >';
                                        }else{
                                            echo '<input type="text" class="form-control text-center" id="tanggal_faktur" name="tanggal_faktur" value="'.date_now().'" readonly />';
                                        }
                                    ?>
                                </div>
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
                            </div>
                            <div class=" form-row">
                                <div class=" form-group col-md-6">
                                    <label for="dari_gudang">Dari Gudang</label>
                                    <select class="form-control select2" id="dari_gudang" name="dari_gudang">
                                        <option value="0" selected>Pilih Gudang</option>
                                        <?php foreach ($list_gudang as $gudang) : ?>
                                            <option value="<?= $gudang['id'] ?>"><?= $gudang['nama'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?= form_error('dari_gudang', '<small class="form-text text-danger">', '</small>'); ?>
                                </div>
                                <div class=" form-group col-md-6">
                                    <label for="ke_gudang">Ke Gudang</label>
                                    <select class="form-control select2" id="ke_gudang" name="ke_gudang">
                                        <option value="0">Pilih Gudang</option>
                                        <?php
                                            foreach($list_gudang as $row){
                                                echo '<option value="'.$row['id'].'">'.$row['nama'].'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class=" form-row">
                                <div class="form-group col-md-6">
                                    <label for="lampiran">Lampiran</label>
                                    <input type="text" class="form-control" id="lampiran" name="lampiran" autocomplete="off" placeholder="Masukkan Link Lampiran">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class=" form-group col-md-12">
                                    <label>Tambah Transaksi Mutasi</label>
                                    <table id="tbl_add_transaksi" style="font-family:nunito; font-color:black; font-size:15px" border="0" width="100%">
                                            <tr>
                                                <th style="text-align:left; color:black;" width="50%" > Produk </th>
                                                <th style="text-align:left; color:black;" width="20%" > Satuan </th>
                                                <th style="text-align:left; color:black;" width="30%" > Jumlah </th>
                                            </tr>
                						    <tr>
                						        <td>
                                                    <select class="form-control select2" id="produk_transaksi" name="produk_transaksi" >
                                                        <option value="0" selected>Pilih Produk</option>
                                                        <?php foreach ($list_produk as $row) : ?>
                                                            <option value="<?= $row['id'].'PengHubunG'.$row['nama'].'PengHubunG'.$row['satuan_dasar'] ?>" > <?= $row['nama'] ?> </option>
                                                        <?php endforeach; ?>
                                                    </select>
                						        </td>
                						        <td>
                						            <input class="myinput" id="satuan_dasar" style="text-align:center; color:black;" readonly/>
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
                                <div class="form-group col-md-12">
                                    <label for="resep">List Transaksi Mutasi</label>
                                    <div class="table-container">
                                        <table class="table table-bordered table-light shadow-sm p-3 mb-5 bg-white rounded dt-responsive nowrap" id="tabel-add-mutasi">
                                            <thead>
                                                <tr>
                                                    <th class="bg-primary" style="text-align:center; color:white;"> No. </th>
                                                    <th class="bg-primary" style="text-align:center; color:white;"> Produk </th>
                                                    <th class="bg-primary" style="text-align:center; color:white;"> Jumlah </th>
                                                    <th class="bg-primary" style="text-align:center; color:white;"> Satuan </th>
                                                    <th class="bg-primary" style="text-align:center; color:white;"> Harga Mutasi <?= bulan_indo(date("m")); ?> </th>
                                                    <th class="bg-primary" style="text-align:center; color:white;"> Harga Total </th>
                                                    <th class="bg-primary" style="text-align:center; color:white;"> Aksi </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
        			        <br>
        			        <input type="hidden" id="itung" name="itung">
                            <a href="<?= base_url(); ?>mutasi/mutasi" class="btn btn-danger">Kembali</a>
                            <button type="submit" class="btn btn-primary float-right">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>



<script>

    let baris = 0;
    const table = document.getElementById("tabel-add-mutasi");
    const produkInput = document.getElementById('produk_transaksi');
    const jumlahInput = document.getElementById('jumlah_transaksi');
    const tanggalInput = document.getElementById('tanggal_faktur');
    const dariGudangInput = document.getElementById('dari_gudang');
    const keGudangInput = document.getElementById('ke_gudang');
    const itungInput = document.getElementById('itung');
    const satuanDasarInput = document.getElementById('satuan_dasar');
    
    function tambahRow() {
        const produk = produkInput.value;
        const [id, nama, satuanDasar] = produk.split("PengHubunG");
        const jumlah = jumlahInput.value;
    
        const dateObject = new Date(tanggalInput.value);
        const month = dateObject.getMonth() + 1;
        const year = dateObject.getFullYear();
    
        // ambil harga jual
        let hargaJual;
        $.ajax({
            url: '<?= base_url("mutasi/get_harga_jual") ?>',
            method: 'POST',
            data: {
                dari: dariGudangInput.value,
                ke: keGudangInput.value,
                id_produk: id,
                bulan: month,
                tahun: year
            },
            dataType: 'json',
            success: function ({ harga_jual }) {
                hargaJual = harga_jual || 0;
    
                const total = jumlah * hargaJual;
                const formattedJumlah = ThousandSeparator(jumlah);
                const formattedHargaJual = ThousandSeparator(hargaJual);
                const formattedTotal = ThousandSeparator(total);
                baris = parseInt(baris) + 1;
    
                // create a new row
                const row = table.insertRow();
                row.innerHTML = `
                    <td><center>${baris}</center></td>
                    <td>
                        <label class="text-dark no-break up-case">${nama}</label>
                        <input type='hidden' name='id_produk_${baris}' value='${id}'/>
                        <input class='detailInput' type='hidden' name='produk_${baris}' style='width:100%; text-align:center' value='${nama}' readonly/>
                    </td>
                    <td>
                        <label class='text-dark no-break'>${formattedJumlah}</label>
                        <input class='detailInput' type='hidden' name='jumlah_${baris}' style='width:100%; text-align:center' value='${jumlah}' readonly/>
                    </td>
                    <td><input class='detailInput' type='text' name='satuan_${baris}' style='width:100%; text-align:center' value='${satuanDasar}' readonly/></td>
                    <td>
                        <label class='uang text-dark no-break'>Rp. ${formattedHargaJual}</label>
                        <input class='detailInput' type='hidden' name='harga_${baris}' style='width:100%; text-align:center' value='${hargaJual}' readonly/>
                    </td>
                    <td><label class='uang text-dark no-break'>Rp. ${formattedTotal}</label></td>
                    <td>
                        <center>
                            <button type='button' name='btn_delete' onclick='hapusRow(this)' class='btn btn-sm btn-danger text-light'>
                                <i class='fas fa-trash-alt'></i>
                            </button>
                        </center>
                    </td>
                `;
    
                // reset
                itungInput.value = baris;
                jumlahInput.value = "";
                satuanDasarInput.value = "";
                produkInput.value = "0";
                $(produkInput).trigger('change');
    
                // console.log('hargaJual:', hargaJual);
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
                hargaJual = 0;
            }
        });
    }
    
    function hapusRow(rowElement) {
        // Get the parent row element (tr)
        const row = $(rowElement).closest("tr");
    
        // Delete the row from the table
        table.deleteRow(row[0].rowIndex);
    
        // Renumber the rows
        const rows = table.getElementsByTagName("tr");
        for (let i = 1; i < rows.length; i++) {
            rows[i].getElementsByTagName("td")[0].innerText = i;
        }
    
        // Update the baris value (number of rows)
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
    
    // thousand separator change
    function ThousandSeparator(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
</script>