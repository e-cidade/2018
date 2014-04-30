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

//MODULO: caixa
//CLASSE DA ENTIDADE slipconcarpeculiar
class cl_slipconcarpeculiar {
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
   var $k131_sequencial = 0;
   var $k131_slip = 0;
   var $k131_tipo = 0;
   var $k131_concarpeculiar = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 k131_sequencial = int4 = Código Sequencial
                 k131_slip = int4 = Código do Slip
                 k131_tipo = int4 = Tipo de Característica
                 k131_concarpeculiar = varchar(100) = C. Peculiar / C. Aplicação
                 ";
   //funcao construtor da classe
   function cl_slipconcarpeculiar() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("slipconcarpeculiar");
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
       $this->k131_sequencial = ($this->k131_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k131_sequencial"]:$this->k131_sequencial);
       $this->k131_slip = ($this->k131_slip == ""?@$GLOBALS["HTTP_POST_VARS"]["k131_slip"]:$this->k131_slip);
       $this->k131_tipo = ($this->k131_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["k131_tipo"]:$this->k131_tipo);
       $this->k131_concarpeculiar = ($this->k131_concarpeculiar == ""?@$GLOBALS["HTTP_POST_VARS"]["k131_concarpeculiar"]:$this->k131_concarpeculiar);
     }else{
       $this->k131_sequencial = ($this->k131_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k131_sequencial"]:$this->k131_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k131_sequencial){
      $this->atualizacampos();
     if($this->k131_slip == null ){
       $this->erro_sql = " Campo Código do Slip nao Informado.";
       $this->erro_campo = "k131_slip";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k131_tipo == null ){
       $this->erro_sql = " Campo Tipo de Característica nao Informado.";
       $this->erro_campo = "k131_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k131_concarpeculiar == null ){
       $this->erro_sql = " Campo C. Peculiar / C. Aplicação nao Informado.";
       $this->erro_campo = "k131_concarpeculiar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k131_sequencial == "" || $k131_sequencial == null ){
       $result = db_query("select nextval('slipconcarpeculiar_k131_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: slipconcarpeculiar_k131_sequencial_seq do campo: k131_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->k131_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from slipconcarpeculiar_k131_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k131_sequencial)){
         $this->erro_sql = " Campo k131_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k131_sequencial = $k131_sequencial;
       }
     }
     if(($this->k131_sequencial == null) || ($this->k131_sequencial == "") ){
       $this->erro_sql = " Campo k131_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into slipconcarpeculiar(
                                       k131_sequencial
                                      ,k131_slip
                                      ,k131_tipo
                                      ,k131_concarpeculiar
                       )
                values (
                                $this->k131_sequencial
                               ,$this->k131_slip
                               ,$this->k131_tipo
                               ,'$this->k131_concarpeculiar'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "slipconcarpeculiar ($this->k131_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "slipconcarpeculiar já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "slipconcarpeculiar ($this->k131_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k131_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k131_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18166,'$this->k131_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3209,18166,'','".AddSlashes(pg_result($resaco,0,'k131_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3209,18167,'','".AddSlashes(pg_result($resaco,0,'k131_slip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3209,18168,'','".AddSlashes(pg_result($resaco,0,'k131_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3209,18169,'','".AddSlashes(pg_result($resaco,0,'k131_concarpeculiar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($k131_sequencial=null) {
      $this->atualizacampos();
     $sql = " update slipconcarpeculiar set ";
     $virgula = "";
     if(trim($this->k131_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k131_sequencial"])){
       $sql  .= $virgula." k131_sequencial = $this->k131_sequencial ";
       $virgula = ",";
       if(trim($this->k131_sequencial) == null ){
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "k131_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k131_slip)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k131_slip"])){
       $sql  .= $virgula." k131_slip = $this->k131_slip ";
       $virgula = ",";
       if(trim($this->k131_slip) == null ){
         $this->erro_sql = " Campo Código do Slip nao Informado.";
         $this->erro_campo = "k131_slip";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k131_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k131_tipo"])){
       $sql  .= $virgula." k131_tipo = $this->k131_tipo ";
       $virgula = ",";
       if(trim($this->k131_tipo) == null ){
         $this->erro_sql = " Campo Tipo de Característica nao Informado.";
         $this->erro_campo = "k131_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k131_concarpeculiar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k131_concarpeculiar"])){
       $sql  .= $virgula." k131_concarpeculiar = '$this->k131_concarpeculiar' ";
       $virgula = ",";
       if(trim($this->k131_concarpeculiar) == null ){
         $this->erro_sql = " Campo C. Peculiar / C. Aplicação nao Informado.";
         $this->erro_campo = "k131_concarpeculiar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k131_sequencial!=null){
       $sql .= " k131_sequencial = $this->k131_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k131_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18166,'$this->k131_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k131_sequencial"]) || $this->k131_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3209,18166,'".AddSlashes(pg_result($resaco,$conresaco,'k131_sequencial'))."','$this->k131_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k131_slip"]) || $this->k131_slip != "")
           $resac = db_query("insert into db_acount values($acount,3209,18167,'".AddSlashes(pg_result($resaco,$conresaco,'k131_slip'))."','$this->k131_slip',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k131_tipo"]) || $this->k131_tipo != "")
           $resac = db_query("insert into db_acount values($acount,3209,18168,'".AddSlashes(pg_result($resaco,$conresaco,'k131_tipo'))."','$this->k131_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k131_concarpeculiar"]) || $this->k131_concarpeculiar != "")
           $resac = db_query("insert into db_acount values($acount,3209,18169,'".AddSlashes(pg_result($resaco,$conresaco,'k131_concarpeculiar'))."','$this->k131_concarpeculiar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "slipconcarpeculiar nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k131_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "slipconcarpeculiar nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k131_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k131_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($k131_sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k131_sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18166,'$k131_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3209,18166,'','".AddSlashes(pg_result($resaco,$iresaco,'k131_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3209,18167,'','".AddSlashes(pg_result($resaco,$iresaco,'k131_slip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3209,18168,'','".AddSlashes(pg_result($resaco,$iresaco,'k131_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3209,18169,'','".AddSlashes(pg_result($resaco,$iresaco,'k131_concarpeculiar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from slipconcarpeculiar
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k131_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k131_sequencial = $k131_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "slipconcarpeculiar nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k131_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "slipconcarpeculiar nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k131_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k131_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:slipconcarpeculiar";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $k131_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from slipconcarpeculiar ";
     $sql .= "      inner join slip  on  slip.k17_codigo = slipconcarpeculiar.k131_slip";
     $sql .= "      inner join db_config  on  db_config.codigo = slip.k17_instit";
     $sql2 = "";
     if($dbwhere==""){
       if($k131_sequencial!=null ){
         $sql2 .= " where slipconcarpeculiar.k131_sequencial = $k131_sequencial ";
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
   function sql_query_file ( $k131_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from slipconcarpeculiar ";
     $sql2 = "";
     if($dbwhere==""){
       if($k131_sequencial!=null ){
         $sql2 .= " where slipconcarpeculiar.k131_sequencial = $k131_sequencial ";
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

  function sql_query_concarpeculiar ( $k131_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from slipconcarpeculiar ";
    $sql .= "      inner join slip  on  slip.k17_codigo = slipconcarpeculiar.k131_slip";
    $sql .= "      inner join db_config  on  db_config.codigo = slip.k17_instit";
    $sql .= "      inner join concarpeculiar on  concarpeculiar.c58_sequencial = slipconcarpeculiar.k131_concarpeculiar";
    $sql2 = "";
    if($dbwhere==""){
      if($k131_sequencial!=null ){
        $sql2 .= " where slipconcarpeculiar.k131_sequencial = $k131_sequencial ";
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