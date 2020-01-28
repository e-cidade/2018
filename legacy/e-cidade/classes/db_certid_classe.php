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

//MODULO: dividaativa
//CLASSE DA ENTIDADE certid
class cl_certid {
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
   var $v13_certid = 0;
   var $v13_dtemis_dia = null;
   var $v13_dtemis_mes = null;
   var $v13_dtemis_ano = null;
   var $v13_dtemis = null;
   var $v13_memo = 0;
   var $v13_login = null;
   var $v13_instit = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 v13_certid = int4 = certidao
                 v13_dtemis = date = data de emissao
                 v13_memo = oid = texto da certidao
                 v13_login = varchar(8) = login do usuario
                 v13_instit = int4 = Cod. Instituição
                 ";
   //funcao construtor da classe
   function cl_certid() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("certid");
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
       $this->v13_certid = ($this->v13_certid == ""?@$GLOBALS["HTTP_POST_VARS"]["v13_certid"]:$this->v13_certid);
       if($this->v13_dtemis == ""){
         $this->v13_dtemis_dia = ($this->v13_dtemis_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["v13_dtemis_dia"]:$this->v13_dtemis_dia);
         $this->v13_dtemis_mes = ($this->v13_dtemis_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["v13_dtemis_mes"]:$this->v13_dtemis_mes);
         $this->v13_dtemis_ano = ($this->v13_dtemis_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["v13_dtemis_ano"]:$this->v13_dtemis_ano);
         if($this->v13_dtemis_dia != ""){
            $this->v13_dtemis = $this->v13_dtemis_ano."-".$this->v13_dtemis_mes."-".$this->v13_dtemis_dia;
         }
       }
       $this->v13_memo = ($this->v13_memo == ""?@$GLOBALS["HTTP_POST_VARS"]["v13_memo"]:$this->v13_memo);
       $this->v13_login = ($this->v13_login == ""?@$GLOBALS["HTTP_POST_VARS"]["v13_login"]:$this->v13_login);
       $this->v13_instit = ($this->v13_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["v13_instit"]:$this->v13_instit);
     }else{
       $this->v13_certid = ($this->v13_certid == ""?@$GLOBALS["HTTP_POST_VARS"]["v13_certid"]:$this->v13_certid);
     }
   }
   // funcao para inclusao
   function incluir ($v13_certid){
      $this->atualizacampos();
     if($this->v13_dtemis == null ){
       $this->erro_sql = " Campo data de emissao nao Informado.";
       $this->erro_campo = "v13_dtemis_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v13_memo == null ){
       $this->erro_sql = " Campo texto da certidao nao Informado.";
       $this->erro_campo = "v13_memo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v13_login == null ){
       $this->erro_sql = " Campo login do usuario nao Informado.";
       $this->erro_campo = "v13_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v13_instit == null ){
       $this->erro_sql = " Campo Cod. Instituição nao Informado.";
       $this->erro_campo = "v13_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->v13_certid = $v13_certid;
     if(($this->v13_certid == null) || ($this->v13_certid == "") ){
       $this->erro_sql = " Campo v13_certid nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into certid(
                                       v13_certid
                                      ,v13_dtemis
                                      ,v13_memo
                                      ,v13_login
                                      ,v13_instit
                       )
                values (
                                $this->v13_certid
                               ,".($this->v13_dtemis == "null" || $this->v13_dtemis == ""?"null":"'".$this->v13_dtemis."'")."
                               ,$this->v13_memo
                               ,'$this->v13_login'
                               ,$this->v13_instit
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->v13_certid) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->v13_certid) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v13_certid;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->v13_certid));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,527,'$this->v13_certid','I')");
       $resac = db_query("insert into db_acount values($acount,100,527,'','".AddSlashes(pg_result($resaco,0,'v13_certid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100,528,'','".AddSlashes(pg_result($resaco,0,'v13_dtemis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100,529,'','".AddSlashes(pg_result($resaco,0,'v13_memo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100,530,'','".AddSlashes(pg_result($resaco,0,'v13_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100,10576,'','".AddSlashes(pg_result($resaco,0,'v13_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($v13_certid=null) {
      $this->atualizacampos();
     $sql = " update certid set ";
     $virgula = "";
     if(trim($this->v13_certid)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v13_certid"])){
       $sql  .= $virgula." v13_certid = $this->v13_certid ";
       $virgula = ",";
       if(trim($this->v13_certid) == null ){
         $this->erro_sql = " Campo certidao nao Informado.";
         $this->erro_campo = "v13_certid";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v13_dtemis)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v13_dtemis_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v13_dtemis_dia"] !="") ){
       $sql  .= $virgula." v13_dtemis = '$this->v13_dtemis' ";
       $virgula = ",";
       if(trim($this->v13_dtemis) == null ){
         $this->erro_sql = " Campo data de emissao nao Informado.";
         $this->erro_campo = "v13_dtemis_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["v13_dtemis_dia"])){
         $sql  .= $virgula." v13_dtemis = null ";
         $virgula = ",";
         if(trim($this->v13_dtemis) == null ){
           $this->erro_sql = " Campo data de emissao nao Informado.";
           $this->erro_campo = "v13_dtemis_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->v13_memo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v13_memo"])){
       $sql  .= $virgula." v13_memo = $this->v13_memo ";
       $virgula = ",";
       if(trim($this->v13_memo) == null ){
         $this->erro_sql = " Campo texto da certidao nao Informado.";
         $this->erro_campo = "v13_memo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v13_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v13_login"])){
       $sql  .= $virgula." v13_login = '$this->v13_login' ";
       $virgula = ",";
       if(trim($this->v13_login) == null ){
         $this->erro_sql = " Campo login do usuario nao Informado.";
         $this->erro_campo = "v13_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v13_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v13_instit"])){
       $sql  .= $virgula." v13_instit = $this->v13_instit ";
       $virgula = ",";
       if(trim($this->v13_instit) == null ){
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "v13_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($v13_certid!=null){
       $sql .= " v13_certid = $this->v13_certid";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->v13_certid));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,527,'$this->v13_certid','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v13_certid"]))
           $resac = db_query("insert into db_acount values($acount,100,527,'".AddSlashes(pg_result($resaco,$conresaco,'v13_certid'))."','$this->v13_certid',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v13_dtemis"]))
           $resac = db_query("insert into db_acount values($acount,100,528,'".AddSlashes(pg_result($resaco,$conresaco,'v13_dtemis'))."','$this->v13_dtemis',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v13_memo"]))
           $resac = db_query("insert into db_acount values($acount,100,529,'".AddSlashes(pg_result($resaco,$conresaco,'v13_memo'))."','$this->v13_memo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v13_login"]))
           $resac = db_query("insert into db_acount values($acount,100,530,'".AddSlashes(pg_result($resaco,$conresaco,'v13_login'))."','$this->v13_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v13_instit"]))
           $resac = db_query("insert into db_acount values($acount,100,10576,'".AddSlashes(pg_result($resaco,$conresaco,'v13_instit'))."','$this->v13_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v13_certid;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v13_certid;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v13_certid;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($v13_certid=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($v13_certid));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,527,'$v13_certid','E')");
         $resac = db_query("insert into db_acount values($acount,100,527,'','".AddSlashes(pg_result($resaco,$iresaco,'v13_certid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100,528,'','".AddSlashes(pg_result($resaco,$iresaco,'v13_dtemis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100,529,'','".AddSlashes(pg_result($resaco,$iresaco,'v13_memo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100,530,'','".AddSlashes(pg_result($resaco,$iresaco,'v13_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100,10576,'','".AddSlashes(pg_result($resaco,$iresaco,'v13_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from certid
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($v13_certid != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " v13_certid = $v13_certid ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$v13_certid;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$v13_certid;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$v13_certid;
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
        $this->erro_sql   = "Record Vazio na Tabela:certid";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $v13_certid=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from certid ";
    $sql .= "      inner join db_config  on  db_config.codigo = certid.v13_instit";
    $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
    $sql2 = "";
    if($dbwhere==""){
      if($v13_certid!=null ){
        $sql2 .= " where certid.v13_certid = $v13_certid ";
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
   function sql_query_file ( $v13_certid=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from certid ";
    $sql2 = "";
    if($dbwhere==""){
      if($v13_certid!=null ){
        $sql2 .= " where certid.v13_certid = $v13_certid ";
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
   function sql_query_ini ( $v13_certid=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from certid ";
    $sql .= "       left join inicialcert on certid.v13_certid=inicialcert.v51_certidao ";
    $sql2 = "";
    if($dbwhere==""){
      if($v13_certid!=null ){
        $sql2 .= " where certid.v13_certid = $v13_certid ";
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
   function sql_query_tip ( $v13_certid=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from certid ";
    $sql .= "       left join certdiv   on certid.v13_certid  = certdiv.v14_certid ";
    $sql .= "       left join certter   on certid.v13_certid  = certter.v14_certid ";
    $sql .= "       left join divida    on certdiv.v14_coddiv = divida.v01_coddiv ";
    $sql .= "       left join issvardiv on divida.v01_coddiv  = issvardiv.q19_coddiv";
    $sql .= "       left join proced    on proced.v03_codigo  = divida.v01_proced ";
    $sql2 = "";
    if($dbwhere==""){
      if($v13_certid!=null ){
        $sql2 .= " where certid.v13_certid = $v13_certid ";
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

   function sql_query_cgm ( $v13_certid=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from certid ";
    $sql .= "       left join certdiv       on certid.v13_certid  = certdiv.v14_certid ";
    $sql .= "       left join certter       on certid.v13_certid  = certter.v14_certid ";
    $sql .= "       left join divida        on certdiv.v14_coddiv = divida.v01_coddiv ";
    $sql .= "       left join termo         on certter.v14_parcel = termo.v07_parcel ";
    $sql .= "       left join cgm cgmtermo  on termo.v07_numcgm   = cgmtermo.z01_numcgm ";
    $sql .= "       left join cgm cgmdivida on divida.v01_numcgm  = cgmdivida.z01_numcgm ";
    $sql2 = "";
    if($dbwhere==""){
      if($v13_certid!=null ){
        $sql2 .= " where certid.v13_certid = $v13_certid ";
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

  function sql_query_inicial_cda ( $v13_certid=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from certid ";
    $sql .= "       left join certdiv       on certid.v13_certid  = certdiv.v14_certid ";
    $sql .= "       left join certter       on certid.v13_certid  = certter.v14_certid ";
    $sql .= "       left join divida        on certdiv.v14_coddiv = divida.v01_coddiv ";
    $sql .= "       left join termo         on certter.v14_parcel = termo.v07_parcel ";
    $sql .= "       left join cgm cgmtermo  on termo.v07_numcgm   = cgmtermo.z01_numcgm ";
    $sql .= "       left join cgm cgmdivida on divida.v01_numcgm  = cgmdivida.z01_numcgm ";
    $sql .= "       left join inicialcert   on certid.v13_certid  = inicialcert.v51_certidao ";
    $sql2 = "";
    if($dbwhere==""){
      if($v13_certid!=null ){
        $sql2 .= " where certid.v13_certid = $v13_certid ";
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

  function sql_query_origem_termo_parcelamento ($iNumpre, $iNumpar) {

    $iInstituicao = db_getsession('DB_instit');

    $sSql  = "select distinct                                                                          \n";
    $sSql .= "       arreoldcalc.k00_arreoldcalc,                                                      \n";
    $sSql .= "       arreold.k00_receit,                                                               \n";
    $sSql .= "    	 arreold.k00_tipo, 	                                                               \n";
    $sSql .= "       arreold.k00_tipojm, 	                                                             \n";
    $sSql .= "       arreold.k00_numpre, 	                                                             \n";
    $sSql .= "       arreold.k00_numpar, 	                                                             \n";
    $sSql .= "       arreold.k00_numtot, 	                                                             \n";
    $sSql .= "       arreold.k00_numdig,                                                               \n";
    $sSql .= "       arreold.k00_dtvenc,                                                               \n";
    $sSql .= "       arreold.k00_dtoper,                                                               \n";
    $sSql .= "       arreold.k00_valor,                                                                \n";
    $sSql .= "       tabrec.k02_descr,                                                                 \n";
    $sSql .= "       tabrec.k02_drecei,                                                                \n";
    $sSql .= "       histcalc.k01_descr,                                                               \n";
    $sSql .= "       coalesce(arrematric.k00_matric,0) as k00_matric,                                  \n";
    $sSql .= "       coalesce(arreinscr.k00_inscr,0)   as k00_inscr,                                   \n";
    $sSql .= "       coalesce(arreold.k00_numcgm,0)    as k00_numcgm,                                  \n";
    $sSql .= "       arreold.k00_valor                    as vlrhis,                                   \n";
    $sSql .= "       case when arreoldcalc.k00_vlrcor = 0                                              \n";
    $sSql .= "         then arreoldcalc.k00_vlrhis - arreoldcalc.k00_vlrjur - arreoldcalc.k00_vlrmul   \n";
    $sSql .= "         else arreoldcalc.k00_vlrcor                                                     \n";
    $sSql .= "       end                               as vlrcor,                                      \n";
    $sSql .= "       arreoldcalc.k00_vlrjur            as vlrjuros,                                    \n";
    $sSql .= "       arreoldcalc.k00_vlrmul            as vlrmulta,                                    \n";
    $sSql .= "       arreoldcalc.k00_vlrdes            as vlrdesconto,                                 \n";
    $sSql .= "       (arreoldcalc.k00_vlrcor +                                                         \n";
    $sSql .= "        arreoldcalc.k00_vlrjur +                                                         \n";
    $sSql .= "        arreoldcalc.k00_vlrmul -                                                         \n";
    $sSql .= "        arreoldcalc.k00_vlrdes)          as total                                        \n";
    $sSql .= "  from termodiv                                                                          \n";
    $sSql .= " inner join	divida	 	 on v01_coddiv            = coddiv                                 \n";
    $sSql .= " inner join	termo 	 	 on termo.v07_parcel      = parcel                                 \n";
    $sSql .= " left  join certter    on certter.v14_parcel    = termo.v07_parcel                       \n";
    $sSql .= " inner join	arreold	   on v01_numpre            = arreold.k00_numpre                     \n";
    $sSql .= "                      and v01_numpar            = arreold.k00_numpar                     \n";
    $sSql .= "                      and arreold.k00_valor     > 0                                      \n";
    $sSql .= " inner join arreoldcalc  on arreoldcalc.k00_numpre = arreold.k00_numpre                  \n";
    $sSql .= "                        and arreoldcalc.k00_numpar = arreold.k00_numpar                  \n";
    $sSql .= "                        and arreoldcalc.k00_receit = arreold.k00_receit                  \n";
    $sSql .= " inner join arretipo   on arreold.k00_tipo      = arretipo.k00_tipo                      \n";
    $sSql .= "                      and arretipo.k00_instit   = {$iInstituicao}                        \n";
    $sSql .= " inner join	proced		 on v01_proced            = v03_codigo                             \n";
    $sSql .= "  left join	arrematric on arrematric.k00_numpre = divida.v01_numpre                      \n";
    $sSql .= "  left join	iptubase a on arrematric.k00_matric = a.j01_matric                           \n";
    $sSql .= "  left join	arreinscr	 on arreinscr.k00_numpre  = divida.v01_numpre                      \n";
    $sSql .= " inner join tabrec     on tabrec.k02_codigo     = arreold.k00_receit                     \n";
    $sSql .= " inner join histcalc   on histcalc.k01_codigo   = arreold.k00_hist                       \n";
    $sSql .= " where v01_numpre = {$iNumpre}                                                           \n";
    $sSql .= "   and v01_numpar = {$iNumpar}                                                           \n";

    return $sSql;
  }

  public function sql_queryCertidao ($iCodigoPesquisa = null, $sTipoPesquisa = 'certidao', $sCampos, $lBuscarReparcelamento = false) {

    $iInstituicao  = db_getsession('DB_instit');

    $sWhereTipo    = '';

    if ($sTipoPesquisa == 'matricula') {

      $sWhereTipo  = " inner join arrematric on arrematric.k00_numpre = divida.v01_numpre  ";
      $sWhereTipo .= " where k00_matric                               = {$iCodigoPesquisa} ";

    } else if ($sTipoPesquisa == 'inscricao') {

      $sWhereTipo  = " inner join arreinscr on arreinscr.k00_numpre   = divida.v01_numpre  ";
      $sWhereTipo .= " where k00_inscr                                = {$iCodigoPesquisa} ";

    } elseif ($sTipoPesquisa == 'cgm') {
      $sWhereTipo .= " inner join arrenumcgm on arrenumcgm.k00_numpre   = divida.v01_numpre  ";
      $sWhereTipo .= " where arrenumcgm.k00_numcgm                      = {$iCodigoPesquisa}  ";

    }

    $sSqlParametro = "(select coalesce((select v04_envolcdaiptu from pardiv where v04_instit = {$iInstituicao} limit 1), 0))";
    $sSqlMatricula = "(select k00_matric from arrematric where k00_numpre = v01_numpre limit 1)";

    $sCampoCgm  = "case when exists(select * from arrematric where k00_numpre = v01_numpre) then                           ";
    $sCampoCgm .= "     (select riNumcgm from fc_busca_envolvidos(false, {$sSqlParametro}, 'M', {$sSqlMatricula}) limit 1) ";
    $sCampoCgm .= " else v01_numcgm end                                                                                    ";

    $sCampoReparcelamento  = " case ";
    $sCampoReparcelamento .= "   when exists(select * from arrematric where k00_numpre = arrecad.k00_numpre) then ";
    $sCampoReparcelamento .= "      ( select riNumcgm ";
    $sCampoReparcelamento .= "          from fc_busca_envolvidos( false, ";
    $sCampoReparcelamento .= "                                    ( select coalesce( (select v04_envolcdaiptu ";
    $sCampoReparcelamento .= "                                                                from pardiv ";
    $sCampoReparcelamento .= "                                                               where v04_instit = 1 ";
    $sCampoReparcelamento .= "                                                               limit 1), 0) ), ";
    $sCampoReparcelamento .= "                                     'M', ";
    $sCampoReparcelamento .= "                                     ( select k00_matric ";
    $sCampoReparcelamento .= "                                         from arrematric ";
    $sCampoReparcelamento .= "                                        where k00_numpre = arrecad.k00_numpre limit 1) ) ";
    $sCampoReparcelamento .= "         limit 1) ";
    $sCampoReparcelamento .= "   else ";
    $sCampoReparcelamento .= "      arrenumcgm.k00_numcgm ";
    $sCampoReparcelamento .= " end ";

    $sSqlCertidao  = "select $sCampos                                                     \n";
    $sSqlCertidao .= "                                                          \n";
    $sSqlCertidao .= "                                                          \n";
    $sSqlCertidao .= "                                                          \n";
    $sSqlCertidao .= "                                                          \n";
    $sSqlCertidao .= "                                                          \n";
    $sSqlCertidao .= "                                                          \n";
    $sSqlCertidao .= "  from (                                                                                   \n";
    $sSqlCertidao .= "        select v13_certid     as certidao,                                                 \n";
    $sSqlCertidao .= "               v13_dtemis     as data_emissao,                                             \n";
    $sSqlCertidao .= "               v13_instit     as instituicao,                                              \n";
    $sSqlCertidao .= "               v15_observacao as observacao,                                               \n";
    $sSqlCertidao .= "               v01_numpar     as numpar,                                                   \n";
    $sSqlCertidao .= "               v01_numpre     as numpre,                                                   \n";
    $sSqlCertidao .= "               {$sCampoCgm}   as numcgm,                                                   \n";
    $sSqlCertidao .= "               case                                                                        \n";
    $sSqlCertidao .= "                 when v15_parcial is true or v15_parcial is null                           \n";
    $sSqlCertidao .= "                   then false                                                              \n";
    $sSqlCertidao .= "                   else true                                                               \n";
    $sSqlCertidao .= "                end as anulado,                                                            \n";
    $sSqlCertidao .= "               'Divida'    as tipo_cda                                                     \n";
    $sSqlCertidao .= "          from certid                                                                      \n";
    $sSqlCertidao .= "          left join acertid    on acertid.v15_certid       = certid.v13_certid             \n";
    $sSqlCertidao .= "         inner join certdiv    on certdiv.v14_certid       = certid.v13_certid             \n";
    $sSqlCertidao .= "         inner join divida     on divida.v01_coddiv        = certdiv.v14_coddiv            \n";
    $sSqlCertidao .= "                              and divida.v01_instit        = {$iInstituicao}               \n";
    if ($sTipoPesquisa == 'certidao') {
      $sSqlCertidao .= "       where certid.v13_certid                           in ({$iCodigoPesquisa})         \n";
    } else {
      $sSqlCertidao .= "         {$sWhereTipo}                                                                   \n";
    }
    $sSqlCertidao .= "                                                                                           \n";
    $sSqlCertidao .= "    union                                                                                  \n";
    $sSqlCertidao .= "                                                                                           \n";
    $sSqlCertidao .= "        select v15_certid  as certidao,                                                    \n";
    $sSqlCertidao .= "               v15_data    as data_emissao,                                                \n";
    $sSqlCertidao .= "               v15_instit  as instituicao,                                                 \n";
    $sSqlCertidao .= "               v15_observacao as observacao,                                               \n";
    $sSqlCertidao .= "               v01_numpar     as numpar,                                                   \n";
    $sSqlCertidao .= "               v01_numpre     as numpre,                                                   \n";
    $sSqlCertidao .= "               {$sCampoCgm}   as numcgm,                                                   \n";
    $sSqlCertidao .= "               case                                                                        \n";
    $sSqlCertidao .= "                 when v15_parcial is true or v15_parcial is null                           \n";
    $sSqlCertidao .= "                   then false                                                              \n";
    $sSqlCertidao .= "                   else true                                                               \n";
    $sSqlCertidao .= "                end as anulado,                                                            \n";
    $sSqlCertidao .= "               'Divida'    as tipo_cda                                                     \n";
    $sSqlCertidao .= "          from acertid                                                                     \n";
    $sSqlCertidao .= "                                                                                           \n";
    $sSqlCertidao .= "         inner join acertdiv   on acertdiv.v14_certid      = acertid.v15_certid            \n";
    $sSqlCertidao .= "         inner join divida     on divida.v01_coddiv        = acertdiv.v14_coddiv           \n";
    $sSqlCertidao .= "                              and divida.v01_instit        = {$iInstituicao}               \n";
    if ($sTipoPesquisa == 'certidao') {
      $sSqlCertidao .= "       where acertid.v15_certid                          in ({$iCodigoPesquisa} )        \n";
    } else {
      $sSqlCertidao .= "         {$sWhereTipo}                                                                   \n";
    }

    $sCampoCgm = str_replace("v01_numpre", "v07_numpre", $sCampoCgm);

    $sSqlCertidao .= "                                                                                           \n";
    $sSqlCertidao .= "    union                                                                                  \n";
    $sSqlCertidao .= "                                                                                           \n";
    $sSqlCertidao .= "        select v13_certid     as certidao,                                                 \n";
    $sSqlCertidao .= "               v13_dtemis     as data_emissao,                                             \n";
    $sSqlCertidao .= "               v13_instit     as instituicao,                                              \n";
    $sSqlCertidao .= "               v15_observacao as observacao,                                               \n";
    $sSqlCertidao .= "               v07_totpar     as numpar,                                                   \n";
    $sSqlCertidao .= "               v07_numpre     as numpre,                                                   \n";

    if ( $lBuscarReparcelamento ) {
      $sSqlCertidao .= "               {$sCampoReparcelamento}   as numcgm,                                                  \n";
    } else {
      $sSqlCertidao .= "               {$sCampoCgm}   as numcgm,                                                   \n";
    }

    $sSqlCertidao .= "               case                                                                        \n";
    $sSqlCertidao .= "                 when v15_parcial is true or v15_parcial is null                           \n";
    $sSqlCertidao .= "                   then false                                                              \n";
    $sSqlCertidao .= "                   else true                                                               \n";
    $sSqlCertidao .= "                end as anulado,                                                            \n";
    $sSqlCertidao .= "               'Parcelamento' as tipo_cda                                                  \n";
    $sSqlCertidao .= "          from certid                                                                      \n";
    $sSqlCertidao .= "          left join acertid    on acertid.v15_certid       = certid.v13_certid             \n";
    $sSqlCertidao .= "         inner join certter    on certter.v14_certid       = certid.v13_certid             \n";
    $sSqlCertidao .= "         inner join termo      on certter.v14_parcel       = termo.v07_parcel              \n";
    $sSqlCertidao .= "                              and termo.v07_instit         = {$iInstituicao}               \n";

    if ( $lBuscarReparcelamento ) {

      $sSqlCertidao .= "         inner join arrecad    on arrecad.k00_numpre    = termo.v07_numpre              \n";
      $sSqlCertidao .= "         inner join arrenumcgm on arrecad.k00_numpre    = arrenumcgm.k00_numpre              \n";
    } else {

      $sSqlCertidao .= "         inner join termodiv   on termodiv.parcel          = certter.v14_parcel            \n";
      $sSqlCertidao .= "         inner join divida     on divida.v01_coddiv        = termodiv.coddiv               \n";
      $sSqlCertidao .= "                              and divida.v01_instit        = {$iInstituicao}               \n";
    }

    if ($sTipoPesquisa == 'certidao') {
      $sSqlCertidao .= "       where  certid.v13_certid                          in ({$iCodigoPesquisa})         \n";
    } else {
      $sSqlCertidao .= "         {$sWhereTipo}                                                                   \n";
    }
    $sSqlCertidao .= "    union                                                                                  \n";
    $sSqlCertidao .= "                                                                                           \n";
    $sSqlCertidao .= "        select v15_certid     as certidao,                                                 \n";
    $sSqlCertidao .= "               v15_data       as data_emissao,                                             \n";
    $sSqlCertidao .= "               v15_instit     as instituicao,                                              \n";
    $sSqlCertidao .= "               v15_observacao as observacao,                                               \n";
    $sSqlCertidao .= "               v07_totpar     as numpar,                                                   \n";
    $sSqlCertidao .= "               v07_numpre     as numpre,                                                   \n";
    $sSqlCertidao .= "               {$sCampoCgm}   as numcgm,                                                   \n";
    $sSqlCertidao .= "               case                                                                        \n";
    $sSqlCertidao .= "                 when v15_parcial is true or v15_parcial is null                           \n";
    $sSqlCertidao .= "                   then false                                                              \n";
    $sSqlCertidao .= "                   else true                                                               \n";
    $sSqlCertidao .= "                end as anulado,                                                            \n";
    $sSqlCertidao .= "               'Parcelamento' as tipo_cda                                                  \n";
    $sSqlCertidao .= "          from acertid                                                                     \n";
    $sSqlCertidao .= "         inner join acertter   on acertter.v14_certid   = acertid.v15_certid               \n";
    $sSqlCertidao .= "         inner join termodiv   on termodiv.parcel       = acertter.v14_parcel              \n";
    $sSqlCertidao .= "         inner join divida     on divida.v01_coddiv        = termodiv.coddiv               \n";
    $sSqlCertidao .= "                              and divida.v01_instit        = {$iInstituicao}               \n";
    $sSqlCertidao .= "         inner join termo      on acertter.v14_parcel   = termo.v07_parcel                 \n";
    $sSqlCertidao .= "                              and termo.v07_instit      = {$iInstituicao}                  \n";
    $sSqlCertidao .= "                                                                                           \n";
    if ($sTipoPesquisa == 'certidao') {
      $sSqlCertidao .= "       where acertid.v15_certid                          in ({$iCodigoPesquisa})         \n";
    } else {
      $sSqlCertidao .= "         {$sWhereTipo}                                                                   \n";
    }
    $sSqlCertidao .= ") as certidao                                                                              \n";
    $sSqlCertidao .= "                                                                                           \n";
    $sSqlCertidao .= "    left join inicialcert         on inicialcert.v51_certidao        = certidao.certidao   \n";
    $sSqlCertidao .= "    left join inicial             on inicialcert.v51_inicial         = inicial.v50_inicial \n";
    $sSqlCertidao .= "    left join processoforoinicial on processoforoinicial.v71_inicial = inicial.v50_inicial \n";
    $sSqlCertidao .= "                                 and processoforoinicial.v71_anulado is false              \n";
    $sSqlCertidao .= "    left join processoforo        on processoforo.v70_sequencial     = processoforoinicial.v71_processoforo \n";
    $sSqlCertidao .= "    left join inicialmov          on inicialmov.v56_codmov           = inicial.v50_codmov    \n";
    $sSqlCertidao .= "    left join situacao            on situacao.v52_codsit             = inicialmov.v56_codsit \n";
    $sSqlCertidao .= "    left join vara                on vara.v53_codvara                = processoforo.v70_vara \n";
    $sSqlCertidao .= "    left join localiza            on localiza.v54_codlocal           = inicial.v50_codlocal  \n";
    $sSqlCertidao .= "    left join cgm as cgm_advogado on cgm_advogado.z01_numcgm         = inicial.v50_advog ";

    return $sSqlCertidao;
  }

  public function sql_queryConsultaCertidao($sWhere) {

    $sSql  = "select certidao    as dl_Certidao,         ";
    $sSql .= "       data        as dl_Data,             ";
    $sSql .= "       instituicao as dl_Instituicao,      ";
    $sSql .= "       situacao    as dl_Situacao          ";
    $sSql .= "  from (select v13_certid as certidao,     ";
    $sSql .= "               v13_dtemis as data,         ";
    $sSql .= "               v13_instit as instituicao,  ";
    $sSql .= "               'Ativa'    as situacao      ";
    $sSql .= "          from certid                      ";
    $sSql .= "         union                             ";
    $sSql .= "        select v15_certid as certidao,     ";
    $sSql .= "               v15_data   as data,         ";
    $sSql .= "               v15_instit as instituicao,  ";
    $sSql .= "               'Anulada'  as situacao      ";
    $sSql .= "          from acertid) as x               ";

    if (isset($sWhere) and $sWhere != '') {

      $sSql .= " where {$sWhere}";

    }

    return $sSql;
  }

  /**
   * Buscamos so dados da certidão no arrecad
   *
   * @param  string  $sCampos
   * @param  integer $iCertidao
   *
   * @return string   query pronta
   */
  function sql_query_arrecad($sCampos, $iCertidao) {

    $sSql  = " select case                                                               ";
    $sSql .= "          when certter.v14_certid is not null then                         ";
    $sSql .= "            'PARCELAMENTO'                                                 ";
    $sSql .= "          else                                                             ";
    $sSql .= "            case                                                           ";
    $sSql .= "              when certdiv.v14_certid is not null then                     ";
    $sSql .= "                'DIVIDA'                                                   ";
    $sSql .= "            end                                                            ";
    $sSql .= "        end as tipo,                                                       ";
    $sSql .= "        {$sCampos}                                                         ";
    $sSql .= " from certid                                                               ";
    $sSql .= "      left join certdiv on certid.v13_certid = certdiv.v14_certid          ";
    $sSql .= "      left join divida on divida.v01_coddiv = certdiv.v14_coddiv           ";
    $sSql .= "      left join certter on certid.v13_certid = certter.v14_certid          ";
    $sSql .= "      left join termo on  certter.v14_parcel = termo.v07_parcel            ";
    $sSql .= "      inner join arrecad on (     divida.v01_numpre = arrecad.k00_numpre   ";
    $sSql .= "                              and divida.v01_numpar = arrecad.k00_numpar ) ";
    $sSql .= "                         or termo.v07_numpre = arrecad.k00_numpre          ";
    $sSql .= " where certid.v13_certid in ({$iCertidao})                                 ";

    return $sSql;
  }

  /**
   * Buscamos os dados das movimentações da certidão
   *
   * @param  int    $iSequencial  sequencial da certidão
   * @param  string $sCampos      Campos do select
   *
   * @return string               query montada
   */
  function sql_query_movimentacao( $iSequencial = null, $sCampos = "*" ) {

    $sSql  = " select {$sCampos} ";
    $sSql .= "   from certid ";
    $sSql .= "        inner join certidcartorio on v31_certid = v13_certid ";
    $sSql .= "        inner join certidmovimentacao on v31_sequencial = v32_certidcartorio ";
    $sSql .= "  where v31_certid = {$iSequencial} ";
    $sSql .= "  order by v32_sequencial desc ";

    return $sSql;
  }

  /**
   * Criamos a query que busca os abatimentos da certidão
   *
   * @param  integer $iCertidao
   * @param  string  $sCampos
   * @param  integer $iTipoAbatimento
   *
   * @return string  query pronta
   */
  function sql_query_abatimento( $iCodigoCertidao, $sCampos = "*", $iTipoAbatimento = 1 ) {

    $sSql  = " select {$sCampos}                                                                 ";
    $sSql .= "   from certid                                                                     ";
    $sSql .= "        inner join certidcartorio on v31_certid = v13_certid                       ";
    $sSql .= "        inner join certidcartoriorecibopaga on v33_certidcartorio = v31_sequencial ";
    $sSql .= "        inner join recibopaga on v33_numnov = k00_numnov                           ";
    $sSql .= "        inner join arreckey on recibopaga.k00_numpre = arreckey.k00_numpre         ";
    $sSql .= "        inner join abatimentoarreckey on k128_arreckey = arreckey.k00_sequencial   ";
    $sSql .= "        inner join abatimento on k128_abatimento = k125_sequencial                 ";
    $sSql .= "  where v13_certid in ({$iCodigoCertidao})                                         ";
    $sSql .= "    and k125_tipoabatimento = {$iTipoAbatimento}                                   ";

    return $sSql;
  }

  /**
   * Verifica se possui possui pagamento Parcial ou Total para certidão
   * @param  integer $iCodigoCertidao          Código da certidão
   * @param  boolean $lVerificaPagamentoParcial Parcial ou Total
   * @return mix     booleand / Object
   */
  function sql_query_verificaPagamento( $iCodigoCertidao, $lVerificaPagamentoParcial = false ){

    $sSql  = "select distinct 'Pagamento Total'   as v32_tipo,                                          ";
    $sSql .= "                v82_descricao       as v31_cartorio,                                      ";
    $sSql .= "                arrepaga.k00_dtpaga as data_pagamento                                     ";
    $sSql .= "  from certid                                                                             ";
    $sSql .= "       inner join certidcartorio           on v31_certid         = v13_certid             ";
    $sSql .= "       inner join cartorio                 on v31_cartorio       = v82_sequencial         ";
    $sSql .= "       inner join certidcartoriorecibopaga on v33_certidcartorio = v31_sequencial         ";
    $sSql .= "       inner join recibopaga               on v33_numnov         = k00_numnov             ";
    $sSql .= "                                          and k00_conta         <> 0                      ";
    $sSql .= "       inner join arrepaga                 on recibopaga.k00_numpre = arrepaga.k00_numpre ";
    $sSql .= "                                          and recibopaga.k00_numpar = arrepaga.k00_numpar ";
    $sSql .= "                                          and recibopaga.k00_receit = arrepaga.k00_receit ";

    if($lVerificaPagamentoParcial) {

      $sSql  = "select distinct 'Pagamento Parcial' as v32_tipo,                                        ";
      $sSql .= "                v82_descricao       as v31_cartorio,                                    ";
      $sSql .= "                k125_datalanc       as data_pagamento                                   ";
      $sSql .= " from certid                                                                            ";
      $sSql .= "      inner join certidcartorio           on v31_certid          = v13_certid           ";
      $sSql .= "      inner join cartorio                 on v31_cartorio        = v82_sequencial       ";
      $sSql .= "      inner join certidcartoriorecibopaga on v33_certidcartorio  = v31_sequencial       ";
      $sSql .= "      inner join recibopaga               on v33_numnov          = k00_numnov           ";
      $sSql .= "      inner join abatimentorecibo         on k127_numpreoriginal = v33_numnov           ";
      $sSql .= "      inner join abatimento               on k127_abatimento     = k125_sequencial      ";
    }

    $sSql .= " where v13_certid = {$iCodigoCertidao}                                                    ";

    return $sSql;
  }

}
