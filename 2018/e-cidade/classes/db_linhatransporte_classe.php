<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

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

//MODULO: transporteescolar
//CLASSE DA ENTIDADE linhatransporte
class cl_linhatransporte {
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
   var $tre06_sequencial = 0;
   var $tre06_nome = null;
   var $tre06_abreviatura = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 tre06_sequencial = int4 = Sequencial
                 tre06_nome = varchar(60) = Nome
                 tre06_abreviatura = varchar(10) = Abreviatura
                 ";
   //funcao construtor da classe
   function cl_linhatransporte() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("linhatransporte");
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
       $this->tre06_sequencial = ($this->tre06_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["tre06_sequencial"]:$this->tre06_sequencial);
       $this->tre06_nome = ($this->tre06_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["tre06_nome"]:$this->tre06_nome);
       $this->tre06_abreviatura = ($this->tre06_abreviatura == ""?@$GLOBALS["HTTP_POST_VARS"]["tre06_abreviatura"]:$this->tre06_abreviatura);
     }else{
       $this->tre06_sequencial = ($this->tre06_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["tre06_sequencial"]:$this->tre06_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($tre06_sequencial){
      $this->atualizacampos();
     if($this->tre06_nome == null ){
       $this->erro_sql = " Campo Nome nao Informado.";
       $this->erro_campo = "tre06_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tre06_abreviatura == null ){
       $this->erro_sql = " Campo Abreviatura nao Informado.";
       $this->erro_campo = "tre06_abreviatura";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($tre06_sequencial == "" || $tre06_sequencial == null ){
       $result = db_query("select nextval('linhatransporte_tre06_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: linhatransporte_tre06_sequencial_seq do campo: tre06_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->tre06_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from linhatransporte_tre06_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $tre06_sequencial)){
         $this->erro_sql = " Campo tre06_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->tre06_sequencial = $tre06_sequencial;
       }
     }
     if(($this->tre06_sequencial == null) || ($this->tre06_sequencial == "") ){
       $this->erro_sql = " Campo tre06_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into linhatransporte(
                                       tre06_sequencial
                                      ,tre06_nome
                                      ,tre06_abreviatura
                       )
                values (
                                $this->tre06_sequencial
                               ,'$this->tre06_nome'
                               ,'$this->tre06_abreviatura'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Linha de Transporte ($this->tre06_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Linha de Transporte já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Linha de Transporte ($this->tre06_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tre06_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->tre06_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20093,'$this->tre06_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3603,20093,'','".AddSlashes(pg_result($resaco,0,'tre06_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3603,20094,'','".AddSlashes(pg_result($resaco,0,'tre06_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3603,20095,'','".AddSlashes(pg_result($resaco,0,'tre06_abreviatura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($tre06_sequencial=null) {
      $this->atualizacampos();
     $sql = " update linhatransporte set ";
     $virgula = "";
     if(trim($this->tre06_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tre06_sequencial"])){
       $sql  .= $virgula." tre06_sequencial = $this->tre06_sequencial ";
       $virgula = ",";
       if(trim($this->tre06_sequencial) == null ){
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "tre06_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tre06_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tre06_nome"])){
       $sql  .= $virgula." tre06_nome = '$this->tre06_nome' ";
       $virgula = ",";
       if(trim($this->tre06_nome) == null ){
         $this->erro_sql = " Campo Nome nao Informado.";
         $this->erro_campo = "tre06_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tre06_abreviatura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tre06_abreviatura"])){
       $sql  .= $virgula." tre06_abreviatura = '$this->tre06_abreviatura' ";
       $virgula = ",";
       if(trim($this->tre06_abreviatura) == null ){
         $this->erro_sql = " Campo Abreviatura nao Informado.";
         $this->erro_campo = "tre06_abreviatura";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($tre06_sequencial!=null){
       $sql .= " tre06_sequencial = $this->tre06_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->tre06_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20093,'$this->tre06_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tre06_sequencial"]) || $this->tre06_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3603,20093,'".AddSlashes(pg_result($resaco,$conresaco,'tre06_sequencial'))."','$this->tre06_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tre06_nome"]) || $this->tre06_nome != "")
             $resac = db_query("insert into db_acount values($acount,3603,20094,'".AddSlashes(pg_result($resaco,$conresaco,'tre06_nome'))."','$this->tre06_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tre06_abreviatura"]) || $this->tre06_abreviatura != "")
             $resac = db_query("insert into db_acount values($acount,3603,20095,'".AddSlashes(pg_result($resaco,$conresaco,'tre06_abreviatura'))."','$this->tre06_abreviatura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Linha de Transporte nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->tre06_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Linha de Transporte nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->tre06_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tre06_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($tre06_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($tre06_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20093,'$tre06_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3603,20093,'','".AddSlashes(pg_result($resaco,$iresaco,'tre06_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3603,20094,'','".AddSlashes(pg_result($resaco,$iresaco,'tre06_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3603,20095,'','".AddSlashes(pg_result($resaco,$iresaco,'tre06_abreviatura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from linhatransporte
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($tre06_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " tre06_sequencial = $tre06_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Linha de Transporte nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$tre06_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Linha de Transporte nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$tre06_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$tre06_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:linhatransporte";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $tre06_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from linhatransporte ";
     $sql2 = "";
     if($dbwhere==""){
       if($tre06_sequencial!=null ){
         $sql2 .= " where linhatransporte.tre06_sequencial = $tre06_sequencial ";
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
   function sql_query_file ( $tre06_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from linhatransporte ";
     $sql2 = "";
     if($dbwhere==""){
       if($tre06_sequencial!=null ){
         $sql2 .= " where linhatransporte.tre06_sequencial = $tre06_sequencial ";
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

  function sql_query_linha_horario_veiculo ( $sWhere = null, $sOrder = null ){

    $sSql  = " select";
    $sSql .= "        tre09_tipo              as itinerario";
    $sSql .= "       ,tre07_sequencial        as codigo_horario";
    $sSql .= "       ,tre08_sequencial        as vinculo_veiculo_horario";
    $sSql .= "       ,tre07_horasaida         as hora_saida";
    $sSql .= "       ,tre07_horachegada       as hora_chegada";
    $sSql .= "       ,tre01_identificacao     as placa";
    $sSql .= "       ,tre01_numeropassageiros as vagas";
    $sSql .= "       ,(select count(*)";
    $sSql .= "          from linhatransportepontoparadaaluno ";
    $sSql .= "         inner join linhatransportehorarioveiculo as horariovinculado on horariovinculado.tre08_sequencial = tre12_linhatransportehorarioveiculo";
    $sSql .= "         where horariovinculado.tre08_linhatransportehorario     = linhatransportehorarioveiculo.tre08_linhatransportehorario";
    $sSql .= "           and horariovinculado.tre08_veiculotransportemunicipal = linhatransportehorarioveiculo.tre08_veiculotransportemunicipal";
    $sSql .= "       ) as vagas_ocupadas";
    $sSql .= "       ,case ";
    $sSql .= "          when tre03_sequencial is not null";
    $sSql .= "            then  ( select z01_nome ";
    $sSql .= "                      from cgm";
    $sSql .= "                     where z01_numcgm = tre03_cgm";
    $sSql .= "                  )";
    $sSql .= "          else ( select ve22_descr ";
    $sSql .= "                   from veiculos";
    $sSql .= "                  inner join veiccadmodelo on ve22_codigo = ve01_veiccadmodelo";
    $sSql .= "                  where veiculos.ve01_codigo = tre02_veiculos                ";
    $sSql .= "               )";
    $sSql .= "        end as nome_veiculo";
    $sSql .= " from linhatransporte";
    $sSql .= " inner join linhatransporteitinerario          on tre09_linhatransporte           = tre06_sequencial";
    $sSql .= " inner join linhatransportehorario             on tre07_linhatransporteitinerario = tre09_sequencial";
    $sSql .= " inner join linhatransportehorarioveiculo      on tre08_linhatransportehorario    = tre07_sequencial";
    $sSql .= " inner join veiculotransportemunicipal         on tre01_sequencial                = tre08_veiculotransportemunicipal";
    $sSql .= "  left join veiculotransportemunicipalterceiro on tre03_veiculotransportemunicipal = tre01_sequencial";
    $sSql .= "  left join veiculotransportemunicipalveiculos on tre02_veiculotransportemunicipal = tre01_sequencial";
    
    if ( !empty( $sWhere ) ) {
      $sSql .= " where {$sWhere}";
    }

    if ( !empty( $sOrder ) ) {
      $sSql .= " order by {$sOrder}";
    } else {
      $sSql .= " order by tre09_tipo, tre07_horasaida, tre07_horachegada";
    }
    return $sSql;
  }
}
?>