<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Coupon_service
{
    protected $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model(['Coupon_model', 'Order_model']);
    }

    public function get_all()
    {
        return $this->CI->Coupon_model->get();
    }

    public function get_by_id($id)
    {
        return $this->CI->Coupon_model->find($id);
    }

    public function get_by_code($code)
    {
        return $this->CI->Coupon_model->get_by_code($code);
    }

    public function create($data)
    {
        $coupon_id = $this->CI->Coupon_model->insert([
            'code' => $data['code'],
            'discount' => $data['discount'],
            'type' => $data['type'],
            'min_value' => $data['min_value'],
            'valid_until' => $data['valid_until'] . ' ' . $data['valid_until_time'],
            'is_active' => $data['is_active'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return $coupon_id;
    }

    public function update($coupon_id, $data)
    {
        $this->CI->Coupon_model->update($coupon_id, [
            'code' => $data['code'],
            'discount' => $data['discount'],
            'type' => $data['type'],
            'min_value' => $data['min_value'],
            'valid_until' => $data['valid_until'] . ' ' . $data['valid_until_time'],
            'is_active' => $data['is_active'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function delete($coupon_id)
    {
        $this->CI->Coupon_model->delete($coupon_id);
        return true;
    }
}
