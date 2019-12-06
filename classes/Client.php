<?php

namespace Neat\Http\Client;

use Neat\Http\Request;
use Neat\Http\Response;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

class Client
{
    /** @var ClientInterface */
    private $client;

    /** @var RequestFactoryInterface */
    private $requestFactory;

    /** @var StreamFactoryInterface */
    private $streamFactory;

    /**
     * Client constructor
     *
     * @param ClientInterface         $client
     * @param RequestFactoryInterface $requestFactory
     * @param StreamFactoryInterface  $streamFactory
     */
    public function __construct(ClientInterface $client, RequestFactoryInterface $requestFactory, StreamFactoryInterface $streamFactory)
    {
        $this->client         = $client;
        $this->requestFactory = $requestFactory;
        $this->streamFactory  = $streamFactory;
    }

    /**
     * @param string $method
     * @param string $url
     * @return Request
     */
    public function build(string $method, string $url): Request
    {
        return new Request($this->requestFactory->createRequest($method, $url));
    }

    /**
     * @param Request $request
     * @return Response
     * @throws ClientExceptionInterface
     */
    public function send(Request $request): Response
    {
        return new Response($this->client->sendRequest($request->psr()));
    }

    /**
     * @param string $url
     * @return Response
     * @throws ClientExceptionInterface
     */
    public function get(string $url): Response
    {
        $request = $this->build('GET', $url);

        return $this->send($request);
    }

    /**
     * @param string $url
     * @return Response
     * @throws ClientExceptionInterface
     */
    public function head(string $url): Response
    {
        $request = $this->build('HEAD', $url);

        return $this->send($request);
    }

    /**
     * @param string $url
     * @param mixed  $body
     * @return Response
     * @throws ClientExceptionInterface
     */
    public function post(string $url, $body): Response
    {
        $stream  = $this->streamFactory->createStream($body);
        $request = $this->build('POST', $url)->withBody($stream);

        return $this->send($request);
    }

    /**
     * @param string $url
     * @param mixed  $body
     * @return Response
     * @throws ClientExceptionInterface
     */
    public function put(string $url, $body): Response
    {
        $stream  = $this->streamFactory->createStream($body);
        $request = $this->build('PUT', $url)->withBody($stream);

        return $this->send($request);
    }

    /**
     * @param string $url
     * @return Response
     * @throws ClientExceptionInterface
     */
    public function delete(string $url): Response
    {
        $request = $this->build('DELETE', $url);

        return $this->send($request);
    }
}
