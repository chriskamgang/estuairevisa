@extends('backend.layout.master')
@section('content')
<div class="main-content">
    <section class="section pt-2">
        <div class="row">
            <div class="col-md-3">
                <div class="card card-statistic-1 mb-3">
                    <div class="card-icon">
                        <i class="fas fa-dungeon"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{ __('Visa Request') }}</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalVisaRequest }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-statistic-1 mb-3">
                    <div class="card-icon">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{__('Visa Processing')}}</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalVisaProcessing }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-statistic-1 mb-3">
                    <div class="card-icon">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{__('Visa Completed')}}</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalVisaCompleted }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-statistic-1 mb-3">
                    <div class="card-icon">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{__('Visa Shipped')}}</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalVisaShipped }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="custom-xxxl-3 custom-xxl-4 col-md-6 col-sm-6 col-12 mb-4">
                <div class="card-stat bg-white">
                    <div class="icon">
                        <i class="fas fa-dungeon"></i>
                    </div>
                    <div class="content">
                        <p class="caption">{{ __('Total Gateway') }}</p>
                        <h4 class="card-stat-amount">
                            {{ $totalGateways }}</h4>
                    </div>
                </div>
            </div>


            <div class="custom-xxxl-3 custom-xxl-4 col-md-6 col-sm-6 col-12 mb-4">
                <div class="card-stat bg-white">
                    <div class="icon">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <div class="content">
                        <p class="caption">{{ __('Active Gateways') }}</p>
                        <h4 class="card-stat-amount">
                            {{ $activeGateway }}</h4>
                    </div>
                </div>
            </div>

            <div class="custom-xxxl-3 custom-xxl-4 col-md-6 col-sm-6 col-12 mb-4">
                <div class="card-stat bg-white">
                    <div class="icon">
                        <i class="fas fa-money-bill-wave-alt"></i>
                    </div>
                    <div class="content">
                        <p class="caption">{{ __('Total Payment') }}</p>
                        <h4 class="card-stat-amount">
                            {{ number_format($totalPayments, 2) . ' ' . $general->site_currency }}</h4>
                    </div>
                </div>
            </div>
            <div class="custom-xxxl-3 custom-xxl-4 col-md-6 col-sm-6 col-12 mb-4">
                <div class="card-stat bg-white">
                    <div class="icon">
                        <i class="fas fa-spinner"></i>
                    </div>
                    <div class="content">
                        <p class="caption">{{ __('Total Pending Payment') }}</p>
                        <h4 class="card-stat-amount">
                            {{ number_format($totalPendingPayments, 2) . ' ' . $general->site_currency }}</h4>
                    </div>
                </div>
            </div>



            <div class="custom-xxxl-3 custom-xxl-4 col-md-6 col-sm-6 col-12 mb-4">
                <div class="card-stat bg-white">
                    <div class="icon">
                        <i class="far fa-user"></i>
                    </div>
                    <div class="content">
                        <p class="caption">{{ __('Total User') }}</p>
                        <h4 class="card-stat-amount">{{ $totalUser }}</h4>
                    </div>
                </div>
            </div>

            <div class="custom-xxxl-3 custom-xxl-4 col-md-6 col-sm-6 col-12 mb-4">
                <div class="card-stat bg-white">
                    <div class="icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="content">
                        <p class="caption">{{ __('Total Active User') }}</p>
                        <h4 class="card-stat-amount">{{ $activeUser }}</h4>
                    </div>
                </div>
            </div>
            <div class="custom-xxxl-3 custom-xxl-4 col-md-6 col-sm-6 col-12 mb-4">
                <div class="card-stat bg-white">
                    <div class="icon">
                        <i class="fas fa-user-times"></i>
                    </div>
                    <div class="content">
                        <p class="caption">{{ __('Total deactivated User') }}</p>
                        <h4 class="card-stat-amount">{{ $deActiveUser }}</h4>
                    </div>
                </div>
            </div>
            <div class="custom-xxxl-3 custom-xxl-4 col-md-6 col-sm-6 col-12 mb-4">
                <div class="card-stat bg-white">
                    <div class="icon">
                        <i class="fas fa-user-times"></i>
                    </div>
                    <div class="content">
                        <p class="caption">{{ __('Total Ticket') }}</p>
                        <h4 class="card-stat-amount">{{ $totalTicket }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-12 col-lg-12">
                <div class="card invest-report-card">
                    <div class="card-header">
                        <h4>{{ __('Payment Report') }}</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="myChart2"></canvas>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12  col-lg-12 col-12 all-users-table">
                <div class="card">
                    <div class="card-header border-0">
                        <h5>{{ __('All Users') }}</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="example" class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Sl') }}</th>
                                        <th>{{ __('Full Name') }}</th>
                                        <th>{{ __('Phone') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Country') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $key => $user)
                                    <tr>
                                        <td>{{ $key + $users->firstItem() }}</td>
                                        <td>{{ $user->fullname }}</td>

                                        <td>{{ $user->phone }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->address->country ?? '' }}</td>
                                        <td>
                                            @if ($user->status)
                                            <span class='badge badge-success'>{{ __('Active') }}</span>
                                            @else
                                            <span class='badge badge-danger'>{{ __('Inactive') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.user.details', $user) }}"
                                                class="btn btn-md btn-primary"><i class="fa fa-pen"></i></a>
                                        </td>
                                    </tr>
                                    @empty
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if ($users->hasPages())
                    <div class="card-footer">
                        {{ $users->links('backend.partial.paginate') }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('script')
<script src="{{ asset('asset/admin/js/chart.min.js') }}"></script>

<script>
    $(function() {
            'use strict'

            var ctx2 = document.getElementById('myChart2').getContext('2d');

            var myChart2 = new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: @json($months),
                    datasets: [{
                        label: 'Total Payments',
                        data: @json($totalAmount),
                        backgroundColor: [
                            '#625BF6',
                        ],
                        borderColor: [
                            '#625BF6',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    barThickness: 40
                }
            });
            
        });
</script>
@endpush