<?php

namespace App\Services;

use DOMDocument;
use DOMElement;
use Exception;

class YandexWidgetParser
{
    public function parse($html) {
        $doc = new DOMDocument();
        $doc->loadHTML($html, LIBXML_NOERROR);
        $rating = null;
        foreach ($doc->getElementsByTagName('p') as $p) {
            if (mb_strpos($p->className, 'mini-badge__stars-count') !== false ) {
                $rating = $p->nodeValue;
                break;
            }
        }
        $ratingMini = null;
        foreach ($doc->getElementsByTagName('a') as $a) {
            if (mb_strpos($a->className, 'mini-badge__rating') !== false ) {
                $ratingMini = mb_substr($a->nodeValue, 0, mb_strpos($a->nodeValue, ' '));
                break;
            }
        }
        $starsDom = null;
        foreach ($doc->getElementsByTagName('ul') as $ul) {
            if (mb_strpos($ul->className, 'stars-list') !== false ) {
                $starsDomStr = $doc->saveXML($ul);
                $starsDomCountEmpties = count(explode('_empty', $starsDomStr)) - 1;
                $starsDomCountHalf = count(explode('_half', $starsDomStr)) - 1;
                $starsDom = [
                    'stars' => 5 - $starsDomCountEmpties,
                    'is_last_half' => $starsDomCountHalf > 0,
                ];
                break;
            }
        }

        $comments = [];
        $allDivs = $doc->getElementsByTagName('div');
        try {
            foreach (range(0, count($allDivs) - 1) as $index) {
                if (mb_strpos($allDivs->item($index)->className, 'badge__comments') !== false) {
                    $divBadge = $allDivs->item($index);
                    $commentsBadgeDivs = $divBadge->getElementsByTagName('div');
                    foreach (range(0, count($commentsBadgeDivs) - 1) as $indexCommentsBadgeDivs) {
                        $commentDiv = $commentsBadgeDivs->item($indexCommentsBadgeDivs);
                        if (mb_strpos($commentDiv->className, 'comment_') !== false) {
                            continue;
                        }
                        $headerAndStarsDivs = $commentDiv->getElementsByTagName('div');
                        $nameAndDate = null;
                        $stars = null;
                        foreach (range(0, count($headerAndStarsDivs) - 1) as $indexHeaderAndStarsDivs) {
                            $headerAndStarsDiv = $headerAndStarsDivs->item($indexHeaderAndStarsDivs);
                            if (mb_strpos($headerAndStarsDiv->className, 'comment__header') !== false) {
                                $headerNameAndDateDiv = $headerAndStarsDiv->getElementsByTagName('div')->item(0);
                                $headerNameAndDateDivPs = $headerNameAndDateDiv->getElementsByTagName('p');
                                $nameAndDate = [
                                    'name' => $headerNameAndDateDivPs->item(0)->nodeValue ?? '',
                                    'date' => $headerNameAndDateDivPs->item(1)->nodeValue ?? '',
                                ];
                            } else {
                                $starsStr = $doc->saveHTML($headerAndStarsDiv);
                                $stars = [
                                    'stars' => 5 - (count(explode('_empty', $starsStr)) - 1),
                                ];
                            }
                        }
                        $textP = $commentDiv->childNodes->item(2);

                        if (!$nameAndDate) {
                            $comments [] = $doc->saveHTML($commentDiv);
                            break;
                        }

                        $comments [] = [
                            ...$nameAndDate,
                            ...$stars,
                            'text' => $textP->nodeValue ?? '',
                        ];
                    }
                }
            }
        } catch (\Exception $ex) {
        }

        return ['rating' => $rating, 'ratingMini' => $ratingMini, 'starsDom' => $starsDom, 'comments' => $comments];
    }
}
