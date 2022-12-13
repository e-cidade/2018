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
//CLASSE DA ENTIDADE endereco
class cl_endereco { 
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
   var $db76_sequencial = 0; 
   var $db76_cadenderlocal = 0; 
   var $db76_complemento = null; 
   var $db76_caixapostal = null; 
   var $db76_loteamento = null; 
   var $db76_condominio = null; 
   var $db76_pontoref = null; 
   var $db76_cep = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db76_sequencial = int4 = Código do Endereço 
                 db76_cadenderlocal = int4 = Código do Local 
                 db76_complemento = varchar(100) = Complemento 
                 db76_caixapostal = varchar(50) = Caixa Postal 
                 db76_loteamento = varchar(100) = Loteamento 
                 db76_condominio = varchar(100) = Condomínio 
                 db76_pontoref = text = Ponto de Referência 
                 db76_cep = char(8) = Cep 
                 ";
   //funcao construtor da classe 
   function cl_endereco() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("endereco"); 
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
       $this->db76_sequencial = ($this->db76_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db76_sequencial"]:$this->db76_sequencial);
       $this->db76_cadenderlocal = ($this->db76_cadenderlocal == ""?@$GLOBALS["HTTP_POST_VARS"]["db76_cadenderlocal"]:$this->db76_cadenderlocal);
       $this->db76_complemento = ($this->db76_complemento == ""?@$GLOBALS["HTTP_POST_VARS"]["db76_complemento"]:$this->db76_complemento);
       $this->db76_caixapostal = ($this->db76_caixapostal == ""?@$GLOBALS["HTTP_POST_VARS"]["db76_caixapostal"]:$this->db76_caixapostal);
       $this->db76_loteamento = ($this->db76_loteamento == ""?@$GLOBALS["HTTP_POST_VARS"]["db76_loteamento"]:$this->db76_loteamento);
       $this->db76_condominio = ($this->db76_condominio == ""?@$GLOBALS["HTTP_POST_VARS"]["db76_condominio"]:$this->db76_condominio);
       $this->db76_pontoref = ($this->db76_pontoref == ""?@$GLOBALS["HTTP_POST_VARS"]["db76_pontoref"]:$this->db76_pontoref);
       $this->db76_cep = ($this->db76_cep == ""?@$GLOBALS["HTTP_POST_VARS"]["db76_cep"]:$this->db76_cep);
     }else{
       $this->db76_sequencial = ($this->db76_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db76_sequencial"]:$this->db76_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($db76_sequencial){ 
      $this->atualizacampos();
     if($this->db76_cadenderlocal == null ){ 
       $this->erro_sql = " Campo Código do Local nao Informado.";
       $this->erro_campo = "db76_cadenderlocal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db76_sequencial == "" || $db76_sequencial == null ){
       $result = db_query("select nextval('endereco_db76_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: endereco_db76_sequencial_seq do campo: db76_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db76_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from endereco_db76_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db76_sequencial)){
         $this->erro_sql = " Campo db76_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db76_sequencial = $db76_sequencial; 
       }
     }
     if(($this->db76_sequencial == null) || ($this->db76_sequencial == "") ){ 
       $this->erro_sql = " Campo db76_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into endereco(
                                       db76_sequencial 
                                      ,db76_cadenderlocal 
                                      ,db76_complemento 
                                      ,db76_caixapostal 
                                      ,db76_loteamento 
                                      ,db76_condominio 
                                      ,db76_pontoref 
                                      ,db76_cep 
                       )
                values (
                                $this->db76_sequencial 
                               ,$this->db76_cadenderlocal 
                               ,'$this->db76_complemento' 
                               ,'$this->db76_caixapostal' 
                               ,'$this->db76_loteamento' 
                               ,'$this->db76_condominio' 
                               ,'$this->db76_pontoref' 
                               ,'$this->db76_cep' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de Endereços ($this->db76_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de Endereços já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de Endereços ($this->db76_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db76_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db76_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15869,'$this->db76_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2786,15869,'','".AddSlashes(pg_result($resaco,0,'db76_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2786,15870,'','".AddSlashes(pg_result($resaco,0,'db76_cadenderlocal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2786,15871,'','".AddSlashes(pg_result($resaco,0,'db76_complemento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2786,15872,'','".AddSlashes(pg_result($resaco,0,'db76_caixapostal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2786,15873,'','".AddSlashes(pg_result($resaco,0,'db76_loteamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2786,15874,'','".AddSlashes(pg_result($resaco,0,'db76_condominio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2786,15875,'','".AddSlashes(pg_result($resaco,0,'db76_pontoref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2786,16110,'','".AddSlashes(pg_result($resaco,0,'db76_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db76_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update endereco set ";
     $virgula = "";
     if(trim($this->db76_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db76_sequencial"])){ 
       $sql  .= $virgula." db76_sequencial = $this->db76_sequencial ";
       $virgula = ",";
       if(trim($this->db76_sequencial) == null ){ 
         $this->erro_sql = " Campo Código do Endereço nao Informado.";
         $this->erro_campo = "db76_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db76_cadenderlocal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db76_cadenderlocal"])){ 
       $sql  .= $virgula." db76_cadenderlocal = $this->db76_cadenderlocal ";
       $virgula = ",";
       if(trim($this->db76_cadenderlocal) == null ){ 
         $this->erro_sql = " Campo Código do Local nao Informado.";
         $this->erro_campo = "db76_cadenderlocal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db76_complemento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db76_complemento"])){ 
       $sql  .= $virgula." db76_complemento = '$this->db76_complemento' ";
       $virgula = ",";
     }
     if(trim($this->db76_caixapostal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db76_caixapostal"])){ 
       $sql  .= $virgula." db76_caixapostal = '$this->db76_caixapostal' ";
       $virgula = ",";
     }
     if(trim($this->db76_loteamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db76_loteamento"])){ 
       $sql  .= $virgula." db76_loteamento = '$this->db76_loteamento' ";
       $virgula = ",";
     }
     if(trim($this->db76_condominio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db76_condominio"])){ 
       $sql  .= $virgula." db76_condominio = '$this->db76_condominio' ";
       $virgula = ",";
     }
     if(trim($this->db76_pontoref)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db76_pontoref"])){ 
       $sql  .= $virgula." db76_pontoref = '$this->db76_pontoref' ";
       $virgula = ",";
     }
     if(trim($this->db76_cep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db76_cep"])){ 
       $sql  .= $virgula." db76_cep = '$this->db76_cep' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($db76_sequencial!=null){
       $sql .= " db76_sequencial = $this->db76_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db76_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15869,'$this->db76_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db76_sequencial"]) || $this->db76_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2786,15869,'".AddSlashes(pg_result($resaco,$conresaco,'db76_sequencial'))."','$this->db76_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db76_cadenderlocal"]) || $this->db76_cadenderlocal != "")
           $resac = db_query("insert into db_acount values($acount,2786,15870,'".AddSlashes(pg_result($resaco,$conresaco,'db76_cadenderlocal'))."','$this->db76_cadenderlocal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db76_complemento"]) || $this->db76_complemento != "")
           $resac = db_query("insert into db_acount values($acount,2786,15871,'".AddSlashes(pg_result($resaco,$conresaco,'db76_complemento'))."','$this->db76_complemento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db76_caixapostal"]) || $this->db76_caixapostal != "")
           $resac = db_query("insert into db_acount values($acount,2786,15872,'".AddSlashes(pg_result($resaco,$conresaco,'db76_caixapostal'))."','$this->db76_caixapostal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db76_loteamento"]) || $this->db76_loteamento != "")
           $resac = db_query("insert into db_acount values($acount,2786,15873,'".AddSlashes(pg_result($resaco,$conresaco,'db76_loteamento'))."','$this->db76_loteamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db76_condominio"]) || $this->db76_condominio != "")
           $resac = db_query("insert into db_acount values($acount,2786,15874,'".AddSlashes(pg_result($resaco,$conresaco,'db76_condominio'))."','$this->db76_condominio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db76_pontoref"]) || $this->db76_pontoref != "")
           $resac = db_query("insert into db_acount values($acount,2786,15875,'".AddSlashes(pg_result($resaco,$conresaco,'db76_pontoref'))."','$this->db76_pontoref',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db76_cep"]) || $this->db76_cep != "")
           $resac = db_query("insert into db_acount values($acount,2786,16110,'".AddSlashes(pg_result($resaco,$conresaco,'db76_cep'))."','$this->db76_cep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Endereços nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db76_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Endereços nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db76_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db76_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db76_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db76_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15869,'$db76_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2786,15869,'','".AddSlashes(pg_result($resaco,$iresaco,'db76_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2786,15870,'','".AddSlashes(pg_result($resaco,$iresaco,'db76_cadenderlocal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2786,15871,'','".AddSlashes(pg_result($resaco,$iresaco,'db76_complemento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2786,15872,'','".AddSlashes(pg_result($resaco,$iresaco,'db76_caixapostal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2786,15873,'','".AddSlashes(pg_result($resaco,$iresaco,'db76_loteamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2786,15874,'','".AddSlashes(pg_result($resaco,$iresaco,'db76_condominio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2786,15875,'','".AddSlashes(pg_result($resaco,$iresaco,'db76_pontoref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2786,16110,'','".AddSlashes(pg_result($resaco,$iresaco,'db76_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from endereco
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db76_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db76_sequencial = $db76_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Endereços nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db76_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Endereços nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db76_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db76_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:endereco";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $db76_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from endereco ";
     $sql .= "      inner join cadenderlocal  on  cadenderlocal.db75_sequencial = endereco.db76_cadenderlocal";
     $sql .= "      inner join cadenderbairrocadenderrua  on  cadenderbairrocadenderrua.db87_sequencial = cadenderlocal.db75_cadenderbairrocadenderrua";
     $sql2 = "";
     if($dbwhere==""){
       if($db76_sequencial!=null ){
         $sql2 .= " where endereco.db76_sequencial = $db76_sequencial "; 
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
   function sql_query_file ( $db76_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from endereco ";
     $sql2 = "";
     if($dbwhere==""){
       if($db76_sequencial!=null ){
         $sql2 .= " where endereco.db76_sequencial = $db76_sequencial "; 
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