<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\LengthAwarePaginator;

class NewsController extends Controller
{
	public function index(Request $request)
	{
		Session::flush();
		$title = "News List";
		$url = config("app.api_url") . "/everything";
		$urlSource = config("app.api_url") . "/top-headlines/sources";
		$page = $request->input("page", 1);
		$paginator = [];

		$resSource = Http::withToken(config("app.api_key"))->withHeaders([
			"Accept" => "application/json",
		])->get($urlSource)->json();

		$allSource = $responseSource = [];
		if ($resSource["status"] == "ok") {
			$allSource = collect($resSource["sources"])->pluck('id')->toArray();
			$responseSource = $resSource['sources'];
		}

		$search = $request->input("search", "");
		$sources = $request->input("source", $allSource);
		$sortBy = $request->input("sort_by", "");
		$publishDate = $request->input("publish_date", "");
		$query = [
			"pageSize" => 100,
			"page" => $page,
			"sources" => implode(",", $sources),
			"sortBy=" => $sortBy,
		];

		if (!empty($search)) {
			$query["q"] = $search;
		}

		$publishDateFrom = $publishDateTo = "";
		if (!empty($publishDate)) {
			$publishDate = explode(" to ", $publishDate);
			$publishDateFrom = $publishDate[0];
			$publishDateTo = $publishDate[1] ?? $publishDate[0];
			$query["from"] = $publishDateFrom;
			$query["to"] = $publishDateTo;
		}

		$response = Http::withToken(config("app.api_key"))->withHeaders([
			"Accept" => "application/json",
		])->get($url, $query)->json();

		if ($response["status"] == "ok") {
			$perPage = 100;
			$paginator = new LengthAwarePaginator(
				$response["articles"],
				$response["totalResults"],
				$perPage,
				$page,
				['path' => request()->url(), 'query' => request()->query()]
			);
		} else {
			Session::flash('error', $response["message"]);
		}
		return view("news.index", compact("title", "paginator", "responseSource", "sources", "publishDateFrom", "publishDateTo", "sortBy", "search"));
	}
}
