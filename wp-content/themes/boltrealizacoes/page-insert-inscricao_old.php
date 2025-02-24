<?php 
$user_id = get_current_user_id();

$config = get_asaas_config();

if (isset($_POST['new_post']) == '1') {

    extract($_POST);

    $cpf_limpo = preg_replace('/[^\d]/', '', $post_cpf);
    $telefone_limpo = preg_replace('/[^\d]/', '', $post_telefone);
    $id_prova = $post_evento;
    $titulo_prova = get_the_title($id_prova);
    
    // Configurar dados para a API de criação de cliente
    $api_url_customer = $config['url'];
    $access_token = $config['token'];
    $data_customer = array(
        'name' => $post_title,
        'cpfCnpj' => $cpf_limpo,
        'email' => $post_email,
        'mobilePhone' => $telefone_limpo,
        'address' => $post_rua,
        'addressNumber' => $post_numero,
        'complement' => $post_complemento,
        'province' => $post_bairro,
        'postalCode' => $post_cep,
        'observations' => "Inscrição: $post_modalidade - $post_valor_final - Prova: $titulo_prova",
        'notificationDisabled' => false,
        'groupName' => null,
        'company' => null,
        'foreignCustomer' => false,
    );

    // Inicializar curl para criar cliente
    $ch = curl_init($api_url_customer);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'accept: application/json',
        'content-type: application/json',
        'access_token: ' . $access_token,
        'User-Agent: MeuAplicativo/1.0',
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_customer));

    $response_customer = curl_exec($ch);

    if (curl_errno($ch)) {
        die('Erro na API: ' . curl_error($ch));
    }

    curl_close($ch);

    // Decodificar resposta da API de criação do cliente
    $api_result_customer = json_decode($response_customer, true);

    if (isset($api_result_customer['id'])) {
        // Obter ID do cliente
        $customer_id = $api_result_customer['id'];

        // Configurar dados para a API de criação de fatura
        $api_url_payment = 'https://sandbox.asaas.com/api/v3/payments/';
        $data_payment = array(
            'billingType' => 'CREDIT_CARD',
            'customer' => $customer_id,
            'value' => $post_valor,
            'dueDate' => date('Y-m-d', strtotime('+1 day')),
            'description' => sprintf("Inscrição: %s - %s - Prova: %s", $post_modalidade, $post_valor_final, $titulo_prova),
            // 'split' => array(
            //     array(
            //         'walletId' => '849e0ed4-4c00-4366-afc4-e29552bf79ad', 
            //         'percentualValue' => 1 
            //     )
            // )
        );

        // Inicializar curl para criar pagamento
        $ch = curl_init($api_url_payment);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'accept: application/json',
            'content-type: application/json',
            'access_token: ' . $access_token,
            'User-Agent: MeuAplicativo/1.0',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_payment));

        $response_payment = curl_exec($ch);

        if (curl_errno($ch)) {
            die('Erro na API de pagamento: ' . curl_error($ch));
        }

        curl_close($ch);

        // Decodificar resposta da API de criação do pagamento
        $api_result_payment = json_decode($response_payment, true);

        if (isset($api_result_payment['id'])) {
            // Obter ID do pagamento
            $payment_id = $api_result_payment['id'];

            // Criar novo post no WordPress
            $new_post = array(
                'ID' => '',
                'post_type'   => 'inscricao',
                'post_author' => $user_id,
                'post_title'  => $post_title,
                'post_status' => 'publish',
            );

            $post_id = wp_insert_post($new_post);

            // Adicionar campos personalizados ao post
            
            add_post_meta($post_id, 'id_evento', $post_evento);
            add_post_meta($post_id, 'modalidade', $post_modalidade);
            add_post_meta($post_id, 'e-mail', $post_email);
            add_post_meta($post_id, 'telefone', $post_telefone);
            add_post_meta($post_id, 'cpf', $post_cpf);
            add_post_meta($post_id, 'genero', $post_genero);
            add_post_meta($post_id, 'nascimento', $post_nascimento);
            add_post_meta($post_id, 'cep', $post_cep);
            
            add_post_meta($post_id, 'rua', $post_rua);
            add_post_meta($post_id, 'numero', $post_numero);
            add_post_meta($post_id, 'complemento', $post_complemento);
            add_post_meta($post_id, 'bairro', $post_bairro);
            add_post_meta($post_id, 'cidade', $post_cidade);
            add_post_meta($post_id, 'estado', $post_estado);
            add_post_meta($post_id, 'assessoria', $post_assessoria);
            add_post_meta($post_id, 'camiseta', $post_camiseta);
            
            add_post_meta($post_id, 'aceite', $post_aceite);
            add_post_meta($post_id, 'id_consumidor', $customer_id);
            add_post_meta($post_id, 'forma_pagamento', 'CREDIT_CARD');
            add_post_meta($post_id, 'id_pagamento', $payment_id);
            
            add_post_meta($post_id, 'lote', $post_lote);
            
            add_post_meta($post_id, 'valor', $post_valor);
            add_post_meta($post_id, 'valor_final', $post_valor);

            // Redirecionar após o cadastro
            wp_redirect(get_permalink($post_id));
            exit;
        } else {
            die('Erro: A API de pagamento não retornou um ID válido. Resposta da API: ' . $response_payment);
        }
    } else {
        die('Erro: A API de cliente não retornou um ID válido. Resposta da API: ' . $response_customer);
    }
}
?>
