# 🏛️ eBarangay

**A Web-Based e-Governance and Document Requisition System**  
Alfonso XIII, Quezon, Palawan

---

## 📌 Overview

eBarangay is a **web-based system** that digitizes barangay services, allowing residents to request documents, submit concerns, and track transactions online.

---

## 🎯 Objectives

- Digitize barangay transactions  
- Streamline document processing  
- Reduce waiting time  
- Improve service accessibility  
- Maintain centralized records  

---

## 👥 User Roles

### 🔐 Admin
- Manage requests  
- Approve / reject documents  
- Handle concerns  
- Monitor system  

### 🏠 Residents
- Register & login  
- Request documents  
- Track status  
- Submit concerns  

---

## ⚙️ Core Features

### 📄 Documents
- Barangay Clearance  
- Certificate of Residency  
- Certificate of Indigency  
- Barangay ID  

### 🔄 Workflow
Pending → Under Review → Approved → Ready → Completed  

### 📝 System
- Concern Management  
- Notifications  
- Admin Dashboard  
- Role-Based Access  

---

## 🛠️ Tech Stack

- Laravel (PHP)  
- MySQL  
- Blade + Tailwind CSS  

---

## 🎨 UI Design

- Blue `#0038A8`  
- Red `#CE1126`  
- Responsive design  

---

## 🔄 System Flow

User → Request → Admin Review → Status Update → Notification → Release  

---

## 🚀 Installation

```bash
git clone https://github.com/your-username/ebarangay.git
cd ebarangay
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
```

---

## 🔑 Admin Account

Email: admin@ebarangay.test  
Password: password  

---

## 📚 Academic Project

BSIT Capstone Project  
Palawan State University – Quezon Campus  

---

## ⭐ Tagline

> Bringing barangay services online.
