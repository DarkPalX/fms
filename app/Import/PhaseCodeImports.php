<?php
  
namespace App\Import;
  
use App\PhaseCode;
use Carbon\Carbon;

use Maatwebsite\Excel\Concerns\ToModel;
  
class PhaseCodeImports implements ToModel
{
    public function model(array $row)
    {
        return new PhaseCode([
            'phase_code'        => $row[0],
            'phase_desc'        => $row[1]
        ]);
    }
}