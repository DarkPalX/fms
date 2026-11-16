
@if($module == 'issuance_rpt')
	@php $total = 0; @endphp

	@forelse($datas as $data)
		@php $total += $data->liter*$data->cost; @endphp

		<tr>
			<td>{{ $data->uniq_code }}</td>
			<td>{{ $data->request_dt }}</td>
			<td>{{ $data->name }}</td>
			<td>{{ $data->rq }}</td>
			<td>{{ $data->batch_no }}</td>
			<td>{{ $data->department }}</td>
			<td class="text-right">{{ $data->liter }}</td>
			<td class="text-right">{{ $data->cost }}</td>
		</tr>
	@empty
		<tr>
			<td colspan="9"><center>NO ISSUANCE FOUND!</center></td>
		</tr>
	@endforelse

		<tr>
			<td><strong>Grand Total</strong></td>
			<td colspan="9"><strong class="float-right">{{ $total }}</strong></td>
		</tr>
@endif


@if($module == 'issuance_list')
	
	@forelse($datas as $data)
		<tr>
            <td><a href="javascript:;">{{ $data->uniq_code }}</a></td>
            <td>{{ $data->request_dt }}</td>
            <td>{{ $data->batch_no }}</td>
            <td>{{ $data->department }}</td>
            <td>{{ $data->name }}</td>
            <td>{{ $data->liter }}</td>
            <td>
            	@if($data->is_cancelled == 1)
                    <a href="javascript:;" class="btn btn-xs btn-danger">CANCELLED</a>
                @else
                    @if($data->is_issued == 1)
                        <a href="javascript:;" class="btn btn-xs btn-success">ISSUED</a>
                    @else
                        @if(Auth::user()->is_dept == 0)
                        	<a href="/fuel/process-issuance/{{ $data->id }}" class="btn btn-xs btn-primary">Edit</a>
                        @else
                        	<a href="/fuel/issuance/edit/{{ $data->id }}" class="btn btn-xs btn-primary">Edit</a>
                            <a href="/fuel-issuance/print/{{ $data->id }}" target="_blank" class="btn btn-xs btn-info">Print</a>
                            <a href="/fuel/issuance/cancel/{{ $data->id }}" data-id="{{$data->id}}" data-toggle="modal" data-target="#modal-cancel" class="btn btn-xs btn-danger cancel">Cancel</a>
                        @endif
                    @endif
                @endif
            </td>
        </tr>
	@empty
		<tr>
			<td colspan="7"><center>NO ISSUANCE FOUND!</center></td>
		</tr>
	@endforelse

@endif