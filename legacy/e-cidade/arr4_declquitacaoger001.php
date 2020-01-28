<?php
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
require("libs/db_conecta.php");
include("dbforms/db_funcoes.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include ("libs/db_app.utils.php");

$clrotulo = new rotulocampo();
$clrotulo->label('ar30_exercicio');

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<? 

  db_app::load('scripts.js');
  db_app::load('estilos.css');

?>
<script type="text/javascript">
function validaForm() {

	var F = document.form1;

	data = new Date();
  ano  = data.getFullYear();
    
	if((F.ar30_exercicio.value < 1900) || (F.ar30_exercicio.value > (ano - 1))) {
		alert('Exercicio da declaração de quitação inválido');
		return false;
	}
	
	if(!confirm('Esse processo levará vários minutos. Tem certeza q deseja proseguir?')) {
		return false;
	}
	
}
</script>
</head>
<body bgcolor="#CCCCCC">

<form name="form1" method="post" action="arr4_declquitacaoger002.php" onsubmit="return validaForm()">

<fieldset style="width: 450px; margin: 35px auto">
  <legend><strong>Declara&ccedil;&atilde;o Quita&ccedil;&atilde;o Geral</strong></legend>

  <table width="380" align="center">
  
    <tr>
    
      <td title="<?=$Tar30_exercicio?>"><?=$Lar30_exercicio?></td>
      
      <td>
      <?
        db_input('ar30_exercicio', 15, $Iar30_exercicio, true, 'text', 1);
      ?>
      </td>
      
    </tr>
    
    <tr>
    
      <td title="Origem da Declara&ccedil;&atilde;o"><strong>Origem</strong></td>
      
      <td>
      <?
        $origem = 'matric';
        $aOrigem = array('cgm'=>'CGM Geral', 'somentecgm'=>'Somente CGM', 'matric'=>'Matr&iacute;cula', 'inscr'=>'Inscri&ccedil;&atilde;o');
        db_select('origem', $aOrigem, true, 1, 'style="width: 200px"'); 
      ?>
      </td>
      
    </tr>
    
    <tr>
    
      <td title="Tipo de emiss&atilde;o"><strong>Tipo de Emissão</strong></td>
      
      <td>
      <?
        $aTipo = array('txt'=>'TXT', 'pdf'=>'PDF');
        db_select('tipo', $aTipo, true, 1, 'style="width: 200px"'); 
      ?>
      </td>
      
    </tr>
    
    <tr>
    
      <td title="[TODAS DO EXERCÍCIO] = Gera no arquivo todas as declarações do exercício informado. [SOMENTE NÃO GERADAS/CANCELADAS] = Novas declarações que foram quitadas ou canceladas desde o último processamento"><strong>Emitir Declarações</strong></td>
      
      <td>
      <?
        $aArquivo = array('T'=>'TODAS DO EXERCÍCIO', 'S'=>'SOMENTE NÃO GERADAS/CANCELADAS');
        
        db_select('arquivo', $aArquivo, true, 1, 'style="width: 200px"');
      ?>
      </td>
      
    </tr>
    
    <tr>
    
      <td colspan="2" align="center"><br/>
        <input type="submit" name="imprimir" value="Processar"/>
      </td>
      
    </tr>

  </table>
</fieldset>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</form>
</body>
</html>