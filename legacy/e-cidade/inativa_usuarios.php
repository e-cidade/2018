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
require("libs/db_conn.php");
require("classes/db_db_usuarios_classe.php");
include("dbforms/db_funcoes.php");
// Salva sessao php, variavel $_SESSION, na base de dados
require_once("libs/db_libsession.php");

session_start();

//envia parametros pelo console. ex.: php inativa_usuarios.php 1 30
if($argc > 1) {

  if(($argv[1] == "--help") or ($argv[2] == "") or (isset($argv[3]) and ($argv[3] != ''))){
    echo "nome_do_programa.php [numero_instituicao(oes) ex.: 1,2] [dias_sem_uso]\n";
    exit;
  }else {
    $DB_instit       = $argv[1];
    $dias_permitidos = $argv[2];
  }
}else {
  $DB_instit       =  "1";
  $dias_permitidos = 30;
  
}

db_putsession("DB_acessado", "5292");
db_putsession("DB_datausu", date("Y-m-d"));
db_putsession("DB_id_usuario", "1");

//echo "BASE: $DB_BASE  SERVIDOR: $DB_SERVIDOR  PORTA: $DB_PORTA  USUARIO: $DB_USUARIO\n";

if(!$conn = @pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO")){
  echo "Erro ao conectar base de dados.\n";
  exit;
}

$sqlsessao    = "select fc_startsession();";
$resultsessao = pg_query($conn, $sqlsessao);

$aInstit = explode(",", $DB_instit);
$iLinhas = count($aInstit);

for($z=0; $z<$iLinhas; $z++) {

  db_putsession("DB_instit", $aInstit[$z]);
  db_savesession($conn, $_SESSION);
  
  echo "PROCESSANDO INSTITUIÇÃO {$aInstit[$z]}\n";

  $cldbusuariosativos = new cl_db_usuarios;
  $sql  = "select distinct a.* ";
  $sql .= "  from db_usuarios a ";
  $sql .= " inner join db_userinst b on b.id_usuario = a.id_usuario ";
  $sql .= " where a.usuarioativo = '1' and ";
  $sql .= "  			a.usuext       =  0  and ";
  $sql .= "  			b.id_instit    = {$aInstit[$i]} ";

  $resultLogin  = $cldbusuariosativos->sql_record($sql);
  $numrowsLogin = $cldbusuariosativos->numrows;

  if($numrowsLogin > 0) {
    db_inicio_transacao();
    $contador = 0;
    
    for($i=0; $i<$numrowsLogin; $i++) { 
      
      db_fieldsmemory($resultLogin, $i);

      $sql  = "select coalesce(current_date - max(data), 0) as dias_sem_login ";
      $sql .= "  from ((select data, id_usuario ";
      $sql .= "           from db_logsacessa ";
      $sql .= "          where obs like 'Abrindo Acesso ao Sistema%$login%' ";
      $sql .= "          order by data desc, hora desc ";
      $sql .= "          limit 1)";
      $sql .= "         union ";
      $sql .= "        (select data, id_usuario ";
      $sql .= "           from db_logsacessa ";
      $sql .= "          where obs like 'Acesso ao Sistema Efetuado com Sucesso%$login%' ";
      $sql .= "          order by data desc, hora desc ";
      $sql .= "          limit 1)) as login";

      $resultDias  = $cldbusuariosativos->sql_record($sql);

      $numrowsDias = $cldbusuariosativos->numrows;

      if($numrowsDias > 0) {

        db_fieldsmemory($resultDias, 0);

        if($dias_sem_login > $dias_permitidos) {
          //resultLogin
          $cldbusuariosativos->id_usuario     = $id_usuario;
          $cldbusuariosativos->nome           = $nome;
          $cldbusuariosativos->login          = $login;
          $cldbusuariosativos->senha          = $senha;
          $cldbusuariosativos->usuarioativo   = "0";
          $cldbusuariosativos->email          = $email;
          $cldbusuariosativos->usuext         = $usuext;
          $cldbusuariosativos->administrador  = $administrador;
          $cldbusuariosativos->alterar($id_usuario);

          if($cldbusuariosativos->erro_status == "1"){
            echo "ATUALIZACAO EFETUADA COM SUCESSO. LOGIN - $login / CODIGO DO USUARIO - $id_usuario\n";
            $contador++;
            
          }else {
            echo "PROBLEMAS NO PROCESSAMENTO. EXECUCAO ABORTADA. LOGIN - $login / CODIGO DO USUARIO - $id_usuario\n";
            db_fim_transacao(true);
            echo "$cldbusuariosativos->erro_msg\n";
            exit;
            
          }
        }
      }
    }
    if($contador==0){
      echo "NENHUM REGISTRO ATUALIZADO.\n";
      
    }
    db_fim_transacao();
  }else {
    echo "NENHUM REGISTRO ENCONTRADO.\n";
    
  }
}



?>