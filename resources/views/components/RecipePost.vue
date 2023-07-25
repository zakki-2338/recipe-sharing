<template>
  <div>
    <div v-if="errors">
      <p v-for="errorMessage in errors" :key="errorMessage" class="alert alert-error mb-4 p-4 block text-center"><i class="fas fa-times-circle"></i>{{ errorMessage }}</p>
    </div>
    <form @submit.prevent="submitForm" enctype="multipart/form-data">
      <div class="mt-10 mx-auto mb-10 w-11/12 md:w-2/3 bg-[#FFFFFF] outline outline-2 outline-gray-300">
        <div class="mx-auto pt-10 pb-20 mb-10 w-4/5">
  
          <div>
            <p class="text-xl font-semibold text-[#649CAB]">料理名</p>
            <input class="w-full outline outline-2 outline-gray-500" v-model="recipeName" placeholder="料理名">
          </div>
  
          <div class="mt-6">
            <p class="text-lg font-semibold">料理写真</p>
            <p>※登録可能なファイル形式は「JPEG」のみです</p>
            <input class="block w-full h-8 outline outline-2 outline-gray-500" type="file" @change="handleFileChange" accept="image/jpeg">
            <div class="image-preview-wrapper">
              <img v-if="recipeImagePreview" :src="recipeImagePreview" alt="プレビュー画像">
            </div>
          </div>
  
          <div class="mt-6">
            <p class="text-lg font-semibold">材料</p>
            <div v-for="(ingredient, index) in ingredients" :key="index" class="flex mt-2">
              <input v-model="ingredient.name" type="text" class="block w-1/2 outline outline-2 outline-gray-500" placeholder="材料・調味料">
              <input v-model="ingredient.quantity" type="text" class="block ml-3 w-1/2 outline outline-2 outline-gray-500" placeholder="分量">
              <button v-if="ingredients.length > 1" @click="removeIngredient(index)" type="button" class="remove-row-button ml-3 tex-lg cursor-pointer">
                X
              </button>
            </div>
            <button @click="addIngredient" type="button" class="add-row-button mt-4 btn">行の追加</button>
          </div>
  
          <div class="mt-6">
            <p class="text-lg font-semibold">料理工程</p>
            <div v-for="(cookingProcess, index) in cookingProcesses" :key="index" class="flex mt-2">
              <p>{{ index + 1 }}.</p>
              <input v-model="cookingProcess.process" type="text" class="block ml-3 w-full outline outline-2 outline-gray-500">
              <button v-if="cookingProcesses.length > 1" @click="removeCookingProcess(index)" type="button" class="remove-row-button ml-3 tex-lg cursor-pointer">
                X
              </button>
            </div>
            <button @click="addCookingProcess" type="button" class="add-row-button mt-4 btn">行の追加</button>
          </div>
  
          <div class="mt-6 text-center">
            <button class="submit-button btn" type="submit">
              投稿
            </button>
          </div>
  
        </div>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      recipeName: '',
      recipeImage: null,
      ingredients: [{ name: '', quantity: '' }],
      cookingProcesses: [{ process: '' }],
      recipeImagePreview: '',
      errors: []
    };
  },

  methods: {
    handleFileChange(event) {
      this.recipeImage = event.target.files[0];
      this.previewImage();
    },

    previewImage() {
      const reader = new FileReader();
      reader.onload = (event) => {
        this.recipeImagePreview = event.target.result;
      };
      reader.readAsDataURL(this.recipeImage);
    },

    addIngredient() {
      this.ingredients.push({ name: '', quantity: '' });
    },

    removeIngredient(index) {
      if (this.ingredients.length > 1) {
        this.ingredients.splice(index, 1);
      }
    },

    addCookingProcess() {
      this.cookingProcesses.push({ process: '' });
    },

    removeCookingProcess(index) {
      if (this.cookingProcesses.length > 1) {
        this.cookingProcesses.splice(index, 1);
      }
    },

    submitForm() {
      console.log(this.recipeImage);

      // const ingredientsName = this.ingredients.map(ingredient => ingredient.name);
      // const ingredientsQuantity = this.ingredients.map(ingredient => ingredient.quantity);

      // /*global axios*/
      // // フォームデータを送信する（例: axiosを使用）
      // axios.post('recipe-post-example', { 
      //       recipe_name: this.recipeName,
      //       recipe_image: this.recipeImage,
      //       ingredients_name: ingredientsName,
      //       ingredients_quantity: ingredientsQuantity,
      //       cooking_process: this.cookingProcess,
      //   },)

      const formData = new FormData();
      formData.append("recipe_name", this.recipeName);

      // 材料のデータをフォームデータに追加
      this.ingredients.forEach((ingredient, index) => {
        formData.append("ingredients_name[]", ingredient.name);
        formData.append("ingredients_quantity[]", ingredient.quantity);
      });

      // 料理工程のデータをフォームデータに追加
      this.cookingProcesses.forEach((cookingProcess, index) => {
        formData.append("cookingProcesses_process[]", cookingProcess.process);
      });      

      // レシピ画像が選択されている場合にのみフォームデータに追加
      if (this.recipeImage) {
        formData.append("recipe_image", this.recipeImage);
      }
    
      /*global axios*/
      axios
        .post("recipe-post", formData)
        .then(response => {
          console.log('ok');
          window.location.href = "/top";
        })
        .catch(error => {
          console.log('no');
          this.errors = Object.values(error.response.data.errors).flat();
        });
    },
  },
};
</script>

<style scoped>
.image-preview-wrapper {
  width: 200px;
  height: 200px;
  background-color: #DCDCDC;
}

.image-preview-wrapper img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.add-row-button {
  color: black;
  background-color: #DCDCC8;
  border: none;
}

.add-row-button:hover {
  background-color: #C8C8B4;
}

.remove-row-button:hover {
  color: #DCDCDC;
}

.submit-button {
  background-color: #282896;
  border: none;
}

.submit-button:hover {
  background-color: #3C3CAA;
}
</style>