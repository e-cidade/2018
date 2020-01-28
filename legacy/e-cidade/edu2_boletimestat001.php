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
require_once("classes/db_ensino_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
db_postmemory($HTTP_POST_VARS);
$oDaoEnsino  = db_utils::getdao('ensino');
$db_opcao    = 1;
$db_botao    = true;
$sNomeEscola = db_getsession("DB_nomedepto");
$iModulo     = db_getsession('DB_modulo');
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
  <a name="topo"></a>
  <?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
  <form name="form1" method="post" action="">
   <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
     <td valign="top" bgcolor="#CCCCCC">
      <br>
      
      <fieldset style="width:95%">
        <legend>
          <b>Relatório Boletim Estatístico</b>
        </legend>
       <table border="0">
        <tr>
         
          <?    
            if ($iModulo == 7159) {   
                         
              echo '<td align="left">'; 
              echo ' <b>Selecione a escola:</b>';    
              echo '</td>';
              echo '<td>';       
                      $oDaoEscola     = db_utils::getdao('escola');              
                      $sSqlEscola     = $oDaoEscola->sql_query_file("", "ed18_i_codigo, ed18_c_nome", "", "");                                                                      
                      $rsEscola       = $oDaoEscola->sql_record($sSqlEscola);            
                      $iLinhas        = $oDaoEscola->numrows;                       
                      echo '<select name="escola" id="escola" onChange="js_escola(this.value);" 
                                    style="font-size:9px;width:250px;height:18px;">';
                      echo ' <option value="">Selecione a Escola</option>';        
                     
                              for ($iCont = 0; $iCont < $iLinhas; $iCont++) {
                                                     
                                $oDadosEscola = db_utils::fieldsmemory($rsEscola, $iCont);                          
                                echo " <option value='$oDadosEscola->ed18_i_codigo'>$oDadosEscola->ed18_c_nome</option>";
                                             
                              } 
                                              
                      echo ' </select>';
                      echo '</td>';
                     
            } else {
              
              $iEscola = db_getsession("DB_coddepto");
              echo "<input type= 'hidden' id ='escola' value = '$iEscola' >";
              
             }
            ?> 
            <tr> 
            
          <td align="left">
           <b>Selecione o Calendário:</b>
           </td>
           <td>
           <select name="calendario" id= "calendario" onchange="js_ensino($('escola').value, this.value)"  
                   style="font-size:9px;width:250px;height:18px;">        
           </select>
          </td>
         </tr>
         <tr>
          <td align="left">
           <b>Selecione o Ensino:</b>
           </td>
           <td>
           <select name="ensino" id= "ensino" style="font-size:9px; width:250px; height:18px;" disabled>
                   
           </select>
          </td>
         </tr>
         <tr>
          <td>
           <b>Selecione o Mês:</b>
           </td>
           <td>
           <select name="mes" id="mes" style="font-size:9px;width:250px;height:18px;">
            <option value=""></option>
            <option value="1">JANEIRO</option>
            <option value="2">FEVEREIRO</option>
            <option value="3">MARÇO</option>
            <option value="4">ABRIL</option>
            <option value="5">MAIO</option>
            <option value="6">JUNHO</option>
            <option value="7">JULHO</option>
            <option value="8">AGOSTO</option>
            <option value="9">SETEMBRO</option>
            <option value="10">OUTUBRO</option>
            <option value="11">NOVEMBRO</option>
            <option value="12">DEZEMBRO</option>
           </select>
          </td>
         </tr>
         <tr>
          <td colspan="2">
           <input type="checkbox" name="imprime_lista" id = "imprime_lista" value="" checked>
            <b>Imprimir listagem de alunos</b>
          </td>
         </tr>
         <tr>
          <td valign='bottom'>
           <input type="button" name="procurar" value="Processar" 
                 onclick="js_procurar($('calendario').value, $('mes').value, $('ensino').value, $('escola').value)">
          </td>
         </tr>
        </table>
       </fieldset>
      </td>
     </tr>
    </table>
   </form>
    <?
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),
              db_getsession("DB_anousu"),db_getsession("DB_instit")
             );
    ?>
 </body>
</html>
<script>
function js_procurar(calendario, mes, ensino, escola) {
  
  if (calendario != "" && mes != "") {
    
    if (document.form1.imprime_lista.checked == true) {
      imprime_lista = "yes";
    } else {
      imprime_lista = "no";
    }
    
    jan = window.open('edu2_boletimestat002.php?iCalendario='          + $('calendario').value +
                                                     '&iMes='          + $('mes').value        +
                                                     '&sEnsino='       + $('ensino').value     + 
                                                     '&sImprimeLista=' + imprime_lista         +
                                                     '&iEscola='       + $('escola').value,'',
                      'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 '
                     );
    jan.moveTo(0,0);
    
  } else {
    alert("Informe o Ano e o Mês!");
  }
  
}


function js_escola(escola) {
  
  var oParam    = new Object(); 

  oParam.exec   = "PesquisaCalendario";
  oParam.escola =  escola;

  var url       = 'edu4_escola.RPC.php';    

  js_webajax(oParam, 'js_retornoPesquisaCalendario', url);

}

function js_retornoPesquisaCalendario(oRetorno) {
            

  var oRetorno = eval("("+oRetorno.responseText+")");
  sHtml        = '';
      
  if (oRetorno.iStatus  != 1) {
        
    alert(oRetorno.sMessage.urlDecode());
    return false;
    
  } else {
         
    sHtml += '<option value="">Selecione o Calendário</option>';                  
       
    for (var i = 0;i < oRetorno.aResult.length; i++) {
                        
      with (oRetorno.aResult[i]) {
                          
        sHtml += '<option value="'+oRetorno.aResult[i].ed52_i_codigo+'">';
        sHtml += oRetorno.aResult[i].ed52_c_descr.urlDecode()+'</option>';
                           
      }   
                
    }
      
    $('calendario').innerHTML             = sHtml;
    document.form1.calendario[0].selected = true;

  }  
      
  $('calendario').disabled  = false;
    
}


function js_ensino(escola, calendario) {

  var oParam        = new Object(); 
  oParam.exec       = "PesquisaEnsino";
  oParam.escola     =  escola;
  oParam.calendario =  calendario;

  var url           = 'edu4_escola.RPC.php';    

  js_webajax(oParam, 'js_retornoPesquisaEnsino', url);
        
}

function js_retornoPesquisaEnsino(oRetorno) {
        

  var oRetorno = eval("("+oRetorno.responseText+")");
  sHtml        = '';
        
  if (oRetorno.iStatus  != 1) {
          
    alert(oRetorno.sMessage.urlDecode());
    return false;
      
  } else {
           
    sHtml += '<option value="">Selecione o Ensino</option>'; 
    sHtml += '<option value="">TODOS</option>';                  
         
    for (var i = 0;i < oRetorno.aResultEnsino.length; i++) {
                          
      with (oRetorno.aResultEnsino[i]) {
                            
        sHtml += '<option value="'+oRetorno.aResultEnsino[i].ed10_i_codigo+'">';
        sHtml += oRetorno.aResultEnsino[i].ed10_c_descr.urlDecode()+'</option>';
                             
      }   
                  
    }
        
    $('ensino').innerHTML             = sHtml;
    document.form1.ensino[0].selected = true;

  }  
        
  $('ensino').disabled  = false;
           
}

</script>
<?
if ($iModulo != 7159) {
?>
  <script>
    js_escola(<?=$iEscola?>);
  </script>

<?

}
?>