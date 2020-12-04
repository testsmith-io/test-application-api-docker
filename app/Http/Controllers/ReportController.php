<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{

    /**
     * Create a new ReportController.php instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('throttle:60,1');
        $this->middleware('auth:employee');
        $this->middleware('assign.guard:employee');
    }

    /**
     * @OA\Get(
     *     path="/reports/countCustomersBySalesAgent",
     *     summary="Retrieve number of customers by sales agent",
     *     operationId="retreive-number-of-customers-sales-agent",
     *     tags={"Report"},
     *     @OA\Response(
     *         response=200,
     *         description="Sales agent fullname and number of customers",
     *     ),
     *     security={{ "apiAuth": {} }}
     * )
     */
    public function countCustomersBySalesAgent()
    {
        $results = DB::table('employee AS e')
            ->join('customer', 'customer.support_rep_id', '=', 'e.id')
            ->select(DB::raw('CONCAT(e.firstname, " ", e.lastname) as sales_agent, COUNT(customer.support_rep_id) as count'))
            ->groupBy('e.id', 'sales_agent')
            ->get();

        return $this->preferredFormat($results);
    }

    /**
     * @OA\Get(
     *     path="/reports/totalSalesPerCountry",
     *     summary="Retrieve total sales per country",
     *     operationId="retreive-total-sales-per-country",
     *     tags={"Report"},
     *     @OA\Response(
     *         response=200,
     *         description="County and amount of sales",
     *     ),
     *     security={{ "apiAuth": {} }}
     * )
     */
    public function totalSalesPerCountry()
    {
        $results = DB::table('invoice')
            ->select(DB::raw('SUM(total) as "Total Sales For Country", billing_country'))
            ->groupBy('billing_country')
            ->get();

        return $this->preferredFormat($results);
    }

    /**
     * @OA\Get(
     *     path="/reports/totalSalesPerSalesAgent",
     *     summary="Retrieve total sales per sales agent",
     *     operationId="retreive-total-sales-per-sales-agent",
     *     tags={"Report"},
     *     @OA\Response(
     *         response=200,
     *         description="Sales agent fullname and total sales",
     *     ),
     *     security={{ "apiAuth": {} }}
     * )
     */
    public function totalSalesPerSalesAgent()
    {
        $results = DB::table('invoice AS i')
            ->join('customer', 'customer.id', '=', 'i.customer_id')
            ->join('employee AS e', 'customer.support_rep_id', '=', 'e.id')
            ->select(DB::raw('CONCAT(e.firstname, " ", e.lastname) as sales_agent, SUM(i.total) as total'))
            ->groupBy('e.id', 'sales_agent')
            ->get();

        return $this->preferredFormat($results);
    }

    /**
     * @OA\Get(
     *     path="/reports/top10PurchasedTracks",
     *     summary="Retrieve top 10 purchached tracks",
     *     operationId="retreive-top-10-purchased-tracks",
     *     tags={"Report"},
     *     @OA\Response(
     *         response=200,
     *         description="Track name and purchase amount",
     *     ),
     *     security={{ "apiAuth": {} }}
     * )
     */
    public function top10PurchasedTracks()
    {
        $results = DB::table('track AS t')
            ->join('invoiceline AS l', 'l.track_id', '=', 't.id')
            ->select(DB::raw('t.name, count(t.name) as count'))
            ->groupBy('t.name')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        return $this->preferredFormat($results);
    }

    /**
     * @OA\Get(
     *     path="/reports/top5BestSellingArtists",
     *     summary="Retrieve top 5 best selling artists",
     *     operationId="retreive-top-5-best-selling-artists",
     *     tags={"Report"},
     *     @OA\Response(
     *         response=200,
     *         description="Artist name and amount earned",
     *     ),
     *     security={{ "apiAuth": {} }}
     * )
     */
    public function top5BestSellingArtists()
    {
        $results = DB::table(DB::raw('(SELECT ar.name as "artist_name", SUM(t.unit_price) as "total_earned"
FROM track t
JOIN album a
ON t.album_id = a.id
JOIN artist ar
ON a.id = ar.id
GROUP BY ar.name) as f'))
            ->select(DB::raw('artist_name, total_earned'))
            ->orderByDesc('total_earned')
            ->limit(5)
            ->get();

        return $this->preferredFormat($results);
    }

    /**
     * @OA\Get(
     *     path="/reports/topArtistByGenre",
     *     summary="Retrieve top artists by genre",
     *     operationId="retreive-top-artists-by-genre",
     *     tags={"Report"},
     *     @OA\Parameter(
     *         name="genre",
     *         in="query",
     *         description="Genre, default=Rock",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Artist name and amount of songs",
     *     ),
     *     security={{ "apiAuth": {} }}
     * )
     */
    public function topArtistByGenre(Request $request)
    {
        $genre = $request->get('genre', 'Rock');
        $results = DB::table('track AS t')
            ->join('album', 'album.id', '=', 't.album_id')
            ->join('artist', 'artist.id', '=', 'album.artist_id')
            ->join('genre', 'genre.id', '=', 't.genre_id')
            ->select(DB::raw('artist.id, artist.name,COUNT(artist.id) AS number_of_songs'))
            ->where('genre.name', '=', $genre)
            ->groupBy('artist.id', 'artist.name')
            ->orderByDesc('number_of_songs')
            ->limit(10)
            ->get();

        return $this->preferredFormat($results);
    }

    /**
     * @OA\Get(
     *     path="/reports/totalSalesOfYear",
     *     summary="Retrieve total sales of year",
     *     operationId="retreive-total-sales-of-year",
     *     tags={"Report"},
     *     @OA\Parameter(
     *         name="year",
     *         in="query",
     *         description="Year, default=2010",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Artist name and amount of songs",
     *     ),
     *     security={{ "apiAuth": {} }}
     * )
     */
    public function totalSalesOfYear(Request $request)
    {
        $year = $request->get('year', 2010);

        $results = DB::table('invoice')
            ->select(DB::raw('SUM(total)'))
            ->whereBetween('invoice_date', [$year . '-01-01', $year + 1 . '-01-01'])
            ->get();

        return $this->preferredFormat($results);
    }

    /**
     * @OA\Get(
     *     path="/reports/customersByCountry",
     *     summary="Retrieve customers by country",
     *     operationId="retreive-customers-by-country",
     *     tags={"Report"},
     *     @OA\Parameter(
     *         name="country",
     *         in="query",
     *         description="Country, default=France",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Customer fullname, invoice id, invoice date, invoice country",
     *     ),
     *     security={{ "apiAuth": {} }}
     * )
     */
    public function customersByCountry(Request $request)
    {
        $country = $request->get('country', 'France');

        $results = DB::table('customer AS c')
            ->leftJoin('invoice AS i', 'i.customer_id', '=', 'c.id')
            ->select(DB::raw('CONCAT(c.firstname, " ", c.lastname) AS customer, i.id,i.invoice_date,i.billing_country'))
            ->where('c.country', '=', $country)
            ->get();

        return $this->preferredFormat($results);
    }

}
