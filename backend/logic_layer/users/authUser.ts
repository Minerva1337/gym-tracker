// authUser.ts
import { fetchFromDB } from '../logic-fetch';
import * as bcrypt from 'bcrypt';

async function run() {
  const [email, password] = process.argv.slice(2);
  if (!email || !password) {
    console.error('❌ Email und Passwort erforderlich');
    process.exit(1);
  }

  const rows = await fetchFromDB(`SELECT * FROM users WHERE email = '${email.replace(/'/g, "\\'")}'`);
  if (rows.length === 0) {
    console.log(JSON.stringify({ success: false, reason: 'not_found' }));
    return;
  }

  const user = rows[0];
  const match = await bcrypt.compare(password, user.password_hash);

  console.log(JSON.stringify({ success: match, user_id: user.id }));
}

run();