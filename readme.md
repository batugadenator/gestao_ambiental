# Configuração do Virtual Host para o Projeto

Para configurar o acesso ao projeto utilizando o endereço `gestaoambiental.com.br`, siga os passos abaixo:

### 1. Configurar o Virtual Host no Apache

Abra o arquivo de configuração de Virtual Hosts do Apache em:
```
E:\xampp\apache\conf\extra\httpd-vhosts.conf
```

Adicione o seguinte código ao final do arquivo:

```apache
<VirtualHost *:80>
    DocumentRoot "D:/fotos_gestao_ambiental"
    ServerName gestaoambiental.com.br
    <Directory "D:/fotos_gestao_ambiental">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Esse código define o caminho do projeto como o diretório raiz para o endereço gestaoambiental.com.br.

### 2. Atualizar o Arquivo hosts do Windows

Para que o Windows reconheça o endereço gestaoambiental.com.br como um endereço local, abra o arquivo hosts, localizado em:

```
C:\Windows\System32\drivers\etc\hosts
```

Adicione a linha abaixo ao final do arquivo:
```
127.0.0.1 gestaoambiental.com.br
```
Essa configuração direciona as requisições para gestaoambiental.com.br ao servidor local (localhost).

### 3. Reiniciar o Servidor Apache

Após realizar as alterações, reinicie o Apache no painel de controle do XAMPP para aplicar as configurações.

### 4. Acessar o Projeto

Com as configurações acima, você poderá acessar o projeto utilizando o seguinte endereço no navegador:

```
http://gestaoambiental.com.br
```

Observação: Para que as URLs internas funcionem corretamente, o projeto pode precisar de um arquivo .htaccess para configurar redirecionamentos, especialmente se ele utilizar URLs amigáveis.

---

Esse arquivo contém todas as instruções necessárias para configurar o Virtual Host e acessar o projeto pelo endereço `gestaoambiental.com.br`.


### 5. Alterar senha do phpMyAdmin

1. Alterar a senha do usuário root no site normalmente.

2. Após, procurar pelo arquivo "config.inc.php" e alterar:

$cfg['Servers'][$i]['user'] = 'root';
$cfg['Servers'][$i]['password'] = 'resende123';
