@extends('backend.layout.master')

@section('content')
<div class="main-content">
    <div class="manage-country">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>{{ __('Countries') }}</h4>
                <button class="btn btn-primary add">{{ __('Add Country') }}</button>
            </div>

            <div class="card-body p-0 table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('Flag') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Slider') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($countries as $country)
                        <tr>
                            <td><img src="{{ getFile('country', $country->image) }}" class="avatar-image" alt="flag">
                            </td>
                            <td>{{ $country->name }}</td>
                            <td>
                                @if ($country->status)
                                <span class="badge badge-primary">{{ __('Active') }}</span>
                                @else
                                <span class="badge badge-warning">{{ __('Inactive') }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($country->is_slider)
                                <span class="badge badge-primary">{{ __('Yes') }}</span>
                                @else
                                <span class="badge badge-warning">{{ __('No') }}</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm edit mr-1"
                                    data-href="{{ route('admin.country.update', $country->id) }}"
                                    data-country='@json($country)'>
                                    <i class="fa fa-pen"></i>
                                </button>

                                <button class="btn btn-danger btn-sm delete"
                                    data-href="{{ route('admin.country.delete', $country->id) }}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                {{ $countries->links() }}
            </div>
        </div>
    </div>
</div>


<div class="modal fade" tabindex="-1" id="add" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="{{ route('admin.country.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Add Country') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ __('Name') }}</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Flag Image') }}</label>
                        <input type="file" name="image" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Show Slider') }}</label>
                        <select name="is_slider" class="form-control">
                            <option value="1">{{ __('Yes') }}</option>
                            <option value="0">{{ __('No') }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Status') }}</label>
                        <select name="status" class="form-control">
                            <option value="1">{{ __('Active') }}</option>
                            <option value="0">{{ __('Inactive') }}</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>


<div class="modal fade" tabindex="-1" id="edit" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Edit Country') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ __('Name') }}</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Flag Image') }}</label>
                        <input type="file" name="image" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>{{ __('Show Slider') }}</label>
                        <select name="is_slider" class="form-control">
                            <option value="1">{{ __('Yes') }}</option>
                            <option value="0">{{ __('No') }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Status') }}</label>
                        <select name="status" class="form-control">
                            <option value="1">{{ __('Active') }}</option>
                            <option value="0">{{ __('Inactive') }}</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>


<div class="modal fade" tabindex="-1" id="delete" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Delete Country') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>{{ __('Are you sure you want to delete this country?') }}</p>
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
    $(function () {
            'use strict'

            $('.add').on('click', function () {
                $('#add').modal('show')
            })

            $('.edit').on('click', function () {
                const modal = $('#edit')
                const data = $(this).data('country')

                modal.find('form').attr('action', $(this).data('href'))
                modal.find('input[name=name]').val(data.name)
                modal.find('select[name=status]').val(data.status)
                modal.find('select[name=is_slider]').val(data.is_slider)
                modal.modal('show')
            })

            $('.delete').on('click', function () {
                const modal = $('#delete')
                modal.find('form').attr('action', $(this).data('href'))
                modal.modal('show')
            })
        })
</script>
@endpush