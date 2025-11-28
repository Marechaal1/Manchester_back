#!/bin/bash

# Script para gerar certificados SSL self-signed para desenvolvimento

SSL_DIR="docker/ssl"
CERT_FILE="$SSL_DIR/cert.pem"
KEY_FILE="$SSL_DIR/key.pem"

echo "🔐 Gerando certificados SSL self-signed para desenvolvimento..."

# Criar diretório se não existir
mkdir -p "$SSL_DIR"

# Gerar certificado self-signed válido por 365 dias
openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout "$KEY_FILE" \
    -out "$CERT_FILE" \
    -subj "/C=BR/ST=Estado/L=Cidade/O=ManchesterTriage/CN=localhost" \
    -addext "subjectAltName=DNS:localhost,DNS:*.localhost,IP:127.0.0.1,IP:0.0.0.0"

if [ $? -eq 0 ]; then
    echo "✅ Certificados SSL gerados com sucesso!"
    echo "   Certificado: $CERT_FILE"
    echo "   Chave: $KEY_FILE"
    echo ""
    echo "⚠️  ATENÇÃO: Estes são certificados self-signed para desenvolvimento."
    echo "   Seu navegador mostrará um aviso de segurança. Isso é normal."
    echo "   Para produção, use certificados de uma autoridade certificadora (Let's Encrypt, etc.)"
else
    echo "❌ Erro ao gerar certificados SSL"
    exit 1
fi



