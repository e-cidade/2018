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

// Conexões necessárias
 require("libs/db_stdlib.php");
 require("libs/db_conecta.php");
 include("libs/db_sessoes.php");
 include("libs/db_usuariosonline.php");

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>
        <form name="form1" method="post" action="">
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr> 
        <td align="center">&nbsp;Imobili&aacute;ria:&nbsp; <input name="nome_imobiliaria" type="text" id="nome_imobiliaria" size="41" maxlength="40"> 
          &nbsp; <input name="db_pesquisar" type="submit" id="db_pesquisar" value="Pesquisar"> 
        </td>
      </tr>
      <tr> 
        <td align="center">&nbsp;</td>
      </tr>
      <tr> 
        <td align="center"> 
          <?
  if (isset($nome_imobiliaria)){
    $nome_imobiliaria = strtoupper($nome_imobiliaria);
    $sql = "
    select distinct z01_numcgm, z01_nome,
    z01_ender, z01_munic, z01_uf, z01_cep
    from imobil
    inner join cgm  on j44_numcgm = z01_numcgm
    where z01_nome like '$nome_imobiliaria%'
	";
    db_lovrot($sql,15,"()",$nome_imobiliaria,$funcao_js);
  }else if (isset($codimobiliaria)){
    $sql = "
    select distinct z01_numcgm, z01_nome,
    z01_ender, z01_munic, z01_uf, z01_cep
    from imobil
    inner join cgm  on j44_numcgm = z01_numcgm
    where z01_numcgm = $codimobiliaria
	";
    db_lovrot($sql,15,"()",$codimobiliaria,$funcao_js);
  }
?>
        </td>
      </tr>
    </table>
        </form>
</center>

</body>
</html>