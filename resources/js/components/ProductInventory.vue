<template>
  <v-container>
    <v-card>
      <v-card-title>Product Inventory</v-card-title>
      <v-card-text>
        <v-file-input
          v-model="file"
          label="Upload Excel File"
          accept=".xlsx,.xls"
          @change="uploadFile"
          :loading="uploading"
        ></v-file-input>

        <v-data-table
          :headers="headers"
          :items="products"
          :loading="loading"
          class="elevation-1"
        >
          <template v-slot:item.quantity="{ item }">
            <v-chip :color="getQuantityColor(item.quantity)">
              {{ item.quantity }}
            </v-chip>
          </template>
        </v-data-table>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script setup>
import axios from 'axios';
import { onMounted, ref } from 'vue';

const products = ref([]);
const loading = ref(false);
const uploading = ref(false);
const file = ref(null);

const headers = [
  { title: 'Product ID', value: 'product_id' },
  { title: 'Category', value: 'category' },
  { title: 'Brand', value: 'brand' },
  { title: 'Model', value: 'model' },
  { title: 'Quantity', value: 'quantity' },
];

const fetchProducts = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/products');
    products.value = response.data;
  } catch (error) {
    console.error('Error fetching products:', error);
  } finally {
    loading.value = false;
  }
};

const uploadFile = async () => {
  if (!file.value) return;

  uploading.value = true;
  const formData = new FormData();
  formData.append('file', file.value[0]); // Note the [0] to get the first file
  
  try {
    const response = await axios.post('/api/products/upload', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
        'Accept': 'application/json'
      }
    });
    await fetchProducts();
    console.log('Upload successful:', response.data);
  } catch (error) {
    console.error('Upload error:', error.response?.data || error.message);
    // Show error to user
    alert(error.response?.data?.message || 'Error uploading file');
  } finally {
    uploading.value = false;
    file.value = null;
  }
};

const getQuantityColor = (quantity) => {
  if (quantity < 5) return 'red';
  if (quantity < 10) return 'orange';
  return 'green';
};

onMounted(fetchProducts);
</script>