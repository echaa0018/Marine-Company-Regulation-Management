<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Document;
use Carbon\Carbon;

class TestDocumentSeeder extends Seeder
{
    public function run(): void
    {
        $documents = [
            [
                'number' => 'PER-001/DIR/2024',
                'title' => 'Peraturan tentang Sistem Manajemen Dokumen',
                'type' => 'Peraturan Direksi',
                'published_date' => '2024-01-15',
                'effective_until' => '2025-01-15',
                'status' => 'Berlaku',
                'confidentiality' => 'Internal Use',
                'file_name' => 'PER-001-DIR-2024.pdf',
                'file_path' => 'documents/PER-001-DIR-2024.pdf',
            ],
            [
                'number' => 'SOP-002/OPS/2024',
                'title' => 'Standard Operating Procedure untuk Pengelolaan Aset',
                'type' => 'Standard Operating Procedure',
                'published_date' => '2024-02-01',
                'effective_until' => '2025-02-01',
                'status' => 'Berlaku',
                'confidentiality' => 'Confidential',
                'file_name' => 'SOP-002-OPS-2024.pdf',
                'file_path' => 'documents/SOP-002-OPS-2024.pdf',
            ],
            [
                'number' => 'PER-003/HR/2023',
                'title' => 'Peraturan tentang Cuti Karyawan',
                'type' => 'Peraturan Direktur',
                'published_date' => '2023-12-01',
                'effective_until' => '2024-12-01',
                'status' => 'Tidak Berlaku',
                'confidentiality' => 'Public',
                'file_name' => 'PER-003-HR-2023.pdf',
                'file_path' => 'documents/PER-003-HR-2023.pdf',
            ],
        ];

        foreach ($documents as $documentData) {
            Document::create($documentData);
        }
    }
}
