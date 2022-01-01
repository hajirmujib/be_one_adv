<?php
defined("BASEPATH") OR exit("No direct script access allowed");

class Jenis_pekerjaan_m extends CI_Model 
{
    public function getJenis_pekerjaan($id = null)
    {
       

        if($id === null){
           
            $this->db->order_by("id_jenis", "ASC");
            $result = $this->db->get("jenis_pekerjaan")->result();
        }else{
          
             $this->db->order_by("id_jenis", "ASC");
            $this->db->where("id_jenis",$id);
             $result = $this->db->get("jenis_pekerjaan")->result();
        }

        return $result;
    }

      public function cek($data)
    {
        $this->db->where($data);
        $result = $this->db->get("jenis_pekerjaan")->result();

        return $result;
    }

    public function addJenis_pekerjaan($data)
    {

        $this->db->insert("jenis_pekerjaan", $data);
        return $this->db->affected_rows();
    }

    public function deleteJenis_pekerjaan($id)
    {
        
        $this->db->delete("jenis_pekerjaan", ['id_jenis' => $id]);
        return $this->db->affected_rows();
    }

    public function updatejenis_pekerjaan($data, $id)
    {
        
        $this->db->update("jenis_pekerjaan", $data,['id_jenis' => $id]);
        return $this->db->affected_rows();
    }

  


}