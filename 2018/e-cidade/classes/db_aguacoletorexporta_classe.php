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

//MODULO: Agua
//CLASSE DA ENTIDADE aguacoletorexporta
class cl_aguacoletorexporta {

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
   var $x49_sequencial = 0;
   var $x49_aguacoletor = 0;
   var $x49_instit = 0;
   var $x49_anousu = 0;
   var $x49_mesusu = 0;
   var $x49_situacao = 0;
   var $x49_db_layouttxt = 'null';
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 x49_sequencial = int4 = Código Exportação
                 x49_aguacoletor = int4 = Código Coletor
                 x49_instit = int4 = Cod. Instituição
                 x49_anousu = int4 = Ano Exportacao
                 x49_mesusu = int4 = Mês Exportação
                 x49_situacao = int4 = Situação da Exportação
                 x49_db_layouttxt = int4 = Layout
                 ";
   //funcao construtor da classe
   function cl_aguacoletorexporta() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("aguacoletorexporta");
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
       $this->x49_sequencial = ($this->x49_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["x49_sequencial"]:$this->x49_sequencial);
       $this->x49_aguacoletor = ($this->x49_aguacoletor == ""?@$GLOBALS["HTTP_POST_VARS"]["x49_aguacoletor"]:$this->x49_aguacoletor);
       $this->x49_instit = ($this->x49_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["x49_instit"]:$this->x49_instit);
       $this->x49_anousu = ($this->x49_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["x49_anousu"]:$this->x49_anousu);
       $this->x49_mesusu = ($this->x49_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["x49_mesusu"]:$this->x49_mesusu);
       $this->x49_situacao = ($this->x49_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["x49_situacao"]:$this->x49_situacao);
       $this->x49_db_layouttxt = ($this->x49_db_layouttxt == ""?@$GLOBALS["HTTP_POST_VARS"]["x49_db_layouttxt"]:$this->x49_db_layouttxt);
     }else{
       $this->x49_sequencial = ($this->x49_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["x49_sequencial"]:$this->x49_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($x49_sequencial){
      $this->atualizacampos();
     if($this->x49_aguacoletor == null ){
       $this->erro_sql = " Campo Código Coletor não informado.";
       $this->erro_campo = "x49_aguacoletor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x49_instit == null ){
       $this->erro_sql = " Campo Cod. Instituição não informado.";
       $this->erro_campo = "x49_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x49_anousu == null ){
       $this->erro_sql = " Campo Ano Exportacao não informado.";
       $this->erro_campo = "x49_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x49_mesusu == null ){
       $this->erro_sql = " Campo Mês Exportação não informado.";
       $this->erro_campo = "x49_mesusu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x49_situacao == null ){
       $this->erro_sql = " Campo Situação da Exportação não informado.";
       $this->erro_campo = "x49_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($x49_sequencial == "" || $x49_sequencial == null ){
       $result = db_query("select nextval('aguacoletorexporta_x49_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: aguacoletorexporta_x49_sequencial_seq do campo: x49_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->x49_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from aguacoletorexporta_x49_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $x49_sequencial)){
         $this->erro_sql = " Campo x49_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->x49_sequencial = $x49_sequencial;
       }
     }
     if(($this->x49_sequencial == null) || ($this->x49_sequencial == "") ){
       $this->erro_sql = " Campo x49_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into aguacoletorexporta(
                                       x49_sequencial
                                      ,x49_aguacoletor
                                      ,x49_instit
                                      ,x49_anousu
                                      ,x49_mesusu
                                      ,x49_situacao
                                      ,x49_db_layouttxt
                       )
                values (
                                $this->x49_sequencial
                               ,$this->x49_aguacoletor
                               ,$this->x49_instit
                               ,$this->x49_anousu
                               ,$this->x49_mesusu
                               ,$this->x49_situacao
                               ,$this->x49_db_layouttxt
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Agua Coletor Exporta ($this->x49_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Agua Coletor Exporta já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Agua Coletor Exporta ($this->x49_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->x49_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->x49_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15346,'$this->x49_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,2701,15346,'','".AddSlashes(pg_result($resaco,0,'x49_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2701,15347,'','".AddSlashes(pg_result($resaco,0,'x49_aguacoletor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2701,15348,'','".AddSlashes(pg_result($resaco,0,'x49_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2701,15349,'','".AddSlashes(pg_result($resaco,0,'x49_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2701,15350,'','".AddSlashes(pg_result($resaco,0,'x49_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2701,15351,'','".AddSlashes(pg_result($resaco,0,'x49_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2701,22178,'','".AddSlashes(pg_result($resaco,0,'x49_db_layouttxt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($x49_sequencial=null) {
      $this->atualizacampos();
     $sql = " update aguacoletorexporta set ";
     $virgula = "";
     if(trim($this->x49_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x49_sequencial"])){
       $sql  .= $virgula." x49_sequencial = $this->x49_sequencial ";
       $virgula = ",";
       if(trim($this->x49_sequencial) == null ){
         $this->erro_sql = " Campo Código Exportação não informado.";
         $this->erro_campo = "x49_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x49_aguacoletor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x49_aguacoletor"])){
       $sql  .= $virgula." x49_aguacoletor = $this->x49_aguacoletor ";
       $virgula = ",";
       if(trim($this->x49_aguacoletor) == null ){
         $this->erro_sql = " Campo Código Coletor não informado.";
         $this->erro_campo = "x49_aguacoletor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x49_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x49_instit"])){
       $sql  .= $virgula." x49_instit = $this->x49_instit ";
       $virgula = ",";
       if(trim($this->x49_instit) == null ){
         $this->erro_sql = " Campo Cod. Instituição não informado.";
         $this->erro_campo = "x49_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x49_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x49_anousu"])){
       $sql  .= $virgula." x49_anousu = $this->x49_anousu ";
       $virgula = ",";
       if(trim($this->x49_anousu) == null ){
         $this->erro_sql = " Campo Ano Exportacao não informado.";
         $this->erro_campo = "x49_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x49_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x49_mesusu"])){
       $sql  .= $virgula." x49_mesusu = $this->x49_mesusu ";
       $virgula = ",";
       if(trim($this->x49_mesusu) == null ){
         $this->erro_sql = " Campo Mês Exportação não informado.";
         $this->erro_campo = "x49_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x49_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x49_situacao"])){
       $sql  .= $virgula." x49_situacao = $this->x49_situacao ";
       $virgula = ",";
       if(trim($this->x49_situacao) == null ){
         $this->erro_sql = " Campo Situação da Exportação não informado.";
         $this->erro_campo = "x49_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x49_db_layouttxt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x49_db_layouttxt"])){
        if(trim($this->x49_db_layouttxt)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x49_db_layouttxt"])){
           $this->x49_db_layouttxt = "0" ;
        }
       $sql  .= $virgula." x49_db_layouttxt = $this->x49_db_layouttxt ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($x49_sequencial!=null){
       $sql .= " x49_sequencial = $this->x49_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->x49_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,15346,'$this->x49_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x49_sequencial"]) || $this->x49_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,2701,15346,'".AddSlashes(pg_result($resaco,$conresaco,'x49_sequencial'))."','$this->x49_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x49_aguacoletor"]) || $this->x49_aguacoletor != "")
             $resac = db_query("insert into db_acount values($acount,2701,15347,'".AddSlashes(pg_result($resaco,$conresaco,'x49_aguacoletor'))."','$this->x49_aguacoletor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x49_instit"]) || $this->x49_instit != "")
             $resac = db_query("insert into db_acount values($acount,2701,15348,'".AddSlashes(pg_result($resaco,$conresaco,'x49_instit'))."','$this->x49_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x49_anousu"]) || $this->x49_anousu != "")
             $resac = db_query("insert into db_acount values($acount,2701,15349,'".AddSlashes(pg_result($resaco,$conresaco,'x49_anousu'))."','$this->x49_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x49_mesusu"]) || $this->x49_mesusu != "")
             $resac = db_query("insert into db_acount values($acount,2701,15350,'".AddSlashes(pg_result($resaco,$conresaco,'x49_mesusu'))."','$this->x49_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x49_situacao"]) || $this->x49_situacao != "")
             $resac = db_query("insert into db_acount values($acount,2701,15351,'".AddSlashes(pg_result($resaco,$conresaco,'x49_situacao'))."','$this->x49_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x49_db_layouttxt"]) || $this->x49_db_layouttxt != "")
             $resac = db_query("insert into db_acount values($acount,2701,22178,'".AddSlashes(pg_result($resaco,$conresaco,'x49_db_layouttxt'))."','$this->x49_db_layouttxt',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Agua Coletor Exporta não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->x49_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Agua Coletor Exporta não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->x49_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->x49_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }

   // funcao para exclusao
   public function excluir ($x49_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($x49_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,15346,'$x49_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,2701,15346,'','".AddSlashes(pg_result($resaco,$iresaco,'x49_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2701,15347,'','".AddSlashes(pg_result($resaco,$iresaco,'x49_aguacoletor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2701,15348,'','".AddSlashes(pg_result($resaco,$iresaco,'x49_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2701,15349,'','".AddSlashes(pg_result($resaco,$iresaco,'x49_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2701,15350,'','".AddSlashes(pg_result($resaco,$iresaco,'x49_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2701,15351,'','".AddSlashes(pg_result($resaco,$iresaco,'x49_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2701,22178,'','".AddSlashes(pg_result($resaco,$iresaco,'x49_db_layouttxt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from aguacoletorexporta
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($x49_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " x49_sequencial = $x49_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Agua Coletor Exporta não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$x49_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Agua Coletor Exporta não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$x49_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$x49_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:aguacoletorexporta";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($x49_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from aguacoletorexporta ";
     $sql .= "      inner join db_config         on db_config.codigo = aguacoletorexporta.x49_instit ";
     $sql .= "      inner join aguacoletor       on aguacoletor.x46_sequencial = aguacoletorexporta.x49_aguacoletor ";
     $sql .= "      inner join cgm               on cgm.z01_numcgm = db_config.numcgm ";
     $sql .= "      inner join db_tipoinstit     on db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit ";
     $sql .= "      left  join db_layouttxt      on db_layouttxt.db50_codigo = aguacoletorexporta.x49_db_layouttxt ";
     $sql .= "      left join db_layouttxtgrupo  on db_layouttxtgrupo.db56_sequencial = db_layouttxt.db50_layouttxtgrupo ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($x49_sequencial)) {
         $sql2 .= " where aguacoletorexporta.x49_sequencial = $x49_sequencial ";
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
   public function sql_query_file ($x49_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from aguacoletorexporta ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($x49_sequencial)){
         $sql2 .= " where aguacoletorexporta.x49_sequencial = $x49_sequencial ";
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

   function sql_query_status_leitura($x49_sequencial=null, $campos="*", $ordem=null, $dbwhere = "" ) {

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

    $sql .= " from aguacoletorexporta ";
    $sql .= "     inner join aguacoletorexportadados on x49_sequencial = x50_aguacoletorexporta";
    $sql2 = "";
    if($dbwhere == "") {
      if($x49_sequencial != "") {
        $sql2 = " where aguacoletorexporta.x49_sequencial = $x49_sequencial ";
      }
    }else {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if($ordem != null) {
      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++) {
        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ", ";
      }
    }
    return $sql;

  }
}
