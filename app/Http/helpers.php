<?php

function scorings($moderatorId, $candidateId)
{
    return \DB::table('moderator_questions')
        ->select('scoring_types.title', 'moderator_questions.score')
        ->join('questions', 'questions.id', '=', 'moderator_questions.question_id')
        ->join('scoring_types', 'scoring_types.id', '=', 'questions.scoring_type_id')
        ->where('moderator_id', $moderatorId)
        ->where('candidate_id', $candidateId)
        ->orderBy('questions.order', 'ASC')
        ->get();
}
