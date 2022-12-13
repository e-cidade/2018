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

require("libs/db_stdlib.php");
require("libs/db_stdlibwebseller.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_turma_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$escola = db_getsession("DB_coddepto");
$clturma = new cl_turma;
$db_opcao = 1;
$db_botao = true;
if(isset($salvar)){
 db_inicio_transacao();
 $result = pg_query("UPDATE turmaserieregimemat SET ed220_c_aprovauto = 'N'
                     WHERE ed220_i_codigo in (select ed220_i_codigo
                                              from turmaserieregimemat
                                               inner join turma on ed57_i_codigo = ed220_i_turma
                                               inner join serieregimemat on ed223_i_codigo = ed220_i_serieregimemat
                                              where ed223_i_serie = $serie
                                              and ed57_i_calendario = $calendario)");
 if(isset($turmasaprov)){
  $tam = count($turmasaprov);
  for($y=0;$y<$tam;$y++){
   $result = pg_query("UPDATE turmaserieregimemat SET 
                        ed220_c_aprovauto = 'S'
                       WHERE ed220_i_codigo = $turmasaprov[$y]");
  }
 }
 db_fim_transacao();
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<script>
 team = new Array(
 <?
 # Seleciona todos os calendários
 $sql = "SELECT ed52_i_codigo,ed52_c_descr
         FROM calendario
          inner join calendarioescola on ed38_i_calendario = ed52_i_codigo
         WHERE ed38_i_escola = $escola
         AND ed52_c_passivo = 'N'
         ORDER BY ed52_i_ano DESC
         ";
 $sql_result = pg_query($sql);
 $num = pg_num_rows($sql_result);
 $conta = "";
 while ($row=pg_fetch_array($sql_result)){
  $conta = $conta+1;
  $cod_curso = $row["ed52_i_codigo"];
  echo "new Array(\n";
  $sub_sql = "SELECT DISTINCT ed11_i_codigo,ed11_c_descr,ed11_i_ensino,ed11_i_sequencia
              FROM serie
               inner join serieregimemat on ed223_i_serie = ed11_i_codigo
               inner join turmaserieregimemat on ed220_i_serieregimemat = ed223_i_codigo
               inner join turma on ed57_i_codigo = ed220_i_turma
              WHERE ed57_i_escola = $escola
              AND ed57_i_calendario = '$cod_curso'
              ORDER BY ed11_i_ensino,ed11_i_sequencia
             ";
  $sub_result = pg_query($sub_sql);
  $num_sub = pg_num_rows($sub_result);
  if ($num_sub>=1){
   echo "new Array(\"\", ''),\n";
   $conta_sub = "";
   while ($rowx=pg_fetch_array($sub_result)){
    $codigo_base=$rowx["ed11_i_codigo"];
    $base_nome=$rowx["ed11_c_descr"];
    $conta_sub=$conta_sub+1;
    if ($conta_sub==$num_sub){
     echo "new Array(\"$base_nome\", $codigo_base)\n";
     $conta_sub = "";
    }else{
     echo "new Array(\"$base_nome\", $codigo_base),\n";
    }
   }
  }else{
   echo "new Array(\"Calendário sem turmas cadastradas\", '')\n";
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
 }
</script>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" height="18"  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td>&nbsp;</td>
 </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <?=MsgAviso(db_getsession("DB_coddepto"),"escola")?>	
   <br>
   <center>
    <fieldset style="width:95%"><legend><b>Definir Aprovação Automática para as turmas</b></legend>
    <form name="form1" method="post" action="">
    <center>
    <table border="0">
     <tr>
      <td>
       <table border="0" align="left">
        </tr>
         <td>
          <b>Selecione o Calendário:</b><br>
          <select name="grupo" onChange="js_zerar();fillSelectFromArray(this.form.subgrupo, ((this.selectedIndex == -1) ? null : team[this.selectedIndex-1]));" style="font-size:9px;width:250px;height:18px;">
           <option></option>
           <?
           $sql = "SELECT ed52_i_codigo,ed52_c_descr
                   FROM calendario
                    inner join calendarioescola on ed38_i_calendario = ed52_i_codigo
                   WHERE ed38_i_escola = $escola
                   AND ed52_c_passivo = 'N'
                   ORDER BY ed52_i_ano DESC
                  ";
           $sql_result = pg_query($sql);
           while($row=pg_fetch_array($sql_result)){
            $cod_curso=$row["ed52_i_codigo"];
            $desc_curso=$row["ed52_c_descr"];
            ?>
            <option value="<?=$cod_curso;?>" <?=$cod_curso==@$calendario?"selected":""?>><?=$desc_curso;?></option>
            <?
           }
           ?>
          </select>
         </td>
         <td>
          <b>Selecione a Etapa:</b><br>
          <select name="subgrupo" style="font-size:9px;width:200px;height:18px;" disabled onchange="js_procurar(document.form1.grupo.value,document.form1.subgrupo.value)">
           <option value=""></option>
          </select>
         </td>
        </tr>
       </table>
      </td>
     </tr>
     <?if(isset($serie)){?>
     <tr>
      <td colspan="2" align="center">
       <?
       $result = $clturma->sql_record($clturma->sql_query_turmaserie("","DISTINCT ed220_i_codigo,ed57_c_descr,ed220_c_aprovauto","ed57_c_descr"," ed223_i_serie = $serie AND ed57_i_escola = $escola AND ed57_i_calendario = $calendario"));
       ?>
       <b>Turmas com aprovação automática na etapa <span id="nomeserie"></span>:</b><br>
       <select name="turmasaprov[]" id="turmasaprov" style="font-size:9px;width:400px;height:350px" multiple>
        <?
        for($x=0;$x<$clturma->numrows;$x++){
         db_fieldsmemory($result,$x);
         if($ed220_c_aprovauto=="S"){
          $selected = "selected";
         }else{
          $selected = "";
         }
         ?>
         <option value="<?=$ed220_i_codigo?>" <?=$selected?>><?=$ed57_c_descr?></option>
         <?
        }
        ?>
       </select>
      </td>
     </tr>
     <tr>
      <td colspan="2" align="center">
       <br>
       <input type="submit" value="Salvar" name="salvar">
       <input type="button" value="Limpar" name="limpar" onclick="js_limpar();">
       <input type="button" value="Ver Quadro Geral" name="geral" onclick="js_geral();">
       <input type="hidden" value="<?=$serie?>" name="serie">
      </td>
     </tr>
     <tr>
      <td align="center">
       <fieldset style="width:400px;align:center">
        Para selecionar mais de uma turma<br>mantenha pressionada a tecla CTRL <br>e clique sobre os nomes das turmas.
       </fieldset>
      </td>
     </tr>     
     <script>
     fillSelectFromArray(document.form1.subgrupo, ((document.form1.grupo.selectedIndex == -1) ? null : team[document.form1.grupo.selectedIndex-1]));
     document.form1.subgrupo.value = <?=$serie?>;
     document.getElementById("nomeserie").innerHTML = document.form1.subgrupo[document.form1.subgrupo.selectedIndex].text;
     </script>
     <?}?>
    </table>
    </center>
    </form>
    </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<?
if(isset($salvar)){
 db_msgbox("Alteração efetuada com sucesso!");
}
?>
<script>
function js_procurar(calendario,serie){
 if(serie!=""){
  location.href = "edu4_aprovautomatica001.php?calendario="+calendario+"&serie="+serie;
 }else{
  location.href = "edu4_aprovautomatica001.php?calendario=0";
 }
}
function js_limpar(){
 tam = document.form1.turmasaprov.length;
 for(i=0;i<tam;i++){
  document.form1.turmasaprov[i].selected = false;
 }
}
function js_zerar(){
 <?if(isset($serie)){?>
  qtd = document.form1.turmasaprov.length;
  for (i = 0; i < qtd; i++) {
  document.form1.turmasaprov.options[0] = null;
 }
 document.getElementById("nomeserie").innerHTML = "_________";
 <?}?>
}
function js_geral(){
 js_OpenJanelaIframe('','db_iframe_geral','edu4_aprovautomatica002.php','Quadro Geral de Aprovação Automática de Turmas',true);
}
</script>