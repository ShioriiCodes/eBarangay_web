# 🏛️ eBarangay

**A Web-Based e-Governance and Document Requisition System for Alfonso XIII, Quezon, Palawan**

---

## 📌 Overview

eBarangay is a **web-based e-governance system** designed to improve the efficiency, accessibility, and transparency of barangay services in Alfonso XIII, Quezon, Palawan.

The system replaces manual, paper-based transactions with a **centralized digital platform**, allowing residents and barangay officials to manage document requests, concerns, and records through an online interface.

---

## 🎯 Objectives

- Digitize and centralize barangay transactions
- Streamline document request processing
- Improve service accessibility for residents
- Reduce waiting time and manual workload
- Provide real-time request status tracking
- Maintain organized and secure records

---

## 👥 User Roles

### 🔐 Admin / Barangay Secretary

- Full system control
- Manage document requests
- Approve, reject, and update request status
- Handle resident concerns
- Manage resident records
- Monitor system activities and reports

### 🏠 Residents

- Register and login to the system
- Request barangay documents
- Track request status
- Submit concerns or complaints
- Receive notifications and updates

---

## ⚙️ Core Features

### 📄 Document Requisition System

- Barangay Clearance
- Certificate of Residency
- Certificate of Indigency
- Barangay ID

### 🔄 Request Status Workflow

```text
Pending → Under Review → Approved → Ready for Claiming → Completed




### Other Features

- 📝 Concern Management System  
- 🔔 Notification System  
- 📁 Centralized Records Management  
- 📊 Admin Dashboard  
- 🔐 Role-Based Access Control  

---

## 🔐 Authentication & Security

- Laravel Authentication System  
- Role-based access for Admin and Resident  
- Secure password hashing  
- Password reset via email  
- Protected routes and middleware  
- Users can only access their own data  

---

## 🔑 Forgot Password System

eBarangay uses **Laravel’s built-in password reset functionality**.

### Reset Flow

1. User clicks **Forgot Password**  
2. User enters registered email  
3. User receives reset link via email  
4. User sets a new password securely  

---

## 🛠️ Technology Stack

| Layer        | Technology                     |
|-------------|-------------------------------|
| Backend     | Laravel (PHP)                 |
| Database    | MySQL                         |
| Frontend    | Blade (HTML, CSS, JavaScript) |
| Styling     | Tailwind CSS                  |
| Architecture| Client-Server                 |

---

## 🎨 UI Design

- **Primary Color:** Blue `#0038A8`  
- **Secondary Color:** Red `#CE1126`  
- Philippine-inspired government theme 🇵🇭  
- Clean and modern interface  
- Fully responsive (desktop, tablet, mobile)  

---

## 🖨️ Document Generation (Planned Feature)

- Auto-filled document templates  
- Printable and downloadable PDF files  
- Based on resident input and request data  
- Requires signature from authorized barangay personnel  

---

## 🔄 System Workflow

