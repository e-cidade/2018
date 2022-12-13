<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
include("dbforms/db_funcoes.php");
include("classes/db_itbi_classe.php");
include("libs/db_app.utils.php");

$situacao = "";
$tipo     = ""; 
$sWhereLogradouro = "";

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

if(!isset($setorCodigo)) {
	$setorCodigo = '';
}

if(!isset($quadra)) {
	$quadra = '';
}
if(!isset($lote)) {
	$lote = '';
}

$clitbi = new cl_itbi;
$clitbi->rotulo->label("it01_guia");
$clitbi->rotulo->label("it01_guia");


$clrotulo = new rotulocampo;
$clrotulo->label("j01_matric");
$clrotulo->label("j34_setor");
$clrotulo->label("j34_quadra");
$clrotulo->label("j34_lote");
$clrotulo->label("it18_nomelograd");

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?
 db_app::load('estilos.css');
 db_app::load('scripts.js, prototype.js, strings.js, DBViewPesquisaSetorQuadraLote.js, dbcomboBox.widget.js');
?>
<script>
function js_matri(mostra){
  var matri=document.form2.j01_matric.value;
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe3','func_matricitbi.php?valida=false&funcao_js=parent.js_mostramatri|0|1','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe3','func_matricitbi.php?pesquisa_chave='+matri+'&funcao_js=parent.js_mostramatri1','Pesquisa',false);
  }
}
function js_mostramatri(chave1,chave2){
  document.form2.j01_matric.value = chave1;
  document.form2.z01_nomematri.value = chave2;
  db_iframe3.hide();
}
function js_mostramatri1(chave,erro){
  document.form2.z01_nomematri.value = chave; 
  if(erro==true){ 
    document.form2.j01_matric.focus(); 
    document.form2.j01_matric.value = ''; 
  }
}
</script>
</head>
<body bgcolor=#CCCCCC>
<form name="form2" method="post" action="" >
<table align="center">
<tr> 
	<td title="<?=$Tit01_guia?>">
		<?=$Lit01_guia?>
	</td>
	<td> 
  <?
		db_input("it01_guia",10,$Iit01_guia,true,"text",4,"","chave_it01_guia");
	?>
	</td>
</tr>

<tr>   
	<td>
	<?
  	db_ancora("<b>Matrícula :</b>",' js_matri(true); ',1);
	?>
	</td>
	<td> 
	<?
		db_input('j01_matric',10,$Ij01_matric,true,'text',1,"onchange='js_matri(false)'");
		db_input('z01_nome',28,0,true,'text',3,"","z01_nomematri");
	?>
	</td>
</tr> 

<tr>
	<td>
		<b>Logradouro :</b>            
	</td>
	<td>
	<?
  	db_input('logradouroid',40,'',true,'hidden',3);
		db_input('it18_nomelograd',40,$Iit18_nomelograd,true,'text',1);
	?>                 
  </td>
</tr>

<tr>
	<td title="Setor/Quadra/Lote">
		<strong>Setor/Quadra/Lote:</strong>
	</td>
	<td> 
	<?
		db_input('j34_setor',10,$Ij34_setor,true,'text',1);
  	db_input('j34_quadra',10,$Ij34_quadra,true,'text',1);
  	db_input('j34_lote',10,$Ij34_lote,true,'text',1);
  ?>
  </td>
</tr>

<tr>
	<td>
		<b>Tipo :</b>
	</td>
	<td>
	<?
		$aTipo = array( 't'=>'Todos',
										'u'=>'Urbano',
										'r'=>'Rural' );
                          
		db_select('tipo',$aTipo,true,2," style='width:295px;'"); 
	?>                    
	</td>
</tr>

<tr>
	<td>
		<b>Periodo de :</b>
	</td>
	<td>
	<?
		db_inputdata('dtIni', '', '', '', true, 'text', 1, '');
	?>                    
	&nbsp;
	<b> a </b>
	&nbsp;
	<?
		db_inputdata('dtFim', '', '', '', true, 'text', 1, '');
	?>                    
	</td>
</tr>

<tr>
	<td>
		<b>Situaçao:</b>
	</td>
	<td>
	<?
		$aSituacao = array( '1'=>'Todos',
												'2'=>'Aberto',
                        '3'=>'Pago',
                        '4'=>'Cancelado');
		db_select('situacao',$aSituacao,true,2," style='width:295px;'"); 
	?>                    
	</td>
</tr>

<tr> 
	<td colspan="2" align="center"> 
		<div id="pesquisa"></div>
	</td>
</tr>	

<tr> 
	<td colspan="2" align="center"> 
		<input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
		<input name="limpar" type="reset" id="limpar" value="Limpar" >
		<input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_itbi.hide();">
	</td>
</tr>
</table>

<table align="center">
<tr>
	<td>
	<?
	if(!isset($pesquisa_chave)){
		if(isset($campos)==false){
			if(file_exists("funcoes/db_func_itbi.php")==true){
				include("funcoes/db_func_itbi.php");
			}else{
				$campos = "itbi.*";
			}
		}
	
		$sAnd   = "";
		$sWhere = "";
	
		if(isset($chave_it01_guia) && (trim($chave_it01_guia)!="") ){
			$sWhere .= " {$sAnd} it01_guia like '$chave_it01_guia%' ";
			$sAnd    = " and ";
		}
	
		if (isset($j01_matric) && trim($j01_matric) != "" ) {
			$sWhere .= " {$sAnd} it06_matric = $j01_matric";
			$sAnd    = " and ";
		}
	
		if ( isset($dtfim) && isset($dtini) ) {
	
			$dtIni = implode("-",array_reverse(explode("/",$dtini)));
			$dtFim = implode("-",array_reverse(explode("/",$dtfim)));
	
			if ( !empty($dtIni) && !empty($dtFim) ) {
				$sWhere        .= " {$sAnd} it01_data between '{$dtIni}' and '{$dtFim}'";
				$sAnd           = " and ";
			} else if ( !empty($dtIni) ) {
				$sWhere        .= " {$sAnd} it01_data >= '{$dtIni}' ";
				$sAnd           = " and ";
			} else if ( !empty($dtFim) ) {
				$sWhere        .= " {$sAnd} it01_data <= '{$dtFim}' ";
				$sAnd           = " and ";
			}
	
		}
	
		if ( isset($it18_nomelograd) && $it18_nomelograd != "" ) {
			$sWhereLogradouro = " where logradouro = '{$it18_nomelograd}' ";
		}
	
		if ( isset($j34_setor) && $j34_setor != "") {
			$sWhere  .= " {$sAnd} j34_setor = '" . str_pad($j34_setor,4,"0",STR_PAD_LEFT)."'";
			$sAnd     = " and ";
		}
	
		if ( isset($j34_quadra) && $j34_quadra != "" ) {
			$sWhere  .= " {$sAnd} j34_quadra = '" . str_pad($j34_quadra,4,"0",STR_PAD_LEFT)."'";
			$sAnd     = " and ";
		}
	
		if ( isset($j34_lote) && $j34_lote != "" ) {
			$sWhere  .= " {$sAnd} j34_lote = '" . str_pad($j34_lote,4,"0",STR_PAD_LEFT)."'";
			$sAnd     = " and ";
		}
		
		if(isset($setorCodigo) || isset($quadra) || isset($lote)) {
			if(isset($setor) and $setor != '') {
				$sWhere .= " {$sAnd} j05_codigoproprio = '{$setorCodigo}' ";
				$sAnd    = " and ";
			}
			if(isset($quadra) and $quadra != '') {
				$sWhere .= " {$sAnd} j06_quadraloc = '{$quadra}' ";
				$sAnd    = " and "; 
			}
			if(isset($lote) and $lote != '') {
				$sWhere .= " {$sAnd} j06_lote = '{$lote}' ";
				$sAnd    = " and ";
			}
		}				
	
		if ( $situacao == "2" ) {
			$sWhere         .= " {$sAnd} arrepaga.k00_numpre is null";
			$sWhere         .= " and it16_guia is null";
			$sAnd            = " and ";
		} else if ( $situacao == "3" ) {
			$sWhere         .= " {$sAnd} arrepaga.k00_numpre is not null";
			$sAnd            = " and ";
		} else if ( $situacao == "4" ) {
			$sWhere         .= " {$sAnd} it16_guia is not null";
			$sAnd            = " and ";
		}
	
		if ( $tipo == "u"  ) {
			$sWhere     .= " {$sAnd} it05_guia is not null ";
			$sAnd        = " and ";
		} else if ( $tipo == "r"  ) {
			$sWhere     .= " {$sAnd} it18_guia is not null ";
			$sAnd        = " and ";
		}
	
	
		$sql = $clitbi->sql_query_itbi("",$campos,"it01_guia",$sWhere,$sWhereLogradouro);
	 
		$repassa = array();
		if(isset($chave_it01_guia)){
			$repassa = array("chave_it01_guia"=>$chave_it01_guia,"chave_it01_guia"=>$chave_it01_guia);
		}
		db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
	}else{
		if($pesquisa_chave!=null && $pesquisa_chave!=""){
			$result = $clitbi->sql_record($clitbi->sql_query($pesquisa_chave));
			if($clitbi->numrows!=0){
				db_fieldsmemory($result,0);
				echo "<script>".$funcao_js."('$it01_guia',false);</script>";
			}else{
				echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
			}
		}else{
			echo "<script>".$funcao_js."('',false);</script>";
		}
	}
	?>	
	</td>
</tr>
</table>
</form>
</body>
</html>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>
<script>
js_tabulacaoforms("form2","chave_it01_guia",true,1,"chave_it01_guia",true);

</script>

<script>
var oPesquisa = new DBViewPesquisaSetorQuadraLote('pesquisa', 'oPesquisa');
    oPesquisa.show();
    oPesquisa.appendForm();
<? 
	echo "oPesquisa.setValues('{$setorCodigo}','{$quadra}','{$lote}');"; 
?>
</script>