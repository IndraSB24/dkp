<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pembelian extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('username')) {
            redirect('auth');
        }
        $this->load->model('Model_all');
        $this->load->model('Model_transaksi_pembelian');
        $this->load->model('Model_Produk');
        $this->load->model('Model_suplier');
        $this->load->model('Model_gudang');
        $this->load->model('Model_karyawan');
        $this->load->model('Model_stok_gudang');
        $this->load->model('Model_kelompok_produk');
        $this->load->helper('kode_helper');
    }
    
    public function model(){
        $model = "Model_transaksi_pembelian";
        return $model;
    }
    
    public function page_home(){
        $home = "pembelian/pembelian";
        return $home;
    }
    
    public function Pembelian()
    {
        $model              = $this->model();
        $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id            = $data['user']['id_username'];
        $data['nama']       = $data['user']['namaUsaha'];
        $data['title']      = 'Pembelian';
        $data['faktur']     = $this->$model->get_all();
        $data['supplier']   = $this->Model_suplier->get_all();
        $data['data_hutang']= $this->$model->get_total_hutang();
        $data['total_pembelian'] = $this->$model->get_total_pembelian();
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('trans_pembelian/faktur_pembelian', $data);
        $this->load->view('template/footer');
    }
    
    public function show($kode, $id_faktur="")
    {
        $model              = $this->model();
        
        if($kode == "tambah"){
            $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
            $user_id            = $data['user']['id_username'];
            $data['nama']       = $data['user']['namaUsaha'];
            $data['title']      = 'Pembelian';
            
            $bulan = ambil_bulan();
			$tahun = ambil_tahun();
            $last_faktur_id = $this->$model->get_last_id();
            if($last_faktur_id){
                $kode_urut = sprintf("%05d", (int)$last_faktur_id->urut + 1);
            }else{
                $kode_urut = sprintf("%05d", 1);
            }
            $kode = $kode_urut.'/PBL/SP/'.$bulan.'/'.$tahun;
            $data['kode_faktur']    = $kode;
            $data['urut']           = $kode_urut;
            $data['list_produk']    = $this->Model_Produk->get_with_kel_produk();
            $data['list_supplier']  = $this->Model_suplier->get_all();
            $data['list_gudang']    = $this->Model_gudang->get_all();
            $data['list_karyawan']  = $this->Model_karyawan->get_all();
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('trans_pembelian/tambah', $data);
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
            $this->load->view('trans_pembelian/detail_trans_pembelian', $data);
            $this->load->view('template/footer');
        }
    }

    public function tambah()
    {   
        $model              = $this->model();
        $total_pembelian    = 0;
        
        $this->Model_all->reset_increment('faktur_pembelian');
        $this->$model->tambah_faktur();
        
        $this->Model_all->reset_increment('faktur_pembelian_detail');
        $lastId  = $this->$model->get_last_id();
	    $lastId  = $lastId->id;
	    $lastDetailId  = $this->$model->get_last_detail_id();
	    $lastDetailId  = $lastDetailId->id;
        $this->$model->tambah_faktur_detail($lastId, $lastDetailId);
        
        //ambil data faktur
        $ambil_data = $this->$model->get_detail_faktur($lastId);
        foreach($ambil_data as $row){
            //ambil total pembelian
            $pembelian = $row['harga_beli'];
            $total_pembelian = (float)$total_pembelian + $pembelian;
            $this->$model->update_total_pembelian($lastId, $total_pembelian);
            
            //update harga satuan
            $harga_satuan = 0;
            $data_produk = $this->$model->get_transaksi_produk($row['id_produk']);
            foreach($data_produk as $dp){
                $harga = $dp->harga_satuan;
                $harga_satuan += $harga;
            }
            $harga_satuan = (float)$harga_satuan / count($data_produk);
            $this->Model_Produk->update_harga_satuan($row['id_produk'], $harga_satuan);
        }

        $ambil_kelompok_produk      = $this->Model_kelompok_produk->count_kelompok_produk_notBB();
        for($i=0; $i<$ambil_kelompok_produk[0]->jumlah_kelompok_produk; $i++){
            $ambil_produk_with_formula  = $this->Model_produk_formula->get_all_produk();
            foreach($ambil_produk_with_formula as $row){
                $this->Model_produk_formula->update_harga_satuan_formula($row['id_produk']);
            }
        }
        
        $this->session->set_flashdata('pesan', 'Tambah Data Berhasil');
        redirect($this->page_home());
    }

    // Hapus 
    public function hapus($id)
    {
        $model = $this->model();
        $itung = 0;
        
        //hapus transaksi gudang
        $data_faktur = $this->$model->get_by_id($id);
        $kode_faktur = $data_faktur[0]->no_faktur;
        $this->Model_gudang->delete_by_faktur($kode_faktur);
        
        //ambil data faktur
        $ambil_data = $this->$model->get_detail_faktur($id);
        foreach($ambil_data as $row){
            $id_produk  = $row['id_produk'];
            $id_gudang  = $row['id_gudang'];
            $jumlah     = $row['jumlah_beli'];
            
            // ambil semua id produk yang bertransaksi pada faktur ini
            $produk[$itung] = $id_produk;
            $itung = $itung + 1;
            
            // update stok gudang
            $ambil_stok_gudang = $this->Model_gudang->get_produk_stok_gudang($id_gudang, $id_produk);
            $dt_stok_gudang = [
                'id_gudang' => $id_gudang,
                'id_produk' => $id_produk,
                'stok_in'   => (float)$ambil_stok_gudang[0]->stok_in - (float)$jumlah
            ];
            $this->Model_gudang->update_stok_gudang('in', $dt_stok_gudang);
            
            //update stok produk
            $this->Model_Produk->update_stock_all($id_produk);
        }
        
        //hapus data faktur beserta detail
        $this->$model->hapus($id);
        
        //update harga satuan
        foreach($produk as $p => $id_prod){
            $harga_satuan = 0;
            $data_produk = $this->$model->get_transaksi_produk($id_prod);
            if($data_produk){
                foreach($data_produk as $dp){
                    $harga = $dp->harga_satuan;
                    (float)$harga_satuan += (float)$harga;
                }
                $harga_satuan = (float)$harga_satuan / count($data_produk);   
            }else{
                $harga_satuan = 0;
            }
            $this->Model_Produk->update_harga_satuan($id_produk, $harga_satuan);
        }
        
        $ambil_kelompok_produk      = $this->Model_kelompok_produk->count_kelompok_produk_notBB();
        for($i=0; $i<$ambil_kelompok_produk[0]->jumlah_kelompok_produk; $i++){
            $ambil_produk_with_formula  = $this->Model_produk_formula->get_all_produk();
            foreach($ambil_produk_with_formula as $row){
                $this->Model_produk_formula->update_harga_satuan_formula($row['id_produk']);
            }
        }
        
        $this->session->set_flashdata('pesan', 'Data Berhasil Dihapus');
        redirect($this->page_home());
    }
    
    public function update()
    {
        $model      = $this->model();
        $tgl_bayar  = $this->input->post('tgl_bayar');
        $id_faktur  = $this->input->post('id_faktur');
        
        $data = [
            'tanggal_bayar' => $tgl_bayar
        ];
        $this->$model->update($id_faktur, $data);
        
        $this->session->set_flashdata('pesan', 'Update Data Pembelian');
        redirect(base_url().'/pembelian/show/detail/'.$id_faktur);
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
            $kode_faktur        = '<a href="'.base_url().'pembelian/show/detail/'.$baris->id.'">'.$baris->no_faktur.'</a>';
            $total_pembelian    = "Rp. ". number_format($baris->total_pembelian, 2, ',', '.');
            if($baris->status_hutang == "LUNAS"){
                $status = '<span class="badge badge-success">LUNAS</span>';
            }else{
                $status = '<span class="badge badge-danger">TERHUTANG</span>';
            }
            $aksi   = ' <a class="btn btn-sm btn-success text-light" id="btn-edit" data-toggle="modal" data-target="#modal_edit_'.$baris->id.'">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="'.base_url().'pembelian/hapus/'.$baris->id.'" class="btn btn-sm btn-danger text-light tombol-hapus">
                            <i class="fas fa-trash-alt"></i>
                        </a>';
            
            $row = array();
            $row[] = $no;
            $row[] = $kode_faktur;
            $row[] = $baris->no_invoice;
            $row[] = date('d-m-Y', strtotime($baris->tanggal_faktur));
            $row[] = $baris->nama_supplier;
            $row[] = $total_pembelian;
            $row[] = $status;
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
