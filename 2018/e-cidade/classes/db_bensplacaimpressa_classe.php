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
//CLASSE DA ENTIDADE bensplacaimpressa
class cl_bensplacaimpressa {
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
   var $t73_sequencial = 0;
   var $t73_bensplaca = 0;
   var $t73_coddepto = 0;
   var $t73_departdiv = 0;
   var $t73_tipoloteindividual = 'f';
   var $t73_bensetiquetaimpressa = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 t73_sequencial = int4 = Codigo
                 t73_bensplaca = int4 = Bens Placa
                 t73_coddepto = int4 = Departamento
                 t73_departdiv = int4 = Divisao
                 t73_tipoloteindividual = bool = Lote / Individual
                 t73_bensetiquetaimpressa = int4 = Bens Etiqueta Impressa
                 ";
   //funcao construtor da classe
   function cl_bensplacaimpressa() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("bensplacaimpressa");
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
       $this->t73_sequencial = ($this->t73_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["t73_sequencial"]:$this->t73_sequencial);
       $this->t73_bensplaca = ($this->t73_bensplaca == ""?@$GLOBALS["HTTP_POST_VARS"]["t73_bensplaca"]:$this->t73_bensplaca);
       $this->t73_coddepto = ($this->t73_coddepto == ""?@$GLOBALS["HTTP_POST_VARS"]["t73_coddepto"]:$this->t73_coddepto);
       $this->t73_departdiv = ($this->t73_departdiv == ""?@$GLOBALS["HTTP_POST_VARS"]["t73_departdiv"]:$this->t73_departdiv);
       $this->t73_tipoloteindividual = ($this->t73_tipoloteindividual == "f"?@$GLOBALS["HTTP_POST_VARS"]["t73_tipoloteindividual"]:$this->t73_tipoloteindividual);
       $this->t73_bensetiquetaimpressa = ($this->t73_bensetiquetaimpressa == ""?@$GLOBALS["HTTP_POST_VARS"]["t73_bensetiquetaimpressa"]:$this->t73_bensetiquetaimpressa);
     }else{
       $this->t73_sequencial = ($this->t73_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["t73_sequencial"]:$this->t73_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($t73_sequencial){
      $this->atualizacampos();
     if($this->t73_bensplaca == null ){
       $this->erro_sql = " Campo Bens Placa nao Informado.";
       $this->erro_campo = "t73_bensplaca";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t73_coddepto == null ){
       $this->erro_sql = " Campo Departamento nao Informado.";
       $this->erro_campo = "t73_coddepto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t73_departdiv == null ){
       $this->t73_departdiv = "0";
     }
     if($this->t73_tipoloteindividual == null ){
       $this->erro_sql = " Campo Lote / Individual nao Informado.";
       $this->erro_campo = "t73_tipoloteindividual";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t73_bensetiquetaimpressa == null ){
       $this->erro_sql = " Campo Bens Etiqueta Impressa nao Informado.";
       $this->erro_campo = "t73_bensetiquetaimpressa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($t73_sequencial == "" || $t73_sequencial == null ){
       $result = db_query("select nextval('bensplacaimpressa_t73_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: bensplacaimpressa_t73_sequencial_seq do campo: t73_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->t73_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from bensplacaimpressa_t73_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $t73_sequencial)){
         $this->erro_sql = " Campo t73_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->t73_sequencial = $t73_sequencial;
       }
     }
     if(($this->t73_sequencial == null) || ($this->t73_sequencial == "") ){
       $this->erro_sql = " Campo t73_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into bensplacaimpressa(
                                       t73_sequencial
                                      ,t73_bensplaca
                                      ,t73_coddepto
                                      ,t73_departdiv
                                      ,t73_tipoloteindividual
                                      ,t73_bensetiquetaimpressa
                       )
                values (
                                $this->t73_sequencial
                               ,$this->t73_bensplaca
                               ,$this->t73_coddepto
                               ,$this->t73_departdiv
                               ,'$this->t73_tipoloteindividual'
                               ,$this->t73_bensetiquetaimpressa
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Bens Placa Impressa ($this->t73_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Bens Placa Impressa já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Bens Placa Impressa ($this->t73_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t73_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->t73_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15599,'$this->t73_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2735,15599,'','".AddSlashes(pg_result($resaco,0,'t73_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2735,15600,'','".AddSlashes(pg_result($resaco,0,'t73_bensplaca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2735,15604,'','".AddSlashes(pg_result($resaco,0,'t73_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2735,15605,'','".AddSlashes(pg_result($resaco,0,'t73_departdiv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2735,15606,'','".AddSlashes(pg_result($resaco,0,'t73_tipoloteindividual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2735,15611,'','".AddSlashes(pg_result($resaco,0,'t73_bensetiquetaimpressa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($t73_sequencial=null) {
      $this->atualizacampos();
     $sql = " update bensplacaimpressa set ";
     $virgula = "";
     if(trim($this->t73_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t73_sequencial"])){
       $sql  .= $virgula." t73_sequencial = $this->t73_sequencial ";
       $virgula = ",";
       if(trim($this->t73_sequencial) == null ){
         $this->erro_sql = " Campo Codigo nao Informado.";
         $this->erro_campo = "t73_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t73_bensplaca)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t73_bensplaca"])){
       $sql  .= $virgula." t73_bensplaca = $this->t73_bensplaca ";
       $virgula = ",";
       if(trim($this->t73_bensplaca) == null ){
         $this->erro_sql = " Campo Bens Placa nao Informado.";
         $this->erro_campo = "t73_bensplaca";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t73_coddepto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t73_coddepto"])){
       $sql  .= $virgula." t73_coddepto = $this->t73_coddepto ";
       $virgula = ",";
       if(trim($this->t73_coddepto) == null ){
         $this->erro_sql = " Campo Departamento nao Informado.";
         $this->erro_campo = "t73_coddepto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t73_departdiv)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t73_departdiv"])){
        if(trim($this->t73_departdiv)=="" && isset($GLOBALS["HTTP_POST_VARS"]["t73_departdiv"])){
           $this->t73_departdiv = "0" ;
        }
       $sql  .= $virgula." t73_departdiv = $this->t73_departdiv ";
       $virgula = ",";
     }
     if(trim($this->t73_tipoloteindividual)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t73_tipoloteindividual"])){
       $sql  .= $virgula." t73_tipoloteindividual = '$this->t73_tipoloteindividual' ";
       $virgula = ",";
       if(trim($this->t73_tipoloteindividual) == null ){
         $this->erro_sql = " Campo Lote / Individual nao Informado.";
         $this->erro_campo = "t73_tipoloteindividual";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t73_bensetiquetaimpressa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t73_bensetiquetaimpressa"])){
       $sql  .= $virgula." t73_bensetiquetaimpressa = $this->t73_bensetiquetaimpressa ";
       $virgula = ",";
       if(trim($this->t73_bensetiquetaimpressa) == null ){
         $this->erro_sql = " Campo Bens Etiqueta Impressa nao Informado.";
         $this->erro_campo = "t73_bensetiquetaimpressa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($t73_sequencial!=null){
       $sql .= " t73_sequencial = $this->t73_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->t73_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15599,'$this->t73_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t73_sequencial"]) || $this->t73_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2735,15599,'".AddSlashes(pg_result($resaco,$conresaco,'t73_sequencial'))."','$this->t73_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t73_bensplaca"]) || $this->t73_bensplaca != "")
           $resac = db_query("insert into db_acount values($acount,2735,15600,'".AddSlashes(pg_result($resaco,$conresaco,'t73_bensplaca'))."','$this->t73_bensplaca',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t73_coddepto"]) || $this->t73_coddepto != "")
           $resac = db_query("insert into db_acount values($acount,2735,15604,'".AddSlashes(pg_result($resaco,$conresaco,'t73_coddepto'))."','$this->t73_coddepto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t73_departdiv"]) || $this->t73_departdiv != "")
           $resac = db_query("insert into db_acount values($acount,2735,15605,'".AddSlashes(pg_result($resaco,$conresaco,'t73_departdiv'))."','$this->t73_departdiv',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t73_tipoloteindividual"]) || $this->t73_tipoloteindividual != "")
           $resac = db_query("insert into db_acount values($acount,2735,15606,'".AddSlashes(pg_result($resaco,$conresaco,'t73_tipoloteindividual'))."','$this->t73_tipoloteindividual',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t73_bensetiquetaimpressa"]) || $this->t73_bensetiquetaimpressa != "")
           $resac = db_query("insert into db_acount values($acount,2735,15611,'".AddSlashes(pg_result($resaco,$conresaco,'t73_bensetiquetaimpressa'))."','$this->t73_bensetiquetaimpressa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Bens Placa Impressa nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->t73_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Bens Placa Impressa nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->t73_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t73_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($t73_sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($t73_sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15599,'$t73_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2735,15599,'','".AddSlashes(pg_result($resaco,$iresaco,'t73_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2735,15600,'','".AddSlashes(pg_result($resaco,$iresaco,'t73_bensplaca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2735,15604,'','".AddSlashes(pg_result($resaco,$iresaco,'t73_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2735,15605,'','".AddSlashes(pg_result($resaco,$iresaco,'t73_departdiv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2735,15606,'','".AddSlashes(pg_result($resaco,$iresaco,'t73_tipoloteindividual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2735,15611,'','".AddSlashes(pg_result($resaco,$iresaco,'t73_bensetiquetaimpressa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from bensplacaimpressa
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($t73_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t73_sequencial = $t73_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Bens Placa Impressa nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$t73_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Bens Placa Impressa nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$t73_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$t73_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:bensplacaimpressa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $t73_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from bensplacaimpressa ";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = bensplacaimpressa.t73_coddepto";
     $sql .= "      inner join bensplaca  on  bensplaca.t41_codigo = bensplacaimpressa.t73_bensplaca";
     $sql .= "      left  join departdiv  on  departdiv.t30_codigo = bensplacaimpressa.t73_departdiv";
     $sql .= "      inner join db_config  on  db_config.codigo = db_depart.instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = bensplaca.t41_usuario";
     $sql .= "      inner join bens  as a on   a.t52_bem = bensplaca.t41_bem";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = departdiv.t30_numcgm";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = departdiv.t30_depto";
     $sql .= "      inner join db_usuarios  as b on   b.id_usuario = bensetiquetaimpressa.t74_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($t73_sequencial!=null ){
         $sql2 .= " where bensplacaimpressa.t73_sequencial = $t73_sequencial ";
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
   function sql_query_file ( $t73_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from bensplacaimpressa ";
     $sql2 = "";
     if($dbwhere==""){
       if($t73_sequencial!=null ){
         $sql2 .= " where bensplacaimpressa.t73_sequencial = $t73_sequencial ";
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