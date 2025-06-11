// ========== auth.service.ts ==========
import { Injectable } from '@nestjs/common';
import { JwtService } from '@nestjs/jwt';
import * as bcrypt from 'bcrypt';
import { fetchFromDB } from '../logic_layer/logic-fetch';
import { createEntry } from '../logic_layer/db-utils';

@Injectable()
export class AuthService {
  constructor(private jwtService: JwtService) {}

  async validateUser(email: string, password: string): Promise<any> {
    const users = await fetchFromDB(`SELECT * FROM users WHERE email = '${email}'`);
    const user = users[0];
    if (user && await bcrypt.compare(password, user.password_hash)) {
      const { password_hash, ...result } = user;
      return result;
    }
    return null;
  }

  async login(user: any) {
    const payload = { sub: user.id };
    return {
      access_token: this.jwtService.sign(payload),
    };
  }

  async register(username: string, email: string, password: string): Promise<boolean> {
    const hash = await bcrypt.hash(password, 10);
    return await createEntry('users', {
      username,
      email,
      password_hash: hash
    });
  }
}