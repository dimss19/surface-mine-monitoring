<h2 class="section-title mb-4 flex items-center gap-2 pb-3 border-b">
    <span class="material-symbols-outlined text-blue-500">local_gas_station</span>
    Konsumsi Fuel
</h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div>
        <label class="form-label">Konsumsi Fuel (Liter)</label>
        <input type="number" 
               name="fuel_consumption" 
               step="0.01" 
               min="0"
               class="form-input" 
               placeholder="0.00"
               value="{{ old('fuel_consumption') }}">
    </div>
</div>
