@extends('layout.app')

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
  box-shadow: 0 2px 12px rgba(0,0,0,0.06);
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
.form-group { margin-bottom: 18px; }

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

.form-input::placeholder { color: #bbb; }
.form-input:focus { border-bottom-color: #7c3aff; }

/* SELECT */
.select-wrap { position: relative; width: 100%; }

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

.form-select:focus { border-bottom-color: #7c3aff; color: #333; }

.select-arrow {
  position: absolute;
  right: 4px; top: 50%;
  transform: translateY(-50%);
  color: #aaa; font-size: 12px;
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

.btn-upload:hover { background: #3a6bc7; }

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
  display: flex; align-items: center; gap: 5px;
}

.kategori-chip .remove {
  cursor: pointer; font-size: 10px; color: #b06aff;
}

.kategori-input {
  border: none; outline: none;
  font-family: 'Poppins', sans-serif;
  font-size: 12.5px; color: #333;
  background: transparent;
  min-width: 80px;
}

.kategori-input::placeholder { color: #bbb; }

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

.btn-submit:hover { background: #6228e0; }

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

.btn-cancel:hover { color: #f5364f; }
</style>

@section('conten')
<div class="form-wrapper">

  <div class="page-title">Halaman Tambah Buku</div>

  <div class="form-card">
    <div class="form-card-title">Form Tambah Buku</div>

    <form action="" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="form-group">
        <label class="form-label">Judul Buku</label>
        <input type="text" name="judul" class="form-input" placeholder="Judul Buku" value="{{ old('judul') }}">
        @error('judul')
          <small style="color:#f5364f;font-size:11px;">{{ $message }}</small>
        @enderror
      </div>

      <div class="form-group">
        <label class="form-label">Penulis</label>
        <input type="text" name="penulis" class="form-input" placeholder="Penulis" value="{{ old('penulis') }}">
        @error('penulis')
          <small style="color:#f5364f;font-size:11px;">{{ $message }}</small>
        @enderror
      </div>

      <div class="form-group">
        <label class="form-label">Tahun Terbit</label>
        <input type="text" name="tahun_terbit" class="form-input" placeholder="Tahun Terbit" value="{{ old('tahun_terbit') }}">
        @error('tahun_terbit')
          <small style="color:#f5364f;font-size:11px;">{{ $message }}</small>
        @enderror
      </div>

      <div class="form-group">
        <label class="form-label">Stok</label>
        <div class="select-wrap">
          <select name="stok" class="form-select">
            <option value="" disabled selected hidden></option>
            @foreach([1,2,3,4,5,10] as $s)
              <option value="{{ $s }}" {{ old('stok') == $s ? 'selected' : '' }}>{{ $s }}</option>
            @endforeach
          </select>
          <i class="fas fa-chevron-down select-arrow"></i>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Cover</label>
        <div class="upload-wrap">
          <span class="upload-placeholder" id="upload-label">Upload Image</span>
          <button type="button" class="btn-upload" onclick="document.getElementById('cover-input').click()">Upload</button>
          <input type="file" id="cover-input" name="cover" accept="image/*" style="display:none"
                 onchange="document.getElementById('upload-label').textContent = this.files[0]?.name || 'Upload Image'">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Kategori</label>
        <div class="kategori-area" id="kategori-area" onclick="document.getElementById('kategori-input').focus()">
          <input type="text" class="kategori-input" id="kategori-input"
                 placeholder="Ketik kategori lalu Enter..."
                 onkeydown="addKategori(event)">
        </div>
        <div id="kategori-hidden"></div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn-submit">Submit</button>
        <a href="/buku" class="btn-cancel">Cancel</a>
      </div>

    </form>
  </div>

</div>
@endsection