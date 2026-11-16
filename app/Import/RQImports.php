<?php
  
namespace App\Import;
  
use App\RQ;
use Carbon\Carbon;

use Maatwebsite\Excel\Concerns\ToModel;
  
class RQImports implements ToModel
{
    public function model(array $row)
    {
        return new RQ([
            'rq_no' => $row[0],
            'is_verified' => $row[1]
        ]);
    }
}