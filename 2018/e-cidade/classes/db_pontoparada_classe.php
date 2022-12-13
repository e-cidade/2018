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

//MODULO: transporteescolar
//CLASSE DA ENTIDADE pontoparada
class cl_pontoparada {
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
   var $tre04_sequencial = 0;
   var $tre04_cadenderbairrocadenderrua = 0;
   var $tre04_nome = null;
   var $tre04_abreviatura = null;
   var $tre04_pontoreferencia = null;
   var $tre04_latitude = 0;
   var $tre04_longitude = 0;
   var $tre04_tipo = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 tre04_sequencial = int4 = Sequencial
                 tre04_cadenderbairrocadenderrua = int4 = Sequencial
                 tre04_nome = varchar(70) = Nome
                 tre04_abreviatura = varchar(10) = Abreviatura
                 tre04_pontoreferencia = text = Ponto de Referência
                 tre04_latitude = numeric(23) = Latitude
                 tre04_longitude = numeric(23) = Longitude
                 tre04_tipo = int4 = Tipo
                 ";
   //funcao construtor da classe
   function cl_pontoparada() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pontoparada");
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
       $this->tre04_sequencial = ($this->tre04_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["tre04_sequencial"]:$this->tre04_sequencial);
       $this->tre04_cadenderbairrocadenderrua = ($this->tre04_cadenderbairrocadenderrua == ""?@$GLOBALS["HTTP_POST_VARS"]["tre04_cadenderbairrocadenderrua"]:$this->tre04_cadenderbairrocadenderrua);
       $this->tre04_nome = ($this->tre04_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["tre04_nome"]:$this->tre04_nome);
       $this->tre04_abreviatura = ($this->tre04_abreviatura == ""?@$GLOBALS["HTTP_POST_VARS"]["tre04_abreviatura"]:$this->tre04_abreviatura);
       $this->tre04_pontoreferencia = ($this->tre04_pontoreferencia == ""?@$GLOBALS["HTTP_POST_VARS"]["tre04_pontoreferencia"]:$this->tre04_pontoreferencia);
       $this->tre04_latitude = ($this->tre04_latitude == ""?@$GLOBALS["HTTP_POST_VARS"]["tre04_latitude"]:$this->tre04_latitude);
       $this->tre04_longitude = ($this->tre04_longitude == ""?@$GLOBALS["HTTP_POST_VARS"]["tre04_longitude"]:$this->tre04_longitude);
       $this->tre04_tipo = ($this->tre04_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["tre04_tipo"]:$this->tre04_tipo);
     }else{
       $this->tre04_sequencial = ($this->tre04_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["tre04_sequencial"]:$this->tre04_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($tre04_sequencial){
      $this->atualizacampos();
     if($this->tre04_cadenderbairrocadenderrua == null ){
       $this->erro_sql = " Campo Sequencial nao Informado.";
       $this->erro_campo = "tre04_cadenderbairrocadenderrua";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tre04_nome == null ){
       $this->erro_sql = " Campo Nome nao Informado.";
       $this->erro_campo = "tre04_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tre04_abreviatura == null ){
       $this->erro_sql = " Campo Abreviatura nao Informado.";
       $this->erro_campo = "tre04_abreviatura";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tre04_latitude == null ){
       $this->tre04_latitude = "null";
     }
     if($this->tre04_longitude == null ){
       $this->tre04_longitude = "null";
     }
     if($this->tre04_tipo == null ){
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "tre04_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($tre04_sequencial == "" || $tre04_sequencial == null ){
       $result = db_query("select nextval('pontoparada_tre04_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: pontoparada_tre04_sequencial_seq do campo: tre04_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->tre04_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from pontoparada_tre04_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $tre04_sequencial)){
         $this->erro_sql = " Campo tre04_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->tre04_sequencial = $tre04_sequencial;
       }
     }
     if(($this->tre04_sequencial == null) || ($this->tre04_sequencial == "") ){
       $this->erro_sql = " Campo tre04_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pontoparada(
                                       tre04_sequencial
                                      ,tre04_cadenderbairrocadenderrua
                                      ,tre04_nome
                                      ,tre04_abreviatura
                                      ,tre04_pontoreferencia
                                      ,tre04_latitude
                                      ,tre04_longitude
                                      ,tre04_tipo
                       )
                values (
                                $this->tre04_sequencial
                               ,$this->tre04_cadenderbairrocadenderrua
                               ,'$this->tre04_nome'
                               ,'$this->tre04_abreviatura'
                               ,'$this->tre04_pontoreferencia'
                               ,$this->tre04_latitude
                               ,$this->tre04_longitude
                               ,$this->tre04_tipo
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ponto de parada ($this->tre04_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ponto de parada já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ponto de parada ($this->tre04_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tre04_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->tre04_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20082,'$this->tre04_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3601,20082,'','".AddSlashes(pg_result($resaco,0,'tre04_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3601,20083,'','".AddSlashes(pg_result($resaco,0,'tre04_cadenderbairrocadenderrua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3601,20084,'','".AddSlashes(pg_result($resaco,0,'tre04_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3601,20085,'','".AddSlashes(pg_result($resaco,0,'tre04_abreviatura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3601,20086,'','".AddSlashes(pg_result($resaco,0,'tre04_pontoreferencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3601,20087,'','".AddSlashes(pg_result($resaco,0,'tre04_latitude'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3601,20088,'','".AddSlashes(pg_result($resaco,0,'tre04_longitude'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3601,20089,'','".AddSlashes(pg_result($resaco,0,'tre04_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($tre04_sequencial=null) {
      $this->atualizacampos();
     $sql = " update pontoparada set ";
     $virgula = "";
     if(trim($this->tre04_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tre04_sequencial"])){
       $sql  .= $virgula." tre04_sequencial = $this->tre04_sequencial ";
       $virgula = ",";
       if(trim($this->tre04_sequencial) == null ){
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "tre04_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tre04_cadenderbairrocadenderrua)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tre04_cadenderbairrocadenderrua"])){
       $sql  .= $virgula." tre04_cadenderbairrocadenderrua = $this->tre04_cadenderbairrocadenderrua ";
       $virgula = ",";
       if(trim($this->tre04_cadenderbairrocadenderrua) == null ){
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "tre04_cadenderbairrocadenderrua";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tre04_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tre04_nome"])){
       $sql  .= $virgula." tre04_nome = '$this->tre04_nome' ";
       $virgula = ",";
       if(trim($this->tre04_nome) == null ){
         $this->erro_sql = " Campo Nome nao Informado.";
         $this->erro_campo = "tre04_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tre04_abreviatura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tre04_abreviatura"])){
       $sql  .= $virgula." tre04_abreviatura = '$this->tre04_abreviatura' ";
       $virgula = ",";
       if(trim($this->tre04_abreviatura) == null ){
         $this->erro_sql = " Campo Abreviatura nao Informado.";
         $this->erro_campo = "tre04_abreviatura";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tre04_pontoreferencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tre04_pontoreferencia"])){
       $sql  .= $virgula." tre04_pontoreferencia = '$this->tre04_pontoreferencia' ";
       $virgula = ",";
     }
     if(trim($this->tre04_latitude)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tre04_latitude"])){
       $sql  .= $virgula." tre04_latitude = $this->tre04_latitude ";
       $virgula = ",";
     }
     if(trim($this->tre04_longitude)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tre04_longitude"])){
       $sql  .= $virgula." tre04_longitude = $this->tre04_longitude ";
       $virgula = ",";
     }
     if(trim($this->tre04_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tre04_tipo"])){
       $sql  .= $virgula." tre04_tipo = $this->tre04_tipo ";
       $virgula = ",";
       if(trim($this->tre04_tipo) == null ){
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "tre04_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($tre04_sequencial!=null){
       $sql .= " tre04_sequencial = $this->tre04_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->tre04_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20082,'$this->tre04_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tre04_sequencial"]) || $this->tre04_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3601,20082,'".AddSlashes(pg_result($resaco,$conresaco,'tre04_sequencial'))."','$this->tre04_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tre04_cadenderbairrocadenderrua"]) || $this->tre04_cadenderbairrocadenderrua != "")
             $resac = db_query("insert into db_acount values($acount,3601,20083,'".AddSlashes(pg_result($resaco,$conresaco,'tre04_cadenderbairrocadenderrua'))."','$this->tre04_cadenderbairrocadenderrua',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tre04_nome"]) || $this->tre04_nome != "")
             $resac = db_query("insert into db_acount values($acount,3601,20084,'".AddSlashes(pg_result($resaco,$conresaco,'tre04_nome'))."','$this->tre04_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tre04_abreviatura"]) || $this->tre04_abreviatura != "")
             $resac = db_query("insert into db_acount values($acount,3601,20085,'".AddSlashes(pg_result($resaco,$conresaco,'tre04_abreviatura'))."','$this->tre04_abreviatura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tre04_pontoreferencia"]) || $this->tre04_pontoreferencia != "")
             $resac = db_query("insert into db_acount values($acount,3601,20086,'".AddSlashes(pg_result($resaco,$conresaco,'tre04_pontoreferencia'))."','$this->tre04_pontoreferencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tre04_latitude"]) || $this->tre04_latitude != "")
             $resac = db_query("insert into db_acount values($acount,3601,20087,'".AddSlashes(pg_result($resaco,$conresaco,'tre04_latitude'))."','$this->tre04_latitude',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tre04_longitude"]) || $this->tre04_longitude != "")
             $resac = db_query("insert into db_acount values($acount,3601,20088,'".AddSlashes(pg_result($resaco,$conresaco,'tre04_longitude'))."','$this->tre04_longitude',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tre04_tipo"]) || $this->tre04_tipo != "")
             $resac = db_query("insert into db_acount values($acount,3601,20089,'".AddSlashes(pg_result($resaco,$conresaco,'tre04_tipo'))."','$this->tre04_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ponto de parada nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->tre04_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ponto de parada nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->tre04_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tre04_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($tre04_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($tre04_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20082,'$tre04_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3601,20082,'','".AddSlashes(pg_result($resaco,$iresaco,'tre04_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3601,20083,'','".AddSlashes(pg_result($resaco,$iresaco,'tre04_cadenderbairrocadenderrua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3601,20084,'','".AddSlashes(pg_result($resaco,$iresaco,'tre04_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3601,20085,'','".AddSlashes(pg_result($resaco,$iresaco,'tre04_abreviatura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3601,20086,'','".AddSlashes(pg_result($resaco,$iresaco,'tre04_pontoreferencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3601,20087,'','".AddSlashes(pg_result($resaco,$iresaco,'tre04_latitude'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3601,20088,'','".AddSlashes(pg_result($resaco,$iresaco,'tre04_longitude'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3601,20089,'','".AddSlashes(pg_result($resaco,$iresaco,'tre04_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from pontoparada
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($tre04_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " tre04_sequencial = $tre04_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ponto de parada nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$tre04_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ponto de parada nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$tre04_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$tre04_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:pontoparada";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $tre04_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pontoparada ";
     $sql .= "      inner join cadenderbairrocadenderrua  on  cadenderbairrocadenderrua.db87_sequencial = pontoparada.tre04_cadenderbairrocadenderrua";
     $sql .= "      inner join cadenderbairro  on  cadenderbairro.db73_sequencial = cadenderbairrocadenderrua.db87_cadenderbairro";
     $sql .= "      inner join cadenderrua  on  cadenderrua.db74_sequencial = cadenderbairrocadenderrua.db87_cadenderrua";
     $sql2 = "";
     if($dbwhere==""){
       if($tre04_sequencial!=null ){
         $sql2 .= " where pontoparada.tre04_sequencial = $tre04_sequencial ";
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
   function sql_query_file ( $tre04_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pontoparada ";
     $sql2 = "";
     if($dbwhere==""){
       if($tre04_sequencial!=null ){
         $sql2 .= " where pontoparada.tre04_sequencial = $tre04_sequencial ";
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
  function sql_query_departamento ( $tre04_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from pontoparada ";
    $sql .= "      left join pontoparadadepartamento on tre05_pontoparada = tre04_sequencial ";
    $sql .= "      left join pontoparadaescolaproc   on tre13_pontoparada = tre04_sequencial ";
    $sql2 = "";
    if($dbwhere==""){
      if($tre04_sequencial!=null ){
        $sql2 .= " where pontoparada.tre04_sequencial = $tre04_sequencial ";
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
  function sql_query_ponto_parada_linha_transporte ( $tre04_sequencial=null, $campos="*", $ordem=null, $dbwhere=""){
    $sql = "select ";

    if ($campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula = "";

      for ($i=0;$i<sizeof($campos_sql);$i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }

    $sql .= " from pontoparada ";
    $sql .= "      inner join linhatransportepontoparada      on tre11_pontoparada = tre04_sequencial";
    $sql .= "      inner join itinerariologradouro            on tre10_sequencial  = tre11_itinerariologradouro";
    $sql .= "      inner join linhatransporteitinerario       on tre09_sequencial  = tre10_linhatransporteitinerario";
    $sql .= "      inner join linhatransporte                 on tre06_sequencial  = tre09_linhatransporte";
    $sql2 = "";

    if ($dbwhere=="") {

      if ($tre04_sequencial!=null ) {
        $sql2 .= " where pontoparada.tre04_sequencial = $tre04_sequencial ";
      }

    } else if($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }

    $sql .= $sql2;

    if ($ordem != null ) {

      $sql        .= " order by ";
      $campos_sql  = split("#",$ordem);
      $virgula     = "";

      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    }
    return $sql;
  }
}
?>