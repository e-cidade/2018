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

//MODULO: TFD
//CLASSE DA ENTIDADE tfd_veiculodestino
class cl_tfd_veiculodestino {
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
   var $tf18_i_codigo = 0;
   var $tf18_i_veiculo = 0;
   var $tf18_i_destino = 0;
   var $tf18_i_motorista = 0;
   var $tf18_d_datasaida_dia = null;
   var $tf18_d_datasaida_mes = null;
   var $tf18_d_datasaida_ano = null;
   var $tf18_d_datasaida = null;
   var $tf18_c_horasaida = null;
   var $tf18_d_dataretorno_dia = null;
   var $tf18_d_dataretorno_mes = null;
   var $tf18_d_dataretorno_ano = null;
   var $tf18_d_dataretorno = null;
   var $tf18_c_horaretorno = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 tf18_i_codigo = int4 = Código
                 tf18_i_veiculo = int4 = Veículo
                 tf18_i_destino = int4 = Destino
                 tf18_i_motorista = int4 = Motorista
                 tf18_d_datasaida = date = Data
                 tf18_c_horasaida = char(5) = Horário
                 tf18_d_dataretorno = date = Data de Retorno
                 tf18_c_horaretorno = char(5) = Hora de Retorno
                 ";
   //funcao construtor da classe
   function cl_tfd_veiculodestino() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tfd_veiculodestino");
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
       $this->tf18_i_codigo = ($this->tf18_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf18_i_codigo"]:$this->tf18_i_codigo);
       $this->tf18_i_veiculo = ($this->tf18_i_veiculo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf18_i_veiculo"]:$this->tf18_i_veiculo);
       $this->tf18_i_destino = ($this->tf18_i_destino == ""?@$GLOBALS["HTTP_POST_VARS"]["tf18_i_destino"]:$this->tf18_i_destino);
       $this->tf18_i_motorista = ($this->tf18_i_motorista == ""?@$GLOBALS["HTTP_POST_VARS"]["tf18_i_motorista"]:$this->tf18_i_motorista);
       if($this->tf18_d_datasaida == ""){
         $this->tf18_d_datasaida_dia = ($this->tf18_d_datasaida_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["tf18_d_datasaida_dia"]:$this->tf18_d_datasaida_dia);
         $this->tf18_d_datasaida_mes = ($this->tf18_d_datasaida_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["tf18_d_datasaida_mes"]:$this->tf18_d_datasaida_mes);
         $this->tf18_d_datasaida_ano = ($this->tf18_d_datasaida_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["tf18_d_datasaida_ano"]:$this->tf18_d_datasaida_ano);
         if($this->tf18_d_datasaida_dia != ""){
            $this->tf18_d_datasaida = $this->tf18_d_datasaida_ano."-".$this->tf18_d_datasaida_mes."-".$this->tf18_d_datasaida_dia;
         }
       }
       $this->tf18_c_horasaida = ($this->tf18_c_horasaida == ""?@$GLOBALS["HTTP_POST_VARS"]["tf18_c_horasaida"]:$this->tf18_c_horasaida);
       if($this->tf18_d_dataretorno == ""){
         $this->tf18_d_dataretorno_dia = ($this->tf18_d_dataretorno_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["tf18_d_dataretorno_dia"]:$this->tf18_d_dataretorno_dia);
         $this->tf18_d_dataretorno_mes = ($this->tf18_d_dataretorno_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["tf18_d_dataretorno_mes"]:$this->tf18_d_dataretorno_mes);
         $this->tf18_d_dataretorno_ano = ($this->tf18_d_dataretorno_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["tf18_d_dataretorno_ano"]:$this->tf18_d_dataretorno_ano);
         if($this->tf18_d_dataretorno_dia != ""){
            $this->tf18_d_dataretorno = $this->tf18_d_dataretorno_ano."-".$this->tf18_d_dataretorno_mes."-".$this->tf18_d_dataretorno_dia;
         }
       }
       $this->tf18_c_horaretorno = ($this->tf18_c_horaretorno == ""?@$GLOBALS["HTTP_POST_VARS"]["tf18_c_horaretorno"]:$this->tf18_c_horaretorno);
     }else{
       $this->tf18_i_codigo = ($this->tf18_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf18_i_codigo"]:$this->tf18_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($tf18_i_codigo){
      $this->atualizacampos();
     if($this->tf18_i_veiculo == null ){
       $this->erro_sql = " Campo Veículo não informado.";
       $this->erro_campo = "tf18_i_veiculo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf18_i_destino == null ){
       $this->erro_sql = " Campo Destino não informado.";
       $this->erro_campo = "tf18_i_destino";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf18_i_motorista == null ){
       $this->tf18_i_motorista = "null";
     }
     if($this->tf18_d_datasaida == null ){
       $this->erro_sql = " Campo Data não informado.";
       $this->erro_campo = "tf18_d_datasaida_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf18_c_horasaida == null ){
       $this->erro_sql = " Campo Horário não informado.";
       $this->erro_campo = "tf18_c_horasaida";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf18_d_dataretorno == null ){
       $this->erro_sql = " Campo Data de Retorno não informado.";
       $this->erro_campo = "tf18_d_dataretorno_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf18_c_horaretorno == null ){
       $this->erro_sql = " Campo Hora de Retorno não informado.";
       $this->erro_campo = "tf18_c_horaretorno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($tf18_i_codigo == "" || $tf18_i_codigo == null ){
       $result = db_query("select nextval('tfd_veiculodestino_tf18_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tfd_veiculodestino_tf18_i_codigo_seq do campo: tf18_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->tf18_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from tfd_veiculodestino_tf18_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $tf18_i_codigo)){
         $this->erro_sql = " Campo tf18_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->tf18_i_codigo = $tf18_i_codigo;
       }
     }
     if(($this->tf18_i_codigo == null) || ($this->tf18_i_codigo == "") ){
       $this->erro_sql = " Campo tf18_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tfd_veiculodestino(
                                       tf18_i_codigo
                                      ,tf18_i_veiculo
                                      ,tf18_i_destino
                                      ,tf18_i_motorista
                                      ,tf18_d_datasaida
                                      ,tf18_c_horasaida
                                      ,tf18_d_dataretorno
                                      ,tf18_c_horaretorno
                       )
                values (
                                $this->tf18_i_codigo
                               ,$this->tf18_i_veiculo
                               ,$this->tf18_i_destino
                               ,$this->tf18_i_motorista
                               ,".($this->tf18_d_datasaida == "null" || $this->tf18_d_datasaida == ""?"null":"'".$this->tf18_d_datasaida."'")."
                               ,'$this->tf18_c_horasaida'
                               ,".($this->tf18_d_dataretorno == "null" || $this->tf18_d_dataretorno == ""?"null":"'".$this->tf18_d_dataretorno."'")."
                               ,'$this->tf18_c_horaretorno'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "tfd_veiculodestino ($this->tf18_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "tfd_veiculodestino já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "tfd_veiculodestino ($this->tf18_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tf18_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->tf18_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16411,'$this->tf18_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,2874,16411,'','".AddSlashes(pg_result($resaco,0,'tf18_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2874,16414,'','".AddSlashes(pg_result($resaco,0,'tf18_i_veiculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2874,16412,'','".AddSlashes(pg_result($resaco,0,'tf18_i_destino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2874,16413,'','".AddSlashes(pg_result($resaco,0,'tf18_i_motorista'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2874,16416,'','".AddSlashes(pg_result($resaco,0,'tf18_d_datasaida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2874,16417,'','".AddSlashes(pg_result($resaco,0,'tf18_c_horasaida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2874,17299,'','".AddSlashes(pg_result($resaco,0,'tf18_d_dataretorno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2874,17300,'','".AddSlashes(pg_result($resaco,0,'tf18_c_horaretorno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($tf18_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update tfd_veiculodestino set ";
     $virgula = "";
     if(trim($this->tf18_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf18_i_codigo"])){
       $sql  .= $virgula." tf18_i_codigo = $this->tf18_i_codigo ";
       $virgula = ",";
       if(trim($this->tf18_i_codigo) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "tf18_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf18_i_veiculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf18_i_veiculo"])){
       $sql  .= $virgula." tf18_i_veiculo = $this->tf18_i_veiculo ";
       $virgula = ",";
       if(trim($this->tf18_i_veiculo) == null ){
         $this->erro_sql = " Campo Veículo não informado.";
         $this->erro_campo = "tf18_i_veiculo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf18_i_destino)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf18_i_destino"])){
       $sql  .= $virgula." tf18_i_destino = $this->tf18_i_destino ";
       $virgula = ",";
       if(trim($this->tf18_i_destino) == null ){
         $this->erro_sql = " Campo Destino não informado.";
         $this->erro_campo = "tf18_i_destino";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf18_i_motorista)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf18_i_motorista"])){
        if(trim($this->tf18_i_motorista)=="" && isset($GLOBALS["HTTP_POST_VARS"]["tf18_i_motorista"])){
           $this->tf18_i_motorista = "0" ;
        }
       $sql  .= $virgula." tf18_i_motorista = $this->tf18_i_motorista ";
       $virgula = ",";
     }
     if(trim($this->tf18_d_datasaida)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf18_d_datasaida_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["tf18_d_datasaida_dia"] !="") ){
       $sql  .= $virgula." tf18_d_datasaida = '$this->tf18_d_datasaida' ";
       $virgula = ",";
       if(trim($this->tf18_d_datasaida) == null ){
         $this->erro_sql = " Campo Data não informado.";
         $this->erro_campo = "tf18_d_datasaida_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["tf18_d_datasaida_dia"])){
         $sql  .= $virgula." tf18_d_datasaida = null ";
         $virgula = ",";
         if(trim($this->tf18_d_datasaida) == null ){
           $this->erro_sql = " Campo Data não informado.";
           $this->erro_campo = "tf18_d_datasaida_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->tf18_c_horasaida)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf18_c_horasaida"])){
       $sql  .= $virgula." tf18_c_horasaida = '$this->tf18_c_horasaida' ";
       $virgula = ",";
       if(trim($this->tf18_c_horasaida) == null ){
         $this->erro_sql = " Campo Horário não informado.";
         $this->erro_campo = "tf18_c_horasaida";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf18_d_dataretorno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf18_d_dataretorno_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["tf18_d_dataretorno_dia"] !="") ){
       $sql  .= $virgula." tf18_d_dataretorno = '$this->tf18_d_dataretorno' ";
       $virgula = ",";
       if(trim($this->tf18_d_dataretorno) == null ){
         $this->erro_sql = " Campo Data de Retorno não informado.";
         $this->erro_campo = "tf18_d_dataretorno_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["tf18_d_dataretorno_dia"])){
         $sql  .= $virgula." tf18_d_dataretorno = null ";
         $virgula = ",";
         if(trim($this->tf18_d_dataretorno) == null ){
           $this->erro_sql = " Campo Data de Retorno não informado.";
           $this->erro_campo = "tf18_d_dataretorno_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->tf18_c_horaretorno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf18_c_horaretorno"])){
       $sql  .= $virgula." tf18_c_horaretorno = '$this->tf18_c_horaretorno' ";
       $virgula = ",";
       if(trim($this->tf18_c_horaretorno) == null ){
         $this->erro_sql = " Campo Hora de Retorno não informado.";
         $this->erro_campo = "tf18_c_horaretorno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($tf18_i_codigo!=null){
       $sql .= " tf18_i_codigo = $this->tf18_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->tf18_i_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,16411,'$this->tf18_i_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["tf18_i_codigo"]) || $this->tf18_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,2874,16411,'".AddSlashes(pg_result($resaco,$conresaco,'tf18_i_codigo'))."','$this->tf18_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["tf18_i_veiculo"]) || $this->tf18_i_veiculo != "")
             $resac = db_query("insert into db_acount values($acount,2874,16414,'".AddSlashes(pg_result($resaco,$conresaco,'tf18_i_veiculo'))."','$this->tf18_i_veiculo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["tf18_i_destino"]) || $this->tf18_i_destino != "")
             $resac = db_query("insert into db_acount values($acount,2874,16412,'".AddSlashes(pg_result($resaco,$conresaco,'tf18_i_destino'))."','$this->tf18_i_destino',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["tf18_i_motorista"]) || $this->tf18_i_motorista != "")
             $resac = db_query("insert into db_acount values($acount,2874,16413,'".AddSlashes(pg_result($resaco,$conresaco,'tf18_i_motorista'))."','$this->tf18_i_motorista',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["tf18_d_datasaida"]) || $this->tf18_d_datasaida != "")
             $resac = db_query("insert into db_acount values($acount,2874,16416,'".AddSlashes(pg_result($resaco,$conresaco,'tf18_d_datasaida'))."','$this->tf18_d_datasaida',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["tf18_c_horasaida"]) || $this->tf18_c_horasaida != "")
             $resac = db_query("insert into db_acount values($acount,2874,16417,'".AddSlashes(pg_result($resaco,$conresaco,'tf18_c_horasaida'))."','$this->tf18_c_horasaida',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["tf18_d_dataretorno"]) || $this->tf18_d_dataretorno != "")
             $resac = db_query("insert into db_acount values($acount,2874,17299,'".AddSlashes(pg_result($resaco,$conresaco,'tf18_d_dataretorno'))."','$this->tf18_d_dataretorno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["tf18_c_horaretorno"]) || $this->tf18_c_horaretorno != "")
             $resac = db_query("insert into db_acount values($acount,2874,17300,'".AddSlashes(pg_result($resaco,$conresaco,'tf18_c_horaretorno'))."','$this->tf18_c_horaretorno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tfd_veiculodestino nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->tf18_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "tfd_veiculodestino nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->tf18_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tf18_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($tf18_i_codigo=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($tf18_i_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,16411,'$tf18_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,2874,16411,'','".AddSlashes(pg_result($resaco,$iresaco,'tf18_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2874,16414,'','".AddSlashes(pg_result($resaco,$iresaco,'tf18_i_veiculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2874,16412,'','".AddSlashes(pg_result($resaco,$iresaco,'tf18_i_destino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2874,16413,'','".AddSlashes(pg_result($resaco,$iresaco,'tf18_i_motorista'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2874,16416,'','".AddSlashes(pg_result($resaco,$iresaco,'tf18_d_datasaida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2874,16417,'','".AddSlashes(pg_result($resaco,$iresaco,'tf18_c_horasaida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2874,17299,'','".AddSlashes(pg_result($resaco,$iresaco,'tf18_d_dataretorno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2874,17300,'','".AddSlashes(pg_result($resaco,$iresaco,'tf18_c_horaretorno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from tfd_veiculodestino
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($tf18_i_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " tf18_i_codigo = $tf18_i_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tfd_veiculodestino nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$tf18_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "tfd_veiculodestino nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$tf18_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$tf18_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao do recordset
   public function sql_record($sql) {
     $result = db_query($sql);
     if (!$result) {
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:tfd_veiculodestino";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($tf18_i_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from tfd_veiculodestino ";
     $sql .= "      inner join veiculos  on  veiculos.ve01_codigo = tfd_veiculodestino.tf18_i_veiculo";
     $sql .= "      left  join veicmotoristas  on  veicmotoristas.ve05_codigo = tfd_veiculodestino.tf18_i_motorista";
     $sql .= "      inner join tfd_destino  on  tfd_destino.tf03_i_codigo = tfd_veiculodestino.tf18_i_destino";
     $sql .= "      inner join ceplocalidades  on  ceplocalidades.cp05_codlocalidades = veiculos.ve01_ceplocalidades";
     $sql .= "      inner join veiccadtipo  on  veiccadtipo.ve20_codigo = veiculos.ve01_veiccadtipo";
     $sql .= "      inner join veiccadmarca  on  veiccadmarca.ve21_codigo = veiculos.ve01_veiccadmarca";
     $sql .= "      inner join veiccadmodelo  on  veiccadmodelo.ve22_codigo = veiculos.ve01_veiccadmodelo";
     $sql .= "      inner join veiccadcor  on  veiccadcor.ve23_codigo = veiculos.ve01_veiccadcor";
     $sql .= "      inner join veiccadtipocapacidade  on  veiccadtipocapacidade.ve24_codigo = veiculos.ve01_veiccadtipocapacidade";
     $sql .= "      inner join veiccadcategcnh  on  veiccadcategcnh.ve30_codigo = veiculos.ve01_veiccadcategcnh";
     $sql .= "      inner join veiccadproced  on  veiccadproced.ve25_codigo = veiculos.ve01_veiccadproced";
     $sql .= "      inner join veiccadpotencia  on  veiccadpotencia.ve31_codigo = veiculos.ve01_veiccadpotencia";
     $sql .= "      inner join veiccadcateg  as a on   a.ve32_codigo = veiculos.ve01_veiccadcateg";
     $sql .= "      inner join veictipoabast  on  veictipoabast.ve07_sequencial = veiculos.ve01_veictipoabast";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = veicmotoristas.ve05_numcgm";
     $sql .= "      inner join veiccadcategcnh  as b on   b.ve30_codigo = veicmotoristas.ve05_veiccadcategcnh";
     $sql .= "      inner join veiccadmotoristasit  on  veiccadmotoristasit.ve33_codigo = veicmotoristas.ve05_veiccadmotoristasit";
     $sql .= "      inner join tfd_tipodistancia  on  tfd_tipodistancia.tf24_i_codigo = tfd_destino.tf03_i_tipodistancia";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($tf18_i_codigo)) {
         $sql2 .= " where tfd_veiculodestino.tf18_i_codigo = $tf18_i_codigo ";
       }
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }
   // funcao do sql
   public function sql_query_file ($tf18_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from tfd_veiculodestino ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($tf18_i_codigo)){
         $sql2 .= " where tfd_veiculodestino.tf18_i_codigo = $tf18_i_codigo ";
       }
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }

   function sql_query_lista_daer ( $tf18_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from tfd_veiculodestino ";
		 $sql .= "      left  join veiculos                    on veiculos.ve01_codigo                        = tfd_veiculodestino.tf18_i_veiculo";
		 $sql .= "      left  join veiccadmarca                on veiccadmarca.ve21_codigo                    = veiculos.ve01_veiccadmarca";
		 $sql .= "      left  join veiccadmodelo               on veiccadmodelo.ve22_codigo                   = veiculos.ve01_veiccadmodelo";
		 $sql .= "      left  join veicresp  	                 on veicresp.ve02_veiculo                       = veiculos.ve01_codigo";
		 $sql .= "      left  join cgm as empresa              on empresa.z01_numcgm                          =  veicresp.ve02_numcgm";
		 $sql .= "      left  join veicmotoristas              on veicmotoristas.ve05_codigo                  = tfd_veiculodestino.tf18_i_motorista";
		 $sql .= "      left  join cgm  				               on cgm.z01_numcgm                              = veicmotoristas.ve05_numcgm";
		 $sql .= "      inner join tfd_destino                 on tfd_destino.tf03_i_codigo                   = tfd_veiculodestino.tf18_i_destino";
     $sql .= "      inner join tfd_tipodistancia           on tfd_tipodistancia.tf24_i_codigo             = tfd_destino.tf03_i_tipodistancia";
     $sql .= "      inner join tfd_passageiroveiculo       on tfd_passageiroveiculo.tf19_i_veiculodestino = tfd_veiculodestino.tf18_i_codigo";
     $sql .= "        															      and tfd_passageiroveiculo.tf19_i_valido         = 1";
     $sql .= "      inner join tfd_pedidotfd  			       on tfd_pedidotfd.tf01_i_codigo                 = tfd_passageiroveiculo.tf19_i_pedidotfd";
     $sql .= "      inner join tfd_agendasaida             on tfd_agendasaida.tf17_i_pedidotfd            = tfd_pedidotfd.tf01_i_codigo";
     $sql .= "      inner join tfd_agendamentoprestadora   on tfd_agendamentoprestadora.tf16_i_pedidotfd  = tfd_pedidotfd.tf01_i_codigo";
     $sql .= "      inner join tfd_prestadoracentralagend  on tfd_prestadoracentralagend.tf10_i_codigo    = tfd_agendamentoprestadora.tf16_i_prestcentralagend";
     $sql .= "      inner join tfd_prestadora  						 on tfd_prestadora.tf25_i_codigo                = tfd_prestadoracentralagend.tf10_i_prestadora";
     $sql .= "      inner join cgm as a  									 on a.z01_numcgm                                = tfd_prestadora.tf25_i_cgm";
     $sql .= "      inner join cgs_und  									 on cgs_und.z01_i_cgsund                        = tfd_passageiroveiculo.tf19_i_cgsund";
     $sql2 = "";
     if($dbwhere==""){
       if($tf18_i_codigo!=null ){
         $sql2 .= " where tfd_veiculodestino.tf18_i_codigo = $tf18_i_codigo ";
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
   function sql_query2 ( $tf18_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from tfd_veiculodestino ";
     $sql .= "      left  join veiculos  on  veiculos.ve01_codigo = tfd_veiculodestino.tf18_i_veiculo";
     $sql .= "      left  join veicmotoristas  on  veicmotoristas.ve05_codigo = tfd_veiculodestino.tf18_i_motorista";
     $sql .= "      inner join tfd_destino  on  tfd_destino.tf03_i_codigo = tfd_veiculodestino.tf18_i_destino";
     $sql .= "      left  join cgm  on  cgm.z01_numcgm = veicmotoristas.ve05_numcgm";
     $sql .= "      inner join tfd_tipodistancia  on  tfd_tipodistancia.tf24_i_codigo = tfd_destino.tf03_i_tipodistancia";
     $sql2 = "";
     if($dbwhere==""){
       if($tf18_i_codigo!=null ){
         $sql2 .= " where tfd_veiculodestino.tf18_i_codigo = $tf18_i_codigo ";
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
  public function sql_consulta_veiculos( $sDataSaida, $iDestino = null ) {

    $sFiltraDestino = "";
    if ( !empty($iDestino) ) {
      $sFiltraDestino = " and tf18_i_destino = {$iDestino} ";
    }

    $sSql  = " SELECT tf17_d_datasaida    as data_saida,                                                                                 ";
    $sSql .= "        tf17_c_horasaida    as hora_saida,                                                                                 ";
    $sSql .= "        ve01_placa          as placa,                                                                                      ";
    $sSql .= "        ve22_descr          as modelo,                                                                                     ";
    $sSql .= "        ve01_quantcapacidad as vagas,                                                                                      ";
    $sSql .= "        ve01_codigo,                                                                                                       ";
    $sSql .= "        array_to_string(array_accum( distinct tf03_c_descr), ' / ') as destino,                                             ";
    $sSql .= "        ( SELECT count(*)                                                                                                  ";
    $sSql .= "            from tfd_pedidotfd                                                                                             ";
    $sSql .= "           inner join tfd_passageiroveiculo as pv  on pv.tf19_i_pedidotfd  = tfd_pedidotfd.tf01_i_codigo                   ";
    $sSql .= "           inner join tfd_veiculodestino    as vd  on vd.tf18_i_codigo     =  pv.tf19_i_veiculodestino                     ";
    $sSql .= "           inner join tfd_agendasaida       as age on age.tf17_i_pedidotfd = tfd_pedidotfd.tf01_i_codigo                   ";
    $sSql .= "           where age.tf17_d_datasaida = '{$sDataSaida}'                                                                    ";
    $sSql .= "                 {$sFiltraDestino}                                                                                         ";
    $sSql .= "             and vd.tf18_i_veiculo    = ve01_codigo                                                                        ";
    $sSql .= "        ) as passageiros,                                                                                                  ";
    $sSql .= "        ( SELECT z01_nome                                                                                                  ";
    $sSql .= "            from tfd_pedidotfd                                                                                             ";
    $sSql .= "           inner join tfd_passageiroveiculo as pv  on pv.tf19_i_pedidotfd  = tfd_pedidotfd.tf01_i_codigo                   ";
    $sSql .= "           inner join tfd_veiculodestino    as vd  on vd.tf18_i_codigo     =  pv.tf19_i_veiculodestino                     ";
    $sSql .= "           inner join tfd_agendasaida       as age on age.tf17_i_pedidotfd = tfd_pedidotfd.tf01_i_codigo                   ";
    $sSql .= "           left  join veicmotoristas          on veicmotoristas.ve05_codigo             = vd.tf18_i_motorista              ";
    $sSql .= "           left  join cgm                     on cgm.z01_numcgm                         = ve05_numcgm                      ";
    $sSql .= "           where age.tf17_d_datasaida = '{$sDataSaida}'                                                                    ";
    $sSql .= "                 {$sFiltraDestino}                                                                                         ";
    $sSql .= "            and vd.tf18_i_veiculo     = ve01_codigo                                                                        ";
    $sSql .= "           limit 1                                                                                                         ";
    $sSql .= "         ) as motorista                                                                                                    ";
    $sSql .= "   from tfd_pedidotfd                                                                                                      ";
    $sSql .= "  inner join tfd_agendasaida       on tfd_agendasaida.tf17_i_pedidotfd       = tfd_pedidotfd.tf01_i_codigo                 ";
    $sSql .= "  inner join tfd_passageiroveiculo on tfd_passageiroveiculo.tf19_i_pedidotfd = tfd_pedidotfd.tf01_i_codigo                 ";
    $sSql .= "  inner join tfd_veiculodestino    on tfd_veiculodestino.tf18_i_codigo       = tfd_passageiroveiculo.tf19_i_veiculodestino ";
    $sSql .= "  inner join tfd_destino           on tfd_destino.tf03_i_codigo              = tfd_veiculodestino.tf18_i_destino           ";
    $sSql .= "  left  join veicmotoristas        on veicmotoristas.ve05_codigo             = tfd_veiculodestino.tf18_i_motorista         ";
    $sSql .= "  inner join veiculos              on veiculos.ve01_codigo                   = tfd_veiculodestino.tf18_i_veiculo           ";
    $sSql .= "  inner join veiccadmodelo         on veiccadmodelo.ve22_codigo              = veiculos.ve01_veiccadmodelo                 ";
    $sSql .= "  where tf17_d_datasaida = '{$sDataSaida}'                                                                                 ";
    $sSql .= "        {$sFiltraDestino}                                                                                                  ";
    $sSql .= " group by tf17_d_datasaida, tf17_c_horasaida, ve01_placa, ve22_descr, ve01_quantcapacidad, ve01_codigo                     ";

    return $sSql;
  }

}
