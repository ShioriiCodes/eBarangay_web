<?php

namespace App\Support;

use App\Models\DocumentRequest;
use Illuminate\Support\Facades\View;

final class DocumentRequestCatalog
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public static function categories(): array
    {
        return config('document_request_forms.categories', []);
    }

    /**
     * @return list<string>
     */
    public static function allowedDocumentTypeKeys(): array
    {
        return array_keys(self::categories());
    }

    public static function definition(string $documentType): ?array
    {
        return self::categories()[$documentType] ?? null;
    }

    public static function labelFor(string $documentType): string
    {
        $def = self::definition($documentType);

        return is_array($def) ? (string) ($def['label'] ?? $documentType) : $documentType;
    }

    public static function subtypeLabelFor(string $documentType, ?string $subtypeKey): ?string
    {
        if ($subtypeKey === null || $subtypeKey === '') {
            return null;
        }

        $labels = self::subtypeLabels($documentType);

        return $labels[$subtypeKey] ?? str_replace('_', ' ', $subtypeKey);
    }

    /**
     * @return array<string, string>
     */
    public static function subtypeLabels(string $documentType): array
    {
        $def = self::definition($documentType);
        if (! is_array($def)) {
            return [];
        }

        $subtypes = $def['subtypes'] ?? [];

        return is_array($subtypes) ? $subtypes : [];
    }

    /**
     * @return list<array{name: string, type: string, label: string, required: bool, max: int}>
     */
    public static function fieldsFor(string $documentType, ?string $resolvedSubtype): array
    {
        $def = self::definition($documentType);
        if (! is_array($def) || $resolvedSubtype === null || $resolvedSubtype === '') {
            return [];
        }

        $bySubtype = $def['fields_by_subtype'] ?? [];
        if (! is_array($bySubtype) || ! isset($bySubtype[$resolvedSubtype]) || ! is_array($bySubtype[$resolvedSubtype])) {
            return [];
        }

        $out = [];
        foreach ($bySubtype[$resolvedSubtype] as $row) {
            if (! is_array($row) || ! isset($row['name'])) {
                continue;
            }
            $out[] = [
                'name' => (string) $row['name'],
                'type' => (string) ($row['type'] ?? 'text'),
                'label' => (string) ($row['label'] ?? $row['name']),
                'required' => ! empty($row['required']),
                'max' => (int) ($row['max'] ?? 500),
            ];
        }

        return $out;
    }

    /**
     * Whether the UI must show a subtype control (more than one option).
     */
    public static function needsSubtypePicker(string $documentType): bool
    {
        return count(self::subtypeLabels($documentType)) > 1;
    }

    /**
     * Canonical subtype for persistence, or null when the category has no subtypes.
     */
    public static function canonicalSubtype(string $documentType, ?string $requestSubtype): ?string
    {
        $labels = self::subtypeLabels($documentType);
        if ($labels === []) {
            return null;
        }

        if (count($labels) === 1) {
            return (string) array_key_first($labels);
        }

        $key = $requestSubtype !== null && $requestSubtype !== '' ? $requestSubtype : null;
        if ($key === null || ! array_key_exists($key, $labels)) {
            return null;
        }

        return $key;
    }

    public static function isValidSubtype(string $documentType, ?string $requestSubtype): bool
    {
        $labels = self::subtypeLabels($documentType);
        if ($labels === []) {
            return $requestSubtype === null || $requestSubtype === '';
        }

        if (count($labels) === 1) {
            $only = (string) array_key_first($labels);

            return $requestSubtype === null || $requestSubtype === '' || $requestSubtype === $only;
        }

        return $requestSubtype !== null
            && $requestSubtype !== ''
            && array_key_exists($requestSubtype, $labels);
    }

    public static function printTemplateView(string $documentType): ?string
    {
        $def = self::definition($documentType);
        if (! is_array($def)) {
            return null;
        }

        $tpl = $def['print_template'] ?? null;

        return is_string($tpl) && $tpl !== '' ? $tpl : null;
    }

    /**
     * Blade view for PDF/HTML, including legacy certificates and future per-subtype layouts.
     */
    public static function resolvePrintView(DocumentRequest $documentRequest): string
    {
        $certificate = self::printTemplateView($documentRequest->document_type);
        if ($certificate !== null && View::exists($certificate)) {
            return $certificate;
        }

        $category = $documentRequest->document_type;
        $subtype = $documentRequest->request_subtype;

        $candidates = [];
        if ($subtype !== null && $subtype !== '') {
            $candidates[] = "documents.categories.{$category}.{$subtype}";
        }
        $candidates[] = "documents.categories.{$category}.default";

        foreach ($candidates as $view) {
            if (View::exists($view)) {
                return $view;
            }
        }

        return 'documents.categories._placeholder.generic';
    }

    /**
     * Payload for resident create form (Alpine).
     *
     * @param  array<string, mixed>|null  $old
     * @return array{categories: list<array<string, mixed>>, old: array<string, mixed>}
     */
    public static function forFrontend(?array $old = null): array
    {
        $old = $old ?? [];

        $categories = [];
        foreach (self::categories() as $key => $def) {
            if (! is_array($def)) {
                continue;
            }
            $subtypes = [];
            foreach ($def['subtypes'] ?? [] as $sk => $slabel) {
                $subtypes[] = ['key' => (string) $sk, 'label' => (string) $slabel];
            }
            $fieldsBySubtype = [];
            foreach ($def['fields_by_subtype'] ?? [] as $sk => $rows) {
                if (! is_array($rows)) {
                    continue;
                }
                $parsed = [];
                foreach ($rows as $row) {
                    if (! is_array($row) || ! isset($row['name'])) {
                        continue;
                    }
                    $parsed[] = [
                        'name' => (string) $row['name'],
                        'type' => (string) ($row['type'] ?? 'text'),
                        'label' => (string) ($row['label'] ?? $row['name']),
                        'required' => ! empty($row['required']),
                        'max' => (int) ($row['max'] ?? 500),
                    ];
                }
                $fieldsBySubtype[(string) $sk] = $parsed;
            }
            $categories[] = [
                'key' => $key,
                'label' => (string) ($def['label'] ?? $key),
                'group' => (string) ($def['group'] ?? 'forms'),
                'subtypes' => $subtypes,
                'fieldsBySubtype' => $fieldsBySubtype,
            ];
        }

        return [
            'categories' => $categories,
            'old' => [
                'document_type' => (string) ($old['document_type'] ?? ''),
                'request_subtype' => (string) ($old['request_subtype'] ?? ''),
                'purpose' => (string) ($old['purpose'] ?? ''),
                'additional_details' => (string) ($old['additional_details'] ?? ''),
                'form_fields' => is_array($old['form_fields'] ?? null) ? $old['form_fields'] : [],
            ],
        ];
    }

    /**
     * @return array<string, string> document_type => label
     */
    public static function allTypeLabels(): array
    {
        $map = [];
        foreach (self::categories() as $key => $def) {
            $map[$key] = is_array($def) ? (string) ($def['label'] ?? $key) : $key;
        }

        return $map;
    }
}
