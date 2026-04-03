#!/bin/bash

# Script para inicializar ambiente Docker da aplicação
# Uso: ./docker-init.sh

set -e

echo "🚀 Inicializando ambiente Docker - gestao_ambiental"
echo ""

# 1. Verificar docker
if ! command -v docker &> /dev/null; then
    echo "❌ Docker não está instalado. Instale em docker.com"
    exit 1
fi

echo "✅ Docker encontrado"

# 2. Copiar .env
if [ ! -f ".env" ]; then
    echo "📝 Criando .env a partir de .env.example..."
    cp .env.example .env
    echo "✅ Arquivo .env criado"
    echo "⚠️  IMPORTANTE: Edite .env com suas credenciais!"
else
    echo "✅ Arquivo .env já existe"
fi

# 3. Build images
echo ""
echo "🔨 Construindo imagens Docker..."
docker-compose build

# 4. Start containers
echo ""
echo "▶️  Iniciando containers..."
docker-compose up -d

# 5. Esperar MySQL estar pronto
echo ""
echo "⏳ Aguardando MySQL iniciar..."
sleep 10

# 6. Criar banco de dados
echo "📊 Criando banco de dados..."
docker-compose exec -T mysql mysql -uroot -proot_password -e "CREATE DATABASE IF NOT EXISTS gestam16_gestao_ambiental;"

# 7. Importar schema (se existir)
if [ -f "tabelas_gestao_ambiental.sql" ]; then
    echo "📥 Importando schema..."
    docker-compose exec -T mysql mysql -uroot -proot_password gestam16_gestao_ambiental < tabelas_gestao_ambiental.sql
    echo "✅ Schema importado"
fi

# 8. Verificar status
echo ""
echo "✅ Setup concluído!"
echo ""
echo "📍 Endpoints:"
echo "   - Aplicação: http://localhost"
echo "   - phpMyAdmin: http://localhost:8080"
echo "   - Root: root / Senha: root_password"
echo ""
echo "📝 Logs:"
echo "   docker-compose logs -f"
echo ""
echo "🛑 Parar containers:"
echo "   docker-compose down"
