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
require("libs/db_conecta.php");
require("libs/db_utils.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_rhemissaocheque_classe.php");
include("classes/db_rhemissaochequeitem_classe.php");

$oPost = db_utils::postMemory($_POST);

$clrhemissaocheque     = new cl_rhemissaocheque();
$clrhemissaochequeitem = new cl_rhemissaochequeitem();

$clrhemissaocheque->rotulo->label();

$db_opcao = 1;

if ( isset($oPost->cancelar) ) {
	
	$lSqlErro = false;
	
	db_inicio_transacao();

	$aLotes  = array();  
	
	$sWhere  = "     r18_anousu = {$oPost->anofolha}  ";
	$sWhere .= " and r18_mesusu = {$oPost->mesfolha}  ";
	$sWhere .= " and r18_tipo   = {$oPost->tipoFolha} ";
	
	$sSqlCheque        = $clrhemissaochequeitem->sql_query_file(null,"distinct r18_emissaocheque",null,$sWhere);
	$rsConsultaCheques = $clrhemissaochequeitem->sql_record($sSqlCheque);
	$iLinhasCheques    = $clrhemissaochequeitem->numrows;
	
	if ( $iLinhasCheques > 0 ) {
		
		for( $iInd=0; $iInd < $iLinhasCheques; $iInd++ ){
			
			$oCheques = db_utils::fieldsMemory($rsConsultaCheques,$iInd);
			$aLotes[] = $oCheques->r18_emissaocheque; 
			
		}
		
		$clrhemissaochequeitem->excluir(null,$sWhere);
		
		if ( $clrhemissaochequeitem->erro_status == 0 ) {
			$lSqlErro = true;
		}
	
		$sMsgErro = $clrhemissaochequeitem->erro_msg;
		
		
		if ( !$lSqlErro ) {
   
			$sWhereLote  = "     r15_sequencial in (".implode(",",$aLotes).") "; 
			$sWhereLote .= " and r18_sequencial is null                       ";
			
			$sSqlLote        = $clrhemissaocheque->sql_query_item(null,"distinct r15_sequencial",null,$sWhereLote);
			$rsConsultaLotes = $clrhemissaocheque->sql_record($sSqlLote);
			$iLinhasLote     = $clrhemissaocheque->numrows;
			
			for ( $iInd=0; $iInd < $iLinhasLote; $iInd++ ) {
				
				$oLote = db_utils::fieldsMemory($rsConsultaLotes,$iInd);
				
				$clrhemissaocheque->excluir($oLote->r15_sequencial);
				
			  if ( $clrhemissaocheque->erro_status == 0 ) {
			    $lSqlErro = true;
			  }
			
			  $sMsgErro = $clrhemissaocheque->erro_msg;
			}
			  		
		}
		
	} else {
		$lSqlErro = true;
		$sMsgErro = "Nenhum registro encontrado!";
	}
	
	
	db_fim_transacao($lSqlErro);
	
} else  {
	
 $anofolha = db_anofolha();
 $mesfolha = db_mesfolha();
	
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<form name="form1" method="post" action="">
	<table align="center" style="padding-top:25px;">
	  <tr>
	    <td colspan="2">
			  <fieldset>
			    <legend align="center">
			      <b>Cancela Geraçao de Cheques por Mês/Tipo</b>
			    </legend>
				  <table>
				    <tr>
				      <td align="right">
				        <b>Ano/Mês:</b>
				      </td>
				      <td>
				        <?
                  db_input("anofolha",4,"",true,"text",$db_opcao,"");
                  db_input("mesfolha",2,"",true,"text",$db_opcao,"");
				        ?>
				      </td>
				    </tr>
            <tr>
              <td align="right">
                <b>Tipo Geração:</b>
              </td>
              <td>
                <?
                   $aTipo = array("f"=>"Funcionários",
                                  "p"=>"Pensão Alimentícia");
                   db_select("tipoGera",$aTipo,true,1,"onChange='document.form1.submit();' style='width:150px;'");
                ?>
              </td>             
            </tr>     				    
            <tr>
              <td align="right">
                <b>Tipo Folha:</b>
              </td>
              <td>
                <?  
                  if ( isset($oPost->tipoGera) && $oPost->tipoGera == "p" ) {
			               $aTipoFolha = array(
			                                    "7"=>"Salário",
			                                    "8"=>"Complementar",
			                                    "9"=>"Rescisão",
			                                    "10"=>"13o. Salário",
			                                    "6"=>"Férias"
			                                  );
			            } else {
			               $aTipoFolha = array(
			                                    "1"=>"Salário",
			                                    "2"=>"Complementar",
			                                    "3"=>"Rescisão",
			                                    "4"=>"13o. Salário",
			                                    "5"=>"Adiantamento"
			                                  );                                           
			            }
                 
                  db_select("tipoFolha",$aTipoFolha,true,1,"style='width:150px;'");
                   
                ?>
              </td>             
            </tr>            
				  </table>
			  </fieldset>
		  </td>
	  </tr>
	  <tr>
	    <td align="center"> 
	      <input name="cancelar"  type="submit" value="Processar" onClick='return js_cancelar();'>
	    </td>
   </tr>
	</table> 	  
</form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>


function js_cancelar(){

  var doc = document.form1; 
  
  if ( doc.anofolha.value == "" || doc.mesfolha.value == "") {
    alert("Informe corretamente os campos Ano/Mês! ");
    return false;
  }

}

</script>

<?
   
   if ( isset($oPost->cancelar) ) {

   	 db_msgbox($sMsgErro);
   	 
   	 if ( !$lSqlErro ) {
   	 	  echo "<script>document.location.href='pes4_cancgeracaomestipo001.php'</script>";
   	 }
   	
   }

?>