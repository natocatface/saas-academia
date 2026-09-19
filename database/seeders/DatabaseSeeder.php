<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Academia;
use App\Models\Plan;
use App\Models\Suscripcion;
use App\Models\Factura;
use App\Models\Docente;
use App\Models\Estudiante;
use App\Models\Curso;
use App\Models\Matricula;
use App\Models\Pago;
use App\Models\Asistencia;
use App\Models\Calificacion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /** Contadores globales para que los códigos sean únicos en toda la plataforma. */
    private array $seq = ['doc' => 0, 'est' => 0, 'cur' => 0, 'mat' => 0, 'pag' => 0];

    public function run(): void
    {
        // Limpieza previa (permite re-ejecutar sin errores)
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        foreach (['bitacoras', 'facturas', 'calificaciones', 'asistencias', 'pagos', 'matriculas', 'cursos',
                  'estudiantes', 'docentes', 'suscripciones', 'users', 'academias'] as $t) {
            if (DB::getSchemaBuilder()->hasTable($t)) {
                DB::table($t)->truncate();
            }
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $premium = Plan::where('slug', 'premium')->first();
        $pro     = Plan::where('slug', 'profesional')->first();
        $basico  = Plan::where('slug', 'basico')->first();

        // ---------- Super Administrador (dueño de la plataforma) ----------
        User::create([
            'name' => 'Super Administrador',
            'email' => 'superadmin@saas.com',
            'password' => Hash::make('password'),
            'rol' => 'superadmin',
            'academia_id' => null,
        ]);

        // ---------- Academias (tenants) ----------
        $defs = [
            ['Academia Central de Idiomas y Sistemas', 'admin@academia.com', $premium, 'activa', 30, 'Bs'],
            ['Instituto Técnico Horizonte', 'admin2@academia.com', $pro, 'activa', 18, 'Bs'],
            ['Centro de Formación Aurora', 'admin3@academia.com', $basico, 'prueba', 10, 'Bs'],
        ];

        foreach ($defs as $i => $d) {
            [$nombre, $adminEmail, $plan, $estado, $nEst, $moneda] = $d;

            $academia = Academia::create([
                'nombre_academia' => $nombre,
                'eslogan' => 'Formando profesionales de excelencia',
                'ruc_nit' => (string) rand(1000000000, 9999999999),
                'direccion' => 'Av. Principal #' . rand(100, 999) . ', Ciudad',
                'telefono' => '+591 2 ' . rand(2000000, 2999999),
                'email' => 'contacto' . ($i + 1) . '@academia.com',
                'sitio_web' => 'www.academia' . ($i + 1) . '.com',
                'moneda' => $moneda,
                'periodo_actual' => '2026',
                'plan_id' => $plan?->id,
                'estado' => $estado,
                'fecha_registro' => now()->subMonths(rand(2, 24)),
            ]);

            // Suscripción
            Suscripcion::create([
                'academia_id' => $academia->id,
                'plan_id' => $plan?->id,
                'estado' => $estado === 'prueba' ? 'prueba' : 'activa',
                'fecha_inicio' => now()->subMonths(rand(1, 12)),
                'fecha_fin' => now()->addMonths(rand(1, 12)),
                'precio' => $plan?->precio_mensual ?? 0,
            ]);

            // Facturas de los últimos meses
            for ($mf = 3; $mf >= 1; $mf--) {
                $fe = now()->copy()->subMonths($mf);
                Factura::create([
                    'numero' => Factura::siguienteNumero(),
                    'academia_id' => $academia->id,
                    'periodo' => ucfirst($fe->locale('es')->isoFormat('MMMM YYYY')),
                    'monto' => $plan?->precio_mensual ?? 0,
                    'estado' => $mf === 1 ? 'pendiente' : 'pagada',
                    'fecha_emision' => $fe->copy()->startOfMonth(),
                    'fecha_pago' => $mf === 1 ? null : $fe->copy()->startOfMonth()->addDays(3),
                    'metodo_pago' => $mf === 1 ? null : 'transferencia',
                ]);
            }

            // Usuarios de la academia
            User::create(['name' => 'Administrador', 'email' => $adminEmail,
                'password' => Hash::make('password'), 'rol' => 'admin', 'academia_id' => $academia->id]);
            User::create(['name' => 'Secretaría Académica', 'email' => 'secretaria' . ($i + 1) . '@academia.com',
                'password' => Hash::make('password'), 'rol' => 'secretaria', 'academia_id' => $academia->id]);
            User::create(['name' => 'Docente Demo', 'email' => 'docente' . ($i + 1) . '@academia.com',
                'password' => Hash::make('password'), 'rol' => 'docente', 'academia_id' => $academia->id]);

            $this->poblarAcademia($academia->id, $nEst);

            // Cuentas demo de portal: vincular docente y crear estudiante
            $primerDocente = Docente::where('academia_id', $academia->id)->first();
            if ($primerDocente) {
                User::where('email', 'docente' . ($i + 1) . '@academia.com')->update(['docente_id' => $primerDocente->id]);
            }
            $primerEst = Estudiante::where('academia_id', $academia->id)->first();
            if ($primerEst) {
                User::create(['name' => $primerEst->nombre_completo, 'email' => 'estudiante' . ($i + 1) . '@academia.com',
                    'password' => Hash::make('password'), 'rol' => 'estudiante',
                    'academia_id' => $academia->id, 'estudiante_id' => $primerEst->id]);
            }
        }

        // Compatibilidad: la primera academia mantiene secretaria@academia.com
        User::where('email', 'secretaria1@academia.com')->update(['email' => 'secretaria@academia.com']);
        User::where('email', 'estudiante1@academia.com')->update(['email' => 'estudiante@academia.com']);
        User::where('email', 'docente1@academia.com')->update(['email' => 'docente@academia.com']);
    }

    private function poblarAcademia(int $academiaId, int $nEstudiantes): void
    {
        // ----- Docentes -----
        $docentesData = [
            ['Carlos', 'Mendoza Rojas', 'Programación', 'Ing. de Sistemas'],
            ['María', 'Quispe Flores', 'Inglés', 'Lic. en Idiomas'],
            ['Jorge', 'Vargas León', 'Matemáticas', 'Lic. en Matemáticas'],
            ['Ana', 'Torrez Salazar', 'Diseño Gráfico', 'Lic. en Artes'],
            ['Luis', 'Camacho Pérez', 'Marketing Digital', 'MBA'],
        ];
        $docentes = [];
        foreach ($docentesData as $d) {
            $docentes[] = Docente::create([
                'academia_id' => $academiaId,
                'codigo' => 'DOC-' . str_pad(++$this->seq['doc'], 4, '0', STR_PAD_LEFT),
                'nombres' => $d[0], 'apellidos' => $d[1],
                'documento' => (string) rand(1000000, 9999999),
                'email' => strtolower($d[0]) . $this->seq['doc'] . '@academia.com',
                'telefono' => '7' . rand(1000000, 9999999),
                'especialidad' => $d[2], 'titulo' => $d[3],
                'fecha_contratacion' => now()->subMonths(rand(3, 36)), 'estado' => 'activo',
            ]);
        }

        // ----- Cursos -----
        $cursosData = [
            ['Programación Web Full Stack', 'avanzado', 'presencial', 'Lun-Mié-Vie 18:00-20:00', 350, 280, 0],
            ['Inglés Conversacional B1', 'intermedio', 'hibrido', 'Mar-Jue 19:00-21:00', 200, 180, 1],
            ['Cálculo y Álgebra', 'basico', 'presencial', 'Lun-Vie 16:00-18:00', 150, 150, 2],
            ['Diseño Gráfico con Adobe', 'intermedio', 'virtual', 'Sáb 09:00-13:00', 300, 250, 3],
            ['Marketing Digital y Redes', 'basico', 'virtual', 'Mar-Jue 20:00-22:00', 250, 200, 4],
            ['Python para Data Science', 'avanzado', 'hibrido', 'Sáb 14:00-18:00', 400, 320, 0],
        ];
        $cursos = [];
        foreach ($cursosData as $j => $c) {
            $cursos[] = Curso::create([
                'academia_id' => $academiaId,
                'codigo' => 'CUR-' . str_pad(++$this->seq['cur'], 4, '0', STR_PAD_LEFT),
                'nombre' => $c[0], 'descripcion' => 'Curso de ' . $c[0] . '.',
                'docente_id' => $docentes[$c[6]]->id, 'nivel' => $c[1], 'modalidad' => $c[2],
                'horario' => $c[3], 'aula' => 'Aula ' . ($j + 101),
                'cupo_maximo' => [20, 25, 30][rand(0, 2)], 'duracion_meses' => [3, 4, 6][rand(0, 2)],
                'costo_matricula' => $c[4], 'costo_mensual' => $c[5], 'estado' => 'activo',
            ]);
        }

        // ----- Estudiantes -----
        $nombres = ['Pedro','Lucía','Diego','Valeria','Mateo','Camila','Sebastián','Daniela','Andrés','Fernanda',
            'Gabriel','Isabella','Joaquín','Renata','Nicolás','Antonella','Emilio','Micaela','Bruno','Paula',
            'Rodrigo','Sofía','Martín','Julia','Ignacio','Carolina','Alejandro','Mariana','Tomás','Valentina'];
        $apellidos = ['García','Mamani','Choque','Rojas','Fernández','López','Gutiérrez','Condori','Vásquez','Núñez',
            'Saavedra','Aguilar','Méndez','Calle','Soliz','Ramírez','Ticona','Ortega','Cabrera','Paredes'];

        for ($k = 0; $k < $nEstudiantes; $k++) {
            $est = Estudiante::create([
                'academia_id' => $academiaId,
                'codigo' => 'EST-' . str_pad(++$this->seq['est'], 5, '0', STR_PAD_LEFT),
                'nombres' => $nombres[$k % count($nombres)],
                'apellidos' => $apellidos[array_rand($apellidos)] . ' ' . $apellidos[array_rand($apellidos)],
                'documento' => (string) rand(1000000, 9999999),
                'email' => strtolower($nombres[$k % count($nombres)]) . $this->seq['est'] . '@gmail.com',
                'telefono' => '6' . rand(1000000, 9999999),
                'fecha_nacimiento' => now()->subYears(rand(16, 35))->subDays(rand(0, 364)),
                'genero' => ['M', 'F'][rand(0, 1)],
                'direccion' => 'Zona ' . ['Central','Norte','Sur','Este','Oeste'][rand(0, 4)],
                'apoderado' => $apellidos[array_rand($apellidos)], 'telefono_apoderado' => '7' . rand(1000000, 9999999),
                'estado' => 'activo',
            ]);

            foreach (collect($cursos)->random(rand(1, 2)) as $cur) {
                $desc = rand(0, 4) === 0 ? round($cur->costo_matricula * 0.1, 2) : 0;
                $mat = Matricula::create([
                    'academia_id' => $academiaId,
                    'codigo' => 'MAT-' . str_pad(++$this->seq['mat'], 5, '0', STR_PAD_LEFT),
                    'estudiante_id' => $est->id, 'curso_id' => $cur->id, 'periodo' => '2026',
                    'fecha_inicio' => now()->subMonths(rand(0, 5)),
                    'monto_matricula' => $cur->costo_matricula, 'descuento' => $desc, 'estado' => 'activa',
                    'created_at' => now()->subMonths(rand(0, 5))->subDays(rand(0, 20)),
                ]);

                Pago::create([
                    'academia_id' => $academiaId,
                    'codigo' => 'PAG-' . str_pad(++$this->seq['pag'], 6, '0', STR_PAD_LEFT),
                    'estudiante_id' => $est->id, 'matricula_id' => $mat->id, 'concepto' => 'Matrícula 2026',
                    'monto' => $cur->costo_matricula - $desc,
                    'metodo_pago' => ['efectivo','transferencia','qr','tarjeta'][rand(0, 3)],
                    'fecha_pago' => $mat->created_at, 'estado' => 'pagado',
                ]);

                for ($mo = 0; $mo < rand(1, 4); $mo++) {
                    $fecha = now()->subMonths($mo);
                    $pagado = rand(0, 3) > 0;
                    Pago::create([
                        'academia_id' => $academiaId,
                        'codigo' => 'PAG-' . str_pad(++$this->seq['pag'], 6, '0', STR_PAD_LEFT),
                        'estudiante_id' => $est->id, 'matricula_id' => $mat->id,
                        'concepto' => 'Mensualidad ' . $fecha->locale('es')->isoFormat('MMMM YYYY'),
                        'monto' => $cur->costo_mensual,
                        'metodo_pago' => ['efectivo','transferencia','qr'][rand(0, 2)],
                        'fecha_pago' => $pagado ? $fecha : null,
                        'fecha_vencimiento' => $fecha->copy()->endOfMonth(),
                        'estado' => $pagado ? 'pagado' : 'pendiente',
                    ]);
                }

                for ($d = 0; $d < 8; $d++) {
                    $fa = now()->subDays($d * 2);
                    Asistencia::updateOrCreate(
                        ['estudiante_id' => $est->id, 'curso_id' => $cur->id, 'fecha' => $fa->toDateString()],
                        ['academia_id' => $academiaId, 'estado' => ['presente','presente','presente','tardanza','ausente'][rand(0, 4)]]
                    );
                }

                foreach (['Examen Parcial 1', 'Práctica', 'Examen Final'] as $ev) {
                    Calificacion::create([
                        'academia_id' => $academiaId,
                        'estudiante_id' => $est->id, 'curso_id' => $cur->id,
                        'evaluacion' => $ev, 'nota' => rand(40, 100), 'periodo' => '2026',
                        'fecha' => now()->subDays(rand(1, 60)),
                    ]);
                }
            }
        }
    }
}
