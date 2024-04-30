<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_transaksi_pemakaian_barang extends CI_Model
{
    function tabel(){
        $tabel = "faktur_pemakaian_barang";
        return $tabel;
    }
    
    // Ambil Data
    public function get_all()
    {
        $this->db->select('
            fpb.*,
            g.nama as nama_gudang,
            k.nama as nama_karyawan
        ')
        ->from('faktur_pemakaian_barang fpb')
        ->join('gudang g', 'g.id=fpb.id_gudang')
        ->join('karyawan k', 'k.id=fpb.id_karyawan');
        return $this->db->get()->result();
    }
    
    //ambil last id faktur
    public function get_last_id()
    {
        return $this->db->select("*")->limit(1)->order_by('id',"DESC")->get($this->tabel())->row();
    }
    
    //ambil last id faktur
    public function get_last_detail_id()
    {
        return $this->db->select("*")->limit(1)->order_by('id',"DESC")->get('faktur_pemakaian_barang_detail')->row();
    }
    
    //ambil data faktur by id
    public function get_by_id($id_faktur)
    {
        $this->db->select('
            fpb.*,
            g.nama as nama_gudang,
            k.nama as nama_karyawan
        ')
        ->from('faktur_pemakaian_barang fpb')
        ->join('gudang g', 'g.id=fpb.id_gudang')
        ->join('karyawan k', 'k.id=fpb.id_karyawan')
        ->where('fpb.id', $id_faktur);
        return $this->db->get()->result();
    }
    
    // AMbil detail data faktur
    public function get_detail_faktur($id_faktur)
    {
        $this->db->select('
            fpbd.kode as kode,
            fpbd.id_produk as id_produk,
            fpbd.jumlah as jumlah,
            fpbd.satuan as satuan,
            fpb.id_gudang as id_gudang,
            p.nama as nama_produk
        ')
        ->from('faktur_pemakaian_barang_detail fpbd')
        ->join('faktur_pemakaian_barang fpb', 'fpb.id=fpbd.id_faktur')
        ->join('produk p', 'p.id=fpbd.id_produk')
        ->where('fpbd.id_faktur', $id_faktur);
        return $this->db->get()->result();
    }
    
    // total pemakaian
    public function get_total_faktur(){
        $this->db->select('
            count(fpb.id) as jumlah_faktur
        ')
        ->from('faktur_pemakaian_barang fpb');
        return $this->db->get()->result();
    }
    
    // total pemakaian
    public function get_total_pemakaian(){
        $this->db->select('
            sum(fpbd.jumlah * p.harga_satuan) as total_pemakaian
        ')
        ->from('faktur_pemakaian_barang_detail fpbd')
        ->join('produk p', 'p.id=fpbd.id_produk');
        return $this->db->get()->result();
    }

    // Tambah faktur
    public function tambah_faktur($user_id="")
    {
        $data_formula_list = [
                'no_faktur'     => $this->input->post('no_faktur'),
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
            $urut       = $urut+1;
            $jumlah     = $this->input->post('jumlah_'.$i);
            
            $data = [
                'id_faktur' => $id_faktur,
                'kode'      => 'T-PMK-'.$urut,
                'id_produk' => $this->input->post('id_produk_'.$i),
                'jumlah'    => $jumlah,
                'satuan'    => $this->input->post('satuan_dasar_'.$i)
            ];
            
            if($data['id_produk'] != 0){
                $this->db->insert('faktur_pemakaian_barang_detail', $data);
                
                // add transaksi gudang
                $dt_transaksi_gudang = [
                    'id_gudang'             => $id_faktur,
                    'id_faktur'             => $lastId,
                    'jenis_transaksi'       => 'keluar',
                    'deskripsi_transaksi'   => 'pemakaian',
                    'kode_faktur'           => $this->input->post('no_faktur'),
                    'kode_detail'           => $data['kode'],
                    'id_produk'             => $data['id_produk'],
                    'jumlah'                => $data['jumlah'],
                    'tgl_faktur'            => date_db_format($this->input->post('tanggal_faktur'))
                ];
                $this->Model_gudang->add_transaksi_gudang($dt_transaksi_gudang);
                
                // update stok di gudang
                $ambil_stok_gudang = $this->Model_gudang->get_produk_stok_gudang($id_gudang, $data['id_produk']);
                $dt_stok_dari_gudang = [
                    'id_gudang' => $id_gudang,
                    'id_produk' => $data['id_produk'],
                    'stok_out'  => (float)$ambil_stok_gudang[0]->stok_out + (float)$data['jumlah']
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
        $this->db->delete('faktur_pemakaian_barang_detail');
    }
    
     // Update total pembelian
    public function update_total_pembelian($id_faktur, $total_pembelian)
    {
        $data = [
            'total_pembelian' => $total_pembelian
        ];
        
        $this->db->where('id', $id_faktur);
        $this->db->update($this->tabel(), $data);
    }
    
    // filter ------------------------------------------------------------------------------------------------------------------------------
    var $table = 'faktur_mutasi';
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
            fpb.*,
            g.nama as nama_gudang,
            k.nama as nama_karyawan
        ')
        ->from('faktur_pemakaian_barang fpb')
        ->join('gudang g', 'g.id=fpb.id_gudang')
        ->join('karyawan k', 'k.id=fpb.id_karyawan');

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
