<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
$path = "n/";
if ($dir = opendir($path)) {
  while (($file = readdir($dir)) !== false) {
    if($file !== ".." && $file !== "."){
      if(is_file($path.$file)){
        $arq['file'][] = $file;
        $arq['f_date'][] = filemtime($path.$file);
        clearstatcache();
      }
    }
  }
 closedir($dir);
}

arsort($arq['f_date']);
reset($arq);
foreach ($arq['f_date'] as $key => $value){
  $nome = explode('.',$arq['file'][$key]);
  print "<a href=\"consulta.php?n=".$arq['file'][$key]."\"><font 
color=000000 size=1 face=verdana>".$nome
[0]."</font></a><br>\n";
}
?>