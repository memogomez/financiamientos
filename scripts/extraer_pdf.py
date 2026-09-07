import fitz  # PyMuPDF
import mysql.connector
import sys
import os

if len(sys.argv) != 3:
    print("Uso: script.py <archivo_id> <ruta_pdf>")
    sys.exit(1)

archivo_id = int(sys.argv[1])
pdf_path = sys.argv[2]

# Verificar que el archivo existe y es un archivo real
if not os.path.isfile(pdf_path):
    print(f"Ruta invalida o no es un archivo: {pdf_path}")
    sys.exit(1)

# Credenciales: variables de entorno primero (prod/local), con fallbacks
db_host = os.environ.get('DB_HOST', 'localhost')
db_user = os.environ.get('DB_USERNAME', 'root')
db_password = os.environ.get('DB_PASSWORD', '')
db_name = os.environ.get('DB_DATABASE', 'financiamientos')

# Conexión a la base de datos
try:
    conn = mysql.connector.connect(
        host=db_host,
        user=db_user,
        password=db_password,
        database=db_name
    )
    cursor = conn.cursor()
except Exception as conn_err:
    print(f"Error de conexion a la base de datos: {conn_err}")
    sys.exit(1)

# Procesar el PDF (solo inserta en paginas; el archivo ya existe en Laravel)
try:
    cursor.execute("SELECT id FROM archivos WHERE id = %s", (archivo_id,))
    if not cursor.fetchone():
        print(f"No existe un archivo con id {archivo_id}")
        sys.exit(1)

    doc = fitz.open(pdf_path)
    for page_num in range(len(doc)):
        page = doc.load_page(page_num)
        texto = page.get_text()
        cursor.execute(
            "INSERT INTO paginas (archivo_id, numero_pagina, texto) VALUES (%s, %s, %s)",
            (archivo_id, page_num + 1, texto)
        )
    conn.commit()
    print(f'Paginas guardadas para archivo_id: {archivo_id}')
except Exception as e:
    print(f"Error al procesar el PDF: {e}")
    sys.exit(1)
finally:
    cursor.close()
    conn.close()
