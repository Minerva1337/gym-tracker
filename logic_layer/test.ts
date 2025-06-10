// Datei: logic_layer/test.ts

import { fetchFromDB } from './logic-fetch';

async function run() {
  const sql = "SELECT * FROM exercises WHERE user_id = 5";

  try {
    const results = await fetchFromDB(sql);
    console.log("✅ Ergebnis:", results);
  } catch (err) {
    console.error(err);
  }
}

run();
