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

//MODULO: juridico
//CLASSE DA ENTIDADE processoforocodforoant
class cl_processoforocodforoant {
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
   var $v85_sequencial = 0;
   var $v85_processoforo = 0;
   var $v85_codforo = null;
   var $v85_data_dia = null;
   var $v85_data_mes = null;
   var $v85_data_ano = null;
   var $v85_data = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 v85_sequencial = int8 = Sequencial da Tabela
                 v85_processoforo = int8 = Código Processo do Foro
                 v85_codforo = varchar(30) = Código Anterior
                 v85_data = date = Data
                 ";
   //funcao construtor da classe
   function cl_processoforocodforoant() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("processoforocodforoant");
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
       $this->v85_sequencial = ($this->v85_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v85_sequencial"]:$this->v85_sequencial);
       $this->v85_processoforo = ($this->v85_processoforo == ""?@$GLOBALS["HTTP_POST_VARS"]["v85_processoforo"]:$this->v85_processoforo);
       $this->v85_codforo = ($this->v85_codforo == ""?@$GLOBALS["HTTP_POST_VARS"]["v85_codforo"]:$this->v85_codforo);
       if($this->v85_data == ""){
         $this->v85_data_dia = ($this->v85_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["v85_data_dia"]:$this->v85_data_dia);
         $this->v85_data_mes = ($this->v85_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["v85_data_mes"]:$this->v85_data_mes);
         $this->v85_data_ano = ($this->v85_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["v85_data_ano"]:$this->v85_data_ano);
         if($this->v85_data_dia != ""){
            $this->v85_data = $this->v85_data_ano."-".$this->v85_data_mes."-".$this->v85_data_dia;
         }
       }
     }else{
       $this->v85_sequencial = ($this->v85_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v85_sequencial"]:$this->v85_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($v85_sequencial){
      $this->atualizacampos();
     if($this->v85_processoforo == null ){
       $this->erro_sql = " Campo Código Processo do Foro nao Informado.";
       $this->erro_campo = "v85_processoforo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v85_codforo == null ){
       $this->erro_sql = " Campo Código Anterior nao Informado.";
       $this->erro_campo = "v85_codforo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v85_data == null ){
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "v85_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($v85_sequencial == "" || $v85_sequencial == null ){
       $result = db_query("select nextval('processoforocodforoant_v85_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: processoforocodforoant_v85_sequencial_seq do campo: v85_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->v85_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from processoforocodforoant_v85_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $v85_sequencial)){
         $this->erro_sql = " Campo v85_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->v85_sequencial = $v85_sequencial;
       }
     }
     if(($this->v85_sequencial == null) || ($this->v85_sequencial == "") ){
       $this->erro_sql = " Campo v85_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into processoforocodforoant(
                                       v85_sequencial
                                      ,v85_processoforo
                                      ,v85_codforo
                                      ,v85_data
                       )
                values (
                                $this->v85_sequencial
                               ,$this->v85_processoforo
                               ,'$this->v85_codforo'
                               ,".($this->v85_data == "null" || $this->v85_data == ""?"null":"'".$this->v85_data."'")."
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Código anterior do Processo do Foro ($this->v85_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Código anterior do Processo do Foro já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Código anterior do Processo do Foro ($this->v85_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v85_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->v85_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18236,'$this->v85_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3218,18236,'','".AddSlashes(pg_result($resaco,0,'v85_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3218,18237,'','".AddSlashes(pg_result($resaco,0,'v85_processoforo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3218,18238,'','".AddSlashes(pg_result($resaco,0,'v85_codforo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3218,18239,'','".AddSlashes(pg_result($resaco,0,'v85_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($v85_sequencial=null) {
      $this->atualizacampos();
     $sql = " update processoforocodforoant set ";
     $virgula = "";
     if(trim($this->v85_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v85_sequencial"])){
       $sql  .= $virgula." v85_sequencial = $this->v85_sequencial ";
       $virgula = ",";
       if(trim($this->v85_sequencial) == null ){
         $this->erro_sql = " Campo Sequencial da Tabela nao Informado.";
         $this->erro_campo = "v85_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v85_processoforo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v85_processoforo"])){
       $sql  .= $virgula." v85_processoforo = $this->v85_processoforo ";
       $virgula = ",";
       if(trim($this->v85_processoforo) == null ){
         $this->erro_sql = " Campo Código Processo do Foro nao Informado.";
         $this->erro_campo = "v85_processoforo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v85_codforo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v85_codforo"])){
       $sql  .= $virgula." v85_codforo = '$this->v85_codforo' ";
       $virgula = ",";
       if(trim($this->v85_codforo) == null ){
         $this->erro_sql = " Campo Código Anterior nao Informado.";
         $this->erro_campo = "v85_codforo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v85_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v85_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v85_data_dia"] !="") ){
       $sql  .= $virgula." v85_data = '$this->v85_data' ";
       $virgula = ",";
       if(trim($this->v85_data) == null ){
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "v85_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["v85_data_dia"])){
         $sql  .= $virgula." v85_data = null ";
         $virgula = ",";
         if(trim($this->v85_data) == null ){
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "v85_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($v85_sequencial!=null){
       $sql .= " v85_sequencial = $this->v85_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->v85_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18236,'$this->v85_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v85_sequencial"]) || $this->v85_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3218,18236,'".AddSlashes(pg_result($resaco,$conresaco,'v85_sequencial'))."','$this->v85_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v85_processoforo"]) || $this->v85_processoforo != "")
           $resac = db_query("insert into db_acount values($acount,3218,18237,'".AddSlashes(pg_result($resaco,$conresaco,'v85_processoforo'))."','$this->v85_processoforo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v85_codforo"]) || $this->v85_codforo != "")
           $resac = db_query("insert into db_acount values($acount,3218,18238,'".AddSlashes(pg_result($resaco,$conresaco,'v85_codforo'))."','$this->v85_codforo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v85_data"]) || $this->v85_data != "")
           $resac = db_query("insert into db_acount values($acount,3218,18239,'".AddSlashes(pg_result($resaco,$conresaco,'v85_data'))."','$this->v85_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Código anterior do Processo do Foro nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v85_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Código anterior do Processo do Foro nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v85_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v85_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($v85_sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($v85_sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18236,'$v85_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3218,18236,'','".AddSlashes(pg_result($resaco,$iresaco,'v85_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3218,18237,'','".AddSlashes(pg_result($resaco,$iresaco,'v85_processoforo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3218,18238,'','".AddSlashes(pg_result($resaco,$iresaco,'v85_codforo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3218,18239,'','".AddSlashes(pg_result($resaco,$iresaco,'v85_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from processoforocodforoant
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($v85_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " v85_sequencial = $v85_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Código anterior do Processo do Foro nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$v85_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Código anterior do Processo do Foro nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$v85_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$v85_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:processoforocodforoant";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $v85_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from processoforocodforoant ";
     $sql .= "      inner join processoforo  on  processoforo.v70_sequencial = processoforocodforoant.v85_processoforo";
     $sql .= "      inner join db_config  on  db_config.codigo = processoforo.v70_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = processoforo.v70_id_usuario";
     $sql .= "      inner join vara  on  vara.v53_codvara = processoforo.v70_vara";
     $sql .= "      left  join processoforomov  on  processoforomov.v73_sequencial = processoforo.v70_processoforomov";
     $sql .= "      inner join cartorio  on  cartorio.v82_sequencial = processoforo.v70_cartorio";
     $sql .= "                          and  cartorio.v82_extrajudicial = false";
     $sql2 = "";
     if($dbwhere==""){
       if($v85_sequencial!=null ){
         $sql2 .= " where processoforocodforoant.v85_sequencial = $v85_sequencial ";
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
   function sql_query_file ( $v85_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from processoforocodforoant ";
     $sql2 = "";
     if($dbwhere==""){
       if($v85_sequencial!=null ){
         $sql2 .= " where processoforocodforoant.v85_sequencial = $v85_sequencial ";
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