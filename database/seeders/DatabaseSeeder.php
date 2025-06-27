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

        // Crear libros (expandido)
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
            ['name' => 'Juan Carlos Pérez', 'email' => 'juan.perez@unmsm.edu.pe', 'student_code' => 'EST-2025-001'],
            ['name' => 'Ana María Rodriguez', 'email' => 'ana.rodriguez@unmsm.edu.pe', 'student_code' => 'EST-2025-002'],
            ['name' => 'Carlos Eduardo Silva', 'email' => 'carlos.silva@unmsm.edu.pe', 'student_code' => 'EST-2025-003'],
            ['name' => 'Lucia Fernanda Torres', 'email' => 'lucia.torres@unmsm.edu.pe', 'student_code' => 'EST-2025-004'],
            ['name' => 'Miguel Angel Vargas', 'email' => 'miguel.vargas@unmsm.edu.pe', 'student_code' => 'EST-2025-005'],
            ['name' => 'Sofia Isabella Lopez', 'email' => 'sofia.lopez@unmsm.edu.pe', 'student_code' => 'EST-2025-006'],
            ['name' => 'Diego Fernando Castro', 'email' => 'diego.castro@unmsm.edu.pe', 'student_code' => 'EST-2025-007'],
            ['name' => 'Valentina Paz Morales', 'email' => 'valentina.morales@unmsm.edu.pe', 'student_code' => 'EST-2025-008'],
            ['name' => 'Sebastian Alejandro Ruiz', 'email' => 'sebastian.ruiz@unmsm.edu.pe', 'student_code' => 'EST-2025-009'],
            ['name' => 'Camila Victoria Herrera', 'email' => 'camila.herrera@unmsm.edu.pe', 'student_code' => 'EST-2025-010'],
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
     * Create sample books (50+ libros)
     */
    private function createBooks(): void
    {
        $books = [
            // INGENIERÍA DE SOFTWARE (Categoría 1) - 8 libros
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
                'available_quantity' => 4,
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
            [
                'title' => 'Design Patterns: Elements of Reusable Object-Oriented Software',
                'author' => 'Erich Gamma, Richard Helm, Ralph Johnson, John Vlissides',
                'isbn' => '978-0201633610',
                'description' => 'Los patrones de diseño fundamentales en programación orientada a objetos.',
                'publisher' => 'Addison-Wesley',
                'publication_date' => '1994-10-21',
                'pages' => 395,
                'language' => 'Inglés',
                'stock_quantity' => 4,
                'available_quantity' => 4,
                'location' => 'Estante A-03',
                'category_id' => 1,
            ],
            [
                'title' => 'Refactoring: Improving the Design of Existing Code',
                'author' => 'Martin Fowler',
                'isbn' => '978-0134757599',
                'description' => 'Técnicas para mejorar el diseño de código existente.',
                'publisher' => 'Addison-Wesley',
                'publication_date' => '2018-11-20',
                'pages' => 448,
                'language' => 'Inglés',
                'stock_quantity' => 3,
                'available_quantity' => 3,
                'location' => 'Estante A-04',
                'category_id' => 1,
            ],
            [
                'title' => 'Code Complete',
                'author' => 'Steve McConnell',
                'isbn' => '978-0735619678',
                'description' => 'Una guía práctica para la construcción de software.',
                'publisher' => 'Microsoft Press',
                'publication_date' => '2004-06-09',
                'pages' => 960,
                'language' => 'Español',
                'stock_quantity' => 2,
                'available_quantity' => 2,
                'location' => 'Estante A-05',
                'category_id' => 1,
            ],
            [
                'title' => 'Agile Software Development',
                'author' => 'Robert C. Martin',
                'isbn' => '978-0135974445',
                'description' => 'Principios, patrones y prácticas ágiles.',
                'publisher' => 'Pearson',
                'publication_date' => '2002-10-25',
                'pages' => 552,
                'language' => 'Inglés',
                'stock_quantity' => 3,
                'available_quantity' => 3,
                'location' => 'Estante A-06',
                'category_id' => 1,
            ],
            [
                'title' => 'Software Engineering: A Practitioner\'s Approach',
                'author' => 'Roger Pressman',
                'isbn' => '978-0078022128',
                'description' => 'Un enfoque práctico de la ingeniería de software.',
                'publisher' => 'McGraw-Hill',
                'publication_date' => '2014-01-16',
                'pages' => 976,
                'language' => 'Español',
                'stock_quantity' => 4,
                'available_quantity' => 4,
                'location' => 'Estante A-07',
                'category_id' => 1,
            ],
            [
                'title' => 'Scrum: The Art of Doing Twice the Work in Half the Time',
                'author' => 'Jeff Sutherland',
                'isbn' => '978-0385346450',
                'description' => 'Metodología Scrum para gestión ágil de proyectos.',
                'publisher' => 'Crown Business',
                'publication_date' => '2014-09-30',
                'pages' => 248,
                'language' => 'Español',
                'stock_quantity' => 5,
                'available_quantity' => 5,
                'location' => 'Estante A-08',
                'category_id' => 1,
            ],

            // PROGRAMACIÓN (Categoría 2) - 12 libros
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
                'available_quantity' => 3,
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
            [
                'title' => 'React: Up & Running',
                'author' => 'Stoyan Stefanov',
                'isbn' => '978-1491931820',
                'description' => 'Construye aplicaciones web con React.',
                'publisher' => "O'Reilly Media",
                'publication_date' => '2016-07-08',
                'pages' => 222,
                'language' => 'Inglés',
                'stock_quantity' => 4,
                'available_quantity' => 4,
                'location' => 'Estante B-04',
                'category_id' => 2,
            ],
            [
                'title' => 'Node.js Design Patterns',
                'author' => 'Mario Casciaro, Luciano Mammino',
                'isbn' => '978-1785885587',
                'description' => 'Patrones de diseño para aplicaciones Node.js escalables.',
                'publisher' => 'Packt Publishing',
                'publication_date' => '2020-07-29',
                'pages' => 664,
                'language' => 'Inglés',
                'stock_quantity' => 2,
                'available_quantity' => 2,
                'location' => 'Estante B-05',
                'category_id' => 2,
            ],
            [
                'title' => 'Java: The Complete Reference',
                'author' => 'Herbert Schildt',
                'isbn' => '978-1260440232',
                'description' => 'La referencia completa de Java.',
                'publisher' => 'McGraw-Hill',
                'publication_date' => '2020-03-27',
                'pages' => 1248,
                'language' => 'Español',
                'stock_quantity' => 5,
                'available_quantity' => 5,
                'location' => 'Estante B-06',
                'category_id' => 2,
            ],
            [
                'title' => 'C++ Primer',
                'author' => 'Stanley Lippman, Josée Lajoie, Barbara Moo',
                'isbn' => '978-0321714114',
                'description' => 'Una introducción completa al lenguaje C++.',
                'publisher' => 'Addison-Wesley',
                'publication_date' => '2012-08-06',
                'pages' => 976,
                'language' => 'Inglés',
                'stock_quantity' => 3,
                'available_quantity' => 3,
                'location' => 'Estante B-07',
                'category_id' => 2,
            ],
            [
                'title' => 'Eloquent JavaScript',
                'author' => 'Marijn Haverbeke',
                'isbn' => '978-1593279509',
                'description' => 'Una introducción moderna a la programación.',
                'publisher' => 'No Starch Press',
                'publication_date' => '2018-12-04',
                'pages' => 472,
                'language' => 'Español',
                'stock_quantity' => 4,
                'available_quantity' => 4,
                'location' => 'Estante B-08',
                'category_id' => 2,
            ],
            [
                'title' => 'Go Programming Language',
                'author' => 'Alan Donovan, Brian Kernighan',
                'isbn' => '978-0134190440',
                'description' => 'La guía definitiva del lenguaje Go.',
                'publisher' => 'Addison-Wesley',
                'publication_date' => '2015-11-05',
                'pages' => 380,
                'language' => 'Inglés',
                'stock_quantity' => 2,
                'available_quantity' => 2,
                'location' => 'Estante B-09',
                'category_id' => 2,
            ],
            [
                'title' => 'Vue.js: Up and Running',
                'author' => 'Callum Macrae',
                'isbn' => '978-1491997249',
                'description' => 'Construye aplicaciones web accesibles con Vue.js.',
                'publisher' => "O'Reilly Media",
                'publication_date' => '2018-02-26',
                'pages' => 176,
                'language' => 'Inglés',
                'stock_quantity' => 3,
                'available_quantity' => 3,
                'location' => 'Estante B-10',
                'category_id' => 2,
            ],
            [
                'title' => 'Angular: Up and Running',
                'author' => 'Shyam Seshadri, Brad Green',
                'isbn' => '978-1491999837',
                'description' => 'Aprende a construir aplicaciones web con Angular.',
                'publisher' => "O'Reilly Media",
                'publication_date' => '2018-02-02',
                'pages' => 300,
                'language' => 'Inglés',
                'stock_quantity' => 3,
                'available_quantity' => 3,
                'location' => 'Estante B-11',
                'category_id' => 2,
            ],
            [
                'title' => 'Django for Beginners',
                'author' => 'William Vincent',
                'isbn' => '978-1735467207',
                'description' => 'Construye sitios web con Python y Django.',
                'publisher' => 'WelcomeToCode',
                'publication_date' => '2022-05-04',
                'pages' => 356,
                'language' => 'Inglés',
                'stock_quantity' => 4,
                'available_quantity' => 4,
                'location' => 'Estante B-12',
                'category_id' => 2,
            ],

            // BASE DE DATOS (Categoría 3) - 6 libros
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
                'available_quantity' => 3,
                'location' => 'Estante C-01',
                'category_id' => 3,
            ],
            [
                'title' => 'MongoDB: The Definitive Guide',
                'author' => 'Kristina Chodorow, Michael Dirolf',
                'isbn' => '978-1449344689',
                'description' => 'Guía completa de MongoDB.',
                'publisher' => "O'Reilly Media",
                'publication_date' => '2013-05-19',
                'pages' => 432,
                'language' => 'Inglés',
                'stock_quantity' => 3,
                'available_quantity' => 3,
                'location' => 'Estante C-02',
                'category_id' => 3,
            ],
            [
                'title' => 'PostgreSQL: Up and Running',
                'author' => 'Regina Obe, Leo Hsu',
                'isbn' => '978-1491963418',
                'description' => 'Una guía práctica de PostgreSQL.',
                'publisher' => "O'Reilly Media",
                'publication_date' => '2017-01-06',
                'pages' => 274,
                'language' => 'Inglés',
                'stock_quantity' => 3,
                'available_quantity' => 3,
                'location' => 'Estante C-03',
                'category_id' => 3,
            ],
            [
                'title' => 'MySQL Cookbook',
                'author' => 'Paul DuBois',
                'isbn' => '978-1449374020',
                'description' => 'Soluciones para desarrolladores de bases de datos.',
                'publisher' => "O'Reilly Media",
                'publication_date' => '2014-08-26',
                'pages' => 868,
                'language' => 'Inglés',
                'stock_quantity' => 2,
                'available_quantity' => 2,
                'location' => 'Estante C-04',
                'category_id' => 3,
            ],
            [
                'title' => 'Redis in Action',
                'author' => 'Josiah Carlson',
                'isbn' => '978-1617290855',
                'description' => 'Estructuras de datos en memoria con Redis.',
                'publisher' => 'Manning Publications',
                'publication_date' => '2013-06-28',
                'pages' => 320,
                'language' => 'Inglés',
                'stock_quantity' => 2,
                'available_quantity' => 2,
                'location' => 'Estante C-05',
                'category_id' => 3,
            ],
            [
                'title' => 'SQL Performance Explained',
                'author' => 'Markus Winand',
                'isbn' => '978-3950307825',
                'description' => 'Todo lo que los desarrolladores necesitan saber sobre SQL.',
                'publisher' => 'Markus Winand',
                'publication_date' => '2012-12-01',
                'pages' => 204,
                'language' => 'Inglés',
                'stock_quantity' => 2,
                'available_quantity' => 2,
                'location' => 'Estante C-06',
                'category_id' => 3,
            ],

            // REDES Y SEGURIDAD (Categoría 4) - 5 libros
            [
                'title' => 'Computer Networking: A Top-Down Approach',
                'author' => 'James Kurose, Keith Ross',
                'isbn' => '978-0133594140',
                'description' => 'Un enfoque descendente a las redes de computadoras.',
                'publisher' => 'Pearson',
                'publication_date' => '2016-03-05',
                'pages' => 864,
                'language' => 'Español',
                'stock_quantity' => 4,
                'available_quantity' => 4,
                'location' => 'Estante D-01',
                'category_id' => 4,
            ],
            [
                'title' => 'The Web Application Hacker\'s Handbook',
                'author' => 'Dafydd Stuttard, Marcus Pinto',
                'isbn' => '978-1118026472',
                'description' => 'Técnicas de hacking en aplicaciones web.',
                'publisher' => 'Wiley',
                'publication_date' => '2011-09-27',
                'pages' => 912,
                'language' => 'Inglés',
                'stock_quantity' => 2,
                'available_quantity' => 2,
                'location' => 'Estante D-02',
                'category_id' => 4,
            ],
            [
                'title' => 'Network Security Essentials',
                'author' => 'William Stallings',
                'isbn' => '978-0134527338',
                'description' => 'Fundamentos de seguridad en redes.',
                'publisher' => 'Pearson',
                'publication_date' => '2016-03-18',
                'pages' => 464,
                'language' => 'Español',
                'stock_quantity' => 3,
                'available_quantity' => 3,
                'location' => 'Estante D-03',
                'category_id' => 4,
            ],
            [
                'title' => 'Cryptography and Network Security',
                'author' => 'William Stallings',
                'isbn' => '978-0134444284',
                'description' => 'Principios y práctica de la criptografía.',
                'publisher' => 'Pearson',
                'publication_date' => '2016-02-26',
                'pages' => 766,
                'language' => 'Inglés',
                'stock_quantity' => 3,
                'available_quantity' => 3,
                'location' => 'Estante D-04',
                'category_id' => 4,
            ],
            [
                'title' => 'Ethical Hacking and Penetration Testing Guide',
                'author' => 'Rafay Baloch',
                'isbn' => '978-1482231618',
                'description' => 'Guía completa de hacking ético.',
                'publisher' => 'CRC Press',
                'publication_date' => '2014-09-03',
                'pages' => 536,
                'language' => 'Inglés',
                'stock_quantity' => 2,
                'available_quantity' => 2,
                'location' => 'Estante D-05',
                'category_id' => 4,
            ],

            // INTELIGENCIA ARTIFICIAL (Categoría 5) - 6 libros
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
                'available_quantity' => 4,
                'location' => 'Estante E-01',
                'category_id' => 5,
            ],
            [
                'title' => 'Deep Learning',
                'author' => 'Ian Goodfellow, Yoshua Bengio, Aaron Courville',
                'isbn' => '978-0262035613',
                'description' => 'Una introducción completa al deep learning.',
                'publisher' => 'MIT Press',
                'publication_date' => '2016-11-18',
                'pages' => 800,
                'language' => 'Inglés',
                'stock_quantity' => 3,
                'available_quantity' => 3,
                'location' => 'Estante E-02',
                'category_id' => 5,
            ],
            [
                'title' => 'Artificial Intelligence: A Modern Approach',
                'author' => 'Stuart Russell, Peter Norvig',
                'isbn' => '978-0134610993',
                'description' => 'Un enfoque moderno a la inteligencia artificial.',
                'publisher' => 'Pearson',
                'publication_date' => '2020-04-28',
                'pages' => 1136,
                'language' => 'Español',
                'stock_quantity' => 4,
                'available_quantity' => 4,
                'location' => 'Estante E-03',
                'category_id' => 5,
            ],
            [
                'title' => 'Python Machine Learning',
                'author' => 'Sebastian Raschka, Vahid Mirjalili',
                'isbn' => '978-1789955750',
                'description' => 'Machine learning y deep learning con Python.',
                'publisher' => 'Packt Publishing',
                'publication_date' => '2019-12-12',
                'pages' => 772,
                'language' => 'Inglés',
                'stock_quantity' => 4,
                'available_quantity' => 4,
                'location' => 'Estante E-04',
                'category_id' => 5,
            ],
            [
                'title' => 'Pattern Recognition and Machine Learning',
                'author' => 'Christopher Bishop',
                'isbn' => '978-0387310732',
                'description' => 'Reconocimiento de patrones y aprendizaje automático.',
                'publisher' => 'Springer',
                'publication_date' => '2006-08-17',
                'pages' => 738,
                'language' => 'Inglés',
                'stock_quantity' => 2,
                'available_quantity' => 2,
                'location' => 'Estante E-05',
                'category_id' => 5,
            ],
            [
                'title' => 'Natural Language Processing with Python',
                'author' => 'Steven Bird, Ewan Klein, Edward Loper',
                'isbn' => '978-0596516499',
                'description' => 'Análisis de texto con herramientas de Python.',
                'publisher' => "O'Reilly Media",
                'publication_date' => '2009-07-06',
                'pages' => 504,
                'language' => 'Inglés',
                'stock_quantity' => 3,
                'available_quantity' => 3,
                'location' => 'Estante E-06',
                'category_id' => 5,
            ],

            // MATEMÁTICAS (Categoría 6) - 6 libros
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
                'available_quantity' => 7,
                'location' => 'Estante F-01',
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
                'location' => 'Estante F-02',
                'category_id' => 6,
            ],
            [
                'title' => 'Probabilidad y Estadística',
                'author' => 'Ronald Walpole, Raymond Myers',
                'isbn' => '978-0321629111',
                'description' => 'Para ingeniería y ciencias.',
                'publisher' => 'Pearson',
                'publication_date' => '2012-01-06',
                'pages' => 816,
                'language' => 'Español',
                'stock_quantity' => 5,
                'available_quantity' => 5,
                'location' => 'Estante F-03',
                'category_id' => 6,
            ],
            [
                'title' => 'Ecuaciones Diferenciales',
                'author' => 'Dennis Zill',
                'isbn' => '978-1111827052',
                'description' => 'Con aplicaciones de modelado.',
                'publisher' => 'Cengage Learning',
                'publication_date' => '2012-01-01',
                'pages' => 464,
                'language' => 'Español',
                'stock_quantity' => 4,
                'available_quantity' => 4,
                'location' => 'Estante F-04',
                'category_id' => 6,
            ],
            [
                'title' => 'Matemáticas Discretas',
                'author' => 'Kenneth Rosen',
                'isbn' => '978-0073383095',
                'description' => 'Matemáticas discretas y sus aplicaciones.',
                'publisher' => 'McGraw-Hill',
                'publication_date' => '2011-06-14',
                'pages' => 944,
                'language' => 'Español',
                'stock_quantity' => 5,
                'available_quantity' => 5,
                'location' => 'Estante F-05',
                'category_id' => 6,
            ],
            [
                'title' => 'Análisis Matemático',
                'author' => 'Tom Apostol',
                'isbn' => '978-8429151022',
                'description' => 'Introducción moderna al análisis matemático.',
                'publisher' => 'Reverté',
                'publication_date' => '2007-09-01',
                'pages' => 492,
                'language' => 'Español',
                'stock_quantity' => 3,
                'available_quantity' => 3,
                'location' => 'Estante F-06',
                'category_id' => 6,
            ],

            // FÍSICA (Categoría 7) - 5 libros
            [
                'title' => 'Física para Ciencias e Ingeniería',
                'author' => 'Raymond Serway, John Jewett',
                'isbn' => '978-1133947271',
                'description' => 'Fundamentos de física con aplicaciones modernas.',
                'publisher' => 'Cengage Learning',
                'publication_date' => '2013-01-01',
                'pages' => 1280,
                'language' => 'Español',
                'stock_quantity' => 6,
                'available_quantity' => 6,
                'location' => 'Estante G-01',
                'category_id' => 7,
            ],
            [
                'title' => 'Fundamentos de Física',
                'author' => 'David Halliday, Robert Resnick, Jearl Walker',
                'isbn' => '978-1118230725',
                'description' => 'Texto fundamental de física universitaria.',
                'publisher' => 'Wiley',
                'publication_date' => '2013-03-06',
                'pages' => 1328,
                'language' => 'Español',
                'stock_quantity' => 5,
                'available_quantity' => 5,
                'location' => 'Estante G-02',
                'category_id' => 7,
            ],
            [
                'title' => 'Mecánica Cuántica',
                'author' => 'David Griffiths',
                'isbn' => '978-1107189638',
                'description' => 'Una introducción a la mecánica cuántica.',
                'publisher' => 'Cambridge University Press',
                'publication_date' => '2016-08-16',
                'pages' => 508,
                'language' => 'Inglés',
                'stock_quantity' => 3,
                'available_quantity' => 3,
                'location' => 'Estante G-03',
                'category_id' => 7,
            ],
            [
                'title' => 'Electromagnetismo',
                'author' => 'David Griffiths',
                'isbn' => '978-1108420419',
                'description' => 'Introducción a la electrodinámica.',
                'publisher' => 'Cambridge University Press',
                'publication_date' => '2017-06-29',
                'pages' => 656,
                'language' => 'Inglés',
                'stock_quantity' => 3,
                'available_quantity' => 3,
                'location' => 'Estante G-04',
                'category_id' => 7,
            ],
            [
                'title' => 'Termodinámica',
                'author' => 'Yunus Çengel, Michael Boles',
                'isbn' => '978-0073398174',
                'description' => 'Un enfoque de ingeniería.',
                'publisher' => 'McGraw-Hill',
                'publication_date' => '2014-02-04',
                'pages' => 992,
                'language' => 'Español',
                'stock_quantity' => 4,
                'available_quantity' => 4,
                'location' => 'Estante G-05',
                'category_id' => 7,
            ],

            // LITERATURA (Categoría 8) - 10 libros
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
                'location' => 'Estante H-01',
                'category_id' => 8,
            ],
            [
                'title' => 'El Quijote de La Mancha',
                'author' => 'Miguel de Cervantes',
                'isbn' => '978-8467033267',
                'description' => 'La obra cumbre de la literatura española.',
                'publisher' => 'Espasa',
                'publication_date' => '1605-01-16',
                'pages' => 1023,
                'language' => 'Español',
                'stock_quantity' => 5,
                'available_quantity' => 5,
                'location' => 'Estante H-02',
                'category_id' => 8,
            ],
            [
                'title' => '1984',
                'author' => 'George Orwell',
                'isbn' => '978-0451524935',
                'description' => 'Una distopía sobre el totalitarismo.',
                'publisher' => 'Signet Classics',
                'publication_date' => '1949-06-08',
                'pages' => 328,
                'language' => 'Español',
                'stock_quantity' => 6,
                'available_quantity' => 6,
                'location' => 'Estante H-03',
                'category_id' => 8,
            ],
            [
                'title' => 'La Casa de los Espíritus',
                'author' => 'Isabel Allende',
                'isbn' => '978-8401242946',
                'description' => 'Saga familiar con elementos mágicos.',
                'publisher' => 'Plaza & Janés',
                'publication_date' => '1982-10-01',
                'pages' => 448,
                'language' => 'Español',
                'stock_quantity' => 4,
                'available_quantity' => 4,
                'location' => 'Estante H-04',
                'category_id' => 8,
            ],
            [
                'title' => 'Rayuela',
                'author' => 'Julio Cortázar',
                'isbn' => '978-8437604572',
                'description' => 'Novela experimental del boom latinoamericano.',
                'publisher' => 'Cátedra',
                'publication_date' => '1963-06-28',
                'pages' => 635,
                'language' => 'Español',
                'stock_quantity' => 3,
                'available_quantity' => 3,
                'location' => 'Estante H-05',
                'category_id' => 8,
            ],
            [
                'title' => 'Ficciones',
                'author' => 'Jorge Luis Borges',
                'isbn' => '978-8420674346',
                'description' => 'Cuentos fantásticos y laberintos literarios.',
                'publisher' => 'Alianza Editorial',
                'publication_date' => '1944-01-01',
                'pages' => 174,
                'language' => 'Español',
                'stock_quantity' => 4,
                'available_quantity' => 4,
                'location' => 'Estante H-06',
                'category_id' => 8,
            ],
            [
                'title' => 'La Ciudad y los Perros',
                'author' => 'Mario Vargas Llosa',
                'isbn' => '978-8420471174',
                'description' => 'Primera novela del Nobel peruano.',
                'publisher' => 'Alianza Editorial',
                'publication_date' => '1963-10-01',
                'pages' => 419,
                'language' => 'Español',
                'stock_quantity' => 4,
                'available_quantity' => 4,
                'location' => 'Estante H-07',
                'category_id' => 8,
            ],
            [
                'title' => 'El Amor en los Tiempos del Cólera',
                'author' => 'Gabriel García Márquez',
                'isbn' => '978-0307389732',
                'description' => 'Historia de amor que trasciende el tiempo.',
                'publisher' => 'Vintage Español',
                'publication_date' => '1985-01-01',
                'pages' => 368,
                'language' => 'Español',
                'stock_quantity' => 5,
                'available_quantity' => 5,
                'location' => 'Estante H-08',
                'category_id' => 8,
            ],
            [
                'title' => 'Pedro Páramo',
                'author' => 'Juan Rulfo',
                'isbn' => '978-8437505152',
                'description' => 'Novela fundamental de la literatura mexicana.',
                'publisher' => 'Cátedra',
                'publication_date' => '1955-03-19',
                'pages' => 124,
                'language' => 'Español',
                'stock_quantity' => 3,
                'available_quantity' => 3,
                'location' => 'Estante H-09',
                'category_id' => 8,
            ],
            [
                'title' => 'Crónica de una Muerte Anunciada',
                'author' => 'Gabriel García Márquez',
                'isbn' => '978-8497592437',
                'description' => 'Novela corta sobre honor y destino.',
                'publisher' => 'Espasa',
                'publication_date' => '1981-01-01',
                'pages' => 120,
                'language' => 'Español',
                'stock_quantity' => 3,
                'available_quantity' => 3,
                'location' => 'Estante H-10',
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

        if ($students->count() > 0 && $books->count() > 0) {
            // Crear préstamos activos
            $activeLoans = [
                [
                    'student_index' => 0,
                    'book_id' => 1, // Clean Code
                    'days_ago' => 5,
                    'notes' => 'Préstamo para proyecto de tesis sobre Clean Code'
                ],
                [
                    'student_index' => 1,
                    'book_id' => 9, // JavaScript
                    'days_ago' => 3,
                    'notes' => 'Estudio de desarrollo frontend'
                ],
                [
                    'student_index' => 2,
                    'book_id' => 21, // Database book
                    'days_ago' => 7,
                    'notes' => 'Proyecto de base de datos del curso'
                ],
                [
                    'student_index' => 3,
                    'book_id' => 27, // Machine Learning
                    'days_ago' => 4,
                    'notes' => 'Investigación en inteligencia artificial'
                ],
                [
                    'student_index' => 4,
                    'book_id' => 33, // Cálculo
                    'days_ago' => 6,
                    'notes' => 'Preparación para examen de cálculo'
                ],
            ];

            $loanCode = 1;
            foreach ($activeLoans as $loanData) {
                if ($students->count() > $loanData['student_index']) {
                    Loan::create([
                        'loan_code' => 'L' . str_pad($loanCode, 6, '0', STR_PAD_LEFT),
                        'user_id' => $students->skip($loanData['student_index'])->first()->id,
                        'book_id' => $loanData['book_id'],
                        'loan_date' => Carbon::now()->subDays($loanData['days_ago']),
                        'due_date' => Carbon::now()->addDays(14 - $loanData['days_ago']),
                        'status' => 'active',
                        'notes' => $loanData['notes'],
                    ]);

                    $loanCode++;
                }
            }

            // Crear algunos préstamos vencidos
            if ($students->count() > 5) {
                Loan::create([
                    'loan_code' => 'L' . str_pad($loanCode, 6, '0', STR_PAD_LEFT),
                    'user_id' => $students->skip(5)->first()->id,
                    'book_id' => 6, // Agile Software Development
                    'loan_date' => Carbon::now()->subDays(25),
                    'due_date' => Carbon::now()->subDays(11), // Vencido por 11 días
                    'status' => 'active',
                    'notes' => 'Préstamo vencido - recordatorio enviado',
                ]);

                $loanCode++;
            }

            // Crear préstamos devueltos (historial)
            if ($students->count() > 7) {
                Loan::create([
                    'loan_code' => 'L' . str_pad($loanCode, 6, '0', STR_PAD_LEFT),
                    'user_id' => $students->skip(7)->first()->id,
                    'book_id' => 15, // React book
                    'loan_date' => Carbon::now()->subDays(35),
                    'due_date' => Carbon::now()->subDays(21),
                    'return_date' => Carbon::now()->subDays(23), // Devuelto antes del vencimiento
                    'status' => 'returned',
                    'notes' => 'Préstamo completado exitosamente',
                ]);

                $loanCode++;

                Loan::create([
                    'loan_code' => 'L' . str_pad($loanCode, 6, '0', STR_PAD_LEFT),
                    'user_id' => $students->skip(8)->first()->id,
                    'book_id' => 40, // Literatura
                    'loan_date' => Carbon::now()->subDays(40),
                    'due_date' => Carbon::now()->subDays(26),
                    'return_date' => Carbon::now()->subDays(28),
                    'status' => 'returned',
                    'notes' => 'Devuelto antes de la fecha límite',
                ]);
            }
        }
    }
}
