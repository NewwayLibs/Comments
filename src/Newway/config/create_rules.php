<?php

return array(
    'content_type' => 'required',
    'content_id' => 'required',
    'user_name' => 'required|min:3',
    'body' => 'required',
    'created_at' => 'date',
);