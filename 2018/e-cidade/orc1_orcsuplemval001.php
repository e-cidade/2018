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


require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_liborcamento.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("classes/db_orcreserva_classe.php");
require_once("classes/db_orcreservasup_classe.php");
require_once("classes/db_orcsuplem_classe.php");
require_once("classes/db_orcsuplemval_classe.php");
require_once("classes/db_orcdotacao_classe.php");   // instancia da classe dotação
require_once("classes/db_orcreceita_classe.php"); // receita
require_once("classes/db_orcorgao_classe.php"); // receita

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clorcsuplemval           = new cl_orcsuplemval;
$clorcdotacao             = new cl_orcdotacao;  // instancia da classe dotação
$clorcsuplem              = new cl_orcsuplem;
$clorcorgao               = new cl_orcorgao;
$clorcreserva             = new cl_orcreserva;
$clorcreservasup          = new cl_orcreservasup;
$clrotulo                 = new rotulocampo;

$clrotulo->label("o58_concarpeculiar");
$clrotulo->label("c58_descr");

$clorcsuplem->rotulo->label();
$clorcsuplemval->rotulo->label();
$clorcorgao->rotulo->label();
$clorcdotacao->rotulo->label();

$op =1 ;
$db_opcao = 1;
$db_botao = true;
$anousu = db_getsession("DB_anousu");

//------------------------------------------
if (isset($pesquisa_dot) && $o47_coddot!=""){
   // foi clicado no botão "pesquisa" da tela
   $res = $clorcdotacao->sql_record($clorcdotacao->sql_query(db_getsession("DB_anousu"),$o47_coddot));
   if ($clorcdotacao->numrows > 0 ){
      db_fieldsmemory($res,0); // deve existir 1 registro

      $resdot= db_dotacaosaldo(8,2,2,"true","o58_coddot=$o47_coddot",db_getsession("DB_anousu"),$anousu.'-01-01',$anousu.'-12-31');
      db_fieldsmemory($resdot,0);
       // $atual_menos_reservado 

   }  
}  
//------------------------------------------
$limpa_dados = false;

if(isset($incluir)){
  // pressionado botao incluir na tela  
  $limpa_dados = true;
  $sqlerro = false;

  db_inicio_transacao();

  // verifica se tem saldo para reservar  - saldo atual_menos_reservado = saldo disponivel liquido
  $dtini  = $anousu.'-01-01'; // pega o saldo desdo inicio do ano, pra não permitir voltar a data e reduzir
  $dtfin  = $anousu.'-12-31';
  $result= db_dotacaosaldo(8,2,2,"true","o58_coddot=$o47_coddot",db_getsession("DB_anousu"),$dtini,$dtfin) ;
  db_fieldsmemory($result,0);
  if ($atual_menos_reservado < (abs($o47_valor))){
     $sqlerro =true;
     db_msgbox("Dotação $o47_coddot sem saldo ! (Saldo $atual_menos_reservado) ");
  }    
  
  // lança reserva
  $clorcreserva->o80_anousu = $anousu;
  $clorcreserva->o80_coddot = $o47_coddot;
  $clorcreserva->o80_dtlanc = date("Y-m-d", db_getsession('DB_datausu'));
  $clorcreserva->o80_dtini  = date("Y-m-d", db_getsession('DB_datausu'));
  $clorcreserva->o80_dtfim  = db_getsession('DB_anousu')."-12-31";
  $clorcreserva->o80_valor  = $o47_valor;
  $clorcreserva->o80_descr  = "suplementacao ";
  if ($sqlerro==false){
     $clorcreserva->incluir("");
     if ($clorcreserva->erro_status == 0 ){
        $sqlerro = true;
	db_msgbox($clorcreserva->erro_msg);
     }  
  }
  //
  $clorcreservasup->o81_codres = $clorcreserva->o80_codres;
  $clorcreservasup->o81_codsup = $o46_codsup;
  if ($sqlerro == false){
    $clorcreservasup->incluir($clorcreservasup->o81_codres);
    if ($clorcreservasup->erro_status == 0 ){
        $sqlerro = true;
        db_msgbox($clorcreservasup->erro_msg);
    }		   
  }
  $clorcsuplemval->o47_valor          = (abs($o47_valor))*-1; 
  $clorcsuplemval->o47_anousu         = db_getsession("DB_anousu");
  $clorcsuplemval->o47_concarpeculiar = "{$o58_concarpeculiar}";
  if ($sqlerro == false ) {
     $clorcsuplemval->incluir($o46_codsup,db_getsession("DB_anousu"),$o47_coddot);
     if ($clorcsuplemval->erro_status == 0){
         $sqlerro = true;
         db_msgbox($clorcsuplemval->erro_msg);
         $limpa_dados = false;
     }  
  }
  db_fim_transacao($sqlerro);
   
}elseif(isset($opcao) && $opcao=="excluir" ){
  $limpa_dados = true;
  // clicou no exlcuir, já exlcui direto, nem confirma nada
  db_inicio_transacao();
  $sqlerro     = false;
  // procura reserva
  $res = $clorcreservasup->sql_record($clorcreservasup->sql_query(null,"o81_codres",null,"o81_codsup = $o46_codsup and o80_coddot=$o47_coddot "));
  if ($clorcreservasup->numrows > 0){
      db_fieldsmemory($res,0);
  }  
  $clorcreservasup->excluir($o81_codres);
  if ($clorcreservasup->erro_status == 0){
     $sqlerro = true;
     db_msgbox($clorcreservasup->erro_msg);
  }  
  $clorcreserva->excluir($o81_codres);
  if ($clorcreserva->erro_status == 0){
      $sqlerro = true;
      db_msgbox($clorcreserva->erro_msg);
  }
  $clorcsuplemval->excluir($o46_codsup,$anousu,$o47_coddot);
  if ($clorcsuplemval->erro_status == 0){
     $sqlerro = true;
     $limpa_dados = false;
     db_msgbox($clorcsuplemval->erro_msg);
  }  
  // $sqlerro = true;
  db_msgbox($clorcsuplemval->erro_msg);
  db_fim_transacao($sqlerro);

}   
if ($limpa_dados ==true){
   $o47_coddot = "";
   $o58_orgao  = "";
   $o40_descr  = "";
   $o56_elemento ="";
   $o56_descr    ="";
   $o58_codigo   ="";
   $o15_descr    ="";
   $o47_valor    ="";
   $atual_menos_reservado = "";
}  
// --------------------------------------
// calcula total das reduções
$res = $clorcsuplemval->sql_record("select sum(o47_valor) as soma_reduz
                                    from orcsuplemval where o47_codsup=$o46_codsup and o47_valor < 0");
if ($clorcsuplemval->numrows > 0 ){
    db_fieldsmemory($res,0,true);	         
}

// --------------------------------------

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
<table width="480" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmorcsuplemval_reducao.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?

if(isset($incluir) || isset($alterar) || isset($excluir)){
  if($clorcsuplemval->erro_status=="0"){
      $clorcsuplemval->erro(true,false);
      $db_botao=true;
      echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
      if($clorcsuplemval->erro_campo!=""){
        echo "<script> document.form1.".$clorcsuplemval->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clorcsuplemval->erro_campo.".focus();</script>";
      };
  }else{
       $clorcsuplemval->erro(true,false);
  };
};

?>