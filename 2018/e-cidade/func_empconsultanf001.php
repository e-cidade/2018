<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_matordem_classe.php");

$oGet = db_utils::postMemory($HTTP_GET_VARS);

switch ($oGet->exec){
	case 'detalhamento':
		  $sSql = "
		    select e69_codnota, 
							 e69_numero,
							 e69_dtnota,
							 m51_codordem,
							 e60_numemp, 
							 case when cgmordem.z01_numcgm is null then cgmempenho.z01_numcgm 
							 else cgmordem.z01_numcgm end as z01_numcgm , 
							 case when cgmordem.z01_nome is null then cgmempenho.z01_nome 
							 else cgmordem.z01_nome end as z01_nome , 
							 e70_valor, 
							 e70_vlrliq, 
							 e70_vlranu, 
							 e53_vlrpag 
							 from empnota 
							 inner join empnotaele     on e70_codnota = e69_codnota 
							 inner join empempenho     on e60_numemp  = e69_numemp 
							 left  join cgm cgmempenho on cgmempenho.z01_numcgm = e60_numcgm 
							 left  join pagordemnota   on e70_codnota = e71_codnota 
							 and e71_anulado is false 
							 left  join pagordemele    on e71_codord = e53_codord 
							 left  join pagordemconta  on e49_codord = e71_codord 
							 left  join cgm cgmordem   on e49_numcgm = cgmordem.z01_numcgm
							 left  join empnotaord     on m72_codnota = e69_codnota
							 left  join matordem       on m51_codordem = m72_codordem
							 where e69_codnota = $oGet->e69_codnota
							 order by e69_dtnota
		  ";
		break;
	case 'retencoes':
		
		  $sSqlPagOrdemNota = "select e71_codord 
		                         from pagordemnota 
		                        where e71_codnota = $oGet->e69_codnota 
		                          and e71_anulado is false ";
		                          //die ($sSqlPagOrdemNota);  
		  $oDaoPagOrdemNota = db_utils::getDao("pagordemnota");
		  $rsSqlPagOrdemNota = $oDaoPagOrdemNota->sql_record($sSqlPagOrdemNota);
		  
		  if ( $oDaoPagOrdemNota->erro_status != "0" ) {
		    $oPagOrdem = db_utils::fieldsMemory($rsSqlPagOrdemNota,0);
			  $oDaoRetencao  = db_utils::getDao("retencaoreceitas");
	      $sSql = $oDaoRetencao->sql_query_consulta(null,
	                                                      "e21_sequencial,
	                                                       e20_pagordem,
	                                                       e21_descricao,
	                                                       e23_dtcalculo,
	                                                       e23_valor,
	                                                       e23_valorbase,
	                                                       e23_deducao,
	                                                       e23_valorretencao,
	                                                       e23_aliquota,
	                                                       case when e23_recolhido is true then 'Sim'
	                                                       else 'Não' end as 
	                                                       e23_recolhido,
	                                                       k105_data,
	                                                       numpre.k12_numpre,
	                                                       q32_planilha",
	                                                       "e23_sequencial",
	                                                       "e20_pagordem  = {$oPagOrdem->e71_codord}
	                                                        and e27_principal is true
	                                                        and e23_ativo is true"
		                                                     );
		                                                    // die($sSql);
		  }else{
		  	$sMsg = "<center><b>Nenhum Registro Retornado.</b></center>";
		  	$sSql = "";
		  }
		break;
	case 'itens':
		$oDaoEmpNota = db_utils::getDao("empnotaitem");
		$sSql = $oDaoEmpNota->sql_query(null,
		                                      ' 
		                                       pc01_descrmater, 
		                                       e72_valor, 
		                                       e72_qtd,
		                                       e72_vlrliq,
		                                       e72_vlranu',
		                                      null,
		                                      "e69_codnota = $oGet->e69_codnota");
		
		break;

	case 'pit':
		  $sSql = " SELECT e14_nomearquivo, 
                       e14_dtarquivo, 
                       e11_cfop, 
                       e11_seriefiscal, 
                       e11_basecalculoicms, 
                       e11_valoricms, 
                       e11_basecalculosubstitutotrib, 
                       e11_valoricmssubstitutotrib 
                  from empnotadadospitnotas 
                       inner join empnotadadospit    on e13_empnotadadospit = e11_sequencial 
                       inner join emparquivopitnotas on e15_empnotadadospit = e11_sequencial 
                       inner join emparquivopit      on e15_emparquivopit   = e14_sequencial 
                 where e13_empnota = $oGet->e69_codnota";
      $rsSql = db_query($sSql);
      if(pg_num_rows($rsSql) == 0){
      	$sSql = "";
      	$sMsg = "<center><b>Nota não enviada/gerada para o PIT.</b></center>";
      }
		break;
		
	 default: 
	 	$sMsg = "<center><b>Nenhum Registro Retornado.</b></center>";
	 	$sSql = "";
	 	
}
//echo $sSql;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td align="center" valign="top">
    <? 
      if($sSql != ""){
        $funcao_js = '';
        db_lovrot($sSql,15,"()","",$funcao_js);
      }else{
      	echo $sMsg;
      }
    ?>
    </td>
   </tr>
</table>
</body>
</html>