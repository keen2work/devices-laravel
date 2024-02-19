@extends('oxygen::layouts.master-dashboard')

@section('content')
    {{ lotus()->pageHeadline($pageTitle) }}

    {{ lotus()->breadcrumbs([
        ['Dashboard', route('dashboard')],
        ['Devices', route('manage.devices.index')],
        [$pageTitle, null, true]
    ]) }}

    <table class="table table-hover table-responsive-sm">
        <tbody>
            @foreach ($entity->getShowable() as $field)
                <tr>
                    <td>{{ ucwords(\ElegantMedia\PHPToolkit\Text::reverseSnake($field)) }}</td>
                    <td width="75%" class="text-break">{{ $entity->$field }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection



