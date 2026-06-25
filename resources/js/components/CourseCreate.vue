<script setup>
import { ref } from 'vue';
import api from '../api';

const emit = defineEmits(['course-created']);

const form = ref({
    nome: '',
    carga_horaria_total: '',
    quantidade_semestres: '',
    justificativa: '',
    impacto_social: '',
    disciplinas: [{ nome: '', carga_horaria: '', semestre: 1 }]
});

const addDisciplina = () => {
    form.value.disciplinas.push({ nome: '', carga_horaria: '', semestre: 1 });
};

const removeDisciplina = (index) => {
    if (form.value.disciplinas.length > 1) form.value.disciplinas.splice(index, 1);
};

const submit = async () => {
    try {
        await api.post('/courses', form.value);

        alert('Proposta submetida com sucesso!');

        // Reset do form
        form.value = {
            nome: '',
            carga_horaria_total: '',
            quantidade_semestres: '',
            justificativa: '',
            impacto_social: '',
            disciplinas: [{ nome: '', carga_horaria: '', semestre: 1 }]
        };

        // Apenas emite o sinal. Não toca em 'window' aqui!
        emit('course-created');

    } catch (e) {
        console.error(e);
        const errorMessage = e.response?.data?.message || 'Erro ao salvar. Verifique se preencheu todos os campos.';
        alert(errorMessage);
    }
};
</script>

<template>
    <div class="bg-white p-6 rounded shadow mb-6">
        <h3 class="text-lg font-bold mb-4">Nova Proposta de Curso</h3>

        <form @submit.prevent="submit" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input v-model="form.nome" placeholder="Nome do Curso" class="border p-2 rounded" required>
                <input v-model="form.carga_horaria_total" type="number" placeholder="Carga Horária Total" class="border p-2 rounded" required>
                <input v-model="form.quantidade_semestres" type="number" placeholder="Semestres" class="border p-2 rounded" required>
            </div>
            <textarea v-model="form.justificativa" placeholder="Justificativa" class="w-full border p-2 rounded" required></textarea>
            <textarea v-model="form.impacto_social" placeholder="Impacto Social" class="w-full border p-2 rounded" required></textarea>

            <div class="border-t pt-4">
                <h4 class="font-semibold mb-2">Disciplinas</h4>
                <div v-for="(d, index) in form.disciplinas" :key="index" class="flex gap-2 mb-2">
                    <input v-model="d.nome" placeholder="Nome Disc." class="border p-1 rounded flex-1" required>
                    <input v-model="d.carga_horaria" type="number" placeholder="CH" class="border p-1 rounded w-20" required>
                    <input v-model="d.semestre" type="number" placeholder="Sem." class="border p-1 rounded w-20" required>
                    <button type="button" @click="removeDisciplina(index)" class="text-red-500">X</button>
                </div>
                <button type="button" @click="addDisciplina" class="text-sm bg-gray-200 px-2 py-1 rounded">+ Adicionar Disciplina</button>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded font-bold hover:bg-blue-700">
                Submeter Proposta
            </button>
        </form>
    </div>
</template>
