<template>
    <div class="card-surface">
        <form class="space-y-4" @submit.prevent="onSubmit">
            <div>
                <input
                    v-model="form.fullname"
                    class="form-control"
                    placeholder="Nombres"
                    type="text"
                />
                <p v-if="errors.fullname" class="form-error">Este campo es requerido.</p>
            </div>

            <div>
                <input
                    v-model="form.lastname"
                    class="form-control"
                    placeholder="Apellidos"
                    type="text"
                />
                <p v-if="errors.lastname" class="form-error">Este campo es requerido.</p>
            </div>

            <div>
                <p class="mb-2 text-sm font-semibold text-slate-700">Tipo de documento</p>
                <div class="flex flex-wrap gap-2">
                    <label
                        v-for="option in options"
                        :key="option.value"
                        class="inline-flex cursor-pointer items-center gap-2 rounded-full border px-3 py-1.5 text-sm"
                        :class="form.documentType === option.value ? 'border-secondary-500 bg-secondary-50 text-secondary-700' : 'border-slate-300 text-slate-700'"
                    >
                        <input v-model="form.documentType" class="sr-only" :value="option.value" type="radio" />
                        <span>{{ option.text }}</span>
                    </label>
                </div>
                <p v-if="errors.documentType" class="form-error">Este campo es requerido.</p>
            </div>

            <div>
                <input
                    v-model="form.documentNumber"
                    class="form-control"
                    placeholder="No. de documento (sin puntos ni comas)"
                    type="text"
                />
                <p v-if="errors.documentNumber" class="form-error">Este campo es requerido.</p>
            </div>

            <div>
                <input
                    v-model="form.phone"
                    class="form-control"
                    placeholder="Número de contacto"
                    type="tel"
                />
                <p v-if="errors.phone" class="form-error">Este campo es requerido.</p>
            </div>

            <div>
                <input
                    v-model="form.email"
                    class="form-control"
                    placeholder="Correo electrónico"
                    type="email"
                />
                <p v-if="errors.email" class="form-error">Este campo es requerido.</p>
                <p v-else-if="errors.invalidEmail" class="form-error">Este campo debe ser un correo electrónico válido.</p>
            </div>

            <div>
                <label class="form-label" for="avatar">
                    Sube una foto tuya (preferiblemente en fondo blanco) para poner en tu carnet.
                </label>
                <input
                    id="avatar"
                    accept=".jpg,.png,.jpeg"
                    class="form-control-file"
                    type="file"
                    @change="onAvatarChange"
                />
                <small class="form-help">Formatos admitidos (.jpg, .png, .jpeg) | Tamaño máximo 2MB</small>
                <p v-if="errors.avatar" class="form-error">Este campo es requerido.</p>
            </div>

            <div v-if="uploadProgress > 0" class="space-y-1">
                <p class="text-sm font-medium text-slate-700">Cargando archivos ({{ uploadProgress }}%)</p>
                <div class="h-2 w-full rounded-full bg-slate-200">
                    <div
                        class="h-2 rounded-full transition-all"
                        :class="uploadProgress === 100 ? 'bg-action-500' : 'bg-secondary-500'"
                        :style="{ width: `${uploadProgress}%` }"
                    ></div>
                </div>
            </div>

            <button
                class="btn btn-action w-full py-3 font-semibold"
                :disabled="isSending"
                type="submit"
            >
                <span v-if="isSending">Enviando...</span>
                <span v-else>ENVIAR</span>
            </button>
        </form>

        <div v-if="avatarErrors.length > 0" class="mt-4 space-y-2">
            <div
                v-for="(error, index) in avatarErrors"
                :key="index"
                class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"
            >
                {{ error }}
            </div>
        </div>

        <div v-if="showError" class="mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            Ha ocurrido un error. Por favor, vuelve a intentarlo.
        </div>

        <div v-if="showAlert" class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            <p class="font-semibold">Muy bien.</p>
            <p>
                Tus datos se han enviado con éxito. Revisaremos tus datos y pronto recibirás tu carnet como manipulador de alimentos vía correo electrónico.
            </p>
        </div>

        <div v-if="showDuplicate" class="mt-4 rounded-xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm text-sky-700">
            <p class="font-semibold">Muy bien.</p>
            <p>
                Ya has enviado tus datos anteriormente. Tu carnet tiene vigencia de 1 año y se encuentra registrado con el siguiente identificador:
                <strong>{{ certificate?.certificate_number || '-' }}</strong>
            </p>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref } from 'vue';

const props = defineProps({
    userType: {
        type: String,
        default: 'Particular'
    }
});

const options = [
    { text: 'CC', value: 'CC' },
    { text: 'CE', value: 'CE' },
    { text: 'TI', value: 'TI' },
    { text: 'PEP', value: 'PEP' },
    { text: 'Pasaporte', value: 'Pasaporte' },
    { text: 'Otro', value: 'Otro' }
];

const createFormState = () => ({
    userType: props.userType,
    fullname: '',
    lastname: '',
    documentType: 'CC',
    documentNumber: '',
    phone: '',
    email: '',
    avatar: null
});

const form = reactive(createFormState());
const errors = reactive({
    fullname: false,
    lastname: false,
    documentType: false,
    documentNumber: false,
    phone: false,
    email: false,
    invalidEmail: false,
    avatar: false
});

const avatarErrors = ref([]);
const isSending = ref(false);
const showAlert = ref(false);
const showDuplicate = ref(false);
const showError = ref(false);
const uploadProgress = ref(0);
const certificate = ref(null);

const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

const validateForm = () => {
    errors.fullname = !form.fullname;
    errors.lastname = !form.lastname;
    errors.documentType = !form.documentType;
    errors.documentNumber = !form.documentNumber;
    errors.phone = !form.phone;
    errors.email = !form.email;
    errors.invalidEmail = Boolean(form.email) && !emailRegex.test(form.email);
    errors.avatar = !form.avatar;

    return !Object.values(errors).some(Boolean);
};

const resetForm = () => {
    Object.assign(form, createFormState());
    uploadProgress.value = 0;
};

const onAvatarChange = (event) => {
    const [file] = event.target.files;
    form.avatar = file || null;
};

const onSubmit = async () => {
    if (!validateForm()) {
        return;
    }

    isSending.value = true;
    avatarErrors.value = [];
    showAlert.value = false;
    showDuplicate.value = false;
    showError.value = false;

    const config = {
        onUploadProgress: (event) => {
            uploadProgress.value = Math.round((event.loaded / event.total) * 100);
        }
    };

    const data = { ...form };
    const params = new FormData();

    for (const [key, item] of Object.entries(data)) {
        if (item) {
            params.append(key, item);
        }
    }

    try {
        const response = await axios.post('/certificates/generate', params, config);
        resetForm();

        if (response.data.certificate) {
            certificate.value = response.data.certificate;
            showDuplicate.value = true;
            return;
        }

        showAlert.value = true;
    } catch (error) {
        const responseErrors = error?.response?.data?.errors;

        if (responseErrors?.avatar) {
            avatarErrors.value = responseErrors.avatar;
        } else {
            showError.value = true;
        }

        uploadProgress.value = 0;
    } finally {
        isSending.value = false;
    }
};
</script>
