// createUser.ts
import { createEntry } from '../db-utils';
import * as bcrypt from 'bcrypt';

async function run() {
  const [email, password] = process.argv.slice(2);
  if (!email || !password) {
    console.error('❌ Email und Passwort erforderlich');
    process.exit(1);
  }

  const hash = await bcrypt.hash(password, 10);

  const success = await createEntry('users', {
    email,
    password_hash: hash
  });

  console.log(JSON.stringify({ success }));
}

run();