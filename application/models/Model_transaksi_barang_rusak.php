<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_transaksi_barang_rusak extends CI_Model
{
    function tabel(){
        $tabel = "faktur_barang_rusak";
        return $tabel;
    }
    
    // Ambil Data
    public function get_all()
    {
        $this->db->select('
            fbr.*,
            g.nama as nama_gudang,
            k.nama as nama_karyawan
        ')
        ->from('faktur_barang_rusak fbr')
        ->join('gudang g', 'g.id=fbr.id_gudang', 'left')
        ->join('karyawan k', 'k.id=fbr.id_karyawan', 'left');
        return $this->db->get()->result();
    }
    
    //ambil last id faktur
    // public function get_last_id()
    // {
    //     return $this->db->select("*")->limit(1)->order_by('id',"DESC")->get($this->tabel())->row();
    // }
    public function get_last_id()
    {
        $current_year = date('Y'); 
    
        return $this->db
            ->select("*")
            ->where('YEAR(created_at) =', $current_year)
            ->limit(1)
            ->order_by('id', 'DESC')
            ->get($this->tabel())
            ->row();
    }
    
    //ambil last id faktur
    public function get_last_detail_id()
    {
        return $this->db->select("*")->limit(1)->order_by('id',"DESC")->get('faktur_barang_rusak_detail')->row();
    }
    
    //ambil data faktur by id
    public function get_by_id($id_faktur)
    {
        $this->db->select('
            fbr.*,
            g.nama as nama_gudang,
            k.nama as nama_karyawan
        ')
        ->from('faktur_barang_rusak fbr')
        ->join('gudang g', 'g.id=fbr.id_gudang', 'left')
        ->join('karyawan k', 'k.id=fbr.id_karyawan', 'left')
        ->where('fbr.id', $id_faktur);
        return $this->db->get()->result();
    }
    
    // AMbil detail data faktur
    public function get_detail_faktur($id_faktur)
    {
        $this->db->select('
            fbrd.kode as kode,
            fbrd.id_produk as id_produk,
            fbrd.buah as jumlah_buah,
            fbrd.kulit as jumlah_kulit,
            fbrd.bonggol as jumlah_bonggol,
            fbrd.lain as jumlah_lain,
            fbrd.jumlah as jumlah,
            fbrd.satuan as satuan,
            fbr.id_gudang as id_gudang,
            p.nama as nama_produk
        ')
        ->from('faktur_barang_rusak_detail fbrd')
        ->join('faktur_barang_rusak fbr', 'fbr.id=fbrd.id_faktur', 'left')
        ->join('produk p', 'p.id=fbrd.id_produk', 'left')
        ->where('fbrd.id_faktur', $id_faktur);
        return $this->db->get()->result();
    }
    
    // total faktur
    public function get_total_faktur($id_gudang=null){
        $this->db->select('
            count(fbr.id) as jumlah_faktur
        ')
        ->from('faktur_barang_rusak fbr');
        
        if($id_gudang){
            $this->db->where('fbr.id_gudang', $id_gudang);
        }
        
        return $this->db->get()->result();
    }
    
    // total barang rusak
    public function get_total_barang_rusak($id_gudang=null){
        $this->db->select('
            sum(fbrd.jumlah * p.harga_satuan) as total_rusak
        ')
        ->from('faktur_barang_rusak_detail fbrd')
        ->join('faktur_barang_rusak fbr', 'fbr.id=fbrd.id_faktur')
        ->join('produk p', 'p.id=fbrd.id_produk');
        
        if($id_gudang){
            $this->db->where('fbr.id_gudang', $id_gudang);
        }
        
        return $this->db->get()->result();
    }

    // Tambah faktur
    public function tambah_faktur($user_id="")
    {
        $data_formula_list = [
                'no_faktur'     => $this->input->post('no_faktur'),
                'urut'          => $this->input->post('kode_urut'),
                'id_karyawan'   => $this->input->post('karyawan'),
                'id_gudang'     => $this->input->post('gudang'),
                'tgl_faktur'    => date_db_format($this->input->post('tanggal_faktur')),
                'lampiran'      => $this->input->post('lampiran'),
                'created_by'    => activeId()
            ];
        $this->db->insert($this->tabel(), $data_formula_list);
    }
    
    // tambah detail formula
    public function tambah_faktur_detail($id_faktur, $id_detail)
    {
        $itung      = $this->input->post('itung');
        $id_gudang  = $this->input->post('gudang');
        $urut       = (int)$id_detail;
        for($i=1; $i<=$itung; $i++){
            $urut   = $urut+1;
            $kulit = 0;
            $buah = 0;
            $bonggol = 0;
            $buah   = $this->input->post('buah_'.$i);
            $kulit  = $this->input->post('kulit_'.$i);
            $bonggol= $this->input->post('bonggol_'.$i);
            $lain   = $this->input->post('lain_'.$i);
            $total  = $buah + $kulit + $bonggol + $lain;
            
            $data = [
                'id_faktur' => $id_faktur,
                'kode'      => 'T-BR-'.$urut,
                'id_produk' => $this->input->post('id_produk_'.$i),
                'buah'      => $buah,
                'kulit'     => $kulit,
                'bonggol'   => $bonggol,
                'lain'      => $lain,
                'satuan'    => $this->input->post('satuan_dasar_'.$i)
            ];
            
            if($data['id_produk'] != 0){
                $this->db->insert('faktur_barang_rusak_detail', $data);
                
                // add transaksi gudang
                $dt_transaksi_gudang = [
                    'id_gudang'             => $id_gudang,
                    'id_faktur'             => $data['id_faktur'],
                    'jenis_transaksi'       => 'keluar',
                    'deskripsi_transaksi'   => 'rusak',
                    'kode_faktur'           => $this->input->post('no_faktur'),
                    'kode_detail'           => $data['kode'],
                    'id_produk'             => $data['id_produk'],
                    'jumlah'                => $total,
                    'tgl_faktur'            => date_db_format($this->input->post('tanggal_faktur'))
                ];
                $this->Model_gudang->add_transaksi_gudang($dt_transaksi_gudang);
                
                // update stok di gudang
                $ambil_stok_gudang = $this->Model_gudang->get_produk_stok_gudang($id_gudang, $data['id_produk']);
                $dt_stok_dari_gudang = [
                    'id_gudang' => $id_gudang,
                    'id_produk' => $data['id_produk'],
                    'stok_out'  => (float)$ambil_stok_gudang[0]->stok_out + (float)$total
                ];
                $this->Model_gudang->update_stok_gudang('out', $dt_stok_dari_gudang);
                
                //update stok produk
                $this->Model_Produk->update_stock_all($data['id_produk']);
            }
        }
    }
    
    // Hapus
    public function hapus($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->tabel());
        
        $this->db->where('id_faktur', $id);
        $this->db->delete('faktur_barang_rusak_detail');
    }
    
    // filter ------------------------------------------------------------------------------------------------------------------------------
    var $table = 'faktur_barang_rusak';
    var $column_order = array(null, 'no_faktur', 'no_invoice', 'tgl_faktur', 'id_supplier'); //set column field database for datatable orderable
    var $column_search = array('tgl_faktur'); //set column field database for datatable searchable 
    var $order = array('id' => 'DESC'); // default order
 
    private function _get_datatables_query()
    {
        //add custom filter here
        if($this->input->post('filter_dari_tgl')){
            $this->db->where('tgl_faktur >=', $this->input->post('filter_dari_tgl'));
        }
        if($this->input->post('filter_ke_tgl')){
            $this->db->where('tgl_faktur <=', $this->input->post('filter_ke_tgl'));
        }
        if($this->input->post('filter_gudang') && $this->input->post('filter_gudang')!="nothing"){
            $this->db->where('id_gudang', $this->input->post('filter_gudang'));
        }
        if($this->input->post('filter_karyawan') && $this->input->post('filter_karyawan')!="nothing"){
            $this->db->where('id_karyawan', $this->input->post('filter_karyawan'));
        }
        
        $this->db->select('
            fbr.*,
            g.nama as nama_gudang,
            k.nama as nama_karyawan
        ')
        ->from('faktur_barang_rusak fbr')
        ->join('gudang g', 'g.id=fbr.id_gudang', 'left')
        ->join('karyawan k', 'k.id=fbr.id_karyawan', 'left');

        $i = 0;
        foreach ($this->column_search as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                 
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
         
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
 
    public function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    public function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
 
    public function get_list_countries()
    {
        $this->db->select('country');
        $this->db->from($this->table);
        $this->db->order_by('country','asc');
        $query = $this->db->get();
        $result = $query->result();
 
        $countries = array();
        foreach ($result as $row) 
        {
            $countries[] = $row->country;
        }
        return $countries;
    }
    
}
