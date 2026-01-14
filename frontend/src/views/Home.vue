<template>
  <div class="max-w-7xl mx-auto px-4 py-8 border-x border-gray-100 min-h-screen bg-white">
    
    <div v-if="loading" class="text-center py-20">
      <div class="inline-block animate-spin rounded-full h-12 w-12 border-t-4 border-black border-r-4"></div>
      <p class="mt-4 text-gray-500 font-sans">Sedang memuat berita...</p>
    </div>

    <div v-else class="grid grid-cols-1 lg:grid-cols-12 gap-12">
      
      <div class="lg:col-span-9 space-y-10">
        
        <div v-if="searchKeyword" class="mb-4 p-4 bg-gray-50 border-l-4 border-black flex justify-between items-center">
          <p class="text-sm text-gray-600">
            Hasil pencarian: <span class="font-bold text-black">"{{ searchKeyword }}"</span>
          </p>
          <button @click="clearSearch" class="text-red-500 text-xs font-bold hover:underline">HAPUS FILTER ✕</button>
        </div>

        <div v-if="filteredPosts.length > 0" class="border-b border-gray-200 pb-8 group cursor-pointer">
          <RouterLink :to="'/posts/' + filteredPosts[0].slug">
            <span class="text-red-700 font-bold text-xs uppercase tracking-widest font-sans mb-3 block">Berita Utama</span>
            
            <h1 class="text-3xl md:text-5xl font-bold font-serif text-gray-900 leading-tight group-hover:text-gray-700 transition mb-4">
              {{ filteredPosts[0].title }}
            </h1>
            
            <div class="flex flex-col md:flex-row gap-8">
              <div class="md:w-2/3">
                <p class="text-lg text-gray-600 leading-relaxed font-serif line-clamp-4">
                  {{ stripHtml(filteredPosts[0].content) }}
                </p>
                <div class="mt-4 text-xs text-gray-500 font-sans flex items-center gap-2">
                  <span class="font-bold text-black uppercase">{{ filteredPosts[0].author || 'Admin' }}</span> 
                  <span class="text-gray-300">|</span>
                  {{ formatDate(filteredPosts[0].created_at) }}
                </div>
              </div>
              <div class="md:w-1/3">
                 <div class="aspect-[4/3] bg-gray-100 w-full overflow-hidden shadow-sm">
                    <img :src="getImageUrl(filteredPosts[0].thumbnail)" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition duration-700" @error="handleImageError">
                 </div>
                 <p class="text-[10px] text-gray-400 mt-1 text-right font-sans">Dokumentasi: UKM PK</p>
              </div>
            </div>
          </RouterLink>
        </div>

        <div v-if="filteredPosts.length > 1" class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-12 pt-2">
          <div v-for="(post, index) in filteredPosts.slice(1)" :key="post.id" class="border-t border-gray-200 pt-5">
            <RouterLink :to="'/posts/' + post.slug" class="group block">
              <div class="mb-4 overflow-hidden aspect-video bg-gray-100 shadow-sm relative">
                  <img :src="getImageUrl(post.thumbnail)" class="w-full h-full object-cover transition transform duration-500 group-hover:scale-105" @error="handleImageError">
                  <div class="absolute bottom-0 left-0 bg-black text-white text-[10px] uppercase font-bold px-2 py-1">
                    {{ post.category }}
                  </div>
              </div>
              
              <div class="flex items-center gap-2 mb-2 text-[10px] text-gray-400 font-sans">
                <span>{{ formatDate(post.created_at) }}</span>
              </div>

              <h3 class="text-xl md:text-2xl font-bold mb-2 leading-snug font-serif group-hover:underline decoration-1 underline-offset-4">
                {{ post.title }}
              </h3>
              
              <p class="text-sm text-gray-600 line-clamp-3 font-serif leading-relaxed">
                {{ stripHtml(post.content) }}
              </p>
            </RouterLink>
          </div>
        </div>

        <div v-if="filteredPosts.length === 0" class="py-10 text-center bg-gray-50 rounded-lg">
          <p class="text-gray-500 text-lg font-serif italic">Tidak ada berita ditemukan untuk pencarian ini.</p>
          <button @click="clearSearch" class="mt-2 text-sm text-blue-600 hover:underline">Kembali ke semua berita</button>
        </div>

      </div>

      <div class="lg:col-span-3 pl-0 lg:pl-4 border-t lg:border-t-0 lg:border-l border-gray-200 pt-8 lg:pt-0">
        
        <div class="sticky top-20 transition-all duration-300">
            <h4 class="font-sans font-bold text-xs uppercase tracking-widest border-b border-black pb-2 mb-6">
            Trending Topic
            </h4>
            
            <div class="space-y-6">
                <div v-for="(pop, index) in popularPosts" :key="pop.id" class="group cursor-pointer">
                <RouterLink :to="'/posts/' + pop.slug">
                    <div class="flex items-start gap-3">
                        <span class="text-3xl font-black text-gray-200 font-serif leading-none group-hover:text-[#d4a017] transition -mt-1">{{ index + 1 }}</span>
                        <div>
                            <span class="text-[10px] text-red-600 font-bold uppercase font-sans mb-1 block">{{ pop.category }}</span>
                            <h3 class="text-base font-bold leading-snug group-hover:text-red-700 transition font-serif">
                            {{ pop.title }}
                            </h3>
                        </div>
                    </div>
                </RouterLink>
                <div class="border-b border-gray-100 mt-4"></div>
                </div>

                <div v-if="popularPosts.length === 0" class="text-gray-400 text-xs italic">
                    Belum ada data trending.
                </div>
            </div>

            <div class="mt-10 border border-gray-200 p-4 text-center bg-gray-50">
                <p class="text-[10px] text-gray-400 uppercase tracking-widest mb-2">Advertisement</p>
                <div class="w-full aspect-square bg-gray-200 flex items-center justify-center">
                    <span class="text-gray-400 font-bold text-xs">IKLAN UKM</span>
                </div>
            </div>
        </div>

      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import axios from 'axios';
import { RouterLink, useRoute, useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter();

const posts = ref([]);
const popularPosts = ref([]);
const loading = ref(true);
const searchKeyword = ref('');

// Filter Berita
const filteredPosts = computed(() => {
  if (!searchKeyword.value) return posts.value;
  const lowerKeyword = searchKeyword.value.toLowerCase();
  return posts.value.filter(post => {
    return post.title.toLowerCase().includes(lowerKeyword) || 
           post.category.toLowerCase().includes(lowerKeyword);
  });
});

const fetchPosts = async () => {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/posts');
    const allPosts = response.data.data.data;
    posts.value = allPosts;
    // Filter Populer
    popularPosts.value = allPosts.filter(p => p.is_popular == 1 || p.is_popular == true).slice(0, 5);
  } catch (error) {
    console.error("Gagal ambil berita:", error);
  } finally {
    loading.value = false;
  }
};

const clearSearch = () => {
  searchKeyword.value = '';
  router.replace({ query: null });
};

const stripHtml = (html) => {
   if (!html) return "";
   let tmp = document.createElement("DIV");
   tmp.innerHTML = html;
   return tmp.textContent || tmp.innerText || "";
};

const getImageUrl = (path) => {
  if (!path) return 'https://via.placeholder.com/640x480?text=No+Image';
  if (path.startsWith('http')) return path;
  return `http://127.0.0.1:8000/storage/${path}`;
};

const handleImageError = (e) => {
  e.target.src = "https://via.placeholder.com/640x480?text=Image+Error";
};

const formatDate = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
};

watch(() => route.query.q, (newQuery) => {
  searchKeyword.value = newQuery || '';
}, { immediate: true });

onMounted(() => {
  fetchPosts();
});
</script>