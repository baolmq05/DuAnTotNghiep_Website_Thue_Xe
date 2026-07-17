<div class="flex flex-col items-center justify-center p-4 bg-slate-50 border border-slate-100 rounded-xl">
    @if(!empty($getRecord()->user?->bank_name) && !empty($getRecord()->user?->bank_account_number))
        <img 
            src="https://qr.sepay.vn/img?bank={{ urlencode($getRecord()->user->bank_name) }}&acc={{ $getRecord()->user->bank_account_number }}&template=compact&amount={{ intval($getRecord()->amount) }}&des={{ urlencode('REF ' . $getRecord()->id) }}" 
            alt="VietQR Transfer Code" 
            class="w-64 h-64 object-contain rounded-lg shadow-sm border border-slate-200"
        />
        <div class="mt-3 text-xs font-semibold text-slate-500 text-center">
            Nội dung CK: <strong class="text-[#286874] font-mono select-all">{{ 'REF ' . $getRecord()->id }}</strong>
        </div>
    @else
        <p class="text-sm text-red-500 font-semibold p-4 bg-red-50 rounded-lg border border-red-200 text-center">
            Khách hàng chưa liên kết ngân hàng đầy đủ.
        </p>
    @endif
</div>
