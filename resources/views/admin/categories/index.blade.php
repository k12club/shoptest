@extends('layouts.admin')

@section('title', 'Admin - Manage Categories - ' . config('app.name'))

@section('content')

    <div class="row">

        <div class="col-md-12">

            <h1>Categories</h1>

            <table class="table table-striped">
                <tr>
                    <th>Name</th>
                    <th>Products</th>
                    <th>Edit</th>
                    <th>Remove</th>
                </tr>
                @foreach ($categories as $category)
                    <tr>
                        <td>{{ Html::link(route('admin.categories.category', $category['id']), $category['name']) }}</td>
                        <td>{{ $category->products()->count() }}</td>
                        <td><a href="{{ route('admin.categories.category', $category['id']) }}" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i> Edit</a></td>
                        <td>
                            {{ Form::open(['route' => ['admin.categories.remove', $category['id']], 'method' => 'DELETE', 'onsubmit' => 'return confirm("Do you really want to remove this category?");']) }}

                            <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> Remove</button>

                            {{ Form::close() }}
                        </td>
                    </tr>
                @endforeach
            </table>

            <a href="{{ route('admin.categories.create') }}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Add new Category</a><br /><br />

        </div>

    </div>

@stop

<script>
    function remove(url) {
        var r = confirm("Are you sure you want to remove this category?");
        if (r == true) {
            window.location.replace(url);
        } else {
            return false;
        }
    }
</script>