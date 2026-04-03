<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    require_once $_SERVER['DOCUMENT_ROOT'] . '/config/conexao.php';
    header('Content-Type: application/json');
    $con = connect_local_mysqli('gestao_ambiental');

    $carregarDados = $_POST['carregarDados'] ?? '';

    if ($carregarDados == 'sim') {

        $sql = "SELECT * FROM lista_castanheira";
        $resultado = mysqli_query($con, $sql);

        $dados = [];

        if ($resultado) {
            while ($row = mysqli_fetch_assoc($resultado)) {
                $dados[] = [
                    'id' => $row['id'],
                    'item' => $row['item'],
                    'desc_item' => $row['desc_item'],
                    'descricao' => $row['descricao']
                ];
            }

            if (empty($dados)) {
                echo json_encode(["error" => "Consulta não retornou dados."]);
            } else {
                echo json_encode(["dados" => $dados]);
            }
        } else {
            echo json_encode(["error" => "Erro na execução da consulta."]);
        }
        exit;
    }

    if ($carregarDados == 'nao') {
        $id = $_REQUEST['id'] ?? '';
        $item = $_REQUEST['item'] ?? '';
        $desc_item = $_REQUEST['desc_item'] ?? '';
        $descricao = $_REQUEST['descricao'] ?? '';

        if (!empty($id)) {

            $sql = "UPDATE lista_castanheira SET item='$item', desc_item='$desc_item', descricao='$descricao' WHERE id = '$id'";
            $resultado = mysqli_query($con, $sql);

            if ($resultado) {
                echo json_encode(["status" => "true", "message" => "Setor atualizado com sucesso."]);
            } else {
                echo json_encode(["status" => "false", "message" => "Erro ao atualizar setor."]);
            }
            exit;
        } else {

            $sql = "INSERT INTO lista_castanheira (item, desc_item, descricao) VALUES ('$item', '$desc_item', '$descricao')";
            $resultado = mysqli_query($con, $sql);

            if ($resultado) {
                echo json_encode(["status" => "true", "message" => "Setor adicionado com sucesso."]);
            } else {
                echo json_encode(["status" => "false", "message" => "Erro ao adicionar setor."]);
            }
            exit;
        }
    }

    if ($carregarDados == 'linha') {

        $id = $_POST['id'];

        $sql = "SELECT * FROM lista_castanheira WHERE id = '$id' ";
        $resultado = mysqli_query($con, $sql);

        $dados = [];

        if ($resultado) {

            $row = mysqli_fetch_assoc($resultado);

            $dados[] = [
                'id' => $row['id'],
                'item' => $row['item'],
                'desc_item' => $row['desc_item'],
                'descricao' => $row['descricao']
            ];


            if (empty($dados)) {
                echo json_encode(["error" => "Consulta não retornou dados."]);
            } else {
                echo json_encode(["dados" => $dados]);
            }
        } else {
            echo json_encode(["error" => "Erro na execução da consulta."]);
        }
        exit;
    }

    if ($carregarDados == 'delete') {
        $id = $_POST['id'];

        $sql = "DELETE FROM lista_castanheira WHERE id = '$id' ";
        $resultado = mysqli_query($con, $sql);

        if ($resultado) {
            echo json_encode(["dados" => "Setor deletado com sucesso."]);
        } else {
            echo json_encode(["error" => "Erro na execução da consulta."]);
        }
        exit;
    }
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/config/autoload.php';
require_once HOME_DIR . 'componentes/navbar.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="/includes/estilo.css" rel="stylesheet">
    <link rel="icon" href="/includes/logo.ico">
    <title>Lista Castanheira</title>
</head>

<body>

    <div class="container">
        <div class="container-fluid">
            <h1>Lista Castanheira</h1>
        </div>

        <div class="container-fluid">
            <div class="row justify-content-end">
                <div class="col-md-3 d-flex justify-content-end">
                    <button type="button" class="btn btn-dark" id="addBtn">Cadastrar Item</button>
                </div>
            </div>
            <br>
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <table width="100%" id="tabela" class="table table-striped tabela">
                        <thead>
                            <tr>
                                <th type="hidden"></th>
                                <th>Item</th>
                                <th>Resumo</th>
                                <th>Descrição</th>
                                <th>Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Dados serão preenchidos pelo DataTables -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal01" tabindex="-1" aria-labelledby="modal01" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cadastrar Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <input type="hidden" class="form-control" id="id" name="id">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="item" class="form-label">Item:</label>
                                <input type="text" class="form-control" id="item" name="item">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="desc_item" class="form-label">Resumo:</label>
                                <input type="text" class="form-control" id="desc_item" name="desc_item">
                                <span style="font-size: 12px;">100 caracteres restantes</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="descricao" class="form-label">Descrição:</label>
                            <input type="text" class="form-control" id="descricao" name="descricao">
                            <span style="font-size: 12px;">100 caracteres restantes</span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-dark" id="saveBtn">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <footer>Desenvolvido por: Douglas Marcondes.</footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.0.6/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.6/js/dataTables.bootstrap5.min.js"></script>

    <script type="text/javascript">
        let datatable;

        $('#id').val('');
        $('#item').val('');
        $('#desc_item').val('');
        $('#descricao').val('');

        $(document).ready(function() {
            document.getElementById("addBtn").addEventListener("click", function() {
                $('#modal01').modal('show');
            });

            carregarDados();
        });

        const desc_itemInput = document.getElementById('desc_item');
        const maxChars = 100;
        enforceMaxLength(desc_itemInput);

        function enforceMaxLength(input) {
            input.addEventListener('input', function() {
                if (input.value.length > maxChars) {
                    alert("Você ultrapassou o limite de 30 caracteres.");
                    input.value = input.value.substring(0, maxChars);
                }

                const remainingChars = maxChars - input.value.length;
                const charCountElement = input.nextElementSibling;
                charCountElement.textContent = `${remainingChars} caracteres restantes`;
            });
        }



        const descricaoInput = document.getElementById('descricao');
        const maxChars1 = 100;
        enforceMaxLength1(descricaoInput);

        function enforceMaxLength1(input) {
            input.addEventListener('input', function() {
                if (input.value.length > maxChars1) {
                    alert("Você ultrapassou o limite de 30 caracteres.");
                    input.value = input.value.substring(0, maxChars1);
                }

                const remainingChars = maxChars - input.value.length;
                const charCountElement = input.nextElementSibling;
                charCountElement.textContent = `${remainingChars} caracteres restantes`;
            });
        }




        async function carregarDatatable(data) {
            if (datatable) {
                datatable.clear().rows.add(data).draw();
            } else {
                datatable = $('#tabela').DataTable({
                    language: {
                        "url": "/includes/datatablesPortugues.json"
                    },
                    data: data,
                    columns: [{
                            "data": "id",
                            "visible": false
                        },
                        {
                            "data": "item"
                        },
                        {
                            "data": "desc_item",
                            "render": function(data, type, row) {
                                return '<div style="text-align: left;">' + data + '</div>';
                            }
                        },
                        {
                            "data": "descricao",
                            "render": function(data, type, row) {
                                return '<div style="text-align: left;">' + data + '</div>';
                            }
                        },

                        {
                            "data": null,
                            "defaultContent": `
                                                <div class="d-flex align-items-center">
                                                    <button class="btn btn-sm btn-outline-dark edit me-1">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            `,
                            "width": "90px"

                        }
                    ],
                    columnDefs: [{
                        targets: '_all',
                        className: 'text-center'
                    }],
                    ordering: true,
                    lengthMenu: [
                        [25, 50, 100, -1],
                        [25, 50, 100, "Todos"]
                    ],
                    paging: false,
                    info: false,
                    drawCallback: function(settings) {

                        var api = this.api();

                        $('#tabela tbody').on('click', '.edit', function(event) {

                            var data = $('#tabela').DataTable().row($(this).closest('tr')).data();
                            var id = data.id;

                            $('#modal01').modal('show');

                            $.ajax({
                                url: "castanheira.php",
                                method: 'POST',
                                data: {
                                    id: id,
                                    carregarDados: "linha"
                                },
                                success: function(response) {

                                    if (response.dados && response.dados.length > 0) {
                                        var data = response.dados[0];

                                        $('#id').val(data.id);
                                        $('#item').val(data.item);
                                        $('#desc_item').val(data.desc_item);
                                        $('#descricao').val(data.descricao);

                                        $('#modal01').modal('show');
                                    } else {
                                        console.error('Nenhum dado encontrado.');
                                        alert('Erro: Nenhum dado encontrado.');
                                    }
                                },

                                error: function(xhr, status, error) {
                                    console.error('Erro no AJAX:', error);
                                }
                            });
                        });

                        $('#tabela tbody').on('click', '.delete', function(event) {
                            var data = $('#tabela').DataTable().row($(this).closest('tr')).data();
                            var id = data.id;

                            if (confirm('Você tem certeza que deseja deletar este item?')) {
                                $.ajax({
                                    url: "castanheira.php",
                                    method: 'POST',
                                    data: {
                                        id: id,
                                        carregarDados: "delete"
                                    },
                                    success: function(response) {

                                        console.log(response);

                                        if (response.dados) {
                                            window.location.reload();
                                        } else {
                                            console.error('Erro na resposta:', response.error);
                                            alert('Erro: ' + response.error);
                                        }
                                    },
                                    error: function(xhr, status, error) {
                                        console.error('Erro no AJAX:', error);
                                        alert('Erro na requisição: ' + error);
                                    }
                                });
                            }
                        });
                    }
                });
            }
        }

        function carregarDados() {
            $.ajax({
                url: 'castanheira.php',
                type: "POST",
                data: {
                    carregarDados: "sim"
                },
                dataType: 'json',
                success: function(response) {
                    console.log('Resposta do servidor:', response);
                    if (response.dados && Array.isArray(response.dados)) {
                        carregarDatatable(response.dados);
                    } else if (response.error) {
                        console.error('Erro no servidor:', response.error);
                    } else {
                        console.error('Dados inválidos recebidos do servidor:', response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erro na requisição:', error);
                }
            });
        }

        $('#modal01').on('click', '#saveBtn', function() {
            var id = $('#id').val();
            var item = $('#item').val();
            var desc_item = $('#desc_item').val();
            var descricao = $('#descricao').val();

            $.ajax({
                url: 'castanheira.php',
                type: 'POST',
                data: {
                    id: id,
                    item: item,
                    desc_item: desc_item,
                    descricao: descricao,
                    carregarDados: 'nao'
                },
                success: function(response) {
                    console.log(response);
                    $('#modal01').modal('hide');

                    if (response.status === 'true') {
                        window.location.reload();
                    } else {
                        alert(response.message);
                    }

                    $('#id').val('');
                    $('#item').val('');
                    $('#desc_item').val('');
                    $('#descricao').val('');
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    alert('Erro na requisição: ' + error);
                }
            });
        });
    </script>
</body>

</html>