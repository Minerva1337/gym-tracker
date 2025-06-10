import { updateEntry } from './db-utils';

async function run() {
  const success = await updateEntry('exercises', {
    name: 'Trizeps Kickbacks mit KH',
    category: 'Trizeps'
  }, 'id = 20 AND user_id = 5');

  console.log(success ? '✅ Eintrag aktualisiert' : '❌ Fehler beim Aktualisieren');
}

run();
