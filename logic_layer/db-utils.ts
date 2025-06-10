import { fetchFromDB } from './logic-fetch';

export async function createEntry(table: string, data: Record<string, any>): Promise<boolean> {
  if (!table || typeof data !== 'object' || Object.keys(data).length === 0) {
    console.error("createEntry() → Leere oder ungültige Eingabedaten:", data);
    throw new Error("Ungültige Eingabe für createEntry()");
  }

  const columns = Object.keys(data).map(col => `\`${col}\``).join(', ');
  const values = Object.values(data)
    .map(val => typeof val === 'string' ? `'${val.replace(/'/g, "\\'")}'` : val)
    .join(', ');

  const sql = `INSERT INTO \`${table}\` (${columns}) VALUES (${values});`;

  console.log("DEBUG - SQL:", sql);

  try {
    await fetchFromDB(sql);
    return true;
  } catch (error) {
    console.error("Fehler bei createEntry:", error);
    return false;
  }
}
