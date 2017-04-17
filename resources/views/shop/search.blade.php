@extends('layouts.master')

@section('title', 'Search Results - ' . config('app.name'))

@section('content')

    <div class="row">
        <div class="col-md-12">
            <h1>
                Search Results

                <div class="btn-group pull-right">
                    <a href="{{ route('search', ['q' => request()->get('q')]) }}" class="btn btn-default{{ request()->get('display') == 'list' ? '' : ' active' }}" title="Display items in a grid view"><i class="fa fa-th-large"></i></a>
                    <a href="{{ route('search', ['q' => request()->get('q'), 'display' => 'list']) }}" class="btn btn-default{{ request()->get('display') == 'list' ? ' active' : '' }}" title="Display items in a list view"><i class="fa fa-th-list"></i></a>
                </div>
            </h1>
        </div>
    </div>

    <!-- Product Listing -->
    @if (count($products) > 0)
        @if (request()->get('display') == 'list')
            @include('shop.products.list_list')
        @else
            @include('shop.products.list_grid')
        @endif
    @else
        <div class="alert alert-warning">There are no items matched your search results, please search again.</div>
    @endif

@endsection