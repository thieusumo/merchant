<?php

namespace App\Imports;

use App\Models\PosCustomer;
use Maatwebsite\Excel\Concerns\ToModel;
use Session;

class PosCustomerImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new PosCustomer([
            'customer_customertag_id'     => $row[0],
            'customer_fullname'    => $row[1], 
            'customer_gender' => $row[2],
            'customer_phone' => $row[3],
            'customer_country_code' => $row[4],
            'customer_email' => $row[5],
            'customer_birthdate' => $row[6],
            'customer_address' => $row[7],
        ]);
    }
}
