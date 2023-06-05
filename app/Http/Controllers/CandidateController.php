<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Validator;
use Hashids;
use Storage;
use Auth;
use DB;

use App\ModeratorQuestion;
use App\ClientCandidate;
use App\CandidateFile;
use App\CandidateAudio;
use App\Question;
use App\Client;
use App\User;
use App\File;

class CandidateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($encodedClientId=null)
    {

        $decodedClientId = Hashids::decode($encodedClientId);
        $clients = Client::orderBy('title', 'ASC')->get();
        $candidatesQuery = User::where('user_type_id', 3);
        if($encodedClientId){
            $candidatesQuery->join('client_candidates', 'client_candidates.candidate_id' ,'=' ,'users.id');
            $candidatesQuery->where('client_candidates.client_id', $decodedClientId);
        }
        $candidates = $candidatesQuery->get();

        return view('candidates.index', [
            'clients' => $clients,
            'candidates' => $candidates,
            'encodedClientId' => $encodedClientId
        ]);
    }

    public function create()
    {
        $clients = Client::orderBy('title', 'ASC')->get();

        return view('candidates.create', [
            'clients' => $clients
        ]);
    }

    public function store(Request $request)
    {

        $rules = [
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required|unique:users'
        ];

        $messages = [];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please make sure the fields have been filled in correctly']);
        }

        if($request->avatar){

            $extension = $request->avatar->getClientOriginalExtension();
            $filename  = 'avatar-'.time().'.'.$extension;

            $avatar = new File;
            $avatar->name = $request->avatar->storeAs('/', $filename);
            $avatar->extension = $extension;
            $avatar->mime = $request->avatar->getClientMimeType();
            $avatar->size = $request->avatar->getSize();
            $avatar->save();

        }

        $candidate = new User;
        $candidate->user_type_id = 3;
        $candidate->name = $request->name;
        $candidate->surname = $request->surname;
        $candidate->email = $request->email;
        $candidate->cellphone = $request->cellphone;
        $candidate->password = bcrypt('secret');
        $candidate->pin = bcrypt($request->pin);
        $candidate->title = $request->title;
        $candidate->bio = $request->bio;
        isset($avatar) ? $candidate->avatar_id = $avatar->id : null;
        $candidate->save();

        ClientCandidate::where('candidate_id', $candidate->id)->delete();
        if($request->clients){
            $data = [];

            foreach($request->clients as $clients)
            {
                $client_id = Hashids::decode($clients)[0];
                $data[] = [
                    'client_id' => $client_id,
                    'candidate_id' => $candidate->id
                ];
            }
            ClientCandidate::insert($data);
        }

        if(count($request->file('files'))){
            foreach ($request->file('files') as $i => $file) {

                $extension = $file->getClientOriginalExtension();
                $filename  = strtolower($candidate->name).'-'.strtolower($candidate->surname).'-'.time().$i.'.'.$extension;

                $uploadedFile = new File;
                $uploadedFile->name = $file->storeAs('/', $filename);
                $uploadedFile->extension = $extension;
                $uploadedFile->mime = $file->getClientMimeType();
                $uploadedFile->size = $file->getSize();
                $uploadedFile->save();

                CandidateFile::insert([
                    'candidate_id' => $candidate->id,
                    'file_id' => $uploadedFile->id
                ]);
            }
        }
        if(count($request->file('audios') )){
            foreach ($request->file('audios') as $i => $audio) {

                $extension = $audio->getClientOriginalExtension();
                $filename  = strtolower($candidate->name).'-'.strtolower($candidate->surname).'-'.time().$i.'.'.$extension;

                $uploadedFile = new File;
                $uploadedFile->name = $audio->storeAs('/', $filename);
                $uploadedFile->extension = $extension;
                $uploadedFile->mime = $audio->getClientMimeType();
                $uploadedFile->size = $audio->getSize();
                $uploadedFile->save();

                CandidateAudio::insert([
                    'candidate_id' => $candidate->id,
                    'file_id' => $uploadedFile->id
                ]);
            }
        }

        return redirect()->route('candidate.index')->with(['success' => 'Candidate successfully created.']);
    }

    public function edit($id)
    {
        $decodedId = Hashids::decode($id);

        $candidate = User::where('id', $decodedId)->first();

        $candidateFiles = CandidateFile::join('files', 'candidate_files.file_id', '=', 'files.id')
            ->where('candidate_files.candidate_id', $candidate->id)
            ->get();

        $candidateAudios = CandidateAudio::join('files', 'candidate_audios.file_id', '=', 'files.id')
            ->where('candidate_audios.candidate_id', $candidate->id)
            ->get();

        $avatar = File::where('id', $candidate->avatar_id)->first();

        $client_ids = Client::join('client_candidates', 'client_candidates.client_id', '=', 'clients.id')
            ->where('candidate_id', $decodedId)
            ->pluck('client_id')->toArray();

        $clients = Client::orderBy('title', 'ASC')->get();

        return view('candidates.edit', [
            'candidate' => $candidate,
            'clients' => $clients,
            'client_ids' => $client_ids,
            'id' => $id,
            'candidateFiles' => $candidateFiles,
            'candidateAudios' => $candidateAudios,
            'avatar' => $avatar
        ]);
    }

    public function update(Request $request)
    {
        $decodedId = Hashids::decode($request->id)[0];

        $rules = [
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required|unique:users,email,'.$decodedId
        ];

        $messages = [];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please make sure the fields have been filled in correctly']);
        }

        if($request->avatar){

            $extension = $request->avatar->getClientOriginalExtension();
            $filename  = 'avatar-'.time().'.'.$extension;

            $avatar = new File;
            $avatar->name = $request->avatar->storeAs('/', $filename);
            $avatar->extension = $extension;
            $avatar->mime = $request->avatar->getClientMimeType();
            $avatar->size = $request->avatar->getSize();
            $avatar->save();

        }

        $candidate = User::where('id', $decodedId)->first();
        $candidate->name = $request->name;
        $candidate->surname = $request->surname;
        $candidate->email = $request->email;
        $candidate->cellphone = $request->cellphone;
        ($request->pin != '') ? ($candidate->pin = bcrypt($request->pin)) : '';
        $candidate->title = $request->title;
        $candidate->bio = $request->bio;
        isset($avatar) ? $candidate->avatar_id = $avatar->id : null;
        $candidate->save();

        ClientCandidate::where('candidate_id', $decodedId)->delete();
        if($request->clients){
            $data = [];

            foreach($request->clients as $clients)
            {
                $client_id = Hashids::decode($clients)[0];
                $data[] = [
                    'client_id' => $client_id,
                    'candidate_id' => $decodedId
                ];
            }
            ClientCandidate::insert($data);
        }

        if(count($request->file('files'))){
            foreach ($request->file('files') as $i => $file) {

                $extension = $file->getClientOriginalExtension();
                $filename  = strtolower($candidate->name).'-'.strtolower($candidate->surname).'-'.time().$i.'.'.$extension;

                $uploadedFile = new File;
                $uploadedFile->name = $file->storeAs('/', $filename);
                $uploadedFile->extension = $extension;
                $uploadedFile->mime = $file->getClientMimeType();
                $uploadedFile->size = $file->getSize();
                $uploadedFile->save();

                CandidateFile::insert([
                    'candidate_id' => $candidate->id,
                    'file_id' => $uploadedFile->id
                ]);
            }
        }
        if(count($request->file('audios') )){
            foreach ($request->file('audios') as $i => $audio) {

                $extension = $audio->getClientOriginalExtension();
                $filename  = strtolower($candidate->name).'-'.strtolower($candidate->surname).'-'.time().$i.'.'.$extension;

                $uploadedFile = new File;
                $uploadedFile->name = $audio->storeAs('/', $filename);
                $uploadedFile->extension = $extension;
                $uploadedFile->mime = $audio->getClientMimeType();
                $uploadedFile->size = $audio->getSize();
                $uploadedFile->save();

                CandidateAudio::insert([
                    'candidate_id' => $candidate->id,
                    'file_id' => $uploadedFile->id
                ]);
            }
        }

        // Update the candidate document descriptions.
        if (!empty($request->candidate_documents) && is_array($request->candidate_documents)) {
            foreach ($request->candidate_documents as $file_id => $description) {
                File::where('id', $file_id)
                    ->update(['description' => $description]);
            }
        }

        return redirect()->route('candidate.index')->with(['success' => 'Candidate successfully updated.']);
    }

    public function destroy($id)
    {
        $decodedId = Hashids::decode($id);

        User::where('id', $decodedId)->delete();

        return [
            'success' => true,
            'message' => 'Candidate successfully deleted.'
        ];
    }

    public function destroyFile($id)
    {
        $decodedId = Hashids::decode($id);

        $file = File::where('id', $decodedId)->first();

        Storage::delete($file->name);

        CandidateFile::where('file_id', $decodedId)->delete();
        File::where('id', $decodedId)->delete();

        return [
            'success' => true,
            'message' => 'File successfully deleted.'
        ];
    }

    public function destroyAudio($id)
    {
        $decodedId = Hashids::decode($id);

        $file = File::where('id', $decodedId)->first();

        Storage::delete($file->name);

        CandidateAudio::where('file_id', $decodedId)->delete();
        File::where('id', $decodedId)->delete();

        return [
            'success' => true,
            'message' => 'Audio File successfully deleted.'
        ];
    }

    public function destroyAvatar($id)
    {
        $decodedId = Hashids::decode($id);

        $candidate = User::where('id', $decodedId)->first();

        $file = File::where('id', $candidate->avatar_id)->first();

        $candidate->avatar_id = null;
        $candidate->save();
        Storage::delete($file->name);
        $file->delete();

        return [
            'success' => true,
            'message' => 'Profile Photo successfully deleted.'
        ];
    }

    public function list($encodedClientId=null, $encodedCandidateId = null)
    {
        $decodedClientId = Hashids::decode($encodedClientId);
        $decodedCandidateId = Hashids::decode($encodedCandidateId);
        $moderatorId = Auth::user()->id;
        $activeClient = null;
        $activeCandidate = null;
        $candidates = null;
        $questions = [];
        $total = 0;
        $avatar = null;

        $clients = Client::orderBy('title', 'ASC')
            ->join('client_moderators', 'clients.id', '=', 'client_moderators.client_id')
            ->where('client_moderators.moderator_id', $moderatorId)
            ->get();

        if($encodedClientId)
        {

            $candidates = DB::table('users')
                ->leftJoin(DB::raw('(SELECT candidate_id, SUM(score) as totalScore FROM moderator_questions WHERE moderator_id = '.$moderatorId.' GROUP BY candidate_id) mq'),
                function($join)
                {
                    $join->on('users.id', '=', 'mq.candidate_id');
                })
                ->join('client_candidates', 'client_candidates.candidate_id' ,'=' ,'users.id')
                ->where('users.user_type_id', 3)
                ->where('client_candidates.client_id', $decodedClientId)
                ->orderBy('totalScore', 'DESC')
                ->get();

            $activeClient = Client::where('id', $decodedClientId)->first();
        }

        if($encodedCandidateId)
        {
            $activeCandidate = DB::table('users')
                ->leftJoin(DB::raw('(SELECT candidate_id, SUM(score) as totalScore FROM moderator_questions WHERE moderator_id = '.$moderatorId.' GROUP BY candidate_id) mq'),
                function($join)
                {
                    $join->on('users.id', '=', 'mq.candidate_id');
                })
                ->where('id', $decodedCandidateId)
                ->first();

            $questions = Question::with('scorings')->with('scoringType')
                ->leftJoin(DB::raw('(SELECT question_id, score FROM moderator_questions WHERE moderator_id = '.$moderatorId.' AND candidate_id = '.$decodedCandidateId[0].') mq'),
                function($join)
                {
                    $join->on('questions.id', '=', 'mq.question_id');
                })
                ->join('client_questions', 'client_questions.question_id', '=', 'questions.id')
                ->where('client_questions.client_id', $decodedClientId)
                ->orderBy('order', 'ASC')
                ->get();

            $avatar = File::where('id', $activeCandidate->avatar_id)->first();
        }

        foreach($questions as $question)
        {
            $total = $total+count($question->scorings);
        }

        $moderator = User::where('users.id', Auth::user()->id)
            ->select(
                'users.name as name',
                'users.surname as surname',
                'files.name as avatar',
                'titles.title as title'
            )
            ->leftJoin('files', 'files.id', '=', 'users.avatar_id')
            ->leftJoin('titles', 'titles.id', '=', 'users.title_id')
            ->first();

        return view('candidates.list', compact(
            'clients',
            'candidates',
            'questions',
            'total',
            'encodedClientId',
            'encodedCandidateId',
            'activeClient',
            'activeCandidate',
            'avatar',
            'moderator'
        ));
    }

    public function submitQuestions(Request $request)
    {
        $decodedCandidateId = Hashids::decode($request->candidate_id)[0];
        $moderatorId = Auth::user()->id;

        foreach($request->request as $key => $question)
        {
            if(strpos($key, 'question') !== false){
                $questionId = explode('-', $key)[1];

                $moderator_question = ModeratorQuestion::where('candidate_id', $decodedCandidateId)
                    ->where('moderator_id', $moderatorId)
                    ->where('question_id', $questionId)
                    ->first();

                if($moderator_question === null){
                    $moderator_question = new ModeratorQuestion;
                    $moderator_question->candidate_id = $decodedCandidateId;
                    $moderator_question->moderator_id = $moderatorId;
                    $moderator_question->question_id = $questionId;
                }

                $moderator_question->score = $question;
                $moderator_question->save();
            }
        }
        return redirect()->back()->with(['success' => 'Candidate successfully scored.']);
    }

}
