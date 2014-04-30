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

require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_layouttxt.php");

$oJson             = new services_json();
$oParam            = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\","",$_POST["json"])));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = 1;
$lErro             = false;
$sMensagem         = "";

switch($oParam->exec) {

  case  'txt' :
  	
  	$sDataSessao = date("Y-m-d",db_getsession('DB_datausu')) ;
  	$iAnoFolha   = db_anofolha();
  	$iMesFolha   = db_mesfolha();
  	$sIntervaloA = implode("-", array_reverse(explode("/",$oParam -> sIntervaloA)));
  	$sIntervaloB = implode("-", array_reverse(explode("/",$oParam -> sIntervaloB)));
  	$iEmpresa    = $oParam -> iEmpresa;
  	$iMunicipio  = $oParam -> iMunicipio;
  	$sResumo     = $oParam -> sResumo;
  	$sFiltro     = $oParam -> sFiltro;
  	$sTipoFiltro = $oParam -> sTipoFiltro;
  	$sSqlLota    = "";
  	$sSqlMatri   = "";
  	$sSqlTrab    = "";
  	$iCodLayOut  = 94;
  	
  	/*
  	 * definimos uma variavel adicional ao where dependendo do tipo de resumo e to tipo de filtro
  	 */
  	if ($sResumo == "l" && $sFiltro != 0 || $sFiltro != "0" ) {      // Lotaηγo
  		$sSqlLota = " and rhpessoalmov.rh02_lota   $sTipoFiltro ";
  	} else {
  		$sSqlLota = "";
  	}
  	if ($sResumo == "m" && $sFiltro != 0 || $sFiltro != "0") {       // Matricula
  		$sSqlMatri = " and rhpessoalmov.rh02_regist $sTipoFiltro ";
  	} else {
  		$sSqlMatri = "";
  	}
  	if ($sResumo == "t" && $sFiltro != 0 || $sFiltro != "0") {       // Trabalho
  		
      $sSqlTrab  = "   and exists ( select 1                                                 ";
      $sSqlTrab .= "                 from rhpeslocaltrab                                     ";
      $sSqlTrab .= "                where rhpeslocaltrab.rh56_seqpes                         ";
      $sSqlTrab .= "                 and rhpeslocaltrab.rh56_localtrab $sTipoFiltro )        ";  		
  	} else {
  		$sSqlTrab = "";
  	}
  	
  	if ($sIntervaloA == null || $sIntervaloA == "") {
  		$sIntervaloA = '1900-01-01';
  	}
    if ($sIntervaloB == null || $sIntervaloB == "") {
      $sIntervaloB = date("Y-m-d",db_getsession('DB_datausu'));
    }  	
    
    $sSqlTxt  = " select rh01_regist,                                                                                 ";
    $sSqlTxt .= "        z01_nome,                                                                                    ";
    $sSqlTxt .= "        z01_nasc,                                                                                    ";
    $sSqlTxt .= "        z01_sexo,                                                                                    ";
    $sSqlTxt .= "        rh01_estciv,                                                                                 ";
    $sSqlTxt .= "        z01_ender,                                                                                   ";
    $sSqlTxt .= "        z01_compl,                                                                                   ";
    $sSqlTxt .= "        z01_numero,                                                                                  ";
    $sSqlTxt .= "        substr(z01_cep,1,5) as cep,                                                                  ";
    $sSqlTxt .= "        substr(z01_cep,5,3) as suf_cep,                                                              ";
    $sSqlTxt .= "        z01_uf,                                                                                      ";
    $sSqlTxt .= "        z01_telef,                                                                                   ";
    $sSqlTxt .= "        z01_ident,                                                                                   ";
    $sSqlTxt .= "        'SSP' as orgao_emissor,                                                                      ";
    $sSqlTxt .= "        '114' as codigo_pais,                                                                        ";
    $sSqlTxt .= "        z01_cgccpf,                                                                                  ";
    $sSqlTxt .= "        z01_mae,                                                                                     ";
    $sSqlTxt .= "        '0' as ficha_azul,                                                                           ";
    $sSqlTxt .= "        z01_profis,                                                                                  ";
    $sSqlTxt .= "        r70_descr,                                                                                   ";
    $sSqlTxt .= "   case                                                                                              ";
    $sSqlTxt .= "      when rh16_regist is not null then rh16_ctps_n||'/'||rh16_ctps_s||'/'||rh16_ctps_d              ";
    $sSqlTxt .= "      else ''                                                                                        ";
    $sSqlTxt .= "    end as carteira_profissional,                                                                    ";
    $sSqlTxt .= "    rh16_pis,                                                                                        ";
    $sSqlTxt .= "    case                                                                                             ";
    $sSqlTxt .= "      when ('$sDataSessao' ::date - afasta.r45_dtafas) = 15 then 'S'                                 ";
    $sSqlTxt .= "      else 'N'                                                                                       ";
    $sSqlTxt .= "    end as beneficio_inss,                                                                           ";
    $sSqlTxt .= "    rh01_admiss,                                                                                     ";
    $sSqlTxt .= "    rh05_recis,                                                                                      ";
    $sSqlTxt .= "    case                                                                                             ";
    $sSqlTxt .= "      when rh05_recis is not null then 'E'                                                           ";
    $sSqlTxt .= "      else 'I'                                                                                       ";
    $sSqlTxt .= "    end as status                                                                                    ";
    $sSqlTxt .= " from rhpessoal                                                                                      ";
    $sSqlTxt .= "  inner join cgm          on cgm.z01_numcgm            = rhpessoal.rh01_numcgm                       ";
    $sSqlTxt .= "  inner join rhpessoalmov on rhpessoalmov.rh02_regist  = rhpessoal.rh01_regist                       ";
    $sSqlTxt .= "                     and rhpessoalmov.rh02_anousu      = $iAnoFolha                                  ";
    $sSqlTxt .= "                     and rhpessoalmov.rh02_mesusu      = $iMesFolha                                  ";
	  $sSqlTxt .= "  inner join rhlota       on rhlota.r70_codigo         = rhpessoalmov.rh02_lota                      ";
	  $sSqlTxt .= "  left  join rhpesdoc     on rhpesdoc.rh16_regist      = rhpessoal.rh01_regist                       ";
	  $sSqlTxt .= "  left  join afasta       on afasta.r45_regist         = rhpessoalmov.rh02_regist                    ";
	  $sSqlTxt .= "                     and afasta.r45_anousu             = rhpessoalmov.rh02_anousu                    ";
	  $sSqlTxt .= "                     and afasta.r45_mesusu             = rhpessoalmov.rh02_mesusu                    ";
	  $sSqlTxt .= "  left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes                    ";
	  $sSqlTxt .= " where ( rhpessoal.rh01_admiss    between '$sIntervaloA' :: date and '$sIntervaloB' :: date )        ";
	  $sSqlTxt .= "    or ( rhpesrescisao.rh05_recis between '$sIntervaloA' :: date and '$sIntervaloB' :: date )        ";
    /*
     * Filtros adicionais, conforme escolha entre lotaηγo, matricula e trbalho
     */
	  if ($sResumo == 'l') {        // Lotaηγo
	    $sSqlTxt .= " $sSqlLota  ;";
    }
    if ($sResumo == 'm') {        // Matricula
      $sSqlTxt .= " $sSqlMatri ;";
    }
    if ($sResumo == 't') {        // Trabalho
	    $sSqlTxt .= " $sSqlTrab  ;";
    }
    
    $sSufixo        =  "{$iAnoFolha}{$iMesFolha}_".date('Ymdis', db_getsession('DB_datausu'));
    $pArquivoTxt    = "tmp/dados_medicinaOcupacional_{$sSufixo}.txt";
    $pArquivoLayout = "tmp/layout_medicinaOcupacional_{$sSufixo}.txt";    
    
    $oLayoutTxt     = new db_layouttxt($iCodLayOut, $pArquivoTxt);    

    $rsTxt          = db_query($sSqlTxt);
    $aListaTxt      = db_utils::getColectionByRecord($rsTxt);
    
    foreach ($aListaTxt as $oIndiceTxt => $oValorTxt) {
      
    	//verifica o estado civil, se for diferente de 1,2,3, assume -se 1, solteiro
    	if ($oValorTxt->rh01_estciv > 3) {
    		 $oValorTxt->rh01_estciv = 1;
    	}
      $oLayoutTxt->limpaCampos();
      $oLayoutTxt->setCampoTipoLinha(3);
      $oLayoutTxt->setCampo("data_arquivo"         , date('dmY', db_getsession('DB_datausu')));
      $oLayoutTxt->setCampo("codigo_empresa"       , $iEmpresa);
      $oLayoutTxt->setCampo("registro_titular"     , str_pad($oValorTxt->rh01_regist, 15, "0", STR_PAD_LEFT));
    	$oLayoutTxt->setCampo("nome"                 , $oValorTxt->z01_nome);
    	$oLayoutTxt->setCampo("data_nascimento"      , $oValorTxt->z01_nasc);
    	$oLayoutTxt->setCampo("sexo"                 , $oValorTxt->z01_sexo);
    	$oLayoutTxt->setCampo("estado_civil"         , $oValorTxt->rh01_estciv);
    	$oLayoutTxt->setCampo("endereco"             , $oValorTxt->z01_ender);
    	$oLayoutTxt->setCampo("complemento"          , $oValorTxt->z01_compl);
    	$oLayoutTxt->setCampo("numero"               , $oValorTxt->z01_numero);
    	$oLayoutTxt->setCampo("codigo_municipio"     , $iMunicipio);
    	$oLayoutTxt->setCampo("cep"                  , $oValorTxt->cep);
    	$oLayoutTxt->setCampo("cep_sufixo"           , $oValorTxt->suf_cep);
    	$oLayoutTxt->setCampo("uf"                   , $oValorTxt->z01_uf);
    	$oLayoutTxt->setCampo("telefone"             , $oValorTxt->z01_telef);
    	$oLayoutTxt->setCampo("carteira_identidade"  , $oValorTxt->z01_ident);
    	$oLayoutTxt->setCampo("orgao_emissor"        , $oValorTxt->orgao_emissor);
    	$oLayoutTxt->setCampo("codigo_pais"          , "114");
    	$oLayoutTxt->setCampo("cpf"                  , str_pad($oValorTxt->z01_cgccpf, 14, "0", STR_PAD_LEFT));
    	$oLayoutTxt->setCampo("nome_mae"             , $oValorTxt->z01_mae);
    	$oLayoutTxt->setCampo("ficha_azul"           , $oValorTxt->ficha_azul);
    	$oLayoutTxt->setCampo("profissao"            , $oValorTxt->z01_profis);
    	$oLayoutTxt->setCampo("setor"                , $oValorTxt->r70_descr);
    	$oLayoutTxt->setCampo("carteira_proficional" , $oValorTxt->carteira_profissional);
    	$oLayoutTxt->setCampo("numero_pis"           , str_pad($oValorTxt->rh16_pis, 15, "0", STR_PAD_LEFT));
    	$oLayoutTxt->setCampo("beneficio_inss"       , $oValorTxt->beneficio_inss);
    	$oLayoutTxt->setCampo("data_admissao"        , $oValorTxt->rh01_admiss);
    	$oLayoutTxt->setCampo("data_demissao"        , $oValorTxt->rh05_recis);
    	$oLayoutTxt->setCampo("status"               , $oValorTxt->status);
    	
    	$oLayoutTxt->geraDadosLinha();
    }
    
    $oLayoutTxt->fechaArquivo();
    
    $oLayoutTxt->limpaCampos();
    $oLayoutTxt->gerarArquivoLeiaute($pArquivoLayout, 332);
     
    $oRetorno->arquivotxt = $pArquivoTxt;
    $oRetorno->leiaute    = $pArquivoLayout;
   
  break;	
  
}
    
echo $oJson->encode($oRetorno);   

?>