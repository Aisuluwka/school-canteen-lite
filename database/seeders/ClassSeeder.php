<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassModel;

class ClassSeeder extends Seeder
{
    public function run()
    {
        $grades = range(5, 11);
        $letters = ['А', 'Б', 'В', 'Г', 'Д', 'Е'];

        foreach ($grades as $grade) {
            foreach ($letters as $letter) {
                ClassModel::create([
                    'name' => "{$grade}{$letter}"
                ]);
            }
        }
    }
}
