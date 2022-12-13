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

//MODULO: Compras
//CLASSE DA ENTIDADE solicitavinculo
class cl_solicitavinculo {
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
   var $pc53_sequencial = 0;
   var $pc53_solicitapai = 0;
   var $pc53_solicitafilho = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 pc53_sequencial = int4 = Código Sequencial
                 pc53_solicitapai = int4 = Código da solicitação principal
                 pc53_solicitafilho = int4 = Código da solicitação vinculada
                 ";
   //funcao construtor da classe
   function cl_solicitavinculo() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("solicitavinculo");
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
       $this->pc53_sequencial = ($this->pc53_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc53_sequencial"]:$this->pc53_sequencial);
       $this->pc53_solicitapai = ($this->pc53_solicitapai == ""?@$GLOBALS["HTTP_POST_VARS"]["pc53_solicitapai"]:$this->pc53_solicitapai);
       $this->pc53_solicitafilho = ($this->pc53_solicitafilho == ""?@$GLOBALS["HTTP_POST_VARS"]["pc53_solicitafilho"]:$this->pc53_solicitafilho);
     }else{
       $this->pc53_sequencial = ($this->pc53_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc53_sequencial"]:$this->pc53_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($pc53_sequencial){
      $this->atualizacampos();
     if($this->pc53_solicitapai == null ){
       $this->erro_sql = " Campo Código da solicitação principal nao Informado.";
       $this->erro_campo = "pc53_solicitapai";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc53_solicitafilho == null ){
       $this->erro_sql = " Campo Código da solicitação vinculada nao Informado.";
       $this->erro_campo = "pc53_solicitafilho";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($pc53_sequencial == "" || $pc53_sequencial == null ){
       $result = db_query("select nextval('solicitavinculo_pc53_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: solicitavinculo_pc53_sequencial_seq do campo: pc53_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->pc53_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from solicitavinculo_pc53_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc53_sequencial)){
         $this->erro_sql = " Campo pc53_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc53_sequencial = $pc53_sequencial;
       }
     }
     if(($this->pc53_sequencial == null) || ($this->pc53_sequencial == "") ){
       $this->erro_sql = " Campo pc53_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into solicitavinculo(
                                       pc53_sequencial
                                      ,pc53_solicitapai
                                      ,pc53_solicitafilho
                       )
                values (
                                $this->pc53_sequencial
                               ,$this->pc53_solicitapai
                               ,$this->pc53_solicitafilho
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Vinculos da Solicitação ($this->pc53_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Vinculos da Solicitação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Vinculos da Solicitação ($this->pc53_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc53_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc53_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15199,'$this->pc53_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2677,15199,'','".AddSlashes(pg_result($resaco,0,'pc53_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2677,15200,'','".AddSlashes(pg_result($resaco,0,'pc53_solicitapai'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2677,15201,'','".AddSlashes(pg_result($resaco,0,'pc53_solicitafilho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($pc53_sequencial=null) {
      $this->atualizacampos();
     $sql = " update solicitavinculo set ";
     $virgula = "";
     if(trim($this->pc53_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc53_sequencial"])){
       $sql  .= $virgula." pc53_sequencial = $this->pc53_sequencial ";
       $virgula = ",";
       if(trim($this->pc53_sequencial) == null ){
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "pc53_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc53_solicitapai)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc53_solicitapai"])){
       $sql  .= $virgula." pc53_solicitapai = $this->pc53_solicitapai ";
       $virgula = ",";
       if(trim($this->pc53_solicitapai) == null ){
         $this->erro_sql = " Campo Código da solicitação principal nao Informado.";
         $this->erro_campo = "pc53_solicitapai";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc53_solicitafilho)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc53_solicitafilho"])){
       $sql  .= $virgula." pc53_solicitafilho = $this->pc53_solicitafilho ";
       $virgula = ",";
       if(trim($this->pc53_solicitafilho) == null ){
         $this->erro_sql = " Campo Código da solicitação vinculada nao Informado.";
         $this->erro_campo = "pc53_solicitafilho";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($pc53_sequencial!=null){
       $sql .= " pc53_sequencial = $this->pc53_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc53_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15199,'$this->pc53_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc53_sequencial"]) || $this->pc53_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2677,15199,'".AddSlashes(pg_result($resaco,$conresaco,'pc53_sequencial'))."','$this->pc53_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc53_solicitapai"]) || $this->pc53_solicitapai != "")
           $resac = db_query("insert into db_acount values($acount,2677,15200,'".AddSlashes(pg_result($resaco,$conresaco,'pc53_solicitapai'))."','$this->pc53_solicitapai',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc53_solicitafilho"]) || $this->pc53_solicitafilho != "")
           $resac = db_query("insert into db_acount values($acount,2677,15201,'".AddSlashes(pg_result($resaco,$conresaco,'pc53_solicitafilho'))."','$this->pc53_solicitafilho',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vinculos da Solicitação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc53_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Vinculos da Solicitação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc53_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc53_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($pc53_sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc53_sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15199,'$pc53_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2677,15199,'','".AddSlashes(pg_result($resaco,$iresaco,'pc53_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2677,15200,'','".AddSlashes(pg_result($resaco,$iresaco,'pc53_solicitapai'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2677,15201,'','".AddSlashes(pg_result($resaco,$iresaco,'pc53_solicitafilho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from solicitavinculo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc53_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc53_sequencial = $pc53_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vinculos da Solicitação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc53_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Vinculos da Solicitação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc53_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc53_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:solicitavinculo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $pc53_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from solicitavinculo ";
     $sql .= "      inner join solicita  on  solicita.pc10_numero = solicitavinculo.pc53_solicitapai";
     $sql .= "      inner join db_config  on  db_config.codigo = solicita.pc10_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = solicita.pc10_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = solicita.pc10_depto";
     $sql .= "      inner join solicitacaotipo  on  solicitacaotipo.pc52_sequencial = solicita.pc10_solicitacaotipo";
     $sql2 = "";
     if($dbwhere==""){
       if($pc53_sequencial!=null ){
         $sql2 .= " where solicitavinculo.pc53_sequencial = $pc53_sequencial ";
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
   function sql_query_file ( $pc53_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from solicitavinculo ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc53_sequencial!=null ){
         $sql2 .= " where solicitavinculo.pc53_sequencial = $pc53_sequencial ";
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

  function sql_query_filhas ( $pc53_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from solicitavinculo ";
     $sql .= "      inner join solicita  on  solicita.pc10_numero = solicitavinculo.pc53_solicitafilho";
     $sql .= "       left join solicitaanulada  on solicitaanulada.pc67_solicita = solicita.pc10_numero ";
     $sql .= "      inner join db_config  on  db_config.codigo = solicita.pc10_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = solicita.pc10_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = solicita.pc10_depto";
     $sql .= "      inner join solicitacaotipo  on  solicitacaotipo.pc52_sequencial = solicita.pc10_solicitacaotipo";
     $sql2 = "";
     if($dbwhere==""){
       if($pc53_sequencial!=null ){
         $sql2 .= " where solicitavinculo.pc53_sequencial = $pc53_sequencial ";
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

  /*
   *
   */
  function sql_query_aberturaestimativa( $pc11_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from solicitavinculo estcedente                                                                      ";
     $sql .= "   inner join solicita abertura         on abertura.pc10_numero         = estcedente.pc53_solicitapai ";
     $sql .= "   inner join solicitavinculo estrecebe on abertura.pc10_numero         = estrecebe.pc53_solicitapai  ";
     $sql .= "   inner join solicita  recebe          on estrecebe.pc53_solicitafilho = recebe.pc10_numero          ";
     $sql .= "    left join solicitaanulada           on pc67_solicita                = recebe.pc10_numero          ";
     $sql .= "    left join solicitaregistropreco     on pc54_solicita                = abertura.pc10_numero        ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc11_codigo!=null ){
         $sql2 .= " where solicitem.pc11_codigo = $pc11_codigo ";
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

   /*
   *
   */
  function sql_query_abertura( $pc11_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from solicitavinculo estcedente                                                                      ";
     $sql .= "   inner join solicita abertura         on abertura.pc10_numero         = estcedente.pc53_solicitapai ";
     $sql .= "    left join solicitaregistropreco     on pc54_solicita                = abertura.pc10_numero        ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc11_codigo!=null ){
         $sql2 .= " where solicitem.pc11_codigo = $pc11_codigo ";
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