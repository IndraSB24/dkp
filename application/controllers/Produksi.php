<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produksi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('username')) {
            redirect('auth');
        }
        $this->load->model('Model_all');
        $this->load->model('Model_transaksi_produksi');
        $this->load->model('Model_Produk');
        $this->load->model('Model_produk_formula');
        $this->load->model('Model_suplier');
        $this->load->model('Model_gudang');
        $this->load->model('Model_karyawan');
        $this->load->helper('kode_helper');
    }
    
    public function model(){
        $model = "Model_transaksi_produksi";
        return $model;
    }
    
    public function page_home(){
        $home = "produksi/produksi";
        return $home;
    }
    
    public function Produksi()
    {
        $model              = $this->model();
        $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id            = $data['user']['id_username'];
        $data['nama']       = $data['user']['namaUsaha'];
        $data['title']      = 'Produksi';
        
        $data['data_faktur']    = $this->$model->get_all();
        $data['data_karyawan']    = $this->Model_karyawan->get_all();
        $data['list_gudang']    = $this->Model_gudang->get_list();
        
        if(substr(hak_akses(),0,3)=="ADM" && !isAdmin()){
            $data['total_produksi'] = 0;
            $lokasi = substr(hak_akses(),6);
            $faktur = $this->$model->get_total_faktur_where($lokasi);
            $data['total_faktur'] = $faktur[0]->total_faktur;
            if($data['total_faktur']){
                $list_id_faktur = $this->$model->get_id_faktur_list($lokasi);
                foreach($list_id_faktur as $row){
                    $produksi = $this->$model->get_total_produksi_where($row->id_faktur);
                    if($produksi){
                        $data['total_produksi'] = $data['total_produksi'] + (float)$produksi[0]->total_produksi;
                    }
                }
            }
        }else{
            $total_produksi   = $this->$model->get_total_produksi_all();
            if($total_produksi){
                $produksi = $this->$model->get_total_produksi_all();
                $data['total_produksi'] = $produksi[0]->total_produksi;
            }else{
                $data['total_produksi'] = 0;
            }
            $total_faktur   = $this->$model->get_total_faktur();
            if($total_faktur){
                $faktur = $this->$model->get_total_faktur();
                $data['total_faktur'] = $faktur[0]->total_faktur;
            }else{
                $data['total_faktur'] = 0;
            }
        }
        
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('trans_produksi/faktur_produksi', $data);
        $this->load->view('template/footer');
    }
    
    public function show($kode, $id_faktur="")
    {
        $model              = $this->model();
        
        if($kode == "tambah"){
            $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
            $user_id            = $data['user']['id_username'];
            $data['nama']       = $data['user']['namaUsaha'];
            $data['title']      = 'Faktur Produksi';
            
            $harga_satuan_produk = array("");
            $bulan = ambil_bulan();
			$tahun = ambil_tahun();
            $last_faktur_id = $this->$model->get_last_id();
            if($last_faktur_id){
                $kode_urut = sprintf("%05d", (int)$last_faktur_id->urut + 1);
            }else{
                $kode_urut = sprintf("%05d", 1);
            }
            $kode = $kode_urut.'/PRD/SP/'.$bulan.'/'.$tahun;
            
            $data['kode_faktur']    = $kode;
            $data['urut']           = $kode_urut;
            $data['list_produk']    = $this->Model_Produk->getProdukWithFormula();
            $data['list_supplier']  = $this->Model_suplier->get_all();
            $data['list_gudang']    = $this->Model_gudang->get_all();
            $data['list_karyawan']  = $this->Model_karyawan->get_all();
            
            foreach($data['list_produk'] as $row){
                $id_produk      = $row['id'];
                $harga_satuan   = 0;
                $ambil_detail_produk = $this->Model_produk_formula->get_detail_formula_by_produk($id_produk);
                foreach($ambil_detail_produk as $adp){
                    $harga_satuan += (int)$adp['harga_satuan'] * (int)$adp['jumlah_bahan'];
                    $stok_kini  = (int)$adp['stok_masuk'] - (int)$adp['stok_keluar'];
                    $id_bahan   = $adp['id_bahan'];
                    $data['stok_terkini'][$id_produk][$id_bahan] = $stok_kini;
                    $data['jumlah_bahan'][$id_produk][$id_bahan] = $adp['jumlah_bahan'];
                    
                }
                $data['harga_satuan'][$id_produk] = $harga_satuan;
            }
            
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('trans_produksi/tambah', $data);
            $this->load->view('template/footer');
            
        }else if($kode == "detail"){
            $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
            $user_id            = $data['user']['id_username'];
            $data['nama']       = $data['user']['namaUsaha'];
            $data['title']      = 'Detail Faktur';
            $data['data_faktur']= $this->$model->get_by_id($id_faktur);
            $data['detail_faktur']= $this->$model->get_detail_faktur($id_faktur);
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('trans_produksi/detail_trans_produksi', $data);
            $this->load->view('template/footer');
        }
    }

    public function tambah()
    {   
        $model              = $this->model();
        
        $this->Model_all->reset_increment('faktur_produksi');
        $this->$model->tambah_faktur();
        
        $lastId  = $this->$model->get_last_id();
	    $lastId  = $lastId->id;
	    $this->Model_all->reset_increment('faktur_produksi_detail');
	    $lastDetailId  = $this->$model->get_last_detail_id();
	    $lastDetailId  = $lastDetailId->id;
        $this->$model->tambah_faktur_detail($lastId, $lastDetailId);
        
        $this->session->set_flashdata('pesan', 'Tambah Data Berhasil');
        redirect($this->page_home());
    }

    // Hapus 
    public function hapus($id)
    {
        $model = $this->model();
        $itung = 0;
        
        //ambil data faktur
        $ambil_data = $this->$model->get_detail_faktur($id);
        foreach($ambil_data as $row){
            $id_produk      = $row->id_produk;
            $id_gudang      = $row->id_gudang;
            $jumlah_produksi= $row->jumlah;
            
            //hapus transaksi gudang
            $data_faktur = $this->$model->get_by_id($id);
            $kode_faktur = $data_faktur[0]->no_faktur;
            $this->Model_gudang->delete_by_faktur($kode_faktur);
            
            // kurangi stok gudang
            $ambil_stok_gudang = $this->Model_gudang->get_produk_stok_gudang($id_gudang, $id_produk);
            $dt_stok_gudang = [
                'id_gudang' => $id_gudang,
                'id_produk' => $id_produk,
                'stok_in'   => (float)$ambil_stok_gudang[0]->stok_in - (float)$jumlah_produksi
            ];
            $this->Model_gudang->update_stok_gudang('in', $dt_stok_gudang);
            
            //update stok produk
            $this->Model_Produk->update_stock_all($id_produk);
            
            // ambil bahan yang digunakan
            $ambil_bahan = $this->Model_produk_formula->get_detail_formula_by_produk_no_array($id_produk);
            if($ambil_bahan){
                foreach($ambil_bahan as $ab){
                    $id_bahan   = $ab->id_bahan;
                    $total_bahan= (float)$jumlah_produksi * (float)$ab->jumlah_bahan;
                        
                    // kurangi stok out bahan
                    $ambil_stok_gudang = $this->Model_gudang->get_produk_stok_gudang($id_gudang, $id_bahan);
                    $dt_up_stok_bahan = [
                        'id_gudang' => $id_gudang,
                        'id_produk' => $id_bahan,
                        'stok_out'  => (float)$ambil_stok_gudang[0]->stok_out - (float)$total_bahan
                    ];
                    $this->Model_gudang->update_stok_gudang('out', $dt_up_stok_bahan);
                    
                    //update stok bahan
                    $this->Model_Produk->update_stock_all($id_bahan);
                }
            }
        }
        
        //hapus faktur beserta detail
        $this->$model->hapus($id);
        
        $this->session->set_flashdata('pesan', 'Data Berhasil Dihapus');
        redirect($this->page_home());
    }
    
    public function update()
    {
        $model          = $this->model();
        $data['user']   = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id        = $data['user']['id_username'];
        
        $this->$model->update($user_id);
        $this->session->set_flashdata('pesan', 'Update Data Departemen');
        redirect($this->page_home());
    }
    
    public function cek_ketersediaan_stok()
    {
        $id_gudang  = $this->post->input('id_gudang', true);
        $id_produk  = $this->post->input('id_produk', true);
        $jumlah     = $this->post->input('jumlah', true);
        if($id_gudang != 0){
                $ambil_detail_produk = $this->Model_produk_formula->get_detail_formula_by_produk_no_array($id_produk);
                foreach($ambil_detail_produk as $adp){
                    $jumlah_bahan = $adp->jumlah_bahan * $jumlah;
                    $ambil_stok = $this->Model_gudang->get_produk_stok_gudang($id_gudang, $adp->id_bahan);
                    if($ambil_stok){
                        $cek_stok = ((int)$ambil_stok[0]->stok_in - (int)$ambil_stok[0]->stok_out) - (int)$jumlah_bahan;
                        if((int)$cek_stok < 0){
                            $status = $adp->nama_produk." Kurang untuk produksi";
                        }else{
                            $status = "ok";
                        }
                    }else{
                        $status = $adp->nama_produk." Tidak ada stok di Gudang";
                    }
                    
                    $count_stat += 1;
                }
            }
        $dt = array (
            'stat' => $status
        );
        echo json_encode($dt);
        //die;
    }
    
    // datatables
    public function ajax_list()
    {
        $model          = $this->model();
        $list = $this->$model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $baris) {
            $no++;
            $kode_faktur        = '<a href="'.base_url().'produksi/show/detail/'.$baris->id.'">'.$baris->no_faktur.'</a>';
            $aksi   = ' <a class="btn btn-sm btn-success text-light" id="btn-edit" data-toggle="modal" data-target="#modal_edit_'.$baris->id.'">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="'.base_url().'produksi/hapus/'.$baris->id.'" class="btn btn-sm btn-danger text-light tombol-hapus">
                            <i class="fas fa-trash-alt"></i>
                        </a>';
            
            $row = array();
            $row[] = $no;
            $row[] = $kode_faktur;
            $row[] = date('d-m-Y', strtotime($baris->tgl_faktur));
            $row[] = $baris->nama_gudang;
            $row[] = $baris->nama_karyawan;
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
    

}
