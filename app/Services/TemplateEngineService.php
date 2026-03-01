<?php

namespace App\Services;

class TemplateEngineService
{
    /**
     * Replace placeholders in the template with actual data.
     *
     * @param string $templateContent
     * @param array $data
     * @return string
     */
    public function render(string $templateContent, array $data): string
    {
        foreach ($data as $key => $value) {
            // Replace {{key}} with $value
            $templateContent = str_replace('{{' . $key . '}}', $value ?? '', $templateContent);
            // Replace {{ key }} with $value
            $templateContent = str_replace('{{ ' . $key . ' }}', $value ?? '', $templateContent);
        }

        return $templateContent;
    }
}
