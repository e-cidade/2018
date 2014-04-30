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

//MODULO: compras
//CLASSE DA ENTIDADE notificacaonotificafornecedor
class cl_notificacaonotificafornecedor { 
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
   var $pc87_sequencial = 0; 
   var $pc87_notificabloqueiofornecedor = 0; 
   var $pc87_notificacao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc87_sequencial = int4 = Código Sequencial 
                 pc87_notificabloqueiofornecedor = int4 = Notificação do fornecedor 
                 pc87_notificacao = int4 = Número da notificação 
                 ";
   //funcao construtor da classe 
   function cl_notificacaonotificafornecedor() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("notificacaonotificafornecedor"); 
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
       $this->pc87_sequencial = ($this->pc87_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc87_sequencial"]:$this->pc87_sequencial);
       $this->pc87_notificabloqueiofornecedor = ($this->pc87_notificabloqueiofornecedor == ""?@$GLOBALS["HTTP_POST_VARS"]["pc87_notificabloqueiofornecedor"]:$this->pc87_notificabloqueiofornecedor);
       $this->pc87_notificacao = ($this->pc87_notificacao == ""?@$GLOBALS["HTTP_POST_VARS"]["pc87_notificacao"]:$this->pc87_notificacao);
     }else{
       $this->pc87_sequencial = ($this->pc87_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc87_sequencial"]:$this->pc87_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($pc87_sequencial){ 
      $this->atualizacampos();
     if($this->pc87_notificabloqueiofornecedor == null ){ 
       $this->erro_sql = " Campo Notificação do fornecedor nao Informado.";
       $this->erro_campo = "pc87_notificabloqueiofornecedor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc87_notificacao == null ){ 
       $this->erro_sql = " Campo Número da notificação nao Informado.";
       $this->erro_campo = "pc87_notificacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($pc87_sequencial == "" || $pc87_sequencial == null ){
       $result = db_query("select nextval('notificacaonotificafornecedor_pc87_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: notificacaonotificafornecedor_pc87_sequencial_seq do campo: pc87_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->pc87_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from notificacaonotificafornecedor_pc87_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc87_sequencial)){
         $this->erro_sql = " Campo pc87_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc87_sequencial = $pc87_sequencial; 
       }
     }
     if(($this->pc87_sequencial == null) || ($this->pc87_sequencial == "") ){ 
       $this->erro_sql = " Campo pc87_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into notificacaonotificafornecedor(
                                       pc87_sequencial 
                                      ,pc87_notificabloqueiofornecedor 
                                      ,pc87_notificacao 
                       )
                values (
                                $this->pc87_sequencial 
                               ,$this->pc87_notificabloqueiofornecedor 
                               ,$this->pc87_notificacao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "notificacaonotificafornecedor ($this->pc87_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "notificacaonotificafornecedor já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "notificacaonotificafornecedor ($this->pc87_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc87_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc87_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17647,'$this->pc87_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3116,17647,'','".AddSlashes(pg_result($resaco,0,'pc87_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3116,17648,'','".AddSlashes(pg_result($resaco,0,'pc87_notificabloqueiofornecedor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3116,17649,'','".AddSlashes(pg_result($resaco,0,'pc87_notificacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc87_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update notificacaonotificafornecedor set ";
     $virgula = "";
     if(trim($this->pc87_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc87_sequencial"])){ 
       $sql  .= $virgula." pc87_sequencial = $this->pc87_sequencial ";
       $virgula = ",";
       if(trim($this->pc87_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "pc87_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc87_notificabloqueiofornecedor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc87_notificabloqueiofornecedor"])){ 
       $sql  .= $virgula." pc87_notificabloqueiofornecedor = $this->pc87_notificabloqueiofornecedor ";
       $virgula = ",";
       if(trim($this->pc87_notificabloqueiofornecedor) == null ){ 
         $this->erro_sql = " Campo Notificação do fornecedor nao Informado.";
         $this->erro_campo = "pc87_notificabloqueiofornecedor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc87_notificacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc87_notificacao"])){ 
       $sql  .= $virgula." pc87_notificacao = $this->pc87_notificacao ";
       $virgula = ",";
       if(trim($this->pc87_notificacao) == null ){ 
         $this->erro_sql = " Campo Número da notificação nao Informado.";
         $this->erro_campo = "pc87_notificacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($pc87_sequencial!=null){
       $sql .= " pc87_sequencial = $this->pc87_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc87_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17647,'$this->pc87_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc87_sequencial"]) || $this->pc87_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3116,17647,'".AddSlashes(pg_result($resaco,$conresaco,'pc87_sequencial'))."','$this->pc87_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc87_notificabloqueiofornecedor"]) || $this->pc87_notificabloqueiofornecedor != "")
           $resac = db_query("insert into db_acount values($acount,3116,17648,'".AddSlashes(pg_result($resaco,$conresaco,'pc87_notificabloqueiofornecedor'))."','$this->pc87_notificabloqueiofornecedor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc87_notificacao"]) || $this->pc87_notificacao != "")
           $resac = db_query("insert into db_acount values($acount,3116,17649,'".AddSlashes(pg_result($resaco,$conresaco,'pc87_notificacao'))."','$this->pc87_notificacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "notificacaonotificafornecedor nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc87_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "notificacaonotificafornecedor nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc87_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc87_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc87_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc87_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17647,'$pc87_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3116,17647,'','".AddSlashes(pg_result($resaco,$iresaco,'pc87_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3116,17648,'','".AddSlashes(pg_result($resaco,$iresaco,'pc87_notificabloqueiofornecedor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3116,17649,'','".AddSlashes(pg_result($resaco,$iresaco,'pc87_notificacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from notificacaonotificafornecedor
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc87_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc87_sequencial = $pc87_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "notificacaonotificafornecedor nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc87_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "notificacaonotificafornecedor nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc87_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc87_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:notificacaonotificafornecedor";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $pc87_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from notificacaonotificafornecedor ";
     $sql .= "      inner join notificacao  on  notificacao.k50_notifica = notificacaonotificafornecedor.pc87_notificacao";
     $sql .= "      inner join notificabloqueiofornecedor  on  notificabloqueiofornecedor.pc86_sequencial = notificacaonotificafornecedor.pc87_notificabloqueiofornecedor";
     $sql .= "      inner join db_config  on  db_config.codigo = notificacao.k50_instit";
     $sql .= "      inner join notitipo  on  notitipo.k51_procede = notificacao.k50_procede";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = notificabloqueiofornecedor.pc86_numcgm";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = notificabloqueiofornecedor.pc86_id_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = notificabloqueiofornecedor.pc86_departamento";
     $sql2 = "";
     if($dbwhere==""){
       if($pc87_sequencial!=null ){
         $sql2 .= " where notificacaonotificafornecedor.pc87_sequencial = $pc87_sequencial "; 
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
   function sql_query_file ( $pc87_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from notificacaonotificafornecedor ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc87_sequencial!=null ){
         $sql2 .= " where notificacaonotificafornecedor.pc87_sequencial = $pc87_sequencial "; 
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