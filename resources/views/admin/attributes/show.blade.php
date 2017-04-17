@extends('layouts.admin')

@section('title', 'Admin - Edit Attribute - ' . config('app.name'))

@section('content')

    <div class="row">

        <div class="col-md-5">

            <h2>Details</h2>

            @include('snippets.errors')
            @include('snippets.flash')

            {{ Form::open(['route' => ['admin.attributes.update', $attribute['id']]]) }}

            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        {{ Form::label('name', 'Attribute:', ['class' => 'control-label required']) }}

                        {{
                            Form::text('name', old('name', $attribute['name']), [
                                'class'       => 'form-control',
                                'id'          => 'name',
                                'maxlength'   => 45,
                                'placeholder' => 'Attribute',
                                'required'    => true,
                            ])
                        }}

                        @include('snippets.errors_first', ['param' => 'name'])
                    </div>

                    <div class="form-group{{ $errors->has('display') ? ' has-error' : '' }}">
                        {{ Form::label('display', 'Display Style:', ['class' => 'control-label required']) }}

                        {{
                            Form::select('display', [
                                'radio'  => 'Radio check',
                                'select' => 'Drop-down list'
                            ], old('display', $attribute['display']), [
                                'class'    => 'form-control',
                                'id'       => 'display',
                                'required' => true,
                            ])
                        }}

                        @include('snippets.errors_first', ['param' => 'display'])
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update</button>

                        {{ Html::link(route('admin.attributes'), 'Cancel', ['class' => 'btn', 'title' => 'Cancel']) }}
                    </div>
                </div>

            </div>

            {{ Form::close() }}

        </div>

        <div class="col-md-7">
            @include('admin.attributes.options.index')
        </div>

    </div>

@endsection