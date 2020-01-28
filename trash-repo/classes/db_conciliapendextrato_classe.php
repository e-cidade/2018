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
//CLASSE DA ENTIDADE conciliapendextrato
class cl_conciliapendextrato {
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
   var $k88_sequencial = 0;
   var $k88_extratolinha = 0;
   var $k88_concilia = 0;
   var $k88_conciliaorigem = 0;
   var $k88_justificativa = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 k88_sequencial = int8 = Codigo sequencial
                 k88_extratolinha = int8 = Codigo da linha do extrato
                 k88_concilia = int4 = Codigo da conciliação
                 k88_conciliaorigem = int4 = Código
                 k88_justificativa = text = Justificativa
                 ";
   //funcao construtor da classe
   function cl_conciliapendextrato() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("conciliapendextrato");
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
       $this->k88_sequencial = ($this->k88_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k88_sequencial"]:$this->k88_sequencial);
       $this->k88_extratolinha = ($this->k88_extratolinha == ""?@$GLOBALS["HTTP_POST_VARS"]["k88_extratolinha"]:$this->k88_extratolinha);
       $this->k88_concilia = ($this->k88_concilia == ""?@$GLOBALS["HTTP_POST_VARS"]["k88_concilia"]:$this->k88_concilia);
       $this->k88_conciliaorigem = ($this->k88_conciliaorigem == ""?@$GLOBALS["HTTP_POST_VARS"]["k88_conciliaorigem"]:$this->k88_conciliaorigem);
       $this->k88_justificativa = ($this->k88_justificativa == ""?@$GLOBALS["HTTP_POST_VARS"]["k88_justificativa"]:$this->k88_justificativa);
     }else{
       $this->k88_sequencial = ($this->k88_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k88_sequencial"]:$this->k88_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k88_sequencial){
      $this->atualizacampos();
     if($this->k88_extratolinha == null ){
       $this->erro_sql = " Campo Codigo da linha do extrato nao Informado.";
       $this->erro_campo = "k88_extratolinha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k88_concilia == null ){
       $this->erro_sql = " Campo Codigo da conciliação nao Informado.";
       $this->erro_campo = "k88_concilia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k88_conciliaorigem == null ){
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "k88_conciliaorigem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k88_sequencial == "" || $k88_sequencial == null ){
       $result = db_query("select nextval('conciliapendextrato_k88_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: conciliapendextrato_k88_sequencial_seq do campo: k88_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->k88_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from conciliapendextrato_k88_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k88_sequencial)){
         $this->erro_sql = " Campo k88_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k88_sequencial = $k88_sequencial;
       }
     }
     if(($this->k88_sequencial == null) || ($this->k88_sequencial == "") ){
       $this->erro_sql = " Campo k88_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into conciliapendextrato(
                                       k88_sequencial
                                      ,k88_extratolinha
                                      ,k88_concilia
                                      ,k88_conciliaorigem
                                      ,k88_justificativa
                       )
                values (
                                $this->k88_sequencial
                               ,$this->k88_extratolinha
                               ,$this->k88_concilia
                               ,$this->k88_conciliaorigem
                               ,'$this->k88_justificativa'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Pendencias do extrato ($this->k88_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Pendencias do extrato já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Pendencias do extrato ($this->k88_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k88_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k88_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10088,'$this->k88_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1734,10088,'','".AddSlashes(pg_result($resaco,0,'k88_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1734,10089,'','".AddSlashes(pg_result($resaco,0,'k88_extratolinha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1734,10090,'','".AddSlashes(pg_result($resaco,0,'k88_concilia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1734,10170,'','".AddSlashes(pg_result($resaco,0,'k88_conciliaorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1734,19287,'','".AddSlashes(pg_result($resaco,0,'k88_justificativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($k88_sequencial=null) {
      $this->atualizacampos();
     $sql = " update conciliapendextrato set ";
     $virgula = "";
     if(trim($this->k88_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k88_sequencial"])){
       $sql  .= $virgula." k88_sequencial = $this->k88_sequencial ";
       $virgula = ",";
       if(trim($this->k88_sequencial) == null ){
         $this->erro_sql = " Campo Codigo sequencial nao Informado.";
         $this->erro_campo = "k88_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k88_extratolinha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k88_extratolinha"])){
       $sql  .= $virgula." k88_extratolinha = $this->k88_extratolinha ";
       $virgula = ",";
       if(trim($this->k88_extratolinha) == null ){
         $this->erro_sql = " Campo Codigo da linha do extrato nao Informado.";
         $this->erro_campo = "k88_extratolinha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k88_concilia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k88_concilia"])){
       $sql  .= $virgula." k88_concilia = $this->k88_concilia ";
       $virgula = ",";
       if(trim($this->k88_concilia) == null ){
         $this->erro_sql = " Campo Codigo da conciliação nao Informado.";
         $this->erro_campo = "k88_concilia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k88_conciliaorigem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k88_conciliaorigem"])){
       $sql  .= $virgula." k88_conciliaorigem = $this->k88_conciliaorigem ";
       $virgula = ",";
       if(trim($this->k88_conciliaorigem) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "k88_conciliaorigem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k88_justificativa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k88_justificativa"])){
       $sql  .= $virgula." k88_justificativa = '$this->k88_justificativa' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($k88_sequencial!=null){
       $sql .= " k88_sequencial = $this->k88_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k88_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10088,'$this->k88_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k88_sequencial"]) || $this->k88_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,1734,10088,'".AddSlashes(pg_result($resaco,$conresaco,'k88_sequencial'))."','$this->k88_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k88_extratolinha"]) || $this->k88_extratolinha != "")
           $resac = db_query("insert into db_acount values($acount,1734,10089,'".AddSlashes(pg_result($resaco,$conresaco,'k88_extratolinha'))."','$this->k88_extratolinha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k88_concilia"]) || $this->k88_concilia != "")
           $resac = db_query("insert into db_acount values($acount,1734,10090,'".AddSlashes(pg_result($resaco,$conresaco,'k88_concilia'))."','$this->k88_concilia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k88_conciliaorigem"]) || $this->k88_conciliaorigem != "")
           $resac = db_query("insert into db_acount values($acount,1734,10170,'".AddSlashes(pg_result($resaco,$conresaco,'k88_conciliaorigem'))."','$this->k88_conciliaorigem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k88_justificativa"]) || $this->k88_justificativa != "")
           $resac = db_query("insert into db_acount values($acount,1734,19287,'".AddSlashes(pg_result($resaco,$conresaco,'k88_justificativa'))."','$this->k88_justificativa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Pendencias do extrato nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k88_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Pendencias do extrato nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k88_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k88_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($k88_sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k88_sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10088,'$k88_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1734,10088,'','".AddSlashes(pg_result($resaco,$iresaco,'k88_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1734,10089,'','".AddSlashes(pg_result($resaco,$iresaco,'k88_extratolinha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1734,10090,'','".AddSlashes(pg_result($resaco,$iresaco,'k88_concilia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1734,10170,'','".AddSlashes(pg_result($resaco,$iresaco,'k88_conciliaorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1734,19287,'','".AddSlashes(pg_result($resaco,$iresaco,'k88_justificativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from conciliapendextrato
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k88_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k88_sequencial = $k88_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Pendencias do extrato nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k88_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Pendencias do extrato nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k88_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k88_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:conciliapendextrato";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $k88_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from conciliapendextrato ";
     $sql .= "      inner join concilia  on  concilia.k68_sequencial = conciliapendextrato.k88_concilia";
     $sql .= "      inner join extratolinha  on  extratolinha.k86_sequencial = conciliapendextrato.k88_extratolinha";
     $sql .= "      inner join conciliaorigem  on  conciliaorigem.k96_sequencial = conciliapendextrato.k88_conciliaorigem";
     $sql .= "      inner join conciliastatus  on  conciliastatus.k95_sequencial = concilia.k68_conciliastatus";
     $sql .= "      inner join contabancaria  on  contabancaria.db83_sequencial = concilia.k68_contabancaria";
     $sql .= "      inner join bancoshistmov  on  bancoshistmov.k66_sequencial = extratolinha.k86_bancohistmov";
     $sql .= "      inner join extrato  as a on   a.k85_sequencial = extratolinha.k86_extrato";
     $sql .= "      inner join contabancaria  as b on   b.db83_sequencial = extratolinha.k86_contabancaria";
     $sql2 = "";
     if($dbwhere==""){
       if($k88_sequencial!=null ){
         $sql2 .= " where conciliapendextrato.k88_sequencial = $k88_sequencial ";
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
   function sql_query_file ( $k88_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from conciliapendextrato ";
     $sql2 = "";
     if($dbwhere==""){
       if($k88_sequencial!=null ){
         $sql2 .= " where conciliapendextrato.k88_sequencial = $k88_sequencial ";
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

  function sql_query_extrato_sigfis ( $k88_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from conciliapendextrato ";
    $sql .= "      inner join concilia      on  concilia.k68_sequencial = conciliapendextrato.k88_concilia";
    $sql .= "      inner join extratolinha  on  extratolinha.k86_sequencial = conciliapendextrato.k88_extratolinha";
    $sql .= "      inner join extrato  as a on  a.k85_sequencial = extratolinha.k86_extrato";
    $sql2 = "";
    if($dbwhere==""){
      if($k88_sequencial!=null ){
        $sql2 .= " where conciliapendextrato.k88_sequencial = $k88_sequencial ";
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