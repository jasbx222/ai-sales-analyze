<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Files;
use Laravel\Ai\Tools\Request;
use Stringable;

class PrepareConversationText implements Tool
{
    public function description(): Stringable|string
    {
        return 'Reads a chat screenshot image and extracts the visible conversation text in reading order.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'image_path' => $schema
                ->string()
                ->description('Storage path of the uploaded conversation screenshot.')
                ->required(),
        ];
    }

    public function handle(Request $request): Stringable|string
    {
        $imagePath = (string) ($request->image_path ?? '');

        if ($imagePath === '') {
            return json_encode([
                'success' => false,
                'error' => 'image_path is required.',
            ], JSON_UNESCAPED_UNICODE);
        }

        $response = ai()->agent()->prompt(
            'Extract all visible conversation text from the attached image in correct reading order. Do not guess unclear text. Return plain text only.',
            attachments: [
                Files\Image::fromStorage($imagePath),
            ]
        );

        return (string) $response;
    }
}