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
$cllayout_BBBS              = new LayoutBBBSFolha;
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
$clrotulo->label("r38_agenc");
$clrotulo->label("r38_conta");
$clrotulo->label("r70_descr");

$sqlerro = false;

db_sel_instit();

//die($clrharqbanco->sql_query($rh34_codarq));    
$result_arqbanco=$clrharqbanco->sql_record($clrharqbanco->sql_query($rh34_codarq));    
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

}else{
  $sqlerro = true;
  $erro_msg = "Arquivo não encontrado";
}
$rh34_wherefolha = " rh44_codban = '$rh34_codban'  and rh104_rhgeracaofolha = {$oGet->rh104_rhgeracaofolha}";
$rh34_wherepensa = " r52_codbco  = '$rh34_codban'  and r52_anousu = ".db_anofolha()." and r52_mesusu = ".db_mesfolha();
$titrelatorio = "Todos os funcionários";
$titarquivo   = "txt_pgto_funcionarios";

if($sqlerro == false){

  if($tiparq == 0){

    $sql = $clrhgeracaofolhareg->sql_query_geraTXT(null,"
                                               rh104_sequencial,
                                               rh02_seqpes,
                                               rh02_regist,
                                               rh104_vlrliquido,
                                               rh104_vlrsalario,
                                               cgm.*,
                                               trim(rh44_agencia)||trim(coalesce(rh44_dvagencia,'')) as rh44_agencia,
                                               rh44_codban,
                                               trim(rh44_conta)||trim(coalesce(rh44_dvconta,'')) as rh44_conta,
                                               length(trim(rh44_agencia)) as qtddigitosagencia,
                                               substr(db_fxxx(rh02_regist,rh102_anousu,rh102_mesusu,".db_getsession("DB_instit")."),111,11) as f010,
                                               r70_descr,
                                               length(trim(z01_cgccpf)) as tam,
                                               rh104_vlrliquido as valorori",
                                              "rh44_codban,z01_nome",
                                              "$rh34_wherefolha");

    $result  = $clfolha->sql_record($sql);
    $numrows = $clfolha->numrows;
    
  }else{
  	
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

    $sql      = $clpensao->sql_query_gerarqbag(null,null,null,null,"$campovalor as rh104_vlrliquido, length(trim(r52_codage)||trim(r52_dvagencia)) as qtddigitosagencia,
                                               r52_numcgm as rh02_regist,
                                               r52_codbco as rh44_codban,
                                               trim(r52_conta)||trim(coalesce(r52_dvconta,'')) as r38_conta,
                                               trim(r52_codage)||trim(coalesce(r52_dvagencia,'')) as r38_agenc,
                                               cgm.*,func.z01_nome as nomefuncionario,
                                               r70_descr,
                                               length(trim(cgm.z01_cgccpf)) as tam,
                                               $campovalor as valorori",
                                              "r52_codbco,cgm.z01_nome",
                                              "$rh34_wherepensa and $campovalor > 0");


                                               //die($sql);
    $result   = $clpensao->sql_record($sql);
    $numrows  = $clpensao->numrows;
  }
  
  // Coleta os dados da consulta sql e coloca em um vetor
  $oDados  = db_utils::getCollectionByRecord($result);

  // echo $sql; exit;
  if($numrows > 0 && $rh34_codban == "041"){
  	
    $nomearquivo_impressao = "/tmp/".$titarquivo.".pdf";
    $nomearquivo = $titarquivo.".txt";
    $cllayouts_bb->nomearq            = "tmp/$nomearquivo";
    $cllayout_BBBS->nomearq           = "tmp/$nomearquivo";
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

///// INICIA IMPRESSÃO DO RELATÓRIO
    $pdf    = new PDF();
    $pdf->Open();
    $pdf->AliasNbPages();
    $pdf->setfillcolor(235);
    $total  = 0;
    $alt    = 4;

    $head3        = $titrelatorio;
    $head5        = "SEQUENCIAL DO ARQUIVO  :  ".$sequenciaarqui;
    $head6        = "GERAÇÃO  :  ".db_formatar($datagera,"d").' AS '.date("H").':'.date("i").':'.date("s").' HS';
    $head7        = "PAGAMENTO:  ".db_formatar($datadeposit,"d");
    $head8        = 'BANCO : '.$rh34_codban.' - '.$db90_descr;

    $xvalor       = 0;
    $xvaltotal    = 0;
    $xbanco       = "";
    $ant_codgera  = "";
    $total_geral  = 0;

    $xtotal_func  = 0;
    $xtotal       = 0;
    $total_func   = 0;

    $soma_dep     = 0;
    $soma_doc     = 0;
    $soma_ted     = 0;
    $tota_dep     = 0;
    $tota_doc     = 0;
    $tota_ted     = 0;

    $seq_header   = 0;
    $registro     = 1;
    $valor_header = 0;

    $bancoanterior= "";

    $entrar = true;
    for($i=0; $i<$numrows; $i++){
      db_fieldsmemory($result,$i);

      if($entrar == true || $pdf->gety() > $pdf->h - 30){
      	
        $pdf->addpage("L");
        $pdf->setfont('arial','b',8);
        $pdf->cell(20,$alt,$RLrh01_regist,1,0,"C",1);
        if($tiparq < 5){
        	
          $pdf->cell(20,$alt,$RLz01_numcgm,1,0,"C",1);
          $pdf->cell(20,$alt,$RLz01_cgccpf,1,0,"C",1);
          $pdf->cell(65,$alt,$RLz01_nome,1,0,"C",1);
          $pdf->cell(65,$alt,$RLr70_descr,1,0,"C",1);
        }else{
        	
          $pdf->cell(65,$alt,"Pensionista",1,0,"C",1);
          $pdf->cell(65,$alt,"Funcionário",1,0,"C",1);
          $pdf->cell(20,$alt,$RLz01_numcgm,1,0,"C",1);
          $pdf->cell(20,$alt,$RLz01_cgccpf,1,0,"C",1);
      }
        $pdf->cell(20,$alt,$RLrh104_vlrliquido,1,0,"C",1);
        $pdf->cell(15,$alt,"Cod.Pgto.",1,0,"C",1);
        $pdf->cell(15,$alt,$RLrh44_codban,1,0,"C",1);
        $pdf->cell(15,$alt,$RLr38_agenc,1,0,"C",1);
        $pdf->cell(25,$alt,$RLr38_conta,1,1,"C",1);
        $entrar = false;
      }

      if($rh34_codban==$rh44_codban){
      	
        $codpgto   = "DEP";
        $soma_dep += $rh104_vlrliquido;
        $tota_dep += $rh104_vlrliquido;
      }else{
      	
        if($rh104_vlrliquido < 5000){
        	
          $codpgto   = "DOC";
          $soma_doc += $rh104_vlrliquido;
          $tota_doc += $rh104_vlrliquido;
          
        }else{
        	
          $codpgto   = "TED";
          $soma_ted += $rh104_vlrliquido;
          $tota_ted += $rh104_vlrliquido;
        }
      }

      if($bancoanterior != $rh44_codban){

        $bancoanterior = $rh44_codban;

  if($acodigodobanco == '041'){
  	
    $tiposerv = "30";
    $tipopaga = "01";
  }else{
  	
    $tiposerv = "12";
    $tipopaga = "03";
  }

  if($seq_header != 0){
  	
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
      if($acodigodobanco == $rh44_codban || $rh104_vlrliquido<5000){
        $compensacao = "010";
      }else{
      	
			  if($rh104_vlrliquido>=5000){
			    $compensacao = "018";
			  }
      }

      $agenciapagarT = str_replace('.','',str_replace('-','',$rh44_agencia));
      $contasapagarT = str_replace('.','',str_replace('-','',$rh44_conta));

      if($qtddigitosagencia == 5){
        $agenciapagarT = substr($agenciapagarT, 0, 4);
      }

      $agenciapagarT = db_formatar($agenciapagarT,'s','0', 5,'e',0);
      $contasapagarT = db_formatar($contasapagarT,'s','0',10,'e',0);

      $contasapagarT+= 0;
      if($contasapagarT == 0){
			  continue;
      }
      $contasapagarT = db_formatar($contasapagarT,'s','0',10,'e',0);

      $agenciapagar = substr($agenciapagarT,0,4);
      $digitoagenci = substr($agenciapagarT,4,1);

      $contasapagar = substr($contasapagarT,0,10);

      $conf = 1;
      if($tam == 14){
        $conf = 2;
      }

      $valor_header += $rh104_vlrliquido;
      $registro ++;
      $seq_detalhe ++;
      $xtotal_func ++;
      $xvaltotal += $rh104_vlrliquido;


      $pdf->setfont('arial','',7);
      $pdf->cell(20,$alt,$rh02_regist,1,0,"C",0);
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
      $pdf->cell(20,$alt,db_formatar($rh104_vlrliquido,'f'),1,0,"R",0);
      $pdf->cell(15,$alt,$codpgto,1,0,"C",0);
      $pdf->cell(15,$alt,$rh44_codban,1,0,"C",0);
      $pdf->cell(15,$alt,$rh44_agencia,1,0,"R",0);
      $pdf->cell(25,$alt,$rh44_conta,1,1,"R",0);

      $cllayout_BBBS->BSregist_001_003 = $acodigodobanco;
      $cllayout_BBBS->BSregist_004_007 = $seq_header;
      $cllayout_BBBS->BSregist_009_013 = $seq_detalhe;
      $cllayout_BBBS->BSregist_018_020 = $compensacao;
      $cllayout_BBBS->BSregist_021_023 = $rh44_codban;
      $cllayout_BBBS->BSregist_024_028 = $agenciapagar;
      $cllayout_BBBS->BSregist_030_042 = $contasapagar;
      $cllayout_BBBS->BSregist_044_073 = $z01_nome; 
      $cllayout_BBBS->BSregist_074_088 = $rh02_regist;
      $cllayout_BBBS->BSregist_094_101 = $datadedeposito; 
      $cllayout_BBBS->BSregist_120_134 = $rh104_vlrliquido;
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
//$oDados
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
?>
