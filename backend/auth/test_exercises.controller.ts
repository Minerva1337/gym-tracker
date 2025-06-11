// ========== Beispiel: exercises.controller.ts ==========
import { Controller, Post, Body, UseGuards } from '@nestjs/common';
import { createEntry } from '../logic_layer/db-utils';
import { JwtAuthGuard } from '../auth/jwt.guard';
import { User } from '../common/user.decorator';

@Controller('exercises')
export class ExercisesController {
  @UseGuards(JwtAuthGuard)
  @Post()
  async create(@Body() body: any, @User() user: any) {
    return createEntry('exercises', {
      ...body,
      user_id: user.userId
    });
  }
}
