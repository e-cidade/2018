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
require_once("libs/db_utils.php");
require_once("classes/db_iptubase_classe.php");
require_once("dbforms/verticalTab.widget.php");

$clrotulo = new rotulocampo;
$clrotulo->label('j01_matric');
$clrotulo->label('z01_numcgm');
$clrotulo->label('z01_nome');
$clrotulo->label('j40_refant');
$clrotulo->label('proprietario');
$clrotulo->label('z01_numimob');
$clrotulo->label('j34_zona');
$clrotulo->label('j34_setor');
$clrotulo->label('j34_quadra');
$clrotulo->label('j34_lote');

db_sel_instit(db_getsession("DB_instit"), "db21_codcli");

if (@$cod_matricula != "") {
	$where = "j01_matric = $cod_matricula";
} elseif (@$cod_matricularegimo != "") {
	$where = "j04_matricregimo  = $cod_matricularegimo ";
}

$areaconst1 = 0;
$oDaoIptubase = db_utils::getDao('iptubase');

$rsIptubase   = $oDaoIptubase->sql_record($oDaoIptubase->sql_query_proprietariolote($where));

if ($oDaoIptubase->numrows > 0) {
	$oDadosMatricula = db_utils::fieldsMemory($rsIptubase, 0, null);
} else {
	db_redireciona("db_erros.php?db_erro=Matrícula não cadastrada.");
}


$rsAreaTotal = $oDaoIptubase->sql_record($oDaoIptubase->sql_query_area_total($oDadosMatricula->j34_setor,
$oDadosMatricula->j34_quadra,
$oDadosMatricula->j34_lote));
$nAreaTotal  = db_utils::fieldsMemory($rsAreaTotal, 0)->area_total;

$rsAreaConstruida = $oDaoIptubase->sql_record($oDaoIptubase->sql_query_area_contruida($oDadosMatricula->j01_matric));
$nAreaConstruida  = db_utils::fieldsMemory($rsAreaConstruida, 0)->area_construida;

$rsImobiliaria = $oDaoIptubase->sql_record($oDaoIptubase->sql_query_imobiliaria($oDadosMatricula->j01_matric, 'z01_nome'));
$lImobiliaria  = false;
if($oDaoIptubase->numrows > 0) {
	$lImobiliaria = true;
	$imobiliaria  = db_utils::fieldsMemory($rsImobiliaria, 0)->z01_nome;
}
$rsSetorFiscal = $oDaoIptubase->sql_record($oDaoIptubase->sql_query_setorfiscal($oDadosMatricula->j01_matric));
if($oDaoIptubase->numrows > 0) {
	$oSetorFiscal = db_utils::fieldsMemory($rsSetorFiscal, 0);
}

$oDaoCfIptu = db_utils::getDao('cfiptu');
$rsCfIptu   = $oDaoCfIptu->sql_record($oDaoCfIptu->sql_query_file(null, "j18_utilizaloc", "", "j18_anousu = ". db_getsession("DB_anousu")));
$lUtilizaLoc = db_utils::fieldsMemory($rsCfIptu, 0)->j18_utilizaloc == 't' ? true : false;

$sLoteloc = '';
if($lUtilizaLoc) {
	$sLoteloc = $oDadosMatricula->j05_codigoproprio .' - '. $oDadosMatricula->j05_descr . '-' . $oDadosMatricula->j06_quadraloc . '/' . $oDadosMatricula->j06_lote;
}

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
<link href="estilos/tab.style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript"
	src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript"
	src="scripts/prototype.js"></script>
<style>
.valores {
	background-color: #FFFFFF;
	padding-left: 10px;
}
</style>
<script>
		/**
		 * Função para Impressão BIC
		 * @param boolen lParam - Parâmetro para confirmação de impressão da BIC
		 * @return void()
		 */
    function js_Impressao(lParam) { 
      if (lParam) {
        var lGeraCalculo = confirm('Imprimir Demonstrativo de Cálculo?')
        window.open('cad3_conscadastro_impressao.php?tipo=2&geracalculo='+lGeraCalculo+'&parametro=<?=$oDadosMatricula->j01_matric?>','','location=0,HEIGHT=600,WIDTH=600');
      } else {
        window.open('cad3_conscadastro_impressao.php?tipo=1&parametro=<?=$oDadosMatricula->j01_matric?>','','location=0,HEIGHT=600,WIDTH=600');
      }
    }
    </script>
</head>

<body>
	<fieldset>
		<legend>
			<b>Dados Cadastrais do Imóvel (<?=@$oDadosMatricula->j01_tipoimp?>) </b>
			<?
			if(!empty($oDadosMatricula->j01_baixa)) {
				echo "<span class='aviso'>
                <font color='red'><b>Matrícula Baixada</b></font>
              </span>";
			}
			?>
		</legend>
		<table>
			<tr>
				<td title="<?=$Tj01_matric?>" style="width: 120px;"><?=$Lj01_matric?>
				</td>
				<td title="<?=$Tj01_matric?>" nowrap class='valores'
					style="width: 300px;"><?=$oDadosMatricula->j01_matric?>
				</td>
				<td style="width: 10px;"></td>
				<td title="<?=$Tj40_refant?>" style="width: 110px;"><?=$Lj40_refant?>
				</td>
				<td title="<?=$Tj40_refant?>" nowrap class='valores'
					style="width: 300px;"><?=$oDadosMatricula->j40_refant?>
				</td>
			</tr>
			<tr>
				<td title="<?=$Tz01_nome?>"><? db_ancora($Lz01_nome, "js_JanelaAutomatica('cgm','$oDadosMatricula->z01_cgmpri')", 2); ?>
				</td>
				<td title="<?=$Tz01_nome?>" nowrap class='valores'><?=$oDadosMatricula->z01_nome?>
				</td>
				<td></td>
				<td title="Proprietário"><b><? db_ancora('Proprietário',"js_JanelaAutomatica('cgm','$oDadosMatricula->z01_numcgm')",2); ?>
				</b>
				</td>
				<td title="Proprietário" nowrap class='valores'><?=$oDadosMatricula->proprietario?>
				</td>
			</tr>
			<tr>
				<td title="">
				  <strong>
    				<?
    				if($lImobiliaria) {
    					db_ancora("Imobiliária:", "js_JanelaAutomatica('cgm','$oDadosMatricula->z01_numimob')", 2);
    				} else {
    					echo "Imobiliária:";
    				}
    				?>
				  </strong>
				</td>
				<td title="" nowrap class='valores'><?
				if($lImobiliaria) {
					echo $imobiliaria;
				} else {
					echo "Matricula sem Imobiliária vinculada.";
				}
				?>
				</td>
				<td></td>
				<td title="<?=$Tj34_zona?>"><?=$Lj34_zona?>
				</td>
				<td title="" nowrap class='valores'><?= $oDadosMatricula->j34_zona  . " - " . $oDadosMatricula->j50_descr ?>
				</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td title="Setor Fiscal"><b>Setor Fiscal:</b>
				</td>
				<td nowrap class='valores'><?=@$oSetorFiscal->j91_codigo . " - " . @$oSetorFiscal->j90_descr?>
				</td>
			</tr>
			<tr>
				<td title="<?=$Tj34_setor?>"><b>Setor/Quadra/Lote:</b>
				</td>
				<td nowrap class='valores'>
				<? 
					echo $oDadosMatricula->j34_setor  . ' - ' . $oDadosMatricula->j30_descr . '/' . $oDadosMatricula->j34_quadra . '/' . $oDadosMatricula->j34_lote; 
				?>
				</td>
				<td></td>
				<td title="Construído no lote:"><b>Construído no lote:</b>
				</td>

				<td nowrap class='valores'><?=$nAreaConstruida?> - &Aacute;rea real
					construida no lote: <?=$oDadosMatricula->j34_totcon?>
				</td>
			</tr>
			<tr>
				<td title="Área do lote"><b>Área do lote:</b>
				</td>
				<td title="" nowrap class='valores'><?=db_formatar($oDadosMatricula->area_matric,"f");?>
					- Área real do lote: <?=@db_formatar($nAreaTotal,'f');?>
				</td>
				<td></td>
				<td title=""><b>Loteamento:</b>
				</td>
				<td title="" nowrap class='valores'><?=@$oDadosMatricula->j34_descr?>
				</td>
			</tr>
			<tr>
				<td title=""><b>Logradouro:<b>
				
				</td>
				<td title="" nowrap class='valores'><?=@$oDadosMatricula->codpri?> -
				<?=@$oDadosMatricula->tipopri?> . <?=@$oDadosMatricula->nomepri?>
					, <?=@$oDadosMatricula->j39_numero?> <?=(@$oDadosMatricula->j39_compl != ""?"/":"")?>
					<?=@$oDadosMatricula->j39_compl?>
				</td>
				<td></td>
				<td title=""><b>Setor/Quadra/Lote de localização:</b>
				</td>
				<td title="" nowrap class='valores'>
				<?=$lUtilizaLoc == true ? $sLoteloc : ''?>
				</td>
			</tr>
			<tr>
				<td title="Bairro"><b>Bairro:</b>
				</td>
				<td title="" nowrap class='valores'><?=@$oDadosMatricula->j13_codi . "-" . @$oDadosMatricula->j13_descr?>
				</td>

			</tr>
		</table>
	</fieldset>
	<fieldset>
		<legend>
			<b>Detalhamento</b>
		</legend>
		<?
		$oTabDetalhes = new verticalTab("detalhesemp",300);

		$oTabDetalhes->add("CaracteristicaImovel", "Caracteristicas do Imóvel",
        "cad3_conscadastro_002_detalhes.php?solicitacao=CaracteristicasDoImovel&parametro1=".$oDadosMatricula->j01_idbql);

		$oTabDetalhes->add("Isencoes", "Isenções",
        "cad3_conscadastro_002_detalhes.php?solicitacao=Isencoes&parametro=".$oDadosMatricula->j01_matric);

		$oTabDetalhes->add("ConstrucaoAtiva", "Construções ativas",
        "cad3_conscadastro_002_detalhes.php?solicitacao=Construcoes&parametro=".$oDadosMatricula->j01_matric);

		$oTabDetalhes->add("ConstrucaoDemolida", "Construções demolidas",
        "cad3_conscadastro_002_detalhes.php?solicitacao=Construcoesdemolidas&parametro=".$oDadosMatricula->j01_matric);

		$oTabDetalhes->add("ConstrucoesEscrituradas", "Construções escrituradas" ,
        "cad3_conscadastro_002_detalhes.php?solicitacao=ConstrucoesEscrituradas&parametro=".$oDadosMatricula->j01_matric);

		$oTabDetalhes->add("Testada", "Testada",
        "cad3_conscadastro_002_detalhes.php?solicitacao=Testada&parametro=".$oDadosMatricula->j01_idbql);

		$oTabDetalhes->add("TestadasInternas", "Testadas internas",
        "cad3_conscadastro_002_detalhes.php?solicitacao=TestadasInternas&parametro=".$oDadosMatricula->j01_idbql);

		$oTabDetalhes->add("DemonstrativoCalculo", "Demonstrativo de Cálculo",
        "cad3_conscadastro_002_detalhes.php?solicitacao=Imagens&parametro=".$oDadosMatricula->j01_matric);

		$oTabDetalhes->add("EnderecoEntrega", "Endereço de entrega",
        "cad3_conscadastro_002_detalhes.php?solicitacao=EnderecoDeEntrega&parametro=".$oDadosMatricula->j01_matric);

		$oTabDetalhes->add("OutrosProprietarios", "Outros proprietários",
        "cad3_conscadastro_002_detalhes.php?solicitacao=OutrosProprietarios&parametro=".$oDadosMatricula->j01_matric);

		$oTabDetalhes->add("PromitentesCompradores", "Promitentes Compradores",
        "cad3_conscadastro_002_detalhes.php?solicitacao=OutrosPromitentes&parametro=".$oDadosMatricula->j01_matric);

		$oTabDetalhes->add("ListaITBI", "Lista de ITBI",
        "cad3_conscadastro_002_detalhes.php?solicitacao=ListaITBI&parametro=".$oDadosMatricula->j01_matric);

		$oTabDetalhes->add("Averbacao", "Averbação",
        "cad3_conscadastro_002_detalhes.php?solicitacao=Averbacao&parametro=".$oDadosMatricula->j01_matric);

		$oTabDetalhes->add("Calculo", "Cálculo",
        "cad3_conscadastro_002_detalhes.php?solicitacao=Calculo&parametro=".$oDadosMatricula->j01_matric);

		$oTabDetalhes->add("OutrosDados", "Outros dados",
        "cad3_conscadastro_002_detalhes.php?solicitacao=outros&parametro=".$oDadosMatricula->j01_matric);

		$oTabDetalhes->add("ImprimeBICCompleta", "Imprime BIC Completa",
        "javascript:");  

		$oTabDetalhes->add("ImprimeBICResumida", "Imprime BIC Resumida",
        "javascript:");  

		$oTabDetalhes->add("ImprimeBICModeloNovo", "Imprime BIC - Modelo Novo",
        "cad3_conscadastrodetalhesmodelonovo001.php?matricula=".$oDadosMatricula->j01_matric);

		$oTabDetalhes->add("Ocorrencias", "Ocorrências",
        "agu3_conscadastro_002_detalhes.php?solicitacao=Ocorrencia&parametro=".$oDadosMatricula->j01_matric);

		if ($oDadosMatricula->j04_sequencial != null) {

			$oTabDetalhes->add("DadosRegistroImoveis", "Dados do Registro de Imóveis",
          "cad3_conscadastro_002_detalhes.php?solicitacao=RegistroImovel&parametro=".$oDadosMatricula->j04_sequencial);
		}

		if (!empty($oDadosMatricula->j01_baixa)) {

			$oTabDetalhes->add("DadosBaixa", "Dados da Baixa",
          "cad3_conscadastro_002_detalhes.php?solicitacao=dadosbaixa&parametro=".$oDadosMatricula->j01_matric);
		}
		
	  $oTabDetalhes->add("certidaoConstrucao", "Certidão de Construção",
	      "cad3_conscadastro_002_detalhes.php?solicitacao=certidaoConstrucao&parametro=".$oDadosMatricula->j01_matric);

    if ($db21_codcli == 19985){
       $oTabDetalhes->add("bicanterior", "Bic Recadastramento",
           "cad3_conscadastro_002_marica.php?parametro=".$oDadosMatricula->j01_matric);
    }


		$oTabDetalhes->show();
		?>

	</fieldset>
</body>
</html>
<script>
$('ImprimeBICResumida').observe("click", function() {
	js_Impressao(false);
});
$('ImprimeBICCompleta').observe("click", function() {
  js_Impressao(true);
});

</script>