<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(['form', 'url']);
	}

	public function index()
	{
		$this->load->view('layouts/main', [
			'view' => 'pages/home/dashboard',
			'title' => 'Bem-vindo à minha aplicação',
		]);
	}
}
