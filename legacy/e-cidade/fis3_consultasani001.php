<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_sanitario_classe.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$clsanitario = new cl_sanitario;
$clrotulo    = new rotulocampo;
$clsanitario->rotulo->label();
$clrotulo->label("z01_nome");
$clrotulo->label("q03_descr");
$db_opcao    = 1;

if(isset($chavepesquisa)){

   $result = $clsanitario->sql_record($clsanitario->sql_query($chavepesquisa));
   db_fieldsmemory($result,0);
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
<body class="body-default">
<div class="container">
<form name="form1" method="post" action="">
  <fieldset>
    <legend>Alvará Sanitário</legend>
    <table>
      <tr>
        <td nowrap title="<?=@$Ty80_codsani?>">
           <?php
           db_ancora(@$Ly80_codsani,"js_pesquisa();",1);
           ?>
        </td>
        <td>
        <?php
        db_input('y80_codsani',10,$Iy80_codsani,true,'text',$db_opcao,"")
        ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tz01_nome?>">
           <?php
           db_ancora(@$Lz01_nome,"js_pesquisay80_numcgm(true);",$db_opcao);
           ?>
        </td>
        <td>
          <?php
            db_input('y80_numcgm',10,$Iy80_numcgm,true,'text',$db_opcao," onchange='js_pesquisay80_numcgm(false);'");
            db_input('z01_nome',40,$Iz01_nome,true,'text',3,'');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ty80_data?>">
           <?=@$Ly80_data?>
        </td>
        <td>
        <?php
        db_inputdata('',@$dia,@$mes,@$ano,true,'text',$db_opcao,"");
        ?>
        &nbsp;&nbsp;&nbsp;À&nbsp;&nbsp;&nbsp;
        <?php
        db_inputdata('a',@$diaa,@$mesa,@$anoa,true,'text',$db_opcao,"");
        ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="Atividades">
           <strong>
           <?php
            db_ancora("Atividade","js_pesquisay83_ativ(true);",$db_opcao);
           ?>
           </strong>
        </td>
        <td>
           <?php
            db_input('ativ',8,"",true,'text',$db_opcao," onchange='js_pesquisay83_ativ(false);'");
            db_input('q03_descr',40,$Iq03_descr,true,'text',3,'');
           ?>
        </td>
      </tr>
      </table>
  </fieldset>
  <input name="consultar" type="button" value="Consultar" onClick="js_consultasani();js_limpacampos();" >
</form>
</div>
<?php
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script type="text/javascript">

function js_limpacampos(){

  document.form1.y80_codsani.value = '';
  document.form1.y80_numcgm.value  = '';
  document.form1.z01_nome.value    = '';
  document.form1.ativ.value        = '';
  document.form1.q03_descr.value   = '';
  document.form1._dia.value        = '';
  document.form1._mes.value        = '';
  document.form1._ano.value        = '';
  document.form1.a_dia.value       = '';
  document.form1.a_mes.value       = '';
  document.form1.a_ano.value       = '';
}
function js_consultasani(){
  js_OpenJanelaIframe('top.corpo','db_iframe_consultasani','fis3_consultasani002.php?y80_codsani='+document.form1.y80_codsani.value+'&y80_numcgm='+document.form1.y80_numcgm.value+'&ativ='+document.form1.ativ.value+'&dataini='+document.form1._ano.value+'-'+document.form1._mes.value+'-'+document.form1._dia.value+'&datafim='+document.form1.a_ano.value+'-'+document.form1.a_mes.value+'-'+document.form1.a_dia.value+'&funcao_js=parent.js_abreconsulta|y80_codsani','Pesquisa',true);
}
function js_pesquisay80_numcgm(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.y80_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
  }
}
function js_mostracgm(erro,chave){

  document.form1.z01_nome.value = chave;

  if (chave == true) {

    document.form1.y80_numcgm.focus();
    document.form1.y80_numcgm.value = '';
  }
}
function js_mostracgm1(chave1,chave2){

  document.form1.y80_numcgm.value = chave1;
  document.form1.z01_nome.value   = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_sanitario','func_sanitario.php?lMostarTodos=true&funcao_js=parent.js_preenchepesquisa|y80_codsani','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  js_OpenJanelaIframe('top.corpo','db_iframe_consulta','fis3_consultasani002.php?y80_codsani='+chave,'Pesquisa',true);
}
function js_pesquisay83_ativ(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_ativid','func_ativid.php?funcao_js=parent.js_mostraativid1|q03_ativ|q03_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_ativid','func_ativid.php?pesquisa_chave='+document.form1.ativ.value+'&funcao_js=parent.js_mostraativid','Pesquisa',false);
  }
}
function js_mostraativid(chave,erro){

  document.form1.q03_descr.value = chave;
  if(erro==true){

    document.form1.ativ.focus();
    document.form1.ativ.value = '';
  }
}
function js_mostraativid1(chave1,chave2){

  document.form1.ativ.value      = chave1;
  document.form1.q03_descr.value = chave2;
  db_iframe_ativid.hide();
}
function js_abreconsulta(chave){
  js_OpenJanelaIframe('top.corpo','db_iframe_consulta','fis3_consultasani002.php?y80_codsani='+chave,'Pesquisa',true,25);
}
</script>