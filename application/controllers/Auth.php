<?php

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['form', 'url']);
        $this->load->library(['form_validation', 'Auth_service']);
    }

    public function login()
    {
        $this->load->view('layouts/main', [
            'view' => 'pages/auth/login',
            'title' => 'Login'
        ]);
    }

    public function authenticate()
    {
        $this->setAutheticateValidationRules();

        $email = $this->input->post('email');
        $password = $this->input->post('password');

        if ($this->auth_service->authenticate($email, $password)) {
            redirect('product');
            return;
        }

        $this->session->set_flashdata('error', 'Credenciais Inválidas');

        $this->load->view('layouts/main', [
            'view' => 'pages/auth/login',
            'title' => 'Login'
        ]);
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth/login');
    }

    public function api()
    {
        $this->load->view('layouts/main', [
            'view' => 'pages/auth/tokens',
            'tokens' => $this->auth_service->get_all_tokens()
        ]);
    }

    public function create_token()
    {
        $this->load->view('layouts/main', [
            'view' => 'pages/auth/create_token'
        ]);
    }

    public function register_token()
    {
        $this->setAuthenticateRegisterToken();

        $this->auth_service->register_token($this->input->post('title'));

        return redirect('auth/api');
    }

    public function token_delete($id)
    {
        $this->auth_service->delete_token($id);

        return redirect('auth/api');
    }

    private function setAutheticateValidationRules()
    {
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Senha', 'required');
    }

    private function setAuthenticateRegisterToken()
    {
        $this->form_validation->set_rules('title', 'Título', 'required');
    }
}
