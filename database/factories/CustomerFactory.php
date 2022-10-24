<?php

use Faker\Generator as Faker;

$factory->define(\App\Customer::class, function (Faker $faker) {
    return [
        //
        'customer_id'=> $this->faker->name,
        'customer_name' => $this->faker->name,
        'customer_id_number' => $this->faker->numerify('############'),
        'customer_address' => $this->faker->Address,
        'customer_proffesion' => $this->faker->Company,
        'customer_phone' => $this->faker->phoneNumber,
        'customer_agent' => $this->faker->name,
        'updated_at' => $this->faker->dateTime(),
        'created_at' => $this->faker->dateTime()
    ];
});
