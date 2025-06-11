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


/**
 * Generische Funktion zum Aktualisieren eines Eintrags in einer Tabelle
 * @param table - Tabellenname (z. B. "exercises")
 * @param data - zu aktualisierende Spalten/Werte
 * @param condition - WHERE-Klausel als String (z. B. "id = 5 AND user_id = 1")
 */
export async function updateEntry(
  table: string,
  data: Record<string, any>,
  condition: string
): Promise<boolean> {
  if (!table || typeof data !== 'object' || Object.keys(data).length === 0 || !condition) {
    console.error("updateEntry() → Ungültige Eingabe:", { table, data, condition });
    throw new Error("Ungültige Eingabe für updateEntry()");
  }

  const setClause = Object.entries(data)
    .map(([key, val]) => {
      const safeVal = typeof val === 'string' ? `'${val.replace(/'/g, "\\'")}'` : val;
      return `\`${key}\` = ${safeVal}`;
    })
    .join(', ');

  const sql = `UPDATE \`${table}\` SET ${setClause} WHERE ${condition};`;

  console.log("DEBUG - SQL (update):", sql);

  try {
    await fetchFromDB(sql);
    return true;
  } catch (error) {
    console.error("Fehler bei updateEntry:", error);
    return false;
  }
}

export async function deleteEntry(table: string, condition: string): Promise<boolean> {
  if (!table || !condition) {
    console.error("deleteEntry() → Ungültige Eingabe:", { table, condition });
    throw new Error("Ungültige Eingabe für deleteEntry()");
  }

  const sql = `DELETE FROM \`${table}\` WHERE ${condition};`;

  console.log("DEBUG - SQL (delete):", sql);

  try {
    await fetchFromDB(sql);
    return true;
  } catch (error) {
    console.error("Fehler bei deleteEntry:", error);
    return false;
  }
}