<?php

namespace Database\Factories;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement(['Gaming', 'Programming', 'Music', 'Movies', 'Books', 'Sports', 'Travel', 'Food', 'Fashion', 'Lifestyle']),
            'description' => $this->faker->text(),
            'color' => $this->faker->unique()->randomElement(['red', 'green', 'blue', 'yellow', 'purple', 'pink', 'orange', 'gray', 'black', 'white']),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
