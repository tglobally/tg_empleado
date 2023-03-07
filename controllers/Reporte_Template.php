<?php

namespace tglobally\tg_empleado\controllers;

use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class Reporte_Template
{
    const REPORTE_EMPLEADOS = [
        "A2" => [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('rgb' => '002060')
            ),
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ]
        ],
        "D2" => [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('rgb' => 'C00000')
            ),
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ]
        ],
        "D3:J3" => [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '000000'],
                'size' => 11,
            ],
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('rgb' => 'FFCC99')
            ),
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ]
        ],

        "A3:A9" => [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('rgb' => '0070C0')
            ),
            'alignment' => [
                'horizontal' => 'left',
                'vertical' => 'center',
            ],
            'borders' => [
                'inside' => [
                    'borderStyle' => Border::BORDER_DOTTED,
                    'color' => array('argb' => 'FFFFFFFF'),
                ]
            ]
        ],
        "B3:B9" => [
            'font' => [
                'color' => ['rgb' => '000000'],
                'size' => 11,
            ],
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('rgb' => 'F2F2F2')
            ),
            'alignment' => [
                'horizontal' => 'left',
                'vertical' => 'center',
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_DOTTED,
                    'color' => array('argb' => '000000'),
                ]
            ]
        ],
        "B7:B9" => [
            'numberFormat' => [
                'formatCode' => "\"$\"#.##,##",
            ],
        ],
        "B9" => [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '000000'],
                'size' => 11,
            ],
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('rgb' => 'FFFF00')
            ),
        ]

    ];

    const REPORTE_GENERAL = [];

}