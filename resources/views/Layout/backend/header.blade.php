<header class="topbar" style="display: flex; justify-content: flex-end; align-items: center; padding: 10px 30px; background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
    
    <div class="topbar-right">
        <div style="display: flex; align-items: center;">
            {{-- Klik Area: Profil BE (Admin Profile) --}}
            <div class="topbar-user" onclick="window.location.href='{{ route('admin.profile') }}'" style="display: flex; align-items: center; gap: 12px; cursor: pointer; padding: 6px 15px; border-radius: 12px; transition: 0.3s;" onmouseover="this.style.background='#f0eeff'">
                
                @if(Auth::user()->img)
                    <img src="{{ asset('storage/' . Auth::user()->img) }}" alt="Profile" class="user-avatar" style="object-fit: cover; width: 38px; height: 38px; border-radius: 50%; border: 2px solid #7c3aff;">
                @else
                    <div class="user-avatar" style="width: 38px; height: 38px; border-radius: 50%; background: linear-gradient(135deg, #7c3aff, #b06aff); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 15px; box-shadow: 0 4px 10px rgba(124, 58, 255, 0.2);">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif

                <div style="display: flex; flex-direction: column;">
                    <span class="user-name" style="font-weight: 700; font-size: 14px; color: #1e1b3a; line-height: 1.2;">{{ Auth::user()->name }}</span>
                    <span style="font-size: 10px; color: #7c3aff; text-transform: uppercase; font-weight: 800; letter-spacing: 0.5px;">{{ Auth::user()->role }}</span>
                </div>

                <i class="fas fa-chevron-right" style="font-size: 10px; color: #ccc; margin-left: 5px;"></i>
            </div>
        </div>
    </div>
</header>