import { createRouter, createWebHistory } from 'vue-router';
// ページコンポーネントをインポートする
import RecipePost from '../views/components/RecipePost.vue';
import RecipeEdit from '../views/components/RecipeEdit.vue';

// パスとコンポーネントのマッピング
const routes = [
  { 
      path: '/recipe-post', 
      name: 'recipe-post',
      component: RecipePost 
  },
  { 
      path: '/recipe-edit',
      name: 'recipe-edit',
      component: RecipeEdit,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// VueRouterインスタンスをエクスポートする
// app.jsでインポートするため
export default router;