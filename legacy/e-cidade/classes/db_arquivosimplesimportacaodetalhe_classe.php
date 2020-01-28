<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: issqn
//CLASSE DA ENTIDADE arquivosimplesimportacaodetalhe
class cl_arquivosimplesimportacaodetalhe {
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
   var $q142_sequencial = 0;
   var $q142_arquivosimplesimportacao = 0;
   var $q142_cnpj = null;
   var $q142_cnae = null;
   var $q142_apto = 'f';
   var $q142_observacao = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 q142_sequencial = int4 = Código sequencial
                 q142_arquivosimplesimportacao = int4 = Arquivo simples importação
                 q142_cnpj = varchar(14) = CNPJ
                 q142_cnae = varchar(20) = CNAE
                 q142_apto = bool = Apto
                 q142_observacao = text = Observação
                 ";
   //funcao construtor da classe
   function cl_arquivosimplesimportacaodetalhe() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("arquivosimplesimportacaodetalhe");
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
       $this->q142_sequencial = ($this->q142_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q142_sequencial"]:$this->q142_sequencial);
       $this->q142_arquivosimplesimportacao = ($this->q142_arquivosimplesimportacao == ""?@$GLOBALS["HTTP_POST_VARS"]["q142_arquivosimplesimportacao"]:$this->q142_arquivosimplesimportacao);
       $this->q142_cnpj = ($this->q142_cnpj == ""?@$GLOBALS["HTTP_POST_VARS"]["q142_cnpj"]:$this->q142_cnpj);
       $this->q142_cnae = ($this->q142_cnae == ""?@$GLOBALS["HTTP_POST_VARS"]["q142_cnae"]:$this->q142_cnae);
       $this->q142_apto = ($this->q142_apto == "f"?@$GLOBALS["HTTP_POST_VARS"]["q142_apto"]:$this->q142_apto);
       $this->q142_observacao = ($this->q142_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["q142_observacao"]:$this->q142_observacao);
     }else{
       $this->q142_sequencial = ($this->q142_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q142_sequencial"]:$this->q142_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q142_sequencial){
      $this->atualizacampos();
     if($this->q142_arquivosimplesimportacao == null ){
       $this->erro_sql = " Campo Arquivo simples importação não informado.";
       $this->erro_campo = "q142_arquivosimplesimportacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q142_cnpj == null ){
       $this->erro_sql = " Campo CNPJ não informado.";
       $this->erro_campo = "q142_cnpj";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q142_cnae == null ){
       $this->erro_sql = " Campo CNAE não informado.";
       $this->erro_campo = "q142_cnae";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q142_apto == null ){
       $this->erro_sql = " Campo Apto não informado.";
       $this->erro_campo = "q142_apto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q142_sequencial == "" || $q142_sequencial == null ){
       $result = db_query("select nextval('arquivosimplesimportacaodetalhe_q142_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: arquivosimplesimportacaodetalhe_q142_sequencial_seq do campo: q142_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->q142_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from arquivosimplesimportacaodetalhe_q142_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q142_sequencial)){
         $this->erro_sql = " Campo q142_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q142_sequencial = $q142_sequencial;
       }
     }
     if(($this->q142_sequencial == null) || ($this->q142_sequencial == "") ){
       $this->erro_sql = " Campo q142_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into arquivosimplesimportacaodetalhe(
                                       q142_sequencial
                                      ,q142_arquivosimplesimportacao
                                      ,q142_cnpj
                                      ,q142_cnae
                                      ,q142_apto
                                      ,q142_observacao
                       )
                values (
                                $this->q142_sequencial
                               ,$this->q142_arquivosimplesimportacao
                               ,'$this->q142_cnpj'
                               ,'$this->q142_cnae'
                               ,'$this->q142_apto'
                               ,'$this->q142_observacao'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "arquivosimplesimportacaodetalhe ($this->q142_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "arquivosimplesimportacaodetalhe já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "arquivosimplesimportacaodetalhe ($this->q142_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q142_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->q142_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20330,'$this->q142_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3654,20330,'','".AddSlashes(pg_result($resaco,0,'q142_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3654,20331,'','".AddSlashes(pg_result($resaco,0,'q142_arquivosimplesimportacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3654,20332,'','".AddSlashes(pg_result($resaco,0,'q142_cnpj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3654,20333,'','".AddSlashes(pg_result($resaco,0,'q142_cnae'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3654,20334,'','".AddSlashes(pg_result($resaco,0,'q142_apto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3654,20335,'','".AddSlashes(pg_result($resaco,0,'q142_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($q142_sequencial=null) {
      $this->atualizacampos();
     $sql = " update arquivosimplesimportacaodetalhe set ";
     $virgula = "";
     if(trim($this->q142_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q142_sequencial"])){
       $sql  .= $virgula." q142_sequencial = $this->q142_sequencial ";
       $virgula = ",";
       if(trim($this->q142_sequencial) == null ){
         $this->erro_sql = " Campo Código sequencial não informado.";
         $this->erro_campo = "q142_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q142_arquivosimplesimportacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q142_arquivosimplesimportacao"])){
       $sql  .= $virgula." q142_arquivosimplesimportacao = $this->q142_arquivosimplesimportacao ";
       $virgula = ",";
       if(trim($this->q142_arquivosimplesimportacao) == null ){
         $this->erro_sql = " Campo Arquivo simples importação não informado.";
         $this->erro_campo = "q142_arquivosimplesimportacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q142_cnpj)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q142_cnpj"])){
       $sql  .= $virgula." q142_cnpj = '$this->q142_cnpj' ";
       $virgula = ",";
       if(trim($this->q142_cnpj) == null ){
         $this->erro_sql = " Campo CNPJ não informado.";
         $this->erro_campo = "q142_cnpj";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q142_cnae)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q142_cnae"])){
       $sql  .= $virgula." q142_cnae = '$this->q142_cnae' ";
       $virgula = ",";
       if(trim($this->q142_cnae) == null ){
         $this->erro_sql = " Campo CNAE não informado.";
         $this->erro_campo = "q142_cnae";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q142_apto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q142_apto"])){
       $sql  .= $virgula." q142_apto = '$this->q142_apto' ";
       $virgula = ",";
       if(trim($this->q142_apto) == null ){
         $this->erro_sql = " Campo Apto não informado.";
         $this->erro_campo = "q142_apto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     /**
      * Removida validação para alteração da validação automática
      */
     $sql  .= $virgula." q142_observacao = '$this->q142_observacao' ";
     $virgula = ",";
     $sql .= " where ";
     if($q142_sequencial!=null){
       $sql .= " q142_sequencial = $this->q142_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->q142_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20330,'$this->q142_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q142_sequencial"]) || $this->q142_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3654,20330,'".AddSlashes(pg_result($resaco,$conresaco,'q142_sequencial'))."','$this->q142_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q142_arquivosimplesimportacao"]) || $this->q142_arquivosimplesimportacao != "")
             $resac = db_query("insert into db_acount values($acount,3654,20331,'".AddSlashes(pg_result($resaco,$conresaco,'q142_arquivosimplesimportacao'))."','$this->q142_arquivosimplesimportacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q142_cnpj"]) || $this->q142_cnpj != "")
             $resac = db_query("insert into db_acount values($acount,3654,20332,'".AddSlashes(pg_result($resaco,$conresaco,'q142_cnpj'))."','$this->q142_cnpj',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q142_cnae"]) || $this->q142_cnae != "")
             $resac = db_query("insert into db_acount values($acount,3654,20333,'".AddSlashes(pg_result($resaco,$conresaco,'q142_cnae'))."','$this->q142_cnae',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q142_apto"]) || $this->q142_apto != "")
             $resac = db_query("insert into db_acount values($acount,3654,20334,'".AddSlashes(pg_result($resaco,$conresaco,'q142_apto'))."','$this->q142_apto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q142_observacao"]) || $this->q142_observacao != "")
             $resac = db_query("insert into db_acount values($acount,3654,20335,'".AddSlashes(pg_result($resaco,$conresaco,'q142_observacao'))."','$this->q142_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "arquivosimplesimportacaodetalhe nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q142_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "arquivosimplesimportacaodetalhe nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q142_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q142_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($q142_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($q142_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20330,'$q142_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3654,20330,'','".AddSlashes(pg_result($resaco,$iresaco,'q142_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3654,20331,'','".AddSlashes(pg_result($resaco,$iresaco,'q142_arquivosimplesimportacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3654,20332,'','".AddSlashes(pg_result($resaco,$iresaco,'q142_cnpj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3654,20333,'','".AddSlashes(pg_result($resaco,$iresaco,'q142_cnae'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3654,20334,'','".AddSlashes(pg_result($resaco,$iresaco,'q142_apto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3654,20335,'','".AddSlashes(pg_result($resaco,$iresaco,'q142_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from arquivosimplesimportacaodetalhe
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q142_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q142_sequencial = $q142_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "arquivosimplesimportacaodetalhe nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q142_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "arquivosimplesimportacaodetalhe nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q142_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q142_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:arquivosimplesimportacaodetalhe";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $q142_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from arquivosimplesimportacaodetalhe ";
     $sql .= "      inner join arquivosimplesimportacao  on  arquivosimplesimportacao.q64_sequencial = arquivosimplesimportacaodetalhe.q142_arquivosimplesimportacao";
     $sql2 = "";
     if($dbwhere==""){
       if($q142_sequencial!=null ){
         $sql2 .= " where arquivosimplesimportacaodetalhe.q142_sequencial = $q142_sequencial ";
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

  function sql_query_inconsistencias ($q142_sequencial = null, $sCampos = "*", $sOrdem = null, $dbwhere = "") {
    $sql = "select {$sCampos} ";

    $sql .= " from arquivosimplesimportacaodetalhe ";
    $sql .= "      inner join arquivosimplesimportacao on arquivosimplesimportacao.q64_sequencial = arquivosimplesimportacaodetalhe.q142_arquivosimplesimportacao";
    $sql .= "      left join cgm on cgm.z01_cgccpf = arquivosimplesimportacaodetalhe.q142_cnpj ";
    $sql .= "      left join issbase on issbase.q02_numcgm = cgm.z01_numcgm ";

    $sql2 = "";

    if ($dbwhere == "") {
      if ($q142_sequencial != null) {
        $sql2 .= " where arquivosimplesimportacaodetalhe.q142_sequencial = {$q142_sequencial} ";
      }

    } else if ($dbwhere != "") {
      $sql2 = " where {$dbwhere}";
    }

    $sql .= $sql2;

    if ($sOrdem != null ){
      $sql .= " order by {$sOrdem}";
    }

    return $sql;
  }

   // funcao do sql
   function sql_query_file ( $q142_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from arquivosimplesimportacaodetalhe ";
     $sql2 = "";
     if($dbwhere==""){
       if($q142_sequencial!=null ){
         $sql2 .= " where arquivosimplesimportacaodetalhe.q142_sequencial = $q142_sequencial ";
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