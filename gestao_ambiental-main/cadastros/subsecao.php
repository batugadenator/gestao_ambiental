<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    require_once $_SERVER['DOCUMENT_ROOT'] . '/config/conexao.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/config/csrf.php';
    
    header('Content-Type: application/json');
    
    // Validar CSRF token
    if (!validatePostWithCSRF()) {
        exit;
    }

    $db = Database::getInstance();
    $con = $db->getConnection();
    
    $carregarDados = $_POST['carregarDados'] ?? '';

    if ($carregarDados == 'sim') {
        // SELECT com prepared statement
        $sql = "SELECT subsecoes.*, setores.setor FROM subsecoes INNER JOIN setores ON setores.id = subsecoes.setor_superior";
        $stmt = $db->query($sql);

        if ($stmt) {
            $dados = $db->fetchAll($stmt);
            
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

        if (empty($_POST['setor_superior'])) {
            echo json_encode(["status" => "false", "message" => "Selecione um setor superior para esta subseção."]);
            exit;
        }

        $id = $_POST['id'] ?? '';
        $subsecao = $_POST['subsecao'] ?? '';
        $setor_superior = $_POST['setor_superior'] ?? '';

        if (!empty(trim($subsecao)) && !empty(trim($setor_superior))) {
            
            if (!empty($id)) {
                // UPDATE com prepared statement
                $sql = "UPDATE subsecoes SET subsecao = ?, setor_superior = ? WHERE id = ?";
                $stmt = $db->query($sql, [$subsecao, $setor_superior, $id], 'ssi');

                if ($stmt && $db->affectedRows() >= 0) {
                    echo json_encode(["status" => "true", "message" => "Subseção atualizada com sucesso."]);
                } else {
                    echo json_encode(["status" => "false", "message" => "Erro ao atualizar subseção."]);
                }
                exit;
            } else {
                // INSERT com prepared statement
                $sql = "INSERT INTO subsecoes (subsecao, setor_superior) VALUES (?, ?)";
                $stmt = $db->query($sql, [$subsecao, $setor_superior], 'ss');

                if ($stmt && $db->affectedRows() > 0) {
                    echo json_encode(["status" => "true", "message" => "Subseção adicionada com sucesso."]);
                } else {
                    echo json_encode(["status" => "false", "message" => "Erro ao adicionar subseção."]);
                }
                exit;
            }
        } else {
            echo json_encode(["status" => "false", "message" => "Subsecção e setor superior são obrigatórios."]);
            exit;
        }
    }

    if ($carregarDados == 'linha') {

        $id = $_POST['id'] ?? '';

        if (empty($id)) {
            echo json_encode(["error" => "ID não fornecido."]);
            exit;
        }

        // SELECT com prepared statement
        $sql = "SELECT * FROM subsecoes WHERE id = ?";
        $stmt = $db->query($sql, [$id], 'i');

        if ($stmt) {
            $row = $db->fetchOne($stmt);

            if ($row) {
                $dados = [
                    [
                        'id' => $row['id'],
                        'subsecao' => $row['subsecao'],
                        'setor' => $row['setor_superior']
                    ]
                ];
                echo json_encode(["dados" => $dados]);
            } else {
                echo json_encode(["error" => "Consulta não retornou dados."]);
            }
        } else {
            echo json_encode(["error" => "Erro na execução da consulta."]);
        }
        exit;
    }

    if ($carregarDados == 'delete') {
        $id = $_POST['id'] ?? '';

        if (empty($id)) {
            echo json_encode(["error" => "ID não fornecido."]);
            exit;
        }

        // DELETE com prepared statement
        $sql = "DELETE FROM subsecoes WHERE id = ?";
        $stmt = $db->query($sql, [$id], 'i');

        if ($stmt && $db->affectedRows() > 0) {
            echo json_encode(["dados" => "Setor deletado com sucesso."]);
        } else {
            echo json_encode(["error" => "Erro ao deletar setor."]);
        }
        exit;
    }
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/conexao.php';
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
    <title>Subseção</title>

</head>


<body>

    <div class="container">
        <div class="container-fluid">
            <h1>Subseção</h1>
        </div>

        <div class="container-fluid">
            <div class="row justify-content-end">
                <div class="col-md-3 d-flex justify-content-end">
                    <button type="button" class="btn btn-dark" id="addBtnSubSecao">Cadastrar Subseção</button>
                </div>
            </div>
            <br>
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <table width="100%" id="tabela_subsecao" class="table table-striped tabela">
                        <thead>
                            <tr>
                                <th>Subseção</th>
                                <th>Setor Superior</th>
                                <th>Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Seus dados do data table aqui -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div><!-- Modal -->
    <div class="modal fade" id="modal01" tabindex="-1" aria-labelledby="modal01" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cadastrar Subseção</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <!-- Formulário dentro do modal -->
                    <form>
                        <input type="hidden" class="form-control" id="id" name="id">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="subsecao" class="form-label"><strong>Subseção:</strong></label>
                                <input type="text" class="form-control" id="subsecao" name="subsecao" value="">
                                <span style="font-size: 12px;">30 caracteres restantes</span>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="setor_superior" class="form-label"><strong>Setor Superior:</strong></label>
                                    <select class="form-select ml-2" id="setor_superior" name="setor_superior" required>
                                        <option value="" disabled selected>Selecione um setor superior...</option>
                                        <?php
                                        $con = connect_local_mysqli('gestao_ambiental');
                                        $sql = "SELECT * FROM setores";
                                        $resultado = mysqli_query($con, $sql);
                                        while ($row = mysqli_fetch_assoc($resultado)) {
                                            echo "<option value='" . $row['id'] . "'>" . $row['setor'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
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
        $('#subsecao').val('');
        $('#setorsuperior').prop('selectedIndex', 0);

        const subsecaoInput = document.getElementById('subsecao');

        function enforceMaxLength(inputElement, maxChars, spanElement) {
            inputElement.addEventListener('input', function() {
                if (inputElement.value.length > maxChars) {
                    alert(`Você ultrapassou o limite de ${maxChars} caracteres.`);
                    inputElement.value = inputElement.value.substring(0, maxChars);
                }
                const remainingChars = maxChars - inputElement.value.length;
                spanElement.textContent = `${remainingChars} caracteres restantes`;
            });
        }

        const subsecaoCharCount = subsecaoInput.nextElementSibling;

        enforceMaxLength(subsecaoInput, 30, subsecaoCharCount);

        $(document).ready(function() {

            carregarDados();

            document.getElementById("addBtnSubSecao").addEventListener("click", function() {
                $('#modal01').modal('show');
            });
        });

        async function carregarDatatable(data) {
            if (datatable) {
                datatable.clear().rows.add(data).draw();
            } else {
                datatable = $('#tabela_subsecao').DataTable({
                    language: {
                        "url": "/includes/datatablesPortugues.json"
                    },
                    data: data,
                    columns: [{
                            "data": "subsecao"
                        },
                        {
                            "data": "setor"
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
                        [10, 25, 50, -1],
                        [10, 25, 50, "Todos"]
                    ],
                    drawCallback: function(settings) {

                        var api = this.api();

                        $('#tabela_subsecao tbody').on('click', '.edit', function(event) {

                            var data = $('#tabela_subsecao').DataTable().row($(this).closest('tr')).data();
                            var id = data.id;

                            $('#modal01').modal('show');

                            $.ajax({
                                url: "subsecao.php",
                                method: 'POST',
                                data: {
                                    id: id,
                                    carregarDados: "linha"
                                },
                                success: function(response) {

                                    if (response.dados && response.dados.length > 0) {
                                        var data = response.dados[0];

                                        $('#id').val(data.id);
                                        $('#subsecao').val(data.subsecao);
                                        $('#setor_superior').val(data.setor);

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

                        $('#tabela_subsecao tbody').on('click', '.delete', function(event) {
                            var data = $('#tabela_subsecao').DataTable().row($(this).closest('tr')).data();
                            var id = data.id;

                            if (confirm('Você tem certeza que deseja deletar este setor?')) {
                                $.ajax({
                                    url: "subsecao.php",
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
                url: 'subsecao.php',
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
            var subsecao = $('#subsecao').val();
            var setor_superior = $('#setor_superior').val();

            $.ajax({
                url: 'subsecao.php',
                type: 'POST',
                data: {
                    id: id,
                    subsecao: subsecao,
                    setor_superior: setor_superior,
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
                    $('#subsecao').val('');
                    $('#setor_superior').val('');
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