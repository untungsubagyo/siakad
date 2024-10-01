@extends('layouts.home-layout')

@section('home-content')
    <style>
        .radio-group input {
            margin-right: 5px; /* Atur jarak antara input dan label */
        }

        .radio-group label {
            margin-right: 15px; /* Atur jarak antar kelompok radio */
        }
    </style>
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                {{-- start page title --}}
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18"></h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('course.index') }}">Mata Kuliah</a>
                                    </li>
                                    <li class="breadcrumb-item active">Edit</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- end page title --}}

                <!-- Form to edit a course -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Edit Mata Kuliah</h4>
                                <p class="card-title-desc">
                                    Isilah form untuk mengedit data mata kuliah.
                                </p>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <form action="{{ route('course.update', $course->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            <!-- Prodi field -->
                                            <div class="form-group mt-2">
                                                <label for="prodi_id">Prodi</label>
                                                <select name="prodi_id" id="prodi_id" class="form-control" required>
                                                    <option value="" selected>Pilih Prodi</option>
                                                    @foreach ($prodi as $p)
                                                        <option value="{{ $p->id_prodi }}"
                                                            {{ old('prodi_id', $course->prodi_id) == $p->id_prodi ? 'selected' : '' }}>
                                                            {{ $p->nama_prodi }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Education Level field (Auto-filled) -->
                                            <div class="form-group mt-2">
                                                <label for="education_level_display">Tingkat Pendidikan</label>
                                                <input type="text" id="education_level_display" class="form-control"
                                                    value="{{ old('education_level_display', $course->education_level->nm_jenj_didik ?? '') }}"
                                                    readonly required>
                                            </div>

                                            <!-- Hidden ID Field -->
                                            <input type="hidden" id="education_level_id" name="education_level_id"
                                                value="{{ old('education_level_id', $course->education_level_id) }}" required>

                                            {{-- Course Code --}}
                                            <div class="form-group mt-2">
                                                <label for="code">Kode Matakuliah</label>
                                                <input type="text" name="code" id="code" class="form-control"
                                                    maxlength="10" value="{{ old('code', $course->code) }}" required>
                                            </div>

                                            {{-- Course Name --}}
                                            <div class="form-group mt-2">
                                                <label for="name">Nama Matakuliah</label>
                                                <input type="text" name="name" id="name" class="form-control"
                                                    maxlength="200" value="{{ old('name', $course->name) }}" required>
                                            </div>

                                            {{-- Course Group --}}
                                            <div class="form-group mt-2">
                                                <label for="group_id">Kelompok Matakuliah</label>
                                                <select name="group_id" id="group_id" class="form-control" required>
                                                    <option value="">Pilih...</option>
                                                    @foreach ($group as $gro)
                                                        <option value="{{ $gro->id }}"
                                                            {{ old('group_id', $course->group_id) == $gro->id ? 'selected' : '' }}>
                                                            {{ $gro->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{-- Course Type --}}
                                            <div class="form-group mt-2">
                                                <label for="type_id">Jenis Matakuliah</label>
                                                <select name="type_id" id="type_id" class="form-control" required>
                                                    <option value="">Pilih...</option>
                                                    @foreach ($type as $ty)
                                                        <option value="{{ $ty->id }}"
                                                            {{ old('type_id', $course->type_id) == $ty->id ? 'selected' : '' }}>
                                                            {{ $ty->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{-- SKS Fields --}}
                                            <div class="form-group mt-2">
                                                <label for="sks_mk">SKS Matakuliah</label>
                                                <input type="number" name="sks_mk" id="sks_mk" class="form-control"
                                                    value="{{ old('sks_mk', $course->sks_mk) }}" required>
                                            </div>

                                            <div class="form-group mt-2">
                                                <label for="sks_tm">SKS Tatap Muka</label>
                                                <input type="number" name="sks_tm" id="sks_tm" class="form-control"
                                                    value="{{ old('sks_tm', $course->sks_tm) }}" required>
                                            </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mt-2">
                                            <label for="sks_pr">SKS Praktikum</label>
                                            <input type="number" name="sks_pr" id="sks_pr" class="form-control"
                                                value="{{ old('sks_pr', $course->sks_pr) }}" required>
                                        </div>

                                        <div class="form-group mt-2">
                                            <label for="sks_pl">SKS Praktikum Lapangan</label>
                                            <input type="number" name="sks_pl" id="sks_pl" class="form-control"
                                                value="{{ old('sks_pl', $course->sks_pl) }}" required>
                                        </div>

                                        <div class="form-group mt-2">
                                            <label for="sks_sim">SKS Simulasi</label>
                                            <input type="number" name="sks_sim" id="sks_sim" class="form-control"
                                                value="{{ old('sks_sim', $course->sks_sim) }}" required>
                                        </div>

                                        {{-- Status --}}
                                        <div class="form-group mt-2">
                                            <label for="status">Status</label>
                                            <select name="status" class="form-control" required>
                                                <option value="">Pilih...</option>
                                                <option value="Active" {{ old('status', $course->status) == 'Active' ? 'selected' : '' }}>Active</option>
                                                <option value="Deleted" {{ old('status', $course->status) == 'Deleted' ? 'selected' : '' }}>Deleted</option>
                                                <option value="Non-Active" {{ old('status', $course->status) == 'Non-Active' ? 'selected' : '' }}>Non-Active</option>
                                            </select>
                                        </div>

                                        {{-- Boolean Fields --}}
                                        <div class="form-group mt-2">
                                            <label for="is_sap">Apakah Ada SAP (Satuan Acara Perkuliahan) / RPS?</label>
                                            <div class="radio-group">
                                                <input type="radio" class="form-check-input" name="is_sap"
                                                    value="1" id="is_sap_yes" {{ old('is_sap', $course->is_sap) == 1 ? 'checked' : '' }} required>
                                                <label class="form-check-label" for="is_sap_yes">Yes</label>

                                                <input type="radio" class="form-check-input" name="is_sap"
                                                    value="0" id="is_sap_no" {{ old('is_sap', $course->is_sap) == 0 ? 'checked' : '' }} required>
                                                <label class="form-check-label" for="is_sap_no">No</label>
                                            </div>
                                        </div>

                                        <div class="form-group mt-2">
                                            <label for="is_silabus"> Apakah ada Silabus?</label>
                                            <div class="radio-group">
                                                <input type="radio" class="form-check-input" name="is_silabus"
                                                    value="1" id="is_silabus_yes" {{ old('is_silabus', $course->is_silabus) == 1 ? 'checked' : '' }} required>
                                                <label class="form-check-label" for="is_silabus_yes">Yes</label>

                                                <input type="radio" class="form-check-input" name="is_silabus"
                                                    value="0" id="is_silabus_no" {{ old('is_silabus', $course->is_silabus) == 0 ? 'checked' : '' }} required>
                                                <label class="form-check-label" for="is_silabus_no">No</label>
                                            </div>
                                        </div>

                                        <div class="form-group mt-2">
                                            <label for="is_teaching_material">Apakah ada bahan materi perkuliahan?</label>
                                            <div class="radio-group">
                                                <input type="radio" class="form-check-input"
                                                    name="is_teaching_material" value="1"
                                                    id="is_teaching_material_yes" {{ old('is_teaching_material', $course->is_teaching_material) == 1 ? 'checked' : '' }} required>
                                                <label class="form-check-label" for="is_teaching_material_yes">Yes</label>

                                                <input type="radio" class="form-check-input"
                                                    name="is_teaching_material" value="0"
                                                    id="is_teaching_material_no" {{ old('is_teaching_material', $course->is_teaching_material) == 0 ? 'checked' : '' }} required>
                                                <label class="form-check-label" for="is_teaching_material_no">No</label>
                                            </div>
                                        </div>

                                        <div class="form-group mt-2">
                                            <label for="is_praktikum">Apakah matakuliah praktikum?</label>
                                            <div class="radio-group">
                                                <input type="radio" class="form-check-input" name="is_praktikum"
                                                    value="1" id="is_praktikum_yes" {{ old('is_praktikum', $course->is_praktikum) == 1 ? 'checked' : '' }} required>
                                                <label class="form-check-label" for="is_praktikum_yes">Yes</label>

                                                <input type="radio" class="form-check-input" name="is_praktikum"
                                                    value="0" id="is_praktikum_no" {{ old('is_praktikum', $course->is_praktikum) == 0 ? 'checked' : '' }} required>
                                                <label class="form-check-label" for="is_praktikum_no">No</label>
                                            </div>
                                        </div>

                                        {{-- Effective Dates --}}
                                        <div class="form-group">
                                            <label class="form-label">Rentang Tanggal</label>
                                            <input type="text" class="form-control"
                                                id="datepicker-range-without-d-value" name="course_range"
                                                value="{{ old('course_range', $course_range) }}" />
                                            @error('course_range')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div><br>
                                    </div>
                                </div>

                                {{-- Submit Button --}}
                                <div class="form-group mt-2">
                                    <button type="submit" class="btn btn-primary mt-3">Simpan</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Form -->
            </div>
        </div>
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <script>
                            document.write(new Date().getFullYear());
                        </script> © Minia.
                    </div>
                    <div class="col-sm-6">
                        <div class="text-sm-end d-none d-sm-block">
                            Design & Develop by <a href="#!" class="text-decoration-underline">Themesbrand</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        // Auto-fill education level when prodi is selected
        $('#prodi_id').on('change', function() {
            const prodiId = $(this).val();
            if (prodiId) {
                $.ajax({
                    url: '/get-education-level/' + prodiId,
                    type: 'GET',
                    success: function(data) {
                        if (data && !data.error) {
                            $('#education_level_id').val(data.id_jenj_didik); // Set the hidden input value
                            $('#education_level_display').val(data.nm_jenj_didik); // Display the education level name
                        } else {
                            $('#education_level_id').val('');
                            $('#education_level_display').val('');
                        }
                    },
                    error: function() {
                        $('#education_level_id').val('');
                        $('#education_level_display').val('');
                    }
                });
            } else {
                $('#education_level_id').val('');
                $('#education_level_display').val('');
            }
        });

        // Set initial education level based on the existing prodi selection
        $(document).ready(function() {
            $('#prodi_id').trigger('change');
        });
    </script>
@endsection
