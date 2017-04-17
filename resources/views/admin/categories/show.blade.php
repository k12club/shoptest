@extends('layouts.admin')

@section('title', 'Admin - Edit Category - ' . config('app.name'))

@section('content')

    <div class="row">

        <div class="col-md-12">

            <h2>Edit Category</h2>

            @include('snippets.errors')
            @include('snippets.flash')

            {{ Form::open(['route' => ['admin.categories.update', $category['id']], 'role' => 'form']) }}

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        {{ Form::label('name', 'Category name:', ['class' => 'control-label required']) }}

                        {{
                            Form::text('name', old('name', $category['name']), [
                                'class'       => 'form-control',
                                'id'          => 'name',
                                'maxlength'   => 45,
                                'placeholder' => 'Category name',
                                'required'    => true,
                            ])
                        }}

                        @include('snippets.errors_first', ['param' => 'name'])
                    </div>

                    <div class="form-group{{ $errors->has('uri') ? ' has-error' : '' }}">
                        {{ Form::label('uri', 'Category URI:', ['class' => 'control-label required']) }}

                        {{
                            Form::text('uri', old('uri', $category['uri']), [
                                'class'       => 'form-control',
                                'id'          => 'uri',
                                'maxlength'   => 45,
                                'placeholder' => 'category-uri',
                                'required'    => true,
                            ])
                        }}

                        <p class="help-block">
                            This will appear as category name in the URL. Please use lower case only, with no spaces,
                            and separate words by hyphen.
                        </p>

                        @include('snippets.errors_first', ['param' => 'uri'])
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" title="Save this category"><i class="fa fa-save"></i> Save</button>
                    </div>
                </div>

            </div>

            {{ Form::close() }}

        </div>

    </div>

@endsection