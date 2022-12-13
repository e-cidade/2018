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
include("classes/db_matricula_classe.php");
include("classes/db_calendario_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clmatricula = new cl_matricula;
$clcalendario = new cl_calendario;
$db_opcao = 1;
$db_botao = true;
$nomeescola = db_getsession("DB_nomedepto");
$escola = db_getsession("DB_coddepto");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/DBFormCache.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/DBFormSelectCache.js"></script>

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
 # Seleciona todos os calendários
 $sql = "SELECT ed52_i_codigo,ed52_c_descr
         FROM calendario
          inner join calendarioescola on ed38_i_calendario = ed52_i_codigo
         WHERE ed38_i_escola = $escola
         AND ed52_c_passivo = 'N'
         ORDER BY ed52_i_ano DESC";
 $sql_result = pg_query($sql);
 $num = pg_num_rows($sql_result);
 $conta = "";
 while ($row=pg_fetch_array($sql_result)){
  $conta = $conta+1;
  $cod_curso = $row["ed52_i_codigo"];
  echo "new Array(\n";
  $sub_sql = "SELECT DISTINCT ed10_i_codigo,ed10_c_descr
              FROM turma
               inner join matricula on ed60_i_turma = ed57_i_codigo
               inner join matriculaserie on ed221_i_matricula = ed60_i_codigo
               inner join serie on ed11_i_codigo = ed221_i_serie
               inner join ensino on ed10_i_codigo = ed11_i_ensino
              WHERE ed57_i_calendario = '$cod_curso'
              AND ed57_i_escola = $escola
              ORDER BY ed10_c_descr
             ";
  $sub_result = pg_query($sub_sql);
  $num_sub = pg_num_rows($sub_result);
  if ($num_sub>=1){
   # Se achar alguma base para o curso, marca a palavra Todas
   echo "new Array(\"\", ''),\n";
   echo "new Array(\"TODOS\", 0),\n";
   $conta_sub = "";
   while ($rowx=pg_fetch_array($sub_result)){
    $codigo_base=$rowx["ed10_i_codigo"];
    $base_nome=$rowx["ed10_c_descr"];
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
   echo "new Array(\"Calendário sem matrículas\", '')\n";
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
   <?if(isset($ensino)){?>
    if(<?=trim($ensino)?>==itemArray[i][1]){
     indice = i;
    }
   <?}?>
   j++;
  }
  <?if(isset($ensino)){?>
   selectCtrl.options[indice].selected = true;
   document.form1.procurar.disabled = false;
  <?}else{?>
   selectCtrl.options[0].selected = true;
  <?}?>
  document.form1.subgrupo.disabled = false;
 }
}
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
  <td align="center" valign="top" bgcolor="#CCCCCC">
   <br>
   <fieldset style="width:95%"><legend><b><?=$nomeescola?> - Consulta Turmas Encerradas</b></legend>
    <table border="0">
     <tr>
      <td align="left">
       <b>Selecione o Calendário:</b><br>
       <select name="grupo" onChange="fillSelectFromArray(this.form.subgrupo, ((this.selectedIndex == -1) ? null : team[this.selectedIndex-1]));" style="font-size:9px;width:200px;height:18px;">
        <option></option>
        <?
        #Seleciona todos os grupos para setar os valores no combo
        $sql = "SELECT ed52_i_codigo,ed52_c_descr
                FROM calendario
                 inner join calendarioescola on ed38_i_calendario = ed52_i_codigo
                WHERE ed38_i_escola = $escola
                AND ed52_c_passivo = 'N'
                ORDER BY ed52_i_ano DESC";
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
       <b>Selecione o Ensino:</b><br>
       <select name="subgrupo" style="font-size:9px;width:200px;height:18px;" disabled onchange="js_botao(this.value);">
        <option value=""></option>
       </select>
      </td>
      <td>
        <b>Exibir Trocas de Turma:</b><br/>
        <select id='trocaTurma' style="font-size:9px;width:200px;height:18px;">
          <option value="1" selected="selected">Não</option>
          <option value="2">Sim</option>
        </select>
      </td>
      <td valign='bottom'>
       <input type="button" name="procurar" value="Procurar" onclick="js_procurar(document.form1.grupo.value,document.form1.subgrupo.value)" disabled>
      </td>
     </tr>
    </table>
   </fieldset>
  </td>
 </tr>
 <?if(isset($calendario)){?>
 <script>fillSelectFromArray2(document.form1.subgrupo, ((document.form1.grupo.selectedIndex == -1) ? null : team[document.form1.grupo.selectedIndex-1]));</script>
 <tr>
  <td align="center">
   <table border="0" width="97%" cellspacing="2px" cellpadding="1px" bgcolor="#cccccc">
    <tr>
     <td align="center" valign="top">
      <table border='1px' width="100%" bgcolor="#cccccc" style="" cellspacing="0px">
       <?
       if($ensino!=0){
        $where = " AND ed11_i_ensino = $ensino";
       }else{
        $where = "";
       }
       $sql = "SELECT DISTINCT ed10_c_descr,
                      ed10_i_codigo,
                      ed11_i_codigo,
                      ed11_c_descr,
                      ed11_i_sequencia,
                      ed57_c_descr,
                      ed57_i_codigo,
                      ed15_c_nome,
                      (select count(*)
                       from matricula
                        inner join matriculaserie on ed221_i_matricula = ed60_i_codigo
                       where ed60_c_concluida = 'N'
                       and ed60_c_situacao = 'MATRICULADO'
                       and ed60_i_turma = ed57_i_codigo
                       and ed221_i_serie = ed11_i_codigo
                       and ed221_c_origem = 'S'
                      ) as naoconcluidos,
                      (select count(*)
                       from matricula
                        inner join matriculaserie on ed221_i_matricula = ed60_i_codigo
                       where ed60_c_concluida = 'S'
                       and (ed60_c_situacao = 'MATRICULADO' OR ed60_c_situacao = 'AVANÇADO' OR ed60_c_situacao = 'CLASSIFICADO')
                       and ed60_i_turma = ed57_i_codigo
                       and ed221_i_serie = ed11_i_codigo
                       and ed221_c_origem = 'S'
                      ) as concluidos
               FROM turma
                inner join turmaserieregimemat on ed220_i_turma = ed57_i_codigo
                inner join serieregimemat on ed223_i_codigo = ed220_i_serieregimemat
                inner join serie on ed11_i_codigo = ed223_i_serie
                inner join ensino on ed10_i_codigo = ed11_i_ensino
                inner join turno on ed15_i_codigo = ed57_i_turno
               WHERE ed57_i_calendario = $calendario
               AND (select count(*)
                    from matricula
                     inner join matriculaserie on ed221_i_matricula = ed60_i_codigo
                    where ed60_c_situacao = 'MATRICULADO'
                    and ed60_i_turma = ed57_i_codigo
                    and ed221_i_serie = ed11_i_codigo
                    and ed221_c_origem = 'S'
                    )>0
               $where
               ORDER BY ed10_i_codigo,ed11_i_sequencia,ed57_c_descr
              ";
       $result = pg_query($sql);
       //db_criatabela($result);
       //exit;
       $linhas = pg_num_rows($result);
       $ensino = "";
       $serie = "";
       $turma = "";
       if($linhas>0){
        $cor1 = "#dbdbdb";
        $cor2 = "#f3f3f3";
        $ensino = "";
        $serie = "";
        for($c=0;$c<$linhas;$c++){
         db_fieldsmemory($result,$c);
         if($ensino!=$ed10_c_descr){
          ?>
          <tr>
           <td class="cabec" colspan="3">
            <?=$ed10_c_descr?>
           </td>
          </tr>
          <?
          $ensino = $ed10_c_descr;
         }
         if($serie!=$ed11_c_descr){
          ?>
          <tr bgcolor="<?=$cor1?>">
           <td><b>&nbsp;&nbsp;Etapa: <?=$ed11_c_descr?></b></td>
           <td align="center">Concluídos</td>
           <td align="center">Não Concluídos</td>
          </tr>
          <?
          $serie = $ed11_c_descr;
         }
         if($naoconcluidos==0){
          $color = "green";
         }else{
          $color = "red";
         }
         if($naoconcluidos==0){

         }
         ?>
         <tr bgcolor="#f3f3f3" style="color:<?=$color?>;font-weight:bold;">
          <td width="50%">
           <table width="100%">
            <tr style="color:<?=$color?>;font-weight:bold;">
             <td width="40%">&nbsp;&nbsp;&nbsp;Turma: <a href="javascript:js_matriculas(<?=$ed57_i_codigo?>,'<?=$ed57_c_descr?>',<?=$calendario?>,<?=$ed11_i_codigo?>)" style="color:<?=$color?>;font-weight:bold;" title="Veja os alunos matriculados nesta turma"><?=$ed57_c_descr?></a></td>
             <td width="30%">Turno <?=$ed15_c_nome?></td>
             <td width="30%"><?=$naoconcluidos==0?"ENCERRADA":"NÂO ENCERRADA"?></td>
            </tr>
           </table>
          </td>
          <td align="center"><?=$concluidos?></td>
          <td align="center"><?=$naoconcluidos?></td>
         </tr>
         <?
        }
        ?>
        </table>
        <?
       }else{
        ?>
        <table border='1px' width="100%" bgcolor="#cccccc" style="" cellspacing="0px">
         <tr bgcolor="#EAEAEA">
          <td class='aluno'>NENHUMA MATRÍCULA NESTE CALENDÁRIO.</td>
         </tr>
        </table>
        <?
       }
       ?>
     </td>
    </tr>
   </table>
  </td>
 </tr>
 <?}?>
</table>
</form>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>

var oDBFormCache = new DBFormCache('oDBFormCache', 'edu3_turmaencerrada001.php');
oDBFormCache.setElements(new Array($('trocaTurma')));
oDBFormCache.load();
function js_procurar(calendario,ensino) {
 if(calendario!="" && ensino!="") {
  
  oDBFormCache.save();
  location.href = "edu3_turmaencerrada001.php?calendario="+calendario+"&ensino="+ensino;
 }
}
function js_botao(valor){
 if(valor!=""){
  document.form1.procurar.disabled = false;
 }else{
  document.form1.procurar.disabled = true;
 }
}
function js_matriculas(turma,descrturma,calendario,serieregencia) {

 var sUrl  = 'edu3_turmaencerrada002.php?turma='+turma+'&serieregencia='+serieregencia;
     sUrl += '&trocaturma='+$F('trocaTurma'); 
 js_OpenJanelaIframe('', 'db_iframe_matriculas', sUrl, 'Alunos Matriculados na Turma '+descrturma, true);
 location.href = "#topo";
}
<?if(!isset($ensino) && pg_num_rows($sql_result)>0){?>
 fillSelectFromArray2(document.form1.subgrupo,team[0]);
 document.form1.grupo.options[1].selected = true;
<?}?>
</script>