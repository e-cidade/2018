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

      db_postmemory($HTTP_POST_VARS);
      if(!empty($k11_id)) {
        $result = pg_exec("select k11_id from cfautent where k11_id = $k11_id");
	    if(pg_numrows($result) > 0) {
 	      db_redireciona("cai1_autenticadora002.php?".base64_encode("retorno=".pg_result($result,0,0)));
	      exit;
	    } else {
          $sql = "select k11_id as db_codigo,k11_id as Código,k11_ipterm as \"Ip/Term\",k11_local as Local from cfautent where k11_id like '".$k11_id."%' and k11_instit = " . db_getsession("DB_instit");
	    }
      } else if(!empty($k11_ipterm)) {
        $sql = "select k11_id as db_codigo,k11_ipterm as \"Ip/Term\",k11_id as Código,k11_local as Local from cfautent where upper(k11_ipterm) like upper('".$k11_ipterm."%') and k11_instit = " . db_getsession("DB_instit");
      } else {
        $sql = "select k11_id as db_codigo,k11_local as Local,k11_id as Código,k11_ipterm as \"Ip/Term\" from cfautent where upper(k11_local) like upper('".$k11_local."%') and k11_instit = " . db_getsession("DB_instit");
      }
	  echo "<center>";
      db_lov($sql,15,"cai1_autenticadora002.php");
	  echo "</center>";
?>