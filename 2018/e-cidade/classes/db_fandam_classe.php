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

//MODULO: fiscal
//CLASSE DA ENTIDADE fandam
class cl_fandam {
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
   var $y39_codandam = 0;
   var $y39_data_dia = null;
   var $y39_data_mes = null;
   var $y39_data_ano = null;
   var $y39_data = null;
   var $y39_codtipo = 0;
   var $y39_obs = null;
   var $y39_id_usuario = 0;
   var $y39_hora = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 y39_codandam = int8 = Codigo do Andamento Gerado
                 y39_data = date = Data do Andamento
                 y39_codtipo = int4 = Código do Tipo de Andamento
                 y39_obs = text = Observação do Andamento
                 y39_id_usuario = int4 = Cod. Usuário
                 y39_hora = varchar(5) = Hora
                 ";
   //funcao construtor da classe
   function cl_fandam() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("fandam");
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
       $this->y39_codandam = ($this->y39_codandam == ""?@$GLOBALS["HTTP_POST_VARS"]["y39_codandam"]:$this->y39_codandam);
       if($this->y39_data == ""){
         $this->y39_data_dia = ($this->y39_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["y39_data_dia"]:$this->y39_data_dia);
         $this->y39_data_mes = ($this->y39_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["y39_data_mes"]:$this->y39_data_mes);
         $this->y39_data_ano = ($this->y39_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["y39_data_ano"]:$this->y39_data_ano);
         if($this->y39_data_dia != ""){
            $this->y39_data = $this->y39_data_ano."-".$this->y39_data_mes."-".$this->y39_data_dia;
         }
       }
       $this->y39_codtipo = ($this->y39_codtipo == ""?@$GLOBALS["HTTP_POST_VARS"]["y39_codtipo"]:$this->y39_codtipo);
       $this->y39_obs = ($this->y39_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["y39_obs"]:$this->y39_obs);
       $this->y39_id_usuario = ($this->y39_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["y39_id_usuario"]:$this->y39_id_usuario);
       $this->y39_hora = ($this->y39_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["y39_hora"]:$this->y39_hora);
     }else{
       $this->y39_codandam = ($this->y39_codandam == ""?@$GLOBALS["HTTP_POST_VARS"]["y39_codandam"]:$this->y39_codandam);
     }
   }
   // funcao para inclusao
   function incluir ($y39_codandam){
      $this->atualizacampos();
     if($this->y39_data == null ){
       $this->erro_sql = " Campo Data do Andamento nao Informado.";
       $this->erro_campo = "y39_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y39_codtipo == null ){
       $this->erro_sql = " Campo Código do Tipo de Andamento nao Informado.";
       $this->erro_campo = "y39_codtipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y39_obs == null ){
       $this->erro_sql = " Campo Observação do Andamento nao Informado.";
       $this->erro_campo = "y39_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y39_id_usuario == null ){
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "y39_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y39_hora == null ){
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "y39_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($y39_codandam == "" || $y39_codandam == null ){
       $result = db_query("select nextval('fandam_y39_codandam_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: fandam_y39_codandam_seq do campo: y39_codandam";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->y39_codandam = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from fandam_y39_codandam_seq");
       if(($result != false) && (pg_result($result,0,0) < $y39_codandam)){
         $this->erro_sql = " Campo y39_codandam maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->y39_codandam = $y39_codandam;
       }
     }
     if(($this->y39_codandam == null) || ($this->y39_codandam == "") ){
       $this->erro_sql = " Campo y39_codandam nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into fandam(
                                       y39_codandam
                                      ,y39_data
                                      ,y39_codtipo
                                      ,y39_obs
                                      ,y39_id_usuario
                                      ,y39_hora
                       )
                values (
                                $this->y39_codandam
                               ,".($this->y39_data == "null" || $this->y39_data == ""?"null":"'".$this->y39_data."'")."
                               ,$this->y39_codtipo
                               ,'$this->y39_obs'
                               ,$this->y39_id_usuario
                               ,'$this->y39_hora'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Andamento ($this->y39_codandam) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Andamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Andamento ($this->y39_codandam) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y39_codandam;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->y39_codandam));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4928,'$this->y39_codandam','I')");
       $resac = db_query("insert into db_acount values($acount,346,4928,'','".AddSlashes(pg_result($resaco,0,'y39_codandam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,346,4929,'','".AddSlashes(pg_result($resaco,0,'y39_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,346,4930,'','".AddSlashes(pg_result($resaco,0,'y39_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,346,4931,'','".AddSlashes(pg_result($resaco,0,'y39_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,346,5067,'','".AddSlashes(pg_result($resaco,0,'y39_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,346,5068,'','".AddSlashes(pg_result($resaco,0,'y39_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($y39_codandam=null) {
      $this->atualizacampos();
     $sql = " update fandam set ";
     $virgula = "";
     if(trim($this->y39_codandam)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y39_codandam"])){
       $sql  .= $virgula." y39_codandam = $this->y39_codandam ";
       $virgula = ",";
       if(trim($this->y39_codandam) == null ){
         $this->erro_sql = " Campo Codigo do Andamento Gerado nao Informado.";
         $this->erro_campo = "y39_codandam";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y39_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y39_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["y39_data_dia"] !="") ){
       $sql  .= $virgula." y39_data = '$this->y39_data' ";
       $virgula = ",";
       if(trim($this->y39_data) == null ){
         $this->erro_sql = " Campo Data do Andamento nao Informado.";
         $this->erro_campo = "y39_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["y39_data_dia"])){
         $sql  .= $virgula." y39_data = null ";
         $virgula = ",";
         if(trim($this->y39_data) == null ){
           $this->erro_sql = " Campo Data do Andamento nao Informado.";
           $this->erro_campo = "y39_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->y39_codtipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y39_codtipo"])){
       $sql  .= $virgula." y39_codtipo = $this->y39_codtipo ";
       $virgula = ",";
       if(trim($this->y39_codtipo) == null ){
         $this->erro_sql = " Campo Código do Tipo de Andamento nao Informado.";
         $this->erro_campo = "y39_codtipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y39_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y39_obs"])){
       $sql  .= $virgula." y39_obs = '$this->y39_obs' ";
       $virgula = ",";
       if(trim($this->y39_obs) == null ){
         $this->erro_sql = " Campo Observação do Andamento nao Informado.";
         $this->erro_campo = "y39_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y39_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y39_id_usuario"])){
       $sql  .= $virgula." y39_id_usuario = $this->y39_id_usuario ";
       $virgula = ",";
       if(trim($this->y39_id_usuario) == null ){
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "y39_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y39_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y39_hora"])){
       $sql  .= $virgula." y39_hora = '$this->y39_hora' ";
       $virgula = ",";
       if(trim($this->y39_hora) == null ){
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "y39_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($y39_codandam!=null){
       $sql .= " y39_codandam = $this->y39_codandam";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->y39_codandam));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4928,'$this->y39_codandam','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y39_codandam"]))
           $resac = db_query("insert into db_acount values($acount,346,4928,'".AddSlashes(pg_result($resaco,$conresaco,'y39_codandam'))."','$this->y39_codandam',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y39_data"]))
           $resac = db_query("insert into db_acount values($acount,346,4929,'".AddSlashes(pg_result($resaco,$conresaco,'y39_data'))."','$this->y39_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y39_codtipo"]))
           $resac = db_query("insert into db_acount values($acount,346,4930,'".AddSlashes(pg_result($resaco,$conresaco,'y39_codtipo'))."','$this->y39_codtipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y39_obs"]))
           $resac = db_query("insert into db_acount values($acount,346,4931,'".AddSlashes(pg_result($resaco,$conresaco,'y39_obs'))."','$this->y39_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y39_id_usuario"]))
           $resac = db_query("insert into db_acount values($acount,346,5067,'".AddSlashes(pg_result($resaco,$conresaco,'y39_id_usuario'))."','$this->y39_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y39_hora"]))
           $resac = db_query("insert into db_acount values($acount,346,5068,'".AddSlashes(pg_result($resaco,$conresaco,'y39_hora'))."','$this->y39_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Andamento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->y39_codandam;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Andamento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->y39_codandam;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y39_codandam;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($y39_codandam=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($y39_codandam));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4928,'$y39_codandam','E')");
         $resac = db_query("insert into db_acount values($acount,346,4928,'','".AddSlashes(pg_result($resaco,$iresaco,'y39_codandam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,346,4929,'','".AddSlashes(pg_result($resaco,$iresaco,'y39_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,346,4930,'','".AddSlashes(pg_result($resaco,$iresaco,'y39_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,346,4931,'','".AddSlashes(pg_result($resaco,$iresaco,'y39_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,346,5067,'','".AddSlashes(pg_result($resaco,$iresaco,'y39_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,346,5068,'','".AddSlashes(pg_result($resaco,$iresaco,'y39_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from fandam
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($y39_codandam != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y39_codandam = $y39_codandam ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Andamento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$y39_codandam;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Andamento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$y39_codandam;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$y39_codandam;
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
        $this->erro_sql   = "Record Vazio na Tabela:fandam";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $y39_codandam=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from fandam ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = fandam.y39_id_usuario";
     $sql .= "      inner join tipoandam  on  tipoandam.y41_codtipo = fandam.y39_codtipo";
     $sql2 = "";
     if($dbwhere==""){
       if($y39_codandam!=null ){
         $sql2 .= " where fandam.y39_codandam = $y39_codandam ";
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
   function sql_query_file ( $y39_codandam=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from fandam ";
     $sql2 = "";
     if($dbwhere==""){
       if($y39_codandam!=null ){
         $sql2 .= " where fandam.y39_codandam = $y39_codandam ";
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