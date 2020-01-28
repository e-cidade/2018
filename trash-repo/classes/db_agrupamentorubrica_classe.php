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

//MODULO: pessoal
//CLASSE DA ENTIDADE agrupamentorubrica
class cl_agrupamentorubrica { 
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
   var $rh113_sequencial = 0; 
   var $rh113_codigo = 0; 
   var $rh113_descricao = null; 
   var $rh113_tipo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh113_sequencial = int4 = Sequencial 
                 rh113_codigo = int4 = Código do grupo 
                 rh113_descricao = varchar(200) = Descrição 
                 rh113_tipo = int4 = Tipo 
                 ";
   //funcao construtor da classe 
   function cl_agrupamentorubrica() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("agrupamentorubrica"); 
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
       $this->rh113_sequencial = ($this->rh113_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh113_sequencial"]:$this->rh113_sequencial);
       $this->rh113_codigo = ($this->rh113_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh113_codigo"]:$this->rh113_codigo);
       $this->rh113_descricao = ($this->rh113_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh113_descricao"]:$this->rh113_descricao);
       $this->rh113_tipo = ($this->rh113_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh113_tipo"]:$this->rh113_tipo);
     }else{
       $this->rh113_sequencial = ($this->rh113_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh113_sequencial"]:$this->rh113_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh113_sequencial){ 
      $this->atualizacampos();
     if($this->rh113_codigo == null ){ 
       $this->erro_sql = " Campo Código do grupo nao Informado.";
       $this->erro_campo = "rh113_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh113_descricao == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "rh113_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh113_tipo == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "rh113_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh113_sequencial == "" || $rh113_sequencial == null ){
       $result = db_query("select nextval('agrupamentorubrica_rh113_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: agrupamentorubrica_rh113_sequencial_seq do campo: rh113_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh113_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from agrupamentorubrica_rh113_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh113_sequencial)){
         $this->erro_sql = " Campo rh113_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh113_sequencial = $rh113_sequencial; 
       }
     }
     if(($this->rh113_sequencial == null) || ($this->rh113_sequencial == "") ){ 
       $this->erro_sql = " Campo rh113_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into agrupamentorubrica(
                                       rh113_sequencial 
                                      ,rh113_codigo 
                                      ,rh113_descricao 
                                      ,rh113_tipo 
                       )
                values (
                                $this->rh113_sequencial 
                               ,$this->rh113_codigo 
                               ,'$this->rh113_descricao' 
                               ,$this->rh113_tipo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Agrupamento de Rubricas ($this->rh113_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Agrupamento de Rubricas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Agrupamento de Rubricas ($this->rh113_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh113_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh113_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19557,'$this->rh113_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3478,19557,'','".AddSlashes(pg_result($resaco,0,'rh113_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3478,19586,'','".AddSlashes(pg_result($resaco,0,'rh113_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3478,19559,'','".AddSlashes(pg_result($resaco,0,'rh113_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3478,19561,'','".AddSlashes(pg_result($resaco,0,'rh113_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh113_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update agrupamentorubrica set ";
     $virgula = "";
     if(trim($this->rh113_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh113_sequencial"])){ 
       $sql  .= $virgula." rh113_sequencial = $this->rh113_sequencial ";
       $virgula = ",";
       if(trim($this->rh113_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "rh113_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh113_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh113_codigo"])){ 
       $sql  .= $virgula." rh113_codigo = $this->rh113_codigo ";
       $virgula = ",";
       if(trim($this->rh113_codigo) == null ){ 
         $this->erro_sql = " Campo Código do grupo nao Informado.";
         $this->erro_campo = "rh113_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh113_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh113_descricao"])){ 
       $sql  .= $virgula." rh113_descricao = '$this->rh113_descricao' ";
       $virgula = ",";
       if(trim($this->rh113_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "rh113_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh113_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh113_tipo"])){ 
       $sql  .= $virgula." rh113_tipo = $this->rh113_tipo ";
       $virgula = ",";
       if(trim($this->rh113_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "rh113_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh113_sequencial!=null){
       $sql .= " rh113_sequencial = $this->rh113_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh113_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19557,'$this->rh113_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh113_sequencial"]) || $this->rh113_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3478,19557,'".AddSlashes(pg_result($resaco,$conresaco,'rh113_sequencial'))."','$this->rh113_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh113_codigo"]) || $this->rh113_codigo != "")
           $resac = db_query("insert into db_acount values($acount,3478,19586,'".AddSlashes(pg_result($resaco,$conresaco,'rh113_codigo'))."','$this->rh113_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh113_descricao"]) || $this->rh113_descricao != "")
           $resac = db_query("insert into db_acount values($acount,3478,19559,'".AddSlashes(pg_result($resaco,$conresaco,'rh113_descricao'))."','$this->rh113_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh113_tipo"]) || $this->rh113_tipo != "")
           $resac = db_query("insert into db_acount values($acount,3478,19561,'".AddSlashes(pg_result($resaco,$conresaco,'rh113_tipo'))."','$this->rh113_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Agrupamento de Rubricas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh113_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Agrupamento de Rubricas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh113_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh113_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh113_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh113_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19557,'$rh113_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3478,19557,'','".AddSlashes(pg_result($resaco,$iresaco,'rh113_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3478,19586,'','".AddSlashes(pg_result($resaco,$iresaco,'rh113_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3478,19559,'','".AddSlashes(pg_result($resaco,$iresaco,'rh113_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3478,19561,'','".AddSlashes(pg_result($resaco,$iresaco,'rh113_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from agrupamentorubrica
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh113_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh113_sequencial = $rh113_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Agrupamento de Rubricas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh113_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Agrupamento de Rubricas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh113_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh113_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:agrupamentorubrica";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh113_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from agrupamentorubrica ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh113_sequencial!=null ){
         $sql2 .= " where agrupamentorubrica.rh113_sequencial = $rh113_sequencial "; 
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
   function sql_query_file ( $rh113_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from agrupamentorubrica ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh113_sequencial!=null ){
         $sql2 .= " where agrupamentorubrica.rh113_sequencial = $rh113_sequencial "; 
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

  function sql_query_agrupamento($sCampos = '*', $sWhere = null) {

    $sSql  = " select $sCampos                                                                                                     ";
    $sSql .= " from agrupamentorubrica                                                                                             ";
    $sSql .= "      left join agrupamentorubricarubrica  on rh113_sequencial       = rh114_agrupamentorubrica                     ";
    $sSql .= "      left join rhrubricas                 on rhrubricas.rh27_rubric = agrupamentorubricarubrica.rh114_rubrica       "; 
    $sSql .= "                                          and rhrubricas.rh27_instit = agrupamentorubricarubrica.rh114_instituicao   ";
    $sSql .= "      left join db_config                  on db_config.codigo       = rhrubricas.rh27_instit                        ";
    $sSql .= "      left join rhtipomedia                on rhtipomedia.rh29_tipo  = rhrubricas.rh27_calc1                         ";

    if ( !empty($sWhere) ) {
      $sSql .= "where $sWhere";      
    }

    return $sSql;
  }
  
  function sql_queryRubricasAgrupamento ($sCampos = '*', $iCodigoAgrupamento, $sOrderBy = null) {

  	$iInstituicao = db_getsession('DB_instit');
  	
  	$sSql  = "select {$sCampos}                                                                                                                               ";
  	$sSql .= "  from rhrubricas                                                                                                                               ";
  	$sSql .= "  left join agrupamentorubricarubrica on agrupamentorubricarubrica.rh114_rubrica            = rhrubricas.rh27_rubric                            ";
  	$sSql .= " 	  															   and agrupamentorubricarubrica.rh114_instituicao        = rhrubricas.rh27_instit 						                ";
  	$sSql .= "                                     and agrupamentorubricarubrica.rh114_agrupamentorubrica = {$iCodigoAgrupamento}															";
  	$sSql .= "  left join agrupamentorubrica        on agrupamentorubrica.rh113_sequencial         			  = agrupamentorubricarubrica.rh114_agrupamentorubrica";
		$sSql .= " where rhrubricas.rh27_instit = {$iInstituicao}		   																																												    ";
		
		if ($sOrderBy != null) {
			
			$sSql .= " order by {$sOrderBy}																																																													";
			
		}

		return $sSql;
	  	
  }

}
?>