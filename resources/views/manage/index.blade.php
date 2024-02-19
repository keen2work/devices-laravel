@extends('oxygen::layouts.master-dashboard')

@section('breadcrumbs')
    {{ lotus()->breadcrumbs([
        ['Dashboard', route('dashboard')],
        [$pageTitle, null, true]
    ]) }}
@stop

@section('pageMainActions')
    @include('oxygen::dashboard.partials.searchField')
@stop

@section('content')
	<?php $tableHeader = ['Device ID', 'Device Type', 'Push Token', 'Latest IP', 'User', 'Actions'] ?>
    @if (has_feature('notifications.send-device-push-notifications'))
        <?php $tableHeader = ['Device ID', 'Device Type', 'Push Token', 'Latest IP', 'User', 'Notifications', 'Actions'] ?>
    @endif

    @include('oxygen::dashboard.partials.table-allItems', [
        'tableHeader' => $tableHeader
    ])

    @foreach ($allItems as $item)
        <tr>
            <td>
                <a href="{{ entity_resource_path() . '/' . $item->id }}">{{ $item->device_id }}</a>
            </td>
            <td>{{ $item->device_type }}</td>
            <td style="word-break: break-word">{{ $item->device_push_token }}</td>
            <td>{{ $item->latest_ip_address }}</td>
            <td>
                @if ($item->user)
                    {{ $item->user->full_name }}
                @endif
            </td>
            @if (has_feature('notifications.send-device-push-notifications'))
                <td>
                    <a href="{{ route('manage.push-notifications.create', ['device_id' => $item->id]) }}" class="btn btn-warning">Send Notification</a>
                </td>
            @endif
            <td>
                <form action="{{ entity_resource_path() . '/' . $item->id }}" method="POST" class="form form-inline js-confirm">
                    {{ method_field('delete') }}
                    {{ csrf_field() }}
                    <button class="btn btn-danger btn-sm"><em class="fa fa-times"></em> Delete</button>
                </form>
            </td>
        </tr>
    @endforeach
@stop
