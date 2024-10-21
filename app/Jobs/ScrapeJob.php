<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

class ScrapeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $jobId;

    public function __construct(string $jobId)
    {
        $this->jobId = $jobId;
    }

    public function handle()
    {
        // Fetch job data from Redis
        $jobData = json_decode(Redis::get("job:$this->jobId"), true);
        $client = new Client();
        $scrapedData = [];

        foreach ($jobData['urls'] as $url) {
            try {
                // Make the GET request
                $response = $client->get($url);
                $html = (string) $response->getBody();

                // Initialize DomCrawler with the fetched HTML
                $crawler = new Crawler($html);
                $data = [];

                // Loop through each selector provided
                foreach ($jobData['selectors'] as $selector) {
                    $crawler->filter($selector)->each(function (Crawler $node) use (&$data) {
                        $data[] = $node->text(); // Extract text content of matched elements
                    });
                }

                // Store scraped data for this URL
                $scrapedData[$url] = $data;

            } catch (\Exception $e) {
                // Log the error message
                Log::error('ScrapeJob failed for URL ' . $url . ': ' . $e->getMessage());
                $scrapedData[$url] = 'Error fetching data'; // Indicate that an error occurred
            }
        }

        // Update job data with scraped data
        $jobData['scraped_data'] = $scrapedData;
        $jobData['status'] = 'completed';
        Redis::set("job:$this->jobId", json_encode($jobData));
    }
}
