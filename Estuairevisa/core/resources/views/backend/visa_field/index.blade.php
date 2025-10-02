@extends('backend.layout.master')

@section('content')
<div class="main-content">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>{{ __('Field List') }}</h4>
            <a href="{{ route('admin.field.create') }}" class="btn btn-primary">{{ __('Create Field') }}</a>
        </div>

        <div class="card-body p-0 table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>{{ __('Icon') }}</th>
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('Label') }}</th>
                        <th>{{ __('name') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($fields as $field)
                    <tr>
                        <td><img src="{{ getFile('field', $field->image) }}" class="avatar-image" alt="icon"></td>
                        <td>{{ $field->title }}</td>
                        <td>{{ $field->label}}</td>
                        <td>{{ $field->name}}</td>
                        <td>
                            @if ($field->status)
                                <span class="badge badge-success">{{ __('Active') }}</span>
                            @else
                                <span class="badge badge-danger">{{ __('Inactive') }}</span>
                            @endif
                        </td>
                        
                        <td>
                            <a href="{{ route('admin.field.edit', $field->id) }}" class="btn btn-sm btn-primary mr-1">
                                <i class="fa fa-edit"></i>
                            </a>

                            <button class="btn btn-md btn-danger delete mr-1"
                                data-href="{{ route('admin.field.destroy', $field->id) }}"><i
                                    class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">{{ __('No Field available.') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{ $fields->links() }}
        </div>
    </div>
</div>


<div class="modal fade" tabindex="-1" id="delete" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="" method="post">
            @csrf
            @method('DELETE')


            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Delete Field') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <p>{{ __('Are You Sure to Delete') }}?</p>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>

                    <button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>


@endsection





@push('script')
<script>
    $(function() {
            'use strict';

            
            $('.delete').on('click', function() {
                const modal = $('#delete');
                modal.find('form').attr('action', $(this).data('href'));

                modal.modal('show');
            });

        });
</script>
@endpush