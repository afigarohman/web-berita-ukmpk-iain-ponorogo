import { createRouter, createWebHistory } from 'vue-router'

import Home from '../views/berita/Home.vue' 
import Login from '../views/Login.vue'

import DetailBaca from '../components/DetailBaca.vue' 

import ArtikelIndex from '../views/artikel/Artikel.vue'
import BuletinIndex from '../views/buletin/Buletin.vue'
import EsaiIndex from '../views/esai/Esai.vue'
import GeneologiIndex from '../views/geneologi/Geneologi.vue'
import OpiniIndex from '../views/opini/Opini.vue'
import ResensiIndex from '../views/resensi/Resensi.vue'
import SastraIndex from '../views/sastra/Sastra.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  scrollBehavior(to, from, savedPosition) {
    return { top: 0 }
  },
  routes: [
    { 
      path: '/', 
      name: 'home', 
      component: Home 
    },
    
    // --- LOGIN ---
    { 
      path: '/login', 
      name: 'login', 
      component: Login 
    },

    // --- DETAIL BACA BERITA ---
    { 
      path: '/posts/:slug', 
      name: 'detail', 
      component: DetailBaca, 
      meta: { requiresAuth: true } 
    },

    { path: '/berita', component: Home }, 
    { path: '/artikel', name: 'artikel', component: ArtikelIndex },
    { path: '/buletin', name: 'buletin', component: BuletinIndex },
    { path: '/esai', name: 'esai', component: EsaiIndex },
    { path: '/geneologi', name: 'geneologi', component: GeneologiIndex },
    { path: '/opini', name: 'opini', component: OpiniIndex },
    { path: '/resensi', name: 'resensi', component: ResensiIndex },
    { path: '/sastra', name: 'sastra', component: SastraIndex },
  ]
})

router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth) {
    const isAuthenticated = localStorage.getItem('isLoggedIn');
    if (!isAuthenticated) {
      next('/login');
    } else {
      next();
    }
  } else {
    next();
  }
});

export default router