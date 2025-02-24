$(document).ready(function () {
    // Aplicar máscaras nos campos específicos
    $('#cpf').inputmask('999.999.999-99'); // Máscara CPF
    $('#aluno_cep').inputmask('99999-999'); // Máscara CEP
    $('#telefone').inputmask('(99) 9 9999-9999'); // Máscara Telefone 1
    $('#telefone2').inputmask('(99) 9 9999-9999'); // Máscara Telefone 2

    // Evento de blur para busca de CEP
    $("#aluno_cep").blur(function () {
        const cep = $(this).val().replace(/[^0-9]/g, ''); // Remove caracteres não numéricos
        if (cep !== "") {
            const url = `https://viacep.com.br/ws/${cep}/json/`;
            
            $.getJSON(url, function (json) {
                if (!json.erro) {
                    // Preenche os campos de endereço
                    $("#aluno_endereco").val(json.logradouro);
                    $("#aluno_bairro").val(json.bairro);
                    $("#aluno_estado").val(json.uf);
                    $("#aluno_cidade").val(json.localidade);
                    $('#aluno_numero').focus();
                } else {
                    alert("CEP não encontrado!");
                    limparEndereco();
                }
            }).fail(function () {
                alert("Erro ao buscar o CEP. Tente novamente.");
                limparEndereco();
            });
        }
    });

    // Função para limpar os campos de endereço
    function limparEndereco() {
        $("#aluno_endereco").val('');
        $("#aluno_bairro").val('');
        $("#aluno_estado").val('');
        $("#aluno_cidade").val('');
        $("#aluno_numero").val('');
    }
});

new dgCidadesEstados({
    estado: $('#aluno_estado')[0],
    cidade: $('#aluno_cidade')[0],
    change: true,
    estadoVal: 'MS'
});

$(document).ready(function() {
    $("#modalidade").change(function() {
        let selectedText = $("#modalidade option:selected").text();
        let priceMatch = selectedText.match(/R\$ (\d+[\.,]?\d{0,2})/);

        if (priceMatch) {
            let formattedPrice = priceMatch[1].replace(",", "."); // Substitui vírgula por ponto
            $("#post_valor, #post_valor_final").val(formattedPrice);
        }
    });
});