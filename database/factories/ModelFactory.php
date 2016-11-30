<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Campaign::class, function (Faker\Generator $oFaker) {

    return [
        'title' => $oFaker->company,
        'description' => $oFaker->paragraphs(3, true),
        'budget' => $oFaker->randomNumber(5),
        'start_dt' => $oFaker->dateTime,
        'end_dt' => $oFaker->dateTime,
        'reminder' => (int) $oFaker->boolean(50)

    ];
});

$factory->define(App\Customer::class, function (Faker\Generator $oFaker) {

    return [
        'first_name' => $oFaker->firstName,
        'last_name' => $oFaker->lastName,
        'dob' => $oFaker->date(),
        'phone' => $oFaker->phoneNumber,
        'email_address' => $oFaker->email,
        'remarks' => $oFaker->paragraphs(6, true)

    ];
});
