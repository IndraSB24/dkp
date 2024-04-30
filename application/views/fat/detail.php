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
		
		input{
		    text-transform: uppercase;
		}
		
		.bgGreen{
		    background-color: #008000;
		    color: white;
		    text-align: center;
		    min-width: 150px;
		    max-width: 150px;
		}
		
		.bgYellow{
		    background-color: #CCCC00;
		    color: black;
		}
		
		.bgOrange{
		    background-color: #FF8C00;
		    color: black;
		}
		
		.bgOrangeNom{
		    background-color: #FF8C00;
		    color: white;
		    text-align: center;
		    min-width: 150px;
		    max-width: 150px;
		}
		
		.custom-padding {
            padding-left: 4px; 
            padding-right: 4px;
        }
        
        #show_tanggal{
            text-align: center;
            color: black;
        }
        
        td{
		    color: black;
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
                                <input type="text" class="form-control text-center" id="tanggal" value="<?= tgl_indo($data[0]->tanggal) ?>"  readonly />
                            </div>
                            <div class=" form-group col-md-6">
                                <label for="karyawan">Nama karyawan</label>
                                <input type="text" class="form-control text-center" value="<?= $data[0]->nama_karyawan ?>"  readonly />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class=" form-group col-md-6">
                                <label for="id_entitas">Pada Outlet</label>
                                <input type="text" class="form-control text-center" value="<?= $data[0]->nama_outlet ?>"  readonly />
                            </div>
                            <div class="form-group col-md-6">
                                <label for="shift">Shift</label>
                                <input type="text" class="form-control text-center"  value="<?= 'SHIFT '.$data[0]->shift ?>"  readonly />
                            </div>
                        </div>
                        <br>
                        <?php  
                            if(!isAdmin()){
                                echo '
                                    <div class="alert alert-info mb-1">
                                        Jika ada data yang salah dan ingin mengubahnya, hubungi Super Admin!!!
                                    </div>
                                ';
                            }
                        ?>
                        <div class="form-row">
                            <div class=" form-group col-md-12">
                                <div class="table-container">
                                    <table class="table-bordered shadow-sm mb-2 bg-white" id="tabel-detail" width="100%">
                                        <thead>
                                            <tr class="bg-primary text-white">
                                                <th class="text-center" rowspan="4"> TANGGAL </th>
                                                <th class="text-center" rowspan="4"> KETERANGAN </th>
                                                <th class="text-center" colspan="2"> PETTY CASH </th>
                                                <th class="text-center" colspan="2"> OJOL </th>
                                                <th class="text-center" colspan="2"> BSM OPR </th>
                                            </tr>
                                            <tr class="bg-primary text-white">
                                                <th class="text-center"> DEBET </th>
                                                <th class="text-center"> KREDIT </th>
                                                <th class="text-center"> DEBET </th>
                                                <th class="text-center"> KREDIT </th>
                                                <th class="text-center"> DEBET </th>
                                                <th class="text-center"> KREDIT </th>
                                            </tr>
                                            <tr class="bg-primary text-white">
                                                <th class="text-right">-</th>
                                                <th class="text-right">-</th>
                                                <th class="text-right">-</th>
                                                <th class="text-right">-</th>
                                                <th class="text-right">-</th>
                                                <th class="text-right">-</th>
                                            </tr>
                                            <tr class="bg-primary text-white">
                                                <th class="text-center"> SALDO </th>
                                                <th class="text-right"> - </th>
                                                <th class="text-center"> SALDO </th>
                                                <th class="text-right"> - </th>
                                                <th class="text-center"> SALDO </th>
                                                <th class="text-right"> - </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Omzet Outlet</td>
                                                <td class="bgGreen"> 
                                                    <?= thousand_separator($data[0]->omzet_outlet_debet_pc) ?>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Dp yang di Terima</td>
                                                <td class="bgGreen"> 
                                                    <?= thousand_separator($data[0]->dp_diterima_debet_pc) ?>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Dp yang di Ambil</td>
                                                <td></td>
                                                <td class="bgGreen">
                                                    <?= thousand_separator($data[0]->dp_diambil_kredit_pc) ?>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Gojek</td>
                                                <td></td>
                                                <td class="bgGreen">
                                                    <?= thousand_separator($data[0]->gojek_kredit_pc) ?>
                                                </td>
                                                <td class="bgGreen">
                                                    <?= thousand_separator($data[0]->gojek_debet_o) ?>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding bgYellow">Potongan gojek</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="bgYellow"></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding bgYellow">Potongan promo gojek</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="bgYellow"></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Grab</td>
                                                <td></td>
                                                <td class="bgGreen">
                                                    <?= thousand_separator($data[0]->grab_kredit_pc) ?> 
                                                </td>
                                                <td class="bgGreen"> 
                                                    <?= thousand_separator($data[0]->grab_debet_o) ?>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding bgYellow">Potongan Grab</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="bgYellow"></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding bgYellow">Potongan promo Grab</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="bgYellow"></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding bgYellow">Potongan Outlet</td>
                                                <td></td>
                                                <td class="bgYellow"></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Setor Tunai Kasir</td>
                                                <td></td>
                                                <td class="bgGreen">
                                                    <?= thousand_separator($data[0]->setor_kredit_pc) ?> 
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td class="bgGreen">
                                                    <?= thousand_separator($data[0]->setor_debet_bo) ?>
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">ShopeePay</td>
                                                <td></td>
                                                <td class="bgGreen">
                                                    <?= thousand_separator($data[0]->shopeepay_kredit_pc) ?>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td class="bgGreen">
                                                    <?= thousand_separator($data[0]->shopeepay_debet_bo) ?>
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding bgYellow">Potongan ShopeePay</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="bgYellow"></td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Shopee food</td>
                                                <td></td>
                                                <td class="bgGreen">
                                                    <?= thousand_separator($data[0]->shopeefood_kredit_pc) ?>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td class="bgGreen">
                                                    <?= thousand_separator($data[0]->shopeefood_debet_bo) ?>
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding bgYellow">Potongan Shopee food</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="bgYellow"></td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding bgYellow">Potongan Promo Shopeefood</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="bgYellow"></td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Transfer BSI</td>
                                                <td></td>
                                                <td class="bgGreen">
                                                    <?= thousand_separator($data[0]->tf_bsi_kredit_pc) ?> 
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td class="bgGreen">
                                                    <?= thousand_separator($data[0]->tf_bsi_debet_bo) ?> 
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Voucher Karyawan Produksi</td>
                                                <td></td>
                                                <td class="bgGreen">
                                                    <?= thousand_separator($data[0]->voucher_karyawan_produksi_kredit_pc) ?> 
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Discount Karyawan Produksi</td>
                                                <td></td>
                                                <td class="bgGreen">
                                                    <?= thousand_separator($data[0]->diskon_karyawan_produksi_kredit_pc) ?> 
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Voucher Karyawan Gumik</td>
                                                <td></td>
                                                <td class="bgGreen">
                                                    <?= thousand_separator($data[0]->voucher_karyawan_gumik_kredit_pc) ?>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Discount karyawan Gumik</td>
                                                <td></td>
                                                <td class="bgGreen">
                                                    <?= thousand_separator($data[0]->diskon_karyawan_gumik_kredit_pc) ?>  
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Voucher Karyawan</td>
                                                <td></td>
                                                <td class="bgGreen">
                                                    <?= thousand_separator($data[0]->voucher_karyawan_kredit_pc) ?> 
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Discount Karyawan</td>
                                                <td></td>
                                                <td class="bgGreen">
                                                    <?= thousand_separator($data[0]->diskon_karyawan_kredit_pc) ?>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Pie Give Away</td>
                                                <td></td>
                                                <td class="bgGreen">
                                                    <?= thousand_separator($data[0]->pie_give_away_kredit_pc) ?> 
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Kurir Bahan Baku</td>
                                                <td></td>
                                                <td class="bgGreen">
                                                    <?= thousand_separator($data[0]->kurir_bahan_baku_kredit_pc) ?>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Tebus Point</td>
                                                <td></td>
                                                <td class="bgGreen">
                                                    <?= thousand_separator($data[0]->tebus_point_kredit_pc) ?> 
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Pisang</td>
                                                <td></td>
                                                <td class="bgGreen">
                                                    <?= thousand_separator($data[0]->pisang_kredit_pc) ?> 
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Kopi</td>
                                                <td></td>
                                                <td class="bgGreen">
                                                    <?= thousand_separator($data[0]->kopi_kredit_pc) ?>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Air Galon</td>
                                                <td></td>
                                                <td class="bgGreen">
                                                    <?= thousand_separator($data[0]->air_galon_kredit_pc) ?>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <?php
                                                foreach($data_other as $other):
                                            ?>
                                                    <tr>
                                                        <td id="show_tanggal" class="custom-padding"></td>
                                                        <td class="custom-padding bgOrange">
                                                            <?= $other->keterangan ?>
                                                        </td>
                                                        <td></td>
                                                        <td class="bgOrangeNom">
                                                            <?= thousand_separator($other->nominal) ?>
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <br>
                        <a href="<?= base_url('fat/fat'); ?>" class="btn btn-danger mb-2">Kembali</a>
                        <?php
                            if(isAdmin() || role()!='1'){
                                echo '
                                    <a href="'.base_url('fat/show/by_date/'.$data[0]->tanggal.'/'.$data[0]->id_entitas).'" class="btn btn-info float-right mb-2">
                                        Detail Untuk Tanggal '.tgl_indo($data[0]->tanggal).'
                                    </a>
                                ';
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </section>
</div>


<script>

    document.addEventListener('DOMContentLoaded', function() {
      const tanggalInput = document.getElementById('tanggal').value;
      const showTanggalElements = document.querySelectorAll('td#show_tanggal');
    
      showTanggalElements.forEach(element => {
        element.textContent = tanggalInput;
      });
    });
    
</script>
