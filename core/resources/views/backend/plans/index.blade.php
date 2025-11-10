@extends('backend.layout.master')

@section('content')
    <div class="main-content">
        <div class="manage-plans">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>{{ __('Plans List') }}</h4>
                    <a href="{{ route('admin.plans.create') }}" class="btn btn-primary">{{ __('Create Plan') }}</a>
                </div>

                <div class="card-body p-0 table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>{{ __('Title') }}</th>
                                <th>{{ __('Type') }}</th>
                                <th>{{ __('Price') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Recommended') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($plans as $plan)
                                <tr>
                                    <td>{{ $plan->title }}</td>
                                    <td><span class="badge badge-info">{{ ucfirst(str_replace('_', ' ', $plan->plan_type)) }}</span></td>
                                    <td>{{ number_format($plan->price, 2) }} {{ $general->site_currency ?? 'FCFA' }}</td>
                                    <td>
                                        @if ($plan->status)
                                            <span class="badge badge-success">{{ __('Active') }}</span>
                                        @else
                                            <span class="badge badge-danger">{{ __('Inactive') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($plan->is_recommended)
                                            <span class="badge badge-warning">{{ __('Yes') }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ __('No') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.plans.edit', $plan->id) }}" class="btn btn-sm btn-primary mr-1">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <button class="btn btn-md btn-danger delete mr-1"
                                                data-href="{{ route('admin.plans.destroy', $plan) }}"><i
                                                    class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">{{ __('No plans available.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-footer">
                    {{ $plans->links() }}
                </div>
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
                        <h5 class="modal-title">{{ __('Delete Plan') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <p>{{ __('Are You Sure to Delete') }}?</p>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ __('Close') }}</button>

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
