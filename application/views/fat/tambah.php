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
			text-align:center;
			background-color: transparent;
			color: white;
		}
		
		/*input{*/
		/*    text-transform: uppercase;*/
		/*}*/
		
		.bgGreen{
		    background-color: #008000;
		    
		}
		
		.bgYellow{
		    background-color: #CCCC00;
		}
		
		.bgOrange{
		    background-color: #FF8C00;
		}
		
		.custom-padding {
            padding-left: 4px; 
            padding-right: 4px;
        }
        
        .up-case{
            text-transform: uppercase;
        }
        
        #show_tanggal{
            text-align: center;
        }
        
        .table-container {
            overflow-x: auto;
            width: 100%;
        }
    </style>
</header>

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <form action="<?= base_url('fat/tambah') ?>" method="POST">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="form-row">
                            <div class=" form-group col-md-6">
                                <label for="tanggal_faktur">Tanggal</label>
                                <?php
                                    if(role() == '1'){
                                        echo '<input type="text" class="form-control text-center" id="tanggal" name="tanggal" value="'.tgl_indo(now()).'"  />';
                                    }else{
                                        echo '<input type="date" class="form-control text-center" id="tanggal" name="tanggal" />';
                                    }
                                ?>
                            </div>
                            <div class=" form-group col-md-6">
                                <label for="karyawan">Nama karyawan</label>
                                <input type="text" class="form-control text-center up-case" id="karyawan" name="karyawan" value="<?= activeNama() ?>"  readonly />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class=" form-group col-md-6">
                                <label for="id_entitas">Pada Outlet</label>
                                <select class="form-control select2" id="id_entitas" name="id_entitas">
                                    <option value="0" selected>PILIH OUTLET</option>
                                    <?php foreach ($list_gudang as $gudang) : ?>
                                        <option value="<?= $gudang['id'] ?>"><?= $gudang['nama'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="shift">Shift</label>
                                <select class="form-control select2" id="shift" name="shift">
                                    <option value="0">PILIH SHIFT</option>
                                    <option value="1">SHIFT 1</option>
                                    <option value="2">SHIFT 2</option>
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="form-row">
                            <div class=" form-group col-md-12">
                                <div class="table-container">
                                    <table class="table-bordered shadow-sm mb-2 bg-white" id="tabel-detail" width="100%">
                                        <thead>
                                            <tr class="bg-primary text-white">
                                                <th class="text-center" rowspan="4"> TANGGAL </th>
                                                <th class="text-center" rowspan="4"> KETERANGAN </th>
                                                <th class="text-center" colspan="2"> PETTY CASH </th>
                                            </tr>
                                            <tr class="bg-primary text-white">
                                                <th class="text-center"> DEBET </th>
                                                <th class="text-center"> KREDIT </th>
                                            </tr>
                                            <tr class="bg-primary text-white">
                                                <th class="text-right">-</th>
                                                <th class="text-right">-</th>
                                            </tr>
                                            <tr class="bg-primary text-white">
                                                <th class="text-center"> SALDO </th>
                                                <th class="text-right"> - </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Omzet Outlet</td>
                                                <td class="bgGreen"> 
                                                    <input type="text" class="detailInput thousand-separator" name="omzet_outlet_debet_pc" /> 
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Dp yang di Terima</td>
                                                <td class="bgGreen"> 
                                                    <input type="text" class="detailInput thousand-separator" name="dp_diterima_debet_pc" /> 
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Dp yang di Ambil</td>
                                                <td></td>
                                                <td class="bgGreen">
                                                    <input type="text" class="detailInput thousand-separator" name="dp_diambil_kredit_pc" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Gojek</td>
                                                <td></td>
                                                <td class="bgGreen"> 
                                                    <input type="text" class="detailInput thousand-separator" name="gojek_kredit_pc" /> 
                                                </td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Grab</td>
                                                <td></td>
                                                <td class="bgGreen"> 
                                                    <input type="text" class="detailInput thousand-separator" name="grab_kredit_pc" /> 
                                                </td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Setor Tunai Kasir</td>
                                                <td></td>
                                                <td class="bgGreen"> 
                                                    <input type="text" class="detailInput thousand-separator" name="setor_kredit_pc" /> 
                                                </td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">ShopeePay</td>
                                                <td></td>
                                                <td class="bgGreen"> 
                                                    <input type="text" class="detailInput thousand-separator" name="shopeepay_kredit_pc" /> 
                                                </td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Shopee food</td>
                                                <td></td>
                                                <td class="bgGreen"> 
                                                    <input type="text" class="detailInput thousand-separator" name="shopeefood_kredit_pc" /> 
                                                </td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Transfer BSI</td>
                                                <td></td>
                                                <td class="bgGreen"> 
                                                    <input type="text" class="detailInput thousand-separator" name="tf_bsi_kredit_pc" /> 
                                                </td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Voucher Karyawan Produksi</td>
                                                <td></td>
                                                <td class="bgGreen"> 
                                                    <input type="text" class="detailInput thousand-separator" name="voucher_karyawan_produksi_kredit_pc" /> 
                                                </td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Discount Karyawan Produksi</td>
                                                <td></td>
                                                <td class="bgGreen"> 
                                                    <input type="text" class="detailInput thousand-separator" name="diskon_karyawan_produksi_kredit_pc" /> 
                                                </td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Voucher Karyawan Gumik</td>
                                                <td></td>
                                                <td class="bgGreen"> 
                                                    <input type="text" class="detailInput thousand-separator" name="voucher_karyawan_gumik_kredit_pc" /> 
                                                </td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Discount karyawan Gumik</td>
                                                <td></td>
                                                <td class="bgGreen"> 
                                                    <input type="text" class="detailInput thousand-separator" name="diskon_karyawan_gumik_kredit_pc" /> 
                                                </td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Voucher Karyawan</td>
                                                <td></td>
                                                <td class="bgGreen"> 
                                                    <input type="text" class="detailInput thousand-separator" name="voucher_karyawan_kredit_pc" /> 
                                                </td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Discount Karyawan</td>
                                                <td></td>
                                                <td class="bgGreen"> 
                                                    <input type="text" class="detailInput thousand-separator" name="diskon_karyawan_kredit_pc" /> 
                                                </td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Pie Give Away</td>
                                                <td></td>
                                                <td class="bgGreen"> 
                                                    <input type="text" class="detailInput thousand-separator" name="pie_give_away_kredit_pc" /> 
                                                </td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Kurir Bahan Baku</td>
                                                <td></td>
                                                <td class="bgGreen"> 
                                                    <input type="text" class="detailInput thousand-separator" name="kurir_bahan_baku_kredit_pc" /> 
                                                </td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Tebus Point</td>
                                                <td></td>
                                                <td class="bgGreen"> 
                                                    <input type="text" class="detailInput thousand-separator" name="tebus_point_kredit_pc" /> 
                                                </td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Pisang</td>
                                                <td></td>
                                                <td class="bgGreen"> 
                                                    <input type="text" class="detailInput thousand-separator" name="pisang_kredit_pc" /> 
                                                </td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Kopi</td>
                                                <td></td>
                                                <td class="bgGreen"> 
                                                    <input type="text" class="detailInput thousand-separator" name="kopi_kredit_pc" /> 
                                                </td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Air Galon</td>
                                                <td></td>
                                                <td class="bgGreen"> 
                                                    <input type="text" class="detailInput thousand-separator" name="air_galon_kredit_pc" /> 
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <input type="hidden" id="counted_other_row" name="counted_other_row" />
                                        <button type="button" class="btn btn-primary btn-block" id="addRowBtn" style="width:100%">Tambah Data Lain</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <input type="hidden" id="id_role" value="<?= role() ?>" />
                        <a href="<?= base_url('fat/fat'); ?>" class="btn btn-danger">Kembali</a>
                        <button type="submit" class="btn btn-primary float-right">Submit Data</button>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </section>
</div>


<script>

    // thousand separator set
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
    
    // auto set date
    const roleId = document.getElementById('id_role').value;
    const showTanggalCells = document.querySelectorAll('td#show_tanggal');
    if(roleId == '1'){
        const today = new Date();
        const formattedDate = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;
        showTanggalCells.forEach(cell => {
            cell.textContent = new Date(formattedDate).toLocaleDateString('en-GB');
        });
    }else{
        const dateInput = document.getElementById('tanggal');
        dateInput.addEventListener('input', function(event) {
            const selectedDate = event.target.value;
            showTanggalCells.forEach(cell => {
                cell.textContent = new Date(selectedDate).toLocaleDateString('en-GB');
            });
        });
    }
    
    // add row
    function addNewRow() {
        const tableBody = document.querySelector("#tabel-detail tbody");
        const showTanggal = document.getElementById("show_tanggal");
        let lastTanggal;
        if (showTanggal) {
            lastTanggal = showTanggal.textContent;
        } else {
            lastTanggal = null;
        }

        const newRow = document.createElement("tr");
        newRow.innerHTML = `
          <td id="show_tanggal" class="custom-padding">${lastTanggal}</td>
          <td class="custom-padding bgOrange">
            <input type="text" class="detailInput" name="other[]" />
          </td>
          <td></td>
          <td class="bgOrange">
            <input type="text" class="detailInput thousand-separator" name="other_debet_pc[]" />
          </td>
          <td>
            <button type="button" onclick="deleteRow(this)" class="btn btn-sm btn-danger text-light tombol-hapus" style="width:100%">
              <i class="fas fa-trash-alt"></i>
            </button>
          </td>
        `;
        tableBody.appendChild(newRow);
        
        const thousandSeparatorInputs = document.querySelectorAll('.thousand-separator');
        thousandSeparatorInputs.forEach(input => {
            input.addEventListener('input', function(event) {
                let inputValue = event.target.value;
                inputValue = keepOnlyNumbers(inputValue);
    
                const formattedValue = addThousandSeparator(inputValue);
                event.target.value = formattedValue;
            });
        });
    }
    function deleteRow(button) {
        const row = button.closest("tr");
        row.remove();
    }
    const addRowBtn = document.getElementById("addRowBtn");
    addRowBtn.addEventListener("click", addNewRow);
    
</script>

