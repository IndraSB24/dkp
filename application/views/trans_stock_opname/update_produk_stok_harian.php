<head>
    <style>
        .huruf-besar{
            text-transform:uppercase;
        }
        
        input[type=checkbox] {
            transform: scale(2);
        }
    </style>
</head>

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="row mt-3">
            <div class="col-lg-12 mt-5">
            <form action="<?= base_url(); ?>/stock_opname/update_status_opname_harian" method="POST">
                <table class="table table-bordered table-light shadow-sm p-3 mb-5 bg-white rounded nowrap" id="tabel-stat-opname-harian" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Produk</th>
                            <th class="text-center">Check</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                            $baris = 0;
                            foreach($list_produk as $row){
                                $baris += 1;
                                    echo '
                                        <tr>
                                            <td class="text-center huruf-besar">'.$baris.'</td>
                                            <td class="text-center huruf-besar">'.$row->nama_produk.'</td>
                                            <td class="text-center huruf-besar"><input type="checkbox" name="check_list['.$baris.']" alt="checkbox" value="'.$row->id_produk.'" /></td>
                                        </tr>
                                    ';
                                
                            }
                        ?>
                    </tbody>
                </table>
                <input type="hidden" name="id_gudang" id="id_gudang" value="<?= $id_gudang ?>">
                <a href="<?= base_url(); ?>stock_opname/stock_opname" class="btn btn-danger">Kembali</a>
                <?php if($list_produk): ?>
                    <button type="submit" class="btn btn-primary float-right">Atur Sebagai Produk Opname Harian</button>
                <?php endif; ?>
            </form>
            </div>
        </div>
    </section>
</div>


<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.10/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#tabel-stat-opname-harian').DataTable({
            "paging": false, 
            "searching": true, 
            "ordering": true,
            scrollX: true,
            autoWidth: false,
        });
    });
</script>