@extends('layouts.admin')

@section('title', 'Admin - Users - ' . config('app.name'))

@section('content')

    <div class="row">

        <div class="col-md-12">

            <h1>Users ({{ count($users) }})</h1>

            @if (count($users) > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Type</th>
                            <th>Joined</th>
                        </tr>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ Html::link(route('admin.users.user', $user['id']), $user['first_name'] . ' ' . $user['last_name']) }}</td>
                                <td>{{ $user['email'] }}</td>
                                <td>{{ $user['type'] == 'admin' ? 'Admin' : 'Customer' }}</td>
                                <td><span title="{{ $user['created_at']->timezone(config('custom.timezone'))->toDayDateTimeString() }}">{{ $user['created_at']->diffForHumans() }}</span></td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            @else
                <div class="alert alert-warning">There are no users at the present.</div>
            @endif

        </div>

    </div>

@endsection