# PHP Development Environment

สภาพแวดล้อมสำหรับการพัฒนา PHP แบบเต็มรูปแบบ พร้อม MySQL, phpMyAdmin, และ Redis

## เริ่มต้นใช้งาน

### วิธีที่ 1: ใช้ Script (แนะนำ)
```bash
# เริ่มต้นสภาพแวดล้อม
start.bat

# หยุดสภาพแวดล้อม
stop.bat

# รีสตาร์ท
restart.bat
```

### วิธีที่ 2: ใช้ Docker Compose โดยตรง
```bash
# เริ่มต้น
docker-compose up -d --build

# หยุด
docker-compose down

# ดูสถานะ
docker-compose ps

# ดู logs
docker-compose logs -f
```

## Services ที่รวมอยู่

| Service | URL/Port | Description |
|---------|----------|-------------|
| **PHP Application** | http://localhost:8080 | Web server สำหรับพัฒนาโปรเจค PHP |
| **phpMyAdmin** | http://localhost:8000 | จัดการฐานข้อมูล MySQL ผ่านเว็บ |
| **MySQL** | localhost:3306 | ฐานข้อมูล MySQL 8.0 |
| **Redis** | localhost:6379 | Cache และ Session Storage |

## ข้อมูลการเชื่อมต่อ

### MySQL
- **Host:** `db` (ภายใน Docker network) หรือ `localhost` (จากเครื่อง host)
- **Port:** `3306`
- **Root Password:** `rootpassword`
- **Database:** `myapp`
- **Username:** `devuser`
- **Password:** `devpassword`

### phpMyAdmin
- **URL:** http://localhost:8000
- **Server:** `db`
- **Username:** `root`
- **Password:** `rootpassword`

### Redis
- **Host:** `redis` (ภายใน Docker network) หรือ `localhost` (จากเครื่อง host)
- **Port:** `6379`
- **Password:** ไม่มี (default)

## โครงสร้างโปรเจค

```
.
├── docker-compose.yml      # Docker Compose configuration
├── start.bat              # Script สำหรับเริ่มต้น
├── stop.bat               # Script สำหรับหยุด
├── restart.bat            # Script สำหรับรีสตาร์ท
├── php/
│   ├── Dockerfile         # PHP + Apache image
│   └── src/               # โฟลเดอร์สำหรับพัฒนาโปรเจค PHP ของคุณ
│       ├── index.php      # ไฟล์ทดสอบ
│       ├── config.php     # ไฟล์ configuration
│       └── .htaccess      # Apache configuration
└── mysql/
    └── init/              # SQL files สำหรับ initialize database
```

## การพัฒนาโปรเจค

### 1. พัฒนาโปรเจคในโฟลเดอร์ `php/src/`

ไฟล์ทั้งหมดใน `php/src/` จะถูก map ไปที่ `/var/www/html/` ใน container

ตัวอย่าง:
- `php/src/index.php` → http://localhost:8080/index.php
- `php/src/api/users.php` → http://localhost:8080/api/users.php

### 2. ใช้ไฟล์ config.php

```php
<?php
require_once 'config.php';

// เชื่อมต่อ Database
$pdo = getDBConnection();

// เชื่อมต่อ Redis
$redis = getRedisConnection();
$redis->set('key', 'value');
?>
```

### 3. ตัวอย่างการเชื่อมต่อ Database

```php
<?php
require_once 'config.php';

$pdo = getDBConnection();

// Query example
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll();

foreach ($users as $user) {
    echo $user['name'];
}
?>
```

### 4. ตัวอย่างการใช้ Redis

```php
<?php
require_once 'config.php';

$redis = getRedisConnection();

// Set value
$redis->set('user:1', json_encode(['name' => 'John', 'email' => 'john@example.com']));

// Get value
$user = json_decode($redis->get('user:1'), true);

// Set expiration
$redis->setex('session:abc123', 3600, 'session_data');
?>
```

## PHP Extensions ที่ติดตั้งแล้ว

- PDO & PDO_MySQL
- MySQLi
- Redis
- GD (Image Processing)
- ZIP
- MBString
- Intl
- OPcache
- BCMath
- PCNTL

## การจัดการ Database

### วิธีที่ 1: ใช้ phpMyAdmin
1. เปิด http://localhost:8000
2. Login ด้วย:
   - Server: `db`
   - Username: `root`
   - Password: `rootpassword`

### วิธีที่ 2: ใช้ MySQL Client
```bash
# เชื่อมต่อจากเครื่อง host
mysql -h localhost -P 3306 -u root -p
# Password: rootpassword
```

### วิธีที่ 3: ใช้ Docker Exec
```bash
docker exec -it mysql-db mysql -u root -p
# Password: rootpassword
```

### สร้าง Database ใหม่
```sql
CREATE DATABASE mynewdb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'newuser'@'%' IDENTIFIED BY 'newpassword';
GRANT ALL PRIVILEGES ON mynewdb.* TO 'newuser'@'%';
FLUSH PRIVILEGES;
```

## การปรับแต่ง Configuration

### เปลี่ยน PHP Settings
แก้ไขไฟล์ `php/Dockerfile` และ rebuild:
```bash
docker-compose up -d --build
```

### เปลี่ยน MySQL Password
แก้ไขไฟล์ `docker-compose.yml` ในส่วน environment ของ service `db`

### เพิ่ม SQL Initialization
วางไฟล์ `.sql` ในโฟลเดอร์ `mysql/init/` จะถูก execute อัตโนมัติเมื่อ container เริ่มครั้งแรก

## Troubleshooting

### ตรวจสอบ Logs
```bash
# ดู logs ทั้งหมด
docker-compose logs

# ดู logs ของ service เฉพาะ
docker-compose logs php-apache
docker-compose logs db
docker-compose logs redis
```

### ตรวจสอบสถานะ Services
```bash
docker-compose ps
```

### เข้าไปใน Container
```bash
# PHP Container
docker exec -it php-apache bash

# MySQL Container
docker exec -it mysql-db bash

# Redis Container
docker exec -it redis-cache sh
```

### รีเซ็ต Database
```bash
# หยุดและลบ volumes
docker-compose down -v

# เริ่มใหม่
docker-compose up -d
```

## ตัวอย่างโปรเจค

### Laravel
```bash
# ติดตั้ง Laravel ใน php/src
cd php/src
composer create-project laravel/laravel .

# แก้ไข .env
DB_HOST=db
DB_DATABASE=myapp
DB_USERNAME=devuser
DB_PASSWORD=devpassword
```

### CodeIgniter / Custom PHP
วางไฟล์โปรเจคของคุณใน `php/src/` ได้เลย

## Requirements

- Docker Desktop (Windows)
- Docker Compose
- อย่างน้อย 4GB RAM
- พอร์ต 8080, 8000, 3306, 6379 ต้องว่าง

## License

MIT License - ใช้ได้ฟรีสำหรับการพัฒนา

---

**Happy Coding!**

