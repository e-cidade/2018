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
include(modification("classes/db_rharqbanco_classe.php"));
include(modification("classes/db_rhlocaltrab_classe.php"));
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
db_postmemory($HTTP_POST_VARS);
//db_postmemory($HTTP_SERVER_VARS,2);
//db_postmemory($HTTP_POST_VARS,2);exit;

$tiparq = 4;

$cllayouts_bb  = new LayoutBB;
$cllayout_BBBS = new LayoutBBBSFolha;
$clrharqbanco  = new cl_rharqbanco;
$clrhlocaltrab = new cl_rhlocaltrab;
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
$result_local=$clrhlocaltrab->sql_record($clrhlocaltrab->sql_query($local));    
db_fieldsmemory($result_local,0);

//echo ' Local : '.$rh55_descr;exit;

$result_arqbanco=$clrharqbanco->sql_record($clrharqbanco->sql_query($rh34_codarq));    
if($clrharqbanco->numrows>0){

  db_fieldsmemory($result_arqbanco,0);

  $paramnome = $ano.$mes;

  $nomearquivo = "banco_".$rh34_codban."_".$paramnome."_local$local";


  $wherefolha = " where  r38_banco = '$rh34_codban' and rh56_localtrab = $local ";
  $head1 = $rh55_descr;

  if($local == 3){
    $wherefolha .= " and trim(upper(rh01_clas1)) = '$qfolha' ";
    $nomearquivo .= "_folha_$qfolha";
    if($qfolha == 'A'){
      $head1 = 'EFETIVOS - FOLHA DE PAGAMENTO DOS PROFESSORES E PESSOAL EM ATIVIDADE PEDAGOGICAS DO ENSINO FUNDAMENTAL (FOLHA-A)';
    }elseif($qfolha == 'B'){
      $head1 = 'EFETIVOS - FOLHA DE PAGAMENTO DO PESSOAL DA AREA ADMINISTRATIVA DAS ESCOLAS DO ENSINO FUNDAMENTAL (FOLHA-B)';
    }elseif($qfolha == 'C'){
      $head1 = 'EFETIVOS - FOLHA DE PAGAMENTO DO PESSOAL DA AREA ADMINISTRATIVA DAS CRECHES (FOLHA-C)';
    }elseif($qfolha == 'D'){
      $head1 = 'EFETIVOS - FOLHA DE PAGAMENTO DOS PROFESSORES DO ENSINO INFANTIL (FOLHA-D)';
    }elseif($qfolha == 'E'){
      $head1 = 'EFETIVOS - FOLHA DE PAGAMENTO DO PESSOAL DA UNIDADE ADMINISTRATIVA (FOLHA-E - 10%)';
    }elseif($qfolha == 'F'){
      $head1 = 'EFETIVOS - FOLHA DE PAGAMENTO DOS PROFESSORES EM EDUCACAO DE JOVENS E ADULTOS (EJA) (FOLHA-F)';
    }elseif($qfolha == 'G'){
      $head1 = 'EFETIVOS - FOLHA DE PAGAMENTO DOS PROFESSORES DAS CRECHES (FOLHA-G)';
    }
  }elseif($local == 9){
    if($qcc == 'd'){
      $nomearquivo .= "_cc_outras";
      $head1 = 'CARGOS EM COMISSAO - OUTRAS SECRETARIAS';
      $wherefolha .= " and o40_orgao not in (8, 9)";
    }elseif($qcc == 'e'){
      $nomearquivo .= "_cc_edu";
      $head1 = 'CARGOS EM COMISSAO - EDUCACAO';
      $wherefolha .= " and o40_orgao = 8 ";
    }elseif($qcc == 's'){
      $nomearquivo .= "_cc_sau";
      $head1 = 'CARGOS EM COMISSAO - SAUDE';
      $wherefolha .= " and o40_orgao = 9 ";
    }
  }

  $nomearquivo .= ".txt";

  include(modification("dbforms/db_layouttxt.php"));
  $posicao = "A";
  if($rh34_codban == "001"){
    $layoutimprime = 7;
    $posicao = "1";
  }else{
    $layoutimprime = 3;
    $posicao = "E";
  }
  $idcaixa = "P";
  $idcliente = "P";
  $db90_codban = $rh34_codban;
  $agenciaheader = $rh34_agencia;
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
  $operacaoheader = substr($contaheader,0,3);
  $contaheader2   = str_pad(trim(substr($contaheader,4,20)),8);
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

  $adatadegeracao = $datag_ano."-".$datag_mes."-".$datag_dia;
  $datadedeposito = $datad_ano."-".$datad_mes."-".$datad_dia;
  $anomesgera     = substr($datad_ano,2,2)."-".$datad_mes."-".$datad_dia;
 
  $sequenciaarqui = $rh34_sequencial;
  $versaodoarquiv = "030";

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

  db_setaPropriedadesLayoutTxt(&$db_layouttxt,1);

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


$rh34_wherefolha.= " r38_banco = '$rh34_codban' ";
$rh34_wherepensa.= " r52_codbco = '$rh34_codban' and r52_anousu = $ano and r52_mesusu = $mes ";
if($tiparq == 0){
  $titrelatorio = "Todos os funcionários";
  $titarquivo   = "pagtofuncionarios";
}

///// tirar depois
///$wherefolha = " where  r38_banco = '$rh34_codban'";


if($sqlerro == false){

  $sql = "select *,
                 length(trim(z01_cgccpf)) as tam
          from folha
               inner join rhpessoal on rh01_regist = r38_regist
               inner join cgm            on rh01_numcgm = z01_numcgm 
               inner join rhpessoalmov   on rh01_regist = rh02_regist 
                                        and rh02_anousu = $ano 
                                        and rh02_mesusu = $mes
               left  join rhpeslocaltrab on rh02_seqpes = rh56_seqpes 
                                        and rh56_princ  = true  
               left join rhlocaltrab     on rh56_localtrab = rh55_codigo
                                        and rh55_instit = rh02_instit
               inner join rhfuncao       on rh37_funcao = rh01_funcao
               inner join rhlota         on r70_codigo  = rh02_lota 
               left  join rhlotaexe      on rh26_codigo = r70_codigo
                                        and rh26_anousu = rh02_anousu
               left  join orcorgao       on o40_anousu  = rh26_anousu
                                        and o40_orgao   = rh26_orgao
               inner join rhregime       on rh02_codreg = rh30_codreg
                                        and rh02_instit = rh30_instit
               $wherefolha";
          
  $result  = db_query($sql);
  $numrows = pg_numrows($result);
//  die($sql);
//  db_criatabela($result);exit;
  $nomeprefeitura = "PREF. MUN. ARAPIRACA";
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

//    $head3 = "ARQUIVO PAGAMENTO FOLHA";
//    $head2 = "SEQUENCIAL DO ARQUIVO  :  ".$sequenciaarqui;
    $head3 = "GERAÇÃO  :  ".db_formatar($datagera,"d").' AS '.$horageracao.' HS';
    $head4 = "PAGAMENTO:  ".db_formatar($datadedeposito,"d");
    $head5 = 'BANCO : '.$rh34_codban.' - '.$db90_descr;


    $loteservic = 1;
    $finalidadedoc = "00";
    $codigocompromisso = substr($rh34_convenio,6,4);
    $tipocompromisso = "02";
    $agencialote = $agenciaheader;

    $loteservico = 1;
    $tiposervico = "30";
    if($db90_codban == $r38_banco){
      $formalancamento = "01";
    }else{
      $formalancamento = "03";
    }
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
//      if($rh34_codban == "001"){
        $agencia = db_formatar(str_replace('.','',str_replace('-','',$r38_agenc)),'s','0', 5,'e',0);
//      }else{
//        $agencia = substr(db_formatar(str_replace('.','',str_replace('-','',$r38_agenc)),'s','0', 5,'e',0),1,4);
//      }
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
      $contasapagarT = db_formatar(str_replace('.','',str_replace('-','',$r38_conta)),'s','0',15,'e',0);

      $agenciapagar = substr($agenciapagarT,0,5);
      $digitoagenci = substr($agenciapagarT,5,1);

      $contasapagar = substr($contasapagarT,0,14)+0;
      $digitocontas = substr($contasapagarT,14,1);
      $contasapagar = db_formatar($contasapagar,'s','0',12,'e',0);

      $bancofavorecido     = $r38_banco;
      $agenciafavorecido   = $agenciapagar;
      $dvagenciafavorecido = $digitoagenci;
      $contafavorecido     = $contasapagar;
      $dvcontafavorecido   = $digitocontas;
      $dvagenciacontafav   = " ";
      $numerocontrolemov   = $r38_regist;
      $rh01_regist         = $r38_regist;
      $agenciafunc         = substr($agenciapagarT,1,4);
      $dvagenciafunc       = substr($agenciapagarT,5,1);
      $contafunc           = substr($contasapagarT,2,12)+0;
      $dvcontafunc         = substr($contasapagarT,14,1);

      $sequencialreg       = $i + 1;
      $sequencialbb120     = $i + 2;
      //echo "<br> matricula --> $numerocontrolemov   banco --> $bancofavorecido   agencia --> $agenciafavorecido   dvagencia --> $dvagenciafavorecido   conta --> $contafavorecido  dvconta --> $dvcontafavorecido ";
      //////////////////////////////////////////////
      //////////////////////////////////////////////
      
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
    $pdf->cell(160,$alt,'Total de funcionários',1,0,"C",1);
    $pdf->cell(20,$alt,$quantidadefuncionarios,1,0,"R",1);
    $pdf->cell(50,$alt,'',1,1,"C",1);

    $pdf->cell(160,$alt,'Total Geral',1,0,"C",1);
    $pdf->cell(20,$alt,db_formatar($valortotal,'f'),1,0,"R",1);
    $pdf->cell(50,$alt,'',1,1,"C",1);

    $quantidadetotallote = $sequencialnolote + 2;
    $valortotallote      = $valortotal;
    $sequencialbb120    ++;
    ///// TRAILLER DE LOTE
    db_setaPropriedadesLayoutTxt(&$db_layouttxt, 4);
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
    db_setaPropriedadesLayoutTxt(&$db_layouttxt, 5);
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
