<?php

namespace App\Http\Helper;

use Faker\Factory;
use REAZON\PWdocx\Facades\PWdocx;
use Illuminate\Support\Collection;

class TemplateDocument
{
    public static function generateTestData(array $templateData)
    {
        foreach ($templateData as $value) {
            if ($value['type'] == 'normal') {
                $data[] = [
                    'type' => $value['type'],
                    'input' => $value['input'],
                    'placeholder' => $value['placeholder'],
                    'value' => self::generateValue($value['input']),
                ];
            } else if ($value['type'] == 'block') {
                $data[] = [
                    'type' => $value['type'],
                    'placeholder' => $value['placeholder'],
                    'loop' => collect(array_fill(0, 5, 1))->map(function () use ($value) {
                        return collect($value['loop'])->map(function ($item) {
                            return [
                                'input' => $item['input'],
                                'placeholder' => $item['placeholder'],
                                'value' => self::generateValue($item['input']),
                            ];
                        })->toArray();
                    })->toArray(),
                ];
            } else if ($value['type'] == 'row') {
                $data[] = [
                    'type' => $value['type'],
                    'row' => collect(array_fill(0, 5, 1))->map(function () use ($value) {
                        return collect($value['row'])->map(function ($item) {
                            return [
                                'input' => $item['input'],
                                'placeholder' => $item['placeholder'],
                                'value' => self::generateValue($item['input']),
                            ];
                        })->toArray();
                    })->toArray(),
                ];
            }
        }

        return $data;
    }

    public static function download(string $template, array|Collection $data, ?string $fileName = null)
    {
        $data = $data instanceof Collection ? $data : collect($data);
        $docx = PWdocx::from($template);

        $funcReplace = function ($data) {
            $temp = [];
            foreach ($data as $item) {
                $temp[$item['placeholder']] = $item['value'];

                switch ($item['input']) {
                    case 'number':
                    case 'rupiah':
                        $temp["rupiah@{$item['placeholder']}"] = rupiah($item['value'], '');
                        $temp["terbilang@{$item['placeholder']}"] = terbilang($item['value']);
                        break;
                    case 'date':
                        $temp["hari@{$item['placeholder']}"] = printDay($item['value']);
                        $temp["tanggal@{$item['placeholder']}"] = printDate($item['value']);
                        $temp["tanggal1@{$item['placeholder']}"] = printDateOnly1($item['value']);
                        $temp["tanggal2@{$item['placeholder']}"] = printDateOnly($item['value']);
                        $temp["tanggal_hari@{$item['placeholder']}"] = printDateDay($item['value']);
                        $temp["bulan@{$item['placeholder']}"] = printMonthFull($item['value']);
                        $temp["bulan1@{$item['placeholder']}"] = printMonth1($item['value']);
                        $temp["bulan2@{$item['placeholder']}"] = printMonth($item['value']);
                        $temp["bulan_pendek@{$item['placeholder']}"] = printMonthShort($item['value']);
                        $temp["bulan_romawi@{$item['placeholder']}"] = printMonthRome($item['value']);
                        $temp["tahun@{$item['placeholder']}"] = printYear($item['value']);
                        $temp["tahun2@{$item['placeholder']}"] = printYear2($item['value']);
                        break;
                }
            }

            $temp2 = [];
            foreach ($temp as $key => $value) {
                $temp2[$key] = $value;
                $temp2["lower@{$key}"] = strtolower($value);
                $temp2["upper@{$key}"] = strtoupper($value);
            }

            return $temp2;
        };

        $dataNormal = $data->where('type', 'normal')->values();
        $replaceNormal = $funcReplace($dataNormal);
        $docx->setValues($replaceNormal);

        $dataBlock = $data->where('type', 'block')->values();
        foreach ($dataBlock as $item) {
            $replaceBlock = [];
            foreach ($item['loop'] as $loop) {
                $replaceBlock[] = $funcReplace($loop);
            }
            $docx->setCloneBlockAndSetValues($item['placeholder'], $replaceBlock);
        }

        $dataRow = $data->where('type', 'row')->values();
        foreach ($dataRow as $item) {
            $replaceRow = [];
            foreach ($item['row'] as $row) {
                $replaceRow[] = $funcReplace($row);
            }
            $docx->setCloneRowAndSetValues($item['row'][0][0]['placeholder'], $replaceRow);
        }

        return $docx->download($fileName);
    }

    public static function generateValue($type)
    {
        $faker = Factory::create();

        switch ($type) {
            case 'text':
                return $faker->name();
                break;
            case 'textarea':
                return $faker->sentence(10);
                break;
            case 'number':
                return rand(1, 99);
                break;
            case 'date':
                $rand = rand(-100, 100);
                return date('Y-m-d', strtotime("today {$rand} days"));
                break;
            case 'rupiah':
                $rand = rand(1, 1000);
                return $rand * 1000;
                break;
            default:
                return '';
                break;
        }
    }
}
