<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;

class MatchingService
{
    /**
     * Calculate match percentage between two users
     *
     * @param User $currentUser
     * @param User $potentialMatch
     * @return int
     */
    public function calculateMatchPercentage(User $currentUser, User $potentialMatch): int
    {
        $score = 0;
        $maxScore = 0;
        
        // Age compatibility (20 points)
        $ageScore = $this->calculateAgeCompatibility($currentUser, $potentialMatch);
        $score += $ageScore;
        $maxScore += 20;
        
        // Location compatibility (15 points)
        $locationScore = $this->calculateLocationCompatibility($currentUser, $potentialMatch);
        $score += $locationScore;
        $maxScore += 15;
        
        // Quiz results compatibility (40 points)
        $quizScore = $this->calculateQuizCompatibility($currentUser, $potentialMatch);
        $score += $quizScore;
        $maxScore += 40;
        
        // Relationship type compatibility (15 points)
        $relationshipScore = $this->calculateRelationshipTypeCompatibility($currentUser, $potentialMatch);
        $score += $relationshipScore;
        $maxScore += 15;
        
        // Interest compatibility (10 points)
        $interestScore = $this->calculateInterestCompatibility($currentUser, $potentialMatch);
        $score += $interestScore;
        $maxScore += 10;
        
        // Calculate percentage
        $percentage = $maxScore > 0 ? round(($score / $maxScore) * 100) : 0;
        
        // Ensure minimum 60% and maximum 100%
        return max(60, min(100, $percentage));
    }
    
    /**
     * Calculate age compatibility score
     */
    private function calculateAgeCompatibility(User $currentUser, User $potentialMatch): int
    {
        if (!$currentUser->age || !$potentialMatch->age) {
            return 10; // Default score if age is missing
        }
        
        // Check if potential match is within current user's age preferences
        $withinAgeRange = true;
        if ($currentUser->age_min && $potentialMatch->age < $currentUser->age_min) {
            $withinAgeRange = false;
        }
        if ($currentUser->age_max && $potentialMatch->age > $currentUser->age_max) {
            $withinAgeRange = false;
        }
        
        if (!$withinAgeRange) {
            return 5; // Low score for outside age range
        }
        
        // Calculate based on age difference
        $ageDiff = abs($currentUser->age - $potentialMatch->age);
        
        if ($ageDiff <= 2) return 20;
        if ($ageDiff <= 5) return 18;
        if ($ageDiff <= 8) return 15;
        if ($ageDiff <= 12) return 12;
        return 8;
    }
    
    /**
     * Calculate location compatibility score
     */
    private function calculateLocationCompatibility(User $currentUser, User $potentialMatch): int
    {
        // If no location data, give moderate score
        if (!$currentUser->latitude || !$currentUser->longitude || 
            !$potentialMatch->latitude || !$potentialMatch->longitude) {
            return 8;
        }
        
        $distance = $this->calculateDistance(
            $currentUser->latitude, $currentUser->longitude,
            $potentialMatch->latitude, $potentialMatch->longitude
        );
        
        $radius = $currentUser->discovery_radius ?? 50; // Default 50 miles
        
        if ($distance <= $radius * 0.3) return 15; // Very close
        if ($distance <= $radius * 0.6) return 12; // Close
        if ($distance <= $radius) return 8; // Within range
        return 3; // Outside preferred range
    }
    
    /**
     * Calculate quiz results compatibility score
     */
    private function calculateQuizCompatibility(User $currentUser, User $potentialMatch): int
    {
        $currentQuiz = $currentUser->quiz_results ? json_decode($currentUser->quiz_results, true) : [];
        $matchQuiz = $potentialMatch->quiz_results ? json_decode($potentialMatch->quiz_results, true) : [];
        
        if (empty($currentQuiz) || empty($matchQuiz)) {
            return 20; // Default score if quiz data is missing
        }
        
        $score = 0;
        
        // Del Match Code compatibility (20 points)
        if (isset($currentQuiz['del_match_code']) && isset($matchQuiz['del_match_code'])) {
            $score += $this->calculateDelMatchCodeCompatibility(
                $currentQuiz['del_match_code'], 
                $matchQuiz['del_match_code']
            );
        } else {
            $score += 10; // Default if missing
        }
        
        // Additional quiz answers compatibility (20 points)
        if (isset($currentQuiz['all_answers']) && isset($matchQuiz['all_answers'])) {
            $score += $this->calculateAnswerCompatibility(
                $currentQuiz['all_answers'],
                $matchQuiz['all_answers']
            );
        } else {
            $score += 10; // Default if missing
        }
        
        return min(40, $score);
    }
    
    /**
     * Calculate Del Match Code compatibility
     */
    private function calculateDelMatchCodeCompatibility(string $code1, string $code2): int
    {
        if ($code1 === $code2) {
            return 15; // Same code - very compatible
        }
        
        // Calculate similarity based on matching characters
        $matches = 0;
        $length = min(strlen($code1), strlen($code2));
        
        for ($i = 0; $i < $length; $i++) {
            if (isset($code1[$i]) && isset($code2[$i]) && $code1[$i] === $code2[$i]) {
                $matches++;
            }
        }
        
        $similarity = $length > 0 ? ($matches / $length) : 0;
        
        if ($similarity >= 0.8) return 12;
        if ($similarity >= 0.6) return 10;
        if ($similarity >= 0.4) return 8;
        if ($similarity >= 0.2) return 6;
        return 4;
    }
    
    /**
     * Calculate answer compatibility based on quiz responses
     */
    private function calculateAnswerCompatibility(array $answers1, array $answers2): int
    {
        $commonQuestions = array_intersect_key($answers1, $answers2);
        
        if (empty($commonQuestions)) {
            return 10; // Default score
        }
        
        $matches = 0;
        $total = count($commonQuestions);
        
        foreach ($commonQuestions as $questionId => $answer1) {
            $answer2 = $answers2[$questionId];
            
            // Exact match
            if ($answer1 === $answer2) {
                $matches += 1;
            }
            // Partial match for similar answers
            elseif (is_string($answer1) && is_string($answer2)) {
                $similarity = similar_text(strtolower($answer1), strtolower($answer2)) / 100;
                $matches += $similarity;
            }
        }
        
        $compatibility = $total > 0 ? ($matches / $total) : 0;
        return round($compatibility * 20);
    }
    
    /**
     * Calculate relationship type compatibility
     */
    private function calculateRelationshipTypeCompatibility(User $currentUser, User $potentialMatch): int
    {
        if (!$currentUser->relationship_type || !$potentialMatch->relationship_type) {
            return 8; // Default score
        }
        
        if ($currentUser->relationship_type === $potentialMatch->relationship_type) {
            return 15; // Perfect match
        }
        
        // Compatible relationship types (based on enum: serious, casual, friendship, open)
        $compatibleTypes = [
            'serious' => ['serious'],
            'casual' => ['casual', 'open'],
            'friendship' => ['friendship', 'casual'],
            'open' => ['open', 'casual'],
        ];
        
        $userType = strtolower($currentUser->relationship_type);
        $matchType = strtolower($potentialMatch->relationship_type);
        
        if (isset($compatibleTypes[$userType]) && 
            in_array($matchType, $compatibleTypes[$userType])) {
            return 10;
        }
        
        return 5; // Low compatibility
    }
    
    /**
     * Calculate interest compatibility
     */
    private function calculateInterestCompatibility(User $currentUser, User $potentialMatch): int
    {
        $userInterests = $currentUser->interests ? json_decode($currentUser->interests, true) : [];
        $matchInterests = $potentialMatch->interests ? json_decode($potentialMatch->interests, true) : [];
        
        if (empty($userInterests) || empty($matchInterests)) {
            return 5; // Default score
        }
        
        $commonInterests = array_intersect($userInterests, $matchInterests);
        $totalInterests = array_unique(array_merge($userInterests, $matchInterests));
        
        if (empty($totalInterests)) {
            return 5;
        }
        
        $similarity = count($commonInterests) / count($totalInterests);
        
        if ($similarity >= 0.5) return 10;
        if ($similarity >= 0.3) return 8;
        if ($similarity >= 0.2) return 6;
        return 4;
    }
    
    /**
     * Calculate distance between two coordinates in miles
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $earthRadius = 3959; // miles
        
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        return $earthRadius * $c;
    }
    
    /**
     * Get potential matches for a user
     *
     * @param User $user
     * @param int $limit
     * @return Collection
     */
    public function getPotentialMatches(User $user, int $limit = 10): Collection
    {
        $query = User::where('id', '!=', $user->id)
            ->where('active', true)
            ->whereNotNull('quiz_results');
        
        // Filter by interested_in preference
        // interested_in stores: 'men', 'women', 'both'
        // gender (legacy) stores: 'male', 'female', 'other'
        // gender_identity (new) stores: 'men', 'women', 'nonbinary', 'prefer_not_to_say'
        if ($user->interested_in && $user->interested_in !== 'both') {
            // Map interested_in value to the corresponding gender column value
            $genderMap = [
                'men' => 'male',
                'women' => 'female',
            ];
            $legacyGender = $genderMap[$user->interested_in] ?? null;
            $genderIdentity = $user->interested_in; // 'men' or 'women' matches gender_identity directly
            
            $query->where(function ($q) use ($legacyGender, $genderIdentity) {
                // Match against legacy 'gender' field (male/female/other)
                if ($legacyGender) {
                    $q->where('gender', $legacyGender);
                }
                // Also match against new 'gender_identity' field (men/women/nonbinary)
                $q->orWhere('gender_identity', $genderIdentity);
            });
        }
        
        // Filter by age preferences
        if ($user->age_min) {
            $query->where('age', '>=', $user->age_min);
        }
        if ($user->age_max) {
            $query->where('age', '<=', $user->age_max);
        }
        
        // Get users and calculate match percentages
        $potentialMatches = $query->get()->map(function ($potentialMatch) use ($user) {
            $matchPercentage = $this->calculateMatchPercentage($user, $potentialMatch);
            $potentialMatch->match_percentage = $matchPercentage;
            return $potentialMatch;
        });
        
        // Sort by match percentage and return top matches
        return $potentialMatches->sortByDesc('match_percentage')->take($limit);
    }
    
    /**
     * Generate alignment description based on match data
     */
    public function generateAlignmentDescription(User $currentUser, User $match): string
    {
        $descriptions = [
            "Your shared values and life goals create a strong foundation for connection.",
            "Both of you prioritize similar things in relationships, making for great compatibility.",
            "Your complementary personalities could bring wonderful balance to each other's lives.",
            "You both seem to value personal growth and meaningful connections.",
            "Your similar approach to life and relationships shows great potential for harmony."
        ];
        
        // Add specific insights based on quiz results
        $currentQuiz = $currentUser->quiz_results ? json_decode($currentUser->quiz_results, true) : [];
        $matchQuiz = $match->quiz_results ? json_decode($match->quiz_results, true) : [];
        
        $specificDescriptions = [];
        
        if (isset($currentQuiz['del_match_code']) && isset($matchQuiz['del_match_code'])) {
            $currentCode = $currentQuiz['del_match_code'];
            $matchCode = $matchQuiz['del_match_code'];
            
            if ($currentCode === $matchCode) {
                $specificDescriptions[] = "You share the same Del Match Code ({$currentCode}), indicating very similar core values and life approach.";
            } else {
                $specificDescriptions[] = "Your Del Match Codes ({$currentCode} and {$matchCode}) complement each other beautifully.";
            }
        }
        
        // Return a specific description if available, otherwise random generic one
        return !empty($specificDescriptions) ? $specificDescriptions[0] : $descriptions[array_rand($descriptions)];
    }
}