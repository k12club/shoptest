<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Invoices or receipts</title>
    <style>
        /* -------------------------------------
    GLOBAL
------------------------------------- */
        * {
            margin: 0;
            padding: 0;
            font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
            box-sizing: border-box;
            font-size: 14px;
        }

        img {
            max-width: 100%;
        }

        body {
            -webkit-font-smoothing: antialiased;
            -webkit-text-size-adjust: none;
            width: 100% !important;
            height: 100%;
            line-height: 1.6;
        }

        /* Let's make sure all tables have defaults */
        table td {
            vertical-align: top;
        }

        /* -------------------------------------
            BODY & CONTAINER
        ------------------------------------- */
        body {
            background-color: #f6f6f6;
        }

        .body-wrap {
            background-color: #f6f6f6;
            width: 100%;
        }

        .container {
            display: block !important;
            max-width: 600px !important;
            margin: 0 auto !important;
            /* makes it centered */
            clear: both !important;
        }

        .content {
            max-width: 600px;
            margin: 0 auto;
            display: block;
            padding: 20px;
        }

        /* -------------------------------------
            HEADER, FOOTER, MAIN
        ------------------------------------- */
        .main {
            background: #fff;
            border: 1px solid #e9e9e9;
            border-radius: 3px;
        }

        .content-wrap {
            padding: 20px;
        }

        .content-block {
            padding: 0 0 20px;
        }

        .header {
            width: 100%;
            margin-bottom: 20px;
        }

        .footer {
            width: 100%;
            clear: both;
            color: #999;
            padding: 20px;
        }
        .footer a {
            color: #999;
        }
        .footer p, .footer a, .footer unsubscribe, .footer td {
            font-size: 12px;
        }

        /* -------------------------------------
            GRID AND COLUMNS
        ------------------------------------- */
        .column-left {
            float: left;
            width: 50%;
        }

        .column-right {
            float: left;
            width: 50%;
        }

        /* -------------------------------------
            TYPOGRAPHY
        ------------------------------------- */
        h1, h2, h3 {
            font-family: "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
            color: #000;
            margin: 40px 0 0;
            line-height: 1.2;
            font-weight: 400;
        }

        h1 {
            font-size: 32px;
            font-weight: 500;
        }

        h2 {
            font-size: 24px;
        }

        h3 {
            font-size: 18px;
        }

        h4 {
            font-size: 14px;
            font-weight: 600;
        }

        p, ul, ol {
            margin-bottom: 10px;
            font-weight: normal;
        }
        p li, ul li, ol li {
            margin-left: 5px;
            list-style-position: inside;
        }

        /* -------------------------------------
            LINKS & BUTTONS
        ------------------------------------- */
        a {
            color: #348eda;
            text-decoration: underline;
        }

        .btn-primary {
            text-decoration: none;
            color: #FFF;
            background-color: #348eda;
            border: solid #348eda;
            border-width: 10px 20px;
            line-height: 2;
            font-weight: bold;
            text-align: center;
            cursor: pointer;
            display: inline-block;
            border-radius: 5px;
            text-transform: capitalize;
        }

        /* -------------------------------------
            OTHER STYLES THAT MIGHT BE USEFUL
        ------------------------------------- */
        .last {
            margin-bottom: 0;
        }

        .first {
            margin-top: 0;
        }

        .padding {
            padding: 10px 0;
        }

        .aligncenter {
            text-align: center;
        }

        .alignright {
            text-align: right;
        }

        .alignleft {
            text-align: left;
        }

        .clear {
            clear: both;
        }

        /* -------------------------------------
            Alerts
        ------------------------------------- */
        .alert {
            font-size: 16px;
            color: #fff;
            font-weight: 500;
            padding: 20px;
            text-align: center;
            border-radius: 3px 3px 0 0;
        }
        .alert a {
            color: #fff;
            text-decoration: none;
            font-weight: 500;
            font-size: 16px;
        }
        .alert.alert-warning {
            background: #ff9f00;
        }
        .alert.alert-bad {
            background: #d0021b;
        }
        .alert.alert-good {
            background: #68b90f;
        }

        /* -------------------------------------
            INVOICE
        ------------------------------------- */
        .invoice {
            margin: 40px auto;
            text-align: left;
            width: 80%;
        }
        .invoice td {
            padding: 5px 0;
        }
        .invoice .invoice-items {
            width: 100%;
        }
        .invoice .invoice-items td {
            border-top: #eee 1px solid;
        }
        .invoice .invoice-items .total td {
            border-top: 2px solid #333;
            border-bottom: 2px solid #333;
            font-weight: 700;
        }

        /* -------------------------------------
            RESPONSIVE AND MOBILE FRIENDLY STYLES
        ------------------------------------- */
        @media only screen and (max-width: 640px) {
            h1, h2, h3, h4 {
                font-weight: 600 !important;
                margin: 20px 0 5px !important;
            }

            h1 {
                font-size: 22px !important;
            }

            h2 {
                font-size: 18px !important;
            }

            h3 {
                font-size: 16px !important;
            }

            .container {
                width: 100% !important;
            }

            .content, .content-wrapper {
                padding: 10px !important;
            }

            .invoice {
                width: 100% !important;
            }
        }
    </style>
</head>

<body>

<table class="body-wrap">
    <tr>
        <td></td>
        <td class="container" width="600">
            <div class="content">
                <table class="main" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="content-wrap aligncenter">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td class="content-block">
                                        <h1>Order #: {!! $orderNumber !!}</h1>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        <h2>{!! $name !!} has placed a new order.</h2>
                                        <h3>A summary of the purchase is shown below.</h3>
                                        <br /><br />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        <table class="invoice">
                                            <tr>
                                                <td>
                                                    Confirmation Code: {!! $confirmationCode !!}<br />
                                                    Shipping Carrier: {!! $shippingConfig['carriers'][$shippingCarrier]['name'] !!}<br />
                                                    Shipping Method: {!! $shippingConfig['carriers'][$shippingCarrier]['methods'][$shippingMethod]['method'] !!}<br />
                                                    Subtotal: ${!! money_format('%i', $fees['subtotal']) !!} USD<br />
                                                    Tax: ${!! money_format('%i', $fees['tax']) !!} USD<br />
                                                    Shipping: ${!! money_format('%i', $fees['shipping']) !!} USD<br />
                                                    Total: ${!! money_format('%i', $fees['total']) !!} USD<br />
                                                    <br /><br />
                                                    Contact Email: {{ $email }}<br />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <strong>Bill To:</strong><br />
                                                                {!!
                                                                    CustomHelper::formatAddress([
                                                                        'first_name' => $billing['first_name'],
                                                                        'last_name'  => $billing['last_name'],
                                                                        'address_1'  => $billing['address_1'],
                                                                        'address_2'  => $billing['address_2'],
                                                                        'city'       => $billing['city'],
                                                                        'state'      => $billing['state'],
                                                                        'zipcode'    => $billing['zipcode']
                                                                    ])
                                                                !!}
                                                                <br /><br />
                                                                <strong>Billing Phone Number:</strong><br />
                                                                {{ $billing['phone'] }}
                                                            </td>
                                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                            <td>
                                                                <strong>Ship To:</strong><br />
                                                                {!!
                                                                    CustomHelper::formatAddress([
                                                                        'first_name' => $delivery['first_name'],
                                                                        'last_name'  => $delivery['last_name'],
                                                                        'address_1'  => $delivery['address_1'],
                                                                        'address_2'  => $delivery['address_2'],
                                                                        'city'       => $delivery['city'],
                                                                        'state'      => $delivery['state'],
                                                                        'zipcode'    => $delivery['zipcode']
                                                                    ])
                                                                !!}
                                                                <br /><br />
                                                                <strong>Shipping Phone Number:</strong><br />
                                                                {{ $delivery['phone'] }}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <br /><br />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <table class="invoice-items" cellpadding="0" cellspacing="0">
                                                        @foreach($cartItems as $cartItem)
                                                        <?php
                                                            $item = $items[$cartItem['id']];
                                                            if (!empty($item['photos']) && !empty($item['photos'][0])) {
                                                                $item['thumbnail'] = CustomHelper::image($item['photos'][0]['name'], true);
                                                            }

                                                            $inventoryItem = $inventoryItems[$cartItem['sku']];
                                                        ?>
                                                        <tr>
                                                            <th>Item</th>
                                                            <th class="alignright">Count</th>
                                                            <th class="alignright">Price</th>
                                                            <th class="alignright">Total</th>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                @if (!empty($item['thumbnail']))
                                                                    <a href="{!! route('shop.product', ['uri' => $item['uri'], 'id' => $item['id']]) !!}"><img width="150px" src="{!! $item['thumbnail'] !!}" alt="{!! $item['name'] !!}" /></a><br />
                                                                @endif
                                                                <a href="{!! route('shop.product', ['uri' => $item['uri'], 'id' => $item['id']]) !!}">{!! $item['name'] !!}</a>

                                                                @if (!empty($inventoryItem['options']))
                                                                    <?php
                                                                    $inventoryItemOptions = !empty($inventoryItem['options']) ? json_decode($inventoryItem['options'], true) : [];
                                                                    ?>
                                                                    <ul>
                                                                        @foreach($inventoryItemOptions as $attributeId => $optionIds)
                                                                            <li>
                                                                                {!! $attributes[$attributeId] !!}:
                                                                                <?php
                                                                                $optionValues = [];
                                                                                foreach($optionIds as $optionId) {
                                                                                    foreach($options[$attributeId] as $option) {
                                                                                        if ($option['id'] == $optionId && $option['attribute_id'] == $attributeId) {
                                                                                            $optionValues[] = $option['value'];
                                                                                            break;
                                                                                        }
                                                                                    }
                                                                                }
                                                                                if (count($optionValues) > 0) {
                                                                                    echo implode(', ', $optionValues);
                                                                                }
                                                                                ?>
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                @endif
                                                            </td>
                                                            <td class="alignright">{!! $cartItem['count'] !!}</td>
                                                            <td class="alignright">${!! money_format('%i', $cartItem['price']) !!}</td>
                                                            <td class="alignright">${!! money_format('%i', $cartItem['price'] * $cartItem['count']) !!}</td>
                                                        </tr>
                                                        @endforeach
                                                    </table>
                                                    <br /><br />
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        {{ config('app.name') }} - 123 Main St. Los Angeles CA 92683
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <div class="footer">
                    <table width="100%">
                        <tr>
                            <td class="aligncenter content-block">Questions? Email <a href="mailto:">{!! env('OWNER_EMAIL') !!}</a></td>
                        </tr>
                    </table>
                </div></div>
        </td>
        <td></td>
    </tr>
</table>

</body>
</html>