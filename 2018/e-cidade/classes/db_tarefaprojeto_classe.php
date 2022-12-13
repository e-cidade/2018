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

//MODULO: atendimento
//CLASSE DA ENTIDADE tarefaprojeto
class cl_tarefaprojeto { 
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
   var $at41_tarefa = 0; 
   var $at41_projeto = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 at41_tarefa = int4 = Sequencial 
                 at41_projeto = int4 = Codigo do projeto 
                 ";
   //funcao construtor da classe 
   function cl_tarefaprojeto() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tarefaprojeto"); 
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
       $this->at41_tarefa = ($this->at41_tarefa == ""?@$GLOBALS["HTTP_POST_VARS"]["at41_tarefa"]:$this->at41_tarefa);
       $this->at41_projeto = ($this->at41_projeto == ""?@$GLOBALS["HTTP_POST_VARS"]["at41_projeto"]:$this->at41_projeto);
     }else{
       $this->at41_tarefa = ($this->at41_tarefa == ""?@$GLOBALS["HTTP_POST_VARS"]["at41_tarefa"]:$this->at41_tarefa);
       $this->at41_projeto = ($this->at41_projeto == ""?@$GLOBALS["HTTP_POST_VARS"]["at41_projeto"]:$this->at41_projeto);
     }
   }
   // funcao para inclusao
   function incluir ($at41_tarefa,$at41_projeto){ 
      $this->atualizacampos();
       $this->at41_tarefa = $at41_tarefa; 
       $this->at41_projeto = $at41_projeto;
     if(($this->at41_tarefa == null) || ($this->at41_tarefa == "") ){ 
       $this->erro_sql = " Campo at41_tarefa nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->at41_projeto == null) || ($this->at41_projeto == "") ){ 
       $this->erro_sql = " Campo at41_projeto nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tarefaprojeto(
                                       at41_tarefa 
                                      ,at41_projeto 
                       )
                values (
                                $this->at41_tarefa 
                               ,$this->at41_projeto 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "projeto da tarefa ($this->at41_tarefa."-".$this->at41_projeto) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "projeto da tarefa já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "projeto da tarefa ($this->at41_tarefa."-".$this->at41_projeto) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at41_tarefa."-".$this->at41_projeto;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->at41_tarefa,$this->at41_projeto));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,8099,'$this->at41_tarefa','I')");
       $resac = pg_query("insert into db_acountkey values($acount,8100,'$this->at41_projeto','I')");
       $resac = pg_query("insert into db_acount values($acount,1366,8099,'','".AddSlashes(pg_result($resaco,0,'at41_tarefa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1366,8100,'','".AddSlashes(pg_result($resaco,0,'at41_projeto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($at41_tarefa=null,$at41_projeto=null) { 
      $this->atualizacampos();
     $sql = " update tarefaprojeto set ";
     $virgula = "";
     if(trim($this->at41_tarefa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at41_tarefa"])){ 
       $sql  .= $virgula." at41_tarefa = $this->at41_tarefa ";
       $virgula = ",";
       if(trim($this->at41_tarefa) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "at41_tarefa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at41_projeto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at41_projeto"])){ 
       $sql  .= $virgula." at41_projeto = $this->at41_projeto ";
       $virgula = ",";
       if(trim($this->at41_projeto) == null ){ 
         $this->erro_sql = " Campo Codigo do projeto nao Informado.";
         $this->erro_campo = "at41_projeto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($at41_tarefa!=null){
       $sql .= " at41_tarefa = $this->at41_tarefa";
     }
     if($at41_projeto!=null){
       $sql .= " and  at41_projeto = $this->at41_projeto";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->at41_tarefa,$this->at41_projeto));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,8099,'$this->at41_tarefa','A')");
         $resac = pg_query("insert into db_acountkey values($acount,8100,'$this->at41_projeto','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at41_tarefa"]))
           $resac = pg_query("insert into db_acount values($acount,1366,8099,'".AddSlashes(pg_result($resaco,$conresaco,'at41_tarefa'))."','$this->at41_tarefa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at41_projeto"]))
           $resac = pg_query("insert into db_acount values($acount,1366,8100,'".AddSlashes(pg_result($resaco,$conresaco,'at41_projeto'))."','$this->at41_projeto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "projeto da tarefa nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->at41_tarefa."-".$this->at41_projeto;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "projeto da tarefa nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->at41_tarefa."-".$this->at41_projeto;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at41_tarefa."-".$this->at41_projeto;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($at41_tarefa=null,$at41_projeto=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($at41_tarefa,$at41_projeto));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,8099,'$at41_tarefa','E')");
         $resac = pg_query("insert into db_acountkey values($acount,8100,'$at41_projeto','E')");
         $resac = pg_query("insert into db_acount values($acount,1366,8099,'','".AddSlashes(pg_result($resaco,$iresaco,'at41_tarefa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1366,8100,'','".AddSlashes(pg_result($resaco,$iresaco,'at41_projeto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tarefaprojeto
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($at41_tarefa != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " at41_tarefa = $at41_tarefa ";
        }
        if($at41_projeto != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " at41_projeto = $at41_projeto ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "projeto da tarefa nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$at41_tarefa."-".$at41_projeto;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "projeto da tarefa nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$at41_tarefa."-".$at41_projeto;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$at41_tarefa."-".$at41_projeto;
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
     $result = @pg_query($sql);
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
        $this->erro_sql   = "Record Vazio na Tabela:tarefaprojeto";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $at41_tarefa=null,$at41_projeto=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from tarefaprojeto ";
     $sql .= "      inner join db_projetos  on  db_projetos.at30_codigo = tarefaprojeto.at41_projeto";
     $sql .= "      inner join tarefa  on  tarefa.at40_sequencial = tarefaprojeto.at41_tarefa";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = db_projetos.at30_responsavel";
     $sql .= "      inner join db_projetosituacao  on  db_projetosituacao.at32_codigo = db_projetos.at30_situacao";
     $sql .= "      inner join db_usuarios  as a on   a.id_usuario = tarefa.at40_responsavel";
     $sql2 = "";
     if($dbwhere==""){
       if($at41_tarefa!=null ){
         $sql2 .= " where tarefaprojeto.at41_tarefa = $at41_tarefa "; 
       } 
       if($at41_projeto!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " tarefaprojeto.at41_projeto = $at41_projeto "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   // funcao do sql 
   function sql_query_file ( $at41_tarefa=null,$at41_projeto=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from tarefaprojeto ";
     $sql2 = "";
     if($dbwhere==""){
       if($at41_tarefa!=null ){
         $sql2 .= " where tarefaprojeto.at41_tarefa = $at41_tarefa "; 
       } 
       if($at41_projeto!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " tarefaprojeto.at41_projeto = $at41_projeto "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
?>