<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("libs/db_libpessoal.php");
include("classes/db_db_config_classe.php");
include("classes/db_cfpess_classe.php");
include("classes/db_rhpessoal_classe.php");
include("classes/db_rhpessoalmov_classe.php");
include("classes/db_rescisao_classe.php");
include("classes/db_db_uf_classe.php");
include("classes/db_afasta_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_layouttxt.php");
db_postmemory($HTTP_POST_VARS);
$cldb_config = new cl_db_config;
$clcfpess = new cl_cfpess;
$clrhpessoal = new cl_rhpessoal;
$clrhpessoalmov = new cl_rhpessoalmov;
$clrescisao = new cl_rescisao;
$clafasta = new cl_afasta;
$cldb_uf = new cl_db_uf;
$db_opcao = 1;
$db_botao = true;


if(isset($gerar)){

  $sqlerro = false;
  $codativ2 = 0;
  $anoant = $anousu;
  $mesant = $mesusu - 1;
  if($mesant == 0){
    $anoant-= 1;
    $mesant = 12;
  }

  $anoant = db_formatar($anoant,"s","0",4,"e",0);
  $mesant = db_formatar($mesant,"s","0",2,"e",0);

  $anousu = db_formatar($anousu,"s","0",4,"e",0);
  $mesusu = db_formatar($mesusu,"s","0",2,"e",0);

  $result_db_config = $cldb_config->sql_record($cldb_config->sql_query_file(db_getsession("DB_instit")));
  if($cldb_config->numrows == 0){
    $sqlerro = true;
    $erro_msg = "ERRO: Institui��o n�o encontrada.\\nArquivo n�o poder� ser gerado.";
  }else{
    $result_cfpess = $clcfpess->sql_record($clcfpess->sql_query_file($anousu,$mesusu));
    if($clcfpess->numrows == 0){
      $sqlerro = true;
      $erro_msg = "ERRO: Configura��o da folha n�o encontrada para o Ano/M�s (".$anousu."/".$mesusu.").\\nArquivo n�o poder� ser gerado.";
    }else{

      db_fieldsmemory($result_db_config, 0);
      db_fieldsmemory($result_cfpess,0);

      $competenciai = $mesant.$anoant;
      $competenciaf = $mesusu.$anousu;

      $totaldeestabelecimentos = 1;
      $totaldemovimentos       = 1;

      $primeirodia  = $anousu."-".$mesusu."-01";

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

      include("libs/db_sql.php");
      $clgera_sql_folha = new cl_gera_sql_folha;
      $clgera_subsql_folha = new cl_gera_subsql_folha;
      $clgera_sql_folha->usar_res = true;
      $clgera_sql_folha->usar_atv = true;
      $clgera_sql_folha->usar_doc = true;
      $clgera_sql_folha->usar_cgm = true;
      $clgera_sql_folha->usar_pad = true;
      $clgera_sql_folha->usar_tpc = true;
      $clgera_sql_folha->usar_fun = true;
      $clgera_sql_folha->usar_pad = true;
      $clgera_sql_folha->inner_atv = false;
      $clgera_sql_folha->inner_pad = false;
      $clgera_sql_folha->inner_tpc = false;
      $clgera_sql_folha->inner_pad = false;

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
     // db_criatabela($result_dad);exit;
      if($clrhpessoal->numrows == 0){
        $sqlerro = true;
        $erro_msg = "Nenhum registro encontrado no Ano/M�s (".$anousu."/".$mesusu.").\\nArquivo n�o poder� ser gerado.";
      }else{
        $nomearq = "/tmp/CAGED.TXT";
        $cldb_layouttxr = new db_layouttxt(6,$nomearq,"C,X");
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

        $arr_campos = Array(1=>"regist",2=>"admiss",3=>"rescis",4=>"admrec");
        $arr_ctipos = Array(1=>"n",2=>"d",3=>"d",4=>"c");
        $arr_ctaman = Array(1=>0,2=>0,3=>0,4=>1);
        $arr_cdecim = Array(1=>0,2=>0,3=>0,4=>0);
        db_criatemp("wkcaged",$arr_campos,$arr_ctipos,$arr_ctaman,$arr_cdecim,null);

        $arr_admrec = Array();
        for($i=0; $i<$clrhpessoal->numrows; $i++){
          db_fieldsmemory($result_dad, $i);
          if(trim($rh01_admiss) != ""){
            if((int)db_subdata($rh01_admiss,"m") >= (int)$mesant 
	    && (int)db_subdata($rh01_admiss,"a") >= (int)$anoant 
	    && (int)db_subdata($rh01_admiss,"m") <= ( (int)$mesusu==1?13:(int)$mesusu ) 
	    && (int)db_subdata($rh01_admiss,"a") <= (int)$anousu){
              if(  ((int)db_subdata($rh01_admiss,"m") == (int)$mesusu 
		      && (int)db_subdata($rh01_admiss,"a") == (int)$anousu )
		      || ( (int)db_subdata($rh05_recis,"m") == (int)$mesusu && (int)db_subdata($rh05_recis,"a") == (int)$anousu) 
		){
                $contador_C ++;
                $CX = "C";
              }else{
                $contador_X ++;
                $CX = "X";
	      }
	      if(trim($rh05_recis) != ""){
                 $arr_vals = Array(1=>$i,2=>$rh01_admiss,3=>$rh05_recis,4=>$CX);
	      }else{
                 $arr_vals = Array("1"=>$i,"2"=>$rh01_admiss,"3"=>"null","4"=>$CX);
	      }
              db_insert("wkcaged",$arr_campos,$arr_vals);
            }elseif(trim($rh05_recis) != ""){
            if((int)db_subdata($rh05_recis,"m") >= (int)$mesant 
	    && (int)db_subdata($rh05_recis,"a") >= (int)$anoant 
	    && (int)db_subdata($rh05_recis,"m") <= ( (int)$mesusu==1?13:(int)$mesusu ) 
	    && (int)db_subdata($rh05_recis,"a") <= (int)$anousu){
              if((int)db_subdata($rh05_recis,"m") == (int)$mesusu && (int)db_subdata($rh05_recis,"a") == (int)$anousu){
                $contador_C ++;
                $CX = "C";
              }else{
                $contador_X ++;
                $CX = "X";
              }
              $arr_vals = Array(1=>$i,2=>"null",3=>$rh05_recis,4=>$CX);
              db_insert("wkcaged",$arr_campos,$arr_vals);
	    }
	    }
          }
        }
        if($contador_C > 0 || $contador_X > 0){
          $sequencia = 1;
//          $mesano = (($contador_C > 0)?$anousu:"    ").'-'.(($contador_C > 0)?$mesusu:"  ")."-01";
          $mesano = $anousu.'-'.$mesusu."-01";
          $ender .= " ".$numero;
          $telefone = $codarea.$telefone;
//        $totalestabelecimento = (($contador_C > 0)?"1":"     ");
          $totalestabelecimento = "1";
          $totalmovimentos = ($contador_C + $contador_X);
          db_setaPropriedadesLayoutTxt(&$cldb_layouttxr,1);

//          if($contador_C > 0){
            $sequencia ++;
            db_setaPropriedadesLayoutTxt(&$cldb_layouttxr,2);
//          }
          global $result_wkcaged;
          if($contador_C > 0){
            db_selectmax("result_wkcaged","select * from wkcaged where admrec = 'C' order by admiss,rescis");
            for($i=0; $i<count($result_wkcaged); $i++){
              db_fieldsmemory($result_dad, $result_wkcaged[$i]["regist"]);
              $sequencia++;
              if(is_numeric($rh16_ctps_uf)){
                $result_uf = $cldb_uf->sql_record($cldb_uf->sql_query_file($rh16_ctps_uf,"db12_uf as rh16_ctps_uf"));
                $rh16_ctps_uf = "";
                if($cldb_uf->numrows > 0){
                  db_fieldsmemory($result_uf, 0);
                }
              }
              $rh01_sexo = ((strtoupper($rh01_sexo) == "M")?"1":"2");
              $r02_valor = (($rh02_salari == 0)?(($r02_valor > 0)?$r02_valor:1):$r02_valor);
              $rh02_hrssem = ((trim($rh02_hrssem) != "" && (int)$rh02_hrssem > 0)?$rh02_hrssem:((trim($r02_hrssem) != "" && (int)$r02_hrssem > 0)?$r02_hrssem:2));
              $tipomovimento = tpmov($rh05_causa,$rh01_tipadm,$rh05_recis,$rh02_tpcont);
              $diarescisao = ((trim($rh05_recis) != "")?db_subdata($rh05_recis,"d"):"  ");
              $rh01_raca = (($rh01_raca == 2)?$rh01_raca:"9");
              db_setaPropriedadesLayoutTxt(&$cldb_layouttxr,3,"C");
            }
          }

          db_selectmax("result_wkcaged","select * from wkcaged where admrec = 'X' order by admiss,rescis");
          for($i=0; $i<count($result_wkcaged); $i++){
            db_fieldsmemory($result_dad, $result_wkcaged[$i]["regist"]);
            $sql_FS = $clrhpessoal->sql_query_file($rh01_regist,"db_fxxx(rh01_regist,".$anousu.",".$mesusu.",".db_getsession("DB_instit").")");
            $result_FS = $clrhpessoal->sql_record($clgera_subsql_folha->gera_subsql($sql_FS,"substr(db_fxxx,12,11) as fdois,substr(db_fxxx,111,11) as fddez"));
            if($clrhpessoal->numrows > 0){
              db_fieldsmemory($result_FS, 0);
            }
            $sequencia++;
            if(is_numeric($rh16_ctps_uf)){
              $result_uf = $cldb_uf->sql_record($cldb_uf->sql_query_file($rh16_ctps_uf,"db12_uf as rh16_ctps_uf"));
              $rh16_ctps_uf = "";
              if($cldb_uf->numrows > 0){
                db_fieldsmemory($result_uf, 0);
              }
            }
            $rh01_sexo = ((strtoupper($rh01_sexo) == "M")?"1":"2");
            $r02_valor = (float)$fddez;
            // $rh02_hrssem = (int)$fdois;
            $rh02_hrssem = ((trim($rh02_hrssem) != "" && (int)$rh02_hrssem > 0)?$rh02_hrssem:((trim($r02_hrssem) != "" && (int)$r02_hrssem > 0)?$r02_hrssem:2));
            $tipomovimento = tpmov($rh05_causa,$rh01_tipadm,$rh05_recis,$rh02_tpcont);
            $diarescisao = ((trim($rh05_recis) != "")?db_subdata($rh05_recis,"d"):"  ");
            $mesano = ((trim($rh05_recis) != "")?db_subdata($rh05_recis,"a"):db_subdata($rh01_admiss,"a"))."-".((trim($rh05_recis) != "")?db_subdata($rh05_recis,"m"):db_subdata($rh01_admiss,"m"))."-01";
            $rh01_raca = (($rh01_raca == 2)?$rh01_raca:"9");
            db_setaPropriedadesLayoutTxt(&$cldb_layouttxr,3,"X");
          }
        }else{
          $sqlerro = true;
          $erro_msg = "Nenhuma movimenta��o de celetista encontrada para o Ano / M�s (".$anousu." / ".$mesusu.")";
        }
      }
    }
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
<script>
js_tabulacaoforms("form1","anousu",true,1,"anousu",true);
</script>
<?
if(isset($gerar)){
  $qry = "?retorno=true";
  if($sqlerro == true){
    db_msgbox($erro_msg);
    $qry = "?retorno=false";
  }
  echo "<script>location.href = 'pes1_caged001.php".$qry."';</script>";
}else if(isset($retorno)){
  if($retorno == 'true'){
    echo "<script>js_montarlista('/tmp/CAGED.TXT#Arquivo para envio CAGED','form1');</script>";
  }
}
?>