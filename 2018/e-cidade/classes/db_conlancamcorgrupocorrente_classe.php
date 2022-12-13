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
//CLASSE DA ENTIDADE conlancamcorgrupocorrente
class cl_conlancamcorgrupocorrente {
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
   var $c23_sequencial = 0;
   var $c23_conlancam = 0;
   var $c23_corgrupocorrente = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 c23_sequencial = int4 = Código Sequencial
                 c23_conlancam = int4 = Código do Lançamento
                 c23_corgrupocorrente = int4 = Código do grupo
                 ";
   //funcao construtor da classe
   function cl_conlancamcorgrupocorrente() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("conlancamcorgrupocorrente");
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
       $this->c23_sequencial = ($this->c23_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c23_sequencial"]:$this->c23_sequencial);
       $this->c23_conlancam = ($this->c23_conlancam == ""?@$GLOBALS["HTTP_POST_VARS"]["c23_conlancam"]:$this->c23_conlancam);
       $this->c23_corgrupocorrente = ($this->c23_corgrupocorrente == ""?@$GLOBALS["HTTP_POST_VARS"]["c23_corgrupocorrente"]:$this->c23_corgrupocorrente);
     }else{
       $this->c23_sequencial = ($this->c23_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c23_sequencial"]:$this->c23_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($c23_sequencial){
      $this->atualizacampos();
     if($this->c23_conlancam == null ){
       $this->erro_sql = " Campo Código do Lançamento nao Informado.";
       $this->erro_campo = "c23_conlancam";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c23_corgrupocorrente == null ){
       $this->erro_sql = " Campo Código do grupo nao Informado.";
       $this->erro_campo = "c23_corgrupocorrente";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c23_sequencial == "" || $c23_sequencial == null ){
       $result = db_query("select nextval('conlancamcorgrupocorrente_c23_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: conlancamcorgrupocorrente_c23_sequencial_seq do campo: c23_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->c23_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from conlancamcorgrupocorrente_c23_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $c23_sequencial)){
         $this->erro_sql = " Campo c23_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c23_sequencial = $c23_sequencial;
       }
     }
     if(($this->c23_sequencial == null) || ($this->c23_sequencial == "") ){
       $this->erro_sql = " Campo c23_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into conlancamcorgrupocorrente(
                                       c23_sequencial
                                      ,c23_conlancam
                                      ,c23_corgrupocorrente
                       )
                values (
                                $this->c23_sequencial
                               ,$this->c23_conlancam
                               ,$this->c23_corgrupocorrente
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Grupo de lancamentos ($this->c23_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Grupo de lancamentos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Grupo de lancamentos ($this->c23_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c23_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

       $resaco = $this->sql_record($this->sql_query_file($this->c23_sequencial));
       if(($resaco!=false)||($this->numrows!=0)) {

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12441,'$this->c23_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,2169,12441,'','".AddSlashes(pg_result($resaco,0,'c23_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2169,12442,'','".AddSlashes(pg_result($resaco,0,'c23_conlancam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2169,12443,'','".AddSlashes(pg_result($resaco,0,'c23_corgrupocorrente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($c23_sequencial=null) {
      $this->atualizacampos();
     $sql = " update conlancamcorgrupocorrente set ";
     $virgula = "";
     if(trim($this->c23_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c23_sequencial"])){
       $sql  .= $virgula." c23_sequencial = $this->c23_sequencial ";
       $virgula = ",";
       if(trim($this->c23_sequencial) == null ){
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "c23_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c23_conlancam)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c23_conlancam"])){
       $sql  .= $virgula." c23_conlancam = $this->c23_conlancam ";
       $virgula = ",";
       if(trim($this->c23_conlancam) == null ){
         $this->erro_sql = " Campo Código do Lançamento nao Informado.";
         $this->erro_campo = "c23_conlancam";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c23_corgrupocorrente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c23_corgrupocorrente"])){
       $sql  .= $virgula." c23_corgrupocorrente = $this->c23_corgrupocorrente ";
       $virgula = ",";
       if(trim($this->c23_corgrupocorrente) == null ){
         $this->erro_sql = " Campo Código do grupo nao Informado.";
         $this->erro_campo = "c23_corgrupocorrente";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c23_sequencial!=null){
       $sql .= " c23_sequencial = $this->c23_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

       $resaco = $this->sql_record($this->sql_query_file($this->c23_sequencial));
       if($this->numrows>0){
         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,12441,'$this->c23_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["c23_sequencial"]))
             $resac = db_query("insert into db_acount values($acount,2169,12441,'".AddSlashes(pg_result($resaco,$conresaco,'c23_sequencial'))."','$this->c23_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["c23_conlancam"]))
             $resac = db_query("insert into db_acount values($acount,2169,12442,'".AddSlashes(pg_result($resaco,$conresaco,'c23_conlancam'))."','$this->c23_conlancam',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["c23_corgrupocorrente"]))
             $resac = db_query("insert into db_acount values($acount,2169,12443,'".AddSlashes(pg_result($resaco,$conresaco,'c23_corgrupocorrente'))."','$this->c23_corgrupocorrente',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Grupo de lancamentos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c23_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Grupo de lancamentos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c23_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c23_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($c23_sequencial=null,$dbwhere=null) {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {
       if($dbwhere==null || $dbwhere==""){
         $resaco = $this->sql_record($this->sql_query_file($c23_sequencial));
       }else{
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if(($resaco!=false)||($this->numrows!=0)){
         for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,12441,'$c23_sequencial','E')");
           $resac = db_query("insert into db_acount values($acount,2169,12441,'','".AddSlashes(pg_result($resaco,$iresaco,'c23_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,2169,12442,'','".AddSlashes(pg_result($resaco,$iresaco,'c23_conlancam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,2169,12443,'','".AddSlashes(pg_result($resaco,$iresaco,'c23_corgrupocorrente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from conlancamcorgrupocorrente
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c23_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c23_sequencial = $c23_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Grupo de lancamentos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c23_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Grupo de lancamentos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c23_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c23_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:conlancamcorgrupocorrente";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $c23_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from conlancamcorgrupocorrente ";
     $sql .= "      inner join conlancam  on  conlancam.c70_codlan = conlancamcorgrupocorrente.c23_conlancam";
     $sql2 = "";
     if($dbwhere==""){
       if($c23_sequencial!=null ){
         $sql2 .= " where conlancamcorgrupocorrente.c23_sequencial = $c23_sequencial ";
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
   function sql_query_file ( $c23_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from conlancamcorgrupocorrente ";
     $sql2 = "";
     if($dbwhere==""){
       if($c23_sequencial!=null ){
         $sql2 .= " where conlancamcorgrupocorrente.c23_sequencial = $c23_sequencial ";
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

  function sql_query_recurso ( $c23_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){

  	$sql = "select ";
  	if($campos != "*" ){
  		$campos_sql = split("#",$campos);
  		$virgula = "";
  		for($i=0;$i<sizeof($campos_sql);$i++){
  			$sql .= $virgula.$campos_sql[$i];
  			$virgula = ",";
  		}
  	} else {
  		$sql .= $campos;
  	}

  	$sql .= " 	from conlancamcorgrupocorrente ";
  	$sql .= "   inner join conlancam  			on  c70_codlan 		  = conlancamcorgrupocorrente.c23_conlancam";
  	$sql .= "   inner join corgrupocorrente on  k105_sequencial = conlancamcorgrupocorrente.c23_corgrupocorrente";

  	$sql .= "   inner join corrente 			  on
	  																						corrente.k12_id    = corgrupocorrente.k105_id    and
	  																						corrente.k12_data  = corgrupocorrente.k105_data  and
	  																						corrente.k12_autent= corgrupocorrente.k105_autent   ";
  	$sql .= "   inner join cornump 					on
	  																						cornump.k12_id     = corrente.k12_id    and
	  																						cornump.k12_data   = corrente.k12_data  and
	  																						cornump.k12_autent = corrente.k12_autent   ";

  	$sql .= "   inner join reciborecurso 		on 	reciborecurso.k00_numpre = cornump.k12_numpre  ";

  	$sql2 = "";

  	if($dbwhere=="") {

  		if($c23_sequencial!=null ){
  			$sql2 .= " where conlancamcorgrupocorrente.c23_sequencial = $c23_sequencial ";
  		}

  	} else if($dbwhere != ""){

  		$sql2 = " where $dbwhere";
  	}
  		$sql .= $sql2;
  		if($ordem != null ) {

  			$sql .= " order by ";
  			$campos_sql = split("#",$ordem);
  			$virgula = "";

  			for($i=0;$i<sizeof($campos_sql);$i++) {

  				$sql .= $virgula.$campos_sql[$i];
  				$virgula = ",";
  			}
  		}
  	return $sql;
  	}
}
?>