<?php

class Webhook extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(['Order_model', 'Stock_model']);
        $this->output->set_content_type('application/json');
    }

    public function order_status()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!isset($input['id']) || !isset($input['status'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Parametros id e status sao obrigatorios']);
            return;
        }

        if (!in_array($input['status'], ['pending', 'paid', 'completed', 'canceled'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Status invalido']);
            return;
        }

        $order_id = $input['id'];
        $new_status = $input['status'];

        $order = $this->Order_model->find($order_id);

        if (!$order) {
            http_response_code(404);
            echo json_encode(['error' => 'Pedido nao encontrado']);
            return;
        }

        if ($order['status'] === $new_status) {
            http_response_code(200);
            echo json_encode(['message' => 'Nenhuma alteracao necessaria']);
            return;
        }

        try {
            $this->db->trans_begin();

            if ($new_status === 'pending') {
                $this->Order_model->update($order_id, ['status' => 'pending']);
                echo json_encode(['message' => 'Status do pedido atualizado para pendente']);
            }

            if ($new_status === 'paid') {
                $this->Order_model->update($order_id, ['status' => 'paid']);
                echo json_encode(['message' => 'Status do pedido atualizado para pago']);
            }

            if ($new_status === 'completed') {
                $this->Order_model->update($order_id, ['status' => 'completed']);
                echo json_encode(['message' => 'Status do pedido atualizado para completo']);
            }

            if ($new_status === 'canceled') {
                $this->Order_model->delete($order_id);
                $this->increaseStock($order['products']);
                echo json_encode(['message' => 'Pedido cancelado com sucesso']);
            }

            $this->db->trans_commit();
            http_response_code(200);

            $this->notify_socket([
                'order_id' => $order_id,
                'new_status' => $new_status
            ]);
        } catch (Exception $e) {
            $this->db->trans_rollback();
            http_response_code(500);
            echo json_encode(['error' => 'Erro ao processar a solicitacao']);
            return;
        }
    }

    private function increaseStock($products)
    {
        if (empty($products)) {
            return;
        }

        foreach (json_decode($products) as $product) {
            $this->Stock_model->increase_stock($product->id, $product->quantity);
        }
    }

    private function notify_socket($data)
    {
        $url = 'http://socket:3000/notify-update';

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_exec($ch);
        curl_close($ch);
    }
}
