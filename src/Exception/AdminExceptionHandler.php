<?php

declare(strict_types=1);
namespace Oyhdd\Admin\Exception;

use Throwable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Contract\SessionInterface;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Utils\Context;
use Hyperf\Utils\Str;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PlainTextHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Handler\XmlResponseHandler;
use Whoops\Run;
use Oyhdd\Admin\Common\Log;

class AdminExceptionHandler extends ExceptionHandler
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var StdoutLoggerInterface
     */
    protected $stdoutLogger;

    public function __construct(ServerRequestInterface $request, StdoutLoggerInterface $logger)
    {
        $this->request      = $request;
        $this->stdoutLogger = $logger;
    }

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $message = $throwable->getMessage();
        $line = $throwable->getLine();
        $file = $throwable->getFile();
        $log_msg = sprintf('%s[%s] in %s', $message, $line, $file);
        $log_trace = $throwable->getTraceAsString();

        Log::error($log_msg);
        Log::error($log_trace);
        $this->stdoutLogger->error($log_msg);
        $this->stdoutLogger->error($log_trace);

        if (config('admin.debug')) {
            if (!$this->isAjax() && class_exists(Run::class)) {
                $whoops = new Run();
                [$handler, $contentType] = $this->negotiateHandler();

                $whoops->pushHandler($handler);
                $whoops->allowQuit(false);
                ob_start();
                $whoops->{Run::EXCEPTION_HANDLER}($throwable);
                $content = ob_get_clean();
                $response->withHeader('Content-Type', $contentType);
            } else {
                $content = json_encode([
                    'type'    => get_class($throwable),
                    'message' => $message,
                    'file'    => $file,
                    'line'    => $line,
                    'trace'   => $log_trace,
                ], JSON_UNESCAPED_UNICODE);
    
                admin_toastr($message, 'error');
            }
        } else {
            $message = 'Internal Server Error.';
            $content = json_encode([
                'message' => $message,
            ], JSON_UNESCAPED_UNICODE);
            admin_toastr($message, 'error');
        }

        $this->stopPropagation();
        return $response
            ->withStatus(500)
            ->withBody(new SwooleStream($content));
    }

    public function isValid(Throwable $th): bool
    {
        return true;
    }

    protected static $preference = [
        'text/html' => PrettyPageHandler::class,
        'application/json' => JsonResponseHandler::class,
        'application/xml' => XmlResponseHandler::class,
    ];

    protected function negotiateHandler()
    {
        $accepts = $this->request->getHeaderLine('accept');
        foreach (self::$preference as $contentType => $handler) {
            if (Str::contains($accepts, $contentType)) {
                return [$this->setupHandler(new $handler()),  $contentType];
            }
        }
        return [new PlainTextHandler(),  'text/plain'];
    }

    protected function setupHandler($handler)
    {
        if ($handler instanceof PrettyPageHandler) {
            $handler->handleUnconditionally(true);

            if (defined('BASE_PATH')) {
                $handler->setApplicationRootPath(BASE_PATH);
            }

            $handler->addDataTableCallback('PSR7 Query', [$this->request, 'getQueryParams']);
            $handler->addDataTableCallback('PSR7 Post', [$this->request, 'getParsedBody']);
            $handler->addDataTableCallback('PSR7 Server', [$this->request, 'getServerParams']);
            $handler->addDataTableCallback('PSR7 Cookie', [$this->request, 'getCookieParams']);
            $handler->addDataTableCallback('PSR7 File', [$this->request, 'getUploadedFiles']);
            $handler->addDataTableCallback('PSR7 Attribute', [$this->request, 'getAttributes']);

            $session = Context::get(SessionInterface::class);
            if ($session) {
                $handler->addDataTableCallback('Hyperf Session', [$session, 'all']);
            }
        } elseif ($handler instanceof JsonResponseHandler) {
            $handler->addTraceToOutput(true);
        }

        return $handler;
    }

    private function isAjax(): bool
    {
        return $this->request->header('X-Requested-With') == 'XMLHttpRequest';
    }
}
