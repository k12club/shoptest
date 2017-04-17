<!-- Modal -->
<div class="modal fade" id="payment-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong class="modal-title">Credit Cards</strong>
            </div>
            <div class="modal-body">
                <ul class="list-unstyled" id="payment-current-cards">
                    @foreach (auth()->user()->cards()->get() as $card)
                        <li class="row address-row" data-name="{!! $card['name_on_card'] !!}" data-last4="{{ $card['last4'] }}" data-brand="{!! $card['brand'] !!}">
                            <div class="col-md-8">
                                <a href="javascript:void(0);" class="address" id="payment-select-card-{{ $card['id'] }}" title="Use this credit card for this order">{!! CustomHelper::formatCard($card) !!}</a>
                            </div>
                            <div class="col-md-4 text-right">
                                <a href="#">Edit</a> |
                                <a href="#">Delete</a><br /><br />

                                @if ($card['id'] == auth()->user()->primaryCard()->first()['id'])
                                    <i class="fa fa-check"></i> Primary
                                @else
                                    <a href="#">Make primary</a>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>

                {{ Form::open(['route' => ['shop.payments.store'], 'class' => 'material hide', 'id' => 'add-payment-card-form']) }}

                @include('shop.checkout.payment_fieldset')

                <button class="btn btn-primary" type="submit">Add</button>

                <a href="javascript:void(0);" id="add-payment-card-cancel" class="btn hide">Cancel</a>

                {{ Form::close() }}
            </div>
            <div class="modal-footer">
                <a href="javascript:void(0);" id="payment-new-card" class="btn btn-sm btn-success pull-left">Add new credit card</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>