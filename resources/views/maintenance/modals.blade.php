<!--  ############################## Fuel Type Modals ############################## -->
<div class="modal fade" id="modal-add">
    <div class="modal-dialog">
        <div class="modal-content">
            <form autocomplete="off" role="form" action="{{ route('fuel-store') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Create Fuel</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="inputCode">Stock Code</label>
                            <input required type="number" class="form-control" id="inputCode" name="stock_code">
                        </div>
                        <div class="form-group">
                            <label for="inputDesc">Fuel Description <i class="text-danger">*</i></label>
                            <input required type="text" class="form-control" id="inputDesc" name="description">
                        </div>
                        <div class="form-group">
                            <label for="inputCost">Cost <i class="text-danger">*</i></label>
                            <input required type="number" class="form-control text-right" value="0.00" step="0.01" id="inputCost" name="cost">
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-update">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('fuel-update') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Update Fuel</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="u_fuel_id" name="fuel_id">
                    <div class="form-group">
                        <label for="inputName">Stock Code</label>
                        <input type="text" class="form-control" id="stock_code" name="stock_code">
                    </div>
                    <div class="form-group">
                        <label for="inputDomain">Fuel Description <i class="text-danger">*</i></label>
                        <input type="text" class="form-control" id="desc" name="description">
                    </div>
                    <div class="form-group">
                        <label for="inputDomain">Cost <i class="text-danger">*</i></label>
                        <input type="number" class="form-control text-right" id="cost" name="cost" step="0.01">
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="modal-delete">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('delete.fuel-type') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Fuel Disable Confirmation</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>This will disable the "<b id="fuel_name_disable"></b>" from fuel requisition and will send an email to everyone@philsaga.com about its status.</p>
                    <p>Specify Reason:
                    <select name="fuel_disable_reason" id="fuel_disable_reason" class="form-control" required="required">
                        <option value="">Select</option>
                        <option value="Unavailable supply of fuel">No Stock</option>
                        <option value="Breakdown of dispenser machine">Breakdown</option>
                        <option value="Facility is closed">No Operation</option>
                    </select>
                    </p>
                    <p>Email Content
                    <textarea required="required" name="fuel_email_disable" id="fuel_email_disable" class="form-control" rows="5"></textarea>
                    </p>
                    <input type="hidden" id="p_fuel_id" name="fuel_id">
                    <input type="hidden" id="fuel_name_disable_hidden" name="fuel_name_disable_hidden">
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-enable">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('enable.fuel-type') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Fuel Enable Confirmation</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>This will enable the "<b id="fuel_name_enable"></b>", Do you want to continue?</h5>
                    <input type="hidden" id="p_fuel_id_enable" name="fuel_id">
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Yes, Enable</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--  ############################## Fuel Type Modals ############################## -->