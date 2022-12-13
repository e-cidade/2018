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
//CLASSE DA ENTIDADE recibounica
class cl_recibounica {
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
   var $k00_numpre = 0;
   var $k00_dtvenc_dia = null;
   var $k00_dtvenc_mes = null;
   var $k00_dtvenc_ano = null;
   var $k00_dtvenc = null;
   var $k00_dtoper_dia = null;
   var $k00_dtoper_mes = null;
   var $k00_dtoper_ano = null;
   var $k00_dtoper = null;
   var $k00_percdes = 0;
   var $k00_tipoger = null;
   var $k00_recibounicageracao = 0;
   var $k00_sequencial = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 k00_numpre = int4 = Numpre
                 k00_dtvenc = date = DT.Venc
                 k00_dtoper = date = DT.Lanc
                 k00_percdes = float8 = Percentual de Desconto
                 k00_tipoger = char(1) = Tipo de geracao da parcela unica
                 k00_recibounicageracao = int4 = recibounicageração
                 k00_sequencial = int4 = Código da arrecadação suspensa
                 ";
   //funcao construtor da classe
   function cl_recibounica() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("recibounica");
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
       $this->k00_numpre = ($this->k00_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_numpre"]:$this->k00_numpre);
       if($this->k00_dtvenc == ""){
         $this->k00_dtvenc_dia = ($this->k00_dtvenc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtvenc_dia"]:$this->k00_dtvenc_dia);
         $this->k00_dtvenc_mes = ($this->k00_dtvenc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtvenc_mes"]:$this->k00_dtvenc_mes);
         $this->k00_dtvenc_ano = ($this->k00_dtvenc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtvenc_ano"]:$this->k00_dtvenc_ano);
         if($this->k00_dtvenc_dia != ""){
            $this->k00_dtvenc = $this->k00_dtvenc_ano."-".$this->k00_dtvenc_mes."-".$this->k00_dtvenc_dia;
         }
       }
       if($this->k00_dtoper == ""){
         $this->k00_dtoper_dia = ($this->k00_dtoper_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtoper_dia"]:$this->k00_dtoper_dia);
         $this->k00_dtoper_mes = ($this->k00_dtoper_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtoper_mes"]:$this->k00_dtoper_mes);
         $this->k00_dtoper_ano = ($this->k00_dtoper_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtoper_ano"]:$this->k00_dtoper_ano);
         if($this->k00_dtoper_dia != ""){
            $this->k00_dtoper = $this->k00_dtoper_ano."-".$this->k00_dtoper_mes."-".$this->k00_dtoper_dia;
         }
       }
       $this->k00_percdes = ($this->k00_percdes == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_percdes"]:$this->k00_percdes);
       $this->k00_tipoger = ($this->k00_tipoger == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_tipoger"]:$this->k00_tipoger);
       $this->k00_recibounicageracao = ($this->k00_recibounicageracao == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_recibounicageracao"]:$this->k00_recibounicageracao);
       $this->k00_sequencial = ($this->k00_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_sequencial"]:$this->k00_sequencial);
     }else{
       $this->k00_sequencial = ($this->k00_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_sequencial"]:$this->k00_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k00_sequencial){
      $this->atualizacampos();
     if($this->k00_numpre == null ){
       $this->erro_sql = " Campo Numpre nao Informado.";
       $this->erro_campo = "k00_numpre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_dtvenc == null ){
       $this->erro_sql = " Campo DT.Venc nao Informado.";
       $this->erro_campo = "k00_dtvenc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_dtoper == null ){
       $this->erro_sql = " Campo DT.Lanc nao Informado.";
       $this->erro_campo = "k00_dtoper_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_percdes == null ){
       $this->erro_sql = " Campo Percentual de Desconto nao Informado.";
       $this->erro_campo = "k00_percdes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_tipoger == null ){
       $this->erro_sql = " Campo Tipo de geracao da parcela unica nao Informado.";
       $this->erro_campo = "k00_tipoger";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_recibounicageracao == null ){
       $this->erro_sql = " Campo recibounicageração nao Informado.";
       $this->erro_campo = "k00_recibounicageracao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k00_sequencial == "" || $k00_sequencial == null ){
       $result = db_query("select nextval('recibounica_k00_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: recibounica_k00_sequencial_seq do campo: k00_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->k00_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from recibounica_k00_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k00_sequencial)){
         $this->erro_sql = " Campo k00_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k00_sequencial = $k00_sequencial;
       }
     }
     if(($this->k00_sequencial == null) || ($this->k00_sequencial == "") ){
       $this->erro_sql = " Campo k00_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into recibounica(
                                       k00_numpre
                                      ,k00_dtvenc
                                      ,k00_dtoper
                                      ,k00_percdes
                                      ,k00_tipoger
                                      ,k00_recibounicageracao
                                      ,k00_sequencial
                       )
                values (
                                $this->k00_numpre
                               ,".($this->k00_dtvenc == "null" || $this->k00_dtvenc == ""?"null":"'".$this->k00_dtvenc."'")."
                               ,".($this->k00_dtoper == "null" || $this->k00_dtoper == ""?"null":"'".$this->k00_dtoper."'")."
                               ,$this->k00_percdes
                               ,'$this->k00_tipoger'
                               ,$this->k00_recibounicageracao
                               ,$this->k00_sequencial
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Numpres em cota unica ($this->k00_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Numpres em cota unica já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Numpres em cota unica ($this->k00_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k00_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k00_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11816,'$this->k00_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1504,361,'','".AddSlashes(pg_result($resaco,0,'k00_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1504,377,'','".AddSlashes(pg_result($resaco,0,'k00_dtvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1504,373,'','".AddSlashes(pg_result($resaco,0,'k00_dtoper'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1504,8812,'','".AddSlashes(pg_result($resaco,0,'k00_percdes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1504,8065,'','".AddSlashes(pg_result($resaco,0,'k00_tipoger'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1504,18478,'','".AddSlashes(pg_result($resaco,0,'k00_recibounicageracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1504,11816,'','".AddSlashes(pg_result($resaco,0,'k00_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($k00_sequencial=null) {
      $this->atualizacampos();
     $sql = " update recibounica set ";
     $virgula = "";
     if(trim($this->k00_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_numpre"])){
       $sql  .= $virgula." k00_numpre = $this->k00_numpre ";
       $virgula = ",";
       if(trim($this->k00_numpre) == null ){
         $this->erro_sql = " Campo Numpre nao Informado.";
         $this->erro_campo = "k00_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_dtvenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_dtvenc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k00_dtvenc_dia"] !="") ){
       $sql  .= $virgula." k00_dtvenc = '$this->k00_dtvenc' ";
       $virgula = ",";
       if(trim($this->k00_dtvenc) == null ){
         $this->erro_sql = " Campo DT.Venc nao Informado.";
         $this->erro_campo = "k00_dtvenc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["k00_dtvenc_dia"])){
         $sql  .= $virgula." k00_dtvenc = null ";
         $virgula = ",";
         if(trim($this->k00_dtvenc) == null ){
           $this->erro_sql = " Campo DT.Venc nao Informado.";
           $this->erro_campo = "k00_dtvenc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k00_dtoper)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_dtoper_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k00_dtoper_dia"] !="") ){
       $sql  .= $virgula." k00_dtoper = '$this->k00_dtoper' ";
       $virgula = ",";
       if(trim($this->k00_dtoper) == null ){
         $this->erro_sql = " Campo DT.Lanc nao Informado.";
         $this->erro_campo = "k00_dtoper_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["k00_dtoper_dia"])){
         $sql  .= $virgula." k00_dtoper = null ";
         $virgula = ",";
         if(trim($this->k00_dtoper) == null ){
           $this->erro_sql = " Campo DT.Lanc nao Informado.";
           $this->erro_campo = "k00_dtoper_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k00_percdes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_percdes"])){
       $sql  .= $virgula." k00_percdes = $this->k00_percdes ";
       $virgula = ",";
       if(trim($this->k00_percdes) == null ){
         $this->erro_sql = " Campo Percentual de Desconto nao Informado.";
         $this->erro_campo = "k00_percdes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_tipoger)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_tipoger"])){
       $sql  .= $virgula." k00_tipoger = '$this->k00_tipoger' ";
       $virgula = ",";
       if(trim($this->k00_tipoger) == null ){
         $this->erro_sql = " Campo Tipo de geracao da parcela unica nao Informado.";
         $this->erro_campo = "k00_tipoger";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_recibounicageracao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_recibounicageracao"])){
       $sql  .= $virgula." k00_recibounicageracao = $this->k00_recibounicageracao ";
       $virgula = ",";
       if(trim($this->k00_recibounicageracao) == null ){
         $this->erro_sql = " Campo recibounicageração nao Informado.";
         $this->erro_campo = "k00_recibounicageracao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_sequencial"])){
       $sql  .= $virgula." k00_sequencial = $this->k00_sequencial ";
       $virgula = ",";
       if(trim($this->k00_sequencial) == null ){
         $this->erro_sql = " Campo Código da arrecadação suspensa nao Informado.";
         $this->erro_campo = "k00_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k00_sequencial!=null){
       $sql .= " k00_sequencial = $this->k00_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k00_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11816,'$this->k00_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_numpre"]) || $this->k00_numpre != "")
           $resac = db_query("insert into db_acount values($acount,1504,361,'".AddSlashes(pg_result($resaco,$conresaco,'k00_numpre'))."','$this->k00_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_dtvenc"]) || $this->k00_dtvenc != "")
           $resac = db_query("insert into db_acount values($acount,1504,377,'".AddSlashes(pg_result($resaco,$conresaco,'k00_dtvenc'))."','$this->k00_dtvenc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_dtoper"]) || $this->k00_dtoper != "")
           $resac = db_query("insert into db_acount values($acount,1504,373,'".AddSlashes(pg_result($resaco,$conresaco,'k00_dtoper'))."','$this->k00_dtoper',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_percdes"]) || $this->k00_percdes != "")
           $resac = db_query("insert into db_acount values($acount,1504,8812,'".AddSlashes(pg_result($resaco,$conresaco,'k00_percdes'))."','$this->k00_percdes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_tipoger"]) || $this->k00_tipoger != "")
           $resac = db_query("insert into db_acount values($acount,1504,8065,'".AddSlashes(pg_result($resaco,$conresaco,'k00_tipoger'))."','$this->k00_tipoger',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_recibounicageracao"]) || $this->k00_recibounicageracao != "")
           $resac = db_query("insert into db_acount values($acount,1504,18478,'".AddSlashes(pg_result($resaco,$conresaco,'k00_recibounicageracao'))."','$this->k00_recibounicageracao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_sequencial"]) || $this->k00_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,1504,11816,'".AddSlashes(pg_result($resaco,$conresaco,'k00_sequencial'))."','$this->k00_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Numpres em cota unica nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k00_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Numpres em cota unica nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k00_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k00_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($k00_sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k00_sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11816,'$k00_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1504,361,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1504,377,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_dtvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1504,373,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_dtoper'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1504,8812,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_percdes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1504,8065,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_tipoger'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1504,18478,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_recibounicageracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1504,11816,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from recibounica
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k00_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k00_sequencial = $k00_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Numpres em cota unica nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k00_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Numpres em cota unica nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k00_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k00_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:recibounica";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $k00_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from recibounica ";
     $sql .= "      inner join recibounicageracao  on  recibounicageracao.ar40_sequencial = recibounica.k00_recibounicageracao";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = recibounicageracao.ar40_db_usuarios";
     $sql2 = "";
     if($dbwhere==""){
       if($k00_sequencial!=null ){
         $sql2 .= " where recibounica.k00_sequencial = $k00_sequencial ";
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
   function sql_query_file ( $k00_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from recibounica ";
     $sql2 = "";
     if($dbwhere==""){
       if($k00_sequencial!=null ){
         $sql2 .= " where recibounica.k00_sequencial = $k00_sequencial ";
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
   * Retorna sql de exclusão de recibounica realizando validações em recibounicageracao
   * @param  integer $iReciboUnicaGeracao Sequencial de recibounicageracao
   * @param  integer $iUsuario            Codigo do usuario de recibounicageracao
   * @param  string  $sDataVencimento     Data de vencimento de recibounicageracao
   * @return string
   */
  function excluir_unica_geral($iReciboUnicaGeracao, $iUsuario, $sDataVencimento){

    $sSql  = "delete from recibounica                                                            ";
    $sSql .= " where k00_recibounicageracao = (select ar40_sequencial                            ";
    $sSql .= "                                   from recibounicageracao                         ";
    $sSql .= "                                  where ar40_sequencial = {$iReciboUnicaGeracao}   ";

    if(db_getsession("DB_administrador") == 0){
      $sSql .= "                                  and ar40_db_usuarios = {$iUsuario}             ";
    }

    $sSql .= "                                    and ar40_dtvencimento >= '{$sDataVencimento}') ";
    $sSql .= "   and not exists(select 1                                                         ";
    $sSql .= "                    from arrepaga                                                  ";
    $sSql .= "                   where arrepaga.k00_numpre = recibounica.k00_numpre              ";
    $sSql .= "                     and arrepaga.k00_hist   = 990)                                ";

    return $sSql;
  }

  /**
   * Retorna sql de consulta a única parcial
   * @param  string $sWhere
   * @param  string $sInnerJoin
   * @param  string $sOrderby
   * @return string
   */
  function sql_query_unica_parcial($sWhere = null, $sInnerJoin = null, $sOrderby = "k00_dtvenc"){

    $sSql  = "  select k00_sequencial,                                                                                   ";
    $sSql .= "         k00_dtoper,                                                                                       ";
    $sSql .= "         k00_dtvenc,                                                                                       ";
    $sSql .= "         k00_percdes,                                                                                      ";
    $sSql .= "         (select case when rimatric is not null then ('Matrícula - ' || rimatric::text || ' - ' || rvnome) ";
    $sSql .= "                      when riinscr  is not null then ('Inscrição - ' || riinscr::text  || ' - ' || rvnome) ";
    $sSql .= "                      when rinumcgm is not null then ('CGM - '       || rinumcgm::text || ' - ' || rvnome) ";
    $sSql .= "                 end  || ' - ' ||                                                                          ";
    $sSql .= "                 case when riTipoEnvol = 1 then 'Proprietário Principal'                                   ";
    $sSql .= "                      when riTipoEnvol = 2 then 'Proprietário'                                             ";
    $sSql .= "                      when riTipoEnvol = 3 then 'Promitente'                                               ";
    $sSql .= "                      when riTipoEnvol = 4 then 'CGM Empresa'                                              ";
    $sSql .= "                      when riTipoEnvol = 5 then 'Sócio'                                                    ";
    $sSql .= "                      end                                                                                  ";
    $sSql .= "            from fc_socio_promitente(recibounica.k00_numpre,                                               ";
    $sSql .= "                                     true,                                                                 ";
    $sSql .= "                                     (select coalesce(fc_regrasconfig(1), 1)),                             ";
    $sSql .= "                                     (select coalesce(fc_regrasconfig(2), 1)))                             ";
    $sSql .= "         ) as origem                                                                                       ";
    $sSql .= "    from recibounica                                                                                       ";
    $sSql .= "         inner join recibounicageracao on ar40_sequencial = k00_recibounicageracao                         ";
    $sSql .= "         {$sInnerJoin}                                                                                     ";
    $sSql .= "   where not exists( select 1                                                                              ";
    $sSql .= "                       from arrepaga                                                                       ";
    $sSql .= "                      where arrepaga.k00_numpre = recibounica.k00_numpre                                   ";
    $sSql .= "                        and arrepaga.k00_hist   = 990)                                                     ";
    $sSql .= "         {$sWhere}                                                                                         ";
    $sSql .= "order by {$sOrderby}                                                                                       ";

    return $sSql;
  }
}