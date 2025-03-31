<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Seats;
use App\Models\filmSessions;
use Illuminate\Support\Facades\DB;

class SeatsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('seats')->truncate(); // Limpia la tabla antes de insertar

        $sessions = filmSessions::all(); // Obtener todas las sesiones disponibles

        if ($sessions->isEmpty()) {
            $this->command->warn('No hay sesiones disponibles. Primero inserta sesiones en la base de datos.');
            return;
        }

        $rows = range('A', 'L'); // Filas A-L
        $columns = range(1, 10); // Columnas 1-10

        $seats = [];

        foreach ($sessions as $session) {
            foreach ($rows as $row) {
                foreach ($columns as $column) {
                    $seats[] = [
                        'session_id' => $session->id,
                        'row' => $row,
                        'number' => $column,
                        'type' => ($row === 'F') ? 'VIP' : 'Normal', // Fila F es VIP
                        'status' => 'Disponible', // Estado inicial: disponible
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        Seats::insert($seats);

        $this->command->info('Se han insertado los asientos correctamente para todas las sesiones.');
    }
}
