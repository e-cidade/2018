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
include(modification("libs/db_libpessoal.php"));
include(modification("dbforms/db_funcoes.php"));

db_postmemory($HTTP_POST_VARS);

global $cfpess,$subpes,$d08_carnes;

$subpes = db_anofolha().'/'.db_mesfolha();
$subpes_ano =  db_anofolha();
$subpes_mes =  db_mesfolha();

db_selectmax("cfpess"," select * from cfpess ".bb_condicaosubpes("r11_"));

db_inicio_transacao();

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
<br><br><br>
<center>
<?
db_criatermometro('calculo_folha','Concluído...','blue',1,'Efetuando Geração da RAIS');
?>

<?
db_criatermometro('calculo_folha1','Concluído...','blue',1,'Processando Meses');
?>
</center>

<?
//db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<?

global $db_config , $r70_numcgm , $whererhlota ;
//db_selectmax("db_config","select numero,ender,cgc,nomeinst,bairro,cep,munic,uf,telef, email,lower(trim(munic)) as d08_carnes , cgc from db_config where codigo = ".db_getsession("DB_instit"));

  if ($r70_numcgm==0){
    db_selectmax("db_config","select ender,cgc,nomeinst,bairro,cep,munic,uf, telef, numero, email,lower(trim(munic)) as d08_carnes , cgc from db_config where codigo = ".db_getsession("DB_instit"));
    $whererhlota = "";
  }else{
    db_selectmax("db_config","select z01_cgccpf  as cgc, z01_numero as numero, z01_nome as nomeinst,z01_ender as ender, z01_bairro as bairro, z01_cep as cep, z01_telef as telef, z01_munic as munic,z01_uf as uf, z01_email as email from cgm where z01_numcgm = $r70_numcgm");
    $whererhlota = " and rh02_lota in (select r70_codigo from rhlota where r70_instit = ".db_getsession("DB_instit")." and r70_numcgm = $r70_numcgm) ";

  }

global $d08_ender,$d08_cgc,$d08_nome,$d08_bairro,$d08_cep,$d08_munic,$d08_uf,$d08_telef,$d08_email,$d08_numero;

$d08_ender  = db_translate($db_config[0]["ender"]);
$d08_cgc    = $db_config[0]["cgc"];
$d08_nome   = db_translate($db_config[0]["nomeinst"]);
$d08_bairro = db_translate($db_config[0]["bairro"]);
$d08_cep    = $db_config[0]["cep"];
$d08_munic  = db_translate($db_config[0]["munic"]);
$d08_uf     = $db_config[0]["uf"];
$d08_telef  = $db_config[0]["telef"];
$d08_email  = $db_config[0]["email"];
$d08_numero = $db_config[0]["numero"];

global $ano_base,$mes_base,$codmun,$nome_resp, $cpfr, $obs,$datan, $cnpj_sind,$cnpj_asso,$w_asso,$w_sind,$w_extras,$dataretificacao,$retificacao, $iTipoSistemaPonto;

/**
 * Busca o codigo do municipio do IBGE para a instituição
 */
$sSqlMunicipio  = "select db125_codigosistema                                                         ";
$sSqlMunicipio .= "  from cadendermunicipio                                                           ";
$sSqlMunicipio .= "  inner join db_config on munic = db72_descricao                                   ";
$sSqlMunicipio .= "  inner join cadendermunicipiosistema on db125_cadendermunicipio = db72_sequencial ";
$sSqlMunicipio .= "where db125_db_sistemaexterno = 4 and codigo = " . db_getsession('DB_instit');

$rsMunicipio = pg_query($sSqlMunicipio);
$oMunicipio  = db_utils::fieldsMemory($rsMunicipio, 0);
$codmun      = $oMunicipio->db125_codigosistema;

/**
 * Busca o tipo de sistema de controle de ponto.
 */
$oDaoCfpess = db_utils::getDao('cfpess');
$sSqlCfpess = $oDaoCfpess->sql_query_file($subpes_ano, $subpes_mes, db_getsession('DB_instit'), 'r11_sistemacontroleponto');
$rsCfpess   = db_query($sSqlCfpess);

$oCfpess           = db_utils::fieldsMemory($rsCfpess, 0);
$iTipoSistemaPonto = $oCfpess->r11_sistemacontroleponto;

if(empty($iTipoSistemaPonto)){

  $erro_msg = "Parâmetro Sistema de Controle do Ponto não configurado.";
  echo "<script>parent.js_erro('$erro_msg');</script>";
  db_redireciona("pes4_gerarais001.php");
}

$db_erro = false;
$sqlerro = false;
$nomearq = "tmp/rais.dec";
$nomepdf = "tmp/rais.pdf";

emite_rais($nomearq);

if($sqlerro == false){
  echo "
  <script>
    parent.js_detectaarquivo('$nomearq','$nomepdf');
  </script>
  ";
}else{
  echo "
  <script>
    parent.js_erro('$erro_msg');
  </script>
  ";
}

db_fim_transacao();

db_redireciona("pes4_gerarais001.php");

function emite_rais($nomearq){

global $ano_base,$codmun,$obs,$work,$numcgm,$prefixo,$F010,$F008;
global $subpes_atual,$subpes,$arq_work,$dataretificacao,$retificacao;
$subpes_atual = $subpes;
global $ano_base,$mes_base,$obs,$nome_resp, $cpfr, $datan, $cnpj_sind,$cnpj_asso,$w_asso,$w_sind,$w_extras, $subpes_ano, $subpes_mes, $whererhlota;

 global $diversos;
 db_selectmax( "diversos", "select * from pesdiver ".bb_condicaosubpes( "r07_" ));
 $separa = "global ";
 $quais_diversos = "";
 for($Idiversos=0;$Idiversos<count($diversos);$Idiversos++){
    $codigo = $diversos[$Idiversos]["r07_codigo"];


    $quais_diversos .= $separa.'$'.$codigo;
    $separa = ",";


    global $$codigo;
    eval('$$codigo = '.$diversos[$Idiversos]["r07_valor"].";");
 }
 $quais_diversos .= ';';

cria_work_128();

global $sel_B904,$sel_B008,$basesr;

$condicaoaux  = " and r09_base = ".db_sqlformat( "B904" );
$sel_B904 = "0";
if( db_selectmax( "basesr", "select r09_rubric from basesr ".bb_condicaosubpes("r09_").$condicaoaux )){
  $sel_B904 = "'";
  for($Ibasesr=0;$Ibasesr<count($basesr);$Ibasesr++){
     if($Ibasesr > 0){
        $sel_B904 .= ",'";
     }
     $sel_B904 .= $basesr[$Ibasesr]["r09_rubric"]."'";
  }
}


$condicaoaux  = " and r09_base = ".db_sqlformat( "B008" );
$sel_B008 = "0";
if( db_selectmax( "basesr", "select r09_rubric from basesr ".bb_condicaosubpes("r09_").$condicaoaux )){
  $sel_B008 = "'";
  for($Ibasesr=0;$Ibasesr<count($basesr);$Ibasesr++){
     if($Ibasesr > 0){
        $sel_B008 .= ",'";
     }
     $sel_B008 .= $basesr[$Ibasesr]["r09_rubric"]."'";
  }
}

$numcgm = 0;
$prefixo = 0;

if( $ano_base < db_substr( $subpes_atual,1,4 )){
   $subpes_fim_ano = $ano_base."/12" ;
}else{
   $subpes_fim_ano = $subpes_atual;
}

$matriz1 = array();
$matriz2 = array();

$matriz1[1] = "w_matric";
$matriz1[2] = "w_prefixo";
$matriz1[3] = "w_nome";
$matriz1[4] = "w_pis";
$matriz1[5] = "w_salario";
$matriz1[6] = "w_tiposal";
$matriz1[7] = "w_horas";
$matriz1[8] = "w_carteira";
$matriz1[9] = "w_nasc";
$matriz1[10] = "w_admissao";
$matriz1[11] = "w_fgts";
$matriz1[12] = "w_cpf";
$matriz1[13] = "w_cbo";
$matriz1[14] = "w_vinculo";
$matriz1[15] = "w_instruc";
$matriz1[16] = "w_nacion";
$matriz1[17] = "w_chegada";
$matriz1[18] = "w_tipadm";
$matriz1[19] = "w_raca";
$matriz1[20] = "w_desliga";
$matriz1[21] = "w_causa";
$matriz1[22] = "w_sexo";
$matriz1[23] = "w_adianta";
$matriz1[24] = "w_mesadi";
$matriz1[25] = "w_sal13";
$matriz1[26] = "w_mes13";
$matriz1[27] = "w_mot_afa1";
$matriz1[28] = "w_ini_afa1";
$matriz1[29] = "w_fin_afa1";
$matriz1[30] = "w_dia_afa1";
$matriz1[31] = "w_mot_afa2";
$matriz1[32] = "w_ini_afa2";
$matriz1[33] = "w_fin_afa2";
$matriz1[34] = "w_dia_afa2";
$matriz1[35] = "w_mot_afa3";
$matriz1[36] = "w_ini_afa3";
$matriz1[37] = "w_fin_afa3";
$matriz1[38] = "w_dia_afa3";
$matriz1[39] = "w_deficientefisico";
$matriz1[40] = "w_tipodeficiencia";

global $pess;

  $campos_pessoal  = "distinct on (rh01_regist) rh01_regist as rh01_regist,
                      rh02_anousu as r01_anousu,
                      rh02_mesusu as r01_mesusu,
                      rh01_regist as r01_regist,
                      rh01_numcgm as r01_numcgm,
                      trim(to_char(rh02_lota,'9999')) as r01_lotac,
                      rh16_pis      as r01_pis,
                      rh02_tipsal   as r01_tipsal,
                      lpad(rh16_ctps_n,8,0)||lpad(rh16_ctps_s,5,0) as r01_ctps,
                      rh01_nasc     as r01_nasc,
                      rh01_admiss   as r01_admiss,
                      rh15_data     as r01_fgts,
                      rh02_vincrais as r01_vincul,
                      rh01_instru   as r01_instru,
                      rh01_nacion   as r01_nacion,
                      rh01_anoche   as r01_anoche,
                      rh01_tipadm   as r01_tipadm,
                      rh01_raca     as r01_raca,
                      rh05_recis    as r01_recis,
                      rh30_vinculo  as r01_tpvinc,
                      rh05_causa    as r01_causa,
                      rh01_sexo     as r01_sexo,
                      rh30_vinculo ";

    $condicaoaux  = " and extract(year from rh01_admiss) <= ".db_sqlformat($ano_base);
    $condicaoaux .= " and lower(rh30_vinculo) = 'a' ";
    $condicaoaux .= $whererhlota;
    $condicaoaux .= " and ( rh05_recis is null ";
    $condicaoaux .= "      or  ( rh05_recis is not null  and extract(year from rh05_recis) >= " .db_sqlformat($ano_base)." ) ) ";
		$sql = "select ".$campos_pessoal." from rhpessoalmov
                       inner join rhpessoal    on rhpessoal.rh01_regist       = rhpessoalmov.rh02_regist
                       inner join rhlota       on rhlota.r70_codigo           = rhpessoalmov.rh02_lota
											                        and rhlota.r70_instit           = rhpessoalmov.rh02_instit
                       inner join cgm          on cgm.z01_numcgm              = rhpessoal.rh01_numcgm
                       left join rhpesrescisao on rhpesrescisao.rh05_seqpes   = rhpessoalmov.rh02_seqpes
                       left join rhpesdoc      on rhpesdoc.rh16_regist        = rhpessoalmov.rh02_regist
                       left join rhpespadrao on rhpespadrao.rh03_seqpes       = rhpessoalmov.rh02_seqpes
                       left join rhregime on rhregime.rh30_codreg = rhpessoalmov.rh02_codreg
											                   and rhregime.rh30_instit = rhpessoalmov.rh02_instit
                       left join rhpesfgts on rhpesfgts.rh15_regist = rhpessoalmov.rh02_regist
                       WHERE rh02_anousu = '$ano_base' AND rh02_instit = " . db_getsession('DB_instit') . " $condicaoaux";
                       
    db_selectmax("pess", $sql);

for($Ipes=0;$Ipes<count($pess);$Ipes++){
   if( $pess[$Ipes]["r01_numcgm"] == $numcgm){
       $prefixo += 1;
   }else{
       $numcgm = $pess[$Ipes]["r01_numcgm"];
       $prefixo = 0;
   }
   $condicaoaux = " where z01_numcgm = ".db_sqlformat($pess[$Ipes]["r01_numcgm"]);
   global $cgm;
   db_selectmax( "cgm", "select * from cgm ".$condicaoaux );
   $subpes = $subpes_fim_ano;

  $campos_pessoal  = "rh02_anousu   as r01_anousu,
                      rh02_mesusu   as r01_mesusu,
                      rh01_regist   as r01_regist,
                      rh01_numcgm   as r01_numcgm,
                      rh30_regime   as r01_regime,
                      rh03_padrao   as r01_padrao,
                      rh02_salari   as r01_salari,
                      (case when rh01_progres is not null then 's' else 'n' end) as r01_progr,
                      rh01_funcao   as r01_funcao,
                      case when trim(rh37_cbo) = '' or rh37_cbo is null then '0' else rh37_cbo end::integer as r01_cbo,
                      rh02_hrsmen   as r01_hrsmen,
                      rh30_vinculo  as r01_tpvinc,
                      rh01_admiss   as r01_admiss,
                      rh01_progres  as r01_anter,
                      rh02_hrssem   as r01_hrssem,
                      rh02_deficientefisico as r01_deficientefisico,
                      rh02_tipodeficiencia  as r01_tipodeficiencia";


   global $pessoal_128;

      $condicaoaux  = " and rh02_regist = ".db_sqlformat($pess[$Ipes]["r01_regist"]);
      $sql2 = "select ".$campos_pessoal." from rhpessoalmov
               inner join rhpessoal    on rhpessoal.rh01_regist       = rhpessoalmov.rh02_regist
               inner join rhlota       on rhlota.r70_codigo           = rhpessoalmov.rh02_lota
							                        and rhlota.r70_instit           = rhpessoalmov.rh02_instit
               inner join cgm          on cgm.z01_numcgm              = rhpessoal.rh01_numcgm
               left join rhpesrescisao on rhpesrescisao.rh05_seqpes   = rhpessoalmov.rh02_seqpes
               left join rhpespadrao on rhpespadrao.rh03_seqpes = rhpessoalmov.rh02_seqpes
               left join rhfuncao  on rhfuncao.rh37_funcao = rhpessoal.rh01_funcao
							                    and rhfuncao.rh37_instit = rhpessoalmov.rh02_instit
               left join rhregime  on rhregime.rh30_codreg = rhpessoalmov.rh02_codreg
							                    and rhregime.rh30_instit = rhpessoalmov.rh02_instit
               ".bb_condicaosubpesproc("rh02_",$ano_base.'/'.$mes_base ).$condicaoaux ;

      db_selectmax("pessoal_128", $sql2);

   if(!db_empty($pessoal_128[0]["r01_hrssem"])){
       $F008 = $pessoal_128[0]["r01_hrssem"] * 5;
   }else{
      $condicaoaux  = " and r02_regime = ".db_sqlformat($pessoal_128[0]["r01_regime"] );
      $condicaoaux .= " and trim(upper(r02_codigo)) = ".db_sqlformat(trim(strtoupper($pessoal_128[0]["r01_padrao"])) );
      global $padroes;
      if( db_selectmax( "padroes", "select * from padroes ".bb_condicaosubpes( "r02_" ).$condicaoaux )){
         $F008 = $padroes[0]["r02_hrssem"]  * 5;
      }
   }
   salario_base($pessoal_128,0);
   if( !db_empty($pessoal_128[0]["r01_hrssem"])){
      $mhrs = $pessoal_128[0]["r01_hrssem"];

   }else{
      $condicaoaux  = " and r02_regime = ".db_sqlformat($pessoal_128[0]["r01_regime"] );
      $condicaoaux .= " and r02_codigo = ".db_sqlformat(trim(strtoupper($pessoal_128[0]["r01_padrao"])));
      global $padroes;
      if( db_selectmax("padroes", "select * from padroes ".bb_condicaosubpes( "r02_" ).$condicaoaux )){
         $mhrs = $padroes[0]["r02_hrssem"];
      }else{
         $mhrs = $pessoal_128[0]["r01_hrssem"];
      }
   }
   $subpes = $subpes_atual;
   $condicaoaux = " and r37_funcao = ".db_sqlformat( $pessoal_128[0]["r01_funcao"] );

   global $funcao;

   $cbo = db_val($pessoal_128[0]["r01_cbo"]);
   $matriz2[1]  = $pess[$Ipes]["r01_regist"];
   $matriz2[2]  = $prefixo;
   $matriz2[3]  = db_translate(addslashes($cgm[0]["z01_nome"]));
   $matriz2[4]  = $pess[$Ipes]["r01_pis"];
   $matriz2[5]  = $F010;
   $matriz2[6]  = $pess[$Ipes]["r01_tipsal"];
   $matriz2[7]  = $mhrs;
   $matriz2[8]  = $pess[$Ipes]["r01_ctps"];
   $matriz2[9]  = $pess[$Ipes]["r01_nasc"];
   $matriz2[10] = $pess[$Ipes]["r01_admiss"];
   $matriz2[11] = db_nulldata($pess[$Ipes]["r01_fgts"]);
   $matriz2[12] = $cgm[0]["z01_cgccpf"];
   $matriz2[13] = $cbo;
   $matriz2[14] = $pess[$Ipes]["r01_vincul"];
   $matriz2[15] = $pess[$Ipes]["r01_instru"];
   $matriz2[16] = $pess[$Ipes]["r01_nacion"];
   $matriz2[17] = $pess[$Ipes]["r01_anoche"];
   $matriz2[18] = $pess[$Ipes]["r01_tipadm"];
   $matriz2[19] = (db_empty($pess[$Ipes]["r01_raca"])?"2":db_str($pess[$Ipes]["r01_raca"],1));

   /**
    * Retorna a rescisao valida do servidor.
    */
   if( db_empty($pess[$Ipes]["r01_recis"]) ){

     $sSqlRescisao  = " select rh05_recis as r01_recis,                                   ";
     $sSqlRescisao .= "        rh05_causa as r01_causa                                    ";
     $sSqlRescisao .= "   from rhpesrescisao                                              ";
     $sSqlRescisao .= "        inner join rhpessoalmov on rh05_seqpes = rh02_seqpes       ";
     $sSqlRescisao .= "  where rh02_anousu = $ano_base                                    ";
     $sSqlRescisao .= "    and rh02_regist = {$pess[$Ipes]['rh01_regist']}                ";
     $sSqlRescisao .= "    and rh05_recis between '$ano_base-01-01' and '$ano_base-12-31' ";
     $sSqlRescisao .= "    and rh02_instit = " . db_getsession('DB_instit') ;
     $sSqlRescisao .= "  limit 1                                                          ";

     $rsSqlRescisao    = pg_query($sSqlRescisao);

     $aServidorRecisao = db_utils::getCollectionByRecord($rsSqlRescisao);

     if ( !empty($aServidorRecisao) ) {

       $pess[$Ipes]["r01_recis"] = (isset($aServidorRecisao[0])) ? $aServidorRecisao[0]->r01_recis : '';
       $pess[$Ipes]["r01_causa"] = (isset($aServidorRecisao[0])) ? $aServidorRecisao[0]->r01_causa : '';
     }
   }

   if( !db_empty($pess[$Ipes]["r01_recis"]) && db_year($pess[$Ipes]["r01_recis"]) <= db_val($ano_base)){
      $matriz2[20] = db_nulldata($pess[$Ipes]["r01_recis"]);
      $matriz2[21] = $pess[$Ipes]["r01_causa"];
   }else{
      $matriz2[20] = db_nulldata('');
      $matriz2[21] = 0;
   }

   $matriz2[22] = ($pess[$Ipes]["r01_sexo"]=="M"?"1":"2");
   $matriz2[23] = 0;
   $matriz2[24] = 0;
   $matriz2[25] = 0;
   $matriz2[26] = 0;

   /**
    * Retorna os afastamentos do servidor se possuir.
    */
   $sSqlAfastamentos  = " select  case r45_situac                                              ";
   $sSqlAfastamentos .= "             when 2 then 70                                           ";
   $sSqlAfastamentos .= "             when 3 then 10                                           ";
   $sSqlAfastamentos .= "             when 4 then 60                                           ";
   $sSqlAfastamentos .= "             when 5 then 50                                           ";
   $sSqlAfastamentos .= "             when 6 then 40                                           ";
   $sSqlAfastamentos .= "             when 7 then 70                                           ";
   $sSqlAfastamentos .= "             when 8 then 40                                           ";
   $sSqlAfastamentos .= "             else 40                                                  ";
   $sSqlAfastamentos .= "        end                                                           ";
   $sSqlAfastamentos .= "        ||' '||                                                       ";
   $sSqlAfastamentos .= "        to_char(case when r45_dtafas < '$ano_base-01-01'              ";
   $sSqlAfastamentos .= "                     then '$ano_base-01-01'                           ";
   $sSqlAfastamentos .= "                     else r45_dtafas                                  ";
   $sSqlAfastamentos .= "                end,'ddmm')                                           ";
   $sSqlAfastamentos .= "        ||' '||                                                       ";
   $sSqlAfastamentos .= "        to_char(case when r45_dtreto > '$ano_base-12-31'              ";
   $sSqlAfastamentos .= "                       or r45_dtreto is null                          ";
   $sSqlAfastamentos .= "                     then '$ano_base-12-31'                           ";
   $sSqlAfastamentos .= "                     else r45_dtreto                                  ";
   $sSqlAfastamentos .= "                end,'ddmm')                                           ";
   $sSqlAfastamentos .= "        ||' '||                                                       ";
   $sSqlAfastamentos .= "        to_char(                                                      ";
   $sSqlAfastamentos .= "        case when r45_dtreto > '$ano_base-12-31'                      ";
   $sSqlAfastamentos .= "                       or r45_dtreto is null                          ";
   $sSqlAfastamentos .= "                     then '$ano_base-12-31'                           ";
   $sSqlAfastamentos .= "                     else r45_dtreto                                  ";
   $sSqlAfastamentos .= "        end                                                           ";
   $sSqlAfastamentos .= "        -                                                             ";
   $sSqlAfastamentos .= "        case when r45_dtafas < '$ano_base-01-01'                      ";
   $sSqlAfastamentos .= "                     then '$ano_base-01-01'                           ";
   $sSqlAfastamentos .= "                     else r45_dtafas                                  ";
   $sSqlAfastamentos .= "        end                                                           ";
   $sSqlAfastamentos .= "        + 1                                                           ";
   $sSqlAfastamentos .= "        ,'999') as afastamento                                        ";
   $sSqlAfastamentos .= "         from afasta                                                  ";
   $sSqlAfastamentos .= "        where r45_anousu = $ano_base                                  ";
   $sSqlAfastamentos .= "          and r45_mesusu  = $mes_base                                 ";
   $sSqlAfastamentos .= "  and r45_dtafas <= '$ano_base-12-31'                          ";
   $sSqlAfastamentos .= "          and (r45_dtreto is null or r45_dtreto >= '$ano_base-01-01') ";
   $sSqlAfastamentos .= "          and r45_regist = {$pess[$Ipes]['rh01_regist'] }             ";
   $sSqlAfastamentos .= " order by r45_dtafas limit 3                                          ";

   $rsAfastamentos = pg_query($sSqlAfastamentos);

   $oAfastamento = db_utils::getCollectionByRecord($rsAfastamentos);

   $pess[$Ipes]["afa1"] = (isset($oAfastamento[0])) ? $oAfastamento[0]->afastamento : '';
   $pess[$Ipes]["afa2"] = (isset($oAfastamento[1])) ? $oAfastamento[1]->afastamento : '';
   $pess[$Ipes]["afa3"] = (isset($oAfastamento[2])) ? $oAfastamento[2]->afastamento : '';


   $matriz2[27] = (trim(substr($pess[$Ipes]["afa1"],0,2))==''?'00'  :substr($pess[$Ipes]["afa1"],0,2));
   $matriz2[28] = (trim(substr($pess[$Ipes]["afa1"],3,4))==''?'0000':substr($pess[$Ipes]["afa1"],3,4));
   $matriz2[29] = (trim(substr($pess[$Ipes]["afa1"],8,4))==''?'0000':substr($pess[$Ipes]["afa1"],8,4));
   $matriz2[30] = substr($pess[$Ipes]["afa1"],14,3)+0;

   $matriz2[31] = (trim(substr($pess[$Ipes]["afa2"],0,2))==''?'00'  :substr($pess[$Ipes]["afa2"],0,2));
   $matriz2[32] = (trim(substr($pess[$Ipes]["afa2"],3,4))==''?'0000':substr($pess[$Ipes]["afa2"],3,4));
   $matriz2[33] = (trim(substr($pess[$Ipes]["afa2"],8,4))==''?'0000':substr($pess[$Ipes]["afa2"],8,4));
   $matriz2[34] = substr($pess[$Ipes]["afa2"],14,3)+0;

   $matriz2[35] = (trim(substr($pess[$Ipes]["afa3"],0,2))==''?'00'  :substr($pess[$Ipes]["afa3"],0,2));
   $matriz2[36] = (trim(substr($pess[$Ipes]["afa3"],3,4))==''?'0000':substr($pess[$Ipes]["afa3"],3,4));
   $matriz2[37] = (trim(substr($pess[$Ipes]["afa3"],8,4))==''?'0000':substr($pess[$Ipes]["afa3"],8,4));
   $matriz2[38] = substr($pess[$Ipes]["afa3"],14,3)+0;
   $matriz2[39] = $pessoal_128[0]["r01_deficientefisico"];

   //Se não for informado (null) o tipo de deficiência será setado para 0
   $matriz2[40] = $pessoal_128[0]["r01_tipodeficiencia"] === null ? '0' : $pessoal_128[0]["r01_tipodeficiencia"];

   db_insert( $arq_work, $matriz1, $matriz2 );
}

$indice = " order by w_prefixo, w_nome ";
global $work;
db_selectmax( "work", "select * from ".$arq_work.$indice );
ficha_128();
db_selectmax( "work", "select * from ".$arq_work.$indice );
ajusresc();
db_selectmax( "work", "select * from ".$arq_work.$indice );

imprime_rais_128($nomearq);
}

function ficha_128(){

   db_sel_cfpess(null,null,"r11_mes13,r11_codaec,r11_natest,r11_fgts12,r11_codaec,r11_altfer");
   global $r11_mes13,$r11_codaec,$r11_natest,$r11_fgts12,$r11_codaec,$r11_altfer;
   global $ano_base,$mes_base,$codmun,$obs,$nome_resp, $cpfr , $datan, $cnpj_sind,$cnpj_asso,$w_asso,$w_sind,$w_extras,$dataretificacao,$retificacao;
   global $subpes,$sal13,$arq_work,$work,$Iwork,$cfpess, $subpes_ano, $subpes_mes;

   $ant = $subpes;

   $indice = " order by w_prefixo, w_nome ";

   $max = count($work);
   $retorna = true;
   for($ind=1;$ind<=12;$ind++){

      $subpes = $ano_base . "/" . db_str($ind,2,0,"0");

      $anomes = $ano_base . db_str($ind,2,0,"0");

      $atual = 0;
      $mes = db_substr("janfevmarabrmaijunjulagosetoutnovdez",($ind*3)-2,3);

      $matriz1 = array();
      $matriz2 = array();
      $matriz1[1] = "w_sal13";
      $matriz1[2] = "w_mes13";

      $matriz2 = array();
      $matriz3[1] = "w_adianta";
      $matriz3[2] = "w_mesadi";

      for($Iwork=0;$Iwork<count($work);$Iwork++){
         db_atutermometro($Iwork,count($work),"calculo_folha1",1);
         $avisoprevio = 0;
         $feriasresc  = 0;
         $atual      += 1;
         $soma        = 0;
 	       $soma_sind   = 0;
 	       $soma_asso   = 0;
 	       $soma_extras = 0;
         $sal13       = 0;
         $matricula   = $work[$Iwork]["w_matric"];

	 global $gerfsal;
         $condicaoaux = " and (r14_rubric < '4000' or r14_rubric > '6000') and r14_regist = ".db_sqlformat($work[$Iwork]["w_matric"] );
	 global $gerfsal;
         if( db_selectmax( "gerfsal", "select * from gerfsal ".bb_condicaosubpes( "r14_" ).$condicaoaux )){
            $soma        += soma_128($gerfsal,"r14_");
            $soma_sind   += soma_128_sind($gerfsal,"r14_",$w_sind);
            $soma_asso   += soma_128_asso($gerfsal,"r14_",$w_asso);
            $soma_extras += soma_128_extras($gerfsal,"r14_",$w_extras);
         }

         $condicaoaux = " and (r48_rubric < '4000' or r48_rubric > '6000') and r48_regist = ".db_sqlformat($work[$Iwork]["w_matric"] );
	 global $gerfcom;
         if( db_selectmax( "gerfcom", "select * from gerfcom ".bb_condicaosubpes( "r48_" ).$condicaoaux )){
            $soma += soma_128($gerfcom,"r48_");
            $soma_sind   += soma_128_sind($gerfcom,"r48_",$w_sind);
            $soma_asso   += soma_128_asso($gerfcom,"r48_",$w_asso);
            $soma_extras += soma_128_extras($gerfcom,"r48_",$w_extras);
         }
         if( $subpes < $r11_altfer || db_empty( $r11_altfer )){
            $condicaoaux = " and r31_regist = ".db_sqlformat($work[$Iwork]["w_matric"] );
	    global $gerffer;
            if( db_selectmax( "gerffer", "select * from gerffer ".bb_condicaosubpes( "r31_" ).$condicaoaux )){
               $soma += soma_128($gerffer,"r31_");
               $soma_sind   += soma_128_sind($gerffer,"r31_",$w_sind);
               $soma_asso   += soma_128_asso($gerffer,"r31_",$w_asso);
               $soma_extras += soma_128_extras($gerffer,"r31_",$w_extras);
            }
         }
         $condicaoaux = " and r20_regist = ".db_sqlformat($work[$Iwork]["w_matric"] );
	 global $gerfres;
   
         if( db_selectmax( "gerfres", "select * from gerfres ".bb_condicaosubpes( "r20_" ).$condicaoaux ) ){

            $soma        += soma_128($gerfres,"r20_");
            $soma_sind   += soma_128_sind($gerfres,"r20_",$w_sind);
            $soma_asso   += soma_128_asso($gerfres,"r20_",$w_asso);
            $soma_extras += soma_128_extras($gerfres,"r20_",$w_extras);

            // somar o valor de aviso previo indenizado;
            if( !db_empty( $work[$Iwork]["w_desliga"] ) && $ano_base >= "2001"){

                $avisoprevio += soma_128_aviso_previo($gerfres,"r20_");
            }
            // somar o valor de ferias na rescisao;
            if( !db_empty( $work[$Iwork]["w_desliga"] ) && $ano_base >= "2005" ){

                $feriasresc += soma_128_ferias_resc($gerfres,"r20_");
                // para que nao some 2 vezes o valor das rubricas 2000;
//              soma -= feriasresc;
            }

         }
         $condicaoalt = " where w_matric = ".db_sqlformat( $matricula );

         if( $sal13 > 0
           &&  $ind < $cfpess[0]["r11_mes13"]
           && db_empty($work[$Iwork]["w_desliga"])
           || ( $anomes < db_substr(db_dtos($work[$Iwork]["w_desliga"]),1,6) && $sal13 != 0)  ){

            $mesadi = $ind;
            $matriz3[1] = "w_adianta" ;
            $matriz3[2] = "w_mesadi";
            $matriz4[1] = ( $work[$Iwork]["w_adianta"] + $sal13 );
            $matriz4[2] = $mesadi;
            db_update( $arq_work, $matriz3,$matriz4, $condicaoalt );

         }else if ($sal13 != 0) {

            $mes13 = $ind;
            $matriz1[1] = "w_sal13" ;
            $matriz1[2] = "w_mes13";
            $matriz2[1] = $sal13 ;
            $matriz2[2] = $mes13;
            db_update( $arq_work, $matriz1,$matriz2, $condicaoalt );
         }

         $condicaoaux = " and r35_regist = ".db_sqlformat($work[$Iwork]["w_matric"] );
	       global $gerfs13;
         if( db_selectmax( "gerfs13", "select * from gerfs13 ".bb_condicaosubpes( "r35_" ).$condicaoaux )){
            $mes13 = $ind;
            $x = soma_128($gerfs13,"r35_");

           /*
            * Verificamos se o servidor possui alguma rubrica de 13º lançado no calculo de salário (gerfsal)
            * Se existir, somamos esse valor ao valor do calculo de 13º (gerfs13)
            */
            $sSqlGerfsal13 = "select coalesce(sum(r14_valor),0) as valor
                              from gerfsal
                              where r14_anousu = $ano_base
                                and r14_regist = ".db_sqlformat($work[$Iwork]["w_matric"] )."
                                and r14_rubric between '4000' and '6000'";
            $rsGerfsal13   = db_query($sSqlGerfsal13);
            $nGerfSal13    = pg_result($rsGerfsal13,0,0);

            $sSqlGerfcom13 = "select coalesce(sum(r48_valor),0) as valor
                              from gerfcom
                              where r48_anousu = $ano_base
                                and r48_regist = ".db_sqlformat($work[$Iwork]["w_matric"] )."
                                and r48_rubric between '4000' and '6000'";
            $rsGerfcom13   = db_query($sSqlGerfcom13);
            $nGerfcom13    = pg_result($rsGerfcom13,0,0);

            $sal13 += $x;
            if( $sal13 != 0 && $ind < $r11_mes13 && (db_empty($work[$Iwork]["w_desliga"]) || $anomes < db_substr(db_dtos($work[$Iwork]["w_desliga"]),1,6))) {

               $matriz3[1] = "w_adianta" ;
               $matriz3[2] = "w_mesadi";

               $matriz4[1] = ( $work[$Iwork]["w_adianta"] + $sal13 );
               $matriz4[2] = $mes13;
               db_update( $arq_work, $matriz3, $matriz4, $condicaoalt );

               $sal13 = 0;
            }else{
               $matriz1[1] = "w_sal13" ;
               $matriz1[2] = "w_mes13";

               $matriz2[1] = $sal13+$nGerfSal13+$nGerfcom13;
               $matriz2[2] = $mes13;
               db_update( $arq_work, $matriz1, $matriz2, $condicaoalt );

           }
         }

         $matriz5 = array();
   	     $matriz6 = array();
         $matriz5[1] = "w_".$mes;
         $matriz5[2] = "w_avisop";
         $matriz5[3] = "w_si_".$mes;
         $matriz5[4] = "w_as_".$mes;
         $matriz5[5] = "w_he_".$mes;
         $matriz6[1] = ($soma<0?0:$soma);
         $matriz6[2] = $work[$Iwork]["w_avisop"] + $avisoprevio;
         $matriz6[3] = $soma_sind;
         $matriz6[4] = $soma_asso;
         $matriz6[5] = $soma_extras;

        /**
         * Verificamos se o servidor ja possui registro na tabela temporaria e se o mesmo é zero 
         * para que ao processar a rais a rotina nao sobreescreva o valor das ferias pagas na rescisao
         * É necessário que a lógica para o calculo do mesmo seja reescrita pois da forma que a rotina
         * se comporta o valor sempre sera zerado a menos que as ferias sejam pagas em dezembro 
         * juntamente com a rescisao. O ajuste abaixo foi feito para diminuir os pontos de impacto. 
         */
         $lAtualizaValorFeriasNaRescisao = 0;
         $sSqlVerificaFeriasNaRescisao   = "select 1 from $arq_work where w_matric = " . $work[$Iwork]["w_matric"] . " and w_fer_res = 0";
         $rsVerificaFeriasNaRescisao     = db_query($sSqlVerificaFeriasNaRescisao);

         if( $rsVerificaFeriasNaRescisao ){

          $iNumeroLinhasVerificaFeriasNaRescisao =  pg_num_rows($rsVerificaFeriasNaRescisao);

          if ($iNumeroLinhasVerificaFeriasNaRescisao <> 0 ){
            
           $lAtualizaValorFeriasNaRescisao = pg_result($rsVerificaFeriasNaRescisao, 0, 0);

           if( $lAtualizaValorFeriasNaRescisao == 1 ){

             $matriz5[6] = "w_fer_res";
             $matriz6[6] = $work[$Iwork]["w_fer_res"] + $feriasresc;
           }
          }
         }

         db_update( $arq_work, $matriz5, $matriz6, $condicaoalt );
      }

      db_atutermometro($ind,13,'calculo_folha',1);
   $subpes = $ant;
   }
}

function imprime_rais_128($nomearq){

  global $ano_base,$mes_base,$codmun,$obs,$nome_resp, $cpfr,$datan, $cnpj_sind,$cnpj_asso,$w_asso,$w_extras,$w_sind,$pos,$pdf,$head1,$head2,$head3;
  global $d08_ender,$d08_cgc,$d08_nome,$d08_bairro,$d08_cep,$d08_munic,$d08_uf,$d08_telef,$d08_email, $d08_numero,$dataretificacao,$retificacao;
  db_sel_cfpess(null,null,"r11_mes13,r11_codaec,r11_natest,r11_fgts12,r11_codaec,r11_altfer");
  global $r11_mes13,$r11_codaec,$r11_natest,$r11_fgts12,$r11_codaec,$r11_altfer,$work,$tot_val,$tot_as,$tot_he,$iTipoSistemaPonto,$nomepdf;

   $pdf = new PDF();
   $pdf->Open();
   $pdf->AliasNbPages();
   $pdf->setfillcolor(235);
   $pdf->setfont('arial','b',8);
   $troca = 1;
   $alt   = 4;
   $head2 = "Relatório de Simples conferência - RAIS";
   $head3 = "ANO BASE :".$ano_base;

  $arquivo = fopen($nomearq,"w");

  $seq = 1;
  $prefixo = 999;
  $vinculo = 0;

  $ncom    = (db_at("-",$d08_ender)>0?true:false);
  $ntam    = (db_at(",",$d08_ender)>0?true:false);
  $ccom    = bb_space(21);
  if( $ncom != 0){
     $ccom = db_substr($d08_ender,(strlen($d08_ender)-$ncom)*-1);
  }
  $ccom .= bb_space(21-strlen($ccom)) ;

  if( $ntam != 0){
     if( $ncom != 0){
        $nume  = db_substr($d08_ender,(($ncom-1)-$ntam)*-1)  ;
     }else{
        $nume  = db_substr($d08_ender,(strlen($d08_ender)-$ntam)*-1);
     }
     $ende    = db_substr($d08_ender,1,$ntam-1) ;
  }else{
     $nume    = "000000";
     $ende    = $d08_ender ;
  }

  $datageracao = db_strtran( db_dtoc( date('Y-m-d',db_getsession("DB_datausu")) ), "-", "" );
  
  // Registro Tipo 0 esta de acordo com o layout 2007
  $lin  = "000001";                                                   // 01 a 06   - Sequencial do registro do arquivo;
  $lin .= str_pad( $d08_cgc,14 );                                     // 07 a 20   - Inscricao cnpj;
  $lin .= "00";                                                       // 21 a 22   - Prefixo do 1.estabelecimento;
  $lin .= "0";                                                        // 23 a 23   - Tipo dp registro = 0;
  $lin .= "1";                                                        // 24 a 24   - Constante indicador de endereço para envio;
  $lin .= str_pad($d08_cgc,14);                                       // 25 a 38   - Inscrição CNPJ/CEI/CPF do responsável;
  $lin .= "1";                                                        // 39 a 39   - Tipo inscricao responsavel (1=cnpj/2=cei/3=cpf);
  $lin .= str_pad($d08_nome,40);                                      // 40 a 79   - Nome responsavel;
  $lin .= str_pad($ende,40);                                          // 80 a 119  - Ender responsavel;
  $lin .= db_str($d08_numero,6,0,"0");                                // 120 a 125 - Numero;
  $lin .= str_pad(trim($ccom),21);                                    // 126 a 146 - Complemento;
  $lin .= str_pad(trim($d08_bairro),19);                              // 147 a 165 - Bairro;
  $lin .= str_pad($d08_cep,8);                                        // 166 a 173 - Cep;
  $lin .= str_pad(str_replace('-','',str_replace('.','',$codmun)),7); // 174 a 180 - Codigo do municipio;
  $lin .= str_pad($d08_munic,30);                                     // 181 a 210 - Nome do municipio;
  $lin .= str_pad($d08_uf,2);                                         // 211 a 212 - Uf;
  $lin .= str_pad( substr($d08_telef,0,2),2);                         // 213 a 214 - ddd;
  $lin .= str_pad( trim(db_substr($d08_telef,-9)), 9 );               // 215 a 223 - Telefone;
  $lin .= $retificacao;                                               // 224 a 224 - Indicador de retificação da declaração;
                                                                      //             1 - retifica os estabelecimentos entregues anteriormente
                                                                      //             2 - a declaração não é retificação (é primeira entrega)
  if($retificacao == 2){
    $lin .= "00000000";                                               // 225 a 232 - ddmmaaaa - data retificacao dos estabelecimentos;
  }else{

    $dataretificacao = db_strtran( $dataretificacao, "/", "" );
    $lin .= db_str($dataretificacao,8,0,"0");                         // 225 a 232 - ddmmaaaa - data retificacao dos estabelecimentos;
  }
  $lin .= $datageracao;                                               // 233 a 240 - ddmmaaaa - data da geração do Arquivo;
  $lin .= str_pad( $d08_email ,45 );                                  // 241 a 285 - E-mail;
  $lin .= str_pad(trim($nome_resp),52);                               // 286 a 337 - Nome do Responsável;
  $lin .= bb_space(24);                                               // 338 a 361 - Espacos;
  $lin .= db_str($cpfr,11,0,"0");                                     // 362 a 372 - CPF do responsavel
  $lin .= "000000000000";                                             // 373 a 384 - CREA a ser retificado
  $lin .= db_str($datan,8,0,"0");                                    // 385 a 392 - Data de nascimento do resonsavel (ddmmaaaa)
  $lin .= bb_space(159);                                              // 393 a 551 - Espacos;
  fputs($arquivo,$lin."\n");

  $t_asso   = 0;
  $t_sind   = 0;
  $t_extras = 0;
  $tot_ts   = 0;
  $prefixo  = 999;

  $pre_fixo = array();
  $pref_ts  = array();
  $pref_ta  = array();

  for($Iwork=0;$Iwork<count($work);$Iwork++){
     // total anual dos valores dos sindicatos;
     // filtra funcionarios sem remuneracao no ano base;
     $rendg = $work[$Iwork]["w_jan"]+$work[$Iwork]["w_fev"]+$work[$Iwork]["w_mar"]+$work[$Iwork]["w_abr"]+$work[$Iwork]["w_mai"]+$work[$Iwork]["w_jun"] +
              $work[$Iwork]["w_jul"]+$work[$Iwork]["w_ago"]+$work[$Iwork]["w_set"]+$work[$Iwork]["w_out"]+$work[$Iwork]["w_nov"]+$work[$Iwork]["w_dez"] ;
     if( db_empty($rendg)){
        continue;
     }

     $pos = db_ascan($pre_fixo,$work[$Iwork]["w_prefixo"]);

     if( $work[$Iwork]["w_prefixo"] != $prefixo){
         $prefixo = $work[$Iwork]["w_prefixo"];

        if( $pos == 0){
           $tot_ts++;
     	     $pos = $tot_ts;
     	     $pre_fixo[$pos] = $work[$Iwork]["w_prefixo"];
     	     $pref_ts[$pos]  = 0;
     	     $pref_ta[$pos]  = 0;
        }

      }

     $pref_ts[$pos] += $work[$Iwork]["w_si_jan"]+$work[$Iwork]["w_si_fev"]+$work[$Iwork]["w_si_mar"]+$work[$Iwork]["w_si_abr"]+$work[$Iwork]["w_si_mai"]+$work[$Iwork]["w_si_jun"]+
                       $work[$Iwork]["w_si_jul"]+$work[$Iwork]["w_si_ago"]+$work[$Iwork]["w_si_set"]+$work[$Iwork]["w_si_out"]+$work[$Iwork]["w_si_nov"]+$work[$Iwork]["w_si_dez"] ;

     $pref_ta[$pos] += $work[$Iwork]["w_as_jan"]+$work[$Iwork]["w_as_fev"]+$work[$Iwork]["w_as_mar"]+$work[$Iwork]["w_as_abr"]+$work[$Iwork]["w_as_mai"]+$work[$Iwork]["w_as_jun"]+
                       $work[$Iwork]["w_as_jul"]+$work[$Iwork]["w_as_ago"]+$work[$Iwork]["w_as_set"]+$work[$Iwork]["w_as_out"]+$work[$Iwork]["w_as_nov"]+$work[$Iwork]["w_as_dez"];


  }

  $prefixo = 999;
  for($Iwork=0;$Iwork<count($work);$Iwork++){
     // filtra funcionarios sem remuneracao no ano base;
     $rendg = $work[$Iwork]["w_jan"]+$work[$Iwork]["w_fev"]+$work[$Iwork]["w_mar"]+$work[$Iwork]["w_abr"]+$work[$Iwork]["w_mai"]+$work[$Iwork]["w_jun"]+
              $work[$Iwork]["w_jul"]+$work[$Iwork]["w_ago"]+$work[$Iwork]["w_set"]+$work[$Iwork]["w_out"]+$work[$Iwork]["w_nov"]+$work[$Iwork]["w_dez"] ;

     if( db_empty($rendg)){
        continue;
     }

     $w_sind = $work[$Iwork]["w_si_jan"]+$work[$Iwork]["w_si_fev"]+$work[$Iwork]["w_si_mar"]+$work[$Iwork]["w_si_abr"]+$work[$Iwork]["w_si_mai"]+$work[$Iwork]["w_si_jun"]+
               $work[$Iwork]["w_si_jul"]+$work[$Iwork]["w_si_ago"]+$work[$Iwork]["w_si_set"]+$work[$Iwork]["w_si_out"]+$work[$Iwork]["w_si_nov"]+$work[$Iwork]["w_si_dez"] ;

     $w_asso = $work[$Iwork]["w_as_jan"]+$work[$Iwork]["w_as_fev"]+$work[$Iwork]["w_as_mar"]+$work[$Iwork]["w_as_abr"]+$work[$Iwork]["w_as_mai"]+$work[$Iwork]["w_as_jun"]+
               $work[$Iwork]["w_as_jul"]+$work[$Iwork]["w_as_ago"]+$work[$Iwork]["w_as_set"]+$work[$Iwork]["w_as_out"]+$work[$Iwork]["w_as_nov"]+$work[$Iwork]["w_as_dez"];


     if( !$work[$Iwork]["w_prefixo"] == $prefixo){
         $prefixo = $work[$Iwork]["w_prefixo"];

         // REGISTRO TIPO-1 esta de acordo com Layout 2007
         $seq += 1;
         $lin  = db_str($seq,6,0,"0");                                       // 001 a 006 - sequencial do registro no arquivo ;
         $lin .= str_pad(trim($d08_cgc),14);                                 // 007 a 020 - inscrição do estabelecimento;
         $lin .= db_str(trim($prefixo),2,0,"0");                             // 021 a 022 - diferenciador de sub-arquivos ;
         $lin .= "1";                                                        // 023 a 023 - tipo de registro;
         $lin .= str_pad(trim($d08_nome),52);                                // 024 a 075 - razão social;
         $lin .= str_pad(trim($ende),40);                                    // 076 a 115 - endereço ;
         $lin .= db_str($d08_numero,6,0,"0");                                // 116 a 121 - numero;
         $lin .= str_pad(trim($ccom),21);                                    // 122 a 142 - complemento;
         $lin .= str_pad(trim($d08_bairro),19);                              // 143 a 161 - bairro;
         $lin .= str_pad(trim($d08_cep),8);                                  // 162 a 169 - cep;
         $lin .= str_pad(str_replace('-','',str_replace('.','',$codmun)),7); // 170 a 176 - codigo do municipio;
         $lin .= str_pad(trim($d08_munic),30);                               // 177 a 206 - municipio;
         $lin .= str_pad($d08_uf,2);                                         // 207 a 208 - uf;
         $lin .= str_pad(substr($d08_telef,0,2),2," ");                      // 209 a 210 - ddd;
         $lin .= str_pad(trim(db_substr($d08_telef,-9)), 9," ");             // 211 a 219 - telefone do estabelecimento;
         $lin .= str_pad(trim($d08_email),45 );                              // 220 a 264 - email;
         $lin .= "8411600";                                                  // 265 a 271 - cnae;
         $lin .= "1031";                                                     // 272 a 275 - concla;
         $lin .= "0000";                                                     // 276 a 279 - numero de socios;
         $lin .= $mes_base;                                                  // 280 a 281 - mes da data base da categoria;
         $lin .= "1";                                                        // 282 a 282 - tipo de inscrição - 1 - cnpj;
         $lin .= "0";                                                        // 283 a 283 - identificador de rais negativa;
         $lin .= "00";                                                       // 284 a 285 - fixo zeros;
         $lin .= "000000000000";                                             // 286 a 297 - matricula cei vinculada a inscrição cnpj;
         $lin .= $ano_base;                                                  // 298 a 301 - ano base;
         $lin .= "3";                                                        // 302 a 302 - porte da empresa;
         $lin .= "2";                                                        // 303 a 303 - nao optante pelo simples;
         $lin .= "2";                                                        // 304 a 304 - participa do programa de alimentação do trabalhador;
         $lin .= "000000";                                                   // 305 a 310 - participantes do pat com renda ate 5 sal. minimos;
         $lin .= "000000";                                                   // 311 a 316 - participantes do pat com renda maior que 5 sal. minimos;
         $lin .= "000";                                                      // 317 a 319 - % da modalidade (serviço proprio);
         $lin .= "000";                                                      // 320 a 322 - % da modalidade (adeministração de cozinha);
         $lin .= "000";                                                      // 323 a 325 - % da modalidade (refeiçao convenio);
         $lin .= "000";                                                      // 326 a 328 - % da modalidade (refeiçoes transportadas);
         $lin .= "000";                                                      // 329 a 331 - % da modalidade (cesta de alimentos);
         $lin .= "000";                                                      // 332 a 334 - % da modalidade (alimentação convenio);
         $lin .= "2";                                                        // 335 a 335 - indicador de encerramento das atividades;
         $lin .= "00000000";                                                 // 336 a 343 - data do encerramento das atividades;
         $lin .= str_pad(trim($cnpj_asso),14);	                             // 344 a 357 - cnpj da entidade sindical - associativa;

         $pos = db_ascan($pre_fixo,$prefixo);
         $t_asso = 0;
         $t_sind = 0;
         if($pos != 0){
             $t_sind = $pref_ts[$pos];
             $t_asso = $pref_ta[$pos];
         }

         $lin .= valor_9($t_asso);	                                         // 358 a 366 - valor anual repassado a entidade sindical - associativa;
         $lin .= str_pad($cnpj_sind,14);		                                 // 367 a 380 - cnpj da entidade sindical - sindical;
         $lin .= valor_9($t_sind);	                                         // 381 a 389 - valor anual repassado a entidade sindical - sindical   ;
         $lin .= "00000000000000";	                                         // 390 a 403 - cnpj da entidade sindical - assistencial;
         $lin .= "000000000";		                                             // 404 a 412 - valor anual repassado a entidade sindical - assistencial;
         $lin .= "00000000000000";	                                         // 413 a 426 - cnpj da entidade sindical - confederativa;
         $lin .= "000000000";		                                             // 427 a 435 - valor anual repassado a entidade sindical - confederativa;
         $lin .= "1";                                                        // 436 a 436 - a empresa exerceu atividade no ano base;
         $lin .= "2";                                                        // 437 a 437 - Indicador de centralização do pagamento da contribuição sindical
         $lin .= "00000000000000";                                           // 438 a 451 - cnpj - centralizadora
         $lin .= '1'         ;                                               // 452 a 452 - sindicalizada
         $lin .= str_pad($iTipoSistemaPonto,2, '0', STR_PAD_LEFT);           // 453 a 454 02 Número Tipo de Sistema de Controle de Ponto;
         $lin .= bb_space(85);                                               // 453 a 539 - espacos;
         $lin .= str_pad( substr($obs,0,12), 12 );                           // 540 a 551 - espaco para empresa;
         fputs($arquivo,$lin."\n");
     }
     $vinculo += 1;
     $seq += 1;

     if( $work[$Iwork]["w_nacion"] == 10){
         $chegada = "0000";
     }else{
        //Ajustados anos de chegada dos servidores de 2 dígitos para 4 dígitos
        $chegada = $work[$Iwork]["w_chegada"];
     }
     $tot_val = 0;
     $tot_as  = 0;
     $tot_he  = 0;
     if($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
	     $pdf->addpage();
	     $pdf->setfont('arial','b',8);
	     $troca = 0;
     }
     $pdf->ln($alt);
     $pdf->cell(0,$alt,'',"T",1,"C",0);
     $pdf->cell(35,$alt,"PIS: ".trim($work[$Iwork]["w_pis"]),0,0,"L",0);
     $pdf->cell(80,$alt,"Nome: ".str_pad(trim($work[$Iwork]["w_nome"]),40),0,0,"L",0);
     $pdf->cell(30,$alt,"Reg.: ".trim($work[$Iwork]["w_matric"] ),0,0,"L",0);
     $pdf->cell(35,$alt,"Cpf: ".trim($work[$Iwork]["w_cpf"]),0,1,"L",0);
     $pdf->cell(40,$alt,"Admissão: ".db_formatar($work[$Iwork]["w_admissao"],'d'),0,0,"L",0);
     $pdf->cell(40,$alt,"Rescisão: ".db_formatar($work[$Iwork]["w_desliga"],'d'),0,0,"L",0);
     $pdf->cell(40,$alt,"Causa: ".trim($work[$Iwork]["w_causa"]),0,0,"L",0);
     $pdf->cell(40,$alt,"Aviso: ".trim($work[$Iwork]["w_avisop"]),0,0,"L",0);
     $pdf->cell(50,$alt,"Férias Ind.: ".trim($work[$Iwork]["w_fer_res"]),0,1,"L",0);
     $pdf->cell(50,$alt,"1o. Afas: ".db_str($work[$Iwork]["w_mot_afa1"],2,0,"0").'-'.db_str($work[$Iwork]["w_ini_afa1"],4,0,"0").'-'.db_str($work[$Iwork]["w_fin_afa1"],4,0,"0"),0,0,"L",0);
     $pdf->cell(50,$alt,"2o. Afas: ".db_str($work[$Iwork]["w_mot_afa1"],2,0,"0").'-'.db_str($work[$Iwork]["w_ini_afa1"],4,0,"0").'-'.db_str($work[$Iwork]["w_fin_afa1"],4,0,"0"),0,0,"L",0);
     $pdf->cell(50,$alt,"3o. Afas: ".db_str($work[$Iwork]["w_mot_afa1"],2,0,"0").'-'.db_str($work[$Iwork]["w_ini_afa1"],4,0,"0").'-'.db_str($work[$Iwork]["w_fin_afa1"],4,0,"0"),0,0,"L",0);
     $pdf->cell(50,$alt,"Total Afas: ".db_str($work[$Iwork]["w_dia_afa1"]
                                              +$work[$Iwork]["w_dia_afa2"]
                                              +$work[$Iwork]["w_dia_afa3"],3,0,"0"),0,1,"L",0);

     $tot_val = $work[$Iwork]["w_jan"]+$work[$Iwork]["w_fev"]+$work[$Iwork]["w_mar"]+$work[$Iwork]["w_abr"]+$work[$Iwork]["w_mai"]+$work[$Iwork]["w_jun"]+
                $work[$Iwork]["w_jul"]+$work[$Iwork]["w_ago"]+$work[$Iwork]["w_set"]+$work[$Iwork]["w_out"]+$work[$Iwork]["w_nov"]+$work[$Iwork]["w_dez"];

     $tot_as  = $work[$Iwork]["w_as_jan"]+$work[$Iwork]["w_as_fev"]+$work[$Iwork]["w_as_mar"]+$work[$Iwork]["w_as_abr"]+$work[$Iwork]["w_as_mai"]+$work[$Iwork]["w_as_jun"]+
                $work[$Iwork]["w_as_jul"]+$work[$Iwork]["w_as_ago"]+$work[$Iwork]["w_as_set"]+$work[$Iwork]["w_as_out"]+$work[$Iwork]["w_as_nov"]+$work[$Iwork]["w_as_dez"];

     $tot_he  = $work[$Iwork]["w_he_jan"]+$work[$Iwork]["w_he_fev"]+$work[$Iwork]["w_he_mar"]+$work[$Iwork]["w_he_abr"]+$work[$Iwork]["w_he_mai"]+$work[$Iwork]["w_he_jun"]+
                $work[$Iwork]["w_he_jul"]+$work[$Iwork]["w_he_ago"]+$work[$Iwork]["w_he_set"]+$work[$Iwork]["w_he_out"]+$work[$Iwork]["w_he_nov"]+$work[$Iwork]["w_he_dez"];


       $pdf->ln($alt);
       $pdf->cell(10,$alt, "Mês",1,0,"C",1);
       $pdf->cell(18,$alt, "Valor",1,0,"C",1);
       $pdf->cell(18,$alt, "Ass. Sind",1,0,"C",1);
       $pdf->cell(18,$alt, "H.Extras",1,0,"C",1);

       $pdf->cell(10,$alt, "Mês",1,0,"C",1);
       $pdf->cell(18,$alt, "Valor",1,0,"C",1);
       $pdf->cell(18,$alt, "Ass. Sind",1,0,"C",1);
       $pdf->cell(18,$alt, "H.Extras",1,0,"C",1);

       $pdf->cell(10,$alt, "Mês",1,0,"C",1);
       $pdf->cell(18,$alt, "Valor",1,0,"C",1);
       $pdf->cell(18,$alt, "Ass. Sind",1,0,"C",1);
       $pdf->cell(18,$alt, "H.Extras",1,1,"C",1);

       $pdf->cell(10,$alt, "Jan",1,0,"C",1);
       $pdf->cell(18,$alt,db_formatar( $work[$Iwork]["w_jan"] ,'f'),0,0,"R",0);
       $pdf->cell(18,$alt,db_formatar( $work[$Iwork]["w_as_jan"]  ,'f'),0,0,"R",0);
       $pdf->cell(18,$alt,db_formatar( $work[$Iwork]["w_he_jan"]  ,'f'),0,0,"R",0);
       $pdf->cell(10,$alt, "Fev",1,0,"C",1);
       $pdf->cell(18,$alt,db_formatar( $work[$Iwork]["w_fev"] ,'f'),0,0,"R",0);
       $pdf->cell(18,$alt,db_formatar( $work[$Iwork]["w_as_fev"]  ,'f'),0,0,"R",0);
       $pdf->cell(18,$alt,db_formatar( $work[$Iwork]["w_he_fev"]  ,'f'),0,0,"R",0);
       $pdf->cell(10,$alt, "Mar",1,0,"C",1);
       $pdf->cell(18,$alt,db_formatar( $work[$Iwork]["w_mar"] ,'f'),0,0,"R",0);
       $pdf->cell(18,$alt,db_formatar( $work[$Iwork]["w_as_mar"]  ,'f'),0,0,"R",0);
       $pdf->cell(18,$alt,db_formatar( $work[$Iwork]["w_he_mar"]  ,'f'),0,1,"R",0);


       $pdf->cell(10,$alt, "Abr",1,0,"C",1);
       $pdf->cell(18,$alt,db_formatar( $work[$Iwork]["w_abr"] ,'f'),0,0,"R",0);
       $pdf->cell(18,$alt,db_formatar( $work[$Iwork]["w_as_abr"]  ,'f'),0,0,"R",0);
       $pdf->cell(18,$alt,db_formatar( $work[$Iwork]["w_he_abr"]  ,'f'),0,0,"R",0);
       $pdf->cell(10,$alt, "Mai",1,0,"C",1);
       $pdf->cell(18,$alt,db_formatar( $work[$Iwork]["w_mai"] ,'f'),0,0,"R",0);
       $pdf->cell(18,$alt,db_formatar( $work[$Iwork]["w_as_mai"]  ,'f'),0,0,"R",0);
       $pdf->cell(18,$alt,db_formatar( $work[$Iwork]["w_he_mai"]  ,'f'),0,0,"R",0);
       $pdf->cell(10,$alt, "Jun",1,0,"C",1);
       $pdf->cell(18,$alt,db_formatar( $work[$Iwork]["w_jun"] ,'f'),0,0,"R",0);
       $pdf->cell(18,$alt,db_formatar( $work[$Iwork]["w_as_jun"]  ,'f'),0,0,"R",0);
       $pdf->cell(18,$alt,db_formatar( $work[$Iwork]["w_he_jun"]  ,'f'),0,1,"R",0);


       $pdf->cell(10,$alt, "Jul",1,0,"C",1);
       $pdf->cell(18,$alt,db_formatar( $work[$Iwork]["w_jul"] ,'f'),0,0,"R",0);
       $pdf->cell(18,$alt,db_formatar( $work[$Iwork]["w_as_jul"]  ,'f'),0,0,"R",0);
       $pdf->cell(18,$alt,db_formatar( $work[$Iwork]["w_he_jul"]  ,'f'),0,0,"R",0);
       $pdf->cell(10,$alt, "Ago",1,0,"C",1);
       $pdf->cell(18,$alt,db_formatar( $work[$Iwork]["w_ago"] ,'f'),0,0,"R",0);
       $pdf->cell(18,$alt,db_formatar( $work[$Iwork]["w_as_ago"]  ,'f'),0,0,"R",0);
       $pdf->cell(18,$alt,db_formatar( $work[$Iwork]["w_he_ago"]  ,'f'),0,0,"R",0);
       $pdf->cell(10,$alt, "Set",1,0,"C",1);
       $pdf->cell(18,$alt,db_formatar( $work[$Iwork]["w_set"] ,'f'),0,0,"R",0);
       $pdf->cell(18,$alt,db_formatar( $work[$Iwork]["w_as_set"]  ,'f'),0,0,"R",0);
       $pdf->cell(18,$alt,db_formatar( $work[$Iwork]["w_he_set"]  ,'f'),0,1,"R",0);


       $pdf->cell(10,$alt, "Out",1,0,"C",1);
       $pdf->cell(18,$alt,db_formatar( $work[$Iwork]["w_out"] ,'f'),0,0,"R",0);
       $pdf->cell(18,$alt,db_formatar( $work[$Iwork]["w_as_out"]  ,'f'),0,0,"R",0);
       $pdf->cell(18,$alt,db_formatar( $work[$Iwork]["w_he_out"]  ,'f'),0,0,"R",0);
       $pdf->cell(10,$alt, "Nov",1,0,"C",1);
       $pdf->cell(18,$alt,db_formatar( $work[$Iwork]["w_nov"] ,'f'),0,0,"R",0);
       $pdf->cell(18,$alt,db_formatar( $work[$Iwork]["w_as_nov"]  ,'f'),0,0,"R",0);
       $pdf->cell(18,$alt,db_formatar( $work[$Iwork]["w_he_nov"]  ,'f'),0,0,"R",0);
       $pdf->cell(10,$alt, "Dez",1,0,"C",1);
       $pdf->cell(18,$alt,db_formatar( $work[$Iwork]["w_dez"] ,'f'),0,0,"R",0);
       $pdf->cell(18,$alt,db_formatar( $work[$Iwork]["w_as_dez"]  ,'f'),0,0,"R",0);
       $pdf->cell(18,$alt,db_formatar( $work[$Iwork]["w_he_dez"]  ,'f'),0,1,"R",0);

       $pdf->cell(10,$alt, "13s",1,0,"C",1);
       $pdf->cell(18,$alt,db_formatar( $work[$Iwork]["w_sal13"] ,'f'),0,0,"R",0);
       $pdf->cell(18,$alt,db_formatar( $work[$Iwork]["w_adianta"]  ,'f'),0,0,"R",0);
       $pdf->cell(18,$alt,'',0,0,"R",0);
       $pdf->cell(10,$alt, "Totais",1,0,"C",1);
       $pdf->cell(18,$alt,db_formatar( $tot_val ,'f'),0,0,"R",0);
       $pdf->cell(18,$alt,db_formatar( $tot_as  ,'f'),0,0,"R",0);
       $pdf->cell(18,$alt,db_formatar( $tot_he  ,'f'),0,1,"R",0);

     $lin  = db_str($seq,6,0,"0");                                                                  // 001 a 006 - sequencial no arquivo;
     $lin .= str_pad($d08_cgc,14);                                                                  // 007 a 020 - cnpj da empresa;
     $lin .= db_str($prefixo,2,0,"0");                                                              // 021 a 022 - indicador de sub-arquivos;
     $lin .= "2";                                                                                   // 023 a 023 - indicador de tipo de registro;
     $lin .= str_pad($work[$Iwork]["w_pis"],11);                                                    // 024 a 034 - pis/pasep;
     $lin .= db_substr($work[$Iwork]["w_nome"],1,52);                                               // 035 a 086 - nome do empregado;
     $lin .= dat7_128($work[$Iwork]["w_nasc"]);                                                     // 087 a 094 - data de nascimento;
     $lin .= db_str($work[$Iwork]["w_nacion"],2,0,"0");                                             // 095 a 096 - nacionalidade;
     $lin .= str_pad($chegada,4);                                                                   // 097 a 100 - ano de chegada;
     $lin .= db_str($work[$Iwork]["w_instruc"],2,0,"0");                                            // 101 a 102 - grau de instruçao;
     $lin .= (db_empty($work[$Iwork]["w_cpf"])?"00000000000":substr($work[$Iwork]["w_cpf"],0,11));  // 103 a 113 - cpf;
     $ctps = db_str(db_val($work[$Iwork]["w_carteira"]),13,0,'0');
     $lin .= substr($ctps,0,8)   ;                                                                  // 114 a 121 - no. carteira de trabalho ;
     $lin .= substr($ctps,8,5)   ;                                                                  // 122 a 126 - serie carteira de trabalho ;
     $lin .= dat7_128($work[$Iwork]["w_admissao"]);                                                 // 127 a 134 - data de admissão;
     $lin .= db_str($work[$Iwork]["w_tipadm"],2,0,"0");                                             // 135 a 136 - tipo de admissao;
     $lin .= valor_128($work[$Iwork]["w_salario"]);                                                 // 137 a 145 - salario contratual;
     $lin .= tipo_128($work[$Iwork]["w_tiposal"]);                                                  // 146 a 146 - tipo de trabalho;
     $lin .= db_str($work[$Iwork]["w_horas"],2,0,"0");                                              // 147 a 148 - horas semanais;
     $lin .= db_str($work[$Iwork]["w_cbo"],6,0,"0");                                                // 149 a 154 - cbo;
     $lin .= db_str($work[$Iwork]["w_vinculo"],2,0,"0");                                            // 155 a 156 - vinculo;
     $lin .= db_str($work[$Iwork]["w_causa"],2,0,"0");                                              // 157 a 158 - causa da rescisao;
     if($work[$Iwork]["w_causa"] == 71 || $work[$Iwork]["w_causa"] == 78){
        $lin .= "0000";                                                                             // 159 a 162 - data de rescisao - ddmm;
     }else{
        $lin .= db_str(db_day($work[$Iwork]["w_desliga"]),2,0,"0").db_str(db_month($work[$Iwork]["w_desliga"]),2,0,"0"); // 159 a 162 - data de rescisao - ddmm;
     }
     $lin .= valor_128($work[$Iwork]["w_jan"]);                          // 163 a 171 - janeiro;
     $lin .= valor_128($work[$Iwork]["w_fev"]);                          // 172 a 180 - fevereiro;
     $lin .= valor_128($work[$Iwork]["w_mar"]);                          // 181 a 189 - março;
     $lin .= valor_128($work[$Iwork]["w_abr"]);                          // 190 a 198 - abril;
     $lin .= valor_128($work[$Iwork]["w_mai"]);                          // 199 a 207 - maio;
     $lin .= valor_128($work[$Iwork]["w_jun"]);                          // 208 a 216 - junho;
     $lin .= valor_128($work[$Iwork]["w_jul"]);                          // 217 a 225 - julho;
     $lin .= valor_128($work[$Iwork]["w_ago"]);                          // 226 a 234 - agosto;
     $lin .= valor_128($work[$Iwork]["w_set"]);                          // 235 a 243 - setembro;
     $lin .= valor_128($work[$Iwork]["w_out"]);                          // 244 a 252 - outrubro;
     $lin .= valor_128($work[$Iwork]["w_nov"]);                          // 253 a 261 - novembro;
     $lin .= valor_128($work[$Iwork]["w_dez"]);                          // 262 a 270 - dezembro;
     $lin .= valor_128($work[$Iwork]["w_adianta"]);                      // 271 a 279 - adiantamento do 13o salario;
     $lin .= db_str($work[$Iwork]["w_mesadi"],2,0,"0");                  // 280 a 281 - mes do adiantamento;

     /*
      * Verificamos se o caso se trata de registro de rescisão
      */
     if (!empty($work[$Iwork]["w_desliga"])) {
       $lin .= valor_128($work[$Iwork]["w_sal13"]-$work[$Iwork]["w_adianta"]); // 282 a 290 - valor 13 salario;
     } else{
     	 $lin .= valor_128($work[$Iwork]["w_sal13"]);                            // 282 a 290 - valor 13 salario;
     }

     $lin .= db_str($work[$Iwork]["w_mes13"],2,0,"0");                   // 291 a 292 - mes do 13 salario ;
     $lin .= trim(substr(db_str($work[$Iwork]["w_raca"],1,1),0,1));      // 293 a 293 - raca/cor;

     //Verifica se true na base de dados, informa (1 = sim) para deficiente físico, senão informa (2 = não)
     $iDeficiente      = ($work[$Iwork]["w_deficientefisico"] == 't' || $work[$Iwork]["w_deficientefisico"] === true) ? '1' : '2';
     //Verifica se o campo anterior foi setado com (2 = não) força para (0 = nenhum) o tipo de deficiencia
     $iTipoDeficiencia = ($iDeficiente == '2') ? '0' : $work[$Iwork]["w_tipodeficiencia"];

     $lin .= $iDeficiente;                                               // 294 a 294 - Tipo de defic fisica;
     $lin .= $iTipoDeficiencia;                                          // 295 a 295 - Indicador de defic fisica; 
     $lin .= "2";                                                        // 296 a 296 - alvara para trabal;
     $lin .= valor_128( $work[$Iwork]["w_avisop"]);                      // 297 a 305 - valor aviso previo indenizado;
     $lin .= $work[$Iwork]["w_sexo"];                                    // 306 a 306 - 1-masculino 2 femin;
     db_str($work[$Iwork]["w_nacion"],2,0,"0");
     $lin .= db_str($work[$Iwork]["w_mot_afa1"],2,0,"0");                // 307 a 308 - 1o. afastamento;
     $lin .= db_str($work[$Iwork]["w_ini_afa1"],4,0,"0");                // 309 a 312 - data do ininio 1o. afastamento - ddmm;
     $lin .= db_str($work[$Iwork]["w_fin_afa1"],4,0,"0");                // 313 a 316 - data do final  1o. afastamento - ddmm;
     $lin .= db_str($work[$Iwork]["w_mot_afa2"],2,0,"0");                // 317 a 318 - 2o. afastamento;
     $lin .= db_str($work[$Iwork]["w_ini_afa2"],4,0,"0");                // 319 a 322 - data do ininio 2o. afastamento - ddmm;
     $lin .= db_str($work[$Iwork]["w_fin_afa2"],4,0,"0");                // 323 a 326 - data do final  2o. afastamento - ddmm;
     $lin .= db_str($work[$Iwork]["w_mot_afa3"],2,0,"0");                // 327 a 328 - 3o. afastamento;
     $lin .= db_str($work[$Iwork]["w_ini_afa3"],4,0,"0");                // 329 a 332 - data do ininio 3o. afastamento - ddmm;
     $lin .= db_str($work[$Iwork]["w_fin_afa3"],4,0,"0");                // 333 a 336 - data do final  3o. afastamento - ddmm;
     $lin .= db_str($work[$Iwork]["w_dia_afa1"]
                   +$work[$Iwork]["w_dia_afa2"]
                   +$work[$Iwork]["w_dia_afa3"],3,0,"0");                // 337 a 339 - dias de afastamento;
     $lin .= valor_8( $work[$Iwork]["w_fer_res"]);                       // 340 a 347 - valor das ferias em rescisao;
     $lin .= "00000000";			                                           // 348 a 355 - valor do saldo de horas extras (banco de horas);
     $lin .= "00";			                                                 // 356 a 357 - meses de de horas extras (banco de horas);
     $lin .= "00000000";			                                           // 358 a 365 - acrescimo salarial - dissidio coletivo;
     $lin .= "00";			                                                 // 366 a 367 - meses com acrescimo salarial - dissidio coletivo;
     $lin .= "00000000";			                                           // 368 a 375 - valor de gratificacoes nao pagas durante o contrato;
     $lin .= "00";			                                                 // 376 a 377 - meses de gratificacoes nao pagas durante o contrato;
     $lin .= "00000000";			                                           // 378 a 385 - multa rescisoria (saldo do fgts);
     if($w_asso > 0){
       $lin .= db_str(trim($cnpj_asso),14,0,"0"); 			                 // 386 a 399 - cnpj do sindicato (associativo);
       $lin .= valor_8( $w_asso );	                                     // 400 a 407 - valor acumulado repassado ao sindicato (associativo) - 1a. ocorr;
     }else{
       $lin .= "00000000000000";                                          // 386 a 399 - cnpj do sindicato (associativo);
       $lin .= "00000000";                                                // 400 a 407 - valor acumulado repassado ao sindicato (associativo) - 1a. ocorr;
     }
     $lin .= "00000000000000";                                          // 408 a 421 - cnpj do sindicato (associativo) - 2a. ocorr ;
     $lin .= "00000000";			                                           // 422 a 429 - valor acumulado repassado ao sindicato (associativo) - 2a. ocorr;
     if ($w_sind > 0 ){
       $lin .= db_str(trim($cnpj_sind),14,0,"0"); 			                   // 430 a 443 - cnpj do sindicato (contribuiçao);
       $lin .= valor_8( $w_sind );                                         // 444 a 451 - valor acumulado repassado ao sindicato (contribuiçao sindiical);
     }else{
       $lin .= "00000000000000";                                           // 430 a 443 - cnpj do sindicato (contribuiçao);
       $lin .= "00000000";                                                 // 444 a 451 - valor acumulado repassado ao sindicato (contribuiçao sindiical);
     }
     $lin .= "00000000000000";		                                       // 452 a 465 - cnpj do sindicato (assistencial);
     $lin .= "00000000";			                                           // 466 a 473 - valor acumulado repassado ao sindicato (assistencial);
     $lin .= "00000000000000";		                                       // 474 a 487 - cnpj do sindicato (confederativa);
     $lin .= "00000000";			                                           // 488 a 495 - valor acumulado repassado ao sindicato (convederativa);
     $lin .= str_pad(str_replace('-','',str_replace('.','',$codmun)),7); // 496 a 502 - codigo do municipio;
     $lin .= (horas_128($work[$Iwork]["w_he_jan"]) > 999?999:horas_128($work[$Iwork]["w_he_jan"]));  // 503 a 505 - Horas Extras Trabalhadas - Janeiro
     $lin .= (horas_128($work[$Iwork]["w_he_fev"]) > 999?999:horas_128($work[$Iwork]["w_he_fev"]));  // 506 a 508 - Horas Extras Trabalhadas - Fevereiro
     $lin .= (horas_128($work[$Iwork]["w_he_mar"]) > 999?999:horas_128($work[$Iwork]["w_he_mar"]));  // 509 a 511 - Horas Extras Trabalhadas - Marco
     $lin .= (horas_128($work[$Iwork]["w_he_abr"]) > 999?999:horas_128($work[$Iwork]["w_he_abr"]));  // 512 a 514 - Horas Extras Trabalhadas - Abril
     $lin .= (horas_128($work[$Iwork]["w_he_mai"]) > 999?999:horas_128($work[$Iwork]["w_he_mai"]));  // 515 a 517 - Horas Extras Trabalhadas - Maio
     $lin .= (horas_128($work[$Iwork]["w_he_jun"]) > 999?999:horas_128($work[$Iwork]["w_he_jun"]));  // 518 a 520 - Horas Extras Trabalhadas - Junho
     $lin .= (horas_128($work[$Iwork]["w_he_jul"]) > 999?999:horas_128($work[$Iwork]["w_he_jul"]));  // 521 a 523 - Horas Extras Trabalhadas - Julho
     $lin .= (horas_128($work[$Iwork]["w_he_ago"]) > 999?999:horas_128($work[$Iwork]["w_he_ago"]));  // 524 a 526 - Horas Extras Trabalhadas - Agosto
     $lin .= (horas_128($work[$Iwork]["w_he_set"]) > 999?999:horas_128($work[$Iwork]["w_he_set"]));  // 527 a 529 - Horas Extras Trabalhadas - Setembro
     $lin .= (horas_128($work[$Iwork]["w_he_out"]) > 999?999:horas_128($work[$Iwork]["w_he_out"]));  // 530 a 532 - Horas Extras Trabalhadas - Outrubro
     $lin .= (horas_128($work[$Iwork]["w_he_nov"]) > 999?999:horas_128($work[$Iwork]["w_he_nov"]));  // 533 a 535 - Horas Extras Trabalhadas - Novembro
     $lin .= (horas_128($work[$Iwork]["w_he_dez"]) > 999?999:horas_128($work[$Iwork]["w_he_dez"]));  // 536 a 538 - Horas Extras Trabalhadas - Dezembro
     if($w_asso > 0){
       $lin .= "1"  ;                                                    // 539 a 539 - Indicador - Sidicalizado (1-sim 2-nao)
     }else{
       $lin .= "2"  ;                                                    // 539 a 539 - Indicador - Sidicalizado (1-sim 2-nao)
     }
     $lin .= bb_space(12);                                               // 540 a 551 - espacos;
     fputs($arquivo,$lin."\n");
  }

  $pdf->Output($nomepdf,false,true);

  $seq += 1;
  $lin  = db_str($seq,6,0,"0");                                          // 001 a 006 - sequencial do arquivo;
  $lin .= str_pad($d08_cgc,14);                                          // 007 a 020 - cnpj ;
  $lin .= db_str($prefixo,2,0,"0");                                      // 021 a 022 - digitos diferenciadores de sub-arquivos;
  $lin .= "9";                                                           // 023 a 023 - indicador do tipo de registro;
  $lin .= db_str($prefixo+1,6,0,"0");                                    // 024 a 029 - qtde de registros tipo 1 ;
  $lin .= db_str($vinculo,6,0,"0");                                      // 030 a 035 - qtde de registros tipo 2;
  $lin .= bb_space(516);                                                 // 036 a 527 - espaços;
  fputs($arquivo,$lin."\n");
  fclose($arquivo);
}

function valor_8($numero){
  if( $numero < 0){
    $numero = 0;
  }
   $valor = db_formatar(str_replace(',','',str_replace('.','',trim(db_formatar($numero,'f')))),'s','0',8,'e',2);
   $resp = $valor;
   return $resp;
}

function valor_9($numero){
  if( $numero < 0){
    $numero = 0;
  }
   $valor = db_formatar(str_replace(',','',str_replace('.','',trim(db_formatar($numero,'f')))),'s','0',9,'e',2);
   $resp = $valor;
   return $resp;
}

function valor_128($numero){
  if( $numero < 0){
    $numero = 0;
  }
   $valor = db_formatar(str_replace(',','',str_replace('.','',trim(db_formatar($numero,'f')))),'s','0',9,'e',2);
   $resp = $valor;
   return $resp;
}
function horas_128($numero){
   $valor = db_str(intval($numero),3,0,"0");
   $resp = $valor;
   return $resp;
}

function vinculo_128($cod){
   switch ($cod) {
    case 1:
      $codigo = "10";
      break;
    case 2:
      $codigo = "30";
      break;
    case 3:
      $codigo = "40";
      break;
    case 4:
      $codigo = "50";
      break;
    case 5:
      $codigo = "60";
      break;
    case 6:
      $codigo = "20";
      break;
    case 7:
      $codigo = "80";
      break;
    case 8:
      $codigo = "35";
      break;
   }
   return $codigo;
}

function causa_128($causa){
   switch ($causa) {
    case 0:
      $resp = "00";
      break;
    case 1:
      $resp = "10";
      break;
    case 2:
      $resp = "11";
      break;
    case 3:
      $resp = "20";
      break;
    case 4:
      $resp = "21";
      break;
    case 5:
      $resp = "30";
      break;
    case 6:
      $resp = "31";
      break;
    case 7:
      $resp = "72";
      break;
    case 8:
      $resp = "60";
      break;
    case 9:
      $resp = "90";
      break;
   }
  return $resp;
}

function data_128($data){
   $resp = db_str(db_day($data),2,0,"0").db_str(db_month($data),2,0,"0").db_substr(db_str(db_year($data),4),2,2);
   return $resp;
}

function dat7_128($data){
   $resp = db_str(db_day($data),2,0,"0").db_str(db_month($data),2,0,"0").db_str(db_year($data),4);
    return $resp;
}

function instruc_128($instruc){
   switch ($instruc){
    case 1:
      $resp = "10";
      break;
    case 2:
      $resp = "20";
      break;
    case 3:
      $resp = "25";
      break;
    case 4:
      $resp = "30";
      break;
    case 5:
      $resp = "35";
      break;
    case 6:
      $resp = "40";
      break;
    case 7:
      $resp = "45";
      break;
    case 8:
      $resp = "50";
      break;
    case 9:
      $resp = "55";
      break;
   }
   return $resp;
}

function tipo_128($tipo){
  global $resp;
   if($tipo == "M"){
      $resp = "1";
   }else if($tipo == "Q"){
      $resp = "2";
   }else if($tipo == "S"){
      $resp = "3";
   }else if($tipo == "D"){
      $resp = "4";
   }else if($tipo == "H"){
      $resp = "5";
   }else if($tipo == "E"){
      $resp = "7";
   }
   return $resp;
}

function soma_128($arq,$sigla){
   global $sal13, $soma,$subpes_atual,$work,$Iwork,$sel_B904,$sel_B008;

   $soma = 0;

   for($ix=0;$ix<count($arq);$ix++){
      if( db_val($arq[$ix][$sigla."rubric"]) > 0 || ( db_substr($arq[$ix][$sigla."rubric"],1,1) == "R" && db_val(db_substr($arq[$ix][$sigla."rubric"],2,3)) < 950 )){

        // B904 -->RAIS->13O PG FORA FLS 13O SAL
         if( db_at($arq[$ix][$sigla."rubric"],$sel_B904) > 0 ){
            if( $arq[$ix][$sigla."pd"] == 1){
               $sal13 += $arq[$ix][$sigla."valor"];
            }else{
               $sal13 -= $arq[$ix][$sigla."valor"];
            }

         }elseif(db_at($arq[$ix][$sigla."rubric"],$sel_B008) > 0){

           // B008 --> BASE RAIS

            $condicaoaux  = " and r22_rubric = ".db_sqlformat( $arq[$ix][$sigla."rubric"]);
            $condicaoaux .= " and r22_regist = ".db_sqlformat($arq[$ix][$sigla."regist"] );
            global $gerfadi;
            if( db_selectmax( "gerfadi", "select * from gerfadi ".bb_condicaosubpes( "r22_" ).$condicaoaux )){
               if( $arq[$ix][$sigla."pd"] == 1){
                  $soma += $gerfadi[0]["r22_valor"];
               }else{
                  $soma -= $gerfadi[0]["r22_valor"];
               }
            }
           if($sigla == 'r20_' ){
             // Rescisao - Rubricas de salario
              if ( $arq[$ix][$sigla."rubric"] < 2000 && db_substr($arq[$ix][$sigla."rubric"],1,1) != 'R' ) {
                   if( $arq[$ix][$sigla."pd"] == 1){
                      $soma += $arq[$ix][$sigla."valor"];
                   }else{
                      $soma -= $arq[$ix][$sigla."valor"];
                   }
              }
             // Rescisao - Rubricas de 13 salario
              if ( $arq[$ix][$sigla."rubric"] > 4000 &&  $arq[$ix][$sigla."rubric"] < 6000 ) {
                   if( $arq[$ix][$sigla."pd"] == 1){
                      $sal13 += $arq[$ix][$sigla."valor"];
                   }else{
                      $sal13 -= $arq[$ix][$sigla."valor"];
                   }
              }
           }else{
              if( $arq[$ix][$sigla."pd"] == 1){
                 $soma += $arq[$ix][$sigla."valor"];
              }else{
                 $soma -= $arq[$ix][$sigla."valor"];
              }
           }

         }
      }
  }
   return $soma;
}

function soma_128_ferias_resc($arq,$sigla){
   global $subpes_atual,$sel_B008;
   $feriasresc = 0;

   for($ix=0;$ix<count($arq);$ix++){

      // Rubricas de Ferias na rescisao e R931 -->1/3 de ferias

      if( (db_substr($arq[$ix][$sigla."rubric"],1,1) != "R" && db_val($arq[$ix][$sigla."rubric"]) > 2000 && db_val($arq[$ix][$sigla."rubric"]) < 4000 ) || $arq[$ix][$sigla."rubric"] == 'R931' ){
         // verif se rubrica tem base p/ rais;
         if( db_at($arq[$ix][$sigla."rubric"],$sel_B008) > 0){
            if( $arq[$ix][$sigla."pd"] == 1){
               $feriasresc += $arq[$ix][$sigla."valor"];
            }else{
               $feriasresc -= $arq[$ix][$sigla."valor"];
            }
         }
      }
   }
   return $feriasresc;
}

function soma_128_aviso_previo($arq,$sigla){
   global $subpes_atual,$sel_B008;
   $avisoprevio = 0;
   for($ix=0;$ix<count($arq);$ix++){
      // Rubricas de Rescisao na rescisao
      if( db_substr($arq[$ix][$sigla."rubric"],1,1) != "R" && db_val($arq[$ix][$sigla."rubric"]) > 6000 && db_val($arq[$ix][$sigla."rubric"]) < 8000 ){
         // verif se rubrica tem base p/ rais;
         if( db_at($arq[$ix][$sigla."rubric"],$sel_B008) > 0){
            if( $arq[$ix][$sigla."pd"] == 1){
               $avisoprevio += $arq[$ix][$sigla."valor"];
            }else{
               $avisoprevio -= $arq[$ix][$sigla."valor"];
            }
         }
      }
   }
   return $avisoprevio;
}


function soma_128_sind($arq,$sigla,$w_sind){
   $soma_sind = 0;
   for($ix=0;$ix<count($arq);$ix++){
      if( db_at($arq[$ix][$sigla."rubric"],$w_sind)> 0 ){
         $soma_sind += $arq[$ix][$sigla."valor"];
      }
   }
   return $soma_sind;
}

function soma_128_asso($arq,$sigla,$w_asso){
   $soma_asso = 0;
   for($ix=0;$ix<count($arq);$ix++){
      if( db_at($arq[$ix][$sigla."rubric"],$w_asso)> 0 ){
         $soma_asso += $arq[$ix][$sigla."valor"];
      }
   }
   return $soma_asso;
}
function soma_128_extras($arq,$sigla,$w_extras){
   $soma_extras = 0;
   for($ix=0;$ix<count($arq);$ix++){
      if( db_at($arq[$ix][$sigla."rubric"],$w_extras)> 0 ){
         $soma_extras += $arq[$ix][$sigla."quant"];
      }
   }
   return $soma_extras;
}

function cria_work_128(){
   global $arq_work;
   $campo = array();
   $tipo  = array();
   $tam   = array();
   $dec   = array();

   $campo[1]  = "w_matric";
   $campo[2]  = "w_prefixo";
   $campo[3]  = "w_nome";
   $campo[4]  = "w_pis";
   $campo[5]  = "w_salario";
   $campo[6]  = "w_tiposal";
   $campo[7]  = "w_horas";
   $campo[8]  = "w_carteira";
   $campo[9]  = "w_nasc";
   $campo[10] = "w_admissao";
   $campo[11] = "w_fgts";
   $campo[12] = "w_cpf";
   $campo[13] = "w_cbo";
   $campo[14] = "w_vinculo";
   $campo[15] = "w_instruc";
   $campo[16] = "w_nacion";
   $campo[17] = "w_chegada";
   $campo[18] = "w_tipadm";
   $campo[19] = "w_raca";
   $campo[20] = "w_desliga";
   $campo[21] = "w_causa";
   $campo[22] = "w_sexo";
   $campo[23] = "w_adianta";
   $campo[24] = "w_mesadi";
   $campo[25] = "w_sal13";
   $campo[26] = "w_mes13";
   $campo[27] = "w_mot_afa1";
   $campo[28] = "w_ini_afa1";
   $campo[29] = "w_fin_afa1";
   $campo[30] = "w_dia_afa1";
   $campo[31] = "w_mot_afa2";
   $campo[32] = "w_ini_afa2";
   $campo[33] = "w_fin_afa2";
   $campo[34] = "w_dia_afa2";
   $campo[35] = "w_mot_afa3";
   $campo[36] = "w_ini_afa3";
   $campo[37] = "w_fin_afa3";
   $campo[38] = "w_dia_afa3";
   $campo[39] = "w_jan";
   $campo[40] = "w_fev";
   $campo[41] = "w_mar";
   $campo[42] = "w_abr";
   $campo[43] = "w_mai";
   $campo[44] = "w_jun";
   $campo[45] = "w_jul";
   $campo[46] = "w_ago";
   $campo[47] = "w_set";
   $campo[48] = "w_out";
   $campo[49] = "w_nov";
   $campo[50] = "w_dez";
   $campo[51] = "w_avisop";
   $campo[52] = "w_fer_res";
   $campo[53] = "w_si_jan";
   $campo[54] = "w_si_fev";
   $campo[55] = "w_si_mar";
   $campo[56] = "w_si_abr";
   $campo[57] = "w_si_mai";
   $campo[58] = "w_si_jun";
   $campo[59] = "w_si_jul";
   $campo[60] = "w_si_ago";
   $campo[61] = "w_si_set";
   $campo[62] = "w_si_out";
   $campo[63] = "w_si_nov";
   $campo[64] = "w_si_dez";
   $campo[65] = "w_as_jan";
   $campo[66] = "w_as_fev";
   $campo[67] = "w_as_mar";
   $campo[68] = "w_as_abr";
   $campo[69] = "w_as_mai";
   $campo[70] = "w_as_jun";
   $campo[71] = "w_as_jul";
   $campo[72] = "w_as_ago";
   $campo[73] = "w_as_set";
   $campo[74] = "w_as_out";
   $campo[75] = "w_as_nov";
   $campo[76] = "w_as_dez";
   $campo[77] = "w_he_jan";
   $campo[78] = "w_he_fev";
   $campo[79] = "w_he_mar";
   $campo[80] = "w_he_abr";
   $campo[81] = "w_he_mai";
   $campo[82] = "w_he_jun";
   $campo[83] = "w_he_jul";
   $campo[84] = "w_he_ago";
   $campo[85] = "w_he_set";
   $campo[86] = "w_he_out";
   $campo[87] = "w_he_nov";
   $campo[88] = "w_he_dez";
   $campo[89] = "w_deficientefisico";
   $campo[90] = "w_tipodeficiencia";

   $tipo[1] = "n";
   $tipo[2] = "n";
   $tipo[3] = "c";
   $tipo[4] = "c";
   $tipo[5] = "n";
   $tipo[6] = "c";
   $tipo[7] = "n";
   $tipo[8] = "c";
   $tipo[9] = "d";
   $tipo[10] = "d";
   $tipo[11] = "d";
   $tipo[12] = "c";
   $tipo[13] = "n";
   $tipo[14] = "n";
   $tipo[15] = "n";
   $tipo[16] = "n";
   $tipo[17] = "n";
   $tipo[18] = "n";
   $tipo[19] = "c";
   $tipo[20] = "d";
   $tipo[21] = "n";
   $tipo[22] = "c";
   $tipo[23] = "n";
   $tipo[24] = "n";
   $tipo[25] = "n";
   $tipo[26] = "n";
   $tipo[27] = "c";
   $tipo[28] = "c";
   $tipo[29] = "c";
   $tipo[30] = "n";
   $tipo[31] = "c";
   $tipo[32] = "c";
   $tipo[33] = "c";
   $tipo[34] = "n";
   $tipo[35] = "c";
   $tipo[36] = "c";
   $tipo[37] = "c";
   $tipo[38] = "n";
   $tipo[39] = "n";
   $tipo[40] = "n";
   $tipo[41] = "n";
   $tipo[42] = "n";
   $tipo[43] = "n";
   $tipo[44] = "n";
   $tipo[45] = "n";
   $tipo[46] = "n";
   $tipo[47] = "n";
   $tipo[48] = "n";
   $tipo[49] = "n";
   $tipo[50] = "n";
   $tipo[51] = "n";
   $tipo[52] = "n";
   $tipo[53] = "n";
   $tipo[54] = "n";
   $tipo[55] = "n";
   $tipo[56] = "n";
   $tipo[57] = "n";
   $tipo[58] = "n";
   $tipo[59] = "n";
   $tipo[60] = "n";
   $tipo[61] = "n";
   $tipo[62] = "n";
   $tipo[63] = "n";
   $tipo[64] = "n";
   $tipo[65] = "n";
   $tipo[66] = "n";
   $tipo[67] = "n";
   $tipo[68] = "n";
   $tipo[69] = "n";
   $tipo[70] = "n";
   $tipo[71] = "n";
   $tipo[72] = "n";
   $tipo[73] = "n";
   $tipo[74] = "n";
   $tipo[75] = "n";
   $tipo[76] = "n";
   $tipo[77] = "n";
   $tipo[78] = "n";
   $tipo[79] = "n";
   $tipo[80] = "n";
   $tipo[81] = "n";
   $tipo[82] = "n";
   $tipo[83] = "n";
   $tipo[84] = "n";
   $tipo[85] = "n";
   $tipo[86] = "n";
   $tipo[87] = "n";
   $tipo[88] = "n";
   $tipo[89] = "l";
   $tipo[90] = "n";

   $tam[1] = 6;
   $tam[2] = 2;
   $tam[3] = 52;
   $tam[4] = 11;
   $tam[5] = 15;
   $tam[6] = 1;
   $tam[7] = 2;
   $tam[8] = 13;
   $tam[9] = 8;
   $tam[10] = 8;
   $tam[11] = 8;
   $tam[12] = 14;
   $tam[13] = 5;
   $tam[14] = 2;
   $tam[15] = 1;
   $tam[16] = 2;
   $tam[17] = 2;
   $tam[18] = 1;
   $tam[19] = 2;
   $tam[20] = 8;
   $tam[21] = 2;
   $tam[22] = 1;
   $tam[23] = 15;
   $tam[24] = 2;
   $tam[25] = 15;
   $tam[26] = 2;
   $tam[27] = 2;
   $tam[28] = 4;
   $tam[29] = 4;
   $tam[30] = 3;
   $tam[31] = 2;
   $tam[32] = 4;
   $tam[33] = 4;
   $tam[34] = 3;
   $tam[35] = 2;
   $tam[36] = 4;
   $tam[37] = 4;
   $tam[38] = 3;
   $tam[39] = 15;
   $tam[40] = 15;
   $tam[41] = 15;
   $tam[42] = 15;
   $tam[43] = 15;
   $tam[44] = 15;
   $tam[45] = 15;
   $tam[46] = 15;
   $tam[47] = 15;
   $tam[48] = 15;
   $tam[49] = 15;
   $tam[50] = 15;
   $tam[51] = 15;
   $tam[52] = 15;
   $tam[53] = 15;
   $tam[54] = 15;
   $tam[55] = 15;
   $tam[56] = 15;
   $tam[57] = 15;
   $tam[58] = 15;
   $tam[59] = 15;
   $tam[50] = 15;
   $tam[61] = 15;
   $tam[62] = 15;
   $tam[63] = 15;
   $tam[64] = 15;
   $tam[65] = 15;
   $tam[66] = 15;
   $tam[67] = 15;
   $tam[68] = 15;
   $tam[69] = 15;
   $tam[70] = 15;
   $tam[71] = 15;
   $tam[72] = 15;
   $tam[73] = 15;
   $tam[74] = 15;
   $tam[75] = 15;
   $tam[76] = 15;
   $tam[77] = 15;
   $tam[78] = 15;
   $tam[79] = 15;
   $tam[80] = 15;
   $tam[81] = 15;
   $tam[82] = 15;
   $tam[83] = 15;
   $tam[84] = 15;
   $tam[85] = 15;
   $tam[86] = 15;
   $tam[87] = 15;
   $tam[88] = 15;
   $tam[89] = 1;
   $tam[90] = 15;

   $dec[1] = 0;
   $dec[2] = 0;
   $dec[3] = 0;
   $dec[4] = 0;
   $dec[5] = 2;
   $dec[6] = 0;
   $dec[7] = 0;
   $dec[8] = 0;
   $dec[9] = 0;
   $dec[10] = 0;
   $dec[11] = 0;
   $dec[12] = 0;
   $dec[13] = 0;
   $dec[14] = 0;
   $dec[15] = 0;
   $dec[16] = 0;
   $dec[17] = 0;
   $dec[18] = 0;
   $dec[19] = 0;
   $dec[20] = 0;
   $dec[21] = 0;
   $dec[22] = 0;
   $dec[23] = 2;
   $dec[24] = 0;
   $dec[25] = 2;
   $dec[26] = 0;
   $dec[27] = 0;
   $dec[28] = 0;
   $dec[29] = 0;
   $dec[30] = 0;
   $dec[31] = 0;
   $dec[32] = 0;
   $dec[33] = 0;
   $dec[34] = 0;
   $dec[35] = 0;
   $dec[36] = 0;
   $dec[37] = 0;
   $dec[38] = 0;
   $dec[39] = 2;
   $dec[40] = 2;
   $dec[41] = 2;
   $dec[42] = 2;
   $dec[43] = 2;
   $dec[44] = 2;
   $dec[45] = 2;
   $dec[46] = 2;
   $dec[47] = 2;
   $dec[48] = 2;
   $dec[49] = 2;
   $dec[50] = 2;
   $dec[51] = 2;
   $dec[52] = 2;
   $dec[53] = 2;
   $dec[54] = 2;
   $dec[55] = 2;
   $dec[56] = 2;
   $dec[57] = 2;
   $dec[58] = 2;
   $dec[59] = 2;
   $dec[60] = 2;
   $dec[61] = 2;
   $dec[62] = 2;
   $dec[63] = 2;
   $dec[64] = 2;
   $dec[65] = 2;
   $dec[66] = 2;
   $dec[67] = 2;
   $dec[68] = 2;
   $dec[69] = 2;
   $dec[70] = 2;
   $dec[71] = 2;
   $dec[72] = 2;
   $dec[73] = 2;
   $dec[74] = 2;
   $dec[75] = 2;
   $dec[76] = 2;
   $dec[77] = 2;
   $dec[78] = 2;
   $dec[79] = 2;
   $dec[80] = 2;
   $dec[81] = 2;
   $dec[82] = 2;
   $dec[83] = 2;
   $dec[84] = 2;
   $dec[85] = 2;
   $dec[86] = 2;
   $dec[87] = 2;
   $dec[88] = 2;
   $dec[89] = 0;
   $dec[90] = 2;

   $arq_work = "arq1";
   db_criatemp( $arq_work, $campo, $tipo, $tam, $dec);
   db_query("create index work_matric on arq1(w_matric); ");
}

function ajusresc(){

 global $matricula,$work,$arq_work;

 $matriz7 = array();
 $matriz8 = array();
 $matriz7[1] = "w_mes13";
 $matriz5 = array();
 $matriz6 = array();

 for($Iwork=0;$Iwork<count($work);$Iwork++){

   $matricula = $work[$Iwork]["w_matric"];
   $condicaoalt = " where w_matric = ".db_sqlformat($matricula);
   if( db_month($work[$Iwork]["w_desliga"]) != 0 && db_month($work[$Iwork]["w_desliga"]) != 12){

      $matricula = $work[$Iwork]["w_matric"];
      $condicaoalt = " where w_matric = ".db_sqlformat( $matricula );
      $nrmes = db_month($work[$Iwork]["w_desliga"]);
      $valor = 0;
      for($ind=$nrmes;$ind<=12;$ind++){
        $mes = db_substr("janfevmarabrmaijunjulagosetoutnovdez",($ind*3)-2,3);
        $valor += $work[$Iwork]["w_".$mes];
      }
      for($ind=$nrmes;$ind<=12;$ind++){
          $mes = db_substr("janfevmarabrmaijunjulagosetoutnovdez",($ind*3)-2,3);
          if( $ind == $nrmes){
             $soma = $valor;
          }else{
             $soma = 0;
          }
          $matriz5[1] = "w_".$mes;
          $matriz6[1] = $soma;
          db_update( $arq_work, $matriz5,$matriz6, $condicaoalt );

      }
      if( !db_empty($work[$Iwork]["w_sal13"])){
         $matriz8[1] = $nrmes;
         db_update( $arq_work, $matriz7, $matriz8, $condicaoalt );
      }
   }else{
      if( db_empty($work[$Iwork]["w_sal13"]) && !db_empty($work[$Iwork]["w_mes13"]) ){
         $matriz8[1] = 0;
         db_update( $arq_work, $matriz7, $matriz8, $condicaoalt );
      }
   }
 }
}
?>
