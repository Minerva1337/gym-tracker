import { deleteEntry } from './db-utils';

async function run() {
  const success = await deleteEntry('exercises', 'id = 20 AND user_id = 5');

  console.log(success ? '✅ Eintrag gelöscht' : '❌ Fehler beim Löschen');
}

run();
