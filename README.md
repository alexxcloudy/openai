# openai
Proyecto Laravel para llamar a las API de OpenAI desde línea de comando

Más información del proyecto en:

https://xcloudy.es/openai-en-laravel-en-5-minutos

1. Crea una nueva aplicación Laravel
PreRequisitos : PHP 8.1.0 o superior

Para crear nuestro nuevo proyecto de Laravel llamado openai, si esto te da problemas sigue la guía de la documentación de Laravel

Ejecutamos: (Nota: no hay que copiar el símbolo $)

$ composer create-project laravel/laravel openai
Este comando puede tardar uno o dos minutos en completarse mientras el composer descarga las dependencias del del proyecto y de Laravel. 

Una vez haya finalizado, instalaremos el paquete openai-php ejecutando el comando:

$ composer require openai-php/laravel
2. Obtenemos la clave de la API openai
Llegados a este punto, para poder consumir la api de OpenAI, es necesario tener una cuenta en la plataforma. Con la creación, nos dan 18$ de crédito para que podamos hacer pruebas. Nos vamos a la sección de las API-keys E y le damos a «Create New Secret Key» que lo que hará es crear nueva clave que deberemos copiar, ya que solo la mostrará una vez:

Nueva API KEY OpenAI en Laravel
Nueva API KEY OpenAI en Laravel
Una vez copiada la API key, nos vamos a nuestro proyecto de Laravel y en el fichero que está en la raíz del proyecto .env, añadimos una nueva línea, donde pegaremos nuestra API-key, reemplaza la mía que es: sk-Irhe0pEypmT4JsssWgJT3BlbkFJYYKjw8u8yCtSjklt0PYO

OPENAI_API_KEY="sk-Irhe0pEypmT4JsssWgJT3BlbkFJYYKjw8u8yCtSjklt0PYO"
3. Creamos un Comando en Laravel
Para este ejemplo de proyecto crearemos un comando en Laravel para ejecutar nuestro código desde línea de comandos, y que la salida también sea por pantalla.

Algo más elaborado sería generar un formulario con lo que queremos crear y que lo mostrase por pantalla en el navegador, pero eso quizás lo hagamos en otro tutorial.

Para crear el nuevo comando OpenAiCommand, ejecutamos desde el terminal:

$ php artisan make:command --command openai OpenAiCommand
Si todo ha ido bien, se debería haber creado un nuevo fichero «app\Console\Commands\OpenAiCommand.php» y si ejecutamos un php artisan list saldrá nuestro comando nuevo con la descripción genérica:

$ php artisan list|grep openai
  openai                 Llamada a OpenAI para que escriba un texto según promt
4. Editar Comando de Laravel
Ya casi lo tenemos, ahora nos queda editar el fichero creado anteriormente «OpenAiCommand.php» y añadiremos:

la importación de la librería:

use OpenAI\Laravel\Facades\OpenAI;

Y modificaremos el handle, asignándole el modelo que queremos usar, en nuestro caso: text-davinci-003

El código se quedaría así:

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
    protected $description = 'Llamada a OpenAI para que escriba un texto según prompt';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // OpenAI en Laravel
        $result = OpenAI::completions()->create([
            'model' => 'text-davinci-003',
            'prompt' => 'Escribe un poema sobre mi odio a desarrollar con el lenguaje css.',
            'max_tokens' => 350,
        ]);

        $this->line(ltrim($result->choices[0]->text));
        return Command::SUCCESS;
    }
}
5. Guardar todo y ejecutar
Ya esta todo, le damos a guardar todo y desde línea de comando ejecutamos:

$ php artisan openai
¡¡¡ Y ya lo tenemos un proyecto con OpenAI en Laravel!!!
$ php artisan openai

<div class="poem">
    <p>Las lineas de código me inspiran odio,<br>
    Habitualmente e intento evadirlo,<br>
    Pero la curva de aprendizaje para CSS,<br>
    Es curva que me continúa haciendo llorar.
</p>

<p>Estilo, cajas, imágenes, diseños,<br>
    Pegados juntos, formando CSS divino,<br>
    Y sin mis esfuerzos continuos,<br>
    El hacerlo seguirá siendo un destino.
</p>

<p>Profundizar en comprender el lenguaje<br>
    Y emplear sus elementos de forma correcta,<br>
    Me siento más frustrado que una profecía hecha por un oráculo,<br>
    Mientras la página no carga con éx

C:\Users\alex\Documents\Proyectos\LARAVEL\OPENAI\openai>php artisan openai
/*Un profundo odio siento en mi pecho
Cuando algo sale mal con este lenguaje
Solo tengo este odio que me custiodia
Odie a desarrollar con CSS*/

body{
    background-color: darkorange;
    font-family: Arial;
  }

  #poema{
    width: 50%;
    margin: 0 auto;
  }

  #poema p{
    font-size: 22px;
    font-weight: bold;
    line-height: 26px;
    margin: 20px 0;
  }

  #poema p::before{
    content: "Un profundo odio siento en mi pecho\n";
    font-style: italic;
  }

  #poema p::after{
    content: "\nOdie a desarrollar con CSS";
    font-style: italic;
  }
Si en vez de crear un poema con las bondades del CSS, quisiésemos generar imágenes con DALL-e, usando un texto descriptivo, podríamos modificar nuestro código modificando la función handle del fichero «OpenAiCommand.php» , del siguiente modo:

public function handle()
    {

        // OpenAI en Laravel
        // $result = OpenAI::completions()->create([
        //     'model' => 'text-davinci-003',
        //     'prompt' => 'Escribe un poema sobre mi odio a desarrollar con el lenguaje css.',
        //     'max_tokens' => 350,
        // ]);

        // $this->line(ltrim($result->choices[0]->text));

        $response = OpenAI::images()->create([
            'prompt' => 'Hyperrealistic pencil drawing of grizzled spaniard old man with a large, bushy beard and bulbous nose.',
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
Y el resultado sería, una url con la imagen siguiente:


prompt: «Hyperrealistic pencil drawing of grizzled spaniard old man with a large, bushy beard and bulbous nose»
Como veis es muy sencillo montar un prototipo que nos permite llamar a OpenAI rápidamente desde Laravel sin necesidad de emplear mucho tiempo en el proceso.

Si os interesa profundizar más o usar modelos distintos, revisar la documentación de la librería de Nuno Maduro:

https://github.com/openai-php/client

Espero que os haya gustado y si tienes dudas, contacta con nosotros
