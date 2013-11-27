<?php

class User_View_Helper_CandidateRating extends Zend_View_Helper_Abstract
{
    public function candidateRating($applicationId, $curRate = Null)
    {
        $this->view->headLink()->appendStylesheet('/css/jRating.jquery.css');
        $this->view->jQuery()->addJavascriptFile('/js/jRating.jquery.min.js');

        $this->view->jQuery()->addOnload("
            $('.candidate-rating').jRating({step: 1, 
                                            length: 5,
                                            rateMax: 5,
                                            sendRequest: 1,
                                            canRateAgain: 1,
                                            nbRates: 1000,
                                            phpPath: '{$this->view->url(array('module' => 'user',
                                                                              'controller' => 'candidates',
                                                                              'action' => 'ajax-rate-candidate',))}',
                                            bigStarsPath: '/assets/images/stars-empty.png',
                                            onSuccess: function(element,rate) {
                                                $(element).parents('.stars-parent').find('div.score').html(rate+'<span>/5</span>')
                                            }

                                            });
        ");

        //bigStarsPath: '/images/icons/stars.png',

        $html = '';
        $html .= '<div class="basic candidate-rating"';
        $html .= ' data-id="' . $applicationId . '"';
        $html .= ' data-average="' . (($curRate) ? $curRate : 0) . '"';
        $html .= '>'; 
        $html .= '</div>';
        return $html;
    }
}
