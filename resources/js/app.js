import './bootstrap';
import React from 'react';
import { createRoot } from 'react-dom/client';
import LoginApp from './components/LoginApp';
import DashboardApp from './components/DashboardApp';

// Mount Login App
const loginRoot = document.getElementById('login-app');
if (loginRoot) {
    createRoot(loginRoot).render(<LoginApp />);
}

// Mount Dashboard App
const dashboardRoot = document.getElementById('dashboard-app');
if (dashboardRoot) {
    const statsData = window.__INITIAL_STATS__ || null;
    createRoot(dashboardRoot).render(<DashboardApp initialStats={statsData} />);
}

// Public App
const publicRoot = document.getElementById('app');
if (publicRoot) {
    function App() {
        const [website, setWebsite] = React.useState(null);
        const [posts, setPosts] = React.useState([]);

        React.useEffect(() => {
            fetch('/api/website')
                .then((res) => res.json())
                .then((data) => setWebsite(data))
                .catch(() => {});

            fetch('/api/posts')
                .then((res) => res.json())
                .then((data) => setPosts(data))
                .catch(() => {});
        }, []);

        const navItems = ['Profil', 'Berita', 'Kontak'];

        return (
            <div className="min-h-screen bg-[#f4f7f6] text-slate-800 font-sans">
                <header className="border-b border-emerald-100 bg-white/90 backdrop-blur sticky top-0 z-50">
                    <div className="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
                        <div>
                            <div className="text-xl font-bold text-emerald-700">{website?.name || 'MA Al Ikhlas'}</div>
                            <div className="text-sm text-slate-500">{website?.description || 'CMS Sekolah Digital'}</div>
                        </div>
                        <nav className="flex items-center gap-6 text-sm font-semibold text-slate-600">
                            {navItems.map((item) => (
                                <a key={item} href="#" className="hover:text-emerald-700 transition-colors">{item}</a>
                            ))}
                            <a href="/login" className="ml-2 px-4 py-2 rounded-full bg-emerald-700 text-white text-xs hover:bg-emerald-800 transition-colors shadow-md">
                                Masuk / Login
                            </a>
                        </nav>
                    </div>
                </header>

                <main className="mx-auto max-w-6xl px-6 py-10 space-y-10">
                    <section className="grid gap-6 rounded-[28px] bg-gradient-to-r from-emerald-800 via-emerald-700 to-emerald-600 p-8 sm:p-10 text-white shadow-xl lg:grid-cols-[1.2fr_0.8fr]">
                        <div>
                            <p className="text-xs uppercase tracking-[0.3em] text-emerald-200 font-bold">Selamat Datang</p>
                            <h1 className="mt-3 text-3xl sm:text-4xl font-extrabold leading-tight">Membangun Generasi Berakhlak dan Berprestasi</h1>
                            <p className="mt-4 max-w-xl text-emerald-100 text-sm leading-relaxed">Sekolah yang menumbuhkan karakter, kecerdasan, dan semangat belajar siswa dalam lingkungan yang aman dan inspiratif.</p>
                            <div className="mt-6 flex flex-wrap gap-3">
                                <a href="/profil-sekolah" className="rounded-full bg-white px-5 py-2.5 text-sm font-semibold text-emerald-800 hover:bg-emerald-50 transition-colors shadow-md">Profil Sekolah</a>
                                <a href="#berita" className="rounded-full border border-white/60 px-5 py-2.5 text-sm font-semibold text-white hover:bg-white/10 transition-colors">Lihat Berita</a>
                            </div>
                        </div>
                        <div className="rounded-[24px] bg-white/15 p-6 backdrop-blur border border-white/20">
                            <h2 className="text-lg font-bold">Informasi Sekolah</h2>
                            <div className="mt-4 space-y-2 text-xs text-emerald-50">
                                <p>{website?.alamat ? `📍 ${website.alamat}` : '📍 Alamat sekolah akan tampil di sini.'}</p>
                                <p>{website?.telepon ? `📞 ${website.telepon}` : ''}</p>
                                <p>{website?.email ? `✉️ ${website.email}` : ''}</p>
                            </div>
                        </div>
                    </section>

                    <section id="berita" className="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
                        <div className="rounded-[24px] bg-white p-8 shadow-sm border border-slate-200/80">
                            <div className="flex items-center justify-between">
                                <h2 className="text-xl font-bold text-slate-800">Berita Terbaru</h2>
                            </div>
                            <div className="mt-6 space-y-4">
                                {posts.length > 0 ? (
                                    posts.map((post) => (
                                        <div key={post.id} className="rounded-2xl border border-slate-200 p-5 hover:border-emerald-500 transition-colors">
                                            <span className="inline-block px-2.5 py-0.5 rounded-md bg-emerald-50 text-emerald-700 text-xs font-semibold">{post.category || 'Umum'}</span>
                                            <h3 className="mt-2 text-base font-bold text-slate-800">{post.judul}</h3>
                                            <p className="mt-2 text-xs text-slate-600 leading-relaxed">{post.isi}</p>
                                        </div>
                                    ))
                                ) : (
                                    <p className="text-xs text-slate-400">Belum ada berita yang diterbitkan.</p>
                                )}
                            </div>
                        </div>
                        <aside className="rounded-[24px] bg-emerald-900 p-8 text-white shadow-sm flex flex-col justify-between">
                            <div>
                                <h3 className="text-xl font-bold">Profil Singkat</h3>
                                <p className="mt-3 text-xs leading-relaxed text-emerald-100">
                                    MA Al Ikhlas adalah lembaga pendidikan yang menghadirkan pembelajaran yang menyenangkan, religius, dan penuh nilai karakter.
                                </p>
                            </div>
                            <a href="/profil-sekolah" className="mt-6 inline-block text-center rounded-full bg-white px-5 py-2.5 text-xs font-bold text-emerald-900 hover:bg-emerald-50 transition-colors shadow-md">
                                Selengkapnya &rarr;
                            </a>
                        </aside>
                    </section>
                </main>

                <footer className="border-t border-slate-200 bg-white px-6 py-6 text-center text-xs text-slate-500">
                    &copy; 2026 MA Al Ikhlas. Semua hak cipta dilindungi.
                </footer>
            </div>
        );
    }

    createRoot(publicRoot).render(<App />);
}
