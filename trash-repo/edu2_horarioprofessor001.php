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

include("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_regenciahorario_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clregenciahorario = new cl_regenciahorario;
$db_opcao = 1;
$db_botao = true;
$codigo_escola = db_getsession("DB_coddepto");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.cabec{
 text-align: left;
 font-size: 13;
 font-weight: bold;
 color: #DEB887;
 background-color:#444444;
 border:1px solid #CCCCCC;
}
.aluno{
 font-size: 11;
}
</style>
<SCRIPT LANGUAGE="JavaScript">
 team = new Array(
 <?
 # Seleciona todos as escolas
 $restricao = "exists(select * from rechumanoescola
                      where ed75_i_rechumano = ed58_i_rechumano
                      and ed75_i_escola = $codigo_escola) and ed58_ativo is true  ";
 $result = $clregenciahorario->sql_record($clregenciahorario->sql_query("","DISTINCT ed20_i_codigo,case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as z01_nome,case when ed20_i_tiposervidor = 1 then rechumanopessoal.ed284_i_rhpessoal else rechumanocgm.ed285_i_cgm end as identificacao","z01_nome"," $restricao"));
 $num = pg_num_rows($result);
 $conta = "";
 while ($row=pg_fetch_array($result)){
  $conta = $conta+1;
  $cod_rec = $row["ed20_i_codigo"];
  echo "new Array(\n";
  //$result1 = $clregenciahorario->sql_record($clregenciahorario->sql_query("","DISTINCT ed52_i_ano","ed52_i_ano desc"," ed58_i_rechumano = $cod_rec"));
  $result_ano = " select distinct ed52_i_ano from regenciahorario"; 
  $result_ano .= " inner join periodoescola  on  periodoescola.ed17_i_codigo = regenciahorario.ed58_i_periodo";
  $result_ano .= " inner join regencia  on  regencia.ed59_i_codigo = regenciahorario.ed58_i_regencia";
  $result_ano .= " inner join rechumano  on  rechumano.ed20_i_codigo = regenciahorario.ed58_i_rechumano";
  $result_ano .= " inner join diasemana  on  diasemana.ed32_i_codigo = regenciahorario.ed58_i_diasemana";
  $result_ano .= " inner join escola  on  escola.ed18_i_codigo = periodoescola.ed17_i_escola";
  $result_ano .= " inner join periodoaula  on  periodoaula.ed08_i_codigo = periodoescola.ed17_i_periodoaula";
  $result_ano .= " inner join turno  on  turno.ed15_i_codigo = periodoescola.ed17_i_turno";
  $result_ano .= " inner join disciplina  on  disciplina.ed12_i_codigo = regencia.ed59_i_disciplina";
  $result_ano .= " inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina";
  $result_ano .= " inner join turma  on  turma.ed57_i_codigo = regencia.ed59_i_turma";
  $result_ano .= " inner join calendario  on  calendario.ed52_i_codigo = turma.ed57_i_calendario"; 
  $result_ano .= " where ed58_i_rechumano=$cod_rec and ed58_ativo is true  "; 
  $result_ano .= " union ";
  $result_ano .= " select distinct ed52_i_ano from turmaachorario ";
  $result_ano .= " inner join turmaac  on  turmaac.ed268_i_codigo = turmaachorario.ed270_i_turmaac";
  $result_ano .= " inner join calendario  on  calendario.ed52_i_codigo = turmaac.ed268_i_calendario";
  $result_ano .= " where ed270_i_rechumano= $cod_rec order by ed52_i_ano desc";
  $result1 = pg_query($result_ano) ;
  $num_sub = pg_num_rows($result1);
  if ($num_sub>=1){
   echo "new Array(\"\", ''),\n";
   $conta_sub = "";
   while ($rowx=pg_fetch_array($result1)){
    $codigo_ano=$rowx["ed52_i_ano"];
    $conta_sub=$conta_sub+1;
    if ($conta_sub==$num_sub){
     echo "new Array(\"$codigo_ano\", $codigo_ano)\n";
     $conta_sub = "";
    }else{
     echo "new Array(\"$codigo_ano\", $codigo_ano),\n";
    }
   }
  }else{
   echo "new Array(\"Professor sem horários cadastrados\", '')\n";
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
function fillSelectFromArray2(selectCtrl, itemArray, goodPrompt, badPrompt, defaultItem){
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
   <?if(isset($anohorario)){?>
    if(<?=trim($anohorario)?>==itemArray[i][1]){
     indice = i;
    }
   <?}?>
   j++;
  }
  <?if(isset($anohorario)){?>
   selectCtrl.options[indice].selected = true;
  <?}else{?>
   selectCtrl.options[0].selected = true;
  <?}?>
  document.form1.subgrupo.disabled = false;
 }
}

//End -->
</script>
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
<?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
<a name="topo"></a>
<form name="form1" method="post" action="">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td valign="top" bgcolor="#CCCCCC" align="center">
   <br>
   <fieldset style="width:95%"><legend><b>Relatório Horários do Professor</b></legend>
    <table border="0" align="left">
     <tr>
      <td align="left">
       <?
       $restricao = "exists(select * from rechumanoescola
                            where ed75_i_rechumano = ed58_i_rechumano
                            and ed75_i_escola = $codigo_escola) and ed58_ativo is true  ";
       $result = $clregenciahorario->sql_record($clregenciahorario->sql_query("","DISTINCT ed20_i_codigo,case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as z01_nome,case when ed20_i_tiposervidor = 1 then rechumanopessoal.ed284_i_rhpessoal else rechumanocgm.ed285_i_cgm end as identificacao","z01_nome"," $restricao"));
       ?>
       <b>Selecione o Professor:</b><br>
       <select name="grupo" onChange="fillSelectFromArray(this.form.subgrupo, ((this.selectedIndex == -1) ? null : team[this.selectedIndex-1]));" style="font-size:9px;width:300px;height:18px;">
        <option></option>
        <?
        while($row=pg_fetch_array($result)){
         $cod_rechumano=$row["ed20_i_codigo"];
         $desc_rechumano=$row["z01_nome"];
         $identificacao=$row["identificacao"];
         ?>
         <option value="<?=$cod_rechumano;?>" <?=$cod_rechumano==@$rechumano?"selected":""?>><?=$identificacao." - ".$desc_rechumano?></option>
         <?
        }
        ?>
       </select>
      </td>
     </tr>
     <tr>
      <td>
       <b>Selecione o Ano:</b><br>
       <select name="subgrupo" style="font-size:9px;width:300px;height:18px;" disabled onchange="js_ano(this.value);">
        <option value=""></option>
       </select>
      </td>
      <?if(isset($anohorario)){?>
      <tr>
       <td>
       <script>fillSelectFromArray2(document.form1.subgrupo, ((document.form1.grupo.selectedIndex == -1) ? null : team[document.form1.grupo.selectedIndex-1]));</script>
       <b>Escola:</b><br>
       <select name="escolahorario" style="font-size:9px;width:300px;height:18px;">
       <?
       //$result2 = $clregenciahorario->sql_record($clregenciahorario->sql_query("","DISTINCT ed18_i_codigo,ed18_c_nome","ed18_c_nome","ed58_i_rechumano = $rechumano AND ed52_i_ano = $anohorario"));
       $resultano = " select distinct ed18_i_codigo,ed18_c_nome from regenciahorario"; 
 	   $resultano .= " inner join periodoescola  on  periodoescola.ed17_i_codigo = regenciahorario.ed58_i_periodo";
       $resultano .= " inner join regencia  on  regencia.ed59_i_codigo = regenciahorario.ed58_i_regencia";
       $resultano .= " inner join rechumano  on  rechumano.ed20_i_codigo = regenciahorario.ed58_i_rechumano";
       $resultano .= " inner join diasemana  on  diasemana.ed32_i_codigo = regenciahorario.ed58_i_diasemana";
       $resultano .= " inner join escola  on  escola.ed18_i_codigo = periodoescola.ed17_i_escola";
       $resultano .= " inner join turma  on  turma.ed57_i_codigo = regencia.ed59_i_turma";
       $resultano .= " inner join calendario  on  calendario.ed52_i_codigo = turma.ed57_i_calendario"; 
       $resultano .= " where ed58_i_rechumano=$rechumano and ed52_i_ano=$anohorario and ed58_ativo is true  "; 
       $resultano .= " union ";
       $resultano .= " select distinct ed18_i_codigo, ed18_c_nome from turmaachorario ";
       $resultano .= " inner join turmaac  on  turmaac.ed268_i_codigo = turmaachorario.ed270_i_turmaac";
       $resultano .= " inner join calendario  on  calendario.ed52_i_codigo = turmaac.ed268_i_calendario";
       $resultano .= " inner join escola  on  escola.ed18_i_codigo = turmaac.ed268_i_escola";
       $resultano .= " where ed270_i_rechumano= $rechumano and ed52_i_ano=$anohorario order by ed18_c_nome";
       $result2 = pg_query($resultano) ;
       $linhas2 = pg_num_rows($result2);
       if($linhas2>0){
        ?><option value="">TODAS</option><?
        for($x=0;$x<$linhas2;$x++){
         db_fieldsmemory($result2,$x);
         ?>
         <option value="<?=$ed18_i_codigo?>" <?=@$ed18_i_codigo==@$escolahorario?"selected":""?>><?=$ed18_i_codigo." - ".$ed18_c_nome?></option>
         <?
        }
       }else{
         ?>
         <option value="">Nenhuma escola</option>
         <?
       }
       ?>
       </select>
       </td>
      </tr>
      <?}?>
      <tr>
       <td>
        <input type="button" name="procurar" value="Processar" onclick="js_procurar(document.form1.grupo.value,document.form1.subgrupo.value)">
       </td>
      </tr>
     </table>
   </fieldset>
  </td>
 </tr>
</table>
</form>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
function js_ano(valor){
 if(valor!=""){
  location.href = "edu2_horarioprofessor001.php?rechumano="+document.form1.grupo.value+"&anohorario="+valor;
 }else{
  location.href = "edu2_horarioprofessor001.php?rechumano="+document.form1.grupo.value;
 }
}
function js_procurar(professor,ano){
 jan = window.open('edu2_horarioprofessor002.php?rechumano='+professor+'&anohorario='+ano+'&escolahorario='+document.form1.escolahorario.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 jan.moveTo(0,0);
}
<?if(!isset($anohorario) && pg_num_rows($result)>0){?>
 fillSelectFromArray2(document.form1.subgrupo,team[0]);
 document.form1.grupo.options[1].selected = true;
<?}?>
</script>