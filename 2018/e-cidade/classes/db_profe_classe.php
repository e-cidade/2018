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

//MODULO: recursoshumanos
//CLASSE DA ENTIDADE profe
class cl_profe { 
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
   var $h18_regist = 0; 
   var $h18_local = null; 
   var $h18_dtini_dia = null; 
   var $h18_dtini_mes = null; 
   var $h18_dtini_ano = null; 
   var $h18_dtini = null; 
   var $h18_dtfim_dia = null; 
   var $h18_dtfim_mes = null; 
   var $h18_dtfim_ano = null; 
   var $h18_dtfim = null; 
   var $h18_descat = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 h18_regist = int4 = Codigo do Funcionario 
                 h18_local = varchar(40) = Local de Trabalho 
                 h18_dtini = date = Data inicio 
                 h18_dtfim = date = Data fim 
                 h18_descat = varchar(120) = Descrição da Atividade 
                 ";
   //funcao construtor da classe 
   function cl_profe() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("profe"); 
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
       $this->h18_regist = ($this->h18_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["h18_regist"]:$this->h18_regist);
       $this->h18_local = ($this->h18_local == ""?@$GLOBALS["HTTP_POST_VARS"]["h18_local"]:$this->h18_local);
       if($this->h18_dtini == ""){
         $this->h18_dtini_dia = ($this->h18_dtini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["h18_dtini_dia"]:$this->h18_dtini_dia);
         $this->h18_dtini_mes = ($this->h18_dtini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["h18_dtini_mes"]:$this->h18_dtini_mes);
         $this->h18_dtini_ano = ($this->h18_dtini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["h18_dtini_ano"]:$this->h18_dtini_ano);
         if($this->h18_dtini_dia != ""){
            $this->h18_dtini = $this->h18_dtini_ano."-".$this->h18_dtini_mes."-".$this->h18_dtini_dia;
         }
       }
       if($this->h18_dtfim == ""){
         $this->h18_dtfim_dia = ($this->h18_dtfim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["h18_dtfim_dia"]:$this->h18_dtfim_dia);
         $this->h18_dtfim_mes = ($this->h18_dtfim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["h18_dtfim_mes"]:$this->h18_dtfim_mes);
         $this->h18_dtfim_ano = ($this->h18_dtfim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["h18_dtfim_ano"]:$this->h18_dtfim_ano);
         if($this->h18_dtfim_dia != ""){
            $this->h18_dtfim = $this->h18_dtfim_ano."-".$this->h18_dtfim_mes."-".$this->h18_dtfim_dia;
         }
       }
       $this->h18_descat = ($this->h18_descat == ""?@$GLOBALS["HTTP_POST_VARS"]["h18_descat"]:$this->h18_descat);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->h18_regist == null ){ 
       $this->erro_sql = " Campo Codigo do Funcionario nao Informado.";
       $this->erro_campo = "h18_regist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h18_local == null ){ 
       $this->erro_sql = " Campo Local de Trabalho nao Informado.";
       $this->erro_campo = "h18_local";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h18_dtini == null ){ 
       $this->erro_sql = " Campo Data inicio nao Informado.";
       $this->erro_campo = "h18_dtini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h18_dtfim == null ){ 
       $this->erro_sql = " Campo Data fim nao Informado.";
       $this->erro_campo = "h18_dtfim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h18_descat == null ){ 
       $this->erro_sql = " Campo Descrição da Atividade nao Informado.";
       $this->erro_campo = "h18_descat";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into profe(
                                       h18_regist 
                                      ,h18_local 
                                      ,h18_dtini 
                                      ,h18_dtfim 
                                      ,h18_descat 
                       )
                values (
                                $this->h18_regist 
                               ,'$this->h18_local' 
                               ,".($this->h18_dtini == "null" || $this->h18_dtini == ""?"null":"'".$this->h18_dtini."'")." 
                               ,".($this->h18_dtfim == "null" || $this->h18_dtfim == ""?"null":"'".$this->h18_dtfim."'")." 
                               ,'$this->h18_descat' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de Local de Trabalho em Sala de Aula      () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de Local de Trabalho em Sala de Aula      já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de Local de Trabalho em Sala de Aula      () nao Incluído. Inclusao Abortada.";
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
     $sql = " update profe set ";
     $virgula = "";
     if(trim($this->h18_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h18_regist"])){ 
       $sql  .= $virgula." h18_regist = $this->h18_regist ";
       $virgula = ",";
       if(trim($this->h18_regist) == null ){ 
         $this->erro_sql = " Campo Codigo do Funcionario nao Informado.";
         $this->erro_campo = "h18_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h18_local)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h18_local"])){ 
       $sql  .= $virgula." h18_local = '$this->h18_local' ";
       $virgula = ",";
       if(trim($this->h18_local) == null ){ 
         $this->erro_sql = " Campo Local de Trabalho nao Informado.";
         $this->erro_campo = "h18_local";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h18_dtini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h18_dtini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["h18_dtini_dia"] !="") ){ 
       $sql  .= $virgula." h18_dtini = '$this->h18_dtini' ";
       $virgula = ",";
       if(trim($this->h18_dtini) == null ){ 
         $this->erro_sql = " Campo Data inicio nao Informado.";
         $this->erro_campo = "h18_dtini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["h18_dtini_dia"])){ 
         $sql  .= $virgula." h18_dtini = null ";
         $virgula = ",";
         if(trim($this->h18_dtini) == null ){ 
           $this->erro_sql = " Campo Data inicio nao Informado.";
           $this->erro_campo = "h18_dtini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->h18_dtfim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h18_dtfim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["h18_dtfim_dia"] !="") ){ 
       $sql  .= $virgula." h18_dtfim = '$this->h18_dtfim' ";
       $virgula = ",";
       if(trim($this->h18_dtfim) == null ){ 
         $this->erro_sql = " Campo Data fim nao Informado.";
         $this->erro_campo = "h18_dtfim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["h18_dtfim_dia"])){ 
         $sql  .= $virgula." h18_dtfim = null ";
         $virgula = ",";
         if(trim($this->h18_dtfim) == null ){ 
           $this->erro_sql = " Campo Data fim nao Informado.";
           $this->erro_campo = "h18_dtfim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->h18_descat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h18_descat"])){ 
       $sql  .= $virgula." h18_descat = '$this->h18_descat' ";
       $virgula = ",";
       if(trim($this->h18_descat) == null ){ 
         $this->erro_sql = " Campo Descrição da Atividade nao Informado.";
         $this->erro_campo = "h18_descat";
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
       $this->erro_sql   = "Cadastro de Local de Trabalho em Sala de Aula      nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Local de Trabalho em Sala de Aula      nao foi Alterado. Alteracao Executada.\\n";
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
     $sql = " delete from profe
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
       $this->erro_sql   = "Cadastro de Local de Trabalho em Sala de Aula      nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Local de Trabalho em Sala de Aula      nao Encontrado. Exclusão não Efetuada.\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:profe";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>