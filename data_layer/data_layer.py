# Datei: data_layer.py

import mysql.connector
import json
import sys
import os
import hashlib
from datetime import datetime

def main():
    if len(sys.argv) != 3:
        print("Usage: python3 data_layer.py '<SQL_QUERY>' <output_dir>")
        sys.exit(1)

    query = sys.argv[1]
    output_dir = sys.argv[2]

    # MariaDB Verbindung
    connection = mysql.connector.connect(
        host="localhost:3306",
        user="lukas",                 
        password="MtYKk$uVnXY9WGNHfryX",   
        database="gym"
    )
    cursor = connection.cursor(dictionary=True)

    # Query ausführen
    cursor.execute(query)
    results = cursor.fetchall()

    # Eindeutiger Dateiname (Query + Timestamp)
    hash_source = query + datetime.now().isoformat()
    file_hash = hashlib.md5(hash_source.encode()).hexdigest()
    filename = f"data_{file_hash}.json"
    filepath = os.path.join(output_dir, filename)

    # JSON-Datei schreiben
    with open(filepath, "w", encoding="utf-8") as json_file:
        json.dump(results, json_file, ensure_ascii=False, indent=2)

    # Pfad zur JSON-Datei ausgeben
    print(filepath)

    cursor.close()
    connection.close()

if __name__ == "__main__":
    main()
