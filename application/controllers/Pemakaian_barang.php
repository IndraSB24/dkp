<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pemakaian_barang extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('username')) {
            redirect('auth');
        }
        $this->load->helper('kode_helper');
        $this->load->model('Model_all');
        $this->load->model('Model_Produk');
        $this->load->model('Model_gudang');
        $this->load->model('Model_karyawan');
        $this->load->model('Model_transaksi_pemakaian_barang');
    }
    
    public function model(){
        $model = "Model_transaksi_pemakaian_barang";
        return $model;
    }
    
    public function page_home(){
        $home = "pemakaian_barang/Pemakaian_barang";
        return $home;
    }
    
    public function Pemakaian_barang()
    {
        $model              = $this->model();
        $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id            = $data['user']['id_username'];
        $data['nama']       = $data['user']['namaUsaha'];
        $data['title']      = 'Pemakaian Barang';
        
        $data['list_data']  = $this->$model->get_all();
        $data['gudang']  = $this->Model_gudang->get_all();
        $data['karyawan']  = $this->Model_karyawan->get_all();
        $data['total_faktur'] = $this->$model->get_total_faktur();
        $total_pemakaian    = $this->$model->get_total_pemakaian();
        if($total_pemakaian){
            $data['total_pemakaian'] = $this->$model->get_total_pemakaian();
        }else{
            $data['total_pemakaian'] = 0;
        }
        
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('trans_pemakaian_barang/faktur_pemakaian_barang', $data);
        $this->load->view('template/footer');
    }
    
    public function show($kode, $id_faktur="")
    {
        $model              = $this->model();
        
        if($kode == "tambah"){
            $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
            $user_id            = $data['user']['id_username'];
            $data['nama']       = $data['user']['namaUsaha'];
            $data['title']      = 'Pemakaian Barang';
            
            $bulan = ambil_bulan();
			$tahun = ambil_tahun();
            $last_faktur_id = $this->$model->get_last_id();
            if($last_faktur_id){
                $kode_urut = sprintf("%05d", (int)$last_faktur_id->id + 1);
            }else{
                $kode_urut = sprintf("%05d", 1);
            }
            $kode = $kode_urut.'/PMK/SP/'.$bulan.'/'.$tahun;
            $data['kode_faktur']    = $kode;
            $data['list_produk']    = $this->Model_Produk->get_with_kel_produk();
            $data['list_gudang']    = $this->Model_gudang->get_all();
            $data['list_karyawan']  = $this->Model_karyawan->get_all();
            
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('trans_pemakaian_barang/tambah', $data);
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
            $this->load->view('trans_pemakaian_barang/detail_trans_pemakaian_barang', $data);
            $this->load->view('template/footer');
        }
    }

    public function tambah()
    {   
        $model              = $this->model();
        
        $this->Model_all->reset_increment('faktur_pemakaian_barang');
        $this->$model->tambah_faktur();
        
        $lastId  = $this->$model->get_last_id();
	    $lastId  = $lastId->id;
	    $this->Model_all->reset_increment('faktur_pemakaian_barang_detail');
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
        $data_faktur    = $this->$model->get_by_id($id);
        $kode_faktur    = $data_faktur[0]->no_faktur;
        $id_gudang      = $data_faktur[0]->id_gudang;
        
        //hapus transaksi gudang
        $this->Model_gudang->delete_by_faktur($kode_faktur);
        
        $ambil_data     = $this->$model->get_detail_faktur($data_faktur[0]->id);
        foreach($ambil_data as $row){
            $id_produk_barang_rusak = $row->id_produk;
            
            // kurangi stok out gudang
            $ambil_stok_gudang = $this->Model_gudang->get_produk_stok_gudang($id_gudang, $id_produk_barang_rusak);
            $dt_out_dari_gudang = [
                'id_gudang' => $id_gudang,
                'id_produk' => $id_produk_barang_rusak,
                'stok_out'   => (int)$ambil_stok_gudang[0]->stok_out - (int)$row->jumlah
            ];
            $this->Model_gudang->update_stok_gudang('out', $dt_out_dari_gudang);
            
            //update stok produk
            $this->Model_Produk->update_stock_all($id_produk);
        }
        
        //hapus data faktur beserta detail
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
    
    // datatables
    public function ajax_list()
    {
        $model          = $this->model();
        $list = $this->$model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $baris) {
            $no++;
            $kode_faktur        = '<a href="'.base_url().'pemakaian_barang/show/detail/'.$baris->id.'">'.$baris->no_faktur.'</a>';
            $aksi   = ' <a class="btn btn-sm btn-success text-light" id="btn-edit" data-toggle="modal" data-target="#modal_edit_'.$baris->id.'">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="'.base_url().'pemakaian_barang/hapus/'.$baris->id.'" class="btn btn-sm btn-danger text-light tombol-hapus">
                            <i class="fas fa-trash-alt"></i>
                        </a>';
            
            $row = array();
            $row[] = $no;
            $row[] = $kode_faktur;
            $row[] = $baris->tgl_faktur;
            $row[] = $baris->nama_karyawan;
            $row[] = $baris->nama_gudang;
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
