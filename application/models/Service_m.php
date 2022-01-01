<?php
defined("BASEPATH") OR exit("No direct script access allowed");

class Service_m extends CI_Model 
{
    public function getService($id = null)
    {
        if ($id === null) {
            $this->db->select("s.*,us.jenis");
            $this->db->join("jenis_pekerjaan us", "s.jenis=us.id_jenis","left");
            $this->db->order_by("s.tgl_masuk", "DESC");
            $result = $this->db->get("pekerjaan s")->result();
        }else{
            $this->db->select("s.*,us.jenis");
            $this->db->join("jenis_pekerjaan us", "s.jenis=us.jenis","left");
            $this->db->where("s.id_pekerjaan",$id);
            $result = $this->db->get("pekerjaan s")->result();
        }

        return $result;
    }


     public function getServiceStatus($status=null)
    {
       $this->db->select("s.*,us.jenis");
        $this->db->join("jenis_pekerjaan us", "s.jenis=us.id_jenis","left");
            
        $this->db->where(["s.status" => $status]);
        $this->db->order_by("s.tgl_masuk", "DESC");
        $result = $this->db->get("pekerjaan s")->result();

        return $result;
    }


    public function addService($data)
    {
        $this->db->insert('pekerjaan',$data);
        return $this->db->affected_rows();
    }

    public function deleteService($id)
    {
        $this->db->delete('pekerjaan', ['id_pekerjaan' => $id]);
        return $this->db->affected_rows();
    }

    public function updateService($data, $id)
    {
        $this->db->update('pekerjaan',$data,['id_pekerjaan' => $id]);
        return $this->db->affected_rows();
    }

}