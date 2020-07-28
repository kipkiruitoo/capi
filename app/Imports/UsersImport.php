<?php

namespace App\Imports;

use App\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // print_r($row);
        return new User([
            'name'     => $row[0],
            'email'    => $row[1], 
            'password' => Hash::make($row[2]),
            'role_id' => 2,
            'phone' => $row[3],
            'avatar' => 'users/default.png'
        ]);
    }
}
