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

//MODULO: compras
//CLASSE DA ENTIDADE pcorcamforne
class cl_pcorcamforne {
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
   var $pc21_orcamforne = 0;
   var $pc21_codorc = 0;
   var $pc21_numcgm = 0;
   var $pc21_importado = 'f';
   var $pc21_prazoent_dia = null;
   var $pc21_prazoent_mes = null;
   var $pc21_prazoent_ano = null;
   var $pc21_prazoent = null;
   var $pc21_validadorc_dia = null;
   var $pc21_validadorc_mes = null;
   var $pc21_validadorc_ano = null;
   var $pc21_validadorc = null;
   var $pc21_taxaestimadaglobal = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 pc21_orcamforne = int8 = Código do orcamento deste fornecedor
                 pc21_codorc = int4 = Código do orçamento
                 pc21_numcgm = int4 = Numcgm
                 pc21_importado = bool = Importado
                 pc21_prazoent = date = Prazo de Entrega
                 pc21_validadorc = date = Validade do Orçamento
                 pc21_taxaestimadaglobal = float4 = Taxa Estimada Global 
                 ";
   //funcao construtor da classe
   function cl_pcorcamforne() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pcorcamforne");
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
       $this->pc21_orcamforne = ($this->pc21_orcamforne == ""?@$GLOBALS["HTTP_POST_VARS"]["pc21_orcamforne"]:$this->pc21_orcamforne);
       $this->pc21_codorc = ($this->pc21_codorc == ""?@$GLOBALS["HTTP_POST_VARS"]["pc21_codorc"]:$this->pc21_codorc);
       $this->pc21_numcgm = ($this->pc21_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["pc21_numcgm"]:$this->pc21_numcgm);
       $this->pc21_importado = ($this->pc21_importado == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc21_importado"]:$this->pc21_importado);
       if($this->pc21_prazoent == ""){
         $this->pc21_prazoent_dia = ($this->pc21_prazoent_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc21_prazoent_dia"]:$this->pc21_prazoent_dia);
         $this->pc21_prazoent_mes = ($this->pc21_prazoent_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["pc21_prazoent_mes"]:$this->pc21_prazoent_mes);
         $this->pc21_prazoent_ano = ($this->pc21_prazoent_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["pc21_prazoent_ano"]:$this->pc21_prazoent_ano);
         if($this->pc21_prazoent_dia != ""){
            $this->pc21_prazoent = $this->pc21_prazoent_ano."-".$this->pc21_prazoent_mes."-".$this->pc21_prazoent_dia;
         }
       }
       if($this->pc21_validadorc == ""){
         $this->pc21_validadorc_dia = ($this->pc21_validadorc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc21_validadorc_dia"]:$this->pc21_validadorc_dia);
         $this->pc21_validadorc_mes = ($this->pc21_validadorc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["pc21_validadorc_mes"]:$this->pc21_validadorc_mes);
         $this->pc21_validadorc_ano = ($this->pc21_validadorc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["pc21_validadorc_ano"]:$this->pc21_validadorc_ano);
         if($this->pc21_validadorc_dia != ""){
            $this->pc21_validadorc = $this->pc21_validadorc_ano."-".$this->pc21_validadorc_mes."-".$this->pc21_validadorc_dia;
         }
       }
       $this->pc21_taxaestimadaglobal = ($this->pc21_taxaestimadaglobal == ""?@$GLOBALS["HTTP_POST_VARS"]["pc21_taxaestimadaglobal"]:$this->pc21_taxaestimadaglobal);
     }else{
       $this->pc21_orcamforne = ($this->pc21_orcamforne == ""?@$GLOBALS["HTTP_POST_VARS"]["pc21_orcamforne"]:$this->pc21_orcamforne);
     }
   }
   // funcao para Inclusão
   function incluir ($pc21_orcamforne){
      $this->atualizacampos();
     if($this->pc21_codorc == null ){
       $this->erro_sql = " Campo Código do Orçamento não informado.";
       $this->erro_campo = "pc21_codorc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc21_numcgm == null ){
       $this->erro_sql = " Campo Numcgm não informado.";
       $this->erro_campo = "pc21_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc21_importado == null ){
       $this->erro_sql = " Campo Importado não informado.";
       $this->erro_campo = "pc21_importado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc21_prazoent == null ){
       $this->pc21_prazoent = "null";
     }
     if($this->pc21_validadorc == null ){
       $this->pc21_validadorc = "null";
     }
     if($this->pc21_taxaestimadaglobal == null ){
       $this->pc21_taxaestimadaglobal = "null";
     }
     if($pc21_orcamforne == "" || $pc21_orcamforne == null ){
       $result = db_query("select nextval('pcorcamforne_pc21_orcamforne_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: pcorcamforne_pc21_orcamforne_seq do campo: pc21_orcamforne";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->pc21_orcamforne = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from pcorcamforne_pc21_orcamforne_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc21_orcamforne)){
         $this->erro_sql = " Campo pc21_orcamforne maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc21_orcamforne = $pc21_orcamforne;
       }
     }
     if(($this->pc21_orcamforne == null) || ($this->pc21_orcamforne == "") ){ 
       $this->erro_sql = " Campo pc21_orcamforne não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pcorcamforne(
                                       pc21_orcamforne
                                      ,pc21_codorc
                                      ,pc21_numcgm
                                      ,pc21_importado
                                      ,pc21_prazoent
                                      ,pc21_validadorc
                                      ,pc21_taxaestimadaglobal
                       )
                values (
                                $this->pc21_orcamforne
                               ,$this->pc21_codorc
                               ,$this->pc21_numcgm
                               ,'$this->pc21_importado'
                               ,".($this->pc21_prazoent == "null" || $this->pc21_prazoent == ""?"null":"'".$this->pc21_prazoent."'")."
                               ,".($this->pc21_validadorc == "null" || $this->pc21_validadorc == ""?"null":"'".$this->pc21_validadorc."'")."
                               ,$this->pc21_taxaestimadaglobal
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Fornecedores do orçamento ($this->pc21_orcamforne) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Fornecedores do orçamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Fornecedores do orçamento ($this->pc21_orcamforne) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->pc21_orcamforne;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

     $resaco = $this->sql_record($this->sql_query_file($this->pc21_orcamforne));
     if(($resaco!=false)||($this->numrows!=0)){

       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6377,'$this->pc21_orcamforne','I')");
       $resac = db_query("insert into db_acount values($acount,858,6377,'','".AddSlashes(pg_result($resaco,0,'pc21_orcamforne'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,858,5512,'','".AddSlashes(pg_result($resaco,0,'pc21_codorc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,858,5513,'','".AddSlashes(pg_result($resaco,0,'pc21_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,858,6760,'','".AddSlashes(pg_result($resaco,0,'pc21_importado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,858,9204,'','".AddSlashes(pg_result($resaco,0,'pc21_prazoent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,858,9203,'','".AddSlashes(pg_result($resaco,0,'pc21_validadorc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,858,1009491,'','".AddSlashes(pg_result($resaco,0,'pc21_taxaestimadaglobal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($pc21_orcamforne=null) {
      $this->atualizacampos();
     $sql = " update pcorcamforne set ";
     $virgula = "";
     if(trim($this->pc21_orcamforne)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc21_orcamforne"])){
       $sql  .= $virgula." pc21_orcamforne = $this->pc21_orcamforne ";
       $virgula = ",";
       if(trim($this->pc21_orcamforne) == null ){
         $this->erro_sql = " Campo Código do orcamento deste fornecedor não informado.";
         $this->erro_campo = "pc21_orcamforne";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc21_codorc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc21_codorc"])){
       $sql  .= $virgula." pc21_codorc = $this->pc21_codorc ";
       $virgula = ",";
       if(trim($this->pc21_codorc) == null ){
         $this->erro_sql = " Campo Código do Orçamento não informado.";
         $this->erro_campo = "pc21_codorc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc21_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc21_numcgm"])){
       $sql  .= $virgula." pc21_numcgm = $this->pc21_numcgm ";
       $virgula = ",";
       if(trim($this->pc21_numcgm) == null ){
         $this->erro_sql = " Campo Numcgm não informado.";
         $this->erro_campo = "pc21_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc21_importado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc21_importado"])){
       $sql  .= $virgula." pc21_importado = '$this->pc21_importado' ";
       $virgula = ",";
       if(trim($this->pc21_importado) == null ){
         $this->erro_sql = " Campo Importado não informado.";
         $this->erro_campo = "pc21_importado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc21_prazoent)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc21_prazoent_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["pc21_prazoent_dia"] !="") ){
       $sql  .= $virgula." pc21_prazoent = '$this->pc21_prazoent' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["pc21_prazoent_dia"])){
         $sql  .= $virgula." pc21_prazoent = null ";
         $virgula = ",";
       }
     }
     if(trim($this->pc21_validadorc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc21_validadorc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["pc21_validadorc_dia"] !="") ){
       $sql  .= $virgula." pc21_validadorc = '$this->pc21_validadorc' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["pc21_validadorc_dia"])){
         $sql  .= $virgula." pc21_validadorc = null ";
         $virgula = ",";
       }
     }
     if(trim($this->pc21_taxaestimadaglobal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc21_taxaestimadaglobal"])){ 
        if(trim($this->pc21_taxaestimadaglobal)=="" && isset($GLOBALS["HTTP_POST_VARS"]["pc21_taxaestimadaglobal"])){ 
           $this->pc21_taxaestimadaglobal = "null" ; 
        } 
       $sql  .= $virgula." pc21_taxaestimadaglobal = $this->pc21_taxaestimadaglobal ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($pc21_orcamforne!=null){
       $sql .= " pc21_orcamforne = $this->pc21_orcamforne";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

     $resaco = $this->sql_record($this->sql_query_file($this->pc21_orcamforne));
     if ($this->numrows > 0) {

       for($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6377,'$this->pc21_orcamforne','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc21_orcamforne"]) || $this->pc21_orcamforne != "")
           $resac = db_query("insert into db_acount values($acount,858,6377,'".AddSlashes(pg_result($resaco,$conresaco,'pc21_orcamforne'))."','$this->pc21_orcamforne',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if (isset($GLOBALS["HTTP_POST_VARS"]["pc21_codorc"]) || $this->pc21_codorc != "")
             $resac = db_query("insert into db_acount values($acount,858,5512,'".AddSlashes(pg_result($resaco,$conresaco,'pc21_codorc'))."','$this->pc21_codorc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pc21_numcgm"]) || $this->pc21_numcgm != "")
             $resac = db_query("insert into db_acount values($acount,858,5513,'".AddSlashes(pg_result($resaco,$conresaco,'pc21_numcgm'))."','$this->pc21_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pc21_importado"]) || $this->pc21_importado != "")
             $resac = db_query("insert into db_acount values($acount,858,6760,'".AddSlashes(pg_result($resaco,$conresaco,'pc21_importado'))."','$this->pc21_importado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pc21_prazoent"]) || $this->pc21_prazoent != "")
             $resac = db_query("insert into db_acount values($acount,858,9204,'".AddSlashes(pg_result($resaco,$conresaco,'pc21_prazoent'))."','$this->pc21_prazoent',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pc21_validadorc"]) || $this->pc21_validadorc != "")
             $resac = db_query("insert into db_acount values($acount,858,9203,'".AddSlashes(pg_result($resaco,$conresaco,'pc21_validadorc'))."','$this->pc21_validadorc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pc21_taxaestimadaglobal"]) || $this->pc21_taxaestimadaglobal != "")
             $resac = db_query("insert into db_acount values($acount,858,1009491,'".AddSlashes(pg_result($resaco,$conresaco,'pc21_taxaestimadaglobal'))."','$this->pc21_taxaestimadaglobal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Fornecedores do orçamento não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc21_orcamforne;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Fornecedores do orçamento não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc21_orcamforne;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->pc21_orcamforne;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($pc21_orcamforne=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($pc21_orcamforne));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,6377,'$pc21_orcamforne','E')");
           $resac  = db_query("insert into db_acount values($acount,858,6377,'','".AddSlashes(pg_result($resaco,$iresaco,'pc21_orcamforne'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,858,5512,'','".AddSlashes(pg_result($resaco,$iresaco,'pc21_codorc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,858,5513,'','".AddSlashes(pg_result($resaco,$iresaco,'pc21_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,858,6760,'','".AddSlashes(pg_result($resaco,$iresaco,'pc21_importado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,858,9204,'','".AddSlashes(pg_result($resaco,$iresaco,'pc21_prazoent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,858,9203,'','".AddSlashes(pg_result($resaco,$iresaco,'pc21_validadorc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,858,1009491,'','".AddSlashes(pg_result($resaco,$iresaco,'pc21_taxaestimadaglobal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from pcorcamforne
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($pc21_orcamforne)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " pc21_orcamforne = $pc21_orcamforne ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Fornecedores do orçamento não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc21_orcamforne;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Fornecedores do orçamento não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc21_orcamforne;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$pc21_orcamforne;
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
        $this->erro_sql   = "Record Vazio na Tabela:pcorcamforne";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($pc21_orcamforne = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from pcorcamforne ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = pcorcamforne.pc21_numcgm";
     $sql .= "      inner join pcorcam  on  pcorcam.pc20_codorc = pcorcamforne.pc21_codorc";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($pc21_orcamforne)) {
         $sql2 .= " where pcorcamforne.pc21_orcamforne = $pc21_orcamforne "; 
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
   public function sql_query_file ($pc21_orcamforne = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from pcorcamforne ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($pc21_orcamforne)){
         $sql2 .= " where pcorcamforne.pc21_orcamforne = $pc21_orcamforne "; 
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

   function sql_query_fornec ( $pc21_orcamforne=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcorcamforne ";
     $sql .= "      inner join cgm           on cgm.z01_numcgm                = pcorcamforne.pc21_numcgm";
     $sql .= "      inner join pcorcamitem   on pcorcamitem.pc22_codorc       = pcorcamforne.pc21_codorc";
     $sql .= "      inner join pcorcam       on pcorcam.pc20_codorc           = pcorcamforne.pc21_codorc";
     $sql .= "      left  join pcorcamval    on pcorcamval.pc23_orcamitem     = pcorcamitem.pc22_orcamitem and";
     $sql .= "                                  pcorcamval.pc23_orcamforne    = pcorcamforne.pc21_orcamforne";
     $sql .= "      left join pcorcamdescla  on pcorcamdescla.pc32_orcamforne = pcorcamforne.pc21_orcamforne and";
     $sql .= "                                  pcorcamdescla.pc32_orcamitem  = pcorcamitem.pc22_orcamitem";
     $sql .= "      left join pcorcamitemlic on pcorcamitemlic.pc26_orcamitem = pcorcamitem.pc22_orcamitem";
     $sql .= "      left join liclicitemlote on liclicitemlote.l04_liclicitem = pcorcamitemlic.pc26_liclicitem";
     $sql2 = "";
     if($dbwhere==""){
       if($pc21_orcamforne!=null ){
         $sql2 .= " where pcorcamforne.pc21_orcamforne = $pc21_orcamforne ";
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
   function sql_query_orcitem ( $pc21_orcamforne=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from pcorcamforne ";
    $sql .= "      inner join pcorcam  on  pcorcam.pc20_codorc = pcorcamforne.pc21_codorc";
    $sql .= "      inner join pcorcamitem  on  pcorcamitem.pc22_codorc = pcorcamforne.pc21_codorc";
    $sql2 = "";
    if($dbwhere==""){
      if($pc21_orcamforne!=null ){
        $sql2 .= " where pcorcamforne.pc21_orcamforne = $pc21_orcamforne ";
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
   function sql_query_solsugforne ( $pc21_orcamforne=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcorcamforne ";
     $sql .= "      inner join pcorcamitem  on  pcorcamitem.pc22_codorc = pcorcamforne.pc21_codorc";
     $sql .= "      inner join pcorcamitemsol  on  pcorcamitemsol.pc29_orcamitem = pcorcamitem.pc22_orcamitem";
     $sql .= "      inner join solicitem  on  solicitem.pc11_codigo = pcorcamitemsol.pc29_solicitem";
     $sql .= "      inner join pcsugforn  on  pcsugforn.pc40_solic = solicitem.pc11_numero";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = pcsugforn.pc40_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($pc21_orcamforne!=null ){
         $sql2 .= " where pcorcamforne.pc21_orcamforne = $pc21_orcamforne ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       }
     return $sql;
  }

  /**
   * Retorna uma string sql com inner join para pcforneclic
   *
   * @param integer $pc21_orcamforne codigo do fornecedor
   * @param string $campos lista de campo
   * @param string $ordem  lista de campos para order by
   * @param string $dbwhere fitro de selecao
   * @return string
   */
  function sql_query_forneclic ($pc21_orcamforne=null,$campos="*",$ordem=null,$dbwhere="") {

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
    $sql .= " from pcorcamforne ";
    $sql .= "      inner join cgm                  on cgm.z01_numcgm                = pcorcamforne.pc21_numcgm";
    $sql .= "      inner join pcorcam              on  pcorcam.pc20_codorc          = pcorcamforne.pc21_codorc";
    $sql .= "      inner join pcorcamfornelic      on  pcorcamforne.pc21_orcamforne = pcorcamfornelic.pc31_orcamforne";
    $sql .= "      inner join liclicitatipoempresa on  pc31_liclicitatipoempresa    = l32_sequencial";
    $sql2 = "";
    if($dbwhere==""){
      if($pc21_orcamforne!=null ){
        $sql2 .= " where pcorcamforne.pc21_orcamforne = $pc21_orcamforne ";
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
   * @param string $sCampos
   * @param string $sOrdem
   * @param string $sWhere
   */
  public function sql_query_fornecedor_responsavel($sCampos = "*", $sOrdem = null, $sWhere = "") {

    $sSql  = " select {$sCampos}                                                                                \n";
    $sSql .= "   from pcorcamforne                                                                              \n";
    $sSql .= "        inner join cgm              on cgm.z01_numcgm          = pcorcamforne.pc21_numcgm         \n";
    $sSql .= "        inner join pcorcamitem      on pcorcamitem.pc22_codorc = pcorcamforne.pc21_codorc         \n";
    $sSql .= "        inner join pcorcam          on pcorcam.pc20_codorc     = pcorcamforne.pc21_codorc         \n";
    $sSql .= "        left  join pcforne          on pcforne.pc60_numcgm     = pcorcamforne.pc21_numcgm         \n";
    $sSql .= "        left  join pcfornereprlegal on pcfornereprlegal.pc81_cgmforn = pcforne.pc60_numcgm        \n";
    $sSql .= "        left  join pcfornecon       on pcfornecon.pc63_numcgm        = pcforne.pc60_numcgm        \n";
    $sSql .= "        left  join pcforneconpad    on pcforneconpad.pc64_contabanco = pcfornecon.pc63_contabanco \n";
    $sSql .= "        left  join db_bancos        on db_bancos.db90_codban         = pcfornecon.pc63_banco      \n";

    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere} \n";
    }

    if (!empty($sOrdem)) {
      $sSql .= " order by {$sOrdem} ";
    }

    return $sSql;
  }
}
