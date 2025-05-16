<?php

class Stock_model extends CI_Model
{
    protected $table = 'stocks';

    public function get_quantity_by_product($product_id)
    {
        $this->db->select('SUM(quantity) as quantity');
        $this->db->from($this->table);
        $this->db->where('product_id', $product_id);
        $this->db->group_by('product_id');
        $result = $this->db->get()->row_array();
        return $result ? $result['quantity'] : 0;
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function updateByProductId($product_id, $data)
    {
        $this->db->where('product_id', $product_id);
        return $this->db->update('stocks', $data);
    }

    public function deleteByProductId($product_id)
    {
        $this->db->where('product_id', $product_id)->delete('stocks');
    }

    public function decrease_stock($product_id, $quantity)
    {
        $this->db->set('quantity', 'quantity - ' . (int)$quantity, FALSE);
        $this->db->where('product_id', $product_id);
        return $this->db->update($this->table);
    }

    public function increase_stock($product_id, $quantity)
    {
        $this->db->set('quantity', 'quantity + ' . (int)$quantity, FALSE);
        $this->db->where('product_id', $product_id);
        return $this->db->update($this->table);
    }
}
