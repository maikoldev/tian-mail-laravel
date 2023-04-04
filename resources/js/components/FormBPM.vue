<template>
    <div>
        <b-form @submit.prevent="onSubmit()">
            <b-form-group>
                <b-form-input
                    placeholder="Nombres"
                    type="text"
                    v-model="$v.form.fullname.$model"
                    :state="validateState('fullname')"
                />
                <b-form-invalid-feedback>Este campo es requerido.</b-form-invalid-feedback>
            </b-form-group>

            <b-form-group>
                <b-form-input
                    placeholder="Apellidos"
                    type="text"
                    v-model="$v.form.lastname.$model"
                    :state="validateState('lastname')"
                />
                <b-form-invalid-feedback>Este campo es requerido.</b-form-invalid-feedback>
            </b-form-group>

            <b-form-group>
                <b-form-radio-group
                    :options="options"
                    v-model="$v.form.documentType.$model"
                    :state="validateState('documentType')"
                >
                    <b-form-invalid-feedback :state="validateState('documentType')">
                        Este campo es requerido.
                    </b-form-invalid-feedback>
                </b-form-radio-group>
            </b-form-group>

            <b-form-group>
                <b-form-input
                    placeholder="No. de documento (sin puntos ni comas)"
                    type="text"
                    v-model="$v.form.documentNumber.$model"
                    :state="validateState('documentNumber')"
                />
                <b-form-invalid-feedback>Este campo es requerido.</b-form-invalid-feedback>
            </b-form-group>

            <b-form-group>
                <b-form-input
                    placeholder="Número de contacto"
                    type="tel"
                    v-model="$v.form.phone.$model"
                    :state="validateState('phone')"
                />
                <b-form-invalid-feedback>Este campo es requerido.</b-form-invalid-feedback>
            </b-form-group>

            <b-form-group>
                <b-form-input
                    placeholder="Correo electrónico"
                    type="email"
                    v-model="$v.form.email.$model"
                    :state="validateState('email')"
                />
                <b-form-invalid-feedback v-if="!$v.form.email.email">
                    Este campo debe un correo electronico valido.
                </b-form-invalid-feedback>
                <b-form-invalid-feedback v-if="!$v.form.email.required">
                    Este campo es requerido.
                </b-form-invalid-feedback>
            </b-form-group>

            <b-form-group
                label="Sube una foto tuya (preferiblemente en fondo blanco) para poner en tu carnet."
                label-for="avatar"
            >
                <b-form-file
                    accept=".jpg, .png, .jpeg"
                    browse-text="Buscar"
                    drop-placeholder="Sueltalo"
                    id="avatar"
                    placeholder="Seleccionar archivo"
                    v-model="$v.form.avatar.$model"
                    :state="validateState('avatar')"
                />
                <small>Formatos admitidos ( .jpg, .png, .jpeg )</small>
                <b-form-invalid-feedback>Este campo es requerido.</b-form-invalid-feedback>
            </b-form-group>

            <!-- Progress Bar -->
            <template v-if="uploadProgress > 0">
                <label>Cargando archivos</label>
                <b-progress
                    :variant="uploadProgress === 100 ? 'success' : 'info'"
                    :value="uploadProgress"
                    :max="100"
                    show-progress
                    class="mb-3"
                />
            </template>

            <!-- Submit Button -->
            <b-button block type="submit" variant="info" :disabled="isSending">
                <template v-if="isSending">
                    <b-spinner small />
                    <span class="sr-only">Loading...</span>
                </template>
                <span v-else>ENVIAR</span>
            </b-button>
        </b-form>

        <!-- Avatar Errors -->
        <b-alert :show="avatarErrors.length > 0" class="mt-4" dismissible fade variant="danger">
            El campo "Foto" debe ser un archivo de tipo imagen: jpg, png, jpeg.
        </b-alert>

        <!-- Alert Error -->
        <b-alert :show="showError" class="mt-4" dismissible fade variant="danger">
            ¡Ah ocurrido un error! Por favor, vuelve a intertarlo.
        </b-alert>

        <!-- Alert Success -->
        <b-alert :show="showAlert" class="mt-4" dismissible fade variant="success">
            <h4 class="alert-heading">¡Muy bien!</h4>
            <p>
                Tus datos se han enviado con éxito. Revisaremos tus datos y pronto recibirás tu
                carnet como manipulador de alimentos vía correo electrónico.
            </p>
        </b-alert>

        <!-- Alert Duplicate -->
        <b-alert :show="showDuplicate" class="mt-4" dismissible fade variant="info">
            <h4 class="alert-heading">¡Muy bien!</h4>
            <p>
                Ya has enviado tus datos anteriormente. Tu carnet tiene vigencia de 1 año y se
                encuentra registrado con el siguiente identificador:
                <b>{{ certificate?.certificate_number || '-' }}</b>
            </p>
        </b-alert>
    </div>
</template>

<script>
import { validationMixin } from 'vuelidate';
import { email, required } from 'vuelidate/lib/validators';

export default {
    mixins: [validationMixin],
    props: {
        userType: {
            type: String,
            default: 'Particular'
        }
    },
    data() {
        return {
            avatarErrors: [],
            form: {
                userType: this.userType,
                fullname: '',
                lastname: '',
                documentType: 'CC',
                documentNumber: '',
                phone: '',
                email: '',
                avatar: null
            },
            isSending: false,
            options: [
                { text: 'CC', value: 'CC' },
                { text: 'CE', value: 'CE' },
                { text: 'TI', value: 'TI' },
                { text: 'PEP', value: 'PEP' },
                { text: 'Pasaporte', value: 'Pasaporte' },
                { text: 'Otro', value: 'Otro' }
            ],
            showAlert: false,
            showDuplicate: false,
            showError: false,
            uploadProgress: 0,
            certificate: null
        };
    },
    validations: {
        form: {
            userType: {
                required
            },
            fullname: {
                required
            },
            lastname: {
                required
            },
            documentType: {
                required
            },
            documentNumber: {
                required
            },
            phone: {
                required
            },
            email: {
                required,
                email
            },
            avatar: {
                required
            }
        }
    },
    methods: {
        countDownChanged(dismissCountDown) {
            this.dismissCountDown = dismissCountDown;
        },
        onSubmit() {
            // Validate Form
            this.$v.form.$touch();
            if (this.$v.form.$anyError) {
                return;
            }

            // Active Spinner
            this.isSending = true;
            this.avatarErrors = [];
            this.showAlert = false;
            this.showError = false;

            // Axios Settings
            const config = {
                onUploadProgress: (event) => {
                    this.uploadProgress = Math.round((event.loaded / event.total) * 100);
                }
            };

            // Create Params
            const data = { ...this.form };
            const params = new FormData();

            for (const [key, item] of Object.entries(data)) {
                if (item) {
                    params.append(key, item);
                    if (typeof item === 'object') {
                        for (const [index, entrie] of Object.entries(item)) {
                            params.append(`${key}[${index}]`, entrie);
                        }
                    }
                }
            }

            // Send Request
            axios
                .post('/certificates/generate', params, config)
                .then((res) => {
                    // this.resetForm();
                    if (res.data.certificate) {
                        this.certificate = res.data.certificate;
                        this.showDuplicate = true;
                    } else {
                        this.showAlert = true;
                    }
                })
                .catch((error) => {
                    const errors = error.response.data.errors;
                    console.log(errors);
                    if (errors) {
                        this.avatarErrors = errors.avatar;
                    } else {
                        this.showError = true;
                    }

                    this.uploadProgress = 0;
                })
                .finally(() => {
                    this.isSending = false;
                });
        },
        resetForm() {
            this.form = {
                fullname: '',
                lastname: '',
                documentType: null,
                documentNumber: '',
                phone: '',
                email: '',
                avatar: null
            };

            this.uploadProgress = 0;

            this.$nextTick(() => {
                this.$v.$reset();
            });
        },
        validateState(name) {
            const { $dirty, $error } = this.$v.form[name];
            return $dirty ? !$error : null;
        }
    }
};
</script>

<style lang="scss">
@import '~bootstrap/scss/bootstrap';
@import '~bootstrap-vue/src/index';
</style>
