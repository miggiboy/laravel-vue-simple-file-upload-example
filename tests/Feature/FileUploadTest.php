<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;

class FileUploadsTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
    }

    /** @test */
    function file_can_be_uploaded()
    {
        $response = $this->post(route('file-uploads.store'), [
            'files' => [ File::image('new-image') ]
        ]);
        $response->assertStatus(200);

        $this->assertCount(1, $response['files']);
        collect($response['files'])->each(function ($file) {
            Storage::disk('local')->assertExists($file['path']);
        });
    }

    /** @test */
    function uploaded_file_can_be_deleted()
    {
        $response = $this->post(route('file-uploads.store'), [
            'files' => [ File::image('new-image') ]
        ]);

        $this->assertCount(1, $response['files']);
        collect($response['files'])->each(function ($file) {
            Storage::disk('local')->assertExists($file['path']);
        });

        $uploadedFiles = collect($response['files']);
        $response = $this->delete(route('file-uploads.destroy'), [
            'files' => $uploadedFiles->pluck('path')
        ]);
        $response->assertOk();
        $uploadedFiles->each(function ($file) {
            Storage::disk('local')->assertMissing($file['path']);
        });
    }
}
