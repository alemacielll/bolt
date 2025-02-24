<?php 
$user_id = get_current_user_id();
$asaas_customer_id = get_user_meta( $user_id, 'asaas_customer_id', true );

if (isset($_POST['new_post']) == '1') {
    extract($_POST);

    $integracao_unimed = '';
    $desconto_unimed = '';
    $lotes = get_field('lotes', $post_evento);
    $lotes_ativos = [];
    $modalidades_ativas = [];
    if ($lotes && is_array($lotes)) {
        $data_atual = date('Y-m-d');         
        foreach ($lotes as $key=>$lote) {
            $esgotado = intval($lote['utilizados']) >= intval($lote['quantidade']);			            
            foreach ($lote['itens'] as $item) {
                $data_inicio = DateTime::createFromFormat('d/m/Y', $item['inicio'])->format('Y-m-d');
                $data_fim = DateTime::createFromFormat('d/m/Y', $item['fim'])->format('Y-m-d');
                $categoria_nome = get_term($item['categoria'])->name;			                
                if ($data_atual >= $data_inicio && $data_atual <= $data_fim && !$esgotado) {
                    $lotes_ativos[] = [
                        'key' => $key,
                        'lote' => $lote['titulo'],
                        'categoria' => $categoria_nome,
                        'inicio' => $item['inicio'],
                        'fim' => $item['fim'],
                        'valor' => $item['valor'],
                    ];			                    
                    $modalidades_ativas[] = [
                        'categoria' => $categoria_nome,
                        'valor' => $item['valor']
                    ];
                }
            }
        }
    }
    if (empty($lotes_ativos)){
        wp_redirect(get_bloginfo('url') . '/adicionar-inscricao?id_evento=' . $post_evento);
        exit;
    }



    $new_post = array(
        'ID' => '',
        'post_type'   => 'inscricao',
        'post_author' => $user_id,
        'post_title'  => $post_title,
        'post_status' => 'publish',
    );
    $post_id = wp_insert_post($new_post);  
    $post_parcelas = 1;       
    add_post_meta($post_id, 'id_evento', $post_evento);
    add_post_meta($post_id, 'modalidade', $post_modalidade);
    add_post_meta($post_id, 'e-mail', $post_email);
    add_post_meta($post_id, 'telefone', $post_telefone);
    add_post_meta($post_id, 'cpf', $post_cpf);
    add_post_meta($post_id, 'genero', $post_genero);
    add_post_meta($post_id, 'tipo_sanguineo', $post_tipo_sanguineo);
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
    add_post_meta($post_id, 'lote', $post_lote);  
    add_post_meta($post_id, 'parcelas', $post_parcelas);
    
    
    foreach ($lotes as $key => $lote) {
        unset($lotes[$key]['itens']);
    }
    if ($lotes && is_array($lotes) && isset($lotes[$post_key_lote])) {
        $lotes[$post_key_lote]['utilizados'] = intval($lotes[$post_key_lote]['utilizados']) + 1;
        update_field('lotes', $lotes, $post_evento);
    }


    $loginUrl = 'https://api-autenticacao.unimedcg.com.br/login';
    $loginData = [
        "dsLogin" => "30245713000186",
        "dsSenha" => "Q@mpkH76dsUDT635",
        "tipoAutenticacao" => 1
    ];
    $loginHeaders = [
        "Content-Type: application/json"
    ];
    $ch = curl_init($loginUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $loginHeaders);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($loginData));
    $loginResponse = curl_exec($ch);
    $loginHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($loginHttpCode == 200 || $loginHttpCode == 201) {
        $token = trim($loginResponse);
    }     
    if (isset($post_cpf) && !empty($post_cpf) && isset($post_evento) && get_field('unimed', $post_evento)) {
        $cpf = preg_replace('/\D/', '', trim($post_cpf));
        $discountUrl = "https://api-unimedcg.unimedcg.com.br/api/v1/bolts/verifica-desconto/" . $cpf;
        $discountHeaders = [
            "Content-Type: application/json",
            "Authorization: Bearer " . $token
        ];
        $ch = curl_init($discountUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $discountHeaders);
        $discountResponse = curl_exec($ch);
        $discountHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($discountHttpCode == 200 || $discountHttpCode == 201) {
            $data = json_decode($discountResponse, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {               
                if (isset($data['cooperado']) && $data['cooperado'] === true) {
                    $integracao_unimed = 'cooperado';
                } elseif ((isset($data['cliente']) && $data['cliente'] === true) || (isset($data['colaborador']) && $data['colaborador'] === true)) {
                    $desconto_unimed = "UNIMEDESPECIAL";
                } 
            } 
        } 
    }
    add_post_meta($post_id, 'integracao_unimed', $integracao_unimed);
    $valor_final = $post_valor;
    if(!empty($desconto_unimed)){
        $args = array(
            'posts_per_page' => '-1',
            'post_type'      => 'cupom',
            'title'          => $desconto_unimed
        );      
        $wp_query = new WP_Query($args);
        if($wp_query->have_posts()) : 
            while($wp_query->have_posts()) : 
                $wp_query->the_post(); 
                $i++;       
                $id_cupom     = get_the_ID();
                $tipo         = get_field('tipo');
                $valor_cupom  = get_field('valor');           
                
                if ($tipo == 'PERCENTAGE') {                    
                    $valor           = $post_valor;
                    $valor_desconto  = $valor * ($valor_cupom / 100);
                    $valor_final     = $valor - $valor_desconto;
                } else {
                    $valor_final    = $post_valor - $valor_cupom;
                    $valor_desconto = $valor_cupom;
                }
                
                // Se o valor final for negativo, redefine para 35
                if($valor_final < 0){
                    $valor_final = 35;
                    $valor_desconto = 0;
                    $id_cupom = '';
                    $post_valor  = 35; // Atualiza o "campo valor"
                }
                
                add_post_meta($post_id, 'cupom_desconto', $id_cupom);
                add_post_meta($post_id, 'valor_desconto', $valor_desconto);
                
                // Atualiza os campos 'valor' e 'valor_final' com 35 (ou com os valores já ajustados)
                update_post_meta($post_id, 'valor', $post_valor);
                update_post_meta($post_id, 'valor_final', $valor_final);
                update_post_meta($post_id, 'valor_desconto', $valor_desconto);
                update_post_meta($post_id, 'id_cupom', $id_cupom);
                
            endwhile; 
        endif;
    } 
    if($post_valor<=0 || !empty($integracao_unimed)){ 
        add_post_meta($post_id, 'forma_pagamento', 'FREE');
        add_post_meta($post_id, 'situacao_pagamento','CONFIRMED');
        add_post_meta($post_id, 'data_pagamento', date('Y-m-d'));
        add_post_meta($post_id, 'pago', true);
        add_post_meta($post_id, 'valor','0.00');
        add_post_meta($post_id, 'valor_final','0.00');
        wp_redirect(get_permalink($post_id));
        exit;
    }else{ 
        add_post_meta($post_id, 'valor', $post_valor);
        add_post_meta($post_id, 'valor_final', $valor_final);
        if(empty($asaas_customer_id)){    
            $retorno = do_shortcode('[asaas_criar_cliente 
                name="' . $post_title . '" 
                cpfcnpj="' . $post_cpf . '"
                email="' . $post_email . '"                
                address="' . $post_rua . '"
                addressNumber="' . $post_numero . '"
                complement="' .  $post_complemento . '"
                province="' . $post_bairro . '"
                postalCode="' . $post_cep . '"
                groupName="' .$post_assessoria . '"
            ]');
            $retorno = json_decode($retorno, true);
            $asaas_customer_id = $retorno['id'];
            update_user_meta( $user_id, 'asaas_customer_id', $asaas_customer_id );
        }
        $id_prova = $post_evento;
        $titulo_prova = get_the_title($id_prova);
        if (!empty($asaas_customer_id)) {
            $retorno = do_shortcode('[asaas_criar_pagamento 
                customer="' . $asaas_customer_id . '" 
                value="' . $valor_final . '"
                duedate="' . date('Y-m-d', strtotime('+1 day')) . '"
                description="' . sprintf("Inscrição: %s - %s - Prova: %s", $post_modalidade, $post_valor_final, $titulo_prova) . '"
            ]');
            $retorno = json_decode($retorno, true);
            if(!empty($retorno['id'])){
                $payment_id = $retorno['id']; 
                add_post_meta( $post_id, 'id_consumidor', $asaas_customer_id );    
                add_post_meta($post_id, 'id_pagamento', $payment_id);
                wp_redirect(get_permalink($post_id));
                exit;
            } else {
                die($retorno['errors'][0]['description']);
            }
        } else {
            die('Erro: Customer ID não encontrado');
        }
    }
}
?>
