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
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
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
   <form name="form1" method="post" action="" onsubmit="return check();">
   <fieldset style="width:95%"><legend><b>Consulta de Logs</b></legend>
    <table width="100%" border="0" cellspacing="2" cellpadding="0">
     <tr>
      <td width="10%" nowrap title="<?=@$Ted90_c_tabela?>">
       <?db_ancora("<b>Tabela:</b>","js_pesquisaed90_c_tabela();",$db_opcao);?>
      </td>
      <td>
       <?db_input('ed90_c_tabela',30,@$Ied90_c_tabela,true,'text',3,"")?>
       <?db_input('ed90_c_modulo',40,@$Ied90_c_modulo,true,'text',3,"")?>
      </td>
     </tr>
     <tr>
      <td width="10%" nowrap title="<?=@$Ted90_c_tabela?>">
       <b>Tipo de Ação:</b>
      </td>
      <td>
       <?
       $x = array(''=>'','I'=>'INCLUSÃO','A'=>'ALTERAÇÃO','E'=>'EXCLUSÃO');
       db_select('actipo',$x,true,$db_opcao," onchange='js_registros(this.value)'");
       ?>
       <span id="reg">
       <b>Registro:</b>
       <?db_input('registro',20,@$Iregistro,true,'text',$db_opcao,"")?>
       </span>
      </td>
     </tr>
     <tr>
      <td colspan="2">
       <input type="submit" name="processar" value="Processar">
      </td>
     </tr>
    </table>
   </fieldset>
   </form>
  </td>
 </tr>
</table>
<?
 if(isset($ed90_c_tabela)){
 $where = "";
 $where .= $actipo==""?"":" AND db_acountkey.actipo = '$actipo'";
 $where .= $registro==""?"":" AND db_acountkey.campotext = '$registro'";
 $sql = "SELECT db_acountkey.campotext,
                db_acountkey.id_acount,
                db_sysarquivo.nomearq,
                db_syscampo.descricao,
                db_syscampo.nulo,
                db_acount.contant,
                db_acount.contatu,
                db_acount.datahr,
                db_acountkey.actipo,
                db_usuarios.nome
         FROM db_acount
          inner join db_acountkey on db_acountkey.id_acount = db_acount.id_acount
          inner join db_syscampo on db_syscampo.codcam = db_acount.codcam
          inner join db_sysarquivo on db_sysarquivo.codarq = db_acount.codarq
          inner join db_usuarios on db_usuarios.id_usuario = db_acount.id_usuario
         WHERE db_sysarquivo.nomearq = '$ed90_c_tabela'
         AND trim(db_acount.contant) != trim(db_acount.contatu)
         $where
         ORDER BY datahr desc,campotext asc,db_acountkey.id_acount desc,db_syscampo.codcam asc
        ";
 $result = pg_query($sql);
 $linhas = pg_num_rows($result);
 //db_criatabela($result);
 if($linhas>0){
  $datainicial = "";
  $codigoinicial = "";
  $idinicial = "";
  ?>
  <table align="center" width="97%" border="1" cellspacing="0" cellpadding="0">
   <tr style="font-weight:bold;" bgcolor="#dbdbdb" align="center">
    <td width="10">&nbsp;</td>
    <td>Descrição</td>
    <td>Anterior</td>
    <td>Atual</td>
    <td>Tipo</td>
   </tr>
  <?
  for($x=0;$x<$linhas;$x++){
   db_fieldsmemory($result,$x);
   $data = date("d/m/Y",trim($datahr));
   $hora = date("H:i:s",trim($datahr));
   if($data!=$datainicial){
    ?>
    <tr bgcolor="#444444" style="color:#DEB887">
     <td colspan="5">
      <b>Data: <?=$data?></b>
     </td>
    </tr>
    <?
    $datainicial = $data;
   }
   if($campotext!=$codigoinicial){
    ?>
    <tr bgcolor="#dbdbdb">
     <td colspan="5">
      <b>Registro:</b> <?=$campotext?> <?=Registro($ed90_c_tabela,$campotext)?>
     </td>
    </tr>
    <?
    $codigoinicial = $campotext;
   }
   if($id_acount!=$idinicial){
    ?>
    <tr bgcolor="#dbdbdb">
     <td>&nbsp;</td>
     <td colspan="4">
      <b>Código:</b> <?=$id_acount?> <b>Usuário:</b> <?=$nome?>
     </td>
    </tr>
    <?
    $idinicial = $id_acount;
   }
   ?>
   <tr bgcolor="#f3f3f3">
    <td>&nbsp;</td>
    <td style="font-size:9px;"><?=$descricao?></td>
    <td bgcolor="#CCFFCC" style="font-size:9px;"><?=$contant==""?"&nbsp;":trim($contant)?></td>
    <td bgcolor="#CCFFCC" style="font-size:9px;"><?=$contatu==""?"&nbsp;":trim($contatu)?></td>
    <td style="font-size:9px;"><?=($actipo=="I")?"INCLUSÃO":($actipo=="A"?"ALTERAÇÃO":"EXCLUSÃO")?></td>
   </tr>
   <?
  }
  ?>
  </table>
  <br><br>
  <?
 }else{
  ?>
  <table align="center" width="760" border="1" cellspacing="0" cellpadding="0">
   <tr>
    <td bgcolor="#f3f3f3" align="center">
    <b>Nenhum registro para as opções selecionadas.</b>
    </td>
   </tr>
  </table>
  <?
 }
}?>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
function js_pesquisaed90_c_tabela(){
 js_OpenJanelaIframe('top.corpo','db_iframe_tabela','func_tabelalog_edu.php?funcao_js=parent.js_mostratabela|nomearq|nomemod','Pesquisa de Tabelas',true);
}
function js_mostratabela(chave1,chave2){
 document.form1.ed90_c_tabela.value = chave1;
 document.form1.ed90_c_modulo.value = chave2;
 db_iframe_tabela.hide();
}
function check(){
 F = document.form1;
 if(F.ed90_c_tabela.value==""){
  alert("Informe a tabela!");
  js_pesquisaed90_c_tabela();
  return false;
 }
 return true;
}
function js_registros(valor){
 if(valor=="E"){
  document.getElementById("reg").style.visibility = "hidden";
  document.form1.registro.value = "";
 }else{
  document.getElementById("reg").style.visibility = "visible";
 }
}
</script>
<?
function Registro($tabela,$valor){
 if(trim($tabela)=="regencia"){
  $sql = "SELECT ed232_c_descr, ed18_c_nome FROM regencia
           inner join disciplina on ed12_i_codigo = ed59_i_disciplina
           inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina
           inner join turma on ed57_i_codigo = ed59_i_turma
           inner join escola on ed18_i_codigo = ed57_i_escola
          WHERE ed59_i_codigo = $valor";
 }elseif(trim($tabela)=="parecer"){
  $sql = "SELECT ed92_c_descr FROM $tabela
          WHERE ed92_i_codigo = $valor
         ";
 }else{
  $sql = "";
 }
 if($sql!=""){
  $result = pg_query($sql);
  $linhas = pg_num_rows($result);
  if($linhas>0){
   $ncampos = pg_num_fields($result);
   $retorno = "";
   $sep = "";
   for($x=0;$x<$ncampos;$x++){
    $retorno .= $sep.pg_result($result,0,$x);
    $sep = " - ";
   }
   return $retorno;
  }
 }
}
?>