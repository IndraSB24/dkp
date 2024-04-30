<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mutasi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('username')) {
            redirect('auth');
        }
        $this->load->model('Model_all');
        $this->load->model('Model_transaksi_mutasi');
        $this->load->model('Model_harga_jual');
        $this->load->model('Model_Produk');
        $this->load->model('Model_suplier');
        $this->load->model('Model_gudang');
        $this->load->model('Model_karyawan');
        $this->load->helper('kode_helper');
        $this->load->helper('wa_helper');
    }
    
    public function model(){
        $model = "Model_transaksi_mutasi";
        return $model;
    }
    
    public function page_home(){
        $home = "mutasi/mutasi";
        return $home;
    }
    
    public function Mutasi()
    {
        $model              = $this->model();
        $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id            = $data['user']['id_username'];
        $data['nama']       = $data['user']['namaUsaha'];
        $data['title']      = 'Faktur Mutasi Barang';
        
        $data['list_mutasi']= $this->$model->get_all();
        $data['gudang']     = $this->Model_gudang->get_all();
        $data['total_piutang']  = $this->$model->get_total_piutang();
        $data['total_mutasi']   = $this->$model->get_total_mutasi();
        
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('trans_mutasi_barang/faktur_mutasi_barang', $data);
        $this->load->view('template/footer');
    }
    
    public function show($kode, $id_faktur="", $kode_tambah="")
    {
        $model              = $this->model();
        
        if($kode == "tambah"){
            $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
            $user_id            = $data['user']['id_username'];
            $data['nama']       = $data['user']['namaUsaha'];
            $data['title']      = 'Tambah Faktur Mutasi';
            
            $bulan = ambil_bulan();
			$tahun = ambil_tahun();
            $last_faktur_id = $this->$model->get_last_id();
            if($last_faktur_id){
                $kode_urut = sprintf("%05d", (int)$last_faktur_id->urut + 1);
            }else{
                $kode_urut = sprintf("%05d", 1);
            }
            $kode = $kode_urut.'/MTS/SP/'.$bulan.'/'.$tahun;
            $data['kode_faktur']    = $kode;
            $data['urut']           = $kode_urut;
            $data['list_produk']    = $this->Model_Produk->get_with_kel_produk();
            $data['list_gudang']    = $this->Model_gudang->get_all();
            $data['list_karyawan']  = $this->Model_karyawan->get_all();
            $data['harga_jual']     = $this->Model_harga_jual->get_by_bulan(date('m'));
            $data['kode_tambah']    = $kode_tambah;
            
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('trans_mutasi_barang/tambah', $data);
            $this->load->view('template/footer');
            
        }else if($kode == "detail_permintaan"){
            $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
            $user_id            = $data['user']['id_username'];
            $data['nama']       = $data['user']['namaUsaha'];
            $data['title']      = 'Detail Faktur';
            $data['data_faktur']= $this->$model->get_by_id($id_faktur);
            $data['detail_permintaan']= $this->$model->get_detail_permintaan($id_faktur);
            $data['list_karyawan']  = $this->Model_karyawan->get_all();
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('trans_mutasi_barang/detail_trans_mutasi_barang', $data);
            $this->load->view('template/footer');
            
        }else if($kode == "detail_mutasi"){
            $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
            $user_id            = $data['user']['id_username'];
            $data['nama']       = $data['user']['namaUsaha'];
            $data['title']      = 'Detail Faktur';
            $data['data_faktur']= $this->$model->get_by_id($id_faktur);
            $data['detail_mutasi']= $this->$model->get_detail_faktur($id_faktur);
            $data['list_karyawan']  = $this->Model_karyawan->get_all();
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('trans_mutasi_barang/detail_dimutasi', $data);
            $this->load->view('template/footer');
            
        }else if($kode == "detail_diterima"){
            $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
            $user_id            = $data['user']['id_username'];
            $data['nama']       = $data['user']['namaUsaha'];
            $data['title']      = 'Detail Faktur';
            $data['data_faktur']= $this->$model->get_by_id($id_faktur);
            $data['detail_diterima']= $this->$model->get_detail_faktur($id_faktur);
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('trans_mutasi_barang/detail_diterima', $data);
            $this->load->view('template/footer');
        }
    }

    public function tambah($kode="", $id_faktur="")
    {   
        $model              = $this->model();
        
        if($kode == "permintaan"){
            $this->Model_all->reset_increment('faktur_mutasi');
            $this->$model->tambah_faktur();
            
            $lastId  = $this->$model->get_last_id();
	        $lastId  = $lastId->id;
            $this->Model_all->reset_increment('faktur_mutasi_detail_permintaan');
            $add_permintaan = $this->$model->tambah_detail_permintaan($lastId);
            
            // Notif WA---------------------------------------------------------
            $data_faktur = $this->Model_transaksi_mutasi->get_by_id($lastId);
            $detail_faktur = $this->Model_transaksi_mutasi->get_detail_permintaan($lastId);
            foreach($detail_faktur as $df){
                $list_barang .= '<br>- '.$df->nama_produk.' : '.thousand_separator($df->jumlah).' '.$df->satuan_dasar;
            }
            $data_wa = [
                'no_faktur' => $data_faktur[0]->no_faktur,
                'nama_dari_gudang' => $data_faktur[0]->dari_gudang,
                'nama_ke_gudang' => $data_faktur[0]->ke_gudang,
                'tgl_diminta' => tgl_indo($data_faktur[0]->tgl_faktur),
                'waktu_diminta' => waktu_24($data_faktur[0]->tgl_faktur),
                'nama_peminta' => $data_faktur[0]->nama_karyawan,
                'list_barang_diminta' => $list_barang,
                'keterangan_minta' => $data_faktur->lampiran ? $data_faktur->lampiran : "-"
            ];
            waPermintaanMutasi($data_wa);
            
            $this->session->set_flashdata('pesan', 'Tambah Data Permintaan Mutasi');
            redirect($this->page_home());
            
        }else if($kode == "mutasi"){
            $data = [
                'disetujui_oleh'=> $this->input->post('pemutasi'),
                'tgl_mutasi'    => datetime_db_format(now()),
                'catatan_mutasi'=> $this->input->post('catatan'),
                'mutated_by'    => activeId()
            ];
            $this->$model->update_faktur($id_faktur, $data);
            
    	    $this->Model_all->reset_increment('faktur_mutasi_detail');
    	    $lastDetailId  = $this->$model->get_last_detail_id();
    	    $lastDetailId  = $lastDetailId->id;
            $this->$model->tambah_faktur_detail($id_faktur, $lastDetailId);
            
            // Notif WA---------------------------------------------------------
            $data_faktur = $this->Model_transaksi_mutasi->get_by_id($id_faktur);
            $detail_permintaan = $this->Model_transaksi_mutasi->get_detail_permintaan($id_faktur);
            foreach($detail_permintaan as $diminta){
                $list_barang_diminta .= '<br>- '.$diminta->nama_produk.' : '.thousand_separator($diminta->jumlah).' '.$diminta->satuan_dasar;
            }
            $detail_mutasi = $this->Model_transaksi_mutasi->get_detail_faktur($id_faktur);
            foreach($detail_mutasi as $dimutasi){
                $list_barang_dimutasi .= '<br>- '.$dimutasi->nama_produk.' : '.thousand_separator($dimutasi->jumlah).' '.$dimutasi->satuan_dasar;
            }
            $data_wa = [
                'no_faktur' => $data_faktur[0]->no_faktur,
                'nama_dari_gudang' => $data_faktur[0]->dari_gudang,
                'nama_ke_gudang' => $data_faktur[0]->ke_gudang,
                'tgl_diminta' => tgl_indo($data_faktur[0]->tgl_faktur),
                'waktu_diminta' => waktu_24($data_faktur[0]->tgl_faktur),
                'nama_peminta' => $data_faktur[0]->nama_karyawan,
                'list_barang_diminta' => $list_barang_diminta,
                'keterangan_minta' => $data_faktur->lampiran ? $data_faktur->lampiran : "-",
                'tgl_dikirim' => tgl_indo($data_faktur[0]->tgl_mutasi),
                'waktu_dikirim' => waktu_24($data_faktur[0]->tgl_mutasi),
                'nama_pengirim' => $data_faktur[0]->disetujui_oleh,
                'list_barang_dikirim' => $list_barang_dimutasi,
                'keterangan_kirim' => $data_faktur->catatan_mutasi ? $data_faktur->catatan_mutasi : "-"
            ];
            waPengirimanMutasi($data_wa);
            
            $this->session->set_flashdata('pesan', 'Mutasi Barang');
            redirect($this->page_home());
            
        }else if($kode == "penerimaan"){
            $dari_gudang= $this->input->post('dari_gudang');
            $ke_gudang  = $this->input->post('ke_gudang');
            $total_nilai_mutasi = 0;
            $itung = 0;
            
            if($this->input->post('dari_gudang') == 1 && $this->input->post('ke_gudang') == 2){
                $piutang = 'LUNAS';
            }else{
                $piutang = 'PIUTANG';
            }
            
            $data = [
                'diterima_oleh'     => $this->input->post('penerima'),
                'tgl_diterima'      => datetime_db_format(now()),
                'catatan_penerimaan'=> $this->input->post('catatan_penerimaan'),
                'status_piutang'    => $piutang,
                'approved_by'       => activeId()
            ];
            $this->$model->update_faktur($id_faktur, $data);
            
            //ambil data faktur
            $ambil_data = $this->$model->get_detail_faktur($id_faktur);
            foreach($ambil_data as $row){
                $itung += 1;
                //update penerimaan
                $jumlah_kirim   = $this->input->post('jumlah_kirim_'.$itung);
                $jumlah_terima  = $this->input->post('jumlah_terima_'.$itung);
                $selisih_jumlah = $jumlah_kirim - $jumlah_terima;
                $harga_mutasi   = $jumlah_terima * $row->harga;
                
                $this->$model->update_penerimaan($id_faktur, $row->id_produk, $jumlah_terima, $harga_mutasi);
                
                $total_nilai_mutasi = (float)$total_nilai_mutasi + $harga_mutasi;
                $this->$model->update_nilai_mutasi($id_faktur, $total_nilai_mutasi);
                
                $this->Model_all->reset_increment('gudang_transaksi');
                // add transaksi dari gudang
                $dt_dari_gudang = [
                    'id_gudang'             => $dari_gudang,
                    'id_faktur'             => $id_faktur,
                    'jenis_transaksi'       => 'keluar',
                    'deskripsi_transaksi'   => 'mutasi',
                    'kode_faktur'           => $this->input->post('no_faktur'),
                    'kode_detail'           => $row->kode,
                    'id_produk'             => $row->id_produk,
                    'jumlah'                => $jumlah_terima,
                    'tgl_faktur'            => date_db_format($data['tgl_diterima'])
                ];
                $this->Model_gudang->add_transaksi_gudang($dt_dari_gudang);
                
                // add transaksi ke gudang
                $dt_ke_gudang = [
                    'id_gudang'             => $ke_gudang,
                    'id_faktur'             => $id_faktur,
                    'jenis_transaksi'       => 'masuk',
                    'deskripsi_transaksi'   => 'mutasi',
                    'kode_faktur'           => $this->input->post('no_faktur'),
                    'kode_detail'           => $row->kode,
                    'id_produk'             => $row->id_produk,
                    'jumlah'                => $jumlah_terima,
                    'tgl_faktur'            => date_db_format($data['tgl_diterima'])
                ];
                $this->Model_gudang->add_transaksi_gudang($dt_ke_gudang);
                
                // update stok dari gudang
                $ambil_stok_gudang = $this->Model_gudang->get_produk_stok_gudang($dari_gudang, $row->id_produk);
                $dt_stok_dari_gudang = [
                    'id_gudang' => $dari_gudang,
                    'id_produk' => $row->id_produk,
                    'stok_out'  => (float)$ambil_stok_gudang[0]->stok_out + (float)$jumlah_terima
                ];
                $this->Model_gudang->update_stok_gudang('out', $dt_stok_dari_gudang);
                
                // add or update stok ke gudang
                $ambil_stok_gudang = $this->Model_gudang->get_produk_stok_gudang($ke_gudang, $row->id_produk);
                if($ambil_stok_gudang){
                    // update stok
                    $dt_stok_ke_gudang = [
                        'id_gudang' => $ke_gudang,
                        'id_produk' => $row->id_produk,
                        'stok_in'   => (float)$ambil_stok_gudang[0]->stok_in + (float)$jumlah_terima
                    ];
                    $this->Model_gudang->update_stok_gudang('in', $dt_stok_ke_gudang);
                }else{
                    // add stok
                    $dt_stok_ke_gudang = [
                        'id_gudang' => $ke_gudang,
                        'id_produk' => $row->id_produk,
                        'stok_in'   => $jumlah_terima
                    ];
                    $this->Model_gudang->tambah_stok_gudang($dt_stok_ke_gudang);
                }
                
                if($selisih_jumlah != 0){
                    // add transaksi gudang
                    $dt_transaksi_gudang = [
                        'id_gudang'             => $dari_gudang,
                        'id_faktur'             => $id_faktur,
                        'jenis_transaksi'       => 'keluar',
                        'deskripsi_transaksi'   => 'rusak',
                        'kode_faktur'           => $this->input->post('no_faktur'),
                        'kode_detail'           => 'penyesuaian mutasi',
                        'id_produk'             => $row->id_produk,
                        'jumlah'                => $selisih_jumlah,
                        'tgl_faktur'            => date_db_format($data['tgl_diterima'])
                    ];
                    $this->Model_gudang->add_transaksi_gudang($dt_transaksi_gudang);
                    
                    // update stok di gudang
                    $ambil_stok_gudang = $this->Model_gudang->get_produk_stok_gudang($dari_gudang, $row->id_produk);
                    $dt_stok_dari_gudang_penyesuaian = [
                        'id_gudang' => $dari_gudang,
                        'id_produk' => $row->id_produk,
                        'stok_out'  => (float)$ambil_stok_gudang[0]->stok_out + (float)$selisih_jumlah
                    ];
                    $this->Model_gudang->update_stok_gudang('out', $dt_stok_dari_gudang_penyesuaian);   
                }
                
                //update stok produk
                $this->Model_Produk->update_stock_all($row->id_produk);
            }
            
            // Notif WA---------------------------------------------------------
            $data_faktur = $this->Model_transaksi_mutasi->get_by_id($id_faktur);
            $detail_permintaan = $this->Model_transaksi_mutasi->get_detail_permintaan($id_faktur);
            foreach($detail_permintaan as $diminta){
                $list_barang_diminta .= '<br>- '.$diminta->nama_produk.' : '.thousand_separator($diminta->jumlah).' '.$diminta->satuan_dasar;
            }
            $detail_mutasi = $this->Model_transaksi_mutasi->get_detail_faktur($id_faktur);
            foreach($detail_mutasi as $dimutasi){
                $list_barang_dimutasi .= '<br>- '.$dimutasi->nama_produk.' : '.thousand_separator($dimutasi->jumlah).' '.$dimutasi->satuan_dasar;
                $list_barang_diterima .= '<br>- '.$dimutasi->nama_produk.' : '.thousand_separator($dimutasi->jumlah_diterima).' '.$dimutasi->satuan_dasar;
            }
            $data_wa = [
                'no_faktur' => $data_faktur[0]->no_faktur,
                'nama_dari_gudang' => $data_faktur[0]->dari_gudang,
                'nama_ke_gudang' => $data_faktur[0]->ke_gudang,
                
                'tgl_diminta' => tgl_indo($data_faktur[0]->tgl_faktur),
                'waktu_diminta' => waktu_24($data_faktur[0]->tgl_faktur),
                'nama_peminta' => $data_faktur[0]->nama_karyawan,
                'list_barang_diminta' => $list_barang_diminta,
                'keterangan_minta' => $data_faktur->lampiran ? $data_faktur->lampiran : "-",
                
                'tgl_dikirim' => tgl_indo($data_faktur[0]->tgl_mutasi),
                'waktu_dikirim' => waktu_24($data_faktur[0]->tgl_mutasi),
                'nama_pengirim' => $data_faktur[0]->disetujui_oleh,
                'list_barang_dikirim' => $list_barang_dimutasi,
                'keterangan_kirim' => $data_faktur->catatan_mutasi ? $data_faktur->catatan_mutasi : "-",
                
                'tgl_diterima' => tgl_indo($data_faktur[0]->tgl_diterima),
                'waktu_diterima' => waktu_24($data_faktur[0]->tgl_diterima),
                'nama_penerima' => $data_faktur[0]->diterima_oleh,
                'list_barang_diterima' => $list_barang_diterima,
                'keterangan_terima' => $data_faktur->catatan_penerimaan ? $data_faktur->catatan_penerimaan : "-"
            ];
            waPenerimaanMutasi($data_wa);
            
            $this->session->set_flashdata('pesan', 'Melakukan Penerimaan Mutasi Barang');
            redirect($this->page_home());
            
        }else if($kode == "mutasi_langsung"){
            $this->Model_all->reset_increment('faktur_mutasi');
            $this->$model->tambah_faktur("mutasi_langsung");
            
            $lastId  = $this->$model->get_last_id();
	        $lastId  = $lastId->id;
    	    $lastDetailId  = $this->$model->get_last_detail_id();
    	    $lastDetailId  = $lastDetailId->id;
            $this->Model_all->reset_increment('faktur_mutasi_detail');
            $this->$model->tambah_faktur_detail($lastId, $lastDetailId);
            
            // update transaksi dan stok
            $dari_gudang= $this->input->post('dari_gudang');
            $ke_gudang  = $this->input->post('ke_gudang');
            $total_nilai_mutasi = 0;
            $itung = 0;
            
            //ambil data faktur
            $ambil_data = $this->$model->get_detail_faktur($lastId);
            foreach($ambil_data as $row){
                $itung += 1;
                $harga_mutasi   = $row->jumlah * $row->harga;
                
                $total_nilai_mutasi = (float)$total_nilai_mutasi + $harga_mutasi;
                $this->$model->update_nilai_mutasi($id_faktur, $total_nilai_mutasi);
                
                $this->Model_all->reset_increment('gudang_transaksi');
                // add transaksi dari gudang
                $dt_dari_gudang = [
                    'id_gudang'             => $dari_gudang,
                    'id_faktur'             => $id_faktur,
                    'jenis_transaksi'       => 'keluar',
                    'deskripsi_transaksi'   => 'mutasi',
                    'kode_faktur'           => $this->input->post('no_faktur'),
                    'kode_detail'           => $row->kode,
                    'id_produk'             => $row->id_produk,
                    'jumlah'                => $row->jumlah,
                    'tgl_faktur'            => date_db_format($this->input->post('tanggal_faktur'))
                ];
                $this->Model_gudang->add_transaksi_gudang($dt_dari_gudang);
                
                // add transaksi ke gudang
                $dt_ke_gudang = [
                    'id_gudang'             => $ke_gudang,
                    'id_faktur'             => $id_faktur,
                    'jenis_transaksi'       => 'masuk',
                    'deskripsi_transaksi'   => 'mutasi',
                    'kode_faktur'           => $this->input->post('no_faktur'),
                    'kode_detail'           => $row->kode,
                    'id_produk'             => $row->id_produk,
                    'jumlah'                => $row->jumlah,
                    'tgl_faktur'            => date_db_format($this->input->post('tanggal_faktur'))
                ];
                $this->Model_gudang->add_transaksi_gudang($dt_ke_gudang);
                
                // update stok dari gudang
                $ambil_stok_gudang = $this->Model_gudang->get_produk_stok_gudang($dari_gudang, $row->id_produk);
                $dt_stok_dari_gudang = [
                    'id_gudang' => $dari_gudang,
                    'id_produk' => $row->id_produk,
                    'stok_out'  => (float)$ambil_stok_gudang[0]->stok_out + (float)$row->jumlah
                ];
                $this->Model_gudang->update_stok_gudang('out', $dt_stok_dari_gudang);
                
                // add or update stok ke gudang
                $ambil_stok_gudang = $this->Model_gudang->get_produk_stok_gudang($ke_gudang, $row->id_produk);
                if($ambil_stok_gudang){
                    // update stok
                    $dt_stok_ke_gudang = [
                        'id_gudang' => $ke_gudang,
                        'id_produk' => $row->id_produk,
                        'stok_in'   => (float)$ambil_stok_gudang[0]->stok_in + (float)$row->jumlah
                    ];
                    $this->Model_gudang->update_stok_gudang('in', $dt_stok_ke_gudang);
                }else{
                    // add stok
                    $dt_stok_ke_gudang = [
                        'id_gudang' => $ke_gudang,
                        'id_produk' => $row->id_produk,
                        'stok_in'   => $row->jumlah
                    ];
                    $this->Model_gudang->tambah_stok_gudang($dt_stok_ke_gudang);
                }
                
                //update stok produk
                $this->Model_Produk->update_stock_all($row->id_produk);
            }
            
            // Notif WA---------------------------------------------------------
            $data_faktur = $this->Model_transaksi_mutasi->get_by_id($lastId);
            $detail_mutasi = $this->Model_transaksi_mutasi->get_detail_faktur($lastId);
            foreach($detail_mutasi as $dimutasi){
                $list_barang_dimutasi .= '<br>- '.$dimutasi->nama_produk.' : '.thousand_separator($dimutasi->jumlah).' '.$dimutasi->satuan_dasar;
            }
            $data_wa = [
                'no_faktur' => $data_faktur[0]->no_faktur,
                'nama_dari_gudang' => $data_faktur[0]->dari_gudang,
                'nama_ke_gudang' => $data_faktur[0]->ke_gudang,
                
                'tgl_dimutasi' => tgl_indo($data_faktur[0]->tgl_mutasi),
                'waktu_dimutasi' => waktu_24($data_faktur[0]->tgl_faktur),
                'nama_pemutasi' => $data_faktur[0]->nama_karyawan,
                'list_barang_dimutasi' => $list_barang_dimutasi,
                'keterangan_mutasi' => $data_faktur->lampiran ? $data_faktur->lampiran : "-"
            ];
            waMutasiLangsung($data_wa);
            
            $this->session->set_flashdata('pesan', 'Tambah Data Mutasi Langsung');
            redirect($this->page_home());
        }
    }

    // Hapus 
    public function hapus($id)
    {
        $model = $this->model();
        $itung = 0;
        
        //ambil data faktur
        $data_faktur    = $this->$model->get_by_id($id);
        $jenis_mutasi   = $data_faktur[0]->jenis_mutasi;
        $kode_faktur    = $data_faktur[0]->no_faktur;
        $dari_gudang    = $data_faktur[0]->id_dari_gudang;
        $ke_gudang      = $data_faktur[0]->id_ke_gudang;
        $ambil_data     = $this->$model->get_detail_faktur($data_faktur[0]->id);
        foreach($ambil_data as $row){
            $id_produk_mutasi   = $row->id_produk;
            
            // kurangi stok out dari gudang
            $ambil_stok_gudang = $this->Model_gudang->get_produk_stok_gudang($dari_gudang, $id_produk_mutasi);
            $dt_out_dari_gudang = [
                'id_gudang' => $dari_gudang,
                'id_produk' => $id_produk_mutasi,
                'stok_out'   => (float)$ambil_stok_gudang[0]->stok_out - (float)$row->jumlah
            ];
            $this->Model_gudang->update_stok_gudang('out', $dt_out_dari_gudang);
            
            // kurangi stok in ke gudang
            if($jenis_mutasi == "normal"){
                $jumlah_terima = $row->jumlah_diterima;
            }else{
                $jumlah_terima = $row->jumlah;
            }
            $ambil_stok_gudang = $this->Model_gudang->get_produk_stok_gudang($ke_gudang, $id_produk_mutasi);
            $dt_in_ke_gudang = [
                'id_gudang' => $ke_gudang,
                'id_produk' => $id_produk_mutasi,
                'stok_in'   => (float)$ambil_stok_gudang[0]->stok_in - (float)$jumlah_terima
            ];
            $this->Model_gudang->update_stok_gudang('in', $dt_in_ke_gudang);
            
            //hapus transaksi gudang
            $this->Model_gudang->delete_by_faktur($kode_faktur);
        }
        
        //hapus data faktur beserta detail
        $this->$model->hapus($id);

        $this->session->set_flashdata('pesan', 'Data Berhasil Dihapus');
        redirect($this->page_home());
    }
    
    public function update($kode="")
    {
        $model          = $this->model();
        $data['user']   = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id        = $data['user']['id_username'];
        
        if($kode == "harga_mutasi"){
            $data = [
                'harga_jual' => $this->input->post('harga_mutasi')
            ];
            $this->$model->update_faktur($data);
            $this->session->set_flashdata('pesan', 'Update Harga Mutasi');
            redirect($this->page_home());    
        }
    }
    
    // datatables
    public function ajax_list()
    {
        $model= $this->model();
        $list = $this->$model->get_datatables();
        $data = array();
        $itung= 0;
        $no   = $_POST['start'];
        foreach ($list as $baris) {
            $no++;
            if($baris->tgl_mutasi == NULL){
                $kode_detail= "detail_permintaan";
                $tgl_mutasi = '<span class="badge badge-danger">BELUM DIMUTASI</span>';
                $stat       = '<span class="badge badge-danger">DIMINTA</span>';
            }else if($baris->tgl_diterima == NULL){
                $kode_detail= "detail_mutasi";
                $tgl_mutasi = tglwaktu_indo($baris->tgl_mutasi);
                $stat       = '<span class="badge badge-warning">DIMUTASI</span>';
            }else{
                $tgl_mutasi = tglwaktu_indo($baris->tgl_mutasi);
                $kode_detail= "detail_diterima";
                $stat       = '<span class="badge badge-success">DITERIMA</span>';
            }
            
            if($baris->jenis_mutasi == "normal"){
                $tgl_diminta= tglwaktu_indo($baris->tgl_faktur);
            }else{
                $tgl_mutasi = tgl_indo($baris->tgl_mutasi).' '.waktu_24($baris->tgl_faktur);
                $tgl_diminta= '<span class="badge badge-info">MUTASI LANGSUNG</span>';
                $stat       = '<span class="badge badge-success">DITERIMA</span>';
            }
            
            if($baris->status_piutang == "PIUTANG"){
                $status_piutang = '<span class="badge badge-warning">PIUTANG</span>';
            }else if($baris->status_piutang == "LUNAS"){
                $status_piutang = '<span class="badge badge-success">LUNAS</span>';
            }else{
                $status_piutang = '<span class="badge badge-danger">BELUM ADA</span>';
            }
            
            $kode_faktur= '<a href="'.base_url().'mutasi/show/'.$kode_detail.'/'.$baris->id.'">'.$baris->no_faktur.'</a>';
            $aksi       = '
                        <a href="'.base_url().'mutasi/hapus/'.$baris->id.'" class="btn btn-sm btn-danger text-light tombol-hapus">
                            <i class="fas fa-trash-alt"></i>
                        </a>';
            
                $itung = $itung + 1;
                $row = array();
                $row[] = $no;
                $row[] = $kode_faktur;
                $row[] = $tgl_diminta;
                $row[] = $tgl_mutasi;
                $row[] = $baris->dari_gudang;
                $row[] = $baris->ke_gudang;
                $row[] = $stat;
                $row[] = $status_piutang;
                $row[] = $aksi;
                $data[] = $row;
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->$model->count_all(),
            "recordsFiltered" => $this->$model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    
    // get data
    public function get_harga_jual()
    {
        $dari = $this->input->post('dari');
        $ke = $this->input->post('ke');
        $id_produk = $this->input->post('id_produk');
        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');

        // Validate input data (you can add more validation if needed)
        if (empty($dari) || empty($ke) || empty($id_produk) || empty($bulan) || empty($tahun)) {
            echo json_encode(['error' => 'Invalid input data']);
            return;
        }
    
        // Fetch data from the model
        $hargaJual = $this->Model_harga_jual->get_for_mutasi($dari, $ke, $id_produk, $bulan, $tahun);
    
        // Check if data is available
        if (!empty($hargaJual) && isset($hargaJual[0]->harga_jual)) {
            echo json_encode(['harga_jual' => $hargaJual[0]->harga_jual]);
        } else {
            echo json_encode(['harga_jual' => 0]);
        }
    }



}
