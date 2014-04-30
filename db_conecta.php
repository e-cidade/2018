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
if(!session_id())
  session_start();
if(!session_is_registered("DB_login") || !session_is_registered("DB_id_usuario")) {
  session_destroy();
  header("Location: db_erros.php?db_erro=Você esta acessando uma página que não possui sessão aberta com o servidor. Contate Administrador.");
  exit;
}
require("db_conn.php");
if(session_is_registered("DB_servidor") and
   session_is_registered("DB_base") and
   session_is_registered("DB_user") and
   session_is_registered("DB_porta") and
   session_is_registered("DB_senha") ){
		$DB_SERVIDOR = db_getsession("DB_servidor");
    $DB_BASE = db_getsession("DB_base");
		$DB_PORTA = db_getsession("DB_porta");
		$DB_USUARIO =db_getsession("DB_user");
		$DB_SENHA = db_getsession("DB_senha");   
}
// Valida Utilizacao da Porta de Conexao Alternativa com o PostgreSQL
// para nao passar pelo Pool de Conexoes (PgBouncer)
if(isset($DB_PORTA_ALT)) {
  // Array com programas que farao uso da porta de conexao alternativa
  $aProgramaConnAlternativa = array();

  // Modulo Caixa
  $aProgramaConnAlternativa[] = "cai4_baixabanco006.php";
  $aProgramaConnAlternativa[] = "cai3_gerfinanc062.php";
  
  // Modulo Cadastro 
  $aProgramaConnAlternativa[] = "cad4_calciptu.php";
  $aProgramaConnAlternativa[] = "cad4_calciptugeral.php";

  // Modulo Agua
  $aProgramaConnAlternativa[] = "agu1_aguabase004.php";
  $aProgramaConnAlternativa[] = "agu1_aguabase005.php";
  $aProgramaConnAlternativa[] = "agu1_aguabase006.php";

  // Modulo Fiscal
  $aProgramaConnAlternativa[] = "fis1_sanicalc001.php";
  $aProgramaConnAlternativa[] = "fis4_vistgeral001.php";
  $aProgramaConnAlternativa[] = "fis1_calculo001.php";

  // Modulo Issqn
  $aProgramaConnAlternativa[] = "iss1_tabativbaixa004.php";
  $aProgramaConnAlternativa[] = "calculoissqnfixo.php";
  $aProgramaConnAlternativa[] = "iss4_calcissgeral002.php";
  $aProgramaConnAlternativa[] = "iss1_isscalc004.php";
  $aProgramaConnAlternativa[] = "iss4_isscalc001.php";
  $aProgramaConnAlternativa[] = "iss1_tabativbaixacancela004.php";

  // Nome do programa atual
  $sProgramaAtual = basename($_SERVER["SCRIPT_NAME"]);

  // Verifica se programa atual consta na lista para setar a porta alternativa
  if(in_array($sProgramaAtual, $aProgramaConnAlternativa)){
    $DB_PORTA = $DB_PORTA_ALT;
  }
}
if(session_is_registered("DB_NBASE")){
	$DB_BASE = $HTTP_SESSION_VARS["DB_NBASE"];
}
if(session_is_registered("DB_servidor")){
	$DB_SERVIDOR = $HTTP_SESSION_VARS["DB_servidor"];
}
if(!($conn = @pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA"))) {
  session_destroy();
  db_redireciona("db_erros.php?db_erro=A conexão com o Servidor de dados falhou. Contate Administrador.");
  exit;
}
// Salva sessao php, variavel $_SESSION, na base de dados
require_once("db_libsession.php");
db_savesession($conn, $_SESSION);
if (db_getsession("DB_id_usuario") != 1 && db_getsession("DB_administrador") != 1 ){
  $result1 = pg_exec($conn,"select db21_ativo from db_config where prefeitura = true") or die("Erro ao verificar se sistema está liberado! Contate suporte!");
  $ativo = pg_result($result1,0,0);
  if($ativo == 3){
    session_destroy();
    db_redireciona("db_erros.php?db_erro=O sistema foi desativado pelo Administrador. Contate Administrador.");
    exit;
  }
}
require_once("db_acessa.php");
if(!defined("DB_VERSION")){
 define("DB_VERSION",$db_fonte_codrelease);
}
db_logs();
?>
