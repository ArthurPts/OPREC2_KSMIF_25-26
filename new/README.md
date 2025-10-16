# 🎮 KSMIF - OPREC Game Besar

**Open Recruitment Game Besar KSMIF** - Platform challenge interaktif berbasis web dengan tema hacker retro 90-an yang melibatkan dekripsi AES dan penyusunan puzzle kode. Dibangun dengan Laravel 12, menampilkan estetika terminal CRT lengkap dengan efek scanline dan glow.

## 🏆 Tentang KSMIF OPREC

**Kelompok Studi Mahasiswa Informatika (KSMIF)** menghadirkan challenge Open Recruitment yang menguji kemampuan:
- 🧠 **Problem Solving** - Memecahkan teka-teki kompleks
- 🔐 **Kriptografi** - Mendekripsi pesan terenkripsi AES
- 🎯 **Logical Thinking** - Menyusun fragmen kode dengan urutan tepat
- ⚡ **Speed & Accuracy** - Berlomba dengan waktu untuk menyelesaikan challenge

## 🎮 RetroTerm Theme

Platform ini menggunakan **RetroTerm** - tema visual yang terinspirasi dari:
- 🖥️ Terminal komputer tahun 90-an dengan efek CRT authentic
- 💙 Warna cyan glow (#00f6ff) khas monitor retro
- 🎯 Estetika hacker dari era cyberpunk
- ✨ Font pixel ("Press Start 2P") untuk nuansa retro gaming
- 📟 Scanline effect dan screen flicker untuk pengalaman immersive

## 🎯 Mekanisme Challenge

1. **Dekripsi AES** - Tim mendekripsi ciphertext menggunakan key rahasia yang diberikan
2. **Submit Fragmen** - Hasil dekripsi disubmit ke sistem untuk diverifikasi
3. **Verifikasi Panitia** - Panitia memvalidasi dan memilih submission terbaik per tim
4. **Penyusunan Puzzle** - Drag & drop fragmen ke urutan yang tepat menggunakan interface interaktif
5. **Validasi Akhir** - Sistem memvalidasi urutan dan menampilkan hasil final

## 🚀 Tech Stack

- **Backend:** PHP 8.2+, Laravel 12
- **Database:** SQLite (dapat diganti MySQL/PostgreSQL)
- **Frontend:** Tailwind CSS 3.x, Alpine.js, Vite
- **Libraries:** CryptoJS, SortableJS, DOMPurify

## 📦 Instalasi

### Prerequisites

- PHP >= 8.2
- Composer
- Node.js & NPM

### Setup Project

```bash
# Clone repository
git clone <repository-url>
cd puzzle-game

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Setup database
touch database/database.sqlite
php artisan migrate --seed

# Build assets
npm run build

# Run development server
php artisan serve
```

Atau gunakan composer script:

```bash
composer setup
```

### Development Mode

```bash
# Run all services (server, queue, logs, vite)
composer dev
```

## 👤 Default Credentials

Setelah seeding:

- **Operator KSMIF:**
  - Email: `operator@ksmif.test`
  - Password: `password`

- **Test User:**
  - Email: `test@example.com`
  - Password: `password`

## 🏗️ Struktur Project (Clean Architecture)

```
app/
├── Http/
│   ├── Controllers/          # Controllers (thin, delegates to services)
│   ├── Requests/             # Form Request Validation
│   ├── Resources/            # API Resources
│   └── Middleware/           # Custom Middleware
├── Models/                   # Eloquent Models dengan Scopes
├── Services/                 # Business Logic
│   ├── SubmissionService.php
│   └── PuzzleValidationService.php
└── Policies/                 # Authorization Policies

database/
├── migrations/               # Database Schema
└── seeders/                  # Data Seeders

resources/
└── views/
    ├── components/           # Reusable Blade Components
    │   ├── alert.blade.php
    │   ├── badge.blade.php
    │   ├── button.blade.php
    │   ├── card.blade.php
    │   └── layout.blade.php
    └── [pages]/              # Page Views
```

## 🎨 Clean Code Improvements

### ✅ Apa yang Sudah Diterapkan:

1. **Separation of Concerns**
   - Controllers hanya handle HTTP request/response
   - Business logic dipindahkan ke Service classes
   - Validation dipisah ke Form Request classes

2. **SOLID Principles**
   - Single Responsibility: Setiap class punya satu tanggung jawab
   - Dependency Injection: Services di-inject via constructor
   - Interface Segregation: Policy per model

3. **DRY (Don't Repeat Yourself)**
   - Reusable Blade components
   - Model scopes untuk query reusability
   - Service methods untuk logic reuse

4. **Security Best Practices**
   - Form Request validation
   - Policy-based authorization
   - XSS prevention (DOMPurify)
   - CSRF protection
   - Content hashing untuk duplicate detection

5. **Database Optimization**
   - Proper indexing
   - Eager loading relationships
   - Query scopes
   - Eloquent accessors

6. **Code Quality**
   - Type hints & return types
   - DocBlocks untuk documentation
   - Consistent naming conventions
   - Meaningful variable/method names

## 📚 API Endpoints

### Public Routes
- `GET /` - Halaman dekripsi
- `GET /input` - Form submit potongan
- `POST /input` - Submit potongan baru

### Authenticated Routes (Operator)
- `GET /dashboard` - Dashboard operator
- `GET /operator/teams/{team}/confirm` - Konfirmasi potongan
- `POST /operator/teams/{team}/confirm` - Proses konfirmasi
- `GET /operator/teams/{team}/arrange` - Penyusunan puzzle
- `POST /operator/teams/{team}/check` - Validasi urutan

### Management Routes (CRUD - Operator Only)
- `GET|POST /operator/manage/teams` - CRUD Teams
- `GET|POST /operator/manage/snippets` - CRUD Snippets

## 🧪 Testing

```bash
# Run tests
composer test

# With coverage
php artisan test --coverage
```

## 🔧 Configuration

Edit `config/puzzle.php` untuk customize:

```php
'submissions' => [
    'per_page_default' => 10,
    'per_page_min' => 5,
    'per_page_max' => 50,
],

'operators' => [
    'default_email' => 'operator@ksmif.test',
],
```

## 📝 Usage Examples

### Menggunakan Service

```php
use App\Services\SubmissionService;

class SomeController extends Controller
{
    public function __construct(
        private readonly SubmissionService $submissionService
    ) {}

    public function index()
    {
        $submissions = $this->submissionService->getPaginatedSubmissions();
        return view('submissions.index', compact('submissions'));
    }
}
```

### Menggunakan Model Scopes

```php
// Get confirmed submissions
$confirmed = TeamSubmission::confirmed()->get();

// Search teams
$teams = Team::search($request->q)->paginate();

// Get snippets in order
$snippets = Snippet::ordered()->get();
```

### Menggunakan Blade Components

```blade
<x-layout title="Dashboard">
    <x-card title="Teams">
        <x-alert type="success">
            Data berhasil disimpan!
        </x-alert>
        
        <x-button variant="primary">
            Submit
        </x-button>
        
        <x-badge variant="success">
            Confirmed
        </x-badge>
    </x-card>
</x-layout>
```

## 🐛 Debugging

Laravel menyediakan fitur debugging yang powerful:

```bash
# Watch logs
php artisan pail

# Clear cache
php artisan optimize:clear

# Check routes
php artisan route:list
```

## 📖 Documentation

- [Laravel Documentation](https://laravel.com/docs)
- [Tailwind CSS](https://tailwindcss.com/docs)
- [Alpine.js](https://alpinejs.dev/start-here)

## 🤝 Contributing

Project ini dibuat untuk **KSMIF (Kelompok Studi Mahasiswa Informatika)** sebagai platform Open Recruitment Game Besar. Contributions are welcome!

## 👥 Tim Developer

Dikembangkan dengan dedikasi oleh tim KSMIF untuk menciptakan pengalaman Open Recruitment yang memorable dan menantang.

## 📄 License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

**Built with ❤️ by KSMIF - Kelompok Studi Mahasiswa Informatika**

*"Challenge Yourself, Prove Your Worth"*
