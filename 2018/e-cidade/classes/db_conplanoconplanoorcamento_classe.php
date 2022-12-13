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

//MODULO: contabilidade
//CLASSE DA ENTIDADE conplanoconplanoorcamento
class cl_conplanoconplanoorcamento {
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
   var $c72_sequencial = 0;
   var $c72_conplano = 0;
   var $c72_conplanoorcamento = 0;
   var $c72_anousu = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 c72_sequencial = int4 = Código ligação
                 c72_conplano = int4 = Código da conplano
                 c72_conplanoorcamento = int4 = Código da conplanoorcamento
                 c72_anousu = int4 = Anousu
                 ";
   //funcao construtor da classe
   function cl_conplanoconplanoorcamento() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("conplanoconplanoorcamento");
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
       $this->c72_sequencial = ($this->c72_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c72_sequencial"]:$this->c72_sequencial);
       $this->c72_conplano = ($this->c72_conplano == ""?@$GLOBALS["HTTP_POST_VARS"]["c72_conplano"]:$this->c72_conplano);
       $this->c72_conplanoorcamento = ($this->c72_conplanoorcamento == ""?@$GLOBALS["HTTP_POST_VARS"]["c72_conplanoorcamento"]:$this->c72_conplanoorcamento);
       $this->c72_anousu = ($this->c72_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c72_anousu"]:$this->c72_anousu);
     }else{
       $this->c72_sequencial = ($this->c72_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c72_sequencial"]:$this->c72_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($c72_sequencial){
      $this->atualizacampos();
     if($this->c72_conplano == null ){
       $this->erro_sql = " Campo Código da conplano nao Informado.";
       $this->erro_campo = "c72_conplano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c72_conplanoorcamento == null ){
       $this->erro_sql = " Campo Código da conplanoorcamento nao Informado.";
       $this->erro_campo = "c72_conplanoorcamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c72_anousu == null ){
       $this->erro_sql = " Campo Anousu nao Informado.";
       $this->erro_campo = "c72_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c72_sequencial == "" || $c72_sequencial == null ){
       $result = db_query("select nextval('conplanoconplanoorcamento_c72_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: conplanoconplanoorcamento_c72_sequencial_seq do campo: c72_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->c72_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from conplanoconplanoorcamento_c72_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $c72_sequencial)){
         $this->erro_sql = " Campo c72_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c72_sequencial = $c72_sequencial;
       }
     }
     if(($this->c72_sequencial == null) || ($this->c72_sequencial == "") ){
       $this->erro_sql = " Campo c72_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into conplanoconplanoorcamento(
                                       c72_sequencial
                                      ,c72_conplano
                                      ,c72_conplanoorcamento
                                      ,c72_anousu
                       )
                values (
                                $this->c72_sequencial
                               ,$this->c72_conplano
                               ,$this->c72_conplanoorcamento
                               ,$this->c72_anousu
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "conplanoconplanoorcamento ($this->c72_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "conplanoconplanoorcamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "conplanoconplanoorcamento ($this->c72_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c72_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c72_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18497,'$this->c72_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3270,18497,'','".AddSlashes(pg_result($resaco,0,'c72_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3270,18498,'','".AddSlashes(pg_result($resaco,0,'c72_conplano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3270,18499,'','".AddSlashes(pg_result($resaco,0,'c72_conplanoorcamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3270,18500,'','".AddSlashes(pg_result($resaco,0,'c72_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($c72_sequencial=null) {
      $this->atualizacampos();
     $sql = " update conplanoconplanoorcamento set ";
     $virgula = "";
     if(trim($this->c72_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c72_sequencial"])){
       $sql  .= $virgula." c72_sequencial = $this->c72_sequencial ";
       $virgula = ",";
       if(trim($this->c72_sequencial) == null ){
         $this->erro_sql = " Campo Código ligação nao Informado.";
         $this->erro_campo = "c72_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c72_conplano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c72_conplano"])){
       $sql  .= $virgula." c72_conplano = $this->c72_conplano ";
       $virgula = ",";
       if(trim($this->c72_conplano) == null ){
         $this->erro_sql = " Campo Código da conplano nao Informado.";
         $this->erro_campo = "c72_conplano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c72_conplanoorcamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c72_conplanoorcamento"])){
       $sql  .= $virgula." c72_conplanoorcamento = $this->c72_conplanoorcamento ";
       $virgula = ",";
       if(trim($this->c72_conplanoorcamento) == null ){
         $this->erro_sql = " Campo Código da conplanoorcamento nao Informado.";
         $this->erro_campo = "c72_conplanoorcamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c72_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c72_anousu"])){
       $sql  .= $virgula." c72_anousu = $this->c72_anousu ";
       $virgula = ",";
       if(trim($this->c72_anousu) == null ){
         $this->erro_sql = " Campo Anousu nao Informado.";
         $this->erro_campo = "c72_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c72_sequencial!=null){
       $sql .= " c72_sequencial = $this->c72_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c72_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18497,'$this->c72_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c72_sequencial"]) || $this->c72_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3270,18497,'".AddSlashes(pg_result($resaco,$conresaco,'c72_sequencial'))."','$this->c72_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c72_conplano"]) || $this->c72_conplano != "")
           $resac = db_query("insert into db_acount values($acount,3270,18498,'".AddSlashes(pg_result($resaco,$conresaco,'c72_conplano'))."','$this->c72_conplano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c72_conplanoorcamento"]) || $this->c72_conplanoorcamento != "")
           $resac = db_query("insert into db_acount values($acount,3270,18499,'".AddSlashes(pg_result($resaco,$conresaco,'c72_conplanoorcamento'))."','$this->c72_conplanoorcamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c72_anousu"]) || $this->c72_anousu != "")
           $resac = db_query("insert into db_acount values($acount,3270,18500,'".AddSlashes(pg_result($resaco,$conresaco,'c72_anousu'))."','$this->c72_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "conplanoconplanoorcamento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c72_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "conplanoconplanoorcamento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c72_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c72_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($c72_sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c72_sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18497,'$c72_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3270,18497,'','".AddSlashes(pg_result($resaco,$iresaco,'c72_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3270,18498,'','".AddSlashes(pg_result($resaco,$iresaco,'c72_conplano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3270,18499,'','".AddSlashes(pg_result($resaco,$iresaco,'c72_conplanoorcamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3270,18500,'','".AddSlashes(pg_result($resaco,$iresaco,'c72_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from conplanoconplanoorcamento
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c72_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c72_sequencial = $c72_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "conplanoconplanoorcamento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c72_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "conplanoconplanoorcamento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c72_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c72_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:conplanoconplanoorcamento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $c72_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from conplanoconplanoorcamento ";
     $sql .= "      inner join conplano  on  conplano.c60_codcon = conplanoconplanoorcamento.c72_conplano and  conplano.c60_anousu = conplanoconplanoorcamento.c72_anousu";
     $sql .= "      inner join conplanoorcamento  on  conplanoorcamento.c60_codcon = conplanoconplanoorcamento.c72_conplanoorcamento and  conplanoorcamento.c60_anousu = conplanoconplanoorcamento.c72_anousu";
     $sql .= "      inner join conclass  on  conclass.c51_codcla = conplano.c60_codcla";
     $sql .= "      inner join consistema  on  consistema.c52_codsis = conplano.c60_codsis";
     $sql .= "      inner join consistemaconta  on  consistemaconta.c65_sequencial = conplano.c60_consistemaconta";
     $sql .= "      inner join conclass  as a on   a.c51_codcla = conplanoorcamento.c60_codcla";
     $sql .= "      inner join consistema  as b on   b.c52_codsis = conplanoorcamento.c60_codsis";
     $sql .= "      inner join consistemaconta  as c on   c.c65_sequencial = conplanoorcamento.c60_consistemaconta";
     $sql2 = "";
     if($dbwhere==""){
       if($c72_sequencial!=null ){
         $sql2 .= " where conplanoconplanoorcamento.c72_sequencial = $c72_sequencial ";
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
   function sql_query_file ( $c72_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from conplanoconplanoorcamento ";
     $sql2 = "";
     if($dbwhere==""){
       if($c72_sequencial!=null ){
         $sql2 .= " where conplanoconplanoorcamento.c72_sequencial = $c72_sequencial ";
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
 function sql_query_pcasp_analitica ( $c72_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from conplanoconplanoorcamento ";
     $sql .= "      inner join conplano       on conplano.c60_codcon  = conplanoconplanoorcamento.c72_conplano ";
     $sql .= "                               and conplano.c60_anousu  = conplanoconplanoorcamento.c72_anousu";
     $sql .= "      left  join conplanoreduz  on conplano.c60_codcon  = conplanoreduz.c61_codcon ";
     $sql .= "                               and conplano.c60_anousu = conplanoreduz.c61_anousu";
     $sql .= "      inner join conplanoorcamento on conplanoorcamento.c60_codcon  = conplanoconplanoorcamento.c72_conplanoorcamento ";
     $sql .= "                                  and conplanoorcamento.c60_anousu  = conplanoconplanoorcamento.c72_anousu";
     $sql .= "      left  join conplanoorcamentoanalitica  on conplanoorcamento.c60_codcon  = conplanoorcamentoanalitica.c61_codcon ";
     $sql .= "                                           and conplanoorcamento.c60_anousu = conplanoorcamentoanalitica.c61_anousu";
     $sql2 = "";
     if($dbwhere==""){
       if($c72_sequencial!=null ){
         $sql2 .= " where conplanoconplanoorcamento.c72_sequencial = $c72_sequencial ";
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