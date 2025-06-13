import { fetchFromDB } from '../logic-fetch';
import { createEntry } from '../db-utils';
import * as bcrypt from 'bcrypt';

async function run() {
  const [email, password] = process.argv.slice(2);
  if (!email || !password) {
    console.error('❌ Email und Passwort erforderlich');
    process.exit(1);
  }

  // 1. Prüfen, ob E-Mail schon vorhanden ist
  const escapedEmail = email.replace(/'/g, "\\'");
  const existingUsers = await fetchFromDB(`SELECT id FROM users WHERE email = '${escapedEmail}'`);

  if (existingUsers.length > 0) {
    console.log(JSON.stringify({ success: false, reason: 'duplicate_email' }));
    return;
  }

  // 2. Passwort hashen & Nutzer anlegen
  const hash = await bcrypt.hash(password, 10);
  const inserted = await createEntry('users', {
    email,
    password_hash: hash
  });

  if (inserted) {
    console.log(JSON.stringify({ success: true }));
  } else {
    console.log(JSON.stringify({ success: false, reason: 'insert_failed' }));
  }
}

run();