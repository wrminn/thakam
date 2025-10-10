# Garbage Payment System

ระบบต้นแบบสำหรับจัดการคำร้องขอถังขยะ การชำระค่าบริการ และการแจ้งเหตุฉุกเฉินของเทศบาล โดยในรีโพนี้ได้ย้ายฝั่งเซิร์ฟเวอร์จาก Node.js (Express + Drizzle) มาเป็น **Laravel 11** เพื่อให้สะดวกกับทีมที่ถนัด PHP มากกว่า

## โครงสร้างโปรเจกต์

```
client/             # แอปหน้าเว็บ (Vite + React)
server-laravel/     # Laravel API + การจัดเก็บไฟล์อัปโหลด
shared/             # โค้ด TypeScript ที่ใช้ร่วมกัน (เช่น schema)
```

## การเตรียม Laravel API

> ต้องติดตั้ง PHP 8.2+, Composer, และฐานข้อมูล PostgreSQL ให้พร้อมก่อน

1. คัดลอกไฟล์ตั้งค่าตัวอย่าง
   ```bash
   cd server-laravel
   cp .env.example .env
   ```
2. แก้ไข `.env` ให้เชื่อมต่อฐานข้อมูล PostgreSQL เดิมที่เคยใช้กับ Drizzle จากนั้นรัน `php artisan key:generate` เพื่อสร้าง `APP_KEY` และกำหนด `JWT_SECRET` (เช่นใช้คำสั่ง `openssl rand -hex 32`)
3. ติดตั้ง dependencies
   ```bash
   composer install
   ```
4. รัน migration (ฐานข้อมูลจะถูกสร้าง schema ให้เหมือนของเดิม)
   ```bash
   php artisan migrate
   ```
5. สร้างลิงก์ storage สำหรับเสิร์ฟไฟล์แนบ
   ```bash
   php artisan storage:link
   ```
6. รันเซิร์ฟเวอร์ทดสอบ
   ```bash
   php artisan serve
   ```

เอนด์พอยต์ API ทั้งหมดจะอยู่ภายใต้ `/api/*` เหมือนเดิม และยังคงรูปแบบ Response ให้เข้ากับฝั่งไคลเอนต์ชุดเดิม

## รันฝั่งไคลเอนต์ (React)

จากโฟลเดอร์รากของโปรเจกต์

```bash
npm install
npm run dev
```

โดยค่าเริ่มต้น Vite จะเปิดที่ `http://localhost:5173` และ proxy คำขอ `/api/*` ไปยัง Laravel (`http://localhost:8000`)

## จุดเด่นของการย้ายมา Laravel

- ใช้ **Eloquent ORM** แทน Drizzle โดยคง schema และความสัมพันธ์กับ PostgreSQL เหมือนเดิม
- รองรับการอัปโหลดไฟล์ (แนบกับคำร้องขอถังขยะ) ผ่าน `storage/app/public/uploads` และคืน URL แบบเดียวกับของเดิม
- ระบบล็อกอิน/สมัครสมาชิกใช้ JWT เก็บใน Cookie ชื่อ `token` เหมือนฝั่ง Express เดิม เพื่อความเข้ากันได้กับ client
- ปรับการตรวจสอบสิทธิ์ของแดชบอร์ดชำระเงิน (admin) ให้ใช้อีเมล `admin02@example.com` ตามเงื่อนไขเดิม

## สคริปต์เพิ่มเติมที่มีประโยชน์

| คำสั่ง | ความหมาย |
| --- | --- |
| `php artisan test` | รันทดสอบอัตโนมัติ |
| `php artisan migrate:fresh --seed` | รีเซ็ตฐานข้อมูลแล้ว seed ใหม่ |
| `php artisan queue:work` | (ในอนาคต) รันคิวสำหรับงานพื้นหลัง |

## หมายเหตุ

- โฟลเดอร์ `server/` (Express) และไฟล์กำกับ Drizzle ถูกลบแล้ว หากต้องการดูเวอร์ชันเก่าสามารถย้อนจาก Git history
- เมื่อ deploy โปรดตั้งค่าเว็บเซิร์ฟเวอร์ให้ชี้ไปที่ `server-laravel/public` และตั้ง Cron/Queue ตามความเหมาะสม
