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

//MODULO: contabilidade
//CLASSE DA ENTIDADE conencerramento
class cl_conencerramento {
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
   var $c42_sequencial = 0;
   var $c42_anousu = 0;
   var $c42_encerramentotipo = 0;
   var $c42_usuario = 0;
   var $c42_data_dia = null;
   var $c42_data_mes = null;
   var $c42_data_ano = null;
   var $c42_data = null;
   var $c42_hora = null;
   var $c42_instit = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 c42_sequencial = int4 = Código Sequencial
                 c42_anousu = int4 = Ano
                 c42_encerramentotipo = int4 = Código do  Encerramento
                 c42_usuario = int4 = Código do Usuário
                 c42_data = date = Data
                 c42_hora = char(5) = Hora
                 c42_instit = int4 = Código da Instituição
                 ";
   //funcao construtor da classe
   function cl_conencerramento() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("conencerramento");
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
       $this->c42_sequencial = ($this->c42_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c42_sequencial"]:$this->c42_sequencial);
       $this->c42_anousu = ($this->c42_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c42_anousu"]:$this->c42_anousu);
       $this->c42_encerramentotipo = ($this->c42_encerramentotipo == ""?@$GLOBALS["HTTP_POST_VARS"]["c42_encerramentotipo"]:$this->c42_encerramentotipo);
       $this->c42_usuario = ($this->c42_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["c42_usuario"]:$this->c42_usuario);
       if($this->c42_data == ""){
         $this->c42_data_dia = ($this->c42_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["c42_data_dia"]:$this->c42_data_dia);
         $this->c42_data_mes = ($this->c42_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c42_data_mes"]:$this->c42_data_mes);
         $this->c42_data_ano = ($this->c42_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["c42_data_ano"]:$this->c42_data_ano);
         if($this->c42_data_dia != ""){
            $this->c42_data = $this->c42_data_ano."-".$this->c42_data_mes."-".$this->c42_data_dia;
         }
       }
       $this->c42_hora = ($this->c42_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["c42_hora"]:$this->c42_hora);
       $this->c42_instit = ($this->c42_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["c42_instit"]:$this->c42_instit);
     }else{
       $this->c42_sequencial = ($this->c42_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c42_sequencial"]:$this->c42_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($c42_sequencial){
      $this->atualizacampos();
     if($this->c42_anousu == null ){
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "c42_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c42_encerramentotipo == null ){
       $this->erro_sql = " Campo Código do  Encerramento nao Informado.";
       $this->erro_campo = "c42_encerramentotipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c42_usuario == null ){
       $this->erro_sql = " Campo Código do Usuário nao Informado.";
       $this->erro_campo = "c42_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c42_data == null ){
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "c42_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c42_hora == null ){
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "c42_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c42_instit == null ){
       $this->erro_sql = " Campo Código da Instituição nao Informado.";
       $this->erro_campo = "c42_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c42_sequencial == "" || $c42_sequencial == null ){
       $result = db_query("select nextval('conencerramento_c42_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: conencerramento_c42_sequencial_seq do campo: c42_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->c42_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from conencerramento_c42_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $c42_sequencial)){
         $this->erro_sql = " Campo c42_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c42_sequencial = $c42_sequencial;
       }
     }
     if(($this->c42_sequencial == null) || ($this->c42_sequencial == "") ){
       $this->erro_sql = " Campo c42_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into conencerramento(
                                       c42_sequencial
                                      ,c42_anousu
                                      ,c42_encerramentotipo
                                      ,c42_usuario
                                      ,c42_data
                                      ,c42_hora
                                      ,c42_instit
                       )
                values (
                                $this->c42_sequencial
                               ,$this->c42_anousu
                               ,$this->c42_encerramentotipo
                               ,$this->c42_usuario
                               ,".($this->c42_data == "null" || $this->c42_data == ""?"null":"'".$this->c42_data."'")."
                               ,'$this->c42_hora'
                               ,$this->c42_instit
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Encerramento do Ano contabil ($this->c42_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Encerramento do Ano contabil já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Encerramento do Ano contabil ($this->c42_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c42_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c42_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10766,'$this->c42_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1853,10766,'','".AddSlashes(pg_result($resaco,0,'c42_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1853,10767,'','".AddSlashes(pg_result($resaco,0,'c42_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1853,10768,'','".AddSlashes(pg_result($resaco,0,'c42_encerramentotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1853,10769,'','".AddSlashes(pg_result($resaco,0,'c42_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1853,10770,'','".AddSlashes(pg_result($resaco,0,'c42_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1853,10771,'','".AddSlashes(pg_result($resaco,0,'c42_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1853,10772,'','".AddSlashes(pg_result($resaco,0,'c42_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($c42_sequencial=null) {
      $this->atualizacampos();
     $sql = " update conencerramento set ";
     $virgula = "";
     if(trim($this->c42_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c42_sequencial"])){
       $sql  .= $virgula." c42_sequencial = $this->c42_sequencial ";
       $virgula = ",";
       if(trim($this->c42_sequencial) == null ){
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "c42_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c42_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c42_anousu"])){
       $sql  .= $virgula." c42_anousu = $this->c42_anousu ";
       $virgula = ",";
       if(trim($this->c42_anousu) == null ){
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "c42_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c42_encerramentotipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c42_encerramentotipo"])){
       $sql  .= $virgula." c42_encerramentotipo = $this->c42_encerramentotipo ";
       $virgula = ",";
       if(trim($this->c42_encerramentotipo) == null ){
         $this->erro_sql = " Campo Código do  Encerramento nao Informado.";
         $this->erro_campo = "c42_encerramentotipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c42_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c42_usuario"])){
       $sql  .= $virgula." c42_usuario = $this->c42_usuario ";
       $virgula = ",";
       if(trim($this->c42_usuario) == null ){
         $this->erro_sql = " Campo Código do Usuário nao Informado.";
         $this->erro_campo = "c42_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c42_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c42_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["c42_data_dia"] !="") ){
       $sql  .= $virgula." c42_data = '$this->c42_data' ";
       $virgula = ",";
       if(trim($this->c42_data) == null ){
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "c42_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["c42_data_dia"])){
         $sql  .= $virgula." c42_data = null ";
         $virgula = ",";
         if(trim($this->c42_data) == null ){
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "c42_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->c42_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c42_hora"])){
       $sql  .= $virgula." c42_hora = '$this->c42_hora' ";
       $virgula = ",";
       if(trim($this->c42_hora) == null ){
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "c42_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c42_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c42_instit"])){
       $sql  .= $virgula." c42_instit = $this->c42_instit ";
       $virgula = ",";
       if(trim($this->c42_instit) == null ){
         $this->erro_sql = " Campo Código da Instituição nao Informado.";
         $this->erro_campo = "c42_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c42_sequencial!=null){
       $sql .= " c42_sequencial = $this->c42_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c42_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10766,'$this->c42_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c42_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1853,10766,'".AddSlashes(pg_result($resaco,$conresaco,'c42_sequencial'))."','$this->c42_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c42_anousu"]))
           $resac = db_query("insert into db_acount values($acount,1853,10767,'".AddSlashes(pg_result($resaco,$conresaco,'c42_anousu'))."','$this->c42_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c42_encerramentotipo"]))
           $resac = db_query("insert into db_acount values($acount,1853,10768,'".AddSlashes(pg_result($resaco,$conresaco,'c42_encerramentotipo'))."','$this->c42_encerramentotipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c42_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1853,10769,'".AddSlashes(pg_result($resaco,$conresaco,'c42_usuario'))."','$this->c42_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c42_data"]))
           $resac = db_query("insert into db_acount values($acount,1853,10770,'".AddSlashes(pg_result($resaco,$conresaco,'c42_data'))."','$this->c42_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c42_hora"]))
           $resac = db_query("insert into db_acount values($acount,1853,10771,'".AddSlashes(pg_result($resaco,$conresaco,'c42_hora'))."','$this->c42_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c42_instit"]))
           $resac = db_query("insert into db_acount values($acount,1853,10772,'".AddSlashes(pg_result($resaco,$conresaco,'c42_instit'))."','$this->c42_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Encerramento do Ano contabil nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c42_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Encerramento do Ano contabil nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c42_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c42_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($c42_sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c42_sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10766,'$c42_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1853,10766,'','".AddSlashes(pg_result($resaco,$iresaco,'c42_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1853,10767,'','".AddSlashes(pg_result($resaco,$iresaco,'c42_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1853,10768,'','".AddSlashes(pg_result($resaco,$iresaco,'c42_encerramentotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1853,10769,'','".AddSlashes(pg_result($resaco,$iresaco,'c42_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1853,10770,'','".AddSlashes(pg_result($resaco,$iresaco,'c42_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1853,10771,'','".AddSlashes(pg_result($resaco,$iresaco,'c42_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1853,10772,'','".AddSlashes(pg_result($resaco,$iresaco,'c42_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from conencerramento
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c42_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c42_sequencial = $c42_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Encerramento do Ano contabil nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c42_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Encerramento do Ano contabil nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c42_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c42_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:conencerramento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $c42_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from conencerramento ";
     $sql .= "      inner join db_config  on  db_config.codigo = conencerramento.c42_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = conencerramento.c42_usuario";
     $sql .= "      inner join conencerramentotipo  on  conencerramentotipo.c43_sequencial = conencerramento.c42_encerramentotipo";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($c42_sequencial!=null ){
         $sql2 .= " where conencerramento.c42_sequencial = $c42_sequencial ";
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
   function sql_query_file ( $c42_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from conencerramento ";
     $sql2 = "";
     if($dbwhere==""){
       if($c42_sequencial!=null ){
         $sql2 .= " where conencerramento.c42_sequencial = $c42_sequencial ";
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
   function consultarRPAjax($iInstit,$iAnousu,$dataUsu,$sFiltros=null){

      $dateparts = explode("/",$dataUsu);
      $dataUsu   = $dateparts[2]."-".$dateparts[1]."-".$dateparts[0];
      $array     = null;
      $sWhere    = " and c75_numemp is  null ";
      $rs        = db_query($this->sqlQueryEmpenhosNaoliquidados($iInstit, $iAnousu,$dataUsu,$sFiltros,$sWhere));
      //echo $this->sqlQueryEmpenhosNaoliquidados($iInstit, $iAnousu,$dataUsu,$sFiltros,$sWhere);exit;
      $objJson   = new services_json();

      (float)$nTotalAliquidar = 0;
      (float)$nTotalLiquidado = 0;
      (float)$nTotalGeral     = 0 ;
      if (!$rs){

        $array   = array("retorno" => "Erro::".$this->erro_msg ,"status" => 0,"erro_banco=".pg_last_error());

      }else{
         $array  = array("status"=> "1","numrows"=> pg_num_rows($rs));

         while ($ln = pg_fetch_array($rs)){
           $array["data"][] = array("e60_numemp" => $ln[0],
                                    "e60_codemp" => $ln[1],
                                    "z01_nome" => urlencode($ln[2]),
                                    "e60_coddot" => $ln[3],
                                    "o58_codigo" => $ln[4],
                                    "a_liquidar" => db_formatar($ln[5],"f"),
                                    "liquidado"  => db_formatar($ln[6],"f"),
                                    "geral"      => db_formatar($ln[7],"f")
                                    );
           $nTotalAliquidar += $ln["a_liquidar"];
           $nTotalLiquidado += $ln["liquidado"];
           $nTotalGeral     += $ln["geral"];


         }
         $array["totalALiquidar"] = db_formatar($nTotalAliquidar,"f");
         $array["totalLiquidado"] = db_formatar($nTotalLiquidado,"f");
         $array["totalGeral"]     = db_formatar($nTotalGeral,"f");
      }
      return $objJson->encode($array);
   }

   function inscreverRP($iInstit, $iAnoUsu, $iEmpenho,$valorLiquidar, $dDataLanc, $iEncerramento){

     $dateparts            = explode("/",$dDataLanc);
     $dDataLancamento      = $dateparts[2]."-".$dateparts[1]."-".$dateparts[0];
     $this->lSqlErro       = false;
     $this->sMsg           = '';
     $cltranslan           = new cl_translan();
     $clencerramentolancam = new cl_conencerramentolancam();
     $clempelemento        = new cl_empelemento();
     $clconlancam          = new cl_conlancam();
     $clconlancamemp       = new cl_conlancamemp();
     $clconlancamdoc       = new cl_conlancamdoc();
     $clconlancamval       = new cl_conlancamval();
     $clconlancamlr        = new cl_conlancamlr();
     $iCodDoc        = 1007;
     //verificando elementos do empenho
	 $rsEle          = $clempelemento->sql_record($clempelemento->sql_query($iEmpenho,null,"empempenho.*,e64_codele,o56_elemento"));
	 $iNumRowsEle    = $clempelemento->numrows;
     $oEle           = db_utils::fieldsMemory($rsEle,0);
     $iAnoUsu        = db_getsession("DB_anousu");
     $dataUsu        = $dDataLancamento;
    //lançamentos na conlamcam
     $valor_liquidar = $valorLiquidar;
	 $clconlancam->c70_anousu = $iAnoUsu;
	 $clconlancam->c70_data   = $dataUsu;
	 $clconlancam->c70_valor  = $valor_liquidar;
	 $clconlancam->incluir(null);
   $lEvento = EventoContabil::vincularLancamentoNaInstituicao($clconlancam->c70_codlan , db_getsession("DB_instit"));
   $lEvento = EventoContabil::vincularOrdem($clconlancam->c70_codlan);
	 $erro_msg=$clconlancam->erro_msg;
	 if ($clconlancam->erro_status==0){

	   $this->lSqlErro  =   true;
       $this->sMsg       =  "Erro ao Incluir lançamento. Erro:".$clconlancam->erro_msg;
	  } else{
        $clencerramentolancam->c44_encerramento = $iEncerramento;
        $clencerramentolancam->c44_conlancam    = $clconlancam->c70_codlan;
        $clencerramentolancam->incluir(null);
		    $c70_codlan = $clconlancam->c70_codlan;
        //lancamento ok... incluimos o conlancam emp (1007);
		    $clconlancamemp->c75_codlan = $c70_codlan;
		    $clconlancamemp->c75_numemp = $iEmpenho;
		    $clconlancamemp->c75_data   = $dataUsu;
		    $clconlancamemp->incluir($c70_codlan);
		    $erro_msg=$clconlancamemp->erro_msg;
		    if($clconlancamemp->erro_status==0){
		      $this->lSqlErro = true;
          $this->sMsg     = "Erro ao Incluir Empenho do Lançamento. Erro:".$clconlancamemp->erro_msg;
		    }else{
        //empenho do lançamento incluido ..vamos incluir documentos;
		     $clconlancamdoc->c71_data    = $dataUsu;
   	 	   $clconlancamdoc->c71_coddoc  = $iCodDoc;
	  	   $clconlancamdoc->c71_codlan  = $c70_codlan;
		     $clconlancamdoc->incluir($c70_codlan);
		    // $erro_msg=$clconlancamdoc->erro_msg;
		    if($clconlancamdoc->erro_status==0){
		       $this->lSqlErro  = true;
           $this->sMsg      = "Erro ao Incluir documento do Lancamento. Erro:".$clconlancamdoc->erro_msg;

		    }else{
          //incluimos transcacoes...
            try {
              $cltranslan->db_trans_inscricao_rp($oEle->e60_codcom,$oEle->e64_codele,$iAnoUsu);
            } catch (Exception $eErro) {

              $this->lSqlErro = true;
              $this->sMsg     = $eErro->getMessage();
            }
            if (!$this->lSqlErro) {

              $arr_debito     = $cltranslan->arr_debito;
              $arr_credito    = $cltranslan->arr_credito;
              $arr_histori    = $cltranslan->arr_histori;
              $arr_seqtranslr = $cltranslan->arr_seqtranslr;
              if (count($arr_credito) > 0){
                for ( $t=0; $t < count($arr_credito); $t++){

                  $clconlancamval->c69_codlan  = $c70_codlan;
                  $clconlancamval->c69_credito = $arr_credito[$t];
                  $clconlancamval->c69_debito  = $arr_debito[$t];
                  $clconlancamval->c69_codhist = $arr_histori[$t];
                  $clconlancamval->c69_valor   = $valor_liquidar;
                  $clconlancamval->c69_data    = $dataUsu;
                  $clconlancamval->c69_anousu  = $iAnoUsu;
                  $clconlancamval->incluir(null);
                  if($clconlancamval->erro_status==0){
                     $this->lSqlErro = true;
                     $this->sMsg     = $clconlancamval->erro_msg;
                     break;
                   }else{
                     $c69_sequen =  $clconlancamval->c69_sequen;
                     $clconlancamlr->c81_codlan      = $c69_sequen;
                     $clconlancamlr->c81_seqtranslr  = $arr_seqtranslr[$t];
                     $clconlancamlr->incluir($c69_sequen,$arr_seqtranslr[$t]);
                  }
               }
              }else{

               $this->lSqlErro = true;
               $this->sMsg     = "Sem Transacao ( 1007 ) Cadastrada - codcom [$oEle->e60_codcom] - codele [$oEle->e64_codele] - anousu [$iAnoUsu]";
              }
            }
         }
       }
		 }
  }
   function inscreverRPAjax($iInstit, $iAnoUsu, $listaEmpenhos, $dDataLanc){

     $this->lSqlErro = false;
     $objJson = new services_json();
     $this->retornoJson = array();
     $dateparts            = explode("/",$dDataLanc);
     $dDataLancamento      = $dateparts[2]."-".$dateparts[1]."-".$dateparts[0];
     if (is_array($listaEmpenhos)){

        db_inicio_transacao();
        $rsEnce               = $this->sql_record($this->sql_query(null,"*",null," c42_instit           = $iInstit
                                                                     and  c42_anousu           = $iAnoUsu
                                                                     and  c42_encerramentotipo = 1"));
        if ($this->numrows == 0){

           $this->c42_instit           = $iInstit;
           $this->c42_anousu           = $iAnoUsu;
           $this->c42_encerramentotipo = 1;
           $this->c42_usuario          = db_getsession("DB_id_usuario");
           $this->c42_hora             = date("H:i");
           $this->c42_data             = $dDataLancamento;
           $this->incluir(null);
           $iEncerramento = $this->c42_sequencial;

        } else {

           $oEnce         = db_utils::fieldsMemory($rsEnce,0);
           $iEncerramento = $oEnce->c42_sequencial;

        }
        for ($i = 0;$i < count($listaEmpenhos); $i++){

           $this->inscreverRP($iInstit, $iAnoUsu, $listaEmpenhos[$i]->empenho,(float)$listaEmpenhos[$i]->valorLiquidar,$dDataLanc, $iEncerramento);
           if ($this->lSqlErro){

             $this->retornoJson["erro"]     = "2";
             $this->retornoJson["mensagem"] = urlencode(str_replace("\\n","",$this->sMsg));
             break;
           }
        }
        db_fim_transacao($this->lSqlErro);
        if (!$this->lSqlErro){

             $this->retornoJson["erro"]     = "1";
             $this->retornoJson["mensagem"] = urlencode("Lançamentos Efetuados com Sucesso");
        }
     }else{
       $this->retornoJson["erro"]     = "1";
       $this->retornoJson["mensagem"] = "Nao eh Array...";
     }
     return $objJson->encode($this->retornoJson);

   }

   function sqlQueryEmpenhosNaoliquidados($iInstit, $iAnoUsu,$dataUsu, $sFiltros = null,$sWhere = null){

      $dataIni = "$iAnoUsu-01-01";
      $sSql = "select empempenho.e60_numemp,
             e60_codemp,
         z01_nome,
         e60_coddot,
         o15_codigo,
         round((yyy.e60_vlremp - yyy.e60_vlranu - yyy.e60_vlrliq),2) as a_liquidar,
         round((yyy.e60_vlrliq - yyy.e60_vlrpag),2) as liquidado,
         round((yyy.e60_vlremp - yyy.e60_vlranu - yyy.e60_vlrpag),2) as geral
    from (select e60_numemp,
                 sum(case when c53_tipo = 10 then c70_valor else 0 end) as e60_vlremp,
                 sum(case when c53_tipo = 11 then c70_valor else 0 end) as e60_vlranu,
                 sum(case when c53_tipo = 20 then c70_valor else 0 end) - sum(case when c53_tipo = 21 then c70_valor else 0 end) as e60_vlrliq,
                 sum(case when c53_tipo = 30 then c70_valor else 0 end) - sum(case when c53_tipo = 31 then c70_valor else 0 end) as e60_vlrpag
            from (select e60_numemp,
                         c53_tipo,
                         sum(c70_valor) as c70_valor
                    from (select e60_numemp,
                                 e60_anousu,
                                 e60_coddot
                            from empempenho
                           where e60_instit = $iInstit
                             and e60_emiss between '$dataIni' and '$dataUsu' ) as xxx
                  inner join orcdotacao   on orcdotacao.o58_anousu = xxx.e60_anousu
                                         and orcdotacao.o58_coddot = xxx.e60_coddot
                  inner join orcelemento  on orcelemento.o56_codele = orcdotacao.o58_codele
                                         and orcelemento.o56_anousu = orcdotacao.o58_anousu
                  inner join conlancamemp on c75_numemp = xxx.e60_numemp
                  inner join conlancam    on c70_codlan = c75_codlan
                                         and c70_data <= '$dataUsu'
                  inner join conlancamdoc on c71_codlan = c70_codlan
                  inner join conhistdoc   on c53_coddoc = c71_coddoc
                                         and c53_tipo in (10,11,20,21,30,31)
                  inner join conlancamdot on c73_codlan = c75_codlan
            group by e60_numemp, c53_tipo ) as xxx
    group by e60_numemp) as yyy
    inner join empempenho on empempenho.e60_numemp = yyy.e60_numemp
    inner join cgm        on cgm.z01_numcgm        = empempenho.e60_numcgm
    inner join db_config  on db_config.codigo      = empempenho.e60_instit
    inner join orcdotacao on orcdotacao.o58_anousu = empempenho.e60_anousu
                         and orcdotacao.o58_coddot = empempenho.e60_coddot
    inner join emptipo    on emptipo.e41_codtipo   = empempenho.e60_codtipo
    inner join db_config as a on a.codigo = orcdotacao.o58_instit
    inner join orctiporec on orctiporec.o15_codigo = orcdotacao.o58_codigo
    inner join orcfuncao  on orcfuncao.o52_funcao  = orcdotacao.o58_funcao
    inner join orcsubfuncao on orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao
    inner join orcprograma on orcprograma.o54_anousu = orcdotacao.o58_anousu
                          and orcprograma.o54_programa = orcdotacao.o58_programa
    inner join orcelemento on orcelemento.o56_codele = orcdotacao.o58_codele
                          and orcelemento.o56_anousu = orcdotacao.o58_anousu
    inner join orcprojativ on orcprojativ.o55_anousu = orcdotacao.o58_anousu
                          and orcprojativ.o55_projativ = orcdotacao.o58_projativ
    inner join orcorgao    on orcorgao.o40_anousu      = orcdotacao.o58_anousu
                          and orcorgao.o40_orgao = orcdotacao.o58_orgao
    inner join orcunidade on orcunidade.o41_anousu = orcdotacao.o58_anousu
                         and orcunidade.o41_orgao = orcdotacao.o58_orgao
                         and orcunidade.o41_unidade = orcdotacao.o58_unidade
    left join empemphist  on empemphist.e63_numemp = empempenho.e60_numemp
    left join emphist     on emphist.e40_codhist  = empemphist.e63_codhist
    left join (select c75_numemp
                from  conencerramentolancam
                                           inner join conencerramento on c42_sequencial = c44_encerramento
                                           inner join conlancam       on c44_conlancam  = c70_codlan
                                                                     and c70_anousu     = $iAnoUsu
                                           inner join conlancamemp on c70_codlan        = c75_codlan
                          where c42_anousu  = $iAnoUsu
                            and c42_instit  = $iInstit
                            and c42_encerramentotipo = 1  ) as c
      on yyy.e60_numemp = c75_numemp
   where e60_instit = $iInstit
     and e60_emiss between '$dataIni' and '$dataUsu'
     and (round(yyy.e60_vlremp,2) - round(yyy.e60_vlranu,2) - round(yyy.e60_vlrliq,2) > 0)
     $sWhere";
    if ($sFiltros != null){
				require modification("libs/db_liborcamento.php");

        $clselorcdotacao = new cl_selorcdotacao();
        $clselorcdotacao->setdados($sFiltros);
        $filtra =  $clselorcdotacao->getDados(false);
        $sele_desdobramentos="";
        $desdobramentos = $clselorcdotacao->getDesdobramento(); // coloca os codele dos desdobramntos no formato (x,y,z)
       if ($desdobramentos != "") {
           $filtra .= " and empelemento.e64_codele in ".$desdobramentos; // adiciona desdobramentos
        }
        $sSql .= " and  $filtra ";
    }
    $sSql .= " order by e60_codemp::integer";
   // echo $sSql;exit;
    return $sSql;
  }


  /**
   * Método Lança Bloqueio Contabil
   *
   * Verifica se existem os lançamentos do tipo 2/3/4 em conencerramento. Se houver
   * será incluído um registro em condataconf referente ao ANOUSU e INSTIT
   *
   * @param string $sListaEncerramento
   * @throws Exception
   * @return integer 1 - Bloqueio aplicado, 2 - Bloqueio removido, 3 - Nenhuma ação
   * @author Matheus Felini
   */
  public function lancaBloqueioContabil($sListaEncerramento = '2,3,4') {

    $oDaoConDataConf = db_utils::getDao("condataconf");
    $iAnoUso         = db_getsession('DB_anousu');
    $iInstituicao    = db_getsession('DB_instit');
    $dDataFinal      = "{$iAnoUso}-12-31";
    $iIdUsuario      = db_getsession('DB_id_usuario');

    $sCondicaoEncerra      = "c42_anousu={$iAnoUso} AND ";
    $sCondicaoEncerra     .= "c42_instit={$iInstituicao} AND ";
    $sCondicaoEncerra     .= "c42_encerramentotipo IN ($sListaEncerramento)";
    $sSqlConEncerramento   = $this->sql_query_file(NULL, 'count(c42_encerramentotipo) as c42_encerramentotipo', null, $sCondicaoEncerra);
    $rsSqlConEncerramento  = $this->sql_record($sSqlConEncerramento);
    $iLinhasAnoInsti       = $this->numrows;
    $iRetorno              = 3;
    $aListaEncerramento    = explode(',', $sListaEncerramento);

    /**
     * Se retornar mais de 1 registro da tabela conencerramento
     * executa a ação abaixo
     */
    if ($iLinhasAnoInsti > 0) {

      $oQueryAnoInsti         = db_utils::fieldsMemory($rsSqlConEncerramento, 0);
      $lEncerramentoExecutado = $oQueryAnoInsti->c42_encerramentotipo == count($aListaEncerramento);

      /**
       * Verifica se os encerramentos foram processados
       */
      if ($lEncerramentoExecutado) {

        $sSqlConDataConf   = $oDaoConDataConf->sql_query_file(null, null,
                                                              '*',
                                                              null,
                                                              "c99_anousu={$iAnoUso} AND c99_instit={$iInstituicao}");
        $rsSqlConDataConf  = $oDaoConDataConf->sql_record($sSqlConDataConf);
        $iLinhaConDataConf = $oDaoConDataConf->numrows;

        $oDaoConDataConf->c99_anousu  = $iAnoUso;
        $oDaoConDataConf->c99_instit  = $iInstituicao;
        $oDaoConDataConf->c99_data    = $dDataFinal;
        $oDaoConDataConf->c99_usuario = $iIdUsuario;

        /**
         * Se existir registro, atualiza a data (c99_data)
         */
        if ($iLinhaConDataConf > 0) {
          $oDaoConDataConf->alterar($iAnoUso, $iInstituicao);
        } else {
          $oDaoConDataConf->incluir($iAnoUso, $iInstituicao);
        }
        if ($oDaoConDataConf->erro_status == 0) {
          throw new Exception('Erro ao Lançar Bloqueio');
        }
        $iRetorno = 1;

      } else {

        /**
         * Se os encerramentos não foram processados, significa que não há um dos lançamentos em conencerramento
         * o que ímplica nos lançamentos condataconf
         */
        $lVerificaLancamentoContabil = $this->verificaLancamentoContabil();
        if ($lVerificaLancamentoContabil) {
         $iRetorno = 2;
        }
      }
    }

    return $iRetorno;
  }

  /**
   * Método Verifica Lançamento Contabil
   *
   * Antes de excluir um lançamento em 'conencerramento', verifica se há anousu, instituicao
   * e data em condataconf. Se houver, este lançamento será excluido pois conencerramento necessita
   * de lançamentos do tipo 2/3/4.
   *
   * @return BOOL
   * @author Matheus Felini
   */
  public function verificaLancamentoContabil() {

    $oDaoConDataConf    = db_utils::getDao("condataconf");
    $iAnoUso            = db_getsession('DB_anousu');
    $iInstituicao       = db_getsession('DB_instit');
    $dDataFinal         = "{$iAnoUso}-12-31";
    $iIdUsuario         = db_getsession('DB_id_usuario');

    $sCondicaoDataConf  = "c99_anousu={$iAnoUso} AND c99_instit={$iInstituicao} AND c99_data='{$dDataFinal}'";
    $sSqlDataConf       = $oDaoConDataConf->sql_query_file($iAnoUso, $iInstituicao, '*', null, $sCondicaoDataConf);
    $rsDataConf         = $oDaoConDataConf->sql_record($sSqlDataConf);
    $iLinhaBuscaConData = $oDaoConDataConf->numrows;
    $lRetorno           = false;
    /**
     * Se Existir registros com a data 31/12/<ano_uso> o registro será excluído
     */
    if ($iLinhaBuscaConData > 0) {

      $sCondicaoDataConfExc  = "c99_anousu={$iAnoUso} AND c99_instit={$iInstituicao} AND c99_data='{$dDataFinal}'";
      $oDaoConDataConf->excluir(null, null, $sCondicaoDataConfExc);

      if ($oDaoConDataConf->erro_status == 0) {
        throw new Exception('Exclusão não pôde ser realizada.');
      }
      $lRetorno =  true;
    }
    return $lRetorno;
  }
}
