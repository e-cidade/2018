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

//MODULO: ambulatorial
//CLASSE DA ENTIDADE medicos
class cl_medicos {
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
   var $sd03_i_codigo = 0;
   var $sd03_i_crm = 0;
   var $sd03_i_numerodias = 0;
   var $sd03_d_folgaini_dia = null;
   var $sd03_d_folgaini_mes = null;
   var $sd03_d_folgaini_ano = null;
   var $sd03_d_folgaini = null;
   var $sd03_d_folgafim_dia = null;
   var $sd03_d_folgafim_mes = null;
   var $sd03_d_folgafim_ano = null;
   var $sd03_d_folgafim = null;
   var $sd03_i_cgm = 0;
   var $sd03_i_tipo = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 sd03_i_codigo = int4 = Profissional
                 sd03_i_crm = int4 = CRM
                 sd03_i_numerodias = int4 = Nro. Dias
                 sd03_d_folgaini = date = Folgaini
                 sd03_d_folgafim = date = Folgafim
                 sd03_i_cgm = int4 = CGM
                 sd03_i_tipo = int4 = Tipo
                 ";
   //funcao construtor da classe
   function cl_medicos() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("medicos");
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
       $this->sd03_i_codigo = ($this->sd03_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd03_i_codigo"]:$this->sd03_i_codigo);
       $this->sd03_i_crm = ($this->sd03_i_crm == ""?@$GLOBALS["HTTP_POST_VARS"]["sd03_i_crm"]:$this->sd03_i_crm);
       $this->sd03_i_numerodias = ($this->sd03_i_numerodias == ""?@$GLOBALS["HTTP_POST_VARS"]["sd03_i_numerodias"]:$this->sd03_i_numerodias);
       if($this->sd03_d_folgaini == ""){
         $this->sd03_d_folgaini_dia = ($this->sd03_d_folgaini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd03_d_folgaini_dia"]:$this->sd03_d_folgaini_dia);
         $this->sd03_d_folgaini_mes = ($this->sd03_d_folgaini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd03_d_folgaini_mes"]:$this->sd03_d_folgaini_mes);
         $this->sd03_d_folgaini_ano = ($this->sd03_d_folgaini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd03_d_folgaini_ano"]:$this->sd03_d_folgaini_ano);
         if($this->sd03_d_folgaini_dia != ""){
            $this->sd03_d_folgaini = $this->sd03_d_folgaini_ano."-".$this->sd03_d_folgaini_mes."-".$this->sd03_d_folgaini_dia;
         }
       }
       if($this->sd03_d_folgafim == ""){
         $this->sd03_d_folgafim_dia = ($this->sd03_d_folgafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd03_d_folgafim_dia"]:$this->sd03_d_folgafim_dia);
         $this->sd03_d_folgafim_mes = ($this->sd03_d_folgafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd03_d_folgafim_mes"]:$this->sd03_d_folgafim_mes);
         $this->sd03_d_folgafim_ano = ($this->sd03_d_folgafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd03_d_folgafim_ano"]:$this->sd03_d_folgafim_ano);
         if($this->sd03_d_folgafim_dia != ""){
            $this->sd03_d_folgafim = $this->sd03_d_folgafim_ano."-".$this->sd03_d_folgafim_mes."-".$this->sd03_d_folgafim_dia;
         }
       }
       $this->sd03_i_cgm = ($this->sd03_i_cgm == ""?@$GLOBALS["HTTP_POST_VARS"]["sd03_i_cgm"]:$this->sd03_i_cgm);
       $this->sd03_i_tipo = ($this->sd03_i_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd03_i_tipo"]:$this->sd03_i_tipo);
     }else{
       $this->sd03_i_codigo = ($this->sd03_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd03_i_codigo"]:$this->sd03_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($sd03_i_codigo){
      $this->atualizacampos();
     if($this->sd03_i_crm == null ){
       $this->sd03_i_crm = "null";
     }
     if($this->sd03_i_numerodias == null ){
       $this->sd03_i_numerodias = "0";
     }
     if($this->sd03_d_folgaini == null ){
       $this->sd03_d_folgaini = "null";
     }
     if($this->sd03_d_folgafim == null ){
       $this->sd03_d_folgafim = "null";
     }
     if($this->sd03_i_cgm == null ){
       $this->sd03_i_cgm = "null";
     }
     if($this->sd03_i_tipo == null ){
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "sd03_i_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($sd03_i_codigo == "" || $sd03_i_codigo == null ){
       $result = db_query("select nextval('medicos_sd03_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: medicos_sd03_i_codigo_seq do campo: sd03_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->sd03_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from medicos_sd03_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd03_i_codigo)){
         $this->erro_sql = " Campo sd03_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd03_i_codigo = $sd03_i_codigo;
       }
     }
     if(($this->sd03_i_codigo == null) || ($this->sd03_i_codigo == "") ){
       $this->erro_sql = " Campo sd03_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into medicos(
                                       sd03_i_codigo
                                      ,sd03_i_crm
                                      ,sd03_i_numerodias
                                      ,sd03_d_folgaini
                                      ,sd03_d_folgafim
                                      ,sd03_i_cgm
                                      ,sd03_i_tipo
                       )
                values (
                                $this->sd03_i_codigo
                               ,$this->sd03_i_crm
                               ,$this->sd03_i_numerodias
                               ,".($this->sd03_d_folgaini == "null" || $this->sd03_d_folgaini == ""?"null":"'".$this->sd03_d_folgaini."'")."
                               ,".($this->sd03_d_folgafim == "null" || $this->sd03_d_folgafim == ""?"null":"'".$this->sd03_d_folgafim."'")."
                               ,$this->sd03_i_cgm
                               ,$this->sd03_i_tipo
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Médicos ($this->sd03_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Médicos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Médicos ($this->sd03_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd03_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->sd03_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,100051,'$this->sd03_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,100012,100051,'','".AddSlashes(pg_result($resaco,0,'sd03_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100012,12375,'','".AddSlashes(pg_result($resaco,0,'sd03_i_crm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100012,12357,'','".AddSlashes(pg_result($resaco,0,'sd03_i_numerodias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100012,12372,'','".AddSlashes(pg_result($resaco,0,'sd03_d_folgaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100012,12373,'','".AddSlashes(pg_result($resaco,0,'sd03_d_folgafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100012,11374,'','".AddSlashes(pg_result($resaco,0,'sd03_i_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100012,17492,'','".AddSlashes(pg_result($resaco,0,'sd03_i_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($sd03_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update medicos set ";
     $virgula = "";
     if(trim($this->sd03_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd03_i_codigo"])){
       $sql  .= $virgula." sd03_i_codigo = $this->sd03_i_codigo ";
       $virgula = ",";
       if(trim($this->sd03_i_codigo) == null ){
         $this->erro_sql = " Campo Profissional nao Informado.";
         $this->erro_campo = "sd03_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd03_i_crm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd03_i_crm"])){
        if(trim($this->sd03_i_crm)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd03_i_crm"])){
           $this->sd03_i_crm = "0" ;
        }
       $sql  .= $virgula." sd03_i_crm = $this->sd03_i_crm ";
       $virgula = ",";
     }
     if(trim($this->sd03_i_numerodias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd03_i_numerodias"])){
        if(trim($this->sd03_i_numerodias)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd03_i_numerodias"])){
           $this->sd03_i_numerodias = "0" ;
        }
       $sql  .= $virgula." sd03_i_numerodias = $this->sd03_i_numerodias ";
       $virgula = ",";
     }
     if(trim($this->sd03_d_folgaini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd03_d_folgaini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["sd03_d_folgaini_dia"] !="") ){
       $sql  .= $virgula." sd03_d_folgaini = '$this->sd03_d_folgaini' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["sd03_d_folgaini_dia"])){
         $sql  .= $virgula." sd03_d_folgaini = null ";
         $virgula = ",";
       }
     }
     if(trim($this->sd03_d_folgafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd03_d_folgafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["sd03_d_folgafim_dia"] !="") ){
       $sql  .= $virgula." sd03_d_folgafim = '$this->sd03_d_folgafim' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["sd03_d_folgafim_dia"])){
         $sql  .= $virgula." sd03_d_folgafim = null ";
         $virgula = ",";
       }
     }
     if(trim($this->sd03_i_cgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd03_i_cgm"])){
        if(trim($this->sd03_i_cgm)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd03_i_cgm"])){
           $this->sd03_i_cgm = "0" ;
        }
       $sql  .= $virgula." sd03_i_cgm = $this->sd03_i_cgm ";
       $virgula = ",";
     }
     if(trim($this->sd03_i_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd03_i_tipo"])){
       $sql  .= $virgula." sd03_i_tipo = $this->sd03_i_tipo ";
       $virgula = ",";
       if(trim($this->sd03_i_tipo) == null ){
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "sd03_i_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($sd03_i_codigo!=null){
       $sql .= " sd03_i_codigo = $this->sd03_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->sd03_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,100051,'$this->sd03_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd03_i_codigo"]) || $this->sd03_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,100012,100051,'".AddSlashes(pg_result($resaco,$conresaco,'sd03_i_codigo'))."','$this->sd03_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd03_i_crm"]) || $this->sd03_i_crm != "")
           $resac = db_query("insert into db_acount values($acount,100012,12375,'".AddSlashes(pg_result($resaco,$conresaco,'sd03_i_crm'))."','$this->sd03_i_crm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd03_i_numerodias"]) || $this->sd03_i_numerodias != "")
           $resac = db_query("insert into db_acount values($acount,100012,12357,'".AddSlashes(pg_result($resaco,$conresaco,'sd03_i_numerodias'))."','$this->sd03_i_numerodias',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd03_d_folgaini"]) || $this->sd03_d_folgaini != "")
           $resac = db_query("insert into db_acount values($acount,100012,12372,'".AddSlashes(pg_result($resaco,$conresaco,'sd03_d_folgaini'))."','$this->sd03_d_folgaini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd03_d_folgafim"]) || $this->sd03_d_folgafim != "")
           $resac = db_query("insert into db_acount values($acount,100012,12373,'".AddSlashes(pg_result($resaco,$conresaco,'sd03_d_folgafim'))."','$this->sd03_d_folgafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd03_i_cgm"]) || $this->sd03_i_cgm != "")
           $resac = db_query("insert into db_acount values($acount,100012,11374,'".AddSlashes(pg_result($resaco,$conresaco,'sd03_i_cgm'))."','$this->sd03_i_cgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd03_i_tipo"]) || $this->sd03_i_tipo != "")
           $resac = db_query("insert into db_acount values($acount,100012,17492,'".AddSlashes(pg_result($resaco,$conresaco,'sd03_i_tipo'))."','$this->sd03_i_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Médicos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd03_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Médicos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd03_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd03_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($sd03_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($sd03_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,100051,'$sd03_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,100012,100051,'','".AddSlashes(pg_result($resaco,$iresaco,'sd03_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100012,12375,'','".AddSlashes(pg_result($resaco,$iresaco,'sd03_i_crm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100012,12357,'','".AddSlashes(pg_result($resaco,$iresaco,'sd03_i_numerodias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100012,12372,'','".AddSlashes(pg_result($resaco,$iresaco,'sd03_d_folgaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100012,12373,'','".AddSlashes(pg_result($resaco,$iresaco,'sd03_d_folgafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100012,11374,'','".AddSlashes(pg_result($resaco,$iresaco,'sd03_i_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100012,17492,'','".AddSlashes(pg_result($resaco,$iresaco,'sd03_i_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from medicos
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($sd03_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd03_i_codigo = $sd03_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Médicos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd03_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Médicos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd03_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd03_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:medicos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $sd03_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from medicos ";
     $sql .= "      left  join cgm  on  cgm.z01_numcgm = medicos.sd03_i_cgm";
     $sql .= "      left join cgmdoc  on  cgmdoc.z02_i_cgm = cgm.z01_numcgm";
     $sql .= "      left  join unidademedicos  on  unidademedicos.sd04_i_medico = medicos.sd03_i_codigo";
     $sql .= "      left  join especmedico  on  especmedico.sd27_i_undmed = unidademedicos.sd04_i_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($sd03_i_codigo!=null ){
         $sql2 .= " where medicos.sd03_i_codigo = $sd03_i_codigo ";
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
   function sql_query_file ( $sd03_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from medicos ";
     $sql .= "      left  join cgm  on  cgm.z01_numcgm = medicos.sd03_i_cgm";
     $sql .= "      left  join cgmdoc  on  cgmdoc.z02_i_cgm = cgm.z01_numcgm";
     $sql .= "      left  join unidademedicos  on  unidademedicos.sd04_i_medico = medicos.sd03_i_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($sd03_i_codigo!=null ){
         $sql2 .= " where medicos.sd03_i_codigo = $sd03_i_codigo ";
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
   function sql_query_cgm ( $sd03_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from medicos ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = medicos.sd03_i_cgm";
     $sql2 = "";
     if($dbwhere==""){
       if($sd03_i_codigo!=null ){
         $sql2 .= " where medicos.sd03_i_codigo = $sd03_i_codigo ";
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
   function sql_query_ativo ( $sd03_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from medicos ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = medicos.sd03_i_cgm";
     $sql .= "      left join cgmdoc  on  cgmdoc.z02_i_cgm = cgm.z01_numcgm";
     $sql .= "      left  join unidademedicos  on  unidademedicos.sd04_i_medico = medicos.sd03_i_codigo";
     $sql .= "      inner join especmedico  on  especmedico.sd27_i_undmed = unidademedicos.sd04_i_codigo";
     $sql .= "      left  join sau_medicosforarede  on  sau_medicosforarede.s154_i_medico = medicos.sd03_i_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($sd03_i_codigo!=null ){
         $sql2 .= " where medicos.sd03_i_codigo = $sd03_i_codigo ";
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
   function sql_query_cgm_fora_rede ( $sd03_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from medicos ";
     $sql .= "      left  join cgm  on  cgm.z01_numcgm = medicos.sd03_i_cgm";
     $sql .= "      left  join sau_medicosforarede  on  sau_medicosforarede.s154_i_medico = medicos.sd03_i_codigo";
     $sql .= "      left  join cgmdoc  on  cgmdoc.z02_i_cgm = cgm.z01_numcgm";
     $sql .= "      left  join unidademedicos  on  unidademedicos.sd04_i_medico = medicos.sd03_i_codigo";
     $sql .= "      left  join especmedico  on  especmedico.sd27_i_undmed = unidademedicos.sd04_i_codigo";
     $sql .= "      left  join rhcbo  on  sau_medicosforarede.s154_rhcbo = rhcbo.rh70_sequencial";
     $sql2 = "";
     if($dbwhere==""){
       if($sd03_i_codigo!=null ){
         $sql2 .= " where medicos.sd03_i_codigo = $sd03_i_codigo ";
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

  function sql_query_info_profissional ( $sd03_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from medicos ";
     $sql .= "      left  join cgm  on  cgm.z01_numcgm = medicos.sd03_i_cgm";
     $sql .= "      left  join sau_medicosforarede  on  sau_medicosforarede.s154_i_medico = medicos.sd03_i_codigo";
     $sql .= "      left  join cgmdoc  on  cgmdoc.z02_i_cgm = cgm.z01_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($sd03_i_codigo!=null ){
         $sql2 .= " where medicos.sd03_i_codigo = $sd03_i_codigo ";
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

  function sql_query_unidade_cbo_medico ( $sOrder = null, $sWhere = null) {

    $sSql  = "select * "; 
    $sSql .= "  from ( select sd03_i_crm            as crm,  ";
    $sSql .= "                medicos.sd03_i_codigo as codigo_medico,";
    $sSql .= "                medico.z01_nome       as nome,";
    $sSql .= "                rhcbo.rh70_sequencial as codigo_cbo,";
    $sSql .= "                rhcbo.rh70_estrutural as estrutura_cbo,";
    $sSql .= "                rhcbo.rh70_descr      as nome_cbo,";
    $sSql .= "                sd02_i_codigo         as codigo_unidade, ";
    $sSql .= "                unidade.z01_nome      as unidade,";
    $sSql .= "                especmedico.sd27_c_situacao as situacao";
    $sSql .= "           from medicos";
    $sSql .= "          inner join cgm as medico       on medico.z01_numcgm            = medicos.sd03_i_cgm";
    $sSql .= "          inner join unidademedicos      on unidademedicos.sd04_i_medico = medicos.sd03_i_codigo";
    $sSql .= "          inner join especmedico         on especmedico.sd27_i_undmed    = unidademedicos.sd04_i_codigo";
    $sSql .= "          inner join rhcbo               on rhcbo.rh70_sequencial        = especmedico.sd27_i_rhcbo";
    $sSql .= "          inner join unidades            on unidades.sd02_i_codigo       = unidademedicos.sd04_i_unidade";
    $sSql .= "          inner join cgm as unidade      on unidade.z01_numcgm           = unidades.sd02_i_numcgm";
    $sSql .= "        union all";
    $sSql .= "         select sd03_i_crm as crm,  ";
    $sSql .= "                medicos.sd03_i_codigo as codigo_medico,";
    $sSql .= "                s154_c_nome as nome,";
    $sSql .= "                rhcbo.rh70_sequencial  as codigo_cbo,";
    $sSql .= "                rhcbo.rh70_estrutural  as estrutura_cbo,";
    $sSql .= "                rhcbo.rh70_descr       as nome_cbo,";
    $sSql .= "                null as codigo_unidade, ";
    $sSql .= "                null as unidade,";
    $sSql .= "                null as situacao";
    $sSql .= "          from sau_medicosforarede";
    $sSql .= "         inner join medicos on medicos.sd03_i_codigo = sau_medicosforarede.s154_i_medico";
    $sSql .= "         inner join rhcbo   on rhcbo.rh70_sequencial = sau_medicosforarede.s154_rhcbo";
    $sSql .= "         ) as x ";

    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere} ";
    }
    if (!empty($sOrder)) {
      $sSql .= " order by {$sOrder} ";
    }
    return $sSql;
  }

  /**
   * Retorna os médicos cadastrado no sistema com uma flag para saber se o médico é da rede ou de fora
   * @param string $sOrder
   * @param string $sWhere
   * @return string
   */
  function sql_query_medicos ( $sOrder = null, $sWhere = null) {

    $sSql  = "select * ";
    $sSql .= "  from ( select sd03_i_crm            as crm,  ";
    $sSql .= "                medicos.sd03_i_codigo as codigo_medico,";
    $sSql .= "                medico.z01_nome       as nome,";
    $sSql .= "                true                  as medico_rede,";
    $sSql .= "                z02_i_cns             as cns,";
    $sSql .= "                z01_numcgm            as cgm";
    $sSql .= "           from medicos";
    $sSql .= "          inner join cgm    as medico     on medico.z01_numcgm    = medicos.sd03_i_cgm";
    $sSql .= "          left  join cgmdoc as medico_doc on medico_doc.z02_i_cgm = medico.z01_numcgm";
    $sSql .= "        union all";
    $sSql .= "         select sd03_i_crm            as crm,  ";
    $sSql .= "                medicos.sd03_i_codigo as codigo_medico,";
    $sSql .= "                s154_c_nome           as nome,";
    $sSql .= "                false                 as medico_rede,";
    $sSql .= "                ''                    as cns,";
    $sSql .= "                null                  as cgm";
    $sSql .= "          from sau_medicosforarede";
    $sSql .= "         inner join medicos on medicos.sd03_i_codigo = sau_medicosforarede.s154_i_medico";
    $sSql .= "         inner join rhcbo   on rhcbo.rh70_sequencial = sau_medicosforarede.s154_rhcbo";
    $sSql .= "         ) as x ";

    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere} ";
    }
    if (!empty($sOrder)) {
      $sSql .= " order by {$sOrder} ";
    }

    return $sSql;
  }

  /**
   * Query para pesquisar profissionais da saude
   * @param  integer $sd03_i_codigo [description]
   * @param  string $campos        [description]
   * @param  string $ordem         [description]
   * @param  string $dbwhere       [description]
   */
  function sql_query_profissional_saude( $sd03_i_codigo=null, $campos="*", $ordem=null, $dbwhere="" ) {

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
    $sql .= " from medicos ";

    $sql .= " inner join cgm            on cgm.z01_numcgm               = medicos.sd03_i_cgm";
    $sql .= " inner join db_usuacgm     on cgmlogin                     = z01_numcgm ";
    $sql .= " inner join db_usuarios    on db_usuarios.id_usuario       = db_usuacgm.id_usuario ";
    $sql .= " inner join unidademedicos on unidademedicos.sd04_i_medico = medicos.sd03_i_codigo";
    $sql .= " inner join unidades       on unidades.sd02_i_codigo       = unidademedicos.sd04_i_unidade ";

    $sql2 = "";
    if($dbwhere==""){
      if($sd03_i_codigo!=null ){
        $sql2 .= " where medicos.sd03_i_codigo = $sd03_i_codigo ";
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
   * Query para pesquisar profissionais da saude e suas especialidades
   * @param  integer $sd03_i_codigo [description]
   * @param  string $campos        [description]
   * @param  string $ordem         [description]
   * @param  string $dbwhere       [description]
   */
  function sql_query_profissional_saude_especialidade ( $sd03_i_codigo=null, $campos="*", $ordem=null, $dbwhere="" ) {

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
    $sql .= "    from medicos   ";
    $sql .= "   inner join cgm            on cgm.z01_numcgm  = medicos.sd03_i_cgm";
    $sql .= "   inner join db_usuacgm     on cgmlogin        = z01_numcgm ";
    $sql .= "   inner join unidademedicos on sd04_i_medico   = sd03_i_codigo ";
    $sql .= "   inner join especmedico    on sd27_i_undmed   = sd04_i_codigo ";
    $sql .= "   inner join rhcbo          on rh70_sequencial = sd27_i_rhcbo ";

    $sql2 = "";
    if($dbwhere==""){
      if($sd03_i_codigo!=null ){
        $sql2 .= " where medicos.sd03_i_codigo = $sd03_i_codigo ";
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
   * Busca os vínculos do medíco com as unidades e especialidade
   * @return string Sql
   */
  public function sql_query_vinculos( $sd03_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "" ) {

     $sql  = "select {$campos} ";
     $sql .= "  from medicos ";
     $sql .= " inner join cgm              on cgm.z01_numcgm  = medicos.sd03_i_cgm ";
     $sql .= " inner join unidademedicos   on sd04_i_medico   = sd03_i_codigo ";
     $sql .= " inner join especmedico      on sd27_i_undmed   = sd04_i_codigo ";
     $sql .= " inner join rhcbo            on rh70_sequencial = sd27_i_rhcbo ";
     $sql .= "  left join sau_orgaoemissor on sd51_i_codigo   = sd04_i_orgaoemissor ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($sd03_i_codigo)){
         $sql2 .= "  where medicos.sd03_i_codigo = = $sd03_i_codigo ";
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
?>