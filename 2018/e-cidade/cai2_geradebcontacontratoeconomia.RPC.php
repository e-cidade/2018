<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBSeller Servicos de Informatica
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

$oParam   = JSON::create()->parse(str_replace("\\", "", $_POST["json"]));
$oRetorno = (object) array(
  "message" => '',
  "erro" => false
);

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case "opcoesSelect":

      $iInstituicao = db_getsession("DB_instit"); 
      
      $sSqlTipoDebito = "
        select distinct
          d66_arretipo,
          k00_descr 
        from
          debcontapedidotipo
        inner join arretipo on k00_tipo = d66_arretipo
        where
          k00_instit = {$iInstituicao}
        order by
          d66_arretipo desc";
      
      $rsTipoDebito = db_query($sSqlTipoDebito);
      $iQuantidadeTipoDebito = pg_num_rows($rsTipoDebito);
      $aTipoDebito = array();

      for($iLinha = 0; $iLinha < $iQuantidadeTipoDebito; $iLinha++){
        $oTipoDebito = pg_fetch_object($rsTipoDebito, $iLinha);
        $aTipoDebito[] = $oTipoDebito;
      }

      $sSqlBanco = "
        select distinct
          d62_banco,
          nomebco
        from
          debcontaparam
        inner join bancos on codbco = d62_banco
        where
          d62_instituicao =  {$iInstituicao}";

      $rsBanco = db_query($sSqlBanco);
      $iQuantidadeBanco = pg_num_rows($rsBanco);
      $aBanco = array();

      for($iLinha = 0; $iLinha < $iQuantidadeBanco; $iLinha++){
        $oBanco = pg_fetch_object($rsBanco, $iLinha);
        $aBanco[] = $oBanco;
      }

      $oRetorno->aOpcoesTipoDebito = $aTipoDebito;
      $oRetorno->aOpcoesBanco      = $aBanco;
      
      break;

    default:
      throw new Exception("Opção é inválida.");
  }

  db_fim_transacao();

} catch (Exception $exception) {

  db_fim_transacao(true);

  $oRetorno->message = $exception->getMessage();
  $oRetorno->erro = true;
}

echo JSON::create()->stringify($oRetorno);