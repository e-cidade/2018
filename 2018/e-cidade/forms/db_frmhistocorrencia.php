<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
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

$clhistocorrencia->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
$clrotulo->label("nomeinst");
$clrotulo->label("nome_modulo");
$clrotulo->label("descricao");

$clrotulo->label('j01_matric');
$clrotulo->label('q02_inscr');
$clrotulo->label('z01_numcgm');
$clrotulo->label('z01_nome');
?>

<form name="form1" method="post" action="">
<center>
<table border="0" align="center" style="margin-top: 50px">
<?php

//Gerar historico pelo cgm
if((isset($z01_numcgm)) and ($z01_numcgm != '')) {
  ?>
	<tr>
		<td align="left" valign="top" bgcolor="#CCCCCC"><?=$Lz01_nome?></td>
		<td>
		<?
		  db_input("z01_numcgm", 10, $Iz01_numcgm, true, 'text', 3, " onchange='js_mostranomes(false);'");
		  db_input("z01_nome", 40, $Iz01_nome, true, 'text', 3);
		?>
		</td>
	</tr>
	<?
}
//gerar historico por matricula
else if((isset($j01_matric)) and ($j01_matric != '')){
  ?>
	<tr>
		<td title="<?=$Tj01_matric?>"><?=$Lj01_matric?></td>
		<td>
		<?
  		db_input("j01_matric", 10, $Ij01_matric, true, 'text', 3);
  		db_input("z01_nome", 40, $Iz01_nome, true, 'text', 3);
		?>
		</td>
	</tr>
	<?
}
else if((isset($q02_inscr)) && ($q02_inscr != '')) {
  ?>
	<tr>
		<td><?=$Lq02_inscr?></td>
		<td>
		<?
		  db_input('q02_inscr', 10, $Iq02_inscr,true,'text',3,"onchange='js_inscr(false)'");
		  db_input("z01_nome", 40, $Iz01_nome, true, 'text', 3);
		?>
		</td>
	</tr>
	<?
}
  if($db_opcao != 1) {?>
    <tr>
      <td nowrap title="<?=@$Tar23_sequencial?>"><?=@$Lar23_sequencial?></td>
      <td>
        <?
          db_input('ar23_sequencial',10,$Iar23_sequencial,true,'text',3," readonly = \"readonly\"");
        ?>
      </td>
      </tr>
		<tr>
	<?
	}
	?>

	<tr>
		<td nowrap title="<?=@$Tar23_data?>"><?=@$Lar23_data?></td>
		<td>
		<?
		  db_inputdata('ar23_data',@$ar23_data_dia,@$ar23_data_mes,@$ar23_data_ano,true,'text',3,"");
		?>
		</td>
	</tr>
	<tr>
		<td nowrap title="<?=@$Tar23_hora?>"><?=@$Lar23_hora?></td>
		<td>
		<?
		  db_input('ar23_hora',10,$Iar23_hora,true,'text',3,"");
		?>
		</td>
	</tr>
	<tr>
		<td nowrap title="<?=@$Tar23_tipo?>"><?=@$Lar23_tipo?></td>
		<td>
		<?php
		  $ar23_tipo = 1;
		  db_input('ar23_tipo', 10, $Iar23_tipo, true, 'hidden', 3);
		  $ar23_tipo_nome = "Manual";
		  db_input('ar23_tipo_nome', 10, 1, true, 'text', 3);
		?>
		</td>
	</tr>
	<tr>
		<td nowrap title="<?=@$Tar23_descricao?>"><?=@$Lar23_descricao?></td>
		<td>
		<?
		  db_input('ar23_descricao',54,$Iar23_descricao,true,'text',$db_opcao,"");
		?>
		</td>
	</tr>
	<tr>
		<td nowrap title="<?=@$Tar23_ocorrencia?>"><?=@$Lar23_ocorrencia?></td>
		<td>
		<?
		  db_textarea('ar23_ocorrencia',10,52,$Iar23_ocorrencia,true,'text',$db_opcao,"");
		?>
		</td>
	</tr>
</table>
</center>

<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"	<?=($db_botao==false?"disabled":"")?>>
<?
  if($db_opcao != 1){ ?>
	<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
<?}
  if (db_permissaomenu(db_getsession("DB_anousu"), db_getsession("DB_modulo"),7929) =='true'){
?>
	<input name="novo" type="button" id="novo" value="Novo" onclick="return js_voltar()">
<?
  }
?>
</form>
<script type="text/javascript">

function js_voltar() {
	window.location = "arr3_histocorrencia001.php";
}

function js_pesquisa(){
	js_OpenJanelaIframe('top.corpo','db_iframe_histocorrencia','func_histocorrencia.php','Pesquisa',true);
}

function js_preenchepesquisa(chave){
	db_iframe_histocorrencia.hide();
  <?
    if($db_opcao!=1){
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    }
  ?>
}

function js_preenchepesquisaCGM(chave, chave2){
	db_iframe_histocorrencia.hide();
  <?
    if($db_opcao!=1){
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?tipoPesquisa=cgm&chavepesquisa='+chave+'&idchave='+chave2";
    }
  ?>
}

function js_preenchepesquisaMatric(chave, chave2){
	db_iframe_histocorrencia.hide();
  <?
    if($db_opcao!=1){
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?tipoPesquisa=matric&chavepesquisa='+chave+'&idchave='+chave2";
    }
  ?>
}

function js_preenchepesquisaInscr(chave, chave2){
	db_iframe_histocorrencia.hide();
  <?
    if($db_opcao!=1){
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?tipoPesquisa=inscr&chavepesquisa='+chave+'&idchave='+chave2";
    }
  ?>
}

</script>