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

//MODULO: caixa
//CLASSE DA ENTIDADE corlanc
class cl_corlanc {
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
   var $k12_id = 0;
   var $k12_data_dia = null;
   var $k12_data_mes = null;
   var $k12_data_ano = null;
   var $k12_data = null;
   var $k12_autent = 0;
   var $k12_conta = 0;
   var $k12_codigo = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 k12_id = int4 = Autenticação
                 k12_data = date = Data Autenticação
                 k12_autent = int4 = Código Autenticação
                 k12_conta = int4 = Código da Conta
                 k12_codigo = int4 = Código do Slip
                 ";
   //funcao construtor da classe
   function cl_corlanc() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("corlanc");
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
       $this->k12_id = ($this->k12_id == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_id"]:$this->k12_id);
       if($this->k12_data == ""){
         $this->k12_data_dia = ($this->k12_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_data_dia"]:$this->k12_data_dia);
         $this->k12_data_mes = ($this->k12_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_data_mes"]:$this->k12_data_mes);
         $this->k12_data_ano = ($this->k12_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_data_ano"]:$this->k12_data_ano);
         if($this->k12_data_dia != ""){
            $this->k12_data = $this->k12_data_ano."-".$this->k12_data_mes."-".$this->k12_data_dia;
         }
       }
       $this->k12_autent = ($this->k12_autent == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_autent"]:$this->k12_autent);
       $this->k12_conta = ($this->k12_conta == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_conta"]:$this->k12_conta);
       $this->k12_codigo = ($this->k12_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_codigo"]:$this->k12_codigo);
     }else{
       $this->k12_id = ($this->k12_id == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_id"]:$this->k12_id);
       $this->k12_data = ($this->k12_data == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_data_ano"]."-".@$GLOBALS["HTTP_POST_VARS"]["k12_data_mes"]."-".@$GLOBALS["HTTP_POST_VARS"]["k12_data_dia"]:$this->k12_data);
       $this->k12_autent = ($this->k12_autent == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_autent"]:$this->k12_autent);
     }
   }
   // funcao para inclusao
   function incluir ($k12_id,$k12_data,$k12_autent){
      $this->atualizacampos();
     if($this->k12_conta == null ){
       $this->erro_sql = " Campo Código da Conta nao Informado.";
       $this->erro_campo = "k12_conta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k12_codigo == null ){
       $this->erro_sql = " Campo Código do Slip nao Informado.";
       $this->erro_campo = "k12_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->k12_id = $k12_id;
       $this->k12_data = $k12_data;
       $this->k12_autent = $k12_autent;
     if(($this->k12_id == null) || ($this->k12_id == "") ){
       $this->erro_sql = " Campo k12_id nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->k12_data == null) || ($this->k12_data == "") ){
       $this->erro_sql = " Campo k12_data nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->k12_autent == null) || ($this->k12_autent == "") ){
       $this->erro_sql = " Campo k12_autent nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into corlanc(
                                       k12_id
                                      ,k12_data
                                      ,k12_autent
                                      ,k12_conta
                                      ,k12_codigo
                       )
                values (
                                $this->k12_id
                               ,".($this->k12_data == "null" || $this->k12_data == ""?"null":"'".$this->k12_data."'")."
                               ,$this->k12_autent
                               ,$this->k12_conta
                               ,$this->k12_codigo
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Autenticações Slip ($this->k12_id."-".$this->k12_data."-".$this->k12_autent) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Autenticações Slip já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Autenticações Slip ($this->k12_id."-".$this->k12_data."-".$this->k12_autent) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k12_id."-".$this->k12_data."-".$this->k12_autent;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k12_id,$this->k12_data,$this->k12_autent));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1139,'$this->k12_id','I')");
       $resac = db_query("insert into db_acountkey values($acount,1140,'$this->k12_data','I')");
       $resac = db_query("insert into db_acountkey values($acount,1141,'$this->k12_autent','I')");
       $resac = db_query("insert into db_acount values($acount,203,1139,'','".AddSlashes(pg_result($resaco,0,'k12_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,203,1140,'','".AddSlashes(pg_result($resaco,0,'k12_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,203,1141,'','".AddSlashes(pg_result($resaco,0,'k12_autent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,203,1143,'','".AddSlashes(pg_result($resaco,0,'k12_conta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,203,1152,'','".AddSlashes(pg_result($resaco,0,'k12_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($k12_id=null,$k12_data=null,$k12_autent=null) {
      $this->atualizacampos();
     $sql = " update corlanc set ";
     $virgula = "";
     if(trim($this->k12_id)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_id"])){
       $sql  .= $virgula." k12_id = $this->k12_id ";
       $virgula = ",";
       if(trim($this->k12_id) == null ){
         $this->erro_sql = " Campo Autenticação nao Informado.";
         $this->erro_campo = "k12_id";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k12_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k12_data_dia"] !="") ){
       $sql  .= $virgula." k12_data = '$this->k12_data' ";
       $virgula = ",";
       if(trim($this->k12_data) == null ){
         $this->erro_sql = " Campo Data Autenticação nao Informado.";
         $this->erro_campo = "k12_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["k12_data_dia"])){
         $sql  .= $virgula." k12_data = null ";
         $virgula = ",";
         if(trim($this->k12_data) == null ){
           $this->erro_sql = " Campo Data Autenticação nao Informado.";
           $this->erro_campo = "k12_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k12_autent)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_autent"])){
       $sql  .= $virgula." k12_autent = $this->k12_autent ";
       $virgula = ",";
       if(trim($this->k12_autent) == null ){
         $this->erro_sql = " Campo Código Autenticação nao Informado.";
         $this->erro_campo = "k12_autent";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k12_conta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_conta"])){
       $sql  .= $virgula." k12_conta = $this->k12_conta ";
       $virgula = ",";
       if(trim($this->k12_conta) == null ){
         $this->erro_sql = " Campo Código da Conta nao Informado.";
         $this->erro_campo = "k12_conta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k12_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_codigo"])){
       $sql  .= $virgula." k12_codigo = $this->k12_codigo ";
       $virgula = ",";
       if(trim($this->k12_codigo) == null ){
         $this->erro_sql = " Campo Código do Slip nao Informado.";
         $this->erro_campo = "k12_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k12_id!=null){
       $sql .= " k12_id = $this->k12_id";
     }
     if($k12_data!=null){
       $sql .= " and  k12_data = '$this->k12_data'";
     }
     if($k12_autent!=null){
       $sql .= " and  k12_autent = $this->k12_autent";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k12_id,$this->k12_data,$this->k12_autent));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1139,'$this->k12_id','A')");
         $resac = db_query("insert into db_acountkey values($acount,1140,'$this->k12_data','A')");
         $resac = db_query("insert into db_acountkey values($acount,1141,'$this->k12_autent','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_id"]))
           $resac = db_query("insert into db_acount values($acount,203,1139,'".AddSlashes(pg_result($resaco,$conresaco,'k12_id'))."','$this->k12_id',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_data"]))
           $resac = db_query("insert into db_acount values($acount,203,1140,'".AddSlashes(pg_result($resaco,$conresaco,'k12_data'))."','$this->k12_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_autent"]))
           $resac = db_query("insert into db_acount values($acount,203,1141,'".AddSlashes(pg_result($resaco,$conresaco,'k12_autent'))."','$this->k12_autent',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_conta"]))
           $resac = db_query("insert into db_acount values($acount,203,1143,'".AddSlashes(pg_result($resaco,$conresaco,'k12_conta'))."','$this->k12_conta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_codigo"]))
           $resac = db_query("insert into db_acount values($acount,203,1152,'".AddSlashes(pg_result($resaco,$conresaco,'k12_codigo'))."','$this->k12_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Autenticações Slip nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k12_id."-".$this->k12_data."-".$this->k12_autent;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Autenticações Slip nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k12_id."-".$this->k12_data."-".$this->k12_autent;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k12_id."-".$this->k12_data."-".$this->k12_autent;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($k12_id=null,$k12_data=null,$k12_autent=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k12_id,$k12_data,$k12_autent));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1139,'$k12_id','E')");
         $resac = db_query("insert into db_acountkey values($acount,1140,'$k12_data','E')");
         $resac = db_query("insert into db_acountkey values($acount,1141,'$k12_autent','E')");
         $resac = db_query("insert into db_acount values($acount,203,1139,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,203,1140,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,203,1141,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_autent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,203,1143,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_conta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,203,1152,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from corlanc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k12_id != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k12_id = $k12_id ";
        }
        if($k12_data != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k12_data = '$k12_data' ";
        }
        if($k12_autent != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k12_autent = $k12_autent ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Autenticações Slip nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k12_id."-".$k12_data."-".$k12_autent;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Autenticações Slip nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k12_id."-".$k12_data."-".$k12_autent;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k12_id."-".$k12_data."-".$k12_autent;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao do recordset
   function sql_query_file ( $k12_id=null,$k12_data=null,$k12_autent=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from corlanc ";
     $sql2 = "";
     if($dbwhere==""){
       if($k12_id!=null ){
         $sql2 .= " where corlanc.k12_id = $k12_id ";
       }
       if($k12_data!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " corlanc.k12_data = '$k12_data' ";
       }
       if($k12_autent!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " corlanc.k12_autent = $k12_autent ";
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
        $this->erro_sql   = "Record Vazio na Tabela:corlanc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

   function sql_query_slip ( $k12_id=null,$k12_data=null,$k12_autent=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql2 = "";
     $sql  .= " from corlanc ";
     $sql2 .= "       inner join corrente       on corlanc.k12_id     = corrente.k12_id";
     $sql2 .= "                                and corlanc.k12_data   = corrente.k12_data";
     $sql2 .= "                                and corlanc.k12_autent = corrente.k12_autent";
     $sql2 .= "  	    left join corconf         on corlanc.k12_id     = corconf.k12_id";
     $sql2 .= "                                and corlanc.k12_data   = corconf.k12_data";
     $sql2 .= "                                and corlanc.k12_autent = corconf.k12_autent";
     $sql2 .= "                                and corconf.k12_ativo is true ";
     $sql2 .= "        left join empageconfche  on k12_codmov         = e91_codcheque";
     $sql2 .= "        left join empagemovforma on e91_codmov         = e97_codmov";
     $sql2 .= "        left join empageforma    on e97_codforma       = e96_codigo";
     $sql2 .= "        left join conlancamcorrente      on conlancamcorrente.c86_id     = corrente.k12_id";
     $sql2 .= "                                        and conlancamcorrente.c86_data   = corrente.k12_data";
     $sql2 .= "                                        and conlancamcorrente.c86_autent = corrente.k12_autent";

     $sql .= $sql2;

     if($dbwhere==""){
       if($k12_id!=null ){
         $sql2 .= " where corlanc.k12_id = $k12_id ";
       }
       if($k12_data!=null ){
       if($sql2!=""){
       $sql2 .= " and ";
       }else{
           $sql2 .= " where ";
           }
           $sql2 .= " corlanc.k12_data = '$k12_data' ";
   }
   if($k12_autent!=null ){
           if($sql2!=""){
           $sql2 .= " and ";
           }else{
           $sql2 .= " where ";
           }
           $sql2 .= " corlanc.k12_autent = $k12_autent ";
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

  function sql_query_corrente_slip ($campos="*", $ordem = null, $dbwhere=null){

    $sql  = "select {$campos} ";
    $sql .= " from corlanc ";
    $sql .= "       inner join corrente       on corlanc.k12_id     = corrente.k12_id";
    $sql .= "                                and corlanc.k12_data   = corrente.k12_data";
    $sql .= "                                and corlanc.k12_autent = corrente.k12_autent";

    if (!empty($dbwhere)) {
      $sql .= " where {$dbwhere} ";
    }

    if (!empty($ordem)) {
      $sql .= " order by {$ordem} ";
    }

    return $sql;
  }

  function sql_query_corrente ( $k12_id=null,$k12_data=null,$k12_autent=null,$campos="*",$ordem=null,$dbwhere=""){

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
     $sql2 = "";
     $sql  .= " from corlanc ";
     $sql2 .= "       inner join corrente       on corlanc.k12_id     = corrente.k12_id";
     $sql2 .= "                                and corlanc.k12_data   = corrente.k12_data";
     $sql2 .= "                                and corlanc.k12_autent = corrente.k12_autent";

     $sql .= $sql2;

     if($dbwhere==""){

       if($k12_id!=null ){
         $sql2 .= " where corlanc.k12_id = $k12_id ";
       }
       if($k12_data!=null ){
       if($sql2!=""){
       $sql2 .= " and ";
       }else{
           $sql2 .= " where ";
           }
           $sql2 .= " corlanc.k12_data = '$k12_data' ";
   }
   if($k12_autent!=null ){

           if($sql2!=""){
           $sql2 .= " and ";
           }else{
           $sql2 .= " where ";
           }
           $sql2 .= " corlanc.k12_autent = $k12_autent ";
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
