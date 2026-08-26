@extends('layouts.app')

@section('title', 'Profil & Histori Pesanan — ' . $user->name . ' | JuangDev')
@section('meta_description', 'Kelola informasi profil, ganti password, dan pantau seluruh riwayat pesanan serta progres pengerjaan proyek website Anda di JuangDev.')

@section('content')
<div class="min-h-screen bg-[#F8F9FA] text-slate-800 pt-28 pb-16" x-data="customerDashboardComponent()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Flash Message Alerts -->
        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4 text-xs sm:text-sm font-semibold flex items-center gap-3 shadow-xs">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl p-4 text-xs sm:text-sm font-semibold space-y-1 shadow-xs">
                @foreach($errors->all() as $error)
                    <div class="flex items-center gap-2">
                        <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 shrink-0"></i>
                        <span>{{ $error }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Main 2-Column Grid (Left: Profile & Forms, Right: Recent Orders) -->
        <div class="grid grid-cols-1 lg:grid-cols-[360px_1fr] gap-8 items-start">
            
            <!-- ====================================================
                 LEFT COLUMN: Profile Info + Edit Profile + Change Password
                 ==================================================== -->
            <div class="space-y-6">
                
                <!-- 1. Customer Profile Information Card -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm space-y-5">
                    <!-- Name, Email, & Customer Badge -->
                    <div class="space-y-1 text-left">
                        <h2 class="text-xl font-bold text-slate-900 tracking-tight">{{ $user->name }}</h2>
                        <p class="text-xs text-slate-500 font-medium truncate">{{ $user->email }}</p>
                        <div class="pt-2">
                            <span class="inline-block px-3.5 py-1 rounded-full bg-black text-white text-[10px] font-bold uppercase tracking-wider">
                                Customer
                            </span>
                        </div>
                    </div>

                    <!-- Profile Meta Details -->
                    <div class="space-y-3 text-left text-xs font-semibold text-slate-700 divide-y divide-slate-100 pt-1">
                        <div class="flex items-center justify-between pt-3 text-slate-800">
                            <div class="flex items-center gap-2.5">
                                <i data-lucide="shopping-bag" class="w-4 h-4 text-slate-400"></i>
                                <span>Histori Pesanan</span>
                            </div>
                            <span class="text-[11px] font-bold bg-slate-100 px-2 py-0.5 rounded-full text-slate-700">{{ $orders->count() }}</span>
                        </div>

                        <div class="flex items-center justify-between pt-3 text-slate-800">
                            <div class="flex items-center gap-2.5">
                                <i data-lucide="phone" class="w-4 h-4 text-slate-400"></i>
                                <span>Phone</span>
                            </div>
                            <span class="text-slate-500 text-xs font-medium">{{ $user->phone ?? '-' }}</span>
                        </div>

                        <div class="flex items-center justify-between pt-3 text-slate-800">
                            <div class="flex items-center gap-2.5">
                                <i data-lucide="calendar" class="w-4 h-4 text-slate-400"></i>
                                <span>Joined</span>
                            </div>
                            <span class="text-slate-500 text-xs font-medium">{{ $user->created_at->format('d M Y') }}</span>
                        </div>
                    </div>

                    <!-- Logout Button -->
                    <div class="pt-3 border-t border-slate-100">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button 
                                type="submit" 
                                class="w-full flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl border border-rose-200 hover:border-rose-300 bg-rose-50/40 hover:bg-rose-50 text-rose-600 text-xs font-bold transition-all cursor-pointer"
                            >
                                <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- 2. Edit Profile Card (Left Side Under Profile Info) -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm space-y-5">
                    <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3">
                        <i data-lucide="edit-3" class="w-4 h-4 text-slate-800"></i>
                        <h3 class="text-base font-bold text-slate-900 tracking-tight">Edit Profile</h3>
                    </div>

                    <form action="{{ route('customer.profile.update') }}" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Full Name</label>
                            <input 
                                type="text" 
                                name="name" 
                                value="{{ old('name', $user->name) }}" 
                                required 
                                placeholder="Nama Lengkap"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-medium focus:bg-white focus:outline-none focus:border-black transition-all"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Email</label>
                            <input 
                                type="email" 
                                value="{{ $user->email }}" 
                                disabled 
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-100 text-xs font-medium text-slate-500 cursor-not-allowed"
                            >
                            <p class="text-[10px] text-slate-400 mt-1">Email cannot be changed</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Phone Number</label>
                            <input 
                                type="tel" 
                                name="phone" 
                                value="{{ old('phone', $user->phone) }}" 
                                required 
                                placeholder="08xxxxxxxxxx"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-medium focus:bg-white focus:outline-none focus:border-black transition-all"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Address / Catatan (Opsional)</label>
                            <textarea 
                                name="address" 
                                rows="2" 
                                placeholder="Alamat atau domisili bisnis..."
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-medium focus:bg-white focus:outline-none focus:border-black transition-all resize-none"
                            ></textarea>
                        </div>

                        <div class="pt-1">
                            <button 
                                type="submit" 
                                class="w-full py-2.5 px-4 rounded-xl bg-black hover:bg-slate-800 text-white text-xs font-bold shadow-xs active:scale-[0.98] transition-all flex items-center justify-center gap-2 cursor-pointer"
                            >
                                <i data-lucide="save" class="w-3.5 h-3.5"></i>
                                <span>Save Changes</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- 3. Change Password Card (Left Side Under Edit Profile) -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm space-y-5">
                    <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3">
                        <i data-lucide="lock" class="w-4 h-4 text-slate-800"></i>
                        <h3 class="text-base font-bold text-slate-900 tracking-tight">Change Password</h3>
                    </div>

                    <form action="{{ route('customer.profile.update') }}" method="POST" class="space-y-3.5">
                        @csrf
                        <input type="hidden" name="name" value="{{ $user->name }}">
                        <input type="hidden" name="phone" value="{{ $user->phone ?? '08123456789' }}">

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Current Password</label>
                            <input 
                                type="password" 
                                name="current_password" 
                                placeholder="••••••••"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-medium focus:bg-white focus:outline-none focus:border-black transition-all"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">New Password</label>
                            <input 
                                type="password" 
                                name="new_password" 
                                placeholder="••••••••"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-medium focus:bg-white focus:outline-none focus:border-black transition-all"
                            >
                            <p class="text-[10px] text-slate-400 mt-0.5">Minimum 6 characters</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Confirm New Password</label>
                            <input 
                                type="password" 
                                name="new_password_confirmation" 
                                placeholder="••••••••"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-medium focus:bg-white focus:outline-none focus:border-black transition-all"
                            >
                        </div>

                        <div class="pt-1">
                            <button 
                                type="submit" 
                                class="w-full py-2.5 px-4 rounded-xl bg-black hover:bg-slate-800 text-white text-xs font-bold shadow-xs active:scale-[0.98] transition-all flex items-center justify-center gap-2 cursor-pointer"
                            >
                                <i data-lucide="key" class="w-3.5 h-3.5"></i>
                                <span>Update Password</span>
                            </button>
                        </div>
                    </form>
                </div>

            </div>

            <!-- ====================================================
                 RIGHT COLUMN: Recent Orders with Date Filter & Pagination
                 ==================================================== -->
            <div class="bg-white rounded-3xl p-7 border border-slate-200/90 shadow-sm space-y-6">
                
                <!-- Header with Title and Date Filter -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-100 pb-5">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-900 flex items-center justify-center">
                            <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 tracking-tight">Recent Orders</h3>
                            <p class="text-xs text-slate-500 font-medium">Daftar pesanan &amp; tagihan proyek digital Anda</p>
                        </div>
                    </div>

                    <!-- Date Filter Dropdown -->
                    <div class="flex items-center gap-2 shrink-0">
                        <label class="text-xs font-bold text-slate-500 flex items-center gap-1.5">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                            <span>Filter:</span>
                        </label>
                        <select 
                            x-model="dateFilter" 
                            @change="currentPage = 1"
                            class="px-3 py-1.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-slate-700 focus:outline-none focus:border-black cursor-pointer"
                        >
                            <option value="all">Semua Waktu</option>
                            <option value="7_days">7 Hari Terakhir</option>
                            <option value="30_days">30 Hari Terakhir</option>
                            <option value="this_month">Bulan Ini</option>
                        </select>
                    </div>
                </div>

                <!-- Orders List Container -->
                <div class="space-y-4">
                    @if($orders->isEmpty())
                        <div class="py-12 text-center space-y-3">
                            <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                                <i data-lucide="inbox" class="w-7 h-7"></i>
                            </div>
                            <p class="text-sm font-bold text-slate-800">Belum Ada Riwayat Pesanan</p>
                            <p class="text-xs text-slate-400 font-medium max-w-sm mx-auto">Gunakan Estimator Biaya untuk membuat pesanan website &amp; aplikasi impian Anda.</p>
                            <div class="pt-2">
                                <a href="{{ route('estimator') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-black text-white text-xs font-bold hover:bg-slate-800 transition-all">
                                    <span>Buat Pesanan Baru</span>
                                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                                </a>
                            </div>
                        </div>
                    @else
                        <!-- Order Item Card (Gambar 3 Style) -->
                        <template x-for="order in paginatedOrders" :key="order.id">
                            <div class="bg-white rounded-2xl border border-slate-200/90 p-5 sm:p-6 shadow-2xs hover:border-slate-300 transition-all space-y-4">
                                
                                <!-- Top Row: Order Number, Date & Status Badge -->
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-black text-slate-900 text-sm sm:text-base tracking-tight" x-text="order.invoice_number"></p>
                                        <p class="text-xs text-slate-400 font-medium mt-0.5" x-text="order.formatted_date"></p>
                                    </div>

                                    <!-- Status Badge (Gambar 3 Style Light Pill) -->
                                    <div>
                                        <template x-if="order.payment_status === 'fully_paid'">
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                                Selesai
                                            </span>
                                        </template>
                                        <template x-if="order.payment_status === 'dp_paid'">
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                                DP 50% Paid
                                            </span>
                                        </template>
                                        <template x-if="order.payment_status === 'unpaid'">
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800">
                                                Belum Bayar
                                            </span>
                                        </template>
                                    </div>
                                </div>

                                <!-- Middle Row: Service Item Description & Price -->
                                <div class="flex items-center justify-between text-xs sm:text-sm font-medium text-slate-700 pt-1">
                                    <p class="truncate max-w-[70%]" x-text="(order.project_name || order.service_name) + ' (' + (order.package_name || 'Kustom') + ')'"></p>
                                    <p class="font-bold text-slate-900 shrink-0" x-text="order.formatted_total"></p>
                                </div>

                                <!-- Bottom Row: Total & "View Details" Button (Gambar 3 & 5 Navigation) -->
                                <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                                    <div>
                                        <p class="text-[11px] text-slate-400 font-medium">Total</p>
                                        <p class="text-sm font-black text-slate-900" x-text="order.formatted_total"></p>
                                    </div>

                                    <a 
                                        :href="'/customer/orders/' + order.invoice_number"
                                        class="px-4 py-2 rounded-xl border border-slate-200 hover:border-slate-300 bg-white hover:bg-slate-50 text-slate-800 text-xs font-bold transition-all shadow-2xs cursor-pointer active:scale-95 inline-flex items-center gap-1.5"
                                    >
                                        <span>View Details</span>
                                        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                                    </a>
                                </div>
                            </div>
                        </template>

                        <div x-show="filteredOrders.length === 0" class="py-8 text-center text-slate-400 text-xs font-medium">
                            Tidak ada pesanan pada rentang tanggal yang dipilih.
                        </div>

                        <!-- Pagination (Appears when filteredOrders.length > 10) -->
                        <div x-show="totalPages > 1" class="pt-6 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-slate-700">
                            <p class="text-slate-400 font-medium">
                                Menampilkan <span x-text="((currentPage - 1) * perPage) + 1"></span> - <span x-text="Math.min(currentPage * perPage, filteredOrders.length)"></span> dari <span x-text="filteredOrders.length"></span> pesanan
                            </p>

                            <div class="flex items-center gap-1.5">
                                <button 
                                    type="button" 
                                    @click="if(currentPage > 1) currentPage--"
                                    :disabled="currentPage === 1"
                                    :class="currentPage === 1 ? 'opacity-40 cursor-not-allowed' : 'hover:bg-slate-100 cursor-pointer'"
                                    class="px-3 py-1.5 rounded-xl border border-slate-200 bg-white transition-colors"
                                >
                                    &larr; Prev
                                </button>

                                <template x-for="page in totalPages" :key="page">
                                    <button 
                                        type="button" 
                                        @click="currentPage = page"
                                        :class="currentPage === page ? 'bg-black text-white border-black' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50 cursor-pointer'"
                                        class="w-8 h-8 rounded-xl border text-xs font-bold flex items-center justify-center transition-colors"
                                        x-text="page"
                                    ></button>
                                </template>

                                <button 
                                    type="button" 
                                    @click="if(currentPage < totalPages) currentPage++"
                                    :disabled="currentPage === totalPages"
                                    :class="currentPage === totalPages ? 'opacity-40 cursor-not-allowed' : 'hover:bg-slate-100 cursor-pointer'"
                                    class="px-3 py-1.5 rounded-xl border border-slate-200 bg-white transition-colors"
                                >
                                    Next &rarr;
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

            </div>

        </div>

    </div>
</div>

<script>
    function customerDashboardComponent() {
        const rawOrders = @json($orders);
        
        const mappedOrders = rawOrders.map(o => {
            const dateObj = new Date(o.created_at);
            const formattedDate = dateObj.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            return {
                ...o,
                created_time: dateObj.getTime(),
                formatted_date: formattedDate,
                formatted_total: 'Rp ' + new Intl.NumberFormat('id-ID').format(o.total_amount || 0),
                formatted_dp: 'Rp ' + new Intl.NumberFormat('id-ID').format(o.dp_amount || 0),
                formatted_remaining: 'Rp ' + new Intl.NumberFormat('id-ID').format(o.remaining_amount || 0),
            };
        });

        return {
            orders: mappedOrders,
            dateFilter: 'all',
            currentPage: 1,
            perPage: 10,
            get filteredOrders() {
                if (this.dateFilter === 'all') return this.orders;
                const now = new Date();
                return this.orders.filter(o => {
                    const orderDate = new Date(o.created_time);
                    if (this.dateFilter === '7_days') {
                        const diffTime = Math.abs(now - orderDate);
                        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                        return diffDays <= 7;
                    }
                    if (this.dateFilter === '30_days') {
                        const diffTime = Math.abs(now - orderDate);
                        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                        return diffDays <= 30;
                    }
                    if (this.dateFilter === 'this_month') {
                        return orderDate.getMonth() === now.getMonth() && orderDate.getFullYear() === now.getFullYear();
                    }
                    return true;
                });
            },
            get totalPages() {
                return Math.ceil(this.filteredOrders.length / this.perPage) || 1;
            },
            get paginatedOrders() {
                const start = (this.currentPage - 1) * this.perPage;
                return this.filteredOrders.slice(start, start + this.perPage);
            }
        };
    }
</script>
@endsection
