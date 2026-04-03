@echo off
REM Script para inicializar ambiente Docker da aplicação (Windows)
REM Uso: docker-init.bat

echo.
echo 🚀 Inicializando ambiente Docker - gestao_ambiental
echo.

REM 1. Verificar docker
where docker >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo ❌ Docker não está instalado. Instale em docker.com
    exit /b 1
)

echo ✅ Docker encontrado

REM 2. Copiar .env
if not exist ".env" (
    echo 📝 Criando .env a partir de .env.example...
    copy .env.example .env
    echo ✅ Arquivo .env criado
    echo ⚠️  IMPORTANTE: Edite .env com suas credenciais!
) else (
    echo ✅ Arquivo .env já existe
)

REM 3. Build images
echo.
echo 🔨 Construindo imagens Docker...
docker-compose build

REM 4. Start containers
echo.
echo ▶️  Iniciando containers...
docker-compose up -d

REM 5. Status
echo.
echo ✅ Setup concluído!
echo.
echo 📍 Endpoints:
echo    - Aplicação: http://localhost
echo    - phpMyAdmin: http://localhost:8080
echo.
echo 📝 Para ver logs:
echo    docker-compose logs -f
echo.
echo 🛑 Para parar:
echo    docker-compose down
