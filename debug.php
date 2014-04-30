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

// exibe o estado de uma classe

function debug($classe,$func=false){
  print_vars($classe);
  if ($func==true)
     print_methods($classe);
  
}
function print_vars($obj) {

  $arr = get_object_vars($obj);
  echo "<table border=0 bgcolor=#AAAAFF style='border:1px solid'>";
  echo "<tr><td colspan=3>Debug da classe ".get_class($obj)." </td></tr>";
  while (list($prop, $val) = each($arr))
     echo "<tr><td>&nbsp; </td><td align=left>var $$prop </td><td> $val </td>";
}

function print_methods($obj) {
   echo "<tr><td colspan=3>Metodos encontrados </td></tr>"; 
   $arr = get_class_methods(get_class($obj));
   foreach ($arr as $method)
     echo "<tr><td>&nbsp; </td><td colspan=2>function $method() </td>";

   echo "<table>";
}

?>