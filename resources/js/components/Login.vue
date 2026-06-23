<script setup>
import { ref } from 'vue';
import axios from 'axios';

const email = ref('unidade@teste.com');
const password = ref('12345678');
const error = ref('');
const loading = ref(false);

const login = async () => {
    loading.value = true;
    error.value = '';

    try {
        const response = await axios.post('/api/login', {
            email: email.value,
            password: password.value
        });

        // Armazena o token para uso posterior
        localStorage.setItem('token', response.data.access_token);

        // ... dentro da função login, após salvar o token:
        localStorage.setItem('token', response.data.access_token);

        // Redireciona o navegador para a rota /dashboard
        window.location.href = '/dashboard';

    } catch (e) {
        if (e.response && e.response.status === 401) {
            error.value = 'Email ou senha incorretos.';
        } else {
            error.value = 'Ocorreu um erro ao conectar com o servidor.';
        }
    } finally {
        loading.value = false;
    }
};
</script>

<template>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-md p-8 bg-white rounded-lg shadow-md">
            <h2 class="mb-6 text-2xl font-bold text-center text-gray-800">Login PPC</h2>

            <form @submit.prevent="login">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input
                        v-model="email"
                        type="email"
                        required
                        class="w-full p-2 mt-1 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500"
                    >
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">Senha</label>
                    <input
                        v-model="password"
                        type="password"
                        required
                        class="w-full p-2 mt-1 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500"
                    >
                </div>

                <button
                    type="submit"
                    :disabled="loading"
                    class="w-full px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700 disabled:bg-blue-300"
                >
                    {{ loading ? 'Entrando...' : 'Entrar' }}
                </button>
            </form>

            <p v-if="error" class="mt-4 text-sm text-center text-red-600">
                {{ error }}
            </p>
        </div>
    </div>
</template>

<style scoped>
/* O Tailwind já estiliza tudo, então aqui fica limpo */
</style>
