<h2>Options</h2>

<table class="table table-striped">
    <tr>
        <th>Order</th>
        <th>Option</th>
        <th>Edit</th>
        <th>Remove</th>
    </tr>
    @foreach ($attribute->options()->orderBy('order')->get() as $option)
        <tr>
            <td>{{ $option['order'] }}</td>
            <td>{!! $option['name'] !!}</td>
            <td><a href="{{ route('admin.attributes.attribute.options.option', [$attribute['id'], $option['id']]) }}" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i> Edit</a></td>
            <td>
                {{ Form::open(['route' => ['admin.attributes.attribute.options.remove', $attribute['id'], $option['id']], 'method' => 'DELETE', 'onsubmit' => 'return confirm("Do you really want to remove this option?");']) }}

                <button type="submit" class="btn btn-sm btn-danger">Remove</button>

                {{ Form::close() }}
            </td>
        </tr>
    @endforeach
</table>

<a href="{{ route('admin.attributes.attribute.options.create', $attribute['id']) }}" class="btn btn-sm btn-success" title="Add a new option to this attribute"><i class="fa fa-plus"></i> Add new Option</a><br /><br />