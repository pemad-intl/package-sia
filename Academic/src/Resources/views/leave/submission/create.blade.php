@extends('academic::layouts.default')

@section('title', 'Izin | ')

@include('components.tourguide', [
    'steps' => array_filter([
        [
            'selector' => '.tg-steps-leave-category',
            'title' => 'Jenis izin',
            'content' => 'Pilih jenis izin yang sesuai dengan kebutuhan kamu.',
        ],
        [
            'selector' => '.tg-steps-leave-date',
            'title' => 'Tanggal izin',
            'content' => 'Kolom ini diisi tanggal izin yang udah kamu rencanain.',
        ],
        [
            'selector' => '.tg-steps-leave-description',
            'title' => 'Keperluan izin',
            'content' => 'Bisa diisi keperluan, catatan, alasan, atau deskripsi penting lainnya.',
        ],
        [
            'selector' => '.tg-steps-leave-attachment',
            'title' => 'Lampiran berkas',
            'content' => 'Kalau ada lampiran bisa diunggah di sini, misalnya surat keterangan dokter atau lainnya.',
        ],
    ]),
])

@section('content')
     <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card border-0">
                    <div class="card-body">
                        <i class="mdi mdi-calendar-multiselect"></i> Data pengajuan izin
                    </div>
                @error('dates.*')
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ $message }} <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @enderror
                @if (count($errors))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div>Maaf, terjadi kegagalan, silakan periksa kembali isian Kamu</div>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- {{ dd() }} --}}
                <div class="card-body border-top border-light">
                    <div class="row justify-content-center">
                        <div class="col-xl-11 col-xxl-9">
                            <form class="form-confirm form-block" action="{{ route('academic::leave.submission.store') }}" method="post" enctype="multipart/form-data"> @csrf
                                 <div class="row mb-3">
                                    <label class="col-md-4 col-lg-3 col-form-label required">Siswa/Siswi</label>
                                    <div class="col-md-8">
                                        <div class="tg-steps-leave-description">
                                            <input class="form-control" type="text" disabled value="{{auth()->user()->name}}" />
                                            <input type="hidden" name="student_id" value="{{auth()->user()->student->user_id}}" />                                      
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-md-4 col-lg-3 col-form-label required">Kategori izin</label>
                                    <div class="col-md-8">
                                        <div class="tg-steps-leave-category">
                                            <div class="card @error('ctg_id') border-danger mb-1 @enderror">
                                                <div class="overflow-auto rounded" style="max-height: 300px;">
                                                    @forelse($categories as $category)
                                                        @if ($category->children->count())
                                                            <div class="card-header border-bottom-0 text-muted small text-uppercase" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $category->id }}" style="cursor: pointer;">{{ $category->name }} <i class="mdi mdi-chevron-down float-end"></i></div>
                                                            <div class="list-group list-group-flush show collapse" id="collapse-{{ $category->id }}">
                                                                @foreach ($category->children as $child)
                                                                    <label class="list-group-item d-flex align-items-center">
                                                                        <input class="form-check-input me-3" type="radio" name="ctg_id" data-meta="{{ json_encode($child->meta) }}" value="{{ $child->id }}" data-quota="{{ $child->meta?->quota ?: -1 }}">
                                                                        <div>
                                                                            <div class="fw-bold mb-0">{{ $child->name }}</div>
                                                                            <div class="small text-muted">Kuota {{ $child->meta?->quota ?: '∞' }} hari</div>
                                                                        </div>
                                                                    </label>
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <label class="card-body border-secondary d-flex align-items-center @if (!$loop->last) border-bottom @endif py-2">
                                                                <input class="form-check-input me-3" type="radio" name="ctg_id" data-meta="{{ json_encode($category->meta) }}" value="{{ $category->id }}" data-quota="{{ $category->meta?->quota ?: -1 }}" required>
                                                                <div>
                                                                    <div class="fw-bold mb-0">{{ $category->name }}</div>
                                                                    <div class="small text-muted">Kuota {{ $category->meta?->quota ?: '∞' }} hari</div>
                                                                </div>
                                                            </label>
                                                        @endif
                                                    @empty
                                                        <div class="card-body text-muted">Tidak ada kategori izin</div>
                                                    @endforelse
                                                </div>
                                            </div>
                                            @error('ctg_id')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <label class="col-md-4 col-lg-3 col-form-label required">Pilih tanggal izin</label>
                                    <div class="col-md-8">
                                        <div class="tg-steps-leave-date">
                                            <div class="inputs-meta-fields" id="inputs-options">
                                                <table class="table-borderless mb-0 table">
                                                    <tbody id="fields-options-tbody">
                                                        <tr id="fields-options-template">
                                                            <td class="ps-0 pt-0">
                                                                <input type="date" class="form-control @error('dates') is-invalid @enderror" name="dates[]" min="{{ date('Y-m-d') }}" required>
                                                            </td>
                                                            <td class="pe-0 pt-0 text-end" width="50"><button class="btn btn-light btn-delete text-danger d-none" type="button" onclick="removeRow(event)"><i class="mdi mdi-trash-can-outline"></i></button></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                @error('dates')
                                                    <div class="small text-danger mb-1">{{ $message }}</div>
                                                @enderror
                                                <button id="fields-options-add" type="button" class="btn btn-light text-danger disabled"><i class="mdi mdi-plus-circle-outline"></i> Tambah tanggal</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row d-none mb-3" id="hide_if_date_only">
                                    <label class="col-md-4 col-lg-3 col-form-label required">Pukul</label>
                                    <div class="col-xl-5 col-md-6">
                                        <div class="input-group">
                                            <input type="time" class="form-control @error('time_start') is-invalid @enderror" name="time_start">
                                            <div class="input-group-text hide_if_start_only">s.d.</div>
                                            <input type="time" class="form-control @error('time_end') is-invalid @enderror hide_if_start_only" name="time_end">
                                        </div>
                                        @error('time_end')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-4 col-lg-3 col-form-label">Deskripsi</label>
                                    <div class="col-md-8">
                                        <div class="tg-steps-leave-description">
                                            <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="2" placeholder="Silakan tulis keterangan/alasan/catatan terkait keperluan izin kamu ...">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-4 col-lg-3 col-form-label">Lampiran</label>
                                    <div class="col-md-8">
                                        <div class="tg-steps-leave-attachment">
                                            <input class="form-control @error('attachment') is-invalid @enderror" name="attachment" type="file" id="upload-input" accept="image/*,application/pdf">
                                            <small class="text-muted">Berkas berupa .jpg, .png atau .pdf maksimal berukuran 2mb</small>
                                            @error('attachment')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3 pt-3">
                                    <div class="col-lg-8 offset-lg-4 offset-xl-3">
                                        <button class="btn btn-soft-danger"><i class="mdi mdi-exit-to-app"></i> Ajukan</button>
                                        <a class="btn btn-ghost-light text-dark" href="{{ request('next', route('academic::leave.submission.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const tbody = document.querySelector('#fields-options-tbody');
        let quota = 0;
        let meta = {}

        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('fields-options-add').addEventListener('click', addRow);
            [].slice.call(document.querySelectorAll('[name="ctg_id"]')).map((e) => {
                e.addEventListener('click', renderFields);
            });
        });

        const renderFields = (e) => {
            if (e.target.dataset.meta) {
                meta = JSON.parse(e.target.dataset.meta);
                quota = JSON.parse(e.target.dataset.quota);
                quota = quota < 0 ? 365 : quota;
                let time_input = meta && meta.time_input

                if (time_input) {
                    Array.from(document.querySelectorAll('.hide_if_start_only')).map((el) => {
                        el.classList.toggle('d-none', meta.time_input == 'start_only');
                    })
                }

                document.querySelector('#hide_if_date_only').classList.toggle('d-none', !(meta && meta.hasOwnProperty('time_input')));

                document.querySelector('[name="time_start"]').required = (time_input ? 'required' : '');
                document.querySelector('[name="time_end"]').required = (time_input == 'start_to_end' ? 'required' : '');

                Array.from(tbody.children).map((el, i) => {
                    if (i > 0) el.remove();
                })

                Array.from(document.querySelectorAll('.inputs-meta-fields')).map((el) => el.classList.add('d-none'));
                document.querySelector(`#inputs-options`).classList.remove('d-none');

                Array.from(document.querySelectorAll('[name="dates[]"]')).map((el) => el.value = '');

                toggleAddButtonBasedQuota();
            }
        }

        const toggleAddButtonBasedQuota = () => {
            document.getElementById('fields-options-add').classList.toggle('disabled', !(tbody.children.length < quota))
            document.getElementById('fields-options-add').classList.toggle('text-muted', !(tbody.children.length < quota))
        }

        const addRow = () => {
            let tr = document.querySelector('#fields-options-template').innerHTML;
            if (tbody.children.length < quota) {
                tbody.insertAdjacentHTML('beforeend', tr);
                Array.from(tbody.children).forEach((el, i) => {
                    if (i > 0) {
                        el.querySelector('.btn-delete').classList.remove('d-none');
                    }
                });
            }
            toggleAddButtonBasedQuota();
        }

        const removeRow = (e) => {
            e.target.parentNode.closest('tr').remove();
            toggleAddButtonBasedQuota();
        }
    </script>
@endpush
