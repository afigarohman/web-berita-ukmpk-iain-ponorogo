<template>
  <div class="min-h-screen bg-white flex flex-col items-center pt-16 px-4 font-serif">
    
    <div class="mb-10 text-center">
      <h1 class="text-4xl md:text-5xl font-black text-black mb-2 tracking-tight">The UKM PK Times</h1>
      
      <h2 class="text-2xl font-bold text-gray-800 mt-6 mb-2">
        {{ isRegister ? 'Create your account' : 'Log in to your account' }}
      </h2>
    </div>

    <div class="w-full max-w-[400px]">
      
      <form @submit.prevent="handleSubmit" class="space-y-5">
        
        <div v-if="isRegister" class="animate-fade-in">
          <label class="block text-sm font-bold text-gray-700 mb-1 font-sans">Full Name</label>
          <input 
            v-model="form.name" 
            type="text" 
            class="w-full border border-gray-400 p-3 rounded text-lg font-sans focus:outline-none focus:border-black focus:ring-1 focus:ring-black transition"
            placeholder="John Doe"
            required
          >
        </div>

        <div>
          <label class="block text-sm font-bold text-gray-700 mb-1 font-sans">Email Address</label>
          <input 
            v-model="form.email" 
            type="email" 
            placeholder="nama@email.com"
            class="w-full border border-gray-400 p-3 rounded text-lg font-sans focus:outline-none focus:border-black focus:ring-1 focus:ring-black transition"
            required
          >
        </div>

        <div>
          <label class="block text-sm font-bold text-gray-700 mb-1 font-sans">Password</label>
          <input 
            v-model="form.password" 
            type="password" 
            class="w-full border border-gray-400 p-3 rounded text-lg font-sans focus:outline-none focus:border-black focus:ring-1 focus:ring-black transition"
            required
          >
        </div>

        <button 
          type="submit" 
          class="w-full bg-black text-white font-bold py-3.5 rounded hover:bg-gray-800 transition transform active:scale-[0.98] font-sans text-lg tracking-wide"
        >
          {{ isRegister ? 'Sign Up' : 'Log In' }}
        </button>

      </form>

      <div class="mt-6 text-center font-sans">
        <p v-if="!isRegister" class="text-gray-600">
          Don't have an account? 
          <button @click="toggleMode" class="font-bold text-black underline hover:no-underline ml-1">
            Create one
          </button>
        </p>
        <p v-else class="text-gray-600">
          Already have an account? 
          <button @click="toggleMode" class="font-bold text-black underline hover:no-underline ml-1">
            Log in
          </button>
        </p>
      </div>

      <div class="relative my-8">
        <div class="absolute inset-0 flex items-center">
          <div class="w-full border-t border-gray-300"></div>
        </div>
        <div class="relative flex justify-center text-xs uppercase tracking-widest">
          <span class="px-3 bg-white text-gray-500 font-sans">or</span>
        </div>
      </div>

      <div class="space-y-3 font-sans font-bold">
        <button 
          @click="handleGoogleLogin"
          type="button"
          class="w-full border border-black text-black py-3 rounded hover:bg-gray-50 transition flex items-center justify-center gap-2 group"
        >
          <svg class="w-5 h-5" viewBox="0 0 24 24">
            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
          </svg>
          <span>Continue with Google</span>
        </button>

        <button type="button" class="w-full border border-black text-black py-3 rounded hover:bg-gray-50 transition flex items-center justify-center gap-2">
          <span class="text-xl"></span> Continue with Apple
        </button>
      </div>

      <p class="mt-8 text-center text-[11px] text-gray-500 font-sans leading-relaxed">
        By continuing, you agree to the Terms of Sale, Terms of Service, and Privacy Policy.
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
// import axios from 'axios'; // Nanti dipakai untuk connect ke backend

const router = useRouter();

// State Mode: false = Login, true = Register
const isRegister = ref(false);

// Data Form
const form = reactive({
  name: '',
  email: '',
  password: ''
});

// Fungsi Switch Mode
const toggleMode = () => {
  isRegister.value = !isRegister.value;
  // Reset form saat ganti mode biar bersih
  form.name = '';
  form.email = '';
  form.password = '';
};

// Fungsi Submit Form (Login / Register Manual)
const handleSubmit = async () => {
  if (isRegister.value) {
    // --- LOGIKA REGISTRASI ---
    console.log("Mendaftar:", form);
    
    // SIMULASI: Nanti ganti dengan axios.post('/api/register', form)
    alert(`Halo ${form.name}! Akun berhasil dibuat. Silakan login.`);
    toggleMode(); // Pindah ke mode login otomatis
    
  } else {
    // --- LOGIKA LOGIN ---
    console.log("Login:", form);
    
    // SIMULASI: Nanti ganti dengan axios.post('/api/login', form)
    if (form.email && form.password) {
      localStorage.setItem('isLoggedIn', 'true');
      localStorage.setItem('userEmail', form.email);
      router.push('/'); // Pindah ke beranda
    }
  }
};

// Fungsi Login Google
const handleGoogleLogin = () => {
  // CATATAN PENTING:
  // Agar ini berfungsi sungguhan, kamu harus install "Laravel Socialite" di Backend
  // dan setup Google Cloud Console.
  
  // Untuk sekarang, kita simulasi redirect saja:
  const confirmGoogle = confirm("Fitur Google Login membutuhkan setup Backend (Laravel Socialite). Apakah kamu ingin mensimulasikan login berhasil?");
  
  if (confirmGoogle) {
    localStorage.setItem('isLoggedIn', 'true');
    localStorage.setItem('userEmail', 'user@google.com'); // Email dummy
    router.push('/');
  } else {
    // Jika backend sudah siap, kodenya biasanya seperti ini:
    // window.location.href = 'http://127.0.0.1:8000/auth/google/redirect';
  }
};
</script>

<style scoped>
/* Animasi halus saat form Nama muncul */
.animate-fade-in {
  animation: fadeIn 0.5s ease-in-out;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>