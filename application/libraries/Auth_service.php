<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth_service
{
    protected $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->library('session');
        $this->CI->load->model(['User_model', 'Token_model']);
    }

    public function authenticate($email, $password)
    {
        $user = $this->CI->User_model->get_by_email($email);

        if ($user && password_verify($password, $user->password)) {
            $this->CI->session->set_userdata([
                'user_id' => $user->id,
                'user_name' => $user->name,
                'logged_in' => true
            ]);

            return true;
        }

        return false;
    }

    public function get_all_tokens()
    {
        return $this->CI->Token_model->get_all();
    }

    public function register_token($title)
    {
        $this->CI->Token_model->create([
            'title' => $title,
            'token' => bin2hex(random_bytes(32)),
            'expires_at' => date('Y-m-d H:i:s', strtotime('+1 day'))
        ]);
    }

    public function delete_token($id)
    {
        $this->CI->Token_model->delete($id);
    }
}