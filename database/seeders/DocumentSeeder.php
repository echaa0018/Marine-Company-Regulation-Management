<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Document;
use App\Models\DocumentRelationship;
use Illuminate\Support\Str;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, create all documents
        $documents = [
            [
                'id' => Str::uuid(),
                'number' => 'PER-2/LGL/03/2023',
                'title' => 'Peraturan Legal Department',
                'type' => 'Peraturan Direksi',
                'published_date' => '2023-03-24',
                'effective_until' => '2025-03-24',
                'status' => 'Berlaku',
                'confidentiality' => 'Internal Use',
                'file_name' => 'corporate-governance-policy.pdf',
                'file_path' => 'documents/corporate-governance-policy.pdf',
                'created_by' => 'system',
            ],
            [
                'id' => Str::uuid(),
                'number' => 'PER-2/HC/02/2022',
                'title' => 'Peraturan Human Capital Department',
                'type' => 'Peraturan Direktur',
                'published_date' => '2022-02-15',
                'effective_until' => '2024-02-15',
                'status' => 'Berlaku',
                'confidentiality' => 'Public',
                'file_name' => 'employee-code-of-conduct.pdf',
                'file_path' => 'documents/employee-code-of-conduct.pdf',
                'created_by' => 'system',
            ],
            [
                'id' => Str::uuid(),
                'number' => 'SOP-22/OPS/12/2014',
                'title' => 'Standard Operating Procedure Operations',
                'type' => 'Standard Operating Procedure',
                'published_date' => '2014-12-20',
                'effective_until' => '2024-12-20',
                'status' => 'Tidak Berlaku',
                'confidentiality' => 'Internal Use',
                'file_name' => 'asset-management-sop.pdf',
                'file_path' => 'documents/asset-management-sop.pdf',
                'created_by' => 'system',
            ],
            [
                'id' => Str::uuid(),
                'number' => 'STD-100/FIN/07/2024',
                'title' => 'Standar Keuangan Finance',
                'type' => 'Peraturan Kepala Pimpinan',
                'published_date' => '2024-07-25',
                'effective_until' => '2026-07-25',
                'status' => 'Berlaku',
                'confidentiality' => 'Confidential',
                'file_name' => 'financial-reporting-standards.pdf',
                'file_path' => 'documents/financial-reporting-standards.pdf',
                'created_by' => 'system',
            ],
            [
                'id' => Str::uuid(),
                'number' => 'GDL-05/IT/05/2024',
                'title' => 'Panduan Transformasi Digital',
                'type' => 'Business Process',
                'published_date' => '2024-05-12',
                'effective_until' => '2025-05-12',
                'status' => 'Berlaku',
                'confidentiality' => 'Internal Use',
                'file_name' => 'digital-transformation-guidelines.pdf',
                'file_path' => 'documents/digital-transformation-guidelines.pdf',
                'created_by' => 'system',
            ],
            [
                'id' => Str::uuid(),
                'number' => 'WI-10/HR/01/2024',
                'title' => 'Work Instruction Human Resources',
                'type' => 'Standard Operating Procedure',
                'published_date' => '2024-01-15',
                'effective_until' => '2025-01-15',
                'status' => 'Berlaku',
                'confidentiality' => 'Internal Use',
                'file_name' => 'recruitment-work-instruction.pdf',
                'file_path' => 'documents/recruitment-work-instruction.pdf',
                'created_by' => 'system',
            ],
            [
                'id' => Str::uuid(),
                'number' => 'FORM-25/MKT/03/2024',
                'title' => 'Form Marketing Department',
                'type' => 'Form',
                'published_date' => '2024-03-10',
                'effective_until' => '2025-03-10',
                'status' => 'Berlaku',
                'confidentiality' => 'Public',
                'file_name' => 'marketing-campaign-evaluation-form.pdf',
                'file_path' => 'documents/marketing-campaign-evaluation-form.pdf',
                'created_by' => 'system',
            ],
            [
                'id' => Str::uuid(),
                'number' => 'STD-200/PROC/06/2024',
                'title' => 'Standard Procurement',
                'type' => 'Peraturan Direktur',
                'published_date' => '2024-06-20',
                'effective_until' => '2026-06-20',
                'status' => 'Berlaku',
                'confidentiality' => 'Internal Use',
                'file_name' => 'procurement-standards.pdf',
                'file_path' => 'documents/procurement-standards.pdf',
                'created_by' => 'system',
            ],
            [
                'id' => Str::uuid(),
                'number' => 'PER-15/IT/08/2024',
                'title' => 'Peraturan Information Technology',
                'type' => 'Peraturan Direksi',
                'published_date' => '2024-08-05',
                'effective_until' => '2026-08-05',
                'status' => 'Berlaku',
                'confidentiality' => 'Confidential',
                'file_name' => 'it-security-policy.pdf',
                'file_path' => 'documents/it-security-policy.pdf',
                'created_by' => 'system',
            ],
            [
                'id' => Str::uuid(),
                'number' => 'GDL-12/OPS/09/2024',
                'title' => 'Guideline Operations',
                'type' => 'Business Process',
                'published_date' => '2024-09-15',
                'effective_until' => '2025-09-15',
                'status' => 'Berlaku',
                'confidentiality' => 'Internal Use',
                'file_name' => 'daily-operations-guideline.pdf',
                'file_path' => 'documents/daily-operations-guideline.pdf',
                'created_by' => 'system',
            ],
            [
                'id' => Str::uuid(),
                'number' => 'SOP-35/FIN/10/2024',
                'title' => 'Standard Operating Procedure Finance',
                'type' => 'Standard Operating Procedure',
                'published_date' => '2024-10-12',
                'effective_until' => '2026-10-12',
                'status' => 'Berlaku',
                'confidentiality' => 'Internal Use',
                'file_name' => 'budget-management-sop.pdf',
                'file_path' => 'documents/budget-management-sop.pdf',
                'created_by' => 'system',
            ],
            [
                'id' => Str::uuid(),
                'number' => 'COC-08/ALL/11/2024',
                'title' => 'Code of Conduct for All Employees',
                'type' => 'Peraturan Direksi',
                'published_date' => '2024-11-05',
                'effective_until' => '2026-11-05',
                'status' => 'Berlaku',
                'confidentiality' => 'Public',
                'file_name' => 'code-of-conduct-all-employees.pdf',
                'file_path' => 'documents/code-of-conduct-all-employees.pdf',
                'created_by' => 'system',
            ],
        ];

        // Store document objects for relationship creation
        $createdDocuments = [];

        foreach ($documents as $docData) {
            $document = Document::create($docData);
            $createdDocuments[$docData['number']] = $document;
        }

        // Now create relationships based on the original dummy data
        $relationships = [
            // PER-2/LGL/03/2023 revokes STD-100/FIN/07/2024 and changes COC-08/ALL/11/2024, WI-10/HR/01/2024
            [
                'source' => 'PER-2/LGL/03/2023',
                'targets' => [
                    ['number' => 'STD-100/FIN/07/2024', 'type' => DocumentRelationship::TYPE_REVOKES],
                ],
            ],
            [
                'source' => 'PER-2/LGL/03/2023',
                'targets' => [
                    ['number' => 'COC-08/ALL/11/2024', 'type' => DocumentRelationship::TYPE_CHANGES],
                    ['number' => 'WI-10/HR/01/2024', 'type' => DocumentRelationship::TYPE_CHANGES],
                ],
            ],
        ];

        foreach ($relationships as $relationship) {
            $sourceDoc = $createdDocuments[$relationship['source']] ?? null;

            if ($sourceDoc) {
                foreach ($relationship['targets'] as $target) {
                    $targetDoc = $createdDocuments[$target['number']] ?? null;

                    if ($targetDoc) {
                        DocumentRelationship::create([
                            'id' => Str::uuid(),
                            'source_document_id' => $sourceDoc->id,
                            'target_document_id' => $targetDoc->id,
                            'relationship_type' => $target['type'],
                            'created_by' => 'system',
                        ]);
                    }
                }
            }
        }

        // Update the revoked_by field for documents that are revoked
        $revokedDocument = $createdDocuments['STD-100/FIN/07/2024'] ?? null;
        if ($revokedDocument) {
            $revokedDocument->update([
                'revoked_by' => 'PER-2/LGL/03/2023',
                'status' => 'Tidak Berlaku', // Set status to inactive when revoked
            ]);
        }

        $this->command->info('Documents seeded successfully with relationships!');
    }
}
