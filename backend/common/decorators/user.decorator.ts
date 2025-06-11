import { createParamDecorator, ExecutionContext } from '@nestjs/common';

// Ein Dekorator, der den Benutzer aus dem Request extrahiert
export const User = createParamDecorator((_, ctx: ExecutionContext) => {
  const req = ctx.switchToHttp().getRequest();
  return req.user;
});