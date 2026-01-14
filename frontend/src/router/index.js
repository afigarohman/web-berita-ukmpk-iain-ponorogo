import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import Login from '../views/Login.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: Home
    },
    {
      path: '/login',
      name: 'login',
      component: Login
    },
    // HALAMAN DETAIL BERITA (DIPROTEKSI)
    {
      path: '/posts/:slug', 
      name: 'detail',
      component: Home, // Nanti ganti DetailView jika sudah ada file khusus
      meta: { requiresAuth: true } // <--- KUNCI PENGAMAN
    }
  ]
})

// --- LOGIKA SATPAM (NAVIGATION GUARD) ---
router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth) {
    const isAuthenticated = localStorage.getItem('isLoggedIn');

    if (!isAuthenticated) {
      // Simpan halaman yang ingin dituju user, biar nanti habis login bisa balik lagi (opsional)
      // alert("Maaf, konten ini khusus anggota. Silakan Login dulu.");
      next('/login');
    } else {
      next();
    }
  } else {
    next();
  }
});

export default router