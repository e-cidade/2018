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

//MODULO: pessoal
//CLASSE DA ENTIDADE rhempenhofolharhemprubrica
class cl_rhempenhofolharhemprubrica { 
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
   var $rh81_sequencial = 0; 
   var $rh81_rhempenhofolha = 0; 
   var $rh81_rhempenhofolharubrica = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh81_sequencial = int4 = Sequencial 
                 rh81_rhempenhofolha = int4 = rhempenhofolha 
                 rh81_rhempenhofolharubrica = int4 = rhempenhofolharubrica 
                 ";
   //funcao construtor da classe 
   function cl_rhempenhofolharhemprubrica() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhempenhofolharhemprubrica"); 
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
       $this->rh81_sequencial = ($this->rh81_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh81_sequencial"]:$this->rh81_sequencial);
       $this->rh81_rhempenhofolha = ($this->rh81_rhempenhofolha == ""?@$GLOBALS["HTTP_POST_VARS"]["rh81_rhempenhofolha"]:$this->rh81_rhempenhofolha);
       $this->rh81_rhempenhofolharubrica = ($this->rh81_rhempenhofolharubrica == ""?@$GLOBALS["HTTP_POST_VARS"]["rh81_rhempenhofolharubrica"]:$this->rh81_rhempenhofolharubrica);
     }else{
       $this->rh81_sequencial = ($this->rh81_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh81_sequencial"]:$this->rh81_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh81_sequencial){ 
      $this->atualizacampos();
     if($this->rh81_rhempenhofolha == null ){ 
       $this->erro_sql = " Campo rhempenhofolha nao Informado.";
       $this->erro_campo = "rh81_rhempenhofolha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh81_rhempenhofolharubrica == null ){ 
       $this->erro_sql = " Campo rhempenhofolharubrica nao Informado.";
       $this->erro_campo = "rh81_rhempenhofolharubrica";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh81_sequencial == "" || $rh81_sequencial == null ){
       $result = db_query("select nextval('rhempenhofolharhemprubrica_rh81_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhempenhofolharhemprubrica_rh81_sequencial_seq do campo: rh81_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh81_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhempenhofolharhemprubrica_rh81_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh81_sequencial)){
         $this->erro_sql = " Campo rh81_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh81_sequencial = $rh81_sequencial; 
       }
     }
     if(($this->rh81_sequencial == null) || ($this->rh81_sequencial == "") ){ 
       $this->erro_sql = " Campo rh81_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhempenhofolharhemprubrica(
                                       rh81_sequencial 
                                      ,rh81_rhempenhofolha 
                                      ,rh81_rhempenhofolharubrica 
                       )
                values (
                                $this->rh81_sequencial 
                               ,$this->rh81_rhempenhofolha 
                               ,$this->rh81_rhempenhofolharubrica 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "rhempenhofolharhemprubrica ($this->rh81_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "rhempenhofolharhemprubrica já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "rhempenhofolharhemprubrica ($this->rh81_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh81_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh81_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14412,'$this->rh81_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2543,14412,'','".AddSlashes(pg_result($resaco,0,'rh81_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2543,14414,'','".AddSlashes(pg_result($resaco,0,'rh81_rhempenhofolha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2543,14413,'','".AddSlashes(pg_result($resaco,0,'rh81_rhempenhofolharubrica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh81_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhempenhofolharhemprubrica set ";
     $virgula = "";
     if(trim($this->rh81_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh81_sequencial"])){ 
       $sql  .= $virgula." rh81_sequencial = $this->rh81_sequencial ";
       $virgula = ",";
       if(trim($this->rh81_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "rh81_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh81_rhempenhofolha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh81_rhempenhofolha"])){ 
       $sql  .= $virgula." rh81_rhempenhofolha = $this->rh81_rhempenhofolha ";
       $virgula = ",";
       if(trim($this->rh81_rhempenhofolha) == null ){ 
         $this->erro_sql = " Campo rhempenhofolha nao Informado.";
         $this->erro_campo = "rh81_rhempenhofolha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh81_rhempenhofolharubrica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh81_rhempenhofolharubrica"])){ 
       $sql  .= $virgula." rh81_rhempenhofolharubrica = $this->rh81_rhempenhofolharubrica ";
       $virgula = ",";
       if(trim($this->rh81_rhempenhofolharubrica) == null ){ 
         $this->erro_sql = " Campo rhempenhofolharubrica nao Informado.";
         $this->erro_campo = "rh81_rhempenhofolharubrica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh81_sequencial!=null){
       $sql .= " rh81_sequencial = $this->rh81_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh81_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14412,'$this->rh81_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh81_sequencial"]) || $this->rh81_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2543,14412,'".AddSlashes(pg_result($resaco,$conresaco,'rh81_sequencial'))."','$this->rh81_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh81_rhempenhofolha"]) || $this->rh81_rhempenhofolha != "")
           $resac = db_query("insert into db_acount values($acount,2543,14414,'".AddSlashes(pg_result($resaco,$conresaco,'rh81_rhempenhofolha'))."','$this->rh81_rhempenhofolha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh81_rhempenhofolharubrica"]) || $this->rh81_rhempenhofolharubrica != "")
           $resac = db_query("insert into db_acount values($acount,2543,14413,'".AddSlashes(pg_result($resaco,$conresaco,'rh81_rhempenhofolharubrica'))."','$this->rh81_rhempenhofolharubrica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhempenhofolharhemprubrica nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh81_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rhempenhofolharhemprubrica nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh81_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh81_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh81_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh81_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14412,'$rh81_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2543,14412,'','".AddSlashes(pg_result($resaco,$iresaco,'rh81_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2543,14414,'','".AddSlashes(pg_result($resaco,$iresaco,'rh81_rhempenhofolha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2543,14413,'','".AddSlashes(pg_result($resaco,$iresaco,'rh81_rhempenhofolharubrica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhempenhofolharhemprubrica
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh81_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh81_sequencial = $rh81_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhempenhofolharhemprubrica nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh81_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rhempenhofolharhemprubrica nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh81_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh81_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhempenhofolharhemprubrica";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh81_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhempenhofolharhemprubrica ";
     $sql .= "      inner join rhempenhofolha  on  rhempenhofolha.rh72_sequencial = rhempenhofolharhemprubrica.rh81_rhempenhofolha";
     $sql .= "      inner join rhempenhofolharubrica  on  rhempenhofolharubrica.rh73_sequencial = rhempenhofolharhemprubrica.rh81_rhempenhofolharubrica";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = rhempenhofolha.rh72_recurso";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = rhempenhofolha.rh72_codele and  orcelemento.o56_anousu = rhempenhofolha.rh72_anousu";
     $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = rhempenhofolha.rh72_anousu and  orcprojativ.o55_projativ = rhempenhofolha.rh72_projativ";
     $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = rhempenhofolha.rh72_anousu and  orcunidade.o41_orgao = rhempenhofolha.rh72_orgao and  orcunidade.o41_unidade = rhempenhofolha.rh72_unidade";
     $sql .= "      left  join orcdotacao  on  orcdotacao.o58_anousu = rhempenhofolha.rh72_coddot and  orcdotacao.o58_coddot = rhempenhofolha.rh72_anousu";
     $sql .= "      inner join db_config  on  db_config.codigo = rhempenhofolharubrica.rh73_instit";
     $sql .= "      inner join rhpessoalmov  on  rhpessoalmov.rh02_seqpes = rhempenhofolharubrica.rh73_seqpes and  rhpessoalmov.rh02_instit = rhempenhofolharubrica.rh73_instit";
     $sql .= "      inner join rhrubricas  on  rhrubricas.rh27_rubric = rhempenhofolharubrica.rh73_rubric and  rhrubricas.rh27_instit = rhempenhofolharubrica.rh73_instit";
     $sql2 = "";
     if($dbwhere==""){
       if($rh81_sequencial!=null ){
         $sql2 .= " where rhempenhofolharhemprubrica.rh81_sequencial = $rh81_sequencial "; 
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
   function sql_query_file ( $rh81_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhempenhofolharhemprubrica ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh81_sequencial!=null ){
         $sql2 .= " where rhempenhofolharhemprubrica.rh81_sequencial = $rh81_sequencial "; 
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