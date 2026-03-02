<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;

class GeminiAvatarService
{
    private $apiKey;
    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
        $this->baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';
    }

    /**
     * Generate avatar image using Gemini AI
     */
    public function generateAvatar($avatarData, $age, $gender)
    {
        try {
            // Create detailed prompt for avatar generation
            $prompt = $this->createAvatarPrompt($avatarData, $age, $gender);
            
            // Since Gemini doesn't directly generate images, we'll use it to create a detailed description
            // and then use a placeholder service or return a stylized avatar based on the description
            $description = $this->getAvatarDescription($prompt);
            
            // For now, we'll create a stylized avatar using CSS/SVG or return a placeholder
            // In a real implementation, you'd integrate with an image generation service like DALL-E, Midjourney, etc.
            $avatarPath = $this->createStylizedAvatar($avatarData, $age, $gender);
            
            return [
                'success' => true,
                'avatar_path' => $avatarPath,
                'description' => $description,
                'avatar_data' => $avatarData
            ];
            
        } catch (Exception $e) {
            Log::error('Avatar generation failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => 'Failed to generate avatar. Please try again.',
                'avatar_path' => null
            ];
        }
    }

    /**
     * Create detailed prompt for avatar generation
     */
    private function createAvatarPrompt($avatarData, $age, $gender)
    {
        $prompt = "Create a detailed description for a professional, artistic avatar portrait of a {$age}-year-old {$gender} person with the following characteristics:\n\n";
        
        $prompt .= "Physical Appearance:\n";
        $prompt .= "- Build: {$avatarData['build']}\n";
        $prompt .= "- Skin tone: {$avatarData['skin']}\n";
        $prompt .= "- Hair: {$avatarData['hair']}\n";
        $prompt .= "- Eyes: {$avatarData['eyes']}\n";
        $prompt .= "- Age: {$age} years old\n";
        $prompt .= "- Gender: {$gender}\n\n";
        
        $prompt .= "Style Guidelines:\n";
        $prompt .= "- Professional headshot style\n";
        $prompt .= "- Warm, friendly expression\n";
        $prompt .= "- Clean, modern aesthetic\n";
        $prompt .= "- Soft lighting\n";
        $prompt .= "- Neutral background\n";
        $prompt .= "- High quality, detailed artwork\n";
        $prompt .= "- Suitable for a dating profile\n\n";
        
        $prompt .= "Please provide a detailed description that could be used to generate this avatar image.";
        
        return $prompt;
    }

    /**
     * Get avatar description from Gemini AI
     */
    private function getAvatarDescription($prompt)
    {
        try {
            if (!$this->apiKey) {
                return "A stylized avatar based on the provided characteristics.";
            }

            $response = Http::timeout(30)->post($this->baseUrl . '?key=' . $this->apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => $prompt
                            ]
                        ]
                    ]
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    return $data['candidates'][0]['content']['parts'][0]['text'];
                }
            }

            return "A personalized avatar based on your unique characteristics.";
            
        } catch (Exception $e) {
            Log::error('Gemini API call failed: ' . $e->getMessage());
            return "A stylized avatar representation.";
        }
    }

    /**
     * Create a stylized avatar (placeholder implementation)
     * In a real implementation, you would integrate with an image generation service
     */
    private function createStylizedAvatar($avatarData, $age, $gender)
    {
        try {
            // Create a unique filename
            $filename = 'avatar_' . uniqid() . '.svg';
            
            // Add age and gender to avatar data for Snapchat-style generation
            $avatarData['age'] = $age;
            $avatarData['gender'] = $gender;
            
            // Generate Snapchat-style SVG avatar
            $svg = $this->generateSnapchatStyleAvatar($avatarData);
            
            // Store the SVG file
            Storage::disk('public')->put('avatars/' . $filename, $svg);
            
            return 'avatars/' . $filename;
            
        } catch (Exception $e) {
            Log::error('Snapchat-style avatar creation failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate realistic SVG avatar based on characteristics
     */
    private function generateSVGAvatar($avatarData, $age, $gender)
    {
        // Map characteristics to realistic colors and styles
        $skinColor = $this->mapSkinColor($avatarData['skin']);
        $hairColor = $this->mapHairColor($avatarData['hair']);
        $eyeColor = $this->mapEyeColor($avatarData['eyes']);
        
        // Determine realistic proportions based on age and gender
        $faceWidth = $gender === 'male' ? 48 : 44;
        $faceHeight = $gender === 'male' ? 55 : 52;
        $jawWidth = $gender === 'male' ? 42 : 38;
        
        // Determine hair style and facial features
        $hairStyle = $this->determineHairStyle($avatarData['hair']);
        $buildType = $this->determineBuildType($avatarData['build']);
        
        // Create realistic SVG with gradients and shadows
        $svg = '<?xml version="1.0" encoding="UTF-8"?>
<svg width="200" height="200" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
    <defs>
        <!-- Realistic gradients for skin -->
        <radialGradient id="skinGradient" cx="0.3" cy="0.3" r="0.8">
            <stop offset="0%" style="stop-color:' . $this->lightenColor($skinColor, 0.15) . ';stop-opacity:1" />
            <stop offset="70%" style="stop-color:' . $skinColor . ';stop-opacity:1" />
            <stop offset="100%" style="stop-color:' . $this->darkenColor($skinColor, 0.2) . ';stop-opacity:1" />
        </radialGradient>
        
        <!-- Hair gradient -->
        <linearGradient id="hairGradient" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:' . $this->lightenColor($hairColor, 0.1) . ';stop-opacity:1" />
            <stop offset="100%" style="stop-color:' . $this->darkenColor($hairColor, 0.15) . ';stop-opacity:1" />
        </linearGradient>
        
        <!-- Eye gradient -->
        <radialGradient id="eyeGradient" cx="0.3" cy="0.3" r="0.7">
            <stop offset="0%" style="stop-color:' . $this->lightenColor($eyeColor, 0.2) . ';stop-opacity:1" />
            <stop offset="100%" style="stop-color:' . $eyeColor . ';stop-opacity:1" />
        </radialGradient>
        
        <!-- Shadow filter -->
        <filter id="shadow" x="-20%" y="-20%" width="140%" height="140%">
            <feDropShadow dx="2" dy="2" stdDeviation="3" flood-color="rgba(0,0,0,0.3)"/>
        </filter>
    </defs>
    
    <!-- Background with subtle gradient -->
    <circle cx="100" cy="100" r="95" fill="url(#backgroundGradient)" stroke="none"/>
    <defs>
        <radialGradient id="backgroundGradient" cx="0.5" cy="0.3" r="0.8">
            <stop offset="0%" style="stop-color:#ffffff;stop-opacity:1" />
            <stop offset="100%" style="stop-color:#f8f9fa;stop-opacity:1" />
        </radialGradient>
    </defs>
    
    <!-- Neck and shoulders -->
    <path d="M 70 180 Q 100 170 130 180 L 130 200 L 70 200 Z" fill="url(#skinGradient)" />
    
    <!-- Face shape with realistic proportions -->
    <ellipse cx="100" cy="105" rx="' . $faceWidth . '" ry="' . $faceHeight . '" fill="url(#skinGradient)" filter="url(#shadow)"/>
    
    <!-- Jaw line for more realistic face shape -->
    <path d="M ' . (100 - $jawWidth) . ' 140 Q 100 155 ' . (100 + $jawWidth) . ' 140" 
          fill="url(#skinGradient)" stroke="none"/>
    
    <!-- Hair with realistic styling -->
    ' . $this->generateRealisticHairSVG($hairStyle, $hairColor, $gender) . '
    
    <!-- Realistic eyes with depth -->
    <g id="leftEye">
        <!-- Eye socket shadow -->
        <ellipse cx="85" cy="95" rx="8" ry="6" fill="' . $this->darkenColor($skinColor, 0.1) . '" opacity="0.3"/>
        <!-- Eye white -->
        <ellipse cx="85" cy="95" rx="7" ry="5" fill="#ffffff"/>
        <!-- Iris -->
        <circle cx="85" cy="95" r="3.5" fill="url(#eyeGradient)"/>
        <!-- Pupil -->
        <circle cx="85" cy="95" r="1.5" fill="#000000"/>
        <!-- Eye highlight -->
        <circle cx="86" cy="93.5" r="0.8" fill="#ffffff" opacity="0.8"/>
        <!-- Upper eyelid -->
        <path d="M 78 92 Q 85 89 92 92" stroke="' . $this->darkenColor($skinColor, 0.2) . '" stroke-width="1" fill="none"/>
        <!-- Lower eyelid -->
        <path d="M 78 98 Q 85 99 92 98" stroke="' . $this->darkenColor($skinColor, 0.15) . '" stroke-width="0.5" fill="none"/>
    </g>
    
    <g id="rightEye">
        <!-- Eye socket shadow -->
        <ellipse cx="115" cy="95" rx="8" ry="6" fill="' . $this->darkenColor($skinColor, 0.1) . '" opacity="0.3"/>
        <!-- Eye white -->
        <ellipse cx="115" cy="95" rx="7" ry="5" fill="#ffffff"/>
        <!-- Iris -->
        <circle cx="115" cy="95" r="3.5" fill="url(#eyeGradient)"/>
        <!-- Pupil -->
        <circle cx="115" cy="95" r="1.5" fill="#000000"/>
        <!-- Eye highlight -->
        <circle cx="116" cy="93.5" r="0.8" fill="#ffffff" opacity="0.8"/>
        <!-- Upper eyelid -->
        <path d="M 108 92 Q 115 89 122 92" stroke="' . $this->darkenColor($skinColor, 0.2) . '" stroke-width="1" fill="none"/>
        <!-- Lower eyelid -->
        <path d="M 108 98 Q 115 99 122 98" stroke="' . $this->darkenColor($skinColor, 0.15) . '" stroke-width="0.5" fill="none"/>
    </g>
    
    <!-- Realistic eyebrows -->
    <path d="M 76 87 Q 80 85 85 86 Q 90 87 94 89" 
          stroke="' . $this->darkenColor($hairColor, 0.1) . '" stroke-width="2.5" fill="none" stroke-linecap="round"/>
    <path d="M 106 89 Q 110 87 115 86 Q 120 85 124 87" 
          stroke="' . $this->darkenColor($hairColor, 0.1) . '" stroke-width="2.5" fill="none" stroke-linecap="round"/>
    
    <!-- Realistic nose with shadows -->
    <g id="nose">
        <!-- Nose bridge -->
        <path d="M 100 100 Q 99 108 100 115" stroke="' . $this->darkenColor($skinColor, 0.1) . '" stroke-width="1" fill="none"/>
        <!-- Nostrils -->
        <ellipse cx="97" cy="113" rx="1.5" ry="2" fill="' . $this->darkenColor($skinColor, 0.2) . '" opacity="0.6"/>
        <ellipse cx="103" cy="113" rx="1.5" ry="2" fill="' . $this->darkenColor($skinColor, 0.2) . '" opacity="0.6"/>
        <!-- Nose tip highlight -->
        <ellipse cx="100" cy="110" rx="2" ry="1.5" fill="' . $this->lightenColor($skinColor, 0.1) . '" opacity="0.4"/>
    </g>
    
    <!-- Realistic mouth -->
    <g id="mouth">
        <!-- Lip line -->
        <path d="M 88 125 Q 94 128 100 127 Q 106 128 112 125" 
              stroke="' . $this->darkenColor($skinColor, 0.3) . '" stroke-width="1.5" fill="none" stroke-linecap="round"/>
        <!-- Upper lip -->
        <path d="M 88 125 Q 94 123 100 124 Q 106 123 112 125" 
              fill="#c97064" opacity="0.6"/>
        <!-- Lower lip -->
        <path d="M 88 125 Q 94 130 100 129 Q 106 130 112 125" 
              fill="#d4807a" opacity="0.7"/>
    </g>
    
    <!-- Facial contours and cheekbones -->
    <ellipse cx="75" cy="110" rx="3" ry="8" fill="' . $this->lightenColor($skinColor, 0.1) . '" opacity="0.3"/>
    <ellipse cx="125" cy="110" rx="3" ry="8" fill="' . $this->lightenColor($skinColor, 0.1) . '" opacity="0.3"/>
    
    <!-- Optional: Facial hair for males -->
    ' . ($gender === 'male' ? $this->generateRealisticFacialHair($skinColor, $hairColor, $age) : '') . '
    
    <!-- Age-appropriate features -->
    ' . $this->generateSnapchatStyleAvatar($avatarData) . '
</svg>';

        return $svg;
    }

    /**
     * Map skin tone description to realistic color
     */
    private function mapSkinColor($skinDescription)
    {
        $skinDescription = strtolower($skinDescription);
        
        if (strpos($skinDescription, 'fair') !== false || strpos($skinDescription, 'light') !== false || strpos($skinDescription, 'pale') !== false) {
            return '#fde8d7';
        } elseif (strpos($skinDescription, 'olive') !== false || strpos($skinDescription, 'mediterranean') !== false) {
            return '#deb887';
        } elseif (strpos($skinDescription, 'medium') !== false || strpos($skinDescription, 'tan') !== false) {
            return '#d2b48c';
        } elseif (strpos($skinDescription, 'dark') !== false || strpos($skinDescription, 'brown') !== false) {
            return '#8b4513';
        } elseif (strpos($skinDescription, 'black') !== false || strpos($skinDescription, 'ebony') !== false) {
            return '#654321';
        } elseif (strpos($skinDescription, 'asian') !== false || strpos($skinDescription, 'yellow') !== false) {
            return '#f4d1a6';
        } else {
            return '#deb887'; // Default medium tone
        }
    }

    /**
     * Map hair description to color
     */
    private function mapHairColor($hairDescription)
    {
        $hairDescription = strtolower($hairDescription);
        
        if (strpos($hairDescription, 'blonde') !== false || strpos($hairDescription, 'blond') !== false) {
            return '#faf0be';
        } elseif (strpos($hairDescription, 'brown') !== false) {
            return '#8B4513';
        } elseif (strpos($hairDescription, 'black') !== false) {
            return '#2c1810';
        } elseif (strpos($hairDescription, 'red') !== false || strpos($hairDescription, 'ginger') !== false) {
            return '#cc6600';
        } elseif (strpos($hairDescription, 'gray') !== false || strpos($hairDescription, 'grey') !== false) {
            return '#a0a0a0';
        } else {
            return '#8B4513'; // Default brown
        }
    }

    /**
     * Map eye description to color
     */
    private function mapEyeColor($eyeDescription)
    {
        $eyeDescription = strtolower($eyeDescription);
        
        if (strpos($eyeDescription, 'blue') !== false) {
            return '#4169e1';
        } elseif (strpos($eyeDescription, 'green') !== false) {
            return '#228b22';
        } elseif (strpos($eyeDescription, 'brown') !== false || strpos($eyeDescription, 'dark') !== false) {
            return '#8b4513';
        } elseif (strpos($eyeDescription, 'hazel') !== false) {
            return '#cd853f';
        } elseif (strpos($eyeDescription, 'gray') !== false || strpos($eyeDescription, 'grey') !== false) {
            return '#708090';
        } else {
            return '#8b4513'; // Default brown
        }
    }

    /**
     * Determine hair style from description
     */
    private function determineHairStyle($hairDescription)
    {
        $hairDescription = strtolower($hairDescription);
        
        if (strpos($hairDescription, 'long') !== false) {
            return 'long';
        } elseif (strpos($hairDescription, 'short') !== false) {
            return 'short';
        } elseif (strpos($hairDescription, 'curly') !== false) {
            return 'curly';
        } elseif (strpos($hairDescription, 'wavy') !== false) {
            return 'wavy';
        } else {
            return 'medium';
        }
    }

    /**
     * Generate hair SVG based on style
     */
    private function generateHairSVG($style, $color)
    {
        switch ($style) {
            case 'long':
                return '<path d="M 55 75 Q 50 60 60 50 Q 100 30 140 50 Q 150 60 145 75 Q 145 90 150 110 Q 150 130 145 150 L 140 160 Q 100 170 60 160 L 55 150 Q 50 130 50 110 Q 50 90 55 75" fill="' . $color . '"/>';
            case 'short':
                return '<path d="M 65 80 Q 60 65 70 55 Q 100 40 130 55 Q 140 65 135 80" fill="' . $color . '"/>';
            case 'curly':
                return '<path d="M 60 85 Q 55 70 65 60 Q 75 50 85 55 Q 95 45 105 55 Q 115 45 125 55 Q 135 50 145 60 Q 155 70 150 85" fill="' . $color . '"/>
                        <circle cx="70" cy="65" r="8" fill="' . $color . '"/>
                        <circle cx="90" cy="60" r="6" fill="' . $color . '"/>
                        <circle cx="110" cy="60" r="6" fill="' . $color . '"/>
                        <circle cx="130" cy="65" r="8" fill="' . $color . '"/>';
            default:
                return '<path d="M 60 80 Q 55 65 65 55 Q 100 35 135 55 Q 145 65 140 80 Q 140 95 135 105" fill="' . $color . '"/>';
        }
    }

    /**
     * Generate facial hair for males
     */
    private function generateFacialHair($skinColor)
    {
        $facialHairColor = $this->darkenColor($skinColor, 0.3);
        return '<path d="M 85 135 Q 100 140 115 135 Q 115 145 100 148 Q 85 145 85 135" fill="' . $facialHairColor . '"/>';
    }

    /**
     * Lighten a color by a percentage
     */
    private function lightenColor($color, $percent)
    {
        $color = str_replace('#', '', $color);
        $r = hexdec(substr($color, 0, 2));
        $g = hexdec(substr($color, 2, 2));
        $b = hexdec(substr($color, 4, 2));
        
        $r = min(255, $r + ((255 - $r) * $percent));
        $g = min(255, $g + ((255 - $g) * $percent));
        $b = min(255, $b + ((255 - $b) * $percent));
        
        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }

    /**
     * Darken a color by a percentage
     */
    private function darkenColor($color, $percent)
    {
        $color = str_replace('#', '', $color);
        $r = hexdec(substr($color, 0, 2));
        $g = hexdec(substr($color, 2, 2));
        $b = hexdec(substr($color, 4, 2));
        
        $r = max(0, $r - ($r * $percent));
        $g = max(0, $g - ($g * $percent));
        $b = max(0, $b - ($b * $percent));
        
        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }

    /**
     * Determine build type characteristics
     */
    private function determineBuildType($buildDescription)
    {
        $buildDescription = strtolower($buildDescription);
        
        if (strpos($buildDescription, 'slim') !== false || strpos($buildDescription, 'slender') !== false || strpos($buildDescription, 'thin') !== false) {
            return 'slim';
        } elseif (strpos($buildDescription, 'athletic') !== false || strpos($buildDescription, 'fit') !== false || strpos($buildDescription, 'muscular') !== false) {
            return 'athletic';
        } elseif (strpos($buildDescription, 'heavy') !== false || strpos($buildDescription, 'large') !== false || strpos($buildDescription, 'big') !== false) {
            return 'heavy';
        } else {
            return 'average';
        }
    }

    /**
     * Generate realistic hair SVG
     */
    private function generateRealisticHairSVG($style, $color, $gender)
    {
        switch ($style) {
            case 'long':
                return '<g id="hair">
                    <path d="M 50 70 Q 45 50 60 40 Q 100 25 140 40 Q 155 50 150 70 Q 155 90 160 120 Q 160 150 155 180 L 150 190 Q 100 200 50 190 L 45 180 Q 40 150 40 120 Q 45 90 50 70" 
                          fill="url(#hairGradient)" stroke="' . $this->darkenColor($color, 0.2) . '" stroke-width="0.5"/>
                    <!-- Hair strands for texture -->
                    <path d="M 60 50 Q 65 80 70 120 Q 75 160 80 190" stroke="' . $this->darkenColor($color, 0.1) . '" stroke-width="0.8" fill="none" opacity="0.6"/>
                    <path d="M 80 45 Q 85 75 90 115 Q 95 155 100 185" stroke="' . $this->darkenColor($color, 0.1) . '" stroke-width="0.8" fill="none" opacity="0.6"/>
                    <path d="M 120 45 Q 115 75 110 115 Q 105 155 100 185" stroke="' . $this->darkenColor($color, 0.1) . '" stroke-width="0.8" fill="none" opacity="0.6"/>
                    <path d="M 140 50 Q 135 80 130 120 Q 125 160 120 190" stroke="' . $this->darkenColor($color, 0.1) . '" stroke-width="0.8" fill="none" opacity="0.6"/>
                </g>';
                
            case 'short':
                return '<g id="hair">
                    <path d="M 65 75 Q 60 60 70 50 Q 100 35 130 50 Q 140 60 135 75 Q 135 85 130 90" 
                          fill="url(#hairGradient)" stroke="' . $this->darkenColor($color, 0.2) . '" stroke-width="0.5"/>
                    <!-- Hair texture -->
                    <path d="M 70 55 Q 75 65 80 75" stroke="' . $this->darkenColor($color, 0.15) . '" stroke-width="1" fill="none" opacity="0.7"/>
                    <path d="M 90 50 Q 95 60 100 70" stroke="' . $this->darkenColor($color, 0.15) . '" stroke-width="1" fill="none" opacity="0.7"/>
                    <path d="M 110 50 Q 115 60 120 70" stroke="' . $this->darkenColor($color, 0.15) . '" stroke-width="1" fill="none" opacity="0.7"/>
                    <path d="M 130 55 Q 125 65 120 75" stroke="' . $this->darkenColor($color, 0.15) . '" stroke-width="1" fill="none" opacity="0.7"/>
                </g>';
                
            case 'curly':
                return '<g id="hair">
                    <path d="M 60 80 Q 55 65 65 55 Q 100 30 135 55 Q 145 65 140 80 Q 145 95 140 105" 
                          fill="url(#hairGradient)" stroke="' . $this->darkenColor($color, 0.2) . '" stroke-width="0.5"/>
                    <!-- Curly texture -->
                    <circle cx="70" cy="60" r="6" fill="url(#hairGradient)" opacity="0.8"/>
                    <circle cx="85" cy="55" r="5" fill="url(#hairGradient)" opacity="0.8"/>
                    <circle cx="100" cy="52" r="4" fill="url(#hairGradient)" opacity="0.8"/>
                    <circle cx="115" cy="55" r="5" fill="url(#hairGradient)" opacity="0.8"/>
                    <circle cx="130" cy="60" r="6" fill="url(#hairGradient)" opacity="0.8"/>
                    <circle cx="75" cy="75" r="4" fill="url(#hairGradient)" opacity="0.6"/>
                    <circle cx="125" cy="75" r="4" fill="url(#hairGradient)" opacity="0.6"/>
                </g>';
                
            default: // medium
                return '<g id="hair">
                    <path d="M 60 75 Q 55 60 65 50 Q 100 30 135 50 Q 145 60 140 75 Q 145 90 140 100 Q 135 110 130 115" 
                          fill="url(#hairGradient)" stroke="' . $this->darkenColor($color, 0.2) . '" stroke-width="0.5"/>
                    <!-- Hair flow lines -->
                    <path d="M 70 55 Q 80 70 85 90" stroke="' . $this->darkenColor($color, 0.1) . '" stroke-width="0.8" fill="none" opacity="0.6"/>
                    <path d="M 100 50 Q 100 70 100 90" stroke="' . $this->darkenColor($color, 0.1) . '" stroke-width="0.8" fill="none" opacity="0.6"/>
                    <path d="M 130 55 Q 120 70 115 90" stroke="' . $this->darkenColor($color, 0.1) . '" stroke-width="0.8" fill="none" opacity="0.6"/>
                </g>';
        }
    }

    /**
     * Generate realistic facial hair for males
     */
    private function generateRealisticFacialHair($skinColor, $hairColor, $age)
    {
        // More facial hair for older males
        $facialHairChance = $age > 25 ? 0.7 : 0.4;
        
        if (rand(0, 100) / 100 < $facialHairChance) {
            $facialHairColor = $this->darkenColor($hairColor, 0.1);
            
            // Beard styles based on age
            if ($age > 30 && rand(0, 1)) {
                // Full beard
                return '<g id="facialHair">
                    <path d="M 75 135 Q 85 145 100 148 Q 115 145 125 135 Q 125 155 100 165 Q 75 155 75 135" 
                          fill="' . $facialHairColor . '" opacity="0.8"/>
                    <!-- Beard texture -->
                    <path d="M 80 140 Q 85 150 90 160" stroke="' . $this->darkenColor($facialHairColor, 0.2) . '" stroke-width="0.5" fill="none"/>
                    <path d="M 100 142 Q 100 152 100 162" stroke="' . $this->darkenColor($facialHairColor, 0.2) . '" stroke-width="0.5" fill="none"/>
                    <path d="M 120 140 Q 115 150 110 160" stroke="' . $this->darkenColor($facialHairColor, 0.2) . '" stroke-width="0.5" fill="none"/>
                </g>';
            } else {
                // Mustache or goatee
                return '<g id="facialHair">
                    <path d="M 88 130 Q 100 135 112 130 Q 112 138 100 140 Q 88 138 88 130" 
                          fill="' . $facialHairColor . '" opacity="0.8"/>
                </g>';
            }
        }
        
        return '';
    }

    /**
     * Generate multiple avatar style previews for user to choose from
     */
    public function generateAvatarPreviews($avatarData, $age, $gender)
    {
        $avatarData['age'] = $age;
        $avatarData['gender'] = $gender;
        
        $previews = [];
        $styles = ['cartoon', 'minimal', 'detailed', 'artistic'];
        
        foreach ($styles as $style) {
            $avatarData['style'] = $style;
            $previews[$style] = [
                'svg' => $this->generateSnapchatStyleAvatar($avatarData),
                'name' => ucfirst($style),
                'description' => $this->getStyleDescription($style)
            ];
        }
        
        return $previews;
    }
    
    /**
     * Get style descriptions for user interface
     */
    private function getStyleDescription($style)
    {
        $descriptions = [
            'cartoon' => 'Fun and expressive, like Snapchat Bitmoji',
            'minimal' => 'Clean and simple design',
            'detailed' => 'Realistic with more features',
            'artistic' => 'Creative and abstract style'
        ];
        
        return $descriptions[$style] ?? 'Custom style';
    }

    /**
     * Generate Snapchat-style avatar with multiple variations
     */
    public function generateSnapchatStyleAvatar($avatarData)
    {
        // Avatar style variations
        $styles = [
            'cartoon' => $this->generateCartoonAvatar($avatarData),
            'minimal' => $this->generateMinimalAvatar($avatarData),
            'detailed' => $this->generateDetailedAvatar($avatarData),
            'artistic' => $this->generateArtisticAvatar($avatarData)
        ];
        
        // Return random style or specific if requested
        $selectedStyle = $avatarData['style'] ?? 'cartoon'; // Default to cartoon instead of random
        return $styles[$selectedStyle];
    }
    
    /**
     * Generate cartoon-style avatar (Snapchat Bitmoji-like)
     */
    private function generateCartoonAvatar($avatarData)
    {
        $skinTone = $this->getSkinToneColor($avatarData['skin'] ?? 'medium');
        $hairColor = $this->getHairColor($avatarData['hair'] ?? 'brown');
        $eyeColor = $this->getEyeColor($avatarData['eyes'] ?? 'brown');
        $hairStyle = $this->getHairStyle($avatarData['hair'] ?? 'short');
        $gender = $avatarData['gender'] ?? 'female';
        
        $svg = '<svg viewBox="0 0 300 300" xmlns="http://www.w3.org/2000/svg">';
        
        // Background with modern gradient
        $svg .= '<defs>';
        $svg .= '<linearGradient id="bgGrad" x1="0%" y1="0%" x2="100%" y2="100%">';
        $svg .= '<stop offset="0%" style="stop-color:#667eea;stop-opacity:1" />';
        $svg .= '<stop offset="100%" style="stop-color:#764ba2;stop-opacity:1" />';
        $svg .= '</linearGradient>';
        $svg .= '<radialGradient id="faceGrad" cx="50%" cy="40%">';
        $svg .= '<stop offset="0%" style="stop-color:' . $this->lightenColor($skinTone, 0.2) . ';stop-opacity:1" />';
        $svg .= '<stop offset="100%" style="stop-color:' . $skinTone . ';stop-opacity:1" />';
        $svg .= '</radialGradient>';
        $svg .= '</defs>';
        
        // Background circle
        $svg .= '<circle cx="150" cy="150" r="145" fill="url(#bgGrad)"/>';
        
        // Face shape - more modern and proportional
        $faceWidth = $gender === 'male' ? 70 : 65;
        $faceHeight = $gender === 'male' ? 85 : 80;
        $svg .= '<ellipse cx="150" cy="160" rx="' . $faceWidth . '" ry="' . $faceHeight . '" fill="url(#faceGrad)" stroke="none"/>';
        
        // Hair - more detailed and stylish
        $svg .= $this->generateModernHair($hairStyle, $hairColor, $gender);
        
        // Eyes - much more expressive and detailed
        $svg .= $this->generateModernEyes($eyeColor, $gender);
        
        // Eyebrows - more defined
        $svg .= $this->generateEyebrows($hairColor, $gender);
        
        // Nose - subtle and well-proportioned
        $svg .= $this->generateNose($skinTone);
        
        // Mouth - more realistic smile
        $svg .= $this->generateMouth($gender);
        
        // Cheeks and facial features
        $svg .= $this->generateFacialFeatures($skinTone);
        
        // Accessories
        $svg .= $this->generateModernAccessories($avatarData);
        
        $svg .= '</svg>';
        
        return $svg;
    }
    
    /**
     * Generate cartoon-style hair
     */
    private function generateCartoonHair($style, $color)
    {
        $hair = '<g id="hair">';
        
        switch($style) {
            case 'curly':
                $hair .= '<path d="M 60 85 Q 70 70 85 75 Q 100 65 115 75 Q 130 70 140 85 Q 135 95 125 90 Q 115 85 100 90 Q 85 85 75 90 Q 65 95 60 85" fill="' . $color . '" stroke="#000" stroke-width="1"/>';
                // Curly details
                $hair .= '<circle cx="75" cy="80" r="6" fill="' . $color . '" stroke="#000" stroke-width="0.5"/>';
                $hair .= '<circle cx="100" cy="75" r="7" fill="' . $color . '" stroke="#000" stroke-width="0.5"/>';
                $hair .= '<circle cx="125" cy="80" r="6" fill="' . $color . '" stroke="#000" stroke-width="0.5"/>';
                break;
                
            case 'long':
                $hair .= '<path d="M 65 80 Q 75 65 100 70 Q 125 65 135 80 L 140 120 Q 135 125 130 120 L 125 90 Q 115 85 100 90 Q 85 85 75 90 L 70 120 Q 65 125 60 120 Z" fill="' . $color . '" stroke="#000" stroke-width="1"/>';
                break;
                
            case 'short':
            default:
                $hair .= '<path d="M 65 90 Q 75 70 100 75 Q 125 70 135 90 Q 130 95 125 90 Q 115 85 100 90 Q 85 85 75 90 Q 70 95 65 90" fill="' . $color . '" stroke="#000" stroke-width="1"/>';
                break;
        }
        
        $hair .= '</g>';
        return $hair;
    }
    
    /**
     * Generate cartoon-style eyes
     */
    private function generateCartoonEyes($eyeColor)
    {
        $eyes = '<g id="eyes">';
        
        // Left eye
        $eyes .= '<ellipse cx="85" cy="105" rx="12" ry="15" fill="white" stroke="#000" stroke-width="1.5"/>';
        $eyes .= '<circle cx="85" cy="105" r="8" fill="' . $eyeColor . '"/>';
        $eyes .= '<circle cx="87" cy="103" r="3" fill="#000"/>';
        $eyes .= '<circle cx="88" cy="101" r="1.5" fill="white"/>';
        
        // Right eye
        $eyes .= '<ellipse cx="115" cy="105" rx="12" ry="15" fill="white" stroke="#000" stroke-width="1.5"/>';
        $eyes .= '<circle cx="115" cy="105" r="8" fill="' . $eyeColor . '"/>';
        $eyes .= '<circle cx="113" cy="103" r="3" fill="#000"/>';
        $eyes .= '<circle cx="112" cy="101" r="1.5" fill="white"/>';
        
        // Eyebrows
        $eyes .= '<path d="M 75 95 Q 85 90 95 95" stroke="#000" stroke-width="2" fill="none" stroke-linecap="round"/>';
        $eyes .= '<path d="M 105 95 Q 115 90 125 95" stroke="#000" stroke-width="2" fill="none" stroke-linecap="round"/>';
        
        $eyes .= '</g>';
        return $eyes;
    }
    
    /**
     * Generate accessories
     */
    private function generateAccessories($avatarData)
    {
        $accessories = '<g id="accessories">';
        
        // Random accessories
        $accessoryType = rand(1, 4);
        
        switch($accessoryType) {
            case 1: // Glasses
                $accessories .= '<rect x="70" y="100" width="60" height="20" rx="10" fill="none" stroke="#333" stroke-width="2"/>';
                $accessories .= '<circle cx="85" cy="110" r="15" fill="none" stroke="#333" stroke-width="2"/>';
                $accessories .= '<circle cx="115" cy="110" r="15" fill="none" stroke="#333" stroke-width="2"/>';
                break;
                
            case 2: // Hat
                $accessories .= '<ellipse cx="100" cy="75" rx="40" ry="15" fill="#4a90e2"/>';
                $accessories .= '<rect x="75" y="65" width="50" height="20" rx="5" fill="#4a90e2" stroke="#333" stroke-width="1"/>';
                break;
                
            case 3: // Earrings
                $accessories .= '<circle cx="65" cy="115" r="3" fill="#ffd700" stroke="#333" stroke-width="0.5"/>';
                $accessories .= '<circle cx="135" cy="115" r="3" fill="#ffd700" stroke="#333" stroke-width="0.5"/>';
                break;
                
            default: // No accessory
                break;
        }
        
        $accessories .= '</g>';
        return $accessories;
    }
    
    /**
     * Get skin tone colors
     */
    private function getSkinToneColor($skinType)
    {
        $skinTones = [
            'fair' => '#fdbcb4',
            'light' => '#edb98a',
            'medium' => '#d08b5b',
            'olive' => '#ae7242',
            'tan' => '#9d6e2e',
            'dark' => '#8d5524',
            'deep' => '#654321'
        ];
        
        return $skinTones[$skinType] ?? $skinTones['medium'];
    }
    
    /**
     * Get hair colors
     */
    private function getHairColor($hairType)
    {
        $hairColors = [
            'blonde' => '#faf0be',
            'brown' => '#8b4513',
            'black' => '#2c1b18',
            'red' => '#cc4125',
            'auburn' => '#a52a2a',
            'gray' => '#808080',
            'white' => '#f5f5f5'
        ];
        
        // Extract color from hair description
        foreach($hairColors as $color => $hex) {
            if(stripos($hairType, $color) !== false) {
                return $hex;
            }
        }
        
        return $hairColors['brown']; // default
    }
    
    /**
     * Get eye colors
     */
    private function getEyeColor($eyeType)
    {
        $eyeColors = [
            'brown' => '#8b4513',
            'blue' => '#4169e1',
            'green' => '#228b22',
            'hazel' => '#8e7618',
            'gray' => '#708090',
            'amber' => '#ffbf00'
        ];
        
        foreach($eyeColors as $color => $hex) {
            if(stripos($eyeType, $color) !== false) {
                return $hex;
            }
        }
        
        return $eyeColors['brown']; // default
    }
    
    /**
     * Get hair style from description
     */
    private function getHairStyle($hairDescription)
    {
        if(stripos($hairDescription, 'curly') !== false) return 'curly';
        if(stripos($hairDescription, 'long') !== false) return 'long';
        return 'short'; // default
    }
    
    /**
     * Generate minimal style avatar
     */
    private function generateMinimalAvatar($avatarData)
    {
        // Simplified, clean design
        $skinTone = $this->getSkinToneColor($avatarData['skin'] ?? 'medium');
        
        $svg = '<svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">';
        $svg .= '<circle cx="100" cy="100" r="95" fill="#f8f9fa"/>';
        $svg .= '<circle cx="100" cy="110" r="40" fill="' . $skinTone . '"/>';
        $svg .= '<circle cx="90" cy="105" r="3" fill="#333"/>';
        $svg .= '<circle cx="110" cy="105" r="3" fill="#333"/>';
        $svg .= '<path d="M 95 120 Q 100 125 105 120" stroke="#333" stroke-width="2" fill="none"/>';
        $svg .= '</svg>';
        
        return $svg;
    }
    
    /**
     * Generate detailed style avatar
     */
    private function generateDetailedAvatar($avatarData)
    {
        // More realistic with shadows and details
        return $this->generateCartoonAvatar($avatarData); // For now, use cartoon as base
    }
    
    /**
     * Generate modern hair styles
     */
    private function generateModernHair($style, $color, $gender)
    {
        $hair = '<g id="modernHair">';
        
        switch($style) {
            case 'curly':
                if ($gender === 'male') {
                    $hair .= '<path d="M 90 120 Q 80 100 90 90 Q 100 80 120 85 Q 140 80 150 90 Q 160 85 170 95 Q 180 100 170 115 Q 160 120 150 115 Q 140 110 130 115 Q 120 110 110 115 Q 100 120 90 120" fill="' . $color . '" stroke="#000" stroke-width="1.5"/>';
                    // Curly texture
                    for ($i = 0; $i < 8; $i++) {
                        $x = 95 + ($i * 8);
                        $y = 95 + rand(-5, 5);
                        $hair .= '<circle cx="' . $x . '" cy="' . $y . '" r="4" fill="' . $color . '" stroke="#000" stroke-width="0.5"/>';
                    }
                } else {
                    $hair .= '<path d="M 80 130 Q 70 110 80 95 Q 90 80 110 85 Q 130 75 150 85 Q 170 75 190 85 Q 210 80 220 95 Q 230 110 220 130 Q 210 140 200 135 Q 190 130 180 135 Q 170 140 160 135 Q 150 130 140 135 Q 130 140 120 135 Q 110 130 100 135 Q 90 140 80 130" fill="' . $color . '" stroke="#000" stroke-width="1.5"/>';
                    // Long curly details
                    for ($i = 0; $i < 12; $i++) {
                        $x = 85 + ($i * 10);
                        $y = 100 + rand(-8, 8);
                        $hair .= '<ellipse cx="' . $x . '" cy="' . $y . '" rx="3" ry="6" fill="' . $color . '" stroke="#000" stroke-width="0.3"/>';
                    }
                }
                break;
                
            case 'long':
                $hair .= '<path d="M 85 140 Q 75 120 85 100 Q 95 85 115 90 Q 135 80 150 90 Q 165 80 185 90 Q 205 85 215 100 Q 225 120 215 140 L 220 180 Q 215 185 210 180 L 205 150 Q 195 145 185 150 Q 175 155 165 150 Q 155 145 145 150 Q 135 155 125 150 Q 115 145 105 150 L 90 180 Q 85 185 80 180 Z" fill="' . $color . '" stroke="#000" stroke-width="1.5"/>';
                // Hair strands
                $hair .= '<path d="M 100 90 Q 105 120 100 150" stroke="' . $this->darkenColor($color, 0.2) . '" stroke-width="1" fill="none"/>';
                $hair .= '<path d="M 150 90 Q 155 120 150 150" stroke="' . $this->darkenColor($color, 0.2) . '" stroke-width="1" fill="none"/>';
                $hair .= '<path d="M 200 90 Q 195 120 200 150" stroke="' . $this->darkenColor($color, 0.2) . '" stroke-width="1" fill="none"/>';
                break;
                
            case 'short':
            default:
                if ($gender === 'male') {
                    $hair .= '<path d="M 95 130 Q 85 115 95 105 Q 105 95 125 100 Q 145 95 165 100 Q 185 95 195 105 Q 205 115 195 130 Q 185 135 175 130 Q 165 125 155 130 Q 145 135 135 130 Q 125 125 115 130 Q 105 135 95 130" fill="' . $color . '" stroke="#000" stroke-width="1.5"/>';
                } else {
                    $hair .= '<path d="M 90 135 Q 80 120 90 110 Q 100 100 120 105 Q 140 95 150 105 Q 160 95 180 105 Q 200 100 210 110 Q 220 120 210 135 Q 200 140 190 135 Q 180 130 170 135 Q 160 140 150 135 Q 140 130 130 135 Q 120 140 110 135 Q 100 130 90 135" fill="' . $color . '" stroke="#000" stroke-width="1.5"/>';
                }
                break;
        }
        
        $hair .= '</g>';
        return $hair;
    }
    
    /**
     * Generate modern expressive eyes
     */
    private function generateModernEyes($eyeColor, $gender)
    {
        $eyes = '<g id="modernEyes">';
        
        // Left eye
        $eyes .= '<ellipse cx="125" cy="145" rx="18" ry="22" fill="white" stroke="#000" stroke-width="2"/>';
        $eyes .= '<circle cx="125" cy="145" r="12" fill="' . $eyeColor . '"/>';
        $eyes .= '<circle cx="127" cy="142" r="5" fill="#000"/>';
        $eyes .= '<circle cx="129" cy="140" r="2" fill="white"/>';
        $eyes .= '<ellipse cx="125" cy="150" rx="8" ry="3" fill="' . $this->darkenColor($eyeColor, 0.3) . '" opacity="0.3"/>';
        
        // Right eye
        $eyes .= '<ellipse cx="175" cy="145" rx="18" ry="22" fill="white" stroke="#000" stroke-width="2"/>';
        $eyes .= '<circle cx="175" cy="145" r="12" fill="' . $eyeColor . '"/>';
        $eyes .= '<circle cx="173" cy="142" r="5" fill="#000"/>';
        $eyes .= '<circle cx="171" cy="140" r="2" fill="white"/>';
        $eyes .= '<ellipse cx="175" cy="150" rx="8" ry="3" fill="' . $this->darkenColor($eyeColor, 0.3) . '" opacity="0.3"/>';
        
        // Eyelashes for female
        if ($gender === 'female') {
            $eyes .= '<path d="M 110 135 Q 115 130 120 135" stroke="#000" stroke-width="1.5" fill="none"/>';
            $eyes .= '<path d="M 125 132 Q 125 127 125 132" stroke="#000" stroke-width="1.5" fill="none"/>';
            $eyes .= '<path d="M 130 135 Q 135 130 140 135" stroke="#000" stroke-width="1.5" fill="none"/>';
            
            $eyes .= '<path d="M 160 135 Q 165 130 170 135" stroke="#000" stroke-width="1.5" fill="none"/>';
            $eyes .= '<path d="M 175 132 Q 175 127 175 132" stroke="#000" stroke-width="1.5" fill="none"/>';
            $eyes .= '<path d="M 180 135 Q 185 130 190 135" stroke="#000" stroke-width="1.5" fill="none"/>';
        }
        
        $eyes .= '</g>';
        return $eyes;
    }
    
    /**
     * Generate eyebrows
     */
    private function generateEyebrows($hairColor, $gender)
    {
        $eyebrows = '<g id="eyebrows">';
        
        if ($gender === 'male') {
            $eyebrows .= '<path d="M 110 130 Q 125 125 140 130" stroke="' . $hairColor . '" stroke-width="3" fill="none" stroke-linecap="round"/>';
            $eyebrows .= '<path d="M 160 130 Q 175 125 190 130" stroke="' . $hairColor . '" stroke-width="3" fill="none" stroke-linecap="round"/>';
        } else {
            $eyebrows .= '<path d="M 112 132 Q 125 128 138 132" stroke="' . $hairColor . '" stroke-width="2.5" fill="none" stroke-linecap="round"/>';
            $eyebrows .= '<path d="M 162 132 Q 175 128 188 132" stroke="' . $hairColor . '" stroke-width="2.5" fill="none" stroke-linecap="round"/>';
        }
        
        $eyebrows .= '</g>';
        return $eyebrows;
    }
    
    /**
     * Generate nose
     */
    private function generateNose($skinTone)
    {
        $nose = '<g id="nose">';
        $nose .= '<ellipse cx="150" cy="165" rx="4" ry="8" fill="' . $this->darkenColor($skinTone, 0.15) . '" opacity="0.6"/>';
        $nose .= '<ellipse cx="148" cy="168" rx="1.5" ry="2" fill="' . $this->darkenColor($skinTone, 0.25) . '" opacity="0.4"/>';
        $nose .= '<ellipse cx="152" cy="168" rx="1.5" ry="2" fill="' . $this->darkenColor($skinTone, 0.25) . '" opacity="0.4"/>';
        $nose .= '</g>';
        return $nose;
    }
    
    /**
     * Generate mouth
     */
    private function generateMouth($gender)
    {
        $mouth = '<g id="mouth">';
        
        if ($gender === 'male') {
            $mouth .= '<path d="M 135 185 Q 150 195 165 185" stroke="#d63384" stroke-width="3" fill="none" stroke-linecap="round"/>';
        } else {
            $mouth .= '<path d="M 135 185 Q 150 195 165 185" stroke="#e91e63" stroke-width="3.5" fill="none" stroke-linecap="round"/>';
            // Lipstick effect
            $mouth .= '<ellipse cx="150" cy="188" rx="12" ry="4" fill="#e91e63" opacity="0.3"/>';
        }
        
        $mouth .= '</g>';
        return $mouth;
    }
    
    /**
     * Generate facial features
     */
    private function generateFacialFeatures($skinTone)
    {
        $features = '<g id="facialFeatures">';
        
        // Cheeks
        $features .= '<circle cx="110" cy="175" r="12" fill="#ffb3d1" opacity="0.4"/>';
        $features .= '<circle cx="190" cy="175" r="12" fill="#ffb3d1" opacity="0.4"/>';
        
        // Chin highlight
        $features .= '<ellipse cx="150" cy="210" rx="15" ry="8" fill="' . $this->lightenColor($skinTone, 0.1) . '" opacity="0.3"/>';
        
        $features .= '</g>';
        return $features;
    }
    
    /**
     * Generate modern accessories
     */
    private function generateModernAccessories($avatarData)
    {
        $accessories = '<g id="modernAccessories">';
        
        // Random accessories with better designs
        $accessoryType = rand(1, 5);
        
        switch($accessoryType) {
            case 1: // Modern glasses
                $accessories .= '<rect x="105" y="140" width="90" height="25" rx="12" fill="none" stroke="#2c3e50" stroke-width="3"/>';
                $accessories .= '<circle cx="125" cy="152" r="20" fill="rgba(255,255,255,0.1)" stroke="#2c3e50" stroke-width="3"/>';
                $accessories .= '<circle cx="175" cy="152" r="20" fill="rgba(255,255,255,0.1)" stroke="#2c3e50" stroke-width="3"/>';
                $accessories .= '<path d="M 145 152 L 155 152" stroke="#2c3e50" stroke-width="3"/>';
                break;
                
            case 2: // Stylish hat
                $accessories .= '<ellipse cx="150" cy="110" rx="60" ry="20" fill="#3498db"/>';
                $accessories .= '<rect x="100" y="90" width="100" height="30" rx="8" fill="#3498db" stroke="#2c3e50" stroke-width="2"/>';
                $accessories .= '<rect x="110" y="95" width="80" height="5" fill="#2980b9"/>';
                break;
                
            case 3: // Earrings
                $accessories .= '<circle cx="85" cy="165" r="4" fill="#f1c40f" stroke="#f39c12" stroke-width="1"/>';
                $accessories .= '<circle cx="215" cy="165" r="4" fill="#f1c40f" stroke="#f39c12" stroke-width="1"/>';
                $accessories .= '<ellipse cx="85" cy="172" rx="2" ry="4" fill="#f1c40f"/>';
                $accessories .= '<ellipse cx="215" cy="172" rx="2" ry="4" fill="#f1c40f"/>';
                break;
                
            case 4: // Necklace
                $accessories .= '<ellipse cx="150" cy="240" rx="40" ry="8" fill="none" stroke="#e74c3c" stroke-width="3"/>';
                $accessories .= '<circle cx="150" cy="245" r="5" fill="#e74c3c"/>';
                break;
                
            default: // No accessory
                break;
        }
        
        $accessories .= '</g>';
        return $accessories;
    }

    /**
     * Generate artistic style avatar
     */
    private function generateArtisticAvatar($avatarData)
    {
        // Modern abstract/artistic interpretation
        $colors = ['#ff6b6b', '#4ecdc4', '#45b7d1', '#96ceb4', '#ffeaa7', '#a29bfe', '#fd79a8'];
        $mainColor = $colors[array_rand($colors)];
        $accentColor = $colors[array_rand($colors)];
        
        $svg = '<svg viewBox="0 0 300 300" xmlns="http://www.w3.org/2000/svg">';
        
        // Artistic background
        $svg .= '<defs>';
        $svg .= '<linearGradient id="artGrad" x1="0%" y1="0%" x2="100%" y2="100%">';
        $svg .= '<stop offset="0%" style="stop-color:' . $mainColor . ';stop-opacity:0.8" />';
        $svg .= '<stop offset="100%" style="stop-color:' . $accentColor . ';stop-opacity:0.8" />';
        $svg .= '</linearGradient>';
        $svg .= '</defs>';
        
        $svg .= '<circle cx="150" cy="150" r="145" fill="url(#artGrad)"/>';
        
        // Abstract face
        $svg .= '<polygon points="150,100 200,180 100,180" fill="white" opacity="0.9" stroke="' . $mainColor . '" stroke-width="3"/>';
        
        // Stylized eyes
        $svg .= '<circle cx="130" cy="140" r="8" fill="' . $accentColor . '"/>';
        $svg .= '<circle cx="170" cy="140" r="8" fill="' . $accentColor . '"/>';
        $svg .= '<circle cx="130" cy="140" r="4" fill="white"/>';
        $svg .= '<circle cx="170" cy="140" r="4" fill="white"/>';
        
        // Abstract smile
        $svg .= '<path d="M 130 160 Q 150 175 170 160" stroke="' . $mainColor . '" stroke-width="4" fill="none" stroke-linecap="round"/>';
        
        // Decorative elements
        $svg .= '<circle cx="100" cy="100" r="15" fill="' . $accentColor . '" opacity="0.6"/>';
        $svg .= '<circle cx="200" cy="100" r="12" fill="' . $mainColor . '" opacity="0.6"/>';
        $svg .= '<polygon points="80,200 90,220 70,220" fill="' . $accentColor . '" opacity="0.7"/>';
        
        $svg .= '</svg>';
        
        return $svg;
    }
}
