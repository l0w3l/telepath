<?php

declare(strict_types=1);

namespace Lowel\Telepath\Http\Middlewares\ErrorHandlers;

use Illuminate\Http\Request;
use Lowel\Telepath\Facades\Extrasense;
use Lowel\Telepath\Facades\SpiritBox;
use Phptg\BotApi\Type\InputFile;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;

class ErrorReportMiddleware
{
    public function handle(Request $request, \Closure $next)
    {
        try {
            return $next($request);
        } catch (\Throwable $exception) {
            if (Extrasense::profile()->chatIdFallback !== null) {
                $this->addReportFallbackInTheChat($exception);
            }

            throw $exception;
        }
    }

    private function addReportFallbackInTheChat(\Throwable $exception): void
    {
        $cloner = new VarCloner;
        $dumper = new HtmlDumper;

        $stream = fopen('php://memory', 'r+');

        fwrite($stream, $dumper->dump($cloner->cloneVar(Extrasense::update()), true));
        fwrite($stream, $dumper->dump($cloner->cloneVar($exception), true));
        fwrite($stream, $dumper->dump($cloner->cloneVar($this->telepathConfigWithoutSecrets()), true));

        rewind($stream);

        SpiritBox::sendDocument(
            new InputFile($stream, 'report_'.now()->format('Y-m-d_H-i-s').'.html'),
            chatId: Extrasense::profile()->chatIdFallback,
            caption: substr('Report by '.now()->toString()."\n\nMessage: {$exception->getMessage()}", 0, 1023)
        );

        fclose($stream);
    }

    private function telepathConfigWithoutSecrets(): array
    {
        $config = config('telepath');

        data_set($config, 'hook.secret', '...');

        foreach ($config['profiles'] as $key => $profile) {
            data_set($config, "profiles.{$key}.token", '...');
        }

        return $config;
    }
}
