<template>
  <div class="relative">
    <div class="h-[140px] md:h-[160px] w-full bg-white"></div>

    <header
      class="fixed top-0 left-0 w-full z-50 bg-white border-b border-black transition-transform duration-300 ease-in-out"
      :class="{ '-translate-y-[110px]': hideTopBar }"
    >
      <div class="bg-white relative z-20">
        <div
          class="max-w-7xl mx-auto px-4 py-1 flex justify-between items-center text-xs sm:text-sm font-ui border-b border-gray-200"
        >
          <div class="text-gray-700 font-bold">{{ currentDateTime }}</div>

          <div v-if="!isLoggedIn" class="flex items-center space-x-4">
            <RouterLink
              to="/login"
              class="bg-black text-white px-3 py-1 rounded hover:bg-gray-800 font-bold uppercase text-xs"
              >Login</RouterLink
            >
          </div>
          <div v-else class="flex items-center space-x-3">
            <span class="text-gray-500 text-xs italic">Hi, Pembaca</span>
            <button
              @click="handleLogout"
              class="text-red-700 font-bold text-xs uppercase border-l border-gray-300 pl-3"
            >
              Logout
            </button>
          </div>
        </div>

        <div class="py-4 flex justify-center items-center">
          <RouterLink to="/" class="flex flex-col items-center group">
            <img src="/logo_ukmpk.png" alt="Logo" class="h-12 md:h-16 w-auto mb-1" />
            <span
              class="uppercase tracking-[0.2em] text-[10px] font-ui text-gray-500 hidden md:block"
              >Unit Kegiatan Mahasiswa Pengembangan Keilmuan</span
            >
          </RouterLink>
        </div>
      </div>

      <nav
        class="bg-white border-t border-black border-double border-b-4 py-2 shadow-sm relative z-30"
      >
        <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
          <div
            class="flex-1 hidden md:flex justify-center space-x-6 text-sm font-bold font-ui uppercase tracking-wide"
          >
            <RouterLink
              v-for="(item, index) in menuItems"
              :key="index"
              :to="item === 'Berita' ? '/' : '/' + item.toLowerCase()"
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
                class="border border-gray-300 rounded-full py-1 px-3 text-xs w-24 focus:w-40 transition focus:outline-none focus:border-black"
              />
              <button
                @click="handleSearch"
                class="absolute right-2 top-1/2 -translate-y-1/2"
              >
                <svg
                  class="w-3 h-3 text-gray-400"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                  ></path>
                </svg>
              </button>
            </div>
          </div>

          <button @click="isOpen = !isOpen" class="md:hidden ml-4 text-gray-800">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"
              ></path>
            </svg>
          </button>
        </div>

        <div
          v-if="isOpen"
          class="md:hidden bg-gray-50 border-t mt-2 p-4 grid grid-cols-2 gap-3 absolute w-full left-0 shadow-lg top-full max-h-[80vh] overflow-y-auto"
        >
          <RouterLink
            v-for="(item, index) in menuItems"
            :key="index"
            :to="'/' + item.toLowerCase()"
            class="text-gray-700 font-medium block text-sm"
            @click="isOpen = false"
            >{{ item }}</RouterLink
          >
        </div>
      </nav>
    </header>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();
const menuItems = [
  "Berita",
  "Opini",
  "Esai",
  "Artikel",
  "Geneologi",
  "Resensi",
  "Buletin",
  "Sastra",
];
const isOpen = ref(false);
const currentDateTime = ref("");
const searchQuery = ref("");
const isLoggedIn = ref(localStorage.getItem("isLoggedIn") === "true");

// --- LOGIKA SCROLL BARU ---
const hideTopBar = ref(false);
let lastScrollY = 0;

const handleScroll = () => {
  const currentScrollY = window.scrollY;

  if (currentScrollY < 0) return; // Abaikan efek karet

  // Jika scroll ke BAWAH dan sudah melewati header (100px) -> Sembunyikan Bagian Atas
  if (currentScrollY > lastScrollY && currentScrollY > 100) {
    hideTopBar.value = true; // Geser ke atas (-110px)
  }
  // Jika scroll ke ATAS -> Tampilkan semua
  else if (currentScrollY < lastScrollY) {
    hideTopBar.value = false; // Reset posisi (0px)
  }

  lastScrollY = currentScrollY;
};

const updateTime = () => {
  /* ... logika jam sama ... */
  const now = new Date();
  currentDateTime.value = now
    .toLocaleDateString("id-ID", {
      weekday: "long",
      year: "numeric",
      month: "long",
      day: "numeric",
      hour: "2-digit",
      minute: "2-digit",
    })
    .replace(".", ":");
};
const handleSearch = () => {
  router.push({ name: "home", query: { q: searchQuery.value } });
};
const handleLogout = () => {
  /* ... logika logout sama ... */ localStorage.removeItem("isLoggedIn");
  window.location.reload();
};

onMounted(() => {
  updateTime();
  setInterval(updateTime, 1000);
  window.addEventListener("scroll", handleScroll);
});
onUnmounted(() => {
  window.removeEventListener("scroll", handleScroll);
});
</script>
