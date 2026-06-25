<script setup>
import { ref, onMounted } from 'vue';
import api from '../api';
import CourseEditModal from './CourseEditModal.vue';

const courses = ref([]);
const loading = ref(true);
const error = ref(null);

// Estados para controle do Modal de Edição
const isModalOpen = ref(false);
const selectedCourseId = ref(null);

const fetchCourses = async () => {
    loading.value = true;
    error.value = null;
    try {
        const response = await api.get('/courses');
        courses.value = response.data;
    } catch (e) {
        error.value = 'Não foi possível carregar os cursos.';
        console.error(e);
    } finally {
        loading.value = false;
    }
};

// Lógica para abrir o modal
const openEditModal = (id) => {
    selectedCourseId.value = id;
    isModalOpen.value = true;
};

// Lógica para Excluir
const deleteCourse = async (id) => {
    if (!confirm('Tem certeza que deseja excluir esta proposta? Esta ação não pode ser desfeita.')) {
        return;
    }

    try {
        await api.delete(`/courses/${id}`);
        alert('Curso excluído com sucesso!');
        fetchCourses();
    } catch (e) {
        console.error(e);
        alert('Erro ao excluir o curso.');
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
                    <td class="px-6 py-4 flex gap-2">
                        <button
                            @click="openEditModal(course.id)"
                            class="text-blue-600 hover:text-blue-900 font-medium">
                            Editar
                        </button>
                        <button
                            @click="deleteCourse(course.id)"
                            class="text-red-600 hover:text-red-900 font-medium">
                            Excluir
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>

            <div v-if="courses.length === 0" class="p-6 text-center text-gray-500">
                Nenhuma proposta encontrada.
            </div>
        </div>

        <CourseEditModal
            v-if="isModalOpen"
            :course-id="selectedCourseId"
            @close="isModalOpen = false"
            @updated="fetchCourses"
        />
    </div>
</template>
