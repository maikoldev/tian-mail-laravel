<template>
    <div class="container mt-5">
        <div class="row">
            <div class="col-6">
                <h1 class="h2 mb-3">Validar mi Certificación</h1>
                <b-form @submit.prevent="onSubmit">
                    <b-form-group
                        label="Número del certificado o Cedula"
                        label-for="certificate-id"
                    >
                        <b-form-input
                            id="certificate-id"
                            v-model="certificateId"
                            type="number"
                            placeholder="000123456789"
                            required
                        ></b-form-input>
                    </b-form-group>

                    <b-button type="submit" variant="info">Validar</b-button>
                </b-form>

                <!-- Alert  -->
                <b-alert :show="data ? true : false" class="mt-4" dismissible fade variant="info">
                    <p class="mb-0">
                        {{ data?.message || '-' }} <br />
                        Su numero de certificado es:
                        {{ data?.certificate.certificate_number || '-' }}
                    </p>
                </b-alert>

                <!-- Alert Errors -->
                <b-alert
                    :show="errors ? true : false"
                    class="mt-4"
                    dismissible
                    fade
                    variant="warning"
                >
                    <p class="mb-0">{{ errors?.message || '-' }}</p>
                </b-alert>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            certificateId: '',
            data: null,
            errors: null
        };
    },
    methods: {
        onSubmit() {
            this.data = null;
            this.errors = null;

            axios
                .post(`${process.env.MIX_APP_URL}/certificates/validation/${this.certificateId}`)
                .then((response) => {
                    this.data = response.data;
                })
                .catch((error) => {
                    this.errors = error.response.data;
                    console.log(error);
                });
        }
    }
};
</script>
