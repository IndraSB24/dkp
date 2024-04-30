<head>
    <style>
        .hurufbesar {
            text-transform: uppercase;
        }
    </style>
</head>
<!-- Main Content -->
<div class="main-content">
  <div class="flash-data" data-flashdata="<?= $this->session->flashdata('pesan'); ?>"></div>
  <section class="section">
    <div class="row">
      <div class="col-lg-4 col-md-4 col-sm-12">
        <div class="card card-statistic-2">
          <div class="card-icon shadow-primary bg-primary text-light font-weight-bold">
            Rp
          </div>
          <div class="card-wrap">
            <div class="card-header">
              <h4 style="font-size: 12px;">Total Hutang <?= date('Y') ?> - <?= bulan_indo(date("m")) ?></h4>
            </div>
            <div class="card-body">
                Rp <?= number_format($data_hutang[0]->total_hutang, 2, ',', '.') ?>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-4 col-sm-12">
        <div class="card card-statistic-2">
          <div class="card-icon shadow-primary bg-primary">
            <i class="fas fa-shopping-bag"></i>
          </div>
          <div class="card-wrap">
            <div class="card-header">
                <h4 style="font-size: 12px;">Total Produk - 
                    <select class="text-primary" id="pilih_gudang" name="pilih_gudang">
                        <?php
                            foreach($list_gudang as $lg){
                                echo '<option value="'. $lg->id .'">'. $lg->nama .'</option>';
                            }
                        ?>
                    </select>
                </h4>
            </div>
            <div class="card-body">
                <span id="show_total_produk"><?= number_format($total_produk[1], 0, ',', '.') ?> Produk</span>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-4 col-sm-12">
        <div class="card card-statistic-2">
          <div class="card-icon shadow-primary bg-primary text-light font-weight-bold">
            Rp
          </div>
          <div class="card-wrap">
            <div class="card-header">
              <h4 style="font-size: 12px;">Nilai Persediaan <?= bulan_indo(date('m'))."/".date('Y') ?>-
                <span class="text-primary"> 
                    <select class="text-primary" id="pilih_gudang_persediaan" name="pilih_gudang_persediaan">
                        <?php
                            foreach($list_gudang as $lg){
                                echo '<option value="'. $lg->id .'">'. $lg->nama .'</option>';
                            }
                        ?>
                    </select>
                </span>
              </h4>
            </div>
            <div class="card-body">
              <span id="show_total_persediaan">Rp <?= number_format($total_persediaan[1], 2, ',', '.') ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="row">
      <div class="col-lg-4 col-md-4 col-sm-12">
        <div class="card card-statistic-2">
          <div class="card-icon shadow-primary bg-primary text-light font-weight-bold">
            Rp.
          </div>
          <div class="card-wrap">
            <div class="card-header">
              <h4 style="font-size: 12px;">Total Penjualan <?= bulan_indo(date('m'))."/".date('Y') ?>-
                <span class="text-primary"> 
                    <select class="text-primary" id="pilih_outlet_penjualan" name="pilih_outlet_penjualan">
                        <?php
                            foreach($list_gudang as $lg){
                                echo '<option value="'. $lg->id .'">'. $lg->nama .'</option>';
                            }
                        ?>
                    </select>
                </span>
            </div>
            <div class="card-body">
            <span id="show_outlet_penjualan">Rp <?= number_format($total_penjualan[1], 2, ',', '.') ?></span>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-4 col-sm-12">
        <div class="card card-statistic-2">
          <div class="card-icon shadow-primary bg-primary text-light font-weight-bold">
            Rp
          </div>
          <div class="card-wrap">
            <div class="card-header">
              <h4 style="font-size: 12px;">Total Pembelian <?= date('Y') ?> - <?= bulan_indo(date("m")) ?></h4>
            </div>
            <div class="card-body">
                Rp <?= number_format($data_pembelian[0]->total_pembelian, 2, ',', '.') ?>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-4 col-sm-12">
        <div class="card card-statistic-2">
          <div class="card-icon shadow-primary bg-primary text-light font-weight-bold">
            Rp
          </div>
          <div class="card-wrap">
            <div class="card-header">
              <h4 style="font-size: 12px;">Total Piutang Mutasi <?= bulan_indo(date('m'))."/".date('Y') ?>-
                <span class="text-primary"> 
                    <select class="text-primary" id="pilih_outlet_mutasi" name="pilih_outlet_mutasi">
                        <?php
                            foreach($list_gudang as $lg){
                                echo '<option value="'. $lg->id .'">'. $lg->nama .'</option>';
                            }
                        ?>
                    </select>
                </span>
            </div>
            <div class="card-body">
                <span id="show_outlet_mutasi">Rp <?= number_format($total_mutasi[1], 2, ',', '.') ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!--<div class="row">-->
    <!--  <div class="col-lg-12">-->
    <!--    <a href="<?= base_url().'dashboard/sendWaPersonal' ?>" class="btn btn-success btn-lg"> test WA Personal </a>-->
    <!--  </div>-->
    <!--</div>-->
    <!--<div class="row">-->
    <!--  <div class="col-lg-12">-->
    <!--    <a href="<?= base_url().'dashboard/sendWaGroup' ?>" class="btn btn-info btn-lg"> test WA Group </a>-->
    <!--  </div>-->
    <!--</div>-->
    
    <!--<div class="container">-->
    <!--    <canvas id="myChart"></canvas>-->
    <!--</div>-->
  </section>
</div>

<?php
    foreach($list_gudang as $row){
        echo '
            <input type="hidden" id="total_produk_gudang_'.$row->id.'" name="total_produk_gudang_'.$row->id.'" value="'.$total_produk[$row->id].'">
            <input type="hidden" id="total_persediaan_gudang_'.$row->id.'" name="total_persediaan_gudang_'.$row->id.'" value="'.$total_persediaan[$row->id].'">
            <input type="hidden" id="total_outlet_penjualan_'.$row->id.'" name="total_outlet_penjualan_'.$row->id.'" value="'.$total_penjualan[$row->id].'">
            <input type="hidden" id="total_outlet_mutasi_'.$row->id.'" name="total_outlet_mutasi_'.$row->id.'" value="'.$total_mutasi[$row->id].'">
        ';
    }
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
	    Array.prototype.forEach.call(document.getElementsByName('pilih_gudang'),
    		function (elem) {
    			elem.addEventListener('change', function() {
    				let id_gudang	= this.value;
    				
    				document.getElementById('show_total_produk').innerHTML = document.getElementById('total_produk_gudang_'+id_gudang).value + " Produk";
    	    });
        });
    });
    
    document.addEventListener('DOMContentLoaded', function() {
	    Array.prototype.forEach.call(document.getElementsByName('pilih_gudang_persediaan'),
    		function (elem) {
    			elem.addEventListener('change', function() {
    				let id_gudang	= this.value;
    				var persediaan  = document.getElementById('total_persediaan_gudang_'+id_gudang).value;
    				const rupiah = (number)=>{
                        return new Intl.NumberFormat("id-ID", {
                            style: "currency",
                            currency: "IDR"
                        }).format(number);
                    }
    				
    				document.getElementById('show_total_persediaan').innerHTML = rupiah(persediaan);
    	    });
        });
    });
    
    document.addEventListener('DOMContentLoaded', function() {
	    Array.prototype.forEach.call(document.getElementsByName('pilih_outlet_penjualan'),
    		function (elem) {
    			elem.addEventListener('change', function() {
    				let id_gudang	= this.value;
    				var persediaan  = document.getElementById('total_outlet_penjualan_'+id_gudang).value;
    				const rupiah = (number)=>{
                        return new Intl.NumberFormat("id-ID", {
                            style: "currency",
                            currency: "IDR"
                        }).format(number);
                    }
    				
    				document.getElementById('show_outlet_penjualan').innerHTML = rupiah(persediaan);
    	    });
        });
    });
    
    document.addEventListener('DOMContentLoaded', function() {
	    Array.prototype.forEach.call(document.getElementsByName('pilih_outlet_mutasi'),
    		function (elem) {
    			elem.addEventListener('change', function() {
    				let id_gudang	= this.value;
    				var persediaan  = document.getElementById('total_outlet_mutasi_'+id_gudang).value;
    				const rupiah = (number)=>{
                        return new Intl.NumberFormat("id-ID", {
                            style: "currency",
                            currency: "IDR"
                        }).format(number);
                    }
    				
    				document.getElementById('show_outlet_mutasi').innerHTML = rupiah(persediaan);
    	    });
        });
    });
</script>

<script type="text/javascript">
    var ctx = document.getElementById('chartPenjualan').getContext('2d');
    var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [
          <?php
            if (count($graph_penjualan)>0) {
              foreach ($graph_penjualan as $data) {
                echo "'" .$data->kode_faktur ."',";
              }
            }
          ?>
        ],
        datasets: [{
            label: 'Jumlah Penduduk',
            backgroundColor: '#ADD8E6',
            borderColor: '##93C3D2',
            data: [
              <?php
                if (count($graph_penjualan)>0) {
                   foreach ($graph_penjualan as $data) {
                    echo $data->id_gudang . ", ";
                  }
                }
              ?>
            ]
        }]
    },
});
 
</script>