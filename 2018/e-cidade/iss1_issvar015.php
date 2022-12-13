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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$clissvar                      = new cl_issvar;
$clissvarnotas                 = new cl_issvarnotas;
$clissbase                     = new cl_issbase;
$clcgm                         = new cl_cgm;
$clcadvenc                     = new cl_cadvenc;
$clparissqn                    = new cl_parissqn;
$clarrecad                     = new cl_arrecad;
$clarrecant                    = new cl_arrecant;
$clarrenumcgm                  = new cl_arrenumcgm;
$clarreinscr                   = new cl_arreinscr;
$clnumpref                     = new cl_numpref;
$cldb_confplan                 = new cl_db_confplan;
$cliframe_alterar_excluir_html = new cl_iframe_alterar_excluir_html;

$db_botao = true;
$db_opcao = 2;

//////////////////////////////////////////////////INICIA ALTERAÇÃO////////////////////////////////////////////////
if ( isset( $alterar ) ) {

  $sqlerro = false;

  db_inicio_transacao();

  if ( $q05_histor == "" ) {

     $vt = $HTTP_POST_VARS;
     reset       ( $vt );
     $ta         = sizeof( $vt );
     $vir        = "";
     $q05_histor = "REFERENTE NOTAS FISCAIS No.:";

     for ( $i = 0; $i < $ta; $i++ ) {

       $chave = key( $vt );

       if ( substr( $chave, 0, 6) == "linha_" ) {

         $sqlerro     = false;
 	       $matri       = split("#",$vt[$chave]);
         $q05_histor .= $vir.$matri[0];
 	       $vir         = ",";
       }

       $proximo = next($vt);
     }
   }

   $result10 = $clissvar->sql_record( $clissvar->sql_query_file( $q05_codigo, "q05_numpre" ) );

   db_fieldsmemory( $result10, 0 );

   $clissvar->q05_numpre = $q05_numpre;
   $clissvar->q05_numpar = $q05_mes;
   $clissvar->q05_valor  = $q05_valor;
   $clissvar->q05_ano    = $q05_ano;
   $clissvar->q05_mes    = $q05_mes;
   $clissvar->q05_histor = $q05_histor;
   $clissvar->q05_aliq   = $q05_aliq;;
   $clissvar->q05_bruto  = $q05_bruto;
   $clissvar->q05_vlrinf = "null";
   $clissvar->alterar($q05_codigo);

   $codigo = $q05_codigo;
   if ( $clissvar->erro_status == 0 ) {
     $sqlerro = true;
   }

   $codigo = $clissvar->q05_codigo;
   if ( !$sqlerro ) {

     $result88 = $clissvarnotas->sql_record( $clissvarnotas->sql_query_file( $q05_codigo, "", "q06_codigo", "1=1 limit 1" ) );
     if ( $clissvarnotas->numrows > 0 ) {

       $clissvarnotas->q06_codigo = $q05_codigo;
       $clissvarnotas->excluir( $q05_codigo );
       if ( $clissvarnotas->erro_status == 0 ) {
         $sqlerro = true;
       }
     }
   }

   if ( !$sqlerro ) {

     $vt = $HTTP_POST_VARS;
     reset($vt);
     $ta = sizeof($vt);
     for ( $i = 0; $i < $ta; $i++ ) {

       $chave = key($vt);
       if ( substr( $chave, 0, 6) == "linha_" ) {

         $sqlerro  = false;
 	       $matri    = split("#",$vt[$chave]);
         $result55 = $clissvarnotas->sql_record($clissvarnotas->sql_query_file($codigo,"","max(q06_seq) +1 as seq"));
         db_fieldsmemory($result55,0);

         $q06_seq = $seq == "" ? "1" : $seq;

         $clissvarnotas->q06_codigo = $codigo;
         $clissvarnotas->q06_seq    = $q06_seq;
         $clissvarnotas->q06_nota   = $matri[0];
         $clissvarnotas->q06_valor  = $matri[1];
         $clissvarnotas->incluir($codigo,$q06_seq);

         if ( $clissvarnotas->erro_status == 0 ) {
           $sqlerro = true;
         }
       }

      @$proximo = next($vt);
     }
   }

   if ( !$sqlerro ) {

       $result25 = $clarrecant->sql_record( $clarrecant->sql_query_file( "", "k00_numpre", "", "k00_numpre = {$q05_numpre}" ) );
       if ( $clarrecant->numrows > 0 ) {

         //se entrar aqui, sera porque ja já foi pago e se já tiver sido naum poderia ter aparecido este codigo de issvar quando foi pesquisado para alterar
         die("Erro no sistema. Procure o técnico responsável.");
       } else {

         $clarrecad->excluir_arrecad( $q05_numpre );
         if ( $clarrecad->erro_status == "0" ) {

           $erro    = $clarrecad->erro_msg;
           $sqlerro = true;
         }
      }

      if ( empty( $z01_numcgm ) ) {

        $result03 = $clissbase->sql_record( $clissbase->sql_query_file( $q02_inscr, "q02_numcgm as z01_numcgm" ) );
        db_fieldsmemory($result03,0);
      }

      $result66 = $clparissqn->sql_record( $clparissqn->sql_query_file() );
      db_fieldsmemory($result66,0);
      $clarrecad->k00_tipo   = $q60_tipo;
      $clarrecad->k00_receit = $q60_receit;

      $result77 = $clcadvenc->sql_record( $clcadvenc->sql_query_file( $q60_codvencvar, $q05_mes, "q82_venc,q82_hist" ) );
      db_fieldsmemory($result77,0);

      $clarrecad->k00_hist = $q82_hist;

      $venc_arrecad = $q82_venc;

      if ( $q05_ano == db_getsession( "DB_anousu" ) ) {
        $clarrecad->k00_dtvenc="$q82_venc";
      } else {

 		    $res = $cldb_confplan->sql_record( $cldb_confplan->sql_query() );
 		    if ( $cldb_confplan->numrows > 0 ) {
 		      db_fieldsmemory($res,0);
 		    } else {

 		     db_msgbox("Tabela db_confplan vazia!");
 		     db_redireciona("iss1_issvar014.php");
 		     exit;
 		    }

 		    $qmes = $q05_mes;
 		    $qano = $q05_ano;
 		    $qmes += 1;
 		    if ( $qmes > 12 ) {

 		      $qmes  = 1;
 		      $qano += 1;
 		    }

 		    $qmes = str_pad($qmes,2,"0",STR_PAD_LEFT);
 		    $venc_arrecad = $qano."-".$qmes."-".$w10_dia;
 		    $clarrecad->k00_dtvenc = "{$venc_arrecad}";
      }

      $clarrecad->k00_numcgm = $z01_numcgm;
      $clarrecad->k00_dtoper = "{$venc_arrecad}";
      $clarrecad->k00_valor  = $q05_valor;
      $clarrecad->k00_numpre = $q05_numpre;
      $clarrecad->k00_numtot = 1;
      $clarrecad->k00_numpar = $q05_mes;
      $clarrecad->k00_numdig = '0';
      $clarrecad->k00_tipojm = '0';
      $clarrecad->incluir();

      if ( $clarrecad->erro_status == 0 ) {
        $sqlerro = true;
      }
   }

   if ( !$sqlerro ) {

     $q05_valor = "";
     $q05_bruto = "";
     unset($q05_histor);
     unset($z01_numcgm);
   }

   db_fim_transacao($sqlerro);
}
/////////////FINALIZA ALTERAÇÃO/////////////////////////////////////////////////////////////////////////////////////////

if(empty($entrar) && isset($q05_codigo) && $q05_codigo>0){//quando vier o código do ISSVAR
  $result99=$clissvar->sql_record($clissvar->sql_query_file($q05_codigo));
  db_fieldsmemory($result99,0);
  $sql_codigo=$clissvarnotas->sql_query_file($q05_codigo,"","q06_nota,q06_valor","q06_seq");
}else{
  if(isset($entrar) && isset($z01_numcgm) && $z01_numcgm>0){//quando for digitado numcgm
      $result03 = $clissbase->sql_record($clissbase->sql_query_file("","issbase.q02_inscr","q02_inscr","q02_numcgm=$z01_numcgm"));
      if($clissbase->numrows>0){//se o cgm tiver inscrições
          $result02=$clcgm->sql_record($clcgm->sql_query_file($z01_numcgm,"z01_nome,z01_munic"));
          db_fieldsmemory($result02,0);
          $varios=true;
      }else{
         $result65=$clarrenumcgm->sql_record($clarrenumcgm->sql_query_file($z01_numcgm,"","k00_numpre"));
         $numrows65=$clarrenumcgm->numrows;
      }
      if(@$z01_nome==""){
        $result01=$clcgm->sql_record($clcgm->sql_query($z01_numcgm,"z01_nome"));
        db_fieldsmemory($result01,0);
      }
  }else if(isset($entrar) && isset($q02_inscr) && $q02_inscr>0){//quando for digiado inscrição
      $result65=$clarreinscr->sql_record($clarreinscr->sql_query("",$q02_inscr,"k00_numpre,z01_nome"));
      $numrows65=$clarreinscr->numrows;
  }
  ///////////////////////////////////////////////////////////////////////////////////////////
  if(empty($varios)){//$varios é setado quando há varias inscrições para um cgm
    if($numrows65<1){//se não encontrar nenhum registro no arreinscr, volta para a pagina inicial
        if(isset($z01_numcgmx)){
           db_redireciona("iss1_issvar015.php?inscr=$q02_inscr&z01_numcgm=$z01_numcgmx&entrar=denovo");
        }else{
           db_redireciona("iss1_issvar002.php?z01_nomeinscr=$z01_nomeinscr&registro=invalido&q02_inscr=$q02_inscr");
        }
    }else{
      $codigos=array();
      $codigos_pagos=array();
      for($i=0;$i<$numrows65;$i++){//numero de registro encontrados no arreinscr
        db_fieldsmemory($result65,$i);
        $result77=$clissvar->sql_record($clissvar->sql_query("","q05_codigo","","q05_numpre=$k00_numpre"));
        if($clissvar->numrows>0){//se encontrar registro no ISSVAR
            db_fieldsmemory($result77,0);
            $clarrecant->sql_record($clarrecant->sql_query_file("","k00_numpre","","k00_numpre=$k00_numpre"));
            if($clarrecant->numrows>0){//se já tiver sido pago entra aqui
              $codigos_pagos[$q05_codigo]=$q05_codigo;
            }else{//os numpres que ainda não tiveram nenhuma parcela paga
              $clarrecad->sql_record($clarrecad->sql_query_file_instit("","arrecad.k00_numpre","","arrecad.k00_numpre=$k00_numpre and k00_instit = ".db_getsession('DB_instit') ));
              if($clarrecad->numrows>0){
                $codigos[$q05_codigo]=$q05_codigo;
      	      }
            }
        }
      }
      if(sizeof($codigos)==0 && sizeof($codigos_pagos)==0){//se  nehum registro for encontrado no ISSVAR, volta para a pagina inicial
          if(isset($z01_numcgmx)){
             db_redireciona("iss1_issvar015.php?inscr=$q02_inscr&z01_numcgm=$z01_numcgmx&entrar=denovo");
          }else{
         	   db_redireciona("iss1_issvar002.php?z01_nomeinscr=$z01_nomeinscr&registro=invalido&q02_inscr=$q02_inscr");
          }
      }else if(sizeof($codigos)>0){//se tiver sido encontrado algum registro no ISSVAR que ainda não foi pago
          $varios_codigos=$codigos;
          if(sizeof($codigos)==1){
  	    reset($codigos);
  	    $chave=key($codigos);
            $unico_codigo=$codigos[$chave];
          }
      }else if(sizeof($codigos_pagos)>0){//se tiver sido encontrado algum registro no ISSVAR que já foi pago
          db_redireciona("iss1_issvar002.php?z01_nomeinscr=$z01_nomeinscr&registro=pago&q02_inscr=$q02_inscr");
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
<form name="form1" method="post" action="iss1_issvar015.php">
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
     location.href="iss1_issvar002.php";
   }
   <?
   if(isset($entrar) && $entrar=="denovo"){
     echo  "alert('Nenhum registro de issqn variável encontrado para esta inscrição!')";
   }
   ?>
 </script>
<?
}else if(isset($varios_codigos)){//quando tiver vários códigos de ISSVAR para uma inscrição
  $clrotulo= new rotulocampo;
  $clrotulo->label("z01_nome");
  $clrotulo->label("z01_numcgm");
  $clrotulo->label("q02_inscr");
  $clrotulo->label("q05_codigo");
?>
<form name="form1" method="post" action="iss1_issvar015.php">
 <table>
<?
    if(isset($unico_codigo)){
      if(isset($entrar) && isset($z01_numcgm) && $z01_numcgm>0){//quando for por numcgm
         db_input('z01_numcgm',6,$Iz01_numcgm,true,'hidden',1,"");
      }else if(isset($entrar) && isset($q02_inscr) && $q02_inscr>0){//quando for por  inscrição
         db_input('q02_inscr',40,$Iq02_inscr,true,'hidden',1,"");
      }
      db_input('z01_nome',40,$Iz01_nome,true,'hidden',1,"");
      $q05_codigo=$unico_codigo;
    }
    db_input('q05_codigo',6,0,true,'hidden',1);
?>
<?
  if(empty($unico_codigo)){
?>
  <tr>
<?
if(isset($entrar) && isset($z01_numcgm) && $z01_numcgm>0){//quando for por numcgm
?>
    <td nowrap title="<?=@$Tz01_numcgm?>" width="25%">
      <?=$Lz01_numcgm?>
    </td>
    <td nowrap title="<?=@$Tz01_numcgm?>">
<?
  db_input('z01_numcgm',6,$Iz01_numcgm,true,'text',3,"")
?>

    </td>
<?
}else if(isset($entrar) && isset($q02_inscr) && $q02_inscr>0){//quando for por  inscrição
?>
    <td nowrap title="<?=@$Tq02_inscr?>">
      <?=$Lq02_inscr?>
    </td>
    <td nowrap title="<?=@$Tq02_inscr?>">
<?
  db_input('q02_inscr',6,$Iq02_inscr,true,'text',3,"")
?>

    </td>
<?
}
?>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tz01_nome?>">
      <?=$Lz01_nome?>
    </td>
    <td nowrap title="<?=@$Tz01_nome?>">
<?
  db_input('z01_nome',40,$Iz01_nome,true,'text',3,"")
?>
    </td>
  </tr>
  <tr><td colspan="2" align="center"> <input type="button" name="voltar" value="Voltar" onclick="js_voltar();"></td></tr>
<?
    reset($varios_codigos);
    $codis="";
    $vir="";
    for($e=0; $e<sizeof($varios_codigos); $e++){
      $chave=key($varios_codigos);
      $codis.=$vir.$varios_codigos[$chave];
      next($varios_codigos);
      $vir=",";
    }
    $sql=$clissvar->sql_query_file("","*","","q05_codigo in ($codis)");
    db_lovrot($sql,1000,"()","","js_alterar|q05_codigo");

?>
     <?
     }
     ?>
   </table>
 </form>
 <script>
   function js_alterar(codigo){
     document.form1.q05_codigo.value=codigo;
     document.form1.submit();
   }
   function js_voltar(){
     location.href="iss1_issvar002.php";
   }
   <?
    if(isset($unico_codigo)){//quando tiver um unico codigo de issvar, ele já entra direto
      echo "document.form1.submit();";
    }
   ?>
 </script>
<?
}else{
   include(modification("forms/db_frmissvar.php"));
}
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
if(isset($alterar)){
  if($clissvar->erro_status=="0"){
    $clissvar->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clissvar->erro_campo!=""){
      echo "<script> document.form1.".$clissvar->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clissvar->erro_campo.".focus();</script>";
    };
  }else{
    $clissvar->erro(true,false);
    };
}
?>