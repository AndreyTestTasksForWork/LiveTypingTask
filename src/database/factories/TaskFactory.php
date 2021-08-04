<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition()
    {
        $categoryIds = DB::table('category')->select('id')->get();

        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(150),
            'category_id' => $categoryIds[rand(0, 6)]->id
        ];
    }
}
