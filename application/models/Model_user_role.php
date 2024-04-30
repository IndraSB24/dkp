<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_user_role extends CI_Model
{
    public function tabel(){
        $tabel = "user_role";
        return $tabel;
    }
    
    #Ambil data
    public function get_all()
    {
        return $this->db->get($this->tabel())->result_array();
    }
    
    //ambil data by id_user
    public function get_by_id($id_user)
    {
        $this->db->select('
            u.*
        ')
        ->from('user u')
        ->where('u.id_username', $id_user);
        return $this->db->get()->result();
    }
    
    #tambah data
    public function tambah()
    {
        $data = [
            "nama"      => htmlspecialchars($this->input->post('nama')),
            "username"  => htmlspecialchars($this->input->post('username')),
            "hp"        => htmlspecialchars($this->input->post('nohp')),
            "login_type"=> htmlspecialchars($this->input->post('role')),
            "password"  => htmlspecialchars(password_hash($this->input->post('password'), PASSWORD_DEFAULT))
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
