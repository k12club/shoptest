<!-- Modal -->
<div class="modal fade" id="delivery-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong class="modal-title">Delivery Addresses</strong>
            </div>
            <div class="modal-body">
                <ul class="list-unstyled" id="delivery-current-addresses">
                    @foreach (auth()->user()->deliveryAddresses()->get() as $deliveryAddress)
                        <li class="row address-row" id="delivery-address-{{ $deliveryAddress['id'] }}" data-default-delivery="{{ $deliveryAddress['default_delivery'] }}" data-name="{!! $deliveryAddress['name'] !!}" data-phone="{{ $deliveryAddress['phone'] }}" data-address_1="{{ $deliveryAddress['address_1'] }}" data-address_2="{{ $deliveryAddress['address_2'] }}" data-city="{{ $deliveryAddress['city'] }}" data-state="{{ $deliveryAddress['state'] }}" data-zipcode="{{ $deliveryAddress['zipcode'] }}">
                            <div class="col-md-8">
                                <a href="#" class="address" id="delivery-select-address-{{ $deliveryAddress['id'] }}" title="Use this address for this delivery">{!! CustomHelper::formatAddress($deliveryAddress) !!}</a>
                            </div>
                            <div class="col-md-4 text-right">
                                <a href="#" id="delivery-edit-address-btn-{{ $deliveryAddress['id'] }}">Edit</a> |
                                <a href="#" class="remove">Delete</a><br /><br />

                                @if ($deliveryAddress['id'] == auth()->user()->deliveryAddress()->first()['id'])
                                    <span class="primary"><i class="fa fa-check"></i> Primary</span>
                                @else
                                    <a href="#" class="primary">Make primary</a>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>

                {{ Form::open(['route' => ['shop.addresses.manage'], 'class' => 'material hide', 'id' => 'delivery-address-form']) }}

                    @include('shop.checkout.delivery_fieldset')

                    <input type="hidden" name="id" value="" />

                    <button class="btn btn-primary" type="submit">Submit</button>

                    <a href="#" id="delivery-address-cancel-btn" class="btn">Cancel</a>

                {{ Form::close() }}
            </div>
            <div class="modal-footer">
                <a href="#" id="delivery-new-address-btn" class="btn btn-sm btn-success pull-left">Add new address</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>