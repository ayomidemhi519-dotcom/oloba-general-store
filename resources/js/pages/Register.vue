
<template>
  <main class="auth-page">
    <div class="auth-glow glow-one"></div>
    <div class="auth-glow glow-two"></div>

    <section class="auth-card">
      <div class="auth-brand">
        <div class="brand-logo">O</div>
        <h1>OLOBA <span>STORE</span></h1>
      </div>

      <div class="auth-heading">
        <p class="eyebrow">JOIN OUR STORE</p>
        <h2>Create your account<span>.</span></h2>
        <p>Get started with your OLOBA General Store account.</p>
      </div>

      <form @submit.prevent="register">
        <div class="form-group">
          <label for="name">Full Name</label>
          <input
            id="name"
            v-model="form.name"
            type="text"
            placeholder="Enter your full name"
            autocomplete="name"
            required
          />
        </div>

        <div class="form-group">
          <label for="email">Email Address</label>
          <input
            id="email"
            v-model="form.email"
            type="email"
            placeholder="Enter your email"
            autocomplete="email"
            required
          />
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input
            id="password"
            v-model="form.password"
            type="password"
            placeholder="Create a password"
            autocomplete="new-password"
            minlength="8"
            required
          />
        </div>

        <div class="form-group">
          <label for="password_confirmation">Confirm Password</label>
          <input
            id="password_confirmation"
            v-model="form.password_confirmation"
            type="password"
            placeholder="Confirm your password"
            autocomplete="new-password"
            required
          />
        </div>

        <p v-if="error" class="message error">{{ error }}</p>
        <p v-if="success" class="message success">{{ success }}</p>

        <button type="submit" :disabled="loading">
          {{ loading ? 'Creating account...' : 'Create Account' }}
          <span v-if="!loading">→</span>
        </button>
      </form>

      <p class="auth-switch">
        Already have an account?
        <RouterLink to="/login">Log in</RouterLink>
      </p>
    </section>
  </main>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '../services/api'

const router = useRouter()

const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const loading = ref(false)
const error = ref('')
const success = ref('')

const register = async () => {
  error.value = ''
  success.value = ''

  if (form.password !== form.password_confirmation) {
    error.value = 'Your passwords do not match.'
    return
  }

  loading.value = true

  try {
    await api.post('/register', form)

    success.value = 'Account created successfully! You can now log in.'

    setTimeout(() => {
      router.push('/login')
    }, 1200)
  } catch (err) {
    const errors = err.response?.data?.errors

    error.value = errors
      ? Object.values(errors).flat().join(' ')
      : err.response?.data?.message || 'Registration failed. Please try again.'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.auth-page {
  min-height: 100vh;
  display: grid;
  place-items: center;
  padding: 40px 18px;
  position: relative;
  overflow: hidden;
  background: #070d1b;
  color: #f8fafc;
}

.auth-glow {
  position: absolute;
  width: 380px;
  height: 380px;
  border-radius: 50%;
  filter: blur(100px);
  opacity: 0.35;
  pointer-events: none;
  animation: floatGlow 7s ease-in-out infinite alternate;
}

.glow-one {
  background: #087cff;
  top: -120px;
  left: -100px;
}

.glow-two {
  background: #00d5ff;
  bottom: -140px;
  right: -100px;
  animation-delay: -3s;
}

.auth-card {
  width: 100%;
  max-width: 460px;
  padding: 38px;
  position: relative;
  z-index: 1;
  border: 1px solid rgba(96, 165, 250, 0.2);
  border-radius: 24px;
  background: rgba(15, 27, 49, 0.82);
  backdrop-filter: blur(20px);
  box-shadow: 0 25px 80px rgba(0, 0, 0, 0.4);
  animation: cardEnter 0.8s cubic-bezier(.2,.8,.2,1) both;
  transition: transform 0.35s ease, box-shadow 0.35s ease;
}

.auth-card:hover {
  transform: perspective(1000px) rotateX(1deg) rotateY(-1deg) translateY(-4px);
  box-shadow: 0 30px 90px rgba(0, 119, 255, 0.2);
}

.auth-brand {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 35px;
}

.brand-logo {
  width: 42px;
  height: 42px;
  display: grid;
  place-items: center;
  border-radius: 12px;
  background: linear-gradient(135deg, #087cff, #00d5ff);
  font-size: 23px;
  font-weight: 900;
  box-shadow: 0 0 25px rgba(0, 160, 255, 0.35);
}

.auth-brand h1 {
  font-size: 17px;
  letter-spacing: 2px;
  font-weight: 800;
}

.auth-brand h1 span {
  color: #38bdf8;
}

.eyebrow {
  color: #38bdf8;
  font-size: 11px;
  font-weight: 800;
  letter-spacing: 3px;
  margin-bottom: 10px;
}

.auth-heading h2 {
  font-size: clamp(25px, 5vw, 32px);
  font-weight: 800;
  letter-spacing: -1px;
}

.auth-heading h2 span {
  color: #38bdf8;
}

.auth-heading > p:last-child {
  color: #94a3b8;
  font-size: 13px;
  margin-top: 9px;
  margin-bottom: 28px;
}

.form-group {
  margin-bottom: 18px;
}

.form-group label {
  display: block;
  color: #cbd5e1;
  font-size: 12px;
  font-weight: 600;
  margin-bottom: 8px;
}

.form-group input {
  width: 100%;
  padding: 14px 15px;
  color: white;
  background: rgba(2, 8, 23, 0.65);
  border: 1px solid #26364e;
  border-radius: 10px;
  outline: none;
  font-size: 13px;
  transition: border 0.25s, box-shadow 0.25s, transform 0.25s;
}

.form-group input::placeholder {
  color: #64748b;
}

.form-group input:focus {
  border-color: #0ea5e9;
  box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.13),
              0 0 20px rgba(14, 165, 233, 0.1);
  transform: translateY(-2px);
}

button {
  width: 100%;
  padding: 15px;
  margin-top: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
  border: none;
  border-radius: 10px;
  background: linear-gradient(110deg, #087cff, #06b6d4);
  color: white;
  font-size: 14px;
  font-weight: 800;
  cursor: pointer;
  box-shadow: 0 8px 25px rgba(8, 124, 255, 0.25);
  transition: transform 0.25s, box-shadow 0.25s;
}

button:hover:not(:disabled) {
  transform: translateY(-3px) scale(1.01);
  box-shadow: 0 12px 35px rgba(8, 124, 255, 0.4);
}

button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.auth-switch {
  margin-top: 25px;
  text-align: center;
  color: #94a3b8;
  font-size: 13px;
}

.auth-switch a {
  color: #38bdf8;
  font-weight: 700;
  text-decoration: none;
  margin-left: 4px;
}

.auth-switch a:hover {
  color: white;
}

.message {
  padding: 11px;
  margin-bottom: 14px;
  border-radius: 8px;
  font-size: 12px;
}

.error {
  color: #fca5a5;
  background: rgba(239, 68, 68, 0.12);
}

.success {
  color: #86efac;
  background: rgba(34, 197, 94, 0.12);
}

@keyframes cardEnter {
  from {
    opacity: 0;
    transform: translateY(35px) scale(0.96);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

@keyframes floatGlow {
  from {
    transform: translate(0, 0) scale(1);
  }
  to {
    transform: translate(45px, 35px) scale(1.15);
  }
}

@media (max-width: 480px) {
  .auth-card {
    padding: 27px 21px;
  }
}

@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after {
    animation-duration: 0.01ms !important;
    transition-duration: 0.01ms !important;
  }
}
</style>
