<?php

class Order_model extends CI_Model
{
    protected $table = 'orders';

    public function get_all()
    {
        return $this->db->get($this->table)->result_array();
    }

    public function find($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row_array();
    }
    
    public function create($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }
}