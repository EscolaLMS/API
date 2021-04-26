<?php

use App\Http\Controllers\API\Admin\CourseApiController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'section'], function () {
    Route::get('{curriculumSection}', [CourseApiController::class, 'getSection']);
    Route::post('save', [CourseApiController::class, 'storeSection']);
    Route::post('save/{curriculumSection}', [CourseApiController::class, 'updateSection']);
    Route::post('delete/{curriculumSection}', [CourseApiController::class, 'deleteSection']);
});


Route::get('curriculum/{course}', [CourseApiController::class, 'curriculum']);




Route::group(['prefix' => 'lecture'], function () {
    Route::get('{curriculumLecturesQuiz}', [CourseApiController::class, 'getLecture']);
    Route::post('save/{curriculumSection}', [CourseApiController::class, 'storeLecture']);
    Route::post('save/{curriculumSection}/{curriculumLecturesQuiz}', [CourseApiController::class, 'updateLecture']);
    Route::post('desc/save/{curriculumLecturesQuiz}', [CourseApiController::class, 'postLectureDescSave']);
    Route::post('video/save/{curriculumLecturesQuiz}', [CourseApiController::class, 'postLectureVideoSave']);
    Route::post('audio/save/{curriculumLecturesQuiz}', [CourseApiController::class, 'postLectureAudioSave']);
    Route::post('document/save/{curriculumLecturesQuiz}', [CourseApiController::class, 'postLectureDocumentSave']);
    Route::post('text/save/{curriculumLecturesQuiz}', [CourseApiController::class, 'postLectureTextSave']);
    Route::post('resource/save/{curriculumLecturesQuiz}', [CourseApiController::class, 'postLectureResourceSave']);
    Route::delete('resource/delete/{courseFiles}', [CourseApiController::class, 'deleteResource']);

    Route::post('lib/save/{curriculumLecturesQuiz}/{courseFiles}', [CourseApiController::class, 'postLectureLibrarySave']);
    Route::post('libres/save/{curriculumLecturesQuiz}/{courseFiles}', [CourseApiController::class, 'postLectureLibraryResourceSave']);
    Route::post('exres/save/{curriculumLecturesQuiz}', [CourseApiController::class, 'postLectureExternalResourceSave']);
    Route::post('delete/{curriculumLecturesQuiz}', [CourseApiController::class, 'deleteLecture']);
    Route::post('publish/{curriculumLecturesQuiz}', [CourseApiController::class, 'postLecturePublishSave']);
});

Route::group(['prefix' => 'sort'], function () {
    Route::post('section', [CourseApiController::class, 'sortSection']);
    Route::post('lecturequiz', [CourseApiController::class, 'sortLecture']);
});

Route::post('video/remove/{courseVideos}', [CourseApiController::class, 'removeVideo'])->name('course.video.remove');
