🏛️ eBarangay

A Web-Based e-Governance and Document Requisition System for Alfonso XIII, Quezon, Palawan

📌 Overview

eBarangay is a web-based e-governance system designed to improve the efficiency, accessibility, and transparency of barangay services in Alfonso XIII, Quezon, Palawan.

The system replaces manual, paper-based transactions with a centralized digital platform, allowing residents and barangay officials to manage document requests, concerns, and records through an online interface.

🎯 Objectives
Digitize and centralize barangay transactions
Streamline document request processing
Improve service accessibility for residents
Reduce waiting time and manual workload
Provide real-time request status tracking
Maintain organized and secure records
👥 User Roles
🔐 Admin / Barangay Secretary
Full system control
Manage document requests (approve/reject/update status)
Handle resident concerns
Manage resident records
Monitor system activities and reports
🏠 Residents
Register and login to the system
Request barangay documents
Track request status
Submit concerns or complaints
Receive notifications and updates
⚙️ Core Features
📄 Document Requisition System
Barangay Clearance
Certificate of Residency
Certificate of Indigency
Barangay ID
🔄 Request Status Workflow
Pending → Under Review → Approved → Ready for Claiming → Completed
📝 Concern Management System
🔔 Notification System
📁 Centralized Records Management
📊 Admin Dashboard (Monitoring & Reports)
🔐 Authentication & Security
Laravel Authentication System
Role-based access (Admin, Resident)
Secure password hashing
Password reset via email
Protected routes and middleware
Users can only access their own data
🔑 Forgot Password System

eBarangay uses Laravel’s built-in password reset functionality.

🔁 Reset Flow
User clicks Forgot Password
Enters registered email
Receives reset link via email
Sets a new password securely
🛠️ Technology Stack
Layer	Technology
Backend	Laravel (PHP)
Database	MySQL
Frontend	Blade (HTML, CSS, JS)
Styling	Tailwind CSS
Architecture	Client-Server
🎨 UI Design
Primary Color: Blue #0038A8
Secondary Color: Red #CE1126
Philippine-inspired government theme 🇵🇭
Clean and modern interface
Fully responsive (desktop, tablet, mobile)
🖨️ Document Generation (Planned Feature)
Auto-filled document templates
Printable and downloadable PDF files
Based on resident input and request data
Requires signature from authorized barangay personnel
🔄 System Workflow
Resident registers/logs in
Submits document request
Admin reviews and processes request
System updates request status
Resident receives notification
Document is prepared and released
📁 Project Structure
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
├── web.php
🚀 Installation
git clone https://github.com/your-username/ebarangay.git
cd ebarangay
composer install
cp .env.example .env
php artisan key:generate

Configure your database, then:

php artisan migrate:fresh --seed
php artisan serve
🔑 Default Admin Account
Email: admin@ebarangay.test
Password: password
📚 Academic Context

This system is developed as a BS Information Technology Capstone Project
for Palawan State University – Quezon Campus

💡 Note

This project is intended for academic purposes and may be further enhanced for real-world deployment.

⭐ Tagline

Bringing barangay services closer to the community through digital transformation.
