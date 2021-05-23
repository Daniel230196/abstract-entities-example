<?php

declare(strict_types=1);

namespace Http;


/**
 * сущность http-ответа
 * Class Response
 * @package Http
 */
class Response
{
    /**
     * Содержимое ответа
     * @var string|null
     */
    private ?string $content;


    private array $contentAction;
    /**
     * HTTP-статус ответа
     * @var int
     */
    private int $statusCode;

    /**
     * @var string
     */
    private string $statusText;

    /**
     * Заголовки ответа
     * @var array
     */
    private array $headers;

    public function __construct(array $headers, int $statusCode, array $contentAction, ?string $content = "")
    {
        $this->content = $content;
        $this->statusCode = $statusCode;
        $this->contentAction = $contentAction;
        $this->headers = $headers;
    }

    /**
     * Добавить заголовок ответа
     * @param string $header
     */
    public function setHeader(string $header)
    {
        $this->headers[] = $header;
    }

    /**
     * Установить статус ответа
     * @param int $statusCode
     */
    public function setStatus(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    /**
     * Установить тело ответа
     * @param string $content
     */
    public function setContent(string $content)
    {
        $this->content = $content;
    }

    /**
     * @param string $text
     */
    public function setStatusText(string $text): void
    {
        $this->statusText = $text;
    }

    /**
     * Разрешение ответа. Заголовки + контент
     * @return void
     */
    public function resolve(): void
    {

        if(empty($this->content)){
            $this->resolveAction();
        }

        $this->sendHeaders();
        $this->sendContent();
    }

    /**
     * Разрешить метод контроллера в контент
     * @return void
     */
    private function resolveAction(): void
    {
        $this->content = $this->contentAction['controller']->{$this->contentAction['action']}();
    }

    /**
     * Отправить заголовки ответа
     * @return void
     */
    private function sendHeaders(): void
    {
        if($this->statusCode!==200){
            header('HTTP/1.1 ' . $this->statusCode . ' ' .  $this->statusText);
        }
        foreach ($this->headers as $header){
            header($header);
        }
    }

    /**
     * Вывести содержимое в браузер
     */
    private function sendContent(): void
    {
        echo $this->content;
    }

}
