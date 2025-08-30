<?php
//
//
// Parsedown
// http://parsedown.org
//
// (c) Emanuil Rusev
// http://erusev.com
//
// For the full license information, view the LICENSE file that was distributed
// with this source code.
//
//

class Parsedown
{
    # ~

    const version = '1.7.4';

    # ~

    function text($text)
    {
        # make sure no definitions are set
        $this->DefinitionData = array();

        # standardize line breaks
        $text = str_replace(array("\r\n", "\r"), "\n", $text);

        # remove UTF-8 BOM
        $text = trim($text, "\xEF\xBB\xBF");

        # pre-process text
        $text = $this->preProcessText($text);

        # split text into lines
        $lines = explode("\n", $text);

        # iterate through lines to identify blocks
        $this->BlockTypes = $this->blockTypes;
        $lines = $this->identifyBlocks($lines);

        # process identified blocks
        $lines = $this->processBlocks($lines);

        # join lines
        $text = implode("\n", $lines);

        # post-process text
        $text = $this->postProcessText($text);

        return $text;
    }

    protected function preProcessText($text)
    {
        return $text;
    }

    protected function processBlocks($lines)
    {
        return $this->iterateThroughLines($lines);
    }

    protected function postProcessText($text)
    {
        return $text;
    }

    #
    # Setters
    #

    function setBreaksEnabled($breaksEnabled)
    {
        $this->breaksEnabled = $breaksEnabled;

        return $this;
    }

    protected $breaksEnabled = false;

    function setMarkupEscaped($markupEscaped)
    {
        $this->markupEscaped = $markupEscaped;

        return $this;
    }

    protected $markupEscaped = false;

    function setUrlsLinked($urlsLinked)
    {
        $this->urlsLinked = $urlsLinked;

        return $this;
    }

    protected $urlsLinked = true;

    function setSafeMode($safeMode)
    {
        $this->safeMode = (bool) $safeMode;

        return $this;
    }

    protected $safeMode = false;

    function setStrictMode($strictMode)
    {
        $this->strictMode = (bool) $strictMode;

        return $this;
    }

    protected $strictMode = false;

    #
    # Lines
    #

    protected $BlockTypes = array();
    protected $DefinitionData;

    #
    # Blocks
    #

    protected $blockTypes = array(
        '#' => array('Header'),
        '*' => array('Rule', 'List'),
        '+' => array('List'),
        '-' => array('SetextHeader', 'Table', 'Rule', 'List'),
        '0' => array('List'),
        '1' => array('List'),
        '2' => array('List'),
        '3' => array('List'),
        '4' => array('List'),
        '5' => array('List'),
        '6' => array('List'),
        '7' => array('List'),
        '8' => array('List'),
        '9' => array('List'),
        ':' => array('Table'),
        '<' => array('Comment', 'Markup'),
        '=' => array('SetextHeader'),
        '>' => array('Quote'),
        '[' => array('Reference'),
        '_' => array('Rule'),
        '`' => array('FencedCode'),
        '|' => array('Table'),
        '~' => array('FencedCode'),
    );

    # ~

    protected $unmarkedBlockTypes = array(
        'Code',
    );

    #
    # Code
    #

    protected function blockCode($Line, $Block = null)
    {
        if (isset($Block) and ! isset($Block['type']) and ! isset($Block['interrupted']))
        {
            if ($Line['text'][0] === "\t" or $Line['text'][0] === ' ')
            {
                $text = substr($Line['text'], 4);

                $Block['element']['text'] .= "\n" . $text;

                return $Block;
            }
        }
    }

    protected function blockCodeContinue($Line, $Block)
    {
        if (isset($Block['interrupted']))
        {
            return;
        }

        if ($Line['text'][0] === "\t" or $Line['text'][0] === ' ')
        {
            $text = substr($Line['text'], 4);

            $Block['element']['text'] .= "\n" . $text;

            return $Block;
        }
    }

    protected function blockCodeComplete($Block)
    {
        $text = $Block['element']['text'];

        $Block['element']['text'] = htmlspecialchars($text, ENT_NOQUOTES, 'UTF-8');

        return $Block;
    }

    #
    # Comment
    #

    protected function blockComment($Line)
    {
        if ($this->markupEscaped or $this->safeMode)
        {
            return;
        }

        if (strpos($Line['text'], '<!--') === 0)
        {
            $Block = array(
                'element' => $Line['text'],
            );

            if (strpos($Line['text'], '-->') !== false)
            {
                $Block['closed'] = true;
            }

            return $Block;
        }
    }

    protected function blockCommentContinue($Line, $Block)
    {
        if (isset($Block['closed']))
        {
            return;
        }

        $Block['element'] .= "\n" . $Line['text'];

        if (strpos($Line['text'], '-->') !== false)
        {
            $Block['closed'] = true;
        }

        return $Block;
    }

    #
    # Fenced Code
    #

    protected function blockFencedCode($Line)
    {
        $marker = $Line['text'][0];

        $openerLength = strspn($Line['text'], $marker);

        if ($openerLength < 3)
        {
            return;
        }

        $infostring = trim(substr($Line['text'], $openerLength));

        if (strpos($infostring, '`') !== false)
        {
            return;
        }

        $Element = array(
            'text' => '',
        );

        if ($infostring !== '')
        {
            $language = substr($infostring, 0, strcspn($infostring, " \t\n\f\r"));

            $Element['attributes'] = array('class' => 'language-' . $language);
        }

        $Block = array(
            'char' => $marker,
            'openerLength' => $openerLength,
            'element' => array(
                'name' => 'code',
                'element' => $Element,
            ),
        );

        return $Block;
    }

    protected function blockFencedCodeContinue($Line, $Block)
    {
        if (isset($Block['complete']))
        {
            return;
        }

        if (isset($Block['interrupted']))
        {
            $Block['element']['element']['text'] .= str_repeat("\n", $Block['interrupted']);

            unset($Block['interrupted']);
        }

        if (($len = strspn($Line['text'], $Block['char'])) >= $Block['openerLength'] and rtrim(substr($Line['text'], $len)) === '')
        {
            $Block['element']['element']['text'] = substr($Block['element']['element']['text'], 1);

            $Block['complete'] = true;

            return $Block;
        }

        $Block['element']['element']['text'] .= "\n" . $Line['body'];

        return $Block;
    }

    protected function blockFencedCodeComplete($Block)
    {
        $text = $Block['element']['element']['text'];

        $Block['element']['element']['text'] = htmlspecialchars($text, ENT_NOQUOTES, 'UTF-8');

        return $Block;
    }

    #
    # Header
    #

    protected function blockHeader($Line)
    {
        $level = strspn($Line['text'], '#');

        if ($level > 6)
        {
            return;
        }

        $text = trim($Line['text'], '#');

        if ($this->strictMode and isset($text[0]) and $text[0] !== ' ')
        {
            return;
        }

        $text = trim($text, ' ');

        $Block = array(
            'element' => array(
                'name' => 'h' . $level,
                'text' => $text,
                'handler' => 'line',
            ),
        );

        return $Block;
    }

    #
    # List
    #

    protected function blockList($Line, array $CurrentBlock = null)
    {
        list($name, $pattern) = $Line['text'][0] <= '-' ? array('ul', '[*+-]') : array('ol', '[0-9]{1,9}\.');

        if (preg_match('/^('.$pattern.'([ ]+|$))(.*)/', $Line['text'], $matches))
        {
            $contentIndent = strlen($matches[2]);

            if ($contentIndent >= 5)
            {
                $contentIndent -= 1;
                $matches[1] = substr($matches[1], 0, -$contentIndent);
                $matches[3] = str_repeat(' ', $contentIndent) . $matches[3];
            }
            elseif ($contentIndent === 0)
            {
                $matches[1] .= ' ';
            }

            $markerWithoutWhitespace = strstr($matches[1], ' ', true);

            $Block = array(
                'indent' => $Line['indent'],
                'pattern' => $pattern,
                'data' => array(
                    'type' => $name,
                    'marker' => $matches[1],
                    'marker-type' => ($name === 'ul' ? $markerWithoutWhitespace : substr($markerWithoutWhitespace, 0, -1)),
                ),
                'element' => array(
                    'name' => $name,
                    'elements' => array(),
                ),
            );

            if ($name === 'ol')
            {
                $listStart = ltrim(strstr($matches[1], '.', true), '0');

                if ($listStart !== '1')
                {
                    if (
                        isset($CurrentBlock)
                        and $CurrentBlock['type'] === 'Paragraph'
                        and ! isset($CurrentBlock['interrupted'])
                    ) {
                        return;
                    }

                    $Block['data']['start'] = $listStart;
                }
            }

            $Block['li'] = array(
                'name' => 'li',
                'handler' => 'li',
                'text' => !empty($matches[3]) ? array($matches[3]) : array(),
            );

            $Block['element']['elements'] []= & $Block['li'];

            return $Block;
        }
    }

    protected function blockListContinue($Line, array $Block)
    {
        if (isset($Block['interrupted']) and empty($Block['li']['text']))
        {
            return null;
        }

        $requiredIndent = ($Block['indent'] + strlen($Block['data']['marker']));

        if ($Line['indent'] < $requiredIndent
            and (
                (
                    $Block['data']['type'] === 'ol'
                    and preg_match('/^[0-9]{1,9}\.([ ]+|$)/', $Line['text'])
                ) or (
                    $Block['data']['type'] === 'ul'
                    and preg_match('/^[-*+]([ ]+|$)/', $Line['text'])
                )
            )
        ) {
            if (isset($Block['interrupted']))
            {
                $Block['li']['text'] []= '';

                $Block['loose'] = true;

                unset($Block['interrupted']);
            }

            unset($Block['li']);

            $newList = $this->blockList($Line);

            if ($newList['data']['type'] === $Block['data']['type'] and $newList['data']['marker-type'] === $Block['data']['marker-type'])
            {
                $Block['element']['elements'] = array_merge($Block['element']['elements'], $newList['element']['elements']);
                $Block['li'] = & $Block['element']['elements'][count($Block['element']['elements']) - 1];
                return $Block;
            }

            return $newList;
        }
        elseif ($Line['indent'] < $requiredIndent and $this->isBlockCompletable($Block))
        {
            $requiredIndent = $Block['indent'] + 1;
        }


        if ($Line['indent'] >= $requiredIndent)
        {
            if (isset($Block['interrupted']))
            {
                $Block['li']['text'] []= '';

                $Block['loose'] = true;

                unset($Block['interrupted']);
            }

            $text = substr($Line['text'], $requiredIndent);

            $Block['li']['text'] []= $text;

            return $Block;
        }

        if ( ! isset($Block['interrupted']))
        {
            $text = preg_replace('/^[ ]{0,'.$requiredIndent.'}/', '', $Line['text']);

            $Block['li']['text'] []= $text;

            return $Block;
        }

        return null;
    }

    protected function blockListComplete(array $Block)
    {
        if (isset($Block['loose']))
        {
            foreach ($Block['element']['elements'] as &$li)
            {
                if (end($li['text']) !== '')
                {
                    $li['text'] []= '';
                }
            }
        }

        return $Block;
    }

    #
    # Quote
    #

    protected function blockQuote($Line)
    {
        if (preg_match('/^>[ ]?(.*)/', $Line['text'], $matches))
        {
            $Block = array(
                'element' => array(
                    'name' => 'blockquote',
                    'handler' => 'lines',
                    'text' => (array) $matches[1],
                ),
            );

            return $Block;
        }
    }

    protected function blockQuoteContinue($Line, array $Block)
    {
        if ($Line['text'][0] === '>' and preg_match('/^>[ ]?(.*)/', $Line['text'], $matches))
        {
            if (isset($Block['interrupted']))
            {
                $Block['element']['text'] []= '';

                unset($Block['interrupted']);
            }

            $Block['element']['text'] []= $matches[1];

            return $Block;
        }

        if ( ! isset($Block['interrupted']))
        {
            $Block['element']['text'] []= $Line['text'];

            return $Block;
        }
    }

    #
    # Rule
    #

    protected function blockRule($Line)
    {
        $marker = $Line['text'][0];

        if (substr_count($Line['text'], $marker) >= 3 and rtrim($Line['text'], " $marker") === '')
        {
            $Block = array(
                'element' => array(
                    'name' => 'hr',
                ),
            );

            return $Block;
        }
    }

    #
    # Setext
    #

    protected function blockSetextHeader($Line, array $Block = null)
    {
        if ( ! isset($Block) or $Block['type'] !== 'Paragraph' or isset($Block['interrupted']))
        {
            return;
        }

        if (rtrim($Line['text'], $Line['text'][0]) === '')
        {
            $Block['element']['name'] = $Line['text'][0] === '=' ? 'h1' : 'h2';

            return $Block;
        }
    }

    #
    # Markup
    #

    protected function blockMarkup($Line)
    {
        if ($this->markupEscaped or $this->safeMode)
        {
            return;
        }

        if (preg_match('/^<(\w[\w-]*)(?:[ ]*'.$this->regexHtmlAttribute.')*[ ]*(\/)?>/', $Line['text'], $matches))
        {
            $element = strtolower($matches[1]);

            if (in_array($element, $this->textLevelElements))
            {
                return;
            }

            $Block = array(
                'name' => $matches[1],
                'depth' => 0,
                'element' => $Line['text'],
            );

            $length = strlen($matches[0]);

            $remainder = substr($Line['text'], $length);

            if (trim($remainder) === '')
            {
                if (isset($matches[2]) or in_array($matches[1], $this->voidElements))
                {
                    $Block['closed'] = true;

                    $Block['void'] = true;
                }
            }
            else
            {
                if (isset($matches[2])) # self-closing tag
                {
                    return;
                }

                if (preg_match('/<\/'.$matches[1].'>[ ]*$/i', $remainder)) # closing tag
                {
                    $Block['closed'] = true;
                }
            }

            return $Block;
        }
    }

    protected function blockMarkupContinue($Line, array $Block)
    {
        if (isset($Block['closed']))
        {
            return;
        }

        if (preg_match('/<'.$Block['name'].'(?:[ ]*'.$this->regexHtmlAttribute.')*[ ]*>/i', $Line['text'])) # open
        {
            $Block['depth'] ++;
        }

        if (preg_match('/(.*?)<\/'.$Block['name'].'>/i', $Line['text'], $matches)) # close
        {
            if ($Block['depth'] > 0)
            {
                $Block['depth'] --;
            }
            else
            {
                $Block['closed'] = true;
            }
        }

        if (isset($Block['interrupted']))
        {
            $Block['element'] .= "\n";

            unset($Block['interrupted']);
        }

        $Block['element'] .= "\n".$Line['text'];

        return $Block;
    }

    #
    # Reference
    #

    protected function blockReference($Line)
    {
        if (strpos($Line['text'], ']') !== false and preg_match('/^\[(.+?)\]:[ ]*<?(\S+?)>?(?:[ ]+["\'(](.+)["\')])?[ ]*$/', $Line['text'], $matches))
        {
            $id = strtolower($matches[1]);

            $Data = array(
                'url' => $matches[2],
                'title' => isset($matches[3]) ? $matches[3] : null,
            );

            $this->DefinitionData['Reference'][$id] = $Data;

            $Block = array(
                'hidden' => true,
            );

            return $Block;
        }
    }

    #
    # Table
    #

    protected function blockTable($Line, array $Block = null)
    {
        if ( ! isset($Block) or $Block['type'] !== 'Paragraph' or isset($Block['interrupted']))
        {
            return;
        }

        if (
            strpos($Block['element']['text'], '|') === false
            and strpos($Line['text'], '|') === false
            and strpos($Line['text'], ':') === false
            or strpos($Block['element']['text'], "\n") !== false
        ) {
            return;
        }

        if (rtrim($Line['text'], ' -:|') !== '')
        {
            return;
        }

        $alignments = array();

        $divider = $Line['text'];

        $divider = trim($divider);
        $divider = trim($divider, '|');

        $dividerCells = explode('|', $divider);

        foreach ($dividerCells as $dividerCell)
        {
            $dividerCell = trim($dividerCell);

            if ($dividerCell === '')
            {
                return;
            }

            $alignment = null;

            if ($dividerCell[0] === ':')
            {
                $alignment = 'left';
            }

            if (substr($dividerCell, -1) === ':')
            {
                $alignment = $alignment === 'left' ? 'center' : 'right';
            }

            $alignments []= $alignment;
        }

        # ~

        $header = $Block['element']['text'];

        $header = trim($header);
        $header = trim($header, '|');

        $headerCells = explode('|', $header);

        if (count($headerCells) !== count($alignments))
        {
            return;
        }

        foreach ($headerCells as $index => $headerCell)
        {
            $headerCells[$index] = trim($headerCell);
        }

        # ~

        $Block = array(
            'alignments' => $alignments,
            'identified' => true,
            'element' => array(
                'name' => 'table',
                'elements' => array(),
            ),
        );

        $Block['element']['elements'] []= array(
            'name' => 'thead',
            'elements' => array(),
        );

        $Block['element']['elements'] []= array(
            'name' => 'tbody',
            'elements' => array(),
        );

        $Row = array(
            'name' => 'tr',
            'elements' => array(),
        );

        foreach ($headerCells as $index => $headerCell)
        {
            $TableCell = array(
                'name' => 'th',
                'text' => $headerCell,
                'handler' => 'line',
            );

            if (isset($alignments[$index]))
            {
                $TableCell['attributes'] = array(
                    'style' => 'text-align: '.$alignments[$index].';',
                );
            }

            $Row['elements'] []= $TableCell;
        }

        $Block['element']['elements'][0]['elements'] []= $Row;

        return $Block;
    }

    protected function blockTableContinue($Line, array $Block)
    {
        if (isset($Block['interrupted']))
        {
            return;
        }

        if ($Line['text'][0] === '|' or strpos($Line['text'], '|'))
        {
            $row = $Line['text'];

            $row = trim($row);
            $row = trim($row, '|');

            $cells = explode('|', $row);

            $Row = array(
                'name' => 'tr',
                'elements' => array(),
            );

            $cellCount = max(count($cells), count($Block['alignments']));

            foreach (range(0, $cellCount - 1) as $index)
            {
                $cell = isset($cells[$index]) ? $cells[$index] : '';

                $TableCell = array(
                    'name' => 'td',
                    'text' => trim($cell),
                    'handler' => 'line',
                );

                if (isset($Block['alignments'][$index]))
                {
                    $TableCell['attributes'] = array(
                        'style' => 'text-align: '.$Block['alignments'][$index].';',
                    );
                }

                $Row['elements'] []= $TableCell;
            }

            $Block['element']['elements'][1]['elements'] []= $Row;

            return $Block;
        }
    }

    #
    # ~
    #

    protected function isBlockCompletable($Type)
    {
        return ! in_array($Type, $this->unmarkedBlockTypes);
    }

    #
    # Paragraph
    #

    protected function blockParagraph($Line)
    {
        if (isset($this->DefinitionData['Reference']) and strpos($Line['text'], ']:') !== false)
        {
            $Block = $this->blockReference($Line);

            if (isset($Block))
            {
                return $Block;
            }
        }

        $Block = array(
            'type' => 'Paragraph',
            'element' => array(
                'name' => 'p',
                'text' => $Line['text'],
                'handler' => 'line',
            ),
        );

        return $Block;
    }

    protected function blockParagraphContinue($Line, array $Block)
    {
        if (isset($Block['interrupted']))
        {
            return;
        }

        $Block['element']['text'] .= "\n".$Line['text'];

        return $Block;
    }

    #
    # Inline Elements
    #

    protected $InlineTypes = array(
        '"' => array('SpecialCharacter'),
        '!' => array('Image'),
        '&' => array('SpecialCharacter'),
        '*' => array('Emphasis'),
        ':' => array('Url'),
        '<' => array('UrlTag', 'EmailTag', 'Markup', 'SpecialCharacter'),
        '>' => array('SpecialCharacter'),
        '[' => array('Link'),
        '_' => array('Emphasis'),
        '`' => array('Code'),
        '~' => array('Strikethrough'),
        '\\' => array('EscapeSequence'),
    );

    # ~

    protected $inlineMarkerList = '!"*_&[:<>`~\\';

    #
    # ~
    #

    public function line($text, $nonNestables = array())
    {
        $markup = '';

        # $excerpt is based on the first occurrence of a marker

        while ($excerpt = strpbrk($text, $this->inlineMarkerList))
        {
            $marker = $excerpt[0];

            $markerPosition = strlen($text) - strlen($excerpt);

            $Excerpt = array('text' => $excerpt, 'context' => $text);

            foreach ($this->InlineTypes[$marker] as $inlineType)
            {
                # check to see if the current inline type is nestable in the current context

                if ( ! empty($nonNestables) and in_array($inlineType, $nonNestables))
                {
                    continue;
                }

                $Inline = $this->{'inline'.$inlineType}($Excerpt);

                if ( ! isset($Inline))
                {
                    continue;
                }

                # makes sure that the inline belongs to the current marker and context

                if (isset($Inline['position']) and $Inline['position'] > $markerPosition)
                {
                    continue;
                }

                # sets a default inline position

                if ( ! isset($Inline['position']))
                {
                    $Inline['position'] = $markerPosition;
                }

                # cause the new element to 'inherit' our non nestables

                $Inline['element']['nonNestables'] = isset($Inline['element']['nonNestables'])
                    ? array_merge($Inline['element']['nonNestables'], $nonNestables)
                    : $nonNestables;

                # the text that comes before the inline
                $unmarkedText = substr($text, 0, $Inline['position']);

                # compile the unmarked text
                $markup .= $this->unmarkedText($unmarkedText);

                # compile the inline
                $markup .= isset($Inline['markup']) ? $Inline['markup'] : $this->element($Inline['element']);

                # remove the examined text
                $text = substr($text, $Inline['position'] + $Inline['extent']);

                continue 2;
            }

            # the marker does not belong to an inline

            $unmarkedText = substr($text, 0, $markerPosition + 1);

            $markup .= $this->unmarkedText($unmarkedText);

            $text = substr($text, $markerPosition + 1);
        }

        $markup .= $this->unmarkedText($text);

        return $markup;
    }

    #
    # ~
    #

    protected function inlineCode($Excerpt)
    {
        $marker = $Excerpt['text'][0];

        if (preg_match('/^('.$marker.'+)[ ]*(.+?)[ ]*(?<!'.$marker.')\1(?!'.$marker.')/s', $Excerpt['text'], $matches))
        {
            $text = $matches[2];
            $text = htmlspecialchars($text, ENT_NOQUOTES, 'UTF-8');
            $text = preg_replace("/[ ]*\n/", ' ', $text);

            return array(
                'extent' => strlen($matches[0]),
                'element' => array(
                    'name' => 'code',
                    'text' => $text,
                ),
            );
        }
    }

    protected function inlineEmailTag($Excerpt)
    {
        if (strpos($Excerpt['text'], '>') !== false and preg_match('/^<((mailto:)?\S+?@\S+?)>/i', $Excerpt['text'], $matches))
        {
            $url = $matches[1];

            if ( ! isset($matches[2]))
            {
                $url = 'mailto:' . $url;
            }

            return array(
                'extent' => strlen($matches[0]),
                'element' => array(
                    'name' => 'a',
                    'text' => $matches[1],
                    'attributes' => array(
                        'href' => $url,
                    ),
                ),
            );
        }
    }

    protected function inlineEmphasis($Excerpt)
    {
        if ( ! isset($Excerpt['text'][1]))
        {
            return;
        }

        $marker = $Excerpt['text'][0];

        if ($Excerpt['text'][1] === $marker and preg_match($this->StrongRegex[$marker], $Excerpt['text'], $matches))
        {
            $emphasis = 'strong';
        }
        elseif (preg_match($this->EmRegex[$marker], $Excerpt['text'], $matches))
        {
            $emphasis = 'em';
        }
        else
        {
            return;
        }

        return array(
            'extent' => strlen($matches[0]),
            'element' => array(
                'name' => $emphasis,
                'handler' => 'line',
                'text' => $matches[1],
            ),
        );
    }

    protected function inlineEscapeSequence($Excerpt)
    {
        if (isset($Excerpt['text'][1]) and in_array($Excerpt['text'][1], $this->specialCharacters))
        {
            return array(
                'markup' => $Excerpt['text'][1],
                'extent' => 2,
            );
        }
    }

    protected function inlineImage($Excerpt)
    {
        if ( ! isset($Excerpt['text'][1]) or $Excerpt['text'][1] !== '[')
        {
            return;
        }

        $Excerpt['text']= substr($Excerpt['text'], 1);

        $Link = $this->inlineLink($Excerpt);

        if ($Link === null)
        {
            return;
        }

        $Inline = array(
            'extent' => $Link['extent'] + 1,
            'element' => array(
                'name' => 'img',
                'attributes' => array(
                    'src' => $Link['element']['attributes']['href'],
                    'alt' => $Link['element']['text'],
                ),
                'nonNestables' => array('Url', 'Link'),
            ),
        );

        $Inline['element']['attributes'] += $Link['element']['attributes'];

        unset($Inline['element']['attributes']['href']);

        return $Inline;
    }

    protected function inlineLink($Excerpt)
    {
        $Element = array(
            'name' => 'a',
            'handler' => 'line',
            'nonNestables' => array('Url', 'Link'),
            'text' => null,
            'attributes' => array(
                'href' => null,
                'title' => null,
            ),
        );

        $extent = 0;

        $remainder = $Excerpt['text'];

        if (preg_match('/\[((?:[^][]++|(?R))*)\]/', $remainder, $matches))
        {
            $Element['text'] = $matches[1];

            $extent += strlen($matches[0]);

            $remainder = substr($remainder, $extent);
        }
        else
        {
            return;
        }

        if (preg_match('/^[(]\s*+((?:[^ ()]++|[(][^ )]+[)])++)(?:[ ]+("[^"]*"|\'[^\']*\'))?\s*+[)]/', $remainder, $matches))
        {
            $Element['attributes']['href'] = $matches[1];

            if (isset($matches[2]))
            {
                $Element['attributes']['title'] = substr($matches[2], 1, -1);
            }

            $extent += strlen($matches[0]);
        }
        else
        {
            if (preg_match('/^\s*\[(.*?)\]/', $remainder, $matches))
            {
                $definition = strlen($matches[1]) ? $matches[1] : $Element['text'];
                $definition = strtolower($definition);

                $extent += strlen($matches[0]);
            }
            else
            {
                $definition = strtolower($Element['text']);
            }

            if (isset($this->DefinitionData['Reference'][$definition]))
            {
                $Definition = $this->DefinitionData['Reference'][$definition];

                $Element['attributes']['href'] = $Definition['url'];
                $Element['attributes']['title'] = $Definition['title'];
            }
            else
            {
                return;
            }
        }

        return array(
            'extent' => $extent,
            'element' => $Element,
        );
    }

    protected function inlineMarkup($Excerpt)
    {
        if ($this->markupEscaped or $this->safeMode or strpos($Excerpt['text'], '>') === false)
        {
            return;
        }

        if ($Excerpt['text'][1] === '/' and preg_match('/^<\/\w[\w-]*[ ]*>/', $Excerpt['text'], $matches))
        {
            return array(
                'markup' => $matches[0],
                'extent' => strlen($matches[0]),
            );
        }

        if ($Excerpt['text'][1] === '!' and preg_match('/^<!---?[^>-](?:-?[^-])*-->/', $Excerpt['text'], $matches))
        {
            return array(
                'markup' => $matches[0],
                'extent' => strlen($matches[0]),
            );
        }

        if ($Excerpt['text'][1] !== ' ' and preg_match('/^<\w[\w-]*(?:[ ]*'.$this->regexHtmlAttribute.')*[ ]*\/?>/', $Excerpt['text'], $matches))
        {
            $element = strtolower(substr($matches[0], 1));

            if (in_array($element, $this->textLevelElements))
            {
                return array(
                    'markup' => $matches[0],
                    'extent' => strlen($matches[0]),
                );
            }
        }
    }

    protected function inlineSpecialCharacter($Excerpt)
    {
        if ($Excerpt['text'][0] === '&' and ! preg_match('/^&#?\w+;/', $Excerpt['text']))
        {
            return array(
                'markup' => '&amp;',
                'extent' => 1,
            );
        }

        $SpecialCharacter = array('>' => 'gt', '<' => 'lt', '"' => 'quot');

        if (isset($SpecialCharacter[$Excerpt['text'][0]]))
        {
            return array(
                'markup' => '&'.$SpecialCharacter[$Excerpt['text'][0]].';',
                'extent' => 1,
            );
        }
    }

    protected function inlineStrikethrough($Excerpt)
    {
        if ( ! isset($Excerpt['text'][1]))
        {
            return;
        }

        if ($Excerpt['text'][1] === '~' and preg_match('/^~~(?=\S)(.+?)(?<=\S)~~/', $Excerpt['text'], $matches))
        {
            return array(
                'extent' => strlen($matches[0]),
                'element' => array(
                    'name' => 'del',
                    'text' => $matches[1],
                    'handler' => 'line',
                ),
            );
        }
    }

    protected function inlineUrl($Excerpt)
    {
        if ($this->urlsLinked !== true or ! isset($Excerpt['text'][2]) or $Excerpt['text'][2] !== '/')
        {
            return;
        }

        if (preg_match('/\bhttps?:\/\/[^\s<>"\'`]+/i', $Excerpt['context'], $matches, PREG_OFFSET_CAPTURE))
        {
            $Inline = array(
                'extent' => strlen($matches[0][0]),
                'position' => $matches[0][1],
                'element' => array(
                    'name' => 'a',
                    'text' => $matches[0][0],
                    'attributes' => array(
                        'href' => $matches[0][0],
                    ),
                ),
            );

            return $Inline;
        }
    }

    protected function inlineUrlTag($Excerpt)
    {
        if (strpos($Excerpt['text'], '>') !== false and preg_match('/^<(\w+:\/{2}[^ >]+)>/i', $Excerpt['text'], $matches))
        {
            $url = $matches[1];

            return array(
                'extent' => strlen($matches[0]),
                'element' => array(
                    'name' => 'a',
                    'text' => $url,
                    'attributes' => array(
                        'href' => $url,
                    ),
                ),
            );
        }
    }

    #
    # ~
    #

    protected function unmarkedText($text)
    {
        if ($this->breaksEnabled)
        {
            $text = preg_replace('/[ ]*\n/', "<br />\n", $text);
        }
        else
        {
            $text = preg_replace('/[ ]{2,}\n/', "<br />\n", $text);
            $text = str_replace(" \n", "\n", $text);
        }

        return $text;
    }

    #
    # Handlers
    #

    protected function element(array $Element)
    {
        if ($this->safeMode)
        {
            $Element = $this->sanitiseElement($Element);
        }

        # identity operator === is required to prevent satisfying strict mode issues
        $hasName = isset($Element['name']);

        $markup = '';

        if ($hasName)
        {
            $markup .= '<' . $Element['name'];

            if (isset($Element['attributes']))
            {
                foreach ($Element['attributes'] as $name => $value)
                {
                    if ($value === null)
                    {
                        continue;
                    }

                    $markup .= ' ' . $name . '="' . self::escape($value) . '"';
                }
            }
        }

        $hasHandler = isset($Element['handler']);

        if ($hasHandler)
        {
            $handler = is_string($Element['handler']) ? $Element['handler'] : 'element';

            $parameter = $Element['text'];
            $nonNestables = isset($Element['nonNestables']) ? $Element['nonNestables'] : array();

            $markup = $this->{'handle' . $handler}($markup, $parameter, $nonNestables);
        }
        elseif (isset($Element['text']))
        {
            $markup .= $hasName ? '>' : '';
            $markup .= $Element['text'];
        }

        if ($hasName)
        {
            $markup .= isset($Element['void']) ? '' : (isset($Element['text']) || $hasHandler ? '</' . $Element['name'] . '>' : '/>');
        }

        return $markup;
    }

    protected function handleElement($markup, $element)
    {
        return $markup . '>' . $this->element($element) . '</' . substr($markup, 1, strpos($markup, ' ') -1) . '>';
    }

    protected function handleElements($markup, $elements)
    {
        $markup .= '>';

        foreach ($elements as $element)
        {
            $markup .= $this->element($element);
        }

        return $markup . '</' . substr($markup, 1, strpos($markup, ' ') -1) . '>';
    }

    protected function handleLi($markup, $lines)
    {
        $markup .= '>';

        $this->BlockTypes = array_diff($this->BlockTypes, array('#' => 'Header', '=' => 'SetextHeader'));

        $lines = $this->identifyBlocks($lines);
        $lines = $this->processBlocks($lines);

        $markup .= implode("\n", $lines);

        $this->BlockTypes = $this->blockTypes;

        return $markup . '</li>';
    }

    protected function handleLine($markup, $line, $nonNestables)
    {
        return $markup . '>' . $this->line($line, $nonNestables) . '</' . substr($markup, 1, strpos($markup, ' ') -1) . '>';
    }

    protected function handleLines($markup, $lines, $nonNestables)
    {
        $markup .= '>';

        foreach ($lines as $line)
        {
            $markup .= $this->line($line, $nonNestables) . "\n";
        }

        return rtrim($markup, "\n") . '</' . substr($markup, 1, strpos($markup, ' ') -1) . '>';
    }

    #
    # Deprecated Methods
    #

    function parse($text)
    {
        $markup = $this->text($text);

        return $markup;
    }

    protected function sanitiseElement(array $Element)
    {
        static $goodAttribute = '/^[a-zA-Z0-9][a-zA-Z0-9-]*+$/';
        static $safeUrlNameToAtt = array(
            'a'   => 'href',
            'img' => 'src',
        );

        if ( ! isset($Element['name']))
        {
            unset($Element['attributes']);
            return $Element;
        }

        if (isset($safeUrlNameToAtt[$Element['name']]))
        {
            $Element = $this->filterUnsafeUrlInAttribute($Element, $safeUrlNameToAtt[$Element['name']]);
        }

        if ( ! empty($Element['attributes']))
        {
            foreach ($Element['attributes'] as $att => $val)
            {
                # filter out invalid attribute names
                if ( ! preg_match($goodAttribute, $att))
                {
                    unset($Element['attributes'][$att]);
                }
                # filter out unsafe protocols
                elseif (self::striAtStart($att, 'on') or self::striAtStart($val, 'javascript:'))
                {
                    unset($Element['attributes'][$att]);
                }
            }
        }

        return $Element;
    }

    protected function filterUnsafeUrlInAttribute(array $Element, $attributeName)
    {
        foreach ($this->safeLinksWhitelist as $scheme => $length)
        {
            if (self::striAtStart($Element['attributes'][$attributeName], $scheme) and
                ! in_array(strtolower($Element['attributes'][$attributeName][$length]), array(':', '/', '#', '?')))
            {
                return $Element;
            }
        }

        $Element['attributes'][$attributeName] = str_replace(':', '%3A', $Element['attributes'][$attributeName]);

        return $Element;
    }

    #
    # Static Methods
    #

    protected static function escape($text, $allowQuotes = false)
    {
        return htmlspecialchars($text, $allowQuotes ? ENT_NOQUOTES : ENT_QUOTES, 'UTF-8');
    }

    protected static function striAtStart($string, $needle)
    {
        $len = strlen($needle);

        if ($len > strlen($string))
        {
            return false;
        }

        return strtolower(substr($string, 0, $len)) === $needle;
    }

    #
    # Private
    #

    private function identifyBlocks(array $lines)
    {
        $CurrentBlock = null;

        foreach ($lines as $line)
        {
            $indent = 0;

            while (isset($line[$indent]) and $line[$indent] === ' ')
            {
                $indent ++;
            }

            $text = $indent > 0 ? substr($line, $indent) : $line;

            # ~

            $Line = array('body' => $line, 'indent' => $indent, 'text' => $text);

            # ~

            if (isset($CurrentBlock['continuable']))
            {
                $Block = $this->{'block'.$CurrentBlock['type'].'Continue'}($Line, $CurrentBlock);

                if (isset($Block))
                {
                    $CurrentBlock = $Block;

                    continue;
                }
                else
                {
                    if ($this->isBlockCompletable($CurrentBlock['type']))
                    {
                        $CurrentBlock = $this->{'block'.$CurrentBlock['type'].'Complete'}($CurrentBlock);
                    }
                }
            }

            # ~

            $marker = $text[0];

            # ~

            $blockTypes = $this->unmarkedBlockTypes;

            if (isset($this->BlockTypes[$marker]))
            {
                foreach ($this->BlockTypes[$marker] as $blockType)
                {
                    $blockTypes []= $blockType;
                }
            }

            #
            # ~

            foreach ($blockTypes as $blockType)
            {
                $Block = $this->{'block'.$blockType}($Line, $CurrentBlock);

                if (isset($Block))
                {
                    $Block['type'] = $blockType;

                    if ( ! isset($Block['identified']))
                    {
                        if (isset($CurrentBlock))
                        {
                            $this->Blocks []= $this->extractElement($CurrentBlock);
                        }

                        $Block['identified'] = true;
                    }

                    if ($this->isBlockContinuable($blockType))
                    {
                        $Block['continuable'] = true;
                    }

                    $CurrentBlock = $Block;

                    continue 2;
                }
            }

            # ~

            if (isset($CurrentBlock) and ! isset($CurrentBlock['type']) and ! isset($CurrentBlock['interrupted']))
            {
                $CurrentBlock['element']['text'] .= "\n".$text;
            }
            else
            {
                if (isset($CurrentBlock))
                {
                    $this->Blocks []= $this->extractElement($CurrentBlock);
                }

                $CurrentBlock = $this->blockParagraph($Line);

                $CurrentBlock['identified'] = true;
            }
        }

        # ~

        if (isset($CurrentBlock['continuable']) and $this->isBlockCompletable($CurrentBlock['type']))
        {
            $CurrentBlock = $this->{'block'.$CurrentBlock['type'].'Complete'}($CurrentBlock);
        }

        # ~

        if (isset($CurrentBlock))
        {
            $this->Blocks []= $this->extractElement($CurrentBlock);
        }

        # ~

        $lines = array();

        foreach ($this->Blocks as $Block)
        {
            if ( ! isset($Block['hidden']))
            {
                $lines []= $Block;
            }
        }

        unset($this->Blocks);

        return $lines;
    }

    private function isBlockContinuable($Type)
    {
        return method_exists($this, 'block'.$Type.'Continue');
    }

    private function iterateThroughLines(array $lines)
    {
        foreach ($lines as &$line)
        {
            if (isset($line['element']))
            {
                $line = $this->element($line['element']);
            }
        }

        return $lines;
    }

    private function extractElement(array $Block)
    {
        if ( ! isset($Block['element']))
        {
            if (isset($Block['markup']))
            {
                return $Block['markup'];
            }

            return;
        }

        return $Block['element'];
    }

    # ~

    protected $voidElements = array(
        'area', 'base', 'br', 'col', 'command', 'embed', 'hr', 'img', 'input', 'keygen', 'link', 'meta', 'param', 'source', 'track', 'wbr',
    );

    protected $textLevelElements = array(
        'a', 'br', 'bdo', 'abbr', 'blink', 'nextid', 'acronym', 'basefont',
        'b', 'em', 'i', 'strike', 's', 'big', 'small', 'tt',
        'address', 'article', 'aside', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
        'blockquote', 'code', 'del', 'ins', 'dl', 'dt', 'dd', 'kbd', 'li',
        'mark', 'pre', 'q', 'rp', 'rt', 'ruby', 'samp', 'span', 'strong',
        'sub', 'sup', 'var', 'cite', 'dfn',
    );

    # ~

    protected $regexHtmlAttribute = '[a-zA-Z_:][\w:.-]*+(?:\s*=\s*(?:[^"\'=<>`\s]+|"[^"]*+"|\'[^\']*+\'))?';

    # ~

    protected $specialCharacters = array(
        '\\', '`', '*', '_', '{', '}', '[', ']', '(', ')', '>', '#', '+', '-', '.', '!', '|',
    );

    protected $StrongRegex = array(
        '*' => '/^[*]{2}((?:\\\\\*|[^*]|[*][^*]*[*])+?)[*]{2}(?![*])/s',
        '_' => '/^__((?:\\\\_|[^_]|_[^_]*_)+?)__(?!_)/us',
    );

    protected $EmRegex = array(
        '*' => '/^[*]((?:\\\\\*|[^*]|[*][*][^*]+?[*][*])+?)[*](?![*])/s',
        '_' => '/^_((?:\\\\_|[^_]|__[^_]*__)+?)_(?!_)\b/us',
    );

    protected $safeLinksWhitelist = array(
        'http://'  => 7,
        'https://' => 8,
        'ftp://'   => 6,
        'ftps://'  => 7,
        'mailto:'  => 7,
        'data:'    => 5,
        'irc:'     => 4,
        'ircs:'    => 5,
        'git:'     => 4,
        'ssh:'     => 4,
        'xmpp:'    => 5,
        'tel:'     => 4,
    );

    #
    # Fields
    #

    protected $Blocks = array();
}
