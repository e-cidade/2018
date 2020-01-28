<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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


$sScriptName = basename(__FILE__);

db_query("select fc_startsession();") or die("$sScriptName: Erro ao iniciar sessão no banco de dados");
// criar usuario

db_query("begin") or die("$sScriptName: Erro ao iniciar transação");

$sqlprocura = "select * from db_usuarios where id_usuario = 1";
$resultprocura = db_query($sqlprocura) or die("$sScriptName: $sqlprocura");

if (pg_numrows($resultprocura) == 0) {

  $sql = "insert into db_usuarios (id_usuario , nome , login , senha , usuarioativo , email , usuext)
		       values     (1,'DBSeller Informática Ltda','dbseller','" . Encriptacao::encriptaSenha('') . "','1','dbseller#dbseller.com.br',0)";
   //                    values     (nextval('db_usuarios_id_usuario_seq'),'DBSeller Informática Ltda','dbseller','','t','dbseller#dbseller.com.br',0)";

  $res = db_query($sql) or die("$sScriptName: $sql");
}

// cria cgm
$sqlprocura = "select * from cgm where z01_numcgm = 1";
$resultprocura = db_query($sqlprocura) or die("$sScriptName: $sqlprocura");

if (pg_numrows($resultprocura) == 0) {
  $sql = "insert into cgm (z01_numcgm , z01_nome )
		      values    (1,'PREFEITURA DBSELLER')";
  $res = db_query($sql) or die("$sScriptName: $sql");
}

// criar instituicao
$sqlprocura = "select * from db_config where codigo = 1";
$resultprocura = db_query($sqlprocura) or die("$sScriptName: $sqlprocura");

if (pg_numrows($resultprocura) == 0) {
  $sql = "insert into db_config (codigo , nomeinst,  prefeitura , numcgm )
		                     values (1, 'PREFEITURA DBSELLER', true, 1)";
  $res = db_query($sql) or die("$sScriptName: $sql");
}

// criar ligacao instituicao com usuario (db_userinst)
$sqlprocura = "select * from db_userinst where id_instit = 1 and id_usuario = 1";
$resultprocura = db_query($sqlprocura) or die("$sScriptName: $sqlprocura");

if (pg_numrows($resultprocura) == 0) {
  $sql = "insert into db_userinst (id_instit, id_usuario)
		      values    (1, 1)";
  $res = db_query($sql) or die("$sScriptName: $sql");
}

// criar ligacao usuario com cgm (db_usuacgm)
$sqlprocura = "select * from db_usuacgm where id_usuario = 1";
$resultprocura = db_query($sqlprocura) or die("$sScriptName: $sqlprocura");

if (pg_numrows($resultprocura) == 0) {
  $sql = "insert into db_usuacgm (id_usuario, cgmlogin)
		      values    (1, 1)";
  $res = db_query($sql) or die("$sScriptName: $sql");
}

// criar departamento
$sqlprocura = "select * from db_depart where coddepto = 1";
$resultprocura = db_query($sqlprocura) or die("$sScriptName: $sqlprocura");

if (pg_numrows($resultprocura) == 0) {
	$sql = "insert into db_depart (coddepto , descrdepto ,instit)
											values    (1,'CPD',1)";
	$res = db_query($sql) or die("$sScriptName: $sql");
}

// criar db_deptousu
$sql = "insert into db_depusu(id_usuario , coddepto)
                    values    (1,1)";
$res = db_query($sql) or die("$sScriptName: $sql");


// liberar acesso ao sistema
$sql = " insert into db_sysregrasacesso values (0,'2000-01-01','00:00','2999-01-01','24:00',1,current_date,'implantação')";
$res = db_query($sql) or die("$sScriptName: $sql");

$sql = "insert into db_sysregrasacessoip values (0,'*')";
$res = db_query($sql) or die("$sScriptName: $sql");
//


db_query("commit") or die("$sScriptName: Erro ao finalizar transação");

die("ATENÇÃO!! feche todas janelas e acesse novamente o sistema...")

?>