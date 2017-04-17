<div class="row">
    <div class="col-md-3">
        <h5 style="line-height: normal; margin-top: 0;">Delivery</h5>
    </div>
    <div class="col-md-9">
        @if (auth()->check())
            @if (auth()->user()->deliveryAddresses()->count() > 0)
                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" name="delivery[address_id]" value="{{ auth()->user()->deliveryAddress()->first()['id'] }}" />
                        <span class="pull-left" id="delivery-current-address">
                            {!! CustomHelper::formatAddress(auth()->user()->deliveryAddress()->first()) !!}
                        </span>
                        <span class="pull-right">
                            <a href="#" id="delivery-change-address" class="btn" data-toggle="modal" data-target="#delivery-modal">Change address</a>
                        </span>
                    </div>
                </div>
            @else
                @include('shop.checkout.delivery_fieldset')
            @endif
        @else
            @include('shop.checkout.delivery_fieldset')
        @endif

        <br />

        {{
            Form::text('notes', old('notes'), [
                'class'       => 'form-control',
                'maxlength'   => 255,
                'placeholder' => 'Notes: gate code (if needed), building, etc.',
            ])
        }}

        <hr class="separator" /><br />
    </div>
</div>

@section('bottom_block')
    @parent
    <script>
        $( document ).ready(function() {
            var deliveryForm = $('#delivery-address-form');

            // On click on 'Add new address' button
            $('#delivery-new-address-btn').click(function(e) {
                e.preventDefault();

                $(this).toggleClass('hide');

                $('#delivery-current-addresses, #delivery-address-form').toggleClass('hide');

                deliveryForm.find('[name="id"]').val('');
                deliveryForm.find('[name="delivery[name]"]').val('');
                deliveryForm.find('[name="delivery[phone]"]').val('');
                deliveryForm.find('[name="delivery[address_1]"]').val('');
                deliveryForm.find('[name="delivery[address_2]"]').val('');
                deliveryForm.find('[name="delivery[city]"]').val('');
                deliveryForm.find('[name="delivery[state]"]').val('');
                deliveryForm.find('[name="delivery[zipcode]"]').val('');
            });

            // On click on 'Cancel' manage address button
            $('#delivery-address-cancel-btn').click(function(e) {
                e.preventDefault();

                $('#delivery-current-addresses, #delivery-address-form, #delivery-new-address-btn').toggleClass('hide');
            });

            // On click on 'Edit' address button
            $("#delivery-current-addresses").on('click', 'a[id^="delivery-edit-address-btn"]', function(e){
                e.preventDefault();

                var id = $(this).attr('id'),
                    addressId = id.substr(26),
                    li = $(this).closest('li'),
                    name = li.data('name'),
                    phone = li.data('phone'),
                    address_1 = li.data('address_1'),
                    address_2 = li.data('address_2'),
                    city = li.data('city'),
                    state = li.data('state'),
                    zipcode = li.data('zipcode');

                $('#delivery-current-addresses, #delivery-address-form, #delivery-new-address-btn').toggleClass('hide');

                deliveryForm.find('[name="id"]').val(addressId);
                deliveryForm.find('[name="delivery[name]"]').val(name);
                deliveryForm.find('[name="delivery[phone]"]').val(phone);
                deliveryForm.find('[name="delivery[address_1]"]').val(address_1);
                deliveryForm.find('[name="delivery[address_2]"]').val(address_2);
                deliveryForm.find('[name="delivery[city]"]').val(city);
                deliveryForm.find('[name="delivery[state]"]').val(state);
                deliveryForm.find('[name="delivery[zipcode]"]').val(zipcode);
            });

            // On click on 'Make Primary' button
            $("#delivery-current-addresses").on('click', 'a.primary', function(e){
                e.preventDefault();

                var id = $(this).closest('li').attr('id'),
                    addressId = id.substr(17);

                $.get('/addresses/' + addressId + '/primary', function(data) {
                    if (typeof data['status'] != 'undefined' && data['status'] === true) {
                        var currentPrimary = $('li[data-default-delivery="1"]');
                        currentPrimary.find('.primary').replaceWith('<a href="#" class="primary">Make primary</a>');
                        currentPrimary.attr('data-default-delivery', '').data('default-primary', '');

                        var newPrimary = $('li[id="delivery-address-'+addressId+'"]');
                        newPrimary.find('.primary').replaceWith('<span class="primary"><i class="fa fa-check"></i> Primary</span>');
                        newPrimary.attr('data-default-delivery', '1').data('default-primary', '1');
                    }
                });
            });

            // On click on 'Delete' button
            $("#delivery-current-addresses").on('click', 'a.remove', function(e){
                e.preventDefault();

                var id = $(this).closest('li').attr('id'),
                    addressId = id.substr(17);

                if (addressId == $('input[name="delivery[address_id]"]').val()) {
                    alert('You cannot delete this delivery address because it is currently selected for this order. Please select another address for delivery first.');
                    return false;
                }

                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                    },
                    url: '/addresses/' + addressId,
                    type: "POST",
                    data: {
                        "_method": "delete"
                    },
                    cache: false,
                    success: function(data) {
                        if (typeof data['status'] != 'undefined') {
                            if (data['status'] === true) {
                                $('li[id="delivery-address-'+addressId+'"]').remove();
                            } else if (typeof data['error'] != 'undefined') {
                                alert(data['error']['msg']);
                            }
                        }
                    }
                });
            });

            // On click on 'Select' address area
            $("#delivery-current-addresses").on('click', 'a[id^="delivery-select-address-"]', function(e){
                e.preventDefault();

                var id = $(this).attr('id'),
                    addressId = id.substr(24),
                    li = $(this).closest('li'),
                    name = li.data('name'),
                    address_1 = li.data('address_1'),
                    address_2 = li.data('address_2'),
                    city = li.data('city'),
                    state = li.data('state'),
                    zipcode = li.data('zipcode');

                $('#delivery-current-address').html(name + '<br />' + address_1 + ' ' + address_2 + '<br />' + city + ', ' + state + ' ' + zipcode);

                $('input[name="delivery[address_id]"]').val(addressId);

                $('#delivery-modal').modal('hide');
            });

            // On submission of the delivery form (create/edit)
            deliveryForm.submit(function() {
                var form = $(this),
                    address = {
                        "id": form.find('[name="id"]').val(),
                        "name": form.find('[name="delivery[name]"]').val(),
                        "phone": form.find('[name="delivery[phone]"]').val(),
                        "address_1": form.find('[name="delivery[address_1]"]').val(),
                        "address_2": form.find('[name="delivery[address_2]"]').val(),
                        "city": form.find('[name="delivery[city]"]').val(),
                        "state": form.find('[name="delivery[state]"]').val(),
                        "zipcode": form.find('[name="delivery[zipcode]"]').val(),
                        "is_delivery": true
                    };

                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                    },
                    url: form.attr('action'),
                    type: "POST",
                    data: address,
                    cache: false,
                    success: function(data) {
                        if (typeof data['status'] != 'undefined' && data['status'] === true) {
                            var liData = {
                                    "name": address['name'],
                                    "phone": address['name'],
                                    "address_1": address['address_1'],
                                    "address_2": address['address_2'],
                                    "city": address['city'],
                                    "state": address['state'],
                                    "zipcode": address['zipcode']
                                },
                                addressSelect = '<a href="#" class="address" id="delivery-select-address-'+data['address_id']+'" title="Use this address for this delivery">' +
                                address['name'] + '<br />' +
                                address['address_1'] +  ' ' + address['address_2'] + '<br />' +
                                address['city'] +  ', ' + address['state'] + ' ' + address['zipcode'] + '<br />' +
                                '</a>';

                            if (data['act'] == 'create') {
                                if (typeof data['address_id'] != 'undefined') {
                                    var li = $("<li/>");

                                    li.attr('id', 'delivery-address-' + data['address_id']);

                                    li.attr({
                                        "data-default-delivery": "",
                                        "data-name": liData['name'],
                                        "data-phone": liData['phone'],
                                        "data-address_1": liData['address_1'],
                                        "data-address_2": liData['address_2'],
                                        "data-city": liData['city'],
                                        "data-state": liData['state'],
                                        "data-zipcode": liData['zipcode']
                                    }).data({
                                        "default-delivery": "",
                                        "name": liData['name'],
                                        "phone": liData['phone'],
                                        "address_1": liData['address_1'],
                                        "address_2": liData['address_2'],
                                        "city": liData['city'],
                                        "state": liData['state'],
                                        "zipcode": liData['zipcode']
                                    });

                                    li.addClass('row address-row').html('<div class="col-md-8">' +
                                        addressSelect +
                                        '</div>' +
                                        '<div class="col-md-4 text-right">' +
                                        '   <a href="#" id="delivery-edit-address-btn-'+data['address_id']+'">Edit</a> |' +
                                        '   <a href="#">Delete</a><br /><br />' +
                                        '   <a href="#" class="primary">Make primary</a>' +
                                        '</div>');

                                    $('#delivery-current-addresses').append(li);
                                }
                            } else {
                                if (typeof data['address_id'] != 'undefined') {
                                    var selectAddressBtn = $('#delivery-select-address-' + data['address_id']),
                                        li = selectAddressBtn.closest('li');

                                    selectAddressBtn.replaceWith(addressSelect);

                                    li.attr({
                                        "data-default-delivery": "",
                                        "data-name": liData['name'],
                                        "data-phone": liData['phone'],
                                        "data-address_1": liData['address_1'],
                                        "data-address_2": liData['address_2'],
                                        "data-city": liData['city'],
                                        "data-state": liData['state'],
                                        "data-zipcode": liData['zipcode']
                                    }).data({
                                        "default-delivery": "",
                                        "name": liData['name'],
                                        "phone": liData['phone'],
                                        "address_1": liData['address_1'],
                                        "address_2": liData['address_2'],
                                        "city": liData['city'],
                                        "state": liData['state'],
                                        "zipcode": liData['zipcode']
                                    });
                                }
                            }

                            $('#delivery-current-addresses, #delivery-address-form, #delivery-new-address-btn').toggleClass('hide');
                        }
                    },
                    error: function() {
                    }
                });

                return false;
            });
        });
    </script>
@endsection