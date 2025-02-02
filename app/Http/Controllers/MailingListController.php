<?php

namespace App\Http\Controllers;

use App\MailingList;
use App\Components\Filters\MailingListFilter;
use App\Http\Requests\UploadMailingListRequest;
use Excel;
use Illuminate\View\View;
use Illuminate\Support\Collection;



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

        $filter = new MailingListFilter(
            $collection,

        );

        MailingList::insert($subscriber);

        unlink($file);

        return view('mailing-list.result')
             ->with('subscriber', $subscriber)
             ->with('errorBug', $filter->errorBug);
    }
}
