@extends('teacher::layouts.default')

@section('title', $meet->subject->name . ' ' . $meet->classroom->full_name . ' - ')

@section('content')
    <div class="row">
        <div class="col-md-7 col-lg-8">
            <div class="card bg-{{ $meet->props->color ?? 'white border' }} mb-4 border-0">
                <div class="card-body">
                    <i class="mdi mdi-account-badge-horizontal-outline position-absolute" style="top: 10pt; right: 40pt; font-size: 40pt;"></i>
                    <h2><strong>{{ $meet->subject->name }}</strong></h3>
                        <p class="mb-0">Rombel {{ $meet->classroom->full_name }}</p>
                </div>
            </div>
            <h2>
                <a class="text-decoration-none small text-{{ $meet->props->color ?? 'primary' }}" href="{{ request('next', route('teacher::meet', ['meet' => $meet->id])) }}"><i class="mdi mdi-arrow-left-circle-outline"></i></a>
                Atur pertemuan
            </h2>
            <hr>
            <div class="card">
                <div class="card-header">
                    <i class="mdi mdi-file-document-edit-outline"></i> Rencana pertemuan kolektif
                </div>
                <form class="form-block form-confirm" action="{{ route('teacher::meet.plans', ['meet' => $meet->id]) }}" method="POST"> @csrf @method('PUT')
                    <div class="table-responsive">
                        <table class="table-striped table-hover mb-0 table">
                            <tbody>
                                @forelse($plans as $plan)
                                    <tr>
                                        <td class="text-center align-middle">{{ $plan->az }}</td>
                                        <td nowrap class="row no-gutters">
                                            <div class="col-lg mb-lg-0 mr-lg-3 mb-2">
                                                <div class="input-group flex-nowrap">
                                                    <input class="form-control @error('plans.' . $plan->id . '.plan_at') is-invalid @enderror" type="date" name="plans[{{ $plan->id }}][plan_at]" value="{{ old('plan_at', $plan->plan_at ? \Carbon\Carbon::parse($plan->plan_at)->format('Y-m-d') : null) }}" style="min-width: 180px;">
                                                    <input class="form-control @error('plans.' . $plan->id . '.hour') is-invalid @enderror" type="number" name="plans[{{ $plan->id }}][hour]" value="{{ old('hour', $plan->hour) }}" style="width: 60px;">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">jam</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg">
                                                <div class="input-group flex-nowrap">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text" style="width: 42px;">
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input test-checkbox" data-target="#test-checkbox-{{ $plan->id }}" name="plans[{{ $plan->id }}][test]" id="plans.{{ $plan->id }}.test" value="1" @if ($plan->test) checked @endif>
                                                                <label class="custom-control-label" for="plans.{{ $plan->id }}.test"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <select class="form-control @error('plans.' . $plan->id . '.comp_id') is-invalid @enderror" id="test-checkbox-{{ $plan->id }}" name="plans[{{ $plan->id }}][comp_id]" value="{{ old('comp_id', $plan->comp_id) }}" @if ($plan->test) disabled @endif>
                                                        <option value="">-- Pilih kompetensi --</option>
                                                        @foreach ($meet->subject->competences as $competence)
                                                            <option value="{{ $competence->id }}" @if ($plan->comp_id == $competence->id) selected @endif>{{ $competence->full_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2">Tidak ada rencana pertemuan</td>
                                    </tr>
                                @endforelse
                                <tr>
                                    <td></td>
                                    <td>
                                        <button class="btn btn-{{ $meet->props->color ?? 'primary' }}"><i class="mdi mdi-check-circle-outline"></i> Simpan</button>
                                        <a class="btn btn-secondary" href="{{ request('next', route('teacher::meet', ['meet' => $meet->id])) }}"><i class="mdi mdi-close-circle-outline"></i> Batal</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-5 col-lg-4">
            @include('teacher::includes.classroom-info', ['classroom' => $meet->classroom])
            @include('teacher::includes.subject-info', ['subject' => $meet->subject])
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(() => {
            $('.test-checkbox').on('change', (e) => {
                let select = $(e.target).data('target');
                $(e.target).is(':checked') ?
                    $(select).attr('disabled', 'disabled').val(null) :
                    $(select).removeAttr('disabled');
            });
        })
    </script>
@endpush

@push('style')
    <style scoped>
        @media (min-width: 576px) {
            .card-columns {
                -webkit-column-count: 2;
                -moz-column-count: 2;
                column-count: 2;
            }
        }

        @media (min-width: 992px) {
            .card-columns {
                -webkit-column-count: 3;
                -moz-column-count: 3;
                column-count: 3;
            }
        }
    </style>
@endpush
