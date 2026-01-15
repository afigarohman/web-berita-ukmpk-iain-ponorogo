<template>
  <div class="max-w-7xl mx-auto px-4 py-12 min-h-screen">
    <div class="text-center mb-12 animate-fade-in-down">
      <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">
        Arsip Kategori
      </p>
      <h1
        class="text-3xl md:text-6xl font-black font-serif text-gray-900 border-b-4 border-[#d4a017] inline-block pb-2"
      >
        Sastra
      </h1>
    </div>

    <div v-if="loading" class="text-center py-20">
      <div
        class="inline-block animate-spin rounded-full h-12 w-12 border-t-4 border-black border-r-4"
      ></div>
    </div>

    <div v-else-if="posts.length === 0" class="text-center py-20 bg-gray-50 rounded-lg">
      <p class="text-xl text-gray-500 font-serif italic">
        Belum ada tulisan di kategori Artikel.
      </p>
      <RouterLink
        to="/"
        class="mt-4 inline-block bg-black text-white px-6 py-2 rounded font-bold text-sm"
        >Kembali ke Beranda</RouterLink
      >
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
      <div v-for="post in posts" :key="post.id" class="group cursor-pointer">
        <RouterLink :to="'/posts/' + post.slug">
          <div
            class="aspect-video bg-gray-100 overflow-hidden mb-4 rounded-lg shadow-sm relative"
          >
            <img
              :src="getImageUrl(post.thumbnail)"
              class="w-full h-full object-cover transition transform duration-500 group-hover:scale-105"
            />
          </div>
          <div class="flex items-center gap-2 mb-2 text-xs text-gray-500 font-sans">
            <span>{{ formatDate(post.created_at) }}</span>
            <span class="text-[#d4a017]">•</span>
            <span>{{ post.author || "Admin" }}</span>
          </div>
          <h3
            class="text-xl font-bold font-serif leading-tight group-hover:text-red-700 transition mb-3"
          >
            {{ post.title }}
          </h3>
          <p class="text-sm text-gray-600 font-serif line-clamp-3 leading-relaxed">
            {{ stripHtml(post.content) }}
          </p>
        </RouterLink>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const posts = ref([]);
const loading = ref(true);

const fetchPosts = async () => {
  loading.value = true;
  try {
    const response = await axios.get("http://127.0.0.1:8000/api/posts?category=Sastra");
    posts.value = response.data.data.data;
  } catch (error) {
    console.error("Error:", error);
  } finally {
    loading.value = false;
  }
};

const getImageUrl = (path) =>
  path?.startsWith("http") ? path : `http://127.0.0.1:8000/storage/${path}`;
const formatDate = (d) =>
  new Date(d).toLocaleDateString("id-ID", {
    day: "numeric",
    month: "long",
    year: "numeric",
  });
const stripHtml = (h) => {
  let t = document.createElement("div");
  t.innerHTML = h;
  return t.textContent || "";
};

onMounted(() => fetchPosts());
</script>
