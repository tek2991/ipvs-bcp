<?php

namespace App\Http\Controllers;

use ZipArchive;
use App\Models\Set;
use App\Models\Export;
use App\Exports\BagExport;
use Illuminate\Http\Request;
use App\Models\BagTransactionType;
use App\Exports\ArticleCloseExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ArticleReceiveExport;
use App\Models\ArticleTransactionType;
use App\Models\User;

class ExportController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $current_facility = $user->facility;

        $users = $current_facility->users()->get();

        if ($user->is_admin()) {
            $users = User::all();
        }

        $sets = $user->username != 'administrator' ? $current_facility->sets()->orderBy('created_at', 'desc')->get() : Set::orderBy('created_at', 'desc')->get();

        return view('export', compact('sets', 'users'));
    }

    public function export(Request $request)
    {
        $this->validate($request, [
            'set_id' => 'nullable|integer|exists:sets,id',
            'report_type' => 'nullable|string|in:bag_receive,bag_dispatch,article_open,article_close'
        ]);

        $set = Set::find($request->set_id);

        if (in_array($request->report_type, ['bag_receive', 'bag_dispatch'])) {
            $bag_status_names = $request->report_type == 'bag_receive' ? ['RD', 'OP', 'OP_SCAN', 'DI'] : ['CL', 'DI'];
            $bag_status_ids = BagTransactionType::whereIn('name', $bag_status_names)->get()->modelKeys();
            $bags = $set->bags()->whereIn('bag_transaction_type_id', $bag_status_ids)->with('bagType', 'bagTransactionType')->get();

            $date_time = $request->report_type == 'bag_dispatch' ? $set->updated_at->addMinute() : $set->updated_at;
            $name = $set->facility->facility_code . '_' . 'GEN1' . '_' . date_format($date_time, "YmdHi") . '.xlsx';
            $status = $request->report_type == 'bag_receive' ? 'RD' : 'DI';
            return Excel::download(new BagExport($bags, $status, $request->report_type), $name);
        } else {
            $article_status_names = $request->report_type == 'article_open' ? ['OP', 'CL', 'CL_SCAN'] : ['CL'];
            $article_status_ids = ArticleTransactionType::whereIn('name', $article_status_names)->get()->modelKeys();

            $date_time = $request->report_type == 'article_open' ? $set->updated_at->addMinute(2) : $set->updated_at->addMinute(3);
            $name = $set->facility->facility_code . '_' . 'GEN1' . '_' . date_format($date_time, "YmdHi") . '.xlsx';
            $status = $request->report_type == 'article_open' ? 'OP' : 'CL';

            if ($request->report_type == 'article_open') {
                $articles = $set->articles()->whereIn('article_transaction_type_id', $article_status_ids)->with('openingBag.fromFacility', 'articleType', 'articleTransactionType')->get();
                return Excel::download(new ArticleReceiveExport($articles, $status), $name);
            } else {
                $articles = $set->articles()->whereIn('article_transaction_type_id', $article_status_ids)->with('closingBag.toFacility', 'articleType', 'articleTransactionType')->get();
                return Excel::download(new ArticleCloseExport($articles, $status), $name);
            }
        }
    }

    public function exportUser(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'integer|exists:users,id',
            'set_id' => 'integer|exists:sets,id',
            'report_type' => 'string|in:bag_receive,bag_dispatch,article_open,article_close'
        ]);

        $set = Set::find($request->set_id);
        $user = User::find($request->user_id);


        if (in_array($request->report_type, ['bag_receive', 'bag_dispatch'])) {
            $bag_status_names = $request->report_type == 'bag_receive' ? ['RD', 'OP', 'OP_SCAN', 'DI'] : ['CL', 'DI'];
            $bag_status_ids = BagTransactionType::whereIn('name', $bag_status_names)->get()->modelKeys();
            $bags = $set->bags()->whereIn('bag_transaction_type_id', $bag_status_ids)->where('updated_by', $user->id)->with('bagType', 'bagTransactionType')->get();

            $date_time = $request->report_type == 'bag_dispatch' ? $set->updated_at->addMinute() : $set->updated_at;
            $name = $set->facility->facility_code . '_' . $set->setType->name . '_' . date_format($date_time, "YmdHis") . '.xlsx';
            $status = $request->report_type == 'bag_receive' ? 'RD' : 'DI';
            return Excel::download(new BagExport($bags, $status, $request->report_type), $name);
        }

        /*
        if (in_array($request->report_type, ['article_open_zip', 'article_close_zip'])) {
            $type = $request->report_type == 'article_open_zip' ? 'OP' : 'CL';
            $export_file_paths = Export::where('user_id', $user->id)->where('set_id', $set->id)->where('type', $type)->get()->pluck('file_path')->toArray();

            // Check if there are any files to export
            if (count($export_file_paths) == 0) {
                return redirect()->back()->with('error', 'No files to export');
            }

            // Add all the file paths to a zip file.
            $zip = new ZipArchive();
            $zip_file_path = $set->facility->facility_code . '_' . $set->setType->name . '_' . date_format($set->updated_at, "YmdHis") . '_' . $user->name . '_' . $type . '.zip';
            $zip_file_path = storage_path('app/public/' . $zip_file_path);
            if ($zip->open($zip_file_path, \ZipArchive::CREATE) === TRUE) {
                foreach ($export_file_paths as $file_path) {
                    $zip->addFile(storage_path('app/' . $file_path), basename($file_path));
                }
                $zip->close();
            }

            // Return the zip file.
            return response()->download($zip_file_path);
        }
        */

        if (in_array($request->report_type, ['article_open', 'article_close'])) {
            $article_status_names = $request->report_type == 'article_open' ? ['OP', 'CL', 'CL_SCAN'] : ['CL'];
            $article_status_ids = ArticleTransactionType::whereIn('name', $article_status_names)->get()->modelKeys();
            
            // $current_facility = $user->facility;
            // $current_set = $current_facility->sets()->where('is_active', true)->first();
            $set_date_and_type = $set->created_at->format('Ymd') . '_' . $set->setType->name;
            $date_time = $request->report_type == 'article_open' ? $set->updated_at->addMinute(2) : $set->updated_at->addMinute(3);

            $status = $request->report_type == 'article_open' ? 'OP' : 'CL';

            if ($request->report_type == 'article_open') {
                $articles = $set->articles()->whereIn('article_transaction_type_id', $article_status_ids)->with('openingBag.fromFacility', 'articleType', 'articleTransactionType')->where('updated_by', $user->id)->get();
                // Break articles into chunks of 500 and export each chunk.
                $articles_chunks = $articles->chunk(500);
                $files = [];
                foreach ($articles_chunks as $key => $articles_chunk) {
                    $date_time = $date_time->addMinute();
                    $name = $set->facility->facility_code . '_' . 'GEN1' . '_' . date_format($date_time, "YmdHi") . '.xlsx';
                    $path = 'exports/' . $set_date_and_type . '/' . $user->name . '/chunks' . '/article_open/' . $name;
                    Excel::store(new ArticleReceiveExport($articles_chunk, $status), $path);
                    $files[] = $path;
                }
                // Create a zip file of all the exported files.
                $zip = new ZipArchive();
                $zip_file_path = $set->facility->facility_code . '_' . $set->setType->name . '_' . date_format($set->updated_at, "YmdHis") . '_' . $user->name . '_chunked_article_open' . $status . '.zip';
                $zip_file_path = storage_path('app/public/' . $zip_file_path);
                if ($zip->open($zip_file_path, \ZipArchive::CREATE) === TRUE) {
                    foreach ($files as $file_path) {
                        $zip->addFile(storage_path('app/' . $file_path), basename($file_path));
                    }
                    $zip->close();
                }
    
                // Return the zip file.
                return response()->download($zip_file_path);
            } else {
                $articles = $set->articles()->whereIn('article_transaction_type_id', $article_status_ids)->with('closingBag.toFacility', 'articleType', 'articleTransactionType')->where('updated_by', $user->id)->get();
                // Break articles into chunks of 500 and export each chunk.
                $articles_chunks = $articles->chunk(500);
                $files = [];
                foreach ($articles_chunks as $key => $articles_chunk) {
                    $date_time = $date_time->addMinute();
                    $name = $set->facility->facility_code . '_' . 'GEN1' . '_' . date_format($date_time, "YmdHi") . '.xlsx';
                    $path = 'exports/' . $set_date_and_type . '/' . $user->name . '/chunks' . '/article_close/' . $name;
                    Excel::store(new ArticleCloseExport($articles_chunk, $status), $path);
                    $files[] = $path;
                }
                // Create a zip file of all the exported files.
                $zip = new ZipArchive();
                $zip_file_path = $set->facility->facility_code . '_' . $set->setType->name . '_' . date_format($set->updated_at, "YmdHis") . '_' . $user->name . '_chunked_article_close' . $status . '.zip';
                $zip_file_path = storage_path('app/public/' . $zip_file_path);
                if ($zip->open($zip_file_path, \ZipArchive::CREATE) === TRUE) {
                    foreach ($files as $file_path) {
                        $zip->addFile(storage_path('app/' . $file_path), basename($file_path));
                    }
                    $zip->close();
                }
    
                // Return the zip file.
                return response()->download($zip_file_path);
            }
        }

        return redirect()->back()->with('error', 'Invalid report type');
    }
}
