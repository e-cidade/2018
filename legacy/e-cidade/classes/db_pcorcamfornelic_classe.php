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

//MODULO: licitação
//CLASSE DA ENTIDADE pcorcamfornelic
class cl_pcorcamfornelic {
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
   var $pc31_orcamforne = 0;
   var $pc31_nomeretira = null;
   var $pc31_dtretira_dia = null;
   var $pc31_dtretira_mes = null;
   var $pc31_dtretira_ano = null;
   var $pc31_dtretira = null;
   var $pc31_horaretira = null;
   var $pc31_liclicitatipoempresa = 0;
   var $pc31_tipocondicao = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 pc31_orcamforne = int8 = Código do orcamento deste fornecedor
                 pc31_nomeretira = varchar(100) = Nome da Pessoa que Retirou o Edital
                 pc31_dtretira = date = Data da Retirada do Edital
                 pc31_horaretira = char(5) = Hora da Retirada do Edital
                 pc31_liclicitatipoempresa = int4 = Tipo da Empresa
                 pc31_tipocondicao = int4 = Tipo de Condição
                 ";
   //funcao construtor da classe
   function cl_pcorcamfornelic() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pcorcamfornelic");
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
       $this->pc31_orcamforne = ($this->pc31_orcamforne == ""?@$GLOBALS["HTTP_POST_VARS"]["pc31_orcamforne"]:$this->pc31_orcamforne);
       $this->pc31_nomeretira = ($this->pc31_nomeretira == ""?@$GLOBALS["HTTP_POST_VARS"]["pc31_nomeretira"]:$this->pc31_nomeretira);
       if($this->pc31_dtretira == ""){
         $this->pc31_dtretira_dia = ($this->pc31_dtretira_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc31_dtretira_dia"]:$this->pc31_dtretira_dia);
         $this->pc31_dtretira_mes = ($this->pc31_dtretira_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["pc31_dtretira_mes"]:$this->pc31_dtretira_mes);
         $this->pc31_dtretira_ano = ($this->pc31_dtretira_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["pc31_dtretira_ano"]:$this->pc31_dtretira_ano);
         if($this->pc31_dtretira_dia != ""){
            $this->pc31_dtretira = $this->pc31_dtretira_ano."-".$this->pc31_dtretira_mes."-".$this->pc31_dtretira_dia;
         }
       }
       $this->pc31_horaretira = ($this->pc31_horaretira == ""?@$GLOBALS["HTTP_POST_VARS"]["pc31_horaretira"]:$this->pc31_horaretira);
       $this->pc31_liclicitatipoempresa = ($this->pc31_liclicitatipoempresa == ""?@$GLOBALS["HTTP_POST_VARS"]["pc31_liclicitatipoempresa"]:$this->pc31_liclicitatipoempresa);
       $this->pc31_tipocondicao = ($this->pc31_tipocondicao == ""?@$GLOBALS["HTTP_POST_VARS"]["pc31_tipocondicao"]:$this->pc31_tipocondicao);
     }else{
       $this->pc31_orcamforne = ($this->pc31_orcamforne == ""?@$GLOBALS["HTTP_POST_VARS"]["pc31_orcamforne"]:$this->pc31_orcamforne);
     }
   }
   // funcao para Inclusão
   function incluir ($pc31_orcamforne){
      $this->atualizacampos();
     if($this->pc31_dtretira == null ){
       $this->erro_sql = " Campo Data da Retirada do Edital não informado.";
       $this->erro_campo = "pc31_dtretira_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc31_horaretira == null ){
       $this->erro_sql = " Campo Hora da Retirada do Edital não informado.";
       $this->erro_campo = "pc31_horaretira";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc31_liclicitatipoempresa == null ){
       $this->erro_sql = " Campo Tipo da Empresa não informado.";
       $this->erro_campo = "pc31_liclicitatipoempresa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->pc31_orcamforne = $pc31_orcamforne;
     if(($this->pc31_orcamforne == null) || ($this->pc31_orcamforne == "") ){
       $this->erro_sql = " Campo pc31_orcamforne não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }

    if ($this->pc31_tipocondicao == null || $this->pc31_tipocondicao == '') {
      $this->pc31_tipocondicao = "null";
    }

     $sql = "insert into pcorcamfornelic(
                                       pc31_orcamforne
                                      ,pc31_nomeretira
                                      ,pc31_dtretira
                                      ,pc31_horaretira
                                      ,pc31_liclicitatipoempresa
                                      ,pc31_tipocondicao
                       )
                values (
                                $this->pc31_orcamforne
                               ,'$this->pc31_nomeretira'
                               ,".($this->pc31_dtretira == "null" || $this->pc31_dtretira == ""?"null":"'".$this->pc31_dtretira."'")."
                               ,'$this->pc31_horaretira'
                               ,$this->pc31_liclicitatipoempresa
                               ,$this->pc31_tipocondicao
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "pcorcamfornelic ($this->pc31_orcamforne) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "pcorcamfornelic já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "pcorcamfornelic ($this->pc31_orcamforne) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc31_orcamforne;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->pc31_orcamforne  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7757,'$this->pc31_orcamforne','I')");
         $resac = db_query("insert into db_acount values($acount,1291,7757,'','".AddSlashes(pg_result($resaco,0,'pc31_orcamforne'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1291,7755,'','".AddSlashes(pg_result($resaco,0,'pc31_nomeretira'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1291,7754,'','".AddSlashes(pg_result($resaco,0,'pc31_dtretira'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1291,7756,'','".AddSlashes(pg_result($resaco,0,'pc31_horaretira'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1291,12301,'','".AddSlashes(pg_result($resaco,0,'pc31_liclicitatipoempresa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1291,21728,'','".AddSlashes(pg_result($resaco,0,'pc31_tipocondicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($pc31_orcamforne=null) {
      $this->atualizacampos();
     $sql = " update pcorcamfornelic set ";
     $virgula = "";
     if(trim($this->pc31_orcamforne)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc31_orcamforne"])){
       $sql  .= $virgula." pc31_orcamforne = $this->pc31_orcamforne ";
       $virgula = ",";
       if(trim($this->pc31_orcamforne) == null ){
         $this->erro_sql = " Campo Código do orcamento deste fornecedor não informado.";
         $this->erro_campo = "pc31_orcamforne";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc31_nomeretira)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc31_nomeretira"])){
       $sql  .= $virgula." pc31_nomeretira = '$this->pc31_nomeretira' ";
       $virgula = ",";
     }
     if(trim($this->pc31_dtretira)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc31_dtretira_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["pc31_dtretira_dia"] !="") ){
       $sql  .= $virgula." pc31_dtretira = '$this->pc31_dtretira' ";
       $virgula = ",";
       if(trim($this->pc31_dtretira) == null ){
         $this->erro_sql = " Campo Data da Retirada do Edital não informado.";
         $this->erro_campo = "pc31_dtretira_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["pc31_dtretira_dia"])){
         $sql  .= $virgula." pc31_dtretira = null ";
         $virgula = ",";
         if(trim($this->pc31_dtretira) == null ){
           $this->erro_sql = " Campo Data da Retirada do Edital não informado.";
           $this->erro_campo = "pc31_dtretira_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->pc31_horaretira)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc31_horaretira"])){
       $sql  .= $virgula." pc31_horaretira = '$this->pc31_horaretira' ";
       $virgula = ",";
       if(trim($this->pc31_horaretira) == null ){
         $this->erro_sql = " Campo Hora da Retirada do Edital não informado.";
         $this->erro_campo = "pc31_horaretira";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc31_liclicitatipoempresa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc31_liclicitatipoempresa"])){
       $sql  .= $virgula." pc31_liclicitatipoempresa = $this->pc31_liclicitatipoempresa ";
       $virgula = ",";
       if(trim($this->pc31_liclicitatipoempresa) == null ){
         $this->erro_sql = " Campo Tipo da Empresa não informado.";
         $this->erro_campo = "pc31_liclicitatipoempresa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc31_tipocondicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc31_tipocondicao"])){
        if(trim($this->pc31_tipocondicao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["pc31_tipocondicao"])){
           $this->pc31_tipocondicao = "null" ;
        }
       $sql  .= $virgula." pc31_tipocondicao = $this->pc31_tipocondicao ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($pc31_orcamforne!=null){
       $sql .= " pc31_orcamforne = $this->pc31_orcamforne";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->pc31_orcamforne));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,7757,'$this->pc31_orcamforne','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pc31_orcamforne"]) || $this->pc31_orcamforne != "")
             $resac = db_query("insert into db_acount values($acount,1291,7757,'".AddSlashes(pg_result($resaco,$conresaco,'pc31_orcamforne'))."','$this->pc31_orcamforne',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pc31_nomeretira"]) || $this->pc31_nomeretira != "")
             $resac = db_query("insert into db_acount values($acount,1291,7755,'".AddSlashes(pg_result($resaco,$conresaco,'pc31_nomeretira'))."','$this->pc31_nomeretira',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pc31_dtretira"]) || $this->pc31_dtretira != "")
             $resac = db_query("insert into db_acount values($acount,1291,7754,'".AddSlashes(pg_result($resaco,$conresaco,'pc31_dtretira'))."','$this->pc31_dtretira',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pc31_horaretira"]) || $this->pc31_horaretira != "")
             $resac = db_query("insert into db_acount values($acount,1291,7756,'".AddSlashes(pg_result($resaco,$conresaco,'pc31_horaretira'))."','$this->pc31_horaretira',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pc31_liclicitatipoempresa"]) || $this->pc31_liclicitatipoempresa != "")
             $resac = db_query("insert into db_acount values($acount,1291,12301,'".AddSlashes(pg_result($resaco,$conresaco,'pc31_liclicitatipoempresa'))."','$this->pc31_liclicitatipoempresa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pc31_tipocondicao"]) || $this->pc31_tipocondicao != "")
             $resac = db_query("insert into db_acount values($acount,1291,21728,'".AddSlashes(pg_result($resaco,$conresaco,'pc31_tipocondicao'))."','$this->pc31_tipocondicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "pcorcamfornelic não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc31_orcamforne;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "pcorcamfornelic não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc31_orcamforne;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc31_orcamforne;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($pc31_orcamforne=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($pc31_orcamforne));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,7757,'$pc31_orcamforne','E')");
           $resac  = db_query("insert into db_acount values($acount,1291,7757,'','".AddSlashes(pg_result($resaco,$iresaco,'pc31_orcamforne'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1291,7755,'','".AddSlashes(pg_result($resaco,$iresaco,'pc31_nomeretira'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1291,7754,'','".AddSlashes(pg_result($resaco,$iresaco,'pc31_dtretira'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1291,7756,'','".AddSlashes(pg_result($resaco,$iresaco,'pc31_horaretira'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1291,12301,'','".AddSlashes(pg_result($resaco,$iresaco,'pc31_liclicitatipoempresa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1291,21728,'','".AddSlashes(pg_result($resaco,$iresaco,'pc31_tipocondicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from pcorcamfornelic
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($pc31_orcamforne)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " pc31_orcamforne = $pc31_orcamforne ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "pcorcamfornelic não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc31_orcamforne;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "pcorcamfornelic não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc31_orcamforne;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc31_orcamforne;
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
        $this->erro_sql   = "Record Vazio na Tabela:pcorcamfornelic";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($pc31_orcamforne = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from pcorcamfornelic ";
     $sql .= "      inner join pcorcamforne  on  pcorcamforne.pc21_orcamforne = pcorcamfornelic.pc31_orcamforne";
     $sql .= "      inner join liclicitatipoempresa  on  liclicitatipoempresa.l32_sequencial = pcorcamfornelic.pc31_liclicitatipoempresa";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = pcorcamforne.pc21_numcgm";
     $sql .= "      inner join pcorcam  on  pcorcam.pc20_codorc = pcorcamforne.pc21_codorc";
     $sql .= "      left  join pcorcamfornelichabilitacao on l17_pcorcamfornelic = pc31_orcamforne  ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($pc31_orcamforne)) {
         $sql2 .= " where pcorcamfornelic.pc31_orcamforne = $pc31_orcamforne ";
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
   public function sql_query_file ($pc31_orcamforne = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from pcorcamfornelic ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($pc31_orcamforne)){
         $sql2 .= " where pcorcamfornelic.pc31_orcamforne = $pc31_orcamforne ";
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

}
