<?php

use App\Models\Program;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('program has many students', function () {
    $program = Program::factory()->create();
    $student = Student::factory()->create(['program_id' => $program->id]);

    expect($program->students)->toHaveCount(1);
    expect($program->students->first()->id)->toBe($student->id);
    expect($program->students()->exists())->toBeTrue();
});
