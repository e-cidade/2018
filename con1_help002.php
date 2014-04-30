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
include("classes/db_db_modulos_classe.php");

$cldb_modulos = new cl_db_modulos ;

$id_modulo = db_getsession("DB_modulo");

$result = $cldb_modulos->sql_record($cldb_modulos->sql_query_file($id_modulo));

if($result==false || $cldb_modulos->numrows ==0){
  echo "Módulo não definido. Contate Suporte";
  exit;
}
db_fieldsmemory($result,0,0);

if( file_exists("manuais/".strtolower($nome_manual)."/index.php") ){

  include("manuais/".strtolower($nome_manual)."/index.php");

  echo '<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>';

  echo '<script>
  
  function js_alerta(){
    js_OpenJanelaIframe("parent.parent","db_iframe_imagem","manuais/index.php?imagem="+this.src+"&modulo='.strtolower($nome_manual).'","Pesquisa",true);
  }
        numero = document.images.length;
        for( i=0; i<numero;i++){
          document.images[i].width = 10;
          document.images[i].height = 15;
          document.images[i].onclick = js_alerta;
          document.images[i].title = "Clique na Figura para Visualizar.";
        } 
        </script>';


}else{

  //include("manuais/index.php");

}
?>