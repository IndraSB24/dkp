<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_transaksi_mutasi_divisi extends CI_Model
{
    function tabel(){
        $tabel = "faktur_mutasi_divisi";
        return $tabel;
    }
    
    // Ambil Data
    public function get_all()
    {
        $this->db->select('
            fmd.*,
            dgd.nama as dari_divisi,
            kgd.nama as ke_divisi,
            k.nama as nama_karyawan
        ')
        ->from('faktur_mutasi_divisi fmd')
        ->join('gudang_divisi dgd', 'dgd.id=fmd.id_dari_divisi')
        ->join('gudang_divisi kgd', 'kgd.id=fmd.id_ke_divisi')
        ->join('karyawan k', 'k.id=fmd.id_karyawan');
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
        return $this->db->select("*")->limit(1)->order_by('id',"DESC")->get('faktur_mutasi_detail')->row();
    }
    
    //ambil data faktur
    public function get_by_id($id_faktur)
    {
        $this->db->select('
            fmd.*,
            dgd.nama as dari_divisi,
            kgd.nama as ke_divisi,
            g.nama as gudang,
            k.nama as nama_karyawan
        ')
        ->from('faktur_mutasi_divisi fmd')
        ->join('gudang_divisi dgd', 'dgd.id=fmd.id_dari_divisi')
        ->join('gudang_divisi kgd', 'kgd.id=fmd.id_ke_divisi')
        ->join('gudang g', 'g.id=dgd.id_gudang')
        ->join('karyawan k', 'k.id=fmd.id_karyawan')
        ->where('fmd.id', $id_faktur);
        return $this->db->get()->result();
    }
    
    // total mutasi per faktur
    public function total_mutasi($id_faktur){
        $this->db->select_sum('')
            ->from('faktur_mutasi_detail fpd')
            ->where('id_faktur', $id_faktur);
        return $this->db->get()->result();
    }
    
    // AMbil detail data faktur
    public function get_detail_faktur($id_faktur)
    {
        $this->db->select('
            fmdd.*,
            p.nama as nama_produk,
            su.nama_satuan as satuan_dasar
        ')
        ->from('faktur_mutasi_divisi_detail fmdd')
        ->join('faktur_mutasi_divisi fmd', 'fmd.id=fmdd.id_faktur')
        ->join('produk p', 'p.id=fmdd.id_produk')
        ->join('satuan_ukuran su', 'su.id=p.id_satuan_dasar')
        ->where('fmdd.id_faktur', $id_faktur);
        return $this->db->get()->result();
    }
    
    
    // Ambil transaksi sesuai produk
    public function get_transaksi_produk($id_produk)
    {
        $this->db->select('
            fpd.*
        ')
        ->from('faktur_mutasi_detail fpd')
        ->where('fpd.id_produk', $id_produk);
        return $this->db->get()->result();
    }
    
    //ambil data piutang
    public function get_piutang()
    {
        $this->db->select('
            count(fm.id) as jumlah_faktur,
            sum(fm.nilai_mutasi) as total_piutang,
            sum(fm.dibayar) as dibayar,
            g.id as id_ke_gudang,
            g.nama as ke_gudang
        ')
        ->from('faktur_mutasi fm')
        ->join('gudang g', 'g.id=fm.ke_gudang')
        ->where('fm.status_piutang', 'PIUTANG')
        ->group_by('fm.ke_gudang');
        return $this->db->get()->result();
    }
    
    //ambil data detail piutang
    public function get_piutang_by_gudang($id_gudang)
    {
        $this->db->select('
            fm.*,
            g.id as id_ke_gudang,
            g.nama as ke_gudang,
            dg.nama as dari_gudang,
            k.nama as nama_karyawan
        ')
        ->from('faktur_mutasi fm')
        ->join('gudang g', 'g.id=fm.ke_gudang')
        ->join('gudang dg', 'dg.id=fm.dari_gudang')
        ->join('karyawan k', 'k.id=fm.id_karyawan')
        ->where('fm.status_piutang', 'PIUTANG')
        ->where('fm.ke_gudang', $id_gudang)
        ->order_by('fm.no_faktur');
        return $this->db->get()->result();
    }
    
    // total pembelian
    public function get_total_mutasi(){
        $this->db->select('
            count(fm.id) as jumlah_faktur,
            sum(fm.nilai_mutasi) as total_mutasi
        ')
        ->from('faktur_mutasi fm');
        return $this->db->get()->result();
    }
    
    //get total hutang
    public function get_total_piutang()
    {
        $this->db->select('
            sum(fm.nilai_mutasi) as total_piutang,
            sum(fm.dibayar) as dibayar
        ')
        ->from('faktur_mutasi fm')
        ->where('fm.status_piutang', 'PIUTANG');
        return $this->db->get()->result();
    }

    // Tambah
    public function tambah_faktur()
    {
        $data = [
                'no_faktur'     => $this->input->post('no_faktur'),
                'urut'          => $this->input->post('kode_urut'),
                'id_karyawan'   => $this->input->post('karyawan'),
                'id_dari_divisi'=> $this->input->post('dari_divisi'),
                'id_ke_divisi'  => $this->input->post('ke_divisi'),
                'tgl_faktur'    => date_db_format($this->input->post('tanggal_faktur')),
                'catatan_mutasi'=> $this->input->post('catatan'),
                'lampiran'      => $this->input->post('lampiran'),
                'created_by'    => activeId()
            ];
        $this->db->insert($this->tabel(), $data);
    }
    
    // tambah detail
    public function tambah_faktur_detail($id_faktur)
    {
        $itung      = $this->input->post('itung');

        for($i=1; $i<=$itung; $i++){
            $jumlah     = $this->input->post('jumlah_'.$i);
            
            $data = [
                'id_faktur'     => $id_faktur,
                'id_produk'     => $this->input->post('id_produk_'.$i),
                'jumlah'        => $jumlah
            ];
            
            if($data['id_produk'] != 0){
                $this->db->insert('faktur_mutasi_divisi_detail', $data);
            }
        }
    }
    
    // Hapus faktur
    public function hapus($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->tabel());
        
        $this->db->where('id_faktur', $id);
        $this->db->delete('faktur_mutasi_divisi_detail');
    }
    
    // Update 
    public function update_faktur($id_faktur, $data)
    {
        $this->db->where('id', $id_faktur);
        $this->db->update($this->tabel(), $data);
    }
    
    // Update total mutasi
    public function update_total_mutasi($id_faktur, $total_mutasi)
    {
        $data = [
            'total_mutasi' => $total_mutasi
        ];
        
        $this->db->where('id', $id_faktur);
        $this->db->update($this->tabel(), $data);
    }
    
    // Update total nilai mutasi
    public function update_nilai_mutasi($id_faktur, $nilai_mutasi)
    {
        $data = [
            'nilai_mutasi' => $nilai_mutasi
        ];
        
        $this->db->where('id', $id_faktur);
        $this->db->update($this->tabel(), $data);
    }
    
    // Update bayar piutang
    public function update_bayar_piutang($no_faktur, $jumlah, $tgl)
    {
        $data = [
            'dibayar' => $jumlah,
            'tgl_bayar' => date_db_format($tgl)
        ];
        
        $this->db->where('no_faktur', $no_faktur);
        $this->db->update($this->tabel(), $data);
    }
    
    public function update_status_piutang($no_faktur, $status)
    {
        $data = [
            'status_piutang' => $status
        ];
        
        $this->db->where('no_faktur', $no_faktur);
        $this->db->update($this->tabel(), $data);
    }
    
    public function update_penerimaan($id_faktur, $id_produk, $jml_diterima, $harga_mutasi)
    {
        $data = [
            'jumlah_diterima'   => $jml_diterima,
            'harga_mutasi'      => $harga_mutasi
        ];
        
        $this->db->where('id_faktur', $id_faktur);
        $this->db->where('id_produk', $id_produk);
        $this->db->update('faktur_mutasi_detail', $data);
    }
    
    // filter ------------------------------------------------------------------------------------------------------------------------------
    var $table = 'faktur_mutasi_divisi';
    var $column_order = array(null, 'no_faktur', 'tgl_faktur'); //set column field database for datatable orderable
    var $column_search = array('no_faktur'); //set column field database for datatable searchable 
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
        if($this->input->post('filter_dari_divisi') && $this->input->post('filter_dari_divisi')!="nothing"){
            $this->db->where('id_dari_divisi', $this->input->post('filter_dari_divisi'));
        }
        if($this->input->post('filter_ke_divisi') && $this->input->post('filter_ke_divisi')!="nothing"){
            $this->db->where('id_ke_divisi', $this->input->post('filter_ke_divisi'));
        }
        
        $this->db->select('
            fmd.*,
            dgd.nama as dari_divisi,
            kgd.nama as ke_divisi,
            g.nama as gudang,
            k.nama as nama_karyawan
        ')
        ->from('faktur_mutasi_divisi fmd')
        ->join('gudang_divisi dgd', 'dgd.id=fmd.id_dari_divisi')
        ->join('gudang_divisi kgd', 'kgd.id=fmd.id_ke_divisi')
        ->join('gudang g', 'g.id=dgd.id_gudang')
        ->join('karyawan k', 'k.id=fmd.id_karyawan');

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
