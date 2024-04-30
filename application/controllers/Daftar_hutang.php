<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Daftar_hutang extends CI_Controller
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
        $this->load->model('Model_suplier');
        $this->load->model('Model_gudang');
        $this->load->model('Model_karyawan');
        $this->load->model('Model_stok_gudang');
        $this->load->model('Model_transaksi_pembelian');
        $this->load->model('Model_transaksi_hutang');
    }
    
    public function model(){
        $model = "Model_transaksi_pembelian";
        return $model;
    }
    
    public function page_home(){
        $home = "pembelian/pembelian";
        return $home;
    }
    
    public function Daftar_hutang()
    {
        $model              = $this->model();
        $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id            = $data['user']['id_username'];
        $data['nama']       = $data['user']['namaUsaha'];
        $data['title']      = 'Daftar Hutang';
        $data['supplier']  = $this->Model_suplier->get_all();
        $data['list_data']  = $this->$model->get_terhutang();
        $data['data_hutang']= $this->$model->get_total_hutang();
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('daftar_hutang/index', $data);
        $this->load->view('template/footer');
    }
    
    public function show($kode, $id_supplier="")
    {
        $model              = $this->model();
        
        if($kode == "detail"){
            $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
            $user_id            = $data['user']['id_username'];
            $data['nama']       = $data['user']['namaUsaha'];
            $data['title']      = 'Detail Faktur';
            //$data['data_faktur']= $this->$model->get_by_id($id_faktur);
            $data['detail_hutang']= $this->$model->get_terhutang_by_supplier($id_supplier);
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('daftar_hutang/detail_hutang', $data);
            $this->load->view('template/footer');
        }
    }

    public function bayar($kode="", $deskripsi="")
    {   
        $model      = $this->model();
        $id_faktur  = $this->input->post('id_faktur');
        $no_faktur  = $this->input->post('no_faktur');
        $id_supplier= $this->input->post('id_vendor');
        
        $this->Model_all->reset_increment('transaksi_hutang');
        $this->Model_transaksi_hutang->tambah($kode, $deskripsi);
        
        $total_bayar = $this->Model_transaksi_hutang->get_totalBayar_by_faktur("hutang", $no_faktur);
        $this->$model->update_bayar_hutang($no_faktur, $total_bayar[0]->total_bayar);
        
        $data_faktur = $this->$model->get_by_id($id_faktur);
        if(($data_faktur[0]->total_pembelian - $data_faktur[0]->dibayar) == 0){
            $status = "LUNAS";
        }else{
            $status = "TERHUTANG";
        }
        $this->$model->update_status_hutang($no_faktur, $status);
        
        $this->session->set_flashdata('pesan', 'Pembayaran Hutang Berhasil');
        redirect('daftar_hutang/show/detail/'.$id_supplier);
    }
    
    // datatables
    public function ajax_list()
    {
        $model          = $this->model();
        $list = $this->Model_transaksi_hutang->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $baris) {
            $no++;
            $supplier        = '<a href="'.base_url().'daftar_hutang/show/detail/'.$baris->id_supplier.'">'.$baris->nama_supplier.'</a>';
            $aksi   = ' <a class="btn btn-sm btn-success text-light" id="btn-edit" href="'.base_url().'daftar_hutang/show/detail/'.$baris->id_supplier.'">
                            <i class="fas fa-info">&nbsp;&nbsp;Detail</i>
                        </a>';
            $jumlah_faktur  = number_format($baris->jumlah_faktur, 0, ',', '.');
            $total_hutang  = number_format($baris->total_hutang, 2, ',', '.');
            $dibayar  = number_format($baris->dibayar, 2, ',', '.');
            $sisa_hutang  = number_format($baris->total_hutang - $baris->dibayar, 2, ',', '.');
            
            
            $row = array();
            $row[] = $no;
            $row[] = $supplier;
            $row[] = $jumlah_faktur;
            $row[] = $total_hutang;
            $row[] = $dibayar;
            $row[] = $sisa_hutang;
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
