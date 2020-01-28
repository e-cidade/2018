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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
$iModulo     = db_getsession("DB_modulo");
$sNomeEscola = db_getsession("DB_nomedepto");
$iAnoCenso   = date("Y");

for ($iCont = 1; $iCont <= 31; $iCont++) {
	
  if (date("w",mktime(0,0,0,5,$iCont,$iAnoCenso)) == 3) {
  	
    $dDataCensoDia = strlen($iCont) == 1 ? "0".$iCont : $iCont;
    $dDataCensoMes = "05";
    $dDataCensoAno = $iAnoCenso;
    
  }
  
}
$dDataCenso = $dDataCensoDia."/".$dDataCensoMes."/".$dDataCensoAno;
?>
<html>
 <head>
  <title>DBSeller Inform&aacute;tica Ltda</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/webseller.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
 </head>
 <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" > 
  <table width="790" height="18"  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
   <tr>
    <td>&nbsp;</td>
   </tr>
  </table>
  <form name="form1" method="post" action="">
   <center>
    <?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
    <br>
    <fieldset style="width:95%"><legend><b>Relatório Anual de Matrículas</b></legend>
     <table width="80%" border="0" align="left">
      <tr>
       <td colspan="3">
        <table border="0" align="left">
         <tr>
          <td nowrap>
           <b>Data do Censo:</b>
          </td>
          <td nowrap>
            <?
             db_inputdata('dDataCenso',@$dDataCensoDia,@$dDataCensoMes,@$dDataCensoAno,true,
                          'text',1," onchange=\"js_ano();\"","","","parent.js_ano();"
                         );
              echo "<b>Ano:</b> ";
              db_input('iAnoCenso',4,@$iAnoCenso,true,'text',3,"")?>
          </td>
         </tr>
         <tr>    
          <?
            if ($iModulo == 7159) {   
            	    	     
              echo '<td align="left">'; 
              echo ' <b>Selecione a escola:</b>';
              echo '</td>';
              echo '<td>';              
                     $oDaoEscola     = db_utils::getdao('escola');              
                     $sSqlEscola     = $oDaoEscola->sql_query_file("","ed18_i_codigo,ed18_c_nome","","");                                                                      
                     $rsResultEscola = $oDaoEscola->sql_record($sSqlEscola);            
                     $iLinhas        = $oDaoEscola->numrows;                       
                     echo '<select name="escola" id="escola" style="height:18px;font-size:10px;">';
                     echo ' <option value="">Selecione a Escola</option>';        
                            for ($iCont = 0; $iCont < $iLinhas; $iCont++) {                       
                              $oDadosEscola = db_utils::fieldsmemory($rsResultEscola,$iCont);                          
                              echo " <option value='$oDadosEscola->ed18_i_codigo'>$oDadosEscola->ed18_c_nome</option>";               
                            }                   
                     echo ' </select>';
                     echo '</td>';
            } else {
            	
              $iEscola = db_getsession("DB_coddepto");
              echo "<input type= 'hidden' id ='escola' value = '$iEscola' >";
              
            }
            ?>          
         </tr>
        </table>
       </td>
      </tr>
      <tr>
       <td colspan="3">
         <input name="pesquisar" type="button" id="pesquisar" value="Processar" onclick="js_pesquisa();">
         <br><br>
       </td>
      </tr>
     </table>
    </fieldset>
   </center>
  </form>
   <?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),
             db_getsession("DB_anousu"),db_getsession("DB_instit")
            );
   ?>
 </body>
</html>
<script>
function js_ano() {
	
  data = document.form1.dDataCenso.value;
  if (data != "") {
	  
    data = data.split("/");
    document.form1.iAnoCenso.value = data[2];
    
  } else {
    document.form1.iAnoCenso.value = "";
  }
  
}

function js_pesquisa() {
	
  if (document.form1.dDataCenso.value == "" || document.form1.iAnoCenso.value == "") {
	  
    alert("Informe a data do censo!");
    return false;
    
  }
  
  jan = window.open('edu2_relatanual002.php?data_censo='+document.form1.dDataCenso.value+
		            '&ano_censo='+document.form1.iAnoCenso.value+'&iEscola='+$('escola').value,'',
		            'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 '
		           );
  jan.moveTo(0,0);
  
}
</script>