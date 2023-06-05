<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Validator;
use Hashids;

use App\ClientScoringType;
use App\ScoringType;
use App\Scoring;
use App\Client;

class ScoringController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($encodedClientId=null)
    {
        $decodedClientId = Hashids::decode($encodedClientId);
        $clients = Client::orderBy('title', 'ASC')->get();
        $scoringTypesQuery = ScoringType::orderby('title', 'ASC');
        if($encodedClientId){
            $scoringTypesQuery->join('client_scoring_types', 'client_scoring_types.scoring_type_id' ,'=' ,'scoring_types.id');
            $scoringTypesQuery->where('client_scoring_types.client_id', $decodedClientId);
        }
        $scoringTypes = $scoringTypesQuery->get();

        return view('scorings.index', [
            'clients' => $clients,
            'scoringTypes' => $scoringTypes,
            'encodedClientId' => $encodedClientId
        ]);
    }

    public function create()
    {
        $clients = Client::orderBy('title', 'ASC')->get();

        return view('scorings.create', [
            'clients' => $clients
        ]);
    }

    public function store(Request $request)
    {

        $rules = [
            'title' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please make sure the fields have been filled in correctly']);
        }

        $scoringType = new ScoringType;
        $scoringType->title = $request->title;
        $scoringType->save();

        ClientScoringType::where('scoring_type_id', $scoringType->id)->delete();
        if($request->clients){
            $data = [];

            foreach($request->clients as $clients)
            {
                $client_id = Hashids::decode($clients)[0];
                $data[] = [
                    'client_id' => $client_id,
                    'scoring_type_id' => $scoringType->id
                ];
            }
            ClientScoringType::insert($data);
        }

        return redirect()->route('scoring.index')->with(['success' => 'Scoring Matrix successfully created.']);
    }

    public function edit($id)
    {
        $decodedId = Hashids::decode($id);

        $scoringType = ScoringType::where('id', $decodedId)->first();

        $client_ids = Client::join('client_scoring_types', 'client_scoring_types.client_id', '=', 'clients.id')
            ->where('scoring_type_id', $decodedId)
            ->pluck('client_id')->toArray();

        $clients = Client::orderBy('title', 'ASC')->get();

        return view('scorings.edit', [
            'scoringType' => $scoringType,
            'clients' => $clients,
            'client_ids' => $client_ids,
            'id' => $id
        ]);
    }

    public function update(Request $request)
    {
        $decodedId = Hashids::decode($request->id)[0];

        $rules = [
            'title' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please make sure the fields have been filled in correctly']);
        }

        $scoringType = ScoringType::where('id', $decodedId)->first();
        $scoringType->title = $request->title;
        $scoringType->save();

        ClientScoringType::where('scoring_type_id', $scoringType->id)->delete();
        if($request->clients){
            $data = [];

            foreach($request->clients as $clients)
            {
                $client_id = Hashids::decode($clients)[0];
                $data[] = [
                    'client_id' => $client_id,
                    'scoring_type_id' => $scoringType->id
                ];
            }
            ClientScoringType::insert($data);
        }

        return redirect()->route('scoring.index')->with(['success' => 'Scoring Matrix successfully updated.']);
    }

    public function destroy($id)
    {
        $decodedId = Hashids::decode($id);

        ClientScoringType::where('scoring_type_id', $decodedId)->delete();
        Scoring::where('scoring_type_id', $decodedId)->delete();
        ScoringType::where('id', $decodedId)->delete();

        return [
            'success' => true,
            'message' => 'Scoring Matrix successfully deleted.'
        ];
    }

    public function list($encodedTypeId)
    {
        $decodedTypeId = Hashids::decode($encodedTypeId);

        $scorings = Scoring::where('scoring_type_id', $decodedTypeId)->orderBy('order', 'ASC')->get();

        return view('scorings.list', [
            'scorings' => $scorings,
            'encodedTypeId' => $encodedTypeId
        ]);
    }

    public function sort($encodedTypeId)
    {
        $decodedTypeId = Hashids::decode($encodedTypeId);

        $scorings = Scoring::where('scoring_type_id', $decodedTypeId)->orderBy('order', 'ASC')->get();

        return view('scorings.sort', [
            'scorings' => $scorings,
            'encodedTypeId' => $encodedTypeId
        ]);
    }

    public function sortUpdate(Request $request, $encodedTypeId)
    {
        $decodedTypeId = Hashids::decode($encodedTypeId)[0];

        $scoringString = $request->scoring;
        $explodedScoring = explode(',', $scoringString);

        foreach($explodedScoring as $key => $item) {
            $id = Hashids::decode($item)[0];
            Scoring::where('id', $id)
                ->where('scoring_type_id', $decodedTypeId)
                ->update([
                    'order' => $key+1
                ]);
        }

        return [
            'success' => true,
            'type' => 'success',
            'message' => 'Scoring sorted successfully.'
        ];
    }

    public function createItem($encodedTypeId)
    {

        return view('scorings.item.create', [
            'encodedTypeId' => $encodedTypeId
        ]);
    }

    public function storeItem(Request $request, $encodedTypeId)
    {

        $rules = [
            'description' => 'required',
            'criteria' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please make sure the fields have been filled in correctly']);
        }

        $decodedTypeId = Hashids::decode($encodedTypeId)[0];

        $scoringCount = Scoring::where('scoring_type_id', $decodedTypeId)->count();

        $scoring = new Scoring;
        $scoring->description = $request->description;
        $scoring->criteria = $request->criteria;
        $scoring->scoring_type_id = $decodedTypeId;
        $scoring->order = $scoringCount+1;
        $scoring->save();

        return redirect()->route('scoring.list', ['id' => $encodedTypeId])->with(['success' => 'Scoring Item successfully created.']);
    }

    public function editItem($encodedTypeId, $encodedScoringId)
    {
        $decodedScoringId = Hashids::decode($encodedScoringId);

        $scoring = Scoring::where('id', $decodedScoringId)->first();

        return view('scorings.item.edit', [
            'scoring' => $scoring,
            'encodedTypeId' => $encodedTypeId,
            'encodedScoringId' => $encodedScoringId
        ]);
    }

    public function updateItem($encodedTypeId, $encodedScoringId, Request $request)
    {
        $decodedScoringId = Hashids::decode($encodedScoringId)[0];

        $rules = [
            'description' => 'required',
            'criteria' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please make sure the fields have been filled in correctly']);
        }

        $scoring = Scoring::where('id', $decodedScoringId)->first();
        $scoring->description = $request->description;
        $scoring->criteria = $request->criteria;
        $scoring->save();

        return redirect()->route('scoring.list', ['typeId' => $encodedTypeId])->with(['success' => 'Scoring Item successfully updated.']);
    }

    public function destroyItem($encodedScoringId)
    {
        $decodedScoringId = Hashids::decode($encodedScoringId);

        Scoring::where('id', $decodedScoringId)->delete();

        return [
            'success' => true,
            'message' => 'Scoring Item successfully deleted.'
        ];
    }

}
