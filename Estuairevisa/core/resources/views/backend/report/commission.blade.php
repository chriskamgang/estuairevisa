@extends('backend.layout.master')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __($pageTitle) }}</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div class="form-inline">
                                    <label for="" class="mr-2">{{ __('Search Trx') }}</label>
                                    <input type="text" class="form-control" id="myInput">
                                </div>
                                <div class="card-header-form">
                                    <form action="">
                                        <div class="form-group mb-0">
                                            <a href="javascript:void(0)"
                                                class="btn btn-primary daterange-btn btn-d icon-left btn-icon filterData"><i
                                                    class="fas fa-calendar"></i> {{ __('Filter By Date') }}
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="card-body p-2">

                                <table class="table" id="example">
                                    <thead>
                                        <tr>
                                            <th scope="col">{{ __('Commison From') }}</th>
                                            <th scope="col">{{ __('Commison To') }}</th>
                                            <th scope="col">{{ __('Amount') }}</th>
                                            <th scope="col">{{ __('Commision Date') }}</th>
                                        </tr>

                                    </thead>
                                    <tbody id="appendFilter">

                                        @forelse ($commison as $item)
                                            <tr>
                                                <td data-caption="From">{{ @$item->parent->username }}</td>
                                                <td data-caption="To">{{ @$item->child->username }}</td>
                                                <td data-caption="Amount">{{ number_format($item->amount, 2) }}
                                                    {{ @$general->site_currency }}</td>
                                                <td data-caption="{{ __('date') }}">
                                                    {{ $item->created_at->format('y-m-d') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td data-caption="Data" class="text-center" colspan="100%">
                                                    {{ __('No Data Found') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>

                                </table>

                            </div>

                            @if ($commison->hasPages())
                                <div class="card-footer">
                                    {{ $commison->links('backend.partial.paginate') }}
                                </div>
                            @endif



                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection



@push('script')
    <script>
        $(function() {
            'use strict'

            $("#myInput").on("input", function() {
                var value = $(this).val().toLowerCase();

                $("#example tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            $('.daterange-btn').daterangepicker({
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')]
                },
                startDate: moment().subtract(29, 'days'),
                endDate: moment()
            }, function(start, end) {
                $('.daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format(
                    'MMMM D, YYYY'))
            });


            $('.ranges ul li').each(function(index) {
                $(this).on('click', function() {
                    let key = $(this).data('range-key')
                    $("#overlay").fadeIn(300);
                    $.ajax({
                        url: "{{ route('admin.payment.report') }}",
                        data: {
                            key: key
                        },
                        method: "GET",
                        success: function(response) {

                            $('#appendFilter').html(response);
                        },
                        complete: function() {
                            $("#overlay").fadeOut(300);
                        }

                    })


                })
            })

            $(document).on('click', '.applyBtn', function() {
                let dateStrat = $('input[name=daterangepicker_start]').val()
                let dateEnd = $('input[name=daterangepicker_end]').val()
                let key = 'Custom Range'
                $("#overlay").fadeIn(300);
                $.ajax({
                    url: "{{ route('admin.payment.report') }}",
                    data: {
                        key: key,
                        startdate: dateStrat,
                        dateEnd: dateEnd
                    },
                    method: "GET",
                    success: function(response) {

                        $('#appendFilter').html(response);
                    },
                    complete: function() {
                        $("#overlay").fadeOut(300);
                    }

                })
            })

        })
    </script>
@endpush







@push('style')
    <style>
        .pagination .page-item.active .page-link {
            background-color: rgb(95, 116, 235);
            border: none;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: transparent;
            border-color: transparent;
        }

        .pagination .page-item.active .page-link:hover {
            background-color: rgb(95, 116, 235);
        }
    </style>
@endpush
