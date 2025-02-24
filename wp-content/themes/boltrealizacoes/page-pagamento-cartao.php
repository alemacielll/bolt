<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['acao'] == 'pagar_cartao') {
    $post_id = sanitize_text_field($_POST['post_id']); 
    $parcelas = sanitize_text_field($_POST['parcelas']); 
    $holderName = sanitize_text_field($_POST['holderName']);
    $number = sanitize_text_field($_POST['number']);
    $expiryMonth = sanitize_text_field($_POST['expiryMonth']);
    $expiryYear = sanitize_text_field($_POST['expiryYear']);
    $ccv = sanitize_text_field($_POST['ccv']);
    $nome = sanitize_text_field($_POST['nome']);
    $id = sanitize_text_field($_POST['id']);
    $value = get_field('valor_final', $post_id);
    $email = sanitize_email($_POST['email']);
    $cpfCnpj = sanitize_text_field($_POST['cpfCnpj']);
    $postalCode = sanitize_text_field($_POST['postalCode']);
    $addressNumber = sanitize_text_field($_POST['addressNumber']);
    $phone = sanitize_text_field($_POST['phone']); 
    
    if($parcelas==1){
            $retorno = do_shortcode('[asaas_pagar_cartao 
            paymentid="' .$_POST['id'] . '" 
            holdername="' . $holderName . '"
            cardnumber="' . $number . '"
            expirymonth="' . $expiryMonth . '"
            expiryyear="' . $expiryYear . '"
            ccv="' . $ccv . '"
            name="' . $nome . '"
            email="' . $email . '"
            cpfcnpj="' . $cpfCnpj . '"
            phone="' . $phone . '"
            postalcode="' . $postalCode . '"
            addressnumber="' . $addressNumber . '"
        ]');   
        $retorno = json_decode($retorno, true);
        if(!empty($retorno['errors'])){
            wp_redirect(get_permalink($post_id) . '?situacao_pagamento=erro&error_message=' . urlencode($retorno['errors'][0]['description']));
            exit;
        }else{
            $id_pagamento = $retorno['id'];
            $status = $retorno['status'];
            $foi_pago = in_array($status, ['CONFIRMED', 'RECEIVED']);
            $paymentdate = $retorno['paymentDate'];
            update_post_meta($post_id, 'forma_pagamento', 'CREDIT_CARD');
            update_post_meta($post_id, 'situacao_pagamento',$status);
            update_post_meta($post_id, 'parcelas', $parcelas);
            if($foi_pago){
                update_post_meta($post_id, 'pago', $foi_pago);
            }
            wp_redirect(get_permalink($post_id)); 
            exit;
        }
    }else{
        $user_id = get_current_user_id();
        $asaas_customer_id = get_user_meta( $user_id, 'asaas_customer_id', true );
        $valor_final = 'R$ '.number_format($value, 2, ',', '.');
        $modalidade = get_field('modalidade', $post_id);
        $titulo_prova = get_the_title(get_field('id_evento', $post_id));
        $retorno = do_shortcode('[asaas_criar_pagamento_parcelado_cartao 
            customer="' . $asaas_customer_id . '" 
            value="' . $value . '"
            duedate="' .  date('Y-m-d', strtotime('+1 day')) . '"
            description="' . sprintf("Inscrição: %s - %s - Prova: %s", $modalidade, $valor_final, $titulo_prova) . '"
            holdername="' . $holderName . '"
            cardnumber="' . $number . '"
            expirymonth="' . $expiryMonth . '"
            expiryyear="' . $expiryYear . '"
            ccv="' . $ccv . '"
            name="' . $nome . '"
            email="' . $email . '"
            cpfcnpj="' . $cpfCnpj . '"
            phone="' . $phone . '"
            postalcode="' . $postalCode . '"
            addressnumber="' . $addressNumber . '"
            installments="' . $parcelas . '"
        ]'); 
        $retorno = json_decode($retorno, true);
        if(!empty($retorno['errors'])){
            wp_redirect(get_permalink($post_id) . '?situacao_pagamento=erro&error_message=' . urlencode($retorno['errors'][0]['description']));
            exit;
        }else{
            $installment = $retorno['id'];
            update_post_meta($post_id, 'installment', $installment);
            update_post_meta($post_id, 'forma_pagamento', 'CREDIT_CARD');
            update_post_meta($post_id, 'situacao_pagamento','CONFIRMED');
            update_post_meta($post_id, 'parcelas', $parcelas);
            update_post_meta($post_id, 'pago', true);
            wp_redirect(get_permalink($post_id)); 
            exit;
        }
    }   
    
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['acao'] == 'aplicar_cupom') {
    $paymentid = $_POST['id'];
    $post_id = $_POST['post_id'];
    $id_evento = $_POST['id_evento'];
    $titulo_cupom = strtoupper($_POST['titulo_cupom']);      
    $wp_query = new WP_Query(array(
        'post_type'      => 'cupom',
        'posts_per_page' => '1',
        'title'=>$_POST['titulo_cupom'],
        'meta_query'     => array(
            'relation' => 'AND',
            array(
                'key'   => 'id_evento',
                'compare' => '=',
                'value' => $id_evento
            )
        )
    ));
    if($wp_query->have_posts()) : while($wp_query->have_posts()) : $wp_query->the_post(); $i++;       
        $id_cupom = get_the_ID();
        $quantidade = get_field('quantidade');
        $utilizados = get_field('utilizados');
        $tipo = get_field('tipo');
        $valor_cupom = get_field('valor');
        $valor = get_field('valor', $post_id);

        if ($quantidade > $utilizados) {
            if (($tipo == 'PERCENTAGE' && $valor_cupom == 100) || ($tipo == 'FIXED' && $valor_cupom >= $valor)) {
                // Cupom cobre 100% do valor, apenas atualizar os campos
                update_post_meta($post_id, 'valor', '0.00');
                update_post_meta($post_id, 'valor_desconto', $valor);
                update_post_meta($post_id, 'valor_final', '0.00');
                update_post_meta($post_id, 'data_pagamento', current_time('Y-m-d H:i:s'));
                update_post_meta($post_id, 'utilizados', $utilizados + 1);
                update_post_meta($post_id, 'id_pagamento', '');
                update_post_meta($post_id, 'pago', true);
                update_post_meta($post_id, 'cupom_desconto', $id_cupom);
                
                update_post_meta($id_cupom, 'utilizados', $utilizados + 1);
                
                asaas_cancelar_pagamento($paymentid);
                
                wp_redirect(get_permalink($post_id) . '?success_message=' . urlencode('Cupom aplicado com sucesso!'));
                exit;
            }
            
            $retorno = do_shortcode('[asaas_aplicar_cupom
                paymentid="' . $paymentid . '" 
                type="' . $tipo . '" 
                value="' . $valor_cupom . '"
            ]');
            $retorno = json_decode($retorno, true);
            if (!empty($retorno['errors'])) {
                wp_redirect(get_permalink($post_id) . '?situacao_pagamento=erro&error_message=' . urlencode($retorno['errors'][0]['description']));
                exit;
            } else {
                if ($tipo == 'PERCENTAGE') {                    
                    $valor_desconto = $valor * ($valor_cupom / 100);
                    $valor_final = $valor - $valor_desconto;
                } else {
                    $valor_final = $valor - $valor_cupom;
                    $valor_desconto = $valor_cupom;
                }
                update_post_meta($post_id, 'valor_final', $valor_final);
                update_post_meta($post_id, 'cupom_desconto', $id_cupom);
                update_post_meta($post_id, 'valor_desconto', $valor_desconto);
                
                update_post_meta($id_cupom, 'utilizados', $utilizados + 1);
                
                wp_redirect(get_permalink($post_id) . '?success_message=' . urlencode('Cupom adicionado com sucesso!'));
                exit;    
            }
        } else {
            wp_redirect(get_permalink($post_id) . '?situacao_pagamento=erro&error_message=' . urlencode('Cupom indisponível!'));
            exit;
        }
    endwhile; else : 
        wp_redirect(get_permalink($post_id) . '?situacao_pagamento=erro&error_message=' . urlencode('Cupom indisponível!'));
        exit;    
    endif;
    wp_reset_query();
}


?>