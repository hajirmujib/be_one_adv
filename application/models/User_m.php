<?php
defined("BASEPATH") OR exit("No direct script access allowed");

class User_m extends CI_Model 
{
    public function getUser($id = null)
    {
        if ($id === null) {
            $this->db->order_by("nama", "ASC");
            $result = $this->db->get("akun")->result();
        }else{
            $this->db->order_by("nama", "ASC");
            $this->db->where("id_akun", $id);
            $result = $this->db->get("akun")->result();
        }

        return $result;
    }

     public function cek($data)
    {
        $this->db->where($data);
        $result = $this->db->get("akun")->result();

        return $result;
    }


    public function addUser($data)
    {
        $this->db->insert('akun',$data);
        return $this->db->affected_rows();
    }

    public function deleteUser($id)
    {
        $this->_unlinkUser($id);

        $this->db->delete('akun', ['id_akun' => $id]);
        return $this->db->affected_rows();
    }

    public function updateUser($data, $id)
    {
        if (isset($data["foto"])) $this->_unlinkUser($id);

        $this->db->update('akun',$data,['id_akun' => $id]);
        return $this->db->affected_rows();
    }

    // has file
    private function _unlinkUser($id)
    {
        $image = $this->db->get_where("akun",["id_akun" => $id])->row();
        
        if ($image != null) {
            if ($image->foto != "") {
                $target_file = './fotoProfile/' . $image->foto;
                unlink($target_file);
            }
        }

    }

    public function cekPassword($data,$idUser)
    {
        
        $this->db->where("id_akun='$idUser' AND password='$data'");
        $result = $this->db->get("akun")->result();

        return $this->db->affected_rows();
    }


}