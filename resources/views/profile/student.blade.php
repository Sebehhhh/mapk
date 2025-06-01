@extends('layouts.app')
@section('title', 'Profil Siswa')
@section('content')
<div class="row">
  <div class="col-lg-4">
    <div class="card text-center">
      <div class="card-body">
        <img src="{{ asset('assets/images/profile/user-1.jpg') }}" class="rounded-circle mb-3" width="120" height="120" alt="Foto Profil">
        <h5>{{ $student->user->name }}</h5>
        <p class="text-muted">{{ $student->nisn }}</p>
        <a href="#" class="btn btn-outline-primary btn-sm">Edit Profile</a>
      </div>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="row">
      <div class="col-md-12">
        <div class="card mb-3">
          <div class="card-header fw-bold">Profil Siswa</div>
          <div class="card-body row">
            <div class="col-md-6"><strong>Nama Lengkap:</strong><br>{{ $student->user->name }}</div>
            <div class="col-md-6"><strong>Kelas:</strong><br>{{ $student->class }}</div>
            <div class="col-md-6 mt-3"><strong>NISN:</strong><br>{{ $student->nisn }}</div>
            <div class="col-md-6 mt-3"><strong>Angkatan:</strong><br>{{ $student->graduation_year }}</div>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card mb-3">
          <div class="card-header fw-bold">Data Siswa</div>
          <div class="card-body">
            <p><strong>Email:</strong> {{ $student->user->email }}</p>
            <p><strong>Telepon:</strong> {{ $student->phone }}</p>
            <p><strong>Tempat Lahir:</strong> {{ $student->place_of_birth }}</p>
            <p><strong>Tanggal Lahir:</strong> {{ $student->birth_date }}</p>
            <p><strong>Jenis Kelamin:</strong> {{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
            <p><strong>Agama:</strong> {{ $student->religion }}</p>
            <p><strong>Provinsi:</strong> {{ $student->province }}</p>
            <p><strong>Kabupaten:</strong> {{ $student->district }}</p>
            <p><strong>Kecamatan:</strong> {{ $student->sub_district }}</p>
            <p><strong>Kelurahan:</strong> {{ $student->village }}</p>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card mb-3">
          <div class="card-header fw-bold">Data Orang Tua</div>
          <div class="card-body">
            <p><strong>Nama Ayah:</strong> {{ $student->parent->father_name ?? '-' }}</p>
            <p><strong>Telepon Ayah:</strong> {{ $student->parent->father_phone ?? '-' }}</p>
            <p><strong>Pekerjaan Ayah:</strong> {{ $student->parent->father_job ?? '-' }}</p>
            <p><strong>Nama Ibu:</strong> {{ $student->parent->mother_name ?? '-' }}</p>
            <p><strong>Telepon Ibu:</strong> {{ $student->parent->mother_phone ?? '-' }}</p>
            <p><strong>Pekerjaan Ibu:</strong> {{ $student->parent->mother_job ?? '-' }}</p>
          </div>
        </div>

        <div class="card mb-3">
          <div class="card-header fw-bold">Asal Sekolah</div>
          <div class="card-body">
            <p><strong>Nama Sekolah:</strong> {{ $student->origin_school_name }}</p>
            <p><strong>Alamat Sekolah:</strong> {{ $student->origin_school_address }}</p>
            <p><strong>Tahun Lulus:</strong> {{ $student->graduation_year }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection