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

    require("libs/db_stdlib.php");
    require("libs/db_conecta.php");
    include("libs/db_sessoes.php");
    include("libs/db_usuariosonline.php");
    include("dbforms/db_funcoes.php");
    require_once("libs/db_utils.php");
    $clrotulo = new rotulocampo;
    $clrotulo->label('DBtxt23');
    $clrotulo->label('DBtxt25');
    $clrotulo->label('DBtxt27');
    $clrotulo->label('DBtxt28');

    db_postmemory($HTTP_POST_VARS);
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="300" marginheight="0" onLoad="a=1" align = "center">
    <table width="100%" height="18"  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2" align = "center" >
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table>
    <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC" align = "center">
      <tr>
        <td valign="top">
          <br>
          <form name="form1" method="post" align="center">
            <fieldset style="width:40%" align="center"><legend><b>Relatório de Medicamentos</b></legend>
              <fieldset style="width:30%" align = "center" ><legend><b>Classificação</b></legend>
                <table align="center" border="0" cellpadding="0" cellspacing="0">
                  <tr align="center">
                    <td>
                      <?
                          $oDaoFarClass = db_utils::getdao('far_class');
                          $sSql         = $oDaoFarClass->sql_query("", "fa05_c_descr, fa05_i_codigo", "fa05_c_descr", "");
                          $rsSql        = $oDaoFarClass->sql_record($sSql);
                        ?> 
                        <select name="medicaHisto" id="medicaHisto" size="10" onclick="js_desabinc()" multiple style="font-size:9px;width:350px;">
                         
                        <?
                          $iLinhas =  $oDaoFarClass->numrows;
                          
                            for ($iCont = 0; $iCont < $iLinhas; $iCont++) {
                
                              $oDados = db_utils::fieldsmemory($rsSql, $iCont);
                              echo "<option value = '$oDados->fa05_i_codigo'>$oDados->fa05_c_descr</option>";        
      
                            }

                        ?>
                       </select>
             </td>
             <td align="center">
               <br>
               <table border="0">
                 <tr>
                   <td>
                     <input name="incluirum" title="Incluir" type="button" value=">" onclick="js_incluir();" 
                      style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;
                      font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled> 
                   </td>
                 </tr>
                 <tr>
                   <td height="0">
                   </td>
                 </tr>
                 <tr>
                   <td>
                     <input name="incluirtodos" title="Incluir Todos" type="button" value=">>" onclick="js_incluirtodos();" 
                      style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;
                      font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" <?=$iLinhas==0?"disabled":""?>>
                   </td>
                 </tr>
                 <tr>
                   <td height="0">
                   </td>
                 </tr> 
                 <tr>
                   <td height="0">
                   </td>
                 </tr>
                 <tr>
                   <td>
                     <input name="excluirum" title="Excluir" type="button" value="<" onclick="js_excluir();" 
                      style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;
                      font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled>
                   </td>
                 </tr>
                 <tr>
                   <td height="0">
                   </td>
                 </tr>
                 <tr>
                   <td>
                     <input name="excluirtodos" title="Excluir Todos" type="button" value="<<" onclick="js_excluirtodos();" 
                      style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;
                      font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled>
                   </td>
                 </tr>
               </table> 
                        
              <td>
                <br>
                <select name="medicamentos" id="medicamentos" size="10" onclick="js_desabexc()"  multiple style="font-size:9px;width:350px;">;
                </select>
              </td>
             </tr>
            </table>
            </fieldset>
            <table  align="center"  width= "80%" height=¿80%¿>
             <tr align="left">
              <td align="left" >
                <b>Quebra :</b>
                <?
                  $x = array("n"=>"Nenhuma","q"=>"Classificação");
                  db_select("sQuebra",$x,true,2);
                ?>
              </td>
              <td>
              </td>
              <td align="left">
                <b>Ordem :</b>
              <?
              $y = array("n"=>"Numérica","a"=>"Alfabética","u"=>"Unidade");
              db_select("sOrdem",$y,true,2);
              ?>
            </td>
          </tr>
          <tr align="right" >
            <td>
            </td>
            <td>
              <br>
              <input  name="emite2" id="emite2" type="button" value="Gerar Relátorio" onclick="js_emite();" >
            </td>
          </tr>
        </fieldset>
      
    </form>
  </body>
</html>

<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>
<script>


function js_emite(){

  //Concatena as variaveis que o usuario selecionar para enviar como parametro para o arquivo far2_medicamento002.php  
  oDoc           = document.form1.medicamentos;
  sMedicamentos  = "";
  sVir           = "";

  for (iCont = 0; iCont < oDoc.length; iCont++) {

    sMedicamentos += sVir+oDoc.options[iCont].value;
    sVir = ",";
  }

  sParametros  = "sMedicamentos="+sMedicamentos;
  sParametros += "&sQuebra="+document.form1.sQuebra.value;
  sParametros += "&sOrdem="+document.form1.sOrdem.value;
  jan = window.open('far2_medicamento002.php?'+sParametros,'',
                  'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 '
                 );

  jan.moveTo(0,0);
                         
}


function js_incluir() {
    
    var Tam = document.form1.medicaHisto.length;
    var F   = document.form1;
     
     for (x = 0; x < Tam; x++) {
          
          if (F.medicaHisto.options[x].selected == true) {
                 
                 F.elements['medicamentos'].options[F.elements['medicamentos'].options.length] = new Option(F.medicaHisto.options[x].text,
                                                                                                       F.medicaHisto.options[x].value)
                 F.medicaHisto.options[x] = null;
                 Tam--;
                 x--;
                      
          }
     }

      
    if (document.form1.medicaHisto.length > 0) {
      document.form1.medicaHisto.options[0].selected = true;
    } else {
      
      document.form1.incluirum.disabled    = true;
      document.form1.incluirtodos.disabled = true;
              
    }
  
      document.form1.excluirtodos.disabled = false;
      document.form1.medicamentos.focus();
        
  }

/*

  function js_desabexc() {
    
    for (i = 0; i < document.form1.medicamentos.length; i++) {
            
      if (document.form1.medicamentos.length > 0 && document.form1.medicamentos.options[i].selected) {
                          
        if (document.form1.medicaHisto.length > 0) {
          document.form1.medicaHisto.options[0].selected = false;
        }
                                                      
          document.form1.incluirum.disabled = true;
          document.form1.excluirum.disabled = false;
                                                        
        }
      }
}
*/

function js_incluirtodos() {

   var Tam = document.form1.medicaHisto.length;
   var F   = document.form1;
   for (i = 0; i < Tam; i++) {  
        
        F.elements['medicamentos'].options[F.elements['medicamentos'].options.length] = new Option(F.medicaHisto.options[0].text,
                                                                                             F.medicaHisto.options[0].value)

   F.medicaHisto.options[0] = null;
                                                                                                 
   }
     
     document.form1.incluirum.disabled    = true;
     document.form1.incluirtodos.disabled = true;
     document.form1.excluirtodos.disabled = false;
     document.form1.medicamentos.focus();

}


function js_excluir() {
    
  var F = document.getElementById("medicamentos");
  Tam   = F.length;
  
  for (x = 0; x < Tam; x++) {
            
    if (F.options[x].selected == true) {
                      
      document.form1.medicaHisto.options[document.form1.medicaHisto.length] = new Option(F.options[x].text, 
                                                                                                           F.options[x].value)
      F.options[x] = null;
      Tam--;
      x--;
                            
    }
  }
        
    if (document.form1.medicamentos.length > 0) {
          document.form1.medicamentos.options[0].selected = true;
    }
    
    if (F.length == 0) {
              
              document.form1.excluirum.disabled    = true;
              document.form1.excluirtodos.disabled = true;
              document.form1.incluirtodos.disabled = false;
                  
    }
        document.form1.medicaHisto.focus(); 
}

function js_excluirtodos() {
    
    var Tam = document.form1.medicamentos.length;
    var F   = document.getElementById("medicamentos");
    for (i = 0; i < Tam; i++) {
    
      document.form1.medicaHisto.options[document.form1.medicaHisto.length] = new Option(F.options[0].text,
                                                                                             F.options[0].value);
      F.options[0] = null;
              
    }
      
    if (F.length == 0) {
            
            document.form1.excluirum.disabled    = true;
            document.form1.excluirtodos.disabled = true;
            document.form1.incluirtodos.disabled = false;
                
      }  
      document.form1.medicaHisto.focus();
}


function js_desabinc() {
  
  for (i = 0; i < document.form1.medicaHisto.length; i++) {
          
    if (document.form1.medicaHisto.length>0 && document.form1.medicaHisto.options[i].selected) {
                    
      if (document.form1.medicamentos.length > 0) {          
        document.form1.medicamentos.options[0].selected = false;
      }
                          
        document.form1.incluirum.disabled = false;
        document.form1.excluirum.disabled = true;
                                
      }
    }
}

function js_desabexc() {
      
for (i = 0; i < document.form1.medicamentos.length; i++) {
                    
 if (document.form1.medicamentos.length > 0 && document.form1.medicamentos.options[i].selected) {
                                                
 if (document.form1.medicaHisto.length > 0) {
 document.form1.medicaHisto.options[0].selected = false;
}
                                                                                                      
document.form1.incluirum.disabled = true;
document.form1.excluirum.disabled = false;
                                                                                                                                                              
 }
}
}


function js_OrdenarLista(combo) {
    
    var lb = document.getElementById(combo);
    arrTexts = new Array();
    arrValues = new Array();
      
      for (i = 0; i < lb.length; i++) {
            
            arrValues[i] = lb.options[i].value;
            arrTexts[i]  = lb.options[i].text;
                
      }
        
        arrTexts.sort();
        for (i = 0; i < lb.length; i++) {
              
              lb.options[i].text  = arrTexts[i];
              lb.options[i].value = arrValues[i];
                  
        }
}

</script>