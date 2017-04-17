@extends('layouts.admin')

@section('title', 'Admin - Manage Product Attributes - ' . config('app.name'))

@section('content')

    <div class="row">

        <div class="col-md-12">

            <h1>Attributes</h1>

            <table class="table table-striped">
                <tr>
                    <th>Name</th>
                    <th>Display Style</th>
                    <th>Options</th>
                    <th>Edit</th>
                    <th>Remove</th>
                </tr>
                @foreach ($attributes as $attribute)
                    <tr>
                        <td>{{ Html::link(route('admin.attributes.attribute', $attribute['id']), $attribute['name']) }}</td>
                        <td>{{ $attribute['display'] == 'select' ? 'Drop-down list' : 'Radio check' }}</td>
                        <td>{{ $attribute->options()->count() }}</td>
                        <td><a href="{{ route('admin.attributes.attribute', $attribute['id']) }}" title="Edit this attribute" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i> Edit</a></td>
                        <td>
                            {{ Form::open(['route' => ['admin.attributes.remove', $attribute['id']], 'method' => 'DELETE', 'onsubmit' => 'return confirm("Do you really want to remove this attribute?");']) }}

                            <button type="submit" class="btn btn-sm btn-danger" title="Remove this attribute"><i class="fa fa-times"></i> Remove</button>

                            {{ Form::close() }}
                        </td>
                    </tr>
                @endforeach
            </table>

            {{ Html::link(route('admin.attributes.create'), ' Add new Attribute', ['class' => 'btn btn-lg btn-success fa fa-plus']) }}<br /><br />

        </div>

    </div>

@endsection