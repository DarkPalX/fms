<?php
  
namespace App\Import;
  
use App\Vehicles;
use Carbon\Carbon;

use Maatwebsite\Excel\Concerns\ToModel;
  
class VehicleImports implements ToModel
{
    public function model(array $row)
    {
        return new Vehicles([
            'name'         => $row[0],
            'plateno'      => $row[1], 
            'vehicle_code' => $row[2],
            'dept'         => $row[3],
            'type'         => $row[4],
            'isECS'        => 0
        ]);
    }
}