<?php

declare(strict_types=1);

namespace App\MoonShine\Controllers;

use App\Models\Course;
use App\MoonShine\Controllers\BaseControllers\BaseCourseController;
use App\MoonShine\Pages\Courses\CourseIndexPage;
use App\MoonShine\Pages\Courses\CourseDetailPage;
use MoonShine\Pages\Page;

final class CourseController extends BaseCourseController
{
    public function index(): Page
    {
        $courses = Course::select('title', 'subtitle', 'slug', 'image')->get();
        return CourseIndexPage::make($courses);
    }

    public function detail(string $slug): Page
    {
        $course = Course::select('id', 'title', 'subtitle', 'image', 'slug')
            ->with(['chapters' => function ($query) {
                $query->select('id', 'course_id', 'title', 'subtitle')
                    ->whereNull('parent_id');
            }])
            ->where('slug', $slug)
            ->firstOrFail();
        return CourseDetailPage::make($course);
    }
}
