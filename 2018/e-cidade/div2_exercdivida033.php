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
include("dbforms/db_classesgenericas.php");
include("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$aux = new cl_arquivo_auxiliar;
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">

  <table  align="center">
    <form name="form1" method="post" action="">
       <tr>
           <td colspan=2 >
           <b>Tipo de Débito:</b>
           <?
           $arr=array("d"=>"Todos","f"=>"Tributário","t"=>"Não Tributário");
           db_select('tipodeb',$arr,true,2,"onchange='document.form1.submit();'");
           ?>
           </td>
       </tr>
       <tr> 
           <td colspan=2 >
                <strong>Opções:</strong>
                <select name="ver">
                    <option name="condicao2" value="com">Com as procedências selecionadas</option>
                    <option name="condicao2" value="sem">Sem as procedências selecionadas</option>
                </select>
          </td>
       </tr>
       <tr>
          <td nowrap width="50%">
               <?$manda="";
               if (isset($tipodeb)&&($tipodeb!="")){
                 		$manda="$tipodeb";
               
               }
               
                 // $aux = new cl_arquivo_auxiliar;
                 $aux->cabecalho = "<strong>Procedências</strong>";
                 $aux->codigo = "v03_codigo"; //chave de retorno da func
                 $aux->descr  = "v03_descr";   //chave de retorno
                 $aux->nomeobjeto = 'procedencias';
                 $aux->funcao_js = 'js_mostra';
                 $aux->funcao_js_hide = 'js_mostra1';
                 $aux->sql_exec  = "";
                 $aux->func_arquivo = "func_proced.php?trib=$manda&";  //func a executar
                 $aux->nomeiframe = "db_iframe_proced";
                 $aux->localjan = "";
                 $aux->onclick = "";
                 $aux->db_opcao = 2;
                 $aux->tipo = 2;
                 $aux->top = 0;
                 $aux->linhas = 10;
                 $aux->vwhidth = 400;
                 $aux->funcao_gera_formulario();
                 
              ?>    
          </td>
       </tr>
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr >
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
  </form>
    </table>
</body>
</html>
<script>
</script>