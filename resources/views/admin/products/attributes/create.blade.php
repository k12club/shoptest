@extends('layouts.admin')

@section('title', 'Admin - Add new Product Attribute - ' . config('app.name'))

@section('content')

    <div class="row">

        <div class="col-md-12">

            <h2>Admin - Add new Product Attribute</h2>

            @include('snippets.errors')
            @include('snippets.flash')

            {{ Form::open(['action' => ['Admin\AttributesController@store'], 'role' => 'form']) }}

            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <div class="form-group{{ $errors->has('key') ? ' has-error' : '' }}">
                        {{ Form::label('key', 'Attribute Key:', ['class' => 'control-label required']) }}

                        {{
                            Form::text('key', old('key'), [
                                'class'       => 'form-control',
                                'id'          => 'key',
                                'maxlength'   => 100,
                                'placeholder' => 'Attribute key',
                                'required'    => true,
                            ])
                        }}

                        @include('snippets.errors_first', ['param' => 'key'])
                    </div>

                    <div class="form-group{{ $errors->has('value') ? ' has-error' : '' }}">
                        {{ Form::label('value', 'Attribute Value:', ['class' => 'control-label required']) }}
                        {{
                            Form::text('value', old('value'), [
                                'class'       => 'form-control',
                                'id'          => 'value',
                                'maxlength'   => 100,
                                'placeholder' => 'Attribute value',
                                'required'    => true,
                            ])
                        }}

                        @include('snippets.errors_first', ['param' => 'value'])
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Submit</button>

                        {{ Html::link(route('admin.attributes'), 'Cancel', ['title' => 'Click here to cancel']) }}
                    </div>
                </div>

            </div>

            {{ Form::close() }}

        </div>

    </div>

@endsection