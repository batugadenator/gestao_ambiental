 <div class="modal fade" id="registerUserModal" tabindex="-1" aria-labelledby="registerUserModalLabel" aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="registerUserModalLabel">Registrar Novo Usuário</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <form id="registerUserForm" method="post" action="/login/registrar.php">
                     <div class="mb-3">
                         <label for="username" class="form-label">Nome de Usuário</label>
                         <input type="text" class="form-control" id="username" name="username" required>
                     </div>
                     <div class="mb-3">
                         <label for="email" class="form-label">Email</label>
                         <input type="email" class="form-control" id="email" name="email" required>
                     </div>
                     <div class="mb-3">
                         <label for="password" class="form-label">Senha</label>
                         <input type="password" class="form-control" id="password" name="password" required>
                     </div>
                     <div class="mb-3">
                         <label for="admin" class="form-label">Administrador</label>
                         <select name="admin" id="admin" class="form-select">
                             <option selected disabled>Selecione uma opção...</option>
                             <option value="S">Sim</option>
                             <option value="N">Não</option>
                         </select>
                     </div>
                     <button type="submit" class="btn btn-primary">Registrar</button>
                 </form>
             </div>
         </div>
     </div>
 </div>
 <div id="alertPlaceholder"></div>

 <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
     <div class="container">
         <img class="logo" src="/includes/logo.ico" alt="Logo" style="max-height: 25px;">
         <div class="collapse navbar-collapse" id="navbarSupportedContent">
             <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                 <li class="nav-item">
                     <a class="nav-link active" aria-current="page" href="/index.php">Início</a>
                 </li>
                 <li class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                         Fotos
                     </a>
                     <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                         <li><a class="dropdown-item" href="/fotos/visualizar_fotos.php">Relatório</a></li>
                         <li><a class="dropdown-item" href="/fotos/registrar_fotos.php">Registrar</a></li>
                     </ul>
                 </li>
                 <li class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                         Cadastros
                     </a>
                     <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                         <li><a class="dropdown-item" href="/cadastros/setores.php">Setores</a></li>
                         <li><a class="dropdown-item" href="/cadastros/subsecao.php">Subseção</a></li>
                         <li><a class="dropdown-item" href="/cadastros/local_ocorrencia.php">Local/Ocorrência</a></li>
                     </ul>
                 </li>
                 <li class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                         Listas
                     </a>
                     <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                         <li><a class="dropdown-item" href="/cadastros/listas/castanheira.php">Castanheira</a></li>
                         <li><a class="dropdown-item" href="/cadastros/listas/imbauba.php">Imbaúba</a></li>
                         <li><a class="dropdown-item" href="/cadastros/listas/paubrasil.php">Pau Brasil</a></li>
                     </ul>
                 </li>

                 <li class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle text-white fw-bold" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                         <strong>Opções</strong>
                     </a>
                     <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                         <li><a class="dropdown-item" href="#" role="button" data-bs-toggle="modal" data-bs-target="#registerUserModal">Registrar usuário</a></li>
                         <li><a class="dropdown-item" href="#" role="button" onclick="confirmBackup()">Backup</a></li>
                     </ul>
                 </li>
                 <li class="nav-item">
                     <a class="nav-link active" aria-current="page" href="/login/logout.php">Logout</a>
                 </li>
                 <!-- <li class="nav-item">
                     <a class="nav-link active" aria-current="page" href="/info.php">Info</a>
                 </li> -->
             </ul>
         </div>
     </div>
 </nav>

 <script>
     function confirmBackup() {
         if (confirm("Tem certeza de que deseja fazer um backup do banco de dados?")) {
             window.location.href = "/backup/backup.php";
         }
     }

     document.getElementById("registerUserForm").addEventListener("submit", function(event) {
         event.preventDefault(); // Impede o envio padrão do formulário

         const form = event.target;
         const formData = new FormData(form);

         fetch("/login/registrar.php", {
                 method: "POST",
                 body: formData
             })
             .then(response => response.json())
             .then(data => {
                 if (data.status === "success") {
                     // Fecha o modal
                     const modal = bootstrap.Modal.getInstance(document.getElementById("registerUserModal"));
                     modal.hide();

                     // Exibe notificação de sucesso
                     showAlert("success", data.message);
                 } else {
                     // Exibe notificação de erro
                     showAlert("danger", data.message);
                 }
             })
             .catch(error => {
                 showAlert("danger", "Erro ao processar a solicitação.");
                 console.error("Erro:", error);
             });
     });

     function showAlert(type, message) {
         const alertPlaceholder = document.getElementById("alertPlaceholder");
         alertPlaceholder.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
     }
 </script>