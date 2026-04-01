<template>
    <div class="tian-form-bpm">
        <form class="tian-form" @submit.prevent="onSubmit">
            <div>
                <input
                    v-model="form.fullname"
                    class="tian-form-control"
                    placeholder="Nombres"
                    type="text"
                />
                <p v-if="errors.fullname" class="tian-form-error">Este campo es requerido.</p>
            </div>

            <div>
                <input
                    v-model="form.lastname"
                    class="tian-form-control"
                    placeholder="Apellidos"
                    type="text"
                />
                <p v-if="errors.lastname" class="tian-form-error">Este campo es requerido.</p>
            </div>

            <div>
                <p class="tian-section-title">Tipo de documento</p>
                <div class="tian-options-grid">
                    <label
                        v-for="option in options"
                        :key="option.value"
                        class="tian-option-chip"
                        :class="form.documentType === option.value ? 'tian-option-chip-active' : 'tian-option-chip-idle'"
                    >
                        <input v-model="form.documentType" class="tian-sr-only" :value="option.value" type="radio" />
                        <span>{{ option.text }}</span>
                    </label>
                </div>
                <p v-if="errors.documentType" class="tian-form-error">Este campo es requerido.</p>
            </div>

            <div>
                <input
                    v-model="form.documentNumber"
                    class="tian-form-control"
                    placeholder="No. de documento (sin puntos ni comas)"
                    type="text"
                />
                <p v-if="errors.documentNumber" class="tian-form-error">Este campo es requerido.</p>
            </div>

            <div>
                <input
                    v-model="form.phone"
                    class="tian-form-control"
                    placeholder="Número de contacto"
                    type="tel"
                />
                <p v-if="errors.phone" class="tian-form-error">Este campo es requerido.</p>
            </div>

            <div>
                <input
                    v-model="form.email"
                    class="tian-form-control"
                    placeholder="Correo electrónico"
                    type="email"
                />
                <p v-if="errors.email" class="tian-form-error">Este campo es requerido.</p>
                <p v-else-if="errors.invalidEmail" class="tian-form-error">Este campo debe ser un correo electrónico válido.</p>
            </div>

            <div>
                <label class="tian-form-label" for="avatar">
                    Sube una foto tuya (preferiblemente en fondo blanco) para poner en tu carnet.
                </label>
                <input
                    id="avatar"
                    accept=".jpg,.png,.jpeg"
                    class="tian-form-control-file"
                    type="file"
                    @change="onAvatarChange"
                />
                <small v-if="form.avatar" class="tian-form-file-name">Archivo seleccionado: {{ form.avatar.name }}</small>
                <small class="tian-form-help">Formatos admitidos (.jpg, .png, .jpeg) | Tamaño máximo 2MB</small>
                <p v-if="errors.avatar" class="tian-form-error">Este campo es requerido.</p>
            </div>

            <div v-if="uploadProgress > 0" class="tian-upload-progress">
                <p class="tian-upload-progress-label">Cargando archivos ({{ uploadProgress }}%)</p>
                <div class="tian-upload-progress-track">
                    <div
                        class="tian-upload-progress-fill"
                        :class="uploadProgress === 100 ? 'tian-upload-progress-fill-complete' : 'tian-upload-progress-fill-active'"
                        :style="{ width: `${uploadProgress}%` }"
                    ></div>
                </div>
            </div>

            <button
                class="tian-submit-btn"
                :disabled="isSending"
                type="submit"
            >
                <span v-if="isSending">Enviando...</span>
                <span v-else>ENVIAR</span>
            </button>
        </form>

        <div v-if="avatarErrors.length > 0" class="tian-alert-stack">
            <div
                v-for="(error, index) in avatarErrors"
                :key="index"
                class="tian-alert tian-alert-error"
            >
                {{ error }}
            </div>
        </div>

        <div v-if="showError" class="tian-alert tian-alert-error">
            Ha ocurrido un error. Por favor, vuelve a intentarlo.
        </div>

        <div v-if="showAlert" class="tian-alert tian-alert-success">
            <p class="tian-alert-title">Muy bien.</p>
            <p>
                Tus datos se han enviado con éxito. Revisaremos tus datos y pronto recibirás tu carnet como manipulador de alimentos vía correo electrónico.
            </p>
        </div>

        <div v-if="showDuplicate" class="tian-alert tian-alert-info">
            <p class="tian-alert-title">Muy bien.</p>
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

<style scoped>
.tian-form-bpm {
    --tian-white: #ffffff;
    --tian-text-muted: #334155;
    --tian-border: #cbd5e1;
    --tian-brand: #0275d8;
    --tian-action: #7bb929;

    border: 1px solid #e2e8f0;
    border-radius: 1rem;
    background: var(--tian-white);
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.06);
    padding: 1.5rem;
    color: #0f172a;
}

.tian-form-bpm *,
.tian-form-bpm *::before,
.tian-form-bpm *::after {
    box-sizing: border-box;
}

.tian-form-bpm .tian-form {
    display: grid;
    gap: 1rem;
}

.tian-form-bpm .tian-form-control {
    width: 100%;
    border: 1px solid var(--tian-border);
    border-radius: 0.75rem;
    background: var(--tian-white);
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    color: #0f172a;
    outline: none;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.tian-form-bpm .tian-form-control::placeholder {
    color: #94a3b8;
}

.tian-form-bpm .tian-form-control:focus {
    border-color: var(--tian-brand);
    box-shadow: 0 0 0 2px rgba(2, 117, 216, 0.25);
}

.tian-form-bpm .tian-form-error {
    margin-top: 0.25rem;
    font-size: 0.875rem;
    color: #dc2626;
}

.tian-form-bpm .tian-section-title {
    margin: 0 0 0.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--tian-text-muted);
}

.tian-form-bpm .tian-options-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.tian-form-bpm .tian-option-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    border-radius: 999px;
    border: 1px solid var(--tian-border);
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.2s ease;
}

.tian-form-bpm .tian-option-chip.tian-option-chip-idle {
    border-color: var(--tian-border);
    color: var(--tian-text-muted);
}

.tian-form-bpm .tian-option-chip.tian-option-chip-active {
    border-color: var(--tian-brand);
    background: #e6f2fb;
    color: #014680;
}

.tian-form-bpm .tian-sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}

.tian-form-bpm .tian-form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--tian-text-muted);
}

.tian-form-bpm .tian-form-control-file {
    display: block;
    width: 100%;
    border: 1px solid var(--tian-border);
    border-radius: 0.75rem;
    background: #f8fafc;
    padding: 0.4rem;
    font-size: 0.875rem;
    color: #475569;
    transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
}

.tian-form-bpm .tian-form-control-file:hover {
    background: #f1f5f9;
}

.tian-form-bpm .tian-form-control-file:focus {
    border-color: var(--tian-brand);
    box-shadow: 0 0 0 2px rgba(2, 117, 216, 0.2);
    outline: none;
}

.tian-form-bpm .tian-form-control-file::file-selector-button {
    border: 0;
    border-radius: 0.6rem;
    background: var(--tian-action);
    color: var(--tian-white);
    margin-right: 0.75rem;
    padding: 0.5rem 0.85rem;
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.tian-form-bpm .tian-form-control-file:hover::file-selector-button {
    background: #639421;
}

.tian-form-bpm .tian-form-file-name {
    display: block;
    margin-top: 0.4rem;
    font-size: 0.75rem;
    color: var(--tian-text-muted);
}

.tian-form-bpm .tian-form-help {
    display: block;
    margin-top: 0.25rem;
    font-size: 0.75rem;
    color: #64748b;
}

.tian-form-bpm .tian-upload-progress {
    display: grid;
    gap: 0.25rem;
}

.tian-form-bpm .tian-upload-progress-label {
    margin: 0;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--tian-text-muted);
}

.tian-form-bpm .tian-upload-progress-track {
    height: 0.5rem;
    width: 100%;
    border-radius: 999px;
    background: #e2e8f0;
    overflow: hidden;
}

.tian-form-bpm .tian-upload-progress-fill {
    height: 100%;
    transition: width 0.2s ease;
}

.tian-form-bpm .tian-upload-progress-fill.tian-upload-progress-fill-active {
    background: var(--tian-brand);
}

.tian-form-bpm .tian-upload-progress-fill.tian-upload-progress-fill-complete {
    background: var(--tian-action);
}

.tian-form-bpm .tian-submit-btn {
    width: 100%;
    border: 1px solid var(--tian-action);
    border-radius: 0.75rem;
    background: var(--tian-action);
    color: var(--tian-white);
    font-size: 0.875rem;
    font-weight: 600;
    padding: 0.75rem 1rem;
    cursor: pointer;
    transition: background-color 0.2s ease, opacity 0.2s ease;
}

.tian-form-bpm .tian-submit-btn:hover {
    background: #639421;
}

.tian-form-bpm .tian-submit-btn:disabled {
    cursor: not-allowed;
    opacity: 0.7;
}

.tian-form-bpm .tian-alert-stack {
    margin-top: 1rem;
    display: grid;
    gap: 0.5rem;
}

.tian-form-bpm .tian-alert {
    margin-top: 1rem;
    border: 1px solid transparent;
    border-radius: 0.75rem;
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
}

.tian-form-bpm .tian-alert.tian-alert-error {
    border-color: #fecaca;
    background: #fef2f2;
    color: #b91c1c;
}

.tian-form-bpm .tian-alert.tian-alert-success {
    border-color: #a7f3d0;
    background: #ecfdf5;
    color: #047857;
}

.tian-form-bpm .tian-alert.tian-alert-info {
    border-color: #bae6fd;
    background: #f0f9ff;
    color: #0369a1;
}

.tian-form-bpm .tian-alert-title {
    margin: 0 0 0.25rem;
    font-weight: 600;
}
</style>
