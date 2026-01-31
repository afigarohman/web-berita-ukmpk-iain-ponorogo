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

          <div class="mt-12 bg-gray-50 p-6 rounded-lg border border-gray-100">
            <h3 class="font-bold text-lg mb-6 font-serif border-b pb-2">
              Komentar ({{ comments.length }})
            </h3>

            <div v-if="isLoggedIn" class="mb-10 animate-fade-in">
              <div class="flex items-center gap-3 mb-4">
                <img
                  :src="`https://ui-avatars.com/api/?name=${currentUser.name}&background=random`"
                  class="w-8 h-8 rounded-full"
                />
                <p class="text-sm text-gray-600">
                  Berkomentar sebagai
                  <span class="font-bold text-black">{{ currentUser.name }}</span>
                </p>
              </div>

              <form @submit.prevent="submitComment">
                <div class="mb-4">
                  <textarea
                    v-model="commentBody"
                    required
                    rows="3"
                    class="w-full px-4 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:border-black transition"
                    placeholder="Tulis pendapatmu dengan sopan..."
                  ></textarea>
                </div>
                <button
                  type="submit"
                  :disabled="isSubmitting"
                  class="bg-black text-white px-6 py-2 text-xs font-bold uppercase tracking-widest hover:bg-gray-800 transition disabled:opacity-50"
                >
                  {{ isSubmitting ? "Mengirim..." : "Kirim Komentar" }}
                </button>
              </form>
            </div>

            <div
              v-else
              class="mb-10 text-center py-8 bg-white border border-dashed border-gray-300 rounded-lg"
            >
              <p class="text-gray-500 text-sm mb-4">
                Silakan login terlebih dahulu untuk ikut berdiskusi.
              </p>
              <RouterLink
                to="/login"
                class="inline-block bg-[#d4a017] text-white px-6 py-2 rounded text-xs font-bold uppercase hover:bg-[#b88b14] transition"
              >
                Login Akun
              </RouterLink>
            </div>

            <div
              v-if="showSuccessMessage"
              class="mb-8 p-4 bg-green-50 border border-green-200 text-green-700 text-sm rounded flex items-center gap-2 animate-fade-in"
            >
              <svg
                class="w-5 h-5 flex-shrink-0"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                ></path>
              </svg>
              <span
                >Terima kasih! Komentar Anda telah terkirim dan sedang
                <b>menunggu persetujuan admin</b> sebelum ditampilkan.</span
              >
            </div>

            <div class="space-y-6">
              <div
                v-if="comments.length === 0"
                class="text-center text-gray-400 text-sm italic py-4"
              >
                Belum ada komentar yang ditampilkan.
              </div>

              <div
                v-for="(comment, index) in comments"
                :key="index"
                class="flex gap-4 border-b border-gray-200 pb-4 last:border-0"
              >
                <img
                  :src="`https://ui-avatars.com/api/?name=${
                    comment.user ? comment.user.name : 'User'
                  }&background=random`"
                  class="w-10 h-10 rounded-full"
                />
                <div>
                  <div class="flex items-center gap-2 mb-1">
                    <span class="font-bold text-sm text-gray-800">{{
                      comment.user ? comment.user.name : "Pengguna"
                    }}</span>
                    <span class="text-[10px] text-gray-400 font-sans uppercase">{{
                      formatDate(comment.created_at)
                    }}</span>
                  </div>
                  <p class="text-sm text-gray-600 leading-relaxed">
                    {{ comment.body }}
                  </p>
                </div>
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

// --- CONFIG API ---
const API_BASE_URL = import.meta.env.VITE_API_URL || "http://127.0.0.1:8000";

const route = useRoute();
const post = ref(null);
const popularPosts = ref([]);
const relatedPosts = ref([]);
const loading = ref(true);

// State Pagination Berita
const currentPage = ref(1);
const paragraphsPerPage = 4;

// --- STATE KOMENTAR & AUTH ---
const comments = ref([]);
const commentBody = ref("");
const isSubmitting = ref(false);
const isLoggedIn = ref(false);
const currentUser = ref({});
const showSuccessMessage = ref(false);

// --- COMPUTED PROPERTIES ---
const paginatedContent = computed(() => {
  if (!post.value || !post.value.content) return "";
  const rawContent = post.value.content;
  const splitContent = rawContent.split("</p>");
  if (splitContent[splitContent.length - 1] === "") splitContent.pop();

  const start = (currentPage.value - 1) * paragraphsPerPage;
  const end = start + paragraphsPerPage;
  return splitContent.slice(start, end).join("</p>") + "</p>";
});

const totalPages = computed(() => {
  if (!post.value || !post.value.content) return 0;
  const splitContent = post.value.content.split("</p>").filter((p) => p.trim() !== "");
  return Math.ceil(splitContent.length / paragraphsPerPage);
});

// --- HELPER METHODS ---
const getImageUrl = (path) => {
  if (!path) return "https://via.placeholder.com/800x400";
  if (path.startsWith("http")) return path;
  return `${API_BASE_URL}/storage/${path}`;
};

const formatDate = (dateString) => {
  if (!dateString) return "";
  const date = new Date(dateString);
  return date.toLocaleDateString("id-ID", {
    weekday: "long",
    day: "numeric",
    month: "long",
    year: "numeric",
  });
};

// --- AUTH LOGIC ---
const checkAuth = () => {
  const token = localStorage.getItem("token");
  const user = localStorage.getItem("user");

  if (token && user) {
    isLoggedIn.value = true;
    try {
      currentUser.value = JSON.parse(user);
    } catch (e) {
      currentUser.value = { name: "User" };
    }
  } else {
    isLoggedIn.value = false;
  }
};

// --- API FETCH METHODS ---
const fetchComments = async (postId) => {
  try {
    const headers = { "ngrok-skip-browser-warning": "true" };
    const response = await axios.get(`${API_BASE_URL}/api/posts/${postId}/comments`, {
      headers,
    });
    comments.value = response.data.data;
  } catch (error) {
    console.error("Belum ada komentar / Backend endpoint belum siap.");
  }
};

const submitComment = async () => {
  if (!commentBody.value) return;

  isSubmitting.value = true;
  showSuccessMessage.value = false;

  try {
    const token = localStorage.getItem("token");

    await axios.post(
      `${API_BASE_URL}/api/posts/${post.value.id}/comments`,
      { body: commentBody.value },
      {
        headers: {
          Authorization: `Bearer ${token}`, // Kirim Token Auth
          "ngrok-skip-browser-warning": "true",
        },
      }
    );

    // Reset Form & Tampilkan Pesan Sukses
    commentBody.value = "";
    showSuccessMessage.value = true;

    // Catatan: Kita tidak push ke comments.value karena statusnya masih pending approval
  } catch (error) {
    console.error("Gagal kirim komentar:", error);
    if (error.response && error.response.status === 401) {
      alert("Sesi login berakhir. Silakan login ulang.");
      // Logout paksa jika token expired
      localStorage.removeItem("token");
      localStorage.removeItem("user");
      isLoggedIn.value = false;
      window.location.href = "/login";
    } else {
      alert("Gagal mengirim komentar. Pastikan backend sudah siap.");
    }
  } finally {
    isSubmitting.value = false;
  }
};

const fetchPost = async () => {
  loading.value = true;
  currentPage.value = 1;

  try {
    const slug = route.params.slug;
    const headers = { "ngrok-skip-browser-warning": "true" };

    // 1. Ambil Detail Post
    const response = await axios.get(`${API_BASE_URL}/api/posts/${slug}`, {
      headers,
    });
    post.value = response.data.data;

    // 2. Ambil Komentar (Jika post berhasil diload)
    if (post.value && post.value.id) {
      fetchComments(post.value.id);
    }

    // 3. Ambil Post Lain untuk Sidebar & Footer
    const allPostsResponse = await axios.get(`${API_BASE_URL}/api/posts`, {
      headers,
    });
    const allPosts = allPostsResponse.data.data.data;

    popularPosts.value = allPosts.filter((p) => p.is_popular == 1).slice(0, 5);
    relatedPosts.value = allPosts
      .filter((p) => p.category === post.value.category && p.id !== post.value.id)
      .slice(0, 4);
  } catch (error) {
    console.error("Gagal load data berita:", error);
  } finally {
    loading.value = false;
  }
};

// --- LIFECYCLE HOOKS ---
onMounted(() => {
  checkAuth();
  fetchPost();
});

watch(
  () => route.params.slug,
  () => {
    showSuccessMessage.value = false; // Reset pesan saat ganti berita
    fetchPost();
    window.scrollTo(0, 0);
  }
);
</script>

<style>
/* CSS Typography untuk Konten Berita */
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

/* Animasi Fade In Sederhana */
.animate-fade-in {
  animation: fadeIn 0.5s ease-in-out;
}
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
