<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_transaksi_pembelian extends CI_Model
{
    function tabel(){
        $tabel = "faktur_pembelian";
        return $tabel;
    }
    
    // Ambil Data
    public function get_all()
    {
        $this->db->select('
            fp.*,
            s.nama as nama_supplier
        ')
        ->from('faktur_pembelian fp')
        ->join('suplier s', 's.id=fp.id_supplier')
        ->order_by('fp.id', 'DESC');
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
        return $this->db->select("*")->limit(1)->order_by('id',"DESC")->get('faktur_pembelian_detail')->row();
    }
    
    //ambil data faktur
    public function get_by_id($id_faktur)
    {
        $this->db->select('
            fp.*,
            s.nama as nama_supplier,
            g.nama as nama_gudang,
            k.nama as nama_karyawan
        ')
        ->from('faktur_pembelian fp')
        ->join('suplier s', 's.id=fp.id_supplier')
        ->join('gudang g', 'g.id=fp.id_gudang')
        ->join('karyawan k', 'k.id=fp.id_karyawan')
        ->where('fp.id', $id_faktur);
        return $this->db->get()->result();
    }
    
    // get by no faktur
    public function get_by_no_faktur($no_faktur)
    {
        $this->db->select('
            fp.*,
            s.nama as nama_supplier,
            g.nama as nama_gudang,
            k.nama as nama_karyawan
        ')
        ->from('faktur_pembelian fp')
        ->join('suplier s', 's.id=fp.id_supplier')
        ->join('gudang g', 'g.id=fp.id_gudang')
        ->join('karyawan k', 'k.id=fp.id_karyawan')
        ->where('fp.no_faktur', $no_faktur);
        return $this->db->get()->result();
    }
    
     //ambil data supplier
    public function get_by_supplier($id_supplier)
    {
        $this->db->select('
            fp.*,
            s.nama as nama_supplier,
            g.nama as nama_gudang,
            k.nama as nama_karyawan
        ')
        ->from('faktur_pembelian fp')
        ->join('suplier s', 's.id=fp.id_supplier')
        ->join('gudang g', 'g.id=fp.id_gudang')
        ->join('karyawan k', 'k.id=fp.id_karyawan')
        ->where('fp.id_supplier', $id_supplier);
        return $this->db->get()->result();
    }
    
    //ambil data hutang
    public function get_terhutang()
    {
        $this->db->select('
            count(fp.id) as jumlah_faktur,
            sum(fp.total_pembelian) as total_hutang,
            sum(fp.dibayar) as dibayar,
            s.id as id_supplier,
            s.nama as nama_supplier
        ')
        ->from('faktur_pembelian fp')
        ->join('suplier s', 's.id=fp.id_supplier')
        ->where('fp.status_hutang', 'TERHUTANG')
        ->group_by('fp.id_supplier');
        return $this->db->get()->result();
    }
    
    //ambil data detail hutang
    public function get_terhutang_by_supplier($id_supplier)
    {
        $this->db->select('
            fp.*,
            s.id as id_supplier,
            s.nama as nama_supplier,
            g.nama as nama_gudang,
            k.nama as nama_karyawan
        ')
        ->from('faktur_pembelian fp')
        ->join('suplier s', 's.id=fp.id_supplier')
        ->join('gudang g', 'g.id=fp.id_gudang')
        ->join('karyawan k', 'k.id=fp.id_karyawan')
        ->where('fp.status_hutang', 'TERHUTANG')
        ->where('fp.id_supplier', $id_supplier)
        ->order_by('fp.no_faktur');
        return $this->db->get()->result();
    }
    
    //get total hutang
    public function get_total_hutang()
    {
        $this->db->select('
            sum(fp.total_pembelian) as total_hutang,
            sum(fp.dibayar) as dibayar
        ')
        ->from('faktur_pembelian fp')
        ->where('fp.status_hutang', 'TERHUTANG');
        return $this->db->get()->result();
    }
    
    // total pembelian
    public function get_total_pembelian(){
        // role filter
        if( active_role()=="admin_entitas" ){
            $this->db->where('id_gudang = ', activeOutlet());
        }
        $this->db->select('
            count(fp.id) as jumlah_faktur,
            sum(fp.total_pembelian) as total_pembelian
        ')
        ->from('faktur_pembelian fp');
        return $this->db->get()->result();
    }
    
    // AMbil detail data faktur
    public function get_detail_faktur($id_faktur)
    {
        $this->db->select('
            fpd.kode as kode,
            fpd.jumlah as jumlah_beli,
            fpd.harga_beli as harga_beli,
            fpd.harga_satuan as harga_satuan,
            fpd.id_produk as id_produk,
            fp.id_gudang as id_gudang,
            p.nama as nama_produk,
            su.nama_satuan as satuan_dasar
        ')
        ->from('faktur_pembelian_detail fpd')
        ->join('faktur_pembelian fp', 'fp.id=fpd.id_faktur')
        ->join('produk p', 'p.id=fpd.id_produk')
        ->join('satuan_ukuran su', 'su.id=p.id_satuan_dasar')
        ->where('fpd.id_faktur', $id_faktur);
        return $this->db->get()->result_array();
    }
    
    // Ambil transaksi sesuai produk
    public function get_transaksi_produk($id_produk)
    {
        $this->db->select('
            fpd.*
        ')
        ->from('faktur_pembelian_detail fpd')
        ->where('fpd.id_produk', $id_produk);
        return $this->db->get()->result();
    }
    
    // Ambil detail transaksi sesuai produk dan faktur
    public function get_detail_transaksi_produk($id_faktur, $id_produk)
    {
        $this->db->select('
            fpd.*
        ')
        ->from('faktur_pembelian_detail fpd')
        ->where('fpd.id_faktur', $id_faktur)
        ->where('fpd.id_produk', $id_produk);
        return $this->db->get()->result();
    }

    // Tambah
    public function tambah_faktur($user_id="")
    {
        $status_hutang = "LUNAS";
        if($this->input->post('jenis_bayar') == "CREDIT"){
            $status_hutang = "TERHUTANG";
        }
        $data = [
                'no_faktur'     => $this->input->post('no_faktur'),
                'urut'          => $this->input->post('kode_urut'),
                'no_invoice'    => $this->input->post('no_invoice'),
                'id_supplier'   => $this->input->post('supplier'),
                'id_karyawan'   => $this->input->post('karyawan'),
                'id_gudang'     => $this->input->post('gudang') ?: activeOutlet(),
                'jenis_bayar'   => $this->input->post('jenis_bayar'),
                'tanggal_faktur'=> date_db_format($this->input->post('tanggal_faktur')),
                'tanggal_bayar' => date_db_format($this->input->post('tanggal_bayar')),
                'lampiran'      => $this->input->post('lampiran'),
                'status_hutang' => $status_hutang,
                'created_by'    => activeId()
            ];
        $this->db->insert($this->tabel(), $data);
    }
    
    // tambah detail formula
    public function tambah_faktur_detail($id_faktur, $id_detail)
    {
        $itung      = $this->input->post('itung');
        $id_gudang  = $this->input->post('gudang');
        active_role()=="admin_entitas" ? $id_gudang = activeOutlet() : "";
        
        $urut       = (int)$id_detail;
        for($i=1; $i<=$itung; $i++){
            $urut       = $urut+1;
            $jumlah     = $this->input->post('jumlah_'.$i);
            $harga_satuan = (float)$this->input->post('harga_satuan_'.$i);
            //$harga_beli = (float)$harga_satuan * (float)$jumlah;
            $harga_beli = $this->input->post('harga_beli_'.$i);
            
            $data = [
                'id_faktur'     => $id_faktur,
                'kode'          => 'T-PBL-'.$urut,
                'id_produk'     => $this->input->post('id_produk_'.$i),
                'jumlah'        => $jumlah,
                'harga_beli'    => $harga_beli,
                'harga_satuan'  => (float)$harga_beli / (float)$jumlah
            ];
            
            if($data['id_produk'] != 0){
                $this->db->insert('faktur_pembelian_detail', $data);
                
                // add transaksi gudang
                $dt_transaksi_gudang = [
                    'id_gudang'             => $id_gudang,
                    'id_faktur'             => $data['id_faktur'],
                    'jenis_transaksi'       => 'masuk',
                    'deskripsi_transaksi'   => 'pembelian',
                    'kode_faktur'           => $this->input->post('no_faktur'),
                    'kode_detail'           => $data['kode'],
                    'id_produk'             => $data['id_produk'],
                    'jumlah'                => $data['jumlah'],
                    'tgl_faktur'            => date_db_format($this->input->post('tanggal_faktur'))
                ];
                $this->Model_gudang->add_transaksi_gudang($dt_transaksi_gudang);
                
                // add or update stok gudang
                $ambil_stok_gudang = $this->Model_gudang->get_produk_stok_gudang($id_gudang, $data['id_produk']);
                if($ambil_stok_gudang){
                    // update stok
                    $dt_stok_ke_gudang = [
                        'id_gudang' => $id_gudang,
                        'id_produk' => $data['id_produk'],
                        'stok_in'   => (float)$ambil_stok_gudang[0]->stok_in + (float)$data['jumlah']
                    ];
                    $this->Model_gudang->update_stok_gudang('in', $dt_stok_ke_gudang);
                }else{
                    // add stok
                    $dt_stok_ke_gudang = [
                        'id_gudang' => $id_gudang,
                        'id_produk' => $data['id_produk'],
                        'stok_in'   => $data['jumlah']
                    ];
                    $this->Model_gudang->tambah_stok_gudang($dt_stok_ke_gudang);
                }
                
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
        $this->db->delete('faktur_pembelian_detail');
    }
    
    // Update
    public function update($id_faktur, $data)
    {
        $this->db->where('id', $id_faktur);
        $this->db->update($this->tabel(), $data);
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
    
    // Update bayar hutang
    public function update_bayar_hutang($no_faktur, $jumlah)
    {
        $data = [
            'dibayar' => $jumlah
        ];
        
        $this->db->where('no_faktur', $no_faktur);
        $this->db->update($this->tabel(), $data);
    }
    
    public function update_status_hutang($no_faktur, $status)
    {
        $data = [
            'status_hutang' => $status
        ];
        
        $this->db->where('no_faktur', $no_faktur);
        $this->db->update($this->tabel(), $data);
    }
    
    // filter ------------------------------------------------------------------------------------------------------------------------------
    var $table = 'faktur_pembelian';
    var $column_order = array(null, 'no_faktur', 'no_invoice', 'tgl_faktur', 'id_supplier'); //set column field database for datatable orderable
    var $column_search = array('tgl_faktur'); //set column field database for datatable searchable 
    var $order = array('id' => 'DESC'); // default order
 
    private function _get_datatables_query()
    {
        //add custom filter here
        if($this->input->post('filter_dari_tgl')){
            $this->db->where('tanggal_faktur >=', $this->input->post('filter_dari_tgl'));
        }
        if($this->input->post('filter_ke_tgl')){
            $this->db->where('tanggal_faktur <=', $this->input->post('filter_ke_tgl'));
        }
        if($this->input->post('filter_no_invoice') && $this->input->post('filter_no_invoice')!="nothing"){
            $this->db->like('no_invoice', $this->input->post('filter_no_invoice'));
        }
        if($this->input->post('filter_supplier') && $this->input->post('filter_supplier')!="nothing"){
            $this->db->like('id_supplier', $this->input->post('filter_supplier'));
        }
        
        // role filter
        if( active_role()=="admin_entitas" ){
            $this->db->where('id_gudang = ', activeOutlet());
        }
        
        $this->db->select('
            fp.*,
            s.nama as nama_supplier
        ')
        ->from('faktur_pembelian fp')
        ->join('suplier s', 's.id=fp.id_supplier', 'left');

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
