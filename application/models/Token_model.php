<?php

class Token_model extends CI_Model
{
    protected $table = 'tokens';

    public function get_all()
    {
        return $this->db->get($this->table)->result_array();
    }

    public function get_by_token($token)
    {
        return $this->db->get_where($this->table, ['token' => $token])->row();
    }

    public function create($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function delete($id)
    {
        $this->db->where('id', $id)->delete($this->table);
    }
}
