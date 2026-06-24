import './bootstrap';
import { createApp } from 'vue';
import Login from './components/Login.vue';
import CourseList from './components/CourseList.vue';

const app = createApp({});

// 1. Registre todos os seus componentes ANTES de montar
app.component('login-component', Login);
app.component('course-list', CourseList);

// 2. Monte a aplicação (Sempre deve ser a última instrução)
app.mount('#app');
