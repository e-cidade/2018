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
include("dbforms/db_funcoes.php");
include("libs/db_libcontabilidade.php");

include("classes/db_empresto_classe.php");
include("classes/db_conlancam_classe.php");
include("classes/db_empempenho_classe.php");

$clempresto     = new cl_empresto;
$clconlancam    = new cl_conlancam;
$clempempenho   = new cl_empempenho;

db_postmemory($HTTP_POST_VARS);

if(isset($processaroutros)){

  $db_msgerro = "Processo Concluído com Sucesso.";
  db_inicio_transacao();
  $sqlerro=false;
  $data = $e91_anousu."-01-01";

  $result = $clempempenho->sql_record($clempempenho->sql_query(null,"e60_numemp,e60_vlremp,e60_vlranu,e60_vlrliq,e60_vlrpag","e60_numemp"," e60_emiss < '$data'"));
  if( $clempempenho->numrows > 0 ){
  	for($i=0;$i<$clempempenho->numrows;$i++){
  	  db_fieldsmemory($result,$i);
  	  if(round(round(round($e60_vlremp,2)-round($e60_vlranu,2),2)-round($e60_vlrpag,2),2) > 0 ){
        $res = $clempresto->sql_record($clempresto->sql_query_file($e91_anousu,$e60_numemp));
        if( $clempempenho->numrows == 0 ){
          echo "Erro no empenho $e60_numemp <br>";
          break;	
        }  	
      }
  	}
  }
  db_fim_transacao($sqlerro);

}

if(isset($processar)){

  $db_msgerro = "Processo Concluído com Sucesso.";
  db_inicio_transacao();
  $sqlerro=false;
  $data = $e91_anousu."-01-01";
  
  $result = $clempresto->sql_record($clempresto->sql_query_file($e91_anousu));
  //db_criatabela($result);
  if( $clempresto->numrows > 0 ){
  	for($i=0;$i<$clempresto->numrows;$i++){
  	  db_fieldsmemory($result,$i);
  	  $res = $clconlancam->sql_record($clconlancam->sql_query_trans(null,"e60_vlremp,e60_vlranu,e60_vlrliq,e60_vlrpag, c71_coddoc,c53_tipo,c70_valor,c75_data","c75_numemp"," c75_numemp = $e91_numemp and c75_data > '$data'"));
  	  if($clconlancam->numrows > 0 ){
  	  	//db_criatabela($res);
        $vlremp = $e91_vlremp;
        $vlranu = $e91_vlranu;
        $vlrliq = $e91_vlrliq;
        $vlrpag = $e91_vlrpag;  
        for($x=0;$x<$clconlancam->numrows;$x++){
          db_fieldsmemory($res,$x);
          if($c53_tipo == "10"){
          	$vlremp += $c70_valor;
          }else if($c53_tipo == "11"){
          	$vlranu += $c70_valor;      
          }else if($c53_tipo == "20"){
          	$vlrliq += $c70_valor;      
          }else if($c53_tipo == "21"){
          	$vlrliq -= $c70_valor;      
          }else if($c53_tipo == "30"){
          	$vlrpag += $c70_valor;      
          }else if($c53_tipo == "31"){
          	$vlrpag -= $c70_valor;      
          }
        }
        if(round($vlremp,2) != round($e60_vlremp,2)){
          echo "Empenho $e91_numemp -> Valor do empenho $e60_vlremp -> $vlremp <br>";
          $sqlempenho     = "update empempenho  set e60_vlremp = $vlremp where e60_numemp = $e91_numemp";
          $sqlempelemento = "update empelemento set e64_vlremp = $vlremp where e64_numemp = $e91_numemp";
          $res = @pg_query($sqlempenho);
          if($res == false){
            $sqlerro = true;
            $db_msgerro = "Erro ao atualizar o empenho (Numemp : $e91_numemp)";
            break;
          }
          $res = @pg_query($sqlempelemento);
          if($res == false){
            $sqlerro = true;
            $db_msgerro = "Erro ao atualizar o empelemento (Numemp : $e91_numemp)";
            break;
          }
        }
        if(round($vlranu,2) != round($e60_vlranu,2)){
          echo "Empenho $e91_numemp -> Valor do anulado $e60_vlranu -> $vlranu <br>";
          $sqlempenho     = "update empempenho  set e60_vlranu = $vlranu where e60_numemp = $e91_numemp";
          $sqlempelemento = "update empelemento set e64_vlranu = $vlranu where e64_numemp = $e91_numemp"; 
          $res = @pg_query($sqlempenho);
          if($res == false){
            $sqlerro = true;
            $db_msgerro = "Erro ao atualizar o empenho (Numemp : $e91_numemp)";
            break;
          }
          $res = @pg_query($sqlempelemento);
          if($res == false){
            $sqlerro = true;
            $db_msgerro = "Erro ao atualizar o empelemento (Numemp : $e91_numemp)";
            break;
          }
        }
        if(round($vlrliq,2) != round($e60_vlrliq,2)){
          echo "Empenho $e91_numemp -> Valor do liquida $e60_vlrliq -> $vlrliq <br>";
          $sqlempenho     = "update empempenho  set e60_vlrliq = $vlrliq where e60_numemp = $e91_numemp";
          $sqlempelemento = "update empelemento set e64_vlrliq = $vlrliq where e64_numemp = $e91_numemp"; 
          $res = @pg_query($sqlempenho);
          if($res == false){
            $sqlerro = true;
            $db_msgerro = "Erro ao atualizar o empenho (Numemp : $e91_numemp)";
            break;
          }
          $res = @pg_query($sqlempelemento);
          if($res == false){
            $sqlerro = true;
            $db_msgerro = "Erro ao atualizar o empelemento (Numemp : $e91_numemp)";
            break;
          }
        }
        if(round($vlrpag,2) != round($e60_vlrpag,2)){
          echo "Empenho $e91_numemp -> Valor do pago    $e60_vlrpag -> $vlrpag <br>";
          $sqlempenho     = "update empempenho  set e60_vlrpag = $vlrpag where e60_numemp = $e91_numemp";
          $sqlempelemento = "update empelemento set e64_vlrpag = $vlrpag where e64_numemp = $e91_numemp"; 
          $res = @pg_query($sqlempenho);
          if($res == false){
            $sqlerro = true;
            $db_msgerro = "Erro ao atualizar o empenho (Numemp : $e91_numemp)";
            break;
          }
          $res = @pg_query($sqlempelemento);
          if($res == false){
            $sqlerro = true;
            $db_msgerro = "Erro ao atualizar o empelemento (Numemp : $e91_numemp)";
            break;
          }
        }
  	  }
  	}
  }
  $sqlerro = true;
  db_fim_transacao($sqlerro);
}


$clrotulo = new rotulocampo;
$clrotulo->label("e91_anousu");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#CCCCCC">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
    <form name="form1" method="post" action="">
    <center>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height='20' colspan='2'><strong>Processa a verificação dos empenhos restos a pagar desde a implantação</strong>
    </td> 
  </tr> 
  <tr> 
    <td align='right'> <strong><?=$Le91_anousu?></strong></td > 
    <td align='left'> 
    <?
    global $e91_anousu;

    $result = $clempresto->sql_record($clempresto->sql_query_file(null,null,"distinct e91_anousu","e91_anousu limit 1"));
    if($clempresto->numrows > 0){
      db_fieldsmemory($result,0);
    }
    db_input("e91_anousu",5,$Se91_anousu,true,'',3);
    ?>
    </td>
  </tr>
  <tr> 
    <td align='center' colspan='2'>
        <input name="processar" type="submit" id="db_opcao" value="Processar" onclick='return js_verfica();'>
    </td> 
  </tr>
  
  <tr> 
    <td height='20' colspan='2'><strong>Processa a verificação dos empenhos abertos que não estão nos restos</strong>
    </td> 
  </tr> 
  <tr> 
    <td align='center' colspan='2'>
        <input name="processaroutros" type="submit" id="db_opcao" value="Processar" onclick='return js_verfica();'>
    </td> 
  </tr> 
  
   
</table>
    </form>
    </center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($processar)){
  db_msgbox($db_msgerro);
}
?>