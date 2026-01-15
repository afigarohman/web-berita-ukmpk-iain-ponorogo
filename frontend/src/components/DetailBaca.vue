<template>
  <div class="min-h-screen bg-white pb-20">
    <div v-if="loading" class="text-center py-40">
      <div
        class="inline-block animate-spin rounded-full h-12 w-12 border-t-4 border-black border-r-4"
      ></div>
      <p class="mt-4 text-gray-400 text-sm font-sans">Sedang memuat tulisan...</p>
    </div>

    <div v-else-if="!post" class="text-center py-40">
      <h1 class="text-2xl font-bold text-gray-800">Tulisan tidak ditemukan.</h1>
      <RouterLink to="/" class="text-red-600 underline mt-4 block"
        >Kembali ke Beranda</RouterLink
      >
    </div>

    <div v-else class="max-w-7xl mx-auto px-4 pt-8 animate-fade-in">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
        <div class="lg:col-span-8">
          <div class="mb-6">
            <div class="flex items-center gap-3 mb-4">
              <span
                class="px-2 py-1 text-[10px] font-bold tracking-widest text-white uppercase bg-black"
              >
                {{ post.category }}
              </span>
              <span class="text-xs text-gray-500 font-sans uppercase tracking-wide">
                {{ formatDate(post.created_at) }}
              </span>
            </div>

            <h1
              class="text-3xl md:text-4xl font-bold font-serif text-gray-900 leading-tight mb-4"
            >
              {{ post.title }}
            </h1>

            <div
              class="flex items-center gap-2 text-sm font-sans text-gray-600 border-b border-gray-200 pb-6"
            >
              <span class="font-bold uppercase text-xs"
                >Oleh {{ post.author || "Redaksi UKM PK" }}</span
              >
            </div>
          </div>

          <div class="mb-8">
            <div class="w-full bg-gray-100 overflow-hidden shadow-sm">
              <img
                :src="getImageUrl(post.thumbnail)"
                class="w-full h-auto object-cover"
                alt="Cover Image"
              />
            </div>
            <p
              class="text-[10px] text-gray-400 mt-2 text-right font-sans uppercase tracking-wide"
            >
              Dokumentasi: UKM PK
            </p>
          </div>

          <div
            class="max-w-none prose prose-lg prose-headings:font-serif prose-p:font-serif prose-p:text-gray-800 prose-p:leading-loose prose-a:text-red-700 hover:prose-a:text-red-500 transition-colors"
          >
            <div v-html="paginatedContent"></div>
          </div>

          <div
            v-if="totalPages > 1"
            class="my-10 flex items-center justify-center gap-2 font-sans"
          >
            <span class="text-xs text-gray-400 uppercase mr-2">Halaman:</span>
            <button
              v-for="page in totalPages"
              :key="page"
              @click="currentPage = page"
              class="w-8 h-8 flex items-center justify-center rounded-full text-sm font-bold border transition"
              :class="
                currentPage === page
                  ? 'bg-black text-white border-black'
                  : 'bg-white text-gray-700 border-gray-300 hover:border-black'
              "
            >
              {{ page }}
            </button>
          </div>

          <div class="mt-8 pt-6 border-t border-black border-double border-t-4">
            <div class="flex justify-between items-center">
              <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                Bagikan Tulisan Ini
              </p>
              <div class="flex gap-2">
                <button
                  class="bg-[#3b5998] text-white px-3 py-1 rounded text-xs font-bold hover:opacity-90"
                >
                  Facebook
                </button>
                <button
                  class="bg-[#25D366] text-white px-3 py-1 rounded text-xs font-bold hover:opacity-90"
                >
                  WhatsApp
                </button>
                <button
                  class="bg-[#1DA1F2] text-white px-3 py-1 rounded text-xs font-bold hover:opacity-90"
                >
                  Twitter
                </button>
              </div>
            </div>
          </div>
        </div>

        <div
          class="lg:col-span-4 pl-0 lg:pl-8 border-t lg:border-t-0 lg:border-l border-gray-200 pt-8 lg:pt-0"
        >
          <div class="sticky top-24">
            <h4
              class="font-sans font-bold text-xs uppercase tracking-widest border-b-2 border-black pb-2 mb-6"
            >
              Berita Populer
            </h4>

            <div class="space-y-6">
              <div
                v-for="(pop, index) in popularPosts"
                :key="pop.id"
                class="group cursor-pointer"
              >
                <RouterLink :to="'/posts/' + pop.slug">
                  <div class="flex gap-4">
                    <span
                      class="text-3xl font-black text-gray-200 font-serif leading-none group-hover:text-[#d4a017] transition"
                      >{{ index + 1 }}</span
                    >
                    <div>
                      <span
                        class="text-[10px] text-red-600 font-bold uppercase block mb-1"
                        >{{ pop.category }}</span
                      >
                      <h3
                        class="text-base font-bold leading-snug font-serif group-hover:text-red-700 transition"
                      >
                        {{ pop.title }}
                      </h3>
                    </div>
                  </div>
                </RouterLink>
                <div class="border-b border-gray-100 mt-4"></div>
              </div>
            </div>

            <div class="mt-10 bg-gray-50 border border-gray-200 p-8 text-center">
              <p class="text-[10px] text-gray-400 uppercase mb-2">Advertisement</p>
              <div
                class="h-40 bg-gray-200 w-full flex items-center justify-center text-gray-400 font-bold text-xs"
              >
                IKLAN UKM PK
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="mt-20 pt-10 border-t border-gray-200">
        <h3 class="font-bold text-lg mb-6 font-serif">Bacaan Lainnya</h3>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
          <div v-for="related in relatedPosts" :key="related.id" class="group">
            <RouterLink :to="'/posts/' + related.slug">
              <div class="aspect-video bg-gray-100 mb-3 overflow-hidden">
                <img
                  :src="getImageUrl(related.thumbnail)"
                  class="w-full h-full object-cover transition duration-500 group-hover:scale-105 grayscale group-hover:grayscale-0"
                />
              </div>
              <span class="text-[10px] text-gray-400 font-bold uppercase">{{
                related.category
              }}</span>
              <h4
                class="text-sm font-bold font-serif mt-1 leading-snug group-hover:underline"
              >
                {{ related.title }}
              </h4>
            </RouterLink>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, computed } from "vue";
import { useRoute } from "vue-router";
import axios from "axios";

const route = useRoute();
const post = ref(null);
const popularPosts = ref([]);
const relatedPosts = ref([]);
const loading = ref(true);

// State untuk Pagination
const currentPage = ref(1);
const paragraphsPerPage = 4; // Jumlah paragraf per halaman

// Computed: Memecah konten HTML menjadi Halaman
const paginatedContent = computed(() => {
  if (!post.value || !post.value.content) return "";

  // Pisahkan konten berdasarkan tag penutup paragraf </p>
  // Ini trik sederhana untuk memecah HTML dari CKEditor
  const rawContent = post.value.content;
  const splitContent = rawContent.split("</p>");

  // Hapus elemen kosong terakhir (akibat split)
  if (splitContent[splitContent.length - 1] === "") splitContent.pop();

  const start = (currentPage.value - 1) * paragraphsPerPage;
  const end = start + paragraphsPerPage;

  // Gabungkan kembali paragraf yang dipilih dan tambahkan </p> lagi
  return splitContent.slice(start, end).join("</p>") + "</p>";
});

// Computed: Hitung Total Halaman
const totalPages = computed(() => {
  if (!post.value || !post.value.content) return 0;
  const splitContent = post.value.content.split("</p>").filter((p) => p.trim() !== "");
  return Math.ceil(splitContent.length / paragraphsPerPage);
});

// Fetch Data Utama
const fetchPost = async () => {
  loading.value = true;
  currentPage.value = 1; // Reset halaman ke 1 setiap buka berita baru

  try {
    const slug = route.params.slug;

    // 1. Ambil Detail Berita
    const response = await axios.get(`http://127.0.0.1:8000/api/posts/${slug}`);
    post.value = response.data.data;

    // 2. Ambil Semua Berita untuk Filter Populer & Terkait
    // (Idealnya ini endpoint terpisah di backend, tapi kita filter manual di sini sementara)
    const allPostsResponse = await axios.get("http://127.0.0.1:8000/api/posts");
    const allPosts = allPostsResponse.data.data.data;

    // Filter Popular (is_popular == 1)
    popularPosts.value = allPosts.filter((p) => p.is_popular == 1).slice(0, 5);

    // Filter Related (Kategori sama, tapi bukan berita yang sedang dibuka)
    relatedPosts.value = allPosts
      .filter((p) => p.category === post.value.category && p.id !== post.value.id)
      .slice(0, 4); // Ambil 4 berita terkait
  } catch (error) {
    console.error("Gagal load data:", error);
  } finally {
    loading.value = false;
  }
};

const getImageUrl = (path) => {
  if (!path) return "https://via.placeholder.com/800x400";
  if (path.startsWith("http")) return path;
  return `http://127.0.0.1:8000/storage/${path}`;
};

const formatDate = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleDateString("id-ID", {
    weekday: "long",
    day: "numeric",
    month: "long",
    year: "numeric",
  });
};

onMounted(() => {
  fetchPost();
});

watch(
  () => route.params.slug,
  () => {
    fetchPost();
    window.scrollTo(0, 0); // Scroll ke atas saat ganti berita
  }
);
</script>

<style>
/* Styling khusus untuk konten hasil CKEditor */
.prose p {
  margin-bottom: 1.5em;
  line-height: 1.8;
  font-size: 1.125rem;
}
.prose h2,
.prose h3 {
  margin-top: 2em;
  margin-bottom: 1em;
  font-weight: bold;
  font-family: "Playfair Display", serif;
}
.prose blockquote {
  border-left: 4px solid #d4a017;
  padding-left: 1rem;
  font-style: italic;
  color: #555;
  margin: 2em 0;
  background-color: #fffdf5;
  padding: 1em;
}
.prose img {
  margin: 2rem auto;
  border-radius: 8px;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}
.prose ul {
  list-style-type: disc;
  padding-left: 1.5em;
  margin-bottom: 1.5em;
}
.prose ol {
  list-style-type: decimal;
  padding-left: 1.5em;
  margin-bottom: 1.5em;
}
</style>
