<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Book;
use App\Models\Loan;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear usuarios de prueba
        $this->createUsers();

        // Crear categorías
        $this->createCategories();

        // Crear libros
        $this->createBooks();

        // Crear algunos préstamos de ejemplo
        $this->createSampleLoans();
    }

    /**
     * Create test users
     */
    private function createUsers(): void
    {
        // Admin
        User::create([
            'name' => 'Administrador Sistema',
            'email' => 'admin@bibliotecaunmsm.edu.pe',
            'password' => Hash::make('password123'),
            'student_code' => 'ADM-2025-001',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Bibliotecario
        User::create([
            'name' => 'María González',
            'email' => 'bibliotecario@bibliotecaunmsm.edu.pe',
            'password' => Hash::make('password123'),
            'student_code' => 'LIB-2025-001',
            'role' => 'librarian',
            'email_verified_at' => now(),
        ]);

        // Estudiantes de prueba
        $students = [
            [
                'name' => 'Juan Carlos Pérez',
                'email' => 'juan.perez@UNMSM.pe',
                'student_code' => 'EST-2025-001',
            ],
            [
                'name' => 'Ana María Rodriguez',
                'email' => 'ana.rodriguez@UNMSM.pe',
                'student_code' => 'EST-2025-002',
            ],
            [
                'name' => 'Carlos Eduardo Silva',
                'email' => 'carlos.silva@UNMSM.pe',
                'student_code' => 'EST-2025-003',
            ],
            [
                'name' => 'Lucia Fernanda Torres',
                'email' => 'lucia.torres@UNMSM.pe',
                'student_code' => 'EST-2025-004',
            ],
            [
                'name' => 'Miguel Angel Vargas',
                'email' => 'miguel.vargas@UNMSM.pe',
                'student_code' => 'EST-2025-005',
            ],
        ];

        foreach ($students as $student) {
            User::create([
                'name' => $student['name'],
                'email' => $student['email'],
                'password' => Hash::make('password123'),
                'student_code' => $student['student_code'],
                'role' => 'student',
                'email_verified_at' => now(),
            ]);
        }
    }

    /**
     * Create book categories
     */
    private function createCategories(): void
    {
        $categories = [
            [
                'name' => 'Ingeniería de Software',
                'description' => 'Libros relacionados con desarrollo de software, metodologías ágiles y gestión de proyectos.'
            ],
            [
                'name' => 'Programación',
                'description' => 'Manuales y guías de lenguajes de programación y frameworks.'
            ],
            [
                'name' => 'Base de Datos',
                'description' => 'Diseño, administración y optimización de bases de datos.'
            ],
            [
                'name' => 'Redes y Seguridad',
                'description' => 'Configuración de redes, ciberseguridad y protocolos de comunicación.'
            ],
            [
                'name' => 'Inteligencia Artificial',
                'description' => 'Machine Learning, Deep Learning y algoritmos de IA.'
            ],
            [
                'name' => 'Matemáticas',
                'description' => 'Álgebra, cálculo, estadística y matemáticas discretas.'
            ],
            [
                'name' => 'Física',
                'description' => 'Física general, mecánica cuántica y electromagnetismo.'
            ],
            [
                'name' => 'Literatura',
                'description' => 'Obras literarias clásicas y contemporáneas.'
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }

    /**
     * Create sample books
     */
    private function createBooks(): void
    {
        $books = [
            // Ingeniería de Software
            [
                'title' => 'Clean Code: Manual de desarrollo ágil de software',
                'author' => 'Robert C. Martin',
                'isbn' => '978-0132350884',
                'description' => 'Una guía completa para escribir código limpio y mantenible.',
                'publisher' => 'Prentice Hall',
                'publication_date' => '2008-08-01',
                'pages' => 464,
                'language' => 'Español',
                'stock_quantity' => 5,
                'available_quantity' => 5,
                'location' => 'Estante A-01',
                'category_id' => 1,
            ],
            [
                'title' => 'The Pragmatic Programmer',
                'author' => 'Andrew Hunt, David Thomas',
                'isbn' => '978-0201616224',
                'description' => 'Tu viaje hacia la maestría en programación.',
                'publisher' => 'Addison-Wesley',
                'publication_date' => '1999-10-20',
                'pages' => 352,
                'language' => 'Inglés',
                'stock_quantity' => 3,
                'available_quantity' => 3,
                'location' => 'Estante A-02',
                'category_id' => 1,
            ],

            // Programación
            [
                'title' => 'JavaScript: The Definitive Guide',
                'author' => 'David Flanagan',
                'isbn' => '978-1491952023',
                'description' => 'La guía definitiva del lenguaje JavaScript.',
                'publisher' => "O'Reilly Media",
                'publication_date' => '2020-05-14',
                'pages' => 704,
                'language' => 'Inglés',
                'stock_quantity' => 4,
                'available_quantity' => 4,
                'location' => 'Estante B-01',
                'category_id' => 2,
            ],
            [
                'title' => 'Python Crash Course',
                'author' => 'Eric Matthes',
                'isbn' => '978-1593279288',
                'description' => 'Una introducción práctica a la programación en Python.',
                'publisher' => 'No Starch Press',
                'publication_date' => '2019-05-03',
                'pages' => 560,
                'language' => 'Español',
                'stock_quantity' => 6,
                'available_quantity' => 6,
                'location' => 'Estante B-02',
                'category_id' => 2,
            ],
            [
                'title' => 'Laravel: Up & Running',
                'author' => 'Matt Stauffer',
                'isbn' => '978-1492041207',
                'description' => 'Una introducción completa al framework Laravel.',
                'publisher' => "O'Reilly Media",
                'publication_date' => '2019-08-27',
                'pages' => 456,
                'language' => 'Inglés',
                'stock_quantity' => 3,
                'available_quantity' => 3,
                'location' => 'Estante B-03',
                'category_id' => 2,
            ],

            // Base de Datos
            [
                'title' => 'Database System Concepts',
                'author' => 'Abraham Silberschatz, Henry F. Korth',
                'isbn' => '978-0078022159',
                'description' => 'Conceptos fundamentales de sistemas de bases de datos.',
                'publisher' => 'McGraw-Hill',
                'publication_date' => '2019-02-05',
                'pages' => 1376,
                'language' => 'Inglés',
                'stock_quantity' => 4,
                'available_quantity' => 4,
                'location' => 'Estante C-01',
                'category_id' => 3,
            ],

            // Inteligencia Artificial
            [
                'title' => 'Hands-On Machine Learning',
                'author' => 'Aurélien Géron',
                'isbn' => '978-1492032649',
                'description' => 'Aprende machine learning con Scikit-Learn y TensorFlow.',
                'publisher' => "O'Reilly Media",
                'publication_date' => '2019-10-15',
                'pages' => 856,
                'language' => 'Inglés',
                'stock_quantity' => 5,
                'available_quantity' => 5,
                'location' => 'Estante D-01',
                'category_id' => 5,
            ],

            // Matemáticas
            [
                'title' => 'Cálculo de una Variable',
                'author' => 'James Stewart',
                'isbn' => '978-1285740621',
                'description' => 'Conceptos y contextos del cálculo diferencial e integral.',
                'publisher' => 'Cengage Learning',
                'publication_date' => '2015-01-01',
                'pages' => 920,
                'language' => 'Español',
                'stock_quantity' => 8,
                'available_quantity' => 8,
                'location' => 'Estante E-01',
                'category_id' => 6,
            ],
            [
                'title' => 'Álgebra Lineal y sus Aplicaciones',
                'author' => 'David C. Lay',
                'isbn' => '978-0321982384',
                'description' => 'Una introducción moderna al álgebra lineal.',
                'publisher' => 'Pearson',
                'publication_date' => '2015-12-25',
                'pages' => 576,
                'language' => 'Español',
                'stock_quantity' => 6,
                'available_quantity' => 6,
                'location' => 'Estante E-02',
                'category_id' => 6,
            ],

            // Literatura
            [
                'title' => 'Cien Años de Soledad',
                'author' => 'Gabriel García Márquez',
                'isbn' => '978-0307474728',
                'description' => 'Una obra maestra del realismo mágico.',
                'publisher' => 'Vintage Español',
                'publication_date' => '1967-06-05',
                'pages' => 417,
                'language' => 'Español',
                'stock_quantity' => 4,
                'available_quantity' => 4,
                'location' => 'Estante F-01',
                'category_id' => 8,
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }

    /**
     * Create sample loans
     */
    private function createSampleLoans(): void
    {
        $students = User::where('role', 'student')->get();
        $books = Book::all();

        // Crear algunos préstamos activos
        Loan::create([
            'loan_code' => 'L000001',
            'user_id' => $students->first()->id,
            'book_id' => $books->first()->id,
            'loan_date' => Carbon::now()->subDays(5),
            'due_date' => Carbon::now()->addDays(9),
            'status' => 'active',
            'notes' => 'Préstamo para proyecto de tesis',
        ]);

        // Actualizar disponibilidad del libro
        $books->first()->decrement('available_quantity');

        // Préstamo vencido
        if ($students->count() > 1 && $books->count() > 1) {
            Loan::create([
                'loan_code' => 'L000002',
                'user_id' => $students->skip(1)->first()->id,
                'book_id' => $books->skip(1)->first()->id,
                'loan_date' => Carbon::now()->subDays(20),
                'due_date' => Carbon::now()->subDays(6),
                'status' => 'active',
                'notes' => 'Préstamo para estudio personal',
            ]);

            $books->skip(1)->first()->decrement('available_quantity');
        }

        // Préstamo devuelto
        if ($students->count() > 2 && $books->count() > 2) {
            Loan::create([
                'loan_code' => 'L000003',
                'user_id' => $students->skip(2)->first()->id,
                'book_id' => $books->skip(2)->first()->id,
                'loan_date' => Carbon::now()->subDays(30),
                'due_date' => Carbon::now()->subDays(16),
                'return_date' => Carbon::now()->subDays(18),
                'status' => 'returned',
                'notes' => 'Préstamo completado exitosamente',
            ]);
        }
    }
}
