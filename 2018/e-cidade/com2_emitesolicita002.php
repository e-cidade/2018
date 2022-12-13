<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require_once(modification("fpdf151/scpdf.php"));
require_once(modification("fpdf151/impcarne.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("classes/db_solicita_classe.php"));
require_once(modification("classes/db_solicitem_classe.php"));
require_once(modification("classes/db_pcdotac_classe.php"));
require_once(modification("classes/db_pcsugforn_classe.php"));
require_once(modification("classes/db_db_departorg_classe.php"));
require_once(modification("classes/db_orcreservasol_classe.php"));
require_once(modification("classes/db_pcparam_classe.php"));
require_once(modification("classes/db_empparametro_classe.php"));


/*
 * Configurações GED
 */
require_once(modification("integracao_externa/ged/GerenciadorEletronicoDocumento.model.php"));
require_once(modification("integracao_externa/ged/GerenciadorEletronicoDocumentoConfiguracao.model.php"));
require_once(modification("libs/exceptions/BusinessException.php"));

$oGet = db_utils::postMemory($_GET);
$oConfiguracaoGed = GerenciadorEletronicoDocumentoConfiguracao::getInstance();
if ($oConfiguracaoGed->utilizaGED()) {

  if ($oGet->ini != $oGet->fim) {

    $sMsgErro  = "O parâmetro para utilização do GED (Gerenciador Eletrônico de Documentos) está ativado.<br><br>";
    $sMsgErro .= "Neste não é possível informar interválos de códigos ou datas.<br><br>";
    db_redireciona("db_erros.php?fechar=true&db_erro={$sMsgErro}");
    exit;
  }
}

$clsolicita       = new cl_solicita;
$clsolicitem      = new cl_solicitem;
$clpcdotac        = new cl_pcdotac;
$clpcsugforn      = new cl_pcsugforn;
$cldb_departorg   = new cl_db_departorg;
$classinatura     = new cl_assinatura;
$clorcreservasol  = new cl_orcreservasol;
$clpcparam        = new cl_pcparam;
$clempparametro	  = new cl_empparametro;



$sqlpref = "select * from db_config where codigo = ".db_getsession("DB_instit");
$resultpref = db_query($sqlpref);
db_fieldsmemory($resultpref,0);

$result02 = $clempparametro->sql_record($clempparametro->sql_query_file(db_getsession("DB_anousu"),"e30_nroviaaut,e30_numdec"));
if($clempparametro->numrows>0){
  db_fieldsmemory($result02,0);
}

$result_pcparam = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"),"pc30_comsaldo,pc30_permsemdotac,pc30_gerareserva,pc30_libdotac"));
db_fieldsmemory($result_pcparam,0);

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$where_solicita = "";
if(isset($ini) && trim($ini)!=""){
  $where_solicita = " pc10_numero >= $ini";
}
if(isset($fim) && trim($fim)!=""){
  if($where_solicita == ""){
    $where_solicita = " pc10_numero <= $fim";
  }else{
    $where_solicita = " pc10_numero between $ini and $fim";
  }
}

$and = "";
if($where_solicita!=""){
  $and = " and ";
}
if(isset($departamento) && trim($departamento)!=""){
  $where_solicita .= " $and pc10_depto=$departamento ";
  $and = " and ";
}

$where_teste = "";
if(trim($where_solicita) != ""){
  $where_teste = $where_solicita;
}
if($pc30_permsemdotac=='f'){

  $result_test_dot = $clsolicitem->sql_record(
                                              $clsolicitem->sql_query_dot(
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
                                                                           "));
  if($clsolicitem->numrows > 0){
//    db_redireciona("db_erros.php?fechar=true&db_erro=Existe item sem dotação ou sem quantidade total lançada em dotação!!");

      $lista_itens = "";
      $virgula     = "";
      $cod_item    = "";
      for($i = 0; $i < pg_numrows($result_test_dot); $i++){
	   db_fieldsmemory($result_test_dot,$i);
	   if ($cod_item != $pc11_codigo){
	        $cod_item     = $pc11_codigo;
	        $lista_itens .= $virgula.$cod_item;
	        $virgula      = ",";
	   }
      }
      $dbwhere   = $where_teste . " and pc11_codigo in ($lista_itens)";
      $res_itens = $clsolicitem->sql_record($clsolicitem->sql_query_pcmater(null,"distinct pc01_codmater,pc01_descrmater,pc11_codigo",null,$dbwhere));
      $erro_msg  = "";
      if ($clsolicitem->numrows > 0){
	   $erro_msg = "Verifique o(s) item(ns) ";
	   $virgula  = "";
	   for($i = 0; $i < pg_numrows($res_itens); $i++){
 	        db_fieldsmemory($res_itens,$i);
	        $erro_msg .= $virgula."<b>".$pc01_codmater." - ".$pc01_descrmater."</b>";
		$virgula   = ",";
	   }
	   $erro_msg .= ". Estes item(ns) podem estar sem dotação ou sem quantidade total lançada em dotação!!";
      }

      if ($erro_msg == ""){
	   $erro_msg = "Existe item sem dotação ou sem quantidade total lançada em dotação!!";
      }

      db_redireciona("db_erros.php?fechar=true&db_erro=".$erro_msg);
  }
}
$where_solicita .= $and." pc10_correto='t' ";

//die($clsolicita->sql_query_solicita(null," distinct pc10_numero,pc10_data,pc10_resumo,pc12_vlrap,descrdepto,coddepto,nomeresponsavel,pc50_descr,pc10_login,nome",'pc10_numero',$where_solicita));
$sSqlSolicita         = $clsolicita->sql_query_solicita(null,
                                                        "distinct pc10_numero,
                                                         pc67_sequencial,
                                                         pc10_data,
                                                         pc10_resumo,
                                                         pc12_vlrap,
                                                         descrdepto,
                                                         coddepto,
                                                         pc10_solicitacaotipo,
                                                         nomeresponsavel,
                                                         pc50_descr,
                                                         pc10_login,
                                                         nome,
                                                         o78_pactoplano,
                                                         o74_descricao,
																												 pc90_numeroprocesso",'pc10_numero',$where_solicita);

$result_pesq_solicita = $clsolicita->sql_record($sSqlSolicita);
$numrows_solicita = $clsolicita->numrows;
if($numrows_solicita==0){
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum Registro Encontrado! Verifique seu departamento.");
}

$erros = "";
if ($pc30_permsemdotac=='f' || $pc30_comsaldo=="t") {

  if ($pc30_gerareserva=='t') {

    if ($pc30_comsaldo=="t") {

      $sSqlReservaSaldo    = $clorcreservasol->sql_query_saldo(null,
                                                               null,"
                                                               round(o80_valor,2)  as valorreserva,
                                                               round(pc13_valor,2) as valordotacao,
                                                               pc11_numero,
                                                               pc11_codigo,
                                                               pc11_seq
                                                               ",
                                                               "pc11_numero,pc11_codigo",
                                                               $where_solicita .
                                                               " and not exists ( select 1
                                                                                    from solicitem
                                                                                    left join orcreservasol on o82_solicitem = pc11_codigo
                                                                                   where pc11_numero = pc10_numero
                                                                                     and o82_solicitem is null )");
      $result_reservasaldo = $clorcreservasol->sql_record($sSqlReservaSaldo);
      $sol = "";
      $vir = "";
      for($i=0;$i<$clorcreservasol->numrows;$i++){
        db_fieldsmemory($result_reservasaldo,$i);
        if($valordotacao>$valorreserva){
          if((int)(strlen("<BR>Solicitação: $pc11_numero<BR>Itens ")+strlen($erros)) < 220 && $sol!=$pc11_numero){
          	$sol = $pc11_numero;
            $vir = "";
            if($erros != ""){
              $erros .= " sem saldo reservado<BR>";
            }

          	$erros .= "<BR>Solicitação: $pc11_numero<BR>Itens ";
          }
          if(strlen($pc11_codigo.$vir)+strlen($erros) < 220){
  	        $erros .= $vir." ".$pc11_codigo;
  	        $vir = ",";
          }
        }
      }
      if($erros != "" && $clorcreservasol->numrows != 0){
        $erros .= " sem saldo reservado";
      }
    }
  }
}

// Teste de saldo de reserva e total de dotacao para material de servicos
if (trim($erros) == ""){
     $dbwhere         = "";
     $dbwhere_servico = "";
     if(isset($ini) && trim($ini)!=""){
         $dbwhere = " pc11_numero >= $ini";
     }
     if(isset($fim) && trim($fim)!=""){
         if($dbwhere == ""){
              $dbwhere = " pc11_numero <= $fim";
         }else{
              $dbwhere = " pc11_numero between $ini and $fim";
         }
     }
     if (trim($dbwhere) != ""){
          $dbwhere_servico .= " and pc01_servico = 't'";
     }else{
          $dbwhere_servico = "pc01_servico = 't'";
     }

     $sSubQuery  = "     SELECT *                                                                                            ";
     $sSubQuery .= "       FROM pcprocitem                                                                                   ";
     $sSubQuery .= " INNER JOIN empautitempcprocitem ON empautitempcprocitem.e73_pcprocitem = pcprocitem.pc81_codprocitem    ";
     $sSubQuery .= " INNER JOIN empautoriza          ON empautoriza.e54_autori              = empautitempcprocitem.e73_autori";
     $sSubQuery .= "      WHERE pcprocitem.pc81_solicitem = pc11_codigo                                                      ";
     $sSubQuery .= "        AND empautoriza.e54_anulad    IS NULL                                                            ";

     $res_solservico    = $clsolicitem->sql_record($clsolicitem->sql_query_serv(null,"pc11_codigo as codsol,pc11_vlrun","pc11_codigo",$dbwhere.$dbwhere_servico));
     $res_reservasaldo  = $clorcreservasol->sql_record($clorcreservasol->sql_query_saldo(null,
                                                                                        null,"
                                                                                        o80_valor  as valorreserva,
                                                                                        pc13_valor as valordotacao,
                                                                                        pc11_numero,
                                                                                        pc11_codigo,
                                                                                        pc11_seq,
                                                                                        exists({$sSubQuery}) as autorizado,
                                                                                        pc11_servicoquantidade",
                                                                                        "pc11_numero,pc11_codigo",
                                                                                        $where_solicita ));

     if ($clsolicitem->numrows > 0){
       $num_rows = $clsolicitem->numrows;

	     $num_rows_res = $clorcreservasol->numrows;

	     $linha = 0;
	     $autorizado = 'false';
       for ($i = 0; $i < $num_rows; $i++){
          db_fieldsmemory($res_solservico,$i);
	        $total_dotacao = 0;
	        $total_reserva = 0;
	        if (@$autorizado == 't') {
	        	continue;
	        }

	        $sServicoQuantidade = 'f';
	        for ($ii = $linha; $ii < $num_rows_res; $ii++){
	          db_fieldsmemory($res_reservasaldo,$ii);
	   	      if ($codsol == $pc11_codigo){

		          $total_dotacao += $valordotacao;
		          $total_reserva += $valorreserva;
			        $linha = $ii;
			        $linha++;
			        $sServicoQuantidade = $pc11_servicoquantidade;
	          } else {
		         break;
		        }
	        }

          if ((round($total_reserva,2) < round($pc11_vlrun,2) || round($total_reserva,2) > round($pc11_vlrun,2)) ||
	           (round($total_dotacao,2) < round($pc11_vlrun,2) || round($total_dotacao,2) > round($pc11_vlrun,2))){

            if ($total_reserva == 0 && $total_dotacao == 0){
		          $erros = "";
		        } else if (round($total_reserva,2) < round($pc11_vlrun,2) || round($total_reserva,2) > round($pc11_vlrun,2) && $sServicoQuantidade != "t"){
		          $erros = "Valor total de RESERVAS ";
		        } else if (round($total_dotacao,2) < round($pc11_vlrun,2) || round($total_dotacao,2) > round($pc11_vlrun,2) && $sServicoQuantidade != "t"){
		          $erros = "Valor total de DOTAÇÃO(ÕES) ";
		        }

            if (trim($erros) != ""){
		          $erros .= "difere do valor total do serviço";
	          }
          }

	        if (trim($erros) != ""){
		       break;
	        }
	     }
     }
}

// exit;
if($erros != ""){
  db_redireciona("db_erros.php?fechar=true&db_erro=$erros");
}


$pdf = new scpdf();
$pdf->Open();
$pdf->SetAutoPageBreak(false);
$pdf1 = new db_impcarne($pdf, '11');
//$pdf1->modelo = 11;
$pdf1->objpdf->SetTextColor(0,0,0);
$pdf1->Snumero_ant = "";
$pdf1->logo        = $logo;
for($contador=0;$contador<$numrows_solicita;$contador++) {

  db_fieldsmemory($result_pesq_solicita,$contador);

  /**
   * Verifica o número da licitacao
   */
  $pdf1->iNumeroLicitacao     = null;
  $pdf1->sModalidadeLicitacao = null;
  if ($pc10_solicitacaotipo == 5) {

    $sSqlLicitacao  = "select distinct l20_codigo,";
    $sSqlLicitacao .= "       l03_descr";
    $sSqlLicitacao .= "  from solicitavinculo ";
    $sSqlLicitacao .= "       inner join solicitem  on pc53_solicitapai  = pc11_numero ";
    $sSqlLicitacao .= "       inner join pcprocitem on pc11_codigo       = pc81_solicitem ";
    $sSqlLicitacao .= "       inner join liclicitem on pc81_codprocitem  = l21_codpcprocitem ";
    $sSqlLicitacao .= "       inner join liclicita  on l21_codliclicita = l20_codigo";
    $sSqlLicitacao .= "       inner join cflicita   on l20_codtipocom    = l03_codigo";
    $sSqlLicitacao .= " where pc53_solicitafilho = {$pc10_numero}";
    //echo $sSqlLicitacao."<br>";
    $rsLicitacao   = db_query($sSqlLicitacao);
    if (pg_num_rows($rsLicitacao) > 0) {

      $oLicitacao = db_utils::fieldsMemory($rsLicitacao, 0);
      $pdf1->iNumeroLicitacao     = $oLicitacao->l20_codigo;
      $pdf1->sModalidadeLicitacao = substr($oLicitacao->l03_descr, 0, 19);
    }

  }
  $pdf1->anulada    = !empty($pc67_sequencial);
  $pdf1->prefeitura = $nomeinst;
  $pdf1->enderpref  = trim($ender).",".$numero;
  $pdf1->municpref  = $munic;
  $pdf1->telefpref  = $telef;
  $pdf1->iTipo      = $pc10_solicitacaotipo;
  $pdf1->emailpref  = $email;
  $pdf1->emissao    = date("Y-m-d",db_getsession("DB_datausu"));
  $pdf1->cgcpref    = $cgc;
  $sec  = "______________________________"."\n"."Secretaria da Fazenda";
  $pref = "______________________________"."\n"."Prefeito";


  $pdf1->casadec     = $e30_numdec;
  $pdf1->secfaz      = $classinatura->assinatura(1002);
  $pdf1->nompre      = $classinatura->assinatura(1000);

  $pdf1->Snumero     = $pc10_numero;
  $pdf1->processo_administrativo = $pc90_numeroprocesso;
  $pdf1->Sdata       = $pc10_data;
  $pdf1->Svalor      = $pc12_vlrap;
  $pdf1->Sresumo     = substr(stripslashes(addslashes($pc10_resumo)),0,735);
  $pdf1->Stipcom     = $pc50_descr;
  $pdf1->Sdepart     = $descrdepto;
  $pdf1->Srespdepart = $nomeresponsavel;
  $pdf1->SdescrPacto = $o74_descricao;
  $pdf1->iPlanoPacto = $o78_pactoplano;
  $pdf1->Susuarioger = $nome;

  $pdf1->iCodigoDepartamento     = $coddepto;

  $V_item	      = 0;
  $q_quant	    = 0;
  $v_unit		    = 0;
  $v_aproximado = 0;


  $sCamposItem  = " pc11_codigo as cod_sol, pc11_quant as quant, pc11_vlrun as v_unit, ";
  $sCamposItem .= " (pc11_quant * pc11_vlrun ) as v_total";

  $sSqlItemValor = $clsolicitem->sql_query_file(null,$sCamposItem,"pc11_codigo","pc11_numero = {$pc10_numero}");
  $result 	  	 = $clsolicitem->sql_record($sSqlItemValor);

  if ($clsolicitem->numrows>0){
    for ($i=0;$i<$clsolicitem->numrows;$i++){
      db_fieldsmemory($result,$i);
      $v_aproximado += number_format($v_total,2,'.','');
    }
  }

  $pdf1->Svaloraprox= $v_aproximado;

  $result_orgunid   = $cldb_departorg->sql_record($cldb_departorg->sql_query_orgunid($coddepto,db_getsession('DB_anousu'),"o40_descr,o41_descr"));
  db_fieldsmemory($result_orgunid,0);
  $pdf1->Sorgao     = $o40_descr;
  $pdf1->Sunidade   = $o41_descr;

  $sWhereBuscaItem  = "     pc11_numero = {$pc10_numero} ";

  /**
  *
  * Verifica se há fornecedor julgado,
  * Se houver, deve filtra a query sql_query_gerautsol buscando somente o fornecedor vencedor
  */
  $oPcOrcamento                  = db_utils::getDao('pcorcam');
  $sWhereBuscaFornecedorJulgado  = "pc11_numero between {$oGet->ini} and {$oGet->fim}  and pc24_pontuacao = 1";
  $sSqlBuscaFornecedorJulgado    = $oPcOrcamento->sql_query_valitemjulgsol(null, "pc24_pontuacao", null, $sWhereBuscaFornecedorJulgado);
  $rsBuscaFornecedorJulgado      = $oPcOrcamento->sql_record($sSqlBuscaFornecedorJulgado);
  $iBuscaFornecedorJulgado       = $oPcOrcamento->numrows;

  if ($iBuscaFornecedorJulgado > 0 ) {
    $sWhereBuscaItem .= "	and pc24_pontuacao = 1 ";
  }

  $sOrdemBuscaItem  = "pc11_seq";

  $sSqlItem   = $clsolicitem->sql_query_solicitacao_orcamento(null,
      																			"distinct pc01_servico,
      																			 pc11_seq,
      																			 pc11_codigo, pc11_numero,
      																			 pc11_seq,
      																			 pc11_quant,
      																			 pc23_quant,
      																			 pc11_vlrun,
      																			 pc23_vlrun,
      																			 pc11_prazo,
      																			 pc11_pgto,
      																			 pc11_resum,
      																			 pc11_just,
      																			 m61_abrev,
      																			 m61_descr,
      																			 pc17_quant,
      																			 pc01_codmater,
      																			 pc01_descrmater,
                                             pc01_complmater,
                                             pc01_liberaresumo,
      																			 (pc11_quant*pc11_vlrun) as pc11_valtot,
      																			 pc23_valor,
      																			 m61_usaquant,
      																			 o56_elemento as so56_elemento,
      																			 o56_descr as descrele",
      																			$sOrdemBuscaItem,
      																			$sWhereBuscaItem);

  $result_pesq_solicitem = $clsolicitem->sql_record($sSqlItem);

  $numrows_solicitem     = $clsolicitem->numrows;

  if ($numrows_solicitem == 0 && isset($oGet->valor_orcado) && $oGet->valor_orcado == 't') {
    db_redireciona("db_erros.php?fechar=true&db_erro=Não existe orçamento para esta solicitação!!");
  }

  $pdf1->recorddositens  = $result_pesq_solicitem;
  $pdf1->linhasdositens  = $numrows_solicitem;
  $pdf1->item	           = 'pc11_seq';
  $pdf1->quantitem       = 'pc11_quant';
  $pdf1->valoritem       = 'pc11_vlrun';
  $pdf1->svalortot       = 'pc11_valtot';

  /**
   * Imprime valor do orçamento
   */
  if (isset($oGet->valor_orcado) && $oGet->valor_orcado == 't') {

    $pdf1->quantitem      = 'pc23_quant';
    $pdf1->valoritem      = 'pc23_vlrun';
    $pdf1->svalortot      = 'pc23_valor';
  }
  $pdf1->descricaoitem  = 'pc01_descrmater';
  $pdf1->pc11_codigo    = 'pc11_codigo';
  $pdf1->pc11_numero    = 'pc11_numero';
  $pdf1->squantunid     = 'pc17_quant';
  $pdf1->sprazo         = 'pc11_prazo';
  $pdf1->spgto          = 'pc11_pgto';
  $pdf1->sresum         = 'pc11_resum';
  $pdf1->sComplemento   = 'pc01_complmater';
  $pdf1->lLiberaresumo  = 'pc01_liberaresumo';
  $pdf1->sjust          = 'pc11_just';
  $pdf1->sunidade       = 'm61_descr';
  $pdf1->sabrevunidade  = 'm61_abrev';
  $pdf1->sservico       = 'pc01_servico';
  $pdf1->susaquant      = 'm61_usaquant';
  $pdf1->scodpcmater    = 'pc01_codmater';
  $pdf1->selemento      = 'so56_elemento';
  $pdf1->sdelemento     = 'descrele';


  $result_pesq_pcdotac = $clpcdotac->sql_record($clpcdotac->sql_query_dotreserva(
                                                 null,
                                                 null,
                                                 null,
                                                 "pc13_codigo,
                                                 pc13_anousu,
                                                 pc13_coddot,
                                                 pc13_quant,
                                                 pc13_valor,
                                                 pc19_orctiporec,
                                                 o58_projativ,
                                                 o55_descr,
                                                 o56_elemento as do56_elemento",
                                                 'pc13_codigo',"pc11_numero=$pc10_numero"));


  $numrows_pcdotac          = $clpcdotac->numrows;
  $pdf1->recorddasdotac     = $result_pesq_pcdotac;
  $pdf1->linhasdasdotac     = $numrows_pcdotac;
  $pdf1->dcodigo            = 'pc13_codigo';
  $pdf1->dcoddot            = 'pc13_coddot';
  $pdf1->danousu            = 'pc13_anousu';
  $pdf1->dquant             = 'pc13_quant';
  $pdf1->projAtividade      = 'o58_projativ';
  $pdf1->projAtividadeDescr = 'o55_descr';
  $pdf1->dcontrap           = 'pc19_orctiporec';
  $pdf1->dvalor             = 'pc13_valor';
  $pdf1->delemento          = 'do56_elemento';

  $result_pesq_pcsugforn = $clpcsugforn->sql_record($clpcsugforn->sql_query($pc10_numero,null,"distinct z01_numcgm,z01_nome,z01_ender,z01_numero,z01_munic,z01_telef,z01_cgccpf",'z01_numcgm'));
  $numrows_pcsugforn = $clpcsugforn->numrows;

	  $pdf1->recorddosfornec = $result_pesq_pcsugforn;
	  $pdf1->linhasdosfornec = $numrows_pcsugforn;
	  $pdf1->cgmforn         = 'z01_numcgm';
	  $pdf1->nomeforn        = 'z01_nome';
	  $pdf1->enderforn       = 'z01_ender';
	  $pdf1->numforn         = 'z01_numero';
	  $pdf1->municforn       = 'z01_munic';
	  $pdf1->foneforn        = 'z01_telef';
	  $pdf1->cgccpf          = 'z01_cgccpf';
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
    }
  } else {
    $pdf1->objpdf->Output();
  }
}
?>