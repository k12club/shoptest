@extends('layouts.admin')

@section('title', 'Admin - Add new Option - ' . config('app.name'))

@section('content')

    <div class="row">

        <div class="col-md-12">

            <h2>Add new Option</h2>

            @include('snippets.errors')
            @include('snippets.flash')

            {{ Form::open(['route' => ['admin.attributes.attribute.options.store', $attribute['id']], 'role' => 'form']) }}

            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        {{ Form::label('name', 'Option:', ['class' => 'control-label required']) }}

                        {{
                            Form::text('name', old('name'), [
                                'class'       => 'form-control',
                                'id'          => 'name',
                                'maxlength'   => 45,
                                'placeholder' => 'Option',
                                'required'    => true,
                            ])
                        }}

                        @include('snippets.errors_first', ['param' => 'name'])
                    </div>

                    <div class="form-group">
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

                        <p class="help-block">Set the order of this option.</p>

                        @include('snippets.errors_first', ['param' => 'order'])
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-warning"><i class="fa fa-save"></i> Submit</button>

                        {{ Html::link(route('admin.attributes.attribute', $attribute['id']), 'Cancel', ['class' => 'btn', 'title' => 'Cancel']) }}
                    </div>
                </div>

            </div>

            {{ Form::close() }}

        </div>

    </div>

@endsection