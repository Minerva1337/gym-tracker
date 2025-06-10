import { createEntry } from './db-utils';

async function run() {
  const success = await createEntry('exercises', {
    user_id: 5,
    name: 'Trizeps Kickbacks',
    category: 'Trizeps'
  });

  console.log(success ? '✅ Eintrag erstellt' : '❌ Fehler beim Erstellen');
}

run();
