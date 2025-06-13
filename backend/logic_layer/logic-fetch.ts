import { spawn } from 'child_process';
import * as fs from 'fs/promises';
import * as path from 'path';

const PYTHON_SCRIPT = path.resolve(__dirname, '../data_layer/data_layer.py');
const OUTPUT_DIR = path.resolve(__dirname, '../data_layer/tmp_json');

export async function fetchFromDB(sqlQuery: string): Promise<any[]> {
  return new Promise((resolve, reject) => {
    const args = [PYTHON_SCRIPT, sqlQuery, OUTPUT_DIR];
    const process = spawn(`${process.env.HOME}/python3.10/bin/python3.10`, args);

    let stdout = '';
    let stderr = '';

    process.stdout.on('data', data => { stdout += data.toString(); });
    process.stderr.on('data', data => { stderr += data.toString(); });

    process.on('close', async (code) => {
      if (code !== 0) {
        return reject(`Python-Fehler:\n${stderr}`);
      }

      const jsonPath = stdout.trim();

      try {
        const content = await fs.readFile(jsonPath, 'utf-8');
        const data = JSON.parse(content);
        await fs.unlink(jsonPath);
        resolve(data);
      } catch (e) {
        reject(`Fehler beim Lesen/Parsen:\n${e}`);
      }
    });
  });
}
