<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Carbon\Carbon;
use Session;
use Hashids;
use Hash;
use DB;

use App\CandidateFile;
use App\CandidateAudio;
use App\Question;
use App\Client;
use App\User;
use App\File;

class ViewController extends Controller
{

    public function reportView($encodedClientId=null)
    {
        $clientName = 'No Access';
        $client = null;
        $candidates = null;
        $moderators = null;
        $total = null;

        $clientId = Hashids::decode($encodedClientId);

        if(Session::has('report_access') && Session::get('report_access') == $encodedClientId){

            $client = Client::where('id', $clientId)->first();
            $clientName = $client->title;

            $questions = Question::orderBy('order', 'ASC')
                ->join('client_questions', 'client_questions.question_id' ,'=' ,'questions.id')
                ->where('client_questions.client_id', $clientId)
                ->get();

            $candidates = User::with('avatar')
                ->select(
                    'id',
                    'name',
                    'surname',
                    'totalScore',
                    'avatar_id',
                    DB::raw("CONCAT(users.name, ' ', users.surname) AS score")
                )
                ->leftJoin(DB::raw('(SELECT candidate_id, SUM(score) as totalScore FROM moderator_questions GROUP BY candidate_id) mq'),
                function($join)
                {
                    $join->on('users.id', '=', 'mq.candidate_id');
                })
                ->join('client_candidates', 'client_candidates.candidate_id' ,'=' ,'users.id')
                ->where('users.user_type_id', 3)
                ->where('client_candidates.client_id', $clientId)
                ->orderBy('totalScore', 'DESC')
                ->get();

            foreach($candidates as $candidate)
            {
                $candidate->questions = DB::table('moderator_questions')
                    ->select(
                        'title',
                        'text',
                        DB::raw('SUM(score) AS score')
                    )
                    ->join('questions', 'questions.id', '=', 'moderator_questions.question_id')
                    ->join('scoring_types', 'scoring_types.id', '=', 'questions.scoring_type_id')
                    ->where('candidate_id', $candidate->id)
                    ->groupBy('question_id')
                    ->orderBy('order', 'ASC')
                    ->get();
            }

            $moderators = User::where('user_type_id', 2)
                ->select(
                    'users.*',
                    'files.name as avatar'
                )
                ->join('client_moderators', 'client_moderators.moderator_id' ,'=' ,'users.id')
                ->leftJoin('files', 'files.id', '=', 'users.avatar_id')
                ->where('client_moderators.client_id', $clientId)
                ->get();

            $total = 0;
            foreach($questions as $question)
            {
                $total = $total+count($question->scorings);
            }

            $total = (count($moderators)*$total);
        }

        return view('clients.view', [
            'date' => Carbon::now()->format('d M Y'),
            'client' => $client,
            'clientName' => $clientName,
            'candidates' => $candidates,
            'moderators' => $moderators,
            'total' => $total,
            'encodedClientId' => $encodedClientId
        ]);
    }

    public function reportPin(Request $request)
    {
        $clientId = Hashids::decode($request->client_id);
        $client = Client::where('id', $clientId)->first();

        if($request->pin != '' && Hash::check($request->pin, $client->report_url_pin)) {
            Session::put('report_access', $request->client_id);
        } else {
            return redirect()->back()->with(['error' => 'Wrong Pin.']);
        }
        return redirect()->back();
    }

    public function reportPinClear($encodedClientId)
    {
        Session::forget('report_access');
        return redirect()->route('report.view', $encodedClientId);
    }

    public function view($encodedCandidateId=null)
    {
        $candidateName = 'No Access';
        $candidate = null;
        $candidateFiles = null;
        $candidateAudios = null;
        $avatar = null;

        if(Session::has('access') && Session::get('access') == $encodedCandidateId){

            $decodedCandidateId = Hashids::decode($encodedCandidateId);
            $candidate = User::where('id', $decodedCandidateId)->first();

            $candidateFiles = CandidateFile::join('files', 'candidate_files.file_id', '=', 'files.id')
                ->where('candidate_files.candidate_id', $candidate->id)
                ->get();

            $candidateAudios = CandidateAudio::join('files', 'candidate_audios.file_id', '=', 'files.id')
                ->where('candidate_audios.candidate_id', $candidate->id)
                ->get();

            $avatar = File::where('id', $candidate->avatar_id)->first();
            $candidateName = $candidate->name.' '.$candidate->surname;
        }

        return view('candidates.view', [
            'candidate' => $candidate,
            'candidateName' => $candidateName,
            'candidateFiles' => $candidateFiles,
            'candidateAudios' => $candidateAudios,
            'avatar' => $avatar,
            'encodedCandidateId' => $encodedCandidateId
        ]);
    }

    public function pin(Request $request)
    {

        $decodedCandidateId = Hashids::decode($request->candidate_id);
        $candidate = User::where('id', $decodedCandidateId)->first();

        if(Hash::check($request->pin, $candidate->pin)) {
            // Right pin
            Session::put('access', $request->candidate_id);
        } else {
            // Wrong one
            return redirect()->back()->with(['error' => 'Wrong Pin.']);
        }
        return redirect()->back();
    }

    public function pinClear($encodedCandidateId)
    {
        Session::forget('access');
        return redirect()->route('candidate.view', $encodedCandidateId);
    }
}
