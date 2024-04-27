<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Content\Infrastructure\Http;

use Illuminate\Foundation\Http\FormRequest;

final class CreateContentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'content_id' => 'required|integer|min:1',
            'parent_content_id' => 'required|integer|min:1',
            'author_id' => 'required|integer|min:1',
            'author_name' => 'required|string|min:3|max:50',
            'creation_date' => 'required|date_format:U',
            'edition_date' => 'required|date_format:U',
            'message' => 'required|string',
        ];
    }

    public function contentData(): ContentData
    {
        $validatedData = $this->validated();

        return new ContentData(
            content_id: $validatedData['content_id'],
            parent_content_id: $validatedData['parent_content_id'],
            author_id: $validatedData['author_id'],
            author_name: $validatedData['author_name'],
            creation_date: $validatedData['creation_date'],
            edition_date: $validatedData['edition_date'],
            message: $validatedData['message']
        );
    }
}
