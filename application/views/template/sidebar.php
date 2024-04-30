<div class="navbar-bg"></div>
<nav class="navbar main-navbar">
    <ul class="navbar-nav mr-3">
        <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i>
            <h5 class="mt-2 text-light d-inline ml-3"><?= $title; ?></h5>
        </a>

    </ul>
    <!--<ul class="nav navbar-nav navbar-right">-->
    <!--    <button type="button" class="btn btn-light" data-toggle="modal" data-target="#logout">Logout</button>-->
    <!--</ul>-->
</nav>
</div>
<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="<?= base_url() ?>dashboard"><img src="<?= base_url() ?>/assets/img/storypie200.png" \></a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header mt-4">Menu</li>
        <?php if(isManager() || isAdmin() || active_role() == "spv_outlet" || active_role() == "spv_gudang") : ?>    
            <li <?= $this->uri->segment(1) == 'dashboard' || $this->uri->segment(1) == '' ? 'class="active"' : "" ?>>
                <a class="nav-link" href="<?= base_url() ?>dashboard">
                    <i class="fas fa-fire"></i>
                    <span>Dashboard New</span>
                </a>
            </li>
            
            <!-- Data Master -->
            <li class="dropdown <?php echo $this->uri->segment(2) == 'data_produk' || $this->uri->segment(2) == 'data_karyawan' || $this->uri->segment(2) == 'formula_produk' || $this->uri->segment(2) == 'data_supplier' || $this->uri->segment(2) == 'data_departemen'  || $this->uri->segment(2) == 'data_gudang' || $this->uri->segment(2) == 'satuan_pengukuran' || $this->uri->segment(2) == 'kelompok_produk' || $this->uri->segment(2) == 'kelompok_pelanggan' || $this->uri->segment(2) == 'harga_jual' || $this->uri->segment(2) == 'kpi_barang_rusak' || $this->uri->segment(2) == 'kategori_jurnal' ? 'active' : ''; ?>">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i> <span>Data Master</span></a>
                <ul class="dropdown-menu">
                    <li <?= $this->uri->segment(2) == 'data_produk' || $this->uri->segment(2) == 'tambah_produk' || $this->uri->segment(2) == 'update_produk' ? 'class="active"' : "" ?>>
                        <a class="nav-link" href="<?= base_url(); ?>produk/data_produk">
                            <!--<i class="fas fa-box-open"></i>-->
                            <span>Data Produk</span>
                        </a>
                    </li>
                    <li <?= $this->uri->segment(2) == 'harga_jual' || $this->uri->segment(2) == 'tambah_produk' || $this->uri->segment(2) == 'update_produk' ? 'class="active"' : "" ?>>
                        <a class="nav-link" href="<?= base_url(); ?>harga_jual/harga_jual">
                            <!--<i class="fas fa-box-open"></i>-->
                            <span>Data Harga Jual</span>
                        </a>
                    </li>
                    <li <?= $this->uri->segment(2) == 'formula_produk' || $this->uri->segment(2) == 'tambah_produk' || $this->uri->segment(2) == 'update_produk' ? 'class="active"' : "" ?>>
                        <a class="nav-link" href="<?= base_url(); ?>formula_produk/formula_produk">
                            <!--<i class="fas fa-box-open"></i>-->
                            <span>Formula Produk</span>
                        </a>
                    </li>
                    <li <?=  $this->uri->segment(2) == 'data_karyawan' ? 'class="active"' : "" ?>>
                        <a class="nav-link" href="<?= base_url(); ?>data_karyawan/data_karyawan">
                            <!--<i class="fa fa-users"></i>-->
                            <span>Data Karyawan</span>
                        </a>
                    </li>
                    <li <?=  $this->uri->segment(2) == 'data_supplier' ? 'class="active"' : "" ?>>
                        <a class="nav-link" href="<?= base_url(); ?>data_supplier/data_supplier">
                            <!--<i class="fas fa-truck"></i>-->
                            <span>Data Supplier</span>
                        </a>
                    </li>
                    <li <?=  $this->uri->segment(2) == 'data_departemen' ? 'class="active"' : "" ?>>
                        <a class="nav-link" href="<?= base_url(); ?>data_departemen/data_departemen">
                            <!--<i class="fas fa-building"></i>-->
                            <span>Data Departemen</span>
                        </a>
                    </li>
                    <li <?=  $this->uri->segment(2) == 'data_gudang' ? 'class="active"' : "" ?>>
                        <a class="nav-link" href="<?= base_url(); ?>data_gudang/data_gudang">
                            <!--<i class="fas fa-warehouse"></i>-->
                            <span>Data Gudang</span>
                        </a>
                    </li>
                    <li <?=  $this->uri->segment(2) == 'satuan_pengukuran' ? 'class="active"' : "" ?>>
                        <a class="nav-link" href="<?= base_url(); ?>satuan_pengukuran/satuan_pengukuran">
                            <!--<i class="fas fa-ruler"></i>-->
                            <span>Satuan Pengukuran</span>
                        </a>
                    </li>
                    <li <?=  $this->uri->segment(2) == 'kelompok_produk' ? 'class="active"' : "" ?>>
                        <a class="nav-link" href="<?= base_url(); ?>kelompok_produk/kelompok_produk">
                            <!--<i class="fas fa-layer-group"></i>-->
                            <span>Kelompok Produk</span>
                        </a>
                    </li>
                    <li <?=  $this->uri->segment(2) == 'kelompok_pelanggan' ? 'class="active"' : "" ?>>
                        <a class="nav-link" href="<?= base_url(); ?>kelompok_pelanggan/kelompok_pelanggan">
                            <!--<i class="fas fa-layer-group"></i>-->
                            <span>Kelompok Pelanggan</span>
                        </a>
                    </li>
                    <li <?=  $this->uri->segment(2) == 'kpi_barang_rusak' ? 'class="active"' : "" ?>>
                        <a class="nav-link" href="<?= base_url(); ?>kpi_barang_rusak/kpi_barang_rusak">
                            <!--<i class="fas fa-layer-group"></i>-->
                            <span>KPI Barang Rusak</span>
                        </a>
                    </li>
                    <!--<li <?=  $this->uri->segment(2) == 'kategori_jurnal' ? 'class="active"' : "" ?>>-->
                    <!--    <a class="nav-link" href="<?= base_url(); ?>kategori_jurnal/kategori_jurnal">-->
                            <!--<i class="fas fa-layer-group"></i>-->
                    <!--        <span>Kategori Jurnal</span>-->
                    <!--    </a>-->
                    <!--</li>-->
                </ul>
            </li>
        <?php endif; ?>
        
            <!-- Transaksi -->
            <li class="dropdown <?= in_array($this->uri->segment(2), ['pembelian', 'penjualan', 'produksi', 'mutasi', 'mutasi_divisi', 'pemakaian_barang', 'input_pembelian', 'barang_rusak', 'stock_opname', 'fat']) ? 'active' : ''; ?>">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-shopping-cart"></i> <span>Transaksi</span></a>
                <ul class="dropdown-menu">
                <?php if(active_role()!='finance_staff'): ?>
                
                    <?php if(active_role() == "super_admin" || active_role() == "spv_outlet" || active_role() == "manager") : ?>
                        <li <?=  $this->uri->segment(2) == 'penjualan' ? 'class="active"' : "" ?>>
                            <a class="nav-link" href="<?= base_url(); ?>penjualan/penjualan">
                                <!--<i class="fas fa-coins"></i>-->
                                <span>Penjualan</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if(active_role() != "entitas_vendor" && active_role() != "spv_outlet" && active_role() != "spv_gudang") : ?>
                        <li class="dropdown <?php echo $this->uri->segment(2) == 'pembelian' || $this->uri->segment(2) == 'input_pembelian' ? 'active' : ''; ?>">
                            <li <?=  $this->uri->segment(2) == 'pembelian' || $this->uri->segment(2) == 'show' ? 'class="active"' : "" ?>>
                                <a class="nav-link" href="<?= base_url(); ?>pembelian/pembelian">
                                    <span>Pembelian</span>
                                </a>
                            </li>
                        </li>
                    <?php endif; ?>

                    <li <?=  $this->uri->segment(2) == 'produksi' || $this->uri->segment(2) == 'show' ? 'class="active"' : "" ?>>
                        <a class="nav-link" href="<?= base_url(); ?>produksi/produksi">
                            <span>Produksi</span>
                        </a>
                    </li>
                    <li <?=  $this->uri->segment(2) == 'mutasi' || $this->uri->segment(2) == 'show' ? 'class="active"' : "" ?>>
                        <a class="nav-link" href="<?= base_url(); ?>mutasi/mutasi">
                            <span>Mutasi Barang</span>
                        </a>
                    </li>
                    
                    <?php if(active_role() != "entitas_vendor") : ?>
                        <li <?=  $this->uri->segment(2) == 'mutasi_divisi'  ? 'class="active"' : "" ?>>
                            <a class="nav-link" href="<?= base_url(); ?>mutasi_divisi/mutasi_divisi">
                                <span>Mutasi Divisi</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php if(active_role() != "entitas_vendor") : ?>
                        <li <?=  $this->uri->segment(2) == 'pemakaian_barang' ? 'class="active"' : "" ?>>
                            <a class="nav-link" href="<?= base_url(); ?>pemakaian_barang/pemakaian_barang">
                                <span>Pemakaian Barang</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <li <?=  $this->uri->segment(2) == 'barang_rusak' ? 'class="active"' : "" ?>>
                        <a class="nav-link" href="<?= base_url(); ?>barang_rusak/barang_rusak">
                            <span>Barang Rusak</span>
                        </a>
                    </li>
                    <li <?=  $this->uri->segment(2) == 'stock_opname' ? 'class="active"' : "" ?>>
                        <a class="nav-link" href="<?= base_url(); ?>stock_opname/stock_opname">
                            <span>Stock Opname</span>
                        </a>
                    </li>
                <?php endif; ?>
                
                <?php if(active_role() != "entitas_vendor") : ?>
                    <li <?=  $this->uri->segment(2) == 'fat' ? 'class="active"' : "" ?>>
                        <a class="nav-link" href="<?= base_url(); ?>fat/fat">
                            <span>Cash Drawer</span>
                        </a>
                    </li>
                <?php endif; ?>
                
                </ul>
            </li>
            
        <?php if(isManager() || isAdmin() || substr(hak_akses(),0,3)=="SPV") : ?>
            <li <?= $this->uri->segment(1) == 'daftar_hutang' ? 'class="active"' : "" ?>>
                <a class="nav-link" href="<?= base_url() ?>daftar_hutang/daftar_hutang">
                    <i class="fas fa-file-invoice"></i>
                    <span>Daftar Hutang</span>
                </a>
            </li>
           
           <li <?= $this->uri->segment(1) == 'daftar_piutang' ? 'class="active"' : "" ?>>
                <a class="nav-link" href="<?= base_url() ?>daftar_piutang/daftar_piutang">
                    <i class="fas fa-receipt"></i>
                    <span>Daftar Piutang</span>
                </a>
            </li>
        <?php endif; ?>
        
        <?php if(role()!='4'): ?>
            <!-- Laporan -->
            <li class="dropdown <?php echo $this->uri->segment(2) == 'stok_gudang' || $this->uri->segment(2) == 'transaksi_gudang' ? 'active' : ''; ?>">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-chart-line"></i> <span>Laporan</span></a>
                <ul class="dropdown-menu">
                    <li <?=  $this->uri->segment(2) == 'transaksi_gudang' ? 'class="active"' : "" ?>>
                        <a class="nav-link" href="<?= base_url(); ?>transaksi_gudang/transaksi_gudang">
                            <span>Transaksi Gudang</span>
                        </a>
                    </li>
                    <li <?=  $this->uri->segment(2) == 'stok_gudang' ? 'class="active"' : "" ?>>
                        <a class="nav-link" href="<?= base_url(); ?>stok_gudang/stok_gudang">
                            <span>Stok Gudang</span>
                        </a>
                    </li>
                    
                    <?php if(active_role() != "entitas_vendor") : ?>
                    <li <?=  $this->uri->segment(2) == 'transaksi_divisi' ? 'class="active"' : "" ?>>
                        <a class="nav-link" href="<?= base_url(); ?>transaksi_divisi/transaksi_divisi">
                            <span>Transaksi Divisi</span>
                        </a>
                    </li>
                    <li <?=  $this->uri->segment(2) == 'stok_divisi' ? 'class="active"' : "" ?>>
                        <a class="nav-link" href="<?= base_url(); ?>stok_divisi/stok_divisi">
                            <span>Stok Divisi</span>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endif; ?>
        
        <?php if(isManager() || isAdmin()) : ?>    
            <li <?=  $this->uri->segment(2) == 'user_management' ? 'class="active"' : "" ?>>
                <a class="nav-link" href="<?= base_url(); ?>user_management/user_management">
                    <i class="fas fa-bars"></i>
                    <span>User Management</span>
                </a>
            </li>
        <?php endif; ?>
            
            <li class="dropdown <?php echo $this->uri->segment(2) == 'user_profile' ? 'active' : ''; ?>">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-user"></i> <span>User Profile</span></a>
                <ul class="dropdown-menu">
                    <li <?=  $this->uri->segment(2) == 'user_profile' ? 'class="active"' : "" ?>>
                        <a class="nav-link" href="<?= base_url(); ?>user_profile/user_profile">
                            <span>Profile</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="nav-link" data-toggle="modal" data-target="#logout">
                            <span>Log Out</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </aside>
</div>

<!-- Modal -->
<div class="modal fade" id="logout" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Story Pie</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Anda Yakin Keluar Dari Sistem Storypie ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <a class="btn btn-danger" href="<?= base_url() ?>auth/logout">Keluar</a>
            </div>
        </div>
    </div>
</div>
