<?php

namespace Tests\Feature;

use App\Models\PrivateDocument;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PrivateDocumentTest extends TestCase
{
    protected $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->superAdmin = User::factory()->create([
            'role' => 'admin',
            'is_super_admin' => true,
        ]);
    }

    public function test_super_admin_can_view_private_document_image(): void
    {
        Storage::fake('private');

        $file = UploadedFile::fake()->image('test_doc.png', 100, 100);
        $path = $file->store('documents', 'private');

        $document = PrivateDocument::create([
            'user_id'   => $this->superAdmin->id,
            'title'     => 'Test Document Image',
            'type'      => 'user_document',
            'file_path' => $path,
            'file_name' => 'test_doc.png',
            'file_size' => $file->getSize(),
            'mime_type' => 'image/png',
        ]);

        $response = $this->actingAs($this->superAdmin)
            ->get(route('private-documents.view', $document->id));

        $response->assertStatus(200);

        // cleanup test doc
        $document->delete();
    }

    public function test_super_admin_can_delete_private_document(): void
    {
        Storage::fake('private');

        $file = UploadedFile::fake()->image('to_delete.png');
        $path = $file->store('documents', 'private');

        $document = PrivateDocument::create([
            'user_id'   => $this->superAdmin->id,
            'title'     => 'Document To Delete',
            'type'      => 'user_document',
            'file_path' => $path,
            'file_name' => 'to_delete.png',
            'file_size' => $file->getSize(),
            'mime_type' => 'image/png',
        ]);

        $this->assertTrue(Storage::disk('private')->exists($path));
        $this->assertDatabaseHas('private_documents', ['id' => $document->id]);

        $response = $this->actingAs($this->superAdmin)
            ->delete(route('private-documents.destroy', $document->id));

        $response->assertRedirect(route('private-documents.index'));
        $this->assertDatabaseMissing('private_documents', ['id' => $document->id]);
        $this->assertFalse(Storage::disk('private')->exists($path));
    }

    public function test_super_admin_can_delete_document_even_if_file_missing_on_disk(): void
    {
        $document = PrivateDocument::create([
            'user_id'   => $this->superAdmin->id,
            'title'     => 'Document Missing On Disk',
            'type'      => 'user_document',
            'file_path' => 'documents/nonexistent_file_12345.png',
            'file_name' => 'nonexistent_file_12345.png',
            'file_size' => 1234,
            'mime_type' => 'image/png',
        ]);

        $response = $this->actingAs($this->superAdmin)
            ->delete(route('private-documents.destroy', $document->id));

        $response->assertRedirect(route('private-documents.index'));
        $this->assertDatabaseMissing('private_documents', ['id' => $document->id]);
    }
}
