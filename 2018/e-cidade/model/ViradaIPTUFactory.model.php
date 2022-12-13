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
 * Factory que retorna a instancia da classe do model que irá ser utilizado para virada anual IPTU
 *
 * @package iptu
 * @author Luiz Marcelo Schmitt
 * @revision $Author: dbtales.baz $
 * @version $Revision: 1.4 $
 */
 abstract class ViradaIPTUFactory {

  function __construct() {

  }

  /**
   * Retorna a instancia da classe apartir do parâmetros informados
   * @param  string $sNomeTabela
   * @return object
   */
  public static function getInstance( $sNomeTabela='' ) {

    if ( trim($sNomeTabela) != '' ) {

      $sNomeModel = ucwords(trim("{$sNomeTabela}IPTU"));
      if ( file_exists("model/viradaIPTU/{$sNomeModel}.model.php") ) {

        if ( !class_exists(trim($sNomeModel)) ) {
          require_once("model/viradaIPTU/{$sNomeModel}.model.php");
        }

        return new $sNomeModel;
      } else {

      	require_once('model/viradaIPTU/ViradaIPTUPadrao.model.php');
        return new ViradaIPTUPadrao( $sNomeTabela );
      }
    }
  }
}