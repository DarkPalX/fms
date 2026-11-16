<?php
  
namespace App\Import;
  
use App\ProjectCode;
use Carbon\Carbon;

use Maatwebsite\Excel\Concerns\ToModel;
  
class ProjectCodeImport implements ToModel
{
    public function model(array $row)
    {
        return new ProjectCode([
            'project_code'        => $row[0],
            'project_desc'        => $row[1],
            'phase_code'          => $row[2]
        ]);
    }
}