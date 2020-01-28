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
//CLASSE DA ENTIDADE ppaestimativareceita
class cl_ppaestimativareceita {
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
   var $o06_sequencial = 0;
   var $o06_ppaestimativa = 0;
   var $o06_codrec = 0;
   var $o06_anousu = 0;
   var $o06_ppaversao = 0;
   var $o06_concarpeculiar = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 o06_sequencial = int4 = Código Sequencial
                 o06_ppaestimativa = int4 = Código Sequencial
                 o06_codrec = int4 = Receita
                 o06_anousu = int4 = Ano do Exercicio
                 o06_ppaversao = int4 = Perspectiva do ppa
                 o06_concarpeculiar = varchar(100) = Caracteristica Peculiar
                 ";
   //funcao construtor da classe
   function cl_ppaestimativareceita() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("ppaestimativareceita");
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
       $this->o06_sequencial = ($this->o06_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o06_sequencial"]:$this->o06_sequencial);
       $this->o06_ppaestimativa = ($this->o06_ppaestimativa == ""?@$GLOBALS["HTTP_POST_VARS"]["o06_ppaestimativa"]:$this->o06_ppaestimativa);
       $this->o06_codrec = ($this->o06_codrec == ""?@$GLOBALS["HTTP_POST_VARS"]["o06_codrec"]:$this->o06_codrec);
       $this->o06_anousu = ($this->o06_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o06_anousu"]:$this->o06_anousu);
       $this->o06_ppaversao = ($this->o06_ppaversao == ""?@$GLOBALS["HTTP_POST_VARS"]["o06_ppaversao"]:$this->o06_ppaversao);
       $this->o06_concarpeculiar = ($this->o06_concarpeculiar == ""?@$GLOBALS["HTTP_POST_VARS"]["o06_concarpeculiar"]:$this->o06_concarpeculiar);
     }else{
       $this->o06_sequencial = ($this->o06_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o06_sequencial"]:$this->o06_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($o06_sequencial){
      $this->atualizacampos();
     if($this->o06_ppaestimativa == null ){
       $this->erro_sql = " Campo Código Sequencial nao Informado.";
       $this->erro_campo = "o06_ppaestimativa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o06_codrec == null ){
       $this->erro_sql = " Campo Receita nao Informado.";
       $this->erro_campo = "o06_codrec";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o06_anousu == null ){
       $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
       $this->erro_campo = "o06_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o06_ppaversao == null ){
       $this->erro_sql = " Campo Perspectiva do ppa nao Informado.";
       $this->erro_campo = "o06_ppaversao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o06_concarpeculiar == null ){
       $this->erro_sql = " Campo Caracteristica Peculiar nao Informado.";
       $this->erro_campo = "o06_concarpeculiar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o06_sequencial == "" || $o06_sequencial == null ){
       $result = db_query("select nextval('ppaestimativareceita_o06_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: ppaestimativareceita_o06_sequencial_seq do campo: o06_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->o06_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from ppaestimativareceita_o06_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o06_sequencial)){
         $this->erro_sql = " Campo o06_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o06_sequencial = $o06_sequencial;
       }
     }
     if(($this->o06_sequencial == null) || ($this->o06_sequencial == "") ){
       $this->erro_sql = " Campo o06_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into ppaestimativareceita(
                                       o06_sequencial
                                      ,o06_ppaestimativa
                                      ,o06_codrec
                                      ,o06_anousu
                                      ,o06_ppaversao
                                      ,o06_concarpeculiar
                       )
                values (
                                $this->o06_sequencial
                               ,$this->o06_ppaestimativa
                               ,$this->o06_codrec
                               ,$this->o06_anousu
                               ,$this->o06_ppaversao
                               ,'$this->o06_concarpeculiar'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Receitas da estimativa ($this->o06_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Receitas da estimativa já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Receitas da estimativa ($this->o06_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o06_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o06_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,13612,'$this->o06_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2384,13612,'','".AddSlashes(pg_result($resaco,0,'o06_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2384,13613,'','".AddSlashes(pg_result($resaco,0,'o06_ppaestimativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2384,13614,'','".AddSlashes(pg_result($resaco,0,'o06_codrec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2384,2741,'','".AddSlashes(pg_result($resaco,0,'o06_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2384,14461,'','".AddSlashes(pg_result($resaco,0,'o06_ppaversao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2384,14499,'','".AddSlashes(pg_result($resaco,0,'o06_concarpeculiar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($o06_sequencial=null) {
      $this->atualizacampos();
     $sql = " update ppaestimativareceita set ";
     $virgula = "";
     if(trim($this->o06_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o06_sequencial"])){
       $sql  .= $virgula." o06_sequencial = $this->o06_sequencial ";
       $virgula = ",";
       if(trim($this->o06_sequencial) == null ){
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "o06_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o06_ppaestimativa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o06_ppaestimativa"])){
       $sql  .= $virgula." o06_ppaestimativa = $this->o06_ppaestimativa ";
       $virgula = ",";
       if(trim($this->o06_ppaestimativa) == null ){
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "o06_ppaestimativa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o06_codrec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o06_codrec"])){
       $sql  .= $virgula." o06_codrec = $this->o06_codrec ";
       $virgula = ",";
       if(trim($this->o06_codrec) == null ){
         $this->erro_sql = " Campo Receita nao Informado.";
         $this->erro_campo = "o06_codrec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o06_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o06_anousu"])){
       $sql  .= $virgula." o06_anousu = $this->o06_anousu ";
       $virgula = ",";
       if(trim($this->o06_anousu) == null ){
         $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
         $this->erro_campo = "o06_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o06_ppaversao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o06_ppaversao"])){
       $sql  .= $virgula." o06_ppaversao = $this->o06_ppaversao ";
       $virgula = ",";
       if(trim($this->o06_ppaversao) == null ){
         $this->erro_sql = " Campo Perspectiva do ppa nao Informado.";
         $this->erro_campo = "o06_ppaversao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o06_concarpeculiar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o06_concarpeculiar"])){
       $sql  .= $virgula." o06_concarpeculiar = '$this->o06_concarpeculiar' ";
       $virgula = ",";
       if(trim($this->o06_concarpeculiar) == null ){
         $this->erro_sql = " Campo Caracteristica Peculiar nao Informado.";
         $this->erro_campo = "o06_concarpeculiar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o06_sequencial!=null){
       $sql .= " o06_sequencial = $this->o06_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o06_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13612,'$this->o06_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o06_sequencial"]) || $this->o06_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2384,13612,'".AddSlashes(pg_result($resaco,$conresaco,'o06_sequencial'))."','$this->o06_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o06_ppaestimativa"]) || $this->o06_ppaestimativa != "")
           $resac = db_query("insert into db_acount values($acount,2384,13613,'".AddSlashes(pg_result($resaco,$conresaco,'o06_ppaestimativa'))."','$this->o06_ppaestimativa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o06_codrec"]) || $this->o06_codrec != "")
           $resac = db_query("insert into db_acount values($acount,2384,13614,'".AddSlashes(pg_result($resaco,$conresaco,'o06_codrec'))."','$this->o06_codrec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o06_anousu"]) || $this->o06_anousu != "")
           $resac = db_query("insert into db_acount values($acount,2384,2741,'".AddSlashes(pg_result($resaco,$conresaco,'o06_anousu'))."','$this->o06_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o06_ppaversao"]) || $this->o06_ppaversao != "")
           $resac = db_query("insert into db_acount values($acount,2384,14461,'".AddSlashes(pg_result($resaco,$conresaco,'o06_ppaversao'))."','$this->o06_ppaversao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o06_concarpeculiar"]) || $this->o06_concarpeculiar != "")
           $resac = db_query("insert into db_acount values($acount,2384,14499,'".AddSlashes(pg_result($resaco,$conresaco,'o06_concarpeculiar'))."','$this->o06_concarpeculiar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Receitas da estimativa nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o06_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Receitas da estimativa nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o06_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o06_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($o06_sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o06_sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13612,'$o06_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2384,13612,'','".AddSlashes(pg_result($resaco,$iresaco,'o06_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2384,13613,'','".AddSlashes(pg_result($resaco,$iresaco,'o06_ppaestimativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2384,13614,'','".AddSlashes(pg_result($resaco,$iresaco,'o06_codrec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2384,2741,'','".AddSlashes(pg_result($resaco,$iresaco,'o06_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2384,14461,'','".AddSlashes(pg_result($resaco,$iresaco,'o06_ppaversao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2384,14499,'','".AddSlashes(pg_result($resaco,$iresaco,'o06_concarpeculiar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from ppaestimativareceita
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o06_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o06_sequencial = $o06_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Receitas da estimativa nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o06_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Receitas da estimativa nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o06_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o06_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:ppaestimativareceita";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $o06_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from ppaestimativareceita ";
     $sql .= "      inner join orcfontes  on  orcfontes.o57_codfon = ppaestimativareceita.o06_codrec and  orcfontes.o57_anousu = ppaestimativareceita.o06_anousu";
     $sql .= "      inner join concarpeculiar  on  concarpeculiar.c58_sequencial = ppaestimativareceita.o06_concarpeculiar";
     $sql .= "      inner join ppaestimativa  on  ppaestimativa.o05_sequencial = ppaestimativareceita.o06_ppaestimativa";
     $sql .= "      inner join ppaversao  on  ppaversao.o119_sequencial = ppaestimativareceita.o06_ppaversao";
     $sql .= "      inner join ppaversao  as a on   a.o119_sequencial = ppaestimativa.o05_ppaversao";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = ppaversao.o119_idusuario";
     $sql .= "      inner join ppalei  on  ppalei.o01_sequencial = ppaversao.o119_ppalei";
     $sql2 = "";
     if($dbwhere==""){
       if($o06_sequencial!=null ){
         $sql2 .= " where ppaestimativareceita.o06_sequencial = $o06_sequencial ";
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
   function sql_query_file ( $o06_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from ppaestimativareceita ";
     $sql2 = "";
     if($dbwhere==""){
       if($o06_sequencial!=null ){
         $sql2 .= " where ppaestimativareceita.o06_sequencial = $o06_sequencial ";
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
   function sql_query_analitica ( $o06_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from ppaestimativareceita ";
     $sql .= "      inner join orcfontes  on  orcfontes.o57_codfon = ppaestimativareceita.o06_codrec
                                         and  orcfontes.o57_anousu = ppaestimativareceita.o06_anousu";
     $sql .= "      inner join ppaestimativa  on  ppaestimativa.o05_sequencial = ppaestimativareceita.o06_ppaestimativa";
     $sql .= "      inner join ppaversao  on  ppaversao.o119_sequencial = ppaestimativa.o05_ppaversao";
     $sql .= "      inner join ppalei  on  ppalei.o01_sequencial = ppaversao.o119_ppalei";
     $sql .= "      inner join concarpeculiar  on  o06_concarpeculiar = c58_sequencial";
     $sql .= "      inner join conplano  on  o57_codfon = c60_codcon";
     $sql .= "                          and o57_anousu = c60_anousu";
     $sql .= "      inner join conplanoreduz  on  c60_codcon = c61_codcon";
     $sql .= "                          and c61_anousu = c60_anousu";
     $sql .= "      left join orctiporec on  c61_codigo = o15_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($o06_sequencial!=null ){
         $sql2 .= " where ppaestimativareceita.o06_sequencial = $o06_sequencial ";
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
     return analiseQueryPlanoOrcamento($sql);
  }
   public function sql_query_receita_integrada($o06_sequencial=null,$campos="*",$ordem=null,$dbwhere="") {

    $sql = "select ";
    if ($campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    } else {
       $sql .= $campos;
    }
    $sql .= " From ppaestimativareceita  ";
    $sql .= "      inner join ppaestimativa        on o06_ppaestimativa = o05_sequencial ";
    $sql .= "      inner join ppaversao            on o05_ppaversao  = o119_sequencial  ";
    $sql .= "      inner join ppaintegracao        on o123_ppaversao = o119_sequencial  ";
    $sql .= "      left  join ppaintegracaoreceita on o122_ppaestimativareceita = o06_sequencial ";
    $sql .= "                                     and o122_ppaintegracao  = o123_sequencial ";
    $sql .= "      left join orcreceita            on o70_anousu          = o06_anousu ";
    $sql .= "                                     and o70_codfon          = o06_codrec ";
    $sql .= "                                     and o70_concarpeculiar = o06_concarpeculiar ";
    $sql .= "      inner join conplanoreduz on c61_codcon = o06_codrec  ";
    $sql .= "                              and c61_anousu = o06_anousu  ";
    $sql .= "      inner join orcfontes     on o57_codfon = o06_codrec  ";
    $sql .= "                              and o57_anousu = o06_anousu  ";
    if ($dbwhere=="") {
      if ($o08_sequencial!=null ) {
         $sql2 .= " where o06_sequencial = $o08_sequencial ";
      }
    } else if ($dbwhere != "") {
       $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {

      $sql       .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return analiseQueryPlanoOrcamento($sql);
  }


   function sql_query_soma_receita_ano ($o06_ano, $o06_codfon,$iEstrutural, $iCodigoLei, $base = false, $sWhereRec = "",
                                        $sInstituicoes='', $iConcarpeculiar = 0){

     if (empty($sInstituicoes)) {
       $sInstituicoes= db_getsession("DB_instit");
     }
     $sWhere = "";
     if ($o06_codfon != null) {
       $sWhere .= " and ppaestimativareceita.o06_codrec  = {$o06_codfon}";
     }
     if ($iEstrutural != null) {
       $sWhere .= " and o57_fonte  like '{$iEstrutural}%'";
     } else {
       $sWhere .= " and o06_concarpeculiar = '{$iConcarpeculiar}'";
     }
     $sWhere .= $sWhereRec;
     if ($o06_ano < 2008 and $iEstrutural == "4") {
       $sWhere .= " and o57_fonte not like '49%'";
     }
     $sBase = $base==true?"true":"false";
     $sCampos = "round(o05_valor,2)";
     if ($base) {
       $sCampos = "round(o05_valor,2)";
     }
     $sql = "select sum({$sCampos}) as valorreceita";
     $sql .= " from ppaestimativareceita ";
     $sql .= "      inner join orcfontes      on  orcfontes.o57_codfon = ppaestimativareceita.o06_codrec and  orcfontes.o57_anousu = ppaestimativareceita.o06_anousu";
     $sql .= "      left  join conplanoreduz  on  orcfontes.o57_anousu = c61_anousu and c61_codcon = o57_codfon";
     $sql .= "      inner join ppaestimativa  on  ppaestimativa.o05_sequencial = ppaestimativareceita.o06_ppaestimativa";
     $sql2 = "";
     $sql2 .= " where o06_anousu    = {$o06_ano} ";
     $sql2 .= "   and o05_ppaversao = {$iCodigoLei}";
     $sql2 .= "   and o06_ppaversao = {$iCodigoLei}";
     $sql2 .= "       $sWhere ";
     $sql2 .= "   and o05_base is {$sBase}";
     $sql2 .= "   and c61_instit in({$sInstituicoes})";
     //$sql2 .= " group by o57_codfon ";
     $sql .= $sql2;
     return analiseQueryPlanoOrcamento($sql, $o06_ano);
  }



    public function sql_query_estimativa_planoconta($o06_sequencial=null,$campos="*",$ordem=null,$dbwhere="") {

    $sql = "select ";
    if ($campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    } else {
       $sql .= $campos;
    }
    $sql .= " from ppaestimativareceita  ";
    $sql .= "        inner join ppaestimativa  on o06_ppaestimativa = o05_sequencial ";
    $sql .= "        inner join orcfontes      on o06_codrec        = o57_codfon     ";
    $sql .= "                                 and o57_anousu        = o06_anousu     ";
    $sql .= "         inner join conplano      on  o57_codfon = c60_codcon";
    $sql .= "                                 and o57_anousu = c60_anousu";
    $sql .= "         inner join conplanoreduz on  c60_codcon = c61_codcon";
    $sql .= "                                 and c61_anousu = c60_anousu";
    if ($dbwhere=="") {
      if ($o08_sequencial!=null ) {
         $sql2 .= " where o06_sequencial = $o08_sequencial ";
      }
    } else if ($dbwhere != "") {
       $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {

      $sql       .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return analiseQueryPlanoOrcamento($sql);
  }
}
?>