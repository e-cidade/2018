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
include("classes/db_calendario_classe.php");
include("classes/db_trocaserie_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clcalendario = new cl_calendario;
$cltrocaserie = new cl_trocaserie;
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
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.cabec{
 font-size: 11;
 font-weight: bold;
 color: #DEB887;
 background-color:#444444;
 border:1px solid #CCCCCC;
}
</style>
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
  <td align="center" valign="top" bgcolor="#CCCCCC">
   <?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
   <br>
   <form name="form1" method="post" action="">
   <fieldset style="width:95%"><legend><b>Consulta Progressão de Aluno</b></legend>
    <?
    $result = $clcalendario->sql_record($clcalendario->sql_query_calturma("","ed52_i_codigo,ed52_c_descr,ed52_i_ano","ed52_i_ano desc"," ed38_i_escola = $escola AND ed52_c_passivo = 'N'"));?>
    <table border="0" cellspacing="0" cellpadding="0">
     <tr>
      <td align="left" valign="top">
       <b>Selecione o Calendário:</b>
       <select name="calendario" style="font-size:9px;width:150px;height:18px;">
        <option value=""></option>
        <?
        for($i=0;$i<$clcalendario->numrows;$i++) {
         db_fieldsmemory($result,$i);
         $selected = $ed52_i_codigo==@$calendario?"selected":"";
         echo "<option value='$ed52_i_codigo' $selected>$ed52_c_descr</option>\n";
        }
        ?>
       </select>
      </td>
      <td>
       <b>Tipo:</b>
       <select name="tipo" style="font-size:9px;width:150px;height:18px;">
        <option value=""></option>
        <option value='A'<?=@$tipo=="A"?"selected":"";?>>AVANÇO</option>
        <option value='C'<?=@$tipo=="C"?"selected":"";?>>CLASSIFICAÇÃO</option>
       </select>
       <input type="button" value="Processar" onclick="js_processar(document.form1.calendario.value,document.form1.tipo.value)">
      </td>
     </tr>
    </table>
   </fieldset>
   </form>
  </td>
 </tr>
</table>
<?
 if(isset($calendario)){
 $where = $tipo!=""?" AND ed101_c_tipo = '$tipo '":"";
 $sql = "SELECT DISTINCT
          ed47_i_codigo,
          ed47_v_nome,
          (select ed11_c_descr
           from serie
            inner join matriculaserie on ed221_i_serie = ed11_i_codigo
            inner join matricula on ed60_i_codigo = ed221_i_matricula
           where ed60_i_turma = turmaorig.ed57_i_codigo
           and ed60_c_situacao = 'AVANÇADO'
           and ed60_i_aluno = ed47_i_codigo
           and ed221_c_origem = 'S') as serieorigem,
          (select ed11_c_descr
           from serie
            inner join matriculaserie on ed221_i_serie = ed11_i_codigo
            inner join matricula on ed60_i_codigo = ed221_i_matricula
           where ed60_i_turma = turmadest.ed57_i_codigo
           and ed60_c_situacao != 'AVANÇADO'
           and ed60_i_aluno = ed47_i_codigo
           and ed221_c_origem = 'S') as seriedestino,
          turmaorig.ed57_c_descr as turmaorigem,
          turmadest.ed57_c_descr as turmadestino,
          ed101_d_data,
          ed101_c_tipo
         FROM trocaserie
          inner join aluno on ed47_i_codigo = ed101_i_aluno
          inner join turma as turmaorig on turmaorig.ed57_i_codigo = ed101_i_turmaorig
          inner join turma as turmadest on turmadest.ed57_i_codigo = ed101_i_turmadest
         WHERE turmaorig.ed57_i_calendario = $calendario
         $where
         ORDER BY ed47_v_nome
        ";
 $result1 = $cltrocaserie->sql_record($sql);
 ?>
 <table align="center" width="97%" border="1" cellspacing="0" cellpadding="0">
  <tr>
   <td class="cabec" align="center">Tipo</td>
   <td class="cabec" align="center">Cód. Aluno</td>
   <td class="cabec" align="center">Aluno</td>
   <td class="cabec" align="center">Etapa/Turma Origem</td>
   <td class="cabec" align="center">Etapa/Turma Destino</td>
   <td class="cabec" align="center">Data</td>
  </tr>
  <?
  $cor1 = "#f3f3f3";
  $cor2 = "#DBDBDB";
  $cor = "";
  if($cltrocaserie->numrows>0){
   for($x=0;$x<$cltrocaserie->numrows;$x++){
    db_fieldsmemory($result1,$x);
    if($cor==$cor1){
     $cor = $cor2;
    }else{
     $cor = $cor1;
    }
    ?>
    <tr bgcolor="<?=$cor?>">
     <td><?=$ed101_c_tipo=="A"?"AVANÇO":"CLASSIF."?></td>
     <td align="center"><?=$ed47_i_codigo?></td>
     <td><?=$ed47_v_nome?></td>
     <td align="center"><?=$serieorigem." / ".$turmaorigem?></td>
     <td align="center"><?=$seriedestino." / ".$turmadestino?></td>
     <td align="center"><?=db_formatar($ed101_d_data,'d')?></td>
    </tr>
    <?
   }
  }else{
   ?>
   <tr bgcolor="#f3f3f3">
    <?
    if($tipo==""){
     $descr_tipo = " Nenhum AVANÇO ou CLASSIFICAÇÃO";
    }elseif($tipo=="A"){
     $descr_tipo = " Nenhum AVANÇO";
    }elseif($tipo=="C"){
     $descr_tipo = " Nenhuma CLASSIFICAÇÃO";
    }
    ?>
    <td colspan="6"><?=$descr_tipo?> neste calendário.</td>
   </tr>
   <?
  }
  ?>
 </table>
<?}?>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
function js_processar(calendario,tipo){
 if(calendario!=""){
  location.href = "edu3_trocaserie001.php?calendario="+calendario+"&tipo="+tipo;
 }
}
<?if(!isset($calendario) && pg_num_rows($result)>0){?>
 document.form1.calendario.options[1].selected = true;
<?}?>
</script>