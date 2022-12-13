<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//MODULO: Compras
//CLASSE DA ENTIDADE pcfornecertifrenovacao
class cl_pcfornecertifrenovacao { 
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
   var $pc35_sequencial = 0; 
   var $pc35_pcfornecertiforiginal = 0; 
   var $pc35_fornecertffilho = 0; 
   var $pc35_datarenovacao_dia = null; 
   var $pc35_datarenovacao_mes = null; 
   var $pc35_datarenovacao_ano = null; 
   var $pc35_datarenovacao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc35_sequencial = int4 = C�digo Sequencial 
                 pc35_pcfornecertiforiginal = int4 = Certificado Original 
                 pc35_fornecertffilho = int4 = Certificado Novo 
                 pc35_datarenovacao = date = Data Vinculo 
                 ";
   //funcao construtor da classe 
   function cl_pcfornecertifrenovacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pcfornecertifrenovacao"); 
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
       $this->pc35_sequencial = ($this->pc35_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc35_sequencial"]:$this->pc35_sequencial);
       $this->pc35_pcfornecertiforiginal = ($this->pc35_pcfornecertiforiginal == ""?@$GLOBALS["HTTP_POST_VARS"]["pc35_pcfornecertiforiginal"]:$this->pc35_pcfornecertiforiginal);
       $this->pc35_fornecertffilho = ($this->pc35_fornecertffilho == ""?@$GLOBALS["HTTP_POST_VARS"]["pc35_fornecertffilho"]:$this->pc35_fornecertffilho);
       if($this->pc35_datarenovacao == ""){
         $this->pc35_datarenovacao_dia = ($this->pc35_datarenovacao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc35_datarenovacao_dia"]:$this->pc35_datarenovacao_dia);
         $this->pc35_datarenovacao_mes = ($this->pc35_datarenovacao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["pc35_datarenovacao_mes"]:$this->pc35_datarenovacao_mes);
         $this->pc35_datarenovacao_ano = ($this->pc35_datarenovacao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["pc35_datarenovacao_ano"]:$this->pc35_datarenovacao_ano);
         if($this->pc35_datarenovacao_dia != ""){
            $this->pc35_datarenovacao = $this->pc35_datarenovacao_ano."-".$this->pc35_datarenovacao_mes."-".$this->pc35_datarenovacao_dia;
         }
       }
     }else{
       $this->pc35_sequencial = ($this->pc35_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc35_sequencial"]:$this->pc35_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($pc35_sequencial){ 
      $this->atualizacampos();
     if($this->pc35_pcfornecertiforiginal == null ){ 
       $this->erro_sql = " Campo Certificado Original nao Informado.";
       $this->erro_campo = "pc35_pcfornecertiforiginal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc35_fornecertffilho == null ){ 
       $this->pc35_fornecertffilho = "0";
     }
     if($this->pc35_datarenovacao == null ){ 
       $this->pc35_datarenovacao = "null";
     }
     if($pc35_sequencial == "" || $pc35_sequencial == null ){
       $result = db_query("select nextval('pcfonecertifrenovacao_pc35_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: pcfonecertifrenovacao_pc35_sequencial_seq do campo: pc35_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->pc35_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from pcfonecertifrenovacao_pc35_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc35_sequencial)){
         $this->erro_sql = " Campo pc35_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc35_sequencial = $pc35_sequencial; 
       }
     }
     if(($this->pc35_sequencial == null) || ($this->pc35_sequencial == "") ){ 
       $this->erro_sql = " Campo pc35_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pcfornecertifrenovacao(
                                       pc35_sequencial 
                                      ,pc35_pcfornecertiforiginal 
                                      ,pc35_fornecertffilho 
                                      ,pc35_datarenovacao 
                       )
                values (
                                $this->pc35_sequencial 
                               ,$this->pc35_pcfornecertiforiginal 
                               ,$this->pc35_fornecertffilho 
                               ,".($this->pc35_datarenovacao == "null" || $this->pc35_datarenovacao == ""?"null":"'".$this->pc35_datarenovacao."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "pcfornecertifrenovacao ($this->pc35_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "pcfornecertifrenovacao j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "pcfornecertifrenovacao ($this->pc35_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc35_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc35_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16558,'$this->pc35_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2907,16558,'','".AddSlashes(pg_result($resaco,0,'pc35_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2907,16559,'','".AddSlashes(pg_result($resaco,0,'pc35_pcfornecertiforiginal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2907,16561,'','".AddSlashes(pg_result($resaco,0,'pc35_fornecertffilho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2907,16562,'','".AddSlashes(pg_result($resaco,0,'pc35_datarenovacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc35_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update pcfornecertifrenovacao set ";
     $virgula = "";
     if(trim($this->pc35_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc35_sequencial"])){ 
       $sql  .= $virgula." pc35_sequencial = $this->pc35_sequencial ";
       $virgula = ",";
       if(trim($this->pc35_sequencial) == null ){ 
         $this->erro_sql = " Campo C�digo Sequencial nao Informado.";
         $this->erro_campo = "pc35_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc35_pcfornecertiforiginal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc35_pcfornecertiforiginal"])){ 
       $sql  .= $virgula." pc35_pcfornecertiforiginal = $this->pc35_pcfornecertiforiginal ";
       $virgula = ",";
       if(trim($this->pc35_pcfornecertiforiginal) == null ){ 
         $this->erro_sql = " Campo Certificado Original nao Informado.";
         $this->erro_campo = "pc35_pcfornecertiforiginal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc35_fornecertffilho)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc35_fornecertffilho"])){ 
        if(trim($this->pc35_fornecertffilho)=="" && isset($GLOBALS["HTTP_POST_VARS"]["pc35_fornecertffilho"])){ 
           $this->pc35_fornecertffilho = "0" ; 
        } 
       $sql  .= $virgula." pc35_fornecertffilho = $this->pc35_fornecertffilho ";
       $virgula = ",";
     }
     if(trim($this->pc35_datarenovacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc35_datarenovacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["pc35_datarenovacao_dia"] !="") ){ 
       $sql  .= $virgula." pc35_datarenovacao = '$this->pc35_datarenovacao' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["pc35_datarenovacao_dia"])){ 
         $sql  .= $virgula." pc35_datarenovacao = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($pc35_sequencial!=null){
       $sql .= " pc35_sequencial = $this->pc35_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc35_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16558,'$this->pc35_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc35_sequencial"]) || $this->pc35_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2907,16558,'".AddSlashes(pg_result($resaco,$conresaco,'pc35_sequencial'))."','$this->pc35_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc35_pcfornecertiforiginal"]) || $this->pc35_pcfornecertiforiginal != "")
           $resac = db_query("insert into db_acount values($acount,2907,16559,'".AddSlashes(pg_result($resaco,$conresaco,'pc35_pcfornecertiforiginal'))."','$this->pc35_pcfornecertiforiginal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc35_fornecertffilho"]) || $this->pc35_fornecertffilho != "")
           $resac = db_query("insert into db_acount values($acount,2907,16561,'".AddSlashes(pg_result($resaco,$conresaco,'pc35_fornecertffilho'))."','$this->pc35_fornecertffilho',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc35_datarenovacao"]) || $this->pc35_datarenovacao != "")
           $resac = db_query("insert into db_acount values($acount,2907,16562,'".AddSlashes(pg_result($resaco,$conresaco,'pc35_datarenovacao'))."','$this->pc35_datarenovacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "pcfornecertifrenovacao nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc35_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "pcfornecertifrenovacao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc35_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc35_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc35_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc35_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16558,'$pc35_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2907,16558,'','".AddSlashes(pg_result($resaco,$iresaco,'pc35_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2907,16559,'','".AddSlashes(pg_result($resaco,$iresaco,'pc35_pcfornecertiforiginal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2907,16561,'','".AddSlashes(pg_result($resaco,$iresaco,'pc35_fornecertffilho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2907,16562,'','".AddSlashes(pg_result($resaco,$iresaco,'pc35_datarenovacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pcfornecertifrenovacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc35_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc35_sequencial = $pc35_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "pcfornecertifrenovacao nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc35_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "pcfornecertifrenovacao nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc35_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc35_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:pcfornecertifrenovacao";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $pc35_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcfornecertifrenovacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc35_sequencial!=null ){
         $sql2 .= " where pcfornecertifrenovacao.pc35_sequencial = $pc35_sequencial "; 
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
   function sql_query_file ( $pc35_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcfornecertifrenovacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc35_sequencial!=null ){
         $sql2 .= " where pcfornecertifrenovacao.pc35_sequencial = $pc35_sequencial "; 
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