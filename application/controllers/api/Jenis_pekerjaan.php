<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Jenis_pekerjaan extends RestController 
{   

    function __construct()
    {
        parent::__construct();
        $this->load->model("Jenis_pekerjaan_m", "jenis_pekerjaan");
    }

    public function index_get()
    {
        $id = $this->get('id_jenis');

        if($id !== null)
        {
            $jenis_pekerjaan = $this->jenis_pekerjaan->getJenis_pekerjaan($id);
        }
        else
        {
            $jenis_pekerjaan = $this->jenis_pekerjaan->getJenis_pekerjaan();
        }

        if($jenis_pekerjaan){
            $this->response([
                'status' => true,
                'data' => $jenis_pekerjaan
            ],self::HTTP_OK);
        }else {
            $this->response([
                'status' => false,
                'message' => 'not found'
            ],self::HTTP_NOT_FOUND);
        }
    }

        public function cek_post()
    {
       

        $data =[
            'id_jenis' => $this->post('id_jenis'),
            
        ];
        $data = $this->jenis_pekerjaan->cek($data);

        if ($data) {
            $this->response([
                'status' => true,
                'data' => $data,
                'message' => 'data found'
            ],self::HTTP_CREATED);
        }else {
            $this->response([
                'status' => false,
                'message' => 'data not found'
            ],self::HTTP_BAD_REQUEST);
        }
    }

    public function index_post()
    {
        date_default_timezone_set('Asia/Jakarta');
       
        $data =[
            
            'jenis' => $this->post('jenis'),
        ];


        if ($this->jenis_pekerjaan->addJenis_pekerjaan($data) > 0) {
            $this->response([
                'status' => true,
                'message' => 'new jenis_pekerjaan has been added.'
            ],self::HTTP_CREATED);
        }else {
            $this->response([
                'status' => false,
                'message' => $id,
            ],self::HTTP_BAD_REQUEST);
        }

       
    }


    public function edit_post()
    {
        $id = $this->post('id_jenis');
        $data =[
            'jenis' => $this->post('jenis'),
            
        ];


        if ($this->jenis_pekerjaan->updateJenis_pekerjaan($data, $id) > 0) {
            date_default_timezone_set('Asia/Jakarta');
            $this->response([
                'status' => true,
                'message' => 'new jenis_pekerjaan has been modify'
            ],self::HTTP_CREATED);
        }else {
            $this->response([
                'status' => false,
                'message' => 'failed to modify new data'
            ],self::HTTP_BAD_REQUEST);
        }
    }

    public function delete_get()
    {
        $id = $this->get('id_jenis');

        if ($id === null) {
            $this->response([
                'status' => false,
                'message' => 'provide an id'
            ],self::HTTP_BAD_REQUEST);
        }else {
            if ($this->jenis_pekerjaan->deleteJenis_pekerjaan($id) > 0) {
                $this->response([
                    'status' => true,
                    'id' => $id,
                    'message' => 'deleted.'
                ],self::HTTP_CREATED);
            }else {
                $this->response([
                    'status' => false,
                    'message' => 'id not found!'
                ],self::HTTP_BAD_REQUEST);
            }
        }
    }


}