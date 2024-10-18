<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Sample data for seeding
        $courses = [
            [
                'title' => 'Introduction to Laravel',
                'description' => 'A comprehensive introduction to Laravel framework.',
                'short_description' => [
                    "Understand the MVC architecture",
                    "Learn about routes and controllers",
                    "Master views and Blade templates",
                    "Explore Eloquent ORM"
                ],
                'price' => 99.99,
                'discounted_price' => 49.99,
                'thumbnail' => 'https://example.com/thumbnail/laravel.png', // Replace with a real image URL
                'user_id' => 1,
                'course_categories_id' => 1,
                'duration' => 52,
            ],
            [
                'title' => 'Mastering PHP',
                'description' => 'A comprehensive guide to mastering PHP framework.',
                'short_description' => [
                    "Deep dive into PHP syntax",
                    "Explore OOP concepts in PHP",
                    "Learn about PHP frameworks",
                    "Build web applications with PHP"
                ],
                'price' => 129.99,
                'discounted_price' => 79.99,
                'thumbnail' => 'https://example.com/thumbnail/php.png', // Replace with a real image URL
                'user_id' => 1,
                'course_categories_id' => 1,
                'duration' => 72,
            ],
            [
                'title' => 'JavaScript Essentials',
                'description' => 'Learn the fundamentals of JavaScript.',
                'short_description' => [
                    "Understand variables and data types",
                    "Learn about functions and scope",
                    "Explore DOM manipulation",
                    "Get familiar with ES6 features"
                ],
                'price' => 89.99,
                'discounted_price' => 49.99,
                'thumbnail' => 'https://via.placeholder.com/150/0000FF/FFFFFF?text=JavaScript', // Replace with a real image URL
                'user_id' => 1,
                'course_categories_id' => 1,
                'duration' => 45,
            ],
            [
                'title' => 'Data Science with Python',
                'description' => 'A comprehensive guide to data analysis with Python.',
                'short_description' => [
                    "Use Python for data analysis",
                    "Implement machine learning algorithms",
                    "Learn to use Pandas for data manipulation",
                    "Visualize data with Matplotlib"
                ],
                'price' => 119.99,
                'discounted_price' => 69.99,
                'thumbnail' => 'https://via.placeholder.com/150/FF0000/FFFFFF?text=Python', // Replace with a real image URL
                'user_id' => 1,
                'course_categories_id' => 1,
                'duration' => 60,
            ],
            [
                'title' => 'React for Beginners',
                'description' => 'An introductory course to React.js.',
                'short_description' => [
                    "Understand components and props",
                    "Learn about state and lifecycle",
                    "Explore hooks and context API",
                    "Build a simple application"
                ],
                'price' => 109.99,
                'discounted_price' => 59.99,
                'thumbnail' => 'https://via.placeholder.com/150/FFFF00/000000?text=React', // Replace with a real image URL
                'user_id' => 1,
                'course_categories_id' => 2,
                'duration' => 55,
            ],
            [
                'title' => 'Understanding APIs',
                'description' => 'Learn how to build and consume APIs.',
                'short_description' => [
                    "Explore RESTful APIs",
                    "Understand JSON format",
                    "Learn about API authentication",
                    "Build your own API with Laravel"
                ],
                'price' => 89.99,
                'discounted_price' => 49.99,
                'thumbnail' => 'https://via.placeholder.com/150/00FF00/000000?text=API', // Replace with a real image URL
                'user_id' => 1,
                'course_categories_id' => 2,
                'duration' => 50,
            ],
            [
                'title' => 'Django for Web Development',
                'description' => 'A complete guide to building web applications with Django.',
                'short_description' => [
                    "Understand the Django architecture",
                    "Learn about models and migrations",
                    "Explore views and templates",
                    "Build a full-stack application"
                ],
                'price' => 99.99,
                'discounted_price' => 59.99,
                'thumbnail' => 'https://via.placeholder.com/150/FF00FF/FFFFFF?text=Django', // Replace with a real image URL
                'user_id' => 1,
                'course_categories_id' => 3,
                'duration' => 70,
            ],
            [
                'title' => 'Advanced CSS Techniques',
                'description' => 'Take your CSS skills to the next level.',
                'short_description' => [
                    "Understand CSS Grid and Flexbox",
                    "Learn about animations and transitions",
                    "Explore responsive design principles",
                    "Build complex layouts"
                ],
                'price' => 79.99,
                'discounted_price' => 39.99,
                'thumbnail' => 'https://via.placeholder.com/150/0000FF/FFFFFF?text=CSS', // Replace with a real image URL
                'user_id' => 1,
                'course_categories_id' => 4,
                'duration' => 40,
            ],
            [
                'title' => 'Introduction to SQL',
                'description' => 'Learn the basics of SQL for data manipulation.',
                'short_description' => [
                    "Understand databases and tables",
                    "Learn about SELECT statements",
                    "Explore joins and subqueries",
                    "Use SQL for data analysis"
                ],
                'price' => 69.99,
                'discounted_price' => 29.99,
                'thumbnail' => 'https://via.placeholder.com/150/FFFF00/000000?text=SQL', // Replace with a real image URL
                'user_id' => 1,
                'course_categories_id' => 5,
                'duration' => 45,
            ],
            [
                'title' => 'Mobile App Development with Flutter',
                'description' => 'A complete guide to building mobile applications with Flutter.',
                'short_description' => [
                    "Understand the Flutter architecture",
                    "Learn about widgets and state management",
                    "Explore navigation and routing",
                    "Build a complete mobile application"
                ],
                'price' => 109.99,
                'discounted_price' => 69.99,
                'thumbnail' => 'https://via.placeholder.com/150/00FFFF/000000?text=Flutter', // Replace with a real image URL
                'user_id' => 1,
                'course_categories_id' => 6,
                'duration' => 80,
            ],
            [
                'title' => 'Introduction to Graphic Design',
                'description' => 'Learn the principles of graphic design.',
                'short_description' => [
                    "Understand design principles",
                    "Learn about typography and color theory",
                    "Explore design tools and software",
                    "Build your own design projects"
                ],
                'price' => 89.99,
                'discounted_price' => 49.99,
                'thumbnail' => 'https://via.placeholder.com/150/FF8800/FFFFFF?text=Design', // Replace with a real image URL
                'user_id' => 1,
                'course_categories_id' => 7,
                'duration' => 50,
            ],
            [
                'title' => 'Cybersecurity Fundamentals',
                'description' => 'Understand the basics of cybersecurity.',
                'short_description' => [
                    "Learn about network security",
                    "Understand vulnerabilities and threats",
                    "Explore best practices for security",
                    "Build a secure system"
                ],
                'price' => 99.99,
                'discounted_price' => 59.99,
                'thumbnail' => 'https://via.placeholder.com/150/FF4444/FFFFFF?text=Security', // Replace with a real image URL
                'user_id' => 1,
                'course_categories_id' => 8,
                'duration' => 75,
            ],
            [
                'title' => 'Blockchain Technology',
                'description' => 'Explore the world of blockchain and cryptocurrencies.',
                'short_description' => [
                    "Understand how blockchain works",
                    "Learn about cryptocurrencies",
                    "Explore smart contracts",
                    "Build a simple blockchain application"
                ],
                'price' => 149.99,
                'discounted_price' => 99.99,
                'thumbnail' => 'https://via.placeholder.com/150/000000/FFFFFF?text=Blockchain', // Replace with a real image URL
                'user_id' => 1,
                'course_categories_id' => 2,
                'duration' => 65,
            ],
            [
                'title' => 'AI and Machine Learning Basics',
                'description' => 'Learn the basics of AI and machine learning.',
                'short_description' => [
                    "Understand AI concepts",
                    "Explore machine learning algorithms",
                    "Learn about data preprocessing",
                    "Build your first machine learning model"
                ],
                'price' => 129.99,
                'discounted_price' => 79.99,
                'thumbnail' => 'https://via.placeholder.com/150/00FF44/FFFFFF?text=AI', // Replace with a real image URL
                'user_id' => 1,
                'course_categories_id' => 3,
                'duration' => 70,
            ],
            [
                'title' => 'Introduction to Cloud Computing',
                'description' => 'A comprehensive guide to cloud computing.',
                'short_description' => [
                    "Understand cloud services",
                    "Learn about deployment models",
                    "Explore security in cloud computing",
                    "Build a cloud-based application"
                ],
                'price' => 99.99,
                'discounted_price' => 49.99,
                'thumbnail' => 'https://via.placeholder.com/150/FF0077/FFFFFF?text=Cloud', // Replace with a real image URL
                'user_id' => 1,
                'course_categories_id' => 9,
                'duration' => 60,
            ],
        ];

        // Loop through each course and create it
        foreach ($courses as $course) {
            Course::create([
                'title' => $course['title'],
                'slug' => $this->generateUniqueSlug($course['title']),
                'description' => $course['description'],
                'short_description' => json_encode($course['short_description']),
                'price' => $course['price'],
                'discounted_price' => $course['discounted_price'],
                'thumbnail' => $course['thumbnail'],
                'user_id' => $course['user_id'],
                'course_categories_id' => $course['course_categories_id'],
                'duration' => $course['duration'],
            ]);
        }
    }

    /**
     * Generate a unique slug for the course.
     *
     * @param string $title
     * @return string
     */
    private function generateUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        // Check if the slug already exists, and append a number if it does
        while (Course::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }
}
