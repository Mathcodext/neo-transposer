<?php

namespace NeoTransposer\Domain\ChordPrinter;

use NeoTransposer\Domain\NotesNotation;

/**
 * Notation for chords as in the French songbook.
 */
final class ChordPrinterFrench extends ChordPrinter
{
    protected $cssClass = 'chord-sans';

    /**
     * Return a chord with French/Latin notation.
     * 
     * @param  string $fundamental Chord's root note.
     * @param  string $attributes  Chord's type or attributes.
     * @return string              The final notation (HTML).
     */
    public function printChordInNotation($fundamental, $attributes)
    {
        if (!str_contains($attributes, 'dim'))
        {
            $attributes = str_replace(
                ['m', 'M'],
                ['m', 'aug'],
                $attributes
            );
        }

        $notesNotation = new NotesNotation();
        return $notesNotation->getNotation($fundamental, 'latin') . $attributes;
    }
}
