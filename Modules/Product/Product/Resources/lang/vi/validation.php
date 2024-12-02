<?php

return [
    'required' => ':attribute là bắt buộc.',
    'max' => [
        'string' => ':attribute không được dài quá :max ký tự.',
        'file' => ':attribute không được lớn hơn :max kilobytes.',
    ],
    'numeric' => ':attribute phải là số.',
    'integer' => ':attribute phải là số nguyên.',
    'min' => [
        'numeric' => ':attribute phải lớn hơn :min.',
    ],
    'image' => ':attribute phải là hình ảnh.',
    'unique' => ':attribute đã tồn tại, vui lòng chọn giá trị khác.',

    'attributes' => [
        'name' => 'Tiêu đề sản phẩm',
        'slug' => 'Slug',
        'price' => 'Giá',
        'stock' => 'Tồn kho',
        'description' => 'Mô tả ngắn',
        'content' => 'Mô tả dài',
        'variant_id' => 'Loại thuộc tính',
        'sku' => 'SKU',
        'variant_image' => 'Ảnh biến thể',
        'main_image' => 'Ảnh chính',
        'seo_title' => 'Tiêu đề SEO',
        'seo_description' => 'Mô tả SEO',
        'seo_keywords' => 'Từ khóa SEO',
    ],
];
