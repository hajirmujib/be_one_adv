<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class User extends RestController 
{

    function __construct()
    {
        parent::__construct();
        $this->load->model("User_m", "user");

    }

    public function index_get()
    {
        $id = $this->get('id_akun');
        

        if($id !== null)
        {
            $user = $this->user->getUser($id);
        }
        else
        {
            $user = $this->user->getUser();
        }

        if($user){
            $this->response([
                'status' => true,
                'data' => $user
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
            'nama' => $this->post('nama'),
            'email' => $this->post('email'),
            'password' => md5($this->post('password')),
            'level' => $this->post('level'),
        ];

        // cek upload image file
        if (!empty($_FILES["foto"])) {
            $data["foto"] = $this->_uploadFile($_FILES["foto"]);
        } else {
            $data["foto"] = "";
        }

        if ($this->user->addUser($data) > 0) {
            $this->response([
                'status' => true,
                'message' => 'new user has been added.'
            ],self::HTTP_CREATED);
        }else {
            $this->response([
                'status' => false,
                'message' => 'failed to add new data'
            ],self::HTTP_BAD_REQUEST);
        }
    }

       public function cek_get()
    {
       

        $data =[
            'email' => $this->get('email'),
            'level'=>'Customer'
            
        ];
        $data = $this->user->cek($data);

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

    public function edit_post()
    {
        $id = $this->post('id_akun');


            $data =[
            'nama' => $this->post('nama'),
            'email' => $this->post('email'),
            'password' => md5($this->post('password')),
            'level' => $this->post('level'),
             ];
        
        // cek upload image user
        if (!empty($_FILES["foto"])) {
            if($_FILES["foto"]["error"] == 0){
                $data["foto"] = $this->_uploadFile($_FILES["foto"]);    
            }
        }

        if ($this->user->updateUser($data, $id) > 0) {
            $this->response([
                'status' => true,
                'cek password 1: '=>$this->user->cekPassword($this->post('password'),$id),  
                'message' => 'new user has been modify'
            ],self::HTTP_CREATED);
        }else {
            $this->response([
                'status' => false,
                'cek password 0: '=>$this->user->cekPassword($this->post('password'),$id),
                'message' => 'failed to modify new data'
            ],self::HTTP_BAD_REQUEST);
        }
    }

    public function delete_get()
    {
        $id = $this->get('id_akun');

        if ($id === null) {
            $this->response([
                'status' => false,
                'message' => 'provide an id'
            ],self::HTTP_BAD_REQUEST);
        }else {
            if ($this->user->deleteUser($id) > 0) {
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
        $config['upload_path'] = './fotoProfile/';
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