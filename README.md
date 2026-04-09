# things
Каталог вещей для систематизации предметов в доме.

## SQL-структура

```sql
CREATE TABLE items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255),
  category VARCHAR(255),
  location VARCHAR(255),
  note TEXT,
  image_path VARCHAR(255),
  qr_code TEXT,
  date_added DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  location VARCHAR(255)
);

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  role VARCHAR(100) DEFAULT 'Пользователь',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

## Пользователи

- Страница `users.php` поддерживает создание, редактирование и удаление пользователей.
- Все операции выполняются асинхронно (без перезагрузки страницы).
