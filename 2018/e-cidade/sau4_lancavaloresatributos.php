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

/**
 * 
 * @author I
 * @revision $Author: dbiuri $
 * @version $Revision: 1.1 $
 */
require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_sau_examesatributos_classe.php");
include("dbforms/db_funcoes.php");
require("libs/db_app.utils.php");
$oDaoExames = new cl_sau_examesatributos;
$oGet       = db_utils::postMemory($_GET);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php
db_app::load("scripts.js");
db_app::load("prototype.js");
db_app::load("strings.js");
db_app::load("grid.style.css");
db_app::load("estilos.css");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
    <div style="border-bottom: 2px groove white; background-color: white;width: 100%;height: 8%;tex-align:left">
    <b>informe os valores para os Atributos do Exame</b>
    </div>
    <br>
    <center>
    <table style='border:2px inset white;background: white;width: 99%' cellspacing="0">
    <tr>
       <td class="table_header">Atributo</td>
       <td class="table_header">Valor</td>
       <td class="table_header">Un Medida</td>
       <td class="table_header" style="width: 17px">&nbsp;</td>
       
    </tr>
    <tbody style='background-color: white;height:100px;width: 100%;overflow: scroll; overflow-x: hidden'>   
    <?
     $sWhere         = " s131_i_exames  = {$oGet->iCodigoExame}";
     $iCodigoConfirmaExame = null;
     
     if (isset($oGet->iCodigoConfirmaExame) && $oGet->iCodigoConfirmaExame != "")  {
       $iCodigoConfirmaExame = $oGet->iCodigoConfirmaExame;
     }
     
     $sSqlAtributos = $oDaoExames->sql_query_atributovalores(null,"*","s132_i_codigo", $sWhere, $iCodigoConfirmaExame);
     $rsAtributos   = $oDaoExames->sql_record($sSqlAtributos);
     $aAtributosExames = db_utils::getColectionByRecord($rsAtributos);
     foreach ($aAtributosExames as $oAtributo) {

       echo "<tr style='height:1em'>";
       echo "  <td class='linhagrid'>{$oAtributo->s131_c_descricao}:{$oAtributo->s132_i_codigo}</td>";
       echo "  <td class='linhagrid'>";
       echo "   <input style='width:100%' type='text'     atributo='{$oAtributo->s132_i_codigo}'"; 
       echo "    id='atributo{$oAtributo->s132_i_codigo}' size=10 value='".@$oAtributo->s134_c_valor."'>";
       echo "  </td>";
       echo "  <td class='linhagrid'>{$oAtributo->m61_descr}</td>";
       echo "</tr>";
       
     }
    ?>
    <tr style="height: auto"><td>&nbsp;</td>
    </tr>
    </tbody>
    </table>
    <input type="button" onclick="js_saveValues()" value='Salvar'>
    </center>
</body>
</html>
<script>
function js_saveValues() {
  
  var sRetorno = '';
  var sVirgula = ''; 
  var aInputs  = $$('input[type=text]');
      aInputs.each(function(input, i) {
      if (input.value != "") {
      
        sRetorno += sVirgula+input.getAttribute('atributo')+"-"+input.value;
        sVirgula = ",";
      }
     });
    if (sRetorno != "") {
      parent.$('valoresatributos').value = sRetorno;
    }  
    parent.lkp_atributosexames.hide();
}

function js_getValues() {
  
  var sValores = parent.$('valoresatributos').value;
  var aValores = sValores.split(',');
  for (var i = 0; i < aValores.length; i++) {
    
    var aItens = aValores[i].split("-");
    if (document.getElementById('atributo'+aItens[0])) {
       document.getElementById('atributo'+aItens[0]).value = aItens[1];
    }
  }
}
js_getValues();
</script>