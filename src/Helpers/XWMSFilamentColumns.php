<?php

namespace XWMS\Package\Helpers;

use Filament\Tables\Columns\TextColumn;
use Carbon\Carbon;

class XWMSFilamentColumns
{
    public static function expiresAt(string $name = 'expires_at'): TextColumn
    {
        return static::date(
            $name,
            label: 'Expires At',
            falseLabel: 'Infinite',
            falseColor: 'success',
            trueColor: 'warning',
        );
    }

    public static function date(
        string $name,
        string $label,
        string $falseLabel = 'Not Set',
        string $falseColor = 'danger',
        string $trueColor = 'success',
        string $dateFormat = 'j F Y \a\t H:i',
    ): TextColumn {
        return TextColumn::make($name)
            ->label($label)
            ->badge()
            ->color(fn ($state) => static::resolveColor($state, $falseColor, $trueColor))
            ->formatStateUsing(fn ($state) => static::formatDateState($state, $falseLabel, $dateFormat))
            ->default(false);
    }

    public static function dateInfo(
        string $name,
        string $label,
        string $falseLabel = 'Not Set',
        string $falseColor = 'gray',
        string $trueColor = 'info',
        string $dateFormat = 'j F Y \a\t H:i',
    ): TextColumn {
        return static::date(
            $name,
            $label,
            $falseLabel,
            $falseColor,
            $trueColor,
            $dateFormat,
        );
    }

    public static function badge(
        string $name,
        string $label,
        array $colors = ['default' => 'gray'],
        string $falseLabel = 'Not Set',
        string $falseColor = 'gray',
    ): TextColumn {
        return TextColumn::make($name)
            ->label($label)
            ->badge()
            ->color(fn ($state) => static::resolveBadgeColor($state, $colors, $falseColor))
            ->formatStateUsing(fn ($state) => static::formatBadgeState($state, $falseLabel))
            ->default(false);
    }

    // --- private helper methods ---

    private static function resolveColor(mixed $state, string $falseColor, string $trueColor): string
    {
        return $state === false ? $falseColor : $trueColor;
    }

    private static function formatDateState(mixed $state, string $falseLabel, string $dateFormat): string
    {
        if ($state === false) {
            return $falseLabel;
        }

        if ($state instanceof \DateTimeInterface) {
            return $state->format($dateFormat);
        }

        return (string)$state;
    }

    private static function resolveBadgeColor(mixed $state, array $colors, string $falseColor): string
    {
        if ($state === false) {
            return $falseColor;
        }

        return $colors[$state] ?? 'primary';
    }

    private static function formatBadgeState(mixed $state, string $falseLabel): string
    {
        return $state === false ? $falseLabel : (string)$state;
    }
}