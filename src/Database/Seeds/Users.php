<?php

namespace Ostap\Gate\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class Users extends Seeder
{
    public function run()
    {
        // Dati utenti di esempio
        $users = [
            [
                'name' => 'Super Amministratore',
                'email' => 'admin@esempio.com',
                'password' => password_hash('admin123!', PASSWORD_DEFAULT),
                'role' => 'admin',
                'status' => 'active',
                'phone' => '+39 333 1234567',
                'birth_date' => '1980-05-15',
                'email_verified_at' => Time::now()->toDateTimeString(),
                'last_login' => Time::now()->subDays(1)->toDateTimeString(),
                'created_at' => Time::now()->subMonths(6)->toDateTimeString(),
                'updated_at' => Time::now()->subDays(1)->toDateTimeString(),
            ],
            [
                'name' => 'Mario Rossi',
                'email' => 'mario.rossi@esempio.com',
                'password' => password_hash('user123!', PASSWORD_DEFAULT),
                'role' => 'user',
                'status' => 'active',
                'phone' => '+39 345 9876543',
                'birth_date' => '1990-03-22',
                'email_verified_at' => Time::now()->subDays(30)->toDateTimeString(),
                'last_login' => Time::now()->subHours(2)->toDateTimeString(),
                'created_at' => Time::now()->subMonths(3)->toDateTimeString(),
                'updated_at' => Time::now()->subHours(2)->toDateTimeString(),
            ],
            [
                'name' => 'Giulia Bianchi',
                'email' => 'giulia.bianchi@esempio.com',
                'password' => password_hash('moderator123!', PASSWORD_DEFAULT),
                'role' => 'moderator',
                'status' => 'active',
                'phone' => '+39 320 5555444',
                'birth_date' => '1985-11-08',
                'email_verified_at' => Time::now()->subDays(15)->toDateTimeString(),
                'last_login' => Time::now()->subDays(3)->toDateTimeString(),
                'created_at' => Time::now()->subMonths(2)->toDateTimeString(),
                'updated_at' => Time::now()->subDays(3)->toDateTimeString(),
            ],
            [
                'name' => 'Luca Verdi',
                'email' => 'luca.verdi@esempio.com',
                'password' => password_hash('user456!', PASSWORD_DEFAULT),
                'role' => 'user',
                'status' => 'inactive',
                'phone' => '+39 347 1111222',
                'birth_date' => '1992-07-14',
                'email_verified_at' => null, // Email non verificata
                'last_login' => Time::now()->subWeeks(2)->toDateTimeString(),
                'login_attempts' => 3,
                'created_at' => Time::now()->subMonth()->toDateTimeString(),
                'updated_at' => Time::now()->subWeeks(2)->toDateTimeString(),
            ],
            [
                'name' => 'Anna Neri',
                'email' => 'anna.neri@esempio.com',
                'password' => password_hash('user789!', PASSWORD_DEFAULT),
                'role' => 'user',
                'status' => 'suspended',
                'phone' => '+39 366 7777888',
                'birth_date' => '1988-12-03',
                'email_verified_at' => Time::now()->subDays(45)->toDateTimeString(),
                'last_login' => Time::now()->subDays(7)->toDateTimeString(),
                'created_at' => Time::now()->subMonths(4)->toDateTimeString(),
                'updated_at' => Time::now()->subDays(7)->toDateTimeString(),
            ],
        ];

        // Inserimento batch per performance migliori
        $this->db->table('mio_pacchetto_users')->insertBatch($users);

        // Stampa info per il CLI
        echo "✅ Inseriti " . count($users) . " utenti di esempio\n";
        
        // Genera utenti casuali aggiuntivi
        $this->generateRandomUsers(20);
    }

    /**
     * Genera utenti casuali per testing
     */
    private function generateRandomUsers(int $count): void
    {
        $nomi = ['Marco', 'Andrea', 'Francesco', 'Alessandro', 'Matteo', 'Lorenzo', 'Gabriele', 'Mattia', 'Riccardo', 'Davide'];
        $cognomi = ['Rossi', 'Bianchi', 'Verdi', 'Neri', 'Gialli', 'Ferrari', 'Conti', 'Ricci', 'Marino', 'Greco'];
        $domini = ['gmail.com', 'yahoo.it', 'libero.it', 'hotmail.com', 'outlook.com'];
        $ruoli = ['user', 'user', 'user', 'moderator']; // user più frequente
        $status = ['active', 'active', 'active', 'inactive']; // active più frequente

        $randomUsers = [];
        
        for ($i = 0; $i < $count; $i++) {
            $nome = $nomi[array_rand($nomi)];
            $cognome = $cognomi[array_rand($cognomi)];
            $email = strtolower($nome . '.' . $cognome . ($i + 1) . '@' . $domini[array_rand($domini)]);
            
            $randomUsers[] = [
                'name' => $nome . ' ' . $cognome,
                'email' => $email,
                'password' => password_hash('password123!', PASSWORD_DEFAULT),
                'role' => $ruoli[array_rand($ruoli)],
                'status' => $status[array_rand($status)],
                'phone' => '+39 3' . rand(10, 99) . ' ' . rand(1000000, 9999999),
                'birth_date' => date('Y-m-d', strtotime('-' . rand(20, 50) . ' years')),
                'email_verified_at' => rand(0, 1) ? Time::now()->subDays(rand(1, 90))->toDateTimeString() : null,
                'last_login' => rand(0, 1) ? Time::now()->subDays(rand(0, 30))->toDateTimeString() : null,
                'created_at' => Time::now()->subDays(rand(1, 180))->toDateTimeString(),
                'updated_at' => Time::now()->subDays(rand(0, 30))->toDateTimeString(),
            ];
        }

        $this->db->table('mio_pacchetto_users')->insertBatch($randomUsers);
        echo "✅ Generati " . $count . " utenti casuali aggiuntivi\n";
    }
}
