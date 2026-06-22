<?php

namespace App\Enum;

enum LabAI: string
{
    case GEMINI = 'gemini';
    case OPENAI = 'openai';
    case ANTHROPIC = 'anthropic';
}