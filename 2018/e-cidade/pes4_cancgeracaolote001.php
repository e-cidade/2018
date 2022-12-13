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
require("libs/db_utils.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_rhemissaocheque_classe.php");
include("classes/db_rhemissaochequeitem_classe.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clrhemissaocheque     = new cl_rhemissaocheque();
$clrhemissaochequeitem = new cl_rhemissaochequeitem();

$clrhemissaocheque->rotulo->label();

$db_opcao = 33;

if ( isset($oPost->cancelar) ) {
	
	$lSqlErro = false;
	
	db_inicio_transacao();
	
	
	$clrhemissaochequeitem->excluir(null," r18_emissaocheque = {$oPost->r15_sequencial} ");
	
	if ( $clrhemissaochequeitem->erro_status == 0 ) {
		$lSqlErro = true;
	}

	$sMsgErro = $clrhemissaochequeitem->erro_msg;
	
	
	if ( !$lSqlErro ) {
		
		$clrhemissaocheque->excluir($oPost->r15_sequencial);
		
	  if ( $clrhemissaocheque->erro_status == 0 ) {
	    $lSqlErro = true;
	  }
	
	  $sMsgErro = $clrhemissaocheque->erro_msg;		
		
	}
	
	db_fim_transacao($lSqlErro);
	
} else if ( isset($oGet->chavepesquisa) ) {
	
	$rsConsultaGeracao = $clrhemissaocheque->sql_record($clrhemissaocheque->sql_query($oGet->chavepesquisa));
	
	if ( $clrhemissaocheque->numrows > 0 ) {
		db_fieldsmemory($rsConsultaGeracao,0);
	}
	
	$db_opcao = 3;
	
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<form name="form1" method="post" action="">
	<table align="center" style="padding-top:25px;">
	  <tr>
	    <td colspan="2">
			  <fieldset>
			    <legend align="center">
			      <b>Cancela Geração de Cheques por Lote</b>
			    </legend>
				  <table>
				    <tr>
				      <td align="right">
				        <b>Cód Geração:</b>
				      </td>
				      <td>
				        <?
                  db_input("r15_sequencial",10,$Ir15_sequencial,true,"text",$db_opcao,"");
				        ?>
				      </td>
				    </tr>
            <tr>
              <td align="right">
                <b>Descrição</b>
              </td>
              <td>
                <?
                  db_input("r15_descricao",55,$Ir15_descricao,true,"text",$db_opcao,"");
                ?>
              </td>
            </tr>
            <tr>
              <td align="right">
                <b>Usuário:</b>
              </td>
              <td>
                <?
                  db_input("r15_idusuario",10,$Ir15_idusuario,true,"text",$db_opcao,"");
                  db_input("nome",40,"",true,"text",3,"");
                ?>
              </td>
            </tr>				    
            <tr>
              <td align="right">
                <b>Data Geração:</b>
              </td>
              <td>
                <?
                  db_inputdata('r15_dtgeracao',@$r15_dtgeracao_dia,@$r15_dtgeracao_mes,@$r15_dtgeracao_ano,true,"text",$db_opcao,"");
                ?>                
              </td>
            </tr>
            <tr>
              <td align="right">
                <b>Hora:</b>
              </td>
              <td>
                <?
                  db_input("r15_hora",5,$Ir15_hora,true,"text",$db_opcao,"");
                ?>
              </td>
            </tr>                                                                       
				  </table>
			  </fieldset>
		  </td>
	  </tr>
	  <tr>
	    <td align="right"> 
	      <input name="cancelar"  type="submit" value="Processar" <?=($db_opcao!=3?"disabled":"")?> >
	    </td>
	    <td> 
	      <input name="pesquisar" type="button" value="Pesquisar" onclick="js_pesquisaGeracao();" >
	    </td>    
	  </tr>
	</table> 	  
</form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

function js_pesquisaGeracao(mostra){
  js_OpenJanelaIframe('top.corpo','db_iframe_geracao','func_rhemissaocheque.php?funcao_js=parent.js_mostraGeracao|r15_sequencial','Pesquisa',true);
}

function js_mostraGeracao(chave1){
  db_iframe_geracao.hide();
  document.location.href = 'pes4_cancgeracaolote001.php?chavepesquisa='+chave1;
}
</script>

<?
   
   if ( isset($oPost->cancelar) ) {

   	 db_msgbox($sMsgErro);
   	 
   	 if ( !$lSqlErro ) {
   	 	  echo "<script>document.location.href='pes4_cancgeracaolote001.php'</script>";
   	 }
   	
   }

   if ( $db_opcao == 33 ) { 
     echo "<script>document.form1.pesquisar.click();</script>";
   }
   
?>