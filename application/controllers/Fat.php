<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\BorderStyle;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;


class Fat extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('username')) {
            redirect('auth');
        }
        // $this->load->library('PhpSpreadsheet');
        $this->load->model('Model_all');
        $this->load->model('Model_transaksi_fat');
        $this->load->model('Model_transaksi_penjualan');
        $this->load->model('Model_Produk');
        $this->load->model('Model_suplier');
        $this->load->model('Model_gudang');
        $this->load->model('Model_karyawan');
        $this->load->model('Model_stok_gudang');
        $this->load->model('Model_kelompok_produk');
        $this->load->helper('kode_helper');
        $this->load->helper('Excel_helper');
    }
    
    public function model(){
        $model = "Model_transaksi_fat";
        return $model;
    }
    
    public function page_home(){
        $home = "fat/fat";
        return $home;
    }
    
    public function Fat()
    {
        $model              = $this->model();
        $data['title']      = 'Cash Drawer';
        
        $data['list_gudang']    = $this->Model_gudang->get_all();
        $data['list_karyawan']  = $this->Model_karyawan->get_all();
        $data['list_data']      = $this->$model->get_all();
            
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('fat/index', $data);
        $this->load->view('template/footer');
    }
    
    public function show($kode="", $param1="", $param2="")
    {
        $model              = $this->model();
        
        if($kode == "tambah_data"){
            $data['title']      = 'Tambah Data';
            $data['list_gudang']= $this->Model_gudang->get_all();
            
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('fat/tambah', $data);
            $this->load->view('template/footer');
            
        }else if($kode == "detail"){
            $id = $param1;
            $data['title']      = 'Detail Per Shift';
            $data['data']= $this->Model_transaksi_fat->get_by_id($id);
            $data['data_other'] = $this->Model_transaksi_fat->get_other_by_fat_id($id);
            
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('fat/detail', $data);
            $this->load->view('template/footer');
            
        }else if($kode == "by_date"){
            $date       = $param1;
            $id_entitas = $param2;
            $data['title']      = 'Detail Cash Drawer';
            $data['data_fat']       = $this->Model_transaksi_fat->get_fat($date, $id_entitas);
            $data['data_fat_other'] = $this->Model_transaksi_fat->get_fat_other($date, $id_entitas);
            
            $ids = $this->Model_transaksi_fat->get_fat_ids($date, $id_entitas);
            $data['id_0'] = false;
            $data['id_1'] = false;
            foreach ($ids as $index => $row) {
                $data['id_' . $index] = $row->id;
            }

            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('fat/detail_by_date', $data);
            $this->load->view('template/footer');
        }
    }

    public function tambah($kode="")
    {   
        $model              = $this->model();
        
        $this->Model_all->reset_increment('fat');
        $this->$model->tambah();
        $this->session->set_flashdata('pesan', 'Tambah Data Cash Drawer');
        redirect($this->page_home());
    }
    
    // Hapus 
    public function hapus($id){
        $model = $this->model();
        $this->$model->hapus($id);
        $this->session->set_flashdata('pesan', 'Hapus Data');
        redirect($this->page_home());
    }
    
    // update
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
    public function table_data()
    {
        $model          = $this->model();
        $list = $this->$model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $baris) {
            $no++;
            $tanggal	= '<a href="'.base_url('fat/show/by_date/'.$baris->tanggal.'/'.$baris->id_entitas).'">'.tgl_indo($baris->tanggal).'</a>';
            $aksi   = ' 
                <a href="'.base_url('fat/show/detail/'.$baris->id).'" class="btn btn-sm btn-info text-light">
                    <i class="fas fa-info"> Detail</i>
                </a>
                <a href="'.base_url('fat/hapus/'.$baris->id).'" class="btn btn-sm btn-danger text-light tombol-hapus">
                    <i class="fas fa-trash-alt"></i>
                </a>
            ';
            
            $row = array();
            $row[] = $no;
            $row[] = role()=='1' ? tgl_indo($baris->tanggal) : $tanggal;
            $row[] = 'SHIFT '.$baris->shift;
            $row[] = $baris->nama_outlet;
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
    
    // export FAT
    public function export_fat($date, $id_entitas) {
        // get data
        $data_fat       = $this->Model_transaksi_fat->get_fat($date, $id_entitas);
        $data_fat_other = $this->Model_transaksi_fat->get_fat_other($date, $id_entitas);
        
        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set default styles
        $defaultStyle = $spreadsheet->getDefaultStyle();
        $defaultFont = $spreadsheet->getDefaultStyle()->getFont();
        $defaultFont->setSize(11);
        $defaultFont->setName('Calibri');
        $defaultStyle->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $defaultStyle->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(40);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(20);
        
        $alignLeft  = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT
            ]
        ];
        $alignRight = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT
            ]
        ];
        $alignCenter = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            ]
        ];
        
        $sideBorder = [
            'borders' => [
                'left' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                ],
                'right' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                ],
            ]
        ];
        $bottomBorder = [
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                ]
            ]
        ];
        
        // set B column to left
        $sheet->getStyle('B')->applyFromArray($alignLeft);
        
        // header style
        $headerStyle = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                ],
            ],
        ];
        $sheet->getStyle('A3:H6')->applyFromArray($headerStyle);
        
        // Header
        $sheet->setCellValue('A3', 'TANGGAL');
        $sheet->mergeCells('A3:A6');
        
        $sheet->setCellValue('B3', 'KETERANGAN');
        $sheet->mergeCells('B3:B6');
        
        $sheet->setCellValue('C3', 'PETTY CASH');
        $sheet->mergeCells('C3:D3');
        $sheet->setCellValue('C4', 'DEBET');    $sheet->setCellValue('D4', 'KREDIT');
        $sheet->setCellValue('C5', '-');        $sheet->setCellValue('D5', '-');
        $sheet->setCellValue('C6', 'SALDO');    $sheet->setCellValue('D6', '-');
        $sheet->getStyle('C5')->applyFromArray($alignRight);
        $sheet->getStyle('D5')->applyFromArray($alignRight);
        $sheet->getStyle('D6')->applyFromArray($alignRight);

        $sheet->setCellValue('E3', 'OJOL');
        $sheet->mergeCells('E3:F3');
        $sheet->setCellValue('E4', 'DEBET');    $sheet->setCellValue('F4', 'KREDIT');
        $sheet->setCellValue('E5', '-');        $sheet->setCellValue('F5', '-');
        $sheet->setCellValue('E6', 'SALDO');    $sheet->setCellValue('F6', '-');
        $sheet->getStyle('E5')->applyFromArray($alignRight);
        $sheet->getStyle('F5')->applyFromArray($alignRight);
        $sheet->getStyle('F6')->applyFromArray($alignRight);

        $sheet->setCellValue('G3', 'BSM OPR');
        $sheet->mergeCells('G3:H3');
        $sheet->setCellValue('G4', 'DEBET');    $sheet->setCellValue('H4', 'KREDIT');
        $sheet->setCellValue('G5', '-');        $sheet->setCellValue('H5', '-');
        $sheet->setCellValue('G6', 'SALDO');    $sheet->setCellValue('H6', '-');
        $sheet->getStyle('G5')->applyFromArray($alignRight);
        $sheet->getStyle('H5')->applyFromArray($alignRight);
        $sheet->getStyle('H6')->applyFromArray($alignRight);

        // Set cell colors
        // Yellow
        $yellowColor = 'FFFF00';
        $cellRanges = [
            'B11:B12', 'B14:B16', 'B19', 'B21:B22',
            'F11:F12', 'F14:F15',
            'D16',
            'H19', 'H21:H22'
        ];
        foreach ($cellRanges as $cellRange) {
            $sheet->getStyle($cellRange)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($yellowColor);
        }
        
        // Green
        $greenColor = '92D050';
        $cellRanges = [
            'C7:C8',
            'D9:D10', 'D13', 'D17:D18', 'D20', 'D23:D35',
            'E10', 'E13',
            'G17:G18', 'G20', 'G23'
        ];
        foreach ($cellRanges as $cellRange) {
            $sheet->getStyle($cellRange)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($greenColor);
        }

        
        // set row value
        $items = array(
            'Omzet Outlet',
            'Dp yang di Terima',
            'Dp yang di Ambil',
            'Gojek',
            'Potongan gojek',
            'Potongan promo gojek',
            'Grab',
            'Potongan Grab',
            'Potongan promo Grab',
            'Potongan Outlet',
            'Setor Tunai Kasir',
            'ShopeePay',
            'Potongan ShopeePay',
            'Shopee food',
            'Potongan Shopee food',
            'Potongan Promo Shopeefood',
            'Transfer BSI',
            'Voucher Karyawan Produksi',
            'Discount Karyawan Produksi',
            'Voucher Karyawan Gumik',
            'Discount karyawan Gumik',
            'Voucher Karyawan',
            'Discount Karyawan',
            'Pie Give Away',
            'Kurir Bahan Baku',
            'Tebus Point',
            'Pisang',
            'Kopi',
            'Air Galon',
        );
        for ($i = 0; $i < count($items); $i++) {
            $rowNumber = $i + 7;
            $sheet->setCellValue('A' . $rowNumber, tgl_indo($data_fat[0]->tanggal)); 
            $sheet->setCellValue('B' . $rowNumber, $items[$i]);
        }
        
        $sheet->setCellValue('C7', $data_fat[0]->total_omzet_outlet_debet_pc);
        $sheet->setCellValue('C8', $data_fat[0]->total_dp_diterima_debet_pc);
        $sheet->setCellValue('D9', $data_fat[0]->total_dp_diambil_kredit_pc);
        $sheet->setCellValue('D10', $data_fat[0]->total_gojek_kredit_pc); $sheet->setCellValue('E10', $data_fat[0]->total_gojek_debet_o);
        $sheet->setCellValue('D13', $data_fat[0]->total_grab_kredit_pc);  $sheet->setCellValue('E13', $data_fat[0]->total_grab_debet_o);
        $sheet->setCellValue('D17', $data_fat[0]->total_setor_kredit_pc); $sheet->setCellValue('G17', $data_fat[0]->total_setor_debet_bo);
        $sheet->setCellValue('D18', $data_fat[0]->total_shopeepay_kredit_pc); $sheet->setCellValue('G18', $data_fat[0]->total_shopeepay_debet_bo);
        $sheet->setCellValue('D20', $data_fat[0]->total_shopeefood_kredit_pc); $sheet->setCellValue('G20', $data_fat[0]->total_shopeefood_debet_bo);
        $sheet->setCellValue('D23', $data_fat[0]->total_tf_bsi_kredit_pc); $sheet->setCellValue('G23', $data_fat[0]->total_tf_bsi_debet_bo);
        $sheet->setCellValue('D24', $data_fat[0]->total_voucher_karyawan_produksi_kredit_pc);
        $sheet->setCellValue('D25', $data_fat[0]->total_diskon_karyawan_produksi_kredit_pc);
        $sheet->setCellValue('D26', $data_fat[0]->total_voucher_karyawan_gumik_kredit_pc);
        $sheet->setCellValue('D27', $data_fat[0]->total_diskon_karyawan_gumik_kredit_pc);
        $sheet->setCellValue('D28', $data_fat[0]->total_voucher_karyawan_kredit_pc);
        $sheet->setCellValue('D29', $data_fat[0]->total_diskon_karyawan_kredit_pc);
        $sheet->setCellValue('D30', $data_fat[0]->total_pie_give_away_kredit_pc);
        $sheet->setCellValue('D31', $data_fat[0]->total_kurir_bahan_baku_kredit_pc);
        $sheet->setCellValue('D32', $data_fat[0]->total_tebus_point_kredit_pc);
        $sheet->setCellValue('D33', $data_fat[0]->total_pisang_kredit_pc);
        $sheet->setCellValue('D34', $data_fat[0]->total_kopi_kredit_pc);
        $sheet->setCellValue('D35', $data_fat[0]->total_air_galon_kredit_pc);
        
        $data = array_merge($data_fat_other);
        foreach ($data as $row => $rowData) {
            $rowNumber = $row + 36;
        
            $sheet->setCellValue('A' . $rowNumber, tgl_indo($data_fat[0]->tanggal)); 
            $sheet->setCellValue('B' . $rowNumber, $rowData->keterangan); 
            $sheet->setCellValue('C' . $rowNumber, '');
            $sheet->setCellValue('D' . $rowNumber, $rowData->nominal);
        }
        
        // Set edge border
        $highestRow = $sheet->getHighestRow();
        for ($row = 7; $row <= $highestRow; $row++) {
            for ($column = 'A'; $column <= 'H'; $column++) {
                $sheet->getStyle($column . $row)->applyFromArray($sideBorder);
            }
        }
        $sheet->getStyle('A' . $highestRow . ':H' . $highestRow)->applyFromArray($bottomBorder);
        
        // set currency format
        $thousandSeparatorFormat = '#,##0';
        for ($row = 7; $row <= $highestRow; $row++) {
            for ($column = 'C'; $column <= 'H'; $column++) {
                $sheet->getStyle($column . $row)->getNumberFormat()->setFormatCode($thousandSeparatorFormat);
            }
        }
        
        // Cell Orange Color
        if($highestRow >= 36){
            $orangeColor = 'F7C033';
            $cellRanges = [
                'B36:B'.$highestRow, 'D36:D'.$highestRow
            ];
            foreach ($cellRanges as $cellRange) {
                $sheet->getStyle($cellRange)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($orangeColor);
            }
        }

        // Save the spreadsheet as an Excel file
        $filename = 'Exported_CashDrawer_'.$data_fat[0]->nama_outlet.'_'.tgl_indo($data_fat[0]->tanggal).'.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);

        // Download the Excel file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        readfile($filename);
        unlink($filename); // Remove the temporary file after download
        exit;
    }

}
