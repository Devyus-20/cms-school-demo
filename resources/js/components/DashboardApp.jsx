import React, { useEffect, useState } from 'react';

export default function DashboardApp({ initialStats }) {
    const [user, setUser] = useState(window.__INITIAL_USER__ || null);
    const [website, setWebsite] = useState(window.__WEBSITE_SETTING__ || null);
    const [sidebarOpen, setSidebarOpen] = useState(true);
    const [mobileDrawerOpen, setMobileDrawerOpen] = useState(false);

    useEffect(() => {
        fetch('/api/me')
            .then(res => res.json())
            .then(data => {
                if (data.authenticated) {
                    setUser(data.user);
                }
            })
            .catch(() => { });

        fetch('/api/website')
            .then(res => res.json())
            .then(data => setWebsite(data))
            .catch(() => { });
    }, []);

    const handleLogout = async () => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        await fetch('/logout', {
            method: 'GET',
            headers: { 'X-CSRF-TOKEN': csrfToken }
        });
        window.location.href = '/login';
    };

    const stats = initialStats || { users: 1, roles: 3, permissions: 7, posts: 1 };

    const navSections = [
        {
            items: [
                { label: 'Dashboard', icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', href: '/dashboard', active: true }
            ]
        },
        {
            title: 'Akademik & Siswa',
            items: [
                { label: 'Data Siswa', icon: 'M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z', href: '/admin/siswa', requiredPermission: 'Kelola User' },
                { label: 'Presensi Siswa', icon: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', href: '/admin/presensi', requiredPermission: 'Kelola User' },
                { label: 'Tugas Siswa', icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', href: '/admin/tugas', requiredPermission: 'Kelola User' },
                { label: 'Rekap & Perankingan', icon: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', href: '/admin/rekap-akademik', requiredPermission: 'Kelola User' },
                { label: 'Ujian Online', icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', href: '/admin/exams', requiredPermission: 'Kelola Akademik' }
            ]
        },
        {
            title: 'Pusat Laporan',
            items: [
                { label: 'Pusat Laporan', icon: 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', href: '/admin/reports' },
                { label: 'Lap. Data Siswa', icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', href: '/admin/reports?type=siswa' },
                { label: 'Lap. Nilai & Ranking', icon: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', href: '/admin/reports?type=nilai' },
                { label: 'Lap. Presensi Siswa', icon: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', href: '/admin/reports?type=presensi' },
                { label: 'Lap. Hasil Ujian (CBT)', icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', href: '/admin/reports?type=ujian' },
                { label: 'Lap. Pendaftar PPDB', icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', href: '/admin/reports?type=ppdb' }
            ]
        },
        {
            title: 'Konten Website',
            items: [
                { label: 'Halaman Profil', icon: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', href: '/admin/pages', requiredPermission: 'Kelola Website' },
                { label: 'Galeri', icon: 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z', href: '/admin/galleries', requiredPermission: 'Kelola Website' },
                { label: 'Artikel', icon: 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z', href: '/admin/posts?tipe=artikel', requiredPermission: 'Tambah Berita' },
                { label: 'Berita', icon: 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z', href: '/admin/posts?tipe=berita', requiredPermission: 'Tambah Berita' },
                { label: 'Pengumuman', icon: 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z', href: '/admin/posts?tipe=pengumuman', requiredPermission: 'Tambah Berita' }
            ]
        },
        {
            title: 'PPDB & Pengaturan',
            items: [
                { label: 'Pendaftar PPDB', icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', href: '/admin/ppdb', requiredPermission: 'Kelola Settings', badge: 'pending_ppdb' },
                { label: 'Settings', icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z', href: '/admin/settings', requiredRole: 'Admin' }
            ]
        },
        {
            title: 'Taksonomi & Akses',
            items: [
                { label: 'Kategori', icon: 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z', href: '/admin/categories', requiredPermission: 'Kelola Website' },
                { label: 'Tag', icon: 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z', href: '/admin/tags', requiredPermission: 'Kelola Website' },
                { label: 'User', icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', href: '/admin/users', requiredPermission: 'Kelola User' },
                { label: 'Role', icon: 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', href: '/admin/roles', requiredPermission: 'Kelola User' },
                { label: 'Permission', icon: 'M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z', href: '/admin/permissions', requiredPermission: 'Kelola User' },
                { label: 'Activity Log', icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', href: '/admin/activity-logs', requiredPermission: 'Kelola User' }
            ]
        }
    ];

    const quickModules = [
        { label: 'Pusat Laporan & Cetak', desc: 'Rekapitulasi nilai, presensi, ujian & PPDB ber-KOP', icon: 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', color: 'bg-emerald-700 text-white', href: '/admin/reports' },
        { label: 'Ujian Online', desc: 'Kelola bank soal & jadwal ujian online', icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', color: 'bg-emerald-600 text-white', href: '/admin/exams', requiredPermission: 'Kelola Akademik' },
        { label: 'Pendaftar PPDB', desc: 'Verifikasi & kelola calon siswa', icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', color: 'bg-teal-700 text-white', href: '/admin/ppdb', requiredPermission: 'Kelola Settings' },
        { label: 'Halaman Profil', desc: 'Sejarah, Visi Misi, Fasilitas, dll', icon: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', color: 'bg-emerald-600 text-white', href: '/admin/pages', requiredPermission: 'Kelola Website' },
        { label: 'Galeri Foto', desc: 'Album & foto kegiatan sekolah', icon: 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z', color: 'bg-teal-600 text-white', href: '/admin/galleries', requiredPermission: 'Kelola Website' },
        { label: 'Artikel & Edukasi', desc: 'Karya tulis & opini siswa/guru', icon: 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z', color: 'bg-indigo-600 text-white', href: '/admin/posts?tipe=artikel', requiredPermission: 'Tambah Berita' },
        { label: 'Berita Sekolah', desc: 'Kabar & liputan kegiatan', icon: 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z', color: 'bg-blue-600 text-white', href: '/admin/posts?tipe=berita', requiredPermission: 'Tambah Berita' },
        { label: 'Pengumuman Resmi', desc: 'Informasi resmi & agenda', icon: 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z', color: 'bg-amber-600 text-white', href: '/admin/posts?tipe=pengumuman', requiredPermission: 'Tambah Berita' },
        { label: 'Settings & PPDB', desc: 'Informasi sekolah & formulir PPDB', icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z', color: 'bg-purple-600 text-white', href: '/admin/settings', requiredRole: 'Admin' },
        { label: 'User & Role', desc: 'Pengelola & hak akses akun', icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', color: 'bg-slate-700 text-white', href: '/admin/users', requiredPermission: 'Kelola User' },
        { label: 'Activity Log', desc: 'Catatan aktivitas & log pengguna', icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', color: 'bg-purple-600 text-white', href: '/admin/activity-logs', requiredPermission: 'Kelola User' },
        { label: 'Kategori & Tag', desc: 'Pengelompokan artikel & berita', icon: 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z', color: 'bg-orange-500 text-white', href: '/admin/categories', requiredPermission: 'Kelola Website' },
    ];

    const userPermissions = user?.permissions || [];
    const userRole = user?.role || '';
    const canAccess = (item) => {
        if (item.requiredRole && userRole.toLowerCase() !== item.requiredRole.toLowerCase()) {
            return false;
        }
        if (item.requiredPermission && !userPermissions.includes(item.requiredPermission)) {
            return false;
        }
        return true;
    };
    const visibleQuickModules = quickModules.filter(canAccess);

    const pendingPpdb = stats.pending_ppdb ?? user?.pending_ppdb ?? 0;

    return (
        <div className="h-screen bg-slate-100 flex font-sans overflow-hidden relative">
            {/* Mobile Backdrop */}
            {mobileDrawerOpen && (
                <div
                    onClick={() => setMobileDrawerOpen(false)}
                    className="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-30 md:hidden transition-opacity"
                />
            )}

            {/* Sidebar */}
            <aside className={`fixed md:sticky top-0 inset-y-0 left-0 ${mobileDrawerOpen ? 'translate-x-0 w-64' : '-translate-x-full md:translate-x-0'} ${sidebarOpen ? 'md:w-64' : 'md:w-20'} bg-slate-900 text-slate-300 transition-all duration-300 flex flex-col justify-between z-40 md:z-20 shrink-0 h-screen`}>
                <div className="flex flex-col overflow-y-auto flex-1 min-h-0">
                    <div className="h-16 flex items-center justify-between px-4 border-b border-slate-800 shrink-0">
                        {(sidebarOpen || mobileDrawerOpen) && (
                            <div className="flex items-center space-x-2">
                                {website?.logo ? (
                                    <img src={website.logo} alt="Logo" className="w-8 h-8 rounded-xl object-contain bg-white/10 p-0.5 shrink-0" />
                                ) : (
                                    <img src="/images/default-logo.png" alt="Logo Y-School" className="w-8 h-8 rounded-xl object-contain bg-white/10 p-0.5 shrink-0" />
                                )}
                                <div>
                                    <span className="font-bold text-white text-base tracking-wide block leading-none">{website?.name || 'MA Al Ikhlas'}</span>
                                    <span className="text-[10px] text-emerald-400 font-semibold uppercase tracking-wider">CMS Admin</span>
                                </div>
                            </div>
                        )}
                        <button
                            onClick={() => {
                                if (window.innerWidth < 768) {
                                    setMobileDrawerOpen(false);
                                } else {
                                    setSidebarOpen(!sidebarOpen);
                                }
                            }}
                            className="p-2 rounded-lg hover:bg-slate-800 text-slate-400 hover:text-white transition-colors shrink-0"
                        >
                            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>

                    <nav className="p-3 space-y-0.5">
                        {navSections.map((section, sIdx) => {
                            const visibleItems = section.items.filter(canAccess);
                            if (visibleItems.length === 0) return null;

                            return (
                                <React.Fragment key={sIdx}>
                                    {section.title && (sidebarOpen || mobileDrawerOpen) && (
                                        <p className="px-3 pt-4 pb-1 text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                                            {section.title}
                                        </p>
                                    )}
                                    {visibleItems.map((item, idx) => {
                                        const badgeCount = item.badge === 'pending_ppdb' ? pendingPpdb : 0;

                                        return (
                                            <a
                                                key={idx}
                                                href={item.href}
                                                className={`flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all ${item.active
                                                        ? 'bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-lg shadow-emerald-600/30'
                                                        : 'text-slate-400 hover:text-white hover:bg-slate-800/80'
                                                    }`}
                                            >
                                                <div className="flex items-center gap-3">
                                                    <svg className="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d={item.icon} />
                                                    </svg>
                                                    {(sidebarOpen || mobileDrawerOpen) && <span>{item.label}</span>}
                                                </div>
                                                {(sidebarOpen || mobileDrawerOpen) && badgeCount > 0 && (
                                                    <span className="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-500 text-slate-900 shrink-0">
                                                        {badgeCount}
                                                    </span>
                                                )}
                                            </a>
                                        );
                                    })}
                                </React.Fragment>
                            );
                        })}
                    </nav>
                </div>

                {/* Footer Logout Button */}
                <div className="p-3 border-t border-slate-800">
                    <button
                        onClick={handleLogout}
                        className="w-full flex items-center space-x-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-colors"
                    >
                        <svg className="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        {(sidebarOpen || mobileDrawerOpen) && <span>Logout</span>}
                    </button>
                </div>
            </aside>

            {/* Main Content Area */}
            <div className="flex-1 flex flex-col min-w-0 overflow-hidden">
                {/* Navbar Top */}
                <header className="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-3 sm:px-6 z-10 shrink-0 gap-2">
                    <div className="flex items-center min-w-0 gap-2">
                        <button
                            onClick={() => setMobileDrawerOpen(true)}
                            className="md:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 shrink-0"
                            aria-label="Buka Menu"
                        >
                            <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <h1 className="text-base sm:text-lg md:text-xl font-bold text-slate-800 truncate leading-tight">Dashboard Administrator</h1>
                    </div>

                    <div className="flex items-center gap-2 sm:gap-3 shrink-0">
                        {canAccess({ requiredPermission: 'Kelola User' }) && (
                            <a
                                href="/admin/activity-logs"
                                title="Activity Log"
                                className="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 sm:px-3.5 py-2 rounded-xl bg-purple-50 text-purple-700 hover:bg-purple-100 transition-colors border border-purple-200"
                            >
                                <svg className="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span className="hidden sm:inline">Activity Log</span>
                            </a>
                        )}
                        <a
                            href="/ujian"
                            target="_blank"
                            title="Ujian Online"
                            className="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 sm:px-3.5 py-2 rounded-xl bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors border border-blue-200"
                        >
                            <svg className="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span className="hidden sm:inline">Ujian Online</span>
                        </a>
                        <a
                            href="/"
                            target="_blank"
                            title="Lihat Website"
                            className="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 sm:px-3.5 py-2 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition-colors border border-emerald-200"
                        >
                            <svg className="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                            <span className="hidden sm:inline">Lihat Website</span>
                        </a>

                        <div className="flex items-center gap-2 sm:gap-3 border-l border-slate-200 pl-2 sm:pl-4">
                            <div className="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-gradient-to-tr from-emerald-700 to-teal-500 text-white flex items-center justify-center font-bold text-xs sm:text-sm shadow-md shrink-0">
                                {user?.name ? user.name[0] : 'A'}
                            </div>
                            <div className="hidden sm:block">
                                <div className="text-xs sm:text-sm font-semibold text-slate-800 leading-none">{user?.name || 'Administrator'}</div>
                                <div className="text-[11px] text-slate-500 capitalize mt-0.5">{user?.role || 'Super Admin'}</div>
                            </div>
                        </div>
                    </div>
                </header>

                {/* Dashboard Body */}
                <main className="flex-1 overflow-y-auto p-4 sm:p-6 space-y-6">
                    {/* Welcome Banner */}
                    <div className="rounded-[5px] bg-gradient-to-r from-emerald-800 via-emerald-700 to-teal-700 p-5 sm:p-8 text-white shadow-xl relative overflow-hidden">
                        <div className="relative z-10 max-w-2xl">
                            <span className="inline-block px-3 py-1 bg-white/20 backdrop-blur rounded-full text-xs font-semibold uppercase tracking-wider mb-3">
                                CMS Sekolah Digital - MA Al Ikhlas
                            </span>
                            <h2 className="text-xl sm:text-2xl md:text-3xl font-extrabold tracking-tight leading-tight break-words">
                                Selamat Datang, {user?.name || 'Administrator'}!
                            </h2>
                            <p className="mt-2 text-emerald-100 text-xs sm:text-sm leading-relaxed">
                                Sistem CMS sekolah siap digunakan. Kelola konten profil, galeri foto, artikel, berita, pengumuman, serta pendaftaran siswa baru (PPDB).
                            </p>
                        </div>
                        <div className="absolute right-0 top-0 bottom-0 w-1/3 bg-white/5 skew-x-12 pointer-events-none"></div>
                    </div>

                    {/* Stats Grid */}
                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
                        <div className="bg-white p-4 sm:p-5 rounded-[5px] border border-slate-200 shadow-sm flex items-center space-x-4">
                            <div className="w-10 h-10 sm:w-12 sm:h-12 rounded-[5px] bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                <svg className="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <div>
                                <div className="text-xs text-slate-500 font-medium">Total User</div>
                                <div className="text-xl sm:text-2xl font-bold text-slate-800">{stats.users}</div>
                            </div>
                        </div>

                        <div className="bg-white p-4 sm:p-5 rounded-[5px] border border-slate-200 shadow-sm flex items-center space-x-4">
                            <div className="w-10 h-10 sm:w-12 sm:h-12 rounded-[5px] bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                                <svg className="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <div>
                                <div className="text-xs text-slate-500 font-medium">Role Sistem</div>
                                <div className="text-xl sm:text-2xl font-bold text-slate-800">{stats.roles}</div>
                            </div>
                        </div>

                        <div className="bg-white p-4 sm:p-5 rounded-[5px] border border-slate-200 shadow-sm flex items-center space-x-4">
                            <div className="w-10 h-10 sm:w-12 sm:h-12 rounded-[5px] bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                                <svg className="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                            </div>
                            <div>
                                <div className="text-xs text-slate-500 font-medium">Permissions</div>
                                <div className="text-xl sm:text-2xl font-bold text-slate-800">{stats.permissions}</div>
                            </div>
                        </div>

                        <div className="bg-white p-4 sm:p-5 rounded-[5px] border border-slate-200 shadow-sm flex items-center space-x-4">
                            <div className="w-10 h-10 sm:w-12 sm:h-12 rounded-[5px] bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                                <svg className="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                </svg>
                            </div>
                            <div>
                                <div className="text-xs text-slate-500 font-medium">Post & Berita</div>
                                <div className="text-xl sm:text-2xl font-bold text-slate-800">{stats.posts}</div>
                            </div>
                        </div>
                    </div>

                    {/* Quick Access Modules Grid */}
                    <div className="bg-white p-5 sm:p-6 rounded-[5px] border border-slate-200 shadow-sm">
                        <div className="flex items-center justify-between mb-5">
                            <div>
                                <h3 className="text-base sm:text-lg font-bold text-slate-800">Modul Fitur & Navigasi Utama</h3>
                                <p className="text-xs text-slate-500">Pilih modul di bawah ini untuk mengelola data website</p>
                            </div>
                        </div>
                        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                            {visibleQuickModules.map((mod, idx) => (
                                <a
                                    key={idx}
                                    href={mod.href}
                                    className="p-4 rounded-[5px] border border-slate-200/80 hover:border-emerald-500 bg-slate-50/40 hover:bg-emerald-50/30 transition-all group flex flex-col justify-between space-y-3"
                                >
                                    <div className="flex items-center space-x-3">
                                        <div className={`w-10 h-10 rounded-[5px] ${mod.color} flex items-center justify-center shrink-0 shadow-md group-hover:scale-105 transition-transform`}>
                                            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d={mod.icon} />
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 className="text-sm font-bold text-slate-800 group-hover:text-emerald-700 transition-colors leading-tight">{mod.label}</h4>
                                            <p className="text-[11px] text-slate-500 leading-tight mt-0.5">{mod.desc}</p>
                                        </div>
                                    </div>
                                    <div className="flex items-center justify-end text-xs font-semibold text-emerald-600 group-hover:translate-x-1 transition-transform">
                                        <span>Buka</span>
                                        <svg className="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                </a>
                            ))}
                        </div>
                    </div>
                </main>
            </div>
        </div>
    );
}