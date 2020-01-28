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
include("classes/db_turmaachorario_classe.php");
include("dbforms/db_funcoes.php");
$escola = db_getsession("DB_coddepto");
$clturmaachorario = new cl_turmaachorario;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<SCRIPT LANGUAGE="JavaScript">
 team = new Array(
 <?
 # Seleciona todos os calendários
 $sql = " select distinct ed52_i_codigo,ed52_c_descr,ed52_i_ano from turmaachorario ";
 $sql .= " inner join turmaac  on  turmaac.ed268_i_codigo = turmaachorario.ed270_i_turmaac";
 $sql .= " inner join calendario  on  calendario.ed52_i_codigo = turmaac.ed268_i_calendario";
 $sql .= " where ed268_i_escola= $escola order by ed52_i_ano desc";
 $sql_result = pg_query($sql) ;
 $num= pg_num_rows($sql_result); 
 $conta = "";
 while ($row=pg_fetch_array($sql_result)){
  $conta = $conta+1;
  $cod_curso = $row["ed52_i_codigo"];
  echo "new Array(\n";  
  $sub_sql = " select distinct ed268_i_codigo,ed268_c_descr from turmaac";
  $sub_sql .= " inner join turmaachorario  on  turmaachorario.ed270_i_turmaac = turmaac.ed268_i_codigo";
  $sub_sql .= " inner join calendario  on  calendario.ed52_i_codigo = turmaac.ed268_i_calendario";
  $sub_sql .= " WHERE ed268_i_calendario = '$cod_curso' AND ed268_i_escola = $escola  ORDER BY ed268_c_descr";
  $sub_result = pg_query($sub_sql);
  $num_sub = pg_num_rows($sub_result);
  if ($num_sub>=1){
   # Se achar alguma base para o curso, marca a palavra Todas
   echo "new Array(\"\", ''),\n";
   $conta_sub = "";
   while ($rowx=pg_fetch_array($sub_result)){
    $codigo_base=$rowx["ed268_i_codigo"];
    $turma_nome=$rowx["ed268_c_descr"];    
    $conta_sub=$conta_sub+1;
    if ($conta_sub==$num_sub){
     echo "new Array(\"$turma_nome \", $codigo_base)\n";
     $conta_sub = "";
    }else{
     echo "new Array(\"$turma_nome \", $codigo_base),\n";
    }
   }
  }else{
   #Se nao achar base para o curso selecionado...
   echo "new Array(\"Sem turmas.\", '')\n";
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
 <?if(isset($turma)){?>
  qtd = document.form1.professor.length;
  for (i = 0; i < qtd; i++) {
   document.form1.professor.options[0] = null;
  }
  document.form1.professor.disabled = true;
  dados.location.href = "edu3_diarioclasse002.php?turma=0";
 <?}?>
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
   <?if(isset($turma)){?>
    if(<?=trim($turma)?>==itemArray[i][1]){
     indice = i;
    }
   <?}?>
   j++;
  }
  <?if(isset($turma)){?>
   selectCtrl.options[indice].selected = true;
  <?}else{?>
   selectCtrl.options[0].selected = true;
  <?}?>
  document.form1.subgrupo.disabled = false;
 }
}
//End -->
</script>
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
<fieldset style="width:95%"><legend><b>Relatório de Horário das Turmas</b></legend>
<table border="0" align="left">
 <tr>
  <td>
   <table border="0" align="left">
    <tr>
     <td>
      <b>Calendário:</b><br>
      <select name="grupo" onChange="fillSelectFromArray(this.form.subgrupo, ((this.selectedIndex == -1) ? null : team[this.selectedIndex-1]));" style="font-size:9px;width:150px;height:18px;">
       <option></option>
       <?
       #Seleciona todos os grupos para setar os valores no combo       
       $sql = " select distinct ed52_i_codigo,ed52_i_ano,ed52_c_descr from turmaachorario ";
       $sql .= " inner join turmaac  on  turmaac.ed268_i_codigo = turmaachorario.ed270_i_turmaac";
       $sql .= " inner join calendario  on  calendario.ed52_i_codigo = turmaac.ed268_i_calendario";
       $sql .= " where ed268_i_escola= $escola order by ed52_i_ano desc";
       $sql_result = pg_query($sql);
       while($row=pg_fetch_array($sql_result)){
        $cod_curso=$row["ed52_i_codigo"];
        $desc_curso=$row["ed52_c_descr"];
        ?>
        <option value="<?=$cod_curso;?>" <?=$cod_curso==@$calendario?"selected":""?>><?=$desc_curso;?></option>
        <?
       }
       #Popula o segundo combo de acordo com a escolha no primeiro
       ?>
      </select>
     </td>
     <td>
      <b>Turma:</b><br>
      <select name="subgrupo" style="font-size:9px;width:150px;height:18px;" disabled onchange="js_botao(this.value,document.form1.grupo.value,document.form1.subgrupo.value);">
       <option value=""></option>
      </select>
     </td>
     <?if(isset($turma)){?>
      <script>fillSelectFromArray2(document.form1.subgrupo, ((document.form1.grupo.selectedIndex == -1) ? null : team[document.form1.grupo.selectedIndex-1]));</script>
      <td>
       <?
       $sql  = " select distinct ed20_i_codigo,case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as z01_nome,case when ed20_i_tiposervidor = 1 then rechumanopessoal.ed284_i_rhpessoal else rechumanocgm.ed285_i_cgm end as identificacao";
       $sql .= " from turmaachorario ";
       $sql .= "  inner join turmaac  on  turmaac.ed268_i_codigo = turmaachorario.ed270_i_turmaac";
       $sql .= "  inner join periodoescola  on  periodoescola.ed17_i_codigo = turmaachorario.ed270_i_periodo"; 
       $sql .= "  inner join rechumano  on  rechumano.ed20_i_codigo = turmaachorario.ed270_i_rechumano";
       $sql .= "  inner join diasemana  on  diasemana.ed32_i_codigo = turmaachorario.ed270_i_diasemana";
       $sql .= "  inner join calendario  on  calendario.ed52_i_codigo = turmaac.ed268_i_calendario";
       $sql .= "  inner join escola  as a on   a.ed18_i_codigo = periodoescola.ed17_i_escola";
       $sql .= "  inner join periodoaula  on  periodoaula.ed08_i_codigo = periodoescola.ed17_i_periodoaula";
       $sql .= "  inner join turno  as b on   b.ed15_i_codigo = periodoescola.ed17_i_turno";
       $sql .= "  left join rechumanopessoal  on  rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo";
       $sql .= "  left join rhpessoal  on  rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal";
       $sql .= "  left join cgm as cgmrh on  cgmrh.z01_numcgm = rhpessoal.rh01_numcgm";
       $sql .= "  left join rechumanocgm  on  rechumanocgm.ed285_i_rechumano = rechumano.ed20_i_codigo";
       $sql .= "  left join cgm as cgmcgm on  cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm";
       $sql .= " where ed270_i_turmaac=$turma order by z01_nome";
       $result = pg_query($sql);
       $linhas = pg_num_rows($result);
       ?>
        <b>Selecione o Professor:</b> (Opcional)<br> 
        <select name="professor" style="font-size:9px;width:250px;height:18px;"> 
       <?if($linhas>0){?>
       <option value=''></option> 
        <?
        for($i=0;$i<$linhas;$i++){
         db_fieldsmemory($result,$i);
         echo "<option value='$ed20_i_codigo'>$identificacao - $z01_nome</option>\n";
        }
       }else{
        ?>
        <option value=''>NENHUM PROFESSOR CADASTRADO PARA ESTA TURMA.</option>
        <?
       }
       ?>
        </select>
       <input type="button" value="Processar" name="processar" onclick="js_pesquisa(document.form1.subgrupo.value,document.form1.professor.value);" <?=$linhas==0?"disabled":""?>>
      </td>
      <?}?>
    </tr>
   </table>
  </td>
 </tr>
</table>
</fieldset>
<iframe name="dados" id="dados" src="" width="750" height="350" frameborder="0"></iframe>
</center>
</form>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
function js_botao(valor,calendario,turma){
 if(valor!=""){
  location.href = "edu2_horarioturmaac001.php?calendario="+calendario+"&turma="+turma;
 }
 <?if(isset($turma)){?>
  qtd = document.form1.professor.length;
  for (i = 0; i < qtd; i++) {
  document.form1.professor.options[0] = null;
  }
 <?}?>
}
function js_pesquisa(turma,professor,z01_nome){		
 jan = window.open('edu2_horarioturmaac002.php?professor='+professor+'&turma='+turma+'&z01_nome='+z01_nome,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 jan.moveTo(0,0);
}
<?if(!isset($turma) && pg_num_rows($sql_result)>0){?>
 fillSelectFromArray2(document.form1.subgrupo,team[0]);
 document.form1.grupo.options[1].selected = true;
<?}?>
</script>