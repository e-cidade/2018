<?php

class DBFaq extends DBCentralAjuda {

  public function getData() {

    $sUri = sprintf('faq/findFaqByMenu/%s/%s/1', $this->getIdItemMenu(), $this->getVersao());;

    $oHttpRequest = $this->getHttpRequest();
    $oHttpRequest->send($sUri);
    $sRetorno = $oHttpRequest->getBody();

    $aFaqs = json_decode($sRetorno);

    if (!empty($aFaqs->error)) {
      throw new BusinessException($aFaqs->message);
    }

    return $aFaqs;
  }

}