#!/usr/bin/env python3

import mysql.connector
import json
import sys
import os
import hashlib
from datetime import datetime
from dotenv import load_dotenv

DEFAULT_OUTPUT_DIR = os.path.expanduser("~/gym.lukas-holzmann.de/tmp_json")


def generate_output_filename(query: str, output_dir: str) -> str:
    hash_source = query + datetime.now().isoformat()
    file_hash = hashlib.md5(hash_source.encode()).hexdigest()
    filename = f"data_{file_hash}.json"
    return os.path.join(output_dir, filename)


def fetch_data_from_db(query: str) -> list:
    load_dotenv()

    connection = mysql.connector.connect(
        host=os.getenv("DB_HOST"),
        port=int(os.getenv("DB_PORT", 3306)),
        user=os.getenv("DB_USER"),
        password=os.getenv("DB_PASSWORD"),
        database=os.getenv("DB_NAME")
    )
    cursor = connection.cursor(dictionary=True)
    cursor.execute(query)
    results = cursor.fetchall()
    cursor.close()
    connection.close()
    return results


def save_to_json_file(data: list, filepath: str):
    os.makedirs(os.path.dirname(filepath), exist_ok=True)
    with open(filepath, "w", encoding="utf-8") as f:
        json.dump(data, f, ensure_ascii=False, indent=2, default=str)


def main():
    if len(sys.argv) < 2:
        print("Usage: python3 data_layer.py '<SQL_QUERY>' [output_dir]", file=sys.stderr)
        sys.exit(1)

    query = sys.argv[1]
    output_dir = sys.argv[2] if len(sys.argv) >= 3 else DEFAULT_OUTPUT_DIR

    try:
        results = fetch_data_from_db(query)
        filepath = generate_output_filename(query, output_dir)
        save_to_json_file(results, filepath)
        print(filepath)
    except Exception as e:
        print(f"[ERROR] {e}", file=sys.stderr)
        sys.exit(1)


if __name__ == "__main__":
    main()
