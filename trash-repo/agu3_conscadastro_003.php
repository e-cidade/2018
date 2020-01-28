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
include("dbforms/db_funcoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_iptubase_classe.php");
include("agu3_conscadastro_002_classe.php");

if(!isset($j39_numero)){
  $j39_numero = 0;
  $filtrotipo = 'todos';
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" height="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC" >
  <?
  if (isset($pesquisaRua)) {
    ?>
     <tr>
       <form name="form1" method="post">
       <td align="center" bgcolor="#CCCCCC">
        <input type="radio" name="filtrotipo" value="todos" <?=(@$filtrotipo=="todos" || !isset($filtrotipo)?"checked":"")?>>
        Ambos   
	<input type="radio" name="filtrotipo" value="Predial" <?=(@$filtrotipo=="Predial"?"checked":"")?>>
        Predial 
	<input type="radio" name="filtrotipo" value="Territorial" <?=(@$filtrotipo=="Territorial"?"checked":"")?>>
        Territorial &nbsp;&nbsp; 
        <?
         $clrotulo = new rotulocampo;
         $clrotulo->label("j39_numero");
         echo $Lj39_numero;
         db_input("j39_numero",6,$Ij39_numero,true,'text',2)
         ?>
        <input name="pesquisaRua" value="<?=$pesquisaRua?>"  type="hidden">
        <input name="pesquisa" value="Pesquisa"  type="submit">
       </td>
       </form>
     </tr>
    <?
  }
  ?>
  <tr>
  <td align="center">
  <?
  $funcao_js = "parent.mostraJanelaDadosImovel|0";
  $clsqlamatriculas   = new cl_iptubase;
  $clconsultaaguabase = new ConsultaAguaBase(0);
  if (isset($pesquisaPorNome)) {
    // a variavel $pesquisaPorNome retorna com o numro do cgm do registro selecionado
    $sql = $clsqlamatriculas->sqlmatriculas_nome($pesquisaPorNome);
    db_lovrot($sql,15,"()",$pesquisaPorNome,$funcao_js);
  } else if (isset($pesquisaPorImobiliaria)) {
    $sql = $clsqlamatriculas->sqlmatriculas_imobiliaria($pesquisaPorImobiliaria);
    db_lovrot($sql,15,"()",$pesquisaPorImobiliaria,$funcao_js);
  } else if (isset($pesquisaPorIDBQL)) {
    $sql = $clsqlamatriculas->sqlmatriculas_IDBQL($pesquisaPorIDBQL);
    db_lovrot($sql,15,"()",$pesquisaPorIDBQL,$funcao_js);
  } else if (isset($pesquisaRua)) {
    // aqui é usado o filtro "todos","Predial","Territorial"
    $sql = $clconsultaaguabase->sqlmatriculas_ruas($pesquisaRua,$j39_numero,$filtrotipo);
    db_lovrot($sql,100,"()",$pesquisaRua,$funcao_js);
  } else if (isset($pesquisaBairro)) {
    $sql = $clconsultaaguabase->sqlmatriculas_bairros($pesquisaBairro);
    db_lovrot($sql,15,"()",$pesquisaBairro,$funcao_js);
  }
  
?>
    </td>
  </tr>
</table>
</body>
</html>