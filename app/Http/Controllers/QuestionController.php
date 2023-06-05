<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Validator;
use Hashids;

use App\ClientQuestion;
use App\ScoringType;
use App\Question;
use App\Client;

class QuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($encodedClientId=null)
    {
        $decodedClientId = Hashids::decode($encodedClientId);
        $clients = Client::orderBy('title', 'ASC')->get();
        $questionsQuery = Question::orderBy('order', 'ASC');
        if($encodedClientId){
            $questionsQuery->join('client_questions', 'client_questions.question_id' ,'=' ,'questions.id');
            $questionsQuery->where('client_questions.client_id', $decodedClientId);
        }
        $questions = $questionsQuery->get();

        return view('questions.index', [
            'clients' => $clients,
            'questions' => $questions,
            'encodedClientId' => $encodedClientId
        ]);
    }

    public function create()
    {
        $clients = Client::orderBy('title', 'ASC')->get();

        $scoringTypes = ScoringType::get();

        return view('questions.create', [
            'clients' => $clients,
            'scoringTypes' => $scoringTypes
        ]);
    }

    public function store(Request $request)
    {

        $rules = [
            'text' => 'required',
            'scoring_type_id' => 'required'
        ];

        $messages = [
            'text.required' => 'Please enter Question text.',
            'text.required' => 'Please select Scoring Type.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please make sure the fields have been filled in correctly']);
        }

        $decodedScoringTypeId = Hashids::decode($request->scoring_type_id)[0];

        $question = new Question;
        $question->text = $request->text;
        $question->scoring_type_id = $decodedScoringTypeId;
        $question->save();

        ClientQuestion::where('question_id', $question->id)->delete();
        if($request->clients){
            $data = [];

            foreach($request->clients as $clients)
            {
                $client_id = Hashids::decode($clients)[0];
                $data[] = [
                    'client_id' => $client_id,
                    'question_id' => $question->id
                ];
            }
            ClientQuestion::insert($data);
        }

        return redirect()->route('question.index')->with(['success' => 'Question successfully created.']);
    }

    public function edit($id)
    {
        $decodedId = Hashids::decode($id);

        $question = Question::where('id', $decodedId)->first();

        $client_ids = Client::join('client_questions', 'client_questions.client_id', '=', 'clients.id')
            ->where('question_id', $decodedId)
            ->pluck('client_id')->toArray();

        $clients = Client::orderBy('title', 'ASC')->get();

        $scoringTypes = ScoringType::get();

        return view('questions.edit', [
            'question' => $question,
            'clients' => $clients,
            'client_ids' => $client_ids,
            'id' => $id,
            'scoringTypes' => $scoringTypes
        ]);
    }

    public function update(Request $request)
    {
        $rules = [
            'text' => 'required',
            'scoring_type_id' => 'required'
        ];

        $messages = [
            'text.required' => 'Please enter Question text.',
            'text.required' => 'Please select Scoring Type.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please make sure the fields have been filled in correctly']);
        }

        $decodedId = Hashids::decode($request->id);
        $decodedScoringTypeId = Hashids::decode($request->scoring_type_id)[0];

        $question = Question::where('id', $decodedId)->first();
        $question->text = $request->text;
        $question->scoring_type_id = $decodedScoringTypeId;
        $question->save();

        ClientQuestion::where('question_id', $question->id)->delete();
        if($request->clients){
            $data = [];

            foreach($request->clients as $clients)
            {
                $client_id = Hashids::decode($clients)[0];
                $data[] = [
                    'client_id' => $client_id,
                    'question_id' => $question->id
                ];
            }
            ClientQuestion::insert($data);
        }

        return redirect()->route('question.index')->with(['success' => 'Question successfully updated.']);
    }

    public function destroy($id)
    {
        $decodedId = Hashids::decode($id);

        ClientQuestion::where('question_id', $decodedId)->delete();
        Question::where('id', $decodedId)->delete();

        return [
            'success' => true,
            'message' => 'Question successfully deleted.'
        ];
    }

    public function sort($encodedClientId)
    {
        $decodedClientId = Hashids::decode($encodedClientId);

        $questions = Question::orderBy('order', 'ASC')
            ->join('client_questions', 'client_questions.question_id' ,'=' ,'questions.id')
            ->where('client_questions.client_id', $decodedClientId)
            ->get();

        return view('questions.sort', [
            'questions' => $questions,
            'encodedClientId' => $encodedClientId
        ]);
    }

    public function sortUpdate(Request $request, $encodedClientId)
    {
        $decodedClientId = Hashids::decode($encodedClientId)[0];

        $questionString = $request->questions;
        $explodedQuestions = explode(',', $questionString);

        foreach($explodedQuestions as $key => $item) {
            $id = Hashids::decode($item)[0];

            Question::join('client_questions', 'client_questions.question_id' ,'=' ,'questions.id')
                ->where('client_questions.client_id', $decodedClientId)
                ->where('questions.id', $id)
                ->update([
                    'order' => $key+1
                ]);
        }

        return [
            'success' => true,
            'type' => 'success',
            'message' => 'Questions sorted successfully.'
        ];
    }

}
