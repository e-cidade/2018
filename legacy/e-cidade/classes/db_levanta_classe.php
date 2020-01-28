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

//MODULO: Fiscal
//CLASSE DA ENTIDADE levanta
class cl_levanta {
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
   var $y60_codlev = 0;
   var $y60_data_dia = null;
   var $y60_data_mes = null;
   var $y60_data_ano = null;
   var $y60_data = null;
   var $y60_contato = null;
   var $y60_dtini_dia = null;
   var $y60_dtini_mes = null;
   var $y60_dtini_ano = null;
   var $y60_dtini = null;
   var $y60_dtfim_dia = null;
   var $y60_dtfim_mes = null;
   var $y60_dtfim_ano = null;
   var $y60_dtfim = null;
   var $y60_obs = null;
   var $y60_importado = 'f';
   var $y60_proces = 0;
   var $y60_espontaneo = 'f';
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 y60_codlev = int4 = Levantamento
                 y60_data = date = Data do Levantamento
                 y60_contato = varchar(50) = Contato
                 y60_dtini = date = Período de Início do Levantamento
                 y60_dtfim = date = Período Final do Levantamento
                 y60_obs = text = Observação do Levantamento
                 y60_importado = bool = Exportado
                 y60_proces = int4 = codigo do processo
                 y60_espontaneo = bool = Levantamento Espontâneo
                 ";
   //funcao construtor da classe
   function cl_levanta() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("levanta");
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
       $this->y60_codlev = ($this->y60_codlev == ""?@$GLOBALS["HTTP_POST_VARS"]["y60_codlev"]:$this->y60_codlev);
       if($this->y60_data == ""){
         $this->y60_data_dia = ($this->y60_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["y60_data_dia"]:$this->y60_data_dia);
         $this->y60_data_mes = ($this->y60_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["y60_data_mes"]:$this->y60_data_mes);
         $this->y60_data_ano = ($this->y60_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["y60_data_ano"]:$this->y60_data_ano);
         if($this->y60_data_dia != ""){
            $this->y60_data = $this->y60_data_ano."-".$this->y60_data_mes."-".$this->y60_data_dia;
         }
       }
       $this->y60_contato = ($this->y60_contato == ""?@$GLOBALS["HTTP_POST_VARS"]["y60_contato"]:$this->y60_contato);
       if($this->y60_dtini == ""){
         $this->y60_dtini_dia = ($this->y60_dtini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["y60_dtini_dia"]:$this->y60_dtini_dia);
         $this->y60_dtini_mes = ($this->y60_dtini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["y60_dtini_mes"]:$this->y60_dtini_mes);
         $this->y60_dtini_ano = ($this->y60_dtini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["y60_dtini_ano"]:$this->y60_dtini_ano);
         if($this->y60_dtini_dia != ""){
            $this->y60_dtini = $this->y60_dtini_ano."-".$this->y60_dtini_mes."-".$this->y60_dtini_dia;
         }
       }
       if($this->y60_dtfim == ""){
         $this->y60_dtfim_dia = ($this->y60_dtfim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["y60_dtfim_dia"]:$this->y60_dtfim_dia);
         $this->y60_dtfim_mes = ($this->y60_dtfim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["y60_dtfim_mes"]:$this->y60_dtfim_mes);
         $this->y60_dtfim_ano = ($this->y60_dtfim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["y60_dtfim_ano"]:$this->y60_dtfim_ano);
         if($this->y60_dtfim_dia != ""){
            $this->y60_dtfim = $this->y60_dtfim_ano."-".$this->y60_dtfim_mes."-".$this->y60_dtfim_dia;
         }
       }
       $this->y60_obs = ($this->y60_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["y60_obs"]:$this->y60_obs);
       $this->y60_importado = ($this->y60_importado == "f"?@$GLOBALS["HTTP_POST_VARS"]["y60_importado"]:$this->y60_importado);
       $this->y60_proces = ($this->y60_proces == ""?@$GLOBALS["HTTP_POST_VARS"]["y60_proces"]:$this->y60_proces);
       $this->y60_espontaneo = ($this->y60_espontaneo == "f"?@$GLOBALS["HTTP_POST_VARS"]["y60_espontaneo"]:$this->y60_espontaneo);
     }else{
       $this->y60_codlev = ($this->y60_codlev == ""?@$GLOBALS["HTTP_POST_VARS"]["y60_codlev"]:$this->y60_codlev);
     }
   }
   // funcao para inclusao
   function incluir ($y60_codlev){
      $this->atualizacampos();
     if($this->y60_data == null ){
       $this->erro_sql = " Campo Data do Levantamento nao Informado.";
       $this->erro_campo = "y60_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y60_contato == null ){
       $this->erro_sql = " Campo Contato nao Informado.";
       $this->erro_campo = "y60_contato";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y60_dtini == null ){
       $this->erro_sql = " Campo Período de Início do Levantamento nao Informado.";
       $this->erro_campo = "y60_dtini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y60_dtfim == null ){
       $this->erro_sql = " Campo Período Final do Levantamento nao Informado.";
       $this->erro_campo = "y60_dtfim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y60_importado == null ){
       $this->y60_importado = "f";
     }
     if($this->y60_proces == null ){
       $this->y60_proces = "null";
     }
     if($this->y60_espontaneo == null ){
       $this->erro_sql = " Campo Levantamento Espontâneo nao Informado.";
       $this->erro_campo = "y60_espontaneo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($y60_codlev == "" || $y60_codlev == null ){
       $result = db_query("select nextval('levanta_y60_codlev_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: levanta_y60_codlev_seq do campo: y60_codlev";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->y60_codlev = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from levanta_y60_codlev_seq");
       if(($result != false) && (pg_result($result,0,0) < $y60_codlev)){
         $this->erro_sql = " Campo y60_codlev maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->y60_codlev = $y60_codlev;
       }
     }
     if(($this->y60_codlev == null) || ($this->y60_codlev == "") ){
       $this->erro_sql = " Campo y60_codlev nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into levanta(
                                       y60_codlev
                                      ,y60_data
                                      ,y60_contato
                                      ,y60_dtini
                                      ,y60_dtfim
                                      ,y60_obs
                                      ,y60_importado
                                      ,y60_proces
                                      ,y60_espontaneo
                       )
                values (
                                $this->y60_codlev
                               ,".($this->y60_data == "null" || $this->y60_data == ""?"null":"'".$this->y60_data."'")."
                               ,'$this->y60_contato'
                               ,".($this->y60_dtini == "null" || $this->y60_dtini == ""?"null":"'".$this->y60_dtini."'")."
                               ,".($this->y60_dtfim == "null" || $this->y60_dtfim == ""?"null":"'".$this->y60_dtfim."'")."
                               ,'$this->y60_obs'
                               ,'$this->y60_importado'
                               ,$this->y60_proces
                               ,'$this->y60_espontaneo'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "levanta ($this->y60_codlev) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "levanta já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "levanta ($this->y60_codlev) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y60_codlev;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->y60_codlev));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5014,'$this->y60_codlev','I')");
       $resac = db_query("insert into db_acount values($acount,709,5014,'','".AddSlashes(pg_result($resaco,0,'y60_codlev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,709,5015,'','".AddSlashes(pg_result($resaco,0,'y60_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,709,5016,'','".AddSlashes(pg_result($resaco,0,'y60_contato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,709,5017,'','".AddSlashes(pg_result($resaco,0,'y60_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,709,5018,'','".AddSlashes(pg_result($resaco,0,'y60_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,709,5019,'','".AddSlashes(pg_result($resaco,0,'y60_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,709,6537,'','".AddSlashes(pg_result($resaco,0,'y60_importado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,709,6551,'','".AddSlashes(pg_result($resaco,0,'y60_proces'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,709,7781,'','".AddSlashes(pg_result($resaco,0,'y60_espontaneo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($y60_codlev=null) {
      $this->atualizacampos();
     $sql = " update levanta set ";
     $virgula = "";
     if(trim($this->y60_codlev)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y60_codlev"])){
       $sql  .= $virgula." y60_codlev = $this->y60_codlev ";
       $virgula = ",";
       if(trim($this->y60_codlev) == null ){
         $this->erro_sql = " Campo Levantamento nao Informado.";
         $this->erro_campo = "y60_codlev";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y60_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y60_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["y60_data_dia"] !="") ){
       $sql  .= $virgula." y60_data = '$this->y60_data' ";
       $virgula = ",";
       if(trim($this->y60_data) == null ){
         $this->erro_sql = " Campo Data do Levantamento nao Informado.";
         $this->erro_campo = "y60_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["y60_data_dia"])){
         $sql  .= $virgula." y60_data = null ";
         $virgula = ",";
         if(trim($this->y60_data) == null ){
           $this->erro_sql = " Campo Data do Levantamento nao Informado.";
           $this->erro_campo = "y60_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->y60_contato)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y60_contato"])){
       $sql  .= $virgula." y60_contato = '$this->y60_contato' ";
       $virgula = ",";
       if(trim($this->y60_contato) == null ){
         $this->erro_sql = " Campo Contato nao Informado.";
         $this->erro_campo = "y60_contato";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y60_dtini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y60_dtini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["y60_dtini_dia"] !="") ){
       $sql  .= $virgula." y60_dtini = '$this->y60_dtini' ";
       $virgula = ",";
       if(trim($this->y60_dtini) == null ){
         $this->erro_sql = " Campo Período de Início do Levantamento nao Informado.";
         $this->erro_campo = "y60_dtini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["y60_dtini_dia"])){
         $sql  .= $virgula." y60_dtini = null ";
         $virgula = ",";
         if(trim($this->y60_dtini) == null ){
           $this->erro_sql = " Campo Período de Início do Levantamento nao Informado.";
           $this->erro_campo = "y60_dtini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->y60_dtfim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y60_dtfim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["y60_dtfim_dia"] !="") ){
       $sql  .= $virgula." y60_dtfim = '$this->y60_dtfim' ";
       $virgula = ",";
       if(trim($this->y60_dtfim) == null ){
         $this->erro_sql = " Campo Período Final do Levantamento nao Informado.";
         $this->erro_campo = "y60_dtfim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["y60_dtfim_dia"])){
         $sql  .= $virgula." y60_dtfim = null ";
         $virgula = ",";
         if(trim($this->y60_dtfim) == null ){
           $this->erro_sql = " Campo Período Final do Levantamento nao Informado.";
           $this->erro_campo = "y60_dtfim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->y60_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y60_obs"])){
       $sql  .= $virgula." y60_obs = '$this->y60_obs' ";
       $virgula = ",";
     }
     if(trim($this->y60_importado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y60_importado"])){
       $sql  .= $virgula." y60_importado = '$this->y60_importado' ";
       $virgula = ",";
     }
     if(trim($this->y60_proces)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y60_proces"])){
        if(trim($this->y60_proces)=="" && isset($GLOBALS["HTTP_POST_VARS"]["y60_proces"])){
           $this->y60_proces = "null" ;
        }
       $sql  .= $virgula." y60_proces = $this->y60_proces ";
       $virgula = ",";
     }
     if(trim($this->y60_espontaneo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y60_espontaneo"])){
       $sql  .= $virgula." y60_espontaneo = '$this->y60_espontaneo' ";
       $virgula = ",";
       if(trim($this->y60_espontaneo) == null ){
         $this->erro_sql = " Campo Levantamento Espontâneo nao Informado.";
         $this->erro_campo = "y60_espontaneo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($y60_codlev!=null){
       $sql .= " y60_codlev = $this->y60_codlev";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->y60_codlev));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5014,'$this->y60_codlev','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y60_codlev"]))
           $resac = db_query("insert into db_acount values($acount,709,5014,'".AddSlashes(pg_result($resaco,$conresaco,'y60_codlev'))."','$this->y60_codlev',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y60_data"]))
           $resac = db_query("insert into db_acount values($acount,709,5015,'".AddSlashes(pg_result($resaco,$conresaco,'y60_data'))."','$this->y60_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y60_contato"]))
           $resac = db_query("insert into db_acount values($acount,709,5016,'".AddSlashes(pg_result($resaco,$conresaco,'y60_contato'))."','$this->y60_contato',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y60_dtini"]))
           $resac = db_query("insert into db_acount values($acount,709,5017,'".AddSlashes(pg_result($resaco,$conresaco,'y60_dtini'))."','$this->y60_dtini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y60_dtfim"]))
           $resac = db_query("insert into db_acount values($acount,709,5018,'".AddSlashes(pg_result($resaco,$conresaco,'y60_dtfim'))."','$this->y60_dtfim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y60_obs"]))
           $resac = db_query("insert into db_acount values($acount,709,5019,'".AddSlashes(pg_result($resaco,$conresaco,'y60_obs'))."','$this->y60_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y60_importado"]))
           $resac = db_query("insert into db_acount values($acount,709,6537,'".AddSlashes(pg_result($resaco,$conresaco,'y60_importado'))."','$this->y60_importado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y60_proces"]))
           $resac = db_query("insert into db_acount values($acount,709,6551,'".AddSlashes(pg_result($resaco,$conresaco,'y60_proces'))."','$this->y60_proces',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y60_espontaneo"]))
           $resac = db_query("insert into db_acount values($acount,709,7781,'".AddSlashes(pg_result($resaco,$conresaco,'y60_espontaneo'))."','$this->y60_espontaneo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "levanta nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->y60_codlev;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "levanta nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->y60_codlev;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y60_codlev;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($y60_codlev=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($y60_codlev));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5014,'$y60_codlev','E')");
         $resac = db_query("insert into db_acount values($acount,709,5014,'','".AddSlashes(pg_result($resaco,$iresaco,'y60_codlev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,709,5015,'','".AddSlashes(pg_result($resaco,$iresaco,'y60_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,709,5016,'','".AddSlashes(pg_result($resaco,$iresaco,'y60_contato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,709,5017,'','".AddSlashes(pg_result($resaco,$iresaco,'y60_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,709,5018,'','".AddSlashes(pg_result($resaco,$iresaco,'y60_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,709,5019,'','".AddSlashes(pg_result($resaco,$iresaco,'y60_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,709,6537,'','".AddSlashes(pg_result($resaco,$iresaco,'y60_importado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,709,6551,'','".AddSlashes(pg_result($resaco,$iresaco,'y60_proces'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,709,7781,'','".AddSlashes(pg_result($resaco,$iresaco,'y60_espontaneo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from levanta
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($y60_codlev != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y60_codlev = $y60_codlev ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "levanta nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$y60_codlev;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "levanta nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$y60_codlev;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$y60_codlev;
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
        $this->erro_sql   = "Record Vazio na Tabela:levanta";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $y60_codlev=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from levanta ";
     $sql .= "      left join protprocesso  on  protprocesso.p58_codproc = levanta.y60_proces";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = protprocesso.p58_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = protprocesso.p58_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = protprocesso.p58_id_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = protprocesso.p58_coddepto";
     $sql .= "      inner join tipoproc  on  tipoproc.p51_codigo = protprocesso.p58_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($y60_codlev!=null ){
         $sql2 .= " where levanta.y60_codlev = $y60_codlev ";
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
   function sql_query_file ( $y60_codlev=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from levanta ";
     $sql2 = "";
     if($dbwhere==""){
       if($y60_codlev!=null ){
         $sql2 .= " where levanta.y60_codlev = $y60_codlev ";
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
   function sql_query_inf ( $y60_codlev=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from levanta ";
     $sql .= "    left join levinscr on y60_codlev = y62_codlev ";
     $sql .= "    left join issbase  on y62_inscr  = q02_inscr ";
     $sql .= "    left join cgm as a on a.z01_numcgm = q02_numcgm ";
     $sql .= "    left join levcgm on y60_codlev = y93_codlev ";
     $sql .= "    left join cgm as b on b.z01_numcgm = y93_numcgm ";
     $sql2 = "";
     if($dbwhere==""){
       if($y60_codlev!=null ){
         $sql2 .= " where levanta.y60_codlev = $y60_codlev ";
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
  function sql_query_inf_numpre ( $y60_codlev=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from levanta ";
     $sql .= "    left join levinscr on y60_codlev = y62_codlev ";
     $sql .= "    left join issbase  on y62_inscr  = q02_inscr ";
     $sql .= "    left join cgm as a on a.z01_numcgm = q02_numcgm ";
     $sql .= "    left join levcgm on y60_codlev = y93_codlev ";
     $sql .= "    left join cgm as b on b.z01_numcgm = y93_numcgm ";
     $sql .= "    left join arreinscr on k00_inscr = y62_inscr ";
     $sql2 = "";
     if($dbwhere==""){
       if($y60_codlev!=null ){
         $sql2 .= " where levanta.y60_codlev = $y60_codlev ";
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

   function sql_query_inscr ( $y60_codlev=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from levanta ";
     $sql .= "    inner join levinscr on y60_codlev = y62_codlev ";
     $sql .= "    inner join issbase  on y62_inscr  = q02_inscr ";
     $sql .= "    inner join cgm      on z01_numcgm = q02_numcgm ";

     $sql2 = "";
     if($dbwhere==""){
       if($y60_codlev!=null ){
         $sql2 .= " where levanta.y60_codlev = $y60_codlev ";
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
   function sql_querylev( $y60_codlev=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from levanta                                                           ";
     $sql .= "    inner join levinscr   on y60_codlev          =  y62_codlev          ";
     $sql .= "    inner join levvalor   on y63_codlev          =  y60_codlev          ";
     $sql .= "    inner join issbase    on y62_inscr           =  q02_inscr           ";
     $sql .= "    inner join tabativ    on q07_inscr           =  q02_inscr           ";
     $sql .= "    inner join ativid     on q03_ativ            =  q07_ativ            ";
     $sql .= "    inner join ativprinc  on q88_inscr           =  q02_inscr           ";
     $sql .= "    inner join issruas    on issruas.q02_inscr   =  issbase.q02_inscr   ";
     $sql .= "    inner join ruas       on ruas.j14_codigo     =  issruas.j14_codigo  ";
     $sql .= "    inner join issbairro  on issbairro.q13_inscr =  issbase.q02_inscr";
     $sql .= "    inner join bairro     on bairro.j13_codi     =  issbairro.q13_bairro ";
     $sql .= "    inner join cgm        on z01_numcgm          =  issbase.q02_numcgm ";

     $sql2 = "";
     if($dbwhere==""){
       if($y60_codlev!=null ){
         $sql2 .= " where levanta.y60_codlev = $y60_codlev ";
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
   function sql_query_pesquisa ( $y60_codlev=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
         $campos='';
         $campos.="y60_codlev,";
         $campos.= "
             case when y62_inscr is null then 'CGM'      else 'INSCRIÇÃO'  end
               as DBtxttipo_origem,
             case when y62_inscr is null then y93_numcgm else y62_inscr   end
               as DBtxtcod_origem,
             case when y62_inscr is null then nome_cgm   else nome_empresa end
                 as DBtxtnome_origem,";
         $campos.= " y60_data,y60_importado";
     }
         $sql.=" $campos
                  from (
                    select
       levanta.*,y62_inscr, y93_numcgm,empresa.z01_nome as nome_empresa,cgm.z01_nome as nome_cgm
                       from
                           levanta
                             left join levinscr on y62_codlev = y60_codlev
                             left join levcgm   on y93_codlev = y60_codlev
                             left join issbase  on y62_inscr  = q02_inscr
                             left join cgm empresa on q02_numcgm = empresa.z01_numcgm
                             left join cgm on y93_numcgm = cgm.z01_numcgm
                      ) as x

     ";
     $sql2 = "";
     if($dbwhere==""){
       if($y60_codlev!=null ){
         $sql2 .= " where levanta.y60_codlev = $y60_codlev ";
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