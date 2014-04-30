<?php
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
include("dbforms/db_classesgenericas.php");
require("libs/db_app.utils.php");

$clrotulo = new rotulocampo;
$clrotulo->label("x21_exerc");
$clrotulo->label("x21_mes");

$clArqAuxiliar = new cl_arquivo_auxiliar();

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
<script>
function js_relatorio(){
	var zona = js_campo_recebe_valores();
	var ano  = document.form1.ano.value;
	var mes  = document.form1.mes.value;

	if(zona == '') {
    alert('Zona(s) não informada(s).');
    return false;
	}
	if(ano == '') {
    alert('Ano não informado.');
    return false;
  }
	if(zona == '') {
    alert('Mês não informada.');
    return false;
  }	  
		
  query = "";
  query += "zona="+zona;
  query += "&ano="+ano;
  query += "&mes="+mes;

  jan = window.open('agu2_relimobiliarias002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}

</script>
</head>
<body bgcolor=#CCCCCC>
<form name="form1">
<table style="margin: 20px auto">

  <tr>
    <td>
    <?php
      $clArqAuxiliar->cabecalho      = '<strong>Zona de Entrega</strong>';
      $clArqAuxiliar->codigo         = 'j85_codigo'; //chave de retorno da func
      $clArqAuxiliar->descr          = 'j85_descr';   //chave de retorno
      $clArqAuxiliar->nomeobjeto     = 'zona_entrega';
      $clArqAuxiliar->funcao_js      = 'js_mostra_zona_ent';
      $clArqAuxiliar->funcao_js_hide = 'js_mostra_zona_ent1';
      $clArqAuxiliar->func_arquivo   = 'func_iptucadzonaentrega.php';  //func a executar
      $clArqAuxiliar->nomeiframe     = 'db_iframe_zona_ent';
      $clArqAuxiliar->nome_botao     = 'db_lanca_zona_ent';
      $clArqAuxiliar->db_opcao       = 2;
			$clArqAuxiliar->tipo           = 2;
			$clArqAuxiliar->top            = 0;
			$clArqAuxiliar->linhas         = 4;
			$clArqAuxiliar->vwidth         = 350;
			$clArqAuxiliar->Labelancora    = 'C&oacute;digo';
			$clArqAuxiliar->obrigarselecao = false;
      $clArqAuxiliar->funcao_gera_formulario(); 
    ?>
    </td>
  </tr>
  
  <tr>
    <td align="center">
    <strong>Ano / Mês :</strong>
    <?
      $ano = date("Y",db_getsession("DB_datausu"));
      $mes = date("m",db_getsession("DB_datausu"));
      
      db_input("ano",4,@$Ix21_exerc,true,"text",1);
       
      echo '<strong> / </strong>';
      
      db_input("mes",2,@$Ix21_mes,true,"text",1);
    ?>
    </td>
  </tr>
  
  <tr>
    <td align="center" colspan="2">
      <input type="button" name="gerar" value="Gerar Relat&oacute;rio" onclick="js_relatorio()" /> 
    </td>
  </tr>
</table>

<? 
  db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</form>
</body>
</html>