<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_transaksi_produksi extends CI_Model
{
    function tabel(){
        $tabel = "faktur_produksi";
        return $tabel;
    }
    
    // Ambil Data
    public function get_all()
    {
        //return $this->db->get($this->tabel())->result();
        $this->db->select('
            fp.*,
            g.nama as nama_gudang,
            k.nama as nama_karyawan
        ')
        ->from('faktur_produksi fp')
        ->join('gudang g', 'g.id=fp.id_gudang')
        ->join('karyawan k', 'k.id=fp.id_karyawan', 'left');
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
        return $this->db->select("*")->limit(1)->order_by('id',"DESC")->get('faktur_produksi_detail')->row();
    }
    
    //ambil data faktur
    public function get_by_id($id_faktur)
    {
        $this->db->select('
            fp.*,
            g.nama as nama_gudang,
            k.nama as nama_karyawan
        ')
        ->from('faktur_produksi fp')
        ->join('gudang g', 'g.id=fp.id_gudang')
        ->join('karyawan k', 'k.id=fp.id_karyawan')
        ->where('fp.id', $id_faktur);
        return $this->db->get()->result();
    }
    
    //ambil list id faktur
    public function get_id_faktur_list($lokasi){
        $this->db->select('
            fp.id as id_faktur
        ')
        ->from('faktur_produksi fp')
        ->join('gudang g', 'g.id=fp.id_gudang')
        ->where('g.nama', $lokasi);
        return $this->db->get()->result();
    }
    
    // total produksi per faktur
    public function total_produksi($id_faktur){
        $this->db->select_sum('')
            ->from('faktur_produksi_detail fpd')
            ->where('id_faktur', $id_faktur);
        return $this->db->get()->result();
    }
    
    // AMbil detail data faktur
    public function get_detail_faktur($id_faktur)
    {
        $this->db->select('
            fpd.*,
            fp.id_gudang as id_gudang,
            p.nama as nama_produk
        ')
        ->from('faktur_produksi_detail fpd')
        ->join('faktur_produksi fp', 'fp.id=fpd.id_faktur')
        ->join('produk p', 'p.id=fpd.id_produk')
        ->where('fpd.id_faktur', $id_faktur);
        return $this->db->get()->result();
    }
    
    // Ambil transaksi sesuai produk
    public function get_transaksi_produk($id_produk)
    {
        $this->db->select('
            fpd.*
        ')
        ->from('faktur_produksi_detail fpd')
        ->where('fpd.id_produk', $id_produk);
        return $this->db->get()->result();
    }
    
    //ambil total penjualan by id
    public function get_total_produksi_where($id_faktur)
    {
        $this->db->select('
            sum(fpd.jumlah * fpd.harga_satuan) as total_produksi
        ')
        ->from('faktur_produksi_detail fpd')
        ->group_by('fpd.id_faktur')
        ->where('fpd.id_faktur', $id_faktur);
        return $this->db->get()->result();
    }
    
    //ambil total penjualan
    public function get_total_produksi_all()
    {
        if(activeKota() && active_role()=="spv_outlet"){
            $this->db->where('g.id_kota', activeKota());
        }
        
        $this->db->select('
            sum(fpd.jumlah * fpd.harga_satuan) as total_produksi
        ')
        ->from('faktur_produksi_detail fpd')
        ->join('faktur_produksi fp', 'fp.id=fpd.id_faktur')
        ->join('gudang g', 'g.id=fp.id_gudang');
        return $this->db->get()->result();
    }
    
    //ambil total faktur
    public function get_total_faktur()
    {
        if(activeKota() && active_role()=="spv_outlet"){
            $this->db->where('g.id_kota', activeKota());
        }
        
        $this->db->select('
            count(fp.id) as total_faktur
        ')
        ->from('faktur_produksi fp')
        ->join('gudang g', 'g.id=fp.id_gudang');
        return $this->db->get()->result();
    }
    
    //ambil total faktur
    public function get_total_faktur_where($lokasi)
    {
        $this->db->select('
            count(fp.id) as total_faktur,
            g.nama as nama_outlet
        ')
        ->from('faktur_produksi fp')
        ->join('gudang g', 'g.id=fp.id_gudang')
        ->where('g.nama', $lokasi);
        return $this->db->get()->result();
    }

    // Tambah
    public function tambah_faktur()
    {
        $data_faktur = [
            'no_faktur'     => $this->input->post('no_faktur'),
            'urut'          => $this->input->post('kode_urut'),
            'tgl_faktur'    => date_db_format($this->input->post('tanggal_faktur')),
            'id_karyawan'   => $this->input->post('karyawan'),
            'id_gudang'     => $this->input->post('gudang'),
            'lampiran'      => $this->input->post('lampiran'),
            'created_by'    => activeId()
        ];
        $this->db->insert($this->tabel(), $data_faktur);
    }
    
    // tambah detail produksi
    public function tambah_faktur_detail($id_faktur, $id_detail)
    {
        $itung          = $this->input->post('itung');
        $id_gudang      = $this->input->post('gudang');
        $tgl_transaksi  = date_db_format($this->input->post('tanggal_faktur'));
        $urut           = (int)$id_detail;
        for($i=1; $i<=$itung; $i++){
            $urut       = $urut+1;
            $jumlah     = $this->input->post('jumlah_'.$i);
            
            $data = [
                'id_faktur'     => $id_faktur,
                'kode'          => 'T-PRD-'.$urut,
                'id_produk'     => $this->input->post('id_produk_'.$i),
                'jumlah'        => $jumlah,
                'satuan'        => $this->input->post('satuan_turunan_'.$i),
                'harga_satuan'  => $this->input->post('harga_satuan_'.$i)
            ];
            
            if($data['id_produk'] != 0){
                $this->db->insert('faktur_produksi_detail', $data);
                
                // add transaksi masuk produksi
                $dt_masuk_produksi = [
                    'id_gudang'             => $id_gudang,
                    'id_faktur'             => $data['id_faktur'],
                    'jenis_transaksi'       => 'masuk',
                    'deskripsi_transaksi'   => 'produksi',
                    'kode_faktur'           => $this->input->post('no_faktur'),
                    'kode_detail'           => $data['kode'],
                    'id_produk'             => $data['id_produk'],
                    'jumlah'                => $data['jumlah'],
                    'tgl_faktur'            => $tgl_transaksi
                ];
                $this->Model_gudang->add_transaksi_gudang($dt_masuk_produksi);
                
                //update stok produk
                $this->Model_Produk->update_stock_all($data['id_produk']);
                
                // add or update stok ke gudang
                $ambil_stok_gudang = $this->Model_gudang->get_produk_stok_gudang($id_gudang, $data['id_produk']);
                if($ambil_stok_gudang){
                    // update stok
                    $dt_up_stok = [
                        'id_gudang' => $id_gudang,
                        'id_produk' => $data['id_produk'],
                        'stok_in'   => (float)$ambil_stok_gudang[0]->stok_in + (float)$data['jumlah']
                    ];
                    $this->Model_gudang->update_stok_gudang('in', $dt_up_stok);
                }else{
                    // add stok
                    $dt_add_stok = [
                        'id_gudang' => $id_gudang,
                        'id_produk' => $data['id_produk'],
                        'stok_in'   => $data['jumlah']
                    ];
                    $this->Model_gudang->tambah_stok_gudang($dt_add_stok);
                }
                
                // ambil bahan yang digunakan
                $ambil_bahan = $this->Model_produk_formula->get_detail_formula_by_produk_no_array($data['id_produk']);
                if($ambil_bahan){
                    foreach($ambil_bahan as $ab){
                        $id_bahan   = $ab->id_bahan;
                        $total_bahan= (float)$data['jumlah'] * (float)$ab->jumlah_bahan;
                        // add transaksi keluar bahan
                        $dt_keluar_bahan = [
                            'id_gudang'             => $id_gudang,
                            'id_faktur'             => $data['id_faktur'],
                            'jenis_transaksi'       => 'keluar',
                            'deskripsi_transaksi'   => 'produksi',
                            'kode_faktur'           => $this->input->post('no_faktur'),
                            'kode_detail'           => $data['kode'],
                            'id_produk'             => $id_bahan,
                            'jumlah'                => $total_bahan,
                            'tgl_faktur'            => $tgl_transaksi
                        ];
                        $this->Model_gudang->add_transaksi_gudang($dt_keluar_bahan);
                        
                        // update stok out bahan di gudang
                        $ambil_stok_gudang = $this->Model_gudang->get_produk_stok_gudang($id_gudang, $id_bahan);
                        $dt_up_stok_bahan = [
                            'id_gudang' => $id_gudang,
                            'id_produk' => $id_bahan,
                            'stok_out'  => (float)$ambil_stok_gudang[0]->stok_out + (float)$total_bahan
                        ];
                        $this->Model_gudang->update_stok_gudang('out', $dt_up_stok_bahan);
                        
                        //update stok bahan
                        $this->Model_Produk->update_stock_all($id_bahan);
                    }
                }
            }
        }
    }
    
    // Hapus
    public function hapus($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->tabel());
        
        $this->db->where('id_faktur', $id);
        $this->db->delete('faktur_produksi_detail');
    }
    
     // Update total produksi
    public function update_total_produksi($id_faktur, $total_produksi)
    {
        $data = [
            'total_produksi' => $total_produksi
        ];
        
        $this->db->where('id', $id_faktur);
        $this->db->update($this->tabel(), $data);
    }
    
    // filter ------------------------------------------------------------------------------------------------------------------------------
    var $table = 'faktur_produksi';
    var $column_order = array(null, 'no_faktur', 'no_invoice', 'tgl_faktur', 'id_supplier'); //set column field database for datatable orderable
    var $column_search = array('tgl_faktur'); //set column field database for datatable searchable 
    var $order = array('id' => 'DESC'); // default order
 
    private function _get_datatables_query()
    {
        //add custom filter here
        if($this->input->post('filter_outlet') && $this->input->post('filter_outlet')!="nothing")
        {
            $this->db->where('id_gudang', $this->input->post('filter_outlet'));
        }
        if($this->input->post('filter_dari_tgl')){
            $this->db->where('tgl_faktur >=', $this->input->post('filter_dari_tgl'));
        }
        if($this->input->post('filter_ke_tgl')){
            $this->db->where('tgl_faktur <=', $this->input->post('filter_ke_tgl'));
        }
        if($this->input->post('filter_karyawan') && $this->input->post('filter_karyawan')!="nothing"){
            $this->db->where('id_karyawan', $this->input->post('filter_karyawan'));
        }
        
        if(activeKota() && active_role()=="spv_outlet"){
            $this->db->where('g.id_kota', activeKota());
        }
        
        $this->db->select('
            fp.*,
            g.nama as nama_gudang,
            k.nama as nama_karyawan
            
        ')
        ->from('faktur_produksi fp')
        ->join('gudang g', 'g.id=fp.id_gudang')
        ->join('karyawan k', 'k.id=fp.id_karyawan', 'left');

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
