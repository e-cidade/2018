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
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

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

if(!$result_arqbanco || pg_num_rows($result_arqbanco) == 0) {
  $sqlerro = true;
  $erro_msg = "Arquivo não encontrado";
}
$clrharqbanco->numrows = pg_num_rows($result_arqbanco);

if($clrharqbanco->numrows>0){

  db_fieldsmemory($result_arqbanco,0);

  if($rh34_codban == "041"){
    $acodigodobanco = $rh34_codban;
    $atipoinscricao = "2";
    $inscricaoprefa = $cgc;
    $aconveniobanco = $rh34_convenio;

    $dvagenciabanco = "0";
    $dvcontadobanco = "0";
    $dvcontaagencia = "0";

    $agenciadobanco = $rh34_agencia;
    $dacontadobanco = $rh34_conta;

    $dvdacontabanco = "0";
    if(trim($rh34_dvconta) != ""){
      $digitos = strlen($rh34_dvconta);
      $dvdacontabanco = $rh34_dvconta[0];
    }
    $dacontadobanco .= $dvdacontabanco;

    if(trim($rh34_dvagencia)!=""){
      $dvagenciabanco = $rh34_dvagencia[0];
    }

    if(trim($rh34_dvconta)!=""){
      $dvcontadobanco = $rh34_dvconta[0];
      $digitos        = strlen($rh34_dvconta);
      if($digitos>1){
        $dvcontaagencia = $rh34_dvconta[1];
      }
    }

    $nomeprefeitura = $nomeinst;
    $descricaobanco = $db90_descr;

    if(isset($datagera) && $datagera!=""){
      $datag = split('-',$datagera);
      $datag_dia=$datag[2];
      $datag_mes=$datag[1];
      $datag_ano=$datag[0];
    }

    if(isset($datadeposit) && $datadeposit!=""){
      $datad = split('-',$datadeposit);
      $datad_dia = $datad[2];
      $datad_mes = $datad[1];
      $datad_ano = $datad[0];
    }

    $adatadegeracao = $datag_ano."-".$datag_mes."-".$datag_dia;
    $datadedeposito = $datad_ano."-".$datad_mes."-".$datad_dia;
    $paramnome = $datag_mes.$datag_ano."_".date("H").date("i");

    $ahoradegeracao = date("H").date("i").date("s");
    $sequenciaarqui = $rh34_sequencial;
    // db_msgbox($sequenciaarqui);
    $versaodoarquiv = "030";

  }

}

if(!isset($rh34_where) || (isset($rh34_where) && trim($rh34_where) == "")){
  $rh34_wherefolha = "";
  $rh34_wherepensa = "";
}else{
  $rh34_wherefolha = $rh34_where." and ";
  $rh34_wherepensa = '' ;
}

$rh34_wherefolha.= " r38_banco = '$rh34_codban' and ";
$rh34_wherefolha.= " r38_liq > 0 ";
$rh34_wherepensa.= " r52_codbco = '$rh34_codban' and r52_anousu = ".db_anofolha()." and r52_mesusu = ".db_mesfolha();
$titrelatorio = "Todos os funcionários";
$titarquivo   = "pagtofuncionarios";


$lPensionista = false;

if($sqlerro == false){

  if($tiparq == 0){
    $sql = $clfolha->sql_query_gerarqbag(null,"folha.*,cgm.*, length(trim(r38_agenc)) as qtddigitosagencia,
                                               r70_descr,
                                               length(trim(z01_cgccpf)) as tam,
                                               r38_liq as valorori",
                                              "r38_banco,r38_nome",
                                              "$rh34_wherefolha");
    $result  = $clfolha->sql_record($sql);

    if(!$result || pg_num_rows($result) == 0) {
      $sqlerro = true;
      $erro_msg = 'Não foram encontrados pagamentos para tipo de arquivo selecionado.';
    }

    $numrows = pg_num_rows($result);
  } else {

    $titarquivo = "pensaojudicial";
    $titrelatorio = "PENSÃO JUDICIAL";
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
    }

    $sql = $clpensao->sql_query_gerarqbag(null,null,null,null,"$campovalor as r38_liq, length(trim(r52_codage)||trim(r52_dvagencia)) as qtddigitosagencia,
                                               r52_numcgm as r38_regist,
                                               r52_codbco as r38_banco,
                                               r52_regist,
                                               trim(r52_conta)||trim(coalesce(r52_dvconta,'')) as r38_conta,
                                               trim(r52_codage)||trim(coalesce(r52_dvagencia,'')) as r38_agenc,
                                               cgm.*,func.z01_nome as nomefuncionario,
                                               r70_descr,
                                               length(trim(cgm.z01_cgccpf)) as tam,
                                               $campovalor as valorori",
                                              "r52_codbco,cgm.z01_nome",
                                              "$rh34_wherepensa and $campovalor > 0");

    $lPensionista = true;
    $result      = $clpensao->sql_record($sql);

    if(!$result || pg_num_rows($result) == 0) {
      $sqlerro = true;
      $erro_msg = 'Não foram encontrados pagamentos para tipo de arquivo selecionado.';
    }

    $numrows     = pg_num_rows($result);
  }

  if ($numrows > 0 && $rh34_codban == "041") {

    $nomearquivo_impressao = "tmp/".$titarquivo.".pdf";
    $nomearquivo = $titarquivo.".txt";
    $cllayouts_bb->nomearq  = "tmp/$nomearquivo";
    $cllayout_BBBS->nomearq = "tmp/$nomearquivo";
    $cllayout_BBBS->BSheaderA_001_003 = $acodigodobanco;
    $cllayout_BBBS->BSheaderA_019_032 = $inscricaoprefa;
    $cllayout_BBBS->BSheaderA_033_037 = $aconveniobanco;
    $cllayout_BBBS->BSheaderA_053_057 = $agenciadobanco;
    $cllayout_BBBS->BSheaderA_062_071 = $dacontadobanco;
    $cllayout_BBBS->BSheaderA_073_102 = $nomeprefeitura;
    $cllayout_BBBS->BSheaderA_103_132 = $descricaobanco;
    $cllayout_BBBS->BSheaderA_144_151 = $adatadegeracao;
    $cllayout_BBBS->BSheaderA_152_157 = $ahoradegeracao;
    $cllayout_BBBS->BSheaderA_158_163 = $sequenciaarqui;
    $cllayout_BBBS->BSheaderA_192_211 = $sequenciaarqui;
    $cllayout_BBBS->geraHEADERArqBS();

    $pdf = new PDF();
    $pdf->Open();
    $pdf->AliasNbPages();
    $pdf->setfillcolor(235);
    $total = 0;
    $alt = 4;

    $head3 = $titrelatorio;
    $head5 = "SEQUENCIAL DO ARQUIVO  :  ".$sequenciaarqui;
    $head6 = "GERAÇÃO  :  ".db_formatar($datagera,"d").' AS '.date("H").':'.date("i").':'.date("s").' HS';
    $head7 = "PAGAMENTO:  ".db_formatar($datadeposit,"d");
    $head8 = 'BANCO : '.$rh34_codban.' - '.$db90_descr;

    $xvalor    = 0;
    $xvaltotal = 0;
    $xbanco    = "";
    $ant_codgera = "";
    $total_geral = 0;

    $xtotal_func = 0;
    $xtotal = 0;
    $total_func = 0;

    $soma_dep = 0;
    $soma_doc = 0;
    $soma_ted = 0;
    $tota_dep = 0;
    $tota_doc = 0;
    $tota_ted = 0;

    $seq_header   = 0;
    $registro     = 1;
    $valor_header = 0;

    $bancoanterior= "";

    $entrar = true;

    $oDaoPesMov = db_utils::getDao('rhpessoalmov');
    $oDaoPensao = db_utils::getDao('pensao');

    for($i=0; $i<$numrows; $i++){

      db_fieldsmemory($result,$i);

      if ($entrar == true || $pdf->gety() > $pdf->h - 30) {

        $pdf->addpage("L");
        $pdf->setfont('arial','b',8);
        $pdf->cell(20,$alt,$RLrh01_regist,1,0,"C",1);
        if ($tiparq < 5) {

          $pdf->cell(20,$alt,$RLz01_numcgm,1,0,"C",1);
          $pdf->cell(20,$alt,$RLz01_cgccpf,1,0,"C",1);
          $pdf->cell(65,$alt,$RLz01_nome,1,0,"C",1);
          $pdf->cell(65,$alt,$RLr70_descr,1,0,"C",1);
         } else {

           $pdf->cell(65,$alt,"Pensionista",1,0,"C",1);
           $pdf->cell(65,$alt,"Funcionário",1,0,"C",1);
           $pdf->cell(20,$alt,$RLz01_numcgm,1,0,"C",1);
           $pdf->cell(20,$alt,$RLz01_cgccpf,1,0,"C",1);
         }
        $pdf->cell(20,$alt,$RLr38_liq,1,0,"C",1);
        $pdf->cell(15,$alt,"Cod.Pgto.",1,0,"C",1);
        $pdf->cell(15,$alt,$RLr38_banco,1,0,"C",1);
        $pdf->cell(15,$alt,$RLr38_agenc,1,0,"C",1);
        $pdf->cell(25,$alt,$RLr38_conta,1,1,"C",1);
        $entrar = false;
      }

      if($rh34_codban==$r38_banco){
        $codpgto   = "DEP";
        $soma_dep += $r38_liq;
        $tota_dep += $r38_liq;
      }else{
        if($r38_liq<5000){
          $codpgto   = "DOC";
          $soma_doc += $r38_liq;
          $tota_doc += $r38_liq;
        }else{
          $codpgto   = "TED";
          $soma_ted += $r38_liq;
          $tota_ted += $r38_liq;
        }
      }

      if($bancoanterior != $r38_banco){

        $bancoanterior = $r38_banco;

        if($acodigodobanco == '041'){
          $tiposerv = "30";
          $tipopaga = "01";
        }else{
          $tiposerv = "12";
          $tipopaga = "03";
        }

        if ($seq_header != 0) {

          $cllayout_BBBS->BBBStraillerL_001_003 = $acodigodobanco;
          $cllayout_BBBS->BBBStraillerL_004_007 = $seq_header;
          $cllayout_BBBS->BBBStraillerL_018_023 = $seq_detalhe;
          $cllayout_BBBS->BBBStraillerL_024_041 = $valor_header;
          $cllayout_BBBS->geraTRAILLERLote();
          $valor_header = 0;
          $registro ++;
        }

        $seq_header ++;
        $seq_detalhe = 0;
        $registro ++;

        $cllayout_BBBS->BSheaderL_001_003 = $acodigodobanco;
        $cllayout_BBBS->BSheaderL_004_007 = $seq_header;
        $cllayout_BBBS->BSheaderL_010_011 = $tiposerv;
        $cllayout_BBBS->BSheaderL_012_013 = $tipopaga;
        $cllayout_BBBS->BSheaderL_019_032 = $inscricaoprefa;
        $cllayout_BBBS->BSheaderL_033_037 = $aconveniobanco;
        $cllayout_BBBS->BSheaderL_053_057 = $agenciadobanco;
        $cllayout_BBBS->BSheaderL_062_071 = $dacontadobanco;
        $cllayout_BBBS->BSheaderL_073_102 = $nomeprefeitura;
        $cllayout_BBBS->BSheaderL_143_172 = $ender;
        $cllayout_BBBS->BSheaderL_193_212 = $munic;
        $cllayout_BBBS->BSheaderL_213_220 = $cep;
        $cllayout_BBBS->BSheaderL_221_222 = $uf;
        $cllayout_BBBS->geraHEADERLoteBS();
      }

      $compensacao = "   ";
      if($acodigodobanco == $r38_banco || $r38_liq<5000){
        $compensacao = "010";
      }else {

        if($r38_liq>=5000){
          $compensacao = "018";
        }
      }

      $contasapagarT = str_replace('.','',str_replace('-','',$r38_conta));
      $contasapagarT = db_formatar($contasapagarT,'s','0',10,'e',0);

      $contasapagarT+= 0;
      if($contasapagarT == 0){
        continue;
      }
      $contasapagarT = db_formatar($contasapagarT,'s','0',10,'e',0);



      $contasapagar = substr($contasapagarT,0,10);


      /**
       * Conversado com a Mari, Anderson, Robson e Andrio sobre o problema das agencias nos arquivos de banco
       * Foi visto que na tabela 'folha' os dados estao sendo inseridos erroneamente.
       * Foi resolvido que provisoriamente, buscariamos da tabela 'rhpesbanco' até a rotina Geracao em Disco (Novo)
       * estiver concluida.
       *
       * Logica abaixo replicada em cada arquivo de geracao de banco
       */

      $iAgencia       = '';
      $iDigitoAgencia = '';
      $rsAgencia      = null;
      $sNomeServidor  = '';

      if ($lPensionista) {

        $sNomeServidor  = $nomefuncionario;

        $sCampos = "r52_codage as agencia, r52_dvagencia as digito";
        $sWhere  = "     r52_numcgm = {$r38_regist}";  // quando é pensão é criado um alias do cgm para r38_regist
        $sWhere .= " and r52_regist = {$r52_regist}";
        $sWhere .= " and r52_anousu = " . db_anofolha();
        $sWhere .= " and r52_mesusu = " . db_mesfolha();

        $sSqlPensao = $oDaoPensao->sql_query_file(null, null, null, null, $sCampos, null, $sWhere);
        $rsAgencia  = $oDaoPensao->sql_record($sSqlPensao);

        if (!$rsAgencia || pg_num_rows($rsAgencia) != 1) {

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

        if (!$rsAgencia || pg_num_rows($rsAgencia) != 1) {

          $sMsgErro  = "Não foi encontrada a agencia do servidor: ";
          $sMsgErro .= "{$r38_regist} - {$r38_nome}.\\nFavor verificar.";
          db_msgbox($sMsgErro);
          exit;
        }
      }

      $oDadosAgencia  = db_utils::fieldsMemory($rsAgencia, 0);
      $iAgencia       = (int) $oDadosAgencia->agencia;
      $iDigitoAgencia = $oDadosAgencia->digito;

      if (strlen($iAgencia) > 5) {

        $sMsgErro  = "Agencia inconsistente. Favor verificar: \\n ";
        $sMsgErro .= "Servidor: {$r38_regist} - {$sNomeServidor}, agencia: {$oDadosAgencia->rh44_agencia}";
        db_msgbox($sMsgErro);
        exit;
      }

      $iAgencia = str_pad($iAgencia, 5, '0', STR_PAD_LEFT);

      $conf = 1;
      if($tam == 14){
        $conf = 2;
      }

      $valor_header += $r38_liq;
      $registro ++;
      $seq_detalhe ++;
      $xtotal_func ++;
      $xvaltotal += $r38_liq;


      $pdf->setfont('arial','',7);
      $pdf->cell(20,$alt,$r38_regist,1,0,"C",0);
      if($tiparq < 5){
        $pdf->cell(20,$alt,$z01_numcgm,1,0,"C",0);
        $pdf->cell(20,$alt,$z01_cgccpf,1,0,"C",0);
        $pdf->cell(65,$alt,$z01_nome,1,0,"L",0);
        $pdf->cell(65,$alt,$r70_descr,1,0,"L",0);
      }else{
        $pdf->cell(65,$alt,$z01_nome,1,0,"L",0);
        $pdf->cell(65,$alt,$nomefuncionario,1,0,"L",0);
        $pdf->cell(20,$alt,$z01_numcgm,1,0,"C",0);
        $pdf->cell(20,$alt,$z01_cgccpf,1,0,"C",0);
      }
      $pdf->cell(20,$alt,db_formatar($r38_liq,'f'),1,0,"R",0);
      $pdf->cell(15,$alt,$codpgto,1,0,"C",0);
      $pdf->cell(15,$alt,$r38_banco,1,0,"C",0);
      $pdf->cell(15,$alt,$iAgencia . ' - ' . $iDigitoAgencia,1,0,"R",0);
      $pdf->cell(25,$alt,$r38_conta,1,1,"R",0);

      $cllayout_BBBS->BSregist_001_003 = $acodigodobanco;
      $cllayout_BBBS->BSregist_004_007 = $seq_header;
      $cllayout_BBBS->BSregist_009_013 = $seq_detalhe;
      $cllayout_BBBS->BSregist_018_020 = $compensacao;
      $cllayout_BBBS->BSregist_021_023 = $r38_banco;
      $cllayout_BBBS->BSregist_024_028 = $iAgencia;
      $cllayout_BBBS->BSregist_030_042 = $contasapagar;
      $cllayout_BBBS->BSregist_044_073 = $z01_nome;
      $cllayout_BBBS->BSregist_074_088 = $r38_regist;
      $cllayout_BBBS->BSregist_094_101 = $datadedeposito;
      $cllayout_BBBS->BSregist_120_134 = $r38_liq;
      $cllayout_BBBS->BSregist_203_203 = $conf;
      $cllayout_BBBS->BSregist_204_217 = $z01_cgccpf;
      $cllayout_BBBS->geraREGISTROSBS();

    }

    $registro ++;
    $cllayout_BBBS->BBBStraillerL_001_003 = $acodigodobanco;
    $cllayout_BBBS->BBBStraillerL_004_007 = $seq_header;
    $cllayout_BBBS->BBBStraillerL_018_023 = $seq_detalhe;
    $cllayout_BBBS->BBBStraillerL_024_041 = $valor_header;
    $cllayout_BBBS->geraTRAILLERLote();

    $registro ++;
    $cllayout_BBBS->BBBStraillerA_001_003 = $acodigodobanco;
    $cllayout_BBBS->BBBStraillerA_004_007 = $loteservico;
    $cllayout_BBBS->BBBStraillerA_018_023 = $seq_header;
    $cllayout_BBBS->BBBStraillerA_024_029 = $registro;
    $cllayout_BBBS->geraTRAILLERArquivo();

    $cllayout_BBBS->gera();

    $pdf->setfont('arial','b',8);

    $pdf->cell(190,$alt,'Total de funcionários',1,0,"C",1);
    $pdf->cell(20,$alt,$xtotal_func,1,0,"R",1);
    $pdf->cell(70,$alt,'',1,1,"C",1);

    $pdf->cell(190,$alt,'Total Geral',1,0,"C",1);
    $pdf->cell(20,$alt,db_formatar($xvaltotal,'f'),1,0,"R",1);
    $pdf->cell(70,$alt,'',1,1,"C",1);

    $pdf->Output($nomearquivo_impressao,false,true);
  }else{
    $sqlerro = true;
    $erro_msg = "Nenhum registro encontrado. Contate o suporte.";
  }
}

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
