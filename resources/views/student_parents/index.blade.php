@extends('layouts.app')
@section('title', 'Manajemen Orang Tua')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="card w-100">
            <div class="card-body">
                <div class="d-md-flex align-items-center justify-content-between">
                    <h4 class="card-title">Manajemen Orang Tua</h4>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createParentModal">Tambah
                        Data</button>
                </div>

                <div class="table-responsive mt-4">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Nama Ayah</th>
                                <th>Nama Ibu</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($parents as $parent)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $parent->student->user->name }}</td>
                                <td>{{ $parent->father_name }}</td>
                                <td>{{ $parent->mother_name }}</td>
                                <td>
                                    <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#showParentModal{{ $parent->id }}">Detail</button>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#editParentModal{{ $parent->id }}">Edit</button>
                                    <form method="POST" action="{{ route('student-parents.destroy', $parent->id) }}"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $parents->links('pagination::bootstrap-4') }}
                </div>
            </div>
            @foreach($parents as $parent)
            <!-- Modal Detail -->
            <div class="modal fade" id="showParentModal{{ $parent->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Detail Orang Tua</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3"><label>Nama Ayah</label><input type="text" class="form-control"
                                    value="{{ $parent->father_name }}" disabled></div>
                            <div class="mb-3"><label>Telepon Ayah</label><input type="text" class="form-control"
                                    value="{{ $parent->father_phone }}" disabled></div>
                            <div class="mb-3"><label>Pekerjaan Ayah</label><input type="text" class="form-control"
                                    value="{{ $parent->father_job }}" disabled></div>
                            <div class="mb-3"><label>Nama Ibu</label><input type="text" class="form-control"
                                    value="{{ $parent->mother_name }}" disabled></div>
                            <div class="mb-3"><label>Telepon Ibu</label><input type="text" class="form-control"
                                    value="{{ $parent->mother_phone }}" disabled></div>
                            <div class="mb-3"><label>Pekerjaan Ibu</label><input type="text" class="form-control"
                                    value="{{ $parent->mother_job }}" disabled></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Edit -->
            <div class="modal fade" id="editParentModal{{ $parent->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <form class="modal-content" method="POST"
                        action="{{ route('student-parents.update', $parent->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Data Orang Tua</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3"><label>Nama Ayah</label><input type="text" name="father_name"
                                    class="form-control" value="{{ $parent->father_name }}"></div>
                            <div class="mb-3"><label>No HP Ayah</label><input type="text" name="father_phone"
                                    class="form-control" value="{{ $parent->father_phone }}"></div>
                            <div class="mb-3"><label>Pekerjaan Ayah</label><input type="text" name="father_job"
                                    class="form-control" value="{{ $parent->father_job }}"></div>
                            <div class="mb-3"><label>Nama Ibu</label><input type="text" name="mother_name"
                                    class="form-control" value="{{ $parent->mother_name }}"></div>
                            <div class="mb-3"><label>No HP Ibu</label><input type="text" name="mother_phone"
                                    class="form-control" value="{{ $parent->mother_phone }}"></div>
                            <div class="mb-3"><label>Pekerjaan Ibu</label><input type="text" name="mother_job"
                                    class="form-control" value="{{ $parent->mother_job }}"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="createParentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" method="POST" action="{{ route('student-parents.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Orang Tua</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Nama Siswa</label>
                    <select name="student_id" class="form-control" required>
                        @foreach(App\Models\Student::with('user')->get() as $student)
                        <option value="{{ $student->id }}">{{ $student->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label>Nama Ayah</label>
                    <input type="text" name="father_name" class="form-control">
                </div>
                <div class="mb-3">
                    <label>No HP Ayah</label>
                    <input type="text" name="father_phone" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Pekerjaan Ayah</label>
                    <input type="text" name="father_job" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Nama Ibu</label>
                    <input type="text" name="mother_name" class="form-control">
                </div>
                <div class="mb-3">
                    <label>No HP Ibu</label>
                    <input type="text" name="mother_phone" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Pekerjaan Ibu</label>
                    <input type="text" name="mother_job" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection