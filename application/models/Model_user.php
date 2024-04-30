<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_user extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Model_gudang');
    }
    
    public function tabel(){
        $tabel = "user";
        return $tabel;
    }
    
    #Ambil data
    public function get_all()
    {
        $this->db->select('
            u.*,
            ur.deskripsi as nama_role,
            g.nama as entitas,
            lk.nama as kota
        ')
        ->from('user u')
        ->join('user_role ur', 'ur.id = u.id_role', 'left')
        ->join('gudang g', 'g.id = u.id_gudang', 'left')
        ->join('list_kota lk', 'lk.id = u.id_kota', 'left')
        ->where('u.id_username != 25');
        return $this->db->get()->result();
    }
    
    //ambil data by id_user
    public function get_by_id($id_user)
    {
        $this->db->select('
            u.*,
            ur.deskripsi as nama_role,
            g.nama as entitas,
            lk.nama as kota
        ')
        ->from('user u')
        ->join('user_role ur', 'ur.id = u.id_role', 'left')
        ->join('gudang g', 'g.id = u.id_gudang', 'left')
        ->join('list_kota lk', 'lk.id = u.id_kota', 'left')
        ->where('u.id_username', $id_user);
        return $this->db->get()->result();
    }
    
    #tambah data
    public function tambah()
    {
        $data_gudang = $this->Model_gudang->get_by_id($this->input->post('gudang'));
        $id_kota = !empty($data_gudang) ? $data_gudang[0]->id_kota : null;
        
        $data = [
            "nama"      => htmlspecialchars($this->input->post('nama')),
            "username"  => htmlspecialchars($this->input->post('username')),
            "hp"        => htmlspecialchars($this->input->post('nohp')),
            "password"  => htmlspecialchars(password_hash($this->input->post('password'), PASSWORD_DEFAULT)),
            "id_role"   => $this->input->post('role'),
            "id_gudang" => $this->input->post('gudang'),
            "id_kota"   => $id_kota
        ];
        $this->db->insert($this->tabel(), $data);
    }
    
    // Hapus
    public function hapus($id)
    {
        $this->db->where('id_username', $id);
        $this->db->delete($this->tabel());
    }
    
    // Hapus
    public function update($id, $data)
    {
        $this->db->where('id_username', $id);
        $this->db->update($this->tabel(), $data);
    }
}
