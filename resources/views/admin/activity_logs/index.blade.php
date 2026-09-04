@extends('admin.layouts.app')

@section('title', 'Catatan Aktivitas User')
@section('page-title', 'Catatan Aktivitas User (Activity Log)')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg sm:text-xl font-bold text-slate-800 leading-tight">Catatan Aktivitas User (Activity Log)</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Pantau seluruh aktivitas pengguna di dalam sistem admin secara real-time.</p>
        </div>

        @if(auth()->user()->role?->name === 'Admin')
        <form action="{{ route('admin.activity-logs.destroy-all') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus seluruh catatan Activity Log? Action ini tidak dapat dibatalkan.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 text-xs font-semibold border border-red-200 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Bersihkan Log Aktivitas
            </button>
        </form>
        @endif
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5">
        <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
            {{-- Search Keyword --}}
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Cari Kata Kunci</label>
                <div class="relative">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari aktivitas, nama user, IP..."
                           class="w-full pl-9 pr-3 py-2 rounded-xl border border-slate-200 text-xs text-slate-800 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            {{-- Filter User --}}
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Pengguna (User)</label>
                <select name="user_id" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs text-slate-800 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                    <option value="">Semua User</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id_user }}" {{ request('user_id') == $u->id_user ? 'selected' : '' }}>
                            {{ $u->name }} ({{ $u->username }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Action --}}
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Jenis Aksi</label>
                <select name="action" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs text-slate-800 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                    <option value="">Semua Aksi</option>
                    @foreach($actions as $act)
                        <option value="{{ $act }}" {{ request('action') == $act ? 'selected' : '' }}>
                            {{ strtoupper($act) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Date From & To --}}
            <div class="sm:col-span-2 md:col-span-5 grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1 border-t border-slate-100 mt-1">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Dari Tanggal</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs text-slate-800 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Sampai Tanggal</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs text-slate-800 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold transition-colors shadow-sm">
                        Terapkan Filter
                    </button>
                    @if(request()->anyFilled(['q', 'user_id', 'action', 'date_from', 'date_to']))
                        <a href="{{ route('admin.activity-logs.index') }}" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold transition-colors">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Activity Log Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="px-4 py-3.5 w-12 text-center">#</th>
                        <th class="px-4 py-3.5 w-44">Waktu</th>
                        <th class="px-4 py-3.5">Pengguna</th>
                        <th class="px-4 py-3.5 w-32">Aksi</th>
                        <th class="px-4 py-3.5">Deskripsi Aktivitas</th>
                        <th class="px-4 py-3.5 w-36">IP & Perangkat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($logs as $index => $log)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-4 py-3.5 text-center font-medium text-slate-400">
                                {{ $logs->firstItem() + $index }}
                            </td>
                            <td class="px-4 py-3.5 text-slate-600 font-medium whitespace-nowrap">
                                <div>{{ $log->created_at->translatedFormat('d M Y H:i') }}</div>
                                <div class="text-[10px] text-slate-400">{{ $log->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-4 py-3.5">
                                @if($log->user)
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center font-bold text-xs shrink-0">
                                            {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-slate-800">{{ $log->user->name }}</div>
                                            <div class="text-[10px] text-slate-400 font-mono">{{ $log->user->username }} &bull; {{ $log->user->role?->name ?? 'User' }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-slate-400 italic">Sistem / Pengunjung</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                @php
                                    $badgeClass = match(strtolower($log->action)) {
                                        'login' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                        'logout' => 'bg-slate-100 text-slate-700 border-slate-200',
                                        'create' => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'update' => 'bg-amber-100 text-amber-800 border-amber-200',
                                        'delete' => 'bg-red-100 text-red-800 border-red-200',
                                        default => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase border tracking-wider inline-block {{ $badgeClass }}">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-slate-700 font-medium">
                                {{ $log->description }}
                            </td>
                            <td class="px-4 py-3.5 text-slate-500 font-mono text-[11px] whitespace-nowrap">
                                <div>{{ $log->ip_address ?? '127.0.0.1' }}</div>
                                @if($log->user_agent)
                                    <div class="text-[10px] text-slate-400 truncate max-w-[140px]" title="{{ $log->user_agent }}">
                                        {{ Str::limit($log->user_agent, 20) }}
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-slate-400">
                                <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-sm font-semibold">Belum ada catatan aktivitas user.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
