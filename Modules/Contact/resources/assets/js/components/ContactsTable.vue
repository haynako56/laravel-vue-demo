<template>
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="h4 mb-0">Contacts</h2>
      <button class="btn btn-success" @click="openForm()">
        <i class="bi bi-plus-lg me-1"></i> Add Contact
      </button>
    </div>

    <div class="table-responsive">
      <table class="table table-striped table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col" style="width: 100px;">Image</th>
            <th scope="col" class="text-center" style="width: 150px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="contact in contacts" :key="contact.id">
            <td>{{ contact.name }}</td>
            <td>{{ contact.email }}</td>
            <td>
              <img
                :src="contact.image ? `/storage/${contact.image}` : '/images/no-image.png'"
                alt="Contact Image"
                class="img-thumbnail"
                style="width: 60px; height: 60px; object-fit: cover;"
              />
            </td>
            <td class="text-center">
              <button
                class="btn btn-sm btn-primary me-2"
                @click="openForm(contact)"
              >
                <i class="bi bi-pencil"></i> Edit
              </button>
              <button
                class="btn btn-sm btn-danger"
                @click="deleteContact(contact.id)"
              >
                <i class="bi bi-trash"></i> Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Contact Form Modal -->
    <ContactForm
      v-if="showForm"
      :contact="selectedContact"
      @close="closeForm"
      @saved="fetchContacts"
    />
  </div>
</template>


<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import ContactForm from './ContactForm.vue'

const contacts = ref([])
const showForm = ref(false)
const selectedContact = ref(null)

function fetchContacts() {
  axios.get('/api/contacts').then(res => {
    contacts.value = res.data
  })
}

function deleteContact(id) {
  if (confirm('Delete this contact?')) {
    axios.delete(`/api/contacts/${id}`).then(fetchContacts)
  }
}

function openForm(contact = null) {
  selectedContact.value = contact
  showForm.value = true
}

function closeForm() {
  showForm.value = false
  selectedContact.value = null
}

onMounted(fetchContacts)
</script>
