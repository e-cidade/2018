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
//CLASSE DA ENTIDADE workflowativdb_cadattdinamico
class cl_workflowativdb_cadattdinamico { 
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
   var $db117_sequencial = 0; 
   var $db117_db_cadattdinamico = 0; 
   var $db117_workflowativ = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db117_sequencial = int4 = Código Sequencial 
                 db117_db_cadattdinamico = int4 = Código Atributo Dinâmico 
                 db117_workflowativ = int4 = Código Work Flow Atividade 
                 ";
   //funcao construtor da classe 
   function cl_workflowativdb_cadattdinamico() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("workflowativdb_cadattdinamico"); 
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
       $this->db117_sequencial = ($this->db117_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db117_sequencial"]:$this->db117_sequencial);
       $this->db117_db_cadattdinamico = ($this->db117_db_cadattdinamico == ""?@$GLOBALS["HTTP_POST_VARS"]["db117_db_cadattdinamico"]:$this->db117_db_cadattdinamico);
       $this->db117_workflowativ = ($this->db117_workflowativ == ""?@$GLOBALS["HTTP_POST_VARS"]["db117_workflowativ"]:$this->db117_workflowativ);
     }else{
       $this->db117_sequencial = ($this->db117_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db117_sequencial"]:$this->db117_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($db117_sequencial){ 
      $this->atualizacampos();
     if($this->db117_db_cadattdinamico == null ){ 
       $this->erro_sql = " Campo Código Atributo Dinâmico nao Informado.";
       $this->erro_campo = "db117_db_cadattdinamico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db117_workflowativ == null ){ 
       $this->erro_sql = " Campo Código Work Flow Atividade nao Informado.";
       $this->erro_campo = "db117_workflowativ";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db117_sequencial == "" || $db117_sequencial == null ){
       $result = db_query("select nextval('workflowativdb_cadattdinamico_db117_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: workflowativdb_cadattdinamico_db117_sequencial_seq do campo: db117_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db117_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from workflowativdb_cadattdinamico_db117_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db117_sequencial)){
         $this->erro_sql = " Campo db117_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db117_sequencial = $db117_sequencial; 
       }
     }
     if(($this->db117_sequencial == null) || ($this->db117_sequencial == "") ){ 
       $this->erro_sql = " Campo db117_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into workflowativdb_cadattdinamico(
                                       db117_sequencial 
                                      ,db117_db_cadattdinamico 
                                      ,db117_workflowativ 
                       )
                values (
                                $this->db117_sequencial 
                               ,$this->db117_db_cadattdinamico 
                               ,$this->db117_workflowativ 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "workflowativdb_cadattdinamico ($this->db117_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "workflowativdb_cadattdinamico já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "workflowativdb_cadattdinamico ($this->db117_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db117_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db117_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17895,'$this->db117_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3160,17895,'','".AddSlashes(pg_result($resaco,0,'db117_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3160,17896,'','".AddSlashes(pg_result($resaco,0,'db117_db_cadattdinamico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3160,17897,'','".AddSlashes(pg_result($resaco,0,'db117_workflowativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db117_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update workflowativdb_cadattdinamico set ";
     $virgula = "";
     if(trim($this->db117_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db117_sequencial"])){ 
       $sql  .= $virgula." db117_sequencial = $this->db117_sequencial ";
       $virgula = ",";
       if(trim($this->db117_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "db117_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db117_db_cadattdinamico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db117_db_cadattdinamico"])){ 
       $sql  .= $virgula." db117_db_cadattdinamico = $this->db117_db_cadattdinamico ";
       $virgula = ",";
       if(trim($this->db117_db_cadattdinamico) == null ){ 
         $this->erro_sql = " Campo Código Atributo Dinâmico nao Informado.";
         $this->erro_campo = "db117_db_cadattdinamico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db117_workflowativ)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db117_workflowativ"])){ 
       $sql  .= $virgula." db117_workflowativ = $this->db117_workflowativ ";
       $virgula = ",";
       if(trim($this->db117_workflowativ) == null ){ 
         $this->erro_sql = " Campo Código Work Flow Atividade nao Informado.";
         $this->erro_campo = "db117_workflowativ";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db117_sequencial!=null){
       $sql .= " db117_sequencial = $this->db117_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db117_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17895,'$this->db117_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db117_sequencial"]) || $this->db117_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3160,17895,'".AddSlashes(pg_result($resaco,$conresaco,'db117_sequencial'))."','$this->db117_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db117_db_cadattdinamico"]) || $this->db117_db_cadattdinamico != "")
           $resac = db_query("insert into db_acount values($acount,3160,17896,'".AddSlashes(pg_result($resaco,$conresaco,'db117_db_cadattdinamico'))."','$this->db117_db_cadattdinamico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db117_workflowativ"]) || $this->db117_workflowativ != "")
           $resac = db_query("insert into db_acount values($acount,3160,17897,'".AddSlashes(pg_result($resaco,$conresaco,'db117_workflowativ'))."','$this->db117_workflowativ',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "workflowativdb_cadattdinamico nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db117_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "workflowativdb_cadattdinamico nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db117_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db117_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db117_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db117_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17895,'$db117_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3160,17895,'','".AddSlashes(pg_result($resaco,$iresaco,'db117_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3160,17896,'','".AddSlashes(pg_result($resaco,$iresaco,'db117_db_cadattdinamico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3160,17897,'','".AddSlashes(pg_result($resaco,$iresaco,'db117_workflowativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from workflowativdb_cadattdinamico
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db117_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db117_sequencial = $db117_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "workflowativdb_cadattdinamico nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db117_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "workflowativdb_cadattdinamico nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db117_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db117_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:workflowativdb_cadattdinamico";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $db117_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from workflowativdb_cadattdinamico ";
     $sql .= "      inner join workflowativ  on  workflowativ.db114_sequencial = workflowativdb_cadattdinamico.db117_workflowativ";
     $sql .= "      inner join db_cadattdinamico  on  db_cadattdinamico.db118_sequencial = workflowativdb_cadattdinamico.db117_db_cadattdinamico";
     $sql .= "      inner join workflow  on  workflow.db112_sequencial = workflowativ.db114_workflow";
     $sql2 = "";
     if($dbwhere==""){
       if($db117_sequencial!=null ){
         $sql2 .= " where workflowativdb_cadattdinamico.db117_sequencial = $db117_sequencial "; 
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
   function sql_query_file ( $db117_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from workflowativdb_cadattdinamico ";
     $sql2 = "";
     if($dbwhere==""){
       if($db117_sequencial!=null ){
         $sql2 .= " where workflowativdb_cadattdinamico.db117_sequencial = $db117_sequencial "; 
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