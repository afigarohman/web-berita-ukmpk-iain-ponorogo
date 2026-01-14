<template>
  <header class="bg-white border-b border-black flex flex-col relative z-50">
    
    <div class="bg-white relative z-20">
      
      <div class="max-w-7xl mx-auto px-4 py-1 w-full flex justify-between items-center text-xs sm:text-sm font-ui border-b border-gray-200">
        <div class="text-gray-700 font-bold">
          {{ currentDateTime }}
        </div>
        
        <div v-if="!isLoggedIn" class="flex items-center space-x-4">
          <RouterLink 
            to="/login" 
            class="bg-black text-white px-3 py-1 rounded hover:bg-gray-800 transition font-bold uppercase tracking-wider text-xs"
          >
            Login
          </RouterLink>
        </div>
        <div v-else class="flex items-center space-x-3">
          <span class="text-gray-500 text-xs italic">Hi, Pembaca</span>
          <button @click="handleLogout" class="text-red-700 hover:text-red-900 font-bold text-xs uppercase border-l border-gray-300 pl-3">
            Logout
          </button>
        </div>
      </div>

      <div class="py-6 flex justify-center items-center">
        <RouterLink to="/" class="flex flex-col items-center group">
          <img src="/logo_ukmpk.png" alt="Logo UKM PK" class="h-16 md:h-20 w-auto mb-2 opacity-90 group-hover:opacity-100 transition">
          <span class="uppercase tracking-[0.2em] text-[10px] font-ui text-gray-500 text-center hidden md:block">Unit Kegiatan Mahasiswa Pengembangan Keilmuan</span>
        </RouterLink>
      </div>

    </div>

    <div class="sticky top-0 z-50 h-[52px]"> 
      <nav 
        class="absolute w-full bg-white border-t border-black border-double border-b-4 py-2 shadow-sm transition-transform duration-300 ease-in-out"
        :class="[
          isHidden ? '-translate-y-[150%]' : 'translate-y-0',
          isScrolled ? 'shadow-md' : ''
        ]"
      >
        <div class="max-w-7xl mx-auto px-4 flex justify-between items-center h-full">
          
          <div class="flex-1 hidden md:flex justify-center space-x-6 lg:space-x-8 text-sm font-bold font-ui uppercase tracking-wide">
            <RouterLink 
              v-for="(item, index) in menuItems" 
              :key="index" 
              to="/" 
              class="text-gray-900 hover:text-[#d4a017] hover:underline decoration-2 underline-offset-4 transition"
            >
              {{ item }}
            </RouterLink>
          </div>

          <div class="flex items-center gap-2">
            <div class="relative group">
              <input 
                type="text" 
                v-model="searchQuery"
                @keyup.enter="handleSearch"
                placeholder="Cari..." 
                class="border border-gray-300 rounded-full py-1 px-3 text-xs focus:outline-none focus:border-black focus:ring-1 focus:ring-black transition w-24 focus:w-40"
              >
              <button @click="handleSearch" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-black">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
              </button>
            </div>
          </div>

          <div class="md:hidden flex items-center ml-4">
            <button @click="isOpen = !isOpen" class="text-gray-800 focus:outline-none">
              <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path v-if="!isOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

        </div>

        <div v-if="isOpen" class="md:hidden bg-gray-50 border-t mt-2 p-4 grid grid-cols-2 gap-3 absolute w-full left-0 shadow-lg top-full">
          <RouterLink 
            v-for="(item, index) in menuItems" 
            :key="index" 
            to="/" 
            class="text-gray-700 font-medium hover:text-black block text-sm"
            @click="isOpen = false" 
          >
            {{ item }}
          </RouterLink>
        </div>
      </nav>
    </div>

  </header>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { RouterLink, useRouter } from 'vue-router';

const router = useRouter();

const menuItems = [
  'Berita', 'Opini', 'Esai', 'Artikel', 
  'Geneologi', 'Resensi', 'Buletin', 'Sastra'
];

const isOpen = ref(false);
const currentDateTime = ref('');
const searchQuery = ref('');
const isLoggedIn = ref(localStorage.getItem('isLoggedIn') === 'true');

// --- LOGIKA SCROLL BARU (LEBIH SENSITIF) ---
const isScrolled = ref(false);
const isHidden = ref(false); 
let lastScrollY = 0;

const handleScroll = () => {
  const currentScrollY = window.scrollY;

  // 1. Abaikan scroll negatif (biasanya efek karet di Mac/HP)
  if (currentScrollY < 0) return;

  // 2. Deteksi apakah sudah scroll agak jauh dari atas (biar shadow muncul)
  isScrolled.value = currentScrollY > 100;

  // 3. LOGIKA UTAMA: Tentukan Arah Scroll
  // Kita beri toleransi 0 agar sangat responsif
  if (currentScrollY > lastScrollY) {
    // SCROLL KE BAWAH -> Sembunyikan
    // Hanya sembunyi jika sudah melewati area logo (misal > 150px)
    if (currentScrollY > 150) {
      isHidden.value = true;
    }
  } else if (currentScrollY < lastScrollY) {
    // SCROLL KE ATAS -> Munculkan SEGERA
    isHidden.value = false;
  }

  // Update posisi terakhir
  lastScrollY = currentScrollY;
};

// Update Jam
const updateTime = () => {
  const now = new Date();
  const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
  currentDateTime.value = now.toLocaleDateString('id-ID', options).replace('.', ':'); 
};

// Search
const handleSearch = () => {
  router.push({ name: 'home', query: { q: searchQuery.value } });
};

// Logout
const handleLogout = () => {
  localStorage.removeItem('isLoggedIn');
  localStorage.removeItem('userEmail');
  isLoggedIn.value = false;
  window.location.reload();
};

let timer;
onMounted(() => {
  updateTime();
  timer = setInterval(updateTime, 1000); 
  // Pasang Event Listener Scroll
  window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
  clearInterval(timer);
  // Hapus Event Listener saat pindah halaman (PENTING biar ga error)
  window.removeEventListener('scroll', handleScroll);
});
</script>