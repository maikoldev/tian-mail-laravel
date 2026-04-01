<template>
    <section class="mx-auto my-8 max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="card-surface overflow-hidden">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-slate-900">Certificados</h1>
                <button
                    class="btn btn-outline"
                    :disabled="isRefreshing"
                    :title="isRefreshing ? 'Actualizando certificados...' : 'Actualizar certificados'"
                    @click="getCertificates"
                >
                    <IconRefresh
                        :class="isRefreshing ? 'mr-2 h-4 w-4 animate-spin' : 'mr-2 h-4 w-4'"
                        aria-hidden="true"
                    />
                    <span>{{ isRefreshing ? 'Actualizando...' : 'Actualizar' }}</span>
                </button>
            </div>

            <div
                v-if="notice.message"
                class="mb-4 rounded-xl px-4 py-3 text-sm"
                :class="notice.success ? 'border border-emerald-200 bg-emerald-50 text-emerald-700' : 'border border-red-200 bg-red-50 text-red-700'"
            >
                {{ notice.message }}
            </div>

            <div class="mb-5 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div class="w-full lg:max-w-2xl lg:flex-1">
                    <label class="form-label" for="certificate-search">Buscar</label>
                    <div class="relative">
                        <input
                            id="certificate-search"
                            v-model.trim="searchTerm"
                            type="text"
                            class="form-control pr-10"
                            placeholder="Cédula, nombre, apellido o número de certificado"
                        />
                        <button
                            v-if="searchTerm"
                            type="button"
                            class="absolute right-2 top-1/2 inline-flex h-6 w-6 -translate-y-1/2 items-center justify-center rounded-full text-slate-500 transition hover:bg-slate-100 hover:text-slate-700"
                            title="Limpiar búsqueda"
                            aria-label="Limpiar búsqueda"
                            @click="searchTerm = ''"
                        >
                            <IconX class="h-4 w-4" aria-hidden="true" />
                        </button>
                    </div>
                </div>

                <div class="grid w-full gap-3 sm:grid-cols-2 lg:w-auto lg:shrink-0">
                    <div>
                        <label class="form-label" for="certificate-status">Estado</label>
                        <select
                            id="certificate-status"
                            v-model="statusFilter"
                            class="form-control w-full py-2 sm:w-44"
                        >
                            <option value="">Todos</option>
                            <option value="Generated">Generado</option>
                            <option value="Sent">Enviado</option>
                            <option value="Expired">Vencido</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label" for="certificate-user-type">Tipo de usuario</label>
                        <select
                            id="certificate-user-type"
                            v-model="userTypeFilter"
                            class="form-control w-full py-2 sm:w-44"
                        >
                            <option value="">Todos</option>
                            <option value="Particular">Particular</option>
                            <option value="Empresa">Empresa</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="table-wrap">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID Certificado</th>
                            <th>Tipo de usuario</th>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Documento</th>
                            <th>Fecha de certificación</th>
                            <th>Fecha de vencimiento</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in items" :key="item.id">
                            <td>{{ item.certificate_number }}</td>
                            <td>{{ item.user_type }}</td>
                            <td>{{ item.name }}</td>
                            <td>{{ item.lastname }}</td>
                            <td>{{ item.document_number }}</td>
                            <td>{{ item.certificate_date }}</td>
                            <td>{{ item.certificate_expiration_date }}</td>
                            <td>
                                <span class="badge" :class="statusClass(item.certificate_status)">
                                    {{ statusText(item.certificate_status) }}
                                </span>
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <a
                                        class="btn btn-sm btn-brand-soft"
                                        :href="item.certificate_url"
                                        title="Descargar"
                                        aria-label="Descargar"
                                        download
                                    >
                                        <IconDownload class="h-4 w-4" aria-hidden="true" />
                                    </a>

                                    <button
                                        v-if="item.certificate_status === 'Generated'"
                                        class="btn btn-sm btn-success-soft"
                                        :disabled="item.isRotating"
                                        title="Aprobar y enviar"
                                        aria-label="Aprobar y enviar"
                                        @click="approve(item)"
                                    >
                                        <IconMail
                                            :class="item.isRotating ? 'h-4 w-4 animate-spin' : 'h-4 w-4'"
                                            aria-hidden="true"
                                        />
                                    </button>

                                    <button
                                        v-if="item.certificate_status === 'Sent'"
                                        class="btn btn-sm btn-teal-soft"
                                        :disabled="item.isRotating"
                                        title="Reenviar"
                                        aria-label="Reenviar"
                                        @click="resend(item)"
                                    >
                                        <IconRefresh
                                            :class="item.isRotating ? 'h-4 w-4 animate-spin' : 'h-4 w-4'"
                                            aria-hidden="true"
                                        />
                                    </button>

                                    <button
                                        class="btn btn-sm btn-danger-soft"
                                        :disabled="item.isRotating"
                                        title="Eliminar"
                                        aria-label="Eliminar"
                                        @click="destroy(item)"
                                    >
                                        <IconTrash
                                            :class="item.isRotating ? 'h-4 w-4 animate-spin' : 'h-4 w-4'"
                                            aria-hidden="true"
                                        />
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr v-if="items.length === 0">
                            <td class="py-8 text-center text-slate-500" colspan="9">
                                No hay certificados disponibles.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div
                v-if="totalItems > 0"
                class="mt-4 flex flex-col gap-3 border-t border-slate-200 pt-4 text-sm text-slate-600 sm:flex-row sm:items-center sm:justify-between"
            >
                <div class="flex items-center gap-3">
                    <label class="form-label mb-0" for="per-page">Por página</label>
                    <select
                        id="per-page"
                        v-model.number="perPage"
                        class="form-control w-24"
                    >
                        <option v-for="size in perPageOptions" :key="size" :value="size">
                            {{ size }}
                        </option>
                    </select>
                    <span>
                        Mostrando {{ pageStartItem }}-{{ pageEndItem }} de {{ totalItems }}
                    </span>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <button
                        class="btn btn-sm btn-outline"
                        :disabled="currentPage === 1"
                        title="Página anterior"
                        @click="goToPage(currentPage - 1)"
                    >
                        Anterior
                    </button>

                    <button
                        v-for="page in visiblePages"
                        :key="page"
                        class="btn btn-sm"
                        :class="page === currentPage ? 'btn-brand-soft' : 'btn-outline'"
                        :title="`Ir a la página ${page}`"
                        @click="goToPage(page)"
                    >
                        {{ page }}
                    </button>

                    <button
                        class="btn btn-sm btn-outline"
                        :disabled="currentPage === totalPages"
                        title="Página siguiente"
                        @click="goToPage(currentPage + 1)"
                    >
                        Siguiente
                    </button>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { IconDownload, IconMail, IconRefresh, IconTrash, IconX } from '@tabler/icons-vue';
import { refDebounced } from '@vueuse/core';
import { computed, onMounted, reactive, ref, watch } from 'vue';

const items = ref([]);
const isRefreshing = ref(false);
const currentPage = ref(1);
const perPage = ref(10);
const perPageOptions = [5, 10, 25, 50];
const totalItems = ref(0);
const totalPages = ref(1);
const searchTerm = ref('');
const searchTermDebounced = refDebounced(searchTerm, 350);
const statusFilter = ref('');
const userTypeFilter = ref('');
const notice = reactive({
    message: '',
    success: true
});

const pageStartItem = computed(() => {
    if (totalItems.value === 0) {
        return 0;
    }

    return (currentPage.value - 1) * perPage.value + 1;
});

const pageEndItem = computed(() => Math.min(currentPage.value * perPage.value, totalItems.value));

const visiblePages = computed(() => {
    const pages = [];
    const start = Math.max(1, currentPage.value - 2);
    const end = Math.min(totalPages.value, start + 4);

    for (let page = Math.max(1, end - 4); page <= end; page += 1) {
        pages.push(page);
    }

    return pages;
});

const goToPage = (page) => {
    if (page < 1 || page > totalPages.value || page === currentPage.value) {
        return;
    }

    getCertificates(page);
};

const setNotice = (message, success = true) => {
    notice.message = message;
    notice.success = success;
};

const statusClass = (status) => {
    if (status === 'Generated') {
        return 'badge-info';
    }

    if (status === 'Sent') {
        return 'badge-success';
    }

    return 'badge-danger';
};

const statusText = (status) => {
    if (status === 'Generated') {
        return 'Generado';
    }

    if (status === 'Sent') {
        return 'Enviado';
    }

    if (status === 'Expired') {
        return 'Vencido';
    }

    return status;
};

const getCertificates = async (page = currentPage.value) => {
    isRefreshing.value = true;

    try {
        const response = await axios.get('/certificates/all', {
            params: {
                page,
                per_page: perPage.value,
                search: searchTerm.value,
                status: statusFilter.value,
                user_type: userTypeFilter.value
            }
        });

        items.value = response.data.data.map((item) => ({
            ...item,
            isRotating: false
        }));
        currentPage.value = response.data.current_page;
        totalItems.value = response.data.total;
        totalPages.value = response.data.last_page;
    } catch (error) {
        setNotice('No se pudieron cargar los certificados.', false);
    } finally {
        isRefreshing.value = false;
    }
};

const resend = async (item) => {
    item.isRotating = true;

    try {
        await axios.post(`/certificates/resend/${item.certificate_number}`);
        item.certificate_status = 'Sent';
        setNotice('El certificado ha sido reenviado.');
    } catch (error) {
        setNotice(error?.response?.data?.message || 'No se pudo reenviar el certificado.', false);
    } finally {
        item.isRotating = false;
    }
};

const approve = async (item) => {
    item.isRotating = true;

    try {
        await axios.post(`/certificates/approve/${item.certificate_number}`);
        item.certificate_status = 'Sent';
        setNotice('El certificado ha sido enviado.');
    } catch (error) {
        setNotice(error?.response?.data?.message || 'No se pudo aprobar el certificado.', false);
    } finally {
        item.isRotating = false;
    }
};

const destroy = async (item) => {
    item.isRotating = true;

    try {
        await axios.delete(`/certificates/delete/${item.id}`);
        const targetPage = items.value.length === 1 && currentPage.value > 1
            ? currentPage.value - 1
            : currentPage.value;

        setNotice('El certificado ha sido eliminado.');
        await getCertificates(targetPage);
    } catch (error) {
        setNotice(error?.response?.data?.message || 'No se pudo eliminar el certificado.', false);
    } finally {
        item.isRotating = false;
    }
};

watch(perPage, () => {
    getCertificates(1);
});

watch(statusFilter, () => {
    getCertificates(1);
});

watch(userTypeFilter, () => {
    getCertificates(1);
});

watch(searchTerm, () => {
    // Keep page index consistent while the debounced request is pending.
    currentPage.value = 1;
});

watch(searchTermDebounced, () => {
    getCertificates(1);
});

onMounted(getCertificates);
</script>
