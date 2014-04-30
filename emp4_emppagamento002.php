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

//------------------------------------------------------
//   Arquivos que verificam se o boletim já foi liberado ou naum
  include("classes/db_boletim_classe.php");
  $clverficaboletim =  new cl_verificaboletim(new cl_boletim);
//------------------------------------------------------

  

  include("libs/db_liborcamento.php");
  include("classes/db_orcdotacao_classe.php");
  include("classes/db_empempenho_classe.php");
  include("classes/db_empelemento_classe.php");
  include("classes/db_pagordem_classe.php");
  include("classes/db_pagordemele_classe.php");

  include("classes/db_cfautent_classe.php");
  $clcfautent = new cl_cfautent;

  $clpagordem    = new cl_pagordem;
  $clpagordemele = new cl_pagordemele;
  $clempempenho  = new cl_empempenho;
  $clempelemento = new cl_empelemento;
  $clorcdotacao  = new cl_orcdotacao;


  include("libs/db_libcaixa.php");
  $clautenticar= new cl_autenticar;

  include("classes/db_empagemov_classe.php");
  $clempagemov    = new cl_empagemov;


  include("classes/db_conlancam_classe.php");
  include("classes/db_conlancamele_classe.php");
  include("classes/db_conlancampag_classe.php");
  include("classes/db_conlancamcgm_classe.php");
  include("classes/db_conparlancam_classe.php");
  include("classes/db_conlancamemp_classe.php");
  include("classes/db_conlancamval_classe.php");
  include("classes/db_conlancamdot_classe.php");
  include("classes/db_conlancamdoc_classe.php");
  include("classes/db_conlancamcompl_classe.php");
  include("classes/db_saltes_classe.php");
  include("classes/db_conplanoreduz_classe.php");
  include("classes/db_conlancamord_classe.php");
  include("classes/db_conlancamlr_classe.php");


$clconlancam	          = new cl_conlancam;
$clconlancamele         = new cl_conlancamele;
$clconlancampag	  = new cl_conlancampag;
$clconlancamcgm	  = new cl_conlancamcgm;
$clconparlancam	  = new cl_conparlancam;
$clconlancamemp	  = new cl_conlancamemp;
$clconlancamval	  = new cl_conlancamval;
$clconlancamdot	  = new cl_conlancamdot;
$clconlancamdoc	  = new cl_conlancamdoc;
$clconlancamcompl = new cl_conlancamcompl;
$clconplanoreduz = new cl_conplanoreduz;
$clsaltes = new cl_saltes;
$clconlancamord	  = new cl_conlancamord;
$clconlancamlr	  = new cl_conlancamlr;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

//retorna os arrays de lancamento...
$cltranslan       = new cl_translan;

$db_opcao = 22;
$db_botao = false;	 
    
if(isset($confirmar)){
      $db_opcao = 2;
      $db_botao = true;

      $sqlerro=false;
      db_inicio_transacao();

      // arquivo de pagamento de empenho
      include("emp1_emppagamentoarq.php");  

      db_fim_transacao($sqlerro);
}
  


// escreve na autenticadora
if( ( isset($confirmar) && $sqlerro==false && $k11_tipautent == 1)  || isset($retorno_imp)){
    if(isset($retorno_imp)){
       $retorno = $retorno_imp;
    }
    // b_msgbox($retorno);
    $fd = fsockopen(db_getsession('DB_ip'),4444);
    fputs($fd,chr(15)."$retorno".chr(18).chr(10).chr(13));
    fclose($fd);
}  


function php_erro($msg){
  global $e60_numemp,$e50_codord;
  $erro = base64_encode("erro_msg=$msg&e50_codord=$e50_codord&e60_numemp=$e60_numemp");
  db_redireciona("emp1_emppagamento001.php?$erro");
}
//quando o pagamento for por empenho, será criado um input no formulario com o nome de pag_emp e  se for por ordem de
//pagamento, o nome será pag_ord



if(isset($pag_emp) && empty($confirmar)){
  $db_opcao = 2;
  $db_botao = true;
   //rotina que traz os dados de empempenho
   if(isset($e60_codemp) && $e60_codemp !=''){
      $arr = split("/",$e60_codemp);
      if(count($arr) == 2  && isset($arr[1]) && $arr[1] != '' ){
	$dbwhere_ano = " and e60_anousu = ".$arr[1];
      }else{
	$dbwhere_ano = " and e60_anousu = ".db_getsession("DB_anousu");
      }
      $sql = $clempempenho->sql_query("","*","e60_numemp"," e60_codemp =  '".$arr[0]."' $dbwhere_ano and e60_instit = ".db_getsession("DB_instit"));
   }else{
      $sql = $clempempenho->sql_query($e60_numemp);
   }   
   $result = $clempempenho->sql_record($sql); 
   if($clempempenho->numrows>0){
     db_fieldsmemory($result,0,true);
   }else{
     php_erro("Empenho inválido!");
     exit;
   }

   $result01 = $clpagordemele->sql_record($clpagordemele->sql_query(null,null,'(sum(e53_valor)-sum(e53_vlranu)) as saldo  ',"","e50_numemp = $e60_numemp ")); 
   $numrows01 = $clpagordemele->numrows;
   if($numrows01>0){
     db_fieldsmemory($result01,0);
     if($saldo!=0){
        php_erro("Empenho possui ordens de pagamento!");
       exit;
     } 
   }
}else if(isset($pag_ord) && empty($confirmar)){

  $db_opcao = 2;
  $db_botao = true;
   //rotina que traz os dados de pagordem
   $result = $clpagordem->sql_record($clpagordem->sql_query(null,"*",null,"e50_codord= $e50_codord and e60_instit=".db_getsession("DB_instit"))); 
   if($clpagordem->numrows>0){
     db_fieldsmemory($result,0);
     $result01 = $clpagordem->sql_record($clpagordem->sql_query(null,'e50_codord as codord',"","e50_numemp = $e50_numemp")); 
     db_fieldsmemory($result01,0);
   }else{
     php_erro("Ordem de pagamento inválida ! ");
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
	include("forms/db_frmemppagamento.php");
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

if( (isset($retorno) && $k11_tipautent == 1) ||  (isset($retorno_imp))  ){
echo "
       <script>
	   retorna = confirm('Autenticar novamente?');
	   if(retorna == true){
	     obj=document.createElement('input');
	     obj.setAttribute('name','retorno_imp');
	     obj.setAttribute('type','hidden');
	     obj.setAttribute('value','$retorno');
	     document.form1.appendChild(obj);
	     document.form1.submit();
	     
	   }
       </script>


";
}
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
if(isset($confirmar)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
  }else{
    //db_msgbox($ok_msg);
  }  
} 
if($clempagemov->numrows > 0){
	echo "<script>js_cheque(true)();</script>";
}
echo "<script>document.form1.k12_cheque.focus();</script>";
?>