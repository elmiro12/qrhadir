<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventParticipant;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class IdCardService
{
    public function generate(EventParticipant $eventParticipant)
    {
        $event = $eventParticipant->event;
        $template = $event->idCardTemplate;

        if (!$template || !$template->is_active) {
            throw new \Exception("ID Card Template not found for this event.");
        }

        $templatePath = public_path('assets/images/templates/' . $event->id . '/' . $template->file_path);

        if (!file_exists($templatePath)) {
            throw new \Exception("Template file not found at: " . $templatePath);
        }

        // 1. Load Background
        $image = imagecreatefrompng($templatePath);
        if (!$image) {
            throw new \Exception("Failed to load background image.");
        }

        $width = imagesx($image);
        $height = imagesy($image);

        // 2. Add Event Name (Top)
        $eventName = strtoupper($eventParticipant->event->name);
        $black = imagecolorallocate($image, 30, 30, 30);
        $white = imagecolorallocate($image, 255, 255, 255);
        // Font settings
        $fontBoldPath = public_path('assets/fonts/Nunito-Bold.ttf');
        $fontMediumPath = public_path('assets/fonts/Nunito-Medium.ttf');

        if (file_exists($fontBoldPath) && file_exists($fontMediumPath)) {
            $maxWidth = $width - 100; // 50px padding on each side
            
            //Render App Name for Branding
            $type = strtoupper(setting('app_name') ?? 'QR-Hadir');
            $fontSize = 8;
            $bbox = imagettfbbox($fontSize, 0, $fontMediumPath, $type);
            $textWidth = $bbox[2] - $bbox[0];
            $x = ($width - $textWidth) / 2;
            imagettftext($image, $fontSize, 0, $x, 20, $white, $fontBoldPath, $type);
            

            // Render Event Name
            $fontSize = 16;
            $lineHeight = $fontSize * 1.5;
            $currentY = 100; // Default start Y position
            $eventLines = $this->wrapText($fontSize, 0, $fontBoldPath, $eventName, $maxWidth);
            // Render Event Logo if exists
            if ($eventParticipant->event->logo) {
                $logoPath = public_path('assets/images/event-logo/' . $eventParticipant->event->logo);
                if (file_exists($logoPath)) {
                    $logoImg = imagecreatefrompng($logoPath);
                    if ($logoImg) {
                        $logoW = imagesx($logoImg);
                        $logoH = imagesy($logoImg);
                        $targetLogoH = 40;
                        $targetLogoW = ($logoW / $logoH) * $targetLogoH;
                        
                        $logoX = (int)(($width - $targetLogoW) / 2);
                        $logoY = 35; // Position below branding
                        
                        imagecopyresampled($image, $logoImg, $logoX, $logoY, 0, 0, $targetLogoW, $targetLogoH, $logoW, $logoH);
                        imagedestroy($logoImg);
                        
                        // Shift event name down if logo exists
                        $currentY = 110;
                    }
                }
            }

            foreach ($eventLines as $line) {
                $bbox = imagettfbbox($fontSize, 0, $fontBoldPath, $line);
                $textWidth = $bbox[2] - $bbox[0];
                $x = ($width - $textWidth) / 2;
                imagettftext($image, $fontSize, 0, $x, $currentY, $white, $fontBoldPath, $line);
                $currentY += $lineHeight;
            }

            // Render Participant Name
            $participantName = $eventParticipant->participant->name;
            $fontSize = 14;
            $participantLines = $this->wrapText($fontSize, 0, $fontMediumPath, $participantName, $maxWidth);
            
            // Calculate total height for participant name and type to position them from bottom
            $lineHeight = $fontSize * 1.4;
            $typeFontSize = 12;
            $totalParticipantHeight = (count($participantLines) * $lineHeight) + ($typeFontSize * 2);
            $startY = $height - $totalParticipantHeight - 40;

            foreach ($participantLines as $line) {
                $bbox = imagettfbbox($fontSize, 0, $fontMediumPath, $line);
                $textWidth = $bbox[2] - $bbox[0];
                $x = ($width - $textWidth) / 2;
                imagettftext($image, $fontSize, 0, $x, $startY, $black, $fontMediumPath, $line);
                $startY += $lineHeight;
            }

            // Render Participant Type
            $type = strtoupper($eventParticipant->participantType->name ?? 'PESERTA');
            $orange = imagecolorallocate($image, 249, 115, 22);
            $fontSize = $typeFontSize;
            $bbox = imagettfbbox($fontSize, 0, $fontBoldPath, $type);
            $textWidth = $bbox[2] - $bbox[0];
            $x = ($width - $textWidth) / 2;
            imagettftext($image, $fontSize, 0, $x, $startY + 5, $orange, $fontBoldPath, $type);
            
            //Render App Url for Branding
            $type = env('APP_URL', setting('app_name'));
            $fontSize = 8;
            $bbox = imagettfbbox($fontSize, 0, $fontMediumPath, $type);
            $textWidth = $bbox[2] - $bbox[0];
            $x = ($width - $textWidth) / 2;
            imagettftext($image, $fontSize, 0, $x, $height - 20, $black, $fontBoldPath, $type);

        } else {
            // Fallback to basic GD fonts (ugly but works)
            $textWidth = strlen($eventName) * 9;
            imagestring($image, 5, ($width - $textWidth) / 2, 50, $eventName, $black);
            
            $textWidth = strlen($eventParticipant->participant->name) * 9;
            imagestring($image, 5, ($width - $textWidth) / 2, $height - 100, $eventParticipant->participant->name, $black);
        }

        // 3. Add QR Code (Center)
        $qrToken = $eventParticipant->qrToken->token;
        
        $qrSize = 220;
        $qrImageData = QrCode::format('png')
            ->size($qrSize)
            ->margin(1)
            ->backgroundColor(255, 255, 255)
            ->generate($qrToken);
        
        $qrImage = imagecreatefromstring($qrImageData);

        if ($qrImage) {
            // Convert to truecolor if indexed to handle alpha correctly
            imagepalettetotruecolor($qrImage);
            
            // Apply rounded corners to the QR Image
            $this->applyRoundedCorners($qrImage, 10); // 20px radius

            $qrX = (int)(($width - $qrSize) / 2);
            $qrY = (int)(($height - $qrSize) / 2);
            
            // Prepare template image for alpha blending
            imagepalettetotruecolor($image);
            imagealphablending($image, true);
            
            imagecopy($image, $qrImage, $qrX, $qrY-30, 0, 0, $qrSize, $qrSize);
            imagedestroy($qrImage);
        }

        // 4. Output as PNG string
        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        imagedestroy($image);

        return $imageData;
    }

    /**
     * Generate and save ID card to public directory
     */
    public function generateAndSave(EventParticipant $eventParticipant)
    {
        $imageData = $this->generate($eventParticipant);
        
        $directory = public_path('assets/images/generated-card/' . $eventParticipant->event_id);
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $fileName = "card_{$eventParticipant->event_id}_{$eventParticipant->qrToken->token}.png";
        $filePath = $directory . '/' . $fileName;
        
        File::put($filePath, $imageData);

        return [
            'file_name' => $fileName,
            'file_path' => $filePath,
            'public_path' => 'assets/images/generated-card/' . $eventParticipant->event_id . '/' . $fileName
        ];
    }

    /**
     * Helper to apply rounded corners (border-radius) to a GD image
     */
    private function applyRoundedCorners(&$source, $radius) {
        $width = imagesx($source);
        $height = imagesy($source);

        // Ensure source handles alpha transparency
        imagealphablending($source, false);
        imagesavealpha($source, true);

        // Create a mask image
        $mask = imagecreatetruecolor($width, $height);
        $black = imagecolorallocate($mask, 0, 0, 0);
        $white = imagecolorallocate($mask, 255, 255, 255);

        imagefill($mask, 0, 0, $black);
        
        // Draw circles for 4 corners with -1 offset for right/bottom bounds
        imagefilledellipse($mask, $radius, $radius, $radius * 2, $radius * 2, $white);
        imagefilledellipse($mask, $width - $radius - 1, $radius, $radius * 2, $radius * 2, $white);
        imagefilledellipse($mask, $radius, $height - $radius - 1, $radius * 2, $radius * 2, $white);
        imagefilledellipse($mask, $width - $radius - 1, $height - $radius - 1, $radius * 2, $radius * 2, $white);

        // Fill cross-section rectangles
        imagefilledrectangle($mask, $radius, 0, $width - $radius - 1, $height - 1, $white);
        imagefilledrectangle($mask, 0, $radius, $width - 1, $height - $radius - 1, $white);

        // Identify black mask areas and set them to full transparency in source
        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $color = imagecolorat($mask, $x, $y);
                $rgb = imagecolorsforindex($mask, $color);
                
                // If the mask pixel is black (or very close to it)
                if ($rgb['red'] < 128) {
                    $transparent = imagecolorallocatealpha($source, 0, 0, 0, 127);
                    imagesetpixel($source, $x, $y, $transparent);
                }
            }
        }
        imagedestroy($mask);
    }

    /**
     * Helper to wrap text into multiple lines
     */
    private function wrapText($fontSize, $angle, $fontPath, $text, $maxWidth)
    {
        $words = explode(' ', $text);
        $lines = [];
        $currentLine = '';

        foreach ($words as $word) {
            $testLine = $currentLine === '' ? $word : $currentLine . ' ' . $word;
            $bbox = imagettfbbox($fontSize, $angle, $fontPath, $testLine);
            $textWidth = $bbox[2] - $bbox[0];

            if ($textWidth <= $maxWidth) {
                $currentLine = $testLine;
            } else {
                if ($currentLine !== '') {
                    $lines[] = $currentLine;
                }
                $currentLine = $word;
            }
        }

        if ($currentLine !== '') {
            $lines[] = $currentLine;
        }

        return $lines;
    }
}
