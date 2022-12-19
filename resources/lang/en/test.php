<?php
orderBy('student.student_id', 'asc')
    ->select('student.id', DB::raw("CONCAT(student.student_id, ' - ', rank.short_name, ' ', student.full_name) as name"))
    ->pluck('name', 'id')->toArray();