<?php

Auth::routes();
Route::get('/', 'HomeController@index')->name('home');

Route::get('force_login/{user_id}', 'CustomAuthController@replaceAuth')->name('custom.login');

Route::group(['prefix' => '/admin'], function () {

    /*
    |--------------------------------------------------------------------------
      CLIENT
    |--------------------------------------------------------------------------
    */
    Route::get('/client/index', 'ClientController@index')->name('client.index');
    Route::get('/client/create', 'ClientController@create')->name('client.create');
    Route::post('/client/store', 'ClientController@store')->name('client.store');
    Route::get('/client/edit/{id}', 'ClientController@edit')->name('client.edit');
    Route::post('/client/update/{id}', 'ClientController@update')->name('client.update');
    Route::get('/client/delete/{id}', 'ClientController@destroy')->name('client.delete');

    /*
    |--------------------------------------------------------------------------
      MODERATORS
    |--------------------------------------------------------------------------
    */
    Route::get('/moderator/index/{clientId?}', 'ModeratorController@index')->name('moderator.index');
    Route::get('/moderator/create', 'ModeratorController@create')->name('moderator.create');
    Route::post('/moderator/store', 'ModeratorController@store')->name('moderator.store');
    Route::get('/moderator/edit/{id}', 'ModeratorController@edit')->name('moderator.edit');
    Route::post('/moderator/update/{id}', 'ModeratorController@update')->name('moderator.update');
    Route::get('/moderator/delete/{id}', 'ModeratorController@destroy')->name('moderator.delete');
    Route::get('/moderator/avatar/delete/{id}', 'ModeratorController@destroyAvatar')->name('moderator.avatar.delete');

    /*
    |--------------------------------------------------------------------------
      CANDIDATES
    |--------------------------------------------------------------------------
    */
    Route::get('/candidate/index/{clientId?}', 'CandidateController@index')->name('candidate.index');
    Route::get('/candidate/create', 'CandidateController@create')->name('candidate.create');
    Route::post('/candidate/store', 'CandidateController@store')->name('candidate.store');
    Route::get('/candidate/edit/{id}', 'CandidateController@edit')->name('candidate.edit');
    Route::post('/candidate/update/{id}', 'CandidateController@update')->name('candidate.update');
    Route::get('/candidate/delete/{id}', 'CandidateController@destroy')->name('candidate.delete');
    Route::get('/candidate/file/delete/{id}', 'CandidateController@destroyFile')->name('candidate.file.delete');
    Route::get('/candidate/audio/delete/{id}', 'CandidateController@destroyAudio')->name('candidate.audio.delete');
    Route::get('/candidate/avatar/delete/{id}', 'CandidateController@destroyAvatar')->name('candidate.avatar.delete');

    /*
    |--------------------------------------------------------------------------
      SCORING
    |--------------------------------------------------------------------------
    */
    Route::get('/scoring/index/{clientId?}', 'ScoringController@index')->name('scoring.index');
    Route::get('/scoring/create', 'ScoringController@create')->name('scoring.create');
    Route::post('/scoring/store', 'ScoringController@store')->name('scoring.store');
    Route::get('/scoring/edit/{id}', 'ScoringController@edit')->name('scoring.edit');
    Route::post('/scoring/update/{id}', 'ScoringController@update')->name('scoring.update');
    Route::get('/scoring/delete/{id}', 'ScoringController@destroy')->name('scoring.delete');
    Route::get('/scoring/list/{typeId}', 'ScoringController@list')->name('scoring.list');
    Route::get('/scoring/sort/index/{typeId}', 'ScoringController@sort')->name('scoring.sort');
    Route::post('/scoring/sort/update/{typeId}', 'ScoringController@sortUpdate')->name('scoring.sort.update');
    Route::get('/scoring/item/create/{typeId}', 'ScoringController@createItem')->name('scoring.item.create');
    Route::post('/scoring/item/store/{typeId}', 'ScoringController@storeItem')->name('scoring.item.store');
    Route::get('/scoring/item/edit/{typeId}/{scoringId}', 'ScoringController@editItem')->name('scoring.item.edit');
    Route::post('/scoring/item/update/{typeId}/{scoringId}', 'ScoringController@updateItem')->name('scoring.item.update');
    Route::get('/scoring/item/delete/{scoringId}', 'ScoringController@destroyItem')->name('scoring.item.delete');

    /*
    |--------------------------------------------------------------------------
      QUESTIONS
    |--------------------------------------------------------------------------
    */
    Route::get('/question/index/{clientId?}', 'QuestionController@index')->name('question.index');
    Route::get('/question/create', 'QuestionController@create')->name('question.create');
    Route::post('/question/store', 'QuestionController@store')->name('question.store');
    Route::get('/question/edit/{id}', 'QuestionController@edit')->name('question.edit');
    Route::post('/question/update/{id}', 'QuestionController@update')->name('question.update');
    Route::get('/question/delete/{id}', 'QuestionController@destroy')->name('question.delete');
    Route::get('/question/sort/index/{typeId}', 'QuestionController@sort')->name('question.sort');
    Route::post('/question/sort/update/{typeId}', 'QuestionController@sortUpdate')->name('question.sort.update');

    /*
    |--------------------------------------------------------------------------
      RESULTS
    |--------------------------------------------------------------------------
    */
    Route::get('/results/all-candidates/print/{clientId}', 'ResultsController@allCandidatesPrint')->name('results.all.candidates.print');
    Route::get('/results/overview/print/{clientId}', 'ResultsController@overviewPrint')->name('results.overview.print');
    Route::get('/results/candidate/print/{clientId}/{candidateId}', 'ResultsController@candidatePrint')->name('results.candidate.print');
    Route::get('/results/{clientId?}/{candidateId?}', 'ResultsController@index')->name('results.index');
});

/*
|--------------------------------------------------------------------------
  MODERATORS LOGIN
|--------------------------------------------------------------------------
*/
Route::get('/candidate/list/{clientId?}/{candidateId?}', 'CandidateController@list')->name('candidate.list');
Route::post('/candidate/questions/submit/{id?}', 'CandidateController@submitQuestions')->name('candidate.questions.submit');

/*
|--------------------------------------------------------------------------
  VIEW
|--------------------------------------------------------------------------
*/
Route::get('/report/view/{clientId}', 'ViewController@reportView')->name('report.view');
Route::post('/report/pin', 'ViewController@reportPin')->name('report.pin');
Route::get('/report/pin/clear/{id}', 'ViewController@reportPinClear')->name('report.pin.clear');

Route::get('/candidate/view/{id}', 'ViewController@view')->name('candidate.view');
Route::post('/candidate/pin', 'ViewController@pin')->name('candidate.pin');
Route::get('/candidate/pin/clear/{id}', 'ViewController@pinClear')->name('candidate.pin.clear');
