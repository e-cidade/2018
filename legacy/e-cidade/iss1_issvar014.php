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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("classes/db_issvar_classe.php"));
include(modification("classes/db_numpref_classe.php"));
include(modification("classes/db_issbase_classe.php"));
include(modification("classes/db_issvarnotas_classe.php"));
include(modification("classes/db_cgm_classe.php"));
include(modification("classes/db_cadvenc_classe.php"));
include(modification("classes/db_arrecad_classe.php"));
include(modification("classes/db_arreinscr_classe.php"));
include(modification("classes/db_parissqn_classe.php"));
include(modification("classes/db_db_confplan_classe.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("dbforms/db_classesgenericas.php"));

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clissvar      = new cl_issvar;
$clissvarnotas = new cl_issvarnotas;
$clissbase     = new cl_issbase;
$clcgm         = new cl_cgm;
$clcadvenc     = new cl_cadvenc;
$clparissqn    = new cl_parissqn;
$cldb_confplan = new cl_db_confplan;
$clarrecad     = new cl_arrecad;
$clarreinscr   = new cl_arreinscr;
$clnumpref     = new cl_numpref;
$cliframe_alterar_excluir_html = new cl_iframe_alterar_excluir_html;
$db_botao = true;
$db_opcao=1;
///////////////////////INCLUSÃO///////////////////////////////////////////////////////////////////////////////////////
if(isset($incluir)){
  $sqlerro=false;
  db_inicio_transacao();


  $vt=$HTTP_POST_VARS;

  $q05_codigo="";
  $numpre=$clnumpref->sql_numpre();
  $clissvar->q05_numpre =$numpre;
  $clissvar->q05_numpar =$q05_mes;
  $clissvar->q05_valor  =$q05_valor;
  $clissvar->q05_ano    =$q05_ano;
  $clissvar->q05_mes    =$q05_mes;
  $clissvar->q05_histor =$q05_histor;
  $clissvar->q05_aliq   =$q05_aliq;;
  $clissvar->q05_bruto  =$q05_bruto;
  $clissvar->q05_vlrinf ="null";
  $clissvar->incluir_issvar_complementar ($vt,@$q02_inscr);
	if($clissvar->erro_status=="0"){
		$sqlerro = true;
		$erro_msg = $clissvar->erro_msg;

	}else{
		$erro_msg =" Inclusão efetuada com sucesso!.";

	}

 if(!$sqlerro){

   $q05_valor = 0;
   $q05_bruto = 0;
 }

 db_fim_transacao($sqlerro);

}else if (isset($substituir)){
//----------------------------Se já existe um lancamento na competencia informada substituir!!---------------------
 $sqlerro=false;
 db_inicio_transacao();
  if($q05_histor==""){
    $vt=$HTTP_POST_VARS;
    reset($vt);
    $ta=sizeof($vt);
    $vir="";
    $q05_histor="REFERENTE NOTAS FISCAIS No.:";
    for($i=0; $i<$ta; $i++){
      $chave=key($vt);
      if(substr($chave,0,6)=="linha_"){
        $sqlerro=false;
	$matri= split("#",$vt[$chave]);
        $q05_histor.=$vir.$matri[0];
	$vir=",";
      }
     $proximo=next($vt);
    }
  }
  $clissvar->q05_codigo=$q05_codigo;
  $clissvar->q05_valor=0;
  $clissvar->q05_histor=$q05_histor;
  $clissvar->q05_aliq=$q05_aliq;;
  $clissvar->q05_bruto=$q05_bruto;
  $clissvar->q05_vlrinf=$q05_valor;
  $clissvar->alterar($q05_codigo);
  $erro_msg=$clissvar->erro_msg;
  $codigo=$clissvar->q05_codigo;
  if($clissvar->erro_status==0){
    $sqlerro=true;
  }
  $codigo=$clissvar->q05_codigo;
  if(!$sqlerro){
    $vt=$HTTP_POST_VARS;
    reset($vt);
    $ta=sizeof($vt);
    for($i=0; $i<$ta; $i++){
      $chave=key($vt);
      if(substr($chave,0,6)=="linha_"){
        $sqlerro=false;
	      $matri = split("#",$vt[$chave]);
        $result55 = $clissvarnotas->sql_record($clissvarnotas->sql_query_file($codigo,"","max(q06_seq) +1 as seq"));
        db_fieldsmemory($result55,0);
        $q06_seq = $seq == ""?"1":$seq;
        $clissvarnotas->q06_codigo=$codigo;
        $clissvarnotas->q06_seq=$q06_seq;
        $clissvarnotas->q06_nota=$matri[0];
        $clissvarnotas->q06_valor=$matri[1];
        $clissvarnotas->incluir($codigo,$q06_seq);

        if($clissvarnotas->erro_status==0){
         $sqlerro=true;
        }
      }
      $proximo=next($vt);
    }
  }
  if(!$sqlerro){

    $q05_valor = 0;
    $q05_bruto = 0;
    unset($q05_histor);
    unset($q06_nota);
    unset($q06_valor);
  }
 db_fim_transacao($sqlerro);
}

/*toda vez que for setada a inscrição, é verificado se existe aliquota para a inscrição*/
if(isset($q02_inscr) && $q02_inscr>0){
   $result88=$clissbase->sql_record($clissbase->sql_query_file($q02_inscr,"q02_dtbaix"));
   db_fieldsmemory($result88,0);
   if($q02_dtbaix!=""){
      if(isset($z01_numcgmx)){
       $baixa="invalida";
       $entrar="Entrar";
       $z01_numcgm=$z01_numcgmx;
       $varios=true;
      }else{
        db_redireciona("iss1_issvar001.php?z01_nomeinscr=$z01_nome&q02_inscr=$q02_inscr&baixa=invalida");
        exit;
      }
   }

   $oDataAtual = new DBDate(date("d/m/Y", db_getsession("DB_datausu")));

   $sSqlAliquota = $clissbase->sql_query_atividade_aliquota( $q02_inscr,
                                                             "q81_valexe",
                                                             null,
                                                             "cadcalc.q85_var is true"
                                                             . " and (tabativ.q07_datafi is null or tabativ.q07_datafi >= '{$oDataAtual->getDate()}')"
                                                             . " and tabativ.q07_databx is null " );
   $result33=$clissbase->sql_record($sSqlAliquota);
   $numrows33=$clissbase->numrows;
   if($numrows33<1){
      if(isset($z01_numcgmx)){
       $aliquota="invalida";
       $entrar="Entrar";
       $z01_numcgm=$z01_numcgmx;
       $varios=true;
      }else{
        db_redireciona("iss1_issvar001.php?z01_nomeinscr=$z01_nome&q02_inscr=$q02_inscr&aliquota=invalida");
        exit;
      }
   }
}
if(empty($varios) && isset($entrar) && isset($q02_inscr) && $q02_inscr>0){
    $result01=$clissbase->sql_record($clissbase->empresa_query($q02_inscr,"z01_nome"));
    $numrows01=$clissbase->numrows;
    if($numrows01>0){
        db_fieldsmemory($result01,0);
        if($q02_dtbaix!=""){
          db_redireciona("iss1_issvar001.php?z01_nomeinscr=$z01_nome&q02_inscr=$q02_inscr&baixa=invalida");
          exit;
        }
    }else{
        if(isset($z01_numcgmx)){
           db_redireciona("iss1_issvar014.php?inscr=$q02_inscr&z01_numcgm=$z01_numcgmx&entrar=denovo");
        }else{
           db_redireciona("iss1_issvar001.php?z01_nomeinscr=$z01_nomeinscr&registro=invalido&q02_inscr=$q02_inscr");
        }
    }
}else if(isset($entrar) && isset($z01_numcgm) && $z01_numcgm>0){
    $result03 = $clissbase->sql_record($clissbase->sql_query_file("","issbase.q02_inscr","q02_inscr","q02_numcgm=$z01_numcgm"));
    if($clissbase->numrows>0){
      $result02=$clcgm->sql_record($clcgm->sql_query_file($z01_numcgm,"z01_nome"));
      db_fieldsmemory($result02,0);
       $varios=true;
    }else{
      $result02=$clcgm->sql_record($clcgm->sql_query_file($z01_numcgm,"z01_nome"));
      if($clcgm->numrows>0){
        db_fieldsmemory($result02,0);
      }else{
        db_redireciona("iss1_issvar001.php?z01_numcgm=$z01_numcgm&numcgm=invalida");
      }
    }
}
if(isset($mesant) && $mesant != "" && $db_opcao == "1"){//isso eh pra vir o ultimo mes incluido
    $q05_mes = $mesant;
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
<?
if(isset($varios) && $varios==true){//quando tiver várias inscrições para um cgm
$clrotulo= new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("z01_numcgm");
$clrotulo->label("q02_inscr");
db_fieldsmemory($result03,0);
?>
<form name="form1" method="post" action="iss1_issvar014.php">
 <table>
  <tr>
    <td>
      <?=$Lz01_numcgm?>
    </td>
    <td>
    <?
      $z01_numcgmx=$z01_numcgm;
      db_input('z01_numcgm',6,$Iz01_numcgm,true,'text',3,"","z01_numcgmx")
    ?>
    </td>
  </tr>
  <tr>
    <td>
      <?=$Lz01_nome?>
    </td>
    <td>
    <?
      db_input('z01_nome',40,$Iz01_nome,true,'text',3,"")
    ?>
    </td>
  </tr>
  <tr>
    <td>
       <?=$Lq02_inscr?>
    </td>
    <td>
    <?
    for($e=0; $e<$clissbase->numrows; $e++){
      db_fieldsmemory($result03,$e);
      $inscrs[$q02_inscr]=$q02_inscr;
    }
    if(isset($inscr)){
      global $q02_inscr;
      $q02_inscr=$inscr;
    }
    db_select("q02_inscr",$inscrs,true,$db_opcao,"","","","","");
    ?>
       </td>
     </tr>
     <tr>
       <td  colspan="2" align="center">
          <input type="submit" name="entrar" value="Entrar">
          <input type="button" name="voltar" value="Voltar" onclick="js_voltar();">
       </td>
     </tr>
   </table>
 </form>
 <script>
   function js_voltar(){
     location.href="iss1_issvar001.php";
   }
   <?
   if(isset($entrar) && $entrar=="denovo"){
     echo  "alert('Nenhum registro de issqn variável encontrado para esta inscrição!')";
   }
   if(isset($baixa) && $aliquota=="invalida"){
    echo "alert(\"Inscrição baixada.\");";
   }
   if(isset($aliquota) && $aliquota=="invalida"){
    echo "alert(\"Inscrição sem atividade em funcionamento cadastrado.\");";
   }
   ?>
 </script>
<?
}else{
	include(modification("forms/db_frmissvar.php"));
	?>
    </center>
    </td>
  </tr>
<?
}
?>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($incluir)||isset($substituir)){
  if($clissvar->erro_status=="0"&&$sqlerro==true){
    $clissvar->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clissvar->erro_campo!=""){
      echo "<script> document.form1.".$clissvar->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clissvar->erro_campo.".focus();</script>";
    };
  }else{
    db_msgbox($erro_msg);
    };
}
?>