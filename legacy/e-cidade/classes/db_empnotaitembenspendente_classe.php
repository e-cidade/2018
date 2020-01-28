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

//MODULO: empenho
//CLASSE DA ENTIDADE empnotaitembenspendente
class cl_empnotaitembenspendente {
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
   var $e137_sequencial = 0;
   var $e137_empnotaitem = 0;
   var $e137_matestoqueitem = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 e137_sequencial = int4 = Sequencial
                 e137_empnotaitem = int4 = empnotaitem
                 e137_matestoqueitem = int4 = Matestoqueitem
                 ";
   //funcao construtor da classe
   function cl_empnotaitembenspendente() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("empnotaitembenspendente");
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
       $this->e137_sequencial = ($this->e137_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e137_sequencial"]:$this->e137_sequencial);
       $this->e137_empnotaitem = ($this->e137_empnotaitem == ""?@$GLOBALS["HTTP_POST_VARS"]["e137_empnotaitem"]:$this->e137_empnotaitem);
       $this->e137_matestoqueitem = ($this->e137_matestoqueitem == ""?@$GLOBALS["HTTP_POST_VARS"]["e137_matestoqueitem"]:$this->e137_matestoqueitem);
     }else{
       $this->e137_sequencial = ($this->e137_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e137_sequencial"]:$this->e137_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($e137_sequencial){
      $this->atualizacampos();
     if($this->e137_empnotaitem == null ){
       $this->erro_sql = " Campo empnotaitem nao Informado.";
       $this->erro_campo = "e137_empnotaitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e137_matestoqueitem == null ){
       $this->erro_sql = " Campo Matestoqueitem nao Informado.";
       $this->erro_campo = "e137_matestoqueitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($e137_sequencial == "" || $e137_sequencial == null ){
       $result = db_query("select nextval('empnotaitembenspendente_e137_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: empnotaitembenspendente_e137_sequencial_seq do campo: e137_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->e137_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from empnotaitembenspendente_e137_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $e137_sequencial)){
         $this->erro_sql = " Campo e137_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->e137_sequencial = $e137_sequencial;
       }
     }
     if(($this->e137_sequencial == null) || ($this->e137_sequencial == "") ){
       $this->erro_sql = " Campo e137_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empnotaitembenspendente(
                                       e137_sequencial
                                      ,e137_empnotaitem
                                      ,e137_matestoqueitem
                       )
                values (
                                $this->e137_sequencial
                               ,$this->e137_empnotaitem
                               ,$this->e137_matestoqueitem
                      )";

     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "empnotaitembenspendente ($this->e137_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "empnotaitembenspendente já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "empnotaitembenspendente ($this->e137_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e137_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e137_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18890,'$this->e137_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3350,18890,'','".AddSlashes(pg_result($resaco,0,'e137_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3350,18891,'','".AddSlashes(pg_result($resaco,0,'e137_empnotaitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3350,18892,'','".AddSlashes(pg_result($resaco,0,'e137_matestoqueitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($e137_sequencial=null) {
      $this->atualizacampos();
     $sql = " update empnotaitembenspendente set ";
     $virgula = "";
     if(trim($this->e137_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e137_sequencial"])){
       $sql  .= $virgula." e137_sequencial = $this->e137_sequencial ";
       $virgula = ",";
       if(trim($this->e137_sequencial) == null ){
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "e137_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e137_empnotaitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e137_empnotaitem"])){
       $sql  .= $virgula." e137_empnotaitem = $this->e137_empnotaitem ";
       $virgula = ",";
       if(trim($this->e137_empnotaitem) == null ){
         $this->erro_sql = " Campo empnotaitem nao Informado.";
         $this->erro_campo = "e137_empnotaitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e137_matestoqueitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e137_matestoqueitem"])){
       $sql  .= $virgula." e137_matestoqueitem = $this->e137_matestoqueitem ";
       $virgula = ",";
       if(trim($this->e137_matestoqueitem) == null ){
         $this->erro_sql = " Campo Matestoqueitem nao Informado.";
         $this->erro_campo = "e137_matestoqueitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($e137_sequencial!=null){
       $sql .= " e137_sequencial = $this->e137_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e137_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18890,'$this->e137_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e137_sequencial"]) || $this->e137_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3350,18890,'".AddSlashes(pg_result($resaco,$conresaco,'e137_sequencial'))."','$this->e137_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e137_empnotaitem"]) || $this->e137_empnotaitem != "")
           $resac = db_query("insert into db_acount values($acount,3350,18891,'".AddSlashes(pg_result($resaco,$conresaco,'e137_empnotaitem'))."','$this->e137_empnotaitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e137_matestoqueitem"]) || $this->e137_matestoqueitem != "")
           $resac = db_query("insert into db_acount values($acount,3350,18892,'".AddSlashes(pg_result($resaco,$conresaco,'e137_matestoqueitem'))."','$this->e137_matestoqueitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "empnotaitembenspendente nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e137_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "empnotaitembenspendente nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e137_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e137_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($e137_sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e137_sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18890,'$e137_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3350,18890,'','".AddSlashes(pg_result($resaco,$iresaco,'e137_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3350,18891,'','".AddSlashes(pg_result($resaco,$iresaco,'e137_empnotaitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3350,18892,'','".AddSlashes(pg_result($resaco,$iresaco,'e137_matestoqueitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from empnotaitembenspendente
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e137_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e137_sequencial = $e137_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "empnotaitembenspendente nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e137_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "empnotaitembenspendente nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e137_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e137_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:empnotaitembenspendente";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $e137_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empnotaitembenspendente ";
     $sql .= "      inner join matestoqueitem  on  matestoqueitem.m71_codlanc = empnotaitembenspendente.e137_matestoqueitem";
     $sql .= "      inner join empnotaitem  on  empnotaitem.e72_sequencial = empnotaitembenspendente.e137_empnotaitem";
     $sql .= "      inner join matestoque  on  matestoque.m70_codigo = matestoqueitem.m71_codmatestoque";
     $sql .= "      inner join empempitem  on  empempitem.e62_sequencial = empnotaitem.e72_empempitem";
     $sql .= "      inner join empnota  as a on   a.e69_codnota = empnotaitem.e72_codnota";
     $sql2 = "";
     if($dbwhere==""){
       if($e137_sequencial!=null ){
         $sql2 .= " where empnotaitembenspendente.e137_sequencial = $e137_sequencial ";
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
   function sql_query_file ( $e137_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empnotaitembenspendente ";
     $sql2 = "";
     if($dbwhere==""){
       if($e137_sequencial!=null ){
         $sql2 .= " where empnotaitembenspendente.e137_sequencial = $e137_sequencial ";
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

  function sql_query_patrimonio ( $e137_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from empnotaitembenspendente ";
    $sql .= "      inner join matestoqueitem  on  matestoqueitem.m71_codlanc = empnotaitembenspendente.e137_matestoqueitem";
    $sql .= "      inner join matestoqueitemoc  on  matestoqueitem.m71_codlanc = matestoqueitemoc.m73_codmatestoqueitem";
    $sql .= "      inner join empnotaitem  on  empnotaitem.e72_sequencial = empnotaitembenspendente.e137_empnotaitem";
    $sql .= "      inner join matestoque  on  matestoque.m70_codigo = matestoqueitem.m71_codmatestoque";
    $sql .= "      inner join empempitem  on  empempitem.e62_sequencial = empnotaitem.e72_empempitem";
    $sql .= "      inner join empnota  as a on   a.e69_codnota = empnotaitem.e72_codnota";
    $sql .= "      left  join bensempnotaitem on  bensempnotaitem.e136_empnotaitem = empnotaitem.e72_sequencial";
    $sql .= "      left  join bensdispensatombamento on  bensdispensatombamento.e139_empnotaitem = empnotaitem.e72_sequencial";
    $sql2 = "";
    if($dbwhere==""){
      if($e137_sequencial!=null ){
        $sql2 .= " where empnotaitembenspendente.e137_sequencial = $e137_sequencial ";
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

   function sql_query_bens ( $e137_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
  	$sql .= " from empnotaitembenspendente ";
  	$sql .= "      inner join matestoqueitem  on  matestoqueitem.m71_codlanc       = empnotaitembenspendente.e137_matestoqueitem";
  	$sql .= "      inner join empnotaitem     on  empnotaitem.e72_sequencial       = empnotaitembenspendente.e137_empnotaitem";
  	$sql .= "      inner join matestoque      on  matestoque.m70_codigo            = matestoqueitem.m71_codmatestoque";
  	$sql .= "      inner join empempitem      on  empempitem.e62_sequencial        = empnotaitem.e72_empempitem";
  	$sql .= "      inner join empnota         on  empnota.e69_codnota              = empnotaitem.e72_codnota";
  	$sql .= "      inner join empempenho      on  empempenho.e60_numemp            = empnota.e69_numemp";
  	$sql .= "      left  join empnotaord      on  empnotaord.m72_codnota           = empnota.e69_codnota ";
  	$sql .= "      left  join matordemitem    on  matordemitem.m52_codordem        = empnotaord.m72_codordem ";
  	$sql .= "      inner join cgm             on  cgm.z01_numcgm                   = empempenho.e60_numcgm";
  	$sql .= "      inner join pcmater         on  pcmater.pc01_codmater  		       = e62_item";
  	$sql .= "      inner join orcelemento     on  orcelemento.o56_codele           = e62_codele";
  	$sql .= "                                and  orcelemento.o56_anousu           = ".db_getsession("DB_anousu");
  	$sql .= "      left  join bensempnotaitem on  bensempnotaitem.e136_empnotaitem = empnotaitem.e72_sequencial";
  	$sql .= "      left  join matordemanu     on  m53_codordem = m52_codordem ";
  	$sql2 = "";
  	if($dbwhere==""){
  		if($e137_sequencial!=null ){
  			$sql2 .= " where empnotaitembenspendente.e137_sequencial = $e137_sequencial ";
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

  function sql_query_bens_nota ( $e137_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from empnotaitembenspendente ";
    $sql .= "      inner join matestoqueitem  on  matestoqueitem.m71_codlanc       = empnotaitembenspendente.e137_matestoqueitem";
    $sql .= "      inner join empnotaitem     on  empnotaitem.e72_sequencial       = empnotaitembenspendente.e137_empnotaitem";
    $sql .= "      inner join matestoque      on  matestoque.m70_codigo            = matestoqueitem.m71_codmatestoque";
    $sql .= "      inner join empempitem      on  empempitem.e62_sequencial        = empnotaitem.e72_empempitem";
    $sql .= "      inner join empnota         on  empnota.e69_codnota              = empnotaitem.e72_codnota";
    $sql .= "      left  join empnotaele      on  empnotaele.e70_codnota           = empnota.e69_codnota";
    $sql .= "      inner join empempenho      on  empempenho.e60_numemp            = empnota.e69_numemp";
    $sql .= "      left  join empnotaord      on  empnotaord.m72_codnota           = empnota.e69_codnota ";
    $sql .= "      left  join matordemitem    on  matordemitem.m52_codordem        = empnotaord.m72_codordem ";
    $sql .= "      inner join cgm             on  cgm.z01_numcgm                   = empempenho.e60_numcgm";
    $sql .= "      inner join pcmater         on  pcmater.pc01_codmater  		       = e62_item";
    $sql .= "      inner join orcelemento     on  orcelemento.o56_codele           = e62_codele";
    $sql .= "                                and  orcelemento.o56_anousu           = ".db_getsession("DB_anousu");
    $sql .= "      left  join bensempnotaitem on  bensempnotaitem.e136_empnotaitem = empnotaitem.e72_sequencial";
    $sql .= "      left  join matordemanu     on  m53_codordem = m52_codordem ";
    $sql2 = "";
    if($dbwhere==""){
    		if($e137_sequencial!=null ){
    		  $sql2 .= " where empnotaitembenspendente.e137_sequencial = $e137_sequencial ";
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

  public function sql_query_nota ($campos = "*",$dbwhere = null){

    $sql  = "select {$campos}";
    $sql .= " from empnotaitembenspendente ";
    $sql .= "      inner join empnotaitem  on  empnotaitem.e72_sequencial = empnotaitembenspendente.e137_empnotaitem";
    $sql .= "      inner join empnota      on  empnota.e69_codnota = empnotaitem.e72_codnota";

    $dbwhere = trim($dbwhere);
    if (!empty($dbwhere)) {
      $sql .= " where {$dbwhere} ";
    }

    return $sql;
  }
}
?>