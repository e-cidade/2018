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
include("libs/db_liborcamento.php");
require_once("model/relatorioContabil.model.php");

$oGet = db_utils::postMemory($_GET);
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');
$clrotulo->label('o116_periodo');
$oRelatorio = new relatorioContabil($oGet->c83_codrel);
db_postmemory($HTTP_POST_VARS);
$iAnoUsu   = db_getsession("DB_anousu");
$sFonteRel = "con2_lrfdemonstrativoprojatuarialrpps002_2010.php";
$sLabelMsg = "Anexo XIII - Demonstrativo da Proje��o Atuarial  RPP";
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_emite(){

  var sel_instit  = new Number(document.form1.db_selinstit.value);
  var sel_periodo = document.form1.o116_periodo.value;

  <?
		if(!file_exists($sFonteRel)) {
			echo "alert('Relat�rio n�o dispon�vel para o exerc�cio $iAnoUsu');";
		  echo "return false;";
		}
  ?>
  
  if (sel_periodo == "0"){
    alert("Selecione um periodo");
    return false;
  }
  
  sel_instit  = new Number(document.form1.db_selinstit.value);
  if(sel_instit == 0){
    alert('Voc� n�o escolheu nenhuma Institui��o. Verifique!');
    return false;
  }else{
    var query = "";
    var obj   = document.form1;
    
    query  = "db_selinstit="+obj.db_selinstit.value;
    query += "&periodo="+obj.o116_periodo.value;
  
    obj = document.form1;
    jan = window.open('<?=$sFonteRel?>?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <form name="form1" method="post" action="" >
	  <table align="center" border="0" cellpadding="0" cellspacing="0">
	   <tr>
	    <td >&nbsp;</td>
	   </tr>
	   <tr>
	    <td colspan=3  class='table_header'>
	     <?=$sLabelMsg?>
	    </td>
	   </tr>  
	   <tr>
	    <td>
	      <fieldset>
	       <legend><b>Filtros</b></legend>
			    <table  align="center">
			      <tr>
			        <td align="center" colspan="2">
			          <?  
			            db_selinstit('',300,100); 
			          ?>
			        </td>
			      </tr>
			      <tr>
			        <td align="right" nowrap>
			          <b>Bimestre :</b>
			        </td>
			        <td>
			          <?			
			           if ($iAnoUsu < 2010 ) {
              
                   $aListaPeriodos = array(
                                    "1B" => "1 � Bimestre",
                                    "2B" => "2 � Bimestre",
                                    "3B" => "3 � Bimestre",
                                    "4B" => "4 � Bimestre",
                                    "5B" => "5 � Bimestre",
                                    "6B" => "6 � Bimestre",
                                    );
                  } else {            

                     $aPeriodos = $oRelatorio->getPeriodos();
                     $aListaPeriodos = array();
                     $aListaPeriodos[0] = "Selecione";
                     foreach ($aPeriodos as $oPeriodo) {
                       $aListaPeriodos[$oPeriodo->o114_sequencial] = $oPeriodo->o114_descricao;
                     }
                  }
                  db_select("o116_periodo", $aListaPeriodos, true, 1);
			          ?>
			        </td> 
			      </tr>
			    </table>	       
	      </fieldset>
	      <table align="center">
	        <tr>
            <td>&nbsp;</td>	       
	        </tr>
          <tr>
            <td align="center" colspan="2">
               <input  name="emite" id="emite" type="button" value="Imprimir" onclick="js_emite();">
            </td>
          </tr>	      
	      </table>
	    </td>
	   </tr>
	  </table> 
  </form>
</body>
</html>