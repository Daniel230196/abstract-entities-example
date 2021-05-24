<?php
declare(strict_types=1);

namespace Http;


/**
 * Class Request
 * Сущность http-запроса
 * @package Http
 */
class Request
{
    /**
     * Массив get-запроса
     * @var ?array
     */
    private ?array $get;

    /**
     * Массив post-запроса
     * @var ?array
     */
    private ?array $post;

    /**
     * Строка запроса
     * @var string
     */
    private string $uri;

    /**
     * Метод запроса
     * @var string
     */
    private string $method;

    /**
     * Заголовки запроса
     * @var ?array
     */
    private ?array $headers;

    /**
     * IP клиента
     * @var string|null
     */
    private ?string $client;

    /**
     * Обработчик сессий
     * @var \SessionHandlerInterface
     */
    private \SessionHandlerInterface $session;

    public function __construct(array $get = [], array $post = [], array $headers = [], string $method = 'GET', string $uri = '/')
    {
        $this->get = $this->clear($get);
        $this->post = $this->clear($post);
        $this->headers = $headers;
        $this->method = $method;
        $this->uri = $uri;
        $this->client = $_SERVER['REMOTE_ADDR'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getIp(): ?string
    {
        return $this->client;
    }

    public function setSession(\SessionHandlerInterface $sessionHandler)
    {
        $this->session = $sessionHandler;
    }

    /**
     * Создать запрос из глобальных переменных
     * @return Request
     */
    public static function fromGlobals(): Request
    {
        return new Request($_GET, $_POST, getallheaders(), $_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
    }

    /**
     * Проверка ajax-запроса
     * @return bool
     */
    public function isAjax(): bool
    {
        return (!empty($this->headers()['X-Requested-with']));
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->$name ?? null;
    }

    /**
     * @param string $name
     * @param $value
     */
    public function __set(string $name, $value)
    {
        $this->$name = $value;
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->$name);
    }


    /**
     * Получить метод контроллера из строки запроса
     * @return ?string
     */
    public function action(): ?string
    {

        $method = $this->explodeUri()[2] ?? null;

        if(!is_null($method)){
            return strpos($method, '?') ? stristr($method, '?', true) : $method ;
        }

        return null;
    }

    /**
     * Полчить имя контроллера
     * @return ?string
     */
    public function controller(): ?string
    {
        return $this->explodeUri()[1] ?? null;
    }

    /**
     * Разбить строку запроса
     * @return array
     */
    private function explodeUri(): array
    {
        return explode('/',$this->uri);
    }

    /**
     * @param array $data Данные GET/POST
     * @return array
     */
    private function clear(array $data): array
    {
        foreach ($data as &$datum){
            if(is_string($datum)){
                $datum = trim($datum);
                $datum = stripcslashes($datum);
                $datum = htmlspecialchars($datum);
            }
        }
        return $data;
    }

    /**
     * Получить заголовки запроса
     * @return array
     */
    private function headers(): ?array
    {
        return getallheaders();
    }
}