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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_libgertxtfolha.php");
include("libs/db_libpessoal.php");
include("classes/db_codmovsefip_classe.php");
include("classes/db_db_config_classe.php");
include("classes/db_cfpess_classe.php");
include("classes/db_rhpessoal_classe.php");
include("classes/db_rhpessoalmov_classe.php");
include("classes/db_rescisao_classe.php");
include("classes/db_afasta_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clcodmovsefip = new cl_codmovsefip;
$cldb_config = new cl_db_config;
$clcfpess = new cl_cfpess;
$clrhpessoal = new cl_rhpessoal;
$clrhpessoalmov = new cl_rhpessoalmov;
$clrescisao = new cl_rescisao;
$clafasta = new cl_afasta;
$cllayout_SEFIP= new cl_layout_SEFIP;
$db_opcao = 1;
$db_botao = true;

if(isset($gerar)){
  $mesant = $mesusu;
  $anoant = $anousu;
  $anoant = db_formatar($anoant,"s","0",4,"e",0);
  $mesant = db_formatar($mesant,"s","0",2,"e",0);

  $anousu = db_anofolha();
  $mesusu = db_mesfolha();

  $sqlerro = false;
  $result_db_config = $cldb_config->sql_record($cldb_config->sql_query_file(db_getsession("DB_instit")));
  if($cldb_config->numrows == 0){
    $sqlerro = true;
    $erro_msg = "ERRO: Instituição não encontrada. Arquivo não poderá ser gerado.";
  }else{

    $result_cfpess = $clcfpess->sql_record($clcfpess->sql_query_file($anousu,$mesusu));
    if($clcfpess->numrows == 0){
      $sqlerro = true;
      $erro_msg = "ERRO: Configuração da folha não encontrada para o Ano/Mês (".$anousu."/".$mesusu."). Arquivo não poderá ser gerado.";
    }else{

      db_inicio_transacao();

      db_fieldsmemory($result_db_config, 0);
      db_fieldsmemory($result_cfpess,0);

      $competenciai = $mesant.$anoant;
      $competenciaf = $mesusu.$anousu;

      $totaldeestabelecimentos = 1;
      $totaldemovimentos       = 1;

      $primeirodia  = $anousu."-".$mesusu."-01";
      include("libs/db_sql.php");
      $clgera_sql_folha = new cl_gera_sql_folha;
      $clgera_sql_folha->usar_res = true;
//      $clgera_sql_folha->usar_cgm = true;
      $clgera_sql_folha->usar_atv = true;
//      $clgera_sql_folha->usar_doc = true;
//      $clgera_sql_folha->usar_pad = true;
//      $clgera_sql_folha->usar_tpc = true;
//      $clgera_sql_folha->inner_atv = false;
//      $clgera_sql_folha->inner_pad = false;
//      $clgera_sql_folha->inner_tpc = false;

      function tpmov($causas,$tipadm,$rescis,$tpcontr){
        $tipomov = "";
        if(trim($rescis) != ""){
          if((int)$causas >= 70 && (int)$causas <= 79){
            $tipomov = "50";
          }else if($causas == 30 || $causas == 31 || $causas == 40 || $causas == 50){
            $tipomov = "80";
          }else if($causas == 60 || $causas == 62 || $causas == 64){
            $tipomov = "60";
          }else if($causas == 20 || $causas == 21){
            $tipomov = "40";
          }else if($causas == 12){
            $tipomov = "45";
          }else if($causas == 11){
            $tipomov = "31";
          }else if($causas == 10){
            $tipomov = "32";
          }
        }else{
          if($tipadm == 1){
            $tipomov = "10";
          }else if($tipadm == 2){
            $tipomov = "20";
          }else if($tipadm == 3){
            $tipomov = "35";
          }else if($tipadm == 4){
            $tipomov = "70";
          }else if($tpcontr == '04'){
            $tipomov = "25";
          }
        }
        return $tipomov;
      }
      $sql_dad = $clgera_sql_folha->gerador_sql("",
                                                $anousu,
                                                $mesusu,
                                                null,
                                                null,
                                                " distinct * ",
                                                " rh01_admiss, rh05_recis ",
                                                " 
						  rh30_regime = 2
                                               ");
      $result_dad = $clrhpessoal->sql_record($sql_dad);
      if($clrhpessoal->numrows == 0){
        $sqlerro = true;
        $erro_msg = "Nenhum registro encontrado no Ano/Mês (".$anousu."/".$mesusu."). Arquivo não poderá ser gerado.";
      }else{
        $cllayout_SEFIP->nomearq = "/tmp/CAGED.TXT";
        $totaldoprimeirodia = 0;
        for($i=0; $i<$clrhpessoal->numrows; $i++){
          db_fieldsmemory($result_dad, $i);
	  if(trim($rh01_admiss) != ""){
            if(mktime(0,0,0,db_subdata($rh01_admiss,"m"),db_subdata($rh01_admiss,"d"),db_subdata($rh01_admiss,"a")) <= mktime(0,0,0,db_subdata($primeirodia,"m"),db_subdata($primeirodia,"d"),db_subdata($primeirodia,"a"))){ 
              if(trim($rh05_recis) == ""){
                $totaldoprimeirodia ++;
              }else{
                if(mktime(0,0,0,db_subdata($rh05_recis,"m"),db_subdata($rh05_recis,"d"),db_subdata($rh05_recis,"a")) > mktime(0,0,0,db_subdata($primeirodia,"m"),db_subdata($primeirodia,"d"),db_subdata($primeirodia,"a"))){
                  $totaldoprimeirodia ++;
                }
              }
            }
	  }
        }

        $contador_C = 0;
        $contador_X = 0;

        $arr_adm = Array();
        $arr_rec = Array();
        for($i=0; $i<$clrhpessoal->numrows; $i++){
          db_fieldsmemory($result_dad, $i);
          $tmovimento = tpmov($rh05_causa,$rh01_tipadm,$rh05_recis,$rh02_tpcont);
	  if(trim($rh01_admiss) != ""){
            if((int)db_subdata($rh01_admiss,"m") >= (int)$mesant && (int)db_subdata($rh01_admiss,"a") >= (int)$anoant && (int)db_subdata($rh01_admiss,"m") <= (int)$mesusu && (int)db_subdata($rh01_admiss,"a") <= (int)$anousu){
              if((int)db_subdata($rh01_admiss,"m") == (int)$mesusu && (int)db_subdata($rh01_admiss,"a") == (int)$anousu){
                $contador_C ++;
                $CX = "C";
              }else{
                $contador_X ++;
                $CX = "X";
              }
              $arr_adm[$rh01_regist] = $rh01_admiss."_".$tmovimento."_".$CX;
            }
	  }
          if(trim($rh05_recis) != ""){
            if((int)db_subdata($rh05_recis,"m") >= (int)$mesant && (int)db_subdata($rh05_recis,"a") >= (int)$anoant && (int)db_subdata($rh05_recis,"m") <= (int)$mesusu && (int)db_subdata($rh05_recis,"a") <= (int)$anousu){
              if((int)db_subdata($rh05_recis,"m") == (int)$mesusu && (int)db_subdata($rh05_recis,"a") == (int)$anousu){
                $contador_C ++;
                $CX = "C";
              }else{
                $contador_X ++;
                $CX = "X";
              }
              $arr_rec[$rh01_regist] = $rh05_recis."_".$tmovimento."_".$CX;
            }
          }
        }
	if($contador_C > 0 || $contador_X > 0){
	  $a = 0;
          foreach($arr_adm as $registro => $valor){
	    if(strpos($valor,"C")){
	      $a ++;
              echo "<br><br>$a -- Key: $registro; Value: $valor - CCC ADMISS";
	    }
          }
          foreach($arr_rec as $registro => $valor){
	    if(strpos($valor,"C")){
	      $a ++;
              echo "<br><br>$a -- Key: $registro; Value: $valor - CCC RESCIS";
	    }
          }
          foreach($arr_adm as $registro => $valor){
	    if(strpos($valor,"X")){
	      $a ++;
              echo "<br><br>$a -- Key: $registro; Value: $valor - XXX ADMISS";
	    }
          }
          foreach($arr_rec as $registro => $valor){
	    if(strpos($valor,"X")){
	      $a ++;
              echo "<br><br>$a -- Key: $registro; Value: $valor - XXX RESCIS";
	    }
          }
	}else{
          $sqlerro = true;
          $erro_msg = "Nenhuma movimentação de celetista encontrada para o Ano / Mês (".$anousu." / ".$mesusu.")";
	}
      }
    }
/*
        $cllayout_SEFIP->SFPRegistro00_056_069 = $cgc;
        $cllayout_SEFIP->SFPRegistro00_070_099 = $nomeinst;
        $cllayout_SEFIP->SFPRegistro00_100_119 = $contato;
        $cllayout_SEFIP->SFPRegistro00_120_169 = $ender;
        $cllayout_SEFIP->SFPRegistro00_170_189 = $bairro;
        $cllayout_SEFIP->SFPRegistro00_190_197 = $cep;
        $cllayout_SEFIP->SFPRegistro00_198_217 = $munic;
        $cllayout_SEFIP->SFPRegistro00_218_219 = $uf;
        $cllayout_SEFIP->SFPRegistro00_220_231 = $fone;
        $cllayout_SEFIP->SFPRegistro00_232_291 = $email;
        $cllayout_SEFIP->SFPRegistro00_292_297 = $anousu.$mesusu;
        $cllayout_SEFIP->SFPRegistro00_298_300 = $codrec;
        $cllayout_SEFIP->SFPRegistro00_301_301 = $indrecfgts;
        $cllayout_SEFIP->SFPRegistro00_303_310 = db_formatar($dtrecfgts_dia,"s","0",2,"e",0).db_formatar($dtrecfgts_mes,"s","0",2,"e",0).db_formatar($dtrecfgts_ano,"s","0",4,"e",0);
        $cllayout_SEFIP->SFPRegistro00_311_311 = $indrecinss;
        $cllayout_SEFIP->SFPRegistro00_312_319 = db_formatar($dtrecinss_dia,"s","0",2,"e",0).db_formatar($dtrecinss_mes,"s","0",2,"e",0).db_formatar($dtrecinss_ano,"s","0",4,"e",0);
        $cllayout_SEFIP->SFPRegistro00_320_326 = $indatrasoinss;
        $cllayout_SEFIP->SFPRegistro00_328_341 = $cgc;
        $cllayout_SEFIP->geraRegist00SFP();

        $cllayout_SEFIP->SFPRegistro10_004_017 = $cgc;
        $cllayout_SEFIP->SFPRegistro10_054_093 = $nomeinst;
        $cllayout_SEFIP->SFPRegistro10_094_143 = $ender;
        $cllayout_SEFIP->SFPRegistro10_144_163 = $bairro;
        $cllayout_SEFIP->SFPRegistro10_164_171 = $cep;
        $cllayout_SEFIP->SFPRegistro10_172_191 = $munic;
        $cllayout_SEFIP->SFPRegistro10_192_193 = $uf;
        $cllayout_SEFIP->SFPRegistro10_194_205 = $fone;
        $cllayout_SEFIP->SFPRegistro10_206_206 = $alteraender;
        $cllayout_SEFIP->SFPRegistro10_207_213 = $cnae;
        $cllayout_SEFIP->SFPRegistro10_214_214 = $alteracnae;
        $cllayout_SEFIP->SFPRegistro10_215_216 = $aliqsat;
        $cllayout_SEFIP->SFPRegistro10_219_221 = $r11_cdfpas;
        $cllayout_SEFIP->SFPRegistro10_222_225 = $codterceiro;
        $cllayout_SEFIP->SFPRegistro10_226_229 = $codgps;
        $cllayout_SEFIP->SFPRegistro10_235_249 = $tot_sal_familia; // Total geral do salário família
        $cllayout_SEFIP->geraRegist10SFP();

   
        for($i=0; $i<$clrhpessoal->numrows; $i++){
          db_fieldsmemory($result_dad, $i);
          if((int)$rh16_pis > 0){
            $cllayout_SEFIP->SFPRegistro14_004_017 = $cgc;
            $cllayout_SEFIP->SFPRegistro14_054_064 = $rh16_pis;
            $cllayout_SEFIP->SFPRegistro14_065_072 = db_formatar($rh01_admiss,"d");
            $cllayout_SEFIP->SFPRegistro14_073_074 = $h13_tpcont;
            $cllayout_SEFIP->SFPRegistro14_075_144 = $z01_nome;
            $cllayout_SEFIP->SFPRegistro14_145_151 = $rh16_ctps_n;
            $cllayout_SEFIP->SFPRegistro14_152_156 = $rh16_ctps_s;
            $cllayout_SEFIP->SFPRegistro14_157_206 = $z01_ender;
            $cllayout_SEFIP->SFPRegistro14_207_226 = $z01_bairro;
            $cllayout_SEFIP->SFPRegistro14_227_234 = $z01_cep;
            $cllayout_SEFIP->SFPRegistro14_235_254 = $z01_munic;
            $cllayout_SEFIP->SFPRegistro14_255_256 = $z01_uf;
            $cllayout_SEFIP->geraRegist14SFP();
          }else{
          }
        }

        for($i=0; $i<$clrhpessoal->numrows; $i++){
          db_fieldsmemory($result_dad, $i);

            $desconto_seguro = 0;
	    if($ocorrencia >= 5 || ($situacao_funcionario == 5 && $dias_pagamento != 0)){
	      $desconto_seguro = $arr_descinss[$rh01_regist];
	    }

            $cllayout_SEFIP->SFPRegistro30_004_017 = $cgc;
            $cllayout_SEFIP->SFPRegistro30_033_043 = $rh16_pis;
            $cllayout_SEFIP->SFPRegistro30_044_051 = db_formatar($rh01_admiss,"d");
            $cllayout_SEFIP->SFPRegistro30_052_053 = $h13_tpcont;
            $cllayout_SEFIP->SFPRegistro30_054_123 = $z01_nome;
            $cllayout_SEFIP->SFPRegistro30_124_134 = $rh01_regist;
            $cllayout_SEFIP->SFPRegistro30_135_141 = $rh16_ctps_n;
            $cllayout_SEFIP->SFPRegistro30_142_146 = $rh16_ctps_s;
            $cllayout_SEFIP->SFPRegistro30_147_154 = db_formatar($rh15_data,"d");
            $cllayout_SEFIP->SFPRegistro30_155_162 = db_formatar($rh01_nasc,"d");
            $cllayout_SEFIP->SFPRegistro30_163_167 = $rh37_cbo;
            $cllayout_SEFIP->SFPRegistro30_168_182 = $remuneracaosem13;
            $cllayout_SEFIP->SFPRegistro30_183_197 = $remuneracao13;
            $cllayout_SEFIP->SFPRegistro30_200_201 = $ocorrencia;
            $cllayout_SEFIP->SFPRegistro30_202_216 = $desconto_seguro;
            $cllayout_SEFIP->SFPRegistro30_217_231 = (($situacao_funcionario == 3 || $situacao_funcionario == 4)?$arr_base_inss[$rh01_regist]:0);
            $cllayout_SEFIP->SFPRegistro30_232_246 = (trim($rh05_recis) != ""?0:0.01);
            $cllayout_SEFIP->geraRegist30SFP();

            $codmov = "";
            if(trim($rh05_recis) != ""){
              $nr_dias_prop = (int)db_subdata($rh05_recis,"d");
              if((db_subdata($rh01_admiss,"m") == db_subdata($rh05_recis,"m")) && (db_subdata($rh01_admiss,"a") == db_subdata($rh05_recis,"a"))){
                $nr_dias_prop -= (int)db_subdata($rh01_admiss,"d");
              }
              $result_dadosrescisao = $clrescisao->sql_record($clrescisao->sql_query_file($anousu,$mesusu,$rh30_regime,$rh05_causa,$rh05_caub,null,"r59_movsef"));
              if($clrescisao->numrows > 0){
                db_fieldsmemory($result_dadosrescisao, 0);
                $codmov = $r59_movsef; 
              }
            }
	    $result_afasta = $clafasta->sql_record($clafasta->sql_query_file(null,"*","r45_regist, r45_dtafas desc","r45_anousu = ".$anousu." and r45_mesusu = ".$mesusu." and r45_regist = ".$rh01_regist));
            $numrows_afasta = $clafasta->numrows;
            for($in=0; $in<$numrows_afasta; $in++){
              db_fieldsmemory($result_afasta, $in);
              if((db_subdata($r45_dtafas,"m") == $mesusu && db_subdata($r45_dtafas,"a") == $anousu) || ((db_subdata($r45_dtafas,"a") < $anousu && trim($r45_dtreto) == "" ) || (db_subdata($r45_dtreto,"m") >= $mesusu && db_subdata($r45_dtreto,"a") >= $anousu)) || ((db_subdata($r45_dtafas,"m") <= $mesusu && db_subdata($r45_dtafas,"a") <= $anousu) && (trim($r45_dtreto) == "" || (db_subdata($r45_dtreto,"m") >= $mesusu and db_subdata($r45_dtreto,"a") >= $anousu)))){
                $nr_dias_prop = 0;
                $situacao = $r45_situac;
                $dataafasta = ($situacao == 3 || $situacao == 6)?date("Y-m-d",mktime(0,0,0,db_subdata($r45_dtafas,"m"), db_subdata($r45_dtafas,"d") - 15, db_subdata($r45_dtafas,"a"))):$r45_dtafas;
                $dataretorno = $r45_dtreto;

                $nr_dias_prop = db_dias_mes($anousu,$mesusu);

                $dataini = $dataafasta;
                $datafim = $dataretorno;
                if($situacao == 3 || $situacao == 6){
                  $dataini = $r45_dtafas;
                }

                if(db_subdata($dataini,"a").db_subdata($dataini,"m") == db_anofolha().db_mesfolha() && db_subdata($datafim,"a").db_subdata($datafim,"m") == db_anofolha().db_mesfolha()){
                  $nr_dias_prop = (int)db_subdata($datafim,"d") - (int)db_subdata($dataini,"d");
                }else if(db_subdata($dataini,"a").db_subdata($dataini,"m") != db_anofolha().db_mesfolha() && db_subdata($datafim,"a").db_subdata($datafim,"m") == db_anofolha().db_mesfolha()){
		  $nr_dias_prop = (int)db_subdata($datafim,"d");
                }else if(db_subdata($dataini,"a").db_subdata($dataini,"m") == db_anofolha().db_mesfolha() && db_subdata($datafim,"a").db_subdata($datafim,"m") != db_anofolha().db_mesfolha()){
		  $nr_dias_prop = (int)db_subdata($dataini,"d");
		}


                if(db_subdata($dataafasta,"m") < $mesusu && db_subdata($dataafasta,"a") == $anousu){
		  $datamov = $dataafasta;
		  $codmov  = $r45_codafa;
		}

                if((db_subdata($dataafasta,"m") < $mesusu && db_subdata($dataafasta,"a") == $anousu) || db_subdata($dataafasta,"a") < $anousu){
                  $result_codmovsefip = $clcodmovsefip->sql_record($clcodmovsefip->sql_query_file(null,null,null,"r66_codigo,r66_mensal","","r66_anousu = ".$anousu." and r66_mesusu = ".$mesusu." and trim(r66_codigo) = '".$r45_codafa."' and r66_mensal = 't'"));
                  if($clcodmovsefip->numrows > 0){
                    db_fieldsmemory($result_codmovsefip, 0);
                    $datamov = $dataafasta;
                    $codmov  = $r45_codafa;
                  }else if(db_subdata($dataretorno,"m") < $mesusu && db_subdata($dataretorno,"a") < $anousu && trim($r45_codret) != ""){
                    $datamov = $dataafasta;
                    $codmov  = $r45_codafa;
                  }else{
                    $mesant = $mesusu - 1;
                    $anoant = $anousu;
                    if($mesant == 0){
                      $mesant = 12;
                      $anoant-= 1;
                    }
                    if(db_subdata($dataafasta,"m") == $mesant && db_subdata($dataafasta,"a") == $anoant){
                      $result_afasta_ant = $clafasta->sql_record($clafasta->sql_query_file(null,"*","","r45_anousu = ".$anoant." and r45_mesusu = ".$mesant." and r45_regist = ".$rh01_regist." and r45_dtafas = '".$r45_dtafas."' and r45_situac = ".$r45_situac));
                      if($clafasta->numrows > 0){
                        $datamov = $dataafasta;
                        $codmov  = $r45_codafa;
                      }
                    }
                  }
                }
                if(db_subdata($dataretorno,"m") == $mesusu && db_subdata($dataretorno,"a") == $anousu){
                  $datamov = $dataretorno;
                  $codmov  = $r45_codret;
                }

                $result_ifgts = $clcodmovsefip->sql_record($clcodmovsefip->sql_query_file(null,null,null,"r66_ifgtsc,r66_ifgtse","","r66_anousu = ".$anousu." and r66_mesusu = ".$mesusu." and trim(r66_codigo) = '".$r45_codafa."'"));
                if($clcodmovsefip->numrows > 0){
                  db_fieldsmemory($result_ifgts,0);
                }
                if($rh30_regime == 2){
                  $indfgts = $r66_ifgtsc;
                }else{
                  $indfgts = $r66_ifgtse;
                }
 
                $cllayout_SEFIP->SFPRegistro32_004_017 = $cgc;
                $cllayout_SEFIP->SFPRegistro32_033_043 = $rh16_pis;
                $cllayout_SEFIP->SFPRegistro32_044_051 = db_formatar($rh01_admiss,"d");
                $cllayout_SEFIP->SFPRegistro32_052_053 = $h13_tpcont;
                $cllayout_SEFIP->SFPRegistro32_054_123 = $z01_nome;
                $cllayout_SEFIP->SFPRegistro32_124_125 = $codmov;
                $cllayout_SEFIP->SFPRegistro32_126_133 = db_formatar($datamov,"d");
                $cllayout_SEFIP->SFPRegistro32_134_134 = $indfgts;
                $cllayout_SEFIP->geraRegist32SFP();
 
              }
            }
          }else{
          }
        }

        $cllayout_SEFIP->geraRegist90SFP();
        $cllayout_SEFIP->gera();
      }
      db_fim_transacao();
    }
  }
*/
  }
exit;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmcaged.php");
	?>
    </center>
    </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($gerar)){
  if($sqlerro == true){
    db_msgbox($erro_msg);
  }
}
?>
<script>
js_tabulacaoforms("form1","anousu",true,1,"anousu",true);
</script>