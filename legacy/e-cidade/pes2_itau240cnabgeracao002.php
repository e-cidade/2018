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

require_once(modification("fpdf151/pdf.php"));
require_once(modification("fpdf151/assinatura.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_libcaixa_ze.php"));
require_once(modification("libs/db_libgertxtfolha.php"));
require_once(modification("classes/db_folha_classe.php"));
require_once(modification("classes/db_pensao_classe.php"));
require_once(modification("classes/db_rharqbanco_classe.php"));
require_once(modification("classes/db_orctiporec_classe.php"));
require_once(modification("classes/db_rhgeracaofolhareg_classe.php"));
require_once(modification("classes/db_rhgeracaofolhaarquivo_classe.php"));
require_once(modification("classes/db_rhgeracaofolhaarquivoreg_classe.php"));
require_once(modification("libs/db_utils.php"));

parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
db_postmemory($HTTP_POST_VARS);
$oGet = db_utils::postMemory($_GET);
$cllayouts_bb               = new LayoutBB;
$cllayouts_itau             = new cl_layouts_itau;
$clfolha                    = new cl_folha;
$clpensao                   = new cl_pensao;
$clrharqbanco               = new cl_rharqbanco;
$clorctiporec               = new cl_orctiporec;
$clrhgeracaofolhareg        = new cl_rhgeracaofolhareg();
$clrhgeracaofolhaarquivo    = new cl_rhgeracaofolhaarquivo();
$clrhgeracaofolhaarquivoreg = new cl_rhgeracaofolhaarquivoreg();
$clrotulo = new rotulocampo;
$clrotulo->label("rh01_regist");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("z01_cgccpf");
$clrotulo->label("rh104_vlrliquido");
$clrotulo->label("rh44_codban");
$clrotulo->label("rh44_agencia");
$clrotulo->label("rh44_conta");
$clrotulo->label("r70_descr");

$sqlerro = false;

db_sel_instit();

//die($clrharqbanco->sql_query($rh34_codarq));    
$result_arqbanco=$clrharqbanco->sql_record($clrharqbanco->sql_query($rh34_codarq));    
if($clrharqbanco->numrows>0){

  db_fieldsmemory($result_arqbanco,0);

    include(modification("dbforms/db_layouttxt.php"));
    $posicao = "A";
    $layoutimprime = 78;

    $idcaixa = "P";
    $idcliente = "P";
    $db90_codban = $rh34_codban;
    $agenciaheader = $rh34_agencia;
    $contaheader = $rh34_conta;
    $contalote   = $rh34_conta;

    $conveniobanco = trim($rh34_convenio); 
    
    $descrarquivo = "FOLHA PAGAMENTO"; // Campo somente do layout 3

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
    $dac            = $dvagenciaheader;
    $dvcontalote    = $dvcontaheader;
    $dvagenciacontalote = $dvagenciacontaheader;

    $datageracao = $datagera; 
    $horageracao = date("H").date("i").date("s");
 
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

    if($db90_codban == "104"){
			if(trim($rh34_convenio) == '003881'){
        $conveniobanco = substr($rh34_convenio,0,6)."060003        ";
		  }else{
        $conveniobanco = substr($rh34_convenio,0,6)."060001        ";
			}
    }else{
      $conveniobanco = trim($rh34_convenio); 
    }
    ////// DADOS SOMENTE CNAB240 CEF
    $parametrotransmiss = substr($rh34_convenio,10,2);
    $indicaambcaixa   = "P";
    $indicaambcliente = "P";
    $densidadearquivo = "01600";
    //////

    db_setaPropriedadesLayoutTxt($db_layouttxt,1);

}else{
  $sqlerro = true;
  $erro_msg = "Arquivo não encontrado";
}

if(!isset($rh34_where) || (isset($rh34_where) && trim($rh34_where) == "")){
  $rh34_wherefolha = "";
  $rh34_wherepensa = ""; 
}else{
  $rh34_wherefolha = $rh34_where." and ";
  $rh34_wherepensa = $rh34_where." and "; 
}

$rh34_wherefolha  = " rh44_codban = '$rh34_codban'  and rh104_rhgeracaofolha = {$oGet->rh104_rhgeracaofolha}";
$rh34_wherepensa  = " r52_codbco  = '$rh34_codban'  and r52_anousu = ".db_anofolha()." and r52_mesusu = ".db_mesfolha();

$titrelatorio = "Todos os funcionários";
$titarquivo   = "pagtofuncionarios";

if($sqlerro == false){

  if($tiparq == 0){
    $sql = $clrhgeracaofolhareg->sql_query_geraTXT(null,"
                                               rh104_sequencial,
                                               rh02_seqpes,
                                               rh02_regist,
                                               rh104_vlrliquido,
                                               rh104_vlrsalario,
                                               trim(rh44_agencia)||trim(coalesce(rh44_dvagencia,'')) as rh44_agencia,
                                               rh44_codban,
                                               trim(rh44_conta)||trim(coalesce(rh44_dvconta,'')) as rh44_conta,
                                               length(trim(rh44_agencia)) as qtddigitosagencia,
                                               length(trim(rh44_conta))   as qtddigitosconta,
                                               substr(db_fxxx(rh02_regist,rh102_anousu,rh102_mesusu,".db_getsession("DB_instit")."),111,11) as f010,
                                               r70_descr,
                                               length(trim(z01_cgccpf)) as tam,
                                               cgm.*,
                                               rh104_vlrliquido as valorori",
                                              "rh44_codban,z01_nome",
                                              "$rh34_wherefolha");
  	
    $result  = $clfolha->sql_record($sql);
    $numrows = $clfolha->numrows;
  }else{
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

    $sql = $clpensao->sql_query_gerarqbag(null,null,null,null,"$campovalor as rh104_vlrliquido, length(trim(r52_codage)||trim(r52_dvagencia)) as qtddigitosagencia,
                                               r52_numcgm as rh02_regist,
                                               r52_codbco as rh44_codban,
	                                       trim(r52_conta)||trim(coalesce(r52_dvconta,'')) as rh44_conta,
	                                       trim(r52_codage)||trim(coalesce(r52_dvagencia,'')) as rh44_agencia,
	                                       cgm.*,func.z01_nome as nomefuncionario,
                                               r70_descr,
                                               length(trim(cgm.z01_cgccpf)) as tam,
                                               $campovalor as valorori",
                                              "r52_codbco,cgm.z01_nome",
                                              "$rh34_wherepensa and $campovalor > 0");

//die($sql);
    $result  = $clpensao->sql_record($sql);
//		db_criatabela($result);exit;
    $numrows = $clpensao->numrows;
  }
  
  
  // Coleta os dados da consulta sql e coloca em um vetor
  $oDados  = db_utils::getCollectionByRecord($result);
  
  
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
    $head6 = "GERAÇÃO  :  ".db_formatar($datagera,"d").' AS '.$horageracao.' HS';
    $head7 = "PAGAMENTO:  ".db_formatar($datadedeposito,"d");
    $head8 = 'BANCO : '.$rh34_codban.' - '.$db90_descr;


    $loteservic = 1;
    $finalidadedoc = "00";
    $codigocompromisso = substr($rh34_convenio,6,4);
    $tipocompromisso = "02";
    $agencialote = $agenciaheader;

    $loteservico = 1;
    $tiposervico = "30";
    if($db90_codban == $rh44_codban){
      $formalancamento = "01";
    }else{
      $formalancamento = "03";
    }
    ///// HEADER DO LOTE
    db_setaPropriedadesLayoutTxt($db_layouttxt, 2);
    ///// FINAL DO HEADER DO LOTE

    $sequencialnolote = 0;

    $quantidadefuncionarios = 0;
    $valortotal = 0;

    for($i=0;$i<$numrows;$i++){

      db_fieldsmemory($result,$i);
      //////////////////////////////////////////////
      $agencia = db_formatar(str_replace('.','',str_replace('-','',$rh44_agencia)),'s','0', 5,'e',0);
      $conta   = trim(str_replace(',','',str_replace('.','',str_replace('-','',$rh44_conta))));
      $qtddigitosconta = strlen($conta) - 4; /////// -4, pois -1 é do dvconta e -3 do codigooperacao
      $dvconta = substr($conta,-1);
      $codigooperacao = substr($conta,0,3);
      $conta = substr($conta, 3, $qtddigitosconta);
      //////////////////////////////////////////////
      //////////////////////////////////////////////


      //////////////////////////////////////////////
      // CAMPOS LAYOUT CNAB240
      $sequencialnolote ++;
      $compensacao = "700";
      if($rh44_codban == $db90_codban){
        $compensacao = str_repeat('0',3);
      }

      $agenciapagarT = db_formatar(str_replace('.','',str_replace('-','',$rh44_agencia)),'s','0', 5,'e',0);
      $contasapagarT = db_formatar(str_replace('.','',str_replace('-','',$rh44_conta)),'s','0',6,'e',0);

      $agenciapagar = substr($agenciapagarT,0,4);
      $digitoagenci = substr($agenciapagarT,4,1);

      $digitocontas        = substr($contasapagarT,5,1);
      $contafavorecido     = substr($contasapagarT,0,5);

      $bancofavorecido     = $rh44_codban;
      $agenciafavorecido   = $agenciapagar;

      $dvagenciafavorecido = $digitoagenci;
//    $contafavorecido     = $contasapagarT;
      $dvcontafavorecido   = $digitocontas;
      $dvagenciacontafav   = "";
      $cpffavorecido       = db_formatar(str_replace('.','',str_replace('-','',$z01_cgccpf)),'s','0', 14,'e',0);
      $numerocontrolemov   = $rh02_regist;
      $sequencialreg       = $i + 1;
//	  echo "<br> matricula --> $numerocontrolemov agencia --> $agenciafavorecido   dvagencia --> $dvagenciafavorecido   conta --> $contafavorecido  dvconta --> $dvcontafavorecido ";
      //////////////////////////////////////////////
      //////////////////////////////////////////////
      
      $valordebito = $rh104_vlrliquido;
      $dataprocessamento = $datadedeposito;
      //      $sequencialreg = "      ";
      ///// REGISTRO A
      db_setaPropriedadesLayoutTxt($db_layouttxt, 3, $posicao);
      ///// FINAL DO REGISTRO A

      if($tam == 11){
        $tipoinscricaofav = "1";
      }else if($tam == 14){
        $tipoinscricaofav = "2";
      }else{
        $tipoinscricaofav = "3";
      }
      $datavencimento = $datadedeposito;
      $valorvencimento = $rh104_vlrliquido;
      ///// REGISTRO B
//      db_setaPropriedadesLayoutTxt(&$db_layouttxt, 3, "B");
      ///// FINAL DO REGISTRO B

      if($i == 0 || $pdf->gety() > $pdf->h - 30){
	$pdf->addpage("L");
        $pdf->cell(15,$alt,$RLrh01_regist,1,0,"C",1);
        if($tiparq == 0){
          $pdf->cell(15,$alt,$RLz01_numcgm,1,0,"C",1);
          $pdf->cell(20,$alt,$RLz01_cgccpf,1,0,"C",1);
          $pdf->cell(65,$alt,$RLz01_nome,1,0,"C",1);
          $pdf->cell(65,$alt,$RLr70_descr,1,0,"C",1);
	      }else{
          $pdf->cell(65,$alt,"Pensionista",1,0,"C",1);
          $pdf->cell(65,$alt,"Funcionário",1,0,"C",1);
          $pdf->cell(15,$alt,$RLz01_numcgm,1,0,"C",1);
          $pdf->cell(20,$alt,$RLz01_cgccpf,1,0,"C",1);
	      }
        $pdf->cell(17,$alt,$RLrh104_vlrliquido,1,0,"C",1);
        $pdf->cell(13,$alt,$RLrh44_agencia,1,0,"C",1);
        $pdf->cell(20,$alt,$RLrh44_conta,1,1,"C",1);
        $pdf->ln(3);
      }

      $pdf->setfont('arial','',7);
      $pdf->cell(15,$alt,$rh02_regist,1,0,"C",0);
      if($tiparq == 0){
        $pdf->cell(15,$alt,$z01_numcgm,1,0,"C",0);
        $pdf->cell(20,$alt,$z01_cgccpf,1,0,"C",0);
        $pdf->cell(65,$alt,$z01_nome,1,0,"L",0);
        $pdf->cell(65,$alt,$r70_descr,1,0,"L",0);
      }else{
        $pdf->cell(65,$alt,$z01_nome,1,0,"L",0);
        $pdf->cell(65,$alt,$nomefuncionario,1,0,"L",0);
        $pdf->cell(15,$alt,$z01_numcgm,1,0,"C",0);
        $pdf->cell(20,$alt,$z01_cgccpf,1,0,"C",0);
      }
      $pdf->cell(17,$alt,db_formatar($rh104_vlrliquido,'f'),1,0,"R",0);
      $pdf->cell(13,$alt,$rh44_agencia,1,0,"R",0);
      $pdf->cell(20,$alt,$rh44_conta,1,1,"R",0);
      $quantidadefuncionarios ++;
      $valortotal += $rh104_vlrliquido;
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
db_inicio_transacao();
$erro = false;

$clrhgeracaofolhaarquivo->rh105_dtgeracao      = date("Y-m-d", db_getsession('DB_datausu'));
$clrhgeracaofolhaarquivo->rh105_dtdeposito     = $oGet->datadeposit;
$clrhgeracaofolhaarquivo->rh105_codarq         = $oGet->rh34_codarq;
$clrhgeracaofolhaarquivo->rh105_codbcofebraban = str_pad($oGet->codban,3,"0",STR_PAD_RIGHT);
$clrhgeracaofolhaarquivo->rh105_tipoarq        = $oGet->tiparq;
$clrhgeracaofolhaarquivo->rh105_folha          = $oGet->qfolha;
$clrhgeracaofolhaarquivo->rh105_arquivotxt     = $clrhgeracaofolhaarquivo->salvaArquivoTXT('tmp/'.$nomearquivo);
$clrhgeracaofolhaarquivo->rh105_instit         = db_getsession("DB_instit");
$clrhgeracaofolhaarquivo->incluir("");
if($numrows > 0){
  if($clrhgeracaofolhaarquivo->erro_status == "0"){
    db_msgbox($clrhgeracaofolhaarquivo->erro_msg);
    $erro = true;
  } else {
    
    foreach ($oDados as $oLinhas){
      
      $clrhgeracaofolhaarquivoreg->rh106_rhgeracaofolhareg     = $oLinhas->rh104_sequencial;
      $clrhgeracaofolhaarquivoreg->rh106_rhgeracaofolhaarquivo = $clrhgeracaofolhaarquivo->rh105_sequencial;
      $clrhgeracaofolhaarquivoreg->incluir("");
      if($clrhgeracaofolhaarquivoreg->erro_status == "0"){
        
        db_msgbox($clrhgeracaofolhaarquivoreg->erro_msg);
        $erro = true;
        exit;
      }
    }
  }
  db_fim_transacao($erro);
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
  db_fim_transacao($erro);

?>
