<script setup>
import { ref, onMounted } from 'vue';
import api from '../api';

const props = defineProps(['courseId']);
const emit = defineEmits(['close', 'updated']);

const form = ref({
    nome: '',
    carga_horaria_total: '',
    quantidade_semestres: '',
    justificativa: '',
    impacto_social: '',
    status: '',
    disciplinas: []
});

const loading = ref(true);

onMounted(async () => {
    try {
        const response = await api.get(`/courses/${props.courseId}`);
        form.value = response.data;
    } catch (e) {
        alert('Erro ao carregar dados do curso.');
        emit('close');
    } finally {
        loading.value = false;
    }
});

const updateCourse = async () => {
    try {
        await api.put(`/courses/${props.courseId}`, form.value);
        alert('Curso atualizado com sucesso!');
        emit('updated');
        emit('close');
    } catch (e) {
        // AQUI ESTÁ O SEGREDO: mostrar o erro real do Laravel
        if (e.response && e.response.status === 422) {
            const errors = e.response.data.errors;
            // Pega todos os erros e monta uma frase legível
            let message = 'Erro de validação:\n';
            for (const key in errors) {
                message += `- ${errors[key][0]}\n`;
            }
            alert(message);
        } else {
            alert('Erro ao salvar: ' + (e.response?.data?.message || 'Erro desconhecido'));
        }
        console.error("Erro detalhado:", e.response?.data);
    }
};
</script>

<template>
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white p-6 rounded shadow-lg w-full max-w-2xl max-h-screen overflow-y-auto">
            <div class="flex justify-between mb-4">
                <h3 class="text-xl font-bold">Editar Curso</h3>
                <button @click="$emit('close')" class="text-gray-500 hover:text-gray-700">Fechar</button>
            </div>

            <div v-if="loading" class="text-center py-10">Carregando dados...</div>

            <form v-else @submit.prevent="updateCourse" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input v-model="form.nome" placeholder="Nome do Curso" class="border p-2 rounded w-full" required>
                    <input v-model="form.carga_horaria_total" type="number" placeholder="Carga Horária Total" class="border p-2 rounded w-full" required>
                    <input v-model="form.quantidade_semestres" type="number" placeholder="Semestres" class="border p-2 rounded w-full" required>

                    <select v-model="form.status" class="border p-2 rounded w-full" required>
                        <option value="" disabled>Selecione o Status</option>
                        <option value="em-avaliacao">Em Avaliação</option>
                        <option value="recomendacao-ajuste">Recomendação de Ajuste</option>
                        <option value="decisao-final">Decisão Final</option>
                    </select>
                </div>

                <textarea v-model="form.justificativa" placeholder="Justificativa" class="w-full border p-2 rounded" required></textarea>
                <textarea v-model="form.impacto_social" placeholder="Impacto Social" class="w-full border p-2 rounded" required></textarea>

                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" @click="$emit('close')" class="px-4 py-2 bg-gray-300 rounded">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</template>
