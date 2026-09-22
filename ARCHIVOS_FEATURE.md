# Módulo de Archivos PDF - Guía de implementación

## Descripción
Este módulo permite a los usuarios subir archivos PDF, que son procesados automáticamente por un script de Python para extraer el texto página por página y almacenarlo en la base de datos, permitiendo luego búsquedas sobre el contenido.

## Requisitos previos

### Python
El sistema requiere Python 3.10+ con las siguientes librerías instaladas:
- `PyMuPDF` (fitz): para extracción de texto de PDFs
- `mysql-connector-python`: para conexión a MySQL

**Instalación (en el intérprete Python de Laragon):**
```bash
C:\laragon\bin\python\python-3.10\python.exe -m pip install PyMuPDF mysql-connector-python
```

### Base de datos
Las migraciones crean automáticamente las tablas:
- `archivos`: almacena metadatos del archivo
- `paginas`: almacena texto extraído de cada página

## Configuración

### 1. Variables de entorno
Editar `.env` y asegurar que `PYTHON_PATH` apunte al intérprete de Python:

```env
PYTHON_PATH=C:\laragon\bin\python\python-3.10\python.exe
# PYTHONPATH=  (opcional, solo si se requiere una ruta adicional)
```

### 2. Ejecutar migraciones
```bash
php artisan migrate
```

## Uso

### 1. Subir un archivo
1. Ir a **Menú → Archivos → Crear**
2. Seleccionar un archivo PDF (máx. 20 MB)
3. Indicar la fecha del archivo
4. Hacer clic en "Subir y procesar"

El sistema:
- Valida que sea un PDF
- Guarda el archivo en `storage/app/upload/`
- Crea un registro en la tabla `archivos`
- **Invoca el script Python** que extrae texto página por página
- Inserta cada página en la tabla `paginas`

Si el proceso Python falla, el archivo se elimina automáticamente y se muestra un mensaje de error.

### 2. Listar archivos
Ir a **Menú → Archivos → Ver** para ver todos los archivos subidos.
- Permite búsqueda por nombre
- Ofrece botón para descargar cada archivo

### 3. Buscar en archivos
Ir a **Menú → Archivos → Buscar** para buscar texto dentro de los PDFs.

Opciones:
- **Término de búsqueda**: palabra(s) o frase
- **Modo**: 
  - "Palabras (AND)": busca todos los términos en el mismo documento
  - "Frase exacta": busca la frase textual
- **Filtro de fechas**: limitar resultados a un rango de fechas

Los resultados muestran:
- Nombre del archivo
- Número de página
- Fecha del archivo
- Fragmento de texto que contiene el término

## Estructura de carpetas

```
financiamientos/
├── app/
│   ├── Http/Controllers/ArchivoController.php
│   └── Models/Archivo.php
├── database/
│   └── migrations/
│       ├── 2026_09_07_145327_create_archivos_table.php
│       └── 2026_09_07_145328_create_paginas_table.php
├── resources/views/archivos/
│   ├── show.blade.php      (listado)
│   ├── create.blade.php    (formulario de subida)
│   └── buscar.blade.php    (búsqueda)
├── public/js/archivos/
│   └── show.js             (DataTable para listado)
├── scripts/
│   └── extraer_pdf.py      (script de extracción)
└── .env                    (incluye PYTHON_PATH)
```

## Troubleshooting

### Error: "Python not found" o "script no encontrado"
- Verificar que `PYTHON_PATH` en `.env` apunta a un ejecutable válido
- Ejecutar en terminal: `C:\laragon\bin\python\python-3.10\python.exe --version`

### Error de conexión a base de datos en el script Python
- Verificar que `DB_HOST`, `DB_USERNAME`, `DB_PASSWORD`, `DB_DATABASE` en `.env` son correctos
- El script recibe estas variables automáticamente desde Laravel

### Las páginas no se extraen (script tarda mucho)
- PDFs muy grandes pueden tardar. El timeout está configurado en 300 segundos (5 minutos)
- Para PDFs con escaneo de imágenes sin OCR, el texto extraído será vacío

### Búsqueda no encuentra resultados
- Verificar que el PDF fue procesado correctamente (revisar tabla `paginas`)
- La búsqueda es case-insensitive pero literal (sin stemming)
- Probar con frases cortas en lugar de palabras complejas

## Notas técnicas

- El procesamiento es **síncrono y bloqueante**: la subida espera a que termine la extracción de texto
- Para PDFs grandes, el usuario verá una "carga" durante varios segundos
- No hay cola de trabajos (QUEUE_CONNECTION=sync) — si se requiere, puede adaptarse para usar jobs asincronos
- El texto extraído es texto plano, sin formato ni características gráficas
- No hay OCR automático para PDFs escaneados

## Próximas mejoras opcionales

- [ ] Hacer la extracción asincrónica (usar Queue + jobs)
- [ ] Agregar OCR para PDFs escaneados (usar Tesseract)
- [ ] Índices de búsqueda full-text en MySQL para búsquedas más rápidas
- [ ] Previsualización de PDFs en el navegador
- [ ] Notificaciones al completar la extracción
