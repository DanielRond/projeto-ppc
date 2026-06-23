import './bootstrap';
import { createApp } from 'vue';
import Login from './components/Login.vue';

const app = createApp({});

// Registra o componente para ser usado no seu blade
app.component('login-component', Login);

// Monta a aplicação no elemento <div id="app"> do seu blade
app.mount('#app');
