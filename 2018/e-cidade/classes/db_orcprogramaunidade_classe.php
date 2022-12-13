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

//MODULO: orcamento
//CLASSE DA ENTIDADE orcprogramaunidade
class cl_orcprogramaunidade { 
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
   var $o14_sequencial = 0; 
   var $o14_orcunidade = 0; 
   var $o14_orgao = 0; 
   var $o14_orcprograma = 0; 
   var $o14_anousu = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o14_sequencial = int4 = Sequencial 
                 o14_orcunidade = int4 = Unidade 
                 o14_orgao = int4 = Orgão 
                 o14_orcprograma = int4 = Programa 
                 o14_anousu = int4 = Exercício 
                 ";
   //funcao construtor da classe 
   function cl_orcprogramaunidade() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcprogramaunidade"); 
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
       $this->o14_sequencial = ($this->o14_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o14_sequencial"]:$this->o14_sequencial);
       $this->o14_orcunidade = ($this->o14_orcunidade == ""?@$GLOBALS["HTTP_POST_VARS"]["o14_orcunidade"]:$this->o14_orcunidade);
       $this->o14_orgao = ($this->o14_orgao == ""?@$GLOBALS["HTTP_POST_VARS"]["o14_orgao"]:$this->o14_orgao);
       $this->o14_orcprograma = ($this->o14_orcprograma == ""?@$GLOBALS["HTTP_POST_VARS"]["o14_orcprograma"]:$this->o14_orcprograma);
       $this->o14_anousu = ($this->o14_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o14_anousu"]:$this->o14_anousu);
     }else{
       $this->o14_sequencial = ($this->o14_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o14_sequencial"]:$this->o14_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($o14_sequencial){ 
      $this->atualizacampos();
     if($this->o14_orcunidade == null ){ 
       $this->erro_sql = " Campo Unidade nao Informado.";
       $this->erro_campo = "o14_orcunidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o14_orgao == null ){ 
       $this->erro_sql = " Campo Orgão nao Informado.";
       $this->erro_campo = "o14_orgao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o14_orcprograma == null ){ 
       $this->erro_sql = " Campo Programa nao Informado.";
       $this->erro_campo = "o14_orcprograma";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o14_anousu == null ){ 
       $this->erro_sql = " Campo Exercício nao Informado.";
       $this->erro_campo = "o14_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o14_sequencial == "" || $o14_sequencial == null ){
       $result = db_query("select nextval('orcprogramaunidade_o14_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orcprogramaunidade_o14_sequencial_seq do campo: o14_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o14_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from orcprogramaunidade_o14_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o14_sequencial)){
         $this->erro_sql = " Campo o14_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o14_sequencial = $o14_sequencial; 
       }
     }
     if(($this->o14_sequencial == null) || ($this->o14_sequencial == "") ){ 
       $this->erro_sql = " Campo o14_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcprogramaunidade(
                                       o14_sequencial 
                                      ,o14_orcunidade 
                                      ,o14_orgao 
                                      ,o14_orcprograma 
                                      ,o14_anousu 
                       )
                values (
                                $this->o14_sequencial 
                               ,$this->o14_orcunidade 
                               ,$this->o14_orgao 
                               ,$this->o14_orcprograma 
                               ,$this->o14_anousu 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "orcprogramaunidade ($this->o14_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "orcprogramaunidade já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "orcprogramaunidade ($this->o14_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o14_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o14_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,13690,'$this->o14_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2393,13690,'','".AddSlashes(pg_result($resaco,0,'o14_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2393,13691,'','".AddSlashes(pg_result($resaco,0,'o14_orcunidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2393,13694,'','".AddSlashes(pg_result($resaco,0,'o14_orgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2393,13692,'','".AddSlashes(pg_result($resaco,0,'o14_orcprograma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2393,13693,'','".AddSlashes(pg_result($resaco,0,'o14_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o14_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update orcprogramaunidade set ";
     $virgula = "";
     if(trim($this->o14_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o14_sequencial"])){ 
       $sql  .= $virgula." o14_sequencial = $this->o14_sequencial ";
       $virgula = ",";
       if(trim($this->o14_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "o14_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o14_orcunidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o14_orcunidade"])){ 
       $sql  .= $virgula." o14_orcunidade = $this->o14_orcunidade ";
       $virgula = ",";
       if(trim($this->o14_orcunidade) == null ){ 
         $this->erro_sql = " Campo Unidade nao Informado.";
         $this->erro_campo = "o14_orcunidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o14_orgao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o14_orgao"])){ 
       $sql  .= $virgula." o14_orgao = $this->o14_orgao ";
       $virgula = ",";
       if(trim($this->o14_orgao) == null ){ 
         $this->erro_sql = " Campo Orgão nao Informado.";
         $this->erro_campo = "o14_orgao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o14_orcprograma)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o14_orcprograma"])){ 
       $sql  .= $virgula." o14_orcprograma = $this->o14_orcprograma ";
       $virgula = ",";
       if(trim($this->o14_orcprograma) == null ){ 
         $this->erro_sql = " Campo Programa nao Informado.";
         $this->erro_campo = "o14_orcprograma";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o14_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o14_anousu"])){ 
       $sql  .= $virgula." o14_anousu = $this->o14_anousu ";
       $virgula = ",";
       if(trim($this->o14_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "o14_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o14_sequencial!=null){
       $sql .= " o14_sequencial = $this->o14_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o14_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13690,'$this->o14_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o14_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2393,13690,'".AddSlashes(pg_result($resaco,$conresaco,'o14_sequencial'))."','$this->o14_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o14_orcunidade"]))
           $resac = db_query("insert into db_acount values($acount,2393,13691,'".AddSlashes(pg_result($resaco,$conresaco,'o14_orcunidade'))."','$this->o14_orcunidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o14_orgao"]))
           $resac = db_query("insert into db_acount values($acount,2393,13694,'".AddSlashes(pg_result($resaco,$conresaco,'o14_orgao'))."','$this->o14_orgao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o14_orcprograma"]))
           $resac = db_query("insert into db_acount values($acount,2393,13692,'".AddSlashes(pg_result($resaco,$conresaco,'o14_orcprograma'))."','$this->o14_orcprograma',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o14_anousu"]))
           $resac = db_query("insert into db_acount values($acount,2393,13693,'".AddSlashes(pg_result($resaco,$conresaco,'o14_anousu'))."','$this->o14_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "orcprogramaunidade nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o14_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "orcprogramaunidade nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o14_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o14_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o14_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o14_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13690,'$o14_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2393,13690,'','".AddSlashes(pg_result($resaco,$iresaco,'o14_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2393,13691,'','".AddSlashes(pg_result($resaco,$iresaco,'o14_orcunidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2393,13694,'','".AddSlashes(pg_result($resaco,$iresaco,'o14_orgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2393,13692,'','".AddSlashes(pg_result($resaco,$iresaco,'o14_orcprograma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2393,13693,'','".AddSlashes(pg_result($resaco,$iresaco,'o14_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcprogramaunidade
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o14_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o14_sequencial = $o14_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "orcprogramaunidade nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o14_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "orcprogramaunidade nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o14_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o14_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcprogramaunidade";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o14_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcprogramaunidade ";
     $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcprogramaunidade.o14_anousu and  orcprograma.o54_programa = orcprogramaunidade.o14_orcprograma";
     $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcprogramaunidade.o14_anousu and  orcunidade.o41_orgao = orcprogramaunidade.o14_orgao and  orcunidade.o41_unidade = orcprogramaunidade.o14_orcunidade";
     $sql .= "      inner join db_config  on   codigo = orcunidade.o41_instit";
     $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcunidade.o41_anousu and  orcorgao.o40_orgao = orcunidade.o41_orgao";
     $sql2 = "";
     if($dbwhere==""){
       if($o14_sequencial!=null ){
         $sql2 .= " where orcprogramaunidade.o14_sequencial = $o14_sequencial "; 
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
   function sql_query_file ( $o14_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcprogramaunidade ";
     $sql2 = "";
     if($dbwhere==""){
       if($o14_sequencial!=null ){
         $sql2 .= " where orcprogramaunidade.o14_sequencial = $o14_sequencial "; 
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