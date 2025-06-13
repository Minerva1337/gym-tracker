import { fetchFromDB } from '../logic-fetch';
import { createEntry } from '../db-utils';
import * as bcrypt from 'bcrypt';

async function run() {
  const [username, email, password] = process.argv.slice(2);
  if (!username || !email || !password) {
    console.error('❌ Benutzername, E-Mail und Passwort erforderlich');
    process.exit(1);
  }

  // E-Mail-Schutz (SQL-Injection vermeiden)
  const escapedEmail = email.replace(/'/g, "\\'");
  const existing = await fetchFromDB(`SELECT id FROM users WHERE email = '${escapedEmail}'`);

  if (existing.length > 0) {
    console.log(JSON.stringify({ success: false, reason: 'duplicate_email' }));
    return;
  }

  // Passwort hashen
  const hash = await bcrypt.hash(password, 10);

  // Insert versuchen
  try {
    const inserted = await createEntry('users', {
      username,
      email,
      password_hash: hash
    });

    console.log(JSON.stringify({ success: inserted }));
  } catch (err: any) {
    console.log(JSON.stringify({ success: false, reason: 'insert_failed', message: err.message }));
  }
}

run();