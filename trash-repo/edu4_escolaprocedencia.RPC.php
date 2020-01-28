<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/JSON.php");
require_once ("std/db_stdClass.php");
require_once ("std/DBDate.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");

define( "CAMINHO_MENSAGENS", "educacao.escola.edu4_escolaprocedencia." );

$iEscola           = db_getsession("DB_coddepto");
$oJson             = new Services_JSON();
$oParam            = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';
$oMensagem         = new stdClass();

try {
  
  switch ($oParam->exec) {
    
	  case "getPais":
	    
	    $aPais      = array();
	    $oDaoPais   = new cl_pais();
	    $sSqlPais   = $oDaoPais->sql_query_file(null, "ed228_i_codigo, ed228_c_descr", "ed228_c_descr");
	    $rsPais     = $oDaoPais->sql_record($sSqlPais);
	    $iTotalPais = $oDaoPais->numrows;
	    
	    $oRetorno->aPaises = array();
	    if ( $iTotalPais > 0 ) {
	    
	      for ($iContador = 0; $iContador < $iTotalPais; $iContador++) {
	    
	        $oDadosPais     = db_utils::fieldsMemory($rsPais, $iContador);
	        $oPais          = new stdClass();
	        $oPais->iCodigo = $oDadosPais->ed228_i_codigo;
	        $oPais->sPais   = urlencode($oDadosPais->ed228_c_descr);
	        
	        $oRetorno->aPaises[] = $oPais;
	      }
	    }
	    break;
	    
	  case 'getEstados':
	    
	    $oDaoEstado = new cl_censouf;
	    $sSqlEstado = $oDaoEstado->sql_query_file("", "ed260_i_codigo, ed260_c_nome", "ed260_c_nome");;
	    $rsEstados  = $oDaoEstado->sql_record($sSqlEstado);
	    $iLinhas    = $oDaoEstado->numrows;
	    
	    $oRetorno->aEstados = array();
	    for ($i = 0; $i < $iLinhas; $i++) {
	    	
	      $oDadosEstado     = db_utils::fieldsMemory($rsEstados, $i);
	      $oEstado          = new stdClass();
	      $oEstado->iCodigo = $oDadosEstado->ed260_i_codigo;
	      $oEstado->sEstado = urlencode($oDadosEstado->ed260_c_nome);
	      
	      $oRetorno->aEstados[] = $oEstado;
	    }
	    break;
	    
	  case 'getMunicipios':
	    
	    $sWhere         = "ed261_i_censouf = {$oParam->iEstado}";
	    $oDaoMunicipios = new cl_censomunic();
	    $sSqlMunicipios = $oDaoMunicipios->sql_query_file("", "ed261_i_codigo,ed261_c_nome", "ed261_c_nome", $sWhere);
	    $rsMunicipios   = $oDaoMunicipios->sql_record($sSqlMunicipios);
	    $iLinhas        = $oDaoMunicipios->numrows;
	    
	    $oRetorno->aMunicipios = array();
	    
	    for ($i = 0; $i < $iLinhas; $i++ ) {
	      
	      $oDadosMunicipio        = db_utils::fieldsMemory($rsMunicipios, $i);
	      $oMunicipio             = new stdClass();
	      $oMunicipio->iCodigo    = $oDadosMunicipio->ed261_i_codigo;
	      $oMunicipio->sMunicipio = urlencode($oDadosMunicipio->ed261_c_nome);

	      $oRetorno->aMunicipios[] = $oMunicipio;
	    }
	    
	    break;
	    
	  case 'getDistritos':
	    
	    $sWhere       = "     ed262_i_censomunic = {$oParam->iMunicipio} ";
	    $sWhere      .= " and ed261_i_censouf    = {$oParam->iEstado} ";
	    $oDaoDistrito = new cl_censodistrito();
	    $sSqlDistrito = $oDaoDistrito->sql_query(null, "ed262_i_codigo, ed262_c_nome", "ed262_c_nome", $sWhere );
	    $rsDistrito   = $oDaoDistrito->sql_record($sSqlDistrito);
	    $iLinhas      = $oDaoDistrito->numrows;
	    
	    $oRetorno->aDistritos = array();
	    
	    for ($i = 0; $i < $iLinhas; $i++) {
	    	
	      $oDadosDistrito       = db_utils::fieldsMemory($rsDistrito, $i);
	      $oDistrito            = new stdClass();
	      $oDistrito->iCodigo   = $oDadosDistrito->ed262_i_codigo;
	      $oDistrito->sDistrito = urlencode($oDadosDistrito->ed262_c_nome);
	      
	      $oRetorno->aDistritos[] = $oDistrito;
	    }
	    
	    break;
	    
	  case 'getEscolaProcedencia':
	    
	    $oDaoEscolaProcedencia = new cl_escolaproc();
      $sSqlEscolaProcedencia = $oDaoEscolaProcedencia->sql_query_file($oParam->iEscolaProcedencia);
      $rsEscolaProcedencia   = db_query($sSqlEscolaProcedencia);
      
      if (!$rsEscolaProcedencia || pg_num_rows($rsEscolaProcedencia) == 0) {
      	throw new Exception( _M( CAMINHO_MENSAGENS . "escola_procedencia_nao_encontrada" ) );
      }
      
      $oDadoEscolaProc = db_utils::fieldsMemory($rsEscolaProcedencia, 0);
      $oEscolaProc     = new stdClass();
      $oEscolaProc->iCodigo      = $oDadoEscolaProc->ed82_i_codigo;
      $oEscolaProc->sNome        = urlencode($oDadoEscolaProc->ed82_c_nome);
      $oEscolaProc->sAbreviatura = urlencode($oDadoEscolaProc->ed82_c_abrev);
      $oEscolaProc->sEmail       = urlencode($oDadoEscolaProc->ed82_c_email);
      $oEscolaProc->sRua         = urlencode($oDadoEscolaProc->ed82_c_rua);
      $oEscolaProc->sComplemento = urlencode($oDadoEscolaProc->ed82_c_complemento);
      $oEscolaProc->sBairro      = urlencode($oDadoEscolaProc->ed82_c_bairro);
      $oEscolaProc->sMantenedora = $oDadoEscolaProc->ed82_c_mantenedora;
      $oEscolaProc->iNumero      = $oDadoEscolaProc->ed82_i_numero;
      $oEscolaProc->iCep         = $oDadoEscolaProc->ed82_i_cep;
      $oEscolaProc->iEstado      = $oDadoEscolaProc->ed82_i_censouf;
      $oEscolaProc->iMunicipio   = $oDadoEscolaProc->ed82_i_censomunic;
      $oEscolaProc->iDistrito    = $oDadoEscolaProc->ed82_i_censodistrito;
      $oEscolaProc->iPais        = $oDadoEscolaProc->ed82_pais;
      
      $oRetorno->oEscolaProc = $oEscolaProc;
	    
	    break;
	    
	  case 'excluir' :
	    
	    $sSql  = "select 1 ";
      $sSql .= "  from (select 1 from historicompsfora where ed99_i_escolaproc = {$oParam->iEscolaProcedencia}) as x ";
      $sSql .= "union all ";
      $sSql .= "select 1 ";
      $sSql .= "  from (select 1 from transfescolafora where ed104_i_escoladestino = {$oParam->iEscolaProcedencia}) as x; ";
	    
      $rsValidaVinculo = db_query($sSql);
      
      if (pg_num_rows($rsValidaVinculo) > 0) {
      	throw new Exception( _M( CAMINHO_MENSAGENS . "escola_procedencia_com_vinculo" ) );
      }
	    
	    $oDaoEscolaProcedencia = new cl_escolaproc();
	    $oDaoEscolaProcedencia->excluir($oParam->iEscolaProcedencia);
	    
	    if ($oDaoEscolaProcedencia->erro_status == 0) {
	      
	      $oMensagem->sErro = $oDaoEscolaProcedencia->erro_msg;
	      throw new Exception( _M( CAMINHO_MENSAGENS . "escola_procedencia_nao_excluida", $oMensagem ) );
	    }
	    $oRetorno->message = urlencode( _M( CAMINHO_MENSAGENS . "escola_procedencia_excluida" ) );
	    
	    break;
	    
	  case 'salvar':
    
      $sNome  = db_stdClass::normalizeStringJsonEscapeString($oParam->sNome);
      $sNome .= trim($sNome);
      if ( empty($sNome) ) {
	      throw new Exception( _M( CAMINHO_MENSAGENS . "preencha_nome" ) );
	    }
	    
	    if ($oParam->iPais == 10 && empty($oParam->iEstado)) {
	      throw new Exception( _M( CAMINHO_MENSAGENS . "selecione_estado" ) );
	    }
	    
	    $oDaoEscolaProcedencia = new cl_escolaproc();
	    $oDaoEscolaProcedencia->ed82_i_codigo       = null;
	    $oDaoEscolaProcedencia->ed82_c_nome         = db_stdClass::normalizeStringJsonEscapeString($oParam->sNome);
	    $oDaoEscolaProcedencia->ed82_c_abrev        = db_stdClass::normalizeStringJsonEscapeString($oParam->sAbreviatura);
	    $oDaoEscolaProcedencia->ed82_c_mantenedora  = $oParam->iMantenedora;
	    $oDaoEscolaProcedencia->ed82_c_email        = db_stdClass::normalizeStringJsonEscapeString($oParam->sEmail);
	    $oDaoEscolaProcedencia->ed82_c_rua          = db_stdClass::normalizeStringJsonEscapeString($oParam->sRua);
	    $oDaoEscolaProcedencia->ed82_i_numero       = $oParam->iNumero;
	    $oDaoEscolaProcedencia->ed82_c_complemento  = db_stdClass::normalizeStringJsonEscapeString($oParam->sComplemento);
	    $oDaoEscolaProcedencia->ed82_c_bairro       = db_stdClass::normalizeStringJsonEscapeString($oParam->sBairro);
	    $oDaoEscolaProcedencia->ed82_i_cep          = empty($oParam->iCep)       ? 'null' : $oParam->iCep;
	    $oDaoEscolaProcedencia->ed82_i_censouf      = empty($oParam->iEstado)    ? 'null' : $oParam->iEstado;
	    $oDaoEscolaProcedencia->ed82_i_censomunic   = empty($oParam->iMunicipio) ? 'null' : $oParam->iMunicipio;
	    $oDaoEscolaProcedencia->ed82_i_censodistrito= empty($oParam->iDistrito)  ? 'null' : $oParam->iDistrito;
	    $oDaoEscolaProcedencia->ed82_pais           = $oParam->iPais;
	    
	    if (isset ($oParam->iEscolaProcedencia) && !empty($oParam->iEscolaProcedencia)) {
	      
	      $oDaoEscolaProcedencia->ed82_i_codigo = $oParam->iEscolaProcedencia;
	      $oDaoEscolaProcedencia->alterar2($oParam->iEscolaProcedencia);
	    } else {
	      $oDaoEscolaProcedencia->incluir(null);
	    }
	    
	    if ($oDaoEscolaProcedencia->erro_status == 0) {
	      
	      $oMensagem->sErro = $oDaoEscolaProcedencia->erro_msg;
	      throw new Exception( _M( CAMINHO_MENSAGENS . "erro_incluir_alterar", $oMensagem ) );
	    }
	    
	    $oRetorno->message = urlencode( _M( CAMINHO_MENSAGENS . "escola_procedencia_sucesso" ) );
	    break;
  }
  
} catch(Exception $oErro) {
  
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($oErro->getMessage());
}

echo $oJson->encode($oRetorno);