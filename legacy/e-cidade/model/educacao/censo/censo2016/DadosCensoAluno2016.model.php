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
 * Classe responsável por gerar o censo 2016
 *
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @package    educacao
 * @subpackage censo
 * @subpackage censo2016
 *
 * @version   $Revision: 1.2 $
 */
class DadosCensoAluno2016 extends DadosCensoAluno2015 {


  /**
   * Define os novos campos para layout 2016
   */
  protected function setDadosIdenficacao($rsDadosAluno) {

    parent::setDadosIdenficacao($rsDadosAluno);

    $this->oDadosAluno->filiacao_1 = '';
    $this->oDadosAluno->filiacao_2 = '';

    if ($this->oDadosAluno->filiacao == 0 ) {

      unset($this->oDadosAluno->nome_mae);
      unset($this->oDadosAluno->nome_pai);
      return $this->oDadosAluno;
    }

    if ( !empty($this->oDadosAluno->nome_mae) && !empty($this->oDadosAluno->nome_pai)) {

      $this->oDadosAluno->filiacao_1 = $this->oDadosAluno->nome_mae;
      $this->oDadosAluno->filiacao_2 = $this->oDadosAluno->nome_pai;
    }

    if ( empty($this->oDadosAluno->nome_mae) && !empty($this->oDadosAluno->nome_pai)) {
      $this->oDadosAluno->filiacao_1 = $this->oDadosAluno->nome_pai;
    }

    if ( !empty($this->oDadosAluno->nome_mae) && empty($this->oDadosAluno->nome_pai)) {
      $this->oDadosAluno->filiacao_1 = $this->oDadosAluno->nome_mae;
    }

    unset($this->oDadosAluno->nome_mae);
    unset($this->oDadosAluno->nome_pai);
    return $this->oDadosAluno;
  }

}

