<template>
    <div>
        <label>
            <input
                :id="id"
                type="file"
                multiple
                :required="isRequired"
                @change="onChange"
            />
        </label>

        <div v-if="loading" class="mt-2">
            <img src="/svg/admin/nova-loader.svg" alt="Загрузка" class="flex-none w-8 h-8">
        </div>

        <div v-if="uploadedFiles.length" class="mt-2">
            <h6 class="text-sm mb-1">Загруженные файлы</h6>
            <table>
                <tr v-for="file in uploadedFiles" :key="file.path" class="hover:bg-gray-200">
                    <td class="pr-10 py-1">
                        {{ file.name }}
                    </td>

                    <td class="px-2 py-1">
                        <button
                            type="button"
                            title="Удалить файл"
                            class="ml-auto"
                            @click="deleteFile(file)"
                        >
                            <svg class="inline-block w-4 h-4 fill-current text-gray-700" viewBox="0 0 24 24"><path d="M8 6V4c0-1.1.9-2 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8H3a1 1 0 1 1 0-2h5zM6 8v12h12V8H6zm8-2V4h-4v2h4zm-4 4a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0v-6a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0v-6a1 1 0 0 1 1-1z"/></svg>
                        </button>
                    </td>
                </tr>
            </table>
        </div>

        <input
            v-for="file in uploadedFiles"
            type="hidden"
            :name="name"
            :value="file.path"
        />
    </div>
</template>

<script>
    import axios from 'axios'
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

    export default {
        props: {
            id: {
                required: false
            },

            name: {
                required: true
            },

            required: {
                type: Boolean,
                default: false
            }
        },

        data() {
            return {
                loading: false,
                uploadedFiles: []
            }
        },

        computed: {
            isRequired() {
                if (! this.required) {
                    return false
                }

                return this.uploadedFiles.length === 0
            }
        },

        methods: {
            onChange({ target }) {
                if (target.files.length === 0) {
                    return
                }

                let form = new FormData()
                Array.from(target.files).forEach(file => form.append('files[]', file))

                this.loading = true
                axios.post('/file-uploads', form, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                }).then(response => {
                    target.value = ''
                    this.uploadedFiles = this.uploadedFiles.concat(response.data.files)
                    alert('Upload complete')
                }).catch(error => {
                    target.value = ''
                    alert('Upload error')
                })
                .finally(() => this.loading = false)
            },

            deleteFile(file) {
                let index = this.uploadedFiles.findIndex(uploadedFile => file.path == uploadedFile.path)

                if (index !== -1) {
                    this.uploadedFiles.splice(index, 1)

                    axios.delete('/file-uploads', {
                        data: {
                            files: [ file.path ]
                        }
                    }).catch(() => alert('Something went wrong while removing file from server'))
                }
            }
        }
    }
</script>
