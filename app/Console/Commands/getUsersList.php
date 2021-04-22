<?php

namespace App\Console\Commands;

use App\Models\Hobby;
use App\Models\User;
use Illuminate\Console\Command;

class getUsersList extends Command
{
    // /**
    //  * The name and signature of the console command.
    //  *
    //  * @var string
    //  */
    protected $signature = 'get:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get a CSV document with all users of the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $filename = "users.csv";

        $handle = fopen($filename, 'w');

        $columns = array('FullName', 'DOB', 'Biography', 'Hobbies', 'Created');
        fputcsv($handle, $columns);
        $users = User::all();
        foreach ($users as $user) {
            $row['FullName']  = $user->fullname;
            $row['DOB']    = $user->dob;
            $row['Biography']    = $user->biography;

            $hobbies = Hobby::where('user_id', $user->id)->get();
            $row['Hobbies']  = $hobbies;
            $row['Created']  = $user->created_at;

            fputcsv($handle, array(
                $row['FullName'],
                $row['DOB'],
                $row['Biography'],
                $row['Hobbies'],
                $row['Created']
            ));
        }
        fclose($handle);

        $headers = array(
            'Content-Type' => 'text/csv',
        );
    }
}
