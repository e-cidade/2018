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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_usuariosonline
class cl_db_usuariosonline { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $uol_id = 0; 
   var $uol_hora = 0; 
   var $uol_ip = null; 
   var $uol_login = null; 
   var $uol_arquivo = null; 
   var $uol_modulo = null; 
   var $uol_inativo = 0; 
   var $uol_chat = null; 
   var $uol_sol = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 uol_id = int4 = Cod. Usuário 
                 uol_hora = int8 = Hora 
                 uol_ip = varchar(50) = IP 
                 uol_login = char(10) = Login 
                 uol_arquivo = varchar(400) = Arquivo 
                 uol_modulo = varchar(100) = Módulo 
                 uol_inativo = int8 = Inativo 
                 uol_chat = text = Chat 
                 uol_sol = varchar(100) = Sol 
                 ";
   //funcao construtor da classe 
   function cl_db_usuariosonline() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_usuariosonline"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->uol_id = ($this->uol_id == ""?@$GLOBALS["HTTP_POST_VARS"]["uol_id"]:$this->uol_id);
       $this->uol_hora = ($this->uol_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["uol_hora"]:$this->uol_hora);
       $this->uol_ip = ($this->uol_ip == ""?@$GLOBALS["HTTP_POST_VARS"]["uol_ip"]:$this->uol_ip);
       $this->uol_login = ($this->uol_login == ""?@$GLOBALS["HTTP_POST_VARS"]["uol_login"]:$this->uol_login);
       $this->uol_arquivo = ($this->uol_arquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["uol_arquivo"]:$this->uol_arquivo);
       $this->uol_modulo = ($this->uol_modulo == ""?@$GLOBALS["HTTP_POST_VARS"]["uol_modulo"]:$this->uol_modulo);
       $this->uol_inativo = ($this->uol_inativo == ""?@$GLOBALS["HTTP_POST_VARS"]["uol_inativo"]:$this->uol_inativo);
       $this->uol_chat = ($this->uol_chat == ""?@$GLOBALS["HTTP_POST_VARS"]["uol_chat"]:$this->uol_chat);
       $this->uol_sol = ($this->uol_sol == ""?@$GLOBALS["HTTP_POST_VARS"]["uol_sol"]:$this->uol_sol);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->uol_id == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "uol_id";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->uol_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "uol_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->uol_ip == null ){ 
       $this->erro_sql = " Campo IP nao Informado.";
       $this->erro_campo = "uol_ip";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->uol_login == null ){ 
       $this->erro_sql = " Campo Login nao Informado.";
       $this->erro_campo = "uol_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->uol_arquivo == null ){ 
       $this->erro_sql = " Campo Arquivo nao Informado.";
       $this->erro_campo = "uol_arquivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->uol_modulo == null ){ 
       $this->erro_sql = " Campo Módulo nao Informado.";
       $this->erro_campo = "uol_modulo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->uol_inativo == null ){ 
       $this->erro_sql = " Campo Inativo nao Informado.";
       $this->erro_campo = "uol_inativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->uol_chat == null ){ 
       $this->erro_sql = " Campo Chat nao Informado.";
       $this->erro_campo = "uol_chat";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->uol_sol == null ){ 
       $this->erro_sql = " Campo Sol nao Informado.";
       $this->erro_campo = "uol_sol";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_usuariosonline(
                                       uol_id 
                                      ,uol_hora 
                                      ,uol_ip 
                                      ,uol_login 
                                      ,uol_arquivo 
                                      ,uol_modulo 
                                      ,uol_inativo 
                                      ,uol_chat 
                                      ,uol_sol 
                       )
                values (
                                $this->uol_id 
                               ,$this->uol_hora 
                               ,'$this->uol_ip' 
                               ,'$this->uol_login' 
                               ,'$this->uol_arquivo' 
                               ,'$this->uol_modulo' 
                               ,$this->uol_inativo 
                               ,'$this->uol_chat' 
                               ,'$this->uol_sol' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Usuários on-line () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Usuários on-line já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Usuários on-line () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     return true;
   } 
   // funcao para alteracao
   function alterar ( $oid=null ) { 
      $this->atualizacampos();
     $sql = " update db_usuariosonline set ";
     $virgula = "";
     if(trim($this->uol_id)!="" || isset($GLOBALS["HTTP_POST_VARS"]["uol_id"])){ 
       $sql  .= $virgula." uol_id = $this->uol_id ";
       $virgula = ",";
       if(trim($this->uol_id) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "uol_id";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->uol_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["uol_hora"])){ 
       $sql  .= $virgula." uol_hora = $this->uol_hora ";
       $virgula = ",";
       if(trim($this->uol_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "uol_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->uol_ip)!="" || isset($GLOBALS["HTTP_POST_VARS"]["uol_ip"])){ 
       $sql  .= $virgula." uol_ip = '$this->uol_ip' ";
       $virgula = ",";
       if(trim($this->uol_ip) == null ){ 
         $this->erro_sql = " Campo IP nao Informado.";
         $this->erro_campo = "uol_ip";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->uol_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["uol_login"])){ 
       $sql  .= $virgula." uol_login = '$this->uol_login' ";
       $virgula = ",";
       if(trim($this->uol_login) == null ){ 
         $this->erro_sql = " Campo Login nao Informado.";
         $this->erro_campo = "uol_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->uol_arquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["uol_arquivo"])){ 
       $sql  .= $virgula." uol_arquivo = '$this->uol_arquivo' ";
       $virgula = ",";
       if(trim($this->uol_arquivo) == null ){ 
         $this->erro_sql = " Campo Arquivo nao Informado.";
         $this->erro_campo = "uol_arquivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->uol_modulo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["uol_modulo"])){ 
       $sql  .= $virgula." uol_modulo = '$this->uol_modulo' ";
       $virgula = ",";
       if(trim($this->uol_modulo) == null ){ 
         $this->erro_sql = " Campo Módulo nao Informado.";
         $this->erro_campo = "uol_modulo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->uol_inativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["uol_inativo"])){ 
       $sql  .= $virgula." uol_inativo = $this->uol_inativo ";
       $virgula = ",";
       if(trim($this->uol_inativo) == null ){ 
         $this->erro_sql = " Campo Inativo nao Informado.";
         $this->erro_campo = "uol_inativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->uol_chat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["uol_chat"])){ 
       $sql  .= $virgula." uol_chat = '$this->uol_chat' ";
       $virgula = ",";
       if(trim($this->uol_chat) == null ){ 
         $this->erro_sql = " Campo Chat nao Informado.";
         $this->erro_campo = "uol_chat";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->uol_sol)!="" || isset($GLOBALS["HTTP_POST_VARS"]["uol_sol"])){ 
       $sql  .= $virgula." uol_sol = '$this->uol_sol' ";
       $virgula = ",";
       if(trim($this->uol_sol) == null ){ 
         $this->erro_sql = " Campo Sol nao Informado.";
         $this->erro_campo = "uol_sol";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
$sql .= "oid = '$oid'";     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Usuários on-line nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Usuários on-line nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ( $oid=null ,$dbwhere=null) { 
     $sql = " delete from db_usuariosonline
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
       $sql2 = "oid = '$oid'";
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Usuários on-line nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Usuários on-line nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:db_usuariosonline";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>