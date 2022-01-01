<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Auth extends RestController 
{

    function __construct()
    {
        parent::__construct();
        $this->load->model("Auth_m", "auth");

    }

    public function login_post()
    {
        date_default_timezone_set('Asia/Jakarta');

        $data =[
            'email' => $this->post('email'),
            'password' => md5($this->post('password'))
        ];
        $data = $this->auth->login($data);

        if ($data) {
            $this->response([
                'status' => true,
                'data' => $data,
                'message' => 'login success.'
            ],self::HTTP_CREATED);
        }else {
            $this->response([
                'status' => false,
                'message' => 'failed to login'
            ],self::HTTP_BAD_REQUEST);
        }
    }

    public function register_post()
    {
        date_default_timezone_set('Asia/Jakarta');

        $data =[
            'nama' => $this->post('nama'),
            'nidn' => $this->post('nidn'),
            'email' => $this->post('email'),
            'password' => md5($this->post('password')),
            'level' => $this->post('level'),
            'no_telp' => $this->post('no_telp'),
                    ];

        // cek upload image file
        if (!empty($_FILES["foto"])) {
            $data["foto"] = $this->_uploadFile($_FILES["foto"]);
        } else {
            $data["foto"] = "";
        }

        if ($this->auth->register($data) > 0) {
            $this->response([
                'status' => true,
                'message' => 'new user has been added.'
            ],self::HTTP_CREATED);
        }else {
            $this->response([
                'status' => false,
                'message' => 'failed to add new user'
            ],self::HTTP_BAD_REQUEST);
        }
    }


    // upload image
    private function _uploadFile($file)
    {
        $config['upload_path'] = './fotoProfile/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
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