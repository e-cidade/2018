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

  const MENSAGEM = "recursoshumanos.pessoal.db_tipoassentamentoinformacoesexternas.";

  $cltipoasseexterno = new cl_tipoasseexterno;
  $cltipoasseexterno->rotulo->label();

  db_postmemory($_POST);
  $lErro       = false;
  $db_opcao    = 1;
  $sMsgSucesso = null;

  try {

    if(isset($incluir) || isset($alterar) || isset($excluir)) {
      
      $oDaoTipoasse = new cl_tipoasse;
      $rsTipoasse   = $oDaoTipoasse->sql_record($oDaoTipoasse->sql_query(null, "h12_codigo", null, "h12_assent = '{$rh167_tipoasse}'"));


      if($oDaoTipoasse->erro_status == '0') {
        throw new BusinessException(_M("erro_buscar_tipo_assentamento"));
      }

      $rh167_tipoasse = db_utils::fieldsMemory($rsTipoasse, 0)->h12_codigo;

      $cltipoasseexterno->rh167_sequencial          = $rh167_sequencial;
      $cltipoasseexterno->rh167_anousu              = $rh167_anousu;
      $cltipoasseexterno->rh167_mesusu              = $rh167_mesusu;
      $cltipoasseexterno->rh167_codmovsefip         = $rh167_codmovsefip;
      $cltipoasseexterno->rh167_tipoasse            = $rh167_tipoasse;
      $cltipoasseexterno->rh167_situacaoafastamento = $rh167_situacaoafastamento;
      $cltipoasseexterno->rh167_instit              = $rh167_instit;
    }

    /**
     * Verificamos se existe um afastamento criado no RH utilizando o tipo de assentamento
     * que esta sendo alterado/excluido. Se existir um afastamento na competencia atual ou 
     * com a data final null, não é permitudo alterar/excluir
     */
    if (isset($alterar) || isset($excluir)) {

      $oDaoTipoasseAssenta    = new cl_tipoasseexterno();

      $sJoinAssenta           = "inner join assenta on rh167_tipoasse = h16_assent";
      $sJoinAssenta          .= " and h16_codigo in (select h81_assenta from afastaassenta)";

      $sWhereTipoasseAssenta  = "     rh167_sequencial = {$rh167_sequencial}";
      $sWhereTipoasseAssenta .= " and rh167_anousu     = " .DBPessoal::getAnoFolha();
      $sWhereTipoasseAssenta .= " and rh167_mesusu     = " .DBPessoal::getMesFolha();
      $sWhereTipoasseAssenta .= " and (h16_dtterm is null or extract( month from h16_dtterm ) >= " .DBPessoal::getMesFolha(). ")";

      $sSqlTipoasseAssenta    = $oDaoTipoasseAssenta->sql_query_com_join(null, "h16_codigo", null, $sWhereTipoasseAssenta, $sJoinAssenta);
      $rsTipoasseAssenta      = db_query($sSqlTipoasseAssenta);

      if (pg_num_rows($rsTipoasseAssenta) > 0) {
        throw new BusinessException(_M(MENSAGEM . 'acao_nao_permitda'));
      }
    }

    if(isset($incluir)) {

      /**
       * Verificamos se o tipo de assentamento já não está configurado.
       * Se estiver não é permitido efetuar a inclusão.
       */
      $sWhereTipoAsseExterno  = "    rh167_tipoasse   = {$rh167_tipoasse} ";
      $sWhereTipoAsseExterno .= "and rh167_anousu     = {$rh167_anousu}   ";
      $sWhereTipoAsseExterno .= "and rh167_mesusu     = {$rh167_mesusu}   ";

      $sSqlTipoAsseExterno   = $cltipoasseexterno->sql_query_file(null, "rh167_sequencial", null, $sWhereTipoAsseExterno);
      $rsTipoAsseExterno     = db_query($sSqlTipoAsseExterno);

      if (pg_num_rows($rsTipoAsseExterno) > 0) {
        throw new BusinessException(_M(MENSAGEM . 'tipo_assentamento_ja_configurado'));
      }

      $cltipoasseexterno->incluir(null);

      if($cltipoasseexterno->erro_status == "0") {
        throw new BusinessException($cltipoasseexterno->erro_msg);
      }
    }

    if(isset($alterar)) {

      /**
       * Verificamos se o tipo de assentamento já não está configurado.
       * Se estiver não é permitido efetuar a inclusão.
       */
      $sWhereTipoAsseExterno  = "    rh167_tipoasse   = {$rh167_tipoasse}  ";
      $sWhereTipoAsseExterno .= "and rh167_anousu     = {$rh167_anousu}      ";
      $sWhereTipoAsseExterno .= "and rh167_mesusu     = {$rh167_mesusu}      ";
      $sWhereTipoAsseExterno .= "and rh167_sequencial <> {$rh167_sequencial} ";

      $sSqlTipoAsseExterno   = $cltipoasseexterno->sql_query_file(null, "rh167_sequencial", null, $sWhereTipoAsseExterno);
      $rsTipoAsseExterno     = db_query($sSqlTipoAsseExterno);

      if (pg_num_rows($rsTipoAsseExterno) > 0) {
        throw new BusinessException(_M(MENSAGEM . 'tipo_assentamento_ja_configurado'));
      }


      $cltipoasseexterno->alterar($rh167_sequencial);

      if($cltipoasseexterno->erro_status == "0") {
        throw new BusinessException($cltipoasseexterno->erro_msg);
      }
    }

    if(isset($excluir)) {

      $cltipoasseexterno->excluir($rh167_sequencial);

      if($cltipoasseexterno->erro_status == "0") {
        throw new BusinessException($cltipoasseexterno->erro_msg);
      }
    }

    if(isset($incluir) || isset($alterar) || isset($excluir)) {

      if($cltipoasseexterno->erro_status == "1") {

        $sMsgSucesso = $cltipoasseexterno->erro_msg;
        unset($rh167_sequencial);
      }
    }

    if (isset($opcao)) {
      $db_opcao = ($opcao == 'alterar')? 2 : 3;
    } 

    if (isset($rh167_sequencial) && !empty($rh167_sequencial) ) {

      $sCamposTipoAsseExterno  = " rh167_sequencial,";
      $sCamposTipoAsseExterno .= " rh167_anousu,";
      $sCamposTipoAsseExterno .= " rh167_mesusu,";
      $sCamposTipoAsseExterno .= " rh167_codmovsefip,";
      $sCamposTipoAsseExterno .= " h12_assent as rh167_tipoasse,";
      $sCamposTipoAsseExterno .= " rh167_situacaoafastamento,";
      $sCamposTipoAsseExterno .= " rh167_instit,";
      $sCamposTipoAsseExterno .= " h12_descr,";
      $sCamposTipoAsseExterno .= " rh166_descricao,";
      $sCamposTipoAsseExterno .= " r66_descr";

      $sSqlTipoAsseExterno  = $cltipoasseexterno->sql_query($rh167_sequencial, $sCamposTipoAsseExterno);
      $rsSqlTipoAsseExterno = db_query($sSqlTipoAsseExterno);

      if (!$rsSqlTipoAsseExterno) {
        throw new DBException(_M(MENSAGEM . 'erro_consultar_tipoassentamentoexterno'));
      }

      if (pg_num_rows($rsSqlTipoAsseExterno) == 0) {
        throw new BusinessException(_M(MENSAGEM . 'nenhum_resultado_encotrado'));
      }

      db_fieldsmemory($rsSqlTipoAsseExterno, 0, "");
    }
    
  } catch (Exception $oErro) {

    db_msgbox($oErro->getMessage());
    db_redireciona("pes4_tiposassentamentoinformcoesexternas001.php");
  }

  if($db_opcao == 1) {

    if(!isset($rh167_instit)) {
      $rh167_instit = db_getsession('DB_instit');
    }

    if(!isset($rh167_anousu)) {
      $rh167_anousu = DBPessoal::getAnoFolha();
    }

    if(!isset($rh167_mesusu)) {
      $rh167_mesusu = DBPessoal::getMesFolha();
    }

  }

  include(modification("forms/db_tipoassentamentoinformacoesexternas.php"));
?>