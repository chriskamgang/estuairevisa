@extends('backend.layout.master')

@section('content')
<div class="main-content">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>{{ __('Apply List') }}</h4>
            <form action="">
                <div class="input-group">
                    <input type="text" class="form-control" name="order_number"
                        value="{{request()->order_number ?? ''}}" placeholder="{{__('Track Number')}}" >
                    <button class="btn btn-outline-secondary" type="submit" id="button-addon2"> {{__('Search')}}</button>
                </div>
            </form>
        </div>

        <div class="card-body p-0 table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>{{__("User")}}</th>
                        <th>{{__("Track Number")}}</th>
                        <th>{{__("Plan")}}</th>
                        <th>{{__("Price")}}</th>
                        <th>{{__("Status")}}</th>
                        <th>{{__("Action")}}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                    <tr>
                        <td>
                            <div class="d-flex">
                                <div class="pr-3">
                                    <img src="{{getFile('user',$item->checkout->user->image)}}" class="avatar-image">
                                </div>
                                <div>
                                    <a href="{{route('admin.user.details',$item->checkout->user_id)}}">{{$item->checkout->user->fullname}}
                                        </br>{{$item->checkout->user->email}}</a>
                                </div>
                            </div>
                        </td>
                        <td>{{$item->order_number}}</td>
                        <td>{{$item->plan->title}}</td>
                        <td>{{number_format($item->price,2)}}</td>
                        <td>
                            <?= $item->status() ?>
                        </td>
                        <td>
                            <a href="{{route('admin.visa.details',$item->order_number)}}"
                                class="btn btn-sm btn-primary mr-1">
                                <i class="fa fa-eye"></i>
                            </a>
                            <button data-action="{{route('admin.visa.change.status',$item->order_number)}}"
                                data-status="{{$item->status}}" class="btn btn-sm change_status_btn btn-info mr-1">
                                <i class="fa fa-bell"></i>
                            </button>
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
            {{ $items->links() }}
        </div>
    </div>
</div>


<div class="modal fade" tabindex="-1" id="changeStatus" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="" method="post">
            @csrf


            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Change Status') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label for="">{{__("Status")}}</label>
                        <select name="status" class="form-control">
                            <option value="pending">{{__("Pending")}}</option>
                            <option value="issues">{{__("Issues")}}</option>
                            <option value="under_review">{{__("Under Review")}}</option>
                            <option value="proccessing">{{__("Proccessing")}}</option>
                            <option value="complete">{{__("Complete")}}</option>
                            <option value="shipped">{{__("Shipped")}}</option>
                            <option value="reject">{{__("Rejected")}}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="">{{__("Note")}}</label>
                        <textarea name="note" class="form-control"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>

                    <button type="submit" class="btn btn-danger">{{ __('Submit') }}</button>
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

            $(".change_status_btn").on('click',function(){

                let modal = $("#changeStatus");
                    modal.find('form').attr('action',$(this).data('action'));
                    modal.find("select[name='status']").val($(this).data('status'));
                    modal.modal('show');

            });


        });
</script>
@endpush