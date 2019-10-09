<?php

namespace App\Http\Controllers;

use Excel;
use Illuminate\View\View;
use Illuminate\Support\Collection;
use App\Http\Requests\UploadMailingListRequest;


class MailingListController extends Controller
{
    /**
     * Display import form.
     *
     * @return View
     */
    public function index() : View
    {
        return view('mailing-list.index');
    }

    /**
     * Upload file and store results.
     *
     * @param UploadMailingListRequest $request
     * @return View
     */
    public function store(UploadMailingListRequest $request) : View
    {
        $file = $request->moveFile();

        $collection = new Collection(
            Excel::load($file)->get()->toArray()
        );

        return view('mailing-list.result');
    }
}
