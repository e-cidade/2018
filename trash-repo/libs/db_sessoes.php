<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

$sess = 0;
if(!session_is_registered("DB_modulo"))
  $sess = 1;
if(!session_is_registered("DB_nome_modulo"))
  $sess = 1;
if(!session_is_registered("DB_anousu"))
  $sess = 1;
if(!session_is_registered("DB_instit"))
  $sess = 1;
if(!session_is_registered("DB_uol_hora"))
  $sess = 1;
if($sess == 1) {
  session_destroy();
  echo "Sess�o Inv�lida!(14)<br>Feche seu navegador e fa�a login novamente.<Br>\n";
  exit;
}

/*$arquivo = pg_exec("select id_item,funcao as arquivo
                    from db_itensmenu
		    where funcao like '".basename($HTTP_SERVER_VARS["SCRIPT_FILENAME"])."%'"); 
$numrows = pg_numrows($arquivo);
if($numrows > 0) {
  $str = "";
  $c = "";
  for($i = 0;$i < $numrows;$i++) {
    $str .= $c.pg_result($arquivo,$i,"id_item");
    $c = ",";
  }
  /*
  $result = pg_exec("select id_item
  		   from db_permissao
  		   where ( id_usuario = ".db_getsession("DB_id_usuario")." or id_usuario in (select id_perfil from db_permherda where id_usuario = ".db_getsession("DB_id_usuario")."))
 		   and anousu = ".db_getsession("DB_anousu")."
 		   and id_item in(".$str.")"); 
  if(pg_numrows($result) == 0) {
      // para o usuario dbseller, n�o destroy a se��o...
      if (db_getsession("DB_id_usuario")!=1)
         session_destroy();
      
    ?>
	<html>
    <body>
      <CENTER><BR><BR><BR>
        <h1>ACESSO NAO PERMITIDO</h1>
      </CENTER>
    </body>
    </html>
    <?
    exit;
  }
  
}*/
?>