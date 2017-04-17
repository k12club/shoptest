<!-- Modal -->
<div class="modal fade" id="billing-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong class="modal-title">Billing Addresses</strong>
            </div>
            <div class="modal-body">
                <ul class="list-unstyled" id="billing-current-addresses">
                    @foreach (auth()->user()->billingAddresses()->get() as $billingAddress)
                        <li class="row address-row" id="billing-address-{{ $billingAddress['id'] }}" data-default-billing="{{ $billingAddress['default_billing'] }}" data-name="{!! $billingAddress['name'] !!}" data-phone="{{ $billingAddress['phone'] }}" data-address_1="{{ $billingAddress['address_1'] }}" data-address_2="{{ $billingAddress['address_2'] }}" data-city="{{ $billingAddress['city'] }}" data-state="{{ $billingAddress['state'] }}" data-zipcode="{{ $billingAddress['zipcode'] }}">
                            <div class="col-md-8">
                                <a href="#" class="address" id="billing-select-address-{{ $billingAddress['id'] }}" title="Use this address for this billing">{!! CustomHelper::formatAddress($billingAddress) !!}</a>
                            </div>
                            <div class="col-md-4 text-right">
                                <a href="#" id="billing-edit-address-btn-{{ $billingAddress['id'] }}">Edit</a> |
                                <a href="#" class="remove">Delete</a><br /><br />

                                @if ($billingAddress['id'] == auth()->user()->billingAddress()->first()['id'])
                                    <span class="primary"><i class="fa fa-check"></i> Primary</span>
                                @else
                                    <a href="#" class="primary">Make primary</a>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>

                {{ Form::open(['route' => ['shop.addresses.manage'], 'class' => 'material hide', 'id' => 'billing-address-form']) }}

                    @include('shop.checkout.billing_fieldset')

                    <input type="hidden" name="id" value="" />

                    <button class="btn btn-primary" type="submit">Add</button>

                    <a href="#" id="billing-address-cancel-btn" class="btn">Cancel</a>

                {{ Form::close() }}
            </div>
            <div class="modal-footer">
                <a href="#" id="billing-new-address-btn" class="btn btn-sm btn-success pull-left">Add new address</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>