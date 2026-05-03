<?php

/**
 * Central definition for resident document / request forms.
 *
 * - Certificates: existing official outputs; `print_template` points to Blade under documents/templates/.
 * - Forms (form_category_*): placeholders until official DOCX/PDF layouts are provided.
 *
 * To add a category: append a key under `categories`, optional `subtypes`, and `fields_by_subtype`.
 * To add a printable template: create resources/views/documents/categories/{category_key}/{subtype_key}.blade.php
 *   (or default.blade.php as fallback).
 */
return [
    'categories' => [
        'barangay_clearance' => [
            'label' => 'Barangay Clearance',
            'group' => 'certificates',
            'print_template' => 'documents.templates.barangay-clearance',
            'subtypes' => [],
            'fields_by_subtype' => [],
        ],
        'certificate_of_residency' => [
            'label' => 'Certificate of Residency',
            'group' => 'certificates',
            'print_template' => 'documents.templates.certificate-of-residency',
            'subtypes' => [],
            'fields_by_subtype' => [],
        ],
        'certificate_of_indigency' => [
            'label' => 'Certificate of Indigency',
            'group' => 'certificates',
            'print_template' => 'documents.templates.certificate-of-indigency',
            'subtypes' => [],
            'fields_by_subtype' => [],
        ],
        'barangay_id' => [
            'label' => 'Barangay ID',
            'group' => 'certificates',
            'print_template' => 'documents.templates.barangay-id',
            'subtypes' => [],
            'fields_by_subtype' => [],
        ],

        'form_category_1' => [
            'label' => 'Inquiry / report category 1 (official template pending)',
            'group' => 'forms',
            'subtypes' => [
                'choice_a' => 'Sub-type A (placeholder)',
                'choice_b' => 'Sub-type B (placeholder)',
            ],
            'fields_by_subtype' => [
                'choice_a' => [
                    ['name' => 'subject', 'type' => 'text', 'label' => 'Subject / reference', 'required' => false, 'max' => 200],
                ],
                'choice_b' => [
                    ['name' => 'subject', 'type' => 'text', 'label' => 'Subject / reference', 'required' => false, 'max' => 200],
                ],
            ],
        ],
        'form_category_2' => [
            'label' => 'Inquiry / report category 2 (official template pending)',
            'group' => 'forms',
            'subtypes' => [
                'choice_a' => 'Sub-type A (placeholder)',
                'choice_b' => 'Sub-type B (placeholder)',
                'choice_c' => 'Sub-type C (placeholder)',
            ],
            'fields_by_subtype' => [
                'choice_a' => [
                    ['name' => 'subject', 'type' => 'text', 'label' => 'Subject / reference', 'required' => false, 'max' => 200],
                ],
                'choice_b' => [
                    ['name' => 'subject', 'type' => 'text', 'label' => 'Subject / reference', 'required' => false, 'max' => 200],
                ],
                'choice_c' => [
                    ['name' => 'subject', 'type' => 'text', 'label' => 'Subject / reference', 'required' => false, 'max' => 200],
                ],
            ],
        ],
        'form_category_3' => [
            'label' => 'Inquiry / report category 3 (official template pending)',
            'group' => 'forms',
            'subtypes' => [
                'standard' => 'Standard (single option)',
            ],
            'fields_by_subtype' => [
                'standard' => [
                    ['name' => 'details', 'type' => 'textarea', 'label' => 'Details (optional)', 'required' => false, 'max' => 1000],
                ],
            ],
        ],
        'form_category_4' => [
            'label' => 'Inquiry / report category 4 (official template pending)',
            'group' => 'forms',
            'subtypes' => [
                'choice_a' => 'Sub-type A (placeholder)',
                'choice_b' => 'Sub-type B (placeholder)',
            ],
            'fields_by_subtype' => [
                'choice_a' => [
                    ['name' => 'reference_no', 'type' => 'text', 'label' => 'Reference no. (optional)', 'required' => false, 'max' => 120],
                ],
                'choice_b' => [
                    ['name' => 'reference_no', 'type' => 'text', 'label' => 'Reference no. (optional)', 'required' => false, 'max' => 120],
                ],
            ],
        ],
        'form_category_5' => [
            'label' => 'Inquiry / report category 5 (official template pending)',
            'group' => 'forms',
            'subtypes' => [
                'choice_a' => 'Sub-type A (placeholder)',
                'choice_b' => 'Sub-type B (placeholder)',
            ],
            'fields_by_subtype' => [
                'choice_a' => [],
                'choice_b' => [],
            ],
        ],
        'form_category_6' => [
            'label' => 'Inquiry / report category 6 (official template pending)',
            'group' => 'forms',
            'subtypes' => [
                'standard' => 'Standard (single option)',
            ],
            'fields_by_subtype' => [
                'standard' => [],
            ],
        ],
        'form_category_7' => [
            'label' => 'Inquiry / report category 7 (official template pending)',
            'group' => 'forms',
            'subtypes' => [
                'choice_a' => 'Sub-type A (placeholder)',
                'choice_b' => 'Sub-type B (placeholder)',
            ],
            'fields_by_subtype' => [
                'choice_a' => [
                    ['name' => 'notes', 'type' => 'textarea', 'label' => 'Notes (optional)', 'required' => false, 'max' => 800],
                ],
                'choice_b' => [
                    ['name' => 'notes', 'type' => 'textarea', 'label' => 'Notes (optional)', 'required' => false, 'max' => 800],
                ],
            ],
        ],
        'form_category_8' => [
            'label' => 'Inquiry / report category 8 (official template pending)',
            'group' => 'forms',
            'subtypes' => [
                'choice_a' => 'Sub-type A (placeholder)',
                'choice_b' => 'Sub-type B (placeholder)',
                'choice_c' => 'Sub-type C (placeholder)',
            ],
            'fields_by_subtype' => [
                'choice_a' => [],
                'choice_b' => [],
                'choice_c' => [],
            ],
        ],
    ],
];
