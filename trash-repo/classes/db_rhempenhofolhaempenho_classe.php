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
//CLASSE DA ENTIDADE rhempenhofolhaempenho
class cl_rhempenhofolhaempenho { 
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
   var $rh76_sequencial = 0; 
   var $rh76_rhempenhofolha = 0; 
   var $rh76_numemp = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh76_sequencial = int4 = Código Sequencial 
                 rh76_rhempenhofolha = int4 = Empenho da Folha 
                 rh76_numemp = int4 = Número do Empenho 
                 ";
   //funcao construtor da classe 
   function cl_rhempenhofolhaempenho() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhempenhofolhaempenho"); 
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
       $this->rh76_sequencial = ($this->rh76_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh76_sequencial"]:$this->rh76_sequencial);
       $this->rh76_rhempenhofolha = ($this->rh76_rhempenhofolha == ""?@$GLOBALS["HTTP_POST_VARS"]["rh76_rhempenhofolha"]:$this->rh76_rhempenhofolha);
       $this->rh76_numemp = ($this->rh76_numemp == ""?@$GLOBALS["HTTP_POST_VARS"]["rh76_numemp"]:$this->rh76_numemp);
     }else{
       $this->rh76_sequencial = ($this->rh76_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh76_sequencial"]:$this->rh76_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh76_sequencial){ 
      $this->atualizacampos();
     if($this->rh76_rhempenhofolha == null ){ 
       $this->erro_sql = " Campo Empenho da Folha nao Informado.";
       $this->erro_campo = "rh76_rhempenhofolha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh76_numemp == null ){ 
       $this->erro_sql = " Campo Número do Empenho nao Informado.";
       $this->erro_campo = "rh76_numemp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh76_sequencial == "" || $rh76_sequencial == null ){
       $result = db_query("select nextval('rhempenhofolhaempenho_rh76_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhempenhofolhaempenho_rh76_sequencial_seq do campo: rh76_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh76_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhempenhofolhaempenho_rh76_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh76_sequencial)){
         $this->erro_sql = " Campo rh76_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh76_sequencial = $rh76_sequencial; 
       }
     }
     if(($this->rh76_sequencial == null) || ($this->rh76_sequencial == "") ){ 
       $this->erro_sql = " Campo rh76_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhempenhofolhaempenho(
                                       rh76_sequencial 
                                      ,rh76_rhempenhofolha 
                                      ,rh76_numemp 
                       )
                values (
                                $this->rh76_sequencial 
                               ,$this->rh76_rhempenhofolha 
                               ,$this->rh76_numemp 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Empenhos da folha ($this->rh76_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Empenhos da folha já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Empenhos da folha ($this->rh76_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh76_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh76_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14393,'$this->rh76_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2538,14393,'','".AddSlashes(pg_result($resaco,0,'rh76_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2538,14394,'','".AddSlashes(pg_result($resaco,0,'rh76_rhempenhofolha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2538,14395,'','".AddSlashes(pg_result($resaco,0,'rh76_numemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh76_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhempenhofolhaempenho set ";
     $virgula = "";
     if(trim($this->rh76_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh76_sequencial"])){ 
       $sql  .= $virgula." rh76_sequencial = $this->rh76_sequencial ";
       $virgula = ",";
       if(trim($this->rh76_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "rh76_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh76_rhempenhofolha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh76_rhempenhofolha"])){ 
       $sql  .= $virgula." rh76_rhempenhofolha = $this->rh76_rhempenhofolha ";
       $virgula = ",";
       if(trim($this->rh76_rhempenhofolha) == null ){ 
         $this->erro_sql = " Campo Empenho da Folha nao Informado.";
         $this->erro_campo = "rh76_rhempenhofolha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh76_numemp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh76_numemp"])){ 
       $sql  .= $virgula." rh76_numemp = $this->rh76_numemp ";
       $virgula = ",";
       if(trim($this->rh76_numemp) == null ){ 
         $this->erro_sql = " Campo Número do Empenho nao Informado.";
         $this->erro_campo = "rh76_numemp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh76_sequencial!=null){
       $sql .= " rh76_sequencial = $this->rh76_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh76_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14393,'$this->rh76_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh76_sequencial"]) || $this->rh76_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2538,14393,'".AddSlashes(pg_result($resaco,$conresaco,'rh76_sequencial'))."','$this->rh76_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh76_rhempenhofolha"]) || $this->rh76_rhempenhofolha != "")
           $resac = db_query("insert into db_acount values($acount,2538,14394,'".AddSlashes(pg_result($resaco,$conresaco,'rh76_rhempenhofolha'))."','$this->rh76_rhempenhofolha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh76_numemp"]) || $this->rh76_numemp != "")
           $resac = db_query("insert into db_acount values($acount,2538,14395,'".AddSlashes(pg_result($resaco,$conresaco,'rh76_numemp'))."','$this->rh76_numemp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Empenhos da folha nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh76_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Empenhos da folha nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh76_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh76_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh76_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh76_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14393,'$rh76_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2538,14393,'','".AddSlashes(pg_result($resaco,$iresaco,'rh76_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2538,14394,'','".AddSlashes(pg_result($resaco,$iresaco,'rh76_rhempenhofolha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2538,14395,'','".AddSlashes(pg_result($resaco,$iresaco,'rh76_numemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhempenhofolhaempenho
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh76_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh76_sequencial = $rh76_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Empenhos da folha nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh76_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Empenhos da folha nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh76_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh76_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhempenhofolhaempenho";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh76_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhempenhofolhaempenho ";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = rhempenhofolhaempenho.rh76_numemp";
     $sql .= "      inner join rhempenhofolha  on  rhempenhofolha.rh72_sequencial = rhempenhofolhaempenho.rh76_rhempenhofolha";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
     $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
     $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = empempenho.e60_codcom";
     $sql .= "      inner join emptipo  on  emptipo.e41_codtipo = empempenho.e60_codtipo";
     $sql .= "      inner join concarpeculiar  on  concarpeculiar.c58_sequencial = empempenho.e60_concarpeculiar";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = rhempenhofolha.rh72_recurso";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = rhempenhofolha.rh72_codele and  orcelemento.o56_anousu = rhempenhofolha.rh72_anousu";
     $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = rhempenhofolha.rh72_anousu and  orcprojativ.o55_projativ = rhempenhofolha.rh72_projativ";
     $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = rhempenhofolha.rh72_anousu and  orcunidade.o41_orgao = rhempenhofolha.rh72_orgao and  orcunidade.o41_unidade = rhempenhofolha.rh72_unidade";
     $sql .= "      inner join orcdotacao  as a on   a.o58_anousu = rhempenhofolha.rh72_coddot and   a.o58_coddot = rhempenhofolha.rh72_anousu";
     $sql2 = "";
     if($dbwhere==""){
       if($rh76_sequencial!=null ){
         $sql2 .= " where rhempenhofolhaempenho.rh76_sequencial = $rh76_sequencial "; 
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
   function sql_query_file ( $rh76_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhempenhofolhaempenho ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh76_sequencial!=null ){
         $sql2 .= " where rhempenhofolhaempenho.rh76_sequencial = $rh76_sequencial "; 
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