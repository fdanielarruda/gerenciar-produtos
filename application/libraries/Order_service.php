<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Order_service
{
    protected $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model(['Order_model', 'Stock_model']);
    }

    public function get_all()
    {
        $orders = $this->CI->Order_model->get_all();

        return array_map(function ($order) {
            if (!empty($order['products'])) {
                $order['products'] = json_decode($order['products'], true);
            }
            return $order;
        }, $orders);
    }

    public function get_by_id($id)
    {
        $order = $this->CI->Order_model->find($id);

        if ($order && !empty($order['products'])) {
            $order['products'] = json_decode($order['products'], true);
        }

        return $order;
    }

    public function create($cart, $cep, $coupon, $client, $email, $address_info)
    {
        $subtotal = array_reduce($cart, fn($carry, $item) => $carry + ($item['price'] * $item['quantity']), 0);
        $discount = $coupon['discount'] ?? 0;

        [$total, $shipping] = $this->getTotalAfterShipping($subtotal, $discount);

        $address = '';
        if ($address_info) {
            $address = "{$address_info['logradouro']}, {$address_info['bairro']}, {$address_info['localidade']}/{$address_info['uf']}";
        }

        $data = [
            'coupon_code'    => $coupon['code'] ?? null,
            'coupon_value'   => $discount,
            'customer_name'  => $client,
            'customer_email' => $email,
            'zipcode'        => $cep,
            'address'        => $address,
            'subtotal'       => $subtotal,
            'shipping'       => $shipping,
            'total'          => $total,
            'products'       => json_encode($cart),
            'created_at'     => date('Y-m-d H:i:s'),
            'updated_at'     => date('Y-m-d H:i:s'),
        ];

        $this->decreaseStock($cart);

        return $this->CI->Order_model->create($data);
    }

    public function update($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');

        return $this->CI->Order_model->update($id, $data);
    }

    public function delete($id)
    {
        return $this->CI->Order_model->delete($id);
    }

    private function getTotalAfterShipping($subtotal, $discount)
    {
        $total_after_discount = max($subtotal - $discount, 0);

        $shipping = 0;
        if ($total_after_discount > 200) {
            $shipping = 0;
        } elseif ($total_after_discount >= 52 && $total_after_discount <= 166.59) {
            $shipping = 15;
        } else {
            $shipping = 20;
        }

        return [
            $total_after_discount + $shipping,
            $shipping
        ];
    }

    private function decreaseStock($cart)
    {
        foreach ($cart as $item) {
            $product_id = $item['id'];
            $quantity = $item['quantity'];
            $this->CI->Stock_model->decrease_stock($product_id, $quantity);
        }
    }
}
