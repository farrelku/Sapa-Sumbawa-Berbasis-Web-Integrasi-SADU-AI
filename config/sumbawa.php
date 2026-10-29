<?php

return [
    'districts' => [
        'Sumbawa' => 'Kec. Sumbawa (Kota)',
        'Labuhan Badas' => 'Kec. Labuhan Badas',
        'Unter Iwes' => 'Kec. Unter Iwes',
        'Moyo Hilir' => 'Kec. Moyo Hilir',
        'Moyo Hulu' => 'Kec. Moyo Hulu',
        'Moyo Utara' => 'Kec. Moyo Utara',
        'Alas' => 'Kec. Alas',
        'Alas Barat' => 'Kec. Alas Barat',
        'Utan' => 'Kec. Utan',
        'Buer' => 'Kec. Buer',
        'Rhee' => 'Kec. Rhee',
        'Batulanteh' => 'Kec. Batulanteh',
        'Lunyuk' => 'Kec. Lunyuk',
        'Plampang' => 'Kec. Plampang',
        'Empang' => 'Kec. Empang',
        'Tarano' => 'Kec. Tarano',
        'Lape' => 'Kec. Lape',
        'Lopok' => 'Kec. Lopok',
        'Maronge' => 'Kec. Maronge',
        'Labangka' => 'Kec. Labangka',
        'Lenangguar' => 'Kec. Lenangguar',
        'Ropang' => 'Kec. Ropang',
        'Orong Telu' => 'Kec. Orong Telu',
    ],

    'categories' => [
        'Jalan Rusak & Jembatan' => [
            'icon' => 'road',
            'desc' => 'Jalan aspal berlubang, jembatan retak/ambrol, trotoar rusak',
            'default_opd' => 'PUPR',
        ],
        'Pelayanan Publik' => [
            'icon' => 'users',
            'desc' => 'Pelayanan instansi lambat, pungli, ketidaksesuaian SOP pelayanan',
            'default_opd' => 'DISKOMINFO',
        ],
        'Kebersihan & Sampah' => [
            'icon' => 'trash-2',
            'desc' => 'Tumpukan sampah liar, TPS overload, pembuangan limbah sembarangan',
            'default_opd' => 'DLH',
        ],
        'Penerangan Jalan (PJU)' => [
            'icon' => 'lightbulb',
            'desc' => 'Lampu PJU mati/rusak, tiang listrik roboh, kabel menjuntai',
            'default_opd' => 'DISHUB',
        ],
        'Drainase & Saluran Air' => [
            'icon' => 'droplet',
            'desc' => 'Saluran air tersumbat, genangan banjir, gorong-gorong rusak',
            'default_opd' => 'PUPR',
        ],
        'Ketertiban Umum' => [
            'icon' => 'shield',
            'desc' => 'Gangguan ketentraman, penertiban PKL, balap liar, kebisingan',
            'default_opd' => 'SATPOLPP',
        ],
        'Pelayanan Kesehatan & Puskesmas' => [
            'icon' => 'heart-pulse',
            'desc' => 'Layanan puskesmas/RSUD, antrean obat, fasilitas rawat inap',
            'default_opd' => 'DINKES',
        ],
        'Pelayanan Adminduk (KTP/KK)' => [
            'icon' => 'id-card',
            'desc' => 'Keterlambatan cetak KTP-el, KK, akta kelahiran, IKD error',
            'default_opd' => 'DUKCAPIL',
        ],
        'Fasilitas Sekolah & Pendidikan' => [
            'icon' => 'graduation-cap',
            'desc' => 'Ruang kelas rusak, pagar sekolah roboh, fasilitas belajar',
            'default_opd' => 'DIKBUD',
        ],
        'Bencana Alam & Darurat' => [
            'icon' => 'flame',
            'desc' => 'Banjir bandang, longsor, pohon tumbang berbahaya, kebakaran lahan',
            'default_opd' => 'BPBD',
        ],
        'Lainnya' => [
            'icon' => 'help-circle',
            'desc' => 'Aspirasi dan pengaduan masalah fasilitas publik lainnya',
            'default_opd' => 'DISKOMINFO',
        ],
    ],
];
