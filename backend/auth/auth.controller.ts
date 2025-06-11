// ========== auth.controller.ts ==========
import { Controller, Post, Body, UnauthorizedException, BadRequestException } from '@nestjs/common';
import { AuthService } from './auth.service';

@Controller('auth')
export class AuthController {
  constructor(private authService: AuthService) {}

  @Post('login')
  async login(@Body() body: { email: string; password: string }) {
    const user = await this.authService.validateUser(body.email, body.password);
    if (!user) {
      throw new UnauthorizedException('Ungültige Zugangsdaten');
    }
    return this.authService.login(user);
  }

  @Post('register')
  async register(@Body() body: { username: string; email: string; password: string }) {
    if (!body.username || !body.email || !body.password) {
      throw new BadRequestException('Alle Felder sind erforderlich');
    }
    const success = await this.authService.register(body.username, body.email, body.password);
    if (!success) {
      throw new BadRequestException('Registrierung fehlgeschlagen');
    }
    return { message: '✅ Registrierung erfolgreich' };
  }
}