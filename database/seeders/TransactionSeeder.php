<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $courses = collect(['course 1', 'course 2', 'course 3', 'course 4', 'course 5', 'course 6']);
        $notes = collect(['sample note', '']);
        $createDatesWithSubs = collect([now('UTC')->toDateTimeString(), now('UTC')->subYear(1)->toDateTimeString(), now('UTC')->subMonth(1)->toDateTimeString(), now('UTC')->subWeek(1)->toDateTimeString()]);
        $professorUsers = User::onlyProfessors()->get();

        $transactions = collect();
        for ($i=0; $i < 650; $i++) {
            $randomDate = $createDatesWithSubs->random();
            $user_id = $professorUsers[0]->id;
            if($i >= 150) $user_id = $professorUsers[1]->id;
            if($i >= 290) $user_id = $professorUsers[2]->id;
            if($i >= 420) $user_id = $professorUsers[3]->id;
            if($i >= 540) $user_id = $professorUsers[4]->id;

            $transactions->push([
                'course' => $courses->random(),
                'visitor_name' => fake()->name(),
                'purpose' => fake()->sentence(),
                'user_id' => $user_id,
                'note' => $notes->random(),
                'created_at' => $randomDate,
                'updated_at' => $randomDate,
                'served_at' => collect([$randomDate, null, $randomDate])->random()
            ]);
        }

        DB::table('transactions')->insert($transactions->all());
    }
}
