<?php

class Order extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['form', 'url']);
        $this->load->library(['form_validation', 'session', 'Product_service', 'Order_service']);
        $this->load->model('Coupon_model');
    }

    public function my_requests()
    {
        $orders = $this->order_service->get_all();

        $this->load->view('layouts/main', [
            'view' => 'pages/order/my_requests',
            'title' => 'Meus Pedidos',
            'orders' => $orders,
        ]);
    }

    public function create()
    {
        $products = $this->product_service->get_all();
        $cart = $this->session->userdata('cart') ?? [];

        $this->load->view('layouts/main', [
            'view' => 'pages/order/create',
            'title' => 'Comprar',
            'success' => $this->input->get('success') ?? null,
            'products' => $products,
            'cart' => $cart,
        ]);
    }

    public function checkout()
    {
        $cart = $this->session->userdata('cart') ?? [];

        $this->session->set_flashdata('old_input', [
            'name' => $this->input->post('name'),
            'customer_email' => $this->input->post('customer_email'),
        ]);

        if (empty($cart)) {
            $this->session->set_flashdata('error', 'Seu carrinho está vazio.');
            return redirect('order/create');
        }

        $cep = $this->session->userdata('cep') ?? null;
        if (!$cep) {
            $this->session->set_flashdata('error', 'Você precisa informar o CEP para calcular o frete.');
            return redirect('order/create');
        }

        $customer_email = $this->input->post('customer_email');
        if (!$customer_email || !filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
            $this->session->set_flashdata('error', 'Email inválido.');
            return redirect('order/create');
        }

        $coupon = $this->session->userdata('applied_coupon') ?? null;
        $address_info = $this->session->userdata('cep_info') ?? null;
        $customer_name = $this->input->post('name');

        $this->db->trans_begin();

        $order_id = $this->order_service->create($cart, $cep, $coupon, $customer_name, $customer_email, $address_info);

        if (!$order_id) {
            $this->session->set_flashdata('error', 'Erro ao salvar pedido.');
            return redirect('order/create');
        }

        $this->db->trans_commit();

        $this->session->unset_userdata(['cart', 'applied_coupon', 'cep', 'cep_info']);
        $this->session->set_flashdata('success', 'Pedido realizado com sucesso!');

        redirect('order/create');
    }

    public function add_to_cart()
    {
        $product_id = $this->input->post('product_id');
        $quantity = (int)$this->input->post('quantity');

        if (!$product_id || $quantity <= 0) {
            $this->session->set_flashdata('error', 'Produto ou quantidade inválidos.');
            return redirect('order/create');
        }

        $product = $this->product_service->get_by_id($product_id);
        if (!$product) {
            $this->session->set_flashdata('error', 'Produto não encontrado.');
            return redirect('order/create');
        }

        $cart = $this->session->userdata('cart') ?? [];

        if (isset($cart[$product_id])) {
            $cart[$product_id]['quantity'] += $quantity;
        } else {
            $cart[$product_id] = [
                'id'       => $product['id'],
                'name'     => $product['name'],
                'price'    => $product['price'],
                'quantity' => $quantity,
            ];
        }

        $this->session->set_userdata('cart', $cart);
        $this->session->unset_userdata(['applied_coupon', 'cep']);

        redirect('order/create');
    }

    public function increase_quantity($product_id)
    {
        $cart = $this->session->userdata('cart') ?? [];

        if (isset($cart[$product_id])) {
            $cart[$product_id]['quantity'] += 1;
            $this->session->set_userdata('cart', $cart);
            $this->session->unset_userdata(['applied_coupon', 'cep']);
        }

        redirect('order/create');
    }

    public function decrease_quantity($product_id)
    {
        $cart = $this->session->userdata('cart') ?? [];

        if (isset($cart[$product_id])) {
            $cart[$product_id]['quantity'] -= 1;
            if ($cart[$product_id]['quantity'] <= 0) {
                unset($cart[$product_id]);
            }
            $this->session->set_userdata('cart', $cart);
            $this->session->unset_userdata(['applied_coupon', 'cep']);
        }

        redirect('order/create');
    }

    public function remove_item($product_id)
    {
        $cart = $this->session->userdata('cart') ?? [];

        if (isset($cart[$product_id])) {
            unset($cart[$product_id]);
            $this->session->set_userdata('cart', $cart);
            $this->session->unset_userdata(['applied_coupon', 'cep']);
        }

        redirect('order/create');
    }

    public function apply_coupon()
    {
        $code = trim($this->input->post('coupon_code'));
        $cart = $this->session->userdata('cart') ?? [];

        if (empty($code) || empty($cart)) {
            return redirect('order/create');
        }

        $total = array_reduce($cart, fn($carry, $item) => $carry + ($item['price'] * $item['quantity']), 0);
        $coupon = $this->Coupon_model->get_by_code($code);

        if (!$coupon || !$coupon['is_active'] || strtotime($coupon['valid_until']) < time() || $total < $coupon['min_value']) {
            $this->session->set_flashdata('error', 'Cupom inválido ou não aplicável.');
            return redirect('order/create');
        }

        $this->session->set_userdata('applied_coupon', $coupon);
        $this->session->set_flashdata('success', 'Cupom aplicado com sucesso!');
        redirect('order/create');
    }

    public function remove_coupon()
    {
        $this->session->unset_userdata('applied_coupon');
        $this->session->set_flashdata('success', 'Cupom removido com sucesso!');
        redirect('order/create');
    }

    public function apply_cep()
    {
        $cep = preg_replace('/[^0-9]/', '', $this->input->post('cep'));

        if (strlen($cep) !== 8) {
            $this->session->set_flashdata('error', 'CEP inválido.');
            return redirect('order/create');
        }

        $response = file_get_contents("https://viacep.com.br/ws/{$cep}/json/");
        $data = json_decode($response, true);

        if (isset($data['erro'])) {
            $this->session->set_flashdata('error', 'CEP não encontrado.');
            return redirect('order/create');
        }

        $this->session->set_userdata('cep', $cep);
        $this->session->set_userdata('cep_info', [
            'logradouro' => $data['logradouro'],
            'bairro'     => $data['bairro'],
            'localidade' => $data['localidade'],
            'uf'         => $data['uf']
        ]);

        redirect('order/create');
    }

    public function remove_cep()
    {
        $this->session->unset_userdata(['cep', 'cep_info']);
        redirect('order/create');
    }
}
