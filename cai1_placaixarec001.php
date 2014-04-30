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
require("libs/db_utils.php");
require("libs/db_libdicionario.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_placaixarec_classe.php");
include("classes/db_placaixa_classe.php");
include("classes/db_tabrec_classe.php");
include("classes/db_saltes_classe.php");
require("classes/planilhaCaixa.model.php");
include("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clplacaixarec = new cl_placaixarec;
$clplacaixa = new cl_placaixa;
$cltabrec = new cl_tabrec;
$clsaltes = new cl_saltes;

$db_opcao = 22;
$db_botao = false;

if(isset($alterar) || isset($excluir) || isset($incluir) || isset($importar) ){
  $sqlerro = false;
  /*
     $clplacaixarec->k81_seqpla = $k81_seqpla;
     $clplacaixarec->k81_codpla = $k81_codpla;
     $clplacaixarec->k81_conta = $k81_conta;
     $clplacaixarec->k81_receita = $k81_receita;
     $clplacaixarec->k81_valor = $k81_valor;
   */
}
// importa uma planilha ja digitada para a planilha atual
if(isset($importar)){

  $resultcai = $clplacaixarec->sql_record($clplacaixarec->sql_query(null,'*',null," k81_codpla = $importar "));
  if($resultcai!=false && $clplacaixarec->numrows>0){
    $planilha = $k81_codpla;
    $numrows = $clplacaixarec->numrows;
    db_inicio_transacao();
    for($impx=0;$impx<$numrows;$impx++){

      db_fieldsmemory($resultcai,$impx);
      //$clplacaixarec->k81_seqpla = ;
      $clplacaixarec->k81_codpla         = $planilha;
      $clplacaixarec->k81_conta          = $k81_conta;
      $clplacaixarec->k81_receita        = $k81_receita;
      $clplacaixarec->k81_valor          = $k81_valor;
      $clplacaixarec->k81_obs            = pg_escape_string($k81_obs);
      $clplacaixarec->k81_codigo         = $k81_codigo;
      $clplacaixarec->k81_datareceb      = $k81_datareceb;
      $clplacaixarec->k81_numcgm         = $k81_numcgm;
      $clplacaixarec->k81_operbanco      = $k81_operbanco;
      $clplacaixarec->k81_origem         = $k81_origem;
      $clplacaixarec->k81_concarpeculiar = $c58_sequencial;

      $clplacaixarec->incluir(0);
      if($clplacaixarec->erro_status==0){
        $sqlerro=true;
      }
      if ($k81_origem == 2) {

        $oDaoInscr = db_utils::getdao("placaixarecinscr");
        $rsinscr   = $oDaoInscr->sql_record($oDaoInscr->sql_query_file(null,"*",null,"k76_placaixarec={$k81_seqpla}"));
        if ($oDaoInscr->numrows > 0) {

          $oInscrRec =  db_utils::fieldsMemory($rsinscr, 0);
          $oDaoInscr->k76_inscr       = $oInscrRec->k76_inscr;
          $oDaoInscr->k76_placaixarec = $clplacaixarec->k81_seqpla;
          $oDaoInscr->incluir(null);
        }
      } else if ($k81_origem == 3) {

        $oDaoMatric = db_utils::getdao("placaixarecmatric");
        $rsMatric   = $oDaoMatric->sql_record($oDaoMatric->sql_query_file(null,"*",null,"k77_placaixarec={$k81_seqpla}"));
        if ($oDaoMatric->numrows > 0) {

          $oMatricRec =  db_utils::fieldsMemory($rsMatric, 0);
          $oDaoMatric->k77_matric = $oMatricRec->k77_matric;
          $oDaoMatric->k77_placaixarec = $clplacaixarec->k81_seqpla;
          $oDaoMatric->incluir(null);

        }
      }

      if($clplacaixarec->erro_status==0){
        $sqlerro=true;
      }

    }
    db_fim_transacao($sqlerro);
    if($sqlerro==true){
      db_msgbox($clplacaixarec->erro_msg);
    }

    unset($k81_seqpla);
    $k81_codpla = $planilha;
    unset($k81_conta);
    unset($k81_receita);
    unset($k81_valor);
    unset($k81_obs);
    unset($k81_codigo);
    unset($k81_datareceb);
    unset($k81_operbanco);

  }
  $k13_descr = "";
  $k02_drecei= "";
  $c61_codigo= "";
}

if(isset($incluir)){
  if($sqlerro==false){    
    db_inicio_transacao();
    $oPlanilha = new planilhaCaixa($k81_codpla);
    $oReceita  = db_utils::postMemory($_POST);
    if (!$oPlanilha->adicionarReceita($oReceita)) {

      $sqlerro  = true;
      $erro_msg = $oPlanilha->sErroMsg;

    } else {
      $erro_msg = " Inclusão Efetuada com sucesso";
    }
    db_fim_transacao($sqlerro);
    if($sqlerro==false){
      $k81_valor = "";
      $k81_operbanco = "";
    }
    // zera variaveis da tela
    unset($k81_receita);
    // unset($c61_codigo);
    unset($k02_drecei);    

  }
  $k81_seqpla = "";
}else if(isset($alterar)){

  if($sqlerro==false){
    db_inicio_transacao();
    $oPlanilha = new planilhaCaixa($k81_codpla);
    $oReceita  = db_utils::postMemory($_POST);
    if (!$oPlanilha->alterarReceita($k81_seqpla, $oReceita)) {

      $sqlerro  = true;
      $erro_msg = $oPlanilha->sErroMsg;

    } else {
      $erro_msg = " Alteração  Efetuada com sucesso";
    }
    db_fim_transacao($sqlerro);
    if($sqlerro==false){
      $k81_valor = "";
      $k81_operbanco = "";
    }
  }
} else if (isset($excluir)) {

  if($sqlerro==false){
    db_inicio_transacao();
    $oPlanilha = new planilhaCaixa($k81_codpla);
    $oReceita  = db_utils::postMemory($_POST);
    if (!$oPlanilha->excluirReceita($k81_seqpla)) {
     
      $sqlerro  = true;
      $erro_msg = $oPlanilha->sErroMsg;

    } else {
      $erro_msg = " exclusão  Efetuada com sucesso";
    }
    db_fim_transacao($sqlerro);
    if($sqlerro==false){
      $k81_valor = "";
      $k81_operbanco = "";
    }

  }
  $k81_seqpla = "";
}else if(isset($opcao)){

  $sCampos  = "placaixarec.*,concarpeculiar.c58_sequencial,concarpeculiar.c58_descr,cgmmatric.z01_nome as nomematric, cgminscr.z01_nome as nomeinscr,";
  $sCampos .= "q02_inscr, j01_matric,cgm.z01_nome,k02_drecei,k13_descr";
  $result = $clplacaixarec->sql_record($clplacaixarec->sql_query_matric_inscr($k81_seqpla, $sCampos));
  if($result!=false && $clplacaixarec->numrows>0){
    db_fieldsmemory($result,0);

    $resulttab = $cltabrec->sql_record($cltabrec->sql_query_inst($k81_receita));

    $recurso = pg_result($resulttab,0,'recurso');

    $resulttab = $clsaltes->sql_record($clsaltes->sql_query($k81_conta));
    $c61_codigo = pg_result($resulttab,0,'c61_codigo');

  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmplacaixarec.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar) || isset($excluir) || isset($incluir)){
    
	if ($sqlerro) {
	 db_msgbox($erro_msg);	
	}
	
	if($clplacaixarec->erro_campo!=""){
        db_msgbox($erro_msg);
        echo "<script> document.form1.".$clplacaixarec->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clplacaixarec->erro_campo.".focus();</script>";
    }
}
?>