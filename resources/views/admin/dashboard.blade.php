@extends('layouts.admin')

@section('title', 'Dashboard Pemantauan')

@section('content')
<div x-data="dashboardApp()" x-init="init()" class="space-y-4">

    <!-- Header Actions -->
    <div class="header-actions">
        <div class="period-switch">
            <button type="button" 
                @click="setPeriod('daily')" 
                :class="{ active: period === 'daily' }"
                class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                Daily
            </button>
            <button type="button" 
                @click="setPeriod('weekly')" 
                :class="{ active: period === 'weekly' }"
                class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                Weekly
            </button>
            <button type="button" 
                @click="setPeriod('monthly')" 
                :class="{ active: period === 'monthly' }"
                class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                Monthly
            </button>
        </div>
        <div class="actions-right">
            <button type="button" class="btn">⬇ Export</button>
            <button type="button" class="btn sync">⟳ Sync Data</button>
        </div>
    </div>

    <!-- Breakdown Activity Card -->
    <div class="card">
        <div class="card-head">
            <h3 x-text="breakdownTitle"></h3>
            <div class="stat-strip" id="stat-strip"></div>
        </div>

        <div class="breakdown-grid">
            <!-- Target Chart -->
            <div>
                <div class="target-chart" id="target-chart"></div>
                <div class="legend-row">
                    <span><i class="sw" style="background:var(--ore)"></i>Ore (utama)</span>
                    <span><i class="sw" style="background:var(--blue)"></i>Material lain</span>
                    <span><i class="sw" style="background:var(--grey)"></i>Belum capai target</span>
                    <span><i class="sw" style="border-top:2px dashed #8a8f9e;background:none;width:14px;height:0"></i>Garis target</span>
                </div>
            </div>

            <!-- Day Shift -->
            <div>
                <div class="shift-title">Day Shift (00:00–12:00)</div>
                <div id="day-shift"></div>
                <div class="axis-row"><span>0.0</span><span>4.0</span><span>8.0</span><span>12.0</span></div>
            </div>

            <!-- Night Shift -->
            <div>
                <div class="shift-title">Night Shift (12:00–24:00)</div>
                <div id="night-shift"></div>
                <div class="axis-row"><span>12.0</span><span>16.0</span><span>20.0</span><span>24.0</span></div>
            </div>
        </div>

        <div class="legend-row" style="margin-top:14px;">
            <span><i class="sw" style="background:var(--green)"></i>Running / digunakan</span>
            <span><i class="sw" style="background:var(--red)"></i>Breakdown / rusak</span>
            <span><i class="sw" style="background:var(--white-slot);border:1px solid #d7dae2"></i>Standby / tidak digunakan</span>
        </div>
    </div>

    <!-- Hauling Card -->
    <div class="card">
        <h3 x-text="haulingTitle"></h3>
        <div class="hauling-grid">
            <div>
                <div class="weekly-total" id="hauling-total"></div>
                <div id="hbar-list"></div>
            </div>
            <div>
                <div class="sub-card">
                    <div class="donut-title">Availability</div>
                    <div class="donut-block" id="avail-donuts"></div>
                </div>
                <div class="sub-card">
                    <div class="donut-title">UoA</div>
                    <div class="donut-block" id="uoa-donuts"></div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
    :root {
        --navy: #0b1440;
        --navy-2: #141d54;
        --blue: #2f5bd7;
        --blue-light: #dbe4fb;
        --grey: #c7cdd9;
        --grey-light: #eef0f4;
        --red: #e5484d;
        --green: #2fae66;
        --white-slot: #f3f4f7;
        --ore: #e07b1c;
        --ink: #141a2e;
        --sub: #6b7280;
        --card: #ffffff;
        --bg: #f4f5f8;
        --radius: 14px;
    }

    * { box-sizing: border-box; }

    .main-content {
        padding: 26px 34px;
        max-width: 1500px;
    }

    h2.page-title { font-size: 32px; margin: 18px 0 2px; color: var(--navy); }
    p.page-sub { color: var(--sub); margin: 0 0 18px; }

    .header-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .period-switch {
        display: inline-flex;
        background: #e7e9f0;
        border-radius: 10px;
        padding: 4px;
    }
    .period-switch button {
        border: none;
        background: transparent;
        padding: 8px 18px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #5b6172;
        cursor: pointer;
        transition: all 0.2s;
    }
    .period-switch button.active {
        background: var(--navy);
        color: #fff;
    }
    .period-switch button:hover:not(.active) {
        background: #d5d8e8;
    }

    .actions-right { display: flex; gap: 10px; }
    .btn {
        padding: 9px 16px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 600;
        border: 1px solid #d7dae2;
        background: #fff;
        cursor: pointer;
    }
    .btn.sync {
        background: #f2a541;
        border-color: #f2a541;
        color: #1a1330;
    }

    .card {
        background: var(--card);
        border-radius: var(--radius);
        padding: 22px 24px;
        margin-bottom: 22px;
        box-shadow: 0 1px 3px rgba(20,26,46,.06);
    }
    .card h3 { margin: 0 0 16px; font-size: 19px; }
    .card-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 14px;
        margin-bottom: 6px;
    }

    .stat-strip { display: flex; gap: 10px; flex-wrap: wrap; }
    .stat-chip {
        background: var(--grey-light);
        border-radius: 10px;
        padding: 8px 14px;
        min-width: 112px;
        text-align: center;
    }
    .stat-chip b { display: block; font-size: 18px; color: var(--navy); }
    .stat-chip span { font-size: 11px; color: var(--sub); }
    .stat-chip.hours b { color: var(--blue); }
    .stat-chip.achv b { color: var(--green); }
    .stat-chip.bd b { color: var(--red); }
    .stat-chip.standby b { color: #9aa0b0; }

    .breakdown-grid {
        display: grid;
        grid-template-columns: 1.15fr 1fr 1fr;
        gap: 18px;
        margin-top: 10px;
    }
    @media(max-width: 1100px) { .breakdown-grid { grid-template-columns: 1fr; } }

    .target-chart {
        display: flex;
        align-items: flex-end;
        gap: 14px;
        height: 230px;
        padding-top: 10px;
        border-bottom: 1px solid #e6e8ef;
        position: relative;
    }
    .bar-col {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-end;
        flex: 1;
        height: 100%;
        position: relative;
    }
    .bar-stack {
        width: 60%;
        max-width: 38px;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        height: 100%;
        position: relative;
        border-radius: 6px 6px 0 0;
        overflow: visible;
    }
    .bar-grey { background: var(--grey); width: 100%; border-radius: 6px 6px 0 0; }
    .bar-fill { width: 100%; border-radius: 0 0 0 0; }
    .bar-fill.blue { background: var(--blue); }
    .bar-fill.ore { background: var(--ore); }
    .bar-fill.hit { border-radius: 6px 6px 0 0; }
    .target-line {
        position: absolute;
        left: -6px; right: -6px;
        border-top: 2px dashed #8a8f9e;
    }
    .bar-value { font-size: 11.5px; font-weight: 700; color: var(--ink); margin-bottom: 4px; }
    .bar-target { font-size: 9.5px; color: var(--sub); margin-bottom: 2px; }
    .bar-label {
        margin-top: 8px;
        font-size: 10.5px;
        text-align: center;
        color: var(--sub);
        line-height: 1.2;
        max-width: 70px;
    }
    .bar-label.main { color: var(--ink); font-weight: 700; }

    .legend-row {
        display: flex;
        gap: 16px;
        margin-top: 14px;
        font-size: 11.5px;
        color: var(--sub);
        flex-wrap: wrap;
    }
    .legend-row span { display: inline-flex; align-items: center; gap: 6px; }
    .sw { width: 10px; height: 10px; border-radius: 3px; display: inline-block; }

    .shift-title { font-weight: 700; font-size: 14px; margin-bottom: 10px; }
    .shift-row { display: flex; align-items: center; gap: 8px; margin-bottom: 8px; }
    .shift-row .eq-name { width: 104px; font-size: 11.5px; flex-shrink: 0; color: var(--ink); }
    .shift-track { flex: 1; height: 22px; border-radius: 6px; overflow: hidden; display: flex; background: var(--white-slot); border: 1px solid #e4e6ec; }
    .seg { height: 100%; display: flex; align-items: center; justify-content: center; font-size: 9.5px; font-weight: 700; color: #fff; }
    .seg.running { background: var(--green); }
    .seg.breakdown { background: var(--red); }
    .seg.standby { background: var(--white-slot); color: #9aa0b0; }
    .axis-row { display: flex; justify-content: space-between; font-size: 10px; color: var(--sub); margin: 6px 0 4px 112px; }

    .hauling-grid { display: grid; grid-template-columns: 1.1fr 1fr; gap: 20px; }
    @media(max-width: 1000px) { .hauling-grid { grid-template-columns: 1fr; } }
    .weekly-total { background: var(--grey-light); border-radius: 10px; padding: 10px 16px; display: inline-block; margin-bottom: 14px; }
    .weekly-total b { font-size: 20px; color: var(--navy); margin-left: 8px; }
    .hbar-row { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
    .hbar-row .name { width: 110px; font-size: 12px; color: var(--ink); text-align: right; flex-shrink: 0; }
    .hbar-track { flex: 1; background: var(--grey-light); border-radius: 6px; height: 16px; position: relative; }
    .hbar-fill { background: var(--blue); height: 100%; border-radius: 6px; }
    .hbar-val { font-size: 11.5px; font-weight: 700; width: 56px; color: var(--ink); }

    .donut-block { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; }
    .donut { display: flex; flex-direction: column; align-items: center; font-size: 11.5px; color: var(--ink); }
    .donut svg { margin-bottom: 4px; }
    .donut b { font-size: 12.5px; }
    .donut-title { grid-column: 1/-1; font-weight: 700; font-size: 13px; margin-bottom: 4px; }
    .sub-card { background: var(--grey-light); border-radius: 10px; padding: 14px; margin-bottom: 14px; }
</style>
@endpush

@push('scripts')
<script>
function dashboardApp() {
    return {
        period: 'daily',
        data: @json($dashboardData),
        
        init() {
            this.period = this.data.period || 'daily';
            this.render();
        },
        
        setPeriod(p) {
            this.period = p;
            this.render();
        },
        
        get currentData() {
            return this.data[this.period];
        },

        render() {
            if (!this.currentData) return;
            
            const d = this.currentData;
            const periods = { daily: 'Today', weekly: 'WTD', monthly: 'MTD' };
            
            document.getElementById('breakdown-title').textContent = d.label + ' Breakdown Activity';
            document.getElementById('hauling-title').textContent = 'Grafik All Hauling (' + (periods[this.period] || 'Today') + ')';
            document.getElementById('hauling-total').innerHTML = d.haulingLabel + ' &nbsp; <b>' + d.haulingTotal.toLocaleString() + '</b>';
            
            this.renderTargetChart(d.materials);
            this.renderShift(d.segs_day || this.data.shift_segments.day, 'day-shift');
            this.renderShift(d.segs_night || this.data.shift_segments.night, 'night-shift');
            this.renderStatStrip();
            this.renderHbars(this.data.all_materials_hbar);
            this.renderDonuts('avail-donuts', this.data.availability);
            this.renderDonuts('uoa-donuts', this.data.uoa);
        },

        renderTargetChart(materials) {
            const el = document.getElementById('target-chart');
            el.innerHTML = '';
            
            const arr = Array.from(materials);
            const scaleMax = Math.max(...arr.map(m => Math.max(m.value, m.target))) * 1.05;
            
            arr.forEach(m => {
                const col = document.createElement('div');
                col.className = 'bar-col';
                
                const stack = document.createElement('div');
                stack.className = 'bar-stack';
                stack.style.height = '190px';
                
                const valuePct = (m.value / scaleMax) * 100;
                const targetPct = (m.target / scaleMax) * 100;
                const achieved = m.value >= m.target;
                
                const fill = document.createElement('div');
                fill.className = 'bar-fill ' + (m.main ? 'ore' : 'blue') + (achieved ? ' hit' : '');
                fill.style.height = valuePct + '%';
                stack.appendChild(fill);
                
                if (!achieved && m.target > m.value) {
                    const grey = document.createElement('div');
                    grey.className = 'bar-grey';
                    grey.style.height = (targetPct - valuePct) + '%';
                    stack.insertBefore(grey, fill);
                }
                
                const line = document.createElement('div');
                line.className = 'target-line';
                line.style.bottom = targetPct + '%';
                stack.appendChild(line);
                
                const val = document.createElement('div');
                val.className = 'bar-value';
                val.textContent = m.value.toLocaleString();
                col.appendChild(val);
                
                const tval = document.createElement('div');
                tval.className = 'bar-target';
                tval.textContent = 'target ' + m.target.toLocaleString();
                col.appendChild(tval);
                
                col.appendChild(stack);
                
                const name = document.createElement('div');
                name.className = 'bar-label' + (m.main ? ' main' : '');
                name.textContent = m.name;
                col.appendChild(name);
                
                el.appendChild(col);
            });
        },

        renderShift(rows, containerId) {
            const el = document.getElementById(containerId);
            el.innerHTML = '';
            
            const totalHours = rows.reduce((max, r) => Math.max(max, r.segs.reduce((a, s) => a + s.h, 0)), 0) || 12;
            
            rows.forEach(r => {
                const row = document.createElement('div');
                row.className = 'shift-row';
                
                const name = document.createElement('div');
                name.className = 'eq-name';
                name.textContent = r.name;
                row.appendChild(name);
                
                const track = document.createElement('div');
                track.className = 'shift-track';
                
                r.segs.forEach(s => {
                    const seg = document.createElement('div');
                    seg.className = 'seg ' + s.t;
                    seg.style.width = (s.h / totalHours * 100) + '%';
                    if (s.h / totalHours > 0.09) seg.textContent = s.h + 'h';
                    track.appendChild(seg);
                });
                
                row.appendChild(track);
                el.appendChild(row);
            });
        },

        renderStatStrip() {
            const s = this.data.stat_strip;
            const html = `
                <div class="stat-chip hours"><b>${s.total_running_hours}h</b><span>Total Jam (unit running)</span></div>
                <div class="stat-chip achv"><b>${s.achievement_pct}%</b><span>Capaian Jam Running</span></div>
                <div class="stat-chip bd"><b>${s.total_bd_hours}h</b><span>Total BD</span></div>
                <div class="stat-chip standby"><b>${s.total_standby_hours}h</b><span>Standby</span></div>
            `;
            document.getElementById('stat-strip').innerHTML = html;
        },

        renderHbars(hbars) {
            const listEl = document.getElementById('hbar-list');
            listEl.innerHTML = '';
            
            const maxH = Math.max(...hbars.map(b => b.val));
            
            hbars.forEach(b => {
                const row = document.createElement('div');
                row.className = 'hbar-row';
                row.innerHTML = `
                    <div class="name">${b.name}</div>
                    <div class="hbar-track"><div class="hbar-fill" style="width:${(b.val/maxH*100)}%"></div></div>
                    <div class="hbar-val">${b.val.toLocaleString()}</div>
                `;
                listEl.appendChild(row);
            });
        },

        renderDonuts(containerId, obj) {
            const el = document.getElementById(containerId);
            el.innerHTML = '';
            
            const colorVar = containerId === 'avail-donuts' ? 'var(--blue)' : 'var(--green)';
            const r = 26;
            const c = 2 * Math.PI * r;
            
            Object.keys(obj).forEach(k => {
                const pct = obj[k];
                const off = c * (1 - pct / 100);
                const wrap = document.createElement('div');
                wrap.className = 'donut';
                wrap.innerHTML = `
                    <svg width="66" height="66" viewBox="0 0 66 66">
                        <circle cx="33" cy="33" r="${r}" fill="none" stroke="var(--grey-light)" stroke-width="8"/>
                        <circle cx="33" cy="33" r="${r}" fill="none" stroke="${colorVar}" stroke-width="8"
                            stroke-dasharray="${c}" stroke-dashoffset="${off}" stroke-linecap="round"
                            transform="rotate(-90 33 33)"/>
                    </svg>
                    <b>${k}</b><span>${pct}%</span>
                `;
                el.appendChild(wrap);
            });
        },
    };
}
</script>
@endpush