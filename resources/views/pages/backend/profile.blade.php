@extends('layout.backend.app')

@section('content')
    <div class="content">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 30px;">
            <div>
                <h2 style="font-size: 26px; font-weight: 800; color: #1e1b3a; margin: 0;">Profil Akun</h2>
                <p style="color: #888; font-size: 14px; margin-top: 5px;">Informasi lengkap identitas dan akses Anda dalam
                    sistem.</p>
            </div>
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('landing') }}"
                    style="text-decoration: none; display: flex; align-items: center; gap: 8px; background: #fff; color: #7c3aff; padding: 10px 20px; border-radius: 12px; font-weight: 700; font-size: 13px; border: 1px solid #7c3aff; transition: 0.3s;"
                    onmouseover="this.style.background='#7c3aff'; this.style.color='#fff'"
                    onmouseout="this.style.background='#fff'; this.style.color='#7c3aff'">
                    <i class="fas fa-external-link-alt"></i> Lihat Frontend
                </a>
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit"
                        style="display: flex; align-items: center; gap: 8px; background: #f5364f; color: #fff; padding: 10px 20px; border-radius: 12px; font-weight: 700; font-size: 13px; border: none; cursor: pointer; transition: 0.3s;"
                        onmouseover="this.style.background='#d32f2f'" onmouseout="this.style.background='#f5364f'">
                        <i class="fas fa-power-off"></i> Keluar Sesi
                    </button>
                </form>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 350px 1fr; gap: 30px;">

            <div
                style="background: #fff; border-radius: 24px; padding: 40px 30px; box-shadow: 0 10px 40px rgba(0,0,0,0.04); text-align: center; border: 1px solid #f0f0f0;">
                <div style="position: relative; display: inline-block; margin-bottom: 25px;">
                    @if(Auth::user()->img)
                        <img src="{{ asset('storage/' . Auth::user()->img) }}"
                            style="width: 140px; height: 140px; border-radius: 40px; object-fit: cover; border: 6px solid #f8f9ff; box-shadow: 0 15px 30px rgba(124, 58, 255, 0.15);">
                    @else
                        <div
                            style="width: 140px; height: 140px; border-radius: 40px; background: linear-gradient(135deg, #1e1b3a, #3a3570); color: white; display: flex; align-items: center; justify-content: center; font-size: 55px; font-weight: 800; box-shadow: 0 15px 30px rgba(30, 27, 58, 0.2);">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <div
                        style="position: absolute; bottom: -5px; right: -5px; width: 35px; height: 35px; background: #26c6a6; border-radius: 12px; border: 4px solid #fff; display: flex; align-items: center; justify-content: center; color: white;">
                        <i class="fas fa-check" style="font-size: 12px;"></i>
                    </div>
                </div>

                <h3 style="font-size: 22px; color: #1e1b3a; margin-bottom: 5px; font-weight: 800;">{{ Auth::user()->name }}
                </h3>
                <p style="color: #888; font-size: 14px; margin-bottom: 25px;">{{ Auth::user()->email }}</p>

                <div
                    style="background: #f0eeff; color: #7c3aff; padding: 10px; border-radius: 14px; font-weight: 800; font-size: 12px; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 10px;">
                    ROLE: {{ Auth::user()->role }}
                </div>

                <div style="margin-top: 30px; text-align: left; background: #fafafa; padding: 20px; border-radius: 18px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span style="color: #aaa; font-size: 12px; font-weight: 600;">Status</span>
                        <span style="color: #26c6a6; font-size: 12px; font-weight: 700;">● Aktif</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #aaa; font-size: 12px; font-weight: 600;">Sesi IP</span>
                        <span style="color: #1e1b3a; font-size: 12px; font-weight: 700;">{{ request()->ip() }}</span>
                    </div>
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 25px;">

                <div
                    style="background: #fff; border-radius: 24px; padding: 35px; box-shadow: 0 10px 40px rgba(0,0,0,0.04); border: 1px solid #f0f0f0;">
                    <h4
                        style="font-size: 16px; color: #1e1b3a; margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-database" style="color: #7c3aff;"></i> Metadata Pengguna
                    </h4>

                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                        <div style="padding: 20px; background: #fcfaff; border-radius: 16px; border: 1px solid #f0eeff;">
                            <label
                                style="display: block; font-size: 11px; color: #b0b0b0; font-weight: 700; text-transform: uppercase; margin-bottom: 8px;">User
                                Unique ID</label>
                            <div style="font-size: 15px; font-weight: 700; color: #1e1b3a;">
                                #USR-{{ str_pad(Auth::user()->id, 5, '0', STR_PAD_LEFT) }}</div>
                        </div>

                        <div style="padding: 20px; background: #fcfaff; border-radius: 16px; border: 1px solid #f0eeff;">
                            <label
                                style="display: block; font-size: 11px; color: #b0b0b0; font-weight: 700; text-transform: uppercase; margin-bottom: 8px;">Email
                                Terdaftar</label>
                            <div style="font-size: 15px; font-weight: 700; color: #1e1b3a;">{{ Auth::user()->email }}</div>
                        </div>

                        <div style="padding: 20px; background: #fcfaff; border-radius: 16px; border: 1px solid #f0eeff;">
                            <label
                                style="display: block; font-size: 11px; color: #b0b0b0; font-weight: 700; text-transform: uppercase; margin-bottom: 8px;">Tanggal
                                Bergabung</label>
                            <div style="font-size: 15px; font-weight: 700; color: #1e1b3a;">
                                {{ Auth::user()->created_at->translatedFormat('d F Y') }}</div>
                        </div>

                        <div style="padding: 20px; background: #fcfaff; border-radius: 16px; border: 1px solid #f0eeff;">
                            <label
                                style="display: block; font-size: 11px; color: #b0b0b0; font-weight: 700; text-transform: uppercase; margin-bottom: 8px;">Terakhir
                                Diperbarui</label>
                            <div style="font-size: 15px; font-weight: 700; color: #1e1b3a;">
                                {{ Auth::user()->updated_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>

                <div
                    style="background: #1e1b3a; border-radius: 20px; padding: 25px; color: white; display: flex; align-items: center; gap: 20px;">
                    <div
                        style="width: 50px; height: 50px; background: rgba(255,255,255,0.1); border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        <i class="fas fa-user-lock" style="color: #b06aff;"></i>
                    </div>
                    <div>
                        <h5 style="margin: 0; font-size: 14px; font-weight: 700;">Keamanan Akun</h5>
                        <p style="margin: 5px 0 0 0; font-size: 12px; opacity: 0.7;">Email Anda
                            {{ Auth::user()->email_verified_at ? 'sudah' : 'belum' }} diverifikasi secara sistem. Pastikan
                            untuk memperbarui password secara berkala.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection