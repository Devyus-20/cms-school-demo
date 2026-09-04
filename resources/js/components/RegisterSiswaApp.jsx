import React, { useState } from 'react';

export default function RegisterSiswaApp() {
    const [username, setUsername] = useState('');
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [passwordConfirmation, setPasswordConfirmation] = useState('');
    const [showPassword, setShowPassword] = useState(false);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState('');

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    const handleSubmit = async (e) => {
        e.preventDefault();
        setError('');

        if (password !== passwordConfirmation) {
            setError('Konfirmasi password tidak cocok.');
            return;
        }

        setLoading(true);

        try {
            const response = await fetch('/register/siswa', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    username,
                    email,
                    password,
                    password_confirmation: passwordConfirmation,
                }),
            });

            const data = await response.json();

            if (response.ok && data.success) {
                window.location.href = data.redirect || '/siswa/dashboard';
            } else {
                setError(data.message || 'Registrasi gagal. Pastikan email Anda sudah didaftarkan oleh Admin.');
            }
        } catch (err) {
            setError('Terjadi kesalahan koneksi ke server. Silakan coba lagi.');
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="min-h-screen w-full bg-[#0f172a] flex items-center justify-center p-4 sm:p-6 lg:p-8 font-sans text-slate-200 selection:bg-amber-400 selection:text-slate-950">
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

                {/* Header */}
                <div className="text-center mb-6">
                    {window.websiteSetting?.logo ? (
                        <div className="inline-flex items-center justify-center mb-3">
                            <img src={`/${window.websiteSetting.logo.replace(/^\//, '')}`} alt="Logo Sekolah" className="w-16 h-16 object-contain" />
                        </div>
                    ) : (
                        <div className="inline-flex items-center justify-center mb-3">
                            <img src="/images/default-logo.png" alt="Logo Y-School" className="w-16 h-16 object-contain" />
                        </div>
                    )}
                    <h1 className="text-2xl font-extrabold text-white tracking-tight">Form Registrasi Siswa</h1>
                    <p className="text-xs text-slate-400 mt-1">
                        Khusus email yang sudah terdaftar di sistem Admin Sekolah.
                    </p>
                </div>

                {/* Info Note Alert */}
                <div className="mb-5 p-3.5 rounded-xl bg-slate-800/90 border border-slate-700 text-slate-300 text-xs flex items-start gap-2.5">
                    <svg className="w-5 h-5 text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>
                        <strong className="text-amber-400">Catatan:</strong> Registrasi ini khusus siswa. Hanya email yang telah dimasukkan Admin di sistem sekolah yang dapat berhasil mendaftar.
                    </span>
                </div>

                {/* Error Alert */}
                {error && (
                    <div className="mb-5 p-4 rounded-xl bg-red-950/60 border border-red-800/80 flex items-start space-x-3 text-red-300 animate-fadeIn">
                        <svg className="w-5 h-5 mt-0.5 shrink-0 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span className="text-xs sm:text-sm leading-snug">{error}</span>
                    </div>
                )}

                {/* Form */}
                <form onSubmit={handleSubmit} className="space-y-4">
                    {/* Username */}
                    <div>
                        <label className="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">
                            Username / Nama Pengguna *
                        </label>
                        <input
                            type="text"
                            value={username}
                            onChange={(e) => setUsername(e.target.value)}
                            required
                            placeholder="Ketik username (cth: Ahmad Fauzi)"
                            className="w-full px-3.5 py-2.5 bg-slate-800/90 border border-slate-700 rounded-xl focus:bg-slate-800 focus:border-amber-400 text-white text-xs sm:text-sm outline-none transition-all placeholder:text-slate-500"
                        />
                    </div>

                    {/* Email */}
                    <div>
                        <label className="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">
                            Email Terdaftar *
                        </label>
                        <input
                            type="email"
                            value={email}
                            onChange={(e) => setEmail(e.target.value)}
                            required
                            placeholder="ahmad.siswa@school.test"
                            className="w-full px-3.5 py-2.5 bg-slate-800/90 border border-slate-700 rounded-xl focus:bg-slate-800 focus:border-amber-400 text-white text-xs sm:text-sm outline-none transition-all placeholder:text-slate-500"
                        />
                    </div>

                    {/* Password */}
                    <div>
                        <label className="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">
                            Password *
                        </label>
                        <input
                            type={showPassword ? 'text' : 'password'}
                            value={password}
                            onChange={(e) => setPassword(e.target.value)}
                            required
                            placeholder="Minimal 8 karakter"
                            className="w-full px-3.5 py-2.5 bg-slate-800/90 border border-slate-700 rounded-xl focus:bg-slate-800 focus:border-amber-400 text-white text-xs sm:text-sm outline-none transition-all placeholder:text-slate-500"
                        />
                    </div>

                    {/* Confirm Password */}
                    <div>
                        <label className="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">
                            Confirm Password *
                        </label>
                        <input
                            type={showPassword ? 'text' : 'password'}
                            value={passwordConfirmation}
                            onChange={(e) => setPasswordConfirmation(e.target.value)}
                            required
                            placeholder="Ketik ulang password"
                            className="w-full px-3.5 py-2.5 bg-slate-800/90 border border-slate-700 rounded-xl focus:bg-slate-800 focus:border-amber-400 text-white text-xs sm:text-sm outline-none transition-all placeholder:text-slate-500"
                        />
                    </div>

                    <div className="flex items-center justify-between text-xs pt-1">
                        <label className="flex items-center text-slate-300 cursor-pointer">
                            <input
                                type="checkbox"
                                checked={showPassword}
                                onChange={(e) => setShowPassword(e.target.checked)}
                                className="rounded border-slate-700 bg-slate-800 text-amber-500 focus:ring-amber-400 w-4 h-4"
                            />
                            <span className="ml-2">Tampilkan Password</span>
                        </label>
                    </div>

                    <button
                        type="submit"
                        disabled={loading}
                        className="w-full py-3.5 px-4 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded-xl shadow-lg active:scale-[0.98] transition-all duration-200 disabled:opacity-70 flex items-center justify-center space-x-2 text-sm mt-3 uppercase tracking-wider"
                    >
                        {loading ? (
                            <>
                                <svg className="animate-spin h-4 w-4 text-slate-950" fill="none" viewBox="0 0 24 24">
                                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Memverifikasi Email...</span>
                            </>
                        ) : (
                            <span>Buat Akun Registrasi</span>
                        )}
                    </button>
                </form>

                <div className="mt-6 pt-5 border-t border-slate-800 text-center text-xs text-slate-400 flex justify-between items-center">
                    <span>Sudah memiliki akun?</span>
                    <a href="/login/siswa" className="text-amber-400 font-bold hover:underline">
                        Halaman Login Siswa &rarr;
                    </a>
                </div>
            </div>
        </div>
    );
}
