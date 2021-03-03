<?php

namespace EscolaSoft\LaravelH5p\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\H5pEmbedRequest;
use EscolaSoft\LaravelH5p\Events\H5pEvent;
use EscolaSoft\LaravelH5p\LaravelH5p;

class EmbedController extends Controller
{
    private LaravelH5p $h5p;

    /**
     * EmbedController constructor.
     * @param LaravelH5p $h5p
     */
    public function __construct(LaravelH5p $h5p)
    {
        $this->h5p = $h5p;
    }

    public function __invoke(H5pEmbedRequest $request, int $id)
    {
        try {
            $settings = $this->h5p->get_editor();
            $content = $this->h5p->get_content($id);
            $embed = $this->h5p->get_embed($content, $settings);
            $embed_code = $embed['embed'];
            $settings = $embed['settings'];
            $user = $request->user();

            event(new H5pEvent('content', null, $content['id'], $content['title'], $content['library']['name'], $content['library']['majorVersion'], $content['library']['minorVersion']));

            return view('h5p.content.embed', compact('settings', 'user', 'embed_code'));
        } catch (\Exception $e) {
            abort(404);
        }
    }
}
