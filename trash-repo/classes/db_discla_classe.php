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

//MODULO: caixa
//CLASSE DA ENTIDADE discla
class cl_discla {
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
   var $codcla = 0;
   var $codret = 0;
   var $dtcla_dia = null;
   var $dtcla_mes = null;
   var $dtcla_ano = null;
   var $dtcla = null;
   var $dtaute_dia = null;
   var $dtaute_mes = null;
   var $dtaute_ano = null;
   var $dtaute = null;
   var $instit = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 codcla = int4 = CodCla
                 codret = int4 = Código
                 dtcla = date = Data Classificação
                 dtaute = date = Data Autenticação
                 instit = int4 = Instituição
                 ";
   //funcao construtor da classe
   function cl_discla() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("discla");
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
       $this->codcla = ($this->codcla == ""?@$GLOBALS["HTTP_POST_VARS"]["codcla"]:$this->codcla);
       $this->codret = ($this->codret == ""?@$GLOBALS["HTTP_POST_VARS"]["codret"]:$this->codret);
       if($this->dtcla == ""){
         $this->dtcla_dia = ($this->dtcla_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["dtcla_dia"]:$this->dtcla_dia);
         $this->dtcla_mes = ($this->dtcla_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["dtcla_mes"]:$this->dtcla_mes);
         $this->dtcla_ano = ($this->dtcla_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["dtcla_ano"]:$this->dtcla_ano);
         if($this->dtcla_dia != ""){
            $this->dtcla = $this->dtcla_ano."-".$this->dtcla_mes."-".$this->dtcla_dia;
         }
       }
       if($this->dtaute == ""){
         $this->dtaute_dia = ($this->dtaute_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["dtaute_dia"]:$this->dtaute_dia);
         $this->dtaute_mes = ($this->dtaute_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["dtaute_mes"]:$this->dtaute_mes);
         $this->dtaute_ano = ($this->dtaute_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["dtaute_ano"]:$this->dtaute_ano);
         if($this->dtaute_dia != ""){
            $this->dtaute = $this->dtaute_ano."-".$this->dtaute_mes."-".$this->dtaute_dia;
         }
       }
       $this->instit = ($this->instit == ""?@$GLOBALS["HTTP_POST_VARS"]["instit"]:$this->instit);
     }else{
       $this->codcla = ($this->codcla == ""?@$GLOBALS["HTTP_POST_VARS"]["codcla"]:$this->codcla);
     }
   }
   // funcao para inclusao
   function incluir ($codcla){
      $this->atualizacampos();
     if($this->codret == null ){
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "codret";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dtcla == null ){
       $this->erro_sql = " Campo Data Classificação nao Informado.";
       $this->erro_campo = "dtcla_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dtaute == null ){
       $this->erro_sql = " Campo Data Autenticação nao Informado.";
       $this->erro_campo = "dtaute_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->instit == null ){
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($codcla == "" || $codcla == null ){
       $result = db_query("select nextval('discla_codcla_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: discla_codcla_seq do campo: codcla";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->codcla = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from discla_codcla_seq");
       if(($result != false) && (pg_result($result,0,0) < $codcla)){
         $this->erro_sql = " Campo codcla maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->codcla = $codcla;
       }
     }
     if(($this->codcla == null) || ($this->codcla == "") ){
       $this->erro_sql = " Campo codcla nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into discla(
                                       codcla
                                      ,codret
                                      ,dtcla
                                      ,dtaute
                                      ,instit
                       )
                values (
                                $this->codcla
                               ,$this->codret
                               ,".($this->dtcla == "null" || $this->dtcla == ""?"null":"'".$this->dtcla."'")."
                               ,".($this->dtaute == "null" || $this->dtaute == ""?"null":"'".$this->dtaute."'")."
                               ,$this->instit
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Dados classificacao receita ($this->codcla) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Dados classificacao receita já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Dados classificacao receita ($this->codcla) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->codcla;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->codcla));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1198,'$this->codcla','I')");
       $resac = db_query("insert into db_acount values($acount,215,1198,'','".AddSlashes(pg_result($resaco,0,'codcla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,215,1179,'','".AddSlashes(pg_result($resaco,0,'codret'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,215,1194,'','".AddSlashes(pg_result($resaco,0,'dtcla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,215,1196,'','".AddSlashes(pg_result($resaco,0,'dtaute'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,215,9996,'','".AddSlashes(pg_result($resaco,0,'instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($codcla=null) {
      $this->atualizacampos();
     $sql = " update discla set ";
     $virgula = "";
     if(trim($this->codcla)!="" || isset($GLOBALS["HTTP_POST_VARS"]["codcla"])){
       $sql  .= $virgula." codcla = $this->codcla ";
       $virgula = ",";
       if(trim($this->codcla) == null ){
         $this->erro_sql = " Campo CodCla nao Informado.";
         $this->erro_campo = "codcla";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->codret)!="" || isset($GLOBALS["HTTP_POST_VARS"]["codret"])){
       $sql  .= $virgula." codret = $this->codret ";
       $virgula = ",";
       if(trim($this->codret) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "codret";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dtcla)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dtcla_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["dtcla_dia"] !="") ){
       $sql  .= $virgula." dtcla = '$this->dtcla' ";
       $virgula = ",";
       if(trim($this->dtcla) == null ){
         $this->erro_sql = " Campo Data Classificação nao Informado.";
         $this->erro_campo = "dtcla_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["dtcla_dia"])){
         $sql  .= $virgula." dtcla = null ";
         $virgula = ",";
         if(trim($this->dtcla) == null ){
           $this->erro_sql = " Campo Data Classificação nao Informado.";
           $this->erro_campo = "dtcla_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->dtaute)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dtaute_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["dtaute_dia"] !="") ){

       if($this->dtaute == "null") {
          $sql  .= $virgula." dtaute = null ";
          $virgula = ",";
       } else {
          $sql  .= $virgula." dtaute = '$this->dtaute' ";
          $virgula = ",";
       }
       if(trim($this->dtaute) == null ){
         $this->erro_sql = " Campo Data Autenticação nao Informado.";
         $this->erro_campo = "dtaute_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     } else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["dtaute_dia"])){
         $sql  .= $virgula." dtaute = null ";
         $virgula = ",";
         if(trim($this->dtaute) == null ){
           $this->erro_sql = " Campo Data Autenticação nao Informado.";
           $this->erro_campo = "dtaute_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["instit"])){
       $sql  .= $virgula." instit = $this->instit ";
       $virgula = ",";
       if(trim($this->instit) == null ){
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($codcla!=null){
       $sql .= " codcla = $this->codcla";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->codcla));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1198,'$this->codcla','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["codcla"]) || $this->codcla != "")
           $resac = db_query("insert into db_acount values($acount,215,1198,'".AddSlashes(pg_result($resaco,$conresaco,'codcla'))."','$this->codcla',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["codret"]) || $this->codret != "")
           $resac = db_query("insert into db_acount values($acount,215,1179,'".AddSlashes(pg_result($resaco,$conresaco,'codret'))."','$this->codret',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dtcla"]) || $this->dtcla != "")
           $resac = db_query("insert into db_acount values($acount,215,1194,'".AddSlashes(pg_result($resaco,$conresaco,'dtcla'))."','$this->dtcla',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dtaute"]) || $this->dtaute != "")
           $resac = db_query("insert into db_acount values($acount,215,1196,'".AddSlashes(pg_result($resaco,$conresaco,'dtaute'))."','$this->dtaute',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["instit"]) || $this->instit != "")
           $resac = db_query("insert into db_acount values($acount,215,9996,'".AddSlashes(pg_result($resaco,$conresaco,'instit'))."','$this->instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados classificacao receita nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->codcla;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dados classificacao receita nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->codcla;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->codcla;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($codcla=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($codcla));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1198,'$codcla','E')");
         $resac = db_query("insert into db_acount values($acount,215,1198,'','".AddSlashes(pg_result($resaco,$iresaco,'codcla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,215,1179,'','".AddSlashes(pg_result($resaco,$iresaco,'codret'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,215,1194,'','".AddSlashes(pg_result($resaco,$iresaco,'dtcla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,215,1196,'','".AddSlashes(pg_result($resaco,$iresaco,'dtaute'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,215,9996,'','".AddSlashes(pg_result($resaco,$iresaco,'instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from discla
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($codcla != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " codcla = $codcla ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados classificacao receita nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$codcla;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dados classificacao receita nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$codcla;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$codcla;
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
        $this->erro_sql   = "Record Vazio na Tabela:discla";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $codcla=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from discla ";
     $sql .= "      inner join db_config  on  db_config.codigo = discla.instit";
     $sql .= "      inner join disarq  on  disarq.codret = discla.codret";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql .= "      inner join db_config  on  db_config.codigo = disarq.instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = disarq.id_usuario";
     $sql .= "      inner join saltes  on  saltes.k13_conta = disarq.k00_conta";
     $sql2 = "";
     if($dbwhere==""){
       if($codcla!=null ){
         $sql2 .= " where discla.codcla = $codcla ";
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
   function sql_query_file ( $codcla=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from discla ";
     $sql2 = "";
     if($dbwhere==""){
       if($codcla!=null ){
         $sql2 .= " where discla.codcla = $codcla ";
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

  function sql_query_classificacao ( $codcla=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from discla ";
    $sql .= "      inner join disarq  on  disarq.codret = discla.codret";
    $sql .= "      inner join saltes  on  saltes.k13_conta = disarq.k00_conta";
    $sql2 = "";
    if($dbwhere==""){
      if($codcla!=null ){
        $sql2 .= " where discla.codcla = $codcla ";
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