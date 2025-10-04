@extends('frontend.layout.master2')
@section('content2')

<div class="card">
    <div class="card-header d-flex flex-wrap gap-3 justify-content-between align-items-center gap-2">
        <h5 class="mb-0">{{__("My Applications")}}</h5>
        <form action="">
            <div class="input-group">
                <input type="text" class="form-control form-control-sm" name="order_number"
                    value="{{request()->order_number ?? ''}}" placeholder="{{__('Track Number')}}">
                <button class="btn btn-sm btn-outline-secondary" type="submit"
                    id="button-addon2">{{__("Search")}}</button>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table responsive-table head-light-bg nir-table">
                <thead>
                    <tr>
                        <th>{{__("Track Number")}}</th>
                        <th>{{__("Plan")}}</th>
                        <th>{{__("Price")}}</th>
                        <th>{{__("Status")}}</th>
                        <th>{{__("Action")}}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                    <tr>
                        <td>
                            <p class="trx-number d-inline-flex align-items-center gap-2 mb-0">
                                <span class="text-sm copy-text">{{$item->order_number}}</span>
                                <button type="button" class="copy-btn"><i class="bi bi-copy"></i></button>
                            </p>
                        </td>
                        <td>{{$item->plan->title}}</td>
                        <td>{{number_format($item->price,2)}}</td>
                        <td>
                            <?= $item->status() ?>
                        </td>
                        <td>
                            <a href="{{route('user.visa.details',$item->order_number)}}"
                                class="btn btn-sm btn-primary">{{__("View")}} <i class="bi bi-chevron-right"></i></a>
                            @if($item->status == 'issues')
                            <a href="{{route('user.visa.resubmit',$item->order_number)}}"
                                class="btn btn-sm btn-info">{{__("Resubmit")}} <i class="bi bi-chevron-right"></i></a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">{{__("No data available")}}</td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>


    @if ($items->hasPages())
    {{ $items->links() }}
    @endif
</div>
@endsection


@push('script')
<script>
    $(function () {
        'use strict';

        $(".copy-btn").on('click', function (e) {
            e.preventDefault();

            let textToCopy = $(this).closest('.trx-number').find('.copy-text').text().trim();

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(textToCopy).then(function () {
                    iziToast.success({
                        message: "Copied: " + textToCopy,
                        position: 'topRight'
                    });
                }).catch(function (err) {
                    fallbackCopy(textToCopy);
                });
            } else {
                fallbackCopy(textToCopy);
            }

            function fallbackCopy(text) {
                let temp = $("<textarea>");
                $("body").append(temp);
                temp.val(text).select();

                try {
                    let success = document.execCommand("copy");
                    if (success) {
                        iziToast.success({
                            message: "Copied: " + text,
                            position: 'topRight'
                        });
                    } else {
                        throw new Error("Copy failed");
                    }
                } catch (err) {
                    iziToast.error({
                        message: "Failed to copy",
                        position: 'topRight'
                    });
                    console.error("Fallback copy failed", err);
                }

                temp.remove();
            }
        });
    });
</script>
@endpush