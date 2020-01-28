<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_lote_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("classes/db_empempenho_classe.php");
require_once("libs/db_liborcamento.php");
require_once("model/relatorioContabil.model.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clempempenho = new cl_empempenho;
$clrotulo     = new rotulocampo;
$cllote       = new cl_lote;
$cllote->rotulo->label();

$iAnoUsu = db_getsession("DB_anousu");
$oRelatorio = new relatorioContabil(122);
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
<body bgcolor=#CCCCCC bgcolor="#CCCCCC"  >
<form name="form1" method="post"  >

<center>

  <fieldset style="width: 350px; margin-top: 20px;">
    <legend><strong>Demonstrativo de Despesas com Saúde</strong></legend>
    
      <fieldset style="margin-top: 10px;">
        <legend><strong>Instituição</strong></legend>
           <?
             db_selinstit('',300,100);
           ?>
      </fieldset>
      
      <div style="margin-top: 10px;">
        <table width='100%'>
          <tr>
            <td align="left" width="10%" nowrap="nowrap">
              <strong>Período:</strong>
            </td>  
            <td align="left">
            
			          <?			
			           if ($iAnoUsu < 2010 ) {
              
                   $aListaPeriodos = array(
                                    "1B" => "1 º Bimestre",
                                    "2B" => "2 º Bimestre",
                                    "3B" => "3 º Bimestre",
                                    "4B" => "4 º Bimestre",
                                    "5B" => "5 º Bimestre",
                                    "6B" => "6 º Bimestre",
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
      </div>
      
  </fieldset>
  
  <div style="margin-top: 10px;">
    <input type='button' value="Imprimir" id='imprimir' onclick = "js_imprimir();" />
  </div>
</center>
</form>

</body>
</html>

<script>

function js_imprimir(){

  var sel_instit = document.form1.db_selinstit.value;
  var sPeriodo   = $F("o116_periodo");
  
  if(sel_instit == 0){
    alert('Você não escolheu nenhuma Instituição. Verifique!');
    return false;
  }

  if (sPeriodo == 0) {
    alert("Selecione um Período !!");
    return false;
  }
  
  var sFonte  = "con2_despesacomsaude003.php";  
  var sQuery  = "";
  
      sQuery  = "?sInstituicao=" + sel_instit;
      sQuery += "&sPeriodo="     + sPeriodo;
  
  jan = window.open(sFonte+sQuery,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
               
</script>