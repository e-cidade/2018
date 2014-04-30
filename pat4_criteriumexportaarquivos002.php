<?php
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

require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");
include ("libs/db_utils.php");
include ("libs/JSON.php");
include ("dbforms/db_layouttxt.php");
?>
<html>
	<head>
		<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta http-equiv="Expires" CONTENT="0">
		<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
		<script type="text/javascript" src="scripts/strings.js"></script>
		<script type="text/javascript" src="scripts/prototype.js"></script>
		<script type="text/javascript" src="scripts/datagrid.widget.js"></script>
		<link href="estilos.css" rel="stylesheet" type="text/css">
		<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
	</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="">
<form name="form2" action="">
<table width="790" border="0" align="center" cellspacing="0"
	bgcolor="#CCCCCC" style="padding-top: 20px;">
	<tr>
		<td>
			<?
			db_criatermometro('termometro','Concluído','blue',1);
			echo "<br><br>";
    	db_criatermometro('termometroitem','Concluido...','blue',1);
    	
			$oJson      = new Services_JSON();
			//$sArqName   = "INTEGRACAO_CRITERIUM_".date('Y-m-d',db_getsession('DB_datausu')).".TXT";
			$oGet 				= db_utils::postMemory($_GET);
//			$iIdUsuario		=	db_getsession('DB_id_usuario');
//			$sQueryUsuarioCriterium = "select t08_id_usuario,t08_sequencial"
			
			$aParametros 	= $oJson->decode(str_replace("\\", "", $oGet->aParametros));
		//	print_r($_GET);exit();
			$iCountItem = count($aParametros);
			$iCodigoUsuario = $oGet->idusuario;
			$sQueryUsuarioCriterium = "select t08_id_usuario,t08_sequencial,t08_seqsinc 
																	from usuariocriterium 
																	where t08_sequencial = $iCodigoUsuario";
			$resQueryUsuarioCriterium = pg_query($sQueryUsuarioCriterium);
			if(pg_num_rows($resQueryUsuarioCriterium)>0){
				$rowQueryUsuarioCriterium = pg_fetch_object($resQueryUsuarioCriterium);
				$iCodDBUsuario 	= $rowQueryUsuarioCriterium->t08_id_usuario;
				$iCodSequencial = $rowQueryUsuarioCriterium->t08_seqsinc;
			}
			$iNewCodSequencial =  $iCodSequencial + 1;
			$sQueryUsuarioCriterium = "update usuariocriterium set t08_seqsinc = $iNewCodSequencial 
																	where t08_sequencial = $iCodigoUsuario";
			if(pg_query($sQueryUsuarioCriterium)){
									
				$sArqName    = "BD";
				$sArqName		.= str_pad($iCodDBUsuario,5,"0",STR_PAD_LEFT);
				$sArqName		.= str_pad($iNewCodSequencial,3,"0",STR_PAD_LEFT);
				$sArqName		.= ".TXT";
				
				$oLayoutTxt = new db_layouttxt(67,"tmp/".$sArqName );
				$oLayoutTxt->setCampoTipoLinha(1);
				$oLayoutTxt->limpaCampos();
				$oLayoutTxt->setCampo("tipo_de_registro","01");
				$oLayoutTxt->setCampo("tipo_de_processamento","COMPLETA");
	//			$oLayoutTxt->setCampo("codigo_do_vendedor","1");
				$oLayoutTxt->setCampo("codigo_do_vendedor",str_pad($iCodDBUsuario,5,"0",STR_PAD_LEFT));
				$oLayoutTxt->setCampo("data_de_criacao",date('Y-m-d',db_getsession('DB_datausu')));
				$oLayoutTxt->setCampo("hora_de_criacao",date('His'));
				$oLayoutTxt->setCampo("versao_do_programa_de_retaguarda","999.999.999.999");
				$oLayoutTxt->geraDadosLinha();
				
				
				$Item = 0;
				foreach ($aParametros as $oParametro) {
					
					db_atutermometro($Item, $iCountItem, 'termometro', 1, "Processando Arquivos");
					$Item++;
					sleep(1);
					$arquivo = urldecode($oParametro->nome);
					$nomeArquivo = "pat4_criteriumexportaarquivos002_".$arquivo.".php";
	
					if (file_exists($nomeArquivo)) {					
						include ($nomeArquivo);
					}
				}
				
				$oTrailler = new stdClass();
				$oTrailler->tipo_de_registro = "99";
				$oLayoutTxt->setByLineOfDBUtils($oTrailler,5);
				
				$nomearquivos = "tmp/".$sArqName."#Dowload do Arquivo. $sArqName|";
				echo "<script>";
				echo "  listagem = '$nomearquivos';";
				echo "  parent.js_montarlista(listagem,'form1');";
				//echo "  parent.db_iframe_pcfornecertif.hide(); ";
				echo "</script>";
			}else{
				echo "Falha ao gerar arquivo !!!";		
			}
			?>
		</td>
	</tr>
	<tr align="center">
		<td>
			<input type="button" value="Fechar" onclick="parent.db_iframe_exporta.hide();">
		</td>
	</tr>
</table>
</form>
</body>
</html>