<template>
    <div class="container-fluid mt-5">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-11">
                <h1 class="h2 mb-3">Certificados</h1>
                <b-table striped responsive hover :items="items" :fields="fields">
                    <template #cell(certificate_status)="row">
                        <b-badge v-if="row.item.certificate_status == 'Generated'" variant="info">
                            Generado
                        </b-badge>
                        <b-badge v-if="row.item.certificate_status == 'Sent'" variant="success">
                            Enviado
                        </b-badge>
                        <b-badge v-if="row.item.certificate_status == 'Expired'" variant="danger">
                            Vencido
                        </b-badge>
                    </template>
                    <template #cell(actions)="row">
                        <div class="d-flex gap">
                            <b-button
                                variant="info"
                                size="sm"
                                :href="row.item.certificate_url"
                                download
                                title="Descargar"
                            >
                                <b-icon icon="download"></b-icon>
                            </b-button>
                            <b-button
                                v-if="row.item.certificate_status == 'Generated'"
                                variant="success"
                                size="sm"
                                title="Aprobar y Enviar"
                                @click="approve(row.item)"
                            >
                                <b-icon
                                    icon="envelope"
                                    :animation="row.item.isRotating ? 'throb' : 'none'"
                                ></b-icon>
                            </b-button>
                            <b-button
                                v-if="row.item.certificate_status == 'Sent'"
                                variant="success"
                                size="sm"
                                title="Reenviar"
                                @click="resend(row.item)"
                            >
                                <b-icon
                                    icon="arrow-repeat"
                                    :animation="row.item.isRotating ? 'spin' : 'none'"
                                ></b-icon>
                            </b-button>
                        </div>
                    </template>
                </b-table>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            items: null,
            fields: [
                { key: 'certificate_number', label: 'ID Certificado', sortable: true },
                { key: 'user_type', label: 'Tipo de usuario', sortable: true },
                { key: 'name', label: 'Nombres', sortable: true },
                { key: 'lastname', label: 'Apellidos', sortable: true },
                { key: 'document_number', label: 'Documento', sortable: true },
                { key: 'certificate_date', label: 'Fecha de certificación', sortable: true },
                {
                    key: 'certificate_expiration_date',
                    label: 'Fecha de vencimiento',
                    sortable: true
                },
                { key: 'certificate_status', label: 'Estado', sortable: true },
                { key: 'actions', label: 'Acciones' }
            ]
        };
    },
    mounted() {
        this.getCertificates();
    },
    methods: {
        getCertificates() {
            axios
                .get('/certificates/all')
                .then((response) => {
                    this.items = response.data.map((item) => {
                        return {
                            ...item,
                            isRotating: false
                        };
                    });
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        async resend(item) {
            item.isRotating = true;

            await axios
                .post(`${process.env.MIX_APP_URL}/certificates/resend/${item.certificate_number}`)
                .then((response) => {
                    console.log(response);

                    item.certificate_status = 'Sent';

                    this.$bvToast.toast('El certificado ha sido reenviado.', {
                        autoHideDelay: 2500,
                        solid: true,
                        title: 'Envío de certificado',
                        variant: 'success'
                    });
                })
                .catch((error) => {
                    console.log(error.response.data);

                    this.$bvToast.toast(error.response.data.message, {
                        autoHideDelay: 2500,
                        solid: true,
                        title: 'Error',
                        variant: 'danger'
                    });
                });

            item.isRotating = false;
        },
        async approve(item) {
            item.isRotating = true;

            await axios
                .post(`${process.env.MIX_APP_URL}/certificates/approve/${item.certificate_number}`)
                .then((response) => {
                    console.log(response);

                    item.certificate_status = 'Sent';

                    this.$bvToast.toast('El certificado ha sido enviado.', {
                        autoHideDelay: 2500,
                        solid: true,
                        title: 'Aprobación de certificado',
                        variant: 'success'
                    });
                })
                .catch((error) => {
                    console.log(error.response.data);

                    this.$bvToast.toast(error.response.data.message, {
                        autoHideDelay: 2500,
                        solid: true,
                        title: 'Error',
                        variant: 'danger'
                    });
                });

            item.isRotating = false;
        }
    }
};
</script>

<style scoped>
.gap {
    gap: 0.5rem;
}
</style>
