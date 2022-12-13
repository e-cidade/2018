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

session_start();
include("libs/db_stdlib.php");
include("classes/db_itbi_classe.php");
$clitbi            = new cl_itbi;
$cod=@$_SESSION["itbi"];
postmemory($HTTP_POST_VARS);

if (isset($envia)){
	$sqlerro = false;
	
	$clitbi->it01_guia       = $cod;
	$clitbi->it01_finalizado = 't'; 
	$clitbi->alterar($cod);	
	if ($clitbi->erro_status == 0) {
				$sqlerro = true;
				die($clitbi->erro_sql);
				$erro_msg = $clitbi->erro_msg;
	}
	if ($sqlerro == false){
		msgbox("ITBI enviada");
		$_SESSION["itbi"] = "";
		 echo "
			<script>
           		parent.document.form1.disabilitado.value='sim';
				location.href = 'itbi_itbi.php';
				parent.trocacor('1');
			</script>
		";
	}
	
}

?>
<style type="text/css">
<?
db_estilosite(); ?>
</style>
<br><br>
<form name="form1" method="post" action="">
<table width="100%" border="0" cellspacing="0" cellpadding="0" >  
    <tr class="titulo">
      <td align="center">NÚMERO DA GUIA ITBI <?=$cod?></td>
    </tr>
    <tr>
      <td align="center">&nbsp;</td>
    </tr>
    <tr class="titulo">
      <td align="center">Confirme a solicitação da ITBI, após o envio da solicitação não podera mais alterar as informações.</td>
    </tr>
    <tr>
      <td align="center">&nbsp;</td>
    </tr>
    <tr>
      <td align="center"><input type="submit" name="envia" value="Enviar solicitação de ITBI" class="botao"></td>
    </tr>
</table>
</form >