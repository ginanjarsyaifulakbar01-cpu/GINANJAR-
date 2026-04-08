@extends('layout.backend.app')

@section('conten')
    <style>
        /* CSS Modal Custom - Biar rapi di tengah layar */
        .custom-modal-backdrop {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
            background: rgba(0,0,0,0.6); z-index: 9999; 
            display: none; align-items: center; justify-content: center;
        }
        .modal-content-custom {
            background: white; width: 100%; max-width: 500px; 
            border-radius: 12px; overflow: hidden; animation: fadeIn 0.3s;
        }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        
        .modal-header-custom { background: #1a2035; color: white; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; }
        .modal-body-custom { padding: 20px; }
        .modal-footer-custom { padding: 15px 20px; border-top: 1px solid #eee; display: flex; justify-content: flex-end; gap: 10px; }
        
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 5px; font-size: 13px; color: #333; }
        .form-control { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; }
        
        .btn-simpan { background: #1a2035; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: bold; }
        .btn-batal { background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; }
        
        style { display: none !important; }
    </style>

    <div class="content">
        <div class="page-title">Halaman Data Kategori</div>

        <div class="table-card">
            <div class="table-header">
                <div class="table-header-left">Daftar Kategori</div>
                <div class="table-header-right">
                    <div class="search-wrap">
                        <span class="search-label">SEARCH:</span>
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" id="categorySearch" placeholder="Cari kategori...">
                        </div>
                    </div>

                    {{-- Tombol Tambah --}}
                    <button type="button" onclick="openAddModal()" style="background: #3498db; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: bold;">
                        <i class="fas fa-plus"></i> Category
                    </button>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th>Icon</th>
                        <th>Nama Kategori</th>
                        <th>Slug</th>
                        <th style="width: 15%">Action</th>
                    </tr>
                </thead>
                <tbody id="categoryTableBody">
                    @forelse ($categories as $index => $category)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><i class="{{ $category->icon ?? 'fas fa-tag' }}"></i></td>
                            <td><strong>{{ $category->name }}</strong></td>
                            <td><code>{{ $category->slug }}</code></td>
                            <td>
                                {{-- Tombol Edit dengan Data URL Laravel --}}
                                <button type="button" 
                                    onclick="openEditModal(this)" 
                                    data-id="{{ $category->id }}"
                                    data-name="{{ $category->name }}"
                                    data-slug="{{ $category->slug }}"
                                    data-icon="{{ $category->icon }}"
                                    data-url="{{ route('categories.update', $category->id) }}"
                                    class="action-btn" style="border:none; background:none; cursor:pointer; color:#3498db;">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="border:none; background:none; cursor:pointer;"><i class="fas fa-trash" style="color: #e74c3c;"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="text-align:center; padding: 20px;">Data kategori kosong</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Area --}}
    <div id="categoryModal" class="custom-modal-backdrop">
        <div class="modal-content-custom">
            <div class="modal-header-custom">
                <h3 style="margin:0" id="modalTitle">Tambah Kategori Baru</h3>
                <span style="cursor:pointer; font-size: 24px;" onclick="closeModal()">&times;</span>
            </div>
            <form id="categoryForm" action="" method="POST">
                @csrf
                <div id="methodField"></div> {{-- Untuk Injeksi @method('PUT') --}}
                
                <div class="modal-body-custom">
                    <div class="form-group">
                        <label>Nama Kategori</label>
                        <input name="name" id="catName" type="text" class="form-control" placeholder="Contoh: Sains & Teknologi" required>
                    </div>
                    <div class="form-group">
                        <label>Slug (URL)</label>
                        <input name="slug" id="catSlug" type="text" class="form-control" readonly style="background: #eee">
                    </div>
                    <div class="form-group">
                        <label>Icon (FontAwesome)</label>
                        <input name="icon" id="catIcon" type="text" class="form-control" placeholder="fas fa-tag">
                    </div>
                </div>
                <div class="modal-footer-custom">
                    <button type="button" class="btn-batal" onclick="closeModal()">Batal</button>
                    <button type="submit" class="btn-simpan" id="btnSubmit">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const modal = $('#categoryModal');
        const form = document.getElementById('categoryForm');
        const methodField = document.getElementById('methodField');
        const modalTitle = document.getElementById('modalTitle');
        const btnSubmit = document.getElementById('btnSubmit');

        // Fungsi Buka Modal Tambah
        function openAddModal() {
            modalTitle.innerText = "Tambah Kategori Baru";
            btnSubmit.innerText = "Simpan Kategori";
            form.action = "{{ route('categories.store') }}";
            methodField.innerHTML = ""; 
            
            $('#catName').val('');
            $('#catSlug').val('');
            $('#catIcon').val('fas fa-tag');
            
            modal.css('display', 'flex');
        }

        // Fungsi Buka Modal Edit (Gunakan data dari tombol)
        function openEditModal(button) {
            const btn = $(button);
            modalTitle.innerText = "Edit Kategori";
            btnSubmit.innerText = "Update Kategori";
            
            // Set URL Action secara dinamis dari data-url
            form.action = btn.data('url');
            
            // Tambahkan Method PUT agar Laravel mengenali sebagai Update
            methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">'; 
            
            // Isi data ke input field
            $('#catName').val(btn.data('name'));
            $('#catSlug').val(btn.data('slug'));
            $('#catIcon').val(btn.data('icon'));
            
            modal.css('display', 'flex');
        }

        function closeModal() {
            modal.css('display', 'none');
        }

        $(document).ready(function() {
            // Auto Slug Script
            $('#catName').on('input', function() {
                let text = $(this).val();
                let slug = text.toLowerCase()
                               .replace(/[^a-z0-9 -]/g, '')
                               .replace(/\s+/g, '-')
                               .replace(/-+/g, '-');
                $('#catSlug').val(slug);
            });

            // Search Table Script
            $("#categorySearch").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#categoryTableBody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            // Klik Luar Modal untuk Tutup
            $(window).on('click', function(event) {
                if ($(event.target).is(modal)) closeModal();
            });
        });
    </script>
@endsection