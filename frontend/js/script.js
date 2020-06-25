$(document).ready(function() {

    //var url_backend = '/backend/';
    var url_backend = '/clientes/firenet/crud-php/backend/';

    function listarClientes() {
        $(".lista_clientes").html('<tr><td colspan="5" style="text-align: center;">Carregando...</td></tr>');
        $.ajax({
            url: url_backend+"clientes.php?_method=GET",
            type: "GET",
            success: function (reposta) {
                $(".lista_clientes").html('');
                for(var i = 0; i < reposta['abrir_cliente'].length; i++){
                    $(".lista_clientes").append(''+
                        '<tr id="item_cliente-'+reposta['abrir_cliente'][i]['id']+'">'+
                            '<td>'+
                                reposta['abrir_cliente'][i]['nome']+
                            '</td>'+
                            '<td>'+
                                reposta['abrir_cliente'][i]['endereco']+
                            '</td>'+
                            '<td>'+
                                reposta['abrir_cliente'][i]['endereco_numero']+
                            '</td>'+
                            '<td>'+
                                reposta['abrir_cliente'][i]['telefone']+
                            '</td>'+
                            '<td>'+
                                reposta['abrir_cliente'][i]['credito']+
                            '</td>'+
                            '<td>'+
                                '<button type="button" class="botao_editar_cliente" id="botao_editar_cliente-'+reposta['abrir_cliente'][i]['id']+'" data-cod_cliente="'+reposta['abrir_cliente'][i]['id']+'" data-loading-text="...">Editar</button>'+
                                '&nbsp;'+
                                '<button type="button" class="botao_excluir_cliente" id="botao_excluir_cliente-'+reposta['abrir_cliente'][i]['id']+'" data-cod_cliente="'+reposta['abrir_cliente'][i]['id']+'" data-loading-text="...">Excluir</button>'+
                            '</td>'+
                        '</tr>'
                    );
                }
                
            }
        });
    }
    listarClientes();

    function abrirClientes(cod_cliente) {
        $("#inputNome").val('');
        $("#inputEndereco").val('');
        $("#inputEnderecoNumero").val('');
        $("#inputTelefone").val('');
        $("#inputCredito").val('');
        $("#inputCodCliente").val('0');
        
        $.ajax({
            url: url_backend+"clientes.php?_method=GET&id="+cod_cliente,
            type: "GET",
            success: function (reposta) {
                $("#inputNome").val(reposta['abrir_cliente'][0]['nome']);
                $("#inputEndereco").val(reposta['abrir_cliente'][0]['endereco']);
                $("#inputEnderecoNumero").val(reposta['abrir_cliente'][0]['endereco_numero']);
                $("#inputTelefone").val(reposta['abrir_cliente'][0]['telefone']);
                $("#inputCredito").val(reposta['abrir_cliente'][0]['credito']);
                $("#inputCodCliente").val(reposta['abrir_cliente'][0]['id']);
            }
        });
    }

    $("#form_cadastrar_cliente").validate({
        debug: true,
        eachInvalidField : function() {
            $(this).css('background', '#FFEAEA');
            $(this).attr("placeholder", "");
        },
        eachValidField : function() {
            $(this).css('background', '#FFFFFF');
            $(this).attr("placeholder", "");
        },
        valid: function() {
            var loading_text = $("#botao_cadastrar_cliente").attr('data-loading-text');
            var botao_text = $("#botao_cadastrar_cliente").html();
            var loading = '...';
            if(loading_text!=undefined){
                loading = loading_text;
            }

            var metodo = $("#metodo").val();
            
            var formData = new FormData();
            formData.append('nome', $("#inputNome").val());
            formData.append('endereco', $("#inputEndereco").val());
            formData.append('endereco_numero', $("#inputEnderecoNumero").val());
            formData.append('telefone', $("#inputTelefone").val());
            formData.append('credito', $("#inputCredito").val());

            $("#botao_cadastrar_cliente").attr("disabled", true).text(loading);
            $.ajax({
                url: url_backend+"index.php?_method="+metodo,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (reposta) {
                    listarClientes();
                    $("#botao_cadastrar_cliente").attr("disabled", false).html(botao_text);

                }
            });
            
        }
    });

    $(document).on('click', ".botao_editar_cliente", function (){
        var cod_cliente = $(this).attr('data-cod_cliente');

        var loading_text = $("#botao_editar_cliente-"+cod_cliente).attr('data-loading-text');
        var botao_text = $("#botao_editar_cliente-"+cod_cliente).text();
        var loading = '...';
        if(loading_text!=undefined){
            loading = loading_text;
        }

        $("#botao_editar_cliente-"+cod_cliente).attr("disabled", true).text(loading);
        abrirClientes(cod_cliente);
        $("#botao_editar_cliente-"+cod_cliente).attr("disabled", false).html(botao_text);
    });


});