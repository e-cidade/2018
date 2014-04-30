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

//MODULO: pessoal
//CLASSE DA ENTIDADE rhempenhofolharubricaplanilha
class cl_rhempenhofolharubricaplanilha { 
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
   var $rh111_sequencial = 0; 
   var $rh111_rhempenhofolharubrica = 0; 
   var $rh111_placaixarec = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh111_sequencial = int4 = Sequencial 
                 rh111_rhempenhofolharubrica = int4 = rhempenhofolharubrica 
                 rh111_placaixarec = int4 = placaixarec 
                 ";
   //funcao construtor da classe 
   function cl_rhempenhofolharubricaplanilha() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhempenhofolharubricaplanilha"); 
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
       $this->rh111_sequencial = ($this->rh111_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh111_sequencial"]:$this->rh111_sequencial);
       $this->rh111_rhempenhofolharubrica = ($this->rh111_rhempenhofolharubrica == ""?@$GLOBALS["HTTP_POST_VARS"]["rh111_rhempenhofolharubrica"]:$this->rh111_rhempenhofolharubrica);
       $this->rh111_placaixarec = ($this->rh111_placaixarec == ""?@$GLOBALS["HTTP_POST_VARS"]["rh111_placaixarec"]:$this->rh111_placaixarec);
     }else{
       $this->rh111_sequencial = ($this->rh111_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh111_sequencial"]:$this->rh111_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh111_sequencial){ 
      $this->atualizacampos();
     if($this->rh111_rhempenhofolharubrica == null ){ 
       $this->erro_sql = " Campo rhempenhofolharubrica nao Informado.";
       $this->erro_campo = "rh111_rhempenhofolharubrica";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh111_placaixarec == null ){ 
       $this->erro_sql = " Campo placaixarec nao Informado.";
       $this->erro_campo = "rh111_placaixarec";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh111_sequencial == "" || $rh111_sequencial == null ){
       $result = db_query("select nextval('rhempenhofolharubricaplanilha_rh111_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhempenhofolharubricaplanilha_rh111_sequencial_seq do campo: rh111_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh111_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhempenhofolharubricaplanilha_rh111_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh111_sequencial)){
         $this->erro_sql = " Campo rh111_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh111_sequencial = $rh111_sequencial; 
       }
     }
     if(($this->rh111_sequencial == null) || ($this->rh111_sequencial == "") ){ 
       $this->erro_sql = " Campo rh111_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhempenhofolharubricaplanilha(
                                       rh111_sequencial 
                                      ,rh111_rhempenhofolharubrica 
                                      ,rh111_placaixarec 
                       )
                values (
                                $this->rh111_sequencial 
                               ,$this->rh111_rhempenhofolharubrica 
                               ,$this->rh111_placaixarec 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Planilhas geradas para as rubricas dos empenhos ($this->rh111_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Planilhas geradas para as rubricas dos empenhos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Planilhas geradas para as rubricas dos empenhos ($this->rh111_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh111_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh111_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19184,'$this->rh111_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3408,19184,'','".AddSlashes(pg_result($resaco,0,'rh111_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3408,19185,'','".AddSlashes(pg_result($resaco,0,'rh111_rhempenhofolharubrica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3408,19186,'','".AddSlashes(pg_result($resaco,0,'rh111_placaixarec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh111_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhempenhofolharubricaplanilha set ";
     $virgula = "";
     if(trim($this->rh111_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh111_sequencial"])){ 
       $sql  .= $virgula." rh111_sequencial = $this->rh111_sequencial ";
       $virgula = ",";
       if(trim($this->rh111_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "rh111_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh111_rhempenhofolharubrica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh111_rhempenhofolharubrica"])){ 
       $sql  .= $virgula." rh111_rhempenhofolharubrica = $this->rh111_rhempenhofolharubrica ";
       $virgula = ",";
       if(trim($this->rh111_rhempenhofolharubrica) == null ){ 
         $this->erro_sql = " Campo rhempenhofolharubrica nao Informado.";
         $this->erro_campo = "rh111_rhempenhofolharubrica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh111_placaixarec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh111_placaixarec"])){ 
       $sql  .= $virgula." rh111_placaixarec = $this->rh111_placaixarec ";
       $virgula = ",";
       if(trim($this->rh111_placaixarec) == null ){ 
         $this->erro_sql = " Campo placaixarec nao Informado.";
         $this->erro_campo = "rh111_placaixarec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh111_sequencial!=null){
       $sql .= " rh111_sequencial = $this->rh111_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh111_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19184,'$this->rh111_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh111_sequencial"]) || $this->rh111_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3408,19184,'".AddSlashes(pg_result($resaco,$conresaco,'rh111_sequencial'))."','$this->rh111_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh111_rhempenhofolharubrica"]) || $this->rh111_rhempenhofolharubrica != "")
           $resac = db_query("insert into db_acount values($acount,3408,19185,'".AddSlashes(pg_result($resaco,$conresaco,'rh111_rhempenhofolharubrica'))."','$this->rh111_rhempenhofolharubrica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh111_placaixarec"]) || $this->rh111_placaixarec != "")
           $resac = db_query("insert into db_acount values($acount,3408,19186,'".AddSlashes(pg_result($resaco,$conresaco,'rh111_placaixarec'))."','$this->rh111_placaixarec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Planilhas geradas para as rubricas dos empenhos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh111_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Planilhas geradas para as rubricas dos empenhos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh111_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh111_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh111_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh111_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19184,'$rh111_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3408,19184,'','".AddSlashes(pg_result($resaco,$iresaco,'rh111_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3408,19185,'','".AddSlashes(pg_result($resaco,$iresaco,'rh111_rhempenhofolharubrica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3408,19186,'','".AddSlashes(pg_result($resaco,$iresaco,'rh111_placaixarec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhempenhofolharubricaplanilha
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh111_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh111_sequencial = $rh111_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Planilhas geradas para as rubricas dos empenhos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh111_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Planilhas geradas para as rubricas dos empenhos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh111_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh111_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhempenhofolharubricaplanilha";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh111_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhempenhofolharubricaplanilha ";
     $sql .= "      inner join placaixarec  on  placaixarec.k81_seqpla = rhempenhofolharubricaplanilha.rh111_placaixarec";
     $sql .= "      inner join rhempenhofolharubrica  on  rhempenhofolharubrica.rh73_sequencial = rhempenhofolharubricaplanilha.rh111_rhempenhofolharubrica";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = placaixarec.k81_numcgm";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = placaixarec.k81_receita";
     $sql .= "      inner join saltes  on  saltes.k13_conta = placaixarec.k81_conta";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = placaixarec.k81_codigo";
     $sql .= "      inner join placaixa  on  placaixa.k80_codpla = placaixarec.k81_codpla";
     $sql .= "      inner join concarpeculiar  on  concarpeculiar.c58_sequencial = placaixarec.k81_concarpeculiar";
     $sql .= "      inner join db_config  on  db_config.codigo = rhempenhofolharubrica.rh73_instit";
     $sql .= "      inner join rhpessoalmov  on  rhpessoalmov.rh02_seqpes = rhempenhofolharubrica.rh73_seqpes and  rhpessoalmov.rh02_instit = rhempenhofolharubrica.rh73_instit";
     $sql .= "      inner join rhrubricas  on  rhrubricas.rh27_rubric = rhempenhofolharubrica.rh73_rubric and  rhrubricas.rh27_instit = rhempenhofolharubrica.rh73_instit";
     $sql2 = "";
     if($dbwhere==""){
       if($rh111_sequencial!=null ){
         $sql2 .= " where rhempenhofolharubricaplanilha.rh111_sequencial = $rh111_sequencial "; 
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
   function sql_query_file ( $rh111_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhempenhofolharubricaplanilha ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh111_sequencial!=null ){
         $sql2 .= " where rhempenhofolharubricaplanilha.rh111_sequencial = $rh111_sequencial "; 
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