<form method="POST" action="{{ route('filament.admin.auth.logout') }}" style="width: 100%; padding: 0 8px;">
    @csrf
    <button type="submit" style="display: flex; width: 100%; align-items: center;gap: 12px;border: none;background: transparent; padding: 8px 12px;font-size: 14px;font-weight: 500; color: #dc2626; cursor: pointer;  border-radius: 8px; transition: background 0.2s;
    " onmouseover="this.style.backgroundColor='rgba(220, 38, 38, 0.08)'" onmouseout="this.style.backgroundColor='transparent'">

        <x-heroicon-o-arrow-left-on-rectangle style="width: 20px; height: 20px; flex-shrink: 0; color: #dc2626;" />

        <span>Đăng xuất</span>
    </button>
</form>