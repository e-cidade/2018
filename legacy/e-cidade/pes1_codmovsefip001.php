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

//require("libs/db_stdlib.php");
include("fpdf151/pdf.php");
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
include("classes/db_rhpesdoc_classe.php");
include("classes/db_rhpesrescisao_classe.php");
include("classes/db_rescisao_classe.php");
include("classes/db_afasta_classe.php");
include("classes/db_inssirf_classe.php");
include("classes/db_rhlota_classe.php");
include("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
$clcodmovsefip   = new cl_codmovsefip;
$cldb_config     = new cl_db_config;
$clcfpess        = new cl_cfpess;
$clrhpessoal     = new cl_rhpessoal;
$clrhpessoalmov  = new cl_rhpessoalmov;
$clrhpesdoc      = new cl_rhpesdoc;
$clrhpesrescisao = new cl_rhpesrescisao;
$clrescisao      = new cl_rescisao;
$clafasta        = new cl_afasta;
$clinssirf       = new cl_inssirf;
$cllayout_SEFIP  = new cl_layout_SEFIP;
$clrhlota        = new cl_rhlota;
$db_opcao        = 1;
$db_botao        = true;
$sFgts           = "1";
$instit          = db_getsession("DB_instit");
$aListaGerados   = array();
$aListaSemPIS    = array();

if(isset($gerar)){
  $sqlerro = false;
  $anousu = db_formatar($anousu,"s","0",4,"e",0);
  $mesusu = db_formatar($mesusu,"s","0",2,"e",0);
  
  if ($r70_numcgm==0){
    $result_db_config = $cldb_config->sql_record($cldb_config->sql_query_file($instit));
    $whererhlota = "";
  }else{
    $campos="z01_cgccpf  as cgc, z01_nome as nomeinst,z01_ender as ender, z01_bairro as bairro, z01_cep as cep, z01_munic as munic,z01_uf as uf, z01_email as email";
    $result_db_config = $clrhlota->sql_record($clrhlota->sql_query_lota_cgm(null,"$campos",null,"r70_numcgm=$r70_numcgm and r70_instit=$instit"));
    $whererhlota = " and rh02_lota in (select r70_codigo from rhlota where r70_instit = $instit and r70_numcgm = $r70_numcgm) ";
  }

  $r11_mes13 = 12;
  $result_cfpess_prev = $clcfpess->sql_record($clcfpess->sql_query_file(db_anofolha(),db_mesfolha(),db_getsession("DB_instit"), "r11_mes13" ));
//  echo ($clcfpess->sql_query_file(db_anofolha(),db_mesfolha(),db_getsession("DB_instit"), "r11_mes13" ));
  db_fieldsmemory($result_cfpess_prev,0);
  if ($r70_numcgm==0){
    if($cldb_config->numrows == 0){
      $sqlerro = true;
      $erro_msg = "ERRO: Instituição não encontrada. Arquivo não poderá gerado.";
    }
  }else{
      if($clrhlota->numrows == 0){
            $sqlerro = true;
            $erro_msg = "ERRO: CGM não encontrado. Arquivo não poderá gerado.";
      }
  }

  if ($sqlerro==false){
    $mes13 = false;
    if($mesusu == 13){
      $mesusu = $r11_mes13 ;
      $mes13 = true ;
    }

    $result_cfpess = $clcfpess->sql_record($clcfpess->sql_query_file($anousu,$mesusu,db_getsession("DB_instit")));
    if($clcfpess->numrows == 0){
      $sqlerro = true;
      $erro_msg = "ERRO: Configuração da folha não encontrada para o Ano/Mês (".$anousu."/".$mesusu."). Arquivo não poderá ser gerado.";
    }else{

      db_fieldsmemory($result_db_config, 0);
      db_fieldsmemory($result_cfpess,0);
      $mesant = (int)$mesusu - 1;
      $anoant = (int)$anousu;
      if($mesant == 0){
        $mesant = 12;
        $anoant-= 1;
      }
      $anoant = db_formatar($anoant,"s","0",4,"e",0);
      $mesant = db_formatar($mesant,"s","0",2,"e",0);

      $res_prev = $clinssirf->sql_record($clinssirf->sql_query_file(null,null,"r33_rubmat","r33_nome limit 1","r33_anousu = $anousu and r33_mesusu = $mesusu and r33_codtab = $r11_tbprev + 2 and r33_instit = ".db_getsession('DB_instit')));
      db_fieldsmemory($res_prev,0);   

      include("libs/db_sql.php");
      $clgera_sql_folha = new cl_gera_sql_folha;
      $clgera_sql_folha->usar_res  = true;
      $clgera_sql_folha->usar_doc  = true;
      $clgera_sql_folha->usar_cgm  = true;
      $clgera_sql_folha->usar_fgt  = true;
      $clgera_sql_folha->usar_fun  = true;
      $clgera_sql_folha->usar_ins  = true;
      $clgera_sql_folha->usar_tpc  = true;
      $clgera_sql_folha->usar_atv  = true;
      $clgera_sql_folha->inner_ins = false;
      $clgera_sql_folha->inner_doc = false;
      $clgera_sql_folha->inner_fgt = false;
      $clgera_sql_folha->inner_atv = false;

      $sql_not = $clrhpessoalmov->sql_query_rescisao(null,"rh02_regist","","rh02_anousu = ".$anoant." and rh02_mesusu = ".$mesant." and rh02_regist = rh01_regist and rh02_instit = ".db_getsession("DB_instit")." and rh05_recis is not null");
      $sql_dad = $clgera_sql_folha->gerador_sql("",
                                                $anousu,
                                                $mesusu,
                                                null,
                                                null,
                                                " * ",
                                                " rh16_pis,rh01_admiss ",
                                                "
                                                  rh02_tbprev in (".$checkboxes.")
                                                  and (rh05_recis is null or (rh05_recis is not null and (
                                                   (cast(extract(year from rh05_recis) as integer) = ".$anousu." and cast(extract(month from rh05_recis) as integer) = ".$mesusu.")
                                                    or
                                                   (cast(extract(year from rh05_recis) as integer) = ".$anoant." and cast(extract(month from rh05_recis) as integer) = ".$mesant."
                                                    and rh01_regist not in (".$sql_not."))
                                                  )))
                                                  $whererhlota
                                               ",db_getsession("DB_instit"));
       //echo $sql_dad;exit;
      $result_dad = $clrhpessoal->sql_record($sql_dad);
      if($clrhpessoal->numrows == 0){
        $sqlerro = true;
        $erro_msg = "Nenhum registro encontrado no Ano/Mês (".$anousu."/".$mesusu."). Arquivo não poderá ser gerado.";
      }else{
        $cllayout_SEFIP->nomearq = "/tmp/SEFIP.RE";
   
        $clgera_sql_folha->inicio_rh = false;
        $clgera_sql_folha->usar_pes = false;
        $clgera_sql_folha->usar_res = false;
        $clgera_sql_folha->usar_doc = false;
        $clgera_sql_folha->usar_cgm = false;
        $clgera_sql_folha->usar_fgt = false;
        $clgera_sql_folha->usar_fun = false;
        $clgera_sql_folha->usar_ins = false;
        $clgera_sql_folha->usar_tpc = false;
        $clgera_sql_folha->usar_atv = false;
        $tot_sal_familia = 0;
        $tot_sal_mater = 0;

        // $arr_siglas = array("r14","r48","r31","r20","r35");
	      if(!$mes13){
	        //if($mesusu != $r11_mes13){ 
             $arr_siglas = array("r14","r48","r35","r20");
	        //}else{
          //   $arr_siglas = array("r14","r48","r20");
	        //}
        }else{
          $arr_siglas = array("r35");
        }
        $inss_sal = "R9".db_formatar((($r11_tbprev * 3) - 2),"s","0",2,"e",0);
        $inss_s13 = "R9".db_formatar((($r11_tbprev * 3) - 1),"s","0",2,"e",0);
        $inss_fer = "R9".db_formatar((($r11_tbprev * 3)),"s","0",2,"e",0);
        if(trim($r11_rubdec) != ""){
          $r11_rubdec = ",'".$r11_rubdec."'";
        }

        $arr_sal_matern    = Array();
        $arr_sal_familia   = Array();
        $arr_base_fgts     = Array();
        $arr_base_fgts13   = Array();
        $arr_fgts13        = Array();
        $arr_fgts          = Array();
        $arr_base_inss     = Array();
        $arr_base_inss13   = Array();
        $arr_base_descinss = Array();
        $arr_descinss      = Array();
        $arr_descinss13    = Array();
        $arr_base_inssR990 = Array();

        for($i=0; $i<$clrhpessoal->numrows; $i++){
          db_fieldsmemory($result_dad, $i);

          $sal_matern   = 0;
          $sal_familia   = 0;
          $base_fgts     = 0;
          $base_fgts13   = 0;
          $fgts13        = 0;
          $fgts          = 0;
          $base_inss     = 0;
          $base_inss13   = 0;
          $base_descinss = 0;
          $descinss      = 0;
          $descinss13    = 0;
          $base_inssR990 = 0;
          $nDescFolha    = 0;

          for($in=0; $in<count($arr_siglas); $in++){
            $sql_ger = $clgera_sql_folha->gerador_sql($arr_siglas[$in],
                                                      $anousu,
                                                      $mesusu,
                                                      $rh01_regist,
                                                      null,
                                                      " #s#_valor as valor,#s#_rubric as rubri ",
                                                      "",
                                                      " #s#_rubric in ('R993','R919','R921','R985','R986','R987','R990','R991','R996','".$inss_sal."','".$inss_fer."','".$inss_s13."','".$r33_rubmat."'".$r11_rubdec.") ",
                                                      db_getsession("DB_instit")
                                                     );
                                                     
            $result_ger = db_query($sql_ger);
            $numrows_ger = pg_numrows($result_ger);
            for($im=0; $im<$numrows_ger; $im++){
              db_fieldsmemory($result_ger, $im);
              if($rubri == "R919" || $rubri == "R921"){
                $tot_sal_familia += $valor;
                $sal_familia     += $valor;
              }
              if($rubri == "R991"){
                $base_fgts += $valor;
                if($arr_siglas[$in] == "r35"){
                  $base_fgts13 += $valor;
                  $base_fgts -= $valor;
                }else if($arr_siglas[$in] == "r10"){
                  $fgts13 += $valor;
                }
              }
              if($rubri == "R996" && $arr_siglas[$in] != "r35"){
                $base_fgts += $valor;
              }
              if($rubri == $r11_rubdec && $arr_siglas[$in] != "r20" && $arr_siglas[$in] != "r35"){
                $base_fgts -= $valor;
                $base_fgts13 += $valor;
              }
              if(($rubri == "R985" || $rubri == "R987") && $arr_siglas[$in] != "r35"){
                $base_inss += $valor;
                $base_descinss +=$valor;
              }
              if($rubri == "R986"  ){
                if($arr_siglas[$in] == "r35" ){
                  if($mes13){
                    $base_inss13 += $valor;
                  }else{
                    $base_inss13 += 0;
                  }
                }else{
                    $base_inss13 += $valor;
                }
              }
              
              if(($rubri == $inss_sal || $rubri == $inss_fer) && $arr_siglas[$in] != "r35"){
                $descinss += $valor;
              }
              
              if ( $mesusu != 13 && $rubri == $inss_s13 ) {
                $descinss += $valor;
              } else if( $mesusu == 13 && $rubri == $inss_s13) {
                $descinss13 += $valor;
              }
              
              if($rubri == $r33_rubmat){
                $sal_matern += $valor;
                $tot_sal_mater += $valor;
              }
              if($rubri == "R990"){
                if($arr_siglas[$in] == "r10"){
                  $baseinssr990 += $valor;
                }
              }
              if($rubri == "R993"){
                if($arr_siglas[$in] == "r14" || $arr_siglas[$in] == "r20" || $arr_siglas[$in] == "r35" || $arr_siglas[$in] == "r48"){
                  $nDescFolha += $valor;
                }
              }              
            }
          }
          
          $arr_sal_matern[$rh01_regist]    = $sal_matern;
          $arr_sal_familia[$rh01_regist]   = $sal_familia;
          $arr_base_fgts[$rh01_regist]     = $base_fgts;
          $arr_base_fgts13[$rh01_regist]   = $base_fgts13;
          $arr_fgts13[$rh01_regist]        = $fgts13;
          $arr_fgts[$rh01_regist]          = $fgts;
          $arr_base_inss[$rh01_regist]     = $base_inss;
          $arr_base_inss13[$rh01_regist]   = $base_inss13;
          $arr_base_descinss[$rh01_regist] = $base_descinss;
          $arr_descinss[$rh01_regist]      = $descinss;
          $arr_descinss13[$rh01_regist]    = $descinss13;
          $arr_base_inssR990[$rh01_regist] = $base_inssR990;
          
          $aDescFolha[$rh01_regist]        = $nDescFolha;
          
          /*
           * Altera o inicio da linha do header para 00 quando há recolhimento de FGTS do contrário inicia com 01 
           */
          if ($base_fgts != 0 || $base_fgts13 != 0 || $fgts13 != 0 || $fgts != 0) {
          	$sFgts = " ";
          }
          
        }

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
	if($mes13){
           $cllayout_SEFIP->SFPRegistro00_292_297 = $anousu."13";
	}else{
           $cllayout_SEFIP->SFPRegistro00_292_297 = $anousu.$mesusu;
	}
        $cllayout_SEFIP->SFPRegistro00_298_300 = $codrec;
  
	if($mes13){
           $cllayout_SEFIP->SFPRegistro00_301_301 = ' ';
           $cllayout_SEFIP->SFPRegistro00_302_302 = "1";
	}else{
           $cllayout_SEFIP->SFPRegistro00_301_301 = $indrecfgts;
           $cllayout_SEFIP->SFPRegistro00_302_302 = $sFgts;
	}
        $cllayout_SEFIP->SFPRegistro00_303_310 = db_formatar($dtrecfgts_dia,"s",(trim($dtrecfgts_dia)==""?" ":"0"),2,"e",0)."-".db_formatar($dtrecfgts_mes,"s",(trim($dtrecfgts_mes)==""?" ":"0"),2,"e",0)."-".db_formatar($dtrecfgts_ano,"s",(trim($dtrecfgts_ano)==""?" ":"0"),4,"e",0);
        $cllayout_SEFIP->SFPRegistro00_311_311 = $indrecinss;
        $cllayout_SEFIP->SFPRegistro00_312_319 = db_formatar($dtrecinss_dia,"s",(trim($dtrecinss_dia)==""?" ":"0"),2,"e",0)."-".db_formatar($dtrecinss_mes,"s",(trim($dtrecinss_mes)==""?" ":"0"),2,"e",0)."-".db_formatar($dtrecinss_ano,"s",(trim($dtrecinss_ano)==""?" ":"0"),4,"e",0);
        $cllayout_SEFIP->SFPRegistro00_320_326 = $indatrasoinss;
        $cllayout_SEFIP->SFPRegistro00_328_341 = $cgc;
//        $teste++;
        $cllayout_SEFIP->geraRegist00SFP();

        if($anousu < 2009){
          $alteracnae = 'N';
        }

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
        $cllayout_SEFIP->SFPRegistro10_215_216 = str_pad(trim($aliqsat),2,"0",STR_PAD_RIGHT);
        $cllayout_SEFIP->SFPRegistro10_219_221 = $r11_cdfpas;
        $cllayout_SEFIP->SFPRegistro10_222_225 = $codterceiro;
        $cllayout_SEFIP->SFPRegistro10_226_229 = $codgps;
        $cllayout_SEFIP->SFPRegistro10_235_249 = $tot_sal_familia; // Total geral do salario familia
        $cllayout_SEFIP->SFPRegistro10_250_264 = $tot_sal_mater; // Total geral do salario maternidade
//        $teste++;
        $cllayout_SEFIP->geraRegist10SFP();

        for($i=0; $i<$clrhpessoal->numrows; $i++){
          db_fieldsmemory($result_dad, $i);
          if((int)$rh16_pis > 0 && $alteraender == 'S'){
            if($arr_sal_familia[$rh01_regist] > 0 || $arr_base_fgts[$rh01_regist]     > 0 || $arr_base_fgts13[$rh01_regist] > 0 ||
              $arr_fgts13[$rh01_regist]       > 0 || $arr_fgts[$rh01_regist]          > 0 || $arr_base_inss[$rh01_regist]   > 0 ||
              $arr_base_inss13[$rh01_regist]  > 0 || $arr_base_descinss[$rh01_regist] > 0 || $arr_descinss[$rh01_regist]    > 0 ||
              $arr_descinss13[$rh01_regist]   > 0 || $arr_base_inssR990[$rh01_regist] > 0){
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
              
              $aListaGerados[$rh01_regist]['Nome']            = $z01_nome;
              $aListaGerados[$rh01_regist]['TipoContrato']    = $h13_tpcont;
              $aListaGerados[$rh01_regist]['DescPrevidencia'] = $arr_descinss[$rh01_regist];
		          $aListaGerados[$rh01_regist]['Desc13']          = $arr_descinss13[$rh01_regist];
            	$aListaGerados[$rh01_regist]['DescFolha']       = $aDescFolha[$rh01_regist];
		          

              
//              $aListaGerados[$rh01_regist]['Nome']            = $z01_nome;
//              $aListaGerados[$rh01_regist]['TipoContrato']    = $h13_tpcont;
//              $aListaGerados[$rh01_regist]['CodAfastamento']  = $r45_codafa;
//              $aListaGerados[$rh01_regist]['DataAfastamento'] = $dataafasta;
//              $aListaGerados[$rh01_regist]['CodRetorno']      = $r45_codret;
//              $aListaGerados[$rh01_regist]['DataRetorno']     = $dataretorno;
//              $aListaGerados[$rh01_regist]['BasePrevidencia'] = $remuneracaosem13;
//              $aListaGerados[$rh01_regist]['DescPrevidencia'] = $descinss;
//              $aListaGerados[$rh01_regist]['Base13']          = $base_inss13;
//              $aListaGerados[$rh01_regist]['Desc13']          = $descinss13;
//              $aListaGerados[$rh01_regist]['FGTS']            = $base_fgts;              
              
              $cllayout_SEFIP->geraRegist14SFP();
            }
          }else{
          }
        }
        for($i=0; $i<$clrhpessoal->numrows; $i++){
          db_fieldsmemory($result_dad, $i);
          if((int)$rh16_pis > 0 ){
            $remuneracao13 = 0;
            if($arr_sal_familia[$rh01_regist] > 0 || $arr_base_fgts[$rh01_regist]     > 0 || $arr_base_fgts13[$rh01_regist] > 0 ||
              $arr_fgts13[$rh01_regist]      > 0 || $arr_fgts[$rh01_regist]          > 0 || $arr_base_inss[$rh01_regist]   > 0 ||
              $arr_base_inss13[$rh01_regist] > 0 || $arr_base_descinss[$rh01_regist] > 0 || $arr_descinss[$rh01_regist]    > 0 ||
              $arr_descinss13[$rh01_regist] > 0  || $arr_base_inssR990[$rh01_regist] > 0){
              if($h13_tpcont >= 12){
                 $remuneracaosem13 = $arr_base_inss[$rh01_regist];
                 $remuneracao13 = $arr_base_inss13[$rh01_regist];
              }else{
                 $remuneracaosem13 = $arr_base_fgts[$rh01_regist];
                 $remuneracao13 = $arr_base_fgts13[$rh01_regist];
              }
              if(( $mes13 && $remuneracao13 == 0) || ($mes13 && $h13_tpcont == 13) ){
                continue;
	            }      
					       
              if(trim($rh05_recis) != "" && $codrec == "115"){
                if($h13_tpcont < 12){
                  if($remuneracaosem13 > 0){
                     $remuneracaosem13 -= $arr_base_inss13[$rh01_regist];
                  }else{
                     $remuneracaosem13 = $arr_base_inss13[$rh01_regist];
                  }   
                  if($remuneracaosem13 < 0){                                         
                    $remuneracaosem13 = 0;                                           
                  }                                                                  
                }else{
                  //Sandro: tirei pq não no mês da rescisão soma a base do salario com a do 13 e coloca na base de salario e 
                  //        ainda ficava com a base do 13.
                  //$remuneracaosem13 +=  $remuneracao13;
                }
              }else{
                if($mes13){
                  $remuneracaosem13 = 0;
                }
              }
              if((trim($rh05_recis) == "" && $mes13) || (trim($rh05_recis) != "" && !$mes13)){
              	$remuneracao13 = $arr_base_inss13[$rh01_regist];
                if($mes13){
                  $remuneracao13 = 0;
                }
              }

	            $valorrescis = 0;

              $recis_dia = '';
              $recis_mes = '';
              $recis_ano = '';

              if(trim($rh05_recis) != ""){
                $recis_dia = (int) db_subdata($rh05_recis,"d");
                $recis_mes = (int) db_subdata($rh05_recis,"m");
                $recis_ano = (int) db_subdata($rh05_recis,"a");
              }
              if(trim($rh05_recis) != "" || $mes13){
                if($recis_ano == (int)$anousu && $recis_mes == (int)$mesusu){
          	      $valorrescis = $arr_base_inss13[$rh01_regist];
                  if($arr_base_inss13[$rh01_regist] == 0 ){
          	        $valorrescis = 0.01;
                  }
                }
                if($mes13){
          	      $valorrescis = $arr_base_inss13[$rh01_regist];
                  $remuneracao13    = 0;
                  $remuneracaosem13 = 0;
                }
	            }

//              if((trim($rh05_recis) == "" || $recis_ano < (int)$anousu || ($recis_ano == (int)$anousu && $recis_mes < (int)$mesusu)) ){
//          	  $valorrescis = $arr_base_inss13[$rh01_regist];
//              }else{
//	        $valorrescis = 0.01;
//              }
		
              $ocorrencia = trim($rh02_ocorre);
              if(trim($rh02_ocorre) == ""){
                $ocorrencia = "  ";
              }
              if((int)($anousu.$mesusu) > 200306 && ($rh51_basefo > 0 || $rh51_descfo > 0 || $rh51_b13fo > 0 || $rh51_d13fo > 0)){
                if(trim($rh51_ocorre) != ""){
                  $ocorrencia = $rh51_ocorre;
                }
              }
            
              $mpis = false;
              $cont_pis = 0;
              for($in=0; $in<count($arr_siglas); $in++){
                $result_pesdoc = $clrhpesdoc->sql_record($clrhpesdoc->sql_query_gerfs(null,"distinct rh16_regist",""," #s#_anousu = ".$anousu." and #s#_mesusu = ".$mesusu." and #s#_instit = ".db_getsession("DB_instit")." and rh02_tbprev in (".$checkboxes.") ",$arr_siglas[$in],$rh16_pis));
                if(($cont_pis == 0 || $arr_siglas[$in] == "r20") && $clrhpesdoc->numrows > 0){
                  $cont_pis ++;
                }
                if($clrhpesdoc->numrows > 1 || $cont_pis > 1){
                  $mpis = true;
                  break;
                }
              }

              if($mpis == true){
                if(trim($ocorrencia) == "" || (int)$ocorrencia == 1){
                  $ocorrencia = "05";
                }else if((int)$ocorrencia == 2){
                  $ocorrencia = "06";
                }else if((int)$ocorrencia == 3){
                  $ocorrencia = "07";
                }else if((int)$ocorrencia == 4){
                  $ocorrencia = "08";
                }
              }
             
              $subpes = $anousu."/".$mesusu;
              $situacao_funcionario = situacao_funcionario($rh01_regist);
             
              $desconto_seguro = 0;
              if((int)$ocorrencia >= 5){
                $desconto_seguro = $arr_descinss[$rh01_regist];
              }
              
              $xctps_d = str_repeat(" ",8);
              $xctps_n = str_repeat(" ",7);
              $xctps_s = str_repeat(" ",5);

              $stringcategoriactps = "01-02-03-04-06-07-26";
              $posicaocategoria = strpos($stringcategoriactps,$h13_tpcont);
              if($posicaocategoria !== false){
                $xctps_n = db_formatar($rh16_ctps_n,"s","0",7,"e",0);
                $xctps_s = db_formatar($rh16_ctps_s,"s","0",5,"e",0);
                if(trim($rh15_data) != ""){
                  $xctps_d = db_formatar($rh15_data,"d");
                }
              }

              $data_admiss = str_repeat(" ",8);
              $stringcategoriaadmiss = "01-03-04-05-06-07-11-12-19-20-21-26";
              $posicaocategoria = strpos($stringcategoriaadmiss,$h13_tpcont);
              if($posicaocategoria !== false){
                $data_admiss = db_formatar($rh01_admiss,"d");
              }

              $stringcategoriaregist = "06-13-14-15-16-17-18-22-23-24-25";
              $posicaocategoria = strpos($stringcategoriaregist,$h13_tpcont);
              if($posicaocategoria !== false){
                $iRegist = str_repeat(" ",11);
              } else {
              	$iRegist = $rh01_regist; 
              }

              $data_nasc = str_repeat(" ",8);
              $stringcategorianasc = "01-02-03-04-05-06-07-12-19-20-21-26";
              $posicaocategoria = strpos($stringcategorianasc,$h13_tpcont);
              if($posicaocategoria !== false){
                $data_nasc = db_formatar($rh01_nasc,"d");
              }

              $cllayout_SEFIP->SFPRegistro30_004_017 = $cgc;
              $cllayout_SEFIP->SFPRegistro30_033_043 = $rh16_pis;
              $cllayout_SEFIP->SFPRegistro30_044_051 = $data_admiss;
              $cllayout_SEFIP->SFPRegistro30_052_053 = $h13_tpcont;
              $cllayout_SEFIP->SFPRegistro30_054_123 = $z01_nome;
              $cllayout_SEFIP->SFPRegistro30_124_134 = $iRegist;
              $cllayout_SEFIP->SFPRegistro30_135_141 = $xctps_n;
              $cllayout_SEFIP->SFPRegistro30_142_146 = $xctps_s;
              $cllayout_SEFIP->SFPRegistro30_147_154 = $xctps_d;
              $cllayout_SEFIP->SFPRegistro30_155_162 = $data_nasc;
              $cllayout_SEFIP->SFPRegistro30_163_167 = $rh37_cbo;
              $cllayout_SEFIP->SFPRegistro30_168_182 = $remuneracaosem13;
              $cllayout_SEFIP->SFPRegistro30_183_197 = $remuneracao13;
              $cllayout_SEFIP->SFPRegistro30_200_201 = $ocorrencia;
              $cllayout_SEFIP->SFPRegistro30_202_216 = $desconto_seguro;
              $cllayout_SEFIP->SFPRegistro30_217_231 = (($situacao_funcionario == 3 || $situacao_funcionario == 4)?$arr_base_inss[$rh01_regist]:0);
              $cllayout_SEFIP->SFPRegistro30_232_246 = $valorrescis;
              
              $aListaGerados[$rh01_regist]['Nome']            = $z01_nome;
              $aListaGerados[$rh01_regist]['TipoContrato']    = $h13_tpcont;
              $aListaGerados[$rh01_regist]['BasePrevidencia'] = $remuneracaosem13;
              $aListaGerados[$rh01_regist]['DescPrevidencia'] = $desconto_seguro;
              $aListaGerados[$rh01_regist]['Base13']          = $remuneracao13;
              $aListaGerados[$rh01_regist]['DescFolha']       = $aDescFolha[$rh01_regist];
              
              if ( $h13_tpcont == '01' ) {
                $aListaGerados[$rh01_regist]['FGTS'] = $remuneracaosem13;
              }

              $cllayout_SEFIP->geraRegist30SFP();
              
	            if(!$mes13){
	            	
                 $codmov = "";
                 $result_afasta = $clafasta->sql_record($clafasta->sql_query_file(null,"*","r45_regist, r45_dtafas desc","r45_anousu = ".$anousu." and r45_mesusu = ".$mesusu." and r45_regist = ".$rh01_regist));
                 $numrows_afasta = $clafasta->numrows;
                 for($in=0; $in<$numrows_afasta; $in++){
                   db_fieldsmemory($result_afasta, $in);

                   // (mes_afas  = mes e  ano_afas =  ano)
                   // (ano_afas  < ano e (dat_reto =  null ou  mes_reto >= mes   e  ano_reto >= ano))
                   // (mes_afas <= ano e  ano_afas <= ano   e (dat_reto  = null ou (mes_reto >= mes e ano_reto >= ano)))


                   if(
                      ((int)db_subdata($r45_dtafas,"m") == (int)$mesusu && (int)db_subdata($r45_dtafas,"a") == (int)$anousu) ||
                      ((int)db_subdata($r45_dtafas,"a") <  (int)$anousu && (trim($r45_dtreto) == "" || ((int)db_subdata($r45_dtreto,"m") >= (int)$mesusu && (int)db_subdata($r45_dtreto,"a") >= (int)$anousu))) ||
                      (((int)db_subdata($r45_dtafas,"m") <= (int)$mesusu && (int)db_subdata($r45_dtafas,"a") <= (int)$anousu) && (trim($r45_dtreto) == "" || ((int)db_subdata($r45_dtreto,"m") >= (int)$mesusu && (int)db_subdata($r45_dtreto,"a") >= (int)$anousu))) ||
                      ((int)db_subdata($r45_dtreto,"a") > (int)$anousu)
                     ){
                     $situacao = $r45_situac;
                     $dataafasta = ($situacao == 3 || $situacao == 6 || $situacao == 8)?date("Y-m-d",mktime(0,0,0,db_subdata($r45_dtafas,"m"), db_subdata($r45_dtafas,"d") - 15, db_subdata($r45_dtafas,"a"))):$r45_dtafas;
                     $dataretorno = $r45_dtreto;
                 
                     $dataini = $dataafasta;
                     $datafim = $dataretorno;
                     if($situacao == 3 || $situacao == 6 || $situacao == 8){
                       $dataini = $r45_dtafas;
                     }
                 
                     if(db_subdata($dataafasta,"m") <= $mesusu && db_subdata($dataafasta,"a") == $anousu){
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
                 
 	                   $temreg = false;
 	                   
                     if(db_subdata($dataretorno,"m") == $mesusu && db_subdata($dataretorno,"a") == $anousu){
                       $datamov = $dataretorno;
                       $codmov  = $r45_codret;
                       $temreg  = true;
                     }
                 
                     $indfgts = "";
                     $result_ifgts = $clcodmovsefip->sql_record($clcodmovsefip->sql_query_file(null,null,null,"r66_ifgtsc,r66_ifgtse","","r66_anousu = ".$anousu." and r66_mesusu = ".$mesusu." and trim(r66_codigo) = '".$r45_codafa."'"));
                     if($clcodmovsefip->numrows > 0){
                       db_fieldsmemory($result_ifgts,0);
                       if($rh30_regime == 2){
                         $indfgts = $r66_ifgtsc;
                       }else{
                         $indfgts = $r66_ifgtse;
                       }
                     }
                     if ($temreg){

			                  $cllayout_SEFIP->SFPRegistro32_004_017 = $cgc;
                        $cllayout_SEFIP->SFPRegistro32_033_043 = $rh16_pis;
                        $cllayout_SEFIP->SFPRegistro32_044_051 = db_formatar($rh01_admiss,'d');
                        $cllayout_SEFIP->SFPRegistro32_052_053 = $h13_tpcont;
                        $cllayout_SEFIP->SFPRegistro32_054_123 = $z01_nome;
                        $cllayout_SEFIP->SFPRegistro32_124_125 = $r45_codafa;
                        $cllayout_SEFIP->SFPRegistro32_126_133 = db_formatar($dataafasta,'d');
                        $cllayout_SEFIP->SFPRegistro32_134_134 = $indfgts;
                        
					                                      
                        $cllayout_SEFIP->geraRegist32SFP();
                


                    }
               
                     $cllayout_SEFIP->SFPRegistro32_004_017 = $cgc;
                     $cllayout_SEFIP->SFPRegistro32_033_043 = $rh16_pis;
                     $cllayout_SEFIP->SFPRegistro32_044_051 = db_formatar($rh01_admiss,'d');
                     $cllayout_SEFIP->SFPRegistro32_052_053 = $h13_tpcont;
                     $cllayout_SEFIP->SFPRegistro32_054_123 = $z01_nome;
                     $cllayout_SEFIP->SFPRegistro32_124_125 = $codmov;
                     $cllayout_SEFIP->SFPRegistro32_126_133 = db_formatar($datamov,'d');
                     $cllayout_SEFIP->SFPRegistro32_134_134 = $indfgts;
                     
                     
                     
				             $aListaGerados[$rh01_regist]['Nome']            = $z01_nome;
  			             $aListaGerados[$rh01_regist]['TipoContrato']    = $h13_tpcont;
  			             
  			             if ( $temreg ) {
				               $aListaGerados[$rh01_regist]['CodAfastamento']  = $r45_codafa;
		 		               $aListaGerados[$rh01_regist]['DataAfastamento'] = db_formatar($dataafasta,'d');
	 	                   $aListaGerados[$rh01_regist]['CodRetorno']      = $codmov;
	                     $aListaGerados[$rh01_regist]['DataRetorno']     = db_formatar($datamov,'d');
  			             } else {   
	                     $aListaGerados[$rh01_regist]['CodAfastamento']  = $codmov;
	                     $aListaGerados[$rh01_regist]['DataAfastamento'] = db_formatar($datamov,'d');
  			             }				                                  
		                    
                     $cllayout_SEFIP->geraRegist32SFP();
                
                   }
                 }
              }
              $codmov = "";
              if(trim($rh05_recis) != "" && !$mes13 ){
                $result_dadosrescisao = $clrescisao->sql_record($clrescisao->sql_query_file($anousu,$mesusu,$rh30_regime,$rh05_causa,$rh05_caub,null,db_getsession("DB_instit"),"r59_movsef"));
                if($clrescisao->numrows > 0){
                  db_fieldsmemory($result_dadosrescisao, 0);
                  $codmov = $r59_movsef; 
                }

                $indfgts = "";
                $result_ifgts = $clcodmovsefip->sql_record($clcodmovsefip->sql_query_file(null,null,null,"r66_ifgtsc,r66_ifgtse","","r66_anousu = ".$anousu." and r66_mesusu = ".$mesusu." and trim(r66_codigo) = '".$codmov."'"));
                if($clcodmovsefip->numrows > 0){
                  db_fieldsmemory($result_ifgts,0);
                  if($rh30_regime == 2){
                    $indfgts = $r66_ifgtsc;
                  }else{
                    $indfgts = $r66_ifgtse;
                  }
                }

                $cllayout_SEFIP->SFPRegistro32_004_017 = $cgc;
                $cllayout_SEFIP->SFPRegistro32_033_043 = $rh16_pis;
                $cllayout_SEFIP->SFPRegistro32_044_051 = db_formatar($rh01_admiss,"d");
                $cllayout_SEFIP->SFPRegistro32_052_053 = $h13_tpcont;
                $cllayout_SEFIP->SFPRegistro32_054_123 = $z01_nome;
                $cllayout_SEFIP->SFPRegistro32_124_125 = $codmov;
                $cllayout_SEFIP->SFPRegistro32_126_133 = db_formatar($rh05_recis,"d");
                $cllayout_SEFIP->SFPRegistro32_134_134 = $indfgts;
                
	              $aListaGerados[$rh01_regist]['Nome']            = $z01_nome;
	              $aListaGerados[$rh01_regist]['TipoContrato']    = $h13_tpcont;
	              $aListaGerados[$rh01_regist]['CodAfastamento']  = $codmov;
	              $aListaGerados[$rh01_regist]['DataAfastamento'] = db_formatar($rh05_recis,"d");

                $cllayout_SEFIP->geraRegist32SFP();
              }
            }
          } else {
          	$aListaSemPIS[$rh01_regist]['Nome']         = $z01_nome;
          	$aListaSemPIS[$rh01_regist]['TipoContrato'] = $h13_tpcont;
          }
        }
        $cllayout_SEFIP->geraRegist90SFP();
        $cllayout_SEFIP->gera();
      }
    }
  }

  if ( count($aListaGerados) > 0 ) {
  
	  $head2 = "Relatório de Conferêcia SEFIP      ";
    $head3 = "Competência : {$mesusu} / {$anousu}";
	  
		$pdf = new PDF(); 
		$pdf->Open(); 
		$pdf->AliasNbPages(); 
		$pdf->setfillcolor(235);
		$pdf->AddPage("L");
		
		$iAlt   = 5;
		$iLista = 1;
		$lPrimeiraPagina = true;
	  
		
    $nBasePrevidencia = 0;
    $nDescPrevidencia = 0;
    $nBase13          = 0;
    $nDesc13          = 0;
    $nFGTS            = 0;
    $iContRegist      = 0;		
		$nDescFolha       = 0;
    
		foreach ( $aListaGerados as $iMatric => $aValores ){
			
			if ($pdf->gety()>$pdf->h-30 || $lPrimeiraPagina ){
				$pdf->setfont('arial','b',8);
			  $pdf->Cell(15,$iAlt,"Matrícula"         ,1,0,"C",1);
			  $pdf->Cell(65,$iAlt,"Nome"              ,1,0,"C",1);
			  $pdf->Cell(20,$iAlt,"Tipo Contrato"     ,1,0,"C",1);
			  $pdf->Cell(18,$iAlt,"Cód. Afasta."      ,1,0,"C",1);
			  $pdf->Cell(18,$iAlt,"Data Afasta."      ,1,0,"C",1);
			  $pdf->Cell(18,$iAlt,"Cód. Retorno"      ,1,0,"C",1);
			  $pdf->Cell(18,$iAlt,"Data Retorno"      ,1,0,"C",1);
			  $pdf->Cell(18,$iAlt,"Base Prev."        ,1,0,"C",1);
			  $pdf->Cell(18,$iAlt,"Desc. Prev."       ,1,0,"C",1);
			  $pdf->Cell(18,$iAlt,"Base 13º"          ,1,0,"C",1);
			  $pdf->Cell(18,$iAlt,"Desc. 13º"         ,1,0,"C",1);
			  $pdf->Cell(18,$iAlt,"FGTS"              ,1,0,"C",1);
			  $pdf->Cell(18,$iAlt,"Desc Folha"        ,1,1,"C",1);
			  $pdf->setfont('arial','',8);
			  
			  $lPrimeiraPagina = false;
			  $iLista = 1;
			}
			
	    if ( !isset($aValores['CodAfastamento']) ) {
	    	$aValores['CodAfastamento'] = '';
	    }
	    if ( !isset($aValores['DataAfastamento']) ) {
	    	$aValores['DataAfastamento'] = '';
	    }
	    if ( !isset($aValores['CodRetorno']) ) {
	    	$aValores['CodRetorno'] = '';
	    }
	    if ( !isset($aValores['DataRetorno']) ) {
	    	$aValores['DataRetorno'] = '';
	    }
	    if ( !isset($aValores['BasePrevidencia']) ) {
	    	$aValores['BasePrevidencia'] = '';
	    }
	    if ( !isset($aValores['DescPrevidencia']) ) {
	    	$aValores['DescPrevidencia'] = '';
	    }
	    if ( !isset($aValores['Base13']) ) {
	    	$aValores['Base13'] = '';
	    }
	    if ( !isset($aValores['Desc13']) ) {
	    	$aValores['Desc13'] = '';
	    }
	    if ( !isset($aValores['FGTS']) ) {
	    	$aValores['FGTS'] = '';
	    }
	    
		  if ( !isset($aValores['DescFolha']) ) {
        $aValores['DescFolha'] = '';
      }	    
			
			if ( $iLista == 1 ) {
				$iLista = 0;
			} else {
				$iLista = 1;
			}
	        
		  $pdf->Cell(15,$iAlt,$iMatric                                     ,0,0,'C',$iLista);
		  $pdf->Cell(65,$iAlt,$aValores['Nome']                            ,0,0,'L',$iLista);
		  $pdf->Cell(20,$iAlt,$aValores['TipoContrato']                    ,0,0,'C',$iLista);
		  $pdf->Cell(18,$iAlt,$aValores['CodAfastamento']                  ,0,0,'C',$iLista);
		  $pdf->Cell(18,$iAlt,$aValores['DataAfastamento']                 ,0,0,'C',$iLista);
		  $pdf->Cell(18,$iAlt,$aValores['CodRetorno']                      ,0,0,'C',$iLista);
		  $pdf->Cell(18,$iAlt,$aValores['DataRetorno']                     ,0,0,'C',$iLista);
		  $pdf->Cell(18,$iAlt,db_formatar($aValores['BasePrevidencia'],'f'),0,0,'R',$iLista);
		  $pdf->Cell(18,$iAlt,db_formatar($aValores['DescPrevidencia'],'f'),0,0,'R',$iLista);
		  $pdf->Cell(18,$iAlt,db_formatar($aValores['Base13'],'f')         ,0,0,'R',$iLista);
		  $pdf->Cell(18,$iAlt,db_formatar($aValores['Desc13'],'f')         ,0,0,'C',$iLista);
		  $pdf->Cell(18,$iAlt,db_formatar($aValores['FGTS'],'f')           ,0,0,'R',$iLista);
		  $pdf->Cell(18,$iAlt,db_formatar($aValores['DescFolha'],'f')      ,0,1,'C',$iLista);
		  
		  $nBasePrevidencia += $aValores['BasePrevidencia'];
		  $nDescPrevidencia += $aValores['DescPrevidencia'];
		  $nBase13          += $aValores['Base13'];
		  $nDesc13          += $aValores['Desc13'];
		  $nFGTS            += $aValores['FGTS'];
		  $nDescFolha       += $aValores['DescFolha'];
		  $iContRegist++;
		  
		}
		
	  $pdf->setfont('arial','b',8);
	  $pdf->Cell(5  ,$iAlt,""                                   ,'T',0,'L',0);
    $pdf->Cell(131,$iAlt,"TOTAL DE REGISTROS : {$iContRegist}",'T',0,'L',0);
    $pdf->Cell(36 ,$iAlt,'TOTAIS:'                            ,'T',0,'R',0);
    $pdf->Cell(18 ,$iAlt,db_formatar($nBasePrevidencia,'f')   ,'T',0,'R',0);
    $pdf->Cell(18 ,$iAlt,db_formatar($nDescPrevidencia,'f')   ,'T',0,'R',0);
    $pdf->Cell(18 ,$iAlt,db_formatar($nBase13,'f')            ,'T',0,'R',0);
    $pdf->Cell(18 ,$iAlt,db_formatar($nDesc13,'f')            ,'T',0,'C',0);
    $pdf->Cell(18 ,$iAlt,db_formatar($nFGTS,'f')              ,'T',0,'R',0);
    $pdf->Cell(18 ,$iAlt,db_formatar($nDescFolha,'f')         ,'T',1,'R',0);
    $pdf->setfont('arial','',8);
     
	  $sArquivoConferencia = "tmp/lista_conferencia_sefip_".date('His').".pdf";
	  $pdf->Output($sArquivoConferencia,false,true);
  
  }
  
  
	if ( count($aListaSemPIS) > 0 ) {
		
	  $head2 = "Relatório de Funcionários sem PIS";
    $head3 = "Competência : {$mesusu} / {$anousu}";
	  
	  $pdf1 = new PDF(); 
	  $pdf1->Open(); 
	  $pdf1->AliasNbPages(); 
	  $pdf1->setfillcolor(235);
	  $pdf1->AddPage();
	  
	  $iAlt   = 5;
	  $iLista = 1;
	  $lPrimeiraPagina   = true;
	  $iContRegistSemPIS = 0;
	  
	  
	  foreach ( $aListaSemPIS as $iMatric => $aValores ){
	    
		  if ($pdf1->gety()>$pdf1->h-30 || $lPrimeiraPagina ){
		    $pdf1->setfont('arial','b',8);
			  $pdf1->Cell(30 ,$iAlt,"Matrícula"         ,1,0,"C",1);
			  $pdf1->Cell(120,$iAlt,"Nome"              ,1,0,"C",1);
			  $pdf1->Cell(40 ,$iAlt,"Tipo de Contrato"  ,1,1,"C",1);
	  	  $pdf1->setfont('arial','',8);
			  $iLista = 1;
			  $lPrimeiraPagina = false;
		  }
		  
	    if ( $iLista == 1 ) {
	      $iLista = 0;
	    } else {
	      $iLista = 1;
	    }
	        
	    $pdf1->Cell(30 ,$iAlt,$iMatric                 ,0,0,'C',$iLista);
	    $pdf1->Cell(120,$iAlt,$aValores['Nome']        ,0,0,'L',$iLista);
	    $pdf1->Cell(40 ,$iAlt,$aValores['TipoContrato'],0,1,'C',$iLista);
	    
	    $iContRegistSemPIS++;
	    
	  }
	  
	  $pdf1->setfont('arial','b',8);
    $pdf1->Cell(5,$iAlt,""                                         ,'T',0,'L',0);
    $pdf1->Cell(0,$iAlt,"TOTAL DE REGISTROS : {$iContRegistSemPIS}",'T',1,'L',0);
    $pdf1->setfont('arial','',8);
	  
	
	  $sArquivoSemPis = "tmp/lista_funcionarios_sem_PIS".date('His').".pdf";
	  $pdf1->Output($sArquivoSemPis,false,true);		
		
	}
  
  
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
      include("forms/db_frmcodmovsefip.php");
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
<script>
js_tabulacaoforms("form1","anousu",true,1,"anousu",true);
</script>
<?
if(isset($gerar)){
	
	$sNomeArquivos  = "/tmp/SEFIP.RE#Arquivo para envio SEFIP";
	
  if ( count($aListaGerados) > 0 ) {	
	  $sNomeArquivos .= "|{$sArquivoConferencia}#Relatório de Conferência";
  }
	
  if ( count($aListaSemPIS) > 0 ) {
    $sNomeArquivos .= "|{$sArquivoSemPis}#Relatório de Funcionário sem PIS";
  }
  
	echo "<script>
	           var sLista = '{$sNomeArquivos}';
	           js_montarlista(sLista,'form1');
	      </script>";
	
}


//if(isset($gerar)){
//  $qry = "?retorno=true";
//  if($sqlerro == true){
//    db_msgbox($erro_msg);
//    $qry = "?retorno=true";
//  }
////  echo "<script>location.href = 'pes1_codmovsefip001.php".$qry."';</script>";
//}else if(isset($retorno)){
//  if($retorno == 'true'){
//    echo "<script>js_montarlista('/tmp/SEFIP.RE#Arquivo para envio SEFIP','form1');</script>";
//  }
//}

?>