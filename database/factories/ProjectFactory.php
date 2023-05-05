<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Project;
use Database\Factories\ProfessorFactory;


class ProjectFactory extends Factory
{

    protected $model = Project::class;

    public function definition()
    {
        return [
            'sujet' => $this->faker->sentence,
            'filiere' => $this->faker->word,
            'NbrPersonnes' => $this->faker->randomDigitNotNull,
            'description' => $this->faker->word,
            'id_user' => ProjectFactory::new()->create()->id,
           
        ];
    }
}
