<?php

namespace App\Helpers;

class CustomHelper {

    /**
     * Render S3 image URL
     *
     * @param $name
     * @param bool $thumbnail
     * @return string
     */
    public static function image($name, $thumbnail = false) {
        return env('S3_URL') . '/' . env('S3_BUCKET') . '/photos' . ($thumbnail ? '/thumbnails/' : '/') . $name;
    }

    /**
     * Format a 10-digit phone number from xxxxxxxxxx to (xxx) xxx-xxxx
     *
     * @param $phone
     * @return string
     */
    public static function formatPhoneNumber($phone) {
        return strlen($phone) == 10 ? '(' . substr($phone, 0, 3) . ') ' . substr($phone, 3, 3) . '-' .substr($phone,6) : $phone;
    }

    /**
     * Format address
     *
     * @param $data
     * @return string
     */
    public static function formatAddress($data) {
        $output = '';

        if (!empty($data['name'])) {
            $output .= $data['name'];
        } else {
            if (!empty($data['first_name'])) {
                $output .= $data['first_name'];
            }

            if (!empty($data['last_name'])) {
                $output .= ' ' . $data['last_name'];
            }
        }

        if (!empty($data['address_1'])) {
            if (!empty($data['name']) || !empty($data['first_name']) || !empty($data['last_name'])) {
                $output .= '<br />';
            }
            $output .= $data['address_1'];
        }

        if (!empty($data['address_2'])) {
            if (!empty($data['address_1'])) {
                $output .= ' ';
            }
            $output .= $data['address_2'];
        }

        if (!empty($data['city'])) {
            if (!empty($data['address_1']) || !empty($data['address_2'])) {
                $output .= '<br />';
            }
            $output .= $data['city'];
        }

        if (!empty($data['state'])) {
            if (!empty($data['city'])) {
                $output .= ', ';
            }
            $output .= $data['state'];
        }

        if (!empty($data['zipcode'])) {
            if (!empty($data['state'])) {
                $output .= ' ';
            }
            $output .= $data['zipcode'];
        }

        return $output;
    }

    /**
     * Format credit card
     *
     * @param $card
     * @return string
     */
    public static function formatCard($card) {
        $brand = '';

        switch($card['brand']) {
            case 'Visa':
                $brand = 'visa';
                break;
            case 'MasterCard':
                $brand = 'mastercard';
                break;
            case 'American Express':
                $brand = 'amex';
                break;
            case 'Discover':
                $brand = 'discover';
                break;
            case 'Diners Club':
                $brand = 'diners-club';
                break;
            case 'JCB':
                $brand = 'jcb';
                break;
        }

        return '<i class="fa fa-cc-' . $brand . '" title="'.$card['brand'].'"></i>' . ' ***' . $card['last4'] . ' - ' . $card['name_on_card'];
    }

    /**
     * Format order status
     *
     * @param $order
     * @return string
     */
    public function formatOrderStatus($order) {
        if ($order['status'] == 'processing') {
            return '<span class="label label-info" title="' . config('custom.order_status.' . $order['status']) . '">' . config('custom.order_status.' . $order['status']) . '</span>';
        } else {
            return '<span class="label label-default" title="' . config('custom.order_status.' . $order['status']) . '">' . config('custom.order_status.' . $order['status']) . '</span>';
        }
    }

    /**
     * Format order payment status
     *
     * @param $order
     * @return string
     */
    public function formatOrderPaymentStatus($order) {
        if ($order['payment_status'] == 'paid') {
            return '<span class="label label-success" title="' . config('custom.payment_status.' . $order['payment_status']) . ($order['payment_method'] == 'cash' ? ' in cash' : ' with card'). '"><i class="fa fa-' . ($order['payment_method'] == 'cash' ? 'money' : 'credit-card') . '"></i > ' . config('custom.payment_status.' . $order['payment_status']) . '</span>';
        } else if ($order['payment_status'] == 'refunded') {
            return '<span class="label label-default" title="' . config('custom.payment_status.' . $order['payment_status']) . '">' . config('custom.payment_status.' . $order['payment_status']) . '</span>';
        } else {
            return '<span class="label label-danger" title="' . config('custom.payment_status.' . $order['payment_status']) . '">' . config('custom.payment_status.' . $order['payment_status']) . '</span>';
        }
    }

    /**
     * Format tracking URL
     *
     * @param $order
     * @return string
     */
    public function formatTrackingURL($order) {
        switch ($order['shipping_carrier']) {
            case 'usps':
                return !empty($order['shipping_tracking_number']) ?
                    '<a href="https://tools.usps.com/go/TrackConfirmAction?tLabels=' . $order['shipping_tracking_number'] . '" target="_blank">' . $order['shipping_tracking_number'] . '</a>)':
                    'Not available';
                break;

            case 'ups':
                return !empty($order['shipping_tracking_number']) ?
                    '<a href="https://wwwapps.ups.com/WebTracking/track?track=yes&trackNums=' . $order['shipping_tracking_number'] . '" target="_blank">' . $order['shipping_tracking_number'] . '</a>)':
                    'Not available';
                break;

            default:
                return 'Not available';
        }
    }

    /**
     * Compute all fees from a given Cart
     *
     * @param $cart
     * @return array
     */
    public static function computeFees($cart) {
        $fees = [
            'items' => collect($cart['items'])->sum('quantity'),
            'subtotal' => collect($cart['items'])->reduce(function ($carry, $item) { return $carry + ($item['price'] * $item['quantity']); })
        ];

        // Compute tax
        $fees['tax'] = round($fees['subtotal'] * config('custom.tax') / 100, 2);

        // Compute shipping fees
        $shipping = [
            'config' => config('custom.checkout.shipping')
        ];

        $shipping['default'] = $shipping['config']['default'];

        if (isset($cart['shipping'])) {
            if ($cart['shipping'] == 'cash_on_delivery') {
                $fees['shipping'] = 0;
            } else {
                $fees['shipping'] = $shipping['config']['carriers'][$cart['shipping']['carrier']]['plans'][$cart['shipping']['plan']]['fee'];
            }
        } else {
            $fees['shipping'] = $shipping['config']['carriers'][$shipping['default'][0]]['plans'][$shipping['default'][1]]['fee'];
        }

        $fees['total'] = $fees['subtotal'] + $fees['tax'] + $fees['shipping'];

        return $fees;
    }

}