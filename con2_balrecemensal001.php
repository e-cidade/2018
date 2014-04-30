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


require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");
include ("libs/db_liborcamento.php");

db_postmemory($HTTP_POST_VARS);
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_emite() {

  obj = document.form1;	
  sel_instit  = new Number(document.form1.db_selinstit.value);
  var periodo = document.form1.periodo.value;
  var dtLimit = document.form1.dtLimit.value;
  if (sel_instit == 0) {
  
    alert('Você não escolheu nenhuma Instituição. Verifique!');
    return false;
  }
  
  if(periodo == 0){
    alert('Você não escolheu nenhum Período!');
    return false;
  }
  
  jan = window.open('con2_balrecemensal002.php?dtLimit='+dtLimit+'&periodo='+periodo+'&db_selinstit='+
                     document.form1.db_selinstit.value,'','width='+(screen.availWidth-5)+',height='+
                     (screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}

</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<div style="margin-top: 25px;"></div>
<center>
  <div style="width: 350px; margin-top: 5px;">
    <div style="font-weight: bold; background-color: #EFF0F2; width: 330px; height: 15px; padding: 5px">Relátório de Receita Mensal</div>
    <fieldset>
      <form name="form1" method="post" action="">
        <table  align="center" border=0>
          <tr>
             <td align="center" colspan="2">
             <?
               db_selinstit('', 300, 100);
             ?>
             </td>
          </tr>
          <tr>
            <td>
              <strong>Período:</strong>
            </td>
            <td>
              <?                 
                $aListaPeriodos = array();
                $aListaPeriodos[0] = "Selecione";
                $aListaPeriodos[1] = "Anual";
                $aListaPeriodos[2] = "1º Semestre";
                $aListaPeriodos[3] = "2º Semestre";
                
                db_select("periodo", $aListaPeriodos, true, 1);
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <strong>Posição até:</strong>
            </td>
            <td>
              <?                 
                db_inputdata('dtLimit',"","","",true,'text',1,"");
              ?>
            </td>
          </tr>
        </table>
      </form>
    </fieldset>
  </div>
  <div >
    <input type="submit" value="Imprimir" onClick="js_emite();">
  </div>
</center>
<?
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>