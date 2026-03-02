<?php

namespace Database\Seeders;

use App\Models\QuizQuestion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuizQuestionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = [
            // Phase 1: Del Match Code™
            ['question_id' => 'q1', 'section' => 'Del Match Code™', 'question' => "When you think about family and kids in your future, which feels most true?", 'options' => [['text' => "I want kids / family is central to my future.", 'value' => "F"], ['text' => "I don't want kids.", 'value' => "N"], ['text' => "I'm not sure yet.", 'value' => "U"]], 'order' => 1],
            ['question_id' => 'q2', 'section' => 'Del Match Code™', 'question' => "Which best describes how you approach emotional connection?", 'options' => [['text' => "I feel ready and open to connect emotionally.", 'value' => "E"], ['text' => "I'm still healing / moving carefully.", 'value' => "H"], ['text' => "I'm open, but I take time to build trust.", 'value' => "O"]], 'order' => 2],
            ['question_id' => 'q3', 'section' => 'Del Match Code™', 'question' => "How do you think about relationship structure?", 'options' => [['text' => "I'm monogamous — I believe in commitment with one partner at a time.", 'value' => "M"], ['text' => "I practice or prefer ethical non-monogamy / polyamory.", 'value' => "P"], ['text' => "I'm open-minded — I could explore different structures if there's mutual understanding and respect.", 'value' => "F"]], 'order' => 3],
            ['question_id' => 'q4', 'section' => 'Del Match Code™', 'question' => "When you enter relationships, what's your natural approach?", 'options' => [['text' => "I'm looking for long-term partnership.", 'value' => "L"], ['text' => "I'm interested in something short-term.", 'value' => "S"], ['text' => "I want to explore and see what develops.", 'value' => "E"]], 'order' => 4],
            ['question_id' => 'q5', 'section' => 'Del Match Code™', 'question' => "How important is cultural alignment to you in a partner?", 'options' => [['text' => "It's very important — I want shared cultural values.", 'value' => "C"], ['text' => "I'm adaptable — blending cultures is exciting.", 'value' => "A"], ['text' => "Culture doesn't factor much into my choice.", 'value' => "I"]], 'order' => 5],
            
            // Phase 2a: Attachment Style
            ['question_id' => 'q6', 'section' => 'Attachment Style', 'question' => "Your partner doesn't reply quickly — how do you feel?", 'options' => [['text' => "Calm — I trust they'll respond.", 'value' => "A"], ['text' => "Worried — maybe they're upset or losing interest.", 'value' => "B"], ['text' => "Fine — I like space and time apart.", 'value' => "C"], ['text' => "Torn — I want closeness but fear being needy.", 'value' => "D"]], 'order' => 6],
            ['question_id' => 'q7', 'section' => 'Attachment Style', 'question' => "Depending on someone emotionally feels…", 'options' => [['text' => "Comfortable and safe.", 'value' => "A"], ['text' => "Nerve-wracking — what if they're not there?", 'value' => "B"], ['text' => "Uncomfortable — I prefer to rely on myself.", 'value' => "C"], ['text' => "Conflicted — I want it but fear being hurt.", 'value' => "D"]], 'order' => 7],
            ['question_id' => 'q8', 'section' => 'Attachment Style', 'question' => "Conflict arises — your instinct?", 'options' => [['text' => "Talk it through calmly.", 'value' => "A"], ['text' => "Try hard to fix it quickly.", 'value' => "B"], ['text' => "Withdraw — I need space.", 'value' => "C"], ['text' => "Overwhelmed — part of me runs, part clings.", 'value' => "D"]], 'order' => 8],
            ['question_id' => 'q9', 'section' => 'Attachment Style', 'question' => "What is your ideal relationship dynamic?", 'options' => [['text' => "Balance of closeness and independence.", 'value' => "A"], ['text' => "Lots of reassurance and closeness.", 'value' => "B"], ['text' => "Independence and space.", 'value' => "C"], ['text' => "Deep connection — but with caution.", 'value' => "D"]], 'order' => 9],
            
            // Phase 2b: Family Imprint
            ['question_id' => 'q10', 'section' => 'Family Imprint', 'question' => "Growing up, love in my family looked most like…", 'options' => [['text' => "Stability and support.", 'value' => "stability"], ['text' => "Sacrifice and duty.", 'value' => "sacrifice"], ['text' => "Freedom and independence.", 'value' => "freedom"], ['text' => "Achievement and expectations.", 'value' => "achievement"], ['text' => "It wasn't shown directly.", 'value' => "hidden"]], 'order' => 10],
            ['question_id' => 'q11', 'section' => 'Family Imprint', 'question' => "In my family, conflict was usually…", 'options' => [['text' => "Avoided quietly.", 'value' => "avoided"], ['text' => "Loud and explosive.", 'value' => "explosive"], ['text' => "Worked through together.", 'value' => "collaborative"], ['text' => "Ignored until it passed.", 'value' => "ignored"]], 'order' => 11],
            ['question_id' => 'q12', 'section' => 'Family Imprint', 'question' => "When it came to emotions…", 'options' => [['text' => "We talked about feelings openly.", 'value' => "open"], ['text' => "Feelings were private.", 'value' => "private"], ['text' => "Emotions were discouraged.", 'value' => "discouraged"], ['text' => "Mixed — sometimes warm, sometimes unpredictable.", 'value' => "mixed"]], 'order' => 12],
            ['question_id' => 'q13', 'section' => 'Family Imprint', 'question' => "What message did I learn about relationships?", 'options' => [['text' => "Love means stability.", 'value' => "stability"], ['text' => "Love means sacrifice.", 'value' => "sacrifice"], ['text' => "Love means freedom.", 'value' => "freedom"], ['text' => "Love means achievement.", 'value' => "achievement"], ['text' => "Love is unpredictable.", 'value' => "unpredictable"]], 'order' => 13],
            ['question_id' => 'q14', 'section' => 'Family Imprint', 'question' => "Today, I see how my family background…", 'options' => [['text' => "Helps me value closeness.", 'value' => "closeness"], ['text' => "Makes it harder to trust.", 'value' => "trust_issues"], ['text' => "Encourages independence.", 'value' => "independence"], ['text' => "Shapes my expectations strongly.", 'value' => "high_expectations"]], 'order' => 14],
            
            // Phase 3a: Values Alignment
            ['question_id' => 'q15', 'section' => 'Values Alignment', 'question' => "When I picture my best life, the theme is…", 'options' => [['text' => "Building security.", 'value' => "security"], ['text' => "Chasing achievement.", 'value' => "achievement"], ['text' => "Creating harmony.", 'value' => "harmony"], ['text' => "Exploring freedom.", 'value' => "freedom"]], 'order' => 15],
            ['question_id' => 'q16', 'section' => 'Values Alignment', 'question' => "If I had to give one up, I'd let go of…", 'options' => [['text' => "Financial success.", 'value' => "not_money_focused"], ['text' => "Close friendships.", 'value' => "not_relationship_focused"], ['text' => "Creative freedom.", 'value' => "not_creativity_focused"], ['text' => "Stability at home.", 'value' => "not_stability_focused"]], 'order' => 16],
            ['question_id' => 'q17', 'section' => 'Values Alignment', 'question' => "I feel most fulfilled when…", 'options' => [['text' => "I achieve a goal.", 'value' => "achievement"], ['text' => "I deepen relationships.", 'value' => "connection"], ['text' => "I feel safe and secure.", 'value' => "security"], ['text' => "I explore something new.", 'value' => "exploration"]], 'order' => 17],
            ['question_id' => 'q18', 'section' => 'Values Alignment', 'question' => "A partner inspires me most when they…", 'options' => [['text' => "Help me grow.", 'value' => "growth"], ['text' => "Make me feel at peace.", 'value' => "peace"], ['text' => "Build stability with me.", 'value' => "stability"], ['text' => "Share adventure with me.", 'value' => "adventure"]], 'order' => 18],
            ['question_id' => 'q19', 'section' => 'Values Alignment', 'question' => "My core value is…", 'options' => [['text' => "Growth.", 'value' => "growth"], ['text' => "Peace.", 'value' => "peace"], ['text' => "Security.", 'value' => "security"], ['text' => "Success.", 'value' => "success"]], 'order' => 19],
            
            // Phase 3b: Energy Style
            ['question_id' => 'q20', 'section' => 'Energy Style', 'question' => "I'm usually energized by…", 'options' => [['text' => "Time with people. (Social)", 'value' => "social"], ['text' => "Time alone. (Reflective)", 'value' => "reflective"]], 'order' => 20],
            ['question_id' => 'q21', 'section' => 'Energy Style', 'question' => "I prefer…", 'options' => [['text' => "Structure and planning.", 'value' => "structured"], ['text' => "Flexibility and spontaneity.", 'value' => "flexible"]], 'order' => 21],
            ['question_id' => 'q22', 'section' => 'Energy Style', 'question' => "When stressed, I tend to…", 'options' => [['text' => "Stay calm.", 'value' => "calm"], ['text' => "Get restless.", 'value' => "restless"]], 'order' => 22],
            ['question_id' => 'q23', 'section' => 'Energy Style', 'question' => "I thrive most in…", 'options' => [['text' => "Large gatherings.", 'value' => "large_groups"], ['text' => "Small groups or 1:1.", 'value' => "small_groups"]], 'order' => 23],
            ['question_id' => 'q24', 'section' => 'Energy Style', 'question' => "My natural rhythm is…", 'options' => [['text' => "Morning person.", 'value' => "morning"], ['text' => "Night owl.", 'value' => "night"], ['text' => "Flexible.", 'value' => "flexible"]], 'order' => 24],
            
            // Phase 3c: Practical Compatibility
            ['question_id' => 'q25', 'section' => 'Practical Compatibility', 'question' => "Do you drink?", 'options' => [['text' => "Never", 'value' => "never"], ['text' => "Socially", 'value' => "socially"], ['text' => "Regularly", 'value' => "regularly"]], 'order' => 25],
            ['question_id' => 'q26', 'section' => 'Practical Compatibility', 'question' => "Do you smoke/vape?", 'options' => [['text' => "Never", 'value' => "never"], ['text' => "Occasionally", 'value' => "occasionally"], ['text' => "Regularly", 'value' => "regularly"]], 'order' => 26],
            ['question_id' => 'q27', 'section' => 'Practical Compatibility', 'question' => "How active are you physically?", 'options' => [['text' => "Daily", 'value' => "daily"], ['text' => "Weekly", 'value' => "weekly"], ['text' => "Rarely", 'value' => "rarely"]], 'order' => 27],
            ['question_id' => 'q28', 'section' => 'Practical Compatibility', 'question' => "What is your food approach?", 'options' => [['text' => "Love cooking", 'value' => "love_cooking"], ['text' => "Cook when needed", 'value' => "cook_when_needed"], ['text' => "Prefer eating out", 'value' => "prefer_eating_out"]], 'order' => 28],
            ['question_id' => 'q29', 'section' => 'Practical Compatibility', 'question' => "Pets in your life?", 'options' => [['text' => "Must have", 'value' => "must_have"], ['text' => "Don't mind", 'value' => "dont_mind"], ['text' => "Not for me", 'value' => "not_for_me"]], 'order' => 29],
            ['question_id' => 'q30', 'section' => 'Practical Compatibility', 'question' => "Do you want kids (in the future)?", 'options' => [['text' => "Yes", 'value' => "yes"], ['text' => "No", 'value' => "no"], ['text' => "Maybe", 'value' => "maybe"]], 'order' => 30],
            ['question_id' => 'q31', 'section' => 'Practical Compatibility', 'question' => "How important is family involvement in relationships?", 'options' => [['text' => "Very important", 'value' => "very_important"], ['text' => "Somewhat", 'value' => "somewhat"], ['text' => "Not important", 'value' => "not_important"]], 'order' => 31],
            ['question_id' => 'q32', 'section' => 'Practical Compatibility', 'question' => "Do you practice a faith/spiritual path?", 'options' => [['text' => "Yes, strongly", 'value' => "yes_strongly"], ['text' => "Somewhat", 'value' => "somewhat"], ['text' => "Not at all", 'value' => "not_at_all"]], 'order' => 32],
            ['question_id' => 'q33', 'section' => 'Practical Compatibility', 'question' => "How important is it that your partner shares it?", 'options' => [['text' => "Must-have", 'value' => "must_have"], ['text' => "Nice-to-have", 'value' => "nice_to_have"], ['text' => "Not important", 'value' => "not_important"]], 'order' => 33],
            ['question_id' => 'q34', 'section' => 'Practical Compatibility', 'question' => "What is your living environment preference?", 'options' => [['text' => "City", 'value' => "city"], ['text' => "Suburb", 'value' => "suburb"], ['text' => "Rural", 'value' => "rural"], ['text' => "Flexible", 'value' => "flexible"]], 'order' => 34],
            ['question_id' => 'q35', 'section' => 'Practical Compatibility', 'question' => "What is your travel style?", 'options' => [['text' => "Plan everything", 'value' => "plan_everything"], ['text' => "Pack & go", 'value' => "pack_and_go"], ['text' => "Flexible", 'value' => "flexible"]], 'order' => 35],
            ['question_id' => 'q36', 'section' => 'Practical Compatibility', 'question' => "What is your ideal weekend?", 'options' => [['text' => "Quiet at home", 'value' => "quiet_at_home"], ['text' => "Out with friends", 'value' => "out_with_friends"], ['text' => "Family-centered", 'value' => "family_centered"], ['text' => "Exploring new places", 'value' => "exploring_new_places"]], 'order' => 36],
            ['question_id' => 'q37', 'section' => 'Practical Compatibility', 'question' => "What is your money philosophy?", 'options' => [['text' => "Saver", 'value' => "saver"], ['text' => "Spender", 'value' => "spender"], ['text' => "Balanced", 'value' => "balanced"]], 'order' => 37],
            ['question_id' => 'q38', 'section' => 'Practical Compatibility', 'question' => "What is your sleep style?", 'options' => [['text' => "Early riser", 'value' => "early_riser"], ['text' => "Night owl", 'value' => "night_owl"], ['text' => "In-between", 'value' => "in_between"]], 'order' => 38],
            ['question_id' => 'q39', 'section' => 'Readiness Gate', 'question' => "Reflecting on this process, how ready do you feel for a new connection right now?", 'options' => [['text' => "I feel clear, grounded, and ready to connect.", 'value' => "ready"], ['text' => "I'm still in a growth stage and would like to focus on myself for a bit.", 'value' => "not_ready"]], 'order' => 39]
        ];

        foreach ($questions as $question) {
            QuizQuestion::updateOrCreate(
                ['question_id' => $question['question_id']], 
                $question
            );
        }

        $this->command->info('Quiz questions seeded successfully!');
    }
}
