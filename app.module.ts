import { Module } from '@nestjs/common';
import { AuthModule } from './auth/auth.module';
import { ExercisesModule } from './exercises/exercises.module';

@Module({
  imports: [AuthModule, ExercisesModule], // Hier werden alle Module importiert
})
export class AppModule {}