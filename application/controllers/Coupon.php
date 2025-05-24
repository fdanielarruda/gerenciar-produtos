<?php

class Coupon extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['form', 'url']);
        $this->load->library(['form_validation', 'Coupon_service']);

        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    public function list()
    {
        $this->load->view('layouts/main', [
            'view' => 'pages/coupon/list',
            'coupons' => $this->coupon_service->get_all()
        ]);
    }

    public function create()
    {
        $this->load->view('layouts/main', [
            'view' => 'pages/coupon/create',
            'success' => $this->input->get('success') ?? null
        ]);
    }

    public function store()
    {
        $this->setCouponValidationRules();

        if ($this->form_validation->run() === FALSE) {
            return $this->load->view('layouts/main', [
                'view' => 'pages/coupon/create',
            ]);
        }

        $this->db->trans_begin();

        $coupon_id = $this->coupon_service->create($this->input->post());

        $this->db->trans_commit();

        redirect("coupon/edit/$coupon_id?success=1");
    }

    public function edit($id)
    {
        $this->load->view('layouts/main', [
            'view' => 'pages/coupon/create',
            'success' => $this->input->get('success') ?? null,
            'coupon_saved' => $this->coupon_service->get_by_id($id) ?? null
        ]);
    }

    public function update($id)
    {
        $this->setCouponValidationRules($id);

        if ($this->form_validation->run() === FALSE) {
            return $this->load->view('layouts/main', [
                'view' => 'pages/coupon/create',
                'form_data' => $this->input->post(),
                'coupon_saved' => $this->coupon_service->get_by_id($id) ?? null,
            ]);
        }

        $this->db->trans_begin();

        $this->coupon_service->update($id, $this->input->post());

        $this->db->trans_commit();

        redirect("coupon/edit/$id?success=2");
    }

    public function delete($id)
    {
        $deleted = $this->coupon_service->delete($id);

        if ($deleted) {
            redirect('coupon/list?success=3');
        } else {
            redirect('coupon/list?error=1');
        }
    }

    private function setCouponValidationRules($id = null)
    {
        $code = $this->input->post('code');
        $coupon = $this->coupon_service->get_by_code($code);

        $rules = 'required';

        if (!$id || ($coupon && $coupon['id'] != $id)) {
            $rules .= '|is_unique[coupons.code]';
        }

        $this->form_validation->set_rules('code', 'código', $rules);
        $this->form_validation->set_rules('discount', 'desconto', 'required|numeric');
        $this->form_validation->set_rules('type', 'tipo', 'required');
        $this->form_validation->set_rules('min_value', 'valor mínimo', 'required|numeric');
        $this->form_validation->set_rules('valid_until', 'válido até', 'required|date');
        $this->form_validation->set_rules('is_active', 'ativo', 'required|in_list[0,1]');
    }
}
