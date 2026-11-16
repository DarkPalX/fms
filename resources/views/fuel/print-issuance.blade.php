<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>PMC | Department Portal</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('assets/print/bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/print/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/print/font.css') }}">

    <style>
        .table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
            border-top: 0px solid #f4f4f4;
        }

        .table>thead>tr>th {
            border-bottom: 0px solid #f4f4f4;
        }
    </style>
</head>
<body onload="window.print();">
    <div class="wrapper">
        <section class="invoice">
            <div class="row">
                <div class="col-xs-12">
                    <center><h3>FUEL REQUEST SLIP</h3><p>PHILSAGA MINING CORPORATION</p></center>
                </div>
            </div>
            <br><br>
            <div class="row">
                <div class="col-xs-4">
                    <h2 class="page-header">
                        <small>Transaction No.</small>{{ $data->uniq_code }}
                    </h2>
                </div>
                <div class="col-xs-4">
                    <h2 class="page-header">
                        <small>Plate #</small>{{ $data->plate_no }}
                    </h2>
                </div>
                <div class="col-xs-4">
                    <h2 class="page-header">
                        <small>Vehicle:</small>{{ $data->vehicle }}
                    </h2>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <table class="table">
                        <thead>
                            <th>General Information</th>
                            <th>Other Information</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Originator Code: {{ $data->cost_code }}</td>
                                <td>RQ #: {{ $data->rq }}</td>
                            </tr>
                            <tr>
                                <td>Originator Department: {{ $data->dept }}</td>
                                <td>RQ Cost Code: {{ \App\RQ::rq_costcode($data->rq) }}</td>
                            </tr>
                            <tr>
                                <td colspan="2">Purpose: {{ strtoupper($data->purpose) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <br>
            <div class="row">
                <div class="col-xs-12 table-responsive" style="border-top: 1px solid #eee;">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Item Code</th>
                                <th>Fuel Type</th>
                                <th>Requested Qty</th>
                                <th>Actual Qty</th>
                                <th>Odometer</th>
                                <th>Full Tank</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $data->item_code }}</td>
                                <td>{{ $data->fuel_type }}</td>
                                <td>{{ $data->requested_qty }}</td>
                                <td>{{ $data->actual_qty }}</td>
                                <td>{{ number_format($data->odo,2) }}</td>
                                <td>{{ $data->type }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <br><br><br><br>
            <div class="row">
                <table class="table borderless">
                    <tbody>
                        <tr>
                            <td><b>&nbsp;&nbsp;Prepared By</b><center>{{ strtoupper(\App\Users::user_details($data->processed_by)) }}<br><br>__________________________________<br>(MM / DD / YYYY)</center></td>
                            <td><b>&nbsp;&nbsp;Received By</b><center>{{ $data->driver }}<br><br>__________________________________<br>(MM / DD / YYYY)</center></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</body>
</html>
