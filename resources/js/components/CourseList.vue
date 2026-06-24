<script setup>
import { ref, onMounted } from 'vue';
import api from '../api';

const courses = ref([]);
const loading = ref(true);
const error = ref(null);

const fetchCourses = async () => {
    loading.value = true;
    error.value = null; // Limpa erros anteriores
    try {
        console.log("Buscando cursos em: /courses");
        const response = await api.get('/courses');
        console.log("Dados recebidos:", response.data);
        courses.value = response.data;
    } catch (e) {
        console.error("Erro capturado:", e);
        if (e.response && e.response.status === 401) {
            error.value = 'Sessão expirada. Faça login novamente.';
        } else {
            error.value = 'Não foi possível carregar os cursos. Verifique o console.';
        }
    } finally {
        loading.value = false;
    }
};

onMounted(fetchCourses);
</script>

<template>
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Propostas de Cursos</h2>

        <div v-if="loading" class="text-center py-10">
            <p class="text-gray-600">Carregando cursos...</p>
        </div>

        <div v-else-if="error" class="bg-red-100 text-red-700 p-4 rounded mb-4">
            {{ error }}
        </div>

        <div v-else class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full text-left">
                <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-sm font-semibold text-gray-600">Nome</th>
                    <th class="px-6 py-3 text-sm font-semibold text-gray-600">Carga Horária</th>
                    <th class="px-6 py-3 text-sm font-semibold text-gray-600">Status</th>
                    <th class="px-6 py-3 text-sm font-semibold text-gray-600">Ações</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                <tr v-for="course in courses" :key="course.id" class="hover:bg-gray-50">
                    <td class="px-6 py-4">{{ course.nome }}</td>
                    <td class="px-6 py-4">{{ course.carga_horaria_total }}h</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                            {{ course.status }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <button class="text-indigo-600 hover:text-indigo-900 font-medium">Ver Detalhes</button>
                    </td>
                </tr>
                </tbody>
            </table>

            <div v-if="courses.length === 0" class="p-6 text-center text-gray-500">
                Nenhuma proposta encontrada.
            </div>
        </div>
    </div>
</template>
