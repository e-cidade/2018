<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

//MODULO: fiscal
$clvistorias->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("y77_descricao");
$clrotulo->label("y39_codandam");
$clrotulo->label("y80_codsani");
$clrotulo->label("z01_numcgm");
$clrotulo->label("j01_matric");
$clrotulo->label("q02_inscr");
$clrotulo->label("y10_codigo");
$clrotulo->label("y10_codi");
$clrotulo->label("y10_numero");
$clrotulo->label("y10_compl");
$clrotulo->label("y11_codigo");
$clrotulo->label("y11_codi");
$clrotulo->label("y11_numero");
$clrotulo->label("y11_compl");
$clrotulo->label("j14_nome");
$clrotulo->label("j13_descr");
$clrotulo->label("y100_sequencial");
$clrotulo->label("z01_nome");
$get="";

$url = "";

if(isset($cgm)){
  $url = "cgm=1";
}elseif(isset($matric)){
  $url = "matric=1";
}elseif(isset($inscr)){
  $url = "inscr=1";
}elseif(isset($sani)){
  $url = "sani=1";
}


?>
<form name="form1" method="post" action="">
<?


if ($db_opcao==1){
if (isset ($z01_numcgm) && $z01_numcgm != "") {
	db_input('z01_numcgm', 5, $Iz01_numcgm, true, 'hidden', 1, "");
	include ("classes/db_cgm_classe.php");
	$clcgm = new cl_cgm;

	$get = "&tipo=y101_numcgm&valor=$z01_numcgm";
	if (@ $clvistorias->numrows_incluir == 0) {
		$teste = $clvistcgm->sql_record($clvistcgm->sql_query('', 'y73_codvist', '', 'y73_numcgm = '.$z01_numcgm.' and extract(year from y70_data) = '.db_getsession('DB_anousu')." and y70_instit = ".db_getsession('DB_instit') ));
		if ($clvistcgm->numrows > 0) {
?>
			<script>
    	   		if(!confirm("AVISO:\nCGM ja possui uma vistoria lançada para este ano\n\nDados da Vistoria:\nCódigo:<?=$y71_codvist?>\nTipo:<?=$y70_tipovist?>-<?=$y77_descricao?>\nDepartamento:<?=$y70_coddepto?>\n\nDeseja cadastrar uma nova Vistoria?")){
        			top.corpo.location = 'fis1_vistorias001.php?cgm=1';
       			}
      		</script>
<?


		}
	}
	$result = $clcgm->sql_record($clcgm->sql_query_ender($z01_numcgm));
	if ($clcgm->numrows > 0) {
		db_fieldsmemory($result, 0);
		$result = $clcgm->sql_record($clcgm->sql_query_ender($z01_numcgm));
		if ($clcgm->numrows > 0) {
			db_fieldsmemory($result, 0);
			$rua = $j14_codigo;
			$bairro = $j13_codi;
			$numero = $z01_numero;
			$compl = $z01_compl;
		}
	}
	if ($db_opcao==1){
	$dados = "<a style='text-decoration:none;color:#6699cc;background-color:yellow' onMouseOver='this.style.color=\"blue\"' onMouseOut='this.style.color=\"#6699cc\"' onClick=\"js_abre('prot3_conscgm002.php?fechar=func_nome&numcgm=$z01_numcgm');return false\" href=''>CGM: ".$z01_numcgm." &nbsp;|&nbsp;".@ $z01_nome."</a>";
	}
}
elseif (isset ($j01_matric) && $j01_matric != "") {
	db_input('j01_matric', 5, $Ij01_matric, true, 'hidden', 1, "");
	include ("classes/db_iptubase_classe.php");
	$cliptubase = new cl_iptubase;
	$get = "&tipo=y102_matric&valor=$j01_matric";
	if (@ $clvistorias->numrows_incluir == 0) {
		$teste = $clvistmatric->sql_record($clvistmatric->sql_query('', 'y72_codvist', '', 'y72_matric = '.$j01_matric.' and extract(year from y70_data) = '.db_getsession('DB_anousu')." and y70_instit = ".db_getsession('DB_instit') ));
		if ($clvistmatric->numrows > 0) {
?>
			<script>
      			if(!confirm("AVISO:\nMatricula ja possui uma vistoria lançada para este ano\n\nDados da Vistoria:\nCódigo:<?=$y71_codvist?>\nTipo:<?=$y70_tipovist?>-<?=$y77_descricao?>\nDepartamento:<?=$y70_coddepto?>\n\nDeseja cadastrar uma nova Vistoria?")){
       				top.corpo.location = 'fis1_vistorias001.php?matric=1';
      			}
     		</script>
<?


		}
	}
	$result = $cliptubase->sql_record($cliptubase->sql_query($j01_matric));
	if ($cliptubase->numrows > 0) {
		db_fieldsmemory($result, 0);
		$result = $cliptubase->sql_record($cliptubase->proprietario_query($j01_matric));
		if ($cliptubase->numrows > 0) {
			db_fieldsmemory($result, 0);
			$rua = $j14_codigo;
			$bairro = $j34_bairro;
			$numero = $j39_numero;
			$compl = $j39_compl;

		}
		include ("classes/db_cgm_classe.php");
		$clcgm = new cl_cgm;
		$result = $clcgm->sql_record($clcgm->sql_query($j01_numcgm));
		if ($clcgm->numrows > 0) {
			db_fieldsmemory($result, 0);
		}
	}
	if ($db_opcao==1){
	$dados = "<a style='text-decoration:none;color:#6699cc;background-color:yellow' onMouseOver='this.style.color=\"blue\"' onMouseOut='this.style.color=\"#6699cc\"' onClick=\"js_abre('cad3_conscadastro_002.php?cod_matricula=$j01_matric');return false\" href=''>matrícula: ".$j01_matric." &nbsp;|&nbsp;".@ $z01_nome."</a>";
	}
}
elseif (isset ($q02_inscr) && $q02_inscr != "") {
	db_input('q02_inscr', 5, $Iq02_inscr, true, 'hidden', 1, "");
	include ("classes/db_issbase_classe.php");
	$clissbase = new cl_issbase;
	$get = "&tipo=y103_inscr&valor=$q02_inscr";
	if (@ $clvistorias->numrows_incluir == 0) {
		$teste = $clvistinscr->sql_record($clvistinscr->sql_query('', 'y71_codvist,y70_tipovist,y77_descricao,y70_coddepto', '', 'y71_inscr = '.$q02_inscr.' and extract(year from y70_data) = '.db_getsession('DB_anousu')." and y70_instit = ".db_getsession('DB_instit') ));
		if ($clvistinscr->numrows > 0) {
			db_fieldsmemory($teste, 0);
?><script>
       if(!confirm("AVISO:\nInscrição ja possui uma vistoria lançada para este ano\n\nDados da Vistoria:\nCódigo:<?=$y71_codvist?>\nTipo:<?=$y70_tipovist?>-<?=$y77_descricao?>\nDepartamento:<?=$y70_coddepto?>\n\nDeseja cadastrar uma nova Vistoria?")){
        top.corpo.location = 'fis1_vistorias001.php?inscr=1';
       }
      </script>
    <?

		}
	}
	$result = $clissbase->sql_record($clissbase->sql_query($q02_inscr));
	if ($clissbase->numrows > 0) {
		db_fieldsmemory($result, 0);
		$result = $clissbase->sql_record($clissbase->empresa_query($q02_inscr));
		if ($clissbase->numrows > 0) {
			db_fieldsmemory($result, 0);
			$rua = $q02_lograd;
			$bairro = $q02_bairro;
			$numero = $z01_numero;
			$compl = $z01_compl;

		}
		include ("classes/db_cgm_classe.php");
		$clcgm = new cl_cgm;
		$result = $clcgm->sql_record($clcgm->sql_query($q02_numcgm));
		if ($clcgm->numrows > 0) {
			db_fieldsmemory($result, 0);
		}
		include ("classes/db_issquant_classe.php");
		$clissquant = new cl_issquant;
		$result = $clissquant->sql_record($clissquant->sql_query_file(db_getsession("DB_anousu"), $q02_inscr));
		if ($clissquant->numrows > 0) {
			db_fieldsmemory($result, 0);
			$area = " Área: $q30_quant";
		}
	}
	if ($db_opcao==1){
	$dados = "<a  onClick=\"js_abre('iss3_consinscr003.php?numeroDaInscricao=$q02_inscr');return false\" style='text-decoration:none;color:#6699cc;background-color:yellow' onMouseOver='this.style.color=\"blue\"' onMouseOut='this.style.color=\"#6699cc\"' href=''>inscrição: ".$q02_inscr." &nbsp;|&nbsp;".@ $z01_nome." &nbsp;|&nbsp;".@ $area." </a>";
	}
	echo "<script>parent.document.formaba.calculo.disabled = true</script>";
}
elseif (isset ($y80_codsani) && $y80_codsani != "") {
	db_input('y80_codsani', 5, $Iy80_codsani, true, 'hidden', 1, "");
	include ("classes/db_sanitario_classe.php");
	$clsanitario = new cl_sanitario;
	$get = "&tipo=y104_codsani&valor=$y80_codsani";
	if (@ $clvistorias->numrows_incluir == 0) {
		$teste = $clvistsanitario->sql_record($clvistsanitario->sql_query('', 'y74_codvist', '', 'y74_codsani = '.$y80_codsani.' and extract(year from y70_data) = '.db_getsession('DB_anousu')." and y70_instit = ".db_getsession('DB_instit') ));
		if ($clvistsanitario->numrows > 0) {
?><script>
        if(!confirm("AVISO:\nSanitário ja possui uma vistoria lançada para este ano\n\nDados da Vistoria:\nCódigo:<?=$y71_codvist?>\nTipo:<?=$y70_tipovist?>-<?=$y77_descricao?>\nDepartamento:<?=$y70_coddepto?>\n\nDeseja cadastrar uma nova Vistoria?")){
         top.corpo.location = 'fis1_vistorias001.php?sani=1';
        }
       </script>
     <?



		}
	}
	$result = $clsanitario->sql_record($clsanitario->sql_query($y80_codsani));
	if ($clsanitario->numrows > 0) {
		$result = $clsanitario->sql_record($clsanitario->sql_query($y80_codsani));
		if ($clsanitario->numrows > 0) {
			db_fieldsmemory($result, 0);
			$rua = $y80_codrua;
			$bairro = $y80_codbairro;
			$numero = $y80_numero;
			$compl = $y80_compl;
		}
		db_fieldsmemory($result, 0);
		include ("classes/db_cgm_classe.php");
		$clcgm = new cl_cgm;
		$result = $clcgm->sql_record($clcgm->sql_query($y80_numcgm));
		if ($clcgm->numrows > 0) {
			db_fieldsmemory($result, 0);
		}
	}
	if ($db_opcao==1){
	$dados = "<a style='text-decoration:none;color:#6699cc;background-color:yellow' onMouseOver='this.style.color=\"blue\"' onMouseOut='this.style.color=\"#6699cc\"' onClick=\"js_abre('fis3_consultasani002.php?y80_codsani=$y80_codsani');return false;\" href=''>sanitário: ".$y80_codsani." &nbsp;|&nbsp;".@ $z01_nome."</a>";
	}
	echo "<script>parent.document.formaba.calculo.disabled = true</script>";
}
elseif (isset ($y70_codvist) && $y70_codvist != "") {

	//  die("db_opcao: $db_opcao\n");
	$result = $clvistorias->sql_record($clvistorias->sql_querycgm($y70_codvist,"*",null," y70_instit = ".db_getsession('DB_instit') ));
	db_fieldsmemory($result, 0);
	if ($clvistorias->numrows > 0) {
		db_fieldsmemory($result, 0);
		include ("classes/db_cgm_classe.php");
		$clcgm = new cl_cgm;
		$result = $clcgm->sql_record($clcgm->sql_query($z01_numcgm));
		if ($clcgm->numrows > 0) {
			db_fieldsmemory($result, 0);
			if ($db_opcao==1){
			$dados = "<a style='text-decoration:none;color:#6699cc;background-color:yellow' onMouseOver='this.style.color=\"blue\"' onMouseOut='this.style.color=\"#6699cc\"' onClick=\"js_abre('prot3_conscgm002.php?fechar=func_nome&numcgm=$z01_numcgm');return false\" href=''>CGM: ".$z01_numcgm." &nbsp;|&nbsp;".@ $z01_nome."</a>";
			}
		}
	}
}
}
if ($db_opcao!=1){
	if (isset($identificacao)&&$identificacao=="CGM"){
		$z01_numcgm =$codigo;
		$get = "&tipo=y101_numcgm&valor=$z01_numcgm";
		$dados = "<a style='text-decoration:none;color:#6699cc;background-color:yellow' onMouseOver='this.style.color=\"blue\"' onMouseOut='this.style.color=\"#6699cc\"' onClick=\"js_abre('prot3_conscgm002.php?fechar=func_nome&numcgm=$z01_numcgm');return false\" href=''>CGM: ".$z01_numcgm." &nbsp;|&nbsp;".@ $z01_nome."</a>";
	}else if (isset($identificacao)&&$identificacao=="Inscrição"){
		$q02_inscr =$codigo;
		$get = "&tipo=y103_inscr&valor=$q02_inscr";
		$dados = "<a  onClick=\"js_abre('iss3_consinscr003.php?numeroDaInscricao=$q02_inscr');return false\" style='text-decoration:none;color:#6699cc;background-color:yellow' onMouseOver='this.style.color=\"blue\"' onMouseOut='this.style.color=\"#6699cc\"' href=''>inscrição: ".$q02_inscr." &nbsp;|&nbsp;".@ $z01_nome." &nbsp;|&nbsp;".@ $area." </a>";
	}else if (isset($identificacao)&&$identificacao=="Matrícula"){
		$j01_matric =$codigo;
		$get = "&tipo=y102_matric&valor=$j01_matric";
		$dados = "<a style='text-decoration:none;color:#6699cc;background-color:yellow' onMouseOver='this.style.color=\"blue\"' onMouseOut='this.style.color=\"#6699cc\"' onClick=\"js_abre('cad3_conscadastro_002.php?cod_matricula=$j01_matric');return false\" href=''>matrícula: ".$j01_matric." &nbsp;|&nbsp;".@ $z01_nome."</a>";
	}else if (isset($identificacao)&&$identificacao=="Sanitário"){
		$y80_codsani=$codigo;
		$get = "&tipo=y104_codsani&valor=$y80_codsani";
		$dados = "<a style='text-decoration:none;color:#6699cc;background-color:yellow' onMouseOver='this.style.color=\"blue\"' onMouseOut='this.style.color=\"#6699cc\"' onClick=\"js_abre('fis3_consultasani002.php?y80_codsani=$y80_codsani');return false;\" href=''>sanitário: ".$y80_codsani." &nbsp;|&nbsp;".@ $z01_nome."</a>";
	}
}
?>
<center>
<br>
<table border="0" width="790">
  <?if(isset($y70_ativo)&&($y70_ativo=="f")){?>
  <tr>
    <td colspan=2 nowrap>
       <b><h2>Vistoria Anulada</h2></b>
    </td>
  <tr>
  <?}?>
  <tr>
    <td colspan=2 nowrap>
  <fieldset>
      <legend align="center"><strong>Dados da Vistoria</strong></legend>
   <table border="0" width="100%">
  <tr>
    <td nowrap title="<?=@$Ty70_codvist?>"  width="210" >
       <?=@$Ly70_codvist?>
    </td>
    <td>
<?

db_input('y70_codvist', 10, $Iy70_codvist, true, 'text', 3, "");
db_input('y39_codandam', 10, $Iy39_codandam, true, 'hidden', $db_opcao, "");
echo "&nbsp;&nbsp;&nbsp;&nbsp;<strong>&nbsp;".@ $dados."&nbsp;</strong>";
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty70_numbloco?>">
       <?=@$Ly70_numbloco?>
    </td>
    <td>
<?



db_input('y70_numbloco', 10, $Iy70_numbloco, true, 'text', $db_opcao, "");
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
?>
       <?=@$Ly70_data?>
<?

if ($db_opcao == 1) {
	$y70_data_dia = date("d", db_getsession("DB_datausu"));
	$y70_data_mes = date("m", db_getsession("DB_datausu"));
	$y70_data_ano = date("Y", db_getsession("DB_datausu"));
}
db_inputdata('y70_data', @ $y70_data_dia, @ $y70_data_mes, @ $y70_data_ano, true, 'text', $db_opcao, "")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty70_obs?>">
       <?=@$Ly70_obs?>
    </td>
    <td>
<?

 db_textarea('y70_obs', 2, 60, $Iy70_obs, true, 'text', $db_opcao, "")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty70_contato?>">
       <?=@$Ly70_contato?>
    </td>
    <td>
<?

 db_input('y70_contato', 35, $Iy70_contato, true, 'text', $db_opcao, "")
?>
       <?=@$Ly70_hora?>
<?

 db_input('y70_ultandam', 5, $Iy70_ultandam, true, 'hidden', 1, "");
db_input('y70_id_usuario', 5, $Iy70_id_usuario, true, 'hidden', 1, "");
echo "<script>document.form1.y70_id_usuario.value = '".db_getsession("DB_id_usuario")."'</script>";
db_input('y70_hora', 5, $Iy70_hora, true, 'text', $db_opcao, "");
if ($db_opcao == 1) {
	echo "<script>document.form1.y70_hora.value = '".db_hora()."'</script>";
}
?>
    </td>
  </tr>
  <?
   if ($db_opcao==1){
	  $y10_codigo=$rua;
	  $y10_codi=$bairro;
	  $y10_numero=$numero;
	  $y10_compl=$compl;
	  $y11_codigo=$rua;
	  $y11_codi=$bairro;
	  $y11_numero=$numero;
	  $y11_compl=$compl;
	  if(@$rua!=""){
		  $result_descr = db_query("select j14_nome from ruas where j14_codigo = $rua");
		  if (pg_numrows($result_descr)>0){
		  	db_fieldsmemory($result_descr,0);
		  	$j14_nome_exec = $j14_nome;
		  }
	  }
	  if(@$bairro!=""){
		  $result_descr = db_query("select j13_descr from bairro where j13_codi = $bairro");
		  if (pg_numrows($result_descr)>0){
		  	db_fieldsmemory($result_descr,0);
		  	$j13_descr_exec = $j13_descr;
		  }
	  }
   }
  ?>
  <tr>
    <td nowrap title="<?=@$Ty70_tipovist?>" >
       <?
db_ancora(@ $Ly70_tipovist, "js_pesquisay70_tipovist(true);", $db_opcao);
?>
    </td>
    <td>
<?

db_input('y70_tipovist', 10, $Iy70_tipovist, true, 'text', $db_opcao, " onchange='js_pesquisay70_tipovist(false);'");
db_input('y70_coddepto', 10, $Iy70_coddepto, true, 'hidden', $db_opcao, "");
db_input('y77_descricao', 50, $Iy77_descricao, true, 'text', 3, '');
if ($db_opcao == 1) {
	$result = $cltipovistorias->sql_record($cltipovistorias->sql_query("", "*", "", " y77_coddepto = ".db_getsession("DB_coddepto")." and y77_instit = ".db_getsession('DB_instit') ));
	if ($cltipovistorias->numrows == 1) {
		db_fieldsmemory($result, 0);
		echo "<script>document.form1.y70_tipovist.value='$y77_codtipo'</script>";
		echo "<script>document.form1.y77_descricao.value='$y77_descricao'</script>";
	}
}
?>
    </td>
  </tr>
	<tr>
    <td nowrap title="<?=@$Ty100_sequencial?>">
       <?
       db_ancora(@$Ly100_sequencial,"js_pesquisaprocfiscal(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('procfiscal',10,$Iy100_sequencial,true,'text',$db_opcao," onchange='js_pesquisaprocfiscal(false);'")
?>
       <?
db_input('nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table >
  </fieldset>
  </td>
  </tr>

  <tr>
    <td colspan="2" align="center">
      <fieldset>
      <legend align="center"><strong>Endereço registrado</strong></legend>
      <table width="100%" border="0">
	<tr>
	  <td nowrap title="<?=@$Ty10_codigo?>" width="210">
	     <?



db_ancora(@ $Ly10_codigo, "js_ruas1(true);", 3);
?>
	  </td>
	  <td>
      <?
db_input('y10_codigo', 10, $Iy10_codigo, true, 'text', 3, " onChange='js_ruas1(false)'");
db_input('j14_nome', 50, $Ij14_nome, true, 'text', 3, "");
?>
	  </td>
	</tr>
	<tr>
	  <td nowrap title="<?=@$Ty10_numero?>">
	     <?=@$Ly10_numero?>
	  </td>
	  <td>
      <?



db_input('y10_numero', 10, $Iy10_numero, true, 'text', 3, "")
?>
	     <?=@$Ly10_compl?>
      <?

 db_input('y10_compl', 37, $Iy10_compl, true, 'text', 3, "")
?>
	  </td>
	</tr>
	<tr>
	  <td nowrap title="<?=@$Ty10_codi?>">
	    <?

 db_ancora(@ $Ly10_codi, "js_bairro1(true);", 3);
?>
	  </td>
	  <td nowrap>
	    <?



db_input('y10_codi', 10, $Iy10_codi, true, 'text', 3, " onChange='js_bairro1(false)'");
db_input('j13_descr', 50, $Ij13_descr, true, 'text', 3);
?>
	  </td>
	</tr>
      </table>
      </fieldset>
    </td>
    </tr>
    <tr>
      <td colspan="2" align="center">
      <fieldset>
      <legend align="center"><strong>Endereço localizado</strong></legend>
      <table width="100%" border="0">
	<tr>
	  <td nowrap title="<?=@$Ty11_codigo?>" width="210">
	     <?
db_ancora(@ $Ly11_codigo, "js_ruas(true);", $db_opcao);
?>
	  </td>
	  <td nowrap>
	    <?
db_input('y11_codigo', 10, $Iy11_codigo, true, 'text', $db_opcao, " onChange='js_ruas(false)'");
db_input('j14_nome', 50, $Ij14_nome, true, 'text', 3, "", "j14_nome_exec");
?>
	  </td>
	</tr>
	<tr>
	  <td nowrap title="<?=@$Ty11_numero?>">
	     <?=@$Ly11_numero?>
	  </td>
	  <td>
      <?



db_input('y11_numero', 10, $Iy11_numero, true, 'text', $db_opcao, "")
?>
	     <?=@$Ly11_compl?>
      <?

 db_input('y11_compl', 37, $Iy11_compl, true, 'text', $db_opcao, "")
?>
	  </td>
	</tr>
	<tr>
	  <td nowrap title="<?=@$Ty11_codi?>">
	    <?

 db_ancora(@ $Ly11_codi, "js_bairro(true);", $db_opcao);
?>
	  </td>
	  <td nowrap>
	    <?



db_input('y11_codi', 10, $Iy11_codi, true, 'text', $db_opcao, " onChange='js_bairro(false)'");
db_input('j13_descr', 50, $Ij13_descr, true, 'text', 3, "", "j13_descr_exec");
?>
	  </td>
	</tr>
      </table>
    </fieldset>
    </td>
  </tr>

  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" <?=($db_opcao==22||$db_opcao==2||$db_opcao==33||$db_opcao==3)?"":"onblur='js_setatabulacao();'"?>>
<?



if ($db_opcao == 22 || $db_opcao == 2 || $db_opcao == 33 || $db_opcao == 3) {
?>
<input name="novo" type="button" id="novo" value="Incluir Novo" onclick="parent.location.href='fis1_vistorias005.php?<?=$url?>';" onblur="js_setatabulacao();">
<?



}
?>
</form>
<script>
function js_setatabulacao(){
  js_tabulacaoforms("form1","y70_numbloco",true,1,"y70_numbloco",true);
}
function js_bairro(mostra){
  if(mostra == true){
    js_OpenJanelaIframe('','db_iframe_bairros','func_bairro.php?rural=1&funcao_js=parent.js_preenchebairro|j13_codi|j13_descr','pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_bairros','func_bairro.php?rural=1&funcao_js=parent.js_preenchebairro1&pesquisa_chave='+document.form1.y11_codi.value,'pesquisa',false);
  }
}
function js_preenchebairro(chave,chave1){
  document.form1.y11_codi.value = chave;
  document.form1.j13_descr_exec.value = chave1;
  db_iframe_bairros.hide();
}
function js_preenchebairro1(chave,erro){
  document.form1.j13_descr_exec.value = chave;
  if(erro == true){
    document.form1.y11_codi.focus();
    document.form1.y11_codi.value='';
  }
  db_iframe_bairros.hide();
}
function js_bairro1(mostra){
  if(mostra == true){
    js_OpenJanelaIframe('','db_iframe_bairros','func_bairro.php?rural=1&funcao_js=parent.js_preenchebairro2|j13_codi|j13_descr','pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_bairros','func_bairro.php?rural=1&funcao_js=parent.js_preenchebairro22&pesquisa_chave='+document.form1.y10_codi.value,'pesquisa',false);
  }
}
function js_preenchebairro2(chave,chave1){
  document.form1.y10_codi.value = chave;
  document.form1.j13_descr.value = chave1;
  db_iframe_bairros.hide();
}
function js_preenchebairro22(chave,erro){
  document.form1.j13_descr.value = chave;
  if(erro == true){
    document.form1.y10_codi.focus();
    document.form1.y10_codi.value='';
  }
  db_iframe_bairros.hide();
}
function js_ruas(mostra){
  if(mostra == true){
    js_OpenJanelaIframe('','db_iframe_ruas','func_ruas.php?rural=1&funcao_js=parent.js_preencheruas|j14_codigo|j14_nome','Pesquisa',true);
  }else{
    document.form1.j14_nome_exec.value = '';
    document.form1.y11_numero.value = '';
    document.form1.y11_compl.value = '';
    document.form1.y11_codi.value = '';
    document.form1.j13_descr_exec.value = '';
    document.form1.y11_numero.focus();
    js_OpenJanelaIframe('','db_iframe_ruas','func_ruas.php?rural=1&funcao_js=parent.js_preencheruas1&pesquisa_chave='+document.form1.y11_codigo.value+'','Pesquisa',false);
  }
}
function js_preencheruas(chave,chave1){
  document.form1.y11_codigo.value = chave;
  document.form1.j14_nome_exec.value = chave1;
  db_iframe_ruas.hide();
}
function js_preencheruas1(chave,erro){
  document.form1.j14_nome_exec.value = chave;
  if(erro == true){
    document.form1.y11_codigo.focus();
    document.form1.y11_codigo.value='';
  }
  db_iframe_ruas.hide();
}
function js_ruas1(mostra){
  if(mostra == true){
    js_OpenJanelaIframe('','db_iframe_ruas','func_ruas.php?rural=1&funcao_js=parent.js_preencheender|j14_codigo|j14_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_ruas','func_ruas.php?rural=1&funcao_js=parent.js_preencheender1&pesquisa_chave='+document.form1.y10_codigo.value+'','Pesquisa',false);
  }
}
function js_preencheender(chave,chave1){
  document.form1.y10_codigo.value = chave;
  document.form1.j14_nome.value = chave1;
  db_iframe_ruas.hide();
}
function js_preencheender1(chave,erro){
  document.form1.j14_nome.value = chave;
  if(erro==true){
    document.form1.y10_codigo.focus();
    document.form1.y10_codigo.value = '';
  }
}
function js_pesquisay70_tipovist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_tipovistorias','func_tipovistoriasdepto.php?funcao_js=parent.js_mostratipovistorias1|y77_codtipo|y77_descricao','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_tipovistorias','func_tipovistoriasdepto.php?pesquisa_chave='+document.form1.y70_tipovist.value+'&funcao_js=parent.js_mostratipovistorias','Pesquisa',false);
  }
}
function js_mostratipovistorias(chave,erro){
  document.form1.y77_descricao.value = chave;
  if(erro==true){
    document.form1.y70_tipovist.focus();
    document.form1.y70_tipovist.value = '';
  }
}
function js_mostratipovistorias1(chave1,chave2){
  document.form1.y70_tipovist.value = chave1;
  document.form1.y77_descricao.value = chave2;
  db_iframe_tipovistorias.hide();
}
function js_pesquisa(){

  js_OpenJanelaIframe('','db_iframe_vistorias','func_vistorias.php?funcao_js=parent.js_preenchepesquisa|y70_codvist','Pesquisa',true);
}

function js_pesquisaprocfiscal(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_procfiscal','func_procfiscal_alt.php?funcao_js=parent.js_mostraprocfiscal1|y100_sequencial|z01_nome|db_depart_protocolo|db_descr_depart|db_depart_atual<?=$get?>','Pesquisa',true);
  }else{
     if(document.form1.procfiscal.value != ''){
        js_OpenJanelaIframe('','db_iframe_procfiscal','func_procfiscal_alt.php?pesquisa_chave='+document.form1.procfiscal.value+'&funcao_js=parent.js_mostraprocfiscal','Pesquisa',false,'0','1','775','390');
     }else{
		 	 document.form1.nome.value = '';
		 }
  }
}
function js_mostraprocfiscal(chave,erro,dep_prot,depart,dep_atual){

 if (dep_prot == dep_atual) {
  	document.form1.nome.value = chave;
  if(erro==true){
    document.form1.procfiscal.focus();
    document.form1.procfiscal.value = '';
  }
  }
  else {
    alert('Processo de protocolo não está neste departamento atualmente! \nDepartamento atual do processo:'+depart);
		document.form1.procfiscal.focus();
    document.form1.procfiscal.value = '';
		document.form1.nome.value = '';
		return false;
  }


}
function js_mostraprocfiscal1(chave1,chave2,dep_prot,depart,dep_atual){
  if (dep_prot == dep_atual) {
  	document.form1.procfiscal.value = chave1;
  	document.form1.nome.value = chave2;
  	db_iframe_procfiscal.hide();
  }
  else {
    alert('Processo de protocolo não está neste departamento atualmente! \nDepartamento atual do processo:'+depart);
		return false;
  }



}



function js_preenchepesquisa(chave){
  db_iframe_vistorias.hide();
  <?



if ($db_opcao == 2 || $db_opcao == 22) {
	echo " location.href = 'fis1_vistorias002.php?abas=1&chavepesquisa='+chave;";
}
elseif ($db_opcao == 33 || $db_opcao == 3) {
	echo " location.href = 'fis1_vistorias003.php?abas=1&chavepesquisa='+chave;";
}
?>
}
function js_abre(pagina){
  js_OpenJanelaIframe('','db_iframe_consulta',pagina,'Pesquisa',true,0);
}
</script>
<?



if ($db_opcao != 1) {
	if (isset ($y10_codigo)) {
		echo "<script>js_OpenJanelaIframe('','db_iframe_bairros','func_bairro.php?rural=1&funcao_js=parent.js_preenchebairro1&pesquisa_chave=$y11_codi','pesquisa',false);</script>";
		echo "<script>js_OpenJanelaIframe('','db_iframe_ruas','func_ruas.php?rural=1&funcao_js=parent.js_preencheruas1&pesquisa_chave=$y11_codigo','Pesquisa',false);</script>";
		echo "<script>document.form1.y10_codigo.value = '$y10_codigo';js_ruas1(false);</script>";
		echo "<script>document.form1.y10_codi.value='$y10_codi';js_bairro1(false)</script>";
	}
}
if ($db_opcao == 1) {
	if (isset ($j14_codigo) && $j14_codigo != "") {
		echo "<script>js_OpenJanelaIframe('','db_iframe_ruas','func_ruas.php?rural=1&funcao_js=parent.js_preencheruas1&pesquisa_chave=$j14_codigo','Pesquisa',false);</script>";
		echo "<script>document.form1.y11_codigo.value = '$j14_codigo';</script>";
		echo "<script>document.form1.y10_codigo.value = '$j14_codigo';js_ruas1(false);</script>";
		echo "<script>document.form1.y10_codigo.readOnly = true</script>";
	} else {
		if (isset ($q02_inscr) && $q02_inscr != "") {
			include ("classes/db_issruas_classe.php");
			$clissruas = new cl_issruas;

			$result = $clissruas->sql_record($clissruas->sql_query($q02_inscr));
			if ($clissruas->numrows == 0) {
				echo "<script>alert('Atualize dados cadastrais da inscrição...\"Logradouro não informado\".')</script>";
				echo "<script>
									      for(i=0;i<document.form1.length;i++){
										document.form1.elements[i].disabled = true
									      }
									      </script>";
			} else {
				db_fieldsmemory($result, 0);
				echo "<script>js_OpenJanelaIframe('','db_iframe_ruas','func_ruas.php?rural=1&funcao_js=parent.js_preencheruas1&pesquisa_chave=$j14_codigo','Pesquisa',false);</script>";
				echo "<script>document.form1.y11_codigo.value = '$j14_codigo';</script>";
				echo "<script>document.form1.y10_codigo.value = '$j14_codigo';js_ruas1(false);</script>";
				echo "<script>document.form1.y10_codigo.readOnly = true</script>";


			}
		}
		elseif (isset($z01_numcgm) && $z01_numcgm !="") {
			echo "<script>
							alert('Atualize dados cadastrais do CGM...\"Logradouro não informado\".')
            </script>";


			echo "<script>
							    for(i=0;i<document.form1.length;i++){
							      document.form1.elements[i].disabled = true
							    }
							    </script>";
		}
	}
	if (isset ($j13_codi) && $j13_codi != "") {
		echo "<script>js_OpenJanelaIframe('','db_iframe_bairros','func_bairro.php?rural=1&funcao_js=parent.js_preenchebairro1&pesquisa_chave=$j13_codi','pesquisa',false);</script>";
		echo "<script>document.form1.y11_codi.value='$j13_codi';</script>";
		echo "<script>document.form1.y10_codi.value='$j13_codi';js_bairro1(false)</script>";
		echo "<script>document.form1.y10_codi.readOnly = true</script>";
	}else{
	  if (isset ($q02_inscr) && $q02_inscr != "") {
	    $sqlbairro = "select *from issbairro inner join bairro on q13_bairro = j13_codi where q13_inscr = $q02_inscr";
	    $resultbairro = db_query($sqlbairro);
	    $linhasbairro = pg_num_rows($resultbairro);
	    if($linhasbairro == 0){
	      echo "<script>alert('Atualize dados cadastrais da inscrição...\"Bairro não informado\".')</script>";
				echo "<script>
									      for(i=0;i<document.form1.length;i++){
										document.form1.elements[i].disabled = true
									      }
									      </script>";
	    }else{
	      db_fieldsmemory($resultbairro, 0);
	      echo "<script>js_OpenJanelaIframe('','db_iframe_bairros','func_bairro.php?rural=1&funcao_js=parent.js_preenchebairro1&pesquisa_chave=$j13_codi','pesquisa',false);</script>";
				echo "<script>document.form1.y11_codi.value='$j13_codi';</script>";
				echo "<script>document.form1.y10_codi.value='$j13_codi';js_bairro1(false)</script>";
				echo "<script>document.form1.y10_codi.readOnly = true</script>";
	    }
	  }elseif (isset($z01_numcgm) && $z01_numcgm !="") {
			echo "<script>
							alert('Atualize dados cadastrais do CGM...\"Bairro não informado\".')
            </script>";


			echo "<script>
							    for(i=0;i<document.form1.length;i++){
							      document.form1.elements[i].disabled = true
							    }
							    </script>";
		}

	}

	$iNumero = "";
	if(isset($_GET['cgm'])){
		$iNumero = $z01_numero;
	}
  if(isset($_GET['inscr'])){
    $iNumero = $q02_numero;
  }


	if (isset ($z01_numero) && $z01_numero != "" && !isset($_GET['sani'])) {
		echo "<script>document.form1.y10_numero.value='$iNumero';</script>";
		echo "<script>document.form1.y11_numero.value='$iNumero';</script>";
		echo "<script>document.form1.y10_numero.readOnly = true</script>";
	}
	if (isset ($z01_compl) && $z01_compl != "") {
		echo "<script>document.form1.y10_compl.value='$z01_compl';</script>";
		echo "<script>document.form1.y11_compl.value='$z01_compl';</script>";
		echo "<script>document.form1.y10_compl.readOnly = true</script>";
	}
}
?>