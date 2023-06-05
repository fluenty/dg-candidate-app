<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Hashids;
use Auth;
use DB;

use App\Question;
use App\Client;
use App\User;
use App\File;

class ResultsController extends Controller
{

    public function index($encodedClientId=null, $encodedCandidateId = null)
    {
        $decodedClientId = Hashids::decode($encodedClientId);
        $decodedCandidateId = Hashids::decode($encodedCandidateId);
        $activeClient = null;
        $activeCandidate = null;
        $candidates = null;
        $questions = [];
        $moderators = null;
        $avatar = null;
        $total = 0;

        $clients = Client::orderBy('title', 'ASC')->get();

        if($encodedClientId)
        {
            $questions = Question::orderBy('order', 'ASC')
                ->join('client_questions', 'client_questions.question_id' ,'=' ,'questions.id')
                ->where('client_questions.client_id', $decodedClientId)
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
                ->where('client_candidates.client_id', $decodedClientId)
                ->orderBy('totalScore', 'DESC')
                ->get();

            $activeClient = Client::where('id', $decodedClientId)->first();

            $moderators = User::where('user_type_id', 2)
                ->select(
                    'users.*',
                    'files.name as avatar'
                )
                ->join('client_moderators', 'client_moderators.moderator_id' ,'=' ,'users.id')
                ->leftJoin('files', 'files.id', '=', 'users.avatar_id')
                ->where('client_moderators.client_id', $decodedClientId)
                ->get();

            $total = 0;
            foreach($questions as $question)
            {
                $total = $total+count($question->scorings);
            }

            $total = (count($moderators)*$total);
        }

        if($encodedCandidateId){

            $questions = Question::with('scorings')->with('scoringType')
                ->select(
                    'questions.*',
                    DB::raw('SUM(score) AS score')
                )
                ->leftJoin('moderator_questions', 'questions.id', '=', 'moderator_questions.question_id')
                ->where('candidate_id', $decodedCandidateId)
                ->groupBy('questions.id')
                ->orderBy('order', 'ASC')
                ->get();

            $activeCandidate = DB::table('users')
                ->leftJoin(DB::raw('(SELECT candidate_id, SUM(score) as totalScore FROM moderator_questions GROUP BY candidate_id) mq'),
                function($join)
                {
                    $join->on('users.id', '=', 'mq.candidate_id');
                })
                ->where('id', $decodedCandidateId)
                ->first();

            $avatar = File::where('id', $activeCandidate->avatar_id)->first();

            $moderators = DB::table('users')
                ->select(
                    'users.*',
                    DB::raw('SUM(score) AS score'),
                    'files.name as avatar'
                )
                ->leftJoin('moderator_questions', 'users.id', '=', 'moderator_questions.moderator_id')
                ->leftJoin('files', 'files.id', '=', 'users.avatar_id')
                ->join('client_moderators', 'client_moderators.moderator_id' ,'=' ,'users.id')
                ->where('candidate_id', $decodedCandidateId)
                ->where('client_moderators.client_id', $decodedClientId)
                ->groupBy('users.id')
                ->orderBy('score', 'DESC')
                ->get();

            $total = 0;
            foreach($questions as $question)
            {
                $total = $total+count($question->scorings);
            }

            $total = (count($moderators)*$total);

        }

        return view('results.index', compact(
            'clients',
            'candidates',
            'moderators',
            'questions',
            'total',
            'encodedClientId',
            'encodedCandidateId',
            'activeClient',
            'activeCandidate',
            'avatar'
        ));
    }

    public function overviewPrint($encodedClientId=null)
    {
        $decodedClientId = Hashids::decode($encodedClientId);

        $questions = Question::orderBy('order', 'ASC')
            ->join('client_questions', 'client_questions.question_id' ,'=' ,'questions.id')
            ->where('client_questions.client_id', $decodedClientId)
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
            ->where('client_candidates.client_id', $decodedClientId)
            ->orderBy('totalScore', 'DESC')
            ->get();

        $activeClient = Client::where('id', $decodedClientId)->first();

        $moderators = User::where('user_type_id', 2)
            ->select(
                'users.*',
                'files.name as avatar'
            )
            ->join('client_moderators', 'client_moderators.moderator_id' ,'=' ,'users.id')
            ->leftJoin('files', 'files.id', '=', 'users.avatar_id')
            ->where('client_moderators.client_id', $decodedClientId)
            ->get();

        $total = 0;
        foreach($questions as $question)
        {
            $total = $total+count($question->scorings);
        }
        $total = (count($moderators)*$total);

        return view('results.print.overview', [
            'candidates' => $candidates,
            'moderators' => $moderators,
            'total' => $total
        ]);
    }

    public function candidatePrint($encodedClientId=null, $encodedCandidateId = null)
    {
        $decodedClientId = Hashids::decode($encodedClientId);
        $decodedCandidateId = Hashids::decode($encodedCandidateId);

        $questions = Question::with('scorings')->with('scoringType')
            ->select(
                'questions.*',
                DB::raw('SUM(score) AS score')
            )
            ->leftJoin('moderator_questions', 'questions.id', '=', 'moderator_questions.question_id')
            ->where('candidate_id', $decodedCandidateId)
            ->groupBy('questions.id')
            ->orderBy('order', 'ASC')
            ->get();

        $activeCandidate = DB::table('users')
            ->leftJoin(DB::raw('(SELECT candidate_id, SUM(score) as totalScore FROM moderator_questions GROUP BY candidate_id) mq'),
            function($join)
            {
                $join->on('users.id', '=', 'mq.candidate_id');
            })
            ->where('id', $decodedCandidateId)
            ->first();

        $avatar = File::where('id', $activeCandidate->avatar_id)->first();

        $moderators = DB::table('users')
            ->select(
                'users.*',
                DB::raw('SUM(score) AS score'),
                'files.name as avatar'
            )
            ->leftJoin('moderator_questions', 'users.id', '=', 'moderator_questions.moderator_id')
            ->leftJoin('files', 'files.id', '=', 'users.avatar_id')
            ->join('client_moderators', 'client_moderators.moderator_id' ,'=' ,'users.id')
            ->where('candidate_id', $decodedCandidateId)
            ->where('client_moderators.client_id', $decodedClientId)
            ->groupBy('users.id')
            ->orderBy('score', 'DESC')
            ->get();

        $total = 0;
        foreach($questions as $question)
        {
            $total = $total+count($question->scorings);
        }

        $total = (count($moderators)*$total);

        return view('results.print.candidate', compact(
            'moderators',
            'questions',
            'activeCandidate',
            'total',
            'avatar'
        ));
    }

    public function allCandidatesPrint($encodedClientId)
    {
        $decodedClientId = Hashids::decode($encodedClientId);

        $questions = Question::orderBy('order', 'ASC')
            ->join('client_questions', 'client_questions.question_id' ,'=' ,'questions.id')
            ->where('client_questions.client_id', $decodedClientId)
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
            ->where('client_candidates.client_id', $decodedClientId)
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

        $activeClient = Client::where('id', $decodedClientId)->first();

        $moderators = User::where('user_type_id', 2)
            ->select(
                'users.*',
                'files.name as avatar'
            )
            ->join('client_moderators', 'client_moderators.moderator_id' ,'=' ,'users.id')
            ->leftJoin('files', 'files.id', '=', 'users.avatar_id')
            ->where('client_moderators.client_id', $decodedClientId)
            ->get();

        $total = 0;
        foreach($questions as $question)
        {
            $total = $total+count($question->scorings);
        }

        $total = (count($moderators)*$total);

        return view('results.print.all-candidates', [
            'candidates' => $candidates,
            'moderators' => $moderators,
            'total' => $total,
            'activeClient' => $activeClient
        ]);
    }
}
