<template>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
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
                            variant="success"
                            size="sm"
                            title="Reenviar"
                            @click="resend(row.item.certificate_number)"
                        >
                            <b-icon icon="arrow-repeat"></b-icon>
                        </b-button>
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
    methods: {
        getCertificates() {
            axios.get('/certificates/all').then((response) => {
                this.items = response.data;
            });
        },
        resend(certificateId) {
            axios
                .post(`/certificates/resend/${certificateId}`)
                .then((response) => {
                    console.log(response);
                })
                .catch((error) => {
                    console.log(error);
                });
        }
    },
    mounted() {
        this.getCertificates();
    }
};
</script>
