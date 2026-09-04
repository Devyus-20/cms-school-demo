import React, { useState } from 'react';

export default function LoginSiswaApp() {
    const [login, setLogin] = useState('');
    const [password, setPassword] = useState('');
    const [remember, setRemember] = useState(false);
    const [showPassword, setShowPassword] = useState(false);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState('');

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    const handleSubmit = async (e) => {
        e.preventDefault();
        setError('');
        setLoading(true);

        try {
            const response = await fetch('/login/siswa', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    login,
                    password,
                    remember,
                }),
            });

            const data = await response.json();

            if (response.ok && data.success) {
                window.location.href = data.redirect || '/siswa/dashboard';
            } else {
                setError(data.message || 'Gagal masuk. Periksa kembali Email/Username dan Password Anda.');
            }
        } catch (err) {
            setError('Terjadi kesalahan koneksi ke server. Silakan coba lagi.');
        } finally {
            setLoading(false);
        }
    };

    return (
        <div class="min-h-screen w-full bg-[#0f172a] flex items-center justify-center p-4 sm:p-6 lg:p-8 font-sans text-slate-200 selection:bg-amber-400 selection:text-slate-950">
            <div className="w-full max-w-md bg-slate-900/90 backdrop-blur-md rounded-[5px] p-8 sm:p-10 shadow-2xl border border-slate-800 relative overflow-hidden transition-all duration-300">
                {/* Subtle background glow */}
                <div className="absolute -top-24 -right-24 w-48 h-48 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
                <div className="absolute -bottom-24 -left-24 w-48 h-48 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

                {/* Back to Homepage Button */}
                <div className="mb-6 flex justify-start">
                    <a href="/" className="inline-flex items-center gap-2 text-xs font-bold text-slate-400 hover:text-amber-400 transition-colors py-1.5 px-3 rounded-[5px] bg-slate-800/80 border border-slate-700/80">
                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Kembali ke Beranda</span>
                    </a>
                </div>

                {/* Header Logo & Title */}
                <div className="text-center mb-8">
                    {window.websiteSetting?.logo ? (
                        <div className="inline-flex items-center justify-center mb-3">
                            <img src={`/${window.websiteSetting.logo.replace(/^\//, '')}`} alt="Logo Sekolah" className="w-16 h-16 object-contain" />
                        </div>
                    ) : (
                        <div className="inline-flex items-center justify-center mb-3">
                            <img src="/images/default-logo.png" alt="Logo Y-School" className="w-16 h-16 object-contain" />
                        </div>
                    )}
                    <h1 className="text-2xl font-extrabold text-white tracking-tight">Portal Login Siswa</h1>
                    <p className="text-xs text-slate-400 mt-1">Masuk untuk melihat Presensi, Tugas, & Rekap Nilai Akademik</p>
                </div>

                {/* Error Alert */}
                {error && (
                    <div className="mb-6 p-4 rounded-[5px] bg-red-950/60 border border-red-800/80 flex items-start space-x-3 text-red-300 animate-fadeIn">
                        <svg className="w-5 h-5 mt-0.5 shrink-0 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span className="text-xs sm:text-sm leading-snug">{error}</span>
                    </div>
                )}

                {/* Login Form */}
                <form onSubmit={handleSubmit} className="space-y-5">
                    <div>
                        <label className="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                            Email / Username / NIS
                        </label>
                        <input
                            type="text"
                            value={login}
                            onChange={(e) => setLogin(e.target.value)}
                            required
                            placeholder="Masukkan Email / Username / NIS"
                            className="w-full px-4 py-3 bg-slate-800/90 border border-slate-700 rounded-[5px] focus:bg-slate-800 focus:border-amber-400 text-white text-sm outline-none transition-all placeholder:text-slate-500"
                        />
                    </div>

                    <div>
                        <label className="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                            Password
                        </label>
                        <div className="relative">
                            <input
                                type={showPassword ? 'text' : 'password'}
                                value={password}
                                onChange={(e) => setPassword(e.target.value)}
                                required
                                placeholder="Masukkan password Anda"
                                className="w-full px-4 py-3 bg-slate-800/90 border border-slate-700 rounded-[5px] focus:bg-slate-800 focus:border-amber-400 text-white text-sm outline-none transition-all pr-10 placeholder:text-slate-500"
                            />
                            <button
                                type="button"
                                onClick={() => setShowPassword(!showPassword)}
                                className="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-200 focus:outline-none"
                            >
                                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    {showPassword ? (
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.018 10.018 0 014.122-.963c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m-6.84-2.88a3 3 0 11-4.243-4.243m4.242 4.242L3 3l18 18" />
                                    ) : (
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    )}
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div className="flex items-center justify-between text-xs">
                        <label className="flex items-center text-slate-300 cursor-pointer">
                            <input
                                type="checkbox"
                                checked={remember}
                                onChange={(e) => setRemember(e.target.checked)}
                                className="rounded border-slate-700 bg-slate-800 text-amber-500 focus:ring-amber-400 w-4 h-4"
                            />
                            <span className="ml-2 font-medium">Ingat saya</span>
                        </label>
                        <a href="/forgot-password" className="text-amber-400 hover:text-amber-300 font-semibold hover:underline">
                            Lupa password?
                        </a>
                    </div>

                    <button
                        type="submit"
                        disabled={loading}
                        className="w-full py-3.5 px-4 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded-[5px] shadow-lg active:scale-[0.98] transition-all duration-200 disabled:opacity-70 flex items-center justify-center space-x-2 text-sm uppercase tracking-wider"
                    >
                        {loading ? (
                            <>
                                <svg className="animate-spin h-4 w-4 text-slate-950" fill="none" viewBox="0 0 24 24">
                                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Memverifikasi...</span>
                            </>
                        ) : (
                            <span>Masuk ke Portal Siswa</span>
                        )}
                    </button>
                </form>

                {/* Registration link */}
                <div className="mt-6 p-3.5 rounded-[5px] bg-slate-800/80 border border-slate-700 text-center text-xs text-slate-300 flex items-center justify-between">
                    <span>Siswa baru / Belum punya akun?</span>
                    <a href="/register" className="font-bold text-amber-400 hover:text-amber-300 hover:underline inline-flex items-center gap-1">
                        Registrasi Akun &rarr;
                    </a>
                </div>

                <div className="mt-6 pt-5 border-t border-slate-800 text-center text-xs text-slate-500">
                    &copy; 2026 MA Al Ikhlas. Portal Siswa Digital.
                </div>
            </div>
        </div>
    );
}
