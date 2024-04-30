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
                            <div class=" form-group col-md-12">
                                <h2 class="text-center">
                                    CASH DRAWER <?= $data_fat[0]->nama_outlet ?>
                                </h2>
                                <h4 class="text-center">
                                    <?= tgl_indo($data_fat[0]->tanggal) ?>
                                </h4>
                                <input type="hidden" class="form-control text-center" id="tanggal" value="<?= tgl_indo($data_fat[0]->tanggal) ?>"  readonly />
                            </div>
                        </div>
                        <div class="row">
                            <?php 
                                if($id_0){
                                    echo '
                                        <div class="col-md-6">
                                            <a href="'.base_url('fat/show/detail/'.$id_0).'" class="btn btn-info mb-2 float-right" style="width: 100%">
                                                Lihat Data Shift 1
                                            </a>
                                        </div>
                                    ';
                                }else{
                                    echo '
                                        <div class="col-md-6">
                                            <a href="#" class="btn btn-warning float-right mb-2" style="width: 100%">
                                                Data Sift 1 Belum Ada
                                            </a>
                                        </div>
                                    ';
                                }
                                
                                if($id_1){
                                    echo '
                                        <div class="col-md-6">
                                            <a href="'.base_url('fat/show/detail/'.$id_1).'" class="btn btn-info float-right mb-2" style="width: 100%">
                                                Lihat Data Shift 2
                                            </a>
                                        </div>
                                    ';
                                }else{
                                    echo '
                                        <div class="col-md-6">
                                            <a href="#" class="btn btn-warning float-right mb-2" style="width: 100%">
                                                Data Sift 2 Belum Ada
                                            </a>
                                        </div>
                                    ';
                                }
                            ?>
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
                                                    <?= thousand_separator($data_fat[0]->total_omzet_outlet_debet_pc) ?>
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
                                                    <?= thousand_separator($data_fat[0]->total_dp_diterima_debet_pc) ?>
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
                                                    <?= thousand_separator($data_fat[0]->total_dp_diambil_kredit_pc) ?>
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
                                                    <?= thousand_separator($data_fat[0]->total_gojek_kredit_pc) ?>
                                                </td>
                                                <td class="bgGreen">
                                                    <?= thousand_separator($data_fat[0]->total_gojek_debet_o) ?>
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
                                                    <?= thousand_separator($data_fat[0]->total_grab_kredit_pc) ?> 
                                                </td>
                                                <td class="bgGreen"> 
                                                    <?= thousand_separator($data_fat[0]->total_grab_debet_o) ?>
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
                                                    <?= thousand_separator($data_fat[0]->total_setor_kredit_pc) ?> 
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td class="bgGreen">
                                                    <?= thousand_separator($data_fat[0]->total_setor_debet_bo) ?>
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">ShopeePay</td>
                                                <td></td>
                                                <td class="bgGreen">
                                                    <?= thousand_separator($data_fat[0]->total_shopeepay_kredit_pc) ?>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td class="bgGreen">
                                                    <?= thousand_separator($data_fat[0]->total_shopeepay_debet_bo) ?>
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
                                                    <?= thousand_separator($data_fat[0]->total_shopeefood_kredit_pc) ?>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td class="bgGreen">
                                                    <?= thousand_separator($data_fat[0]->total_shopeefood_debet_bo) ?>
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
                                                    <?= thousand_separator($data_fat[0]->total_tf_bsi_kredit_pc) ?> 
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td class="bgGreen">
                                                    <?= thousand_separator($data_fat[0]->total_tf_bsi_debet_bo) ?> 
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td id="show_tanggal" class="custom-padding"></td>
                                                <td class="custom-padding">Voucher Karyawan Produksi</td>
                                                <td></td>
                                                <td class="bgGreen">
                                                    <?= thousand_separator($data_fat[0]->total_voucher_karyawan_produksi_kredit_pc) ?> 
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
                                                    <?= thousand_separator($data_fat[0]->total_diskon_karyawan_produksi_kredit_pc) ?> 
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
                                                    <?= thousand_separator($data_fat[0]->total_voucher_karyawan_gumik_kredit_pc) ?>
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
                                                    <?= thousand_separator($data_fat[0]->total_diskon_karyawan_gumik_kredit_pc) ?>  
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
                                                    <?= thousand_separator($data_fat[0]->total_voucher_karyawan_kredit_pc) ?> 
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
                                                    <?= thousand_separator($data_fat[0]->total_diskon_karyawan_kredit_pc) ?>
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
                                                    <?= thousand_separator($data_fat[0]->total_pie_give_away_kredit_pc) ?> 
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
                                                    <?= thousand_separator($data_fat[0]->total_kurir_bahan_baku_kredit_pc) ?>
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
                                                    <?= thousand_separator($data_fat[0]->total_tebus_point_kredit_pc) ?> 
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
                                                    <?= thousand_separator($data_fat[0]->total_pisang_kredit_pc) ?> 
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
                                                    <?= thousand_separator($data_fat[0]->total_kopi_kredit_pc) ?>
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
                                                    <?= thousand_separator($data_fat[0]->total_air_galon_kredit_pc) ?>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <?php
                                                foreach($data_fat_other as $other):
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
                        <a href="<?= base_url('fat/fat'); ?>" class="btn btn-lg btn-danger mb-2">Kembali</a>
                        <?php
                            if(isAdmin() || role()=='4'){
                                echo '
                                    <a href="'.base_url('fat/export_fat/'.$data_fat[0]->tanggal.'/'.$data_fat[0]->id_entitas).'" class="btn btn-lg btn-success float-right mb-2">
                                        <i class="fas fa-file-excel fa-30x"></i>&nbsp;&nbsp;Export Cash Drawer
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
