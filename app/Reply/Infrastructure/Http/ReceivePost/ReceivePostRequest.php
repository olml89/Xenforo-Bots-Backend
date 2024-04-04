<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Reply\Infrastructure\Http\ReceivePost;

use Illuminate\Foundation\Http\FormRequest;
use olml89\XenforoBotsBackend\Reply\Application\Create\Post\PostData;

final class ReceivePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'post_id' => ['required', 'integer'],
            'thread_id' => ['required', 'integer'],
            'author_id' => ['required', 'int'],
            'author_name' => ['required', 'string'],
            'create_date' => ['required', 'int', 'date_format:U'],
            'update_date' => ['required', 'int', 'date_format:U'],
            'message' => ['required', 'string'],
        ];
    }

    public function validated($key = null, $default = null): PostData
    {
        $validatedData = parent::validated($key, $default);

        return new PostData(
            post_id: $validatedData['post_id'],
            thread_id: $validatedData['thread_id'],
            author_id: $validatedData['author_id'],
            author_name: $validatedData['author_name'],
            create_date: $validatedData['create_date'],
            update_date: $validatedData['update_date'],
            message: $validatedData['message'],
        );
    }

}
