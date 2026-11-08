
<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import api from '../services/api';

const router = useRouter();

const form = ref({
    email: '',
    password: '',
});

const showPassword = ref(false);
const loading = ref(false);
const error = ref('');
const success = ref('');

const login = async () => {
    error.value = '';
    success.value = '';

    loading.value = true;

    try {
        const response = await api.post('/login', form.value);

        localStorage.setItem('auth_token', response.data.token);
        localStorage.setItem('auth_user', JSON.stringify(response.data.user));

        success.value = 'Login successful!';

        setTimeout(() => {
            router.push('/');
        }, 800);

    } catch (err) {
        error.value = err.response?.data?.message
            || 'Invalid email or password. Please try again.';
    } finally {
        loading.value = false;
    }
};
</script>

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
                <p class="eyebrow">WELCOME BACK</p>
                <h2>Sign in to your <span>account.</span></h2>
                <p>Enter your details to access your store dashboard.</p>
            </div>

            <form @submit.prevent="login">
                <div v-if="error" class="message error">
                    {{ error }}
                </div>

                <div v-if="success" class="message success">
                    {{ success }}
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

                    <div class="password-wrapper">
                        <input
                            id="password"
                            v-model="form.password"
                            :type="showPassword ? 'text' : 'password'"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required
                        />

                        <button
                            type="button"
                            class="toggle-password"
                            @click="showPassword = !showPassword"
                        >
                            {{ showPassword ? 'Hide' : 'Show' }}
                        </button>
                    </div>
                </div>

                <button type="submit" :disabled="loading">
                    {{ loading ? 'Signing in...' : 'Sign In' }}
                    <span v-if="!loading">→</span>
                </button>
            </form>

            <p class="auth-switch">
                Don't have an account?
                <RouterLink to="/register">Create Account</RouterLink>
            </p>
        </section>
    </main>
</template>

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
    border-radius: 24px;
    background: rgba(15, 27, 49, 0.92);
    box-shadow: 0 25px 80px rgba(0, 0, 0, 0.4);
    animation: cardEnter 0.8s ease both;
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
}

.auth-brand h1 {
    font-size: 17px;
    letter-spacing: 2px;
    font-weight: 800;
}

.auth-brand h1 span,
.auth-heading h2 span {
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
    transition: border-color 0.25s, box-shadow 0.25s;
}

.form-group input::placeholder {
    color: #64748b;
}

.form-group input:focus {
    border-color: #0ea5e9;
    box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.13);
}

.password-wrapper {
    position: relative;
}

.password-wrapper input {
    padding-right: 65px;
}

.toggle-password {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    width: auto;
    padding: 5px;
    margin: 0;
    background: transparent;
    color: #38bdf8;
    border: none;
    box-shadow: none;
    font-size: 12px;
    cursor: pointer;
}

button[type="submit"] {
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
    transition: box-shadow 0.25s, opacity 0.25s;
}

button[type="submit"]:hover:not(:disabled) {
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
        transform: translateY(25px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes floatGlow {
    from {
        transform: translate(0, 0);
    }
    to {
        transform: translate(45px, 35px);
    }
}

@media (max-width: 480px) {
    .auth-card {
        padding: 27px 21px;
    }
}

@media (prefers-reduced-motion: reduce) {
    *, *::before, *::after {
        animation: none !important;
        transition: none !important;
    }
}
</style>
