<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Harga_jual extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        if (!$this->session->userdata('username')) {
            redirect('auth');
        }
        $this->load->model('Model_all');
        $this->load->model('Model_harga_jual');
        $this->load->model('Model_gudang');
        $this->load->model('Model_produk');
        $this->load->helper('Excel_helper');
    }
    
    public function model(){
        $model = "Model_harga_jual";
        return $model;
    }
    
    public function page_home(){
        $home = "harga_jual/harga_jual";
        return $home;
    }
    
    public function harga_jual(){
        $model          = $this->model();
        $data['user']   = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id        = $data['user']['id_username'];
        $data['nama']   = $data['user']['namaUsaha'];
        $data['title']  = 'Data Harga Jual';
        $data['list_data']  = $this->$model->get_all();
        $data['list_gudang']= $this->Model_gudang->get_all();
        $data['list_produk']= $this->Model_Produk->get_all_sorted();
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('harga_jual/index_', $data);
        $this->load->view('template/footer');
    }
    
    public function show($kode, $id_produk=""){
        $model          = $this->model();
        
        if($kode == "detail"){
            $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
            $user_id            = $data['user']['id_username'];
            $data['nama']       = $data['user']['namaUsaha'];
            $data['title']      = 'Detail Harga Jual';
            $data['data_harga_jual']= $this->$model->get_by_produk($id_produk);
            $data['list_tahun'] = $this->$model->get_tahun();
            $data['id_produk']  = $id_produk;
            $data_produk = $this->Model_Produk->getById($id_produk);
            $data['nama_produk']= $data_produk[0]->nama;
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('harga_jual/detail', $data);
            $this->load->view('template/footer');
            
        }else if($kode == "import"){
            $data['title']      = 'Import Data Harga Jual';
            
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('harga_jual/import_data', $data);
            $this->load->view('template/footer');
        }
    }

    public function tambah(){   
        $model              = $this->model();
        
        $this->Model_all->reset_increment('produk_harga_jual');
        $this->$model->tambah();
        $this->session->set_flashdata('pesan', 'Tambah Data Berhasil');
        redirect($this->page_home());
    }

    // Hapus 
    public function hapus($id){
        $model = $this->model();
        $this->$model->hapus($id);
        $this->session->set_flashdata('pesan', 'Data Berhasil Dihapus');
        redirect($this->page_home());
    }
    
    // Update
    public function update(){
        $model          = $this->model();
        
        $this->$model->update();
        $this->session->set_flashdata('pesan', 'Update Data Harga Jual');
        redirect($this->page_home());
    }
    
    // datatables
    public function table_data(){
        $model= $this->model();
        $list = $this->$model->get_datatables();
        $data = array();
        $itung= 0;
        $no   = $_POST['start'];
        foreach ($list as $baris) {
            $no++;
            $kode_produk= '<a href="'.base_url().'harga_jual/show/'.$baris->kode_produk.'">'.$baris->kode_produk.'</a>';
            $harga_jual ='
                <div class="price-container">
                    <span>Rp.</span>
                    <span>'.currency_indo($baris->harga_jual).'</span>
                </div>
            ';
            $aksi       = '
                <a href="'.base_url().'harga_jual/hapus/'.$baris->id.'" class="btn btn-sm btn-danger text-light tombol-hapus">
                    <i class="fas fa-trash-alt"></i>
                </a>
            ';
            
            $itung = $itung + 1;
            $row = array();
            $row[] = $no;
            $row[] = $kode_produk;
            $row[] = $baris->nama_produk;
            $row[] = $baris->satuan_dasar;
            $row[] = $baris->dari_entitas;
            $row[] = $baris->ke_entitas;
            $row[] = $harga_jual;
            $row[] = strtoupper(bulan_indo($baris->bulan));
            $row[] = $baris->tahun;
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
    
    public function export_template()
    {
        $entitas_data = $this->Model_gudang->get_for_export();
        $produk_data = $this->Model_produk->get_for_export();
    
        $spreadsheet = new Spreadsheet();
        
        // set default style
        $defaultStyle = $spreadsheet->getDefaultStyle();
        $defaultFont = $defaultStyle->getFont();
        $defaultFont->setSize(11);
        $defaultFont->setName('Calibri');
        $defaultStyle->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $defaultStyle->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        // header style
        $headerStyle = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => '92D050'],
            ],
        ];

        // Create the Entitas sheet
        $entitasSheet = $spreadsheet->getActiveSheet();
        $entitasSheet->setTitle('Entitas');
        $entitasSheet->fromArray($entitas_data, null, 'A1');
        $entitasSheet->getColumnDimension('A')->setWidth(40);
        $entitasSheet->getStyle('A')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $entitasSheet->getStyle('A1')->applyFromArray($headerStyle);
    
        // Create the Produk sheet
        $produkSheet = $spreadsheet->createSheet();
        $produkSheet->setTitle('Produk');
        $produkSheet->fromArray($produk_data, null, 'A1');
        $produkSheet->getColumnDimension('A')->setWidth(40);
        $produkSheet->getColumnDimension('B')->setWidth(40);
        $produkSheet->getColumnDimension('C')->setWidth(15);
        $produkSheet->getStyle('A')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $produkSheet->getStyle('A1:C1')->applyFromArray($headerStyle);
    
        // Create the Template (Import) sheet
        $templateSheet = $spreadsheet->createSheet();
        $templateSheet->setTitle('Import');
        $template_data = array(
            array('Nama Produk', 'Dari Entitas', 'Ke Entitas', 'Harga Jual', 'Bulan', 'Tahun'),
        );
        $templateSheet->fromArray($template_data, null, 'A1');
        $templateSheet->getColumnDimension('A')->setWidth(40);
        $templateSheet->getColumnDimension('B')->setWidth(30);
        $templateSheet->getColumnDimension('C')->setWidth(30);
        $templateSheet->getColumnDimension('D')->setWidth(20);
        $templateSheet->getColumnDimension('E')->setWidth(15);
        $templateSheet->getColumnDimension('F')->setWidth(15);
        $templateSheet->getStyle('A1:F1')->applyFromArray($headerStyle);
    
        // Create the Writer object and specify the output file name
        $writer = new Xlsx($spreadsheet);
        $filename = 'template_import_harga_jual.xlsx';
    
        // Set the headers for browser download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
    
        // Write the spreadsheet to the browser
        $writer->save('php://output');
        exit;
    }
    
    // import data
    public function import_harga_jual()
    {
        $model = $this->model();
    
        print_r('HIT HERE!!!!');
    
        if (isset($_FILES["fileExcel"]["name"])) {
            $path = $_FILES["fileExcel"]["tmp_name"];
            $spreadsheet = IOFactory::load($path);
    
            $sheetToProcess = 'Import';
            $worksheet = $spreadsheet->getSheetByName($sheetToProcess);
    
            if (!$worksheet) {
                $this->session->set_flashdata('error', 'Sheet not found');
                redirect($_SERVER['HTTP_REFERER']);
            }
    
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();
            
            for ($row = 2; $row <= $highestRow; $row++) {
                $produk = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                $dari_entitas = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                $ke_entitas = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                $harga_jual = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                $bulan = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                $tahun = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                
                // $this->session->set_flashdata('pesan', 'Produk: '.$produk);
                // redirect($_SERVER['HTTP_REFERER']);
                
                if ($produk) {
                    // ambil ID produk
                    $data_produk = $this->Model_produk->get_where('p.nama', $produk);
                    if (!$data_produk) {
                        $this->session->set_flashdata('pesan', 'Data Terkait Produk Tidak Ditemukan');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $produk_id = $data_produk[0]['id'];
                    }
    
                    // ambil ID dari entitas
                    $data_dari_entitas = $this->Model_gudang->get_where('nama', $dari_entitas);
                    if (!$data_dari_entitas) {
                        $this->session->set_flashdata('pesan', 'Data Terkait Dari Entitas Tidak Ditemukan');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $dari_entitas_id = $data_dari_entitas[0]['id'];
                    }
    
                    // ambil ID ke entitas
                    $data_ke_entitas = $this->Model_gudang->get_where('nama', $ke_entitas);
                    if (!$data_ke_entitas) {
                        $this->session->set_flashdata('pesan', 'Data Terkait Ke Entitas Tidak Ditemukan');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $ke_entitas_id = $data_ke_entitas[0]['id'];
                    }
    
                    $temp_data[] = array(
                        'id_gudang'     => 0,
                        'dari_gudang'   => $dari_entitas_id,
                        'ke_gudang'     => $ke_entitas_id,
                        'id_produk'     => $produk_id,
                        'harga_jual'    => $harga_jual,
                        'tgl_periode'   => date('Y-m-d'), // Use the appropriate date format
                        'bulan'         => $bulan,
                        'tahun'         => $tahun,
                        'created_by'    => activeId(),
                        'created_at'    => date('Y-m-d H:i:s') // Use the appropriate date format
                    );
                }
            }
    
            if (!empty($temp_data)) {
                // Call the model's method to insert the data into the database
                $insert = $this->$model->add_from_excel($temp_data);
    
                if ($insert) {
                    $this->session->set_flashdata('pesan', 'Import Data ke Database');
                    redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_flashdata('error', 'Terjadi Kesalahan');
                    redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->session->set_flashdata('error', 'Tidak ada data yang diimport');
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            echo "Tidak ada file yang masuk";
        }
    }


}
