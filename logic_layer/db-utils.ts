import { fetchFromDB } from './logic-fetch';

/**
 * Generische Einfügefunktion für beliebige Tabellen
 * @param table - Name der Zieltabelle, z. B. "exercises"
 * @param data - Objekt mit Spaltennamen und Werten
 * @returns Promise<boolean> (true bei Erfolg)
 */
export async function createEntry(table: string, data: Record<string, any>): Promise<boolean> {
  if (!table || typeof data !== 'object' || Object.keys(data).length === 0) {
    throw new Error("Ungültige Eingabe für createEntry()");
  }

  const columns = Object.keys(data).map(col => `\`${col}\``).join(', ');
  const values = Object.values(data)
    .map(val => typeof val === 'string' ? `'${val.replace(/'/g, "\\'")}'` : val)
    .join(', ');

  const sql = `INSERT INTO \`${table}\` (${columns}) VALUES (${values});`;

  try {
    await fetchFromDB(sql);
    return true;
  } catch (error) {
    console.error("Fehler bei createEntry:", error);
    return false;
  }
}
