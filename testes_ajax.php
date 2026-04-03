<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Teste de API AJAX - gestao_ambiental</title>
    <style>
        .test-section {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .test-result {
            margin-top: 10px;
            padding: 10px;
            border-radius: 3px;
            font-family: monospace;
            font-size: 12px;
            max-height: 300px;
            overflow-y: auto;
        }

        .result-success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }

        .result-error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        .result-loading {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
        }

        button {
            margin-right: 5px;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container mt-5 mb-5">
        <h1>Testes de Endpoints AJAX</h1>
        <p class="text-muted">Utilize esta página para testar todos os endpoints AJAX da aplicação.</p>
        <hr>

        <!-- ============ TESTE SETORES ============ -->
        <div class="test-section">
            <h3>📋 Setores</h3>

            <div class="row">
                <div class="col-md-6">
                    <button class="btn btn-primary btn-sm" onclick="testSetoresLoad()">GET - Carregar Todos</button>
                    <button class="btn btn-info btn-sm" onclick="testSetorById()">GET - Por ID</button>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-success btn-sm" onclick="testSetorInsert()">POST - Novo</button>
                    <button class="btn btn-warning btn-sm" onclick="testSetorUpdate()">PUT - Atualizar</button>
                    <button class="btn btn-danger btn-sm" onclick="testSetorDelete()">DELETE</button>
                </div>
            </div>

            <label for="setorNome" class="form-label mt-3">Nome do Setor:</label>
            <input type="text" id="setorNome" class="form-control" placeholder="ex: Pesquisa">

            <label for="setorId" class="form-label mt-2">ID do Setor:</label>
            <input type="number" id="setorId" class="form-control" placeholder="ex: 1">

            <div id="setoresResult" class="test-result"></div>
        </div>

        <!-- ============ TESTE SUBSECOES ============ -->
        <div class="test-section">
            <h3>🔀 Subsecções</h3>

            <div class="row">
                <div class="col-md-6">
                    <button class="btn btn-primary btn-sm" onclick="testSubsecoesLoad()">GET - Carregar Todos</button>
                    <button class="btn btn-info btn-sm" onclick="testSubsecaoById()">GET - Por ID</button>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-success btn-sm" onclick="testSubsecaoInsert()">POST - Nova</button>
                    <button class="btn btn-warning btn-sm" onclick="testSubsecaoUpdate()">PUT - Atualizar</button>
                    <button class="btn btn-danger btn-sm" onclick="testSubsecaoDelete()">DELETE</button>
                </div>
            </div>

            <label for="subsecaoNome" class="form-label mt-3">Nome:</label>
            <input type="text" id="subsecaoNome" class="form-control" placeholder="ex: Subsecção A">

            <label for="setorSuperior" class="form-label mt-2">Setor Superior (ID):</label>
            <input type="number" id="setorSuperior" class="form-control" placeholder="ex: 1">

            <label for="subsecaoId" class="form-label mt-2">ID da Subsecção:</label>
            <input type="number" id="subsecaoId" class="form-control" placeholder="ex: 1">

            <div id="subsecoesResult" class="test-result"></div>
        </div>

        <!-- ============ TESTE LOCAL/OCORRENCIA ============ -->
        <div class="test-section">
            <h3>📍 Local e Ocorrência</h3>

            <div class="row">
                <div class="col-md-6">
                    <button class="btn btn-primary btn-sm" onclick="testLocalLoad()">GET - Locais</button>
                    <button class="btn btn-success btn-sm" onclick="testLocalInsert()">POST - Novo Local</button>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-primary btn-sm" onclick="testOcorrenciaLoad()">GET - Ocorrências</button>
                    <button class="btn btn-success btn-sm" onclick="testOcorrenciaInsert()">POST - Nova Ocorrência</button>
                </div>
            </div>

            <label for="localNome" class="form-label mt-3">Local:</label>
            <input type="text" id="localNome" class="form-control" placeholder="ex: Campo Norte">

            <label for="ocorrenciaNome" class="form-label mt-2">Ocorrência:</label>
            <input type="text" id="ocorrenciaNome" class="form-control" placeholder="ex: Desmatamento">

            <div id="localOcorrenciaResult" class="test-result"></div>
        </div>

        <!-- ============ TESTE Performance ============ -->
        <div class="test-section">
            <h3>⚡ Performance e Stress Test</h3>

            <div class="row">
                <div class="col-md-4">
                    <button class="btn btn-secondary btn-sm" onclick="testConcurrentRequests(5)">5 Requisições Simultâneas</button>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-secondary btn-sm" onclick="testConcurrentRequests(20)">20 Requisições Simultâneas</button>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-secondary btn-sm" onclick="testConcurrentRequests(50)">50 Requisições Simultâneas</button>
                </div>
            </div>

            <div id="performanceResult" class="test-result"></div>
        </div>

        <!-- ============ RESUMO TESTES ============ -->
        <div class="test-section bg-info text-white">
            <h4>📊 Resumo de Testes</h4>
            <p id="testSummary">Nenhum teste executado ainda.</p>
        </div>
    </div>

    <script>
        let testCount = 0;
        let testsPassed = 0;
        let testsFailed = 0;

        // Função auxiliar para fazer requisições AJAX
        async function makeRequest(endpoint, method = 'GET', data = null) {
            const url = `/cadastros/${endpoint}`;

            // Adicionar token CSRF se for POST/PUT/DELETE
            if (data && (method === 'POST' || method === 'PUT' || method === 'DELETE')) {
                // Obter token CSRF do DOM ou gerar um valor simulado
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || 'test-token';
                data.csrf_token = csrfToken;
            }

            const options = {
                method: method,
                headers: {
                    'Content-Type': method === 'GET' ? 'application/x-www-form-urlencoded' : 'application/x-www-form-urlencoded'
                }
            };

            if (data && method !== 'GET') {
                options.body = new URLSearchParams(data);
            } else if (data && method === 'GET') {
                const params = new URLSearchParams(data);
                return fetch(`${url}?${params}`, options);
            }

            return fetch(url, options);
        }

        // Função auxiliar para exibir resultado
        function displayResult(elementId, result, isSuccess) {
            const resultDiv = document.getElementById(elementId);
            resultDiv.innerHTML = `<pre>${JSON.stringify(result, null, 2)}</pre>`;
            resultDiv.className = `test-result ${isSuccess ? 'result-success' : 'result-error'}`;

            // Atualizar resumo
            testCount++;
            isSuccess ? testsPassed++ : testsFailed++;
            updateSummary();
        }

        function updateSummary() {
            const summary = `Total: ${testCount} | ✅ Passou: ${testsPassed} | ❌ Falhou: ${testsFailed}`;
            document.getElementById('testSummary').innerText = summary;
        }

        // ============ TESTES SETORES ============
        async function testSetoresLoad() {
            const resultDiv = document.getElementById('setoresResult');
            resultDiv.innerHTML = '<p class="result-loading">Carregando...</p>';
            try {
                const response = await makeRequest('setores.php', 'POST', { carregarDados: 'sim' });
                const result = await response.json();
                displayResult('setoresResult', result, result.dados !== undefined);
            } catch (error) {
                displayResult('setoresResult', { erro: error.message }, false);
            }
        }

        async function testSetorById() {
            const id = document.getElementById('setorId').value;
            if (!id) { alert('Digite um ID'); return; }
            const resultDiv = document.getElementById('setoresResult');
            resultDiv.innerHTML = '<p class="result-loading">Carregando...</p>';
            try {
                const response = await makeRequest('setores.php', 'POST', { carregarDados: 'linha', id });
                const result = await response.json();
                displayResult('setoresResult', result, result.dados !== undefined);
            } catch (error) {
                displayResult('setoresResult', { erro: error.message }, false);
            }
        }

        async function testSetorInsert() {
            const nome = document.getElementById('setorNome').value;
            if (!nome) { alert('Digite um nome'); return; }
            const resultDiv = document.getElementById('setoresResult');
            resultDiv.innerHTML = '<p class="result-loading">Enviando...</p>';
            try {
                const response = await makeRequest('setores.php', 'POST', { carregarDados: 'nao', setor: nome });
                const result = await response.json();
                displayResult('setoresResult', result, result.status === 'true');
            } catch (error) {
                displayResult('setoresResult', { erro: error.message }, false);
            }
        }

        async function testSetorUpdate() {
            const id = document.getElementById('setorId').value;
            const nome = document.getElementById('setorNome').value;
            if (!id || !nome) { alert('Digite ID e nome'); return; }
            const resultDiv = document.getElementById('setoresResult');
            resultDiv.innerHTML = '<p class="result-loading">Atualizando...</p>';
            try {
                const response = await makeRequest('setores.php', 'POST', { carregarDados: 'nao', id, setor: nome });
                const result = await response.json();
                displayResult('setoresResult', result, result.status === 'true');
            } catch (error) {
                displayResult('setoresResult', { erro: error.message }, false);
            }
        }

        async function testSetorDelete() {
            const id = document.getElementById('setorId').value;
            if (!id) { alert('Digite um ID'); return; }
            const resultDiv = document.getElementById('setoresResult');
            resultDiv.innerHTML = '<p class="result-loading">Deletando...</p>';
            try {
                const response = await makeRequest('setores.php', 'POST', { carregarDados: 'delete', id });
                const result = await response.json();
                displayResult('setoresResult', result, result.dados !== undefined);
            } catch (error) {
                displayResult('setoresResult', { erro: error.message }, false);
            }
        }

        // ============ TESTES SUBSECOES ============
        async function testSubsecoesLoad() {
            const resultDiv = document.getElementById('subsecoesResult');
            resultDiv.innerHTML = '<p class="result-loading">Carregando...</p>';
            try {
                const response = await makeRequest('subsecao.php', 'POST', { carregarDados: 'sim' });
                const result = await response.json();
                displayResult('subsecoesResult', result, result.dados !== undefined);
            } catch (error) {
                displayResult('subsecoesResult', { erro: error.message }, false);
            }
        }

        async function testSubsecaoById() {
            const id = document.getElementById('subsecaoId').value;
            if (!id) { alert('Digite um ID'); return; }
            const resultDiv = document.getElementById('subsecoesResult');
            resultDiv.innerHTML = '<p class="result-loading">Carregando...</p>';
            try {
                const response = await makeRequest('subsecao.php', 'POST', { carregarDados: 'linha', id });
                const result = await response.json();
                displayResult('subsecoesResult', result, result.dados !== undefined);
            } catch (error) {
                displayResult('subsecoesResult', { erro: error.message }, false);
            }
        }

        async function testSubsecaoInsert() {
            const subsecao = document.getElementById('subsecaoNome').value;
            const setor_superior = document.getElementById('setorSuperior').value;
            if (!subsecao || !setor_superior) { alert('Preencha todos os campos'); return; }
            const resultDiv = document.getElementById('subsecoesResult');
            resultDiv.innerHTML = '<p class="result-loading">Enviando...</p>';
            try {
                const response = await makeRequest('subsecao.php', 'POST', { carregarDados: 'nao', subsecao, setor_superior });
                const result = await response.json();
                displayResult('subsecoesResult', result, result.status === 'true');
            } catch (error) {
                displayResult('subsecoesResult', { erro: error.message }, false);
            }
        }

        async function testSubsecaoUpdate() {
            const id = document.getElementById('subsecaoId').value;
            const subsecao = document.getElementById('subsecaoNome').value;
            const setor_superior = document.getElementById('setorSuperior').value;
            if (!id || !subsecao || !setor_superior) { alert('Preencha todos os campos'); return; }
            const resultDiv = document.getElementById('subsecoesResult');
            resultDiv.innerHTML = '<p class="result-loading">Atualizando...</p>';
            try {
                const response = await makeRequest('subsecao.php', 'POST', { carregarDados: 'nao', id, subsecao, setor_superior });
                const result = await response.json();
                displayResult('subsecoesResult', result, result.status === 'true');
            } catch (error) {
                displayResult('subsecoesResult', { erro: error.message }, false);
            }
        }

        async function testSubsecaoDelete() {
            const id = document.getElementById('subsecaoId').value;
            if (!id) { alert('Digite um ID'); return; }
            const resultDiv = document.getElementById('subsecoesResult');
            resultDiv.innerHTML = '<p class="result-loading">Deletando...</p>';
            try {
                const response = await makeRequest('subsecao.php', 'POST', { carregarDados: 'delete', id });
                const result = await response.json();
                displayResult('subsecoesResult', result, result.dados !== undefined);
            } catch (error) {
                displayResult('subsecoesResult', { erro: error.message }, false);
            }
        }

        // ============ TESTES LOCAL E OCORRENCIA ============
        async function testLocalLoad() {
            const resultDiv = document.getElementById('localOcorrenciaResult');
            resultDiv.innerHTML = '<p class="result-loading">Carregando...</p>';
            try {
                const response = await makeRequest('local_ocorrencia.php', 'POST', { qual: 'local', carregarDados: 'sim' });
                const result = await response.json();
                displayResult('localOcorrenciaResult', result, result.dados !== undefined);
            } catch (error) {
                displayResult('localOcorrenciaResult', { erro: error.message }, false);
            }
        }

        async function testLocalInsert() {
            const local = document.getElementById('localNome').value;
            if (!local) { alert('Digite um local'); return; }
            const resultDiv = document.getElementById('localOcorrenciaResult');
            resultDiv.innerHTML = '<p class="result-loading">Enviando...</p>';
            try {
                const response = await makeRequest('local_ocorrencia.php', 'POST', { qual: 'local', carregarDados: 'nao', local });
                const result = await response.json();
                displayResult('localOcorrenciaResult', result, result.status === 'true');
            } catch (error) {
                displayResult('localOcorrenciaResult', { erro: error.message }, false);
            }
        }

        async function testOcorrenciaLoad() {
            const resultDiv = document.getElementById('localOcorrenciaResult');
            resultDiv.innerHTML = '<p class="result-loading">Carregando...</p>';
            try {
                const response = await makeRequest('local_ocorrencia.php', 'POST', { qual: 'ocorrencia', carregarDados: 'sim' });
                const result = await response.json();
                displayResult('localOcorrenciaResult', result, result.dados !== undefined);
            } catch (error) {
                displayResult('localOcorrenciaResult', { erro: error.message }, false);
            }
        }

        async function testOcorrenciaInsert() {
            const ocorrencia = document.getElementById('ocorrenciaNome').value;
            if (!ocorrencia) { alert('Digite uma ocorrência'); return; }
            const resultDiv = document.getElementById('localOcorrenciaResult');
            resultDiv.innerHTML = '<p class="result-loading">Enviando...</p>';
            try {
                const response = await makeRequest('local_ocorrencia.php', 'POST', { qual: 'ocorrencia', carregarDados: 'nao', ocorrencia });
                const result = await response.json();
                displayResult('localOcorrenciaResult', result, result.status === 'true');
            } catch (error) {
                displayResult('localOcorrenciaResult', { erro: error.message }, false);
            }
        }

        // ============ STRESS TEST ============
        async function testConcurrentRequests(count) {
            const resultDiv = document.getElementById('performanceResult');
            resultDiv.innerHTML = `<p class="result-loading">Executando ${count} requisições simultâneas...</p>`;

            const startTime = performance.now();
            const promises = [];

            for (let i = 0; i < count; i++) {
                promises.push(
                    makeRequest('setores.php', 'POST', { carregarDados: 'sim' })
                        .then(r => r.json())
                );
            }

            try {
                const results = await Promise.all(promises);
                const endTime = performance.now();
                const duration = (endTime - startTime).toFixed(2);
                const successful = results.filter(r => r.dados !== undefined).length;

                const summary = {
                    total: count,
                    successful: successful,
                    failed: count - successful,
                    duration_ms: duration,
                    avg_time_ms: (duration / count).toFixed(2)
                };

                displayResult('performanceResult', summary, successful === count);
            } catch (error) {
                displayResult('performanceResult', { erro: error.message }, false);
            }
        }

        // Mensagem de boas-vindas
        console.log('%c🧪 Página de Testes AJAX Carregada', 'font-size: 16px; color: green; font-weight: bold;');
        console.log('Use os botões acima para testar os endpoints');
    </script>
</body>

</html>
