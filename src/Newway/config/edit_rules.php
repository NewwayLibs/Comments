<?php

return array(
    'content_type' => 'sometimes|required',
    'content_id' => 'sometimes|required',
    'user_name' => 'sometimes|required|min:3',
    'body' => 'sometimes|required',
    'user_email' => 'sometimes|email',
    'created_at' => 'sometimes|date',
);