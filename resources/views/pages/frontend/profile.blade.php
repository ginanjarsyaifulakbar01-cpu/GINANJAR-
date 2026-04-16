@extends('Layout.frontend.app')

@section('content')
    <div class="container" style="padding-top: 120px; padding-bottom: 80px; min-height: 100vh;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 30px;">
            <div>
                <h2 style="font-size: 26px; font-weight: 800; color: #1e1b3a; margin: 0;">Profil Akun</h2>
                <p style="color: #888; font-size: 14px; margin-top: 5px;">Detail informasi akun Anda di PerpusGinx.</p>
            </div>
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('landing') }}"
                    style="text-decoration: none; display: flex; align-items: center; gap: 8px; background: #fff; color: #2563eb; padding: 10px 20px; border-radius: 12px; font-weight: 700; font-size: 13px; border: 1px solid #2563eb; transition: 0.3s;"
                    onmouseover="this.style.background='#2563eb'; this.style.color='#fff'"
                    onmouseout="this.style.background='#fff'; this.style.color='#2563eb'">
                    <i class="fas fa-home"></i> Beranda
                </a>
                @if(Auth::user()->role == 'admin' || Auth::user()->role == 'petugas')
                    <a href="{{ route('admin.dashboard') }}"
                        style="text-decoration: none; display: flex; align-items: center; gap: 8px; background: #1e1b3a; color: #fff; padding: 10px 20px; border-radius: 12px; font-weight: 700; font-size: 13px; border: none; transition: 0.3s;"
                        onmouseover="this.style.background='#3a3570'" onmouseout="this.style.background='#1e1b3a'">
                        <i class="fas fa-user-shield"></i> Panel Admin
                    </a>
                @endif
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit"
                        style="display: flex; align-items: center; gap: 8px; background: #f5364f; color: #fff; padding: 10px 20px; border-radius: 12px; font-weight: 700; font-size: 13px; border: none; cursor: pointer; transition: 0.3s;"
                        onmouseover="this.style.background='#d32f2f'" onmouseout="this.style.background='#f5364f'">
                        <i class="fas fa-power-off"></i> Logout
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
                            style="width: 140px; height: 140px; border-radius: 40px; object-fit: cover; border: 6px solid #f8f9ff; box-shadow: 0 15px 30px rgba(37, 99, 235, 0.1);">
                    @else
                        <div
                            style="width: 140px; height: 140px; border-radius: 40px; background: linear-gradient(135deg, #2563eb, #7c3aff); color: white; display: flex; align-items: center; justify-content: center; font-size: 55px; font-weight: 800; box-shadow: 0 15px 30px rgba(37, 99, 235, 0.2);">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <div
                        style="position: absolute; bottom: -5px; right: -5px; width: 35px; height: 35px; background: #22c55e; border-radius: 12px; border: 4px solid #fff; display: flex; align-items: center; justify-content: center; color: white;">
                        <i class="fas fa-check" style="font-size: 12px;"></i>
                    </div>
                </div>

                <h3 style="font-size: 22px; color: #1e1b3a; margin-bottom: 5px; font-weight: 800;">{{ Auth::user()->name }}
                </h3>
                <p style="color: #888; font-size: 14px; margin-bottom: 25px;">{{ Auth::user()->email }}</p>

                <div
                    style="background: #eff6ff; color: #2563eb; padding: 10px; border-radius: 14px; font-weight: 800; font-size: 12px; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 10px;">
                    {{ Auth::user()->role }}
                </div>

                <div style="margin-top: 30px; text-align: left; background: #fafafa; padding: 20px; border-radius: 18px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span style="color: #aaa; font-size: 12px; font-weight: 600;">Status</span>
                        <span style="color: #22c55e; font-size: 12px; font-weight: 700;">● Aktif</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #aaa; font-size: 12px; font-weight: 600;">Member ID</span>
                        <span style="color: #1e1b3a; font-size: 12px; font-weight: 700;">#{{ Auth::user()->id }}</span>
                    </div>
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 25px;">

                <div
                    style="background: #fff; border-radius: 24px; padding: 35px; box-shadow: 0 10px 40px rgba(0,0,0,0.04); border: 1px solid #f0f0f0;">
                    <h4
                        style="font-size: 16px; color: #1e1b3a; margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-info-circle" style="color: #2563eb;"></i> Informasi Akun
                    </h4>

                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                        <div style="padding: 20px; background: #f8fafc; border-radius: 16px; border: 1px solid #e2e8f0;">
                            <label
                                style="display: block; font-size: 11px; color: #94a3b8; font-weight: 700; text-transform: uppercase; margin-bottom: 8px;">User
                                ID</label>
                            <div style="font-size: 15px; font-weight: 700; color: #1e1b3a;">
                                #USR-{{ str_pad(Auth::user()->id, 5, '0', STR_PAD_LEFT) }}</div>
                        </div>

                        <div style="padding: 20px; background: #f8fafc; border-radius: 16px; border: 1px solid #e2e8f0;">
                            <label
                                style="display: block; font-size: 11px; color: #94a3b8; font-weight: 700; text-transform: uppercase; margin-bottom: 8px;">Email
                                Terdaftar</label>
                            <div style="font-size: 15px; font-weight: 700; color: #1e1b3a;">{{ Auth::user()->email }}</div>
                        </div>

                        <div style="padding: 20px; background: #f8fafc; border-radius: 16px; border: 1px solid #e2e8f0;">
                            <label
                                style="display: block; font-size: 11px; color: #94a3b8; font-weight: 700; text-transform: uppercase; margin-bottom: 8px;">Tanggal
                                Join</label>
                            <div style="font-size: 15px; font-weight: 700; color: #1e1b3a;">
                                {{ Auth::user()->created_at->format('d M Y') }}</div>
                        </div>

                        <div style="padding: 20px; background: #f8fafc; border-radius: 16px; border: 1px solid #e2e8f0;">
                            <label
                                style="display: block; font-size: 11px; color: #94a3b8; font-weight: 700; text-transform: uppercase; margin-bottom: 8px;">Terakhir
                                Update</label>
                            <div style="font-size: 15px; font-weight: 700; color: #1e1b3a;">
                                {{ Auth::user()->updated_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection