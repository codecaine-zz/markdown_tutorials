# Pygame — Practical Tutorial (with Table of Contents)

A concise, copy‑pasteable guide to building 2D games and interactive apps with [Pygame](https://www.pygame.org/docs/). This focuses on clean patterns, simple utilities, and minimal boilerplate.

## Table of Contents

- [Overview](#overview)
- [Install (macOS)](#install-macos)
- [Quick Start: window + loop](#quick-start-window--loop)
- [Drawing basics (surfaces, colors, shapes)](#drawing-basics-surfaces-colors-shapes)
- [Images, Rects, and movement](#images-rects-and-movement)
- [Sprites and Groups](#sprites-and-groups)
- [Input and Events](#input-and-events)
- [Text rendering](#text-rendering)
- [Sound and Music](#sound-and-music)
- [Collision detection](#collision-detection)
- [Timing and delta time](#timing-and-delta-time)
- [Mini game: Pong](#mini-game-pong)
- [Recommended project structure](#recommended-project-structure)
- [Packaging notes](#packaging-notes)
- [Troubleshooting](#troubleshooting)
- [References](#references)

---

## Overview

Pygame wraps SDL to provide a simple API for 2D game loops, rendering, input, audio, and basic collision. Typical flow:

- Initialize modules and create a display Surface.
- Enter a loop: handle events, update state, render, flip.
- Cap framerate with a Clock.

## Install (macOS)

```bash
python -m pip install pygame
```

If you see import/runtime errors, ensure you’re using a modern Python and recent Pygame wheel. Apple Silicon is supported via official wheels.

## Quick Start: window + loop

```python
import pygame as pg

WIDTH, HEIGHT = 800, 600
BG = (24, 24, 32)

pg.init()
screen = pg.display.set_mode((WIDTH, HEIGHT))
pg.display.set_caption("Hello Pygame")
clock = pg.time.Clock()

running = True
while running:
    # 1) input/events
    for event in pg.event.get():
        if event.type == pg.QUIT:
            running = False

    # 2) update (game logic)
    # ...

    # 3) render
    screen.fill(BG)
    # draw stuff here

    pg.display.flip()   # or pg.display.update()
    clock.tick(60)      # cap to 60 FPS

pg.quit()
```

## Drawing basics (surfaces, colors, shapes)

```python
import pygame as pg
pg.init()
screen = pg.display.set_mode((640, 360))
clock = pg.time.Clock()

WHITE = (255, 255, 255)
RED = (239, 68, 68)
GREEN = (34, 197, 94)
BLUE = (59, 130, 246)

running = True
while running:
    for e in pg.event.get():
        if e.type == pg.QUIT:
            running = False

    screen.fill(WHITE)

    # line(surface, color, start_pos, end_pos, width)
    pg.draw.line(screen, BLUE, (20, 20), (200, 20), 3)

    # rect(surface, color, rect, width=0, border_radius=0)
    pg.draw.rect(screen, RED, pg.Rect(20, 60, 160, 80), border_radius=8)

    # circle(surface, color, center, radius, width=0)
    pg.draw.circle(screen, GREEN, (260, 100), 40)

    pg.display.flip()
    clock.tick(60)

pg.quit()
```

## Images, Rects, and movement

```python
import pygame as pg
pg.init()
screen = pg.display.set_mode((800, 450))
clock = pg.time.Clock()

player_img = pg.Surface((48, 48), pg.SRCALPHA)
pg.draw.rect(player_img, (255, 215, 0), (0, 0, 48, 48), border_radius=6)
player_rect = player_img.get_rect(center=(400, 225))

speed = 300  # pixels/sec
running = True
while running:
    dt = clock.tick(60) / 1000.0
    for e in pg.event.get():
        if e.type == pg.QUIT:
            running = False

    keys = pg.key.get_pressed()
    dx = dy = 0
    if keys[pg.K_LEFT] or keys[pg.K_a]:
        dx -= speed * dt
    if keys[pg.K_RIGHT] or keys[pg.K_d]:
        dx += speed * dt
    if keys[pg.K_UP] or keys[pg.K_w]:
        dy -= speed * dt
    if keys[pg.K_DOWN] or keys[pg.K_s]:
        dy += speed * dt

    player_rect.x += int(dx)
    player_rect.y += int(dy)

    screen.fill((30, 30, 46))
    screen.blit(player_img, player_rect)
    pg.display.flip()

pg.quit()
```

## Sprites and Groups

```python
import pygame as pg

class Player(pg.sprite.Sprite):
    def __init__(self, pos):
        super().__init__()
        self.image = pg.Surface((40, 40))
        self.image.fill((99, 102, 241))
        self.rect = self.image.get_rect(center=pos)
        self.speed = 240

    def update(self, dt):
        keys = pg.key.get_pressed()
        vx = vy = 0
        if keys[pg.K_LEFT] or keys[pg.K_a]:
            vx -= self.speed
        if keys[pg.K_RIGHT] or keys[pg.K_d]:
            vx += self.speed
        if keys[pg.K_UP] or keys[pg.K_w]:
            vy -= self.speed
        if keys[pg.K_DOWN] or keys[pg.K_s]:
            vy += self.speed
        self.rect.x += int(vx * dt)
        self.rect.y += int(vy * dt)

pg.init()
screen = pg.display.set_mode((800, 450))
clock = pg.time.Clock()
all_sprites = pg.sprite.Group(Player((400, 225)))

running = True
while running:
    dt = clock.tick(60) / 1000.0
    for e in pg.event.get():
        if e.type == pg.QUIT:
            running = False

    all_sprites.update(dt)
    screen.fill((17, 24, 39))
    all_sprites.draw(screen)
    pg.display.flip()

pg.quit()
```

## Input and Events

```python
import pygame as pg
pg.init()
screen = pg.display.set_mode((500, 300))
clock = pg.time.Clock()
msg = ""

running = True
while running:
    for e in pg.event.get():
        if e.type == pg.QUIT:
            running = False
        elif e.type == pg.KEYDOWN:
            if e.key == pg.K_ESCAPE:
                running = False
            elif e.key == pg.K_BACKSPACE:
                msg = msg[:-1]
            elif e.unicode:
                msg += e.unicode
        elif e.type == pg.MOUSEBUTTONDOWN:
            print("Mouse clicked:", e.pos, e.button)

    screen.fill((250, 250, 250))
    # simple text (see text section for fonts)
    pg.display.set_caption(f"Typed: {msg}")

    pg.display.flip()
    clock.tick(60)

pg.quit()
```

## Text rendering

```python
import pygame as pg
pg.init()
screen = pg.display.set_mode((640, 360))
clock = pg.time.Clock()

font = pg.font.SysFont(None, 36)  # use system default font

running = True
while running:
    for e in pg.event.get():
        if e.type == pg.QUIT:
            running = False

    screen.fill((34, 34, 48))
    text_surf = font.render("Hello, Pygame!", True, (255, 255, 255))
    screen.blit(text_surf, (24, 24))

    pg.display.flip()
    clock.tick(60)

pg.quit()
```

## Sound and Music

```python
import pygame as pg
pg.mixer.pre_init(44100, -16, 2, 512)
pg.init()

screen = pg.display.set_mode((400, 200))
clock = pg.time.Clock()

click = pg.mixer.Sound("click.wav")  # short sfx
pg.mixer.music.load("music.mp3")      # background music
pg.mixer.music.play(-1)               # loop forever

running = True
while running:
    for e in pg.event.get():
        if e.type == pg.QUIT:
            running = False
        elif e.type == pg.MOUSEBUTTONDOWN:
            click.play()

    screen.fill((20, 20, 20))
    pg.display.flip()
    clock.tick(60)

pg.mixer.music.stop()
pg.quit()
```

## Collision detection

```python
import pygame as pg

player = pg.Rect(50, 50, 40, 40)
wall = pg.Rect(120, 60, 100, 30)

# rect vs rect
def collides_rect(a: pg.Rect, b: pg.Rect) -> bool:
    return a.colliderect(b)

# sprite groups can use built-in collision helpers too
# pg.sprite.spritecollide(sprite, group, dokill) → list
```

## Timing and delta time

```python
import pygame as pg
pg.init()
clock = pg.time.Clock()

# Get milliseconds since last tick
while True:
    dt = clock.tick(120) / 1000.0  # seconds; target 120 FPS
    # use dt to scale movement/animations
    break
pg.quit()
```

## Mini game: Pong

```python
import pygame as pg

WIDTH, HEIGHT = 800, 480
WHITE = (255, 255, 255)
BG = (15, 23, 42)

class Paddle(pg.sprite.Sprite):
    def __init__(self, x):
        super().__init__()
        self.image = pg.Surface((12, 80))
        self.image.fill(WHITE)
        self.rect = self.image.get_rect(center=(x, HEIGHT // 2))
        self.speed = 360

    def update(self, dt, up, down):
        vy = 0
        keys = pg.key.get_pressed()
        if keys[up]:
            vy -= self.speed
        if keys[down]:
            vy += self.speed
        self.rect.y += int(vy * dt)
        self.rect.y = max(0, min(self.rect.y, HEIGHT - self.rect.height))

class Ball(pg.sprite.Sprite):
    def __init__(self):
        super().__init__()
        self.image = pg.Surface((14, 14), pg.SRCALPHA)
        pg.draw.circle(self.image, WHITE, (7, 7), 7)
        self.rect = self.image.get_rect(center=(WIDTH // 2, HEIGHT // 2))
        self.vx, self.vy = 300, 220

    def update(self, dt):
        self.rect.x += int(self.vx * dt)
        self.rect.y += int(self.vy * dt)
        if self.rect.top <= 0 or self.rect.bottom >= HEIGHT:
            self.vy *= -1

pg.init()
screen = pg.display.set_mode((WIDTH, HEIGHT))
clock = pg.time.Clock()

left = Paddle(30)
right = Paddle(WIDTH - 30)
ball = Ball()
all_sprites = pg.sprite.Group(left, right, ball)

score_l = score_r = 0
font = pg.font.SysFont(None, 48)

running = True
while running:
    dt = clock.tick(60) / 1000.0
    for e in pg.event.get():
        if e.type == pg.QUIT:
            running = False

    left.update(dt, pg.K_w, pg.K_s)
    right.update(dt, pg.K_UP, pg.K_DOWN)
    ball.update(dt)

    # paddle collisions
    if ball.rect.colliderect(left.rect) and ball.vx < 0:
        ball.vx *= -1
    if ball.rect.colliderect(right.rect) and ball.vx > 0:
        ball.vx *= -1

    # scoring
    if ball.rect.right < 0:
        score_r += 1
        ball.rect.center = (WIDTH // 2, HEIGHT // 2)
        ball.vx = abs(ball.vx)
    if ball.rect.left > WIDTH:
        score_l += 1
        ball.rect.center = (WIDTH // 2, HEIGHT // 2)
        ball.vx = -abs(ball.vx)

    screen.fill(BG)
    all_sprites.draw(screen)
    txt = font.render(f"{score_l} : {score_r}", True, WHITE)
    screen.blit(txt, (WIDTH // 2 - txt.get_width() // 2, 20))
    pg.display.flip()

pg.quit()
```

## Recommended project structure

A small but scalable layout:

```text
mygame/
  main.py
  settings.py
  assets/
    images/
    sounds/
  mygame/
    __init__.py
    core.py       # loop, scenes
    sprites.py    # sprite classes
    scenes/
      __init__.py
      menu.py
      game.py
```

- Keep asset loads centralized; pass references into objects that need them.
- Use `pygame.sprite.Group` for batching draw/update.

## Packaging notes

- For distribution, consider `pyinstaller` or `briefcase`. Keep assets relative to executable location and add runtime path detection.
- Avoid large uncompressed assets; prefer compressed formats when appropriate.

## Troubleshooting

- Black window and “not responding”: make sure your loop handles events and calls `pg.display.flip()`.
- Performance dips: reduce per-frame allocations, pre-render text, batch with sprite groups, cap FPS.
- Fonts missing: use `pg.font.get_fonts()` to check availability, or bundle TTFs and load with `pg.font.Font("path.ttf", size)`.
- Audio crackle: call `pg.mixer.pre_init(...)` before `pg.init()` and avoid reloading sounds per frame.

## References

- Official docs: [https://www.pygame.org/docs/](https://www.pygame.org/docs/)
- Community: [https://www.reddit.com/r/pygame/](https://www.reddit.com/r/pygame/)

---

Copy any block into a file and run with `python your_file.py`. Happy coding!
