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

require_once("fpdf151/scpdf.php");
require_once("fpdf151/impcarne.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("classes/db_solicita_classe.php");
require_once("classes/db_solicitem_classe.php");
require_once("classes/db_pcdotac_classe.php");
require_once("classes/db_pcparam_classe.php");
require_once("classes/db_pcsugforn_classe.php");
require_once("classes/db_db_departorg_classe.php");
require_once("classes/db_orcreservasol_classe.php");
require_once("classes/db_empparametro_classe.php");

/*
 * Configura��es GED
*/
require_once ("integracao_externa/ged/GerenciadorEletronicoDocumento.model.php");
require_once ("integracao_externa/ged/GerenciadorEletronicoDocumentoConfiguracao.model.php");
require_once ("libs/exceptions/BusinessException.php");

$oGet = db_utils::postMemory($_GET);
$oConfiguracaoGed = GerenciadorEletronicoDocumentoConfiguracao::getInstance();
if ($oConfiguracaoGed->utilizaGED()) {

  if ($oGet->ini != $oGet->fim) {

    $sMsgErro  = "O par�metro para utiliza��o do GED (Gerenciador Eletr�nico de Documentos) est� ativado.<br><br>";
    $sMsgErro .= "Neste n�o � poss�vel informar interv�los de c�digos ou datas.<br><br>";
    db_redireciona("db_erros.php?fechar=true&db_erro={$sMsgErro}");
    exit;
  }
}

$clsolicita      = new cl_solicita;
$clsolicitem     = new cl_solicitem;
$clpcdotac       = new cl_pcdotac;
$clpcsugforn     = new cl_pcsugforn;
$cldb_departorg  = new cl_db_departorg;
$classinatura    = new cl_assinatura;
$clpcparam       = new cl_pcparam;
$clorcreservasol = new cl_orcreservasol;
$clempparametro	 = new cl_empparametro;

$sqlpref    = "select * from db_config where codigo = ".db_getsession("DB_instit");
$resultpref = db_query($sqlpref);
db_fieldsmemory($resultpref,0);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

// die($clpcparam->sql_query_file(null,"pc30_comsaldo,pc30_permsemdotac,pc30_gerareserva,pc30_libdotac"));
$result_pcparam = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"),"pc30_comsaldo,pc30_permsemdotac,pc30_gerareserva,pc30_libdotac"));
db_fieldsmemory($result_pcparam,0);

// die($clempparametro->sql_query_file(db_getsession("DB_anousu"),"e30_numdec"));
$result02 = $clempparametro->sql_record($clempparametro->sql_query_file(db_getsession("DB_anousu"),"e30_numdec"));
if($clempparametro->numrows>0) {
	db_fieldsmemory($result02,0);
}

$where_solicita = "";
if(isset($ini) && trim($ini)!="") {
	$where_solicita = " pc10_numero >= $ini";
}
if(isset($fim) && trim($fim)!="") {

	if($where_solicita == "") {
		$where_solicita = " pc10_numero <= $fim";
	}else{
		$where_solicita = " pc10_numero between $ini and $fim";
	}
}

$and = "";
if($where_solicita!="") {
	$and = " and ";
}

if(isset($departamento) && trim($departamento)!="") {
	$where_solicita .= " $and pc10_depto=$departamento ";
}
$where_teste = "";
if(trim($where_solicita) != "") {
	$where_teste = $where_solicita;
}
if($pc30_permsemdotac=='f') {
	$result_test_dot = $clsolicitem->sql_record($clsolicitem->sql_query_dot(
			null,
			"pc11_codigo,
			pc11_quant,
			sum(pc13_quant)",
			"",
			"",
			"
			group by pc10_numero,
			pc10_depto,
			pc11_codigo,
			pc11_quant,
			pc11_numero,
			pc13_codigo
			having   (pc11_quant > sum(pc13_quant) or pc13_codigo is null) and $where_teste
			")
	);


	if($clsolicitem->numrows > 0) {
		//    db_redireciona("db_erros.php?fechar=true&db_erro=Existe item sem dota��o ou sem quantidade total lan�ada em dota��o!!");
		$lista_itens = "";
		$virgula     = "";
		$cod_item    = "";
		for($i = 0; $i < pg_numrows($result_test_dot); $i++) {

			db_fieldsmemory($result_test_dot,$i);
			if ($cod_item != $pc11_codigo) {

				$cod_item     = $pc11_codigo;
				$lista_itens .= $virgula.$cod_item;
				$virgula      = ",";
			}
		}
		$dbwhere   = $where_teste . " and pc11_codigo in ($lista_itens)";


		$res_itens = $clsolicitem->sql_record($clsolicitem->sql_query_pcmater(null,"distinct pc01_codmater,pc01_descrmater,pc11_codigo",null,$dbwhere));
		die ($clsolicitem->sql_query_pcmater(null,"distinct pc01_codmater,pc01_descrmater,pc11_codigo",null,$dbwhere));
		$erro_msg  = "";
		if ($clsolicitem->numrows > 0) {

			$erro_msg = "Verifique o(s) item(ns) ";
			$virgula  = "";
			for($i = 0; $i < pg_numrows($res_itens); $i++) {

				db_fieldsmemory($res_itens,$i);
				$erro_msg .= $virgula."<b>".$pc01_codmater." - ".$pc01_descrmater."</b>";
				$virgula   = ",";
			}
			$erro_msg .= ". Estes item(ns) podem estar sem dota��o ou sem quantidade total lan�ada em dota��o!!";
		}

		if ($erro_msg == "") {
			$erro_msg = "Existe item sem dota��o ou sem quantidade total lan�ada em dota��o!!";
		}
		db_redireciona("db_erros.php?fechar=true&db_erro=".$erro_msg);
	}
}

$where_solicita .= $and." pc10_correto='t' ";

$sCampos  = "pc10_numero, pc10_data, pc10_resumo, pc12_vlrap, descrdepto, coddepto, nomeresponsavel, pc50_descr, pc10_login, nome,";
$sCampos .= "pc10_solicitacaotipo,";
$sCampos .= "(select pc52_sequencial";
$sCampos .= "   from solicitacaotipo inner join solicita st2 on pc52_sequencial = pc10_solicitacaotipo";
$sCampos .= "  where st2.pc10_numero = (select pc53_solicitapai";
$sCampos .= "                             from solicita s inner join solicitavinculo on pc53_solicitafilho = s.pc10_numero";
$sCampos .= "                            where s.pc10_numero = solicita.pc10_numero)) as tiposolicitacaopai,";
$sCampos .= "(select pc53_solicitapai";
$sCampos .= "   from solicita s inner join solicitavinculo on pc53_solicitafilho = s.pc10_numero";
$sCampos .= "  where s.pc10_numero = solicita.pc10_numero) as codigosolicitacaopai";

$result_pesq_solicita = $clsolicita->sql_record($clsolicita->sql_query_solicita(null,$sCampos,'pc10_numero',$where_solicita));
$numrows_solicita     = $clsolicita->numrows;

if($numrows_solicita == 0) {
	db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum Registro Encontrado! Verifique seu departamento. ");
}

$erros = "";

if ($pc30_permsemdotac=='f' ) {

	if ($pc30_gerareserva=='t') {

		if ($pc30_comsaldo=="t") {
			$result_reservasaldo = $clorcreservasol->sql_record($clorcreservasol->sql_query_saldo(
					null,
					null,"
					round(o80_valor,2)  as valorreserva,
					round(pc13_valor,2) as valordotacao,
					pc11_numero,
					pc11_codigo,
					pc11_seq
					",
					"pc11_numero,pc11_codigo",
					$where_solicita));
			$sol = "";
			$vir = "";
			for ($i=0;$i<$clorcreservasol->numrows;$i++) {

				db_fieldsmemory($result_reservasaldo,$i);
				if ($valordotacao>$valorreserva) {

					if ((int)(strlen("<BR>Solicita��o: $pc11_numero<BR>Itens ")+strlen($erros)) < 220 && $sol!=$pc11_numero) {

						$sol = $pc11_numero;
						$vir = "";
						if($erros != "") {
							$erros .= " sem saldo reservado<BR>";
						}
						$erros .= "<BR>Solicita��o: $pc11_numero<BR>Itens ";
					}
					if (strlen($pc11_codigo.$vir)+strlen($erros) < 220) {

						$erros .= $vir." ".$pc11_codigo;
						$vir = ",";
					}
				}
			}
			if ($erros != "" && $clorcreservasol->numrows != 0) {
				$erros .= " sem saldo reservado";
			}
		}
	}
}

// Teste de saldo de reserva e total de dotacao para material de servicos
if (trim($erros) == "") {

	$dbwhere         = "";
	$dbwhere_servico = "";
	if (isset($ini) && trim($ini)!="") {
		$dbwhere = " pc11_numero >= $ini";
	}
	if (isset($fim) && trim($fim)!="") {
		if($dbwhere == ""){
			$dbwhere = " pc11_numero <= $fim";
		}else{
			$dbwhere = " pc11_numero between $ini and $fim";
		}
	}
	if (trim($dbwhere) != "") {
		$dbwhere_servico .= " and pc01_servico = 't'";
	} else {
		$dbwhere_servico = "pc01_servico = 't'";
	}

	$sSubQuery  = "     SELECT *                                                                                            ";
	$sSubQuery .= "       FROM pcprocitem                                                                                   ";
	$sSubQuery .= " INNER JOIN empautitempcprocitem ON empautitempcprocitem.e73_pcprocitem = pcprocitem.pc81_codprocitem    ";
	$sSubQuery .= " INNER JOIN empautoriza          ON empautoriza.e54_autori              = empautitempcprocitem.e73_autori";
	$sSubQuery .= "      WHERE pcprocitem.pc81_solicitem = pc11_codigo                                                      ";
	$sSubQuery .= "        AND empautoriza.e54_anulad    IS NULL                                                            ";

	$res_solservico   = $clsolicitem->sql_record($clsolicitem->sql_query_serv(null,"pc11_codigo as codsol,pc11_vlrun","pc11_codigo",$dbwhere.$dbwhere_servico));
	$res_reservasaldo = $clorcreservasol->sql_record($clorcreservasol->sql_query_saldo(null,
			null,"
			round(o80_valor,2)  as valorreserva,
			round(pc13_valor,2) as valordotacao,
			pc11_numero,
			pc11_codigo,
			pc11_seq,
			exists({$sSubQuery}) as autorizado
			",
			"pc11_numero,pc11_codigo",
			$where_solicita
	)
	);

	if ($clsolicitem->numrows > 0) {

		$num_rows = $clsolicitem->numrows;
		if ($clorcreservasol->numrows > 0) {
			$num_rows_res = $clorcreservasol->numrows;
		}else{
			$num_rows_res = 0;
		}
		$linha = 0;
		$autorizado = '';
		for($i = 0; $i < $num_rows; $i++) {

			db_fieldsmemory($res_solservico,$i);
			$total_dotacao = 0;
			$total_reserva = 0;
			if ($autorizado == 't') {
				continue;
			}

			for ($ii = $linha; $ii < $num_rows_res; $ii++) {

				db_fieldsmemory($res_reservasaldo,$ii);
				if ($codsol == $pc11_codigo) {

					$total_dotacao += $valordotacao;
					$total_reserva += $valorreserva;
					$linha = $ii;
					$linha++;
				} else {
					break;
				}
			}

			if (($total_reserva < $pc11_vlrun || $total_reserva > $pc11_vlrun) ||
					($total_dotacao < $pc11_vlrun || $total_dotacao > $pc11_vlrun)){

				if ($total_reserva == 0 && $total_dotacao == 0) {
					$erros = "";
				} else if ($total_reserva < $pc11_vlrun || $total_reserva > $pc11_vlrun) {
					$erros = "Valor total de RESERVAS ";
				} else if ($total_dotacao < $pc11_vlrun || $total_dotacao > $pc11_vlrun) {
					$erros = "Valor total de DOTA��O(�ES) ";
				}

				if (trim($erros) != "") {
					$erros .= "difere do valor total do servi�o";
				}
			}

			if (trim($erros) != "") {
				break;
			}
		}
	}
}

// $nValorUnitario = "";
// if (isset($valor_orcado) && $valor_orcado == 't') {
//   $sSqlBuscaValorOrcado = $clsolicita->sql_query_orcamento_julgamento(null, pc23_vlrun, null, $where_solicita);
//   $rsBuscaValorOrcado   = $clsolicita->sql_record($sSqlBuscaValorOrcado);
// }

if ($erros != "") {
	db_redireciona("db_erros.php?fechar=true&db_erro=$erros");
}

$pdf  = new scpdf();
$pdf->Open();
$pdf1 = new db_impcarne($pdf,'17');
//$pdf1->modelo = 17;
$pdf1->objpdf->SetTextColor(0,0,0);
$pdf1->Snumero_ant = "";

$pdf1->casadec    = @$e30_numdec;
for ($contador = 0; $contador < $numrows_solicita; $contador++) {

	db_fieldsmemory($result_pesq_solicita,$contador);
	$pdf1->prefeitura = $nomeinst;
	$pdf1->logo			  = $logo;
	$pdf1->enderpref  = $ender;
	$pdf1->municpref  = $munic;
	$pdf1->telefpref  = $telef;
	$pdf1->emailpref  = $email;
	$pdf1->emissao    = date("Y-m-d",db_getsession("DB_datausu"));
	$pdf1->cgcpref    = $cgc;
	$sec  = "______________________________"."\n"."Secretaria da Fazenda";
	$pref = "______________________________"."\n"."Prefeito";

	$pdf1->secfaz     = $classinatura->assinatura(1002);
	$pdf1->nompre     = $classinatura->assinatura(1000);

	$pdf1->Snumero     = $pc10_numero;
	$pdf1->Sdata       = $pc10_data;
	$pdf1->Svalor      = $pc12_vlrap;
	$pdf1->Sresumo     = $pc10_resumo;
	$pdf1->Stipcom     = $pc50_descr;
	$pdf1->Sdepart     = $descrdepto;
	$pdf1->Scoddepart  = $coddepto;
	$pdf1->Srespdepart = $nomeresponsavel;
	$pdf1->Susuarioger = $nome;
	$pdf1->iTipo       = $pc10_solicitacaotipo;
	$pdf1->Stiposolicitacaopai   = $tiposolicitacaopai;
	$pdf1->Scodigosolicitacaopai = $codigosolicitacaopai;

	/**
	 * Busca processo administrativo
	 */
	$oDaoProcessoAdministrativo   = db_utils::getDao("solicitaprotprocesso");
	$sWhereProcessoAdministrativo = " pc90_solicita = {$pc10_numero}";
	$sSqlProcessoAdministrativo   = $oDaoProcessoAdministrativo->sql_query_file(null, "pc90_numeroprocesso", null,
			$sWhereProcessoAdministrativo);
	$rsProcessoAdministrativo     = $oDaoProcessoAdministrativo->sql_record($sSqlProcessoAdministrativo);
	$sProcessoAdministrativo      = "";
	if ($oDaoProcessoAdministrativo->numrows > 0) {
		$sProcessoAdministrativo = db_utils::fieldsMemory($rsProcessoAdministrativo, 0)->pc90_numeroprocesso;
	}

	$pdf1->processoAdministrativo = $sProcessoAdministrativo;

	$result_orgunid   = $cldb_departorg->sql_record($cldb_departorg->sql_query_orgunid($coddepto,db_getsession('DB_anousu'),"o40_descr,o41_descr"));
	db_fieldsmemory($result_orgunid,0);
	$pdf1->Sorgao     = $o40_descr;
	$pdf1->Sunidade   = $o41_descr;

	//  die($clsolicitem->sql_query_relmod2(null,"distinct fc_estruturaldotacao(pc13_anousu,pc13_coddot) as estrutural,o55_descr,o15_descr,b.o56_descr as descrestrutural,pc13_codigo,pc13_anousu,pc13_coddot,pc13_quant,pc13_valor,b.o56_elemento as do56_elemento,pc05_servico,pc11_seq,pc11_codigo,pc11_quant,pc11_vlrun,pc11_prazo,pc11_pgto,pc11_resum,pc11_just,m61_abrev,m61_descr,pc17_quant,pc01_codmater,pc01_descrmater,(pc13_valor/pc13_quant) as pc13_valtot,(pc11_vlrun*pc11_quant) as pc11_valtot,m61_usaquant,a.o56_elemento as so56_elemento,a.o56_descr as descrele",'pc13_coddot,pc13_codigo',"pc11_numero=$pc10_numero"));

	$sOrdem  = " pc13_coddot,pc13_codigo ";
	$sWhere  = " pc11_numero = {$pc10_numero} ";
	if (isset($valor_orcado) && $valor_orcado == 't') {
		$sWhere .= " and pc24_pontuacao = 1";
	}

	$sCampos  = " distinct fc_estruturaldotacao(pc13_anousu,pc13_coddot) as estrutural, o55_projativ, o55_descr, ";
	$sCampos .= " o15_codigo, o15_descr, b.o56_descr as descrestrutural, pc13_codigo, pc13_anousu, pc13_coddot, pc13_quant, ";
	$sCampos .= " pc13_valor, b.o56_elemento as do56_elemento, pc01_servico, pc11_seq, pc11_codigo, pc11_quant, ";
	$sCampos .= " pc11_vlrun, pc11_prazo, pc11_pgto, pc11_resum, pc11_just, a.o56_descr as descrele,o41_descr, ";
	$sCampos .= " m61_abrev, m61_descr, pc17_quant, pc01_codmater, pc01_descrmater, (pc13_valor/pc13_quant) as pc13_valtot, ";
	$sCampos .= " (pc11_vlrun*pc11_quant) as pc11_valtot, m61_usaquant,a.o56_elemento as so56_elemento ";

	$sSqlBuscaPcDotac    = $clsolicitem->sql_query_relmod2(null, $sCampos, $sOrdem, $sWhere);
	if (isset($valor_orcado) && $valor_orcado == 't') {

		$sCampos          .= ", pc23_valor, pc23_quant, pc23_vlrun ";
		$sSqlBuscaPcDotac  = $clsolicitem->sql_query_relmod3(null, $sCampos, $sOrdem, $sWhere);
	}
	$result_pesq_pcdotac  = $clsolicitem->sql_record($sSqlBuscaPcDotac);
	$numrows_pcdotac      = $clsolicitem->numrows;

	$pdf1->valor_orcado   = $valor_orcado;
	$pdf1->recorddasdotac = $result_pesq_pcdotac;
	$pdf1->linhasdasdotac = $numrows_pcdotac;
	$pdf1->dcodigo        = 'pc13_codigo';
	$pdf1->dcoddot        = 'pc13_coddot';
	$pdf1->danousu        = 'pc13_anousu';
	$pdf1->dquant         = 'pc13_quant';
	$pdf1->delemento      = 'estrutural';

	$pdf1->descrunid      = 'o41_descr';
	$pdf1->dcprojativ     = 'o55_projativ';
	$pdf1->dctiporec      = 'o15_codigo';
	$pdf1->dprojativ      = 'o55_descr';
	$pdf1->dtiporec       = 'o15_descr';
	$pdf1->ddescrest      = 'descrestrutural';
	$pdf1->item	          = 'pc11_seq';

	if (isset($valor_orcado) && $valor_orcado == 't') {

		$pdf1->dvalortot      = 'pc23_vlrun';
		$pdf1->dvalor         = 'pc23_valor';
		$pdf1->quantitem      = 'pc23_quant';
		$pdf1->valoritem      = 'pc23_vlrun';
		$pdf1->svalortot      = 'pc23_valor';
	} else {

		$pdf1->dvalor         = 'pc13_valor';
		$pdf1->dvalortot      = 'pc13_valtot';
		$pdf1->quantitem      = 'pc11_quant';
		$pdf1->valoritem      = 'pc11_vlrun';
		$pdf1->svalortot      = 'pc11_valtot';
	}
	$pdf1->descricaoitem  = 'pc01_descrmater';
	$pdf1->squantunid     = 'pc17_quant';
	$pdf1->sprazo         = 'pc11_prazo';
	$pdf1->spgto          = 'pc11_pgto';
	$pdf1->sresum         = 'pc11_resum';
	$pdf1->sjust          = 'pc11_just';
	$pdf1->sunidade       = 'm61_descr';
	$pdf1->sabrevunidade  = 'm61_abrev';
	$pdf1->sservico       = 'pc01_servico';
	$pdf1->susaquant      = 'm61_usaquant';
	$pdf1->scodpcmater    = 'pc01_codmater';
	$pdf1->selemento      = 'so56_elemento';
	$pdf1->sdelemento     = 'descrele';

	$result_pesq_pcsugforn = $clpcsugforn->sql_record($clpcsugforn->sql_query($pc10_numero,null,"distinct z01_numcgm,z01_nome,z01_ender,z01_numero,z01_munic,z01_telef,z01_cgccpf",'z01_numcgm'));
	$numrows_pcsugforn = $clpcsugforn->numrows;
	$pdf1->recorddosfornec = $result_pesq_pcsugforn;
	$pdf1->linhasdosfornec = $numrows_pcsugforn;
	$pdf1->cgmforn        = 'z01_numcgm';
	$pdf1->nomeforn       = 'z01_nome';
	$pdf1->enderforn      = 'z01_ender';
	$pdf1->numforn        = 'z01_numero';
	$pdf1->municforn      = 'z01_munic';
	$pdf1->foneforn       = 'z01_telef';
	$pdf1->cgccpf         = 'z01_cgccpf';
	$pdf1->imprime();
	$pdf1->Snumero_ant = $pc10_numero;
}
if(isset($argv[1])){
	$pdf1->objpdf->Output("/tmp/teste.pdf");
}else{


  if ($oConfiguracaoGed->utilizaGED()) {

    try {

      $sTipoDocumento = GerenciadorEletronicoDocumentoConfiguracao::SOLICITACAO_COMPRA;
      $oGerenciador   = new GerenciadorEletronicoDocumento();
      $oGerenciador->setLocalizacaoOrigem("tmp/");
      $oGerenciador->setNomeArquivo("{$sTipoDocumento}_{$pc10_numero}.pdf");

      $oStdDadosGED        = new stdClass();
      $oStdDadosGED->nome  = $sTipoDocumento;
      $oStdDadosGED->tipo  = "NUMERO";
      $oStdDadosGED->valor = $pc10_numero;

      $pdf1->objpdf->Output("tmp/{$sTipoDocumento}_{$pc10_numero}.pdf");

      $oGerenciador->moverArquivo(array($oStdDadosGED));

    } catch (Exception $eErro) {
      db_redireciona("db_erros.php?fechar=true&db_erro=".$eErro->getMessage());
    } catch (SoapFault $eSFErro) {
      db_redireciona("db_erros.php?fechar=true&db_erro=".$eSFErro->getMessage());
    }
  } else {
    $pdf1->objpdf->Output();
  }
}
?>