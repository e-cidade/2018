<?php
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

//MODULO: diversos
//CLASSE DA ENTIDADE diverimporta
class cl_diverimporta {
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
   var $dv11_sequencial = 0;
   var $dv11_instit = 0;
   var $dv11_id_usuario = 0;
   var $dv11_data_dia = null;
   var $dv11_data_mes = null;
   var $dv11_data_ano = null;
   var $dv11_data = null;
   var $dv11_hora = null;
   var $dv11_tipo = 0;
   var $dv11_obs = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 dv11_sequencial = int8 = Código
                 dv11_instit = int4 = Cod. Instituição
                 dv11_id_usuario = int4 = Cod. Usuário
                 dv11_data = date = Data Operação
                 dv11_hora = char(5) = Hora
                 dv11_tipo = int4 = Tipo
                 dv11_obs = text = Observação
                 ";
   //funcao construtor da classe
   function cl_diverimporta() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("diverimporta");
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
       $this->dv11_sequencial = ($this->dv11_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["dv11_sequencial"]:$this->dv11_sequencial);
       $this->dv11_instit = ($this->dv11_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["dv11_instit"]:$this->dv11_instit);
       $this->dv11_id_usuario = ($this->dv11_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["dv11_id_usuario"]:$this->dv11_id_usuario);
       if($this->dv11_data == ""){
         $this->dv11_data_dia = ($this->dv11_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["dv11_data_dia"]:$this->dv11_data_dia);
         $this->dv11_data_mes = ($this->dv11_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["dv11_data_mes"]:$this->dv11_data_mes);
         $this->dv11_data_ano = ($this->dv11_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["dv11_data_ano"]:$this->dv11_data_ano);
         if($this->dv11_data_dia != ""){
            $this->dv11_data = $this->dv11_data_ano."-".$this->dv11_data_mes."-".$this->dv11_data_dia;
         }
       }
       $this->dv11_hora = ($this->dv11_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["dv11_hora"]:$this->dv11_hora);
       $this->dv11_tipo = ($this->dv11_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["dv11_tipo"]:$this->dv11_tipo);
       $this->dv11_obs = ($this->dv11_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["dv11_obs"]:$this->dv11_obs);
     }else{
       $this->dv11_sequencial = ($this->dv11_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["dv11_sequencial"]:$this->dv11_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($dv11_sequencial){
      $this->atualizacampos();
     if($this->dv11_instit == null ){
       $this->erro_sql = " Campo Cod. Instituição nao Informado.";
       $this->erro_campo = "dv11_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dv11_id_usuario == null ){
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "dv11_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dv11_data == null ){
       $this->erro_sql = " Campo Data Operação nao Informado.";
       $this->erro_campo = "dv11_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dv11_hora == null ){
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "dv11_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dv11_tipo == null ){
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "dv11_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($dv11_sequencial == "" || $dv11_sequencial == null ){
       $result = db_query("select nextval('diverimporta_dv11_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: diverimporta_dv11_sequencial_seq do campo: dv11_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->dv11_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from diverimporta_dv11_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $dv11_sequencial)){
         $this->erro_sql = " Campo dv11_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->dv11_sequencial = $dv11_sequencial;
       }
     }
     if(($this->dv11_sequencial == null) || ($this->dv11_sequencial == "") ){
       $this->erro_sql = " Campo dv11_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into diverimporta(
                                       dv11_sequencial
                                      ,dv11_instit
                                      ,dv11_id_usuario
                                      ,dv11_data
                                      ,dv11_hora
                                      ,dv11_tipo
                                      ,dv11_obs
                       )
                values (
                                $this->dv11_sequencial
                               ,$this->dv11_instit
                               ,$this->dv11_id_usuario
                               ,".($this->dv11_data == "null" || $this->dv11_data == ""?"null":"'".$this->dv11_data."'")."
                               ,'$this->dv11_hora'
                               ,$this->dv11_tipo
                               ,'$this->dv11_obs'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "diverimporta ($this->dv11_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "diverimporta já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "diverimporta ($this->dv11_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->dv11_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->dv11_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18604,'$this->dv11_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3293,18604,'','".AddSlashes(pg_result($resaco,0,'dv11_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3293,18609,'','".AddSlashes(pg_result($resaco,0,'dv11_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3293,18605,'','".AddSlashes(pg_result($resaco,0,'dv11_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3293,18606,'','".AddSlashes(pg_result($resaco,0,'dv11_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3293,18607,'','".AddSlashes(pg_result($resaco,0,'dv11_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3293,18608,'','".AddSlashes(pg_result($resaco,0,'dv11_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3293,19285,'','".AddSlashes(pg_result($resaco,0,'dv11_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($dv11_sequencial=null) {
      $this->atualizacampos();
     $sql = " update diverimporta set ";
     $virgula = "";
     if(trim($this->dv11_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv11_sequencial"])){
       $sql  .= $virgula." dv11_sequencial = $this->dv11_sequencial ";
       $virgula = ",";
       if(trim($this->dv11_sequencial) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "dv11_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv11_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv11_instit"])){
       $sql  .= $virgula." dv11_instit = $this->dv11_instit ";
       $virgula = ",";
       if(trim($this->dv11_instit) == null ){
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "dv11_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv11_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv11_id_usuario"])){
       $sql  .= $virgula." dv11_id_usuario = $this->dv11_id_usuario ";
       $virgula = ",";
       if(trim($this->dv11_id_usuario) == null ){
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "dv11_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv11_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv11_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["dv11_data_dia"] !="") ){
       $sql  .= $virgula." dv11_data = '$this->dv11_data' ";
       $virgula = ",";
       if(trim($this->dv11_data) == null ){
         $this->erro_sql = " Campo Data Operação nao Informado.";
         $this->erro_campo = "dv11_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["dv11_data_dia"])){
         $sql  .= $virgula." dv11_data = null ";
         $virgula = ",";
         if(trim($this->dv11_data) == null ){
           $this->erro_sql = " Campo Data Operação nao Informado.";
           $this->erro_campo = "dv11_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->dv11_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv11_hora"])){
       $sql  .= $virgula." dv11_hora = '$this->dv11_hora' ";
       $virgula = ",";
       if(trim($this->dv11_hora) == null ){
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "dv11_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv11_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv11_tipo"])){
       $sql  .= $virgula." dv11_tipo = $this->dv11_tipo ";
       $virgula = ",";
       if(trim($this->dv11_tipo) == null ){
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "dv11_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv11_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv11_obs"])){
       $sql  .= $virgula." dv11_obs = '$this->dv11_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($dv11_sequencial!=null){
       $sql .= " dv11_sequencial = $this->dv11_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->dv11_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18604,'$this->dv11_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv11_sequencial"]) || $this->dv11_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3293,18604,'".AddSlashes(pg_result($resaco,$conresaco,'dv11_sequencial'))."','$this->dv11_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv11_instit"]) || $this->dv11_instit != "")
           $resac = db_query("insert into db_acount values($acount,3293,18609,'".AddSlashes(pg_result($resaco,$conresaco,'dv11_instit'))."','$this->dv11_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv11_id_usuario"]) || $this->dv11_id_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3293,18605,'".AddSlashes(pg_result($resaco,$conresaco,'dv11_id_usuario'))."','$this->dv11_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv11_data"]) || $this->dv11_data != "")
           $resac = db_query("insert into db_acount values($acount,3293,18606,'".AddSlashes(pg_result($resaco,$conresaco,'dv11_data'))."','$this->dv11_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv11_hora"]) || $this->dv11_hora != "")
           $resac = db_query("insert into db_acount values($acount,3293,18607,'".AddSlashes(pg_result($resaco,$conresaco,'dv11_hora'))."','$this->dv11_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv11_tipo"]) || $this->dv11_tipo != "")
           $resac = db_query("insert into db_acount values($acount,3293,18608,'".AddSlashes(pg_result($resaco,$conresaco,'dv11_tipo'))."','$this->dv11_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv11_obs"]) || $this->dv11_obs != "")
           $resac = db_query("insert into db_acount values($acount,3293,19285,'".AddSlashes(pg_result($resaco,$conresaco,'dv11_obs'))."','$this->dv11_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "diverimporta nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->dv11_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "diverimporta nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->dv11_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->dv11_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($dv11_sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($dv11_sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18604,'$dv11_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3293,18604,'','".AddSlashes(pg_result($resaco,$iresaco,'dv11_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3293,18609,'','".AddSlashes(pg_result($resaco,$iresaco,'dv11_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3293,18605,'','".AddSlashes(pg_result($resaco,$iresaco,'dv11_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3293,18606,'','".AddSlashes(pg_result($resaco,$iresaco,'dv11_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3293,18607,'','".AddSlashes(pg_result($resaco,$iresaco,'dv11_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3293,18608,'','".AddSlashes(pg_result($resaco,$iresaco,'dv11_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3293,19285,'','".AddSlashes(pg_result($resaco,$iresaco,'dv11_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from diverimporta
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($dv11_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " dv11_sequencial = $dv11_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "diverimporta nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$dv11_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "diverimporta nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$dv11_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$dv11_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:diverimporta";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $dv11_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from diverimporta ";
     $sql .= "      inner join db_config  on  db_config.codigo = diverimporta.dv11_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = diverimporta.dv11_id_usuario";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql2 = "";
     if($dbwhere==""){
       if($dv11_sequencial!=null ){
         $sql2 .= " where diverimporta.dv11_sequencial = $dv11_sequencial ";
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
   function sql_query_file ( $dv11_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from diverimporta ";
     $sql2 = "";
     if($dbwhere==""){
       if($dv11_sequencial!=null ){
         $sql2 .= " where diverimporta.dv11_sequencial = $dv11_sequencial ";
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

   function sql_query_dadosImportacao( $iCodigoImporta ) {

  	$sSql   = "select x.dv13_numpre                                                                           ";
    $sSql  .= "  from (select dv13_sequencial,dv12_diverimporta,                                              ";
    $sSql  .= "               dv13_numpre,                                                                    ";
    $sSql  .= "               dv13_numpar,                                                                    ";
    $sSql  .= "               dv13_receita,                                                                   ";
    $sSql  .= "               diversos.dv05_coddiver,                                                         ";
    $sSql  .= "               diversos.dv05_numpre,                                                           ";
    $sSql  .= "               arrecad.k00_numpre,                                                             ";
    $sSql  .= "               arrecad.k00_numpar,                                                             ";
    $sSql  .= "               arrecad.k00_receit,                                                             ";
    $sSql  .= "              arrecad.k00_tipo,                                                                ";
    $sSql  .= "              k00_matric                                                                       ";
    $sSql  .= "          from diverimportareg                                                                 ";
    $sSql  .= "               inner join diversos on dv05_coddiver = dv12_diversos                            ";
    $sSql  .= "               inner join diverimportaold on dv13_diversos = dv05_coddiver                     ";
    $sSql  .= "               left  join arrecad  on k00_numpre    = dv05_numpre                              ";
    $sSql  .= "               left  join arrematric on arrematric.k00_numpre = arrecad.k00_numpre             ";
    $sSql  .= "        where dv12_diverimporta = $iCodigoImporta                                              ";
    $sSql  .= "          and not exists (select 1 from arrepaga where k00_numpre = arrecad.k00_numpre)        ";
    $sSql  .= "          and not exists (select 1 from arrecant where k00_numpre = arrecad.k00_numpre)        ";
    $sSql  .= "          and not exists (select 1 from arreold  where k00_numpre = arrecad.k00_numpre)        ";
    $sSql  .= "          and not exists (select 1 from arresusp where k00_numpre = arrecad.k00_numpre) ) AS x ";
    $sSql  .= " group by x.dv13_numpre                                                                        ";
    $sSql  .= " having count(x.dv05_coddiver) = count(x.k00_numpre);                                          ";

    return $sSql;

   }

   function sql_query_debitos_diversos($iNumpre, $iNumpar, $iReceita) {

  	$sSql  = "select k00_numpre,                     ";
  	$sSql .= "			 k00_numcgm,                     ";
  	$sSql .= "       k00_numpar,                     ";
  	$sSql .= "       k00_receit,                     ";
  	$sSql .= "       k00_hist,                       ";
  	$sSql .= "       k00_tipo,                       ";
  	$sSql .= "       sum(k00_valor) as k00_valor,    ";
  	$sSql .= "       min(k00_numtot) as k00_numtot,  ";
  	$sSql .= "       min(k00_dtvenc) as k00_dtvenc,  ";
  	$sSql .= "       min(k00_dtoper) as k00_dtoper   ";
  	$sSql .= "  from arrecad                         ";
  	$sSql .= " where k00_numpre = {$iNumpre}         ";
  	$sSql .= "   and k00_numpar = {$iNumpar}         ";
  	$sSql .= "   and k00_receit = {$iReceita}        ";
  	$sSql .= " group by                              ";
  	$sSql .= "       k00_numpre,                     ";
  	$sSql .= "       k00_numpar,                     ";
  	$sSql .= "       k00_numcgm,                     ";
  	$sSql .= "       k00_receit,                     ";
  	$sSql .= "       k00_hist,                       ";
  	$sSql .= "       k00_numtot,                     ";
  	$sSql .= "       k00_tipo                        ";

		return $sSql;

  }

   function sql_query_debitos_importados($iTipoPesquisa, $iChavePequisa) {

  	$iInstituicao = db_getsession('DB_instit');

		$sSql  = "    select  diverimporta.dv11_sequencial,                                                                           \n";
		$sSql .= "            diverimporta.dv11_data,                                                                                 \n";
		$sSql .= "            diverimporta.dv11_hora,                                                                                 \n";
		$sSql .= "            diverimporta.dv11_obs,                                                                                  \n";
		$sSql .= "            arretipo.k00_tipo,                                                                                      \n";
		$sSql .= "            arretipo.k00_descr,                                                                                     \n";
		$sSql .= "            array_to_string( array_accum( distinct tabrec.k02_codigo || ' - ' || tabrec.k02_descr ), ', ') as receitas";
		$sSql .= "     from   diverimporta                                                                                            \n";
		$sSql .= "            inner join  diverimportareg on diverimportareg.dv12_diverimporta  = diverimporta.dv11_sequencial        \n";
		$sSql .= "            inner join  diversos        on diversos.dv05_coddiver             = diverimportareg.dv12_diversos       \n";
		$sSql .= "            inner join  diverimportaold on diverimportaold.dv13_diversos      = diversos.dv05_coddiver              \n";
		$sSql .= "            inner join  arreold         on arreold.k00_numpre                 = diverimportaold.dv13_numpre         \n";
		$sSql .= "                                       and arreold.k00_numpar                 = diverimportaold.dv13_numpar         \n";
		$sSql .= "                                       and arreold.k00_receit                 = diverimportaold.dv13_receita        \n";
		$sSql .= "            inner join  arretipo        on arretipo.k00_tipo                  = arreold.k00_tipo                    \n";
		$sSql .= "            inner join  arreinstit      on arreinstit.k00_numpre              = diversos.dv05_numpre                \n";
		$sSql .= "            inner join  cadtipo         on cadtipo.k03_tipo                   = arretipo.k03_tipo                   \n";
		$sSql .= "            inner join  tabrec          on tabrec.k02_codigo                  = arreold.k00_receit                  \n";
	  $sSql .= "            inner join  arrenumcgm      on arrenumcgm.k00_numpre              = diversos.dv05_numpre                \n";
    if ($iTipoPesquisa == "3") {
  		$sSql .= "                                     and arrenumcgm.k00_numcgm               = {$iChavePequisa}                   \n";
		}

    if ($iTipoPesquisa == "4") {
			$sSql.="                                       and arrenumcgm.k00_numpre               = {$iChavePequisa}                   \n";
		}

  	if ($iTipoPesquisa == "2") {
		  $sSql .= "          inner join arrematric       on arrematric.k00_numpre               = diversos.dv05_numpre               \n";
  		$sSql .= "                                     and arrematric.k00_matric               = {$iChavePequisa}                   \n";
		}
		$sSql .= "    where   arreinstit.k00_instit = {$iInstituicao}                                                                 \n";
		$sSql .= "      and   cadtipo.k03_tipo in (1, 20)                                                                             \n";
		$sSql .= "      and   dv11_tipo = 1                            --Apenas Individuais                                           \n";
		$sSql .= " group by   diverimporta.dv11_sequencial,                                                                           \n";
		$sSql .= "            diverimporta.dv11_data,                                                                                 \n";
		$sSql .= "            diverimporta.dv11_hora,                                                                                 \n";
		$sSql .= "            diverimporta.dv11_obs,                                                                                  \n";
		$sSql .= "            arretipo.k00_tipo,                                                                                      \n";
		$sSql .= "            arretipo.k00_descr                                                                                      \n";
		$sSql .= " order by   diverimporta.dv11_data desc,                                                                            \n";
		$sSql .= "            diverimporta.dv11_hora desc                                                                             \n";

		return $sSql;
  }

  function sql_query_importa_iptu($iTipoPesquisa, $aChavePesquisa) {

    switch ($iTipoPesquisa) {

      case 1: // CODIGO IMPORTACAO
        break;

      case 2: // MATRICULA

        $sWhere = "arrematric.k00_matric in (".implode(",",$aChavePesquisa).")";
        break;

      case 3: // CGM

        $sWhere = "arrenumcgm.k00_numcgm in (".implode(",",$aChavePesquisa).")";
        break;

      case 4: // Array de débitos

        $aWhere = array();

        foreach ($aChavePesquisa as $oDebito) {

          $sClausula  = " (   arrecad.k00_numpre = {$oDebito->iNumpre} ";
          $sClausula .= " and arrecad.k00_numpar = {$oDebito->iNumpar} ";

          if ($oDebito->iReceita != 0) {
            $sClausula .= " and arrecad.k00_receit = {$oDebito->iReceita} ";
          }

          $sClausula .= " ) ";

          $aWhere[] = $sClausula;
        }

        $sWhere = implode(" or ", $aWhere);
        break;
    }

  	$iInstituicao = db_getsession('DB_instit');

  	$sSql = "select sum(vlr_total) as k00_valor,                                                               \n";
		$sSql.= "       k00_numpre,                                                                                \n";
		$sSql.= "       k00_numpar,                                                                                \n";
		$sSql.= "       k00_descr,                                                                                 \n";
		$sSql.= "       k02_codigo,                                                                                \n";
		$sSql.= "       k02_descr                                                                                  \n";
		$sSql.= "  from ( select k00_numpre,                                                                       \n";
		$sSql.= "                k00_numpar,                                                                       \n";
		$sSql.= "                k00_descr,                                                                        \n";
		$sSql.= "                k02_codigo,                                                                       \n";
		$sSql.= "                k02_descr,                                                                        \n";
		$sSql.= "                k00_valor as vlr_total                                                            \n";
		$sSql.= "           from ( select arrecad.k00_numpre,                                                      \n";
		$sSql.= "                         arrecad.k00_valor,                                                       \n";
		$sSql.= "                         arrecad.k00_numpar,                                                      \n";
		$sSql.= "                         arretipo.k00_descr,                                                      \n";
		$sSql.= "                         tabrec.k02_codigo,                                                       \n";
    $sSql.= "                         tabrec.k02_descr                                                         \n";
		$sSql.= "                    from arrecad                                                                  \n";
		$sSql.= "                         inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre      \n";
		$sSql.= "                         inner join arretipo   on arretipo.k00_tipo     = arrecad.k00_tipo        \n";
		$sSql.= "                         inner join cadtipo    on cadtipo.k03_tipo      = arretipo.k03_tipo       \n";
		$sSql.= "                         inner join tabrec     on tabrec.k02_codigo     = arrecad.k00_receit      \n";
		$sSql.= "                         inner join arrenumcgm on arrenumcgm.k00_numpre = arrecad.k00_numpre      \n";
    $sSql.= "                         inner join arrematric on arrematric.k00_numpre = arrecad.k00_numpre      \n";
		$sSql.= "                         left  join iptunump   on iptunump.j20_numpre   = arrecad.k00_numpre      \n"; /** Força o debito a ser agua ou IPTU **/
		$sSql.= "                                              and iptunump.j20_matric   = arrematric.k00_matric   \n"; /** Força o debito a ser agua ou IPTU **/
		$sSql.= "                         left  join aguacalc   on aguacalc.x22_numpre   = arrecad.k00_numpre      \n"; /** Força o debito a ser agua ou IPTU **/
		$sSql.= "                                              and aguacalc.x22_matric   = arrematric.k00_matric   \n"; /** Força o debito a ser agua ou IPTU **/
    $sSql.= "                   where arreinstit.k00_instit = {$iInstituicao}                                  \n";
		$sSql.= "                     and ({$sWhere})                                                              \n";
		$sSql.= "                     and cadtipo.k03_tipo in (1, 20)                                              \n";
    $sSql.= "                     and case when iptunump.j20_numpre is null                                    \n"; /** Força o debito a ser agua ou IPTU **/
    $sSql.= "                           then case when aguacalc.x22_numpre is null                             \n"; /** Força o debito a ser agua ou IPTU **/
    $sSql.= "                                     then false                                                   \n"; /** Força o debito a ser agua ou IPTU **/
    $sSql.= "                                     else true                                                    \n"; /** Força o debito a ser agua ou IPTU **/
    $sSql.= "                                end                                                               \n"; /** Força o debito a ser agua ou IPTU **/
    $sSql.= "                           else true                                                              \n"; /** Força o debito a ser agua ou IPTU **/
    $sSql.= "                         end                                                                      \n"; /** Força o debito a ser agua ou IPTU **/
    $sSql.= "                ) as debitos                                                                      \n";
		$sSql.= "       ) as debitos_corridos                                                                      \n";
		$sSql.= " group by                                                                                         \n";
		$sSql.= "       k00_numpre,                                                                                \n";
		$sSql.= "       k00_numpar,                                                                                \n";
		$sSql.= "       k00_descr,                                                                                 \n";
		$sSql.= "       k02_codigo,                                                                                \n";
		$sSql.= "       k02_descr                                                                                  \n";
		$sSql.= " having                                                                                           \n";
		$sSql.= "       sum(vlr_total) > 0                                                                         \n";
		$sSql.= " order by                                                                                         \n";
		$sSql.= "       k00_numpre,                                                                                \n";
		$sSql.= "       k00_numpar,                                                                                \n";
		$sSql.= "       k00_descr,                                                                                 \n";
		$sSql.= "       k02_codigo,                                                                                \n";
		$sSql.= "       k02_descr                                                                                  \n";

    return $sSql;
  }

  function sql_query_relatorio_importacao( $oWherePesquisa ) {

  	$sWhere = '';
  	$iInstituicao = db_getsession('DB_instit');

  	if (!empty($oWherePesquisa->iCgm)) {
  		$sWhere  = " and diversos.dv05_coddiver = '{$oWherePesquisa->iCgm}'";
  	}

  	if (!empty($oWherePesquisa->iMatricula)) {

  		$sWhere .= " and iptubase.j01_matric = '{$oWherePesquisa->iMatricula}'";

  	} else if(!empty($oWherePesquisa->iInscricao)) {

  	  $sWhere .= " and issbase.q02_inscr   = '{$oWherePesquisa->iInscricao}'";

  	}

  	if (!empty($oWherePesquisa->iNumpre)) {
  		$sWhere .= " and diverimportaold.dv13_numpre = '{$oWherePesquisa->iNumpre}'";
  	}

  	if (!empty($oWherePesquisa->dDataInicial) and !empty($oWherePesquisa->dDataFinal)) {
  		$sWhere .= " and diverimporta.dv11_data BETWEEN '{$oWherePesquisa->dDataInicial}' AND '{$oWherePesquisa->dDataFinal}'";
  	}

  	if (!empty($oWherePesquisa->dDataInicial) and empty($oWherePesquisa->dDataFinal)) {
  		$sWhere .= " and diverimporta.dv11_data >= '{$oWherePesquisa->dDataInicial}'";
  	}

  	if (empty($oWherePesquisa->dDataInicial) and !empty($oWherePesquisa->dDataFinal)) {
  		$sWhere .= " and diverimporta.dv11_data <= '{$oWherePesquisa->dDataFinal}'";
  	}

  	$sSql  = "select distinct *                                                                                                    ";
  	$sSql .= "  from (                                                                                                             ";
  	$sSql .= "        select diverimporta.dv11_data,                                                                               ";
  	$sSql .= "               diverimporta.dv11_hora,                                                                               ";
  	$sSql .= "               db_usuarios.login,                                                                                    ";
  	$sSql .= "               diversos.dv05_numcgm,                                                                                 ";
  	$sSql .= "               cgm.z01_nome,                                                                                         ";
  	$sSql .= "               issbase.q02_inscr as inscricao,                                                                       ";
  	$sSql .= "               case when db21_usasisagua is true then aguabase.x01_matric else iptubase.j01_matric end AS matricula, ";
  	$sSql .= "               diverimporta.dv11_obs        AS observacao,                                                           ";
  	$sSql .= "               proced.v03_descr             AS tipoprocedencia,                                                      ";
  	$sSql .= "               diverimportaold.dv13_numpre  AS numpreantigo,                                                         ";
  	$sSql .= "               diverimportaold.dv13_numpar  AS numparantigo,                                                         ";
  	$sSql .= "               diverimportaold.dv13_receita AS receitaantigo,                                                        ";
  	$sSql .= "               diversos.dv05_procdiver      AS procedencia,                                                          ";
  	$sSql .= "               diversos.dv05_numpre         AS numprenovo,                                                           ";
  	$sSql .= "               1                            AS numparnovo,                                                           ";
  	$sSql .= "               diversos.dv05_vlrhis,                                                                                 ";
  	$sSql .= "               diversos.dv05_valor,                                                                                  ";
  	$sSql .= "               arreoldcalc.k00_vlrjur       AS juros,                                                                ";
  	$sSql .= "               arreoldcalc.k00_vlrmul       AS multa,                                                                ";
  	$sSql .= "               arreoldcalc.k00_vlrcor       AS total,                                                                ";
  	$sSql .= "               diverimporta.dv11_sequencial AS codImportacao,                                                        ";
  	$sSql .= "               tabrec.k02_descr AS descrreceitaantiga                                                                ";
    $sSql .= "         FROM diversos                                                                                               ";
  	$sSql .= "               inner join diverimportareg on (diverimportareg.dv12_diversos = diversos.dv05_coddiver)                ";
  	$sSql .= "               inner join diverimporta    on (diverimporta.dv11_sequencial  = diverimportareg.dv12_diverimporta)     ";
  	$sSql .= "               inner join diverimportaold on (diverimportaold.dv13_diversos = diversos.dv05_coddiver)                ";
   	$sSql .= "               inner join arreold         on arreold.k00_numpre             = diverimportaold.dv13_numpre            ";
  	$sSql .= "                                         and arreold.k00_numpar             = diverimportaold.dv13_numpar            ";
  	$sSql .= "                                         and arreold.k00_receit             = diverimportaold.dv13_receita           ";
  	$sSql .= "               inner join arreoldcalc     on arreoldcalc.k00_numpre         = arreold.k00_numpre                     ";
  	$sSql .= "                                         and arreoldcalc.k00_numpar         = arreold.k00_numpar                     ";
  	$sSql .= "                                         and arreoldcalc.k00_receit         = arreold.k00_receit                     ";
  	$sSql .= "               inner join procdiver       on (procdiver.dv09_procdiver      = diversos.dv05_procdiver)               ";
  	$sSql .= "               inner join proced          on (proced.v03_codigo             = procdiver.dv09_proced)                 ";
  	$sSql .= "               inner join db_usuarios     on (db_usuarios.id_usuario        = diverimporta.dv11_id_usuario)          ";
  	$sSql .= "               inner join cgm             on (cgm.z01_numcgm                = diversos.dv05_numcgm)                  ";
  	$sSql .= "               inner join tabrec          on (tabrec.k02_codigo             = diverimportaold.dv13_receita)          ";
  	$sSql .= "               inner join db_config       on db_config.codigo               = diversos.dv05_instit                   ";
  	$sSql .= "               left  join iptubase        on (iptubase.j01_numcgm           = cgm.z01_numcgm)                        ";
  	$sSql .= "               left  join aguabase        on aguabase.x01_numcgm            = cgm.z01_numcgm                         ";
  	$sSql .= "               left  join issbase         on issbase.q02_numcgm             = cgm.z01_numcgm                         ";
  	$sSql .= "         WHERE diversos.dv05_instit = '{$iInstituicao}'                                                              ";
  	$sSql .= "               {$sWhere}                                                                                             ";
  	$sSql .= "         ORDER BY diverimporta.dv11_data,                                                                            ";
  	$sSql .= "                  diverimporta.dv11_hora,                                                                            ";
  	$sSql .= "                  db_usuarios.login,                                                                                 ";
  	$sSql .= "                  diversos.dv05_numcgm,                                                                              ";
  	$sSql .= "                  cgm.z01_nome,                                                                                      ";
  	$sSql .= "                  iptubase.j01_matric,                                                                               ";
  	$sSql .= "                  diverimportaold.dv13_numpre,                                                                       ";
  	$sSql .= "                  diverimportaold.dv13_numpar,                                                                       ";
  	$sSql .= "                  diverimportaold.dv13_receita ) as x                                                                ";

  	return $sSql;

  }

  function sql_queryDebitosImportadosAlvara($iChavePequisa) {

    $iInstituicao = db_getsession('DB_instit');

    $sSql  = "select diverimporta.dv11_sequencial,                                                                              \n";
    $sSql .= "       diverimporta.dv11_data,                                                                                    \n";
    $sSql .= "       diverimporta.dv11_hora,                                                                                    \n";
    $sSql .= "       diverimporta.dv11_obs,                                                                                     \n";
    $sSql .= "       arretipo.k00_tipo,                                                                                         \n";
    $sSql .= "       arretipo.k00_descr,                                                                                        \n";
    $sSql .= "       array_to_string( array_accum( distinct tabrec.k02_codigo || ' - ' || tabrec.k02_descr ), ', ') as receitas \n";
    $sSql .= "  from diverimporta                                                                                               \n";
    $sSql .= " inner join diverimportareg on diverimportareg.dv12_diverimporta  = diverimporta.dv11_sequencial                  \n";
    $sSql .= " inner join diversos        on diversos.dv05_coddiver             = diverimportareg.dv12_diversos                 \n";
    $sSql .= " inner join diverimportaold on diverimportaold.dv13_diversos      = diversos.dv05_coddiver                        \n";
    $sSql .= " inner join arreold         on arreold.k00_numpre                 = diverimportaold.dv13_numpre                   \n";
    $sSql .= "                           and arreold.k00_numpar                 = diverimportaold.dv13_numpar                   \n";
    $sSql .= "                           and arreold.k00_receit                 = diverimportaold.dv13_receita                  \n";
    $sSql .= " inner join arretipo        on arretipo.k00_tipo                  = arreold.k00_tipo                              \n";
    $sSql .= " inner join arreinstit      on arreinstit.k00_numpre              = diversos.dv05_numpre                          \n";
    $sSql .= " inner join cadtipo         on cadtipo.k03_tipo                   = arretipo.k03_tipo                             \n";
    $sSql .= " inner join tabrec          on tabrec.k02_codigo                  = arreold.k00_receit                            \n";
    $sSql .= " inner join arrenumcgm      on arrenumcgm.k00_numpre              = diversos.dv05_numpre                          \n";
    $sSql .= " inner join arreinscr       on arreinscr.k00_numpre               = diversos.dv05_numpre                          \n";
    $sSql .= "                           and arreinscr.k00_inscr                = {$iChavePequisa}                              \n";
    $sSql .= " where arreinstit.k00_instit = {$iInstituicao}                                                                    \n";
    $sSql .= "   and cadtipo.k03_tipo in (9, 19)                                                                                \n";
    $sSql .= " group by diverimporta.dv11_sequencial,                                                                           \n";
    $sSql .= "          diverimporta.dv11_data,                                                                                 \n";
    $sSql .= "          diverimporta.dv11_hora,                                                                                 \n";
    $sSql .= "          diverimporta.dv11_obs,                                                                                  \n";
    $sSql .= "          arretipo.k00_tipo,                                                                                      \n";
    $sSql .= "          arretipo.k00_descr                                                                                      \n";
    $sSql .= " order by diverimporta.dv11_data desc,                                                                            \n";
    $sSql .= "          diverimporta.dv11_hora desc                                                                             \n";

    return $sSql;
  }

  function sql_queryImportacaoAlvara($aChavePesquisa) {

    $iInstituicao = db_getsession('DB_instit');

    $sSql  = "select sum(vlr_total) as k00_valor,                                                               \n";
    $sSql .= "       k00_numpre,                                                                                \n";
    $sSql .= "       k00_numpar,                                                                                \n";
    $sSql .= "       k00_descr,                                                                                 \n";
    $sSql .= "       k02_codigo,                                                                                \n";
    $sSql .= "       k02_descr                                                                                  \n";
    $sSql .= "  from ( select k00_numpre,                                                                       \n";
    $sSql .= "                k00_numpar,                                                                       \n";
    $sSql .= "                k00_descr,                                                                        \n";
    $sSql .= "                k02_codigo,                                                                       \n";
    $sSql .= "                k02_descr,                                                                        \n";
    $sSql .= "                case                                                                              \n";
    $sSql .= "                 when k03_tipo = 3 and q05_vlrinf > 0 then q05_vlrinf                             \n";
    $sSql .= "                 else k00_valor                                                                   \n";
    $sSql .= "                end as vlr_total                                                                  \n";
    $sSql .= "           from ( select distinct                                                                 \n";
    $sSql .= "                         arrecad.k00_numpre,                                                      \n";
    $sSql .= "                         arrecad.k00_valor,                                                       \n";
    $sSql .= "                         arrecad.k00_numpar,                                                      \n";
    $sSql .= "                         arretipo.k00_descr,                                                      \n";
    $sSql .= "                         tabrec.k02_codigo,                                                       \n";
    $sSql .= "                         tabrec.k02_descr,                                                        \n";
    $sSql .= "                         cadtipo.k03_tipo,                                                        \n";
    $sSql .= "                         issvar.q05_vlrinf                                                        \n";
    $sSql .= "                    from arrecad                                                                  \n";
    $sSql .= "                         inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre      \n";
    $sSql .= "                         inner join arretipo   on arretipo.k00_tipo     = arrecad.k00_tipo        \n";
    $sSql .= "                         inner join cadtipo    on cadtipo.k03_tipo      = arretipo.k03_tipo       \n";
    $sSql .= "                         inner join tabrec     on tabrec.k02_codigo     = arrecad.k00_receit      \n";
    $sSql .= "                         inner join arrenumcgm on arrenumcgm.k00_numpre = arrecad.k00_numpre      \n";
    $sSql .= "                         inner join arreinscr  on arreinscr.k00_numpre  = arrecad.k00_numpre      \n";
    $sSql .= "                         left join issvar on issvar.q05_numpre = arrecad.k00_numpre               \n";
    $sSql .= "                                         and issvar.q05_numpar = arrecad.k00_numpar               \n";
    $sSql .= "                   where arreinstit.k00_instit = {$iInstituicao}                                  \n";
    $sSql .= "                     and arreinscr.k00_inscr in (".implode(",", $aChavePesquisa).")               \n";
    $sSql .= "                     and cadtipo.k03_tipo in (9, 19)                                              \n";
    $sSql .= "                ) as debitos                                                                      \n";
    $sSql .= "       ) as debitos_corridos                                                                      \n";
    $sSql .= " group by                                                                                         \n";
    $sSql .= "       k00_numpre,                                                                                \n";
    $sSql .= "       k00_numpar,                                                                                \n";
    $sSql .= "       k00_descr,                                                                                 \n";
    $sSql .= "       k02_codigo,                                                                                \n";
    $sSql .= "       k02_descr                                                                                  \n";
    $sSql .= " having                                                                                           \n";
    $sSql .= "       sum(vlr_total) > 0                                                                         \n";
    $sSql .= " order by                                                                                         \n";
    $sSql .= "       k00_numpre,                                                                                \n";
    $sSql .= "       k00_numpar,                                                                                \n";
    $sSql .= "       k00_descr,                                                                                 \n";
    $sSql .= "       k02_codigo,                                                                                \n";
    $sSql .= "       k02_descr                                                                                  ";

    return $sSql;
  }

  function sql_query_cobranca_adm($iTipoPesquisa, $aChavePesquisa, $origemDebito) {

    switch ($iTipoPesquisa) {

      case 1: // CODIGO IMPORTACAO
        break;

      case 2: // MATRICULA

        $sWhere = " arrematric.k00_matric in (".implode(",", $aChavePesquisa).") ";
        break;

      case 3: // CGM

        $sWhere = " arrenumcgm.k00_numcgm in (".implode(",", $aChavePesquisa).") ";
        break;

      case 4: // Array de débitos

        $aWhere = array();

        foreach ($aChavePesquisa as $oDebito) {

          $sClausula  = " (   arrecad.k00_numpre = {$oDebito->iNumpre} ";
          $sClausula .= " and arrecad.k00_numpar = {$oDebito->iNumpar} ";

          if ($oDebito->iReceita != 0) {

            $sClausula .= "and arrecad.k00_receit = {$oDebito->iReceita} ";
          }

          $sClausula .= ")";

          $aWhere[] = $sClausula;
        }

        $sWhere = implode(" or ", $aWhere);
        break;

      case 5: // INSCRICAO

        $sWhere = " arreinscr.k00_inscr in (".implode(",", $aChavePesquisa).") ";
        break;
    }

    $iInstituicao = db_getsession('DB_instit');

    $sSql  = "select sum(vlr_total) as k00_valor,                                                          ";
    $sSql .= "       k00_numpre,                                                                           ";
    $sSql .= "       k00_numpar,                                                                           ";
    $sSql .= "       k00_descr,                                                                            ";
    $sSql .= "       k02_codigo,                                                                           ";
    $sSql .= "       k02_descr                                                                             ";
    $sSql .= "  from ( select k00_numpre,                                                                  ";
    $sSql .= "                k00_numpar,                                                                  ";
    $sSql .= "                k00_descr,                                                                   ";
    $sSql .= "                k02_codigo,                                                                  ";
    $sSql .= "                k02_descr,                                                                   ";
    $sSql .= "                case                                                                         ";
    $sSql .= "                 when k03_tipo = 3 and q05_valor > 0 then q05_valor                          ";
    $sSql .= "                 when k03_tipo = 3 and q05_valor = 0 then q05_vlrinf                         ";
    $sSql .= "                 else k00_valor                                                              ";
    $sSql .= "                end as vlr_total                                                             ";
    $sSql .= "           from ( select arrecad.k00_numpre,                                                 ";
    $sSql .= "                         arrecad.k00_valor,                                                  ";
    $sSql .= "                         arrecad.k00_numpar,                                                 ";
    $sSql .= "                         arretipo.k00_descr,                                                 ";
    $sSql .= "                         tabrec.k02_codigo,                                                  ";
    $sSql .= "                         tabrec.k02_descr,                                                   ";
    $sSql .= "                         cadtipo.k03_tipo,                                                   ";
    $sSql .= "                         issvar.q05_valor,                                                   ";
    $sSql .= "                         issvar.q05_vlrinf                                                   ";
    $sSql .= "                    from arrecad                                                             ";
    $sSql .= "                         inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre ";
    $sSql .= "                         inner join arretipo on arretipo.k00_tipo = arrecad.k00_tipo         ";
    $sSql .= "                         inner join cadtipo on cadtipo.k03_tipo = arretipo.k03_tipo          ";
    $sSql .= "                         inner join tabrec on tabrec.k02_codigo = arrecad.k00_receit         ";
    $sSql .= "                         inner join arrenumcgm on arrenumcgm.k00_numpre = arrecad.k00_numpre ";
    $sSql .= "                         left join arrematric on arrematric.k00_numpre = arrecad.k00_numpre  ";
    $sSql .= "                         left join arreinscr on arreinscr.k00_numpre = arrecad.k00_numpre    ";
    $sSql .= "                         left join issvar on issvar.q05_numpre = arrecad.k00_numpre          ";
    $sSql .= "                                         and issvar.q05_numpar = arrecad.k00_numpar          ";
    $sSql .= "                   where arreinstit.k00_instit = {$iInstituicao}                             ";
    $sSql .= "                     and ({$sWhere})                                                         ";
    $sSql .= "                     and arrecad.k00_tipo = {$origemDebito}                                  ";
    $sSql .= "                ) as debitos                                                                 ";
    $sSql .= "       ) as debitos_corridos                                                                 ";
    $sSql .= " group by                                                                                    ";
    $sSql .= "       k00_numpre,                                                                           ";
    $sSql .= "       k00_numpar,                                                                           ";
    $sSql .= "       k00_descr,                                                                            ";
    $sSql .= "       k02_codigo,                                                                           ";
    $sSql .= "       k02_descr                                                                             ";
    $sSql .= " having                                                                                      ";
    $sSql .= "       sum(vlr_total) > 0                                                                    ";
    $sSql .= " order by                                                                                    ";
    $sSql .= "       k00_numpre,                                                                           ";
    $sSql .= "       k00_numpar,                                                                           ";
    $sSql .= "       k00_descr,                                                                            ";
    $sSql .= "       k02_codigo,                                                                           ";
    $sSql .= "       k02_descr                                                                             ";

    return $sSql;
  }
}