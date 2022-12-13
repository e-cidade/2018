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
require_once(modification("libs/db_utils.php"));
require_once(modification("classes/db_folha_classe.php"));
require_once(modification("classes/db_rharqbanco_classe.php"));
require_once(modification("classes/db_orctiporec_classe.php"));
require_once(modification("classes/db_rhpessoalmov_classe.php"));

parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
db_postmemory($HTTP_POST_VARS);

$cllayouts_bb     = new LayoutBB;
$cllayout_BBBS    = new LayoutBBBSFolha;
$clfolha          = new cl_folha;
$clrharqbanco     = new cl_rharqbanco;
$clorctiporec     = new cl_orctiporec;
$oDaoRhPessoalMov = new cl_rhpessoalmov();
$clrotulo         = new rotulocampo;
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

  }else{

    include(modification("dbforms/db_layouttxt.php"));
    if($rh34_codban == "001"){
      $layoutimprime = 2;
    }else if($rh34_codban == "008" || $rh34_codban == "033"){
      $layoutimprime = 21;
    }else if(isset($layout) && $layout == 9){
      $layoutimprime = 9;
    }else{
      $agenciaheader = str_repeat(' ',4);
      $layoutimprime = 3;
      $posicao = "E";
    }
    $db90_codban = $rh34_codban;
    $conveniobanco = trim($rh34_convenio); 
    $agenciaheader = $rh34_agencia;
    $agencialote   = $rh34_agencia;
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
      if($digitos>1){
        $dvagenciacontaheader = $rh34_dvconta[1];
      }
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

    /// alterado pelo sandro

    $endereco    = $ender;
    $numero      = $numero;
    $complementoendereco = '';
    $cidadepref  = $munic;
    $ceppref     = substr($cep,0,5);
    $complcep    = substr($cep,5,3);
    $ufpref      = $uf;

    $sequencialarq = $rh34_sequencial;
    $usoprefeitura1 = $rh34_sequencial;

    $adatadegeracao = $datag_ano."-".$datag_mes."-".$datag_dia;
    $datadedeposito = $datad_ano."-".$datad_mes."-".$datad_dia;
 
    $sequenciaarqui = $rh34_sequencial;
    $versaodoarquiv = "030";
    if($rh34_codban == "033"){
       $versaodoarquiv = "060";
    }

    $paramnome = $datag_mes.$datag_ano."_".$horageracao;
    $nomearquivo = "folha_".$db90_codban."_".$paramnome.".txt";
    if($rh34_codban == "001"){
      $posicao = "A";
    }else{
      $posicao = "E";
    }
    $db_layouttxt = new db_layouttxt($layoutimprime,"tmp/".$nomearquivo, $posicao);
    db_setaPropriedadesLayoutTxt($db_layouttxt,1);
  }

}else{
  $sqlerro = true;
  $erro_msg = "Arquivo não encontrado";
}

if(!isset($rh34_where) || (isset($rh34_where) && trim($rh34_where) == "")){
  $rh34_where = "";
}

if (!empty($rh34_where)) {
  $rh34_where .= " and ";
}
$rh34_where .= " r38_liq > 0";
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
  
  if ($ordem == 1) {
    $sOrderBy = "r38_banco,r38_agenc, r38_conta";
  } else {
    $sOrderBy = "r38_nome";
  }
  $sql = $clfolha->sql_query_gerarqbag(null,"$campos,length(trim(r38_agenc)) as qtddigitosagencia,
                                             r70_descr,
                                             length(trim(cgm.z01_cgccpf)) as tam,
                                             r38_liq as valorori",
                                            "r38_banco,r38_agenc,r38_conta",
                                            "$rh34_where");
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
           order by $sOrderBy";
 // echo $sql1;exit;
  $result  = $clfolha->sql_record($sql1);
  $numrows = $clfolha->numrows;
  if($numrows > 0 && $rh34_codban == "041"){
    $nomearquivo_impressaosintetico = "/tmp/folha_".$datad_dia."-".$datad_mes."-".$datad_ano."sintetico.pdf";
    $nomearquivo_impressao = "/tmp/folha_".$datad_dia."-".$datad_mes."-".$datad_ano.".pdf";
    $nomearquivo = "folha_".$datad_dia."-".$datad_mes."-".$datad_ano.".txt";
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

    $pdf1 = new PDF();
    $pdf1->Open();
    $pdf1->AliasNbPages();
    $pdf1->setfillcolor(235);
    $pdf1->addpage();

    $head3 = "ARQUIVO PAGAMENTO FUNCIONÁRIOS";
    $head4 = "SEQUENCIAL DO ARQUIVO  :  ".$sequenciaarqui;
    $head5 = "GERAÇÃO  :  ".db_formatar($datagera,"d").' AS '.date("H").':'.date("i").':'.date("s").' HS';
    $head6 = "PAGAMENTO:  ".db_formatar($datadeposit,"d");
    $head7 = 'BANCO : '.$rh34_codban.' - '.$db90_descr;
    $head8 = "CONTA: " . $rh34_conta . " - " . $rh34_dvconta;


    $pdf1 = new PDF();
    $pdf1->Open();
    $pdf1->AliasNbPages();
    $pdf1->setfillcolor(235);
    $pdf1->addpage();

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
        $pdf->cell(20,$alt,$RLz01_numcgm,1,0,"C",1);
        $pdf->cell(65,$alt,$RLz01_nome,1,0,"C",1);
        $pdf->cell(20,$alt,$RLz01_cgccpf,1,0,"C",1);
        $pdf->cell(65,$alt,$RLr70_descr,1,0,"C",1);
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

      $pdf->setfont('arial','',7);
      $pdf->cell(20,$alt,$r38_regist,1,0,"C",0);
      $pdf->cell(20,$alt,$z01_numcgm,1,0,"C",0);
      $pdf->cell(65,$alt,$z01_nome,1,0,"L",0);
      $pdf->cell(20,$alt,$z01_cgccpf,1,0,"C",0);
      $pdf->cell(65,$alt,$r70_descr,1,0,"L",0);
      $pdf->cell(20,$alt,db_formatar($r38_liq,'f'),1,0,"R",0);
      $pdf->cell(15,$alt,$codpgto,1,0,"C",0);
      $pdf->cell(15,$alt,$r38_banco,1,0,"C",0);
      $pdf->cell(15,$alt,$r38_agenc,1,0,"R",0);
      $pdf->cell(25,$alt,$r38_conta,1,1,"R",0);

      if($bancoanterior != $r38_banco){

        $bancoanterior = $r38_banco;

               
  if($acodigodobanco == '041'){
    $tiposerv = "30";
    if(($r38_conta+0) == 0){
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
      
      $contasapagarT += 0;

      if($qtddigitosagencia == 5){
        $agenciapagarT = substr($agenciapagarT, 0, 4);
      }
      
      $agenciapagarT = db_formatar($agenciapagarT,'s','0', 5,'e',0);
      $contasapagarT = db_formatar($contasapagarT,'s','0',10,'e',0);
      
      $sCampos = "rh44_agencia as agencia, rh44_dvagencia as digito";
      $sWhere  = "     rh02_regist = {$r38_regist}";
      $sWhere .= " and rh02_anousu = " . db_anofolha();
      $sWhere .= " and rh02_mesusu = " . db_mesfolha();
      
      $sSqlRhPessoalMov = $oDaoRhPessoalMov->sql_query_dados_bancario(null, null, $sCampos, null, $sWhere);
      $rsRhPessoalMov   = $oDaoRhPessoalMov->sql_record($sSqlRhPessoalMov);
      
      if ($oDaoRhPessoalMov->numrows != 1) {
      
        $sMsgErro  = "Não foi encontrada a agencia do servidor: ";
        $sMsgErro .= "{$r38_regist} - {$r38_nome}.\\nFavor verificar.";
        
        $sqlerro  = true;
        $erro_msg = $sMsgErro;
        
      }
      
      $oRhPessoalMov = db_utils::fieldsMemory($rsRhPessoalMov, 0);
      
      $agenciapagar = $oRhPessoalMov->agencia;
      $digitoagenci = $oRhPessoalMov->digito;

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

        if($r38_banco == 0){
          $r38_banco = '041';
        }

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

    $pdf->cell(190,$alt,'Total de funcionários',1,0,"C",1);
    $pdf->cell(20,$alt,$xtotal_func,1,0,"R",1);
    $pdf->cell(70,$alt,'',1,1,"C",1);

    $pdf->cell(190,$alt,'Total Geral',1,0,"C",1);
    $pdf->cell(20,$alt,db_formatar($xvaltotal,'f'),1,0,"R",1);
    $pdf->cell(70,$alt,'',1,1,"C",1);

    $pdf1->cell(80,$alt,"Credor",1,0,"C",1);
    $pdf1->cell(30, $alt, "Número de funcionários",1,0,"C",1);
    $pdf1->cell(30, $alt, "Valor total",1,1,"C",1);

    $pdf1->cell(80,$alt, "FOLHA DE PAGAMENTO",0,0,"L",0);
    $pdf1->cell(30, $alt, $xtotal_func,0,0,"R",0);
    $pdf1->cell(30, $alt, db_formatar($xvaltotal,'f'),0,1,"R",0);

    $pdf1->text(35,$pdf->h - 14,'______________________________',0,4);
    $pdf1->text(52,$pdf->h - 11,'Prefeito',0,4);
    $pdf1->text(85,$pdf->h - 14,'______________________________',0,4);
    $pdf1->text(94,$pdf->h - 11,'Secretário da Fazenda',0,4);
    $pdf1->text(135,$pdf->h - 14,'______________________________',0,4);
    $pdf1->text(152,$pdf->h - 11,'Tesoureiro',0,4);

    $pdf->Output($nomearquivo_impressao,false,true);
    $pdf1->Output($nomearquivo_impressaosintetico,false,true);
  }else if($numrows > 0){

    db_fieldsmemory($result,0);

    $idregistroimpressao = ($rh34_codban=="001"?"A":"E");
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

    $loteservico = 1;
    $tiposervico = "30";
    if($db90_codban == $r38_banco){
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
      // CAMPOS LAYOUT CEF       
      $agencia = db_formatar(str_replace('.','',str_replace('-','',$r38_agenc)),'s','0', 4,'e',0);
      $conta   = trim(str_replace(',','',str_replace('.','',str_replace('-','',$r38_conta))));
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
      if($r38_banco == $db90_codban){
        $compensacao = str_repeat('0',3);
      }

      $agenciapagarT = db_formatar(str_replace('.','',str_replace('-','',$r38_agenc)),'s','0', 6,'e',0);
      $contasapagarT = db_formatar(str_replace('.','',str_replace('-','',$r38_conta)),'s','0',13,'e',0);

      $agenciapagar = substr($agenciapagarT,0,5);
      $digitoagenci = substr($agenciapagarT,5,1);

      $contasapagar = substr($contasapagarT,0,12);
      $digitocontas = substr($contasapagarT,12,1);

      $bancofavorecido = $r38_banco;
      $agenciafavorecido = $agenciapagar;
      $dvagenciafavorecido = $digitoagenci;
      $contafavorecido  = $contasapagar;
      $dvcontafavorecido = $digitocontas;
      $dvagenciacontafav = " ";
      $numerocontrolemov = $r38_regist;
      //////////////////////////////////////////////
      //////////////////////////////////////////////
      
      $valordebito = $r38_liq;
      $dataprocessamento = $datadedeposito;

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
      $valorvencimento = $r38_liq;
      ///// REGISTRO B
//      db_setaPropriedadesLayoutTxt(&$db_layouttxt, 3, "B");
      ///// FINAL DO REGISTRO B

      if($i == 0 || $pdf->gety() > $pdf->h - 30){ 
  $pdf->addpage("L");
        $pdf->cell(20,$alt,$RLrh01_regist,1,0,"C",1);
        $pdf->cell(20,$alt,$RLz01_numcgm,1,0,"C",1);
        $pdf->cell(100,$alt,$RLz01_nome,1,0,"C",1);
        $pdf->cell(20,$alt,$RLz01_cgccpf,1,0,"C",1);
        $pdf->cell(20,$alt,$RLr38_liq,1,0,"C",1);
        $pdf->cell(15,$alt,$RLr38_banco,1,0,"C",1);
        $pdf->cell(15,$alt,$RLr38_agenc,1,0,"C",1);
        $pdf->cell(20,$alt,$RLr38_conta,1,1,"C",1);
        $pdf->ln(3);
      }

      $pdf->setfont('arial','',7);
      $pdf->cell(20,$alt,$r38_regist,1,0,"C",0);
      $pdf->cell(20,$alt,$z01_numcgm,1,0,"C",0);
      $pdf->cell(100,$alt,$z01_nome,1,0,"L",0);
      $pdf->cell(20,$alt,$z01_cgccpf,1,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($r38_liq,'f'),1,0,"R",0);
      $pdf->cell(15,$alt,$r38_banco,1,0,"C",0);
      $pdf->cell(15,$alt,$r38_agenc,1,0,"R",0);
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
    //////////////////////////////////
    //////////////////////////////////


    // VARIAVEIS PARA TRAILLER CNAB240
    $quantidadelotesarq = 1;
    $quantidaderegistarq = $quantidadetotallote + 2;
    //////////////////////////////////
    //////////////////////////////////


    ///// TRAILLER DE ARQUIVO
    db_setaPropriedadesLayoutTxt($db_layouttxt, 5);
    ///// FINAL DO TRAILLER DE ARQUIVO
    //////////////////////////////////

    $pdf->Output($nomearquivo_impressao,false,true);
  }else{
    $sqlerro = true;
    $erro_msg = "Nenhum registro encontrado. Contate o suporte.";
  }
}

$nomearquivo_impressaosintetico = isset($pdf1) ? $nomearquivo_impressaosintetico : "";
if($sqlerro == false){
  echo "
  <script>
    parent.js_detectaarquivo('tmp/$nomearquivo','$nomearquivo_impressao','$nomearquivo_impressaosintetico');
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
