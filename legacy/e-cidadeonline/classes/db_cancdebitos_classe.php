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

//MODULO: caixa
//CLASSE DA ENTIDADE cancdebitos
class cl_cancdebitos {
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
   var $k20_codigo = 0;
   var $k20_cancdebitostipo = 0;
   var $k20_instit = 0;
   var $k20_descr = null;
   var $k20_hora = null;
   var $k20_data_dia = null;
   var $k20_data_mes = null;
   var $k20_data_ano = null;
   var $k20_data = null;
   var $k20_usuario = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 k20_codigo = int8 = Código
                 k20_cancdebitostipo = int4 = Tipo
                 k20_instit = int4 = Cód. Instituição
                 k20_descr = varchar(50) = Descricao resumida do cancelamento
                 k20_hora = varchar(5) = Hora
                 k20_data = date = Data
                 k20_usuario = int4 = Cod. Usuário
                 ";
   //funcao construtor da classe
   function cl_cancdebitos() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cancdebitos");
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
       $this->k20_codigo = ($this->k20_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["k20_codigo"]:$this->k20_codigo);
       $this->k20_cancdebitostipo = ($this->k20_cancdebitostipo == ""?@$GLOBALS["HTTP_POST_VARS"]["k20_cancdebitostipo"]:$this->k20_cancdebitostipo);
       $this->k20_instit = ($this->k20_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["k20_instit"]:$this->k20_instit);
       $this->k20_descr = ($this->k20_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["k20_descr"]:$this->k20_descr);
       $this->k20_hora = ($this->k20_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["k20_hora"]:$this->k20_hora);
       if($this->k20_data == ""){
         $this->k20_data_dia = ($this->k20_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k20_data_dia"]:$this->k20_data_dia);
         $this->k20_data_mes = ($this->k20_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k20_data_mes"]:$this->k20_data_mes);
         $this->k20_data_ano = ($this->k20_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k20_data_ano"]:$this->k20_data_ano);
         if($this->k20_data_dia != ""){
            $this->k20_data = $this->k20_data_ano."-".$this->k20_data_mes."-".$this->k20_data_dia;
         }
       }
       $this->k20_usuario = ($this->k20_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["k20_usuario"]:$this->k20_usuario);
     }else{
       $this->k20_codigo = ($this->k20_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["k20_codigo"]:$this->k20_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($k20_codigo){
      $this->atualizacampos();
     if($this->k20_cancdebitostipo == null ){
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "k20_cancdebitostipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k20_instit == null ){
       $this->erro_sql = " Campo Cód. Instituição nao Informado.";
       $this->erro_campo = "k20_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k20_hora == null ){
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "k20_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k20_data == null ){
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "k20_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k20_usuario == null ){
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "k20_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k20_codigo == "" || $k20_codigo == null ){
       $result = @db_query("select nextval('cancdebitos_k20_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cancdebitos_k20_codigo_seq do campo: k20_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->k20_codigo = pg_result($result,0,0);
     }else{
       $result = @db_query("select last_value from cancdebitos_k20_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $k20_codigo)){
         $this->erro_sql = " Campo k20_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k20_codigo = $k20_codigo;
       }
     }
     if(($this->k20_codigo == null) || ($this->k20_codigo == "") ){
       $this->erro_sql = " Campo k20_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cancdebitos(
                                       k20_codigo
                                      ,k20_cancdebitostipo
                                      ,k20_instit
                                      ,k20_descr
                                      ,k20_hora
                                      ,k20_data
                                      ,k20_usuario
                       )
                values (
                                $this->k20_codigo
                               ,$this->k20_cancdebitostipo
                               ,$this->k20_instit
                               ,'$this->k20_descr'
                               ,'$this->k20_hora'
                               ,".($this->k20_data == "null" || $this->k20_data == ""?"null":"'".$this->k20_data."'")."
                               ,$this->k20_usuario
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Debitos a cancelar ($this->k20_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Debitos a cancelar já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Debitos a cancelar ($this->k20_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k20_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k20_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7326,'$this->k20_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1217,7326,'','".AddSlashes(pg_result($resaco,0,'k20_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1217,11749,'','".AddSlashes(pg_result($resaco,0,'k20_cancdebitostipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1217,10680,'','".AddSlashes(pg_result($resaco,0,'k20_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1217,7763,'','".AddSlashes(pg_result($resaco,0,'k20_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1217,7328,'','".AddSlashes(pg_result($resaco,0,'k20_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1217,7327,'','".AddSlashes(pg_result($resaco,0,'k20_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1217,7329,'','".AddSlashes(pg_result($resaco,0,'k20_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($k20_codigo=null) {
      $this->atualizacampos();
     $sql = " update cancdebitos set ";
     $virgula = "";
     if(trim($this->k20_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k20_codigo"])){
       $sql  .= $virgula." k20_codigo = $this->k20_codigo ";
       $virgula = ",";
       if(trim($this->k20_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "k20_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k20_cancdebitostipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k20_cancdebitostipo"])){
       $sql  .= $virgula." k20_cancdebitostipo = $this->k20_cancdebitostipo ";
       $virgula = ",";
       if(trim($this->k20_cancdebitostipo) == null ){
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "k20_cancdebitostipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k20_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k20_instit"])){
       $sql  .= $virgula." k20_instit = $this->k20_instit ";
       $virgula = ",";
       if(trim($this->k20_instit) == null ){
         $this->erro_sql = " Campo Cód. Instituição nao Informado.";
         $this->erro_campo = "k20_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k20_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k20_descr"])){
       $sql  .= $virgula." k20_descr = '$this->k20_descr' ";
       $virgula = ",";
     }
     if(trim($this->k20_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k20_hora"])){
       $sql  .= $virgula." k20_hora = '$this->k20_hora' ";
       $virgula = ",";
       if(trim($this->k20_hora) == null ){
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "k20_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k20_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k20_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k20_data_dia"] !="") ){
       $sql  .= $virgula." k20_data = '$this->k20_data' ";
       $virgula = ",";
       if(trim($this->k20_data) == null ){
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "k20_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["k20_data_dia"])){
         $sql  .= $virgula." k20_data = null ";
         $virgula = ",";
         if(trim($this->k20_data) == null ){
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "k20_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k20_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k20_usuario"])){
       $sql  .= $virgula." k20_usuario = $this->k20_usuario ";
       $virgula = ",";
       if(trim($this->k20_usuario) == null ){
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "k20_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k20_codigo!=null){
       $sql .= " k20_codigo = $this->k20_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k20_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7326,'$this->k20_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k20_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1217,7326,'".AddSlashes(pg_result($resaco,$conresaco,'k20_codigo'))."','$this->k20_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k20_cancdebitostipo"]))
           $resac = db_query("insert into db_acount values($acount,1217,11749,'".AddSlashes(pg_result($resaco,$conresaco,'k20_cancdebitostipo'))."','$this->k20_cancdebitostipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k20_instit"]))
           $resac = db_query("insert into db_acount values($acount,1217,10680,'".AddSlashes(pg_result($resaco,$conresaco,'k20_instit'))."','$this->k20_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k20_descr"]))
           $resac = db_query("insert into db_acount values($acount,1217,7763,'".AddSlashes(pg_result($resaco,$conresaco,'k20_descr'))."','$this->k20_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k20_hora"]))
           $resac = db_query("insert into db_acount values($acount,1217,7328,'".AddSlashes(pg_result($resaco,$conresaco,'k20_hora'))."','$this->k20_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k20_data"]))
           $resac = db_query("insert into db_acount values($acount,1217,7327,'".AddSlashes(pg_result($resaco,$conresaco,'k20_data'))."','$this->k20_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k20_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1217,7329,'".AddSlashes(pg_result($resaco,$conresaco,'k20_usuario'))."','$this->k20_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Debitos a cancelar nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k20_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Debitos a cancelar nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k20_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k20_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($k20_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k20_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7326,'$k20_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1217,7326,'','".AddSlashes(pg_result($resaco,$iresaco,'k20_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1217,11749,'','".AddSlashes(pg_result($resaco,$iresaco,'k20_cancdebitostipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1217,10680,'','".AddSlashes(pg_result($resaco,$iresaco,'k20_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1217,7763,'','".AddSlashes(pg_result($resaco,$iresaco,'k20_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1217,7328,'','".AddSlashes(pg_result($resaco,$iresaco,'k20_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1217,7327,'','".AddSlashes(pg_result($resaco,$iresaco,'k20_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1217,7329,'','".AddSlashes(pg_result($resaco,$iresaco,'k20_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cancdebitos
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k20_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k20_codigo = $k20_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Debitos a cancelar nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k20_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Debitos a cancelar nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k20_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k20_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:cancdebitos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_pendentes($campos="*",$ordem="",$dbwhere=""){
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
   $sql .= " from cancdebitos
                  left join cancdebitosprot on k25_cancdebitos = k20_codigo
                  inner join cancdebitosreg  on k20_codigo = k21_codigo
                  inner join arrecad         on k21_numpre = k00_numpre
                                            and k21_numpar = k00_numpar
                                            and case
                                                  when k21_receit <> 0 then
                                                    k21_receit = k00_receit
                                                  else
                                                    true
                                                end
                  inner join db_usuarios     on k20_usuario = id_usuario ";
   if($dbwhere!=""){
    $sql .= " where ".$dbwhere;
   }
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
   function sql_pendentesproc($campos="*",$ordem="",$dbwhere=""){

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
   $sql .= " from cancdebitos
									inner join cancdebitosreg     on k20_codigo         = k21_codigo
						      inner join cancdebitosprocreg on k24_cancdebitosreg = k21_sequencia
						      inner join cancdebitosproc    on k23_codigo         = k24_codigo
						      inner join arrecant           on k21_numpre         = arrecant.k00_numpre
						                                   and k21_numpar         = arrecant.k00_numpar
																					     and case when k21_receit <> 0 then
																									 k21_receit         = arrecant.k00_receit
																							 else true end
									inner join arretipo           on arretipo.k00_tipo  = arrecant.k00_tipo
									left  join tabrec             on tabrec.k02_codigo  = arrecant.k00_receit
									inner join db_usuarios c      on k20_usuario        = c.id_usuario
									inner join db_usuarios p      on k23_usuario        = p.id_usuario
				          left  join arrenumcgm     		on arrenumcgm.k00_numpre = arrecant.k00_numpre
									left  join arreinscr          on arreinscr.k00_numpre  = arrecant.k00_numpre
									left  join arrematric         on arrematric.k00_numpre = arrecant.k00_numpre";

   if($dbwhere!=""){
    $sql .= " where ".$dbwhere;
   }
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
   function sql_query ( $k20_codigo=null,$campos="*",$ordem=null,$dbwhere=""){

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
     $sql .= " from cancdebitos ";
     $sql .= "      inner join db_config  on  db_config.codigo = cancdebitos.k20_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = cancdebitos.k20_usuario";
     $sql .= "      inner join cancdebitostipo  on  cancdebitostipo.k73_sequencial = cancdebitos.k20_cancdebitostipo";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($k20_codigo!=null ){
         $sql2 .= " where cancdebitos.k20_codigo = $k20_codigo ";
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
   function sql_query_file ( $k20_codigo=null,$campos="*",$ordem=null,$dbwhere=""){

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
     $sql .= " from cancdebitos ";
     $sql2 = "";
     if($dbwhere==""){
       if($k20_codigo!=null ){
         $sql2 .= " where cancdebitos.k20_codigo = $k20_codigo ";
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

  /**
   * Anulaçao do debito, já efetuando o processamento do cancelamento
   *
   * @param boolean $cancdebitosissplan se a anulação é de uma planilha de retencao;
   */
  function incluir_cancelamento( $cancdebitosissplan = true){

    //gravar na cancdebitos, cancdebitosreg, cancdebitosproc, cancdebitosprocreg
    $erro = false;

    // inclui cancdebitos
    $this->incluir(null);
    if ($this->erro_status == "0") {

      echo $this->erro_msg;
      $erro = true;
    }
    $codigo = $this->k20_codigo;

    if ($cancdebitosissplan == true) {

      // inclui cancdebitosissplan
      $clcancdebitosissplan = new cl_cancdebitosissplan();
      $clcancdebitosissplan->q78_cancdebitos = $codigo;
      $clcancdebitosissplan->q78_issplan     = $this->planilha;
      $clcancdebitosissplan->incluir(null);
      if ($clcancdebitosissplan->erro_status == "0") {

        $erro = true;
        $this->erro_msg = "planilha".$clcancdebitosissplan->erro_msg;
        $this->erro_status = "0";
      }

    }

    // inclui cancdebitosproc
		$clcancdebitosproc = new cl_cancdebitosproc;
    $clcancdebitosproc->k23_data            = $this->k20_data;
    $clcancdebitosproc->k23_hora            = $this->k20_hora;
    $clcancdebitosproc->k23_usuario         = $this->usuario;
    $clcancdebitosproc->k23_cancdebitostipo = 1;
    $clcancdebitosproc->k23_obs = $this->k21_obs;
    $clcancdebitosproc->incluir(null);
    if ($clcancdebitosproc->erro_status == "0") {

      $erro = true;
      $this->erro_msg = "deb prtoc".$clcancdebitosproc->erro_msg;
      $this->erro_status = "0";
    }

		$clcancdebitosprocreg  = new cl_cancdebitosprocreg;
		$clcancdebitosreg      = new cl_cancdebitosreg;
    $sqlarrecad = "select k00_receit from arrecad where k00_numpre = " . $this->numpre . " and k00_numpar=" . $this->numpar;
    $resultarrecad = db_query($sqlarrecad);
    $linhasarrecad = pg_num_rows($resultarrecad);

    if ($linhasarrecad > 0) {

      for($i = 0; $i < $linhasarrecad; $i ++) {

        $k00_receit = pg_result($resultarrecad, $i, "k00_receit");
        $clcancdebitosreg->k21_codigo = $codigo;
        $clcancdebitosreg->k21_numpre = $this->numpre;
        $clcancdebitosreg->k21_numpar = $this->numpar;
        $clcancdebitosreg->k21_receit = $k00_receit;
        $clcancdebitosreg->k21_data   = $this->k20_data;
        $clcancdebitosreg->k21_hora   = $this->k20_hora;
        $clcancdebitosreg->k21_obs    = $this->k21_obs;
        $clcancdebitosreg->incluir(null);
        if ($clcancdebitosreg->erro_status == "0") {

          $erro = true;
          $this->erro_msg = "debitos registros".$clcancdebitosreg->erro_msg;
          $this->erro_status = "0";
        }
        if ($erro == false) {

          $resultdebito = debitos_numpre($this->numpre, 0, $this->tipo, strtotime(date("Y-m-d")), date("Y"));
          $linhasdebito = pg_num_rows($resultdebito);

          $vlrhis       = pg_result($resultdebito, 0, "vlrhis");
          $vlrcor       = pg_result($resultdebito, 0, "vlrcor");
          $vlrjuros     = pg_result($resultdebito, 0, "vlrjuros");
          $vlrmulta     = pg_result($resultdebito, 0, "vlrmulta");
          $vlrdesconto  = pg_result($resultdebito, 0, "vlrdesconto");
          $clcancdebitosprocreg->k24_codigo         = $clcancdebitosproc->k23_codigo;
          $clcancdebitosprocreg->k24_cancdebitosreg = $clcancdebitosreg->k21_sequencia;
          $clcancdebitosprocreg->k24_vlrhis         = $vlrhis;
          $clcancdebitosprocreg->k24_vlrcor         = $vlrcor;
          $clcancdebitosprocreg->k24_juros          = $vlrjuros;
          $clcancdebitosprocreg->k24_multa          = $vlrmulta;
          $clcancdebitosprocreg->k24_desconto       = $vlrdesconto;
          $clcancdebitosprocreg->incluir(null);
          if ($clcancdebitosprocreg->erro_status == "0") {

            $erro = true;
            $this->erro_msg = "regitors prco".$clcancdebitosprocreg->erro_msg;
            $this->erro_status = "0";
          }
        }

      }
    } else {
      $this->erro_msg = "Não encontrou registros no arrecad.";
    }
  }

   function sql_query_proc( $k20_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
	   $sql .= " from cancdebitos
			  	          inner join cancdebitosreg     on k20_codigo         = k21_codigo
				            inner join cancdebitosprocreg on k24_cancdebitosreg = k21_sequencia
				            inner join cancdebitosproc    on k23_codigo         = k24_codigo ";
     $sql2 = "";
     if($dbwhere==""){
       if($k20_codigo!=null ){
         $sql2 .= " where cancdebitos.k20_codigo = $k20_codigo ";
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