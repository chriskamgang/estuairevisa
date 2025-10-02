@extends('backend.layout.master')
@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{ __($pageTitle) }}</h1>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="card">

                    <img src="{{ getFile('user', $user->image) }}" class="w-100">


                    <div class="container my-3">
                        <h4>{{ $user->full_name }}</h4>
                        <p class="title">{{ $user->designation }}</p>
                        <p class="title">{{ $user->email }}</p>
                        <a href="" class="btn btn-primary sendMail">{{ __('Send Mail To user') }}</a>
                    </div>
                </div>
                <div class="card card-statistic-1">
                    <div class="card-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{ __('User Balance') }}</h4>
                        </div>
                        <div class="card-body">
                            {{ number_format($user->balance, 2) . ' ' . $general->site_currency }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <p>{{ __('Add Balance') }}</p>
                                <form action="{{ route('admin.user.balance.update', $user->id) }}" method="post">
                                    @csrf
                                    <div class="input-group mb-3">
                                        <input type="hidden" class="form-control" name="user_id"
                                            value="{{ $user->id }}">
                                        <input type="hidden" class="form-control" name="type" value="add">
                                        <input type="text" class="form-control" name="balance">
                                        <button class="btn btn-primary" type="submit" id="button-addon2"> <i
                                                class="fa fa-plus"></i> {{ __('Add Balance') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <p>{{ __('Subtruct Balance') }}</p>
                                <form action="{{ route('admin.user.balance.update', $user->id) }}" method="post">
                                    @csrf
                                    <div class="input-group mb-3">
                                        <input type="hidden" class="form-control" name="user_id"
                                            value="{{ $user->id }}">
                                        <input type="hidden" class="form-control" name="type" value="minus">
                                        <input type="text" class="form-control" name="balance">
                                        <button class="btn btn-danger" type="submit" id="button-addon2"> <i
                                                class="fa fa-minus"></i> {{ __('Subtruct Balance') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.user.update', $user->id) }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>{{ __('First Name') }}</label>
                                    <input type="text" name="fname" class="form-control" value="{{ $user->fname }}">
                                </div>
                                <div class="col-md-6 mb-3">

                                    <label>{{ __('Last Name') }}</label>
                                    <input type="text" name="lname" class="form-control" value="{{ $user->lname }}">
                                </div>

                                <div class="form-group col-md-6 mb-3 ">
                                    <label>{{ __('Phone') }}</label>
                                    <input type="text" name="phone" class="form-control" value="{{ $user->phone }}">
                                </div>
                                <div class="form-group col-md-6 mb-3 ">
                                    <label>{{ __('Country') }}</label>
                                    <input type="text" name="country" class="form-control"
                                        value="{{ $user->address->country ?? '' }}">
                                </div>

                                <div class="col-md-6 mb-3">

                                    <label>{{ __('city') }}</label>
                                    <input type="text" name="city" class="form-control form_control"
                                        value="{{ $user->address->city ?? '' }}">
                                </div>

                                <div class="col-md-6 mb-3">

                                    <label>{{ __('zip') }}</label>
                                    <input type="text" name="zip" class="form-control form_control"
                                        value="{{ $user->address->zip ?? '' }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>{{ __('state') }}</label>
                                    <input type="text" name="state" class="form-control form_control"
                                        value="{{ $user->address->state ?? '' }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>{{ 'Status' }}</label>
                                    <select name="status" id="" class="form-control selectric">

                                        <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>
                                            {{ 'Inactive' }}</option>
                                        <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>
                                            {{ 'Active' }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 mt-4">
                                <button type="submit" class="btn btn-primary w-100">{{ 'Update User' }}</button>
                            </div>
                    </div>
                    </form>

                </div>

            </div>


            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ __('Reference Tree') }}</h4>
                    </div>
                    <div class="card-body">
                        @php
                        $reference = \App\Models\User::where('reffered_by', $user->username)->get();
                        @endphp
                        @if ($reference->count() > 0)
                        <div class="tree">
                            <ul>
                                <li>
                                    <a href="#">
                                        <img src="{{ getFile('user', $user->image) }}" class="ref-img" alt="">
                                        <p class="mb-0">{{ $user->full_name }}</p>
                                    </a>
                                    <ul>


                                        @foreach ($reference as $user)
                                        <li>
                                            <a href="{{ route('admin.user.details', $user) }}">
                                                <img src="{{ getFile('user', $user->image) }}" class="ref-img" alt="">
                                                <p class="mb-0">{{ $user->full_name }}</p>
                                            </a>

                                        </li>
                                        @endforeach

                                    </ul>
                                </li>
                            </ul>
                        </div>
                        @else
                        <div class="col-md-12 text-center mt-5">
                            <i class="far fa-sad-tear display-1"></i>
                            <p class="mt-2">
                                {{ __('No Reference User Found') }}
                            </p>

                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
</div>
</section>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="mail">
    <div class="modal-dialog" role="document">
        <form action="{{ route('admin.user.mail', $user->id) }}" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Send Mail to user') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">

                        <label for="">{{ __('Subject') }}</label>

                        <input type="text" name="subject" class="form-control">

                    </div>

                    <div class="form-group">

                        <label for="">{{ __('Message') }}</label>

                        <textarea name="message" id="" cols="30" rows="10" class="form-control summernote"></textarea>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Send Mail') }}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('style')
<style>
    .tree ul {
        padding-top: 20px;
        position: relative;

        transition: all 0.5s;
        -webkit-transition: all 0.5s;
        -moz-transition: all 0.5s;
    }

    .ref-img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        -o-object-fit: cover
    }

    .tree li {
        float: left;
        text-align: center;
        list-style-type: none;
        position: relative;
        padding: 20px 5px 0 5px;

        transition: all 0.5s;
        -webkit-transition: all 0.5s;
        -moz-transition: all 0.5s;

        margin-bottom: 15px;
    }

    .tree li::before,
    .tree li::after {
        content: '';
        position: absolute;
        top: 0;
        right: 50%;
        border-top: 1px solid #ccc;
        width: 50%;
        height: 20px;
    }

    .tree li::after {
        right: auto;
        left: 50%;
        border-left: 1px solid #ccc;
    }

    /*We need to remove left-right connectors from elements without
                                    any siblings*/
    .tree li:only-child::after,
    .tree li:only-child::before {
        display: none;
    }

    /*Remove space from the top of single children*/
    .tree li:only-child {
        padding-top: 0;
    }

    /*Remove left connector from first child and
                                    right connector from last child*/
    .tree li:first-child::before,
    .tree li:last-child::after {
        border: 0 none;
    }

    /*Adding back the vertical connector to the last nodes*/
    .tree li:last-child::before {
        border-right: 1px solid #ccc;
        border-radius: 0 5px 0 0;
        -webkit-border-radius: 0 5px 0 0;
        -moz-border-radius: 0 5px 0 0;
    }

    .tree li:first-child::after {
        border-radius: 5px 0 0 0;
        -webkit-border-radius: 5px 0 0 0;
        -moz-border-radius: 5px 0 0 0;
    }

    /*Time to add downward connectors from parents*/
    .tree ul ul::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        border-left: 1px solid #ccc;
        width: 0;
        height: 20px;
    }

    .tree li a {
        border: 1px solid #ccc;
        padding: 5px 20px;
        text-decoration: none;
        color: #666;
        font-family: arial, verdana, tahoma;
        font-size: 11px;
        display: inline-block;

        border-radius: 5px;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;

        transition: all 0.5s;
        -webkit-transition: all 0.5s;
        -moz-transition: all 0.5s;
    }

    /*Time for some hover effects*/
    /*We will apply the hover effect the the lineage of the element also*/
    .tree li a:hover,
    .tree li a:hover+ul li a {
        background: #c8e4f8;
        color: #000;
        border: 1px solid #94a0b4;
    }

    /*Connector styles on hover*/
    .tree li a:hover+ul li::after,
    .tree li a:hover+ul li::before,
    .tree li a:hover+ul::before,
    .tree li a:hover+ul ul::before {
        border-color: #94a0b4;
    }
</style>
@endpush

@push('script')
<script>
    $(function() {
            'use strict';
            $('.sendMail').on('click', function(e) {
                e.preventDefault();

                const modal = $('#mail');

                modal.modal('show');
            })

            $('#country option').each(function(index) {

                let country = "{{ $user->address->country ?? '' }}"

                if ($(this).val() == country) {
                    $(this).attr('selected', 'selected')
                }


            })
        })
</script>
@endpush