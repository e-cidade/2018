<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

require_once(modification("fpdf151/pdf1.php"));
require_once(modification("fpdf151/impcarne.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_libsys.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_protparam_classe.php"));
require_once(modification("classes/db_procvar_classe.php"));
require_once(modification("classes/db_protprocesso_classe.php"));
require_once(modification("classes/db_db_config_classe.php"));

require_once(modification("std/db_stdClass.php"));
require_once(modification('dbagata/classes/core/AgataAPI.class'));
require_once(modification("model/documentoTemplate.model.php"));
require_once(modification("classes/db_db_config_classe.php"));
require_once(modification("model/processoProtocolo.model.php"));

// **********************************************
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oPost          = db_utils::postMemory($_POST);
$oGet           = db_utils::postMemory($_GET);
$sWhere = "";

$iAnoUsuInicial = db_getsession("DB_anousu");
$iAnoUsuFinal   = db_getsession("DB_anousu");

if (isset($oGet->numeroProcessoInicial) && !empty($oGet->numeroProcessoInicial)) {
		
	$aProcessoInicial        = explode("/", $oGet->numeroProcessoInicial);
	$aProcessoFinal          = explode("/", $oGet->numeroProcessoFinal);
	$iNumeroProcessoInicical = $aProcessoInicial[0];
	$iNumeroProcessoFinal    = $aProcessoFinal[0];
	
	if ($oGet->numeroProcessoInicial != '' ) {
		
		if (count($aProcessoInicial) > 1) {
			$iAnoUsuInicial = $aProcessoInicial[1];
		}
	}
	
	if ($oGet->numeroProcessoFinal != '') {
	
		if (count($aProcessoFinal) > 1) {
			$iAnoUsuFinal = $aProcessoFinal[1];
		}
	}
	
	if (empty($iNumeroProcessoFinal) || $iNumeroProcessoFinal == '') {
		$iNumeroProcessoFinal = $iNumeroProcessoInicical;
	}
}

if (isset($oGet->codproc)  && !empty($oGet->codproc) ) {

	/*
	 * aqui é parte de impresao quando se inclui um processo
	 * como é um só, definimos inicial e final o mesmo, para fazer o between na query
	 */

	$oProcesso = new ProcessoProtocolo($oGet->codproc);
	$iNumeroProcessoInicical = $oProcesso->getNumeroProcesso();
	$iAnoUsuInicial          = $oProcesso->getAnoProcesso();
	
	$iAnoUsuFinal            = $iAnoUsuInicial;
	$iNumeroProcessoFinal    = $iNumeroProcessoInicical;
	
}


$sWhere = " where p58_numero between '{$iNumeroProcessoInicical}' and '{$iNumeroProcessoFinal}' ";
$sWhere .= " and p58_ano between {$iAnoUsuInicial} and {$iAnoUsuFinal} ";

$sWhere  .= " and p58_instit = ".db_getsession("DB_instit") ;

$clprotparam    = new cl_protparam;
$clprocvar      = new cl_procvar;
$clProtProcesso = new cl_protprocesso;

$iCodigoInstituicao  = db_getsession('DB_instit');
$iCodigoDepartamento = db_getsession('DB_coddepto');


$sCamposParam = "p90_modelcapaproc, p90_db_documentotemplate, db82_templatetipo";
$sSqlParam    = $clprotparam->sql_query_documentos(null,$sCamposParam);
$rsParm       = $clprotparam->sql_record($sSqlParam);
$oProtParam   = db_utils::fieldsMemory($rsParm, 0);

$oDaoProtPocesso = db_utils::getDao('protprocesso');

    $sql = " select	p58_codproc,
  	                p58_numero,
  	                p58_ano,
         	  		  	p58_numcgm,
                    p58_requer,
  					        p58_dtproc,
                    p58_obs,
                    p51_descr,
  					        nome,
  		    		      p58_codigo,
  					        cgm.*,
                    to_char(p58_dtproc,'DD/MM/YYYY') as dtproc
               from protprocesso 
              	 	  inner join tipoproc on p58_codigo = p51_codigo
  			   		      inner join cgm on z01_numcgm = p58_numcgm
              		  inner join db_usuarios on id_usuario = p58_id_usuario
              {$sWhere}		  
              order by p58_codproc";

/**
 * Verifica se o parâmetro não esta setado como Documento Template
 */
if ($oProtParam->p90_modelcapaproc != 3) {
  
  $result = db_query($sql);
  
  $numrows = pg_numrows($result);
  
  if (pg_numrows($result) == 0) {
  	
    db_redireciona('db_erros.php?fechar=true&db_erro=Processo nao cadastrado!');
    exit;
  }
  
  
  $pdf = new pdf1();
  $pdf->Open();
  $result_param = $clprotparam->sql_record($clprotparam->sql_query_file(null,"*",null,"p90_instit = ".db_getsession("DB_instit")));
  if ($clprotparam->numrows>0){
    db_fieldsmemory($result_param,0);
  }
  if (isset($p90_modelcapaproc)&&$p90_modelcapaproc==0){
    $modelo = 40;
  }else if (isset($p90_modelcapaproc)&&$p90_modelcapaproc==1){
    $modelo = 41;
  }else if (isset($p90_modelcapaproc)&&$p90_modelcapaproc==2){
    $modelo = 42;
  }else{
    $modelo = 40;
  }
  $pdf1 = new db_impcarne($pdf, "$modelo");
  $pdf1->telefinstit = pg_result(db_query("select telef from db_config where codigo = ".db_getsession("DB_instit")),0,0);
  
  for($w=0;$w<$numrows;$w++){
    db_fieldsmemory($result,$w);
  
    $dtprocinfo = explode("/", $dtproc);
    $pdf1->anoproc 	   = $dtprocinfo[2];
    $pdf1->p58_codproc = $p58_codproc;
    $pdf1->p58_numero  = $p58_numero;
    $pdf1->p58_ano     = $p58_ano;
    $pdf1->dtproc 	   = $dtproc;
    $pdf1->p58_numcgm  = $p58_numcgm;
    $pdf1->z01_nome    = $z01_nome;
    $pdf1->z01_cgccpf  = $z01_cgccpf;
    $pdf1->z01_telef   = $z01_telef;
    $pdf1->p58_requer  = $p58_requer;
    $pdf1->p51_descr   = $p51_descr;
    $pdf1->z01_ender   = $z01_ender;
    $pdf1->z01_numero  = $z01_numero;
    $pdf1->z01_compl   = $z01_compl;
    $pdf1->z01_bairro  = $z01_bairro;
    $pdf1->z01_munic   = $z01_munic;
    $pdf1->p58_codigo  = $p58_codigo;
    $pdf1->p58_dtproc  = $p58_dtproc;
    $pdf1->nome	   = $nome;
    $pdf1->p58_obs	   = $p58_obs;
  
    if (isset($p90_imprimevar) and $p90_imprimevar == "t"){
      $pdf1->result_vars = $clprocvar->sql_record($clprocvar->sql_query_varconteudo($p58_codproc,
      null,
  											                                                                     "distinct rotulo,
  											                                                                     p55_conteudo,p55_codcam", 
  	                                                                                         "p55_codcam"));
    } else {
      $pdf1->result_vars = "";
    }
  
    $pdf1->imprime();
  }
  $pdf1->objpdf->Output();
  
} else {
  
  ini_set("error_reporting","E_ALL & ~NOTICE");
  $sDescrDoc        = date("YmdHis").db_getsession("DB_id_usuario");
  $sNomeRelatorio   = "tmp/geraCapaProtocolo{$sDescrDoc}.pdf";
  $sCaminhoSalvoSxw = "tmp/capa_protocolo_{$sDescrDoc}.sxw";
  
  /*
   * implentando uma logica para descobrir os codproc pelos numero do processo selecionado
  * para passarmos para o agata
  */
  
  $rsCodProc = $oDaoProtPocesso->sql_record($sql);
  if ($oDaoProtPocesso->numrows > 0) {
  
  	$iCodProcInicial = db_utils::fieldsMemory($rsCodProc, 0)->p58_codproc;
  	$iCodProcFinal   = db_utils::fieldsMemory($rsCodProc, $oDaoProtPocesso->numrows - 1)->p58_codproc;
  }
  
  // Caminho onde esta o .agt
  $sAgt = "protocolo/capa_processo.agt";
  
   // Parâmetros Utilizado no .agt
  $aParam                      = array();
  $aParam['$codigo_processo']  = $iCodProcInicial;
  
  // Se for imprimir mais de uma capa
  if (isset($iCodProcFinal) && !empty($iCodProcFinal)) {
    $aParam['$codigo_processo_fim'] = $iCodProcFinal  ;
  } else {
    $aParam['$codigo_processo_fim'] = $iCodProcInicial;
  }
  
  $aParam['$codigo_instituicao']  = $iCodigoInstituicao;
  $aParam['$codigo_departamento'] = $iCodigoDepartamento;
  
  
  db_stdClass::oo2pdf($oProtParam->db82_templatetipo,
                      $oProtParam->p90_db_documentotemplate, 
                      $sAgt, 
                      $aParam, 
                      $sCaminhoSalvoSxw, 
                      $sNomeRelatorio);
  
}



?>