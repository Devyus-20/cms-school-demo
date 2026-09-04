import './bootstrap';
import React, { useState, useEffect } from 'react';
import { createRoot } from 'react-dom/client';
import LoginApp from './components/LoginApp';
import LoginSiswaApp from './components/LoginSiswaApp';
import LoginAdminApp from './components/LoginAdminApp';
import ForgotPasswordApp from './components/ForgotPasswordApp';
import ResetPasswordApp from './components/ResetPasswordApp';
import RegisterSiswaApp from './components/RegisterSiswaApp';
import DashboardApp from './components/DashboardApp';

// Mount Register Siswa App
const registerSiswaRoot = document.getElementById('register-siswa-app');
if (registerSiswaRoot) {
    createRoot(registerSiswaRoot).render(<RegisterSiswaApp />);
}

// Mount Login Siswa App
const loginSiswaRoot = document.getElementById('login-siswa-app');
if (loginSiswaRoot) {
    createRoot(loginSiswaRoot).render(<LoginSiswaApp />);
}

// Mount Login Admin App
const loginAdminRoot = document.getElementById('login-admin-app');
if (loginAdminRoot) {
    createRoot(loginAdminRoot).render(<LoginAdminApp />);
}

// Mount Login App (fallback)
const loginRoot = document.getElementById('login-app');
if (loginRoot) {
    createRoot(loginRoot).render(<LoginApp />);
}

// Mount Forgot Password App
const forgotRoot = document.getElementById('forgot-password-app');
if (forgotRoot) {
    createRoot(forgotRoot).render(<ForgotPasswordApp />);
}

// Mount Reset Password App
const resetRoot = document.getElementById('reset-password-app');
if (resetRoot) {
    const token = resetRoot.dataset.token || '';
    const email = resetRoot.dataset.email || '';
    createRoot(resetRoot).render(<ResetPasswordApp initialToken={token} initialEmail={email} />);
}

// Mount Dashboard App
const dashboardRoot = document.getElementById('dashboard-app');
if (dashboardRoot) {
    const statsData = window.__INITIAL_STATS__ || null;
    createRoot(dashboardRoot).render(<DashboardApp initialStats={statsData} />);
}

// Public Homepage React Component
const publicRoot = document.getElementById('app');
if (publicRoot) {
    function App() {
        const [website, setWebsite] = useState(window.__WEBSITE_SETTING__ || null);
        const [posts, setPosts] = useState([]);
        const [pages, setPages] = useState(window.__INITIAL_PAGES__ || []);
        const [allPages, setAllPages] = useState(window.__INITIAL_ALL_PAGES__ || window.__INITIAL_PAGES__ || []);
        const [profilDropdown, setProfilDropdown] = useState(false);
        const [infoDropdown, setInfoDropdown] = useState(false);
        const [mobileMenu, setMobileMenu] = useState(false);
        const [heroSlideIndex, setHeroSlideIndex] = useState(0);
        const [activePrestasiPhoto, setActivePrestasiPhoto] = useState(0);

        const heroImages = React.useMemo(() => {
            if (website?.hero_bgs && Array.isArray(website.hero_bgs) && website.hero_bgs.length > 0) {
                return website.hero_bgs;
            }
            if (website?.hero_bg) {
                return [website.hero_bg];
            }
            return [];
        }, [website]);

        useEffect(() => {
            if (heroImages.length <= 1) return;
            const interval = setInterval(() => {
                setHeroSlideIndex((prevIndex) => (prevIndex + 1) % heroImages.length);
            }, 5000);
            return () => clearInterval(interval);
        }, [heroImages]);

        useEffect(() => {
            fetch('/api/website')
                .then((res) => res.json())
                .then((data) => setWebsite(data))
                .catch(() => { });

            fetch('/api/posts')
                .then((res) => res.json())
                .then((data) => setPosts(data))
                .catch(() => { });

            fetch('/api/pages')
                .then((res) => res.json())
                .then((data) => {
                    if (Array.isArray(data)) {
                        setPages(data);
                        setAllPages(data);
                    } else if (data && typeof data === 'object') {
                        if (data.grouped) setPages(data.grouped);
                        if (data.all) setAllPages(data.all);
                    }
                })
                .catch(() => { });
        }, []);

        const profilItems = pages.length > 0
            ? pages.map((p) => ({ label: p.judul, href: `/profil/${p.slug}` }))
            : [
                { label: 'Sejarah', href: '/profil/sejarah' },
                { label: 'Visi dan Misi', href: '/profil/visi-dan-misi' },
                { label: 'Prestasi', href: '/profil/prestasi' },
                { label: 'Ekstrakurikuler', href: '/profil/ekstrakurikuler' },
                { label: 'Guru dan Staff', href: '/profil/guru-dan-staff' },
                { label: 'Fasilitas', href: '/profil/fasilitas' },
            ];

        const informasiItems = [
            { label: 'Artikel', href: '/informasi/artikel' },
            { label: 'Berita', href: '/informasi/berita' },
            { label: 'Pengumuman', href: '/informasi/pengumuman' },
            { label: 'Galeri', href: '/galeri' },
        ];

        const parseMapInput = (mapsInput, schoolName, schoolAlamat) => {
            const fallbackQuery = [schoolName, schoolAlamat].filter(Boolean).join(' ').trim() || 'MA Al Ikhlas';
            
            if (!mapsInput || !mapsInput.trim()) {
                return {
                    embedSrc: `https://maps.google.com/maps?q=${encodeURIComponent(fallbackQuery)}&t=&z=16&ie=UTF8&iwloc=&output=embed`,
                    directionsUrl: `https://www.google.com/maps/dir/?api=1&destination=${encodeURIComponent(fallbackQuery)}`
                };
            }

            let input = mapsInput.trim();

            // 1. Jika berupa kode <iframe src="...">
            if (input.includes('<iframe')) {
                const srcMatch = input.match(/src=["']([^"']+)["']/i);
                if (srcMatch && srcMatch[1]) {
                    input = srcMatch[1];
                }
            }

            // 2. Deteksi Koordinat murni: "-6.200000, 106.816666"
            const coordMatch = input.match(/^([+-]?\d+\.\d+)\s*,\s*([+-]?\d+\.\d+)$/);
            if (coordMatch) {
                const lat = coordMatch[1];
                const lng = coordMatch[2];
                return {
                    embedSrc: `https://maps.google.com/maps?q=${lat},${lng}&t=&z=16&ie=UTF8&iwloc=&output=embed`,
                    directionsUrl: `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`
                };
            }

            // 3. Deteksi Koordinat dari URL Google Maps (@lat,lng)
            const atCoordMatch = input.match(/@([+-]?\d+\.\d+),([+-]?\d+\.\d+)/);
            if (atCoordMatch) {
                const lat = atCoordMatch[1];
                const lng = atCoordMatch[2];
                return {
                    embedSrc: input.includes('/maps/embed') 
                        ? input 
                        : `https://maps.google.com/maps?q=${lat},${lng}&t=&z=16&ie=UTF8&iwloc=&output=embed`,
                    directionsUrl: `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`
                };
            }

            // 4. Deteksi Koordinat dari URL Embed (!3dLat!4dLng)
            const pbLatMatch = input.match(/!3d([+-]?\d+\.\d+)/);
            const pbLngMatch = input.match(/!(?:2d|4d)([+-]?\d+\.\d+)/);
            if (pbLatMatch && pbLngMatch) {
                const lat = pbLatMatch[1];
                const lng = pbLngMatch[1];
                return {
                    embedSrc: input.includes('/maps/embed') || input.includes('output=embed')
                        ? input
                        : `https://maps.google.com/maps?q=${lat},${lng}&t=&z=16&ie=UTF8&iwloc=&output=embed`,
                    directionsUrl: `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`
                };
            }

            // 5. Parameter q= pada URL Google Maps
            const qMatch = input.match(/[?&]q=([^&]+)/);
            if (qMatch) {
                const qVal = decodeURIComponent(qMatch[1]);
                return {
                    embedSrc: `https://maps.google.com/maps?q=${encodeURIComponent(qVal)}&t=&z=16&ie=UTF8&iwloc=&output=embed`,
                    directionsUrl: `https://www.google.com/maps/dir/?api=1&destination=${encodeURIComponent(qVal)}`
                };
            }

            // 6. Direct Embed URL
            if (input.includes('/maps/embed') || input.includes('output=embed')) {
                return {
                    embedSrc: input,
                    directionsUrl: `https://www.google.com/maps/dir/?api=1&destination=${encodeURIComponent(fallbackQuery)}`
                };
            }

            // 7. General fallback (Teks alamat atau Link Share)
            return {
                embedSrc: `https://maps.google.com/maps?q=${encodeURIComponent(input)}&t=&z=16&ie=UTF8&iwloc=&output=embed`,
                directionsUrl: input.startsWith('http://') || input.startsWith('https://') ? input : `https://www.google.com/maps/dir/?api=1&destination=${encodeURIComponent(input)}`
            };
        };

        const [activeFaq, setActiveFaq] = useState(0);

        const faqItems = React.useMemo(() => {
            if (website?.info_faq_list && Array.isArray(website.info_faq_list) && website.info_faq_list.length > 0) {
                return website.info_faq_list;
            }
            return [
                {
                    q: "Bagaimana cara mendaftar peserta didik baru (PPDB) di MA Al Ikhlas?",
                    a: "Pendaftaran PPDB dapat dilakukan secara online 24 jam melalui portal website ini (/ppdb) atau secara offline di Sekretariat Panitia PPDB sekolah. Syarat pendaftaran meliputi: 1) Fotokopi Ijazah/SKL SMP/MTs, 2) Akta Kelahiran & Kartu Keluarga (KK), 3) Pas foto 3x4 (3 lembar), dan 4) Mengisi formulir pendaftaran secara lengkap. Nomor pendaftaran resmi akan terbit otomatis setelah formulir berhasil dikirim."
                },
                {
                    q: "Apakah tersedia program beasiswa untuk calon siswa?",
                    a: "Ya, MA Al Ikhlas menyediakan 3 program beasiswa utama: 1) Beasiswa Prestasi Akademik bagi peringkat 1-3 sekolah asal/kejuaraan OSN, 2) Beasiswa Hafidz Al-Qur'an (Bebas Biaya Pendidikan untuk minimal 3 Juz), dan 3) Beasiswa Bantuan Pendidikan PIP/KKS/PKH bagi calon siswa kurang mampu yang berprestasi."
                },
                {
                    q: "Bagaimana sistem Ujian Online (CBT) di MA Al Ikhlas?",
                    a: "Sistem Ujian Online CBT (Computer Based Test) MA Al Ikhlas terintegrasi dengan Portal Akun Siswa Digital. Siswa dapat mengikuti Ujian Harian (UH), UTS, UAS, dan Asesmen Madrasah dari perangkat laptop/smartphone secara praktis dengan proteksi kecurangan digital, alokasi waktu otomatis, dan rekapitulasi nilai yang transparan."
                },
                {
                    q: "Apa saja kegiatan ekstrakurikuler unggulan yang tersedia?",
                    a: "Ekstrakurikuler unggulan meliputi: 1) Keagamaan: Tahfidz Al-Qur'an, Hadroh/Seni Qasidah, & Kajian Kitab Kuning; 2) Kepemimpinan: Pramuka Bantara, Paskibra, PMR, & OPMIS; 3) Olahraga: Futsal, Basket, Voli, & Pencak Silat; 4) Teknologi & Seni: Jurnalistik Digital, Multimedia Podcast, Karya Ilmiah Remaja (KIR), & English Club."
                }
            ];
        }, [website]);

        return (
            <div className="min-h-screen bg-[#f8fafc] text-slate-800 font-sans flex flex-col justify-between selection:bg-amber-400 selection:text-slate-950">
                {/* 1. TOPBAR HEADER */}
                <div className="bg-[#0f172a] text-slate-300 text-xs py-2.5 px-4 sm:px-8 border-b border-slate-800">
                    <div className="mx-auto max-w-7xl flex flex-wrap justify-between items-center gap-3">
                        <div className="flex items-center gap-6">
                            {website?.telepon && (
                                <a href={`tel:${website.telepon}`} className="flex items-center gap-2 hover:text-amber-400 transition-colors">
                                    <svg className="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1.01 1.01 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    <span className="font-medium">{website.telepon}</span>
                                </a>
                            )}
                            {website?.email && (
                                <a href={`mailto:${website.email}`} className="flex items-center gap-2 hover:text-amber-400 transition-colors hidden sm:flex">
                                    <svg className="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    <span className="font-medium">{website.email}</span>
                                </a>
                            )}
                        </div>

                        <div className="flex items-center gap-4 text-slate-300">
                            <div className="flex items-center gap-3 pr-4 border-r border-slate-800 hidden md:flex">
                                <span className="text-[11px] font-semibold text-slate-400">Media Sosial:</span>
                                {website?.facebook && (
                                    <a href={website.facebook} target="_blank" rel="noopener noreferrer" className="hover:text-amber-400 transition-colors">
                                        <svg className="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                    </a>
                                )}
                                {website?.instagram && (
                                    <a href={website.instagram} target="_blank" rel="noopener noreferrer" className="hover:text-amber-400 transition-colors">
                                        <svg className="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                    </a>
                                )}
                                {website?.youtube && (
                                    <a href={website.youtube} target="_blank" rel="noopener noreferrer" className="hover:text-amber-400 transition-colors">
                                        <svg className="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                    </a>
                                )}
                            </div>

                            <a href="/login/siswa" className="px-3.5 py-1.5 rounded-lg bg-amber-500/20 text-amber-300 border border-amber-400/30 text-[11px] font-bold hover:bg-amber-500 hover:text-slate-950 transition-all flex items-center gap-1.5">
                                <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                                <span>Portal Siswa</span>
                            </a>
                        </div>
                    </div>
                </div>

                {/* 2. MAIN STICKY NAVBAR */}
                <header className="border-b border-slate-800 bg-[#0f172a]/95 backdrop-blur sticky top-0 z-50 text-white shadow-lg">
                    <div className="mx-auto flex max-w-7xl items-center justify-between px-4 sm:px-8 py-3.5">
                        {/* Brand Logo */}
                        <a href="/" className="flex items-center gap-3.5 group">
                            {website?.logo ? (
                                <img src={website.logo} alt="Logo" className="w-10 h-10 object-contain group-hover:scale-105 transition-transform" />
                            ) : (
                                <img src="/images/default-logo.png" alt="Logo Y-School" className="w-10 h-10 object-contain group-hover:scale-105 transition-transform" />
                            )}
                            <div>
                                <div className="text-lg font-bold tracking-tight text-white leading-none group-hover:text-amber-400 transition-colors">
                                    {website?.name || 'MA Al Ikhlas'}
                                </div>
                                <div className="text-[11px] font-medium text-slate-400 mt-1 uppercase tracking-wider">
                                    {website?.description || 'Sekolah Digital Terpadu'}
                                </div>
                            </div>
                        </a>

                        {/* Navigation Links */}
                        <nav className="hidden lg:flex items-center gap-1 text-xs uppercase font-bold tracking-wider text-slate-300">
                            <a href="/" className="px-4 py-2.5 rounded-xl text-amber-400 bg-slate-800/80 border border-slate-700">
                                Beranda
                            </a>

                            {/* Profil Dropdown */}
                            <div className="relative" onMouseEnter={() => setProfilDropdown(true)} onMouseLeave={() => setProfilDropdown(false)}>
                                <button className="flex items-center gap-1.5 px-4 py-2.5 rounded-xl hover:bg-slate-800 hover:text-white transition-colors cursor-pointer">
                                    <span>Profil</span>
                                    <svg className={`w-3.5 h-3.5 transition-transform ${profilDropdown ? 'rotate-180 text-amber-400' : 'text-slate-500'}`} fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                 {profilDropdown && (
                                    <div className="absolute top-full left-0 w-56 pt-2 z-50">
                                        <div className="bg-[#0f172a] rounded-[5px] border border-slate-700 shadow-2xl p-2 space-y-1">
                                            {profilItems.map((item) => (
                                                <a key={item.label} href={item.href} className="block px-3.5 py-2.5 rounded-[5px] text-xs font-semibold text-slate-300 hover:bg-amber-500 hover:text-slate-950 transition-colors">
                                                    {item.label}
                                                </a>
                                            ))}
                                        </div>
                                    </div>
                                )}
                            </div>

                            {/* Informasi Dropdown */}
                            <div className="relative" onMouseEnter={() => setInfoDropdown(true)} onMouseLeave={() => setInfoDropdown(false)}>
                                <button className="flex items-center gap-1.5 px-4 py-2.5 rounded-[5px] hover:bg-slate-800 hover:text-white transition-colors cursor-pointer">
                                    <span>Informasi</span>
                                    <svg className={`w-3.5 h-3.5 transition-transform ${infoDropdown ? 'rotate-180 text-amber-400' : 'text-slate-500'}`} fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                {infoDropdown && (
                                    <div className="absolute top-full left-0 w-48 pt-2 z-50">
                                        <div className="bg-[#0f172a] rounded-[5px] border border-slate-700 shadow-2xl p-2 space-y-1">
                                            {informasiItems.map((item) => (
                                                <a key={item.label} href={item.href} className="block px-3.5 py-2.5 rounded-[5px] text-xs font-semibold text-slate-300 hover:bg-amber-500 hover:text-slate-950 transition-colors">
                                                    {item.label}
                                                </a>
                                            ))}
                                        </div>
                                    </div>
                                )}
                            </div>

                            <a href="/ppdb" className="px-4 py-2.5 rounded-xl text-amber-400 bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/30 flex items-center gap-2">
                                <span>PPDB</span>
                            </a>

                            <a href="/register" className="px-4 py-2.5 rounded-xl hover:bg-slate-800 hover:text-white transition-colors">
                                Registrasi
                            </a>

                            <a href="/login/siswa" className="ml-3 px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs transition-all shadow-md flex items-center gap-2 hover:scale-105 active:scale-95">
                                <span>PORTAL SISWA</span>
                                <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </nav>

                        {/* Mobile Menu Button */}
                        <button onClick={() => setMobileMenu(!mobileMenu)} className="lg:hidden p-2.5 rounded-xl bg-slate-800 text-slate-300 hover:text-white">
                            <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>

                    {/* Mobile Drawer */}
                    {mobileMenu && (
                        <div className="lg:hidden border-t border-slate-800 bg-[#0f172a] px-6 py-5 space-y-4">
                            <a href="/" className="block py-1.5 font-bold text-amber-400">Beranda</a>
                            
                            <div className="space-y-1">
                                <div className="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Profil Sekolah</div>
                                {profilItems.map((item) => (
                                    <a key={item.label} href={item.href} className="block pl-3 py-1.5 text-xs font-semibold text-slate-300 hover:text-amber-400">
                                        {item.label}
                                    </a>
                                ))}
                            </div>

                            <div className="space-y-1 pt-2">
                                <div className="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Informasi</div>
                                {informasiItems.map((item) => (
                                    <a key={item.label} href={item.href} className="block pl-3 py-1.5 text-xs font-semibold text-slate-300 hover:text-amber-400">
                                        {item.label}
                                    </a>
                                ))}
                            </div>

                            <div className="pt-3 flex flex-col gap-2.5">
                                <a href="/register" className="block text-center py-2.5 rounded-xl bg-slate-800 text-slate-200 text-xs font-bold">
                                    Registrasi Akun Siswa
                                </a>
                                <a href="/login/siswa" className="block text-center py-2.5 rounded-xl bg-amber-500 text-slate-950 text-xs font-bold">
                                    Login Portal Siswa &rarr;
                                </a>
                                <a href="/ppdb" className="block text-center py-2.5 rounded-xl bg-emerald-600 text-white text-xs font-bold">
                                    Pendaftaran PPDB Online
                                </a>
                            </div>
                        </div>
                    )}
                </header>

                {/* 3. HERO SLIDER SECTION */}
                <section className="bg-[#0f172a] text-white relative overflow-hidden py-16 sm:py-24 px-4 sm:px-8 border-b border-slate-800 min-h-[500px] flex items-center">
                    {/* Background Slider Images */}
                    {heroImages.length > 0 && heroImages.map((imgUrl, idx) => (
                        <div 
                            key={idx} 
                            className={`absolute inset-0 transition-opacity duration-1000 ease-in-out ${idx === heroSlideIndex ? 'opacity-100 z-0' : 'opacity-0 z-0 pointer-events-none'}`}
                        >
                            <img src={imgUrl} alt={`Slide ${idx + 1}`} className="w-full h-full object-cover" />
                            <div className="absolute inset-0 bg-gradient-to-r from-[#0f172a]/50 via-[#0f172a]/15 to-transparent"></div>
                        </div>
                    ))}

                    <div className="mx-auto max-w-7xl w-full relative z-10">
                        <div className="max-w-3xl space-y-6">
                            <div className="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-slate-800/90 border border-slate-700 text-amber-400 text-xs font-semibold uppercase tracking-wider backdrop-blur">
                                <span>PENDAFTARAN PESERTA DIDIK BARU</span>
                            </div>
                            <h1 className="text-3xl sm:text-5xl lg:text-6xl font-extrabold leading-[1.15] tracking-tight">
                                {website?.hero_title ? (
                                    website.hero_title
                                ) : (
                                    <>Membangun Generasi <span className="text-amber-400">Berakhlak Mulia</span> & Berprestasi Unggul</>
                                )}
                            </h1>
                            <p className="text-slate-300 text-sm sm:text-base leading-relaxed max-w-xl font-normal">
                                {website?.hero_subtitle || 'Pendidikan sains & keagamaan berkualitas tinggi dengan bimbingan dewan guru profesional serta sarana ujian online CBT digital terpadu.'}
                            </p>

                            <div className="pt-2 flex flex-wrap gap-4 items-center">
                                <a 
                                    href={website?.hero_btn1_link || '/ppdb'} 
                                    className="rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 px-7 py-3.5 text-xs font-bold uppercase tracking-wider transition-all shadow-lg hover:scale-105 active:scale-95 flex items-center gap-2"
                                >
                                    <span>{website?.hero_btn1_text || 'Daftar PPDB Sekarang'}</span>
                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>

                                <a 
                                    href={website?.hero_btn2_link || '/login/siswa'} 
                                    className="rounded-xl bg-slate-800/90 hover:bg-slate-800 border border-slate-700 px-6 py-3.5 text-xs font-bold text-white transition-all backdrop-blur flex items-center gap-2 hover:scale-105 active:scale-95"
                                >
                                    <span className="w-2 h-2 rounded-full bg-emerald-400"></span>
                                    <span>{website?.hero_btn2_text || 'Portal Siswa & CBT'}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </section>

                {/* 4. 3-FEATURE QUICK BAR BELOW HERO */}
                <section className="mx-auto max-w-7xl px-4 sm:px-8 -mt-8 relative z-20">
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div className="bg-white p-6 rounded-[5px] border border-slate-200 shadow-lg flex items-center gap-4 hover:border-amber-400 transition-all">
                            <div className="w-12 h-12 rounded-[5px] bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 shadow-sm">
                                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                            <div>
                                <h4 className="font-bold text-slate-900 text-base">Kurikulum Terpadu</h4>
                                <p className="text-xs text-slate-500 mt-0.5 leading-relaxed">Kombinasi sains modern & pendalaman agama Islam.</p>
                            </div>
                        </div>

                        <div className="bg-white p-6 rounded-[5px] border border-slate-200 shadow-lg flex items-center gap-4 hover:border-emerald-400 transition-all">
                            <div className="w-12 h-12 rounded-[5px] bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 shadow-sm">
                                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <div>
                                <h4 className="font-bold text-slate-900 text-base">Pengajar Profesional</h4>
                                <p className="text-xs text-slate-500 mt-0.5 leading-relaxed">Bimbingan dewan guru & ustadz berkualifikasi.</p>
                            </div>
                        </div>

                        <div className="bg-white p-6 rounded-[5px] border border-slate-200 shadow-lg flex items-center gap-4 hover:border-blue-400 transition-all">
                            <div className="w-12 h-12 rounded-[5px] bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 shadow-sm">
                                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <h4 className="font-bold text-slate-900 text-base">Fasilitas Ujian CBT</h4>
                                <p className="text-xs text-slate-500 mt-0.5 leading-relaxed">Ujian online digital, perpus online, & lab komputer.</p>
                            </div>
                        </div>
                    </div>
                </section>

                {/* MAIN CONTENT CONTAINER */}
                <main className="mx-auto max-w-7xl px-4 sm:px-8 py-16 space-y-16 flex-1 w-full">

                    {/* 5. SEKSI SEJARAH SEKOLAH (DINAMIS DARI DATA HALAMAN PROFIL DI ADMIN PANEL) */}
                    {(() => {
                        const sejarahPage = pages.find((p) => p.slug === 'sejarah') || null;
                        const sejarahImg = sejarahPage?.gambar || '/storage/pages/RHx6CdwjGb88FJx5dntugpEJeZQNZASFqetfZuKi.jpg';
                        const sejarahTitle = sejarahPage?.judul || 'Sejarah Sekolah';
                        const sejarahKonten = sejarahPage?.konten;

                        return (
                            <section className="bg-white rounded-[5px] border border-slate-200 p-8 sm:p-12 shadow-sm">
                                <div className="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                                    {/* Visual / Gambar Sejarah */}
                                    <div className="lg:col-span-5 relative">
                                        <div className="relative rounded-[5px] overflow-hidden border border-slate-200 shadow-lg bg-slate-100 group">
                                            <img 
                                                src={sejarahImg} 
                                                alt={sejarahTitle} 
                                                className="w-full h-72 sm:h-96 object-cover group-hover:scale-105 transition-transform duration-500"
                                                onError={(e) => {
                                                    e.target.onerror = null;
                                                    e.target.src = '/images/default-logo.png';
                                                }}
                                            />
                                            <div className="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent flex items-end p-6">
                                                <div className="text-white">
                                                    <span className="px-3 py-1 rounded-full bg-amber-500 text-slate-950 text-[10px] font-black uppercase tracking-wider shadow">
                                                        SEJARAH & KILAS BALIK
                                                    </span>
                                                    <h3 className="font-bold text-lg text-white mt-2 leading-tight">
                                                        {website?.name || 'MA Al Ikhlas'}
                                                    </h3>
                                                    <p className="text-xs text-slate-300 mt-0.5">
                                                        Dedikasi & Perjalanan Membangun Generasi Unggul
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {/* Teks Penjelasan Sejarah */}
                                    <div className="lg:col-span-7 space-y-5">
                                        <div className="space-y-1.5">
                                            <span className="text-xs font-bold uppercase tracking-wider text-amber-600 block">
                                                TENTANG KAMI
                                            </span>
                                            <h2 className="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-tight">
                                                {sejarahTitle}
                                            </h2>
                                        </div>

                                        {sejarahKonten && sejarahKonten.trim() !== '' ? (
                                            <div 
                                                className="text-xs sm:text-sm text-slate-600 leading-relaxed space-y-3 prose prose-slate max-w-none line-clamp-6 text-justify"
                                                dangerouslySetInnerHTML={{ __html: sejarahKonten }}
                                            />
                                        ) : (
                                            <p className="text-xs sm:text-sm text-slate-600 leading-relaxed text-justify">
                                                {website?.name || 'MA Al Ikhlas'} didirikan dengan tekad mulia untuk mencetak generasi berkarakter Islami, berprestasi akademik unggul, serta berdaya saing global melalui penguasaan sains dan teknologi digital terpadu.
                                            </p>
                                        )}

                                        {/* Fitur Nilai Utama Sekolah */}
                                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                                            <div className="flex items-center gap-2.5 p-3 rounded-[5px] bg-slate-50 border border-slate-100">
                                                <div className="w-8 h-8 rounded-[5px] bg-amber-500/10 text-amber-600 flex items-center justify-center shrink-0">
                                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M5 13l4 4L19 7"/></svg>
                                                </div>
                                                <span className="text-xs font-bold text-slate-700">Pondasi Karakter & Akhlak Mulia</span>
                                            </div>
                                            <div className="flex items-center gap-2.5 p-3 rounded-[5px] bg-slate-50 border border-slate-100">
                                                <div className="w-8 h-8 rounded-[5px] bg-emerald-500/10 text-emerald-600 flex items-center justify-center shrink-0">
                                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M5 13l4 4L19 7"/></svg>
                                                </div>
                                                <span className="text-xs font-bold text-slate-700">Teknologi Pembelajaran Modern</span>
                                            </div>
                                        </div>

                                        <div className="pt-2">
                                            <a 
                                                href="/profil/sejarah" 
                                                className="inline-flex items-center gap-2 px-6 py-3 rounded-[5px] bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-xs uppercase tracking-wider transition-all shadow-md hover:scale-105 active:scale-95"
                                            >
                                                <span>Baca Selengkapnya Sejarah</span>
                                                <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        );
                    })()}

                    {/* SEKSI PRESTASI (DINAMIS DARI HALAMAN PROFIL DI ADMIN PANEL DENGAN MULTI-FOTO) */}
                    {(() => {
                        const prestasiPage = pages.find((p) => p.slug === 'prestasi') || null;
                        if (!prestasiPage) return null;

                        const imagesList = (() => {
                            if (prestasiPage.gambar_list && Array.isArray(prestasiPage.gambar_list) && prestasiPage.gambar_list.length > 0) {
                                return prestasiPage.gambar_list;
                            }
                            const g = prestasiPage.gambar;
                            if (!g) return ["/images/prestasi_siswa.jpg"];
                            if (Array.isArray(g)) return g;
                            if (typeof g === 'string' && g.startsWith('[')) {
                                try {
                                    const parsed = JSON.parse(g);
                                    if (Array.isArray(parsed) && parsed.length > 0) return parsed;
                                } catch (e) {}
                            }
                            return [g];
                        })();

                        const currentImg = imagesList[activePrestasiPhoto % imagesList.length] || imagesList[0] || "/images/prestasi_siswa.jpg";

                        return (
                            <section className="bg-white rounded-[5px] border border-slate-200 p-8 sm:p-14 shadow-sm space-y-8">
                                <div className="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-center">
                                    {/* KIRI: Foto Siswa Berprestasi dengan Bentuk Lingkaran & Switcher Multi-Foto */}
                                    <div className="lg:col-span-5 flex flex-col justify-center items-center">
                                        <div className="relative w-64 h-64 sm:w-80 sm:h-80 flex items-center justify-center">
                                            {/* Aksen Latar Belakang Lembut (Tanpa Gradasi) */}
                                            <div className="absolute -top-3 -right-3 w-56 h-56 sm:w-64 sm:h-64 rounded-full bg-sky-100/90 pointer-events-none"></div>
                                            <div className="absolute -bottom-3 -left-3 w-52 h-52 sm:w-60 sm:h-60 rounded-full bg-slate-100 pointer-events-none"></div>
                                            <div className="absolute bottom-1 -right-1 w-44 h-44 sm:w-52 sm:h-52 rounded-full bg-sky-200/70 pointer-events-none"></div>
                                            
                                            {/* Foto Bulat Siswa Berprestasi */}
                                            <div className="relative z-10 w-56 h-56 sm:w-72 sm:h-72 rounded-full overflow-hidden border-4 border-white shadow-xl bg-slate-100 group">
                                                <img 
                                                    src={currentImg} 
                                                    alt={prestasiPage.judul || "Prestasi"} 
                                                    className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                                    onError={(e) => {
                                                        e.target.onerror = null;
                                                        e.target.src = "/images/prestasi_siswa.jpg";
                                                    }}
                                                />
                                            </div>
                                        </div>

                                        {/* Thumbnail Switcher Jika Memiliki Lebih Dari 1 Foto */}
                                        {imagesList.length > 1 && (
                                            <div className="mt-4 flex items-center justify-center gap-3 flex-wrap z-20">
                                                {imagesList.map((imgUrl, idx) => (
                                                    <button 
                                                        key={idx} 
                                                        type="button" 
                                                        onClick={() => setActivePrestasiPhoto(idx)}
                                                        className={`w-12 h-12 sm:w-14 sm:h-14 rounded-full overflow-hidden border-2 border-white shadow-md transition-all cursor-pointer hover:opacity-100 ${idx === (activePrestasiPhoto % imagesList.length) ? 'ring-2 ring-[#0284c7] scale-110 opacity-100' : 'opacity-70'}`}
                                                        title={`Lihat Foto ${idx + 1}`}
                                                    >
                                                        <img src={imgUrl} alt={`Thumbnail ${idx + 1}`} className="w-full h-full object-cover" />
                                                    </button>
                                                ))}
                                            </div>
                                        )}
                                    </div>

                                    {/* KANAN: Judul Prestasi & Penjelasan Lengkap Prestasi */}
                                    <div className="lg:col-span-7 space-y-6">
                                        <div className="space-y-2">
                                            <h2 className="text-4xl sm:text-5xl font-extrabold text-[#0284c7] tracking-tight">
                                                {prestasiPage.judul || 'Prestasi'}
                                            </h2>
                                        </div>

                                        {prestasiPage.konten && prestasiPage.konten.trim() !== '' ? (
                                            <div 
                                                className="space-y-3.5 text-slate-600 text-xs sm:text-sm leading-relaxed font-normal text-justify prose prose-slate max-w-none"
                                                dangerouslySetInnerHTML={{ __html: prestasiPage.konten }}
                                            />
                                        ) : null}

                                        {/* Galeri Foto Tambahan Jika Lebih Dari 1 Foto */}
                                        {imagesList.length > 1 && (
                                            <div className="pt-4 border-t border-slate-100">
                                                <div className="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Dokumentasi Foto Prestasi:</div>
                                                <div className="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                                    {imagesList.map((imgUrl, idx) => (
                                                        <div 
                                                            key={idx}
                                                            onClick={() => setActivePrestasiPhoto(idx)}
                                                            className={`h-28 rounded-[5px] overflow-hidden border cursor-pointer group transition-all relative ${idx === (activePrestasiPhoto % imagesList.length) ? 'border-[#0284c7] ring-2 ring-[#0284c7]/30' : 'border-slate-200 hover:border-[#0284c7]'}`}
                                                        >
                                                            <img src={imgUrl} alt={`Foto ${idx + 1}`} className="w-full h-full object-cover group-hover:scale-105 transition-transform" />
                                                            <div className="absolute inset-0 bg-slate-900/10 group-hover:bg-transparent transition-colors"></div>
                                                        </div>
                                                    ))}
                                                </div>
                                            </div>
                                        )}

                                        {/* Tombol Aksi */}
                                        <div className="pt-2 flex flex-wrap items-center justify-between gap-4 border-t border-slate-100">
                                            <a 
                                                href="/profil/prestasi" 
                                                className="inline-flex items-center gap-2 px-5 py-2.5 rounded-[5px] bg-[#0284c7] hover:bg-[#0369a1] text-white font-bold text-xs transition-all shadow-sm hover:shadow"
                                            >
                                                <span>Lihat Seluruh Halaman Prestasi</span>
                                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        );
                    })()}

                    {/* 6. SEKSI EKSTRAKURIKULER (DISESUAIKAN PENUH DENGAN INPUTAN DI ADMIN PANEL) */}
                    {(() => {
                        const ekstraList = allPages.filter((p) => p.base_slug === 'ekstrakurikuler' || (p.slug && p.slug.startsWith('ekstrakurikuler')));
                        const mainEkstra = pages.find((p) => p.slug === 'ekstrakurikuler') || (ekstraList.length > 0 ? ekstraList[0] : null);

                        const sectionTitle = mainEkstra?.judul || 'Ekstrakurikuler Sekolah';
                        const sectionKonten = mainEkstra?.konten;
                        const sectionImages = mainEkstra?.gambar_list || (mainEkstra?.gambar ? [mainEkstra.gambar] : []);

                        // Helper untuk membersihkan tag HTML agar teks ringkasan rapi
                        const cleanHtmlText = (html) => {
                            if (!html) return '';
                            return html.replace(/<[^>]*>?/gm, '').trim();
                        };

                        return (
                            <section className="space-y-8">
                                <div className="flex flex-col sm:flex-row sm:items-end justify-between gap-4 border-b border-slate-200 pb-4">
                                    <div>
                                        <span className="text-xs font-bold uppercase tracking-wider text-amber-600 block mb-1">
                                            PENGEMBANGAN MINAT & BAKAT
                                        </span>
                                        <h2 className="text-2xl sm:text-3xl font-extrabold text-slate-900">
                                            {sectionTitle}
                                        </h2>
                                        <p className="text-xs sm:text-sm text-slate-500 mt-1">
                                            Wadah aktualisasi diri santri dalam bidang keagamaan, kepemimpinan, olahraga, seni, dan teknologi digital.
                                        </p>
                                    </div>
                                    <a 
                                        href="/profil/ekstrakurikuler" 
                                        className="text-xs font-bold text-amber-600 hover:text-amber-700 flex items-center gap-1.5 shrink-0 group"
                                    >
                                        <span>Lihat Seluruh Ekstrakurikuler</span>
                                        <svg className="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </a>
                                </div>

                                {/* Penjelasan Utama dari Admin Jika Diisi */}
                                {sectionKonten && sectionKonten.trim() !== '' && (
                                    <div className="bg-white p-6 sm:p-8 rounded-[5px] border border-slate-200 shadow-sm flex flex-col md:flex-row gap-6 items-center">
                                        {sectionImages.length > 0 && (
                                            <div className="w-full md:w-1/3 shrink-0 h-48 rounded-[5px] overflow-hidden bg-slate-100 border border-slate-200 relative group">
                                                <img 
                                                    src={sectionImages[0]} 
                                                    alt={sectionTitle} 
                                                    className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                                    onError={(e) => {
                                                        e.target.onerror = null;
                                                        e.target.src = '/images/default-logo.png';
                                                    }}
                                                />
                                            </div>
                                        )}
                                        <div className="space-y-3 flex-1">
                                            <div 
                                                className="text-xs sm:text-sm text-slate-600 leading-relaxed prose prose-slate max-w-none text-justify"
                                                dangerouslySetInnerHTML={{ __html: sectionKonten }}
                                            />
                                            <div className="pt-2">
                                                <a 
                                                    href="/profil/ekstrakurikuler" 
                                                    className="inline-flex items-center gap-1.5 text-xs font-bold text-amber-600 hover:text-amber-700"
                                                >
                                                    <span>Kunjungi Halaman Detail Ekstrakurikuler &rarr;</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                )}

                                {/* Daftar Kartu Ekstrakurikuler Dinamis Dari Admin Panel */}
                                {ekstraList.length > 0 ? (
                                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                        {ekstraList.map((item, idx) => {
                                            const itemImgs = item.gambar_list && item.gambar_list.length > 0 ? item.gambar_list : (item.gambar ? [item.gambar] : []);
                                            const mainImg = itemImgs.length > 0 ? itemImgs[0] : null;
                                            const cleanText = cleanHtmlText(item.konten);

                                            return (
                                                <a 
                                                    key={item.id || idx} 
                                                    href={`/profil/detail/${item.slug || item.id}`}
                                                    className="bg-white rounded-[5px] border border-slate-200 overflow-hidden shadow-sm hover:border-amber-400 hover:shadow-xl transition-all duration-300 flex flex-col justify-between group"
                                                >
                                                    <div>
                                                        {mainImg ? (
                                                            <div className="h-48 bg-slate-100 overflow-hidden relative border-b border-slate-100">
                                                                <img 
                                                                    src={mainImg} 
                                                                    alt={item.judul} 
                                                                    className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                                                    onError={(e) => {
                                                                        e.target.onerror = null;
                                                                        e.target.src = '/images/default-logo.png';
                                                                    }}
                                                                />
                                                                <span className="absolute top-3 left-3 px-2.5 py-1 rounded-full bg-amber-500 text-slate-950 text-[10px] font-black uppercase tracking-wider shadow">
                                                                    Ekstrakurikuler
                                                                </span>
                                                                {itemImgs.length > 1 && (
                                                                    <span className="absolute bottom-3 right-3 px-2 py-0.5 rounded-[5px] bg-slate-900/80 text-white text-[10px] font-bold backdrop-blur">
                                                                        📷 {itemImgs.length} Foto
                                                                    </span>
                                                                )}
                                                            </div>
                                                        ) : (
                                                            <div className="h-36 bg-slate-50 flex items-center justify-center text-slate-300 border-b border-slate-100 relative">
                                                                <span className="absolute top-3 left-3 px-2.5 py-1 rounded-full bg-amber-500 text-slate-950 text-[10px] font-black uppercase tracking-wider shadow">
                                                                    Ekstrakurikuler
                                                                </span>
                                                                <svg className="w-10 h-10 text-amber-500/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                            </div>
                                                        )}

                                                        <div className="p-6 space-y-2">
                                                            <h3 className="font-extrabold text-slate-900 text-base group-hover:text-amber-600 transition-colors line-clamp-2">
                                                                {item.judul}
                                                            </h3>
                                                            <p className="text-xs text-slate-500 leading-relaxed line-clamp-3">
                                                                {cleanText || 'Klik baca selengkapnya untuk melihat informasi dan dokumentasi kegiatan ekstrakurikuler ini...'}
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div className="px-6 py-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400 font-medium">
                                                        <span>{website?.name || 'MA Al Ikhlas'}</span>
                                                        <span className="text-amber-600 font-bold group-hover:translate-x-1 transition-transform flex items-center gap-1">
                                                            <span>Baca Selengkapnya</span>
                                                            <span>&rarr;</span>
                                                        </span>
                                                    </div>
                                                </a>
                                            );
                                        })}
                                    </div>
                                ) : (
                                    <div className="bg-white rounded-[5px] border border-slate-200 p-12 text-center text-slate-400 text-sm">
                                        Belum ada data ekstrakurikuler yang ditambahkan di panel admin.
                                    </div>
                                )}
                            </section>
                        );
                    })()}

                    {/* 7. NEWS & ANNOUNCEMENT GRID */}
                    <section id="berita" className="space-y-8">
                        <div className="flex flex-col sm:flex-row sm:items-end justify-between gap-4 border-b border-slate-200 pb-4">
                            <div>
                                <span className="text-xs font-bold uppercase tracking-wider text-amber-600 block mb-1">BERITA & INFORMASI</span>
                                <h2 className="text-2xl sm:text-3xl font-bold text-slate-900">Kabar Terbaru Sekolah</h2>
                            </div>
                            <a href="/informasi/berita" className="text-xs font-bold text-amber-600 hover:text-amber-700 flex items-center gap-1.5 shrink-0">
                                <span>Lihat Seluruh Berita</span>
                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                            {posts.length > 0 ? (
                                posts.slice(0, 3).map((post) => (
                                    <a key={post.id} href={`/berita/${post.slug}`} className="bg-white rounded-[5px] border border-slate-200 overflow-hidden shadow-sm hover:shadow-xl hover:border-amber-400 transition-all duration-300 flex flex-col justify-between group">
                                        <div>
                                            {post.thumbnail && (
                                                <div className="h-44 bg-slate-100 overflow-hidden relative">
                                                    <img src={post.thumbnail} alt={post.judul} className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                                                    <span className="absolute top-3 left-3 px-2.5 py-1 rounded-[5px] bg-amber-500 text-slate-950 text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                                        {post.tipe || 'Berita'}
                                                    </span>
                                                </div>
                                            )}
                                            <div className="p-5 space-y-2">
                                                <h3 className="font-bold text-slate-900 text-base group-hover:text-amber-600 transition-colors line-clamp-2">{post.judul}</h3>
                                                <p className="text-xs text-slate-500 leading-relaxed line-clamp-3">{post.isi}</p>
                                            </div>
                                        </div>

                                        <div className="px-5 py-3.5 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                                            <span className="font-semibold text-emerald-700">Baca Selengkapnya &rarr;</span>
                                        </div>
                                    </a>
                                ))
                            ) : (
                                <div className="col-span-3 text-center py-12 text-slate-400 text-xs">Belum ada berita yang diterbitkan.</div>
                            )}
                        </div>
                    </section>

                    {/* 8. FAQ ACCORDION SECTION */}
                    <section className="bg-white p-8 sm:p-10 rounded-[5px] border border-slate-200 shadow-sm grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
                        <div className="lg:col-span-5 space-y-3">
                            <span className="text-xs font-bold uppercase tracking-wider text-amber-600 block">PERTANYAAN POPULER</span>
                            <h2 className="text-2xl sm:text-3xl font-bold text-slate-900 leading-tight">
                                Informasi Seputar Pendaftaran & Pembelajaran
                            </h2>
                            <p className="text-xs text-slate-500 leading-relaxed">
                                Temukan jawaban atas pertanyaan mengenai syarat pendaftaran PPDB, portal ujian CBT, serta program kegiatan sekolah.
                            </p>
                        </div>

                        <div className="lg:col-span-7 space-y-3">
                            {faqItems.map((item, idx) => (
                                <div key={idx} className="border border-slate-200 rounded-[5px] overflow-hidden">
                                    <button 
                                        onClick={() => setActiveFaq(activeFaq === idx ? null : idx)}
                                        className="w-full text-left p-4 bg-slate-50 hover:bg-slate-100 text-slate-900 font-semibold text-xs sm:text-sm flex justify-between items-center gap-4 transition-colors cursor-pointer"
                                    >
                                        <span>{item.q}</span>
                                        <span className="text-amber-600 font-bold text-base">{activeFaq === idx ? '−' : '+'}</span>
                                    </button>
                                    {activeFaq === idx && (
                                        <div className="p-4 bg-white text-xs text-slate-600 leading-relaxed border-t border-slate-100">
                                            {item.a}
                                        </div>
                                    )}
                                </div>
                            ))}
                        </div>
                    </section>

                    {/* 9. GOOGLE MAPS SECTION */}
                    {(() => {
                        const mapData = parseMapInput(website?.google_maps, website?.name, website?.alamat);
                        return (
                            <section className="rounded-[5px] bg-white p-8 border border-slate-200 shadow-sm space-y-6">
                                <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                                    <div>
                                        <span className="text-xs font-bold text-amber-600 uppercase tracking-wider block mb-1">LOKASI SEKOLAH</span>
                                        <h2 className="text-2xl font-bold text-slate-900">Peta Kampus MA Al Ikhlas</h2>
                                        <p className="text-xs text-slate-500 mt-1">{website?.alamat || 'Lokasi Kampus Sekolah'}</p>
                                    </div>
                                    <a href={mapData.directionsUrl} target="_blank" rel="noopener noreferrer" className="px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 rounded-[5px] text-xs font-bold transition-all shadow-sm flex items-center gap-2 self-start sm:self-center shrink-0">
                                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        <span>Petunjuk Arah Google Maps</span>
                                    </a>
                                </div>

                                <div className="w-full h-80 sm:h-96 rounded-[5px] overflow-hidden border border-slate-200 bg-slate-100 relative shadow-inner">
                                    <iframe
                                        src={mapData.embedSrc}
                                        width="100%"
                                        height="100%"
                                        style={{ border: 0 }}
                                        allowFullScreen=""
                                        loading="lazy"
                                        referrerPolicy="no-referrer-when-downgrade"
                                        title="Peta Lokasi Sekolah"
                                        className="w-full h-full"
                                    ></iframe>
                                </div>
                            </section>
                        );
                    })()}
                </main>

                {/* 10. FOOTER */}
                <footer className="bg-[#0f172a] text-slate-300 border-t border-slate-800 mt-auto">
                    <div className="mx-auto max-w-7xl px-6 py-12 grid grid-cols-1 md:grid-cols-4 gap-8 text-xs">
                        <div className="space-y-4 md:col-span-1">
                            <div className="flex items-center gap-3">
                                {website?.logo ? (
                                    <img src={website.logo} alt="Logo" className="w-10 h-10 object-contain" />
                                ) : (
                                    <img src="/images/default-logo.png" alt="Logo Y-School" className="w-10 h-10 object-contain" />
                                )}
                                <div>
                                    <div className="text-base font-bold text-white">{website?.name || 'MA Al Ikhlas'}</div>
                                    <div className="text-[10px] text-amber-400 uppercase tracking-wider font-semibold">Sekolah Digital Terpadu</div>
                                </div>
                            </div>
                            <p className="text-slate-400 leading-relaxed font-normal">
                                {website?.description || 'Membangun generasi penerus yang berakhlak mulia, berprestasi unggul, dan menguasai teknologi digital.'}
                            </p>

                            {/* Social Media Links */}
                            <div className="pt-2 flex items-center gap-3">
                                {website?.facebook && (
                                    <a href={website.facebook} target="_blank" rel="noopener noreferrer" title="Facebook" className="w-9 h-9 rounded-xl bg-slate-800 hover:bg-amber-500 hover:text-slate-950 text-slate-300 flex items-center justify-center transition-colors">
                                        <svg className="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                    </a>
                                )}
                                {website?.instagram && (
                                    <a href={website.instagram} target="_blank" rel="noopener noreferrer" title="Instagram" className="w-9 h-9 rounded-xl bg-slate-800 hover:bg-amber-500 hover:text-slate-950 text-slate-300 flex items-center justify-center transition-colors">
                                        <svg className="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                    </a>
                                )}
                                {website?.youtube && (
                                    <a href={website.youtube} target="_blank" rel="noopener noreferrer" title="YouTube" className="w-9 h-9 rounded-xl bg-slate-800 hover:bg-amber-500 hover:text-slate-950 text-slate-300 flex items-center justify-center transition-colors">
                                        <svg className="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                    </a>
                                )}
                            </div>
                        </div>

                        {/* Navigasi Utama */}
                        <div className="space-y-3">
                            <h3 className="text-sm font-bold text-white uppercase tracking-wider">Navigasi Utama</h3>
                            <ul className="space-y-2 text-xs text-slate-400 font-medium">
                                <li><a href="/" className="hover:text-amber-400 transition-colors">Beranda</a></li>
                                <li><a href="/profil/sejarah" className="hover:text-amber-400 transition-colors">Profil Sekolah</a></li>
                                <li><a href="/informasi/berita" className="hover:text-amber-400 transition-colors">Berita & Informasi</a></li>
                                <li><a href="/ppdb" className="hover:text-amber-400 transition-colors">PPDB Online</a></li>
                            </ul>
                        </div>

                        {/* Profil Sekolah */}
                        <div className="space-y-3">
                            <h3 className="text-sm font-bold text-white uppercase tracking-wider">Profil Sekolah</h3>
                            <ul className="space-y-2 text-xs text-slate-400 font-medium">
                                <li><a href="/profil/sejarah" className="hover:text-amber-400 transition-colors">Sejarah</a></li>
                                <li><a href="/profil/visi-dan-misi" className="hover:text-amber-400 transition-colors">Visi & Misi</a></li>
                                <li><a href="/profil/prestasi" className="hover:text-amber-400 transition-colors">Prestasi Siswa</a></li>
                                <li><a href="/profil/ekstrakurikuler" className="hover:text-amber-400 transition-colors">Ekstrakurikuler</a></li>
                                <li><a href="/profil/fasilitas" className="hover:text-amber-400 transition-colors">Fasilitas</a></li>
                            </ul>
                        </div>

                        {/* Informasi Kontak Column */}
                        <div className="space-y-3">
                            <h3 className="text-sm font-bold text-white uppercase tracking-wider">Informasi Kontak</h3>
                            <ul className="space-y-2.5 text-xs text-slate-300">
                                {website?.alamat && (
                                    <li className="flex items-start gap-2.5">
                                        <svg className="w-4 h-4 text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        <span>{website.alamat}</span>
                                    </li>
                                )}
                                {website?.telepon && (
                                    <li className="flex items-center gap-2.5">
                                        <svg className="w-4 h-4 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1.01 1.01 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                        <a href={`tel:${website.telepon}`} className="hover:text-amber-400 transition-colors">{website.telepon}</a>
                                    </li>
                                )}
                                {website?.email && (
                                    <li className="flex items-center gap-2.5">
                                        <svg className="w-4 h-4 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                        <a href={`mailto:${website.email}`} className="hover:text-amber-400 transition-colors">{website.email}</a>
                                    </li>
                                )}
                            </ul>
                        </div>
                    </div>

                    {/* Bottom Copyright Bar */}
                    <div className="border-t border-slate-800 bg-[#090d16] px-6 py-4 text-center text-xs text-slate-500 font-medium">
                        <p>{website?.footer || `© 2026 ${website?.name || 'MA Al Ikhlas'}. Semua Hak Cipta Dilindungi.`}</p>
                    </div>
                </footer>
            </div>
        );
    }

    createRoot(publicRoot).render(<App />);
}