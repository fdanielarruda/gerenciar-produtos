<?php

class Product extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['form', 'url']);
        $this->load->library(['form_validation', 'Product_service']);

        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    public function list()
    {
        $products = $this->product_service->get_all();

        $this->load->view('layouts/main', [
            'view' => 'pages/product/list',
            'title' => 'Gerenciar produtos',
            'products' => $products,
            'success' => $this->input->get('success') ?? null,
        ]);
    }

    public function create()
    {
        $product_saved = null;

        if ($this->input->get('id')) {
            $product_saved = $this->product_service->get_with_variations($this->input->get('id')) ?? null;
        }

        $this->load->view('layouts/main', [
            'view' => 'pages/product/create',
            'title' => 'Gerenciar produtos',
            'success' => $this->input->get('success') ?? null,
            'product_saved' => $product_saved,
        ]);
    }

    public function store()
    {
        $this->setProductValidationRules();
        $this->setVariationValidationRules();

        if ($this->form_validation->run() === FALSE) {
            return $this->load->view('pages/product/create');
        }

        $product_id = $this->input->post('product_id');
        $is_update = !empty($product_id);

        $this->db->trans_begin();

        if ($is_update) {
            $this->product_service->update($product_id, $this->input->post());
            $success = 2;
        } else {
            $product_id = $this->product_service->create($this->input->post());
            $success = 1;
        }

        $this->db->trans_commit();

        redirect("product/create?success=$success&id=$product_id");
    }

    public function delete($id)
    {
        $deleted = $this->product_service->delete($id);

        if ($deleted) {
            redirect('product/list?success=3');
        } else {
            redirect('product/list?error=1');
        }
    }

    private function setProductValidationRules()
    {
        $this->form_validation->set_rules('name', 'nome do produto', 'required');
        $this->form_validation->set_rules('price', 'preço', 'required|regex_match[/^\d{1,8}(\.\d{1,2})?$/]');
        $this->form_validation->set_rules('quantity', 'estoque', 'required|numeric|greater_than_equal_to[0]');
    }

    private function setVariationValidationRules()
    {
        $variations = $this->input->post('variations');
        if (!$variations) return;

        foreach ($variations as $index => $variation) {
            $this->form_validation->set_rules("variations[$index][name]", "nome da variação #" . ($index + 1), 'required');
            $this->form_validation->set_rules("variations[$index][price]", "preço da variação #" . ($index + 1), 'required|regex_match[/^\d{1,8}(\.\d{1,2})?$/]');
            $this->form_validation->set_rules("variations[$index][quantity]", "estoque da variação #" . ($index + 1), 'required|numeric|greater_than_equal_to[0]');
        }
    }
}
