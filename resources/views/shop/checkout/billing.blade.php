<div class="row" id="billing-block">
    <div class="col-md-3">
        <h5 style="line-height: normal; margin-top: 0;">Billing</h5>
    </div>
    <div class="col-md-9">
        @if (auth()->check())
            @if (auth()->user()->billingAddresses()->count() > 0)
                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" name="billing[address_id]" value="{{ auth()->user()->billingAddress()->first()['id'] }}" />
                        <span class="pull-left" id="billing-current-address">
                            {!! CustomHelper::formatAddress(auth()->user()->billingAddress()->first()) !!}
                        </span>
                        <span class="pull-right">
                            <a href="#" id="billing-change-address" class="btn" data-toggle="modal" data-target="#billing-modal">Change address</a>
                        </span>
                    </div>
                </div>
            @else
                @include('shop.checkout.copy_address')

                @include('shop.checkout.billing_fieldset')
            @endif
        @else
            @include('shop.checkout.copy_address')

            @include('shop.checkout.billing_fieldset')
        @endif

        <hr class="separator" /><br />
    </div>
</div>

@section('bottom_block')
    @parent
    <script>
        $( document ).ready(function() {
            // If the 'Copy address' checkbox is checked (if page is loaded after submission error)
            if ($('input[type="checkbox"][name^="copy_address"]').prop('checked')) {
                $('#billing-fieldset').attr('disabled', true).addClass('hide');
            }

            $('input[type="checkbox"][name^="copy_address"]').change(function() {
                $('#billing-fieldset').attr('disabled', $(this).prop('checked')).toggleClass('hide', $(this).prop('checked'));
            });

            var billingForm = $('#billing-address-form');

            // On click on 'Add new address' button
            $('#billing-new-address-btn').click(function(e) {
                e.preventDefault();

                $(this).toggleClass('hide');

                $('#billing-current-addresses, #billing-address-form').toggleClass('hide');

                billingForm.find('[name="id"]').val('');
                billingForm.find('[name="billing[name]"]').val('');
                billingForm.find('[name="billing[phone]"]').val('');
                billingForm.find('[name="billing[address_1]"]').val('');
                billingForm.find('[name="billing[address_2]"]').val('');
                billingForm.find('[name="billing[city]"]').val('');
                billingForm.find('[name="billing[state]"]').val('');
                billingForm.find('[name="billing[zipcode]"]').val('');
            });

            // On click on 'Cancel' manage address button
            $('#billing-address-cancel-btn').click(function(e) {
                e.preventDefault();

                $('#billing-current-addresses, #billing-address-form, #billing-new-address-btn').toggleClass('hide');
            });

            // On click on 'Edit' address button
            $("#billing-current-addresses").on('click', 'a[id^="billing-edit-address-btn"]', function(e){
                e.preventDefault();

                var id = $(this).attr('id'),
                        addressId = id.substr(25),
                        li = $(this).closest('li'),
                        name = li.data('name'),
                        phone = li.data('phone'),
                        address_1 = li.data('address_1'),
                        address_2 = li.data('address_2'),
                        city = li.data('city'),
                        state = li.data('state'),
                        zipcode = li.data('zipcode');

                $('#billing-current-addresses, #billing-address-form, #billing-new-address-btn').toggleClass('hide');

                billingForm.find('[name="id"]').val(addressId);
                billingForm.find('[name="billing[name]"]').val(name);
                billingForm.find('[name="billing[phone]"]').val(phone);
                billingForm.find('[name="billing[address_1]"]').val(address_1);
                billingForm.find('[name="billing[address_2]"]').val(address_2);
                billingForm.find('[name="billing[city]"]').val(city);
                billingForm.find('[name="billing[state]"]').val(state);
                billingForm.find('[name="billing[zipcode]"]').val(zipcode);
            });

            // On click on 'Make Primary' button
            $("#billing-current-addresses").on('click', 'a.primary', function(e){
                e.preventDefault();

                var id = $(this).closest('li').attr('id'),
                    addressId = id.substr(16);

                $.get('/addresses/' + addressId + '/primary', function(data) {
                    if (typeof data['status'] != 'undefined' && data['status'] === true) {
                        var currentPrimary = $('li[data-default-billing="1"]');
                        currentPrimary.find('.primary').replaceWith('<a href="#" class="primary">Make primary</a>');
                        currentPrimary.attr('data-default-billing', '').data('default-primary', '');

                        var newPrimary = $('li[id="billing-address-'+addressId+'"]');
                        newPrimary.find('.primary').replaceWith('<span class="primary"><i class="fa fa-check"></i> Primary</span>');
                        newPrimary.attr('data-default-billing', '1').data('default-primary', '1');
                    }
                });
            });

            // On click on 'Delete' button
            $("#billing-current-addresses").on('click', 'a.remove', function(e){
                e.preventDefault();

                var id = $(this).closest('li').attr('id'),
                    addressId = id.substr(16);

                if (addressId == $('input[name="billing[address_id]"]').val()) {
                    alert('You cannot delete this billing address because it is currently selected for this order. Please select another address for billing first.');
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
                                $('li[id="billing-address-'+addressId+'"]').remove();
                            } else if (typeof data['error'] != 'undefined') {
                                alert(data['error']['msg']);
                            }
                        }
                    }
                });
            });

            // On click on 'Select' address area
            $("#billing-current-addresses").on('click', 'a[id^="billing-select-address-"]', function(e){
                e.preventDefault();

                var id = $(this).attr('id'),
                    addressId = id.substr(23),
                    li = $(this).closest('li'),
                    name = li.data('name'),
                    address_1 = li.data('address_1'),
                    address_2 = li.data('address_2'),
                    city = li.data('city'),
                    state = li.data('state'),
                    zipcode = li.data('zipcode');

                $('#billing-current-address').html(name + '<br />' + address_1 + ' ' + address_2 + '<br />' + city + ', ' + state + ' ' + zipcode);

                $('input[name="billing[address_id]"]').val(addressId);

                $('#billing-modal').modal('hide');
            });

            // On submission of the billing form (create/edit)
            billingForm.submit(function() {
                var form = $(this),
                    address = {
                        "id": form.find('[name="id"]').val(),
                        "name": form.find('[name="billing[name]"]').val(),
                        "phone": form.find('[name="billing[phone]"]').val(),
                        "address_1": form.find('[name="billing[address_1]"]').val(),
                        "address_2": form.find('[name="billing[address_2]"]').val(),
                        "city": form.find('[name="billing[city]"]').val(),
                        "state": form.find('[name="billing[state]"]').val(),
                        "zipcode": form.find('[name="billing[zipcode]"]').val(),
                        "is_billing": true
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
                                addressSelect = '<a href="#" class="address" id="billing-select-address-'+data['address_id']+'" title="Use this address for this billing">' +
                                        address['name'] + '<br />' +
                                        address['address_1'] +  ' ' + address['address_2'] + '<br />' +
                                        address['city'] +  ', ' + address['state'] + ' ' + address['zipcode'] + '<br />' +
                                        '</a>';

                            if (data['act'] == 'create') {
                                if (typeof data['address_id'] != 'undefined') {
                                    var li = $("<li/>");

                                    li.attr('id', 'billing-address-' + data['address_id']);

                                    li.attr({
                                        "data-default-billing": "",
                                        "data-name": liData['name'],
                                        "data-phone": liData['phone'],
                                        "data-address_1": liData['address_1'],
                                        "data-address_2": liData['address_2'],
                                        "data-city": liData['city'],
                                        "data-state": liData['state'],
                                        "data-zipcode": liData['zipcode']
                                    }).data({
                                        "default-billing": "",
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
                                        '   <a href="#" id="billing-edit-address-btn-'+data['address_id']+'">Edit</a> |' +
                                        '   <a href="#">Delete</a><br /><br />' +
                                        '   <a href="#" class="primary">Make primary</a>' +
                                        '</div>');

                                    $('#billing-current-addresses').append(li);
                                }
                            } else {
                                if (typeof data['address_id'] != 'undefined') {
                                    var selectAddressBtn = $('#billing-select-address-' + data['address_id']),
                                        li = selectAddressBtn.closest('li');

                                    selectAddressBtn.replaceWith(addressSelect);

                                    li.attr({
                                        "data-default-billing": "",
                                        "data-name": liData['name'],
                                        "data-phone": liData['phone'],
                                        "data-address_1": liData['address_1'],
                                        "data-address_2": liData['address_2'],
                                        "data-city": liData['city'],
                                        "data-state": liData['state'],
                                        "data-zipcode": liData['zipcode']
                                    }).data({
                                        "default-billing": "",
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

                            $('#billing-current-addresses, #billing-address-form, #billing-new-address-btn').toggleClass('hide');
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