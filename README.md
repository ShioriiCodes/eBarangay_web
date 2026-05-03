# 🏛️ eBarangay

<p align="center">
  <b>Web-Based e-Governance and Document Requisition System</b><br>
  Alfonso XIII, Quezon, Palawan
</p>
<p align="center">
  <a href="https://ebarangay.great-site.net/" target="_blank">
    <img src="https://img.shields.io/badge/🌐%20Live%20Website-Visit%20Now-blue?style=for-the-badge">
  </a>
</p>
<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-red?style=for-the-badge&logo=laravel">
  <img src="https://img.shields.io/badge/MySQL-Database-blue?style=for-the-badge&logo=mysql">
  <img src="https://img.shields.io/badge/TailwindCSS-UI-38B2AC?style=for-the-badge&logo=tailwind-css">
  <img src="https://img.shields.io/badge/Status-Active-success?style=for-the-badge">
</p>

---

## 📌 Overview

**eBarangay** is a modern **web-based e-governance system** designed to digitize barangay services.

It enables residents to:
- Request documents online  
- Submit concerns  
- Track transactions  

While allowing barangay officials to efficiently manage requests through a centralized system.

---

## 🚀 Features

### 📄 Document Requests
- Barangay Clearance  
- Certificate of Residency  
- Certificate of Indigency  
- Barangay ID  

### 🔄 Workflow
```
Pending → Under Review → Approved → Ready → Completed
```

### 🧩 System Modules
- 📝 Concern Management  
- 🔔 Notification System  
- 📊 Admin Dashboard  
- 🔐 Role-Based Access  

---

## 👥 User Roles

### 🔐 Admin / Secretary
- Manage document requests  
- Approve / reject applications  
- Handle concerns  
- Monitor system activity  

### 🏠 Residents
- Register & login  
- Request documents  
- Track status  
- Submit concerns  

---

## 🎨 UI Preview

> 📸 Add your screenshots here

```
/screenshots
├── login.png
├── dashboard.png
├── request.png
```

Example:

![Login](screenshots/login.png)  
![Dashboard](screenshots/dashboard.png)

---

## 🛠️ Tech Stack

| Layer | Technology |
|------|-----------|
| Backend | Laravel (PHP) |
| Database | MySQL |
| Frontend | Blade (HTML, CSS, JS) |
| Styling | Tailwind CSS |
| Architecture | Client-Server |

---

## 🎨 UI Design

- 🇵🇭 Philippine-inspired theme  
- Blue `#0038A8`  
- Red `#CE1126`  
- Clean & modern interface  
- Fully responsive  

---

## 🔐 Authentication & Security

- Laravel Authentication  
- Role-based access  
- Password hashing  
- Email password reset  
- Protected routes  

---

## 🖨️ Document Generation (Upcoming)

- Auto-filled templates  
- Printable PDF documents  
- Ready for signature by authorized personnel  

---

## 🔄 System Flow

```
User → Submit Request → Admin Review → Status Update → Notification → Release
```

---

## 📁 Project Structure

```
app/
├── Models/
├── Http/
│   ├── Controllers/
│   └── Middleware/

database/
├── migrations/
├── seeders/

resources/
├── views/
│   ├── auth/
│   ├── resident/
│   ├── admin/
│   └── layouts/

routes/
└── web.php
```

---

## ⚙️ Installation

```bash
git clone https://github.com/your-username/ebarangay.git
cd ebarangay
composer install
cp .env.example .env
php artisan key:generate
```

Configure `.env`, then run:

```bash
php artisan migrate:fresh --seed
php artisan serve
```

---

## 🔑 Default Admin

```
Email: admin@ebarangay.test
Password: password
```

---

## 📚 Academic Project

**BS Information Technology Capstone Project**  
Palawan State University – Quezon Campus

---

## 🤝 Contributing

Pull requests are welcome. For major changes, please open an issue first.

---

## 📄 License

This project is for **academic purposes only**.

---

## ⭐ Tagline

> Bringing barangay services closer to the community through digital transformation.
