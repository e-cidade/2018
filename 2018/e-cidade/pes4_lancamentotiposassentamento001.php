<?php
/*
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

  require_once(modification("libs/db_stdlib.php"));
  require_once(modification("libs/db_utils.php"));
  require_once(modification("libs/db_app.utils.php"));
  require_once(modification("libs/db_conecta.php"));
  require_once(modification("libs/db_sessoes.php"));
  require_once(modification("dbforms/db_funcoes.php"));
  require_once(modification("dbforms/db_classesgenericas.php"));

  const MENSAGEM = "recursoshumanos.pessoal.db_lancamentotipoassentamento.";

  $cltipoassefinanceiro = new cl_tipoassefinanceiro();
  $cltipoassefinanceiro->rotulo->label();

  db_postmemory($_POST);
  $lErro    = false;
  $db_opcao = 1;

  try {

    if (isset($incluir) || isset($alterar)) {

      $oDataInicio = null;

      if(!empty($rh165_datainicio)) {
        $oDataInicio = new DBDate($rh165_datainicio);
      }
    }

    if (isset($incluir)) {

      $iInstituicao = InstituicaoRepository::getInstituicaoSessao()->getCodigo();
      /**
       * Verificamos se o  tipo de assentamento já esta configurado.
       */
      $sSqlTipoAsseFinanceiro  = $cltipoassefinanceiro->sql_query_file(null, "rh165_tipoasse", null, "rh165_tipoasse = {$rh165_tipoasse} and rh165_instit = {$iInstituicao}");
      $rsSqlTipoAsseFinanceiro = db_query($sSqlTipoAsseFinanceiro);

      if (!$rsSqlTipoAsseFinanceiro) {
        
        $lErro     = true;
        $sMensagem = _M(MENSAGEM."erro_consultar_assentamento");
      }

      if (!$lErro && pg_num_rows($rsSqlTipoAsseFinanceiro) > 0) {

        $lErro     = true;
        $sMensagem = _M(MENSAGEM."assentamento_ja_cadastrado");
      }

      if (!$lErro) {

        $cltipoassefinanceiro->rh165_sequencial       = null;
        $cltipoassefinanceiro->rh165_tipoasse         = $rh165_tipoasse;
        $cltipoassefinanceiro->rh165_rubric           = $rh165_rubric;
        $cltipoassefinanceiro->rh165_instit           = $iInstituicao;
        $cltipoassefinanceiro->rh165_db_formulas      = $rh165_db_formulas;
        $cltipoassefinanceiro->rh165_tipolancamento   = $rh165_tipolancamento;
        $cltipoassefinanceiro->rh165_anousu           = DBPessoal::getAnoFolha();
        $cltipoassefinanceiro->rh165_mesusu           = DBPessoal::getMesFolha();
        $cltipoassefinanceiro->rh165_datainicio       = (!empty($oDataInicio) ? $oDataInicio->getDate() : null);
        $cltipoassefinanceiro->incluir(null);

        $sMensagem = $sMensagem = _M(MENSAGEM."sucesso_inclusao");;

        if ($cltipoassefinanceiro->erro_status == "0") {

          $lErro     = true;
          $sMensagem = $cltipoassefinanceiro->erro_msg;
        }
      }
    }

    if (isset($alterar)) {

      $cltipoassefinanceiro->rh165_sequencial       = $rh165_sequencial;
      $cltipoassefinanceiro->rh165_tipoasse         = $rh165_tipoasse;
      $cltipoassefinanceiro->rh165_rubric           = $rh165_rubric;
      $cltipoassefinanceiro->rh165_instit           = InstituicaoRepository::getInstituicaoSessao()->getCodigo();
      $cltipoassefinanceiro->rh165_db_formulas      = $rh165_db_formulas;
      $cltipoassefinanceiro->rh165_tipolancamento   = $rh165_tipolancamento;
      $cltipoassefinanceiro->rh165_anousu           = DBPessoal::getAnoFolha();
      $cltipoassefinanceiro->rh165_mesusu           = DBPessoal::getMesFolha();
      $cltipoassefinanceiro->rh165_datainicio       = (!empty($oDataInicio) ? $oDataInicio->getDate() : null);
      $cltipoassefinanceiro->alterar($rh165_sequencial);

      if ($cltipoassefinanceiro->erro_status == "0") {
        throw new DBException($cltipoassefinanceiro->erro_msg);
      }
        
      $sMensagem = _M(MENSAGEM."assentamento_alterado");

    }

    if (isset($excluir)) {

      $cltipoassefinanceiro->rh165_sequencial     = $rh165_sequencial;    
      $cltipoassefinanceiro->excluir($rh165_sequencial);

      if ($cltipoassefinanceiro->erro_status == "0") {
        throw new DBException($cltipoassefinanceiro->erro_msg);
      }
      
      unset($rh165_sequencial);
      $sMensagem = _M(MENSAGEM."assentamento_excluido");
    }

    if (isset($opcao)) {
      $db_opcao = ($opcao == 'alterar')? 2 : 3;
    } 

    if (isset($rh165_sequencial) && !empty($rh165_sequencial) ) {

      $sSqlTipoAsseFinanceiro  = $cltipoassefinanceiro->sql_query($rh165_sequencial);
      $rsSqlTipoAsseFinanceiro = db_query($sSqlTipoAsseFinanceiro);

      if (!$rsSqlTipoAsseFinanceiro) {
        throw new DBException(_M(MENSAGEM . 'erro_consultar_tipoassentamento'));
      }

      if (pg_num_rows($rsSqlTipoAsseFinanceiro) == 0) {
        throw new BusinessException(_M(MENSAGEM . 'nenhum_resultado_encotrado'));
      }

      db_fieldsmemory($rsSqlTipoAsseFinanceiro, 0, "");
    }

  } catch (Exception $oException) {
    db_msgbox($oException->getMessage());
  }



  include(modification("forms/db_lancamentotipoassentamento.php"));
  if (!empty($sMensagem)) {

    db_msgbox($sMensagem);
    db_redireciona("");
    exit;
  }
?>