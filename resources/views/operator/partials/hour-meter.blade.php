<h2 class="section-title mb-4 flex items-center gap-2 pb-3 border-b">
    <span class="material-symbols-outlined text-blue-500">timer</span>
    Hour Meter (HM)
</h2>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div>
        <label class="form-label">HM Awal</label>
        <input type="number" name="hm_awal" class="form-input" step="0.1" min="0" value="0.0" required id="hmAwal">
    </div>
    <div>
        <label class="form-label">HM Akhir</label>
        <input type="number" name="hm_akhir" class="form-input" step="0.1" min="0" value="0.0" required id="hmAkhir">
    </div>
    <div class="bg-slate-100 rounded-lg p-4 flex items-center justify-between">
        <span class="text-sm font-medium">Total Durasi HM:</span>
        <span class="text-xl font-bold" style="color: var(--text);" id="hmTotal">0.0 Jam</span>
    </div>
</div>
