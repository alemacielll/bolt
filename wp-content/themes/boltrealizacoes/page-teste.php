<?php 
//codigo para adicionar lote
/*$post_id = 88;
$lotes = get_field('lotes', $post_id);
if (!$lotes || !is_array($lotes)) {
    $lotes = []; 
}
$novo_lote = [
    'titulo' => 'Lote 3',
    'quantidade' => 150,
    'itens' => [
        [
            'categoria' => '1',
            'inicio' => '2025-02-20',
            'fim' => '2025-02-25',
            'valor' => '100',
        ],
        [
            'categoria' => '2',
            'inicio' => '2025-02-20',
            'fim' => '2025-02-25',
            'valor' => '200',
        ],
    ],
];
$lotes[] = $novo_lote;
update_field('lotes', $lotes, $post_id);
echo 'Lote adicionado com sucesso!';*/


//codigo listagem de lotes
$post_id = 88;
echo get_the_title($post_id);echo '<br>';
$lotes = get_field('lotes', $post_id);
$lotes_ativos = [];
if ($lotes && is_array($lotes)) {
    $data_atual = date('Y-m-d'); 
    foreach ($lotes as $lote) {
        echo '<strong>' . esc_html($lote['titulo']) . '</strong><br>';
        echo 'Quantidade: ' . esc_html($lote['quantidade']) . '<br>';
        foreach ($lote['itens'] as $item) {
            $data_inicio = DateTime::createFromFormat('d/m/Y', $item['inicio'])->format('Y-m-d');
            $data_fim = DateTime::createFromFormat('d/m/Y', $item['fim'])->format('Y-m-d');
            if ($data_atual >= $data_inicio && $data_atual <= $data_fim) {
                $lotes_ativos[] = [
                    'lote' => $lote['titulo'],
                    'categoria' => $item['categoria'],
                    'inicio' => $item['inicio'],
                    'fim' => $item['fim'],
                ];
                echo '<span style="color: green;">✔ Categoria ' . esc_html($item['categoria']) . ' está ativa!</span><br>';
            } else {
                echo '<span style="color: red;">✘ Categoria ' . esc_html($item['categoria']) . ' não está ativa.</span><br>';
            }
        }
        echo '<hr>'; 
    }
}

if (!empty($lotes_ativos)) {
    echo '<h3>Lotes Ativos no Momento:</h3>';
    foreach ($lotes_ativos as $ativo) {
        echo '<p><strong>' . esc_html($ativo['lote']) . '</strong> - Categoria ' . esc_html($ativo['categoria']) . 
        ' (De ' . esc_html($ativo['inicio']) . ' até ' . esc_html($ativo['fim']) . ')</p>';
    }
} else {
    echo '<p>Nenhum lote ativo no momento.</p>';
}