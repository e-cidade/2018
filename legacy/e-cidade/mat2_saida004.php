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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_classesgenericas.php");
include("dbforms/db_funcoes.php");
include("classes/db_orcorgao_classe.php");
include("classes/db_matestoquetipo_classe.php");

db_postmemory($HTTP_POST_VARS);
$cliframe_seleciona     = new cl_iframe_seleciona;
$clorcorgao             = new cl_orcorgao;
$clmatestoquetipo       = new cl_matestoquetipo;


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

  <table  align="center">
    <form name="form1" method="post" action="">
      <tr>

      <td colspan=2>
      <br>
      <?
      $instit=db_getsession("DB_instit");
      $anousu=db_getsession("DB_anousu");
      $cliframe_seleciona->chaves = "o40_orgao";
      $cliframe_seleciona->campos  = "o40_orgao,o40_descr";
      $cliframe_seleciona->legenda="Órgãos";
      $cliframe_seleciona->sql=$clorcorgao->sql_query_orgao(null,null,"distinct o40_orgao,o40_descr","o40_descr","o40_instit=$instit and  o40_anousu=$anousu");
      $cliframe_seleciona->iframe_height ="300";
      $cliframe_seleciona->iframe_width  ="400";
      $cliframe_seleciona->iframe_nome ="db_iframe_orgao";
      $cliframe_seleciona->dbscript="onClick='parent.js_verifica(this)'";
      $cliframe_seleciona->iframe_seleciona(1);
      ?>
      </td>
      <td colspan=2>
      <br>
      <?
      $cliframe_seleciona->chaves = "m81_codtipo";
      $cliframe_seleciona->campos  = "m81_codtipo, m81_descr";
      $cliframe_seleciona->legenda="Tipos de Saída";
      $cliframe_seleciona->sql=$clmatestoquetipo->sql_query_file(null,"m81_codtipo, m81_descr","m81_codtipo","m81_codtipo in (5, 17, 19, 21)");
      $cliframe_seleciona->iframe_height ="300";
      $cliframe_seleciona->iframe_width  ="400";
      $cliframe_seleciona->iframe_nome ="db_iframe_matestoquetipo";
      $cliframe_seleciona->dbscript="onClick='parent.js_verifica_matestoquetipo(this)'";
      $cliframe_seleciona->iframe_seleciona(2);
      ?>
      </td>
    </tr>

  </form>
    </table>
</body>
</html>
<script>      

function js_verifica(chk){
  if (parent.iframe_g1.document.form1.departamentos.length!="0") {
    alert("Já existe departamento selecionado na guia Departamentos.");
     chk.checked = false;
    //  parent.document.formaba.g4.disabled=true;
     }else{
       orgao=false;
       obj=parent.iframe_g4.db_iframe_orgao.document.getElementsByTagName("input");
       nObj=obj.length;
         for (i=0;i < nObj;i++){
              if (obj[i].type == "checkbox" && obj[i].checked==true){
                  orgao=true; 
                 }   
            }
     }
}
function js_verifica_matestoquetipo(chk){
   matestoquetipo=false;
   obj=parent.iframe_g4.db_iframe_matestoquetipo.document.getElementsByTagName("input");
   nObj=obj.length;
   for (i=0;i < nObj;i++){
      if (obj[i].type == "checkbox" && obj[i].checked==true){
          matestoquetipo=true; 
      }   
   }
}
</script>