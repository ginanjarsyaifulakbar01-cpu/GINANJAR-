@extends('layout.backend.app')

@section('content')
<div class="content">

    <div class="table-card">
        <div class="table-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; gap: 20px;">
            <div class="table-header-left">
                <h3 style="font-size: 16px; font-weight: 700; color: #1e1b3a;">Daftar Kategori</h3>
            </div>

            <div class="table-header-right" style="display: flex; align-items: center; gap: 15px;">
                <div style="position: relative;">
                    <i class="fas fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
                    <input type="text" id="categorySearch" placeholder="Cari kategori..." 
                        style="padding: 8px 15px 8px 35px; border: 1px solid #e2e8f0; border-radius: 8px; width: 220px; font-family: inherit; font-size: 12px; transition: 0.3s;">
                </div>

                <button type="button" onclick="openAddModal()" style="background: #7c3aff; color: #fff; border: none; padding: 9px 16px; border-radius: 8px; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 8px; font-size: 12px; transition: 0.3s; box-shadow: 0 4px 12px rgba(124, 58, 255, 0.2);">
                    <i class="fas fa-plus"></i> Kategori
                </button>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
                <thead>
                    <tr style="background: #f8fafc;">
                        <th style="width: 60px; text-align: center; padding: 12px; color: #64748b; font-size: 11px; text-transform: uppercase;">No</th>
                        <th style="width: 80px; text-align: center; padding: 12px; color: #64748b; font-size: 11px; text-transform: uppercase;">Icon</th>
                        <th style="padding: 12px; color: #64748b; font-size: 11px; text-transform: uppercase;">Nama Kategori</th>
                        <th style="padding: 12px; color: #64748b; font-size: 11px; text-transform: uppercase;">Slug</th>
                        <th style="width: 120px; text-align: center; padding: 12px; color: #64748b; font-size: 11px; text-transform: uppercase;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="categoryTableBody">
                    @forelse ($categories as $index => $category)
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: 0.2s;">
                        <td style="text-align: center; color: #94a3b8;">{{ $index + 1 }}</td>
                        <td style="text-align: center;">
                            <div style="width: 35px; height: 35px; background: #f0f0ff; color: #7c3aff; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center;">
                                <i class="{{ $category->icon ?? 'fas fa-tag' }}"></i>
                            </div>
                        </td>
                        <td><strong style="color: #1e1b3a;">{{ $category->name }}</strong></td>
                        <td><code style="background: #f1f5f9; padding: 2px 6px; border-radius: 4px; color: #e83e8c; font-size: 11px;">{{ $category->slug }}</code></td>
                        <td style="text-align: center;">
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <button type="button" onclick="openEditModal(this)" 
                                    data-id="{{ $category->id }}" data-name="{{ $category->name }}"
                                    data-slug="{{ $category->slug }}" data-icon="{{ $category->icon }}"
                                    data-url="{{ route('categories.update', $category->id) }}"
                                    style="width: 30px; height: 30px; background: #eef2ff; color: #4338ca; border: none; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: 0.2s;">
                                    <i class="fas fa-edit" style="font-size: 12px;"></i>
                                </button>

                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Yakin hapus?')" style="width: 30px; height: 30px; background: #fff1f2; color: #e11d48; border: none; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: 0.2s;">
                                        <i class="fas fa-trash" style="font-size: 12px;"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center; padding: 40px; color: #94a3b8;">Belum ada kategori.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL CUSTOM --}}
<div id="categoryModal" class="custom-modal-backdrop">
    <div class="modal-content-custom">
        <div class="modal-header-custom" style="background: #7c3aff; color: white; padding: 18px 25px; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin:0; font-size: 16px; font-weight: 600;" id="modalTitle">Tambah Kategori</h3>
            <span style="cursor:pointer; font-size: 20px;" onclick="closeModal()">&times;</span>
        </div>
        <form id="categoryForm" action="" method="POST">
            @csrf
            <div id="methodField"></div>
            <div class="modal-body-custom" style="padding: 25px;">
                <div class="form-group" style="margin-bottom: 18px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; font-size: 13px; color: #475569;">Nama Kategori</label>
                    <input name="name" id="catName" type="text" class="form-control" placeholder="Contoh: Sains & Teknologi" required 
                        style="width: 100%; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-family: inherit;">
                </div>
                <div class="form-group" style="margin-bottom: 18px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; font-size: 13px; color: #475569;">Slug (URL)</label>
                    <input name="slug" id="catSlug" type="text" class="form-control" readonly style="width: 100%; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f8fafc; color: #94a3b8;">
                </div>
                <div class="form-group">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; font-size: 13px; color: #475569;">Icon (FontAwesome)</label>
                    <input name="icon" id="catIcon" type="text" class="form-control" placeholder="fas fa-tag"
                        style="width: 100%; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 8px;">
                </div>
            </div>
            <div class="modal-footer-custom" style="padding: 15px 25px; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 10px; background: #fbfbfb;">
                <button type="button" class="btn-batal" onclick="closeModal()" style="background: #e2e8f0; color: #475569; border: none; padding: 10px 18px; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 12px;">Batal</button>
                <button type="submit" class="btn-simpan" id="btnSubmit" style="background: #7c3aff; color: white; border: none; padding: 10px 18px; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 12px;">Simpan Kategori</button>
            </div>
        </form>
    </div>
</div>

<style>
    .custom-modal-backdrop {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
        background: rgba(15, 23, 42, 0.5); z-index: 9999; 
        display: none; align-items: center; justify-content: center;
        backdrop-filter: blur(4px);
    }
    .modal-content-custom {
        background: white; width: 100%; max-width: 450px; 
        border-radius: 16px; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
        animation: modalFade 0.3s ease-out;
    }
    @keyframes modalFade { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const modal = $('#categoryModal');
    
    function openAddModal() {
        $('#modalTitle').text("Tambah Kategori");
        $('#btnSubmit').text("Simpan Kategori");
        $('#categoryForm').attr('action', "{{ route('categories.store') }}");
        $('#methodField').empty(); 
        $('#catName, #catSlug').val('');
        $('#catIcon').val('fas fa-tag');
        modal.css('display', 'flex');
    }

    function openEditModal(button) {
        const btn = $(button);
        $('#modalTitle').text("Edit Kategori");
        $('#btnSubmit').text("Update Kategori");
        $('#categoryForm').attr('action', btn.data('url'));
        $('#methodField').html('<input type="hidden" name="_method" value="PUT">'); 
        $('#catName').val(btn.data('name'));
        $('#catSlug').val(btn.data('slug'));
        $('#catIcon').val(btn.data('icon'));
        modal.css('display', 'flex');
    }

    function closeModal() { modal.hide(); }

    $(document).ready(function() {
        // Auto Slug
        $('#catName').on('input', function() {
            let slug = $(this).val().toLowerCase().replace(/[^a-z0-9 -]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-');
            $('#catSlug').val(slug);
        });

        // Search
        $("#categorySearch").on("keyup", function() {
            let value = $(this).val().toLowerCase();
            $("#categoryTableBody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        // Click Outside
        $(window).on('click', (e) => { if ($(e.target).is(modal)) closeModal(); });
    });
</script>
@endsection