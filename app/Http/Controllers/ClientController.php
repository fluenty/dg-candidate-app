<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\ClientModerator;
use App\ClientCandidate;
use App\Client;
use Validator;
use Hashids;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $clients = Client::get();

        return view('clients.index', [
            'clients' => $clients
        ]);
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {

        $rules = [
            'title' => 'required'
        ];

        $messages = [
            'title.required' => 'Please enter Client title.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please make sure the fields have been filled in correctly']);
        }

        $client = new Client;
        $client->title = $request->title;
        $client->role_title = $request->role_title;
        $client->report_title = $request->report_title;
        $client->definitions = $request->definitions;
        $client->welcome_message = $request->welcome_message;
        ($request->report_url_pin !== '') ? ($client->report_url_pin = bcrypt($request->report_url_pin)) : '';
        $client->disable_report_url = $request->disable_report_url ? 1 : 0;
        $client->save();

        return redirect()->route('client.index')->with(['success' => 'Client successfully created.']);
    }

    public function edit($id)
    {
        $decodedId = Hashids::decode($id);

        $client = Client::where('id', $decodedId)->first();

        return view('clients.edit', [
            'client' => $client,
            'id' => $id
        ]);
    }

    public function update(Request $request)
    {
        $rules = [
            'title' => 'required'
        ];

        $messages = [
            'title.required' => 'Please enter Client title.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please make sure the fields have been filled in correctly']);
        }

        $decodedId = Hashids::decode($request->id);

        $client = Client::where('id', $decodedId)->first();
        $client->title = $request->title;
        $client->role_title = $request->role_title;
        $client->report_title = $request->report_title;
        $client->definitions = $request->definitions;
        $client->welcome_message = $request->welcome_message;
        ($request->report_url_pin !== null) ? ($client->report_url_pin = bcrypt($request->report_url_pin)) : '';
        $client->disable_report_url = $request->disable_report_url ? 1 : 0;
        $client->save();

        return redirect()->route('client.index')->with(['success' => 'Client successfully updated.']);
    }

    public function destroy($id)
    {
        $decodedId = Hashids::decode($id);

        $client_moderators = ClientModerator::where('client_id', $decodedId)->count();
        $client_candidates = ClientCandidate::where('client_id', $decodedId)->count();

        if($client_candidates || $client_candidates)
        {
            return [
                'success' => false,
                'message' => 'Client has Moderators and/or Candidates.'
            ];
        }

        Client::where('id', $decodedId)->delete();

        return [
            'success' => true,
            'message' => 'Client successfully deleted.'
        ];
    }
}
