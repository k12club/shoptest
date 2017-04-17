@extends('layouts.admin')

@section('title', 'Admin - Create new Product - ' . config('app.name'))

@section('content')

    <div class="row">

        <div class="col-md-12">

            <h2>Create new Product</h2>

            @include('snippets.errors')
            @include('snippets.flash')

            {{ Form::open(['route' => ['admin.products.store'], 'files' => true, 'role' => 'form']) }}

            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        {{ Form::label('name', 'Product Name:', ['class' => 'control-label required']) }}

                        {{
                            Form::text('name', old('name'), [
                                'class'       => 'form-control',
                                'id'          => 'name',
                                'maxlength'   => 255,
                                'placeholder' => 'Product name',
                                'required'    => true,
                            ])
                        }}

                        @include('snippets.errors_first', ['param' => 'name'])
                    </div>

                    <div class="form-group{{ $errors->has('uri') ? ' has-error' : '' }}">
                        {{ Form::label('uri', 'Product URI:', ['class' => 'control-label required']) }}

                        {{
                            Form::text('uri', old('uri'), [
                                'class'       => 'form-control',
                                'id'          => 'uri',
                                'maxlength'   => 255,
                                'placeholder' => 'product-uri',
                                'required'    => true,
                            ])
                        }}

                        <p class="help-block">To be used in the URL, make sure to use lowercase with hyphen, i.e. "product-title".</p>

                        @include('snippets.errors_first', ['param' => 'uri'])
                    </div>

                    <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                        {{ Form::label('description', 'Product Description:', ['class' => 'control-label']) }}

                        {{
                            Form::textarea('description', old('description'), [
                                'class'       => 'form-control',
                                'id'          => 'description',
                                'maxlength'   => 2048,
                                'placeholder' => 'Product description',
                            ])
                        }}

                        @include('snippets.errors_first', ['param' => 'description'])
                    </div>

                    <div class="form-group{{ $errors->has('price') ? ' has-error' : '' }}">
                        {{ Form::label('price', 'Price:', ['class' => 'control-label required']) }}

                        {{
                            Form::text('price', old('price'), [
                                'class'       => 'form-control',
                                'id'          => 'price',
                                'maxlength'   => 10,
                                'placeholder' => '0.00',
                                'required'    => true,
                            ])
                        }}

                        @include('snippets.errors_first', ['param' => 'price'])
                    </div>

                    <div class="form-group{{ $errors->has('old_price') ? ' has-error' : '' }}">
                        {{ Form::label('old-price', 'Old Price:', ['class' => 'control-label']) }}

                        {{
                            Form::text('old_price', old('old_price'), [
                                'class'       => 'form-control',
                                'id'          => 'old-price',
                                'maxlength'   => 10,
                                'placeholder' => '0.00',
                            ])
                        }}

                        @include('snippets.errors_first', ['param' => 'old_price'])
                    </div>

                    <div class="form-group{{ $errors->has('category_id') ? ' has-error' : '' }}">
                        {{ Form::label('category-id', 'Category:', ['class' => 'control-label required']) }}

                        {{
                            Form::select('category_id',
                                array_reduce($categories->toArray(), function ($result, $item) {
                                    $result[$item['id']] = $item['name'];
                                    return $result;
                                }, []), old('category_id'), [
                                    'class' => 'form-control',
                                    'id'    => 'category-id',
                                ]
                            )
                        }}

                        @include('snippets.errors_first', ['param' => 'category_id'])
                    </div>

                    <div class="form-group">
                        {{ Form::label('special', 'Special:', ['class' => 'control-label']) }}

                        {{ Form::checkbox('special', 'on') }}

                        <p class="help-block">Check this to mark this product as a "special" product, which will be shown
                            in the Special block in homepage.</p>

                        @include('snippets.errors_first', ['param' => 'special'])
                    </div>

                    <div class="form-group">
                        {{ Form::label('new', 'New:', ['class' => 'control-label']) }}

                        {{ Form::checkbox('new', 'on') }}

                        <p class="help-block">Check this to mark this product as a "new" product, which will have a "New"
                            ribbon on its thumbnail image.</p>

                        @include('snippets.errors_first', ['param' => 'new'])
                    </div>

                    <div class="form-group">
                        {{ Form::label('photos', 'Photos:', ['class' => 'control-label']) }}

                        {{ Form::file( 'photos[]', ['multiple' => true] ) }}
                    </div>

                    <div class="form-group{{ $errors->has('order') ? ' has-error' : '' }}">
                        {{ Form::label('order', 'Order:', ['class' => 'control-label required']) }}

                        {{
                            Form::input('number', 'order', old('order'), [
                                'class'       => 'form-control',
                                'id'          => 'order',
                                'maxlength'   => 10,
                                'placeholder' => '1',
                                'required'    => true,
                            ])
                        }}

                        <p class="help-block">Set the order of this product in its category.</p>

                        @include('snippets.errors_first', ['param' => 'order'])
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" title="Create this new product"><i class="fa fa-save"></i> Submit</button>

                        {{ Html::link(route('admin.products'), 'Cancel', ['title' => 'Click here to cancel']) }}
                    </div>

                    @if (env('SHOP_DEMO'))
                        <div class="form-group">
                            <p class="alert alert-info"><i class="fa fa-exclamation-circle"></i> Please note that photo upload is disabled in this demo.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{ Form::close() }}

        </div>

    </div>

@endsection