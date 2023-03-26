<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions=[
            ['name'=>'cud livres'],
            ['name'=>'show livres'],
            ['name'=>'filtrer livres'],
            ['name'=>'assign role/permission'],
            ['name'=>'cud category']
        ];
        foreach($permissions as $per){
            Permission::create($per);
        }
    }
}
