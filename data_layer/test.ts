import { fetchFromDB } from './logic-fetch';

async function run() {
  const query = "SELECT * FROM exercises WHERE category = 'Brust'";
  try {
    const results = await fetchFromDB(query);
    console.log("DB-Daten:", results);
  } catch (err) {
    console.error("Fehler:", err);
  }
}

run();
