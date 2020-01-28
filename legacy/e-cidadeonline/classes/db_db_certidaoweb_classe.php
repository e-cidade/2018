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

//MODULO: prefeitura
//CLASSE DA ENTIDADE db_certidaoweb
class cl_db_certidaoweb {
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
   var $codcert = null;
   var $tipocer = 0;
   var $cerdtemite_dia = null;
   var $cerdtemite_mes = null;
   var $cerdtemite_ano = null;
   var $cerdtemite = null;
   var $cerhora = null;
   var $cerdtvenc_dia = null;
   var $cerdtvenc_mes = null;
   var $cerdtvenc_ano = null;
   var $cerdtvenc = null;
   var $cerip = null;
   var $ceracesso = null;
   var $cercertidao = 0;
   var $cernomecontr = null;
   var $cerweb = 'f';
   var $cerhtml = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 codcert = varchar(50) = Código da certidão
                 tipocer = int8 = tipo de certidao
                 cerdtemite = date = data da emissao
                 cerhora = varchar(8) = hora da emissao
                 cerdtvenc = date = data de venc
                 cerip = varchar(40) = ip
                 ceracesso = varchar(40) = Acesso
                 cercertidao = oid = Certidão
                 cernomecontr = varchar(100) = Nome
                 cerweb = bool = Web
                 cerhtml = text = Certidão HTML
                 ";
   //funcao construtor da classe
   function cl_db_certidaoweb() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_certidaoweb");
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
       $this->codcert = ($this->codcert == ""?@$GLOBALS["HTTP_POST_VARS"]["codcert"]:$this->codcert);
       $this->tipocer = ($this->tipocer == ""?@$GLOBALS["HTTP_POST_VARS"]["tipocer"]:$this->tipocer);
       if($this->cerdtemite == ""){
         $this->cerdtemite_dia = ($this->cerdtemite_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["cerdtemite_dia"]:$this->cerdtemite_dia);
         $this->cerdtemite_mes = ($this->cerdtemite_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["cerdtemite_mes"]:$this->cerdtemite_mes);
         $this->cerdtemite_ano = ($this->cerdtemite_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["cerdtemite_ano"]:$this->cerdtemite_ano);
         if($this->cerdtemite_dia != ""){
            $this->cerdtemite = $this->cerdtemite_ano."-".$this->cerdtemite_mes."-".$this->cerdtemite_dia;
         }
       }
       $this->cerhora = ($this->cerhora == ""?@$GLOBALS["HTTP_POST_VARS"]["cerhora"]:$this->cerhora);
       if($this->cerdtvenc == ""){
         $this->cerdtvenc_dia = ($this->cerdtvenc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["cerdtvenc_dia"]:$this->cerdtvenc_dia);
         $this->cerdtvenc_mes = ($this->cerdtvenc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["cerdtvenc_mes"]:$this->cerdtvenc_mes);
         $this->cerdtvenc_ano = ($this->cerdtvenc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["cerdtvenc_ano"]:$this->cerdtvenc_ano);
         if($this->cerdtvenc_dia != ""){
            $this->cerdtvenc = $this->cerdtvenc_ano."-".$this->cerdtvenc_mes."-".$this->cerdtvenc_dia;
         }
       }
       $this->cerip = ($this->cerip == ""?@$GLOBALS["HTTP_POST_VARS"]["cerip"]:$this->cerip);
       $this->ceracesso = ($this->ceracesso == ""?@$GLOBALS["HTTP_POST_VARS"]["ceracesso"]:$this->ceracesso);
       $this->cercertidao = ($this->cercertidao == ""?@$GLOBALS["HTTP_POST_VARS"]["cercertidao"]:$this->cercertidao);
       $this->cernomecontr = ($this->cernomecontr == ""?@$GLOBALS["HTTP_POST_VARS"]["cernomecontr"]:$this->cernomecontr);
       $this->cerweb = ($this->cerweb == "f"?@$GLOBALS["HTTP_POST_VARS"]["cerweb"]:$this->cerweb);
       $this->cerhtml = ($this->cerhtml == ""?@$GLOBALS["HTTP_POST_VARS"]["cerhtml"]:$this->cerhtml);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){
      $this->atualizacampos();
     if($this->codcert == null ){
       $this->erro_sql = " Campo Código da certidão nao Informado.";
       $this->erro_campo = "codcert";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tipocer == null ){
       $this->erro_sql = " Campo tipo de certidao nao Informado.";
       $this->erro_campo = "tipocer";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cerdtemite == null ){
       $this->erro_sql = " Campo data da emissao nao Informado.";
       $this->erro_campo = "cerdtemite_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cerhora == null ){
       $this->erro_sql = " Campo hora da emissao nao Informado.";
       $this->erro_campo = "cerhora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cerdtvenc == null ){
       $this->erro_sql = " Campo data de venc nao Informado.";
       $this->erro_campo = "cerdtvenc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cerip == null ){
       $this->erro_sql = " Campo ip nao Informado.";
       $this->erro_campo = "cerip";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ceracesso == null ){
       $this->erro_sql = " Campo Acesso nao Informado.";
       $this->erro_campo = "ceracesso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cernomecontr == null ){
       $this->erro_sql = " Campo Nome nao Informado.";
       $this->erro_campo = "cernomecontr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cerweb == null ){
       $this->erro_sql = " Campo Web nao Informado.";
       $this->erro_campo = "cerweb";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }

     if ( empty($this->codcert) ) {


       if($codcert == "" || $codcert == null ){
         $result = db_query("select nextval('db_certidaoweb_codcert_seq')");
         if($result==false){
           $this->erro_banco = str_replace("\n","",@pg_last_error());
           $this->erro_sql   = "Verifique o cadastro da sequencia: db_certidaoweb_codcert_seq do campo: codcert";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
         $this->codcert = pg_result($result,0,0);
       }else{
         $result = db_query("select last_value from db_certidaoweb_codcert_seq");
         if(($result != false) && (pg_result($result,0,0) < $codcert)){
           $this->erro_sql = " Campo codcert maior que último número da sequencia.";
           $this->erro_banco = "Sequencia menor que este número.";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }else{
           $this->codcert = $codcert;
         }
       }
     }

     $sql = "insert into db_certidaoweb(
                                       codcert
                                      ,tipocer
                                      ,cerdtemite
                                      ,cerhora
                                      ,cerdtvenc
                                      ,cerip
                                      ,ceracesso
                                      ,cercertidao
                                      ,cernomecontr
                                      ,cerweb
                                      ,cerhtml
                       )
                values (
                                '$this->codcert'
                               ,$this->tipocer
                               ,".($this->cerdtemite == "null" || $this->cerdtemite == ""?"null":"'".$this->cerdtemite."'")."
                               ,'$this->cerhora'
                               ,".($this->cerdtvenc == "null" || $this->cerdtvenc == ""?"null":"'".$this->cerdtvenc."'")."
                               ,'$this->cerip'
                               ,'$this->ceracesso'
                               ,$this->cercertidao
                               ,'$this->cernomecontr'
                               ,'$this->cerweb'
                               ,'$this->cerhtml'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "certidao_web () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "certidao_web já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "certidao_web () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     return true;
   }
   // funcao para alteracao
   function alterar ( $oid=null ) {
      $this->atualizacampos();
     $sql = " update db_certidaoweb set ";
     $virgula = "";
     if(trim($this->codcert)!="" || isset($GLOBALS["HTTP_POST_VARS"]["codcert"])){
       $sql  .= $virgula." codcert = '$this->codcert' ";
       $virgula = ",";
       if(trim($this->codcert) == null ){
         $this->erro_sql = " Campo Código da certidão nao Informado.";
         $this->erro_campo = "codcert";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tipocer)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tipocer"])){
       $sql  .= $virgula." tipocer = $this->tipocer ";
       $virgula = ",";
       if(trim($this->tipocer) == null ){
         $this->erro_sql = " Campo tipo de certidao nao Informado.";
         $this->erro_campo = "tipocer";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cerdtemite)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cerdtemite_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["cerdtemite_dia"] !="") ){
       $sql  .= $virgula." cerdtemite = '$this->cerdtemite' ";
       $virgula = ",";
       if(trim($this->cerdtemite) == null ){
         $this->erro_sql = " Campo data da emissao nao Informado.";
         $this->erro_campo = "cerdtemite_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["cerdtemite_dia"])){
         $sql  .= $virgula." cerdtemite = null ";
         $virgula = ",";
         if(trim($this->cerdtemite) == null ){
           $this->erro_sql = " Campo data da emissao nao Informado.";
           $this->erro_campo = "cerdtemite_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->cerhora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cerhora"])){
       $sql  .= $virgula." cerhora = '$this->cerhora' ";
       $virgula = ",";
       if(trim($this->cerhora) == null ){
         $this->erro_sql = " Campo hora da emissao nao Informado.";
         $this->erro_campo = "cerhora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cerdtvenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cerdtvenc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["cerdtvenc_dia"] !="") ){
       $sql  .= $virgula." cerdtvenc = '$this->cerdtvenc' ";
       $virgula = ",";
       if(trim($this->cerdtvenc) == null ){
         $this->erro_sql = " Campo data de venc nao Informado.";
         $this->erro_campo = "cerdtvenc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["cerdtvenc_dia"])){
         $sql  .= $virgula." cerdtvenc = null ";
         $virgula = ",";
         if(trim($this->cerdtvenc) == null ){
           $this->erro_sql = " Campo data de venc nao Informado.";
           $this->erro_campo = "cerdtvenc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->cerip)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cerip"])){
       $sql  .= $virgula." cerip = '$this->cerip' ";
       $virgula = ",";
       if(trim($this->cerip) == null ){
         $this->erro_sql = " Campo ip nao Informado.";
         $this->erro_campo = "cerip";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ceracesso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ceracesso"])){
       $sql  .= $virgula." ceracesso = '$this->ceracesso' ";
       $virgula = ",";
       if(trim($this->ceracesso) == null ){
         $this->erro_sql = " Campo Acesso nao Informado.";
         $this->erro_campo = "ceracesso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cercertidao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cercertidao"])){
       $sql  .= $virgula." cercertidao = $this->cercertidao ";
       $virgula = ",";
     }
     if(trim($this->cernomecontr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cernomecontr"])){
       $sql  .= $virgula." cernomecontr = '$this->cernomecontr' ";
       $virgula = ",";
       if(trim($this->cernomecontr) == null ){
         $this->erro_sql = " Campo Nome nao Informado.";
         $this->erro_campo = "cernomecontr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cerweb)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cerweb"])){
       $sql  .= $virgula." cerweb = '$this->cerweb' ";
       $virgula = ",";
       if(trim($this->cerweb) == null ){
         $this->erro_sql = " Campo Web nao Informado.";
         $this->erro_campo = "cerweb";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cerhtml)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cerhtml"])){
       $sql  .= $virgula." cerhtml = '$this->cerhtml' ";
       $virgula = ",";
     }
     $sql .= " where ";
$sql .= "oid = '$oid'";     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "certidao_web nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "certidao_web nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ( $oid=null ,$dbwhere=null) {
     $sql = " delete from db_certidaoweb
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
       $sql2 = "oid = '$oid'";
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "certidao_web nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "certidao_web nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:db_certidaoweb";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

   // funcao do sql
   function sql_query ( $oid,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from db_certidaoweb ";
     $sql2 = "";
     if($dbwhere==""){
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
   function sql_query_file ( $oid,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from db_certidaoweb ";
     $sql2 = "";
     if($dbwhere==""){
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
