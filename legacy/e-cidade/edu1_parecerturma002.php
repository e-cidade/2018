<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
include("classes/db_parecerturma_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($_POST);
db_postmemory($_GET);
$clparecerturma = new cl_parecerturma;
$db_opcao = 2;
$db_botao = true;
$escola   = db_getsession("DB_coddepto");
$sWhereDisciplinas = "";
if (isset($listadisciplinas) && !empty($listadisciplinas)) {
  $sWhereDisciplinas = " and ed59_i_disciplina in ({$listadisciplinas}) ";
}

if (isset($alterar)) {
  
  $db_opcao = 2;
  db_inicio_transacao();
  $sql1 = "DELETE FROM parecerturma
           WHERE ed105_i_codigo in (select ed105_i_codigo
                                    from parecerturma
                                     inner join turma on ed57_i_codigo = ed105_i_turma
                                    where ed57_i_calendario = $calendario
                                    and ed105_i_parecer = $codigoparecer
                                   )";
  $result1 = db_query($sql1);
  if (isset($checkturma)) {
    
    $aTurmasVinculadas = array();
    for ($t = 0; $t < count($checkturma); $t++) {
      
      if ( in_array($checkturma[$t], $aTurmasVinculadas)) {
        continue;
      }
      $aTurmasVinculadas[] = $checkturma[$t];

      $clparecerturma->ed105_i_turma   = $checkturma[$t];
      $clparecerturma->ed105_i_parecer = $codigoparecer;
      $clparecerturma->incluir(null);
    }
    if ($clparecerturma->erro_status == "0") {
      
      $clparecerturma->erro(true,false);
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<form name="form1" method="post" action="">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Turmas para o Parecer Cod.: <?=$codigoparecer?> - <?=substr($descrparecer,0,80)?><?=strlen($descrparecer)>80?"...":""?></b></legend>
   <table border="0" align="left" width="95%">
    </tr>
     <td>
      <b>Selecione o Calendário:</b>
      <select name="calendario" onchange="js_calendario(this.value);" style="font-size:9px;width:200px;height:18px;">
       <option></option>
       <?
       #Seleciona todos os grupos para setar os valores no combo
       $sql = "SELECT ed52_i_codigo,ed52_c_descr
                 FROM calendario
                inner join calendarioescola on ed38_i_calendario = ed52_i_codigo
                WHERE ed38_i_escola = $escola
                  AND ed52_c_passivo = 'N'
               ORDER BY ed52_i_ano DESC";
       $sql_result = db_query($sql);
       $linhas_result = pg_num_rows($sql_result);
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
    </tr>
    <?if(isset($calendario)){?>
    <tr>
     <td>
      <table border='1px' width="100%" bgcolor="#cccccc" style="" cellspacing="0px">
       <tr>
        <td align="center">
         <input style="height:12px;" type="checkbox" id="MT" value="" onclick="js_marcatudo();"> Marcar Tudo
         <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
         <input name="restaurar" type="button" value="Restaurar" onclick="location.href='edu1_parecerturma002.php?codigoparecer=<?=$codigoparecer?>&descrparecer=<?=$descrparecer?>&calendario=<?=$calendario?>';">
        </td>
       </tr>
       <?
       
       $sql = "SELECT DISTINCT
                      ed10_i_codigo,
                      ed10_c_descr,
                      ed11_i_codigo,
                      ed11_c_descr,
                      ed11_i_sequencia,
                      ed57_c_descr,
                      ed57_i_codigo,
                      ed15_c_nome
               FROM turma
                inner join turmaserieregimemat on ed220_i_turma  = ed57_i_codigo
                inner join serieregimemat      on ed223_i_codigo = ed220_i_serieregimemat
                inner join serie               on ed11_i_codigo  = ed223_i_serie
                inner join turno               on ed15_i_codigo  = ed57_i_turno
                inner join ensino              on ed10_i_codigo  = ed11_i_ensino
                inner join regencia            on ed59_i_turma   = ed57_i_codigo
               WHERE ed57_i_calendario = $calendario
                 and ed57_i_escola = $escola
                 {$sWhereDisciplinas}
               ORDER BY ed10_i_codigo,ed11_i_sequencia,ed57_c_descr
              ";
       $result = db_query($sql);
       //db_criatabela($result);
       $linhas = pg_num_rows($result);
       $ensino = "";
       $serie = "";
       $turma = "";
       $codentrada = "";
       $sepentrada = "";
       $codturma = "";
       $septurma = "";
       if($linhas>0){
        $cor1 = "#dbdbdb";
        $cor2 = "#f3f3f3";
        for($c=0;$c<$linhas;$c++){
         db_fieldsmemory($result,$c);
         if($serie!=$ed11_c_descr){
          if($c!=0){
           echo "</tr></table></td></tr>";
          }
          if($ensino!=$ed10_i_codigo){
           if($c!=0){
            $septurma = "|";
           }
           ?>
           <tr bgcolor="#999999">
            <td class='cabec'>
             <b>&nbsp;&nbsp;<?=$ed10_c_descr?></b>
             <input style="height:12px;" type="checkbox" id="MTE<?=$ed10_i_codigo?>" value="" onclick="js_marcaensino(<?=$ed10_i_codigo?>);"> Marcar Tudo
            </td>
           </tr>
           <?
           $ensino = $ed10_i_codigo;
           $codentrada .= $sepentrada.$ed10_i_codigo;
           $sepentrada = "|";
          }
          ?>
          <tr bgcolor="<?=$cor1?>">
           <td>
            <b>&nbsp;&nbsp;Etapa: <?=$ed11_c_descr?></b>
           </td>
          </tr>
          <tr bgcolor="<?=$cor2?>">
           <td>
            <table border="0" cellpading="0" cellspacing="0">
             <tr>
             <?
             $serie = $ed11_c_descr;
         }
         $result1 = $clparecerturma->sql_record($clparecerturma->sql_query("","ed105_i_codigo",""," ed105_i_turma = $ed57_i_codigo AND ed105_i_parecer = $codigoparecer"));
         if($clparecerturma->numrows>0){
          //db_fieldsmemory($result,0);
          $checked = "checked";
         }else{
          $checked = "";
         }
         ?>
         <td>
          <input style="height:12px;" type="checkbox" id="checkturma" name="checkturma[]" value="<?=$ed57_i_codigo?>" <?=$checked?>>
          <?=$ed57_c_descr?>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         </td>
         <?
         $codturma .= $septurma.$ed57_i_codigo;
         $septurma = ",";
        }
       }else{
        ?>
        <table border='1px' width="100%" bgcolor="#cccccc" style="" cellspacing="0px">
         <tr bgcolor="#EAEAEA">
          <td class='aluno'>NENHUMA TURMA NESTE CALENDÁRIO.</td>
         </tr>
        </table>
        <?
       }
       ?>
      </table>
     </td>
    </tr>
    <input type="hidden" name="codigoparecer" value="<?=$codigoparecer?>">
    <input type="hidden" name="descrparecer" value="<?=$descrparecer?>">
    <input type="hidden" name="calendario" value="<?=$calendario?>">
    <input type="hidden" name="codentrada" value="<?=$codentrada?>">
    <input type="hidden" name="codturma" value="<?=$codturma?>">
    <?}?>
   </table>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</form>
</body>
</html>
<script>
function js_calendario(valor) {
  
 if (valor == "") {
   location.href="edu1_parecerturma002.php?codigoparecer=<?=$codigoparecer?>&descrparecer=<?=$descrparecer?>&listadisciplinas=<?=$listadisciplinas?>";
 } else {
   location.href="edu1_parecerturma002.php?codigoparecer=<?=$codigoparecer?>&descrparecer=<?=$descrparecer?>&calendario="+valor+"&listadisciplinas=<?=$listadisciplinas?>";
 }
}
function js_marcatudo(codigo) {
  
  tam = document.form1.checkturma.length;
  for (i = 0; i < tam; i++) {
    
    if(document.getElementById("MT").checked==true){
      document.form1.checkturma[i].checked=true;
    }else{
      document.form1.checkturma[i].checked=false;
    }
  }
}
function js_marcaensino(codigo) {
  
  tam = document.form1.checkturma.length;
  arr_entrada = document.form1.codentrada.value.split("|");
  arr_turma = document.form1.codturma.value.split("|");
  for (t = 0; t < arr_entrada.length; t++) {
    
    if (codigo==arr_entrada[t]) {
      
      codturma = arr_turma[t].split(",");
      for (x=0;x<codturma.length;x++) {
        
        for (i=0;i<tam;i++) {
          
          if (document.form1.checkturma[i].value==codturma[x]) {
            
            if (document.getElementById("MTE"+codigo).checked==true) {
              document.form1.checkturma[i].checked = true;
            } else {
              document.form1.checkturma[i].checked = false;
            }
          }
        }
      }
    }
  }
}
<?
if($linhas_result>0 && !isset($calendario)){
 ?>
 js_calendario(document.form1.calendario.options[1].value);
 <?
}
?>
</script>