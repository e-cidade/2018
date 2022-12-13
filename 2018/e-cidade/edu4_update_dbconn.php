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

umask(74);
$arquivo_conn = "tmp/db_conn.php";
$fd1 = fopen($arquivo_conn,"w");
fputs($fd1,"<?\n");
fputs($fd1,'$DB_COR_FUNDO = "#00CCFF";'."\n");
fputs($fd1,'$DB_FILES = "/dbportal2/imagens/files";'."\n");
fputs($fd1,'$DB_DIRPCB = "/home/sistema";'."\n");
fputs($fd1,'$DB_EXEC = "/usr/bin/dbs";'."\n");
fputs($fd1,'$DB_NETSTAT = "netstat";'."\n");
fputs($fd1,'$DB_USUARIO = "postgres";'."\n");
fputs($fd1,'$DB_SENHA = "";'."\n");
fputs($fd1,'$DB_SERVIDOR = "'.$DB_SERVIDOR.'";'."\n");
fputs($fd1,'$DB_BASE = "'.$base_destino.'";'."\n");
fputs($fd1,'$DB_BASE_CORRETA = "";'."\n");
fputs($fd1,'$DB_PORTA = "'.$DB_PORTA.'";'."\n");
fputs($fd1,'$DB_SELLER = "on";'."\n");
fputs($fd1,'?>'."\n");
fclose($fd1);
system("rm libs/db_conn.php");
if(!@copy($arquivo_conn,"libs/db_conn.php")){
 echo "ERRO copiando arquivo conexão";
 exit;
}
?>