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
  include("classes/db_aguaisencaorec_classe.php");
  include("classes/db_aguaconstrcar_classe.php");
  include("classes/db_agualeitura_classe.php");
  include("classes/db_aguahidrotroca_classe.php");
  include("dbforms/db_funcoes.php");
  require_once("agu3_conscadastro_002_classe.php");

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js">

</script>
<style>
.db_area {
  
  font-family : courier; 

}
  

</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?
	db_postmemory($HTTP_GET_VARS,0);

	$Consulta = new ConsultaAguaBase($parametro);
	$rotulo = new rotulocampo();

	// CARACTERISTICAS
	if ($solicitacao == "CaracteristicasDoImovel") {
		db_lovrot($Consulta->GetAguaBaseCarSQL(), 15, "()", "", "");
		
  	//
	// ISENCOES
	//
	} else if ($solicitacao == "Isencoes") {
		db_lovrot($Consulta->GetAguaIsencaoRecSQL(), 15, "()", "", "");

	//
	// ENDERECO DE ENTREGA
	//
	} else if ($solicitacao == "EnderecoDeEntrega") {
		db_lovrot($Consulta->GetAguaBaseCorrespSQL(), 15, "()", "", "");

	//
	// CONSTRUCOES
	//
	} else if ($solicitacao == "Construcoes") {

		$claguaconstrcar = $Consulta->GetAguaConstrCarDAO();
		$claguaconstrcar->rotulo->label();

		$rotulo->label("x11_numero");
		$rotulo->label("x11_complemento");
		$rotulo->label("x11_area");
		$rotulo->label("x11_pavimento");
		$rotulo->label("x11_qtdfamilia");
		$rotulo->label("x11_qtdpessoas");
		$rotulo->label("j31_codigo");
		$rotulo->label("j31_descr");
		$rotulo->label("j32_descr");
		$rotulo->label("j32_tipo");

		$result = $Consulta->RecordSetAguaConstrCar();

		$linhas	= pg_num_rows( $result );	

		// Percorre construcoes;
		if( $linhas != 0 ) {

			echo "<table width=\"70%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"2\">";
			
			for ($contador=0; $contador < $linhas; $contador ++ ) {
				db_fieldsmemory($result, $contador);
				
				// Mostra Dados da Construcao
				if($x11_codconstr <> @$ant) {
					$ant = $x11_codconstr;
					
					// Construcao: 0
					echo "<tr align=center>";
					echo "  <td colspan=7 bgcolor=#333333>";
					echo "    <font color=#FFFFFF>$Lx12_codconstr $x12_codconstr</font>";
					echo "  </td>";
					echo "</tr>";

					echo " <tr align=\"center\"> ";
					echo "   <td colspan=\"4\">&nbsp; </td> ";
					echo " </tr> ";

					// NUMERO - COMPLEMENTO
					echo " <tr align=\"center\"> ";
					echo "   <td width=\"30%\"align=\"right\" nowrap bgcolor=\"#CCCCCC\">$Lx11_numero</td> ";
					echo "   <td align=\"left\" nowrap bgcolor=\"#FFFFFF\">$x11_numero&nbsp;</td> ";
					echo "   <td width=\"30%\"align=\"right\" nowrap bgcolor=\"#CCCCCC\">$Lx11_complemento</td> ";
					echo "   <td align=\"left\" nowrap bgcolor=\"#FFFFFF\">$x11_complemento&nbsp; ";
					echo "   </td> ";
					echo " </tr> ";
	
					// AREA - PAVIMENTO
					echo " <tr align=\"center\"> ";
					echo "   <td width=\"30%\"align=\"right\" nowrap bgcolor=\"#CCCCCC\">$Lx11_area</td> ";
					echo "   <td align=\"left\" nowrap bgcolor=\"#FFFFFF\">$x11_area m2&nbsp;</td> ";
					echo "   <td width=\"30%\"align=\"right\" nowrap bgcolor=\"#CCCCCC\">$Lx11_pavimento</td> ";
					echo "   <td align=\"left\" nowrap bgcolor=\"#FFFFFF\">$x11_pavimento&nbsp; ";
					echo "   </td> ";
					echo " </tr> ";
	
					// QTDFAMILIA - QTDPESSOAS
					echo " <tr align=\"center\"> ";
					echo "   <td width=\"30%\"align=\"right\" nowrap bgcolor=\"#CCCCCC\">$Lx11_qtdfamilia</td> ";
					echo "   <td align=\"left\" nowrap bgcolor=\"#FFFFFF\">$x11_qtdfamilia&nbsp;</td> ";
					echo "   <td width=\"30%\"align=\"right\" nowrap bgcolor=\"#CCCCCC\">$Lx11_qtdpessoas</td> ";
					echo "   <td align=\"left\" nowrap bgcolor=\"#FFFFFF\">$x11_qtdpessoas&nbsp; ";
					echo "   </td> ";
					echo " </tr> ";

					echo " <tr align=\"center\"> ";
					echo "   <td colspan=\"4\">&nbsp; </td> ";
					echo " </tr> ";
		
				}

				// j31_codigo - j31_descr
				echo " <tr align=\"center\"> ";
				echo "   <td width=\"30%\"align=\"right\" nowrap bgcolor=\"#CCCCCC\">$Lj31_codigo</td> ";
				echo "   <td align=\"left\" nowrap bgcolor=\"#FFFFFF\">$j31_codigo&nbsp;</td> ";
				echo "   <td width=\"30%\"align=\"right\" nowrap bgcolor=\"#CCCCCC\">$Lj31_descr</td> ";
				echo "   <td align=\"left\" nowrap bgcolor=\"#FFFFFF\">$j31_descr&nbsp; ";
				echo "   </td> ";
				echo " </tr> ";

				// j32_descr - j32_tipo
				echo " <tr align=\"center\"> ";
				echo "   <td width=\"30%\"align=\"right\" nowrap bgcolor=\"#CCCCCC\">$Lj32_descr</td> ";
				echo "   <td align=\"left\" nowrap bgcolor=\"#FFFFFF\">$j32_descr&nbsp;</td> ";
				echo "   <td width=\"30%\"align=\"right\" nowrap bgcolor=\"#CCCCCC\">$Lj32_tipo</td> ";
				echo "   <td align=\"left\" nowrap bgcolor=\"#FFFFFF\">$j32_tipo&nbsp; ";
				echo "   </td> ";
				echo " </tr> ";
			}
			echo "</table>";
		}
		
	/////////////////////////////////////////////////////////////////////////////////////////////////////
	} else if ($solicitacao == "Leitura") {
	  ?>
	  <center>
    <input name="imprimir" type="button" value="Imprimir Leituras" onclick="js_imprime_leituras();">
    </center>
	  <?
	  
		db_lovrot($Consulta->GetAguaLeituraSQL(), 12, "()", "", "");


	/////////////////////////////////////////////////////////////////////////////////////////////////////
	}else if ($solicitacao == "Condominio") {
    db_lovrot($Consulta->GetAguaCondominioMatricSQL(), 10, "()", "", "");

    }else if ($solicitacao == "Hidrometro") {
		db_lovrot($Consulta->GetAguaHidroMatricSQL(), 10, "()", "", "");
    
 	//
	// CORTE
	//
	} else if ($solicitacao == "Corte") {
	  echo "<br>";
	  ?>
	  <center>
	   <input name="imprimir" type="button" value="Imprimir" onclick="js_imprime();">
	  </center>
    <?
   	db_lovrot($Consulta->GetAguaCorteMatMovSQL(), 15, "()", "", "");

	/////////////////////////////////////////////////////////////////////////////////////////////////////
	} else if ($solicitacao == "Calculo") {

		echo "<table width=95% border=0 align=center cellpadding=0 cellspacing=2>";
		//echo "  <tr align=center>"; 
		//echo "    <td colspan=7><u>C&aacute;lculo por matr&iacute;cula</u></td>";
		//echo "  </tr>";

		$rotulo->label("x22_area");
		$rotulo->label("x22_numpre");
		$rotulo->label("x19_conspadrao");
		$rotulo->label("x19_descr");
		$rotulo->label("x21_consumo");
		$rotulo->label("x21_excesso");

		$rotulo->label("x23_valor");
		$rotulo->label("x25_descr");
		$rotulo->label("x25_receit");
		$rotulo->label("k02_descr");
		//$rotulo->label("x21_excesso");
		//$rotulo->label("x21_excesso");

		$rCalc = $Consulta->RecordSetAguaCalc();

		$iLinhas = pg_numrows($rCalc);

		if( $iLinhas == 0 ) {
			echo "  <tr align=center>"; 
			echo "    <td colspan=7></td>";
			echo "  </tr>";
			echo "  <tr align=center>"; 
			echo "    <td colspan=7><b>Nenhum registro encontrado</b></td>";
			echo "  </tr>";
			exit;
		}

		for ($indx=0; $indx < $iLinhas; $indx ++ ) {
			db_fieldsmemory($rCalc, $indx);
	
			$nomemes = strtoupper(db_mes($x22_mes));

			// Titulo:  MES / ANO
			echo "<tr align=center>";
			echo "  <td colspan=7 bgcolor=#333333>";
			echo "    <font color=#FFFFFF><b>$nomemes / $x22_exerc</b></font>";
			echo "  </td>";
			echo "</tr>";
			
			echo "  <tr align=left>"; 
			echo "   <td colspan=7 nowrap> <table width=100% border=0 cellspacing=2 cellpadding=0>";

			// X22_AREA  :  X22_NUMPRE
			echo "       <tr> ";
			echo "         <td width=21% align=left nowrap bgcolor=#CCCCCC><div align=right>&nbsp;";
			echo "           $Lx22_area</div></td>";
			echo "         <td width=23% align=right> ";
			echo             db_formatar($x22_area,'f');
			echo "         </td>";
			echo "         <td width=25% align=left nowrap bgcolor=#CCCCCC><div align=right>";
			echo "	         $Lx22_numpre</div></td>";
			echo "         <td width=31% align=right> ";
			echo             db_numpre($x22_numpre, $x22_mes, 1, 0);
			echo "         </td>";
			echo "       </tr>";

			//	X19_CONSPADRAO  :  X19_DESCR
			echo "	     <tr> ";
			echo "         <td width=21% align=left nowrap bgcolor=#CCCCCC><div align=right>&nbsp;";
			echo "           $Lx19_conspadrao</div></td>";
			echo "         <td width=23% align=right> ";
			//echo             db_formatar($x19_conspadrao,'f') . " m3";
			echo             db_formatar($x21_consumo,'f') . " m3";
			echo "         </td>";
			echo "         <td width=25% align=left nowrap bgcolor=#CCCCCC><div align=right>";
			echo "	         $Lx19_descr</div></td>";
			echo "         <td width=31% align=right> ";
			echo "           $x19_descr";
			echo "         </td>";
			echo "       </tr>";

			//	X21_CONSUMO  :  X21_EXCESSO
			echo "	     <tr> ";
			echo "         <td width=21% align=left nowrap bgcolor=#CCCCCC><div align=right>&nbsp;";
			echo "           $Lx21_consumo</div></td>";
			echo "         <td width=23% align=right> ";
			echo             db_formatar($x21_consumo + $x21_excesso,'f') . " m3";
			echo "         </td>";
			echo "         <td width=25% align=left nowrap bgcolor=#CCCCCC><div align=right>";
			echo "	         $Lx21_excesso</div></td>";
			echo "         <td width=31% align=right> ";
			echo             db_formatar($x21_excesso, 'f') . " m3";
			echo "         </td>";
			echo "       </tr>";

			echo " <tr><td colspan=7></td></tr>";
			echo " <tr align=left> ";
			echo "   <td colspan=7 nowrap bgcolor=#CCCCCC> <table width=100% border=0 cellspacing=2 cellpadding=0>";
			echo "     <tr> ";
			echo "       <td width=9% nowrap>$Lx25_receit</td>";
			echo "       <td width=21% nowrap>$Lk02_descr</td>";
			echo "       <td width=30% nowrap>$Lx25_descr</td>";
			echo "       <td width=16% align=right nowrap>$Lx23_valor</td>";
			echo "     </tr>";

			$rCalcVal = $Consulta->RecordSetAguaCalcVal($x22_codcalc);

			$nSoma = 0;
			// Exibe Valores Calculados
			for ($indy=0; $indy < pg_numrows($rCalcVal); $indy ++ ) {
				db_fieldsmemory($rCalcVal, $indy);
				$nSoma += $x23_valor;

				echo "     <tr> ";
				echo "       <td width=9% nowrap bgcolor=#FFFFFF>$x25_receit</td>";
				echo "       <td width=21% nowrap bgcolor=#FFFFFF>$k02_descr</td>";
				echo "       <td width=30% nowrap bgcolor=#FFFFFF>$x25_descr</td>";
				echo "       <td width=16% align=right nowrap bgcolor=#FFFFFF>".db_formatar($x23_valor,'f')."</td>";
				echo "     </tr>";
			}

			echo "     <tr align=left> ";
			echo "       <td width=70% colspan=3 align=left nowrap bgcolor=#CCCCCC>";
			echo "	       <b>T O T A L &nbsp&nbsp C A L C U L A D O:</b>";
			echo "	     </td>";
			echo "       <td width=30% align=right nowrap bgcolor=#FFFFFF> ";
			echo "         <b>".db_formatar($nSoma,'f')."</b>";
			echo "	     </td>";
			echo "     </tr>";
			echo "     <tr align=left> ";
			echo "       <td colspan=7>&nbsp;</td>";
			echo "     </tr>";


			echo "   </td></table>";
			echo " </tr>";

			// Fecha Tabela
			//echo "    </table></td>";
			//echo " </tr>";
		}

		echo "</table>";
	} else if ($solicitacao == "Ocorrencia") {
	  echo "<br>";
	 
   	db_lovrot($Consulta->getHistOcorrenciaMatric(), 15, "()", "", "");

	/////////////////////////////////////////////////////////////////////////////////////////////////////
	} else if ($solicitacao == "BaixaImoveis") {
	  echo "<br>";	 
   	  db_lovrot($Consulta->getHistAguaBaixaImoveis(), 15, "()", "", "");
   	  
	}
?>
</body>
</html>
<script>
function js_imprime(){

	window.open('agu4_relcorte.php?matric=<?=@$parametro?>' ,'Relatorio','toolbar=no,menubar=no,scrollbars=no,resizable=no,location=no,directories=no,status=no');
  return false;
}

function js_imprime_leituras() {
	window.open('agu4_relleituras002.php?matric=<?=@$parametro?>' ,'Relatorio','toolbar=no,menubar=no,scrollbars=no,resizable=no,location=no,directories=no,status=no');
	return false;	
}
</script>