@extends('layouts.master')

@section('title', 'Delivery Information - ' . config('app.name'))

@section('content')

    <div class="row">

        <div class="col-md-12">

            <div class="content-box">

                <h1>Delivery Information</h1>

                <table>
                    <tbody>
                        <tr>
                            <th>Delivery Method</th>
                            <th>Price</th>
                        </tr>
                        <tr>
                            <td>Standard (2 - 3 working days)</td>
                            <td>$6.80</td>
                        </tr>
                        <tr>
                            <td>Express (1-2 working days)</td>
                            <td>$22.95</td>
                        </tr>
                    </tbody>
                </table>

                <h1>Order Codes</h1>

                <table>
                    <tbody>
                        <tr>
                            <th>Code</th>
                            <th>Description</th>
                        </tr>
                        <tr>
                            <td>Processing</td>
                            <td>Your order is being processed. We're reviewing your oder, packaging your items, or shipping
                                your oder.</td>
                        </tr>
                        <tr>
                            <td>Shipped</td>
                            <td>Your order has been shipped. When applicable, and if you have registered an account with us,
                                you might be able to see a tracking code for this shipping, in your Order History section
                                after you login.</td>
                        </tr>
                        <tr>
                            <td>Delivered</td>
                            <td>According to the tracking status from the shipping carrier, your order has been delivered.
                                Please check at your delivery address for your order package immediately as soon as you can.
                                We don't guarantee any possible loss at your end after the order has been delivered, unless
                                you have bought the delivery insurance.</td>
                        </tr>
                        <tr>
                            <td>Cancel Requested</td>
                            <td>You have just requested to cancel the order. When applicable, and if this order hasn't been
                                shipped yet, we'll honor this request and issue you the refund for your order. If we
                                accepted this Cancel request, the order status will become Canceled.</td>
                        </tr>
                        <tr>
                            <td>Canceled</td>
                            <td>Your order has been canceled. The order package won't be shipped, and a refund might have
                            been issued, if applicable.</td>
                        </tr>
                        <tr>
                            <td>Returned</td>
                            <td>Please contact us if you'd like to return your order. Please keep in mind that most of
                                our items are final sale. However, when applicable, we may accept your return. We reserve
                                the right to accept or refuse a return request. A order status of "Returned" means we have
                                received your returned package, and we will review it and potentially issue you the refund
                                if the package meets our return expectation, or we might have to ship it back to you if
                                it doesn't.</td>
                        </tr>
                    </tbody>
                </table>

                <h1>Payment Codes</h1>

                <table>
                    <tbody>
                        <tr>
                            <th>Code</th>
                            <th>Description</th>
                        </tr>
                        <tr>
                            <td>Paid</td>
                            <td>You have paid for your order.</td>
                        </tr>
                        <tr>
                            <td>Declined</td>
                            <td>Your payment has been declined. Your order will soon be canceled. Please contact us as
                                soon as you can if you'd like to continue your oder.</td>
                        </tr>
                        <tr>
                            <td>Refunded</td>
                            <td>A refund has been issued to you since your order has been canceled or you have successfully
                                returned the order. Please allow a few business days for the refund to arrive back to your
                                payment account.</td>
                        </tr>
                    </tbody>
                </table>

            </div>

        </div>

    </div>

@endsection