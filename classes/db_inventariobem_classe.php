<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: patrimonio
//CLASSE DA ENTIDADE inventariobem
class cl_inventariobem {
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
   var $t77_sequencial = 0;
   var $t77_inventario = 0;
   var $t77_bens = 0;
   var $t77_db_depart = 0;
   var $t77_departdiv = 0;
   var $t77_situabens = 0;
   var $t77_valordepreciavel = 0;
   var $t77_valorresidual = 0;
   var $t77_vidautil = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 t77_sequencial = int4 = Squencia inventario bem
                 t77_inventario = int4 = Inventario
                 t77_bens = int4 = Bem
                 t77_db_depart = int4 = Departamento do bem
                 t77_departdiv = int4 = Divisão do bem
                 t77_situabens = int4 = Situação do bem
                 t77_valordepreciavel = numeric(10) = Valor depreciável
                 t77_valorresidual = numeric(10) = Valor residual
                 t77_vidautil = int4 = Vida util
                 ";
   //funcao construtor da classe
   function cl_inventariobem() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("inventariobem");
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
       $this->t77_sequencial = ($this->t77_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["t77_sequencial"]:$this->t77_sequencial);
       $this->t77_inventario = ($this->t77_inventario == ""?@$GLOBALS["HTTP_POST_VARS"]["t77_inventario"]:$this->t77_inventario);
       $this->t77_bens = ($this->t77_bens == ""?@$GLOBALS["HTTP_POST_VARS"]["t77_bens"]:$this->t77_bens);
       $this->t77_db_depart = ($this->t77_db_depart == ""?@$GLOBALS["HTTP_POST_VARS"]["t77_db_depart"]:$this->t77_db_depart);
       $this->t77_departdiv = ($this->t77_departdiv == ""?@$GLOBALS["HTTP_POST_VARS"]["t77_departdiv"]:$this->t77_departdiv);
       $this->t77_situabens = ($this->t77_situabens == ""?@$GLOBALS["HTTP_POST_VARS"]["t77_situabens"]:$this->t77_situabens);
       $this->t77_valordepreciavel = ($this->t77_valordepreciavel == ""?@$GLOBALS["HTTP_POST_VARS"]["t77_valordepreciavel"]:$this->t77_valordepreciavel);
       $this->t77_valorresidual = ($this->t77_valorresidual == ""?@$GLOBALS["HTTP_POST_VARS"]["t77_valorresidual"]:$this->t77_valorresidual);
       $this->t77_vidautil = ($this->t77_vidautil == ""?@$GLOBALS["HTTP_POST_VARS"]["t77_vidautil"]:$this->t77_vidautil);
     }else{
       $this->t77_sequencial = ($this->t77_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["t77_sequencial"]:$this->t77_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($t77_sequencial){
      $this->atualizacampos();
     if($this->t77_inventario == null ){
       $this->erro_sql = " Campo Inventario nao Informado.";
       $this->erro_campo = "t77_inventario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t77_bens == null ){
       $this->erro_sql = " Campo Bem nao Informado.";
       $this->erro_campo = "t77_bens";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t77_db_depart == null ){
       $this->t77_db_depart = "null";
     }
     if($this->t77_departdiv == null ){
       $this->t77_departdiv = "null";
     }
     if($this->t77_situabens == null ){
       $this->t77_situabens = "null";
     }
     if($this->t77_valordepreciavel == null ){
       $this->t77_valordepreciavel = "null";
     }
     if($this->t77_valorresidual == null ){
       $this->t77_valorresidual = "null";
     }
     if($this->t77_vidautil == null ){
       $this->t77_vidautil = "null";
     }
     if($t77_sequencial == "" || $t77_sequencial == null ){
       $result = db_query("select nextval('inventariobem_t77_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: inventariobem_t77_sequencial_seq do campo: t77_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->t77_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from inventariobem_t77_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $t77_sequencial)){
         $this->erro_sql = " Campo t77_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->t77_sequencial = $t77_sequencial;
       }
     }
     if(($this->t77_sequencial == null) || ($this->t77_sequencial == "") ){
       $this->erro_sql = " Campo t77_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into inventariobem(
                                       t77_sequencial
                                      ,t77_inventario
                                      ,t77_bens
                                      ,t77_db_depart
                                      ,t77_departdiv
                                      ,t77_situabens
                                      ,t77_valordepreciavel
                                      ,t77_valorresidual
                                      ,t77_vidautil
                       )
                values (
                                $this->t77_sequencial
                               ,$this->t77_inventario
                               ,$this->t77_bens
                               ,$this->t77_db_depart
                               ,$this->t77_departdiv
                               ,$this->t77_situabens
                               ,$this->t77_valordepreciavel
                               ,$this->t77_valorresidual
                               ,$this->t77_vidautil
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Inventarios e bens ($this->t77_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Inventarios e bens já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Inventarios e bens ($this->t77_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t77_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->t77_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19383,'$this->t77_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3442,19383,'','".AddSlashes(pg_result($resaco,0,'t77_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3442,19384,'','".AddSlashes(pg_result($resaco,0,'t77_inventario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3442,19385,'','".AddSlashes(pg_result($resaco,0,'t77_bens'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3442,19386,'','".AddSlashes(pg_result($resaco,0,'t77_db_depart'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3442,19387,'','".AddSlashes(pg_result($resaco,0,'t77_departdiv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3442,19388,'','".AddSlashes(pg_result($resaco,0,'t77_situabens'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3442,19389,'','".AddSlashes(pg_result($resaco,0,'t77_valordepreciavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3442,19390,'','".AddSlashes(pg_result($resaco,0,'t77_valorresidual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3442,19391,'','".AddSlashes(pg_result($resaco,0,'t77_vidautil'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($t77_sequencial=null) {
      $this->atualizacampos();
     $sql = " update inventariobem set ";
     $virgula = "";
     if(trim($this->t77_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t77_sequencial"])){
       $sql  .= $virgula." t77_sequencial = $this->t77_sequencial ";
       $virgula = ",";
       if(trim($this->t77_sequencial) == null ){
         $this->erro_sql = " Campo Squencia inventario bem nao Informado.";
         $this->erro_campo = "t77_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t77_inventario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t77_inventario"])){
       $sql  .= $virgula." t77_inventario = $this->t77_inventario ";
       $virgula = ",";
       if(trim($this->t77_inventario) == null ){
         $this->erro_sql = " Campo Inventario nao Informado.";
         $this->erro_campo = "t77_inventario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t77_bens)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t77_bens"])){
       $sql  .= $virgula." t77_bens = $this->t77_bens ";
       $virgula = ",";
       if(trim($this->t77_bens) == null ){
         $this->erro_sql = " Campo Bem nao Informado.";
         $this->erro_campo = "t77_bens";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t77_db_depart)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t77_db_depart"])){
        if(trim($this->t77_db_depart)=="" && isset($GLOBALS["HTTP_POST_VARS"]["t77_db_depart"])){
           $this->t77_db_depart = "0" ;
        }
       $sql  .= $virgula." t77_db_depart = $this->t77_db_depart ";
       $virgula = ",";
     }
     if(trim($this->t77_departdiv)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t77_departdiv"])){
        if(trim($this->t77_departdiv)=="" && isset($GLOBALS["HTTP_POST_VARS"]["t77_departdiv"])){
           $this->t77_departdiv = "0" ;
        }
       $sql  .= $virgula." t77_departdiv = $this->t77_departdiv ";
       $virgula = ",";
     }
     if(trim($this->t77_situabens)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t77_situabens"])){
        if(trim($this->t77_situabens)=="" && isset($GLOBALS["HTTP_POST_VARS"]["t77_situabens"])){
           $this->t77_situabens = "0" ;
        }
       $sql  .= $virgula." t77_situabens = $this->t77_situabens ";
       $virgula = ",";
     }
     if(trim($this->t77_valordepreciavel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t77_valordepreciavel"])){
       $sql  .= $virgula." t77_valordepreciavel = $this->t77_valordepreciavel ";
       $virgula = ",";
     }
     if(trim($this->t77_valorresidual)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t77_valorresidual"])){
       $sql  .= $virgula." t77_valorresidual = $this->t77_valorresidual ";
       $virgula = ",";
     }
     if(trim($this->t77_vidautil)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t77_vidautil"])){
        if(trim($this->t77_vidautil)=="" && isset($GLOBALS["HTTP_POST_VARS"]["t77_vidautil"])){
           $this->t77_vidautil = "0" ;
        }
       $sql  .= $virgula." t77_vidautil = $this->t77_vidautil ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($t77_sequencial!=null){
       $sql .= " t77_sequencial = $this->t77_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->t77_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19383,'$this->t77_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t77_sequencial"]) || $this->t77_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3442,19383,'".AddSlashes(pg_result($resaco,$conresaco,'t77_sequencial'))."','$this->t77_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t77_inventario"]) || $this->t77_inventario != "")
           $resac = db_query("insert into db_acount values($acount,3442,19384,'".AddSlashes(pg_result($resaco,$conresaco,'t77_inventario'))."','$this->t77_inventario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t77_bens"]) || $this->t77_bens != "")
           $resac = db_query("insert into db_acount values($acount,3442,19385,'".AddSlashes(pg_result($resaco,$conresaco,'t77_bens'))."','$this->t77_bens',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t77_db_depart"]) || $this->t77_db_depart != "")
           $resac = db_query("insert into db_acount values($acount,3442,19386,'".AddSlashes(pg_result($resaco,$conresaco,'t77_db_depart'))."','$this->t77_db_depart',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t77_departdiv"]) || $this->t77_departdiv != "")
           $resac = db_query("insert into db_acount values($acount,3442,19387,'".AddSlashes(pg_result($resaco,$conresaco,'t77_departdiv'))."','$this->t77_departdiv',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t77_situabens"]) || $this->t77_situabens != "")
           $resac = db_query("insert into db_acount values($acount,3442,19388,'".AddSlashes(pg_result($resaco,$conresaco,'t77_situabens'))."','$this->t77_situabens',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t77_valordepreciavel"]) || $this->t77_valordepreciavel != "")
           $resac = db_query("insert into db_acount values($acount,3442,19389,'".AddSlashes(pg_result($resaco,$conresaco,'t77_valordepreciavel'))."','$this->t77_valordepreciavel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t77_valorresidual"]) || $this->t77_valorresidual != "")
           $resac = db_query("insert into db_acount values($acount,3442,19390,'".AddSlashes(pg_result($resaco,$conresaco,'t77_valorresidual'))."','$this->t77_valorresidual',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t77_vidautil"]) || $this->t77_vidautil != "")
           $resac = db_query("insert into db_acount values($acount,3442,19391,'".AddSlashes(pg_result($resaco,$conresaco,'t77_vidautil'))."','$this->t77_vidautil',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Inventarios e bens nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->t77_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Inventarios e bens nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->t77_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t77_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($t77_sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($t77_sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19383,'$t77_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3442,19383,'','".AddSlashes(pg_result($resaco,$iresaco,'t77_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3442,19384,'','".AddSlashes(pg_result($resaco,$iresaco,'t77_inventario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3442,19385,'','".AddSlashes(pg_result($resaco,$iresaco,'t77_bens'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3442,19386,'','".AddSlashes(pg_result($resaco,$iresaco,'t77_db_depart'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3442,19387,'','".AddSlashes(pg_result($resaco,$iresaco,'t77_departdiv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3442,19388,'','".AddSlashes(pg_result($resaco,$iresaco,'t77_situabens'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3442,19389,'','".AddSlashes(pg_result($resaco,$iresaco,'t77_valordepreciavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3442,19390,'','".AddSlashes(pg_result($resaco,$iresaco,'t77_valorresidual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3442,19391,'','".AddSlashes(pg_result($resaco,$iresaco,'t77_vidautil'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from inventariobem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($t77_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t77_sequencial = $t77_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Inventarios e bens nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$t77_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Inventarios e bens nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$t77_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$t77_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:inventariobem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $t77_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from inventariobem ";
     $sql .= "      left  join db_depart  on  db_depart.coddepto = inventariobem.t77_db_depart";
     $sql .= "      inner join bens  on  bens.t52_bem = inventariobem.t77_bens";
     $sql .= "      left  join situabens  on  situabens.t70_situac = inventariobem.t77_situabens";
     $sql .= "      left  join departdiv  on  departdiv.t30_codigo = inventariobem.t77_departdiv";
     $sql .= "      inner join inventario  on  inventario.t75_sequencial = inventariobem.t77_inventario";
     $sql .= "      inner join db_config  on  db_config.codigo = db_depart.instit";
     //$sql .= "      inner join db_datausuarios  on  db_datausuarios.id_usuario = db_depart.id_usuarioresp";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = bens.t52_numcgm";
     $sql .= "      inner join db_config  as a on   a.codigo = bens.t52_instit";
     //$sql .= "      inner join db_depart  on  db_depart.coddepto = bens.t52_depart";
     $sql .= "      inner join clabens  on  clabens.t64_codcla = bens.t52_codcla";
     $sql .= "      inner join bensmarca  on  bensmarca.t65_sequencial = bens.t52_bensmarca";
     $sql .= "      inner join bensmodelo  on  bensmodelo.t66_sequencial = bens.t52_bensmodelo";
     $sql .= "      inner join bensmedida  on  bensmedida.t67_sequencial = bens.t52_bensmedida";
     $sql .= "      inner join cgm  as b on   b.z01_numcgm = departdiv.t30_numcgm";
     //$sql .= "      inner join db_depart  on  db_depart.coddepto = departdiv.t30_depto";
     //$sql .= "      inner join db_depart  on  db_depart.coddepto = inventario.t75_db_depart";
     $sql .= "      left  join protprocesso  on  protprocesso.p58_codproc = inventario.t75_processo";
     $sql .= "      inner join acordocomissao  on  acordocomissao.ac08_sequencial = inventario.t75_acordocomissao";
     $sql2 = "";
     if($dbwhere==""){
       if($t77_sequencial!=null ){
         $sql2 .= " where inventariobem.t77_sequencial = $t77_sequencial ";
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
   function sql_query_file ( $t77_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from inventariobem ";
     $sql2 = "";
     if($dbwhere==""){
       if($t77_sequencial!=null ){
         $sql2 .= " where inventariobem.t77_sequencial = $t77_sequencial ";
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
  function sql_query_inventario ( $t77_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from inventariobem ";
    $sql .= "      inner join bens  on  bens.t52_bem = inventariobem.t77_bens";
    $sql .= "      inner join inventario  on  inventario.t75_sequencial = inventariobem.t77_inventario";
    if($dbwhere==""){
      if($t77_sequencial!=null ){
        $sql2 .= " where inventariobem.t77_sequencial = $t77_sequencial ";
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