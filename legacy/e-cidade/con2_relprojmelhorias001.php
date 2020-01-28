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
require_once("dbforms/db_funcoes.php");
require_once("classes/db_projmelhorias_classe.php");
require_once("classes/db_projmelhoriasmatric_classe.php");

$clprojmelhorias = new cl_projmelhorias;
$clprojmelhorias->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j14_nome");
$clrotulo->label("z01_nome");
$db_opcao = 1;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_relatorio() {
  rel=document.form1.relatorio.value;
  if(rel==1){
    jan = window.open('con2_relprojmelhorias002.php?d40_codigo='+document.form1.d40_codigo.value+'&tipo=2','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  }else if(rel==2){
    jan = window.open('con2_relprojmelhorias002.php?d40_codigo='+document.form1.d40_codigo.value+'&tipo=1','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  }else if(rel==3){
    op=document.form1.opcao.value;
    jan = window.open('con2_relprojmelhorias003.php?opcao='+op+'&d40_codigo='+document.form1.d40_codigo.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  }

  jan.moveTo(0,0);
}
function js_troca(rel){
  if(rel==3){
    document.getElementById('opc').style.visibility="visible";
  }else{
    document.getElementById('opc').style.visibility="hidden";
  }

}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
  <div class="container">
    <form name="form1" method="post" action="">
      <fieldset>
        <legend>Lista de Projetos</legend>

        <table >
        <tr>
          <td nowrap title="<?=@$Td40_codigo?>">
            <?
               db_ancora(@$Ld40_codigo,"js_contri(true);",$db_opcao);
            ?>
          </td>
          <td colspan="2">
            <?
              db_input('d40_codigo',7,$Id40_codigo,true,'text',$db_opcao," onchange='js_contri(false);'");
              db_input('j14_nome',40,$Ij14_nome,true,'text',3);
            ?>
          </td>
        </tr>
      	<tr>
      	   <td>
      	     <strong>Relatório:</strong>
      	   </td>
           <td height="25" nowrap  align="left" >
              <?
              $result=array("1"=>"Pavimentação","2"=>"Pavimentação Solidária","3"=>"Financeiro");
              db_select("relatorio",$result,true,$db_opcao,"onchange='js_troca(this.value);'");
              ?>
	         </td>
      	   <td rowspan="3" id="opc" style="visibility:hidden;">
      	     <input type="radio"  name="opca" id="id01" value="1" onclick="js_op('1');" checked><small><strong><label for="id01" >Todos</label></strong></small><br>
                   <input type="radio"  name="opca" id="id02" value="2" onclick="js_op('2');"><small><strong><label for="id02" >Lançamento em Contribuição de Melhoria</label></strong></small><br>
                   <input type="radio"  name="opca" id="id03" value="3" onclick="js_op('3');"><small><strong><label for="id03" >Pagos ao empreiteiro</label></strong></small>
                   <input type="hidden" name="opcao" value="1">
      	   </td>
	     </tr>
       </table>
     </fieldset>
  	 <input name="gerar" type="button" id="boletim" onClick="js_relatorio()" value="Gerar relatório"/>
    </form>
  </div>
    <?
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
</body>
</html>
<script type="text/javascript">
function js_op(va){
  document.form1.opcao.value=va;
}
function js_contri(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_projmelhorias.php?funcao_js=parent.js_mostracontri1|d40_codigo|j14_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe','func_projmelhorias.php?pesquisa_chave='+document.form1.d40_codigo.value+'&funcao_js=parent.js_mostracontri','Pesquisa',false);
  }
}
function js_mostracontri(chave,erro){
  if(erro==true){
    document.form1.d40_codigo.focus();
    document.form1.d40_codigo.value="";
    document.form1.j14_nome.value="";
  }else{
      document.form1.j14_nome.value = chave;
  }
}
function js_mostracontri1(chave1,chave2){
  document.form1.d40_codigo.value = chave1;
  document.form1.j14_nome.value = chave2;
  db_iframe.hide();
}
</script>