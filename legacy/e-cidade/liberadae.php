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
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
if(isset($libera)){
$result = pg_exec("update db_dae set w04_enviado = 'f' where w04_codigo = $codigo");
echo "<script>parent.db_iframe.hide();</script>";
echo "<script>parent.alert('DAI Liberada para alteração');</script>";
}
$result = pg_exec("select * from db_dae inner join issbase on q02_inscr = w04_inscr inner join cgm on z01_numcgm = q02_numcgm where w04_codigo = $codigo");
db_fieldsmemory($result,0);
?>
<style>
td{
  font-family: arial;
  font-size: 11px;
  }
strong{
  font-family: arial;
  font-size: 11px;
  }
</style>
<form name="form1" method="post" action="">
<center>
<strong>Confirma a Liberação da DAI?<strong>
<table border="1">
  <tr>
    <td nowrap>
      <strong>Inscrição</strong>
    </td>
    <td nowrap>
      <strong>Nome</strong>
    </td>
    <td nowrap>
      <strong>Data de envio</strong>
    </td>
  </tr>
  <tr>
    <td nowrap>
      <?=$w04_inscr?>
    </td>
    <td nowrap>
      <?=$z01_nome?>
    </td>
    <td nowrap>
      <?=($w04_data!=""?db_formatar($w04_data,'d'):"")?>
    </td>
  </tr>
  </table>
    <br>
  <input name="codigo" type="hidden" value="<?=$w04_codigo?>" >
  <input name="libera" type="submit" value="Libera DAI" >
  </center>
</form>