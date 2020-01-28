<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
include("classes/db_folha_classe.php");
include("classes/db_selecao_classe.php");
include("classes/db_gerfsal_classe.php");
include("classes/db_gerfadi_classe.php");
include("classes/db_gerffer_classe.php");
include("classes/db_gerfres_classe.php");
include("classes/db_gerfs13_classe.php");
include("classes/db_gerfcom_classe.php");
include("classes/db_gerffx_classe.php");
include("dbforms/db_funcoes.php");
include("libs/db_sql.php");
db_postmemory($HTTP_POST_VARS); 
$clfolha = new cl_folha;
$clselecao = new cl_selecao;
$clgerfsal = new cl_gerfsal;
$clgerfadi = new cl_gerfadi;
$clgerffer = new cl_gerffer;
$clgerfres = new cl_gerfres;
$clgerfs13 = new cl_gerfs13;
$clgerfcom = new cl_gerfcom;
$clgerffx  = new cl_gerffx;
$clsubsql  = new cl_gera_sql_folha;
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
  db_inicio_transacao();
  $contaREG = "Nenhum registro encontrado";
  $sqlerro = false;
  $sqlsal = "";
  $sqladi = "";
  $sqlfer = "";
  $sqlres = "";
  $sql13o = "";
  $sqlcom = "";
  $sqlffx = "";
  $DBwher = "";
  $DBands = "";
  // $DBgerrs = " and rh05_seqpes is null ";
  $DBgerrs = "  ";
  if(isset($folhaselecion) && trim($folhaselecion) != ""){
    if(trim($anofolha) == ""){
      $anofolha = db_anofolha();
    }
    if(trim($mesfolha) == ""){
      $mesfolha = db_mesfolha();
    }
    $faixa_lotac = str_replace("\'","'",$faixa_lotac);
    $faixa_orgao = str_replace("\'","'",$faixa_orgao);
    $DBwher.= $DBands." #s#_anousu = ".$anofolha;
    $DBwher.= " and #s#_mesusu = ".$mesfolha;
    $DBwher.= " and #s#_instit = ".db_getsession("DB_instit");
    $DBwher.= " and #s#_pd != 3 ";

    $campos = "#s#_regist as regist, #s#_lotac as lotac, case when #s#_pd = 1 then #s#_valor else 0 end as proven, case when #s#_pd = 2 then #s#_valor else 0 end as descon, #s#_anousu as anousu, #s#_mesusu as mesusu";

    $arr_folha = split(",",$folhaselecion);
    for($i=0; $i<count($arr_folha); $i++){
      if($arr_folha[$i] == 0){
        $DBwher1 = str_replace("#s#","r14",$DBwher);
        $campos1 = str_replace("#s#","r14",$campos);
        $sqlsal  = $clgerfsal->sql_query_file(null,null,null,null,$campos1,"",$DBwher1);
      }else if($arr_folha[$i] == 1){
        $DBwher2 = str_replace("#s#","r22",$DBwher);
        $campos2 = str_replace("#s#","r22",$campos);
        $sqladi  = $clgerfadi->sql_query_file(null,null,null,null,$campos2,"",$DBwher2);
      }else if($arr_folha[$i] == 2){
        $DBwher3 = str_replace("#s#","r31",$DBwher);
        $campos3 = str_replace("#s#","r31",$campos);
        $sqlfer  = $clgerffer->sql_query_file(null,null,null,null,null,$campos3,"",$DBwher3);
      }else if($arr_folha[$i] == 3){
        $DBgerrs = "";
        $DBwher4 = str_replace("#s#","r20",$DBwher);
        $campos4 = str_replace("#s#","r20",$campos);
        $sqlres  = $clgerfres->sql_query_file(null,null,null,null,null,$campos4,"",$DBwher4);
      }else if($arr_folha[$i] == 4){
        $DBwher5 = str_replace("#s#","r35",$DBwher);
        $campos5 = str_replace("#s#","r35",$campos);
        $sql13o  = $clgerfs13->sql_query_file(null,null,null,null,$campos5,"",$DBwher5);
      }else if($arr_folha[$i] == 5){
        $DBwher6 = str_replace("#s#","r48",$DBwher);
        $campos6 = str_replace("#s#","r48",$campos);
        if(isset($complementares) && $complementares != 0){
          $DBwher6.= " and r48_semest = ".$complementares;
        }
        $sqlcom  = $clgerfcom->sql_query_file(null,null,null,null,$campos6,"",$DBwher6);
      }else if($arr_folha[$i] == 6){
        $DBwher7 = str_replace("#s#","r53",$DBwher);
        $campos7 = str_replace("#s#","r53",$campos);
        $sqlffx  = $clgerffx->sql_query_file(null,null,null,null,$campos7,"",$DBwher7);
      }
    }

    $valorunion = "";
    $sqlgrunion = "";
    if($sqlsal != ""){
      $sqlgrunion.= $valorunion.$sqlsal;
      $valorunion = " union all ";
    }
    if($sqladi != ""){
      $sqlgrunion.= $valorunion.$sqladi;
      $valorunion = " union all ";
    }
    if($sqlfer != ""){
      $sqlgrunion.= $valorunion.$sqlfer;
      $valorunion = " union all ";
    }
    if($sqlres != ""){
      $sqlgrunion.= $valorunion.$sqlres;
      $valorunion = " union all ";
    }
    if($sql13o != ""){
      $sqlgrunion.= $valorunion.$sql13o;
      $valorunion = " union all ";
    }
    if($sqlcom != ""){
      $sqlgrunion.= $valorunion.$sqlcom;
      $valorunion = " union all ";
    }
    if($sqlffx = ""){
      $sqlgrunion.= $valorunion.$sqlfx;
      $valorunion = " union all ";
    }

    if(trim($selecao) != ""){
      $result_selecao = $clselecao->sql_record($clselecao->sql_query_file($selecao,db_getsession('DB_instit'),"r44_where as wher"));
      if($clselecao->numrows > 0){
        db_fieldsmemory($result_selecao, 0);
        $DBwher = " where 1=1 and ".$wher;
      }
    }else{
      $DBwher = " where 1=1 ";
    }
    if(isset($lotaci) && trim($lotaci) != "" && isset($lotacf) && trim($lotacf) != ""){
      // Se for por intervalos e vier lota��o inicial e final
      $DBwher .= " and r70_estrut between '".$lotaci."' and '".$lotacf."' ";
    }else if(isset($lotaci) && trim($lotaci) != ""){
      // Se for por intervalos e vier somente lota��o inicial
      $DBwher .= " and r70_estrut >= '".$lotaci."' ";
    }else if(isset($lotacf) && trim($lotacf) != ""){
      // Se for por intervalos e vier somente lota��o final
      $DBwher .= " and r70_estrut <= '".$lotacf."' ";
    }else if(isset($faixa_lotac) && $faixa_lotac != ''){
      $DBwher.= " and r70_estrut in ($faixa_lotac) ";
    }
    
    if(isset($orgaoi) && trim($orgaoi) != "" && isset($orgaof) && trim($orgaof) != ""){

      // Se for por intervalos e vier �rg�o inicial e final
      $DBwher .= " and o40_orgao between ".$orgaoi." and ".$orgaof;
    }else if(isset($orgaoi) && trim($orgaoi) != ""){
      // Se for por intervalos e vier somente �rg�o inicial
      $DBwher .= " and o40_orgao >= ".$orgaoi;
    }else if(isset($orgaof) && trim($orgaof) != ""){
      // Se for por intervalos e vier somente �rg�o final
      $DBwher .= " and o40_orgao <= ".$orgaof;
    }else if(isset($faixa_orgao) && trim($faixa_orgao) != ""){
      // Se for por selecionados
      $DBwher .= " and o40_orgao in (".$faixa_orgao.") ";
    }
    
    if(isset($registini) && trim($registini) != "" && isset($registfim) && trim($registfim) != ""){

      // Se for por intervalos e vier �rg�o inicial e final
      $DBwher .= " and rh02_regist between ".$registini." and ".$registfim;
    }else if(isset($registini) && trim($registini) != ""){
      // Se for por intervalos e vier somente �rg�o inicial
      $DBwher .= " and rh02_regist >= ".$registini;
    }else if(isset($registfim) && trim($registfim) != ""){
      // Se for por intervalos e vier somente �rg�o final
      $DBwher .= " and rh02_regist <= ".$registfim;
    } else if (isset($faixa_matricula) && $faixa_matricula != "") {
      $DBwher .= " and rh02_regist in (".$faixa_matricula.") ";
    }
    //echo "<br><br> where $DBwher";exit;
    if(isset($pagtosaldo) && $pagtosaldo == "t"){
      $pagarliq = 999999999.99;
      $pagarperc= 100;
      $liquidar = " ( ( sum(proven) - sum(descon) ) - ( ( (sum(proven) - sum(descon) ) / 100 ) * ( $percpago - 100 ) ) )";
      $liquidar = " trunc( cast( ( ( (sum(proven) - sum(descon) ) / 100 ) * {$percpago} ) as numeric) ,2) ";
      $liquidar = " round( (
                             ( sum(proven) - sum(descon) ) -
                             ( ( (sum(proven) - sum(descon) ) / 100 ) * {$percpago} )
                           ),2 
                         )  ";
      }else if(trim($pagarliq) == ""){
      $liquidar = " (sum(proven) - sum(descon)) ";
    }else{
      $liquidar = " (case when (sum(proven) - sum(descon)) > ".$pagarliq." then ".$pagarliq." else (sum(proven) - sum(descon)) end) ";
    }

    //echo "LIQUIDAR --".$liquidar;exit;

    if($pagarperc == 0 || trim($pagarperc) == ""){
      $case = " round((".$liquidar." - ".$liquido1."),2) as liquido, ";
      $havi = " round((".$liquidar." - ".$liquido1."),2) ";
    }else{
      $pagarperc = ($pagarperc / 100);
      $percpago  = ($percpago / 100);
      if(isset($pagtosaldo) && $pagtosaldo == "t"){
        // $case = " round((".$liquidar." - (".$liquidar." * (".$percpago."))),2) as liquido, ";
        // $havi = " round((".$liquidar." - (".$liquidar." * (".$percpago."))),2) ";

        $case = " round(".$liquidar.",2) as liquido, ";
        $havi = " round(".$liquidar.",2) ";
      }else{
        $case = " round((".$liquidar." * (".$pagarperc.")),2) as liquido, ";
        $havi = " round((".$liquidar." * (".$pagarperc.")),2) ";
      }
    }


    $clsubsql->inner_atv = false;
    $clsubsql->inner_pad = false;
    $clsubsql->inner_fun = false;
    $clsubsql->inner_ban = false;
    $clsubsql->inner_res = false;
    $clsubsql->usar_atv  = true;
    $clsubsql->usar_pad  = true;
    $clsubsql->usar_lot  = true;
    $clsubsql->usar_fun  = true;
    $clsubsql->usar_ban  = true;
    $clsubsql->usar_cgm  = true;
    $clsubsql->usar_res  = true;
    $clsubsql->subsql    = $sqlgrunion;
    $clsubsql->subsqlano = "anousu";
    $clsubsql->subsqlmes = "mesusu";
    $clsubsql->subsqlreg = "regist";
    $grsubsql = $clsubsql->gerador_sql(
        "",null,null,null,null,
        "
        regist,
        z01_nome,
        z01_numcgm,
        rh30_regime as rh02_codreg,
        rh02_lota as lotac,
        rh30_vinculo,
        rh03_padrao,
        substr(db_fxxx(regist,".$anofolha.",".$mesfolha.",".db_getsession("DB_instit")."),111,11) as f010,
        rh37_descr,
        1 as r38_situac,
        rh02_tbprev,
        ".$case."
        sum(proven) as proven,
        sum(descon) as descon,
        rh44_codban,
        lpad(trim(to_char(to_number(rh44_agencia,'9999'),'9999'))::varchar(4)||rh44_dvagencia,5,'0') as rh44_agencia,
        rh44_conta||rh44_dvconta as rh44_conta,
        rh02_fpagto,
        r70_estrut
          "
          );
    $grsubsql .= $DBwher." 
      group by regist,
            rh02_lota,
            z01_nome,
            z01_numcgm,
            r70_estrut,
            rh30_regime,
            rh30_vinculo,
            rh03_padrao,
            rh37_descr,
            rh02_tbprev,
            rh44_codban,
            trim(to_char(to_number(rh44_agencia,'9999'),'9999'))::varchar(4)||rh44_dvagencia,
            rh44_conta||rh44_dvconta,
            rh02_fpagto,
            rh05_seqpes
              having     ".$havi." > 0
              and (sum(proven) - sum(descon)) between ".$liquido1." and ".$liquido2."
              ".$DBgerrs."
              order by regist "
              ;

//    echo "<BR> $grsubsql"; exit;

    $result_grsubsql = $clfolha->sql_record($grsubsql);
    $numrows_folha = $clfolha->numrows;

    if($numrows_folha > 0){
      unset($contaREG);
      $contaREG = 0;
      $clfolha->excluir(null,null,"1=1 and r38_instit = ".db_getsession("DB_instit"));
      if($clfolha->erro_status == 0){
        $erro_msg = $clfolha->erro_msg;
        $sqlerro = true;
      }
      if($sqlerro == false){
        for($i=0; $i<$numrows_folha; $i++){
          db_fieldsmemory($result_grsubsql, $i);
          if ($rh02_fpagto  == 4) {

            $rh44_agencia = "";
            $rh44_conta   = "";
          } else  if ($rh02_fpagto != 3) {
            
            $rh44_codban  = "";
            $rh44_agencia = "";
            $rh44_conta   = "";
          }

          $clfolha->r38_nome   = db_translate($z01_nome);
          $clfolha->r38_numcgm = $z01_numcgm;
          $clfolha->r38_regime = $rh02_codreg;
          $clfolha->r38_lotac  = $lotac;
          $clfolha->r38_vincul = $rh30_vinculo;
          $clfolha->r38_padrao = $rh03_padrao;
          $clfolha->r38_salari = $f010;
          $clfolha->r38_funcao = $rh37_descr;
          $clfolha->r38_situac = "1";
          $clfolha->r38_previd = $rh02_tbprev;
          $clfolha->r38_liq    = "$liquido";
          $clfolha->r38_prov   = "$proven";
          $clfolha->r38_desc   = "$descon";
          $clfolha->r38_proc   = date("Y-m-d",db_getsession("DB_datausu"));
          $clfolha->r38_banco  = $rh44_codban;
          $clfolha->r38_agenc  = $rh44_agencia;
          $clfolha->r38_conta  = $rh44_conta;
          $clfolha->r38_instit = db_getsession("DB_instit");
          $clfolha->incluir($regist,db_getsession("DB_instit"));
          $contaREG ++;
          //					echo "<br>  $contaREG   regist --> $regist    instit --> ".db_getsession("DB_instit");
          if($clfolha->erro_status == 0){
            $erro_msg = $clfolha->erro_msg;
            $sqlerro = true;
            break;
          }
        }
      }
      $contaREG = $contaREG." registros inclu�dos.";
      }
  }
  db_fim_transacao($sqlerro);
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
include("forms/db_frmfolha.php");
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
//exit;
if(isset($incluir)){
  if($sqlerro == true){
    $clfolha->erro(true,false);
  }else{
    db_msgbox($contaREG);
  };
  echo "<script>location.href = 'pes1_folha001.php';</script>";
};
?>
<script>
js_tabulacaoforms("form1","selecao",true,1,"selecao",true);
</script>