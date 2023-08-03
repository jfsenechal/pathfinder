<?php

namespace AfmLibre\Pathfinder\Ancestry;

enum AlignementEnum: string
{
    case ALIGN_LG = 'Lawful Good';
    case ALIGN_NG = 'Neutral Good';
    case ALIGN_CG = 'Chaotic Good';
    case ALIGN_LN = 'Lawful Neutral';
    case ALIGN_N = 'True Neutral';
    case ALIGN_CN = 'Chaotic Neutral';
    case ALIGN_LE = 'Lawful Evil';
    case ALIGN_NE = 'Neutral Evil';
    case ALIGN_CE = 'Chaotic Evil';
}
