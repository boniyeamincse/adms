# Admit Card Management System

A comprehensive Laravel-based web application for managing school student data, examinations, fees, and admit card generation. Supports academic levels from Nursery to Class 12 with role-based access control.

## 🚀 Features

### Core Modules
- **Student Management** - CRUD operations, bulk import via Excel, search/filter by class/section
- **Class & Section Management** - Organize students into classes and sections with subject assignments
- **Exam Management** - Create standard/custom exams, assign subjects, set schedules
- **Admit Card Generation** - Auto-generate PDF admit cards with student and exam details
- **Fee Management** - Track fees, payments, generate receipts, integrate with admit card access
- **Dashboard & Reporting** - Interactive dashboard with metrics, charts, and exportable reports

### User Roles & Access Control
- **Super Admin** - Full system access and management
- **Teacher** - Exam management, student evaluation, mark entry
- **Accountant** - Fee and payment management
- **Student** - Admit card download access (optional)

### Additional Features
- Bulk student import/export via Excel
- PDF generation for admit cards and receipts using DomPDF
- Interactive charts and data visualization
- Responsive design with Tailwind CSS
- File upload validation for photos and documents
- CSRF protection and encrypted passwords

## 🛠️ Tech Stack

- **Backend**: Laravel 11.x Framework
- **Frontend**: Alpine.js, Tailwind CSS
- **Database**: SQLite (production-ready, easily switchable to MySQL/PostgreSQL)
- **Build Tool**: Vite
- **Authentication**: Laravel Breeze
- **PDF Generation**: DomPDF (barryvdh/laravel-dompdf)
- **Excel Import/Export**: Laravel Excel (maatwebsite/excel)
- **Charts**: Chart.js with Laravel integration

## 📋 Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js & NPM
- XAMPP/Apache server (for production deployment)

## 🚀 Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/your-repo/admit-card-system.git
   cd admit-card-system
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Build assets**
   ```bash
   npm run build
   ```

7. **Start the development server**
   ```bash
   php artisan serve --host=127.0.0.1 --port=8080
   ```

## 📊 Database Schema

The application uses the following main tables:
- `users` - User accounts with role management
- `students` - Student profiles and information
- `classes` - Class definitions and sections
- `sections` - Section assignments within classes
- `subjects` - Subject allocations
- `exams` - Exam definitions and schedules
- `admit_cards` - Generated admit card records
- `fees` - Fee structures and tracking
- `payments` - Payment records

For detailed migration structure, refer to the `database/migrations/` directory.

## 🔒 Authentication & Security

- Role-based access control using middleware
- CSRF protection on all forms
- Password encryption with bcrypt
- File upload validation and sanitization

## 📱 Usage

1. **Login** - Access the application with appropriate credentials based on your role
2. **Student Management** - Add, edit, or bulk import students
3. **Exam Creation** - Create new exams and assign classes/subjects
4. **Admit Card Generation** - Generate downloadable PDF admit cards
5. **Fee Management** - Track and manage student fees
6. **Reports** - Export data reports in Excel/PDF format

## 📦 Deployment

1. **Production server setup** - Ensure PHP 8.2+, MySQL/PostgreSQL server
2. **Environment configuration** - Update `.env` with production database credentials
3. **Asset compilation** - Run `npm run build` and upload compiled assets
4. **Database migration** - Run `php artisan migrate` on the server
5. **File permissions** - Set write permissions for `storage/` and `bootstrap/cache/`
6. **Web server configuration** - Point domain to `public/` directory

## 🧪 Testing

### Unit & Feature Tests
Run the test suite with:
```bash
php artisan test
```

### Manual Testing

#### Test Users Created
The application comes with pre-seeded test users for all available roles. Use the following credentials for testing:

| Role | Email | Password | Access Level |
|------|-------|----------|-------------|
| **Super Admin** | `admin@test.com` | `admin123` | Full system access |
| **Super Admin** | `superadmin@example.com` | `password` | Full system access |
| **Teacher** | `teacher@test.com` | `teacher123` | Exam management, student evaluation |
| **Teacher** | `teacher@example.com` | `password` | Exam management, student evaluation |
| **Teacher** | `teacher2@example.com` | `password` | Exam management, student evaluation |
| **Accountant** | `accountant@test.com` | `accountant123` | Fee and payment management |
| **Accountant** | `accountant@example.com` | `password` | Fee and payment management |
| **Accountant** | `accountant2@example.com` | `password` | Fee and payment management |
| **Student/Cadet** | `cadet@test.com` | `cadet123` | Admit card access |
| **Student/Cadet** | `cadet@example.com` | `password` | Admit card access |
| **Student** | `student@example.com` | `password` | Admit card access |

#### Testing Dashboard
1. Start the development server: `php artisan serve --host=127.0.0.1 --port=8080`
2. Navigate to: http://127.0.0.1:8080/dashboard
3. Login with any of the test user credentials above
4. Check dashboard features based on user role:
   - **Super Admin**: Full access to all modules
   - **Teacher**: Dashboard, Students, Exams, Admit Cards
   - **Accountant**: Dashboard, Fees, Payments
   - **Student**: Dashboard, Personal Admit Cards

#### Module-Specific Testing

**Student Management Testing:**
```bash
# Login as teacher or admin
# Navigate to /students
# Test: Create, Edit, Delete students
# Test: Bulk import/export
# Test: Search/filter functionality
```

**Exam Management Testing:**
```bash
# Login as admin or teacher
# Navigate to /exams
# Test: Create new exams
# Test: Assign subjects and classes
# Test: Generate admit cards for exam
```

**Fee Management Testing:**
```bash
# Login as admin or accountant
# Navigate to /fees
# Test: Create fees for classes
# Test: Mark fees as paid/unpaid
# Test: Generate receipts
```

**Admit Card Testing:**
```bash
# Login as student
# Navigate to admit cards section
# Test: Download PDF admit cards
```

#### Database Migration Testing
```bash
# Reset and re-seed database (removes all data)
php artisan migrate:fresh --seed

# Run seeders individually
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=ClassSeeder
php artisan db:seed --class=SectionSeeder
php artisan db:seed --class=StudentSeeder
```

#### Browser Testing
Recommended browsers for testing:
- Google Chrome (latest)
- Mozilla Firefox (latest)
- Microsoft Edge (latest)

#### API Testing
Test AJAX endpoints using browser developer tools or tools like Postman:
```bash
# Dashboard chart data
GET /dashboard/chart-data?type=monthly-fees

# Statistics API
GET /dashboard/stats-api
```

#### Performance Testing
- Test with bulk data: Create 100+ students via seeding
- Test PDF generation under load
- Test search/filter performance with large datasets

## 📂 Project Structure

```
admit-card-system/
├── app/
│   ├── Http/Controllers/     # Controllers for all modules
│   ├── Models/              # Eloquent models
│   └── Providers/           # Service providers
├── database/
│   ├── migrations/          # Database migrations
│   └── seeders/            # Database seeders
├── public/                  # Public assets
├── resources/
│   ├── views/              # Blade templates
│   └── css/                # Stylesheets
├── routes/
│   └── web.php             # Route definitions
└── tests/                  # Test files
```

## 🚧 Development

- **Assets**: Run `npm run dev` for hot reloading during development
- **Code Quality**: Uses Laravel Pint for code formatting
- **PHPStan**: Static analysis with `php artisan phpstan:analyze`

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

For support, email support@example.com or create an issue in the repository.

---

**Built with Laravel Framework**
