<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Product_service
{
    protected $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model(['Product_model', 'Stock_model']);
    }

    public function get_all()
    {
        $products = $this->CI->Product_model->get();

        foreach ($products as $key => $product) {
            $products[$key]['product_id'] = $products[$key]['product_id'] ?? $product['id'];
            $products[$key]['stock'] = $this->CI->Stock_model->get_quantity_by_product($product['id']);
        }

        usort($products, function ($a, $b) {
            if ($a['product_id'] == $b['product_id']) {
                return $a['id'] <=> $b['id'];
            };

            return $a['product_id'] <=> $b['product_id'];
        });

        return $products;
    }

    public function get_by_id($id)
    {
        $product = $this->CI->Product_model->find($id);
        $product['quantity'] = $this->CI->Stock_model->get_quantity_by_product($id);

        return $product;
    }

    public function get_with_variations($id)
    {
        $product = $this->CI->Product_model->find($id);
        $product['quantity'] = $this->CI->Stock_model->get_quantity_by_product($id);
        $product['variations'] = $this->CI->Product_model->get_variations_by_product($id);

        foreach ($product['variations'] as &$variation) {
            $variation['quantity'] = $this->CI->Stock_model->get_quantity_by_product($variation['id']);
        }

        return $product;
    }

    public function create($data)
    {
        $product_id = $this->CI->Product_model->insert([
            'name' => $data['name'],
            'price' => $data['price'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $this->saveStock($product_id, $data['quantity']);
        $this->saveVariations($product_id, $data['variations'] ?? []);

        return $product_id;
    }

    public function update($product_id, $data)
    {
        $this->CI->Product_model->update($product_id, [
            'name' => $data['name'],
            'price' => $data['price'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $this->updateStock($product_id, $data['quantity']);
        $this->saveVariations($product_id, $data['variations'] ?? []);
    }

    private function saveStock($product_id, $quantity)
    {
        $this->CI->Stock_model->insert([
            'product_id' => $product_id,
            'quantity' => $quantity,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    private function updateStock($product_id, $quantity)
    {
        $this->CI->Stock_model->updateByProductId($product_id, [
            'quantity' => $quantity,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    private function saveVariations($product_id, $variations)
    {
        if (!$variations) return;

        $existing_variations = $this->CI->Product_model->get_variations_by_product($product_id);
        $existing_ids = array_column($existing_variations, 'id');
        $posted_ids = [];

        foreach ($variations as $variation) {
            if (!empty($variation['name']) && !empty($variation['price']) && isset($variation['quantity']) && is_numeric($variation['quantity'])) {
                if (!empty($variation['id'])) {
                    $this->CI->Product_model->update($variation['id'], [
                        'name' => $variation['name'],
                        'price' => $variation['price'],
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);

                    $this->CI->Stock_model->updateByProductId($variation['id'], [
                        'quantity' => $variation['quantity'],
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);

                    $posted_ids[] = $variation['id'];
                } else {
                    $variation_id = $this->CI->Product_model->insert([
                        'product_id' => $product_id,
                        'name' => $variation['name'],
                        'price' => $variation['price'],
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);

                    $this->saveStock($variation_id, $variation['quantity']);
                }
            }
        }

        $ids_to_delete = array_diff($existing_ids, $posted_ids);

        foreach ($ids_to_delete as $id) {
            $this->CI->Product_model->delete($id);
            $this->CI->Stock_model->deleteByProductId($id);
        }
    }

    public function delete($product_id)
    {
        $this->CI->Product_model->delete($product_id);
        return true;
    }
}
