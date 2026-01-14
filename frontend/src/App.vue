<template>
  <Navbar />
  
  <div class="min-h-screen">
    <RouterView />
  </div>
  
  <footer class="bg-gray-100 text-center py-8 border-t border-gray-300 mt-12">
    <p class="font-ui text-xs text-gray-500">
      &copy; 2026 UKM Pengembangan Keilmuan - IAIN Ponorogo. All Rights Reserved.
    </p>
  </footer>
</template>

<script setup>
import { RouterView } from 'vue-router'
import Navbar from './components/Navbar.vue'
</script>