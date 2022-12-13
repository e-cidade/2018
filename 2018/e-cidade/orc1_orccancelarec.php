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
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_liborcamento.php");
include("classes/db_orcduplicacao_classe.php");
include("classes/db_orcduplicacaodotacao_classe.php");
include("classes/db_orcduplicacaoreceita_classe.php");
include("classes/db_orcdotacao_classe.php");
include("classes/db_orcreceita_classe.php");
include("classes/db_conaberturaexe_classe.php");
include("dbforms/db_funcoes.php");

$get  = db_utils::postmemory($_GET);
$post = db_utils::postmemory($_POST);

(integer)$o75_conabertura = null;
(string) $sSql            = null;
(string) $sErro           = null;
(bool)   $lSqlErro        = false;

$clorcduplicacao          = new cl_orcduplicacao;
$clorcduplicacaoreceita   = new cl_orcduplicacaoreceita;
$clorcduplicacaodotacao   = new cl_orcduplicacaodotacao;
$clconaberturaexe         = new cl_conaberturaexe;
$clorcreceita             = new cl_orcreceita;

$clconaberturaexe->rotulo->label();
$db_opcao = 22;
$db_botao = true;
if(isset($cancela)){

   db_inicio_transacao();
	 
	 $sDel  = "delete
	             from orcduplicacaoreceita 
	            using orcduplicacao
							where o77_orcduplicacao  = o75_sequencial
							  and o75_conaberturaexe = ".$post->o75_conaberturaexe; 
	 $rsDel = pg_query($sDel);							
	 if (!$rsDel){

		  echo "<br><br>aqui ";
       
			 $sErro = "Não foi possivel Excluir receitas";
			 $lSqlErro = true;
			 
	 }
	 if ($lSqlErro == false){

 		  $clorcduplicacao->excluir(null,"o75_conaberturaexe = ".$post->o75_conaberturaexe);
			if ($clorcduplicacao->erro_status == 0){
          
					$lSqlErro = true;
					$sErro    = $clorcduplicacao->erro_msg;
			}
	 }
	 if ($lSqlErro == false){

 		  $clorcreceita->excluir(null,null,"o70_anousu = ".$post->c91_anousudestino." and o70_instit = ".db_getsession("DB_instit"));
			if ($clorcreceita->erro_status == 0){
          
					$lSqlErro = true;
					$sErro    = $clorcreceita->erro_msg;
			}
	 }
   if ($lSqlErro == false){

      $clconaberturaexe->c91_situacao   = 3;
      $clconaberturaexe->c91_sequencial = $post->o75_conaberturaexe;
			$clconaberturaexe->alterar($post->o75_conaberturaexe);
      if ($clconaberturaexe->erro_status == 0){
 
          db_msgbox('aqui');
					$lSqlErro = true;
					$sErro    = $clconaberturaexe->erro_msg;
          
			}

	 }
   db_fim_transacao($lSqlErro);
	 if ($lSqlErro == true){
      db_msgbox($sErro);
	 }else{
      
			db_msgbox("Abertura cancelada com sucesso!");
			db_redireciona('orc1_orccancelarec.php');
	 }

}

if (isset($get->chavepesquisa) && $get->chavepesquisa != ''){
   $rsCon =  $clconaberturaexe->sql_record($clconaberturaexe->sql_query($get->chavepesquisa));
    if ($clconaberturaexe->numrows > 0){
        
				$db_opcao           = 3;
				$oCon               = db_utils::fieldsmemory($rsCon,0);
				$c91_anousudestino  = $oCon->c91_anousudestino;
				$c91_anousuorigem   = $oCon->c91_anousuorigem;
				$o75_conaberturaexe = $oCon->c91_sequencial;

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
		<table>
<tr><td>
    <fieldset><legend><b> Cancelamento de Duplicação do Orcamento - Receita</b></legend>
		
		<table width='100%'>
		<form name='form1' method="post">
            <tr>
						    <input type='hidden' name='o75_conaberturaexe' value='<?=@$o75_conaberturaexe?>'>
                <td nowrap title="<?=@$Tc91_anousuorigem?>">
                 <?=@$Lc91_anousuorigem?>
                </td> 
                 <td> 
                 <?
                   db_input('c91_anousuorigem',5,$Ic91_anousuorigem,true,'text',3,"")
                 ?>
								</td>
						 </tr>
             <tr>
              <td nowrap title="<?=@$Tc91_anousudestino?>">
                 <?=@$Lc91_anousudestino?>
              </td> 
              <td> 
                 <?
                   db_input('c91_anousudestino',5,$Ic91_anousudestino,true,'text',3,"")
                 ?>
							</td>
					 </tr>
				 <tr><td colspan='3' style='text-align:center'>
       <input name="cancela"  onclick='return js_geraImp()' type="submit" id="db_opcao" 
			        value="Cancelar" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
		
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
//js_tabulacaoforms("form1","o75_conaberturaexe",true,1,"o75_conaberturaexe",true);
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_duplicacao','func_conaberturaexe.php?ano=1&tipo=3&funcao_js=parent.js_preenchepesquisa|c91_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_duplicacao.hide();
  <?
  if($db_opcao!=1){
    echo "location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function js_cancela(){

 return (confirm('Confirma o cancelamento?'));
}
</script>
<?
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>