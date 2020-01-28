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

global $conn;
$result = db_query("select descricao from db_itensmenu where funcao = '".basename($_SERVER['PHP_SELF'])."'");
if(pg_numrows($result) > 0)
  $str = pg_result($result,0,0);
else
  $str = basename($_SERVER['PHP_SELF']);

$result = db_query("select uol_id from db_usuariosonline 
  where uol_id = ".db_getsession("DB_id_usuario")."
  and uol_ip = '".(isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]:$_SERVER['REMOTE_ADDR'])."' 
  and uol_hora = ".db_getsession("DB_uol_hora"));
if(pg_numrows($result) == 0) {
  $hora = time();
  db_query($conn,"insert into db_usuariosonline 
    values(".db_getsession("DB_id_usuario").",
      ".$hora.",
      '".(isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]:$_SERVER['REMOTE_ADDR'])."',            
      '".db_getsession("DB_login")."',
      '".$str."',
      '".db_getsession("DB_nome_modulo")."',
      ".time().")") or die("Erro:(27) inserindo arquivo em db_usuariosonline: ".pg_errormessage());
  db_putsession("DB_uol_hora",$hora);
} else {
  db_query("update db_usuariosonline set  
    uol_arquivo = '".$str."',
    uol_inativo = ".time()."
    where uol_id = ".db_getsession("DB_id_usuario")."
    and uol_ip = '".(isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]:$_SERVER['REMOTE_ADDR'])."' 
    and uol_hora = ".db_getsession("DB_uol_hora")."
    ") or die("Erro(26) atualizando db_usuariosonline");
}
?>