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
include("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$strRetorno = "";
$pipe       = "";
$traco      = "-";

$sqlItem   = " select k09_sequencial, ";
$sqlItem  .= "        k37_descr ";
$sqlItem  .= "   from cadtipo ";
$sqlItem  .= "        inner join cadtipoitem      on k03_tipo = k09_cadtipo ";
$sqlItem  .= "        inner join cadtipoitemgrupo on k09_cadtipoitemgrupo = k37_sequencial ";
$sqlItem  .= " where k09_cadtipo = $cadtipo";
$rsItem    = pg_query($sqlItem);
$intItem   = pg_numrows($rsItem);
//db_criatabela($rsItem);exit;
//echo "$sqlItem \n";
for($iItem=0;$iItem<$intItem;$iItem++){
  db_fieldsmemory($rsItem,$iItem);
//	echo "$k09_sequencial -- $k37_descr \n";
	$strRetorno .= $pipe.$k09_sequencial.$traco.$k37_descr;
	$pipe = "|";
}
 echo $strRetorno;

 /*
   $categorias = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
   $categorias .= "<noprincipal>\n";
   $categorias .= "   <dados tipo='numero_1'>\n";
   $categorias .= "      <id> 100 </id>\n";
   $categorias .= "      <nome> Robson  </nome>\n";
   $categorias .= "      <obs> teste </obs>\n";
   $categorias .= "   </dados>\n";
   $categorias .= "   <dados tipo='numero_2'>\n";
   $categorias .= "      <id> 200 </id>\n";
   $categorias .= "      <nome> teste200  </nome>\n";
   $categorias .= "      <obs> teste teste </obs>\n";
   $categorias .= "   </dados>\n";
   $categorias .= "   <dados tipo='numero_3'>\n";
   $categorias .= "      <id> 300 </id>\n";
   $categorias .= "      <nome> teste 300  </nome>\n";
   $categorias .= "      <obs> teste teste teste </obs>\n";
   $categorias .= "   </dados>\n";
   $categorias .= "</noprincipal>\n";
*/
?>