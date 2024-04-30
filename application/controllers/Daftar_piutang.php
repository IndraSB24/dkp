<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Daftar_piutang extends CI_Controller
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
        $this->load->model('Model_transaksi_mutasi');
        $this->load->model('Model_transaksi_hutang');
    }
    
    public function model(){
        $model = "Model_transaksi_mutasi";
        return $model;
    }
    
    public function page_home(){
        $home = "daftar_piutang/daftar_piutang";
        return $home;
    }
    
    public function Daftar_piutang()
    {
        $model              = $this->model();
        $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id            = $data['user']['id_username'];
        $data['nama']       = $data['user']['namaUsaha'];
        $data['title']      = 'Daftar Hutang';
        $data['outlet']  = $this->Model_gudang->get_all();
        $data['list_data']  = $this->$model->get_piutang();
        $data['total_piutang']  = $this->$model->get_total_piutang();
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('daftar_piutang/index', $data);
        $this->load->view('template/footer');
    }
    
    public function show($kode, $id_gudang="")
    {
        $model              = $this->model();
        
        if($kode == "detail"){
            $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
            $user_id            = $data['user']['id_username'];
            $data['nama']       = $data['user']['namaUsaha'];
            $data['title']      = 'Detail Faktur Piutang';
            $data['detail_hutang']= $this->$model->get_piutang_by_gudang($id_gudang);
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('daftar_piutang/detail_piutang', $data);
            $this->load->view('template/footer');
        }
    }

    public function bayar($kode="", $deskripsi="")
    {   
        $model      = $this->model();
        $id_faktur  = $this->input->post('id_faktur');
        $no_faktur  = $this->input->post('no_faktur');
        $id_gudang  = $this->input->post('id_vendor');
        $tgl_bayar  = $this->input->post('tgl_bayar');
        
        $this->Model_all->reset_increment('transaksi_hutang');
        $this->Model_transaksi_hutang->tambah($kode, $deskripsi);
        
        $total_bayar = $this->Model_transaksi_hutang->get_totalBayar_by_faktur("piutang", $no_faktur);
        $this->$model->update_bayar_piutang($no_faktur, $total_bayar[0]->total_bayar, $tgl_bayar);
        
        $data_faktur = $this->$model->get_by_id($id_faktur);
        if(($data_faktur[0]->nilai_mutasi - $data_faktur[0]->dibayar) == 0){
            $status = "LUNAS";
        }else{
            $status = "PIUTANG";
        }
        $this->$model->update_status_piutang($no_faktur, $status);
        
        $this->session->set_flashdata('pesan', 'Bayar Piutang');
        redirect('daftar_piutang/show/detail/'.$id_gudang);
    }
    
    // datatables
    public function ajax_list()
    {
        $model          = $this->model();
        $list = $this->Model_transaksi_hutang->get_datatables_piutang();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $baris) {
            $no++;
            $supplier        = '<a href="'.base_url().'daftar_piutang/show/detail/'.$baris->id_ke_gudang.'">'.$baris->ke_gudang.'</a>';
            $aksi   = ' <a class="btn btn-sm btn-success text-light" id="btn-edit" href="'.base_url().'daftar_piutang/show/detail/'.$baris->id_ke_gudang.'">
                            <i class="fas fa-info">&nbsp;&nbsp;Detail</i>
                        </a>';
            $jumlah_faktur  = number_format($baris->jumlah_faktur, 0, ',', '.');
            $total_piutang  = number_format($baris->total_piutang, 2, ',', '.');
            $dibayar  = number_format($baris->dibayar, 2, ',', '.');
            $sisa_piutang  = number_format($baris->total_piutang - $baris->dibayar, 2, ',', '.');
            
            
            $row = array();
            $row[] = $no;
            $row[] = $supplier;
            $row[] = $jumlah_faktur;
            $row[] = $total_piutang;
            $row[] = $dibayar;
            $row[] = $sisa_piutang;
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
