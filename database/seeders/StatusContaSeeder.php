<?php

namespace Database\Seeders;

use App\Models\StatusConta;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusContaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(!StatusConta::where('nome', 'Paga')->first())
        {
            StatusConta::create([
                'nome' => 'Paga',
                'cor' => 'success'
            ]);
        }
        if(!StatusConta::where('nome', 'Pendente')->first())
        {
            StatusConta::create([
                'nome' => 'Pendente',
                'cor' => 'danger'
            ]);
        }
        if(!StatusConta::where('nome', 'Cancelada')->first())
        {
            StatusConta::create([
                'nome' => 'Cancelada',
                'cor' => 'secondary'
            ]);
        }
    }
}
