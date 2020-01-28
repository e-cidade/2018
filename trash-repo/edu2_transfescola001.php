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
db_postmemory($HTTP_POST_VARS);
$lDbOpcao    = 1;
$lDbBotao    = true;
$iEscola     = db_getsession("DB_coddepto");
$sNomeEscola = db_getsession("DB_nomedepto");
$iModulo     = db_getsession("DB_modulo");
$dDataIniDia = '01';
$dDataIniMes = '01';
$dDataIniAno = date('Y');
$dDataFimDia = date('d');
$dDataFimMes = date('m');
$dDataFimAno = date('Y');
?>
<html>
 <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/webseller.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
 </head>
 <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
   <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr>
     <td width="360" height="18">&nbsp;</td>
     <td width="263">&nbsp;</td>
     <td width="25">&nbsp;</td>
     <td width="140">&nbsp;</td>
    </tr>
   </table>
   <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
     <td valign="top" bgcolor="#CCCCCC">
      <?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
      <br>
      <form name="form1" method="post" action="">
       <fieldset style="width:95%"><legend><b>Relatório de Entradas / Saídas por Transferências</b></legend>
        <table border="0" cellspacing="2" cellpadding="0">
         <tr>
          <td>
           <b>Período:</b>
          </td>
          <td>
           <?db_inputdata('inicio', "$dDataIniDia", "$dDataIniMes", "$dDataIniAno", true, 'text', $lDbOpcao, "")?>
           <b>até</b>
           <?db_inputdata('final', "$dDataFimDia", "$dDataFimMes", "$dDataFimAno", true, 'text', $lDbOpcao,"")?>
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
                     $sSqlEscola     = $oDaoEscola->sql_query_file("", "ed18_i_codigo,ed18_c_nome", "", "");                                                                      
                     $rsResultEscola = $oDaoEscola->sql_record($sSqlEscola);            
                     $iLinhas        = $oDaoEscola->numrows;                       
                     echo '<select name="escola" id="escola" style="height:18px;font-size:10px;">';
                     echo ' <option value="">Selecione a Escola</option>';        
                            for ($iCont = 0; $iCont < $iLinhas; $iCont++) {                       
                              $oDadosEscola = db_utils::fieldsmemory($rsResultEscola, $iCont);                          
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
         <tr>
          <td>
           <b>Movimento:</b>
           </td>
           <td>
            <select id ="movimento" name="movimento" style="font-size:9px;height:18px;">
             <option value="E">ENTRADA (Matrícula)</option>
             <option value="S" selected>SAÍDA (Transferência)</option>
            </select>
          </td>
         </tr>
         <tr>
          <td>
           <b>Tipo:</b>
          </td>
          <td>
           <select id = "tipo" name="tipo" style="font-size:9px;height:18px;">
            <option value="" selected>TODAS</option>
            <option value="R">REDE</option>
            <option value="F">FORA</option>
           </select>
          </td>
         </tr>
         <tr>
          <td>
           <input type="button" value="Processar" onclick="js_processar()">
          </td>
         </tr>
        </table>
       </fieldset>
      </form>
     </td>
    </tr>
   </table>
    <?
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),
              db_getsession("DB_anousu"),db_getsession("DB_instit")
             );
    ?>
 </body>
</html>
<script>
function js_processar() {
	
  if ($('inicio_dia').value == "" || $('inicio_mes').value == "" || $('inicio_ano').value == "" 
      ||$('final_dia').value == "" || $('final_mes').value == "" || $('final_ano').value == "") {
	  
    alert("Preencha as datas corretamente!");
    
  } else {
     
    dDataInicio  = $('inicio_ano').value+"-"+$('inicio_mes').value+"-"+$('inicio_dia').value;
    dDataFim     = $('final_ano').value+"-"+$('final_mes').value+"-"+$('final_dia').value;
    
    if ($('movimento').value == "S") {
        
      jan = window.open('edu2_transfescola002.php?dDataInicio='+dDataInicio+
    	                  '&dDataFinal='+dDataFim+'&sTipo='+$('tipo').value+'&iEscola='+$('escola').value,'',
    	                  'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 '
    	                 );
      
    } else {
          
      jan = window.open('edu2_transfescola003.php?dDataInicio='+dDataInicio+
    	                  '&dDataFinal='+dDataFim+'&sTipo='+$('tipo').value+'&iEscola='+$('escola').value,'',
    	                  'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 '
    	                 );
      
    }
    
    jan.moveTo(0,0);
    
  }
  
}
</script>