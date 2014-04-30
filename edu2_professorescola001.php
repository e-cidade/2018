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

include("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_feriado_classe.php");
include("classes/db_evento_classe.php");
include("classes/db_rechumanoescola_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clrechumanoescola = new cl_rechumanoescola;
$db_opcao = 1;
$db_botao = true;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<SCRIPT LANGUAGE="JavaScript">
 team = new Array(
 <?
 # Seleciona todos as escolas
 $result = $clrechumanoescola->sql_record($clrechumanoescola->sql_query("","DISTINCT ed18_i_codigo,ed18_c_nome","ed18_c_nome",""));
 $num = pg_num_rows($result);
 $conta = "";
 while ($row=pg_fetch_array($result)){
  $conta = $conta+1;
  $cod_curso = $row["ed18_i_codigo"];
  echo "new Array(\n";
  $sql1 = "SELECT DISTINCT ed25_i_codigo,ed25_c_descr
           FROM rechumanoescola
            inner join relacaotrabalho  on  relacaotrabalho.ed23_i_rechumanoescola = rechumanoescola.ed75_i_codigo
            inner join areatrabalho  on  areatrabalho.ed25_i_codigo = relacaotrabalho.ed23_i_areatrabalho
           WHERE ed75_i_escola = $cod_curso
           ORDER BY ed25_c_descr
          ";
  $result1 = pg_query($sql1);
  $num_sub = pg_num_rows($result1);
  if ($num_sub>=1){
   # Se achar alguma base para o curso, marca a palavra Todas
   echo "new Array(\"\", ''),\n";
   echo "new Array(\"TODAS\", '0'),\n";
   $conta_sub = "";
   while ($rowx=pg_fetch_array($result1)){
    $codigo_base=$rowx["ed25_i_codigo"];
    $base_nome=$rowx["ed25_c_descr"];
    $conta_sub=$conta_sub+1;
    if ($conta_sub==$num_sub){
     echo "new Array(\"$base_nome\", $codigo_base)\n";
     $conta_sub = "";
    }else{
     echo "new Array(\"$base_nome\", $codigo_base),\n";
    }
   }
  }else{
   #Se nao achar base para o curso selecionado...
   echo "new Array(\"Nenhuma área de trabalho definida.\", '')\n";
  }
  if ($num>$conta){
   echo "),\n";
  }
 }
 echo ")\n";
 echo ");\n";
?>
//Inicio da função JS
function fillSelectFromArray(selectCtrl, itemArray, goodPrompt, badPrompt, defaultItem){
 var i, j;
 var prompt;
 // empty existing items
 for (i = selectCtrl.options.length; i >= 0; i--) {
  selectCtrl.options[i] = null;
 }
 prompt = (itemArray != null) ? goodPrompt : badPrompt;
 if (prompt == null) {
  document.form1.subgrupo.disabled = true;
  j = 0;
 }else{
  selectCtrl.options[0] = new Option(prompt);
  j = 1;
 }
 if (itemArray != null) {
  // add new items
  for (i = 0; i < itemArray.length; i++){
   selectCtrl.options[j] = new Option(itemArray[i][0]);
   if (itemArray[i][1] != null){
    selectCtrl.options[j].value = itemArray[i][1];
   }
   j++;
  }
  selectCtrl.options[0].selected = true;
  document.form1.subgrupo.disabled = false;
 }
 document.form1.procurar.disabled = true;
}

//End -->
</script>
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
  <td align="center" valign="top" bgcolor="#CCCCCC">
   <?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
   <br>
   <form name="form1" method="post" action="">
   <fieldset style="width:95%"><legend><b>Relatório Professores por Escola</b></legend>
    <table border="0" align="left">
     <tr>
      <td align="left">
       <b>Selecione a Escola:</b><br>
       <select name="grupo" onChange="fillSelectFromArray(this.form.subgrupo, ((this.selectedIndex == -1) ? null : team[this.selectedIndex-1]));" style="font-size:9px;width:300px;height:18px;">
        <option></option>
        <?
        #Seleciona todos as escolas para setar os valores no combo
        $result = $clrechumanoescola->sql_record($clrechumanoescola->sql_query("","DISTINCT ed18_i_codigo,ed18_c_nome","ed18_c_nome",""));
        while($row=pg_fetch_array($result)){
         $cod_escola=$row["ed18_i_codigo"];
         $desc_escola=$row["ed18_c_nome"];
         ?>
         <option value="<?=$cod_escola;?>" <?=$cod_escola==@$escola?"selected":""?>><?=$desc_escola;?></option>
         <?
        }
        #Popula o segundo combo de acordo com a escolha no primeiro
        ?>
       </select>
      </td>
     </tr>
     <tr>
      <td>
       <b>Selecione a Área de Trabalho:</b><br>
       <select name="subgrupo" style="font-size:9px;width:300px;height:18px;" disabled onchange="js_botao(this.value);">
        <option value=""></option>
       </select>
      </td>
     </tr>
     <tr>
      <td valign='bottom'>
       <input type="checkbox" name="disciplina" value="" checked> <b>Mostrar disciplinas que o professor leciona.</b>
      </td>
     </tr>
     <tr>
      <td valign='bottom'>
       <input type="button" name="procurar" value="Processar" onclick="js_procurar(document.form1.grupo.value,document.form1.subgrupo.value)" disabled>
      </td>
     </tr>
    </table>
   </fieldset>
   </form>
  </td>
 </tr>
</table>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
function js_procurar(escola,area){
 if(escola!="" && area!=""){
  if(document.form1.disciplina.checked==true){
   disciplina = "S";
  }else{
   disciplina = "N";
  }
  jan = window.open('edu2_professorescola002.php?escola='+escola+'&area='+area+'&disciplina='+disciplina,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
 }
}
function js_botao(valor){
 if(valor!=""){
  document.form1.procurar.disabled = false;
 }else{
  document.form1.procurar.disabled = true;
 }
}
<?if(pg_num_rows($result)>0){?>
 fillSelectFromArray(document.form1.subgrupo,team[0]);
 document.form1.grupo.options[1].selected = true;
<?}?>
</script>