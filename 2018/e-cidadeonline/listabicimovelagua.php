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

session_start();
require_once("agu3_conscadastro_002_classe.php");
require_once("libs/db_stdlib.php");


$consulta = new ConsultaAguaBase(0);
$rotulo   = new rotulocampo();

parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));

$consulta->SetMatric($matricula);
$result = $consulta->RecordSetAguaBase();

$claguabase = $consulta->GetAguaBaseDAO();
$claguabase->rotulo->label();

db_fieldsmemory($result, 0, false);

?>

<html>
<head>
<script language="JavaScript" src="scripts/db_script.js"></script>
<script>
<?
		echo " 
		function js_voltar(){
		  location.href = 'opcoesdebitospendentes.php?matricula=".@$matricula."&inscricao=".@$inscricao."&opcao=m&id_usuario=".@$id_usuario."&opcao=m&cgccpf=.@$cgccpf';
		}";

		/*
		  location.href = 'opcoesdebitospendentes.php?".base64_encode("matricula1=$matric&opcao=m&id_usuario=$id_usuario")."';

		*/
		?>
</script>
<style type="text/css">
<?db_estilosite();
echo"
.tabfonte {
               font-family: $w01_fontesite;
          font-size: $w01_tamfontesite;
          }
    ";
?>

td {
	font-size:10px;
}

</style>
</head>
<body>


<input type="submit" value="< Voltar" style="background-color:#eaeaea" onclick="js_voltar();">

<table width="100%" border="1" bordercolor="#eaeaea" cellpadding="0" cellspacing="0" >
<tr border="1">
	<td align="center" ><strong style="font-size:15px">DADOS CADASTRAIS DO IM&Oacute;VEL</strong></td>
</tr>

<tr>
<td>
<table width="100%" border="0" cellpadding="3" cellspacing="0" bordercolor="#cccccc"  class="tabfonte">
	<td>&nbsp;<?=@$Lx01_matric?></td>
	<td>&nbsp;<?=@$x01_matric?></td>

	<td>&nbsp;<?=@$Lx01_numcgm?></td>
	<td>&nbsp;<?=@$z01_nome?></td>
</tr>

<tr>
	<td>&nbsp;<?=@$Lx01_codrua?></td>
	<td>&nbsp;<?=@$x01_codrua." - ".$j14_nome.", ".$x01_numero?></td>
	
	<td>&nbsp;<?=@$Lx01_codbairro?></td>
	<td>&nbsp;<?=@$j13_descr?></td>
</tr>

<tr>
	<td>&nbsp;<?=@$Lx01_distrito?></td>
	<td>&nbsp;<?=@$x01_distrito?></td>
	
	<td>&nbsp;<?=@$Lx01_zona?></td>
	<td>&nbsp;<?=@$x01_zona?></td>
</tr>

<tr>
	<td>&nbsp;<?=@$Lx01_quadra?></td>
	<td>&nbsp;<?=@$x01_quadra?></td>
	
	<td>&nbsp;<?=@$Lx01_numero?></td>
	<td>&nbsp;<?=@$x01_numero?></td>
</tr>

<tr>
	<td>&nbsp;<?=@$Lx01_orientacao?></td>
	<td>&nbsp;<?=@$x01_orientacao?></td>
	
	<td>&nbsp;<?=@$Lx01_rota?></td>
	<td>&nbsp;<?=@$x01_rota?></td>
</tr>

<tr>
	<td>&nbsp;<?=@$Lx01_qtdeconomia?></td>
	<td>&nbsp;<?=@$x01_qtdeconomia?></td>
	
	<td>&nbsp;<?=@$Lx01_dtcadastro?></td>
	<td>&nbsp;<?=db_formatar(@$x01_dtcadastro, 'd')?></td>
</tr>

<tr>
	<td>&nbsp;<?=@$Lx01_qtdponto?></td>
	<td>&nbsp;<?=@$x01_qtdponto?></td>
	
	<!-- <td>&nbsp;<?=@$Lx01_obs?></td>
	<td>&nbsp;<? if($x01_obs == null){echo "Nenhuma";} else{ echo $x01_obs;} ?></td>
	 -->
</tr>

<?// Condomínio 

  $resultCondominio = $consulta->RecordSetAguaCondominio();
  if(pg_numrows($resultCondominio)>0) {
    db_fieldsmemory($resultCondominio, 0);
    $infoCondominio = $x31_codcondominio." ( Matrícula: ".$x31_matric." - ".$dl_proprietario." ) ";
  	?>
  	<tr>
  		<td>Condominio</td>
  		<td>&nbsp;<?=@$infoCondominio?></td>
  	</tr>
  	<?
  }
 ?>
 
 </table>
 </td>
 </tr>
 </table>
<br/>

<table width="100%"> 
<tr>
	<td align="center"><strong style="font-size:13px">Caracter&iacute;sticas do Im&oacute;vel</strong></td>
</tr>

<tr><td>
 <table width="100%" border="1" bordercolor="#eaeaea" cellpadding="0" cellspacing="0">
 <?
 //CARACTERISTICAS DO IMOVEL
  $result = $consulta->RecordSetAguaBaseCar();
  if($result) {
  	$linhas = pg_numrows($result);
  }else {
  	$linhas = 0;
  }
  
  if($linhas > 0) {
  	$coluna = 1;
  	for($i=0; $i<$linhas; $i++) {
  		db_fieldsmemory($result, $i);
  		if($coluna == 1) echo "<tr>";
  		?>
  		
  			<td>&nbsp;<?=@$j31_codigo?></td>
  			<td>&nbsp;<?=@$j31_descr?> (<?=@$j32_descr?>)</td>
  		<?
  		if($coluna <> 1){
  			echo "</tr>";
  		}
  		
  		if($i%2==0) {
  			$coluna == 1;
  		}
  		$coluna ++;
  	}
  }else {
  	?>
  		<tr>
  			<td align="center">Sem Caracter&iacute;sticas Cadastradas</td>
  		</tr>
  	<?
  }
  //ISENÇÕES
  ?>
 </table>
 </td>
 </tr>
 </table>
 
 <br/>
 <table width="100%">
 	<tr>
		<td align="center"><strong style="font-size:13px">ISEN&Ccedil;&Otilde;ES</strong></td>
	</tr>
	<tr><td>
	<table width="100%" border="1"  cellpadding="0" cellspacing="0" bordercolor="#cccccc">
  <?
	$result = $consulta->RecordSetAguaIsencaoRec();

	if($result) {
		$linhas = pg_numrows($result);
	} else {
		$linhas = 0;
	}

	if($linhas>0) {
		for($i=0; $i<$linhas; $i++) {
			db_fieldsmemory($result, $i);
			?>
			<tr>
				<td>&nbsp;<?echo db_formatar($x10_dtini, 'd')?> a 
				    &nbsp;<?echo db_formatar($x10_dtfim, 'd')?></td>
				<td>&nbsp;<?=@$x29_descr?></td>
				<td>&nbsp;<?echo db_formatar($x26_percentual, 'f')?>%</td>
				<td>&nbsp;<?=@$x25_descr?></td>
			</tr>
			<?
		}
	}else {
		?>
			<tr>
  			<td align="center">Sem Isen&ccedil;&otilde;es</td>
  		</tr>
		<?
	}
	// CONSTRUÇÕES
	?>
	</table></td></tr>
	</table>
	<br/><br>
	
	<table width="100%">
  	<tr>
			<td align="center"><strong style="font-size:13px">CONSTRU&Ccedil;&Otilde;ES</strong></td>
		</tr>
		<tr><td><table width="100%" border="1"  cellpadding="0" cellspacing="0" bordercolor="#cccccc">
  <?
  
	$result = $consulta->RecordSetAguaConstrCar();
	if($result) {
		$linhas = pg_numrows($result);
	}else {
		$linhas = 0;
	}
	
	if($linhas > 0) {
		$rotulo->label("x11_numero");
		$rotulo->label("x11_complemento");
		$rotulo->label("x11_area");
		$rotulo->label("x11_pavimento");
		$rotulo->label("x11_qtdfamilia");
		$rotulo->label("x11_qtdpessoas");
		$rotulo->label("j31_codigo");
		$rotulo->label("j31_descr");
		$rotulo->label("j32_descr");
		$rotulo->label("x12_codconstr");
		
		for($i=0; $i<$linhas; $i++) {
			db_fieldsmemory($result, $i);
			if($x11_codconstr <> $ant) {
				$ant = $x11_codconstr;
				//numero : complemento
				?>
				<tr>
					<td align="center" colspan= "4"><?=@$Lx12_codconstr?> <?=@$x12_codconstr?> </td>
				</tr>
				<tr>
					<td>&nbsp;<?=@$Lx11_numero?></td><td>&nbsp;<?=@$x11_numero?> </td>
					<td>&nbsp;<?=@$Lx11_complemento?></td><td>&nbsp;<?=@$x11_complemento?> </td>
				</tr>
				<?//area pavimento?>
				<tr>
					<td>&nbsp;<?=@$Lx11_area?></td><td>&nbsp;<?=@$x11_area?> m2</td>
					<td>&nbsp;<?=@$Lx11_pavimento?></td><td>&nbsp;<?=@$x11_pavimento?> </td>
				</tr>
				<?// qtde familia / qtde pessoas?>
				<tr>
					<td>&nbsp;<?=@$Lx11_qtdfamilia?></td><td>&nbsp;<?=@$x11_qtdfamilia?> </td>
					<td>&nbsp;<?=@$Lx11_qtdpessoas?></td><td>&nbsp;<?=@$x11_qtdpessoas?> </td>
				</tr>
				<?
			}
			?>
			</table>
			<br>
			<table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#cccccc">
			<tr>
				<td colspan="2" align="center"><strong style="font-size:11px">Caracter&iacute;sticas da Contru&ccedil;&atilde;o</strong></td>
			</tr>
			<tr>
				<td>&nbsp;<?=@$j31_codigo?></td>
				<td>&nbsp;<? $descr = substr($j31_descr,0,20).' ('.substr($j32_descr,0,20).')'; echo $descr?> </td>
			</tr>	
			<?
			
		}
	}else {
		?>
		<tr>
  			<td align="center">Sem Constru&ccedil;&otilde;es Cadastradas</td>
  	</tr>
		<?
	}

	//Endereço de Entrega
	?>
	</table></td></tr>
	</table>
	<br/>
	<table width="100%">
		<tr>
			<td align="center"><strong style="font-size:13px">ENDERE&Ccedil;O DE ENTREGA</strong></td>
		</tr>
		<tr><td><table width="100%" border="1"  cellpadding="0" cellspacing="0" bordercolor="#cccccc">
	<?
	$result = $consulta->RecordSetAguaBaseCorresp();
	
	if($result) {
		$linhas = pg_numrows($result);
	} else {
		$linhas = 0;
	}
	
	if($linhas > 0) {
		$rotulo->label("x02_codrua");
		$rotulo->label("x02_numero");
		$rotulo->label("x02_codbairro");
		$rotulo->label("x02_complemento");
		$rotulo->label("x02_rota");
		$rotulo->label("x02_orientacao");

		$rotulo->label("j13_descr");
		$rotulo->label("j14_nome");
		
		for($i=0; $i<$linhas; $i++) {
			db_fieldsmemory($result, $i);
			//logradouro : bairro
			?>
				<tr>
					<td>&nbsp;<?=@$Lx02_codrua?></td>
					<td>&nbsp;<?=@$x02_codrua." - ".$j14_nome?></td>
					<td>&nbsp;<?=@$Lx02_codbairro?></td>
					<td>&nbsp;<?=@$j13_descr?></td>
				</tr>
			<?
			//numero : complemento
			?>
				<tr>
					<td>&nbsp;<?=@$Lx02_numero?></td>
					<td>&nbsp;<?=@$x02_numero?></td>
					<td>&nbsp;<?=@$Lx02_complemento?></td>
					<td>&nbsp;<?=@$x02_complemento?></td>
				</tr>
			
			<?
			// ROTA  :  ORIENTACAO
			?>
				<tr>
					<td>&nbsp;<?=@$Lx02_rota?></td>
					<td>&nbsp;<?=@$x02_rota?></td>
					<td>&nbsp;<?=@$Lx02_orientacao?></td>
					<td>&nbsp;<?=@$x02_orientacao?></td>
				</tr>
			<?
		
		}
	}else {
		?>
		<tr>
  			<td align="center">Sem Endere&ccedil;o de Entrega Cadastrado</td>
  	</tr>
		<?
	}
	
 	//Hidrometros
 	?>
 	</table></td></tr>
 	</table>
 	<br/>
 	
 	<table width="100%">
		<tr>
			<td align="center"><strong style="font-size:13px">HIDR&Ocirc;METROS</strong></td>
		</tr>
		
	<?
	$result = $consulta->RecordSetAguaHidroMatric();

	if($result) {
		$linhas = pg_numrows($result);
	} else {
		$linhas = 0;
	}
	
	if($linhas>0){
		$rotulo->label("x04_nrohidro");
		$rotulo->label("x04_qtddigito");
		$rotulo->label("x04_dtinst");
		$rotulo->label("x04_leitinicial");
		$rotulo->label("x15_diametro");
		$rotulo->label("x03_nomemarca");
		$rotulo->label("x28_dttroca");
		$rotulo->label("x28_obs");
		
		for($i=0; $i<$linhas; $i++) {
			db_fieldsmemory($result, $i);
			
			// NRO HIDRO  :  QTD DIGITOS  : DT INSTALACAO
			?>
			<tr><td><table width="100%" border="1"  cellpadding="0" cellspacing="0" bordercolor="#cccccc">
			<tr>
				<td>&nbsp;<?=$Lx04_nrohidro?></td>
				<td>&nbsp;<?=$x04_nrohidro?></td>
				<td>&nbsp;<?=$Lx04_qtddigito?></td>
				<td>&nbsp;<?=$x04_qtddigito?></td>
				<td>&nbsp;<?=$Lx04_dtinst?></td>
				<td>&nbsp;<?=db_formatar($x04_dtinst,'d')?></td>
			</tr>
			<?
			// LEITURA INICIAL  :  DIAMETRO  :  MARCA 
			?>
			<tr>
				<td>&nbsp;<?=$Lx04_leitinicial?></td>
				<td>&nbsp;<?=$x04_leitinicial?></td>
				<td>&nbsp;<?=$Lx15_diametro?></td>
				<td>&nbsp;<?=$x15_diametro?></td>
				<td>&nbsp;<?=$Lx03_nomemarca?></td>
				<td>&nbsp;<?=$x03_nomemarca?></td>
			</tr>
			<?
			if(!empty($x28_dttroca)){
			?>
				<tr>
					<td>&nbsp;<?=$Lx28_dttroca?></td>
					<td>&nbsp;<?=db_formatar($x28_dttroca,'d')?></td>
					<td>&nbsp;<?=$Lx28_obs?></td>
					<td colspan="3">&nbsp;<?=$x28_obs?></td>
				</tr>
			<?
			}
			?>
				</table><br/>
			<? 
		}		
	}else{
		?>
		<tr>
  			<td align="center">Sem Hidr&ocirc;metros Cadastrados</td>
  	</tr>
		<?
	}
	?>
	
	</table></td></tr>
	</table>
	
	<br/>
	<table width="100%">
	<tr>
		<td align="center" align="center" colspan="10"><strong style="font-size:13px">LEITURAS</strong></td>
	</tr>
	<tr><td><table width="100%" border="1"  cellpadding="0" cellspacing="0" bordercolor="#cccccc">
	<?
	
	//Leituras
	$result = $consulta->RecordSetAguaLeitura(12);

	if($result) {
		$linhas = pg_numrows($result);
	} else {
		$linhas = 0;
	}
	
	if($linhas > 0){
		
		$rotulo->label("x21_exerc");
		$rotulo->label("x21_mes");
		$rotulo->label("x17_descr");
		$rotulo->label("x21_dtleitura");
		$rotulo->label("x21_dtinc");
		$rotulo->label("x21_leitura");
		$rotulo->label("x21_consumo");
		$rotulo->label("x21_excesso");
		/*$rotulo->label("x21_numcgm");
		$rotulo->label("login");
		*/
		?>
		<tr>
			<td>&nbsp;<?=$Lx21_exerc?></td>
			<td>&nbsp;<?=$Lx21_mes?></td>
			<td>&nbsp;<?=$Lx17_descr?></td>
			<td>&nbsp;<?=$Lx21_dtleitura?></td>
			<td>&nbsp;<?=$Lx21_dtinc?></td>
			<td>&nbsp;<?=$Lx21_leitura?></td>
			<td>&nbsp;<?=$Lx21_consumo?></td>
			<td>&nbsp;<?=$Lx21_excesso?></td>
			<!--<td>&nbsp;<?=$Lx21_numcgm?></td>
			<td>&nbsp;<?=$Llogin?></td>
		--></tr>
		<?
		for($i=0; $i<$linhas; $i++) {
			db_fieldsmemory($result, $i);
			?>
			<tr>
				<td>&nbsp;<?=$x21_exerc?></td>
				<td>&nbsp;<?=$x21_mes?></td>
				<td>&nbsp;<?=$x17_descr?></td>
				<td>&nbsp;<?=db_formatar($x21_dtleitura,'d')?></td>
				<td>&nbsp;<?=db_formatar($x21_dtinc,'d')?></td>
				<td>&nbsp;<?=$x21_leitura?></td>
				<td>&nbsp;<?=$x21_consumo?></td>
				<td>&nbsp;<?=$x21_excesso?></td>
				<!--<td>&nbsp;<?=$x21_numcgm?></td>
				<td>&nbsp;<?=$login?></td>
			--></tr>
			<?
		}
		
	}else {
		?>
		<tr>
  			<td colspan="4" align="center">Sem Leituras Cadastradas</td>
  	</tr>
		<?
	}	

?>
</table></td></tr>
</table>

</body>
</html>