<template>
  <div class="position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-flex justify-content-center align-items-center">
    <div class="bg-white rounded shadow p-4" style="width: 400px;">
      <h5 class="fw-bold mb-3">
        {{ contact ? 'Edit Contact' : 'Add Contact' }}
      </h5>

      <form @submit.prevent="saveContact" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label">Name</label>
          <input
            v-model="form.name"
            type="text"
            class="form-control"
            placeholder="Enter name"
            required
          />
        </div>

        <div class="mb-3">
          <label class="form-label">Email</label>
          <input
            v-model="form.email"
            type="email"
            class="form-control"
            placeholder="Enter email"
            required
          />
        </div>

        <div class="mb-3">
          <label class="form-label">Image</label>
          <input
            type="file"
            class="form-control"
            @change="handleFileUpload"
            accept="image/*"
          />
          <div v-if="preview" class="mt-2">
            <img :src="preview" alt="Preview" class="img-thumbnail" style="max-height: 120px;" />
          </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
          <button type="button" class="btn btn-outline-secondary" @click="$emit('close')">
            Cancel
          </button>
          <button type="submit" class="btn btn-success">
            {{ contact ? 'Update' : 'Save' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import axios from 'axios'

const props = defineProps({ contact: Object })
const emit = defineEmits(['close', 'saved'])

const form = ref({ name: '', email: '', image: null })
const preview = ref(null)

watch(
  () => props.contact,
  (val) => {
    form.value = val ? { ...val, image: null } : { name: '', email: '', image: null }
    preview.value = val?.image ? `/storage/${val.image}` : null
  },
  { immediate: true }
)

function handleFileUpload(event) {
  const file = event.target.files[0]
  form.value.image = file
  preview.value = URL.createObjectURL(file)
}

function saveContact() {
  const method = props.contact ? 'post' : 'post'
  const url = props.contact ? `/api/contacts/${props.contact.id}?_method=PUT` : '/api/contacts'

  const formData = new FormData()
  formData.append('name', form.value.name)
  formData.append('email', form.value.email)
  if (form.value.image) formData.append('image', form.value.image)

  axios.post(url, formData, {
    headers: { 'Content-Type': 'multipart/form-data' }
  }).then(() => {
    emit('saved')
    emit('close')
  })
}
</script>
