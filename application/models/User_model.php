<?php

class User_model extends CI_Model
{
    protected $table = 'users';

    public function get_by_email($email)
    {
        return $this->db->get_where($this->table, ['email' => $email])->row();
    }
}
