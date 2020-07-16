<?php

namespace App\Providers;

use GuzzleHttp\Client;
use App\Exceptions\InvalidApiResponseException;

/**
 * Google Service Provider class.
 *
 */
class GoogleProvider 
{
    /**
     * @var string
     */
    private $apiKey;
   
    /**
     * @var array
     */
    private $options;
    
     /**
     * Constructor.
     *
     * @param string $apiKey  A Google API key, optional
     * @param array  $options An array of options used to do the shorten/expand request
     */
    public function __construct()
    {
        $this->apiKey  = config('urlshortener.google.apikey');
        $this->options = array(
            'connect_timeout' => config('urlshortener.connect_timeout'),
            'timeout'         => config('urlshortener.timeout'),
        );
    }
   
    public function shorten($longUrl)
    {
        $client = $this->createClient();        
        $response = $client->post($this->getUri(), array_merge(
            array(
                'json' => array(
                    'longUrl' => $longUrl,
                ),
            ),
            $this->options
        ));
        $response = $this->validate($response->getBody()->getContents());
        return $response->id;
    }
   
    public function expand($shortUrl)
    {
        $client = $this->createClient();     
        $response = $client->get($this->getUri(array(
            'shortUrl' => $shortUrl,
        )), $this->options);

        $response = $this->validate($response->getBody()->getContents(), true);
        return $response->longUrl;
    }
    /**
     * Creates a client.
     *
     * This method is mocked in unit tests in order to not make a real request,
     * so visibility must be protected or public.
     *
     * @return Client
     */
    protected function createClient()
    {
        return new Client(array(
            'base_uri' => 'https://www.googleapis.com/urlshortener/v1/url',
        ));
    }
    /**
     * Gets the URI.
     *
     * @param array $parameters An array of parameters, optional
     *
     * @return null|string
     */
    private function getUri(array $parameters = array())
    {
        if ($this->apiKey) {
            $parameters = array_merge($parameters, array('key' => $this->apiKey));
        }
        if (0 === count($parameters)) {
            return;
        }
        return sprintf('?%s', http_build_query($parameters));
    }
    /**
     * Validates the Google's response and returns it whether the status code is 200.
     *
     * @param string $apiRawResponse An API response, as it returned
     * @param bool   $checkStatus    TRUE whether the status code has to be checked, default FALSE
     *
     * @return object
     *
     * @throws InvalidApiResponseException
     */
    private function validate($apiRawResponse, $checkStatus = false)
    {
        $response = json_decode($apiRawResponse);
        if (null === $response) {
            throw new InvalidApiResponseException('Google response is probably mal-formed because cannot be json-decoded.');
        }
        if (property_exists($response, 'error')) {
            throw new InvalidApiResponseException(sprintf('Google returned status code "%s" with message "%s".',
                property_exists($response->error, 'code') ? $response->error->code : '',
                property_exists($response->error, 'message') ? $response->error->message : ''
            ));
        }
        if (!property_exists($response, 'id')) {
            throw new InvalidApiResponseException('Property "id" does not exist within Google response.');
        }
        if (!property_exists($response, 'longUrl')) {
            throw new InvalidApiResponseException('Property "longUrl" does not exist within Google response.');
        }
        if (!$checkStatus) {
            return $response;
        }
        if (!property_exists($response, 'status')) {
            throw new InvalidApiResponseException('Property "status" does not exist within Google response.');
        }
        if ('OK' !== $response->status) {
            throw new InvalidApiResponseException(sprintf('Google returned status code "%s".', $response->status));
        }
        return $response;
    }
}