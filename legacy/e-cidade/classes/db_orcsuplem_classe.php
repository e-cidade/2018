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

//MODULO: orcamento
//CLASSE DA ENTIDADE orcsuplem
class cl_orcsuplem {
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
   var $o46_codsup = 0;
   var $o46_tiposup = 0;
   var $o46_codlei = 0;
   var $o46_instit = 0;
   var $o46_data_dia = null;
   var $o46_data_mes = null;
   var $o46_data_ano = null;
   var $o46_data = null;
   var $o46_obs = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 o46_codsup = int4 = Código Suplementação
                 o46_tiposup = int4 = Código
                 o46_codlei = int4 = Projeto de Lei
                 o46_instit = int4 = instituição
                 o46_data = date = Data
                 o46_obs = text = Origem do Recurso
                 ";
   //funcao construtor da classe
   function cl_orcsuplem() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcsuplem");
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
       $this->o46_codsup = ($this->o46_codsup == ""?@$GLOBALS["HTTP_POST_VARS"]["o46_codsup"]:$this->o46_codsup);
       $this->o46_tiposup = ($this->o46_tiposup == ""?@$GLOBALS["HTTP_POST_VARS"]["o46_tiposup"]:$this->o46_tiposup);
       $this->o46_codlei = ($this->o46_codlei == ""?@$GLOBALS["HTTP_POST_VARS"]["o46_codlei"]:$this->o46_codlei);
       $this->o46_instit = ($this->o46_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["o46_instit"]:$this->o46_instit);
       if($this->o46_data == ""){
         $this->o46_data_dia = ($this->o46_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["o46_data_dia"]:$this->o46_data_dia);
         $this->o46_data_mes = ($this->o46_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o46_data_mes"]:$this->o46_data_mes);
         $this->o46_data_ano = ($this->o46_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["o46_data_ano"]:$this->o46_data_ano);
         if($this->o46_data_dia != ""){
            $this->o46_data = $this->o46_data_ano."-".$this->o46_data_mes."-".$this->o46_data_dia;
         }
       }
       $this->o46_obs = ($this->o46_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["o46_obs"]:$this->o46_obs);
     }else{
       $this->o46_codsup = ($this->o46_codsup == ""?@$GLOBALS["HTTP_POST_VARS"]["o46_codsup"]:$this->o46_codsup);
     }
   }
   // funcao para inclusao
   function incluir ($o46_codsup){
      $this->atualizacampos();
     if($this->o46_tiposup == null ){
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "o46_tiposup";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o46_codlei == null ){
       $this->erro_sql = " Campo Projeto de Lei nao Informado.";
       $this->erro_campo = "o46_codlei";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o46_instit == null ){
       $this->erro_sql = " Campo instituição nao Informado.";
       $this->erro_campo = "o46_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o46_data == null ){
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "o46_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o46_codsup == "" || $o46_codsup == null ){
       $result = db_query("select nextval('orcsuplem_o46_codsup_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orcsuplem_o46_codsup_seq do campo: o46_codsup";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->o46_codsup = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from orcsuplem_o46_codsup_seq");
       if(($result != false) && (pg_result($result,0,0) < $o46_codsup)){
         $this->erro_sql = " Campo o46_codsup maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o46_codsup = $o46_codsup;
       }
     }
     if(($this->o46_codsup == null) || ($this->o46_codsup == "") ){
       $this->erro_sql = " Campo o46_codsup nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcsuplem(
                                       o46_codsup
                                      ,o46_tiposup
                                      ,o46_codlei
                                      ,o46_instit
                                      ,o46_data
                                      ,o46_obs
                       )
                values (
                                $this->o46_codsup
                               ,$this->o46_tiposup
                               ,$this->o46_codlei
                               ,$this->o46_instit
                               ,".($this->o46_data == "null" || $this->o46_data == ""?"null":"'".$this->o46_data."'")."
                               ,'$this->o46_obs'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Suplementações ($this->o46_codsup) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Suplementações já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Suplementações ($this->o46_codsup) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o46_codsup;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o46_codsup));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5328,'$this->o46_codsup','I')");
       $resac = db_query("insert into db_acount values($acount,786,5328,'','".AddSlashes(pg_result($resaco,0,'o46_codsup'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,786,5329,'','".AddSlashes(pg_result($resaco,0,'o46_tiposup'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,786,5330,'','".AddSlashes(pg_result($resaco,0,'o46_codlei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,786,5331,'','".AddSlashes(pg_result($resaco,0,'o46_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,786,5332,'','".AddSlashes(pg_result($resaco,0,'o46_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,786,5333,'','".AddSlashes(pg_result($resaco,0,'o46_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($o46_codsup=null) {
      $this->atualizacampos();
     $sql = " update orcsuplem set ";
     $virgula = "";
     if(trim($this->o46_codsup)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o46_codsup"])){
       $sql  .= $virgula." o46_codsup = $this->o46_codsup ";
       $virgula = ",";
       if(trim($this->o46_codsup) == null ){
         $this->erro_sql = " Campo Código Suplementação nao Informado.";
         $this->erro_campo = "o46_codsup";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o46_tiposup)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o46_tiposup"])){
       $sql  .= $virgula." o46_tiposup = $this->o46_tiposup ";
       $virgula = ",";
       if(trim($this->o46_tiposup) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "o46_tiposup";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o46_codlei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o46_codlei"])){
       $sql  .= $virgula." o46_codlei = $this->o46_codlei ";
       $virgula = ",";
       if(trim($this->o46_codlei) == null ){
         $this->erro_sql = " Campo Projeto de Lei nao Informado.";
         $this->erro_campo = "o46_codlei";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o46_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o46_instit"])){
       $sql  .= $virgula." o46_instit = $this->o46_instit ";
       $virgula = ",";
       if(trim($this->o46_instit) == null ){
         $this->erro_sql = " Campo instituição nao Informado.";
         $this->erro_campo = "o46_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o46_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o46_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["o46_data_dia"] !="") ){
       $sql  .= $virgula." o46_data = '$this->o46_data' ";
       $virgula = ",";
       if(trim($this->o46_data) == null ){
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "o46_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["o46_data_dia"])){
         $sql  .= $virgula." o46_data = null ";
         $virgula = ",";
         if(trim($this->o46_data) == null ){
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "o46_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->o46_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o46_obs"])){
       $sql  .= $virgula." o46_obs = '$this->o46_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($o46_codsup!=null){
       $sql .= " o46_codsup = $this->o46_codsup";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o46_codsup));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5328,'$this->o46_codsup','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o46_codsup"]))
           $resac = db_query("insert into db_acount values($acount,786,5328,'".AddSlashes(pg_result($resaco,$conresaco,'o46_codsup'))."','$this->o46_codsup',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o46_tiposup"]))
           $resac = db_query("insert into db_acount values($acount,786,5329,'".AddSlashes(pg_result($resaco,$conresaco,'o46_tiposup'))."','$this->o46_tiposup',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o46_codlei"]))
           $resac = db_query("insert into db_acount values($acount,786,5330,'".AddSlashes(pg_result($resaco,$conresaco,'o46_codlei'))."','$this->o46_codlei',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o46_instit"]))
           $resac = db_query("insert into db_acount values($acount,786,5331,'".AddSlashes(pg_result($resaco,$conresaco,'o46_instit'))."','$this->o46_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o46_data"]))
           $resac = db_query("insert into db_acount values($acount,786,5332,'".AddSlashes(pg_result($resaco,$conresaco,'o46_data'))."','$this->o46_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o46_obs"]))
           $resac = db_query("insert into db_acount values($acount,786,5333,'".AddSlashes(pg_result($resaco,$conresaco,'o46_obs'))."','$this->o46_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Suplementações nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o46_codsup;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Suplementações nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o46_codsup;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o46_codsup;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($o46_codsup=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o46_codsup));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5328,'$o46_codsup','E')");
         $resac = db_query("insert into db_acount values($acount,786,5328,'','".AddSlashes(pg_result($resaco,$iresaco,'o46_codsup'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,786,5329,'','".AddSlashes(pg_result($resaco,$iresaco,'o46_tiposup'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,786,5330,'','".AddSlashes(pg_result($resaco,$iresaco,'o46_codlei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,786,5331,'','".AddSlashes(pg_result($resaco,$iresaco,'o46_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,786,5332,'','".AddSlashes(pg_result($resaco,$iresaco,'o46_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,786,5333,'','".AddSlashes(pg_result($resaco,$iresaco,'o46_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcsuplem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o46_codsup != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o46_codsup = $o46_codsup ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Suplementações nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o46_codsup;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Suplementações nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o46_codsup;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o46_codsup;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcsuplem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

   function sql_query ( $o46_codsup=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from orcsuplem ";
     $sql .= "      inner join orcsuplemtipo  on  orcsuplemtipo.o48_tiposup = orcsuplem.o46_tiposup";
     $sql .= "      inner join orcprojeto  on  orcprojeto.o39_codproj = orcsuplem.o46_codlei";
     $sql .= "      inner join conhistdoc  on  conhistdoc.c53_coddoc = orcsuplemtipo.o48_coddocsup";
     $sql .= "      inner join orclei  on  orclei.o45_codlei = orcprojeto.o39_codlei";
     $sql .= "      left join orcsuplemlan on o49_codsup= orcsuplem.o46_codsup ";
     $sql .= "      left join db_usuarios on id_usuario = o49_id_usuario  ";

     $sql2 = "";
     if($dbwhere==""){
       if($o46_codsup!=null ){
         $sql2 .= " where orcsuplem.o46_codsup = $o46_codsup ";
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

   function sql_query_file ( $o46_codsup=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from orcsuplem ";
     $sql2 = "";
     if($dbwhere==""){
       if($o46_codsup!=null ){
         $sql2 .= " where orcsuplem.o46_codsup = $o46_codsup ";
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


   function sql_query_sup ( $o46_codsup=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from orcsuplem ";
     $sql .= "      inner join orcsuplemtipo  on  orcsuplemtipo.o48_tiposup = orcsuplem.o46_tiposup";
     $sql .= "      inner join orcprojeto  on  orcprojeto.o39_codproj = orcsuplem.o46_codlei";
     $sql .= "      inner join conhistdoc  on  conhistdoc.c53_coddoc = orcsuplemtipo.o48_coddocsup";
     $sql .= "      inner join orclei  on  orclei.o45_codlei = orcprojeto.o39_codlei";
     $sql .= "      left join orcsuplemlan on o49_codsup = o46_codsup ";
     $sql2 = "";
     if($dbwhere==""){
       if($o46_codsup!=null ){
         $sql2 .= " where orcsuplem.o46_codsup = $o46_codsup ";
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

  public function sql_query_suplementacoes ( $o46_codsup=null,$campos="*",$ordem=null,$dbwhere=""){

    $sql  = "select {$campos} ";
    $sql .= " from orcsuplem ";
    $sql .= "      inner join orcsuplemval on o47_codsup = o46_codsup ";
    $sql .= "      inner join orcsuplemlan on o49_codsup = o47_codsup ";

    $sql2 = "";
    if($dbwhere==""){
      if($o46_codsup!=null ){
        $sql2 .= " where orcsuplem.o46_codsup = $o46_codsup ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }

    $sql .= $sql2;
    if($ordem != null ){
      $sql .= " order by {$ordem} ";
    }
    return $sql;
  }
}
?>