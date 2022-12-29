<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use OpenAI\Laravel\Facades\OpenAI;

class OpenAiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'openai';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Llamada a OpenAI para que escriba un texto según promt';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $result = OpenAI::completions()->create([
        //     'model' => 'text-davinci-003',
        //     'prompt' => 'Escribe un poema sobre mi odio a desarrollar con el lenguaje css.',
        //     'max_tokens' => 350,
        // ]);

        // $this->line(ltrim($result->choices[0]->text));

        $response = OpenAI::images()->create([
            'prompt' => 'Hyperrealistic pencil drawing of grizzled spaniard old man with a large, bushy beard and bulbous nose. ',
            'n' => 1,
            'size' => '256x256',
            'response_format' => 'url',
        ]);

        $response->created; // 1589478378

        foreach ($response->data as $data) {
            $data->url; // 'https://oaidalleapiprodscus.blob.core.windows.net/private/...'
            $data->b64_json; // null
            $this->line($data->url);
        }

        $response->toArray(); // ['created' => 1589478378, data => ['url' => 'https://oaidalleapiprodscus...', ...]]

        return Command::SUCCESS;
    }
}
