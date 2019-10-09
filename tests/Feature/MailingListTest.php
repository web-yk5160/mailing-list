<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Http\UploadFile;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MailingListTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->get('/')
            ->assertSee('input', ['type' => 'file', 'name' => 'file'])
            ->assertSee('input', ['type' => 'submit', 'name' => 'UPLOAD']);
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

    /**
     * @test
     */
    public function uploads_and_correctly_filters_and_imports_records()
    {
        $this->call(
            'POST',
            '/',
            [],
            [],
            ['file' => $this->uploadFile('mailingList.csv')]
        );

        $this->assertResponseOk();
        $this->seeText('Import completed');
        $this->seeText('You have imported 2 records');
        $this->seeText('2 invalid email address');
        $this->seeText('1 duplicate record');
        $this->seeText('0 already existing records');

        $this->assertCount(
            2,
            MailingList::all()
        );

        $this->seeInDatabase('mailing_list',[
            'name' => 'John Doe',
            'email' => 'john@doe.com'
        ]);

        $this->seeInDatabase('mailing_list',[
            'name' => 'Jessie Doe',
            'email' => 'jessie@doe.com'
        ]);

        $this->dontSeeInDatabase('mailing_list',[
            'name' => 'Mark Spencer'
        ]);

        $this->dontSeeInDatabase('mailing_list',[
            'name' => 'Greg Monty'
        ]);

        $this->dontSeeInDatabase('mailing_list',[
            'name' => 'Brian Smith',
            'email' => 'jessie12@doe.com'
        ]);
    }
}
