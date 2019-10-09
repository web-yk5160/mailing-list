<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadFile;
use Illuminate\Http\Response;


use Illuminate\Foundation\Testing\DatabaseMigrations;

class MailingListTest extends DuskTestCase
{

    use DatabaseMigrations;
    /**
     * @test
     */
    public function displays_upload_form()
    {
        $this->browse(function (Browser $browser) {
            // seeElementはduskで使えない
            $browser->visit('/')
            ->seeElement('input', ['type' => 'file', 'name' => 'file'])
            ->seeElement('input', ['type' => 'submit', 'value' => 'UPLOAD']);
        });
    }

    /**
     * @test
     */
    public function returns_validation_error_if_submitted_without_the_file_selected()
    {
        $this->post('/')
              ->assertResponseStatus(Response::HTTP_FOUND);

        $this->assertSessionHasErrors([
            'file' => 'The file field is required.'
        ]);
    }

    /**
     * @test
     */
    public function returns_validation_error_if_submitted_without_the_file_type()
    {
        $this->post('/', ['file' => 'Some file'])
              ->assertResponseStatus(Response::HTTP_FOUND);

        $this->assertSessionHasErrors([
            'file' => 'The file must be a file.'
        ]);
    }

    /**
     * Get instance of the UploadedFile.
     *
     * @param string $stubName
     * @return UploadedFile
     */
    public function uploadFile(string $stubName) : Uploaded
    {
        $stub = __DIR__ . '/stubs/' . $stubName;
        $file = new SplFileInfo($stub);
        $name = str_random(8) . '.' . $file->getExtension();
        $path = sys_get_temp_dir() . '/' . $name;

        copy($stub, $path);

        return new uploadFile(
            $path,
            $name,
            mime_content_type($path),
            filesize($path),
            null,
            true
        );
    }

    /**
     * @test
     */
    public function returns_validation_error_if_submitted_without_the_valid_file_format()
    {
        $this->call(
            'POST',
            '/',
            [],
            [],
            ['file' => $this->uploadFile('document.pdf')]
        );

        $this->assertResponseStatus(Response::HTTP_FOUND);

        $this->assertSessionHasErrors([
            'file' => 'The file must be a file of type: csv, txt.'
        ]);
    }

}
