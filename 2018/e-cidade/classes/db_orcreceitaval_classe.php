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

//MODULO: orcamento
//CLASSE DA ENTIDADE orcreceitaval
class cl_orcreceitaval {
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
   var $o71_anousu = 0;
   var $o71_codrec = 0;
   var $o71_coddoc = 0;
   var $o71_mes = 0;
   var $o71_valor = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 o71_anousu = int4 = Exercício
                 o71_codrec = int4 = Código Reduzido
                 o71_coddoc = int4 = Documento
                 o71_mes = int4 = Mês
                 o71_valor = float8 = Valor
                 ";
   //funcao construtor da classe
   function cl_orcreceitaval() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcreceitaval");
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
       $this->o71_anousu = ($this->o71_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o71_anousu"]:$this->o71_anousu);
       $this->o71_codrec = ($this->o71_codrec == ""?@$GLOBALS["HTTP_POST_VARS"]["o71_codrec"]:$this->o71_codrec);
       $this->o71_coddoc = ($this->o71_coddoc == ""?@$GLOBALS["HTTP_POST_VARS"]["o71_coddoc"]:$this->o71_coddoc);
       $this->o71_mes = ($this->o71_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o71_mes"]:$this->o71_mes);
       $this->o71_valor = ($this->o71_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["o71_valor"]:$this->o71_valor);
     }else{
       $this->o71_anousu = ($this->o71_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o71_anousu"]:$this->o71_anousu);
       $this->o71_codrec = ($this->o71_codrec == ""?@$GLOBALS["HTTP_POST_VARS"]["o71_codrec"]:$this->o71_codrec);
       $this->o71_coddoc = ($this->o71_coddoc == ""?@$GLOBALS["HTTP_POST_VARS"]["o71_coddoc"]:$this->o71_coddoc);
       $this->o71_mes = ($this->o71_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o71_mes"]:$this->o71_mes);
     }
   }
   // funcao para inclusao
   function incluir ($o71_anousu,$o71_codrec,$o71_coddoc,$o71_mes){
      $this->atualizacampos();
     if($this->o71_valor == null ){
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "o71_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->o71_anousu = $o71_anousu;
       $this->o71_codrec = $o71_codrec;
       $this->o71_coddoc = $o71_coddoc;
       $this->o71_mes = $o71_mes;
     if(($this->o71_anousu == null) || ($this->o71_anousu == "") ){
       $this->erro_sql = " Campo o71_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o71_codrec == null) || ($this->o71_codrec == "") ){
       $this->erro_sql = " Campo o71_codrec nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o71_coddoc == null) || ($this->o71_coddoc == "") ){
       $this->erro_sql = " Campo o71_coddoc nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o71_mes == null) || ($this->o71_mes == "") ){
       $this->erro_sql = " Campo o71_mes nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcreceitaval(
                                       o71_anousu
                                      ,o71_codrec
                                      ,o71_coddoc
                                      ,o71_mes
                                      ,o71_valor
                       )
                values (
                                $this->o71_anousu
                               ,$this->o71_codrec
                               ,$this->o71_coddoc
                               ,$this->o71_mes
                               ,$this->o71_valor
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Movimentação das receitas ($this->o71_anousu."-".$this->o71_codrec."-".$this->o71_coddoc."-".$this->o71_mes) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Movimentação das receitas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Movimentação das receitas ($this->o71_anousu."-".$this->o71_codrec."-".$this->o71_coddoc."-".$this->o71_mes) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o71_anousu."-".$this->o71_codrec."-".$this->o71_coddoc."-".$this->o71_mes;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

       $resaco = $this->sql_record($this->sql_query_file($this->o71_anousu,$this->o71_codrec,$this->o71_coddoc,$this->o71_mes));
       if(($resaco!=false)||($this->numrows!=0)){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5302,'$this->o71_anousu','I')");
         $resac = db_query("insert into db_acountkey values($acount,5303,'$this->o71_codrec','I')");
         $resac = db_query("insert into db_acountkey values($acount,6055,'$this->o71_coddoc','I')");
         $resac = db_query("insert into db_acountkey values($acount,5304,'$this->o71_mes','I')");
         $resac = db_query("insert into db_acount values($acount,769,5302,'','".AddSlashes(pg_result($resaco,0,'o71_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,769,5303,'','".AddSlashes(pg_result($resaco,0,'o71_codrec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,769,6055,'','".AddSlashes(pg_result($resaco,0,'o71_coddoc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,769,5304,'','".AddSlashes(pg_result($resaco,0,'o71_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,769,5305,'','".AddSlashes(pg_result($resaco,0,'o71_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($o71_anousu=null,$o71_codrec=null,$o71_coddoc=null,$o71_mes=null) {
      $this->atualizacampos();
     $sql = " update orcreceitaval set ";
     $virgula = "";
     if(trim($this->o71_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o71_anousu"])){
       $sql  .= $virgula." o71_anousu = $this->o71_anousu ";
       $virgula = ",";
       if(trim($this->o71_anousu) == null ){
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "o71_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o71_codrec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o71_codrec"])){
       $sql  .= $virgula." o71_codrec = $this->o71_codrec ";
       $virgula = ",";
       if(trim($this->o71_codrec) == null ){
         $this->erro_sql = " Campo Código Reduzido nao Informado.";
         $this->erro_campo = "o71_codrec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o71_coddoc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o71_coddoc"])){
       $sql  .= $virgula." o71_coddoc = $this->o71_coddoc ";
       $virgula = ",";
       if(trim($this->o71_coddoc) == null ){
         $this->erro_sql = " Campo Documento nao Informado.";
         $this->erro_campo = "o71_coddoc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o71_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o71_mes"])){
       $sql  .= $virgula." o71_mes = $this->o71_mes ";
       $virgula = ",";
       if(trim($this->o71_mes) == null ){
         $this->erro_sql = " Campo Mês nao Informado.";
         $this->erro_campo = "o71_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o71_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o71_valor"])){
       $sql  .= $virgula." o71_valor = $this->o71_valor ";
       $virgula = ",";
       if(trim($this->o71_valor) == null ){
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "o71_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o71_anousu!=null){
       $sql .= " o71_anousu = $this->o71_anousu";
     }
     if($o71_codrec!=null){
       $sql .= " and  o71_codrec = $this->o71_codrec";
     }
     if($o71_coddoc!=null){
       $sql .= " and  o71_coddoc = $this->o71_coddoc";
     }
     if($o71_mes!=null){
       $sql .= " and  o71_mes = $this->o71_mes";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

       $resaco = $this->sql_record($this->sql_query_file($this->o71_anousu,$this->o71_codrec,$this->o71_coddoc,$this->o71_mes));
       if($this->numrows>0){
         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,5302,'$this->o71_anousu','A')");
           $resac = db_query("insert into db_acountkey values($acount,5303,'$this->o71_codrec','A')");
           $resac = db_query("insert into db_acountkey values($acount,6055,'$this->o71_coddoc','A')");
           $resac = db_query("insert into db_acountkey values($acount,5304,'$this->o71_mes','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o71_anousu"]))
             $resac = db_query("insert into db_acount values($acount,769,5302,'".AddSlashes(pg_result($resaco,$conresaco,'o71_anousu'))."','$this->o71_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o71_codrec"]))
             $resac = db_query("insert into db_acount values($acount,769,5303,'".AddSlashes(pg_result($resaco,$conresaco,'o71_codrec'))."','$this->o71_codrec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o71_coddoc"]))
             $resac = db_query("insert into db_acount values($acount,769,6055,'".AddSlashes(pg_result($resaco,$conresaco,'o71_coddoc'))."','$this->o71_coddoc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o71_mes"]))
             $resac = db_query("insert into db_acount values($acount,769,5304,'".AddSlashes(pg_result($resaco,$conresaco,'o71_mes'))."','$this->o71_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o71_valor"]))
             $resac = db_query("insert into db_acount values($acount,769,5305,'".AddSlashes(pg_result($resaco,$conresaco,'o71_valor'))."','$this->o71_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Movimentação das receitas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o71_anousu."-".$this->o71_codrec."-".$this->o71_coddoc."-".$this->o71_mes;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Movimentação das receitas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o71_anousu."-".$this->o71_codrec."-".$this->o71_coddoc."-".$this->o71_mes;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o71_anousu."-".$this->o71_codrec."-".$this->o71_coddoc."-".$this->o71_mes;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($o71_anousu=null,$o71_codrec=null,$o71_coddoc=null,$o71_mes=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {
       if($dbwhere==null || $dbwhere==""){
         $resaco = $this->sql_record($this->sql_query_file($o71_anousu,$o71_codrec,$o71_coddoc,$o71_mes));
       }else{
         $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,"*",null,$dbwhere));
       }
       if(($resaco!=false)||($this->numrows!=0)){
         for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,5302,'$o71_anousu','E')");
           $resac = db_query("insert into db_acountkey values($acount,5303,'$o71_codrec','E')");
           $resac = db_query("insert into db_acountkey values($acount,6055,'$o71_coddoc','E')");
           $resac = db_query("insert into db_acountkey values($acount,5304,'$o71_mes','E')");
           $resac = db_query("insert into db_acount values($acount,769,5302,'','".AddSlashes(pg_result($resaco,$iresaco,'o71_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,769,5303,'','".AddSlashes(pg_result($resaco,$iresaco,'o71_codrec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,769,6055,'','".AddSlashes(pg_result($resaco,$iresaco,'o71_coddoc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,769,5304,'','".AddSlashes(pg_result($resaco,$iresaco,'o71_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,769,5305,'','".AddSlashes(pg_result($resaco,$iresaco,'o71_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from orcreceitaval
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o71_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o71_anousu = $o71_anousu ";
        }
        if($o71_codrec != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o71_codrec = $o71_codrec ";
        }
        if($o71_coddoc != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o71_coddoc = $o71_coddoc ";
        }
        if($o71_mes != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o71_mes = $o71_mes ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Movimentação das receitas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o71_anousu."-".$o71_codrec."-".$o71_coddoc."-".$o71_mes;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Movimentação das receitas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o71_anousu."-".$o71_codrec."-".$o71_coddoc."-".$o71_mes;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o71_anousu."-".$o71_codrec."-".$o71_coddoc."-".$o71_mes;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcreceitaval";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $o71_anousu=null,$o71_codrec=null,$o71_coddoc=null,$o71_mes=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from orcreceitaval ";
     $sql .= "      inner join orcreceita  on  orcreceita.o70_anousu = orcreceitaval.o71_anousu and  orcreceita.o70_codrec = orcreceitaval.o71_codrec";
     $sql .= "      inner join conhistdoc  on  conhistdoc.c53_coddoc = orcreceitaval.o71_coddoc";
     $sql .= "      inner join db_config  on  db_config.codigo = orcreceita.o70_instit";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcreceita.o70_codigo";
     $sql .= "      inner join orcfontes  on  orcfontes.o57_codfon = orcreceita.o70_codfon and orcfontes.o57_anousu = orcreceita.o70_anousu ";
     $sql .= "      inner join db_config  as a on   a.codigo = orcreceita.o70_instit";
     $sql .= "      inner join orctiporec  as b on   b.o15_codigo = orcreceita.o70_codigo";
     $sql .= "      inner join orcfontes  as c on   c.o57_codfon = orcreceita.o70_codfon and c.o57_anousu = orcreceita.o70_anousu ";
     $sql2 = "";
     if($dbwhere==""){
       if($o71_anousu!=null ){
         $sql2 .= " where orcreceitaval.o71_anousu = $o71_anousu ";
       }
       if($o71_codrec!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " orcreceitaval.o71_codrec = $o71_codrec ";
       }
       if($o71_coddoc!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " orcreceitaval.o71_coddoc = $o71_coddoc ";
       }
       if($o71_mes!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " orcreceitaval.o71_mes = $o71_mes ";
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
   function sql_query_file ( $o71_anousu=null,$o71_codrec=null,$o71_coddoc=null,$o71_mes=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from orcreceitaval ";
     $sql2 = "";
     if($dbwhere==""){
       if($o71_anousu!=null ){
         $sql2 .= " where orcreceitaval.o71_anousu = $o71_anousu ";
       }
       if($o71_codrec!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " orcreceitaval.o71_codrec = $o71_codrec ";
       }
       if($o71_coddoc!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " orcreceitaval.o71_coddoc = $o71_coddoc ";
       }
       if($o71_mes!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " orcreceitaval.o71_mes = $o71_mes ";
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