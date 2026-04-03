<?php
/**
 * Exemplo de Formulário HTML com Token CSRF
 * Este arquivo demonstra como adicionar proteção CSRF em formulários
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/config/autoload.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Exemplo de Formulário com CSRF</title>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2>Exemplo: Formulário com Token CSRF</h2>
                <hr>

                <!-- =========== FORMULÁRIO HTML TRADICIONAL =========== -->
                <h4>1. Formulário HTML Tradicional</h4>
                <form method="POST" action="/cadastros/setores.php">
                    <!-- ✅ ADICIONE ESTE TOKEN EM TODOS OS FORMULÁRIOS -->
                    <?php csrfField(); ?>

                    <div class="mb-3">
                        <label for="setor" class="form-label">Setor:</label>
                        <input type="text" class="form-control" id="setor" name="setor" required>
                    </div>

                    <input type="hidden" name="carregarDados" value="nao">
                    <button type="submit" class="btn btn-primary">Salvar Setor</button>
                </form>

                <hr class="my-5">

                <!-- =========== REQUISIÇÃO AJAX =========== -->
                <h4>2. Requisição AJAX com Token CSRF</h4>
                <div class="mb-3">
                    <label for="setorAjax" class="form-label">Setor (via AJAX):</label>
                    <input type="text" class="form-control" id="setorAjax" placeholder="Digite um setor">
                </div>
                <button type="button" class="btn btn-success" onclick="enviarViaAjax()">Enviar via AJAX</button>
                <pre id="resultado"></pre>

            </div>
        </div>
    </div>

    <script>
        /**
         * Função para enviar dados via AJAX com token CSRF
         */
        function enviarViaAjax() {
            const setor = document.getElementById('setorAjax').value;

            if (!setor.trim()) {
                alert('Digite um setor');
                return;
            }

            // ✅ IMPORTANTE: Passe o token CSRF na requisição
            const csrfToken = '<?php echo getCSRFToken(); ?>';

            const formData = new FormData();
            formData.append('setor', setor);
            formData.append('carregarDados', 'nao');
            formData.append('csrf_token', csrfToken);  // ✅ ADICIONE O TOKEN

            fetch('/cadastros/setores.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('resultado').textContent = JSON.stringify(data, null, 2);
                console.log('Resposta:', data);
            })
            .catch(error => {
                console.error('Erro:', error);
                document.getElementById('resultado').textContent = 'Erro: ' + error;
            });
        }
    </script>
</body>

</html>
