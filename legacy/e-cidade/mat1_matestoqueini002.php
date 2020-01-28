<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
require("libs/db_utils.php");
include("libs/db_usuariosonline.php");
include("classes/db_matestoque_classe.php");
include("classes/db_matestoqueitem_classe.php");
include("classes/db_matestoqueini_classe.php");
include("classes/db_matestoqueinil_classe.php");
include("classes/db_matestoqueinill_classe.php");
include("classes/db_matestoqueinimei_classe.php");
include("classes/db_transmater_classe.php");
include("classes/db_empempitem_classe.php");
include("dbforms/db_funcoes.php");
require_once("classes/db_matestoqueitemnotafiscalmanual_classe.php");

db_postmemory($HTTP_POST_VARS);

$clmatestoque = new cl_matestoque;
$clmatestoqueitem = new cl_matestoqueitem;
$clmatestoqueini = new cl_matestoqueini;
$clmatestoqueinil = new cl_matestoqueinil;
$clmatestoqueinill = new cl_matestoqueinill;
$clmatestoqueinimei = new cl_matestoqueinimei;
$clempempitem = new cl_empempitem;
$cltransmater = new cl_transmater;

$oDaoMatEstoqueItemNotaFiscal = db_utils::getDao("matestoqueitemnotafiscalmanual");
$db_opcao = 22;
$db_botao = false;
$passou=false;

if(isset($alterar)){
  if(isset($m60_codmater) && trim($m60_codmater)!=""){
		if ($m71_valor == 0 or $m71_quant == 0) {
			$sqlerro = true;
			$erro_msg = "Valores zerados!";
		} else {
			$sqlerro = false;
			db_inicio_transacao();
                        $m80_codigo = (isset($m80_codigo)&&!empty($m80_codigo))?$m80_codigo:'null';
			$result_matestoque = $clmatestoqueini->sql_record(
			                     $clmatestoqueini->sql_query_mater(
			                                                       null,
			                                                       "m70_quant,
			                                                       m70_valor,
			                                                       m71_quantatend,
			                                                       m71_quant as quantretira,
			                                                       m71_valor as valorretira,
			                                                       m77_sequencial,
			                                                       m78_sequencial
			                                                       ","",
			                                                       "matestoqueini.m80_codigo=$m80_codigo
			                                                        and
			                                                        m70_codigo=$m70_codigo and m71_codlanc=$m71_codlanc"));
			                                                        
			if($clmatestoqueini->numrows>0){
				db_fieldsmemory($result_matestoque,0);
				if($m80_codtipo==1){
					$m80_codtipo = 14;
				}else if($m80_codtipo==3){
					$m80_codtipo = 15;
				}
				$m80_login = db_getsession("DB_id_usuario");
				$m80_data  = date("Y-m-d",db_getsession("DB_datausu"));
				$m80_hora  = date('H:i:s');
				$m80_coddepto = $coddepto;
				
				if($sqlerro==false){
					$clmatestoqueini->m80_login          = $m80_login;
					$clmatestoqueini->m80_obs            = $m80_obs;
					$clmatestoqueini->m80_codtipo        = $m80_codtipo;
					$clmatestoqueini->m80_coddepto       = $m80_coddepto;
					$clmatestoqueini->alterar($m80_codigo);
					$matestoqueininovo = $clmatestoqueini->m80_codigo;
					$erro_msg = $clmatestoqueini->erro_msg;
					if($clmatestoqueini->erro_status==0){
					  
					  $erro_msg = $clmatestoqueini->erro_msg;
						$sqlerro  = true;
					}
				}
				
				if ($sqlerro == false) {
				  if($m71_quant>=$m71_quantatend){
					 // ---------- encontrar o valor que existia na inclusão da implantação -------------
					 // soma o valor do matestoque com o valor unit antigo vezes a quantidade atendida e subtrai o valor unit antigo
					 // vezes a quantidade antiga de itens...
					 // ---------------------------------------------------------------------------------
					 // somar valor encontrado com a quantidade que sobra (quantidade nova - quantidade atendida) multiplicada pelo
					 // novo valor unitário dos itens...
					 $valorimprime = $m70_valor+(($valorretira/$quantretira)*$m71_quantatend)-(($valorretira/$quantretira)*$quantretira)+(($m71_quant-$m71_quantatend)*$m71_valorunit);
					 $clmatestoque->m70_codigo = $m70_codigo;
					 //$clmatestoque->m70_valor  = $valorimprime;
					 $val1=$m70_valor-$valorretira;
					 $val2=$val1+$m71_valor;
					 $clmatestoque->m70_valor = "$val2";
					 $quant2  = $m70_quant+$m71_quant-$quantretira;
					 $clmatestoque->m70_quant  = "$quant2";
					 $clmatestoque->m70_coddepto=$coddepto;
					 $clmatestoque->alterar($m70_codigo);
					 if($clmatestoque->erro_status==0){
					 	$erro_msg = $clmatestoque->erro_msg;
					 	$sqlerro=true;
					 }
					 if($sqlerro == false){
					   
					 	if(isset($m70_codigo) && trim($m70_codigo)!=""){	
					 		$clmatestoqueitem->m71_codlanc       = $m71_codlanc;
					 		$clmatestoqueitem->m71_codmatestoque = $m70_codigo;
					 		$clmatestoqueitem->m71_valor         = $m71_valor;
					 		$clmatestoqueitem->m71_quant         = $m71_quant;
					 		$clmatestoqueitem->alterar($m71_codlanc);
					 		if($clmatestoqueitem->erro_status==0){
					 			$erro_msg = $clmatestoqueitem->erro_msg;
					 			$sqlerro=true;
					 		}
					 	}
					 	
					 	/**
					 	 * Inclui ou altera os dados da nota fiscal digitada pelo usuário
					 	 */
					 	if (!$sqlerro) {
					 	  
					 	  if (!empty($m79_notafiscal) && !empty($m79_data)) {
					 	  
					 	    $oDaoMatEstoqueItemNotaFiscal->m79_sequencial     = $m79_sequencial;
					 	    $oDaoMatEstoqueItemNotaFiscal->m79_matestoqueitem = $m71_codlanc;
					 	    $oDaoMatEstoqueItemNotaFiscal->m79_notafiscal     = $m79_notafiscal;
					 	    $oDaoMatEstoqueItemNotaFiscal->m79_data           = $m79_data;
					 	    if (empty($m79_sequencial)) {
					 	      $oDaoMatEstoqueItemNotaFiscal->incluir(null);
					 	    } else {
					 	      $oDaoMatEstoqueItemNotaFiscal->alterar($m79_sequencial);
					 	    }
					 	    
					 	    if ($oDaoMatEstoqueItemNotaFiscal->erro_status == 0) {
					 	    
					 	      $erro_msg = $oDaoMatEstoqueItemNotaFiscal->erro_msg;
					 	      $sqlerro  = true;
					 	    }
					 	    
					 	  } else if ( empty($m79_notafiscal) && empty($m79_data) ) {
					 	    
					 	    if (!empty($m79_sequencial)) {
					 	      
					 	      $oDaoMatEstoqueItemNotaFiscal->excluir($m79_sequencial);
					 	      if ($oDaoMatEstoqueItemNotaFiscal->erro_status == 0) {
					 	        
					 	        $erro_msg = $oDaoMatEstoqueItemNotaFiscal->erro_msg;
					 	        $sqlerro  = true;
					 	      }
					 	    }
					 	  }
					 	}
					 	
        	 			if ($sqlerro == false) {
        	 			  
        	 			  if (trim($m77_lote) != "") {
        	 			    
        	 			    if ($m77_sequencial == null) {
        	 			      
        	 			      $clmatestoqueitemlote = db_utils::getDao("matestoqueitemlote");
        	 			      $clmatestoqueitemlote->m77_lote = $m77_lote;
        	 			      $clmatestoqueitemlote->m77_dtvalidade = implode("-",array_reverse(explode("/", $m77_dtvalidade)));
        	 			      $clmatestoqueitemlote->m77_matestoqueitem = $m71_codlanc;
        	 			      $clmatestoqueitemlote->incluir(null);
        	 			      if ($clmatestoqueitemlote->erro_status == 0){
				       
        	 			        $erro_msg = $clmatestoqueitemlote->erro_msg;
        	 			        $sqlerro  = true;
				       
        	 			      }
        	 			    } else {
        	 			      
        	 			      $clmatestoqueitemlote = db_utils::getDao("matestoqueitemlote");
        	 			      $clmatestoqueitemlote->m77_lote = $m77_lote;
        	 			      $clmatestoqueitemlote->m77_dtvalidade = implode("-",array_reverse(explode("/", $m77_dtvalidade)));
        	 			      $clmatestoqueitemlote->m77_sequencial = $m77_sequencial;
        	 			      $clmatestoqueitemlote->alterar($m77_sequencial);
        	 			      if ($clmatestoqueitemlote->erro_status == 0){
        	 			      
        	 			        $erro_msg = $clmatestoqueitemlote->erro_msg;
        	 			        $sqlerro  = true;
        	 			      
        	 			      }
        	 			    }
        	 			  } else if ($m77_sequencial != null){
        	 			    
        	 			    $clmatestoqueitemlote = db_utils::getDao("matestoqueitemlote");
        	 			    $clmatestoqueitemlote->excluir($m77_sequencial);
        	 			    if ($clmatestoqueitemlote->erro_status == 0){
        	 			      
        	 			      $erro_msg = $clmatestoqueitemlote->erro_msg;
        	 				  $sqlerro  = true;
        	 			      
        	 			    }
        	 			  }
        	 			}
        	 			if (!$sqlerro) {
           
        	 			  if (trim($m78_matfabricante) != "") {
        	 			  
        	 			    if ($m78_sequencial == null) {
        	 			      
        	 			      $clmatestoqueitemfabric = db_utils::getDao("matestoqueitemfabric");
				               $clmatestoqueitemfabric->m78_matestoqueitem = $m71_codlanc;
				               $clmatestoqueitemfabric->m78_matfabricante  = $m78_matfabricante;
				               $clmatestoqueitemfabric->incluir(null);
				               if ($clmatestoqueitemfabric->erro_status  == 0) {
           
				                 $erro_msg = $clmatestoqueitemfabric->erro_msg;
				                 $sqlerro   = true;
				       
				               }          				      
        	 			    } else {
        	 			      
        	 			      $clmatestoqueitemfabric = db_utils::getDao("matestoqueitemfabric");
				               $clmatestoqueitemfabric->m78_matfabricante  = $m78_matfabricante;
				               $clmatestoqueitemfabric->m78_sequencial     = $m78_sequencial;
				               $clmatestoqueitemfabric->alterar($m78_sequencial);
				               if ($clmatestoqueitemfabric->erro_status  == 0) {
           
				                $erro_msg = $clmatestoqueitemfabric->erro_msg;
				                $sqlerro  = true;
				               
				               }
        	 			    }
				           } else if ($m78_sequencial != null){
				             
				             $clmatestoqueitemfabric = db_utils::getDao("matestoqueitemfabric");
				             $clmatestoqueitemfabric->excluir($m78_sequencial);
				             if ($clmatestoqueitemfabric->erro_status  == 0) {
           
				               $erro_msg = $clmatestoqueitemfabric->erro_msg;
				               $sqlerro  = true;
				               
				             }
				             
				           }
        	 			}
					 	if($sqlerro == false){
					 		$result_inimei = $clmatestoqueinimei->sql_record($clmatestoqueinimei->sql_query_file(null,"m82_codigo","","m82_matestoqueitem=$m71_codlanc and m82_matestoqueini=$m80_codigo"));
					 		if($clmatestoqueinimei->numrows>0){
					 			db_fieldsmemory($result_inimei,0);
					 			$clmatestoqueinimei->m82_codigo         = $m82_codigo;
					 			$clmatestoqueinimei->m82_matestoqueitem = $m71_codlanc;
					 			$clmatestoqueinimei->m82_matestoqueini  = $m80_codigo;
					 			$clmatestoqueinimei->m82_quant          = $m71_quant;
					 			$clmatestoqueinimei->alterar($m82_codigo);
					 			if($clmatestoqueinimei->erro_status==0){
					 				$erro_msg = $clmatestoqueiniimei->erro_msg;
					 				$sqlerro=true;
					 			}
					 		}
					 	}
					 }
				  }else{
				  	$sqlerro  = true;
				  	$erro_msg = "Usuário:\\n\\nAlteração não efetuada.\\nA quantidade a ser alterada deve ser menor que $m70_quant.\\n\\nAdministrador:";
				  }
				}
			}
			if ($sqlerro==false){
				$passou=true;
			}
			db_fim_transacao($sqlerro);
		}
  }else{
    $sqlerro = true;
    $erro_msg = "Usuário: \\n\\nCódigo do material não informado.\\n\\nAdministrador:";
  }
}else if(isset($chavepesquisa) || (isset($m60_codmater) && trim($m60_codmater)!="")){
  $db_opcao = 2;
  if(isset($chavepesquisa)){
    $result = $clmatestoqueini->sql_record($clmatestoqueini->sql_query_mater(
                                           $chavepesquisa,
                                           "matestoqueini.m80_codigo,
                                           m70_codigo,m71_codlanc,
                                           m71_quantatend,
                                           m70_quant,
                                           m60_codmater,
                                           m60_descr,
                                           coddepto,
                                           descrdepto,
                                           m71_quant,
                                           m77_dtvalidade,
                                           m77_lote,
                                           m78_matfabricante,
                                           m76_nome,
                                           m71_valor,
                                           m79_sequencial,
                                           m79_notafiscal,
                                           m79_data,                                           
                                           (m71_valor/m71_quant) as m71_valorunit,
                                           matestoqueini.m80_obs"
                                           )); 

    if($clmatestoqueini->numrows>0){
      db_fieldsmemory($result,0);
      
      if ($m77_dtvalidade != "") {
        list($m77_dtvalidade_ano,$m77_dtvalidade_mes,$m77_dtvalidade_dia) = explode("-",$m77_dtvalidade);
      }
      $db_botao = true;
    }else{
      $db_opcao = 22;
    }
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.m71_quant.select();">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
<tr> 
<td width="360" height="18">&nbsp;</td>
<td width="263">&nbsp;</td>
<td width="25">&nbsp;</td>
<td width="140">&nbsp;</td>
</tr>
</table>
<center>
<?
if($passou==false){
  include("forms/db_frmmatestoqueini.php");
}
?>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($alterar)){
  db_msgbox($erro_msg);
  echo "<script>location.href='mat1_matestoqueini002.php';</script>";
};
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>