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

//MODULO: contabilidade
//CLASSE DA ENTIDADE conplanocontacorrente
class cl_conplanocontacorrente {
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
   var $c18_sequencial = 0;
   var $c18_codcon = 0;
   var $c18_anousu = 0;
   var $c18_contacorrente = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 c18_sequencial = int4 = Código
                 c18_codcon = int4 = Conta PCASP
                 c18_anousu = int4 = Ano
                 c18_contacorrente = int4 = Conta Corrente
                 ";
   //funcao construtor da classe
   function cl_conplanocontacorrente() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("conplanocontacorrente");
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
       $this->c18_sequencial = ($this->c18_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c18_sequencial"]:$this->c18_sequencial);
       $this->c18_codcon = ($this->c18_codcon == ""?@$GLOBALS["HTTP_POST_VARS"]["c18_codcon"]:$this->c18_codcon);
       $this->c18_anousu = ($this->c18_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c18_anousu"]:$this->c18_anousu);
       $this->c18_contacorrente = ($this->c18_contacorrente == ""?@$GLOBALS["HTTP_POST_VARS"]["c18_contacorrente"]:$this->c18_contacorrente);
     }else{
       $this->c18_sequencial = ($this->c18_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c18_sequencial"]:$this->c18_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($c18_sequencial){
      $this->atualizacampos();
     if($this->c18_codcon == null ){
       $this->erro_sql = " Campo Conta PCASP nao Informado.";
       $this->erro_campo = "c18_codcon";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c18_anousu == null ){
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "c18_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c18_contacorrente == null ){
       $this->erro_sql = " Campo Conta Corrente nao Informado.";
       $this->erro_campo = "c18_contacorrente";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c18_sequencial == "" || $c18_sequencial == null ){
       $result = db_query("select nextval('conplanocontacorrente_c18_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: conplanocontacorrente_c18_sequencial_seq do campo: c18_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->c18_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from conplanocontacorrente_c18_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $c18_sequencial)){
         $this->erro_sql = " Campo c18_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c18_sequencial = $c18_sequencial;
       }
     }
     if(($this->c18_sequencial == null) || ($this->c18_sequencial == "") ){
       $this->erro_sql = " Campo c18_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into conplanocontacorrente(
                                       c18_sequencial
                                      ,c18_codcon
                                      ,c18_anousu
                                      ,c18_contacorrente
                       )
                values (
                                $this->c18_sequencial
                               ,$this->c18_codcon
                               ,$this->c18_anousu
                               ,$this->c18_contacorrente
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Vinculo PCASP com conta corrente ($this->c18_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Vinculo PCASP com conta corrente já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Vinculo PCASP com conta corrente ($this->c18_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c18_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c18_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18625,'$this->c18_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3298,18625,'','".AddSlashes(pg_result($resaco,0,'c18_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3298,18627,'','".AddSlashes(pg_result($resaco,0,'c18_codcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3298,18628,'','".AddSlashes(pg_result($resaco,0,'c18_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3298,18626,'','".AddSlashes(pg_result($resaco,0,'c18_contacorrente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($c18_sequencial=null) {
      $this->atualizacampos();
     $sql = " update conplanocontacorrente set ";
     $virgula = "";
     if(trim($this->c18_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c18_sequencial"])){
       $sql  .= $virgula." c18_sequencial = $this->c18_sequencial ";
       $virgula = ",";
       if(trim($this->c18_sequencial) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "c18_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c18_codcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c18_codcon"])){
       $sql  .= $virgula." c18_codcon = $this->c18_codcon ";
       $virgula = ",";
       if(trim($this->c18_codcon) == null ){
         $this->erro_sql = " Campo Conta PCASP nao Informado.";
         $this->erro_campo = "c18_codcon";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c18_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c18_anousu"])){
       $sql  .= $virgula." c18_anousu = $this->c18_anousu ";
       $virgula = ",";
       if(trim($this->c18_anousu) == null ){
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "c18_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c18_contacorrente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c18_contacorrente"])){
       $sql  .= $virgula." c18_contacorrente = $this->c18_contacorrente ";
       $virgula = ",";
       if(trim($this->c18_contacorrente) == null ){
         $this->erro_sql = " Campo Conta Corrente nao Informado.";
         $this->erro_campo = "c18_contacorrente";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c18_sequencial!=null){
       $sql .= " c18_sequencial = $this->c18_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c18_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18625,'$this->c18_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c18_sequencial"]) || $this->c18_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3298,18625,'".AddSlashes(pg_result($resaco,$conresaco,'c18_sequencial'))."','$this->c18_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c18_codcon"]) || $this->c18_codcon != "")
           $resac = db_query("insert into db_acount values($acount,3298,18627,'".AddSlashes(pg_result($resaco,$conresaco,'c18_codcon'))."','$this->c18_codcon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c18_anousu"]) || $this->c18_anousu != "")
           $resac = db_query("insert into db_acount values($acount,3298,18628,'".AddSlashes(pg_result($resaco,$conresaco,'c18_anousu'))."','$this->c18_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c18_contacorrente"]) || $this->c18_contacorrente != "")
           $resac = db_query("insert into db_acount values($acount,3298,18626,'".AddSlashes(pg_result($resaco,$conresaco,'c18_contacorrente'))."','$this->c18_contacorrente',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vinculo PCASP com conta corrente nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c18_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Vinculo PCASP com conta corrente nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c18_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c18_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($c18_sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c18_sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18625,'$c18_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3298,18625,'','".AddSlashes(pg_result($resaco,$iresaco,'c18_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3298,18627,'','".AddSlashes(pg_result($resaco,$iresaco,'c18_codcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3298,18628,'','".AddSlashes(pg_result($resaco,$iresaco,'c18_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3298,18626,'','".AddSlashes(pg_result($resaco,$iresaco,'c18_contacorrente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from conplanocontacorrente
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c18_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c18_sequencial = $c18_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vinculo PCASP com conta corrente nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c18_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Vinculo PCASP com conta corrente nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c18_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c18_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:conplanocontacorrente";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $c18_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from conplanocontacorrente ";
     $sql .= "      inner join conplano  on  conplano.c60_codcon = conplanocontacorrente.c18_codcon";
     $sql .= "      inner join contacorrente  on  contacorrente.c17_sequencial = conplanocontacorrente.c18_contacorrente";
     $sql .= "      inner join conclass  on  conclass.c51_codcla = conplano.c60_codcla";
     $sql .= "      inner join consistema  on  consistema.c52_codsis = conplano.c60_codsis";
     $sql .= "      inner join consistemaconta  on  consistemaconta.c65_sequencial = conplano.c60_consistemaconta";
     $sql2 = "";
     if($dbwhere==""){
       if($c18_sequencial!=null ){
         $sql2 .= " where conplanocontacorrente.c18_sequencial = $c18_sequencial ";
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

  function sql_query_conplano_contacorrente ( $c18_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
  	$sql .= " from conplanocontacorrente ";
  	$sql .= "      inner join conplano        on  conplano.c60_codcon = conplanocontacorrente.c18_codcon";
  	$sql .= "                                and  conplanocontacorrente.c18_anousu = conplano.c60_anousu";
  	$sql .= "      inner join conplanoreduz   on  conplanoreduz.c61_codcon = conplano.c60_codcon";
  	$sql .= "                                and  conplanoreduz.c61_anousu = conplano.c60_anousu";
  	$sql .= "      inner join contacorrente   on  contacorrente.c17_sequencial = conplanocontacorrente.c18_contacorrente";
  	$sql2 = "";
  	if($dbwhere==""){
  		if($c18_sequencial!=null ){
  			$sql2 .= " where conplanocontacorrente.c18_sequencial = $c18_sequencial ";
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
   function sql_query_file ( $c18_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from conplanocontacorrente ";
     $sql2 = "";
     if($dbwhere==""){
       if($c18_sequencial!=null ){
         $sql2 .= " where conplanocontacorrente.c18_sequencial = $c18_sequencial ";
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