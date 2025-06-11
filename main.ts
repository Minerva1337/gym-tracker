import { NestFactory } from '@nestjs/core';
import { AppModule } from './app.module';

async function bootstrap() {
  const app = await NestFactory.create(AppModule); // AppModule ist das Hauptmodul deiner App
  await app.listen(3000); // Startet die App auf Port 3000
}

bootstrap();