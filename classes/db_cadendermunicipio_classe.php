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

//MODULO: Configuracoes
//CLASSE DA ENTIDADE cadendermunicipio
class cl_cadendermunicipio { 
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
   var $db72_sequencial = 0; 
   var $db72_cadenderestado = 0; 
   var $db72_descricao = null; 
   var $db72_sigla = null; 
   var $db72_cepinicial = null; 
   var $db72_cepfinal = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db72_sequencial = int4 = Código do Município 
                 db72_cadenderestado = int4 = Código do Estado 
                 db72_descricao = varchar(100) = Descrição do Município 
                 db72_sigla = varchar(2) = Sigla do Município 
                 db72_cepinicial = varchar(8) = Cep Inicial 
                 db72_cepfinal = varchar(8) = Cep Final 
                 ";
   //funcao construtor da classe 
   function cl_cadendermunicipio() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cadendermunicipio"); 
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
       $this->db72_sequencial = ($this->db72_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db72_sequencial"]:$this->db72_sequencial);
       $this->db72_cadenderestado = ($this->db72_cadenderestado == ""?@$GLOBALS["HTTP_POST_VARS"]["db72_cadenderestado"]:$this->db72_cadenderestado);
       $this->db72_descricao = ($this->db72_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["db72_descricao"]:$this->db72_descricao);
       $this->db72_sigla = ($this->db72_sigla == ""?@$GLOBALS["HTTP_POST_VARS"]["db72_sigla"]:$this->db72_sigla);
       $this->db72_cepinicial = ($this->db72_cepinicial == ""?@$GLOBALS["HTTP_POST_VARS"]["db72_cepinicial"]:$this->db72_cepinicial);
       $this->db72_cepfinal = ($this->db72_cepfinal == ""?@$GLOBALS["HTTP_POST_VARS"]["db72_cepfinal"]:$this->db72_cepfinal);
     }else{
       $this->db72_sequencial = ($this->db72_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db72_sequencial"]:$this->db72_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($db72_sequencial){ 
      $this->atualizacampos();
     if($this->db72_cadenderestado == null ){ 
       $this->erro_sql = " Campo Código do Estado nao Informado.";
       $this->erro_campo = "db72_cadenderestado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db72_descricao == null ){ 
       $this->erro_sql = " Campo Descrição do Município nao Informado.";
       $this->erro_campo = "db72_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db72_sequencial == "" || $db72_sequencial == null ){
       $result = db_query("select nextval('cadendermunicipio_db72_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cadendermunicipio_db72_sequencial_seq do campo: db72_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db72_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cadendermunicipio_db72_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db72_sequencial)){
         $this->erro_sql = " Campo db72_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db72_sequencial = $db72_sequencial; 
       }
     }
     if(($this->db72_sequencial == null) || ($this->db72_sequencial == "") ){ 
       $this->erro_sql = " Campo db72_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cadendermunicipio(
                                       db72_sequencial 
                                      ,db72_cadenderestado 
                                      ,db72_descricao 
                                      ,db72_sigla 
                                      ,db72_cepinicial 
                                      ,db72_cepfinal 
                       )
                values (
                                $this->db72_sequencial 
                               ,$this->db72_cadenderestado 
                               ,'$this->db72_descricao' 
                               ,'$this->db72_sigla' 
                               ,'$this->db72_cepinicial' 
                               ,'$this->db72_cepfinal' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de Municipio ($this->db72_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de Municipio já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de Municipio ($this->db72_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db72_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db72_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15847,'$this->db72_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2781,15847,'','".AddSlashes(pg_result($resaco,0,'db72_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2781,15848,'','".AddSlashes(pg_result($resaco,0,'db72_cadenderestado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2781,15849,'','".AddSlashes(pg_result($resaco,0,'db72_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2781,15850,'','".AddSlashes(pg_result($resaco,0,'db72_sigla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2781,15851,'','".AddSlashes(pg_result($resaco,0,'db72_cepinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2781,15852,'','".AddSlashes(pg_result($resaco,0,'db72_cepfinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db72_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cadendermunicipio set ";
     $virgula = "";
     if(trim($this->db72_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db72_sequencial"])){ 
       $sql  .= $virgula." db72_sequencial = $this->db72_sequencial ";
       $virgula = ",";
       if(trim($this->db72_sequencial) == null ){ 
         $this->erro_sql = " Campo Código do Município nao Informado.";
         $this->erro_campo = "db72_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db72_cadenderestado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db72_cadenderestado"])){ 
       $sql  .= $virgula." db72_cadenderestado = $this->db72_cadenderestado ";
       $virgula = ",";
       if(trim($this->db72_cadenderestado) == null ){ 
         $this->erro_sql = " Campo Código do Estado nao Informado.";
         $this->erro_campo = "db72_cadenderestado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db72_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db72_descricao"])){ 
       $sql  .= $virgula." db72_descricao = '$this->db72_descricao' ";
       $virgula = ",";
       if(trim($this->db72_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição do Município nao Informado.";
         $this->erro_campo = "db72_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db72_sigla)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db72_sigla"])){ 
       $sql  .= $virgula." db72_sigla = '$this->db72_sigla' ";
       $virgula = ",";
     }
     if(trim($this->db72_cepinicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db72_cepinicial"])){ 
       $sql  .= $virgula." db72_cepinicial = '$this->db72_cepinicial' ";
       $virgula = ",";
     }
     if(trim($this->db72_cepfinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db72_cepfinal"])){ 
       $sql  .= $virgula." db72_cepfinal = '$this->db72_cepfinal' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($db72_sequencial!=null){
       $sql .= " db72_sequencial = $this->db72_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db72_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15847,'$this->db72_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db72_sequencial"]) || $this->db72_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2781,15847,'".AddSlashes(pg_result($resaco,$conresaco,'db72_sequencial'))."','$this->db72_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db72_cadenderestado"]) || $this->db72_cadenderestado != "")
           $resac = db_query("insert into db_acount values($acount,2781,15848,'".AddSlashes(pg_result($resaco,$conresaco,'db72_cadenderestado'))."','$this->db72_cadenderestado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db72_descricao"]) || $this->db72_descricao != "")
           $resac = db_query("insert into db_acount values($acount,2781,15849,'".AddSlashes(pg_result($resaco,$conresaco,'db72_descricao'))."','$this->db72_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db72_sigla"]) || $this->db72_sigla != "")
           $resac = db_query("insert into db_acount values($acount,2781,15850,'".AddSlashes(pg_result($resaco,$conresaco,'db72_sigla'))."','$this->db72_sigla',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db72_cepinicial"]) || $this->db72_cepinicial != "")
           $resac = db_query("insert into db_acount values($acount,2781,15851,'".AddSlashes(pg_result($resaco,$conresaco,'db72_cepinicial'))."','$this->db72_cepinicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db72_cepfinal"]) || $this->db72_cepfinal != "")
           $resac = db_query("insert into db_acount values($acount,2781,15852,'".AddSlashes(pg_result($resaco,$conresaco,'db72_cepfinal'))."','$this->db72_cepfinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Municipio nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db72_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Municipio nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db72_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db72_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db72_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db72_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15847,'$db72_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2781,15847,'','".AddSlashes(pg_result($resaco,$iresaco,'db72_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2781,15848,'','".AddSlashes(pg_result($resaco,$iresaco,'db72_cadenderestado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2781,15849,'','".AddSlashes(pg_result($resaco,$iresaco,'db72_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2781,15850,'','".AddSlashes(pg_result($resaco,$iresaco,'db72_sigla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2781,15851,'','".AddSlashes(pg_result($resaco,$iresaco,'db72_cepinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2781,15852,'','".AddSlashes(pg_result($resaco,$iresaco,'db72_cepfinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cadendermunicipio
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db72_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db72_sequencial = $db72_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Municipio nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db72_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Municipio nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db72_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db72_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cadendermunicipio";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $db72_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadendermunicipio ";
     $sql .= "      inner join cadenderestado  on  cadenderestado.db71_sequencial = cadendermunicipio.db72_cadenderestado";
     $sql .= "      inner join cadenderpais  on  cadenderpais.db70_sequencial = cadenderestado.db71_cadenderpais";
     $sql2 = "";
     if($dbwhere==""){
       if($db72_sequencial!=null ){
         $sql2 .= " where cadendermunicipio.db72_sequencial = $db72_sequencial "; 
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
   function sql_query_file ( $db72_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadendermunicipio ";
     $sql2 = "";
     if($dbwhere==""){
       if($db72_sequencial!=null ){
         $sql2 .= " where cadendermunicipio.db72_sequencial = $db72_sequencial "; 
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