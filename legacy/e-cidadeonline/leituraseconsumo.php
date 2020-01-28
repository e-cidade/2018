<?
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

session_start ();
include ("libs/db_conecta.php");
include ("libs/db_stdlib.php");
include ("libs/db_sql.php");
include ("libs/db_mens.php");
include ("classes/db_issbase_classe.php");
include ("classes/db_cgm_classe.php");
include ("classes/db_iptubase_classe.php");
require_once("agu3_conscadastro_002_classe.php");

$clissbase  = new cl_issbase;
$clcgm      = new cl_cgm;
$cliptubase = new cl_iptubase;
//parse_str( base64_decode( $HTTP_SERVER_VARS ["QUERY_STRING"] ) );
db_postmemory($_POST);
db_postmemory($_GET);

$Consulta = new ConsultaAguaBase($matric);
$rotulo = new rotulocampo();
 
$db_datausu = date("Y-m-d");
/*
 * consulta se o parâmetro do módulo prefeitura online está habilidado como true
*/
$instit     = db_getsession("DB_instit");

$rsRetornoConfig = db_query("select * from configdbpref where w13_instit = {$instit}");
$sConfig = pg_fetch_assoc($rsRetornoConfig);

$sqlinst    = "select codigo as instituicao, db21_regracgmiptu from db_config where prefeitura='t'";
$resultinst = db_query($sqlinst);
db_fieldsmemory($resultinst, 0);

// valida se o número da matrícula fornecida é válida
if ( isset($matric) ) {

  $sql = "select q02_numcgm, j01_matric 
            from iptubase 
           inner join cgm       on z01_numcgm = j01_numcgm 
           left  join issbase   on q02_numcgm = j01_numcgm 
		   where j01_matric = {$matric}";
  //Se o parâmetro "Exige CPF/CNPJ na consulta de imóveis" do mód. Prefeitura On-line estiver como "Sim" verifica o CPF/CNPJ  		   
  if ($sConfig["w13_exigecpfcnpjmatricula"] == "t") {
   $sql .= " and trim(z01_cgccpf) = '{$cgccpf}' ";
  }		  
		   
  $rsValidaMatricula = db_query($sql);
  $sResultadoValMat  = pg_num_rows($rsValidaMatricula);

  if ($sResultadoValMat == 0 ) {
      db_redireciona("digitamatricula.php?".base64_encode("erroscripts=Aviso: Os dados informados não conferem. Verifique o número da matrícula ou o CPF/CNPJ indicado!"));
  }
  
}
$matricula = $matric;

echo "
<script type=\"text/javascript\"> 
function js_voltar(){
  location.href = 'opcoesdebitospendentes.php?matricula=".@$matric."&inscricao=".@$inscr."&opcao=m&id_usuario=".@$id_usuario."&opcao=".@$opcao."&cgccpf=".@$cgccpf."';
}
</script>
";
?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="include/estilodai.css">
<script language="JavaScript" src="scripts/db_script.js"></script>
<script>
js_verificapagina("atualizaendereco.php,digitacontribuinte.php,digitainscricao.php,digitamatricula.php,listabicalvara.php,listabicimovel.php,listadebitospendentes.php,listasegundaviaalvara.php");
</script>
<style type="text/css">
<?
db_estilosite();
?>
.db_area {
  font-family : courier; 
}
#TabDbLov {
	border:1px;
}
</style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"	bgcolor="<?=$w01_corbody?>" onLoad="" <? mens_OnHelp() ?>>
<? //mens_div(); ?>

<table align="center" width="60%" border="0" cellspacing="0"
	cellpadding="0">
	<tr class="bold3">
		<td height="30" align="<?=$DB_align1?>"><?=$DB_mens1?></td>
	</tr>
	<tr>
		<td height="10" align="center" valign="middle">
		
		<table width="100%" border="0" bordercolor="#cccccc" cellpadding="5" cellspacing="0">
			<tr>
				<td width="100%" nowrap height="28" bgcolor="<?=$w01_corfundomenu?>">
				<table width="100%" border="0" cellpadding="1" cellspacing="0">
					<tr class="texto">
						<td><img src="imagens/icone.gif" border="0"></td>
						<td>CNPJ/CPF: <span class="bold3"><?=$cgccpf?></span><br>
						<? if(@$inscricao!=""){?> Inscrição:&nbsp; <span class="bold3"><?=@$inscricao?></span><br>
						<?}else if(@$matricula!=""){?> Matrícula:&nbsp; <span
							class="bold3"><?=@$matricula?></span><br>
							<?}else if(@$codigo_cgm!=""){?> CGM:&nbsp; <span class="bold3"><?=@$codigo_cgm?></span><br>
							<?}?></td>
					</tr>
					<tr class="texto">
						<td  colspan="2">
						<?php 
						if($acao == 'leitura'){
							echo "Leituras e Consumo";
						}else if($acao == 'hidrometros'){
							echo "Hidrômetros";
						}						
						?>
						</td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
		<table>
		<tr align="center">
				<td>
					<?php 
				
				if($acao == 'leitura'){
					$campos = "	x21_exerc,
	      			  			x21_mes,
				        			x17_descr,
				        			x21_dtleitura,
				        			x21_dtinc,
				        			x21_leitura,
				        			x21_consumo as x19_conspadrao,
	              			x21_consumo + x21_excesso as x21_consumo,
				        			x21_excesso
				        			";
					db_lovrot($Consulta->GetAguaLeituraSQL(0,$campos), 12,'','','NoMe');
				}else if($acao == 'hidrometros'){
					db_lovrot($Consulta->GetAguaHidroMatricSQL(), 10,'','','NoMe');
				}
				?>
				</td>
			</tr>
		</table>
		</td>
		</tr>
			<tr>
				<td align="center"><input type="button" value="Voltar" class="botao"
					onclick="js_voltar();"></td>
			</tr>
		</table>
</body>
</html>