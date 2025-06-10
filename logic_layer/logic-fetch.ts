// Datei: logic_layer/logic-fetch.ts

import { exec } from 'child_process';
import * as fs from 'fs/promises';
import * as path from 'path';

const PYTHON_SCRIPT = path.resolve(__dirname, '../data_layer/data_layer.py');
const OUTPUT_DIR = path.resolve(__dirname, '../data_layer/tmp_json');

export async function fetchFromDB(sqlQuery: string): Promise<any[]> {
  return new Promise((resolve, reject) => {
    const command = `python3.10 "${PYTHON_SCRIPT}" "${sqlQuery}" "${OUTPUT_DIR}"`;

    exec(command, async (error, stdout, stderr) => {
      if (error) {
        return reject(`Python-Fehler:\n${stderr}`);
      }

      const jsonPath = stdout.trim();

      try {
        const content = await fs.readFile(jsonPath, 'utf-8');
        const data = JSON.parse(content);
        await fs.unlink(jsonPath); // optional löschen
        resolve(data);
      } catch (e) {
        reject(`Fehler beim Lesen/Parsen:\n${e}`);
      }
    });
  });
}
