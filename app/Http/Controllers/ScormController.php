<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Peopleaps\Scorm\Manager\ScormManager;
use Illuminate\Http\Request;
use Peopleaps\Scorm\Model\ScormModel;
use Peopleaps\Scorm\Model\ScormScoModel;
use Peopleaps\Scorm\Model\ScormScoTrackingModel;
use App\Library\ScormHelper;
use Illuminate\Support\Facades\Storage;

class ScormController extends Controller
{
    private ScormManager $manager;

    public function __construct()
    {
        $this->helper = new ScormHelper(); // TODO bind
    }

    public function upload(Request $request)
    {
        $file = $request->file('zip');
        $data = $this->helper->uploadScormArchive($file);
        $data = $this->helper->removeRecursion($data);

        return response()->json($data);
        // parseScormArchive(Upl);
    }

    public function parse(Request $request)
    {
        $file = $request->file('zip');
        $data = $this->helper->parseScormArchive($file);
        $data = $this->helper->removeRecursion($data);
        return response()->json($data);
    }

    public function show(string $uuid, Request $request)
    {
        $data = $this->helper->getScoByUuid($uuid);
        $data['entry_url_absolute'] = Storage::url('scorm/'.$data->scorm->version.'/'.$data->scorm->uuid.'/'.$data->entry_url);

        $data['player'] = (object) [
            'lmsCommitUrl' => '/api/lms',
            'logLevel'=> 1,
            'autoProgress' => true,
            'cmi' => [] // cmi is user progress
        ];
        return view('scorm', ['data' => $data]);
    }
}
