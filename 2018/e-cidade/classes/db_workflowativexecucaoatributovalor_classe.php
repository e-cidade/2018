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

//MODULO: configuracoes
//CLASSE DA ENTIDADE workflowativexecucaoatributovalor
class cl_workflowativexecucaoatributovalor { 
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
   var $db111_sequencial = 0; 
   var $db111_workflowativexec = 0; 
   var $db111_cadattdinamicovalorgrupo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db111_sequencial = int4 = Código Sequencial 
                 db111_workflowativexec = int4 = Código Work Flow Atividade Execução 
                 db111_cadattdinamicovalorgrupo = int4 = Grupo de Valores de um Atributo 
                 ";
   //funcao construtor da classe 
   function cl_workflowativexecucaoatributovalor() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("workflowativexecucaoatributovalor"); 
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
       $this->db111_sequencial = ($this->db111_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db111_sequencial"]:$this->db111_sequencial);
       $this->db111_workflowativexec = ($this->db111_workflowativexec == ""?@$GLOBALS["HTTP_POST_VARS"]["db111_workflowativexec"]:$this->db111_workflowativexec);
       $this->db111_cadattdinamicovalorgrupo = ($this->db111_cadattdinamicovalorgrupo == ""?@$GLOBALS["HTTP_POST_VARS"]["db111_cadattdinamicovalorgrupo"]:$this->db111_cadattdinamicovalorgrupo);
     }else{
       $this->db111_sequencial = ($this->db111_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db111_sequencial"]:$this->db111_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($db111_sequencial){ 
      $this->atualizacampos();
     if($this->db111_workflowativexec == null ){ 
       $this->erro_sql = " Campo Código Work Flow Atividade Execução nao Informado.";
       $this->erro_campo = "db111_workflowativexec";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db111_cadattdinamicovalorgrupo == null ){ 
       $this->erro_sql = " Campo Grupo de Valores de um Atributo nao Informado.";
       $this->erro_campo = "db111_cadattdinamicovalorgrupo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db111_sequencial == "" || $db111_sequencial == null ){
       $result = db_query("select nextval('workflowativexecucaoatributovalor_db111_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: workflowativexecucaoatributovalor_db111_sequencial_seq do campo: db111_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db111_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from workflowativexecucaoatributovalor_db111_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db111_sequencial)){
         $this->erro_sql = " Campo db111_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db111_sequencial = $db111_sequencial; 
       }
     }
     if(($this->db111_sequencial == null) || ($this->db111_sequencial == "") ){ 
       $this->erro_sql = " Campo db111_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into workflowativexecucaoatributovalor(
                                       db111_sequencial 
                                      ,db111_workflowativexec 
                                      ,db111_cadattdinamicovalorgrupo 
                       )
                values (
                                $this->db111_sequencial 
                               ,$this->db111_workflowativexec 
                               ,$this->db111_cadattdinamicovalorgrupo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "workflowativexecucaoatributovalor ($this->db111_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "workflowativexecucaoatributovalor já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "workflowativexecucaoatributovalor ($this->db111_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db111_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db111_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17873,'$this->db111_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3157,17873,'','".AddSlashes(pg_result($resaco,0,'db111_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3157,17874,'','".AddSlashes(pg_result($resaco,0,'db111_workflowativexec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3157,17906,'','".AddSlashes(pg_result($resaco,0,'db111_cadattdinamicovalorgrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db111_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update workflowativexecucaoatributovalor set ";
     $virgula = "";
     if(trim($this->db111_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db111_sequencial"])){ 
       $sql  .= $virgula." db111_sequencial = $this->db111_sequencial ";
       $virgula = ",";
       if(trim($this->db111_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "db111_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db111_workflowativexec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db111_workflowativexec"])){ 
       $sql  .= $virgula." db111_workflowativexec = $this->db111_workflowativexec ";
       $virgula = ",";
       if(trim($this->db111_workflowativexec) == null ){ 
         $this->erro_sql = " Campo Código Work Flow Atividade Execução nao Informado.";
         $this->erro_campo = "db111_workflowativexec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db111_cadattdinamicovalorgrupo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db111_cadattdinamicovalorgrupo"])){ 
       $sql  .= $virgula." db111_cadattdinamicovalorgrupo = $this->db111_cadattdinamicovalorgrupo ";
       $virgula = ",";
       if(trim($this->db111_cadattdinamicovalorgrupo) == null ){ 
         $this->erro_sql = " Campo Grupo de Valores de um Atributo nao Informado.";
         $this->erro_campo = "db111_cadattdinamicovalorgrupo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db111_sequencial!=null){
       $sql .= " db111_sequencial = $this->db111_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db111_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17873,'$this->db111_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db111_sequencial"]) || $this->db111_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3157,17873,'".AddSlashes(pg_result($resaco,$conresaco,'db111_sequencial'))."','$this->db111_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db111_workflowativexec"]) || $this->db111_workflowativexec != "")
           $resac = db_query("insert into db_acount values($acount,3157,17874,'".AddSlashes(pg_result($resaco,$conresaco,'db111_workflowativexec'))."','$this->db111_workflowativexec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db111_cadattdinamicovalorgrupo"]) || $this->db111_cadattdinamicovalorgrupo != "")
           $resac = db_query("insert into db_acount values($acount,3157,17906,'".AddSlashes(pg_result($resaco,$conresaco,'db111_cadattdinamicovalorgrupo'))."','$this->db111_cadattdinamicovalorgrupo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "workflowativexecucaoatributovalor nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db111_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "workflowativexecucaoatributovalor nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db111_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db111_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db111_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db111_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17873,'$db111_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3157,17873,'','".AddSlashes(pg_result($resaco,$iresaco,'db111_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3157,17874,'','".AddSlashes(pg_result($resaco,$iresaco,'db111_workflowativexec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3157,17906,'','".AddSlashes(pg_result($resaco,$iresaco,'db111_cadattdinamicovalorgrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from workflowativexecucaoatributovalor
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db111_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db111_sequencial = $db111_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "workflowativexecucaoatributovalor nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db111_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "workflowativexecucaoatributovalor nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db111_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db111_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:workflowativexecucaoatributovalor";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $db111_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from workflowativexecucaoatributovalor ";
     $sql .= "      inner join workflowativexec  on  workflowativexec.db113_sequencial = workflowativexecucaoatributovalor.db111_workflowativexec";
     $sql .= "      inner join db_cadattdinamicovalorgrupo  on  db_cadattdinamicovalorgrupo.db120_sequencial = workflowativexecucaoatributovalor.db111_cadattdinamicovalorgrupo";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = workflowativexec.db113_id_usuario";
     $sql .= "      inner join workflowativ  on  workflowativ.db114_sequencial = workflowativexec.db113_workflowativ";
     $sql2 = "";
     if($dbwhere==""){
       if($db111_sequencial!=null ){
         $sql2 .= " where workflowativexecucaoatributovalor.db111_sequencial = $db111_sequencial "; 
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
   function sql_query_file ( $db111_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from workflowativexecucaoatributovalor ";
     $sql2 = "";
     if($dbwhere==""){
       if($db111_sequencial!=null ){
         $sql2 .= " where workflowativexecucaoatributovalor.db111_sequencial = $db111_sequencial "; 
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
  function sql_query_atributos ( $db111_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from workflowativexecucaoatributovalor ";
    $sql .= "      inner join workflowativexec  on  workflowativexec.db113_sequencial = workflowativexecucaoatributovalor.db111_workflowativexec";
    $sql .= "      inner join db_cadattdinamicovalorgrupo  on  db_cadattdinamicovalorgrupo.db120_sequencial = workflowativexecucaoatributovalor.db111_cadattdinamicovalorgrupo";
    $sql .= "      inner join db_cadattdinamicoatributosvalor on db110_cadattdinamicovalorgrupo = db120_sequencial";
    $sql .= "      inner join db_cadattdinamicoatributos on db109_sequencial = db110_db_cadattdinamicoatributos";
    $sql .= "      inner join workflowativ  on  workflowativ.db114_sequencial = workflowativexec.db113_workflowativ";
    $sql2 = "";
    if($dbwhere==""){
      if($db111_sequencial!=null ){
        $sql2 .= " where workflowativexecucaoatributovalor.db111_sequencial = $db111_sequencial ";
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