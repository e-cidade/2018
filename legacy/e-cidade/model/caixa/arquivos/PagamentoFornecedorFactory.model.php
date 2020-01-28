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

require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));

/**
* Factory que retorna a instancia da Classe do Banco
* @package caixa
* @author Andrio Araujo da Costa
* @version $Revision: 0.1
*/
class PagamentoFornecedorFactory {


  /**
   * Retorna a instancia da classe apartir do código do Banco
   *
   * @param  integer $iCodigoBanco  -- 000 Banco do Brasil - OBN
   *                                -- 001 Banco do Brasil
   *                                -- 041 Banrisul
   *                                -- 104 Caixa Economica Federal
   *
   * @return PagamentoFornecedorTXTBase
   */
  static function getInstance($iCodigoBanco) {

    switch ($iCodigoBanco) {

      case '000':
        return new PagamentoFornecedorBancoDoBrasilOBN();
      break;

      case '001':
        return new PagamentoFornecedorBancoDoBrasil();
      break;

      case '041':
        return new PagamentoFornecedorBanrisul();
      break;

      case '104':
        return new PagamentoFornecedorCaixaEconomica();
      break;

      case GeradorArquivoPagFor::CODIGO_BANCO_BRADESCO:
        return new PagamentoFornecedorBradescoPagFor();
        break;

      default:
        return false;
      break;
    }
  }
}