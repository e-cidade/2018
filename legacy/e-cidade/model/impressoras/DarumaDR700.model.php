<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

/**
 * Driver de impressão para Daruma modelo DR700
 *
 * @package   impressoras
 * @author    Andrio Costa  - andrio.costa@dbseller.com.br
 * @version   $Revision: 1.2 $
 */
class DarumaDR700 extends ImpressoraTermica {

  public function __construct($sIp, $sPorta) {

    $this->setIp($sIp);
    $this->setPorta($sPorta);

    $this->setEspacamentoCaracter(0);

  }

  public function setMargemDireita($iTam=48) {

    $this->iMargemDireita = $iTam;
  }

  public function setMargemEsquerda($iTam=1) {

    $this->iMargemDireita = $iTam;
  }


  /**
   * Retorna o alinhamento
   * @param string $sAlinhamento
   * @return int
   */
  private function getAlinhamento($sAlinhamento = 'L') {

    switch($sAlinhamento) {
      case 'L':
        return 0;
        break;
      case 'C' :
        return 1;
        break;
      case 'D' :
        return 2;
        break;
      default:
        return 0;
    }
  }

  /**
   * Método para escrever um texto puro
   *
   * @param string   $sTexto          String a ser impressa
   * @param bool     $lQuebraLinha    Flag que informa se a linha deve ser quebrada
   * @param integer  $iNroCaracteres  Número de caracteres que servirá de base para o alinhamento
   * @param string   $sAlinhamento    Tipo de alinhamento : 'L' - Esquerda, 'R' - Direita, 'C'-Centro
   *
   */
  public function escrever($sTexto,$lQuebraLinha=false,$iNroCaracteres='',$sAlinhamento='') {

    $sTexto = $this->strToAsc($sTexto);

    parent::addComando(chr(27).chr(106). $this->getAlinhamento(strtoupper($sAlinhamento)));
    $sTexto = str_replace("<b>", $this->getComandoNegrito(true), $sTexto);
    $sTexto = str_replace("</b>", $this->getComandoNegrito(false), $sTexto);
    $sTexto = str_replace("<i>", $this->getComandoItalico(true), $sTexto);
    $sTexto = str_replace("</i>", $this->getComandoItalico(false), $sTexto);
    $sTexto = str_replace("<s>", $this->getComandoSublinhado(true), $sTexto);
    $sTexto = str_replace("</s>", $this->getComandoSublinhado(false), $sTexto);

    parent::addComando($sTexto);

    if ($lQuebraLinha) {
      $this->novaLinha();
    }
  }

  /**
   * Este modelo da Daruma, não possui opção de corte.
   */
  public function cortarPapel($lTotal=true) {}
}