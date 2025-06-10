// Datei: logic-layer.service.ts

import { Injectable } from '@nestjs/common';
import { exec } from 'child_process';
import * as fs from 'fs/promises';
import * as path from 'path';

@Injectable()
export class LogicLayerService {
  private pythonScriptPath = path.join(__dirname, '..', 'scripts', 'data_layer.py');
  private outputDir = '/tmp';

  async fetchData(sqlQuery: string): Promise<any[]> {
    return new Promise((resolve, reject) => {
      const command = `python3 "${this.pythonScriptPath}" "${sqlQuery}" "${this.outputDir}"`;

      exec(command, async (error, stdout, stderr) => {
        if (error) return reject(`Fehler beim Ausführen von Python: ${stderr}`);

        const jsonPath = stdout.trim();

        try {
          const content = await fs.readFile(jsonPath, 'utf-8');
          const data = JSON.parse(content);
          await fs.unlink(jsonPath); // temporäre Datei löschen
          resolve(data);
        } catch (err) {
          reject(`Fehler beim Lesen der JSON-Datei: ${err}`);
        }
      });
    });
  }
}
