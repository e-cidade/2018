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
include(modification("dbforms/db_layouttxt.php"));
include(modification("classes/db_folha_classe.php"));
include(modification("classes/db_rharqbanco_classe.php"));
include(modification("classes/db_orctiporec_classe.php"));
include(modification("classes/db_pensao_classe.php"));

parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
db_postmemory($HTTP_POST_VARS);

$clpensao      = new cl_pensao;
$clfolha       = new cl_folha;
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

$sqlerro = false;

db_sel_instit(db_getsession("DB_instit"));

$result_arqbanco=$clrharqbanco->sql_record($clrharqbanco->sql_query($rh34_codarq));    
if($clrharqbanco->numrows>0){
  db_fieldsmemory($result_arqbanco,0);

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

  $paramnome = $datag_mes.$datag_ano."_".date("H").date("i");

  $adatadegeracao = $datag_ano."-".$datag_mes."-".$datag_dia;
  $datadedeposito = $datad_ano."-".$datad_mes."-".$datad_dia;

  $anomesgera = $datadedeposito;

  $sequenciaarqui = $rh34_sequencial;
  $ahoradegeracao = date("H").date("i").date("s");
  $nomearquivotxt = "/tmp/CC120".$paramnome.".txt";
  $nomearquivopdf = "/tmp/CC120".$paramnome.".pdf";

  $sequencialbb120 = 1;
  $dvagenciaheader = '';
  $dvagenciacontaheader = '';
  if($rh34_codban == "104"){
    if(trim($rh34_dvconta)!=""){
      $dvcontaheader  = $rh34_dvconta[0];
      $digitos        = strlen($rh34_dvconta);
      if($digitos>1){
        $dvagenciacontaheader = $rh34_dvconta[1];
      }
    }
    $agenciaheader = str_repeat(' ',4);
    $layoutimprime = $layout;
    $posicao       = "E";
//    $rh34_convenio = substr($rh34_convenio,0,6)."060001        ";
    $contaheader = $rh34_conta;
    $contalote   = $rh34_conta;
    $operacaoheader = substr($contaheader,0,3);
//    $contaheader2   = str_pad(trim(substr($contaheader,3,20)),8);
    $contaheader2   =  db_formatar( substr($contaheader,3,20),'s','0',8,'e',0);
    $dvagencialote = $dvagenciaheader;
    $dvcontalote   = $dvcontaheader;
    $dvagenciacontalote = $dvagenciacontaheader;

  }
  $db90_codban = $rh34_codban;
  $agenciaheader = $rh34_agencia;
  $agencialote   = $rh34_agencia;
  $contaheader = $rh34_conta;
  $contalote   = $rh34_conta;
  $datageracao = $datagera; 
  $sequencialarq = $rh34_sequencial;
  $descrarquivo = "FOLHA PAGAMENTO"; // Campo somente do layout 3

}else{
  $sqlerro = true;
  $erro_msg = "Arquivo não encontrado";
}

if($sqlerro == false){
  db_inicio_transacao();

//  $rh34_convenio = substr($rh34_convenio,0,6)."060001        ";

  if(!isset($rh34_where) || (isset($rh34_where) && trim($rh34_where) == "")){
    $rh34_wherefolha = "";
    $rh34_wherepensa = ""; 
  }else{
    $rh34_wherefolha = $rh34_where." and ";
    $rh34_wherepensa = $rh34_where." and "; 
  }

  $rh34_wherefolha.= " r38_banco = '$rh34_codban' ";
  $rh34_wherepensa.= " r52_codbco = '$rh34_codban' and r52_anousu = ".db_anofolha()." and r52_mesusu = ".db_mesfolha();

  $titrelatorio = "Todos os funcionários";
  $titarquivo   = "pagtofuncionarios";


  $conveniobanco = trim($rh34_convenio); 

  if($sqlerro == false){
    if($tiparq == 0){
    
    $rh34_wherefolha .= 'and r38_liq > 0';

    $sql = $clfolha->sql_query_gerarqbag(null,"folha.*,cgm.*,
                                            length(trim(z01_cgccpf)) as tam",
                                           "",
                                           " $rh34_wherefolha order by r38_regist");
//     echo "<br><br> $sql ";exit;
//     


    $result  =  $clfolha->sql_record($sql);
    $numrows =  $clfolha->numrows;
  
  }else{
      $titrelatorio = "PENSÃO JUDICIAL ";
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
                                               case when trim(translate(r52_conta,'0','')) = '' then '02' 
                                                else '01' end as tipo_pagamento,
	                                        trim(r52_conta)||trim(coalesce(r52_dvconta,'')) as r38_conta,
	                                       trim(r52_codage)||trim(coalesce(r52_dvagencia,'')) as r38_agenc,
	                                       cgm.*,func.z01_nome as nomefuncionario,
                                               r70_descr,
                                               length(trim(cgm.z01_cgccpf)) as tam,
                                               $campovalor as valorori",
                                              "r52_codbco, tipo_pagamento, cgm.z01_nome",
                                              "$rh34_wherepensa and $campovalor > 0");

    $result  = $clpensao->sql_record($sql);
    $numrows = $clpensao->numrows;
  }
// die($sql);

    if($numrows > 0){

      $registro = 2;
      db_fieldsmemory($result,0);

      if(!is_writable("/tmp/")){
        $sqlerro= true;
        $erro_msg = 'Sem permissão de gravar o arquivo. Contate suporte.';
      }  

      ///// INICIA IMPRESSÃO DO RELATÓRIO
      $pdf = new PDF();
      $pdf->Open();
      $pdf->AliasNbPages();
      $pdf->setfillcolor(235);
      $alt = 4;

      $totalvalor             = 0;
      $totalquant             = 0;
      $entrar                 = true;

      $quantidadefuncionarios = 0;
      $valortotal             = 0;
      $sequencialreg          = 0;

      $head1 = "ARQUIVO PAGAMENTO FOLHA";
      $head2 = "AGENCIA/CONTA";
      $head3 = "SEQUENCIAL DO ARQUIVO  :  ".$sequenciaarqui;
      $head4 = "GERAÇÃO  :  ".db_formatar($datagera,"d").' AS '.$ahoradegeracao.' HS';
      $head5 = "PAGAMENTO:  ".db_formatar($datadeposit,"d");
      $head6 = 'BANCO : '.$rh34_codban.' - '.$db90_descr;
      $head7 = 'AG./CONTA: '.$agenc.'-'.$digia.' / '.$conta.'-'.$digic;

      for($i=0;$i<$numrows;$i++){
	db_fieldsmemory($result,$i);

        $rh34_codban    = $banco;
        $rh34_agencia   = $agenc;
        $rh34_dvagencia = $digia;
        $rh34_conta     = $conta;
        $rh34_dvconta   = $digic;

        $agencia = db_formatar(str_replace('.','',str_replace('-','',$r38_agenc)),'s','0', 5,'e',0);
        $agencia = substr($agencia,0,4);
        $conta   = trim(str_replace(',','',str_replace('.','',str_replace('-','',$r38_conta))));
        $qtddigitosconta = strlen($conta) - 4; /////// -4, pois -1  do dvconta e -3 do codigooperacao
        $dvconta = substr($conta,-1);
        $codigooperacao = substr($conta,0,3);
        $conta = substr($conta, 3, $qtddigitosconta);
        // echo "<br><br> rh34_conta --> $rh34_conta    contaheader2 --> $contaheader2 "  ;exit;

        if($entrar == true){
          $db_layouttxt = new db_layouttxt($layoutimprime,$nomearquivotxt);
          db_setaPropriedadesLayoutTxt(&$db_layouttxt,1);
	      }
        $sequencialreg       = $i + 1;

        if($entrar == true || $pdf->gety() > $pdf->h - 30){
          $pdf->addpage();
   

          $pdf->setfont('arial','b',8);
          $pdf->cell(15,$alt,$RLrh01_regist,1,0,"C",1);
          $pdf->cell(15,$alt,$RLz01_numcgm,1,0,"C",1);
          $pdf->cell(70,$alt,$RLz01_nome,1,0,"C",1);
          $pdf->cell(20,$alt,$RLz01_cgccpf,1,0,"C",1);
          $pdf->cell(20,$alt,$RLr38_liq,1,0,"C",1);
          $pdf->cell(15,$alt,$RLr38_banco,1,0,"C",1);
          $pdf->cell(15,$alt,$RLr38_agenc,1,0,"C",1);
          $pdf->cell(20,$alt,$RLr38_conta,1,1,"C",1);
          $entrar = false;
          $pdf->ln(1);
        }


        $pdf->setfont('arial','',7);
        $pdf->cell(15,$alt,$r38_regist,1,0,"C",0);
        $pdf->cell(15,$alt,$z01_numcgm,1,0,"C",0);
        $pdf->cell(70,$alt,$z01_nome,1,0,"L",0);
        $pdf->cell(20,$alt,$z01_cgccpf,1,0,"R",0);
        $pdf->cell(20,$alt,db_formatar($r38_liq,'f'),1,0,"R",0);
        $pdf->cell(15,$alt,$r38_banco,1,0,"C",0);
        $pdf->cell(15,$alt,$r38_agenc,1,0,"R",0);
        $pdf->cell(20,$alt,$r38_conta,1,1,"R",0);

        $quantidadefuncionarios ++;
        $valortotal += $r38_liq;

        $totalquant ++;
      	$totalvalor += $r38_liq;

        $sequencialbb120 ++;
        $dvcontafunc   = substr($r38_conta,-1);
        $contafunc     = substr($r38_conta,0,(strlen($r38_conta) - 1));
        $dvagenciafunc = substr($r38_agenc,-1);
        $agenciafunc   = substr($r38_agenc,0,(strlen($r38_agenc) - 1));
      	$rh01_regist   = $r38_regist;
        $dataprocessamento = $datadedeposito;
        $valordebito = $r38_liq;
        if($layoutimprime != 18){
          $sequencialreg = "      ";
        }
        db_setaPropriedadesLayoutTxt(&$db_layouttxt,3);
      }

      $sequencialbb120 ++;

      $pdf->ln(2);

      $pdf->setfont('arial','b',8);
      $pdf->cell(100,$alt,'Totalização geral',"LTB",0,"R",1);
      $pdf->cell(20,$alt,$totalquant,"TB",0,"R",1);
      $pdf->cell(20,$alt,db_formatar($totalvalor,"f"),"TB",0,"C",1);
      $pdf->cell(50,$alt,"","RTB",1,"C",1);
      // VARIAVEIS PARA TRAILLER CEF
      $valortrailler  = $valortotal;
      $quanttrailler  = $quantidadefuncionarios + 2;
      $sequencialreg += 1;

      db_setaPropriedadesLayoutTxt(&$db_layouttxt,5);

      //////////////////////////////////
      $pdf->Output($nomearquivopdf,false,true);
    }else{
      $sqlerro  = true;
      $erro_msg = "Sem dados para gerar arquivo";
    }
  }


  if($sqlerro == false){
    echo "
    <script>
      parent.js_detectaarquivo('$nomearquivotxt','$nomearquivopdf');
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

}
?>