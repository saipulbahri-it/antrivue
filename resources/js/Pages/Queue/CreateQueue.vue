<template>
    <div class="create-queue">
        <button @click="create">Ambil Nomor Antrean</button>

        <div v-if="ticket">
            <h3>Nomor Anda: {{ ticket.queue_number }}</h3>
            <button @click="printTicket">Cetak Tiket</button>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            ticket: null,
        };
    },
    methods: {
        async create() {
            const res = await fetch('/api/queues', { method: 'POST' });
            this.ticket = await res.json();
        },
        printTicket() {
            const printWindow = window.open('', '', 'width=300,height=600');
            printWindow.document.write(`
          <div style="text-align: center; font-size: 20px;">
            <p>=== Nomor Antrian ===</p>
            <h1 style="font-size: 50px;">${this.ticket.queue_number}</h1>
            <p>${new Date().toLocaleString()}</p>
            <p>=====================</p>
          </div>
        `);
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        },
    },
};
</script>

<style scoped>
.create-queue {
    text-align: center;
    margin-top: 2rem;
}
</style>
