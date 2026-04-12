@extends('layout.backend.app')

<style>
    /* WRAPPER BIAR CENTER */
    .form-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
    }

    /* TITLE */
    .page-title {
        text-align: center;
        margin-bottom: 20px;
    }

    /* FORM CARD */
    .form-card {
        background: #fff;
        border-radius: 14px;
        padding: 28px 30px 32px 30px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        max-width: 780px;
        width: 100%;
    }

    .form-card-title {
        font-size: 14px;
        font-weight: 600;
        color: #1e1b3a;
        margin-bottom: 24px;
    }

    /* FORM FIELDS */
    .form-group {
        margin-bottom: 18px;
    }

    .form-label {
        display: block;
        font-size: 12.5px;
        font-weight: 500;
        color: #444;
        margin-bottom: 6px;
    }

    .form-input {
        width: 100%;
        border: none;
        border-bottom: 1.5px solid #ddd;
        padding: 8px 2px;
        font-family: 'Poppins', sans-serif;
        font-size: 12.5px;
        color: #333;
        outline: none;
        background: transparent;
        transition: border-color 0.2s;
    }

    .form-input::placeholder {
        color: #bbb;
    }

    .form-input:focus {
        border-bottom-color: #7c3aff;
    }

    /* SELECT */
    .select-wrap {
        position: relative;
        width: 100%;
    }

    .form-select {
        width: 100%;
        border: none;
        border-bottom: 1.5px solid #ddd;
        padding: 8px 30px 8px 2px;
        font-family: 'Poppins', sans-serif;
        font-size: 12.5px;
        color: #bbb;
        outline: none;
        background: transparent;
        appearance: none;
        cursor: pointer;
        transition: border-color 0.2s;
    }

    .form-select:focus {
        border-bottom-color: #7c3aff;
        color: #333;
    }

    .select-arrow {
        position: absolute;
        right: 4px;
        top: 50%;
        transform: translateY(-50%);
        color: #aaa;
        font-size: 12px;
        pointer-events: none;
    }

    /* UPLOAD */
    .upload-wrap {
        display: flex;
        align-items: center;
        border-bottom: 1.5px solid #ddd;
        padding: 6px 2px;
        gap: 10px;
    }

    .upload-placeholder {
        flex: 1;
        font-size: 12.5px;
        color: #bbb;
    }

    .btn-upload {
        background: #4a90e2;
        color: #fff;
        border: none;
        border-radius: 7px;
        padding: 6px 18px;
        font-family: 'Poppins', sans-serif;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
        flex-shrink: 0;
    }

    .btn-upload:hover {
        background: #3a6bc7;
    }

    /* KATEGORI */
    .kategori-area {
        width: 100%;
        border-bottom: 1.5px solid #ddd;
        min-height: 36px;
        padding: 6px 2px;
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        align-items: center;
        cursor: text;
    }

    .kategori-chip {
        background: #ede8ff;
        color: #7c3aff;
        border-radius: 20px;
        padding: 3px 12px;
        font-size: 11.5px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .kategori-chip .remove {
        cursor: pointer;
        font-size: 10px;
        color: #b06aff;
    }

    .kategori-input {
        border: none;
        outline: none;
        font-family: 'Poppins', sans-serif;
        font-size: 12.5px;
        color: #333;
        background: transparent;
        min-width: 80px;
    }

    .kategori-input::placeholder {
        color: #bbb;
    }

    /* BUTTONS */
    .form-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 30px;
    }

    .btn-submit {
        background: #7c3aff;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 10px 28px;
        font-family: 'Poppins', sans-serif;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }

    .btn-submit:hover {
        background: #6228e0;
    }

    .btn-cancel {
        background: transparent;
        color: #555;
        border: none;
        padding: 10px 18px;
        font-family: 'Poppins', sans-serif;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: color 0.2s;
    }

    .btn-cancel:hover {
        color: #f5364f;
    }
</style>
@section('content')
<div class="form-wrapper">

  <div class="page-title">Halaman Edit User</div>

  <div class="form-card">
    <div class="form-card-title">Form Edit User</div>

    <form action="{{ route('user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <!-- Nama -->
      <div class="form-group">
        <label class="form-label">Nama</label>
        <input type="text" name="name" class="form-input"
          value="{{ old('name', $user->name) }}">
        @error('name')
          <small style="color:#f5364f;font-size:11px;">{{ $message }}</small>
        @enderror
      </div>

      <!-- Email -->
      <div class="form-group">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-input"
          value="{{ old('email', $user->email) }}">
        @error('email')
          <small style="color:#f5364f;font-size:11px;">{{ $message }}</small>
        @enderror
      </div>

      <!-- Password (optional) -->
      <div class="form-group">
        <label class="form-label">Password (Opsional)</label>
        <input type="password" name="password" class="form-input"
          placeholder="Kosongkan jika tidak diubah">
        @error('password')
          <small style="color:#f5364f;font-size:11px;">{{ $message }}</small>
        @enderror
      </div>

      <!-- Role -->
      <div class="form-group">
        <label class="form-label">Role</label>
        <div class="select-wrap">
          <select name="role" class="form-select">
            <option value="" disabled hidden>Pilih Role</option>

            <option value="admin"
              {{ old('role', $user->role)=='admin' ? 'selected' : '' }}>Admin</option>

            <option value="petugas"
              {{ old('role', $user->role)=='petugas' ? 'selected' : '' }}>Petugas</option>

            <option value="anggota"
              {{ old('role', $user->role)=='anggota' ? 'selected' : '' }}>Anggota</option>
          </select>
          <i class="fas fa-chevron-down select-arrow"></i>
        </div>
        @error('role')
          <small style="color:#f5364f;font-size:11px;">{{ $message }}</small>
        @enderror
      </div>

      <!-- Foto -->
      <div class="form-group">
        <label class="form-label">Foto</label>

        {{-- Preview foto lama --}}
        @if($user->img)
          <div style="margin-bottom:10px;">
            <img src="{{ asset('storage/'.$user->img) }}" width="80" style="border-radius:50%;">
          </div>
        @endif

        <div class="upload-wrap">
          <span class="upload-placeholder" id="upload-label">Ganti Image (opsional)</span>
          <button type="button" class="btn-upload"
            onclick="document.getElementById('img-input').click()">Upload</button>

          <input type="file" id="img-input" name="img" accept="image/*" style="display:none"
            onchange="document.getElementById('upload-label').textContent = this.files[0]?.name || 'Upload Image'">
        </div>

        @error('img')
          <small style="color:#f5364f;font-size:11px;">{{ $message }}</small>
        @enderror
      </div>

      <!-- BUTTON -->
      <div class="form-actions">
        <button type="submit" class="btn-submit">Update</button>
        <a href="{{ route('user.index') }}" class="btn-cancel">Cancel</a>
      </div>

    </form>
  </div>

</div>
@endsection