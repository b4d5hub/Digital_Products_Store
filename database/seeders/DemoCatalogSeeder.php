<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DemoCatalogSeeder extends Seeder
{
    /**
     * Seed the demo catalog shown by a fresh installation.
     */
    public function run(): void
    {
        if (Category::query()->exists() || Product::query()->exists()) {
            return;
        }

        $categories = collect([
            [
                'name' => 'Creator Tools',
                'slug' => 'creator-tools',
                'description' => 'Digital resources for planning launches and publishing content.',
            ],
            [
                'name' => 'Productivity',
                'slug' => 'productivity',
                'description' => 'Workspaces and systems for focused, organized work.',
            ],
            [
                'name' => 'Finance & Planning',
                'slug' => 'finance-planning',
                'description' => 'Templates for budgeting, savings, and independent work planning.',
            ],
        ])->mapWithKeys(fn (array $category) => [
            $category['slug'] => Category::updateOrCreate(
                ['slug' => $category['slug']],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'is_visible' => true,
                ],
            ),
        ]);

        $products = [
            [
                'name' => 'Brand Launch Bundle',
                'slug' => 'brand-launch-bundle',
                'sku' => 'DEMO-BRAND-LAUNCH',
                'description' => 'A polished starter bundle for planning a memorable launch across social channels and email.',
                'price' => 29.00,
                'image' => 'form-attachments/social-media-template-kit.png',
                'categories' => ['creator-tools'],
            ],
            [
                'name' => 'Content Calendar Pro',
                'slug' => 'content-calendar-pro',
                'sku' => 'DEMO-CONTENT-CALENDAR',
                'description' => 'A practical content-planning system for consistent publishing, campaign planning, and weekly reviews.',
                'price' => 12.00,
                'image' => 'form-attachments/social-media-template-kit.png',
                'categories' => ['creator-tools'],
            ],
            [
                'name' => 'Social Media Template Kit',
                'slug' => 'social-media-template-kit',
                'sku' => 'DEMO-SOCIAL-TEMPLATES',
                'description' => 'A refined collection of editable social media layouts for launches, campaigns, and everyday content.',
                'price' => 19.00,
                'image' => 'form-attachments/social-media-template-kit.png',
                'categories' => ['creator-tools'],
            ],
            [
                'name' => 'Freelancer Client Hub',
                'slug' => 'freelancer-client-hub',
                'sku' => 'DEMO-CLIENT-HUB',
                'description' => 'A client-ready workspace for onboarding, projects, deliverables, and dependable follow-ups.',
                'price' => 22.00,
                'image' => 'form-attachments/notion-workspace-planner.png',
                'categories' => ['productivity'],
            ],
            [
                'name' => 'Notion Workspace Planner',
                'slug' => 'notion-workspace-planner',
                'sku' => 'DEMO-NOTION-PLANNER',
                'description' => 'An organized digital workspace for projects, weekly planning, notes, and focused routines.',
                'price' => 24.00,
                'image' => 'form-attachments/notion-workspace-planner.png',
                'categories' => ['productivity'],
            ],
            [
                'name' => 'Second Brain Starter',
                'slug' => 'second-brain-starter',
                'sku' => 'DEMO-SECOND-BRAIN',
                'description' => 'A clear, flexible system for capturing ideas, organizing notes, and turning information into action.',
                'price' => 18.00,
                'image' => 'form-attachments/notion-workspace-planner.png',
                'categories' => ['productivity'],
            ],
            [
                'name' => 'Freelance Income Planner',
                'slug' => 'freelance-income-planner',
                'sku' => 'DEMO-INCOME-PLANNER',
                'description' => 'A practical income and expense planner built for independent work, taxes, and quarterly targets.',
                'price' => 17.00,
                'image' => 'form-attachments/budget-dashboard.png',
                'categories' => ['finance-planning'],
            ],
            [
                'name' => 'Personal Budget Dashboard',
                'slug' => 'personal-budget-dashboard',
                'sku' => 'DEMO-BUDGET-DASHBOARD',
                'description' => 'A clean budgeting dashboard to plan spending, track savings goals, and review monthly progress.',
                'price' => 15.00,
                'image' => 'form-attachments/budget-dashboard.png',
                'categories' => ['finance-planning'],
            ],
            [
                'name' => 'Savings Goal Tracker',
                'slug' => 'savings-goal-tracker',
                'sku' => 'DEMO-SAVINGS-TRACKER',
                'description' => 'A friendly planning tool for setting savings targets, tracking milestones, and staying motivated.',
                'price' => 9.00,
                'image' => 'form-attachments/budget-dashboard.png',
                'categories' => ['finance-planning'],
            ],
        ];

        foreach ($products as $productData) {
            $categorySlugs = $productData['categories'];
            unset($productData['categories']);

            $product = Product::updateOrCreate(
                ['slug' => $productData['slug']],
                $productData + [
                    'digital_item' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
                    'quantity' => 100,
                    'is_visible' => true,
                    'is_featured' => false,
                    'type' => 'Downloadable',
                    'published_at' => now()->toDateString(),
                ],
            );

            $product->categories()->syncWithoutDetaching(
                collect($categorySlugs)->map(fn (string $slug) => $categories[$slug]->id)->all(),
            );
        }
    }
}
