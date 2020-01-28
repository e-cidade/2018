<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

include("fpdf151/pdf.php");
include("fpdf151/assinatura.php");
include("dbforms/db_funcoes.php");
include("libs/db_libcaixa_ze.php");
include("libs/db_libgertxtfolha.php");
include("classes/db_folha_classe.php");
include("classes/db_pensao_classe.php");
include("classes/db_rharqbanco_classe.php");
include("classes/db_orctiporec_classe.php");
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
db_postmemory($HTTP_POST_VARS);

$cllayouts_bb  = new cl_layouts_bb;
$cllayout_BBBS = new cl_layout_BBBS;
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

$tiparq = 0;

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
    if($vinculo != "A"){
       $dacontadobanco = "006000042";
       $dvdacontabanco = "63";
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

    if($vinculo != "A"){
	       $dacontadobanco = "0404344203";
    }   
  }else{

    include("dbforms/db_layouttxt.php");
    $posicao = "A";
    $agenciaheader = $rh34_agencia;
    if($rh34_codban == "001"){
      $layoutimprime = 2;
    }else if($rh34_codban == "008" || $rh34_codban == "033"){
      $layoutimprime = 14;
    }else if(isset($layout) && $layout == 9){
      $layoutimprime = 9;
    }else if($layout == 4){
      $layoutimprime = 18;
      $posicao = "E";
    }else{
      $agenciaheader = str_repeat(' ',4);
      $layoutimprime = 3;
      $posicao = "E";
    }
    $idcaixa = "P";
    $idcliente = "P";
    $db90_codban = $rh34_codban;
    $contaheader = $rh34_conta;
    $contalote   = $rh34_conta;

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
      if($digitos>1 && $rh34_codban != "008"){
        $dvagenciacontaheader = $rh34_dvconta[1];
      }
    }
    if($rh34_codban != "008"){
      $dvagenciacontaheader = "00";
    }
    if($vinculo != "A"){
       if($rh34_codban == "001"){
	       $contaheader = "8162";
	       $dvcontaheader = "0";
       }else if($rh34_codban == "041"){
	       $contaheader = "040434420";
	       $dvcontaheader = "3";
       }else if($db90_codban == "104" && $layout != 4){
	       $contaheader = "006000042";
	       $dvcontaheader = "63";
       }else if($layout == 4){
	       $contaheader = "006000042";
	       $dvcontaheader = "63";

       }
    }
    
    if($layout == 4){
       $operacaoheader = substr($contaheader,0,3);
       $contaheader2   = str_pad(trim(substr($contaheader,3,8)),8,'0',STR_PAD_LEFT);
    }else{
       $operacaoheader = substr($contaheader,0,3);
       $contaheader2   = str_pad(trim(substr($contaheader,4,8)),8);
    }
    $dvagencialote = $dvagenciaheader;
    $dvcontalote   = $dvcontaheader;
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
    $nroagendacliente = $rh34_sequencial;

    $adatadegeracao = $datag_ano."-".$datag_mes."-".$datag_dia;
    $datadedeposito = $datad_ano."-".$datad_mes."-".$datad_dia;

    /////////////////////
    // PARA LAYOUT SANTANDER
    $datalancamento = $datadedeposito;
    /////////////////////
    
 
    $sequenciaarqui = $rh34_sequencial;
    $versaodoarquiv = "030";

    $paramnome = $datag_mes.$datag_ano."_".$horageracao;
    $nomearquivo = "folha_".$db90_codban."_".$paramnome.".txt";
    $db_layouttxt = new db_layouttxt($layoutimprime,"tmp/".$nomearquivo, $posicao);

    if($db90_codban == "104" && $layout != 4){
      $conveniobanco = substr($rh34_convenio,0,6);
    }else{
      $conveniobanco = trim($rh34_convenio); 
    }
    ////// DADOS SOMENTE CNAB240 CEF
    $parametrotransmiss = substr($rh34_convenio,10,2);
    $indicaambcaixa   = "P";
    $indicaambcliente = "P";
    $densidadearquivo = "01600";
    //////

    if(isset($layout) && $layout == 9){
      ////// DADOS CEF
      $tipocompromisso = str_repeat(' ', 6);
      $numerocompromisso = str_repeat(' ',4);
      $codmovimento = 2;
      $seqregistro = str_repeat(' ',6);
      $contaheader = str_repeat(' ', 11);
      $dvcontaheader = ' ';
      $indicaambcliente = ' ';
      $indicaambcaixa = ' ';
      //////
    }

    db_setaPropriedadesLayoutTxt(&$db_layouttxt,1);

  }

}else{
  $sqlerro = true;
  $erro_msg = "Arquivo n�o encontrado";
}

if(!isset($rh34_where) ){
  $rh34_wherefolha = "";
}else{
  if( trim($rh34_where) == ""){
    $rh34_wherefolha = "";
  }else{
    $rh34_wherefolha = $rh34_where." and ";
  }
}

if( trim($vinculo) != ""){
  if($vinculo == 'A'){
    $rh34_wherefolha .= " r38_vincul = '$vinculo' and ";
  }else{
    $rh34_wherefolha .= " r38_vincul <> 'A' and ";
  }
}

if( trim($tipo) == "O"){
    $rh34_wherefolha .= " (trim(r38_conta) = '00' or r38_conta is null ) and ";
}else{
    $rh34_wherefolha .= " trim(r38_conta) <> '00' and ";
}

$rh34_wherefolha.= " r38_banco = '$rh34_codban' ";

if($sqlerro == false){
  $campos = "  
               r38_regist, 
               r38_nome,   
               r38_numcgm, 
               r38_regime, 
               r38_lotac,  
               r38_vincul, 
               r38_padrao, 
               r38_salari, 
               r38_funcao, 
               r38_banco , 
               r38_agenc , 
               case when trim(r38_conta) = '' or r38_conta is null then '0' else r38_conta end as ver_conta,
               r38_conta, 
               r38_situac, 
               r38_previd, 
               r38_liq   , 
               r38_prov  , 
               r38_desc  , 
               r38_proc ,      
               z01_nome,
               z01_cgccpf,
               z01_numcgm
";
  $sql = $clfolha->sql_query_gerarqbag(null,"$campos,length(trim(r38_agenc)) as qtddigitosagencia,
                                             r70_descr,
                                             length(trim(cgm.z01_cgccpf)) as tam,
                                             r38_liq as valorori",
                                            "r38_banco,r38_agenc,r38_conta",
                                            "$rh34_wherefolha");
  $sql1 = "select  
               r38_regist, 
               r38_nome,   
               r38_numcgm, 
               r38_regime, 
               r38_lotac,  
               r38_vincul, 
               r38_padrao, 
               r38_salari, 
               r38_funcao, 
               r38_agenc , 
               case when to_number(ver_conta,'999999999999999') = 0 then '0' else r38_banco end as r38_banco , 
               r38_conta, 
               r38_situac, 
               r38_previd, 
               r38_liq   , 
               r38_prov  , 
               r38_desc  , 
               r38_proc  ,
               z01_nome,
               z01_cgccpf,
               z01_numcgm,
               length(trim(r38_agenc)) as qtddigitosagencia,
               r70_descr,
               length(trim(z01_cgccpf)) as tam,
               r38_liq as valorori      
           from ($sql) as x 
           order by r38_banco,r38_agenc, r38_conta ";
  //echo "<BR> $sql";exit;
  $result  = $clfolha->sql_record($sql);
  $numrows = $clfolha->numrows;

  $titarquivo = "pgtofunc_" . $db90_codban;

  /*
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
                                            "$rh34_wherefolha and $campovalor > 0");

  // die($sql);
  $result  = $clpensao->sql_record($sql);
  // db_criatabela($result);exit;
  $numrows = $clpensao->numrows;
  */

  if($numrows > 0 && $rh34_codban == "041"){
    $nomearquivo_impressao = "/tmp/".$titarquivo.".pdf";
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
    $head6 = "GERA��O  :  ".db_formatar($datagera,"d").' AS '.date("H").':'.date("i").':'.date("s").' HS';
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
          $pdf->cell(65,$alt,"Funcion�rio",1,0,"C",1);
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
	  if(($tipo) == "O"){
	    $tipopaga = "10";
	  }else{
	    $tipopaga = "01";
	  }
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
      if($acodigodobanco == $r38_banco || $r38_liq<5000){
        $compensacao = "010";
      }else{
	if($r38_liq>=5000){
	  $compensacao = "018";
	}
      }

      $agenciapagarT = str_replace('.','',str_replace('-','',$r38_agenc));
      $contasapagarT = str_replace('.','',str_replace('-','',$r38_conta));


      $agenciapagarT = db_formatar($agenciapagarT,'s','0', 5,'e',0);
      $contasapagarT = db_formatar($contasapagarT,'s','0',10,'e',0);

      $contasapagarT+= 0;
//      if($contasapagarT == 0){
//	continue;
//      }
      $contasapagarT = db_formatar($contasapagarT,'s','0',10,'e',0);

      $agenciapagar = substr($agenciapagarT,0,3);
      $digitoagenci = substr($agenciapagarT,4,1);

      $contasapagar = substr($contasapagarT,0,10);

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
      $pdf->cell(15,$alt,$r38_agenc,1,0,"R",0);
      $pdf->cell(25,$alt,$r38_conta,1,1,"R",0);


      $cllayout_BBBS->BSregist_001_003 = $acodigodobanco;
      $cllayout_BBBS->BSregist_004_007 = $seq_header;
      $cllayout_BBBS->BSregist_009_013 = $seq_detalhe;
      $cllayout_BBBS->BSregist_018_020 = $compensacao;
      $cllayout_BBBS->BSregist_021_023 = $r38_banco;
      $cllayout_BBBS->BSregist_024_028 = $agenciapagar;
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
    $cllayout_BBBS->BBBStraillerA_018_023 = $seq_header;
    $cllayout_BBBS->BBBStraillerA_024_029 = $registro;
    $cllayout_BBBS->geraTRAILLERArquivo();

    $cllayout_BBBS->gera();

    $pdf->setfont('arial','b',8);

    $pdf->cell(190,$alt,'Total de funcion�rios',1,0,"C",1);
    $pdf->cell(20,$alt,$xtotal_func,1,0,"R",1);
    $pdf->cell(70,$alt,'',1,1,"C",1);

    $pdf->cell(190,$alt,'Total Geral',1,0,"C",1);
    $pdf->cell(20,$alt,db_formatar($xvaltotal,'f'),1,0,"R",1);
    $pdf->cell(70,$alt,'',1,1,"C",1);

    $pdf->Output($nomearquivo_impressao,false,true);
  }else if($numrows > 0){
    db_fieldsmemory($result,0);

    $idregistroimpressao = $posicao;
    $nomearquivo_impressao = "/tmp/folha_".$db90_codban."_".$paramnome.".pdf";
    if(!is_writable("/tmp/")){
      $sqlerro= true;
      $erro_msg = 'Sem permiss�o de gravar o arquivo. Contate suporte.';
    }  

    ///// INICIA IMPRESS�O DO RELAT�RIO
    $pdf = new PDF();
    $pdf->Open();
    $pdf->AliasNbPages();
    $pdf->setfillcolor(235);
    $total = 0;
    $alt = 4;

    $head3 = "ARQUIVO PAGAMENTO FOLHA";
    $head5 = "SEQUENCIAL DO ARQUIVO  :  ".$sequenciaarqui;
    $head6 = "GERA��O  :  ".db_formatar($datagera,"d").' AS '.$horageracao.' HS';
    $head7 = "PAGAMENTO:  ".db_formatar($datadedeposito,"d");
    $head8 = 'BANCO : '.$rh34_codban.' - '.$db90_descr;


    $loteservic = 1;
    $finalidadedoc = "00";
    $codigocompromisso = substr($rh34_convenio,6,4);
    $tipocompromisso = "02";
    $agencialote = $rh34_agencia;

    $loteservico = 1;
    $tiposervico = "30";
    /*
    if($db90_codban == $r38_banco){
      $formalancamento = "01";
    }else{
      $formalancamento = "03";
    }
    */
    $formalancamento = ""; // Far� imprimir '00'

    ///// HEADER DO LOTE
    db_setaPropriedadesLayoutTxt(&$db_layouttxt, 2);
    ///// FINAL DO HEADER DO LOTE

    $sequencialnolote = 0;

    $quantidadefuncionarios = 0;
    $valortotal = 0;

    for($i=0;$i<$numrows;$i++){

      db_fieldsmemory($result,$i);
      //////////////////////////////////////////////
      // CAMPOS LAYOUT CEF       
      $agencia = substr(db_formatar(str_replace('.','',str_replace('-','',$r38_agenc)),'s','0', 5,'e',0),0,4);
      $conta   = trim(str_replace(',','',str_replace('.','',str_replace('-','',$r38_conta))));
      $qtddigitosconta = strlen($conta) - 4; /////// -4, pois -1 � do dvconta e -3 do codigooperacao
      $dvconta = substr($conta,-1);
      $codigooperacao = substr($conta,0,3);
      $conta = substr($conta, 3, $qtddigitosconta);
      //////////////////////////////////////////////
      //////////////////////////////////////////////


      //////////////////////////////////////////////
      // CAMPOS LAYOUT CNAB240
      $sequencialnolote ++;
      $compensacao = "700";
      if($r38_banco == $db90_codban){
        $compensacao = str_repeat('0',3);
      }

      $agenciapagarT = db_formatar(str_replace('.','',str_replace('-','',$r38_agenc)),'s','0', 5,'e',0);
      $contasapagarT = db_formatar(str_replace('.','',str_replace('-','',$r38_conta)),'s','0',12,'e',0);

      $agenciapagar = substr($agenciapagarT,0,4);
      $digitoagenci = substr($agenciapagarT,4,1);

      $contasapagar = substr($contasapagarT,0,11);
      $digitocontas = substr($contasapagarT,11,1);

      $bancofavorecido = $r38_banco;
      $agenciafavorecido = $agenciapagar;
      $dvagenciafavorecido = $digitoagenci;
      $contafavorecido  = $contasapagar;
      $dvcontafavorecido = $digitocontas;
      $dvagenciacontafav = " ";
      $numerocontrolemov = $r38_regist;
//echo "<BR> rh34_codban --> $rh34_codban layout --> $layout";      
      if($rh34_codban != "104" || $layout == 4){
        if($layout == 4){
           $sequencialreg = $i + 1;
        }else{
           $sequencialreg = $i + 2;
        }
//echo "<BR> 2 sequencialreg --> $sequencialreg";
      }else{
        $sequencialreg = '';
//echo "<BR> 3 sequencialreg --> $sequencialreg";
      }
      if(isset($layout) && $layout == 18){
        $sequencialreg = str_repeat(' ', 6);
//echo "<BR> 4 sequencialreg --> $sequencialreg";
      }
      //////////////////////////////////////////////
      //////////////////////////////////////////////
      
      //////////////////////////////////////////////
      // CAMPOS LAYOUT SANTANDER
      $instrucaomovimento = "1";
      if($db90_codban == "008"){
        $compensacao = "018";
        $dvagenciacontafav = "00";
      }

      $valordebito = $r38_liq;
      $dataprocessamento = $datadedeposito;
//      $sequencialreg = "      ";

      ///// REGISTRO A
      db_setaPropriedadesLayoutTxt(&$db_layouttxt, 3, $posicao);
      ///// FINAL DO REGISTRO A

      if($tam == 11){
        $tipoinscricaofav = "1";
      }else if($tam == 14){
        $tipoinscricaofav = "2";
      }else{
        $tipoinscricaofav = "3";
      }
      $datavencimento = $datadedeposito;
      $valorvencimento = $r38_liq;
      ///// REGISTRO B
//      db_setaPropriedadesLayoutTxt(&$db_layouttxt, 3, "B");
      ///// FINAL DO REGISTRO B

      if($i == 0 || $pdf->gety() > $pdf->h - 30){
	$pdf->addpage("L");
        $pdf->cell(15,$alt,$RLrh01_regist,1,0,"C",1);
        if($tiparq < 5){
          $pdf->cell(15,$alt,$RLz01_numcgm,1,0,"C",1);
          $pdf->cell(20,$alt,$RLz01_cgccpf,1,0,"C",1);
          $pdf->cell(65,$alt,$RLz01_nome,1,0,"C",1);
          $pdf->cell(65,$alt,$RLr70_descr,1,0,"C",1);
	}else{
          $pdf->cell(65,$alt,"Funcion�rio",1,0,"C",1);
          $pdf->cell(65,$alt,"Pensionista",1,0,"C",1);
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
      if($tiparq < 5){
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
      $pdf->cell(17,$alt,db_formatar($r38_liq,'f'),1,0,"R",0);
      $pdf->cell(13,$alt,$r38_agenc,1,0,"R",0);
      $pdf->cell(20,$alt,$r38_conta,1,1,"R",0);
      $quantidadefuncionarios ++;
      $valortotal += $r38_liq;
    }
    $pdf->setfont('arial','b',8);
    $pdf->cell(160,$alt,'Total de funcion�rios',1,0,"C",1);
    $pdf->cell(20,$alt,$quantidadefuncionarios,1,0,"R",1);
    $pdf->cell(50,$alt,'',1,1,"C",1);

    $pdf->cell(160,$alt,'Total Geral',1,0,"C",1);
    $pdf->cell(20,$alt,db_formatar($valortotal,'f'),1,0,"R",1);
    $pdf->cell(50,$alt,'',1,1,"C",1);

    $quantidadetotallote = $sequencialnolote + 2;
    $valortotallote = $valortotal;
    ///// TRAILLER DE LOTE
    db_setaPropriedadesLayoutTxt(&$db_layouttxt, 4);
    ///// FINAL DO TRAILLER DE LOTE



    // VARIAVEIS PARA TRAILLER CEF
    $quanttrailler = $quantidadefuncionarios + 2;
    $valortrailler = $valortotal;
    $sequencialreg += 1;
//echo "<BR> 1 sequencialreg --> $sequencialreg";
    //////////////////////////////////
    //////////////////////////////////


    // VARIAVEIS PARA TRAILLER CNAB240
    $quantidadelotesarq = 1;
    $quantidaderegistarq = $quantidadetotallote + 2;
    //////////////////////////////////
    //////////////////////////////////


    ///// TRAILLER DE ARQUIVO
//    if(isset($layout) && $layout == 3){
//      $brancos109 = db_formatar(substr(trim(str_replace(".","",str_replace(",","",db_formatar($valortrailler,"f")))),0,17),"s","0",17,"e",0);
//      $valortrailler = str_repeat(" ",17);
//      $fixa999 = str_repeat(" ",6);
//    }
    db_setaPropriedadesLayoutTxt(&$db_layouttxt, 5);
    ///// FINAL DO TRAILLER DE ARQUIVO
    //////////////////////////////////

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
?>