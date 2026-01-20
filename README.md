# <div align="center"><img src="public/assets/img/branding/logo.png" alt="EMR Logo" width="80" height="auto"></div>

# Electronic Medical Record (EMR) System

A comprehensive, Lara-based Electronic Medical Record (EMR) system designed to streamline hospital operations, including patient management, clinical charting, laboratory & radiology requests, billing, and pharmacy management.

## Key Features

### 1. Patient Management
*   **Registration**: Capture comprehensive patient demographics, contact details, and next of kin information.
*   **Hospital Numbering**: Auto-generated hospital numbers with customizable prefixes.
*   **Patient Dashboard**: Centralized view of patient history, visits, and active requests.

### 2. Clinical Charting & Consultation
*   **Vitals Tracking**: Record and monitor patient vitals (BP, Temperature, Pulse, etc.) with historical trends.
*   **Complaint & Diagnosis**: Document presenting complaints and diagnoses (ICD-10 support).
*   **Clinical Notes**: Rich text editor for detailed clinical notes and treatment plans.
*   **Direct Ordering**: Order lab tests, radiology scans, and prescriptions directly from the consultation screen.

### 3. Laboratory Management
*   **Request Management**: Workflow for creating, processing, and completing lab requests.
*   **Result Entry**: dedicated interface for entering parameter-based results with auto-calculated remarks (High/Low/Normal).
*   **Approval Workflow**: Mandatory approval step for results before they can be printed.
*   **Branded Reports**: Professional, customizable print templates with hospital branding and digital signatures.

### 4. Radiology Management
*   **Request Tracking**: Manage radiology requests from order to completion.
*   **Rich Text Findings**: Trix editor integration for detailed, formatted radiology reports.
*   **DICOM Integration**: Launch external DICOM viewers (like Weasis) directly from the dashboard.
*   **Approval & Printing**: Secure approval workflow and professional report printing.

### 5. Billing & Finance
*   **Invoicing**: Generate bills for services (Consultation, Lab, Radiology, etc.).
*   **Payment Processing**: Record payments and issue receipts.
*   **Service Gating**: Enforce payment checks before services (e.g., Lab/Radiology findings) can be rendered.

### 6. Pharmacy & Inventory
*   **Drug Requests**: Manage prescriptions and dispensing.
*   **Stock Management**: Track drug inventory, batches, and expiration dates.

### 7. Administration & Settings
*   **Role-Based Access**: Granular permissions for Doctors, Nurses, Lab Scientists, Radiologists, and Admin.
*   **System Configuration**: Customize hospital name, logo, contact info (Email, Phone, Address), and prefixes.

## Technology Stack

*   **Framework**: [Laravel 11.x](https://laravel.com)
*   **Frontend**: Blade Templates, Livewire 3, Alpine.js, Bootstrap 5
*   **Database**: MySQL
*   **Rich Text Editor**: Trix / Summernote
*   **Charts**: Larapex Charts

## Installation

1.  **Clone the repository**
    ```bash
    git clone <repository-url>
    cd emr
    ```

2.  **Install PHP Dependencies**
    ```bash
    composer install
    ```

3.  **Install Node Dependencies**
    ```bash
    npm install && npm run build
    ```

4.  **Environment Setup**
    ```bash
    cp .env.example .env
    php artisan key:generate
    php artisan storage:link
    ```
    *   Configure your database credentials in the `.env` file.

5.  **Database Migration & Seeding**
    ```bash
    php artisan migrate
    php artisan db:seed
    ```

6.  **Run the Application**
    ```bash
    php artisan serve
    ```

## Usage

*   **Login**: Access the system via `/login`. Default admin credentials (if seeded).
*   **Dashboard**: Overview of daily activities.
*   **Sidebar**: Navigate to Patients, Appointments, Lab, Radiology, etc.

## Contributing

Please follow the standard pull request workflow:
1.  Fork the repository.
2.  Create a feature branch (`git checkout -b feature/amazing-feature`).
3.  Commit your changes (`git commit -m 'Add some amazing feature'`).
4.  Push to the branch (`git push origin feature/amazing-feature`).
5.  Open a Pull Request.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
