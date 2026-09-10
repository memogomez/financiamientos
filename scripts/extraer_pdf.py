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
    pages_count = len(doc)
    print(f"Total de páginas detectadas: {pages_count}")

    total_text_extracted = 0
    is_scanned = True  # Asumir escaneado hasta que encontremos texto

    for page_num in range(pages_count):
        page = doc.load_page(page_num)

        # Intentar extraer texto con el método default
        texto = page.get_text()

        # Si el texto está vacío, intentar con otro método (blocks)
        if not texto.strip():
            try:
                blocks = page.get_text("blocks")
                if isinstance(blocks, list):
                    texto = '\n'.join([str(block[4]) for block in blocks
                                      if isinstance(block, (list, tuple)) and len(block) > 4
                                      and block[4].strip()])
            except:
                pass

        # Si sigue vacío, intentar con dict
        if not texto.strip():
            try:
                text_dict = page.get_text("dict")
                if isinstance(text_dict, dict) and "blocks" in text_dict:
                    texts = []
                    for block in text_dict['blocks']:
                        if block.get('type') == 0:  # 0 = texto, 1 = imagen
                            if 'lines' in block:
                                for line in block['lines']:
                                    for span in line.get('spans', []):
                                        texts.append(span.get('text', ''))
                    texto = '\n'.join(texts)
            except:
                pass

        # Contar caracteres
        texto_len = len(texto.strip()) if isinstance(texto, str) else 0
        total_text_extracted += texto_len

        # Log de depuración
        status = "✓" if texto_len > 0 else "✗"
        print(f"Página {page_num + 1}: {status} {texto_len} caracteres")

        # Convertir a string si es necesario
        if not isinstance(texto, str):
            texto = str(texto)

        cursor.execute(
            "INSERT INTO paginas (archivo_id, numero_pagina, texto) VALUES (%s, %s, %s)",
            (archivo_id, page_num + 1, texto)
        )

    conn.commit()

    # Determinar si es escaneado
    if total_text_extracted == 0:
        is_scanned = True
        print(f"\n⚠️  ADVERTENCIA: PDF escaneado (sin texto extractable)")
        print(f"Para extraer texto de PDFs escaneados, instale Tesseract OCR:")
        print(f"https://github.com/UB-Mannheim/tesseract/wiki")
    else:
        is_scanned = False
        print(f"\n✓ Total de caracteres extraídos: {total_text_extracted}")

    print(f"Paginas guardadas para archivo_id: {archivo_id}")

except Exception as e:
    print(f"Error al procesar el PDF: {e}")
    import traceback
    traceback.print_exc()
    sys.exit(1)
finally:
    cursor.close()
    conn.close()
