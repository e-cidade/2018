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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("model/ppa.model.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/exceptions/ParameterException.php"));
require_once(modification("libs/exceptions/BusinessException.php"));
require_once(modification("std/DBDate.php"));

$clcidadao 										 = new cl_cidadao();
$clcidadaoemail 							 = new cl_cidadaoemail();
$clcidadaotelefone 						 = new cl_cidadaotelefone();
$clcidadaotiporetorno 				 = new cl_cidadaotiporetorno();
$clouvidoriaatendimentocidadao = new cl_ouvidoriaatendimentocidadao();
$clcidadaocgm									 = new cl_cidadaocgm();
$cllocalatendimentofamilia     = new cl_localatendimentofamilia();
$clcidadaofamilia              = new cl_cidadaofamilia();
$clcidadaofamiliacadastrounico = new cl_cidadaofamiliacadastrounico();
$clcidadaocomposicaofamiliar   = new cl_cidadaocomposicaofamiliar();

$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["dados"]));
$oRetorno          = new stdClass;
$oRetorno->status  = 1;
$oRetorno->message = "";
$iDepartamento     = db_getsession('DB_coddepto');

/**
 * Variavel que recebe uma instancia de LocalAtendimentoSocial.
 * Buscamos o codigo do local de atendimento através do codigo do departamento
 */
$oLocalAtendimentoSocial = null;

$oDaoLocalAtendimentoSocial   = new cl_localatendimentosocial();
$sWhereLocalAtendimentoSocial = "as16_db_depart = {$iDepartamento}";
$sSqlLocalAtendimentoSocial   = $oDaoLocalAtendimentoSocial->sql_query_file(
  null,
  "as16_sequencial",
  null,
  $sWhereLocalAtendimentoSocial
);
$rsLocalAtendimentoSocial     = $oDaoLocalAtendimentoSocial->sql_record($sSqlLocalAtendimentoSocial);

if ($oDaoLocalAtendimentoSocial->numrows > 0) {

  $iLocalAtendimento       = db_utils::fieldsMemory($rsLocalAtendimentoSocial, 0)->as16_sequencial;
  $oLocalAtendimentoSocial = new LocalAtendimentoSocial($iLocalAtendimento);
}

if ($oParam->acao == "incluir") {

  db_inicio_transacao();
  $lerro = false;

  /**
   * Caso o CPF informado seja diferente de '00000000000', verificamos se ja existe um cpf cadastrado e ativo
   */
  if ($oParam->cidadao->ov02_cnpjcpf != '00000000000' && !empty($oParam->cidadao->ov02_cnpjcpf) ) {

    $rsCidadao = $clcidadao->sql_record($clcidadao->sql_query(null,null,"ov02_sequencial",null,"ov02_cnpjcpf = '".$oParam->cidadao->ov02_cnpjcpf."' and ov02_ativo is true"));

    if($clcidadao->numrows > 0){
      db_fieldsmemory($rsCidadao,0);
      $lerro = true;
      $oRetorno->message = utf8_encode("Usuário:\\n\\nJá existe um cidadão cadastrado com o CNPJ/CPF informado!\\nValores : $ov02_sequencial\\n\\nAdministrador :\\n\\nInclusão não efetuada!");
    }
  }

  $clcidadao->ov02_sequencial 			= null;
  $clcidadao->ov02_seq							= 1;
  $clcidadao->ov02_nome      				= db_stdClass::normalizeStringJson(strtoupper($oParam->cidadao->ov02_nome));
  $clcidadao->ov02_ident     				= $oParam->cidadao->ov02_ident;
  $clcidadao->ov02_cnpjcpf					= $oParam->cidadao->ov02_cnpjcpf;
  $clcidadao->ov02_endereco					=	$oParam->cidadao->ov02_endereco != ''	? utf8_decode($oParam->cidadao->ov02_endereco) : null;
  $clcidadao->ov02_numero						= $oParam->cidadao->ov02_numero 	!= ''	? utf8_decode($oParam->cidadao->ov02_numero) 	 : '';
  $clcidadao->ov02_compl						= $oParam->cidadao->ov02_compl 		!= ''	? utf8_decode($oParam->cidadao->ov02_compl)		 : null;
  $clcidadao->ov02_bairro						= $oParam->cidadao->ov02_bairro 	!= ''	? utf8_decode($oParam->cidadao->ov02_bairro) 	 : null;
  $clcidadao->ov02_munic						= $oParam->cidadao->ov02_munic 		!= ''	? utf8_decode($oParam->cidadao->ov02_munic) 	 : null;
  $clcidadao->ov02_uf								= $oParam->cidadao->ov02_uf 			!= ''	? utf8_decode($oParam->cidadao->ov02_uf) 			 : null;
  $clcidadao->ov02_situacaocidadao	= 2;
  $clcidadao->ov02_ativo 						= 't';
  $clcidadao->ov02_cep							= $oParam->cidadao->ov02_cep != '' ? $oParam->cidadao->ov02_cep : null;
  $clcidadao->ov02_data							= date('Y-m-d',db_getsession('DB_datausu'));
  $clcidadao->ov02_datanascimento   = implode('-',array_reverse(explode('/',$oParam->cidadao->ov02_datanascimento)));
  $clcidadao->ov02_sexo             = $oParam->cidadao->ov02_sexo;

  /*
   * Validação para evitar duplicidades quando cadastro realizado através da tela do social.
   * Cadastros através de outras telas não passarão por esta validação.
   * Validação utilizando campos ov02_datanascimento e ov02_nome
   */
  if (isset($oParam->cidadao->lTelaSocial) &&  $oParam->cidadao->lTelaSocial) {

    $iResponsavelFamilia     = $oParam->cidadao->iResponsavelFamilia;
    $iLocalAtendimentoSocial = $oParam->cidadao->iLocalatendimentosocial;
    $iTipoFamiliar           = $oParam->cidadao->iTipoFamiliar;

    $sWhereValidaExistenciaCidadao = "ov02_datanascimento = '{$clcidadao->ov02_datanascimento}' and ov02_nome = '{$clcidadao->ov02_nome}'";
    $sSqlValidaExistenciaCidadao   = $clcidadao->sql_query(null, null, 'ov02_sequencial', null, $sWhereValidaExistenciaCidadao);
    $clcidadao->sql_record($sSqlValidaExistenciaCidadao);

    if ($clcidadao->numrows > 0) {

      $lerro             = true;
      $oDataNascimento   = new DBDate($clcidadao->ov02_datanascimento);
      $oRetorno->message = utf8_encode("Já existe um cidadão cadastrado com o Nome e Data de Nascimento informados!
                                      \\nValores : {$clcidadao->ov02_nome}, {$oDataNascimento->convertTo(DBDate::DATA_PTBR)}
                                      \\nInclusão não efetuada!");
    }
  }

  if(!$lerro){
    $clcidadao->incluir(null,0);

    if($clcidadao->erro_status==1){

      $lerro = false;
      $oRetorno->message = urlencode($clcidadao->erro_msg);

    }else{

      $lerro = true;
      $oRetorno->message = urlencode($clcidadao->erro_msg);
      //$oRetorno->status  = 0;
    }
  }

  if ( !$lerro ) {
    if( trim($oParam->cidadao->ov03_numcgm) !="" ){
      $clcidadaocgm->ov03_cidadao  = $clcidadao->ov02_sequencial;
      $clcidadaocgm->ov03_seq      = $clcidadao->ov02_seq;
      $clcidadaocgm->ov03_numcgm   = $oParam->cidadao->ov03_numcgm;
      $clcidadaocgm->incluir(null);

      if($clcidadaocgm->erro_status == '0'){
        $lerro = true;
        $oRetorno->message = urlencode($clcidadaocgm->erro_msg);
      } else {
        $lerro = false;
        $oRetorno->message = urlencode($clcidadaocgm->erro_msg);
      }
    }
  }

  //Verifica o tipo de retorno e os inseri.
  if(is_array($oParam->tiporetorno) && !$lerro){
    $iNumRows = count($oParam->tiporetorno);

    if ($iNumRows == 0 && !$oParam->cidadao->lTelaSocial) {

      $clcidadaotiporetorno->ov04_seq			 		= $clcidadao->ov02_seq;
      $clcidadaotiporetorno->ov04_cidadao  		= $clcidadao->ov02_sequencial;
      $clcidadaotiporetorno->ov04_tiporetorno	= 1;
      $clcidadaotiporetorno->incluir(null);

      if($clcidadaotiporetorno->erro_status == 0) {

        $oRetorno->message = urlencode($clcidadaotiporetorno->erro_msg);
        $lerro             = true;
        break;
      }
    } else {

      for($i=0; $i < $iNumRows; $i++){

        $clcidadaotiporetorno->ov04_seq			 		= $clcidadao->ov02_seq;
        $clcidadaotiporetorno->ov04_cidadao  		= $clcidadao->ov02_sequencial;
        $clcidadaotiporetorno->ov04_tiporetorno	= $oParam->tiporetorno[$i]->ov04_tiporetorno;
        if(!$lerro){
          $clcidadaotiporetorno->incluir(null);
          if($clcidadaotiporetorno->erro_status==0){
            $oRetorno->message = urlencode($clcidadaotiporetorno->erro_msg);
            $lerro = true;
            break;
          }
        }
      }
    }
  }

  //Verifica se existem emails e os inseri.
  if(is_array($oParam->emails) && !$lerro){
    $iNumRows = count($oParam->emails);
    for($i=0; $i < $iNumRows; $i++){

      $clcidadaoemail->ov08_seq			 = $clcidadao->ov02_seq;
      $clcidadaoemail->ov08_cidadao  = $clcidadao->ov02_sequencial;
      $clcidadaoemail->ov08_email		 = utf8_decode($oParam->emails[$i]->ov08_email);
      $clcidadaoemail->ov08_principal= ($oParam->emails[$i]->ov08_principal == 't' ? 'true' : 'false');
      if(!$lerro){
        $clcidadaoemail->incluir(null);
        if($clcidadaoemail->erro_status==0){
          $oRetorno->message = urldecode($clcidadaoemail->erro_msg);
          $lerro = true;
          break;
        }
      }
    }
  }
  //Verifica se existem telefones e os inseri.
  if(is_array($oParam->telefones) && !$lerro){
    $iNumRows = count($oParam->telefones);
    for($i=0; $i < $iNumRows; $i++){
      $clcidadaotelefone->ov07_sequencial		=	null;
      $clcidadaotelefone->ov07_seq		 			= $clcidadao->ov02_seq;
      $clcidadaotelefone->ov07_cidadao  		= $clcidadao->ov02_sequencial;
      $clcidadaotelefone->ov07_ddd		 			= $oParam->telefones[$i]->ov07_ddd != ""   ? utf8_decode($oParam->telefones[$i]->ov07_ddd)   : null;
      $clcidadaotelefone->ov07_numero		 		= $oParam->telefones[$i]->ov07_numero;
      $clcidadaotelefone->ov07_ramal		 		= $oParam->telefones[$i]->ov07_ramal != "" ? utf8_decode($oParam->telefones[$i]->ov07_ramal) : null;
      $clcidadaotelefone->ov07_obs		 			= $oParam->telefones[$i]->ov07_obs != ""   ? utf8_decode($oParam->telefones[$i]->ov07_obs)   : null;
      $clcidadaotelefone->ov07_tipotelefone	= $oParam->telefones[$i]->ov07_tipotelefone;
      $clcidadaotelefone->ov07_principal		= ($oParam->telefones[$i]->ov07_principal == 't' ? 'true' : 'false');

      if(!$lerro){
        $clcidadaotelefone->incluir(null);
        if($clcidadaotelefone->erro_status==0){
          $oRetorno->message = urlencode($clcidadaotelefone->erro_msg);
          $lerro = true;
          break;
        }
      }
    }
  }

  if ($lerro) {
    //$oRetorno->message = urlencode("Usuário:\\n\\n Falha no Cadastro do Cidadão!\\n Valores : = $clcidadao->ov02_sequencial\\n\\nAdministrador:");
    $oRetorno->status  = 1;
  } else {
    $oRetorno->message = urlencode("Usuário:\\n\\n Cadastro do Cidadão Efetuado com sucesso!\\n Valores : = $clcidadao->ov02_sequencial\\n\\nAdministrador:");

    if ( $oParam->lAtendimento ) {
      $oRetorno->iCodCidadao = $clcidadao->ov02_sequencial;
      $oRetorno->iSeqCidadao = $clcidadao->ov02_seq;
      $oRetorno->status  = 4;
    } else {

      $oRetorno->iCodCidadao = $clcidadao->ov02_sequencial;
      $oRetorno->iSeqCidadao = $clcidadao->ov02_seq;
      $oRetorno->sNome       = urlencode($clcidadao->ov02_nome);
      $oRetorno->status      = 0;
    }
  }

  /**
   * Caso os dados venham da tela do social, salva nas seguintes tabelas.
   */
  if (isset($oParam->cidadao->lTelaSocial) && $oParam->cidadao->lTelaSocial && !$lerro) {

    $oRetorno->lTelaSocial = true;

    if (isset($iResponsavelFamilia) && $iResponsavelFamilia > 0 && !$lerro) {

      $clcidadaocomposicaofamiliar->as03_cidadao        = $oRetorno->iCodCidadao;
      $clcidadaocomposicaofamiliar->as03_cidadao_seq    = $oRetorno->iSeqCidadao;
      $clcidadaocomposicaofamiliar->as03_cidadaofamilia = $iResponsavelFamilia;
      $clcidadaocomposicaofamiliar->as03_tipofamiliar   = $iTipoFamiliar;
      $clcidadaocomposicaofamiliar->incluir(null);

      if ($clcidadaocomposicaofamiliar->erro_status == 0) {

        $oRetorno->message = urlencode($clcidadaocomposicaofamiliar->erro_msg);
        $lerro             = true;
        break;
      }
      $oRetorno->iTipoFamiliar = $iTipoFamiliar;
    } else {

      $clcidadaofamilia->as04_aparelhoeletricocontinuo = 'false';
      $clcidadaofamilia->as04_dataatualizacao          = date("d-m-Y",db_getsession('DB_datausu'));
      $clcidadaofamilia->as04_dataentrevista           = date("d-m-Y",db_getsession('DB_datausu'));
      $clcidadaofamilia->as04_rendafamiliar            = '0';
      $clcidadaofamilia->incluir(null);
      $iCidadaoFamilia = $clcidadaofamilia->as04_sequencial;

      if ($clcidadaofamilia->erro_status == 0) {

        $oRetorno->message = urlencode($clcidadaofamilia->erro_msg);
        $lerro             = true;
        break;
      }

      $clcidadaocomposicaofamiliar->as03_cidadao        = $oRetorno->iCodCidadao;
      $clcidadaocomposicaofamiliar->as03_cidadao_seq    = $oRetorno->iSeqCidadao;
      $clcidadaocomposicaofamiliar->as03_cidadaofamilia = $clcidadaofamilia->as04_sequencial;
      $clcidadaocomposicaofamiliar->as03_tipofamiliar   = '0';
      $clcidadaocomposicaofamiliar->incluir(null);

      if ($clcidadaocomposicaofamiliar->erro_status == 0) {

        $oRetorno->message = urlencode($clcidadaocomposicaofamiliar->erro_msg);
        $lerro             = true;
        break;
      }

      $oDataVinculo                = new DBDate(date("d/m/Y"));
      $oUsuario                    = new UsuarioSistema(db_getsession("DB_id_usuario"));
      $oDaoLocalAtendimentoFamilia = new LocalAtendimentoFamilia();

      $oDaoLocalAtendimentoFamilia->setLocalAtendimentoSocial($oLocalAtendimentoSocial);
      $oDaoLocalAtendimentoFamilia->setFamilia(new Familia($iCidadaoFamilia));
      $oDaoLocalAtendimentoFamilia->setDataVinculo($oDataVinculo);
      $oDaoLocalAtendimentoFamilia->setUsuario($oUsuario);
      $oDaoLocalAtendimentoFamilia->salvar();
      $oRetorno->iTipoFamiliar = 0;
    }
  }

  db_fim_transacao($lerro);

} else if($oParam->acao == "alterar") {

  //Abrir transação aqui
  db_inicio_transacao();
  $lerro = false;

  if ($oParam->cidadao->ov02_sequencial != "" && $oParam->cidadao->ov02_sequencial != null) {

    if (isset($oParam->cidadao->lTelaSocial) && $oParam->cidadao->lTelaSocial) {

      $iResponsavelFamilia     = $oParam->cidadao->iResponsavelFamilia;
      $iLocalAtendimentoSocial = $oParam->cidadao->iLocalatendimentosocial;
      $iTipoFamiliar           = $oParam->cidadao->iTipoFamiliar;
    }
    $campos = "ov02_sequencial,ov02_seq,ov02_situacaocidadao";
    $sWhere = "ov02_sequencial = ".$oParam->cidadao->ov02_sequencial." and  ov02_ativo is true";

    $sSqlCidadao = $clcidadao->sql_query($oParam->cidadao->ov02_sequencial, null, $campos, null, $sWhere);
    $rsCidadao 	 = $clcidadao->sql_record($sSqlCidadao);
    $iNumRows 	 = $clcidadao->numrows;
    if($iNumRows > 0){

      $oAlteraCidadao = db_utils::fieldsMemory($rsCidadao,0);
      if ((isset($oParam->cidadao->lTelaSocial) && !$oParam->cidadao->lTelaSocial) ||
        !isset($oParam->cidadao->lTelaSocial)) {

        $clcidadao->ov02_ativo = 'false';
        $clcidadao->alterar_where(null,
                                  null,
                                  "ov02_sequencial = {$oAlteraCidadao->ov02_sequencial}
  			                           and ov02_seq = $oAlteraCidadao->ov02_seq");

        if($clcidadao->erro_status == "0"){
          $lerro = true;
          $oRetorno->message = urlencode($clcidadao->erro_msg);
        }
      }
      // Se não deu erro na alteração prossigo para inserir um novo registro
      if (!$lerro) {

        $oAlteraCidadao->ov02_seq += 1 ;
        $clcidadao->ov02_sequencial 			= $oAlteraCidadao->ov02_sequencial;
        $clcidadao->ov02_seq							= $oAlteraCidadao->ov02_seq;
        $clcidadao->ov02_nome      				= utf8_decode(strtoupper($oParam->cidadao->ov02_nome));
        $clcidadao->ov02_ident     				= utf8_decode($oParam->cidadao->ov02_ident);
        $clcidadao->ov02_cnpjcpf					= $oParam->cidadao->ov02_cnpjcpf;
        $clcidadao->ov02_endereco					=	$oParam->cidadao->ov02_endereco != ''	? utf8_decode($oParam->cidadao->ov02_endereco): null;
        $clcidadao->ov02_numero						= $oParam->cidadao->ov02_numero 	!= ''	? utf8_decode($oParam->cidadao->ov02_numero) 	: '';
        $clcidadao->ov02_compl						= $oParam->cidadao->ov02_compl 		!= ''	? utf8_decode($oParam->cidadao->ov02_compl)		: null;
        $clcidadao->ov02_bairro						= $oParam->cidadao->ov02_bairro 	!= ''	? utf8_decode($oParam->cidadao->ov02_bairro) 	: null;
        $clcidadao->ov02_munic						= $oParam->cidadao->ov02_munic 		!= ''	? utf8_decode($oParam->cidadao->ov02_munic) 	: null;
        $clcidadao->ov02_uf								= $oParam->cidadao->ov02_uf 			!= ''	? utf8_decode($oParam->cidadao->ov02_uf) 			: null;
        $clcidadao->ov02_situacaocidadao	= 2;
        $clcidadao->ov02_ativo 						= 'true';
        $clcidadao->ov02_cep							= $oParam->cidadao->ov02_cep 			!= '' ? $oParam->cidadao->ov02_cep			: null;
        $clcidadao->ov02_data							= date('Y-m-d',db_getsession('DB_datausu'));
        $clcidadao->ov02_datanascimento   = implode('-',array_reverse(explode('/',$oParam->cidadao->ov02_datanascimento)));
        $clcidadao->ov02_sexo             = $oParam->cidadao->ov02_sexo;

        /**
         * Caso a origem do formulario seja o cadastro de leitores (Escola->Biblioteca), apenas alteramos o registro ao
         * inves de incluir um novo
         */
        if ($oParam->cidadao->lOrigemLeitor || $oParam->cidadao->lTelaSocial) {

          $clcidadao->ov02_seq = 1;
          $clcidadao->alterar($oAlteraCidadao->ov02_sequencial, $clcidadao->ov02_seq);

        } else {
          $clcidadao->incluir($oAlteraCidadao->ov02_sequencial,null);
        }
        if ($clcidadao->erro_status == "0") {

          $lerro               = true;
          $oRetorno->message   = urlencode($clcidadao->erro_msg);
          $clcidadao->ov02_seq = $oAlteraCidadao->ov02_seq;
        } else {

          $lerro             = false;
          $oRetorno->message = urlencode($clcidadao->erro_msg);
        }

        if (is_array($oParam->tiporetorno) && !$lerro && !$oParam->cidadao->lOrigemLeitor) {

          if ($oParam->cidadao->lTelaSocial) {

            $sWhereCidadaoRetorno  = "ov04_seq = {$clcidadao->ov02_seq} ";
            $sWhereCidadaoRetorno .= " and ov04_cidadao = {$clcidadao->ov02_sequencial}";
            $clcidadaotiporetorno->excluir(null, $sWhereCidadaoRetorno);


            if ($clcidadaotiporetorno->erro_status == 0) {

              $lerro             = true;
              $oRetorno->message = urlencode($clcidadaotiporetorno->erro_msg);
            }

          }

          $iNumRows = count($oParam->tiporetorno);
          for ($i = 0; $i < $iNumRows; $i++) {

            $clcidadaotiporetorno->ov04_seq			 		= $clcidadao->ov02_seq;
            $clcidadaotiporetorno->ov04_cidadao  		= $clcidadao->ov02_sequencial;
            $clcidadaotiporetorno->ov04_tiporetorno	= $oParam->tiporetorno[$i]->ov04_tiporetorno;

            if (!$lerro) {

              $clcidadaotiporetorno->incluir(null);
              if ($clcidadaotiporetorno->erro_status == 0) {

                $oRetorno->message = urlencode($clcidadaotiporetorno->erro_msg);
                $lerro             = true;
                break;
              }
            }
          }
        }

        //Verifica se existem emails e os inseri.
        if (is_array($oParam->emails) && !$lerro) {

          $iNumRows = count($oParam->emails);

          /**
           * Caso a origem do formulario seja o cadastro de leitores (Escola->Biblioteca),
           * pelo modulo social, removemos os registros existentes e incluimos novamente
           */
          if (($oParam->cidadao->lOrigemLeitor || $oParam->cidadao->lTelaSocial) && $iNumRows > 0) {

            $oDaoCidadaoEmail   = db_utils::getDao("cidadaoemail");
            $sWhereCidadaoEmail = "ov08_cidadao = {$clcidadao->ov02_sequencial} AND ov08_seq = {$clcidadao->ov02_seq}";
            $sSqlCidadaoEmail   = $oDaoCidadaoEmail->sql_query_file(null, "ov08_sequencial", null, $sWhereCidadaoEmail);
            $rsCidadaoEmail     = $oDaoCidadaoEmail->sql_record($sSqlCidadaoEmail);
            $iTotalCidadaoEmail = $oDaoCidadaoEmail->numrows;

            if ($iTotalCidadaoEmail > 0) {

              for ($iContadorCidadaoEmail = 0; $iContadorCidadaoEmail < $iTotalCidadaoEmail; $iContadorCidadaoEmail++) {

                $iSequencialCidadaoEmail  = db_utils::fieldsMemory($rsCidadaoEmail, $iContadorCidadaoEmail)->ov08_sequencial;
                $oDaoCidadaoEmailExclusao = db_utils::getDao("cidadaoemail");
                $oDaoCidadaoEmailExclusao->excluir($iSequencialCidadaoEmail);
              }
            }
          }

          for ($i = 0; $i < $iNumRows; $i++) {

            $clcidadaoemail->ov08_seq			  = $clcidadao->ov02_seq;
            $clcidadaoemail->ov08_cidadao   = $clcidadao->ov02_sequencial;
            $clcidadaoemail->ov08_email		  = utf8_decode($oParam->emails[$i]->ov08_email);
            $clcidadaoemail->ov08_principal = ($oParam->emails[$i]->ov08_principal == 't' ? 'true' : 'false');

            if (!$lerro) {

              $clcidadaoemail->incluir(null);
              if ($clcidadaoemail->erro_status == 0) {

                $oRetorno->message = urlencode($clcidadaoemail->erro_msg);
                $lerro             = true;
                break;
              }
            }

          }
        }

        //Verifica se existem telefones e os inseri.
        if (is_array($oParam->telefones) && !$lerro) {

          $iNumRows = count($oParam->telefones);

          /**
           * Caso a origem do formulario seja o cadastro de leitores (Escola->Biblioteca) ou o cadastro do cidadao
           * pelo modulo social, removemos os registros
           * existentes e incluimos novamente
           */
          if (($oParam->cidadao->lOrigemLeitor || $oParam->cidadao->lTelaSocial) && $iNumRows > 0) {

            $oDaoCidadaoTelefone   = db_utils::getDao("cidadaotelefone");
            $sWhereCidadaoTelefone = "ov07_cidadao = {$clcidadao->ov02_sequencial} AND ov07_seq = {$clcidadao->ov02_seq}";
            $sSqlCidadaoTelefone   = $oDaoCidadaoTelefone->sql_query_file(null, "ov07_sequencial", null, $sWhereCidadaoTelefone);
            $rsCidadaoTelefone     = $oDaoCidadaoTelefone->sql_record($sSqlCidadaoTelefone);
            $iTotalCidadaoTelefone = $oDaoCidadaoTelefone->numrows;

            if ($iTotalCidadaoTelefone > 0) {

              for ($iContadorCidadaoTelefone = 0; $iContadorCidadaoTelefone < $iTotalCidadaoTelefone; $iContadorCidadaoTelefone++) {

                $iSequencialCidadaoTelefone  = db_utils::fieldsMemory($rsCidadaoTelefone, $iContadorCidadaoTelefone)->ov07_sequencial;
                $oDaoCidadaoTelefoneExclusao = db_utils::getDao("cidadaotelefone");
                $oDaoCidadaoTelefoneExclusao->excluir($iSequencialCidadaoTelefone);
              }
            }
          }

          for ($i = 0; $i < $iNumRows; $i++) {

            $clcidadaotelefone->ov07_sequencial		=	null;
            $clcidadaotelefone->ov07_seq		 			= $clcidadao->ov02_seq;
            $clcidadaotelefone->ov07_cidadao  		= $clcidadao->ov02_sequencial;
            $clcidadaotelefone->ov07_ddd		 			= $oParam->telefones[$i]->ov07_ddd != ""   ? utf8_decode($oParam->telefones[$i]->ov07_ddd)   : null;
            $clcidadaotelefone->ov07_numero		 		= $oParam->telefones[$i]->ov07_numero;
            $clcidadaotelefone->ov07_ramal		 		= $oParam->telefones[$i]->ov07_ramal != "" ? utf8_decode($oParam->telefones[$i]->ov07_ramal) : null;
            $clcidadaotelefone->ov07_obs		 			= $oParam->telefones[$i]->ov07_obs != ""   ? utf8_decode($oParam->telefones[$i]->ov07_obs)   : null;
            $clcidadaotelefone->ov07_tipotelefone	= $oParam->telefones[$i]->ov07_tipotelefone;
            $clcidadaotelefone->ov07_principal		= ($oParam->telefones[$i]->ov07_principal == 't' ? 'true' : 'false');

            if(!$lerro){

              $clcidadaotelefone->incluir(null);
              if ($clcidadaotelefone->erro_status == 0) {

                $oRetorno->message = urlencode($clcidadaotelefone->erro_msg);
                $lerro             = true;
                break;
              }
            }
          }
        }
      }

      /**
       * Caso os dados venham da tela do módulo social, altera os dados nas seguintes tabelas.
       */
      if (isset($oParam->cidadao->lTelaSocial) && $oParam->cidadao->lTelaSocial && !$lerro) {

        if (isset($iResponsavelFamilia) && $iResponsavelFamilia > 0 && !$lerro) {

          $clcidadaocomposicaofamiliar->as03_sequencial     = $oParam->cidadao->as03_sequencial;
          $clcidadaocomposicaofamiliar->as03_cidadao        = $clcidadao->ov02_sequencial;
          $clcidadaocomposicaofamiliar->as03_cidadao_seq    = $clcidadao->ov02_seq;
          $clcidadaocomposicaofamiliar->as03_cidadaofamilia = $iResponsavelFamilia;
          $clcidadaocomposicaofamiliar->as03_tipofamiliar   = $iTipoFamiliar;
          $clcidadaocomposicaofamiliar->alterar($oParam->cidadao->as03_sequencial);

          if ($clcidadaocomposicaofamiliar->erro_status == 0) {

            $oRetorno->message = urlencode($clcidadaocomposicaofamiliar->erro_msg);
            $lerro             = true;
            break;
          }

        } else {

          $clcidadaofamilia->as04_sequencial               = $oParam->cidadao->iCidadaoFamilia;
          $clcidadaofamilia->as04_aparelhoeletricocontinuo = 'false';
          $clcidadaofamilia->as04_dataatualizacao          = date("d-m-Y",db_getsession('DB_datausu'));
          $clcidadaofamilia->as04_dataentrevista           = date("d-m-Y",db_getsession('DB_datausu'));
          $clcidadaofamilia->as04_rendafamiliar            = '0';
          $clcidadaofamilia->alterar($oParam->cidadao->iCidadaoFamilia);

          if ($clcidadaofamilia->erro_status == 0) {

            $oRetorno->message = urlencode($clcidadaofamilia->erro_msg);
            $lerro             = true;
            break;
          }

          $clcidadaocomposicaofamiliar->as03_sequencial     = $oParam->cidadao->as03_sequencial;
          $clcidadaocomposicaofamiliar->as03_cidadao        = $clcidadao->ov02_sequencial;
          $clcidadaocomposicaofamiliar->as03_cidadao_seq    = $clcidadao->ov02_seq;
          $clcidadaocomposicaofamiliar->as03_cidadaofamilia = $clcidadaofamilia->as04_sequencial;
          $clcidadaocomposicaofamiliar->as03_tipofamiliar   = '0';
          $clcidadaocomposicaofamiliar->alterar($oParam->cidadao->as03_sequencial);

          if ($clcidadaocomposicaofamiliar->erro_status == 0) {

            $oRetorno->message = urlencode($clcidadaocomposicaofamiliar->erro_msg);
            $lerro             = true;
            break;
          }

          $sWhereLocalAtendimentoFamilia = "as23_cidadaofamilia = {$oParam->cidadao->iCidadaoFamilia}";
          $sSqlLocalAtendimentoFamilia   = $cllocalatendimentofamilia->sql_query_file(
            null,
            "as23_sequencial",
            null,
            $sWhereLocalAtendimentoFamilia
          );
          $rsLocalAtendimentoFamilia = $cllocalatendimentofamilia->sql_record($sSqlLocalAtendimentoFamilia);

          if ($cllocalatendimentofamilia->numrows == 0) {

            $oDataVinculo                = new DBDate(db_getsession("DB_datausu"));
            $oUsuario                    = new UsuarioSistema(db_getsession("DB_id_usuario"));
            $oDaoLocalAtendimentoFamilia = new LocalAtendimentoFamilia();
            $oDaoLocalAtendimentoFamilia->setLocalAtendimentoSocial($oLocalAtendimentoSocial);
            $oDaoLocalAtendimentoFamilia->setFamilia(new Familia($oParam->cidadao->iCidadaoFamilia));
            $oDaoLocalAtendimentoFamilia->setDataVinculo($oDataVinculo);
            $oDaoLocalAtendimentoFamilia->setUsuario($oUsuario);
            $oDaoLocalAtendimentoFamilia->salvar();
          }
        }
      }

      if (!$lerro) {

        $oRetorno->message = urlencode("Usuário:\\n\\n Alteração do Cadastro do Cidadão Efetuado com sucesso!\\n Valores : $clcidadao->ov02_sequencial	\\n\\nAdministrador:");
        $oRetorno->cidadao = $clcidadao->ov02_sequencial;

        if ( $oParam->lAtendimento ) {

          $oRetorno->iCodCidadao = $clcidadao->ov02_sequencial;
          $oRetorno->iSeqCidadao = $clcidadao->ov02_seq;
          $oRetorno->status      = 4;
        } else {
          $oRetorno->status	= 2;
        }
      } else {
        $oRetorno->status	= 2;
      }
    }
  } else {

    $oRetorno->status	 = 1;
    $oRetorno->message = urlencode("\\n\\nUsuário:\\n\\nFalha ao localizar os dados do cidadao = $oParam->cidadao->ov02_sequencial\\n\\nAdministrador:");
  }

  db_fim_transacao($lerro);

}else if ($oParam->acao == "excluir"){
  db_inicio_transacao();
  $lerro = false;

  $rsOuvidoriaAtendimentoCidadao = $clouvidoriaatendimentocidadao->sql_record($clouvidoriaatendimentocidadao->sql_query_file(null,"ov10_sequencial",null,"ov10_cidadao = ".$oParam->cidadao->ov02_sequencial));
  if($clouvidoriaatendimentocidadao->numrows == 0){

    if($oParam->cidadao->ov02_sequencial != "" && $oParam->cidadao->ov02_sequencial != null){

      $campos = "ov02_sequencial,ov02_seq,ov02_situacaocidadao";
      $sWhere = "ov02_sequencial = ".$oParam->cidadao->ov02_sequencial." and  ov02_ativo is true";

      $rsCidadao 	= $clcidadao->sql_record($clcidadao->sql_query($oParam->cidadao->ov02_sequencial,null,$campos,null,$sWhere));

      $iNumRows 	= $clcidadao->numrows;

      if($iNumRows > 0){

        $oAlteraCidadao = db_utils::fieldsMemory($rsCidadao,0);
        //var_dump($oAlteraCidadao);
        $clcidadao->ov02_ativo = 'false';
        $clcidadao->ov02_sequencial = $oAlteraCidadao->ov02_sequencial;
        $clcidadao->ov02_seq			  = $oAlteraCidadao->ov02_seq;
        $clcidadao->alterar($oAlteraCidadao->ov02_sequencial,$oAlteraCidadao->ov02_seq);

        if($clcidadao->erro_status == 0 && $clcidadao->numrows_alterar != 1){

          $lerro = true;
          $oRetorno->message = urlencode($clcidadao->erro_msg);

        }else{
          $oRetorno->message = urlencode("Usuário:\\n\\n Exclusão do Cadastro do Cidadão Efetuada com sucesso!\\n Valores : $clcidadao->ov02_sequencial	\\n\\nAdministrador:");

          if ( $oParam->lAtendimento ) {
            $oRetorno->iCodCidadao = $clcidadao->ov02_sequencial;
            $oRetorno->iSeqCidadao = $clcidadao->ov02_seq;
            $oRetorno->status = 4;
          } else {
            $oRetorno->status	= 3;
          }
        }
      }
    }

  }else{

    $oRetorno->status	= 1;
    $oRetorno->message = urlencode("Usuário:\\n\\n Cidadão não excluído! \\n\\nExistem atendimentos vinculados a este cidadão.\\n\\nAdministrador:");

  }
  db_fim_transacao($lerro);

}else if ($oParam->acao == "pesquisar"){

  if($oParam->chave != ''){

    $campos  = "distinct ov02_sequencial,ov02_seq,ov02_nome,ov02_ident,ov02_cnpjcpf,ov02_endereco,ov02_numero,ov02_compl,";
    $campos .= "ov02_bairro,ov02_munic,ov02_uf,ov02_situacaocidadao,ov02_ativo,ov02_cep,ov02_sexo,ov02_datanascimento,";
    $campos .= "ov03_numcgm,z01_nome";

    $sWhere = "ov02_sequencial = ".$oParam->chave." and ov02_ativo is true";
    $rsCidadao = $clcidadao->sql_record($clcidadao->sql_query_cidadaotiporetornocgm(null,null,$campos,null,$sWhere));
    $iNumRows = pg_num_rows($rsCidadao);

    if ($iNumRows>0){

      $aCidadao = array();
      foreach (db_utils::getCollectionByRecord($rsCidadao,false,false,true) as $oDadosCidadao) {

        $oCidadao = new stdClass();
        $oCidadao->ov02_sequencial     = $oDadosCidadao->ov02_sequencial;
        $oCidadao->ov02_seq            = $oDadosCidadao->ov02_seq;
        $oCidadao->ov02_nome           = ($oDadosCidadao->ov02_nome);
        $oCidadao->ov02_ident          = $oDadosCidadao->ov02_ident;
        $oCidadao->ov02_cnpjcpf        = $oDadosCidadao->ov02_cnpjcpf;
        $oCidadao->ov02_endereco       = db_stdClass::normalizeStringJson($oDadosCidadao->ov02_endereco);
        $oCidadao->ov02_numero         = $oDadosCidadao->ov02_numero;
        $oCidadao->ov02_compl          = $oDadosCidadao->ov02_compl;
        $oCidadao->ov02_bairro         = $oDadosCidadao->ov02_bairro;
        $oCidadao->ov02_munic          = $oDadosCidadao->ov02_munic;
        $oCidadao->ov02_uf             = $oDadosCidadao->ov02_uf;
        $oCidadao->ov02_situacao       = $oDadosCidadao->ov02_situacaocidadao;
        $oCidadao->ov02_ativo          = $oDadosCidadao->ov02_ativo;
        $oCidadao->ov02_cep            = $oDadosCidadao->ov02_cep;
        $oCidadao->ov03_numcgm         = $oDadosCidadao->ov03_numcgm;
        $oCidadao->ov02_sexo           = $oDadosCidadao->ov02_sexo;
        $oCidadao->ov02_datanascimento = $oDadosCidadao->ov02_datanascimento;
        $oCidadao->z01_nome            = $oDadosCidadao->z01_nome;

        $oDaoComposicaoFamiliar = db_utils::getDao('cidadaocomposicaofamiliar');
        $sSqlComposicaoFamiliar = $oDaoComposicaoFamiliar->sql_query(null, '*', null, "as03_cidadao = {$oDadosCidadao->ov02_sequencial}");
        $rsComposicaoFamiliar   = $oDaoComposicaoFamiliar->sql_record($sSqlComposicaoFamiliar);

        if ($oDaoComposicaoFamiliar->numrows > 0) {

          $oComposicaoFamiliar       = db_utils::fieldsMemory($rsComposicaoFamiliar, 0);
          $oCidadao->iTipoFamiliar   = $oComposicaoFamiliar->as03_tipofamiliar;
          $oCidadao->iCidadaoFamilia = $oComposicaoFamiliar->as03_cidadaofamilia;
          $oCidadao->as03_sequencial = $oComposicaoFamiliar->as03_sequencial;
        }

        if (isset($oParam->lTelaSocial) && $oParam->lTelaSocial) {

          $oCidadao->lFamliliaDepartamento = false;

          $sWhereLocalatendimentoFamilia  = "as23_cidadaofamilia = {$oCidadao->iCidadaoFamilia}";
          $sWhereLocalatendimentoFamilia .= " and as16_db_depart = {$iDepartamento}";
          $sSqlLocalAtendimentoFamilia    = $cllocalatendimentofamilia->sql_query(null,'*',null,$sWhereLocalatendimentoFamilia);
          $rsLocalAtendimentoFamilia      = $cllocalatendimentofamilia->sql_record($sSqlLocalAtendimentoFamilia);

          if ($cllocalatendimentofamilia->numrows > 0) {

            $oLocalAtendimento               = db_utils::fieldsMemory($rsLocalAtendimentoFamilia, 0);
            $oCidadao->lFamliliaDepartamento = true;
            $oCidadao->as23_sequencial       = $oLocalAtendimento->as23_sequencial;
          }
        }
        $aCidadao[] = $oCidadao;
      }
      $oRetorno->cidadao     = $aCidadao;
      $oRetorno->status	     = 0;
      $oRetorno->tiporetorno = array();

      $dbwhere       = "ov04_seq = ".$oRetorno->cidadao[0]->ov02_seq." and ov04_cidadao = ".$oRetorno->cidadao[0]->ov02_sequencial;
      $rsTipoRetorno = $clcidadaotiporetorno->sql_record($clcidadaotiporetorno->sql_query_file(null,"*",null,$dbwhere));
      $iNumRows      = $clcidadaotiporetorno->numrows;

      if ($iNumRows > 0){

        $oRetorno->tiporetorno = db_utils::getCollectionByRecord($rsTipoRetorno,false,false,true);
        $oRetorno->status	     = 0;
      }

      //aqui busca os telefones do cidadao
      $campos   = "ov07_numero,ov07_tipotelefone,ov07_ddd,ov07_ramal,ov07_obs,ov07_principal,";
      $campos	 .= "case when ov07_principal is true then 'Sim' else 'Não' end as descrprincipal, ";
      $campos	 .= "ov23_descricao as descricao";
      $dbwhere 	= "ov07_seq = ".$oRetorno->cidadao[0]->ov02_seq." and ov07_cidadao = ".$oRetorno->cidadao[0]->ov02_sequencial;
      $rsCidadaoTelefones = $clcidadaotelefone->sql_record($clcidadaotelefone->sql_query_telefonetipo(null,$campos,null,$dbwhere));
      $iNumRows           = $clcidadaotelefone->numrows;

      if ($iNumRows > 0) {

        $oRetorno->cidadaotelefones = db_utils::getCollectionByRecord($rsCidadaoTelefones,false,false,true);
        $oRetorno->status	          = 0;
      }else {
        $oRetorno->cidadaotelefones	= array();
      }
      //Aqui busca os emails do cidadao
      $campos 	 = "ov08_email,ov08_principal,case when ov08_principal is true then 'Sim' else 'Não' end as descricao";

      $dbwhere 	= "ov08_seq = ".$oRetorno->cidadao[0]->ov02_seq." and ov08_cidadao = ".$oRetorno->cidadao[0]->ov02_sequencial;
      $rsCidadaoEmails = $clcidadaoemail->sql_record($clcidadaoemail->sql_query_file(null,$campos,null,$dbwhere));
      $iNumRows = $clcidadaoemail->numrows;

      if ($iNumRows>0){
        $oRetorno->cidadaoemails = db_utils::getCollectionByRecord($rsCidadaoEmails,false,false,true);
        $oRetorno->status	       = 0;
      }else {
        $oRetorno->cidadaoemails	= array();
      }

      $oRetorno->status	= 0;

    }

  }else{
    $oRetorno->status	= 1;
    $oRetorno->message = urlencode("\\n\\nUsuário:\\n\\nFalha ao localizar os dados do cidadao = $oParam->cidadao->ov02_sequencial\\n\\nAdministrador:");
    //$oRetorno->message = "Falha ao localizar os dados do cidadão de código = $oParam->chave";
  }

} else if ($oParam->acao == "pesquisaCGM"){


  require_once(modification("classes/db_cgm_classe.php"));
  $clCgm = new cl_cgm();

  $oRetorno->status = 0;

  $rsDadosCGM = $clCgm->sql_record($clCgm->sql_query($oParam->numcgm));

  if ( $clCgm->numrows > 0 ) {

    $oDadosCGM  = db_utils::fieldsMemory($rsDadosCGM,0);

    $oEndereco  = new stdClass();
    $oEndereco->ov02_sequencial = '';
    $oEndereco->ov02_seq        = '';
    $oEndereco->ov02_ident      = $oDadosCGM->z01_ident;
    $oEndereco->ov02_cnpjcpf    = $oDadosCGM->z01_cgccpf;
    $oEndereco->ov02_endereco   = $oDadosCGM->z01_ender;
    $oEndereco->ov02_numero     = $oDadosCGM->z01_numero;
    $oEndereco->ov02_compl      = $oDadosCGM->z01_compl;
    $oEndereco->ov02_bairro     = $oDadosCGM->z01_bairro;
    $oEndereco->ov02_munic      = $oDadosCGM->z01_munic;
    $oEndereco->ov02_uf         = $oDadosCGM->z01_uf;
    $oEndereco->ov02_cep        = $oDadosCGM->z01_cep;
    $oEndereco->z01_nome        = $oDadosCGM->z01_nome;
    $oEndereco->ov02_nome       = $oDadosCGM->z01_nome;
    $oEndereco->ov03_numcgm     = $oDadosCGM->z01_numcgm;

    $oRetorno->cidadao          = array($oEndereco);
    $oRetorno->tiporetorno      = array();
    $oRetorno->cidadaotelefones = array();
    $oRetorno->cidadaoemails    = array();

    if ( trim($oDadosCGM->z01_telef) != '' ) {
      $oTelefone = new stdClass();
      $oTelefone->descricao         = 'Residencial';
      $oTelefone->ov07_ddd          = '';
      $oTelefone->ov07_numero       = $oDadosCGM->z01_telef;
      $oTelefone->ov07_ramal        = '';
      $oTelefone->descrprincipal    = urlencode('Sim');
      $oTelefone->ov07_obs          = '';
      $oTelefone->ov07_tipotelefone = 1;
      $oTelefone->ov07_principal    = 't';
      $oRetorno->cidadaotelefones[] = $oTelefone;
    }

    if ( trim($oDadosCGM->z01_telcel) != '' ) {
      $oTelefone = new stdClass();
      $oTelefone->descricao         = 'Celular';
      $oTelefone->ov07_ddd          = '';
      $oTelefone->ov07_numero       = $oDadosCGM->z01_telcel;
      $oTelefone->ov07_ramal        = '';
      $oTelefone->descrprincipal    = urlencode('Não');
      $oTelefone->ov07_obs          = '';
      $oTelefone->ov07_tipotelefone = 2;
      $oTelefone->ov07_principal    = 'f';
      $oRetorno->cidadaotelefones[] = $oTelefone;
    }

    if ( trim($oDadosCGM->z01_telcon) != '' ) {
      $oTelefone = new stdClass();
      $oTelefone->descricao         = 'Comercial';
      $oTelefone->ov07_ddd          = '';
      $oTelefone->ov07_numero       = $oDadosCGM->z01_telcon;
      $oTelefone->ov07_ramal        = '';
      $oTelefone->descrprincipal    = urlencode('Não');
      $oTelefone->ov07_obs          = '';
      $oTelefone->ov07_tipotelefone = 3;
      $oTelefone->ov07_principal    = 'f';
      $oRetorno->cidadaotelefones[] = $oTelefone;
    }
    if ( trim($oDadosCGM->z01_celcon) != '' ) {
      $oTelefone = new stdClass();
      $oTelefone->descricao         = 'Comercial';
      $oTelefone->ov07_ddd          = '';
      $oTelefone->ov07_numero       = $oDadosCGM->z01_celcon;
      $oTelefone->ov07_ramal        = '';
      $oTelefone->descrprincipal    = urlencode('Não');
      $oTelefone->ov07_obs          = '';
      $oTelefone->ov07_tipotelefone = 3;
      $oTelefone->ov07_principal    = 'f';
      $oRetorno->cidadaotelefones[] = $oTelefone;
    }

    if ( trim($oDadosCGM->z01_email) != '' ) {
      $oEmail = new stdClass();
      $oEmail->ov08_email     = $oDadosCGM->z01_email;
      $oEmail->ov08_principal = 't';
      $oEmail->descricao      = 'Sim';
      $oRetorno->cidadaoemails[] = $oEmail;
    }

    if ( trim($oDadosCGM->z01_emailc) != '' ) {
      $oEmail = new stdClass();
      $oEmail->ov08_email     = $oDadosCGM->z01_emailc;
      $oEmail->ov08_principal = 'f';
      $oEmail->descricao      = urlencode('Não');
      $oRetorno->cidadaoemails[] = $oEmail;
    }


  } else {
    $oRetorno->status  = 1;
    $oRetorno->message = urlencode("Nenhum registro encontrado!");
  }

}else if ($oParam->acao == "vincular") {
  $oRetorno->status = 0;
  $oRetorno->message = utf8_encode("Usuário:\\n\\n Falha ao vincular Cidadão ao CGM!\\n\\nAdministrador:\\n\\n");

  db_inicio_transacao();
  $lerro = false;

  if(trim($oParam->ov03_cidadao)!="" && trim($oParam->ov03_seq)!="" && trim($oParam->ov03_numcgm)!=""){

    $clcidadaocgm->ov03_cidadao  = $oParam->ov03_cidadao;
    $clcidadaocgm->ov03_seq			 = $oParam->ov03_seq;
    $clcidadaocgm->ov03_numcgm	 = $oParam->ov03_numcgm;

    $clcidadaocgm->incluir(null);

    if($clcidadaocgm->erro_status == '0'){
      $lerro = true;
    }

    $oRetorno->status = 1;
    $oRetorno->message = utf8_encode("Usuário:\\n\\n Vínculo do Cidadão com CGM criado com sucesso!\\n\\nAdministrador:\\n\\n");
    $oRetorno->ov03_numcgm = $oParam->ov03_numcgm;

  }else{
    $lerro = true;
  }

  db_fim_transacao($lerro);
}

echo $oJson->encode($oRetorno);
?>