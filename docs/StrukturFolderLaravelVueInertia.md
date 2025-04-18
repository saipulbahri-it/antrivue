app/
├── Http/
│   ├── Controllers/
│   │   ├── QueueController.php
│   │   ├── CounterController.php
│   │   └── ServiceController.php
│
│   └── Services/
│       └── QueueService.php

app/Models/
├── Queue.php
├── Counter.php
└── Service.php

routes/
├── web.php (Inertia routes)

resources/js/
├── Pages/
│   ├── Kiosk.vue            ← Untuk pemilihan layanan & cetak antrian
│   ├── CSPanel.vue          ← Panel petugas
│   ├── Display.vue          ← Tampilan monitor antrian
│   └── Admin/
│       ├── ManageServices.vue
│       └── ManageCounters.vue
├── Components/
│   └── QueueNumber.vue      ← Komponen tampilan nomor

resources/views/
└── app.blade.php (Inertia layout)

database/migrations/
├── create_services_table.php
├── create_counters_table.php
└── create_queues_table.php

database/seeders/
├── ServiceSeeder.php
└── CounterSeeder.php
