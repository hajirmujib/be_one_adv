<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Service extends RestController 
{

    function __construct()
    {
        parent::__construct();
        $this->load->model("Service_m", "service");
    }

    public function index_get()
    {
        
        $id_pekerjaan=$this->get('id_pekerjaan');
        $status=$this->get('status');

        if($id_pekerjaan !== null)
        {
            $service = $this->service->getService($id_pekerjaan);
        }else if($status!==null){
            $service=$this->service->getServiceStatus($status);
        }else{
            $service=$this->service->getService();
        }
      
        if($service){
            $this->response([
                'status' => true,
                'data' => $service
            ],self::HTTP_OK);
        }else {
            $this->response([
                'status' => false,
                'message' => 'not found'
            ],self::HTTP_NOT_FOUND);
        }
    }

   
    public function index_post()
    {
        date_default_timezone_set('Asia/Jakarta');

        $data =[
            'tgl_masuk' => (new DateTime('now'))->format('Y-m-d'),
            'nama' => $this->post('nama'),
            'no_telp' => $this->post('no_telp'),
            'jenis'=>$this->post('jenis'),
            'keterangan'=>$this->post('keterangan'),
            'harga' => $this->post('harga'),
            'status'=> $this->post('status')
        ];

        if ($this->service->addService($data) > 0) {
            $this->response([
                'status' => true,
                'message' => 'new Service has been added.'
            ],self::HTTP_CREATED);
        }else {
            $this->response([
                'status' => false,
                'message' => 'failed to add new data'
            ],self::HTTP_BAD_REQUEST);
        }
    }

    public function edit_post()
    {
        $id = $this->post('id_pekerjaan');

            $data =[
            'id_pekerjaan'=>$this->post('id_pekerjaan'),
             'tgl_masuk' => (new DateTime('now'))->format('Y-m-d'),
            'nama' => $this->post('nama'),
            'no_telp' => $this->post('no_telp'),
            'jenis'=>$this->post('jenis'),
            'keterangan'=>$this->post('keterangan'),
            'harga' => $this->post('harga'),
            'status'=> $this->post('status')
         ];
        

        if ($this->service->updateService($data, $id) > 0) {
            $this->response([
                'status' => true,
                'message' => 'new Service has been modify'
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
        $id = $this->get('id_pekerjaan');

        if ($id === null) {
            $this->response([
                'status' => false,
                'message' => 'provide an id'
            ],self::HTTP_BAD_REQUEST);
        }else {
            if ($this->service->deleteService($id) > 0) {
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


       // upload image
    private function _uploadFile($file)
    {
        $config['upload_path'] = './kelengkapan/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg|doc|docx|pdf';
        $config['max_size']     = 15360;
        // $config['file_name'] = $this->post('id') . '-' . date('dmYHis') . '-' . basename($_FILES['abstrak']['name']);
        $this->load->library('upload', $config);

        $_FILES['file']['name'] = date('dmYHis')."_".str_replace("", "", basename($file['name']));
        $_FILES['file']['type'] = $file['type'];
        $_FILES['file']['tmp_name'] = $file['tmp_name'];
        $_FILES['file']['error'] = $file['error'];
        $_FILES['file']['size'] = $file['size'];

        if($this->upload->do_upload('file')){
            $file_name = $this->upload->data('file_name');
        }else{
            $file_name = "";
        }

        return $file_name;

    }
}