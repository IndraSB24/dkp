<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mutasi_divisi extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        if (!$this->session->userdata('username')) {
            redirect('auth');
        }
        $this->load->model('Model_all');
        $this->load->model('Model_transaksi_mutasi_divisi');
        $this->load->model('Model_Produk');
        $this->load->model('Model_suplier');
        $this->load->model('Model_divisi');
        $this->load->model('Model_karyawan');
        $this->load->helper('kode_helper');
    }
    
    public function model(){
        $model = "Model_transaksi_mutasi_divisi";
        return $model;
    }
    
    public function page_home(){
        $home = "mutasi_divisi/mutasi_divisi";
        return $home;
    }
    
    public function mutasi_divisi()
    {
        $model              = $this->model();
        $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id            = $data['user']['id_username'];
        $data['nama']       = $data['user']['namaUsaha'];
        $data['title']      = strtoupper('Faktur Mutasi Divisi');
        
        $data['list_mutasi']    = $this->$model->get_all();
        $data['divisi']         = $this->Model_divisi->get_all();
        $data['gudang']         = $this->Model_gudang->get_all_no_array();
        $data['total_piutang']  = $this->$model->get_total_piutang();
        $data['total_mutasi']   = $this->$model->get_total_mutasi();
        
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('trans_mutasi_divisi/faktur_mutasi_divisi', $data);
        $this->load->view('template/footer');
    }
    
    public function show($kode, $id_faktur="")
    {
        $model              = $this->model();
        
        if($kode == "tambah"){
            $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
            $user_id            = $data['user']['id_username'];
            $data['nama']       = $data['user']['namaUsaha'];
            $data['title']      = 'Tambah Faktur Mutasi Divisi';
            
            $bulan = ambil_bulan();
			$tahun = ambil_tahun();
            $last_faktur_id = $this->$model->get_last_id();
            if($last_faktur_id){
                $kode_urut = sprintf("%05d", (int)$last_faktur_id->urut + 1);
            }else{
                $kode_urut = sprintf("%05d", 1);
            }
            $data['nama_gudang']    = $this->input->post('nama_gudang'); 
            $kode = $kode_urut.'/MD/'.str_replace(" ", "-", $data['nama_gudang']).'/'.$bulan.'/'.$tahun;
            $data['kode_faktur']    = $kode;
            $data['urut']           = $kode_urut;
            $data['list_produk']    = $this->Model_Produk->get_with_kel_produk();
            $data['list_gudang']    = $this->Model_gudang->get_all();
            $data['list_divisi']    = $this->Model_divisi->get_all($this->input->post('id_gudang'));
            $data['list_karyawan']  = $this->Model_karyawan->get_all();
            $data['harga_jual']     = $this->Model_harga_jual->get_by_bulan(date('m'));
            
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('trans_mutasi_divisi/tambah', $data);
            $this->load->view('template/footer');
            
        }else if($kode == "detail"){
            $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
            $user_id            = $data['user']['id_username'];
            $data['nama']       = $data['user']['namaUsaha'];
            $data['title']      = 'Detail Faktur';
            $data['data_faktur']= $this->$model->get_by_id($id_faktur);
            $data['detail_mutasi'] = $this->$model->get_detail_faktur($id_faktur);
            $data['list_karyawan']  = $this->Model_karyawan->get_all();
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('trans_mutasi_divisi/detail_mutasi_divisi', $data);
            $this->load->view('template/footer');
            
        }
    }

    public function tambah($kode="", $id_faktur="")
    {   
        $model              = $this->model();
        
        $this->Model_all->reset_increment('faktur_mutasi_divisi');
        $this->$model->tambah_faktur();
            
        $lastId  = $this->$model->get_last_id();
	    $lastId  = $lastId->id;
        $this->Model_all->reset_increment('faktur_mutasi_divisi_detail');
        $this->$model->tambah_faktur_detail($lastId);
        
        //ambil data faktur
        $ambil_data = $this->$model->get_detail_faktur($lastId);
        foreach($ambil_data as $row){
            //ambil input value
            $dari_divisi= $this->input->post('dari_divisi');
            $ke_divisi  = $this->input->post('ke_divisi');
            
            //reset id increment
            $this->Model_all->reset_increment('gudang_divisi_transaksi');
            $this->Model_all->reset_increment('gudang_divisi_stok');
            
            // add transaksi out dari divisi
            $dt_out_dari_divisi = [
                'id_divisi'             => $dari_divisi,
                'id_faktur'             => $lastId,
                'jenis_transaksi'       => 'keluar',
                'deskripsi_transaksi'   => 'mutasi divisi',
                'kode_faktur'           => $this->input->post('no_faktur'),
                'id_produk'             => $row->id_produk,
                'jumlah'                => $row->jumlah
            ];
            $this->Model_divisi->add_transaksi_divisi($dt_out_dari_divisi);
                
            // add transaksi out dari divisi
            $dt_in_ke_divisi = [
                'id_divisi'             => $ke_divisi,
                'id_faktur'             => $lastId,
                'jenis_transaksi'       => 'masuk',
                'deskripsi_transaksi'   => 'mutasi divisi',
                'kode_faktur'           => $this->input->post('no_faktur'),
                'id_produk'             => $row->id_produk,
                'jumlah'                => $row->jumlah
            ];
            $this->Model_divisi->add_transaksi_divisi($dt_in_ke_divisi);
            
            // cek and add produk to divisi
            $this->Model_divisi->auto_add_stok($dari_divisi, $row->id_produk);
            $this->Model_divisi->auto_add_stok($ke_divisi, $row->id_produk);
            
            // update stok produk di divisi
            $this->Model_divisi->auto_update_stock_per_divisi($dari_divisi, $row->id_produk);
            $this->Model_divisi->auto_update_stock_per_divisi($ke_divisi, $row->id_produk);
        }

        $this->session->set_flashdata('pesan', 'Tambah Data Mutasi Divisi');
        redirect($this->page_home());
    }

    // Hapus 
    public function hapus($id)
    {
        $model = $this->model();
        
        //hapus data faktur beserta detail
        $this->$model->hapus($id);

        $this->session->set_flashdata('pesan', 'Hapus Data Faktur Mutasi Divisi');
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
        $no   = $_POST['start'];
        foreach ($list as $baris) {
            $no++;
            $kode_faktur= '<a href="'.base_url().'/mutasi_divisi/show/detail/'.$baris->id.'">'.$baris->no_faktur.'</a>';
            $aksi       = '
                        <a href="'.base_url().'mutasi_divisi/hapus/'.$baris->id.'" class="btn btn-sm btn-danger text-light tombol-hapus">
                            <i class="fas fa-trash-alt"></i>
                        </a>';
            $row = array();
            $row[] = $no;
            $row[] = $kode_faktur;
            $row[] = tglwaktu_indo($baris->tgl_faktur);
            $row[] = $baris->gudang;
            $row[] = $baris->dari_divisi;
            $row[] = $baris->ke_divisi;
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
