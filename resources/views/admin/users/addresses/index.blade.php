@if ($user->deliveryAddresses()->count() > 0)
    <table class="table table-striped table-bordered table-hover">
        <tr>
            <th>
                Delivery
            </th>
        </tr>
        @foreach ($user->deliveryAddresses as $deliveryAddress)
            <tr>
                <td>
                    <span class="pull-left">
                        {!! CustomHelper::formatAddress($deliveryAddress) !!}
                    </span>
                    <span class="pull-right text-right">
                        @if ($deliveryAddress['default_delivery'])
                            <i class="fa fa-check"></i> Primary
                        @endif
                    </span>
                </td>
            </tr>
        @endforeach
    </table>
@else
    <div class="alert alert-warning">
        This user has no saved delivery addresses at the present.
    </div>
@endif

@if ($user->billingAddresses()->count() > 0)
    <table class="table table-striped table-bordered table-hover">
        <tr>
            <th>
                Billing
            </th>
        </tr>
        @foreach ($user->billingAddresses as $billingAddress)
            <tr>
                <td>
                    <span class="pull-left">
                        {!! CustomHelper::formatAddress($billingAddress) !!}
                    </span>
                    <span class="pull-right text-right">
                        @if ($billingAddress['default_billing'])
                            <i class="fa fa-check"></i> Primary
                        @endif
                    </span>
                </td>
            </tr>
        @endforeach
    </table>
@else
    <div class="alert alert-warning">
        This user has no saved billing addresses at the present.
    </div>
@endif