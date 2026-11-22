@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

@section('breadcrumb')
@if (!request()->routeIs('dashboard.index'))
<x-breadcrumb title="Dashboard" page="Dashboard" active="" route="{{ route('dashboard.index') }}" />
@endif
@endsection

<div class="page-content">
    <section class="row">
        <div class="col-12">

            @if (auth()->user()->hasAnyRole(['super-admin', 'admin']))
                @include('pages.dashboard.card')
            @else
                @include('pages.dashboard.member')
            @endif

        </div>

    </section>
</div>

@endsection