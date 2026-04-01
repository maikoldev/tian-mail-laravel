<template>
    <section class="mx-auto mt-8 max-w-2xl px-4 sm:px-6 lg:px-8">
        <div class="card-surface">
            <h1 class="mb-4 text-2xl font-bold text-slate-900">Validar mi certificación</h1>
            <form class="space-y-4" @submit.prevent="onSubmit">
                <div>
                    <label class="form-label" for="certificate-id">
                        Número del certificado o cédula
                    </label>
                    <input
                        id="certificate-id"
                        v-model="certificateId"
                        class="form-control"
                        placeholder="000123456789"
                        required
                        inputmode="numeric"
                        type="text"
                    />
                </div>

                <button class="btn btn-action" type="submit">
                    Validar
                </button>
            </form>

            <div v-if="data" class="mt-4 rounded-xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm text-sky-700">
                <p>
                    {{ data?.message || '-' }}
                    <br />
                    Su número de certificado es: {{ data?.certificate?.certificate_number || '-' }}
                </p>
            </div>

            <div v-if="errors" class="mt-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700">
                <p>{{ errors?.message || '-' }}</p>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref } from 'vue';

const certificateId = ref('');
const data = ref(null);
const errors = ref(null);

const onSubmit = async () => {
    data.value = null;
    errors.value = null;

    const id = String(certificateId.value || '').trim();

    if (!id) {
        errors.value = { message: 'Ingresa un número de certificado o cédula válido.' };
        return;
    }

    try {
        const response = await axios.get(`/certificates/validation/${encodeURIComponent(id)}`);
        data.value = response.data;
    } catch (error) {
        errors.value = error?.response?.data || { message: 'No fue posible validar el certificado.' };
    }
};
</script>
