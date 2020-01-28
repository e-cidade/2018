<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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

//MODULO: protocolo
$cltipoproc->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tp51_codigo?>">
       <?=@$Lp51_codigo?>
    </td>
    <td> 
<?
db_input('p51_codigo',3,$Ip51_codigo,true,'text',3,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tp51_descr?>">
       <?=@$Lp51_descr?>
    </td>
    <td> 
<?
db_input('p51_descr',60,$Ip51_descr,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tp51_dtlimite?>">
       <?=@$Lp51_dtlimite?>
    </td>
    <td> 
<?
$matriz = array('t'=>"Sim",'f'=>"Nao");
db_inputdata('p51_dtlimite',@$p51_dtlimite_dia,@$p51_dtlimite_mes,@$p51_dtlimite_ano,true,'text',$db_opcao,"");
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
  function js_pesquisa() {
    js_OpenJanelaIframe(
      '',
      'db_iframe',
      'func_tipoproc_todos.php?grupo=1&funcao_js=parent.js_preenchepesquisa|0',
      'Pesquisa Tipo de Processo',
      true
    );
  }
  function js_preenchepesquisa(chave){
    db_iframe.hide();
    location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
  }
</script>
<?php

if($db_opcao == 22 || $db_opcao == 33){
  ?>
  <script>
    js_pesquisa();
  </script>
  <?php
}
?>