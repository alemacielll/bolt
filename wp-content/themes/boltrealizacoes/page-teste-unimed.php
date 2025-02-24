<?php
// Ajustar titulos para capitalized

/*
$args = array(
    'post_type'      => 'seu_post_type', 
    'posts_per_page' => -1,
    'post_status'    => 'publish' 
);

$posts = get_posts( $args );

if ( ! empty( $posts ) ) {
    foreach ( $posts as $post ) {
        $title = $post->post_title;
        if ( $title === strtoupper( $title ) ) {
            $new_title = ucwords( strtolower( $title ) );
            wp_update_post( array(
                'ID'         => $post->ID,
                'post_title' => $new_title
            ) );
            echo "Post ID {$post->ID} atualizado para: {$new_title}<br>";
        }
    }
} else {
    echo "Nenhum post encontrado.";

*/
?>


<?php /* ?>

<?php get_header(); ?>

<div class="container py-5">
    <!-- Formulário para informar o CPF -->
    <form method="GET" class="mb-4">
        <div class="form-group">
            <label for="cpf">CPF:</label>
            <input type="text" name="cpf" id="cpf" class="form-control" placeholder="Digite o CPF" required>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Verificar Desconto</button>
    </form>

    <?php
    // === Autenticação e obtenção do token ===
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
        // Supondo que o token é retornado como string pura
        $token = trim($loginResponse);
        // O token não será exibido na tela.
    } else {
        echo "<p>Erro ao autenticar. Código HTTP: " . $loginHttpCode . "</p>";
        echo "<p>Resposta: " . htmlspecialchars($loginResponse) . "</p>";
        exit;
    }

    // === Se um CPF for informado, chama o endpoint de verificação de desconto ===
    if (isset($_GET['cpf']) && !empty($_GET['cpf'])) {
        // Remove qualquer caractere não numérico do CPF
        $cpf = preg_replace('/\D/', '', trim($_GET['cpf']));
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
            // Decodifica a resposta JSON
            $data = json_decode($discountResponse, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                // Valida o desconto conforme as regras:
                // Se 'cooperado' for true, o desconto é 100%.
                // Senão, se 'cliente' ou 'colaborador' for true, o desconto é R$ 50,00.
                // Caso contrário, não há desconto (0%).
                if (isset($data['cooperado']) && $data['cooperado'] === true) {
                    $desconto = "100%";
                } elseif ((isset($data['cliente']) && $data['cliente'] === true) || (isset($data['colaborador']) && $data['colaborador'] === true)) {
                    $desconto = "R$ 50,00";
                } else {
                    $desconto = "0%";
                }

                echo "<h4>Resultado:</h4>";
                echo "<p>Desconto: " . htmlspecialchars($desconto) . "</p>";
            } else {
                echo "<p>Erro ao decodificar a resposta JSON.</p>";
            }
        } else {
            echo "<p>Erro ao verificar desconto. Código HTTP: " . $discountHttpCode . "</p>";
            echo "<p>Resposta: " . htmlspecialchars($discountResponse) . "</p>";
        }
    }
    ?>
</div>


<?php get_footer(); ?>
<?php */?>

<?php if ( is_user_logged_in() && ( current_user_can('administrator') || current_user_can('editor') ) ) { ?>


<?php get_header(); ?>

<div class="container">

<?php
// Processamento da exclusão caso algum formulário seja enviado
if ( isset( $_POST['delete_post_id'] ) && isset( $_POST['_wpnonce'] ) ) {
    $post_id = intval( $_POST['delete_post_id'] );
    
    if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'delete_inscricao_' . $post_id ) ) {
        echo '<div class="error"><p>Nonce inválido. Ação não autorizada.</p></div>';
    } else {
        // Recupera o ID de pagamento salvo no meta 'id_pagamento'
        $payment_id = get_post_meta( $post_id, 'id_pagamento', true );
        
        if ( ! empty( $payment_id ) ) {
            $result_message = asaas_cancelar_pagamento( $payment_id );
        } else {
            $result_message = 'Nenhum ID de pagamento encontrado para este post.';
        }
        // Exclui o post permanentemente
        wp_delete_post( $post_id, true );

        echo '<div class="updated"><p>Post excluído. Resultado do cancelamento:</p><pre>' . esc_html( $result_message ) . '</pre></div>';
    }
}

// Primeiro: recuperar os títulos duplicados dos posts "inscricao" que possuem meta "id_evento" com valor "687"
global $wpdb;
$sql = $wpdb->prepare(
    "SELECT p.post_title
     FROM {$wpdb->posts} p
     INNER JOIN {$wpdb->postmeta} m ON p.ID = m.post_id
     WHERE p.post_type = %s
       AND p.post_status = 'publish'
       AND m.meta_key = %s
       AND m.meta_value = %s
     GROUP BY p.post_title
     HAVING COUNT(*) > 1",
    'inscricao',
    'id_evento',
    '687'
);
$duplicate_titles = $wpdb->get_col( $sql );

if ( ! empty( $duplicate_titles ) ) {
    // Consulta os posts do tipo "inscricao" com meta "id_evento" igual a "687"
    // e filtra apenas os posts cujo título esteja na lista de duplicados.
    $args = array(
        'post_type'      => 'inscricao',
        'post_status'    => 'publish',
        'meta_query'     => array(
            array(
                'key'     => 'id_evento',
                'value'   => '687',
                'compare' => '='
            )
        ),
        'posts_per_page' => -1,
        'orderby'        => 'title', // Ordena pelo título (alfabético)
        'order'          => 'ASC'
    );
    $query = new WP_Query( $args );
    
    if ( $query->have_posts() ) {
        echo '<table class="table table-striped">';
        echo '<thead>
                <tr>
                    <th>Prova</th>
                    <th>Título</th>
                    <th>Modalidade</th>
                    <th>ID Pagamento</th>
                    <th>Valor</th>
                    <th>Situação</th>
                    <th>Ações</th>
                </tr>
              </thead>';
        echo '<tbody>';
        
        while ( $query->have_posts() ) {
            $query->the_post();
            // Só exibe se o título deste post estiver na lista de duplicados
            if ( in_array( get_the_title(), $duplicate_titles ) ) {
                $post_id    = get_the_ID();
                $payment_id = get_post_meta( $post_id, 'id_pagamento', true );
                $modalidade = get_post_meta( $post_id, 'modalidade', true );
                $prova = get_post_meta( $post_id, 'id_evento', true );
                $situacao   = get_post_meta( $post_id, 'pago', true );
                $valor      = get_post_meta( $post_id, 'valor_final', true );
                
                echo '<tr>';
                echo '<td>' . esc_html( $prova ) . '</td>';
                echo '<td>' . get_the_title() . '</td>';
                echo '<td>' . esc_html( $modalidade ) . '</td>';
                echo '<td>' . esc_html( $payment_id ) . '</td>';
                echo '<td>' . esc_html( $valor ) . '</td>';
                echo '<td>' . esc_html( $situacao ) . '</td>';
                echo '<td>';
                
                // Formulário com botão de exclusão para cada post
                echo '<form method="post" onsubmit="return confirm(\'Tem certeza que deseja excluir este post e cancelar o pagamento?\');">';
                echo '<input type="hidden" name="delete_post_id" value="' . esc_attr( $post_id ) . '">';
                echo wp_nonce_field( 'delete_inscricao_' . $post_id, '_wpnonce', true, false );
                echo '<input type="submit" class="btn btn-danger" value="Excluir">';
                echo '</form>';
                
                echo '</td>';
                echo '</tr>';
            }
        }
        
        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<p>Nenhuma inscrição encontrada.</p>';
    }
    
    wp_reset_postdata();
} else {
    echo '<p>Nenhum título duplicado encontrado para inscrições com id_evento = 687.</p>';
}

get_footer();
?>
</div>


<?php } else { ?>

    <?php wp_redirect( home_url('login') ); exit; ?>

<?php } ?>