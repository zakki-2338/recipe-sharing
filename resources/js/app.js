import './bootstrap';
import { createApp } from 'vue';
import RecipePost from '../views/components/RecipePost.vue';
import RecipeEdit from '../views/components/RecipeEdit.vue';
import router from './router'; // 追加

const app = createApp({});
app.component('recipe-post', RecipePost);
app.component('recipe-edit', RecipeEdit);
app.use(router);
app.mount('#app');


import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();