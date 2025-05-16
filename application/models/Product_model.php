<?php

class Product_model extends CI_Model
{
    protected $table = 'products';

    public function get()
    {
        return $this->db->get($this->table)->result_array();
    }

    public function find($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row_array();
    }

    public function get_variations_by_product($product_id)
    {
        return $this->db->get_where('products', ['product_id' => $product_id])->result_array();
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('products', $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id)->delete('products');
    }
}
