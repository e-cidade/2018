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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_liborcamento.php");
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>

variavel = 1;
function js_emite(){

  document.form1.orgaos.value  = parent.iframe_filtro.js_atualiza_variavel_retorno();
  sel_instit  = new Number(document.form1.db_selinstit.value);
  
  if (sel_instit == 0) {
    
    alert('Você não escolheu nenhuma Instituição. Verifique!');
    return false;
  } else {
    
  jan = window.open('',
                    'safo' + variavel,
                    'width='+(screen.availWidth-5)+
                    ',height='+(screen.availHeight-40)+
                    ',scrollbars=1,location=0 '
                   );
                                        
    document.form1.target = 'safo' + variavel++;
    setTimeout("document.form1.submit()",1000);
    return true;
 }
}
function js_limpa() {

  if (document.form1.orgaos.value != '') {

    alert('Os dados selecionados serão excluídos. Você deverá selecionar novamente.');
    document.form1.vernivel.value = '';
    document.form1.orgaos.value   = '';
    document.form1.seleciona.click();
  }
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<br>
<center>
  <form name="form1" method="post" action="orc2_totaliza002.php">
    <fieldset style="width:350px;">
      <legend><strong>Totalização do Orçamento Despesa</strong></legend>
        <table>
          <tr>
            <td align="center" colspan="2">
              <?
                db_selinstit('parent.js_limpa', 300);
              ?>
            </td>
          </tr>
          <tr>
            <td><b>Nível:</b></td>
            <td>
              <?
                $aNivel = array('1A' => 'Órgão Até o Nível',
                                '2A' => 'Unidade Até o Nível',
                                '3A' => 'Função Até o Nível',
                                '3B' => 'Função só o Nível',
                                '4A' => 'Subfunção Até o Nível',
                                '4B' => 'Subfunção só o Nível',
                                '5A' => 'Programa Até o Nível',
                                '5B' => 'Programa só o Nível',
                                '6A' => 'Proj/Ativ Até o Nível',
                                '6B' => 'Proj/Ativ só o Nível',
                                '7A' => 'Elemento Até o Nível',
                                '7B' => 'Elemento só o Nível',
                                '8A' => 'Recurso Até o Nível',
                                '8B' => 'Recurso só o Nível');
                
                db_select('nivel', $aNivel, true, 2, "style='width:100%'");
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <input name="orgaos"   id="orgaos"   type="hidden" value="" >
              <input name="vernivel" id="vernivel" type="hidden" value="" >
            </td>
          </tr>        
        </table>
    </fieldset>
    <p><input name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" ></p>
  </form>
</center>
</body>
</html>