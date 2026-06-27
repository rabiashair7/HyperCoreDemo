USE hypercore_db;

INSERT INTO users (
    full_name,
    email,
    password,
    role
)
VALUES
(
    'Rabia Shaier',
    'admin@hypercore.com',
    '$2y$10$1GHnCTu5UyK7YzJIHjiIYe1Y6Ww3Wu.c96I3v7fKWgL8MCs6cS6gS',
    'admin' 
);

INSERT INTO categories (
    name,
    slug
)
VALUES
('Games', 'games'),
('Hardware', 'hardware'),
('Accessories', 'accessories'),
('Retro Gaming', 'retro-gaming');


INSERT INTO products (
    category_id,
    name,
    description,
    price,
    image,
    stock_quantity,
    is_featured
)
VALUES

(
    1,
    'Cyberpunk 2077',
    'Open-world futuristic RPG game.',
    199.99,
    'assets/images/products/cyberpunk.jpg',
    15,
    1
),

(
    1,
    'Elden Ring',
    'Fantasy action RPG game.',
    249.99,
    'assets/images/products/eldenring.jpg',
    20,
    1
),

(
    2,
    'RTX 5070',
    'NVIDIA high-performance graphics card.',
    3499.99,
    'assets/images/products/rtx5070.jpg',
    8,
    1
),

(
    2,
    'Ryzen 9 9900X',
    'AMD gaming processor.',
    2299.99,
    'assets/images/products/ryzen9.jpg',
    12,
    0
),

(
    3,
    'Mechanical Keyboard',
    'RGB gaming keyboard.',
    399.99,
    'assets/images/products/keyboard.jpg',
    30,
    0
),

(
    4,
    'Retro Arcade Controller',
    'Classic retro gaming arcade controller.',
    249.99,
    'assets/images/products/arcade-controller.jpg',
    10,
    1
);