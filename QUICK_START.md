# Quick Start Guide

## เริ่มต้นใช้งานใน 3 ขั้นตอน

### 1. เปิด Command Prompt หรือ PowerShell
```bash
cd C:\cycle\StartDocker
```

### 2. รันคำสั่งเดียว
```bash
start.bat
```

### 3. รอให้ containers เริ่มทำงาน (ประมาณ 1-2 นาที)

---

## ตรวจสอบว่าทำงานได้

เปิดเบราว์เซอร์ไปที่:
- **http://localhost:8080/check.php** - ตรวจสอบสภาพแวดล้อม (แนะนำ!)
- **http://localhost:8080** - หน้าแรก
- **http://localhost:8000** - phpMyAdmin

---

## พัฒนาโปรเจค

วางไฟล์ PHP ของคุณในโฟลเดอร์ `php/src/`

ตัวอย่าง:
- `php/src/index.php` → http://localhost:8080/index.php
- `php/src/api/users.php` → http://localhost:8080/api/users.php

---

## ข้อมูลการเชื่อมต่อ

### MySQL
```
Host: db (ใน Docker) หรือ localhost (จากเครื่อง host)
Port: 3306
Database: myapp
Username: devuser
Password: devpassword
Root Password: rootpassword
```

### Redis
```
Host: redis (ใน Docker) หรือ localhost
Port: 6379
Password: ไม่มี
```

### phpMyAdmin
```
URL: http://localhost:8000
Server: db
Username: root
Password: rootpassword
```

---

## หยุดการทำงาน

```bash
stop.bat
```

---

## รีสตาร์ท

```bash
restart.bat
```

---

## ไฟล์ทดสอบที่มีให้

- `/check.php` - ตรวจสอบสภาพแวดล้อมทั้งหมด
- `/test-db.php` - ทดสอบการเชื่อมต่อ MySQL
- `/test-redis.php` - ทดสอบการเชื่อมต่อ Redis
- `/info.php` - ดู PHP Info

---

## ปัญหาที่พบบ่อย

### Docker ไม่ทำงาน
- ตรวจสอบว่า Docker Desktop เปิดอยู่หรือไม่
- รัน `docker --version` เพื่อตรวจสอบ

### Port ถูกใช้งานแล้ว
- เปลี่ยน port ใน `docker-compose.yml`
- หรือหยุด service ที่ใช้ port นั้นอยู่

### Container ไม่เริ่ม
- ดู logs: `docker-compose logs`
- ลบ volumes เก่า: `docker-compose down -v`

---

**Happy Coding!**

