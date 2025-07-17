<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Country;

class QuizTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Crea abbastanza paesi per i test (almeno 4)
        Country::factory()->create([
            'name' => 'Italia',
            'code' => 'IT',
            'difficulty_level' => 1,
            'is_active' => true
        ]);
        
        Country::factory()->create([
            'name' => 'Francia',
            'code' => 'FR',
            'difficulty_level' => 1,
            'is_active' => true
        ]);
        
        Country::factory()->create([
            'name' => 'Germania',
            'code' => 'DE',
            'difficulty_level' => 1,
            'is_active' => true
        ]);
        
        Country::factory()->create([
            'name' => 'Regno Unito',
            'code' => 'GB',
            'difficulty_level' => 1,
            'is_active' => true
        ]);
        
        Country::factory()->create([
            'name' => 'Giappone',
            'code' => 'JP',
            'difficulty_level' => 3,
            'is_active' => false
        ]);
    }

    public function test_quiz_index_page_loads(): void
    {
        $response = $this->get('/quiz');
        $response->assertStatus(200);
        $response->assertViewIs('quiz.index');
    }

    public function test_homepage_redirects_to_quiz(): void
    {
        $response = $this->get('/');
        $response->assertRedirect(route('quiz.index'));
    }

    public function test_get_quiz_stats(): void
    {
        $response = $this->get('/api/quiz/stats');
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'total_countries',
            'countries_by_difficulty',
            'countries_by_continent'
        ]);
        
        $data = $response->json();
        $this->assertEquals(4, $data['total_countries']); // Solo i paesi attivi
    }

    public function test_get_quiz_question(): void
    {
        $response = $this->get('/api/quiz/question?difficulty=1');
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'question' => [
                'flag_url',
                'continent',
                'difficulty'
            ],
            'answers' => [
                '*' => [
                    'id',
                    'name',
                    'code'
                ]
            ],
            'correct_answer_id'
        ]);
        
        $data = $response->json();
        $this->assertCount(4, $data['answers']); // Deve avere 4 opzioni
        $this->assertContains($data['correct_answer_id'], collect($data['answers'])->pluck('id')->toArray());
    }

    public function test_check_correct_answer(): void
    {
        $italy = Country::where('code', 'IT')->first();
        $france = Country::where('code', 'FR')->first();
        
        $response = $this->postJson('/api/quiz/check', [
            'answer_id' => $italy->id,
            'correct_answer_id' => $italy->id
        ]);
        
        $response->assertStatus(200);
        $response->assertJson([
            'is_correct' => true,
            'correct_answer' => [
                'name' => 'Italia',
                'code' => 'IT'
            ]
        ]);
    }

    public function test_check_incorrect_answer(): void
    {
        $italy = Country::where('code', 'IT')->first();
        $france = Country::where('code', 'FR')->first();
        
        $response = $this->postJson('/api/quiz/check', [
            'answer_id' => $france->id,
            'correct_answer_id' => $italy->id
        ]);
        
        $response->assertStatus(200);
        $response->assertJson([
            'is_correct' => false,
            'correct_answer' => [
                'name' => 'Italia',
                'code' => 'IT'
            ]
        ]);
    }

    public function test_get_all_countries(): void
    {
        $response = $this->get('/api/countries');
        
        $response->assertStatus(200);
        $countries = $response->json();
        
        $this->assertCount(4, $countries); // Solo i paesi attivi
        $this->assertEquals('Francia', $countries[0]['name']); // Ordinati per nome
        $this->assertEquals('Germania', $countries[1]['name']);
        $this->assertEquals('Italia', $countries[2]['name']);
        $this->assertEquals('Regno Unito', $countries[3]['name']);
    }

    public function test_country_model_scopes(): void
    {
        // Test scope active
        $activeCountries = Country::active()->get();
        $this->assertCount(4, $activeCountries);
        
        // Test scope by difficulty
        $easyCountries = Country::byDifficulty(1)->get();
        $this->assertCount(4, $easyCountries);
        
        // Test get random countries
        $randomCountries = Country::getRandomCountries(2);
        $this->assertCount(2, $randomCountries);
        
        // Test get random country for question
        $randomCountry = Country::getRandomCountryForQuestion(1);
        $this->assertNotNull($randomCountry);
        $this->assertEquals(1, $randomCountry->difficulty_level);
    }
}
