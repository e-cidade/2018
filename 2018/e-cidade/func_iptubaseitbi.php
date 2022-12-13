<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("classes/db_iptubase_classe.php");
include("classes/db_itbi_classe.php");
include("libs/db_app.utils.php");
db_postmemory($HTTP_POST_VARS);

if(!isset($setorCodigo)) {
	$setorCodigo = '';
}

if(!isset($quadra)) {
	$quadra = '';
}
if(!isset($lote)) {
	$lote = '';
}

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cliptubase = new cl_iptubase;
$clitbi  = new cl_itbi;
$cliptubase->rotulo->label("j01_matric");
$clrotulo = new rotulocampo;
$clrotulo->label("j14_codigo");
$clrotulo->label("j14_nome");
$clrotulo->label("z01_nome");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?
 db_app::load('estilos.css');
 db_app::load('scripts.js, prototype.js, strings.js, DBViewPesquisaSetorQuadraLote.js, dbcomboBox.widget.js');
?>
</head>
<body bgcolor=#CCCCCC>
<form name="form2" id="form2" method="post" action="" >
<table align="center">
<tr>
	<td title="<?=$Tj01_matric?>">
		<?=$Lj01_matric?>
	</td>
	<td>
		<?
			db_input("j01_matric",10,$Ij01_matric,true,"text",4,"","chave_j01_matric");
		?>
	</td>
</tr>

<tr>
	<td title="<?=$Tj14_codigo?>">
	<?
		db_ancora($Lj14_codigo,' js_mostraruas(true); ',2)
	?> 
	</td>
	<td> 
	<?
		db_input("j14_codigo", 10, $Ij14_codigo, true, 'text', 4, " onchange='js_mostraruas(false);'");
		
		db_input("j14_nome"  , 40, $Ij14_nome  , true, 'text', 3);
	?>
	</td>
</tr>

<tr> 
	<td title="<?=$Tz01_nome?>">
		<?=$Lz01_nome?>
	</td>
	<td> 
	<?
		db_input("z01_nome",40,$Iz01_nome,true,'text',4)
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
	</td>
</tr>
	</td>
</tr>	
</table>

<table  align="center">
<tr> 
	<td>

<?
	if(!isset($pesquisa_chave)){
		if(isset($campos)==false){
			$campos = "iptubase.*";
		}
		$sql = "select distinct j01_matric,
													  (select rvnome as z01_nome from fc_busca_envolvidos(false, (select fc_regrasconfig from fc_regrasconfig(1)), 'M', iptubase.j01_matric) limit 1),
													  case when j39_numero is null then 'Terr' else 'Pred' end as Tipo,  
													  case when ruase.j14_codigo is null then ruas.j14_nome else ruase.j14_nome end as j14_nome, 
													  case when j39_numero is null then 0 else j39_numero end as j39_numero,
													  j39_compl,
													  j34_setor,
													  j34_quadra,
													  j34_lote, 
													  j05_codigoproprio, 
													  j06_quadraloc, 
													  j06_lote 
						  from iptubase 
						 inner join lote on j34_idbql 				              = j01_idbql 
							left outer join testpri on j49_idbql              = j01_idbql
							left outer join ruas on j14_codigo                = j49_codigo
						 inner join cgm on z01_numcgm 											= j01_numcgm 
				      left outer join iptuconstr on j01_matric 					= j39_matric 
						  left outer join ruas as ruase on ruase.j14_codigo = j39_codigo
				     inner join itbimatric on j01_matric 							  = itbimatric.it06_matric
				      left join loteloc    on j06_idbql                 = j01_idbql
				      left join setorloc   on j05_codigo                = j06_setorloc";
		$sql2 = "";

		if(isset($chave_j01_matric) && (trim($chave_j01_matric)!="") ){
			//$sql = $cliptubase->sql_query($chave_j01_matric,$campos,"j01_matric");
			$sql2 =" where j01_matric = $chave_j01_matric";			  
		}else if(isset($j14_codigo) && (trim($j14_codigo)!="") ){
			//$sql = $cliptubase->sql_query("",$campos,"j01_numcgm"," j01_numcgm like '$chave_j01_numcgm%' ");
			$sql2 = " where j39_codigo = $j14_codigo order by j39_numero";			  
		}else if(isset($z01_nome) && (trim($z01_nome)!="") ){
			$sql2 = " where z01_nome like '$z01_nome%' order by z01_nome";			  
		}else if(isset($setor) || isset($quadra) || isset($lote)) {
			$and  = "";
			if(isset($setor) and $setor != '') {
				$sql2 .= " j05_codigoproprio = '{$setor}' ";
				$and   = " and ";
			}
			if(isset($quadra) and $quadra != '') {
				$sql2 .= "{$and} j06_quadraloc = '{$quadra}' ";
				$and   = " and ";
			}
			if(isset($lote) and $lote != '') {
				$sql2 .= "{$and} j06_lote = '{$lote}' ";
				$and   = " and ";
			}
			if ($sql2 != '') {
				$sql2 = " where {$sql2} "; 
			} 
		}else{
			$sql2 = "";
		}
		$repassa = array('dblov'=>'0');
		
		if ($sql2 != '') {
			db_lovrot(@$sql.@$sql2,15,"()","",$funcao_js,"","NoMe",$repassa);
		}
	}else{
		$sql = "select distinct j01_matric,
														(select rvnome as z01_nome from fc_busca_envolvidos(false, (select fc_regrasconfig from fc_regrasconfig(1)), 'M', iptubase.j01_matric) limit 1),
														case when j39_numero is null then 'Terr' else 'Pred' end as Tipo,  
														case when ruase.j14_codigo is null then ruas.j14_nome else ruase.j14_nome end as j14_nome, 
														case when j39_numero is null then 0 else j39_numero end as j39_numero,
														j39_compl,
														j34_setor,
														j34_quadra,
														j34_lote,
														j05_codigoproprio, 
													  j06_quadraloc, 
													  j06_lote
														 
							from iptubase 
						 inner join lote on j34_idbql 					            = j01_idbql 
							left outer join testpri on j49_idbql              = j01_idbql
							left outer join ruas on j14_codigo    	          = j49_codigo
						 inner join cgm on z01_numcgm	 						          = j01_numcgm 
							left outer join iptuconstr on j01_matric          = j39_matric 
						  left outer join ruas as ruase on ruase.j14_codigo = j39_codigo
				     inner join itbimatric on j01_matric 							= it06_matric
				      left join loteloc    on j06_idbql                 = j01_idbql
				      left join setorloc   on j05_codigo                = j06_setorloc
				     where j01_matric = $pesquisa_chave";
		$result = $clitbi->sql_record($sql);
		if($clitbi->numrows!=0){
			db_fieldsmemory($result,0);
			echo "<script>".$funcao_js."('$z01_nome',false);</script>";
		}else{
			echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
		}
	}
?>
	</td>
</tr>	
</table>

</form>	
</body>
</html>
<script>
function js_mostraruas(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_ruas.php?funcao_js=parent.js_preencheruas|0|1';
    db_iframe.show();
  }else{
    db_iframe.jan.location.href = 'func_ruas.php?pesquisa_chave='+document.form2.j14_codigo.value+'&funcao_js=parent.js_preencheruas_hide';	
  }
}

function js_preencheruas_hide(chave,chave1){
  
	$('j14_nome').value = chave;
	
}
function js_preencheruas(chave,chave1){
   
	$('j14_codigo').value = chave;
	$('j14_nome').value   = chave1;
	
}
</script>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
document.form2.chave_j01_matric.focus();
document.form2.chave_j01_matric.select();
  </script>
  <?
}


$db_iframe= new janela('db_iframe','');
$db_iframe ->posX=1;
$db_iframe ->posY=20;
$db_iframe ->largura=770;
$db_iframe ->altura=430;
$db_iframe ->titulo="Pesquisa";
$db_iframe ->iniciarVisivel = false;
$db_iframe ->mostrar();

?>
<script>
var oPesquisa = new DBViewPesquisaSetorQuadraLote('pesquisa', 'oPesquisa');
    oPesquisa.show();
    oPesquisa.appendForm();
<? 
if (isset($setorCodigo) ||
   isset($quadra) ){
	echo "oPesquisa.setValues('{$setorCodigo}','{$quadra}','{$lote}');"; 
}
?>
</script>