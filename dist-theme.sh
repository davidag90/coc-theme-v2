#!/bin/bash

# Configuración
THEME_NAME=$(basename "$(pwd)")
OUTPUT_DIR="./dist"
TIMESTAMP=$(date +%Y%m%d-%H%M%S)
ZIP_NAME="${THEME_NAME}-${TIMESTAMP}.zip"

# Crear directorio de salida si no existe
mkdir -p "$OUTPUT_DIR"

echo "🚀 Creando ZIP de distribución usando Git Archive"
echo "📦 Tema: $THEME_NAME"
echo "📄 Archivo: $OUTPUT_DIR/$ZIP_NAME"

# Verificar que estamos en un repositorio Git
if [ ! -d ".git" ]; then
    echo "❌ Error: No se encontró repositorio Git en el directorio actual"
    exit 1
fi

# Verificar que no hay cambios sin commitear (opcional)
if [ -n "$(git status --porcelain)" ]; then
    echo "⚠️  Advertencia: Hay cambios sin commitear. El ZIP solo incluirá archivos commiteados."
    read -p "¿Continuar? (y/N): " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        exit 1
    fi
fi

# Crear ZIP usando git archive
git archive --format=zip --output="$OUTPUT_DIR/$ZIP_NAME" HEAD

if [ $? -eq 0 ]; then
    echo "✅ ZIP creado exitosamente: $OUTPUT_DIR/$ZIP_NAME"
    echo "📊 Tamaño del archivo: $(du -h "$OUTPUT_DIR/$ZIP_NAME" | cut -f1)"
    
    # Mostrar archivos incluidos
    echo ""
    echo "📋 Archivos incluidos en el ZIP:"
    unzip -l "$OUTPUT_DIR/$ZIP_NAME" | head -15
    
    echo ""
    echo "🎉 ¡Listo para subir a WordPress!"
else
    echo "❌ Error al crear el ZIP"
    exit 1
fi