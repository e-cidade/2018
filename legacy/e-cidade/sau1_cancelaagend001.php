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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_agendamentos_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clagendamentos = new cl_agendamentos;
$db_botao = false;
$db_opcao = 22;
if(isset($alterar)){
  db_inicio_transacao();
  $db_opcao = 3;
  $clagendamentos->sd23_c_situacao = 'CANCELADO';
  $clagendamentos->alterar($sd23_c_atendimento);
  db_fim_transacao();
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $sql = "select *, cgm1.z01_nome as z01_nomemed from agendamentos
            inner join cgm             on  cgm.z01_numcgm                =  agendamentos.sd23_i_cgm
            inner join db_usuarios     on  db_usuarios.id_usuario        =  agendamentos.sd23_i_usuario
            inner join especialidades  on  especialidades.sd05_i_codigo  =  agendamentos.sd23_i_especialidade
            inner join unidades        on  unidades.sd02_i_codigo        =  agendamentos.sd23_i_unidade
            inner join medicos         on  medicos.sd03_i_id         =  agendamentos.sd23_i_medico
            inner join cgm as cgm1     on  cgm1.z01_numcgm               =  medicos.sd03_i_codigo
           where agendamentos.sd23_c_atendimento = '$chavepesquisa'";
   $result = $clagendamentos->sql_record($sql);
   db_fieldsmemory($result,0);
   if($sd23_c_situacao == "AGENDADO" ){
    $db_botao = true;
   }
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
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
<?
//MODULO: saude
$clagendamentos->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("sd02_c_nome");
$clrotulo->label("sd03_c_nome");
$clrotulo->label("sd05_c_descr");
$clrotulo->label("nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tsd23_c_atendimento?>">
       <?=@$Lsd23_c_atendimento?>
    </td>
    <td>
<?
db_input('sd23_c_atendimento',11,$Isd23_c_atendimento,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd23_i_cgm?>">
       <?
       db_ancora(@$Lsd23_i_cgm,"js_pesquisasd23_i_cgm(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('sd23_i_cgm',10,$Isd23_i_cgm,true,'text',$db_opcao," onchange='js_pesquisasd23_i_cgm(false);'")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd23_i_unidade?>">
       <?
       db_ancora(@$Lsd23_i_unidade,"js_pesquisasd23_i_unidade(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('sd23_i_unidade',10,$Isd23_i_unidade,true,'text',$db_opcao," onchange='js_pesquisasd23_i_unidade(false);'")
?>
       <?
db_input('sd02_c_nome',50,$Isd02_c_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd23_i_medico?>">
       <?
       db_ancora(@$Lsd23_i_medico,"js_pesquisasd23_i_medico(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('sd23_i_medico',10,$Isd23_i_medico,true,'text',$db_opcao," onchange='js_pesquisasd23_i_medico(false);'")
?>
       <?
db_input('z01_nomemed',50,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd23_i_especialidade?>">
       <?
       db_ancora(@$Lsd23_i_especialidade,"js_pesquisasd23_i_especialidade(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('sd23_i_especialidade',10,$Isd23_i_especialidade,true,'text',$db_opcao," onchange='js_pesquisasd23_i_especialidade(false);'")
?>
       <?
db_input('sd05_c_descr',50,$Isd05_c_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd23_d_consulta?>">
       <?=@$Lsd23_d_consulta?>
    </td>
    <td>
<?
db_inputdata('sd23_d_consulta',@$sd23_d_consulta_dia,@$sd23_d_consulta_mes,@$sd23_d_consulta_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd23_i_fichas?>">
       <?=@$Lsd23_i_fichas?>
    </td>
    <td>
<?
db_input('sd23_i_fichas',10,$Isd23_i_fichas,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd23_c_situacao?>">
       <?=@$Lsd23_c_situacao?>
    </td>
    <td>
<?
db_input('sd23_c_situacao',9,$Isd23_c_situacao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd23_c_hora?>">
       <?=@$Lsd23_c_hora?>
    </td>
    <td>
<?
db_input('sd23_c_hora',5,$Isd23_c_hora,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="alterar" type="submit" id="db_opcao" value="Cancelar Agendamento" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_agendamentos','func_agendamentos.php?funcao_js=parent.js_preenchepesquisa|sd23_c_atendimento','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_agendamentos.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
    </center>
        </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($alterar)){
  if($clagendamentos->erro_status=="0"){
    $clagendamentos->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clagendamentos->erro_campo!=""){
      echo "<script> document.form1.".$clagendamentos->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clagendamentos->erro_campo.".focus();</script>";
    };
  }else{
    $clagendamentos->erro(true,true);
  };
};
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>