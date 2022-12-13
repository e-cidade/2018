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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");

$oParam                 = json_decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->erro         = false;
$oRetorno->configuracao = null;
$oRetorno->mensagem     = '';

try {

  switch($oParam->exec) {

    case 'lerConfiguracao':

      $aContas = array();
      $sCaminhoArquivo   = 'config/financeiro/';
      $sNomeArquivo      = 'contas_siconfi.json';
      $oConteudoArquivo  = json_decode(file_get_contents($sCaminhoArquivo . $sNomeArquivo));
      $aEstruturaisWhere = array();

      if(is_readable($sCaminhoArquivo . $sNomeArquivo) && ($oConteudoArquivo != null || !empty($oConteudoArquivo)) ) {

        foreach ($oConteudoArquivo->contas as $iConta) {
          array_push($aEstruturaisWhere, "'" . str_pad($iConta, 15, '0') . "'");
        }

        $sEstruturaisWhere     = implode(', ', $aEstruturaisWhere);
        $oDaoConplanoOrcamento = new cl_conplanoorcamento();
        $sSqlCampos = 'c60_codcon, c60_estrut, c60_descr';
        $iAnoUsu    = db_getsession('DB_anousu');
        $sWhere     = "c60_estrut in (" . $sEstruturaisWhere . ") and c60_anousu = '" . $iAnoUsu . "'";
        $sSqlDadosContas = $oDaoConplanoOrcamento->sql_query_file(null, null, $sSqlCampos, 'c60_estrut ASC', $sWhere);
        $rsDadosContas   = $oDaoConplanoOrcamento->sql_record($sSqlDadosContas);

        if ($rsDadosContas && $oDaoConplanoOrcamento->numrows > 0) {

          for ($iIndice = 0; $iIndice < $oDaoConplanoOrcamento->numrows; $iIndice++) {
            array_push($aContas, db_utils::fieldsMemory($rsDadosContas, $iIndice));
          }
        }
      }

      $oRetorno->configuracao = $aContas;

      break;

    case 'salvarConfiguracao':

      $oContas = new stdClass();
      $oContas->contas = $oParam->contas;
      $sCaminhoArquivo   = 'config/financeiro/';
      $sNomeArquivo      = 'contas_siconfi.json';

      if(!is_writable($sCaminhoArquivo) || file_put_contents($sCaminhoArquivo . $sNomeArquivo, json_encode($oContas)) === false) {
        throw new Exception('Erro ao tentar escrever o arquivo de contas.');
      }

      break;

    default:

      throw new Exception("Método inexistente.");
      break;
  }

} catch (Exception $e) {

  $oRetorno->erro = true;
  $oRetorno->mensagem = $e->getMessage();
}

$oRetorno->mensagem = urlencode($oRetorno->mensagem);
echo json_encode($oRetorno);
