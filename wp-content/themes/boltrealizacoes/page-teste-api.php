<?php 
/*
$post_evento = 349;
$post_key_lote = 4;
$lotes = get_field('lotes', $post_evento);
foreach ($lotes as $key => $lote) {
    unset($lotes[$key]['itens']);
}
if ($lotes && is_array($lotes) && isset($lotes[$post_key_lote])) {
    $lotes[$post_key_lote]['utilizados'] = intval($lotes[$post_key_lote]['utilizados']) + 1;
    update_field('lotes', $lotes, $post_evento);
}*/
/*
if ($lotes && is_array($lotes)) {
    foreach ($lotes as $key => $lote) {
        $esgotado = intval($lote['utilizados']) >= intval($lote['quantidade']);
    if ($key == $post_key_lote) {
            $lotes[$key]['utilizados'] = intval($lote['utilizados']) + 1;
        }
    }
    update_field('lotes', $lotes, $post_evento);
}  
print_r($lotes);die;*/
/*
$_POST = [
    'name' => 'João da Silva',
    'cpfcnpj' => '72094575187',
    'email' => 'joao@email.com',
    'mobilephone' => '67991122210',
    'address' => 'Rua Exemplo',
    'addressNumber' => '123',
    'complement' => 'Apto 101',
    'province' => 'SP',
    'postalCode' => '01001000',
    'groupName' => 'Cliente Vip',
];

// Converter todas as chaves e valores para minúsculas
$_POST = array_map('strtolower', $_POST);
$_POST = array_map('esc_attr', $_POST);  // Adicionei para escapar os valores, caso necessário

$retorno = do_shortcode('[asaas_criar_cliente 
    name="' . $_POST['name'] . '" 
    cpfcnpj="' . $_POST['cpfcnpj'] . '"
    email="' . $_POST['email'] . '"
    mobilephone="' . $_POST['mobilephone'] . '"
    address="' . $_POST['address'] . '"
    addressNumber="' . $_POST['addressNumber'] . '"
    complement="' . $_POST['complement'] . '"
    province="' . $_POST['province'] . '"
    postalCode="' . $_POST['postalCode'] . '"
    groupName="' . $_POST['groupName'] . '"
]');
$retorno = json_decode($retorno, true);
$id_cliente = $retorno['id'];
print_r($id_cliente);
die;
$user_id = get_current_user_id();
update_user_meta( $user_id, 'asaas_customer_id', 'cus_000006479492' );
$asaas_customer_id = get_user_meta( $user_id, 'asaas_customer_id', true );
print_r($asaas_customer_id);
die;*/

/*
    $_POST = [
        'name' => 'João da Silva',
        'cpfcnpj' => '72094575187',
        'email' => 'joao@email.com',
        'mobilephone' => '67991122210',
        'address' => 'Rua Exemplo',
        'addressNumber' => '123',
        'complement' => 'Apto 101',
        'province' => 'SP',
        'postalCode' => '01001000',
        'groupName' => 'Cliente Vip',
    ];

    // Converter todas as chaves e valores para minúsculas
    $_POST = array_map('strtolower', $_POST);
    $_POST = array_map('esc_attr', $_POST);  // Adicionei para escapar os valores, caso necessário

    $retorno = do_shortcode('[asaas_criar_cliente 
        name="' . $_POST['name'] . '" 
        cpfcnpj="' . $_POST['cpfcnpj'] . '"
        email="' . $_POST['email'] . '"
        mobilephone="' . $_POST['mobilephone'] . '"
        address="' . $_POST['address'] . '"
        addressNumber="' . $_POST['addressNumber'] . '"
        complement="' . $_POST['complement'] . '"
        province="' . $_POST['province'] . '"
        postalCode="' . $_POST['postalCode'] . '"
        groupName="' . $_POST['groupName'] . '"
    ]');
    $retorno = json_decode($retorno, true);
    $id_cliente = $retorno['id'];
    print_r($id_cliente);
    die;*/

    /*
    $id_cliente = 'cus_000006516198';
    $_POST = [
        'customer' => $id_cliente,
        'value' => '100.29',
        'duedate' => date('Y-m-d', strtotime('+1 day')),
        'description' => 'Inscrição: A - Nome Participante - Prova: Prova 1',
    ];
    $retorno = do_shortcode('[asaas_criar_pagamento 
        customer="' . $_POST['customer'] . '" 
        value="' . $_POST['value'] . '"
        duedate="' . $_POST['duedate'] . '"
        description="' . $_POST['description'] . '"
    ]');
    $retorno = json_decode($retorno, true);
    $id_pagamento = $retorno['id'];
    //print_r($id_pagamento);die;
    
    $_POST = [
        'paymentid' => $id_pagamento,           
        'holdername' => 'João da Silva',
        'cardnumber' => '4444444444444444',
        'expirymonth' => '01',
        'expiryyear' => '2026',
        'ccv' => '123',
        'name' => 'João da Silva',
        'email' => 'joao@email.com',
        'cpfcnpj' => '72094575187',
        'phone' => '67991122210',
        'postalcode' => '01001000',
        'addressnumber' => '123',
    ];
    $retorno = do_shortcode('[asaas_pagar_cartao
        paymentid="' .$_POST['paymentid'] . '"  
        holdername="' . $_POST['holdername'] . '"
        cardnumber="' . $_POST['cardnumber'] . '"
        expirymonth="' . $_POST['expirymonth'] . '"
        expiryyear="' . $_POST['expiryyear'] . '"
        ccv="' . $_POST['ccv'] . '"
        name="' . $_POST['name'] . '"
        email="' . $_POST['email'] . '"
        cpfcnpj="' . $_POST['cpfcnpj'] . '"
        phone="' . $_POST['phone'] . '"
        postalcode="' . $_POST['postalcode'] . '"
        addressnumber="' . $_POST['addressnumber'] . '"
        installments="' . $_POST['installments'] . '"
        split="' . $_POST['split'] . '"
    ]');
    $retorno = json_decode($retorno, true);
    */



    $id_cliente = 'cus_000006516198';
    $_POST = [
        'customer' => $id_cliente,
        'value' => '65',
        'duedate' => date('Y-m-d', strtotime('+1 day')),
        'description' => 'Inscrição: A - Nome Participante - Prova: Prova 1',        
        'holdername' => 'João da Silva',
        'cardnumber' => '4444444444444444',
        'expirymonth' => '01',
        'expiryyear' => '2026',
        'ccv' => '123',
        'name' => 'João da Silva',
        'email' => 'joao@email.com',
        'cpfcnpj' => '72094575187',
        'phone' => '67991122210',
        'postalcode' => '01001000',
        'addressnumber' => '123',
        'installments'=>2,
        'split' => false
    ];
    $retorno = do_shortcode('[asaas_criar_pagamento_parcelado_cartao 
        customer="' . $_POST['customer'] . '" 
        value="' . $_POST['value'] . '"
        duedate="' . $_POST['duedate'] . '"
        description="' . $_POST['description'] . '"
        installments="' . $_POST['installments'] . '",
        holdername="' . $_POST['holdername'] . '"
        cardnumber="' . $_POST['cardnumber'] . '"
        expirymonth="' . $_POST['expirymonth'] . '"
        expiryyear="' . $_POST['expiryyear'] . '"
        ccv="' . $_POST['ccv'] . '"
        name="' . $_POST['name'] . '"
        email="' . $_POST['email'] . '"
        cpfcnpj="' . $_POST['cpfcnpj'] . '"
        phone="' . $_POST['phone'] . '"
        postalcode="' . $_POST['postalcode'] . '"
        addressnumber="' . $_POST['addressnumber'] . '"
        installments="' . $_POST['installments'] . '"
        split="' . $_POST['split'] . '"
    ]');
    $retorno = json_decode($retorno, true);
    $installment = $retorno['id'];
    print_r($retorno);die;
    

