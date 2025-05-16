<?php

class Coupon_model extends CI_Model
{
    protected $table = 'coupons';

    public function get()
    {
        return $this->db->get($this->table)->result_array();
    }

    public function find($id)
    {
        $this->db->where('id', $id);
        return $this->db->get($this->table)->row_array();
    }

    public function get_by_code($code)
    {
        $this->db->where('code', $code);
        return $this->db->get($this->table)->row_array();
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($coupon_id, $data)
    {
        $this->db->where('id', $coupon_id);
        return $this->db->update($this->table, $data);
    }

    public function delete($coupon_id)
    {
        $this->db->where('id', $coupon_id);
        return $this->db->delete($this->table);
    }
}