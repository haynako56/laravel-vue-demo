import { createApp } from 'vue'
import ContactsTable from './components/ContactsTable.vue'
import axios from 'axios'

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

createApp(ContactsTable).mount('#contacts-vue-app');
