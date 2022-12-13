<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSelller Servicos de Informatica
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
// $Id: con1_permissaolotacao.RPC.php,v 1.1 2015/04/14 13:39:35 dbrenan Exp $
require_once("std/db_stdClass.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");
require_once("dbforms/db_funcoes.php");

$oJson                = new services_json(0, true);
$oParam               = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno             = new stdClass();
$oRetorno->status     = true;
$oRetorno->erro       = false;
$oRetorno->message    = '';

define('MENSAGENS', 'configuracao.configuracao.con1_permissaolotacao');

try {

  db_inicio_transacao();//Begin
  switch ($oParam->exec) {
      
     /**
      * Retorna as lota��es que est�o disponiveis, e que j� 
      * est�o selecionadas para cada uma das intitui��es que o usu�rio tem acesso.
      */
     case 'carregarLotacoes':

       $aLotacoesUsuarioIntituicoes = array();
       $aLotacoesInstituicoes       = array();
       $aInstituicoesPermitidas     = array();    

       $oUsuarioSistema = UsuarioSistemaRepository::getPorCodigo($oParam->iCodigoUsuario);
       $aInstituicoes   = $oUsuarioSistema->getInstituicoes();

       /**
        * Percorre todas as intitui��es que o usu�rio possui permiss�o, montando 2 arrays, 
        * um com as Lota��es permitidas($aInstituicoesPermitidas) e outro com as lota��es 
        * j� selecinoadas($aLotacoesUsuarioIntituicoes).
        */
       foreach ($aInstituicoes as $oInstituicao) {

         $iInstituicao     = $oInstituicao->getCodigo();
         $aLotacoesUsuario = LotacaoRepository::getLotacoesByUsuario($oUsuarioSistema, $oInstituicao);
         $alotacoes        = UsuarioSistemaRepository::getLotacoesPermitidas($oUsuarioSistema, $oInstituicao);

         if (count($aLotacoesUsuario) > 0) {
           $aLotacoesUsuarioIntituicoes[$iInstituicao] = $aLotacoesUsuario;
         }

         if (count($alotacoes) > 0) {
           $aLotacoesInstituicoes[$iInstituicao] = $alotacoes;
         }

         $aInstituicoesPermitidas[] = $iInstituicao;
       }

       $oRetorno->aInstituicoes         = $aInstituicoesPermitidas;
       $oRetorno->aLotacoesUsuario      = $aLotacoesUsuarioIntituicoes;
       $oRetorno->aLotacoesInstituicoes = $aLotacoesInstituicoes;
     break;
     /**
      * Salva as Lota��es selecionadas para o usu�rio.
      */
     case 'salvarLotacoes':
     
       $oUsuarioSistema  = UsuarioSistemaRepository::getPorCodigo($oParam->iCodigoUsuario);
       
       LotacaoRepository::excluir(null, $oUsuarioSistema);

       foreach ($oParam->aLotacoesSelecionadas as $oLotacaoSelecionada) {

         $oLotacao = new Lotacao();
         $oLotacao->setCodigoLotacao($oLotacaoSelecionada->id);
         $oLotacao->setUsuarios(array($oParam->iCodigoUsuario));
         LotacaoRepository::persist($oLotacao);
       }

       $oRetorno->message = urlencode("Lota��es do usu�rio salvas com sucesso.");  
     break;
  }

  db_fim_transacao();//Commit
} catch (Exception $oErro) {

  db_fim_transacao(true);

  $oRetorno->erro  = true;
  $oRetorno->message = urlencode($oErro->getMessage());
}

echo $oJson->encode($oRetorno, true);