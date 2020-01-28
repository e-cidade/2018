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

include(modification("fpdf151/pdf.php"));
include(modification("fpdf151/assinatura.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("libs/db_libcaixa_ze.php"));
include(modification("libs/db_libgertxtfolha.php"));
include(modification("classes/db_folha_classe.php"));
include(modification("classes/db_pensao_classe.php"));
include(modification("classes/db_rharqbanco_classe.php"));
include(modification("classes/db_orctiporec_classe.php"));
require_once modification("libs/db_utils.php");
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
db_postmemory($_POST);


$cllayouts_bb  = new LayoutBB;
$cllayout_BBBS = new LayoutBBBSFolha;
$clfolha       = new cl_folha;
$clpensao      = new cl_pensao;
$clrharqbanco  = new cl_rharqbanco;
$clorctiporec  = new cl_orctiporec;
$clrotulo = new rotulocampo;
$clrotulo->label("rh01_regist");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("z01_cgccpf");
$clrotulo->label("r38_liq");
$clrotulo->label("r38_banco");
$clrotulo->label("r38_agenc");
$clrotulo->label("r38_conta");
$clrotulo->label("r70_descr");

$sqlerro = false;

db_sel_instit();

//die($clrharqbanco->sql_query($rh34_codarq));
$result_arqbanco=$clrharqbanco->sql_record($clrharqbanco->sql_query($rh34_codarq));
if($clrharqbanco->numrows>0){

  db_fieldsmemory($result_arqbanco,0);

    include(modification("dbforms/db_layouttxt.php"));
    $posicao  = "A";
    if(isset($layout) && $layout == 9){
      $layoutimprime = 9;
    }else{
      $layoutimprime = 18;
      $posicao = "E";
    }


    $numparcelas = '01';
    $finalidadedoc = '06';
    $idcaixa = "P";
    $idcliente = "P";
    $db90_codban = $rh34_codban;
    $agenciaheader = $rh34_agencia;
    $contaheader = $rh34_conta;
    $contalote   = $rh34_conta;

    //// verificar como se dá o final do numero do convenio.
    $conveniobanco = substr($rh34_convenio,0,12);

    $descrarquivo = "FOLHA PAGAMENTO"; // Campo somente do layout 3

    $db90_descr = 'CAIXA';
    $dvagenciaheader = "0";
    $dvcontaheader   = "0";
    $dvagenciacontaheader = " ";
    if(trim($rh34_dvagencia)!=""){
      $dvagenciaheader = $rh34_dvagencia[0];
    }
    if(trim($rh34_dvconta)!=""){
      $dvcontaheader  = $rh34_dvconta[0];
      $digitos        = strlen($rh34_dvconta);
      if($digitos>1){
        $dvagenciacontaheader = $rh34_dvconta[1];
      }
    }
    $operacaoheader = substr($contaheader,0,3);
    $contaheader2   = str_pad(trim(substr($contaheader,4,20)),8);
    $dvagencialote = $dvagenciaheader;
    $dvcontalote   = $dvcontaheader;
    $dvagenciacontalote = $dvagenciacontaheader;

    $datageracao = $datagera;
    $horageracao = date("H:i:s");

    if(isset($datageracao) && $datageracao!=""){
      $datag = split('-',$datageracao);
      $datag_dia = $datag[2];
      $datag_mes = $datag[1];
      $datag_ano = $datag[0];
    }
    if(isset($datadeposit) && $datadeposit!=""){
      $datad = split('-',$datadeposit);
      $datad_dia = $datad[2];
      $datad_mes = $datad[1];
      $datad_ano = $datad[0];
    }

    $sequencialarq = $rh34_sequencial;
    $usoprefeitura1 = $rh34_sequencial;

    $adatadegeracao = $datag_ano."-".$datag_mes."-".$datag_dia;
    $datadedeposito = $datad_ano."-".$datad_mes."-".$datad_dia;

    $sequenciaarqui = $rh34_sequencial;
    $versaodoarquiv = "030";

    $paramnome = $datag_mes.$datag_ano."_".$horageracao;
    $nomearquivo = "folha_".$db90_codban."_".$paramnome.".txt";
    $db_layouttxt = new db_layouttxt($layoutimprime,"tmp/".$nomearquivo, $posicao);

    //// verificar como se dá o final do numero do convenio.
    $conveniobanco = substr($rh34_convenio,0,12);

    ////// DADOS SOMENTE CNAB240 CEF
//    $parametrotransmiss = substr($rh34_convenio,10,2);
    $indicaambcaixa   = "P";
    $indicaambcliente = "P";
    $densidadearquivo = "01600";
    //////

    db_setaPropriedadesLayoutTxt($db_layouttxt,1);
} else {
  $sqlerro = true;
  $erro_msg = "Arquivo não encontrado";
}

if (!isset($rh34_where) || (isset($rh34_where) && trim($rh34_where) == "")) {

  $rh34_wherefolha = "";
  $rh34_wherepensa = "";
} else {

  $rh34_wherefolha = $rh34_where." and ";
  $rh34_wherepensa = "";
}

$rh34_wherefolha.= " r38_banco = '$rh34_codban' ";
$rh34_wherepensa.= " r52_codbco = '$rh34_codban' and r52_anousu = ".db_anofolha()." and r52_mesusu = ".db_mesfolha();
if($tiparq == 0){
  $titrelatorio = "Todos os funcionários";
  $titarquivo   = "pagtofuncionarios";
}

$lPensionista = false;

if($sqlerro == false){

  if($tiparq == 0) {

    $rh34_wherefolha .= " and r38_liq > 0 ";

    $sql = $clfolha->sql_query_gerarqbag(null,"folha.*,cgm.*, length(trim(r38_agenc)) as qtddigitosagencia,
                                               r70_descr,
                                               length(trim(z01_cgccpf)) as tam,
                                               r38_liq as valorori",
                                              "r38_banco,r38_nome",
                                              "$rh34_wherefolha");
    // echo $sql;exit;
    $result  = $clfolha->sql_record($sql);

    $numrows = $clfolha->numrows;
  } else {

    if($tiparq == 1){
      $titarquivo = "pensaojudicial";
      $titrelatorio = "PENSÃO JUDICIAL";
    }
    if($qfolha == 1){
      $campovalor = " r52_valor+r52_valfer ";
      $rh34_wherepensa .= " and (r52_valor > 0 or r52_valfer > 0 ) ";
    }else if($qfolha == 2){
      $campovalor = " r52_valcom ";
      $rh34_wherepensa .= " and r52_valcom > 0 ";
    }else if($qfolha == 3){
      $campovalor = " r52_val13 ";
      $rh34_wherepensa .= " and r52_val13 > 0 ";
    }else if($qfolha == 4){
      $campovalor = " r52_valres ";
      $rh34_wherepensa .= " and r52_valres > 0 ";
    }else if($qfolha == 5){
      $campovalor = " r52_valor  ";
      $rh34_wherepensa .= " and r52_valor > 0 ";
    }
    
    /**
     * Se a variável $DB_COMPLEMENTAR estiver setada, o valor do "r38_liq" será da tabela ("rhhistoricopensao")
     * lembrando que a folha de pagamento precisa ter registros na geração de disco ("folhapagamentogeracao").
     */
    if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
      
      switch ($qfolha) {
        
          case 1:
            $iTipoFolha  = FolhaPagamento::TIPO_FOLHA_SALARIO;
            $sOperacao   = "+r52_valfer";
            break;
          
          case 2: 
            $iTipoFolha  = FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR; 
            $sOperacao   = "";
            break;
          
          case 5: 
            $iTipoFolha  = FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR;
            $sOperacao   = "";
            break;  
      }
      
      $sCampo  = "(                                                                                      \n";
      $sCampo .= " SELECT SUM(rh145_valor)                                                               \n";
      $sCampo .= "   FROM rhhistoricopensao                                                              \n";
      $sCampo .= "        INNER JOIN rhfolhapagamento       ON rh141_sequencial = rh145_rhfolhapagamento \n";
      $sCampo .= "        INNER JOIN folhapagamentogeracao  ON rh141_sequencial = rh146_folhapagamento   \n";
      $sCampo .= "  WHERE rh141_tipofolha = {$iTipoFolha}                                                \n";
      $sCampo .= "    and rh141_aberto is false                                                          \n";
      $sCampo .= "    and rh145_pensao    = r52_sequencial                                               \n";
      $sCampo .= " ){$sOperacao}                                                                         \n";
      
      $campovalor = $sCampo;
    }

    $sql = $clpensao->sql_query_gerarqbag(null,null,null,null,"$campovalor as r38_liq, length(trim(r52_codage)||trim(r52_dvagencia)) as qtddigitosagencia,
                                               r52_numcgm as r38_regist,
                                               r52_codbco as r38_banco,
                                         trim(r52_conta)||trim(coalesce(r52_dvconta,'')) as r38_conta,
                                         trim(r52_codage)||trim(coalesce(r52_dvagencia,'')) as r38_agenc,
                                         cgm.*,func.z01_nome as nomefuncionario,
                                               r70_descr,
                                               length(trim(cgm.z01_cgccpf)) as tam,
                                               $campovalor as valorori",
                                              "r52_codbco,cgm.z01_nome",
                                              "$rh34_wherepensa and $campovalor > 0");

    $lPensionista = true;

    $result  = $clpensao->sql_record($sql);
    $numrows = $clpensao->numrows;
  }
  if($numrows > 0){
    db_fieldsmemory($result,0);

    $idregistroimpressao = $posicao;
    $nomearquivo_impressao = "/tmp/folha_".$db90_codban."_".$paramnome.".pdf";
    if(!is_writable("/tmp/")){
      $sqlerro= true;
      $erro_msg = 'Sem permissão de gravar o arquivo. Contate suporte.';
    }

    ///// INICIA IMPRESSÃO DO RELATÓRIO
    $pdf = new PDF();
    $pdf->Open();
    $pdf->AliasNbPages();
    $pdf->setfillcolor(235);
    $total = 0;
    $alt = 4;

    $head3 = "ARQUIVO PAGAMENTO FOLHA";
    $head5 = "SEQUENCIAL DO ARQUIVO  :  ".$sequenciaarqui;
    $head6 = "GERAÇÃO  :  ".db_formatar($datagera,"d");
    $head7 = "PAGAMENTO:  ".db_formatar($datadedeposito,"d");
    $head8 = 'BANCO : '.$rh34_codban.' - '.$db90_descr;


    $loteservic = 1;
    $finalidadedoc = "06";
    $codigocompromisso = substr($conveniobanco,8,4);
    $tipocompromisso = substr($conveniobanco,6,2);
    $agencialote = $agenciaheader;

    $loteservico = 1;
    $tiposervico = "30";
    if ($db90_codban == $r38_banco) {
      $formalancamento = "01";
    } else {
      $formalancamento = "03";
    }
    ///// HEADER DO LOTE
    db_setaPropriedadesLayoutTxt($db_layouttxt, 2);
    ///// FINAL DO HEADER DO LOTE

    $sequencialnolote       = 0;
    $quantidadefuncionarios = 0;
    $valortotal             = 0;

    $oDaoPesMov = db_utils::getDao('rhpessoalmov');
    $oDaoPensao = db_utils::getDao('pensao');

    for($i=0;$i<$numrows;$i++) {

      db_fieldsmemory($result,$i);
      //////////////////////////////////////////////
      // CAMPOS LAYOUT CEF
      $agencia = substr(db_formatar(str_replace('.','',str_replace('-','',$r38_agenc)),'s','0', 5,'e',0),1,4);

      $conta   = trim(str_replace(',','',str_replace('.','',str_replace('-','',$r38_conta))));
      $qtddigitosconta = strlen($conta) - 4; /////// -4, pois -1 é do dvconta e -3 do codigooperacao
      $dvconta = substr($conta,-1);
      $codigooperacao = substr($conta,0,3);
      $conta = substr($conta, 3, $qtddigitosconta);

      //////////////////////////////////////////////
      // CAMPOS LAYOUT CNAB240
      $sequencialnolote ++;
      $compensacao = "700";
      if($r38_banco == $db90_codban){
        $compensacao = str_repeat('0',3);
      }

      $agenciapagarT = db_formatar(str_replace('.','',str_replace('-','',$r38_agenc)),'s','0', 6,'e',0);
      $contasapagarT = db_formatar(str_replace('.','',str_replace('-','',$r38_conta)),'s','0',15,'e',0);
      $agenciapagar  = substr($agenciapagarT,0,5);
      $digitoagenci  = substr($agenciapagarT,5,1);
      $contasapagar  = substr($contasapagarT,0,14)+0;
      $digitocontas  = substr($contasapagarT,14,1);
      $contasapagar  = db_formatar($contasapagar,'s','0',12,'e',0);
      $bancofavorecido     = $r38_banco;
      $agenciafavorecido   = $agenciapagar;
      $dvagenciafavorecido = $digitoagenci;
      $contafavorecido     = $contasapagar;
      $dvcontafavorecido   = $digitocontas;
      $dvagenciacontafav   = " ";
      $numerocontrolemov   = $r38_regist;
      $sequencialreg       = $i + 1;
      $nroagendacliente    = $sequencialreg;
      $valordebito         = $r38_liq;
      $dataprocessamento   = $datadedeposito;

      /**
       * Conversado com a Mari, Anderson, Robson e Andrio sobre o problema das agencias nos arquivos de banco
       * Foi visto que na tabela 'folha' os dados estao sendo inseridos erroneamente.
       * Foi resolvido que provisoriamente, buscariamos da tabela 'rhpesbanco' até a rotina Geracao em Disco (Novo)
       * estiver concluida.
       *
       * Logica abaixo replicada em cada arquivo de geracao de banco
       */

      $iAgencia       = '';
      $iDigitoAgencia = 0;
      $rsAgencia      = null;
      $sNomeServidor  = '';

      if ($lPensionista) {

        $sNomeServidor  = $nomefuncionario;

        $sCampos = "r52_codage as agencia, r52_dvagencia as digito";
        $sWhere  = "     r52_numcgm = {$r38_regist}";  // quando é pensão é criado um alias do cgm para r38_regist
        $sWhere .= " and r52_anousu = " . db_anofolha();
        $sWhere .= " and r52_mesusu = " . db_mesfolha();

        $sSqlPensao = $oDaoPensao->sql_query_file(null, null, null, null, $sCampos, null, $sWhere);
        $rsAgencia  = $oDaoPensao->sql_record($sSqlPensao);

        if ($oDaoPensao->numrows < 1) {

          $sMsgErro  = "Não foi encontrada a agencia do pensionista: ";
          $sMsgErro .= "{$r38_regist} - {$sNomeServidor}.\\nFavor verificar.";
          db_msgbox($sMsgErro);
          exit;
        }

      } else {

        $sNomeServidor = $r38_nome;

        $sCampos = "rh44_agencia as agencia, rh44_dvagencia as digito";
        $sWhere  = "     rh02_regist = {$r38_regist}";
        $sWhere .= " and rh02_anousu = " . db_anofolha();
        $sWhere .= " and rh02_mesusu = " . db_mesfolha();

        $sSqlAgencia = $oDaoPesMov->sql_query_dados_bancario(null, null, $sCampos, null, $sWhere);
        $rsAgencia   = $oDaoPesMov->sql_record($sSqlAgencia);

        if ($oDaoPesMov->numrows != 1) {

          $sMsgErro  = "Não foi encontrada a agencia do servidor: ";
          $sMsgErro .= "{$r38_regist} - {$r38_nome}.\\nFavor verificar.";
          db_msgbox($sMsgErro);
          exit;
        }
      }

      $oDadosAgencia  = db_utils::fieldsMemory($rsAgencia, 0);
      $iAgencia       = (int) $oDadosAgencia->agencia;
      $iDigitoAgencia = (int) $oDadosAgencia->digito;

      if (strlen($iAgencia) > 5) {

        $sMsgErro  = "Agencia inconsistente. Favor verificar: \\n ";
        $sMsgErro .= "Servidor: {$r38_regist} - {$sNomeServidor}, agencia: {$oDadosAgencia->rh44_agencia}";
        db_msgbox($sMsgErro);
        exit;
      }

      if (strlen($iDigitoAgencia) > 1) {

        $sMsgErro  = "Digito da Agencia inconsistente. Favor verificar: \\n  ";
        $sMsgErro .= "Servidor: {$r38_regist} - {$r38_nome}, digito: {$oDadosAgencia->rh44_dvagencia}\\n  ";
        $sMsgErro .= "Digito tem que ter no máximo 1 casa decimal.";
        db_msgbox($sMsgErro);
        exit;
      }

      $iAgencia = str_pad($iAgencia, 5, '0', STR_PAD_LEFT);


      ///// REGISTRO A
      $oDadosRegistroA = new stdClass();
      $oDadosRegistroA->z01_nome            = $z01_nome           ;
      $oDadosRegistroA->db90_codban         = $db90_codban        ;
      $oDadosRegistroA->loteservico         = $loteservico        ;
      $oDadosRegistroA->agenciapagarT       = $agenciapagarT      ;
      $oDadosRegistroA->registrodetalhelote = $registrodetalhelote;
      $oDadosRegistroA->sequencialnolote    = $sequencialnolote   ;
      $oDadosRegistroA->contasapagarT       = $contasapagarT      ;
      $oDadosRegistroA->agenciapagar        = $agenciapagar       ;
      $oDadosRegistroA->digitoagenci        = $digitoagenci       ;
      $oDadosRegistroA->contasapagar        = $contasapagar       ;
      $oDadosRegistroA->digitocontas        = $digitocontas       ;
      $oDadosRegistroA->contasapagar        = $contasapagar       ;
      $oDadosRegistroA->bancofavorecido     = $bancofavorecido    ;
      $oDadosRegistroA->agenciafavorecido   = $iAgencia           ;
      $oDadosRegistroA->dvagenciafavorecido = $iDigitoAgencia     ;
      $oDadosRegistroA->contafavorecido     = $contafavorecido    ;
      $oDadosRegistroA->dvcontafavorecido   = $dvcontafavorecido  ;
      $oDadosRegistroA->dvagenciacontafav   = $dvagenciacontafav  ;
      $oDadosRegistroA->numerocontrolemov   = $numerocontrolemov  ;
      $oDadosRegistroA->sequencialreg       = $sequencialreg      ;
      $oDadosRegistroA->nroagendacliente    = $nroagendacliente   ;
      $oDadosRegistroA->valordebito         = $valordebito        ;
      $oDadosRegistroA->dataprocessamento   = $dataprocessamento  ;
      $oDadosRegistroA->finalidadedoc       = $finalidadedoc      ;
      $oDadosRegistroA->avisofavorecido     = $avisofavorecido    ;
      $db_layouttxt->setByLineOfDBUtils($oDadosRegistroA, '3', "A");
      $sequencialnolote ++;

      ///// FINAL REGISTRO A

      ///// REGISTRO B

      if($tam == 11){
        $tipoinscricaofav = "1";
      }else if($tam == 14){
        $tipoinscricaofav = "2";
      }else{
        $tipoinscricaofav = "3";
      }

      $valorvencimento = $r38_liq;
      $datavencimento  = $datadedeposito;
      $oDadosRegistroB = new stdClass();
      $oDadosRegistroB->usofebraban2        = $usofebraban2        ;
      $oDadosRegistroB->codigofavorecido    = $codigofavorecido    ;
      $oDadosRegistroB->valormulta          = $valormulta          ;
      $oDadosRegistroB->valormora           = $valormora           ;
      $oDadosRegistroB->valordesconto       = $valordesconto       ;
      $oDadosRegistroB->valorabatimento     = $valorabatimento     ;
      $oDadosRegistroB->valorvencimento     = $valorvencimento     ;
      $oDadosRegistroB->datavencimento      = $datavencimento      ;
      $oDadosRegistroB->z01_uf              = $z01_uf              ;
      $oDadosRegistroB->z01_cep             = $z01_cep             ;
      $oDadosRegistroB->z01_munic           = $z01_munic           ;
      $oDadosRegistroB->z01_bairro          = $z01_bairro          ;
      $oDadosRegistroB->z01_compl           = $z01_compl           ;
      $oDadosRegistroB->z01_numero          = $z01_numero          ;
      $oDadosRegistroB->z01_ender           = $z01_ender           ;
      $oDadosRegistroB->z01_cgccpf          = $z01_cgccpf          ;
      $oDadosRegistroB->tipoinscricaofav    = $tipoinscricaofav    ;
      $oDadosRegistroB->usofebraban1        = $usofebraban1        ;
      $oDadosRegistroB->segmentoregistro    = $segmentoregistro    ;
      $oDadosRegistroB->sequencialnolote    = $sequencialnolote    ;
      $oDadosRegistroB->registrodetalhelote = $registrodetalhelote ;
      $oDadosRegistroB->loteservico         = $loteservico         ;
      $oDadosRegistroB->db90_codban         = $db90_codban         ;
      $db_layouttxt->setByLineOfDBUtils($oDadosRegistroB, '3', "B");


//      db_setaPropriedadesLayoutTxt($db_layouttxt, 3, "B");
      ///// FINAL DO REGISTRO B

      if($i == 0 || $pdf->gety() > $pdf->h - 30) {

        $pdf->addpage("L");
        $pdf->cell(15,$alt,$RLrh01_regist,1,0,"C",1);
        if ($tiparq < 5) {

          $pdf->cell(15,$alt,$RLz01_numcgm,1,0,"C",1);
          $pdf->cell(20,$alt,$RLz01_cgccpf,1,0,"C",1);
          $pdf->cell(65,$alt,$RLz01_nome,1,0,"C",1);
          $pdf->cell(65,$alt,$RLr70_descr,1,0,"C",1);
        } else {

          $pdf->cell(65,$alt,"Pensionista",1,0,"C",1);
          $pdf->cell(65,$alt,"Funcionário",1,0,"C",1);
          $pdf->cell(15,$alt,$RLz01_numcgm,1,0,"C",1);
          $pdf->cell(20,$alt,$RLz01_cgccpf,1,0,"C",1);
        }
        $pdf->cell(17,$alt,$RLr38_liq,1,0,"C",1);
        $pdf->cell(13,$alt,$RLr38_agenc,1,0,"C",1);
        $pdf->cell(20,$alt,$RLr38_conta,1,1,"C",1);
        $pdf->ln(3);
      }

      $pdf->setfont('arial','',7);
      $pdf->cell(15,$alt,$r38_regist,1,0,"C",0);
      if ($tiparq < 5) {

        $pdf->cell(15,$alt,$z01_numcgm,1,0,"C",0);
        $pdf->cell(20,$alt,$z01_cgccpf,1,0,"C",0);
        $pdf->cell(65,$alt,$z01_nome,1,0,"L",0);
        $pdf->cell(65,$alt,$r70_descr,1,0,"L",0);
      } else {

        $pdf->cell(65,$alt,$z01_nome,1,0,"L",0);
        $pdf->cell(65,$alt,$nomefuncionario,1,0,"L",0);
        $pdf->cell(15,$alt,$z01_numcgm,1,0,"C",0);
        $pdf->cell(20,$alt,$z01_cgccpf,1,0,"C",0);
      }
      $pdf->cell(17,$alt,db_formatar($r38_liq,'f'),1,0,"R",0);
      $pdf->cell(13,$alt,"{$iAgencia}-{$iDigitoAgencia}",1,0,"R",0);
      $pdf->cell(20,$alt,$r38_conta,1,1,"R",0);
      $quantidadefuncionarios ++;
      $valortotal += $r38_liq;
    }

    $pdf->setfont('arial','b',8);
    $pdf->cell(160,$alt,'Total de funcionários',1,0,"C",1);
    $pdf->cell(20,$alt,$quantidadefuncionarios,1,0,"R",1);
    $pdf->cell(50,$alt,'',1,1,"C",1);

    $pdf->cell(160,$alt,'Total Geral',1,0,"C",1);
    $pdf->cell(20,$alt,db_formatar($valortotal,'f'),1,0,"R",1);
    $pdf->cell(50,$alt,'',1,1,"C",1);

    $quantidadetotallote = $sequencialnolote + 2;
    $valortotallote = $valortotal;
    ///// TRAILLER DE LOTE
    db_setaPropriedadesLayoutTxt($db_layouttxt, 4);
    ///// FINAL DO TRAILLER DE LOTE



    // VARIAVEIS PARA TRAILLER CEF
    $quanttrailler = $quantidadefuncionarios + 2;
    $valortrailler = $valortotal;
    $sequencialreg += 1;
    $nroagendacliente = $sequencialreg ;
    //////////////////////////////////
    //////////////////////////////////


    // VARIAVEIS PARA TRAILLER CNAB240
    $quantidadelotesarq = 1;
    $quantidaderegistarq = $quantidadetotallote + 2;
    //////////////////////////////////
    //////////////////////////////////


    ///// TRAILLER DE ARQUIVO
    $loteservico = '9999';
    db_setaPropriedadesLayoutTxt($db_layouttxt, 5);
    ///// FINAL DO TRAILLER DE ARQUIVO
    //////////////////////////////////

    $pdf->Output($nomearquivo_impressao,false,true);
  }else{
    $sqlerro = true;
    $erro_msg = "Nenhum registro encontrado. Contate o suporte.";
  }
}
//exit;
  if($sqlerro == false){
    echo "
    <script>
      parent.js_detectaarquivo('tmp/$nomearquivo','$nomearquivo_impressao');
    </script>
    ";
  }else{
    echo "
    <script>
      parent.js_erro('$erro_msg');
    </script>
    ";
  }
  db_fim_transacao($sql);

?>
