// Datei: logic-fetch.ts

import { exec } from 'child_process';
import * as fs from 'fs/promises';
import * as path from 'path';

const PYTHON_SCRIPT = path.resolve(__dirname, './data_layer.py'); // Pfad zum Python-Skript
const DEFAULT_OUTPUT_DIR = path.resolve(__dirname, '../tmp_json'); // gleich wie in Python

export async function fetchFromDB(sqlQuery: string): Promise<any[]> {
  return new Promise((resolve, reject) => {
    const command = `python3.10 "${PYTHON_SCRIPT}" "${sqlQuery}" "${DEFAULT_OUTPUT_DIR}"`;

    exec(command, async (error, stdout, stderr) => {
      if (error) {
        return reject(`Fehler beim Ausführen des Python-Skripts:\n${stderr}`);
      }

      const jsonPath = stdout.trim();

      try {
        const fileContent = await fs.readFile(jsonPath, 'utf-8');
        const data = JSON.parse(fileContent);
        await fs.unlink(jsonPath); // Datei bereinigen (optional)
        resolve(data);
      } catch (e) {
        reject(`Fehler beim Lesen/Parsen der JSON-Datei:\n${e}`);
      }
    });
  });
}
