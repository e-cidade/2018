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


class libparagrafo {

  const TIPO_TEXT_PURO = 1;
  const TIPO_TABELA_SIMPLES = 2;
  const TIPO_CODIGO_PHP = 3;

  var $iTipoParagrafo = null;
  var $oParag         = null;
  var $aParametros    = array();

  //function libparagrafo( $oParag, $aParametros ) {
  function libparagrafo( $oParag ) {

    $this->oParag      = $oParag;
//    $this->aParametros = $aParametros;

  }

  function getObjParagrafo () {

    $oParagrafo = null;

    switch ( (int)$this->oParag->db02_tipo ) {

      case 1:
        if ( !class_exists('libparagrafoText') ) {
          require_once("libs/db_libparagrafoText.php");
        }
        $oParagrafo = new libparagrafoText( $this->oParag );
        break;

      case 2:
        if ( !class_exists('libParagrafoTabela') ) {
          require_once("libs/db_libParagrafoTabela.php");
        }
        $oParagrafo = new libParagrafoTabela( $this->oParag, $this->aParametros );
        break;

      case 3:
        if ( !class_exists('libParagrafoCodPhp') ) {
          require_once("libs/db_libparagrafoCodPhp.php");
        }
        $oParagrafo = new libParagrafoCodPhp( $this->oParag, $this->aParametros );
        break;

    }

    return $oParagrafo;

  }

}