<?php declare(strict_types = 1);

namespace App\Infrastructure\Http\Response;

use Nette\Application\Response;
use Nette\Http\IRequest;
use Nette\Http\IResponse;
use Nette\Utils\Json;

class JsonResponse implements Response
{

	private mixed $payload;

	private int $responseCode;

	public function __construct(mixed $payload, int $responseCode = IResponse::S200_OK)
	{
		$this->payload = $payload;
		$this->responseCode = $responseCode;
	}

	public function send(IRequest $httpRequest, IResponse $httpResponse): void
	{
		$httpResponse->setContentType('application/json', 'utf-8');
		$httpResponse->setCode($this->responseCode);
		echo Json::encode($this->payload);
	}

	public function getResponseCode(): int
	{
		return $this->responseCode;
	}

	public function getPayload(): mixed
	{
		return $this->payload;
	}

}
